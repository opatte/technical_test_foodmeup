<?php

namespace Foodmeup\NutritionBundle\Console;

use Monolog\Logger as Logger;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Foodmeup\NutritionBundle\Tools\ImportCsvFileService;

// use "Query" & "QueryHandler"
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyQuery;
use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientQuery;
use Foodmeup\NutritionBundle\QueryHandler\Family\GetOneFamilyQueryHandler;
use Foodmeup\NutritionBundle\Query\Ingredient\GetOneIngredientContentQuery;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\GetOneIngredientQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Ingredient\GetOneIngredientContentQueryHandler;

// use "Command" & "CommandHandler"
use Foodmeup\NutritionBundle\Command\Ingredient\RegisterIngredientCommand;
use Foodmeup\NutritionBundle\Command\Ingredient\RegisterIngredientContentCommand;
use Foodmeup\NutritionBundle\CommandHandler\Ingredient\RegisterIngredientCommandHandler;
use Foodmeup\NutritionBundle\CommandHandler\Ingredient\RegisterIngredientContentCommandHandler;

// use "AllExceptions"
use Foodmeup\NutritionBundle\Model\Family\FamilyModel;
use Foodmeup\NutritionBundle\Entity\Ingredient\Ingredient;
use Foodmeup\NutritionBundle\Exception\Ingredient\IngredientNotFoundException;
use Foodmeup\NutritionBundle\Exception\Ingredient\IngredientContentNotFoundException;

/**
 * Class ImportIngredientsAndTheirContentsCommand.
 */
class ImportIngredientsAndTheirContentsCommand extends BaseImportDataInDatabaseCommand
{
    /**
     * @var GetOneFamilyQueryHandler
     */
    private $getOneFamilyQueryHandler;

    /**
     * @var GetOneIngredientQueryHandler
     */
    private $getOneIngredientQueryHandler;

    /**
     * @var GetOneIngredientContentQueryHandler
     */
    private $getOneIngredientContentQueryHandler;

    /**
     * @var RegisterIngredientCommandHandler
     */
    private $registerIngredientCommandHandler;

    /**
     * @var RegisterIngredientContentCommandHandler
     */
    private $registerIngredientContentCommandHandler;

    /**
     * @var string The CSV file path (default value)
     */
    private $defaultCSVFilePath = 'reference/ingredients.csv';

    /**
     * @var array
     */
    protected $counters = [
        'csv' => null,
        'ingredients' => ['imported' => 0, 'founded' => 0, 'skipped' => 0, 'error' => 0],
        'ingredient_contents' => ['imported' => 0, 'founded' => 0, 'skipped' => 0, 'error' => 0],
    ];

    /**
     * Constructor.
     *
     * @param GetOneFamilyQueryHandler                  $getOneFamilyQueryHandler
     * @param GetOneIngredientQueryHandler              $getOneIngredientQueryHandler
     * @param GetOneIngredientContentQueryHandler       $getOneIngredientContentQueryHandler
     * @param RegisterIngredientCommandHandler          $registerIngredientCommandHandler
     * @param RegisterIngredientContentCommandHandler   $registerIngredientContentCommandHandler
     * @param ImportCsvFileService                      $importCsvFileService
     * @param Stopwatch                                 $stopwatch
     * @param Logger                                    $logger
     * @param string                                    $name
     */
    public function __construct(GetOneFamilyQueryHandler $getOneFamilyQueryHandler,
                                GetOneIngredientQueryHandler $getOneIngredientQueryHandler,
                                GetOneIngredientContentQueryHandler $getOneIngredientContentQueryHandler,
                                RegisterIngredientCommandHandler $registerIngredientCommandHandler,
                                RegisterIngredientContentCommandHandler $registerIngredientContentCommandHandler,
                                ImportCsvFileService $importCsvFileService, Stopwatch $stopwatch,
                                Logger $logger, $name = null)
    {
        $this->getOneFamilyQueryHandler = $getOneFamilyQueryHandler;
        $this->getOneIngredientQueryHandler = $getOneIngredientQueryHandler;
        $this->getOneIngredientContentQueryHandler = $getOneIngredientContentQueryHandler;

        $this->registerIngredientCommandHandler = $registerIngredientCommandHandler;
        $this->registerIngredientContentCommandHandler = $registerIngredientContentCommandHandler;

        parent::__construct($importCsvFileService, $stopwatch, $logger, $name);
    }

    /**
     * Configures the current command.
     */
    public function configure()
    {
        $this->setName('foodmeup:nutrition:import-ingredients');
        $this->setDescription('Import script - Ingredients');
        $this->setHelp('This command is used to insert the Ingredients into BDD (always keeping the same UUID)');

        $this->addArgument('file_path', InputArgument::OPTIONAL, 'The CSV file path', $this->defaultCSVFilePath);
    }

    /**
     * Execute the console script.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Script begin - "Ingredients import"
        $this->stopwatch->start('foodmeup:nutrition:import-ingredients--stopwatch');

        $filePath = $input->getArgument('file_path');
        $this->importIngredientsAndTheirContents($output, $filePath);

        $this->reportAfterImport($output, 'ingredients');
        $this->reportAfterImport($output, 'ingredient_contents');

        // Script end - "Ingredients import"
        $stopwatchEvent = $this->stopwatch->stop('foodmeup:nutrition:import-ingredients--stopwatch');

        // End Log
        $logComment = $this->getStopWatchReport($stopwatchEvent);
        $output->writeln('');
        $output->writeln(sprintf(
            '<info>%s</info> : <comment>%s</comment>', 'Script - "INGREDIENTS import"', $logComment)
        );
    }

    /**
     * Import the ingredients and their contents.
     *
     * @param OutputInterface $output
     * @param string          $filePath
     */
    private function importIngredientsAndTheirContents(OutputInterface $output, $filePath)
    {
        $data = $this->getCsvDataToImport($filePath, ";");
        $countCsv = count($data) - 1;
        $this->counters['csv'] = $countCsv;

        $progressBar = new ProgressBar($output, $countCsv);
        $progressBar->setFormat('debug');
        $progressBar->start();

        foreach ($data as $lineIndex => $row) {

            // Skip the first line - "HEADER line"
            if ($lineIndex == 0) {
                continue;
            }

            $progressBar->advance();

            // Checks if the CSV line is a valid line to create a "INGREDIENT"
            if ($this->checkCsvLineIsValidForIngredient($row) === false) {
                ++$this->counters['ingredients']['skipped'];
                continue;
            }

            $ingredient = $this->getIngredientByUuid($row['UUID_INGREDIENT']);

            // Checks if the "INGREDIENT" already exists in database
            if ($ingredient === null) {
                try {
                    $family = $this->getFamilyByUuid($row['UUID_FAMILY']);

                    $this->registerIngredient($row, $family);
                    ++$this->counters['ingredients']['imported'];
                } catch (\Exception $exception) {
                    ++$this->counters['ingredients']['error'];
                    continue;
                }
            }

            // Checks if the CSV line is a valid line to create a "INGREDIENT_CONTENT"
            if ($this->checkCsvLineIsValidForIngredientContent($row) === false) {
                ++$this->counters['ingredient_contents']['skipped'];
                continue;
            }

            $ingredientContent = $this->getIngredientContentByIngredientUuidAndIngredientContentUuid(
                $row['UUID_INGREDIENT'], $row['UUID_CONTENT']
            );

            // Checks if the "INGREDIENT_CONTENT" already exists in database
            if ($ingredientContent === null) {
                try {
                    if ($ingredient === null) {
                        $ingredient = $this->getIngredientByUuid($row['UUID_INGREDIENT']);
                    }
                    $this->registerIngredientContent($row, $ingredient);
                    ++$this->counters['ingredient_contents']['imported'];
                } catch (\Exception $exception) {
                    ++$this->counters['ingredient_contents']['error'];
                    continue;
                }
            }
        }

        $progressBar->finish();
    }

    /**
     * Get the family by uuid.
     *
     * @param string $uuid
     *
     * @return mixed
     */
    public function getFamilyByUuid($uuid)
    {
        $familyQuery = new GetOneFamilyQuery($uuid);
        $family = $this->getOneFamilyQueryHandler->handle($familyQuery);

        return $family;
    }

    /**
     * Get the ingredient by uuid.
     *
     * @param string $uuid
     *
     * @return mixed
     */
    public function getIngredientByUuid($uuid)
    {
        $ingredient = null;

        try {
            $ingredientQuery = new GetOneIngredientQuery($uuid);
            $ingredient = $this->getOneIngredientQueryHandler->handle($ingredientQuery);

            ++$this->counters['ingredients']['founded'];
        } catch (IngredientNotFoundException $exception) {
        }

        return $ingredient;
    }

    /**
     * Register - Ingredient.
     *
     * @param array $data
     * @param FamilyModel $family
     */
    public function registerIngredient(array $data, FamilyModel $family)
    {
        $ingredientCommand = new RegisterIngredientCommand($data['UUID_INGREDIENT']);
        $ingredientCommand->setOrigfdcd($data['ORIGFDCD']);
        $ingredientCommand->setFamily($family);

        $this->registerIngredientCommandHandler->handle($ingredientCommand);
    }

    /**
     * Get the ingredient content by ingredient uuid and ingredient content uuid.
     *
     * @param string $ingredientUuid
     * @param string $uuid
     *
     * @return mixed
     */
    public function getIngredientContentByIngredientUuidAndIngredientContentUuid($ingredientUuid, $uuid)
    {
        $ingredientContent = null;

        try {
            $ingredientContentQuery = new GetOneIngredientContentQuery($ingredientUuid, $uuid);
            $ingredientContent = $this->getOneIngredientContentQueryHandler->handle($ingredientContentQuery);

            ++$this->counters['ingredient_contents']['founded'];
        } catch (IngredientContentNotFoundException $exception) {
        }

        return $ingredientContent;
    }

    /**
     * Register - Ingredient Content.
     *
     * @param array $data
     * @param Ingredient $ingredient
     */
    private function registerIngredientContent(array $data, Ingredient $ingredient)
    {
        $ingredientContentCommand = new RegisterIngredientContentCommand($data['UUID_CONTENT'], $ingredient);
        $ingredientContentCommand->setLang($data['LANG']);
        $ingredientContentCommand->setName($data['ORIGFDNM']);

        $this->registerIngredientContentCommandHandler->handle($ingredientContentCommand);
    }

    /**
     * Check if the CSV line is a valid line for a ingredient.
     *
     * @param array $line
     *
     * @return bool Return TRUE if valid or FALSE
     */
    private function checkCsvLineIsValidForIngredient(array $line)
    {
        # Check if the required fields
        if (empty($line['UUID_FAMILY']) || empty($line['UUID_INGREDIENT']) || empty($line['ORIGFDCD'])) {
            return false;
        }

        return true;
    }

    /**
     * Check if the CSV line is a valid line for a ingredient content.
     *
     * @param array $line
     *
     * @return bool Return TRUE if valid or FALSE
     */
    private function checkCsvLineIsValidForIngredientContent(array $line)
    {
        # Check if the required fields
        if (empty($line['UUID_INGREDIENT']) || empty($line['UUID_CONTENT']) || empty($line['LANG'])
            || empty($line['ORIGFDNM'])) {
            return false;
        }

        if (!in_array($line['LANG'], $this->languages)) {
            return false;
        }

        return true;
    }
}

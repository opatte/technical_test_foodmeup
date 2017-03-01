<?php

namespace Foodmeup\NutritionBundle\Console;

use Monolog\Logger as Logger;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Foodmeup\NutritionBundle\Tools\ImportCsvFileService;

// use "Entities" & "AllExceptions"
use Foodmeup\NutritionBundle\Entity\Family\Family;
use Foodmeup\NutritionBundle\Exception\Family\FamilyNotFoundException;
use Foodmeup\NutritionBundle\Exception\Family\FamilyContentNotFoundException;

// use "Query" & "QueryHandler"
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyQuery;
use Foodmeup\NutritionBundle\Query\Family\GetOneFamilyContentQuery;
use Foodmeup\NutritionBundle\QueryHandler\Family\GetOneFamilyQueryHandler;
use Foodmeup\NutritionBundle\QueryHandler\Family\GetOneFamilyContentQueryHandler;

// use "Command" & "CommandHandler"
use Foodmeup\NutritionBundle\Command\Family\RegisterFamilyCommand;
use Foodmeup\NutritionBundle\Command\Family\RegisterFamilyContentCommand;
use Foodmeup\NutritionBundle\CommandHandler\Family\RegisterFamilyCommandHandler;
use Foodmeup\NutritionBundle\CommandHandler\Family\RegisterFamilyContentCommandHandler;

/**
 * Class ImportFamiliesAndTheirContentsCommand.
 */
class ImportFamiliesAndTheirContentsCommand extends BaseImportDataInDatabaseCommand
{
    /**
     * @var GetOneFamilyQueryHandler
     */
    private $getOneFamilyQueryHandler;

    /**
     * @var GetOneFamilyContentQueryHandler
     */
    private $getOneFamilyContentQueryHandler;

    /**
     * @var RegisterFamilyCommandHandler
     */
    private $registerFamilyCommandHandler;

    /**
     * @var RegisterFamilyContentCommandHandler
     */
    private $registerFamilyContentCommandHandler;

    /**
     * @var string The CSV file path (default value)
     */
    private $defaultCSVFilePath = 'reference/ingredient_families.csv';

    /**
     * @var array
     */
    protected $counters = [
        'csv' => null,
        'families' => ['imported' => 0, 'founded' => 0, 'skipped' => 0, 'error' => 0],
        'family_contents' => ['imported' => 0, 'founded' => 0, 'skipped' => 0, 'error' => 0],
    ];

    /**
     * Constructor.
     *
     * @param GetOneFamilyQueryHandler                  $getOneFamilyQueryHandler
     * @param GetOneFamilyContentQueryHandler           $getOneFamilyContentQueryHandler
     * @param RegisterFamilyCommandHandler              $registerFamilyCommandHandler
     * @param RegisterFamilyContentCommandHandler       $registerFamilyContentCommandHandler
     * @param ImportCsvFileService                      $importCsvFileService
     * @param Stopwatch                                 $stopwatch
     * @param Logger                                    $logger
     * @param string                                    $name
     */
    public function __construct(GetOneFamilyQueryHandler $getOneFamilyQueryHandler,
                                GetOneFamilyContentQueryHandler $getOneFamilyContentQueryHandler,
                                RegisterFamilyCommandHandler $registerFamilyCommandHandler,
                                RegisterFamilyContentCommandHandler $registerFamilyContentCommandHandler,
                                ImportCsvFileService $importCsvFileService, Stopwatch $stopwatch,
                                Logger $logger, $name = null)
    {
        $this->getOneFamilyQueryHandler = $getOneFamilyQueryHandler;
        $this->getOneFamilyContentQueryHandler = $getOneFamilyContentQueryHandler;

        $this->registerFamilyCommandHandler = $registerFamilyCommandHandler;
        $this->registerFamilyContentCommandHandler = $registerFamilyContentCommandHandler;

        parent::__construct($importCsvFileService, $stopwatch, $logger, $name);
    }

    /**
     * Configures the current command.
     */
    public function configure()
    {
        $this->setName('foodmeup:nutrition:import-ingredient-families');
        $this->setDescription('Import script - Ingredient families');
        $this->setHelp('This command is used to insert the ingredient families into BDD (always keeping the same UUID)');

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
        // Script begin - "Ingredient families import"
        $this->stopwatch->start('foodmeup:nutrition:import-ingredient-families--stopwatch');

        $filePath = $input->getArgument('file_path');
        $this->importFamiliesAndTheirContents($output, $filePath);

        $this->reportAfterImport($output, 'families');
        $this->reportAfterImport($output, 'family_contents');

        // Script end - "Ingredient families import"
        $stopwatchEvent = $this->stopwatch->stop('foodmeup:nutrition:import-ingredient-families--stopwatch');

        // End Log
        $logComment = $this->getStopWatchReport($stopwatchEvent);
        $output->writeln('');
        $output->writeln(sprintf(
            '<info>%s</info> : <comment>%s</comment>', 'Script - "Ingredient families import"', $logComment)
        );
    }

    /**
     * Import the families and their contents.
     *
     * @param OutputInterface $output
     * @param string          $filePath
     */
    private function importFamiliesAndTheirContents(OutputInterface $output, $filePath)
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

            // Checks if the CSV line is a valid line to create a "FAMILY"
            if ($this->checkCsvLineIsValidForFamily($row) === false) {
                ++$this->counters['families']['skipped'];
                continue;
            }

            $family = $this->getFamilyByUuid($row['UUID_FAMILY']);

            // Checks if the "FAMILY" already exists in database
            if ($family === null) {
                try {
                    $this->registerFamily($row);
                    ++$this->counters['families']['imported'];
                } catch (\Exception $exception) {
                    ++$this->counters['families']['error'];
                    continue;
                }
            }

            // Checks if the CSV line is a valid line to create a "FAMILY_CONTENT"
            if ($this->checkCsvLineIsValidForFamilyContent($row) === false) {
                ++$this->counters['family_contents']['skipped'];
                continue;
            }

            $familyContent = $this->getFamilyContentByFamilyUuidAndFamilyContentUuid(
                $row['UUID_FAMILY'], $row['UUID_CONTENT']
            );

            // Checks if the "FAMILY_CONTENT" already exists in database
            if ($familyContent === null) {
                try {
                    if ($family === null) {
                        $family = $this->getFamilyByUuid($row['UUID_FAMILY']);
                    }
                    $this->registerFamilyContent($row, $family);
                    ++$this->counters['family_contents']['imported'];
                } catch (\Exception $exception) {
                    ++$this->counters['family_contents']['error'];
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
        $family = null;

        try {
            $familyQuery = new GetOneFamilyQuery($uuid);
            $family = $this->getOneFamilyQueryHandler->handle($familyQuery);
        } catch (FamilyNotFoundException $exception) {
        }

        return $family;
    }

    /**
     * Register - Family.
     *
     * @param array $data
     */
    public function registerFamily(array $data)
    {
        $familyCommand = new RegisterFamilyCommand($data['UUID_FAMILY']);
        $familyCommand->setOriggpcd($data['ORIGGPCD']);

        $this->registerFamilyCommandHandler->handle($familyCommand);
    }

    /**
     * Get the family content by family uuid and family content uuid.
     *
     * @param string $familyUuid
     * @param string $uuid
     *
     * @return mixed
     */
    public function getFamilyContentByFamilyUuidAndFamilyContentUuid($familyUuid, $uuid)
    {
        $familyContent = null;

        try {
            $familyContentQuery = new GetOneFamilyContentQuery($familyUuid, $uuid);
            $familyContent = $this->getOneFamilyContentQueryHandler->handle($familyContentQuery);

            ++$this->counters['family_contents']['founded'];
        } catch (FamilyContentNotFoundException $exception) {
        }

        return $familyContent;
    }

    /**
     * Register - Family Content.
     *
     * @param array      $data
     * @param Family $family
     */
    private function registerFamilyContent(array $data, Family $family)
    {
        $familyContentCommand = new RegisterFamilyContentCommand($data['UUID_CONTENT'], $family);
        $familyContentCommand->setLang($data['LANG']);
        $familyContentCommand->setName($data['ORIGGPFR']);

        $this->registerFamilyContentCommandHandler->handle($familyContentCommand);
    }

    /**
     * Check if the CSV line is a valid line for a family.
     *
     * @param array $line
     *
     * @return bool Return TRUE if valid or FALSE
     */
    private function checkCsvLineIsValidForFamily(array $line)
    {
        # Check if the required fields
        if (empty($line['UUID_FAMILY']) || empty($line['ORIGGPCD'])) {
            return false;
        }

        return true;
    }

    /**
     * Check if the CSV line is a valid line for a family content.
     *
     * @param array $line
     *
     * @return bool Return TRUE if valid or FALSE
     */
    private function checkCsvLineIsValidForFamilyContent(array $line)
    {
        # Check if the required fields
        if (empty($line['UUID_FAMILY']) || empty($line['UUID_CONTENT']) || empty($line['LANG'])
            || empty($line['ORIGGPFR'])) {
            return false;
        }

        if (!in_array($line['LANG'], $this->languages)) {
            return false;
        }

        return true;
    }
}

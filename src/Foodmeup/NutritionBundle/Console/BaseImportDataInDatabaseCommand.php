<?php

namespace Foodmeup\NutritionBundle\Console;

use Monolog\Logger as Logger;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

use Foodmeup\NutritionBundle\Tools\ImportCsvFileService;

/**
 * Class BaseImportDataInDatabaseCommand.
 */
class BaseImportDataInDatabaseCommand extends Command
{
    const LANG_FR = 'fr_FR';
    const LANG_EN = 'en_US';
    const LANG_ES = 'es_ES';
    const LANG_DE = 'de_DE';
    const LANG_PT = 'pt_PT';

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @var array
     */
    protected $languages = [self::LANG_EN, self::LANG_FR, self::LANG_DE, self::LANG_ES, self::LANG_PT];

    /**
     * @var ImportCsvFileService
     */
    protected $importCsvFileService;

    /**
     * @var array
     */
    protected $counters = [];

    /**
     * Constructor.
     *
     * @param ImportCsvFileService $importCsvFileService
     * @param Stopwatch            $stopwatch
     * @param Logger               $logger
     * @param string               $name
     */
    public function __construct(ImportCsvFileService $importCsvFileService, Stopwatch $stopwatch,
                                Logger $logger, $name = null)
    {
        $this->importCsvFileService = $importCsvFileService;
        $this->stopwatch = $stopwatch;
        $this->logger = $logger;

        parent::__construct($name);
    }

    /**
     * Get the csv data to import.
     *
     * @param string $filePath File path
     * @param string $separator
     *
     * @return \Iterator
     */
    protected function getCsvDataToImport($filePath, $separator = ',')
    {
        $this->importCsvFileService->initCsv($filePath, $separator);

        return $this->importCsvFileService->convertCsvToArray();
    }

    /**
     * Show the report after import in console SF3.
     *
     * @param OutputInterface $output   An OutputInterface instance
     * @param string          $typeData type data (Event or Edition)
     */
    protected function reportAfterImport($output, $typeData)
    {
        $output->writeln('');
        $output->writeln('');
        $output->writeln(sprintf('<info>%s</info>', '--- Report "'.strtoupper($typeData).' import" ---'));

        $output->writeln('');
        $output->writeln(sprintf(
            '<comment>%s</comment>', 'The number of lines in the CSV file ('.$this->counters['csv'].')')
        );

        $output->writeln('');
        $output->writeln(
            '* Number of "'.strtoupper($typeData).'" imported ('.$this->counters[$typeData]['imported'].')'
        );
        $output->writeln(
            '* Number of "'.strtoupper($typeData).'" already exists in DB ('.$this->counters[$typeData]['founded'].')'
        );
        $output->writeln(
            '* Number of "'.strtoupper($typeData).'" skipped ('.$this->counters[$typeData]['skipped'].')'
        );
        $output->writeln(sprintf(
            '<error>%s</error>',
            'The number of lines in error in the CSV file ('.$this->counters[$typeData]['error'].')')
        );
    }

    /**
     * Get the stopwatch - Report.
     *
     * @param StopwatchEvent $stopwatchEvent
     *
     * @return string
     */
    protected function getStopWatchReport(StopwatchEvent $stopwatchEvent)
    {
        $executionTime = $stopwatchEvent->getDuration() / 1000;
        $memoryUsage = $stopwatchEvent->getMemory() / (1024 * 1024);

        $reportStopWatch = sprintf('Execution time: %ss - Memory usage: %s Mo', $executionTime, $memoryUsage);

        return $reportStopWatch;
    }
}

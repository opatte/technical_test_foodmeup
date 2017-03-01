<?php

namespace Foodmeup\NutritionBundle\Tools;

use League\Csv\Reader;

/**
 * Class ImportCsvFileService
 * > REQUIRED: The first line of the CSV is the HEAD.
 */
class ImportCsvFileService
{
    /**
     * @var Reader
     */
    private $csv = null;

    /**
     * Init the CSV class to manipulate file.
     *
     * @param string $filePath  File path to use for import
     * @param string $delimiter Default value ";"
     *
     * @throws \Exception 'Missing required "uuid" parameter'
     *
     * @return $this
     */
    public function initCsv($filePath, $delimiter = ';')
    {
        if ($filePath === null) {
            throw new \DomainException('Missing required "file_path" parameter');
        }

        $this->csv = Reader::createFromPath($filePath);
        $this->csv->setDelimiter($delimiter);

        return $this;
    }

    /**
     * Set delimiter.
     *  > Values authorized: "," OR ";".
     *
     * @param string $delimiter Delimiter (Values authorized: "," OR ";")
     *
     * @throws 'Delimiter is not valid (Values authorized: "," OR ";")'
     * @throws 'Init      the CSV before set delimiter'
     *
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        if (in_array($delimiter, array(',', ';')) === false) {
            throw new \DomainException('Delimiter is not valid (Values authorized: "," OR ";")');
        }

        if ($this->csv === null) {
            throw new \DomainException('Call function "InitCsv" before set delimiter');
        }

        $this->csv->setDelimiter($delimiter);

        return $this;
    }

    /**
     * Convert the CSV to array.
     *
     * @throws 'Init the CSV before to convert in array'
     *
     * @return \Iterator
     */
    public function convertCsvToArray()
    {
        if ($this->csv === null) {
            throw new \DomainException('Init the CSV before to convert in array');
        }

        return $this->csv->fetchAssoc($this->getHeaders());
    }

    /**
     * Get the headers of the CSV.
     *
     * @return array Return the array with the HEAD of the CSV
     */
    private function getHeaders()
    {
        $headers = $this->csv->fetchOne();
        $headers = array_filter($headers, function ($val) {
            return empty($val) ? null : trim($val);
        });

        return $headers;
    }
}

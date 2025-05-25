<?php

namespace Cookbook\Chapter10\SearchEngine\PersistenceHandler;

use SplFileObject;

class CsvHandler implements DataSourceHandlerInterface
{
    private string $filePath;

    public function prepare(array $options): void
    {
        $this->filePath = $options['path'];
    }

    public function executeSearch(array $criteria): array
    {
        $query = $criteria['query'];
        $page = $criteria['page'];
        $pageSize = $criteria['page_size'];
        $data = $this->loadDataFromCsv();

        $filteredResults = array_filter($data, function ($row) use ($query) {
            return stripos(implode(' ', $row), $query) !== false;
        });

        $totalResults = count($filteredResults);
        $start = ($page - 1) * $pageSize;
        $paginatedResults = array_slice($filteredResults, $start, $pageSize);

        return [
            'total_results' => $totalResults,
            'page' => $page,
            'page_size' => $pageSize,
            'results' => $paginatedResults
        ];
    }

    private function loadDataFromCsv(): array
    {
        $records = [];
        $csvFile = new SplFileObject($this->filePath, 'r');

        if (!$csvFile->eof()) {
            $columnHeaders = str_getcsv($csvFile->fgets(), ',', '"', '\\');
        }

        while (!$csvFile->eof()) {
            $rowData = str_getcsv($csvFile->fgets(), ',', '"', '\\');
            if ($rowData && count($rowData) === count($columnHeaders)) {
                $records[] = array_combine($columnHeaders, $rowData);
            }
        }

        return $records;
    }
}
<?php

namespace Cookbook\Chapter10\SearchEngine\BusinessLogic;

use Cookbook\Chapter10\BinarySearch\BinarySearch;
use Cookbook\Chapter10\SearchEngine\PersistenceHandler\DataSourceHandlerInterface;

class Search
{
    private array $result = [];
    private DataSourceHandlerInterface $dataSourceHandler;
    private BinarySearch $binarySearch;

    private array $persistenceSearchResults = [];

    public function __construct(DataSourceHandlerInterface $dataSource, BinarySearch $binarySearch)
    {
        $this->dataSourceHandler = $dataSource;
        $this->binarySearch = $binarySearch;
    }

    public function search(string $query, int $page = 1, int $pageSize = 10): array
    {
        // Can be customised depending on the persistence mechanism available, and moved to a different class.
        $criteria = [
            'query' => $query,
            'page' => $page,
            'page_size' => $pageSize,
        ];

        $this->result = $this->dataSourceHandler->executeSearch($criteria);

        return $this->result;
    }

    public function binarySearchThroughResults(array $criteria): ?array
    {
        $dataSet = $this->result['results'];
        return $this->binarySearch->searchAssociative($dataSet, $criteria);
    }
}
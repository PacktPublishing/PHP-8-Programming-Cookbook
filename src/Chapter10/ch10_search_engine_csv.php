<?php

use Cookbook\Chapter10\SearchEngine\BusinessLogic\Search;
use Cookbook\Chapter10\SearchEngine\PersistenceHandler\CsvHandler;
use Cookbook\Chapter10\BinarySearch\BinarySearch;
use Cookbook\Chapter10\SearchEngine\PersistenceHandler\MysqlHandler;

include __DIR__ . '/../../vendor/autoload.php';

// Dependency
$binarySearch = new BinarySearch();

// Persistence Handler:
$csvHandler = new CsvHandler();
$csvHandler->prepare(['path' => __DIR__ . '/SearchEngine/Data/car_inventory.csv']);

// Business Logic:
$search = new Search($csvHandler, $binarySearch);
$result = $search->search("Mercedes", 1, 10);
print_r($result);

// Result from post-data source search:
$binarySearchResult = $search->binarySearchThroughResults(['Condition' => 'New']);
print_r($binarySearchResult);
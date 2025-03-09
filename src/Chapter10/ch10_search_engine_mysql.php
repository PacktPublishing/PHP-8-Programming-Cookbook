<?php

use Cookbook\Chapter10\SearchEngine\BusinessLogic\Search;
use Cookbook\Chapter10\BinarySearch\BinarySearch;
use Cookbook\Chapter10\SearchEngine\PersistenceHandler\MysqlHandler;

include __DIR__ . '/../../vendor/autoload.php';

// Dependency
$binarySearch = new BinarySearch();

// --- Run MySQL Example:
$mysqlHandler = new MysqlHandler();
$mysqlHandler->prepare(
    ['host' => '127.0.0.1', 'username' => 'chef', 'password' => 'password', 'dbname' => 'cookbook', 'table' => 'cars']
);
$mysqlSearch = new Search($mysqlHandler, $binarySearch);
$mysqlResult = $mysqlSearch->search("Mercedes", 1, 10);
print_r($mysqlResult);

// Result from post-data source search:
$binarySearchResult2 = $mysqlSearch->binarySearchThroughResults(['Condition' => 'New']);
print_r($binarySearchResult2);

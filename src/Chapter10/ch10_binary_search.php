<?php

use Cookbook\Chapter10\BinarySearch\BinarySearch;

include __DIR__ . '/../../vendor/autoload.php';

$binarySearch = new BinarySearch();

// Basic example:
$userIds = [1011, 2022, 3033, 4044, 5055];
$searchUserId = 3033;

echo "=== Binary Search on a single-dimensional array source: ===\n";
$result = $binarySearch->search($userIds, $searchUserId); // Run Search
echo "\tArray position: " . $result . "\n\n";


// Practical example:
$users = [
    "1011" => [
        "first_name" => "Anton",
        "last_name" => "Pavlov",
        "age" => 19,
        "address" => ["street" => "12 Ciambella", "city" => "Rome"],
    ],
    "2022" => [
        "first_name" => "Harel",
        "last_name" => "Kedem",
        "age" => 23,
        "address" => ["street" => "131 Flinders", "city" => "Sydney"],
    ],
    "3033" => [
        "first_name" => "Murali",
        "last_name" => "Ramakrishnan",
        "age" => 21,
        "address" => ["street" => "82 Hawthorn", "city" => "Melbourne"],
    ],
    "4044" => [
        "first_name" => "Kai",
        "last_name" => "Song",
        "age" => 24,
        "address" => ["street" => "782 Tottenham", "city" => "Paris"],
    ],
];

echo "=== Binary Search through a associative array criteria: ===\n";

$searchCriteria = ["first_name" => "Murali", "last_name" => "Ramakrishnan"];
$result = $binarySearch->search($users, $searchCriteria); // Run Search

echo "\nResult: \n";
echo print_r($result, true) . "\n";


<?php
// OOP query builder example
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\QueryBuilder;
use Cookbook\Database\PostCodeTable;
$query = new QueryBuilder(array_keys(PostCodeTable::COLS), PostCodeTable::TABLE);
echo $query->select()->where('admin_name1 = New York')->and('postal_code = 13676')->getSql();
echo PHP_EOL;
$query->sql = '';
echo $query->select()->where('country_code = US')
    ->and()
    ->like('admin_name1', 'New York')
    ->or()->in('post_code', ['10606', '10607', '10610'])
    ->and()->not('accuracy < 3')
    ->limit(10)
    ->offset(20)
    ->getSql();
echo PHP_EOL;

// sample table:
/*
CREATE TABLE `post_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_code` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `place_name` char(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_name1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code1` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_name2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code2` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_name3` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code3` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,4) NOT NULL,
  `longitude` decimal(10,4) NOT NULL,
  `accuracy` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `place_name` (`place_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
*/

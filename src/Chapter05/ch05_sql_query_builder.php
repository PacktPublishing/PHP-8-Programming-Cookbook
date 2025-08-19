<?php
// OOP query builder example
define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
use Cookbook\Services\PostCodeTableFactory;
use Cookbook\Database\QueryBuilder;
$container = Container::getInstance();
$container->add('db_config', function () { return require DB_CONFIG_FN; });
$container->add('db_connect', new ConnectionFactory($container));
$container->add('postcode_table', new PostCodeTableFactory($container));
$postCodeTable = $container->get('postcode_table');
$query = new QueryBuilder($postCodeTable);
echo $query->select()->where('admin_name1 = New YorK')->and('postal_code = 13676')->getSql();
echo PHP_EOL;
echo $query->select()->where()
    ->like('admin_name1', '%New York%')
    ->and('country_code = US')
    ->or('post_code')->in(['10606', '10607', '10610'])
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

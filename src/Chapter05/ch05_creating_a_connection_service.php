<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
$container = Container::getInstance();

define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
$container->add('db_config', function () { return require DB_CONFIG_FN; });
var_dump($container->get('db_config')());
$container->add('db_connect', new ConnectionFactory($container));
$pdo = $container->get('db_connect')();
var_dump($pdo);

<?php
define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
use Cookbook\Services\PostCodeFactory;
$container = Container::getInstance();
$container->add('db_config', function () { return require DB_CONFIG_FN; });
$container->add('db_connect', new ConnectionFactory($container));
$container->add('postcode', new PostCodeFactory($container));
var_dump($container->get('postcode'));

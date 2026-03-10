<?php
define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
use Cookbook\Database\GenAiCache;
use Cookbook\Database\GenAiRequest;
$container = Container::getInstance();
$container->add('db_config', function () { return require DB_CONFIG_FN; });
$container->add('db_connect', new ConnectionFactory($container));
$request = new GenAiRequest(new GenAiCache($container));
$prompt = $argv[1] ?? '';
if (empty($prompt)) {
    exit('Usage:  php ' . basename(__FILE__) . ' "PROMPT"' . PHP_EOL);
}
echo $request($prompt);


<?php
define('DB_CONFIG_FN', __DIR__ . '/../../config/db.config.php');
require __DIR__ . '/../../vendor/autoload.php';
$cols = [];
$fn = __DIR__ . '/../../data/US.txt';   // post code data
use Cookbook\Iterator\LargeFile;
use Cookbook\Database\GenericRow;
use Cookbook\Services\Container;
use Cookbook\Services\ConnectionFactory;
use Cookbook\Services\PostCodeTableFactory;
$container = Container::getInstance();
$container->add('db_config', function () { return require DB_CONFIG_FN; });
$container->add('db_connect', new ConnectionFactory($container));
$container->add('postcode_table', new PostCodeTableFactory($container));
$postCodeTable = $container->get('postcode_table');
$postCodeTable->createTable();  // reset table
$insert = $postCodeTable->buildInsert(TRUE, $cols);    // prepared statement
$largeFile = new Cookbook\Iterator\LargeFile($fn, 'r', "\t", TRUE);
$iterator = $largeFile->fileIteratorCSV();
$genericRow = new GenericRow();
foreach ($iterator as $row) {
    $genericRow->ingestRow($row, $cols);
    echo 'Processing: ' . $genericRow->postal_code . ':' . $genericRow->place_name . PHP_EOL;
    try {
        $insert->execute($genericRow->row);
    } catch (Throwable $t) {
        error_log(basename(__FILE__) . ':' . $t->getMessage());
        echo '***** ERROR ****' . PHP_EOL;
    }
}    
var_dump($postCodeTable->findByPostCode('13676'));
var_dump($postCodeTable->findByCity('Potsdam'));

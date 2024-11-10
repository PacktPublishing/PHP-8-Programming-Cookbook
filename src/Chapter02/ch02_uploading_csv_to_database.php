<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\Names;
use Cookbook\Database\Connect;
use Cookbook\Iterator\LargeFile;
$config = require __DIR__ . '/../../config/db.config.php';
$csv_fn = __DIR__ . '/../../data/names.csv';
$expected = 0;
$actual   = 0;
try {
    $iter = (new LargeFile($csv_fn))->getIterator('CSV');
    $headers = $iter->current();
    $iter->next();
    $rowObj = new Names(Connect::getConnection($config['ch02']));
    $rowObj->createTable();
    $rowObj->buildInsert();
    while ($iter->valid()) {
        $expected++;
        $actual += (int) $rowObj->insert($iter->current());
        $iter->next();
    }
} catch (Throwable $e) {
  echo get_class($e) . ':' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
echo "\nExpected rows to process : $expected";
echo "\nActual rows processed    : $actual\n";

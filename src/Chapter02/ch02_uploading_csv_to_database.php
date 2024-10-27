<?php
require __DIR__ . '/../../vendor/autoload.php';
define('DB_CONFIG_FILE', '/../data/config/db.config.php');
define('CSV_FILE', '/../data/files/prospects.csv');
require __DIR__ . '/../../vendor/autoload.php';
try {
    $conn = new Cookbook\Database\Connection(include __DIR__ . DB_CONFIG_FILE);
    $iter = (new Cookbook\Iterator\LargeFile(__DIR__ . CSV_FILE))->getIterator('Csv');
    $sql = 'INSERT INTO `prospects` (`id`,`first_name`,'
        . '`last_name`,`address`,`city`,`state_province`,'
        . '`postal_code`,`phone`,`country`,`email`,'
        . '`status`,`budget`, `last_updated`) '
        . ' VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $stmt = $conn->pdo->prepare($sql);
} catch (Throwable $e) {
  echo $e->getMessage();
}
We then use a foreach() to loop through the file iterator. Each yield statement produces an array of values that represents a row in the database. We can then use these values with PDOStatement::execute() to execute the prepared statement, inserting the row of values into the database:
foreach ($iterator as $row) {
  echo implode(',', $row) . PHP_EOL;
  $statement->execute($row);
}

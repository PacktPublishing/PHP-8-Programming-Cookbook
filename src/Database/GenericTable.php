<?php
namespace Cookbook\Database;

use PDO;
use PDOStatement;
use SplObjectStorage;
use Psr\Container\ContainerInterface;
#[GenericTable("Generic table class")]
abstract class GenericTable implements TableInterface
{
    public ?PDO $pdo = NULL;
    public string $sql  = '';
    public ?GenericRowInterface $rowClass = NULL;
    public ?SplObjectStorage $found = NULL;   // used to store results
    public function __construct(
        public ContainerInterface $container,
        public string $connect_svc = 'db_connect') 
    {
        $this->pdo = $container->get($connect_svc);
        $this->found = new SplObjectStorage();
    }
    #[GenericRow\getCols(
        "Returns just the column names",
    )]
    public function getCols() : array
    {
        return array_keys(static::COLS);
    }
    #[GenericRow\createTable(
        "Creates SQL string to create database table",
        "Returns results of PDO::exec()"
    )]
    public function createTable() :  int|false
    {
        $q = function (string $item) {
            return static::QUOTE . trim($item) . static::QUOTE; };
        $sql = 'DROP TABLE IF EXISTS ' . $q(static::TABLE) . ';';
        $sql .= 'CREATE TABLE ' . $q(static::TABLE) . ' ( ';
        foreach (static::COLS as $key => $value) {
            $sql .= $q($key) . ' ' . $value . ',';
        }
        // remove trailing comma
        $sql = substr($sql, -1);
        $sql .= ' PRIMARY KEY (' . $q(static::PRIMARY) . '), ';
        $sql .= ' KEY ' . $q('place_name') . ' (' . $q('place_name') . ') ';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;';
        return $this->pdo->exec($sql);
    }
    #[GenericRow\buildInsert(
        "Creates basic SQL SELECT for database",
        "@param bool \$autoInsert : set FALSE if primary key is not auto-insert",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildInsert(bool $autoInsert = TRUE) : PDOStatement|false
    {
        // remove reference to primary key column if auto-insert
        if ($autoInsert) {
            $cols = static::COLS;
            unset($cols[static::PRIMARY]);
            $cols = array_keys($cols);
        } else {
            $cols = $this->getCols();
        }
        // build SQL INSERT
        $sql = 'INSERT INTO ' . static::TABLE . ' '
             . '(' . implode(',', $cols) . ') '
             . 'VALUES '
             . '(:' . implode(',:', $cols) . ');';
        $this->insertStatement = $this->pdo->prepare($sql);
        return $this->insertStatement;
   }
    #[GenericRow\buildSelectSql(
        "Creates SQL for database table select",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildSelectSql() : string
    {
        // build SQL SELECT
        $this->sql = 'SELECT ' . implode(',', $this->getCols()) . ' '
             . 'FROM ' . static::TABLE . ' ';
        return $this->sql;
   }
}

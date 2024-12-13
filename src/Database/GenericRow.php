<?php
namespace Cookbook\Database;

use PDO;
use PDOStatement;
use Throwable;
use RuntimeException;
use OutOfBoundsException;
use Cookbook\Database\Connect;
use function array_combine;
use function count;
use function implode;
use function substr;
#[GenericRow("Represents a single row in a table")]
abstract class GenericRow
{
    public array $row  = [];
    public string $sql = '';
    public const TABLE = '';
    public const COLS  = [];
    public ?PDOStatement $insertStatement = null;
    public ?PDOStatement $selectStatement = null;
    public const ERR_INS_STMT = 'ERROR: unable to build insert';
    public const ERR_COUNT    = 'ERROR: header / data count does not match';
    public const ERR_INSERT   = 'ERROR: unable to add data to database';
    // IMPORTANT: $dbCols is the list of database column names
    //            the column name order must exactly match the order of CSV the columns
    #[GenericRow\__construct(
        "PDO pdo : PDO instance",
        "string primaryKey : primary key column for this database table",
    )]
    public function __construct(
        public ?PDO $pdo,
        public string $primaryKey = 'id')
    {}
    #[GenericRow\createTable("SQL to create the table")]
    public abstract function createTable();
    #[GenericRow\buildInsert(
        "Creates prepared statement to insert row into database table",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildInsert() : PDOStatement|false
    {
        // remove reference to primary key column
        $cols = array_slice(static::COLS, 1);
        // build SQL INSERT
        $ok = false;
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
        $this->sql = 'SELECT ' . implode(',', static::COLS) . ' '
             . 'FROM ' . static::TABLE . ' ';
        return $this->sql;
   }
    #[GenericRow\ingestRow(
        "Ingests row from CSV",
        "array data: actual data from CSV file",
        "bool includesKey : set TRUE if data includes primary key; FALSE otherwise",
        "Returns TRUE if row count matches; FALSE otherwise",
        "Throws OutOfBoundsException if count of dbCols and data does not match"
    )]
    public function ingestRow(array $data, bool $includesKey) : bool
    {
        $ok = FALSE;
        $cols = ($includesKey) ? static::COLS : array_slice(static::COLS, 1);
        if (count($cols) == count($data)) {
            $this->row = array_combine($cols, $data);
            $ok = TRUE;
        }
        return $ok;
    }
    #[GenericRow\insert(
        "Inserts row into database table",
        "array data: actual data from CSV file",
        "Returns bool : TRUE if successful; FALSE otherwise"
    )]
    public function insert(array $data) : bool
    {
        $ok = FALSE;
        if ($this->ingestRow($data, FALSE)) {
            $ok = $this->insertStatement->execute($this->row);
        }
        return (bool) $ok;    
    }
    #[GenericRow\__get(
        "Returns internal array row element"
    )]
    public function __get(string $key) : string
    {
        return $this->row[$key] ?? '';
    }
}

<?php
namespace Cookbook\Database;

use PDO;
use PDOStatement;
use Throwable;
use RuntimeException;
use OutOfBoundsException;
use Cookbook\Database\Connect;
use function array_combine;
#[GenericRow("Represents a single row in a table")]
class GenericRow
{
    public array $row = [];
    public ?PDOStatement $insertStatement = null;
    public ?PDOStatement $selectStatement = null;
    public const ERR_INS_STMT = 'ERROR: unable to build insert';
    public const ERR_COUNT    = 'ERROR: header / data count does not match';
    public const ERR_INSERT   = 'ERROR: unable to add data to database';
    // IMPORTANT: $dbCols is the list of database column names
    //            the column name order must exactly match the order of CSV the columns
    #[GenericRow\__construct(
        "string table : name of the database table",
        "array dbCols : database column names", 
        "PDO pdo : PDO instance",
    )]
    public function __construct(
        public string $table, 
        public array $dbCols,
        public ?PDO $pdo)
    {}
    #[GenericRow\buildInsert(
        "Creates prepared statement to insert row into database table",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildInsert() : PDOStatement|false
    {
        // build SQL INSERT
        $ok = false;
        $sql = 'INSERT INTO ' . $this->table . ' '
             . '(' . implode(',', $this->dbCols) . ') '
             . 'VALUES '
             . '(:' . implode(',:', $this->dbCols) . ');';
        $this->insertStatement = $this->pdo->prepare($sql);
        return $this->insertStatement;
   }
    #[GenericRow\buildSelect(
        "Creates prepared statement for database table select",
        "array where : key => value pairs where key === col and value == WHERE equivalence",
        "int limit   : adds LIMIT to SQL statement if non zero",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildSelect(array $where = [], int $limit = 0) : PDOStatement|false
    {
        // build SQL SELECT
        $ok = false;
        $sql = 'SELECT ' . implode(',', $this->dbCols) . ' '
             . 'FROM ' . $this->table . ' ';
        if (!empty($where)) {
            $sql .= 'WHERE ';
            foreach ($where as $col => $value) {
                $sql .= $col . '=' . ':' . $col . ',';
            }
            $sql = substr($sql, -1) . ' ';
        }
        if (!empty($limit)) {
            $sql .= 'LIMIT ' . $limit;
        }
        $this->selectStatement = $this->pdo->prepare($sql);
        return $this->selectStatement;
   }
    #[GenericRow\ingestRow(
        "Ingests row from CSV",
        "array data: actual data from CSV file",
        "Returns TRUE if row count matches; FALSE otherwise",
        "Throws OutOfBoundsException if count of dbCols and data does not match"
    )]
    public function ingestRow(array $data) : bool
    {
        if (count($this->dbCols) !== count($data)) {
            throw new OutOfBoundsException(static::ERR_COUNT);
        }
        $this->row = array_combine($this->dbCols, $data);
    }
    #[GenericRow\insert(
        "Inserts row into database table",
        "array data: actual data from CSV file",
        "Returns bool : TRUE if successful; FALSE otherwise"
    )]
    public function insert(array $data) : bool
    {
        // build SQL INSERT
        try {
            $this->ingestRow($data);
            $ok = $this->insertStatement->execute($this->row);
        } catch (Throwable $t) {
            $ok = false;
            error_log(__METHOD__ . ':' 
                . get_class($t) . ':' 
                . $t->getMessage());
        }
        return (bool) $ok;    
    }
}

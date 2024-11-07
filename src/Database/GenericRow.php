<?php
namespace Cookbook\Database;

use PDO;
use PDOStatement;
use Throwable;
use RuntimeException;
use OutOfBoundsException;
use function array_combine;
class GenericRow
{
    public array $row = [];
    public ?PDOStatement $stmt = null;
    public const ERR_PDO = 'ERROR: unable to build insert';
    public const ERR_COUNT = 'ERROR: header / data count does not match';
    public const ERR_INSERT = 'ERROR: unable to add data to database';
    // IMPORTANT: $dbCols is the list of database column names
    //            the column name order must exactly match the order of CSV the columns
    #[GenericRow\__construct(
        "string table : name of the database table",
        "array dbCols : database column names", 
        "PDO pdo : PDO instance",
    )]
    public function __construct(
        public string $table, 
        public array $mapping,
        public PDO $pdo)
    {
        try {
            // prepare once
            $this->stmt = $this->buildInsert();
        } catch (Throwable) {
            error_log(__METHOD__ . ':' 
                . get_class($t) . ':' 
                . $t->getMessage());
            throw new RuntimeException(static::ERR_PDO);
        } finally {
            if (empty($this->stmt)) {
                throw new RuntimeException(static::ERR_PDO);
            }
        }
    }
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
        return $pdo->prepare($sql);
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
            $ok = $this->stmt->execute($this->row);
        } catch (Throwable) {
            $ok = false;
            error_log(__METHOD__ . ':' 
                . get_class($t) . ':' 
                . $t->getMessage());
        }
        return $ok;    
    }
}

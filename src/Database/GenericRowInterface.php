<?php
namespace Cookbook\Database;

#[GenericRowInterface("Represents a single row in a table")]
interface GenericRowInterface
{
    #[GenericRow\createTable("SQL to create the table")]
    public function createTable();
    #[GenericRow\buildInsert(
        "Creates prepared statement to insert row into database table",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildInsert() : PDOStatement|false;
    #[GenericRow\buildSelectSql(
        "Creates SQL for database table select",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function buildSelectSql() : string;
    #[GenericRow\ingestRow(
        "Ingests row from CSV",
        "array data: actual data from CSV file",
        "bool includesKey : set TRUE if data includes primary key; FALSE otherwise",
        "Returns TRUE if row count matches; FALSE otherwise",
        "Throws OutOfBoundsException if count of dbCols and data does not match"
    )]
    public function ingestRow(array $data, bool $includesKey) : bool;
    #[GenericRow\insert(
        "Inserts row into database table",
        "array data: actual data from CSV file",
        "Returns bool : TRUE if successful; FALSE otherwise"
    )]
    public function insert(array $data) : bool;
    #[GenericRow\__get(
        "Returns internal array row element"
    )]
    public function __get(string $key) : string;
}

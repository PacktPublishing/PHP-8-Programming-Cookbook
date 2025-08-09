<?php
namespace Cookbook\Database;

use PDOStatement;
#[TableInterface("Interface associated with the GenericTable class")]
interface TableInterface
{
    public const QUOTE  = '`';  // database specific (default: MySQL)
    public const TABLE  = '';   // override in subclass
    public const COLS   = [];   // override in subclass
    public const PRIMARY = 'id';    // override in subclass
    public function createTable() :  int|false; 
    public function buildInsert() : PDOStatement|false;
    public function buildSelectSql() : string;
    public function getCols() : array;
}

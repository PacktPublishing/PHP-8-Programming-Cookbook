<?php
namespace Cookbook\Iterator\Database;

use PDO;

#[Names("Generic Row specific to 'names' table")]
class Names extends GenericRow
{
    public const TABLE = 'names';
    public const COLS  = ['id','first_name','last_name','address','city','state','zip','phone'];
    public function __construct(public ?PDO $pdo)
    {
        parent::__construct(static::TABLE, static::COLS, $this->pdo);
    }
    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . static::TABLE . ' ('
             . '  id INTEGER PRIMARY KEY,'
             . '  first_name TEXT,'
             . '  last_name TEXT NOT NULL,'
             . '  address TEXT,'
             . '  city TEXT,'
             . '  state TEXT,'
             . '  zip TEXT,'
             . '  phone TEXT NOT NULL UNIQUE );';
        return $this->pdo->exec($sql);
    }
}

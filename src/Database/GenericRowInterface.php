<?php
namespace Cookbook\Database;

#[GenericRowInterface("Represents a single row in a table")]
interface GenericRowInterface
{
    public function ingestRow(array $data, bool $includesKey) : bool;
    public function insert(array $data) : bool;
    public function __get(string $key) : string;
}

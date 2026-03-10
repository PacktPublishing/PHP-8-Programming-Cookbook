<?php
namespace Cookbook\Database;
use Traversable;
use ArrayIterator;
use IteratorAggregate;
#[StateProvince("Entity class that represents a single row in a table")]
class StateProvince implements IteratorAggregate
{
    protected array $properties = [];
    public const FIELDS = ['id', 'state_province_name', 'state_province_code'];
    #[StateProvince\__construct(
        "array \$data : data to be ingested"
    )]
    public function __construct(array $data = []) 
    {
        foreach (static::FIELDS as $key) {
            $this->properties[$key] = ($data[$key] ?? NULL);
        }
    }
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->properties);
    }
    public function __get(string $key) : mixed
    {
        return $this->properties[$key] ?? NULL;
    }
    public function __set(string $key, mixed $value) : void
    {
        if (in_array($key, static::FIELDS)) {
            $this->properties[$key] = $value;
        }
    }
}

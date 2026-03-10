<?php
use Traversable;
use ArrayIterator;
use IteratorAggregate;
#[ENTITY_CLASS_NAME("Entity class that represents a single row in a table")]
class ENTITY_CLASS_NAME implements IteratorAggregate
{
    protected array $properties = [];
    public const FIELDS = [];
    #[ENTITY_CLASS_NAME\__construct(
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

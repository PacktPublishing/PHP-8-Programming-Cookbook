<?php
namespace Cookbook\Services;
use ArrayObject;
use Psr\Container\ContainerInterface;
#[Container("Houses services")]
class Container implements ContainerInterface
{
    public ArrayObject $storage;
    protected $instance = NULL;
    private function __construct()
    {
        $this->storage = new ArrayObject();
    }
    public static function getInstance() : static
    {
        if (empty($this->instance)) {
            $this->instance = new static();
        }
        return $this->instance;
    }
    public function get(string $key) : mixed
    {
        return $this->instance->storage[$key] ?? NULL;
    }
    public function has(string $key) : bool
    {
        return !empty($this?->instance?->storage[$key]);
    }
    public function add(string $key, mixed $service) : bool
    {
        $this->instance->storage[$key] = $service;
        return $this->instance->has($key);
    }
    public function remove(string $key) : bool
    {
        $ok = FALSE;
        if ($this->has($key)) {
            unset($this->instance->storage[$key]);
            $ok = TRUE;
        }
        return $ok;
    }
}

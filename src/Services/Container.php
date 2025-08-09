<?php
namespace Cookbook\Services;
use ArrayObject;
use Psr\Container\ContainerInterface;
#[Container("Houses services")]
class Container implements ContainerInterface
{
    public ArrayObject $storage;
    protected static $instance = NULL;
    private function __construct()
    {
        $this->storage = new ArrayObject();
    }
    public static function getInstance() : static
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    public function get(string $key) : mixed
    {
        // TODO: add provision to retrieve service from cache
        return static::$instance?->storage[$key]() ?? NULL;
    }
    public function has(string $key) : bool
    {
        return !empty($this?->instance?->storage[$key]);
    }
    public function add(string $key, callable $service) : bool
    {
        // TODO: store service as LazyGhost
        static::$instance->storage[$key] = $service;
        return static::$instance->has($key);
    }
    public function cache(string $key) : bool
    {
        // TODO: serialize and cache the service
    }    
    public function remove(string $key) : bool
    {
        $ok = FALSE;
        if ($this->has($key)) {
            unset(static::$instance->storage[$key]);
            $ok = TRUE;
        }
        return $ok;
    }
}

<?php
namespace Cookbook\Services;
use ArrayObject;
use SplFileObject;
use DateInterval;
use Traversable;
use Psr\SimpleCache\CacheInterface;
#[Cache("Provides a caching service as per https://www.php-fig.org/psr/psr-16/")]
class Cache implements CacheInterface
{
    public string $fn = '';
    public ?ArrayObject $cache = NULL;
    public const CACHE_FN = __DIR__ . '/../../data/cache.txt';
    public function __construct(string $fn = '')
    {
        $this->fn = (empty($fn)) ? static::CACHE_FN : $fn;
        if (!file_exists($this->fn)) {
            touch($this->fn);
        }
        $this->cache = new ArrayObject(
            json_decode(file_get_contents($this->fn), TRUE) ?? []);
    }
    public function get($key, $default = null) : mixed
    {
        return $this->cache[$key] ?? NULL;
    }
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $this->cache[$key] = $value;
        return (bool) $this->save();
    }
    public function delete(string $key) : bool
    {
        if ($this->has($key)) {
            unset($this->cache[$key]);
        }
        return (bool) $this->save();
    }
    public function clear() : bool
    {
        if (file_exists($this->fn)) {
            unlink($this->fn);
            touch($this->fn);
        }
        $this->cache = new ArrayObject([]);
        return TRUE;
    }
    public function has(string $key) : bool
    {
        return isset($this->cache[$key]);
    }
    public function save() : int
    {
        $json = json_encode($this->cache, JSON_PRETTY_PRINT);
        return (int) file_put_contents($this->fn, $json);
    }
    public function __destruct()
    {
        $this->save();
    }
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        // do nothing
    }
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        // do nothing
    }
    public function deleteMultiple(iterable $keys) : bool
    {
        // do nothing
    }
}

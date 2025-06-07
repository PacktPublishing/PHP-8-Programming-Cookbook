<?php
namespace Cookbook\Services;
use SplFileObject;
use Psr\SimpleCache\CacheInterface;
#[Caching("Provides a caching service as per https://www.php-fig.org/psr/psr-16/")]
class Caching implements CacheInterface
{
    public const CACHE_FN = __DIR__ . '/../../data/cache.txt';
    public function __construct(string $fn = '')
    {
        $this->fn = (empty($fn)) ? static::FN : $fn;
        $this->obj = new SplFileObject($this->fn, 'r');
        $this->paragraphs = $this->analyze();
    }
    public function get($key, $default = null);
    public function set($key, $value, $ttl = null);
    public function delete($key);
    public function clear();
    public function getMultiple($keys, $default = null);
    public function setMultiple($values, $ttl = null);
    public function deleteMultiple($keys);
    public function has($key);
}

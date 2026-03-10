<?php
namespace Cookbook\Database;
use PDO;
use ArrayObject;
use SplFileObject;
use DateInterval;
use Traversable;
use Psr\SimpleCache\CacheInterface;
use Psr\Container\ContainerInterface;
#[GenAiCache("Provides a GenAI caching service as per https://www.php-fig.org/psr/psr-16/")]
class GenAiCache implements CacheInterface
{
    public const TABLE  = 'gen_ai_cache';
    public ?PDO $pdo = NULL;
    public string $allSQL   = 'SELECT cache_key,request_text FROM %s';
    public string $findSQL  = 'SELECT cache_key,request_text FROM %s WHERE cache_key = ?';
    public string $saveSQL  = 'INSERT INTO %s (cache_key, request_text) VALUES (?,?)';
    public string $delSQL   = 'DELETE FROM %s WHERE cache_key = ?';
    public string $clearSQL = 'DELETE FROM %s';
    public function __construct(ContainerInterface $container)
    {
        $this->pdo = $container->get('db_connect');
    }
    public function get($key, mixed $default = NULL) : mixed
    {
        $stmt = $this->pdo->prepare(sprintf($this->findSQL, static::TABLE));
        $result = $stmt->execute([$key]);
        $text = base64_decode($stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? '');
        return $text;
    }
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $stmt = $this->pdo->prepare(sprintf($this->saveSQL, static::TABLE));
        return (bool) $stmt->execute([$key, base64_encode($value)]);
    }
    public function delete(string $key) : bool
    {
        $result = FALSE;
        if ($this->has($key)) {
            $stmt = $this->pdo->prepare(sprintf($this->delSQL, static::TABLE));
            $result = (bool) $stmt->execute([$key]);
        }
        return $result;
    }
    public function clear() : bool
    {
        return (bool) $this->pdo->exec(sprintf($this->clearSQL, static::TABLE));
    }
    public function has(string $key) : bool
    {
        return (bool) $this->get($key);
    }
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->pdo->query(sprintf($this->allSQL, static::TABLE));
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

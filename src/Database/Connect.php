<?php
namespace Cookbook\Database;
use PDO;
use Throwable;
use RuntimeException;
#[Connect("Singleton that returns a PDO instance")]
class Connect
{
    public const ERR_PDO = 'ERROR: unable to create PDO instance';
    public ?PDO $pdo = NULL;
    #[Connect\__construct(
        "Builds PDO instance",
        "array config : configuration array with connection info",
        "@throw RuntimeException"
    )]
    public function __construct(array $config) 
    {
        $options  = $config['options'] ?? [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $username = $config['db_usr']  ?? '';
        $password = $config['db_pwd']  ?? '';
        try {
            $dsn = sprintf('%s:host=%s;dbname=%s', $config['db_driver'], $config['db_host'], $config['db_name']);
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch(Throwable $t) {
            error_log(__METHOD__ . ':' . get_class($t) . ':' . $t->getMessage());
            throw new RuntimeException(static::ERR_PDO);
        }
    }
    #[Connect\getConnection(
        "Returns PDO instance or NULL",
    )]
    public function __invoke() : PDO|null
    {
        return $this->pdo;
    }
}

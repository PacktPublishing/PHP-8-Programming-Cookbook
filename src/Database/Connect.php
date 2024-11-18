<?php
namespace Cookbook\Database;
use PDO;
use Throwable;
#[Connect("Singleton that returns a PDO instance")]
class Connect
{
    public const DB_DRIVER = 'sqlite';
    public const DB_NAME = __DIR__ . '/../../data/cookbook_names.db';
    public const DB_OPTS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    public const DB_USR  = '';
    public const DB_PWD  = '';
    public const DB_HOST = 'localhost';
    public static ?PDO $pdo = NULL;
    #[Connect\__construct("Marked private to prevent multiple instances")]
    private function __construct()
    {}
    #[Connect\getConnection(
        "Returns PDO instance or NULL",
        "array config : configuration array with connection info"
    )]
    public static function getConnection(array $config = []) : PDO|null
    {
        if (empty(self::$pdo)) {
            $driver   = $config['db_driver']   ?? static::DB_DRIVER;
            $host     = $config['db_host']     ?? static::DB_HOST;
            $dbName   = $config['db_name']   ?? static::DB_NAME;
            $username = $config['db_usr'] ?? static::DB_USR;
            $password = $config['db_pwd'] ?? static::DB_PWD;
            $options  = $config['options']  ?? static::DB_OPTS;
            try {
                // change this is using other drivers
                if ($driver === 'sqlite') {
                    self::$pdo = new PDO($driver . ':dbname=' . realpath($dbName), '', '', $options);
                } else {
                    self::$pdo = new PDO($driver . ':host=' . $host . ';dbname=' . $dbName, $username, $password, $options);
                }
            } catch (Throwable $t) {
                error_log(__METHOD__ . ':' . get_class($t) . ':' . $t->getMessage());
                self::$pdo = NULL;
            }
        }
        return self::$pdo;
    }
}

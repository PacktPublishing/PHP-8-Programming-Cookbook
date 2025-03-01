<?php
namespace Cookbook\Iterator\Database;
use PDO;
use RuntimeException;

#[Connect("Singleton that returns a PDO instance")]
class Connect
{
    public const DB_DRIVER = 'mysql';
    public const DB_NAME = 'php8cookbook';
    public const DB_OPTS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    public const DB_HOST = 'mysql.local';
    public const ERR_PDO = 'ERROR: unable to create PDO instance';
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
            $driver   = $config['db_driver'] ?? static::DB_DRIVER;
            $host     = $config['db_host']   ?? static::DB_HOST;
            $dbName   = $config['db_name']   ?? static::DB_NAME;
            $options  = $config['options']   ?? static::DB_OPTS;
            $username = $config['db_usr']    ?? '';
            $password = $config['db_pwd']    ?? '';
            // change this is using other drivers
            static::$pdo = new PDO($driver . ':host=' . $host . ';dbname=' . $dbName, $username, $password, $options);
            if (empty(self::$pdo)) {
                throw new RuntimeException(static::ERR_PDO);
            }
        }
        return static::$pdo;
    }
}

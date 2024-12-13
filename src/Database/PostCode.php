<?php
namespace Cookbook\Database;

use PDO;
use WeakMap;
use WeakReference;
use SplObjectStorage;
#[Names("Generic Row specific to 'post_codes' table")]
class PostCode extends GenericRow
{
    public const TABLE = 'post_codes';
    public const COLS  = ['id','country_code','postal_code','place_name','admin_name1','admin_code1','admin_name2','admin_code2','admin_name3','admin_code3','latitude','longitude','accuracy'];
    // SQL to create "post_codes" tabls
    public function createTable()
    {
        $sql = <<<EOT
DROP TABLE IF EXISTS `post_codes`;
CREATE TABLE `post_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_code` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `place_name` char(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_name1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code1` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_name2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code2` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_name3` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_code3` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,4) NOT NULL,
  `longitude` decimal(10,4) NOT NULL,
  `accuracy` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `place_name` (`place_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
EOT;
        return $this->pdo->exec($sql);
    }
    #[PostCode\findOneCity(
        "Returns only the first city found",
        "string city : city to find",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function findOneCity(string $city) : static
    {
        $post = new static($this->pdo);
        $sql  = $this->buildSelectSql();
        $sql .= 'WHERE place_name LIKE ' . $this->pdo->quote('%' . $city. '%') . ' ';
        $sql .= 'LIMIT 1';
        $result = $this->pdo->query($sql);
        if (!empty($result)) {
            $post->ingestRow($result->fetch(PDO::FETCH_ASSOC), TRUE);
        }
        return $post;
    }
    #[PostCode\findCity(
        "Returns an SplObjectStorage instance loaded with cities found",
        "string city : city to find",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function findCity(string $city) : SplObjectStorage
    {
        $obj  = new SplObjectStorage();
        $post = new static($this->pdo);
        $sql  = $this->buildSelectSql();
        $sql .= ' WHERE place_name LIKE ' . $this->pdo->quote('%' . $city. '%') . ' ';
        $result = $this->pdo->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $post->ingestRow($row, TRUE);
                $obj->attach(clone $post);
            }
        }
        return $obj;
    }
    #[PostCode\findCityWeakRef(
        "Returns an SplObjectStorage instance loaded with weak references of cities found",
        "string city : city to find",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function findCityWeakRef(string $city) : SplObjectStorage
    {
        $obj  = new class() extends SplObjectStorage {
            public function current()
            {
                return parent::current()->get();
            }
        };
        $post = new static($this->pdo);
        $sql  = $this->buildSelectSql();
        $sql .= ' WHERE place_name LIKE ' . $this->pdo->quote('%' . $city. '%') . ' ';
        $result = $this->pdo->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $post->ingestRow($row, TRUE);
                $obj->attach(WeakReference::create(clone $post));
            }
        }
        return $obj;
   }
}

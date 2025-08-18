<?php
namespace Cookbook\Database;

use PDO;
use WeakMap;
use WeakReference;
use SplObjectStorage;
#[PostCodeTable("Table class associated with 'post_codes' table")]
class PostCodeTable extends GenericTable
{
    public const TABLE = 'post_codes';
    public const COLS  = [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'country_code' => 'char(2) COLLATE utf8mb4_general_ci NOT NULL',
        'postal_code' => 'varchar(20) COLLATE utf8mb4_general_ci NOT NULL',
        'place_name' => 'char(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL',
        'admin_name1' => 'varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'admin_code1' => 'varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'admin_name2' => 'varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'admin_code2' => 'varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'admin_name3' => 'varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'admin_code3' => 'varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL',
        'latitude' => 'decimal(10,4) NOT NULL',
        'longitude' => 'decimal(10,4) NOT NULL',
        'accuracy' => 'varchar(8) NOT NULL',
    ];
    #[PostCode\findByPostCode(
        "Returns city data based on post code",
        "string postCode : post code to find",
        "Returns GenericRow if successful; FALSE otherwise"
    )]
    public function findByPostCode(string $code) : GenericRow | NULL
    {
        $sql  = $this->buildSelectSql();
        $sql .= 'WHERE `postal_code` = ?';
        $result = $this->pdo->prepare($sql);
        if (empty($result->execute([$code]))) {
            $post = new GenericRow();
        } else {
            $post = new GenericRow($result->fetch(PDO::FETCH_ASSOC));
        }
        $this->sql = $sql;
        return $post;
    }
    #[PostCode\findByCity(
        "Returns an SplObjectStorage instance loaded with cities found",
        "string city : city to find",
        "Returns iteration of GenericRow objects"
    )]
    public function findByCity(string $city) : iterable
    {
        $sql  = $this->buildSelectSql();
        $sql .= ' WHERE place_name LIKE ' . $this->pdo->quote('%' . trim($city) . '%') . ' ';
        $result = $this->pdo->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->found->attach(new GenericRow($row));
            }
        }
        $this->sql = $sql;
        return $this->found;
    }
    /*
    #[PostCode\findCityWeakMap(
        "Returns an WeakMap instance loaded with weak references of cities found",
        "string city : city to find",
        "Returns PDOStatement if successful; FALSE otherwise"
    )]
    public function findCityWeakMap(string $city) : WeakMap
    {
        $obj  = new WeakMap();
        $post = $this->container->get($row_svc);
        $sql  = $this->buildSelectSql();
        $sql .= ' WHERE place_name LIKE ' . $this->pdo->quote('%' . $city. '%') . ' ';
        $result = $this->pdo->query($sql);
        if (!empty($result)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $this->obj->attach(new GenericRow($result->fetch(PDO::FETCH_ NUM));
            }
        }
        return $obj;
   }
   */
}

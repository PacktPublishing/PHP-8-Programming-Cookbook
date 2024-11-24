<?php
namespace Cookbook\Database;

use PDO;
#[Names("Generic Row specific to 'post_codes' table")]
class PostCode extends GenericRow
{
    public const TABLE = 'post_codes';
    public const COLS  = ['id','country_code','postal_code','place_name','admin_name1','admin_code1','admin_name2','admin_code2','admin_name3','admin_code3','latitude','longitude','accuracy'];
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
}

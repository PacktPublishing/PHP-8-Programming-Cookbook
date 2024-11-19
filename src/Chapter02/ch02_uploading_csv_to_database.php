<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\PostCode;
use Cookbook\Database\Connect;
use Cookbook\Iterator\LargeFile;
$config = require __DIR__ . '/../../config/db.config.php';
$csv_fn = __DIR__ . '/../../data/US.txt';   // NOTE: tab-delimited
$expected = 0;
$actual   = 0;
$headers  = array_slice(PostCode::COLS, 1);
try {
    // grab an instance using TAB as delimiter
    $iter = (new LargeFile($csv_fn, 'r', "\t", FALSE))->getIterator('CSV');
    $iter->next();
    // set up database connection and row object
    $pdo = Connect::getConnection();
    $rowObj = new PostCode($pdo);
    // check to see if table exists
    $select = $rowObj->buildSelect([], 1);
    $result = $select->execute();
    if (empty($result)) {
        $rowObj->createTable();
    }
    if (!empty($rowObj->buildInsert())) {
        while ($iter->valid()) {
            $expected++;
            $row = $iter->current();
            $actual += (int) $rowObj->insert($row);
            // echo implode(':',$row) . PHP_EOL;
            $iter->next();
        }
    } else {
        throw new RuntimeException('ERROR: problem building INSERT statement');
    }
} catch (Throwable $e) {
  echo get_class($e) . ':' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
echo "\nExpected rows to process : $expected";
echo "\nActual rows processed    : $actual\n";

/*
For more info: https://download.geonames.org/export/zip/
country code      : iso country code, 2 characters
postal code       : varchar(20)
place name        : varchar(180)
admin name1       : 1. order subdivision (state) varchar(100)
admin code1       : 1. order subdivision (state) varchar(20)
admin name2       : 2. order subdivision (county/province) varchar(100)
admin code2       : 2. order subdivision (county/province) varchar(20)
admin name3       : 3. order subdivision (community) varchar(100)
admin code3       : 3. order subdivision (community) varchar(20)
latitude          : estimated latitude (wgs84)
longitude         : estimated longitude (wgs84)
accuracy          : accuracy of lat/lng from 1=estimated, 4=geonameid, 6=centroid of addresses or shape
*/

/*
 * SQL:
CREATE TABLE post_codes (
    id int NOT NULL AUTO_INCREMENT,
    country_code char(2) NOT NULL,
    postal_code varchar(20) NOT NULL,
    place_name varchar(180) NOT NULL,
    admin_name1 varchar(100),
    admin_code1 varchar(20),
    admin_name2 varchar(100),
    admin_code2 varchar(20),
    admin_name3 varchar(100),
    admin_code3 varchar(20),
    latitude decimal(10,4),
    longitude decimal(10,4),
    accuracy int,
    PRIMARY KEY (id)
);
*/

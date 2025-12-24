#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * populate.php (PHP 8.4)
 *
 * Usage:
 *   php populate.php /exact/path/to/file.txt
 *
 * Expects a GeoNames postal code file: UTF-8, tab-delimited, fields:
 * country_code, postal_code, place_name, admin_name1, admin_code1, admin_name2,
 * admin_code2, admin_name3, admin_code3, latitude, longitude, accuracy
 *
 * MariaDB connection is configured via environment variables:
 *   DB_HOST (default: 127.0.0.1)
 *   DB_PORT (default: 3306)
 *   DB_NAME (required)
 *   DB_USER (required)
 *   DB_PASS (default: empty)
 *
 * Notes:
 * - Reads file line-by-line (streaming).
 * - Inserts row-by-row using a prepared statement.
 * - Reports: rows read + rows inserted.
 */

function stderr(string $msg, int $exitCode = 1): never {
    fwrite(STDERR, $msg . PHP_EOL);
    exit($exitCode);
}

if (PHP_SAPI !== 'cli') {
    stderr('Error: This script must be run from the command line.');
}

if ($argc !== 2) {
    stderr('Usage: php populate.php /exact/path/to/tab_delimited_file.txt');
}

$fn = $argv[1];
$msg = match (TRUE) {
    (!is_string($fn) || $fn === '') => 'Error: Missing input file path argument.',
    (!file_exists($fn))             => 'Error: File does not exist: ' . $fn,
    (!is_file($fn))                 => 'Error: Path is not a regular file: ' . $fn,
    (!is_readable($fn))             => 'Error: File is not readable: ' . $fn,
    default => ''
};
if (!empty($msg)) stderr($msg);

// DB config from environment variables
shell_exec('. /tmp/secrets.sh');    // loads ENV variables
$dbHost = (string) (getenv('DB_HOST') ?: '127.0.0.1');
$dbPort = (string) (getenv('DB_PORT') ?: '3306');
$dbName = (string) (getenv('DB_NAM')  ?: '');
$dbUser = (string) (getenv('DB_USR')  ?: '');
$dbPass = (string) (getenv('DB_PWD')  ?: '');

if ($dbName === '' || $dbUser === '') {
    stderr('Error: DB_NAM and DB_USR environment variables are required.');
}

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $dbHost, $dbPort, $dbName
);

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (Throwable $e) {
    stderr('Error: Could not connect to MariaDB: ' . $e->getMessage());
}

// Create table if it doesn't exist
$createSql = <<<'SQL'
CREATE TABLE IF NOT EXISTS postcode (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  country_code CHAR(2) NOT NULL,
  postal_code VARCHAR(20) NOT NULL,
  place_name VARCHAR(180) NOT NULL,
  admin_name1 VARCHAR(100) NOT NULL,
  admin_code1 VARCHAR(20) NOT NULL,
  admin_name2 VARCHAR(100) NOT NULL,
  admin_code2 VARCHAR(20) NOT NULL,
  admin_name3 VARCHAR(100) NOT NULL,
  admin_code3 VARCHAR(20) NOT NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  accuracy TINYINT UNSIGNED NULL,
  PRIMARY KEY (id),
  KEY idx_country_postal (country_code, postal_code),
  KEY idx_admin1 (country_code, admin_code1),
  KEY idx_admin2 (country_code, admin_code1, admin_code2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

try {
    $pdo->exec($createSql);
} catch (Throwable $e) {
    stderr('Error: Could not create table "postcode": ' . $e->getMessage());
}

$insertSql = <<<'SQL'
INSERT INTO postcode (
  country_code, postal_code, place_name, admin_name1, admin_code1,
  admin_name2, admin_code2, admin_name3, admin_code3,
  latitude, longitude, accuracy
) VALUES (
  :country_code, :postal_code, :place_name,
  :admin_name1, :admin_code1, :admin_name2, :admin_code2,
  :admin_name3, :admin_code3, :latitude, :longitude, :accuracy
);
SQL;

try {
    $stmt = $pdo->prepare($insertSql);
} catch (Throwable $e) {
    stderr('Error: Could not prepare insert statement: ' . $e->getMessage());
}

$rowsRead = 0;
$rowsInserted = 0;
$rowsSkipped = 0;

// Stream file line-by-line
$fh = fopen($fn, 'rb');
if (empty($fh)) {
    stderr('Error: Could not open file: ' . $fn);
}

// A small performance boost: commit every N rows.
$batchSize = 2000;
$inTx = false;
$sinceCommit = 0;

try {
    while (($fields = fgetcsv($fh, 0, "\t")) !== false) {
        // Skip empty lines
        if ($fields === [null] || $fields === [] || (count($fields) === 1 && trim((string)$fields[0]) === '')) {
            continue;
        }

        $rowsRead++;

        // Expect exactly 12 columns per README
        // country code, postal code, place name, admin name1, admin code1,
        // admin name2, admin code2, admin name3, admin code3, latitude, longitude, accuracy
        if (count($fields) < 12) {
            $rowsSkipped++;
            continue;
        }

        // Trim all fields (GeoNames files are usually clean; this avoids subtle whitespace issues)
        $fields = array_map(static fn($v) => is_string($v) ? trim($v) : '', $fields);

        [$countryCode, $postalCode, $placeName, $adminName1, 
         $adminCode1, $adminName2, $adminCode2, $adminName3, $adminCode3, 
         $lat, $lng, $acc] = array_slice($fields, 0, 12);

        // Basic validation: country code & postal code should exist
        if ($countryCode === '' || $postalCode === '') {
            $rowsSkipped++;
            continue;
        }

        // Normalize NULL-able numerics
        $latVal = ($lat === '' ? null : (float)$lat);
        $lngVal = ($lng === '' ? null : (float)$lng);
        $accVal = ($acc === '' ? null : (int)$acc);

        if (!$inTx) {
            $pdo->beginTransaction();
            $inTx = true;
        }

        try {
            $stmt->execute([
                ':country_code' => $countryCode,
                ':postal_code'  => $postalCode,
                ':place_name'   => $placeName,
                ':admin_name1'  => $adminName1,
                ':admin_code1'  => $adminCode1,
                ':admin_name2'  => $adminName2,
                ':admin_code2'  => $adminCode2,
                ':admin_name3'  => $adminName3,
                ':admin_code3'  => $adminCode3,
                ':latitude'     => $latVal,
                ':longitude'    => $lngVal,
                ':accuracy'     => $accVal,
            ]);
            $rowsInserted++;
        } catch (Throwable $e) {
            // Keep going; count as skipped/failed insert.
            $rowsSkipped++;
        }

        $sinceCommit++;
        if ($sinceCommit >= $batchSize) {
            $pdo->commit();
            $inTx = false;
            $sinceCommit = 0;
        }
    }

    if ($inTx) {
        $pdo->commit();
    }
} catch (Throwable $e) {
    if ($inTx && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fclose($fh);
    stderr('Error while processing file: ' . $e->getMessage());
}

fclose($fh);

// Final report
echo "File: {$fn}\n";
echo "Rows read:      {$rowsRead}\n";
echo "Rows inserted:  {$rowsInserted}\n";
echo "Rows skipped:   {$rowsSkipped}\n";
exit(0);

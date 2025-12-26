#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * populate.php
 *
 * Usage:
 *   php populate.php /exact/path/to/allCountries.txt
 *
 * Env vars (optional):
 *   DB_HOST (default: 127.0.0.1)
 *   DB_PORT (default: 3306)
 *   DB_NAME (default: geonames)
 *   DB_USER (default: root)
 *   DB_PASS (default: empty)
 */

final class App
{
    private int $rowsRead = 0;
    private int $rowsInserted = 0;

    public function run(array $argv): int
    {
        $fn = $this->parseArgs($argv);
        $this->assertFileReadable($fn);

        $pdo = $this->connect();
        $this->ensureSchema($pdo);

        [$read, $inserted] = $this->importFile($pdo, $fn);
        $this->rowsRead = $read;
        $this->rowsInserted = $inserted;

        $this->printStatsAndExit();

        return 0;
    }

    private function parseArgs(array $argv): string
    {
        if (count($argv) !== 2) {
            $script = $argv[0] ?? 'populate.php';
            $this->stderr("Usage: php {$script} /exact/path/to/tab_delimited_file\n");
            return $this->exitNow(2);
        }

        return (string)$argv[1];
    }

    private function assertFileReadable(string $fn): void
    {
        if (!is_file($fn)) {
            $this->stderr("Error: file does not exist: {$fn}\n");
            $this->exitNow(2);
        }
        if (!is_readable($fn)) {
            $this->stderr("Error: file is not readable: {$fn}\n");
            $this->exitNow(2);
        }
    }

    private function connect(): PDO
    {
        $host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
        $port = getenv('DB_PORT') !== false ? getenv('DB_PORT') : '3306';
        $db   = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'geonames';
        $user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
        $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $db
        );

        try {
            $pdo = new PDO($dsn, (string)$user, (string)$pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            // Ensure predictable SQL mode & time zone behavior if desired
            $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

            return $pdo;
        } catch (Throwable $e) {
            $this->stderr("Database connection failed: {$e->getMessage()}\n");
            $this->exitNow(1);
        }
    }

    private function ensureSchema(PDO $pdo): void
    {
        // Matches GeoNames readme fields:
        // country code, postal code, place name, admin1/2/3 names+codes, lat, lng, accuracy
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS postcode (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    country_code CHAR(2) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    place_name VARCHAR(180) NOT NULL,
    admin_name1 VARCHAR(100) NULL,
    admin_code1 VARCHAR(20) NULL,
    admin_name2 VARCHAR(100) NULL,
    admin_code2 VARCHAR(20) NULL,
    admin_name3 VARCHAR(100) NULL,
    admin_code3 VARCHAR(20) NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    accuracy TINYINT UNSIGNED NULL,

    PRIMARY KEY (id),

    KEY idx_country_postal (country_code, postal_code),
    KEY idx_place (place_name),
    KEY idx_admin1 (admin_code1),
    KEY idx_lat_lng (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL;

        try {
            $pdo->exec($sql);
        } catch (Throwable $e) {
            $this->stderr("Failed to create/verify schema: {$e->getMessage()}\n");
            $this->exitNow(1);
        }
    }

    /**
     * @return array{0:int,1:int} [rowsRead, rowsInserted]
     */
    private function importFile(PDO $pdo, string $fn): array
    {
        $insertSql = <<<SQL
INSERT INTO postcode (
    country_code, postal_code, place_name,
    admin_name1, admin_code1,
    admin_name2, admin_code2,
    admin_name3, admin_code3,
    latitude, longitude, accuracy
) VALUES (
    :country_code, :postal_code, :place_name,
    :admin_name1, :admin_code1,
    :admin_name2, :admin_code2,
    :admin_name3, :admin_code3,
    :latitude, :longitude, :accuracy
)
SQL;

        $stmt = $pdo->prepare($insertSql);

        $file = new SplFileObject($fn, 'r');
        $file->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::DROP_NEW_LINE
        );
        $file->setCsvControl("\t"); // tab-delimited

        $rowsRead = 0;
        $rowsInserted = 0;

        // Batch commits for speed without gigantic transactions.
        $batchSize = 5000;
        $inTx = false;
        $sinceCommit = 0;

        try {
            foreach ($file as $row) {
                // SplFileObject can yield [null] at EOF or on blank lines in some cases
                if (!is_array($row) || (count($row) === 1 && ($row[0] === null || $row[0] === ''))) {
                    continue;
                }

                $rowsRead++;

                // GeoNames format expects 12 columns.
                // Some files may contain a header; GeoNames typically doesn't.
                // We'll skip obvious header rows defensively.
                if ($rowsRead === 1 && isset($row[0], $row[1]) && str_contains((string)$row[0], 'country') && str_contains((string)$row[1], 'postal')) {
                    continue;
                }

                // Normalize to at least 12 columns
                $row = array_pad($row, 12, '');

                // Trim fields
                $row = array_map(
                    static fn($v) => is_string($v) ? trim($v) : (is_null($v) ? '' : (string)$v),
                    $row
                );

                // Required-ish fields per spec
                $countryCode = $row[0] ?? '';
                $postalCode  = $row[1] ?? '';
                $placeName   = $row[2] ?? '';

                if ($countryCode === '' || $postalCode === '' || $placeName === '') {
                    // Skip malformed line rather than failing the entire import
                    continue;
                }

                // Begin transaction lazily
                if (!$inTx) {
                    $pdo->beginTransaction();
                    $inTx = true;
                    $sinceCommit = 0;
                }

                $params = [
                    ':country_code' => $countryCode,
                    ':postal_code'  => $postalCode,
                    ':place_name'   => $placeName,

                    ':admin_name1'  => $this->nullIfEmpty($row[3] ?? ''),
                    ':admin_code1'  => $this->nullIfEmpty($row[4] ?? ''),
                    ':admin_name2'  => $this->nullIfEmpty($row[5] ?? ''),
                    ':admin_code2'  => $this->nullIfEmpty($row[6] ?? ''),
                    ':admin_name3'  => $this->nullIfEmpty($row[7] ?? ''),
                    ':admin_code3'  => $this->nullIfEmpty($row[8] ?? ''),

                    ':latitude'     => $this->nullIfNotNumeric($row[9] ?? ''),
                    ':longitude'    => $this->nullIfNotNumeric($row[10] ?? ''),
                    ':accuracy'     => $this->nullIfNotInt($row[11] ?? ''),
                ];

                try {
                    $stmt->execute($params);
                    $rowsInserted++;
                } catch (Throwable $e) {
                    // Keep going; you can log details if needed.
                    // Typical causes: encoding issues, overly long strings, etc.
                }

                $sinceCommit++;
                if ($sinceCommit >= $batchSize) {
                    $pdo->commit();
                    $inTx = false;
                }
            }

            if ($inTx) {
                $pdo->commit();
            }
        } catch (Throwable $e) {
            if ($inTx) {
                try { $pdo->rollBack(); } catch (Throwable) {}
            }
            $this->stderr("Import failed: {$e->getMessage()}\n");
            $this->exitNow(1);
        }

        return [$rowsRead, $rowsInserted];
    }

    private function nullIfEmpty(string $v): ?string
    {
        $v = trim($v);
        return $v === '' ? null : $v;
    }

    private function nullIfNotNumeric(string $v): ?string
    {
        $v = trim($v);
        if ($v === '' || !is_numeric($v)) {
            return null;
        }
        // Keep as string; PDO will send it fine for DECIMAL
        return $v;
    }

    private function nullIfNotInt(string $v): ?int
    {
        $v = trim($v);
        if ($v === '' || !preg_match('/^-?\d+$/', $v)) {
            return null;
        }
        return (int)$v;
    }

    private function printStatsAndExit(): void
    {
        // Print to STDOUT as requested
        fwrite(STDOUT, "Rows read: {$this->rowsRead}\n");
        fwrite(STDOUT, "Rows inserted: {$this->rowsInserted}\n");
    }

    private function stderr(string $msg): void
    {
        fwrite(STDERR, $msg);
    }

    private function exitNow(int $code): never
    {
        exit($code);
    }
}

$app = new App();
exit($app->run($argv));

<?php
namespace Cookbook\Database;
use PDO;
use Generator;

class PostcodeTable
{
    public function __construct(
        private PDO $pdo,
    ) {}

    /**
     * Creates a Postcode entity from a database row array.
     *
     * @param array<string, mixed> $row Associative array with database column names as keys
     */
    public function factory(array $row): Postcode
    {
        return new Postcode(
            id: isset($row['id']) ? (int) $row['id'] : null,
            countryCode: (string) ($row['country_code'] ?? ''),
            postalCode: (string) ($row['postal_code'] ?? ''),
            placeName: (string) ($row['place_name'] ?? ''),
            adminName1: (string) ($row['admin_name1'] ?? ''),
            adminCode1: (string) ($row['admin_code1'] ?? ''),
            adminName2: (string) ($row['admin_name2'] ?? ''),
            adminCode2: (string) ($row['admin_code2'] ?? ''),
            adminName3: (string) ($row['admin_name3'] ?? ''),
            adminCode3: (string) ($row['admin_code3'] ?? ''),
            latitude: isset($row['latitude']) ? (float) $row['latitude'] : null,
            longitude: isset($row['longitude']) ? (float) $row['longitude'] : null,
            accuracy: isset($row['accuracy']) ? (int) $row['accuracy'] : null,
        );
    }

    /**
     * Finds all postcodes matching the given city/place name.
     *
     * @param string $cityName The city or place name to search for
     * @return Generator<Postcode> Yields Postcode entities one at a time
     */
    public function findByCity(string $cityName): Generator
    {
        $sql = <<<'SQL'
            SELECT 
                id,
                country_code,
                postal_code,
                place_name,
                admin_name1,
                admin_code1,
                admin_name2,
                admin_code2,
                admin_name3,
                admin_code3,
                latitude,
                longitude,
                accuracy
            FROM postcode
            WHERE place_name = :place_name
            ORDER BY country_code, postal_code
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['place_name' => $cityName]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            yield $this->factory($row);
        }
    }
}

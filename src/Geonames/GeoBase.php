<?php
namespace Cookbook\Geonames;
use InvalidArgumentException;
#[GeoBase("Base class for Geonames data sets")]
abstract class GeoBase implements NameInterface
{
    public const ERR_COUNT = 'ERROR: column count mismatch';
    #[GeoBase\__construct("Accepts a row of Geonames data",
        "@param array \$data : row of data from the Geonames files",
        "@return void"
    )]
    public function __construct(array $data)
    {
        $vars = get_object_vars($this);
        if (count($data) !== count($vars)) {
            throw new InvalidArgumentException(self::ERR_COUNT);
        }
        // IMPORTANT: property order must match the order in the Geonames files
        foreach (array_keys($vars) as $idx => $name) {
            $this->$name = match (gettype($this->$name)) {
                'integer' => (int) ($data[$idx] ?? 0),
                'float', 'double'   => (float) ($data[$idx] ?? 0.0),
                default   => trim(strip_tags($data[$idx] ?? ''))
            };
        }
    }
    #[City\getCountry(
        "Returns the country code",
        "@return string \$country"
    )]
    public function getCountry() : string
    {
        return $this->country ?? '';
    }
}

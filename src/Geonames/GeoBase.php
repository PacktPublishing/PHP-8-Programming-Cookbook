<?php
namespace Cookbook\Geonames;
use InvalidArgumentException;
#[GeoBase("Base class for Geonames data sets")]
abstract class GeoBase
{
    public const ERR_COUNT = 'ERROR: column count mismatch';
    public function __construct(array $data)
    {
        $vars = get_object_vars($this);
        if (count($data) !== count($vars)) {
            throw new InvalidArgumentException(self::ERR_COUNT);
        }
        foreach (array_keys($vars) as $idx => $name) {
            $this->$name = trim(strip_tags($data[$idx] ?? ''));
        }
    }
}

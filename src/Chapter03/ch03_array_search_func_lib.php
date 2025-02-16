<?php
namespace Library;
require __DIR__ . '/../../src/Iterator/LargeFile.php';
use Iterator;
use CallbackFilterIterator;
use Cookbook\Iterator\LargeFile;

#[Library\buildArrays("Builds postcode => city, and postcode => [country, postcode, city, etc] for ~4000 entries from the GeoNames file")]
function buildArrays()
{
    $fn = __DIR__ . '/../../data/US.txt';
    $largeFile = new \Cookbook\Iterator\LargeFile($fn);
    $iterator = $largeFile->getIterator('ByLine');
    $gap = 10;
    $pos = $gap;
    $cities = [];
    $multi  = [];
    foreach ($iterator as $line) {
        if ($pos-- > 0) {
            continue;   // skip $gap # lines
        } else {
            $pos = $gap;
        }
        $line = trim($line);
        if (!empty($line)) {
            $row = str_getcsv($line, "\t");
            $multi[$row[1]]  = $row;
            $cities[$row[1]] = $row[2];
        }
    }
    return [$cities, $multi];
}

#[Library\fetchUsingArraySearch(
    "Returns postcode for a given city, or city for a given postcode",
    "param array arr : the array produced by builCityArray()",
    "param string city: the target search city",
    "param string postcode: the target search postcode",
    "return string : city, postcode, or empty if not found"
)]
function fetchUsingArraySearch(array $arr, string $city = '', string $postcode = '') : string | false
{
    return match (TRUE) {
        (!empty($city)) => array_search($city, $arr),
        (!empty($postcode)) => ($arr[$postcode] ?? ''),
        default => ''
    };
}

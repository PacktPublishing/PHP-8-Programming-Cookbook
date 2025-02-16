<?php
namespace Library;
require __DIR__ . '/../../src/Iterator/LargeFile.php';
use Cookbook\Iterator\LargeFile;

#[Library\buildArrays("Builds postcode => city, and postcode => [country, postcode, city, etc] for ~4000 entries from the GeoNames file")]
function buildArrays() : array
{
    $fn = __DIR__ . '/../../data/US.txt';
    $largeFile = new \Cookbook\Iterator\LargeFile($fn);
    $iterator = $largeFile->getIterator('ByLine');
    $gap = 10;
    $pos = $gap;
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
        }
    }
    return $multi;
}

#[Library\fetch(
    "Returns postcode information for the first element matching the search criteria",
    "param array arr : the array produced by builCityArray()",
    "param string|float needle: what you're look for",
    "param bool case : TRUE = case sensitive; FALSE = case insensitive",
    "param bool first : TRUE = return first value that matches; FALSE = return all elements that match",
    "return array : a multidimensional array with search results"
)]
function fetch(array $arr, string|float $needle, bool $case = TRUE, bool $first = TRUE) : mixed
{
    // determine if we return first match or all matches
    $func = ($first) ? 'array_find' : 'array_filter';
    return $func($arr,
        function ($value) use ($needle, $case) {
            // is $value an array?
            $haystack = (is_array($value)) 
                      ? implode(' ', $value)
                      : (string) $value;
            // is the search case insensitive?
            $needle = (string) $needle;
            if (!$case) {
                $haystack = strtolower($haystack);
                $needle   = strtolower($needle);
            }
            // check the contents
            return str_contains($haystack, $needle);
        }
    );
}


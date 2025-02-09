<?php
namespace Library;
require __DIR__ . '/../../src/Iterator/LargeFile.php';
use Iterator;
use CallbackFilterIterator;
use Cookbook\Iterator\LargeFile;

#[Library\htmlListUsingDoWhile("Uses a do {} while() loop")]
function htmlListUsingDoWhile(Iterator $iterator)
{
    $output = '';
    do {
        $row = trim($iterator->current() ?? '');
        if (!empty($row)) {
            $output .= '<tr>';
            $output .= '<td>' . implode('</td><td>', explode("\t", $row)) . '</td>';
            $output .= '</tr>' . PHP_EOL;
        }
        $iterator->next();
    } while ($iterator->valid());
    return $output;
}

#[Library\htmlListUsingForeach("Uses a foreach() loop")]
function htmlListUsingForeachhile(Iterator $iterator)
{
    $output = '<ul>';
    foreach ($iterator as $value)
        $output .= '<li>' . $value . '</li>';
    $output .= '</ul>';
    return $output;
}

#[Library\fetchCityByPostCode(
    "Returns postcode info for a given city",
    "param string city: the target search city",
    "param Iterator iter: the Generator returned from LargeFile"
)]
function fetchPostCodesByCity(string $city, string $fn)
{
    $largeFile = new \Cookbook\Iterator\LargeFile($fn);
    $iterator = $largeFile->getIterator('ByLine');
    return new CallbackFilterIterator($iterator, 
        function($current, $key, $iterator) use ($city) {
            return str_contains($current, $city);
        }
    );
}

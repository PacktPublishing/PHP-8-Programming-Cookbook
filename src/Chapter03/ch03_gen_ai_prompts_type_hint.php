<?php
/*
// 1st pass:
function applyPercentage(array $numbers, float $percentage): array
{
    return array_map(fn($n) => $n * ($percentage / 100), $numbers);
}
$arr = new ArrayObject(range(1,10));
var_dump(applyPercentage($arr, 8));
// result: Fatal Error
*/

// 2nd pass:
/**
 * Applies a percentage factor to each element in an iterable of numeric values.
 *
 * @param iterable $arr The iterable containing numeric values.
 * @param int $percent The percentage factor to apply (e.g., 10 for 10%, 150 for 150%).
 * @return iterable The modified iterable with the percentage factor applied.
 */
function applyPercentage(iterable $arr, int $percent): iterable
{
    foreach ($arr as $key => $value) {
        yield $key => $value * ($percent / 100);
    }
}

$arr = new ArrayObject(range(1,10));
var_dump(iterator_to_array(applyPercentage($arr, 8)));

<?php
namespace Library;

#[Library\smallAdd("Adds float numbers")]
function smallAdd(float $a, float $b)
{
    return $a + $b;
}

#[Library\bigAdd(
    "Adds large numbers",
    "NOTE: requires the bcmath extension"
)]
function bigAdd(string $a, string $b)
{
    return bcadd($a, $b);
}

#[Library\infiniteAdd("Adds an infinite amount of numbers")]
function infiniteAdd(...$nums)
{
    return array_sum($nums);
}


<?php
namespace Library;
use DateTime;

#[Library\returnsString("Returns string value from DateTime instance")]
function returnsString(DateTime $date, string $format) : string
{
  return $date->format($format);
}

#[Library\convertsToString(
    "Return data type 'string'"
)]
function convertsToString(mixed $a, mixed $b, mixed $c) : string
{      
  return $a + $b + $c;
}

#[Library\makesDateTime("Returns a DateTime instance from params")]
function makesDateTime(int $year, int $month, int $day) : DateTime
{
    return (new DateTime())->setDate($year, $month, $day);
}

#[Library\wrongDateTime("Triggers a TypeError")]
function wrongDateTime($year, $month, $day) : DateTime
{
  return date($year . '-' . $month . '-' . $day);
}

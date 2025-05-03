<?php
namespace Cookbook\Appointment;

use DateTime;
class DynamicEnum
{
    public static function getDaysEnum()
    {
        $daysEnum = 'enum Days' . PHP_EOL . '{' . PHP_EOL;
        for ($x = 1; $x < 8; $x++) {
            $str = sprintf('2025-06-%02d', $x);
            $date = new DateTime($str);
            $daysEnum .= '    case ' 
                 . strtoupper($date->format('D'))
                 . ' = \''
                 . $date->format('l')
                 . '\';' . PHP_EOL;
        }
        $daysEnum .= '}' . PHP_EOL;
        return $daysEnum;
    }
    public static function getMonthsEnum()
    {
        $monthsEnum = 'namespace ' . __NAMESPACE__ . ';' . PHP_EOL;
        $monthsEnum .= 'enum Months : int {' . PHP_EOL;
        $monthsEnum .= 'use RandomCaseTrait;' . PHP_EOL;
        $monthsEnum .= 'use MonthNamesTrait;' . PHP_EOL;
        for ($x = 1; $x < 13; $x++) {
            $str = sprintf('2025-%02d-01', $x);
            $date = new DateTime($str);
            $monthsEnum .= '    case ' 
                 . strtoupper($date->format('M'))
                 . ' = ' . $x . ';' . PHP_EOL;
        }
        $monthsEnum .= '}' . PHP_EOL;
        return $monthsEnum;
    }
}

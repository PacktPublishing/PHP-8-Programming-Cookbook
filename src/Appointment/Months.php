<?php
namespace Cookbook\Appointment;

enum Months : int
{
    use RandomCaseTrait;
    public const MONTH_NAMES = [1 => 'January','February','March','April','May','June','July','August','September','October','November','December'];
    case JAN = 1;
    case FEB = 2;
    case MAR = 3;
    case APR = 4;
    case MAY = 5;
    case JUN = 6;
    case JUL = 7;
    case AUG = 8;
    case SEP = 9;
    case OCT = 10;
    case NOV = 11;
    case DEC = 12;
    public static function getName(Months $month)
    {
        return self::MONTH_NAMES[$month->value];
    }
}

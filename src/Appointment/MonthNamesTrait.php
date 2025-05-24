<?php
namespace Cookbook\Appointment;

trait MonthNamesTrait
{
    public const MONTH_NAMES = [1 => 'January','February','March','April','May','June','July','August','September','October','November','December'];
    public static function getName(Months $month)
    {
        return self::MONTH_NAMES[$month->value];
    }
}

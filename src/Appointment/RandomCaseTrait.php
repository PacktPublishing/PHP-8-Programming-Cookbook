<?php
namespace Cookbook\Appointment;

trait RandomCaseTrait
{
    public static function getRandom()
    {
        return self::cases()[array_rand(self::cases())];
    }
}

<?php
namespace Cookbook\Appointment;

class Visitor
{
    public function __construct(
        public int $id,
        public string $name,
        public Months $month,
        public Days   $day,
        public string $time) {}
}

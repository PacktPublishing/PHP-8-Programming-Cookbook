<?php
namespace Cookbook\Appointment;

use DateTime;
class Visitor
{
    public DateTime $date;
    public function __construct(
        public int $id,
        public string $name,
        public Gender $gender,
        public Months $month,
        public int   $day,
        public string $time,
        public int $year = 0) 
    {
        if (empty($year)) $this->year = date('Y');
        $this->date = new DateTime(sprintf('%4d-%02d-%02d', $this->year, $this->month->value, $this->day));
    }
}

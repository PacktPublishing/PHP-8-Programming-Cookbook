<?php
namespace Cookbook\Appointment;

use DateTime;
use ArrayObject;
use SplObjectStorage;
class Calendar
{
    public iterable $calendar;
    public SplObjectStorage $visitors;
    public function __construct(public int $year = 0)
    {
        if (empty($year)) $this->year = date('Y');
        $this->visitors = new SplObjectStorage();
    }
    public function add(Visitor $visitor)
    {
        $this->visitors->attach($visitor);
    }
    public function getLastDay(Months $month) : int
    {
        $str = 'last day of ' 
             . Months::getName($month)
             . ' ' . $this->year;
        return (int) (new DateTime($str))->format('d');
    }
    public function viewAppts(Months $month)
    {
        $this->calendar = new ArrayObject();
        foreach ($this->visitors as $visitor) {
            if ($visitor->year !== $this->year) continue;
            if ($visitor->month !== $month) continue;
            $this->calendar[$visitor->day][$visitor->time][] = $visitor;
        }
        $this->calendar->ksort();
        $monthName = Months::getName($month);
        $txt = 'Appointments for ' . $monthName . ' ' . $this->year . PHP_EOL;
        foreach ($this->calendar as $day => $visits) {
            $txt .= $visits[key($visits)][0]->date->format('D, d M Y') . ': ' . PHP_EOL;
            foreach ($visits as $times) {
                ksort($times);
                foreach ($times as $visitor) {
                    $txt .=  '  ' . $visitor->time . ': ' . $visitor->name . ' [' . $visitor->gender->value . ']' . PHP_EOL;
                }
            }
        }
        return $txt;
    }
}

<?php
namespace Cookbook\Appointment;

use SplObjectStorage;
class MonthlyCalendar
{
    public array $calendar;
    public SplObjectStorage $visitors;
    public function __construct(public Months $month, public int $year = 0)
    {
        if (empty($year)) $this->year = date('Y');
        $this->visitors = new SplObjectStorage();
    }
    public function add(Visitor $visitor)
    {
        $this->visitors->attach($visitor);
    }
    public function view()
    {
        $this->calendar = [];
        foreach ($this->visitors as $visitor) {
            if ($visitor->year !== $this->year) continue;
            if ($visitor->month !== $this->month) continue;
            $calendar[$visitor->day] = $visitor->name;
        }
        ksort($calendar);
        $html = '<h1>' . date($this->month->value) . '</h1>';
        // get first day
        
    }
}

<?php
namespace Cookbook\Appointment;

class WeeklyCalendar
{
    public array $visitors;
    public function add(Visitor $visitor)
    {
        $this->visitors[] = $visitor;
    }
    public function view()
    {
    }
}

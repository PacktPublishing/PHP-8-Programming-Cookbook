<?php
namespace Cookbook\Appointment;
use Iterator;
use ArrayIterator;
use DateTime;
class Calendar
{
    // @param Connection $conn: contains a Connection instance
    public function __construct(public Connection $conn) {}
    // @param Iterator $appts: contains an iteration of Appointment instances for a single day
    public function showApptDetails(Appointment $appt) : string
    {
        /* produce HTML to show all appointment details for the given Appointment instance. */
        return "<div><strong>Title:</strong> " . htmlspecialchars($appt->title) . "<br><strong>Location:</strong> " . htmlspecialchars($appt->location) . "<br><strong>Contact Info:</strong> " . htmlspecialchars($appt->contact_info) . "<br><strong>Start:</strong> " . htmlspecialchars($appt->start_date_and_time) . "<br><strong>End:</strong> " . htmlspecialchars($appt->end_date_and_time) . "</div>";
    }
    public function showApptsByDay(Iterator $appts) : string
    {
        /* 1. produce HTML string of appointments for a single day.
           2. for each appointment only show the title and start time + provide a link to show all appointment details in a pop-up box. */
        $html = '<ul>';
        foreach ($appts as $appt) {
            $startTime = date('H:i', strtotime($appt->start_date_and_time));
            $html .= '<li>' . htmlspecialchars($appt->title) . ' at ' . $startTime . ' <a href="#" onclick="showDetails(' . $appt->id . ')">Details</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
    // @param Iterator $appts: contains an iteration of Appointment instances for a single week
    public function showApptsByWeek(Iterator $appts, string $first_day = '') : string
    {
        /* 1. sort the appointments by start_date_and_time.
           2. from the first appointment in the iteration determine the date of the first day of the week in which the first appointment resides.
           3. display all 7 days for the detected week starting with the first day as follows: */
        // Sort appointments by start_date_and_time
        $apptArray = iterator_to_array($appts);
        usort($apptArray, function($a, $b) {
            return strcmp($a->start_date_and_time, $b->start_date_and_time);
        });
        $appts = new ArrayIterator($apptArray);

        if (!$appts->valid()) {
            return '<table class="table-style"></table>';
        }

        $firstAppt = $appts->current();
        $firstDate = new DateTime($firstAppt->start_date_and_time);
        $first_day = empty($first_day) ? $this->conn::FIRST_DAY : $first_day;
        $startOfWeek = clone $firstDate;
        $startOfWeek->modify($first_day . ' this week');

        $html = '<table class="table-style"><thead><tr>';
        /* add an HTML table header row containing day names and numbers: */
        for ($x = 0; $x < 7; $x++) {
            /* 1. calculate the date for $x day of the selected week.
               2. each element in the header will contain the date information for this date as follows: DateTimeInterface::format('D d'). */
            $date = clone $startOfWeek;
            $date->modify("+$x days");
            $html .= '<th>' . $date->format('D d') . '</th>';
        }
        $html .= '</tr></thead><tbody><tr>';
        /* add an HTML table row containing a list of appointments for each day of the selected week: */
        for ($x = 0; $x < 7; $x++) {
            /* 1. calculate the date for $x day of the selected week.
               2. append the output from showApptsByDay() for iteration elements for this day: 
                  hint: pass to showApptsByDay() an anonymous class the implements FilterIterator, with a filter for this date. */
            $dayDate = clone $startOfWeek;
            $dayDate->modify("+$x days");
            $dayStr = $dayDate->format('Y-m-d');
            $filteredAppts = new class($appts, $dayStr) extends FilterIterator {
                private $day;
                public function __construct(Iterator $iterator, $day) {
                    parent::__construct($iterator);
                    $this->day = $day;
                }
                public function accept() {
                    $appt = $this->getInnerIterator()->current();
                    $apptDate = date('Y-m-d', strtotime($appt->start_date_and_time));
                    return $apptDate === $this->day;
                }
            };
            $html .= '<td>' . $this->showApptsByDay($filteredAppts) . '</td>';
        }
        $html .= '</tr></tbody></table>';
        return $html;
    }
    // @param Iterator $appts: contains an iteration of Appointment instances for a single month
    public function showApptsByMonth(Iterator $appts, string $first_day = '') : string
    {
        /* 1. sort the appointments by start_date_and_time.
           2. in a loop call showApptsByWeek() for all weeks in the month the iteration of appointments represents. */
        // Sort appointments by start_date_and_time
        $apptArray = iterator_to_array($appts);
        usort($apptArray, function($a, $b) {
            return strcmp($a->start_date_and_time, $b->start_date_and_time);
        });
        $appts = new ArrayIterator($apptArray);

        if (!$appts->valid()) {
            return '';
        }

            $firstAppt = $appts->current();
        $firstDate = new DateTime($firstAppt->start_date_and_time);
        $monthStart = clone $firstDate;
        $monthStart->modify('first day of this month');
        $monthEnd = clone $firstDate;
        $monthEnd->modify('last day of this month');
        $first_day = empty($first_day) ? $this->conn::FIRST_DAY : $first_day;

        $currentWeekStart = clone $monthStart;
        $currentWeekStart->modify($first_day . ' this week');
        if ($currentWeekStart > $monthStart) {
            $currentWeekStart->modify('-7 days');
        }

        $html = '';
        while ($currentWeekStart <= $monthEnd) {
            $weekEnd = clone $currentWeekStart;
            $weekEnd->modify('+6 days');
            $filteredAppts = new class($appts, $currentWeekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')) extends FilterIterator {
                private $start, $end;
                public function __construct(Iterator $iterator, $start, $end) {
                    parent::__construct($iterator);
                    $this->start = $start;
                    $this->end = $end;
                }
                public function accept() {
                    $appt = $this->getInnerIterator()->current();
                    $apptDate = date('Y-m-d', strtotime($appt->start_date_and_time));
                    return $apptDate >= $this->start && $apptDate <= $this->end;
                }
            };
            $html .= $this->showApptsByWeek($filteredAppts, $first_day);
            $currentWeekStart->modify('+7 days');
        }
        return $html;
    }
}

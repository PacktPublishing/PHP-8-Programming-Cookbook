<?php
namespace Cookbook\Appointment;
use PDO;
use Iterator;
use DateTime;
use ArrayIterator;
class Connection
{
    public const DRIVER = 'mysql';
    public const FIRST_DAY = 'sun';
    public const WEEK_DAYS = ['sun','mon','tue','wed','thu','fri','sat','sun','mon','tue','wed','thu','fri','sat'];
    public const ERR_DB = 'ERROR: unable to connect to the database';
    public ?PDO $pdo = NULL;

    public function __construct(string $user, string $pwd, string $host, string $db_name, string $driver = '')
    {
        if (empty($user) || empty($pwd) || empty($db_name) || empty($host)) {
            throw new InvalidArgumentException(static::ERR_DB);
        }
        $dsn = 'mysql:host=' . $host . ';dbname=' . $db_name;
        $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO($dsn, $user, $pwd, $opts);
    }
    public function addAppointment(Appointment $appt) : bool
    {
        // Insert the Appointment instance into the "appointment" table
        // return TRUE if add was successful, FALSE otherwise
        $sql = 'INSERT INTO appointment (title, location, contact_info, start_date_and_time, end_date_and_time) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute([$appt->title, $appt->location, $appt->contact_info, $appt->start_date_and_time, $appt->end_date_and_time]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function findAppts(string $start_date = '', string $end_date = '') : ?Iterator
    {
        $sql = 'SELECT * FROM appointment ';
        // if both $start_date and $end_date are set, add a WHERE clause where results are >= $start_date and <= $end_date
        // if $start_date is set but $end_date is not set, add a WHERE clause where results are >= $start_date
        // if $start_date is not set but $end_date is set, add a WHERE clause where results are <= $end_date
        // if none of the above conditions are true, return a list of all appointments
        // all return values must be in the form of ArrayIterator or NULL if no hits
        $params = [];
        $start_date .= (!empty($start_date)) ? ' 00:00' : '';
        $end_date   .= (!empty($end_date))   ? ' 23:59' : '';
        if (!empty($start_date) && !empty($end_date)) {
            $sql .= 'WHERE start_date_and_time >= ? AND end_date_and_time <= ?';
            $params = [$start_date, $end_date];
        } elseif (!empty($start_date)) {
            $sql .= 'WHERE start_date_and_time >= ?';
            $params = [$start_date];
        } elseif (!empty($end_date)) {
            $sql .= 'WHERE end_date_and_time <= ?';
            $params = [$end_date];
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($results)) {
            return null;
        }
        $appointments = [];
        foreach ($results as $row) {
            $appointments[] = new Appointment(
                id: $row['id'],
                title: $row['title'],
                location: $row['location'],
                contact_info: $row['contact_info'],
                start_date_and_time: $row['start_date_and_time'],
                end_date_and_time: $row['end_date_and_time']
            );
        }
        return new ArrayIterator($appointments);
    }
    public function findApptsByDay(string $start_date = '') : ?Iterator
    {
        return $this->findAppts($start_date, $start_date);
    }
    public function findApptsByWeek(string $start_date = '', string $first_day = '') : ?Iterator
    {
        // from $start_date determine the week to search
        // return the results of findAppts() with the start date == the 1st day of the search week, and end date == last day of the search week
        $date = new DateTime($start_date);
        $first_day = (empty($first_day)) ? static::FIRST_DAY : $first_day;
        // ADDED: find out how many days offset is the start date from the first day of the week
        $day_of_week     = strtolower($date->format('D'));
        $day_of_week_pos = array_search($day_of_week, static::WEEK_DAYS);
        $first_day_pos   = array_search($first_day, static::WEEK_DAYS);
        $relative_pos    = $day_of_week_pos - $first_day_pos;
        if ($relative_pos > 0) {
            $start = $date->modify('-' . $relative_pos . ' days')->format('Y-m-d 00:00');
        } else {
            $start = $date->format('Y-m-d 00:00');
        }
        // changed this to last day
        $end = $date->modify('+7 days')->format('Y-m-d 23:59');
        return $this->findAppts($start, $end);
    }
    public function findApptsByMonth(string $start_date = '') : ?Iterator
    {
        // from $start_date determine the month to search
        // return the results of findAppts() with the start date == the 1st of the search month, and end date == last day of the search month
        $date = new DateTime($start_date);
        $start = $date->modify('first day of this month')->format('Y-m-d');
        $end = $date->modify('last day of this month')->format('Y-m-d');
        return $this->findAppts($start, $end);
    }
}

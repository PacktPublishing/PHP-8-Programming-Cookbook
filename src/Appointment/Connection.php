<?php
namespace Cookbook\Appointment;
use PDO;
class Connection
{
    public const string DRIVER = 'mysql';
    public const string ERR_DB = 'ERROR: unable to connect to the database';
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
    public function addAppointment(Appointment $appt, Location $loc) : bool
    {
        $sql = 'SELECT * FROM location WHERE id = ?';
        $locStmt = $pdo->prepare($sql);
        $locResult = $locStmt->execute([$loc->id ?? 0]);
        // If you get a hit, create a new Location instance from the return value of the database query, and assign $locID = $loc->id
        // If no results, add the provided Location instance to the "location" table and save the auto-generated ID into $locID
        // Assign $appt->location_id = $locID
        // Insert the Appointment instance into the "appointment" table
    }
    public function findApptsByDate(Appointment $appt, Location $loc) : bool
    {
        $sql = 'SELECT * FROM appointment ';
        // if both $appt->start_date_and_time and $appt->end_date_and_time are set, add a WHERE clause where results are >= $appt->start_date_and_time and <= $appt->end_date_and_time
        // if $appt->start_date_and_time is set but $appt->end_date_and_time is not set, add a WHERE clause where results are >= $appt->start_date_and_time
        // if $appt->start_date_and_time is not set but $appt->end_date_and_time is set, add a WHERE clause where results are <= $appt->end_date_and_time
        // if none of the above conditions are true, return a list of all appointments
        
    }
}

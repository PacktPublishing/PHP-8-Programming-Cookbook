<?php
// IMPORTANT: use appointment.sql to create the table

// replaced "require" statements with Composer autoloader:
/*
require 'Appointment.php';
require 'Location.php';
require 'Connection.php';
require 'Calendar.php';
*/
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Appointment\Connection;
use Cookbook\Appointment\Appointment;

// added db config
$db = include __DIR__ . '/../../config/db.config.php';

// Database connection - adjust credentials as needed
$conn = new Connection($db['db_usr'], $db['db_pwd'], $db['db_host'], $db['db_name']);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => '', 'html' => ''];
    if (isset($_POST['action'])) {
        // ADDED sanitize all inputs
        $post = $_POST;
        foreach ($post as $key => $val) {
            $post[$key] = trim(strip_tags($val));
        }
        // REMOVED calls to sanitize_input()
        if ($post['action'] === 'get_appointments') {
            $start_date = $post['start_date'] ?? '';
            $type       = $post['type'] ?? 'day';
            $first_day  = $post['first_day'] ?? '';
            // Validate date
            if (!empty($start_date) && !DateTime::createFromFormat('Y-m-d', $start_date)) {
                $response['message'] = 'Invalid start date.';
            } elseif (!in_array($type, ['day', 'week', 'month'])) {
                $response['message'] = 'Invalid type.';
            } else {
                try {
                    if ($type === 'day') {
                        $appts = $conn->findApptsByDay($start_date);
                    } elseif ($type === 'week') {
                        $appts = $conn->findApptsByWeek($start_date, $first_day);
                    } elseif ($type === 'month') {
                        $appts = $conn->findApptsByMonth($start_date);
                    }
                    error_log(__FILE__ . ':' . __LINE__ . ':APPTS:' . var_export($appts, TRUE));
                    if (empty($appts)) {
                        $html = 'No Appointments';
                    } else {
                        $html = '<table>';
                        $html .= '<tr><th>Start</th><th>End</th><th>Title</th><th>Location</th><th>Contact info</th></tr>';
                        foreach ($appts as $key => $appt) {
                            $color = ($key % 2 === 0) ? 'F0F0F0' : 'FFFFFF';
                            $html .= '<tr style="background-color:#' . $color . ';">';
                            $html .= '<td>' . htmlspecialchars($appt->start_date_and_time ?? '') . '</td>';
                            $html .= '<td>' . htmlspecialchars($appt->end_date_and_time ?? '') . '</td>';
                            $html .= '<td>' . htmlspecialchars($appt->title ?? '') . '</td>';
                            $html .= '<td>' . htmlspecialchars($appt->location ?? '') . '</td>';
                            $html .= '<td>' . htmlspecialchars($appt->contact_info ?? '') . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= '</table>';
                    }
                    $response = ['success' => true, 'html' => $html];
                } catch (Exception $e) {
                    $response['message'] = 'Error fetching appointments: ' . $e->getMessage();
                }
            }
        } elseif ($post['action'] === 'add_appointment') {
            // REMOVED calls to sanitize_input()
            $title           = $post['title'] ?? '';
            $location        = $post['location'] ?? '';
            $contact_info    = $post['contact_info'] ?? '';
            $start_date_time = $post['start_date_time'] ?? '';
            $end_date_time   = $post['end_date_time'] ?? '';

            // Validate required fields and datetime
            if (empty($title) || empty($location) || empty($start_date_time) || empty($end_date_time)) {
                $response['message'] = 'All required fields must be filled.';
            } elseif (!DateTime::createFromFormat('Y-m-d\TH:i', $start_date_time) || !DateTime::createFromFormat('Y-m-d\TH:i', $end_date_time)) {
                $response['message'] = 'Invalid date/time format.';
            } else {
                $appt = new Appointment(null, $title, $location, $contact_info, $start_date_time, $end_date_time);
                if ($conn->addAppointment($appt)) {
                    $response = ['success' => true, 'message' => 'Appointment added successfully.'];
                } else {
                    $response['message'] = 'Failed to add appointment.';
                }
            }
        }
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Calendar</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .container {
        font-family:    Arial, Helvetica, sans-serif;
        width: 80%;
        margin: auto;
    }
    td, th {
        border: solid thin black;
        padding: 5px;
    }
    </style>
</head>
<body>
    <div class="container">
    <h1>Appointment Calendar</h1>

    <div id="display-area">
        <!-- Appointments will be displayed here -->
    </div>

    <h2>Get Appointments</h2>
    <form id="get-appointments-form">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br><br>
        <label>Type:</label>&nbsp;
        <input type="radio" id="day" name="type" value="day" checked>&nbsp;
        <label for="day">Day</label>
        <input type="radio" id="week" name="type" value="week">&nbsp;
        <label for="week">Week</label>
        <input type="radio" id="month" name="type" value="month">&nbsp;
        <label for="month">Month</label><br><br>
        <button type="submit">Get Appointments</button>
    </form>

    <h2>Add Appointment</h2>
    <form id="add-appointment-form">
        <table>
        <tr>
        <th><label for="title">Title:</label></th>
        <td><input type="text" id="title" name="title" required maxlength="16"></td>
        </tr>
        <tr>
        <th><label for="location">Location:</label></th>
        <td><input type="text" id="location" name="location" required></td>
        </tr>
        <tr>
        <th><label for="contact_info">Contact Info:</label></th>
        <td><input type="text" id="contact_info" name="contact_info"></td>
        </tr>
        <tr>
        <th><label for="start_date_time">Start Date and Time:</label></th>
        <td><input type="datetime-local" id="start_date_time" name="start_date_time" required></td>
        </tr>
        <tr>
        <th><label for="end_date_time">End Date and Time:</label></th>
        <td><input type="datetime-local" id="end_date_time" name="end_date_time" required></td>
        </tr>
        <tr>
        <td colspan=2><button type="submit">Add Appointment</button></td>
        </table>
    </form>
    </div>
    <script>
        $(document).ready(function() {
            // Handle get appointments form
            $('#get-appointments-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&action=get_appointments&first_day=sun'; // Assuming default first_day
                $.post('', formData, function(response) {
                    if (response.success) {
                        $('#display-area').html(response.html);
                    } else {
                        alert(response.message);
                    }
                }, 'json');
            });

            // Handle add appointment form
            $('#add-appointment-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&action=add_appointment';
                $.post('', formData, function(response) {
                    alert(response.message);
                    if (response.success) {
                        $('#add-appointment-form')[0].reset();
                    }
                }, 'json');
            });
        });

    </script>
</body>
</html>

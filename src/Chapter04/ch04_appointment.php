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
use Cookbook\Appointment\Calendar;
use Cookbook\Appointment\Appointment;

// added db config
$db = include __DIR__ . '/../../config/db.config.php';

// Database connection - adjust credentials as needed
$conn = new Connection($db['db_usr'], $db['db_pwd'], $db['db_host'], $db['db_name']);
$calendar = new Calendar($conn);


// Sanitize and validate inputs
function sanitize_input($data) {
    // $data = trim($data);
    // $data = stripslashes($data);
    // $data = htmlspecialchars($data);
    return trim(strip_tags($data));
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => '', 'html' => ''];

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'get_appointments') {
            $start_date = sanitize_input($_POST['start_date'] ?? '');
            $type = sanitize_input($_POST['type'] ?? 'day');
            $first_day = sanitize_input($_POST['first_day'] ?? '');

            // Validate date
            if (!empty($start_date) && !DateTime::createFromFormat('Y-m-d', $start_date)) {
                $response['message'] = 'Invalid start date.';
            } elseif (!in_array($type, ['day', 'week', 'month'])) {
                $response['message'] = 'Invalid type.';
            } else {
                try {
                    if ($type === 'day') {
                        $appts = $conn->findApptsByDay($start_date);
                        $html = $calendar->showApptsByDay($appts ?: new ArrayIterator([]));
                    } elseif ($type === 'week') {
                        $appts = $conn->findApptsByWeek($start_date, $first_day);
                        $html = $calendar->showApptsByWeek($appts ?: new ArrayIterator([]), $first_day);
                    } elseif ($type === 'month') {
                        $appts = $conn->findApptsByMonth($start_date);
                        $html = $calendar->showApptsByMonth($appts ?: new ArrayIterator([]), $first_day);
                    }
                    error_log(__FILE__ . ':' . __LINE__ . ':' . var_export($appts, TRUE));
                    $response = ['success' => true, 'html' => $html];
                } catch (Exception $e) {
                    $response['message'] = 'Error fetching appointments: ' . $e->getMessage();
                }
            }
        } elseif ($_POST['action'] === 'add_appointment') {
            $title = sanitize_input($_POST['title'] ?? '');
            $location = sanitize_input($_POST['location'] ?? '');
            $contact_info = sanitize_input($_POST['contact_info'] ?? '');
            $start_date_time = sanitize_input($_POST['start_date_time'] ?? '');
            $end_date_time = sanitize_input($_POST['end_date_time'] ?? '');

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
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <h1>Appointment Calendar</h1>

    <div id="display-area">
        <!-- Appointments will be displayed here -->
    </div>

    <h2>Get Appointments</h2>
    <form id="get-appointments-form">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br><br>
        <label>Type:</label><br>
        <input type="radio" id="day" name="type" value="day" checked>
        <label for="day">Day</label><br>
        <input type="radio" id="week" name="type" value="week">
        <label for="week">Week</label><br>
        <input type="radio" id="month" name="type" value="month">
        <label for="month">Month</label><br><br>
        <button type="submit">Get Appointments</button>
    </form>

    <h2>Add Appointment</h2>
    <form id="add-appointment-form">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required maxlength="16"><br><br>
        
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br><br>
        
        <label for="contact_info">Contact Info:</label>
        <input type="text" id="contact_info" name="contact_info"><br><br>
        
        <label for="start_date_time">Start Date and Time:</label>
        <input type="datetime-local" id="start_date_time" name="start_date_time" required><br><br>
        
        <label for="end_date_time">End Date and Time:</label>
        <input type="datetime-local" id="end_date_time" name="end_date_time" required><br><br>
        
        <button type="submit">Add Appointment</button>
    </form>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup"></div>

    <script>
        $(document).ready(function() {
            // Handle get appointments form
            $('#get-appointments-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&action=get_appointments&first_day=Sunday'; // Assuming default first_day
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

        function showPopup(content) {
            $('#popup').html(content);
            $('#overlay').show();
            $('#popup').show();
        }

        $('#overlay').click(function() {
            $('#overlay').hide();
            $('#popup').hide();
        });
    </script>
</body>
</html>

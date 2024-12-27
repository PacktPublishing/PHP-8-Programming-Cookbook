<?php

// Remove whitespaces.
$email = trim($_POST['email']);

// Sanitize input
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Validate
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid Email";
} else {
    echo "Valid Email.";
}
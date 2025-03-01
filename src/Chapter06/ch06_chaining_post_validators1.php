<?php

$email = $_POST['email'];
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Valid Email Address.";
} else {
    echo "Invalid Email Address.";
}

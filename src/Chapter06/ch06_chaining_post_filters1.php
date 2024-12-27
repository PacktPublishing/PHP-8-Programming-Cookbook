<?php
try {
    $username = $_POST['username'] ?? '';
    $username = strip_tags($username); // remove trailing whitespaces
    $username = trim($username); // Strip tags
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); // Convert to escaped characters

    // Accept alphanumeric and underscores only.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        throw new Exception('Invalid username format.');
    }

    echo "Username: $username";
} catch (Exception $exc) {
    echo $exc->getMessage();
}
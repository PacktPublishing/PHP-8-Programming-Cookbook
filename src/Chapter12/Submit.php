<?php

use Cookbook\Chapter12\CsrfToken;

include __DIR__ . '/../../vendor/autoload.php';

$csrf = new CsrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = $_POST['csrf_token'] ?? null;
    if ($csrf->validate($submitted)) {
        echo "CSRF token is valid.";
    } else {
        echo "Nope.";
    }
}
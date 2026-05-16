<?php

use Cookbook\Chapter12\Captcha\ReCaptcha;

include __DIR__ . '/../../vendor/autoload.php';

$captcha = new ReCaptcha();

// configure via setters
$captcha->setSecret('YOUR_SECRET_KEY');
$captcha->setExpectedHost('your-domain.example'); // optional
$captcha->setTimeoutSeconds(5);

// must call initialise() after setting
$captcha->initialise();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('method not allowed');
}

$email = trim($_POST['email'] ?? '');
$token = $_POST['g-recaptcha-response'] ?? '';

if ($email === '' || $token === '') {
    http_response_code(400);
    exit('missing fields');
}

$result = $captcha->verify($token, $_SERVER['REMOTE_ADDR'] ?? null);

if (!$result['ok']) {
    http_response_code(400);
    exit('verification failed: ' . ($result['error'] ?? 'unknown error'));
}

echo 'form submitted successfully';

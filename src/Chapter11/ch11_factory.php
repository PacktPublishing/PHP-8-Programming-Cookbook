<?php

use Cookbook\Chapter11\NotificationFactory;
use Cookbook\Chapter11\NotificationType;

include __DIR__ . '/../../vendor/autoload.php';

$notificationFactory = new NotificationFactory();

// Create an SMS Notification object
$sms = $notificationFactory->create(NotificationType::SMS);

// Create an EMAIL Notification object
$email = $notificationFactory->create(NotificationType::EMAIL);

// Create a Legacy Email Notification object
$legacyEmail = $notificationFactory->create(NotificationType::LEGACY_EMAIL);
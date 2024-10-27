<?php
return [
  'driver' => 'sqlite',
  'dbname' => __DIR__ . '/../data/php8cookbook.db',
  'opts'   => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
];

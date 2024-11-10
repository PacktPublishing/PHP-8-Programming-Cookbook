<?php
return [
    'ch02' => [
        'driver'  => 'sqlite',
        'dbname'  => __DIR__ . '/../data/cookbook_names.db',
        'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    ],
];

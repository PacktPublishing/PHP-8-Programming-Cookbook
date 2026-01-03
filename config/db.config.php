<?php
return [
    'db_driver'  => 'mysql',
    'db_port'    => 3306,
    'db_name'    => 'php8cookbook',
    'db_usr'     => 'cookbook',
    'db_pwd'     => 'password',
    'db_host'    => 'mysql.local',
    'options'    => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
];

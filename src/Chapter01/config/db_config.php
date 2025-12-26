<?php
// load "secrets"
$secrets = file('/tmp/secrets.sh');
array_walk($secrets, fn(&$line) => ($line = trim(str_replace('export ', '', $line))));
$get_secret = function (string $key) use ($secrets) : string {
    $callback = function ($val) use ($key) { return str_contains($val, $key); };
    $line = array_find($secrets, $callback);
    if (!empty($line) && str_contains($line, '=')) {
        $val = str_replace([$key, '='], '', $line);
    } else {
        $val = '';
    }
    return $val;
};
return [ 
    'db' => [
        'DB_HOST' => $get_secret('DB_HOST'),
        'DB_PORT' => 3306,
        'DB_NAM'  => $get_secret('DB_NAM'),
        'DB_USR'  => $get_secret('DB_USR'),
        'DB_PWD'  => $get_secret('DB_PWD'),
    ],
];

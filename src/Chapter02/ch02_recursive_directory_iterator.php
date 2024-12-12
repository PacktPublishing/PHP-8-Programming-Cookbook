<?php
require __DIR__ . '/../../vendor/autoload.php';
$path = $argv[1] ?? realpath(__DIR__ . '/../');
$action = $argv[2] ?? 'ls';
$dirObj = new Cookbook\Iterator\Directory($path);
try {
    if ($action === 'dir') {
        $label = 'Mimics "dir /s" ';
        $result = $dirObj->dir('*.php'); 
    } else {
        $label = 'Mimics "ls -l -R" ';
        $result = $dirObj->ls('*.php'); 
    }
    echo $label . PHP_EOL;
    foreach ($result as $info) echo $info . PHP_EOL;
} catch (Throwable $e) {
    echo $e->getMessage();
}

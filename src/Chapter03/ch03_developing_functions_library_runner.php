<?php
include __DIR__ . '/ch03_developing_functions_library.php';

echo \Library\smallAdd(111.11, 222.22) . PHP_EOL;
echo \Library\bigAdd('123456789123456789', '234567890234567890') . PHP_EOL;
echo \Library\infiniteAdd(111.11, 222.22, 333.33, 444.44) . PHP_EOL;

// actual output:
/*
333.33
358024679358024679
1111.1
*/



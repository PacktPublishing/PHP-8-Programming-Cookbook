<?php
/*
// This is from the PHP 7.4 to 8 Migration Guide:
// https://www.php.net/manual/en/migration80.incompatible.php

A number of notices have been converted into warnings:

    Attempting to read an undefined variable.
    Attempting to read an undefined property.
    Attempting to read an undefined array key.
    Attempting to read a property of a non-object.
    Attempting to access an array index of a non-array.
    Attempting to convert an array to string.
    Attempting to use a resource as an array key.
    Attempting to use null, a boolean, or a float as a string offset.
    Attempting to read an out-of-bounds string offset.
    Attempting to assign an empty string to a string offset.

 A number of warnings have been converted into Error exceptions:

    Attempting to write to a property of a non-object. Previously this implicitly created an stdClass object for null, false and empty strings.
    Attempting to append an element to an array for which the PHP_INT_MAX key is already used.
    Attempting to use an invalid type (array or object) as an array key or string offset.
    Attempting to write to an array index of a scalar value.
    Attempting to unpack a non-array/Traversable.
    Attempting to access unqualified constants which are undefined. Previously, unqualified constant accesses resulted in a warning and were interpreted as strings.
    Passing the wrong number of arguments to a non-variadic built-in function will throw an ArgumentCountError.
    Passing invalid countable types to count() will throw a TypeError.

*/

$obj = new class() {};

$arr = [
    'Notice to Warning' => [
        'Attempting to read an undefined variable' => 
            function () { echo $a; },
        'Attempting to read an undefined property' => 
            function () { echo (new class() {})->undefined; },
        'Attempting to read an undefined array key' => 
            function () { $arr = ['A' => 111]; echo $arr['B']; },
        'Attempting to read a property of a non-object' => 
            function () { $arr = ['A' => 111]; echo $arr->property; },
        'Attempting to access an array index of a non-array' => 
            function () { $a = 12345; echo $a[0]; },
        'Attempting to convert an array to string' => 
            function () { $arr = ['A' => 111]; var_dump((string) $arr); },
        'Attempting to use a resource as an array key' => 
            function () { $fh = fopen('gettysburg_address.txt', 'r'); $arr[$fh] = 'Gettysburg'; },
        'Attempting to use NULL, a boolean, or a float as a string offset' => \
            function () { $str = 'ABC'; echo $str[TRUE]; },
        'Attempting to read an out-of-bounds string offset' => 
            function () { $str = 'ABC'; echo $str[99]; },
        'Attempting to assign an empty string to a string offset' => 
            function () { $str = 'ABC'; $str[0] = ''; },
    ],
    'Warnings to Error' => [
    ],
];


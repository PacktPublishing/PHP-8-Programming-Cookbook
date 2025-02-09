<?php
include (__DIR__ . '/ch03_developing_func_lib.php');

$date   = new DateTime();
$format = 'l, d M Y';
$now    = \Library\returnsString($date, $format);
echo $now . PHP_EOL;
var_dump($now);

/* output: 
Sunday, 09 Feb 2025
string(19) "Sunday, 09 Feb 2025"
*/

var_dump(\Library\convertsToString(2, 3, 4));

/* output: 
string(1) "9"
*/

var_dump(\Library\makesDateTime(2015, 11, 21));

/* output: 
object(DateTime)#2 (3) {
  ["date"]=>
  string(26) "2015-11-21 05:06:51.232673"
  ["timezone_type"]=>
  int(3)
  ["timezone"]=>
  string(3) "UTC"
}
*/

try {
    var_dump(wrongDateTime(2015, 11, 21));
} catch (TypeError $e) {
    echo $e->getMessage();
}


/* output: 
PHP Fatal error:  Uncaught Error: Call to undefined function wrongDateTime() in ch03_developing_func_return_types.php
*/


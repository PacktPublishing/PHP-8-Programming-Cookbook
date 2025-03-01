<?php
require __DIR__ . '/ch03_array_search_func_lib.php';
// build postcode => city array
$multi = \Library\buildArrays();

// entries near the end of the array
$srch1 = 'Lyman';
$srch2 = 'Lym';

//******************* SINGLE-DIM SEARCH ****************************//

// using array_search()
$start  = microtime(TRUE);
$label  = 'array_search()';
$result = array_search($srch1, $cities);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // int(99510)

$start  = microtime(TRUE);
$result = array_search($srch2, $cities);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // bool(false)

// using in_array()
$start  = microtime(TRUE);
$label  = 'in_array()';
$result = in_array($srch1, $cities);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // bool(true)

$start  = microtime(TRUE);
$result = in_array($srch2, $cities);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // bool(false)

// using array_filter()
$start  = microtime(TRUE);
$label  = 'array_filter()';
$result = array_filter($cities, function ($val) use ($srch1) { return str_contains($val, $srch1); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  
// results:
/*
array(2) {
  [99510]=>
  string(9) "Anchorage"
  [99522]=>
  string(9) "Anchorage"
}
*/

$start  = microtime(TRUE);
$result = array_filter($cities, function ($val) use ($srch2) { return str_contains($val, $srch2); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // NOTE: position of the array element has a marginal difference -- look at the elapsed time

$start  = microtime(TRUE);
$city   = 'Anchorage';
$result = array_filter($cities, function ($val) use ($city) { return str_contains($val, $city); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  

// using array_find()
$start  = microtime(TRUE);
$label  = 'array_find()';
$result = array_find($cities, function ($val) use ($srch1) { return str_contains($val, $srch1); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  

$result = array_find($cities, function ($val) use ($srch2) { return str_contains($val, $srch2); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  

// using array_any()
$start  = microtime(TRUE);
$label  = 'array_any()';
$result = array_any($cities, function ($val) use ($srch1) { return str_contains($val, $srch1); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  

$result = array_any($cities, function ($val) use ($srch2) { return str_contains($val, $srch2); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  



//******************* MULTI-DIM SEARCH ****************************//
// using array_search()
$start  = microtime(TRUE);
$label  = 'array_search()';
$result = array_search($srch1, $multi);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  // bool(false)

// using array_filter() w/ implode()
$start  = microtime(TRUE);
$label  = 'array_filter() w/ implode()';
$result = array_filter($multi, function ($val) use ($srch1) { return str_contains(implode(' ', $val), $srch1); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  
// results:
/*
array(2) {
  [84749]=>
  array(12) { ...  }
  [82937]=>
  array(12) { ... }
}
*/

// using array_filter() w/ json_encode()
$start  = microtime(TRUE);
$label  = 'array_filter() w/ json_encode()';
$result = array_filter($multi, function ($val) use ($srch1) { return str_contains(json_encode($val), $srch1); });
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
var_dump($result);  
// results:
/*
array(2) {
  [84749]=>
  array(12) { ...  }
  [82937]=>
  array(12) { ... }
}
*/


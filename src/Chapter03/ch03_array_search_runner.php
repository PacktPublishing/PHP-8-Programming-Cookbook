<?php
require __DIR__ . '/ch03_array_search_func_lib.php';
// build postcode => city array
$multi = \Library\buildArrays();
$srch1 = 'Anch'; // near the start
$srch2 = 'Lyma'; // near the end

// first entry near the start of the array case sensitive
$start  = microtime(TRUE);
$label  = 'Search array near start, case sensitive, return first match';
$result = \Library\fetch($multi, $srch1, TRUE, TRUE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
echo implode("\t", $result) . PHP_EOL;

// first entry near the end of the array case sensitive
$start  = microtime(TRUE);
$label  = 'Search array near end, case sensitive, return first match';
$result = \Library\fetch($multi, $srch2, TRUE, TRUE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
echo implode("\t", $result) . PHP_EOL;

// first entry near the start of the array case insensitive
$start  = microtime(TRUE);
$label  = 'Search array near start, case sensitive, return first match';
$result = \Library\fetch($multi, $srch1, FALSE, TRUE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
echo implode("\t", $result) . PHP_EOL;

// first entry near the end of the array case insensitive
$start  = microtime(TRUE);
$label  = 'Search array near end, case sensitive, return first match';
$result = \Library\fetch($multi, $srch2, FALSE, TRUE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
echo implode("\t", $result) . PHP_EOL;

// all entries near the start of the array case sensitive
$start  = microtime(TRUE);
$label  = 'Search array near start, case sensitive, return all matches';
$result = \Library\fetch($multi, $srch1, TRUE, FALSE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
foreach ($result as $row) echo implode("\t", $row) . PHP_EOL;

// all entries near the end of the array case sensitive
$start  = microtime(TRUE);
$label  = 'Search array near end, case sensitive, return all matches';
$result = \Library\fetch($multi, $srch2, TRUE, FALSE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
foreach ($result as $row) echo implode("\t", $row) . PHP_EOL;

// all entries near the start of the array case insensitive
$start  = microtime(TRUE);
$label  = 'Search array near start, case sensitive, return all matches';
$result = \Library\fetch($multi, $srch1, FALSE, FALSE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
foreach ($result as $row) echo implode("\t", $row) . PHP_EOL;

// all entries near the end of the array case insensitive
$start  = microtime(TRUE);
$label  = 'Search array near end, case sensitive, return all matches';
$result = \Library\fetch($multi, $srch2, FALSE, FALSE);
printf('Label: %10s | Time: %.8f' . PHP_EOL, $label, (microtime(TRUE) - $start));
foreach ($result as $row) echo implode("\t", $row) . PHP_EOL;

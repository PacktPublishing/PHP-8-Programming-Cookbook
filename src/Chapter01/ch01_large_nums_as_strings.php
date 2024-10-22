<?php
// shows that large numbers show as scientific notation when converted to string
$arr = [
    1_000,
    1_000_000,
    1_000_000_000,
    1_000_000_000_000,
    1_000_000_000_000_000,
    1_000_000_000_000_000_000,
    1_000_000_000_000_000_000_000,
    1_000_000_000_000_000_000_000_000,
];
foreach ($arr as $num) {
    echo (string) $num;
    echo PHP_EOL;
}
// actual output:
/*
1000
1000000
1000000000
1000000000000
1000000000000000
1000000000000000000
1.0E+21
1.0E+24
*/


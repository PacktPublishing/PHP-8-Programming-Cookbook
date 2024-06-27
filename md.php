<?php
$arr = range(1,15);
foreach ($arr as $num) {
    $dir = sprintf('chapter%02d', $num);
    mkdir($dir);
}

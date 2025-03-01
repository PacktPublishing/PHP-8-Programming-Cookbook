<?php

$region = strtolower($_GET['region']);
if ($region == 'uk') {
    echo "<input type='text' value='NI Number' name='ni_number' />";
} else {
    if ($region == 'au') {
        echo "<input type='text' value='Tax File Number' name='tax_file_number' />";
    } else {
        echo "<input type='text' value='Social Security Number' name='sss_number' />";
    }
}
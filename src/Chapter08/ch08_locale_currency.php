<?php

$number = 1555231.36;

// Currency formatters
$currency_us = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$currency_de = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);
$currency_in = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
$currency_ar = new NumberFormatter('ar_SA', NumberFormatter::CURRENCY);
$currency_he = new NumberFormatter('he_IL', NumberFormatter::CURRENCY);

// Currency
echo "<h3>Currency Formats:</h3>";
echo "US: " . $currency_us->format($number) . "<br>";
echo "German: " . $currency_de->format($number) . "<br>";
echo "Indian: " . $currency_in->format($number) . "<br>";
echo "Arabic: " . $currency_ar->format($number) . "<br>";
echo "Hebrew: " . $currency_he->format($number) . "<br>";

// RTL Example
echo "<h3>RTL Styling:</h3>";
echo '<div style="float:left; width:300px; direction: rtl; text-align: right; ">';
echo "מטבע: " . $currency_he->format($number);
echo '</div>';
?>

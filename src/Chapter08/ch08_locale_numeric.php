<?php
$number = 1555231.36;

// NumberFormatter concrete instances
$formatter_us = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
$formatter_de = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
$formatter_in = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
$formatter_ar = new NumberFormatter('ar_SA', NumberFormatter::DECIMAL);
$formatter_he = new NumberFormatter('he_IL', NumberFormatter::DECIMAL);

echo "<h3>Numeric Formats:</h3>";
echo "US: " . $formatter_us->format($number) . "<br>";
echo "German: " . $formatter_de->format($number) . "<br>";
echo "Indian: " . $formatter_in->format($number) . "<br>";
echo "Arabic: " . $formatter_ar->format($number) . "<br>";
echo "Hebrew: " . $formatter_he->format($number) . "<br>";

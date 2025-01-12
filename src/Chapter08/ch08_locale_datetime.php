<?php
$date = new DateTime('2025-01-01 00:00:00');

// IntlDateFormatter per locale instances:
$dateFormatter_us = new IntlDateFormatter('en_US', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
$dateFormatter_de = new IntlDateFormatter('de_DE', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
$dateFormatter_fr = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
$dateFormatter_in = new IntlDateFormatter('en_IN', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
$dateFormatter_ar = new IntlDateFormatter('ar_SA', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
$dateFormatter_he = new IntlDateFormatter('he_IL', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);

// Formatting
echo "<h3>Date and Time Formats:</h3>";
echo "US (en_US): " . $dateFormatter_us->format($date) . "<br>";
echo "German (de_DE): " . $dateFormatter_de->format($date) . "<br>";
echo "French (fr_FR): " . $dateFormatter_fr->format($date) . "<br>";
echo "Indian (en_IN): " . $dateFormatter_in->format($date) . "<br>";
echo "Arabic (ar_SA): " . $dateFormatter_ar->format($date) . "<br>";
echo "Hebrew (he_IL): " . $dateFormatter_he->format($date) . "<br>";

// RTL Example
echo "<h3>RTL Example:</h3>";
echo '<div style="float:left; width:400px; direction: rtl; text-align: right;">';
echo "תאריך ושעה: " . $dateFormatter_he->format($date);
echo '</div>';

echo '</body></html>';

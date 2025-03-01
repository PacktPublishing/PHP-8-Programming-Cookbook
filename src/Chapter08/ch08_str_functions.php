<?php
// UTF-8 strings
echo "=== Using strlen and mb_strlen ===\n";
$hebrewString = 'שָׁלוֹם';
echo "Source - Hebrew: $hebrewString\n";
echo "Single-byte strlen: " . strlen($hebrewString) . "\n";
echo "Multi-byte mb_strlen: " . mb_strlen($hebrewString, "UTF-8") . "\n";

echo "\n=== Using substr and mb_substr ===\n";
$germanString = "Ä Ö Ü ß";
echo "Source - German: $germanString\n";
echo "Single-byte substr: " . substr($germanString, 0, 3) . "\n";
echo "Multi-byte mb_substr: " . mb_substr($germanString, 0, 3, "UTF-8") . "\n";

echo "\n=== Using strpos and mb_strpos ===\n";
$polishString = "Ą Ć Ł Ó Ś Ż";
$needle = "Ć";
echo "Source - Polish: $polishString\n";
echo "Single-byte strpos: " . strpos($polishString, $needle) . "\n";
echo "Multi-byte mb_strpos: " . mb_strpos($polishString, $needle, 0, "UTF-8") . "\n";

echo "\n=== Using strtolower and mb_strtolower ===\n";
$spanishUpper = "Á É Í Ñ Ó Ú";
echo "Source - Spanish: $spanishUpper\n";
echo "Single-byte strtolower: " . strtolower($spanishUpper) . "\n";
echo "Multi-byte mb_strtolower: " . mb_strtolower($spanishUpper, "UTF-8") . "\n";

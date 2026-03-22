<?php
use Cookbook\REST\GenAiConnect;
define('DISTANCE_UNITS', ['km','miles']);
#[distance(
    "@param GenAiConnect connect : GenAiConnect instance",
    "@param string city_from : City to start from",
    "@param string city_to   : Destination city",
    "@param string iso2_from : ISO2 code of from country",
    "@param string iso2_to   : ISO2 code of destination country",
    "@param string units     : km | miles",
    "@return string response : Translated phrase",
    "@throws InvalidArgumentException"
)]    
function distance(GenAiConnect $connect, $args)
{
    require_once __DIR__ . '/verify_iso2.php';
    $city_from = $args['city_from'] ?? '';
    $city_to  = $args['city_to'] ?? '';
    $iso2_from = $args['iso2_from'] ?? 'Unknown';
    $iso2_to  = $args['iso2_to'] ?? 'Unknown';
    $units = $args['units'] ?? '';
    if (empty($city_from)) {
        throw new \InvalidArgumentException('Need to supply a source city.');
    }
    if (empty($city_to)) {
        throw new \InvalidArgumentException('Need to supply a destination city.');
    }
    if (!verify_iso2($iso2_from)) {
        throw new \InvalidArgumentException("Invalid source ISO2 code: $iso2_from");
    }
    if (!verify_iso2($iso2_to)) {
        throw new \InvalidArgumentException("Invalid destination ISO2 code: $iso2_to");
    }
    if (!in_array($units, DISTANCE_UNITS)) {
        throw new \InvalidArgumentException('Unit must be one of: ' . implode(',', DISTANCE_UNITS));
    }
    $prompt = "Give me the distance in $units "
            . "from: $city_from, $iso2_from, "
            . "to: $city_to, $iso2_to. "
            . 'Return only the distance with no explanation.';
    return $connect->makeGenAIcall($prompt);
}

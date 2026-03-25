<?php
namespace Cookbook\REST\Library;
use Cookbook\REST\GenAiConnect;
use Cookbook\REST\Library\Traits\VerifyIso2Trait;
class distance implements MicroserviceInterface
{
    use VerifyIso2Trait;
    public const DISTANCE_UNITS = ['km','miles'];
    #[Cookbook\REST\Library\distance\__invoke(
        "@param GenAiConnect connect : GenAiConnect instance",
        "@param string city_from : City to start from",
        "@param string city_to   : Destination city",
        "@param string iso2_from : ISO2 code of from country",
        "@param string iso2_to   : ISO2 code of destination country",
        "@param string units     : km | miles",
        "@return string response : Translated phrase",
        "@throws InvalidArgumentException"
    )]    
    public function __invoke(GenAiConnect $connect, iterable $args) : string
    {
        $usage = 'USAGE: '
                . 'city_from : City to start from,'
                . 'city_to : Destination city, '
                . 'iso2_from : ISO2 code of from country, '
                . 'iso2_to   : ISO2 code of destination country, '
                . 'units     : km | miles (default km)';
        $city_from = $args['city_from'] ?? '';
        $city_to  = $args['city_to'] ?? '';
        $iso2_from = $args['iso2_from'] ?? 'Unknown';
        $iso2_to  = $args['iso2_to'] ?? 'Unknown';
        $units = $args['units'] ?? 'km';
        if (empty($city_from)) {
            throw new \InvalidArgumentException('SOURCE CITY MISSING: ' . $usage);
        }
        if (empty($city_to)) {
            throw new \InvalidArgumentException('DESTINATION CITY MISSING: ' . $usage);
        }
        if (!$this->verify_iso2($iso2_from)) {
            throw new \InvalidArgumentException("INVALID SOURCE ISO2 CODE: $iso2_from: " . $usage);
        }
        if (!$this->verify_iso2($iso2_to)) {
            throw new \InvalidArgumentException("INVALID DESTINATION ISO2 CODE: $iso2_to: " . $usage);
        }
        if (!in_array($units, static::DISTANCE_UNITS)) {
            throw new \InvalidArgumentException('UNIT MUST BE ONE OF: ' . implode(',', static::DISTANCE_UNITS) . ' ' . $usage);
        }
        $prompt = "Give me the distance in $units "
                . "from: $city_from, $iso2_from, "
                . "to: $city_to, $iso2_to. "
                . 'Return only the distance with no explanation.';
        return $connect->genAIcall($prompt);
    }
}

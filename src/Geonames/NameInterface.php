<?php
namespace Cookbook\Geonames;
#[NameInterface("Mandates method getCityName()")]
interface NameInterface
{
    #[NameInterface\getCityName(
        "Returns the name of the city",
        "@return string \$name"
    )]
    public function getCityName() : string;
    #[NameInterface\getStateProvCode(
        "Returns the state or province (or 1st regional area) code",
        "@return string \$admin1"
    )]
    public function getStateProvCode() : string;
}

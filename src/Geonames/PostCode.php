<?php
namespace Cookbook\Geonames;
#[PostCode(
    "This class holds Geonames post code data",
    "Source: https://download.geonames.org/export/zip/"
)]
class PostCode extends GeoBase
{
    public string $country     = '';  // iso country code, 2 characters
    public string $postal_code = '';  // varchar(20)
    public string $place_name  = '';  // usually the city name varchar(180)
    public string $admin_name1 = '';  // 1st order subdivision (state) varchar(100)
    public string $admin_code1 = '';  // 1st order subdivision (state) varchar(20)
    public string $admin_name2 = '';  // 2nd order subdivision (county/province) varchar(100)
    public string $admin_code2 = '';  // 2nd order subdivision (county/province) varchar(20)
    public string $admin_name3 = '';  // 3rd order subdivision (community) varchar(100)
    public string $admin_code3 = '';  // 3rd order subdivision (community) varchar(20)
    public float $latitude     = 0.0; // estimated latitude (wgs84)
    public float $longitude    = 0.0; // estimated longitude (wgs84)
    public int $accuracy       = 0;   // accuracy of lat/lng from 1=estimated, 4=geonameid, 6=centroid of addresses or shape    
    #[City\getName(
        "Returns the name of the city",
        "@return string \$place_name"
    )]
    public function getCityName() : string
    {
        return $this->place_name ?? '';
    }
    #[City\getStateProvCode(
        "Returns the state or province (or 1st regional area) code",
        "@return string \$admin_code1"
    )]
    public function getStateProvCode() : string
    {
        return $this->admin_code1 ?? '';
    }
}
// Geonames CSV file data format:
/*
The data format is tab-delimited text in utf8 encoding, with the following fields :

country code      : iso country code, 2 characters
postal code       : varchar(20)
place name        : varchar(180)
admin name1       : 1. order subdivision (state) varchar(100)
admin code1       : 1. order subdivision (state) varchar(20)
admin name2       : 2. order subdivision (county/province) varchar(100)
admin code2       : 2. order subdivision (county/province) varchar(20)
admin name3       : 3. order subdivision (community) varchar(100)
admin code3       : 3. order subdivision (community) varchar(20)
latitude          : estimated latitude (wgs84)
longitude         : estimated longitude (wgs84)
accuracy          : accuracy of lat/lng from 1=estimated, 4=geonameid, 6=centroid of addresses or shape
*/

<?php
namespace Cookbook\Geonames;
#[City(
    "This class holds Geonames city data",
    "Source: https://download.geonames.org/export/dump/"
)]
class City
{
    public int    $geonameid = 0;  // integer id of record in geonames database
    public string $name = '';   // name of geographical point (utf8) varchar(200)
    public string $asciiname = ''; //name of geographical point in plain ascii characters, varchar(200)
    public string $alternatenames = '';  // comma separated, ascii names automatically transliterated, convenience attribute from alternatename table, varchar(10000)
    public float  $latitude = 0.0;  // latitude in decimal degrees (wgs84)
    public float  $longitude = 0.0; //longitude in decimal degrees (wgs84)
    public string $feature = ''; // see http////www.geonames.org/export/codes.html, char(1)
    public string $feature = ''; // see http////www.geonames.org/export/codes.html, varchar(10)
    public string $country = ''; // ISO-3166 2-letter country code, 2 characters
    public string $cc2     = ''; // alternate country codes, comma separated, ISO-3166 2-letter country code, 200 characters
    public string $admin1  = ''; // fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)
    public string $admin2  = ''; // code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80) 
    public string $admin3  = ''; // code for third level administrative division, varchar(20)
    public string $admin4  = ''; // code for fourth level administrative division, varchar(20)
    public float  $population = 0.0; // bigint (8 byte int) 
    public int    $elevation = 0;   //in meters, integer
    public string $dem     = ''; // digital elevation model, srtm3 or gtopo30, average elevation of 3''x3'' (ca 90mx90m) or 30''x30'' (ca 900mx900m) area in meters, integer. srtm processed by cgiar/ciat.
    public string $timezone = '';  // the iana timezone id (see file timeZone.txt) varchar(40)
    public string $modification = ''; // date of last modification in yyyy-MM-dd format
}

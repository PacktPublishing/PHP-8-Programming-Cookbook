<?php
namespace Cookbook\Services;
/*

*** Usage ***********************************************

WEB: http://10.10.10.10/index.php?city=Xyz&state=ZZ&country=CC
CLI: php lookup.php [CITY] [STATE] [COUNTRY]

*** Source: https://download.geonames.org/export/zip/ ***

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

**** License **************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

* Redistributions of source code must retain the above copyright
  notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above
  copyright notice, this list of conditions and the following disclaimer
  in the documentation and/or other materials provided with the
  distribution.
* Neither the name of the  nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/
#[PostCode("Provides postal code lookups")]
class PostCode
{
    public const PATH = __DIR__ . '/../../data/';
    public const HEADERS = ['ISO2','PostCode','City','State','Code','Name2','Code2','Name3','Code3','Latitude','Longitude','Accuracy'];
    #[PostCode\__invoke("string city, string state, string country")]
    public function __invoke(string $city, string $state = '', string $country = 'US')
    {
        $resp['found'] = 0;
        $country = $_REQUEST['country'] ?? 'US';
        $country = strtoupper(basename(trim(strip_tags($country))));
        $fn = __DIR__ . '/' . $country . '.txt';
        if (!file_exists($fn)) {
            $list = glob(static::PATH . '/*.txt');
            $msg  = 'Currently supported countries: ';
            foreach ($list as $fn) {
                $msg .= substr(basename($fn), 0, -3) . ',';
            }
            $msg = substr($msg, 0, -1) . ' '
                 . 'To add support for additional countries, download and unzip '
                 . 'the desired country file from this URL: '
                 . 'https://download.geonames.org/export/zip/';
            $resp['data'] = $msg;
        } else {
            $city  = $_REQUEST['city'] ?? '';
            $state = $_REQUEST['state'] ?? '';
            $city  = trim(strip_tags($city));
            $state = trim(strip_tags($state));
            if (empty($city)) {
                $resp['found'] = 0;
                $resp['data']['Usage'] = $usage;
            } else {
                $data  = new SplFileObject($fn);
                while (!$data->eof()) {
                    $row = $data->fgetcsv("\t");
                    if (empty($row)) continue;
                    $this->findCity($resp, $row, $city, $state);
                }
            }
        }
        return json_encode($resp, JSON_PRETTY_PRINT);
    }
    #[PostCode\findCity("Locates post codes for given city")]
    public  function find_city(array &$resp, array $row,
                               string $city, string $state = '')
    {
        // check to see if city is present in $row
        if (empty($row[2])) return FALSE;
        $ok = FALSE;
        if (empty($state)) {
            $ok = TRUE;
        } else {
            $name = $row[3] ?? '';
            $code = $row[4] ?? '';
            if ($name === $state) $ok = TRUE;
            if ($code === $state) $ok = TRUE;
        }
        if ($ok && stripos($row[2], $city) !== FALSE) {
            if (count($row) === 12) {
                $resp['found']++;
                $resp['data'][$row[1]] = array_combine(HEADERS, $row);
            }
        }
        return $ok;
    }
}

                             

<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Commerce\{SecureCustomer,ShipAddr,PhoneNumber,SharedSecrets};

define('OBJ_CACHE_FN', __DIR__ . '/../../data/obj_cache.txt');
$obj = unserialize(file_get_contents(OBJ_CACHE_FN));
var_dump($obj);

// output:
/*
object(Cookbook\Commerce\SecureCustomer)#2 (5) {
  ["username"]=>
  string(11) "fflintstone"
  ["creditCardNum"]=>
  string(19) "4111-1111-1111-1111"
  ["shippingAddr"]=>
  object(Cookbook\Commerce\ShipAddr)#4 (6) {
    ["addr1"]=>
    string(19) "345 Cave Stone Road"
    ["addr2"]=>
    string(0) ""
    ["city"]=>
    string(7) "Bedrock"
    ["stateProvince"]=>
    string(7) "Unknown"
    ["postCode"]=>
    string(10) "PRE 111111"
    ["iso3"]=>
    string(3) "IND"
  }
  ["phoneNums"]=>
  array(3) {
    [0]=>
    object(Cookbook\Commerce\PhoneNumber)#5 (3) {
      ["countryCode"]=>
      int(1)
      ["areaCode"]=>
      int(111)
      ["number"]=>
      string(8) "222-3333"
    }
    [1]=>
    object(Cookbook\Commerce\PhoneNumber)#6 (3) {
      ["countryCode"]=>
      int(2)
      ["areaCode"]=>
      int(444)
      ["number"]=>
      string(8) "555-6666"
    }
    [2]=>
    object(Cookbook\Commerce\PhoneNumber)#7 (3) {
      ["countryCode"]=>
      int(3)
      ["areaCode"]=>
      int(777)
      ["number"]=>
      string(8) "888-9999"
    }
  }
  ["shared"]=>
  object(Cookbook\Commerce\SharedSecrets)#8 (4) {
    ["iv"]=>
    string(12) "LUp	D��"
    ["key"]=>
    string(16) "123!ABC@#456?XYZ"
    ["cipher"]=>
    string(11) "aes-256-gcm"
    ["tag"]=>
��" string(16) "H���f�4��Gh
  }
}

*/


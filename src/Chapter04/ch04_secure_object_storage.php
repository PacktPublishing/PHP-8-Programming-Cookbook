<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Commerce\{SecureCustomer,ShipAddr,PhoneNumber,SharedSecrets};

define('OBJ_CACHE_FN', __DIR__ . '/../../data/obj_cache.txt');
$key = '123!ABC@#456?XYZ';
$cipher = 'aes-256-gcm';
$shared = new SharedSecrets($key, $cipher);
$addr = new ShipAddr(
    '345 Cave Stone Road',
    '',
    'Bedrock',
    'Unknown',
    'PRE 111111',
    'IND');
$phoneNums = [
    new PhoneNumber('+1 111-222-3333'),
    new PhoneNumber('+2 444-555-6666'),
    new PhoneNumber('+3 777-888-9999')
];
$cust = new SecureCustomer(
    'fflintstone',
    '4111-1111-1111-1111',
    $addr,
    $phoneNums,
    $shared);

// cache the object in a data folder
file_put_contents(OBJ_CACHE_FN, serialize($cust));
// cache the secrets in a secure folder
file_put_contents(SharedSecrets::KEY_CACHE_FN, serialize($shared));

readfile(OBJ_CACHE_FN);
// output: 
/*
O:32:"Cookbook\Commerce\SecureCustomer":5:{
    s:8:"username";s:11:"fflintstone";
    s:13:"creditCardNum";s:28:"Y/0nNJKmpybJO+XAu5VUn09z9g==";
    s:12:"shippingAddr";
        O:26:"Cookbook\Commerce\ShipAddr":6:{
            s:5:"addr1";s:19:"345 Cave Stone Road";
            s:5:"addr2";s:0:"";
            s:4:"city";s:7:"Bedrock";
            s:13:"stateProvince";s:7:"Unknown";
            s:8:"postCode";s:10:"PRE 111111";
            s:4:"iso3";s:3:"IND";
        }
    s:9:"phoneNums";
        a:3:{
            i:0;O:29:"Cookbook\Commerce\PhoneNumber":3:{
                s:11:"countryCode";i:1;
                s:8:"areaCode";i:111;
                s:6:"number";s:8:"222-3333";
            }i:1;O:29:"Cookbook\Commerce\PhoneNumber":3:{
                s:11:"countryCode";i:2;
                s:8:"areaCode";i:444;
                s:6:"number";s:8:"555-6666";
            }i:2;O:29:"Cookbook\Commerce\PhoneNumber":3:{
                s:11:"countryCode";i:3;
                s:8:"areaCode";i:777;
                s:6:"number";s:8:"888-9999";
            }
        }
    s:9:"timeStamp";s:19:"2025-05-04 10:56:23";
}
*/
    

<?php
namespace Cookbook\Commerce;

class ShipAddr
{
    public function __construct(
        public string $addr1,
        public ?string $addr2,
        public string $city,
        public string $stateProvince,
        public string $postCode,
        public string $iso3) {}
}

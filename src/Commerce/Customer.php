<?php
namespace Cookbook\Commerce;

class Customer
{
    public function __construct(
        public string $username,
        public string $creditCardNum,
        public ShipAddr $shippingAddr,
        public array $phoneNums) {}
}

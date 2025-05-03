<?php
namespace Cookbook\Commerce;

class Customer
{
    public function __construct(
        public string $username,
        #[\SensitiveParameter]
        public string $creditCardNum,
        public ShipAddr $shippingAddr,
        public array $phoneNums) {}
}

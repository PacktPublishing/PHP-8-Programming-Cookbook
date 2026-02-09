<?php
namespace Cookbook\Appointment;
class Location
{
    public function __construct(
        public ?int $id = null,
        public ?string $contact_name = null,
        public ?string $location_name = null,
        public ?string $addr1 = null,
        public ?string $addr2 = null,
        public ?string $city = null,
        public ?string $state_province = null,
        public ?string $postal_code = null,
        public ?string $country = null
    ) {}
}

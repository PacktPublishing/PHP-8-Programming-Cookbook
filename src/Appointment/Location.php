<?php
namespace Cookbook\Appointment;
// SQL for "location: table:
/*
CREATE TABLE location (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    contact_name VARCHAR(64),
    location_name VARCHAR(64) NOT NULL,
    addr1 VARCHAR(64) NOT NULL,
    addr2 VARCHAR(64),
    city VARCHAR(64) NOT NULL,
    state_province VARCHAR(64),
    postal_code VARCHAR(16) NOT NULL,
    country CHAR(2) NOT NULL
);
*/
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

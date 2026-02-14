<?php
namespace Cookbook\Appointment;
// "appointment" table class definition
/*
CREATE TABLE appointment (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title CHAR(16),
    location VARCHAR(255),
    contact_info VARCHAR(255),
    start_date_and_time DATETIME,
    end_date_and_time DATETIME
);
*/
class Appointment
{
    public function __construct(
        public ?int $id = null,
        public ?string $title = null,
        public ?string $location = null,
        public ?string $contact_info = null,
        public ?string $start_date_and_time = null,
        public ?string $end_date_and_time = null
    ) {}
}

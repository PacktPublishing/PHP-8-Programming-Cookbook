<?php
namespace Cookbook\Appointment;
class Appointment
{
    public function __construct(
        public ?int $id = null,
        public ?int $location_id = null,
        public ?string $start_date_and_time = null,
        public ?string $end_date_and_time = null
    ) {}
}

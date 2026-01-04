<?php
declare(strict_types=1);
namespace Cookbook\Database;
class PostCode
{
    public function __construct(
        public ?int $id = null,
        public string $countryCode = '',
        public string $postalCode = '',
        public string $placeName = '',
        public string $adminName1 = '',
        public string $adminCode1 = '',
        public string $adminName2 = '',
        public string $adminCode2 = '',
        public string $adminName3 = '',
        public string $adminCode3 = '',
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $accuracy = null,
    ) {}
}

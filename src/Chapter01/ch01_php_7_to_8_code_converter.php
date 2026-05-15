<?php
include __DIR__ . '/../../vendor/autoload.php';
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/test_file'])
    ->withSets([LevelSetList::UP_TO_PHP_84 ])
    ->withSets([SetList::CODE_QUALITY, SetList::DEAD_CODE, SetList::TYPE_DECLARATION]);

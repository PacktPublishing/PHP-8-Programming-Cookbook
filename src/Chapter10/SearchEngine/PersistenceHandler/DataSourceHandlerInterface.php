<?php

namespace Cookbook\Chapter10\SearchEngine\PersistenceHandler;

interface DataSourceHandlerInterface
{
    public function prepare(array $options): void;

    public function executeSearch(array $criteria): array;
}
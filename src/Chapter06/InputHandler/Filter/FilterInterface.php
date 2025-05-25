<?php

namespace Cookbook\Chapter06\InputHandler\Filter;

interface FilterInterface
{
    public function apply(object $entity): void;
}
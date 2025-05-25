<?php

namespace Cookbook\Chapter06\InputHandler\Filter;

class FilterFactory
{
    public static function create(array $stack): FilterInterface
    {
        $filter = new BaseFilter();
        foreach ($stack as $className) {
            if (class_exists($className)) {
                $filter = new $className($filter);
            }
        }
        return $filter;
    }
}
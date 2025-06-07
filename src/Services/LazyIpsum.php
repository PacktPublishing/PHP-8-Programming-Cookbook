<?php
namespace Cookbook\Services;
use ReflectionClass;
#[LazyIpsum("Returns a Lazy Ghost Ipsum instance")]
class LazyIpsum
{
    public static function getLazy(string $fn)
    {
        $reflector = new ReflectionClass(Ipsum::class);
        return $reflector->newLazyGhost(function (Ipsum $obj) use ($fn) {
             $obj->__construct($fn);
        });
    }
}

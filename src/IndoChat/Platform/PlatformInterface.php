<?php
namespace Cookbook\IndoChat\Platform;

interface PlatformInterface
{
    public function get(array $arr) : string;
}

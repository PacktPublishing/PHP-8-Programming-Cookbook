<?php
namespace Cookbook\View\Strategy;

class RenderJson extends RenderBase
{
    public function __invoke() : string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}

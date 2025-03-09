<?php
namespace Cookbook\View\Strategy;

abstract class RenderBase implements InvokableInterface
{
    public ?iterable $data = NULL;
    public function __construct(iterable $data)
    {
        $this->data = $data;
    }
}

<?php
namespace Cookbook\View\Strategy;

interface InvokableInterface
{
    public function __invoke() : string;
}

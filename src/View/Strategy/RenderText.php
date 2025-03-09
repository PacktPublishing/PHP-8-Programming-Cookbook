<?php
namespace Cookbook\View\Strategy;

class RenderText extends RenderBase
{
    public function __invoke() : string
    {
        $output = '';
        foreach ($this->data as $key => $value) {
            $output .= $key . ":\t" . $value . PHP_EOL;
        }
        return $output;
    }
}

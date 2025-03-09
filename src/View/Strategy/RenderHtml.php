<?php
namespace Cookbook\View\Strategy;

class RenderHtml extends RenderBase
{
    public function __invoke() : string
    {
        $output = '<table>';
        foreach ($this->data as $key => $value) {
            $output .= sprintf('<tr><th>%s</th><td>%s</td></tr>',
                               (string) $key, (string) $value);
        }
        $output .= '</table>';
        return $output;
    }
}

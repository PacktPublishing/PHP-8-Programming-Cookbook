<?php

//********* Test using is_resource() *********//
function test_conventional(mixed $resource, string $label)
{
    $output = '';
    if (is_resource($resource)) {
        $output .=  $label . ' was successfully opened.' . PHP_EOL;
        $output .= '$resource is this type of resource: ' 
                 . get_resource_type($resource);
    } else {
        $output .= 'Unable to open ' . $label;
    }
    return $output . PHP_EOL;
}


//********* Test using !empty() *********//
function test_updated(mixed $resource, string $label)
{
    $output = '';
    if (!empty($resource)) {
        $output .=  $label . ' was successfully opened.' . PHP_EOL;
        $output .= '$resource is this type of resource: ' 
                 . gettype($resource);
    } else {
        $output .= 'Unable to open ' . $label;
    }
    return $output . PHP_EOL;
}

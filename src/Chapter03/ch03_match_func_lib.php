<?php
namespace Library;
// define supported mime types
define('ACCEPT_X', 'application/xml');
define('ACCEPT_J', 'application/json');
define('ACCEPT_H', 'text/html');
define('ACCEPT_T', 'text/plain');
// defines functions used in ch03_match_runner.php
// XML
$x = function ($data) {
    header('Content-Type: ' . ACCEPT_X);
    $xml = xmlwriter_open_memory();
    $xml->startDocument();
    $xml->startElement('Item');
    foreach ($data as $key => $value) {
        $xml->startElement('Key');
        $xml->text((string) $key);
        $xml->endElement();
        $xml->startElement('Value');
        $xml->text((string) $value);
        $xml->endElement();
    }
    $xml->endElement(); // Item
    return $xml->outputMemory();
};
// JSON
$j = function ($data) {
    header('Content-Type: ' . ACCEPT_J);
    return json_encode($data, JSON_PRETTY_PRINT); 
};
// HTML
$h = function ($data) {
    header('Content-Type: ' . ACCEPT_H);
    $output = '<table border=1>' . PHP_EOL;
    foreach ($data as $key => $value) {
        $output .= sprintf("  <tr><th>%s</th><td>%s</td></tr>\n",
                           (string) $key, (string) $value);
    }
    $output .= '</table>' . PHP_EOL;
    return $output;
};
// Text
$t = function ($data) {
    header('Content-Type: ' . ACCEPT_T);
    $output = '';
    foreach ($data as $key => $value) {
        $output .= $key . ":\t" . $value . PHP_EOL;
    }
    return $output;
};

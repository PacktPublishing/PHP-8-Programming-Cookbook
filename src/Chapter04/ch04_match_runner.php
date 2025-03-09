<?php
// decision tree to render using match() {}
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\View\Strategy\{Accept,RenderXml,RenderJson,RenderHtml,RenderText};
// determine format
$accept = trim(strip_tags($_SERVER['HTTP_ACCEPT'] ?? 'text/html'));
// sanitize data
$_POST = (!empty($_POST)) ? $_POST : ['A' => 111, 'B' => 222, 'C' => 333];
$data = array_map(function ($val) { return trim(strip_tags($val)); }, $_POST);
// return data in acceptable format
echo match (TRUE) {
    str_contains($accept, Accept::X->value) => (new RenderXml($data))(),
    str_contains($accept, Accept::J->value) => (new RenderJson($data))(),
    str_contains($accept, Accept::H->value) => (new RenderHtml($data))(),
    default => (new RenderText($data))()
};

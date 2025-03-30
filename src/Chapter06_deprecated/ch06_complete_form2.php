<?php

use Cookbook\Chapter06\Factory\FormFactory;

include __DIR__ . '/../../vendor/autoload.php';

$formFactory = new FormFactory();

$config = [
    'attributes' => ['action' => 'FormHandler/submit.php', 'method' => 'POST'],
    'elements' => [
        [
            'type' => 'text',
            'attributes' => ['name' => 'name_first', 'placeholder' => 'First Name'],
        ],
        [
            'type' => 'email',
            'attributes' => ['name' => 'email', 'placeholder' => 'Email'],
        ],
        [
            'type' => 'radio',
            'attributes' => ['name' => 'like-coffee'],
            'options' => ['no' => 'No', 'yes' => 'Yes'],
        ],
        [
            'type' => 'select',
            'attributes' => ['name' => 'checkin'],
            'options' => ['morning' => 'Morning', 'evening' => 'Evening'],
        ],
    ],
];

// Build the form HTML
$formHtml = $formFactory->build($config);

// Load the form into DOMDocument
$document = new DOMDocument();
libxml_use_internal_errors(true);
$document->loadHTML($formHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

// Add a CSS link to the head
$head = $document->getElementsByTagName('head')->item(0);
if (!$head) {
    $head = $document->createElement('head');
    $document->documentElement->insertBefore($head, $document->documentElement->firstChild);
}

$css = "body { font-family: 'Courier New', Courier, monospace; width: 200px; padding:30px; }; }
form { padding: 30px;} 
input[type=radio] { width: auto; margin: 0 10px; }
input, select, button { margin: 10px 0; padding: 8px; width: 100%; box-sizing: border-box; }";

$style = $document->createElement('style', $css);
$head->appendChild($style);

// Output the modified HTML
echo $document->saveHTML();

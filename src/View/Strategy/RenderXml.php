<?php
namespace Cookbook\View\Strategy;
use function xmlwriter_open_memory;
class RenderXml extends RenderBase
{
    public function __invoke() : string
    {
        $xml = xmlwriter_open_memory();
        $xml->startDocument();
        $xml->startElement('Item');
        foreach ($this->data as $key => $value) {
            $xml->startElement('Key');
            $xml->text((string) $key);
            $xml->endElement();
            $xml->startElement('Value');
            $xml->text((string) $value);
            $xml->endElement();
        }
        $xml->endElement(); // Item
        return $xml->outputMemory();
    }
}

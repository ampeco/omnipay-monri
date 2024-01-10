<?php

namespace Ampeco\OmnipayMonri;

trait ConvertsArrayToXml
{
    private function arrayToXml(array $data): string
    {
        $rootElement = key($data);
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootElement}/>");
        $this->arrayToXmlRecursive($data[$rootElement], $xml);

        return $xml->asXML();
    }

    private function arrayToXmlRecursive(array $data, \SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXmlRecursive($value, $subnode);
            } else {
                $xml->addChild($key, $value);
            }
        }
    }
}

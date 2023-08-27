<?php

namespace Classes;

use Interfaces\FormatDataInterface;

/**
 * Класс для работы записи в формат XML
 * class XMLWriter
 */
class XMLFormatter implements FormatDataInterface
{
    public static function convertDataToFormat($data): string
    {
        $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><companies></companies>');
        self::array_to_xml($data,$xml_data);

        return $xml_data->asXML();
    }

    /**
     * @param $data
     * @param $xml_data
     * @return void
     */
    public static function array_to_xml( $data, &$xml_data): void
    {
        foreach ($data as $key => $values) {
            if ($key === 'value') {
                continue;
            }

            if (!is_numeric($key)) {
                $sub_node = $xml_data->addChild($key, $values['value'] ?? null);

                if (!empty($values['attributes'])) {
                    foreach ($values['attributes'] as $key_attribute => $attribute) {
                        $sub_node->addAttribute($key_attribute, $attribute);
                    }
                } else {
                    self::array_to_xml($values, $sub_node);
                }
            } else {
                self::array_to_xml($values, $xml_data);
            }
        }
    }
}

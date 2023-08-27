<?php

namespace Classes;

use Interfaces\FormatDataInterface;
use Interfaces\ParserInterface;
use Interfaces\WriteDataInterface;

/**
 * Парсер для файла для тестового задания
 * class DomRFParser
 */
class DomRFParser extends BaseParser implements ParserInterface, WriteDataInterface
{

    public static function parseString(string $string, string $separator): array
    {
        $array = explode($separator, $string);
        array_walk($array, 'trim');

        return array_combine([$array[0]], [$array[1]]);
    }

    public function saveToFile(
        $data,
        FormatDataInterface $converter,
        string $file_path
    ): void {
        $convert_data = $converter::convertDataToFormat($data);

        $file = fopen($file_path,'w+');
        fwrite($file, $convert_data);
        fclose($file);
    }

    /**
     * @inheritDoc
     */
    public function parseData(): void
    {
        $getAsocArrayFromArrayString = function ($values): array {
            $array = [];
            foreach ($values as $value) {
                $array = array_merge($array, self::parseString($value, ':'));
            }
            return $array;
        };

        foreach ($this->data as $values) {
            $this->result_data[] = $getAsocArrayFromArrayString($values);
        }
    }

    public function saveData(string $format): void
    {
        switch ($format) {
            case self::JSON_FORMAT:
                $this->saveToFile($this->getJsonDataByStructure(), (new JsonFormatter()), ROOT_DIR . 'offices.json');
                break;
            case self::XML_FORMAT:
                $this->saveToFile($this->getXMLDataByStructure(), (new XMLFormatter()), ROOT_DIR . 'offices.xml');
                break;
            default:
                echo 'Укажите один из вариантов: ' . self::JSON_FORMAT . ',' . self::XML_FORMAT;
        }
    }

    /**
     * Разбирает строку на данные
     * @param string $address
     * @return array
     */
    private function parseAddress(string $address): array
    {
        if (preg_match('/г.(.*?)улица/', $address, $outCity) === 1) {
            $city = trim($outCity[1]);
        } else {
            $city = '';
        }

        if (preg_match('/улица(.*?)дом/', $address, $outStreet) === 1) {
            $street_name = $outStreet[1];
            $street = str_replace('проспект', 'пр-т ', $street_name);
        } else {
            $street = '';
        }

        if (preg_match('/дом(.*?)офис|(дом(.*?)$)/', $address, $outHouse) === 1) {
            $house = trim($outHouse[3] ?? $outHouse[1]);
        } else {
            $house = '';
        }

        if (preg_match('/офис(.*?)$/', $address, $outOffice) === 1) {
            $officeOrApartment = trim($outOffice[1]);
        } else {
            $officeOrApartment = '';
        }

        return compact('city', 'street', 'house', 'officeOrApartment');
    }

    /**
     * Структурирование для JSON
     * @return \stdClass
     */
    private function getJsonDataByStructure(): \stdClass
    {
        $data = new \stdClass();
        $data->data = [];

        foreach ($this->result_data as $array) {
            $address_data = $this->parseAddress($array['address']);

            $data->data[] = [
                'type' => 'office',
                'id' => (int)$array['id'],
                'attributes' => [
                    'name' => $array['name'],
                    'address' => $address_data,
                ],
                'phone' => [
                    'countryNumber' => preg_replace('/[^0-9]/', '', $array['phone']),
                    'official' => $array['phone'],
                ],
            ];
        }

        return $data;
    }

    /**
     * Структурирование для XML
     * @return array
     */
    private function getXMLDataByStructure(): array
    {
        $data = [];

        foreach ($this->result_data as $array) {
            $address_data = $this->parseAddress($array['address']);

            $data_company = [
                'company' => [
                    'company-id' => [
                        'value' => (int)$array['id'],
                    ],
                    'name' => [
                        'value' => $array['name'],
                        'attributes' => [
                            'lang' => 'ru',
                        ],
                    ],
                    'phone' => [
                        'countryNumber' => [
                            'value' => preg_replace('/[^0-9]/', '', $array['phone']),
                        ],
                        'official' => [
                            'value' => $array['phone'],
                        ]
                    ],
                    'address' => [
                        'value' => $array['address'],
                        'attributes' => [
                            'lang' => 'ru',
                        ],
                    ],
                ]
            ];

            if (!empty($address_data['officeOrApartment']))
            {
                $office_value = 'офис ' . $address_data['officeOrApartment'];
                $data_company['company']['address']['value'] = str_replace($office_value, '', $data_company['company']['address']['value']);
                $data_company['company']['address-add'] = [
                    'value' => $office_value,
                ];
            }

            $data[] = $data_company;
        }

        return $data;
    }
}

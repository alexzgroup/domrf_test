<?php

namespace Classes;

use Interfaces\FormatDataInterface;

/**
 * Класс для работы записи в формат JSON
 * class JsonWriter
 */
class JsonFormatter implements FormatDataInterface
{

    public static function convertDataToFormat($data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

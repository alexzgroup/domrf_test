<?php

namespace Interfaces;

/**
 * Методы для работы с записью результатов
 * interface FormatDataInterface
 */
interface FormatDataInterface
{
    /**
     * Пишет данные в файл
     * @param $data
     * @return string
     */
    public static function convertDataToFormat($data): string;
}

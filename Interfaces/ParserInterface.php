<?php

namespace Interfaces;

/**
 * Интерфейс реализующий методы парсинга файла
 * interface ParserInterface
 */
interface ParserInterface
{
    /**
     * Парсит строку и возвращает массив
     * @param string $string
     * @param string $separator
     * @return array
     */
    public static function parseString(string $string, string $separator): array;

    /**
     * Обработка массива данных
     * @return void
     */
    public function parseData(): void;
}

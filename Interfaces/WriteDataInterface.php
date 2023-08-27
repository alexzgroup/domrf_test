<?php

namespace Interfaces;

interface WriteDataInterface
{
    /**
     * Записывает данные в файл
     * @param $data
     * @param FormatDataInterface $converter
     * @param string $file_path
     * @return void
     */
    public function saveToFile($data, FormatDataInterface $converter, string $file_path): void;

    /**
     * Сохраняет результат
     * @param string $format
     * @return void
     */
    public function saveData(string $format): void;
}
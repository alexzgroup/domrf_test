<?php

namespace Interfaces;

/**
 * Методы для работы с чтением данных
 * interface ReadDataInterface
 */
interface ReadDataInterface
{
    /**
     * Загружает данные из источника
     * @return void
     */
    public function readFile(): void;

    /**
     * Сохраняет секцию в память
     * @param array $section_strings
     * @return void
     */
    public function saveSection(array $section_strings): void;
}

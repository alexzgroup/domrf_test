<?php

namespace Classes;

use Interfaces\ReadDataInterface;
use Traits\InitLoadResourceTrait;

/**
 * Базовый класс парсера
 * class BaseParser
 */
class BaseParser implements ReadDataInterface
{
    use InitLoadResourceTrait;

    /**
     * XML формат
     */
    const XML_FORMAT = 'XML';

    /**
     * Json формат
     */
    const JSON_FORMAT = 'JSON';

    /**
     * Наши данные для записи
     * @var array
     */
    protected array $data;

    /**
     * Результирующий массив
     * @var array
     */
    protected array $result_data;

    /**
     * Путь к файлу
     * @var string
     */
    protected string $path_to_file;

    public function readFile(): void
    {
        $fp = @fopen($this->path_to_file, "r");
        if ($fp) {
            /**
             * @var $section_strings string|array
             */
            $section_strings = [];

            while (($buffer = fgets($fp)) !== false) {
                $string = trim($buffer);
                if (empty($string) || $string === PHP_EOL) {
                    $this->saveSection($section_strings);
                    $section_strings = [];
                } else {
                    $section_strings[] = $string;
                }
            }

            $this->saveSection($section_strings);

            if (!feof($fp)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($fp);
        }
    }

    public function saveSection(array $section_strings): void
    {
        $this->data[] = $section_strings;
    }
}

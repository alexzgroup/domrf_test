<?php

namespace Traits;

/**
 * Стартовая функция для парсера
 * trait InitLoadResourceTrait
 */
trait InitLoadResourceTrait
{
    /**
     * Инициализация ресурса
     * @return bool
     * @throws \Exception
     */
    public function init(): bool
    {
        global $argv;
        $is_cli = php_sapi_name() === 'cli';

        $file_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . ($is_cli ? $argv[1] : $_GET['file_path']);

        if (!empty($file_path) && file_exists($file_path)) {
            $this->path_to_file = $file_path;
            return true;
        } else {
            $error = $is_cli ? 'Path file is not valid' : 'GET param `file_path` is not valid';
            throw new \Exception($error);
        }
    }
}

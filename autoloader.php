<?php

error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/php-errors.log');

/**
 * Корневая директория
 */
const ROOT_DIR = __DIR__ . DIRECTORY_SEPARATOR;

/**
 * Подгружаем классы
 */
spl_autoload_register(function ($className) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR . $className . '.php');
    if (is_file($file)) {
        require_once $file;
    }
});

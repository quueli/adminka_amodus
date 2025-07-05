<?php

// Полное отключение отображения ошибок
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);

// Начинаем буферизацию вывода
ob_start();

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Стандартный Symfony bootstrap
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

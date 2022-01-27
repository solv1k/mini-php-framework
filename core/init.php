<?php

// Основные вспомогательные функции и константы
require_once('functions.php');

// Автозагрузка классов в рантайме
spl_autoload_register(function ($class) {
    $path = dir_path(strtolower(str_replace('\\', '/', $class))) . '.php';
    include $path;
});
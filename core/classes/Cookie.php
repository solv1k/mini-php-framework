<?php

namespace Core\Classes;

use Exception;

class Cookie
{
    /**
     * Инстанс класса.
     */
    private static $instance = null;

    /**
     * Делаем синглтон.
     */
    protected function __construct() { }
    protected function __clone() { }
    public function __wakeup() {
        throw new Exception("Cookie is singleton.");
    }

    /**
     * Получить инстанс.
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Получает куку по заданному ключу или возвращает дефолтное значение.
     */
    public function get(string $key, string $default = null)
    {
        return isset($_COOKIE[$key]) ? base64_decode($_COOKIE[$key]) : $default;
    }

    /**
     * Получает и декодирует массив из куки по заданному ключу или возвращает дефолтное значение.
     */
    public function getArray(string $key, array $default = [])
    {
        return $this->get($key) ? json_decode($this->get($key), true) : $default;
    }

    /**
     * Сохраняет куку по ключу (кодирует значение куки в base64).
     */
    public function set(string $key, string $value)
    {
        setcookie($key, base64_encode($value), 0, '/', '', false, true);
    }

    /**
     * Сохраняет массив в куку (кодирует значение куки в base64).
     */
    public function setArray(string $key, array $values)
    {
        $this->set($key, json_encode($values));
    }

    /**
     * Удаляет куку по ключу.
     */
    public function unset(string $key)
    {
        setcookie($key, null, 0, '/', '', false, true);
    }
}
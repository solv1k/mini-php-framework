<?php

namespace Core\Classes;

class Hash
{
    /**
     * Возвращает SHA256 хеш от передаваемого значения и ключа приложения.
     */
    public static function make(string $value)
    {
        return hash('sha256', $value . config('app_key'));
    }
}
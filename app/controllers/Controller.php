<?php

namespace App\Controllers;

class Controller
{
    /**
     * Конструктор.
     */
    public function __construct()
    {
        if ( ! $this->authenticated()) {
            return redirect('/')->withError(translate('auth.need'));
        }
    }
    
    /**
     * Возвращает флаг авторизации пользователя.
     */
    protected function authenticated()
    {
        return true;
    }
}
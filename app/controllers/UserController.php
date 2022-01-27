<?php

namespace App\Controllers;

class UserController extends Controller
{
    /**
     * Проверяем авторизован ли пользователь.
     */
    protected function authenticated()
    {
        return user() ?? false;
    }

    /**
     * Логика выхода из аккаунта.
     */
    public function logout()
    {
        session()->destroy();

        return redirect(url('/'))->withSuccess(translate('auth.logout'));
    }
}
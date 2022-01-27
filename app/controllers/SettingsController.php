<?php

namespace App\Controllers;

use App\Models\User;
use Core\Classes\Request;

class SettingsController extends Controller
{
    /**
     * Проверяем авторизован ли пользователь.
     */
    protected function authenticated()
    {
        return user() ?? false;
    }

    /**
     * Форма настроек.
     */
    public function index()
    {
        return view('user.settings');
    }

    /**
     * Логика сохранения настроек.
     */
    public function update(Request $request)
    {
        $validated = $request->validated([
            'login' => 'required|uniquelogin',
            'password' => 'password'
        ]);

        User::update(user()->id, $validated);

        if ($user = User::find(user()->id)) {
            session()->setObject('user', $user);
        }

        return redirect('/user/settings')->withSuccess(translate('settings.updated'));
    }
}
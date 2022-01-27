<?php

namespace App\Controllers;

use App\Models\User;
use Core\Classes\Hash;
use Core\Classes\Request;

class AuthController extends Controller
{
    /**
     * Главная страница с формой выбора аутентификации.
     */
    public function index()
    {
        $user = session()->getObject('user');

        return view('home', compact('user'));
    }

    /**
     * Форма авторизации.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Логика авторизации.
     */
    public function auth(Request $request)
    {
        $validated = $request->validated([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::first(['id', 'login', 'email'], $validated);

        if (!empty($user)) {
            session()->setObject('user', $user);

            return redirect(url('/'))->withSuccess(translate('auth.success'));
        }

        return redirect(url('/login'))->withError(translate('auth.error'));
    }

    /**
     * Форма регистрации.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Логика регистрации.
     */
    public function store(Request $request)
    {
        $validated = $request->validated([
            'email' => 'required|email|uniqueemail',
            'login' => 'required|login|uniquelogin',
            'password' => 'required|password',
        ]);

        User::create($validated);

        return redirect(url('/login'))->withSuccess(translate('auth.register'));
    }
}
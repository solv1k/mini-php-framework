<?php

namespace App\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Главная страница.
     */
    public function index()
    {
        // Логины пользователей сделавших боле 2-х заказов
        $usersWithManyOrders = array_map(fn($user) => $user->login, User::withManyOrders());
        
        // Логины пользователей не сделавших ни одного заказа
        $usersWithoutOrders = array_map(fn($user) => $user->login, User::withoutOrders());

        return view('home', compact('usersWithManyOrders', 'usersWithoutOrders'));
    }
}
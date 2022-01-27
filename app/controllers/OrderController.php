<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\User;
use Core\Classes\Request;

class OrderController extends Controller
{
    /**
     * Проверяем авторизован ли пользователь.
     */
    protected function authenticated()
    {
        return user() ?? false;
    }

    /**
     * Список заказов пользователя.
     */
    public function index(Request $request)
    {
        $user = new User();
        $user->fill( (array)user() );

        $orders = Order::getAllForUser($user);

        return view('user.orders', compact('orders'));
    }
    
    /**
     * Форма создания заказа.
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Логика сохранения заказа.
     */
    public function store(Request $request)
    {
        $validated = $request->validated([
            'price' => 'required|integer|unsigned'
        ]);

        Order::create([
            'user_id' => user()->id,
            'price' => $validated['price']
        ]);

        return redirect('/user/orders')->withSuccess(translate('order.created'));
    }
}
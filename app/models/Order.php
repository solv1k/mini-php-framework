<?php

namespace App\Models;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'price'
    ];

    /**
     * Возвращает список всех заказов для указанного пользователя.
     */
    public static function getAllForUser(User $user)
    {
        $fields = ['*'];

        $where = ['user_id' => $user->id];

        $orders = self::all($fields, $where);

        return $orders;
    }
}
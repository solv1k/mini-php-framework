<?php

namespace App\Models;

use Core\Classes\DB;
use Core\Classes\Hash;

class User extends Model
{
    /**
     * Набор полей для сохранения в БД.
     */
    protected $fillable = [
        'email',
        'login',
        'password'
    ];

    /**
     * Метод выполняется перед сохранением модели в БД.
     */
    protected function beforeCreate()
    {
        $this->password = Hash::make($this->password);
    }

    /**
     * Метод выполняется перед обновлением модели в БД.
     */
    protected function beforeUpdate()
    {
        if (property_exists($this, 'password')) {
            $this->password = Hash::make($this->password);
        }
    }

    /**
     * Возвращает массив пользователей сделавших более 2-х заказов.
     */
    public static function withManyOrders()
    {
        $users = DB::getInstance()->fetchQuery(
            "SELECT users.id, users.login FROM users 
            LEFT JOIN orders 
            ON users.id = orders.user_id 
            GROUP BY users.id 
            HAVING COUNT(orders.id) > 2");

        return $users;
    }

    /**
     * Возвращает массив пользователей не сделавших ни одного заказа.
     */    
    public static function withoutOrders()
    {
        $users = DB::getInstance()->fetchQuery(
            "SELECT users.id, users.login FROM users 
            LEFT JOIN orders 
            ON users.id = orders.user_id 
            GROUP BY users.id 
            HAVING COUNT(orders.id) = 0");

        return $users;
    }
}
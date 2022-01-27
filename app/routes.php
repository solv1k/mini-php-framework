<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\SettingsController;
use App\Controllers\OrderController;
use App\Controllers\ErrorController;

/**
 * Маршруты.
 */
return [
    '/'                     => [HomeController::class, 'index'],

    '/login'                => [AuthController::class, 'login'],
    '/login/do'             => [AuthController::class, 'auth'],
    '/register'             => [AuthController::class, 'register'],
    '/register/do'          => [AuthController::class, 'store'],

    '/users'                => [UserController::class, 'index'],
    '/logout'               => [UserController::class, 'logout'],

    '/user/settings'        => [SettingsController::class, 'index'],
    '/user/settings/update' => [SettingsController::class, 'update'],

    '/user/orders'          => [OrderController::class, 'index'],
    '/user/orders/create'   => [OrderController::class, 'create'],
    '/user/orders/store'    => [OrderController::class, 'store'],

    '/403'                  => [ErrorController::class, 'error403'],
    '/404'                  => [ErrorController::class, 'error404'],
];
<?php

namespace Core\Classes;

use Core\Classes\Base\Singleton;
use Exception;

class Application extends Singleton
{
    /**
     * Конфиг.
     */
    private $config = [];

    /**
     * Маршруты.
     */
    private $routes = [];

    /**
     * Тексты локализации.
     */
    private $translates = [];

    /**
     * Ассеты.
     */
    private $assets = [];

    /**
     * Объект запроса.
     */
    private $request = null;

    /**
     * Инициализация приложения.
     */
    public static function init()
    {
        $app = self::getInstance();
        
        $app->loadConfig();

        $app->loadRoutes();

        $app->loadTranslates();

        $app->loadAssets();

        return $app;
    }

    /**
     * Загружаем конфиг.
     */
    public function loadConfig()
    {
        $config = require_once(app_path('config.php'));

        if ( ! is_array($config) ) {
            throw new Exception("Config must be array type.");
        }

        $this->config = $config;
    }

    /**
     * Возвращает конкретное значение из конфига
     */
    public function config(string $key)
    {
        return $this->config[$key] ?? null;
    }

    /**
     * Загружаем маршруты.
     */
    public function loadRoutes()
    {
        $routesPath = app_path('routes.php');

        $routes = require_once($routesPath);

        if ( ! is_array($routes) ) {
            throw new Exception("Routes must be array type.");
        }

        foreach ($routes as $uri => $controller) {
            $controller[0] ?? throw new Exception("Controller class can not be empty for route [{$uri}]");
            $controller[1] ?? throw new Exception("Controller method can not be empty for route [{$uri}]");
        }

        $this->routes = $routes;
    }

    /**
     * Возвращает массив с маршрутами приложения.
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * Загружаем тексты локализации.
     */
    public function loadTranslates()
    {
        $translates = require_once(app_path('translates.php'));

        if ( ! is_array($translates) ) {
            throw new Exception("Translates must be array type.");
        }

        $this->translates = $translates;
    }

    /**
     * Возвращает массив с текстами локализации.
     */
    public function translates()
    {
        return $this->translates;
    }

    /**
     * Возвращает текст локализации по ключу.
     */
    public function translate(string $key)
    {
        return $this->translates[$key] ?? null;
    }

    /**
     * Загружаем конфиг.
     */
    public function loadAssets()
    {
        $assets = require_once(app_path('assets.php'));

        if ( ! is_array($assets) ) {
            throw new Exception("Assets must be array type.");
        }

        $this->assets = $assets;
    }

    /**
     * Возвращает массив с ассетами приложения.
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * Возвращает объект текущего запроса.
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Связываем приложение с запросом и обрабатываем его.
     */
    public function handle()
    {
        $this->request = new Request(self::getInstance());

        $this->request->handle();
    }

}
<?php

namespace Core\Classes;

use Exception;

class Request
{
    /**
     * Экземпляр приложения.
     */
    private $app;

    /**
     * Глобавльные переменные.
     */
    private array $globalVars = [];

    /**
     * Серверные переменные.
     */
    private array $serverVars = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->sessionStart();
        $this->parseGlobalVars();
        $this->parseServerVars();
    }

    public function sessionStart()
    {
        session_start();
    }

    /**
     * Парсим глобальные переменные.
     */
    private function parseGlobalVars()
    {
        $this->globalVars['POST'] = $_POST;
        $this->globalVars['GET'] = $_GET;
        $this->globalVars['REQUEST'] = $_REQUEST;
        $this->globalVars['SESSION'] = $_SESSION;
    }

    /**
     * Возвращет глобальные переменные запроса.
     */
    public function globalVars()
    {
        return $this->globalVars;
    }

    /**
     * Парсим серверные переменные.
     */
    private function parseServerVars()
    {
        $this->serverVars['URI'] = $_SERVER['REQUEST_URI'];
        $this->serverVars['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'] ?? null;
    }

    /**
     * Возвращет серверные переменные запроса.
     */
    public function serverVars()
    {
        return $this->serverVars;
    }

    /**
     * Возвращает глобальную переменную GET | POST по имени.
     */
    public function var(string $varName)
    {
        return $this->globalVars['GET'][$varName] ?? $this->globalVars['POST'][$varName] ?? null;
    }

    /**
     * Возвращает массив глобальных переменных GET | POST по имени в массиве.
     */
    public function vars(array $varNames = [])
    {
        if ( ! count($varNames)) {
            return $_POST + $_GET;
        }

        $vars = [];

        foreach ($varNames as $varName) {
            if ( ! is_string($varName)) {
                throw new Exception("Var name index can be only string.");
            }

            $vars[$varName] = $this->globalVars['GET'][$varName] ?? $this->globalVars['POST'][$varName] ?? null;
        }

        return $vars;
    }

    /**
     * Возвращает массив со всеми GET | POST параметрами запроса.
     */
    public function all()
    {
        return $this->vars();
    }

    /**
     * Возвращает чистый URI.
     */
    public function uri()
    {
        $uri = $this->serverVars['URI'];

        if (strpos($uri, '?')) {
            $uri = explode('?', $uri)[0];
        }

        if (strpos($uri, '#')) {
            $uri = explode('#', $uri)[0];
        }

        return $uri;
    }

    /**
     * Возвращает предыдущий Url.
     */
    public function referer()
    {
        return $this->serverVars['HTTP_REFERER'];
    }

    /**
     * Обрабатывает запрос.
     */
    public function handle()
    {
        $routes = $this->app->routes();

        if ( ! isset($routes[$this->uri()]) ) {
            return response(View::make('errors.404')->html(), 404);
        }

        list($controllerClass, $controllerMethod) = $routes[$this->uri()];

        call_user_func([new $controllerClass, $controllerMethod], $this);
    }

    /**
     * Валидация запроса.
     */
    public function validate(array $rules = [])
    {
        $validator = Validator::make($this->vars(), $rules);

        return $validator->validate();
    }

    /**
     * Валидация запроса и возврат обработанных данных.
     */
    public function validated(array $rules = [])
    {
        $validator = Validator::make($this->vars(), $rules);

        if ($validator->validate()) {
            return array_filter($this->vars(array_keys($rules)));
        } else {
            return back()->withInput($this->vars())->withErrors($validator->errors());
        }
    }
}
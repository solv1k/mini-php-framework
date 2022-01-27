<?php

use Core\Classes\Application;
use Core\Classes\Cookie;
use Core\Classes\Redirect;
use Core\Classes\Response;
use Core\Classes\Session;
use Core\Classes\Template;
use Core\Classes\View;

define('DIR_PATH', __DIR__ . '/../');
define('APP_PATH', __DIR__ . '/../app/');
define('VIEWS_PATH', __DIR__ . '/../app/views/');

/**
 * Инициализатор приложения.
 */
function app_init()
{
    return Application::init();
}

/**
 * Приложение.
 */
function app()
{
    return Application::getInstance();
}

/**
 * Запрос.
 */
function request()
{
    return app()->request();
}

/**
 * Глобальный фасадный метод для возврата HTTP-ответа.
 */
function response(string $body, int $code = 200)
{
    return new Response($body, $code);
}

/**
 * Создает и возвращает url.
 */
function url(string $uri, array $params = [])
{
    $url = config('app_url') . str_replace(config('app_url'), '', $uri);

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}

/**
 * Возвращает значение куки по имени, если не найдено, то возвращается значение по умолчанию.
 */
function cookie()
{
    return Cookie::getInstance();
}

/**
 * Глобальный фасадный метод для работы с сессией.
 */
function session()
{
    return new Session;
}

/**
 * Возвращает объект текущего пользователя из сессии.
 */
function user()
{
    return session()->getObject('user');
}

/**
 * Редирект на предыдущую страницу.
 */
function back()
{
    return new Redirect;
}

/**
 * Редирект с критической ошибкой.
 */
function abort(int $code)
{
    return (new Redirect)->withAbort($code);
}

/**
 * Редирект на указанный Url.
 */
function redirect(string $url)
{
    return new Redirect($url);
}

/**
 * Глобальный фасадный метод для рендеринга вьюшки.
 */
function view(string $path, array $data = [])
{
    return View::render($path, $data);
}

/**
 * Возвращает класс шаблона.
 */
function template()
{
    return Template::getInstance();
}

/**
 * Возвращает значение из конфига приложения по ключу.
 */
function config(string $key)
{
    return app()->config($key);
}

/**
 * Возвращает текст локализации по ключу.
 */
function translate(string $key)
{
    return app()->translate($key);
}

/**
 * Строит путь к файлу относительно корневой папки приложения.
 */
function dir_path(string $path)
{
    return DIR_PATH . $path;
}

/**
 * Строит путь к файлу относительно папки домена приложения.
 */
function app_path(string $path)
{
    return APP_PATH . $path;
}

/**
 * Строит путь к файлу относительно папки с вьюшками.
 */
function view_path(string $path)
{
    return VIEWS_PATH . $path;
}

/**
 * Строит путь к файлу относительно папки с ассетами.
 */
function assets_path(string $path)
{
    return url('/assets/' . $path);
}
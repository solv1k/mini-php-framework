<?php

namespace Core\Classes;

use Exception;

class View
{
    /**
     * Короткий путь к файлу вьюшки (без расширения).
     */
    private $path;

    /**
     * Массив данных вьюшки.
     */
    private $data = [];

    public function __construct(string $path, array $data = [])
    {
        $this->path = $path;
        $this->data = $data;
    }

    /**
     * Создаёт новый инстанс.
     */
    public static function make(string $path, array $data = [])
    {
        $instance = new self($path, $data);

        return $instance;
    }

    /**
     * Создаёт новый инстанс и рендерит вьюшку.
     */
    public static function render(string $path, array $data = [])
    {
        $instance = new self($path, $data);

        $instance->printHtml();
    }

    /**
     * Выводит на экран отрендеренный HTML вьюшки.
     */
    public function printHtml()
    {
        print $this->html();
    }

    /**
     * Возвращает отрендеренный HTML.
     */
    public function html()
    {
        $viewFile = $this->viewFilePath();

        $this->throwIfFileNotExists($viewFile);

        $this->addCookieValues();

        ob_start();
            extract($this->data);
            include $viewFile;
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Добавляет в отображение дополнительные данные из куков.
     */
    function addCookieValues()
    {
        if (cookie()->get('request_errors')) {
            $this->data['errors'] = cookie()->getArray('request_errors');
            cookie()->unset('request_errors');
        }

        if (cookie()->get('request_success')) {
            $this->data['success'] = cookie()->get('request_success');
            cookie()->unset('request_success');
        }

        if (cookie()->get('request_old_values')) {
            $this->data['old'] = cookie()->getArray('request_old_values');
            cookie()->unset('request_old_values');
        }
    }

    /**
     * Возвращает полный путь к файлу вьюшки.
     */
    public function viewFilePath()
    {
        return view_path(str_replace('.', '/', $this->path)) . '.php';
    }

    /**
     * Выкидывает исключение, если файл с вьюшкой не найден.
     */
    private function throwIfFileNotExists(string $viewFilePath)
    {
        if ( ! file_exists($viewFilePath)) {
            throw new Exception("View [{$this->path}] does not exists.");
        }
    }
}
<?php

namespace Core\Classes;

class Redirect
{
    /**
     * Url для редиректа.
     */
    private $url;

    /**
     * Конструктор.
     */
    function __construct(string $url = '')
    {
        $this->url = $url;
    }

    /**
     * Сохраняет данные запроса в куки.
     */
    public function withInput(array $vars)
    {
        cookie()->setArray('request_old_values', $vars);

        return $this;
    }

    /**
     * Возвращает ответ с успехом и редиректом в заголовках.
     */
    public function withSuccess(string $message)
    {
        cookie()->set('request_success', $message);
        header('Location: ' . $this->url);

        return $this;
    }

    /**
     * Возвращает ответ с ошибкой и редиректом в заголовках.
     */
    public function withError(string $message)
    {
        return $this->withErrors([$message]);
    }

    /**
     * Возвращает ответ с ошибками и редиректом в заголовках.
     */
    public function withErrors(array $errors)
    {
        $previousUrl = $this->url ?: app()->request()->referer();

        if (empty($previousUrl)) {
            abort(404);
        }

        cookie()->setArray('request_errors', $errors);
        header('Location: ' . $previousUrl);

        return $this;
    }

    /**
     * Возвращает ответ с жестким прерыванием выполнения приложения
     */
    public function withAbort(int $code)
    {
        header('Location: ' . url('/' . $code));

        return $this;
    }
}
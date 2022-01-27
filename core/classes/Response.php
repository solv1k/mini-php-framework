<?php

namespace Core\Classes;

class Response
{
    /**
     * Тело ответа.
     */
    private $body;

    /**
     * Код ответа.
     */
    private $code;

    /**
     * Заголовки.
     */
    private $headers = [];

    /**
     * Конструктор.
     */
    public function __construct(string $body, int $code = 200, array $headers = [])
    {
        $this->body = $body;
        $this->code = $code;
        $this->headers = $headers;

        $this->process();
    }

    /**
     * Выводит на экран тело ответа.
     */
    public function printBody()
    {
        print $this->body;
    }

    /**
     * Устанавливает HTTP-код ответа.
     */
    public function makeCode()
    {
        http_response_code($this->code);
    }

    /**
     * Добавляет заголовки ответа.
     */
    public function makeHeaders()
    {
        foreach ($this->headers as $key => $value) {
            header(sprintf('%s: %s', $key, $value));
        }
    }

    /**
     * Процессит ответ.
     */
    public function process()
    {
        $this->makeHeaders();
        $this->makeCode();
        $this->printBody();
    }
}
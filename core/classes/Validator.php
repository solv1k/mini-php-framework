<?php

namespace Core\Classes;

use Exception;

class Validator
{
    /**
     * Инстанс валидатора.
     */
    private static $instance = null;

    /**
     * Массив с сообщениями об ошибках валидации.
     */
    private $errors = [];

    /**
     * Массив с локализацией ошибок валидации.
     */
    private $messages = [];

    /**
     * Конструктор.
     */
    private function __construct(array $data, array $rules)
    {
        $this->setData($data);
        $this->setRules($rules);
        $this->loadMessages();
    }

    protected function __clone() { }
    public function __wakeup() {
        throw new Exception("Validator is singleton.");
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Загружает массив с локализацией ошибок валидации.
     */
    public function loadMessages()
    {
        $messages = require_once(app_path('validation.php'));

        if ( ! is_array($messages) ) {
            throw new Exception("Messages for validation must be array type.");
        }

        $this->messages = $messages;
    }

    /**
     * Возвращает инстанс валидатора.
     */
    private static function getInstance(array $data, array $rules)
    {
        $instance = self::$instance;

        if (null === $instance) {
            $instance = new self($data, $rules);
        } else {
            $instance->setData($data);
            $instance->setRules($rules);
        }

        return $instance;
    }

    /**
     * Создаёт валидатор согласно переданным данным и правилам.
     */
    public static function make(array $data, array $rules)
    {
        $instance = self::getInstance($data, $rules);

        return $instance;
    }

    /**
     * Указывает на наличие ошибок валидации.
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Возвращает результат валидации.
     */
    public function validate()
    {
        foreach ($this->rules as $key => $rulesStr) {
            if (empty($rulesStr)) {
                continue;
            }
            $this->checkNullData($key);

            $ruleList = self::parseRules($rulesStr);

            foreach ($ruleList as $rule) {
                $this->validateData($key, $rule);
            }
        }

        $validated = ! $this->hasErrors();

        return $validated;
    }

    /**
     * Проверка нулевых значений.
     */
    private function checkNullData(string $key)
    {
        if ( ! isset($this->data[$key])) {
            $this->data[$key] = null;
        }
    }

    /**
     * Валидация данных по заданному правилу.
     */
    private function validateData(string $key, string $rule)
    {
        $validatorMethod = strtolower($rule) . 'Validation';
            
        $this->throwIfMethodNotExists($validatorMethod);

        $result = call_user_func([$this, $validatorMethod], $this->data[$key]);

        if ($result == false) {
            $this->addError($key, $rule);
        }
    }

    /**
     * Добавляет сообщение об ошибке в общий массив.
     */
    public function addError(string $key, string $rule)
    {
        $message = sprintf($this->messages[$rule] ?? "Поле \"%s\" не соответствует правилу {$rule}", $key);

        if ( ! empty($this->errors[$key])) {
            $this->errors[$key] .= '<br>' . $message;
        } else {
            $this->errors[$key] = $message;
        }
    }

    /**
     * Выбрасывает исключение, если не найден метод для валидации.
     */
    private function throwIfMethodNotExists(string $validatorMethod)
    {
        if ( ! method_exists(self::class, $validatorMethod)) {
            throw new Exception("Validator method [{$validatorMethod}] does not exists.");
        }
    }

    /**
     * Возвращает текст сообщения ошибки валидации.
     */
    public function error(string $varName = '')
    {
        return $this->errors[$varName] ?? null;
    }

    /**
     * Возвращает массив с ошибками валидации.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Парсит правила из строки с символом "|"
     */
    public static function parseRules(string $rulesStr)
    {
        return explode('|', $rulesStr);
    }

    /**
     * Валидация REQUIRED.
     */
    public function requiredValidation($value)
    {
        return $value != null;
    }

    /**
     * Валидация INTEGER.
     */
    public function integerValidation($value)
    {
        return $value == null || is_numeric($value) && (string)(int)$value === $value;
    }

    /**
     * Валидация UNSIGNED.
     */
    public function unsignedValidation($value)
    {
        return $value == null || $this->integerValidation($value) && $value > 0;
    }

    /**
     * Валидация EMAIL.
     */
    public function emailValidation($value)
    {
        return $value == null || filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Валидация PASSWORD.
     */
    public function passwordValidation($value)
    {
        return $value == null || strlen($value) >= 8 && preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/', $value);
    }

    /**
     * Валидация LOGIN.
     */
    public function loginValidation($value)
    {
        return $value == null || strlen($value) >= 3 && strlen($value) <= 55 && preg_match('/[a-zA-Z0-9]+/', $value);
    }

    /**
     * Валидация UNIQUE EMAIL.
     */
    public function uniqueemailValidation($value)
    {
        return $value == null || DB::table('users')->count(['email' => $value]) == 0;
    }

    /**
     * Валидация UNIQUE LOGIN.
     */
    public function uniqueloginValidation($value)
    {
        if (user()) {
            return $value == null || user()->login == $value || DB::table('users')->count(['login' => $value]) == 0;
        }

        return $value == null || DB::table('users')->count(['login' => $value]) == 0;
    }
}
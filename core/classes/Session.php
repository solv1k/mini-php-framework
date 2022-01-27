<?php

namespace Core\Classes;

class Session
{
    public function get(string $key, string $default = null)
    {
        $value = $_SESSION[$key] ?? null;

        return $value ? base64_decode($value) : $default;
    }

    public function set(string $key, string $value)
    {
        $_SESSION[$key] = base64_encode($value);
    }

    public function getObject(string $key, $default = null)
    {
        $encodedObject = $this->get($key);

        return $encodedObject ? json_decode($encodedObject) : $default;
    }

    public function setObject(string $key, $value)
    {
        $this->set($key, json_encode($value));
    }

    public function destroy()
    {
        session_destroy();
    }
}
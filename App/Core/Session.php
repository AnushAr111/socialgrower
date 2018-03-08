<?php

namespace App\Core;


class Session
{
    public function __construct()
    {
        $this->start();
    }

    public function start()
    {
        session_start();
    }

    public function close()
    {
        session_destroy();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function remove($key)
    {
        $val = self::get($key);
        unset( $_SESSION[$key]);

        return $val;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }
}
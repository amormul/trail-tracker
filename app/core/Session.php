<?php

namespace app\core;

class Session
{
    public function __construct()
    {
        $this->start();
    }

    public function start(): void
    {
        session_start();
    }

    public function __set(string $key, $data): void
    {
        $_SESSION[$key] = $data;
    }

    public function __get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function remote(string $key): void
    {
        if (!empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function destroy(): void
    {
        session_destroy();
    }
}
<?php

namespace app\core;

class Session
{
    private $properties = [];

    public function __construct()
    {
        $this->start();
    }
    public function start(): void
    {
        session_start();
    }

    /**
     * write once properties
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function __set(string $key, mixed $data): void
    {
        $_SESSION[$key] = $data;
    }
    /**
     * return once properties
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * delete once properties from session
     * @param string $key
     * @return void
     */
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

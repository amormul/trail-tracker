<?php

namespace app\core;

class Session
{
    private array $properties = [];

    public function __construct()
    {
        $this->start();
    }

    public function start(): void
    {
        session_start();
    }

    /**
     * Write property to session
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function __set(string $key, $data): void
    {
        $_SESSION[$key] = $data;
    }

    /**
     * Get property from session
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Remove property from session
     * @param string $key
     * @return void
     */
    public function remote(string $key): void
    {
        if (!empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy session
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }
}
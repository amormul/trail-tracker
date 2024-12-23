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
<<<<<<< HEAD
    public function __set(string $key, mixed $data): void
=======
    public function __set(string $key, $data): void
>>>>>>> danylenko-dev
    {
        $_SESSION[$key] = $data;
    }

    /**
     * Get property from session
     * @param string $key
     * @return mixed
     */
<<<<<<< HEAD
    public function __get(string $key): mixed
=======
    public function __get(string $key)
>>>>>>> danylenko-dev
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
<<<<<<< HEAD
}
=======
}
>>>>>>> danylenko-dev

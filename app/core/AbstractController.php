<?php

namespace app\core;


abstract class AbstractController
{
    public View $view;
    public function __construct()
    {
        $this->view = new View();
        $session = new Session();
        $session->login = 'admin';
    }
    public function getCurrentUserId(): int
    {
        $session = new Session();
        return (int)$session->login;
    }

}
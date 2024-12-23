<?php

namespace app\core;


use app\models\User;

abstract class AbstractController
{
    public View $view;
    public function __construct()
    {
        $this->view = new View();
    }

    public function getUserByLogin(): string
    {
        $session = new Session();
        return $session->login;
    }
    public function getCurrentUserId(): int
    {
        $login = $this->getUserByLogin();
        $userModel = new User();
        $user = $userModel->getByLogin($login);
        return (int)$user['id'];
    }
}

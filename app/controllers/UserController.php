<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Route;
use app\core\Session;
use app\core\UserValidators;
use app\models\User;

class UserController extends AbstractController
{
    /**
     * @var User Модель для работы с пользователями
     */
    protected User $model;

    /**
     * @var Session Объект для управления сессиями
     */
    protected Session $session;

    /**
     * Конструктор контроллера пользователя
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->session = new Session();
    }

    /**
     * Отображает страницу входа
     * @return void
     */
    public function login(): void
    {
        $errors = $this->session->errors ?? [];
        $this->session->remote('errors');

        $this->view->render('login', [
            'title' => 'Login',
            'errors' => $errors,
        ]);
    }

    /**
     * Обрабатывает данные для входа пользователя
     * @return void
     */
    public function processLogin(): void
    {
        $data = [
            'username' => filter_input(INPUT_POST, 'username'),
            'password' => filter_input(INPUT_POST, 'password'),
        ];

        $errors = UserValidators::validateLogin($data);

        if (!empty($errors)) {
            $this->session->errors = $errors;
            Route::redirect('/user/login');
            return;
        }

        if ($this->model->verifyCredentials($data['username'], $data['password'])) {
            $this->session->login = $data['username'];
            Route::redirect('/');
        } else {
            $this->session->errors = ['login' => 'Invalid username or password'];
            Route::redirect('/user/login');
        }
    }

    /**
     * Отображает страницу регистрации
     * @return void
     */
    public function register(): void
    {
        $errors = $this->session->errors ?? [];
        $old = $this->session->old ?? [];

        $this->session->remote('errors');
        $this->session->remote('old');

        $this->view->render('register', [
            'title' => 'Register',
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    /**
     * Обрабатывает данные для регистрации пользователя
     * @return void
     */
    public function processRegister(): void
    {
        $data = [
            'username' => filter_input(INPUT_POST, 'username'),
            'email' => filter_input(INPUT_POST, 'email'),
            'phone' => filter_input(INPUT_POST, 'phone'),
            'password' => filter_input(INPUT_POST, 'password'),
            'confirm_password' => filter_input(INPUT_POST, 'confirm_password'),
        ];

        $errors = UserValidators::validateRegistration($data);

        // Проверка уникальности имени пользователя
        if ($this->model->getByLogin($data['username'])) {
            $errors['username'] = 'Username already taken';
        }

        // Проверка уникальности email
        if ($this->model->getByEmail($data['email'])) {
            $errors['email'] = 'Email already registered';
        }

        if (!empty($errors)) {
            $this->session->errors = $errors;
            $this->session->old = $data;
            Route::redirect('/user/register');
            return;
        }

        if ($this->model->create($data)) {
            $this->session->login = $data['username'];
            Route::redirect('/user/login');
        } else {
            $this->session->errors = ['register' => 'Registration failed'];
            Route::redirect('/user/register');
        }
    }

    /**
     * Выполняет выход пользователя из системы
     * @return void
     */
    public function logout(): void
    {
        $this->session->destroy();
        Route::redirect('/user/login');
    }
}

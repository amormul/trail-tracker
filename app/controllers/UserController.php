<?php
namespace app\controllers;

use app\core\UserValidator;
use app\models\User;

class UserController
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // Страница входа
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Валидация данных
            $errors = UserValidator::validateLogin($username, $password);

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='error'>$error</p>";
                }
                return;
            }

            // Ищем пользователя
            $user = $this->model->findUserByUsername($username);
            if ($user && $this->model->validatePassword($password, $user['password'])) {
                // Если данные верные, сохраняем в сессии и перенаправляем
                $_SESSION['user_id'] = $user['id'];
                header('Location: /dashboard');
                exit;
            } else {
                echo "<p class='error'>Invalid username or password!</p>";
            }
        } else {
            include 'views/login.php'; // Отображаем страницу входа
        }
    }

    // Страница регистрации
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Валидация данных
            $errors = UserValidator::validateRegistration($username, $email, $password, $confirmPassword);

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='error'>$error</p>";
                }
                return;
            }

            // Хеширование пароля перед сохранением
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Здесь нужно добавить сохранение пользователя в базу данных
            $this->model->createUser($username, $email, $hashedPassword);

            echo "<p>Registration successful!</p>";
        } else {
            include 'views/register.php'; // Отображаем страницу регистрации
        }
    }
}
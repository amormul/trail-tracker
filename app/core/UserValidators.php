<?php

namespace app\core;

class UserValidator
{
    // Валидация для регистрации
    public static function validateRegistration(string $username, string $email, string $password, string $confirmPassword): array
    {
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        if (empty($password)) {
            $errors[] = 'Password is required.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        return $errors;
    }

    // Валидация для входа
    public static function validateLogin(string $username, string $password): array
    {
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required.';
        }

        if (empty($password)) {
            $errors[] = 'Password is required.';
        }

        return $errors;
    }
}

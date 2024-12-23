<?php

namespace app\core;

class UserValidators
{
    /**
     * Валидирует данные формы входа.
     * @param array $data Данные из формы входа.
     * @return array Список ошибок валидации.
     */
    public static function validateLogin(array $data): array
    {
        $errors = [];

        // Проверка наличия имени пользователя
        if (empty($data['username'])) {
            $errors['username'] = 'Имя пользователя обязательно для заполнения';
        }

        // Проверка наличия пароля
        if (empty($data['password'])) {
            $errors['password'] = 'Пароль обязателен для заполнения';
        }

        return $errors;
    }

    /**
     * Валидирует данные формы регистрации.
     * @param array $data Данные из формы регистрации.
     * @return array Список ошибок валидации.
     */
    public static function validateRegistration(array $data): array
    {
        $errors = [];

        // Валидация имени пользователя
        if (empty($data['username'])) {
            $errors['username'] = 'Имя пользователя обязательно для заполнения';
        } elseif (strlen($data['username']) < 3) {
            $errors['username'] = 'Имя пользователя должно быть не менее 3 символов';
        }

        // Валидация email
        if (empty($data['email'])) {
            $errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Неверный формат email';
        }

        // Валидация телефона (опционально)
        if (!empty($data['phone'])) {
            if (!preg_match('/^[0-9+\-\s()]{10,15}$/', $data['phone'])) {
                $errors['phone'] = 'Неверный формат телефона';
            }
        }

        // Валидация пароля
        if (empty($data['password'])) {
            $errors['password'] = 'Пароль обязателен для заполнения';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Пароль должен быть не менее 6 символов';
        }

        // Валидация подтверждения пароля
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Необходимо подтвердить пароль';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Пароли не совпадают';
        }

        return $errors;
    }
}

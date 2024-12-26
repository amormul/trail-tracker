<?php
namespace app\models;

use app\core\AbstractDB;

class User extends AbstractDB
{
    /**
     * Создаёт нового пользователя в базе данных.
     * @param array $data Ассоц массив username, email, password, phone.
     * @return bool Успешно ли выполнена операция.
     */
    public function create(array $data): bool
    {
        // Хэшируем пароль
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Если телефон не указан, задаём пустую строку
        $phone = $data['phone'] ?? '';

        // Подготавливаем запрос на добавление пользователя
        $stmt = $this->db->prepare("INSERT INTO users (login, email, password, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['username'], $data['email'], $hashedPassword, $phone);

        // Выполняем запрос
        return $stmt->execute();
    }

    /**
     * Получает пользователя из базы данных по логину.
     * @param string $login Логин пользователя.
     * @return array|null Данные пользователя или null, если пользователь не найден.
     */
    public function getByLogin(string $login): ?array
    {
        // Подготавливаем запрос
        $stmt = $this->db->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();

        // Получаем результат
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Получает пользователя из базы данных по email.
     * @param string $email Email пользователя.
     * @return array|null Данные пользователя или null, если пользователь не найден.
     */
    public function getByEmail(string $email): ?array
    {
        // Подготавливаем запрос
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Получаем результат
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Проверяет учётные данные пользователя.
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @return bool True, если данные верны, иначе False.
     */
    public function verifyCredentials(string $login, string $password): bool
    {
        // Получаем данные пользователя по логину
        $user = $this->getByLogin($login);
        if (!$user) {
            return false;
        }

        // Проверяем хэш пароля
        return password_verify($password, $user['password']);
    }
}
<?php

class User extends \app\core\AbstractDB
{
    public function getByLogin(string $login): array
    {
        $query = "SELECT * FROM users WHERE login = ?;";
        $data = null;
        /* создание подготавливаемого запроса */
        if ($stmt = mysqli_prepare($this->db, $query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("s", $login);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            $stmt->bind_result($data);
            $stmt->fetch();
            $stmt->close();
        }
        return $data;
    }
}

<?php

class Route extends \app\core\AbstractDB
{
    public function getById(int $id) : array | null
    {
        $query = "SELECT * FROM routes WHERE route_id = ?";
        /* создание подготавливаемого запроса */
        if($stmt = mysqli_prepare($this->db,$query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            $stmt->bind_result($name, $code);

            $stmt->fetch();

            $res = true;
            if ($stmt->errno != 0) {
                $res = false;
            }
            $stmt->close();
        }
        return $res;


    }

    public function getByTripId(int $id) : array | null
    {
        $sql = "SELECT * FROM routes WHERE trip_id = :trip_id";
    }

    public function create(array $route) : bool
    {

    }

    public function update(array $route) : bool
    {
        $sql = "UPDATE routes SET trip_id = :trip_id WHERE route_id = :route_id";
    }

    public function addLike(int $id) : bool
    {
        $sql = "INSERT INTO route_likes (route_id, user_id) VALUES (:route_id, :user_id)";
    }

    public function countLikes(int $id) : int
    {
        $sql = "SELECT COUNT(*) FROM route_likes WHERE route_id = :route_id";
    }

    public function deleteLike(int $id) : bool
    {
        $sql = "DELETE FROM route_likes WHERE route_id = :route_id";
    }
}
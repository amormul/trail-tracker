<?php

namespace app\models;

use app\core\Helpers;

class Route extends \app\core\AbstractDB
{
    private string $fileDir = 'storage' . DIRECTORY_SEPARATOR . 'imageRoute' . DIRECTORY_SEPARATOR;
    private string $file = '';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * returns route parameters by identifier
     * @param int $id
     * @return array|null
     */
    public function getRouteById(int $id): array | null
    {
        return $this->getById('rotes', 'route_id', $id);
    }

    /**
     * returns route parameters by trip identifier
     * @param int $id
     * @return array|null|bool
     */
    public function getByTripId(int $id): array | null | bool
    {
        $query = "SELECT * FROM routes WHERE trip_id = ?;";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            return $stmt->get_result()->fetch_assoc();
        }
        return null;
    }

    /**
     * creates route
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function create(array $data): bool
    {
        $this->file = Helpers::savePhoto($this->fileDir, $data['photo']);
        $data['trip_id'] = 1;
        var_dump($data);
        $query = "INSERT INTO routes (trip_id, description,photo) VALUES ( ?,?,?);";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("iss", $data['trip_id'], $data['description'], $this->file);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     * deleting a file Photo from  folder images
     * @param int $trip_id
     * @return void
     * @throws \Exception
     */
    function deletePhoto(int $trip_id): void
    {
        $route = $this->getByTripId($trip_id);
        if (!empty($route['photo'])) {
            $this->file = $route['photo'];
            Helpers::deletePhoto($this->file);
        }
        $this->file = '';
    }
    /**
     * updates  route by id
     * @param array $route
     * @return bool
     * @throws \Exception
     */
    public function update(array $data): bool
    {
        $this->deletePhoto($data['trip_id']);
        $this->file = Helpers::savePhoto($this->fileDir, $data['photo']);
        $data['trip_id'] = 1;
        var_dump($data);
        $query = "UPDATE routes SET description=?,photo=? WHERE trip_id = ?;";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ssi", $data['description'], $this->file, $data['trip_id']);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     * adds a like to the route from the user
     * @param int $route_id
     * @param int $user_id
     * @return bool
     */
    public function like(int $route_id, int $user_id): bool
    {
        $query = "SELECT * FROM likes_route WHERE route_id = ? AND user_id = ?;";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ii", $route_id, $user_id);
            /* выполнение запроса */
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if (empty($res)) {
                return $this->addLike($route_id, $user_id);
            } else {
                return $this->deleteLike($route_id, $user_id);
            }
        }
        return false;
    }

    public function addLike(int $route_id, int $user_id): bool
    {
        $query = "INSERT INTO likes_route (route_id, user_id) VALUES (?, ?);";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ii", $route_id, $user_id);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     * returns the number of likes in the route
     * @param int $id
     * @return int
     */
    public function countLikes(int $id): int
    {
        $query = "SELECT COUNT(*) as count FROM likes_route WHERE route_id = ?";
        $count = 0;
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            /* Связываем переменные результата */
            $count = $res["count"];
        }
        return $count;
    }

    /**
     * deletes like record
     * @param int $route_id
     * @param int $user_id
     * @return bool
     */
    public function deleteLike(int $route_id, int $user_id): bool
    {
        $query = "DELETE FROM likes_route WHERE route_id = ? AND user_id = ?;";
        /* создание подготавливаемого запроса */
        if ($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ii", $route_id, $user_id);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }
}

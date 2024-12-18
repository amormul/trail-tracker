<?php

namespace app\models;

class Route extends \app\core\AbstractDB
{
    private string $fileDir = "\storage\imageRoute";
    private string $file = '';
    public function __construct()
    {
        parent::__construct();
        $this->fileDir = realpath($this->fileDir);
    }

    /**
     * returns route parameters by identifier
     * @param int $id
     * @return array|null
     */
    public function getRouteById(int $id) : array | null
    {
        return $this->getById('rotes','route_id', $id);
    }

    /**
     * returns route parameters by trip identifier
     * @param int $id
     * @return array|null|bool
     */
    public function getByTripId(int $id) : array | null | bool
    {
        $query = "SELECT * FROM routes WHERE trip_id = ?;";
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            $stmt->bind_result($data);
            $stmt->fetch();
            return $data;
        }
        return null;
    }

    /**
     * copying a file Photo from temp folder to folder images
     * @param array|null $photo
     * @return void
     * @throws \Exception
     */
    function savePhoto(array $photo=null): void
    {
        $this->file = '';
        if(empty($photo)){
            return;
        }
        if (!empty($photo['name'])) {
            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $uniqueName = 'route' . uniqid() . '.' . $extension;
            $this->file = $this->fileDir . DIRECTORY_SEPARATOR . $uniqueName;
            if (!move_uploaded_file($photo['tmp_name'], $this->file)) {
                $this->file = '';
                throw new \Exception('Photo was not uploaded: ' . $this->file);
            }
        }
    }

    /**
     * deleting a file Photo from  folder images
     * @param array $data
     * @return void
     * @throws \Exception
     */
    function deletePhoto(array $data): void
    {
        $route = $this->getByTripId($data['trip_id']);
        if(!empty($route['photo'])) {
            $this->file = $route['photo'];
            unlink($this->file);
            if (!unlink($this->file)) {
                throw new \Exception('Photo was not deleted: ' . $this->file);
            }
        }
        $this->file = '';
    }

    /**
     * creates route
     * @param array $route
     * @return bool
     * @throws \Exception
     */
    public function create(array $route) : bool
    {
        $this->savePhoto($route['photo']);
        $route['trip_id'] = 1;
        $query = "INSERT INTO routes (trip_id, description,photo) VALUES ( ?,?,?);" ;
        var_dump($query);
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("iss", $route['trip_id'], $route['description'], $this->file);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     * updates  route by id
     * @param array $route
     * @return bool
     * @throws \Exception
     */
    public function update(array $route) : bool
    {
        $this->deletePhoto($route);
        $this->savePhoto($route['photo']);
        $query = "UPDATE routes SET description=?,photo=? WHERE trip_id = ?;" ;
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ssi", $route['description'], $this->file, $route['trip_id']);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     * deletes route
     * @param int $route_id
     * @return bool
     */
    public function delete(int $trip_id) : bool
    {
        $query = "DELETE FROM routes WHERE trip_id = ?;";
          /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $trip_id);
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

    public function like(int $route_id, int $user_id) : bool
    {
        $query = "SELECT * FROM likes_route WHERE route_id = ? AND user_id = ?;";
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ii", $route_id, $user_id);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            $stmt->bind_result($data);
            if ($stmt->fetch()) {
                var_dump($data);
                if(empty($data)){
                    return $this->addLike($route_id,$user_id);
                }else{
                    return $this->deleteLike($route_id,$user_id);
                }
            }
        }
        return false;
    }



    public function addLike(int $route_id, int $user_id) : bool
    {
        $query = "INSERT INTO likes_route (route_id, user_id) VALUES (?, ?);";
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
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
    public function countLikes(int $id) : int
    {
        $query = "SELECT COUNT(id) as count FROM likes_route WHERE route_id = ?";
        $count = 0;
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            /* Связываем переменные результата */
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
        }
        return $count;
    }

    /**
     * deletes like record
     * @param int $route_id
     * @param int $user_id
     * @return bool
     */
    public function deleteLike(int $route_id, int $user_id) : bool
    {
        $query = "DELETE FROM likes_route WHERE route_id = ? AND user_id = ?;";
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ii", $route_id, $user_id);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }
}
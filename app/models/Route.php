<?php

namespace app\models;

use app\core\Helpers;

class Route extends \app\core\AbstractDB
{
    private string $fileDir = "\storage\images\Route";
    private string $file = '';
    protected $table = 'routes';
    protected $tableLike = 'likes_route';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * creates route
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function create(array $data) : bool
    {
        $this->file = Helpers::savePhoto($this->fileDir,$data['photo']);
        return $this->add([
            "trip_id" => $data["trip_id"],
            "photo" => $this->file,
            "description" => $data["description"],
            ],"iss"
        );
    }

    /**
     * deleting a file Photo from  folder images
     * @param int $trip_id
     * @return void
     * @throws \Exception
     */
    function deletePhoto(int $trip_id): void
    {
        $route = $this->getWhere('trip_id',$trip_id,'i');
        if(!empty($route['photo'])) {
            $this->file = $route['photo'];
            Helpers::deletePhoto($this->file);
        }
        $this->file = '';
    }
    /**
     * updates  route by id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function update(array $data) : bool
    {
        $this->file = '';
        $photo = $data['photo'];
        if (empty($photo['name']))
        {
            if(!empty($data['file'])){
                $this->file = $data['file'];
            }
        }else {
            $this->deletePhoto($data['trip_id']);
            $this->file = Helpers::savePhoto($this->fileDir, $photo);
        }
        $query = "UPDATE routes SET description=?,photo=? WHERE trip_id = ?;" ;
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("ssi", $data['description'], $this->file, $data['trip_id']);
            /* выполнение запроса */
            return $stmt->execute();
        }
        return false;
    }

    /**
     *  adds or deletes a like to the route from the user
     * @param int $route_id
     * @param int $user_id
     * @return bool
     * @throws \Exception
     */
    public function like(int $route_id, int $user_id) : bool
    {
        $like = $this->getWhereLike('route_id',$route_id,'user_id',$user_id);
        if (empty($like)) {
                return $this->_addLike([
                    "route_id" => $route_id,
                    "user_id" => $user_id,
                ]);
            }else{
                return $this->_deleteLike('route_id',$route_id,
                    'user_id', $user_id );
            }
    }

    /**
     * returns the number of likes in the route
     * @param int $id
     * @return int
     */
    public function countLikes(int $id) : int
    {
        $query = "SELECT COUNT(*) as count FROM likes_route WHERE route_id = ?";
        $count = 0;
        /* создание подготавливаемого запроса */
        if($stmt = $this->db->prepare($query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
            /* выполнение запроса */
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            var_dump($res);
            /* Связываем переменные результата */
            $count = $res["count"];
        }
        return $count;
    }

}
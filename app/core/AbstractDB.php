<?php

namespace app\core;

use Couchbase\QueryErrorException;
use MongoDB\Driver\Exception\ConnectionException;

class AbstractDB
{
    protected \mysqli $db;


    public function __construct()
    {
        $this->db = $this->getConnection();
    }
    public function getConnection(): \mysqli
    {
        $db = new \mysqli(conf('DB_HOST'), conf('DB_USER'), conf('DB_PASS'), conf('DB_NAME'));
        if($db->connect_errno != 0){
            throw new ConnectionException();
        }
        return $db;
    }
    public function getById(string $table, int $id) : array | null
    {
        $query = "SELECT * FROM $table WHERE route_id = ?";
        $data = null;
        /* создание подготавливаемого запроса */
        if($stmt = mysqli_prepare($this->db,$query)) {
            /* связывание параметров с метками */
            $stmt->bind_param("i", $id);
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
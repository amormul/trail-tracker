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

    /**
     * @return \mysqli
     * @throws \Exception
     */
    public function getConnection(): \mysqli
    {
        $db = new \mysqli(conf('DB_HOST'), conf('DB_USER'), conf('DB_PASS'), conf('DB_NAME'));
        if($db->connect_errno != 0){
            throw new \Exception($db->connect_error);
        }
        return $db;
    }

    /**
     * returns table parameters by identifier
     * @param string $table
     * @param string $field
     * @param int $id
     * @return array|null
     */
    public function getById(string $table,string $field, int $id) : array | null
    {
        $query = "SELECT * FROM $table WHERE $field = ?;";
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
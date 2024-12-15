<?php

namespace app\core;

use Couchbase\QueryErrorException;
use MongoDB\Driver\Exception\ConnectionException;

class AbstractDB
{
    protected \mysqli $db;

    /**
     * @param string $query
     * @return bool|mysqli_result
     * @throws QueryErrorException
     */
    private function query(string $query)
    {
        $result = $this->db->query($query);
        if($this->db->errno != 0){
            throw new QueryErrorException($this->db->error);
        }
        return $result;
    }
    /**
     * database query  fetch_all
     * @param string $query
     * @return array
     * @throws QueryErrorException
     */
    public function queryAll(string $query) : array
    {
        $result = $this->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    /**
     * request completed or not completed
     * @param string $query
     * @return bool
     * @throws QueryErrorException
     */
    public function queryBool(string $query) : bool
    {
        return  $this->query($query);
    }
    /**
     * database query  fetch_assoc
     * @param string $query
     * @return array
     * @throws QueryErrorException
     */
    public function queryRow(string $query) : array
    {
        $result = $this->query($query);
        return $result->fetch_assoc();
    }
    public function __construct()
    {
        $this->db = new \mysqli(conf('DB_HOST'), conf('DB_USER'), conf('DB_PASS'), conf('DB_NAME'));
        if($this->db->connect_errno != 0){
             throw new ConnectionException();
        }
    }

    /**
     * all entries in $this->table
     * @return array
     * @throws QueryErrorException
     */
    public function all() : array
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->queryAll($query);
    }

    /**
     * all records with id &  $field in $this->table
     * @param string $field
     * @return array
     * @throws QueryErrorException
     */
    public function getAllIdAndField(string $field) : array
    {
        $query = "SELECT id,{$field} FROM {$this->table}";
        return $this->queryAll($query);
    }

    /**
     * return all fields by id
     * @param string $table
     * @param int $id
     * @return array
     * @throws QueryErrorException
     */
    public function getAllById(string $table, int $id) : array
    {
        $query = "SELECT * FROM {$table} WHERE id = {$id}";
        return $this->queryRow($query);
    }

    /**
     *  delete record
     * @param string $table
     * @param int $id
     * @return bool
     */
    public function delRow( string $table, int $id) : bool
    {
        $query = "DELETE FROM {$table} WHERE id= ?";
        /* создание подготавливаемого запроса */
        $stmt = mysqli_prepare($this->db,$query);
        /* связывание параметров с метками */
        $stmt->bind_param("i", $id);
        /* выполнение запроса */
        $stmt->execute();
        $res = true;
        if ($stmt->errno != 0) {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

}
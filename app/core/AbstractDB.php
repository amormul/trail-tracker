<?php

namespace app\core;

use Couchbase\QueryErrorException;
use MongoDB\Driver\Exception\ConnectionException;

class AbstractDB
{
    protected \mysqli $db;
    protected $table;
    protected $tableLike;
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
        if ($db->connect_errno != 0) {
            throw new \Exception($db->connect_error);
        }
        return $db;
    }

    /**
     * returns table parameters by identifier
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): array | null
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?;";
        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getAll(): array | null
    {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }
    public function add(array $data, string $types): bool
    {
        $fields = array_keys($data);
//        $values = array_map(fn($val) => "'{$val}'",array_values($data));
//        $query = "INSERT INTO {$this->table} ("
//            . implode(',',$fields) . ") VALUES ("
//            . implode(',',$values) . ");";
//        var_dump($query);
//        exit;
//        $result = $this->db->query($query);
//        if($this->db->errno != 0){
//            throw new \Exception($this->db->error);
//        }
//        return $result;

        $values = array_values($data);
        $val = [];
        foreach ($values as $value) {
            $val[] = "?";
        }
        $query = "INSERT INTO {$this->table} ("
            . implode(',',$fields) . ") VALUES ("
            . implode(',',$val) . ");";
        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param($types, $values[0],$values[1],$values[2]);
            $stmt->execute();
            return $result = $stmt->get_result();
        }
        return false;



    }
    public function _addLike(array $data): bool
    {
        $fields = array_keys($data);
        $values = array_map(fn($val) => "'{$val}'", array_values($data));
        $query = "INSERT INTO {$this->tableLike} ("
            . implode(',', $fields) . ") VALUES ("
            . implode(',', $values) . ");";
        $result = $this->db->query($query);
        if ($this->db->errno != 0) {
            throw new \Exception($this->db->error);
        }
        return $result;
    }
    public function getWhere(string $fields, mixed $value, string $types,string $opeator = '='): array | null
    {
        $query = "SELECT * FROM {$this->table} WHERE {$fields}{$opeator}?;";
        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param($types, $value);
            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_assoc();
            return $result;
        }
        return null;

    }
    public function getWhereLike(string $fields1, int $value1, string $fields2, int $value2): array | null
    {
        $query = "SELECT * FROM {$this->tableLike} WHERE {$fields1}=? AND {$fields2}=?;";
        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param('ii', $value1,$value2);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * deletes like record
     * @param string $fields1
     * @param int $value1
     * @param string $fields2
     * @param int $value2
     * @return bool
     */
    public function _deleteLike(string $fields1, int $value1, string $fields2, int $value2): bool
    {
        $query = "DELETE FROM {$this->tableLike} WHERE {$fields1}=? AND {$fields2}=?;";
        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param('ii', $value1,$value2);
            return $stmt->execute();
        }
        return false;
    }
}

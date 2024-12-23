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
        if ($db->connect_errno != 0) {
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
    public function getById(string $table, string $field, int $id)
    {
        $query = "SELECT * FROM $table WHERE $field = ?;";
        $data = null;

        if ($stmt = mysqli_prepare($this->db, $query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            $stmt->close();
        }

        return $data;
    }
}

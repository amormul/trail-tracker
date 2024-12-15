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
        if($this->db->connect_errno != 0){
            throw new ConnectionException();
        }
        return $db;
    }


}
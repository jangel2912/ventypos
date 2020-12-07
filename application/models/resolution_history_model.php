<?php

class resolution_history_model extends CI_Model
{
    var $connection;

    function __construct()
    {
        parent::__construct();
    }

    function initialize($connection)
    {
        $this->connection = $connection;
    }

    public function getById($id)
    {
        $query = $this->connection->query("SELECT * FROM `resolution_history` WHERE `id` = " . $id . " LIMIT 1;");
        return $query->result()[0];
    }
}
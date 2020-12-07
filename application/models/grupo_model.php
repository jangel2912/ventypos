<?php

class Grupo_model extends CI_Model {
    var $connection;

    public function __construct() {

        parent::__construct();

    }

    public function initialize($connection) {

        $this->connection = $connection;

    }
    
    public function getAll()
    {
        return $this->connection->get("grupo_clientes")->result();
    }
}
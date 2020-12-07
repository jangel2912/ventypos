<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestFullModel
 *
 * @author usuario
 */
class RestFullModel extends CI_Model {
    
     var $connection;

    public function __construct() {
        parent::__construct();

    }

    public function initialize($connection) {
        $this->connection = $connection;

    }    
    
       public function getProductById($id) {
        $query = $this->connection->query("SELECT * FROM producto where id=$id");
        return $query->row();
    }
}

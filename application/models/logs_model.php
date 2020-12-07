<?php

class Logs_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();

    }

    public function initialize($connection) {

        $this->connection = $connection;

    }

    public function get_almacenes($where) {
     
        $this->connection->where($where);
		$query = $this->connection->get('almacen')->result();
        
        return $query;
    }

    

    public function add($data) {
        $this->connection->insert("logs", $data);
    }
    
    public function actualizarTabla()
    {
        $db=$this->session->userdata('base_dato'); 
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'logs'";      
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        { 
            $sql = "CREATE TABLE `logs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                `mensaje` TEXT NOT NULL,
                PRIMARY KEY (`id`)
            )";
            $this->connection->query($sql);
        }
    }

}

?>
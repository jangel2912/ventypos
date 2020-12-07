<?php

class Productos_seriales_model extends CI_Model{

    var $connection;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    } 


    public function agregar_serial_producto($data){
        return $this->connection->insert('producto_seriales',$data);
    }
    
    public function editar_serial_producto($data, $serial_anterior){
        $this->connection->where('producto_seriales.serial', $serial_anterior);
        $this->connection->where('producto_seriales.id_producto',$data["id_producto"]);
        return $this->connection->update('producto_seriales',$data);
    }

    public function  get_seriales_producto($where){

        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('producto_seriales');

        return $query->result();
    }

    public function delete_seriales($where){
        $this->connection->where($where);
        $this->connection->delete("producto_seriales"); 
    }

}
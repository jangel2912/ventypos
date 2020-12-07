<?php 


class productos_tipo_model extends CI_Model{

    var $connection;
    // Constructor
    public function __construct()
    {
        parent::__construct();      
    }
      
    public function initialize($connection){

        $this->connection = $connection;

    } 

    public function get_all(){
       $query = $this->connection->query("SELECT * FROM  producto_tipo");  
       return $query->result();  
    }

    public function get_by_id($grupo_cliente_id = 0){
      $query = $this->connection->query("SELECT * FROM  producto_tipo WHERE id = '".$grupo_cliente_id."'");
      return $query->row_array();               
    }

    public function getMaxId(){       
      $query=$this->connection->query("SELECT MAX(id) AS id FROM producto_tipo");
      return $query->result();        
    }

    public function delete($id_lista){
      $query = "DELETE FROM producto_tipo WHERE id = $id_lista;";
      return $this->connection->query($query);  
    }

}

?>
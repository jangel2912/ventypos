<?php
//modelo es para las facturas de licencias de usuario
//no tiene nada que ver con las facturas del POS
class impresora_rest_categoria_almacen_model extends CI_Model {
 
    var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function add($data){      
       
        foreach ($data['almacen'] as $key => $value) {  
            $datos = array(
                'id_impresora' => $data['id_impresora'],
                'id_categoria' => $data['id_categoria'],
                'id_almacen' => $value->id              
            );           
            $this->connection->insert('impresora_rest_categoria_almacen',$datos);
            //return $this->connection->insert_id();
        }
       
    }

    public function update($data){       
        

        foreach ($data['almacen'] as $key => $value) {  
            $datos = array(
                'id_impresora' => $data['id_impresora'],
                'id_categoria' => $data['id_categoria'],
                'id_almacen' => $value->id              
            );           
            //$this->connection->where('id_categoria', $data['id_categoria']);            
            $this->connection->insert('impresora_rest_categoria_almacen',$datos);
            //return $this->connection->insert_id();
        }


       // return $this->connection->update('impresora_rest_categoria_almacen', $data); 
    }

    public function delete($id){
        $this->connection->where('id_categoria', $id);
        $this->connection->delete('impresora_rest_categoria_almacen'); 
    }

    public function delete_impresora($id){
        $this->connection->where('id_impresora', $id);
        $this->connection->delete('impresora_rest_categoria_almacen'); 
    }

    public function impresora_cate_get_by_idimpresora_cate($categoria){
        $this->connection->select('*');
        $this->connection->from('impresora_rest_categoria_almacen');
        $this->connection->where('id_categoria',$categoria);
        $query = $this->connection->get();
        return $query->row_array();
    }

    public function createtable_impresoras_categoria_almacen(){
        $sql="CREATE TABLE IF NOT EXISTS impresora_rest_categoria_almacen (
        id_impresora INT(11) NOT NULL,
        id_categoria INT(11) NOT NULL,
        id_almacen INT(11) NOT NULL,
        PRIMARY KEY (id_impresora,id_categoria,id_almacen)
        ) ENGINE=INNODB DEFAULT CHARSET=latin1;";
        $this->connection->query($sql);
    }

/***************************/
}
?>
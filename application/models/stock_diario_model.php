<?php

class Stock_diario_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {
        parent::__construct();
    }

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_by_prod_almac($id_almac, $id_prod,$fecha_ini,$fecha_fin) {
        $sql ="SELECT *  FROM   stock_diario,producto p  WHERE fecha between '" . $fecha_ini . "' and '" . $fecha_fin . "' and  stock_diario.almacen_id = '" . $id_almac . "' AND p.nombre= '" . $id_prod . "'  AND stock_diario.producto_id= p.id";
        
        $query = $this->connection->query($sql);

        return $query->row_array();
    }

    public function add($data) {        
        $this->connection->insert_batch('stock_diario', $data);
    }
}
?>






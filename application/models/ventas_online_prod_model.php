<?php

class Ventas_online_prod_model extends CI_Model
{
    var $connection;

	// Constructor
	public function __construct(){
		parent::__construct();
	}

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_by_id_venta($id = 0){
        $query = $this->connection->query("SELECT *   FROM   online_venta_prod   WHERE online_venta_prod.id_venta = '".$id."'");

        return $query->result_array();
	}

    public function get_stock_actual($id, $id_almacen ){
        $query = $this->connection->query("SELECT stock_actual.unidades   FROM   stock_actual where stock_actual.producto_id = '".$id."' AND stock_actual.almacen_id = '".$id_almacen."'");

        return $query->row_array();
	}

    public function delete($id){ 
        $this->connection->where('id', $id);
        $this->connection->delete("online_venta_prod");
    }

}
?>

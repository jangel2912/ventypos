<?php

class Ventas_online_schedule_model extends CI_Model
{
    var $connection;

	// Constructor
	public function __construct(){
		parent::__construct();
	}

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_schedule_by_online_venta_id($id = 0) {
        $query = $this->connection->query("SELECT * FROM `online_venta_schedule` where online_venta_id = $id");
        $query_array_result = $query->row_array();
        $result = "";

        if(!empty($query_array_result)){
            $fecha = DateTime::createFromFormat('Y-m-d', $query_array_result['sale_date']);
            $time = DateTime::createFromFormat('H:i:s', $query_array_result['sale_time']);
            $result = "{$fecha->format("d/m/Y")} {$time->format("g:i A")}";
        }

        return $result;
	}
}
?>

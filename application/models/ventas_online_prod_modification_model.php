<?php

class Ventas_online_prod_modification_model extends CI_Model
{
    var $connection;

	// Constructor
	public function __construct(){
		parent::__construct();
	}

    public function initialize($connection) {
        $this->connection = $connection;
    }

    /*public function get_by_id_venta($id = 0){
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
    }*/
    public function existeTabla($db) {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'online_venta_prod_modification'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `online_venta_prod_modification` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `online_venta_prod_id` int(11) NOT NULL,
                `producto_modificacion_id` int(11) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->connection->query($sql);

            $sql_contraints = "ALTER TABLE `online_venta_prod_modification`
            ADD CONSTRAINT `online_venta_prod_modification_online_venta_prod_id_foreign` FOREIGN KEY (`online_venta_prod_id`) REFERENCES `online_venta_prod` (`id`) ON DELETE CASCADE,
            ADD CONSTRAINT `online_venta_prod_modification_producto_modificacion_id_foreign` FOREIGN KEY (`producto_modificacion_id`) REFERENCES `producto_modificacion` (`id`) ON DELETE CASCADE;";
            
            $this->connection->query($sql_contraints);

        }
    }

    public function get_modifications_by_online_venta_prod_id($id = 0) {
        $query = $this->connection->query("SELECT producto_modificacion.nombre FROM online_venta_prod_modification 
        INNER JOIN producto_modificacion ON producto_modificacion.id = online_venta_prod_modification.producto_modificacion_id
    WHERE online_venta_prod_id = $id");
        return $query->result_array();
	}

}
?>

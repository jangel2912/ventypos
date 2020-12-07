<?php

class Ventas_online_prod_adition_model extends CI_Model
{
    var $connection;

	// Constructor
	public function __construct(){
		parent::__construct();
	}

    public function initialize($connection) {
        $this->connection = $connection;
    }

    /*
    public function get_stock_actual($id, $id_almacen ){
        $query = $this->connection->query("SELECT stock_actual.unidades   FROM   stock_actual where stock_actual.producto_id = '".$id."' AND stock_actual.almacen_id = '".$id_almacen."'");

        return $query->row_array();
	}

    public function delete($id){ 
        $this->connection->where('id', $id);
        $this->connection->delete("online_venta_prod");
    }*/

    public function existeTabla($db) {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'online_venta_prod_adition'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `online_venta_prod_adition` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `online_venta_prod_id` int(11) NOT NULL,
                `producto_adicional_id` int(11) NOT NULL,
                `qty` int(11) NOT NULL DEFAULT '0',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->connection->query($sql);

            $sql_contraints = "ALTER TABLE `online_venta_prod_adition`
            ADD CONSTRAINT `online_venta_prod_adition_online_venta_prod_id_foreign` FOREIGN KEY (`online_venta_prod_id`) REFERENCES `online_venta_prod` (`id`) ON DELETE CASCADE,
            ADD CONSTRAINT `online_venta_prod_adition_producto_adicional_id_foreign` FOREIGN KEY (`producto_adicional_id`) REFERENCES `producto_adicional` (`id`) ON DELETE CASCADE;";

            $this->connection->query($sql_contraints);
        }
    }


    public function get_aditions_by_online_venta_prod_id($id = 0) {
        $query = $this->connection->query("SELECT producto.nombre, online_venta_prod_adition.qty  
        FROM online_venta_prod_adition 
        inner join producto_adicional 
            ON online_venta_prod_adition.producto_adicional_id = producto_adicional.id 
        inner join producto 
            ON producto.id = producto_adicional.id_adicional 
        WHERE online_venta_prod_adition.online_venta_prod_id = $id");
        return $query->result_array();
    }
    
    public function getAdicionales($id){
        $data = [];
        $productos = $this->connection->query("select * from online_venta_prod where id_venta = {$id}")->result_array();
        foreach($productos as $prod) {
            $adicionales = $this->connection->query("select online_venta_prod_adition.qty, producto_adicional.id_adicional, producto_adicional.precio, producto.nombre, producto.precio_venta from online_venta_prod_adition inner join producto_adicional ON online_venta_prod_adition.producto_adicional_id = producto_adicional.id INNER JOIN producto on producto.id = producto_adicional.id_adicional where online_venta_prod_id = {$prod['id']}")->result_array();
            foreach($adicionales as $adicional){
                $data[] = [
                    'id_producto' => $adicional['id_adicional'],
                    'cantidad' => $adicional['qty'],
                    'precio' => $adicional['precio'],
                    'descripcion' => $adicional['nombre'],
                    'precio_sin_impuesto' => $adicional['precio_venta'],
                ];
            }
        }
        return $data;
    }

}
?>

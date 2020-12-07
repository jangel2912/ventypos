<?php

class Stock_actual_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {
        parent::__construct();
    }

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT *  FROM   stock_actual  WHERE stock_actual.id = '" . $id . "'");

        return $query->row_array();
    }

    public function get_by_prod_almac($id_almac="", $id_prod) {
         
        $condicion="";
        if(!empty($id_almac)){
            $condicion=" stock_actual.almacen_id = '" . $id_almac . "' AND ";
        }

        $query = $this->connection->query("SELECT *  FROM   stock_actual  WHERE  $condicion stock_actual.producto_id= '" . $id_prod . "'");

        return $query->row_array();
    }

    public function update_by_prod_almac($id_almac, $id_prod, $valor) {

        $this->connection->query("UPDATE  stock_actual SET unidades = '" . $valor . "'  WHERE stock_actual.almacen_id = '" . $id_almac . "' AND stock_actual.producto_id= '" . $id_prod . "'");
    }

    public function add($id_almacen, $id_producto, $stock) {

        $data = array(
            'almacen_id' => $id_almacen,
            'producto_id' => $id_producto,
            'unidades' => $stock
        );

        $this->connection->insert("stock_actual", $data);
    }

    public function update_array($almacen_id = null, $producto_id = null, $array) {

        if (isset($almacen_id)) {
            $condicion['almacen_id'] = $almacen_id;
        }
        if (isset($producto_id)) {
            $condicion['producto_id'] = $producto_id;
        }

        $query = $this->connection->update('stock_actual', $array, $condicion);
//        var_dump($this->connection->last_query());
//        var_dump($this->connection->affected_rows());

        $query = $this->connection->affected_rows();

        return $query;
    }

    public function update_by_product($data, $producto_id) {

        $sql = "SHOW COLUMNS FROM stock_actual LIKE 'precio_compra'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {// Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `stock_actual` 
                ADD COLUMN `stock_minimo` INT(11) NULL DEFAULT NULL AFTER `unidades`,
                ADD COLUMN `precio_compra` FLOAT(11) NULL DEFAULT NULL AFTER `stock_minimo`,
                ADD COLUMN `precio_venta` FLOAT(11) NULL DEFAULT NULL AFTER `precio_compra`,
                ADD COLUMN `impuesto` FLOAT(11) NULL DEFAULT NULL AFTER `precio_venta`,
                ADD COLUMN `activo` TINYINT(1) NULL DEFAULT 1 AFTER `impuesto`,
                ADD COLUMN `fecha_vencimiento` VARCHAR(100) NULL DEFAULT NULL AFTER `impuesto`;
            ";
            $this->connection->query($sql);
        }

        $this->connection->where('producto_id', $producto_id);
        $this->connection->where('precio_compra IS NULL', null, false);
        $this->connection->where('precio_venta IS NULL', null, false);
        $this->connection->where('impuesto IS NULL', null, false);
        $this->connection->where('fecha_vencimiento IS NULL', null, false);
        $this->connection->update('stock_actual', $data);

//        var_dump($this->connection->last_query());
    }
/*
    public function update_table_stock_actual() {
        $sql1="SHOW COLUMNS FROM stock_actual WHERE FIELD = 'stock_minimo'";
        $this->connection->query($sql1);
        $existeCampo = $this->connection->query($sql1)->result();
        if (count($existeCampo) == 0) {
            $sql = "
            ALTER TABLE `stock_actual` 
            ADD COLUMN `stock_minimo` INT(11) NULL DEFAULT NULL AFTER `unidades`,
            ADD COLUMN `precio_compra` FLOAT(11) NULL DEFAULT NULL AFTER `stock_minimo`,
            ADD COLUMN `precio_venta` FLOAT(11) NULL DEFAULT NULL AFTER `precio_compra`,
            ADD COLUMN `impuesto` FLOAT(11) NULL DEFAULT NULL AFTER `precio_venta`,
            ADD COLUMN `fecha_vencimiento` VARCHAR(100) NULL DEFAULT NULL AFTER `impuesto`,        
            ADD COLUMN `activo` TINYINT(1) NULL DEFAULT 1 AFTER `fecha_vencimiento`;
        ";
        $this->connection->query($sql);
        }
       
    }*/

}
?>






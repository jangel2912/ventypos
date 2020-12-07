<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('costo_promedio'))
{
    function costo_promedio($data) {
        /*print_r($data);
        die();*/
        extract($data);
        // verificamos el monto de las unidades por el precio de compra en el stock actual
        $ci =& get_instance();
         $usuario = $ci->session->userdata('usuario');
        $clave = $ci->session->userdata('clave');
        $servidor = $ci->session->userdata('servidor');
        $base_dato = $ci->session->userdata('base_dato');
        if ( !$base_dato == ""){
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $dbConnection = $ci->load->database($dns, true);
        }
        $ci->load->model("stock_actual_model"); 
        $ci->stock_actual_model->initialize($dbConnection); 
        $almacen = $data['almacen'];
        $producto = $data['producto_id'];

        $queryResult = $ci->stock_actual_model->get_by_prod_almac($almacen,$producto);  

        $monto_actual = $unidades_actual * $precio_compra_actual;
        $monto_movimiento = $unidades * $precio_venta;
        $total_disponible = $unidades_actual + $unidades;
        $costo_promedio = ($monto_actual + $monto_movimiento) / $total_disponible;
        return $costo_promedio;

    }
}

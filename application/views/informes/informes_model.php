<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Informes_model extends CI_Model
{

    public $connection;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    public function get_ajax_data_existensias_inventario($almacen = 0)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $filtro = "";
            if ($almacen != 0) {
                $filtro = " where almacen.id = " . $almacen;
            }

            $sql = "select almacen.nombre as almacen, producto.nombre as producto, codigo, precio_compra, unidades
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                    inner join almacen on almacen.id = stock_actual.almacen_id $filtro";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $filtro = "";
            if ($almacen != 0) {
                $filtro = " where almacen.id = " . $almacen;
            }

            $sql = "select almacen.nombre as almacen, producto.nombre as producto, codigo, precio_compra, unidades
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                    inner join almacen on almacen.id = stock_actual.almacen_id $filtro";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->almacen,
                $value->producto,
                $value->codigo,
                number_format($value->precio_compra, 2),
                $value->unidades,
                number_format($value->unidades * $value->precio_compra, 2),
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function ventasxclients($fecha_inicio = "", $fecha_fin = "")
    {
        //------------------------------------------------ almacen usuario
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = '';
        $almacen = '';
        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
        }
        //---------------------------------------------
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) <= '$fecha_fin'";
        }

        if ($is_admin == 't') { //administrador

            $sql = "SELECT a.nombre AS almacen, v.fecha, factura, c.nombre_comercial AS cliente, v.total_venta, sum(margen_utilidad) as margen_utilidad
                FROM venta AS v
                    INNER JOIN detalle_venta on venta_id = v.id
                    LEFT JOIN almacen AS a ON v.almacen_id = a.id
                    LEFT JOIN clientes AS c ON v.cliente_id =  c.id_cliente where v.id $filtro_fecha
                        group by(v.id)
                        ORDER BY v.fecha,a.nombre ";
        }

        if ($is_admin != 't') { //usuario

            $sql = "SELECT a.nombre AS almacen, v.fecha, factura, c.nombre_comercial AS cliente, v.total_venta, sum(margen_utilidad) as margen_utilidad
                FROM venta AS v
                    INNER JOIN detalle_venta on venta_id = v.id
                    LEFT JOIN almacen AS a ON v.almacen_id = a.id
                    LEFT JOIN clientes AS c ON v.cliente_id =  c.id_cliente where v.id $filtro_fecha and v.almacen_id  = $almacen
                        group by(v.id)
                        ORDER BY v.fecha,a.nombre ";
        }

        /*echo $sql;*/

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->almacen,
                $value->fecha,
                $value->factura,
                $value->cliente,
                number_format($value->total_venta),
                number_format($value->margen_utilidad),
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function ventasxclientsex($fecha_inicio = "", $fecha_fin = "")
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) <= '$fecha_fin'";
        }

        $sql = "SELECT a.nombre AS almacen, v.fecha, factura, c.nombre_comercial AS cliente, v.total_venta, sum(margen_utilidad) as margen_utilidad
                FROM venta AS v
                    INNER JOIN detalle_venta on venta_id = v.id
                    LEFT JOIN almacen AS a ON v.almacen_id = a.id
                    LEFT JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
                        group by(v.id)
                        ORDER BY v.fecha,a.nombre ";

        /*echo $sql;*/

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->almacen,
                $value->fecha,
                $value->factura,
                $value->cliente,
                $value->total_venta,
                $value->margen_utilidad,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function descuentosotorgados($fecha_inicio = "", $fecha_fin = "")
    {
        $estado = array('ABIERTO', 'CERRADA', 'ANULADA');
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "
               select f.numero, f.fecha, f.estado, fd.descripcion, fd.precio, fd.cantidad, fd.descuento from facturas_detalles fd inner join facturas f on f.id_factura = fd.id_factura $filtro_fecha order by f.numero
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $descuento_unidad = $value->precio * $value->descuento / 100;
            $data[] = array(
                $value->numero,
                $value->fecha,
                $estado[$value->estado],
                $value->descripcion,
                $value->precio,
                $value->cantidad,
                $descuento_unidad,
                $descuento_unidad * $value->cantidad,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function margenutilidad()
    {
        $sql = "select * from productos";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $margen_utilidad = $value->precio - $value->precio_compra;
            $data[] = array(
                $value->codigo,
                $value->nombre,
                ceil($value->precio_compra),
                ceil($value->precio),
                ceil($margen_utilidad),
                ceil(($margen_utilidad - $value->precio_compra) * 100),
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function ventasxproductos($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select descripcion, sum(cantidad) as cantidad, avg(precio) as precio, avg(descuento) as descuento from facturas_detalles fd inner join facturas f on fd.id_factura = f.id_factura
                    $filtro_fecha
                group by descripcion
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $descuento_total = $value->precio * $value->descuento / 100 * $value->cantidad;
            $data[] = array(
                $value->descripcion,
                $value->cantidad,
                $value->precio * $value->cantidad - $descuento_total,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function detallesgastos($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select p.*, i.porciento from proformas p inner join impuestos i on p.id_impuesto = p.id_impuesto
                    $filtro_fecha
                group by descripcion
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $valor_total = $value->cantidad * $value->porciento;
            $data[] = array(
                $value->fecha,
                $value->descripcion,
                $valor_total + $value->cantidad,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function detallesimpuestos($fecha_inicio, $fecha_fin)
    {
        $estado = array('ABIERTO', 'CERRADA', 'ANULADA');
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select f.*, c.nombre_comercial from facturas f inner join clientes c on f.id_cliente = c.id_cliente
                    $filtro_fecha
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $valor_impuesto = $value->monto - $value->monto_siva;
            $data[] = array(
                $estado[$value->estado],
                $value->numero,
                $value->fecha,
                $value->nombre_comercial,
                $value->monto,
                $valor_impuesto,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function pagosrecibidos($fecha_inicio, $fecha_fin)
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha_pago BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha_pago > '$fecha_inicio'";

        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha_pago < '$fecha_fin'";
        }

        $sql = "select
            p.id_pago, p.fecha_pago, v.factura, c.nombre_comercial, p.tipo,  p.cantidad, v.total_venta
            from pagos p
            inner join venta v on v.id = p.id_factura
            inner join clientes c on v.cliente_id = c.id_cliente
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->id_pago,
                $value->fecha_pago,
                $value->factura,
                $value->nombre_comercial,
                $value->tipo,
                $value->cantidad,
                $value->total_venta,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function resumenimpuestos()
    {
        $sql = "SELECT i.nombre_impuesto, i.porciento, sum( precio ) AS monto, sum( precio * f.impuesto ) AS valor_impuesto
                    FROM facturas_detalles f
                        INNER JOIN impuestos i ON i.porciento = f.impuesto
                            GROUP BY i.porciento
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->nombre_impuesto,
                $value->porciento,
                $value->monto,
                $value->valor_impuesto,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    //Cuadre de caja
    public function utilidad_periodo($fechainicial, $fechafinal)
    {

        $vr_valor = 0;
        $vr_costos = 0;
        $vr_gastos = 0;
        $vr_descuento = 0;

        $ventas = "SELECT * FROM venta where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' and estado=0";
        $ventas_id = $this->connection->query($ventas)->result();
        foreach ($ventas_id as $value) {

            $detalle_ventas = "SELECT codigo_producto, sum(descuento) as descuento,sum(precio_venta) as total, sum(unidades) as unidades FROM detalle_venta where venta_id = '$value->id' group by codigo_producto";
            $detalle_ventas_id = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_id as $det) {
                $vr_valor += $det->total * $det->unidades;
                $vr_descuento += $det->descuento;
                // echo $det->codigo_producto."<br>";

                /*      $inventario = "SELECT * FROM movimiento_inventario where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' and tipo_movimiento = 'entrada_compra'";
                $inventario_id = $this->connection->query($inventario)->result();
                foreach ($inventario_id as $value) {
                 */
                $detalle_inventario = "SELECT sum(precio_compra) as total_compra FROM producto where codigo = '$det->codigo_producto'";
                $detalle_inventario_id = $this->connection->query($detalle_inventario)->result();
                foreach ($detalle_inventario_id as $prod) {
                    $vr_costos += $prod->total_compra * $det->unidades;
                }

                //}

            }

        }

        $gastos = "SELECT sum(valor) as total FROM proformas where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal'";
        $gastos_id = $this->connection->query($gastos)->result();
        foreach ($gastos_id as $value) {

            $vr_gastos += $value->total;

        }

        return array(
            'total_venta' => $vr_valor
            , 'total_descuento' => $vr_descuento
            , 'total_costos' => $vr_costos
            , 'total_gastos' => $vr_gastos,
        );
    }

    public function valor_inventario()
    {

        $ventaid = 0;
        $rest = 0;
        $rest1 = 0;
        $detalleventaid = 0;
        $valor_total_detalle = 0;
        $valor_total_venta = 0;
        $total_existencias = 0;

        //------------------------------------------------ almacen usuario
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = '';
        $almacen = '';
        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
        }
        //---------------------------------------------

        if ($is_admin == 't') { //administrador
            $almacen = "SELECT id, nombre FROM almacen";
        }

        if ($is_admin != 't') { //usuario
            $almacen = "SELECT id, nombre FROM almacen where id='$almacen'";
        }

        $almacen_result = $this->connection->query($almacen)->result();
        foreach ($almacen_result as $value) {

            $movimiento_detalle = "SELECT precio_compra, stock_actual.unidades as total_unidades  FROM producto
	 inner join stock_actual on producto.id = stock_actual.producto_id
	  where stock_actual.almacen_id  =  $value->id ";
            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();
            foreach ($movimiento_detalle_id as $det) {

                $valor_total_detalle += $det->precio_compra * $det->total_unidades;

            }

            $movimiento_detalle = "SELECT precio_venta, stock_actual.unidades as total_unidades FROM producto
	 inner join stock_actual on producto.id = stock_actual.producto_id
	  where stock_actual.almacen_id  =  $value->id ";
            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();
            foreach ($movimiento_detalle_id as $det) {
                $valor_total_venta += $det->precio_venta * $det->total_unidades;

            }

            $inventario_almacen[] = array(
                'almacen_nombre' => $value->nombre
                , 'valor_inventario' => $valor_total_detalle
                , 'valor_venta' => $valor_total_venta,
            );

        }

        return array(
            'almacenes' => $inventario_almacen,
        );
    }

    public function total_ventas_hora($fechainicial, $fechafinal, $almacen)
    {
        /*
        $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS fecha,SUM(`total_venta`) AS total_venta
        FROM `venta`
        WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
        GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H')";
        $total_ventas_result = $this->connection->query($total_ventas)->result();

        return array(
        'total_ventas' => $total_ventas_result
        );     */
        $ventas = array();

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS fecha
	 ,SUM( `unidades` * `descuento` ) AS total_descuento
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades`) AS total_precio_venta
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
	GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H') ";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario

            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------

            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS fecha
	 ,SUM( `unidades` * `descuento` ) AS total_descuento
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades`) AS total_precio_venta
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
	GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H') ";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia
                , 'fecha' => $value->fecha
                , 'total_descuento' => $value->total_descuento
                , 'total_impuesto' => $value->impuesto
                , 'total_precio_venta' => $value->total_precio_venta - $value->total_descuento,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function ventas_categoria($fechainicial, $fechafinal)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $ventas_categorias = array();

        if ($is_admin == 't') { //administrador
            $query = "
			SELECT DATE(v.fecha) AS fecha, c.nombre, SUM( dv.unidades * dv.descuento ) AS total_descuento,
			SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto, SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
			FROM  detalle_venta as dv inner join venta as v on dv.venta_id = v.id
			inner join producto as p on dv.codigo_producto = p.id inner join categoria as c on p.categoria_id=c.id
			WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal' and estado = 0
			GROUP BY p.categoria_id
			ORDER BY v.fecha desc ";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $query = "
			SELECT DATE(v.fecha) AS fecha, c.nombre, SUM( dv.unidades * dv.descuento ) AS total_descuento,
			SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto, SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
			FROM  detalle_venta as dv inner join venta as v on dv.venta_id = v.id
			inner join producto as p on dv.codigo_producto = p.id inner join categoria as c on p.categoria_id=c.id
			WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal' and estado = 0 and v.almacen_id = $almacen
			GROUP BY p.categoria_id
			ORDER BY v.fecha desc ";
        }

        $model = $this->connection->query($query)->result();

        foreach ($model as $value) {
            $ventas_categorias[] = array(
                'fecha' => $value->fecha,
                'categoria' => $value->nombre,
                'total' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto,
            );
        }

        return array(
            'ventas_categorias' => $ventas_categorias,
        );
    }

    public function export_erp($fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $ventas = array();

        if ($is_admin == 't') { //administrador
            $total_ventas = "
   SELECT
   venta.fecha as fechaventa, factura, nombre_producto, detalle_venta.precio_venta as precioventa, alm_equivalencia, prod_equivalencia_1, prod_equivalencia_2, prod_equivalencia_3, provincia
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join producto on producto.nombre = detalle_venta.nombre_producto
	inner join clientes on clientes.id_cliente = venta.cliente_id
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $total_ventas = "
   SELECT
   venta.fecha as fechaventa, factura, nombre_producto, detalle_venta.precio_venta as precioventa, alm_equivalencia, prod_equivalencia_1, prod_equivalencia_2, provincia
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join producto on producto.nombre = detalle_venta.nombre_producto
	inner join clientes on clientes.id_cliente = venta.cliente_id
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $date = 0;
        $fecha = 0;
        foreach ($total_ventas_result as $value) {

            $date = date_create($value->fechaventa);
            $fecha = date_format($date, 'dmY');

            $ventas[] = array(
                'fechaventa' => $fecha
                , 'factura' => $value->factura
                , 'nombre_producto' => $value->nombre_producto
                , 'precioventa' => $value->precioventa
                , 'alm_equivalencia' => $value->alm_equivalencia
                , 'prod_equivalencia_1' => $value->prod_equivalencia_1
                , 'prod_equivalencia_2' => $value->prod_equivalencia_2
                , 'prod_equivalencia_3' => $value->prod_equivalencia_3
                , 'provincia' => $value->provincia,

            );
        }

        return array(
            'total_ventas' => $ventas,
        );

    }

    public function export_office($fechainicial, $fechafinal, $almacen)
    {
        $empresa = '';
        $user = $this->connection->query("SELECT * FROM `opciones` where id = '1'")->result();
        foreach ($user as $dat) {
            $empresa = $dat->valor_opcion;
        }

        $ventas = array();
        $total_ventas = "
   SELECT venta_id,  codigo_producto,
   venta.factura as numerofac, venta.fecha as fechaventa, clientes.nif_cif as nit, ventas_pago.forma_pago as formapago,
   nombre_producto, unidades, descuento, detalle_venta.precio_venta, detalle_venta.impuesto, estado, descripcion_producto
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join clientes on clientes.id_cliente = venta.cliente_id
	   inner join ventas_pago on detalle_venta.venta_id = ventas_pago.id_venta
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'";
        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $date = 0;
        $fecha = 0;
        foreach ($total_ventas_result as $value) {

            $date = date_create($value->fechaventa);
            $fecha = date_format($date, 'd/m/Y');

            $total_precio_venta = '';
            $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	  ,SUM( `descripcion_producto` ) AS sobrecosto
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id = '$value->venta_id'";
            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {
                $propina = '0.' . $dat1->sobrecosto;
                $total_precio_venta = round(($dat1->total_precio_venta - $dat1->total_descuento) * $propina);
            }

            $ventas[] = array(
                'venta_id' => $value->venta_id
                , 'numerofac' => $value->numerofac
                , 'fechaventa' => $fecha
                , 'nit' => $value->nit
                , 'formapago' => $value->formapago
                , 'nombre_producto' => $value->nombre_producto
                , 'codigo_producto' => $value->codigo_producto
                , 'unidades' => $value->unidades
                , 'descuento' => $value->descuento
                , 'precio_venta' => $value->precio_venta
                , 'impuesto' => $value->impuesto
                , 'estado' => $value->estado
                , 'empresa' => $empresa
                , 'total_precio_venta' => $total_precio_venta,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );

    }

    public function total_ventas_dia($fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $ventas = array();

        if ($is_admin == 't') { //administrador
            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'
	 GROUP BY DATE_FORMAT(`fecha`,'%d') ";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'
	 GROUP BY DATE_FORMAT(`fecha`,'%d') ";
        }
        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia
                , 'total_descuento' => $value->total_descuento
                , 'total_impuesto' => $value->impuesto
                , 'total_precio_venta' => $value->total_precio_venta - $value->total_descuento,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );

    }

    public function total_ventas_mes($fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $ventas = array();

        if ($is_admin == 't') { //administrador
            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	 ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE DATE_FORMAT(`fecha`,  '%m') >= DATE_FORMAT('$fechainicial','%m')
	 AND DATE_FORMAT(`fecha`,'%m') <= DATE_FORMAT('$fechafinal','%m')
	 AND almacen_id =  '$almacen'
	 AND estado =0
	 GROUP BY DATE_FORMAT(`fecha` ,'%m') ";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $total_ventas = "
	 SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	 ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE DATE_FORMAT(`fecha`,  '%m') >= DATE_FORMAT('$fechainicial','%m')
	 AND DATE_FORMAT(`fecha`,'%m') <= DATE_FORMAT('$fechafinal','%m')
	 AND almacen_id =  '$almacen'
	 AND estado =0
	 GROUP BY DATE_FORMAT(`fecha` ,'%m') ";
        }
        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia
                , 'total_descuento' => $value->total_descuento
                , 'total_impuesto' => $value->impuesto
                , 'total_precio_venta' => $value->total_precio_venta - $value->total_descuento,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );

    }

    public function habitos_consumo_hora($fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H')";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------

            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H')";
        }

        $total_ventas = $this->connection->query($total_ventas)->result();
        foreach ($total_ventas as $value) {

            $value->fecha . "<br>";
            $detalle_ventas = "SELECT id FROM `venta`  where DATE_FORMAT(`fecha`,'%h:00 %p') = '$value->fecha'  and estado = 0 ";
            $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_result as $det) {

                $vr_impuesto = 0;
                $vr_valor = 0;
                $vr_pdv = 0;
                $vr_pdv1 = 0;
                $vr_pdv2 = 0;
                $vr_column = 0;
                $vr_bruto = 0;
                $vr_unidades = 0;

                $detalle_ventas = "SELECT DATE(`fecha`) AS fecha_dia, venta_id ,nombre_producto, sum(unidades) as unidades, sum(precio_venta) as total_detalleventa, sum(descuento) as descuento, impuesto FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id  where venta_id = '$det->id' group by nombre_producto";
                $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();
                foreach ($detalle_ventas_result_1 as $prod) {

                    $vr_pdv = $prod->total_detalleventa * $prod->unidades;
                    $vr_pdv1 = $prod->descuento * $prod->unidades;
                    $vr_pdv2 = $prod->total_detalleventa - $prod->descuento;

                    $vr_bruto = $vr_pdv - $vr_pdv1;
                    $vr_impuesto = $vr_pdv2 * $prod->impuesto / 100 * $prod->unidades;

                    $consumo_productos[] = array(
                        'fecha_dia' => $prod->fecha_dia
                        , 'unidades' => $prod->unidades
                        , 'fecha' => $value->fecha
                        , 'nombre' => $prod->nombre_producto
                        , 'total_detalleventa' => $vr_bruto + $vr_impuesto);
                }

            }

        }
        return array(
            'total_ventas_1' => $total_ventas
            , 'total_ventas_2' => $detalle_ventas_result
            , 'total_ventas_3' => $consumo_productos,
        );
    }

    public function menos_rotacion($fechainicial, $fechafinal, $almacen)
    {

        $ventaid = 0;
        $rest = 0;
        $rest1 = 0;
        $detalleventaid = 0;

        $ventas = "SELECT * FROM venta where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' and estado = '0' ";
        $ventas_id = $this->connection->query($ventas)->result();
        foreach ($ventas_id as $value) {

            $ventaid = $ventaid . "," . $value->id;

        }
        $rest = substr($ventaid, 2);
        if ($rest == '') {$rest = 0;} else { $rest = substr($ventaid, 2);}

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $detalle_ventas = "SELECT * FROM detalle_venta where venta_id  IN (" . $rest . ")";
            $detalle_ventas_id = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_id as $det) {
                $detalleventaid = $detalleventaid . ",'" . $det->codigo_producto . "'";
            }
            $rest1 = substr($detalleventaid, 2);
            if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

            $productos_ventas = "SELECT * FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ") and stock_actual.almacen_id = '$almacen' ";
            $productos_ventas_id = $this->connection->query($productos_ventas)->result();

            $productos_ventas = "SELECT sum(precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ") and stock_actual.almacen_id = '$almacen' ";
            $productos_ventas_totales = $this->connection->query($productos_ventas)->result();
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $detalle_ventas = "SELECT * FROM detalle_venta where venta_id  IN (" . $rest . ")";
            $detalle_ventas_id = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_id as $det) {
                $detalleventaid = $detalleventaid . ",'" . $det->codigo_producto . "'";
            }
            $rest1 = substr($detalleventaid, 2);
            if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

            $productos_ventas = "SELECT * FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ") and stock_actual.almacen_id = '$almacen' ";
            $productos_ventas_id = $this->connection->query($productos_ventas)->result();

            $productos_ventas = "SELECT sum(precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ") and stock_actual.almacen_id = '$almacen' ";
            $productos_ventas_totales = $this->connection->query($productos_ventas)->result();
        }

        return array(
            'productos' => $productos_ventas_id
            , 'totales' => $productos_ventas_totales,
        );
    }

    /*INVOCE2 ======================================*/

    //Cuadre de caja
    public function cuadre_caja($fecha, $tipo, $almacen)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
        }
        $forma_pago = "select venta.id as id_venta, sum(total_venta) as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' and venta.almacen_id = '$almacen' group by forma_pago";
        $forma_pago_result1 = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_result1[] = array('forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );

        }

        $forma_pago = "select venta.id as id_venta, sum(total_venta) as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' and venta.almacen_id = '$almacen' and forma_pago <> 'Credito' and forma_pago <> 'Saldo_a_Favor'   group by forma_pago";
        $forma_pago_ventas = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_ventas[] = array('forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );

        }

        $forma_pago = "select venta.id as id_venta, sum(total_venta) as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' and venta.almacen_id = '$almacen'  and forma_pago <> 'Credito' and forma_pago <> 'Saldo_a_Favor'    group by forma_pago";
        $forma_pago_ventas = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_ventas[] = array('forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );

        }

        $forma_pago = "select sum(cantidad) as total_credito from pago
	   inner join venta on pago.id_factura = venta.id
		where date(fecha_pago) = '$fecha'  and venta.almacen_id = '$almacen' ";
        $forma_pago_credito = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_credito[] = array('total_credito' => $value->total_credito,
            );

        }

        $forma_pago = "select sum(cantidad) as total_credito from pago_orden_compra
	   inner join venta on pago_orden_compra.id_factura = venta.id
		where date(fecha_pago) = '$fecha'  and venta.almacen_id = '$almacen'  ";
        $forma_pago_proveedor = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_proveedor[] = array('total_proveedor' => $value->total_credito,
            );

        }

        $impuesto = "select nombre_impuesto, impuesto.porciento, sum(unidades) as unidades, sum(precio_venta) as precio
		 from venta
		  inner join detalle_venta on detalle_venta.venta_id = venta.id
		  inner join impuesto on detalle_venta.impuesto = impuesto.porciento
		   where date(fecha) = '$fecha' and estado='0'  and venta.almacen_id = '$almacen'  group by impuesto.id_impuesto";

        $gastos = "SELECT sum(valor) as total FROM proformas where fecha = '$fecha' and id_almacen = '$almacen'   group by fecha;";

        $factura = "SELECT
                        venta.id, venta.factura, venta.total_venta, codigo_producto,nombre_producto,sum(unidades) as unidades ,
                        precio_venta as precio_unidad ,impuesto, ( (sum(precio_venta) * (impuesto/100) ) *unidades) as valor_impuesto,
                        (precio_venta * sum(unidades)) as valor,
                        sum((precio_venta+((precio_venta)*(impuesto/100)))*unidades) as total ,
                        (sum(descuento)*unidades) as descuento,
						count(venta.id) as total_final
                    FROM venta
                    inner join detalle_venta on detalle_venta.venta_id = venta.id
                    where date(fecha) = '$fecha' and estado='0'  and venta.almacen_id = '$almacen'
                    group by codigo_producto order by venta.id asc";

        $forma_pago_result = $this->connection->query($forma_pago)->result();

        $gastos_pago_result = $this->connection->query($gastos)->result();

        $impuesto_result = $this->connection->query($impuesto)->result();

        if ($tipo == 'producto') {

            $factura1 = "SELECT venta.id as id_venta, factura FROM venta  where date(fecha) = '$fecha' and estado='0' and venta.almacen_id = '$almacen'  ";
            $factura_result = $this->connection->query($factura1)->result();

            $factura_data = array();
            foreach ($factura_result as $value) {
                $value->id_venta . "<br>";
                $get_details = "select precio_venta, unidades, descuento, impuesto, venta_id, nombre_producto from detalle_venta where venta_id = $value->id_venta   ";
                $details_result = $this->connection->query($get_details)->result();
                $vr_impuesto = 0;
                $vr_valor = 0;
                $vr_pdv = 0;
                $vr_pdv1 = 0;
                $vr_column = 0;
                $vr_bruto = 0;
                $vr_unidades = 0;
                foreach ($details_result as $detail) {

                    $vr_pdv = $detail->precio_venta * $detail->unidades;
                    $vr_pdv1 = $detail->descuento * $detail->unidades;
                    $vr_pdv2 = $detail->precio_venta - $detail->descuento;
                    $vr_unidades = $detail->unidades;

                    $vr_bruto = $vr_pdv - $vr_pdv1;
                    $vr_impuesto = $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                    $vr_column = $vr_pdv - $vr_pdv1;
                    $vr_valor = $vr_pdv - $vr_pdv1;

                    $factura_data[] = array('nombre_producto' => $detail->nombre_producto,
                        'unidades' => $vr_unidades,
                        'precio_unidad' => $detail->precio_venta,
                        'valor' => $vr_bruto,
                        'valor_impuesto' => $vr_impuesto,
                        'total' => $detail->precio_venta,
                        'descuento' => $detail->descuento,
                        'factura' => $value->factura,
                    );

                }

            }

            return array(
                'forma_pago' => $forma_pago_result1
                , 'impuesto_result' => $impuesto_result
                , 'factura_data' => $factura_data
                , 'gastos' => $gastos_pago_result
                , 'forma_pago_credito' => $forma_pago_credito
                , 'forma_pago_proveedor' => $forma_pago_proveedor
                , 'forma_pago_ventas' => $forma_pago_ventas,
            );

        } else if ($tipo == 'factura') {
            $factura1 = "SELECT
                        venta.id, venta.factura, venta.total_venta, codigo_producto,nombre_producto,sum(unidades) as unidades ,
                        precio_venta as precio_unidad ,impuesto, ( ((sum(precio_venta)*unidades - sum(descuento)) * (impuesto/100) ) ) as valor_impuesto,
                        (sum(unidades)) as valor_unidades,
						(sum(precio_venta)) as valor_ventas,
                       (precio_venta+((precio_venta)*(impuesto/100)))*unidades as total ,
                        (sum(descuento) ) as descuento
                    FROM venta
                    inner join detalle_venta on detalle_venta.venta_id = venta.id
                    where date(fecha) = '$fecha' and estado = '0'  and venta.almacen_id = '$almacen'
                    group by venta.id order by venta.id asc";
            $factura_result = $this->connection->query($factura1)->result();

            $factura_data = array();
            foreach ($factura_result as $value) {
                $get_details = "select * from detalle_venta where venta_id = $value->id";
                $details_result = $this->connection->query($get_details)->result();
                $vr_impuesto = 0;
                $vr_valor = 0;
                $vr_pdv = 0;
                $vr_pdv1 = 0;
                $vr_column = 0;
                $vr_bruto = 0;
                foreach ($details_result as $detail) {

                    $vr_pdv = $detail->precio_venta * $detail->unidades;
                    $vr_pdv1 = $detail->descuento * $detail->unidades;
                    $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                    $vr_bruto += $vr_pdv;
                    $vr_impuesto += $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                    $vr_column = $vr_pdv - $vr_pdv1;
                    $vr_valor += $vr_pdv - $vr_pdv1;
                }

                $factura_data[] = array('factura' => $value->factura,
                    'total_venta' => $value->total_venta,
                    'vr_impuesto' => $vr_impuesto,
                    'vr_valor' => $vr_valor,
                    'descuento' => $value->descuento * $detail->unidades,
                    'vr_bruto' => $vr_bruto,
                );

            }

            return array(
                'forma_pago' => $forma_pago_result1
                , 'impuesto_result' => $impuesto_result
                , 'factura_data' => $factura_data
                , 'gastos' => $gastos_pago_result
                , 'forma_pago_credito' => $forma_pago_credito
                , 'forma_pago_proveedor' => $forma_pago_proveedor
                , 'forma_pago_ventas' => $forma_pago_ventas,
            );

        }
        /*echo $forma_pago."<br/>";
    echo $impuesto."<br/>";
    echo $factura."<br/>";
    die;*/

    }
    //Ventas por clientes
    public function ventasgroupclientes($fecha_inicio = "", $fecha_fin = "")
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where v.fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where v.fecha < '$fecha_fin'";
        }

        $sql = "SELECT count(v.id) cantidad, sum(v.total_venta) total_venta, c.nombre_comercial AS cliente
                FROM venta AS v
                    inner JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
                        group by(c.id_cliente)
                        ORDER BY total_venta";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->cliente,
                $value->cantidad,
                number_format($value->total_venta, 2),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    //Informes de gastos
    public function informe_gastos($fecha_inicio, $fecha_fin, $opc)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }
        $sql = "SELECT *
                    FROM proformas p
                        LEFT JOIN impuesto i ON i.id_impuesto = p.id_impuesto
                        inner join proveedores ON p.id_proveedor = proveedores.id_proveedor $filtro_fecha
        ";

        $data = array();
        if ($opc == 'excel') {
            foreach ($this->connection->query($sql)->result() as $value) {
                $valor_impuesto = $value->valor * $value->porciento / 100;
                $data[] = array(
                    $value->fecha,
                    $value->nombre_comercial,
                    $value->descripcion,
                    $value->valor,
                    $value->nombre_impuesto,
                    $valor_impuesto + $value->valor,
                );
            }
            return array(
                'aaData' => $data,
            );
        } else {
            foreach ($this->connection->query($sql)->result() as $value) {
                $valor_impuesto = $value->valor * $value->porciento / 100;
                $data[] = array(
                    $value->fecha,
                    $value->nombre_comercial,
                    $value->descripcion,
                    number_format($value->valor),
                    $value->nombre_impuesto,
                    number_format($valor_impuesto + $value->valor),
                );
            }
            return array(
                'aaData' => $data,
            );
        }

    }

    //Informes
    public function informe_impuesto($fecha_inicio, $fecha_fin)
    {
        //------------------------------------------------ almacen usuario
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = '';
        $almacen = '';
        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
        }
        //---------------------------------------------

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) <= '$fecha_fin'";
        }
        if ($is_admin == 't') { //administrador
            $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0 ";
        }

        if ($is_admin != 't') { //usuario
            $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0 and v.almacen_id = $almacen";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            foreach ($details_result as $detail) {
                $vr_pdv += $detail->precio_venta * $detail->unidades;
                $vr_pdv1 += $detail->descuento * $detail->unidades;
                $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                $vr_bruto += $vr_pdv - $vr_pdv1;
                $vr_impuesto += $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                $vr_column = $vr_pdv - $vr_pdv1;
                $vr_valor += $vr_pdv - $vr_pdv1;
            }

            $data[] = array(
                $value->fecha,
                $value->factura,
                str_replace(",", "", number_format($vr_impuesto)),
                str_replace(",", "", number_format($vr_pdv1)),
                str_replace(",", "", number_format($vr_pdv - $vr_pdv1)),
                str_replace(",", "", number_format($vr_pdv - $vr_pdv1 + $vr_impuesto)),
                $value->forma_pago,

            );
        }
        return array(
            'aaData' => $data,
        );
    }

    //Informes
    public function informe_impuesto_excel($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) <= '$fecha_fin'";
        }

        $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            foreach ($details_result as $detail) {
                $vr_pdv += $detail->precio_venta * $detail->unidades;
                $vr_pdv1 += $detail->descuento * $detail->unidades;
                $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                $vr_bruto += $vr_pdv - $vr_pdv1;
                $vr_impuesto += $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                $vr_column = $vr_pdv - $vr_pdv1;
                $vr_valor += $vr_pdv - $vr_pdv1;
            }

            $data[] = array(
                $value->fecha,
                $value->factura,
                str_replace(",", "", number_format($vr_pdv - $vr_pdv1)),
                str_replace(",", "", number_format($vr_impuesto)),
                str_replace(",", "", number_format($vr_pdv1)),
                str_replace(",", "", number_format($vr_pdv - $vr_pdv1 + $vr_impuesto)),
                $value->forma_pago,

            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function informe_vendedores($fecha_inicio, $fecha_fin, $vendedor)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = "  and v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = "  and v.fecha > '$fecha_inicio'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        } elseif ($fecha_fin != "") {
            $filtro_fecha = "  and v.fecha < '$fecha_fin'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        if ($is_admin == 't') { //administrador
            $sql = "SELECT v.*, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0 $filtro_fecha";

        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $sql = "SELECT v.*, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0 $filtro_fecha and v.almacen_id = $almacen";

        }

        $data = array();

        foreach ($this->connection->query($sql)->result() as $value) {
            /*$get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0; $vr_valor = 0;
            foreach ($details_result as $detail) {
            $vr_valor += $detail->unidades * $detail->precio_venta;
            $vr_impuesto += ($detail->unidades * $detail->precio_venta * $detail->impuesto) / 100;
            }*/
            $data[] = array(
                $value->nombre_vendedor,
                $value->nombre_almacen,
                $value->fecha,
                $value->factura,
                number_format($value->total_venta, 2),
                $value->comision,
                number_format($value->total_venta * $value->comision / 100, 2),
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function total_vendedores($fecha_inicio, $fecha_fin, $almacen)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin' and v.estado = 0 ";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = "  and v.fecha > '$fecha_inicio' ";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = "  and v.fecha < '$fecha_fin' ";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $sql = "SELECT sum(v.total_venta) as total_venta, v.vendedor, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0 $filtro_fecha and v.almacen_id = $almacen group by v.vendedor";
        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $sql = "SELECT sum(v.total_venta) as total_venta, v.vendedor, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0 $filtro_fecha and v.almacen_id = $almacen group by v.vendedor";
        }

        $total = array();

        foreach ($this->connection->query($sql)->result() as $value) {

            $total[] = array('nombre_vendedor' => $value->nombre_vendedor,
                'nombre_almacen' => $value->nombre_almacen,
                'total_venta' => number_format($value->total_venta),
                'comision' => $value->comision,
                'total_comision' => number_format($value->total_venta * $value->comision / 100),
            );
        }
        return array(
            'total_vendedor' => $total,
        );
    }

    public function informe_movimientos($fecha_inicio, $fecha_fin, $almacen)
    {
        $filtro_fecha = "";
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where v.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where v.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where v.almacen_id = $almacen";
            }

        }

        if ($is_admin != 't') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where v.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where v.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where v.almacen_id = $almacen";
            }
        }

        $sql = "SELECT v.fecha, v.codigo_factura, a.nombre, md.nombre as producto_nombre, md.cantidad, v.tipo_movimiento  FROM movimiento_inventario AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN movimiento_detalle AS md ON md.id_inventario =  v.id $filtro_fecha";

        $data = array();
        //echo $sql;
        foreach ($this->connection->query($sql)->result() as $value) {
            /*$get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0; $vr_valor = 0;
            foreach ($details_result as $detail) {
            $vr_valor += $detail->unidades * $detail->precio_venta;
            $vr_impuesto += ($detail->unidades * $detail->precio_venta * $detail->impuesto) / 100;
            }*/
            $data[] = array(
                $value->fecha,
                $value->codigo_factura,
                $value->nombre,
                $value->producto_nombre,
                $value->cantidad,
                $value->tipo_movimiento,
            );
        }
        return array(
            'aaData' => $data,
        );
    }
}

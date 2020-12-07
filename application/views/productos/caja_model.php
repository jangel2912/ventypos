<?php

class Caja_model extends CI_Model
{

    public $connection;

    // Constructor

    public function __construct()
    {

        parent::__construct();

    }

    public function initialize($connection)
    {

        $this->connection = $connection;

    }

    public function get_ajax_data()
    {

        $sql = "SELECT *  FROM cajas  ORDER BY id desc ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $sql1 = "SELECT *  FROM almacen where id = '$value->id_Almacen' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $almacen = $value1->nombre;
            }
            $data[] = array(
                $value->id,
                $value->nombre,
                $almacen,
                $value->id,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_ajax_data_listado_cierre()
    {

        $sql = "SELECT cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen, cierres.total_cierre, cierres.fecha, cierres.hora_apertura, cierres.hora_cierre, cierres.id, (SELECT nombre  FROM cajas where id = cierres.id_Caja) as nombre_caja, (SELECT nombre  FROM almacen where almacen.id = cierres.id_Almacen) as almacen, SUM(det_venta.precio_venta * det_venta.unidades) AS subtotal_factura FROM cierres_caja cierres, movimientos_cierre_caja mov_cierre, detalle_venta det_venta WHERE mov_cierre.Id_cierre = cierres.id AND mov_cierre.id_mov_tip = det_venta.venta_id GROUP BY cierres.hora_apertura ORDER BY cierres.fecha DESC;";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }
            if ($value->total_cierre == '') {
                $total_cierre = '0';
            }
            if ($value->total_cierre != '') {
                $total_cierre = number_format($value->total_cierre);
            }

            $data[] = array(
                $value->fecha,
                $value->hora_apertura,
                $value->hora_cierre,
                $username,
                $value->nombre_caja,
                $value->almacen,
                $total_cierre,
                $value->id,
                ($total_cierre . ',' . $value->id),
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_ajax_data_listado_cierre_productos($id_cierre)
    {

        $sql = "SELECT det_venta.nombre_producto, cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen, cierres.total_cierre, cierres.fecha, cierres.hora_apertura, cierres.hora_cierre, cierres.id, (SELECT COUNT(*) FROM detalle_venta WHERE venta_id = mov_cierre.id_mov_tip) as cantidad, (SELECT nombre FROM cajas where id = id_Caja) as nombre_caja, (SELECT nombre  FROM almacen where almacen.id = id_Almacen) as almacen, sum(det_venta.unidades) as cantidad_factura, sum(det_venta.precio_venta * det_venta.unidades) as subtotal_factura FROM cierres_caja cierres, movimientos_cierre_caja mov_cierre, detalle_venta det_venta WHERE mov_cierre.Id_cierre = cierres.id AND mov_cierre.id_mov_tip = det_venta.venta_id AND (cierres.total_ingresos != '' AND cierres.total_cierre != '') group by det_venta.nombre_producto order by cierres.id desc;";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }
            if ($value->total_cierre == '') {
                $total_cierre = '0';
            }
            if ($value->total_cierre != '') {
                $total_cierre = $value->subtotal_factura;
            }

            $data[] = array(
                $value->fecha,
                $value->hora_apertura,
                $value->hora_cierre,
                $username,
                $value->nombre_caja,
                $value->almacen,
                $value->nombre_producto,
                $total_cierre,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_ajax_data_cierre_productos($id_cierre, $fecha, $hora_apertura, $hora_cierre)
    {

        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id_cierre' and numero <> ''  and tabla_mov = 'venta' ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

        $sql = "SELECT
					venta.usuario_id AS usuario_id,
					producto.codigo AS producto_codigo,
					producto.nombre AS producto_nombre,
					SUM(det_venta.unidades) AS producto_cantidad,
					(SELECT nombre FROM almacen WHERE id = venta.almacen_id) AS almacen_nombre,
					(SELECT nombre FROM cajas WHERE id_Almacen = venta.almacen_id) AS caja_nombre

	           ,SUM( (det_venta.precio_venta - det_venta.descuento) * det_venta.impuesto / 100 *  det_venta.unidades ) AS impuestos
	           ,SUM( det_venta.unidades * det_venta.descuento ) AS total_descuento
			   ,SUM(det_venta.precio_venta * det_venta.unidades) AS SUBTOTAL

					FROM venta, detalle_venta det_venta, producto
				WHERE venta.id IN (" . $rest1 . ")
				AND det_venta.venta_id = venta.id
				AND det_venta.producto_id = producto.id
				GROUP BY producto.nombre
				ORDER BY producto.nombre ASC";

        //$sql = "SELECT det_venta.nombre_producto, det_venta.codigo_producto, cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen, cierres.total_cierre, cierres.fecha, cierres.hora_apertura, cierres.hora_cierre, cierres.id, (SELECT COUNT(*) FROM detalle_venta WHERE venta_id = mov_cierre.id_mov_tip) as cantidad, (SELECT nombre FROM cajas where id = id_Caja) as nombre_caja, (SELECT nombre  FROM almacen where almacen.id = id_Almacen) as almacen, sum(det_venta.unidades) as cantidad_factura, sum(det_venta.precio_venta * det_venta.unidades) as subtotal FROM venta, detalle_venta det_venta, producto WHERE venta.fecha BETWEEN '$fecha $hora_apertura' AND '$fecha $hora_cierre' AND det_venta.venta_id = venta.idAND det_venta.producto_id = producto.id group by producto.nombre_producto order by producto.nombre_producto asc;";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username FROM users WHERE id = '$value->usuario_id' ";

            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }

            $data[] = array(
                $fecha,
                $hora_apertura,
                $hora_cierre,
                $username,
                $value->caja_nombre,
                $value->almacen_nombre,
                $value->producto_codigo,
                $value->producto_cantidad,
                $value->producto_nombre,
                ($value->SUBTOTAL - $value->total_descuento) + $value->impuestos,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_ajax_data_cierre_categorias($id_cierre, $fecha, $hora_apertura, $hora_cierre)
    {

        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id_cierre' and numero <> ''  and tabla_mov = 'venta' ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

        $sql = "SELECT
						venta.usuario_id AS usuario_id,
						categoria.nombre AS categoria_nombre,
						SUM(det_venta.unidades) AS producto_cantidad,
						(SELECT nombre FROM almacen WHERE id = venta.almacen_id) AS almacen_nombre,
						(SELECT nombre FROM cajas WHERE id_Almacen = venta.almacen_id) AS caja_nombre,

	           SUM( (det_venta.precio_venta - det_venta.descuento) * det_venta.impuesto / 100 *  det_venta.unidades ) AS impuestos
	           ,SUM( det_venta.unidades * det_venta.descuento ) AS total_descuento
			   ,SUM(det_venta.precio_venta * det_venta.unidades) AS SUBTOTAL

						FROM venta, detalle_venta det_venta, producto, categoria
					WHERE venta.id IN (" . $rest1 . ")
					AND det_venta.venta_id = venta.id
					AND det_venta.producto_id = producto.id
					AND producto.categoria_id = categoria.id
					GROUP BY categoria.nombre
					ORDER BY categoria.nombre ASC";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->usuario_id' ";

            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }

            $data[] = array(
                $fecha,
                $hora_apertura,
                $hora_cierre,
                $username,
                $value->caja_nombre,
                $value->almacen_nombre,
                $value->producto_cantidad,
                $value->categoria_nombre,
                ($value->SUBTOTAL - $value->total_descuento) + $value->impuestos,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_listado_cierre_productos($id)
    {

        $sql = "SELECT *  FROM cierres_caja  where id = '$id' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }
            $sql1 = "SELECT nombre  FROM cajas where id = '$value->id_Caja' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $nombre_caja = $value1->nombre;
            }
            $sql1 = "SELECT *  FROM almacen where id = '$value->id_Almacen' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $almacen = $value1->nombre;
            }
            if ($value->total_cierre == '') {
                $total_cierre = '0';
            }
            if ($value->total_cierre != '') {
                $total_cierre = number_format($value->total_cierre);
            }
            if ($value->total_egresos == '') {
                $total_egresos = '0';
            }
            if ($value->total_egresos != '') {
                $total_egresos = number_format($value->total_egresos);
            }
            if ($value->total_ingresos == '') {
                $total_ingresos = '0';
            }
            if ($value->total_ingresos != '') {
                $total_ingresos = number_format($value->total_ingresos);
            }

            $data[] = array(
                'fecha' => $value->fecha,
                'hora_apertura' => $value->hora_apertura,
                'hora_cierre' => $value->hora_cierre,
                'username' => $username,
                'nombre_caja' => $nombre_caja,
                'almacen' => $almacen,
                'total_egresos' => $total_egresos,
                'total_ingresos' => $total_ingresos,
                'total_cierre' => $total_cierre,
                'id' => $value->id,
            );
        }
        return $data;
    }

    public function get_listado_cierre($id)
    {

        $sql = "SELECT *  FROM cierres_caja  where id = '$id' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }
            $sql1 = "SELECT nombre  FROM cajas where id = '$value->id_Caja' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $nombre_caja = $value1->nombre;
            }
            $sql1 = "SELECT *  FROM almacen where id = '$value->id_Almacen' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $almacen = $value1->nombre;
            }
            if ($value->total_cierre == '') {
                $total_cierre = '0';
            }
            if ($value->total_cierre != '') {
                $total_cierre = number_format($value->total_cierre);
            }
            if ($value->total_egresos == '') {
                $total_egresos = '0';
            }
            if ($value->total_egresos != '') {
                $total_egresos = number_format($value->total_egresos);
            }
            if ($value->total_ingresos == '') {
                $total_ingresos = '0';
            }
            if ($value->total_ingresos != '') {
                $total_ingresos = number_format($value->total_ingresos);
            }

            $data[] = array(
                'fecha' => $value->fecha,
                'hora_apertura' => $value->hora_apertura,
                'hora_cierre' => $value->hora_cierre,
                'username' => $username,
                'nombre_caja' => $nombre_caja,
                'almacen' => $almacen,
                'total_egresos' => $total_egresos,
                'total_ingresos' => $total_ingresos,
                'total_cierre' => $total_cierre,
                'id' => $value->id,
            );
        }
        return $data;
    }

    public function get_movimientos_cierre_entradas_ventas($id)
    {
        $cambio = '';

        $sql = "SELECT sum(valor) total_ingresos, count(forma_pago) as cantidad_ingresos, forma_pago FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and tipo_movimiento <> 'salida_gastos' and tipo_movimiento <> 'entrada_apertura'  group by  forma_pago  order by forma_pago asc  ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $formpago1 = str_replace("_", " ", $value1->forma_pago);
            $formpago1 = ucfirst($formpago1);
            $data[] = array(
                'total_ingresos' => $value1->total_ingresos,
                'cantidad_ingresos' => $value1->cantidad_ingresos,
                'forma_pago' => $formpago1,
            );

        }

        return $data;

    }

    public function get_movimientos_cierre_entradas_apertura($id)
    {
        $cambio = '';

        $sql = "SELECT sum(valor) total_ingresos FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and  tipo_movimiento = 'entrada_apertura'  group by  forma_pago  order by forma_pago asc  ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $formpago1 = str_replace("_", " ", $value1->forma_pago);
            $formpago1 = ucfirst($formpago1);
            $data[] = array(
                'total_ingresos' => $value1->total_ingresos,
                'cantidad_ingresos' => $value1->cantidad_ingresos,
                'forma_pago' => $formpago1,
            );

        }

        return $data;

    }

    public function get_movimientos_all($id)
    {
        $cambio = '';
        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and numero <> ''  and tipo_movimiento = 'entrada_venta' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $sql1 = "SELECT username  FROM users where id = '$value1->id_usuario' ";
            foreach ($this->db->query($sql1)->result() as $value2) {
                $username = $value2->username;
            }

            $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	  ,SUM( `descripcion_producto` ) AS sobrecosto
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id = '$value1->id_mov_tip'";
            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {
                $impuesto = round($dat1->impuesto);
                $total_descuento = round($dat1->total_descuento);

            }

            $formpago1 = str_replace("_", " ", $value1->forma_pago);
            $formpago1 = ucfirst($formpago1);
            $data[] = array(
                'numero' => $value1->numero,
                'hora_movimiento' => $value1->hora_movimiento,
                'valor' => $value1->valor,
                'username' => $username,
                'impuesto' => $impuesto,
                'total_descuento' => $total_descuento,
                'forma_pago' => $formpago1,
            );

        }
        return $data;

    }

    public function get_movimientos_impuestos($id)
    {
        $cambio = '';
        $detalleventaid = 0;
        $impuesto = 0;
        $total_descuento = 0;
        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and numero <> ''  and tipo_movimiento = 'entrada_venta' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

        $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia
	 ,  (SELECT nombre_impuesto FROM `impuesto` where porciento = impuesto ) as imp
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id  IN (" . $rest1 . ")  group by impuesto";
        $total_ventas_result = $this->connection->query($total_ventas)->result();
        foreach ($total_ventas_result as $dat1) {
            $data[] = array(
                'impuesto' => $dat1->imp,
                'total_precio_venta' => ($dat1->total_precio_venta - $dat1->total_descuento) + $dat1->impuestos,
            );
        }

        return $data;

    }

    public function get_movimientos_cierre_salidas($id)
    {
        $cambio = '';

        $sql2 = "SELECT sum(valor) total_ingresos, count(forma_pago) as cantidad_ingresos, forma_pago   FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id'  and tipo_movimiento = 'salida_gastos' ";
        $data = array();
        $num_rows = $this->connection->query($sql2)->num_rows();
        if ($num_rows > '0') {$cambio = 'si';}

        $sql3 = "SELECT sum(valor) total_ingresos, count(forma_pago) as cantidad_ingresos, forma_pago   FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id'  and tipo_movimiento = 'salida_gastos' group by  forma_pago  order by forma_pago asc ";
        $data = array();
        foreach ($this->connection->query($sql3)->result() as $value3) {

            $formpago3 = str_replace("_", " ", $value3->forma_pago);
            $formpago3 = ucfirst($formpago3);

            $data[] = array(
                'total_ingresos' => $value3->total_ingresos,
                'cantidad_ingresos' => $value3->cantidad_ingresos,
                'forma_pago' => $formpago3,
            );

        }

        return $data;

    }

    public function get_movimientos_cierre_salidas_si_no($id)
    {
        $cambio = '';

        $sql2 = "SELECT sum(valor) total_ingresos, count(forma_pago) as cantidad_ingresos, forma_pago   FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id'  and tipo_movimiento = 'salida_gastos'  group by forma_pago ";
        $data = array();
        $num_rows = $this->connection->query($sql2)->num_rows();
        if ($num_rows > '0') {$cambio = 'si';}

        return $cambio;

    }

    public function get_ajax_data_movimientos_cierre($id)
    {
        $nombre_caja = '';
        $sql = "SELECT *  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id'  ORDER BY id asc ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_usuario' ";
            foreach ($this->db->query($sql1)->result() as $value1) {
                $username = $value1->username;
            }

            $sql1 = "SELECT fecha, id_Caja  FROM cierres_caja where id = '$value->Id_cierre' ";
            foreach ($this->connection->query($sql1)->result() as $value1) {

                $fecha = $value1->fecha;

                $sql2 = "SELECT  nombre  FROM cajas  where id = '$value1->id_Caja' ";
                foreach ($this->connection->query($sql2)->result() as $value2) {
                    $nombre_caja = $value2->nombre;
                }

            }

            $formpago = str_replace("_", " ", $value->forma_pago);
            $formpago = ucfirst($formpago);

            $tipo_movimiento = str_replace("_", " ", $value->tipo_movimiento);
            $tipo_movimiento = ucfirst($tipo_movimiento);

            $data[] = array(
                $fecha,
                $nombre_caja,
                $value->hora_movimiento,
                $tipo_movimiento,
                $username,
                number_format($value->valor),
                $formpago,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function add($data)
    {

        $this->connection->insert("cajas", $data);

    }

    public function editar($data, $id)
    {

        $this->connection->where('id', $id);
        $this->connection->update("cajas", $data);

    }

    public function apertura_cierre_caja($data)
    {

        $this->connection->insert("cierres_caja", $data);
        $id = $this->connection->insert_id();
        $this->session->set_userdata('caja', $id);
        return $id;
    }

    public function movimiento_cierre_caja($data)
    {

        $this->connection->insert("movimientos_cierre_caja", $data);
        return $id = $this->connection->insert_id();
    }

    public function get_by_id($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM  cajas WHERE id = '" . $id . "'");
        return $query->row_array();
    }

    public function get_all($offset)
    {
        $query = $this->connection->query("SELECT * FROM cajas");
        return $query->result();
    }

    public function get_facturas_ultpri($id = 0)
    {

        $mayor = "SELECT numero FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' order by id asc limit 1";
        $mayor_numero = $this->connection->query($mayor)->row();
        $mayor_numero = $mayor_numero->numero;

        $menor = "SELECT numero FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' order by id desc limit 1";
        $menor_numero = $this->connection->query($menor)->row();
        $menor_numero = $menor_numero->numero;

        $total = "SELECT count(numero) as total FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' order by id desc limit 1";
        $total_numero = $this->connection->query($total)->row();
        $total_numero = $total_numero->total;

        return "Desde <b>" . $mayor_numero . "</b> - Hasta <b>" . $menor_numero . "</b>  &nbsp;&nbsp;&nbsp; <b>Total</b> " . $total_numero . " ";
    }

    public function almacen_caja($id)
    {
        $query = $this->connection->query("SELECT * FROM cajas where id_Almacen = '$id' ");
        return $query->result();
    }

    public function base_iva($id, $tipo)
    {
        $ventaid = 0;
        $detalleventaid = 0;
        $base = 0;
        $iva = 0;
        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and numero <> ''  and tipo_movimiento = 'entrada_venta' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {$rest1 = 0;} else { $rest1 = substr($detalleventaid, 2);}

        $total_ventas1 = "SELECT id  FROM venta inner join ventas_pago on venta.id = ventas_pago.id_venta where venta.id  IN (" . $rest1 . ") and (forma_pago <> 'Saldo_a_Favor') ";
        foreach ($this->connection->query($total_ventas1)->result() as $value1) {
            $ventaid = $ventaid . ",'" . $value1->id . "'";
        }

        $rest2 = substr($detalleventaid, 2);
        if ($rest2 == '') {$rest1 = 0;} else { $rest2 = substr($detalleventaid, 2);}

        $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia
	 ,(SELECT nombre_impuesto FROM `impuesto` where porciento = impuesto ) as imp
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	 ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id  IN (" . $rest2 . ")  group by impuesto";
        $total_ventas_result = $this->connection->query($total_ventas)->result();
        foreach ($total_ventas_result as $dat1) {

            $base += $dat1->total_precio_venta - $dat1->total_descuento;
            $iva += $dat1->impuestos;

        }

        if ('base' == $tipo) {return $base;}
        if ('iva' == $tipo) {return $iva;}
    }

    public function cierre_caja($id = null)
    {

        if ($this->session->userdata('caja') > 0) {
            $id = $this->session->userdata('caja');
        }
        $caja_result_1 = array();

        $query = "SELECT fecha, id_Caja, id_Almacen FROM cierres_caja where id = '$id' ";
        $caja_result_1 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total, forma_pago FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and tipo_movimiento <> 'salida_gastos' group by  forma_pago order by total desc ";
        $caja_result_2 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total, forma_pago FROM movimientos_cierre_caja where Id_cierre = '$id' and tipo_movimiento = 'entrada_apertura'  group by forma_pago order by total desc ";
        $caja_result_3 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total, forma_pago FROM movimientos_cierre_caja where  tipo_movimiento = 'salida_gastos' and Id_cierre = '$id' group by forma_pago order by total desc ";
        $caja_result_4 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago' and Id_cierre = '$id' and forma_pago like '%efectivo%'  ";
        $pago_recibidos = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago_orden_compra' and Id_cierre = '$id' and forma_pago like '%efectivo%'   ";
        $pago_proveedores = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'proformas' and Id_cierre = '$id'  and forma_pago like '%efectivo%'  ";
        $pago_gastos = $this->connection->query($query)->result();

        $query = "SELECT * FROM movimiento_inventario";
        $movimiento_inventario = $this->connection->query($query)->result();

        return array(
            'caja_result_1' => $caja_result_1,
            'caja_result_2' => $caja_result_2,
            'caja_result_3' => $caja_result_3,
            'caja_result_4' => $caja_result_4,
            'pago_recibidos' => $pago_recibidos,
            'pago_proveedores' => $pago_proveedores,
            'pago_gastos' => $pago_gastos,
            'movimiento_inventario' => $movimiento_inventario,
        );
    }

    public function cerrar_caja_final($data)
    {
        $id = $this->session->userdata('caja');

        $this->connection->where('id', $id);
        $this->connection->update("cierres_caja", $data);

    }

}

<?php

class Caja_model extends CI_Model
{

    public $connection;

    // Constructor

    public function __construct()
    {

        parent::__construct();

        $this->load->model("opciones_model", 'opciones');
        //$this->opciones->initialize($this->connection);
    }

    public function initialize($connection)
    {

        $this->connection = $connection;
    }

    // Funcion que añade una columna a la tabla cierres_caja en el que se colocará la fecha final del cierre de caja
    public function addFechaCiierre()
    {
        // Si no existe la columna, la creamos
        if (!$this->connection->field_exists('fecha_cierre', 'cierres_caja')) {
            $sql = " ALTER TABLE cierres_caja ADD fecha_cierre DATE; ";
            $this->connection->query($sql);
        }
    }

    public function get_ajax_data()
    {

        $sql = "SELECT *  FROM cajas  ORDER BY id desc ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $sql1 = "SELECT *  FROM almacen where id = '$value->id_Almacen' ";
            $almacen = '';
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

        /* $sql = "
        SELECT cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen, cierres.total_cierre, cierres.fecha, cierres.hora_apertura,
        cierres.hora_cierre, cierres.id, (SELECT nombre  FROM cajas WHERE id = cierres.id_Caja) AS nombre_caja,
        (SELECT nombre  FROM almacen WHERE almacen.id = cierres.id_Almacen) AS almacen
        FROM cierres_caja cierres
        ORDER BY cierres.fecha DESC";
         */

        /*

        El total de cierre de caja está conformado de la siguiente manera

        ( ventas + creditos ) + ( plan separe ) - ( proformas ) - ( orden de compra )

         *//*
        $sql = "
        SELECT cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen,
        IFNULL((
        SELECT SUM(valor) total_cierre
        FROM movimientos_cierre_caja
        WHERE valor > 0
        AND Id_cierre = cierres.id
        AND tipo_movimiento <> 'salida_gastos'
        AND forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
        AND movimientos_cierre_caja.tabla_mov <> 'anulada'
        AND movimientos_cierre_caja.tabla_mov IN ('pago','venta')
        ),0)+
        IFNULL((
        SELECT
        SUM(mcc.valor) AS total
        FROM movimientos_cierre_caja AS mcc
        INNER JOIN plan_separe_pagos AS sp ON mcc.id_mov_tip = sp.id_pago
        WHERE mcc.valor > 0
        AND mcc.Id_cierre = cierres.id
        AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
        AND mcc.tabla_mov = 'plan_separe_pagos'
        ),0)-
        IFNULL((
        SELECT
        SUM(mcc.valor) AS total
        FROM movimientos_cierre_caja AS mcc
        INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
        LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
        WHERE mcc.tabla_mov = 'proformas'
        AND mcc.Id_cierre = cierres.id
        AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
        AND pf.id_almacen = cierres.id_Almacen
        AND cd.tipo_cuenta IN ('Caja menor','Caja registradora')
        ),0)-
        IFNULL((
        SELECT
        SUM(mcc.valor) AS total
        FROM movimientos_cierre_caja AS mcc
        WHERE tipo_movimiento = 'salida_gastos'
        AND tabla_mov = 'pago_orden_compra'
        AND forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
        AND mcc.Id_cierre = cierres.id
        ),0)
        AS total_cierre,
        cierres.fecha,
        cierres.hora_apertura,
        cierres.hora_cierre,
        cierres.id,
        (SELECT nombre  FROM cajas WHERE id = cierres.id_Caja) AS nombre_caja,
        (SELECT nombre  FROM almacen WHERE almacen.id = cierres.id_Almacen) AS almacen
        FROM cierres_caja cierres
        ORDER BY cierres.fecha DESC
        "; */

        //se modificó la consulta para que la tabla sea carga progresiva
        $aColumns = array(
            'cierres.id',
            'cierres.fecha',
            'cierres.fecha_fin_cierre',
            'cierres.hora_apertura',
            'cierres.hora_cierre',
            'cierres.id_Usuario',
            'ca.nombre',
            'al.nombre',
            'cierres.total_cierre',
            'cierres.arqueo',
            'cierres.consecutivo',
        );

        $where = "";
        $is_admin = $this->session->userdata('is_admin');
        $user_id = $this->session->userdata('user_id');

        if ($is_admin != 't' && $is_admin != 'a') {
            $where = " WHERE cierres.id_Usuario=" . $user_id;
        }

        //limit
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }
        //orden
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        //buscar
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            if (empty($where)) {
                $sWhere .= "where (";
            } else {
                $sWhere .= "AND (";
            }

            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS
            cierres.id_Usuario,
            cierres.id_Caja,
            cierres.id_Almacen,
            cierres.total_cierre,
            cierres.fecha,
            cierres.fecha_cierre,
            cierres.fecha_fin_cierre,
            cierres.hora_apertura,
            cierres.hora_cierre,
            cierres.id,
            cierres.arqueo,
            cierres.consecutivo,
            ca.nombre AS nombre_caja,
            al.nombre AS almacen
            FROM cierres_caja cierres
            LEFT JOIN cajas ca ON cierres.id_Caja=ca.id
            INNER JOIN almacen al ON cierres.id_Almacen=al.id
            $where
            $sWhere
            $sOrder
            $sLimit";
        //echo $sql; die();
        $rResult = $this->connection->query($sql);
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(id) as cantidad FROM cierres_caja";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            //"iTotalDisplayRecords" => 142,
            "aaData" => array(),
        );
        //echo $sql; die();
        $data = array();
        foreach ($rResult->result() as $value) {

            $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
            $rsql = $this->db->query($sql1)->result();
            if (!empty($rsql)) {
                foreach ($rsql as $value1) {
                    $username = $value1->username;
                }
            } else {
                $username = "No existe el usuario";
            }

            if ($value->total_cierre == '') {
                $total_cierre = '0';
            }
            if ($value->total_cierre != '') {
                $total_cierre = ($value->total_cierre);
            }

            //busco fechas y horas del cierre
            $cierre_automatico = get_option("cierre_automatico");

            if ($cierre_automatico == 0) { //no tengo cierre automatico
                if (!empty($value->fecha_fin_cierre)) {
                    $fecha_cierre = $value->fecha_fin_cierre;
                    $fecha_cierre = date("Y-m-d", strtotime($fecha_cierre));
                    $hora_cierre = $value->hora_cierre;
                } else {
                    if (!empty($value->fecha_cierre)) {
                        $fecha_cierre = $value->fecha_cierre;
                        $hora_cierre = $value->hora_cierre;
                    } else {
                        $fecha_cierre = date("Y-m-d");
                        $hora_cierre = date("H:i:s");
                    }
                }
            } else {
                $fecha_cierre = $value->fecha;
                $hora_cierre = date("23:59:59");
            }
/*
$hora_cierre=$value->hora_cierre;
if($value->hora_cierre=="00:00:00"){
if($cierre_automatico==0){ //no tengo cierre automatico
$fecha_cierre=date("Y-m-d");
$hora_cierre= date("H:i:s");
}else{
$fecha_cierre=$value->fecha;
$hora_cierre= date("23:59:59");
}

}else{
$fecha_cierre=$value->fecha;
}

//$fecha_cierre=$value->fecha;

if(!empty($value->fecha_fin_cierre)){
$fin = $value->fecha_fin_cierre;
$fecha_cierre = date("Y-m-d", strtotime($fin));
}*/

            $consecutivo = (!empty($value->consecutivo)) ? $value->consecutivo : $value->id;
            $data[] = array(
                $value->id,
                $value->fecha,
                $fecha_cierre,
                $value->hora_apertura,
                $value->hora_cierre,
                $username,
                $value->nombre_caja,
                $value->almacen,
                $this->opciones_model->formatoMonedaMostrar($total_cierre),
                $this->opciones_model->formatoMonedaMostrar($value->arqueo),
                $consecutivo,
                $value->id,
                (number_format($total_cierre) . ',' . $value->id),
            );
        }
        $output['aaData'] = $data;
        return $output;
        /*
    return array(
    'aaData' => $data
    );*/
    }

    public function get_last_closed_box($almacen) {
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS
            cierres.id_Usuario,
            cierres.id_Caja,
            cierres.id_Almacen,
            cierres.total_cierre,
            cierres.fecha,
            cierres.fecha_cierre,
            cierres.fecha_fin_cierre,
            cierres.hora_apertura,
            cierres.hora_cierre,
            cierres.id,
            cierres.arqueo,
            cierres.consecutivo,
            ca.nombre AS nombre_caja,
            al.nombre AS almacen
            FROM cierres_caja cierres
            LEFT JOIN cajas ca ON cierres.id_Caja=ca.id
            INNER JOIN almacen al ON cierres.id_Almacen=al.id where cierres.id_Almacen = '".$almacen."' AND cierres.`fecha_cierre` != ''  ORDER BY cierres.fecha_cierre DESC LIMIT 4";

            $rResult = $this->connection->query($sql);

            //print_r($rResult->result()); die;
            foreach ($rResult->result() as $value) {

                $sql1 = "SELECT username  FROM users where id = '$value->id_Usuario' ";
                $rsql = $this->db->query($sql1)->result();
                if (!empty($rsql)) {
                    foreach ($rsql as $value1) {
                        $username = $value1->username;
                    }
                } else {
                    $username = "No existe el usuario";
                }
    
                if ($value->total_cierre == '') {
                    $total_cierre = '0';
                }
                if ($value->total_cierre != '') {
                    $total_cierre = ($value->total_cierre);
                }
    
                //busco fechas y horas del cierre
                $cierre_automatico = get_option("cierre_automatico");
    
                if ($cierre_automatico == 0) { //no tengo cierre automatico
                    if (!empty($value->fecha_fin_cierre)) {
                        $fecha_cierre = $value->fecha_fin_cierre;
                        $fecha_cierre = date("Y-m-d", strtotime($fecha_cierre));
                        $hora_cierre = $value->hora_cierre;
                    } else {
                        if (!empty($value->fecha_cierre)) {
                            $fecha_cierre = $value->fecha_cierre;
                            $hora_cierre = $value->hora_cierre;
                        } else {
                            $fecha_cierre = date("Y-m-d");
                            $hora_cierre = date("H:i:s");
                        }
                    }
                } else {
                    $fecha_cierre = $value->fecha;
                    $hora_cierre = date("23:59:59");
                }

                $consecutivo = (!empty($value->consecutivo)) ? $value->consecutivo : $value->id;
                $data[] = array(
                    'id' => $value->id,
                    'fecha' => $value->fecha,
                    'fecha_cierre' => $fecha_cierre,
                    'hora_apertura' => $value->hora_apertura,
                    'hora_cierre' => $value->hora_cierre,
                    'usuario' => $username,
                    'nombre_caja' => $value->nombre_caja,
                    'almacen' => $value->almacen,
                    'total_cierre' => $this->opciones_model->formatoMonedaMostrar($total_cierre),
                    'total' => $value->total_cierre,
                    'arqueo' => $this->opciones_model->formatoMonedaMostrar($value->arqueo),
                    'cocecutivo' => $consecutivo,
                );
            }

            return $data;


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

    public function get_ajax_data_cierre_productos($id_cierre, $fecha_inicio, $fecha_fin, $hora_apertura, $hora_cierre)
    {

        //decimales? decimales_moneda
        $decimales_moneda = "";
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda'";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $decimales_moneda = $dat->valor_opcion;
        }

        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where Id_cierre = '$id_cierre' and numero <> ''  and tabla_mov = 'venta' ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }
        //echo $rest1; die();
        /*
        $sql = "SELECT
        venta.usuario_id AS usuario_id,
        producto.codigo AS producto_codigo,
        producto.nombre AS producto_nombre,
        SUM(det_venta.unidades) AS producto_cantidad,
        (SELECT nombre FROM almacen WHERE id = venta.almacen_id limit 1) AS almacen_nombre,
        (SELECT nombre FROM cajas WHERE id_Almacen = venta.almacen_id limit 1) AS caja_nombre

        ,SUM( (det_venta.precio_venta - det_venta.descuento) * det_venta.impuesto / 100 *  det_venta.unidades ) AS impuestos
        ,SUM( det_venta.unidades * det_venta.descuento ) AS total_descuento
        ,SUM(IF((det_venta.descripcion_producto = '0' OR det_venta.descripcion_producto = ''),0,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(det_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))=0,det_venta.unidades,(SELECT det_venta.unidades - (SUBSTRING_INDEX(SUBSTRING_INDEX(det_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))))) AS cantidades_devueltas
        ,SUM(IF((det_venta.descripcion_producto = '0' OR det_venta.descripcion_producto = ''), det_venta.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(det_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * det_venta.descuento) AS total_descuento_sin_devoluciones
        ,SUM(det_venta.precio_venta * det_venta.unidades) AS SUBTOTAL
        ,SUM(det_venta.precio_venta * (IF((det_venta.descripcion_producto = '0' OR det_venta.descripcion_producto = ''),det_venta.unidades,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(det_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))=0,0,(SELECT (SUBSTRING_INDEX(SUBSTRING_INDEX(det_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))))))) AS SUBTOTAL_sin_devoluciones

        FROM venta, detalle_venta det_venta, producto
        WHERE venta.id IN (" . $rest1 . ")
        AND det_venta.venta_id = venta.id
        AND det_venta.producto_id = producto.id
        GROUP BY producto.nombre
        ORDER BY producto.nombre ASC";*/

        //se realiza un nuevo sql para que se realicen los cálculos en php
        $sql = "SELECT v.usuario_id AS usuario_id, p.codigo AS producto_codigo,  p.nombre AS producto_nombre,
                    (dv.unidades) AS producto_cantidad, dv.precio_venta, dv.descuento, dv.impuesto, dv.descripcion_producto,
                    (SELECT nombre FROM almacen WHERE id = v.almacen_id LIMIT 1) AS almacen_nombre,
                    (SELECT nombre FROM cajas WHERE id_Almacen = v.almacen_id LIMIT 1) AS caja_nombre
                    FROM venta v
                    INNER JOIN detalle_venta dv ON dv.venta_id = v.id
                    INNER JOIN producto p ON dv.producto_id = p.id
                    WHERE v.id IN (" . $rest1 . ")
                    ORDER BY p.nombre ASC, p.codigo ASC";

        //echo"<br>".$sql; die();

        //$sql = "SELECT det_venta.nombre_producto, det_venta.codigo_producto, cierres.id_Usuario, cierres.id_Caja, cierres.id_Almacen, cierres.total_cierre, cierres.fecha, cierres.hora_apertura, cierres.hora_cierre, cierres.id, (SELECT COUNT(*) FROM detalle_venta WHERE venta_id = mov_cierre.id_mov_tip) as cantidad, (SELECT nombre FROM cajas where id = id_Caja) as nombre_caja, (SELECT nombre  FROM almacen where almacen.id = id_Almacen) as almacen, sum(det_venta.unidades) as cantidad_factura, sum(det_venta.precio_venta * det_venta.unidades) as subtotal FROM venta, detalle_venta det_venta, producto WHERE venta.fecha BETWEEN '$fecha $hora_apertura' AND '$fecha $hora_cierre' AND det_venta.venta_id = venta.idAND det_venta.producto_id = producto.id group by producto.nombre_producto order by producto.nombre_producto asc;";
        $data = array();
        $cantidad_totales = 0;
        $cantidad_devueltas = 0;
        $subtotal = 0;
        $descuento = 0;
        $impuestos = 0;
        $total = 0;
        $codigo = "";
        $i = 0;
        foreach ($this->connection->query($sql)->result() as $value) {

            if ($codigo != $value->producto_codigo) {
                $i = 0;
                $cantidad_totales = 0;
                $cantidad_devueltas = 0;
                $subtotal = 0;
                $descuento = 0;
                $impuestos = 0;
            }

            if (($i == 0)) {
                $codigo = $value->producto_codigo;
                $i = 1;
            }

            if ($codigo == $value->producto_codigo) {
                //cantidad_totales
                $cantidad_totales += floatval($value->producto_cantidad);

                //SUM( (det_venta.precio_venta - det_venta.descuento) * det_venta.impuesto / 100 * det_venta.unidades ) AS impuestos ,

                //cantidades devueltas
                if (($value->descripcion_producto == '0') || (empty($value->descripcion_producto))) {
                    if ($decimales_moneda == 0) {
                        $subtotal += round($value->precio_venta * $value->producto_cantidad);
                        $descuento += round($value->producto_cantidad * $value->descuento);
                        $impuestos += round(($value->precio_venta - $value->descuento) * $value->impuesto / 100 * $value->producto_cantidad);
                    } else {
                        $subtotal += ($value->precio_venta * $value->producto_cantidad);
                        $descuento += ($value->producto_cantidad * $value->descuento);
                        $impuestos += (($value->precio_venta - $value->descuento) * $value->impuesto / 100 * $value->producto_cantidad);
                    }

                } else {
                    $descripcion = explode('cantidadSindevolver":', $value->descripcion_producto);
                    $descripcion2 = explode(',', $descripcion[1]);

                    if ($descripcion2[0] == 0) { //se devolvieron todas
                        $cantidad_devueltas += floatval($value->producto_cantidad);
                        //$subtotal+=0;
                    } else { //se devolvieron algunas
                        if ($decimales_moneda == 0) {
                            $cantidad_devueltas += floatval($value->producto_cantidad) - floatval($descripcion2[0]);
                            $subtotal += round($value->precio_venta * floatval($descripcion2[0]));
                            $descuento += round(floatval($descripcion2[0]) * $value->descuento);
                            $impuestos += round(($value->precio_venta - $value->descuento) * $value->impuesto / 100 * $cantidad_devueltas);
                        } else {
                            $cantidad_devueltas += floatval($value->producto_cantidad) - floatval($descripcion2[0]);
                            $subtotal += ($value->precio_venta * floatval($descripcion2[0]));
                            $descuento += (floatval($descripcion2[0]) * $value->descuento);
                            $impuestos += (($value->precio_venta - $value->descuento) * $value->impuesto / 100 * $cantidad_devueltas);
                        }
                    }
                }
                /*
                echo"<br>subtotal=".$subtotal;
                echo"<br>descuento=".$descuento;
                echo"<br>impuestos=".$impuestos;*/

                $sql1 = "SELECT username FROM users WHERE id = '$value->usuario_id' ";
                foreach ($this->db->query($sql1)->result() as $value1) {
                    $username = $value1->username;
                }
                if ($decimales_moneda == 0) {
                    $total = round($subtotal - $descuento) + $impuestos;
                } else {
                    $total = ($subtotal - $descuento) + $impuestos;
                }
                $data[$codigo] = array(
                    $fecha_inicio,
                    $fecha_fin,
                    $hora_apertura,
                    $hora_cierre,
                    $username,
                    $value->caja_nombre,
                    $value->almacen_nombre,
                    $value->producto_codigo,
                    $cantidad_totales - $cantidad_devueltas,
                    $value->producto_nombre,
                    $total,
                );
            }

        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_cierre_categorias($id_cierre, $fecha, $hora_apertura, $hora_cierre)
    {

        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where Id_cierre = '$id_cierre' and numero <> ''  and tabla_mov = 'venta' ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        $sql = "SELECT
						venta.usuario_id AS usuario_id,
						categoria.nombre AS categoria_nombre,
						SUM(det_venta.unidades) AS producto_cantidad,
						(SELECT nombre FROM almacen WHERE id = venta.almacen_id limit 1) AS almacen_nombre,
						(SELECT nombre FROM cajas WHERE id_Almacen = venta.almacen_id limit 1) AS caja_nombre,

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
                'fecha_fin_cierre' => $value->fecha_fin_cierre,
                'hora_apertura' => $value->hora_apertura,
                'hora_cierre' => $value->hora_cierre,
                'username' => $username,
                'nombre_caja' => $nombre_caja,
                'almacen' => $almacen,
                'total_egresos' => $total_egresos,
                'total_ingresos' => $total_ingresos,
                'total_cierre' => $total_cierre,
                'id' => $value->id,
                'id_Almacen' => $value->id_Almacen,
            );
        }
        return $data;
    }

    public function get_listado_cierre($id)
    {

        $sql = "SELECT *  FROM cierres_caja  where id = '$id' ";
        $data = array();
        $nombre_caja = '';
        $username = '';
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
                'fecha_fin_cierre' => $value->fecha_fin_cierre,
                'hora_apertura' => $value->hora_apertura,
                'hora_cierre' => $value->hora_cierre,
                'username' => $username,
                'nombre_caja' => $nombre_caja,
                'almacen' => $almacen,
                'total_egresos' => $total_egresos,
                'total_ingresos' => $total_ingresos,
                'total_cierre' => $total_cierre,
                'id' => $value->id,
                'id_Almacen' => $value->id_Almacen,
                'arqueo' => $value->arqueo,
                'consecutivo' => $value->consecutivo,
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

        $sql = "SELECT sum(valor) total_ingresos, forma_pago FROM movimientos_cierre_caja WHERE valor > 0 AND Id_cierre = '$id' AND  tipo_movimiento = 'entrada_apertura'  GROUP BY  forma_pago  ORDER BY forma_pago ASC";
        $data = array();
        $result = $this->connection->query($sql)->result();
        if (count($result) > 0) {
            foreach ($result as $value1) {
                //var_dump($value1);
                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);
                $data[] = array(
                    'total_ingresos' => $value1->total_ingresos,
                    //'cantidad_ingresos' => $value1->cantidad_ingresos,
                    'forma_pago' => $formpago1,
                );
            }
        }

        return $data;
    }

    public function get_movimientos_all($id)
    {
        $cambio = '';
        //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.numero = v.factura LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta', 'anulada') order by numero";
        $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta', 'anulada') order by numero";
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
	 INNER JOIN detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id = '$value1->id_mov_tip'";

            $total_si_cero = 0;
            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {
                $impuesto = round($dat1->impuesto);
                $total_descuento = round($dat1->total_descuento);
                $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
            }

            $formpago1 = str_replace("_", " ", $value1->forma_pago);
            $formpago1 = ucfirst($formpago1);
            $data[] = array(
                'numero' => $value1->numero,
                'hora_movimiento' => $value1->hora_movimiento,
                'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
                'username' => $username,
                'impuesto' => $impuesto,
                'total_descuento' => $total_descuento,
                'forma_pago' => $formpago1,
                'anulada' => $value1->anulada,
            );
        }
        return $data;
    }

    public function get_movimientos_plan_separe($id)
    {
        $cambio = '';
        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where Id_cierre = '$id' and tabla_mov = 'plan_separe_pagos' ";
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
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

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
        if ($num_rows > '0') {
            $cambio = 'si';
        }

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
        if ($num_rows > '0') {
            $cambio = 'si';
        }

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
                $this->opciones_model->formatoMonedaMostrar($value->valor),
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
        if (!empty($id)) {
            $this->session->set_userdata('caja', $id);
        }
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

        $mayor = "SELECT numero FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' AND tipo_movimiento = 'entrada_venta' order by id asc limit 1";
        $mayor_numero = $this->connection->query($mayor)->row();
        if (count($mayor_numero) > 0) {
            $mayor_numero = "Desde: <b>" . $mayor_numero->numero . "</b>";
        } else {
            $mayor_numero = "";
        }

        $menor = "SELECT numero FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' AND tipo_movimiento = 'entrada_venta' order by id desc limit 1";
        $menor_numero = $this->connection->query($menor)->row();
        if (count($menor_numero) > 0) {
            $menor_numero = "Hasta: <b>" . $menor_numero->numero . "</b>";
        } else {
            $menor_numero = "";
        }

        $total = "SELECT DISTINCT numero AS total FROM movimientos_cierre_caja WHERE Id_cierre = '" . $id . "' and numero <> '' AND tipo_movimiento = 'entrada_venta' order by id desc";

        $total_numero = $this->connection->query($total)->num_rows();
        $total_numero = "Total: <b>" . $total_numero . "</b>";

        return $mayor_numero . ' &nbsp;-&nbsp; ' . $menor_numero . ' &nbsp;-&nbsp;  ' . $total_numero;
    }

    public function cant_almacen_caja($id_almacen)
    {
        $query = $this->connection->query("SELECT count(*) as cantidad FROM cajas where id_Almacen = '$id_almacen' ");
        return $query->row()->cantidad;
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
        $sql = "SELECT numero, forma_pago, hora_movimiento, valor, id_usuario, id_mov_tip  FROM movimientos_cierre_caja where Id_cierre = '$id' and numero <> ''  and tipo_movimiento = 'entrada_venta' ";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        $total_ventas1 = "SELECT id  FROM venta inner join ventas_pago on venta.id = ventas_pago.id_venta where venta.id  IN (" . $rest1 . ") and (forma_pago <> 'Saldo_a_Favor') ";
        foreach ($this->connection->query($total_ventas1)->result() as $value1) {

            $ventaid = $ventaid . ",'" . $value1->id . "'";
        }

        $rest2 = substr($detalleventaid, 2);
        if ($rest2 == '') {
            $rest1 = 0;
        } else {
            $rest2 = substr($detalleventaid, 2);
        }

        if ($rest2 != '') {
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
            }

            $total_ventas = "SELECT (SELECT nombre_impuesto FROM `impuesto` where porciento = impuesto limit 1) as imp, SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
		 FROM  `venta`
		 inner join detalle_venta on venta.id = detalle_venta.venta_id WHERE venta.id IN (" . $rest2 . ") and impuesto > 0  group by impuesto";
            $total_ventas_result_impuesto = $this->connection->query($total_ventas)->result();
        }

        if ('base' == $tipo) {
            return $base;
        }
        if ('iva' == $tipo) {
            return $total_ventas_result_impuesto;
        }
    }

    //========================================================================================
    // Retorna el valor total de los gastos que deberian descontar el cierre de caja
    //========================================================================================
    //   gastos de tipo caja menor y caja registradora
    //   Bancos y tarjeta credito no tendran en cuenta para restar al cieerre de caja
    // tipo = In, no descontamos los gastos que se cancelaron dentro del cierre de caja,

    public function getGastosDescuentanCaja($id = null)
    {

        $query = "
                SELECT
                SUM(mcc.valor) AS total
                FROM movimientos_cierre_caja AS mcc
                INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
                LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
                WHERE mcc.tabla_mov = 'proformas'
                AND mcc.Id_cierre = '$id'
                AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
                AND cd.tipo_cuenta IN ('Caja menor','Caja registradora')
                AND pf.notas NOT LIKE '%eliminadoIn%'
            ";

        $resultQuery = $this->connection->query($query)->row()->total;
        $result = $resultQuery == "" ? 0 : $resultQuery;
        return $result;
    }

    public function cierre_caja_gastos($id = null)
    {

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago_orden_compra' and Id_cierre = '$id' and forma_pago like '%efectivo%'   ";
        $pago_proveedores = $this->connection->query($query)->result();

        $qBancos = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago_orden_compra' and Id_cierre = '$id' and forma_pago like '%bancos%'   ";
        $pago_proveedores_bancos = $this->connection->query($qBancos)->result();

        //se buscan las órdenes de compras asociadas a los pagos
        $query_ordenes = "SELECT poc.id_factura FROM movimientos_cierre_caja mcc
                        INNER JOIN pago_orden_compra poc ON mcc.id_mov_tip=poc.id_pago
                        WHERE  tabla_mov = 'pago_orden_compra'
                        AND Id_cierre = '$id'
                        AND forma_pago LIKE '%efectivo%'";
        $ordenes_proveedores = $this->connection->query($query_ordenes)->result();
        $orden_proveedores = "";
        foreach ($ordenes_proveedores as $value) {
            $orden_proveedores .= "," . $value->id_factura;
        }

        $orden_proveedores = trim($orden_proveedores, ",");

        // Listamos los gastos que fueron anulados

        $query = "SELECT IFNULL((
                    SELECT
                    SUM(mcc.valor) AS total
                    FROM movimientos_cierre_caja AS mcc
                    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
                    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
                    WHERE mcc.tabla_mov = 'proformas'
                    AND mcc.Id_cierre = '$id'
                    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
                    AND pf.notas LIKE '%eliminadoIn%'
            ),0)AS dentro,
            IFNULL((
                    SELECT
                    SUM(mcc.valor) AS total
                    FROM movimientos_cierre_caja AS mcc
                    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
                    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
                    WHERE mcc.tabla_mov = 'proformas'
                    AND mcc.Id_cierre = '$id'
                    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
                    AND pf.notas LIKE '%eliminadoOut%'
            ),0)AS fuera";

        $gastos_cancelados = $this->connection->query($query)->result();

        // Separamos los gastos segun el tipo ( Banco, Tarjeta de credito, caja menor o caja registradora)
        // No restamos los gastos que fueron eliminados fuera del cierre de caja = Campo notas => eliminadoOut
        // Esto es solo informativo, no restaran nada al cierre de caja
        $query = "
                SELECT cd.tipo_cuenta,
                SUM(mcc.valor) AS total
                FROM movimientos_cierre_caja AS mcc
                INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
                LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
                WHERE mcc.tabla_mov = 'proformas'
                AND mcc.Id_cierre = '$id'
                AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                /*AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)*/
                AND pf.notas NOT LIKE '%eliminadoIn%'
                GROUP BY cd.tipo_cuenta;
            ";
        $pago_gastos_by_tipo = $this->connection->query($query)->result();

        return array(
            'pago_gastos_by_tipo' => $pago_gastos_by_tipo,
            'pago_proveedores' => $pago_proveedores,
            'pago_proveedores_bancos' => $pago_proveedores_bancos,
            'gastos_cancelados' => $gastos_cancelados,
            'gastos_descuentan_caja' => $this->getGastosDescuentanCaja($id),
            'ordenes_proveedores' => $orden_proveedores,
        );
    }

    public function cierre_caja($id = null)
    {

        if ($this->session->userdata('caja') > 0) {
            $id = $this->session->userdata('caja');
        }

        $caja_result_1 = array();
        $query = "SELECT fecha, id_Caja, id_Almacen FROM cierres_caja where id = '$id' ";
        $caja_result_1 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total, forma_pago FROM movimientos_cierre_caja where valor > 0 and Id_cierre = '$id' and tipo_movimiento <> 'salida_gastos' and tipo_movimiento <> 'anulada' and tipo_movimiento <> 'entrada_devolucion' and tipo_movimiento <> 'salida_devolucion' AND tipo_movimiento <> 'entrada_apertura' group by  forma_pago order by total desc ";
        $caja_result_2 = $this->connection->query($query)->result();
        //var_dump($query);
        //var_dump($caja_result_2);
        $query = "SELECT sum(valor) as total, forma_pago FROM movimientos_cierre_caja where Id_cierre = '$id' and tipo_movimiento = 'entrada_apertura'  group by forma_pago order by total desc ";
        $caja_result_3 = $this->connection->query($query)->result();

        //$query = "SELECT sum(valor) as total, forma_pago, tipo_movimiento FROM movimientos_cierre_caja where  tipo_movimiento = 'salida_gastos' and Id_cierre = '$id' group by forma_pago order by total desc ";
        // GASTOS:  Orden de compra + Proformas
        $query = "
                SELECT
                IFNULL( (
                SELECT SUM(valor) AS total1
                FROM movimientos_cierre_caja
                WHERE  tipo_movimiento = 'salida_gastos'
                AND Id_cierre = $id
                AND tabla_mov <> 'proformas'
                AND forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                ),0) +
                IFNULL((
                SELECT
                SUM(mcc.valor) AS total
                FROM movimientos_cierre_caja AS mcc
                INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
                LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
                WHERE mcc.tabla_mov = 'proformas'
                AND mcc.Id_cierre = $id
                AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
                AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
                AND cd.tipo_cuenta IN ('Caja menor','Caja registradora')
                AND pf.notas NOT LIKE '%eliminado%'
                ),0) AS total
            ";
        $caja_result_4 = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago' and Id_cierre = '$id' and forma_pago like '%efectivo%'  ";
        $pago_recibidos = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago_orden_compra' and Id_cierre = '$id' and forma_pago like '%efectivo%'   ";
        $pago_proveedores = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'proformas' and Id_cierre = '$id'  and forma_pago like '%efectivo%'  ";
        $pago_gastos = $this->connection->query($query)->result();

        $query = "SELECT * FROM movimiento_inventario";
        $movimiento_inventario = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where Id_cierre = '$id' and tipo_movimiento = 'anulada'  group by forma_pago order by total desc ";
        $anulada = $this->connection->query($query)->result();

        $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where Id_cierre = '$id' and tipo_movimiento = 'entrada_devolucion' group by tipo_movimiento";
        $devoluciones = $this->connection->query($query)->result();

        // Gastos que deben descontar, sin contar bancos y tarjetas de credito
        $object = new stdClass();
        $object->total = $this->getGastosDescuentanCaja($id);
        $pagos_gastos_new = array($object);

        return array(
            'caja_result_1' => $caja_result_1,
            'caja_result_2' => $caja_result_2,
            'caja_result_3' => $caja_result_3,
            'caja_result_4' => $caja_result_4,
            'caja_result_5' => $devoluciones,
            'pago_recibidos' => $pago_recibidos,
            'pago_proveedores' => $pago_proveedores,
            //'pago_gastos' => $pago_gastos,
            'pago_gastos' => $pagos_gastos_new,
            'movimiento_inventario' => $movimiento_inventario,
        );
    }

    public function cerrar_caja_final($dataUpdate)
    {

        $data = $dataUpdate;

        // Si existe la columna [fecha_cierre] en la tabla [cierres_caja] añadimos fecha de cierre
        if ($this->connection->field_exists('fecha_cierre', 'cierres_caja')) {
            $data["fecha_cierre"] = date("Y-m-d");
        }

        $id = $this->session->userdata('caja');

        $this->connection->where('id', $id);
        $this->connection->update("cierres_caja", $data);
    }

    public function regenerate_box($id_cierre, $id_venta) {

        // Validamos datos de la venta 
        
        $sql_venta = "select * from venta where id = ".$id_venta;

        $result_venta = $this->connection->query($sql_venta)->result();

        $almacen_id = $result_venta[0]->almacen_id;
        $factura = $result_venta[0]->factura;
        $total_valor  = $result_venta[0]->total_venta;

        // Movimiento cierre factura
        $sql_cierre = "select * from movimientos_cierre_caja where numero = '".$factura."'";
        $result_cierre = $this->connection->query($sql_cierre)->result();
        $id_cierre_mov_anterior = $result_cierre[0]->Id_cierre;

        // Actualizamos el movimiento del cierre 
        $this->connection->where('numero', $factura);
        $update = $this->connection->update('movimientos_cierre_caja', ['Id_cierre' => $id_cierre]);

    
        
        $this->connection->where('id', $id_cierre_mov_anterior);

        $cierre_anterior = $this->connection->get('cierres_caja')->row();

        if($cierre_anterior->fecha_fin_cierre != '') {
            $total_cierre_anterior = $cierre_anterior->total_cierre - $total_valor;
            $this->connection->where('id', $id_cierre_mov_anterior);
            $update = $this->connection->update('cierres_caja', ['total_cierre' => $total_cierre_anterior]);
        }

        // Actualizamos el nuevo cierre

        $this->connection->where('id', $id_cierre);

        $cierre_nuevo = $this->connection->get('cierres_caja')->row();

        $totoal_cierre_nuevo = $cierre_nuevo->total_cierre + $total_valor;

        $this->connection->where('id', $id_cierre);
        $update = $this->connection->update('cierres_caja', ['total_cierre' => $totoal_cierre_nuevo]);


        return true;



        




    }

    public function obtener_movimientos_validos($id, $tipo, $almacen = 0)
    {
        $is_admin = $this->session->userdata('is_admin');
        $cambio = '';
        $username = '';
        switch ($tipo) {
            case 'validos':
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.numero = v.factura LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago <> 'Saldo_a_Favor' AND v.almacen_id = $almacen AND estado = 0 ORDER BY numero";
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago <> 'Saldo_a_Favor' AND v.almacen_id = $almacen AND estado = 0 ORDER BY numero";
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND v.almacen_id = $almacen AND estado = 0 ORDER BY numero";
                $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.tabla_mov IN ('venta') AND v.almacen_id = $almacen AND estado = 0 ORDER BY numero";
                //var_dump($sql);die;
                break;
            case 'devoluciones':
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.numero = v.factura LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago = 'Saldo_a_Favor' ORDER BY numero";
                $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago = 'Saldo_a_Favor' ORDER BY numero";
                break;
            case 'anuladas':
                $where_almacen = "";
                if ($is_admin != "a" && $is_admin != "t") {
                    $where_almacen = "AND v.almacen_id =" . $almacen;
                }
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.numero = v.factura LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' $where_almacen AND estado = '-1' ORDER BY numero";
                $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.tipo_movimiento IN ('anulada') AND mc.tabla_mov IN ('anulada') AND mc.numero <> '' $where_almacen AND estado = '-1' ORDER BY numero";
                //var_dump($sql);die;
                break;
            default:
                //$sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.numero = v.factura LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago <> 'Saldo_a_Favor' ORDER BY numero";
                $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor , mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '$id' AND mc.numero <> '' AND mc.tipo_movimiento IN ('entrada_venta') AND mc.forma_pago <> 'Saldo_a_Favor' ORDER BY numero";
                break;
        }
        $data = array();

        foreach ($this->connection->query($sql)->result() as $value1) {

            $sql1 = "SELECT username  FROM users where id = '$value1->id_usuario' ";
            foreach ($this->db->query($sql1)->result() as $value2) {
                $username = $value2->username;
            }

            $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia,
                    if(nombre_producto='PROPINA' && (codigo_producto is null),precio_venta,0) as propina,
	 			    (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` AS impuesto,
	 			    (`precio_venta` - `descuento`) * `unidades`  AS total_precio_venta,
	  			  `unidades` * `descuento`  AS total_descuento,
	  			  `descripcion_producto`  AS sobrecosto,
                    impuesto as porcentaje_impuesto
	 			FROM  `venta`
	 			INNER JOIN detalle_venta on venta.id = detalle_venta.venta_id
	 			WHERE venta.id = '$value1->id_mov_tip'";
            //echo $total_ventas;
            $total_si_cero = 0;
            $total_ventas_result = $this->connection->query($total_ventas)->result();
            $impuesto = 0;
            $total_descuento = 0;
            $porcentaje_impuesto = 0;
            $total_base_productos = 0;
            $propina_total = 0;
            foreach ($total_ventas_result as $dat1) {
                $impuesto += $this->opciones->redondear($dat1->impuesto);
                $total_descuento += $this->opciones->redondear($dat1->total_descuento);
                $total_base_productos += $this->opciones->redondear($dat1->total_precio_venta);
                $propina_total += $dat1->propina;
                if ($dat1->porcentaje_impuesto > 0) {
                    $porcentaje_impuesto = $this->opciones->redondear($dat1->porcentaje_impuesto);
                }

            }
            //decimales? decimales_moneda
            $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda' LIMIT 1 ";
            $ocpresult = $this->connection->query($ocp)->result();
            foreach ($ocpresult as $dat) {
                $decimales_moneda = $dat->valor_opcion;
            }

            //echo $decimales_moneda;
            //die();
            if ($decimales_moneda == 0) {
                $impuesto = round($impuesto);
                $total_descuento = round($total_descuento);
                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);
                $total_base_productos = round($total_base_productos);
                $propina_total = round($propina_total);
            } else {
                $impuesto = ($impuesto);
                $total_descuento = ($total_descuento);
                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);
                $total_base_productos = ($total_base_productos);
                $propina_total = ($propina_total);
            }
            //verificar si es el mismo monto en los pagos
            $sqlventa_pago = "select SUM(valor_entregado-cambio) AS valor from ventas_pago where id_venta='$value1->id_mov_tip' and forma_pago='$value1->forma_pago'";
            $sqlventa_pago = $this->connection->query($sqlventa_pago)->result();
            $valorp = $value1->valor;

            //if($sqlventa_pago[0]->valor!=$value1->valor){
            if ($sqlventa_pago[0]->valor <= $value1->valor) {
                $valorp = $sqlventa_pago[0]->valor;
                //mensaje para que llegue al slack
                //$mensaje="El usuario con id=".$this->session->userdata('user_id')." de la bd=".$this->session->userdata('base_dato')." tiene un valor incorrecto en el movimiento cierre de caja con id_cierre =".$id." en el id_mov_tip=".$value1->id_mov_tip." para la forma de pago=".$value1->forma_pago." y el valor=".$value1->valor." en el almacén=".$almacen;
                //slack($mensaje);
            }

            $username = ($username != "") ? $username : "No existe usuario";
            $data[] = array(
                'numero' => $value1->numero,
                'hora_movimiento' => $value1->hora_movimiento,
                //'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
                //'valor' => $value1->valor,
                'valor' => $valorp,
                'username' => $username,
                'impuesto' => $impuesto,
                'total_descuento' => $total_descuento,
                'forma_pago' => $formpago1,
                'anulada' => $value1->anulada,
                'porcentaje_impuesto' => $porcentaje_impuesto,
                'base_productos' => $total_base_productos,
                'propina' => $propina_total,
            );
        }

        return $data;
    }

    public function obtener_impuestos_validos($id, $tipo, $almacen = 0)
    {
        //busco las ventas que estan dentro del cierre
        $sql = "SELECT DISTINCT id_mov_tip  FROM movimientos_cierre_caja where Id_cierre = '$id' and numero <> ''  and tabla_mov = 'venta' ";

        $data = array();
        $detalleventaid = "";

        foreach ($this->connection->query($sql)->result() as $value1) {
            $detalleventaid = $detalleventaid . ",'" . $value1->id_mov_tip . "'";
        }

        $detalleventaid = trim($detalleventaid, ',');
        $ventascierre = "";
        if (!empty($detalleventaid)) {
            $ventascierre = " AND venta.id IN($detalleventaid) ";
        }

        $total_ventas = '';
        $total_ventas_sin_impuesto = '';
        //$condition = " and  almacen_id = '$almacen' ";
        $ventas = array();
        if (!empty($ventascierre)) {
            // busco impuestos de las facturas del cierre
            /* $total_ventas = "SELECT  DATE(venta.fecha) AS fecha_dia, venta.id,
            SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades) AS impuesto, porciento, nombre_impuesto,
            SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) AS subtotal,
            (SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) + SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades)) AS total
            FROM venta
            inner join detalle_venta on venta.id = detalle_venta.venta_id
            inner join impuesto on detalle_venta.impuesto = impuesto.porciento
            WHERE estado='0' and impuesto > 0 and  almacen_id = '$almacen' $ventascierre group by nombre_impuesto ";
            echo "<br>".$total_ventas; die();*/
            //DETALLADO
            $total_ventas_impuestos = "SELECT DATE(venta.fecha) AS fecha_dia, venta.id, detalle_venta.precio_venta, detalle_venta.descuento, detalle_venta.impuesto, detalle_venta.unidades, porciento, nombre_impuesto,
            (IF((detalle_venta.descripcion_producto = '0' OR detalle_venta.descripcion_producto = ''),0,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(detalle_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))=0,detalle_venta.unidades,(SELECT detalle_venta.unidades - (SUBSTRING_INDEX(SUBSTRING_INDEX(detalle_venta.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))))) AS cantidades_devueltas
            FROM venta
            INNER JOIN detalle_venta ON venta.id = detalle_venta.venta_id
            INNER JOIN impuesto ON detalle_venta.impuesto = impuesto.porciento
            WHERE estado='0'
            AND impuesto > 0
            AND almacen_id = $almacen
            $ventascierre ORDER BY impuesto";

            //echo $total_ventas_impuestos;

            $subtotal = 0;
            $total = 0;
            $impuesto = 0;
            $i = 0;
            $aux = 0;
            foreach ($this->connection->query($total_ventas_impuestos)->result() as $value) {
                if ($i == 0) {
                    $aux = $value->porciento;
                    $i = 1;
                }

                if ($aux != $value->porciento) {
                    $aux = $value->porciento;
                    $subtotal = 0;
                    $total = 0;
                    $impuesto = 0;
                }

                if ($aux == $value->porciento) {

                    $impuesto += $this->opciones->redondear(((($value->precio_venta - $value->descuento) * $value->impuesto) / 100) * ($value->unidades - $value->cantidades_devueltas));
                    $subtotal += $this->opciones->redondear(((($value->precio_venta - $value->descuento))) * ($value->unidades - $value->cantidades_devueltas));
                    $total += $this->opciones->redondear((((($value->precio_venta - $value->descuento))) * ($value->unidades - $value->cantidades_devueltas)) + (((($value->precio_venta - $value->descuento) * $value->impuesto) / 100) * ($value->unidades - $value->cantidades_devueltas)));

                    $ventas[$value->porciento] = array(
                        'porciento' => $value->porciento,
                        'nombre_impuesto' => $value->nombre_impuesto,
                        'impuesto' => $impuesto,
                        'subtotal' => $subtotal,
                        'total' => $total,
                    );
                }
            }
            /* echo "<br>impuesto=".$impuesto;
        echo "<br>subtotal=".$subtotal;
        echo "<br>total=".$total;
        print_r($ventas);
        die("<br>sali");*/
        }
        /*print_r($ventas);
        die();*/
        return $ventas;
    }

    public function obtenerDevolucionesCierreCaja($id)
    {
        $movimiento = $this->connection->get_where("movimientos_cierre_caja", array("tipo_movimiento" => "salida_devolucion", "Id_cierre" => $id))->result();
        //echo $this->connection->last_query(); die();
        $data = array();

        foreach ($movimiento as $mov) {
            $notaDebito = $this->connection->get_where("notacredito", array("id" => $mov->numero))->row();
            $notaCredito = $this->connection->get_where("notacredito", array("id" => $notaDebito->notaForeign_id))->row();
            $usuarioDebito = $this->db->get_where('users', array("id" => $notaDebito->usuario_id))->row();
            $usuarioCredito = $this->db->get_where('users', array("id" => $notaCredito->usuario_id))->row();
            $facturaDebito = $this->connection->get_where("venta", array("id" => $notaDebito->factura_id))->row();
            $facturaCredito = $this->connection->get_where("venta", array("id" => $notaCredito->factura_id))->row();
            $data[] = array(
                'devolucion' => $notaCredito->id,
                'fecha' => date("Y-m-d", strtotime($notaCredito->fecha)),
                "facturaDevuelta" => $facturaCredito->factura,
                "usernameDevuelta" => $usuarioCredito->username,
                "facturaRedimida" => $facturaDebito->factura,
                "usernameRedimida" => $usuarioDebito->username,
                "valor" => $notaCredito->valor,
            );
        }

        return $data;
    }

    public function obtenerDevolucionesPendientes($id)
    {
        $movimiento = $this->connection->get_where("movimientos_cierre_caja", array("tipo_movimiento" => "entrada_devolucion", "Id_cierre" => $id))->result();
        //echo $this->connection->last_query(); die();
        $data = array();
        $redimida = "";
        foreach ($movimiento as $mov):
            //$result = $this->connection->query("SELECT * FROM notacredito WHERE devolucion_id = '{$mov->id_mov_tip}' AND notaForeign_id IS NULL");
            $result = $this->connection->query("SELECT * FROM notacredito WHERE devolucion_id = '{$mov->id_mov_tip}' AND consecutivo!='--'");
            if ($result->num_rows()):
                $notaCredito = $result->row();
                $facturaCredito = $this->connection->get_where('venta', array('id' => $notaCredito->factura_id))->row();
                $usuarioCredito = $this->db->get_where('users', array("id" => $notaCredito->usuario_id))->row();
                if (!empty($notaCredito->notaForeign_id)) {
                    $redimida = "Si";
                } else {
                    $redimida = "No";
                }
                $data[] = array(
                    'devolucion' => $notaCredito->id,
                    'consecutivo' => $notaCredito->consecutivo,
                    'fecha' => date("Y-m-d", strtotime($notaCredito->fecha)),
                    "facturaDevuelta" => $facturaCredito->factura,
                    "usernameDevuelta" => $usuarioCredito->username,
                    "valor" => $notaCredito->valor,
                    "redimida" => $redimida,
                );
            else:
                continue;
            endif;
        endforeach;

        return $data;
    }

    public function obtenerAbonos($id, $fecha_inicio = null, $fecha_fin = null)
    {
        $data = array('plan_separe' => array(), 'creditos' => array());
        if ((!empty($fecha_inicio)) && (!empty($fecha_fin))) {
            $where = " where fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        } else {
            $where = "";
        }
        //busco las ventas realizadas en el periodo realizado el cierre
        $sqlv = "SELECT id FROM venta $where";
        //echo $sqlv; die();
        $sqlv = $this->connection->query($sqlv)->result();
        //pagos plan separe
        // $sql1 = "SELECT v.id, v.factura AS numero, mc.forma_pago AS forma_pago, mc.hora_movimiento AS hora_movimiento, mc.valor as valor , mc.id_usuario AS id_usuario, mc.id_mov_tip AS id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc INNER JOIN plan_separe_pagos p ON mc.id_mov_tip = p.`id_pago` LEFT JOIN venta v ON p.`id_venta` = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '" . $id . "' AND mc.tipo_movimiento IN ('entrada_venta') AND tabla_mov = 'plan_separe_pagos' ORDER BY numero";
        $sql1 = "SELECT  v.id, v.factura AS numero, mc.forma_pago AS forma_pago, mc.hora_movimiento AS hora_movimiento, mc.valor AS valor , mc.id_usuario AS id_usuario, mc.id_mov_tip AS id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada
            FROM movimientos_cierre_caja mc
            INNER JOIN plan_separe_pagos p ON mc.id_mov_tip = p.id_pago
            INNER JOIN plan_separe_factura pf ON p.id_venta = pf.id
            LEFT JOIN venta v ON pf.factura= v.factura
            LEFT JOIN ventas_anuladas va ON v.id = va.venta_id
            WHERE mc.Id_cierre = '" . $id . "'
            AND mc.tipo_movimiento IN ('entrada_venta')
            AND tabla_mov = 'plan_separe_pagos'
            ORDER BY numero";
        //  echo $sql1; die();
        foreach ($this->connection->query($sql1)->result() as $value1) {

            $sql2 = "SELECT username  FROM users where id = '$value1->id_usuario' ";

            foreach ($this->db->query($sql2)->result() as $value2) {
                $username = $value2->username;
            }
            $estoy = 0;
            if (!empty($value1->id)) {
                //busco si esta entre la fecha del cierre
                foreach ($sqlv as $venta) {
                    if ($value1->id == $venta->id) {
                        $estoy = 1;
                    }
                }
                if ($estoy == 0) { //verifico si ese plan separe se facturo despues de mi cierre
                    $sqlv2 = "SELECT id, fecha FROM venta WHERE id=$value1->id";
                    $sqlv2 = $this->connection->query($sqlv2)->result();
                    if ($sqlv2[0]->fecha >= $fecha_inicio) {
                        //incluyo
                        $estoy = 1;
                    }
                }
            } else {
                $estoy = 1;
            }
            if ($estoy == 1) {

                $total_ventas = "
                            SELECT
                            DATE( sf.fecha ) AS fecha_dia,
                            0 AS impuesto,
                            sp.valor_entregado AS total_precio_venta,
                            0 AS total_descuento,
                            0 AS sobrecosto
                            FROM  plan_separe_pagos AS sp
                            INNER JOIN plan_separe_factura AS sf ON sf.id = sp.id_venta
                            WHERE sp.id_pago = '$value1->id_mov_tip'";

                $total_si_cero = 0;
                $total_ventas_result = $this->connection->query($total_ventas)->result();

                foreach ($total_ventas_result as $dat1) {
                    $impuesto = round($dat1->impuesto);
                    $total_descuento = round($dat1->total_descuento);
                    $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
                }

                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);

                $data['plan_separe'][] = array(
                    'numero' => $value1->numero,
                    'hora_movimiento' => $value1->hora_movimiento,
                    'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
                    'username' => $username,
                    //'impuesto' => $impuesto,
                    'impuesto' => 0,
                    'total_descuento' => $total_descuento,
                    'forma_pago' => $formpago1,
                    'anulada' => $value1->anulada,
                );
            }
        }

        //pagos a creditos
        $sql = "SELECT v.id, v.factura AS numero, mc.forma_pago AS forma_pago, mc.hora_movimiento AS hora_movimiento, mc.valor as valor , mc.id_usuario AS id_usuario, mc.id_mov_tip AS id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN pago p ON mc.id_mov_tip = p.`id_pago` LEFT JOIN venta v ON p.`id_factura` = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre = '" . $id . "' AND mc.tipo_movimiento IN ('entrada_venta') AND tabla_mov = 'pago' ORDER BY numero";
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
	 			INNER JOIN detalle_venta on venta.id = detalle_venta.venta_id
	 			WHERE venta.id = '$value1->id'";
            $total_si_cero = 0;
            $total_ventas_result = $this->connection->query($total_ventas)->result();

            foreach ($total_ventas_result as $dat1) {
                $impuesto = round($dat1->impuesto);
                $total_descuento = round($dat1->total_descuento);
                $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
            }

            $formpago1 = str_replace("_", " ", $value1->forma_pago);
            $formpago1 = ucfirst($formpago1);
            $data['creditos'][] = array(
                'numero' => $value1->numero,
                'hora_movimiento' => $value1->hora_movimiento,
                'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
                'username' => $username,
                //'impuesto' => $impuesto,
                'impuesto' => 0,
                'total_descuento' => $total_descuento,
                'forma_pago' => $formpago1,
                'anulada' => $value1->anulada,
            );
        }

        return $data;
    }

    public function get_formas_pago_validas($id, $id_almacen)
    {

        $cambio = '';
        $id_ventas = "";
        $id_plansepare = "";
        $id_pagos_creditos = "";
        $sql = "SELECT sum(a.valor) total_ingresos,
                           count(a.forma_pago) as cantidad_ingresos,
                           a.forma_pago
                      FROM movimientos_cierre_caja a
                      /*INNER JOIN venta b ON a.numero = b.factura*/
                     /* INNER JOIN venta b ON a.id_mov_tip = b.id*/
                     WHERE
                        a.Id_cierre = '$id'
                       AND a.tipo_movimiento <> 'salida_gastos'
                       AND a.tipo_movimiento <> 'entrada_apertura'
                       AND a.tipo_movimiento <> 'entrada_devolucion'
                       AND a.tipo_movimiento <> 'salida_devolucion'
                      /*AND a.forma_pago <> 'Saldo_a_Favor'*/
                       AND a.tabla_mov <> 'anulada'
                       AND a.tipo_movimiento <> 'anulada'
                       /*AND b.estado != -1
                       AND b.almacen_id = '$id_almacen'  */
                     GROUP BY a.forma_pago
                     ORDER BY a.forma_pago ASC";

        //verificar si concuerda el pago con las ventas_pago
        $sqlid_ventas = "SELECT DISTINCT a.`id_mov_tip`, a.tabla_mov FROM movimientos_cierre_caja a
                        WHERE
                        a.Id_cierre = '$id'
                        AND a.tipo_movimiento <> 'salida_gastos'
                        AND a.tipo_movimiento <> 'entrada_apertura'
                        AND a.tipo_movimiento <> 'entrada_devolucion'
                        AND a.tipo_movimiento <> 'salida_devolucion'
                        AND a.tabla_mov <> 'anulada'
                        AND a.tipo_movimiento <> 'anulada'";
        $sqlid_ventas = $this->connection->query($sqlid_ventas)->result_array();

        foreach ($sqlid_ventas as $key => $value) {
            if ($value['tabla_mov'] == 'venta') {
                $id_ventas .= "," . $value['id_mov_tip'];
            } else {
                if ($value['tabla_mov'] == 'plan_separe_pagos') {
                    $id_plansepare .= "," . $value['id_mov_tip'];
                } else {
                    if ($value['tabla_mov'] == 'pago') {
                        $id_pagos_creditos .= "," . $value['id_mov_tip'];
                    }

                }

            }
        }
        $id_ventas = trim($id_ventas, ',');
        $id_plansepare = trim($id_plansepare, ',');
        $id_pagos_creditos = trim($id_pagos_creditos, ',');
        $sql_ventas_pago = array();
        $sql_pl_abono = array();
        $sql_cre_abono = array();
        //echo $id_plansepare;
        if (!empty($id_ventas)) {
            $sql_ventas_pago = "SELECT
                SUM(valor_entregado-cambio) total_ingresos,
                COUNT(forma_pago) AS cantidad_ingresos,
                forma_pago
                FROM ventas_pago
                WHERE id_venta IN($id_ventas)
                GROUP BY forma_pago
                ORDER BY forma_pago ASC";
            $sql_ventas_pago = $this->connection->query($sql_ventas_pago)->result();
        }

        if (!empty($id_plansepare)) {
            $sql_pl_abono = "SELECT
                SUM(valor_entregado) total_ingresos,
                COUNT(forma_pago) AS cantidad_ingresos,
                forma_pago
                FROM plan_separe_pagos psp
                INNER JOIN plan_separe_factura psf ON psp.`id_venta`=psf.`id`
                WHERE psp.`id_pago` IN($id_plansepare)
                AND psf.`factura`='-'
                AND psf.estado <> 3";
            $sql_pl_abono = $this->connection->query($sql_pl_abono)->result();
        }

        if (!empty($id_pagos_creditos)) {
            $sql_cre_abono = "SELECT
                SUM(cantidad) total_ingresos,
                COUNT(tipo) AS cantidad_ingresos,
                tipo as forma_pago
                FROM pago p
                INNER JOIN venta v ON p.`id_factura`=v.`id`
                WHERE p.id_pago IN($id_pagos_creditos)
                AND v.estado =0";
            $sql_cre_abono = $this->connection->query($sql_cre_abono)->result();
        }
        //ingreso los abonos que no tienen facturas asociadas a ventas_pago

        foreach ($sql_pl_abono as $abono) {
            foreach ($sql_ventas_pago as $value) {

                if ($abono->forma_pago == $value->forma_pago) {
                    $value->total_ingresos = $value->total_ingresos + $abono->total_ingresos;
                }
            }
        }

        foreach ($sql_cre_abono as $abono) {
            foreach ($sql_ventas_pago as $value) {

                if ($abono->forma_pago == $value->forma_pago) {
                    $value->total_ingresos = $value->total_ingresos + $abono->total_ingresos;
                }
            }
        }

        $sql = $this->connection->query($sql)->result();
        $data = array();
        $decimales_moneda = null;
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda' LIMIT 1 ";
        $ocpresult = $this->connection->query($ocp)->result();

        foreach ($ocpresult as $dat) {
            $decimales_moneda = $dat->valor_opcion;
        }

        if (!empty($sql) && (!empty($sql_ventas_pago))) {
            for ($i = 0; $i < count($sql); $i++) {
                $formpago1 = str_replace("_", " ", $sql[$i]->forma_pago);
                $formpago1 = ucfirst($formpago1);

                foreach ($sql_ventas_pago as $key => $value) {
                    $formpago2 = str_replace("_", " ", $value->forma_pago);
                    $formpago2 = ucfirst($formpago2);
                    if ($formpago1 == $formpago2) {

                        if ($sql[$i]->total_ingresos <= $value->total_ingresos) {
                            $data[] = array(
                                'total_ingresos' => $sql[$i]->total_ingresos,
                                'cantidad_ingresos' => $sql[$i]->cantidad_ingresos,
                                'forma_pago' => $formpago1,
                            );
                        } else {
                            $data[] = array(
                                'total_ingresos' => $value->total_ingresos,
                                'cantidad_ingresos' => $value->cantidad_ingresos,
                                'forma_pago' => $formpago1,
                            );
                        }
                    }
                }

            }
        }
        // echo"<br>dataFinal:";
        //print_r($data); die();
        return $data;
    }

    public function get_users()
    {
        $db_config_id = $this->session->userdata('db_config_id');
        $usuarios = $this->db->get_where('users', array('db_config_id' => $db_config_id))->result_array();

        $query = $this->connection->query("SELECT a.usuario_id,
                                            CONCAT( b.nombre, ' / Caja: ', c.nombre )  AS nombre
                                            FROM usuario_almacen a 
                                            INNER JOIN almacen b ON a.almacen_id = b.id
                                            INNER JOIN cajas c ON a.id_Caja = c.id");

        $data = array();
        $data[''] = 'Seleccione un Usuario';
        foreach ($query->result() as $row) {
            $username = "";
            foreach($usuarios as $usuario) {
                if($usuario['id'] == $row->usuario_id) {
                    $username = $usuario['username'] . " / ";
                    break;
                }
            }

            $data[$row->usuario_id] = ucwords($username . $row->nombre);

        }
        return $data;
    }

    public function get_data_user_caja($data_array, $empresa)
    {

        $db_config_id = $this->session->userdata('db_config_id');
        $query = $this->connection->query("SELECT a.usuario_id,
                                                  b.id AS almacen_id,
                                                  b.nombre AS almacen_nombre,
                                                  c.id AS caja_id,
                                                  c.nombre AS caja_nombre,
                                                  d.username
                                             FROM usuario_almacen a INNER JOIN
                                                  almacen b ON a.almacen_id = b.id INNER JOIN
                                                  cajas c ON a.id_Caja = c.id INNER JOIN
                                                  vendty2.users d ON a.usuario_id = d.id
                                            WHERE d.db_config_id = '{$db_config_id}'
                                              AND a.usuario_id = '{$data_array['user']}' ");
        $usuario = $query->row();
        $this->connection->select('*');
        if ($empresa['data']['cierre_automatico'] == "1") {
            $this->connection->where('fecha >=', $data_array['fecha_inicia']);
            $this->connection->where('fecha <=', $data_array['fecha_finalx']);
        } else {
            $this->connection->where('fecha_cierre >=', $data_array['fecha_inicia']);
            $this->connection->where('fecha_cierre <=', $data_array['fecha_finalx']);
        }
        $this->connection->where('id_Usuario', $data_array['user']);
        $query = $this->connection->get('cierres_caja');
        $cierres = $query->result();
        return $cierres;

        /* if($empresa['data']['cierre_automatico'] == "1"){
    /* $query = $this->connection->query("SELECT a.id,
    a.fecha,
    a.hora_apertura,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id
    WHERE a.fecha BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ORDER BY 1 ASC LIMIT 1");

    /* $numero_menor = $query->row();

    $query = $this->connection->query("SELECT a.id,
    a.fecha,
    a.hora_cierre,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id
    WHERE a.fecha BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ORDER BY 1 DESC LIMIT 1 ");

    $numero_mayor = $query->row();

    $query = $this->connection->query("SELECT a.id,
    a.fecha,
    a.hora_cierre,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id INNER JOIN
    venta d ON d.almacen_id = c.almacen_id
    AND b.numero = d.factura
    WHERE a.fecha BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ");

    $total = $query->num_rows();
    }else{
    //no tiene cierre automatico
    $query = $this->connection->query("SELECT a.id,
    a.fecha,
    a.hora_apertura,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id
    WHERE a.fecha_cierre BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ORDER BY 1 ASC LIMIT 1");

    /* $numero_menor = $query->row();

    $query = $this->connection->query("SELECT a.id,
    a.fecha_cierre,
    a.hora_cierre,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id
    WHERE a.fecha_cierre BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ORDER BY 1 DESC LIMIT 1 ");

    $numero_mayor = $query->row();

    $query = $this->connection->query("SELECT a.id,
    a.fecha_cierre,
    a.hora_cierre,
    b.numero AS numero_factura
    FROM cierres_caja a INNER JOIN
    movimientos_cierre_caja b ON a.id = b.Id_cierre INNER JOIN
    usuario_almacen c ON b.id_usuario = c.usuario_id INNER JOIN
    venta d ON d.almacen_id = c.almacen_id
    AND b.numero = d.factura
    WHERE a.fecha_cierre BETWEEN '{$data_array['fecha_inicia']}' AND '{$data_array['fecha_finalx']}'
    AND a.id_Usuario = '{$data_array['user']}'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND b.tipo_movimiento LIKE 'entrada_venta' ");

    $total = $query->num_rows();
    }

    /* $facturas = array();
    foreach ($query->result_array() as $row):
    $facturas[] = $row['id'];
    endforeach;

    $facturas = array_unique($facturas);

    if(count($facturas) > 0 ):
    $query = $this->connection->query( "SELECT v.id,
    mc.numero as numero,
    mc.forma_pago as forma_pago,
    mc.hora_movimiento as hora_movimiento,
    mc.valor as valor ,
    mc.id_usuario as id_usuario,
    mc.id_mov_tip as id_mov_tip,
    IF(ISNULL(va.fecha), 0, 1) AS anulada
    FROM movimientos_cierre_caja mc LEFT JOIN
    venta v ON mc.numero = v.factura LEFT JOIN
    ventas_anuladas va ON v.id = va.venta_id
    WHERE mc.Id_cierre IN('".join("','", $facturas)."')
    AND mc.numero <> ''
    AND mc.tipo_movimiento IN ('entrada_venta')
    AND mc.forma_pago <> 'Saldo_a_Favor'
    AND v.almacen_id = '{$usuario->almacen_id}'
    AND estado = 0 ORDER BY numero ");

    $ventas = array();

    foreach ($query->result() as $value1) {
    $sql1 = "SELECT username  FROM users where id = '$value1->id_usuario' ";
    foreach ($this->db->query($sql1)->result() as $value2) {
    $username = $value2->username;
    }

    $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia,
    SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto,
    SUM( `precio_venta` * `unidades` ) AS total_precio_venta,
    SUM( `unidades` * `descuento` ) AS total_descuento,
    SUM( `descripcion_producto` ) AS sobrecosto
    FROM `venta` INNER JOIN
    detalle_venta on venta.id = detalle_venta.venta_id
    WHERE venta.id = '$value1->id_mov_tip'
    AND venta.almacen_id = '{$usuario->almacen_id}' ";

    $total_si_cero = 0;
    $total_ventas_result = $this->connection->query($total_ventas)->result();

    foreach ($total_ventas_result as $dat1) {
    $impuesto = round($dat1->impuesto);
    $total_descuento = round($dat1->total_descuento);
    $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
    }

    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);
    $ventas[] = array(
    'numero' => $value1->numero,
    'hora_movimiento' => $value1->hora_movimiento,
    'valor' => $value1->valor,
    'username' => $username,
    'impuesto' => $impuesto,
    'total_descuento' => $total_descuento,
    'forma_pago' => $formpago1,
    'anulada' => $value1->anulada
    );
    }

    $query = $this->connection->query("SELECT v.id,
    mc.numero as numero,
    mc.forma_pago as forma_pago,
    mc.hora_movimiento as hora_movimiento,
    mc.valor as valor ,
    mc.id_usuario as id_usuario,
    mc.id_mov_tip as id_mov_tip,
    IF(ISNULL(va.fecha), 0, 1) AS anulada
    FROM movimientos_cierre_caja mc LEFT JOIN
    venta v ON mc.numero = v.factura LEFT JOIN
    ventas_anuladas va ON v.id = va.venta_id
    WHERE mc.Id_cierre IN('".join("','", $facturas)."')
    AND mc.numero <> ''
    AND v.almacen_id = '{$usuario->almacen_id}'
    AND estado = '-1' ORDER BY numero ");

    $anuladas = array();

    foreach ($query->result() as $value1) {
    $sql1 = "SELECT username  FROM users where id = '$value1->id_usuario' ";
    foreach ($this->db->query($sql1)->result() as $value2) {
    $username = $value2->username;
    }

    $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia,
    SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto,
    SUM( `precio_venta` * `unidades` ) AS total_precio_venta,
    SUM( `unidades` * `descuento` ) AS total_descuento,
    SUM( `descripcion_producto` ) AS sobrecosto
    FROM `venta` INNER JOIN
    detalle_venta on venta.id = detalle_venta.venta_id
    WHERE venta.id = '$value1->id_mov_tip'
    AND venta.almacen_id = '{$usuario->almacen_id}' ";

    $total_si_cero = 0;
    $total_ventas_result = $this->connection->query($total_ventas)->result();

    foreach ($total_ventas_result as $dat1) {
    $impuesto = round($dat1->impuesto);
    $total_descuento = round($dat1->total_descuento);
    $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
    }

    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);
    $anuladas[] = array(
    'numero' => $value1->numero,
    'hora_movimiento' => $value1->hora_movimiento,
    'valor' => $value1->valor,
    'username' => $username,
    'impuesto' => $impuesto,
    'total_descuento' => $total_descuento,
    'forma_pago' => $formpago1,
    'anulada' => $value1->anulada
    );
    }

    $query = $this->connection->query("SELECT a.numero,
    c.id AS devolucion,
    c.fecha,
    e.factura  AS facturaDevuelta,
    g.username AS usernameDevuelta,
    d.factura  AS facturaRedimida,
    f.username AS usernameRedimida,
    c.valor
    FROM movimientos_cierre_caja a INNER JOIN
    notacredito b ON a.numero = b.id INNER JOIN
    notacredito c ON b.notaForeign_id = c.id INNER JOIN
    venta d ON b.factura_id = d.id INNER JOIN
    venta e ON c.factura_id = e.id INNER JOIN
    vendty2.users f ON b.usuario_id = f.id INNER JOIN
    vendty2.users g ON c.usuario_id = g.id
    WHERE tipo_movimiento LIKE 'salida_devolucion'
    AND a.Id_cierre IN('".join("','", $facturas)."')
    AND d.almacen_id = '{$usuario->almacen_id}'
    AND e.almacen_id = '{$usuario->almacen_id}'");

    $devoluciones = $query->result_array();

    $query = $this->connection->query("SELECT a.numero,
    b.id AS devolucion,
    b.fecha,
    b.consecutivo,
    c.factura  AS facturaDevuelta,
    d.username AS usernameDevuelta,
    b.valor
    FROM movimientos_cierre_caja a INNER JOIN
    notacredito b ON a.id_mov_tip = b.devolucion_id
    AND notaForeign_id IS NULL INNER JOIN
    venta c ON b.factura_id = c.id INNER JOIN
    vendty2.users d ON b.usuario_id = d.id
    WHERE tipo_movimiento LIKE 'entrada_devolucion'
    AND c.almacen_id = '{$usuario->almacen_id}'
    AND a.Id_cierre IN('".join("','", $facturas)."')");

    $devoluciones_pendientes = $query->result_array();

    $sql = "SELECT sum(a.valor) total_ingresos,
    count(a.forma_pago) as cantidad_ingresos,
    a.forma_pago
    FROM movimientos_cierre_caja a INNER JOIN
    venta b ON a.numero = b.factura
    WHERE a.valor > 0
    AND a.Id_cierre IN('".join("','", $facturas)."')
    AND a.tipo_movimiento <> 'salida_gastos'
    AND a.tipo_movimiento <> 'entrada_apertura'
    AND a.tipo_movimiento <> 'entrada_devolucion'
    AND a.tipo_movimiento <> 'salida_devolucion'
    AND a.forma_pago <> 'Saldo_a_Favor'
    AND a.tabla_mov <> 'anulada'
    AND a.tipo_movimiento <> 'anulada'
    AND b.estado != -1
    AND b.almacen_id = '{$usuario->almacen_id}'
    GROUP BY a.forma_pago
    ORDER BY a.forma_pago ASC";

    $formas_pago = array();
    foreach ($this->connection->query($sql)->result() as $value1) {

    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);
    $formas_pago[] = array(
    'total_ingresos' => $value1->total_ingresos,
    'cantidad_ingresos' => $value1->cantidad_ingresos,
    'forma_pago' => $formpago1
    );
    }

    $sql = "SELECT sum(valor) total_ingresos, forma_pago FROM movimientos_cierre_caja WHERE valor > 0 AND Id_cierre IN('".join("','", $facturas)."') AND  tipo_movimiento = 'entrada_apertura'  GROUP BY  forma_pago  ORDER BY forma_pago ASC";
    $cierre = array();
    $result = $this->connection->query($sql)->result();
    if (count($result) > 0) {
    foreach ($result as $value1) {
    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);
    $cierre[] = array(
    'total_ingresos' => $value1->total_ingresos,
    'forma_pago' => $formpago1
    );
    }
    }

    #Abonos
    #-----------------------------------------------------------------------
    #Plan Separe
    $abonos = array();
    $sql1 = "SELECT v.id, v.factura AS numero, mc.forma_pago AS forma_pago, mc.hora_movimiento AS hora_movimiento, mc.valor as valor , mc.id_usuario AS id_usuario, mc.id_mov_tip AS id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc INNER JOIN plan_separe_pagos p ON mc.id_mov_tip = p.`id_pago` LEFT JOIN venta v ON p.`id_venta` = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre IN('".join("','", $facturas)."') AND mc.tipo_movimiento IN ('entrada_venta') AND tabla_mov = 'plan_separe_pagos' ORDER BY numero";
    foreach ($this->connection->query($sql1)->result() as $value1) {

    $sql2 = "SELECT username  FROM users where id = '$value1->id_usuario' ";

    foreach ($this->db->query($sql2)->result() as $value2) {
    $username = $value2->username;
    }

    $total_ventas = "
    SELECT
    DATE( sf.fecha ) AS fecha_dia,
    0 AS impuesto,
    sp.valor_entregado AS total_precio_venta,
    0 AS total_descuento,
    0 AS sobrecosto
    FROM  plan_separe_pagos AS sp
    INNER JOIN plan_separe_factura AS sf ON sf.id = sp.id_venta
    WHERE sp.id_pago = '$value1->id_mov_tip'";

    $total_si_cero = 0;
    $total_ventas_result = $this->connection->query($total_ventas)->result();

    foreach ($total_ventas_result as $dat1) {
    $impuesto = round($dat1->impuesto);
    $total_descuento = round($dat1->total_descuento);
    $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
    }

    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);

    $abonos['plan_separe'][] = array(
    'numero' => $value1->numero,
    'hora_movimiento' => $value1->hora_movimiento,
    'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
    'username' => $username,
    //'impuesto' => $impuesto,
    'impuesto' => 0,
    'total_descuento' => $total_descuento,
    'forma_pago' => $formpago1,
    'anulada' => $value1->anulada
    );
    }

    //pagos a creditos
    $sql = "SELECT v.id, v.factura AS numero, mc.forma_pago AS forma_pago, mc.hora_movimiento AS hora_movimiento, mc.valor as valor , mc.id_usuario AS id_usuario, mc.id_mov_tip AS id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc LEFT JOIN pago p ON mc.id_mov_tip = p.`id_pago` LEFT JOIN venta v ON p.`id_factura` = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE mc.Id_cierre IN('".join("','", $facturas)."') AND mc.tipo_movimiento IN ('entrada_venta') AND tabla_mov = 'pago' ORDER BY numero";
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
    INNER JOIN detalle_venta on venta.id = detalle_venta.venta_id
    WHERE venta.id = '$value1->id_mov_tip'";

    $total_si_cero = 0;
    $total_ventas_result = $this->connection->query($total_ventas)->result();

    foreach ($total_ventas_result as $dat1) {
    $impuesto = round($dat1->impuesto);
    $total_descuento = round($dat1->total_descuento);
    $total_si_cero += round($dat1->total_precio_venta) - round($dat1->total_descuento);
    }

    $formpago1 = str_replace("_", " ", $value1->forma_pago);
    $formpago1 = ucfirst($formpago1);
    $abonos['creditos'][] = array(
    'numero' => $value1->numero,
    'hora_movimiento' => $value1->hora_movimiento,
    'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
    'username' => $username,
    //'impuesto' => $impuesto,
    'impuesto' => 0,
    'total_descuento' => $total_descuento,
    'forma_pago' => $formpago1,
    'anulada' => $value1->anulada
    );
    }
    #-----------------------------------------------------------------------
    #Gastos
    #-----------------------------------------------------------------------
    $query = "SELECT sum(valor) as total FROM movimientos_cierre_caja where  tabla_mov = 'pago_orden_compra' and Id_cierre  IN('".join("','", $facturas)."') and forma_pago like '%efectivo%'   ";
    $pago_proveedores = $this->connection->query($query)->result_array();

    // Listamos los gastos que fueron anulados
    $query = "SELECT IFNULL((
    SELECT
    SUM(mcc.valor) AS total
    FROM movimientos_cierre_caja AS mcc
    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
    WHERE mcc.tabla_mov = 'proformas'
    AND mcc.Id_cierre IN('".join("','", $facturas)."')
    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
    AND pf.notas LIKE '%eliminadoIn%'
    ),0)AS dentro,
    IFNULL((
    SELECT
    SUM(mcc.valor) AS total
    FROM movimientos_cierre_caja AS mcc
    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
    WHERE mcc.tabla_mov = 'proformas'
    AND mcc.Id_cierre IN('".join("','", $facturas)."')
    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
    AND pf.notas LIKE '%eliminadoOut%'
    ),0)AS fuera";

    $gastos_cancelados = $this->connection->query($query)->result_array();

    // Separamos los gastos segun el tipo ( Banco, Tarjeta de credito, caja menor o caja registradora)
    // No restamos los gastos que fueron eliminados fuera del cierre de caja = Campo notas => eliminadoOut
    // Esto es solo informativo, no restaran nada al cierre de caja
    $query = "
    SELECT cd.tipo_cuenta,
    SUM(mcc.valor) AS total
    FROM movimientos_cierre_caja AS mcc
    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
    WHERE mcc.tabla_mov = 'proformas'
    AND mcc.Id_cierre IN('".join("','", $facturas)."')
    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
    AND pf.notas NOT LIKE '%eliminadoIn%'
    GROUP BY cd.tipo_cuenta;
    ";
    $pago_gastos_by_tipo = $this->connection->query($query)->result_array();

    $query = "
    SELECT
    SUM(mcc.valor) AS total
    FROM movimientos_cierre_caja AS mcc
    INNER JOIN proformas AS pf ON pf.id_proforma = mcc.id_mov_tip
    LEFT JOIN cuentas_dinero AS cd ON cd.id = pf.id_cuenta_dinero
    WHERE mcc.tabla_mov = 'proformas'
    AND mcc.Id_cierre  IN('".join("','", $facturas)."')
    AND mcc.forma_pago NOT IN ('Credito','Saldo_a_Favor','Gift_Card')
    AND pf.id_almacen = (SELECT almacen_id FROM usuario_almacen WHERE usuario_id = mcc.id_usuario LIMIT 1)
    AND cd.tipo_cuenta IN ('Caja menor','Caja registradora')
    AND pf.notas NOT LIKE '%eliminadoIn%'
    ";

    $resultQuery = $this->connection->query($query)->row()->total;
    $gastos_descuentan_caja = $resultQuery == "" ? 0 : $resultQuery;

    $gastos = array(
    'pago_gastos_by_tipo' => $pago_gastos_by_tipo,
    'pago_proveedores' => $pago_proveedores,
    'gastos_cancelados' => $gastos_cancelados,
    'gastos_descuentan_caja' => $gastos_descuentan_caja
    );
    #-----------------------------------------------------------------------

    $data = array(
    'almacen_nombre' => $usuario->almacen_nombre,
    'caja_nombre' => $usuario->caja_nombre,
    'username' => $usuario->username,
    'fecha_apertura' => $numero_menor->fecha,
    'hora_apertura' => $numero_menor->hora_apertura,
    'factura_inicial' => $numero_menor->numero_factura,
    'fecha_cierre' => (isset($numero_mayor->fecha_cierre) ? $numero_mayor->fecha_cierre : $numero_mayor->fecha),
    'hora_cierre' => $numero_mayor->hora_cierre,
    'factura_final' => $numero_mayor->numero_factura,
    'total' => $total,
    'ventas' => $ventas,
    'anuladas' => $anuladas,
    'devoluciones' => $devoluciones,
    'devoluciones_p' => $devoluciones_pendientes,
    'formas_pago' => $formas_pago,
    'cierre' => $cierre,
    'abonos' => $abonos,
    'gastos' => $gastos
    );

    else:
    $data = array();
    endif;

    return $data;*/
    }

    public function get_id_caja_en_cierre_caja($where, $orderby = null, $limit_cierre = null)
    {
        if (!empty($orderby)) {
            $this->connection->order_by($orderby);
        }
        if (!empty($limit_cierre)) {
            $this->connection->limit($limit_cierre);
        }
        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('cierres_caja')->row();
        return $query;
    }

    public function add_campo_fecha_fin_cierre()
    {
        $sql = "SHOW COLUMNS FROM cierres_caja LIKE 'fecha_fin_cierre'";
        $field = $this->connection->query($sql)->result();

        if (count($field) <= 0) {
            $sql = "ALTER TABLE `cierres_caja` ADD `fecha_fin_cierre` TIMESTAMP NULL DEFAULT NULL AFTER fecha;";
            $this->connection->query($sql);
        }
    }

    public function add_campo_arqueo()
    {
        $sql = "SHOW COLUMNS FROM cierres_caja LIKE 'arqueo'";
        $field = $this->connection->query($sql)->result();

        if (count($field) <= 0) {
            $sql = "ALTER TABLE `cierres_caja` ADD `arqueo` double NULL DEFAULT NULL";
            $this->connection->query($sql);
        }
    }
    /**
     * @method  add_campo_consecutivo_cierre_caja()
     *  agregar el campo del consecutivo del cierre de caja en la tabla cierre_caja
     * @author [Dairinet Avila]
     */
    public function add_campo_consecutivo_cierre_caja()
    {
        $sql = "SHOW COLUMNS FROM cierres_caja LIKE 'consecutivo'";
        $field = $this->connection->query($sql)->result();

        if (count($field) <= 0) {
            $sql = "ALTER TABLE `cierres_caja` ADD `consecutivo` int NULL DEFAULT NULL";
            $this->connection->query($sql);
        }
    }

    /**
     * @method  getBox($id)
     *  Función para consultar una caja por Id
     *  @param Int $id
     * @author [José Fernnado]
     * @return array
     */
    public function getBox($id)
    {
        $this->connection->select("*");
        $this->connection->from("cajas");
        $this->connection->where("id", $id);
        $this->connection->limit(1);
        $result = $this->connection->get();

        return $result->result()[0];
    }

    /**
     * @method  saveBox()
     *  Función para guardar una caja
     * @param Int <id>,String<name>,Int <store>,
     * @author [José Fernnado]
     * @return array
     */
    public function saveBox($id, $name, $store)
    {

        $response = "success";
        $this->connection->select("*");
        $this->connection->from("cajas");
        $this->connection->where("nombre", $name);
        $this->connection->where("id_Almacen", $store);
        $result = $this->connection->get();

        if ($result->num_rows() > 0):
            $response = "duplicate";
        else:
            $data = array(
                "nombre" => $name,
            );

            $this->connection->where('id', $id);
            $this->connection->update('cajas', $data);

            if ($this->connection->affected_rows() <= 0):
                $response = "error";
            endif;
        endif;

        return array("message" => $response);
    }

    public function obtenerVentasAnuladas($date)
    {

        if (!$date) {
            $date_ini = "'" . date("Y-m-d", (strtotime("-5 Hours"))) . " 00:00:00'";
            $date_end = "'" . date("Y-m-d", (strtotime("-5 Hours"))) . " 23:59:59'";
            $between = $date_ini . ' AND ' . $date_end;
        } else {
            $date_ini = "'$date  00:00:00'";
            $date_end = "'$date  23:59:59'";
            $between = $date_ini . ' AND ' . $date_end;
        }
        $sql = "SELECT v.id, mc.numero as numero, mc.forma_pago as forma_pago, mc.hora_movimiento as hora_movimiento, mc.valor as valor ,
        mc.id_usuario as id_usuario, mc.id_mov_tip as id_mov_tip, IF(ISNULL(va.fecha), 0, 1) AS anulada FROM movimientos_cierre_caja mc
        LEFT JOIN venta v ON mc.id_mov_tip = v.id LEFT JOIN ventas_anuladas va ON v.id = va.venta_id WHERE (va.fecha BETWEEN $between) AND
        mc.tipo_movimiento IN ('anulada') AND mc.tabla_mov IN ('anulada') AND mc.numero <> '' AND estado = '-1' ORDER BY numero;";

        $data = array();
        $total = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $total += $value1->valor;
            $sql1 = "SELECT username  FROM users where id = '$value1->id_usuario' ";
            foreach ($this->db->query($sql1)->result() as $value2) {
                $username = $value2->username;
            }

            $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia,
                    if(nombre_producto='PROPINA' && (codigo_producto is null),precio_venta,0) as propina,
	 			    (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` AS impuesto,
	 			    (`precio_venta` - `descuento`) * `unidades`  AS total_precio_venta,
	  			  `unidades` * `descuento`  AS total_descuento,
	  			  `descripcion_producto`  AS sobrecosto,
                    impuesto as porcentaje_impuesto
	 			FROM  `venta`
	 			INNER JOIN detalle_venta on venta.id = detalle_venta.venta_id
	 			WHERE venta.id = '$value1->id_mov_tip'";
            //echo $total_ventas;
            $total_si_cero = 0;
            $total_ventas_result = $this->connection->query($total_ventas)->result();
            $impuesto = 0;
            $total_descuento = 0;
            $porcentaje_impuesto = 0;
            $total_base_productos = 0;
            $propina_total = 0;
            foreach ($total_ventas_result as $dat1) {
                $impuesto += $this->opciones->redondear($dat1->impuesto);
                $total_descuento += $this->opciones->redondear($dat1->total_descuento);
                $total_base_productos += $this->opciones->redondear($dat1->total_precio_venta);
                $propina_total += $dat1->propina;
                if ($dat1->porcentaje_impuesto > 0) {
                    $porcentaje_impuesto = $this->opciones->redondear($dat1->porcentaje_impuesto);
                }

            }
            //decimales? decimales_moneda
            $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda' LIMIT 1 ";
            $ocpresult = $this->connection->query($ocp)->result();
            foreach ($ocpresult as $dat) {
                $decimales_moneda = $dat->valor_opcion;
            }

            //echo $decimales_moneda;
            //die();
            if ($decimales_moneda == 0) {
                $impuesto = round($impuesto);
                $total_descuento = round($total_descuento);
                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);
                $total_base_productos = round($total_base_productos);
                $propina_total = round($propina_total);
            } else {
                $impuesto = ($impuesto);
                $total_descuento = ($total_descuento);
                $formpago1 = str_replace("_", " ", $value1->forma_pago);
                $formpago1 = ucfirst($formpago1);
                $total_base_productos = ($total_base_productos);
                $propina_total = ($propina_total);
            }
            //verificar si es el mismo monto en los pagos
            $sqlventa_pago = "select SUM(valor_entregado-cambio) AS valor from ventas_pago where id_venta='$value1->id_mov_tip' and forma_pago='$value1->forma_pago'";
            $sqlventa_pago = $this->connection->query($sqlventa_pago)->result();
            $valorp = $value1->valor;

            //if($sqlventa_pago[0]->valor!=$value1->valor){
            if ($sqlventa_pago[0]->valor <= $value1->valor) {
                $valorp = $sqlventa_pago[0]->valor;
                //mensaje para que llegue al slack
                //$mensaje="El usuario con id=".$this->session->userdata('user_id')." de la bd=".$this->session->userdata('base_dato')." tiene un valor incorrecto en el movimiento cierre de caja con id_cierre =".$id." en el id_mov_tip=".$value1->id_mov_tip." para la forma de pago=".$value1->forma_pago." y el valor=".$value1->valor." en el almacén=".$almacen;
                //slack($mensaje);
            }

            $username = ($username != "") ? $username : "No existe usuario";
            $data[] = array(
                'numero' => $value1->numero,
                'hora_movimiento' => $value1->hora_movimiento,
                //'valor' => $value1->anulada ? $total_si_cero : $value1->valor,
                //'valor' => $value1->valor,
                'valor' => $valorp,
                'username' => $username,
                'impuesto' => $impuesto,
                'total_descuento' => $total_descuento,
                'forma_pago' => $formpago1,
                'anulada' => $value1->anulada,
                'porcentaje_impuesto' => $porcentaje_impuesto,
                'base_productos' => $total_base_productos,
                'propina' => $propina_total,
            );
        }

        return array(
            'data' => $data,
            'total' => $total,
        );

    }

    public function obtenerDevoluciones($date, $almacen)
    {
        if (!$date) {
            $date_ini = "'" . date("Y-m-d", (strtotime("-5 Hours"))) . " 00:00:00'";
            $date_end = "'" . date("Y-m-d", (strtotime("-5 Hours"))) . " 23:59:59'";
            $between = $date_ini . ' AND ' . $date_end;
        } else {
            $date_ini = "'$date  00:00:00'";
            $date_end = "'$date  23:59:59'";
            $between = $date_ini . ' AND ' . $date_end;
        }
        $warehouse = '';
        if ($almacen) {
            $warehouse = "AND id_Almacen = $almacen";
        }
        $cierres_sql = "SELECT * FROM cierres_caja where (fecha BETWEEN $between) $warehouse";

        //dd($cierres_sql);

        $cierres_caja = $this->connection->query($cierres_sql)->result();

        $devoluciones = array();

        $total = 0;

        foreach ($cierres_caja as $key => $cierre) {
            //movimientos cierres de caja

            $movimientos_cc_sql = "SELECT * FROM movimientos_cierre_caja where tipo_movimiento = 'entrada_devolucion' AND Id_cierre = $cierre->id";

            $movimientos_cc = $this->connection->query($movimientos_cc_sql)->result();

            foreach ($movimientos_cc as $key => $movimiento) {
                $devoluciones[] = $movimiento;
                $total += $movimiento->valor;
            }

        }

        return array(
            'data' => $devoluciones,
            'total' => $total,
        );
    }
}

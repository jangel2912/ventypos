<?php

class Credito_model extends CI_Model
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

    public function get_total_pendientes()
    {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  facturas where estado = '0'");

        return $query->row()->cantidad;

    }

    public function get_total_pagadas()
    {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  facturas where estado = '1'");

        return $query->row()->cantidad;

    }

    public function get_total()
    {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  facturas");

        return $query->row()->cantidad;

    }
    //Jeisson Rodriguez (06/06/2019) function para contar los registros de los creditos
    public function countAll()
    {

        //Jeisson Rodriguez - (06/06/2019) - rangos de fechas
        $BETWEEN = "";
        if ($_GET['fecha_inicial'] || $_GET['fecha_final']) {
            $fecha_inicial = $_GET['fecha_inicial'];
            $fecha_final = $_GET['fecha_final'];
            $BETWEEN = "AND venta.fecha between '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $this->load->model('Opciones_model', 'opciones');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " where forma_pago = 'Credito'  AND venta.estado = 0   " . $BETWEEN;
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
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

            $sWhere = " where forma_pago = 'Credito'  and almacen_id = '$almacen'  AND venta.estado = 0 " . $BETWEEN;
        }

        $sql = "SELECT factura, nombre_comercial,total_venta,sum(pago.importe_retencion) as retencion, sum(pago.cantidad) as saldo, fecha, venta.id
                    FROM  ventas_pago
                    left join venta on id_venta = venta.id
                    left join clientes on clientes.id_cliente = venta.cliente_id
                    left join pago on pago.id_factura = venta.id
                    $sWhere group by venta.id";

        $data = array();
        $decimales = $this->opciones_model->getDataMoneda();

        foreach ($this->connection->query($sql)->result() as $value) {

            $total_venta = 0;
            $sql2 = "SELECT SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                        FROM detalle_venta as dv where venta_id = '$value->id'
				";
            foreach ($this->connection->query($sql2)->result() as $value2) {
                // $total_venta =  ($value2->total_precio_venta - $value2->total_descuento) + round($value2->impuesto,$decimales->decimales);
                $total_venta = round((($value2->total_precio_venta - $value2->total_descuento) + ($value2->impuesto)), $decimales->decimales);
            }
            $saldoPagado = round($value->saldo + $value->retencion, $decimales->decimales);

            if ($decimales->decimales == 0) {
                if ($saldoPagado == 0 || $total_venta > $saldoPagado) {
                    if ($total_venta - $value->saldo > 49) {
                        $data[] = array(
                            $value->factura,
                            $value->nombre_comercial,
                            $this->opciones->formatoMonedaMostrar($total_venta),
                            $this->opciones->formatoMonedaMostrar($value->retencion),
                            $this->opciones->formatoMonedaMostrar($total_venta - $saldoPagado),
                            $value->fecha,
                            $value->id,
                        );
                    }
                }
            } else {
                if ($saldoPagado == 0 || $total_venta > $saldoPagado) {
                    $data[] = array(
                        $value->factura,
                        $value->nombre_comercial,
                        $this->opciones->formatoMonedaMostrar($total_venta),
                        $this->opciones->formatoMonedaMostrar($value->retencion),
                        $this->opciones->formatoMonedaMostrar($total_venta - $saldoPagado),
                        $value->fecha,
                        $value->id,
                    );
                }
            }

        }
        return count($data);
    }

    public function get_ajax_data($estado = 0)
    {

        $BETWEEN = "";

        if (isset($_GET['fecha_inicial'])) {
            $fecha_inicial = $_GET['fecha_inicial'];
        } else {
            $fecha_inicial = date("Y-m-d", strtotime("yesterday"));
        }

        if (isset($_GET['fecha_final'])) {
            $fecha_final = $_GET['fecha_final'];
        } else {
            $ahora = time();
            $manana = strtotime("+1 day", $ahora);
            $fecha_final = date("Y-m-d", $manana);
        }
        $BETWEEN = "AND venta.fecha between '" . $fecha_inicial . "' AND '" . $fecha_final . "'";

        $searchParameters = "";
        $limit = "";
        if (isset($_GET['sSearch'])) {
            $sSearch = $_GET['sSearch'];
            $iDisplayStart = $_GET['iDisplayStart'];
            $iDisplayLength = $_GET['iDisplayLength'];
            if ($iDisplayStart || $iDisplayLength) {$limit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;} else { $limit = "LIMIT 0, 5 ";}
            if ($sSearch) {$searchParameters = "AND (factura like '%" . $sSearch . "%' OR nombre_comercial LIKE '%" . $sSearch . "%' OR total_venta LIKE '%" . $sSearch . "%' OR fecha LIKE '%" . $sSearch . "%')";}
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $this->load->model('Opciones_model', 'opciones');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " where forma_pago = 'Credito'  AND venta.estado = 0   " . $BETWEEN;
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
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

            $sWhere = " where forma_pago = 'Credito'  and almacen_id = '$almacen'  AND venta.estado = 0 " . $BETWEEN;
        }

        $sql = "SELECT factura, nombre_comercial,total_venta,sum(pago.importe_retencion) as retencion, sum(pago.cantidad) as saldo, fecha, fecha_vencimiento,  venta.id
                    FROM  ventas_pago
                    left join venta on id_venta = venta.id
                    left join clientes on clientes.id_cliente = venta.cliente_id
                    left join pago on pago.id_factura = venta.id
                    $sWhere $searchParameters group by venta.id $limit";

        $data = array();
        $decimales = $this->opciones_model->getDataMoneda();

        foreach ($this->connection->query($sql)->result() as $value) {

            $total_venta = 0;
            $sql2 = "SELECT SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                        FROM detalle_venta as dv where venta_id = '$value->id'
				";
            foreach ($this->connection->query($sql2)->result() as $value2) {
                // $total_venta =  ($value2->total_precio_venta - $value2->total_descuento) + round($value2->impuesto,$decimales->decimales);
                $total_venta = bcdiv((($value2->total_precio_venta - $value2->total_descuento) + ($value2->impuesto)), 1, $decimales->decimales);
            }
            $saldoPagado = round($value->saldo + $value->retencion, $decimales->decimales);

            if ($decimales->decimales == 0) {
                if ($saldoPagado == 0 || $total_venta > $saldoPagado) {
                    if ($total_venta - $value->saldo > 49) {
                        $data[] = array(
                            $value->factura,
                            $value->nombre_comercial,
                            $this->opciones->formatoMonedaMostrar($total_venta),
                            $this->opciones->formatoMonedaMostrar($value->retencion),
                            $this->opciones->formatoMonedaMostrar($total_venta - $saldoPagado),
                            $value->fecha,
                            $value->fecha_vencimiento,
                            $value->id,
                        );
                    }
                }
            } else {
                if ($saldoPagado == 0 || $total_venta > $saldoPagado) {
                    $data[] = array(
                        $value->factura,
                        $value->nombre_comercial,
                        $this->opciones->formatoMonedaMostrar($total_venta),
                        $this->opciones->formatoMonedaMostrar($value->retencion),
                        $this->opciones->formatoMonedaMostrar($total_venta - $saldoPagado),
                        $value->fecha,
                        $value->fecha_vencimiento,
                        $value->id,
                    );
                }
            }

        }
        return array(
            'aaData' => $data,
            'recordsTotal' => 57,
            'recordsFiltered' => 57,
        );

    }

    public function get_ajax_data_pagadas($estado = 0)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $decimales = $this->opciones_model->getDataMoneda();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " where forma_pago = 'Credito'  AND venta.estado = 0   ";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
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

            $sWhere = " where forma_pago = 'Credito'  and almacen_id = '$almacen' AND venta.estado = 0    ";
        }

        $sql = "SELECT factura, nombre_comercial, total_venta,(SUM(pago.cantidad) + SUM(pago.importe_retencion)) as saldo, fecha, venta.id

				FROM  ventas_pago

		left join venta on id_venta = venta.id

		left join clientes on clientes.id_cliente = venta.cliente_id

		left join pago on pago.id_factura = venta.id

		$sWhere group by venta.id
		";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {

            $total_venta = 0;
            $sql2 = "SELECT SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                FROM detalle_venta as dv where venta_id = '$value->id'
				";
            foreach ($this->connection->query($sql2)->result() as $value2) {
                //$total_venta =  ($value2->total_precio_venta - $value2->total_descuento) + round($value2->impuesto,$decimales->decimales);
                $total_venta = round((($value2->total_precio_venta - $value2->total_descuento) + ($value2->impuesto)), $decimales->decimales);
            }

            if (round($value->saldo, $decimales->decimales) >= $total_venta) {
                $data[] = array(
                    $value->factura,
                    $value->nombre_comercial,
                    $this->opciones_model->formatoMonedaMostrar($total_venta),
                    $this->opciones_model->formatoMonedaMostrar($value->saldo),
                    $value->fecha,
                    $value->id,
                );
            }

        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_max_cod()
    {

        $query = $this->connection->query("SELECT MAX(RIGHT(numero,6)) as cantidad FROM  facturas");

        return $query->row()->cantidad;

    }

    public function get_all_pagadas($offset)
    {

        $query = $this->connection->query("SELECT * FROM facturas f Inner Join clientes c

												On f.id_cliente = c.id_cliente WHERE f.estado = '1'

												ORDER BY f.id_factura DESC LIMIT $offset, 8");

        return $query->result();

    }

    public function get_sum_pagadas()
    {

        $query = $this->connection->query("SELECT sum(monto) as cantidad FROM facturas WHERE estado = '1'");

        return $query->row()->cantidad;

    }

    public function get_all_pendientes($offset)
    {
        $query = $this->connection->query("
			SELECT  * FROM ventas_pago
			inner join venta v on id_venta = v.id
			Inner Join clientes c On v.cliente_id = c.id_cliente
			where forma_pago = 'Credito'  ORDER BY v.id  DESC LIMIT $offset, 15;
		");

        return $query->result();
    }

    public function get_sum_pendientes()
    {

        $query = $this->connection->query("SELECT sum(monto) as cantidad FROM facturas WHERE estado = '0'");

        return $query->row()->cantidad;

    }

    public function get_by_id($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM facturas f Inner Join clientes c On f.id_cliente = c.id_cliente

													where f.id_factura = '" . $id . "'

														ORDER BY f.id_factura DESC ");

        return $query->row_array();
    }

    public function get_detail($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM facturas_detalles inner join productosf on productosf.id_producto = facturas_detalles.fk_id_producto

										WHERE  id_factura = '" . $id . "' ORDER BY id_factura_detalle ASC");

        return $query->result_array();
    }

    public function get_term()
    {
        $q = $this->input->post("q");
        $fi = $this->input->post("fi");
        $ff = $this->input->post("ff");
        $t = $this->input->post("t");

        if ($fi != '' && $ff != '') {
            $wfecha = " AND f.fecha BETWEEN '" . $fi . "' AND '" . $ff . "'";
        }

        if ($q != '') {
            $wcl = " AND c.id_cliente = '" . $q . "'";
        }

        $query = $this->connection->query("SELECT f.id_factura id, f.numero, c.nombre_comercial, f.monto,

												DATE_FORMAT(f.fecha , '%d/%m/%Y') fecha

												FROM facturas f, clientes c

												WHERE f.id_cliente = c.id_cliente

													AND f.estado = '$t'

													" . $wfecha . "

													" . $wcl . "

												ORDER BY f.id_factura DESC");

        return $query->result();

    }

    public function add()
    {

        $array_datos = array(

            "id_cliente" => $this->input->post('id_cliente'),

            "numero" => $this->input->post('numero'),

            "monto" => $this->input->post('input_total_civa'),

            "monto_siva" => $this->input->post('monto_siva'),

            "monto_iva" => $this->input->post('monto_iva'),

            "fecha" => $this->input->post('fecha'),

            "estado" => 0,

            "fecha_v" => $this->input->post('fecha_v'),

        );

        $this->connection->insert("facturas", $array_datos);

        $id = $this->connection->insert_id();

        $return_array_datos = $array_datos;

        $return_array_datos['id_factura'] = $id;

        $data_detalles = array();

        foreach ($_POST['productos'] as $value) {

            $data_detalles[] = array(

                'id_factura' => $id

                , 'fk_id_producto' => $value['fk_id_producto']

                , 'precio' => $value['precio']

                , 'cantidad' => $value['cantidad']

                , 'descuento' => $value['descuento']

                , 'impuesto' => $value['impuesto']

                , 'descripcion_d' => $value['descripcion'],

            );

        }

        $this->connection->insert_batch("facturas_detalles", $data_detalles);

        return $return_array_datos;

    }

    public function update()
    {

        $array_datos = array(

            "estado" => $this->input->post('estado'),

        );

        $this->connection->where('id_factura', $this->input->post('id_factura'));

        $this->connection->update("facturas", $array_datos);

    }

    public function paypal_update($numero, $estado)
    {

        $array_datos = array(

            "estado" => $estado,

        );

        $this->connection->where('numero', $numero);

        $this->connection->update("facturas", $array_datos);

    }

    public function delete($id)
    {

        $this->connection->where('id', $id);

        $this->connection->delete("venta");

    }

    public function excel()
    {

        $this->connection->select("facturas.* , facturas_detalles.descripcion, facturas_detalles.precio, facturas_detalles.cantidad, facturas_detalles.impuesto , clientes.nif_cif, clientes.nombre_comercial, clientes.email, pagos.fecha_pago, pagos.cantidad, pagos.tipo, pagos.notas");

        $this->connection->from("facturas");

        $this->connection->join("clientes", "clientes.id_cliente = facturas.id_cliente");

        $this->connection->join("facturas_detalles", " facturas.id_factura = facturas_detalles.id_factura", 'left');

        $this->connection->join("pagos", "facturas.id_factura = pagos.id_factura", 'left');

        $query = $this->connection->get();

        return $query->result();

    }

    public function excel_exist($numero)
    {

        $this->connection->where("numero", $numero);

        $this->connection->from("facturas");

        $this->connection->select("*");

        $flag = false;

        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            $flag = true;

        }

        $query->free_result();

        return $flag;

    }

    public function excel_add($array_datos)
    {

        $this->connection->insert("facturas", $array_datos);

    }

}

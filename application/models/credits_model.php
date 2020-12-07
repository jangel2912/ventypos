<?php

class Credits_model extends CI_Model
{
    public $connection;

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    public function getCredits()
    {

        $BETWEEN = "";

        if ($_GET['fecha_inicial'] || $_GET['fecha_final']) {
            $fecha_inicial = $_GET['fecha_inicial'];
            $fecha_final = $_GET['fecha_final'];
            $BETWEEN = "AND v.fecha between '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $this->load->model('Opciones_model', 'opciones');
        $decimales = $this->opciones_model->getDataMoneda();

        if ($is_admin == 't' || $is_admin == 'a') {
            $sWhere = " where vp.forma_pago = 'Credito'  AND v.estado = 0   " . $BETWEEN;
        } else {
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

            $sWhere = " where vp.forma_pago = 'Credito'  and v.almacen_id = '$almacen'  AND v.estado = 0   " . $BETWEEN;
        }

        $sql = " SELECT c.nombre_comercial,c.email,c.nif_cif,v.fecha,v.`cliente_id` as id_client,GROUP_CONCAT(DISTINCT(v.id) SEPARATOR ',') AS facturas
        FROM  ventas_pago vp
        LEFT JOIN venta v ON vp.id_venta = v.id
        LEFT JOIN detalle_venta dv ON dv.venta_id = v.id
        LEFT JOIN clientes c ON c.id_cliente = v.cliente_id
        $sWhere GROUP BY v.cliente_id";

        $result = $this->connection->query($sql);
        $data_client = array();

        if ($result->num_rows() > 0):
            foreach ($result->result() as $client):
                $ventas = 0;
                $abonos = 0;
                $retencion = 0;
                $pendiente = 0;
                $propina = 0;

                $invoices = explode(",", $client->facturas);
                $cantidad_facturas = count($invoices);
                $ultima_factura;

                foreach ($invoices as $invoice) {
                    if (!empty($invoice)) {

                        /* Total de la venta*/
                        $venta = 0;
                        $sql_venta = "SELECT SUM( dv.unidades * dv.descuento ) AS total_descuento,
						                                SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
						                                SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
						                                FROM detalle_venta as dv where venta_id = $invoice AND nombre_producto <> 'PROPINA'
						                        ";
                        foreach ($this->connection->query($sql_venta)->result() as $value2) {
                            $venta = round((($value2->total_precio_venta - $value2->total_descuento) + ($value2->impuesto)), $decimales->decimales);
                            $ventas += $venta;
                        }

                        /* Abonos y retención */
                        $this->connection->select("SUM(p.importe_retencion) as retencion, SUM(p.cantidad) as abonos");
                        $this->connection->from("pago p");
                        $this->connection->where("p.id_factura", $invoice);
                        $result = $this->connection->get();

                        if ($result->num_rows() > 0):
                            $result_abonos = $result->row();
                            if (($result_abonos->retencion + $result_abonos->abonos) > $venta):
                                $abonos += $venta;
                            else:
                                $retencion += $result_abonos->retencion;
                                $abonos += $result_abonos->abonos;
                            endif;
                        endif;
                        /* propina */
                        $this->connection->select("precio_venta as propina");
                        $this->connection->from("detalle_venta dv");
                        $this->connection->where("dv.venta_id", $invoice);
                        $this->connection->where("dv.nombre_producto", "PROPINA");
                        $this->connection->limit(1);
                        $result = $this->connection->get();

                        if ($result->num_rows() > 0) {
                            $result_propina = $result->row();
                            $propina += $result_propina->propina;
                        }
                    }
                }

                $retencion = ($retencion < 0) ? ($retencion + $propina) : $retencion;
                $abonos = ($abonos > $ventas) ? ($abonos - $propina) : $abonos;
                $pendiente = (($ventas - ($abonos + $retencion)) < 0) ? 0 : $ventas - ($abonos + $retencion);
                $url = site_url('credits/customer') . '/' . $client->id_client;
                $data_client[] = array(
                    '<a href="' . $url . '">' . $client->nombre_comercial . '</a>',
                    $client->email,
                    $client->nif_cif,
                    $cantidad_facturas,
                    $this->opciones->formatoMonedaMostrar($ventas),
                    $this->opciones->formatoMonedaMostrar($retencion),
                    $this->opciones->formatoMonedaMostrar($abonos),
                    $this->opciones->formatoMonedaMostrar($pendiente),
                    $client->fecha,
                    $client->id_client,
                );
            endforeach;
        endif;

        return array(
            'aaData' => $data_client,
        );
    }

    public function getInvoicesByClient($idCustomer)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $this->load->model('Opciones_model', 'opciones');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " where forma_pago = 'Credito'  AND venta.estado = 0 AND clientes.id_cliente = '$idCustomer' ";
        }

        if ($is_admin != 't' && $is_admin != 'a') {
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
            $sWhere = " where forma_pago = 'Credito'  and almacen_id = '$almacen'  AND venta.estado = 0  AND clientes.id_cliente = '$idCustomer'  ";
        }

        $sql = "SELECT factura,
                nombre_comercial,
                total_venta,
                sum(pago.importe_retencion) as retencion,
                sum(pago.cantidad) as saldo,
                fecha, fecha_vencimiento,
                venta.id FROM  ventas_pago
                left join venta on id_venta = venta.id
                left join clientes on clientes.id_cliente = venta.cliente_id
                left join pago on pago.id_factura = venta.id $sWhere
                group by venta.id";

        $data = array();
        $decimales = $this->opciones_model->getDataMoneda();

        foreach ($this->connection->query($sql)->result() as $value) {
            $total_venta = 0;
            $sql2 = "SELECT SUM( dv.unidades * dv.descuento ) AS total_descuento,
                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                    FROM detalle_venta as dv where venta_id = '$value->id' AND nombre_producto <> 'PROPINA'
            ";

            foreach ($this->connection->query($sql2)->result() as $value2) {
                $total_venta = round((($value2->total_precio_venta - $value2->total_descuento) + ($value2->impuesto)), $decimales->decimales);
            }

            /* Abonos y retención */
            $saldoPagado = 0;
            $this->connection->select("SUM(p.importe_retencion) as retencion, SUM(p.cantidad) as abonos");
            $this->connection->from("pago p");
            $this->connection->where("p.id_factura", $value->id);
            $result = $this->connection->get();
            if ($result->num_rows() > 0) {
                $result_abonos = $result->row();
                $saldoPagado = round($result_abonos->abonos + $result_abonos->retencion, $decimales->decimales);
            }
            /* Abonos y retención */
            //$saldoPagado =  round($value->saldo + $value->retencion,$decimales->decimales);

            if ($saldoPagado == 0 || $total_venta > $saldoPagado) {
                $data[] = array(
                    'invoice' => $value->factura,
                    'client' => $value->nombre_comercial,
                    'totalSale' => $this->opciones->formatoMonedaMostrar($total_venta),
                    'retention' => $this->opciones->formatoMonedaMostrar($value->retencion),
                    'totalPending' => $this->opciones->formatoMonedaMostrar($total_venta - $saldoPagado),
                    'date' => $value->fecha,
                    'expiration_date' => $value->fecha_vencimiento,
                    'id' => $value->id,
                );
            }
        }
        return $data;
    }

    public function loadAccountStatus($idCustomer)
    {
        $data = array();

        /* client */
        $this->connection->select("*");
        $this->connection->from("clientes c");
        $this->connection->where("c.id_cliente", $idCustomer);
        $this->connection->limit("1");
        $result = $this->connection->get();

        $data["identification"] = ($result->num_rows() > 0 && !empty($result->result()[0]->nif_cif)) ? $result->result()[0]->nif_cif : 'No encontrado';
        $data["name"] = ($result->num_rows() > 0) ? $result->result()[0]->nombre_comercial : 'No encontrado';
        $data["email"] = ($result->num_rows() > 0 && !empty($result->result()[0]->email)) ? $result->result()[0]->email : 'No encontrado';
        $data["address"] = ($result->num_rows() > 0 && !empty($result->result()[0]->direccion)) ? $result->result()[0]->direccion : 'No encontrado';
        $data["telephone"] = ($result->num_rows() > 0 && !empty($result->result()[0]->telefono)) ? $result->result()[0]->telefono : 'No encontrado';

        //Ultima factura
        $this->connection->select("*");
        $this->connection->from("ventas_pago vp");
        $this->connection->join("venta v", "vp.id_venta = v.id");
        $this->connection->join("clientes c", "v.cliente_id = c.id_cliente");
        $this->connection->where("c.id_cliente", $idCustomer);
        $this->connection->where("vp.forma_pago", "Credito");
        $this->connection->limit("1");
        $this->connection->order_by("v.id", "desc");
        $result = $this->connection->get();

        $data["last_bill"] = ($result->num_rows() > 0 && !empty($result->result()[0]->factura)) ? $result->result()[0]->factura : 'No encontrado';

        //Ultimo abono
        $this->connection->select("*");
        $this->connection->from("ventas_pago vp");
        $this->connection->join("venta v", "vp.id_venta = v.id");
        $this->connection->join("clientes c", "v.cliente_id = c.id_cliente");
        $this->connection->join("pago p", "p.id_factura = v.id");
        $this->connection->where("c.id_cliente", $idCustomer);
        $this->connection->where("vp.forma_pago", "Credito");
        $this->connection->limit("1");
        $this->connection->order_by("p.id_pago", "desc");
        $result = $this->connection->get();

        $data["last_payment"] = ($result->num_rows() > 0 && !empty($result->result()[0]->cantidad)) ? $result->result()[0]->cantidad : 'No encontrado';

        //Facturas pendientes
        $this->connection->select("COUNT(v.id) AS total");
        $this->connection->from("ventas_pago vp");
        $this->connection->join("venta v", "vp.id_venta = v.id");
        $this->connection->join("clientes c", "v.cliente_id = c.id_cliente");
        $this->connection->where("c.id_cliente", $idCustomer);
        $this->connection->where("vp.forma_pago", "Credito");
        $result = $this->connection->get();

        $data["credits_invoices"] = ($result->num_rows() > 0 && !empty($result->result()[0]->total)) ? $result->result()[0]->total : 'No encontrado';

        return $data;

    }

    public function getCreditNotes($idCustomer)
    {
        $this->connection->select("nc.id,nc.consecutivo,nc.valor,nc.fecha,v.factura,nc.estado");
        $this->connection->from("notacredito nc");
        $this->connection->join("venta v", "nc.factura_id = v.id");
        $this->connection->where("nc.cliente_id", $idCustomer);
        $result = $this->connection->get();

        $data = array();
        if ($result->num_rows() > 0):
            foreach ($result->result() as $value):
                $data[] = array(
                    'id' => $value->id,
                    'consecutive' => $value->consecutivo,
                    'total' => $this->opciones->formatoMonedaMostrar($value->valor),
                    'date' => $value->fecha,
                    'invoice' => $value->factura,
                    'state' => ($value->estado) ? 'Sin redimir' : 'Redimida',
                );
            endforeach;
        endif;

        return $data;

    }

    public function addPayInvoice($data)
    {
        $methodPayment = $this->connection->get_where('forma_pago', array('id' => $data['paymentMethod']), 1)->row();
        //die($methodPayment);
        $array_datos = array(
            "fecha_pago" => $data['payDate'],
            "notas" => '',
            "tipo" => $data['paymentMethod'],
            "cantidad" => $data['payValue'],
            "importe_retencion" => $data['payRetention'],
            "id_factura" => $data['payInvoice'],
        );

        $this->connection->insert("pago", $array_datos);

        $id = $this->connection->insert_id();

        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $valor_caja = $dat->valor_opcion;
        }

        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
            if (!empty($id)) {
                $username = $this->session->userdata('username');
                $db_config_id = $this->session->userdata('db_config_id');
                $id_user = $this->session->userdata('user_id');

                $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                    "hora_movimiento" => date('H:i:s'),
                    "id_usuario" => $id_user,
                    "tipo_movimiento" => 'entrada_venta',
                    "valor" => $data['payValue'],
                    "forma_pago" => $data['paymentMethod'],
                    "numero" => '',
                    "id_mov_tip" => $id,
                    "tabla_mov" => "pago",
                );

                $this->connection->insert('movimientos_cierre_caja', $array_datos);
            }
        }

        return true;
    }
}

<?php

class Ventas_model extends CI_Model
{
    public $connection;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->load->model("opciones_model", "opciones");
        $this->opciones->initialize($this->dbConnection);
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    public function add_campo_comensales()
    {
        $sql = "SHOW COLUMNS FROM venta LIKE 'comensales'";
        $field = $this->connection->query($sql)->result();

        if (count($field) <= 0) {
            $sql = "ALTER TABLE `venta` ADD `comensales` INT UNSIGNED NULL DEFAULT 1;";
            $this->connection->query($sql);
        }
    }

    public function show($id = '', $where = [])
    {
        if (is_numeric($id)) {

            $this->connection->where('venta.id', $id);

            $venta = $this->connection->get('venta')->row();



            return $venta;

        }
        return $this->connection->get('venta')->result_array();
    }

    public function update_fecha($data) {

        $id = $data['venta_id'];

        $fecha = $data['fecha'];

        $this->connection->where('id', $id);

        $this->connection->update('venta', ['fecha' => $fecha]);

        return true;

    }

    public function get_ajax_data($estado = 0)
    {
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

        $aColumns = array(
            'factura',
            'factura_electronica',
            'nif_cif',
            'nombre_comercial',
            'fecha',
            'total_venta',
            'nombre',
            'usuario_id',
            'factura_timbrada',
            'ruta_xml_timbrado',
        );
        $sIndexColumn = "id";
        $sTable = "venta";
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

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

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " WHERE estado = '$estado'";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $sWhere = " WHERE estado = '$estado' and almacen_id = '$almacen'";
        }

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere .= "AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . ", venta.id as id_venta, venta.tipo_factura as tipo_factura
        FROM   $sTable left join clientes on clientes.id_cliente = $sTable.cliente_id inner join almacen a on almacen_id = a.id
        $sWhere
        $sOrder
        $sLimit";

        $rResult = $this->connection->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM   $sTable where estado <> -1";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        // $aColumns[3] = 'saldo';

        $aColumns[8] = 'id_venta';
        $aColumns[7] = 'tipo_factura';
        foreach ($rResult->result_array() as $row) {
            $this->db->select("username");
            $this->db->from("users");
            $this->db->where("id", $row["usuario_id"]);
            $this->db->limit("1");
            $sql_user = $this->db->get();
            $user = "estandar";
            if ($sql_user->num_rows() > 0) {
                $result = $sql_user->result_array();
                $user = $result[0]["username"];
            }

            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($i == 5) {
                    $data[] = $this->opciones_model->formatoMonedaMostrar($row[$aColumns[$i]]);
                    // $data[] = $row[ $aColumns[$i] ];
                } elseif ($i == 7) {
                    $data[] = $user;
                } elseif ($i == 1) {
                    $data[] = $row[$aColumns[$i]] == 1 ? 'Si' : 'No';
                } else {
                    $data[] = $row[$aColumns[$i]];
                }

                // $data[] = $row[ $aColumns[$i] ];
            }
            $output['aaData'][] = $data;
        }

        return $output;

    }

    public function get_ajax_data_anuladas($estado = 0)
    {
        /*Se modificó la consulta para que pueda cargar la tabla progresivamente */
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

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " WHERE estado = '$estado'";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $sWhere = " WHERE estado = '$estado' and almacen_id = '$almacen'";
        }

        $aColumns = array(
            'v.fecha',
            'va.usuario_id',
            'v.factura',
            'va.motivo',
            'c.nombre_comercial',
            'va.fecha',
            'v.total_venta',
            'al.nombre',
        );
        $sIndexColumn = "id";
        $sTable = "venta";
        //limit
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }
        //order
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
        //Search
        $Where = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $Where .= "AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $Where .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $Where = substr_replace($Where, "", -3);
            $Where .= ')';
        }

        //$sql = "SELECT factura, (SELECT motivo FROM ventas_anuladas where venta_id = venta.id limit 1) as motivo, (SELECT fecha FROM ventas_anuladas where venta_id = venta.id limit 1) as fecha_anulacion, (SELECT u.username FROM ventas_anuladas, vendty2.users u WHERE venta_id = venta.id AND usuario_id = u.id LIMIT 1) as usuario, nombre_comercial, fecha, total_venta, nombre, venta.id as venta_id  FROM venta  inner join clientes on clientes.id_cliente = venta.cliente_id inner join almacen a on almacen_id = a.id $sWhere order by venta_id desc ";
        $sql = "SELECT SQL_CALC_FOUND_ROWS v.factura, va.usuario_id, va.motivo,va.fecha AS fecha_anulacion,
                va.usuario_id as usuario,
                c.nombre_comercial, va.fecha, v.total_venta, al.nombre,  v.id AS venta_id
                FROM venta v
                INNER JOIN ventas_anuladas va ON v.id=va.venta_id
                LEFT JOIN clientes c ON v.cliente_id = c.id_cliente
                INNER JOIN almacen al ON v.almacen_id = al.id
                $Where
                $sWhere
                $sOrder
                $sLimit";
        $data = array();

        $rResult = $this->connection->query($sql);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(id) as cantidad FROM  venta where estado = -1";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        $usuarios = $this->db->get_where('users', array('db_config_id' => $db_config_id))->result_array();

        //print_r($usuarios);die;

        foreach ($rResult->result() as $value) {

            $usuario = $value->usuario;
            if(!empty($usuario) && is_numeric($usuario)){
                foreach($usuarios as $user_row) {
                    if($user_row['id'] == $usuario) {
                        $usuario = $user_row['first_name'] . " " . $user_row['last_name'];
                        break;
                    }
                }
            }

            $data[] = array(
                $value->fecha,
                $usuario,
                $value->factura,
                $value->motivo,
                $value->nombre_comercial,
                $value->fecha_anulacion,
                $this->opciones_model->formatoMonedaMostrar($value->total_venta),
                $value->nombre,
                $value->venta_id,
            );
        }
        $output['aaData'] = $data;
        return $output;
        /*
    return array(
    'aaData' => $data
    );*/
    }

    public function get_by_id($id = 0)
    {
        //$query = $this->connection->query("SELECT venta.id as id_venta, venta.almacen_id, venta.porcentaje_descuento_general, venta.forma_pago_id, venta.factura, (venta.fecha) as fecha, venta.usuario_id, venta.cliente_id, venta.vendedor, venta.vendedor_2, venta.cambio, venta.activo, venta.total_venta, venta.estado, venta.tipo_factura, DATE(venta.fecha_vencimiento) as fecha_vencimiento_venta, venta.nota, venta.promocion, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.direccion, clientes.telefono as cliente_telefono, clientes.email as cliente_email , clientes.grupo_clientes_id, clientes.direccion as cliente_direccion , clientes.movil as cliente_movil, clientes.tipo_identificacion as tipo_identificacion, provincia as cliente_provincia,almacen.*, venta.consecutivo_orden, resolution_history_id FROM  venta inner join almacen on almacen.id = venta.almacen_id left join clientes on id_cliente = venta.cliente_id left join vendedor on vendedor.id = venta.vendedor WHERE venta.id = '" . $id . "'");
        //$query = $this->connection->query("SELECT venta.id as id_venta, venta.almacen_id, venta.porcentaje_descuento_general, venta.forma_pago_id, venta.factura, (venta.fecha) as fecha, venta.usuario_id, venta.cliente_id, venta.vendedor, venta.vendedor_2, venta.cambio, venta.activo, venta.total_venta, venta.estado, venta.tipo_factura, DATE(venta.fecha_vencimiento) as fecha_vencimiento_venta, venta.nota, venta.promocion, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.direccion, clientes.telefono as cliente_telefono, clientes.email as cliente_email , clientes.grupo_clientes_id, clientes.direccion as cliente_direccion , clientes.movil as cliente_movil, clientes.tipo_identificacion as tipo_identificacion, provincia as cliente_provincia,almacen.*, venta.consecutivo_orden, resolution_history_id FROM  venta inner join almacen on almacen.id = venta.almacen_id left join clientes on id_cliente = venta.cliente_id left join vendedor on vendedor.id = venta.vendedor WHERE venta.id = '" . $id . "'");
        $query = $this->connection->query("SELECT venta.id as id_venta, venta.factura_electronica as factura_electronica, venta.cufe as cufe, venta.almacen_id, venta.porcentaje_descuento_general, venta.forma_pago_id, venta.factura, (venta.fecha) as fecha, venta.usuario_id, venta.cliente_id, venta.vendedor, venta.vendedor_2, venta.cambio, venta.activo, venta.total_venta, venta.estado, venta.tipo_factura, DATE(venta.fecha_vencimiento) as fecha_vencimiento_venta, venta.nota, venta.promocion, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.direccion, clientes.telefono as cliente_telefono, clientes.email as cliente_email , clientes.grupo_clientes_id, clientes.direccion as cliente_direccion , clientes.movil as cliente_movil, clientes.tipo_identificacion as tipo_identificacion, provincia as cliente_provincia,almacen.*, venta.consecutivo_orden, resolution_history_id FROM  venta inner join almacen on almacen.id = venta.almacen_id left join clientes on id_cliente = venta.cliente_id left join vendedor on vendedor.id = venta.vendedor WHERE venta.id = '" . $id . "'");
        return $query->row_array();
    }

    public function venta_impuestos($id = 0)
    {
        $query = $this->connection->query("SELECT (SELECT nombre_impuesto FROM `impuesto` where porciento = impuesto limit 1) as imp, SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
     FROM  `venta`
     inner join detalle_venta on venta.id = detalle_venta.venta_id WHERE venta.id = '" . $id . "' and detalle_venta.descripcion_producto <> '-1' and impuesto > 0  group by impuesto");
        return $query->result();
    }

    public function get_by_id_comanda($id = 0)
    {
        $query = $this->connection->query("
SELECT factura_espera.id AS id_venta, factura_espera . * , clientes.nombre_comercial AS nombre_cliente
FROM factura_espera
INNER JOIN clientes ON id_cliente = factura_espera.cliente_id
         WHERE factura_espera.id = '" . $id . "'");
        return $query->row_array();
    }

    public function get_detalle_venta()
    {
        return json_encode($this->connection->get("detalle_venta")->result());
    }

    public function get_detalles_ventas($id = 0)
    {
        $query = $this->connection->query("
            SELECT
                detalle_venta.id,venta_id,codigo_producto,nombre_producto,detalle_venta.descripcion_producto as descripcion_producto,unidades,
                detalle_venta.precio_venta,descuento,detalle_venta.impuesto,linea,margen_utilidad,detalle_venta.activo,detalle_venta.producto_id,detalle_venta.porcentaje_descuento,
                (select nombre_impuesto from impuesto where impuesto.porciento = detalle_venta.impuesto limit 1) as des_impuesto
            FROM
            detalle_venta
            where
            venta_id = '" . $id . "'");

        return $query->result_array();
    }

    public function get_imei_by_venta($venta_id, $detalle_venta_id, $producto_id)
    {
        $this->connection->select("serial");
        $this->connection->from("producto_seriales");
        $this->connection->where("id_producto", $producto_id);
        $this->connection->where("id_venta", $venta_id);
        $this->connection->where("id_detalle_venta", $detalle_venta_id);

        // $this->connection->where("serial_vendido",1);

        $result = $this->connection->get();
        if ($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function get_detalles_ventas_comanda($id = 0)
    {
        $query = $this->connection->query("
            SELECT

                detalle_factura_espera.id,venta_id,codigo_producto,nombre_producto,detalle_factura_espera.descripcion_producto as descripcion_producto,unidades,
                detalle_factura_espera.precio_venta,descuento,detalle_factura_espera.impuesto,linea,margen_utilidad,detalle_factura_espera.activo

            FROM
            detalle_factura_espera
            where
            venta_id = '" . $id . "'");
        return $query->result_array();
    }

    public function get_detalles_pago($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM ventas_pago WHERE id_venta = '" . $id . "'");
        return $query->row_array();
    }

    public function get_detalles_pago_result($id = 0, $tipo = '')
    {
        if ($tipo == 'pago') {
            $query = "SELECT * FROM ventas_pago WHERE id_venta = '" . $id . "'";
            $query_result = $this->connection->query($query)->result();
            return $query_result;
        }

        if ($tipo == 'cambio') {
            $query = "SELECT sum(cambio) as total_cambio FROM ventas_pago WHERE id_venta = '" . $id . "'";
            $query_result = $this->connection->query($query)->result();
            return $query_result;
        }
    }

    public function calcular_valor_notas_credito($data)
    {
        $valor_notas = 0;
        for ($x = 0; $x <= 5; $x++) {
            $label = $x == 0 ? 'pago' : 'pago_' . $x;

            //   var_dump($data[$label]);

            if (isset($data[$label]['nota_credito'])) {
                $valor_notas += $data[$label]['valor_entregado'];
            }
        }

        return $valor_notas;
    }

    public function camposVentasDobles()
    {
        /*tabla producto */
        $sql = "ALTER TABLE producto MODIFY precio_compra double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE producto MODIFY precio_venta double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE producto MODIFY impuesto double;";
        $this->connection->query($sql);
        /*tabla ventas */
        $sql = "ALTER TABLE venta MODIFY total_venta double;";
        $this->connection->query($sql);
        /*tabla detalle_venta */
        $sql = "ALTER TABLE detalle_venta MODIFY descuento double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_venta MODIFY precio_venta double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_venta MODIFY impuesto double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_venta MODIFY margen_utilidad double;";
        $this->connection->query($sql);
        /*tabla ventas_pago */
        $sql = "ALTER TABLE ventas_pago MODIFY valor_entregado double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE ventas_pago MODIFY cambio double;";
        $this->connection->query($sql);
        /*tabla factura_espera */
        $sql = "ALTER TABLE factura_espera MODIFY total_venta double;";
        $this->connection->query($sql);
        /*tabla detalle_factura_espera */
        $sql = "ALTER TABLE detalle_factura_espera MODIFY descuento double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_factura_espera MODIFY precio_venta double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_factura_espera MODIFY impuesto double;";
        $this->connection->query($sql);
        /*tabla lista_detalle_precios */
        $sql = "ALTER TABLE lista_detalle_precios MODIFY precio double;";
        $this->connection->query($sql);
        /*tabla plan_separe_detalle */
        $sql = "ALTER TABLE plan_separe_detalle MODIFY descuento double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE plan_separe_detalle MODIFY precio_venta double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE plan_separe_detalle MODIFY impuesto double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE plan_separe_detalle MODIFY margen_utilidad double;";
        $this->connection->query($sql);
        /*tabla plan_separe_factura */
        $sql = "ALTER TABLE plan_separe_factura MODIFY total_venta double;";
        $this->connection->query($sql);
        /*tabla plan_separe_pagos */
        $sql = "ALTER TABLE plan_separe_pagos MODIFY valor_entregado double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE plan_separe_pagos MODIFY cambio double;";
        $this->connection->query($sql);
        /*tabla orden_compra */
        $sql = "ALTER TABLE orden_compra MODIFY total_venta double;";
        $this->connection->query($sql);
        /*tabla detalle_orden_compra */
        $sql = "ALTER TABLE detalle_orden_compra MODIFY descuento double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_orden_compra MODIFY precio_venta double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_orden_compra MODIFY impuesto double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE detalle_orden_compra MODIFY margen_utilidad double;";
        $this->connection->query($sql);
        /*tabla movimiento_detalle */
        $sql = "ALTER TABLE movimiento_detalle MODIFY precio_compra double;";
        $this->connection->query($sql);
        $sql = "ALTER TABLE movimiento_detalle MODIFY total_inventario double;";
        $this->connection->query($sql);
    }

    public function add($data)
    {
        try {
            // Para facturacion electronica $data['facturacion_electronica'] !== "true"
            $db_config_id = $this->session->userdata('db_config_id');
            $no_factura = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $data['usuario'];
            $no_factura_row = $this->connection->query($no_factura)->row();

            //cuando sea facturacion electronica validar asi $data['facturacion_electronica'] !== "true"
            if ($db_config_id == 12480 or $db_config_id == 13102) {
                $resolutionHistoryQuery = "SELECT * FROM `resolution_history` WHERE `warehouse_id` = " . $no_factura_row->id . " ORDER BY `id` DESC LIMIT 1;";
            } else {
                $resolutionHistoryQuery = "SELECT * FROM `resolution_history` ORDER BY `id` DESC LIMIT 1;";
            }

            $resolutionHistory = $this->connection->query($resolutionHistoryQuery)->result();
            $this->addColumnTransaccion();
            $this->connection->trans_begin();
            $precio_almacen = get_option('precio_almacen');
            $this->addColumn_porcentaje_descuento();
            $id_cliente = -1;
            if (is_numeric($data['cliente']) and ($data['cliente'] > 0)) {
                $id_cliente = $data['cliente'];
            }

            if (count($data['productos'] > 0)) {
                $array_cliente = array();
                $numero_factura = 0;
                $prefijo_factura = 0;
                $sobrecostosino = 0;
                $nit = 0;
                $multiformapago = 0;
                $valor_caja = 0;
                $decimales_moneda = 0;

                //tipo negocio
                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'tipo_negocio'";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $tipo_negocio = $dat->valor_opcion;
                }

                // decimales? decimales_moneda

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'decimales_moneda' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $decimales_moneda = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'numero_factura' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $numero_factura = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'prefijo_factura' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $prefijo_factura = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'numero' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $opcnum = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'sobrecosto' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $sobrecostosino = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'nit' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $nit = $dat->valor_opcion;
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'multiples_formas_pago' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $multiformapago = ($data['sistema'] == 'Pos') ? $dat->valor_opcion : 'no';
                }

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $valor_caja = $dat->valor_opcion;
                }

                $no_factura = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $data['usuario'];
                $no_factura_row = $this->connection->query($no_factura)->row();
                $factura_int = $no_factura_row->consecutivo;
                $factura_int++;
                $fact = 0;
                $fac = "SELECT fecha FROM `venta` order by id desc limit 1 ";
                $facresult = $this->connection->query($fac)->result();
                foreach ($facresult as $dat) {
                    $fact = $dat->fecha;
                }

                if ($data['fecha'] != $fact) {
                    if ($data['facturacion_electronica'] !== "true") {
                        if ($opcnum == 'no') {
                            $num_factura = 0;
                            $this->connection->where('id', $no_factura_row->id)->update('almacen', array(
                                'consecutivo' => $factura_int,
                            ));
                            $num_factura = $no_factura_row->prefijo . $factura_int;
                        }

                        if ($opcnum == 'si') {
                            $num_factura = 0;
                            $numero_factura++;
                            $this->connection->where('id', '26')->update('opciones', array(
                                'valor_opcion' => $numero_factura,
                            ));
                            $num_factura = $prefijo_factura . $numero_factura;
                        }
                    } else {
                        $num_factura = get_curl('generate-serial/' . $no_factura_row->id, $this->session->userdata('token_api'));
                    }

                    if (($tipo_negocio != "restaurante") && ($tipo_negocio != "Restaurante")) {
                        $array_datos = array(
                            "fecha" => $data['fecha'],
                            "fecha_vencimiento" => $data['fecha_vencimiento'],
                            "usuario_id" => $data['usuario'],
                            "factura" => $num_factura,
                            "almacen_id" => $no_factura_row->id,
                            "total_venta" => $data['total_venta'],
                            "cliente_id" => $id_cliente,
                            "tipo_factura" => $data['tipo_factura'],
                            "promocion" => $data['promocion'],
                            "porcentaje_descuento_general" => $data['descuento_general'],
                            "factura_electronica" => $data['facturacion_electronica'] === "true" ? "1" : "0",
                        );
                    } else {
                        $array_datos = array(
                            "fecha" => $data['fecha'],
                            "fecha_vencimiento" => $data['fecha_vencimiento'],
                            "usuario_id" => $data['usuario'],
                            "factura" => $num_factura,
                            "resolution_history_id" => (count($resolutionHistory) > 0) ? $resolutionHistory[0]->id : null,
                            "almacen_id" => $no_factura_row->id,
                            "total_venta" => $data['total_venta'],
                            "cliente_id" => $id_cliente,
                            "tipo_factura" => $data['tipo_factura'],
                            "promocion" => $data['promocion'],
                            'porcentaje_descuento_general' => $data['descuento_general'],
                            'comensales' => $data['comensales'],
                            'consecutivo_orden' => $data['consecutivo_orden'],
                            "factura_electronica" => $data['facturacion_electronica'] === "true" ? "1" : "0",
                            'nota' => (isset($data['a'])) ? $data['a'] : ' ',
                            //'nota' => 'asdasd'
                        );
                    }

                    if (!empty($data['vendedor'])) {
                        $array_datos["vendedor"] = $data['vendedor'];
                    }
                    if (!empty($data['vendedor_2'])) {
                        $array_datos["vendedor_2"] = $data['vendedor_2'];
                    }
                    $this->connection->insert("venta", $array_datos);
                    $id = $this->connection->insert_id();

                    // puntos -----------------------------------------------------------------------------------------------------------------------

                    $ocp = "SELECT plan_id FROM cliente_plan_punto where id_cliente = '" . $id_cliente . "' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    $puntos = '';
                    foreach ($ocpresult as $dat) {
                        $puntos = $dat->plan_id;
                    }

                    // pago por nota debito
                    // $notaDebito = false;

                    /* if( $data['pago']['forma_pago'] == 'nota_credito'){
                    $notaDebito = true;
                    }

                    for($x = 1; $x <= 5; $x++)
                    {
                    if(isset($data['pago_'.$x]['forma_pago']) && $data['pago_'.$x]['forma_pago'] == 'nota_credito')
                    {
                    $notaDebito = true;
                    }

                    if(isset($data['pago_'.$x]['nota_credito'])){
                    $notaDebito = true;
                    }else{
                    $notaDebito = false;
                    }
                    }

                    if(isset($data['pago']['nota_credito'])){
                    $notaDebito = true;
                    } */
                    $valor_restar_nota_credito = $this->calcular_valor_notas_credito($data);

                    // var_dump($valor_restar_nota_credito);die();

                    if ($puntos != '' && ($valor_restar_nota_credito < $data['total_venta'])) {
                        $ocp = "SELECT puntos, valor, iva FROM plan_puntos where id_puntos = '" . $puntos . "' ";
                        $ocpresult = $this->connection->query($ocp)->result();
                        $puntos = '';
                        $valor = '';
                        $iva = '';
                        $puntos_final = 0;
                        $total_venta_puntos = 0;
                        $total_saldo_a_favor = 0;
                        $da_puntos = true;
                        foreach ($ocpresult as $dat) {
                            $puntos = $dat->puntos;
                            $valor = $dat->valor;
                            $iva = $dat->iva;
                        }

                        $valor_entregado = 0;
                        if ($iva == 'SI') {
                            $total_venta_puntos = $data['total_venta'] - $valor_restar_nota_credito;
                        }

                        if ($iva == 'NO') {
                            $total_venta_puntos = $data['subtotal_input'] - $valor_restar_nota_credito;
                        }

                        $otros_pagos = 0;
                        for ($x = 0; $x <= 5; $x++) {
                            $label = $x == 0 ? 'pago' : 'pago_' . $x;
                            if ($data[$label]['forma_pago'] == 'Saldo_a_Favor') {
                                $total_saldo_a_favor += $data[$label]['valor_entregado'];
                            }
                            if ($data[$label]['forma_pago'] == 'Puntos') {
                                $valor_entregado += $data[$label]['valor_entregado'];
                            }
                            if ($data[$label]['forma_pago'] != 'nota_credito' && $data[$label]['forma_pago'] != 'Saldo_a_Favor' && $data[$label]['forma_pago'] != 'Puntos') {
                                $otros_pagos += $data[$label]['valor_entregado'];
                            }
                        }

                        /*Se restan los puntos que tengan saldo a favor*/
                        $total_venta_puntos -= $total_saldo_a_favor;
                        $puntos_final = ($puntos / $valor) * $total_venta_puntos;
                        if ($valor_entregado == 0 && $data['pago']['forma_pago'] == 'Puntos') {
                            $valor_entregado = $data['pago']['valor_entregado'];
                        }
                        //Logotipo
                        $logo = getGeneralOptions('logotipo_empresa')->valor_opcion;

                        if ($valor_entregado > 0) {
                            $query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$id_cliente' ";
                            $queryresult = $this->connection->query($query)->result();
                            $total_puntos = '0';
                            foreach ($queryresult as $dat) {
                                $total_puntos = $dat->total_puntos;
                            }

                            $query = "SELECT valor_opcion FROM opciones where nombre_opcion = 'punto_valor'";
                            $queryresult = $this->connection->query($query)->result();
                            $valor_puntos = '0';
                            foreach ($queryresult as $dat) {
                                $valor_puntos = $dat->valor_opcion;
                            }

                            $pago_puntos = (int) ($valor_entregado / $valor_puntos);
                            $ocp = "DELETE FROM puntos_acumulados WHERE cliente = '$id_cliente' ";
                            $this->connection->query($ocp);
                            if (($total_puntos - $pago_puntos) > 0) {
                                $nuevos_puntos = ($total_puntos - $pago_puntos);
                                $ocp = "INSERT INTO puntos_acumulados (fecha, factura, total_factura, puntos, cliente, tipo) VALUES ('" . date($data['fecha']) . "','$id','" . $total_venta_puntos . "','" . (int) $nuevos_puntos . "','$id_cliente','Acumulados') ";
                                $this->connection->query($ocp);

                                //Email de puntos al momento de redimir
                                /*$this->connection->select("*");
                                $this->connection->from("clientes");
                                $this->connection->where("id_cliente",$id_cliente);
                                $this->connection->limit(1);
                                $result = $this->connection->get();
                                if($result->num_rows() > 0):
                                $client = $result->result()[0];

                                $CI =& get_instance();
                                $CI->load->library('email');
                                $CI->email->initialize();

                                $data_email = array(
                                'logo' => $logo,
                                'name' => $client->nombre_comercial,
                                'sale_value' => $valor_entregado,
                                'redeemed_points' => $pago_puntos,
                                'current_points' => $nuevos_puntos
                                );
                                $message = $CI->load->view("email/redeemed_points",$data_email,true);
                                $CI->email->from('no-responder@vendty.net', 'Vendty POS - Has redimido puntos');
                                $CI->email->to($client->email);
                                $CI->email->subject('Has redimido puntos');
                                $CI->email->message($message);
                                $CI->email->send();
                                endif;*/
                                //END Email de puntos al momento de redimir
                            }

                            //Acumulamos puntos al mismo tiempo que redimimos
                            if ($otros_pagos > 0):
                                $puntos_final_otros_pagos = ($puntos / $valor) * $otros_pagos;
                                $ocp = "INSERT INTO puntos_acumulados (fecha,factura,total_factura,puntos,cliente,tipo) VALUES ('" . date($data['fecha']) . "','$id','" . $otros_pagos . "','" . (int) $puntos_final_otros_pagos . "','$id_cliente','Acumulados') ";
                                $this->connection->query($ocp);

                                $query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$id_cliente' ";
                                $queryresult = $this->connection->query($query)->result();
                                $total_puntos = '0';
                                foreach ($queryresult as $dat) {
                                    $total_puntos = $dat->total_puntos;
                                }
                                //Email de nuevos puntos acumulados
                                /*$this->connection->select("*");
                            $this->connection->from("clientes");
                            $this->connection->where("id_cliente",$id_cliente);
                            $this->connection->limit(1);
                            $result = $this->connection->get();
                            if($result->num_rows() > 0):
                            $client = $result->result()[0];

                            $CI =& get_instance();
                            $CI->load->library('email');
                            $CI->email->initialize();

                            $data_email = array(
                            'logo' => $logo,
                            'name' => $client->nombre_comercial,
                            'sale_value' => $total_venta_puntos,
                            'accumulated_points' => (int)$puntos_final,
                            'total_points' => $total_puntos
                            );
                            $message = $CI->load->view("email/accumulated_points",$data_email,true);
                            $CI->email->from('no-responder@vendty.net', 'Vendty POS - Has acumulado puntos');
                            $CI->email->to($client->email);
                            $CI->email->subject('Has acumulado puntos');
                            $CI->email->message($message);
                            $CI->email->send();
                            endif;*/
                            endif;
                        }

                        if ($valor_entregado == 0) {
                            $ocp = "INSERT INTO puntos_acumulados (fecha,factura,total_factura,puntos,cliente,tipo) VALUES ('" . date($data['fecha']) . "','$id','" . $total_venta_puntos . "','" . (int) $puntos_final . "','$id_cliente','Acumulados') ";
                            $this->connection->query($ocp);

                            $query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$id_cliente' ";
                            $queryresult = $this->connection->query($query)->result();
                            $total_puntos = '0';
                            foreach ($queryresult as $dat) {
                                $total_puntos = $dat->total_puntos;
                            }

                            //Email de nuevos puntos acumulados
                            /*$this->connection->select("*");
                            $this->connection->from("clientes");
                            $this->connection->where("id_cliente",$id_cliente);
                            $this->connection->limit(1);
                            $result = $this->connection->get();
                            if($result->num_rows() > 0):
                            $client = $result->result()[0];

                            $CI =& get_instance();
                            $CI->load->library('email');
                            $CI->email->initialize();

                            $data_email = array(
                            'logo' => $logo,
                            'name' => $client->nombre_comercial,
                            'sale_value' => $total_venta_puntos,
                            'accumulated_points' => (int)$puntos_final,
                            'total_points' => $total_puntos
                            );
                            $message = $CI->load->view("email/accumulated_points",$data_email,true);
                            $CI->email->from('no-responder@vendty.net', 'Vendty POS - Has acumulado puntos');
                            $CI->email->to($client->email);
                            $CI->email->subject('Has acumulado puntos');
                            $CI->email->message($message);
                            $CI->email->send();
                            endif;*/
                            //END Email de nuevos puntos acumulado
                        }

                        /*echo $valor_entregado.', '.$total_venta_puntos;
                    if($valor_entregado > 0)
                    {
                    if($valor_entregado < $total_venta_puntos)
                    {
                    } else {
                    $ocp = "DELETE  FROM puntos_acumulados WHERE cliente = '$id_cliente' ";
                    $this->connection->query($ocp);
                    }
                    }*/
                    }

                    // puntos --------------------------------------------------------------------------------------------------------------------------

                    if ($data['nota'] != '') {
                        $this->connection->where('id', $id);
                        $this->connection->set('nota', $data['nota']);
                        if (!is_null($data['comensales'])) {
                            $this->connection->set('comensales', $data['comensales']);
                        }

                        $this->connection->update('venta');
                    }

                    $data_detalles = array();
                    $query_stock = "";
                    $descuento_prod = "0";
                    $i = 0;
                    foreach ($data['productos'] as $value) {
                        $unidades_compra = $value['unidades'];
                        $product_id = $value['product_id'];
                        $comp = 0;

                        // Validamos primero si tiene precios por almacen

                        if ($precio_almacen == 1) {

                            // Si tiene precios por almacen

                            $prod = $this->connection->select('precio_compra')->from('stock_actual')->where('almacen_id', $no_factura_row->id)->where('producto_id', $product_id)->get()->row();
                            $comp = $prod->precio_compra;
                        } else {

                            // Si no tiene precios por almacen

                            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
                            foreach ($prod as $dat) {
                                $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                            }
                        }

                        /*$porcentaje_descuento = null;
                        if($data['descuento_general'] > 0 && round($value['descuento']) == 0)
                        {
                        $descuento_prod = (($value['precio_venta'] * $data['descuento_general']) / 100);
                        $porcentaje_descuento = $data['descuento_general'];
                        } else {
                        $descuento_prod = $value['descuento'];
                        if($value['precio_venta'] > 0 ){
                        $porcentaje_descuento = round( ($value['descuento'] * 100 ) / $value['precio_venta'] , 20);
                        }
                        }    */
                        /*if($this->session->userdata('db_config_id')==4017){

                        // precio

                        if($value['impuesto'] != 0){
                        $precio_venta=ROUND(($value['precio_venta'])+ (($value['precio_venta'])*($value['impuesto']/100)));
                        }else{
                        $precio_venta=ROUND($value['precio_venta']);
                        }

                        // $porcentaje_descuentop = round(($value['descuento'] * 100 ) / $value['precio_venta'] , 20);

                        if($value['precio_venta'] !=0){
                        $porcentaje_descuentop = round(($value['descuento'] * 100 ) / $value['precio_venta'] , 20);
                        }else{
                        $porcentaje_descuentop=0;
                        }

                        if($_POST['descuento_general'] != 0){
                        $descuento_prod=ROUND((((($value['precio_venta'])+(($value['precio_venta'])*$value['impuesto']/100))*$porcentaje_descuentop)/100)+(((($precio_venta) -(((($value['precio_venta'])+(($value['precio_venta'])*$value['impuesto']/100))*$porcentaje_descuentop)/100))*$data['descuento_general'])/100));
                        }else{
                        $descuento_prod=ROUND(((($value['precio_venta'])+(($value['precio_venta'])* $value['impuesto']/100))*$porcentaje_descuentop)/100);
                        }

                        if($value['impuesto'] != 0){
                        $impn=strlen($value['impuesto']);
                        if($impn== 1){
                        $im='1.0'.$value['impuesto'];
                        $descuento_prod=$descuento_prod/$im;
                        }
                        else{
                        if($impn == 2){
                        $im='1.'.$value['impuesto'];
                        $descuento_prod=$descuento_prod/$im;
                        }
                        }
                        }
                        }else{       */
                        $descuento_prod = $value['descuento'];
                        $porcentaje_descuentop = 0;
                        if (!empty($value['porcentaje_descuentop'])) {
                            $porcentaje_descuentop = is_numeric($value['porcentaje_descuentop']) ? $value['porcentaje_descuentop'] : 0;
                        }

                        // }

                        $compra_final_1 = $value['precio_venta'] - $comp;
                        $compra_final_2 = $compra_final_1 * $value['unidades'];
                        $compra_final_3 = $value['descuento'] * $value['unidades'];
                        if (empty($value['descripcion'])) {
                            $value['descripcion'] = '';
                        }
                        if ($value['promocion'] == 0) {
                            $margenUtilidad = $compra_final_2 - $compra_final_3;
                        } else {
                            $margenUtilidad = -($comp * $value['unidades']);
                        }

                        if ($value['unidades'] > 0) {
                            $data_detalles[] = array(
                                'venta_id' => $id,
                                'codigo_producto' => $value['codigo'],
                                'precio_venta' => $value['precio_venta'],
                                'unidades' => $value['unidades'],
                                'nombre_producto' => $value['nombre_producto'],
                                'descripcion_producto' => $value['promocion'], //se guarda si es un producto que vien por promocion
                                'impuesto' => $value['impuesto'],

                                // 'descuento' => $descuento_prod,

                                'descuento' => $descuento_prod,
                                'imei' => (isset($value['imei']) && $value['imei'] != "") ? $value['imei'] : '',
                                'producto_id' => $product_id,
                                'margen_utilidad' => $margenUtilidad,
                                'porcentaje_descuento' => $porcentaje_descuentop,
                            );
                        }

                        // Funcion Recursiva para disminuir en stock al realizar la venta
                        // Funciona para combos, compuestos y productos finales en todos los niveles
                        // Se agrega movimiento en la tabla stock diario

                        $this->reduce_stock($value, $no_factura_row, $unidades_compra, $num_factura, $data['usuario']);

                        // si el producto es una giftcard reducimos el stock de los otros almacenes

                        $this->producto_vendido_es_gift_card($value, $no_factura_row, $unidades_compra, $num_factura, $data['usuario']);
                    }
                }

                /*.......................................*/
            }

            /* print_r($data);
            echo"<br /><br /><br />";
            print_r($data_detalles);
            die();*/
            foreach ($data_detalles as $detalle) {
                $imei = $detalle["imei"];
                unset($detalle["imei"]);
                $this->connection->insert("detalle_venta", $detalle);
                $insert_detalle_id = $this->connection->insert_id(); //id detalle de venta

                // Acrtualizamos los imeis vendidos

                if ($imei != "") {
                    $this->update_venta_imei($detalle['producto_id'], $imei, $detalle['venta_id'], $insert_detalle_id);
                }
            }

            // $this->connection->insert_batch("detalle_venta",$data_detalles);

            //verificar si es sin pagos(restaurante)
            if ($data['venta_sin_pago'] == 1) {
                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                    $array_datos = array(
                        "Id_cierre" => $this->session->userdata('caja'),
                        "hora_movimiento" => date('H:i:s'),
                        "id_usuario" => $this->session->userdata('user_id'),
                        "tipo_movimiento" => 'entrada_venta',
                        "valor" => $data['pago']['valor_entregado'],
                        "forma_pago" => 'Sin_asignar_pago',
                        "numero" => $num_factura,
                        "id_mov_tip" => $id,
                        "tabla_mov" => "venta",
                    );
                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                }
                //insertar en ventas_pago
                $pagosinforma = array(
                    'valor_entregado' => $data['pago']['valor_entregado'],
                    'cambio' => 0,
                    'forma_pago' => 'Sin_asignar_pago',
                    'transaccion' => "",
                    'id_venta' => $id,
                );
                $this->connection->insert('ventas_pago', $pagosinforma);

                //insertar en tabla ventas
                $datos_ventas_pago = array(
                    'id_venta' => $id,
                    'id_almacen' => $no_factura_row->id,
                    'id_user' => $this->session->userdata('user_id'),
                );
                $this->connection->insert('ventas_forma_pago_pendiente', $datos_ventas_pago);
            } else {
                $data['pago']['id_venta'] = $id;
                if ($data['pago']['forma_pago'] == 'Credito') {
                    for ($x = 1; $x <= 5; $x++) {
                        if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                                $username = $this->session->userdata('username');
                                $db_config_id = $this->session->userdata('db_config_id');
                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                foreach ($user as $dat) {
                                    $id_user = $dat->id;
                                }

                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }
                        }
                    }
                }

                // Insertar relacion pago giftcard

                if (array_key_exists('cod_gift', $data['pago'])) { // Si el parametro giftcar ha sido definido en el frontend
                    if ($data['pago']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                        $codGift = $data['pago']['cod_gift'];
                        $giftData = array(
                            'id_venta' => $id,
                            'codigo_gift' => $codGift,
                        );
                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                    }

                    unset($data['pago']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                }

                // Agregar relacion factura -Nota debito

                if (array_key_exists('nota_credito', $data['pago'])) { // Si el parametro giftcar ha sido definido en el frontend
                    if ($data['pago']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                        $codNotaCredito = $data['pago']['nota_credito'];
                        $notaCredito = $this->connection->get_where("notacredito", array(
                            "consecutivo" => $codNotaCredito,
                        ))->row();

                        // var_dump($notaCredito);

                        $this->connection->where("notaForeign_id", $notaCredito->id)->update("notacredito", array(
                            "factura_id" => $id,
                            "cliente_id" => $id_cliente,
                        ));
                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                            $notaDebito = $this->connection->get_where("notacredito", array(
                                "notaForeign_id" => $notaCredito->id,
                            ))->row();

                            // var_dump($notaDebito);

                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $this->session->userdata('user_id'),
                                "tipo_movimiento" => 'salida_devolucion',
                                "valor" => $notaDebito->valor,
                                "forma_pago" => "nota_credito",
                                "numero" => $notaDebito->id,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "notacredito",
                            );

                            // var_dump($array_datos);die;

                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }

                    unset($data['pago']['nota_credito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                }

                // Se calcula de nuevo el cambio de la venta

                $valor_entregado = $data['pago']['valor_entregado'];
                $posicion_valida_cambio = 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($multiformapago == 'si') {
                        if ($decimales_moneda == 0) {
                            if ($data['pago_' . $i]['forma_pago'] == 'efectivo' && round($data['pago_' . $i]['valor_entregado']) > 0) {
                                $posicion_valida_cambio = $i;
                            }
                        } else {
                            if ($data['pago_' . $i]['forma_pago'] == 'efectivo' && ($data['pago_' . $i]['valor_entregado']) > 0) {
                                $posicion_valida_cambio = $i;
                            }
                        }
                    }

                    $valor_entregado += $data['pago_' . $i]['valor_entregado'];
                    $data['pago_' . $i]['cambio'] = 0;
                }

                $val_propina = 0;
                if ($data['subtotal_propina_input'] > 0) {
                    $val_propina = ($data['subtotal_propina_input'] * $data['sobrecostos']) / 100;
                }

                // $valor_cambio = $valor_entregado - ($data['total_venta'] + $val_propina);
                // Se redondea total de la venta para calcular el cambio

                if ($decimales_moneda == 0) {
                    $valor_cambio = $valor_entregado - (round($data['total_venta']) + $val_propina);
                } else {
                    $valor_cambio = $valor_entregado - (($data['total_venta']) + $val_propina);
                }

                if ($valor_cambio > 0) {

                    // para poner el cambio en una forma de pago valida para cambio MASGLO

                    if ($posicion_valida_cambio == 0) {
                        $data['pago']['cambio'] = $valor_cambio;
                    } else {
                        $data['pago_' . $posicion_valida_cambio]['cambio'] = $valor_cambio;
                    }
                } else {
                    $data['pago']['cambio'] = 0;
                }

                $this->connection->insert('ventas_pago', $data['pago']);
                if ($multiformapago == 'si' && $data['sistema'] == 'Pos') {
                    if ($data['pago_1']['valor_entregado'] != '0') {
                        if ($data['pago_1']['forma_pago'] == 'Credito') {
                            if ($data['pago']['forma_pago'] != 'Credito') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago']['forma_pago'],
                                    "cantidad" => $data['pago']['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                            }

                            for ($x = 1; $x <= 5; $x++) {
                                if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $notaDebito = $this->connection->get_where("notacredito", array(
                                                "notaForeign_id" => $notaCredito->id,
                                            ))->row();

                                            // die("as");

                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'salida_devolucion',
                                                "valor" => $notaDebito->valor,
                                                "forma_pago" => "nota_credito",
                                                "numero" => $notaDebito->id,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "notacredito",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago_1'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_1']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago_1']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago_1']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        // Agregar relacion factura -Nota debito

                        if (array_key_exists('nota_credito', $data['pago_1'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_1']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                                $codNotaCredito = $data['pago_1']['nota_credito'];
                                $notaCredito = $this->connection->get_where("notacredito", array(
                                    "consecutivo" => $codNotaCredito,
                                ))->row();
                                $this->connection->where("notaForeign_id", $notaCredito->id)->update("notacredito", array(
                                    "factura_id" => $id,
                                    "cliente_id" => $id_cliente,
                                ));
                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $notaDebito = $this->connection->get_where("notacredito", array(
                                        "notaForeign_id" => $notaCredito->id,
                                    ))->row();
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $this->session->userdata('user_id'),
                                        "tipo_movimiento" => 'salida_devolucion',
                                        "valor" => $notaDebito->valor,
                                        "forma_pago" => "nota_credito",
                                        "numero" => $notaDebito->id,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "notacredito",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            unset($data['pago_1']['codigo_notaCredito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                            unset($data['pago_1']['nota_credito']);
                        }

                        $data['pago_1']['id_venta'] = $id;
                        $this->connection->insert('ventas_pago', $data['pago_1']);
                    }

                    if ($data['pago_2']['valor_entregado'] != '0') {
                        if ($data['pago_2']['forma_pago'] == 'Credito') {
                            if (
                                /*$data['pago']['forma_pago'] == 'efectivo' ||
                                $data['pago']['forma_pago'] == 'tarjeta_credito' ||
                                $data['pago']['forma_pago'] == 'tarjeta_debito' ||
                                $data['pago']['forma_pago'] == 'Saldo_a_Favor' ||
                                $data['pago']['forma_pago'] == 'Visa_dÃƒÆ’Ã‚Â©bito' ||
                                $data['pago']['forma_pago'] == 'Visa_crÃƒÆ’Ã‚Â©dito'||
                                $data['pago']['forma_pago'] == 'MasterCard_dÃƒÆ’Ã‚Â©bito'||
                                $data['pago']['forma_pago'] == 'American_Express'||
                                $data['pago']['forma_pago'] == 'MasterCard'||
                                $data['pago']['forma_pago'] == 'Gift_Card'||
                                $data['pago']['forma_pago'] == 'MercadoPago'||
                                $data['pago']['forma_pago'] == 'Linio'||
                                $data['pago']['forma_pago'] == 'Bancolombia'||
                                $data['pago']['forma_pago'] == 'Efecty'||
                                $data['pago']['forma_pago'] == 'Interrapidisimo'||
                                $data['pago']['forma_pago'] == 'Baloto'||
                                $data['pago']['forma_pago'] == 'Sodexo'||
                                $data['pago']['forma_pago'] == 'Puntos'*/
                                $data['pago']['forma_pago'] != 'Credito') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago']['forma_pago'],
                                    "cantidad" => $data['pago']['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                            }

                            for ($x = 1; $x <= 5; $x++) {
                                if (
                                    /*$data['pago_'.$x]['forma_pago'] == 'efectivo' ||
                                    $data['pago_'.$x]['forma_pago'] == 'tarjeta_credito' ||
                                    $data['pago_'.$x]['forma_pago'] == 'tarjeta_debito' ||
                                    $data['pago_'.$x]['forma_pago'] == 'Saldo_a_Favor' ||
                                    $data['pago_'.$x]['forma_pago'] == 'Visa_dÃƒÆ’Ã‚Â©bito' ||
                                    $data['pago_'.$x]['forma_pago'] == 'Visa_crÃƒÆ’Ã‚Â©dito'||
                                    $data['pago_'.$x]['forma_pago'] == 'MasterCard_dÃƒÆ’Ã‚Â©bito'||
                                    $data['pago_'.$x]['forma_pago'] == 'American_Express'||
                                    $data['pago_'.$x]['forma_pago'] == 'MasterCard'||
                                    $data['pago_'.$x]['forma_pago'] == 'Gift_Card'||
                                    $data['pago_'.$x]['forma_pago'] == 'MercadoPago'||
                                    $data['pago_'.$x]['forma_pago'] == 'Linio'||
                                    $data['pago_'.$x]['forma_pago'] == 'Bancolombia'||
                                    $data['pago_'.$x]['forma_pago'] == 'Efecty'||
                                    $data['pago_'.$x]['forma_pago'] == 'Interrapidisimo'||
                                    $data['pago_'.$x]['forma_pago'] == 'Baloto'||
                                    $data['pago_'.$x]['forma_pago'] == 'Sodexo'||
                                    $data['pago_'.$x]['forma_pago'] == 'Puntos'*/
                                    $data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'entrada_venta',
                                                "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                "numero" => $num_factura,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "venta",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago_2'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_2']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago_2']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago_2']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        // Agregar relacion factura -Nota debito

                        if (array_key_exists('nota_credito', $data['pago_2'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_2']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                                $codNotaCredito = $data['pago_2']['nota_credito'];
                                $notaCredito = $this->connection->get_where("notacredito", array(
                                    "consecutivo" => $codNotaCredito,
                                ))->row();
                                $this->connection->where("notaForeign_id", $notaCredito->id)->update("notacredito", array(
                                    "factura_id" => $id,
                                    "cliente_id" => $id_cliente,
                                ));
                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $notaDebito = $this->connection->get_where("notacredito", array(
                                        "notaForeign_id" => $notaCredito->id,
                                    ))->row();
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $this->session->userdata('user_id'),
                                        "tipo_movimiento" => 'salida_devolucion',
                                        "valor" => $notaDebito->valor,
                                        "forma_pago" => "nota_credito",
                                        "numero" => $notaDebito->id,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "notacredito",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            unset($data['pago_2']['nota_credito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        $data['pago_2']['id_venta'] = $id;
                        $this->connection->insert('ventas_pago', $data['pago_2']);
                    }

                    if ($data['pago_3']['valor_entregado'] != '0') {
                        if ($data['pago_3']['forma_pago'] == 'Credito') {
                            if ($data['pago']['forma_pago'] != 'Credito') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago']['forma_pago'],
                                    "cantidad" => $data['pago']['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                            }

                            for ($x = 1; $x <= 5; $x++) {
                                if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'entrada_venta',
                                                "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                "numero" => $num_factura,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "venta",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago_3'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_3']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago_3']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago_3']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        // Agregar relacion factura -Nota debito

                        if (array_key_exists('nota_credito', $data['pago_3'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_3']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                                $codNotaCredito = $data['pago_3']['nota_credito'];
                                $notaCredito = $this->connection->get_where("notacredito", array(
                                    "consecutivo" => $codNotaCredito,
                                ))->row();
                                $this->connection->where("notaForeign_id", $notaCredito->id);
                                $this->connection->update("notacredito", array(
                                    "factura_id" => $id,
                                    "cliente_id" => $id_cliente,
                                ));
                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $notaDebito = $this->connection->get_where("notacredito", array(
                                        "notaForeign_id" => $notaCredito->id,
                                    ))->row();
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $this->session->userdata('user_id'),
                                        "tipo_movimiento" => 'salida_devolucion',
                                        "valor" => $notaDebito->valor,
                                        "forma_pago" => "nota_credito",
                                        "numero" => $notaDebito->id,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "notacredito",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            unset($data['pago_3']['nota_credito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        $data['pago_3']['id_venta'] = $id;
                        $this->connection->insert('ventas_pago', $data['pago_3']);
                    }

                    if ($data['pago_4']['valor_entregado'] != '0') {
                        if ($data['pago_4']['forma_pago'] == 'Credito') {
                            if ($data['pago']['forma_pago'] != 'Credito') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago']['forma_pago'],
                                    "cantidad" => $data['pago']['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                            }

                            for ($x = 1; $x <= 5; $x++) {
                                if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'entrada_venta',
                                                "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                "numero" => $num_factura,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "venta",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago_4'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_4']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago_4']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago_4']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        // Agregar relacion factura -Nota debito

                        if (array_key_exists('nota_credito', $data['pago_4'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_4']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                                $codNotaCredito = $data['pago_4']['nota_credito'];
                                $notaCredito = $this->connection->get_where("notacredito", array(
                                    "consecutivo" => $codNotaCredito,
                                ))->row();
                                $this->connection->where("notaForeign_id", $notaCredito->id);
                                $this->connection->update("notacredito", array(
                                    "factura_id" => $id,
                                    "cliente_id" => $id_cliente,
                                ));
                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $notaDebito = $this->connection->get_where("notacredito", array(
                                        "notaForeign_id" => $notaCredito->id,
                                    ))->row();
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $this->session->userdata('user_id'),
                                        "tipo_movimiento" => 'salida_devolucion',
                                        "valor" => $notaDebito->valor,
                                        "forma_pago" => "nota_credito",
                                        "numero" => $notaDebito->id,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "notacredito",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            unset($data['pago_4']['nota_credito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        $data['pago_4']['id_venta'] = $id;
                        $this->connection->insert('ventas_pago', $data['pago_4']);
                    }

                    if ($data['pago_5']['valor_entregado'] != '0') {
                        if ($data['pago_4']['forma_pago'] == 'Credito') {
                            if ($data['pago']['forma_pago'] != 'Credito') {
                                $array_datos = array(
                                    "fecha_pago" => $data['fecha'],
                                    "notas" => '',
                                    "tipo" => $data['pago']['forma_pago'],
                                    "cantidad" => $data['pago']['valor_entregado'],
                                    "importe_retencion" => 0,
                                    "id_factura" => $id,
                                );
                                $this->connection->insert("pago", $array_datos);
                            }

                            for ($x = 1; $x <= 5; $x++) {
                                if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'entrada_venta',
                                                "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                "numero" => $num_factura,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "venta",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago_5'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_5']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago_5']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago_5']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        // Agregar relacion factura -Nota debito

                        if (array_key_exists('nota_credito', $data['pago_5'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago_5']['forma_pago'] == 'nota_credito') { // Si el metodo de pago es giftcard
                                $codNotaCredito = $data['pago_5']['nota_credito'];
                                $notaCredito = $this->connection->get_where("notacredito", array(
                                    "consecutivo" => $codNotaCredito,
                                ))->row();
                                $this->connection->where("notaForeign_id", $notaCredito->id);
                                $this->connection->update("notacredito", array(
                                    "factura_id" => $id,
                                    "cliente_id" => $id_cliente,
                                ));
                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                    $notaDebito = $this->connection->get_where("notacredito", array(
                                        "notaForeign_id" => $notaCredito->id,
                                    ))->row();
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $this->session->userdata('user_id'),
                                        "tipo_movimiento" => 'salida_devolucion',
                                        "valor" => $notaDebito->valor,
                                        "forma_pago" => "nota_credito",
                                        "numero" => $notaDebito->id,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "notacredito",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            unset($data['pago_5']['nota_credito']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        $data['pago_5']['id_venta'] = $id;
                        $this->connection->insert('ventas_pago', $data['pago_5']);
                    }
                }

                // INICIO webpay

                $this->connection->where('aleatorio', $data['aleatorio'])->where('estado', 1)->update('webpay', array(
                    'id_pago' => $id,
                ));

                // FIN webpay

                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                    $username = $this->session->userdata('username');
                    $db_config_id = $this->session->userdata('db_config_id');
                    $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                    foreach ($user as $dat) {
                        $id_user = $dat->id;
                    }

                    if ($data['pago']['forma_pago'] != '') {
                        $array_datos = array(
                            "Id_cierre" => $this->session->userdata('caja'),
                            "hora_movimiento" => date('H:i:s'),
                            "id_usuario" => $id_user,
                            "tipo_movimiento" => 'entrada_venta',
                            "valor" => ($data['pago']['valor_entregado'] - $data['pago']['cambio']),
                            "forma_pago" => $data['pago']['forma_pago'],
                            "numero" => isset($num_factura) ? $num_factura : '',
                            "id_mov_tip" => $id,
                            "tabla_mov" => "venta",
                        );
                        $this->connection->insert('movimientos_cierre_caja', $array_datos);
                    }

                    if ($data['pago_1']['valor_entregado'] != '0') {
                        if ($data['pago']['forma_pago'] != 'Credito') {
                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => (($data['pago_1']['valor_entregado'] - $data['pago_1']['cambio'])),
                                "forma_pago" => $data['pago_1']['forma_pago'],
                                "numero" => $num_factura,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "venta",
                            );
                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }

                    if ($data['pago_2']['valor_entregado'] != '0') {
                        if ($data['pago']['forma_pago'] != 'Credito') {
                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => ($data['pago_2']['valor_entregado'] - $data['pago_2']['cambio']),
                                "forma_pago" => $data['pago_2']['forma_pago'],
                                "numero" => $num_factura,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "venta",
                            );
                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }

                    if ($data['pago_3']['valor_entregado'] != '0') {
                        if ($data['pago']['forma_pago'] != 'Credito') {
                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => ($data['pago_3']['valor_entregado'] - $data['pago_3']['cambio']),
                                "forma_pago" => $data['pago_3']['forma_pago'],
                                "numero" => $num_factura,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "venta",
                            );
                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }

                    if ($data['pago_4']['valor_entregado'] != '0') {
                        if ($data['pago']['forma_pago'] != 'Credito') {
                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => ($data['pago_4']['valor_entregado'] - $data['pago_4']['cambio']),
                                "forma_pago" => $data['pago_4']['forma_pago'],
                                "numero" => $num_factura,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "venta",
                            );
                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }

                    if ($data['pago_5']['valor_entregado'] != '0') {
                        if ($data['pago']['forma_pago'] != 'Credito') {
                            $array_datos = array(
                                "Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => ($data['pago_5']['valor_entregado'] - $data['pago_5']['cambio']),
                                "forma_pago" => $data['pago_5']['forma_pago'],
                                "numero" => $num_factura,
                                "id_mov_tip" => $id,
                                "tabla_mov" => "venta",
                            );
                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }
                    }
                }
            }

            if ($data['sobrecostos'] != '' and $sobrecostosino == 'si') {
                if ($nit == '320001127839') {
                    $this->connection->insert('detalle_venta', array(
                        'venta_id' => $id,
                        'nombre_producto' => 'PROPINA',
                        'tipo_propina' => $data['tipo_propina'],
                        'unidades' => '1',
                        'descripcion_producto' => $data['sobrecostos'],
                        'precio_venta' => (($data['subtotal_input'] * $data['sobrecostos']) / 100),
                    ));
                } else {
                    $valor_propina = ($data['subtotal_propina_input'] * $data['sobrecostos']) / 100;
                    $this->connection->insert('detalle_venta', array(
                        'venta_id' => $id,
                        'nombre_producto' => 'PROPINA',
                        'unidades' => '1',
                        'descripcion_producto' => $data['sobrecostos'],
                        'tipo_propina' => $data['tipo_propina'],
                        'precio_venta' => $valor_propina - (($data['descuento_general'] * $valor_propina) / 100),
                    ));
                }
            }

            if ($data["id_fact_espera"] != '') {
                $this->connection->query("delete from  factura_espera where id = '" . $data["id_fact_espera"] . "' ");
                if ($this->connection->table_exists('comanda_notificacion_detalle')) {

                    // Eliminamos relacion factura en espera comanda

                    $this->connection->query("DELETE FROM comanda_notificacion_detalle WHERE id_factura_espera = '" . $id . "' ");

                    // Enviamos notificacion

                    $now = DateTime::createFromFormat('U.u', microtime(true));
                    $ping = $now->format("YdmHisu");
                    $sql = " UPDATE comanda_notificacion_cliente SET notificacion = '$ping' ";
                    $this->connection->query($sql);
                }
            }

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
                die();
            } else {
                $this->connection->trans_commit();
            }

            return $id;
        } catch (Exception $e) {

            // $this->connection->trans_rollback();

            print_r($e);
            die;
        }

        /*DECREMENTAR SOTCK*/
    }

    public function add_impresion_rapida($id_venta, $detalle_factura, $almacen, $caja)
    {
        $this->check_tabla_impresion_rapida();
        $data = array(
            "descripcion_venta" => json_encode($detalle_factura),
            "id_venta" => $id_venta,
            "almacen" => $almacen,
            "caja" => $caja,
            "estado" => 0,
        );
        $this->connection->insert("impresion_rapida", $data);
    }

    public function check_tabla_impresion_rapida()
    {
        $crear_tabla_impresion_rapida = " CREATE TABLE IF NOT EXISTS `impresion_rapida` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `descripcion_venta` text NOT NULL COMMENT 'json con la data de la factura',
            `id_venta` int(11) NOT NULL,
            `almacen` int(11) NOT NULL,
            `caja` int(11) NOT NULL,
            `estado` BOOLEAN NOT NULL DEFAULT 0,
             KEY `id` (`id`)
          )";
        $this->connection->query($crear_tabla_impresion_rapida);

        // impresion rapida agregar almacen caja,estado

        $sql = "SHOW COLUMNS FROM impresion_rapida LIKE 'almacen'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            $sql = "ALTER TABLE impresion_rapida
                ADD COLUMN `almacen` INT(10) UNSIGNED NULL,
                ADD COLUMN `caja`  INT(10) UNSIGNED NULL,
                ADD COLUMN `estado` BOOLEAN NOT NULL DEFAULT 0;
            ";
            $this->connection->query($sql);
        }
    }

    public function update_venta_imei($producto_id, $producto_imei, $id_venta, $id_detalle_venta)
    {
        $data = array(
            "serial_vendido" => 1,
            "id_venta" => $id_venta,
            "id_detalle_venta" => $id_detalle_venta,
        );
        $this->connection->where("id_producto", $producto_id);
        $this->connection->where("serial", $producto_imei);
        $this->connection->update("producto_seriales", $data);
    }

    public function producto_vendido_es_gift_card($producto, $no_factura_row, $unidades_compra, $num_factura, $usuario)
    {
        $datos_producto = $this->connection->get_where("producto", array(
            "id" => $producto['product_id'],
        ))->row();
        $idCategoria = $this->categorias->crear_categoria("GiftCard", "giftCard.png");
        if ($datos_producto->categoria_id == $idCategoria) {
            $almacenes_cliente = $this->connection->get_where("almacen", array(
                'activo' => 1,
            ))->result();
            foreach ($almacenes_cliente as $key => $value) {
                if ($value->id != $no_factura_row->id) {
                    $query1 = $this->connection->query("DELETE FROM stock_actual where almacen_id = '" . $value->id . "' AND producto_id ='" . $datos_producto->id . "' ");
                }
            }
        }
    }

    public function reduce_stock($productos, $id_almacen, $unidades_compra, $num_factura = null, $usuario_id)
    {

        // Comprobamos si es multidimensional, es decir si vienen los productos que componen un combo o un compuesto

        if (count($productos) != count($productos, COUNT_RECURSIVE)) {
            foreach ($productos as $rowProductos) {
                $this->reduce_stock($rowProductos, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
            }
        } else {
            if (isset($productos['product_id'])) {
                $productos['id'] = $productos['product_id'];
            } elseif (isset($productos['id_combo'])) {
                $productos['id'] = $productos['id_producto'];
            } elseif (isset($productos['id_ingrediente'])) {
                $productos['id'] = $productos['id_ingrediente'];
            }

            if (isset($productos['id'])) {
                $productoCombo = $this->connection->get_where("producto", array(
                    "id" => $productos['id'],
                ))->row();
            }
            if (isset($productoCombo->id)) {
                if ($productoCombo->ingredientes != 1 && $productoCombo->combo != 1) {
                    $query_stock = "select * from stock_actual where almacen_id =" . $id_almacen->id . " and producto_id=" . $productoCombo->id;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    if (isset($productos['cantidad'])) {
                        $desc_inventario = $productos['cantidad'] * $unidades_compra;
                    } else {
                        $desc_inventario = $unidades_compra;
                    }

                    $unidades = $unidades_actual - ($desc_inventario); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                    // Actualizar stock

                    $this->connection->where('almacen_id', $id_almacen->id)->where('producto_id', $productoCombo->id)->update('stock_actual', array(
                        'unidades' => $unidades,
                    ));

                    // Insertar stock diario

                    $this->connection->insert('stock_diario', array(
                        'producto_id' => $productoCombo->id,
                        'almacen_id' => $id_almacen->id,
                        'fecha' => date('Y-m-d'),
                        'unidad' => '-' . $desc_inventario,
                        'precio' => 0,
                        'cod_documento' => $num_factura,
                        'usuario' => $usuario_id,
                        'razon' => 'S',
                    ));
                } else {
                    if ($productoCombo->ingredientes == 1) {
                        $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $productoCombo->id;
                        $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                        // Se ejecuta funcion Recursiva para ingredientes

                        $product_array = $ingredientes_producto->result_array();
                        $this->reduce_stock($product_array, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
                    }

                    if ($productoCombo->combo == 1) {
                        $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $productoCombo->id;
                        $productos_combo = $this->connection->query($query_productos_combo);

                        // Se ejecuta funcion Recursiva para compuestos

                        $product_array = $productos_combo->result_array();
                        $this->reduce_stock($product_array, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
                    }
                }
            }
        }
    }

    public function espera($data, $tipo = null)
    {
        $array_datos = array();
        if ($data['cliente'] == "") {
            $id_cliente = -1;
        } else {
            $id_cliente = $data['cliente'];
        }
        $total_numero = '0';
        $total_numero1 = '0';
        $total = '';
        $usuario = '';
        $usuario = $this->session->userdata('user_id');
        $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        $username = $this->session->userdata('username');
        $this->connection->query("update factura_espera set usuario_id = '" . $usuario . "' where id = '-1' ");
        $total = "SELECT no_factura FROM factura_espera where almacen_id='" . $id_almacen . "' and activo = '1'  order by id desc ";
        $total_numero = $this->connection->query($total)->row();
        if ($total_numero) {
            $total_numero = $total_numero->no_factura + $total_numero1;
        }

        $num_rows = $this->connection->query($total)->num_rows();
        if ($num_rows == '0') {
            $total = "SELECT no_factura FROM factura_espera where id = '-1' and activo = '1'   ";
            $total_numero = $this->connection->query($total)->row();
            if (isset($total_numero->no_factura)) {
                $total_numero = $total_numero->no_factura;
            } else {
                $total_numero = 1;
            }
        } else {

            // $total = "SELECT no_factura FROM factura_espera where usuario_id = '".$usuario."' and activo = '1'    order by id desc ";

            $total = "SELECT no_factura FROM factura_espera where activo = '1' and almacen_id='" . $id_almacen . "' order by id desc ";
            $total_numero = $this->connection->query($total)->row();
            if ($total_numero) {
                $total_numero = $total_numero->no_factura + 1;
            } else {
                $total_numero = 1;
            }
        }

        $nombre_venta_espera = 'Venta # ' . $total_numero . ' - ' . $username;
        if (isset($data['mesa'])) {
            if ($data['mesa'] > 0) {
                $nombre_venta_espera = $data['datos_mesa'][0]->nombre_mesa;
            }
        }

        $array_datos = array(
            "fecha" => $data['fecha'],
            "fecha_vencimiento" => $data['fecha_vencimiento'],
            "usuario_id" => $data['usuario'],
            "factura" => $nombre_venta_espera,
            "no_factura" => $total_numero,
            "almacen_id" => $id_almacen,
            "total_venta" => $data['total_venta'],
            "cliente_id" => $id_cliente,
            "tipo_factura" => $data['tipo_factura'],
            "activo" => $data['activo'],
        );
        $this->connection->insert("factura_espera", $array_datos);
        $id = $this->connection->insert_id();
        if ($data['nota'] != '') {
            $this->connection->where('id', $id);
            $this->connection->set('nota', $data["nota"]);
            $this->connection->update('factura_espera');
        }

        if (isset($data['mesa'])) {
            if ($data['mesa'] > 0) {
                $this->connection->where('id', $id);
                $this->connection->set('id_mesa', $data['mesa']);
                $this->connection->update('factura_espera');
            }
        }

        foreach ($data['productos'] as $value) {
            $unidades_compra = $value['unidades'];
            $nombre = $value['nombre_producto'];
            $codigo = $value['codigo'];
            $prod = $this->connection->query("SELECT * FROM `producto` where nombre = '{$nombre}' and  codigo = '{$codigo}' ")->result();
            foreach ($prod as $dat) {
                $comp = $dat->precio_compra;
            }

            $compra_final_1 = $value['precio_venta'] - $comp;
            $compra_final_2 = $compra_final_1 * $value['unidades'];
            $compra_final_3 = $value['descuento'] * $value['unidades'];
            if (empty($value['descripcion'])) {
                $value['descripcion'] = '';
            }
            $data_detalles[] = array(
                'venta_id' => $id,
                'id_producto' => $value['product_id'],
                'codigo_producto' => $value['codigo'],
                'precio_venta' => $value['precio_venta'],
                'unidades' => $value['unidades'],
                'nombre_producto' => $value['nombre_producto'],
                'descripcion_producto' => $value['descripcion'],
                'impuesto' => $value['impuesto'],
                'descuento' => $value['descuento'],
                'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            );
            /*.......................................*/
        }

        if (!empty($data_detalles)) {
            $this->connection->insert_batch("detalle_factura_espera", $data_detalles);
        }

        if ($tipo == 'mesa_espera') {
            return array(
                'id' => $id,
                'nombre_venta' => $nombre_venta_espera,
            );
        }

        return $id;
    }

    public function comanda($data, $tipo = null)
    {
        $array_datos = array();
        if ($data['cliente'] == "") {
            $id_cliente = -1;
        } else {
            $id_cliente = $data['cliente'];
        }
        if (count($data['productos']) > 0) {
            $total_numero = '0';
            $total_numero1 = '0';
            $total = '';
            $usuario = '';
            $usuario = $this->session->userdata('user_id');
            $this->connection->query("update factura_espera set usuario_id = '" . $usuario . "' where id = '-1' ");
            $total = "SELECT no_factura FROM factura_espera where usuario_id = '" . $usuario . "' and activo = '1'  order by id desc ";
            $total_numero = $this->connection->query($total)->row();
            $total_numero = $total_numero->no_factura + $total_numero1;
            $num_rows = $this->connection->query($total)->num_rows();
            if ($num_rows == '0') {
                $total = "SELECT no_factura FROM factura_espera where id = '-1' ";
                $total_numero = $this->connection->query($total)->row();
                $total_numero = $total_numero1->no_factura;
            } else {
                $total = "SELECT no_factura FROM factura_espera where usuario_id = '" . $usuario . "'  order by id desc ";
                $total_numero = $this->connection->query($total)->row();
                $total_numero = $total_numero->no_factura + 1;
            }

            $array_datos = array(
                "fecha" => $data['fecha'],
                "fecha_vencimiento" => $data['fecha_vencimiento'],
                "usuario_id" => $data['usuario'],
                "factura" => $data['factura'],
                "no_factura" => '',
                "almacen_id" => '',
                "total_venta" => $data['total_venta'],
                "cliente_id" => $id_cliente,
                "tipo_factura" => $data['tipo_factura'],
                "activo" => $data['activo'],
            );
            $this->connection->insert("factura_espera", $array_datos);
            $id = $this->connection->insert_id();
            if ($data['nota'] != '') {
                $this->connection->where('id', $id);
                $this->connection->set('nota', $data["nota"]);
                $this->connection->update('factura_espera');
            }

            foreach ($data['productos'] as $value) {
                $unidades_compra = $value['unidades'];
                $nombre = $value['nombre_producto'];
                $codigo = $value['codigo'];
                $prod = $this->connection->query("SELECT * FROM `producto` where nombre = '{$nombre}' and  codigo = '{$codigo}' ")->result();
                foreach ($prod as $dat) {
                    $comp = $dat->precio_compra;
                }

                $compra_final_1 = $value['precio_venta'] - $comp;
                $compra_final_2 = $compra_final_1 * $value['unidades'];
                $compra_final_3 = $value['descuento'] * $value['unidades'];
                if (empty($value['descripcion'])) {
                    $value['descripcion'] = '';
                }
                $data_detalles[] = array(
                    'venta_id' => $id,
                    'id_producto' => $value['product_id'],
                    'codigo_producto' => $value['codigo'],
                    'precio_venta' => $value['precio_venta'],
                    'unidades' => $value['unidades'],
                    'nombre_producto' => $value['nombre_producto'],
                    'descripcion_producto' => $value['descripcion'],
                    'impuesto' => $value['impuesto'],
                    'descuento' => $value['descuento'],
                    'margen_utilidad' => ($compra_final_2 - $compra_final_3),
                );
                /*.......................................*/
            }

            $this->connection->insert_batch("detalle_factura_espera", $data_detalles);
        }

        return $id;
    }

    public function getFacturaEsperaNota($id = 0)
    {
        $query = $this->connection->query(" SELECT nota FROM factura_espera WHERE id = '" . $id . "' ");
        $nota = "";
        if ($query->num_rows() > 0) {
            $nota = $query->row()->nota;
        }

        $resultado = array(
            "nota" => $nota,
        );
        return $resultado;
    }

    public function setFacturaEsperaNota($id = 0, $nota = '')
    {
        $this->connection->query(" UPDATE factura_espera SET nota = '$nota' WHERE id = '$id' ");
    }

    public function get_all_espera($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM factura_espera ");
        return $query->result();
    }

    public function get_facturas_espera($where)
    {
        $this->connection->where($where);
        $query = $this->connection->get('factura_espera');
        return $query->result();
    }

    public function get_all_espera_detalles($id = 0, $con_mesas = false, $almacenActual)
    {
        if ($id != 0) {
            if ($con_mesas) {
                $query = $this->connection->query("
                SELECT detalle_factura_espera.*, factura_espera.factura, cliente_id AS id_clientes ,
                    (SELECT nombre_comercial FROM `clientes` WHERE id_cliente = cliente_id) AS cli_nom,
                factura_espera.id_mesa,mesas_secciones.codigo_mesa,mesas_secciones.nombre_mesa,
                secciones_almacen.nombre_seccion, secciones_almacen.id AS id_seccion,
                producto.vendernegativo,
                stock_actual.unidades AS stock,
                IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto
                FROM detalle_factura_espera
                INNER JOIN factura_espera ON factura_espera.id = detalle_factura_espera.venta_id
                LEFT JOIN mesas_secciones ON (factura_espera.id_mesa = mesas_secciones.id)
                LEFT JOIN secciones_almacen ON (secciones_almacen.id = mesas_secciones.id_seccion )
                INNER JOIN producto ON detalle_factura_espera.id_producto = producto.id
                INNER JOIN stock_actual ON detalle_factura_espera.id_producto = stock_actual.producto_id
                WHERE venta_id = '" . $id . "'
                AND stock_actual.almacen_id='" . $almacenActual . "'
            ");
            } else {
                $query = $this->connection->query("
                SELECT detalle_factura_espera.*, factura_espera.factura, cliente_id AS id_clientes ,
                (SELECT nombre_comercial FROM `clientes` WHERE id_cliente = cliente_id) AS cli_nom,
                producto.vendernegativo,
                stock_actual.unidades AS stock,
                IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto
                FROM detalle_factura_espera
                INNER JOIN factura_espera ON factura_espera.id = detalle_factura_espera.venta_id
                INNER JOIN producto ON detalle_factura_espera.id_producto = producto.id
                INNER JOIN stock_actual ON detalle_factura_espera.id_producto = stock_actual.producto_id
                WHERE detalle_factura_espera.venta_id = '" . $id . "'
                AND stock_actual.almacen_id='" . $almacenActual . "'
            ");
            }

            if ($query->num_rows() > 0) {
                return $query->result();
            }

            return array();
        }

        return null;
    }

    public function get_all_espera_factura($id = 0, $con_mesas = false)
    {
        $usuario = '';
        $usuario = $this->session->userdata('user_id');
        $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        if ($con_mesas) {
            $sql = ' SELECT factura_espera.*,mesas_secciones.codigo_mesa,mesas_secciones.nombre_mesa,
                secciones_almacen.nombre_seccion, secciones_almacen.id AS id_seccion, comanda_notificacion_detalle.estado as estado_comanda
                FROM factura_espera
                LEFT JOIN mesas_secciones ON (mesas_secciones.id = factura_espera.id_mesa )
                LEFT JOIN secciones_almacen ON (secciones_almacen.id = mesas_secciones.id_seccion)
                LEFT JOIN comanda_notificacion_detalle ON (comanda_notificacion_detalle.id_factura_espera = factura_espera.id)
                WHERE factura_espera.activo = 1 ';
        } else {
            $sql = ' SELECT *
                FROM factura_espera
                WHERE factura_espera.activo = 1 ';
        }

        if ($this->session->userdata('is_admin') == 't') {
            $sql .= " AND factura_espera.almacen_id = $id_almacen ";
        } else {
            $sql .= " AND factura_espera.usuario_id = '" . $usuario . "'";
        }

        $query = $this->connection->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return null;
    }

    public function get_all_espera_factura_nombre($id = 0, $nom = 0)
    {
        $usuario = '';
        $usuario = $this->session->userdata('user_id');
        $query1 = $this->connection->query("SELECT * FROM factura_espera where factura =  '" . $nom . "' and usuario_id = '" . $usuario . "'");
        if ($query1->num_rows() == 0) {
            $query = $this->connection->query("update factura_espera set factura = '" . $nom . "' where id = '" . $id . "' ");
        }

        return $query1->num_rows();
    }

    public function espera_actualizar($data, $id = null)
    {
        if (empty($data)) {
            return;
        }

        if (empty($data['productos']) or is_null($data['productos'])) {
            return;
        }

        $this->connection->query("delete from detalle_factura_espera where venta_id = '$id' ");
        $data_detalles = array();
        foreach ($data['productos'] as $value) {
            $unidades_compra = $value['unidades'];
            $nombre = $value['nombre_producto'];
            $codigo = $value['codigo'];
            $product_id = $value['product_id'];
            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
            foreach ($prod as $dat) {
                $comp = $dat->precio_compra;
            }

            $compra_final_1 = $value['precio_venta'] - $comp;
            $compra_final_2 = $compra_final_1 * $value['unidades'];
            $compra_final_3 = $value['descuento'] * $value['unidades'];
            if (empty($value['descripcion'])) {
                $value['descripcion'] = '';
            }
            $data_detalles[] = array(
                'venta_id' => $id,
                'id_producto' => $value['product_id'],
                'codigo_producto' => $value['codigo'],
                'precio_venta' => $value['precio_venta'],
                'unidades' => $value['unidades'],
                'nombre_producto' => $value['nombre_producto'],
                'descripcion_producto' => $value['descripcion'],
                'impuesto' => $value['impuesto'],
                'descuento' => $value['descuento'],
                'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            );
            /*.......................................*/
        }

        $this->connection->insert_batch("detalle_factura_espera", $data_detalles);
        return 'hola';
    }

    // eliminar factura en espera

    public function eliminar_factura($id = 0)
    {
        $usuario = '';
        $usuario = $this->session->userdata('user_id');
        if ($this->session->userdata('is_admin') == 't') {
            $query = $this->connection->query("DELETE FROM factura_espera WHERE id = '" . $id . "' ");
        } else {
            $query = $this->connection->query("DELETE FROM factura_espera WHERE id = '" . $id . "' AND usuario_id = '" . $usuario . "'");
        }

        if ($this->connection->table_exists('comanda_notificacion_detalle')) {

            // Eliminamos relacion factura en espera comanda

            $this->connection->query("DELETE FROM comanda_notificacion_detalle WHERE id_factura_espera = '" . $id . "' ");

            // Enviamos notificacion

            $now = DateTime::createFromFormat('U.u', microtime(true));
            $ping = $now->format("YdmHisu");
            $sql = " UPDATE comanda_notificacion_cliente SET notificacion = '$ping' ";
            $this->connection->query($sql);
        }

        return $query;
    }

    public function eliminar_comanda_temporal($id = 0)
    {
        $usuario = '';
        $query1 = $this->connection->query("DELETE FROM factura_espera where id =  '" . $id . "' ");
    }

    public function get_all_espera_factura_ultimo($id = 0)
    {
        $usuario = '';
        $usuario = $this->session->userdata('user_id');
        $query = $this->connection->query("SELECT * FROM factura_espera where id = '" . $id . "' ");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return null;
    }

    public function edit($id)
    {
        $this->db->get('venta');
    }

    public function eliminar_producto_actualizar($venta, $id, $producto_id, $prod, $cant, $alm)
    {
        $product_id = 0;
        if ($producto_id != '' && $producto_id != '0') {
            $query_producto_id = "select * from producto where  id = '" . $producto_id . "' ";
        } else {
            $query_producto_id = "select * from producto where codigo ='" . $prod . "' ";
        }

        $producto_id = $this->connection->query($query_producto_id)->result();
        foreach ($producto_id as $value) {
            $product_id = $value->id;
            $precio_venta = $value->precio_venta;
        }

        $query_stock = "select * from stock_actual where almacen_id ='" . $alm . "' and producto_id=" . $product_id;
        $almacen = $this->connection->query($query_stock)->row();
        $venta_stock = "select factura from venta where id=" . $venta;
        $venta_stock = $this->connection->query($venta_stock)->result();
        foreach ($venta_stock as $value) {
            $factura = $value->factura;
        }

        $unidades_actual = $almacen->unidades;
        $unidades = $unidades_actual + $cant;
        $this->connection->where('almacen_id', $alm)->where('producto_id', $product_id)->update('stock_actual', array(
            'unidades' => $unidades,
        ));

        // Insertar stock diario

        $this->connection->insert('stock_diario', array(
            'producto_id' => $product_id,
            'almacen_id' => $alm,
            'fecha' => date('Y-m-d'),
            'unidad' => $cant,
            'precio' => $precio_venta,
            'cod_documento' => $factura,
            'usuario' => $this->session->userdata('user_id'),
            'razon' => 'E',
        ));
        /*Ingredientes =================================================== */
        $query_producto = "select * from producto where id =" . $product_id;
        $producto = $this->connection->query($query_producto)->row();
        if ($producto->ingredientes == 1) {

            // Ingredientes del producto

            $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $product_id;
            $ingredientes_producto = $this->connection->query($query_ingredientes_producto);
            foreach ($ingredientes_producto->result() as $key => $value) {

                // Stock del ingrediente

                $query_stock = "select * from stock_actual where almacen_id =" . $alm . " and producto_id=" . $value->id_ingrediente;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                // Insertar stock diario

                $this->connection->insert('stock_diario', array(
                    'producto_id' => $value->id_ingrediente,
                    'almacen_id' => $alm,
                    'fecha' => date('Y-m-d'),
                    'unidad' => ($value->cantidad * $unidades_compra),
                    'precio' => $precio_venta,
                    'cod_documento' => $factura,
                    'usuario' => $this->session->userdata('user_id'),
                    'razon' => 'E',
                ));
            }
        }

        if ($producto->combo == 1) {

            // productos del combo

            $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
            $productos_combo = $this->connection->query($query_productos_combo);
            foreach ($productos_combo->result() as $key => $value) {

                // Stock del ingrediente

                $query_stock = "select * from stock_actual where almacen_id =" . $alm . " and producto_id=" . $value->id_producto;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                $this->connection->insert('stock_diario', array(
                    'producto_id' => $value->id_producto,
                    'almacen_id' => $alm,
                    'fecha' => date('Y-m-d'),
                    'unidad' => ($value->cantidad * $unidades_compra),
                    'precio' => $precio_venta,
                    'cod_documento' => $factura,
                    'usuario' => $this->session->userdata('user_id'),
                    'razon' => 'E',
                ));
            }
        }

        $this->connection->where('id', $id);
        $this->connection->delete("detalle_venta");
        $total_venta = 0;
        $product = "SELECT
     SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
     ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
      ,SUM( `unidades` * `descuento` ) AS total_descuento
     FROM  `venta`
     inner join detalle_venta on venta.id = detalle_venta.venta_id
     WHERE venta.id  IN (" . $venta . ")";
        $product = $this->connection->query($product)->result();
        foreach ($product as $dat) {
            $total_venta = ($dat->total_precio_venta - $dat->total_descuento) + $dat->impuestos;
        }

        $this->connection->where('id', $venta);
        $this->connection->set('total_venta', $total_venta);
        $this->connection->update('venta');
        $this->connection->where('id_venta', $venta);
        $this->connection->set('valor_entregado', $total_venta);
        $this->connection->update('ventas_pago');
    }

    public function agregar_actualizar_venta($data, $id)
    {
        $unidades_compra = $data['unidades'];
        $nombre = $data['nombre_producto'];
        $codigo = $data['codigo'];
        $vprecio = $data['precio_venta'];
        $comp = 0;
        $prod = "SELECT * FROM `producto` where id = '" . $data['product_id'] . "' ";
        $productosconsult = $this->connection->query($prod)->result();
        foreach ($productosconsult as $dat) {
            $comp = $dat->precio_compra;
        }

        $compra_final_1 = $data['precio_venta'] - $comp;
        $compra_final_2 = $compra_final_1 * $data['unidades'];
        $compra_final_3 = $data['descuento'] * $data['unidades'];
        if (empty($data['descripcion'])) {
            $data['descripcion'] = '';
        }
        $data_detalles[] = array(
            'venta_id' => $id,
            'codigo_producto' => $data['codigo'],
            'precio_venta' => $data['precio_venta'],
            'unidades' => $data['unidades'],
            'nombre_producto' => utf8_encode($data['nombre_producto']),
            'descripcion_producto' => $data['descripcion'],
            'impuesto' => $data['impuesto'],
            'descuento' => $data['descuento'],
            'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            'producto_id' => $data['product_id'],
        );
        $venta_stock = "select factura from venta where id=" . $data["id_compra"];
        $venta_stock = $this->connection->query($venta_stock)->result();
        foreach ($venta_stock as $value) {
            $factura = $value->factura;
        }

        $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $data['product_id'];
        $almacen = $this->connection->query($query_stock)->row();
        $unidades_actual = $almacen->unidades;
        $unidades = $unidades_actual - $data['unidades'];
        $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $data['product_id'])->update('stock_actual', array(
            'unidades' => $unidades,
        ));
        $this->connection->insert('stock_diario', array(
            'producto_id' => $data['product_id'],
            'almacen_id' => $data['almacen_id'],
            'fecha' => date('Y-m-d'),
            'unidad' => '-' . $data['unidades'],
            'precio' => $data['precio_venta'],
            'cod_documento' => $factura,
            'usuario' => $this->session->userdata('user_id'),
            'razon' => 'S',
        ));
        /*Ingredientes =================================================== */
        $query_producto = "select * from producto where id =" . $data['product_id'];
        $producto = $this->connection->query($query_producto)->row();
        if ($producto->ingredientes == 1) {

            // Ingredientes del producto

            $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $data['product_id'];
            $ingredientes_producto = $this->connection->query($query_ingredientes_producto);
            foreach ($ingredientes_producto->result() as $key => $value) {

                // Stock del ingrediente

                $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value->id_ingrediente;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                // Actualizar stock

                $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $value->id_ingrediente)->update('stock_actual', array(
                    'unidades' => $unidades,
                ));

                // Insertar stock diario

                $this->connection->insert('stock_diario', array(
                    'producto_id' => $value->id_ingrediente,
                    'almacen_id' => $data['almacen_id'],
                    'fecha' => date('Y-m-d'),
                    'unidad' => '-' . ($value->cantidad * $unidades_compra),
                    'precio' => 0,
                    'cod_documento' => $factura,
                    'usuario' => $this->session->userdata('user_id'),
                    'razon' => 'S',
                ));
            }
        }

        if ($producto->combo == 1) {

            // productos del combo

            $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $data['product_id'];
            $productos_combo = $this->connection->query($query_productos_combo);
            foreach ($productos_combo->result() as $key => $value) {

                // Stock del ingrediente

                $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value->id_producto;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                // Actualizar stock

                $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $value->id_producto)->update('stock_actual', array(
                    'unidades' => $unidades,
                ));

                // Insertar stock diario

                $this->connection->insert('stock_diario', array(
                    'producto_id' => $value->id_producto,
                    'almacen_id' => $data['almacen_id'],
                    'fecha' => date('Y-m-d'),
                    'unidad' => '-' . ($value->cantidad * $unidades_compra),
                    'precio' => 0,
                    'cod_documento' => $factura,
                    'usuario' => $this->session->userdata('user_id'),
                    'razon' => 'S',
                ));
            }
        }

        $this->connection->insert_batch("detalle_venta", $data_detalles);
        $total_venta = 0;
        $product = "SELECT
     SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
     ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
      ,SUM( `unidades` * `descuento` ) AS total_descuento
     FROM  `venta`
     inner join detalle_venta on venta.id = detalle_venta.venta_id
     WHERE venta.id  IN (" . $id . ")";
        $product = $this->connection->query($product)->result();
        foreach ($product as $dat) {
            $total_venta = ($dat->total_precio_venta - $dat->total_descuento) + $dat->impuestos;
        }

        $this->connection->where('id', $id);
        $this->connection->set('total_venta', $total_venta);
        $this->connection->update('venta');
        $this->connection->where('id_venta', $id);
        $this->connection->set('valor_entregado', $total_venta);
        $this->connection->update('ventas_pago');
    }

    public function actualizar_venta($data, $id)
    {
        $id_producto = 0;
        $nombre_producto = 0;
        $codigo_producto = 0;
        $alma = 0;
        $alma = $data['almacen_id'];
        $user_id = $this->session->userdata('user_id');
        if ($data['codigo_interno_producto'] != '' && $data['codigo_interno_producto'] != '0') {
            $product = $this->connection->query("SELECT * FROM producto where id = '" . $data['codigo_interno_producto'] . "' limit 1")->result();
        } else {
            $product = $this->connection->query("SELECT * FROM producto where nombre = '" . $data['nombre_producto'] . "' limit 1")->result();
        }

        foreach ($product as $dat) {
            $id_producto = $dat->id;
            $nombre_producto = $dat->nombre;
            $codigo_producto = $dat->codigo;
        }

        $query_detalle = "select unidades from detalle_venta where id = '" . $data['id'] . "'";
        $detalle = $this->connection->query($query_detalle)->row();
        $query_stock = "select * from stock_actual where almacen_id =" . $alma . " and producto_id=" . $id_producto;
        $almacen = $this->connection->query($query_stock)->row();
        $unidades_actual = $almacen->unidades;
        $unidades_detalles = $detalle->unidades;
        $razon = 'E';
        $signo = '';
        $unidades_1 = $unidades_actual + $unidades_detalles;
        $unidades = $unidades_1 - $data['unidades'];
        $this->connection->where('almacen_id', $alma)->where('producto_id', $id_producto)->update('stock_actual', array(
            'unidades' => $unidades,
        ));
        $this->connection->insert('stock_diario', array(
            'producto_id' => $id_producto,
            'almacen_id' => $alma,
            'fecha' => date('Y-m-d'),
            'unidad' => $signo . $data['unidades'],
            'precio' => $data['precio_venta'],
            'cod_documento' => $data['id_compra'],
            'usuario' => $this->session->userdata('user_id'),
            'razon' => $razon,
        ));
        /*
        $value1['id_inventario'] = $id;
        $value1['codigo_barra'] = $codigo_producto;
        $value1['cantidad'] = $data['unidades'];
        $value1['precio_compra'] = $data['precio_venta'];
        $value1['existencias'] = $unidades_actual;
        $value1['nombre'] = $nombre_producto;
        $value1['total_inventario'] = ($data['precio_venta'] * $data['unidades']);
        $data_detalles[] = $value1;
        $this->connection->insert_batch("movimiento_detalle",$data_detalles);
         */
        $array_datos = array(
            "unidades" => $data['unidades'],
            "precio_venta" => $data['precio_venta'],
            "descuento" => $data['descuento'],
            "impuesto" => $data['id_impuesto'],
        );
        $this->connection->where('id', $data['id']);
        $this->connection->update("detalle_venta", $array_datos);
        $total_venta = 0;
        $product = "SELECT
     SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
     ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
      ,SUM( `unidades` * `descuento` ) AS total_descuento
     FROM  `venta`
     inner join detalle_venta on venta.id = detalle_venta.venta_id
     WHERE venta.id  IN (" . $data['id_compra'] . ")";
        $product = $this->connection->query($product)->result();
        foreach ($product as $dat) {
            $total_venta = ($dat->total_precio_venta - $dat->total_descuento) + $dat->impuestos;
        }

        $this->connection->where('id', $data['id_compra']);
        $this->connection->set('total_venta', $total_venta);
        $this->connection->update('venta');
        $this->connection->where('id_venta', $data['id_compra']);
        $this->connection->set('valor_entregado', $total_venta);
        $this->connection->update('ventas_pago');
    }

    public function update_electronic_invoice($id, $data) {
        try {
            $this->connection->where('id', $id);
            $this->connection->set('resp_electronic_invoice', $data);
            $this->connection->update("venta");
            
        } catch (Exception $e) {
            print_r($e); die;
        }
    }

    public function crearCamposElectronicInvoice() {
        $sql = "SHOW COLUMNS FROM venta LIKE 'resp_electronic_invoice'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0){
            $sql = "ALTER TABLE venta ADD COLUMN resp_electronic_invoice VARCHAR(3000) NULL";
            $this->connection->query($sql);
        }
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
        $this->connection->delete("ventas");
    }

    /*Crear venta con estado pendiente ; estado = 1*/
    public function pendiente($data)
    {
        try {
            /*----------------------------------------------------
            | VENTA                                              |
            ------------------------------------------------------*/
            /*Inicia transacion*/
            $this->connection->trans_begin();
            /*Cliente general o especifico*/
            if ($data['cliente'] == "") {
                $id_cliente = -1;
            } else {
                $id_cliente = $data['cliente'];
            }
            /*Si existe vendedor*/
            if (!empty($data['vendedor'])) {
                $vendedor = $data['vendedor'];
            } else {
                $vendedor = null;
            }
            $array_cliente = array();
            $no_factura = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $data['usuario'];
            $no_factura_row = $this->connection->query($no_factura)->row();
            $factura_int = $no_factura_row->consecutivo;
            $factura_int++;
            $this->connection->where('id', $no_factura_row->id)->update('almacen', array(
                'consecutivo' => $factura_int,
            ));
            $array_datos = array(
                "fecha" => date('Y-m-d H:i:s'),
                "usuario_id" => $data['usuario'],
                "factura" => $no_factura_row->prefijo . $factura_int,
                "almacen_id" => $no_factura_row->id,
                "total_venta" => $data['total_venta'],
                "cliente_id" => $id_cliente,
                'vendedor' => $vendedor,
                "estado" => 1,
            );
            $this->connection->insert("venta", $array_datos);
            $id = $this->connection->insert_id();
            /*----------------------------------------------------
            | DETALLE VENTA                                      |
            ------------------------------------------------------*/
            $data_detalles = array();
            $query_stock = "";
            foreach ($data['productos'] as $value) {
                $data_detalles[] = array(
                    'venta_id' => $id,
                    'codigo_producto' => $value['codigo'],
                    'precio_venta' => $value['precio_venta'],
                    'unidades' => $value['unidades'],
                    'nombre_producto' => $value['nombre_producto'],
                    'impuesto' => $value['impuesto'],
                    'descuento' => $value['descuento'],
                    'margen_utilidad' => '777',
                );
            }

            $this->connection->insert_batch("detalle_venta", $data_detalles);
            $data['pago']['id_venta'] = $id;
            $this->connection->insert('ventas_pago', $data['pago']);
            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
            }

            return $id;
        } catch (Exception $e) {

            // $this->connection->trans_rollback();

            print_r($e);
            die;
        }
    }

    public function anular($data)
    {
        try {
            $mensaje = "";
            $this->connection->trans_begin();
            if ($this->connection->field_exists('fecha_cierre', 'cierres_caja')) {
                $where_valorcaja = " AND fecha_cierre IS NULL";
            } else {
                $where_valorcaja = "";
            }

            // Si aun estamos dentro del cierre de caja
            $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
            $ocpresult = $this->connection->query($ocp)->result();
            foreach ($ocpresult as $dat) {
                $valor_caja = $dat->valor_opcion;
            }

            if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                if ($this->session->userdata('caja') != "") {
                    $query = "update movimientos_cierre_caja set Id_cierre = " . $this->session->userdata('caja') . ",tabla_mov = 'anulada', tipo_movimiento = 'anulada' WHERE id_mov_tip = '" . $data['id'] . "' and tabla_mov = 'venta' ";
                    $this->connection->query($query);
                } else {
                    if ($this->session->userdata('is_admin') == 't') {

                        // busco caja abierta del usuario de la venta

                        $id_usuario = $this->connection->query("select * from venta where id = {$data['id']}")->row();
                        $hoy = date('Y-m-d');
                        $cierre = $this->connection->query("select id, id_Usuario from cierres_caja where id_Usuario = $id_usuario->usuario_id AND fecha='$hoy' $where_valorcaja")->row();
                        if (count($cierre)) {
                            $query = "update movimientos_cierre_caja set Id_cierre = " . $cierre->id . ",tabla_mov = 'anulada', tipo_movimiento = 'anulada' WHERE id_mov_tip = '" . $data['id'] . "' and tabla_mov = 'venta' ";
                        } else {

                            // busco cierres de cajas abiertas de otros usuarios del mismo almacen

                            $cierre = $this->connection->query("select id, id_Usuario from cierres_caja where fecha='$hoy' $where_valorcaja and id_Almacen=$id_usuario->almacen_id ")->row();
                            if (count($cierre)) {
                                $query = "update movimientos_cierre_caja set Id_cierre = " . $cierre->id . ", tabla_mov = 'anulada', tipo_movimiento = 'anulada' WHERE id_mov_tip = '" . $data['id'] . "' and tabla_mov = 'venta' ";
                                $userida = $this->db->query("SELECT username  FROM users where id =$cierre->id_Usuario")->row();
                                $mensaje = ", la factura se le asignÃƒÆ’Ã‚Â³ al cierre de caja <b>#$cierre->id </b> del usuario <b>$userida->username</b>";
                                $this->session->set_flashdata('message', custom_lang('error_anular_venta', $mensaje));
                            } else {

                                // si no hay cieres de cajas del almacen se le obliga a abrir una caja

                                $this->session->set_flashdata('message', custom_lang('error_anular_venta_admin', "NingÃƒÆ’Ã‚Âºn Usuario del almacen tiene una caja aperturada. Debe realizar la apertura de la caja para anular la venta."));
                                redirect(site_url('caja/apertura'));
                            }
                        }
                    } else {
                        $this->session->set_flashdata('message', custom_lang('error_anular_venta', "Debe realizar la apertura de la caja para anular la venta."));
                        redirect(site_url('caja/apertura'));
                    }
                }
            }

            $venta = $this->connection->query("select * from venta where id = {$data['id']}")->row();
            $this->connection->where('id', $data['id']);
            $this->connection->update("venta", array(
                'estado' => '-1',
            ));
            $this->connection->insert('ventas_anuladas', array(
                'venta_id' => $data['id'],
                'usuario_id' => $data['usuario'],
                'fecha' => date('Y-m-d H:i:s'),
                'motivo' => $data['motivo'],
            ));
            //verifico si tiene plan separe asociado
            $plan = $this->verificar_plan($venta, $data);

            $detalles_venta = $this->connection->query("SELECT * FROM `detalle_venta` where venta_id = {$data['id']}")->result();
            foreach ($detalles_venta as $value) {
                $producto = $this->connection->query("select * from producto where id = '{$value->producto_id}' ")->result();
                foreach ($producto as $prod) {
                    $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $prod->id;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades = 0;
                    $unidades_actual = $almacen->unidades;
                    if ($prod->ingredientes !== '1') {
                        $unidades = $unidades_actual + $value->unidades;
                    }
                    $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $prod->id)->update('stock_actual', array(
                        'unidades' => $unidades,
                    ));
                    /* Update stock imei */
                    $this->connection->select("*");
                    $this->connection->from("producto_seriales");
                    $this->connection->where("id_producto", $prod->id);
                    $this->connection->where("id_venta", $data['id']);
                    $this->connection->where("id_detalle_venta", $value->id);
                    $this->connection->limit(1);
                    $result_imei = $this->connection->get();
                    if ($result_imei->num_rows() > 0) {
                        $this->connection->where("id_producto", $prod->id);
                        $this->connection->where("id_venta", $data['id']);
                        $this->connection->where("id_detalle_venta", $value->id);
                        $this->connection->update("producto_seriales", array(
                            "serial_vendido" => 0,
                        ));
                    }

                    if ($prod->ingredientes == 1) {

                        // Ingredientes del producto

                        $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $prod->id;
                        $ingredientes_producto = $this->connection->query($query_ingredientes_producto);
                        foreach ($ingredientes_producto->result() as $key => $value1) {

                            // Stock del ingrediente

                            $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $value1->id_ingrediente;
                            $almacen1 = $this->connection->query($query_stock)->row();
                            if (count($almacen1)) {
                                $unidades_actual = $almacen1->unidades;
                            } else {
                                $unidades_actual = "0";
                            }
                            // echo $unidades = $unidades_actual + ($value1->cantidad * $value->unidades); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                            $unidades = $unidades_actual + ($value1->cantidad * $value->unidades); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                            // Actualizar stock
                            $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $value1->id_ingrediente)->update('stock_actual', array(
                                'unidades' => $unidades,
                            ));

                            // Insertar stock diario

                            $this->connection->insert('stock_diario', array(
                                'producto_id' => $value1->id_ingrediente,
                                'almacen_id' => $venta->almacen_id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => $value1->cantidad * $value->unidades,
                                'precio' => 0,
                                'cod_documento' => $venta->factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'E',
                            ));
                        }
                    }

                    if ($prod->combo == 1) {

                        // productos del combo

                        $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $prod->id;
                        $productos_combo = $this->connection->query($query_productos_combo);
                        foreach ($productos_combo->result() as $key => $value1) {

                            // Stock del ingrediente

                            $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $value1->id_producto;
                            $almacen1 = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen1->unidades;
                            $unidades = $unidades_actual + ($value1->cantidad * $value->unidades); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                            // Actualizar stock

                            $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $value1->id_producto)->update('stock_actual', array(
                                'unidades' => $unidades,
                            ));

                            // Insertar stock diario

                            $this->connection->insert('stock_diario', array(
                                'producto_id' => $value1->id_producto,
                                'almacen_id' => $venta->almacen_id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => $value1->cantidad * $value->unidades,
                                'precio' => 0,
                                'cod_documento' => $venta->factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'E',
                            ));
                        }
                    }

                    $this->connection->insert('stock_diario', array(
                        'producto_id' => $prod->id,
                        'almacen_id' => $venta->almacen_id,
                        'fecha' => date('Y-m-d'),
                        'unidad' => $value->unidades,
                        'precio' => $value->precio_venta,
                        'cod_documento' => $venta->factura,
                        'usuario' => $data['usuario'],
                        'razon' => 'E',
                    ));

                    // /movimiento

                    $this->connection->insert('movimiento_inventario', array(
                        'user_id' => $data['usuario'],
                        'fecha' => date("Y-m-d H:i:s"),
                        'almacen_id' => $venta->almacen_id,
                        'tipo_movimiento' => 'entrada_anulacion_venta',
                        'codigo_factura' => $venta->factura,
                        'total_inventario' => $prod->precio_compra * $value->unidades,
                        'nota' => 'anulacion:' . $venta->factura,
                    ));
                    $id_movimiento = $this->connection->insert_id();

                    // detalle_movimiento
                    if(empty($prod->codigo)) {
                        $codigo = uniqid();
                        $this->connection->where("id", $prod->id);
                        $this->connection->update("producto", [
                            'codigo' => $codigo
                        ]);
                        $prod->codigo = $codigo;
                    }

                    $this->connection->insert('movimiento_detalle', array(
                        "id_inventario" => $id_movimiento,
                        "codigo_barra" => $prod->codigo,
                        "cantidad" => $value->unidades,
                        "precio_compra" => $prod->precio_compra,
                        "existencias" => $unidades_actual,
                        "nombre" => $prod->nombre,
                        "total_inventario" => $prod->precio_compra * $value->unidades,
                        "producto_id" => $prod->id,
                    ));
                }
            }

            // Si aun estamos dentro del cierre de caja

            if (($this->session->userdata('caja') != "") || ($this->session->userdata('is_admin'))) {
                $query = "DELETE FROM pago WHERE id_factura = '" . $data["id"] . "'";
                $this->connection->query($query);
                $query_1 = "DELETE FROM  puntos_acumulados WHERE factura =  '" . $data["id"] . "' ";
                $this->connection->query($query_1);
            }

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
                return $mensaje;
            }
        } catch (Exception $ex) {
        }
    }

    public function verificar_plan($venta, $data)
    {
        //busco si hay plan separe asociado y venta
        $this->connection->where('venta_id', $data['id']);
        $this->connection->select("*");
        $this->connection->from("plan_separe_factura");
        $this->connection->limit("1");
        $sql_plan_venta = $this->connection->get()->result();

        if (!empty($sql_plan_venta)) {
            //anular el plan separe asociado
            $data_anular_plan = array(
                'id_user_anulacion' => $data['usuario'],
                'fecha_anulacion' => date('Y-m-d'),
                'asunto_anulacion' => "Anulación por la factura $venta->factura",
                'estado' => 3,
            );
            //anulo la factura
            $this->connection->where("venta_id", $data['id']);
            $this->connection->update("plan_separe_factura", $data_anular_plan);

            //busco los pagos asociados al plan para anularlos
            $data_anular_abonos_plan = array(
                'tipo_movimiento' => 'anulada',
            );

            $this->connection->where('id_venta', $sql_plan_venta[0]->id);
            $this->connection->select("*");
            $this->connection->from("plan_separe_pagos");
            $sql_plan_abonos = $this->connection->get()->result();

            foreach ($sql_plan_abonos as $valueabonos) {
                $this->connection->where("id_mov_tip", $valueabonos->id_pago);
                $this->connection->where("tabla_mov", 'plan_separe_pagos');
                $this->connection->update("movimientos_cierre_caja", $data_anular_abonos_plan);
            }
        } else {
            // verificar la cantidad de plan separe con la factura asociada si hay más de uno verificar los detalles de las facturas
            // sino anular la que venga
            //verificamos la cantidad de facturas separadas con el mismo número de facturas
            $this->connection->where('factura', $venta->factura);
            $this->connection->select("*");
            $this->connection->from("plan_separe_factura");
            $plan = $this->connection->get()->result();

            if (!empty($plan)) {
                //verificar la cantidad de colunmas que me regresa
                if (count($plan) == 1) {
                    //se anula el plan separe
                    $data_anular_plan = array(
                        'id_user_anulacion' => $data['usuario'],
                        'fecha_anulacion' => date('Y-m-d'),
                        'estado' => 3,
                        'asunto_anulacion' => "Anulación por la factura $venta->factura",
                        'venta_id' => $venta->id,
                    );
                    //anulo la factura
                    $this->connection->where("id", $plan[0]->id);
                    $this->connection->update("plan_separe_factura", $data_anular_plan);

                    //busco los pagos asociados al plan para anularlos
                    $data_anular_abonos_plan = array(
                        'tipo_movimiento' => 'anulada',
                    );

                    $this->connection->where('id_venta', $plan[0]->id);
                    $this->connection->select("*");
                    $this->connection->from("plan_separe_pagos");
                    $sql_plan_abonos = $this->connection->get()->result();

                    foreach ($sql_plan_abonos as $valueabonos) {
                        $this->connection->where("id_mov_tip", $valueabonos->id_pago);
                        $this->connection->where("tabla_mov", 'plan_separe_pagos');
                        $this->connection->update("movimientos_cierre_caja", $data_anular_abonos_plan);
                    }
                } else {
                    //busco el detalle de la venta
                    $this->connection->select("*");
                    $this->connection->where('venta_id', $venta->id);
                    $this->connection->from("detalle_venta");
                    $this->connection->order_by("producto_id");
                    $detalle_venta = $this->connection->get()->result();
                    //verificar cual de los detalles del plan separe son los mismos

                    foreach ($plan as $valueplan) {
                        //traer el detalle del plan separe
                        $this->connection->select("*");
                        $this->connection->where('venta_id', $valueplan->id);
                        $this->connection->from("plan_separe_detalle");
                        $this->connection->order_by("producto_id");
                        $detalles_plan = $this->connection->get()->result();

                        //verifico si la cantidad de registros es la misma y valido sino descarto
                        $fin = array();
                        if (count($detalle_venta) == count($detalles_plan)) {
                            for ($i = 0; $i < count($detalle_venta); $i++) {
                                $fin[$detalle_venta[$i]->producto_id] = 0;
                                for ($j = 0; $j < count($detalles_plan); $j++) {
                                    if ($detalle_venta[$i]->producto_id == $detalles_plan[$j]->producto_id) {
                                        $fin[$detalle_venta[$i]->producto_id] = 1;
                                        break;
                                    }
                                }
                            }
                        }

                        if (!in_array(0, $fin)) {
                            //este tiene los mismos productos anularlo
                            $data_anular_plan = array(
                                'id_user_anulacion' => $data['usuario'],
                                'fecha_anulacion' => date('Y-m-d'),
                                'estado' => 3,
                                'asunto_anulacion' => "Anulación por la factura $venta->factura",
                                'venta_id' => $venta->id,
                            );
                            //anulo la factura
                            $this->connection->where("id", $valueplan->id);
                            $this->connection->update("plan_separe_factura", $data_anular_plan);

                            //busco los pagos asociados al plan para anularlos
                            $data_anular_abonos_plan = array(
                                'tipo_movimiento' => 'anulada',
                            );

                            $this->connection->where('id_venta', $valueplan->id);
                            $this->connection->select("*");
                            $this->connection->from("plan_separe_pagos");
                            $sql_plan_abonos = $this->connection->get()->result();

                            foreach ($sql_plan_abonos as $valueabonos) {
                                $this->connection->where("id_mov_tip", $valueabonos->id_pago);
                                $this->connection->where("tabla_mov", 'plan_separe_pagos');
                                $this->connection->update("movimientos_cierre_caja", $data_anular_abonos_plan);
                            }
                        }
                    }
                }
            }
        }
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

    public function modificar_propina()
    {
        $db_config_id = $this->session->userdata('db_config_id');

        // var_dump($db_config_id); exit();

        $sql = "SELECT sum(precio_venta) as suma, venta_id as id, descripcion_producto FROM detalle_venta as a WHERE a.venta_id IN (SELECT venta_id FROM detalle_venta as b WHERE b.nombre_producto= 'PROPINA') group by a.venta_id, a.descripcion_producto ";
        foreach ($this->connection->query($sql)->result() as $value) {
            if ($value->descripcion_producto == '') {
                $suma = ((10 * $value->suma) / 100);
                $this->connection->query("UPDATE detalle_venta SET precio_venta = '" . $suma . "' WHERE venta_id = '" . $value->id . "' and nombre_producto= 'PROPINA'");
            }
        }
    }

    public function getDataCotizacionById($id)
    {
        $this->connection->select("*, presupuestos_detalles.impuesto as impuesto");
        $this->connection->from("presupuestos_detalles");
        $this->connection->join("presupuestos", "presupuestos.id_presupuesto = presupuestos_detalles.id_presupuesto");
        $this->connection->join("clientes", "presupuestos.id_cliente = clientes.id_cliente");
        $this->connection->join("producto", "presupuestos_detalles.fk_id_producto = producto.id");
        $this->connection->where("presupuestos_detalles.id_presupuesto", $id);
        $query = $this->connection->get();

        // var_dump($query->result());

        return $query->result();
    }

    public function addOffline($fullData)
    {
        try {
            $this->connection->trans_begin();
            foreach ($fullData as $data) {
                if ($data['cliente'] == "") {
                    $id_cliente = -1;
                } else {
                    $id_cliente = $data['cliente'];
                }
                if (count($data['productos'] > 0)) {
                    $array_cliente = array();
                    $numero_factura = 0;
                    $prefijo_factura = 0;
                    $sobrecostosino = 0;
                    $nit = 0;
                    $multiformapago = 0;
                    $valor_caja = 0;
                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'numero_factura' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $numero_factura = $dat->valor_opcion;
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'prefijo_factura' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $prefijo_factura = $dat->valor_opcion;
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'numero' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $opcnum = $dat->valor_opcion;
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'sobrecosto' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $sobrecostosino = $dat->valor_opcion;
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'nit' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $nit = $dat->valor_opcion;
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'multiples_formas_pago' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $multiformapago = ($data['sistema'] == 'Pos') ? $dat->valor_opcion : 'no';
                    }

                    $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
                    $ocpresult = $this->connection->query($ocp)->result();
                    foreach ($ocpresult as $dat) {
                        $valor_caja = $dat->valor_opcion;
                    }

                    $no_factura = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $data['usuario'];
                    $no_factura_row = $this->connection->query($no_factura)->row();
                    $factura_int = $no_factura_row->consecutivo;
                    $factura_int++;
                    $fact = 0;
                    $fac = "SELECT fecha FROM `venta` order by id desc limit 1 ";
                    $facresult = $this->connection->query($fac)->result();
                    foreach ($facresult as $dat) {
                        $fact = $dat->fecha;
                    }

                    if ($data['fecha'] != $fact) {
                        if ($opcnum == 'no') {
                            $num_factura = 0;
                            $this->connection->where('id', $no_factura_row->id)->update('almacen', array(
                                'consecutivo' => $factura_int,
                            ));
                            $num_factura = $no_factura_row->prefijo . $factura_int;
                        }

                        if ($opcnum == 'si') {
                            $num_factura = 0;
                            $numero_factura++;
                            $this->connection->where('id', '26')->update('opciones', array(
                                'valor_opcion' => $numero_factura,
                            ));
                            $num_factura = $prefijo_factura . $numero_factura;
                        }

                        $array_datos = array(
                            "fecha" => $data['fecha'],
                            "fecha_vencimiento" => $data['fecha_vencimiento'],
                            "usuario_id" => $data['usuario'],
                            "factura" => $num_factura,
                            "almacen_id" => $no_factura_row->id,
                            "total_venta" => $data['total_venta'],
                            "cliente_id" => $id_cliente,
                            "tipo_factura" => $data['tipo_factura'],
                            "promocion" => $data['promocion'],
                            "porcentaje_descuento_general" => $data['descuento_general'],
                            "factura_electronica" => $data['facturacion_electronica'] === "true" ? "1" : "0",
                        );
                        if (!empty($data['vendedor'])) {
                            $array_datos["vendedor"] = $data['vendedor'];
                        }
                        if (!empty($data['vendedor_2'])) {
                            $array_datos["vendedor_2"] = $data['vendedor_2'];
                        }
                        $this->connection->insert("venta", $array_datos);
                        $id = $this->connection->insert_id();

                        // puntos -----------------------------------------------------------------------------------------------------------------------

                        $ocp = "SELECT plan_id FROM cliente_plan_punto where id_cliente = '" . $id_cliente . "' ";
                        $ocpresult = $this->connection->query($ocp)->result();
                        $puntos = '';
                        foreach ($ocpresult as $dat) {
                            $puntos = $dat->plan_id;
                        }

                        if ($puntos != '') {
                            $ocp = "SELECT puntos, valor, iva FROM plan_puntos where id_puntos = '" . $puntos . "' ";
                            $ocpresult = $this->connection->query($ocp)->result();
                            $puntos = '';
                            $valor = '';
                            $iva = '';
                            $puntos_final = 0;
                            $total_venta_puntos = 0;
                            $total_saldo_a_favor = 0;
                            $da_puntos = true;
                            foreach ($ocpresult as $dat) {
                                $puntos = $dat->puntos;
                                $valor = $dat->valor;
                                $iva = $dat->iva;
                            }

                            $valor_entregado = 0;
                            if ($iva == 'SI') {
                                $total_venta_puntos = $data['total_venta'];
                            }

                            if ($iva == 'NO') {
                                $total_venta_puntos = $data['subtotal_input'];
                            }

                            for ($x = 0; $x <= 5; $x++) {
                                $label = $x == 0 ? 'pago' : 'pago_' . $x;
                                if ($data[$label]['forma_pago'] == 'Saldo_a_Favor') {
                                    $total_saldo_a_favor += $data[$label]['valor_entregado'];
                                }
                                if ($data[$label]['forma_pago'] == 'Puntos') {
                                    $valor_entregado += $data[$label]['valor_entregado'];
                                }
                            }

                            /*Se restan los puntos que tengan saldo a favor*/
                            $total_venta_puntos -= $total_saldo_a_favor;
                            $puntos_final = ($puntos / $valor) * $total_venta_puntos;
                            if ($valor_entregado == 0 && $data['pago']['forma_pago'] == 'Puntos') {
                                $valor_entregado = $data['pago']['valor_entregado'];
                            }

                            if ($valor_entregado == 0) {
                                $ocp = "INSERT INTO puntos_acumulados (fecha,factura,total_factura,puntos,cliente,tipo) VALUES ('" . date($data['fecha']) . "','$id','" . $total_venta_puntos . "','" . (int) $puntos_final . "','$id_cliente','Acumulados') ";
                                $this->connection->query($ocp);
                            }

                            if ($valor_entregado > 0) {
                                $query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$id_cliente' ";
                                $queryresult = $this->connection->query($query)->result();
                                $total_puntos = '0';
                                foreach ($queryresult as $dat) {
                                    $total_puntos = $dat->total_puntos;
                                }

                                $query = "SELECT valor_opcion FROM opciones where nombre_opcion = 'punto_valor'";
                                $queryresult = $this->connection->query($query)->result();
                                $valor_puntos = '0';
                                foreach ($queryresult as $dat) {
                                    $valor_puntos = $dat->valor_opcion;
                                }

                                $pago_puntos = (int) ($valor_entregado / $valor_puntos);
                                $ocp = "DELETE FROM puntos_acumulados WHERE cliente = '$id_cliente' ";
                                $this->connection->query($ocp);
                                if (($total_puntos - $pago_puntos) > 0) {
                                    $nuevos_puntos = ($total_puntos - $pago_puntos);
                                    $ocp = "INSERT INTO puntos_acumulados (fecha, factura, total_factura, puntos, cliente, tipo) VALUES ('" . date($data['fecha']) . "','$id','" . $total_venta_puntos . "','" . (int) $nuevos_puntos . "','$id_cliente','Acumulados') ";
                                    $this->connection->query($ocp);
                                }
                            }

                            /*echo $valor_entregado.', '.$total_venta_puntos;
                        if($valor_entregado > 0)
                        {
                        if($valor_entregado < $total_venta_puntos)
                        {
                        } else {
                        $ocp = "DELETE  FROM puntos_acumulados WHERE cliente = '$id_cliente' ";
                        $this->connection->query($ocp);
                        }
                        }*/
                        }

                        // puntos --------------------------------------------------------------------------------------------------------------------------

                        if ($data['nota'] != '') {
                            $this->connection->where('id', $id);
                            $this->connection->set('nota', $data['nota']);
                            $this->connection->update('venta');
                        }

                        $data_detalles = array();
                        $query_stock = "";
                        $descuento_prod = "0";
                        $descuento_prod = "0";
                        foreach ($data['productos'] as $value) {
                            $unidades_compra = $value['unidades'];
                            $product_id = $value['product_id'];
                            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
                            $comp = 0;
                            foreach ($prod as $dat) {
                                $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                            }

                            /* if($data['descuento_general'] > 0 && $value['descuento'] == 0)
                            {
                            $descuento_prod = (($value['precio_venta'] * $data['descuento_general']) / 100);
                            } else {
                            $descuento_prod = $value['descuento'];
                            }              */
                            $descuento_prod = $value['descuento'];
                            $porcentaje_descuentop = 0;
                            if (!empty($value['porcentaje_descuentop'])) {
                                $porcentaje_descuentop = $value['porcentaje_descuentop'];
                            }

                            $compra_final_1 = $value['precio_venta'] - $comp;
                            $compra_final_2 = $compra_final_1 * $value['unidades'];
                            $compra_final_3 = $value['descuento'] * $value['unidades'];
                            if (empty($value['descripcion'])) {
                                $value['descripcion'] = '';
                            }
                            if ($value['unidades'] > 0) {
                                $data_detalles[] = array(
                                    'venta_id' => $id,
                                    'codigo_producto' => $value['codigo'],
                                    'precio_venta' => $value['precio_venta'],
                                    'unidades' => $value['unidades'],
                                    'nombre_producto' => $value['nombre_producto'],

                                    // 'descripcion_producto' => $value['margen_utilidad'],//se guarda si es un producto que vien por promocion

                                    'descripcion_producto' => $value['descripcion'], //se guarda si es un producto que vien por promocion
                                    'impuesto' => $value['impuesto'],
                                    'descuento' => $descuento_prod,
                                    'producto_id' => $product_id,
                                    'margen_utilidad' => ($compra_final_2 - $compra_final_3),

                                    // 'margen_utilidad' => $margenUtilidad,

                                    'porcentaje_descuento' => $porcentaje_descuentop,
                                );
                            }

                            // $descuento_prod +=  $descuento_prod;

                            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value['product_id'];
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual - $value['unidades'];
                            $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value['product_id'])->update('stock_actual', array(
                                'unidades' => $unidades,
                            ));
                            $stock_diario_ = $this->connection->insert('stock_diario', array(
                                'producto_id' => $value['product_id'],
                                'almacen_id' => $no_factura_row->id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => '-' . $value['unidades'],
                                'precio' => $value['precio_venta'],
                                'cod_documento' => $num_factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'S',
                            ));
                            /*Ingredientes =================================================== */
                            $query_producto = "select * from producto where id =" . $value['product_id'];
                            $producto = $this->connection->query($query_producto)->row();
                            if ($producto->ingredientes == 1) {

                                // Ingredientes del producto

                                $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['product_id'];
                                $ingredientes_producto = $this->connection->query($query_ingredientes_producto);
                                foreach ($ingredientes_producto->result() as $key => $value) {

                                    // Stock del ingrediente

                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_ingrediente;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = $almacen->unidades;
                                    $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                                    // Actualizar stock

                                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_ingrediente)->update('stock_actual', array(
                                        'unidades' => $unidades,
                                    ));

                                    // Insertar stock diario

                                    $this->connection->insert('stock_diario', array(
                                        'producto_id' => $value->id_ingrediente,
                                        'almacen_id' => $no_factura_row->id,
                                        'fecha' => date('Y-m-d'),
                                        'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                        'precio' => 0,
                                        'cod_documento' => $num_factura,
                                        'usuario' => $data['usuario'],
                                        'razon' => 'S',
                                    ));
                                }
                            }

                            if ($producto->combo == 1) {

                                // productos del combo

                                $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
                                $productos_combo = $this->connection->query($query_productos_combo);
                                foreach ($productos_combo->result() as $key => $value) {

                                    // Stock del ingrediente

                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_producto;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = $almacen->unidades;
                                    $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)

                                    // Actualizar stock

                                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_producto)->update('stock_actual', array(
                                        'unidades' => $unidades,
                                    ));

                                    // Insertar stock diario

                                    $this->connection->insert('stock_diario', array(
                                        'producto_id' => $value->id_producto,
                                        'almacen_id' => $no_factura_row->id,
                                        'fecha' => date('Y-m-d'),
                                        'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                        'precio' => 0,
                                        'cod_documento' => $num_factura,
                                        'usuario' => $data['usuario'],
                                        'razon' => 'S',
                                    ));
                                }
                            }

                            /*.......................................*/
                        }

                        $this->connection->insert_batch("detalle_venta", $data_detalles);
                        $data['pago']['id_venta'] = $id;
                        if ($data['pago']['forma_pago'] == 'Credito') {
                            for ($x = 1; $x <= 5; $x++) {
                                if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                    if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago_' . $x]['forma_pago'],
                                            "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                        $username = $this->session->userdata('username');
                                        $db_config_id = $this->session->userdata('db_config_id');
                                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                        foreach ($user as $dat) {
                                            $id_user = $dat->id;
                                        }

                                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                            $array_datos = array(
                                                "Id_cierre" => $this->session->userdata('caja'),
                                                "hora_movimiento" => date('H:i:s'),
                                                "id_usuario" => $id_user,
                                                "tipo_movimiento" => 'entrada_venta',
                                                "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                "numero" => $num_factura,
                                                "id_mov_tip" => $id,
                                                "tabla_mov" => "venta",
                                            );
                                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                        }
                                    }
                                }
                            }
                        }

                        // Insertar relacion pago giftcard

                        if (array_key_exists('cod_gift', $data['pago'])) { // Si el parametro giftcar ha sido definido en el frontend
                            if ($data['pago']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                $codGift = $data['pago']['cod_gift'];
                                $giftData = array(
                                    'id_venta' => $id,
                                    'codigo_gift' => $codGift,
                                );
                                $this->connection->insert('ventas_pago_giftcard', $giftData);
                            }

                            unset($data['pago']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                        }

                        $this->connection->insert('ventas_pago', $data['pago']);
                        if ($multiformapago == 'si' && $data['sistema'] == 'Pos') {
                            if ($data['pago_1']['valor_entregado'] != '0') {
                                if ($data['pago_1']['forma_pago'] == 'Credito') {
                                    if ($data['pago']['forma_pago'] != 'Credito') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago']['forma_pago'],
                                            "cantidad" => $data['pago']['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                    }

                                    for ($x = 1; $x <= 5; $x++) {
                                        if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                                $array_datos = array(
                                                    "fecha_pago" => $data['fecha'],
                                                    "notas" => '',
                                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                                    "importe_retencion" => 0,
                                                    "id_factura" => $id,
                                                );
                                                $this->connection->insert("pago", $array_datos);
                                                $username = $this->session->userdata('username');
                                                $db_config_id = $this->session->userdata('db_config_id');
                                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                                foreach ($user as $dat) {
                                                    $id_user = $dat->id;
                                                }

                                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                                    $array_datos = array(
                                                        "Id_cierre" => $this->session->userdata('caja'),
                                                        "hora_movimiento" => date('H:i:s'),
                                                        "id_usuario" => $id_user,
                                                        "tipo_movimiento" => 'entrada_venta',
                                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                        "numero" => $num_factura,
                                                        "id_mov_tip" => $id,
                                                        "tabla_mov" => "venta",
                                                    );
                                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Insertar relacion pago giftcard

                                if (array_key_exists('cod_gift', $data['pago_1'])) { // Si el parametro giftcar ha sido definido en el frontend
                                    if ($data['pago_1']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                        $codGift = $data['pago_1']['cod_gift'];
                                        $giftData = array(
                                            'id_venta' => $id,
                                            'codigo_gift' => $codGift,
                                        );
                                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                                    }

                                    unset($data['pago_1']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                                }

                                $data['pago_1']['id_venta'] = $id;
                                $this->connection->insert('ventas_pago', $data['pago_1']);
                            }

                            if ($data['pago_2']['valor_entregado'] != '0') {
                                if ($data['pago_2']['forma_pago'] == 'Credito') {
                                    if (
                                        /*$data['pago']['forma_pago'] == 'efectivo' ||
                                        $data['pago']['forma_pago'] == 'tarjeta_credito' ||
                                        $data['pago']['forma_pago'] == 'tarjeta_debito' ||
                                        $data['pago']['forma_pago'] == 'Saldo_a_Favor' ||
                                        $data['pago']['forma_pago'] == 'Visa_dÃƒÆ’Ã‚Â©bito' ||
                                        $data['pago']['forma_pago'] == 'Visa_crÃƒÆ’Ã‚Â©dito'||
                                        $data['pago']['forma_pago'] == 'MasterCard_dÃƒÆ’Ã‚Â©bito'||
                                        $data['pago']['forma_pago'] == 'American_Express'||
                                        $data['pago']['forma_pago'] == 'MasterCard'||
                                        $data['pago']['forma_pago'] == 'Gift_Card'||
                                        $data['pago']['forma_pago'] == 'MercadoPago'||
                                        $data['pago']['forma_pago'] == 'Linio'||
                                        $data['pago']['forma_pago'] == 'Bancolombia'||
                                        $data['pago']['forma_pago'] == 'Efecty'||
                                        $data['pago']['forma_pago'] == 'Interrapidisimo'||
                                        $data['pago']['forma_pago'] == 'Baloto'||
                                        $data['pago']['forma_pago'] == 'Sodexo'||
                                        $data['pago']['forma_pago'] == 'Puntos'*/
                                        $data['pago']['forma_pago'] != 'Credito') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago']['forma_pago'],
                                            "cantidad" => $data['pago']['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                    }

                                    for ($x = 1; $x <= 5; $x++) {
                                        if (
                                            /*$data['pago_'.$x]['forma_pago'] == 'efectivo' ||
                                            $data['pago_'.$x]['forma_pago'] == 'tarjeta_credito' ||
                                            $data['pago_'.$x]['forma_pago'] == 'tarjeta_debito' ||
                                            $data['pago_'.$x]['forma_pago'] == 'Saldo_a_Favor' ||
                                            $data['pago_'.$x]['forma_pago'] == 'Visa_dÃƒÆ’Ã‚Â©bito' ||
                                            $data['pago_'.$x]['forma_pago'] == 'Visa_crÃƒÆ’Ã‚Â©dito'||
                                            $data['pago_'.$x]['forma_pago'] == 'MasterCard_dÃƒÆ’Ã‚Â©bito'||
                                            $data['pago_'.$x]['forma_pago'] == 'American_Express'||
                                            $data['pago_'.$x]['forma_pago'] == 'MasterCard'||
                                            $data['pago_'.$x]['forma_pago'] == 'Gift_Card'||
                                            $data['pago_'.$x]['forma_pago'] == 'MercadoPago'||
                                            $data['pago_'.$x]['forma_pago'] == 'Linio'||
                                            $data['pago_'.$x]['forma_pago'] == 'Bancolombia'||
                                            $data['pago_'.$x]['forma_pago'] == 'Efecty'||
                                            $data['pago_'.$x]['forma_pago'] == 'Interrapidisimo'||
                                            $data['pago_'.$x]['forma_pago'] == 'Baloto'||
                                            $data['pago_'.$x]['forma_pago'] == 'Sodexo'||
                                            $data['pago_'.$x]['forma_pago'] == 'Puntos'*/
                                            $data['pago_' . $x]['forma_pago'] != 'Credito') {
                                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                                $array_datos = array(
                                                    "fecha_pago" => $data['fecha'],
                                                    "notas" => '',
                                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                                    "importe_retencion" => 0,
                                                    "id_factura" => $id,
                                                );
                                                $this->connection->insert("pago", $array_datos);
                                                $username = $this->session->userdata('username');
                                                $db_config_id = $this->session->userdata('db_config_id');
                                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                                foreach ($user as $dat) {
                                                    $id_user = $dat->id;
                                                }

                                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                                    $array_datos = array(
                                                        "Id_cierre" => $this->session->userdata('caja'),
                                                        "hora_movimiento" => date('H:i:s'),
                                                        "id_usuario" => $id_user,
                                                        "tipo_movimiento" => 'entrada_venta',
                                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                        "numero" => $num_factura,
                                                        "id_mov_tip" => $id,
                                                        "tabla_mov" => "venta",
                                                    );
                                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Insertar relacion pago giftcard

                                if (array_key_exists('cod_gift', $data['pago_2'])) { // Si el parametro giftcar ha sido definido en el frontend
                                    if ($data['pago_2']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                        $codGift = $data['pago_2']['cod_gift'];
                                        $giftData = array(
                                            'id_venta' => $id,
                                            'codigo_gift' => $codGift,
                                        );
                                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                                    }

                                    unset($data['pago_2']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                                }

                                $data['pago_2']['id_venta'] = $id;
                                $this->connection->insert('ventas_pago', $data['pago_2']);
                            }

                            if ($data['pago_3']['valor_entregado'] != '0') {
                                if ($data['pago_3']['forma_pago'] == 'Credito') {
                                    if ($data['pago']['forma_pago'] != 'Credito') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago']['forma_pago'],
                                            "cantidad" => $data['pago']['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                    }

                                    for ($x = 1; $x <= 5; $x++) {
                                        if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                                $array_datos = array(
                                                    "fecha_pago" => $data['fecha'],
                                                    "notas" => '',
                                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                                    "importe_retencion" => 0,
                                                    "id_factura" => $id,
                                                );
                                                $this->connection->insert("pago", $array_datos);
                                                $username = $this->session->userdata('username');
                                                $db_config_id = $this->session->userdata('db_config_id');
                                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                                foreach ($user as $dat) {
                                                    $id_user = $dat->id;
                                                }

                                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                                    $array_datos = array(
                                                        "Id_cierre" => $this->session->userdata('caja'),
                                                        "hora_movimiento" => date('H:i:s'),
                                                        "id_usuario" => $id_user,
                                                        "tipo_movimiento" => 'entrada_venta',
                                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                        "numero" => $num_factura,
                                                        "id_mov_tip" => $id,
                                                        "tabla_mov" => "venta",
                                                    );
                                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Insertar relacion pago giftcard

                                if (array_key_exists('cod_gift', $data['pago_3'])) { // Si el parametro giftcar ha sido definido en el frontend
                                    if ($data['pago_3']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                        $codGift = $data['pago_3']['cod_gift'];
                                        $giftData = array(
                                            'id_venta' => $id,
                                            'codigo_gift' => $codGift,
                                        );
                                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                                    }

                                    unset($data['pago_3']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                                }

                                $data['pago_3']['id_venta'] = $id;
                                $this->connection->insert('ventas_pago', $data['pago_3']);
                            }

                            if ($data['pago_4']['valor_entregado'] != '0') {
                                if ($data['pago_4']['forma_pago'] == 'Credito') {
                                    if ($data['pago']['forma_pago'] != 'Credito') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago']['forma_pago'],
                                            "cantidad" => $data['pago']['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                    }

                                    for ($x = 1; $x <= 5; $x++) {
                                        if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                                $array_datos = array(
                                                    "fecha_pago" => $data['fecha'],
                                                    "notas" => '',
                                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                                    "importe_retencion" => 0,
                                                    "id_factura" => $id,
                                                );
                                                $this->connection->insert("pago", $array_datos);
                                                $username = $this->session->userdata('username');
                                                $db_config_id = $this->session->userdata('db_config_id');
                                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                                foreach ($user as $dat) {
                                                    $id_user = $dat->id;
                                                }

                                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                                    $array_datos = array(
                                                        "Id_cierre" => $this->session->userdata('caja'),
                                                        "hora_movimiento" => date('H:i:s'),
                                                        "id_usuario" => $id_user,
                                                        "tipo_movimiento" => 'entrada_venta',
                                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                        "numero" => $num_factura,
                                                        "id_mov_tip" => $id,
                                                        "tabla_mov" => "venta",
                                                    );
                                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Insertar relacion pago giftcard

                                if (array_key_exists('cod_gift', $data['pago_4'])) { // Si el parametro giftcar ha sido definido en el frontend
                                    if ($data['pago_4']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                        $codGift = $data['pago_4']['cod_gift'];
                                        $giftData = array(
                                            'id_venta' => $id,
                                            'codigo_gift' => $codGift,
                                        );
                                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                                    }

                                    unset($data['pago_4']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                                }

                                $data['pago_4']['id_venta'] = $id;
                                $this->connection->insert('ventas_pago', $data['pago_4']);
                            }

                            if ($data['pago_5']['valor_entregado'] != '0') {
                                if ($data['pago_4']['forma_pago'] == 'Credito') {
                                    if ($data['pago']['forma_pago'] != 'Credito') {
                                        $array_datos = array(
                                            "fecha_pago" => $data['fecha'],
                                            "notas" => '',
                                            "tipo" => $data['pago']['forma_pago'],
                                            "cantidad" => $data['pago']['valor_entregado'],
                                            "importe_retencion" => 0,
                                            "id_factura" => $id,
                                        );
                                        $this->connection->insert("pago", $array_datos);
                                    }

                                    for ($x = 1; $x <= 5; $x++) {
                                        if ($data['pago_' . $x]['forma_pago'] != 'Credito') {
                                            if ($data['pago_' . $x]['valor_entregado'] > '0') {
                                                $array_datos = array(
                                                    "fecha_pago" => $data['fecha'],
                                                    "notas" => '',
                                                    "tipo" => $data['pago_' . $x]['forma_pago'],
                                                    "cantidad" => $data['pago_' . $x]['valor_entregado'],
                                                    "importe_retencion" => 0,
                                                    "id_factura" => $id,
                                                );
                                                $this->connection->insert("pago", $array_datos);
                                                $username = $this->session->userdata('username');
                                                $db_config_id = $this->session->userdata('db_config_id');
                                                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                                                foreach ($user as $dat) {
                                                    $id_user = $dat->id;
                                                }

                                                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                                                    $array_datos = array(
                                                        "Id_cierre" => $this->session->userdata('caja'),
                                                        "hora_movimiento" => date('H:i:s'),
                                                        "id_usuario" => $id_user,
                                                        "tipo_movimiento" => 'entrada_venta',
                                                        "valor" => ($data['pago_' . $x]['valor_entregado'] - $data['pago_' . $x]['cambio']),
                                                        "forma_pago" => $data['pago_' . $x]['forma_pago'],
                                                        "numero" => $num_factura,
                                                        "id_mov_tip" => $id,
                                                        "tabla_mov" => "venta",
                                                    );
                                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                                }
                                            }
                                        }
                                    }
                                }

                                // Insertar relacion pago giftcard

                                if (array_key_exists('cod_gift', $data['pago_5'])) { // Si el parametro giftcar ha sido definido en el frontend
                                    if ($data['pago_5']['forma_pago'] == 'Gift_Card') { // Si el metodo de pago es giftcard
                                        $codGift = $data['pago_5']['cod_gift'];
                                        $giftData = array(
                                            'id_venta' => $id,
                                            'codigo_gift' => $codGift,
                                        );
                                        $this->connection->insert('ventas_pago_giftcard', $giftData);
                                    }

                                    unset($data['pago_5']['cod_gift']); // eliminamos el campo del array para que no se produzca error al guardar en ventas_pago
                                }

                                $data['pago_5']['id_venta'] = $id;
                                $this->connection->insert('ventas_pago', $data['pago_5']);
                            }
                        }

                        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                            $username = $this->session->userdata('username');
                            $db_config_id = $this->session->userdata('db_config_id');
                            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                            foreach ($user as $dat) {
                                $id_user = $dat->id;
                            }

                            if ($data['pago']['forma_pago'] != '') {
                                $array_datos = array(
                                    "Id_cierre" => $this->session->userdata('caja'),
                                    "hora_movimiento" => date('H:i:s'),
                                    "id_usuario" => $id_user,
                                    "tipo_movimiento" => 'entrada_venta',
                                    "valor" => ($data['pago']['valor_entregado'] - $data['pago']['cambio']),
                                    "forma_pago" => $data['pago']['forma_pago'],
                                    "numero" => $num_factura,
                                    "id_mov_tip" => $id,
                                    "tabla_mov" => "venta",
                                );
                                $this->connection->insert('movimientos_cierre_caja', $array_datos);
                            }

                            if ($data['pago_1']['valor_entregado'] != '0') {
                                if ($data['pago']['forma_pago'] != 'Credito') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => (($data['pago_1']['valor_entregado'])),
                                        "forma_pago" => $data['pago_1']['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            if ($data['pago_2']['valor_entregado'] != '0') {
                                if ($data['pago']['forma_pago'] != 'Credito') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => ($data['pago_2']['valor_entregado']),
                                        "forma_pago" => $data['pago_2']['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            if ($data['pago_3']['valor_entregado'] != '0') {
                                if ($data['pago']['forma_pago'] != 'Credito') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => ($data['pago_3']['valor_entregado']),
                                        "forma_pago" => $data['pago_3']['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            if ($data['pago_4']['valor_entregado'] != '0') {
                                if ($data['pago']['forma_pago'] != 'Credito') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => ($data['pago_4']['valor_entregado']),
                                        "forma_pago" => $data['pago_4']['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }

                            if ($data['pago_5']['valor_entregado'] != '0') {
                                if ($data['pago']['forma_pago'] != 'Credito') {
                                    $array_datos = array(
                                        "Id_cierre" => $this->session->userdata('caja'),
                                        "hora_movimiento" => date('H:i:s'),
                                        "id_usuario" => $id_user,
                                        "tipo_movimiento" => 'entrada_venta',
                                        "valor" => ($data['pago_5']['valor_entregado']),
                                        "forma_pago" => $data['pago_5']['forma_pago'],
                                        "numero" => $num_factura,
                                        "id_mov_tip" => $id,
                                        "tabla_mov" => "venta",
                                    );
                                    $this->connection->insert('movimientos_cierre_caja', $array_datos);
                                }
                            }
                        }
                    }

                    if ($data['sobrecostos'] != '' and $sobrecostosino == 'si') {
                        if ($nit == '320001127839') {
                            $this->connection->insert('detalle_venta', array(
                                'venta_id' => $id,
                                'nombre_producto' => 'PROPINA',
                                'unidades' => '1',
                                'descripcion_producto' => $data['sobrecostos'],
                                'precio_venta' => (($data['subtotal_input'] * $data['sobrecostos']) / 100),
                            ));
                        } else {
                            $valor_propina = ($data['subtotal_propina_input'] * $data['sobrecostos']) / 100;
                            $this->connection->insert('detalle_venta', array(
                                'venta_id' => $id,
                                'nombre_producto' => 'PROPINA',
                                'unidades' => '1',
                                'descripcion_producto' => $data['sobrecostos'],
                                'precio_venta' => $valor_propina - (($data['descuento_general'] * $valor_propina) / 100),
                            ));
                        }
                    }

                    if ($data["id_fact_espera"] != '') {
                        $this->connection->query("delete from  factura_espera where id = '" . $data["id_fact_espera"] . "' ");
                    }
                }
            }

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
            }

            // return $id;
        } catch (Exception $e) {

            // $this->connection->trans_rollback();

            print_r($e);
            die;
        }

        /*DECREMENTAR SOTCK*/
    }

    public function addColumnTransaccion()
    {
        // Esta funcion agrega el campo transaccion en donde se guardara la informacion de pagos realizados con datafono (Tarjeta)
        $sql = "SHOW COLUMNS FROM ventas_pago LIKE 'transaccion'";
        $existeCampo = $this->connection->query($sql)->result();

        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `ventas_pago`
                ADD COLUMN `transaccion` VARCHAR(25) NULL DEFAULT NULL AFTER `cambio`;
            ";
            $this->connection->query($sql);
        }
    }

    public function addColumnOrden()
    {
        $sql = "SHOW COLUMNS FROM venta LIKE 'consecutivo_orden'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `venta`
                ADD COLUMN `consecutivo_orden` INT(11) NULL DEFAULT NULL;
            ";
            $this->connection->query($sql);
        }
    }

    public function addColumn_porcentaje_descuento()
    {

        // Esta funcion agregar el porcentaje de descuento global que se le aplica a un venta

        $sql = "SHOW COLUMNS FROM venta LIKE 'porcentaje_descuento_general'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `venta`
                ADD COLUMN `porcentaje_descuento_general` DOUBLE NULL AFTER `promocion`;
            ";
            $this->connection->query($sql);
        }

        $sql = "SHOW COLUMNS FROM detalle_venta LIKE 'porcentaje_descuento'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `detalle_venta`
                ADD COLUMN `porcentaje_descuento` DOUBLE NULL AFTER `producto_id`;
            ";
            $this->connection->query($sql);
        }
    }

    public function agregar_columna_timbrado_venta()
    {

        // Esta funcion agregar el porcentaje de descuento global que se le aplica a un venta

        $sql = "SHOW COLUMNS FROM venta LIKE 'factura_timbrada'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `venta`
                ADD COLUMN `factura_timbrada` INT NULL  COMMENT 'para saber si se ha timbrado o no una factura'  AFTER `promocion`;
            ";
            $this->connection->query($sql);
        }

        $sql = "SHOW COLUMNS FROM venta LIKE 'ruta_xml_timbrado'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `venta`
                ADD COLUMN `ruta_xml_timbrado` LONGTEXT NULL  COMMENT 'ruta donde esta el archivo xml resultado de timbrar' AFTER `promocion`;
            ";
            $this->connection->query($sql);
        }
    }

    public function actualizar_venta_con_parametros($where, $data)
    {
        $this->connection->where($where);
        $this->connection->update('venta', $data);
    }

    public function get_datos_una_venta_where($where)
    {
        $this->connection->where($where);
        $query = $this->connection->get('venta');
        return $query->row();
    }

    public function agregar_columna_tipo_propina()
    {

        // Esta funcion agregar el porcentaje de descuento global que se le aplica a un venta

        $sql = "SHOW COLUMNS FROM detalle_venta LIKE 'tipo_propina'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) { // Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE detalle_venta
                ADD COLUMN tipo_propina varchar(10) NULL  COMMENT 'tipo de propina (valor/procentaje)';";
            $this->connection->query($sql);
        }
    }
    public function get_facturas_por_pago_pendientes($where)
    {
        if (!empty($where)) {
            $this->connection->where($where);
        }
        $this->connection->select('vvp.id_venta,vvp.id_almacen,vvp.id_user,v.fecha, v.factura');
        $this->connection->from('ventas_forma_pago_pendiente vvp');
        $this->connection->join('venta v', 'vvp.id_venta=v.id', 'inner');
        $query = $this->connection->get()->result_array();
        //echo $this->connection->last_query();
        return $query;
    }
    public function get_detalle_factura_pago_pendientes($where)
    {
        if (!empty($where)) {
            $this->connection->where($where);
        }

        $this->connection->select('*');
        $this->connection->from('venta v');
        $this->connection->join('ventas_pago p', 'v.id=p.id_venta', 'inner');
        $query = $this->connection->get()->result_array();
        return $query;
    }

    public function eliminarformasdepago($where)
    {
        $this->connection->where($where);
        $this->connection->delete("ventas_pago");
    }

    public function eliregistropendiente($where)
    {
        $this->connection->where($where);
        $this->connection->delete("ventas_forma_pago_pendiente");
    }

    public function crear_ventas_forma_pago_pendiente()
    {
        $db = $this->session->userdata('base_dato');

        $sql = "SHOW TABLES WHERE Tables_in_$db = 'ventas_forma_pago_pendiente'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `ventas_forma_pago_pendiente` (
                    `id_venta` int(11) NOT NULL,
                    `id_almacen` int(11) NOT NULL,
                    `id_user` int(11) NOT NULL,
                    PRIMARY KEY (`id_venta`,`id_almacen`,`id_user`)
                )";
            $this->connection->query($sql);
        }
    }

    public function registrarformasdepago($datos)
    {
        $this->connection->insert("ventas_pago", $datos);
    }

    public function actualizarnotacredito($codigo, $id, $id_cliente)
    {
        $notaCredito = $this->connection->get_where("notacredito", array(
            "consecutivo" => $codigo,
        ))->row();
        $this->connection->where("notaForeign_id", $notaCredito->id)->update("notacredito", array(
            "factura_id" => $id,
            "cliente_id" => $id_cliente,
            "estado" => 0,
        ));
    }

    public function eliminarregistrocierrecaja($where)
    {
        $this->connection->where($where);
        $this->connection->delete("movimientos_cierre_caja");
    }

    public function getcierrecajafactura($where)
    {
        //busco el cierre asociado
        $this->connection->where($where);
        $this->connection->from('movimientos_cierre_caja');
        $query = $this->connection->get()->result_array();
        return $query;
    }

    public function insertarmovimientocierrecaja($datos)
    {
        $this->connection->insert('movimientos_cierre_caja', $datos);
    }

    public function verificarImpresionRapida()
    {
        valide_option("impresion_rapida", "no");
        $db = $this->session->userdata('base_dato');

        $sql = "SHOW TABLES WHERE Tables_in_$db = 'impresion_rapida'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `impresion_rapida` (
                    `id` int (11) NOT NULL AUTO_INCREMENT,
                    `descripcion_venta` text ,
                    `id_venta` int (11),
                    `almacen` int (10),
                    `caja` int (10),
                    `estado` tinyint (1),
                    PRIMARY KEY(`id`)
                )";
            $this->connection->query($sql);
        }
    }

    public function reiniciar()
    {
        $this->connection->truncate('impresion_rapida');

        $this->connection->select("*");
        $result = $this->connection->get("impresion_rapida");
        if ($result->num_rows() > 0):
            return 0;else:
            return 1;
        endif;
    }
}

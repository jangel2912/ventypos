<?php

class Ventas_online_model extends CI_Model
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
        $aColumns = array('id', 'nombre', 'dni', 'email', 'fecha', 'sub_total', 'estado', 'metodo_pago', 'movil', 'tasa_impuesto', 'venta_id');
        $sIndexColumn = "id";
        $sTable = "online_venta";
        $sLimit = "";
        $sWhere = " where 1 = 1 ";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
            intval($_GET['iDisplayLength']);
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

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM   $sTable $sWhere $sOrder $sLimit";

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";

        $rResultFilterTotal = $this->connection->query($sQuery);
        //$aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM $sTable";

        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );
        // $aColumns[3] = 'saldo';

        /*   $aColumns[7] = 'id_venta';

        $aColumns[6] = 'tipo_factura'; */
        foreach ($rResult->result_array() as $row) {
            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($i == 5) {
                    $row[$aColumns[$i]] = $row[$aColumns[$i]] + $row[$aColumns[9]];
                }

                if ($i == 6) {
                    if ($row[$aColumns[$i]] == 2) {
                        $row[$aColumns[$i]] = 'Rechazada';
                    }
                    if ($row[$aColumns[$i]] == 3) {
                        $row[$aColumns[$i]] = 'Pendiente pago';
                    }
                    if ($row[$aColumns[$i]] == 1) {

                        $row[$aColumns[$i]] = 'Aprobada';
                    }
                    if ($row[$aColumns[$i]] == 0) {

                        $row[$aColumns[$i]] = 'Atendida';
                    }

                    if ($row[$aColumns[$i]] == 11) {
                        $row[$aColumns[$i]] = 'Atendida';
                    }
                    if ($row[$aColumns[$i]] == 13) {
                        $row[$aColumns[$i]] = 'Facturada';
                    }
                    if ($row[$aColumns[$i]] == 12) {
                        $row[$aColumns[$i]] = 'Anulada';
                    }
                }

                $data[] = $row[$aColumns[$i]];
            }
            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_ajax_data_orden()
    {
        $queryAlmacen = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $this->session->userdata('user_id');
        $almacen = $this->connection->query($queryAlmacen)->row();
        $aColumns = array('online_venta.id', 'fecha', 'nombre', 'movil', 'sub_total', 'origen', 'estado', 'metodo_pago', 'dni', 'email', 'tasa_impuesto', 'venta_id', 'online_venta_schedule.sale_date', 'online_venta_schedule.sale_time');
        $sIndexColumn = "online_venta.id";
        $sTable = "online_venta LEFT JOIN online_venta_schedule ON online_venta.id = online_venta_schedule.online_venta_id";
        $sLimit = "";
        $sWhere = " where 1 = 1 and almacen_id = '" . $almacen->id . "'";
        $sOrder = "";

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
            intval($_GET['iDisplayLength']);
        }

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

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM   $sTable $sWhere $sOrder $sLimit";

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";

        $rResultFilterTotal = $this->connection->query($sQuery);
        //$aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        //$sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM $sTable";
        $sQuery = "SELECT COUNT(*) as cantidad FROM $sTable";

        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );
        $aColumns[0] = 'id';
        $aColumns[12] = 'sale_date';
        $aColumns[13] = 'sale_time';
        /*   $aColumns[7] = 'id_venta';

        $aColumns[6] = 'tipo_factura'; */
        foreach ($rResult->result_array() as $row) {
            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($i == 6) {
                    if ($row[$aColumns[$i]] == 2) {
                        $row[$aColumns[$i]] = 'Rechazada';
                    }
                    if ($row[$aColumns[$i]] == 3) {
                        $row[$aColumns[$i]] = 'Pendiente pago';
                    }
                    if ($row[$aColumns[$i]] == 1) {

                        $row[$aColumns[$i]] = 'Aprobada';
                    }
                    if ($row[$aColumns[$i]] == 0) {

                        $row[$aColumns[$i]] = 'Atendida';
                    }

                    if ($row[$aColumns[$i]] == 11) {
                        $row[$aColumns[$i]] = 'Atendida';
                    }
                    if ($row[$aColumns[$i]] == 13) {
                        $row[$aColumns[$i]] = 'Facturada';
                    }
                    if ($row[$aColumns[$i]] == 12) {
                        $row[$aColumns[$i]] = 'Anulada';
                    }
                } elseif ($i == 12) {
                    if (!empty($row[$aColumns[$i]])) {
                        $fecha = DateTime::createFromFormat('Y-m-d', $row[$aColumns[$i]]);
                        $row[$aColumns[$i]] = $fecha->format("d/m/Y");
                    }

                } elseif ($i == 13) {
                    if (!empty($row[$aColumns[$i]])) {
                        $time = DateTime::createFromFormat('H:i:s', $row[$aColumns[$i]]);
                        $row[$aColumns[$i]] = $time->format("g:i A");
                    }
                }

                $data[] = $row[$aColumns[$i]];
            }
            $output['aaData'][] = $data;
        }
        return $output;
    }

    public function get_ajax_data_anulada()
    {
        $aColumns = array('id', 'nombre', 'dni', 'email', 'fecha', 'sub_total', 'estado', 'movil', 'tasa_impuesto');
        $sIndexColumn = "id";
        $sTable = "online_venta";
        $sLimit = "";
        $sWhere = " where estado = 12 ";

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

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
                FROM   $sTable $sWhere $sOrder $sLimit";

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";

        $rResultFilterTotal = $this->connection->query($sQuery);
        //$aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM $sTable";

        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );
        // $aColumns[3] = 'saldo';

        /*   $aColumns[7] = 'id_venta';

        $aColumns[6] = 'tipo_factura'; */
        foreach ($rResult->result_array() as $row) {
            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($i == 5) {
                    $row[$aColumns[$i]] = $row[$aColumns[$i]] + $row[$aColumns[8]];
                }

                if ($i == 6) {
                    if ($row[$aColumns[$i]] == 2) {
                        $row[$aColumns[$i]] = 'Anulada';
                    }

                }

                $data[] = $row[$aColumns[$i]];
            }
            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_by_id($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM online_venta WHERE online_venta.id = '" . $id . "'");

        return $query->row_array();
    }

    public function delete($id)
    {
        $this->connection->where('id', $id);
        $this->connection->update("online_venta", ['estado' => 12]);
    }

    public function update($field, $value, $id)
    {
        $this->connection->where('id', $id);
        $this->connection->update('online_venta', array($field => $value));
    }

    public function actualizarVenta()
    {
        $sql = "SHOW COLUMNS FROM online_venta LIKE 'venta_id'";

        if (count($this->connection->query($sql)->result()) == 0) {
            $sql = "ALTER TABLE `online_venta` ADD COLUMN `venta_id` int(11) NULL AFTER `estado`";
            $this->connection->query($sql);
        }
    }

    public function existeTabla($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'online_venta'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `online_venta` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(100) NOT NULL,
                `nombre2` varchar(100) DEFAULT NULL,
                `apellidos` varchar(100) DEFAULT NULL,
                `dni` varchar(15) NOT NULL,
                `telefono` varchar(30) DEFAULT NULL,
                `movil` varchar(30) DEFAULT NULL,
                `fax` varchar(100) DEFAULT NULL,
                `email` varchar(100) NOT NULL,
                `cpostal` varchar(10) DEFAULT NULL,
                `direccion` text,
                `notas` text,
                `fecha` date NOT NULL,
                `sub_total` int(20) NOT NULL,
                `tasa_impuesto` int(20) DEFAULT NULL,
                `estado` int(11) NOT NULL,
                `id_transac` varchar(100) DEFAULT NULL,
                `metodo_pago` varchar(10) DEFAULT NULL,
                `venta_id` int(11) DEFAULT NULL,
                `cobro_envio` int(11) DEFAULT NULL,
                `nombre_envio` varchar(100) DEFAULT NULL,
                `nombre2_envio` varchar(100) DEFAULT NULL,
                `correo_envio` varchar(100) DEFAULT NULL,
                `telefono_envio` varchar(15) DEFAULT NULL,
                `direccion_envio` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`id`)
              )";
            $this->connection->query($sql);
        }
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'online_venta_prod'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `online_venta_prod` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_venta` int(11) NOT NULL,
                `id_producto` int(11) NOT NULL,
                `descripcion` text NOT NULL,
                `precio` double DEFAULT NULL,
                `cantidad` int(20) DEFAULT NULL,
                `total` double DEFAULT NULL,
                `precio_sin_impuesto` double DEFAULT NULL,
                `impuesto` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ";
            $this->connection->query($sql);
        }

        $sql = "SHOW TABLES WHERE Tables_in_$db = 'online_venta_schedule'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `online_venta_schedule` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `online_venta_id` int(11) NOT NULL,
                `sale_time` time NOT NULL DEFAULT '00:00:00',
                `sale_date` date NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ";
            $this->connection->query($sql);

            $sql = "ALTER TABLE `online_venta_schedule`
            ADD CONSTRAINT `online_venta_schedule_online_venta_id_foreign` FOREIGN KEY (`online_venta_id`) REFERENCES `online_venta` (`id`) ON DELETE CASCADE;";
            $this->connection->query($sql);
        }

        $sql = "SHOW TABLES WHERE Tables_in_$db = 'restaurant_schedule'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) == 0) {
            $sql = "CREATE TABLE `restaurant_schedule` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `sunday` tinyint(1) NOT NULL DEFAULT '1',
                `monday` tinyint(1) NOT NULL DEFAULT '1',
                `tuesday` tinyint(1) NOT NULL DEFAULT '1',
                `wednesday` tinyint(1) NOT NULL DEFAULT '1',
                `thursday` tinyint(1) NOT NULL DEFAULT '1',
                `friday` tinyint(1) NOT NULL DEFAULT '1',
                `saturday` tinyint(1) NOT NULL DEFAULT '1',
                `open_time` time NOT NULL DEFAULT '00:00:00',
                `close_time` time NOT NULL DEFAULT '00:00:00',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )";

            $this->connection->query($sql);
        }

    }

    public function getDetalles($venta_id)
    {
        return $this->connection->get_where('online_venta_prod', array('id_venta' => $venta_id))->result_array();
    }

    public function facturar($data)
    {
        try {
            $this->connection->trans_begin();

            if (count($data['productos'] > 0)) {
                $array_cliente = array();
                $numero_factura = 0;
                $prefijo_factura = 0;
                $sobrecostosino = 0;
                $nit = 0;
                $multiformapago = 0;
                $valor_caja = 0;

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'numero_factura' ";
                $ocpresult = $this->connection->query($ocp)->row();
                $numero_factura = $ocpresult->valor_opcion;

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'prefijo_factura' ";
                $ocpresult = $this->connection->query($ocp)->row();
                $prefijo_factura = $ocpresult->valor_opcion;

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

                $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $valor_caja = $dat->valor_opcion;
                }

                $ocp = "SELECT id_cliente FROM `clientes`  where email = '" . $data['venta']['email'] . "'";
                $ocpresult = $this->connection->query($ocp)->result();
                foreach ($ocpresult as $dat) {
                    $id_cliente = $dat->id_cliente;
                }

                $no_factura = "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id =" . $this->session->userdata('user_id');
                $no_factura_row = $this->connection->query($no_factura)->row();

                $factura_int = $no_factura_row->consecutivo;
                $factura_int++;
                $fact = 0;
                $fac = "SELECT fecha FROM `venta` order by id desc limit 1 ";
                $facresult = $this->connection->query($fac)->result();
                foreach ($facresult as $dat) {
                    $fact = $dat->fecha;
                }

                if ($data['venta']['fecha'] != $fact) {
                    if ($opcnum == 'no') {
                        $num_factura = 0;
                        $this->connection->where('id', $no_factura_row->id)->update('almacen', array('consecutivo' => $factura_int));
                        $num_factura = $no_factura_row->prefijo . $factura_int;
                    }
                    if ($opcnum == 'si') {
                        $num_factura = 0;
                        $numero_factura++;
                        $this->connection->where('id', '26')->update('opciones', array('valor_opcion' => $numero_factura));
                        $num_factura = $prefijo_factura . $numero_factura;
                    }

                    $array_datos = array(
                        "fecha" => $data['venta']['fecha'],
                        "fecha_vencimiento" => date("Y-m-d H:i:s"),
                        "usuario_id" => $this->session->userdata('user_id'),
                        "factura" => $num_factura,
                        "almacen_id" => $no_factura_row->id,
                        "total_venta" => (int) $data['venta']['sub_total'] + (int) $data['venta']['tasa_impuesto'],
                        "cliente_id" => $id_cliente,
                        "tipo_factura" => "estandar",
                        "promocion" => "",
                        "nota" => "venta online",
                    );

                    $this->connection->insert("venta", $array_datos);
                    $id = $this->connection->insert_id();

                    $data_detalles = array();
                    $query_stock = "";
                    $descuento_prod = "0";

                    foreach ($data['productos'] as $value) {
                        $unidades_compra = $value['cantidad'];
                        $product_id = $value['id_producto'];
                        $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
                        $comp = 0;
                        $codigo = 0;
                        $impuesto = 0;
                        foreach ($prod as $dat) {
                            $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                            $codigo = $dat->codigo;
                            $impuesto = $dat->impuesto;
                        }

                        $descuento_prod = 0;

                        $compra_final_1 = $value['precio'] - $comp;
                        $compra_final_2 = $compra_final_1 * $value['cantidad'];
                        $compra_final_3 = 0;

                        if (empty($value['descripcion'])) {
                            $value['descripcion'] = '';
                        }

                        $data_detalles[] = array(
                            'venta_id' => $id,
                            'codigo_producto' => $codigo,
                            'precio_venta' => $value['precio_sin_impuesto'],
                            'unidades' => $value['cantidad'],
                            'nombre_producto' => $value['descripcion'],
                            'descripcion_producto' => 0, //se guarda si es un producto que vien por promocion
                            'impuesto' => $value['impuesto'],
                            'descuento' => $descuento_prod,
                            'producto_id' => $product_id,
                            'margen_utilidad' => ($compra_final_2 - $compra_final_3),
                        );

                        //$descuento_prod +=  $descuento_prod;
                        if ($product_id > 0) {
                            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $product_id;
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual - $value['cantidad'];
                            $query_producto = "select * from producto where id =" . $product_id;
                            $producto = $this->connection->query($query_producto)->row();
                            //Modificacion Stock Actual
                            if ($producto->ingredientes != 1 && $producto->combo != 1) {
                                $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $product_id)->update('stock_actual', array('unidades' => $unidades));
                            }
                            $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $value['id_producto'], 'almacen_id' => $no_factura_row->id, 'fecha' => date('Y-m-d'), 'unidad' => '-' . $value['cantidad'], 'precio' => $value['precio'], 'cod_documento' => $num_factura, 'usuario' => $this->session->userdata('user_id'), 'razon' => 'S'));

                            if ($producto->ingredientes == 1) {
                                $continuar = false;
                                //Ingredientes del producto
                                $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['id_producto'];
                                $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                                foreach ($ingredientes_producto->result() as $key => $valueI) {

                                    //Stock del ingrediente
                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueI->id_ingrediente;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = isset($almacen->unidades) ? $almacen->unidades : 0;
                                    $unidades = $unidades_actual - ($valueI->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                                    //Actualizar stock
                                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueI->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                                    //Insertar stock diario
                                    $this->connection->insert(
                                        'stock_diario', array(
                                            'producto_id' => $valueI->id_ingrediente,
                                            'almacen_id' => $no_factura_row->id,
                                            'fecha' => date('Y-m-d'),
                                            'unidad' => '-' . ($valueI->cantidad * $unidades_compra),
                                            'precio' => 0,
                                            'cod_documento' => $num_factura,
                                            'usuario' => $this->session->userdata('user_id'),
                                            'razon' => 'S',
                                        )
                                    );
                                }
                            }

                            if ($producto->combo == 1) {
                                $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $product_id;
                                $productos_combo = $this->connection->query($query_productos_combo);

                                foreach ($productos_combo->result() as $key => $valueC) {
                                    //Stock del ingrediente
                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueC->id_producto;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = isset($almacen->unidades) ? $almacen->unidades : 0;
                                    $unidades = $unidades_actual - ($valueC->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                                    //Actualizar stock
                                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueC->id_producto)->update('stock_actual', array('unidades' => $unidades));
                                    //Insertar stock diario
                                    $this->connection->insert(
                                        'stock_diario', array(
                                            'producto_id' => $valueC->id_producto,
                                            'almacen_id' => $no_factura_row->id,
                                            'fecha' => date('Y-m-d'),
                                            'unidad' => '-' . ($valueC->cantidad * $unidades_compra),
                                            'precio' => 0,
                                            'cod_documento' => $num_factura,
                                            'usuario' => $this->session->userdata('user_id'),
                                            'razon' => 'S',
                                        )
                                    );
                                }
                            }
                        }
                    }

                    $this->connection->insert_batch("detalle_venta", $data_detalles);

                    $detalle_venta_adicionales = $this->UpdateStockAdicionales($data['adicionales'], $id, $no_factura_row, $num_factura);

                    if (!empty($detalle_venta_adicionales)) {
                        $this->connection->insert_batch("detalle_venta", $detalle_venta_adicionales);
                    }

                    ///pago
                    $this->connection->insert('ventas_pago', array(
                        "id_venta" => $id,
                        "forma_pago" => $data['pago'],
                        "valor_entregado" => (int) $data['venta']['sub_total'] + (int) $data['venta']['tasa_impuesto'],
                        "cambio" => 0,
                    ));

                    //$this->connection->insert("pago", $array_datos);
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
                            "valor" => (int) $data['venta']['sub_total'] + (int) $data['venta']['tasa_impuesto'],
                            "forma_pago" => $data['pago'],
                            "numero" => $num_factura,
                            "id_mov_tip" => $id,
                            "tabla_mov" => "venta",
                        );
                        $this->connection->insert('movimientos_cierre_caja', $array_datos);
                    }
                }

                $this->connection->where('id', $data['venta']['id'])->update('online_venta', array('venta_id' => $data['venta']['id'], 'estado' => 13));
            }

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
            }

            return $num_factura;
        } catch (Exception $e) {

            // $this->connection->trans_rollback();

            print_r($e);
            die;
        }

        /* DECREMENTAR SOTCK */
    }

    public function UpdateStockAdicionales($data, $venta_id, $no_factura_row, $num_factura)
    {
        $data_detalles = [];

        foreach ($data as $value) {
            $unidades_compra = $value['cantidad'];
            $product_id = $value['id_producto'];
            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
            $comp = 0;
            $codigo = 0;
            $impuesto = 0;
            foreach ($prod as $dat) {
                $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                $codigo = $dat->codigo;
                $impuesto = $dat->impuesto;
            }

            $descuento_prod = 0;

            $compra_final_1 = $value['precio'] - $comp;
            $compra_final_2 = $compra_final_1 * $value['cantidad'];
            $compra_final_3 = 0;

            $data_detalles[] = array(
                'venta_id' => $venta_id,
                'codigo_producto' => $codigo,
                'precio_venta' => $value['precio'],
                'unidades' => $value['cantidad'],
                'nombre_producto' => $value['descripcion'],
                'descripcion_producto' => 0, //se guarda si es un producto que vien por promocion
                'impuesto' => 0,
                'descuento' => $descuento_prod,
                'producto_id' => $product_id,
                'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            );

            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $product_id;
            $almacen = $this->connection->query($query_stock)->row();
            $unidades_actual = isset($almacen->unidades) ? $almacen->unidades : 0;
            $unidades = $unidades_actual - $value['cantidad'];
            $query_producto = "select * from producto where id =" . $product_id;
            $producto = $this->connection->query($query_producto)->row();
            //Modificacion Stock Actual
            if ($producto->ingredientes != 1 && $producto->combo != 1) {
                $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $product_id)->update('stock_actual', array('unidades' => $unidades));
            }
            $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $value['id_producto'], 'almacen_id' => $no_factura_row->id, 'fecha' => date('Y-m-d'), 'unidad' => '-' . $value['cantidad'], 'precio' => $value['precio'], 'cod_documento' => $num_factura, 'usuario' => $this->session->userdata('user_id'), 'razon' => 'S'));

            if ($producto->ingredientes == 1) {
                $continuar = false;
                //Ingredientes del producto
                $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['id_producto'];
                $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                foreach ($ingredientes_producto->result() as $key => $valueI) {

                    //Stock del ingrediente
                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueI->id_ingrediente;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    $unidades = $unidades_actual - ($valueI->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                    //Actualizar stock
                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueI->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                    //Insertar stock diario
                    $this->connection->insert(
                        'stock_diario', array(
                            'producto_id' => $valueI->id_ingrediente,
                            'almacen_id' => $no_factura_row->id,
                            'fecha' => date('Y-m-d'),
                            'unidad' => '-' . ($valueI->cantidad * $unidades_compra),
                            'precio' => 0,
                            'cod_documento' => $num_factura,
                            'usuario' => $this->session->userdata('user_id'),
                            'razon' => 'S',
                        )
                    );
                }
            }

            if ($producto->combo == 1) {
                $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $product_id;
                $productos_combo = $this->connection->query($query_productos_combo);

                foreach ($productos_combo->result() as $key => $valueC) {
                    //Stock del ingrediente
                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueC->id_producto;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    $unidades = $unidades_actual - ($valueC->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                    //Actualizar stock
                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueC->id_producto)->update('stock_actual', array('unidades' => $unidades));
                    //Insertar stock diario
                    $this->connection->insert(
                        'stock_diario', array(
                            'producto_id' => $valueC->id_producto,
                            'almacen_id' => $no_factura_row->id,
                            'fecha' => date('Y-m-d'),
                            'unidad' => '-' . ($valueC->cantidad * $unidades_compra),
                            'precio' => 0,
                            'cod_documento' => $num_factura,
                            'usuario' => $this->session->userdata('user_id'),
                            'razon' => 'S',
                        )
                    );
                }
            }
        }

        return $data_detalles;
    }

    public function cambiarAlmacen($id, $almacen_id)
    {
        return $this->connection->where('id', $id)->update('online_venta', array('almacen_id' => $almacen_id));
    }

    public function comandarOrden($id)
    {
        try {
            $this->connection->trans_begin();
            $online_venta_productos = $this->getDetalles($id);

            if (count($online_venta_productos > 0)) {
                $online_venta = $this->get_by_id($id);

                if (!is_null($online_venta)) {
                    $get_tipo_negocio = $this->connection->query("select valor_opcion from opciones where nombre_opcion='tipo_negocio'")->row();
                    $get_comanda_virtual = $this->connection->query("select valor_opcion from opciones where nombre_opcion='comanda_virtual'")->row();
                    $get_comanda = $this->connection->query("select valor_opcion from opciones where nombre_opcion='comanda'")->row();

                    //Seleccionar si la orden tiene entrega para adicionarla a la comanda.
                    $get_fecha_entrega = $this->connection->query("SELECT * FROM `online_venta_schedule` where online_venta_id = $id")->row();
                    $programmedEntrega = " Entrega inmediata. ";
                    if (!empty($get_fecha_entrega)) {
                        $date = DateTime::createFromFormat('Y-m-d', $get_fecha_entrega->sale_date);
                        $time = DateTime::createFromFormat('H:i:s', $get_fecha_entrega->sale_time);
                        $programmedEntrega = " Entrega programada para: " . $date->format("d/m/Y") . " " . $time->format("g:i A");
                    }

                    $notas_adicionales = "";
                    if (isset($online_venta['notas_adicionales']) && !empty($online_venta['notas_adicionales'])) {
                        $notas_adicionales = " Notas adicionales: " . $online_venta['notas_adicionales'];
                    }

                    if ($get_comanda_virtual->valor_opcion == 'si') {

                        $array_comanda_virtual = array(
                            'order' => 1,
                            'zone' => -1,
                            'note' => $get_tipo_negocio->valor_opcion == "restaurante" ? $programmedEntrega . " " . $notas_adicionales : 'Tienda Orden No. ' . $id . ". " . $programmedEntrega . " " . $notas_adicionales,
                            'sale_id' => $id,
                            'table_id' => -2,
                            'user_id' => $this->session->userdata('user_id'),
                            'warehouse_id' => $online_venta['almacen_id'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );

                        $this->connection->insert('comanda_virtual', $array_comanda_virtual);
                        $comanda_virtual_id = $this->connection->insert_id();

                        foreach ($online_venta_productos as $producto) {
                            $producto_id = $producto['id_producto'];

                            if ($producto_id > 0) {
                                $array_comanda_virtual_productos = array(
                                    'virtual_command_id' => $comanda_virtual_id,
                                    'product_id' => $producto_id,
                                    'quantity' => $producto['cantidad'],
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                );

                                $this->connection->insert('comanda_virtual_productos', $array_comanda_virtual_productos);
                                $comanda_virtual_product_id = $this->connection->insert_id();

                                $adicionales = $this->connection->query("SELECT online_venta_prod_adition.qty, producto_adicional.id_adicional FROM online_venta_prod_adition inner join producto_adicional on producto_adicional.id = online_venta_prod_adition.producto_adicional_id where online_venta_prod_id=" . $producto['id'])->result_array();
                                foreach ($adicionales as $adicional) {
                                    $adicional_array = [
                                        'virtual_command_product_id' => $comanda_virtual_product_id,
                                        'addition_id' => $adicional['id_adicional'],
                                        'quantity' => $adicional['qty'],
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];
                                    $this->connection->insert('comanda_virtual_productos_adiciones', $adicional_array);
                                }

                                $modificaciones = $this->connection->query("SELECT * FROM `online_venta_prod_modification` where online_venta_prod_id=" . $producto['id'])->result_array();
                                foreach ($modificaciones as $modificacion) {
                                    $modificacion_array = [
                                        'virtual_command_product_id' => $comanda_virtual_product_id,
                                        'modification_id' => $modificacion['producto_modificacion_id'],
                                        'quantity' => 1,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];
                                    $this->connection->insert('comanda_virtual_productos_modificaciones', $modificacion_array);
                                }
                            }
                        }

                        //$this->connection->insert_batch('comanda_virtual_productos', $array_comanda_virtual_productos);
                    }

                    if ($get_comanda->valor_opcion == 'si') {
                        $comanda = [];
                        foreach ($online_venta_productos as $producto) {
                            $producto_id = $producto['id_producto'];
                            $temp_comanda = [
                                'order_producto' => $producto_id,
                                'zona' => '-1',
                                'mesa_id' => '-2',
                                'estado' => 2,
                                'created_at' => date('Y-m-d H:i:s'),
                                'update_at' => date('Y-m-d H:i:s'),
                                'cantidad' => $producto['cantidad'],
                                'almacen' => $online_venta['almacen_id'],
                                'order_adiciones' => '',
                                'order_modificacion' => '',
                                'nota' => $programmedEntrega . " " . $notas_adicionales,
                            ];

                            if ($producto_id > 0) {
                                $adicionales = $this->connection->query("SELECT producto_adicional.id_adicional, online_venta_prod_adition.qty FROM `online_venta_prod_adition` inner join producto_adicional on producto_adicional.id = online_venta_prod_adition.producto_adicional_id where online_venta_prod_id = " . $producto['id'])->result_array();
                                $adicionales_text = "";
                                foreach ($adicionales as $adicional) {
                                    if ($adicional['qty'] > 0) {
                                        for ($i = 0; $i < $adicional['qty']; $i++) {
                                            if (!empty($adicionales_text)) {
                                                $adicionales_text .= ", " . $adicional['id_adicional'];
                                            } else {
                                                $adicionales_text .= $adicional['id_adicional'];
                                            }
                                        }
                                    }
                                }

                                if (!empty($adicionales_text)) {
                                    $temp_comanda['order_adiciones'] = "[$adicionales_text]";
                                } else {
                                    $temp_comanda['order_adiciones'] = "[]";
                                }

                                $modificaciones = $this->connection->query("SELECT producto_modificacion.nombre FROM `online_venta_prod_modification` inner JOIN producto_modificacion on producto_modificacion.id = online_venta_prod_modification.producto_modificacion_id where online_venta_prod_id=" . $producto['id'])->result_array();
                                $modificaciones_text = "";
                                $aux_count = 0;
                                foreach ($modificaciones as $modificacion) {
                                    if ($aux_count++ > 0) {
                                        $modificaciones_text .= ',"' . $modificacion['nombre'] . '"';
                                    } else {
                                        $modificaciones_text .= '"' . $modificacion['nombre'] . '"';
                                    }
                                }

                                if (!empty($modificaciones_text)) {
                                    $temp_comanda['order_modificacion'] = "[$modificaciones_text]";
                                } else {
                                    $temp_comanda['order_modificacion'] = "[]";
                                }

                                $comanda[] = $temp_comanda;
                            }
                        }

                        if (!empty($comanda)) {
                            $this->connection->insert_batch('orden_producto_restaurant', $comanda);
                        }
                    }
                }
            }

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
                return true;
            }
        } catch (Exception $e) {
            print_r($e);
            die;
        }

        return false;
    }
}

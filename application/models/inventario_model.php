<?php

class Inventario_model extends CI_Model
{

    public $connection;

    // Constructor

    public function __construct()
    {

        parent::__construct();
        $this->load->helper(array('costo_promedio', 'costo_promedio'));
    }

    public function initialize($connection)
    {

        $this->connection = $connection;
    }

    public function get_ajax_data()
    {

        $aColumns = array('movimiento_inventario.id', 'nombre', 'tipo_movimiento', 'codigo_factura', 'nota', 'fecha', 'total_inventario');

        $sIndexColumn = "id";

        $sTable = "movimiento_inventario";

        $sLimit = "";

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

        $sWhere = " ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $sWhere .= "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);

            $sWhere .= ')';
        }

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
            $condicion = " ";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $condicion = " and almacen_id='$almacen' ";
        }

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . ", movimiento_inventario.id
		FROM   $sTable inner join almacen a on almacen_id = a.id
		$sWhere and (codigo_factura <> 'ANULADA' or codigo_factura IS NULL)  $condicion
		$sOrder
		$sLimit";
        //se modificó la consulta para que de los resultados correctos
        $rResult = $this->connection->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        //$aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM   $sTable where (codigo_factura <> 'ANULADA' OR codigo_factura IS NULL)
        ";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        // $aColumns[3] = 'saldo';
        if ($is_admin == 't') {
            $aColumns[0] = 'id';
            $aColumns[7] = 'id';
        }
        if ($is_admin != 't') {
            $aColumns[6] = 'id';
            $aColumns[0] = 'id';
        }
        //echo count($aColumns);
        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {
                if ($i == 6 && $is_admin == 't') {
                    //echo $this->opciones_model->formatoMonedaMostrar($row[$aColumns[$i]]);
                    $data[] = $this->opciones_model->formatoMonedaMostrar($row[$aColumns[$i]]);
                } else {
                    $data[] = $row[$aColumns[$i]];
                }

            }

            $output['aaData'][] = $data;
        }

        // print_r($data);
        //die();

        return $output;
    }

    public function get_by_id($id = 0)
    {

        $query = $this->connection->query("SELECT movimiento_inventario.*, a.nombre as almacen_origen, at.nombre as almacen_traslado, proveedores.nombre_comercial from movimiento_inventario

                        inner join almacen a on a.id = movimiento_inventario.almacen_id

                        left join almacen at on at.id = movimiento_inventario.almacen_traslado_id

                        left join proveedores on proveedores.id_proveedor = movimiento_inventario.proveedor_id

                        where movimiento_inventario.id = $id

                ");

        return $query->row_array();
    }

    public function get_detalles_movimiento($id = 0)
    {

        $query = $this->connection->query("SELECT * FROM movimiento_detalle WHERE id_inventario = '" . $id . "'");

        return $query->result_array();
    }

    public function eliminar_movimiento($id = 0, $id_almacen = 0)
    {
        $cantidad_nueva = 0;
        $query = $this->connection->query("SELECT * FROM movimiento_inventario AS mi
                                               INNER JOIN movimiento_detalle AS md ON md.id_inventario=mi.id
                                               WHERE md.id_inventario = '" . $id . "'")->result();

        foreach ($query as $value) {
            $id_principal = $value->id;
            $cantidad = $value->cantidad;
            $tipo = $value->tipo_movimiento;
            $producto_id_final = $value->producto_id;
            if (strlen($producto_id_final) > 0) {

                $productos = $this->connection->query("SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.id = '" . $producto_id_final . "' AND sa.almacen_id ='" . $id_almacen . "'")->result();
            } else if (strlen($value->nombre) > 0) {

                $productos = $this->connection->query("SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.nombre = '" . $value->nombre . "' AND p.codigo = '" . $value->codigo_barra . "' AND sa.almacen_id ='" . $id_almacen . "'")->result();
            }

            // validar los tipos de inventario y movimiento

            if ($tipo == "entrada_compra" || $tipo == "entrada_devolucion" || $tipo == 'entrada_ajustes' || $tipo == 'entrada_inicial' || $tipo == 'entrada_remision') {
                $this->connection->query("UPDATE movimiento_inventario SET codigo_factura = 'ANULADA' WHERE id='" . $id . "'");
                foreach ($productos as $dt) {
                    $id_producto = $dt->idprod;
                    $id_stock = $dt->idstock;
                    $unidades = $dt->unidades;
                    $cantidad_nueva = $unidades - $cantidad;

                    // echo "esto es lo que queda ".$cantidad_nueva;

                    $actualizar = $this->connection->query("UPDATE stock_actual SET unidades ='" . $cantidad_nueva . "' WHERE id='" . $id_stock . "' AND almacen_id='" . $id_almacen . "' AND producto_id='" . $id_producto . "'");

                    $razon = 'SM';
                    $signo = '-';
                    $this->connection->insert('stock_diario',
                        array('producto_id' => $dt->idprod,
                            'almacen_id' => $value->almacen_id,
                            'fecha' => date('Y-m-d'),
                            'unidad' => $signo . $value->cantidad,
                            'precio' => $value->precio_compra,
                            'cod_documento' => $value->codigo_factura,
                            'usuario' => $value->user_id,
                            'razon' => $razon)
                    );
                }
            }

            if ($tipo == 'traslado') {
                if (strlen($producto_id_final) > 0) {
                    //echo "select  1-" . $producto_id_final . "<br />";
                    $sql = "SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.id = '" . $producto_id_final . "'  AND sa.almacen_id ='" . $value->almacen_traslado_id . "'";
                    //echo $sql;
                    $productos1 = $this->connection->query($sql)->result();
                } else {
                    //echo "select  2-" . $value->codigo_barra . "<br />";
                    $productos1 = $this->connection->query("SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.nombre = '" . $value->nombre . "' AND p.codigo = '" . $value->codigo_barra . "' AND sa.almacen_id ='" . $value->almacen_traslado_id . "'")->result();
                }

                foreach ($productos1 as $dt) {
                    $id_producto = $dt->idprod;
                    $id_stock = $dt->idstock;
                    $unidades = $dt->unidades;
                    $cantidad_almacen_traslado = $unidades - $cantidad;
                    $actualizar2 = $this->connection->query("UPDATE stock_actual SET unidades ='" . $cantidad_almacen_traslado . "' WHERE almacen_id='" . $value->almacen_traslado_id . "' AND producto_id='" . $id_producto . "'");

                    // $eliminar = $this->connection->query("DELETE FROM movimiento_inventario WHERE id='".$id_principal."'");

                }

                if (strlen($producto_id_final) > 0) {
                    $productos2 = $this->connection->query("SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.id = '" . $producto_id_final . "'  AND sa.almacen_id ='" . $value->almacen_id . "'")->result();
                } else {
                    $productos2 = $this->connection->query("SELECT p.nombre, sa.unidades, p.id AS idprod, sa.id as idstock FROM producto AS p
                                                        INNER JOIN stock_actual AS sa ON sa.producto_id=p.id
                                                        WHERE p.nombre = '" . $value->nombre . "' AND p.codigo = '" . $value->codigo_barra . "' AND sa.almacen_id ='" . $value->almacen_id . "'")->result();
                }

                foreach ($productos2 as $dt) {
                    $id_producto = $dt->idprod;
                    $id_stock = $dt->idstock;
                    $unidades = $dt->unidades;
                    $cantidad_almacen_normal = $unidades + $cantidad;
                    $actualizar1 = $this->connection->query("UPDATE stock_actual SET unidades ='" . $cantidad_almacen_normal . "' WHERE almacen_id='" . $value->almacen_id . "' AND producto_id='" . $id_producto . "'");

                    // $eliminar = $this->connection->query("DELETE FROM movimiento_inventario WHERE id='".$id_principal."'");

                }
                $this->connection->query("UPDATE movimiento_inventario SET codigo_factura = 'ANULADA' WHERE id='" . $id_principal . "'");

            }

            if ($tipo == 'salida_devolucion' || $tipo == 'salida_rotura' || $tipo == 'salida_ajustes' || $tipo == 'salida_remision') {
                $signo = '-';
                foreach ($productos as $dt) {
                    $id_producto = $dt->idprod;
                    $id_stock = $dt->idstock;
                    $unidades = $dt->unidades;
                    $cantidad_nueva = $unidades + $cantidad;

                    // echo "esto es lo que queda ".$cantidad_nueva;

                    $sql = "DELETE FROM movimiento_inventario WHERE id='" . $id_principal . "'";
                    $actualizar = $this->connection->query("UPDATE stock_actual SET unidades ='" . $cantidad_nueva . "' WHERE id='" . $id_stock . "' AND almacen_id='" . $id_almacen . "' AND producto_id='" . $id_producto . "'");
                    $this->connection->query("UPDATE movimiento_inventario SET codigo_factura = 'ANULADA' WHERE id='" . $id_principal . "'");

                    $razon = 'E';
                    $this->connection->insert('stock_diario',
                        array('producto_id' => $dt->idprod,
                            'almacen_id' => $value->almacen_id,
                            'fecha' => date('Y-m-d'),
                            'unidad' => $signo . $value->cantidad,
                            'precio' => $value->precio_compra,
                            'cod_documento' => $value->codigo_factura,
                            'usuario' => $value->user_id,
                            'razon' => $razon)
                    );
                }
            }
        }
    }

    public function get_detalles_pago($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM ventas_pago WHERE id_venta = '" . $id . "'");

        return $query->row_array();
    }

    public function add_csv_inventario_1($data)
    {
        $this->connection->insert("movimiento_inventario", $data);
        $id = $this->connection->insert_id();

        return $id;
    }

    public function add_csv_inventario_2($data, $value, $id)
    {
        $producto = trim($value['codigo']);
        $id_producto = 0;
        $nombre_producto = 0;
        $codigo_producto = 0;
        $product = $this->connection->query("SELECT * FROM producto where no = '" . $producto . "' limit 1")->result();
        foreach ($product as $dat) {
            $id_producto = $dat->id;
            $nombre_producto = $dat->nombre;
            $codigo_producto = $dat->codigo;
        }

        $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $id_producto;
        $almacen = $this->connection->query($query_stock)->row();
        $unidades_actual = $almacen->unidades;
        $razon = 'E';
        $signo = '';

        if ($data['tipo_movimiento'] == 'entrada_compra' || $data['tipo_movimiento'] == 'entrada_devolucion' || $data['tipo_movimiento'] == 'entrada_ajustes' || $data['tipo_movimiento'] == 'entrada_inicial' || $data['tipo_movimiento'] == 'entrada_remision') {

            $unidades = $unidades_actual + $value['cantidad'];
        } elseif ($data['tipo_movimiento'] == 'salida_devolucion' || $data['tipo_movimiento'] == 'salida_rotura' || $data['tipo_movimiento'] == 'salida_ajustes' || $data['tipo_movimiento'] == 'salida_remision') {

            $unidades = $unidades_actual - $value['cantidad'];

            $razon = 'SM';

            $signo = '-';
        } elseif ($data['tipo_movimiento'] == 'traslado') {

            $unidades = $unidades_actual - $value['cantidad'];

            $razon = 'ST';

            $signo = '-';

            $query_stock_traslado = "select * from stock_actual where almacen_id =" . $data['almacen_traslado_id'] . " and producto_id=" . $id_producto;

            $almacen_traslado = $this->connection->query($query_stock_traslado)->row();

            $unidades_traslado_actual = $value['cantidad'] + $almacen_traslado->unidades;

            $this->connection->where('almacen_id', $data['almacen_traslado_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades_traslado_actual));

            $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_traslado_id'], 'fecha' => date('Y-m-d'), 'unidad' => $value['cantidad'], 'precio' => $value['precio_compra'], 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => 'E'));
        }

        $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades));

        $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'), 'unidad' => $signo . $value['cantidad'], 'precio' => $value['precio_compra'], 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => $razon));

        $id_producto;

        $value1['id_inventario'] = $id;
        $value1['codigo_barra'] = $codigo_producto;
        $value1['cantidad'] = $value['cantidad'];
        $value1['precio_compra'] = $value['precio_compra'];
        $value1['existencias'] = $unidades_actual;
        $value1['nombre'] = $nombre_producto;
        $value1['total_inventario'] = ($value['precio_compra'] * $value['cantidad']);

        $value1['producto_id'] = $id_producto;

        $data_detalles[] = $value1;

        $this->connection->insert_batch("movimiento_detalle", $data_detalles);
    }

    public function add_csv_inventario_nombre_2($data, $value, $id)
    {

        if (!function_exists('limpiar')) {

            function limpiar($String)
            {
                $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
                $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
                $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
                $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
                $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);

                $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
                $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
                $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
                $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
                $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
                $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
                $String = str_replace("ç", "c", $String);
                $String = str_replace("Ç", "C", $String);
                $String = str_replace("ñ", "n", $String);
                $String = str_replace("Ñ", "N", $String);
                $String = str_replace("Ý", "Y", $String);
                $String = str_replace("ý", "y", $String);

                $String = str_replace("&aacute;", "a", $String);
                $String = str_replace("&Aacute;", "A", $String);
                $String = str_replace("&eacute;", "e", $String);
                $String = str_replace("&Eacute;", "E", $String);
                $String = str_replace("&iacute;", "i", $String);
                $String = str_replace("&Iacute;", "I", $String);
                $String = str_replace("&oacute;", "o", $String);
                $String = str_replace("&Oacute;", "O", $String);
                $String = str_replace("&uacute;", "u", $String);
                $String = str_replace("&Uacute;", "U", $String);
                return $String;
            }

        }

        $producto = limpiar(utf8_encode($value['nombre']));
        $id_producto = 0;
        $nombre_producto = 0;
        $codigo_producto = 0;
        $precio_compra = 0;
        $product = "SELECT * FROM producto where nombre = '" . $producto . "' limit 1;";

        $product = $this->connection->query($product)->result();
        foreach ($product as $dat) {
            $id_producto = $dat->id;
            $nombre_producto = $dat->nombre;

            $codigo_producto = $dat->codigo;
            $precio_compra = $dat->precio_compra;
        }

        $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $id_producto;

        $almacen = $this->connection->query($query_stock)->row();
        $unidades_actual = 0;
        if (isset($almacen->unidades)) {
            $unidades_actual = $almacen->unidades;
        }

        $razon = 'E';

        $signo = '';

        if ($data['tipo_movimiento'] == 'entrada_compra' || $data['tipo_movimiento'] == 'entrada_devolucion' || $data['tipo_movimiento'] == 'entrada_ajustes' || $data['tipo_movimiento'] == 'entrada_inicial' || $data['tipo_movimiento'] == 'entrada_remision') {

            $unidades = $unidades_actual + $value['cantidad'];
        } elseif ($data['tipo_movimiento'] == 'salida_devolucion' || $data['tipo_movimiento'] == 'salida_rotura' || $data['tipo_movimiento'] == 'salida_ajustes' || $data['tipo_movimiento'] == 'salida_remision') {

            $unidades = $unidades_actual - $value['cantidad'];

            $razon = 'SM';

            $signo = '-';
        } elseif ($data['tipo_movimiento'] == 'traslado') {

            $unidades = $unidades_actual - $value['cantidad'];

            $razon = 'ST';

            $signo = '-';

            $query_stock_traslado = "select * from stock_actual where almacen_id =" . $data['almacen_traslado_id'] . " and producto_id=" . $id_producto;

            $almacen_traslado = $this->connection->query($query_stock_traslado)->row();

            $unidades_traslado_actual = $value['cantidad'] + $almacen_traslado->unidades;

            $this->connection->where('almacen_id', $data['almacen_traslado_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades_traslado_actual));

            $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_traslado_id'], 'fecha' => date('Y-m-d'), 'unidad' => $value['cantidad'], 'precio' => $precio_compra, 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => 'E'));
        }

        $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades));

        $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'), 'unidad' => $signo . $value['cantidad'], 'precio' => $precio_compra, 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => $razon));

        $id_producto;

        $value1['id_inventario'] = $id;
        $value1['codigo_barra'] = $codigo_producto;
        $value1['cantidad'] = $value['cantidad'];
        $value1['precio_compra'] = $precio_compra;
        $value1['existencias'] = $unidades_actual;
        $value1['nombre'] = $nombre_producto;
        $value1['total_inventario'] = ($precio_compra * $value['cantidad']);

        $value1['producto_id'] = $id_producto;

        $data_detalles[] = $value1;

        $this->connection->insert_batch("movimiento_detalle", $data_detalles);
    }

    public function add_csv_inventario_nombre_codigo($data, $value, $id)
    {
        $error_message = '';

        if (!function_exists('limpiar')) {

            function limpiar($String)
            {
                $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
                $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
                $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
                $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
                $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);

                $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
                $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
                $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
                $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
                $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
                $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
                $String = str_replace("ç", "c", $String);
                $String = str_replace("Ç", "C", $String);
                $String = str_replace("ñ", "n", $String);
                $String = str_replace("Ñ", "N", $String);
                $String = str_replace("Ý", "Y", $String);
                $String = str_replace("ý", "y", $String);

                $String = str_replace("&aacute;", "a", $String);
                $String = str_replace("&Aacute;", "A", $String);
                $String = str_replace("&eacute;", "e", $String);
                $String = str_replace("&Eacute;", "E", $String);
                $String = str_replace("&iacute;", "i", $String);
                $String = str_replace("&Iacute;", "I", $String);
                $String = str_replace("&oacute;", "o", $String);
                $String = str_replace("&Oacute;", "O", $String);
                $String = str_replace("&uacute;", "u", $String);
                $String = str_replace("&Uacute;", "U", $String);
                return $String;
            }

        }

        $codigo = trim(limpiar(utf8_decode($value['codigo'])));
        //$producto = limpiar(utf8_encode($value['nombre']));
        $id_producto = 0;
        $nombre_producto = 0;
        $codigo_producto = 0;
        $precio_compra = 0;
        $validacion_nombre = "";

        //if ($producto != "")
        //    $validacion_nombre = " AND nombre = '".$producto."'";

        //$product = "SELECT * FROM producto where codigo = '".$codigo."'".$validacion_nombre." limit 1;";
        $product = "SELECT * FROM producto where codigo = '" . $codigo . "' limit 1;";
        $product = $this->connection->query($product)->result();

        if (count($product)) {
            foreach ($product as $dat) {
                $id_producto = $dat->id;
                $nombre_producto = $dat->nombre;
                $codigo_producto = $dat->codigo;
                $precio_compra = $dat->precio_compra;
            }

            $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $id_producto;
            //echo"<br>".$query_stock;
            $almacen = $this->connection->query($query_stock)->row();

            if (empty($almacen)) {
                $unidades_actual = 0;

            } else {
                $unidades_actual = $almacen->unidades;
            }
            $razon = 'E';
            $signo = '';

            if ($data['tipo_movimiento'] == 'entrada_compra' || $data['tipo_movimiento'] == 'entrada_devolucion' || $data['tipo_movimiento'] == 'entrada_ajustes' || $data['tipo_movimiento'] == 'entrada_inicial' || $data['tipo_movimiento'] == 'entrada_remision') {

                $unidades = $unidades_actual + $value['cantidad'];

            } elseif ($data['tipo_movimiento'] == 'salida_devolucion' || $data['tipo_movimiento'] == 'salida_rotura' || $data['tipo_movimiento'] == 'salida_ajustes' || $data['tipo_movimiento'] == 'salida_remision') {

                $unidades = $unidades_actual - $value['cantidad'];
                $razon = 'SM';
                $signo = '-';
            } elseif ($data['tipo_movimiento'] == 'traslado') {
                if ($value['cantidad'] > 0) {
                    $unidades = $unidades_actual - $value['cantidad'];

                    if ($unidades >= 0) { // Validamos si el almacen cuenta con la cantidad a trasladar
                        $razon = 'ST';
                        $signo = '-';
                        $query_stock_traslado = "select * from stock_actual where almacen_id =" . $data['almacen_traslado_id'] . " and producto_id=" . $id_producto;

                        $almacen_traslado = $this->connection->query($query_stock_traslado)->row();

                        if (isset($almacen_traslado->unidades)) {
                            $unidades_traslado_actual = $value['cantidad'] + $almacen_traslado->unidades;
                        } else {
                            $unidades_traslado_actual = $value['cantidad'];
                        }

                        $this->connection->where('almacen_id', $data['almacen_traslado_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades_traslado_actual));

                        $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_traslado_id'], 'fecha' => date('Y-m-d'), 'unidad' => $value['cantidad'], 'precio' => $precio_compra, 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => 'E'));
                    } else {
                        $error_message .= '<p> El producto: con el Código <b> ' . $codigo_producto . '</b> no cuenta con la cantidad para realizar el traslado de ' . $value['cantidad'] . ' unidades.</p>';
                    }
                } else {
                    $error_message .= '<p> El producto: con el Código <b> ' . $codigo_producto . '</b> no se puede realizar el traslado de unidades en negativo ' . $value['cantidad'] . '.</p>';
                }
            }

            if (($error_message == "") && ($value['cantidad'] > 0)) {
                $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades));
                $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'), 'unidad' => $signo . $value['cantidad'], 'precio' => $precio_compra, 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => $razon));

                $value1['id_inventario'] = $id;
                $value1['codigo_barra'] = $codigo_producto;
                $value1['cantidad'] = $value['cantidad'];
                $value1['precio_compra'] = $precio_compra;
                $value1['existencias'] = $unidades_actual;
                $value1['nombre'] = $nombre_producto;
                $value1['total_inventario'] = ($precio_compra * $value['cantidad']);
                $value1['producto_id'] = $id_producto;
                if ($value1['cantidad'] > 0) {
                    $data_detalles[] = $value1;
                }

                if (!empty($data_detalles)) {
                    $this->connection->insert_batch("movimiento_detalle", $data_detalles);
                }

            }
        } else {
            $error_message .= "<p>El producto con el código (" . $codigo . ") no existe</p>";
        }
        return $error_message;
    }

    public function add_csv_inventario_3($id)
    {
        $total_inventario = 0;
        $product = "SELECT (cantidad * precio_compra) as total_inventario FROM movimiento_detalle where id_inventario = '" . $id . "'";
        $product = $this->connection->query($product)->result();
        foreach ($product as $dat) {
            $total_inventario += $dat->total_inventario;
        }

        $this->connection->where('id', $id);
        $this->connection->set('total_inventario', $total_inventario);
        $this->connection->update('movimiento_inventario');
    }

    public function add($data)
    {

        try {

            /*echo "<pre>";

            print_r($data);

            echo "</pre>";

            die;*/
            $costo_promedio = $this->opciones->getOpcion('costo_promedio');

            $this->connection->trans_begin();

            $productos = $data['productos'];
            unset($data['productos']);

            $this->connection->insert("movimiento_inventario", $data);
            $id = $this->connection->insert_id();

            $data_detalles = array();
            $query_stock = "";
            $error_message = "";

            foreach ($productos as $value) {

                if ($value["cantidad"] > 0) {
                    $this->connection->select("precio_compra");
                    $this->connection->from("producto");
                    $this->connection->where("id", $value["producto_id"]);
                    $result = $this->connection->get();
                    $precio_compra_antiguo = $result->result_array()[0]["precio_compra"];

                    if ($costo_promedio == 1) {
                        $value["precio_compra"] = (($value["existencias"] * $precio_compra_antiguo) + ($value["cantidad"] * $value["precio_compra"])) / ($value["existencias"] + $value["cantidad"]);
                    }

                    $sql = "update producto set precio_compra = '" . $value["precio_compra"] . "' WHERE id='" . $value['producto_id'] . "'";
                    $this->connection->query($sql);

                    //pi = producto por ingrediente
                    if ($this->connection->affected_rows() > 0) {
                        $sql_pi = "SELECT * FROM producto_ingredientes as pi INNER JOIN producto as p ON pi.id_ingrediente = p.id WHERE id_ingrediente = '" . $value['producto_id'] . "'";

                        $result_pi = $this->connection->query($sql_pi);
                        if ($result_pi->num_rows() > 0) {
                            foreach ($result_pi->result_array() as $pi) {
                                $this->connection->select("precio_compra");
                                $this->connection->from("producto");
                                $this->connection->where("id", $pi["id_producto"]);
                                $result = $this->connection->get();
                                $precio_compra_producto = $result->result_array()[0]["precio_compra"];

                                $sql_p = "SELECT  FROM producto WHERE id = " . $pi['id_producto'] . "'";
                                $precio_descontar = $precio_compra_antiguo * $pi["cantidad"];
                                $precio_sumar = $value["precio_compra"] * $pi["cantidad"];
                                $precio_compra = $precio_compra_producto - $precio_descontar + $precio_sumar;

                                $sql = "update producto set precio_compra = '" . $precio_compra . "' WHERE id='" . $pi["id_producto"] . "'";
                                $this->connection->query($sql);
                            }
                        }
                    }
                    /*$this->connection->where('id', $value['producto_id'])
                    ->update('producto', array('precio_compra' => $value["precio_compra"]));*/

                    $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value['producto_id'];
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    $almacen_final = $data['almacen_id'];
                    $razon = 'E';
                    $signo = '';
                    if ($data['tipo_movimiento'] == 'entrada_compra' || $data['tipo_movimiento'] == 'entrada_devolucion' || $data['tipo_movimiento'] == 'entrada_ajustes' || $data['tipo_movimiento'] == 'entrada_inicial' || $data['tipo_movimiento'] == 'entrada_remision') {
                        $unidades = $unidades_actual + $value['cantidad'];
                    } else if ($data['tipo_movimiento'] == 'salida_devolucion' || $data['tipo_movimiento'] == 'salida_rotura' || $data['tipo_movimiento'] == 'salida_ajustes' || $data['tipo_movimiento'] == 'salida_remision') {
                        $unidades = $unidades_actual - $value['cantidad'];
                        $razon = 'SM';
                        $signo = '-';
                    } else if ($data['tipo_movimiento'] == 'traslado') {

                        $unidades = $unidades_actual - $value['cantidad'];
                        $razon = 'ST';
                        $signo = '-';
                        $query_stock_traslado = "select * from stock_actual where almacen_id =" . $data['almacen_traslado_id'] . " and producto_id=" . $value['producto_id'];
                        $almacen_traslado = $this->connection->query($query_stock_traslado)->row();
                        $almacen_final = $almacen_traslado->id;
                        $unidades_traslado_actual = $value['cantidad'] + $almacen_traslado->unidades;
                        $this->connection->where('almacen_id', $data['almacen_traslado_id'])->where('producto_id', $value['producto_id'])->update('stock_actual', array('unidades' => $unidades_traslado_actual));
                        $this->connection->insert('stock_diario', array('producto_id' => $value['producto_id'], 'almacen_id' => $data['almacen_traslado_id'], 'fecha' => date('Y-m-d'), 'unidad' => $value['cantidad'], 'precio' => $value['precio_compra'], 'cod_documento' => $id, 'usuario' => $data['user_id'], 'razon' => 'ET'));

                    }

                    // Calculamos el promedio con la compra anterior del mismo producto
                    /*if($costo_promedio == 1){
                    $data['precio_compra_actual'] = $almacen->precio_compra;
                    $data['unidades_actual'] = $almacen->unidades;
                    $data['unidades'] = $value['cantidad'];
                    $data['precio_venta'] = $value['precio_compra'];
                    $data['almacen'] = $almacen_final;
                    $data['producto_id'] = $value['producto_id'];
                    //$data['precio_venta'] = ($data['precio_venta'] + $precio_compra) / 2;
                    $value['precio_compra'] = costo_promedio($data);
                    }*/

                    $this->connection->where('almacen_id', $data['almacen_id'])
                        ->where('producto_id', $value['producto_id'])
                        ->update('stock_actual', array('unidades' => $unidades));
                    //->update('stock_actual', array('unidades' => $unidades,'precio_compra'=> $value['precio_compra']));
                    $this->connection->insert('stock_diario',
                        array('producto_id' => $value['producto_id'],
                            'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'),
                            'unidad' => $signo . $value['cantidad'],
                            'precio' => $value['precio_compra'],
                            'cod_documento' => $id,
                            'usuario' => $data['user_id'], 'razon' => $razon));

                    $value['producto_id'];
                    $value['id_inventario'] = $id;
                    $data_detalles[] = $value;
                } else {
                    $error_message .= '<p> El producto: con el Código <b> ' . $value['codigo_barra'] . '</b> no se puede realizar el traslado de unidades en negativo ' . $value['cantidad'] . '.</p>';
                }
            }
            if (!empty($data_detalles)) {
                $this->connection->insert_batch("movimiento_detalle", $data_detalles);
            }

            if ($this->connection->trans_status() === false) {

                $this->connection->trans_rollback();
            } else {

                $this->connection->trans_commit();
            }

            return $id;
        } catch (Exception $e) {
            /*$this->connection->trans_rollback();
        print_r($e);die; */
        }
    }

    public function add_by_auditoria($data)
    {

        $value = $data["producto"];
        $productos = $data['producto'];
        unset($data['producto']);

        $this->connection->insert("movimiento_inventario", $data);
        $id = $this->connection->insert_id();

        $data_detalles = array();
        $query_stock = "";
        $error_message = "";

        if ($value["cantidad"] > 0) {
            $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value['producto_id'];
            $almacen = $this->connection->query($query_stock)->row();
            $unidades_actual = $almacen->unidades;
            $almacen_final = $data['almacen_id'];
            $razon = 'E';
            $signo = '';
            if ($data['tipo_movimiento'] == 'entrada_auditoria') {
                $unidades = $unidades_actual + $value['cantidad'];
            } else if ($data['tipo_movimiento'] == 'salida_auditoria' || $data['tipo_movimiento'] == 'devolucion_orden' || $data['tipo_movimiento'] == 'devolucion_auditoria') {
                $unidades = $unidades_actual - $value['cantidad'];
                $razon = 'SM';
                $signo = '-';
            }

            $this->connection->where('almacen_id', $data['almacen_id'])
                ->where('producto_id', $value['producto_id'])
                ->update('stock_actual', array('unidades' => $unidades));
            //->update('stock_actual', array('unidades' => $unidades,'precio_compra'=> $value['precio_compra']));
            $this->connection->insert('stock_diario',
                array('producto_id' => $value['producto_id'],
                    'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'),
                    'unidad' => $signo . $value['cantidad'],
                    'precio' => $value['precio_compra'],
                    'cod_documento' => $id,
                    'usuario' => $data['user_id'], 'razon' => $razon));

            $value['producto_id'];
            $value['id_inventario'] = $id;
            $data_detalles[] = $value;
        } else {
            $error_message .= '<p> El producto: con el Código <b> ' . $value['codigo_barra'] . '</b> no se puede realizar el traslado de unidades en negativo ' . $value['cantidad'] . '.</p>';
        }
        if (!empty($data_detalles)) {
            $this->connection->insert_batch("movimiento_detalle", $data_detalles);
        }

        return $id;
    }

    //obtenemos un array de los ingredientes
    public function get_ing_by_producto($id)
    {

        $query = $this->connection->query("select id_ingrediente,cantidad from producto_ingredientes where id_producto = $id");
        return $query->result_array();
    }
    public function afecta_stock($producto, $id_almacen, $unidades_compra, $num_factura = null, $usuario_id)
    {
        echo $id_almacen;
        die();
    }
    public function movimiento_inventario($data)
    {

        try {
            $this->connection->trans_begin();
            $productos = $data['productos'];
            unset($productos['cantidad']);
            unset($data['productos']);

            $this->connection->insert("movimiento_inventario", $data);

            $id = $this->connection->insert_id();
            $data_detalles = array();
            $query_stock = "";
            if ($data['tipo_movimiento'] == 'entrada_devolucion') {
                foreach ($productos as $value) {
                    //Verificamos los ingredientes del producto
                    $ingredientes = $this->get_ing_by_producto($value['producto_id']);

                    //recorremos los ingredientes
                    foreach ($ingredientes as $ingrediente) {
                        $this->afecta_stock($ingrediente['id_ingrediente'], $data['almacen_id'], $ingrediente['cantidad'], $id, $this->session->userdata('user_id'));
                    }
                    //$this->aumenta_stock($value, $data['almacen_id'], $value['cantidad_devolver'], $id, $this->session->userdata('user_id'));
                    //$this->afecta_stock($ingredientes,$data['almacen_id']);
                }

            }

        } catch (Exception $e) {

            /* $this->connection->trans_rollback();

        print_r($e);die; */
        }
    }

    public function add_devolucion($data)
    {

        try {
            $this->connection->trans_begin();

            $productos = $data['productos'];
            unset($productos['cantidad']);
            unset($data['productos']);

            $this->connection->insert("movimiento_inventario", $data);

            $id = $this->connection->insert_id();
            $data_detalles = array();
            $query_stock = "";

            /*echo "<pre>";
            print_r($productos);
            echo "</pre>";
            die;*/

            foreach ($productos as $value) {
                $this->aumenta_stock($value, $data['almacen_id'], $value['cantidad_devolver'], $id, $this->session->userdata('user_id'));
                /*$query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value['producto_id'];
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $razon = 'E';
                $signo = '';
                if ($data['tipo_movimiento'] == 'entrada_devolucion') {
                $unidades = $unidades_actual + $value['cantidad'];
                }
                $this->connection->where('almacen_id', $data['almacen_id'])
                ->where('producto_id', $value['producto_id'])
                ->update('stock_actual', array('unidades' => $unidades));

                if(!isset($value['precio_compra']))
                {
                $productoPrecios = $this->connection->select('precio_compra')->get_where('producto',array('id'=>$value['producto_id']));
                $value['precio_compra'] = $productoPrecios->row_array()['precio_compra'];
                }

                $this->connection->insert('stock_diario',
                array('producto_id' => $value['producto_id'],
                'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'),
                'unidad' => $signo.$value['cantidad'],
                'precio' => $value['precio_compra'],
                'cod_documento' => $id,
                'usuario' => $data['user_id'],
                'razon' => $razon
                ));*
                 */
                $producto = $this->connection->get_where('producto', array('id' => $value['producto_id']))->row();

                $data_detalles[] = array(
                    "id_inventario" => $id,
                    "codigo_barra" => $producto->codigo,
                    "cantidad" => $value['cantidad_devolver'],
                    "precio_compra" => $value['precio_compra'],
                    "nombre" => $value['nombre_producto'],
                    "total_inventario" => $value['precio_compra'] * $value['cantidad_devolver'],
                    "producto_id" => $value['producto_id'],
                    "existencias" => isset($value['existencias']) ? $value['existencias'] : 0,
                );
            }

            //var_dump($data_detalles);
            $this->connection->insert_batch("movimiento_detalle", $data_detalles);

            if ($this->connection->trans_status() === false) {
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
            }

            return $id;
        } catch (Exception $e) {

            /* $this->connection->trans_rollback();

        print_r($e);die; */
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

    public function anular($data)
    {

        try {

            $this->connection->trans_begin();

            $venta = $this->connection->query("select * from venta where id = {$data['id']}")->row();

            /* echo "<pre>";

            print_r($venta);

            echo "</pre>";

            die; */

            $this->connection->where('id', $data['id']);

            $this->connection->update("venta", array('estado' => '-1'));

            $this->connection->insert('ventas_anuladas', array('venta_id' => $data['id'], 'usuario_id' => $data['usuario'], 'fecha' => date('Y-m-d H:i:s'), 'motivo' => $data['motivo']));

            $detalles_venta = $this->connection->query("SELECT * FROM `detalle_venta` where venta_id = {$data['id']}")->result();

            foreach ($detalles_venta as $value) {

                $producto = $this->connection->query("select * from producto where codigo = '{$value->codigo_producto}'")->row();

                $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $producto->id;

                $almacen = $this->connection->query($query_stock)->row();

                $unidades_actual = $almacen->unidades;

                $unidades = $unidades_actual + $value->unidades;

                $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $producto->id)->update('stock_actual', array('unidades' => $unidades));

                $this->connection->insert('stock_diario', array('producto_id' => $producto->id, 'almacen_id' => $venta->almacen_id, 'fecha' => date('Y-m-d'), 'unidad' => $value->unidades, 'precio' => $value->precio_venta, 'cod_documento' => $venta->factura, 'usuario' => $data['usuario'], 'razon' => 'E'));
            }

            if ($this->connection->trans_status() === false) {

                $this->connection->trans_rollback();
            } else {

                $this->connection->trans_commit();
            }
        } catch (Exception $ex) {

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

    public function productos_consolidado_almacen()
    {

        $is_admin = $this->session->userdata('is_admin');

        $username = $this->session->userdata('username');

        //------------------------------------------------ almacen usuario
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = '';
        $alm = '';
        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $alm = ' WHERE  almacen_id =  ' . $dat->almacen_id;
        }

        $query = $this->connection->query("SELECT codigo, producto.nombre as nombre, unidades, producto.id as id, almacen.nombre as almacen   FROM producto inner join stock_actual on stock_actual.producto_id = producto.id   inner join almacen on almacen.id = stock_actual.almacen_id  $alm");
        return $query->result_array();
    }

    public function add_csv_MovientosDetalles($data = false)
    {
        if ($data != false) {
            $this->connection->insert_batch('movimiento_detalle', $data);
        }
    }

    public function aumenta_stock($productos, $id_almacen, $unidades_compra, $num_factura = null, $usuario_id)
    {
        // Comprobamos si es multidimensional, es decir si vienen los productos que componen un combo o un compuesto
        if (count($productos) != count($productos, COUNT_RECURSIVE)) {
            //if (count($productos) > 1) {
            foreach ($productos as $rowProductos) {
                $this->aumenta_stock($rowProductos, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
            }
        } else {
            //echo "Entro por el else <br/>";
            //echo "<pre>";
            //    print_r($productos);
            // echo "</pre>";
            if (isset($productos['product_id'])) {
                $productos['id'] = $productos['product_id'];
            } else if (isset($productos['producto_id'])) {
                $productos['id'] = $productos['producto_id'];
            } else
            if (isset($productos['id_combo'])) {
                $productos['id'] = $productos['id_producto'];
            } else
            if (isset($productos['id_ingrediente'])) {
                $productos['id'] = $productos['id_ingrediente'];
            }

            if (isset($productos['id'])) {
                //echo "Tengo el id del producto {$productos['id']}<br/>";
                $productoCombo = $this->connection->get_where("producto", array("id" => $productos['id']))->row();

                if (isset($productoCombo->id)) {

                    if ($productoCombo->ingredientes != 1 && $productoCombo->combo != 1) {
                        //echo "No soy combo ni ingrediente: {$productoCombo->id} <br/>";
                        $query_stock = "select * from stock_actual where almacen_id =" . $id_almacen . " and producto_id=" . $productoCombo->id;

                        $almacen = $this->connection->query($query_stock)->row();

                        //echo "Actualmente producto {$productoCombo->id} tiene {$almacen->unidades} unidades<br/>";

                        $unidades_actual = $almacen->unidades;
                        if (isset($productos['cantidad'])) {
                            $desc_inventario = $productos['cantidad'] * $unidades_compra;
                        } else {
                            $desc_inventario = $unidades_compra;
                        }
                        $unidades = $unidades_actual + ($desc_inventario); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                        //Actualizar stock
                        //echo "Almacen $id_almacen, Producto: {$productoCombo->id}, Unidades: $unidades <br/>";
                        $this->connection->where('almacen_id', $id_almacen)
                            ->where('producto_id', $productoCombo->id)
                            ->update('stock_actual', array('unidades' => $unidades));

                        //echo "Se actualizo correctamente stock_actual <br/>";

                        //Insertar stock diario
                        $this->connection->insert(
                            'stock_diario', array(
                                'producto_id' => $productoCombo->id,
                                'almacen_id' => $id_almacen,
                                'fecha' => date('Y-m-d'),
                                'unidad' => $desc_inventario,
                                'precio' => 0,
                                'cod_documento' => $num_factura,
                                'usuario' => $usuario_id,
                                'razon' => 'E',
                            )
                        );

                        // echo "Se actualizo correctamente stock_diario <br/>";

                    } else {
                        if ($productoCombo->ingredientes == 1) {
                            $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $productoCombo->id;
                            $ingredientes_producto = $this->connection->query($query_ingredientes_producto);
                            // Se ejecuta funcion Recursiva para ingredientes
                            $product_array = $ingredientes_producto->result_array();
                            $this->aumenta_stock($product_array, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
                        }
                        if ($productoCombo->combo == 1) {
                            $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $productoCombo->id;
                            $productos_combo = $this->connection->query($query_productos_combo);
                            // Se ejecuta funcion Recursiva para compuestos
                            $product_array = $productos_combo->result_array();
                            $this->aumenta_stock($product_array, $id_almacen, $unidades_compra, $num_factura, $usuario_id);
                        }
                    }
                }
            }
        }
    }

}

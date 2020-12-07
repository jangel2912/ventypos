<?php

class Orden_compra_model extends CI_Model
{

    public $connection;

    // Constructor

    public function __construct()
    {

        parent::__construct();
        $this->load->model("opciones_model", "opciones");
        $this->load->helper(array('costo_promedio', 'costo_promedio'));
        $this->opciones->initialize($this->dbConnection);

        $this->load->model("almacenes_model", "almacenes");
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("pagos_model", "pagos");
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

    }

    public function initialize($connection)
    {

        $this->connection = $connection;

    }

    public function get_ajax_data_informe($estado = 0)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " WHERE estado = '$estado'";
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
            $sWhere = " WHERE estado = '$estado' and almacen_id = '$almacen'";

        }

        $sql = "SELECT o.id, factura, o.fecha_vencimiento, nif_cif, nombre_comercial, nombre, total_venta,  DATE(o.fecha) as fechapedido,nota
                FROM orden_compra AS o
                left join proveedores on proveedores.id_proveedor = o.cliente_id
                left join almacen a on o.almacen_id = a.id $sWhere ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $total_cantidad = 0;
            $sql1 = "SELECT sum(cantidad) as total_cantidad
                FROM pago_orden_compra where id_factura = '$value->id'";
            foreach ($this->connection->query($sql1)->result() as $value1) {
                $total_cantidad = $value1->total_cantidad;
            }
            $total_compra = 0;
            $total_impuesto = 0;
            $sql2 = "SELECT SUM( dv.unidades * ((dv.descuento * dv.precio_venta) / 100) ) AS total_descuento,
                    SUM((dv.precio_venta - ((dv.descuento * dv.precio_venta) / 100)) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                    FROM detalle_orden_compra as dv where venta_id = '$value->id'";
            foreach ($this->connection->query($sql2)->result() as $value2) {
                $total_compra = ($value2->total_precio_venta - $value2->total_descuento) + $value2->impuesto;
                $total_impuesto += $value2->impuesto;
            }
            //precision del redondeo
            $precision = $this->opciones_model->getDataMoneda();

            $data[] = array(
                $value->id,
                $value->fecha_vencimiento,
                $value->nif_cif,
                $value->nombre_comercial,
                $value->nombre,
                round($total_cantidad, $precision->decimales),
                round($total_impuesto, $precision->decimales),
                round($total_compra, $precision->decimales),
                //$this->opciones_model->formatoMonedaMostrar($total_compra,$precision->decimales),
                round(($total_compra - $total_cantidad), $precision->decimales),
                $value->fechapedido,
                $value->nota,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function get_ajax_data($estado = 0)
    {

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         *
         * I added $recordsFiltered ( is the record counter )
         */
        $recordsFiltered = 0;

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $user2 = "";
        $camposanulacion = "";

        if ($is_admin == 't' || $is_admin == 'a') { //administrador

            $sWhere = " WHERE estado = '$estado'";

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
            $sWhere = " WHERE estado = '$estado' and almacen_id = '$almacen'";

        }

        if ($estado != 0) {
            $user2 = " LEFT JOIN vendty2.users u ON o.id_user_anulacion = u.id ";
            $camposanulacion = ", o.motivo, o.id_user_anulacion, CONCAT(u.first_name, ' ', u.last_name) as usuario, o.fecha_anulacion";
        }

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         *
         * I added $searchParameters (Is the filter of query by parameters), $limit (Is the limit of registers by parameters example "limit 0,5")
         */
        $searchParameters = "";
        $limit = "";
        /* validate parameters of url */
        if (isset($_GET['sSearch'])) {
            $sSearch = $_GET['sSearch'];
            $iDisplayStart = $_GET['iDisplayStart'];
            $iDisplayLength = $_GET['iDisplayLength'];

            if ($iDisplayStart || $iDisplayLength) {
                $limit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
            } else {
                $limit = "LIMIT 0, 5 ";
            }

            if ($sSearch) {
                $searchParameters = " AND (o.id LIKE '%$sSearch%' OR o.total_venta LIKE '%$sSearch%' OR fecha LIKE '%$sSearch%' OR o.fecha_vencimiento LIKE '%$sSearch%' OR nif_cif LIKE '%$sSearch%' OR nombre_comercial LIKE '%$sSearch%' OR a.nombre LIKE '%$sSearch%' OR o.almacen_id LIKE '%$sSearch%')";
            }

        }

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         * Query for data of purchase orders
         */
        $sql =
            "SELECT o.id, o.total_venta, fecha, o.fecha_vencimiento, nif_cif, nombre_comercial,  a.nombre, o.almacen_id $camposanulacion
            FROM orden_compra AS o
            left join proveedores on proveedores.id_proveedor = o.cliente_id
            $user2
            left join almacen a on o.almacen_id = a.id $sWhere $searchParameters
            ORDER BY o.fecha_anulacion DESC, o.id DESC $limit";

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         *  Count for data of purchase orders with parameters of search
         */
        $sqlCount =
            "SELECT count(*)
            FROM orden_compra AS o
            LEFT JOIN proveedores ON proveedores.id_proveedor = o.cliente_id
            $user2
            LEFT JOIN almacen a ON o.almacen_id = a.id $sWhere $searchParameters
            ORDER BY o.fecha_anulacion DESC, o.id DESC";

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         *  Count for data of purchase orders without parameters of search
         */
        $sqlTotal =
            "SELECT count(*)
            FROM orden_compra AS o
            LEFT JOIN proveedores ON proveedores.id_proveedor = o.cliente_id
            $user2
            LEFT JOIN almacen a ON o.almacen_id = a.id $sWhere
            ORDER BY o.fecha_anulacion DESC, o.id DESC";

        $data = array();

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         * Execute queries
         */
        $orden_compra = $this->connection->query($sql)->result();
        $recordsFiltered = $this->connection->query($sqlCount)->row()->{'count(*)'};
        $totalRecords = $this->connection->query($sqlTotal)->row()->{'count(*)'};

        foreach ($orden_compra as $value) {
            $pagada = 0;
            $afectada = 0;
            $totald = 0;
            $timpd = 0;
            $subtotald = 0;
            $total_itemsd = 0;
            $valor_total_pd = 0;
            $devoluciones = 0;

            $id1 = $this->afectar_inventario_si_no($value->id);
            if ($id1 == $value->id && $value->id != null) {
                $afectada = 1;
                $devoluciones = 0;
                $sqldo = "SELECT * FROM detalle_orden_compra WHERE venta_id= $value->id";

                $detalle_venta = $this->connection->query($sqldo)->result_array();

                foreach ($detalle_venta as $p) {

                    $pv = $p['precio_venta'];

                    $desc = $p['descuento'];

                    $pvd = $pv - ($desc * $pv / 100);

                    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

                    $total_column = $pvd * $p['unidades'];

                    $total_itemsd += $total_column + $imp;

                    $valor_total = $pvd * $p['unidades'] + $imp;

                    $valor_total_pd += $pvd * $p['unidades'] + $imp;

                    $totald += $totald + $valor_total;

                    $timpd += $imp;
                }
                $valor_total_pd = round($valor_total_pd, 2, PHP_ROUND_HALF_UP);
                $venta_totald = (float) ($value->total_venta);
                $devoluciones = ($venta_totald - $valor_total_pd);
            }
            $total_compra = 0;
            $anular = 1;
            $sql2 = "SELECT SUM( dv.unidades * ((dv.descuento * dv.precio_venta) / 100) ) AS total_descuento,
                        SUM((dv.precio_venta - ((dv.descuento * dv.precio_venta) / 100)) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                        ,dv.producto_id, dv.unidades
                FROM detalle_orden_compra as dv where venta_id = '$value->id'
                ";

            foreach ($this->connection->query($sql2)->result() as $value2) {
                if (isset($value2->producto_id) && (!empty($value2->producto_id))) {
                    $sql3 = "SELECT unidades AS unidades_stock FROM stock_actual WHERE producto_id=$value2->producto_id AND almacen_id=$value->almacen_id";
                    $cantstock = $this->connection->query($sql3)->row();
                    if (isset($cantstock->unidades_stock)) {
                        if ($cantstock->unidades_stock < $value2->unidades) {
                            $anular = 0;
                        }
                    }
                }
            }

            $total_pagos = 0;
            $pagos_orden_compra = $this->pagos->get_all_orden_compra($value->id, 0);
            foreach ($pagos_orden_compra as $pago) {
                $total_pagos += $pago->cantidad;
            }

            $total_compra = $value->total_venta;
            $final = $total_compra - $total_pagos - $devoluciones;
            if ($final == 0) {
                $pagada = 1;
            }

            if ($estado == 0) {
                $data[] = array(
                    $value->id,
                    $value->nif_cif,
                    $value->nombre_comercial,
                    $value->fecha,
                    $this->opciones_model->formatoMonedaMostrar($total_compra),
                    $value->nombre,
                    $value->id,
                    $anular,
                    $afectada,
                    $pagada,
                );
            } else {
                $data[] = array(
                    $value->id,
                    $value->nif_cif,
                    $value->nombre_comercial,
                    $value->fecha,
                    $this->opciones_model->formatoMonedaMostrar($total_compra),
                    $value->nombre,
                    $value->usuario,
                    $value->motivo,
                    $value->fecha_anulacion,
                    $value->id,
                    $anular,
                    $afectada,
                    $pagada,
                );
            }

        }

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         *
         * $data => Data of query
         * $totalRecords => Total registers in the db
         * $recordsFiltered => Total registers filtered in the db
         */

        return array(
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
        );
    }

    public function get_by_id($id = 0)
    {

        $query = $this->connection->query("SELECT
		orden_compra.id as id_venta,
		 orden_compra.*,
         orden_compra.fecha_vencimiento as fecha_vencimiento_orden,
		  vendedor.nombre as vendedor,
		   proveedores.nombre_comercial,
		    proveedores.email,
			 proveedores.nif_cif,
			  proveedores.telefono as proveedores_telefono,
			   proveedores.direccion as proveedores_direccion ,
			    almacen.*
		 FROM  orden_compra
		  inner join almacen on almacen.id = orden_compra.almacen_id
		  left join proveedores on id_proveedor = orden_compra.cliente_id
		  left join vendedor on vendedor.id = orden_compra.vendedor

		  WHERE orden_compra.id = '" . $id . "'");

        return $query->row_array();

    }

    public function getDetailTaskOrder($id)
    {
        $query = $this->connection->query("SELECT
		orden_compra.id as id_venta,
		 orden_compra.*,
         orden_compra.fecha_vencimiento as fecha_vencimiento_orden,
		  vendedor.nombre as vendedor,
		   proveedores.nombre_comercial,
		    proveedores.email,
			 proveedores.nif_cif,
			  proveedores.telefono as proveedores_telefono,
			   proveedores.direccion as proveedores_direccion ,
			    almacen.nombre
		 FROM  orden_compra
		  inner join almacen on almacen.id = orden_compra.almacen_id
		  left join proveedores on id_proveedor = orden_compra.cliente_id
		  left join vendedor on vendedor.id = orden_compra.vendedor

		  WHERE orden_compra.id = '" . $id . "'");

        return $query->row_array();
    }

    public function get_detalles_ventas($id = 0)
    {

        $query = $this->connection->query("
            SELECT
                    (SELECT precio_venta FROM producto WHERE id = producto_id limit 1) as precio_venta_final,
                detalle_orden_compra.id,venta_id,codigo_producto,nombre_producto,detalle_orden_compra.descripcion_producto as descripcion_producto,unidades,
                detalle_orden_compra.precio_venta,descuento,detalle_orden_compra.impuesto,linea,margen_utilidad,detalle_orden_compra.activo,
                 producto_id,
                 /*unidades.nombre as nombre_unidad,*/
                 IF(unidades.nombre IS NULL, (SELECT unidades.nombre FROM producto INNER JOIN unidades ON producto.unidad_id=unidades.id WHERE producto.id=producto_id LIMIT 1) , unidades.nombre ) AS nombre_unidad,
                 detalle_orden_compra.precio_venta_p
            FROM
            detalle_orden_compra
			left join unidades on unidades.id = detalle_orden_compra.id_unidad
            where
            venta_id = '" . $id . "'"
        );

        return $query->result_array();

    }

    public function get_detalles_pago($id = 0)
    {

        $query = $this->connection->query("SELECT *,f.nombre as forma_pago FROM ventas_pago AS v INNER JOIN forma_pago AS f ON f.codigo= v.forma_pago WHERE id_venta = '" . $id . "'");
        return $query->row_array();

    }

    public function add_actualizar($id = 0)
    {

    }

    public function add($data)
    {

        $id_orden_compra = 0;
        if ($data['cliente'] == "") {
            $id_cliente = -1;
        } else {
            $id_cliente = $data['cliente'];
        }

        if ($data['almacen'] == '0') {$data['almacen'] = 1;}

        $array_datos = array(

            "fecha" => $data['fecha'] . ' ' . date('H:i:s'),

            "fecha_vencimiento" => $data['fecha_vencimiento'],

            "usuario_id" => $data['usuario'],

            "factura" => '',

            "almacen_id" => ($data['almacen']),

            "total_venta" => $data['total_venta'],

            "cliente_id" => $id_cliente,

            "tipo_factura" => $data['tipo_factura'],
            "nota" => $data['nota'],

        );

        if (!empty($data['vendedor'])) {

            $array_datos["vendedor"] = $data['vendedor'];

        }

        $this->connection->insert("orden_compra", $array_datos);

        $id_orden_compra = $this->connection->insert_id();

        $data_detalles = array();

        $query_stock = "";

        foreach ($data['productos'] as $value) {

            $unidades_compra = $value['unidades'];
            $nombre = $value['nombre_producto'];
            $codigo = $value['codigo'];
            $id_unidad = $value['id_unidades'];
            $product_id_f = $value['product_id'];

            if (strlen($nombre) > 0) {

                $unidad = $this->connection->query("SELECT * FROM `unidades` where nombre = '{$id_unidad}' ")->result();
                foreach ($unidad as $dat1) {
                    $unid = $dat1->id;

                }

                if (empty($value['descripcion'])) {
                    $value['descripcion'] = '';
                }

                $data_detalles[] = array(

                    'venta_id' => $id_orden_compra

                    , 'codigo_producto' => $codigo

                    , 'precio_venta' => $value['precio_venta']

                    , 'unidades' => $value['unidades']

                    , 'nombre_producto' => $value['nombre_producto']

                    , 'descripcion_producto' => $value['descripcion']

                    , 'impuesto' => $value['impuesto']

                    , 'descuento' => $value['descuento']

                    , 'margen_utilidad' => ''

                    , 'id_unidad' => $unid

                    , 'producto_id' => $product_id_f

                    , 'precio_venta_p' => $value['precio_venta2'],
                );

                /*.......................................*/
            }

        }

        $this->connection->insert_batch("detalle_orden_compra", $data_detalles);

        return count($data['productos']);

    }

    public function edit($id)
    {
        $this->db->get('venta');
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

    public function afectar_inventario_si_no($id = null)
    {
        $id_producto = 0;
        $product = $this->connection->query("SELECT * FROM movimiento_inventario where codigo_factura = '" . $id . "' AND tipo_movimiento='entrada_compra' limit 1")->result();
        foreach ($product as $dat) {
            $id_producto = $dat->codigo_factura;
        }

        return $id_producto;

    }

    public function validar_movimiento($codigo_factura)
    {

        if ($codigo_factura != null) {
            $this->connection->select("codigo_factura");
            $this->connection->from("movimiento_inventario mi");
            $this->connection->where("mi.codigo_factura", $codigo_factura);
            $this->connection->where("mi.tipo_movimiento", "entrada_compra");
            $this->connection->limit("1");
            $result = $this->connection->get();

            if ($result->num_rows() == 1) {
                return $result->result()[0]->codigo_factura;
            } else {
                return null;
            }
        } else {
            return null;
        }

    }

    public function afectar_inventario_1($data)
    {

        $this->connection->insert("movimiento_inventario", $data);

        $id = $this->connection->insert_id();

        return $id;
    }
    public function eliminar_movimiento_inventario($id)
    {
        $this->connection->where('id', $id);
        $this->connection->delete("movimiento_inventario");
    }

    public function update_valor_total($data, $where)
    {
        $this->connection->where($where);
        $this->connection->update("orden_compra", $data);
    }

    public function afectar_inventario_nuevo()
    {

        try {

            $this->connection->trans_begin();

            $id = $this->input->post("id");
            $productos = $this->input->post("id_producto");
            $error = "";

            $id_compra = $_POST['id'];
            $id_producto = $_POST['id_producto'];
            $codigo = $_POST['codigo'];
            $product_service = $_POST['product-service'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $precio_venta_p = $_POST['precio_venta2'];
            $precio_venta_actual = $_POST['precio_venta_actual'];
            $producto_id = $_POST['producto_id'];

            $descuento = $_POST['descuento'];
            $data = array(
                'user_id' => $this->session->userdata('user_id')
                , 'fecha' => date('Y-m-d H:i:s')
                , 'almacen_id' => $_POST['almacen']
                , 'tipo_movimiento' => 'entrada_compra'
                , 'total_inventario' => $_POST['input_total_siva']
                , 'proveedor_id' => $_POST['proveedor']
                , 'codigo_factura' => $_POST['id'],
            );

            $id = $this->afectar_inventario_1($data);

            if (!empty($id)) {

                for ($i = 0; $i < count($id_producto); $i++) {

                    $data = array(
                        'id_compra' => $id_compra,
                        'id' => $id_producto[$i],
                        'almacen' => $_POST['almacen'],
                        'unidades' => $cantidad[$i],
                        'codigo' => $codigo[$i],
                        'nombre_producto' => $product_service[$i],
                        'precio_venta' => $precio[$i],
                        'precio_venta_p' => $precio_venta_p[$i],
                        'precio_venta_actual' => $precio_venta_actual[$i],
                        'producto_id' => $producto_id[$i],
                    );

                    $this->afectar_inventario($data, $id);
                    //actualizar precio_venta en stock_Actual o producto
                    $precio_almacen = $this->opciones->getOpcion('precio_almacen');
                    $dataP = array(
                        "precio_venta" => $precio_venta_p[$i],
                    );

                    if ($precio_almacen == 1) {
                        $this->connection->where('producto_id', $producto_id[$i]);
                        $this->connection->where('almacen_id', $_POST['almacen']);
                        $this->connection->update("stock_actual", $dataP);
                    } else {
                        $this->connection->where('id', $producto_id[$i]);
                        $this->connection->update("producto", $dataP);
                    }
                    //verificar si fue ingresado el registro
                    $valido = $this->verificar_afectar_inventario($data, $id);
                    if ($valido == 0) { //no se ingreso error
                        $error += "Hubo un error, no pudo ser procesada la orden de compra";
                    }
                }
            } else {
                $error = "Hubo un error, no pudo ser procesada la orden de compra";
            }

            if (($this->connection->trans_status() === false) || (!empty($error))) {
                $this->connection->trans_rollback();
                return $error;
            } else {
                $this->connection->trans_commit();
                return 1;
            }
        } catch (Exception $e) {
            print_r($e);
            die();
        }
    }
    public function afectar_inventario($data, $id)
    {

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $costo_promedio = $this->opciones->getOpcion('costo_promedio');

        $array_datos = array(
            "unidades" => $data['unidades'],
            "precio_venta" => $data['precio_venta'],
            "precio_venta_p" => $data['precio_venta_p'],
            "precio_venta_actual" => $data['precio_venta_actual'],

        );

        $this->connection->where('id', $data['id']);
        $this->connection->update("detalle_orden_compra", $array_datos);

        $id_producto = 0;
        $nombre_producto = 0;
        $codigo_producto = 0;
        $precio_compra = 0;
        $precio_venta_p = 0;
        $precio_venta_actual = 0;
        $user_id = $this->session->userdata('user_id');

        $codigo_interno_prod = $data['producto_id'];

        // Validamos si tiene precios por almacen o no.
        if ($precio_almacen == 1) {
            // Si tiene precio por almacen
            if ($codigo_interno_prod != '') {
                $product = $this->connection->select('producto.*,stock_actual.unidades,stock_actual.precio_compra as precio_compra_stock,stock_actual.precio_venta as precio_venta_stock')
                    ->from('producto')
                    ->join('stock_actual', 'producto.id = stock_actual.producto_id')
                    ->where('producto.id', $codigo_interno_prod)
                    ->where('stock_actual.almacen_id', $data['almacen'])
                    ->get()
                    ->row();
            } else {
                $product = $this->connection->select('producto.*,stock_actual.unidades,stock_actual.precio_compra as precio_compra_stock, stock_actual.precio_venta as precio_venta_stock')
                    ->from('producto')
                    ->join('stock_actual', 'producto.id = stock_actual.producto_id')
                    ->where('producto.nombre', $data['nombre_producto'])
                    ->where('stock_actual.almacen_id', $data['almacen'])
                    ->get()
                    ->row();
            }

            $id_producto = $product->id;
            $nombre_producto = $product->nombre;
            $codigo_producto = $product->codigo;
            $precio_compra = $product->precio_compra_stock;
            $precio_venta_p = $data['precio_venta_p'];
            $precio_venta_actual = $product->precio_venta_stock;
            $ganancia = $product->ganancia;
            // Cantidad actual del producto por almacen
            $unidades_actual = $product->unidades;
        } else {
            // Si NO tiene precio por Almacen
            if ($codigo_interno_prod != '') {
                $product = $this->connection->query("SELECT * FROM producto where id = '" . $codigo_interno_prod . "'   limit 1")->result();
                foreach ($product as $dat) {
                    $id_producto = $dat->id;
                    $nombre_producto = $dat->nombre;
                    $codigo_producto = $dat->codigo;
                    $precio_compra = $dat->precio_compra;
                    $precio_venta_p = $data['precio_venta_p'];
                    $precio_venta_actual = $dat->precio_venta;
                    $ganancia = $dat->ganancia;
                }
                $query_stock = "select * from stock_actual where almacen_id = " . $data['almacen'] . " and producto_id=" . $codigo_interno_prod;
            } else {
                $product = $this->connection->query("SELECT * FROM producto where nombre = '" . $data['nombre_producto'] . "' and codigo = '" . $data['codigo'] . "'   limit 1")->result();
                foreach ($product as $dat) {
                    $id_producto = $dat->id;
                    $nombre_producto = $dat->nombre;
                    $codigo_producto = $dat->codigo;
                    $precio_compra = $dat->precio_compra;
                    $precio_venta_p = $data['precio_venta_p'];
                    $precio_venta_actual = $dat->precio_venta;
                    $ganancia = $dat->ganancia;
                }
                $query_stock = "select * from stock_actual where almacen_id = " . $data['almacen'] . " and producto_id=" . $id_producto;
            }
            // Cantidad actual del producto

            $almacen = $this->connection->query($query_stock)->row();
            if (count($almacen)) {
                $unidades_actual = $almacen->unidades;
                isset($almacen->precio_compra) ? $precio_compra = $almacen->precio_compra : $precio_compra = $precio_compra;
            }
        }
        // Calculamos el promedio con la compra anterior del mismo producto
        if ($costo_promedio == 1) {
            $data['unidades_actual'] = $unidades_actual;
            $data['precio_compra_actual'] = $precio_compra;
            //$data['precio_venta'] = ($data['precio_venta'] + $precio_compra) / 2;
            $data['precio_venta'] = costo_promedio($data);
        }

        if ($ganancia == '0') {
            $productos_compra = array(
                "precio_compra" => $data['precio_venta'],
            );
            if ($precio_almacen == 1) {
                $detalle_combo = $this->productos->getProductoComboAlmacen($id_producto, $data['almacen']);
                $this->connection->where('producto_id', $id_producto);
                $this->connection->where('almacen_id', $data['almacen']);
                $this->connection->update("stock_actual", $productos_compra);
                $detalle_producto_nuevo = $this->productos->getStockActualo($id_producto, $data['almacen']);
            } else {
                $detalle_combo = $this->productos->getProductoCombo($id_producto);
                $this->connection->where('id', $id_producto);
                $this->connection->update("producto", $productos_compra);
                $detalle_producto_nuevo = $this->productos->getProducto($id_producto);
            }
            /******actualizar precio combo *** */
            if ($detalle_combo) {
                foreach ($detalle_combo as $var) {
                    $nuevo_precio = 0;
                    $precio_compra1 = ($var['precio_compra'] == '' || $var['precio_compra'] == null) ? 0 : $var['precio_compra'];
                    $detalle_producto = $this->productos->getProducto($var['id_combo']);
                    $precio_viejo = $precio_compra1 * $var['cantidad'];
                    $nuevo_precio = $detalle_producto_nuevo[0]['precio_compra'] * $var['cantidad'];
                    $nuevo_precio = $detalle_producto[0]['precio_compra'] - $precio_viejo + $nuevo_precio;
                    $this->productos->updatePrecioCombo($var['id_combo'], $nuevo_precio);
                }
            }
            /******************************** */
        }

        if ($ganancia != '0') {
            $gana = $ganancia;
            //precio unitario * ganancia
            $precioventa1 = ($gana * $data['precio_venta']) / 100;
            $precioventa2 = $precioventa1 + $data['precio_venta'];
            $productos_compra = array(
                "precio_compra" => $data['precio_venta'],
                "precio_venta" => $precioventa2,
            );
            $this->connection->where('id', $id_producto);
            $this->connection->update("producto", $productos_compra);
        }
        if ($ganancia == '') {
            $productos_compra = array(
                "precio_compra" => $data['precio_venta'],
            );
            $this->connection->where('id', $id_producto);
            $this->connection->update("producto", $productos_compra);
        }

        $razon = 'E';

        $signo = '';

        $unidades = $unidades_actual + $data['unidades'];

        $this->connection->where('almacen_id', $data['almacen'])->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades));

        $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $data['almacen'], 'fecha' => date('Y-m-d'), 'unidad' => $signo . $data['unidades'], 'precio' => $data['precio_venta'], 'cod_documento' => $data['id_compra'], 'usuario' => $user_id, 'razon' => $razon));

        $value1['id_inventario'] = $id;
        $value1['codigo_barra'] = $codigo_producto;
        $value1['cantidad'] = $data['unidades'];
        $value1['precio_compra'] = $data['precio_venta'];
        $value1['precio_venta_p'] = $precio_venta_p;
        $value1['precio_venta_actual'] = $precio_venta_actual;
        $value1['existencias'] = $unidades_actual;
        $value1['nombre'] = $nombre_producto;
        $value1['producto_id'] = $id_producto;
        $value1['total_inventario'] = ($data['precio_venta'] * $data['unidades']);

        $data_detalles[] = $value1;

        $this->connection->insert_batch("movimiento_detalle", $data_detalles);

    }

    public function verificar_afectar_inventario($data, $id)
    {
        //verificar si en la tabla movimiento_detalle existe el registro

        $this->connection->select("*");
        $this->connection->from("movimiento_detalle");
        $this->connection->where('id_inventario', $id);
        $this->connection->where('producto_id', $data['producto_id']);
        $result = $this->connection->get();

        if ($result->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public function eliminar_producto($id)
    {

        $this->connection->where('id', $id);

        $this->connection->delete("detalle_orden_compra");

    }

    public function delete($id)
    {

        $this->connection->where('id', $id);

        $this->connection->delete("ventas");

    }

    public function eliminar_orden_compra($id)
    {
        $this->connection->where('id', $id);
        $data = array(
            "estado" => -1,
        );
        //$this->connection->delete("orden_compra");
        $this->connection->update("orden_compra", $data);
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

            $this->connection->where('id', $no_factura_row->id)->update('almacen', array('consecutivo' => $factura_int));

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

                    'venta_id' => $id

                    , 'codigo_producto' => $value['codigo']

                    , 'precio_venta' => $value['precio_venta']

                    , 'unidades' => $value['unidades']

                    , 'nombre_producto' => $value['nombre_producto']

                    , 'impuesto' => $value['impuesto']

                    , 'descuento' => $value['descuento']

                    , 'margen_utilidad' => '777',

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

            print_r($e);die;

        }
    }

    public function anular($data)
    {

        try {
            $this->connection->trans_begin();
            // $venta = $this->connection->query("select * from venta where id = {$data['id']}")->row();
            $this->connection->where('id', $data['id']);
            // $this->connection->update("orden_compra", array('estado' => '-1'));
            $this->connection->update("orden_compra", $data);
            /*

            $this->connection->insert('ventas_anuladas', array('venta_id' =>$data['id'], 'usuario_id' => $data['usuario'], 'fecha' => date('Y-m-d H:i:s'), 'motivo' => $data['motivo']));

            $detalles_venta = $this->connection->query("SELECT * FROM `detalle_venta` where venta_id = {$data['id']}")->result();

            foreach ($detalles_venta as $value) {

            $producto = $this->connection->query("select * from producto where codigo = '{$value->codigo_producto}'")->row();

            $query_stock = "select * from stock_actual where almacen_id =".$venta->almacen_id." and producto_id=".$producto->id;

            $almacen = $this->connection->query($query_stock)->row();

            $unidades_actual = $almacen->unidades;

            $unidades = $unidades_actual + $value->unidades;

            $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $producto->id)->update('stock_actual', array('unidades' => $unidades));

            $this->connection->insert('stock_diario', array('producto_id' =>$producto->id, 'almacen_id' => $venta->almacen_id, 'fecha' => date('Y-m-d'), 'unidad' => $value->unidades, 'precio' => $value->precio_venta, 'cod_documento' => $venta->factura,'usuario' => $data['usuario'], 'razon' => 'E'));

            }

             */

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

    public function actualizarTabla($db)
    {
        $sql = "SHOW COLUMNS FROM $db.orden_compra LIKE 'nota'";
        $existe = $this->connection->query($sql);
        if ($existe->num_rows == 0) {
            $sql = "ALTER TABLE `orden_compra`
                        ADD COLUMN `nota` TEXT NOT NULL AFTER `fecha_vencimiento`;";
            $this->connection->query($sql);
        }
    }

    public function actualizarCantidad()
    {

        $sql = "SHOW COLUMNS FROM pago_orden_compra LIKE 'cantidad'";
        $existe = $this->connection->query($sql);
        if ($existe->num_rows > 0) {
            $sql = "ALTER TABLE pago_orden_compra MODIFY COLUMN cantidad DOUBLE;";
            $this->connection->query($sql);
        }
    }

    public function camposanulaciones()
    {
        //ingresar columna
        $sql = "SHOW COLUMNS FROM orden_compra LIKE 'motivo'";
        $existe = $this->connection->query($sql);
        if ($existe->num_rows == 0) {
            $sql = "ALTER TABLE `orden_compra`
                            ADD COLUMN `motivo` TEXT DEFAULT NULL,
                            ADD COLUMN `id_user_anulacion` INT(11) NULL,
                            ADD COLUMN `fecha_anulacion` DATETIME DEFAULT NULL;";
            $this->connection->query($sql);

        }
    }

    /**
     * agrega unos campos a las tablas de detalle_orden_compra y movimiento_detalle (
     * precio_venta_p = que guarda el precio del producto sin impuesto colocado en la orden de compra por defecto son null,
     * precio_venta_actual = que guarda el precio del producto sin impuesto que tien actualmente por defecto son null)
     * @author dairinet Avila
     * @return  [type]  [return description]
     */

    public function campos_precios_venta_orden()
    {
        //ingresar columna
        $sql = "SHOW COLUMNS FROM detalle_orden_compra LIKE 'precio_venta_p'";
        $existe = $this->connection->query($sql);
        if ($existe->num_rows == 0) {
            $sql = "ALTER TABLE `detalle_orden_compra`
                            ADD COLUMN `precio_venta_p` double NULL COMMENT 'precio de venta sin impuesto en orden de compra para cambiar',
                            ADD COLUMN `precio_venta_actual` double  NULL COMMENT 'precio de venta sin impuesto del producto actual';";
            $this->connection->query($sql);
        }
        $sql = "SHOW COLUMNS FROM movimiento_detalle LIKE 'precio_venta_p'";
        $existe = $this->connection->query($sql);
        if ($existe->num_rows == 0) {
            $sql = "ALTER TABLE `movimiento_detalle`
                            ADD COLUMN `precio_venta_p` double NULL COMMENT 'precio de venta sin impuesto en orden de compra para cambiar',
                            ADD COLUMN `precio_venta_actual` double  NULL COMMENT 'precio de venta sin impuesto del producto actual';";
            $this->connection->query($sql);
        }
    }

    public function obtener_productos($id_orden = 0)
    {
        $where = "";
        if (isset($_GET["sSearch"])) {
            $search = $_GET["sSearch"];
            $where = "AND d.nombre_producto like '%" . $search . "%'";
        }

        $sql = "SELECT  d.nombre_producto, d.codigo_producto, d.precio_venta, d.descuento, d.impuesto, d.unidades, d.producto_id, o.almacen_id FROM orden_compra o INNER JOIN `detalle_orden_compra` d ON o.`id` = d.`venta_id` WHERE o.id = '" . $id_orden . "' AND d.`unidades` > 0 $where";
        $result = $this->connection->query($sql)->result_array();

        foreach ($result as $value) {
            if ((isset($value['producto_id'])) && (!empty($value['producto_id']))) {
                $sql2 = "SELECT unidades AS unidades_stock FROM stock_actual WHERE producto_id=" . $value['producto_id'] . " AND almacen_id=" . $value['almacen_id'];
                $result2 = $this->connection->query($sql2)->row();
                $productos[] = array(
                    $value["nombre_producto"],
                    $value["codigo_producto"],
                    $value["precio_venta"],
                    $value["descuento"],
                    $value["impuesto"],
                    $value["unidades"],
                    $value["producto_id"],
                    $result2->unidades_stock,
                );
            }
        }

        return $productos;
    }

    public function actualizar_orden_compra($id_orden, $id_producto, $unidades)
    {

        $where = array(
            "venta_id" => $id_orden,
            "producto_id" => $id_producto,
        );

        $this->connection->select("unidades");
        $this->connection->from("detalle_orden_compra");
        $this->connection->where($where);
        $result = $this->connection->get();
        $unidades_actuales = $result->result_array()[0]["unidades"];

        $data = array(
            "unidades" => $unidades_actuales - $unidades,
        );

        $this->connection->where($where);
        $this->connection->update("detalle_orden_compra", $data);
        if ($this->connection->affected_rows() > 0) {
            return 1;
        } else {
            return null;
        }
    }

    public function get_orden_compra($data)
    {
        $this->connection->select("*");
        $this->connection->from("detalle_orden_compra");
        $this->connection->where("venta_id", $data["id"]);
        $result = $this->connection->get();
        return $result->result_array();
    }

}

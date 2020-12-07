<?php
//2015/12/18

class Ventas_separe_model extends CI_Model
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

    public function getAjaxFacturas()
    {
        $sql = "
            SELECT separe.*, cl.nombre_comercial, cl.nif_cif, al.nombre AS almacen_nombre
            FROM plan_separe_factura AS separe
            INNER JOIN clientes AS cl ON separe.cliente_id = cl.id_cliente
            INNER JOIN almacen AS al ON separe.almacen_id = al.id
            WHERE estado <> '3'
            AND estado <> '4'
        ";
        $query = $this->connection->query($sql);
        $data = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $user = $this->db->get_where('users', array('id' => $data[$i]["usuario_id"]), 1);
            if (isset($user->result()[0]->username)) {
                $data[$i]["usuario_id"] = $user->result()[0]->username;
            } else {
                $data[$i]["usuario_id"] = "No encontrado";
            }
        }

        return $data;
    }

    //Cuando añadimos en plan separe
    public function add($data)
    {
        try {
            $this->connection->trans_begin();

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

                    /*
                    if ($opcnum == 'no') {
                    $num_factura = 0;
                    $this->connection->where('id', $no_factura_row->id)->update('almacen', array('consecutivo' => $factura_int));
                    $num_factura = $no_factura_row->prefijo . $factura_int;
                    }

                    if ($opcnum == 'si') {
                    $num_factura = 0;
                    $numero_factura++;
                    $this->connection->where('id', '26')->update('opciones', array('valor_opcion' => $numero_factura));
                    //                        $num_factura = $prefijo_factura . $numero_factura;
                    }
                     */

                    //all Obj to string
                    $jsonString = json_encode($data);

                    $array_datos = array(
                        "fecha" => $data['fecha'],
                        "fecha_vencimiento" => $data['fecha_vencimiento'],
                        "usuario_id" => $data['usuario'],
                        "almacen_id" => $no_factura_row->id,
                        "total_venta" => $data['total_venta'],
                        "cliente_id" => $id_cliente,
                        "tipo_factura" => $data['tipo_factura'],
                        "factura" => '-',
                        "nota" => $jsonString,
                    );

                    if (!empty($data['vendedor'])) {

                        $array_datos["vendedor"] = $data['vendedor'];
                    }

                    $this->connection->insert("plan_separe_factura", $array_datos);

                    $id = $this->connection->insert_id();
                    /*
                    if ($data['nota'] != '') {
                    $this->connection->where('id', $id);
                    $this->connection->set('nota', $data['nota']);
                    $this->connection->update('plan_separe_factura');
                    }
                     */
                    $data_detalles = array();

                    $query_stock = "";

                    foreach ($data['productos'] as $value) {

                        $unidades_compra = $value['unidades'];
                        $product_id = $value['product_id'];
                        $comp = 0;

                        if ($product_id != '') {
                            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
                            foreach ($prod as $dat) {
                                $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                            }
                        }
                        $compra_final_1 = $value['precio_venta'] - $comp;
                        $compra_final_2 = $compra_final_1 * $value['unidades'];
                        $compra_final_3 = $value['descuento'] * $value['unidades'];

                        if (empty($value['descripcion'])) {
                            $value['descripcion'] = '';
                        }

                        $data_detalles[] = array(
                            'venta_id' => $id
                            , 'codigo_producto' => $value['codigo']
                            , 'precio_venta' => $value['precio_venta']
                            , 'unidades' => $value['unidades']
                            , 'nombre_producto' => $value['nombre_producto']
                            , 'descripcion_producto' => $value['descripcion']
                            , 'impuesto' => $value['impuesto']
                            , 'descuento' => $value['descuento']
                            , 'producto_id' => $product_id
                            , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
                        );

                        if ($product_id != '') {
                            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value['product_id'];

                            $almacen = $this->connection->query($query_stock)->row();

                            $unidades_actual = $almacen->unidades;

                            $unidades = $unidades_actual - $value['unidades'];

                            $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value['product_id'])->update('stock_actual', array('unidades' => $unidades));
                            // $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $value['product_id'], 'almacen_id' => $no_factura_row->id, 'fecha' => date('Y-m-d'), 'unidad' => '-' . $value['unidades'], 'precio' => $value['precio_venta'], 'cod_documento' => $num_factura, 'usuario' => $data['usuario'], 'razon' => 'S'));

                            /* Ingredientes =================================================== */
                            $query_producto = "select * from producto where id =" . $value['product_id'];
                            $producto = $this->connection->query($query_producto)->row();

                            if ($producto->ingredientes == 1) {

                                //Ingredientes del producto
                                $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['product_id'];
                                $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                                foreach ($ingredientes_producto->result() as $key => $value) {
                                    //Stock del ingrediente
                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_ingrediente;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = $almacen->unidades;
                                    $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                                    //Actualizar stock
                                    //$this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                                    //Insertar stock diario
                                    /*
                                $this->connection->insert(
                                'stock_diario', array(
                                'producto_id' => $value->id_ingrediente,
                                'almacen_id' => $no_factura_row->id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                'precio' => 0,
                                'cod_documento' => $num_factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'S'
                                )
                                );

                                 */
                                }
                            }

                            if ($producto->combo == 1) {
                                //productos del combo
                                $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
                                $productos_combo = $this->connection->query($query_productos_combo);

                                foreach ($productos_combo->result() as $key => $value) {

                                    //Stock del ingrediente
                                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_producto;
                                    $almacen = $this->connection->query($query_stock)->row();
                                    $unidades_actual = $almacen->unidades;
                                    $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                                    //Actualizar stock
                                    //$this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_producto)->update('stock_actual', array('unidades' => $unidades));
                                    //Insertar stock diario
                                    /*

                                $this->connection->insert(
                                'stock_diario', array(
                                'producto_id' => $value->id_producto,
                                'almacen_id' => $no_factura_row->id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                'precio' => 0,
                                'cod_documento' => $num_factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'S'
                                )
                                );

                                 */
                                }
                            }

                            /* ....................................... */
                        }
                    }

                    $this->connection->insert_batch("plan_separe_detalle", $data_detalles);

                    $data['pago']['id_venta'] = $id;

                    $fecha = date('Y-m-d H:i:s');

                    $data['pago']['fecha'] = $fecha;

                    $this->connection->insert('plan_separe_pagos', $data['pago']);
                    $id_plan_separe_pagos = $this->connection->insert_id();

                    if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                        $username = $this->session->userdata('username');
                        $db_config_id = $this->session->userdata('db_config_id');

                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                        foreach ($user as $dat) {
                            $id_user = $dat->id;
                        }
                        if ($data['pago']['forma_pago'] != '') {
                            $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_venta',
                                "valor" => ($data['pago']['valor_entregado']),
                                "forma_pago" => $data['pago']['forma_pago'],
                                "numero" => '',
                                "id_mov_tip" => $id_plan_separe_pagos,
                                "tabla_mov" => "plan_separe_pagos",
                            );

                            $this->connection->insert('movimientos_cierre_caja', $array_datos);
                        }

                    }
                }

                if ($data['sobrecostos'] != '' and $sobrecostosino == 'si') {

                    $this->connection->insert(
                        'plan_separe_detalle', array(
                            'venta_id' => $id,
                            'nombre_producto' => 'PROPINA',
                            'unidades' => '1',
                            'descripcion_producto' => $data['sobrecostos'],
                            'precio_venta' => (($data['sobrecostos'] * $data['total_venta']) / 100),
                        )
                    );
                }

                //if ($data["id_fact_espera"] != '')
                //$this->connection->query("delete from  factura_espera where id = '" . $data["id_fact_espera"] . "' ");
            }

            if ($this->connection->trans_status() === false) {

                $this->connection->trans_rollback();
            } else {

                $this->connection->trans_commit();
            }

            //================================================
            //  Quitamos las unidades del stock actual
            //================================================

            /*
            $sqlStock = "
            SELECT det.producto_id, det.unidades, fac.almacen_id
            FROM plan_separe_detalle AS det
            INNER JOIN plan_separe_factura AS fac ON det.venta_id = fac.id
            WHERE fac.id = $id
            ";

            $resultStock = $this->connection->query( $sqlStock )->result();

            foreach( $resultStock as $val){

            $productoId = $val->producto_id;
            $unidades = $val->unidades;
            $almacenId = $val->almacen_id;
            if($productoId != ''){
            $sqlStock = "
            SELECT unidades FROM stock_actual WHERE producto_id = $productoId AND almacen_id = $almacenId
            ";
            $unidadesActuales = $this->connection->query( $sqlStock )->row()->unidades;

            $nuevoStock = intval($unidadesActuales) - intval($val->unidades);

            $sqlStock = "
            UPDATE stock_actual SET unidades = $nuevoStock WHERE producto_id = $productoId AND almacen_id = $almacenId
            ";
            $this->connection->query( $sqlStock );
            }

            }
             */
            //--------------------------------------------

            //================================================

            return $id;
        } catch (Exception $e) {

            // $this->connection->trans_rollback();

            print_r($e);
            die;
        }

        /* DECREMENTAR SOTCK */
    }

    //==========================================
    //   ADD ORIGINAL
    //==========================================

    public function setFacturar($id)
    {

        //--------------------------------------------
        // DEVOLVEMOS las unidades al stock
        //--------------------------------------------

        $id_plan_separe = $id;

        $sqlStock = "
            SELECT det.producto_id, det.unidades, fac.almacen_id
            FROM plan_separe_detalle AS det
            INNER JOIN plan_separe_factura AS fac ON det.venta_id = fac.id
            WHERE fac.id = $id
        ";

        $resultStock = $this->connection->query($sqlStock)->result();

        foreach ($resultStock as $val) {

            $productoId = $val->producto_id;
            $unidadesdes = $val->unidades;
            $almacenId = $val->almacen_id;

            $productoId = $val->producto_id;
            $unidadesdes = $val->unidades;
            $almacenId = $val->almacen_id;
            if ($productoId != '') {
                $query_stock = "select * from stock_actual where almacen_id =" . $almacenId . " and producto_id=" . $productoId;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual + $unidadesdes;
                $this->connection->where('almacen_id', $almacenId)->where('producto_id', $productoId)->update('stock_actual', array('unidades' => $unidades));
                /* Ingredientes =================================================== */
                $query_producto = "select * from producto where id =" . $productoId;
                $producto = $this->connection->query($query_producto)->row();

                if ($producto->ingredientes == 1) {

                    //Ingredientes del producto
                    $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $productoId;
                    $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                    foreach ($ingredientes_producto->result() as $key => $value) {
                        foreach ($ingredientes_producto->result() as $key => $value) {
                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $almacenId . " and producto_id=" . $value->id_ingrediente;
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $almacenId)->where('producto_id', $value->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));}

                    }

                    if ($producto->combo == 1) {
                        //productos del combo
                        $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $productoId;
                        $productos_combo = $this->connection->query($query_productos_combo);

                        foreach ($productos_combo->result() as $key => $value) {
                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $almacenId . " and producto_id=" . $value->id_producto;
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $almacenId)->where('producto_id', $value->id_producto)->update('stock_actual', array('unidades' => $unidades));}
                    }
                }
            }
        }

        //--------------------------------------------

        // CAMBIAMOS EL ESTADO DE LA FACTURA A 2 = YA PAGO
        $query = "UPDATE plan_separe_factura SET estado = 2 WHERE id = $id ";
        $this->connection->query($query);

        // Capturamos el string del objeto
        $query = "SELECT nota FROM plan_separe_factura WHERE id = $id ";
        $jsonString = $this->connection->query($query)->row()->nota;

        //fecha de la factura
        $query = "SELECT fecha FROM plan_separe_factura WHERE id = $id ";
        $fechaFactura = $this->connection->query($query)->row()->fecha;

        // String json to OBJ
        $data = json_decode($jsonString, true);

        if ($data['cliente'] == "") {
            $id_cliente = -1;
        } else {
            $id_cliente = $data['cliente'];
        }

        $array_cliente = array();
        $numero_factura = 0;
        $prefijo_factura = 0;
        $sobrecostosino = 0;
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
            "fecha" => date("Y-m-d H:i:s"),
            "fecha_vencimiento" => $data['fecha_vencimiento'],
            "usuario_id" => $data['usuario'],
            "factura" => $num_factura,
            "almacen_id" => $no_factura_row->id,
            "total_venta" => $data['total_venta'],
            "cliente_id" => $id_cliente,
            "tipo_factura" => $data['tipo_factura'],
            "activo" => '0',
        );

        $plannumfa = "UPDATE plan_separe_factura SET factura = '" . $num_factura . "' WHERE id = $id ";
        $this->connection->query($plannumfa);

        if (!empty($data['vendedor'])) {

            $array_datos["vendedor"] = $data['vendedor'];
        }

        $this->connection->insert("venta", $array_datos);

        $idventa = $this->connection->insert_id();

        $planid_venta = "UPDATE plan_separe_factura SET venta_id = '" . $idventa . "' WHERE id = $id ";
        $this->connection->query($planid_venta);

        $data_detalles = array();

        $query_stock = "";

        foreach ($data['productos'] as $value) {

            $unidades_compra = $value['unidades'];
            $product_id = $value['product_id'];
            $comp = 0;
            if ($product_id != '') {
                $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();

                foreach ($prod as $dat) {
                    $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
                }
            }
            $compra_final_1 = $value['precio_venta'] - $comp;
            $compra_final_2 = $compra_final_1 * $value['unidades'];
            $compra_final_3 = $value['descuento'] * $value['unidades'];

            if (empty($value['descripcion'])) {
                $value['descripcion'] = '';
            }

            $data_detalles[] = array(
                'venta_id' => $idventa
                , 'codigo_producto' => $value['codigo']
                , 'precio_venta' => $value['precio_venta']
                , 'unidades' => $value['unidades']
                , 'nombre_producto' => $value['nombre_producto']
                , 'descripcion_producto' => $value['descripcion']
                , 'impuesto' => $value['impuesto']
                , 'descuento' => $value['descuento']
                , 'producto_id' => $product_id
                , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            );

            if ($value['product_id'] != '') {
                $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value['product_id'];

                $almacen = $this->connection->query($query_stock)->row();

                $unidades_actual = $almacen->unidades;

                $unidades = $unidades_actual - $value['unidades'];

                $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value['product_id'])->update('stock_actual', array('unidades' => $unidades));

                $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $value['product_id'], 'almacen_id' => $no_factura_row->id, 'fecha' => date('Y-m-d'), 'unidad' => '-' . $value['unidades'], 'precio' => $value['precio_venta'], 'cod_documento' => $num_factura, 'usuario' => $data['usuario'], 'razon' => 'S'));

                /* Ingredientes =================================================== */
                $query_producto = "select * from producto where id =" . $value['product_id'];
                $producto = $this->connection->query($query_producto)->row();

                if ($producto->ingredientes == 1) {

                    //Ingredientes del producto
                    $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['product_id'];
                    $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                    foreach ($ingredientes_producto->result() as $key => $value) {
                        //Stock del ingrediente
                        $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_ingrediente;
                        $almacen = $this->connection->query($query_stock)->row();
                        $unidades_actual = $almacen->unidades;
                        $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                        //Actualizar stock
                        $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                        //Insertar stock diario
                        $this->connection->insert(
                            'stock_diario', array(
                                'producto_id' => $value->id_ingrediente,
                                'almacen_id' => $no_factura_row->id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                'precio' => 0,
                                'cod_documento' => $num_factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'S',
                            )
                        );
                    }
                }

                if ($producto->combo == 1) {
                    //productos del combo
                    $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
                    $productos_combo = $this->connection->query($query_productos_combo);

                    foreach ($productos_combo->result() as $key => $value) {
                        //Stock del ingrediente
                        $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $value->id_producto;
                        $almacen = $this->connection->query($query_stock)->row();
                        $unidades_actual = $almacen->unidades;
                        $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                        //Actualizar stock
                        $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $value->id_producto)->update('stock_actual', array('unidades' => $unidades));
                        //Insertar stock diario
                        $this->connection->insert(
                            'stock_diario', array(
                                'producto_id' => $value->id_producto,
                                'almacen_id' => $no_factura_row->id,
                                'fecha' => date('Y-m-d'),
                                'unidad' => '-' . ($value->cantidad * $unidades_compra),
                                'precio' => 0,
                                'cod_documento' => $num_factura,
                                'usuario' => $data['usuario'],
                                'razon' => 'S',
                            )
                        );
                    }
                }

            }
            /* ....................................... */
        }

        $this->connection->insert_batch("detalle_venta", $data_detalles);

        $this->connection->select("*");
        $this->connection->from("plan_separe_pagos");
        $this->connection->where("id_venta", $id_plan_separe);
        $result = $this->connection->get();

        foreach ($result->result_array() as $value) {
            $data_payment = array(
                "valor_entregado" => $value["valor_entregado"],
                'id_venta' => $idventa,
                "forma_pago" => $value["forma_pago"],
                "cambio" => 0,
            );
            $this->connection->insert('ventas_pago', $data_payment);
        }

        if ($valor_caja == 'si' && $this->session->userdata('caja') > 0 && (!empty($id))) {
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();

            foreach ($user as $dat) {
                $id_user = $dat->id;
            }
            if ($data['pago']['forma_pago'] != '') {
                $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                    "hora_movimiento" => date('H:i:s'),
                    "id_usuario" => $id_user,
                    "tipo_movimiento" => 'entrada_venta',
                    "valor" => '0',
                    "forma_pago" => 'efectivo',
                    "numero" => $num_factura,
                    "id_mov_tip" => $idventa,
                    "tabla_mov" => "venta",
                );

                $this->connection->insert('movimientos_cierre_caja', $array_datos);
            }

        }

        if ($data['sobrecostos'] != '' and $sobrecostosino == 'si') {

            $this->connection->insert(
                'detalle_venta', array(
                    'venta_id' => $idventa,
                    'nombre_producto' => 'PROPINA',
                    'unidades' => '1',
                    'descripcion_producto' => $data['sobrecostos'],
                    'precio_venta' => (($data['sobrecostos'] * $data['total_venta']) / 100),
                )
            );
        }

        return $idventa;

        /* DECREMENTAR SOTCK */
    }

    public function get_separe_by_id($id = 0)
    {

        $query = $this->connection->query("SELECT plan_separe_factura.id as id_venta, plan_separe_factura.*, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.telefono as cliente_telefono, clientes.email as cliente_email ,clientes.direccion as cliente_direccion , clientes.movil as cliente_movil,  clientes.provincia as cliente_provincia ,almacen.* FROM  plan_separe_factura inner join almacen on almacen.id = plan_separe_factura.almacen_id left join clientes on id_cliente = plan_separe_factura.cliente_id left join vendedor on vendedor.id = plan_separe_factura.vendedor WHERE plan_separe_factura.id = '" . $id . "'");
        return $query->row_array();
    }

    public function get_separe_detalle_venta($id)
    {

        return $this->connection->query("SELECT * FROM plan_separe_detalle WHERE venta_id = '" . $id . "'")->result();
    }

    public function get_separe_detalles_pago($id = 0)
    {

        $query = $this->connection->query("SELECT * FROM plan_separe_pagos WHERE id_venta = '" . $id . "'");
        return $query->row_array();
    }

    //-------

    public function get_pagos_tipos_pago()
    {

        $this->db->select('valor_opcion, mostrar_opcion');

        $query = $this->db->get_where('opciones', array('nombre_opcion' => 'tipo_pago'));

        $result = array();

        foreach ($query->result() as $value) {

            $result[$value->valor_opcion] = $value->mostrar_opcion;
        }

        return $result;
    }

    public function get_pagos_total($id_factura)
    {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM plan_separe_pagos where id_venta = $id_factura");

        return $query->row()->cantidad;
    }

    public function get_pagos_all($id_factura, $offset)
    {

        $query = $this->connection->query("SELECT *, nombre as forma_pago from plan_separe_pagos as p inner join forma_pago AS f on f.codigo = p.forma_pago  where id_venta = $id_factura order by id_pago DESC");

        return $query->result();
    }

    public function get_ventas_by_id($id = 0)
    {

        $query = $this->connection->query("SELECT plan_separe_factura.id as id_venta, plan_separe_factura.*, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.telefono as cliente_telefono, clientes.email as cliente_email ,clientes.direccion as cliente_direccion , clientes.movil as cliente_movil,  clientes.provincia as cliente_provincia ,almacen.* FROM  plan_separe_factura inner join almacen on almacen.id = plan_separe_factura.almacen_id left join clientes on id_cliente = plan_separe_factura.cliente_id left join vendedor on vendedor.id = plan_separe_factura.vendedor WHERE plan_separe_factura.id = '" . $id . "'");

        return $query->row_array();
    }

    public function get_estado_factura($id = 0)
    {

        $query = $this->connection->query("SELECT estado from plan_separe_factura WHERE id = '" . $id . "'");
        return $query->row()->estado;
    }

    public function addPago()
    {

        $strFecha = $this->input->post('fecha_pago');

        $fecha = date($strFecha . ' H:i:s');

        $array_datos = array(
            "fecha" => $fecha,
            "forma_pago" => $this->input->post('tipo'),
            "valor_entregado" => $this->input->post('cantidad'),
            "id_venta" => $this->input->post('id_factura'),
        );

        $this->connection->insert("plan_separe_pagos", $array_datos);

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

                $array_datos = array(
                    "Id_cierre" => $this->session->userdata('caja'),
                    "hora_movimiento" => date('H:i:s'),
                    "id_usuario" => $id_user,
                    "tipo_movimiento" => 'entrada_venta',
                    "valor" => ($this->input->post('cantidad')),
                    "forma_pago" => $this->input->post('tipo'),
                    "numero" => '',
                    "id_mov_tip" => $id,
                    "tabla_mov" => "plan_separe_pagos",
                );
                $this->connection->insert('movimientos_cierre_caja', $array_datos);
            }
        }
    }

    //---------------------------

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

        $aColumns = array('factura', 'nif_cif', 'nombre_comercial', 'fecha', 'total_venta', 'nombre', 'tipo_factura');

        $sIndexColumn = "id";

        $sTable = "venta";

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

        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . ", venta.id as id_venta

        FROM   $sTable left join clientes on clientes.id_cliente = $sTable.cliente_id inner join almacen a on almacen_id = a.id

        $sWhere

        $sOrder

        $sLimit

            ";

        // echo $sQuery;
        // die;

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "

                    SELECT FOUND_ROWS() as cantidad

            ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "

        SELECT COUNT(`" . $sIndexColumn . "`) as cantidad

        FROM   $sTable

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

        $aColumns[7] = 'id_venta';
        $aColumns[6] = 'tipo_factura';

        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {

                $data[] = $row[$aColumns[$i]];
            }

            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_ajax_data_anuladas($estado = 0)
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
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sWhere = " WHERE estado = '$estado'";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $sWhere = " WHERE estado = '$estado' and almacen_id = '$almacen'";
        }

        $sql = "SELECT factura, (SELECT motivo FROM ventas_anuladas where venta_id = venta.id limit 1) as motibo, nombre_comercial, fecha, total_venta, nombre, venta.id as venta_id  FROM venta  inner join clientes on clientes.id_cliente = venta.cliente_id inner join almacen a on almacen_id = a.id $sWhere order by venta_id desc ";

        $data = array();
        //echo $sql;
        foreach ($this->connection->query($sql)->result() as $value) {

            $data[] = array(
                $value->factura,
                $value->motibo,
                $value->nombre_comercial,
                $value->fecha,
                number_format($value->total_venta),
                $value->nombre,
                $value->venta_id,
            );
        }
        return array(
            'aaData' => $data,
        );
    }

    public function get_by_id($id = 0)
    {

        $query = $this->connection->query("SELECT venta.id as id_venta, venta.*, vendedor.nombre as vendedor, clientes.nombre_comercial, clientes.email, clientes.nif_cif, clientes.telefono as cliente_telefono, clientes.email as cliente_email ,clientes.direccion as cliente_direccion , clientes.movil as cliente_movil,  clientes.provincia as cliente_provincia ,almacen.* FROM  plan_separe_factura as venta inner join almacen on almacen.id = venta.almacen_id left join clientes on id_cliente = venta.cliente_id left join vendedor on vendedor.id = venta.vendedor WHERE venta.id = '" . $id . "'");

        return $query->row_array();
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
                detalle_venta.precio_venta,descuento,detalle_venta.impuesto,linea,margen_utilidad,detalle_venta.activo,
				(select nombre_impuesto from impuesto where impuesto.porciento = detalle_venta.impuesto limit 1) as des_impuesto

            FROM
            plan_separe_detalle AS detalle_venta
            where
            venta_id = '" . $id . "'"
        );

        return $query->result_array();
    }

    public function get_all($id_factura, $offset)
    {
        $query = $this->connection->query("SELECT * from plan_separe_pagos where id_venta = $id_factura order by id_pago DESC");
        return $query->result();
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
            venta_id = '" . $id . "'"
        );

        return $query->result_array();
    }

    public function get_detalles_pago($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM ventas_pago WHERE id_venta = '" . $id . "'");
        return $query->row_array();
    }

    public function get_detalles_pago_result($id = 0)
    {
        $query = "SELECT * FROM ventas_pago WHERE id_venta = '" . $id . "'";
        $query_result = $this->connection->query($query)->result();
        return $query_result;
    }

    public function espera($data, $tipo = null)
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
                $total = "SELECT no_factura FROM factura_espera where id = '-1' and activo = '1'   ";
                $total_numero = $this->connection->query($total)->row();
                $total_numero = $total_numero1->no_factura;
            } else {
                $total = "SELECT no_factura FROM factura_espera where usuario_id = '" . $usuario . "' and activo = '1'    order by id desc ";
                $total_numero = $this->connection->query($total)->row();
                $total_numero = $total_numero->no_factura + 1;
            }

            $array_datos = array(
                "fecha" => $data['fecha'],
                "fecha_vencimiento" => $data['fecha_vencimiento'],
                "usuario_id" => $data['usuario'],
                "factura" => 'Venta # ' . $total_numero,
                "no_factura" => $total_numero,
                "almacen_id" => '',
                "total_venta" => $data['total_venta'],
                "cliente_id" => $id_cliente,
                "tipo_factura" => $data['tipo_factura'],
                "activo" => $data['activo'],
            );

            $this->connection->insert("factura_espera", $array_datos);

            $id = $this->connection->insert_id();

            if ($data['nota'] != '') {
                //  $this->connection->where('id', $id);
                //   $this->connection->set('nota', $data["nota"]);
                //   $this->connection->update('factura_espera');
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
                    'venta_id' => $id
                    , 'id_producto' => $value['product_id']
                    , 'codigo_producto' => $value['codigo']
                    , 'precio_venta' => $value['precio_venta']
                    , 'unidades' => $value['unidades']
                    , 'nombre_producto' => $value['nombre_producto']
                    , 'descripcion_producto' => $value['descripcion']
                    , 'impuesto' => $value['impuesto']
                    , 'descuento' => $value['descuento']
                    , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
                );

                /* ....................................... */
            }

            $this->connection->insert_batch("detalle_factura_espera", $data_detalles);
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
                    'venta_id' => $id
                    , 'id_producto' => $value['product_id']
                    , 'codigo_producto' => $value['codigo']
                    , 'precio_venta' => $value['precio_venta']
                    , 'unidades' => $value['unidades']
                    , 'nombre_producto' => $value['nombre_producto']
                    , 'descripcion_producto' => $value['descripcion']
                    , 'impuesto' => $value['impuesto']
                    , 'descuento' => $value['descuento']
                    , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
                );

                /* ....................................... */
            }

            $this->connection->insert_batch("detalle_factura_espera", $data_detalles);
        }

        return $id;
    }

    public function get_all_espera($id = 0)
    {

        $query = $this->connection->query("SELECT * FROM factura_espera ");
        return $query->result();
    }

    public function delete($id = 0, $dev)
    {
        //validar si tiene una factura asociada
        $factura = $this->connection->get_where('plan_separe_factura', array('id' => $id))->row()->factura;
        if ($factura != "-") {
            return "No se puede anular el plan separe, ya esta sujeto a una factura";
        }
        // Capturamos el string del objeto
        $query = "SELECT nota FROM plan_separe_factura WHERE id = $id ";
        $jsonString = $this->connection->query($query)->row()->nota;

        $query = "SELECT almacen_id FROM plan_separe_factura WHERE id = $id ";
        $almacen_plan = $this->connection->query($query)->row()->almacen_id;

        // String json to OBJ
        $data = json_decode($jsonString, true);

        foreach ($data['productos'] as $value) {

            $unidades_compra = $value['unidades'];
            $product_id = $value['product_id'];

            $prod = $this->connection->query("SELECT * FROM `producto` where id = '{$product_id}'")->result();
            $comp = 0;
            foreach ($prod as $dat) {
                $comp = ($dat->precio_compra) ? $dat->precio_compra : 0;
            }

            $compra_final_1 = $value['precio_venta'] - $comp;
            $compra_final_2 = $compra_final_1 * $value['unidades'];
            $compra_final_3 = $value['descuento'] * $value['unidades'];

            if (empty($value['descripcion'])) {
                $value['descripcion'] = '';
            }

            $data_detalles[] = array(
                'venta_id' => $id
                , 'codigo_producto' => $value['codigo']
                , 'precio_venta' => $value['precio_venta']
                , 'unidades' => $value['unidades']
                , 'nombre_producto' => $value['nombre_producto']
                , 'descripcion_producto' => $value['descripcion']
                , 'impuesto' => $value['impuesto']
                , 'descuento' => $value['descuento']
                , 'producto_id' => $product_id
                , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
            );

            $query_stock = "select * from stock_actual where almacen_id =" . $almacen_plan . " and producto_id=" . $value['product_id'];

            $almacen = $this->connection->query($query_stock)->row();

            $unidades_actual = $almacen->unidades;

            $unidades = $unidades_actual + $value['unidades'];

            $this->connection->where('almacen_id', $almacen_plan)->where('producto_id', $value['product_id'])->update('stock_actual', array('unidades' => $unidades));

            $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $value['product_id'], 'almacen_id' => $almacen_plan, 'fecha' => date('Y-m-d'), 'unidad' => $value['unidades'], 'precio' => $value['precio_venta'], 'cod_documento' => '', 'usuario' => $data['usuario'], 'razon' => 'E'));

            /* Ingredientes =================================================== */
            $query_producto = "select * from producto where id =" . $value['product_id'];
            $producto = $this->connection->query($query_producto)->row();

            if ($producto->ingredientes == 1) {

                //Ingredientes del producto
                $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $value['product_id'];
                $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                foreach ($ingredientes_producto->result() as $key => $value) {
                    //Stock del ingrediente
                    $query_stock = "select * from stock_actual where almacen_id =" . $almacen_plan . " and producto_id=" . $value->id_ingrediente;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                    //Actualizar stock
                    $this->connection->where('almacen_id', $almacen_plan)->where('producto_id', $value->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                    //Insertar stock diario
                    $this->connection->insert(
                        'stock_diario', array(
                            'producto_id' => $value->id_ingrediente,
                            'almacen_id' => $almacen_plan,
                            'fecha' => date('Y-m-d'),
                            'unidad' => ($value->cantidad * $unidades_compra),
                            'precio' => 0,
                            'cod_documento' => '',
                            'usuario' => $data['usuario'],
                            'razon' => 'E',
                        )
                    );
                }
            }

            if ($producto->combo == 1) {
                //productos del combo
                $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
                $productos_combo = $this->connection->query($query_productos_combo);

                foreach ($productos_combo->result() as $key => $value) {
                    //Stock del ingrediente
                    $query_stock = "select * from stock_actual where almacen_id =" . $almacen_plan . " and producto_id=" . $value->id_producto;
                    $almacen = $this->connection->query($query_stock)->row();
                    $unidades_actual = $almacen->unidades;
                    $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                    //Actualizar stock
                    $this->connection->where('almacen_id', $almacen_plan)->where('producto_id', $value->id_producto)->update('stock_actual', array('unidades' => $unidades));
                    //Insertar stock diario
                    $this->connection->insert(
                        'stock_diario', array(
                            'producto_id' => $value->id_producto,
                            'almacen_id' => $almacen_plan,
                            'fecha' => date('Y-m-d'),
                            'unidad' => ($value->cantidad * $unidades_compra),
                            'precio' => 0,
                            'cod_documento' => '',
                            'usuario' => $data['usuario'],
                            'razon' => 'E',
                        )
                    );
                }
            }

            /* ....................................... */
        }

        // Verificamos si el plan separe es devuelto dentro del cierre de caja

        // Si aun estamos dentro del cieere de caja, eliminamos los pagos, de lo contrario los pagos se reflejaran en el cierre de caja
        if ($this->session->userdata('caja') != "") {

            if ($dev == 'si') {

                // Cambiamos en movimiento_cierre_caja los pagos del plan separe como anulados
                $query = "SELECT id_pago FROM plan_separe_pagos  WHERE id_venta = $id";
                $result = $this->connection->query($query)->result();
                foreach ($result as $row) {
                    $idPago = $row->id_pago;
                    $querySub = "UPDATE movimientos_cierre_caja SET tipo_movimiento = 'anulada' WHERE tabla_mov = 'plan_separe_pagos' AND id_mov_tip = '$idPago' ";
                    $this->connection->query($querySub);
                }

                //$query = "DELETE FROM plan_separe_pagos  WHERE id_venta = $id";
                //$this->connection->query($query);
            }

        }

        $id_user_anulacion = $this->session->userdata('user_id');
        $fecha_actual = date('Y-m-d');
        // Estado 3 significa que fue cancelado dentro del cierre de caja
        // Estado 4 significa que fue cancelado fuera del cierre de caja
        // Si estamos dentro del cierre de caja asignamos estado 3, de lo contrario 4, donde se seguirá reflejando en el cierre de caja
        if ($this->session->userdata('caja') != "") {

            $query = "UPDATE plan_separe_factura SET estado = '3', id_user_anulacion = '" . $id_user_anulacion . "', fecha_anulacion = '" . $fecha_actual . "'  WHERE id = $id";
            $this->connection->query($query);

        } else {
            $query = "UPDATE plan_separe_factura SET estado = '4', id_user_anulacion = '" . $id_user_anulacion . "', fecha_anulacion = '" . $fecha_actual . "' WHERE id = $id";
            $this->connection->query($query);
        }
        return "Se ha anulado correctamente";
    }

    public function validateFields()
    {
        $sql = "SHOW COLUMNS FROM plan_separe_factura LIKE 'fecha_anulacion'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            $sql = "ALTER TABLE `plan_separe_factura`
            ADD COLUMN `id_user_anulacion` INT(10) NULL AFTER `nota`,
            ADD COLUMN `fecha_anulacion` DATE NULL AFTER `id_user_anulacion`;
        ";

            $this->connection->query($sql);
        }
    }

    public function get_all_espera_detalles($id = 0)
    {

        $query = $this->connection->query("
        SELECT detalle_factura_espera.*, factura_espera.factura, cliente_id as id_clientes ,(SELECT nombre_comercial FROM `clientes` WHERE id_cliente = cliente_id) as cli_nom
        FROM detalle_factura_espera
        inner join factura_espera on factura_espera.id = detalle_factura_espera.venta_id
        WHERE venta_id = '" . $id . "' ");

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return null;
    }

    public function get_all_espera_factura($id = 0)
    {
        $usuario = '';

        $usuario = $this->session->userdata('user_id');
        $query = $this->connection->query("SELECT * FROM factura_espera where activo = 1 and usuario_id = '" . $usuario . "'");

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

    //eliminar factura en espera
    public function eliminar_factura($nom = 0)
    {

        $usuario = '';

        $usuario = $this->session->userdata('user_id');

        $query = $this->connection->query("DELETE FROM factura_espera WHERE factura='" . $nom . "' AND usuario_id = '" . $usuario . "'");

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

    public function eliminar_producto_actualizar($venta, $id, $prod, $nom, $cant, $alm)
    {

        $product_id = 0;
        $nom = urldecode($nom);
        $nom = utf8_decode($nom);
        $query_producto_id = "select * from producto where codigo ='" . $prod . "' and nombre like CONVERT('%" . $nom . "%' USING utf8)";
        $producto_id = $this->connection->query($query_producto_id)->result();
        foreach ($producto_id as $value) {
            $product_id = $value->id;
        }

        $query_stock = "select * from stock_actual where almacen_id ='" . $alm . "' and producto_id=" . $product_id;

        $almacen = $this->connection->query($query_stock)->row();

        $unidades_actual = $almacen->unidades;

        $unidades = $unidades_actual + $cant;

        $this->connection->where('almacen_id', $alm)->where('producto_id', $product_id)->update('stock_actual', array('unidades' => $unidades));

        /* Ingredientes =================================================== */
        $query_producto = "select * from producto where id =" . $product_id;
        $producto = $this->connection->query($query_producto)->row();

        if ($producto->ingredientes == 1) {

            //Ingredientes del producto
            $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $product_id;
            $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

            foreach ($ingredientes_producto->result() as $key => $value) {
                //Stock del ingrediente
                $query_stock = "select * from stock_actual where almacen_id =" . $alm . " and producto_id=" . $value->id_ingrediente;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
            }
        }

        if ($producto->combo == 1) {
            //productos del combo
            $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $value['product_id'];
            $productos_combo = $this->connection->query($query_productos_combo);

            foreach ($productos_combo->result() as $key => $value) {
                //Stock del ingrediente
                $query_stock = "select * from stock_actual where almacen_id =" . $alm . " and producto_id=" . $value->id_producto;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual + ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
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

        $prod = "SELECT * FROM `producto` where precio_venta = '{$vprecio}' and  codigo = '{$codigo}' ";
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
            'venta_id' => $id
            , 'codigo_producto' => $data['codigo']
            , 'precio_venta' => $data['precio_venta']
            , 'unidades' => $data['unidades']
            , 'nombre_producto' => utf8_encode($data['nombre_producto'])
            , 'descripcion_producto' => $data['descripcion']
            , 'impuesto' => $data['impuesto']
            , 'descuento' => $data['descuento']
            , 'margen_utilidad' => ($compra_final_2 - $compra_final_3),
        );

        $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $data['product_id'];

        $almacen = $this->connection->query($query_stock)->row();

        $unidades_actual = $almacen->unidades;

        $unidades = $unidades_actual - $data['unidades'];

        $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $data['product_id'])->update('stock_actual', array('unidades' => $unidades));

        $this->connection->insert('stock_diario', array('producto_id' => $data['product_id'], 'almacen_id' => $data['almacen_id'], 'fecha' => date('Y-m-d'), 'unidad' => '-' . $data['unidades'], 'precio' => $data['precio_venta'], 'cod_documento' => '', 'usuario' => '', 'razon' => 'S'));

        /* Ingredientes =================================================== */
        $query_producto = "select * from producto where id =" . $data['product_id'];
        $producto = $this->connection->query($query_producto)->row();

        if ($producto->ingredientes == 1) {

            //Ingredientes del producto
            $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $data['product_id'];
            $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

            foreach ($ingredientes_producto->result() as $key => $value) {
                //Stock del ingrediente
                $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value->id_ingrediente;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                //Actualizar stock
                $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $value->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                //Insertar stock diario
                $this->connection->insert(
                    'stock_diario', array(
                        'producto_id' => $value->id_ingrediente,
                        'almacen_id' => $data['almacen_id'],
                        'fecha' => date('Y-m-d'),
                        'unidad' => '-' . ($value->cantidad * $unidades_compra),
                        'precio' => 0,
                        'cod_documento' => '',
                        'usuario' => '',
                        'razon' => 'S',
                    )
                );
            }
        }

        if ($producto->combo == 1) {
            //productos del combo
            $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $data['product_id'];
            $productos_combo = $this->connection->query($query_productos_combo);

            foreach ($productos_combo->result() as $key => $value) {
                //Stock del ingrediente
                $query_stock = "select * from stock_actual where almacen_id =" . $data['almacen_id'] . " and producto_id=" . $value->id_producto;
                $almacen = $this->connection->query($query_stock)->row();
                $unidades_actual = $almacen->unidades;
                $unidades = $unidades_actual - ($value->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                //Actualizar stock
                $this->connection->where('almacen_id', $data['almacen_id'])->where('producto_id', $value->id_producto)->update('stock_actual', array('unidades' => $unidades));
                //Insertar stock diario
                $this->connection->insert(
                    'stock_diario', array(
                        'producto_id' => $value->id_producto,
                        'almacen_id' => $data['almacen_id'],
                        'fecha' => date('Y-m-d'),
                        'unidad' => '-' . ($value->cantidad * $unidades_compra),
                        'precio' => 0,
                        'cod_documento' => '',
                        'usuario' => '',
                        'razon' => 'S',
                    )
                );
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

        $product = $this->connection->query("SELECT * FROM producto where nombre = '" . $data['nombre_producto'] . "' limit 1")->result();
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

        $this->connection->where('almacen_id', $alma)->where('producto_id', $id_producto)->update('stock_actual', array('unidades' => $unidades));

        $this->connection->insert('stock_diario', array('producto_id' => $id_producto, 'almacen_id' => $alma, 'fecha' => date('Y-m-d'), 'unidad' => $signo . $data['unidades'], 'precio' => $data['precio_venta'], 'cod_documento' => $data['id_compra'], 'usuario' => $user_id, 'razon' => $razon));
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

    /* Crear venta con estado pendiente ; estado = 1 */

    public function pendiente($data)
    {

        try {

            /* ----------------------------------------------------
            | VENTA                                              |
            ------------------------------------------------------ */

            /* Inicia transacion */
            $this->connection->trans_begin();

            /* Cliente general o especifico */
            if ($data['cliente'] == "") {
                $id_cliente = -1;
            } else {
                $id_cliente = $data['cliente'];
            }

            /* Si existe vendedor */
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

            /* ----------------------------------------------------
            | DETALLE VENTA                                      |
            ------------------------------------------------------ */

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

            print_r($e);
            die;
        }
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

            $query = "update movimientos_cierre_caja set tabla_mov = 'anulada', tipo_movimiento = 'anulada', valor = '0'  WHERE id_mov_tip = '" . $data['id'] . "' and tabla_mov = 'venta' ";
            $this->connection->query($query);

            $detalles_venta = $this->connection->query("SELECT * FROM `detalle_venta` where venta_id = {$data['id']}")->result();

            foreach ($detalles_venta as $value) {

                $producto = $this->connection->query("select * from producto where id = '{$value->producto_id}' ")->result();
                foreach ($producto as $prod) {

                    $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $prod->id;

                    $almacen = $this->connection->query($query_stock)->row();

                    $unidades_actual = $almacen->unidades;

                    $unidades = $unidades_actual + $value->unidades;

                    $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $prod->id)->update('stock_actual', array('unidades' => $unidades));

                    if ($prod->ingredientes == 1) {
                        //Ingredientes del producto
                        $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $prod->id;
                        $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                        foreach ($ingredientes_producto->result() as $key => $value1) {
                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $value1->id_ingrediente;
                            $almacen1 = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen1->unidades;
                            echo $unidades = $unidades_actual + ($value1->cantidad * $value->unidades); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $value1->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));
                            //Insertar stock diario
                            $this->connection->insert(
                                'stock_diario', array(
                                    'producto_id' => $value1->id_ingrediente,
                                    'almacen_id' => $venta->almacen_id,
                                    'fecha' => date('Y-m-d'),
                                    'unidad' => '-' . ($value1->cantidad * $value->unidades),
                                    'precio' => 0,
                                    'cod_documento' => $venta->factura,
                                    'usuario' => $data['usuario'],
                                    'razon' => 'E',
                                )
                            );
                        }
                    }

                    if ($prod->combo == 1) {
                        //productos del combo
                        $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $prod->id;
                        $productos_combo = $this->connection->query($query_productos_combo);

                        foreach ($productos_combo->result() as $key => $value1) {
                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $venta->almacen_id . " and producto_id=" . $value1->id_producto;
                            $almacen1 = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen1->unidades;
                            $unidades = $unidades_actual + ($value1->cantidad * $value->unidades); //Unidades stock - (unidades requeridadas * cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $venta->almacen_id)->where('producto_id', $value1->id_producto)->update('stock_actual', array('unidades' => $unidades));
                            //Insertar stock diario
                            $this->connection->insert(
                                'stock_diario', array(
                                    'producto_id' => $value1->id_producto,
                                    'almacen_id' => $venta->almacen_id,
                                    'fecha' => date('Y-m-d'),
                                    'unidad' => '-' . ($value1->cantidad * $unidades_compra),
                                    'precio' => 0,
                                    'cod_documento' => $venta->factura,
                                    'usuario' => $data['usuario'],
                                    'razon' => 'E',
                                )
                            );
                        }
                    }

                    $this->connection->insert('stock_diario', array('producto_id' => $prod->id, 'almacen_id' => $venta->almacen_id, 'fecha' => date('Y-m-d'), 'unidad' => $value->unidades, 'precio' => $value->precio_venta, 'cod_documento' => $venta->factura, 'usuario' => $data['usuario'], 'razon' => 'E'));
                }
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

    public function modificar_propina()
    {
        $db_config_id = $this->session->userdata('db_config_id');
        //var_dump($db_config_id); exit();
        $sql = "SELECT sum(precio_venta) as suma, venta_id as id, descripcion_producto FROM detalle_venta as a WHERE a.venta_id IN (SELECT venta_id FROM detalle_venta as b WHERE b.nombre_producto= 'PROPINA') group by a.venta_id, a.descripcion_producto ";

        foreach ($this->connection->query($sql)->result() as $value) {
            if ($value->descripcion_producto == '') {
                $suma = ((10 * $value->suma) / 100);
                $this->connection->query("UPDATE detalle_venta SET precio_venta = '" . $suma . "' WHERE venta_id = '" . $value->id . "' and nombre_producto= 'PROPINA'");
            }
        }
    }

    public function plan_separe_anulado()
    {

        $query = "
            SELECT separe.*, cl.nombre_comercial, cl.nif_cif, al.nombre AS almacen_nombre
            FROM plan_separe_factura AS separe
            INNER JOIN clientes AS cl ON separe.cliente_id = cl.id_cliente
            INNER JOIN almacen AS al ON separe.almacen_id = al.id
            WHERE estado = '3'
            OR estado = '4'
        ";

        $data = array();
        $total_abonos = 0;
        $devolvio = '';

        foreach ($this->connection->query($query)->result() as $value) {

            $query1 = "SELECT count(*) as total_abonos FROM plan_separe_pagos  WHERE id_venta = '$value->id'";
            foreach ($this->connection->query($query1)->result() as $value1) {
                $total_abonos = $value1->total_abonos;
                if ($total_abonos >= '1') {$devolvio = 'No devolvio';}
                if ($total_abonos == '0') {$devolvio = 'Devolvio';}
            }

            $user_created = $this->db->get_where('users', array('id' => $value->usuario_id), 1);
            $username_created = "No encontrado";
            if (isset($user_created->result()[0]->username)) {
                $username_created = $user_created->result()[0]->username;
            }

            $user_deleted = $this->db->get_where('users', array('id' => $value->id_user_anulacion), 1);
            $username_deleted = "No encontrado";
            if (isset($user_deleted->result()[0]->username)) {
                $username_deleted = $user_deleted->result()[0]->username;
            }

            $data[] = array(
                $value->id,
                $value->nombre_comercial,
                $username_created,
                $username_deleted,
                $value->nif_cif,
                $value->fecha_anulacion,
                number_format($value->total_venta),
                $value->almacen_nombre,
                $value->fecha_vencimiento,
                $devolvio,
                $value->id,
            );
        }
        return array(
            'aaData' => $data,
        );

    }

    public function eliminarProducto($id)
    {
        $query = "DELETE FROM plan_separe_detalle WHERE id = " . $id;
        $this->connection->where('id', $id);
        $this->connection->delete('plan_separe_detalle');
    }

    public function existepagoenventas($where)
    {
        $this->connection->select('*');
        $this->connection->where($where);
        $this->connection->from("plan_separe_pagos");
        return $this->connection->get()->result_array();
    }

    public function deletepago($where)
    {
        $this->connection->where($where);
        $this->connection->delete('plan_separe_pagos');
    }

    public function actualizarTabla_Plan_Separe_factura()
    {
        $sql = "SHOW COLUMNS FROM plan_separe_factura LIKE 'venta_id'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql = "ALTER TABLE plan_separe_factura
                ADD COLUMN venta_id int(11) COMMENT 'Campo para relacionar con la venta al ser facturada',
                ADD COLUMN asunto_anulacion text COMMENT 'Campo para el asunto de la anulación'";
            $this->connection->query($sql);

        }
    }

}

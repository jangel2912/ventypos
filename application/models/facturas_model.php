<?php

class Facturas_model extends CI_Model
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

    //=======================================================
    //  Edwin perez
    //=======================================================

    public function getPrefijo()
    {

        $object = new stdClass();

        // Si es Si el consecutivo y prefijo se lee en la tabla opcion, si no es por almacen
        $facturaConsecutivo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'numero' ")->row()->valor_opcion;

        if ($facturaConsecutivo == "si") {

            $consecutivo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'numero_factura' ")->row()->valor_opcion;
            $prefijo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'prefijo_factura' ")->row()->valor_opcion;

            $object->tipo = 'general';
            $object->consecutivo = $consecutivo;
            $object->prefijo = $prefijo;

            return $object;
        } else {

            $almacenesConsecutivos = $this->connection->query("SELECT id, nombre, prefijo, consecutivo FROM almacen")->result();

            $object->tipo = 'almacenes';
            $object->lista = $almacenesConsecutivos;

            return $object;
        }
    }

    public function getProvincia($ciudad)
    {
        $query = $this->db->query("SELECT pro_nombre FROM provincia WHERE pro_pais = 1 AND pro_nombre LIKE '%$ciudad%' ORDER BY pro_nombre ASC");
        if ($query->num_rows() == "0") {
            return "Bogota, D.C.";
        } else {
            return $query->row()->pro_nombre;
        }

    }

    public function setAjaxFacturasExcel($dataObj)
    {

        // Si se dejan celdas en blanco, creamos el campo en el obejto con contenido vacio, para evitar errores
        foreach ($dataObj as $key => $value) {

            // DATOS FACTURA
            isset($dataObj[$key]->{'Forma Pago'}) ? "" : $dataObj[$key]->{'Forma Pago'} = "";
            isset($dataObj[$key]->{'Fecha'}) ? "" : $dataObj[$key]->{'Fecha'} = "";
            isset($dataObj[$key]->{'Almacen'}) ? "" : $dataObj[$key]->{'Almacen'} = "";
            isset($dataObj[$key]->{'Vendedor'}) ? "" : $dataObj[$key]->{'Vendedor'} = "";
            isset($dataObj[$key]->{'Nota'}) ? "" : $dataObj[$key]->{'Nota'} = "";

            // DATOS CLIENTE
            isset($dataObj[$key]->{'Nombre Cliente'}) ? 0 : $dataObj[$key]->{'Nombre Cliente'} = "";
            isset($dataObj[$key]->{'CCNIT'}) ? 0 : $dataObj[$key]->{'CCNIT'} = "";
            isset($dataObj[$key]->{'Direccion Cliente'}) ? 0 : $dataObj[$key]->{'Direccion Cliente'} = "";
            isset($dataObj[$key]->{'Ciudad Cliente'}) ? 0 : $dataObj[$key]->{'Ciudad Cliente'} = "";
            isset($dataObj[$key]->{'Telefono Cliente'}) ? 0 : $dataObj[$key]->{'Telefono Cliente'} = 0;
            isset($dataObj[$key]->{'Celular Cliente'}) ? 0 : $dataObj[$key]->{'Celular Cliente'} = 0;

            // CODIGO PRODUCTO
            isset($dataObj[$key]->{'Codigo Producto'}) ? 0 : $dataObj[$key]->{'Codigo Producto'} = "";
            isset($dataObj[$key]->{'Nombre Producto'}) ? 0 : $dataObj[$key]->{'Nombre Producto'} = "";
            isset($dataObj[$key]->{'Cantidad'}) ? 0 : $dataObj[$key]->{'Cantidad'} = 0;
            isset($dataObj[$key]->{'Precio'}) ? 0 : $dataObj[$key]->{'Precio'} = 0;
            isset($dataObj[$key]->{'Descuento'}) ? 0 : $dataObj[$key]->{'Descuento'} = 0;

        }

        // Log de la cracion de Factura
        $logRegistro = array();

        $tipoFacturaConsecutivo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'numero' ")->row()->valor_opcion;

        if ($tipoFacturaConsecutivo == "si") {

            $consecutivo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'numero_factura' ")->row()->valor_opcion;
            $prefijo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'prefijo_factura' ")->row()->valor_opcion;
        } else {

            $almacenesConsecutivos = $this->connection->query("SELECT id,prefijo, consecutivo FROM almacen");
        }

        //===================================================
        // CREACION FACTURAS segun el consecutivo
        //===================================================
        // ARRAY MAESTRO
        $arrayFacturas = array();

        // PARA VALIDAR QUE SOLO SE TOME LA INFORMACION DE LA PRIMERA FILA
        $facturaExcelValidar = "consecutivoVacio";

        //CREACION DE FACTURAS EN ARRAY
        foreach ($dataObj as $key => $value) {

            // AÑADIMOS LOS DATOS DE LA FACTURA

            $facturaDatos = array();

            $facturaDatos["almacen_id"] = $value->{'Almacen'};
            $facturaDatos["forma_pago_id"] = $value->{'Forma Pago'};

            $facturaDatos["fecha"] = $value->{'Fecha'};
            $facturaDatos["vendedor"] = $value->{'Vendedor'};
            $facturaDatos["nota"] = $value->{'Nota'};

            $facturaDatos["nombre_cliente"] = $value->{'Nombre Cliente'};
            $facturaDatos["cc"] = $value->{'CCNIT'};
            $facturaDatos["dirreccion"] = $value->{'Direccion Cliente'};
            $facturaDatos["ciudad_cliente"] = $value->{'Ciudad Cliente'};
            $facturaDatos["telefono_cliente"] = $value->{'Telefono Cliente'};
            $facturaDatos["celular_cliente"] = $value->{'Celular Cliente'};

            $facturaExcel = $value->{'Factura'};
            if ($facturaExcel != $facturaExcelValidar) {

                $arrayFacturas[$facturaExcel]["factura"] = $facturaDatos;
                $arrayFacturas[$facturaExcel]["totalFactura"] = 0;

                $facturaExcelValidar = $facturaExcel;
            }
        }

        // IMPRIMIR FACTURAS GENERADAS SIN PRODUCTOS
        //print_r($arrayFacturas);
        // PARA VALIDAR QUE SOLO SE TOME LA INFORMACION DE LA PRIMERA FILA
        $almacenNombre = "";

        // AÑADIMOS LOS PRODUCTOS A LA RESPECTIVA FACTURA EN ARRAY
        foreach ($dataObj as $key => $value) {

            $facturaExcel = $value->{'Factura'};
            $noFactura = $value->{'Factura'};
            $noFacturaNumero = $noFactura;

            // Capturamos nombre almacen
            $almacenExcel = $value->{'Almacen'};
            if ($almacenExcel != "") {
                $almacenNombre = $almacenExcel;
            }

            //----------------------------------------------------------

            $codigoProducto = $value->{'Codigo Producto'};
            $queryProductoData = $this->connection->query(" SELECT producto.*,impuesto.porciento FROM producto INNER JOIN impuesto ON producto.impuesto = impuesto.id_impuesto WHERE codigo = '$codigoProducto' ");
            $existeProducto = $queryProductoData->num_rows();

            // SI EL PRODUCTO EXISTE
            if ($existeProducto) {

                // Existe Almacen?
                $queryAlmacenData = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$almacenNombre' ");
                $existeAlmacen = $queryAlmacenData->num_rows();

                // SI EL ALMACEN EXISTE
                if ($existeAlmacen > 0) {
                    $logRegistro[$facturaExcel]["productos"][] = array(
                        "alm" => $almacenNombre,
                        "almEst" => 1,
                        "pro" => $codigoProducto,
                        "proEsta" => 1,
                    );
                } else {
                    $logRegistro[$facturaExcel]["productos"][] = array(
                        "alm" => $almacenNombre,
                        "almEst" => 0,
                        "pro" => $codigoProducto,
                        "proEsta" => 1,
                    );
                }

                $productoData = $queryProductoData->row();
                $precioVExcel = $value->{'Precio'};

                $precioVenta = $productoData->precio_venta;
                if ($precioVExcel == 0) {
                    $precioVenta = $productoData->precio_venta;
                } else {
                    $precioVenta = $precioVExcel;
                }

                $nombreProducto = $productoData->nombre;
                $idProducto = $productoData->id;

                $unidades = $value->{'Cantidad'};
                $descuento = $value->{'Descuento'};
                $precioCompra = $productoData->precio_compra;
                $impuesto = $productoData->porciento;

                // Se añade al array factura
                $totalProducto = (($precioVenta - $descuento) * $unidades) * ($impuesto / 100 + 1);

                $margenUtilidad = ($precioVenta - $descuento - $precioCompra) * $unidades;

                // Se añade al array de productos para la factura que corresponda
                $productoActual = array();
                $productoActual["venta_id"] = 0; // FALTA CALCULAR MAS ADELANTE
                $productoActual["codigo_producto"] = $codigoProducto;
                $productoActual["nombre_producto"] = $nombreProducto;
                $productoActual["descripcion_producto"] = "";
                $productoActual["unidades"] = $unidades;
                $productoActual["precio_venta"] = $precioVenta;
                $productoActual["descuento"] = $descuento;
                $productoActual["impuesto"] = $impuesto; // porciento
                $productoActual["linea"] = "";
                $productoActual["margen_utilidad"] = $margenUtilidad;
                $productoActual["activo"] = 1;
                $productoActual["producto_id"] = $idProducto;

                //----------------------------------------------------------
                // Sumando los totales de los producto respectivos a cada factura
                $arrayFacturas[$noFactura]["totalFactura"] = $arrayFacturas[$noFactura]["totalFactura"] + $totalProducto;

                //$arrayFacturas[$noFactura]["productos"] = Array();

                $arrayFacturas[$noFactura]["productos"][] = $productoActual;
            } //FIN SI EL PRODUCTO EXISTE
            else {

                //$productoNoExiste[] = "Factura: " . $arrayFacturas[$noFactura]["factura"]["factura"] . " - Almacen: " . $arrayFacturas[$noFactura]["factura"]["almacen_id"] . " - Producto: " . $codigoProducto;
                // Existe Almacen?
                $queryAlmacenData = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$almacenNombre' ");
                $existeAlmacen = $queryAlmacenData->num_rows();
                // SI EL ALMACEN EXISTE
                if ($existeAlmacen > 0) {
                    $logRegistro[$facturaExcel]["productos"][] = array(
                        "alm" => $almacenNombre,
                        "almEst" => 1,
                        "pro" => $codigoProducto,
                        "proEsta" => 0,
                    );
                } else {
                    $logRegistro[$facturaExcel]["productos"][] = array(
                        "alm" => $almacenNombre,
                        "almEst" => 0,
                        "pro" => $codigoProducto,
                        "proEsta" => 0,
                    );
                }
            }

            $logRegistro[$facturaExcel]["factura"] = "0";
        }

        // IMPRIMIR FACTURAS GENERADAS CON PRODUCTOS
        // print_r( $arrayFacturas );
        // IMPRIMIR LOG
        //print_r( $logRegistro );
        //print_r( $arrayFacturas );
        // DESPUES DE CREAR EL ARRAY, AÑADIMOS EN LA BASE DE DATOS
        foreach ($arrayFacturas as $key => $value) {

            $agrupadorFacturaExcel = $key;

            //print_r($value);
            // Existe Almacen?

            $nombreAlmacen = $value["factura"]["almacen_id"];
            $queryFacturaData = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$nombreAlmacen' ");
            $existeAlmacen = $queryFacturaData->num_rows();

            // SI EL ALMACEN EXISTE
            if ($existeAlmacen > 0) {

                $idAlmacen = $existeAlmacen = $queryFacturaData->row()->id;

                $idUser = $this->session->userdata('user_id');

                $idCliente = $value["factura"]["cc"]; //-----
                $nombreCliente = $value["factura"]["nombre_cliente"]; //-----
                $direccionCliente = $value["factura"]["dirreccion"]; //-----
                $ciudadCliente = $value["factura"]["ciudad_cliente"]; //----
                $telefonoCliente = $value["factura"]["telefono_cliente"]; //------
                $celularCliente = $value["factura"]["celular_cliente"]; //--------

                $nota = $value["factura"]["nota"]; //--------

                $nombreCliente == "" ? $nombreCliente = "general" : 0;

                // EXISTE EL CLIENTE?
                $existeCliente = $this->connection->query(" SELECT id_cliente FROM clientes WHERE nombre_comercial = '$nombreCliente' ")->num_rows();

                // Cpaturamos el id del cliente
                if (intval($existeCliente) > 0) {

                    $idDbCliente = $this->connection->query(" SELECT id_cliente FROM clientes WHERE nombre_comercial = '$nombreCliente' ")->row()->id_cliente;
                } else {

                    // [CLIENTE]

                    $ciudadCliente = $this->getProvincia($ciudadCliente);

                    $sql = "
                        INSERT
                        INTO clientes ( nif_cif, nombre_comercial, direccion, provincia, telefono, movil )
                        VALUES
                        ( '$idCliente', '$nombreCliente', '$direccionCliente', '$ciudadCliente', $telefonoCliente, $celularCliente)
                    ";

                    $this->connection->query($sql);
                    $idDbCliente = $this->connection->insert_id();
                }

                $nombreVendedor = $value["factura"]["vendedor"]; //-------
                // EXISTE VENDEDOR
                $existeVendedor = $this->connection->query(" SELECT id FROM vendedor WHERE nombre = '$nombreVendedor' ")->num_rows();
                if (intval($existeVendedor) > 0) {

                    $idVendedor = $this->connection->query(" SELECT id FROM vendedor WHERE nombre = '$nombreVendedor' ")->row()->id;
                } else {

                    // [VENDEDOR]

                    $sql = "
                        INSERT
                        INTO vendedor (nombre)
                        VALUES
                        ( '$nombreVendedor')

                ";

                    $this->connection->query($sql);
                    $idVendedor = $this->connection->insert_id();
                }
                // FIN VENDEDOR
                //============================================
                //$codigoProducto = $value["factura"]["codigo_producto"];
                //$nombreProducto = $this->connection->query(" SELECT nombre FROM producto WHERE codigo = '$codigoProducto' ")->row()->nombre;
                //$productoInfo = $this->connection->query(" SELECT * FROM producto WHERE codigo = $codigoProducto")->row();
                //$impuestoId = $productoInfo->impuesto;
                //$idProductoStock = $productoInfo->id;
                //$unidades = $value["factura"]["cantidad"];
                //$productoPVenta = $productoInfo->precio_venta;
                //$productoPCompra = $productoInfo->precio_compra;
                //$impuestoPorciento = $this->connection->query(" SELECT * FROM impuesto WHERE id_impuesto = $impuestoId")->row()->porciento;
                //$subtotalVenta = intval($unidades) * intval($productoPVenta);
                //============================================

                $totalVenta = $value["totalFactura"];

                $formaPago = $value["factura"]["forma_pago_id"];
                $queryFormaPago = $this->db->query(" SELECT id_opcion FROM opciones WHERE nombre_opcion = 'tipo_pago' AND mostrar_opcion = '$formaPago' ");
                $formaPagoId = $queryFormaPago->num_rows > 0 ? $queryFormaPago->row()->id_opcion : "-1";

                //$nombreAlmacen = $nombreAlmacen;
                // $idAlmacen = $idAlmacen;

                $fecha = date($value["factura"]["fecha"] . ' H:i:s');
                $fechaVencimiento = date('Y-m-d H:i:s');

                $noFactura = $agrupadorFacturaExcel;

                $usuario = $idUser;

                // =================================================================================
                // =================================================================================
                //
                //          [VENTAS]
                //
                // =================================================================================
                // =================================================================================
                // FORMA PAGO
                $sqlFormaPago = "
                    SELECT * FROM opciones WHERE nombre_opcion = 'tipo_pago' AND mostrar_opcion = '$formaPago'
                ";

                $idFormaPago = $this->db->query($sqlFormaPago)->row()->id_opcion;
                $valorFormaPago = $this->db->query($sqlFormaPago)->row()->valor_opcion;
                $nombreForma = $this->db->query($sqlFormaPago)->row()->mostrar_opcion;

                $cantidadFilas = $this->connection->query("SELECT * FROM forma_pago WHERE nombre = '$nombreForma'")->num_rows();

                if ($cantidadFilas == 0) {
                    $this->connection->query("INSERT INTO forma_pago ( id, codigo, nombre) VALUES ($idFormaPago, $idFormaPago, '$nombreForma')");
                }

                // FIN FORMA PAGO
                // PREFIJO Y CONSECUTIVO
                // Capturar y actualizar Consecutivo

                if ($tipoFacturaConsecutivo == "si") {

                    $consecutivo = 1 + intval($this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'numero_factura' ")->row()->valor_opcion);
                    $prefijo = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'prefijo_factura' ")->row()->valor_opcion;

                    //SET

                    $sql = "
                        UPDATE opciones
                        SET valor_opcion = '$consecutivo'
                        WHERE nombre_opcion = 'numero_factura'
                    ";
                    $this->connection->query($sql);
                } else {

                    $almacenenData = $this->connection->query("SELECT id, nombre, prefijo, consecutivo FROM almacen WHERE id = '$idAlmacen' ")->row();

                    $consecutivo = 1 + intval($almacenenData->consecutivo);
                    $prefijo = $almacenenData->prefijo;

                    $sql = "
                        UPDATE almacen
                        SET consecutivo = $consecutivo
                        WHERE id = '$idAlmacen'
                    ";

                    $this->connection->query($sql);
                }

                //PREFIJO RESULTADO
                $prefConsecFactura = $prefijo . "" . $consecutivo;

                // Almacenamos prefijo al LOG
                $logRegistro[$agrupadorFacturaExcel]["factura"] = "$prefConsecFactura";

                //------------------------------------------
                // VENTA
                $sql = "
                    INSERT
                    INTO venta ( almacen_id, factura, fecha, usuario_id, cliente_id, vendedor, total_venta, nota, activo, tipo_factura, fecha_vencimiento)
                    VALUES
                    ( $idAlmacen, '$prefConsecFactura', '$fecha', $idUser, $idDbCliente, $idVendedor, $totalVenta, '$nota', 1, 'estandar', '$fechaVencimiento')

                ";

                // PAGOS
                $query = $this->connection->query($sql);
                $idVenta = $this->connection->insert_id();
                $sqlPago = "
                    INSERT
                    INTO ventas_pago ( id_venta, forma_pago, valor_entregado, cambio)
                    VALUES
                    ( $idVenta, '$valorFormaPago', $totalVenta, 0)

                ";
                $queryPago = $this->connection->query($sqlPago);

                // ======================================
                // APERTURA CAJA
                // ======================================

                $valor_caja = 0;
                $apertura = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
                $apertura_result = $this->connection->query($apertura)->result();
                foreach ($apertura_result as $dat) {
                    $valor_caja = $dat->valor_opcion;
                }

                if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {

                    $username = $this->session->userdata('username');
                    $db_config_id = $this->session->userdata('db_config_id');

                    $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                    foreach ($user as $dat) {
                        $id_user = $dat->id;
                    }

                    $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                        "hora_movimiento" => date('H:i:s'),
                        "id_usuario" => $id_user,
                        "tipo_movimiento" => 'entrada_venta',
                        "valor" => $totalVenta,
                        "forma_pago" => $valorFormaPago,
                        "numero" => $prefConsecFactura,
                        "id_mov_tip" => $idVenta,
                        "tabla_mov" => "venta",
                    );

                    $this->connection->insert('movimientos_cierre_caja', $array_datos);

                }

                //=================================================
                // ALMACENAMOS DETALLE VENTA
                //==================================================

                foreach ($value["productos"] as $keyPr => $valPr) {

                    $valPr["venta_id"] = $idVenta;
                    $this->connection->insert("detalle_venta", $valPr);

                    //print_r($valPr);
                    //echo "factura:<br>";
                    //print_r($value["factura"]);
                    // =================================================================================
                    // [STOCK DIARIO] Varibales
                    // =================================================================================

                    $producto_id = $valPr["producto_id"];
                    $almacen_id = $idAlmacen;
                    $productoPVenta = $valPr["precio_venta"];
                    $descuento = $valPr["descuento"];
                    $unidades = $valPr["unidades"];

                    // =================================================================================
                    // [STOCK DIARIO]
                    // =================================================================================
                    // =================================================================================
                    // [STOCK ACTUAL]
                    // =================================================================================
                    // REWRITE STOCK ACTUAL

                    $no_factura_row = new stdClass();

                    $no_factura_row->id = $idAlmacen; //ID ALMACEN

                    $valueStock = array(
                        "product_id" => $producto_id, // ID DEL PRODUCTO
                        "unidades" => $unidades, // UNIDADES QUE ASIGNO EL CLIENTE
                        "precio_venta" => $productoPVenta, // UNIDADES QUE ASIGNO EL CLIENTE
                    );

                    //print_r( $valueStock );

                    $data = array(
                        "usuario" => $usuario, // ID DEL PRODUCTO
                    );

                    $num_factura = $prefConsecFactura;

                    // REWRITE STOCK ACTUAL

                    $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueStock['product_id'];

                    $almacen = $this->connection->query($query_stock)->row();

                    $unidades_actual = $almacen->unidades;

                    $unidades = $unidades_actual - $valueStock['unidades'];

                    $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueStock['product_id'])->update('stock_actual', array('unidades' => $unidades));

                    $stock_diario_ = $this->connection->insert('stock_diario', array('producto_id' => $valueStock['product_id'], 'almacen_id' => $no_factura_row->id, 'fecha' => date('Y-m-d'), 'unidad' => '-' . $valueStock['unidades'], 'precio' => $valueStock['precio_venta'], 'cod_documento' => $num_factura, 'usuario' => $data['usuario'], 'razon' => 'S'));

                    // Ingredientes ===================================================
                    $query_producto = "select * from producto where id =" . $valueStock['product_id'];

                    $producto = $this->connection->query($query_producto)->row();

                    if ($producto->ingredientes == 1) {

                        //Ingredientes del producto
                        $query_ingredientes_producto = "SELECT * FROM producto_ingredientes where id_producto = " . $valueStock['product_id'];
                        $ingredientes_producto = $this->connection->query($query_ingredientes_producto);

                        foreach ($ingredientes_producto->result() as $key => $valueStock) {

                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueStock->id_ingrediente;
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual - ($valueStock->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas  cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueStock->id_ingrediente)->update('stock_actual', array('unidades' => $unidades));

                            //Insertar stock diario
                            $this->connection->insert(
                                'stock_diario', array(
                                    'producto_id' => $valueStock->id_ingrediente,
                                    'almacen_id' => $no_factura_row->id,
                                    'fecha' => date('Y-m-d'),
                                    'unidad' => '-' . ($valueStock->cantidad * $unidades_compra),
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
                        $query_productos_combo = "SELECT * FROM producto_combos where id_combo = " . $valueStock['product_id'];
                        $productos_combo = $this->connection->query($query_productos_combo);

                        foreach ($productos_combo->result() as $key => $valueStock) {

                            //Stock del ingrediente
                            $query_stock = "select * from stock_actual where almacen_id =" . $no_factura_row->id . " and producto_id=" . $valueStock->id_producto;
                            $almacen = $this->connection->query($query_stock)->row();
                            $unidades_actual = $almacen->unidades;
                            $unidades = $unidades_actual - ($valueStock->cantidad * $unidades_compra); //Unidades stock - (unidades requeridadas  cantidad del producto comprada)
                            //Actualizar stock
                            $this->connection->where('almacen_id', $no_factura_row->id)->where('producto_id', $valueStock->id_producto)->update('stock_actual', array('unidades' => $unidades));
                            //Insertar stock diario
                            $this->connection->insert(
                                'stock_diario', array(
                                    'producto_id' => $valueStock->id_producto,
                                    'almacen_id' => $no_factura_row->id,
                                    'fecha' => date('Y-m-d'),
                                    'unidad' => '-' . ($valueStock->cantidad * $unidades_compra),
                                    'precio' => 0,
                                    'cod_documento' => $num_factura,
                                    'usuario' => $data['usuario'],
                                    'razon' => 'S',
                                )
                            );
                        }
                    }
                }

                //print_r( $value );
            }
        }

        //===================================================
        // Creamos el resultado del LOG
        //===================================================
        $logRegistroResult = array();

        foreach ($logRegistro as $factura) {

            $facturaId = $factura["factura"];

            foreach ($factura["productos"] as $producto) {

                if ($producto["proEsta"] == "0") {
                    $producto["idFac"] = "0";
                } else {
                    $producto["idFac"] = $facturaId;
                }

                $logRegistroResult[] = $producto;
            }
        }

        // IMPRIMIMOS LA FACTURA
        //print_r( $arrayFacturas );
        //print_r( $logRegistroResult );

        echo json_encode($logRegistroResult);

        //echo '{"estado":"ok", "data":""}';
    }

    public function getFormasPago()
    {

        $query = $this->db->query("SELECT * FROM opciones WHERE nombre_opcion = 'tipo_pago' ");
        return $query->result();
    }

    public function getCategorias()
    {
        $query = $this->connection->query("SELECT * FROM categoria");
        return $query->result();
    }

    public function getImpuestos()
    {
        $query = $this->connection->query("SELECT * FROM impuesto");
        return $query->result();
    }

    public function getAlmacenes()
    {
        $query = $this->connection->query("SELECT * FROM almacen");
        return $query->result();
    }

    public function getVendedores()
    {
        $query = $this->connection->query("SELECT * FROM vendedor");
        return $query->result();
    }

    public function getClientes()
    {
        $query = $this->connection->query("SELECT * FROM clientes");
        return $query->result();
    }

    public function getProductos()
    {

        $query = $this->connection->query("SELECT * FROM producto");
        return $query->result();
    }

    //=======================================================
    //=======================================================
    //=======================================================

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

    public function get_ajax_data($estado = 0)
    {

        $aColumns = array('numero', 'nombre_comercial', 'monto', 'sum(pagos.cantidad) as saldo', 'fecha', 'facturas.id_factura');

        $sIndexColumn = "id_factura";

        $sTable = "facturas";

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

        $sWhere = " WHERE estado = $estado ";

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

        /* Individual column filtering

        for ( $i=0 ; $i<count($aColumns) ; $i++ )

        {

        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )

        {

        if ( $sWhere == "" )

        {

        $sWhere = "WHERE ";

        }

        else

        {

        $sWhere .= " AND ";

        }

        $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";

        }

        } */

        $sQuery = "

		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "

		FROM   $sTable inner join clientes on clientes.id_cliente = $sTable.id_cliente left join pagos on pagos.id_factura = facturas.id_factura

		$sWhere group by facturas.id_factura

		$sOrder

		$sLimit

            ";

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

        $aColumns[3] = 'saldo';

        $aColumns[5] = 'id_factura';

        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {

                $data[] = $row[$aColumns[$i]];
            }

            $output['aaData'][] = $data;
        }

        return $output;
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

        $query = $this->connection->query("SELECT * FROM facturas f Inner Join clientes c

												On f.id_cliente = c.id_cliente WHERE f.estado = '0'

												ORDER BY f.id_factura DESC LIMIT $offset, 15");

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
            "fecha_creacion" => date(),
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

        $this->connection->where('id_factura', $id);

        $this->connection->delete("facturas");

        $this->connection->where('id_factura', $id);

        $this->connection->delete("facturas_detalles");
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

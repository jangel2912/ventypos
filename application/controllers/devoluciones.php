<?php

//error_reporting(1);
//ultima actualizacion 2016-01-25

class devoluciones extends CI_Controller {

    const PLAN_SEPARE = 2;
    const ATRIBUTOS = 3;
    const PUNTOS = 4;

    var $dbConnection;
    var $user;

    function __construct() {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $this->user = $this->session->userdata('user_id');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("devoluciones_model", 'devoluciones');

        $this->devoluciones->initialize($this->dbConnection);

        $this->load->model("nota_credito_model", 'nota_credito');
        $this->nota_credito->initialize($this->dbConnection);

        $this->load->model("inventario_model", 'inventario');

        $this->inventario->initialize($this->dbConnection);

        $this->load->model("ventas_model", 'ventas');

        $this->ventas->initialize($this->dbConnection);

        $this->load->model("caja_model", 'caja');
        $this->caja->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("vendedores_model", 'vendedores');

        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        $this->load->model("promociones_model", 'promociones');
        $this->promociones->initialize($this->dbConnection);

        $this->load->model("clientes_model", 'clientes');

        $this->clientes->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');

        $this->productos->initialize($this->dbConnection);

        $this->load->model("Puntos_model", 'puntos');

        $this->puntos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);

        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("facturas_model", 'facturas');



        $this->facturas->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    function facturas() {
        echo $this->devoluciones->get_detalle_venta();
        //return $this->devoluciones->get_detalles_devoluciones($id);
    }

    function obtener_productos($id) {
        $productos = $this->devoluciones->productos($id);

        $data = (count($productos) > 0) ? $productos : [];

        $response = [
            'aaData' => $data,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0,
            'sEcho' => 1
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

     function caja_abierta(){
        $band=0;
            $data_empresa = $this->miempresa->get_data_empresa();
           
            //verifico si la caja esta abierta
             //verifico si hay caja abierta y no la tengo en session 
            //verifico si hay una caja abierta para el usuario
            //verifico si hay cierre automatico
            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico           
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d"); 
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
                }else{
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'));
                }
            
                $orderby_cierre="fecha desc, hora_apertura desc";
                $limit_cierre="1";
                $cierre_caja=$this->caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);            
                    
                if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){                             
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $band=1;
                } else{
                    $this->session->unset_userdata('caja');
                    $band=0;
                }
            }else{
                $band=1;
            }
        return $band;
    }

    public function devolver($factura_id) {

        $band=$this->caja_abierta();
            
        if($band==1){
            $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
            $productos = array();
            $total = 0;
            $totalUnidades = 0;
            $factura = $this->ventas->get_by_id($factura_id);
            //var_dump($_POST);die;
            if (isset($_POST['productos']) && count($_POST['productos']) > 0) {

                foreach ($_POST['productos'] as $key => $value) {
                
                    $keys =  explode("--",$key);
                    //$id_producto_devolucion = $keys[0];
                    //$serial = $keys[1];

                    $id_producto_devolucion = $keys[1];
                    $serial = $keys[2]; 
                    $id_detalle_venta = $keys[0];

                    if (count($this->devoluciones->producto($id_producto_devolucion)) > 0) {
                        $producto_item = $this->devoluciones->detalle_venta_by_id($factura_id, $id_detalle_venta,$id_producto_devolucion,$serial);

                        $cantidad = $producto_item['unidades'] - $_POST['unidades'][$key];
                        if ($producto_item['descripcion_producto'] != "0" && $producto_item['descripcion_producto'] != "-1") {
                            $descripcionJson = json_decode($producto_item['descripcion_producto']);
                            //var_dump($descripcionJson);die;
                            if (isset($descripcionJson->cantidadSindevolver)) {
                                $cantidad = $descripcionJson->cantidadSindevolver - $_POST['unidades'][$key];
                            }
                        }
                        $json = new stdClass();
                        $json->obsequio = 0;
                        $json->cantidadSindevolver = $cantidad;
                        $json->modificacionDevolucion = 1;
                        $this->devoluciones->updateDetalleVenta($producto_item['id'], array(
                            "descripcion_producto" => json_encode($json)
                                )
                        );
                        $producto_item['producto_id'] = $id_producto_devolucion;
                        $producto_item['venta_id'] = $factura_id;
                        $producto_item['cantidad_devolver'] = $_POST['unidades'][$key];
                        $producto_item['precio_compra'] = $_POST['precio'][$key];
                        $producto_item['total_inventario'] = $_POST['unidades'][$key] * $_POST['precio'][$key];
                        $productos[] = $producto_item;
                        $total += $producto_item['total_inventario'];
                        $totalUnidades += $_POST['unidades'][$key];

                        $devolucion_status = [
                            "status" => 2,
                            "producto_id" => $id_producto_devolucion,
                            "venta_id" => $factura_id
                        ];
                        $this->devoluciones->cambiar_status($devolucion_status);

                        $this->devoluciones->update_imei($producto_item);
                    }
                }

                $user_id = $this->session->userdata('user_id');
                $almacen_id = false;

                $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '" . $user_id . "' limit 1")->result();

                foreach ($user as $dat) {
                    $almacen_id = $dat->almacen_id;
                }
                $movimiento = array(
                    'user_id' => $user_id,
                    'fecha' => date("Y-m-d H:i:s"),
                    'productos' => $productos,
                    'almacen_id' => $almacen_id,
                    'tipo_movimiento' => 'entrada_devolucion',
                    'total_inventario' => $totalUnidades, // total unidades que vaya a devolver
                    'nota' => 'devolucion:' . $factura_id
                );
                //var_dump($movimiento['productos']);die();
                $movimiento_id = $this->inventario->add_devolucion($movimiento);
                
                $redondear_precios=get_option("redondear_precios");
                if($redondear_precios==1){
                    $total=$this->opciones_model->redondear($total);
                }


                $devolver_item = [
                    "fecha" => date("Y-m-d H:i:s"),
                    "movimiento_id" => $movimiento_id,
                    "venta_id" => $factura_id,
                    "valor" => $total
                ];

                $id = $this->devoluciones->guardar_devolucion($devolver_item);
                if ($_POST['tipoDevolucion'] == 1) {
                    $prefijo = $this->opciones->getOpcion("prefijo_devolucion");
                    $numero = $this->opciones->getOpcion("numero_devolucion");
                    $decimales = $this->opciones->getOpcion("decimales_moneda");

                    $cod = $this->nota_credito->add(array(
                        "consecutivo" => $prefijo . $numero,
                        "usuario_id" => $user_id,
                        "tipoNota" => "NC",
                        "valor" => $total,
                        "fecha" => date("Y-m-d H:i:s"),
                        "devolucion_id" => $id,
                        "factura_id" => $factura_id,
                        "movimiento_id" => $movimiento_id,
                        "cliente_id" => $factura['cliente_id'],
                        "estado" => 1
                    ));
                    $numeroNuevo = $numero + 1;
                    $this->opciones->editForName("numero_devolucion", $numeroNuevo);
                    $valor_caja = $this->opciones->getNombre("valor_caja")['valor_opcion'];
                    if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
                        $array_datos = array(
                            "Id_cierre" => $this->session->userdata('caja'),
                            "hora_movimiento" => date('H:i:s'),
                            "id_usuario" => $this->session->userdata('user_id'),
                            "tipo_movimiento" => 'entrada_devolucion',
                            "valor" => $total,
                            "forma_pago" => "nota_credito",
                            "numero" => $cod,
                            "id_mov_tip" => $id,
                            "tabla_mov" => "notacredito"
                        );

                        $this->caja->movimiento_cierre_caja($array_datos);
                    }

                    echo json_encode(array(
                        "resp" => 1,
                        "mensaje" => "Se ha registrado correctamente la devolucion con el consecutivo No. " . $id . ". se registrado una nota credito con el codigo " . $prefijo . $numero
                    ));
                } else {
                    echo json_encode(array(
                        "resp" => 1,
                        "mensaje" => "Se ha registrado correctamente la devolucion con el consecutivo No. " . $id
                    ));
                }
            }
        }else{
            if($band==0){            
                echo json_encode(array(
                    "resp" => 0,
                    "mensaje" => "Debe tener caja abierta para realizar una devolución."
                ));
            }             
        }  
    }

    function productos($id = false) {
        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
        $this->devoluciones->existeDevoluciones($this->session->userdata('base_dato'));
        $data = [];
       
        $band=$this->caja_abierta();            
        if($band==1){
            $data["estado_caja"] = "abierta";
        }else{
            $data["estado_caja"] = "cerrada";
        }
        if ($id)
            $data['factura_id'] = $id;

        $this->layout->template('member')->show('devoluciones/productos', array('data' => $data));
    }

    function actualizar($id = NULL) {

        /* var_dump($this->db->get('venta'));  */

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array(
            'venta' => $this->devoluciones->get_by_id($id)
            , 'detalle_venta' => $this->devoluciones->get_detalles_devoluciones($id)
            , 'detalle_pago' => $this->devoluciones->get_detalles_pago($id)
            , 'data_empresa' => $data_empresa
            , 'tipo_factura' => $data_empresa['data']['tipo_factura'],
        );

        if (isset($_POST['id_producto'])) {

            $id_compra = $_POST['id'];
            $id_producto = $_POST['id_producto'];
            $codigo = $_POST['codigo'];
            $product_service = $_POST['product-service'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $descuento = $_POST['descuento'];
            $id_impuesto = $_POST['id_impuesto'];
            $codigo_interno_producto = $_POST['codigo_interno_producto'];
            $n = count($id_producto);
            $i = 0;

            $data = array(
                'user_id' => $this->session->userdata('user_id')
                , 'fecha' => date('Y-m-d')
                , 'almacen_id' => $_POST['almacen']
                , 'tipo_movimiento' => 'entrada_compra'
                , 'total_inventario' => $_POST['input_total_siva']
                , 'proveedor_id' => $_POST['proveedor']
                , 'codigo_factura' => $_POST['id'],
            );

            while ($i < $n) {

                $data = array(
                    'id_compra' => $id_compra,
                    'id' => $id_producto[$i],
                    'unidades' => $cantidad[$i],
                    'nombre_producto' => $product_service[$i],
                    'precio_venta' => $precio[$i],
                    'id_impuesto' => $id_impuesto[$i],
                    'codigo_interno_producto' => $codigo_interno_producto[$i],
                    'descuento' => $descuento[$i]
                    , 'almacen_id' => $_POST['almacen'],
                );

                $this->devoluciones->actualizar_venta($data, $id);

                $i++;
            }

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("devoluciones/index/");
        }

        //agregar producto
        if (isset($_POST['id_producto_ac'])) {

            $id = $_POST['id'];
            $id_producto = $_POST['id_producto_ac'];
            $codigo = $_POST['codigo_ac'];
            $product_service = utf8_decode($_POST['product-service_ac']);
            $cantidad = $_POST['cantidad_ac'];
            $precio = $_POST['precio_ac'];
            $descuento = $_POST['descuento_ac'];
            $impuesto = $_POST['id_impuesto'];
            $n = count($id_producto);
            $i = 0;

            $data = array(
                'id_compra' => $id,
                'codigo' => $codigo,
                'descuento' => $descuento,
                'impuesto' => $impuesto,
                'product_id' => $id_producto,
                'unidades' => $cantidad,
                'nombre_producto' => $product_service,
                'precio_venta' => $precio
                , 'almacen_id' => $_POST['almacen'],
            );

            $this->devoluciones->agregar_actualizar_venta($data, $id);

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("devoluciones/actualizar/" . $id);
        }

        //   $data['cod'] = $this->_codigo();
        //Factura clasica -------------------------------------------------------------------------------------------------------
        $data['impuestos'] = $this->impuestos->get_combo_data_factura();
        $this->layout->template('member')
                ->css(array(base_url("/public/css/stylesheets.css"), base_url('public/css/multiselect/multiselect.css')))
                ->show('devoluciones/actualizar', array('data' => $data, 'id' => $id));
    }

    public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->devoluciones->get_ajax_data()));
    }

    function editar($id) {

        /* var_dump($this->db->get('venta'));  */

        $this->devoluciones->edit($id);

        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["clientes"] = $this->clientes->get_all(0);
        $data['vendedores'] = $this->vendedores->get_combo_data();
        $data['forma_pago'] = $this->pagos->get_tipos_pago();

        $this->layout->template('devoluciones')
                ->css(array(base_url("/public/css/devoluciones.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))
                ->js(array(base_url("/public/js/devoluciones.js"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))
                ->show('devoluciones/nuevo', array('data' => $data));
    }

    public function index() {
        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
        $this->devoluciones->existeDevoluciones($this->session->userdata('base_dato'));
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show("devoluciones/index", array('data'=>$data));
    }

    public function imprimir($id) {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $devolucion = $this->devoluciones->get_by_id($id);
        if ($devolucion->cliente_id != "-1") {
            $cliente = $this->clientes->get_by_id($devolucion->cliente_id);
        } else {
            $cliente = false;
        }
        $data = array(
            'devolucion' => $devolucion,
            'detalle_devolucion' => $this->devoluciones->detalleDevolucion($devolucion->movimiento_id),
            'nota_credito' => $this->nota_credito->get_nota_credito_devolucionId($id),
            'data_empresa' => $data_empresa,
            'cliente' => $cliente
//                    'data_almacen' => $almacen,
        );

        //var_dump($data);
        $this->layout->template('ajax')->show('devoluciones/_imprime', array('data' => $data));
    }

    public function facturaSindevolucion($id) {
        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
        $this->devoluciones->existeDevoluciones($this->session->userdata('base_dato'));
        $data = $this->devoluciones->facturaSindevolucion($id);
        //die("bn");
        if (count($data) != 0) {
            echo json_encode(array("resp" => 0, "mensaje" => "La factura tiene devoluciones asociadas por lo que no puede ser anulada"));
        } else {
            echo json_encode(array("resp" => 1));
        }
    }

}

?>
<?php
class Ventas extends CI_Controller
{
    const PLAN_SEPARE = 2;
    const ATRIBUTOS = 3;
    const PUNTOS = 4;
    const PREFIX_NOTA_CREDITO = 'NOCT';
    public $dbConnection;
    public $user;
    public function __construct()
    {

        parent::__construct();
        $usuario = $this->session->userdata('usuario');
        $this->user = $this->session->userdata('user_id');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model("ventas_model", 'ventas');
        $this->ventas->initialize($this->dbConnection);

        $this->load->model("resolution_history_model", 'resolution_history');
        $this->resolution_history->initialize($this->dbConnection);

        

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
        $this->load->model("pais_provincia_model", 'pais_provincia');
        $this->load->model("facturas_model", 'facturas');
        $this->load->model("nota_credito_model", 'nota_credito');
        $this->nota_credito->initialize($this->dbConnection);
        $this->load->model("devoluciones_model", 'devoluciones');
        $this->devoluciones->initialize($this->dbConnection);
        $this->load->model("comanda_model");
        $this->comanda_model->initialize($this->dbConnection);
        $this->comanda_model->crearTablasNotificaciones();
        $this->ventas->crearCamposElectronicInvoice();
        $this->facturas->initialize($this->dbConnection);
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
        $this->load->model("secciones_almacen_model", 'secciones_almacen');
        $this->secciones_almacen->initialize($this->dbConnection);
        $this->load->model("mesas_secciones_model", 'mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);
        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);
        $this->load->model("licencias_model", 'licenciasModel');
        $this->licenciasModel->initialize($this->dbConnection);
        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);
        $this->load->model('primeros_pasos_model');
        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);
        $this->load->model("grupo_model", 'grupo');
        $this->grupo->initialize($this->dbConnection);
        $this->load->model("caja_model", 'caja');
        $this->caja->initialize($this->dbConnection);
        $this->load->model("domiciliarios_model", 'domiciliarios');
        $this->domiciliarios->initialize($this->dbConnection);
        $this->load->model('crm_empresas_clientes_model');
        $this->load->model("ventas_separe_model", 'separeModel');
        $this->separeModel->initialize($this->dbConnection);
        $this->nota_credito->crearCamposElectronicInvoice();
        $this->load->model('crm_model');

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        if ((!empty($data_empresa['data']['tipo_negocio'])) && ($data_empresa['data']['tipo_negocio'] == "restaurante")) {
            $this->ventas->agregar_columna_tipo_propina();
        }

        //domicilios
        $this->load->model("domiciliarios_model", 'domiciliarios');
        $this->domiciliarios->initialize($this->dbConnection);
        $this->mi_empresa->crearOpcion('domicilios', 'no');
        //crear tabla de domiciliarios
        $this->domiciliarios->crear_domiciliarios();

        //campo de consecutivo orden
        $this->ventas->addColumnOrden();

        //agregar el venta_id a la tabla plan_separe_factura
        $this->separeModel->actualizarTabla_Plan_Separe_factura();
    }

    public function facturas()
    {
        echo $this->ventas->get_detalle_venta();

        // return $this->ventas->get_detalles_ventas($id);

    }

    public function comprobar_venta()
    {
        if ($this->session->userdata('db_config_id') == 4017) {
            if (isset($_POST['productos'])) {
                $totalproducto = 0;
                $totalpagos = 0;
                $multiformapago = 'no';
                $ocp = $this->opciones->getNombre('multiples_formas_pago');
                $multiformapago = $ocp['valor_opcion'];
                $porcentaje_descuento = 0;
                $descuento_prod = 0;
                $precio_venta = 0;
                $propina_porcentaje = 0;
                $propina = 0;
                foreach ($_POST['productos'] as $producto) {

                    // precio

                    if ($producto['impuesto'] != 0) {
                        $precio_venta = ROUND(($producto['precio_venta']) + (($producto['precio_venta']) * ($producto['impuesto'] / 100)));
                    } else {
                        $precio_venta = ROUND($producto['precio_venta']);
                    }

                    // descuento_porcentaje

                    $porcentaje_descuentop = round(($producto['descuento'] * 100) / $producto['precio_venta'], 20);
                    if ($_POST['descuento_general'] != 0) {
                        $descuento_prod = ROUND((((($producto['precio_venta']) + (($producto['precio_venta']) * $producto['impuesto'] / 100)) * $porcentaje_descuentop) / 100) + (((($precio_venta) - (((($producto['precio_venta']) + (($producto['precio_venta']) * $producto['impuesto'] / 100)) * $porcentaje_descuentop) / 100)) * $_POST['descuento_general']) / 100));
                    } else {
                        $descuento_prod = ROUND(((($producto['precio_venta']) + (($producto['precio_venta']) * $producto['impuesto'] / 100)) * $porcentaje_descuentop) / 100);
                    }

                    // productos sin promo

                    if ($producto['promocion'] != '-1') {
                        $total = ROUND(((($precio_venta) - ($descuento_prod)) * $producto['unidades']));
                        $totalproducto += $total;
                    }
                }

                if (isset($_POST['pago'])) {
                    $valor_entregado = $_POST['pago']['valor_entregado'];
                    $cambio = $_POST['pago']['cambio'];
                    $totalpagos += $valor_entregado - $cambio;
                }

                if ($multiformapago == 'si') {
                    if ($_POST['pago_1']['valor_entregado'] != '0') {
                        $valor_entregado = $_POST['pago_1']['valor_entregado'];
                        $cambio = $_POST['pago_1']['cambio'];
                        $totalpagos += $valor_entregado - $cambio;
                    }

                    if ($_POST['pago_2']['valor_entregado'] != '0') {
                        $valor_entregado = $_POST['pago_2']['valor_entregado'];
                        $cambio = $_POST['pago_2']['cambio'];
                        $totalpagos += $valor_entregado - $cambio;
                    }

                    if ($_POST['pago_3']['valor_entregado'] != '0') {
                        $valor_entregado = $_POST['pago_3']['valor_entregado'];
                        $cambio = $_POST['pago_3']['cambio'];
                        $totalpagos += $valor_entregado - $cambio;
                    }

                    if ($_POST['pago_4']['valor_entregado'] != '0') {
                        $valor_entregado = $_POST['pago_4']['valor_entregado'];
                        $cambio = $_POST['pago_4']['cambio'];
                        $totalpagos += $valor_entregado - $cambio;
                    }

                    if ($_POST['pago_5']['valor_entregado'] != '0') {
                        $valor_entregado = $_POST['pago_5']['valor_entregado'];
                        $cambio = $_POST['pago_5']['cambio'];
                        $totalpagos += $valor_entregado - $cambio;
                    }
                }

                if (isset($_POST['subtotal_propina_input'])) {
                    if ($_POST['subtotal_propina_input'] != 0) {
                        $propina = 0;
                        $propina_porcentaje = $_POST['propina'];
                    }
                }

                if ($this->session->userdata('db_config_id') == 4017) {
                    $total_venta = ($_POST['total_venta'] != 0) ? $_POST['total_venta'] : $_POST['total_venta1'];
                    if (($totalproducto == $total_venta) && ($total_venta == $totalpagos)) {
                        $success = array(
                            'success' => 1,
                        ); //bien
                    } else {

                        // $success= array('success'=>0);//mal no coinciden

                        $success = array(
                            'success' => 1,
                        );
                    }
                } /*else{
            $total_venta=($_POST['total_venta'] - (($_POST['total_venta'] * $_POST['descuento_general']) / 100));
            $propina=round (($total_venta * $propina_porcentaje) / 100);
            if($propina>0){
            $total_venta=$total_venta+$propina;
            $totalproducto=$totalproducto+$propina;
            }

            if(($totalproducto==$total_venta)&&($total_venta==$totalpagos)){
            $success= array('success'=>1);//bien
            }
            else{
            $success= array('success'=>0);//mal no coinciden
            }
            }     */
            } else {
                array(
                    'success' => 0,
                );
            }
        } else {
            array(
                'success' => 1,
            );
        }

        echo json_encode($success);
    }

    public function enviar_email_primera_venta($venta_id = 1)
    {
        $email = $this->session->userdata('email');
        $data = array(
            "user" => $this->session->userdata('username'),
        );

        $html = $this->load->view('email/first_sale', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('info@vendty.com', 'Vendty POS Cloud');
        $this->email->to($email);
        $this->email->bcc('arnulfo@vendty.com', 'info@vendty.com');
        $this->email->subject("Realizaste tu primera Factura | Vendty");
        $this->email->message($html);
        $this->email->send();
    }

    public function edit($id) {
        $data['venta'] = $this->ventas->show($id);
        $almacen_id = $data['venta']->almacen_id;
        // Mark: Cierres de caja previos

        $data['cajas'] = $this->caja->get_last_closed_box($almacen_id);
        

        //var_dump($data['cajas']); die;
        $this->layout->template('member')->show('ventas/edit_fecha',$data);
    }

    public function update_fecha() {
        if ($this->input->post()) {
            $data =  $this->input->post();
            
            //$detalle_movimientos['obtener_movimientos_validos'] = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
            if(isset($data['id_cierre'])) {
                $id_cierre = $data['id_cierre'];
                $id_venta = $data['venta_id'];

                $regenerate = $this->caja->regenerate_box($id_cierre, $id_venta);
            }


            $data =  $this->input->post();
            $venta = $this->ventas->update_fecha($data);

            if($venta) {
                $this->session->set_flashdata('message', "Se ha actualizado la fecha de la factura correctamente");
                redirect(site_url("ventas"));
            }
        }
    }

    // MARK : nota de credito
    public function nota($id) {
        // MARK: Validacion incial para generar la nota credito
        $data['venta'] = $this->ventas->show($id);

        // Verificamos si la factura tiene una nota ya generada
        $check_nota =  $this->nota_credito->show(null,['factura_id' => $id,'electronic_invoice' => '1']);
        $nota_exist = false;
        if(count($check_nota))
            $nota_exist = true;

        $nota =  $this->nota_credito->show(null,['electronic_invoice' => '1']);
        $nuevo_consecutivo = self::PREFIX_NOTA_CREDITO.'0001';

        if(count($nota)) {

            $value =  array_values($nota)[0]; 

            $resultado = intval(preg_replace('/[^0-9]+/', '', $value['consecutivo']), 10);

            $nuevo_consecutivo = self::PREFIX_NOTA_CREDITO.str_pad($resultado + 1, 4, "0", STR_PAD_LEFT);

        }

        $data['nota'] = [
            'consecutivo' => $nuevo_consecutivo,
            'nota_exist' => $nota_exist
        ];





        $this->layout->template('member')->show('ventas/nota',$data);
    }

    // MARK: generamso la nota de credito 
    public function genera_nota($id = '') {
        if ($this->input->post()) {
            $data            = $this->input->post();
            

            if ($id == '') {
                $id = $this->nota_credito->nuevo($data);
                if ($id) {
                    $response = get_curl('generate-nota/' . $id, $this->session->userdata('token_api'));
                    //set_alert('success', 'added_successfully', 'nuevo registro creado');
                    $this->session->set_flashdata('message', "Se ha generado la nota de credito correctamente ");
                    redirect(site_url("ventas"));

                }

            }
        }


    }
    public function nuevo()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->puntos->activate_plan();
        $this->productos->check_tabla_seriales();
        $this->productos->validate_tipo_producto_imei();
        $this->ventas->addColumnOrden();
        //Puedo facturar?
        //$almacenActual = $this->dashboardModel->getAlmacenActual();
        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if (empty($almacenActual)) {
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
        if (($puedofacturar == 1) && ($this->session->userdata('db_config_id') != 2547)) {
            $url = site_url("frontend");
            echo '
        <script>
            alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");
            window.location="index";
        </script>';
        }

        /************Comprobar que esta activa la licencia usuarios para vender*****************/
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');
        $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacenActual);
        $almacentodos = $this->almacenes->getAll();
        $hoy = date('Y-m-d');

        if (isset($licencia['estado_licencia'])) {
            if ((($licencia['fecha_vencimiento'] < $hoy) || ($licencia['estado_licencia'] == 15)) && ($licencia['id_almacen'] == $almacenActual) && (($administrador == 's') || ($administrador == 'a') || ($administrador == 'f') || ($administrador != 't'))) {
                //verificar si aun estoy dentro de los 7 dias adicionales
                $fecha_nueva = $licencia['fecha_vencimiento'];
                $fecha_nueva = date("Y-m-d", strtotime($fecha_nueva . "+ 7 days"));

                if ($fecha_nueva < $hoy) {
                    //redirigir a login
                    $data['title'] = "SUSCRIPCIÓN VENCIDA";
                    $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de Vendty, por favor comuníquese con el administrador del Sistema.";
                    $data['message1'] = "";
                    $data['message2'] = "";
                    $data['message3'] = '3';
                    $data['mostrar_salir'] = true;
                    $data['logout'] = false;
                    $data['config'] = false; //boton de pagar
                    $data['btnconfig'] = false; //muestra boton de configuraciones =true
                    $data['licencias'] = $licencia;
                    $data['almacentodos'] = $almacentodos;
                    $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['info_factura_pais'] = $this->clientes->get_pais();
                    $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                    $this->ion_auth->logout();
                    $this->layout->template('login')->show('licenciasvencidas');
                    $html = $this->load->view('licenciasvencidas', $data, true);
                    echo $html;
                    exit;
                }
            }
        }

        /*arreglar los campos en ventas*/

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $estadoBD = $cuentaEstado["estado"];
        $this->session->userdata('caja');
        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }

        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
        $this->devoluciones->existeDevoluciones($this->session->userdata('base_dato'));
        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data = array();

        // webpay

        $this->load->model("webpay_model", 'webpay');
        $this->webpay->initialize($this->dbConnection);
        $this->webpay->existeWebpay($this->session->userdata('base_dato'));
        if ($this->input->get('id_cot')) {
            $data_cotizacion = $this->ventas->getDataCotizacionById($this->input->get('id_cot')); //die(var_dump($data["cotizacion"]));
            if(count($data_cotizacion) > 0){
                $data_cotizacion[0]->nombre_vendedor = '';
                $data_cotizacion[0]->vendedor = '';
            }
        }

        $data['imprimir_comanda'] = false;
        $zona = $this->uri->segment(3);
        $mesa = $this->uri->segment(4);
        if ($zona && $mesa) {

            // buscamos la orden

            $almacenActual = $this->dashboardModel->getAlmacenActual();
            $ordenes = $this->ordenes->getDataOrdenByMesa($zona, $mesa, $almacenActual);

            // buscamos el vendedor si es de la estaciÃƒÆ’Ã‚Â³n de pedidos
            // $vendedor=$this->mesas_secciones->get_una_mesa_by(array('id'=>$mesa, 'id_seccion'=>$zona));

            $vendedor = $this->mesas_secciones->get_vendedor_venta_estacion(array(
                'mesas_secciones.id' => $mesa,
                'mesas_secciones.id_seccion' => $zona,
            ));
            if (count($vendedor)) {
                $vendedor_id = $vendedor->id;
                $nombre_vendedor = $vendedor->nombre;
            } else {
                $vendedor_id = "";
                $nombre_vendedor = "";
            }

            if (count($ordenes)) {
                $data['imprimir_comanda'] = true;
                $data['zona'] = $zona;
                $data['mesa'] = $mesa;
            }

            $counter = 0;
            $orden_final = array();

            foreach ($ordenes as $orden) {
                $data_orden = new stdClass();
                $data_orden->id_cliente = $orden->id_cliente;
                $data_orden->vendedor = $vendedor_id;
                $data_orden->nombre_vendedor = $nombre_vendedor;
                $data_orden->nombre_comercial = $orden->nombre_comercial;
                $data_orden->id = $orden->id;
                $data_orden->order_producto = $orden->order_producto;

                $data_orden->zona = $orden->zona;
                $data_orden->mesa_id = $orden->mesa_id;
                $data_orden->estado = $orden->estado;
                $data_orden->created_at = $orden->created_at;
                $data_orden->update_at = $orden->update_at;
                $data_orden->cantidad = $orden->cantidad;
                $data_orden->fk_id_producto = $orden->fk_id_producto;
                $data_orden->nombre = $orden->nombre;
                $data_orden->precio = $orden->precio;
                $data_orden->codigo = $orden->codigo;
                $data_orden->descripcion_d = $orden->descripcion_d;
                $data_orden->impuesto = $orden->impuesto;
                $data_orden->monto_iva = $orden->monto_iva;
                $data_orden->monto = $orden->monto;
                $orden_final[$counter] = $data_orden;
                if (is_array(json_decode($orden->order_adiciones))) {

                    // Verificamos las adiciones realizadas

                    $adiciones = json_decode($orden->order_adiciones);
                    $adicionnuevas = array();
                    foreach ($adiciones as $adicion) {
                        $data_adicion = $this->productos->getAdicionByid($orden->order_producto, $adicion);
                        if (!empty($data_adicion)) {
                            $adicionnuevas[] = $adicion;
                        }
                    }
                    foreach ($adicionnuevas as $adicion) {
                        $data_orden = new stdClass();
                        $counter++;
                        $data_adicion = $this->productos->getAdicionByid($orden->order_producto, $adicion);
                        if (!empty($data_adicion)) {
                            $id_producto = $data_adicion[0]['id_adicional'];

                            // obtenemos el producto asociado

                            $producto = $this->productos->get_by_id($id_producto);
                            $impuesto = $this->productos->get_impuesto_by_id($producto['impuesto']);
                            $data_orden->id_cliente = $orden->id_cliente;
                            $data_orden->nombre_comercial = $orden->nombre_comercial;
                            $data_orden->id = $orden->id;
                            $data_orden->order_producto = $id_producto;

                            $data_orden->zona = $orden->zona;
                            $data_orden->mesa_id = $orden->mesa_id;
                            $data_orden->estado = $orden->estado;
                            $data_orden->created_at = $orden->created_at;
                            $data_orden->update_at = $orden->update_at;

                            $data_orden->cantidad = $orden->cantidad;

                            $precio = $data_adicion[0]['precio'] * $data_adicion[0]['cantidad'];
                            $precio_total = $precio / ((doubleval($impuesto['porciento']) / 100) + 1);
                            $iva_valor = $precio - $precio_total;

                            $data_orden->fk_id_producto = $producto["id"];
                            $data_orden->nombre = $producto['nombre'];
                            $data_orden->precio = strval($precio_total);
                            $data_orden->codigo = $producto['codigo'];
                            $data_orden->descripcion_d = $producto['descripcion'];
                            //$data_orden->impuesto = $orden->impuesto;
                            $data_orden->impuesto = $impuesto['porciento'];
                            $data_orden->monto_iva = strval($iva_valor);

                            $data_orden->monto = strval($precio_total);

                            $orden_final[$counter] = $data_orden;
                        }
                    }
                }

                $counter++;
            }

            // $data['total'] = $valor_orden;

        }

        $data['pais'] = $this->pais_provincia->get_pais();

        // APERTURA DE CAJA

        if ($data_empresa['data']['valor_caja'] == 'si') {

            // Si el cierre de caja es automatico
            if ($data_empresa['data']['cierre_automatico'] == '1') {

                $is_admin = $this->session->userdata('is_admin');
                $username = $this->session->userdata('username');
                $db_config_id = $this->session->userdata('db_config_id');
                $id_user = $this->session->userdata('user_id');

                $hoy = date("Y-m-d");
                $orderby_cierre = "fecha desc, hora_apertura desc";
                $limit_cierre = "1";
                $cierre_caja = $this->caja->get_id_caja_en_cierre_caja(array('id_Usuario' => $this->session->userdata('user_id'), 'fecha' => $hoy, 'total_cierre' => ''), $orderby_cierre, $limit_cierre);

                if (!empty($cierre_caja)) {
                    if (isset($cierre_caja->id) && $cierre_caja->total_cierre == "") {
                        $this->session->set_userdata('caja', $cierre_caja->id);
                    }
                } else {
                    if ((empty($cierre_caja)) && (isset($_POST['total_venta']))) {
                        $this->session->unset_userdata('caja');
                        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Disculpe no tiene caja abierta, para factura debe abrir la caja"));
                        return $this->output->set_content_type('application/json')->set_output(json_encode(array(
                            'success' => false,
                            'id' => 0,
                            'divisionitem' => '0_',
                            'mensaje' => 'Disculpe no tiene caja abierta, para factura debe abrir la caja',
                        )));
                    } else {
                        if ((empty($cierre_caja)) && (!isset($_POST['total_venta']))) {
                            $this->session->unset_userdata('caja');
                            $this->session->set_userdata('page_backup', null);
                            redirect(site_url("caja/apertura"));
                        }
                    }
                }
            } else { // Si el cierre de caja no es automatico

                $orderby_cierre = "fecha desc, hora_apertura desc";
                $limit_cierre = "1";
                $cierre_caja = $this->caja->get_id_caja_en_cierre_caja(array('id_Usuario' => $this->session->userdata('user_id')), $orderby_cierre, $limit_cierre);

                // Si el ultimo cierre de caja no tiene total de cierre entonces continuamos vendiendo en el
                if (isset($cierre_caja->id) && $cierre_caja->total_cierre == "") {
                    $this->session->set_userdata('caja', $cierre_caja->id);
                } else { // de lo contrario, aperturamos caja

                    if ((isset($cierre_caja->id)) && ($cierre_caja->total_cierre != "") && (isset($_POST['total_venta']))) {
                        $this->session->unset_userdata('caja');
                        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Disculpe no tiene caja abierta, para factura debe abrir la caja"));
                        return $this->output->set_content_type('application/json')->set_output(json_encode(array(
                            'success' => false,
                            'id' => 0,
                            'divisionitem' => '0_',
                            'mensaje' => 'Disculpe no tiene caja abierta, para factura debe abrir la caja',
                        )));
                    } else {
                        if ((isset($cierre_caja->id)) && ($cierre_caja->total_cierre != "") && (!isset($_POST['total_venta']))) {
                            $this->session->unset_userdata('caja');
                            redirect(site_url("caja/apertura"));
                        }

                    }

                    $is_admin = $this->session->userdata('is_admin');
                    $username = $this->session->userdata('username');
                    $db_config_id = $this->session->userdata('db_config_id');

                    if ($this->session->userdata('caja') == "") {
                        $id_user = $this->session->userdata('user_id');
                        $almacen = '';

                        $hoy = date("Y-m-d");

                        $query = $this->caja->get_id_caja_en_cierre_caja(array('fecha' => $hoy, 'id_Usuario' => $this->session->userdata('user_id'), 'total_cierre' => ''), $orderby_cierre, $limit_cierre);
                        $query_registros = count($query);
                        if ($query_registros == 1) {
                            $cierre_caja = 0;
                            $cierre_caja = $this->caja->get_id_caja_en_cierre_caja(array('fecha' => $hoy, 'id_Usuario' => $this->session->userdata('user_id'), 'total_cierre' => ''), $orderby_cierre, $limit_cierre);
                            $this->session->set_userdata('caja', $cierre_caja->id);
                        }

                        if ($query_registros == 0) {
                            redirect(site_url("caja/apertura"));
                        }
                    }
                }
            }
        }

        $divisionitem = "0_";
        if (isset($_POST['forma_pago'])) {
            $pago_1 = (isset($_POST['pago_1'])) ? $_POST['pago_1'] : $_POST['forma_pago'];
            $pago_2 = (isset($_POST['pago_2'])) ? $_POST['pago_2'] : $_POST['forma_pago'];
            $pago_3 = (isset($_POST['pago_3'])) ? $_POST['pago_3'] : $_POST['forma_pago'];
            $pago_4 = (isset($_POST['pago_4'])) ? $_POST['pago_4'] : $_POST['forma_pago'];
            $pago_5 = (isset($_POST['pago_5'])) ? $_POST['pago_5'] : $_POST['forma_pago'];
        }

        /* if($this->form_validation->run('facturas') == true){...}*/
        if (isset($_POST['vendedor']) || isset($_POST['vendedor_2'])) {

            // Identifica si una venta fue por POS o por Servicios

            if ($data_empresa['data']['tipo_factura'] != 'clasico') {
                $pago = $_POST['pago'];
                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];
                $tipo_factura = 'estandar';
                $fecha = date('Y-m-d H:i:s');
                $fecha_venc = '';
                $parts = explode('/', $_POST['fecha_v']);
                if (count($parts) == 3) {
                    $fecha_venc = $_POST['fecha_v'] . " " . date('H:i:s');
                } else {
                    $fecha_venc = date('Y-m-d H:i:s');
                }

                $fecha_vencimiento = $fecha_venc;
            } else {
                $pago = array(
                    'valor_entregado' => $_POST['total_venta'],
                    'cambio' => 0,
                    'forma_pago' => $_POST['forma_pago'],
                    'transaccion' => $_POST['transaccion'],
                );
                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];
                $tipo_factura = 'clasico';
                $fecha = $_POST['fecha'] . " " . date('H:i:s');
                $fecha_vencimiento = $_POST['fecha_v'] . " " . date('H:i:s');
            }

            $total_venta = ($_POST['total_venta1'] != 0) ? $_POST['total_venta1'] : ($_POST['total_venta'] - (($_POST['total_venta'] * $_POST['descuento_general']) / 100));
            //esrestaurante?
            $comandarestaurante = explode("_", $_POST['comandarestaurante']);
            if ($comandarestaurante[0] == 1) {
                $x = $this->mesas_secciones->get_secciones_mesas_by_id_mesa($comandarestaurante[2]);
                $comensales = $x->comensales;
                $consecutivo_orden = $x->consecutivo_orden_restaurante;
            }

            $data = array(
                'fecha' => $fecha,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cliente' => $_POST['cliente'],
                'vendedor' => $_POST['vendedor'],
                'vendedor_2' => $_POST['vendedor_2'],
                'usuario' => $this->session->userdata('user_id'),
                'productos' => $_POST['productos'],
                'total_venta' => $total_venta,
                'pago' => $pago,
                'pago_1' => $pago_1,
                'pago_2' => $pago_2,
                'pago_3' => $pago_3,
                'pago_4' => $pago_4,
                'pago_5' => $pago_5,
                'tipo_factura' => $tipo_factura,
                'nota' => $_POST['nota'],
                'promocion' => $_POST['promocion'],
                'descuento_general' => $_POST['descuento_general'],
                'subtotal_input' => $_POST['subtotal_input'],
                'subtotal_propina_input' => $_POST['subtotal_propina_input'],
                'sobrecostos' => ((isset($_POST['propina'])) ? $_POST['propina'] : 0),
                'tipo_propina' => ((isset($_POST['tipo_propina'])) ? $_POST['tipo_propina'] : null),
                'id_fact_espera' => (isset($_POST['id_fact_espera']) ? $_POST['id_fact_espera'] : ''),
                'sistema' => $data_empresa['data']['sistema'],
                'aleatorio' => $_POST['aleatorio'],
                'venta_sin_pago' => $_POST['grabar_sin_pago'],
                'comensales' => (isset($comensales)) ? $comensales : null,
                'consecutivo_orden' => (isset($consecutivo_orden)) ? $consecutivo_orden : null,
                'facturacion_electronica' => $_POST['facturacion_electronica'],
            );

            /*Registrar venta*/
            $id = $this->ventas->add($data);
            $data_puntos_leal = array(
                'user_puntos_leal' => ((isset($_POST['user_puntos_leal'])) ? $_POST['user_puntos_leal'] : null),
                'total_payment' => $total_venta,
            );

            $puntos_leal = post_curl('puntos_leal/assign_points', json_encode($data_puntos_leal), $this->session->userdata('token_api'));
            $status_electronic_invoice = null;
            if (isset($_POST['facturacion_electronica']) && $_POST['facturacion_electronica'] === 'true') {
                $response = get_curl('generate/' . $id, $this->session->userdata('token_api'));
                
                if($response->response->codigoRespuesta == "04") {
                    $invoice_response =  json_encode([
                        'codigo_repuesta' => $response->response->codigoRespuesta,
                        'descripcion_respuesta' => $response->response->descripcionRespuesta,
                    ]);
                } else {
                    // MARK: ELECTRONIC INVOICE RESPONSE
                    $invoice_response =  json_encode([
                            'codigo_repuesta' => $response->response->codigoRespuesta,
                            'descripcion_respuesta' => $response->response->descripcionRespuesta,
                            'id_transacion' => $response->response->idTransaccion,
                    ]);
                }
                // MARK: ELECTRONIC INVOICE RESPONSE
                $status_electronic_invoice = $response->response->descripcionRespuesta;
                $electronic_invoice = $this->ventas->update_electronic_invoice($id,$invoice_response);
            }
            // MARK: guardar evento de primeros pasos venta

            if (!empty($id)) {

                if (isset($_POST['presione_domicilio']) && $_POST['presione_domicilio'] == "si") {
                    if ((!empty($_POST['datos_cliente_domicilio'])) && (!empty($_POST['domiciliario'])) && (!empty($_POST['telefono_domicilio'])) && (!empty($_POST['direccion_domicilio']))) {

                        $data_domicilio = array(
                            'domiciliario' => $_POST['domiciliario'],
                            'factura' => $id,
                            'nombre' => $_POST['datos_cliente_domicilio'],
                            'telefono' => $_POST['telefono_domicilio'],
                            'direccion' => $_POST['direccion_domicilio'],
                            'estado' => 1,
                        );
                        $id_domi = $this->domiciliarios->add_domicilio($data_domicilio);
                    }
                }

                $estadoBD = $this->newAcountModel->getUsuarioEstado();
                if ($estadoBD["estado"] == 2) {
                    $paso = 14;
                    $marcada = $this->primeros_pasos_model->verificar_tareas_realizadas(array(
                        'id_usuario' => $this->session->userdata('user_id'),
                        'db_config' => $this->session->userdata('db_config_id'),
                        'id_paso' => $paso,
                    ));
                    if ($marcada == 0) {
                        $datatarea = array(
                            'id_paso' => $paso,
                            'id_usuario' => $this->session->userdata('user_id'),
                            'db_config' => $this->session->userdata('db_config_id'),
                        );
                        $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                    }
                }
            }

            /*Impresion rapida*/
            $get_almacen_by_id = $this->ventas->get_by_id($id);
            $result_caja = $this->caja->get_id_caja_en_cierre_caja(array(
                'id' => $this->session->userdata('caja'),
            ));

            // $this->ventas->add_impresion_rapida($id,$data_impresion_rapida,$get_almacen_by_id["almacen_id"],$result_caja->id_Caja);

            /*Vaciar la mesa si fuese el caso */
            $comandarestaurante = explode("_", $_POST['comandarestaurante']);
            if ($comandarestaurante[0] == 1) {

                // $this->ordenes->eliminarOrden($comandarestaurante[1],$comandarestaurante[2]);
                // $this->ordenes->eliminarOrden($comandarestaurante[1],$comandarestaurante[2],2);

                $this->ordenes->guardarOrden($comandarestaurante[1], $comandarestaurante[2], $id, array('2', '3'));
                $this->ordenes->eliminarOrden($comandarestaurante[1], $comandarestaurante[2], array('2', '3'));
                $this->ordenes->CambiarDivisionCuentaOrdenZonaMesa($comandarestaurante[1], $comandarestaurante[2]);
                $almacenActual = $this->dashboardModel->getAlmacenActual();
                $divisionitem = $this->ordenes->getDataOrdenByMesa($comandarestaurante[1], $comandarestaurante[2], $almacenActual);
                $divisionitem = count($divisionitem);
                if ($divisionitem > 0) {
                    $seccionnombre = $this->secciones_almacen->get_una_seccion_by(array(
                        'id_almacen' => $almacenActual,
                        'id' => $comandarestaurante[1],
                    ));
                    $seccionnombre = $seccionnombre->nombre_seccion;
                    $mesanombre = $this->mesas_secciones->get_mesa_secciones(array(
                        'id_seccion' => $comandarestaurante[1],
                        'activo' => 1,
                        'id' => $comandarestaurante[2],
                    ));
                    $mesanombre = $mesanombre[0]->nombre_mesa;
                    $divisionitem .= '_' . $seccionnombre . '_' . $mesanombre;
                } else {

                    // NOTIFICAR EL  VACIADO DE LA MESA
                    $endpoint = "take-order/notify-vacate-table/" . $comandarestaurante[2];

                    get_curl($endpoint, $this->session->userdata('token_api'));

                    $divisionitem = "0_";
                    $this->mesas_secciones->actualizar_mesa(array(
                        'nota_comanda' => null,
                        'vendedor_estacion' => null,
                        'consecutivo_orden_restaurante' => 0,
                        'comensales' => null,
                    ), array(
                        'id_seccion' => $comandarestaurante[1],
                        'id' => $comandarestaurante[2],
                    ));
                }
            }

            /*Email*/
            if ($_POST['enviarFactura'] != "" && $_POST['enviarFactura'] != false) {
                $get_by_id = $this->ventas->get_by_id($id);
                $almacen = $this->almacenes->get_by_id($get_by_id["almacen_id"]);
                $data = array(
                    'venta' => $this->ventas->get_by_id($id),
                    'detalle_venta' => $this->ventas->get_detalles_ventas($id),
                    'detalle_pago' => $this->ventas->get_detalles_pago($id),
                    'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id, 'pago'),
                    'detalle_pago_multiples_cambio' => $this->ventas->get_detalles_pago_result($id, 'cambio'),
                    'venta_impuestos' => $this->ventas->venta_impuestos($id),
                    'puntos_cliente_factura' => $this->puntos->puntos_acumulados_cliente($id, 'factura'),
                    'puntos_cliente_acumulado' => $this->puntos->puntos_acumulados_cliente($id, 'acumulado'),
                    'data_empresa' => $data_empresa,
                    'data_almacen' => $almacen,
                    'tipo_factura' => $data_empresa['data']['tipo_factura'],
                    'publicidad_vendty' => $this->mi_empresa->obtenerOpcion("publicidad_vendty"),
                    'qr' => false,
                );
                $this->load->library('email');
                $this->email->initialize();
                $this->email->from('no-responder@vendty.net', $data_empresa["data"]["nombre"]);
                if (!empty($data_empresa["data"]["email"])) {
                    $this->email->reply_to($data_empresa["data"]["email"], $data_empresa["data"]["nombre"]);
                }
                $this->email->to($_POST['enviarFactura']);
                $this->email->subject("Recibo No " . $data['venta']['factura'] . " " . $data_empresa["data"]["nombre"]);
                $message = "<center>";
                if ($data_empresa['data']['plantilla'] == 'media_carta') {
                    $message .= $this->load->view('ventas/_imprimemediacarta', array(
                        'data' => $data,
                    ), true);
                } else {
                    $message .= $this->load->view('ventas/imprime', array(
                        'data' => $data,
                    ), true);
                }
                $message .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                if ($_POST['facturacion_electronica']) {
                    $message2 = "<center>";
                    $message2 .= $this->load->view('ventas/factura_electronica', array(
                        'data' => $data,
                    ), true);
                    $message2 .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                    $numero = $data['venta']['factura'];

                    require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array('58', '297'), true, 'UTF-8', false);
                    $pdf->SetTitle("Recibo No " . $data['venta']['factura']);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(2, 0, 2);
                    $pdf->SetHeaderMargin(0);
                    $pdf->SetFooterMargin(0);
                    $pdf->AddPage('P');
                    $pdf->writeHTML($message2, true, false, true, false, '');
                    $pdf->Output("factura-$numero.pdf", 'F');
                    $this->email->attach("factura-$numero.pdf");
                }

                $message .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                if ($_POST['facturacion_electronica']) {
                    $message2 = "<center>";
                    $message2 .= $this->load->view('ventas/factura_electronica', array(
                        'data' => $data,
                    ), true);
                    $message2 .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                    $numero = $data['venta']['factura'];

                    require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array('58', '297'), true, 'UTF-8', false);
                    $pdf->SetTitle("Recibo No " . $data['venta']['factura']);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(2, 0, 2);
                    $pdf->SetHeaderMargin(0);
                    $pdf->SetFooterMargin(0);
                    $pdf->AddPage('P');
                    $pdf->writeHTML($message2, true, false, true, false, '');
                    $pdf->Output("factura-$numero.pdf", 'F');
                    $this->email->attach("factura-$numero.pdf");
                }

                $message .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                if ($_POST['facturacion_electronica']) {
                    $message2 = "<center>";
                    $message2 .= $this->load->view('ventas/factura_electronica', array(
                        'data' => $data,
                    ), true);
                    $message2 .= "</center>Enviado desde <a href='https://vendty.com/' style='text-decorate:none'><b>Vendty.com</b></a>";
                    $numero = $data['venta']['factura'];

                    require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array('58', '297'), true, 'UTF-8', false);
                    $pdf->SetTitle("Recibo No " . $data['venta']['factura']);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(2, 0, 2);
                    $pdf->SetHeaderMargin(0);
                    $pdf->SetFooterMargin(0);
                    $pdf->AddPage('P');
                    $pdf->writeHTML($message2, true, false, true, false, '');
                    $pdf->Output("factura-$numero.pdf", 'F');
                    $this->email->attach("factura-$numero.pdf");
                }

                $this->email->message($message);
                $this->email->send();
            }

            /*Impresion rapida*/
            $get_almacen_by_id = $this->ventas->get_by_id($id);
            $result_caja = $this->caja->get_id_caja_en_cierre_caja(array(
                'id' => $this->session->userdata('caja'),
            ));
            $data_impresion_rapida = array();
            if ((isset($data_empresa['data']['impresion_rapida']) && $data_empresa['data']['impresion_rapida'] == 'si') || (get_option('nueva_impresion_rapida') == 'si')) {
                $logo = "";
                if ($data_empresa["data"]["logotipo"] != "") {
                    if ($this->validImage('https://pos.vendty.com/uploads/' . $data_empresa["data"]["logotipo"])) {
                        $logo = 'https://pos.vendty.com/uploads/' . $data_empresa["data"]["logotipo"];
                    }
                }
                $venta = $this->ventas->get_by_id($id);
                $username = $this->session->userdata('username');
                $vendedor = "";
                $orden = "";
                $vendedor_impresion = get_option('vendedor_impresion');
                $tipo_negocio = get_option('tipo_negocio');

                if ($vendedor_impresion === '1') {
                    $vendedor = $venta['vendedor'];
                } else if ($vendedor_impresion === '2') {
                    $vendedor = $username;
                } else if ($vendedor_impresion === '3') {
                    $vendedor = $username . " - " . $venta['vendedor'];
                }

                if ($tipo_negocio === 'restaurante') {
                    $orden = $venta['consecutivo_orden'] ? $venta['consecutivo_orden'] : $venta['consecutivo_orden_restaurante'];
                }

                $data_impresion_rapida = array(
                    'logo' => $logo,
                    'venta' => $venta,
                    'detalle_venta' => $this->ventas->get_detalles_ventas($id),
                    'detalle_pago' => $this->ventas->get_detalles_pago($id),
                    'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id, 'pago'),
                    'detalle_pago_multiples_cambio' => $this->ventas->get_detalles_pago_result($id, 'cambio'),
                    'detalle_productos' => $_POST['productos'],
                    'venta_impuestos' => $this->ventas->venta_impuestos($id),
                    'puntos_cliente_factura' => $this->puntos->puntos_acumulados_cliente($id, 'factura'),
                    'puntos_cliente_acumulado' => $this->puntos->puntos_acumulados_cliente($id, 'acumulado'),
                    'data_empresa' => $data_empresa,
                    'data_almacen' => $this->almacenes->get_by_id($get_almacen_by_id["almacen_id"]),
                    'vendedor' => $vendedor,
                    'tipo_factura' => $data_empresa['data']['tipo_factura'],
                    'publicidad_vendty' => $this->mi_empresa->obtenerOpcion("publicidad_vendty"),
                    'puntos' => $puntos_leal,
                );

                $this->ventas->add_impresion_rapida($id, $data_impresion_rapida, $get_almacen_by_id["almacen_id"], $result_caja->id_Caja);
                $this->session->set_flashdata('venta_impresion_rapida', custom_lang('sima_bill_created_message', "Has realizado tu venta con exito"));
            }

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'success' => true,
                'id' => $id,
                'impresion_rapida' => $data_empresa['data']['impresion_rapida'],
                'fastPrintJson' => json_encode($data_impresion_rapida),
                'divisionitem' => $divisionitem,
                'factura_electronica' => $_POST['facturacion_electronica'],
                'status_electronic_invoice' => $status_electronic_invoice
            )));
        } else {
            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["clientes"] = $this->clientes->get_all(0);

            // $data["espera_detalles"] = @$this->ventas->get_all_espera_detalles($id1 = 0);

            $data['vendedores'] = $this->vendedores->get_combo_data();

            // Vitrina categorias----------------------------------------------------------- //

            $data['categorias'] = $this->categorias->get_all_categories();

            // ...............................................................................
            // $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
            // $data['forma_pago'] = $this->pagos->get_tipos_pago();

            $data['forma_pago'] = $this->forma_pago->getActiva();
            $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];
            $data['comanda'] = $data_empresa['data']['comanda'];
            $data['plan_separe_tipo_negocio'] = $data_empresa['data']['plan_separe'];
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
            $data["eliminar_producto_comanda"] = $data_empresa['data']['eliminar_producto_comanda'];
            $data['permitir_formas_pago_pendiente'] = $data_empresa['data']['permitir_formas_pago_pendiente'];
            $data['comanda_push'] = $data_empresa['data']['comanda_push'];
            $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
            $data['multiples_vendedores'] = $data_empresa['data']['multiples_vendedores'];
            $data['nit'] = $data_empresa['data']['nit'];
            $data['pais'] = $this->clientes->get_pais();
            $data['plan_puntos'] = $this->puntos->plan_puntos();
            $data['si_no_plan_punto'] = $this->puntos->si_no_plan_punto();
            $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
            $data['grupos'] = $this->grupo->getAll();
            $data['plan_separe'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PLAN_SEPARE);
            $data['puntos'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PUNTOS);
            $data['cantidadProductos'] = $this->productos->cantidadProductos();
            $data['auto_factura'] = $data_empresa['data']['auto_factura'];
            $data['enviar_factura'] = $data_empresa['data']['enviar_factura'];
            $data['auto_pago'] = $data_empresa['data']['auto_pago'];
            $data['sobrecosto_todos'] = $data_empresa['data']['sobrecosto_todos'];
            $data['clientes_cartera'] = $this->miempresa->getCartera();
            // envio del id de la base de datos para el metodo de pago con datafono

            $data['db'] = base64_encode($this->session->userdata('db_config_id'));
            $data['base_datos'] = $this->session->userdata('base_dato');
            $data['aleatorio'] = $this->aleatorio();
            $data['factura_con_mesas'] = $data_empresa['data']['facturar_mesas'];
            $data['datos_mesas_html'] = '';
            $data['simbolo'] = $data_empresa['data']['simbolo'];
            $data['impuesto'] = $this->impuestos->getFisrt();
            $data['facturacion_electronica'] = $this->session->userdata('electronic_invoicing');
            if ($data['factura_con_mesas'] == 'si') {
                $data['datos_mesas_html'] = $this->armar_html_mesas();
            }

            if (!$data['puntos']) {
                unset($data['forma_pago']['Puntos']);
            }

            // Factura estandar --------------------------------------------------------------------------------------

            if ($data_empresa['data']['tipo_factura'] == 'estandar') {
                /*var_dump($data_empresa['data']['tipo_factura'] );*/
                if (getUiVersion() == "v2") { //echo "1";

                    // die("nuevo2");

                    $vistaVenta = 'ventas/nuevoV2';
                } else { //echo "2";
                    $vistaVenta = 'ventas/nuevo';
                }

                if (isset($data_cotizacion)) {
                    $data_cotizacion = $data_cotizacion;
                } elseif (isset($ordenes)) {
                    $data_cotizacion = $orden_final;
                } else {
                    $data_cotizacion = '';
                }

                isset($data_orden) ? $data_orden = $data_orden : $data_orden = '';
                $random = rand();

                // /verificar las licencias
                $administrador = $this->session->userdata('is_admin');
                $db_config_id_user = $this->session->userdata('db_config_id');
                if ($administrador) {
                    $fecha = date('Y-m-d');

                    // $licencia = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,true);
                    // $licenciast = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,false);

                    $licencia = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", true);
                    $licenciast = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", false);
                    $licenciastu = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user", false);
                    $milicencia = 0;
                    $almacenActual = $this->dashboardModel->getAlmacenActual();
                    $milicencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacenActual);
                }

                $data['almacentodos'] = $this->almacenes->getAll();
                if (!isset($milicencia['estado_licencia'])) {
                    $milicencia['estado_licencia'] = 1;
                    $milicencia['fecha_vencimiento'] = isset($fecha) ? $fecha : date('Y-m-d');
                }
                //verificar si aun estoy dentro de los 7 dias adicionales
                $fecha_nueva = $milicencia['fecha_vencimiento'];
                $fecha_nueva = date("Y-m-d", strtotime($fecha_nueva . "+ 7 days"));
                $hoy = date('Y-m-d');
                $vencidadespues = false;
                if ($fecha_nueva < $hoy) {
                    $vencidadespues = true;
                }

                if ((($administrador == 't') || ($administrador == 'a')) && (($milicencia['estado_licencia'] == 15) || ($milicencia['fecha_vencimiento'] < $fecha)) && ($vencidadespues)) {

                    // redirigir a login

                    $data['title'] = "SUSCRIPCIÓN VENCIDA";
                    $data['message'] = "Su suscripci&oacute;n est&aacute; vencida. Si gusta seguir disfutando de Vendty, debe realizar su pago ";
                    $data['message1'] = "";
                    $data['message2'] = "";
                    $data['logout'] = false;
                    $data['mostrar_salir'] = true;
                    $data['licencias'] = $licencia;
                    $data['config'] = true;
                    $data['btnconfig'] = true; //muestra boton de configuraciones =true
                    $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['info_factura_pais'] = $this->clientes->get_pais();
                    $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                    $this->layout->template('login')->show('licenciasvencidas');
                    $html = $this->load->view('licenciasvencidas', $data, true);
                    echo $html;
                    exit;

                } else {

                    //  print_r($data_cotizacion); die();
                    $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $cuentaEstado = $this->newAcountModel->getUsuarioEstado();

                    //Jeisson Rodriguez (17/07/2019)
                    // $data['domiciliarios'] = $this->domiciliarios->get_domiciliarios();
                    $data['domiciliarios'] = $this->domiciliarios->get_domiciliarios_activos();
                    $data['cliente_domicilio'] = $this->clientes->get_termg('', $almacenActual);
                    $data['estado'] = $cuentaEstado["estado"];
                    $random = rand();
                    $this->layout->template('ventas')->css(array(
                        base_url("/public/css/ventas.css?$random"),
                        base_url("/public/css/ventasNewDesign.css?$random"),
                        base_url("/public/fancybox/jquery.fancybox.css"),
                        base_url('public/css/multiselect/multiselect.css'),
                    ))->js(array(
                        base_url("/public/js/ventas.js?$random"),
                        base_url("/public/fancybox/jquery.fancybox.js"),
                        base_url('public/js/plugins/multiselect/jquery.multi-select.js'),
                    ))->show($vistaVenta, array(
                        'data' => $data,
                        'cotizacion' => $data_cotizacion,
                        'orden' => $data_orden,
                        'estadoBD' => $estadoBD,
                    ));
                }
            } else {

                // $data['cod'] = $this->_codigo();
                // Factura clasica -------------------------------------------------------------------------------------------------------
                $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $data['estado'] = $cuentaEstado["estado"];
                $data['impuestos'] = $this->impuestos->get_combo_data_factura();
                $data['forma_pago'] = $this->db->query("SELECT valor_opcion, mostrar_opcion FROM opciones  where nombre_opcion = 'tipo_pago' order by id_opcion asc")->result();
                $this->layout->template('member')->show('ventas/nclasico', array(
                    'data' => $data,
                    'estadoBD' => $estadoBD,
                ));
            }
        }
    }

    public function armar_html_mesas($forma_devolver = '')
    {
        $id_almacen_usuario = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        $secciones = $this->secciones_almacen->get_secciones_almacen(array(
            'id_almacen' => $id_almacen_usuario,
        ));
        $mesas_secciones = array();
        foreach ($secciones as $key => $value) {
            $mesas_seccion = $this->mesas_secciones->get_mesa_secciones(array(
                'id_seccion' => $value->id,
                'activo' => 1,
            ));
            foreach ($mesas_seccion as $key_mesas => $una_mesa) {
                $pedidos_mesa = $this->ventas->get_facturas_espera(array(
                    'id_mesa' => $una_mesa->id,
                ));
                $una_mesa->pedidos_en_mesa = false;
                if (count($pedidos_mesa) >= 1) {
                    $una_mesa->pedidos_en_mesa = true;
                }

                $mesas_secciones[$value->id][] = $una_mesa;
            }
        }
        $data = array();
        $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];

        $html_total = $this->load->view('mesas/listado_mesas_para_venta', array(
            'secciones' => $secciones,
            'mesas_secciones' => $mesas_secciones,
            'data' => $data,
        ), true);
        if ($forma_devolver == 'json') {
            echo json_encode(array(
                'html' => $html_total,
            ));
        } else {
            return $html_total;
        }
    }

    public function nuevo2()
    {

        // echo 'En ventas: '.date('Y-m-d H:i:s');
        // exit();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->session->userdata('caja');
        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }

        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data = array();
        if ($this->input->get('id_cot')) {
            $data_cotizacion = $this->ventas->getDataCotizacionById($this->input->get('id_cot')); //die(var_dump($data["cotizacion"]));
        }

        // die(var_dump($data["cotizacion"]));

        $data['pais'] = $this->pais_provincia->get_pais();

        // APERTURA DE CAJA

        if ($data_empresa['data']['valor_caja'] == 'si') {

            // Si el cierre de caja es automatico

            if ($data_empresa['data']['cierre_automatico'] == '1') {
                $is_admin = $this->session->userdata('is_admin');
                $username = $this->session->userdata('username');
                $db_config_id = $this->session->userdata('db_config_id');
                if ($this->session->userdata('caja') == "" || $this->session->userdata('caja') == false) {
                    $id_user = '';
                    $almacen = '';
                    $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                    foreach ($user as $dat) {
                        $id_user = $dat->id;
                    }

                    $hoy = date("Y-m-d");
                    $query = $this->dbConnection->query('SELECT * FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ');
                    $query->num_rows();
                    if ($query->num_rows() == 1) {
                        $cierre_caja = 0;
                        $cierre = 'SELECT id FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ';
                        $cierre_caja = $this->dbConnection->query($cierre)->row();
                        $this->session->set_userdata('caja', $cierre_caja->id);
                    }

                    if ($query->num_rows() == 0) {
                        redirect(site_url("caja/apertura"));
                    }
                }
            } else { // Si el cierre de caja no es automatico
                $cierre = ' SELECT * FROM cierres_caja ORDER BY id DESC LIMIT 1 ';
                $cierre_caja = $this->dbConnection->query($cierre)->row();
                if ($this->session->userdata('caja') == false) {
                    redirect(site_url("caja/apertura"));
                }

                // Si el ultimo cierre de caja no tiene total de cierre entonces continuamos vendiendo en el

                if (trim($cierre_caja->total_cierre) == "") {
                    $this->session->set_userdata('caja', $cierre_caja->id);
                } else { // de lo contrario, aperturamos caja
                    $is_admin = $this->session->userdata('is_admin');
                    $username = $this->session->userdata('username');
                    $db_config_id = $this->session->userdata('db_config_id');
                    if ($this->session->userdata('caja') == "") {
                        $id_user = '';
                        $almacen = '';
                        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                        foreach ($user as $dat) {
                            $id_user = $dat->id;
                        }

                        $hoy = date("Y-m-d");
                        $query = $this->dbConnection->query('SELECT * FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ');
                        $query->num_rows();
                        if ($query->num_rows() == 1) {
                            $cierre_caja = 0;
                            $cierre = 'SELECT id FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ';
                            $cierre_caja = $this->dbConnection->query($cierre)->row();
                            $this->session->set_userdata('caja', $cierre_caja->id);
                        }

                        if ($query->num_rows() == 0) {
                            redirect(site_url("caja/apertura"));
                        }
                    }
                }
            }
        }

        if (isset($_POST['forma_pago'])) {
            $pago_1 = (isset($_POST['pago_1'])) ? $_POST['pago_1'] : $_POST['forma_pago'];
            $pago_2 = (isset($_POST['pago_2'])) ? $_POST['pago_2'] : $_POST['forma_pago'];
            $pago_3 = (isset($_POST['pago_3'])) ? $_POST['pago_3'] : $_POST['forma_pago'];
            $pago_4 = (isset($_POST['pago_4'])) ? $_POST['pago_4'] : $_POST['forma_pago'];
            $pago_5 = (isset($_POST['pago_5'])) ? $_POST['pago_5'] : $_POST['forma_pago'];
        }

        /* if($this->form_validation->run('facturas') == true){...}*/
        if (isset($_POST['vendedor']) || isset($_POST['vendedor_2'])) {

            // Identifica si una venta fue por POS o por Servicios

            if ($data_empresa['data']['tipo_factura'] != 'clasico') {
                $pago = $_POST['pago'];
                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];
                $tipo_factura = 'estandar';
                $fecha = date('Y-m-d H:i:s');
                $fecha_venc = '';
                $parts = explode('/', $_POST['fecha_v']);
                if (count($parts) == 3) {
                    $fecha_venc = $_POST['fecha_v'] . " " . date('H:i:s');
                } else {
                    $fecha_venc = date('Y-m-d H:i:s');
                }

                $fecha_vencimiento = $fecha_venc;
            } else {
                $pago = array(
                    'valor_entregado' => $_POST['total_venta'],
                    'cambio' => 0,
                    'forma_pago' => $_POST['forma_pago'],
                );
                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];
                $tipo_factura = 'clasico';
                $fecha = $_POST['fecha'] . " " . date('H:i:s');
                $fecha_vencimiento = $_POST['fecha_v'] . " " . date('H:i:s');
            }

            //esrestaurante?
            $comandarestaurante = explode("_", $_POST['comandarestaurante']);
            if ($comandarestaurante[0] == 1) {
                $x = $this->mesas_secciones->get_secciones_mesas_by_id_mesa($comandarestaurante[2]);
                $comensales = $x->comensales;
            }

            $data = array(
                'fecha' => $fecha,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cliente' => $_POST['cliente'],
                'vendedor' => $_POST['vendedor'],
                'vendedor_2' => $_POST['vendedor_2'],
                'usuario' => $this->session->userdata('user_id'),
                'productos' => $_POST['productos'],
                'total_venta' => ($_POST['total_venta'] - (($_POST['total_venta'] * $_POST['descuento_general']) / 100)),
                'pago' => $pago,
                'pago_1' => $pago_1,
                'pago_2' => $pago_2,
                'pago_3' => $pago_3,
                'pago_4' => $pago_4,
                'pago_5' => $pago_5,
                'tipo_factura' => $tipo_factura,
                'nota' => $_POST['nota'],
                'promocion' => $_POST['promocion'],
                'descuento_general' => $_POST['descuento_general'],
                'subtotal_input' => $_POST['subtotal_input'],
                'subtotal_propina_input' => $_POST['subtotal_propina_input'],
                'sobrecostos' => ((isset($_POST['propina'])) ? $_POST['propina'] : 0),
                'tipo_propina' => ((isset($_POST['tipo_propina'])) ? $_POST['tipo_propina'] : null),
                'id_fact_espera' => (isset($_POST['id_fact_espera']) ? $_POST['id_fact_espera'] : ''),
                'sistema' => $data_empresa['data']['sistema'],
                'comensales' => (isset($comensales)) ? $comensales : null,
                'facturacion_electronica' => $_POST['facturacion_electronica'],
            );

            /*Registrar venta*/
            $id = $this->ventas->add($data);

            // guardar evento de primeros pasos venta

            if (!empty($id)) {
                $estadoBD = $this->newAcountModel->getUsuarioEstado();
                if ($estadoBD["estado"] == 2) {
                    $paso = 14;
                    $marcada = $this->primeros_pasos_model->verificar_tareas_realizadas(array(
                        'id_usuario' => $this->session->userdata('user_id'),
                        'db_config' => $this->session->userdata('db_config_id'),
                        'id_paso' => $paso,
                    ));
                    if ($marcada == 0) {
                        $datatarea = array(
                            'id_paso' => $paso,
                            'id_usuario' => $this->session->userdata('user_id'),
                            'db_config' => $this->session->userdata('db_config_id'),
                        );
                        $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                    }
                }
            }

            $data = array(
                'venta' => $this->ventas->get_by_id($id),
                'detalle_venta' => $this->ventas->get_detalles_ventas($id),
                'detalle_pago' => $this->ventas->get_detalles_pago($id),
                'data_empresa' => $data_empresa,
            );
            /*Email*/
            $this->load->library('email');
            $this->email->initialize();
            $this->email->clear();
            $this->email->from($data_empresa["data"]["email"], $data_empresa["data"]["nombre"]);
            $this->email->to('comercial@sistematizamos.com');
            $this->email->subject("Su recibo de compra");
            if ($data_empresa['data']['plantilla'] == 'media_carta') {
                $message = $this->load->view('ventas/_imprimemediacarta', array(
                    'data' => $data,
                ), true);
            } else {
                $message = $this->load->view('ventas/imprime', array(
                    'data' => $data,
                ), true);
            }

            $message = $message . "<br/>Enviado por www.vendty.com";

            // echo $message;

            $this->email->message($message);

            // $this->email->send();

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'success' => true,
                'id' => $id,
            )));
        } else {
            $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
            if (empty($almacenActual)) {
                $almacenActual = $this->dashboardModel->getAlmacenActual();
            }
            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["clientes"] = $this->clientes->get_all(0);
            $data["espera_detalles"] = @$this->ventas->get_all_espera_detalles($id1 = 0, false, $almacenActual);
            $data['vendedores'] = $this->vendedores->get_combo_data();

            // Vitrina categorias----------------------------------------------------------- //

            $data['categorias'] = $this->categorias->get_limit(0);

            // ...............................................................................
            // $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
            // $data['forma_pago'] = $this->pagos->get_tipos_pago();

            $data['forma_pago'] = $this->forma_pago->getActiva();
            $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];
            $data['comanda'] = $data_empresa['data']['comanda'];
            $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
            $data['multiples_vendedores'] = $data_empresa['data']['multiples_vendedores'];
            $data['nit'] = $data_empresa['data']['nit'];
            $data['pais'] = $this->clientes->get_pais();
            $data['plan_puntos'] = $this->puntos->plan_puntos();
            $data['si_no_plan_punto'] = $this->puntos->si_no_plan_punto();
            $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
            $data['plan_separe'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PLAN_SEPARE);
            $data['puntos'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PUNTOS);
            $data['cantidadProductos'] = $this->productos->cantidadProductos();
            $data['auto_factura'] = $data_empresa['data']['auto_factura'];
            $data['auto_pago'] = $data_empresa['data']['auto_pago'];
            $data['sobrecosto_todos'] = $data_empresa['data']['sobrecosto_todos'];
            $data['clientes_cartera'] = $this->miempresa->getCartera();
            if (!$data['puntos']) {
                unset($data['forma_pago']['Puntos']);
            }

            // Factura estandar --------------------------------------------------------------------------------------

            if ($data_empresa['data']['tipo_factura'] == 'estandar') {
                /*var_dump($data_empresa['data']['tipo_factura'] );*/
                if (getUiVersion() == "v2") { //echo "1";
                    $vistaVenta = 'ventas/nuevoV3';
                } else { //echo "2";
                    $vistaVenta = 'ventas/nuevo';
                }

                if (isset($data_cotizacion)) {
                    $data_cotizacion = $data_cotizacion;
                } else {
                    $data_cotizacion = '';
                }

                $this->layout->template('ventas')->css(array(
                    base_url("/public/css/ventas.css?$random"),
                    base_url("/public/fancybox/jquery.fancybox.css"),
                    base_url('public/css/multiselect/multiselect.css'),
                ))->js(array(
                    base_url("/public/js/ventas2.js?v2.5"),
                    base_url("/public/fancybox/jquery.fancybox.js"),
                    base_url('public/js/plugins/multiselect/jquery.multi-select.js'),
                ))->show($vistaVenta, array(
                    'data' => $data,
                    'cotizacion' => $data_cotizacion,
                ));
            } else {

                // $data['cod'] = $this->_codigo();
                // Factura clasica -------------------------------------------------------------------------------------------------------

                $data['impuestos'] = $this->impuestos->get_combo_data_factura();
                $data['forma_pago'] = $this->db->query("SELECT valor_opcion, mostrar_opcion FROM opciones  where nombre_opcion = 'tipo_pago' order by id_opcion asc")->result();
                $this->layout->template('member')->show('ventas/nclasico', array(
                    'data' => $data,
                ));
            }
        }
    }

    public function espera($id = null)
    {
        $data = array();
        $id = '';
        $fecha = '';
        $fecha_vencimiento = '';
        $pago = array();
        $tipo_factura = '';
        $tipo_venta_espera = 'espera';
        $pago = $_POST['pago'];
        $tipo_factura = 'estandar';
        $fecha = date('Y-m-d H:i:s');
        $fecha_vencimiento = date('Y-m-d H:i:s');
        $datos_mesa = array();
        if (isset($_POST['id_mesa_seleccionada']) && $_POST['id_mesa_seleccionada'] > 0) {
            $datos_mesa = $this->mesas_secciones->get_mesa_secciones(array(
                'id' => $_POST['id_mesa_seleccionada'],
            ));
            $tipo_venta_espera = 'mesa_espera';
        }

        $arreglo_productos = array();
        if (isset($_POST['productos'])) {
            $arreglo_productos = $_POST['productos'];
        }

        $data = array(
            'fecha' => $fecha,
            'fecha_vencimiento' => $fecha_vencimiento,
            'cliente' => $_POST['cliente'],
            'vendedor' => $_POST['vendedor'],
            'usuario' => $this->session->userdata('user_id'),
            'productos' => $arreglo_productos,
            'total_venta' => $_POST['total_venta'],
            'pago' => $pago,
            'tipo_factura' => $tipo_factura,
            'nota' => $_POST['nota'],
            'sobrecostos' => $_POST['sobrecostos'],
            'activo' => '1',
            'mesa' => @$_POST['id_mesa_seleccionada'],
            'datos_mesa' => $datos_mesa,
        );
        /*Registrar venta*/
        $resultado_insert = $this->ventas->espera($data, $tipo_venta_espera);
        $nombre_factura_espera = '';
        if (is_array($resultado_insert)) {
            $id = $resultado_insert['id'];
            $nombre_factura_espera = $resultado_insert['nombre_venta'];
        } else {
            $id = $resultado_insert;
        }

        $creada_comanda = false;
        if ($tipo_venta_espera == 'mesa_espera') {

            // como es mesa en espera vamos a insertar la comanda de una si es solo un usuario

            $usuarios = $this->comanda_model->get_usuarios_comanda();
            if (count($usuarios) == 1) {
                $data_comanda = array(
                    'id_usuario' => $usuarios[0]->id,
                    'id_factura_espera' => $id,
                    'estado' => 0,
                );
                $this->comanda_model->insertar_comanda($data_comanda);
                $creada_comanda = true;
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => true,
            'id' => $id,
            'nombre_factura_espera' => $nombre_factura_espera,
            'creada_comanda' => $creada_comanda,
        )));
    }

    public function comanda($id = null)
    {
        $data = array();
        $id = '';
        $fecha = '';
        $fecha_vencimiento = '';
        $pago = array();
        $tipo_factura = '';
        $pago = $_POST['pago'];
        $tipo_factura = 'estandar';
        $fecha = date('Y-m-d H:i:s');
        $fecha_vencimiento = date('Y-m-d H:i:s');
        $data = array(
            'fecha' => $fecha,
            'fecha_vencimiento' => $fecha_vencimiento,
            'cliente' => $_POST['cliente'],
            'vendedor' => $_POST['vendedor'],
            'usuario' => $this->session->userdata('user_id'),
            'productos' => $_POST['productos'],
            'total_venta' => $_POST['total_venta'],
            'pago' => $pago,
            'tipo_factura' => $tipo_factura,
            'nota' => $_POST['nota'],
            'sobrecostos' => $_POST['sobrecostos'],
            'activo' => '-1',
            'factura' => $_POST['id_fact_espera_nombre'],
        );
        /*Registrar venta*/
        $id = $this->ventas->comanda($data, 'comanda');
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => true,
            'id' => $id,
        )));
    }

    public function comanda_imprimir($id)
    {
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $get_by_id = $this->ventas->get_by_id_comanda($id);
        $data = array(
            'venta' => $this->ventas->get_by_id_comanda($id),
            'detalle_venta' => $this->ventas->get_detalles_ventas_comanda($id),
            'data_empresa' => $data_empresa,
            'tipo_factura' => $data_empresa['data']['tipo_factura'],
        );
        $this->layout->template('ajax')->show('ventas/_imprime_comanda_primer', array(
            'data' => $data,
        ));
    }

    public function getFacturaEsperaNota()
    {
        $id = $this->input->get('id', true);
        $result = $this->ventas->getFacturaEsperaNota($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function setFacturaEsperaNota()
    {
        $id = $this->input->get('id', true);
        $nota = $this->input->get('nota', true);
        $result = $this->ventas->setFacturaEsperaNota($id, $nota);
    }

    public function detalles_espera()
    {
        $this->secciones_almacen->check_existe_tabla_secciones();
        $result = array();
        $id = $this->input->get('id');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if (empty($almacenActual)) {
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $con_mesas = false;
        if ($data_empresa['data']['facturar_mesas'] == "si") {
            $con_mesas = true;
        }

        $result = $this->ventas->get_all_espera_detalles($id, $con_mesas, $almacenActual);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function factura_espera()
    {
        $result = array();
        $id = $this->input->get('id', true);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $con_mesas = false;
        if ($data_empresa['data']['facturar_mesas'] == "si") {
            $con_mesas = true;
        }

        $result = $this->ventas->get_all_espera_factura($id, $con_mesas);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function factura_espera_nombre()
    {
        $result = array();
        $nom = $this->input->get('nom', true);
        $id = $this->input->get('id', true);
        $result = $this->ventas->get_all_espera_factura_nombre($id, $nom);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function espera_actualizar($id = null)
    {
        $data = array();
        $id = '';
        $id = $_POST['id'];
        $id_respuesta = '';
        if (isset($_POST['productos'])) {
            $data = array(
                'productos' => $_POST['productos'],
            );
            /*Registrar venta*/
            $id_respuesta = $this->ventas->espera_actualizar($data, $id);
            $comanda_creada = $this->comanda_model->get_una_comanda(array(
                'id_factura_espera' => $id,
            ));
            if (count($comanda_creada) > 0) {
                $this->comanda_model->sendPushToUsers();
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($id_respuesta));
    }

    // eliminar factura en espera

    public function factura_espera_eliminar()
    {
        $result = array();
        $id = $this->input->get('id', true);

        // $id = $this->input->get('id', TRUE);

        $result = $this->ventas->eliminar_factura($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => true,
        )));
    }

    public function eliminar_comanda_temporal()
    {
        $result = array();
        $id = $this->input->get('id', true);
        $result = $this->ventas->eliminar_comanda_temporal($id);
    }

    public function factura_espera_ultimo()
    {
        $result = array();
        $id = $this->input->get('id', true);
        $result = $this->ventas->get_all_espera_factura_ultimo($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function actualizar($id = null)
    {
        /*var_dump($this->db->get('venta'));  */
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data = array(
            'venta' => $this->ventas->get_by_id($id),
            'detalle_venta' => $this->ventas->get_detalles_ventas($id),
            'detalle_pago' => $this->ventas->get_detalles_pago($id),
            'data_empresa' => $data_empresa,
            'tipo_factura' => $data_empresa['data']['tipo_factura'],
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
                'user_id' => $this->session->userdata('user_id'),
                'fecha' => date('Y-m-d'),
                'almacen_id' => $_POST['almacen'],
                'tipo_movimiento' => 'entrada_compra',
                'total_inventario' => $_POST['input_total_siva'],
                'proveedor_id' => $_POST['proveedor'],
                'codigo_factura' => $_POST['id'],
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
                    'descuento' => $descuento[$i],
                    'almacen_id' => $_POST['almacen'],
                );
                $this->ventas->actualizar_venta($data, $id);
                $i++;
            }

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));
            redirect("ventas/index/");
        }

        // agregar producto

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
                'precio_venta' => $precio,
                'almacen_id' => $_POST['almacen'],
            );
            $this->ventas->agregar_actualizar_venta($data, $id);
            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));
            redirect("ventas/actualizar/" . $id);
        }

        //   $data['cod'] = $this->_codigo();
        // Factura clasica -------------------------------------------------------------------------------------------------------

        $data['impuestos'] = $this->impuestos->get_combo_data_factura();
        $this->layout->template('member')->css(array(
            base_url("/public/css/stylesheets.css"),
            base_url('public/css/multiselect/multiselect.css'),
        ))->show('ventas/actualizar', array(
            'data' => $data,
            'id' => $id,
        ));
    }

    public function eliminar_producto($venta, $id, $producto_id, $prod, $cant, $alm)
    {
        $this->ventas->eliminar_producto_actualizar($venta, $id, $producto_id, $prod, $cant, $alm);
        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha quitado correctamente el producto"));
        redirect("ventas/actualizar/" . $venta);
    }

    public function _codigo()
    {
        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);
        $last_numero_factura = $this->miempresa->last_numero_factura();
        $numero_factura = $this->miempresa->get_numero_factura();
        $prefijo_factura = $this->miempresa->get_prefijo_factura();
        $cod = $this->facturas->get_max_cod();
        $new_cod = "";
        if ($cod == '') {
            if ($numero_factura != $last_numero_factura) {
                $this->miempresa->update_last_numero_factura($numero_factura);
            }

            $dig = ((int) $numero_factura);
            $ceros = (6 - strlen($dig));
            $new_cod = str_repeat("0", $ceros) . $dig;
            return $prefijo_factura . $new_cod;
        } else {
            if ($numero_factura != $last_numero_factura) {
                $this->miempresa->update_last_numero_factura($numero_factura);
                $cod = $numero_factura;
            } else {
                $cod = (int) $cod + 1;
            }

            $dig = ((int) $cod);
            $ceros = (6 - strlen($dig));
            $new_cod = str_repeat("0", $ceros) . $dig;
            return $prefijo_factura . $new_cod;
        }
    }

    public function pendiente()
    {
        if ($_POST) {
            $data = array(
                'cliente' => $_POST['cliente']['identificacion'],
                'vendedor' => $_POST['vendedor'],
                'usuario' => $this->session->userdata('user_id'),
                'productos' => $_POST['productos'],
                'total_venta' => $_POST['total_venta'],
                'pago' => $_POST['pago'],
            );
            /*Registrar venta*/
            $id = $this->ventas->pendiente($data);
            echo "pendiente success = " . $id;
        }
    }

    public function get_ajax_data()
    {
        $this->ventas->agregar_columna_timbrado_venta();
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data()));
    }

    public function caja_abierta()
    {
        $band = 0;
        $data_empresa = $this->mi_empresa->get_data_empresa();

        //verifico si la caja esta abierta
        //verifico si hay caja abierta y no la tengo en session
        //verifico si hay una caja abierta para el usuario
        //verifico si hay cierre automatico
        if ($data_empresa['data']['valor_caja'] == 'si') {
            // Si el cierre de caja es automatico
            if ($data_empresa['data']['cierre_automatico'] == '1') {
                $hoy = date("Y-m-d");
                $where = array('id_Usuario' => $this->session->userdata('user_id'), 'fecha' => $hoy);
            } else {
                $where = array('id_Usuario' => $this->session->userdata('user_id'));
            }

            $orderby_cierre = "fecha desc, hora_apertura desc";
            $limit_cierre = "1";
            $cierre_caja = $this->caja->get_id_caja_en_cierre_caja($where, $orderby_cierre, $limit_cierre);

            if ((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))) {
                $this->session->set_userdata('caja', $cierre_caja->id);
                $band = 1;
            } else {
                $this->session->unset_userdata('caja');
                $band = 0;
            }
        } else {
            $band = 1;
        }
        return $band;
    }

    public function editar($id)
    {
        /*var_dump($this->db->get('venta'));  */
        $this->ventas->edit($id);
        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["clientes"] = $this->clientes->get_all(0);
        $data['vendedores'] = $this->vendedores->get_combo_data();
        $data['forma_pago'] = $this->pagos->get_tipos_pago();
        $random = rand();
        $this->layout->template('ventas')->css(array(
            base_url("/public/css/ventas.css?$random"),
            base_url("/public/fancybox/jquery.fancybox.css"),
            base_url('public/css/multiselect/multiselect.css'),
        ))->js(array(
            base_url("/public/js/ventas.js?$random"),
            base_url("/public/fancybox/jquery.fancybox.js"),
            base_url('public/js/plugins/multiselect/jquery.multi-select.js'),
        ))->show('ventas/nuevo', array(
            'data' => $data,
        ));
    }

    public function anular()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();

        if ($band == 1) {
            $data = array(
                'id' => $_POST['venta_id'],
                'usuario' => $this->session->userdata('user_id'),
                'motivo' => $_POST['motivo'],
            );
            $m = $this->ventas->anular($data);
            $mensaje = "Se ha anulado correctamente";
            $mensaje .= $m;
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', $mensaje));

        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar este proceso'));
            }
        }
        redirect("ventas/index");
    }

    public function index($estado = 0)
    {
        // actualizar tabla clientes
        $this->clientes->actualizarTabla();
        $action = "ventas/index";
        if ($estado == -1) {
            $action = "ventas/ventas_anuladas";
        }

        $codigo_de_barras = $this->almacenes->verificar_modulo_habilitado($this->user, self::ATRIBUTOS);
        $caja = '';
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $caja = $data_empresa['data']['valor_caja'];
        $data['plan_separe'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PLAN_SEPARE);
        $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));

        // -----------------------------------------------------------
        // Si el cierre de caja no es automatico
        // -----------------------------------------------------------

        if ($data_empresa['data']['cierre_automatico'] == '0') {
            $cierre = ' SELECT * FROM cierres_caja where id_usuario = ' . $this->session->userdata('user_id') . ' ORDER BY fecha DESC, hora_apertura DESC LIMIT 1 ';
            $cierre_caja = $this->dbConnection->query($cierre)->row();
            if (count($cierre_caja) != 0) {
                // Si el ultimo cierre de caja no tiene total de cierre entonces continuamos vendiendo en el
                if (trim($cierre_caja->total_cierre) == "") {
                    $this->session->set_userdata('caja', $cierre_caja->id);
                }
            }
        }

        // -----------------------------------------------------------

        $random = rand();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $ocp = $this->opciones->getNombre('nueva_impresion_rapida');
        $impresion_rapida = $ocp['valor_opcion'];
        $this->layout->template('member')->css(array(
            base_url("/public/css/ventas.css?$random"),
            base_url("/public/fancybox/jquery.fancybox.css"),
        ))
        /*->js(array(base_url("/public/js/ventas.js?v=2.91"), base_url("/public/fancybox/jquery.fancybox.js")))*/->js(array(
            base_url("/public/js/ventas.js?$random"),
            base_url("/public/fancybox/jquery.fancybox.js"),
        ))->show($action, array(
            'caja' => $caja,
            'codigo_de_barras' => $codigo_de_barras,
            'data' => $data,
            'impresion_rapida' => $impresion_rapida,
        ));
    }

    public function ventas_anuladas()
    {
        $this->index(-1);
    }

    public function get_ajax_data_anuladas()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data_anuladas(-1)));
    }

    public function excel_data_anuladas()
    {
        $ventas_anuladas = $this->ventas->get_ajax_data_anuladas(-1);
        $reporte = $this->load->library('phpexcel');
        $reporte = new PHPExcel();
        $reporte->setActiveSheetIndex(0);
        $reporte->getActiveSheet()->setCellValue('A1', 'Fecha');
        $reporte->getActiveSheet()->setCellValue('B1', 'Usuario');
        $reporte->getActiveSheet()->setCellValue('C1', 'Factura');
        $reporte->getActiveSheet()->setCellValue('D1', 'Motivo');
        $reporte->getActiveSheet()->setCellValue('E1', 'Cliente');
        $reporte->getActiveSheet()->setCellValue('F1', 'Fecha anulacion');
        $reporte->getActiveSheet()->setCellValue('G1', 'Total venta');
        $reporte->getActiveSheet()->setCellValue('H1', 'Almacen');
        $row = 2;
        foreach ($ventas_anuladas['aaData'] as $value) {
            $reporte->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $reporte->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $reporte->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $reporte->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $reporte->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $reporte->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $reporte->getActiveSheet()->setCellValueExplicit('G' . $row, $value[6]);
            $reporte->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Ventas anuladas.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($reporte, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output');
    }

    public function imprime_orden()
    {
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $zona = $this->uri->segment(3);
        $mesa = $this->uri->segment(4);
        if ($zona != '' && $mesa != '') {

            // buscamos la orden

            $almacenActual = $this->dashboardModel->getAlmacenActual();
            $ordenes = $this->ordenes->getDataOrdenByMesa($zona, $mesa, $almacenActual);
            $tmesa = $this->mesas_secciones->get_una_mesa_by(array('id' => $mesa));
            $tzona = $this->secciones_almacen->get_una_seccion_by(array('id' => $zona));
            if (count($ordenes)) {
                $datos = array(
                    'data_empresa' => $data_empresa,
                    'zona' => $zona,
                    'nombrezona' => $tzona->nombre_seccion,
                    //'mesa' => $mesa,
                    'nombremesa' => $tmesa->nombre_mesa,
                );
                $data = array();
                $counter = 0;
                foreach ($ordenes as $orden) {
                    $data_orden[$counter]['id_cliente'] = $orden->id_cliente;
                    $data_orden[$counter]['nombre_comercial'] = $orden->nombre_comercial;
                    $data_orden[$counter]['id'] = $orden->id;
                    $data_orden[$counter]['order_producto'] = $orden->order_producto;
                    $data_orden[$counter]['producto'] = $this->productos->get_by_id($orden->order_producto);

                    // $data->order_modificacion = $orden->order_modificacion;

                    $data_orden[$counter]['zona'] = $orden->zona;
                    $data_orden[$counter]['mesa_id'] = $orden->mesa_id;
                    $data_orden[$counter]['estado'] = $orden->estado;
                    $data_orden[$counter]['created_at'] = $orden->created_at;
                    $data_orden[$counter]['update_at'] = $orden->update_at;
                    $data_orden[$counter]['cantidad'] = $orden->cantidad;
                    $data_orden[$counter]['fk_id_producto'] = $orden->fk_id_producto;
                    $data_orden[$counter]['nombre'] = $orden->nombre;
                    $data_orden[$counter]['precio'] = $orden->precio;
                    $data_orden[$counter]['codigo'] = $orden->codigo;
                    $data_orden[$counter]['descripcion_d'] = $orden->descripcion_d;
                    $data_orden[$counter]['impuesto'] = $orden->impuesto;
                    $data_orden[$counter]['monto_iva'] = $orden->monto_iva;
                    $data_orden[$counter]['monto'] = $orden->monto;

                    // $datos[$counter]  = $data_orden;

                    if (is_array(json_decode($orden->order_adiciones))) {

                        // Verificamos las adiciones realizadas

                        $adiciones = json_decode($orden->order_adiciones);
                        $counter_adiciona = 0;
                        foreach ($adiciones as $adicion) {

                            // $data_orden = new stdClass();
                            // $counter++;

                            $data_adicion = $this->productos->getAdicionByid($orden->order_producto, $adicion);
                            if (!empty($data_adicion)) {
                                $id_producto = $data_adicion[0]['id_adicional'];

                                // obtenemos el producto asociado

                                $producto = $this->productos->get_by_id($id_producto);
                                $data_orden[$counter]['adicional'][$counter_adiciona]['id_cliente'] = $orden->id_cliente;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['nombre_comercial'] = $orden->nombre_comercial;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['id'] = $orden->id;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['order_producto'] = $id_producto;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['producto'] = $this->productos->get_by_id($id_producto);

                                // $data->order_adiciones = $orden->order_adiciones;
                                // $data->order_modificacion = $orden->order_modificacion;

                                $data_orden[$counter]['adicional'][$counter_adiciona]['zona'] = $orden->zona;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['mesa_id'] = $orden->mesa_id;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['estado'] = $orden->estado;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['created_at'] = $orden->created_at;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['update_at'] = $orden->update_at;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['cantidad'] = $data_adicion[0]['cantidad'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['fk_id_producto'] = $orden->fk_id_producto;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['nombre'] = $producto['nombre'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['precio'] = $data_adicion[0]['precio'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['codigo'] = $producto['codigo'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['descripcion_d'] = $producto['descripcion'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['impuesto'] = $orden->impuesto;
                                $data_orden[$counter]['adicional'][$counter_adiciona]['monto_iva'] = $data_adicion[0]['precio'];
                                $data_orden[$counter]['adicional'][$counter_adiciona]['monto'] = $data_adicion[0]['precio'];

                                // $orden_final[$counter] = $data_orden;

                                $counter_adiciona++;
                            }
                        }
                    }

                    if (is_array(json_decode($orden->order_modificacion))) {

                        // Verificamos las adiciones realizadas

                        $modificaciones = json_decode($orden->order_modificacion);
                        $counter_modifica = 0;
                        foreach ($modificaciones as $modificacion) {
                            $data_orden[$counter]['modificacion'][$counter_modifica]['nombre'] = $modificacion;
                            $counter_modifica++;
                        }
                    }

                    $counter++;
                }

                $datos['orden'] = $data_orden;
            }
        }

        $this->layout->template('ajax')->show('ventas/imprime_orden', array(
            'data' => $datos,
        ));
    }

    public function imprimir($id)
    {
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        // crear la opcion si no existe

        $this->mi_empresa->crearOpcion("publicidad_vendty", 1);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $get_by_id = $this->ventas->get_by_id($id);
        $username = '';
        $user = $this->db->query("SELECT username FROM users where id = '" . $get_by_id["usuario_id"] . "'")->result();
        
        foreach ($user as $dat) {
            $username = $dat->username;
        }

        $almacen = $this->almacenes->get_by_id($get_by_id["almacen_id"]);

        $resolution = null;

        if (!is_null($get_by_id['resolution_history_id'])) {
            if (!is_null($get_by_id["resolution_history_id"])) {
                $resolution = $this->resolution_history->getById($get_by_id["resolution_history_id"]);
                $get_by_id['resolucion_factura'] = $resolution->resolution;
                $almacen['resolucion_factura'] = $resolution->resolution;
                $almacen['numero_fin'] = $resolution->limmit;
                $almacen['prefijo'] = $resolution->prefix;
            }
        }

        $data = array(
            'venta' => $get_by_id,
            'detalle_venta' => $this->ventas->get_detalles_ventas($id),
            'detalle_pago' => $this->ventas->get_detalles_pago($id),
            'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id, 'pago'),
            'detalle_pago_multiples_cambio' => $this->ventas->get_detalles_pago_result($id, 'cambio'),
            'venta_impuestos' => $this->ventas->venta_impuestos($id),
            'puntos_cliente_factura' => $this->puntos->puntos_acumulados_cliente($id, 'factura'),
            'puntos_cliente_acumulado' => $this->puntos->puntos_acumulados_cliente($id, 'acumulado'),
            'data_empresa' => $data_empresa,
            'data_almacen' => $almacen,
            'username' => $username,
            'trilla' => false,
            'tipo_factura' => $data_empresa['data']['tipo_factura'],
            'publicidad_vendty' => $this->mi_empresa->obtenerOpcion("publicidad_vendty"),
            'numero' => $this->mi_empresa->obtenerOpcion("numero"),
        );

        $lang = 'es';
    
        if ($data_empresa['data']['plantilla'] == 'ticket_decimals') {
            $lang = 'en';
        }

        if ($data_empresa['data']['plantilla'] == 'ticket_decimales' || $data_empresa['data']['plantilla'] == 'ticket_decimals') {
            $data['trilla'] = true;
            $this->layout->template('ajax')->show('ventas/imprime', array(
                'data' => $data,
                'lang' => $lang,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_honduras') {
            $data['trilla'] = true;
            $this->layout->template('ajax')->show('ventas/imprime_ticket_honduras', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_decimales_forma_pendiente') {
            $data['trilla'] = true;
            $this->layout->template('ajax')->show('ventas/imprime', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_decimales_simple') {
            $data['trilla'] = true;
            $this->layout->template('ajax')->show('ventas/imprime_decimales', array(
                'data' => $data,
                'lang' => $lang,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'media_carta') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_imei') {
            $i = 0;
            $data['trilla'] = true;

            foreach ($data["detalle_venta"] as $detalle) {
                $venta_id = $detalle["venta_id"];
                $detalle_venta_id = $detalle["id"];
                $producto_id = $detalle["producto_id"];
                $imei = $this->ventas->get_imei_by_venta($venta_id, $detalle_venta_id, $producto_id);

                if ($imei != "") {
                    $data["detalle_venta"][$i]["imei"] = $imei["serial"];
                } else {
                    $data["detalle_venta"][$i]["imei"] = "";
                }

                $i++;
            }

            $this->layout->template('ajax')->show('ventas/_imprime_imei', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'general') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'general_2') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'media_carta_2') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'media_carta_3') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta3', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta4', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_decimales') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacartadecimales', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_logo_redondo') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_redondo', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_con_descuento') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_descuento_total', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias_decimales') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias_decimales', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_completa_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles_completa', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_codibarras') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_codigo_barras_media', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_1') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_1', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_2') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq_discriminado_iva') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq_discriminado_iva', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_impresora_factura') {
            $this->layout->template('ajax')->show('ventas/_imprimefacturamatricial', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_factura_clasica') {
            $this->layout->template('ajax')->show('ventas/_imprime_clasica', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_factura_clasica_descuento_total') {
            $this->layout->template('ajax')->show('ventas/_imprime_clasica_descuento_total', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_factura_clasica2') {
            $this->layout->template('ajax')->show('ventas/_imprime_clasica2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_ticket_58mm') {
            $this->layout->template('ajax')->show('ventas/imprime_ticket_58mm', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_productos_atributos') {
            $this->layout->template('ajax')->show('ventas/imprime_ticket_atributos', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_2') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_58N') {
            $this->layout->template('ajax')->show('ventas/_imprime_58N2', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_58N3') {
            $this->layout->template('ajax')->show('ventas/_imprime_58N3', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_internacional') {
            $this->layout->template('ajax')->show('ventas/_imprimeInternacional', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_internacional_precioiva') {
            $this->layout->template('ajax')->show('ventas/_imprimeInternacional_precioiva', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_promocion') {
            $promocion = $this->promociones->getId($data['venta']['promocion']);

            if (!empty($promocion)) {
                $promocion = $promocion['nombre'];
            } else {
                $promocion = "";
            }

            $this->layout->template('ajax')->show('ventas/_imprimePromocion', array(
                'data' => $data,
                'promocion' => $promocion,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_promocion_nuevo') {
            $promocion = $this->promociones->getId($data['venta']['promocion']);

            if (!empty($promocion)) {
                $promocion = $promocion['nombre'];
            } else {
                $promocion = "";
            }

            $this->layout->template('ajax')->show('ventas/_imprimePromocionNuevo', array(
                'data' => $data,
                'promocion' => $promocion,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_promocion_decimal') {
            $promocion = $this->promociones->getId($data['venta']['promocion']);

            if (!empty($promocion)) {
                $promocion = $promocion['nombre'];
            } else {
                $promocion = "";
            }

            $this->layout->template('ajax')->show('ventas/_imprimePromocionDecimal', array(
                'data' => $data,
                'promocion' => $promocion,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_atributos_nuevo') {
            $this->layout->template('ajax')->show('ventas/imprime_ticket_atributos_nuevo', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_distribuidor') {
            $this->layout->template('ajax')->show('ventas/imprime_dist', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_dist_att') {
            $this->layout->template('ajax')->show('ventas/imprime_dist_att', array(
                'data' => $data,
            ));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_base_impuesto') {
            $this->layout->template('ajax')->show('ventas/imprime_base_impuesto_total', array(
                'data' => $data,
                'lang' => $lang,
            ));
        } else {
            $this->layout->template('ajax')->show('ventas/imprime', array(
                'data' => $data,
            ));
        }
    }

    public function guia_despacho($id)
    {
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data = array(
            'venta' => $this->ventas->get_by_id($id),
            'detalle_venta' => $this->ventas->get_detalles_ventas($id),
            'detalle_pago' => $this->ventas->get_detalles_pago($id),
            'data_empresa' => $data_empresa,
            'tipo_factura' => $data_empresa['data']['tipo_factura'],
        );
        $this->layout->template('ajax')->show('ventas/_imprimemediacartaguiadespacho', array(
            'data' => $data,
        ));
    }

    public function enviar_email()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $empresa = $this->miempresa->get_data_empresa();
        $id = $this->input->post('venta_id_ven', true);
        $cuerpo_correo = $this->input->post('cuerpo_correo', true);
        $data = array(
            'venta' => $this->ventas->get_by_id($id),
            'detalle_venta' => $this->ventas->get_detalles_ventas($id),
            'detalle_pago' => $this->ventas->get_detalles_pago($id),
            'venta_impuestos' => $this->ventas->venta_impuestos($id),
            //'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id) ,
            'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id, 'pago'),
            'detalle_pago_multiples_cambio' => $this->ventas->get_detalles_pago_result($id, 'cambio'),
            'data_empresa' => $empresa,
            'puntos_cliente_factura' => $this->puntos->puntos_acumulados_cliente($id, 'factura'),
            'puntos_cliente_acumulado' => $this->puntos->puntos_acumulados_cliente($id, 'acumulado'),
        );
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', $empresa["data"]["nombre"]);
        if (!empty($data_empresa["data"]["email"])) {
            $this->email->reply_to($data_empresa["data"]["email"], $data_empresa["data"]["nombre"]);
        }
        $this->email->to($data['venta']['cliente_email']);
        $this->email->subject($empresa["data"]['titulo_venta'] . ':' . $data['venta']['factura']);
        if (empty($data['venta']['cliente_email']) or is_null($data['venta']['cliente_email']) or $data['venta']['cliente_email'] == "") {
            $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "El cliente de esta venta no tiene asociado correo electronico, no se puede enviar la factura"));
            redirect("ventas/index");
        }

        /*die(); */
        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");
        $dire = $empresa["data"]['direccion'];
        $telef = $empresa["data"]['telefono'];
        $email = $empresa["data"]['email'];
        $cafac = $empresa["data"]['cabecera_factura'];
        $terminos_condiciones = $empresa["data"]['terminos_condiciones'];
        $nit = $empresa["data"]['nit'];
        $resol = $empresa["data"]['resolucion'];
        $tele = $empresa["data"]['telefono'];
        $dire = $empresa["data"]['direccion'];
        $web = $empresa["data"]['web'];
        $img = base_url("uploads/{$empresa['data']['logotipo']}");
        $fech = date("d/m/Y", strtotime($data['venta']['fecha']));
        $numero = $data['venta']['factura'];
        $nif_cif = $data['venta']['nif_cif'];
        $nomcomercial_cli = $data['venta']['nombre_comercial'];
        $direccion_cli = $data['venta']['cliente_direccion'];
        $telefono_cli = $data['venta']['cliente_telefono'];
        $html = '
        ' . $cuerpo_correo . '
        <br />
         <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">
                         <tbody><tr>
                                 <td align="center" valign="top" style="border-collapse: collapse;">

 <table border="0" cellpadding="10" cellspacing="0" width="430"  style="background-color: #FFFFFF; border: 1px solid #DDDDDD;">
                                                        <tbody><tr>

                                                                <td valign="top"  align="center"  class="preheaderContent" style="border-collapse: collapse;">

        <div id="ticket_header">
';
        if (!empty($img)) {
            if ($nit != '900590001-2' && $nit != '6466096-9') {
                $html .= '<div align="center" style="margin-top: 5px;"><img src="' . $img . '" width="150" border="0" /></div>';
            }

            if ($nit == '900590001-2' || $nit == '6466096-9') {
                $html .= ' <div align="center" style="margin-top: 2px;"><img src="' . $img . '" width="65" border="0" /></div>';
            }
        }

        $html .= '<div id="company_name"><B>' . $empresa["data"]["nombre"] . '</B></div>';
        if ($empresa["data"]['resolucion_factura_estado'] == 'si') {
            $html .= '<div id="company_resolucion">' . $data["venta"]["resolucion_factura"] . '</div>';
            $html .= '<div id="company_nit">Nit:' . $data["venta"]["nit"] . '</div>';
        } else {
            $html .= ' <div id="company_resolucion">' . $empresa["data"]["resolucion"] . '</div>';
            $html .= '  <div id="company_nit">Nit:' . $empresa["data"]["nit"] . '</div>';
        }

        $html .= '<div id="heading"> ' . $cafac . '</div>';
        $html .= '<div id="company_almacen">Almacen:' . $data["venta"]["nombre"] . '</div>

                <table id="ticket_company" align="center">

                        <tr>

                                <td style="width:65%;text-align: left;">' . $data["venta"]["direccion"] . '</td>

                                <td style="width:35%;text-align: right;">' . $data["venta"]["telefono"] . '</td>

                        </tr>

                </table>

                <table id="ticket_factura" align="center">

                        <tr>

                                <td style="width:45%;text-align: left;">Factura de venta:' . $data["venta"]["factura"] . '</td>

                                <td style="width:55%;text-align: right;">Fecha:' . $data["venta"]["fecha"] . '</td>

                        </tr>

                </table>

                <div id="customer">Cliente:' . $data['venta']["tipo_identificacion"] . ': ' . $data["venta"]["nif_cif"] . ' </div>


                <div id="customer">Tel&eacute;fono:' . $data["venta"]["cliente_telefono"] . '</div>';
        $username = $this->session->userdata('username');
        if ($data['data_empresa']['data']['vendedor_impresion'] == '1') {
            $html .= ' <div id="seller">Vendedor: ' . $data["venta"]["vendedor"] . '</div>';
        }

        if ($data['data_empresa']['data']['vendedor_impresion'] == '2') {
            $html .= ' <div id="seller">Vendedor: ' . $data["username"] . ' </div>';
        }

        if ($data['data_empresa']['data']['vendedor_impresion'] == '3') {
            $html .= ' <div id="seller">Vendedor:' . $data["venta"]["vendedor"] . '</div>
                <div id="seller">' . "Usuario: " . $username . ' </div>';
        }

        if ($data['venta']['nota'] != '') {
            $html .= ' <div id="seller">' . $data["venta"]["nota"] . ' </div>';
        }

        $html .= ' </div>';
        $i = 0;
        foreach ($data["detalle_venta"] as $p) {
            if ($p['descuento'] > 0) {
                $i = 1;
            }
        }

        if ($i == 1) {
            $html .= '  <table id="ticket_items">

                <tr>

                        <th style="width:20%;text-align: left;">Ref </th>

                        <th style="width:20%;text-align:center;">Cant</th>

                        <th style="width:20%;text-align:right;">Precio </th>

                        <th style="width:20%;text-align:center;">Desc </th>

                        <th style="width:20%;text-align:right;">Total </th>

                </tr>
    ';
        } else {
            $html .= '  <table id="ticket_items">

                <tr>

                        <th style="width:20%;text-align: left;">Ref</th>

                        <th style="width:20%;text-align:center;">Cant</th>

                        <th style="width:20%;text-align:right;" >Precio </th>

                        <th style="width:20%;text-align:right;" colspan="2">Total </th>

                </tr>
         ';
        }

        $total = 0;
        $timp = 0;
        $subtotal = 0;
        $total_items = 0;
        $total_items_propina = 0;
        $sobrecosto = 0;
        $propina_final = 0;
        /*$group_by_impuesto = array();*/
        foreach ($data["detalle_venta"] as $p) {
            if ($p["nombre_producto"] == 'PROPINA') {
                $sobrecosto = $p['descripcion_producto'];
            } else {
                if ($empresa["data"]["tipo_factura"] == 'clasico') {
                    /* SERVICIOS */
                    $pv = $p['precio_venta'];
                    $desc = $p['descuento'];
                    $pvd = $pv - $desc;
                    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                    $total_column = $pvd * $p['unidades'];
                    $total_items += $total_column;
                    $valor_total = $pvd * $p['unidades'] + $imp;
                    $total += $total + $valor_total;
                    $timp += $imp;
                } else {
                    /* POS */
                    $pv = $p['precio_venta'];
                    $desc = $p['descuento'];
                    $pvd = $pv - $desc;
                    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                    $total_column = $pvd * $p['unidades'];
                    $total_items += $this->opciones->redondear($total_column);
                    $valor_total = $this->opciones->redondear($pvd * $p['unidades'] + $imp);
                    $total += $total + $valor_total;
                    $timp += $imp;
                }

                if (trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO') {
                    $pv_propina = $p['precio_venta'];
                    $desc_propina = $p['descuento'];
                    $pvd_propina = $pv_propina - $desc_propina;
                    $total_column_propina = $pvd_propina * $p['unidades'];
                    $total_items_propina += $total_column_propina;
                }

                if ($i == 1) {
                    $html .= '    <tr><td colspan="5">' . $p["nombre_producto"] . '</td></tr>

                        <tr>

                                <td>' . $p["codigo_producto"] . '</td>

                                <td style="text-align:center;">' . $p["unidades"] . '</td>

                                <td style="text-align:right;">' . number_format($p["precio_venta"]) . '</td>

                                <td style="text-align:center;">' . $p['descuento'] . '</td>

                                <td style="text-align:right;" colspan="2">' . number_format($valor_total) . '</td>

                        </tr>';
                } else {
                    $html .= '     <tr><td colspan="5">' . $p["nombre_producto"] . '</td></tr>

                        <tr>

                                <td>' . $p["codigo_producto"] . '</td>

                                <td style="text-align:center;">' . $p["unidades"] . '</td>

                                <td style="text-align:right;" colspan="2">' . number_format($p["precio_venta"]) . '</td>

                                <td style="text-align:right;">' . number_format($valor_total) . '</td>

                        </tr>
            ';
                }
            }
        }

        $html .= '
                <tr>

                        <td colspan="4" style="text-align:right;">Valor items</td>' . $total = $this->opciones->redondear($total_items + $timp) . '

                        <td  style="text-align:right">' . number_format($total_items) . ' </td>

                </tr>
';
        if ($sobrecosto > 0) {
            $propina_final = ($total_items_propina * $sobrecosto) / 100;
        }

        foreach ($data["venta_impuestos"] as $p) {
            if ($p->imp != '') {
                $html .= '      <tr>

                        <td colspan="4" style="text-align:right;">' . $p->imp . '</td>

                        <td  style="text-align:right">' . number_format($p->impuestos) . '</td>

                </tr> ';
            } else {
                $html .= '  <tr>

                        <td colspan="4" style="text-align:right;">IVA</td>

                        <td  style="text-align:right">' . number_format($p->impuestos) . '</td>

                </tr> ';
            }
        }

        if ($sobrecosto > 0 && $propina_final > 0) {
            $html .= '      <tr>

                        <td colspan="4" style="text-align:right;">Propina </td>

                        <td  style="text-align:right">' . number_format($propina_final) . '</td>

                </tr> ';
        }

        foreach ($data["detalle_pago_multiples"] as $p) {
            $formpago = str_replace("_", " ", $p->forma_pago);
            if ($p->forma_pago == 'efectivo') {
                $html .= '      <tr>

                                                <td colspan="4" style="text-align:right;">' . ucfirst($formpago) . ' </td>

                                                <td  style="text-align:right">' . number_format($p->valor_entregado) . ' </td>

                                        </tr> ';
            }

            if ($p->forma_pago != 'efectivo') {
                $html .= ' <tr>

                                                <td colspan="4" style="text-align:right;">' . ucfirst($formpago) . ' </td>

                                                <td  style="text-align:right">' . number_format($p->valor_entregado) . ' </td>

                                        </tr>    ';
            }
        }

        foreach ($data["detalle_pago_multiples"] as $p) {
            if ($p->forma_pago == 'efectivo') {
                $html .= '   <tr>

                                                <td colspan="4" style="text-align:right;">Cambio </td>

                                                <td  style="text-align:right">' . number_format($p->cambio) . '</td>

                                        </tr>   ';
            }
        }

        $html .= '     <tr>

                        <td colspan="4" style="text-align:right;">Total venta</td>

                        <td  style="text-align:right">' . number_format($total + $propina_final) . '</td>

                </tr>
                <tr>

                        <td colspan="5">&nbsp;</td>

                </tr>
        </table>
        <div id="customer">Puntos por esta compra:&nbsp;' . number_format($data["puntos_cliente_factura"]) . ' </div>
        <div id="customer">Total de puntos acumulados:&nbsp;' . number_format($data["puntos_cliente_acumulado"]) . ' </div>

 </td>
                             </td>
                                </tr>
                        </tbody></table>

         <table border="0" cellpadding="0" cellspacing="0" height="200px" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">
                         <tbody><tr>
                                 <td align="left" valign="top" style="border-collapse: collapse;"><br />
                 Enviado a usted por https://vendty.com/<br />
                    </td>
                                </tr>
                        </tbody></table>

                    </td>
                                </tr>
                        </tbody></table>
            <br /><br />  <br />  <br />  <br />  <br />  <br />
        ';
        $this->email->message($html);
        $this->email->send();

        $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha enviado la factura correctamente"));
        redirect("ventas/index");
    }

    public function pagos_servicio($id)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $venta = $this->ventas->get_by_id($id);
        $pagos = false;
        if ($venta['tipo_factura'] != 'estandar') {
            $pagos = true;
        } else {
            $detalles_pago = $this->ventas->get_detalles_pago($id);
            if ($detalles_pago['forma_pago'] == 'Credito') {
                $pagos = true;
            }
        }

        if ($pagos) {
            $data_empresa = $this->miempresa->get_data_empresa();
            $data = array();
            $data['venta_credito'] = array(
                'venta' => $venta,
                'detalle_venta' => $this->ventas->get_detalles_ventas($id),
                'detalle_pago' => $this->ventas->get_detalles_pago($id),
                'data_empresa' => $data_empresa,
            );
            $data['tipo'] = $this->pagos->get_tipos_pago();
            $data["total"] = $this->pagos->get_total($id);
            $data["data"] = $this->pagos->get_all($id, 0);
            $numero = $this->ventas->get_by_id($id);
            $data['numero'] = $numero["factura"];
            $data["id_factura"] = $id;
            $this->layout->template('member')->show('pagos/ver_pago', array(
                'data' => $data,
            ));
        }
    }

    public function modificar_propina()
    {
        $ventas = $this->ventas->modificar_propina();
    }

    public function aleatorio()
    {
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
        $numerodeletras = 10; //numero de letras para generar el texto
        $cadena = ""; //variable para almacenar la cadena generada
        for ($i = 0; $i < $numerodeletras; $i++) {
            $cadena .= substr($caracteres, rand(0, strlen($caracteres)), 1); /*Extraemos 1 caracter de los caracteres
        entre el rango 0 a Numero de letras que tiene la cadena */
        }

        $this->load->model("webpay_model", "webpay");
        $this->webpay->initialize($this->dbConnection);
        $ultimoId = $this->webpay->ultimoId();
        return $cadena . "-" . $ultimoId;
    }

    public function getDataOrdenByZona()
    {
        $zona = $this->input->post('zona');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ordenes->getDataOrdenByZona($zona)));
    }

    public function getAllOrdenes()
    {
        $almacenActual = $this->dashboardModel->getAlmacenActual();

        // $this->output->set_content_type('application/json')->set_output(json_encode($this->ordenes->getAllOrdenes($almacenActual)));

        /**nuevo */
        $datatoda = array();
        $productos = $this->ordenes->getAllOrdenes2($almacenActual);

        foreach ($productos as $mesas => $mesa1) {
            $zona = $mesa1['zona'];
            $mesa = $mesa1['mesa_id'];
            $nombre_mesa = $mesa1['nombre_mesa'];
            $nombre_zona = $mesa1['zona_mesa'];
            $creado = $mesa1['created_at'];
            $ordenes = $this->ordenes->getProductoOrden($zona, $mesa, $almacenActual);
            $valor_orden = 0;
            $valor_adiciones = 0;
            $adicionales = array();
            $cantidadTotalpro = 0;
            //print_r($ordenes); die();
            foreach ($ordenes as $orden) {
                $precioiva = 0;
                $preciounitario = 0;
                $str = strlen($orden['porciento']);
                if ($str == '1') {
                    $preciounitario = $orden['precio_venta'] * floatval("1.0" . $orden['porciento']);
                    $precioiva = $orden['precio_venta'] * $orden['cantidad'] * floatval("1.0" . $orden['porciento']);
                } else {
                    if ($str == '2') {
                        $preciounitario = $orden['precio_venta'] * floatval("1." . $orden['porciento']);
                        $precioiva = $orden['precio_venta'] * $orden['cantidad'] * floatval("1." . $orden['porciento']);
                    }
                }

                $adiciones = json_decode($orden['order_adiciones']);
                $id_producto = $orden['order_producto'];
                if (is_array($adiciones)) {
                    $valor_adiciones = 0;
                    $adicionales = array();
                    foreach ($adiciones as $adicion) {
                        $producto = $this->productos->getAdicionByid($id_producto, $adicion);
                        if (!empty($producto)) {
                            $adicionales[] = ['id_adicional' => $producto[0]['id_adicional'], 'nombre' => $producto[0]['nombre'], 'precio_venta' => ($producto[0]['precio'] * $producto[0]['cantidad'])];
                            $valor_adiciones = $valor_adiciones + ($producto[0]['precio'] * $producto[0]['cantidad']);
                        }
                    }
                } else {
                    $adicionales = '';
                }

                $cantidadTotalpro += $orden['cantidad'];
                $precioptotal = (($preciounitario + $valor_adiciones) * $orden['cantidad']);
                $precioputotal = ($preciounitario + $valor_adiciones);
                $valor_orden = $valor_orden + $precioptotal;
                $valor_adiciones = 0;
            }

            // $data['valor_orden'] = $this->opciones->formatoMonedaMostrar($valor_orden);
            // $data['valor_ordensin'] = $valor_orden;

            $datatoda[] = array(
                'created_at' => $creado,
                'zona_mesa' => $nombre_zona,
                'mesa_id' => $mesa,
                'cantidad' => $cantidadTotalpro,
                'zona' => $zona,
                'nombre_mesa' => $nombre_mesa,
                'monto' => $this->opciones->formatoMonedaMostrar($valor_orden),
                'almacen' => $almacenActual,
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($datatoda));
    }

    public function getAllFacturasPendientesxPago()
    {

        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $usuario = $this->session->userdata('user_id');
        $where = array('id_almacen' => $almacenActual, 'id_user' => $usuario, 'estado !=' => -1);

        //Busco las facturas pendientes por pagos
        $ventas = $this->ventas->get_facturas_por_pago_pendientes($where);

        $this->output->set_content_type('application/json')->set_output(json_encode($ventas));
    }

    public function registrarformaspago()
    {

        $idfactura = $this->input->post('factura');
        $valor_a_pagar = $this->input->post('valor_a_pagar');
        $cambio = $this->input->post('cambio');

        $forma_pago = $this->input->post('forma_pago');
        $forma_pago1 = $this->input->post('forma_pago1');
        $forma_pago2 = $this->input->post('forma_pago2');
        $forma_pago3 = $this->input->post('forma_pago3');
        $valor_entregado = $this->input->post('valor_entregado');
        $valor_entregado1 = $this->input->post('valor_entregado1');
        $valor_entregado2 = $this->input->post('valor_entregado2');
        $valor_entregado3 = $this->input->post('valor_entregado3');
        $transaccion = $this->input->post('transaccion');
        $transaccion1 = $this->input->post('transaccion1');
        $transaccion2 = $this->input->post('transaccion2');
        $transaccion3 = $this->input->post('transaccion3');
        $fecha_vencimiento_venta = $this->input->post('fecha_vencimiento_venta');
        $fecha_vencimiento_venta1 = $this->input->post('fecha_vencimiento_venta1');
        $fecha_vencimiento_venta2 = $this->input->post('fecha_vencimiento_venta2');
        $fecha_vencimiento_venta3 = $this->input->post('fecha_vencimiento_venta3');
        $valor_entregado_total = $valor_entregado + $valor_entregado1 + $valor_entregado2 + $valor_entregado3;

        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $codNotaCredito = $this->input->post('valor_entregado_nota_credito');
        $usuario = $this->session->userdata('user_id');
        $where = array('id_almacen' => $almacenActual, 'id_user' => $usuario, 'id_venta' => $idfactura);
        $ventas = $this->ventas->get_facturas_por_pago_pendientes($where);

        if ((!empty($ventas)) && (!empty($idfactura)) && (!empty($valor_entregado_total))) {
            $factura = $this->ventas->get_detalle_factura_pago_pendientes(array('v.id' => $idfactura));

            if (($factura[0]['almacen_id'] == $almacenActual) && ($usuario == $factura[0]['usuario_id'])) {
                //modificar la forma de pago
                //credito
                if (($forma_pago == 'Credito') || ($forma_pago1 == 'Credito') || ($forma_pago2 == 'Credito') || ($forma_pago3 == 'Credito')) {
                    //actualizo la fecha vencimiento en venta
                    $this->ventas->actualizar_venta_con_parametros(array('id' => $idfactura), array('fecha_vencimiento' => $fecha_vencimiento_venta));
                }
                //si es nota de credito
                /* if($forma_pago=='nota_credito'){
                $this->ventas->actualizarnotacredito($codNotaCredito,$idfactura,$factura[0]['cliente_id']);
                }*/
                //elimino las formas de pago actuales
                $eliminar = $this->ventas->eliminarformasdepago(array('id_venta' => $idfactura, 'forma_pago' => 'Sin_asignar_pago'));
                //ingreso las formas nuevas
                //obtengo los movimientos del cierre caja
                $cierre = $this->ventas->getcierrecajafactura(array('id_mov_tip' => $idfactura));
                //elimino los movimientos en el cierre
                $this->ventas->eliminarregistrocierrecaja(array('id_mov_tip' => $idfactura));

                if ($forma_pago != "0") {

                    $datospago = array(
                        'id_venta' => $idfactura,
                        'forma_pago' => $forma_pago,
                        'valor_entregado' => $valor_entregado,
                        'cambio' => $cambio,
                        'transaccion' => $transaccion,
                    );
                    $cambio = 0;
                    $this->ventas->registrarformasdepago($datospago);
                    //registrar pago en cierre
                    $datos_cierre = array(
                        "Id_cierre" => $cierre[0]['Id_cierre'],
                        "hora_movimiento" => $cierre[0]['hora_movimiento'],
                        "id_usuario" => $cierre[0]['id_usuario'],
                        "tipo_movimiento" => $cierre[0]['tipo_movimiento'],
                        "valor" => $cierre[0]['valor'],
                        "forma_pago" => $forma_pago,
                        "numero" => $cierre[0]['numero'],
                        "id_mov_tip" => $cierre[0]['id_mov_tip'],
                        "tabla_mov" => $cierre[0]['tabla_mov'],
                    );
                    $this->ventas->insertarmovimientocierrecaja($datos_cierre);
                }

                if ($forma_pago1 != "0") {
                    $datospago = array(
                        'id_venta' => $idfactura,
                        'forma_pago' => $forma_pago1,
                        'valor_entregado' => $valor_entregado1,
                        'cambio' => $cambio,
                        'transaccion' => $transaccion1,
                    );
                    $this->ventas->registrarformasdepago($datospago);

                    $datos_cierre = array(
                        "Id_cierre" => $cierre[0]['Id_cierre'],
                        "hora_movimiento" => date('H:i:s'),
                        "id_usuario" => $usuario,
                        "tipo_movimiento" => 'entrada_venta',
                        "valor" => $valor_entregado1,
                        "forma_pago" => $forma_pago1,
                        "numero" => $factura[0]['factura'],
                        "id_mov_tip" => $idfactura,
                        "tabla_mov" => "venta",
                    );
                    $this->ventas->insertarmovimientocierrecaja($datos_cierre);

                }

                if ($forma_pago2 != "0") {
                    $datospago = array(
                        'id_venta' => $idfactura,
                        'forma_pago' => $forma_pago2,
                        'valor_entregado' => $valor_entregado2,
                        'cambio' => $cambio,
                        'transaccion' => $transaccion2,
                    );
                    $this->ventas->registrarformasdepago($datospago);

                    $datos_cierre = array(
                        "Id_cierre" => $cierre[0]['Id_cierre'],
                        "hora_movimiento" => date('H:i:s'),
                        "id_usuario" => $usuario,
                        "tipo_movimiento" => 'entrada_venta',
                        "valor" => $valor_entregado2,
                        "forma_pago" => $forma_pago2,
                        "numero" => $factura[0]['factura'],
                        "id_mov_tip" => $idfactura,
                        "tabla_mov" => "venta",
                    );
                    $this->ventas->insertarmovimientocierrecaja($datos_cierre);

                }

                if ($forma_pago3 != "0") {
                    $datospago = array(
                        'id_venta' => $idfactura,
                        'forma_pago' => $forma_pago3,
                        'valor_entregado' => $valor_entregado3,
                        'cambio' => $cambio,
                        'transaccion' => $transaccion3,
                    );
                    $this->ventas->registrarformasdepago($datospago);

                    $datos_cierre = array(
                        "Id_cierre" => $cierre[0]['Id_cierre'],
                        "hora_movimiento" => date('H:i:s'),
                        "id_usuario" => $usuario,
                        "tipo_movimiento" => 'entrada_venta',
                        "valor" => $valor_entregado3,
                        "forma_pago" => $forma_pago3,
                        "numero" => $factura[0]['factura'],
                        "id_mov_tip" => $idfactura,
                        "tabla_mov" => "venta",
                    );
                    $this->ventas->insertarmovimientocierrecaja($datos_cierre);

                }

                $this->ventas->eliregistropendiente(array('id_venta' => $idfactura));
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0, 'msg' => 'Disculpe esta Factura no puede cambiarle la forma de Pago, ya que no es el usuario que la realizÃ³')));
            }

        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0, 'msg' => 'Dispulpe, La factura o el valor entregado no son vÃ¡lidos')));
        }
    }

    public function formaspago()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            //verifico que existe y es del usuario y almacen
            $almacenActual = $this->dashboardModel->getAlmacenActual();
            $usuario = $this->session->userdata('user_id');
            $where = array('id_almacen' => $almacenActual, 'id_user' => $usuario, 'id_venta' => $id);
            $ventas = $this->ventas->get_facturas_por_pago_pendientes($where);

            $random = rand();

            if (!empty($ventas)) {

                $data = $this->ventas->get_detalle_factura_pago_pendientes(array('v.id' => $id));

                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0, 'msg' => 'Dispulpe, La factura o el valor entregado no son vÃ¡lidos')));
            }
        }

    }

    public function formaspago1()
    {
        $id = $this->input->post('id');

        if (!empty($id)) {
            //verifico que existe y es del usuario y almacen
            $almacenActual = $this->dashboardModel->getAlmacenActual();
            $usuario = $this->session->userdata('user_id');
            $where = array('id_almacen' => $almacenActual, 'id_user' => $usuario, 'id_venta' => $id);
            $ventas = $this->ventas->get_facturas_por_pago_pendientes($where);

            $random = rand();

            if (!empty($ventas)) {
                $data['forma_pago'] = $this->forma_pago->getActiva();
                $data['impuesto'] = $this->impuestos->getFisrt();
                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];
                $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
                $data['dataventapago'] = $this->ventas->get_detalle_factura_pago_pendientes(array('v.id' => $id));
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $data['estado'] = $cuentaEstado["estado"];
                $data['simbolo'] = $data_empresa['data']['simbolo'];

                $permisos = $this->session->userdata('permisos');

                $is_admin = $this->session->userdata('is_admin');
                $impuestopredeterminado = $data['impuesto']->porciento;
                //print_r($data['dataventapago']); die();

                //el html
                echo '
                <div class="container">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="form_pago" method="POST">
                            <input type="hidden" class="form-control" id="factura" name="factura" value="' . $data['dataventapago'][0]['id'] . '">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-md-2 control-label">Valor a Pagar:</label>
                                <div class="col-md-10">
                                <input type="number" disabled index="" class="form-control" id="valor_a_pagar" name="valor_a_pagar" placeholder="valor_a_pagar" value="' . $data['dataventapago'][0]['valor_entregado'] . '">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                                <div class="col-md-10">
                                    <select name="forma_pago" id="forma_pago" class="form-control forma_pago" data-id="">';

                foreach ($data['forma_pago'] as $f) {
                    if ((($f->codigo != "nota_credito") && ($f->codigo != "Puntos") && ($f->codigo != "Saldo_a_Favor"))) {
                        echo '
                                            <option value="' . $f->codigo . '" data-tipo="' . $f->tipo . '">' . $f->nombre . '</option>';
                    }
                }
                echo '
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="pago_datafono" style="display:none">
                                <div class="col-md-2"> </div>
                                <div class="col-md-10">
                                    <div class="col-md-3">
                                    Subtotal <input id="subtotal" class="subtotal" type="text" disabled="true" value="0">
                                    </div>
                                    <div class="col-md-3">
                                    IVA <input class="impuesto" id="impuesto" type="text" disabled="true" value="0" >
                                    </div>
                                    <div class="col-md-3">
                                    Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono" id="impuestoDatafono" data-id="" value="' . $impuestopredeterminado . '">
                                    </div>
                                    <div class="col-md-3">
                                    NÂ° TransacciÃ³n <input type="text" name="transaccion" id="transaccion" value="">
                                    </div>
                                </div>
                            </div>
                            <div id="fecha_vencimiento_credito" class="form-group" style="display:none">
                                <div class="col-md-2">Fecha de vencimiento:</div>
                                <div class="col-md-10">
                                    <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" />
                                </div>
                            </div>
                            <div id="nota_credito" class="form-group" style="display:none">
                                <div class="col-md-2">Nota CrÃ©dito:</div>
                                <div class="col-md-9">
                                <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito"  index="" value="" placeholder=" C&oacute;digo Nota CrÃ©dito"/>
                                </div>
                                <div class="col-md-1">
                                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control valor_entregado" id="valor_entregado" name="valor_entregado" data-id="" placeholder="valor Entregado" value="' . $data['dataventapago'][0]['valor_entregado'] . '">
                                </div>
                            </div>

                            <div id="contenido_a_mostrar1" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                                    <div class="col-md-9">
                                        <select name="forma_pago1" id="forma_pago1" class="form-control forma_pago" data-id="1">
                                            <option value="0" data-tipo="">Seleccione</option>';
                foreach ($data['forma_pago'] as $f) {
                    if ((($f->codigo != "nota_credito") && ($f->codigo != "Puntos") && ($f->codigo != "Saldo_a_Favor"))) {
                        echo '<option value="' . $f->codigo . '" data-tipo="' . $f->tipo . '">' . $f->nombre . '</option>';
                    }
                }
                echo '
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a data-id="1" style="cursor: pointer">
                                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="1" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control valor_entregado" id="valor_entregado1" name="valor_entregado1" data-id="1" placeholder="valor Entregado" value="0">
                                    </div>
                                </div>
                                <div class="form-group" id="pago_datafono1" style="display:none">
                                    <div class="col-md-2"> </div>
                                    <div class="col-md-10">
                                        <div class="col-md-3">
                                        Subtotal <input id="subtotal1" class="subtotal" type="text" disabled="true" value="0">
                                        </div>
                                        <div class="col-md-3">
                                        IVA <input class="impuesto" id="impuesto1" type="text" disabled="true" value="0" >
                                        </div>
                                        <div class="col-md-3">
                                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono1" data-id="1" id="impuestoDatafono1" value="' . $impuestopredeterminado . '">
                                        </div>
                                        <div class="col-md-3">
                                        NÂ° TransacciÃ³n <input type="text" name="transaccion1" id="transaccion1" value="">
                                        </div>
                                    </div>
                                </div>
                                <div id="fecha_vencimiento_credito1" class="form-group" style="display:none">
                                    <div class="col-md-2">Fecha de vencimiento:</div>
                                    <div class="col-md-10">
                                        <input type="text" name="fecha_vencimiento_venta1" id="fecha_vencimiento_venta1" />
                                    </div>
                                </div>
                                <div id="nota_credito1" class="form-group" style="display:none">
                                    <div class="col-md-2">Nota CrÃ©dito:</div>
                                    <div class="col-md-9">
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito1" id="valor_entregado_nota_credito1"  index="" value="" placeholder=" C&oacute;digo Nota CrÃ©dito"/>
                                    </div>
                                    <div class="col-md-1">
                                        <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    </div>
                                </div>
                            </div>

                            <div id="contenido_a_mostrar2" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                                    <div class="col-md-9">
                                        <select name="forma_pago2" id="forma_pago2" class="form-control forma_pago" data-id="2">
                                            <option value="0" data-tipo="">Seleccione</option>';

                foreach ($data['forma_pago'] as $f) {
                    if ((($f->codigo != "nota_credito") && ($f->codigo != "Puntos") && ($f->codigo != "Saldo_a_Favor"))) {

                        echo '<option value="' . $f->codigo . '" data-tipo="' . $f->tipo . '">' . $f->nombre . '</option>';
                    }
                }
                echo '
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a data-id="1" style="cursor: pointer">
                                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="2" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control valor_entregado" id="valor_entregado2" name="valor_entregado2" data-id="1" placeholder="valor Entregado" value="0">
                                    </div>
                                </div>
                                <div class="form-group" id="pago_datafono2" style="display:none">
                                    <div class="col-md-2"> </div>
                                    <div class="col-md-10">
                                        <div class="col-md-3">
                                        Subtotal <input id="subtotal2" class="subtotal" type="text" disabled="true" value="0">
                                        </div>
                                        <div class="col-md-3">
                                        IVA <input class="impuesto" id="impuesto2" type="text" disabled="true" value="0" >
                                        </div>
                                        <div class="col-md-3">
                                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono2" id="impuestoDatafono2"  data-id="2" value="' . $impuestopredeterminado . '">
                                        </div>
                                        <div class="col-md-3">
                                        NÂ° TransacciÃ³n <input type="text" name="transaccion2" id="transaccion2" value="">
                                        </div>
                                    </div>
                                </div>
                                <div id="fecha_vencimiento_credito2" class="form-group" style="display:none">
                                    <div class="col-md-2">Fecha de vencimiento:</div>
                                    <div class="col-md-10">
                                        <input type="text" name="fecha_vencimiento_venta2" id="fecha_vencimiento_venta2" />
                                    </div>
                                </div>
                                <div id="nota_credito2" class="form-group" style="display:none">
                                    <div class="col-md-2">Nota CrÃ©dito:</div>
                                    <div class="col-md-9">
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito2" id="valor_entregado_nota_credito2"  index="2" value="" placeholder=" C&oacute;digo Nota CrÃ©dito"/>
                                    </div>
                                    <div class="col-md-1">
                                        <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    </div>
                                </div>
                            </div>

                            <div id="contenido_a_mostrar3" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                                    <div class="col-md-9">
                                        <select name="forma_pago3" id="forma_pago3" class="form-control forma_pago" data-id="3">
                                            <option value="0" data-tipo="">Seleccione</option>';
                foreach ($data['forma_pago'] as $f) {
                    if ((($f->codigo != "nota_credito") && ($f->codigo != "Puntos") && ($f->codigo != "Saldo_a_Favor"))) {
                        echo '<option value="' . $f->codigo . '" data-tipo="' . $f->tipo . '">' . $f->nombre . '</option>';
                    }
                }
                echo '
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a data-id="1" style="cursor: pointer">
                                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="3" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control valor_entregado" id="valor_entregado3" name="valor_entregado3" data-id="3" placeholder="valor Entregado" value="0">
                                    </div>
                                </div>
                                <div class="form-group" id="pago_datafono3" style="display:none">
                                    <div class="col-md-2"> </div>
                                    <div class="col-md-10">
                                        <div class="col-md-3">
                                        Subtotal <input id="subtotal3" class="subtotal" type="text" disabled="true" value="0">
                                        </div>
                                        <div class="col-md-3">
                                        IVA <input class="impuesto" id="impuesto3" type="text" disabled="true" value="0" >
                                        </div>
                                        <div class="col-md-3">
                                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono3" id="impuestoDatafono3"  data-id="3" value="' . $impuestopredeterminado . '">
                                        </div>
                                        <div class="col-md-3">
                                        NÂ° TransacciÃ³n <input type="text" name="transaccion3" id="transaccion3" value="">
                                        </div>
                                    </div>
                                </div>
                                <div id="fecha_vencimiento_credito3" class="form-group" style="display:none">
                                    <div class="col-md-2">Fecha de vencimiento:</div>
                                    <div class="col-md-10">
                                        <input type="text" name="fecha_vencimiento_venta3" id="fecha_vencimiento_venta3" />
                                    </div>
                                </div>
                                <div id="nota_credito3" class="form-group" style="display:none">
                                    <div class="col-md-2">Nota CrÃ©dito:</div>
                                    <div class="col-md-9">
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito3" id="valor_entregado_nota_credito3"  index="3" value="" placeholder=" C&oacute;digo Nota CrÃ©dito"/>
                                    </div>
                                    <div class="col-md-1">
                                        <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-md-2 control-label">Cambio:</label>
                                <div class="col-md-10">
                                    <input type="number" disabled class="form-control" id="cambio" name="cambio" placeholder="cambio" value="0">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <button type="button" class="btn btn-default">Cancelar</button> ';
                if ($data['multiples_formas_pago'] == 'si') {
                    echo '<button type="button" class="btn btn-success" onClick="mostrar();" >Agregar Forma de Pago</button>';
                }
                echo '<button type="button" id="pagar_pendiente" class="btn btn-success">Pagar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>';
            } else {

            }
        }

    }

    public function getDataOrdenByMesa()
    {
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ordenes->getDataOrdenByMesa($zona, $mesa, $almacenActual)));
    }

    public function impresion_rapida()
    {
        $id = $this->input->post('id');
        $response = get_curl("fast_print/" . $id, $this->session->userdata('token_api'));
        echo json_encode($response);
    }

    public function dividir_cuenta()
    {
        $id_orden = $_GET['id'];
        $mesa = $_GET['id_mesa'];
        $zona = $_GET['id_zona'];
        $cantp = isset($_GET['cantp']) ? $_GET['cantp'] : 0;
        if ((!empty($zona)) && (!empty($mesa)) && (!empty($id_orden))) {
            if ((isset($cantp)) && (!empty($cantp)) && ($cantp > 0)) {
                $this->ordenes->DuplicarRowDivisionCuentaOrden($id_orden, $cantp);
            } else {
                echo "<br /><br />zona=" . $zona;
                echo "<br />mesa=" . $mesa;
                echo "<br />id_orden=" . $id_orden;
                $this->ordenes->CambiarDivisionCuentaOrden($id_orden, 4);
            }
        }
    }

    public function eliminar_dividir_cuenta()
    {
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $this->ordenes->CambiarDivisionCuentaOrdenZonaMesa($zona, $mesa);
        return true;
    }

    public function validImage($url)
    {
        $file_headers = @get_headers($url);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        } else {
            return true;
        }
    }
}

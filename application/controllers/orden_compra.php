<?php

class Orden_compra extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        $this->load->helper('logs_helper');

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);
        
        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);

        $this->load->model("orden_compra_model", 'ventas');

        $this->ventas->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');
        
        $this->productos->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("vendedores_model", 'vendedores');

        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        $this->load->model("mesas_secciones_model", 'mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);
        
        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);
        
        $this->load->model("secciones_almacen_model", 'secciones_almacen');
        $this->secciones_almacen->initialize($this->dbConnection);

        /*
          $this->load->model("clientes_model",'clientes');
          $this->clientes->initialize($this->dbConnection);
         */
        $this->load->model("clientes_model", 'clientes');

        $this->clientes->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');

        $this->productos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("unidades_model", 'unidades');

        $this->unidades->initialize($this->dbConnection);

        $this->load->model("facturas_model", 'facturas');

        $this->facturas->initialize($this->dbConnection);
        
        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);

        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        
        //Modelo de proformas
        $this->load->model("proformas_model", 'proformas');
        $this->proformas->initialize($this->dbConnection);

        $this->load->model('inventario_model','inventario');
        $this->inventario->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

        $this->ventas->camposanulaciones();
        $this->ventas->actualizarTabla($this->session->userdata('base_dato'));

        $this->load->model('primeros_pasos_model');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);
        
        $this->load->model("Caja_model",'caja');
        $this->caja->initialize($this->dbConnection);

         $this->load->model('crm_model');

        $this->load->model("Cuentas_dinero_model", 'cuentas_dinero');
        $this->cuentas_dinero->initialize($this->dbConnection);
        
         $this->load->model("pais_provincia_model", 'pais_provincia');
        //agrega los precio de ventas del producto en el detalle_orden_compra y en movimiento_detalle 
        $this->ventas->campos_precios_venta_orden();
         //ACTUALIZAR TABLAS PARA ESTACION PEDIDO // Comensales        
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        if((!empty($data_empresa['data']['tipo_negocio']))&&($data_empresa['data']['tipo_negocio']=="restaurante")){            
            $this->load->model('ventas_model','tmpventa');
            $this->tmpventa->initialize($this->dbConnection);
            $this->tmpventa->add_campo_comensales();
            $this->tmpventa->addColumnOrden();
            $this->mesas_secciones->add_campo_comensales();
        }

        $this->load->model("bancos_model", 'bancos');
        $this->bancos->initialize($this->dbConnection);
        $this->bancos->check_tables();
    }


    public function actualizar_comensales()
    {
        $this->mesas_secciones->actualizar_mesa([
            'comensales' => $this->input->post('comensales')
        ], [
            'id' => $this->input->post('id_mesa'),
            'id_seccion' => $this->input->post('id_seccion')
        ]);
    }


    function mi_orden($id_seccion,$id_mesa,$checkout_enabled = 'no'){
        
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->ordenes->create_table_historico_ordenes();
        $this->ordenes->create_table_log_quick_service();        
        //Puedo factura?
        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
    
        if(($puedofacturar==1)){       
            echo'
            <script>
                    alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");           
                    window.location="../../../frontend/index"; 
            </script>';
        }

          /****si es estacion */      
        if($this->session->userdata('es_estacion_pedido')==1){          
            if($this->session->userdata('vendedor_estacion_actual_id')<=0){
                redirect("tomaPedidos/estacion_pedidos");
            }
            else{
                //verificar si esta disponible la mesas seleccionada y si lo esta se la asigna sino se redirige a las mesas nuevamente
                $vendedor=$this->session->userdata('vendedor_estacion_actual_id');           
                $estacion="(vendedor_estacion IS NULL or vendedor_estacion=".$vendedor.")";
                           
                $diponible_mesa=$this->mesas_secciones->get_mesa_secciones(array('id'=>$id_mesa,'id_seccion'=>$id_seccion),$estacion);

                if(!empty($diponible_mesa)){
                    $ocupado=$this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>$vendedor),array('id'=>$id_mesa,'id_seccion'=>$id_seccion));
                }else{
                    $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'La mesa seleccionada ya esta tomada por otro mesero'));
                    redirect("tomaPedidos/mesero");
                }                
            }
        }
        else{
            //verificar si esta disponible la mesas seleccionada y si lo esta se la asigna sino se redirige a las mesas nuevamente
                $vendedor=0;
                $estacion="(vendedor_estacion IS NULL)";
                           
                $diponible_mesa = $this->mesas_secciones->get_mesa_secciones([
                    'id' => $id_mesa,
                    'id_seccion' => $id_seccion
                ], $estacion);

                if(!empty($diponible_mesa)){
                    $ocupado=$this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>-1),array('id'=>$id_mesa,'id_seccion'=>$id_seccion));
                }              
        }

        $data['seccion'] = $id_seccion;
        $data['mesa'] = $id_mesa;
        //Armando la categorias
        $data['categorias']['total_rows'] = $this->categorias->num_rows();
        $data['categorias']['per_page']  = 5;
        $data['categorias']['page']  = 1;
        if($data['categorias']['page'] * $data['categorias']['per_page'] < $data['categorias']['total_rows'])
            $data['categorias']['next_page'] = $data['categorias']['page'] + 1;
            
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data['categorias']['registros']  = $this->categorias->getAll();
        $data["seccion_mesa"] = $this->mesas_secciones->get_secciones_mesas_by_id_mesa($id_mesa);
         $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];

        $almacenActual = $this->dashboardModel->getAlmacenActual();
        
        $data["data_mesas_disponibles"] = $this->secciones_almacen->get_mesas_disponibles($almacenActual);
        //print_r($data); die();
        //$this->layout->template('new_layout')->show('orden_compra/ordenes',array('data' => $data));
        

        //print_r($this->session->userdata); die();
        valide_option('nueva_impresion_rapida','no');
        $data["payment-methods"] = get_curl("payment-methods",$this->session->userdata('token_api'));
        $data["countries"] = $this->clientes->get_pais();
        $data["groups"] = get_curl("customers-groups",$this->session->userdata('token_api'));
        $data["data-currency"] = get_curl("data-currency",$this->session->userdata('token_api'));
        $data["customers"] =  (array) get_curl("customers",$this->session->userdata('token_api'));
        $data["domiciliaries"] = get_curl("take-order/domiciliaries",$this->session->userdata('token_api'));
        $data["store-id"] = $this->miempresa->get_store();
        $data['quick-service'] = $this->opciones->getOpcion('quick_service');
        $data['quick-service-command'] = $this->opciones->getOpcion('quick_service_command');
        $data['new-fast-print'] = $this->opciones->getOpcion('nueva_impresion_rapida');
        $data['checkout_enabled'] = ($checkout_enabled == 'checkout') ? 'si' : 'no';
        $data['order_consecutive'] =  $this->mesas_secciones->get_order_consecutive($data["store-id"]);
        //print_r($data["order_consecutive"]); die();
     
        if($data['seccion'] == -1){
            $this->mesas_secciones->create_seccion_quick_service($id_seccion,$id_mesa);
        }
        
        if($this->session->userdata("es_estacion_pedido") == 0){
            $caja = $this->verify_state_box();
            //print_r($caja["estado_caja"]); die(); 
            if($caja["estado_caja"] == "abierta"){
                $this->load->view('layouts/new_layout',array('data' => $data));
                $this->load->view('orden_compra/ventas',array('data' => $data));
                $this->load->view('orden_compra/ordenes',array('data' => $data));
                $this->load->view('layouts/newFooter',array('data' => $data));
            }else{
                $this->session->set_userdata('page_backup','quick-service');
                redirect(site_url('caja/apertura'));
            }   
        }else{
            $this->load->view('layouts/new_layout',array('data' => $data));
            $this->load->view('orden_compra/ventas',array('data' => $data));
            $this->load->view('orden_compra/ordenes',array('data' => $data));
            $this->load->view('layouts/newFooter',array('data' => $data));
        }
        
    }
    
    function helpPayAmount(){
        $data = array(
            'amount' => $this->input->post('amount')
        );
        echo json_encode(post_curl('checkout/help-pay-amounts',json_encode($data),$this->session->userdata('token_api')));
    }


    function changeOrden(){        
        //si es estacion de cambio el vendedor de mesa
        if($this->session->userdata('es_estacion_pedido')==1){          
            if($this->session->userdata('vendedor_estacion_actual_id')>=0){
                $mesa=$this->mesas_secciones->get_una_mesa_by(array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>NULL,'consecutivo_orden_restaurante'=>NULL,'nota_comanda'=>NULL,'comensales'=>NULL), array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>$this->session->userdata('vendedor_estacion_actual_id'),'consecutivo_orden_restaurante'=>$mesa->consecutivo_orden_restaurante,'comensales'=>$mesa->comensales), array('id'=>$this->input->post('mesa_seleccionada'),'id_seccion'=>$this->input->post('zona_seleccionada')));               
            }else{
                //verifico si la tiene asignada alguien                
                $mesa=$this->mesas_secciones->get_una_mesa_by(array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>NULL,'consecutivo_orden_restaurante'=>NULL,'nota_comanda'=>NULL,'comensales'=>NULL), array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>$mesa->vendedor_estacion,'consecutivo_orden_restaurante'=>$mesa->consecutivo_orden_restaurante,'comensales'=>$mesa->comensales), array('id'=>$this->input->post('mesa_seleccionada'),'id_seccion'=>$this->input->post('zona_seleccionada')));               

            }
            
        }else{
             //verifico si la tiene asignada alguien                
                $mesa=$this->mesas_secciones->get_una_mesa_by(array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>NULL,'comensales'=>NULL), array('id'=>$this->input->post('mesa_anterior'),'id_seccion'=>$this->input->post('zona_anterior')));
                $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>$mesa->vendedor_estacion,'comensales'=>$mesa->comensales), array('id'=>$this->input->post('mesa_seleccionada'),'id_seccion'=>$this->input->post('zona_seleccionada')));               

        }
        echo $this->ordenes->changeOrdenByMesa($this->input->post());        
    }

    function process(){
        $data = array(
            'zone' => $this->input->post('zone'),
            'table' => $this->input->post('table'),
            'products' => $this->input->post('products'),
            'payments' => $this->input->post('payments'),
            'store_id' => $this->input->post('store_id'),
            'total_payment' => $this->input->post('total_payment'),
            'total_received' => $this->input->post('total_received'),
            'total_change' => $this->input->post('total_change'),
            'selected_domicile' => $this->input->post('selected_domicile'),
            'customer' => $this->input->post('customer'),
            'type_process' => $this->input->post('type_process'),
            'total_discount' => $this->input->post('total_discount'),
            'type_discount' => $this->input->post('type_discount'),
            'discount' => $this->input->post('discount'),
            'total_propine' => $this->input->post('total_propine'),
            'note_invoice' => $this->input->post('note_invoice'),
            'order_consecutive' => $this->input->post('order_consecutive'),
            'quick_service_command' => $this->input->post('quick_service_command')
        );

        $response = (post_curl('checkout/process',json_encode($data),$this->session->userdata('token_api'))); 
        /*if(isset($response->status) && isset($response->fast_print) && $response->status && $response->fast_print){
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Venta generada de manera correcta"));
        }*/
        
        if(is_null($response)){
            $this->save_log_quick_service(json_encode($data));
        }
       echo json_encode($response);
    }

    function save_automatic_print(){
        $option = $this->input->post("automatic_print");
        valide_option('automatic_print',$option);
        set_option('automatic_print',$option);
    }

    function getOrden(){
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $data = array();
        $almacenActual = $this->dashboardModel->getAlmacenActual();               
        $ordenes = $this->ordenes->getProductoOrden($zona,$mesa,$almacenActual);    
        $valor_orden = 0;
        $valor_adiciones = 0;
        $adicionales = array();        
        $data_empresa = $this->miempresa->get_data_empresa();
        $simbolo=(!empty($data_empresa['data']['simbolo'])) ? $data_empresa['data']['simbolo'] : '$';
        
        if(!empty($ordenes)){
            $fecha_or=($ordenes[0]['update_at']!='0000-00-00 00:00:00')?$ordenes[0]['update_at']:$ordenes[0]['created_at'];
            $fecha_orden=date_create($fecha_or);
        }
        foreach($ordenes as $orden){            
            $precioiva=0;    
            $preciounitario = 0;                       
           $str = strlen($orden['porciento']);                     
          
            if($str == '1')
            {
                $preciounitario = $orden['precio_venta']*floatval("1.0".$orden['porciento']);
                $precioiva=$orden['precio_venta']*$orden['cantidad']*floatval("1.0".$orden['porciento']);
                
            }
            else {
                if($str == '2')
                {        
                    $preciounitario = $orden['precio_venta']*floatval("1.".$orden['porciento']);           
                    $precioiva=$orden['precio_venta']*$orden['cantidad']*floatval("1.".$orden['porciento']);
                   
                }
            }
           
           //$valor_orden= $valor_orden + $precioiva;
            $adiciones = json_decode($orden['order_adiciones']);
            $id_producto = $orden['order_producto'];
            if(is_array($adiciones)){
                $valor_adiciones = 0;
                $adicionales = array();  
                
                foreach($adiciones as $adicion){
                    
                    $producto = $this->productos->getAdicionByid($id_producto,$adicion);     
                    if(!empty($producto)) {             
                        $adicionales[] = [
                            'id_adicional' =>  $producto[0]['id_adicional'],
                            'nombre' => $producto[0]['nombre'],
                            'precio_venta' => ($producto[0]['precio']*$producto[0]['cantidad']),
                        ];
                        $valor_adiciones = $valor_adiciones + ($producto[0]['precio']*$producto[0]['cantidad']);
                    }
                }                            
            }else{
                $adicionales = '';
            }
            
            
            $precioptotal=(($preciounitario+$valor_adiciones)*$orden['cantidad']);          
            $precioputotal=($preciounitario+$valor_adiciones);          
            $valor_orden=$valor_orden + $precioptotal;
            $valor_adiciones=0;
            $data['orden'][] = array(
                'id' => $orden['id'],
                'id_producto' => $orden['order_producto'],
                'modificacion' => json_decode($orden['order_modificacion']),
                'adicionales' => $adicionales,
                'nombre' => $orden['nombre'],
                //'precio_venta' => $orden['precio_venta'],
                'cantidad' => $orden['cantidad'],
                'precio_venta' => $this->opciones->formatoMonedaMostrar($preciounitario),
                'precio_ventaptotal' => $this->opciones->formatoMonedaMostrar($precioptotal),                
                'precio_ventaptotalsin' => $precioptotal,
                'precio_ventaputotalsin' => $precioputotal,
                'precio_ventaputotal' => $this->opciones->formatoMonedaMostrar($precioputotal),      
                'estado' => $orden['estado'],
                'impuesto'=> $orden['porciento'],
                'simbolo'=> $simbolo
            );  
        }        
        $data['valor_orden'] = $this->opciones->formatoMonedaMostrar($valor_orden); 
        $data['simbolo'] = $simbolo;
        if(!empty($fecha_orden)){
            $data['fecha_orden'] = date_format($fecha_orden, 'd/m h:i A'); 
        }else{
            $data['fecha_orden']="";
        }         
         
        $data['valor_ordensin'] = $valor_orden;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));


    }
    
    function get_order_products(){

        $orden_final = array();
        $orden_aditions = array();
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $ordenes = $this->ordenes->getDataOrdenByMesa($zona, $mesa, $almacenActual);
        $counter = 0;
        $total = 0;

        foreach($ordenes as $orden) {
            $data_orden = new stdClass();
            $data_orden->id_cliente = $orden->id_cliente;
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
            $data_orden->is_adicional = false;
            $total += ($orden->monto + $orden->monto_iva) * $orden->cantidad; 
            $orden_final[$counter] = $data_orden;

            if (is_array(json_decode($orden->order_adiciones))) {

                // Verificamos las adiciones realizadas
                $adiciones = json_decode($orden->order_adiciones);
                foreach($adiciones as $adicion) {
                    $data_orden = new stdClass();
                    $counter++;
                    $data_adicion = $this->productos->getAdicionByid($orden->order_producto, $adicion);
                    if(!empty($data_adicion)) {                         
                        $id_producto = $data_adicion[0]['id_adicional'];
                        // obtenemos el producto asociado                    
                        $producto = $this->productos->get_by_id($id_producto);
                        
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
                    
                        $data_orden->fk_id_producto = $producto["id"];
                        $data_orden->nombre = $producto['nombre'];  

                        /*$real_price_no_tax = $data_adicion[0]['precio'];
                        
                        
                        if($impuesto['porciento'] != 0) {
                            $percent = $data_adicion[0]['precio'] * $impuesto['porciento'] / 100;
                            $real_price_no_tax = $data_adicion[0]['precio'] - $percent;
                        }*/
                        
                        $data_orden->precio = $data_adicion[0]['precio'] * $data_adicion[0]['cantidad'];
                        $data_orden->codigo = $producto['codigo'];
                        $data_orden->descripcion_d = $producto['descripcion'];
                        $data_orden->impuesto = $orden->impuesto;
                        $data_orden->monto_iva = $data_adicion[0]['precio'];
                        $data_orden->monto = $data_adicion[0]['precio'];

                        $data_orden->is_adicional = true;
                        $impuesto = $this->productos->get_impuesto_by_id($producto['impuesto']);
                        $data_orden->tax_aditional = $impuesto['porciento'];
                        //$total += $data_adicion[0]['precio']; 
                        $total += $data_orden->precio; 
                        $orden_final[$counter] = $data_orden;
                        //$orden_aditions[] = $data_orden;
                    }
                }
            }

            $counter++;
        }

        $response = array(
            "orden" => $orden_final,
            "total" => $this->opciones->formatoMonedaMostrar($total)
        ); 
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function load_cities_from_country() {
        $pais = $this->input->get('pais', TRUE);
        $result = $this->pais_provincia->get_provincia_from_pais($pais);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function get_propine(){
        $data = array(
            'zone' => $this->input->post('zone'),
            'table' => $this->input->post('table'),
            'propine' => $this->input->post('propine'),
            'discount' => $this->input->post('discount'),
            'type_propine' => $this->input->post('type_propine'),
            'type_discount' => $this->input->post('type_discount')
        );

        $response = (post_curl('tools/propine',json_encode($data),$this->session->userdata('token_api'))); 
        echo json_encode($response);
    }


    function verify_state_box(){
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["estado_caja"] = "cerrada";
          //verifico si la caja esta abierta
        if ($this->session->userdata('caja') != ""){
            $data["estado_caja"] = "abierta";
        }else{
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
                    $data["estado_caja"] = "abierta";
                }  
            }else{
                $data["estado_caja"] = "abierta";
            }
        }

        return $data;
    }

    function guardarModificacion(){
        $producto = $this->input->post('id');        
        $modificacion = $this->input->post('modi');

        if((!empty($producto))&& (!empty($modificacion))){  
            $x=$this->productos->verificarexistenciaModificacion(array('id_producto'=>$producto,'nombre'=>$modificacion)); 
            if($x==0){
                $this->productos->addModificacion(array('id_producto'=>$producto,'nombre'=>$modificacion));  
                $data['mensaje'] = "Modificación guarda con éxito";
                $data['status'] = 1;
            }
            else{
                $data['mensaje'] = "Modificación existe";
                $data['status'] = 0;
            }                            
        }        
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function addModificacion(){
        $id = $this->input->post('id');
        $producto = $this->input->post('producto');

        if(!empty($producto)){            
            //verificamos las modificaciones que tienes actuales
            $modificaciones = $this->ordenes->getOrdenById($id);

            $pila = json_decode($modificaciones[0]['order_modificacion']);
            is_array($pila) ? array_push($pila,$producto) : $pila = array($producto);
           
            if($this->ordenes->saveModificacion($id,$pila))
                $data['mensaje'] = "modificacion realizada con exito";
                
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
    function addAdicional(){
        $id = $this->input->post('id');
        $producto = $this->input->post('producto');

        if(!empty($producto)){     
            //verificamos las modificaciones que tienes actuales
            $adiciones = $this->ordenes->getOrdenById($id);

            $pila = json_decode($adiciones[0]['order_adiciones']);
            is_array($pila) ? array_push($pila,$producto) : $pila = array($producto);
           
            if($this->ordenes->saveAdicional($id,$pila))
                $data['mensaje'] = "Adicion realizada con exito";
                
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function eliminarProducto(){
        $id = $this->input->post('id');
        $id_producto = $this->input->post('id_producto');
        if($this->ordenes->deleteProductoOrden($id))
            $data['mensaje'] = "Producto eliminado de la orden";

            $this->output->set_content_type('application/json')->set_output(json_encode($data));    
    }

    public function eliminarAdicion(){
        //buscamos la orden 
        $id = $this->input->post('id');
        $tipo = $this->input->post('type');
        $producto = $this->input->post('val');

        //verificamos las modificaciones que tienes actuales
        $orden = $this->ordenes->getOrdenById($id);
        $pila = array();
        if(!empty($producto)){        
            if($tipo == 1){
                $pila = json_decode($orden[0]['order_modificacion']);
                
                $indice = array_search($producto,$pila,false);
                unset($pila["$indice"]);

                if($this->ordenes->saveModificacion($id,$pila))
                    $data['mensaje'] = "Modificacion realizada con exito";
            }elseif($tipo == 2)
            {
                $pila = json_decode($orden[0]['order_adiciones']);
                $indice = array_search($producto,$pila,false);
                unset($pila["$indice"]);
                              
                if($this->ordenes->saveAdicional($id,$pila))
                    $data['mensaje'] = "Adicion realizada con exito";
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }    
    }

    function eliminarOrden(){
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $estado = $this->input->post('estado');
        if($this->ordenes->eliminarOrden($zona,$mesa,array($estado)))
            $data['mensaje'] = "Orden eliminada con exito";

            $this->output->set_content_type('application/json')->set_output(json_encode($data));    

    }

    function confirmarOrden(){
        session_start();

        $token = $this->input->post('token');
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $notacomanda=$this->input->post('notacomanda');
        $comensales=$this->input->post('comensales');
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $productos = $this->ordenes->getDataOrdenByMesa($zona,$mesa,$almacenActual, true);

        if(empty($notacomanda)){
            $notacomanda= "";
        }

        $this->mesas_secciones->agregarnota($notacomanda,array('id'=>$mesa));

        if($this->ordenes->confirmarOrden($zona,$mesa,$notacomanda)){
                //busco si tengo orden asignanada
            $ordenes = $this->ordenes->getDataOrdenByMesa($zona,$mesa,$almacenActual);
            $numero=0;
            if(!empty($ordenes)){

                $tengOrden=$this->mesas_secciones->get_una_mesa_by(array('id'=>$mesa,'id_seccion'=>$zona));
                
                if(empty($tengOrden->consecutivo_orden_restaurante)){
                    //busco el consecutivo
                    $numero=$this->almacen->buscar_Consecutivo_orden_restaurante(array('id'=>$almacenActual));
                    $this->mesas_secciones->actualizar_mesa(array('consecutivo_orden_restaurante'=>$numero), array('id'=>$mesa,'id_seccion'=>$zona));
                }else{
                    $numero=$tengOrden->consecutivo_orden_restaurante;
                }
            }
            $data['mensaje'] = "Orden confirmada con exito";
            $data['orden_consecutivo']=$numero;
        }


        $data['token']=$token;
        if(isset($token) && $token !== "" && $token !== null && $token !== "null") {
            $dataVirtualCommand = array(
                "note" => $notacomanda,
                "order" => $numero,
                "products" => $productos,
                "table" => $mesa,
                "zone" => $zona
            );
            
            $response = post_curl('virtual_command/take_order', json_encode($dataVirtualCommand), $token);

            $data['dataVirtualCommand']=$dataVirtualCommand;
            $data['response']=$response;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }    


    function verify_print_command(){
        $zone = $this->input->post('zona');
        $table = $this->input->post('mesa');
        $data = array(
            "status" => $this->ordenes->verify_print_command($zone,$table)
        );

        echo json_encode($data);
    }


    /**
     * Permite paginar las categorias
     */
    function getAjaxCategorias(){
        $offset = $this->input->get('offset');
        $data['total_rows'] = $this->categorias->num_rows();
        $limit = 5;
        if($offset * $limit < $data['total_rows'])
            $data['next_page'] =  $offset + 1;
        
        $data['last_page'] = $offset -1;
        $data['final_page'] = round($data['total_rows'] / $limit);

        $data['categorias']  = $this->categorias->pagination($limit,$offset);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function storeProductOrden(){
        
    }
    
    function nuevo_actulizar($id = NULL) {

        $this->ventas->add_actualizar($id);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha guardado correctamente"));

        redirect("orden_compra/index");
    }

    function caja_abierta(){
        $band=0;
            $data_empresa = $this->mi_empresa->get_data_empresa();
           
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

    function nuevo() {

        /*
            Jeisson Rodriguez Dev
            04-09-2019

            I commented timezone default 
        */
        // date_default_timezone_set("America/Lima");

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        /* if($this->form_validation->run('facturas') == true){...} */
        if (isset($_POST['vendedor'])) {

            //Identifica si una venta fue por POS o por Servicios
            if ("clasico" == 'clasico') {

                $pago = $_POST['pago'];
                $tipo_factura = 'Orden de Compra';
                $fecha = $_POST['fecha'];
                $fecha_vencimiento = $_POST['fecha_v'];
            } else {

                $pago = array(
                    'valor_entregado' => $_POST['total_venta'],
                    'cambio' => 0,
                    'forma_pago' => 'efectivo',
                );

                $tipo_factura = 'clasico';
                $fecha = $_POST['fecha'];
                $fecha_vencimiento = $_POST['fecha_v'];
            }

            $data = array(
                'almacen' => $_POST['almacen'],
                'fecha' => $fecha,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cliente' => $_POST['cliente'],
                'vendedor' => $_POST['vendedor'],
                'usuario' => $this->session->userdata('user_id'),
                'productos' => $_POST['productos'],
                'total_venta' => $_POST['total_venta'],
                'pago' => $pago,
                'tipo_factura' => $tipo_factura,
                'nota' => $_POST['nota']
            );

            /* Registrar venta */
            $id = $this->ventas->add($data);

            //guardar evento de primeros pasos vendedor
            $estadoBD = $this->newAcountModel->getUsuarioEstado();                    
            if(($estadoBD["estado"]==2)&&(!empty($id))){
                $paso=16;
                $marcada=$this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'),'db_config' => $this->session->userdata('db_config_id'),'id_paso'=>$paso));
                if($marcada==0){
                        $datatarea=array(
                        'id_paso' => $paso,
                        'id_usuario' => $this->session->userdata('user_id'),
                        'db_config' => $this->session->userdata('db_config_id')
                );
                $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                }                               
            }

            $data = array(
                'venta' => $this->ventas->get_by_id($id)
                , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
                , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
                , 'data_empresa' => $data_empresa,
            );

            /* Email */
            $this->load->library('email');
            $this->email->clear();
            $this->email->from($data_empresa["data"]["email"], $data_empresa["data"]["nombre"]);
            $this->email->to('comercial@sistematizamos.com');
            $this->email->subject("Su recibo de compra");

            if ($data_empresa['data']['plantilla'] == 'media_carta') {
                $message = $this->load->view('ventas/_imprimemediacarta', array('data' => $data), true);
            } else {
                $message = $this->load->view('ventas/imprime', array('data' => $data), true);
            }

            $message = $message . "<br/>Enviado por www.vendty.com";
            $this->email->message($message);
            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));
        } else {
            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["clientes"] = $this->clientes->get_all(0);
            $data['vendedores'] = $this->vendedores->get_combo_data();
            //Vitrina categorias----------------------------------------------------------- //
            $data['categorias'] = $this->categorias->get_limit(0);
            //...............................................................................
            //$data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
            $data['forma_pago'] = $this->pagos->get_tipos_pago();

            //Factura estandar --------------------------------------------------------------------------------------
            if ('clasico' == 'estandar') {
                $this->layout->template('ventas')
                    ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))
                    ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))
                    ->show('orden_compra/nuevo', array('data' => $data));
            } else {
                //Factura clasica -------------------------------------------------------------------------------------------------------
                $data['impuestos'] = $this->impuestos->get_combo_data_factura();
                //$data['unidades'] = $this->unidades->get_combo_data_factura_unidades();
                $data['unidades'] = $this->unidades->get_combo_data_unidades();
                $data['almacen'] = $this->almacen->get_combo_data();
                //------------------------------------------------ almacen usuario
                $user_id = $this->session->userdata('user_id');
                $id_user = '';
                $almacen = '';
                $nombre = '';
                $user = $this->db->query("SELECT id FROM users where id = '" . $user_id . "' limit 1")->result();
                foreach ($user as $dat) {
                    $id_user = $dat->id;
                }

                $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '" . $id_user . "' limit 1")->result();
                foreach ($user as $dat) {
                    $almacen = $dat->almacen_id;
                    $nombre = $dat->nombre;
                }
                $data['almacen_nombre'] = $nombre;
                $data['almacen_id'] = $almacen;
                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
                //---------------------------------------------
                
                $this->layout->template('member')->show('orden_compra/nclasico', array('data' => $data));
            }
        }
    }
    
    function _codigo() {

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

    public function pendiente() {

        if ($_POST) {

            $data = array(
                'cliente' => $_POST['cliente']['identificacion']
                , 'vendedor' => $_POST['vendedor']
                , 'usuario' => $this->session->userdata('user_id')
                , 'productos' => $_POST['productos']
                , 'total_venta' => $_POST['total_venta']
                , 'pago' => $_POST['pago'],
            );

            /* Registrar venta */
            $id = $this->ventas->pendiente($data);
            echo "pendiente success = " . $id;
        }
    }

    public function get_ajax_data_informe() {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data_informe()));
    }

    public function excel_data_informe($estado=0) {
            
        $informe = $this->ventas->get_ajax_data_informe($estado);
        $reporte = $this->load->library('phpexcel');

        $reporte = new PHPExcel();
        $reporte->setActiveSheetIndex(0);

        $reporte->getActiveSheet()->setCellValue('A1', 'Orden de compra No.');
        $reporte->getActiveSheet()->setCellValue('B1', 'Fecha de pago');
        $reporte->getActiveSheet()->setCellValue('C1', 'NIT');
        $reporte->getActiveSheet()->setCellValue('D1', 'Proveedor');
        $reporte->getActiveSheet()->setCellValue('E1', 'Almacen');
        $reporte->getActiveSheet()->setCellValue('F1', 'Valor del pago');
        $reporte->getActiveSheet()->setCellValue('G1', 'Valor del impuesto');
        $reporte->getActiveSheet()->setCellValue('H1', 'Valor de la orden');
        $reporte->getActiveSheet()->setCellValue('I1', 'Total a pagar');
        $reporte->getActiveSheet()->setCellValue('J1', 'Fecha del pedido');
        $reporte->getActiveSheet()->setCellValue('K1', 'Nota');
        $row = 2;

        foreach ($informe['aaData'] as $value) {
            $reporte->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $reporte->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $reporte->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $reporte->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $reporte->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $reporte->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $reporte->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $reporte->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $reporte->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $reporte->getActiveSheet()->setCellValue('J' . $row, $value[9]);
            $reporte->getActiveSheet()->setCellValue('K' . $row, $value[10]);

            $row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Informe ordenes.xlsx"');
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

    public function pagosrordencompra() {
        acceso_informe('Informe orden de compra');
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')
                ->css(array(base_url("/public/fancybox/jquery.fancybox.css")))
                ->js(array(base_url("/public/fancybox/jquery.fancybox.js")))
                ->show('orden_compra/informe_orden_compra',array('data'=>$data));
    }

    public function get_ajax_data() {
        
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data()));
    }

    function editar($id) {

        /* var_dump($this->db->get('venta'));  */

        $this->ventas->edit($id);

        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["clientes"] = $this->clientes->get_all(0);
        $data['vendedores'] = $this->vendedores->get_combo_data();
        $data['forma_pago'] = $this->pagos->get_tipos_pago();

        $this->layout->template('ventas')
                ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))
                ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))
                ->show('ventas/nuevo', array('data' => $data));
    }


    function cargar_detalle_orden($id = NULL){
        if($id != NULL){
            $mov_valido = $this->ventas->validar_movimiento($id);
            if($mov_valido == NULL){
                $this->load->model("miempresa_model", 'mi_empresa');
                $this->mi_empresa->initialize($this->dbConnection);
                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data_unidades = $this->unidades->get_unidades_id($id);
        
                $data = array(
                    //'venta' => $this->ventas->get_by_id($id)
                    'venta' => $this->ventas->getDetailTaskOrder($id)
                    , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
                    , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
                    , 'data_empresa' => $data_empresa
                    , 'tipo_factura' => $data_empresa['data']['tipo_factura']
                    , 'data_unidades' => $data_unidades,
                );
                //     $data['cod'] = $this->_codigo();
                //Factura clasica -------------------------------------------------------------------------------------------------------
                $data['impuestos'] = $this->impuestos->get_combo_data_factura();
                $data['unidades'] = $this->unidades->get_combo_data_factura_unidades();
                $this->layout->template('member')->show('orden_compra/inventario', array('data' => $data, 'id' => $id));
            }else{
                echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Esta orden de compra ya fue ingresada')
                window.location.href='../orden_compra/index/';
                </SCRIPT>");
            }
        }
    }

    function inventario() {

        /* var_dump($this->db->get('venta'));  */
        //$id1 = $this->ventas->afectar_inventario_si_no($id);
        $id = $this->input->post("id");
        $productos = $this->input->post("id_producto");
        $men="";
        if($id != NULL && $productos != NULL){ 
            $mov_valido = $this->ventas->validar_movimiento($id);
            if($mov_valido == NULL){
                $men=$this->ventas->afectar_inventario_nuevo();               
                if($men==1){                     
                    //modifico el valor de la orden de compra si fuese el caso
                    $total_orden=$this->input->post("input_total_civa");
                    $id = $this->input->post("id");
                    $this->ventas->update_valor_total(array('total_venta'=>$total_orden), array('id'=>$id));                                              
                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));
                    redirect("orden_compra/index/");
                }else{
                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', $men));
                    redirect("orden_compra/index/");
                }
            }else{
                echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Esta orden de compra ya fue ingresada')
                window.location.href='../orden_compra/index/';
                </SCRIPT>");
            }
        }else{
            redirect("orden_compra/index/");
        }
    }

    function eliminar_producto($id, $prod) {

        $this->ventas->eliminar_producto($prod);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha quitado correctamente el producto"));

        redirect("orden_compra/inventario/" . $id);
    }


    function devolver_productos_by_orden($id_orden) {         
       $response = array(
           "resp" => "0"
       );
       if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        //$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        //buscar el almacen que fue afectado por la orden
        $orden=$this->ventas->get_by_id($id_orden);
        $id_almacen=$orden['almacen_id'];
        
        /** Eliminar pago movimiento cierre caja */
        //$this->pagos->delete($id_venta);
        //$this->ventas->eliminar_orden_compra($id_orden);
        $pagos = $this->pagos->get_pagos_compra(array('id_factura'=>$id_orden));

        foreach($pagos as $pago){
            $this->pagos->delete_orden($pago->id_pago);
        }
        /** Eliminar pago movimiento cierre caja */

        if (isset($_POST['productos']) && count($_POST['productos']) > 0) {
            $productos = $this->input->post('productos');
            $unidades = $this->input->post('unidades');
            $precios = $this->input->post('precio');

            foreach($productos as $key => $value){
                $product_data = $this->productos->get_total_inventario_producto($key,$id_almacen);
                
                $producto = array(
                    "cantidad" => $unidades[$key],
                    "precio_compra" => $product_data->precio_compra,
                    "codigo_barra" => $product_data->codigo,
                    "nombre" => $product_data->nombre,
                    "existencias" => $product_data->stock_actual,
                    "total_inventario" => ($product_data->precio_compra * $unidades[$key]),
                    "producto_id" => $product_data->id
                );

                $data_movimiento = array(
                    "user_id" => $this->ion_auth->get_user_id(),
                    "fecha" => date("Y-m-d h:i:s"),
                    "producto" => $producto,
                    "almacen_id" => $id_almacen,
                    "codigo_factura" => $id_orden,
                    "tipo_movimiento" => 'devolucion_orden',
                    "total_inventario" => ($product_data->precio_compra * $unidades[$key]),
                    "nota" => ''
                );
                
                $id = $this->inventario->add_by_auditoria($data_movimiento);
                if($id != NULL){

                    $this->ventas->actualizar_orden_compra($id_orden, $key, $unidades[$key]);

                    $response["resp"] = 1;
                    $response["message"] = "Se ha modificado la orden de compra con los productos devueltos, puede verificar los movimientos de inventario.";
                }
            }
            
        }
        echo json_encode($response);
    }

    function anular(){

        $id1 = $this->ventas->afectar_inventario_si_no($_POST['venta_id']);
      
        $data = array(
            'id' => $_POST['venta_id']
            , 'id_user_anulacion' => $this->session->userdata('user_id')
            , 'motivo' => $_POST['motivo']
            ,'estado' => -1
            ,'fecha_anulacion' => date("Y-m-d H:i:s")
        );
       
        //$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());

        /** Eliminar pago movimiento cierre caja */
        //$this->pagos->delete($id_venta);
        //$this->ventas->eliminar_orden_compra($id_orden);
        $pagos = $this->pagos->get_pagos_compra(array('id_factura'=>$_POST['venta_id']));

        foreach($pagos as $pago){
            $this->pagos->delete_orden($pago->id_pago);
        }
        /** Eliminar pago movimiento cierre caja */


        //buscar el almacen que fue afectado por la orden
        $orden=$this->ventas->get_by_id($_POST['venta_id']);
        $id_almacen=$orden['almacen_id'];     

        if(($id1!=NULL) &&($id1==$_POST['venta_id'])){
            $detalle_orden_compra = $this->ventas->get_orden_compra($data);

            foreach($detalle_orden_compra as $product){
                
                $product_data = $this->productos->get_total_inventario_producto($product["producto_id"],$id_almacen);
                
                $producto = array(
                    "cantidad" => $product["unidades"],
                    "precio_compra" => $product_data->precio_compra,
                    "codigo_barra" => $product_data->codigo,
                    "nombre" => $product_data->nombre,
                    "existencias" => $product_data->stock_actual,
                    "total_inventario" => ($product_data->precio_compra * $product["unidades"]),
                    "producto_id" => $product_data->id
                );

                $data_movimiento = array(
                    "user_id" => $this->ion_auth->get_user_id(),
                    "fecha" => date("Y-m-d h:i:s"),
                    "producto" => $producto,
                    "almacen_id" => $id_almacen,
                    "codigo_factura" => $_POST['venta_id'],
                    "tipo_movimiento" => 'devolucion_orden',
                    "total_inventario" => ($product_data->precio_compra * $product["unidades"]),
                    "nota" => ''
                );
                
                $this->inventario->add_by_auditoria($data_movimiento);
            }
        }        

        $this->ventas->anular($data);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente"));

        redirect("orden_compra/index"); 
    }


    function lv_anular(){

        $id_orden = $this->uri->segment(3);

        $id1 = $this->ventas->afectar_inventario_si_no($id_orden);
      
        if ($id1 == null) {
            echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Esta orden de compra no ha afectado el inventario')
                window.location.href='../orden_compra/index/';
                </SCRIPT>");
        }
                
        //$productos_orden  = $this->orden->consultar_productos($id_orden);
        $data = array(
            "orden" => $id_orden
        );
        $this->layout->template('member')->show('orden_compra/anular', array('data' => $data));
    }

    function obtener_productos($id){

        $productos = $this->ventas->obtener_productos($id);

        $data = (count($productos) > 0) ? $productos : [];

        $response = [
            'aaData' => $data,
            'iTotalDisplayRecords' => 0,
            'iTotalRecords' => 0,
            'sEcho' => 1
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
  
    }


    function index($estado = 0) {
        
        $action = "orden_compra/index";
            if ($estado == -1) {
                $action = "orden_compra/ordenes_anuladas";
            }

            $this->ventas->actualizarCantidad();
            $data_empresa = $this->miempresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            //Crear Opcion si no existe
            $this->miempresa->crearOpcion("plantilla_orden_compra","Estandar");
            $this->ventas->actualizarTabla($this->session->userdata('base_dato'));
            $this->layout->template('member')
                ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))
                ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))
                ->show($action,array("data" => $data));
    }

    public function eliminar_orden_compra($id_orden){
        $id1 = $this->ventas->afectar_inventario_si_no($id_orden);
        if ($id1 == null) {
            $data = array(
                'id' => $id_orden            
            );
            $this->ventas->anular($data);
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente"));
            redirect("orden_compra/index"); 
            /*$this->ventas->eliminar_orden_compra($id_orden);
            $data_empresa = $this->miempresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')
                ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))
                ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))
                ->show('orden_compra/index',array("data" => $data));
                */
        }else{
            echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('No puedes anular esta orden de compra, hay productos que no tienen el stock suficiente')
                window.location.href='../orden_compra/index/';
                </SCRIPT>");
        }
    }


    public function ventas_anuladas() {
        $this->index(-1);
    }

    public function get_ajax_data_anuladas() {        
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data(-1)));
    }

    public function imprimir($id) {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data_unidades = $this->unidades->get_unidades_id($id);
        
        //Crear Opcion si no existe
        $this->miempresa->crearOpcion("plantilla_orden_compra","Estandar");
        
        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'data_empresa' => $data_empresa
            , 'tipo_factura' => $data_empresa['data']['tipo_factura']
            , 'data_unidades' => $data_unidades,
        );
        if($this->opciones->getOpcion('plantilla_orden_compra') == "Estandar")
        {
            $this->layout->template('ajax')->show('orden_compra/_imprimemediacarta4', array('data' => $data));
        }else{
            //$data['data']['detalle_venta'] = $this->ventas->get_detalles_ventasPrecios($id);
            $this->layout->template('ajax')->show('orden_compra/_imprimemediacarta4detallada', array('data' => $data));
        }
        
    }

    public function enviar_email($id) {

        $empresa = $this->miempresa->get_data_empresa();

        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'data_empresa' => $empresa,
        );

        //Html factura ===============================================================
        /* var_dump($data); */

        $html = '
    <head>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <style>

          body{
            font-family: sans-serif;
            background-color:#FFFFFF;
            font-size:9pt;
          }

          .header{
            font-size:10pt;
          }

          #contenedor{
            margin: 0px 50px 0 50px;
            width:100%;
          }

          #ticket_wrapper{
            width: 50%;
          }

          table{
            width: 492px!important;
          }

          #print_area{
            border:0px;
          }

          .resolucion{
             font-size:8pt;
          }

      </style>

    </head>';

        $html .= '<body>';

        $html .= '<div id="contenedor">';
        $html .= '<div id="print_area">';
        $html .= '<div id="ticket_wrapper">';
        $html .= '<div id="ticket_header">';
        $html .= '<div align="center" style="margin-top: 5px;">
                 <img src="' . base_url("uploads/{$data['data_empresa']['data']['logotipo']}") . '" width="200" border="0">
               </div>';
        $html .= '<div id="company_name">' . $data['data_empresa']['data']['nombre'] . '</div>';
        $html .= '<div id="company_nit">Nit:' . $data['data_empresa']['data']['nit'] . '</div>';
        $html .= '<div id="company_almacen">' . $data['venta']['nombre'] . '</div>';
        $html .= '<table id="ticket_company" align="center">
                <tbody>
                  <tr>
                    <td style="width:65%;text-align: left;">' . $data['data_empresa']['data']['direccion'] . '</td>
                    <td style="width:35%;text-align: right;">' . $data['data_empresa']['data']['telefono'] . '</td>
                  </tr>
                </tbody>
               </table>';
        $html .= '<table id="ticket_factura" align="center">
                  <tbody>
                    <tr>
                         <td style="width:45%;text-align: left;">Factura de venta:' . $data['venta']['factura'] . '</td>
                         <td style="width:55%;text-align: right;">Fecha expedición: ' . $data['venta']['fecha'] . '</td>
                         <td style="width:55%;text-align: right;">Fecha vencimiento: ' . $data['venta']['fecha_vencimiento'] . '</td>
                     </tr>
                  </tbody>
                </table>';

        $html .= '<div id="customer">Cliente:' . $data['venta']['nombre_comercial'] . '<br> CC Cliente:' . $data['venta']['nif_cif'] . '</div>';
        $html .= '<div id="customer">Teléfono:' . $data['venta']['telefono'] . '</div>';
        $html .= '<div id="seller">Vendedor:' . $data['venta']['vendedor'] . '</div>';
        $html .= '</div>';

        $html .= '
        <table id="ticket_items">
          <tbody>
            <tr>
                <th style="width:20%;text-align: left;">Ref</th>
                <th style="width:20%;text-align:center;">Cant</th>
                <th style="width:20%;text-align:right;">Precio</th>
                <th style="width:20%;text-align:center;">Desc</th>
                <th style="width:20%;text-align:right;">Total</th>
            </tr>';

        $total = 0;
        $timp = 0;
        $subtotal = 0;
        $total_items = 0;

        foreach ($data["detalle_venta"] as $p) {

            $pv = $p['precio_venta'];
            $desc = $p['descuento'];
            $pvd = $pv - $desc;
            $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

            $total_column = $pvd * $p['unidades'];
            $total_items += $total_column;
            $valor_total = $pvd * $p['unidades'] + $imp;
            $total += $total + $valor_total;

            $timp += $imp;

            $html .=
                    "
          <tr>
              <td>" . $p["nombre_producto"] . "</td>
              <td style='text-align:center;'>" . $p["unidades"] . "</td>
              <td style='text-align:right;'>" . number_format($p["precio_venta"]) . "</td>
              <td style='text-align:center;'>" . $p['descuento'] . "</td>
              <td style='text-align:right;'>" . number_format($valor_total) . "</td>
          </tr>";
        }

        $html .=
                '<tr>
          <td colspan="5"><hr/></td>
        </tr>';

        $total = $total_items + $timp;

        $html .=
                '<tr style="border-top: 2px dotted #000;">
            <td colspan="4" style="text-align:right;">Valor items</td>
            <td style="text-align:right">' . number_format($total_items) . '</td>
        </tr>';

        $html .=
                '<tr>
            <td colspan="4" style="text-align:right;">Impuestos</td>
            <td style="text-align:right">' . number_format($timp) . '</td>
        </tr>';

        $html .=
                '<tr>
            <td colspan="5"><hr/></td>
        </tr>';

        $html .=
                '<tr>
            <td colspan="4" style="text-align:right;">Total venta</td>
            <td  style="text-align:right">' . number_format($total) . '</td>
        </tr>';

        $html .=
                '<tr>
          <td colspan="4" style="text-align:right;">Efectivo</td>
          <td  style="text-align:right">' . number_format($data['detalle_pago']['valor_entregado']) . '</td>
        </tr>';

        $html .=
                '<tr>
            <td colspan="4" style="text-align:right;">Cambio</td>
            <td  style="text-align:right">' . number_format($data['detalle_pago']['cambio']) . '</td>
        </tr>';

        $html .=
                '<tr>
            <td colspan="5"><br></td>
        </tr>';

        $html .= '
          </tbody>
        </table>

        <br/>';

        $html .=
                '<div align="center" style="padding-bottom:-10px;">
           ' . $data['data_empresa']["data"]['terminos_condiciones'] . '
        </div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<body>';
        //==============================================================================

        echo $html;

        /* die(); */
        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf_name = "factura-" . $data['venta']['id_venta'] . "-" . $data['venta']['factura'] . ".pdf";

        ob_clean();

        $pdf->Output("uploads/" . $pdf_name, 'F');

        $this->load->library('email');

        $this->email->clear();
        $this->email->from('cotizacion@hotmail.com', $empresa["data"]['nombre']);

        if (empty($empresa["data"]['email'])) {
            $this->email->to('comercial@sistematizamos.com');
        } else {
            $this->email->to($data['venta']['email']);
            $this->email->bcc('comercial@sistematizamos.com');
        }

        /*   $this->email->subject("Cotización {$data_factura["numero"]}"); */
        $this->email->subject("Factura " . $empresa["data"]['nombre']);
        $this->email->attach("uploads/$pdf_name");
        $this->email->message("Para ver su factura descargue el adjunto.");
        $this->email->send();
    }

    public function pagos_servicio($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        $venta = $this->ventas->get_by_id($id);
        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
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
                'venta' => $venta
                , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
                , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
                , 'data_empresa' => $data_empresa,
            );

            $data['tipo'] = $this->pagos->get_tipos_pago();
            $data["total"] = $this->pagos->get_total_orden_compra($id);
            $data["data"] = $this->pagos->get_all_orden_compra($id, 0);
            $data["forma_pago"] =$this->forma_pago->getActiva();
            $numero = $this->ventas->get_by_id($id);
            $data['numero'] = $numero["factura"];
            $data["id_factura"] = $id;
            $data_empresa = $this->miempresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $data["estado_caja"] = "cerrada";
            $data['cuentas_dinero'] = $this->cuentas_dinero->get_all('0');
            
            //verifico si la caja esta abierta
            if ($this->session->userdata('caja') != ""){
                $data["estado_caja"] = "abierta";
            }
            else{
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
                        $data["estado_caja"] = "abierta";
                    }  
                }else{
                    $data["estado_caja"] = "abierta";
                }
            }
            //$data["estado_caja"] = "cerrada";
          //  print_r($data); die();

            $data['categorias_gastos'] = $this->proformas->get_categorias();
            $data['bancos'] = $this->bancos->get_bancos();

            $this->layout->template('member')->show('pagos/ver_pago_orden', array('data' => $data));
        }
    }
    
    //Conocer productos que no fueroin ingresado correctamente en Woocommerce
    public function pruebaProductos()
    {
        if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015')
        {
            if(isset($_POST['post']))
            {
                $this->productos->consultarWC();
                //echo json_encode($this->productos->consultarWC());
            }else{
                $this->layout->template('member')->show('orden_compra/wcProductos');
            }
        }else
        {
            redirect("orden_compra/index/");
        }
    }


    public function cargar_productos(){

        try {
            $this->load->library('phpexcel');
            $tname = $_FILES['archivo']['tmp_name'];
            $obj_excel = PHPExcel_IOFactory::load($tname);
            $sheetData = $obj_excel
                    ->getActiveSheet()
                    ->toArray(null, true, true, true);

            $data = array(); 
            $carpeta = 'uploads/archivos_productos/';
            $config['upload_path'] = $carpeta;
            $config['allowed_types'] = 'xlsx|xls';
            $prefijo = substr(md5(uniqid(rand())), 0, 8);
            $config['file_name'] = $prefijo . $this->session->userdata('user_id');
            $this->load->library('upload', $config);            
            $i = 0;
            $errores = array();
            $columnas = $this->__get_header_productos_con_atributos();            
            $array_errores = [];
            $datos_fallo = false; 
            $valido = true;
            $esnumero="/^[0-9]+([.][0-9]+)?$/";
            $tengoregistro=0;
            
            if (count($sheetData[1]) != count($columnas)) {
                    $sheet = array_values($sheetData[1]);

                    $last_index = count($sheet) - 1;
                    if (count($sheetData[1]) == count($columnas) + 1 && $sheet[$last_index] == 'errores') {
                        
                    } else {
                        $datos_fallo = true;                       
                    }
                }

            $columnas['errores'] = 'errores';
            array_push($array_errores, $columnas);
            
            // Inicio procesamiento de archivo
            if (!$datos_fallo) {
                if(count($sheetData) < 500){                
                    foreach($sheetData as $campo){
                        $regist = array_values($campo);
                        if($campo["A"] != "" && $i > 0){
                            $error = '';
                            $tengoregistro++;
                            //verificar que existen los datos
                            //producto                       
                            $producto = $this->productos->get_product_by_code($campo["A"]);  
                            if($producto ==NULL){
                                $error .=",No existe el código";
                                $valido = false;
                            }           
                            //unidad
                            if($campo["B"] != ""){
                                $unidad = $this->unidades->get_unidades(array('nombre' => $campo["B"]));                                              
                                if($unidad==NULL){                           
                                    $error .=",No existe la unidad";
                                    $valido = false;
                                }
                            }else{
                                $error .=",La unidad no puede ser blanco";
                                $valido = false;
                            }
                           
                            //Cantidad 
                            if(($campo["C"]) != ""){                                
                                if(!preg_match($esnumero, $campo["C"])){                                    
                                    $error .=",La cantidad debe ser mayor a cero. Solo debe contener números y si es decimal por con el '.'";
                                    $valido = false;
                                }     
                            }else{
                                $error .=",La cantidad no puede ser blanco";
                                $valido = false;
                            }                        
                            
                            //Precio   
                            if($campo["D"] != ""){
                                if(!preg_match($esnumero, $campo["D"])){
                                    $error .=",El precio debe ser mayor o igual a cero. Solo debe contener números y si es decimal por con el '.' ";
                                    $valido = false;
                                }
                            }else{
                                $error .=",El precio no puede ser blanco";
                                $valido = false;
                            }                    
                            
                            //Descuento  
                            if($campo["E"] != ""){                      
                                if(!preg_match($esnumero, $campo["E"])){
                                    $error .=",El descuento debe ser mayor o igual a cero. Solo debe contener números y si es decimal con el '.' ";
                                    $valido = false;
                                }
                            }else{
                                $error .=",El descuento no puede ser blanco";
                                $valido = false;
                            }

                            //impuesto                            
                            if($campo["F"] != ""){                              
                                if(preg_match($esnumero, $campo["F"])){                                
                                    $impuesto = $this->impuestos->get_name_by_porcent($campo["F"]);
                                    if($impuesto==NULL){
                                        $error .=",No existe el impuesto";
                                        $valido = false;
                                    }
                                }else{
                                    $error .=",El impuesto debe ser mayor o igual a 0. Solo debe contener números y si es decimal con el '.'";
                                    $valido = false;
                                }
                            }else{
                                $error .=",El impuesto no puede ser blanco";
                                $valido = false;
                            }
                            
                            $error=trim($error,',');
                            $regist['errores'] = $error;
                            array_push($array_errores, $regist);

                            if ($valido == false)
                                $datos_fallo = true;
                        
                            if(empty($error)){
                                $id_producto = $producto->id;
                                $nombre_producto = $producto->nombre;
                                $precio_venta_real = $producto->precio_venta;
                                $nombre_impuesto = $impuesto->nombre_impuesto;
                                $data[] = array(
                                    "id_producto" => $id_producto,
                                    "codigo_producto" => $campo["A"], 
                                    "nombre_producto" => $nombre_producto, 
                                    "unidades" => $campo["B"], 
                                    "cantidad" => $campo["C"], 
                                    "precio_venta_real" => $precio_venta_real,
                                    "precio_unitario" => $campo["D"], //PRECIO DE COMPRA
                                    "utilidad" => $precio_venta_real - $campo["D"],
                                    "descuento" => $campo["E"], 
                                    "impuesto" => $campo["F"],
                                    "nombre_impuesto" => $nombre_impuesto,
                                    "id_impuesto" => $campo["C"].'_'.$nombre_producto
                                );
                            }
                        }
                        $i++;
                    }
                   
                    if (!$datos_fallo) {   
                        if($tengoregistro>0){
                            echo json_encode($data);
                        }else{
                            echo 5;
                        }                                                        
                    }else{                        
                        echo 3;
                        $route_file = $this->__create_error_report_file($array_errores); 
                    }                    
                }
                else{
                    echo 2;
                }
            }else{
                echo 4;
            }            
        } catch (Exception $e) {
            echo 0;
        }                 
    }

    private function __create_error_report_file(array $data) {       
        $carpeta = "uploads/".$this->session->userdata('base_dato')."/archivos_productos";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        @chmod("../../uploads/".$this->session->userdata('base_dato')."/archivos_productos/", 0777);
        @unlink("../../uploads/".$this->session->userdata('base_dato')."/archivos_productos/ordendecompranoguardado.xlsx");

        $hoja_productos = $this->load->library('phpexcel');
        $hoja_productos = new PHPExcel();
        $hoja_productos->setActiveSheetIndex(0);

        $hoja_productos->getActiveSheet()->fromArray($data, null, 'A1');

        foreach ($hoja_productos->getWorksheetIterator() as $worksheet) {
            $hoja_productos->setActiveSheetIndex($hoja_productos->getIndex($worksheet));

            $sheet = $hoja_productos->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

       
        $hoja_productos->getActiveSheet()->setTitle('ordendecomprar');

        $objWriter = PHPExcel_IOFactory::createWriter($hoja_productos, 'Excel2007');
        $objWriter->save("uploads/".$this->session->userdata('base_dato')."/archivos_productos/ordendecompranoguardado.xlsx");
        
        $route_file = $carpeta .'/ordendecompranoguardado.xlsx';
        return $route_file;
    }

    private function __get_header_productos_con_atributos() {
      
        $columnas = [
            'codigo del producto',
            'unidades',
            'cantidad',
            'Precio unitario',
            'Descuento(%)',
            'impuesto (Valor)',
        ];
        return $columnas;
    }

    public function load_additions()
    {
        $url = 'take-order/orders/' . $this->input->get('id');
        echo json_encode(get_curl($url, $this->session->userdata('token_api')));
    }

    public function add_addition()
    {
        $url = 'take-order/product/addition/add';
        echo json_encode(post_curl($url, json_encode($this->input->post()), $this->session->userdata('token_api')));
    }

    public function remove_addition()
    {
        $url = 'take-order/product/addition/remove';
        echo json_encode(post_curl($url, json_encode($this->input->post()), $this->session->userdata('token_api')));
    }

    public function add_modify()
    {
        $url = 'take-order/product/modification/add';
        echo json_encode(post_curl($url, json_encode($this->input->post()), $this->session->userdata('token_api')));
    }

    public function remove_modify()
    {
        $url = 'take-order/product/modification/remove';
        echo json_encode(post_curl($url, json_encode($this->input->post()), $this->session->userdata('token_api')));
    }

    public function get_last_order() {
        $url = 'take-order/' . $this->input->get('product') . '/' . $this->input->get('zone') . '/' . $this->input->get('table') . '/last';
        echo json_encode(get_curl($url, $this->session->userdata('token_api')));
    }

    public function create_new_modification() {
        $url = 'take-order/product/modification/create';
        echo json_encode(post_curl($url, json_encode($this->input->post()), $this->session->userdata('token_api')));
    }

    public function save_log_quick_service($request){
        
        $data = array(
            'user_id' => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'email' => $this->session->userdata('email'),
            'database' => $this->session->userdata('base_dato'),
            'request' => $request
        );

        $this->ordenes->save_log_quick_service($data);
    }
}
?>

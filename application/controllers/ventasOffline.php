<?php

//error_reporting(1);
//ultima actualizacion 2015-12-29

class VentasOffline extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";               

        $this->dbConnection = $this->load->database($dns, true);

        
        $this->load->model('dashboard_model');
        $this->dashboard_model->initialize($this->dbConnection);
        
        $this->load->model( 'backend/db_config/db_config_model', "dbconfig" );


        $this->load->model("ventas_model", 'ventas');

        $this->ventas->initialize($this->dbConnection);


        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);


        $this->load->model("vendedores_model", 'vendedores');

        $this->vendedores->initialize($this->dbConnection);


        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        $this->load->model("clientes_model", 'clientes');

        $this->clientes->initialize($this->dbConnection);

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

        $this->facturas->initialize($this->dbConnection);

        $this->load->model("opciones_model", 'opciones');

        $this->opciones->initialize($this->dbConnection);


        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }


    public function queryOfflineAjax() {

        $getEmail = $this->input->get('mail', TRUE);

        // Get query from model
        $queryResult = $this->dashboard_model->queryOfflineAjax($getEmail);
        
        $this->output->set_content_type('application/json')->set_output('{"status":"'.$queryResult.'"}');
        
    }
    
    
    public function setOffline() {
        
        $email = $this->input->post('email', TRUE);
        $tipo = $this->input->post('tipo', TRUE);
        
        $status = null;
        
        if($tipo == "desactivar")
            $status = "false";
        if($tipo == "activar")
            $status = "active";
        if($tipo == "guardar")
            $status = "backup";
        
        $this->dashboard_model->setOffline( $email, $status);
        
        $this->output->set_content_type('application/json')->set_output('{"status":"ok"}');
        
    }    
    
    
    // vista para signar status offline
    public function asignar(){        
        $this->layout->template('member')->show( 'ventas/offlineAssignar');        
    }
    
    public function importarClientes(){
        
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $array = $this->input->post("data");
        
        
        foreach($array as $item){
                        
            $str = $item["obj"];
            
            $cliente = json_decode( $str, true);                        
            
            $this->nuevoCliente( $cliente );
            
        }
        
        $this->output->set_content_type('application/json')->set_output('{"success":"true","n":"'.count($array).'"}');
            
    }

    public function nuevoCliente($data){
        
        // Convirtiendo a POST        
        
        $_POST = array();
        
        $keys = array_keys($data);
        foreach($keys as $key){                        
            $_POST[$key] = $data[$key];            
        }
        
        // FIN Convirtiendo a POST                

        
        $nombre_comercial = $this->input->post('nombre_comercial');            
        $tipo_identificacion = $this->input->post('tipo_identificacion');
        $nif_cif = $this->input->post('nif_cif');
        $email = $this->input->post('email');			
        $telefono = $this->input->post('telefono');			
        $direccion = $this->input->post('direccion');
        $pais = $this->input->post('pais');  
        $ciudad = $this->input->post('provincia'); 	
        $celular = $this->input->post('celular'); 
        $plan = $this->input->post('plan_puntos');  
        $plan_punto = $this->input->post('pl'); 
        $cod = $this->input->post('cod_targeta');  			         

        $id_cliente = $this->clientes->add_light(
            $nombre_comercial, 
            $tipo_identificacion, 
            $nif_cif, 
            $email, 
            $telefono, 
            $direccion, 
            $pais, 
            $ciudad, 
            $celular, 
            $plan, 
            $plan_punto, 
            $cod
        );

    }    
    
    function facturas() {
        echo $this->ventas->get_detalle_venta();
        //return $this->ventas->get_detalles_ventas($id);
    }
    
    function ventas() {

        $this->layout->template('ventasOffline')
            ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css") ))
            ->js(array( base_url("/public/fancybox/jquery.fancybox.js") ))
            ->show( 'ventas/nuevoOfflineFacturas');

    }
    function nuevo($num = 1) {

        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();

        $data['pais'] = $this->pais_provincia->get_pais();
				

        


            $data["grupo_clientes"] = $this->clientes->get_group_all(0);

            $data["clientes"] = $this->clientes->get_all(0);

            //$data["espera_detalles"] = $this->ventas->get_all_espera_detalles($id1 = 0);

            $data['vendedores'] = $this->vendedores->get_combo_data();

            //Vitrina categorias----------------------------------------------------------- //
            $data['categorias'] = $this->categorias->get_limit(0);
            //...............................................................................
            //$data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));

            //$data['forma_pago'] = $this->pagos->get_tipos_pago();

            $data['forma_pago'] = $this->pagos->get_tipos_pagoOffline();
            

            $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];

            $data['comanda'] = $data_empresa['data']['comanda'];

            $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];

            $data['nit'] = $data_empresa['data']['nit'];

            $data['pais'] = $this->clientes->get_pais();

            $data['plan_puntos'] = $this->puntos->plan_puntos();

            $data['si_no_plan_punto'] = $this->puntos->si_no_plan_punto();

            $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

            $data['num'] = $num;

            $data['__decimales__']=$this->opciones->getOpcion("decimales_moneda"); 
            $data['__separadorDecimal__']=$this->opciones->getOpcion("tipo_separador_decimales"); 
            $data['__separadorMiles__']=$this->opciones->getOpcion("tipo_separador_miles"); 
            $data['__redondear__']=$this->opciones->getOpcion("redondear_precios"); 

            //Factura estandar --------------------------------------------------------------------------------------


                //======================================================
                //  EDWIN  
                //======================================================

                if (getUiVersion() == "v2")
                    $vistaVenta = 'ventas/nuevoV2';
                else
                    $vistaVenta = 'ventas/nuevo';


                
                $html = $this->layout->template('ventasOffline')
                        ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))
                        ->js(array(base_url("/public/js/ventasOffline.js"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))
                        ->show( 'ventas/nuevoOfflineV2', array('data' => $data));

                //======================================================
                //  FIN EDWIN  
                //======================================================
           
        
    }
    
	
    
    function importar0() {
        
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $array = $this->input->post("data");            
        
        foreach($array as $item){
                        
            $str = $item["obj"];
            
            $venta = json_decode( $str, true);                        
            
            $this->nuevoOffline( $venta );
            
        }
        
        $this->output->set_content_type('application/json')->set_output('{"success":"true","n":"'.count($array).'"}');        
        
    }
    
    function importar() {
			
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');
			
 				if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }
			
				

					$this->load->model("miempresa_model", 'mi_empresa');

					$this->mi_empresa->initialize($this->dbConnection);

					$data_empresa = $this->mi_empresa->get_data_empresa();
			
			
        	$array = $this->input->post("data");            
            
					$fullData = array();
			
        foreach($array as $item){
                        
            $str = $item["obj"];
            
            $data = json_decode( $str, true);                        
            
            //======================================================================
						//======================================================================

					 // Convirtiendo a POST        
        
					$_POST = array();

					$keys = array_keys($data);
					foreach($keys as $key){                        
							$_POST[$key] = $data[$key];            
					}

					// FIN Convirtiendo a POST        



					$data = array();

					$data['pais'] = $this->pais_provincia->get_pais();
					/*
					if ($data_empresa['data']['valor_caja'] == 'si') {

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

											header('Location: ../caja/apertura');
									}
							}
					}*/

					if (isset($_POST['forma_pago'])) {
							print_r($_POST);

							$pago_1 = (isset($_POST['pago_1'])) ? $_POST['pago_1'] : $_POST['forma_pago'];
							$pago_2 = (isset($_POST['pago_2'])) ? $_POST['pago_2'] : $_POST['forma_pago'];
							$pago_3 = (isset($_POST['pago_3'])) ? $_POST['pago_3'] : $_POST['forma_pago'];
							$pago_4 = (isset($_POST['pago_4'])) ? $_POST['pago_4'] : $_POST['forma_pago'];
							$pago_5 = (isset($_POST['pago_5'])) ? $_POST['pago_5'] : $_POST['forma_pago'];
					}


					 
					if (isset($_POST['vendedor'])) {

							//Identifica si una venta fue por POS o por Servicios
							if ($data_empresa['data']['tipo_factura'] != 'clasico') {

									$pago = $_POST['pago'];

									$pago_1 = $_POST['pago_1'];
									$pago_2 = $_POST['pago_2'];
									$pago_3 = $_POST['pago_3'];
									$pago_4 = $_POST['pago_4'];
									$pago_5 = $_POST['pago_5'];

									$tipo_factura = 'estandar';
									$fecha = date('Y-m-d H:i:s');
									$fecha_vencimiento = date('Y-m-d H:i:s');
							} else {



									$pago = array(
											'valor_entregado' => $_POST['total_venta'],
											'cambio' => 0,
											'forma_pago' => $_POST['forma_pago']
									);

									$pago_1 = $_POST['pago_1'];
									$pago_2 = $_POST['pago_2'];
									$pago_3 = $_POST['pago_3'];
									$pago_4 = $_POST['pago_4'];
									$pago_5 = $_POST['pago_5'];

									$tipo_factura = 'clasico';
									$fecha = $_POST['fecha'] . " " . date('H:i:s');
									$fecha_vencimiento = $_POST['fecha_v'];
							}

							$fecha = $_POST['fecha'];
                            
							$data = array(
											'fecha' => $fecha,
											'fecha_vencimiento' => $fecha_vencimiento,
											'cliente' => $_POST['cliente'],
											'vendedor' => $_POST['vendedor'],
											'vendedor_2' => "",
											'usuario' => $this->session->userdata('user_id'),
											'productos' => $_POST['productos'],
											//'total_venta' => ($_POST['total_venta'] - (($_POST['total_venta'] * $_POST['descuento_general']) / 100)),
											'total_venta' => $_POST['total_venta'],
											'pago' => $pago,
											'pago_1' => $pago_1,
											'pago_2' => $pago_2,
											'pago_3' => $pago_3,
											'pago_4' => $pago_4,
											'pago_5' => $pago_5,
											'tipo_factura' => $tipo_factura,
											'nota' => $_POST['nota'],
											'promocion' => "",
											'descuento_general' => $_POST['descuento_general'],
											'subtotal_input' => $_POST['subtotal_input'],
											'subtotal_propina_input' => $_POST['subtotal_propina_input'],
											'sobrecostos' => ((isset($_POST['propina'])) ? $_POST['propina'] : 0),
											'id_fact_espera' => (isset($_POST['id_fact_espera']) ? $_POST['id_fact_espera'] : ''),
											'sistema' => $data_empresa['data']['sistema']
							);

							// GUARDAR LISTA DE VENTAS PROCESADAS EN ARRAY 
                            $fullData[] = $data;
                            
					} 
					
					  //======================================================================
            
        }
        
				/* Registrar venta */
				$this->ventas->addOffline($fullData);
			
        $this->output->set_content_type('application/json')->set_output('{"success":"true","n":"'.count($fullData).'"}');        
        
    }
	
    function nuevoOffline($data){

        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }
        
        
        // Convirtiendo a POST        
        
        $_POST = array();
        
        $keys = array_keys($data);
        foreach($keys as $key){                        
            $_POST[$key] = $data[$key];            
        }
        
        // FIN Convirtiendo a POST        
                
    
        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();

        $data['pais'] = $this->pais_provincia->get_pais();

        if ($data_empresa['data']['valor_caja'] == 'si') {

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

                    header('Location: ../caja/apertura');
                }
            }
        }

        if (isset($_POST['forma_pago'])) {
            print_r($_POST);

            $pago_1 = (isset($_POST['pago_1'])) ? $_POST['pago_1'] : $_POST['forma_pago'];
            $pago_2 = (isset($_POST['pago_2'])) ? $_POST['pago_2'] : $_POST['forma_pago'];
            $pago_3 = (isset($_POST['pago_3'])) ? $_POST['pago_3'] : $_POST['forma_pago'];
            $pago_4 = (isset($_POST['pago_4'])) ? $_POST['pago_4'] : $_POST['forma_pago'];
            $pago_5 = (isset($_POST['pago_5'])) ? $_POST['pago_5'] : $_POST['forma_pago'];
        }


        /* if($this->form_validation->run('facturas') == true){...} */
        if (isset($_POST['vendedor'])) {

            //Identifica si una venta fue por POS o por Servicios
            if ($data_empresa['data']['tipo_factura'] != 'clasico') {

                $pago = $_POST['pago'];

                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];

                $tipo_factura = 'estandar';
                $fecha = date('Y-m-d H:i:s');
                $fecha_vencimiento = date('Y-m-d H:i:s');
            } else {



                $pago = array(
                    'valor_entregado' => $_POST['total_venta'],
                    'cambio' => 0,
                    'forma_pago' => $_POST['forma_pago']
                );

                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];

                $tipo_factura = 'clasico';
                $fecha = $_POST['fecha'] . " " . date('H:i:s');
                $fecha_vencimiento = $_POST['fecha_v'];
            }
            
            $fecha = $_POST['fecha'];
            
            $data = array(
                    'fecha' => $fecha,
                    'fecha_vencimiento' => $fecha_vencimiento,
                    'cliente' => $_POST['cliente'],
                    'vendedor' => $_POST['vendedor'],
                    'vendedor_2' => "",
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
                    'promocion' => "",
                    'descuento_general' => $_POST['descuento_general'],
                    'subtotal_input' => $_POST['subtotal_input'],
                    'subtotal_propina_input' => $_POST['subtotal_propina_input'],
                    'sobrecostos' => ((isset($_POST['propina'])) ? $_POST['propina'] : 0),
                    'id_fact_espera' => (isset($_POST['id_fact_espera']) ? $_POST['id_fact_espera'] : ''),
                    'sistema' => $data_empresa['data']['sistema']
            );

            /* Registrar venta */
            $id = $this->ventas->addOffline($data);

        } 
        
    }    

    function espera($id = NULL) {

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
            'activo' => '1'
        );

        /* Registrar venta */
        $id = $this->ventas->espera($data, 'espera');

        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));
    }

    function comanda($id = NULL) {
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
            'factura' => $_POST['id_fact_espera_nombre']
        );

        /* Registrar venta */
        $id = $this->ventas->comanda($data, 'comanda');

        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));
    }

    function comanda_imprimir($id) {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $get_by_id = $this->ventas->get_by_id_comanda($id);

        $data = array(
            'venta' => $this->ventas->get_by_id_comanda($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas_comanda($id)
            , 'data_empresa' => $data_empresa
            , 'tipo_factura' => $data_empresa['data']['tipo_factura']
        );

        $this->layout->template('ajax')->show('ventas/_imprime_comanda_primer', array('data' => $data));
    }

    public function detalles_espera() {
        $result = array();
        $id = $this->input->get('id', TRUE);

        $result = $this->ventas->get_all_espera_detalles($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function factura_espera() {

        $result = array();

        $id = $this->input->get('id', TRUE);

        $result = $this->ventas->get_all_espera_factura($id);

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function factura_espera_nombre() {
        $result = array();

        $nom = $this->input->get('nom', TRUE);
        $id = $this->input->get('id', TRUE);

        $result = $this->ventas->get_all_espera_factura_nombre($id, $nom);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    //eliminar factura en espera
    public function factura_espera_eliminar() {

        $result = array();

        $nom = $this->input->get('nom', TRUE);
        // $id = $this->input->get('id', TRUE);

        $result = $this->ventas->eliminar_factura($nom);

        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
    }

    public function eliminar_comanda_temporal() {
        $result = array();
        $id = $this->input->get('id', TRUE);

        $result = $this->ventas->eliminar_comanda_temporal($id);
    }

    public function factura_espera_ultimo() {
        $result = array();
        $id = $this->input->get('id', TRUE);

        $result = $this->ventas->get_all_espera_factura_ultimo($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    function actualizar($id = NULL) {

        /* var_dump($this->db->get('venta'));  */


        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'data_empresa' => $data_empresa
            , 'tipo_factura' => $data_empresa['data']['tipo_factura']
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
                , 'codigo_factura' => $_POST['id']
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
                    , 'almacen_id' => $_POST['almacen']
                );

                $this->ventas->actualizar_venta($data, $id);

                $i++;
            }

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("ventas/index/");
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
                , 'almacen_id' => $_POST['almacen']
            );

            $this->ventas->agregar_actualizar_venta($data, $id);


            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("ventas/actualizar/" . $id);
        }

        //   $data['cod'] = $this->_codigo();
        //Factura clasica -------------------------------------------------------------------------------------------------------
        $data['impuestos'] = $this->impuestos->get_combo_data_factura();
        $this->layout->template('member')
                ->css(array(base_url("/public/css/stylesheets.css"), base_url('public/css/multiselect/multiselect.css')))
                ->show('ventas/actualizar', array('data' => $data, 'id' => $id));
    }

    function eliminar_producto($venta, $id, $producto_id, $prod, $cant, $alm) {

        $this->ventas->eliminar_producto_actualizar($venta, $id, $producto_id, $prod, $cant, $alm);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha quitado correctamente el producto"));

        redirect("ventas/actualizar/" . $venta);
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

            $dig = ((int) $cod );

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
                , 'pago' => $_POST['pago']
            );

            /* Registrar venta */
            $id = $this->ventas->pendiente($data);
            echo "pendiente success = " . $id;
        }
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

    function anular() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data = array(
            'id' => $_POST['venta_id']
            , 'usuario' => $this->session->userdata('user_id')
            , 'motivo' => $_POST['motivo']
        );

        $this->ventas->anular($data);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente"));

        redirect("ventas/index");
    }

    function index($estado = 0) {

        $action = "ventas/index";

        if ($estado == -1) {

            $action = "ventas/ventas_anuladas";
        }
        $caja = '';

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $caja = $data_empresa['data']['valor_caja'];

        $this->layout->template('member')
                ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))
                ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))
                ->show($action, array('caja' => $caja));
    }

    public function ventas_anuladas() {
        $this->index(-1);
    }

    public function get_ajax_data_anuladas() {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data_anuladas(-1)));
    }

    public function imprimir($id) {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $get_by_id = $this->ventas->get_by_id($id);


        $username = '';
        $user = $this->db->query("SELECT username FROM users where id = '" . $get_by_id["usuario_id"] . "'")->result();
        foreach ($user as $dat) {
            $username = $dat->username;
        }

        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id, 'pago')
            , 'detalle_pago_multiples_cambio' => $this->ventas->get_detalles_pago_result($id, 'cambio')
            , 'venta_impuestos' => $this->ventas->venta_impuestos($id)
            , 'puntos_cliente_factura' => $this->puntos->puntos_acumulados_cliente($id, 'factura')
            , 'puntos_cliente_acumulado' => $this->puntos->puntos_acumulados_cliente($id, 'acumulado')
            , 'data_empresa' => $data_empresa
            , 'username' => $username
            , 'tipo_factura' => $data_empresa['data']['tipo_factura']
        );
        if ($data_empresa['data']['plantilla'] == 'media_carta') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'general') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'media_carta_2') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta2', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'media_carta_3') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta3', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta4', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_logo_redondo') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_redondo', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias_decimales') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias_decimales', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_completa_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles_completa', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_codibarras') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_codigo_barras_media', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_1') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_1', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_2') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_2', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq_discriminado_iva') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq', array('data' => $data));
        }else if ($data_empresa['data']['plantilla'] == 'modelo_impresora_factura') {
            $this->layout->template('ajax')->show('ventas/_imprimefacturamatricial', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_factura_clasica') {
            $this->layout->template('ajax')->show('ventas/_imprime_clasica', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'modelo_ticket_58mm') {
            $this->layout->template('ajax')->show('ventas/imprime_ticket_58mm', array('data' => $data));
        } else if ($data_empresa['data']['plantilla'] == 'ticket_productos_atributos') {
            $this->layout->template('ajax')->show('ventas/imprime_ticket_atributos', array('data' => $data));
        } else {

            $this->layout->template('ajax')->show('ventas/imprime', array('data' => $data));
        }
    }

    public function guia_despacho($id) {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'data_empresa' => $data_empresa
            , 'tipo_factura' => $data_empresa['data']['tipo_factura']
        );

        $this->layout->template('ajax')->show('ventas/_imprimemediacartaguiadespacho', array('data' => $data));
    }

    public function enviar_email() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $empresa = $this->miempresa->get_data_empresa();

        $id = $this->input->post('venta_id_ven', TRUE);
        $cuerpo_correo = $this->input->post('cuerpo_correo', TRUE);


        $data = array(
            'venta' => $this->ventas->get_by_id($id)
            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
            , 'venta_impuestos' => $this->ventas->venta_impuestos($id)
            , 'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id)
            , 'data_empresa' => $empresa
        );

        $this->email->clear();

        $this->email->from($empresa["data"]["email"], $empresa["data"]["nombre"]);

        $this->email->to($data['venta']['cliente_email']);

        $this->email->subject($empresa["data"]['titulo_venta']);


        /* die(); */
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
	  <BR>
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

        /* $group_by_impuesto = array(); */

        foreach ($data["detalle_venta"] as $p) {

            if ($p["nombre_producto"] == 'PROPINA') {
                $sobrecosto = $p['descripcion_producto'];
            } else {

                if ($data["tipo_factura"] == 'clasico') {
                    /* SERVICIOS */
                    $pv = $p['precio_venta'];
                    $desc = $p['descuento'];
                    $pvd = $pv - $desc;
                    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                    $total_column = $pvd * $p['unidades'];
                    $total_items += $total_column;
                    $valor_total = $pvd * $p['unidades'] + $imp;
                    $total += $total + $valor_total;
                    $timp+=$imp;
                } else {
                    /* POS */
                    $pv = $p['precio_venta'];
                    $desc = $p['descuento'];
                    $pvd = $pv - $desc;
                    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                    $total_column = $pvd * $p['unidades'];
                    $total_items += $total_column;
                    $valor_total = $pvd * $p['unidades'] + $imp;
                    $total += $total + $valor_total;
                    $timp+=$imp;
                }

                /*  $group_by_impuesto_length= count($group_by_impuesto);

                  if($group_by_impuesto_length==0){
                  array_push($group_by_impuesto, array('impuesto_nombre'=>$p['impuesto_nombre'],'impuesto_valor'=>$imp) );
                  }else{
                  $impuesto_exist = false;
                  for ($i=0; $i <  $group_by_impuesto_length; $i++) {
                  if($p['impuesto_nombre']==$group_by_impuesto[$i]['impuesto_nombre']){
                  $impuesto_exist = true;
                  $group_by_impuesto[$i]['impuesto_valor']=$group_by_impuesto[$i]['impuesto_valor']+$imp;
                  }
                  }
                  if(!$impuesto_exist)
                  array_push($group_by_impuesto, array('impuesto_nombre'=>$p['impuesto_nombre'],'impuesto_valor'=>$imp)  );
                  } */
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

            <td colspan="4" style="text-align:right;">Valor items</td>' . $total = $total_items + $timp . '

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
		
 </td>
               </td>
                </tr>
            </tbody></table>

     <table border="0" cellpadding="0" cellspacing="0" height="200px" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">
             <tbody><tr>
                 <td align="left" valign="top" style="border-collapse: collapse;"><br>
				 Enviado a usted por http://vendty.com<br>
			    </td>
                </tr>
            </tbody></table>
			
			    </td>
                </tr>
            </tbody></table>
			<br><br>	<br>	<br>	<br>	<br>	<br>			
		';

        $this->email->message($html);
        $this->email->send();
        /* 				
          $html = '
          <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; font-size: 9px">
          <tr>
          <td width="33%"  align="center" style=" font-size: 11px">

          NIT  '.$nit.' <br>
          '.$resol.' <br>
          '.$tele.' <br>
          '.$dire.' <br>
          '.$web.'
          </td>

          <td width="33%"  align="center">
          '.($cafac).'
          </td>

          <td width="20%" align="right">
          ';
          if(!empty($empresa["data"]['logotipo'])){
          $pdf->Image($img, 55, 13, 43, 15, 'JPG', '', '', true, 150, 'R', false, false, 0, false, false, false);
          }
          $html .= '
          </td>
          </tr>
          </table>
          ';

          $html .= '
          <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; font-size: 9px">
          <tr>
          <td>
          <b>Fecha</b>  '. $fech.'
          </td>
          <td align="right">
          <b>No.</b> '.$numero.'
          </td>
          </tr>
          <tr>
          <td style="border-top: 1px solid #000000;">
          <b>Cliente: </b> '.$nomcomercial_cli.'
          </td>
          <td style="border-top: 1px solid #000000;">
          <b>Dirección: </b>
          '.$direccion_cli.'
          </td>
          </tr>
          <tr>
          <td style="border-top: 1px solid #000000;">
          <B>C.C/NIT:</B> '.$nif_cif.'
          </td>
          <td style="border-top: 1px solid #000000;">
          <b>Telefono: </b> '.$telefono_cli.'
          </td>
          </tr>
          </table>
          ';

          $html .= '
          <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; font-size: 9px">
          <tr>
          <th  style="border: inset 1px #000000; " align="left">Ref</th>
          <th  style="border: inset 1px #000000; " align="left">Descripción</th>
          <th  style="border: inset 1px #000000; " align="left">Cantidad</th>
          <th  style="border: inset 1px #000000; " align="left">Precio</th>
          <th  style="border: inset 1px #000000; " align="left">Desc</th>
          <th  style="border: inset 1px #000000; " align="left">Total</th>
          </tr>
          ';

          $total = 0;

          $timp  = 0;

          $subtotal = 0;

          $total_items = 0;

          $group_by_impuesto = array();
          $counter=NULL;
          $hasta=NULL;
          foreach($data["detalle_venta"] as $p){
          $counter++;
          if($data["tipo_factura"]=='clasico'){

          $pv = $p['precio_venta'];
          $desc = $p['descuento'];
          $pvd = $pv - $desc;
          $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
          $total_column = $pvd * $p['unidades'];
          $total_items += $total_column;
          $valor_total = $pvd * $p['unidades'] + $imp ;
          $total += $total + $valor_total;
          $timp+=$imp;
          }else{

          $pv = $p['precio_venta'];
          $desc = $p['descuento'];
          $pvd = $pv - $desc;
          $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
          $total_column = $pvd * $p['unidades'];
          $total_items += $total_column;
          $valor_total = $pvd * $p['unidades'] + $imp ;
          $total += $total + $valor_total;
          $timp+=$imp;
          }

          $precio_venta_final = number_format($p['precio_venta']);
          $precio_column_final = number_format($total_column);
          $html .= '
          <tr>
          <td  style="font-size: 9px" align="left"> '.$p["codigo_producto"] .'</td>
          <td  style="font-size: 9px" align="left"> '.$p["nombre_producto"].' </td>
          <td  style="font-size: 9px" align="right"> '.$p["unidades"].'</td>
          <td  style="font-size: 9px" align="right">$  '.$precio_venta_final.'</td>
          <td style="font-size: 9px"  align="right"> '. $p["descuento"].'</td>
          <td style="font-size: 9px"  align="right">$ '. $precio_column_final.'</td>
          </tr>
          ';
          }
          $hasta=10-$counter;
          for($i=1;$i<=$hasta;$i++){
          $html .= '
          <tr>
          <td  style="font-size: 10px" align="left">   </td>
          <td  style="font-size: 10px" align="left">   </td>
          <td  style="font-size: 10px" align="left">  </td>
          <td  style="font-size: 10px" align="right">   </td>
          <td  style="font-size: 10px" align="right">  </td>
          </tr>
          ';
          }
          $html .= '
          </table>
          ';




          $html .= '
          <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; border-bottom: 1px solid #000000;">
          <tr>
          <td style="border-right: inset 1px #000000; width: 20%; font-size: 8px;" align="center"  ><br><br><br><br><br>______________________<br><B>FIRMA DEL CLIENTE</B></td>
          <td style="border-right: solid 1px #000000; font-size: 8px; width: 260px" align="left">'.strip_tags($terminos_condiciones).'</td>
          <td style="border-right: solid 1px #000000; font-size: 9px; width: 90px; " align="left">
          <b>Valor items:</b><br>
          <b>IVA:</b><br>
          ';

          foreach ($data["detalle_pago_multiples"] as $p) {

          if($p->forma_pago!='efectivo'){  $formpago=str_replace("_"," ",$p->forma_pago);

          $html .= '   <b>'.ucfirst($formpago).':  </b> <br> ';
          }
          if($p->forma_pago=='efectivo'){
          $html .= '    <b>Efectivo: </b> <br> ';
          $html .= '   <b>Cambio: </b>  <br> ';
          }

          }

          $html .= ' <b>Total a Pagar: </b>
          </td> ';

          $html .= '
          <td style="border-right: solid 1px #000000; font-size: 9px; width: 97px; " align="right">
          $ '.number_format($total_items).'<br>
          $ '.number_format($timp).'<br>
          ';

          foreach ($data["detalle_pago_multiples"] as $p) {


          $formpago=str_replace("_"," ",$p->forma_pago);
          if($p->forma_pago!='efectivo'){
          $html .= ' $ '.number_format($p->valor_entregado).'<br>';
          }
          if($p->forma_pago=='efectivo'){
          $html .= ' $  '.number_format($p->valor_entregado).'<br>';
          $html .= ' $  '.number_format($p->cambio).'<br>';
          }

          }

          $html .= ' $ '.number_format($total_items + $timp).'

          </td>
          </tr>
          </table>
          ';


          $pdf->writeHTML($html, true, false, true, false, '');

          $pdf_name = "Factura-".$data['venta']['id_venta']."-".$data['venta']['factura'].".pdf";

          $pdf_name = 'Factura_'.$numero.'.pdf';

          $pdf->Output("uploads/$pdf_name", 'F');



          $this->email->attach("uploads/$pdf_name");
         */


        //  unlink("uploads/$pdf_name");    

        $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha enviado la factura correctamente"));

        redirect("ventas/index");
    }

    public function pagos_servicio($id) {

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
                'venta' => $venta
                , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
                , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
                , 'data_empresa' => $data_empresa
            );

            $data['tipo'] = $this->pagos->get_tipos_pago();


            $data["total"] = $this->pagos->get_total($id);

            $data["data"] = $this->pagos->get_all($id, 0);

            $numero = $this->ventas->get_by_id($id);

            $data['numero'] = $numero["factura"];

            $data["id_factura"] = $id;

            $this->layout->template('member')->show('pagos/ver_pago', array('data' => $data));
        }
    }

    public function modificar_propina() {
        $ventas = $this->ventas->modificar_propina();
    }

}

?>
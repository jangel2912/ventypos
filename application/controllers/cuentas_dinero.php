<?php

class Cuentas_dinero extends CI_Controller {

    var $dbConnection;

    function __construct() {

            parent::__construct();

            

            $usuario = $this->session->userdata('usuario');

            $clave = $this->session->userdata('clave');

            $servidor = $this->session->userdata('servidor');

            $base_dato = $this->session->userdata('base_dato');



            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

            $this->dbConnection = $this->load->database($dns, true);           

            $this->load->model("Cuentas_dinero_model",'cuentas_dinero');

            $this->cuentas_dinero->initialize($this->dbConnection);
			

            $this->load->model("miempresa_model",'miempresa');

            $this->miempresa->initialize($this->dbConnection);


            $this->load->model("vendedores_model",'vendedores');

            $this->vendedores->initialize($this->dbConnection);


            $this->load->model("pagos_model",'pagos');

            $this->pagos->initialize($this->dbConnection);

            

            /*$this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);*/


            $this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);

            $this->load->model("productos_model",'productos');

            $this->productos->initialize($this->dbConnection);


            $this->load->model("categorias_model",'categorias');

            $this->categorias->initialize($this->dbConnection);


            $this->load->model("impuestos_model",'impuestos');

            $this->impuestos->initialize($this->dbConnection);

		        $this->load->model("pais_provincia_model",'pais_provincia'); 

            $this->load->model("facturas_model",'facturas');

            $this->facturas->initialize($this->dbConnection);
        
           $this->load->model("almacenes_model",'almacen'); 
		   
            $this->almacen->initialize($this->dbConnection);	            			


            $this->load->library('pagination');

            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

            $idioma = $this->session->userdata('idioma');

            $this->lang->load('sima', $idioma);

        }      

 
	public function index($offset = 0)

	{	

       if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

               

                $this->layout->template('member')->show('cuentas_dinero/index');

		

	} 

	public function nuevo(){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(
					
                        'nombre' => $this->input->post('nombre')

                        ,'tipo_cuenta' => $this->input->post('tipo_cuenta')

                        ,'numero' => $this->input->post('numero')

                        ,'banco' => $this->input->post('banco')

                        ,'tipo_bancaria' => $this->input->post('tipo_bancaria')

                        ,'id_almacen' => $this->input->post('almacen')


                    );

                    $this->cuentas_dinero->add($data);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Cuenta de dinero se ha creado correctamente'));

                    redirect('cuentas_dinero/index');

            }
                   
	            $data['almacen'] = $this->almacen->get_all('0');
                $this->layout->template('member')->show('cuentas_dinero/nuevo', array('data1' => $data));

	}
	
	public function editar($id){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(

                        'nombre' => $this->input->post('nombre')

                        ,'tipo_cuenta' => $this->input->post('tipo_cuenta')

                        ,'numero' => $this->input->post('numero')

                        ,'banco' => $this->input->post('banco')

                        ,'tipo_bancaria' => $this->input->post('tipo_bancaria')

                        ,'id_almacen' => $this->input->post('almacen')

                    );

                    $this->cuentas_dinero->editar($data, $id);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'se ha editado correctamente'));

                    redirect('cuentas_dinero/index');

            }
                    $data = array();  
	            $data1['almacen'] = $this->almacen->get_all('0');
				$data['data']  = $this->cuentas_dinero->get_by_id($id);
                $this->layout->template('member')->show('cuentas_dinero/editar', array('data1' => $data1, 'data' => $data));

	}
            
	public function get_ajax_data(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->cuentas_dinero->get_ajax_data()));

    }

	public function apertura($id=NULL){
    
      /*var_dump($this->db->get('venta'));  */

       $this->load->model("miempresa_model",'mi_empresa');

       $this->mi_empresa->initialize($this->dbConnection);

       $data_empresa = $this->mi_empresa->get_data_empresa();
	   
	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');
		 $db_config_id = $this->session->userdata('db_config_id');
		 
                $user = $this->db->query("SELECT id FROM users where username = '".$username."' and db_config_id = '".$db_config_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
				   
                 }	
				
			$user = $this->dbConnection->query("SELECT id_Caja FROM usuario_almacen where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_Caja = $dat->id_Caja;
				   
                 }	

		 //agregar producto
		 if(isset($_POST['fecha'])){
		 
	      $data = array(

          'fecha' => $_POST['fecha'],
		  
		  'hora_apertura' =>  date('H:i:s'),
		  
		  'hora_cierre' =>  '',
		  
		  'id_Usuario' => $id_user,
		  
          'id_Caja' =>  $id_Caja,

          'id_Almacen' => $_POST['almacen'],
		  
		  'total_egresos' => '',
		  
		  'total_ingresos' => '',
		 
		  'total_cierre' => ''

          );
		  
	  $id = $this->Caja->apertura_cierre_caja($data);
	  

        if($this->input->post('foma_pago')){
		   for($contx=0;$contx<count($this->input->post('foma_pago'));$contx++){
		   
		 $array_datos = array(

			"Id_cierre"  => $id,
              "hora_movimiento"  => date('H:i:s'),
             "id_usuario"  => $id_user,			  
             "tipo_movimiento"  => 'entrada',			  
             "valor"  => $this->input->post('valor')[$contx],			  
             "forma_pago"  => $this->input->post('foma_pago')[$contx]	
			 		  
		);
		     $this->Caja->movimiento_cierre_caja($array_datos);
			 
		   }
		}
    		   
		   $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha iniciado correctamente la apertura"));

           redirect("ventas/nuevo/");
		} 
		 $data = array();
		 
	$data['almacen'] = $this->almacen->get_all('0');
	$data['forma_pago'] = $this->db->query("SELECT mostrar_opcion FROM opciones  where nombre_opcion = 'tipo_pago' order by id_opcion asc")->result();
	
        $this->layout->template('ventas')
		->css(array(base_url("/public/css/stylesheets.css"),base_url('public/css/multiselect/multiselect.css')))
		->show('caja/apertura', array('data1' => $data, 'data' => $data, 'id' => $id));
    
  }




}



?>
<?php

class Secciones_almacen extends CI_Controller {

	var $dbConnection;

	function __construct() {
		
		parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);


		$this->load->model("miempresa_model", 'mi_empresa');
		$this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("secciones_almacen_model", 'secciones_almacen');

        $this->secciones_almacen->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

	}


	public function index(){

		$this->secciones_almacen->check_existe_tabla_secciones();

		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('secciones/index',array("data" => $data));
	}

	public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->secciones_almacen->get_ajax_data()));
    }

    public function nuevo(){
    	if (!$this->ion_auth->logged_in()) {redirect('auth', 'refresh');}

		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

    	$data['almacenes'] = $this->almacenes->get_almacenes_activos();

    	if ($this->form_validation->run('secciones_almacen') == true)
    	{
    		$data = array(
    				'fecha_creacion' => date("Y-m-d h:i:s"),
    				'creado_por'     => $this->ion_auth->get_user_id(),
    				'id_almacen'     => $this->input->post('almacen'),
    				'codigo_seccion' => $this->input->post('codigo'),
    				'nombre_seccion' => $this->input->post('nombre'),
    				'descripcion_seccion'    => $this->input->post('descripcion'),
    			);

    		$this->secciones_almacen->agregar_seccion($data);
    		if($this->input->is_ajax_request()){
    			echo json_encode(array('status'=>true,'errors'=>'La nueva seccion se creo correctamente'));	
    		}else{
    			$this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'Seccion creada correctamente'));
           		redirect('mesas_secciones/index');
    		}   		
      		
    	}
    	else
    	{
 			if($this->input->is_ajax_request()){
		 		$errors = array();
		        // Loop through $_POST and get the keys
		        foreach ($this->input->post() as $key => $value)
		        {
		            
		            $errors[$key] = form_error($key);
		        }
		        $response['errors'] = array_filter($errors); // Some might be empty
		        $response['status'] = FALSE;
		        echo json_encode($response);

 			}else{
 				$this->layout->template('member')->show('secciones/nuevo',array('data'=>$data));    			
 			}
    		
    	}       
    }


    public function editar($id){
    	if (!$this->ion_auth->logged_in()) {
    		redirect('auth', 'refresh');
    	}

		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
		
    	$data['almacenes'] = $this->almacenes->get_almacenes_activos();
    	$seccion = $this->secciones_almacen->get_una_seccion_by(array('id'=>$id)); 
    	

    	if ($this->form_validation->run('secciones_almacen') == true)
    	{
    		$data = array(
    				'fecha_modificacion' => date("Y-m-d h:i:s"),
    				'modificado_por'     => $this->ion_auth->get_user_id(),
    				'id_almacen'     => $this->input->post('almacen'),
    				'codigo_seccion' => $this->input->post('codigo'),
    				'nombre_seccion' => $this->input->post('nombre'),
    				'descripcion_seccion'    => $this->input->post('descripcion'),
    			);

    		$this->secciones_almacen->actualizar_seccion($data,array('id'=>$id));
    		if($this->input->is_ajax_request()){
    			echo json_encode(array('status'=>true,'errors'=>'La seccion se actualizo correctamente'));	
    		}else{
    			$this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'La seccion se actualizo correctamente'));
           		redirect('secciones_almacen/index');
    		}   		
      		
    	}
    	else
    	{
 			if($this->input->is_ajax_request()){
		 		$errors = array();
		        // Loop through $_POST and get the keys
		        foreach ($this->input->post() as $key => $value)
		        {
		            
		            $errors[$key] = form_error($key);
		        }

		        $response['errors'] = array_filter($errors); // Some might be empty
		        $response['status'] = FALSE;
		        echo json_encode($response);

 			}else{
 				$this->layout->template('member')->show('secciones/editar',array('data'=>$data,'datos_seccion'=>$seccion));    			
 			}
    		
    	}


    }

    public function eliminar($id){
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $mensaje = $this->secciones_almacen->delete_seccion(array('id'=> $id));
        
        $this->session->set_flashdata('message', custom_lang('sima_secciones_deleted_message', $mensaje));
        
        redirect("secciones_almacen/index");
    }

} 

?>
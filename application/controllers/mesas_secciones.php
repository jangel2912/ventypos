<?php

class Mesas_secciones extends CI_Controller {

	var $dbConnection;

	function __construct() {
		
		parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("secciones_almacen_model", 'secciones_almacen');

		$this->secciones_almacen->initialize($this->dbConnection);
		
		$this->load->model("miempresa_model", 'mi_empresa');
		$this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("mesas_secciones_model", 'mesas_secciones');

        $this->mesas_secciones->initialize($this->dbConnection);

	}


	public function index(){

		$this->secciones_almacen->check_existe_tabla_secciones();
		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
		if($this->session->userdata('is_admin') == "t"){
        	$this->layout->template('member')->show('mesas/index',array("data" => $data));
       }else{
          redirect(site_url('frontend/index'));
       }
	}

	public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->mesas_secciones->get_ajax_data()));
    }

    public function nuevo(){
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
    	$data['secciones'] = $this->secciones_almacen->get_secciones_almacen(array('a.activo'=>1,'a.id !=' => -1));
		$this->form_validation->set_rules('codigo', 'codigo', 'numeric');

    	if ($this->form_validation->run('mesas_secciones') == true)
    	{
    		$data = array(
    				'fecha_creacion' => date("Y-m-d h:i:s"),
    				'creado_por'     => $this->ion_auth->get_user_id(),
    				'id_seccion'     => $this->input->post('seccion'),
    				'codigo_mesa' => $this->input->post('codigo'),
    				'nombre_mesa' => $this->input->post('nombre'),
    			);

			$result = $this->mesas_secciones->agregar_mesa($data);
			if($result == null){
				echo json_encode(array('status'=>true,'errors'=>'El codigo de la mesa ya se encuentra registrado'));
			}else{
				echo json_encode(array('status'=>true,'errors'=>'Mesa creada correctamente'));
				//$this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'Mesa creada correctamente'));
           		//redirect('mesas_secciones/index');
			}
    		/*if($this->input->is_ajax_request()){
    			echo json_encode(array('status'=>true,'errors'=>'La nueva mesa se creo correctamente'));	
    		}else{
    			$this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'Mesa creada correctamente'));
           		redirect('mesas_secciones/index');
    		}   	*/	
      		
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
 				$this->layout->template('member')->show('mesas/nuevo',array('data'=>$data));    			
 			}
    		
    	}       
    }


    public function editar($id){
    	if (!$this->ion_auth->logged_in()) {
    		redirect('auth', 'refresh');
    	}

		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
		
    	$data['secciones'] = $this->secciones_almacen->get_secciones_almacen(array('a.activo'=>1,'a.id !=' => -1));
    	$mesa = $this->mesas_secciones->get_una_mesa_by(array('id'=>$id)); 
    	

    	if ($this->form_validation->run('mesas_secciones') == true)
    	{
    		$data = array(
    				'fecha_modificacion' => date("Y-m-d h:i:s"),
    				'modificado_por'     => $this->ion_auth->get_user_id(),
    				'id_seccion'     => $this->input->post('seccion'),
                    'codigo_mesa'    => $this->input->post('codigo'),
                    'nombre_mesa'    => $this->input->post('nombre'),
    			);

    		$this->mesas_secciones->actualizar_mesa($data,array('id'=>$id));
    		if($this->input->is_ajax_request()){
    			echo json_encode(array('status'=>true,'errors'=>'La mesa se actualizo correctamente'));	
    		}else{
    			$this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'La mesa se actualizo correctamente'));
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
 				$this->layout->template('member')->show('mesas/editar',array('data'=>$data,'datos_mesa'=>$mesa));    			
 			}
    		
    	}


    }

    public function eliminar($id){
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $mensaje = $this->mesas_secciones->delete_mesa(array('id'=> $id));
        
        $this->session->set_flashdata('message', custom_lang('sima_mesas_deleted_message', $mensaje));
        
        redirect("mesas_secciones/index");
    }

    public function desactivar($id){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $mensaje = $this->mesas_secciones->update(array('activo'=>0),array('id'=> $id));
        
        $this->session->set_flashdata('message', custom_lang('sima_mesas_desactive_message', $mensaje));
        
        redirect("mesas_secciones/index");   
    }

} 

?>
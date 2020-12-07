<?php

class Puntos extends CI_Controller 

{

    var $dbConnection;

    function __construct()

	{

		parent::__construct();

                 

                $usuario = $this->session->userdata('usuario');

                $clave = $this->session->userdata('clave');

                $servidor = $this->session->userdata('servidor');

                $base_dato = $this->session->userdata('base_dato');

                

                $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

                $this->dbConnection = $this->load->database($dns, true);

		

                $this->load->model("clientes_model",'clientes');

                $this->clientes->initialize($this->dbConnection);
				
                $this->load->model("Puntos_model",'puntos');

                $this->puntos->initialize($this->dbConnection);

                $this->load->model("lista_precios_model",'lista_precios');

                $this->lista_precios->initialize($this->dbConnection);

                $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');

                $this->lista_detalle_precios->initialize($this->dbConnection);


		        $this->load->model("pais_provincia_model",'pais_provincia'); 

                
                $this->load->model("miempresa_model", 'mi_empresa');
                $this->mi_empresa->initialize($this->dbConnection);

                $this->load->model('crm_model');

                $this->load->library('pagination');

                $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

				

                $idioma = $this->session->userdata('idioma');

                $this->lang->load('sima', $idioma);

	}



    public function index($offset = 0){
            $data = array();
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
            $data["count_plan"] = $this->puntos->get_count_plan();
            $this->layout->template('member')->show('puntos/index',array('data' => $data));
   }

    public function clientes_plan_puntos($offset = 0){
            $data = array();
              $data['plan_puntos'] = $this->puntos->plan_puntos();
              $data_empresa = $this->mi_empresa->get_data_empresa();
              $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
             $this->layout->template('member')->show('puntos/cliente_plan_punto',array('data' => $data));
   }

    public function get_ajax_plan_puntos(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->puntos->get_ajax_plan_puntos()));

    }

    public function get_ajax_cliente_plan_puntos(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->puntos->get_ajax_cliente_plan_puntos()));

    }

    public function puntos_acumulados_cliente($id){
         $this->puntos->puntos_acumulados_cliente($id); 
    }
			
    public function get_datos_punto_plan(){
	   $id = 0;
	   $id = $_POST['id'];
	
	   $data = $this->puntos->edit_plan($id);
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	
	
    public function get_datos_clientes_punto_plan(){
	   $id = 0;
	   $id = $_POST['id'];
	
	   $data = $this->puntos->edit_clientes_plan($id);
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	
	
    public function get_datos_punto_valor(){
	   $data = $this->puntos->valor_punto();
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	

    public function get_datos_porcompras_valor(){
	   $data = $this->puntos->porcompras_valor();
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	
	
    public function get_datos_punto_redimir($cli){
	   $data = $this->puntos->valor_punto_redimir($cli);
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	

    public function get_por_compras_puntos($total){
	   $data = $this->puntos->por_compras_puntos($total);
	   $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }	
	
		
	public function editar(){
        $this->puntos->update();	
        $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'Plan de puntos actualizado correctamente'));
        redirect("puntos/index");
	}


	public function nuevo(){
        $this->puntos->add();	
        $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'Plan de puntos ha sido ingresado correctamente'));
        redirect("puntos/index");
	}	


	public function cliente_plan_nuevo(){
        $this->form_validation->set_rules('id_cliente', 'Cliente', 'required|integer');
        $this->form_validation->set_rules('plan_puntos', 'Plan de puntos', 'required|integer');
        if($this->form_validation->run() == true){
            $respuesta = $this->puntos->cliente_plan_nuevo();    
            if($respuesta){
                $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'se ha sido ingresado correctamente'));

                redirect("puntos/clientes_plan_puntos");
            }else{
                $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'El cliente ya esta asignado en el plan de puntos'));

                redirect("puntos/clientes_plan_puntos");
            }    
        }else{
            $error = validation_errors();
            $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', $error));
            redirect("puntos/clientes_plan_puntos");
        }

	}

	public function editar_cliente_plan_nuevo(){
        $this->puntos->update_cliente_plan_nuevo();	
        $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'Se ha actualizado correctamente'));
        redirect("puntos/clientes_plan_puntos");
	}

	
	public function actualizar_punto(){
        $this->puntos->actualizar_punto();	
        $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'El valor del punto ha sido actualizado correctamente'));
        redirect("puntos/index");
	}	
	
	public function actualizar_porcompras(){
        $this->puntos->actualizar_porcompras();	
        $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'Ha sido actualizado correctamente'));
        redirect("puntos/index");
	}	
	
	public function eliminar($id){	
		$delet = $this->puntos->delete($id);
		  $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Este Plan de puntos se ha eliminado correctamente'));
		 redirect("puntos/index");

	}

	public function eliminar_cliente_plan($id){	
		$delet = $this->puntos->eliminar_cliente_plan($id);
		  $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));
		 redirect("puntos/clientes_plan_puntos");

	}

    public function consultar_puntos_clientes(){
        $result = $this->puntos->clientes_con_puntos();
        echo json_encode($result);
    }

    public function get_count_plan(){
        $result = $this->puntos->get_count_plan();
        echo json_encode(array('total' => $result));
    }


    public function crear(){
        $this->layout->template('member')->show('puntos/nuevo');
    }

    public function actualizar($id){
        $data = $this->puntos->edit_plan($id);
        $this->layout->template('member')->show('puntos/editar',$data);
    }



}
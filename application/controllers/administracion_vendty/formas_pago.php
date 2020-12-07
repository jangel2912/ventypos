<?php 

/**
* 
*/
class Formas_pago extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		//$this->load->library('grocery_CRUD'); 
		$this->load->model('crm_model');
		   
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }
	}

	public function index(){
		
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }       
       
        if ($this->ion_auth->in_group(5)) { 
						
			$data['formaspago'] = $this->crm_model->get_formas_pago(array('nombre_forma !='=>''));
			    			     
            $this->layout->template('administracion_vendty')->show('administracion_licencia/formaspagos/index',array('data' => $data));

		}else{
            redirect("frontend/index");
        }   		
	}

	public function nuevo(){
       
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) { 
		   
			
            $nombreforma=$this->input->post('nombre_forma');
            if (!empty($nombreforma)) {              
                $dato = $this->crm_model->get_formas_pago(array('nombre_forma'=>$nombreforma));               
                if(count($dato)==0){

                    if ($this->form_validation->run('formas_pagos') == true) {
                       
                        $data = array(
                            'nombre_forma' =>  $this->input->post('nombre_forma')
                            ,'descripcion' => $this->input->post('descripcion')
                            ,'numero_cuenta' => $this->input->post('numero_cuenta')                            
                            ,'nombre_cuenta' =>  $this->input->post('nombre_cuenta') 
                            ,'activo_forma' =>  $this->input->post('activo_forma')                                   

                        );
                    
                        $this->crm_empresas_clientes_model->add($data);
                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Empresa creada correctamente'));
                        redirect('administracion_vendty/empresas/');
                    }
                }
                else{
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'El nombre de la empresa ya existe'));
                    redirect('administracion_vendty/empresas/');
                } 
            }

            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/nuevo',array('data' => $data));
        }else{
            redirect("frontend/index");
        }
    }

	public function _callback_column_activo($value,$row){
    	if($value == 0){
    		return 'Deshabilitado';
    	}else{
    		return 'Habilitado';
    	}
    }
}

?>
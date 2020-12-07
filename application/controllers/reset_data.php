<?php

class Reset_data extends CI_Controller {
        var $dbConnection;
        
	function __construct() {
            parent::__construct();
            
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            
            $this->load->model("atributos_model",'atributos');
            $this->atributos->initialize($this->dbConnection);
            
            $idioma = $this->session->userdata('idioma');
            $this->lang->load('sima', $idioma);
        }
        
        public function index($offset = 0)
	{	
                if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
               
                $this->layout->template('member')->show('atributos/index');
		
	}
        
         
	public function get_ajax_data(){
            $this->output->set_content_type('application/json')->set_output(json_encode($this->atributos->get_ajax_data()));
        }
        
    public function nuevo(){	 
                
        $error_upload = "";

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
		}

		if ($_POST) {
         
            $data = array(
                'nombre' => $this->input->post('nombre')
            );

            $this->atributos->add($data);
            
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo creado correctamente'));
            redirect('atributos/index');
        }
                $this->layout->template('member')->show('atributos/nuevo');
	}
        
        public function editar($id){
             $error_upload = "";
             if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                
		if ($this->form_validation->run('atributos') == true)
                {            
                        $data = array(
                            'id' => $this->input->post('id')
                            ,'nombre' => $this->input->post('nombre')
                        );
                        $this->atributos->update($data);
                        
                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo actualizado correctamente'));
                        redirect('atributos/index');
                        
                }
                    
                $data = array();   
		$data['data']  = $this->atributos->get_by_id($id);
                $this->layout->template('member')->show('atributos/editar', array('data' => $data));
	}
        
        public function eliminar($id)
	{	
             if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		$this->atributos->delete($id);
		$this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'Se ha eliminado correctamente'));
		redirect("atributos/index");
	}
}

?>



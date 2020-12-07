<?php

class Domiciliarios extends CI_Controller 

{

        var $dbConnection;

        

	function __construct() {

            parent::__construct();

            

            $usuario = $this->session->userdata('usuario');

            $clave = $this->session->userdata('clave');

            $servidor = $this->session->userdata('servidor');

            $base_dato = $this->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            
            $this->load->model("ventas_model", 'ventas');
            $this->ventas->initialize($this->dbConnection);
            $this->load->model("miempresa_model", 'mi_empresa');
            $this->mi_empresa->initialize($this->dbConnection);
            $this->load->model("domiciliarios_model", 'domiciliarios');
            $this->domiciliarios->initialize($this->dbConnection);

            //domicilios
            $this->mi_empresa->crearOpcion('domicilios','no');
            //crear tabla de domiciliarios
            $this->domiciliarios->crear_domiciliarios();
             
            
        }

        

	public function index(){	

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data['domiciliarios']= $this->domiciliarios->get_domiciliarios();
        $action = "domiciliarios/index";            
        $this->layout->template('member')->show($action,array('data' => $data));
    }

    public function nuevo(){	

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data["tipo_domiciliario"] = $this->domiciliarios->get_tipo_domiciliario();
        
        if($this->form_validation->run("domiciliario") == true){                

            $data = array(                
                'tipo' => $this->input->post('tipo'),
                'descripcion' => $this->input->post('nombre'),
                'telefono' => $this->input->post('telefono'),
                'direccion' => $this->input->post('direccion'),
            );

            $id=$this->domiciliarios->add($data);
            if(!empty($id)){
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Domiciliario creado exitosamente'));
            }else{
                $this->session->set_flashdata('message1', custom_lang('sima_category_created_message', 'Domiciliario no pudo ser creado, intente más tarde'));
            }
            redirect('domiciliarios/index');
            
        }
        $this->layout->template('member')->show("domiciliarios/nuevo",array('data' => $data));
    }

    public function editar($id){
        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data["tipo_domiciliario"] = $this->domiciliarios->get_tipo_domiciliario();

        if(!empty($id)){
            //busco el domiciliario
            $data['domiciliario']=$this->domiciliarios->get_domiciliario(array('id'=>$id));            
        }

        if($this->form_validation->run("domiciliario") == true){                
            $where=array(
                'id'=>$id
            );
            $data = array(                
                'tipo' => $this->input->post('tipo'),
                'descripcion' => $this->input->post('nombre'),
                'telefono' => $this->input->post('telefono'),
                'direccion' => $this->input->post('direccion'),
            );
            
            $id=$this->domiciliarios->update($data,$where);
            if(!empty($id)){
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Domiciliario Actualizado Exitosamente'));
            }else{
                $this->session->set_flashdata('message1', custom_lang('sima_category_created_message', 'Domiciliario no pudo ser actualizado, intente más tarde'));
            }
            redirect('domiciliarios/index');            
        }

        $this->layout->template('member')->show("domiciliarios/editar",array('data' => $data));
    }

    public function desactivar(){
        $id=$this->input->post('id');
        $activo=$this->input->post('activo');
             
        if(!empty($id)){
            //desactivar el domiciliario
            $this->domiciliarios->desactivar(array('id'=>$id),array('activo'=>$activo));
        }
    }
}

?>
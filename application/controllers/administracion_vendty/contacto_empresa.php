<?php
class Contacto_empresa extends CI_controller {
    
        function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD'); 
       
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }
	}

    public function index(){
        $crud = new grocery_CRUD();
         if($this->ion_auth->in_group(3)){
            $datos_distribuidor = $this->db->select('*')->where('users_id',$this->ion_auth->get_user_id())->get('crm_usuarios_distribuidores')->row();
            $where = array('j178887a9.id_distribuidores_licencia'=>$datos_distribuidor->id_distribuidores_licencia);
        }elseif ($this->ion_auth->in_group(5)) {
            $where = array();
        }else{
            $where = array('j178887a9.id_user_distribuidor'=>$this->ion_auth->get_user_id());
        } 

        if(!empty($where)){
            $crud->where($where);
        } 
               
        $crud->set_subject('Contacto');
		$crud->set_table('crm_contactos_empresa');
        $crud->where($where);
        $crud->set_relation('idempresas_clientes','crm_empresas_clientes','nombre_empresa');
        $crud->field_type('tipo_contacto','enum',array('creacion_cuenta','gerente_cuenta','envio_factura','contador','otro'));
        $crud->display_as('idempresas_clientes','Empresa');
        $crud->set_rules('email_contacto','required|valid_email');
        $crud->required_fields('email_contacto','tipo_contacto','nombre_contacto','telefono_contacto','email_contacto','idempresas_clientes');
		$output = $crud->render();
		$this->layout->template('administracion_vendty')->show('administracion_licencia/gc_example',array('gc' => $output));
    
    }
}

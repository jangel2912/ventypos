<?php
/**
 * 
 */
class Empresas extends CI_controller {
    var $user_id;
    var $id_db_config;
   
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
       
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        
        if($this->ion_auth->in_group(3)){            
            $datos_distribuidor = $this->db->select('*')->where('users_id',$this->ion_auth->get_user_id())->get('crm_usuarios_distribuidores')->row();
            $where = array('crm_empresas_clientes.id_distribuidores_licencia'=>$datos_distribuidor->id_distribuidores_licencia);
        }elseif ($this->ion_auth->in_group(5)) {
            $where = array();
        }else{
            $where = array('id_user_distribuidor'=>$this->ion_auth->get_user_id());
        }

               

        $crud = new grocery_CRUD();
        
        if(!empty($where)){
            $crud->where($where);
        } 
            
        $crud->set_subject('Empresa');
    	$crud->set_table('crm_empresas_clientes');   
         
        
        $crud->field_type('tipo_contacto','enum',array('creacion_cuenta','gerente_cuenta','envio_factura','contador','otro'));  
        
        //$crud->set_relation('id_db_config','db_config','base_dato');
        $crud->field_type('id_db_config','invisible');
        $crud->display_as('id_db_config','Base de datos');
        $crud->unset_delete();
        
        $crud->callback_before_update(array($this,'definir_db_config_callback'));
        $crud->required_fields('nombre_empresa','direccion_empresa','telefono_contacto','idusuario_creacion','tipo_identificacion','identificacion_empresa','ciudad_empresa','departamento_empresa','pais');
            
        $crud->callback_column('id_db_config',array($this,'_callback_column_db_config'));
        $crud->display_as('idusuario_creacion','Email usuario creacion');    
        if($this->ion_auth->in_group(5)){
            $crud->set_relation('idusuario_creacion','users','email',array('is_admin'=>'t','active'=>1));
            $crud->callback_before_insert(array($this,'definir_db_config_callback'));
            $crud->set_relation('id_user_distribuidor','users','email',array('is_admin'=>'t','active'=>1,'db_config_id'=>1));
            $crud->display_as('id_user_distribuidor','usuario distribuidor');
            $crud->set_relation('id_distribuidores_licencia','crm_distribuidores_licencia','nombre_distribuidor');
            $crud->display_as('id_distribuidores_licencia','Distribuidor');   
            $crud->columns('nombre_empresa','razon_social_empresa','direccion_empresa','telefono_contacto','id_distribuidores_licencia','db_config_id','id_user_distribuidor','tipo_identificacion','identificacion_empresa','ciudad_empresa','departamento_empresa','pais');
            $crud->fields('nombre_empresa','razon_social_empresa','direccion_empresa','telefono_contacto','idusuario_creacion','id_db_config','id_distribuidores_licencia','id_user_distribuidor','tipo_identificacion','identificacion_empresa','ciudad_empresa','departamento_empresa','pais');
        }else{
            $crud->callback_before_insert(array($this,'crear_db_cliente_callback'));
            $crud->callback_after_insert(array($this, 'crear_contacto_empresa'));
            $crud->columns('nombre_empresa','razon_social_empresa','idusuario_creacion','direccion_empresa','telefono_contacto','tipo_identificacion','identificacion_empresa','ciudad_empresa','departamento_empresa','pais','valor_renovacion');
            $crud->callback_field('idusuario_creacion',array($this,'campo_correo_callback'));
            $crud->field_type('id_user_distribuidor','hidden',$this->ion_auth->get_user_id());
            $crud->field_type('id_user_distribuidor','hidden',$this->ion_auth->get_user_id());
            $crud->field_type('id_distribuidores_licencia','hidden',$datos_distribuidor->id_distribuidores_licencia);
            $crud->fields('nombre_empresa','razon_social_empresa','direccion_empresa','telefono_contacto','id_distribuidores_licencia','id_user_distribuidor','tipo_identificacion','identificacion_empresa','ciudad_empresa','departamento_empresa','pais','valor_renovacion','id_db_config');
            //$crud->set_rules('idusuario_creacion','Email usuario creacion','required|valid_email|is_unique[users.email]');
        }
        $crud->set_relation('idusuario_creacion','users','email');

        
    	$output = $crud->render();
    	$this->layout->template('administracion_vendty')->show('administracion_licencia/gc_example',array('gc' => $output));

    }
    
    
    public function crear_contacto_empresa($post_array,$primary_key){
        $user = $this->db->where(array('id'=>$post_array['idusuario_creacion']))->get('users')->row();
        $data_contacto = array(
                'tipo_contacto'    => 'creacion_cuenta',
                'nombre_contacto'  => $post_array['razon_social_empresa'],
                'telefono_contacto'=> $post_array['telefono_contacto'],
                'email_contacto'   => $user->email,
                'idempresas_clientes'=>$primary_key 
            );
        $this->db->insert('crm_contactos_empresa',$data_contacto);
        $data_licencia = array('idempresas_clientes'=>$primary_key,
                                'planes_id'=>1,
                                'fecha_vencimiento'=>date("Y-m-d",strtotime("+1 week")), 
                                'fecha_creacion'   =>date("Y-m-d h:i:s"),
                                'id_db_config'     => $user->db_config_id,
                                'id_almacen'       => 1,
                                'estado_licencia'   =>1,
                                'fecha_inicio_licencia' => date("Y-m-d"),
                                'creado_por'        => $this->ion_auth->get_user_id()
                            );
        return $this->db->insert('crm_licencias_empresa',$data_licencia); 

    }

    public function campo_correo_callback($value = '', $primary_key = null){
        return '<input type="text" maxlength="100" value="'.$value.'" name="idusuario_creacion" style="width:462px">';
    }

    public function crear_db_cliente_callback($post_array,$primary_key=''){
        $year = date('Y');  
        $salt = substr(md5(uniqid(rand(), true)), 0, 10);
        $password = substr($post_array['idusuario_creacion'], 0, 4) . "$year";
        $password_send = $password;
        $conf_code = $salt . substr(sha1($salt . $password), 0, -10);
        
        $username = explode('@', $post_array['idusuario_creacion']);
        
        $data = array( 
                'ip_address'     => $_SERVER['REMOTE_ADDR'],
                'username'       => $username[0],
                'password'       => $this->ion_auth->hash_password($password),
                'salt'           => $salt,
                'email'          => $post_array['idusuario_creacion'],
                'activation_code'=> $conf_code,
                'created_on'     => time(),
                'last_login'     => time(),
                'active'         => 0,
                'idioma'         => 'spanish',
                'is_admin'       => 't',
                'company'        => $post_array['nombre_empresa'],
                'phone'          => $post_array['telefono_contacto'] 
            );
        $this->db->insert('users',$data);
        $id_user = $this->db->insert_id();
        //creamos el contacto
        
        if($id_user){
            $this->ion_auth->activate($id_user, $conf_code);           
            $this->enviar_email($post_array['idusuario_creacion'],$post_array['razon_social_empresa'],$username[0],$password_send);
            $nombre_bd = $id_user . '_' . $password;
            // Creamos y configuramos las DB del usuario
            $this->load->library('PHPRequests');
            $url ='http://localhost/vendty-demo/index.php/auth/create_db_distribuidores/'.$id_user.'/'.$nombre_bd;            
            $response = Requests::get($url,array(),array('timeout'=>120.5));
            $user_actualizado = $this->db->where(array('email'=>$post_array['idusuario_creacion']))->get('users')->row();
            $post_array['id_db_config'] = $user_actualizado->db_config_id;
            $post_array['idusuario_creacion'] = $user_actualizado->id;
            return $post_array;
        }else{
            return false;
        }

    }

    public function enviar_email($email,$nombreUsuario,$username,$password_send){
         $html = $this->load->view("auth/registroEmail_vendty_demo",array(
                "email"=>$email,
                "nombre"=>$nombreUsuario,
                "username"=>$username,
                "password"=>$password_send
              ),true);
                
            $this->load->library('email');
            $this->email->initialize();
            $this->email->from('info@vendty.com', 'Vendty');
            $this->email->to($email);
            $this->email->bcc('roxanna@vendty.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $this->email->message($html);
            $this->email->send();

    }

    public function _callback_column_db_config($value,$row){
        $this->db->where(array('id'=>$value));
        $datos_bd = $this->db->get('db_config')->row();
        return $datos_bd->base_dato;
    }

    
    public function definir_db_config_callback($post_array,$primary_key=''){
        //$id_user = $post_array['idusuario_creacion'];
        $this->db->where(array('id'=>$post_array['idusuario_creacion']));
        $query = $this->db->get('users');
        $id_db_config = 0;
        foreach ($query->result() as $key => $value) {
            $id_db_config = $value->db_config_id;
        }
        $post_array['id_db_config'] = $id_db_config;
        return $post_array;
    }

}

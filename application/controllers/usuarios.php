<?php

class Usuarios extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);



        $this->load->model("roles_model", 'roles');

        $this->roles->initialize($this->dbConnection);

        $this->load->model("Caja_model", 'Caja');

        $this->Caja->initialize($this->dbConnection);
        
        
        $this->load->model("usuario_almacen_model", 'usuario_almacen');
        $this->usuario_almacen->initialize($this->dbConnection);


        $this->load->model('Usuarios_model', 'usuarios');

        $this->usuarios->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

        $this->load->model('crm_licencias_empresa_model');

        $this->load->model('crm_model');

        $this->load->model('almacenes_model', 'almacen');
        $this->almacen->initialize($this->dbConnection);
        
        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);
    }

    function index($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        if($this->session->userdata('is_admin') == "t"){
            $this->layout->template('member')->show('usuarios/index',array('data' => $data));
       }else{
          redirect(site_url('frontend/index'));
       }
    }

    public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->usuarios->get_ajax_data()));
    }

    function nuevo() {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|max_length[10]');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('rol_id', 'Rol', 'required');
        $this->form_validation->set_rules('almacen', 'Almacen', 'required');
        // verificar si se necesita caja, sino es bodega
        $alma=$this->input->post('almacen');
        
        if(!empty($alma)){
            $almab=$this->almacen->get_Bodega($alma);
            
            if($almab==0){
                $this->form_validation->set_rules('caja', 'Caja', 'required');
            }
        }
        
        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('email'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            if ($this->input->post('almacen') == '-1') {
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone1'),
                    'db_config_id' => $this->session->userdata('db_config_id'),
                    'rol_id' => $this->input->post('rol_id'),
                    'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                    'is_admin' => 'a'
                );
            }
            if ($this->input->post('almacen') != '-1') {
                if (!empty($_POST['is_admin'])) {

                    if ($_POST['is_admin'] == 't') {
                        $additional_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'company' => $this->input->post('company'),
                            'phone' => $this->input->post('phone1'),
                            'db_config_id' => $this->session->userdata('db_config_id'),
                            'rol_id' => $this->input->post('rol_id'),
                            'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                            'is_admin' => isset($_POST['is_admin']) ? 't' : 'f'
                        );
                    }

                    if ($_POST['is_admin'] == 's') {

                        $additional_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'company' => $this->input->post('company'),
                            'phone' => $this->input->post('phone1'),
                            'db_config_id' => $this->session->userdata('db_config_id'),
                            'rol_id' => $this->input->post('rol_id'),
                            'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                            'is_admin' => isset($_POST['is_admin']) ? 's' : 'f'
                        );
                        
                    }
                } else {
                    $additional_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone1'),
                        'db_config_id' => $this->session->userdata('db_config_id'),
                        'rol_id' => $this->input->post('rol_id'),
                        'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                        'is_admin' => isset($_POST['is_admin']) ? 't' : 'f'
                    );
                }
            }


            $this->load->model('ion_auth_model');

            $id = $this->ion_auth_model->register($username, $password, $email, $additional_data);
           // var_dump($id);
            $this->ion_auth_model->activate($id); //$this->ion_auth->register($username, $password, $email, $additional_data);

            if ($id != FALSE) {

                $array_datos = array(
                    "db_config_id" => $this->session->userdata('db_config_id')
                );
                
                $this->db->where('id', $id);
                $this->db->update("users", $array_datos);

                $ids = $this->dbConnection->insert('usuario_almacen', array('usuario_id' => $id, 'almacen_id' => $this->input->post('almacen'), 'id_Caja' => $this->input->post('caja')));
                $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

                redirect("frontend/configuracion", 'refresh');
            }
        } else {

            $data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['phone1'] = array(
                'name' => 'phone1',
                'id' => 'phone1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone1'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
                'placeholder' => '',
                'class' => 'form-control required'
            );

            $deficrear=$this->buscar_detalle_plan();
         
            $data['definecrear']=$deficrear;  
            $almacenes = $this->almacenes->get_combo_data(null,null);
            $roles = $this->roles->get_combo_data();
            $data['almacen'] = $this->almacenes->get_all('0',false);
            $data['caja'] = $this->Caja->get_all('0');
            $data['valor_caja'] = $data_empresa['data']['valor_caja'];           
            $this->layout->template('member')->show('usuarios/nuevo.php', array('data' => $data, 'almacen' => $almacenes, 'roles' => $roles));
        }
    }

    public function buscar_detalle_plan(){
             //conseguir las licencias activas 
        $db_config_id = $this->session->userdata('db_config_id');        
        $hoy=date('Y-m-d');        
        $planesactivos="where id_plan in (";
        $licenciatotalbd = $this->crm_licencias_empresa_model->get_all_id(array('id_db_config'=>$db_config_id,'fecha_vencimiento >='=>$hoy,'estado_licencia !='=>'15'));  

        foreach($licenciatotalbd as $idplan){
            $planesactivos.="'".$idplan->id_plan."',";
        }        
        $planesactivos=trim($planesactivos,',');        
        $planesactivos.=")";  
        $planesdetalleactivos = $this->crm_model->get_detalle_plan($planesactivos);   
        $al_campo=array();
        $deficrear=array();
        $cantbodegasplanes=0;
        
        foreach ($licenciatotalbd as $key => $value) {
            $al_campo[$value->id_almacen]['almacen']=$value->id_almacen;                
            foreach($planesdetalleactivos as $key1 => $value1 ){                  
                if($value->id_plan==$value1->id_plan){                    
                    $al_campo[$value->id_almacen][$value1->nombre_campo]= $value1->valor; 
                    if($value1->nombre_campo=='bodegas'){
                        if(!is_null($value1->valor)){   
                            $cantbodegasplanes+=$value1->valor;   
                        }else{                             
                             $cantbodegasplanes+=100000;
                        }                          
                        $al_campo[$value->id_almacen][$value1->nombre_campo]= $cantbodegasplanes;              
                    }                          
               }              
           }                
        }
      
        foreach ($al_campo as $key => $value) {
        //buscar cuantos tengo para desactivar el almacen           
            //usuario         
            $cantuser= $this->usuarios->get_users_almacen($value['almacen']);  
            if(isset($value['usuarios'])){             
                if($cantuser<$value['usuarios']) {
                    $deficrear[$key]['usuarios']="1";//1 permitir -  0 no permitir
                }else{
                    $deficrear[$key]['usuarios']="0";// 0 no permitir
                }
            }else{
                $deficrear[$key]['usuarios']="0";// 0 no permitir
            }
            //bodega
            $cantbodegacreadas= $this->almacen->cantBodega();            
            if($cantbodegacreadas<$cantbodegasplanes) {
                $deficrear[$key]['bodegas']="1";//1 permitir -  0 no permitir
            }else{
                $deficrear[$key]['bodegas']="0";// 0 no permitir
            } 
            //cajas
            $cantcajas= $this->Caja->cant_almacen_caja($value['almacen']);   
            if(isset($value['cajas'])){           
                if($cantcajas<$value['cajas']) {
                    $deficrear[$key]['cajas']="1";//1 permitir
                }
                else{
                    $deficrear[$key]['cajas']="0"; // 0 no permitir
                } 
            }else{
                $deficrear[$key]['cajas']="0"; // 0 no permitir
            }
        }     
        return $deficrear;
    }

    public function almacen_caja() {
        $options = "";
        if ($this->input->post('almacen')) {
            $id = $this->input->post('almacen');
            $caja = $this->input->post('caja');
            $cajas = $this->Caja->almacen_caja($id);
            ?>
                <option value="">Seleccione una Caja</option>
            <?php
            foreach ($cajas as $fila) {
                if ($fila->id == $caja) {
                    $selected = " selected=selected ";
                } else {
                    $selected = "";
                }
                ?>
                <option <?php echo $selected; ?> value="<?php echo $fila->id; ?>"><?php echo $fila->nombre; ?></option>
                <?php
            }
        }
    }

    public function editar($id) {
        
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

        $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|max_length[10]');

        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');

        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        //$this->form_validation->set_rules('caja', $this->lang->line('create_user_validation_caja_label'), 'required');

        $this->form_validation->set_rules('rol_id', 'Rol requerido', 'required');



        if ($this->form_validation->run() == true) {
            $desactivar_usuario = isset($_POST['desactivar_usuario']) ? 0 : 1;
            if ($this->input->post('almacen') == '-1') {

                $additional_data = array(
                    'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                    'password' => $this->input->post('password'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone1'),
                    'email' => $this->input->post('email'),
                    'caja' => $this->input->post('caja'),
                    // 'db_config_id' => $this->session->userdata('db_config_id'),
                    'rol_id' => $this->input->post('rol_id'),
                    'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                    'is_admin' => isset($_POST['is_admin']) ? $_POST['is_admin'] : 'a', 
                    'active' => $desactivar_usuario
                );
                //$is_admin = "t";
            } else {
                $is_admin = "";
                if ($_POST['is_admin'] == 't') {
                    $additional_data = array(
                        'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                        'password' => $this->input->post('password'),
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone1'),
                        'email' => $this->input->post('email'),
                        'caja' => $this->input->post('caja'),
                        // 'db_config_id' => $this->session->userdata('db_config_id'),
                        'rol_id' => $this->input->post('rol_id'),
                        'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                        'is_admin' => isset($_POST['is_admin']) ? 't' : 'f',
                        'active' => $desactivar_usuario
                    );
                    $is_admin = "t";
                }
                if ($_POST['is_admin'] == 's') {
                    $additional_data = array(
                        'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                        'password' => $this->input->post('password'),
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone1'),
                        'email' => $this->input->post('email'),
                        'caja' => $this->input->post('caja'),
                        // 'db_config_id' => $this->session->userdata('db_config_id'),
                        'rol_id' => $this->input->post('rol_id'),
                        'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                        'is_admin' => isset($_POST['is_admin']) ? 's' : 'f',
                        'active' => $desactivar_usuario
                    );
                    
                    $is_admin = "s";
                }
                if ($_POST['is_admin'] == '') {
                    $additional_data = array(
                        'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                        'password' => $this->input->post('password'),
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone1'),
                        'email' => $this->input->post('email'),
                        'caja' => $this->input->post('caja'),
                        // 'db_config_id' => $this->session->userdata('db_config_id'),
                        'rol_id' => $this->input->post('rol_id'),
                        'es_estacion_pedido' => $this->input->post('estacion_pedido'),
                        'is_admin' => isset($_POST['is_admin']) ? 's' : 'f',
                        'active' => $desactivar_usuario
                    );
                    
                    $is_admin = 'f';
                }
            }


            $this->load->model('ion_auth_model');

            $this->ion_auth_model->update($id, $additional_data);

            $data = array(
                'almacen_id' => $this->input->post('almacen'),
                'id_Caja' => $this->input->post('caja'),
                'usuario_id' => $id
            );
            
            if($this->input->post('almacen')!=-1){
                $almacenDefecto = $this->usuarios->getAlmacenDefecto($id);
            }else{
                $almacenDefecto=0;
            }
           
            //$almacenDefecto = 1;
            $this->usuario_almacen->editarUsuario($data,$is_admin,$almacenDefecto);

            $this->session->set_flashdata('message', 'El usuario ha sido editado con exito');
            redirect("frontend/configuracion");
        }

        $user = $this->ion_auth->user($id)->row();

        $data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
            'placeholder' => ''
        );

        $data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
            'placeholder' => ''
        );

        $data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email),
            'placeholder' => ''
        );

        $data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
            'placeholder' => ''
        );

        $data['phone1'] = array(
            'name' => 'phone1',
            'id' => 'phone1',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone1', $user->phone),
            'placeholder' => ''
        );

        $data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'value' => $this->form_validation->set_value('password'),
            'placeholder' => ''
        );

        $data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password',
            'value' => $this->form_validation->set_value('password_confirm'),
            'placeholder' => ''
        );

        $almacenes = $this->almacenes->get_combo_data(null,true);

        $roles = $this->roles->get_combo_data();
        //busco detalle de los planes
        $deficrear=$this->buscar_detalle_plan();

        $data['definecrear']=$deficrear; 
        $data['almacen'] = $this->almacenes->get_all('0',false);
        $data['caja'] = $this->Caja->get_all('0');
        $data['valor_caja'] = $data_empresa['data']['valor_caja'];
        $almacen_usuario = $this->almacenes->get_almacen_usuario($user->id);
       /* print_r($data['definecrear']);
        echo"<br><br>";
        print_r($almacenes);
        die();*/
        $this->layout->template('member')->show('usuarios/editar.php', array('data' => $data, 'almacen' => $almacenes, 'almacen_usuario' => $almacen_usuario, 'roles' => $roles, 'user' => $user));
    }

    public function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        $usuario = $this->usuarios->getId($id);

        if ($usuario->email == $this->session->userdata('email')) {
            $this->session->set_flashdata('message', '¡ALERTA! No puedes eliminar tu propio usuario');
        } else {
            if ($usuario->is_admin == "t") {
                $this->session->set_flashdata('message', '¡ALERTA! Este usuario no puede ser eliminado ya que es un ADMINISTRADOR');
            }else {               
                //Verificar que no tenga ventas ,oredenes,movimientos,devoluciones                      
                $cierre_cajas = $this->dbConnection->query('SELECT * FROM cierres_caja where id_Usuario = '.$id.' LIMIT 1')->row();
                if(!empty($cierre_cajas)){
                    $this->session->set_flashdata('message', '¡ALERTA! Este usuario no puede ser eliminado ya que tiene ventas asociadas');
                }
                else{
                    $orden_compra = $this->dbConnection->query('SELECT * FROM orden_compra where usuario_id = '.$id.' LIMIT 1')->row();
                    if(!empty($orden_compra)){
                        $this->session->set_flashdata('message', '¡ALERTA! Este usuario no puede ser eliminado ya que tiene ordenes de compras asociadas');
                    }else{
                        $movimientos = $this->dbConnection->query('SELECT * FROM movimiento_inventario where user_id = '.$id.' LIMIT 1')->row();
                        if(!empty($movimientos)){
                            $this->session->set_flashdata('message', '¡ALERTA! Este usuario no puede ser eliminado ya que tiene movimientos asociados');
                        }else{
                            $devoluciones = $this->dbConnection->query('SELECT * FROM devoluciones where usuario_id = '.$id.' LIMIT 1')->row();
                            if(!empty($devoluciones)){
                                $this->session->set_flashdata('message', '¡ALERTA! Este usuario no puede ser eliminado ya que tiene devoluciones asociadas');
                            }
                            else{
                                $this->usuarios->eliminar($id);
                                $this->session->set_flashdata('message', 'Se ha eliminado correctamente');
                            }                            
                        }                        
                    }                    
                }
            }
        }

        redirect('usuarios/index');
    }
    
    
    public function validaEmail(){
        if ($this->input->is_ajax_request()) {
            try {
                $result = $this->usuarios->validaEmail( $this->input->post('email') );
                echo json_encode(array('cod_status' => (int)$result == 1 ? '200' : '404'));
            } catch (Exception $exc) {
                echo json_encode(array('msg_status' => $exc->getMessage(), 'cod_status' => $exc->getCode()));
            }
        }
    }

}
?>
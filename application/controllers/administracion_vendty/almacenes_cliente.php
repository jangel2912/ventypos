<?php
/**
 * 
 */
class Almacenes_cliente extends CI_controller {
    var $user_id;
    var $id_db_config;
   
    function __construct()
	{
		
        parent::__construct();
        //$this->load->library('grocery_CRUD'); 
        $this->load->model('crm_model');       
        $this->load->model("usuarios_model");
        $this->load->model("crm_empresas_clientes_model");
        $this->load->model("crm_licencias_empresa_model");
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }
       
	}

    public function index(){
       
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        //get_id_config_email
        if ($this->ion_auth->in_group(5)) {    
            //$datosusuario = $this->crm_model->get_all_user();
            $datosusuario = $this->crm_empresas_clientes_model->get_all();
            $data['datosusuario']=$datosusuario;
            
            $this->layout->template('administracion_vendty')->show('administracion_licencia/almacenes_cliente/index',array('data' => $data));
        }else{
            redirect("frontend/index");
        }

    }
    public function nuevo($id){
       
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        if ($this->ion_auth->in_group(5)){           

            if($this->form_validation->run('almacenesbodegasadm') == true) {

                $dataalmacen = array(                   
                    'nombre' => trim($this->input->post('nombre'))    
                    , 'activo' => 1
                    , 'bodega' =>$this->input->post('bodega')
                );
                
                //armo la conexion del cliente
                $this->armar_conexion_bd_cliente($id);
                $this->load->model("almacenes_model", 'almacenes');
                $this->almacenes->initialize($this->dbConnection);
               
                //insertar almacen
                $idalmacen=$this->almacenes->add($dataalmacen);   

                if(!empty($idalmacen)){
                    //Cambiar la cantidad de almacenes en db_config
                    $cant = $this->db->query("SELECT almacen FROM db_config where id = '" . $id . "' limit 1")->result();
                    $cant=(int)$cant[0]->almacen+1;  
                    $this->crm_model->update_cant_almacen(array('id'=>$id),array('almacen'=>$cant));
                         
                    //data licencia
                    $empresa = $this->crm_model->get_empresas(array('id_db_config'=>$id)); 
                    $plan = $this->crm_model->get_planes(array('id'=>$this->input->post('id_plan'))); 
                                
                    $mes = 0;
                    switch($plan[0]->dias_vigencia){
                        case '30' : 
                            $mes =  1;
                        break;

                        case '90' : 
                            $mes =  3;
                        break;

                        case '180' : 
                            $mes =  6;
                        break;

                        case '365' : 
                            $mes =  12;
                        break;
                    }

                    $hoy=date('Y-m-d'); 
                    $format_fecha = strtotime ('- 1 day' , strtotime($hoy)); 
                    $hoy=date ('Y-m-d' , $format_fecha );                  
                    $format_fecha = strtotime ('-'.$mes.' month' , strtotime($hoy));
                    $fecha_inicio = date ( 'Y-m-d' , $format_fecha );                
                    $fecha_vencimiento = $hoy;
                    
                    $datalicencia = array(                
                        'idempresas_clientes' =>  $empresa[0]->idempresas_clientes
                        ,'planes_id' => $this->input->post('id_plan')
                        ,'fecha_creacion' => date('Y-m-d H:i:s')
                        ,'fecha_modificacion' => date('Y-m-d H:i:s')
                        ,'creado_por' => $this->session->userdata('user_id')
                        ,'fecha_inicio_licencia' =>   $fecha_inicio               
                        ,'fecha_vencimiento' =>  $fecha_vencimiento 
                        ,'estado_licencia' =>  15                    
                        ,'id_db_config' =>  $id                   
                        ,'id_almacen' =>  $idalmacen   
                        ,'fecha_activacion' => date('Y-m-d')                                                    
                    );
                    
                    $id_licencia = $this->crm_licencias_empresa_model->add($datalicencia);         
                    //agregar info en crm_db_activa_almacenes del almacen
                    
                    $dataalma = array(
                        'id_licencia' => $id_licencia
                        ,'id_db_config' => $id
                        ,'id_almacen' => $idalmacen
                        ,'razon_social_almacen' => ""
                        , 'numero_documento_almacen' => ""
                        , 'direccion_almacen' => ""
                        , 'nombre_almacen' => trim($this->input->post('nombre'))  
                        , 'telefono_almacen' => ""
                        , 'ciudad_almacen' => ""
                        , 'pais_almacen' => ""                        
                    );
                    
                    $this->crm_model->add_almacenes_info($dataalma);      

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'El almacen y la licencia se ha creado correctamente'));
                    redirect('administracion_vendty/almacenes_cliente/index');
                }else{
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'El almacén no pudo crearse'));
                    redirect('administracion_vendty/almacenes_cliente/index');
                }
            }

            $data['db']=$id;
            //$data['empresas'] = $this->crm_model->get_empresas(array('id_db_config'=>$id));             		    
            $data['planes'] = $this->crm_model->get_planes_All();
            $this->layout->template('administracion_vendty')->show('administracion_licencia/almacenes_cliente/nuevo',array('data' => $data));

        }else{
            redirect("frontend/index");
        }

       /* $distribuidores = $this->crm_model->get_all_distribuidor();
        $email_bd = $this->crm_model->get_all_user();
        $paises=$this->pais_model->getAll();        
        $data['distribuidor']=$distribuidores;
        $data['email']=$email_bd;
        $data['pais']=$paises;

        if ($this->form_validation->run('empresas_clientes') == true) {
            $user=explode("-", $this->input->post('id_db_config'));
            $data = array(

                 'nombre_empresa' =>  $this->input->post('nombre_empresa')
                ,'direccion_empresa' => $this->input->post('direccion_empresa')
                ,'telefono_contacto' => $this->input->post('telefono_contacto')
                ,'idusuario_creacion' => $user[0]
                ,'id_db_config' => $user[1]
                ,'id_distribuidores_licencia' =>  $this->input->post('id_distribuidores_licencia') 
                ,'id_user_distribuidor' =>  $this->input->post('id_user_distribuidor') 
                ,'identificacion_empresa' =>  $this->input->post('identificacion_empresa') 
                ,'tipo_identificacion' =>  $this->input->post('tipo_identificacion') 
                ,'razon_social_empresa' =>  $this->input->post('razon_social_empresa') 
                ,'ciudad_empresa' =>  $this->input->post('ciudad_empresa') 
                ,'departamento_empresa' => $this->input->post('provincia')
                ,'pais' => $this->input->post('pais')               

            );
          
            $this->crm_empresas_clientes_model->add($data);
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Empresa creada correctamente'));
            redirect('administracion_vendty/empresas/');

        }


        $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/nuevo',array('data' => $data));
        */
    }

    public function editar($id){
        
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('empresas_clientes') == true) {
            $user=explode("-", $this->input->post('id_db_config'));
            $data = array(
                
                 'idempresas_clientes' =>  $this->input->post('idempresas_clientes')
                ,'nombre_empresa' =>  $this->input->post('nombre_empresa')
                ,'direccion_empresa' => $this->input->post('direccion_empresa')
                ,'telefono_contacto' => $this->input->post('telefono_contacto')
                ,'idusuario_creacion' => $user[0]
                ,'id_db_config' => $user[1]
                ,'id_distribuidores_licencia' =>  $this->input->post('id_distribuidores_licencia') 
                ,'id_user_distribuidor' =>  $this->input->post('id_user_distribuidor') 
                ,'identificacion_empresa' =>  $this->input->post('identificacion_empresa') 
                ,'tipo_identificacion' =>  $this->input->post('tipo_identificacion') 
                ,'razon_social_empresa' =>  $this->input->post('razon_social_empresa') 
                ,'ciudad_empresa' =>  $this->input->post('ciudad_empresa') 
                ,'departamento_empresa' => $this->input->post('provincia')
                ,'pais' => $this->input->post('pais')               

            );
            // print_r($data); die();
            $this->crm_empresas_clientes_model->update($data);
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Empresa Modificada correctamente'));
            redirect('administracion_vendty/empresas/');

        }

        $data = array();
        $dataempresa = $this->crm_empresas_clientes_model->get_by_id($id);
        $data['dataempresa']=$dataempresa;
        $distribuidores = $this->crm_model->get_all_distribuidor();
        $email_bd = $this->crm_model->get_all_user();
        $paises=$this->pais_model->getAll();        
        $data['distribuidor']=$distribuidores;
        $data['email']=$email_bd;
        $data['pais']=$paises;

        $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/editar',array('data' => $data));
    }

    

    public function import_excel()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }       

        $this->load->library('phpexcel');
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $cursor = 0;
        $flag = false;
        $pointer = 0;
        $result = "";
        $data = array();
        $error_upload = "";
        $campos = array();
        if (!empty($_FILES))
        {           
            $config = array();
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'xlsx|xls';
            $this->load->library('upload', $config);
            if (!empty($_FILES['archivo']['name']))
            {
                if (!$this->upload->do_upload('archivo'))
                {
                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $excel_name = $upload_data['file_name'];
                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);
                    $reader->setReadDataOnly(TRUE);
                    $objXLS = $reader->load("uploads/" . $excel_name);
                    $campos[] = "No importar este campo";
                    for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++)
                    {
                        if ($flag)
                        {
                            $result = $alpha[$pointer] . $alpha[$cursor];
                            $cursor++;
                            if ($cursor >= (count($alpha) - 1))
                            {
                                $cursor = 0;
                                $pointer++;
                            }
                        }
                        else
                        {
                            $result = $alpha[$cursor];
                            $cursor++;
                            if ($cursor >= (count($alpha) - 1))
                            {
                                $cursor = 0;
                                $flag = true;
                            }
                        }

                        if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "")
                        {
                            $campos[] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                        }
                        else
                        {
                            break;
                        }
                    }
                   
                    $data['campos'] = $campos;
                    
                    $objXLS->disconnectWorksheets();
                    $this->session->set_userdata("file_upload_empresas", $excel_name);                    
                    $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_excel_fields', array('data' => $data));

                }
            }
        }
        else if (isset($_POST["submit"]))
        {
            
            $nombre_empresa = $this->input->post("nombre_empresa");
            $razon_social = $this->input->post("razon_social");
            $direccion = $this->input->post("direccion");
            $telefono = $this->input->post("telefono");
            $email = $this->input->post("email");
            $tipo_identificacion = $this->input->post("tipo_identificacion");
            $documento = $this->input->post("documento");
            $pais = $this->input->post("pais");
            $provincia = $this->input->post("provincia");
            $ciudad = $this->input->post("ciudad");
            $distribuidor = $this->input->post("distribuidor");
            $usuario_distribuidor = $this->input->post("usuario_distribuidor");                 
            
            $excel_name = $this->session->userdata("file_upload_empresas");
            $this->session->unset_userdata('file_upload_empresas');
            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);
            $reader->setReadDataOnly(TRUE);
            $objXLS = $reader->load("uploads/" . $excel_name);
           // var_dump($alpha);

            for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++)
            {
                if ($flag)
                {
                    $result = $alpha[$pointer] . $alpha[$cursor];
                    $cursor++;
                    if ($cursor >= (count($alpha) - 1))
                    {
                        $cursor = 0;
                        $pointer++;
                    }
                }
                else
                {
                    $result = $alpha[$cursor];
                    $cursor++;
                    if ($cursor >= (count($alpha) - 1))
                    {
                        $cursor = 0;
                        $flag = true;
                    }
                }

                if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "")
                {
                    $campos[$result] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                }
                else
                {
                    break;
                }
            }
            
            foreach($campos as $key => $value)
            {
                if ($value == $nombre_empresa)
                {
                    $nombre_empresa = $key;
                }
                else if ($value == $pais)
                {
                    $pais = $key;
                }
                else if ($value == $provincia)
                {
                    $provincia = $key;
                }
                else if ($value == $telefono)
                {
                    $telefono = $key;
                }
                else if ($value == $razon_social)
                {
                    $razon_social = $key;
                }
                else if ($value == $documento)
                {
                    $documento = $key;
                }
                else if ($value == $distribuidor)
                {
                    $distribuidor = $key;
                }
                else if ($value == $usuario_distribuidor)
                {
                    $usuario_distribuidor = $key;
                }
                else if ($value == $email)
                {
                    $email = $key;
                }
                else if ($value == $ciudad)
                {
                    $ciudad = $key;
                }
                else if ($value == $direccion)
                {
                    $direccion = $key;
                }                
                else if ($value == $tipo_identificacion)
                {
                    $tipo_identificacion = $key;
                }                
            }

            $count = 2;
            $adicionados = 0;
            $noadicionados = 0;
            $errores_importar="";
            
            if ($nombre_empresa != 'No importar este campo' && $documento != 'No importar este campo' || $email != 'No importar este campo')
            {
                while ($objXLS->getSheet(0)->getCell($nombre_empresa . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($documento . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($email . $count)->getValue() != '')
                {
                    $usuario=$this->usuarios_model->get_id_config_email($objXLS->getSheet(0)->getCell($email . $count)->getValue());
                    if(count($usuario)>0){
                    
                        $array_datos = array(
                            'nombre_empresa' => $objXLS->getSheet(0)->getCell($nombre_empresa . $count)->getValue() ,
                            'identificacion_empresa' => $objXLS->getSheet(0)->getCell($documento . $count)->getValue() ,                        
                            'idusuario_creacion' => $usuario->id,
                            'id_db_config' => $usuario->db_config_id                        
                        );

                        if ($pais != 'No importar este campo') $array_datos['pais'] = $objXLS->getSheet(0)->getCell($pais . $count)->getValue();
                        if ($provincia != 'provincia') $array_datos['departamento_empresa'] = $objXLS->getSheet(0)->getCell($provincia . $count)->getValue();
                        if ($telefono != 'No importar este campo') $array_datos['telefono_contacto'] = $objXLS->getSheet(0)->getCell($telefono . $count)->getValue();
                        if ($razon_social != 'No importar este campo') $array_datos['razon_social_empresa'] = $objXLS->getSheet(0)->getCell($razon_social . $count)->getValue();
                        if ($ciudad != 'No importar este campo') $array_datos['ciudad_empresa'] = $objXLS->getSheet(0)->getCell($ciudad . $count)->getValue();
                        if ($direccion != 'No importar este campo') $array_datos['direccion_empresa'] = $objXLS->getSheet(0)->getCell($direccion . $count)->getValue();
                        if ($distribuidor != 'No importar este campo') $array_datos['id_distribuidores_licencia'] = $objXLS->getSheet(0)->getCell($distribuidor . $count)->getValue();
                        if ($usuario_distribuidor != 'No importar este campo') $array_datos['id_user_distribuidor'] = $objXLS->getSheet(0)->getCell($usuario_distribuidor . $count)->getValue();
                        if ($tipo_identificacion != 'No importar este campo') $array_datos['tipo_identificacion'] = $objXLS->getSheet(0)->getCell($tipo_identificacion . $count)->getValue();
                        
                        if(!empty($array_datos['idusuario_creacion'])){                    
                            $this->crm_empresas_clientes_model->add($array_datos);
                            $adicionados++;
                        }

                    }else{
                        $errores_importar.="<p> Empresa:".$objXLS->getSheet(0)->getCell($nombre_empresa . $count)->getValue()."</p>";
                        $noadicionados++;
                    }
                    
                    $count++;
                }
            }
            else
            {
                $this->session->set_flashdata('message', custom_lang('sima_import_failure', 'La importación falló'));
                redirect('administracion_vendty/empresas/import_excel');
            }

            $objXLS->disconnectWorksheets();
            unset($objXLS);
            $data['count'] = $count - 2;
            $data['adicionados'] = $adicionados;
            $data['noadicionados'] = $noadicionados;
            $data['errores_importar'] = $errores_importar;
            unlink("uploads/$excel_name");
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_complete', array('data' => $data));
        }
        else
        {
            $data['data']['upload_error'] = $error_upload;
           // $data_empresa = $this->mi_empresa->get_data_empresa();
          //  $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_excel', array('data' => $data));
        }
    }   

    public function consultar_usuarios_distribuidores(){
		$id_distribuidor = $this->input->post('distribuidor');
		$this->db->where(array('id_distribuidores_licencia'=>$id_distribuidor));
		$this->db->select('users_id, email');
		$this->db->from('crm_usuarios_distribuidores');
		$this->db->join('users','crm_usuarios_distribuidores.users_id=users.id');
		$query = $this->db->get();
		$devolver = array();
		foreach ($query->result() as $key => $value) {
		 		$devolver[]=array('id'=>$value->users_id,'email'=>$value->email);
		}
		echo json_encode($devolver); 
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

    private function armar_conexion_bd_cliente($id_db_config){
		$this->db->where(array('id'=>$id_db_config));
		$datos_db_config = $this->db->get('db_config')->row();
		$usuario = $datos_db_config->usuario;
        $clave = $datos_db_config->clave;
        $servidor = $datos_db_config->servidor;
        $base_dato = $datos_db_config->base_dato;
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
	}

}

<?php

class Bodegas extends CI_Controller {

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

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

        $this->load->model("licencias_model", 'licenciasModel');
        $this->licenciasModel->initialize($this->dbConnection);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        $this->load->model('crm_model');

        $this->load->model('crm_licencias_empresa_model');

        $this->load->model("usuarios_model", 'usuarios');
        $this->usuarios->initialize($this->dbConnection);

        $this->load->model('pais_model','pais');
        $this->dbConnection = $this->load->database($dns, true);  
              
        $this->load->model("Caja_model",'Caja');
        $this->Caja->initialize($this->dbConnection);

        $this->load->model("crm_licencia_model", 'crm_licencia_model');
        
    }

    public function index($offset = 0) {
        
        $this->almacenes->actualizarTabla();
        
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');

        }

       /* $almacenes=$this->almacenes->get_ajax_data_bodegas();
        print_r($almacenes);
        die("sdad");*/

        if($this->session->userdata('is_admin') == "t"){ 
            $this->layout->template('member')->show('bodegas/index');
       }else{
          redirect(site_url('frontend/index'));
       }

    }
    
    
    
    public function getIdAlmacenActualByUserId($id) {
        return $this->almacenes->getIdAlmacenActualByUserId($id);
    }
    
    
    // administrador
    public function administrador_vend() {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');

    }

    // solo administtador
    public function administrador_vendty() {
        $var = $this->session->userdata('identity');
        //var_dump($var);die();
        if ( $var == "roxanna@vendty.com" || $var == "soporte@vendty.com") {
            $this->layout->template('member')->show('bodegas/usuarios_admin');
        } else {
            redirect('frontend', 'refresh');
            
        }

    }

    public function administrador_vendty_dbconfig() {

        if ($this->input->post('alm')) {
            for ($contx = 0; $contx < count($this->input->post('alm')); $contx++) {

                $array_datos = array(

                    "almacen" => $this->input->post('alm')[$contx],

                );

                $this->db->where('id', $this->input->post('cod')[$contx]);
                $this->db->update("db_config", $array_datos);

            }
        }

        $db_config_id = $this->session->userdata('db_config_id');
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $user = $this->db->query("SELECT username, email, db_config_id FROM users  where is_admin = 't' and term_acept = 'Si' and active = '1'  group by db_config_id  order by id asc ")->result();
        foreach ($user as $dat) {
            //  echo "SELECT * FROM db_config  where id = '$dat->db_config_id' and servidor = '162.209.50.206' ";
            $db_config = $this->db->query("SELECT * FROM db_config  where id = '$dat->db_config_id' and servidor = '169.53.12.166' ")->result();
            foreach ($db_config as $dat_1) {

                $db = mysql_select_db($dat_1->base_dato);

                if (($err = mysql_errno()) == 1049) {
                    echo "";
                } else {

                    $usuario = $dat_1->usuario;
                    $clave = $dat_1->clave;
                    $servidor = $dat_1->servidor;
                    $base_dato = $dat_1->base_dato;

                    $dns_1 = "mysql://$usuario:$clave@$servidor/$base_dato";

                    $this->dbConnection_dbconfig = $this->load->database($dns_1, true);

                    $almacen = $this->dbConnection_dbconfig->query("SELECT count(*) as total_almacen FROM `" . $dat_1->base_dato . "`.`almacen` ")->result();
                    foreach ($almacen as $dat_2) {
                        $almacen_2 = $dat_2->total_almacen;
                    }

                    $otro = $this->dbConnection_dbconfig->query("SELECT valor_opcion FROM `" . $dat_1->base_dato . "`.`opciones` where id='1' ")->result();
                    foreach ($otro as $dat_2) {
                        $empresa = $dat_2->valor_opcion;
                    }

                    $otro = $this->dbConnection_dbconfig->query("SELECT count(*) as total_ventas FROM `" . $dat_1->base_dato . "`.`venta` ")->result();
                    foreach ($otro as $dat_2) {
                        $total_ventas = $dat_2->total_ventas;
                    }

                    $otro = $this->dbConnection_dbconfig->query("SELECT date(fecha) as fecha_fac FROM `" . $dat_1->base_dato . "`.`venta` order by fecha desc limit 1 ")->result();
                    foreach ($otro as $dat_2) {
                        $fecha = $dat_2->fecha_fac;
                    }

                    $otro = $this->dbConnection_dbconfig->query("SELECT count(*) as total_productos FROM `" . $dat_1->base_dato . "`.`producto` ")->result();
                    foreach ($otro as $dat_2) {
                        $total_productos = $dat_2->total_productos;
                    }

                    //if($fecha >= '2016-01-01' ){

                    $user_result1[] =
                    array('username' => $dat_1->fecha,
                        'email' => $dat->email,
                        'almacen' => $dat_1->almacen,
                        'almacen_2' => $almacen_2,
                        //   'db_config_id' => $dat->db_config_id,
                        //   'db_config_nombre' => $dat_1->base_dato,
                        'empresa' => $empresa,
                        'total_ventas' => $total_ventas,
                        'total_productos' => $total_productos,
                        'estado' => $dat_1->estado,
                    );

                    //}

                }

                return array(
                    'aaData' => $user_result1,
                );
            }
        }

        $this->layout->template('member')->show('bodegas/usuarios_admin_dbconfig', array('user' => $user_result1));

    }

    public function nuevo() {

        if (!$this->ion_auth->logged_in()) {redirect('auth', 'refresh');}
        
        $this->almacenes->actualizarTabla();
        $this->almacenes->actualizarTablaAlmacenBodega();
                
        $db_config_id = $this->session->userdata('db_config_id');
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $user = $this->db->query("SELECT almacen FROM db_config where id = '" . $db_config_id . "' limit 1")->result();

        /*** licencias y planes activos **/
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $licenciaasociada = $this->licenciasModel->by_id_config_almacen($db_config_id,$almacenActual);                        
        $datos_plan = $this->crm_model->get_detalle_plan("where id_plan=".$licenciaasociada['id_plan']." and nombre_campo='bodegas'");
        if(isset($datos_plan[0])){
            $cantbodegas=$datos_plan[0]->valor;
        }
        
        $bodegasexistente=$this->almacenes->getAllBodega();
        $cantbodegasexistente=count($bodegasexistente);
       
         //conseguir las licencias activas    
        $db_config_id = $this->session->userdata('db_config_id');     
        $hoy=date('Y-m-d');        
        $planesactivos="where id_plan in (";
        //$licenciatotalbd = $this->crm_licencias_empresa_model->get_all_id(array('id_db_config'=>$db_config_id,'fecha_vencimiento >='=>$hoy,'estado_licencia !='=>'15'));  
        $licenciatotalbd = $this->crm_licencias_empresa_model->get_all_id(array('id_db_config'=>$db_config_id));  
        
        foreach($licenciatotalbd as $idplan){
            $planesactivos.="'".$idplan->id_plan."',";
        }        
        $planesactivos=trim($planesactivos,',');        
        $planesactivos.=")";  
        $planesdetalleactivos = $this->crm_model->get_detalle_plan($planesactivos);   
        $al_campo=array();
        $deficrear=array();
        $licenciasbodegas=array();
        $cantbodegasplanes=0;
        
        foreach ($licenciatotalbd as $key => $value) {
            $al_campo[$value->id_almacen]['almacen']=$value->id_almacen;                
            $al_campo[$value->id_almacen]['dias_vigencia']=$value->dias_vigencia;                
            $al_campo[$value->id_almacen]['tipo_plan']=$value->tipo_plan;                
            $al_campo[$value->id_almacen]['fecha_inicio_licencia']=$value->fecha_inicio_licencia;                
            $al_campo[$value->id_almacen]['fecha_vencimiento']=$value->fecha_vencimiento;                
                            
            foreach($planesdetalleactivos as $key1 => $value1 ){                  
                if($value->id_plan==$value1->id_plan){         
                    if($value->fecha_vencimiento<$hoy){
                        $al_campo[$value->id_almacen][$value1->nombre_campo]= 0;
                    }else{
                        $al_campo[$value->id_almacen][$value1->nombre_campo]= $value1->valor; 
                        if($value1->nombre_campo=='bodegas'){
                            if(!is_null($value1->valor)){   
                                $cantbodegasplanes+=$value1->valor;   
                            }else{                             
                                $cantbodegasplanes+=100;
                            }                          
                            $al_campo[$value->id_almacen][$value1->nombre_campo]= $cantbodegasplanes;              
                            $al_campo[$value->id_almacen]['sonbodegas']=$value1->valor;
                            $al_campo[$value->id_almacen]['sonbodegastotales']=$cantbodegasplanes;
                        }      
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
            $cantbodegacreadas= $this->almacenes->cantBodega();            
            if($cantbodegacreadas<$cantbodegasplanes) {
                $deficrear[$key]['bodegas']="1";//1 permitir -  0 no permitir    
                
                if($value['sonbodegas']!=0){
                    $licenciasbodegas[$key]['bodegas']="1";//1 permitir -  0 no permitir 
                    $licenciasbodegas[$key]['cantbodegacreadas']=$cantbodegacreadas;
                    $licenciasbodegas[$key]['dias_vigencia']=$value['dias_vigencia']; 
                    $licenciasbodegas[$key]['tipo_plan']=$value['tipo_plan']; 
                    $licenciasbodegas[$key]['fecha_inicio_licencia']=$value['fecha_inicio_licencia']; 
                    $licenciasbodegas[$key]['fecha_vencimiento']=$value['fecha_vencimiento']; 
                    $licenciasbodegas[$key]['sonbodegas']=$value['sonbodegas']; 
                    $licenciasbodegas[$key]['sonbodegastotales']=$value['sonbodegastotales']; 
                }
                

            }else{
                $deficrear[$key]['bodegas']="0";// 0 no permitir              
                if($value['sonbodegas']!=0){
                    $licenciasbodegas[$key]['bodegas']="0";//1 permitir -  0 no permitir 
                    $licenciasbodegas[$key]['cantbodegacreadas']=$cantbodegacreadas;
                    $licenciasbodegas[$key]['dias_vigencia']=$value['dias_vigencia']; 
                    $licenciasbodegas[$key]['tipo_plan']=$value['tipo_plan']; 
                    $licenciasbodegas[$key]['fecha_inicio_licencia']=$value['fecha_inicio_licencia']; 
                    $licenciasbodegas[$key]['fecha_vencimiento']=$value['fecha_vencimiento']; 
                    $licenciasbodegas[$key]['sonbodegas']=$value['sonbodegas']; 
                }
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
        $data['definecrear']=$deficrear; 
       
        if ($this->form_validation->run('bodegas') == true) {
            
            $active = isset($_POST['activo']) ? 1 : 0;

            $data = array(
                'razon_social' =>  null,
                'direccion' => $this->input->post('direccion')
                , 'resolucion_factura' =>null
                , 'nit' => null
                , 'nombre' => $this->input->post('nombre')
                , 'telefono' => "00000"
                , 'correo_electronico' => null
                , 'prefijo' => null
                , 'consecutivo' => null
                , 'meta_diaria' => null
                , 'pais' => $this->input->post('pais_bodega')
                , 'ciudad' => $this->input->post('provincia')
                , 'activo' => $active
                , 'numero_fin' => null
                , 'fecha_vencimiento' => null
                , 'numero_alerta' => null
                , 'fecha_alerta' => null
                , 'bodega' => true

            );

           
            $id_alma=$this->almacenes->add($data);
            $band=0;
            //verificar mis licencias y crear licencia de bodega
            // 15 PLAN BODEGA ANUAL
            // 16 PLAN BODEGA TRIMESTRAL
            // 17 PLAN BODEGA MENSUAL
                        
            foreach ($licenciasbodegas as $key => $value) {               
                if(($value['bodegas']==1) && ($value['sonbodegastotales']>$value['cantbodegacreadas']) && ($band==0)){
                    //busco el id_cliente de la bd 
                    $empresa = $this->crm_model->get_empresas(array('id_db_config'=>$db_config_id));	            
                    $empresa=$empresa[0]->idempresas_clientes;	

                    $plan=17;
                    switch ($value['dias_vigencia']) {
                        case 30:
                            $plan=17;
                            break;
                        case 90:
                            $plan=16;
                            break;
                        case 365:
                            $plan=15;
                            break;
                        default:
                            $plan=16;
                            break;
                    }

                    $datalicencia = array(
                        'fecha_creacion' =>  date('Y-m-d H:s:i')
                        ,'creado_por' =>  $this->session->userdata('user_id')
                        ,'fecha_modificacion' =>  date('Y-m-d H:s:i')
                        ,'fecha_inicio_licencia' =>  $value['fecha_inicio_licencia']                       
                        ,'fecha_vencimiento' => $value['fecha_vencimiento']
                        , 'idempresas_clientes' =>$empresa
                        , 'planes_id' => $plan
                        , 'id_db_config' => $db_config_id
                        , 'id_almacen' => $id_alma
                        , 'estado_licencia' => 1
                    );

                    $this->crm_licencia_model->agregar_licencia($datalicencia);
                    $band=1;
                }
            }
            
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Bodega creada correctamente'));
            $this->session->set_flashdata('message_type', custom_lang('sima_category_created_message', 'success'));
            redirect('frontend/configuracion');
        }
        
        if($cantbodegacreadas<$cantbodegasplanes){             
            $data['data']['miempresa_data'] = $this->miempresa->get_data_empresa();
            $data['paises'] = $this->pais->getAll();
            $this->layout->template('member')->show('bodegas/nuevo', array('data' => $data));
        }else{
           $data['data']['miempresa_data'] = $this->miempresa->get_data_empresa();
           $data['paises'] = $this->pais->getAll();
            $this->layout->template('member')->show('bodegas/nuevo', array('data' => $data));
        }

    }

    public function get_by_id($id = false) {
        if ($id) {
            echo $this->output->set_content_type('application/json')->set_output(json_encode($this->almacenes->get_by_id($id)));
        }
    }

    public function almacen_check($str) {

        $id = $this->almacenes->get_by_name($str);

        if (!empty($id)) {

            $id_producto = $this->input->post('id');

            if (!empty($id_producto) && $id_producto == $id) {

                return true;

            }

            $this->form_validation->set_message('product_check', 'El %s existe');

            return false;

        }

        return true;

    }

    public function get_ajax_data() {
        
      $this->output->set_content_type('application/json')->set_output(json_encode($this->almacenes->get_ajax_data_bodegas()));

    }

    public function get_ajax_data_admin_usuarios() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->almacenes->get_ajax_data_admin_usuarios()));

    }

    public function editar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');

        }
        
        $this->almacenes->actualizarTabla();
        
        if ($this->form_validation->run('bodegas') == true) {
            $active = isset($_POST['activo']) ? 1 : 0;            

            $data = array(
                'id' => $this->input->post('id')
                ,'razon_social' =>  null
                ,'direccion' => $this->input->post('direccion')
                , 'resolucion_factura' =>null
                , 'nit' => null
                , 'nombre' => $this->input->post('nombre')
                , 'telefono' => "00000"
                , 'correo_electronico' => null
                , 'prefijo' => null
                , 'consecutivo' => null
                , 'meta_diaria' => null
                , 'pais' => $this->input->post('pais_almacen')
                , 'ciudad' => $this->input->post('provincia')
                , 'activo' => $active
                , 'numero_fin' => null
                , 'fecha_vencimiento' => null
                , 'numero_alerta' => null
                , 'fecha_alerta' => null
                , 'bodega' => true
            );
           
            $this->almacenes->update($data);
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Bodega actualizada correctamente'));
            $this->session->set_flashdata('message_type', custom_lang('sima_category_created_message', 'success'));
            redirect('bodegas/index');
        }

        $data = array();
        $data['data'] = $this->almacenes->get_by_id($id);
        $data['data']['miempresa_data'] = $this->miempresa->get_data_empresa();
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $db_id=$this->session->userdata['db_config_id'];
        $licenciavencidas = $this->licenciasModel->by_id_config_estado_licencias( "where id_db_config=$db_id and estado_licencia=15",false);
        $data['licenciavencidas']=$licenciavencidas; 
        $data['paises'] = $this->pais->getAll();

        $this->layout->template('member')->show('bodegas/editar', array('data' => $data));
    }

    public function editar_usuarios_admin($id) {
        $var = $this->session->userdata;
        if ($var['identity'] != 'arnulfo@vendty.com' || $var['identity'] != 'roxanna@vendty.com' || $var['identity'] != 'imagen@vendty.com' || $var['identity'] != 'jonatancastro1@gmail.com') {
            if (isset($_POST['id'])) {
                $almacenes = $_POST['almacenes'];
                $tienda = $_POST['tienda'];
                $estado = $_POST['estado'];
                $dias_restantes = $_POST['dias_restantes'];
                $estado_cliente = $_POST['estado_cliente'];
                $alertas_inventario = $_POST['alertas_inventario'];
                $plan_separe = $_POST['plan_separe'];
                $atributos = $_POST['atributos'];
                $puntos = $_POST['puntos'];
                $offline = $_POST['offline'];

                $data = array(
                    'id' => $this->input->post('id'),
                    'almacen' => $almacenes,
                    'tienda' => $tienda,
                    'estado' => $estado,
                    'dias_restantes' => $dias_restantes,
                    'estado_cliente' => $estado_cliente,
                    'alertas_inventario' => $alertas_inventario,
                    'plan_separe' => $plan_separe,
                    'atributos' => $atributos,
                    'puntos' => $puntos,
                    'offline' => $offline
                );

                $this->almacenes->update_user($data);
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', '<i class="icon-exclamation-sign"></i> Usuario actualizado correctamente'));
                redirect('bodegas/administrador_vendty');
            }
            $data = array();
            $data['data'] = $this->almacenes->get_users_by_id($id);
            $this->layout->template('member')->show('bodegas/editar_usuarios_admin', array('data' => $data));
        } else {
            redirect('frontend', 'refresh');
        }
    }

    public function detalles($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');

        }

        $data = $this->productos->get_by_id($id);

        $this->layout->template('member')->show('productos/detalles', array('data' => $data));

    }

    public function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');

        }
        $db_config_id = $this->session->userdata('db_config_id');   
        $resp = $this->almacenes->delete($id);
        if($resp === true)
        {
            $this->crm_licencias_empresa_model->delete($id,array('id_db_config'=>$db_config_id,'id_almacen'=>$id));
            $cant = $this->db->query("SELECT almacen FROM db_config where id = '" . $db_config_id . "' limit 1")->result();
            //$cant=(int)$cant[0]->almacen-1;  
            //$this->crm_model->update_cant_almacen(array('id'=>$db_config_id),array('almacen'=>$cant));

            $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'Se ha eliminado correctamente'));
        }else
        {
            $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'La bodega no puede ser eliminada ya que los siguientes usuarios están asociados a ella.<br>'.$resp.'Para eliminar la bodega seleccione otra para los usuarios mencionados'));
        }
        //var_dump($resp);die;
        redirect("bodegas/index");

    }

    public function excel() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');

        }

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Descripción');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre del impuesto');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Porciento');

        $query = $this->productos->excel();

        $row = 2;

        foreach ($query as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->id_producto);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->nombre);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->descripcion);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->precio);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->nombre_impuesto);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->porciento);

            $row++;

        }

        $styleThinBlackBorderOutline = array(

            'borders' => array(

                'outline' => array(

                    'style' => PHPExcel_Style_Border::BORDER_THIN,

                    'color' => array('argb' => 'FF000000'),

                ),

            ),

        );

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(

            array(

                'font' => array(

                    'bold' => true,

                ),

                'alignment' => array(

                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,

                ),

                'borders' => array(

                    'top' => array(

                        'style' => PHPExcel_Style_Border::BORDER_THIN,

                    ),

                    'bottom' => array(

                        'style' => PHPExcel_Style_Border::BORDER_THIN,

                    ),

                ),

                'fill' => array(

                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,

                    'rotation' => 90,

                    'startcolor' => array(

                        'argb' => 'FFA0A0A0',

                    ),

                    'endcolor' => array(

                        'argb' => 'FFFFFFFF',

                    ),

                ),

            )

        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Productos');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet

        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="productos.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;

    }

    public function import_excel() {

        if (!$this->ion_auth->logged_in()) {

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

        $config = array();

        if (!empty($_FILES)) {

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xls';

            $this->load->library('upload', $config);

            if (!empty($_FILES['archivo']['name'])) {

                if (!$this->upload->do_upload('archivo')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                } else {

                    $upload_data = $this->upload->data();

                    $excel_name = $upload_data['file_name'];

                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

                    $reader->setReadDataOnly(TRUE);

                    $objXLS = $reader->load("uploads/" . $excel_name);

                    $campos[] = "No importar este campo";

                    for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                        if ($flag) {

                            $result = $alpha[$pointer] . $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $pointer++;

                            }

                        } else {

                            $result = $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $flag = true;

                            }

                        }

                        if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                            $campos[] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();

                        } else {

                            break;

                        }

                    }

                    $data['campos'] = $campos;

                    $objXLS->disconnectWorksheets();

                    $this->session->set_flashdata("file_upload_productos", $excel_name);

                    unset($objXLS);

                    $this->layout->template('member')->show('productos/import_excel_fields', array('data' => $data));

                }

            }

        } else if (isset($_POST["submit"])) {

            $nombre_producto = $this->input->post("nombre_producto");

            $precio = $this->input->post("precio");

            $descripcion = $this->input->post("descripcion");

            $nombre_impuesto = $this->input->post("nombre_impuesto");

            $porciento = $this->input->post("porciento");

            $excel_name = $this->session->flashdata("file_upload_productos");

            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

            $reader->setReadDataOnly(TRUE);

            $objXLS = $reader->load("uploads/" . $excel_name);

            for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                if ($flag) {

                    $result = $alpha[$pointer] . $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $pointer++;

                    }

                } else {

                    $result = $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $flag = true;

                    }

                }

                if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                    $campos[$result] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();

                } else {

                    break;

                }

            }

            foreach ($campos as $key => $value) {

                if ($value == $nombre_producto) {

                    $nombre_producto = $key;

                } else if ($value == $porciento) {

                    $porciento = $key;

                } else if ($value == $precio) {

                    $precio = $key;

                } else if ($value == $nombre_impuesto) {

                    $nombre_impuesto = $key;

                } else if ($value == $descripcion) {

                    $descripcion = $key;

                }

            }

            $count = 2;

            $adicionados = 0;

            $noadicionados = 0;

            if ($nombre_producto != 'No importar este campo' && $precio != 'No importar este campo' && $nombre_impuesto != 'No importar este campo' && $porciento != 'No importar este campo') {

                while ($objXLS->getSheet(0)->getCell($nombre_producto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($porciento . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($precio . $count)->getValue() != '') {

                    $porcientoData = $objXLS->getSheet(0)->getCell($porciento . $count)->getValue();

                    $nombreImpuestoData = $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue();

                    $nombreProductoData = $objXLS->getSheet(0)->getCell($nombre_producto . $count)->getValue();

                    $precioData = $objXLS->getSheet(0)->getCell($precio . $count)->getValue();

                    $descripcionData = "";

                    if ($descripcion != 'No importar este campo') {
                        $descripcionData = $objXLS->getSheet(0)->getCell($descripcion . $count)->getValue();
                    }

                    if (!$this->productos->excel_exist($nombreProductoData, $precioData)) {

                        $id_impuesto = $this->impuestos->excel_exist_get_id($nombreImpuestoData, $porcientoData);

                        $array_datos = array(

                            "nombre" => $nombreProductoData,

                            "descripcion" => $descripcionData,

                            "precio" => $precioData,

                            "id_impuesto" => $id_impuesto,

                        );

                        $this->productos->excel_add($array_datos);

                        $adicionados++;

                    } else {

                        $noadicionados++;

                    }

                    $count++;

                }

            } else {

            }

            $objXLS->disconnectWorksheets();

            unset($objXLS);

            $data['count'] = $count - 2;

            $data['adicionados'] = $adicionados;

            $data['noadicionados'] = $noadicionados;

            unlink("uploads/$excel_name");

            $this->layout->template('member')->show('productos/import_complete', array('data' => $data));

        } else {

            $data['data']['upload_error'] = $error_upload;

            $this->layout->template('member')->show('productos/import_excel', array('data' => $data));

        }

    }

}

?>
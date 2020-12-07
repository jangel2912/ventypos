<?php

class Clientes extends CI_Controller {

    const PUNTOS = 4;

    var $dbConnection;
    var $user;

    function __construct() {

        parent::__construct();


        $this->user = $this->session->userdata('user_id');

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);


        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("clientes_model", 'clientes');
        $this->clientes->initialize($this->dbConnection);

        $this->load->model("lista_precios_model", 'lista_precios');
        $this->lista_precios->initialize($this->dbConnection);

        $this->load->model("lista_detalle_precios_model", 'lista_detalle_precios');
        $this->lista_detalle_precios->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);
        
        $this->load->model("grupo_model", 'grupo');
        $this->grupo->initialize($this->dbConnection);

        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        $this->load->model("puntos_model", 'puntos');
        $this->puntos->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');



        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    // saber si el sistema de cartera esta activo desde la tabla d eopciones
    public function getCartera() {

        $result = Array(
            "opc" => $this->miempresa->getCartera()
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function setCartera($id, $opcion) {
        $this->clientes->setCartera($id, $opcion);
    }

    public function aa($term) {
        $result = $this->clientes->get_termg_cartera($term);
        pr($result);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function get_ajax_clientes() {

        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $result = $this->clientes->get_termg($this->input->get('term', TRUE),$almacenActual);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function get_ajax_clientes_cartera() {
        $result = $this->clientes->get_termg_cartera($this->input->get('term', TRUE));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function get_ajax_clientes_correo() {
        $result = $this->input->post('idventa', TRUE);
        $correo = '';
        $cliente = $this->dbConnection->query("SELECT (SELECT email FROM clientes where id_cliente = cliente_id) as clicorreo FROM venta where id = '$result' ")->result();
        foreach ($cliente as $dat_2) {
            $correo = $dat_2->clicorreo;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($correo));
    }

    public function add_ajax_client() {
        $this->add_client('clientes_en_ajax');
    }

    public function add_fast_ajax_client() {
        $this->add_client('clientes_en_venta');
    }

    public function update_fast_ajax_client() {
        $this->update_client();
    }

    private function update_client() {

        $telefono="";
        $movil="";
       
            $telefono=$this->input->post('telefono');
            $cel=explode('/',$telefono);

            if((!empty($cel[0])) && (!empty($cel[1]))){
                $telefono=$cel[0];
                $movil=$cel[1];

            }else{
                if(!empty($cel[0])){
                    $telefono=$cel[0];
                }
                if(!empty($cel[1])){
                    $movil=$cel[1];
                }
            }

            $data=array(
                'nombre_comercial'=> $this->input->post('nombre_comercial'),
                'telefono'=> $telefono,
                'movil'=> $movil,
                'direccion'=> $this->input->post('direccion'),
            );
            
            $this->clientes->update_ajax(array('id_cliente'=>$this->input->post('id')),$data);
            $result = array('id_cliente' => $this->input->post('id'), 'success' => TRUE);
            $this->output->set_content_type('application/json')->set_output(json_encode($result));           
        

    }
    
    private function add_client($validation) {
        $this->load->library('form_validation');
        if ($this->form_validation->run($validation)) {

            $nombre_comercial = $this->input->post('nombre_comercial');
            $email = $this->input->post('email');
            $tipo_identificacion = $this->input->post('tipo_identificacion');
            $nif_cif = $this->input->post('nif_cif');
            $email = $this->input->post('email');
            $telefono = $this->input->post('telefono');
            $direccion = $this->input->post('direccion');
            $pais = $this->input->post('pais');
            $ciudad = $this->input->post('provincia');
            $celular = $this->input->post('celular');
            $plan = $this->input->post('plan_puntos');
            $plan_punto = $this->input->post('pl');
            $cod = $this->input->post('cod_targeta');
            $grupo = $this->input->post('grupo');
            $id_cliente = $this->clientes->add_light($nombre_comercial, $tipo_identificacion, $nif_cif, $email, $telefono, $direccion, $pais, $ciudad, $celular, $plan, $plan_punto, $cod, $grupo);
            
            if($plan && getGeneralOptions('puntos_correo_bienvenida')->valor_opcion):
                $this->load->library('email');
                $this->email->initialize();
                $plan = $this->puntos->get_plan($plan_punto);
               
                $logo = getGeneralOptions('logotipo_empresa')->valor_opcion;
                if(!is_null($plan)):
                    $data = array(
                        'logo' => $logo,
                        'name' => $nombre_comercial,
                        'plan_name' => $plan->nombre,
                        'points' => $plan->puntos,
                        'points_value' => $plan->valor,
                        'date_of_expiry' => $plan->tiempo_caducidad
                    );
                    $message = $this->load->view("email/welcome_points_plan",$data,true);  
                    $this->email->from('no-responder@vendty.net', 'Vendty POS - Bienvenido al plan de puntos');
                    $this->email->to($email);
                    $this->email->subject('Bienvenido al plan de puntos');
                    $this->email->message($message);
                    $this->email->send(); 
                endif;
            endif;


            $result = array('id_cliente' => $id_cliente, 'success' => TRUE);
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => FALSE, 'msg' => validation_errors())));
        }
    }

    public function get_ajax_data() {

        $start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength'); 
        $search = $this->input->get('sSearch');
        
        if($this->input->get('iSortCol_0')==0){
            
            if($order=$this->input->get('sEcho')=="1"){
                $order="DESC";
                $col="id_cliente ";
            }else{
                $col= $this->input->get('iSortCol_0')+1;               
                $order=$this->input->get('sSortDir_0');
            }
            
        }
        else{
            $col= $this->input->get('iSortCol_0')+1;
            $order=$this->input->get('sSortDir_0');
        }
        $orderby= " ORDER BY ".$col." ".$order;       

        $this->output->set_content_type('application/json')->set_output(json_encode($this->clientes->get_ajax_data($start,$limit,$search,$orderby)));
    }

    public function index($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        
        //actualizar tabla clientes
        $this->clientes->actualizarTabla();
        $data = array();

        $data["total"] = $this->clientes->get_total();

        $data["data"] = $this->clientes->get_all($offset);
        $data['puntos'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::PUNTOS);

        $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

        $this->layout->template('member')->show('clientes/index', array('data' => $data));
    }

    public function load_provincias_from_pais() {
        $result = array();
        $pais = $this->input->get('pais', TRUE);

        $result = $this->clientes->get_provincia($pais);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /* Crear nuevo cliente */

    public function nuevo() {

        /* Si NO esta autenticado redirecciona a auth */
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        
       

        $this->load->library('form_validation');

        if ($this->form_validation->run('clientes')) {

            $data = $this->clientes->add();

            $index = array();

            $index['id'] = $data['id_cliente'];

            $index['type'] = "clientes";

            $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Cliente creado correctamente'));

            redirect('clientes/index');
        } else {


            $data = array();

            $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

            $data['pais'] = $this->clientes->get_pais();
            $data['grupo'] = $this->grupo->getAll();
            $data_empresa = $this->miempresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('clientes/nuevo', array('data' => $data));
        }
    }

    /* Crea nuevo grupo de clientes */

    public function grupos() {
        if ($this->isAuth()) {
            /* Guardar un grupo */
            if (array_key_exists('name_group', $_POST)) {
                
                // $isExist = $this->clientes->get_group_one($this->input->post('name_group'));
                //verificar si existe el nombre en el grupo
                $existe=$this->clientes->get_by_where_group(array('nombre'=>$this->input->post('name_group')));
               // print_r($existe); die("dfdfg");
                if($existe==0){                    
                    $this->clientes->add_group();                    
                     $this->output->set_content_type('application/json')->set_output(
                            json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 1))
                    );
                } else {
                    $this->output->set_content_type('application/json')->set_output(
                            json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 0))
                    );
                    //$this->session->set_flashdata('message', custom_lang('sima_client_group_created_message', 'Ya existe un grupo con este nombre'));
                    //redirect('clientes/grupos');                 
                }
            } else if (array_key_exists('delete_group', $_POST)) {

                $lista = $this->lista_precios->get_by_id($_POST['delete_group']);
                //verificar si existe el grupo
                $existe=$this->clientes->get_by_where_group(array('id'=>$_POST['delete_group']));
               
                if($existe!=0){
                    //verifica es  Sin grupo y no dejar eliminarlo
                    $nombregrupo=strtolower($existe[0]['nombre']);
                    if($nombregrupo != "sin grupo"){
                        if (empty($lista)) {
                            //$this->lista_detalle_precios->delete($lista['id']);//Eliminar detalle de la lista 
                            //$this->lista_precios->delete($lista['id']);//Eliminar lista
                            $this->clientes->assign_default_group($_POST['delete_group']); //Cambia grupo de cliente a default = 0
                            $this->clientes->delete_group($_POST['delete_group']);

                            $this->output->set_content_type('application/json')->set_output(
                                    json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 1))
                            );
                        } else {
                            $this->output->set_content_type('application/json')->set_output(
                                    json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 0))
                            );
                        }
                    }else{
                        $this->output->set_content_type('application/json')->set_output(
                            json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 3))
                        );
                    }
                        
                }else{
                    $this->output->set_content_type('application/json')->set_output(
                            json_encode(array("cliente" => $this->clientes->get_group_all(), "resp" => 2))
                    );
                }
                

                /* $this->clientes->assign_default_group($_POST['delete_group']);//Cambia grupo de cliente a default = 0
                  $this->clientes->delete_group($_POST['delete_group']);

                  $this->output->set_content_type('application/json')->set_output(
                  json_encode($this->clientes->get_group_all())
                  ); */
            } else {

                /* Vista index */
                $data = array();

                $data["grupo_clientes"] = $this->clientes->get_group_all();

                $data['pais'] = $this->pais_provincia->get_pais();

                $data["clientes"] = $this->clientes->get_clients_group_all(1);
                $data_empresa = $this->miempresa->get_data_empresa();
                $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
                $this->layout->template('member')
                        ->css(array(base_url('public/css/multiselect/multiselect.css'), base_url('public/css/loaders/loader.css')))
                        ->js(array(base_url("/public/js/plugins/Quicksearch/jquery.quicksearch.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))
                        ->show('clientes/grupos', $data);
            }
        }
    }

    public function asignar_grupo() {
        if ($_POST) {

            /* Asignar */
            $success = $this->clientes->assign_group($_POST['asignar']);
            /* Desasignar */
            $_POST['desasignar']['grupo'] = 0;
            $success = $this->clientes->assign_group($_POST['desasignar']);
            /* Traer clientes sin grupo */
            $data["clientes"] = $this->clientes->get_clients_group_all(0);

            $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done' => $success, 'data' => $data))
            );
        }
    }

    /* Crear nuevo grupo */
    /*    public function nuevo_grupo(){

      if($this->isAuth()){

      $data = array();

      if ( array_key_exists('group', $_GET) ){

      $data['clientes']=$this->clientes->consulta_walter($_GET['group']);

      }else{

      $data["grupo_clientes"] = $this->clientes->get_group_all();

      $data["clientes"] = $this->clientes->get_all(0);

      $data['pais'] = $this->pais_provincia->get_pais();

      }

      $this->layout->template('member')->js(base_url('public/js/plugins/multiselect/jquery.multi-select.min.js'))->show('clientes/nuevo_grupo', $data);

      }

      } */

    /* Trae clientes de un grupo */

    public function get_clients_group_all() {
        $this->output->set_content_type('application/json')->set_output(
                json_encode(
                        array(
                            'clientes_grupo' => $this->clientes->get_clients_group_all($_POST['group']),
                            'clientes_sin_grupo' => $this->clientes->get_clients_group_all(1)
                        )
                )
        );
    }

    public function get_clients_group_filter() {
        $filtro = $_GET['filter'];
        $this->output->set_content_type('application/json')->set_output(json_encode($this->clientes->get_clients_group_filter($filtro)));
    }

    public function get_clients_filter() {
        $filtro = $_GET['filter'];
        $this->output->set_content_type('application/json')->set_output(json_encode($this->clientes->get_clients_filter($filtro)));
    }

    /* Verifica logIN */

    public function isAuth() {
        /* Si NO esta autenticado redirecciona a auth */
        if ($this->ion_auth->logged_in())
            return true;
        else
            redirect('auth', 'refresh');
    }

    public function nif_check($str) {



        if ($this->clientes->nif_check($str)) {

            $this->form_validation->set_message('nif_check', 'EL %s existe');

            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function nif_check_with_empty($str) {
        if (strlen($str) > 0) {
            if ($this->clientes->nif_check($str)) {
                $this->form_validation->set_message('nif_check_with_empty', 'EL %s existe');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function detalles($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data = $this->clientes->get_by_id($id);

        $this->layout->template('member')->show('clientes/detalles', array('data' => $data));
    }

    public function editar($id) {



        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }



        if ($this->form_validation->run('clientes') == true) {

            $this->clientes->update();

            $this->session->set_flashdata('message', custom_lang('sima_client_updated_message', 'Cliente actualizado correctamente'));

            redirect("clientes/index");
        }

        $data = array();

        $data['data'] = $this->clientes->get_by_id($id);

        $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

        $data['pais'] = $this->clientes->get_pais();
        $data['grupo'] = $this->grupo->getAll();

        $this->layout->template('member')->show('clientes/editar', array('data' => $data));
    }

    public function eliminar($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $delet = $this->clientes->delete($id);
       //echo"<br>delet=".$delet; die();
       
       if(!empty($delet)){
        if ($delet == 'eliminado') {
            $delet='Se ha eliminado correctamente';
        } else {
            if($delet == 'facturas asociadas'){
                $delet='El cliente no puede ser eliminado porque tiene facturas';  
            }else{
                $delet='El cliente no puede ser eliminado porque tiene plan separes';                  
            }                  
        }
        $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', $delet));
    }

        redirect("clientes/index");
    }

    public function excel() {

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre del cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre comercial');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Pais');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Provincia');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Razón Social');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Teléfono');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Nit');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', custom_lang('sima_nif',"Grupo") );
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Contacto');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Página Web');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Email');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Ciudad');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Dirección');
        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'Código Postal');
        $this->phpexcel->getActiveSheet()->setCellValue('O1', 'Movil');
        $this->phpexcel->getActiveSheet()->setCellValue('P1', 'Fax');
        $this->phpexcel->getActiveSheet()->setCellValue('Q1', 'Tipo de Empresa');
        $this->phpexcel->getActiveSheet()->setCellValue('R1', 'Entidad Bancaria');
        $this->phpexcel->getActiveSheet()->setCellValue('S1', 'Numero de Cuenta');
        $this->phpexcel->getActiveSheet()->setCellValue('T1', 'Observaciones');
        $this->phpexcel->getActiveSheet()->setCellValue('U1', 'Entidad Bancaria');
        $this->phpexcel->getActiveSheet()->setCellValue('V1', 'Numero Cuenta');
        $this->phpexcel->getActiveSheet()->setCellValue('W1', 'Fecha Nacimiento');
        $this->phpexcel->getActiveSheet()->setCellValue('X1', 'Genero');

        $query = $this->clientes->excel();
       
        $row = 1;

        $count = 0;
        foreach ($query as $cliente) {
            $value = $cliente[0];
            if ($count > 0) {

                $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($value["nombre_comercial"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($value["pais"], ENT_QUOTES, 'UTF-8'));                
                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($value["provincia"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($value["razon_social"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($value["telefono"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($value["nif_cif"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($value["grupo"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($value["contacto"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, html_entity_decode($value["pagina_web"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, html_entity_decode($value["email"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, html_entity_decode($value["poblacion"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, html_entity_decode($value["direccion"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, html_entity_decode($value["cp"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, html_entity_decode($value["movil"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, html_entity_decode($value["fax"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, html_entity_decode($value["tipo_empresa"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, html_entity_decode($value["entidad_bancaria"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, html_entity_decode($value["numero_cuenta"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, html_entity_decode($value["observaciones"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, html_entity_decode($value["entidad_bancaria"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, html_entity_decode($value["numero_cuenta"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, html_entity_decode($value["fecha_nacimiento"], ENT_QUOTES, 'UTF-8'));
                $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, html_entity_decode($value["genero"], ENT_QUOTES, 'UTF-8'));
            }


            $count++;
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

        $this->phpexcel->getActiveSheet()->getStyle('A1:X' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0'
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF'
                        )
                    )
                )
        );
        $this->phpexcel->getActiveSheet()->setTitle('Clientes');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="clientes.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function import_excel() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $this->layout->template('member');
        $data = array();

        $error_upload = "";
        $total = 0;
        $total_correctos = 0;
        $total_incorrectos = 0;
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'text/plain|text|csv|csv';
        $image_name = "";
        $this->load->library('upload', $config);
        //var_dump($this->upload);die;
        if (!empty($_FILES['archivo']['name'])) {
            if (!$this->upload->do_upload('archivo')) {
                $data["grupo_clientes"] = $this->clientes->get_group_all();
                $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla clientes"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla clientes</p>');
                $data['data']['upload_error'] = $error_upload;
                $this->layout->show('clientes/import_excel', array('data' => $data));
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $handle = fopen($_FILES['archivo']['tmp_name'], "r");
                $cicle = 0;
                // Nota que indicamos como estan los campos separados
                while (($linea = fgetcsv($handle, 1024, ";")) !== FALSE) {
                    $cicle ++;
                    if ($cicle == 1)
                        continue;

                    if ($linea[0] != '' || $linea[1] != '' || $linea[2] != '' || $linea[3] != '' || $linea[4] != '' || $linea[5] != '' || $linea[6] != '' || $linea[7] != '') {
                        if ($linea[0] != 'Nombre') {
                            if ($linea[0] != '' && $linea[5] != '') {
                                $array_datos = array();
                                $data = array(
                                    "nombre_comercial" => $linea[0],
                                    "razon_social" => $linea[1],
                                    "nif_cif" => $linea[2],
                                    "direccion" => $linea[3],
                                    "telefono" => $linea[4],
                                    "grupo_clientes_id" => $linea[5],
                                    "pais" => $linea[6],
                                    "provincia" => $linea[7],
                                    "email" => $linea[8],
                                );
                                $this->clientes->add_csv($data, $this->session->userdata('user_id'));
                                $total_correctos++;

                                //--------------------------------		 
                            }
                        }
                        $total++;
                    }
                }

                $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha importado " . $total . " cliente(s)"));
                @unlink("uploads/$image_name");
                redirect("clientes/index");
                //$this->layout->show('productos/import_complete',compact("total","total_correctos","total_incorrectos"));
            }
        } else {
            $data["grupo_clientes"] = $this->clientes->get_group_all();
            $data['data']['upload_error'] = $error_upload;
            $this->layout->show('clientes/import_excel', array('data' => $data));
        }
    }

    public function importar_excel_nuevo() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $error_upload = "";

        $carpeta = 'uploads/archivos_clientes/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        /* foreach (new DirectoryIterator("uploads/archivos_clientes") as $fileInfo) {
            if (!$fileInfo->isDir()) {
                unlink($fileInfo->getPathname());
            }
        } */
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $image_name = "";
        $this->load->library('upload', $config);
        $res_data = array();

        // Si se adjunto un archivo excel
        if (!empty($_FILES['archivo']['name'])) { //no olivdar subir el archivo mime en config
            if (!$this->upload->do_upload('archivo')) {
                $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla producto"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla producto</p>');
                $data['data']['upload_error'] = $error_upload;
                $this->layout->show('clientes/import_excel', array('data' => $data));
            } else {
                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel
                        ->getActiveSheet()
                        ->toArray(null, true, true, true);
                //var_dump($sheetData);
                $importar = $this->clientes->importarExcel($sheetData);
                echo json_encode($importar);
            }
        } else {

            $data['data']['grupos'] = $this->clientes->getGrupoClientes();
            $data['data']['upload_error'] = $error_upload;
            
            $this->layout->template('member2');
            $this->layout->show('clientes/import_excel_new', array('data' => $data));
        }
    }

    /* public function importar_excel_nuevo()
      {
      if (!$this->ion_auth->logged_in()) {
      redirect('auth', 'refresh');
      }
      $error_upload = "";


      $carpeta = 'uploads/archivos_clientes/';
      if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
      }
      foreach (new DirectoryIterator("uploads/archivos_clientes") as $fileInfo) {
      if (!$fileInfo->isDot()) {
      unlink($fileInfo->getPathname());
      }
      }
      $config['upload_path'] = $carpeta;
      $config['allowed_types'] = 'xlsx|xls';
      $prefijo = substr(md5(uniqid(rand())), 0, 8);
      $config['file_name'] = $prefijo . $this->session->userdata('user_id');
      $image_name = "";
      $this->load->library('upload', $config);
      $res_data = array();

      // Si se adjunto un archivo excel
      if (!empty($_FILES['archivo']['name'])) { //no olivdar subir el archivo mime en config
      if (!$this->upload->do_upload('archivo')) {
      $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla producto"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla producto</p>');
      $data['data']['upload_error'] = $error_upload;
      $this->layout->show('clientes/import_excel', array('data' => $data));
      }else
      {
      $this->load->library('phpexcel');
      $tname = $_FILES['archivo']['tmp_name'];
      $obj_excel = PHPExcel_IOFactory::load($tname);
      $sheetData = $obj_excel
      ->getActiveSheet()
      ->toArray(null, true, true, true);

      $tipoAccion = $this->input->get("accion");
      $errorFix = $this->input->post("errorFix");


      if( $this->input->get("validado") == "ok" ){
      //var_dump($tipoAccion);
      //var_dump($errorFix);
      $this->importExcelNewValidado($sheetData,$errorFix,$tipoAccion);
      }else{
      //echo "aaaaa";die;
      $this->importExcelNewValidar($sheetData);
      }
      }
      } else {

      $data['data']['grupos'] = $this->clientes->getGrupoClientes();
      $data['data']['upload_error'] = $error_upload;
      $this->layout->template('member2');
      $this->layout->show('clientes/import_excel_new', array('data' => $data));
      }
      } */

    public function importExcelNewValidado($sheetData, $errorFix, $tipoAccion) {

        $result = $this->clientes->importExcelNewImportar($sheetData, $errorFix, $tipoAccion);
        $this->output->set_content_type('application/json')->set_output(json_encode(Array("data" => $result)));
    }

    public function importExcelNewValidar($sheetData) {

        $result = $this->clientes->importExcelNewValidar($sheetData);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function creacionRapidaNewImportar() {
        $result = $this->clientes->agregarGrupo($_POST['nombre']);

        $response = array(
            "result" => $result
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function getClienteId() {
        if (isset($_POST['id']) && $_POST['id'] != "") {
            $cliente = $this->clientes->get_by_id($_POST['id']);
            echo json_encode(array("resp" => 1, "cliente" => $cliente));
        } else {
            echo json_encode(array("resp" => 0));
        }
    }

    public function timbrado_factura(){
        $id_factura = $this->input->post('factura');
    }

    public function add_client_curl(){
        $data = $this->input->post();
        //print_r(json_encode($data)); die();
        echo json_encode(post_curl('customers',json_encode($data),$this->session->userdata('token_api')));
    }

    public function edit_client_curl(){
        $data = $this->input->post();
        $endpoint =  "customers/".$data["id"];
        //dd(json_encode($data));
        echo json_encode(put_curl($endpoint,json_encode($data),$this->session->userdata('token_api')));
    }

    public function search_puntos_leal(){
        $data = $this->input->post();
        $response = get_curl("puntos_leal/search_user/".$data["id"],$this->session->userdata('token_api'));
        echo json_encode($response ? $response->data : null);
    }
}

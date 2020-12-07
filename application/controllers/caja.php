<?php
date_default_timezone_set("America/Lima");

class Caja extends CI_Controller {
    var $dbConnection;

    function __construct() {
        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("Caja_model",'Caja');
        $this->Caja->initialize($this->dbConnection);

        $this->load->model("miempresa_model",'miempresa');
        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("vendedores_model",'vendedores');
        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("pagos_model",'pagos');
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("clientes_model",'clientes');
        $this->clientes->initialize($this->dbConnection);

        $this->load->model("productos_model",'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("categorias_model",'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("impuestos_model",'impuestos');

        $this->impuestos->initialize($this->dbConnection);
        $this->load->model("pais_provincia_model",'pais_provincia');

        $this->load->model("facturas_model",'facturas');
        $this->facturas->initialize($this->dbConnection);

        $this->load->model("almacenes_model",'almacen');
        $this->almacen->initialize($this->dbConnection);

        $this->load->model("caja_model",'caja');
        $this->caja->initialize($this->dbConnection);

        $this->load->model("opciones_model",'opciones');
        $this->opciones->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
        $this->load->helper('cierre_caja');
        $this->load->model('crm_licencias_empresa_model');
        $this->load->model('crm_model');

        $this->load->model("usuarios_model", 'usuarios');
        $this->usuarios->initialize($this->dbConnection);

        $this->load->model('primeros_pasos_model');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        $this->load->model('mesas_secciones_model','mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);

        //crear campo arqueo en cierre_caja y consecutivo de cierre de caja
        $this->caja->add_campo_arqueo();
        $this->almacenes->actualizar_tabla_almacen_cierre_caja();
        $this->caja->add_campo_consecutivo_cierre_caja();
    }

    public function index($offset = 0)
	{
        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }

        if ($this->session->userdata('is_admin') == "t") {
            $this->layout->template('member')->show('caja/index');
        } else {
            redirect(site_url('frontend/index'));
        }
	}

	public function listado_cierres()
	{
        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }

        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->js(base_url("/public/fancybox/jquery.fancybox.js"))->show('caja/listado_cierres',array("data" => $data));
	}

    public function listado_cierres_productos($id_cierre)
    {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('caja/listado_cierres_productos',compact("id_cierre"));
    }

    public function categorias_cierres($id)
    {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        
        $this->layout->template('member')->show('caja/categorias_cierres',compact("id"));
    }

    public function productos_cierres($id)
    {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        
        $this->layout->template('member')->show('caja/productos_cierres',compact("id"));
    }

	public function movimientos_cierres($id)
	{

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }
        
        $this->layout->template('member')->show('caja/movimientos_cierres',compact("id"));
	}

	public function cerrar_caja(){
            if (!$this->ion_auth->logged_in()) { redirect('auth', 'refresh'); }

            if ($this->input->post('total'))
            {
                //Validamos si es restaurante y tiene mesas abiertas
                $opciones =  $this->miempresa->get_permisos_description();

                $tipo_negocio = NULL;
                $cierre_caja_mesas_abiertas = NULL;
                $flag_cerrar_caja = true;

                foreach($opciones as $opcion):
                    if($opcion["nombre_opcion"] == "cierre_caja_mesas_abiertas")
                        $cierre_caja_mesas_abiertas = $opcion["valor_opcion"];

                    if($opcion["nombre_opcion"] == "tipo_negocio")
                        $tipo_negocio = $opcion["valor_opcion"];
                endforeach;

                if($tipo_negocio == 'restaurante' && $cierre_caja_mesas_abiertas == "no"){
                    $mesas_abiertas = $this->mesas_secciones->get_mesas_abiertas();
                    if($mesas_abiertas == false)  $flag_cerrar_caja = false;
                }

                if($flag_cerrar_caja){
                    $data = array(
                        'total_egresos' => $this->input->post('egresos')
                        ,'total_ingresos' => $this->input->post('ingresos')
                        ,'total_cierre' => $this->input->post('total')
                        ,'hora_cierre' => date('H:i:s')
                        ,'fecha_fin_cierre' => date('Y-m-d H:i:s')
                    );

                    $this->Caja->cerrar_caja_final($data);
                    $this->session->unset_userdata('caja');

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Se ha cerrado correctamente'));
                    redirect('caja/listado_cierres');
                }else{
                    $this->session->set_flashdata('error', custom_lang('sima_category_created_message', 'No es posible cerrar caja, verifique que no tenga mesas abiertas y que el usuario tenga almacen asociado'));
                    redirect('caja/listado_cierres');
                }
            }

            $data['almacen'] = $this->almacen->get_all('0');
            $data['caja'] = $this->Caja->get_all('0');

            $this->layout->template('member')->show('caja/cierre',
                array('data' => $this->Caja->cierre_caja(), 'data1' => $data,'data2'=>$this->Caja->obtenerDevolucionesPendientes($this->session->userdata('caja')))
            );
            //var_dump($this->Caja->cierre_caja());

	}

	public function nuevo(){

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(

                        'nombre' => $this->input->post('nombre')

                        ,'id_Almacen' => $this->input->post('almacen')

                    );

                    $this->Caja->add($data);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Caja se ha creado correctamente'));

                    redirect('caja/index');

            }


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
        $data['definecrear']=$deficrear;

	            $data['almacen'] = $this->almacen->get_all('0',true);
                $this->layout->template('member')->show('caja/nuevo', array('data1' => $data));

	}

	public function editar($id){

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		if ($this->input->post('nombre')){

                    $data = array(

                        'nombre' => $this->input->post('nombre')

                        ,'id_Almacen' => $this->input->post('almacen')

                    );

                    $this->Caja->editar($data, $id);

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Caja se ha editado correctamente'));

                    redirect('caja/index');

            }
                $data = array();
	            $data1['almacen'] = $this->almacen->get_all('0',true);
				$data['data']  = $this->Caja->get_by_id($id);
                $this->layout->template('member')->show('caja/editar', array('data1' => $data1, 'data' => $data));

	}

	public function get_ajax_data(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data()));

    }

	public function get_ajax_data_listado_cierre(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_listado_cierre()));

    }

    public function get_ajax_data_listado_cierre_productos($id_cierre){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_listado_cierre_productos($id_cierre)));

    }

	public function get_ajax_data_movimientos_cierre($id){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->Caja->get_ajax_data_movimientos_cierre($id)));

    }

    public function imprimir_cierre_productos ($id, $fecha, $hora_apertura, $hora_cierre) {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        //busco fechas y horas del cierre
        $caja_1 = $this->Caja->get_listado_cierre($id);

        $empresa = $this->miempresa->get_data_empresa($caja_1[0]['id_Almacen']);
        $data = ['data_empresa' =>  $empresa];

        //verificar si tengo opcion de cerrar caja automatico o no
        $cierre_automatico=get_option("cierre_automatico");
        $hora_cierre=$caja_1[0]['hora_cierre'];
        $hora_apertura=$caja_1[0]['hora_apertura'];
        $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
        if($caja_1[0]['hora_cierre']=="00:00:00"){
            if($cierre_automatico==0){ //no tengo cierre automatico
                $fecha_fin=date("Y-m-d H:i:s");
                $hora_cierre= date("H:i:s");
            }else{
                $fecha_fin=$caja_1[0]['fecha']." 23:59:59";
                $hora_cierre= date("23:59:59");
            }

        }else{
            $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
        }

        if(!empty($caja_1[0]['fecha_fin_cierre'])){

            $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
        }

        /*
        $data_empresa = $this->miempresa->get_data_empresa();

        $data = array (
                "cierres_productos" => $this->Caja->get_ajax_data_cierre_productos($id_cierre, $fecha, $hora_apertura, $hora_cierre)
                ,'data_empresa' =>  $data_empresa
            );

        $this->layout->template('ajax')->show('caja/imprimir_cierre_productos', array("data" => $data));*/

        $detalle_movimientos =array(
            'obtener_movimientos_validos_productos' => $this->Caja->get_ajax_data_cierre_productos($id, $fecha_inicio, $fecha_fin, $hora_apertura, $hora_cierre),
            'obtener_movimientos_validos' => $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_impuestos_validos' => $this->Caja->obtener_impuestos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_movimientos_devoluciones' => $this->Caja->obtenerDevolucionesCierreCaja($id),
            'obtener_movimientos_devoluciones_pendientes' => $this->Caja->obtenerDevolucionesPendientes( $id ),
            'obtener_movimientos_anulados' => $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']),
            //'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id),
            'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id,$fecha_inicio,$fecha_fin),
            //'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin),
            'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']),
            'cierres_salidas' => $this->Caja->cierre_caja_gastos($id),
            'cierre' => $this->Caja->get_movimientos_cierre_entradas_apertura($id),
            'rangoFacturas' => $this->Caja->get_facturas_ultpri($id),
            'tipo_negocio' => $empresa['data']['tipo_negocio']
        );

        $datos_caja = array();
        foreach ($caja_1 as $caja) {
            $inicio = $fecha_inicio;
            $fecha_apertura = date("Y-m-d", strtotime($inicio));
            $fin = $fecha_fin;
            $fecha_cierre = date("Y-m-d", strtotime($fin));

            //$datos_caja['fecha'] = $caja['fecha'];
            $datos_caja['fecha_inicio'] = $fecha_apertura;
            $datos_caja['fecha_fin'] = $fecha_cierre;
            $datos_caja['hora_apertura'] = $hora_apertura;
            $datos_caja['hora_cierre'] = $hora_cierre;
            $datos_caja['username'] = $caja['username'];
            $datos_caja['nombre_caja'] = $caja['nombre_caja'];
            $datos_caja['almacen'] = $caja['almacen'];
            $datos_caja['total_cierre'] =  $caja['total_cierre'];
            $datos_caja['total_egresos'] = $caja['total_egresos'];
            $datos_caja['total_ingresos'] =  $caja['total_ingresos'];
            $datos_caja['id'] = $caja['id'];
            $datos_caja['arqueo'] = $caja['arqueo'];
            $datos_caja['consecutivo'] = $caja['consecutivo'];
        }

        $html = generar_html_cierre_caja_tirilla($empresa,$datos_caja,$detalle_movimientos,false,true,false,true);

        require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(200, 0), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");

        $this->layout->template('ajax')->show('caja/imprime_cierre_de_caja', array('html'=>$html));
    }

    public function imprimir_cierre_categorias ($id_cierre, $fecha, $hora_apertura, $hora_cierre) {
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        $data_empresa = $this->miempresa->get_data_empresa();

        $data = array (
                "cierres_productos" => $this->Caja->get_ajax_data_cierre_categorias($id_cierre, $fecha, $hora_apertura, $hora_cierre)
                ,'data_empresa' =>  $data_empresa
            );

        $this->layout->template('ajax')->show('caja/imprimir_cierre_categorias', array("data" => $data));
    }

   public function re_apertura($id=NULL){

    $this->session->set_userdata('caja', $id);
    redirect("caja/cerrar_caja/");
  }

   //apertura

    public function apertura($id = NULL) {

        $section = $this->session->userdata('page_backup');
        if($id=='credito'){
            $section='credito';
        }

        //echo $section; die();
        $back = $this->input->get('back');
        $http = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : site_url("frontend/index");

        //$almacenActual = $this->dashboardModel->getAlmacenActual();
        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);

        if(($puedofacturar==1)&&($this->session->userdata('db_config_id')!=2547)){
            $url= site_url("frontend");
            echo'
            <script>
                    alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");
                    window.location="../frontend/index";
            </script>';
        }

        /* var_dump($this->db->get('venta'));  */
        date_default_timezone_set("America/Lima");
        //actualizacion segun incidencia #814
        //echo $section;
        //print_r($this->session->userdata('caja'));
        //die();
        if ($this->session->userdata('caja') != ""){

            switch($section){
                case NULL : redirect("ventas/nuevo/"); break;
                case 'tomar-pedido' : redirect("tomaPedidos"); break;
                case 'quick-service' : redirect("orden_compra/mi_orden/-1/".strtotime("now")); break;
                default: redirect("ventas/nuevo/");
            }
        }

        $data_empresa = $this->miempresa->get_data_empresa();
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = $this->session->userdata('user_id');


        $user = $this->dbConnection->query("SELECT ualma.id_Caja, a.id
                                            FROM usuario_almacen AS ualma
                                            INNER JOIN almacen AS a ON  a.id=ualma.almacen_id
                                            WHERE usuario_id = '" . $id_user . "' limit 1")->result();

        foreach ($user as $dat) {
            $id_Caja = $dat->id_Caja;
            $id_almacen = $dat->id;
        }

        //verifico si ingrese directo a la apertura y no existe la variable en session
        //verifico si hay una caja abierta para el usuario
        //verifico si hay cierre automatico
        if ($data_empresa['data']['valor_caja'] == 'si') {
            // Si el cierre de caja es automatico
            if ($data_empresa['data']['cierre_automatico'] == '1') {
                $hoy = date("Y-m-d");
                $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
            }else{
                $where=array('id_Usuario'=>$this->session->userdata('user_id'));
            }

            $orderby_cierre="fecha desc, hora_apertura desc";
            $limit_cierre="1";
            $cierre_caja=$this->Caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);
            // print_r($cierre_caja); die();
            if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){
                $this->session->set_userdata('caja', $cierre_caja->id);
                switch($section){
                    case NULL : redirect("ventas/nuevo/"); break;
                    case 'tomar-pedido' : redirect("tomaPedidos"); break;
                    case 'quick-service' : redirect("orden_compra/mi_orden/-1/".strtotime("now")); break;
                }
            }
        }

        if (isset($_POST['fecha'])) {
            $pagina=$id;

            //actualizacion segun incidencia #814
            if($this->input->post('valor')[0] <= 0){
                $this->session->set_flashdata('message', custom_lang('error_apertura_caja', "Debe ingresar un valor mayor a 0 para la apertura de caja"));
                redirect("caja/apertura/");
            }
            //verifico si uso consecutivo para el cierre de caja
            $datosalmacen=$this->almacen->get_almacenes(array('id'=>$_POST['almacen']));
            $activo_consecutivo_cierre=$datosalmacen[0]->activar_consecutivo_cierre_caja;
            if($activo_consecutivo_cierre=='si'){
                $consecutivo=$this->almacen->buscar_consecutivo_cierre_caja(array('id'=>$_POST['almacen']));
            }else{
                $consecutivo=NULL;
            }

            $data = array(
                'fecha' => $_POST['fecha'],
                'fecha_fin_cierre' => NULL,
                'hora_apertura' => date('H:i:s'),
                'hora_cierre' => '',
                'id_Usuario' => $id_user,
                'id_Caja' => $id_Caja,
                'id_Almacen' => $_POST['almacen'],
                'total_egresos' => '',
                'total_ingresos' => '',
                'total_cierre' => '',
                'consecutivo' => $consecutivo
            );

            if((isset($cierre_caja->id) && ($cierre_caja->total_cierre != ""))||(empty($cierre_caja))){
                $id = $this->Caja->apertura_cierre_caja($data);
                if(!empty($id)){
                    //guardar evento de primeros pasos Caja
                    $estadoBD = $this->newAcountModel->getUsuarioEstado();
                    if($estadoBD["estado"]==2){
                        $paso=11;
                        $marcada=$this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'),'db_config' => $this->session->userdata('db_config_id'),'id_paso'=>$paso));
                        if($marcada==0){
                            $datatarea=array(
                                'id_paso' => $paso,
                                'id_usuario' => $this->session->userdata('user_id'),
                                'db_config' => $this->session->userdata('db_config_id')
                            );
                            $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                        }
                    }

                    if ($this->input->post('foma_pago')) {
                        for ($contx = 0; $contx < count($this->input->post('foma_pago')); $contx++) {

                            $array_datos = array(
                                "Id_cierre" => $id,
                                "hora_movimiento" => date('H:i:s'),
                                "id_usuario" => $id_user,
                                "tipo_movimiento" => 'entrada_apertura',
                                "valor" => $this->input->post('valor')[$contx],
                                "forma_pago" => $this->input->post('foma_pago')[$contx],
                                "numero" => '',
                                "id_mov_tip" => 0,
                                "tabla_mov" => 'apertura',
                            );
                            $idm=$this->Caja->movimiento_cierre_caja($array_datos);
                            if(empty($idm)){
                                $idm=$this->Caja->movimiento_cierre_caja($array_datos);
                            }
                        }
                    }
                    if( $this->input->post('back') ){
                        redirect( $this->input->post('url') );
                    }
                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha iniciado correctamente la apertura"));
                    switch($section){
                        case NULL : redirect("ventas/nuevo/"); break;
                        case 'tomar-pedido' : redirect("tomaPedidos"); break;
                        case 'quick-service' : redirect("orden_compra/mi_orden/-1/".strtotime("now")); break;
                        case 'credito' : return $this->output->set_content_type('application/json')->set_output(json_encode(array("response" =>"1"))); break;
                    }
                }else{
                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Hubo un error al abrir la caja, por favor intentar nuevamente"));
                    redirect("caja/apertura/");
                }
            }else{
                $this->session->set_userdata('caja', $cierre_caja->id);
                switch($section){
                    case NULL : redirect("ventas/nuevo/"); break;
                    case 'tomar-pedido' : redirect("tomaPedidos"); break;
                    case 'quick-service' : redirect("orden_compra/mi_orden/-1/".strtotime("now")); break;
                    case 'credito' : return $this->output->set_content_type('application/json')->set_output(json_encode(array("response" =>"1"))); break;
                }
            }
        }
        $data = array();

        //$data['almacen'] = $this->almacen->get_all('0');
        if(isset($id_almacen)){
            $data['almacen'] = $this->almacen->get_by_id($id_almacen);
        }

        $data['forma_pago'] = $this->db->query("SELECT mostrar_opcion, valor_opcion FROM opciones  where nombre_opcion = 'tipo_pago' order by id_opcion asc")->result();
        $data['tipo_negocio'] =$this->opciones->getOpcion('tipo_negocio');
        $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['section'] = $section;
        //print_r($data['section']); die();
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];
        $this->layout->template('ventas')
            ->css(array(base_url("/public/css/stylesheets.css"), base_url('public/css/multiselect/multiselect.css')))
            ->show('caja/apertura', array('data1'=>$data,'data'=>$data,'id'=>$id,'back'=>$back,'url'=>$http));
    }

    public function imprimir_cierre_caja($id=null){

        $empresa = $this->miempresa->get_data_empresa();

        $data = array(
            'data_empresa' =>  $empresa
        );

        $caja_1 = $this->Caja->get_listado_cierre($id);
        $caja_2 = $this->Caja->get_movimientos_cierre_entradas_ventas($id);
        $caja_4 = $this->Caja->get_movimientos_cierre_salidas($id);
        $caja_5 = $this->Caja->get_movimientos_all($id);
        $caja_8 = $this->Caja->get_movimientos_impuestos($id);
        $caja_6 = $this->Caja->get_movimientos_cierre_salidas_si_no($id);
        $caja_7 = $this->Caja->get_facturas_ultpri($id);
        $caja_8 = $this->Caja->get_movimientos_cierre_entradas_apertura($id);
        $caja_9 = $this->Caja->get_movimientos_plan_separe($id);
        $cierres_salidas = $this->Caja->cierre_caja($id);
        $base = $this->Caja->base_iva($id, 'base');
        $iva = $this->Caja->base_iva($id, 'iva');

        foreach ($caja_1 as $value2) {

            $fecha = $value2['fecha'];
            $hora_apertura = $value2['hora_apertura'];
            $hora_cierre = $value2['hora_cierre'];
            $username = $value2['username'];
            $nombre_caja = $value2['nombre_caja'];
            $almacen = $value2['almacen'];
            $total_cierre =  $value2['total_cierre'];
            $total_egresos = $value2['total_egresos'];
            $total_ingresos =  $value2['total_ingresos'];
            $id = $value2['id'];
	}

        require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");

	if($empresa["data"]['nombre'] == 'ALMACEN LA TUERCA'){
            $resolucion = ' <tr><td align="center"><b>Res DIAN No 10000055307 2015/06/05<br>
            desde No 1 al 500000 factura POS Vendty.com</b></td> </tr>';
	}


$html = '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
    <tr>
        <td align="center"><b>'.$empresa["data"]['nombre'].'</b></td>
    </tr>
    '.$resolucion.'
    <tr>
        <td align="center"><b>Cierre de Caja No. '.$id.'</b></td>
    </tr>
    <tr>
        <td align="center">Fecha: <b>'.$fecha.'</b> &nbsp;&nbsp;&nbsp; Hora de Apertura: <b>'.$hora_apertura.'</b> - Hora de Cierre: <b>'.$hora_cierre.'</b></td>
    </tr>
	<tr>
        <td align="center">Usuario: <b>'.$username.'</b> &nbsp;&nbsp;&nbsp; Caja: <b>'.$nombre_caja.'</b> &nbsp;&nbsp;&nbsp; Almacen: <b>'.$almacen.'</b>  </td>
    </tr>

    <tr>
        <td align="center">'.$caja_7.'</td>
    </tr>
    <tr>
        <td align="center"></td>
    </tr>
</table>';
$html .= '<hr>';

$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
							<th align="left"><b>Cantidad</b></th>
                            <th align="left"><b>Forma de pago</b></th>
							 <th align="right"><b>Valor</b></th>
                        </tr>
';
/*
if($caja_6 == 'si'){
 foreach ($caja_2 as $value){
   foreach ($caja_4 as $value1){
 			$formpago2=str_replace("_"," ",$value["forma_pago"]);
			$formpago2=ucfirst($formpago2);

 			$formpago1=str_replace("_"," ",$value1["forma_pago"]);
			$formpago1=ucfirst($formpago1);

 if($formpago1 == $formpago2){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"] + $value1["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'] - $value1['total_ingresos'])).'
                            </td>
                            <td align="right">
                                '.$formpago1.'
							</td>
                        </tr>
         ';
 if($formpago1 == 'Efectivo' ){ $cambio = 1; }else{ $cambio = 'Efectivo'; }
 if($formpago1 == 'Tarjeta debito' ){ $cambio1 = 1; }else{ $cambio1 = 'Tarjeta debito'; }
 if($formpago1 == 'Credito' ){ $cambio2 = 1; }else{ $cambio2 = 'Credito'; }
 if($formpago1 == 'Saldo a Favor' ){ $cambio3 = 1; }else{ $cambio3 = 'Saldo a Favor'; }
 if($formpago1 == 'Tarjeta credito' ){ $cambio4 = 1; }else{ $cambio4 = 'Tarjeta credito'; }


}



 }

if($formpago2 == $cambio ){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>
                            <td align="right">
                                '.$formpago2.'
							</td>
                        </tr>
         ';
}
if($formpago2 == $cambio1 ){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         ';
}
if($formpago2 == $cambio2 ){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         ';
}
if($formpago2 == $cambio3 ){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         ';
}
if($formpago2 == $cambio4 ){
         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="right">
                               $ '.number_format(($value['total_ingresos'])).'
                            </td>
                            <td align="right">
                               '.$formpago2.'
							</td>
                        </tr>
         ';
}


}

}
else{
*/
   foreach ($caja_2 as $value){
 			$formpago2=str_replace("_"," ",$value["forma_pago"]);
			$formpago2=ucfirst($formpago2);


         $html .= '
                        <tr>
                            <td align="left">
                                '.($value["cantidad_ingresos"]).'
                            </td>
                            <td align="left">
                                '.$formpago2.'
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format(($value['total_ingresos'])).'
                            </td>
                        </tr>
            ';
	}
//}
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                               Base
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($base).'
                            </td>
                        </tr>
            ';

  foreach ($iva as $value){
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                            '.$value->imp.'
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($value->impuestos).'
                            </td>
                        </tr>
            ';
  }
/*
 if($empresa["data"]['sobrecosto'] == 'si'){
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                            IAC
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($iva).'
                            </td>
                        </tr>
            ';
  }
  else{
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                            IVA
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($iva).'
                            </td>
                        </tr>
            ';
   }
*/
     $total_apertura = 0;
   foreach ($caja_8 as $value){
        $total_apertura +=  $value['total_ingresos'];
    }

     $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                                Total de apertura
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format(($total_apertura)).'
                            </td>
                        </tr>
            ';


 foreach ($cierres_salidas['pago_gastos'] as $value1){
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                                Total gastos
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($value1->total).'
                            </td>
                        </tr>
            ';
}
 foreach ($cierres_salidas['pago_recibidos'] as $value1){
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                                Total de pagos a creditos
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.number_format($value1->total).'
                            </td>
                        </tr>
            ';
}
foreach ($cierres_salidas['pago_proveedores'] as $value1){
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                               Total de pagos a proveedores
							</td>
							<td align="right">'.
                                $empresa['data']['simbolo'].' '.number_format($value1->total).'
                            </td>
                        </tr>
            ';
}

         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                              Subtotal de Ingresos
							</td>
							<td align="right">'.
                                $empresa['data']['simbolo'].' '.$total_ingresos.'
                            </td>
                        </tr>
            ';
         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                              Subtotal de Egresos
							</td>
							<td align="right">'.
                                $empresa['data']['simbolo'].' '.$total_egresos.'
                            </td>
                        </tr>
            ';

         $html .= '
                        <tr>
                            <td align="left">

                            </td>
                            <td align="left">
                              Total del cierre
							</td>
							<td align="right">
                               '.$empresa['data']['simbolo'].' '.$total_cierre.'
                            </td>
                        </tr>
            ';

$html .= '	</table>';
$html .= '<hr>';
$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
							<th align="right" width="58%"></th>
							<th align="left" width="20%" ><b>Impuesto</b></th>
                            <th align="right" width="22%"><b>Valor</b></th>
                        </tr>
';
$total_descuento = 0; $total_impuesto = 0;  $total_valor = 0;
 foreach ($caja_8 as $value){
         $html .= '
                        <tr>
						<td align="right"></td>
                            <td align="left">'.$value["impuesto"].' </td>
							<td align="right">'.$empresa['data']['simbolo'].' '.number_format($value["total_precio_venta"]).' </td>
                        </tr>
         ';
}
$html .= '	</table>';
$html .= '<hr>';
$html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
							<th align="left" width="50px"><b>Numero</b></th>
                            <th align="left" width="50px"><b>Hora</b></th>
							<th align="left" width="140px;"><b>Usuario</b></th>
                            <th align="left"><b>Forma de pago</b></th>
							<th align="left"><b>Descuentos</b></th>
							<th align="left"><b>Impuestos</b></th>
							<th align="right"><b>Valor</b></th>
                        </tr>
';

    $total_descuento = 0; $total_impuesto = 0;

    $total_valor = 0;

    foreach ($caja_5 as $value){
         $html .= '
                        <tr>
                            <td align="left">'.$value["numero"].' '.($value['anulada'] ? '(a)' : '').' </td>
                            <td align="left">'.$value["hora_movimiento"].' </td>
                            <td align="left">'.$value["username"].' </td>
                            <td align="left">'.$value["forma_pago"].' </td>
                            <td align="left">'.$empresa['data']['simbolo'].' '.$value["total_descuento"].' </td>
                            <td align="left">'.$empresa['data']['simbolo'].' '.$value["impuesto"].' </td>
                            <td align="right">'.$empresa['data']['simbolo'].' '.number_format($value["valor"]).' </td>
                        </tr>
         ';

        $total_descuento += $value["total_descuento"]; $total_impuesto += $value["impuesto"];


        if( $value["forma_pago"] == "Gift Card" ){
            //no se suma
        }else if( $value["forma_pago"] == "Saldo a Favor" ){
            //no se suma
        }else{
            $total_valor += $value["valor"];
        }



    }
    foreach ($caja_9 as $value){
         $html .= '
                        <tr>
                            <td align="left">Plan separe </td>
							<td align="left">'.$value["hora_movimiento"].' </td>
							<td align="left">'.$value["username"].' </td>
							<td align="left">'.$value["forma_pago"].' </td>
							<td align="left">'.$empresa['data']['simbolo'].' '.$value["total_descuento"].' </td>
							<td align="left"></td>
							<td align="right">'.$empresa['data']['simbolo'].' '.number_format($value["valor"]).' </td>
                        </tr>
         ';
        $total_descuento += $value["total_descuento"]; $total_impuesto += $value["impuesto"];

        if( $value["forma_pago"] == "Gift Card" ){
            //no se suma
        }else if( $value["forma_pago"] == "Saldo a Favor" ){
            //no se suma
        }else{
            $total_valor += $value["valor"];
        }

    }



$html .= '	</table>';
$html .= '<hr>';
         $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <td align="left"> </td>
							<td align="left"></td>
							<td align="left"> </td>
							<td align="left">Totales</td>
							<td align="left">'.$empresa['data']['simbolo'].' '.number_format($total_descuento).' </td>
							<td align="left">'.$empresa['data']['simbolo'].' '.number_format($total_impuesto).' </td>
							<td align="right">'.$empresa['data']['simbolo'].' '.number_format($total_valor).' </td>
                        </tr>
         ';
$html .= '	</table>';
$html .= '<hr>';


      ob_clean();

      $pdf->writeHTML($html, true, false, true, false, '');

      $pdf->Output('cuadre de caja.pdf', 'I');



    }
    function imprimir_cierre_caja_nuevo2($id = null){

        //$obtener_movimientos_abonos = $this->Caja->obtenerAbonos($id);
        $res = $this->Caja->cierre_caja_gastos($id);
        pr( $res );


    }
    function imprimir_cierre_caja_nuevo($id = null)
    {

        $caja_1 = $this->Caja->get_listado_cierre($id);

        $empresa = $this->miempresa->get_data_empresa($caja_1[0]['id_Almacen']);
        $data = ['data_empresa' =>  $empresa];
        //verificar si tengo opcion de cerrar caja automatico o no
        $cierre_automatico=get_option("cierre_automatico");
        $hora_cierre=$caja_1[0]['hora_cierre'];
        $hora_apertura=$caja_1[0]['hora_apertura'];
        $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
        if($caja_1[0]['hora_cierre']=="00:00:00"){
            if($cierre_automatico==0){ //no tengo cierre automatico
                $fecha_fin=date("Y-m-d H:i:s");
                $hora_cierre= date("H:i:s");
            }else{
                $fecha_fin=$caja_1[0]['fecha']." 23:59:59";
                $hora_cierre= date("23:59:59");
            }

        }else{
            $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
        }

        if(!empty($caja_1[0]['fecha_fin_cierre'])){

            $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
        }

        $data_empresa = $this->miempresa->get_data_empresa();
        $detalle_movimientos =array(
            'obtener_movimientos_validos' => $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_impuestos_validos' => $this->Caja->obtener_impuestos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_movimientos_devoluciones' => $this->Caja->obtenerDevolucionesCierreCaja($id),
            'obtener_movimientos_devoluciones_pendientes' => $this->Caja->obtenerDevolucionesPendientes( $id ),

            'obtener_movimientos_anulados' => $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']),
            //'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id),
            'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id,$fecha_inicio,$fecha_fin),
            //'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin),
            'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']),
            'cierres_salidas' => $this->Caja->cierre_caja_gastos($id),
            'cierre' => $this->Caja->get_movimientos_cierre_entradas_apertura($id),
            'rangoFacturas' => $this->Caja->get_facturas_ultpri($id),
            'tipo_negocio' => $data_empresa['data']['tipo_negocio']
        );

        // print_r($detalle_movimientos['cierres_salidas']);
        // print_r($detalle_movimientos['obtener_movimientos_devoluciones_pendientes']);
        //die();
        $datos_caja = array();
        foreach ($caja_1 as $caja) {
            $inicio = $fecha_inicio;
            $fecha_apertura = date("Y-m-d", strtotime($inicio));
            $fin = $fecha_fin;
            $fecha_cierre = date("Y-m-d", strtotime($fin));

            $datos_caja = array(
                    'fecha_inicio' => $fecha_apertura,
                    'fecha_fin' => $fecha_cierre,
                    'hora_apertura' => $hora_apertura,
                    'hora_cierre' => $hora_cierre,
                    'username' => $caja['username'],
                    'nombre_caja' => $caja['nombre_caja'],
                    'almacen' => $caja['almacen'],
                    'total_cierre' =>  $caja['total_cierre'],
                    'total_egresos' => $caja['total_egresos'],
                    'total_ingresos' =>  $caja['total_ingresos'],
                    'id' => $caja['id'],
                    'arqueo' => $caja['arqueo'],
                    'consecutivo' => $caja['consecutivo'],
                );
        }

        $html = generar_html_cierre_caja($empresa,$datos_caja,$detalle_movimientos);


       /* $obtener_movimientos_validos = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
        $obtener_movimientos_devoluciones = $this->Caja->obtenerDevolucionesCierreCaja($id);
        $obtener_movimientos_devoluciones_pendientes = $this->Caja->obtenerDevolucionesPendientes( $id );
        $obtener_movimientos_anulados = $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']);
        $obtener_movimientos_abonos = $this->Caja->obtenerAbonos($id);
        $formas_pago_validas = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']);
        $cierres_salidas = $this->Caja->cierre_caja_gastos($id);
        $cierre = $this->Caja->get_movimientos_cierre_entradas_apertura($id);

        $rangoFacturas = $this->Caja->get_facturas_ultpri($id);

        foreach ($caja_1 as $caja) {
            $fecha = $caja['fecha'];
            $hora_apertura = $caja['hora_apertura'];
            $hora_cierre = $caja['hora_cierre'];
            $username = $caja['username'];
            $nombre_caja = $caja['nombre_caja'];
            $almacen = $caja['almacen'];
            $total_cierre =  $caja['total_cierre'];
            $total_egresos = $caja['total_egresos'];
            $total_ingresos =  $caja['total_ingresos'];
            $id = $caja['id'];
        }*/


        require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(200, 0), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");


        $resolucion = '';
        ob_clean();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('cuadre de caja.pdf', 'I');
    }
    function imprimir_cierre_caja_nuevo_tirilla($id = null)
    {
        $caja_1 = $this->Caja->get_listado_cierre($id);
        $empresa = $this->miempresa->get_data_empresa($caja_1[0]['id_Almacen']);
        $data = ['data_empresa' =>  $empresa];

        //verificar si tengo opcion de cerrar caja automatico o no
        $cierre_automatico=get_option("cierre_automatico");
        $hora_cierre=$caja_1[0]['hora_cierre'];
        $hora_apertura=$caja_1[0]['hora_apertura'];
        $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
        if($caja_1[0]['hora_cierre']=="00:00:00"){
            if($cierre_automatico==0){ //no tengo cierre automatico
                $fecha_fin=date("Y-m-d H:i:s");
                $hora_cierre= date("H:i:s");
            }else{
                $fecha_fin=$caja_1[0]['fecha']." 23:59:59";
                $hora_cierre= date("23:59:59");
            }

        }else{
            $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
        }


        if(!empty($caja_1[0]['fecha_fin_cierre'])){

            $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
        }

        /*$obtener_movimientos_validos = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
        $obtener_movimientos_devoluciones = $this->Caja->obtenerDevolucionesCierreCaja($id);
        $obtener_movimientos_devoluciones_pendientes = $this->Caja->obtenerDevolucionesPendientes( $id );
        $obtener_movimientos_anulados = $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']);
        $obtener_movimientos_abonos = $this->Caja->obtenerAbonos($id,$caja_1[0]['id_Almacen']);
        $formas_pago_validas = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']);
        $cierres_salidas = $this->Caja->cierre_caja_gastos($id);
        $cierre = $this->Caja->get_movimientos_cierre_entradas_apertura($id);
        $rangoFacturas = $this->Caja->get_facturas_ultpri($id);*/

        $detalle_movimientos =array(
            'obtener_movimientos_validos' => $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_impuestos_validos' => $this->Caja->obtener_impuestos_validos($id, 'validos',$caja_1[0]['id_Almacen']),
            'obtener_movimientos_devoluciones' => $this->Caja->obtenerDevolucionesCierreCaja($id),
            'obtener_movimientos_devoluciones_pendientes' => $this->Caja->obtenerDevolucionesPendientes( $id ),
            'obtener_movimientos_anulados' => $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']),
            //'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id),
            'obtener_movimientos_abonos' => $this->Caja->obtenerAbonos($id,$fecha_inicio,$fecha_fin),
            //'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin),
            'formas_pago_validas' => $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']),
            'cierres_salidas' => $this->Caja->cierre_caja_gastos($id),
            'cierre' => $this->Caja->get_movimientos_cierre_entradas_apertura($id),
            'rangoFacturas' => $this->Caja->get_facturas_ultpri($id),
            'tipo_negocio' => $empresa['data']['tipo_negocio']
        );



        $datos_caja = array();
        foreach ($caja_1 as $caja) {
            $inicio = $fecha_inicio;
            $fecha_apertura = date("Y-m-d", strtotime($inicio));
            $fin = $fecha_fin;
            $fecha_cierre = date("Y-m-d", strtotime($fin));

            //$datos_caja['fecha'] = $caja['fecha'];
            $datos_caja['fecha_inicio'] = $fecha_apertura;
            $datos_caja['fecha_fin'] = $fecha_cierre;
            $datos_caja['hora_apertura'] = $hora_apertura;
            $datos_caja['hora_cierre'] = $hora_cierre;
            $datos_caja['username'] = $caja['username'];
            $datos_caja['nombre_caja'] = $caja['nombre_caja'];
            $datos_caja['almacen'] = $caja['almacen'];
            $datos_caja['total_cierre'] =  $caja['total_cierre'];
            $datos_caja['total_egresos'] = $caja['total_egresos'];
            $datos_caja['total_ingresos'] =  $caja['total_ingresos'];
            $datos_caja['id'] = $caja['id'];
            $datos_caja['arqueo'] = $caja['arqueo'];
            $datos_caja['consecutivo'] = $caja['consecutivo'];
        }

        //$html = generar_html_cierre_caja($empresa,$datos_caja,$detalle_movimientos,false,true);
        $html = generar_html_cierre_caja_tirilla($empresa,$datos_caja,$detalle_movimientos,false,true);


        require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(200, 0), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");
        $this->layout->template('ajax')->show('caja/imprime_cierre_de_caja', array('html'=>$html));

//        ob_clean();
//        $pdf->writeHTML($html, true, false, true, false, '');
//        $pdf->Output('cuadre de caja.pdf', 'I');
    }
    //Metodo para cierre de caja rapido
    public function quickCerrarCaja(){

        if ($this->session->userdata('caja') == "") {
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                    $id_user = $dat->id;
            }
            $hoy = date("Y-m-d");
            $query = $this->dbConnection->query('SELECT * FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ');
            $query->num_rows();

            if ($query->num_rows() == 1) {
                    $cierre_caja = 0;
                    $cierre = 'SELECT id FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ';
                    $cierre_caja = $this->dbConnection->query($cierre)->row();
                    $this->session->set_userdata('caja', $cierre_caja->id);
            }

            if ($query->num_rows() == 0) {
                redirect(site_url("caja/apertura"));
            }
        }
        $id = $this->session->userdata('caja');
        $empresa = $this->miempresa->get_data_empresa();
        $caja_1 = $this->Caja->get_listado_cierre($id);

        if ($this->input->post('total'))
        {
            $opciones =  $this->miempresa->get_permisos_description();
            $permisos=$this->session->userdata('permisos');
            $is_admin=$this->session->userdata('is_admin');
            $opciones =  $this->miempresa->get_permisos_description();
            $url='frontend/index';

            if(in_array('11', $permisos)){
                $url='ventas/nuevo';
            }

            if(in_array('10', $permisos)){
                $url='ventas/index';
            }

            if($is_admin == 't'){
                $url='ventas/index';
            }


                 $tipo_negocio = NULL;
                 $cierre_caja_mesas_abiertas = NULL;
                 $flag_cerrar_caja = true;

                 foreach($opciones as $opcion):
                     if($opcion["nombre_opcion"] == "cierre_caja_mesas_abiertas")
                         $cierre_caja_mesas_abiertas = $opcion["valor_opcion"];

                     if($opcion["nombre_opcion"] == "tipo_negocio")
                         $tipo_negocio = $opcion["valor_opcion"];
                 endforeach;

                 if($tipo_negocio == 'restaurante' && $cierre_caja_mesas_abiertas == "no"){
                     $mesas_abiertas = $this->mesas_secciones->get_mesas_abiertas();
                     if($mesas_abiertas == false)  $flag_cerrar_caja = false;
                 }

                 if($flag_cerrar_caja){
                    $data = array(
                        'total_egresos' => $this->input->post('egresos')
                        ,'total_ingresos' => $this->input->post('ingresos')
                        ,'total_cierre' => $this->input->post('total')
                        ,'hora_cierre' => date('H:i:s')
                        ,'fecha_fin_cierre' => date('Y-m-d H:i:s')
                        ,'arqueo' => $this->input->post('arqueo')
                    );

                    $this->Caja->cerrar_caja_final($data);
                    $this->session->unset_userdata('caja');
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Se ha cerrado correctamente'));
                    //redirect('caja/listado_cierres');
                    redirect("$url");
                }else{
                    $this->session->set_flashdata('error', custom_lang('sima_category_created_message', 'No es posible cerrar caja, verifique que no tenga mesas abiertas y que el usuario tenga almacen asociado'));
                    redirect("$url");
                }


        }
        //verificar si tengo opcion de cerrar caja automatico o no
        $cierre_automatico=get_option("cierre_automatico");
        $hora_cierre=$caja_1[0]['hora_cierre'];
        $hora_apertura=$caja_1[0]['hora_apertura'];
        $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
        if($caja_1[0]['hora_cierre']=="00:00:00"){
            if($cierre_automatico==0){ //no tengo cierre automatico
                $fecha_fin=date("Y-m-d H:i:s");
                $hora_cierre= date("H:i:s");
            }else{
                $fecha_fin=$caja_1[0]['fecha']." 23:59:59";
                $hora_cierre= date("23:59:59");
            }

        }else{
            $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
        }

        if(!empty($caja_1[0]['fecha_fin_cierre'])){

            $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
        }

        $datos_caja = array();
        foreach ($caja_1 as $caja) {
            $datos_caja['fecha_apertura'] = $fecha_inicio;
            $datos_caja['fecha_cierre'] = $fecha_fin;
            $datos_caja['hora_apertura'] = $hora_apertura;
            $datos_caja['hora_cierre'] = $hora_cierre;
            $datos_caja['username'] = $caja['username'];
            $datos_caja['nombre_caja'] = $caja['nombre_caja'];
            $datos_caja['almacen'] = $caja['almacen'];
            $datos_caja['total_cierre'] =  $caja['total_cierre'];
            $datos_caja['total_egresos'] = $caja['total_egresos'];
            $datos_caja['total_ingresos'] =  $caja['total_ingresos'];
            $datos_caja['id'] = $caja['id'];
        }



        $detalle_movimientos['obtener_movimientos_validos'] = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
        //$detalle_movimientos['formas_pago_validas'] = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin);
        $detalle_movimientos['formas_pago_validas'] = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']);
        $detalle_movimientos['obtener_movimientos_abonos'] = $this->Caja->obtenerAbonos($id);
        $detalle_movimientos['rangoFacturas'] = $this->Caja->get_facturas_ultpri($id);
        $detalle_movimientos['obtener_movimientos_devoluciones'] = $this->Caja->obtenerDevolucionesCierreCaja($id);
        $detalle_movimientos['cierre'] = $this->Caja->get_movimientos_cierre_entradas_apertura($id);
        $detalle_movimientos['cierres_salidas'] = $this->Caja->cierre_caja_gastos($id);
        //print_r($detalle_movimientos);
        //die();
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('caja/quick_cierre',array('empresa' => $empresa,'datos_caja' => $datos_caja,'detalle_movimientos' => $detalle_movimientos,"data" => $data));
    }

    public function cerrarCaja(){
            if (!$this->ion_auth->logged_in()) { redirect('auth', 'refresh'); }

            if ($this->input->post('total'))
            {
                 //Validamos si es restaurante y tiene mesas abiertas
                 $opciones =  $this->miempresa->get_permisos_description();

                 $tipo_negocio = NULL;
                 $cierre_caja_mesas_abiertas = NULL;
                 $flag_cerrar_caja = true;

                 foreach($opciones as $opcion):
                     if($opcion["nombre_opcion"] == "cierre_caja_mesas_abiertas")
                         $cierre_caja_mesas_abiertas = $opcion["valor_opcion"];

                     if($opcion["nombre_opcion"] == "tipo_negocio")
                         $tipo_negocio = $opcion["valor_opcion"];
                 endforeach;

                 if($tipo_negocio == 'restaurante' && $cierre_caja_mesas_abiertas == "no"){
                     $mesas_abiertas = $this->mesas_secciones->get_mesas_abiertas();
                     if($mesas_abiertas == false)  $flag_cerrar_caja = false;
                 }

                 if($this->input->post('egresos') === '0' && ($this->input->post('ingresos') === '0')){
                    $flag_cerrar_caja = true;
                }

                 if($flag_cerrar_caja){
                    $data = array(
                        'total_egresos' => $this->input->post('egresos')
                       ,'total_ingresos' => $this->input->post('ingresos')
                        ,'total_cierre' => $this->input->post('total')
                       ,'hora_cierre' => date('H:i:s')
                    );

                    $this->Caja->cerrar_caja_final($data);
                    $this->session->unset_userdata('caja');
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Se ha cerrado correctamente'));
                    redirect('caja/listado_cierres');
                }else{
                    $this->session->set_flashdata('error', custom_lang('sima_category_created_message', 'No es posible cerrar caja, verifique que no tenga mesas abiertas y que el usuario tenga almacen asociado'));
                    redirect('caja/listado_cierres');
                }

            }

            $id = $this->session->userdata('caja');

            $data['almacen'] = $this->almacen->get_all('0');
            $data['caja'] = $this->Caja->get_all('0');
            $empresa = $this->miempresa->get_data_empresa();
            $data = ['data' =>  $empresa];
            $caja_1 = $this->Caja->get_listado_cierre($id);

            $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
            if($caja_1[0]['hora_cierre']=="00:00:00"){
                $fecha_fin=$caja_1[0]['fecha']." 23:59:00";
            }else{
                $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
            }

            if(!empty($caja_1[0]['fecha_fin_cierre'])){

                $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
            }

            $detalle_movimientos['obtener_movimientos_validos'] = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
            $detalle_movimientos['obtener_impuestos_validos'] = $this->Caja->obtener_impuestos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
            //var_dump($obtener_movimientos_validos);die;
            $detalle_movimientos['obtener_movimientos_devoluciones'] = $this->Caja->obtenerDevolucionesCierreCaja($id);
            $detalle_movimientos['obtener_movimientos_anulados'] = $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']);
            $detalle_movimientos['obtener_movimientos_abonos'] = $this->Caja->obtenerAbonos($id);
            //$detalle_movimientos['formas_pago_validas'] = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin);
            $detalle_movimientos['formas_pago_validas'] = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']);
            $detalle_movimientos['cierres_salidas'] = $this->Caja->cierre_caja_gastos($id);
            $detalle_movimientos['cierre'] = $this->Caja->get_movimientos_cierre_entradas_apertura($id);

            $detalle_movimientos['rangoFacturas'] = $this->Caja->get_facturas_ultpri($id);
            $detalle_movimientos['obtener_movimientos_devoluciones_pendientes'] = $this->Caja->obtenerDevolucionesPendientes( $id );

            $datos_caja = array();
            foreach ($caja_1 as $caja) {
                $inicio = $fecha_inicio;
                $fecha_apertura = date("Y-m-d", strtotime($inicio));
                $fin = $fecha_fin;
                $fecha_cierre = date("Y-m-d", strtotime($fin));

                $datos_caja['fecha_inicio'] = $fecha_apertura;
                $datos_caja['fecha_fin'] = $fecha_cierre;
                $datos_caja['hora_apertura'] = $caja['hora_apertura'];
                $datos_caja['hora_cierre'] = $caja['hora_cierre'];
                $datos_caja['username'] = $caja['username'];
                $datos_caja['nombre_caja'] = $caja['nombre_caja'];
                $datos_caja['almacen'] = $caja['almacen'];
                $datos_caja['total_cierre'] =  $caja['total_cierre'];
                $datos_caja['total_egresos'] = $caja['total_egresos'];
                $datos_caja['total_ingresos'] =  $caja['total_ingresos'];
                $datos_caja['id'] = $caja['id'];
                $datos_caja['arqueo'] = $caja['arqueo'];
                $datos_caja['consecutivo'] = $caja['consecutivo'];
            }

            $html = generar_html_cierre_caja($empresa,$datos_caja,$detalle_movimientos,true);
           // print_r($data);
           // die();
           $data_empresa = $this->miempresa->get_data_empresa();
           $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
            $this->layout->template('member')->show('caja/cierre2',
                array('html'=>$html,'data'=>$data)
            );
            //var_dump($this->Caja->cierre_caja());
	}

    public function cierre_caja_periodo(){
        $data = array(
            'usuarios' =>  $this->caja->get_users()
        );
        acceso_informe('Cierre de Cajas por Fecha');
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('caja/cierre_caja_periodo',$data);
    }

    public function imprime_cierre_caja_periodo(){
        $user=$this->input->post('user');

        if(!empty($user)){
            $empresa = $this->miempresa->get_data_empresa();
            $datos_cierres = $this->Caja->get_data_user_caja($this->input->post(),$empresa );

            if($datos_cierres){
                $empresa = $this->miempresa->get_data_empresa($datos_cierres[0]->id_Almacen);
                $detalle_movimientos=array('obtener_movimientos_validos'=>array(),
                                        'obtener_movimientos_devoluciones'=>array(),
                                        'obtener_impuestos_validos'=>array(),
                                        'obtener_movimientos_anulados'=>array(),
                                        'obtener_movimientos_abonos'=>array('plan_separe'=>array(),'creditos'=>array()),
                                        'formas_pago_validas'=>array(),
                                        'cierres_salidas'=>array('pago_gastos_by_tipo'=>array(),
                                                'pago_proveedores' => array(),
                                                'gastos_cancelados'=> array(),
                                                'gastos_descuentan_caja'=> 0,

                                            ),
                                        'cierre'=>array(),
                                        'rangoFacturas'=>array(),
                                        'obtener_movimientos_devoluciones_pendientes'=>array(),
                                    );
                $datos_caja=array();
                foreach ($datos_cierres as $key => $un_cierre) {
                    $id = $un_cierre->id;
                    $caja_1 = $this->Caja->get_listado_cierre($id);

                    $fecha_inicio=$caja_1[0]['fecha']." ".$caja_1[0]['hora_apertura'];
                    if($caja_1[0]['hora_cierre']=="00:00:00"){
                        $fecha_fin=$caja_1[0]['fecha']." 23:59:00";
                    }else{
                        $fecha_fin=$caja_1[0]['fecha']." ".$caja_1[0]['hora_cierre'];
                    }

                    if(!empty($caja_1[0]['fecha_fin_cierre'])){

                        $fecha_fin=$caja_1[0]['fecha_fin_cierre'];
                    }

                    $obtener_movimientos_validos = $this->Caja->obtener_movimientos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
                    foreach ($obtener_movimientos_validos as $key => $value) {
                        array_push($detalle_movimientos['obtener_movimientos_validos'],$value);
                    }
                    $obtener_impuestos_validos = $this->Caja->obtener_impuestos_validos($id, 'validos',$caja_1[0]['id_Almacen']);
                    foreach ($obtener_impuestos_validos as $key => $value) {
                        array_push($detalle_movimientos['obtener_impuestos_validos'],$value);
                    }
                    //var_dump($obtener_movimientos_validos);die;
                    $obtener_movimientos_devoluciones = $this->Caja->obtenerDevolucionesCierreCaja($id);
                    foreach ($obtener_movimientos_devoluciones as $key => $value) {
                        array_push($detalle_movimientos['obtener_movimientos_devoluciones'],$value);
                    }
                    $obtener_movimientos_anulados = $this->Caja->obtener_movimientos_validos($id, 'anuladas',$caja_1[0]['id_Almacen']);
                    foreach ($obtener_movimientos_anulados as $key => $value) {
                        array_push($detalle_movimientos['obtener_movimientos_anulados'],$value);
                    }
                    $obtener_movimientos_abonos = $this->Caja->obtenerAbonos($id);
                    //var_dump($obtener_movimientos_abonos);die();
                    if(!empty($obtener_movimientos_abonos['creditos'])){
                        foreach ($obtener_movimientos_abonos['creditos'] as $key => $value) {
                            array_push($detalle_movimientos['obtener_movimientos_abonos']['creditos'],$value);
                        }

                    }
                    if(!empty($obtener_movimientos_abonos['plan_separe'])){
                        foreach ($obtener_movimientos_abonos['plan_separe'] as $key => $value) {
                            array_push($detalle_movimientos['obtener_movimientos_abonos']['plan_separe'],$value);
                        }

                    }


                    //$formas_pago_validas = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen'],$fecha_inicio,$fecha_fin);
                    $formas_pago_validas = $this->Caja->get_formas_pago_validas($id,$caja_1[0]['id_Almacen']);
                    foreach ($formas_pago_validas as $key => $value) {
                        array_push($detalle_movimientos['formas_pago_validas'],$value);
                    }

                    $cierres_salidas = $this->Caja->cierre_caja_gastos($id);
                    //var_dump($cierres_salidas);die();
                    if(!empty($cierres_salidas['pago_gastos_by_tipo'])){
                        foreach ($cierres_salidas['pago_gastos_by_tipo'] as $key => $value) {
                            array_push($detalle_movimientos['cierres_salidas']['pago_gastos_by_tipo'],$value);
                        }
                    }

                    if(!empty($cierres_salidas['pago_proveedores'])){
                        foreach ($cierres_salidas['pago_proveedores'] as $key => $value) {
                            array_push($detalle_movimientos['cierres_salidas']['pago_proveedores'],$value);
                        }
                    }

                    if(!empty($cierres_salidas['gastos_cancelados'])){
                        foreach ($cierres_salidas['gastos_cancelados'] as $key => $value) {
                            array_push($detalle_movimientos['cierres_salidas']['gastos_cancelados'],$value);
                        }
                    }

                    if(!empty($cierres_salidas['gastos_descuentan_caja'])){
                        //var_dump($cierres_salidas['gastos_descuentan_caja']);
                    /* foreach ($cierres_salidas['gastos_descuentan_caja'] as $key => $value) {
                            array_push($detalle_movimientos['cierres_salidas']['gastos_descuentan_caja'],$value);
                        }*/

                        $detalle_movimientos['cierres_salidas']['gastos_descuentan_caja']+= $cierres_salidas['gastos_descuentan_caja'];
                    }



                    $cierre = $this->Caja->get_movimientos_cierre_entradas_apertura($id);
                    foreach ($cierre as $key => $value) {
                        array_push($detalle_movimientos['cierre'],$value);
                    }

                    $rangoFacturas = $this->Caja->get_facturas_ultpri($id);
                    array_push($detalle_movimientos['rangoFacturas'],$rangoFacturas);

                    $obtener_movimientos_devoluciones_pendientes = $this->Caja->obtenerDevolucionesPendientes( $id );
                    foreach ($obtener_movimientos_devoluciones_pendientes as $key => $value) {
                    array_push($detalle_movimientos['obtener_movimientos_devoluciones_pendientes'],$value);
                    }


                    $datos_caja = array();
                    foreach ($caja_1 as $caja) {
                        $datos_caja['fecha_inicio'] = $fecha_inicio;
                        $datos_caja['fecha_fin'] = $fecha_fin;
                        $datos_caja['hora_apertura'] = $caja['hora_apertura'];
                        $datos_caja['hora_cierre'] = $caja['hora_cierre'];
                        $datos_caja['username'] = $caja['username'];
                        $datos_caja['nombre_caja'] = $caja['nombre_caja'];
                        $datos_caja['almacen'] = $caja['almacen'];
                        $datos_caja['total_cierre'] =  $caja['total_cierre'];
                        $datos_caja['total_egresos'] = $caja['total_egresos'];
                        $datos_caja['total_ingresos'] =  $caja['total_ingresos'];
                        $datos_caja['id'] = $caja['id'];
                        $datos_caja['arqueo'] = $caja['arqueo'];
                        $datos_caja['consecutivo'] = $caja['consecutivo'];
                    }
                }

                require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(200, 0), true, 'UTF-8', false);
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->AddPage('P', "LETTER");
                $detalle_movimientos['rangoFacturas'] = implode('<br>',$detalle_movimientos['rangoFacturas']);
                //var_dump($detalle_movimientos['obtener_movimientos_abonos']);die();
                $html = generar_html_cierre_caja($empresa,$datos_caja,$detalle_movimientos);
                ob_clean();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output('cuadre de caja.pdf', 'I');
            }else{
                $this->session->set_flashdata('message', '¡ALERTA! No se encontraron cierres para el usuario seleccionado');
                redirect('caja/cierre_caja_periodo', 'refresh');
            }
        }else{
            $this->session->set_flashdata('message', 'Debe seleccionar un usuario');
            redirect('caja/cierre_caja_periodo', 'refresh');
        }
    }

    /**
     * Function
     */
    function verify_state(){
        $response = get_curl("box/verify-state",$this->session->userdata('token_api'));
        echo json_encode($response);
    }

    function open(){
        $data = array(
            'opening_value' => $this->input->post('opening_value')
        );
        $response = post_curl("box/open",$data,$this->session->userdata('token_api'));
        echo json_encode($response);
    }

    function verify(){
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["estado_caja"] = "cerrada";
            //verifico si la caja esta abierta
        if ($this->session->userdata('caja') != ""){
            $data["estado_caja"] = "abierta";
        } else {
            //verifico si hay caja abierta y no la tengo en session
            //verifico si hay una caja abierta para el usuario
            //verifico si hay cierre automatico
            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d");
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
                }else{
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'));
                }

                $orderby_cierre="fecha desc, hora_apertura desc";
                $limit_cierre="1";
                $cierre_caja=$this->Caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);

                if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $data["estado_caja"] = "abierta";
                }
            }else{
                $data["estado_caja"] = "abierta";
            }
        }

        echo json_encode($data);
    }

    /**
     * @method  getBox($id)
     *  Función para consultar una caja por Id
     *  @param Int $id
     * @author [José Fernnado]
     * @return json
    */
    public function getBox($id){
        $response = $this->Caja->getBox($id);
        echo json_encode($response);
    }

    /**
     * @method  saveBox()
     *  Función para guardar una caja
     * @author [José Fernnado]
     * @return json
    */
    public function saveBox(){
        $id = $this->input->post("id");
        $name = $this->input->post("name");
        $store = $this->input->post("store");
        $response = $this->Caja->saveBox($id,$name,$store);
        echo json_encode($response);
    }
}
?>
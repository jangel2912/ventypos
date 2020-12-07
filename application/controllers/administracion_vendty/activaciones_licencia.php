<?php 

Class Activaciones_licencia extends CI_Controller
{

	var $dbConnection;

    function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD'); 
		$this->load->model('crm_model');
		$this->load->model('usuarios_model');
		$this->load->model('crm_licencia_model');
		$this->load->model('crm_facturas_model');
		
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }
	}

	public function armar_archivos_js_css(){
		$js = array(
				base_url().'assets/grocery_crud/js/jquery-1.11.1.js',
				base_url().'assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js',
				base_url().'assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js',
				base_url().'assets/grocery_crud/js/jquery_plugins/config/jquery.datepicker.config.js',
				base_url().'assets/grocery_crud/js/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-es.js',
				base_url().'assets/grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js'
			);

		$css = array(
				base_url().'assets/grocery_crud/css/jquery_plugins/chosen/chosen.css',
				base_url().'assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css',
				
			);
		return (object)array('js'=>$js,'css'=>$css);
	}

	public function nuevo_view(){
		$archivos =  $this->armar_archivos_js_css();
		$gc = (object)array('output' => '' , 'js_files' => $archivos->js , 'css_files' => $archivos->css);
		$data=array();
		$data['planes']= $this->crm_model->get_planes(array('activo_plan'=>1));
		
		if($this->ion_auth->in_group(array(3,5)) ){
			$id_distribuidor = $this->crm_model->get_id_distribuidor(array('users_id'=>$this->ion_auth->get_user_id()));
			$data['empresas'] = $this->crm_model->get_empresas(array('id_distribuidores_licencia'=>$id_distribuidor));
		}else{			
			$data['empresas'] = $this->crm_model->get_empresas(array('id_user_distribuidor'=>$this->ion_auth->get_user_id()));
		}
		
		$data['formas_pago'] = $this->crm_model->get_formas_pago(array('activo_forma'=>1));
		$this->layout->template('administracion_vendty')->show('administracion_licencia/activar_manual_back',array('gc' => $gc,'data'=>$data));
	}


	public function guardar(){
		$this->form_validation->set_rules('s_empresa', 'Empresa', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('s_plan', 'Plan', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('s_almacen', 'Almacenes', 'required');
		$this->form_validation->set_rules('t_fecha_inicio', 'Fecha inicio licencia', 'required');
		$this->form_validation->set_rules('s_forma_pago', 'Forma de pago', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('t_fecha_pago', 'Fecha de pago', 'required');
		$this->form_validation->set_rules('t_valor_antes_impuesto', 'Valor antes de impuestos', 'required');
		$this->form_validation->set_rules('t_valor_impuesto', 'Valor de impuestos', 'required');
		$this->form_validation->set_rules('t_valor_total', 'Valor total pago', 'required');
		$this->form_validation->set_rules('t_fecha_conciliacion', 'Fecha consolidacion pago', 'required');
		$this->form_validation->set_rules('t_fecha_factura', 'Fecha de factura', 'required');
		$this->form_validation->set_rules('t_fecha_vencimiento_factura', 'Fecha ', 'required');

		if($this->form_validation->run() == true){
			//var_dump($this->input->post('s_almacen'));die();
			$almacenes = $this->input->post('s_almacen');
			$licencias_creadas = array();
			foreach ($almacenes as $key => $value) {
				if($value == -1){
					$value = $this->_crear_nuevo_almacen($this->input->post('s_empresa'));
				}
				$licencias_creadas[] = $this->_crear_licencia($value);

			}
			//crear factura
			$id_factura = $this->_crear_factura();
			$this->_crear_detalle_factura($id_factura,$licencias_creadas);   	
			$this->_crear_pago_licencia($id_factura,null,1);	
			echo json_encode(array('success'=>true,'error'=> custom_lang('exito_crear_licencia','Licencia(s) creada exitosamente!')));
		}else{
			$error = validation_errors();
			echo json_encode(array('success'=>false,'error'=>$error));
		}

	}

	public function _crear_nuevo_almacen($id_empresa){
		$id_db_config = $this->_get_db_config_empresa($id_empresa);
		$this->armar_conexion_bd_cliente($id_db_config);
		$data=array('nombre'=>'Nuevo almacen','telefono'=>'','ciudad'=>'');
		$this->dbConnection->insert('almacen',$data);
		return $this->dbConnection->insert_id();
	}

	private function _crear_pago_licencia($id_factura = null,$id_orden=null,$estado_pago){
		$id_formas_pago = $this->input->post('s_forma_pago');
		$fecha_pago = $this->input->post('t_fecha_pago');
		$monto_pago = $this->input->post('t_valor_total');
		$fecha_conciliacion = $this->input->post('t_fecha_conciliacion');
		$observacion_pago = $this->input->post('ta_observacion_adicional_pago');
		$data_pago = array(
				'creado_por' => $this->ion_auth->get_user_id(),
				'fecha_creacion' => date("Y-m-d h:i:s"),
				'idformas_pago' => $id_formas_pago,
				'fecha_pago'	=> $fecha_pago,
				'monto_pago'	=> $monto_pago,
				'estado_pago'	=> $estado_pago,
				'fecha_conciliacion'=> $fecha_conciliacion,
				'observacion_pago' => $observacion_pago, 
				'id_orden_licencia' => $id_orden,
				'id_factura_licencia' => $id_factura 
			);
		$this->crm_model->agrear_pago($data_pago);
	}

	private function _crear_detalle_factura($id_factura,$licencias_creadas){
		$arreglo_planes=array();
		foreach ($licencias_creadas as $key => $value) {
			$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$value));
			if(isset($arreglo_planes[$datos_licencia[0]->planes_id])){
				$nueva_cantidad = $arreglo_planes[$datos_licencia[0]->planes_id]['cantidad'];
				$arreglo_planes[$datos_licencia[0]->planes_id]['cantidad']=++$nueva_cantidad;
				$arreglo_planes[$datos_licencia[0]->planes_id]['datos_licencia'][]=$datos_licencia[0];
			}else{
				$datos_plan = $this->crm_model->get_planes(array('id'=>$datos_licencia[0]->planes_id));
				$arreglo_planes[$datos_licencia[0]->planes_id]=array('cantidad'=>1,'datos_licencia'=>array(),'nombre_licencia'=>$datos_plan[0]->nombre_plan);
				$arreglo_planes[$datos_licencia[0]->planes_id]['datos_licencia'][]=$datos_licencia;
			}
		}

		foreach ($arreglo_planes as $key => $value) {
			$data_insertar = array(
					'id_factura_licencia'=>$id_factura,
					'nombre_licencia_orden'=>$value['nombre_licencia'],
					'cantidad_licencia_orden'=>$value['cantidad'],
					'datos_licencia_json'=> json_encode($value['datos_licencia'])
				);
			$this->crm_facturas_model->agregar_detalle_factura($data_insertar);
		}
	}

	private function _crear_factura(){
		$id_empresa = $this->input->post('s_empresa');
		$descripcion_factura = $this->input->post('ta_observacion_adicional_factura');
		$numero_factura = $this->crm_facturas_model->get_numero_factura();
		$fecha_factura = $this->input->post('t_fecha_factura');
		if(!$fecha_factura){
			$fecha_factura = date("Y-m-d");
		}
		$fecha_vencimiento = $this->input->post('t_fecha_vencimiento_factura');
		if(!$fecha_vencimiento){
			$fecha_vencimiento = date("Y-m-d",strtotime("+5 day"));
		}
		$total_impuesto = $this->input->post('t_valor_impuesto');
		$total_factura = $this->input->post('t_valor_total');

		$data_factura = array( 
					'creado_por' => $this->ion_auth->get_user_id(),
					'fecha_creacion' => date("Y-m-d h:i:s"),
					'numero_factura' => $numero_factura,
					'fecha_factura'	 => $fecha_factura, 
					'total_impuesto_factura' => $total_impuesto, 
					'total_factura'  => $total_factura,
					'fecha_vencimiento_factura' => $fecha_vencimiento,
					'descripcion_factura'	=> $descripcion_factura,
					'idempresas_clientes' => $id_empresa,
					'estado_factura'	  => 3
			);
		$id_factura = $this->crm_facturas_model->agregar_factura($data_factura);
		return $id_factura;
	}

	private function _crear_licencia($id_almacen = null){
		$fecha_inicio = $this->input->post('t_fecha_inicio');
		if(!$fecha_inicio){
			$fecha_inicio = date("Y-m-d");
		}
		$id_empresa = $this->input->post('s_empresa');
		if(!$id_empresa){
			$id_empresa = $this->_empresa_usuario();
		}
		$id_plan = $this->input->post('s_plan');
		$fecha_fin_licencia = $this->_get_fecha_fin_licencia($id_plan,$fecha_inicio);
		$id_db_config = $this->_get_db_config_empresa($id_empresa);
		$observacion_adicional_licencia = $this->input->post('ta_observacion_adicional_licencia');

		$data_licencia=array(
				'fecha_creacion' => date("Y-m-d h:i:s"),
				'creado_por'	 => $this->ion_auth->get_user_id(),
				'idempresas_clientes' => $id_empresa,
				'planes_id'		 => $id_plan,
				'fecha_inicio_licencia' => $fecha_inicio,
				'fecha_vencimiento' => $fecha_fin_licencia,
				'id_db_config'      => $id_db_config,
				'id_almacen'		=> $id_almacen,
				'estado_licencia'	=>1,
				'observacion_adicional_licencia'=>$observacion_adicional_licencia
		);

		$id = $this->crm_licencia_model->agregar_licencia($data_licencia);
		return $id;
	}

	private function _get_fecha_fin_licencia($id_plan, $fecha_inicio){
		$datos_plan = $this->crm_model->get_planes(array('id'=>$id_plan));
		$dias_vigencia = 1;
		foreach ($datos_plan as $key => $value) {
			$dias_vigencia = $value->dias_vigencia;
		}
		$unix_fecha_incio = strtotime($fecha_inicio);
		$fecha_fin_licencia = date("Y-m-d",strtotime("+$dias_vigencia day",$unix_fecha_incio));
		return $fecha_fin_licencia;
	}

	private function get_db_config_user(){
		$datos_usuario = $this->usuarios_model->getId($this->ion_auth->get_user_id());
		return $datos_usuario->db_config_id;
	}

	private function _empresa_usuario(){
		$datos_usuario = $this->usuarios_model->getId($this->ion_auth->get_user_id());
		$datos_empresa = $this->crm_model->get_empresas(array('id_db_config'=>$datos_usuario->db_config_id));
		$id_empresa = 0;
		foreach ($datos_empresa as $key => $value) {
			$id_empresa = $value->idempresas_clientes;
		}
		return $id_empresa;
	}

	private function _get_db_config_empresa($id_empresa){
		$datos_empresa = $this->crm_model->get_empresas(array('idempresas_clientes'=>$id_empresa));
		$id_db_config = 0;
		foreach ($datos_empresa as $key => $value) {
			$id_db_config = $value->id_db_config;
		}
		return $id_db_config;
	}

	public function consultar_almacen_cliente(){
		$id_db_config = $this->input->post('id_config');

		if (isset($_POST['activo'])) {
			$activo = false;
		}else{
			$activo = true;
		}		
		
		$devolver = array();
		if(is_numeric($id_db_config)){
			
			$this->armar_conexion_bd_cliente($id_db_config);
			$this->load->model("almacenes_model");
			$this->almacenes_model->initialize($this->dbConnection);
			$almacenes =$this->almacenes_model->get_almacenes_activos($activo);
			
			foreach ($almacenes as $key => $value) {
				$devolver[]=array('id'=>$value->id,'nombre'=>$value->nombre);
			}
		}
		
		echo json_encode($devolver);
	}


	public function consultar_almacen_empresa(){
		$id_empresa = $this->input->post('id_empresa');

		if (isset($_POST['activo'])) {
			$activo = false;
		}else{
			$activo = true;
		}		
		
		$devolver = array();
		if(is_numeric($id_empresa)){
			$datos_empresa = $this->crm_model->get_empresas(array('idempresas_clientes'=>$id_empresa));

			$this->armar_conexion_bd_cliente($datos_empresa[0]->id_db_config);
			$this->load->model("almacenes_model");
			$this->almacenes_model->initialize($this->dbConnection);
			$almacenes =$this->almacenes_model->get_almacenes_activos($activo);
			
			foreach ($almacenes as $key => $value) {
				$devolver[]=array('id'=>$value->id,'nombre'=>$value->nombre);
			}
		}
		
		echo json_encode($devolver);
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

?>
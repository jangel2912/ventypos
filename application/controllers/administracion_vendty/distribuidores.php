<?php
class distribuidores extends CI_controller {
    var $user_id;
	var $id_db_config;
	
        function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD'); 
		$this->load->model('ion_auth_model');
		
		$this->load->model("distribuidores_model", 'distribuidores');
		$this->load->model('crm_model');
		$this->load->model('crm_licencia_model');
		$this->load->model('crm_model');
        $this->load->model("pais_model");
        $this->load->model("usuarios_model");
        $this->load->model("crm_licencia_model");
        $this->load->model("crm_empresas_clientes_model");      
        $this->load->model("crm_licencias_empresa_model");      
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){                  
            redirect("frontend/index");
        }
		
	}

	function index(){
		$crud = new grocery_CRUD();
        $crud->set_subject('Distribuidor');
        $crud->set_table('crm_distribuidores_licencia');
        $crud->field_type('creado_por','hidden',$this->ion_auth->get_user_id());
        $crud->field_type('fecha_creacion','hidden',date("Y-m-d h:i:s"));
        $crud->set_relation_n_n('usuarios','crm_usuarios_distribuidores','users','id_distribuidores_licencia','users_id','email');
        $crud->columns('fecha_creacion','nombre_distribuidor','usuarios');
        $output = $crud->render();
		$this->layout->template('administracion_vendty')->show('administracion_licencia/gc_example',array('gc' => $output));

	}


	public function usuarios_comerciales(){
		$crud = new grocery_CRUD();
		$crud->set_subject('Usuario');
		$crud->set_table('users');
		$crud->where('users.id in (select users_id from crm_usuarios_distribuidores)');
		$crud->fields('first_name','last_name','company','email','phone','password','password_confirm','rol');
		$crud->unset_edit();
		$crud->callback_delete(array($this,'disable_user'));
		$crud->display_as('first_name','Nombre(s)');
		$crud->display_as('last_name','Apellido(s)');
		$crud->display_as('company','Compañia');
		$crud->display_as('phone','Telef&oacute;no');
		$crud->display_as('password_confirm','Confirmacion de clave');
		$crud->columns('first_name','last_name','company','email','phone','rol');
		$crud->set_relation('company','crm_distribuidores_licencia','nombre_distribuidor');
		$crud->field_type('password','password');
		$crud->field_type('password_confirm','password');
		$crud->field_type('rol','dropdown',array('3'=>'Director distribuidor','4'=>'Comercial distribuidor'));
		$crud->callback_insert(array($this,'registrar_usuario'));
		$crud->callback_column('rol',array($this,'_callback_rol_distribuidor'));
		$crud->set_rules('email','Email','valid_email|is_unique[users.email]');
		$output = $crud->render();
		$this->layout->template('administracion_vendty')->show('administracion_licencia/gc_example',array('gc' => $output));		
	}

	public function _callback_rol_distribuidor($value, $row){
		if($this->ion_auth->in_group(3,$row->id)){
			return 'Director distribuidor';
		}elseif ($this->ion_auth->in_group(5,$row->id)) {
			return 'Director Vendty';
		}
		return 'Comercial distribuidor';
	}

	public function disable_user($key){
		$this->db->delete('crm_usuarios_distribuidores',array('users_id'=>$key));
		return $this->ion_auth_model->deactivate($key);

	}

	public function registrar_usuario($post_array){
		//var_dump($post_array);die();
		$username = strtolower($post_array['first_name']);
		$additional_data = array(
                'first_name' => $post_array['first_name'],
				'last_name'  => $post_array['last_name'],
                'is_admin'   => 't',
                'company'    => $post_array['company'],
                'phone'		 => $post_array['phone'],
            );
		$groups=array($post_array['rol']);
		$id_usuario= $this->ion_auth_model->register($username, $post_array['password'], $post_array['email'], $additional_data,$groups);
		if($id_usuario){
			$data_distribuidor =array('id_distribuidores_licencia'=>$post_array['company'], 'users_id'=>$id_usuario );
			$this->db->insert('crm_usuarios_distribuidores',$data_distribuidor);
			return true;
		}else{
			return false;
		}

	}

	/* Load View */
	public function nueva_suscripcion(){
		$data["creation_distribuidor"] = $this->crm_model->get_distribuidor_by_id();
		$this->layout->template('distribuidores_vendty')->show('distribuidores/nueva_suscripcion',$data);		
	}

	/* Load View */
	public function suscripciones(){

		$tipos_licencia = '';
		foreach ($this->crm_model->get_all_planes_by_distribuidor() as $value) {
            $tipos_licencia .= "<option value='{$value->id}'>{$value->nombre_plan}</option>";
		}

		//Validar si es distribuidor o vendedor del distribuidor
		$vendedores = '';
		foreach ($this->crm_model->get_vendedores() as $value) {
            $vendedores .= "<option value='{$value->email}'>{$value->email}</option>";
		}

		$this->layout->template('distribuidores_vendty')->show('distribuidores/suscripciones',['tipos_licencia' => $tipos_licencia,'vendedores'=>$vendedores]);		
	}

	public function get_ajax_data_clientes_by_graphics(){
		//$filter = $this->input->post('filter');
		$fecha_inicio = $this->input->post('fecha_inicio');
		$fecha_fin = $this->input->post('fecha_fin');			
        $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->get_ajax_data_clientes_by_graphics2($fecha_inicio, $fecha_fin)));
	}


	public function get_ajax_data_suscripciones(){
		$fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
		$estado = $this->input->get('estado');
		$tipo_plan = $this->input->get('tipo_plan');
		$vendedor = $this->input->get('vendedor');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->get_ajax_data_clientes_distribuidor($fecha_inicio, $fecha_fin, $estado,$tipo_plan,$vendedor)));
   
	}

	public function get_ajax_data_licencias(){
		$fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
		$estado = $this->input->get('estado');
		$tipo_plan = $this->input->get('tipo_plan');
		$vendedor = $this->input->get('vendedor');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->get_ajax_data_licencias($fecha_inicio, $fecha_fin, $estado,$tipo_plan,$vendedor)));
   
	}

	public function get_ajax_data_pagos(){
		$fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
		$estado = $this->input->get('estado');
		$tipo_plan = $this->input->get('tipo_plan');
		$cliente = $this->input->get('cliente');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->get_ajax_data_pagos($fecha_inicio, $fecha_fin, $estado,$tipo_plan,$cliente)));
	}

	public function nuevo_usuario_distribuidor(){
		$distribuidor = $this->input->post('Distribuidor_id');
        $nombre = $this->input->post('Last_Name');
		$email = $this->input->post('Email');
		$mobile = $this->input->post('Mobile');
		$this->crm_model->nuevo_usuario_distribuidor($distribuidor, $nombre, $email,$mobile);
	}


	/* Load View */
	public function herramientas(){
		$this->layout->template('distribuidores_vendty')->show('distribuidores/herramientas');		
	}
	public function churm_datos(){
						
			//buscar por mes los activos z
			$fecha1 = new DateTime('2018-05');
			$fecha2=date('Y-m');
			$fecha2 = new DateTime($fecha2);
			$resultado = $fecha1->diff($fecha2);
			$meses = ( $resultado->y * 12 ) + $resultado->m;
			
			///ultimos 12 meses        
			for ($i=$meses; $i >=0 ; $i--) { 
				$fechainicial=date('Y-m-d');
				$fechainicial = strtotime ( '-'.$i.' month' , strtotime ($fechainicial)) ;
				$fechainicial = date ( 'Y-m-01' , $fechainicial );        
				$fechafin=$fechainicial;		
				$fechafin = strtotime ( '+1 month' , strtotime ($fechafin)) ;
				$fechafin = date ('Y-m-d' ,$fechafin );	
				$fechafin = strtotime ( '-1 day' , strtotime ($fechafin)) ;
				$fechafin = date ('Y-m-d' ,$fechafin );					
				$adicional="";
				$mes = date("Y M", strtotime($fechainicial));
				if($fechainicial=='2018-07-01'){
					$adicional=1;
				}
				$activos[$mes]['activos']=$this->crm_model->get_suscripciones_nuevas('mensual',$fechainicial,$fechafin);
				$activos[$mes]['cant_activos']=count($activos[$mes]['activos']);
				$activos[$mes]['no_renovaron']=$this->crm_model->get_all_vencidos_suscriptos_nuevos('mensual',$fechainicial,$fechafin,$adicional);
				$activos[$mes]['cant_no_renovaron']=count($activos[$mes]['no_renovaron']);
				$activos[$mes]['fecha']=$fechainicial;
				
			}
										
			$arreglo_cantidad = count($activos);
			for ($i = 0; $i <= $arreglo_cantidad-1; $i++){							
				$actual=key($activos);
				$anterior=prev($activos) !== false ? key($activos):'';
				//consultar el anterior				
				$churm=0;				
				$cant_activo_actual=$activos[$actual]['cant_activos'];
				$cant_no_renovaron_actual=$activos[$actual]['cant_no_renovaron'];
				$fecha=$activos[$actual]['fecha'];
				
				if(!empty($anterior)){
					$activos_anterior=$activos[$anterior]['cant_activos'];
					$final_array[$actual]['activos']=$cant_activo_actual;
					$final_array[$actual]['cant_no_renovaron_actual']=$cant_no_renovaron_actual;
					$final_array[$actual]['activos_anterior']=$activos_anterior;
					$final_array[$actual]['churm']=$cant_no_renovaron_actual==0 ? 0 :(($cant_no_renovaron_actual*100)/$activos_anterior);
					$final_array[$actual]['fecha']=$fecha;
				}else{
					$activos_anterior=0;
					$final_array[$actual]['activos']=$cant_activo_actual;
					$final_array[$actual]['cant_no_renovaron_actual']=$cant_no_renovaron_actual;
					$final_array[$actual]['activos_anterior']=$activos_anterior;
					$final_array[$actual]['churm']=0;
					$final_array[$actual]['fecha']=$fecha;
				}	
				
				//volver a la posicion actual
				if (is_null(key($activos)))
				{
					reset($activos);
				}else{
					next($activos);
				}	
				
				next($activos); //avanzar a la siguiente posicion para la siguiente vuelta del for
				
			}

			return $final_array;
	}
	public function descargar_excel_clientes_churm($tipo,$fecha){
		if($this->ion_auth->in_group(3)){
			if($tipo=='nuevos'){
				$data["title"]= "Nuevos Mensuales";
				$data["url"] = "nuevos_mensuales/".$fechainicial."/".$fechafin;
				$data["identificador"] = "nuevos_mensuales";
				$data["clientes"]=$this->crm_model->get_suscripciones_nuevas('mensual',$fechainicial,$fechafin);
			}else{
				if($tipo=='vencidos'){
					$data["title"]= "Vencidos Mensuales";
					$data["url"] = "vencidos_mensual/".$fechainicial."/".$fechafin;
					$data["identificador"] = "vencidos_mensual";
					$data["clientes"]=$this->crm_model->get_all_vencidos_suscriptos_nuevos('mensual',$fechainicial,$fechafin,$adicional);
				}
			}
		}
	}
	public function descargar_excel_churm(){
		if($this->ion_auth->in_group(3)){
			//distribuidor
            $user=$this->session->userdata('user_id');
            $distribuidor = $this->crm_model->get_distribuidor2(array('users_id'=>$user));
			$iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
				
			if($iddistribuidor!=1){
                redirect("distribuidores_vendty/index");
            }else{
				$query=$this->churm_datos();			
				$this->load->library('phpexcel');
				$this->phpexcel->disconnectWorksheets();
				$this->phpexcel->createSheet();
				$this->phpexcel->setActiveSheetIndex(0);
				$this->phpexcel->getActiveSheet()->setCellValue('A1', 'Mes');
				$this->phpexcel->getActiveSheet()->setCellValue('A2', 'Activos Nuevos');
				$this->phpexcel->getActiveSheet()->setCellValue('A3', 'No Renovados');
				$this->phpexcel->getActiveSheet()->setCellValue('A4', 'Churm %');				
				$contador=1; 
				$letra='B'; 
				$row=1;				

				$this->phpexcel->getActiveSheet()->getStyle('A1:A4')->applyFromArray(
					array(                                
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '76933c')
							)
						),
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => 'c6efce')
						),
						'font' => array(
							'bold' => true,
							'color' => array('rgb' => '32482b')
						)
					)
				);
				$greenNotBold = array(                                
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '76933c')
						)
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => 'c6efce')
					),
					'font' => array(
						'bold' => true,
						'color' => array('rgb' => '32482b')
					)
				);
				
					foreach ($query as $key => $value) {
						if ($contador >= 0) {
							$this->phpexcel->getActiveSheet()->setCellValue($letra.$row, html_entity_decode($key, ENT_QUOTES, 'UTF-8'))->getStyle($letra.$row)->applyFromArray($greenNotBold);
							//$this->phpexcel->getActiveSheet()->setCellValue($letra.$row, html_entity_decode($key, ENT_QUOTES, 'UTF-8'));                
							$row++;
							$this->phpexcel->getActiveSheet()->setCellValue($letra.$row, html_entity_decode($value['activos'], ENT_QUOTES, 'UTF-8')); 
							$row++;
							$this->phpexcel->getActiveSheet()->setCellValue($letra.$row, html_entity_decode($value['cant_no_renovaron_actual'], ENT_QUOTES, 'UTF-8'));                
							$row++;
							$this->phpexcel->getActiveSheet()->setCellValue($letra.$row, html_entity_decode($value['churm'], ENT_QUOTES, 'UTF-8')); 
							$row=1;							
							$contador++; 
							$letra++; 
						}
					}
				  
				$titulo="ReporteChurm";
				$this->phpexcel->getActiveSheet()->setTitle();
				header('Content-Type: application/vnd.ms-excel');
				header("Content-Disposition: attachment;filename=$titulo.xls");
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
		}
	}
	public function cargar_cliente_churm($tipo,$fecha,$excel=null){
		
		if($this->ion_auth->in_group(3)){
			
			$fechainicial=$fecha;
			$fechafin=$fecha;		
			$fechafin = strtotime ( '+1 month' , strtotime ($fechafin)) ;
			$fechafin = date ('Y-m-d' ,$fechafin );	
			$fechafin = strtotime ( '-1 day' , strtotime ($fechafin)) ;
			$fechafin = date ('Y-m-d' ,$fechafin );		
			$adicional="";
			if($fechainicial=='2018-07-01'){
				$adicional=1;
			}

			if($tipo=='nuevos'){
				$data["title"]= "Nuevos Mensuales";
				$data["url"] = "nuevos/".$fechainicial."/1";
				$data["clientes"]=$this->crm_model->get_suscripciones_nuevas('mensual',$fechainicial,$fechafin);
			}else{
				if($tipo=='vencidos'){
					$data["title"]= "Vencidos Mensuales";
					$data["url"] = "vencidos/".$fechainicial."/1";
					$data["clientes"]=$this->crm_model->get_all_vencidos_suscriptos_nuevos('mensual',$fechainicial,$fechafin,$adicional);
				}
			}
			if($excel=='1'){//descargar excel
				$this->load->library('phpexcel');
				$this->phpexcel->setActiveSheetIndex(0);
				
				$this->phpexcel->getActiveSheet()->setCellValue('A1', 'Id Licencia');
				$this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha Activación');
				$this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha inicio licencia');
				$this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha vencimiento licencia'); 
				$this->phpexcel->getActiveSheet()->setCellValue('E1', 'Empresa'); 
				$this->phpexcel->getActiveSheet()->setCellValue('F1', 'Correo'); 
				$this->phpexcel->getActiveSheet()->setCellValue('G1', 'Suscripción'); 
				$this->phpexcel->getActiveSheet()->setCellValue('H1', 'Valor Suscripción'); 
				$this->phpexcel->getActiveSheet()->setCellValue('I1', 'Días Vigencia Suscripción'); 
				$this->phpexcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
					array(                                
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '76933c')
							)
						),
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => 'c6efce')
						),
						'font' => array(
							'bold' => true,
							'color' => array('rgb' => '32482b')
						)
					)
				);
				$query=$data["clientes"];				
				$row = 2;
				$count = 0;
				foreach ($query as $cliente) {				
					$this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($cliente->idlicencias_empresa, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($cliente->fecha_activacion, ENT_QUOTES, 'UTF-8'));                
                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($cliente->fecha_inicio_licencia, ENT_QUOTES, 'UTF-8'));                
                    $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($cliente->fecha_vencimiento, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($cliente->nombre_empresa, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($cliente->email, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($cliente->nombre_plan, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($cliente->valor_final, ENT_QUOTES, 'UTF-8'));
					$this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($cliente->dias_vigencia, ENT_QUOTES, 'UTF-8'));
					
					$count++;
					$row++;
				}
					$titulo=$tipo."_".$fecha;
					$this->phpexcel->getActiveSheet()->setTitle($titulo);

					header('Content-Type: application/vnd.ms-excel');
					header("Content-Disposition: attachment;filename=$titulo.xls");
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
					
			}else{
				$this->layout->template('distribuidores_vendty')->show('distribuidores/clienteschurm',$data);
			}
			

		}
	}
	/* Load View */
	public function churm(){
		if($this->ion_auth->in_group(3)){
			//distribuidor
            $user=$this->session->userdata('user_id');
            $distribuidor = $this->crm_model->get_distribuidor2(array('users_id'=>$user));
			$iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
			
			if($iddistribuidor!=1){
                redirect("distribuidores_vendty/index");
            }else{
				$data['churm']=$this->churm_datos();
				$this->layout->template('distribuidores_vendty')->show('distribuidores/churm',array('data' => $data));			
            }
			
		}
	}

	/* Load View */
	public function inicio(){
		$this->layout->template('distribuidores_vendty')->show('distribuidores/inicio');		
	}

	/* Load View */
	public function licencias(){
		//print_r($this->session->userdata);
		$data["distribuidor"] = $this->crm_model->get_info_distribuidor_by_id();
		$data["vendedores"] = $this->crm_model->get_vendedores();
		//$data["clientes"] = $this->crm_model->get_clientes_distribuidor();
		$this->layout->template('distribuidores_vendty')->show('distribuidores/licencias',$data);		
	}

	/* Load View */
	public function informes(){
		$this->layout->template('distribuidores_vendty')->show('distribuidores/informes');		
	}

	/* Load View */
	public function informe_clientes(){
		$tipos_licencia = '';
		foreach ($this->crm_model->get_all_planes_by_distribuidor() as $value) {
            $tipos_licencia .= "<option value='{$value->id}'>{$value->nombre_plan}</option>";
		}

		//Validar si es distribuidor o vendedor del distribuidor
		$vendedores = '';
		foreach ($this->crm_model->get_vendedores() as $value) {
            $vendedores .= "<option value='{$value->id}'>{$value->email}</option>";
		}

		$this->layout->template('distribuidores_vendty')->show('distribuidores/informe_clientes',['tipos_licencia' => $tipos_licencia,'vendedores'=>$vendedores]);		
	}

	/* Load View */
	public function informe_licencias(){
		$tipos_licencia = '';
		foreach ($this->crm_model->get_all_planes_by_distribuidor() as $value) {
            $tipos_licencia .= "<option value='{$value->id}'>{$value->nombre_plan}</option>";
		}

		//Validar si es distribuidor o vendedor del distribuidor
		$vendedores = '';
		foreach ($this->crm_model->get_vendedores() as $value) {
            $vendedores .= "<option value='{$value->id}'>{$value->email}</option>";
		}

		$this->layout->template('distribuidores_vendty')->show('distribuidores/informe_licencias',['tipos_licencia' => $tipos_licencia,'vendedores'=>$vendedores]);		
	}

	/* Load View */
	public function informe_pagos(){
		$tipos_licencia = '';
		foreach ($this->crm_model->get_all_planes_by_distribuidor() as $value) {
            $tipos_licencia .= "<option value='{$value->id}'>{$value->nombre_plan}</option>";
		}

		//Validar si es distribuidor o vendedor del distribuidor
		$clientes = '';
		foreach ($this->crm_model->get_clientes() as $value) {
            $clientes .= "<option value='{$value->id}'>{$value->email}</option>";
		}

		$this->layout->template('distribuidores_vendty')->show('distribuidores/informe_pagos',['tipos_licencia' => $tipos_licencia,'clientes'=>$clientes]);		
	}


	/* Load View */
	public function configuracion(){
		//print_r($this->session->userdata);
		$data["distribuidor"] = $this->crm_model->get_info_distribuidor_by_id();
		$data["vendedores"] = $this->crm_model->get_vendedores();
		//$data["clientes"] = $this->crm_model->get_clientes_distribuidor();
		$this->layout->template('distribuidores_vendty')->show('distribuidores/configuracion',$data);		
	}


	/* Load View */
	public function nuevousuario(){
		$data["creation_distribuidor"] = $this->crm_model->get_distribuidor_by_id();
		$this->layout->template('distribuidores_vendty')->show('distribuidores/nuevousuario',$data);	
	}

	public function guardar_configuracion(){
		
		$data_user_update = array(
			'username' => $this->input->post('username'),
			'first_name' => $this->input->post('first_name'),
			'email' => $this->input->post('email'),
			'company' => $this->input->post('company'),
			'phone' => $this->input->post('phone')
		);

		if($this->crm_model->update_configuracion($data_user_update)){
			$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado la informacion correctamente"));
		}else{
			$this->session->set_flashdata('error', custom_lang('sima_bill_created_message', "Error al momento de actualizar los datos"));
		}

		redirect('administracion_vendty/distribuidores/configuracion/');
	}

	public function usuario_licencias($db_config,$id_user){
		$data["info_cliente"] = $this->crm_model->get_info_cliente($id_user);
		$data["licencias_por_usuario"] = $this->crm_model->get_licencias_por_usuario($db_config);
		//print_r($data);
		$this->layout->template('distribuidores_vendty')->show('distribuidores/usuarios_licencias',$data);
		
		
	}
}
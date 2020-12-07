<?php 

class Administracion_clientes extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model('crm_model');
        $this->load->model('crm_licencia_model');
        $this->load->model('crm_facturas_model');
        $this->load->model('crm_pagos_licencias_model');
       	$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);

    }


    public function mis_licencias(){
    	$where_empresa = array('id_db_config'=>$this->session->userdata('db_config_id'));
    	$datos_empresa = $this->crm_model->get_empresas($where_empresa);
    	if($datos_empresa){
    		$where_licencias = array('idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes);
    		$licencias_empresa = $this->crm_licencia_model->get_licencias($where_licencias);
    		$licencias_devolver=array();
    		foreach ($licencias_empresa as $key => $value) {

    			if($value->estado_licencia == 1){
    				$estado_licencia='Activo';
    			}else{
    				$estado_licencia='Inactivo';
    			}
    			
    			$almacen = $this->get_almacen_cliente($value->id_almacen);
    			$licencias_devolver[]=(object)array(
    									'id_licencia' => $value->idlicencias_empresa,
    									'nombre_plan'=>$value->nombre_plan,
    									'nombre_almacen' => $almacen['nombre'],
    									'fecha_inicio_licencia'   => $value->fecha_inicio_licencia,
    									'fecha_vencimiento_licencia' => $value->fecha_vencimiento,
    									'estado_licencia' => $estado_licencia,
    									'dias_vencimiento' =>$value->dias_pago

    				);
    		}
            $data['planes'] = $this->crm_model->get_planes(array('activo_plan'=>1));
            $data['almacenes'] = $this->almacenes_model->get_almacenes_activos();
            $data['formas_pago'] = $this->crm_model->get_formas_pago(array('activo_forma'=>1));

    		$this->layout->template('member')->show('administracion_licencia/licencias_clientes',array('licencias'=>$licencias_devolver,'data'=>$data));

    	}else{
    		$this->session->set_flashdata('message', custom_lang('no_existe_empresa', 'No se encuentra configuracion de empresa, comuniquese con vendty para la creacion de empresa'));

            redirect('configuracion/mis_planes');
    	}
    }

    public function get_almacen_cliente($id){
    	$datos_almacen = $this->almacenes_model->get_by_id($id);
		return $datos_almacen;

    }

    public function facturas_pendientes(){
    	$where_empresa = array('id_db_config'=>$this->session->userdata('db_config_id'));
    	$datos_empresa = $this->crm_model->get_empresas($where_empresa);
    	if($datos_empresa){
    		$where_facturas = array('estado_factura'=>1,'crm_factura_licencia.idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes);
    		$facturas = $this->crm_facturas_model->get_facturas($where_facturas);
    		
    		$this->layout->template('member')->show('administracion_licencia/facturas_pendientes_cliente',array('facturas'=>$facturas));

    	}else{
    		$this->session->set_flashdata('message', custom_lang('no_existe_empresa', 'No se encuentra configuracion de empresa, comuniquese con vendty para la creacion de empresa'));
            redirect('configuracion/mis_planes');	
    	}
    }

    public function mis_pagos(){
        $where_empresa = array('id_db_config'=>$this->session->userdata('db_config_id'));
        $datos_empresa = $this->crm_model->get_empresas($where_empresa);
        if($datos_empresa){
            $pagos = $this->crm_pagos_licencias_model->get_pagos(array('crm_factura_licencia.idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes),array('crm_orden_licencia.idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes));
            
            $this->layout->template('member')->show('administracion_licencia/pagos_cliente',array('pagos'=>$pagos));

        }else{
            $this->session->set_flashdata('message', custom_lang('no_existe_empresa', 'No se encuentra configuracion de empresa, comuniquese con vendty para la creacion de empresa'));
            redirect('configuracion/mis_planes');   
        }   
    }

    public function activar_licencia_cliente(){
        $this->form_validation->set_rules('s_plan', 'Plan', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('s_almacen', 'Almacen', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('t_fecha_pago','Fecha de pago','required');
        $this->form_validation->set_rules('s_forma_pago','Fecha de pago','required|is_natural_no_zero');
        $validation = (object)array('success'=>false,'message'=>'');
        if($this->form_validation->run()==true){
            $id_licencia = $this->crear_licencia_cliente();
            $id_orden_compra = $this->crear_orden_compra($id_licencia);
            $this->crear_pago_cliente($id_orden_compra);
            
            $validation->success = true;
        }else{
            $validation->message = validation_errors();
        }

        echo json_encode($validation);
    }

    public function crear_orden_compra($id_licencia){
        $data_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia));
        $datos_plan = $this->crm_model->get_planes(array('id'=>$this->input->post('s_plan')));
        $where_empresa = array('id_db_config'=>$this->session->userdata('db_config_id'));
        $datos_empresa = $this->crm_model->get_empresas($where_empresa);

        $data_orden = array(
                'fecha_creacion' => date("Y-m-d h:i:s"),
                'fecha_orden'    => date("Y-m-d"),
                'total_orden'    => $datos_plan[0]->valor_final,
                'fecha_vencimiento' => date("Y-m-d",strtotime("+5 days")),
                'idempresas_clientes' => $datos_empresa[0]->idempresas_clientes,
                'estado_orden'   => 2   
            );
        $id_orden = $this->crm_model->agregar_orden_compra($data_orden);
        $detalle_orden = array(
                'id_orden_licencia' => $id_orden,
                'nombre_licencia_orden' => $datos_plan[0]->nombre_plan,
                'cantidad_licencia_orden' => 1,
                'datos_licencia_json' => json_encode($data_licencia)
            );
        $this->crm_model->agregar_detalle_orden_compra($detalle_orden);

        return $id_orden;
    }

    public function crear_licencia_cliente(){
        $where_empresa = array('id_db_config'=>$this->session->userdata('db_config_id'));
        $datos_empresa = $this->crm_model->get_empresas($where_empresa);
        $datos_plan = $this->crm_model->get_planes(array('id'=>$this->input->post('s_plan')));
        $fecha_fin = date("Y-m-d",strtotime("+".$datos_plan[0]->dias_vigencia." days"));
        $data_licencia = array( 
                      'idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes,
                      'planes_id'          =>$this->input->post('s_plan'),
                      'fecha_creacion'     => date("Y-m-d h:i:s"),
                      'creado_por'         => $this->ion_auth->get_user_id(),
                      'fecha_inicio_licencia'=>date("Y-m-d"),
                      'fecha_vencimiento'   => $fecha_fin,     
                      'id_db_config'        =>$this->session->userdata('db_config_id'),
                      'id_almacen'         => $this->input->post('s_almacen'),
                      'estado_licencia'    => 2  
            );

        $id_licencia = $this->crm_licencia_model->agregar_licencia($data_licencia);

        return $id_licencia;
    }

    public function crear_pago_cliente($id_orden){
        $datos_plan = $this->crm_model->get_planes(array('id'=>$this->input->post('s_plan')));
        $data_pago = array(
                'fecha_creacion' => date("Y-m-d h:i:s"),
                'creado_por'     => $this->ion_auth->get_user_id(),
                'idformas_pago'  => $this->input->post('s_forma_pago'),
                'fecha_pago'     => $this->input->post('t_fecha_pago'),
                'monto_pago'     => $datos_plan[0]->valor_final,
                'estado_pago'    => 4,
                'id_orden_licencia'=>$id_orden
            );
        return $this->crm_model->agrear_pago($data_pago);
    }
}

?>
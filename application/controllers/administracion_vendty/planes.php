<?php 
/**
 *  
 */
Class Planes extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
		$this->load->model('crm_model');
		$this->load->model('crm_planes_model');
		$this->load->model('crm_licencias_empresa_model');
        
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
              redirect("frontend/index");
        }
	}

    public function index(){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}
		
		$planes = $this->crm_planes_model->get_planes_All();
        
        $data['planes']=$planes;
		$this->layout->template('administracion_vendty')->show('administracion_licencia/planes/index',array('data' => $data));
    }

    public function _callback_column_activo_plan($value,$row){
    	if($value == 0){
    		return 'Deshabilitado';
    	}else{
    		return 'Habilitado';
    	}
	}

	public function consultar_plan(){
		$empresa = $this->input->post('id_empresa');
		$almacen = $this->input->post('id_almacen');
		echo json_encode($this->crm_licencias_empresa_model->validar_licencia($empresa,$almacen)); 
	}

	public function nuevo(){
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}

		if ($this->ion_auth->in_group(5)) {
			//print_r($_POST); die();
			if($_POST){
				switch ($this->input->post('dias_vigencia')) {
					case '30':
						$recordar=7;
						break;

					case '90':
						$recordar=30;
						break;
					
					case '180':
						$recordar=30;
						break;

					case '365':
						$recordar=30;
						break;
					
					default:
						$recordar=7;
						break;
				}
							
				$data=array(
					'descripcion' =>$this->input->post('descripcion_plan'),
					'nombre_plan' =>$this->input->post('descripcion_plan'),
					'dias_vigencia' =>$this->input->post('dias_vigencia'),
					'valor_plan' =>$this->input->post('valor_plan'),
					'iva_plan' =>$this->input->post('iva_plan'),
					'valor_final' =>$this->input->post('valor_final'),
					'dias_bloqueo_cuenta' =>5,
					'activo_plan' =>1,
					'comienzo_dias_recordacion' =>$recordar,
					'periodicidad_dias_recordacion' =>5,
					'mostrar' =>$this->input->post('mostrar'),
					'promocion' =>$this->input->post('promocion'),
					'orden_mostrar' =>$this->input->post('orden_mostrar'),
					'tipo_plan' =>$this->input->post('tipo_plan'),
					'valor_plan_dolares' =>$this->input->post('valor_plan_dolares')
				);

				//se ingresa el plan
				$id_plan=$this->crm_planes_model->add($data);

				if(!empty($id_plan)){
					if(!empty($this->input->post('bodegas'))){
						$detalle=array(
							'id_plan' => $id_plan,
							'nombre_campo' =>'bodegas',
							'valor' => $this->input->post('bodegas')
						);
						//se ingresa detalle
						$this->crm_planes_model->add_detalle_plan($detalle);
					}

					if(!empty($this->input->post('usuarios'))){
						$detalle=array(
							'id_plan' => $id_plan,
							'nombre_campo' =>'usuarios',
							'valor' => $this->input->post('usuarios')
						);
						//se ingresa detalle
						$this->crm_planes_model->add_detalle_plan($detalle);
					}

					if(!empty($this->input->post('cajas'))){
						$detalle=array(
							'id_plan' => $id_plan,
							'nombre_campo' =>'cajas',
							'valor' => $this->input->post('cajas')
						);
						//se ingresa detalle
						$this->crm_planes_model->add_detalle_plan($detalle);
					}

					$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Plan Creado con Éxito'));
					$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'success'));
					redirect('administracion_vendty/planes/index');
				}else{
					$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Plan no pudo ser Creado'));
					$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'error'));
					redirect('administracion_vendty/planes/index');
				}
				
			}else{			
				$data=array();
				$this->layout->template('administracion_vendty')->show('administracion_licencia/planes/nuevo',array('data' => $data));
			}
		}else{
            redirect("frontend/index");
        }
	}

	public function editar($id){
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}
		
		if ($this->ion_auth->in_group(5)) {
			if(!empty($id)){
		
				if($_POST){
					switch ($this->input->post('dias_vigencia')) {
						case '30':
							$recordar=7;
							break;

						case '90':
							$recordar=30;
							break;
						
						case '180':
							$recordar=30;
							break;

						case '365':
							$recordar=30;
							break;
						
						default:
							$recordar=7;
							break;
					}
								
					$data=array(
						'descripcion' =>$this->input->post('descripcion_plan'),
						'nombre_plan' =>$this->input->post('descripcion_plan'),
						'dias_vigencia' =>$this->input->post('dias_vigencia'),
						'valor_plan' =>$this->input->post('valor_plan'),
						'iva_plan' =>$this->input->post('iva_plan'),
						'valor_final' =>$this->input->post('valor_final'),
						'dias_bloqueo_cuenta' =>5,
						'activo_plan' =>1,
						'comienzo_dias_recordacion' =>$recordar,
						'periodicidad_dias_recordacion' =>5,
						'mostrar' =>$this->input->post('mostrar'),
						'promocion' =>($this->input->post('promocion')==0)? NULL : $this->input->post('promocion'),
						'orden_mostrar' =>($this->input->post('orden_mostrar')==0)? NULL : $this->input->post('orden_mostrar'),
						'tipo_plan' =>$this->input->post('tipo_plan'),
						'valor_plan_dolares' =>$this->input->post('valor_plan_dolares')
					);

					//print_r($data); die();
					//se actualiza el plan
					$this->crm_planes_model->update($data,array('id'=>$id));

					if(!empty($id)){
						if(!empty($this->input->post('bodegas'))){
							$detalle=array(								
								'valor' => $this->input->post('bodegas')
							);
							//se actualiza detalle
							$this->crm_planes_model->update_detalle($detalle,array('id_plan'=>$id,'nombre_campo'=>'bodegas'));
						}

						if(!empty($this->input->post('usuarios'))){
							$detalle=array(								
								'valor' => $this->input->post('usuarios')
							);
							//se actualiza detalle
							$this->crm_planes_model->update_detalle($detalle,array('id_plan'=>$id,'nombre_campo'=>'usuarios'));
						}

						if(!empty($this->input->post('cajas'))){
							$detalle=array(								
								'valor' => $this->input->post('cajas')
							);
							//se actualiza detalle
							$this->crm_planes_model->update_detalle($detalle,array('id_plan'=>$id,'nombre_campo'=>'cajas'));
						}

						$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Plan Actualizado con Éxito'));
						$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'success'));
						redirect('administracion_vendty/planes/index');
					}else{
						$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Plan no pudo ser Actualizado'));
						$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'error'));
						redirect('administracion_vendty/planes/index');
					}
					
				}else{					
					$data=array();
					$data['plan']=$this->crm_planes_model->get_plan(array('id'=>$id));
					$data['detalle_plan']=$this->crm_planes_model->get_detalle_plan(array('id_plan'=>$id));
					$this->layout->template('administracion_vendty')->show('administracion_licencia/planes/editar',array('data' => $data));					
				}
			}else{
				$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Seleccione un Plan'));
				$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'error'));
				redirect('administracion_vendty/planes/index');
			}
		}else{
            redirect("frontend/index");
        }
	}

	public function inactivar($id){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}

		if ($this->ion_auth->in_group(5)) {
			if(!empty($id)){
				//verificar si se puede inactivar
				$this->crm_planes_model->delete(array('id'=>$id));
			}else{
				$this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Seleccione un Plan'));
				$this->session->set_flashdata('message_type', custom_lang('sima_client_created_message', 'error'));
				redirect('administracion_vendty/planes/index');
			}
		}else{
            redirect("frontend/index");
        }
	}
	
}
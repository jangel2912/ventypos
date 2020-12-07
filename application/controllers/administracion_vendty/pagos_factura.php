<?php
class Pagos_factura extends CI_controller {
    
       function __construct()
	{
		parent::__construct();
		//$this->load->library('grocery_CRUD'); 
		$this->load->model('crm_model');
		$this->load->model("licencia_model");
		$this->load->model("crm_pagos_licencias_model");
		$this->load->model("crm_licencias_empresa_model");
		$this->load->model("crm_licencia_model");
        
	}

    public function index($estado = 0){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {    
			
			if ($estado == -1) {
				$where=array('id_factura_licencia'=>NULL); 
                $groupby='fecha_pago, id_licencia,estado_pago,idformas_pago';     
				$pagos = $this->crm_model->ver_pagos($where,$groupby);				
				$data['facturas']=0;
				
			}
			else{
				$pagos = $this->crm_model->ver_pagos_facturas();
			}          
         
            $data['pagos']=$pagos;
            
            $this->layout->template('administracion_vendty')->show('administracion_licencia/pagos/index',array('data' => $data));
        }else{
            redirect("frontend/index");
        }
		
		/*
        $crud = new grocery_CRUD();
        $crud->set_subject('Pago');
		$crud->set_table('crm_pagos_licencias');
        $crud->set_relation('idformas_pago','crm_formas_pago','nombre_cuenta');
        $crud->columns('idpagos_licencias','idformas_pago','fecha_pago','monto_pago','estado_pago','fecha_conciliacion');
        $crud->display_as('idpagos_licencias','#');
        $crud->display_as('idformas_pago','Forma de pago');
        $crud->callback_column('monto_pago',array($this,'_callback_column_monto'));
        //$crud->callback_column('estado_pago',array($this,'_callback_column_estado'));
        $crud->field_type('estado_pago','dropdown',array('1' => 'Liquidado', '2' => 'consolidado','3' => 'Rechazado' , '4' => 'Pendiente'));
        $crud->callback_delete(array($this,'_callback_rechazar_pago'));
        $crud->edit_fields('monto_pago','estado_pago','fecha_conciliacion');
		$output = $crud->render();
		$this->layout->template('administracion_vendty')->show('administracion_licencia/gc_example',array('gc' => $output));
		*/
	}
	
	public function nuevo(){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {  

			if(!empty($this->uri->segment(4))){
				$id_licencia=$this->uri->segment(4);				
				$empresa=$this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$id_licencia));
				
				$data['idempresas_clientes']=$empresa[0]->idempresas_clientes;
				$data['idplan']= $empresa[0]->id_plan;
				$data['id_almacen']=$empresa[0]->id_almacen;					
			}else{
				$data['idempresas_clientes']=0;
				$data['id_almacen']=0;
				$data['idplan']=0;
			}

			$data['empresas'] = $this->crm_model->get_empresas(array('nombre_empresa !='=>""));             		    
			$data['formaspagos'] = $this->crm_model->get_formas_pago();	
			$data['planes'] =  $this->crm_model->get_planes_All();
			
			if (isset($_POST['formapago'])) {			
				if ($this->form_validation->run('pagos_licencias') == true) {
					//buscamos la licencia
					$empresa=$this->input->post('idempresas_clientes');
					$almacen=$this->input->post('id_almacen');
					$plan_id=$this->input->post('plan_id');
					$id_licencia=$this->crm_model->get_usuario_renovacion(array('id_almacen'=>$almacen,'idempresas_clientes'=>$empresa)); 
										
					$data = array(
                            'fecha_creacion' =>  date('Y-m-d H:i:s')
                            ,'fecha_modificacion' =>  date('Y-m-d H:i:s')
                            ,'creado_por' => $this->session->userdata('user_id')
                            ,'modificado_por' => $this->session->userdata('user_id')
                            ,'idformas_pago' => $this->input->post('formapago')
                            ,'fecha_pago' => $this->input->post('fecha_pago')
                            ,'monto_pago' => $this->input->post('valorpago')
                            ,'estado_pago' =>  $this->input->post('estado') 
                            ,'fecha_conciliacion' =>  date('Y-m-d H:i:s')                            
                            ,'observacion_pago' =>  ($this->input->post('estado')==1)? '00-Aprobada' : 'Pendiente por Cancelar'
                            ,'info_adicional_pago' =>  "Pago Ingresado Manual"                            
							,'id_licencia' =>  $id_licencia[0]->id_licencia
							,'descuento_pago' =>  $this->input->post('descuento')
							,'retencion_pago' =>  $this->input->post('retencion')
							,'transaction_id' =>  $this->input->post('transaction_id')
							,'ref_payco' =>  $this->input->post('ref_payco')

						);
					
					////////******************* */
					$planActual=$this->licencia_model->getPlanActual($id_licencia[0]->id_licencia);
					//se crea el pago											
					$pago=$this->licencia_model->insertPagoLicenciaManual($data);					
					
					$migrar=0; 	
					
					$this->load->library('../controllers/job');						
					$email = new Job();
					
					if($this->input->post('estado')== 1){
						//cambiar esto de bd					
						$bduser=$this->licencia_model->buscarBD($id_licencia[0]->id_licencia);
						$idbd=$bduser[0]['id'];
            			$nombrebd=$bduser[0]['base_dato'];          
						$email1=explode("vendty2_db_",$nombrebd);  

						if(($migrar==0)&&($planActual==1)){    
							//PASAR A PRODUCCIÓN 						
							//$this->licencia_model->produccion($nombrebd);  
							$data = array(
								'origen' => 2,
								'destino' => 8,
								'dbname' => $email1[1]
							);
						   
							$migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api')); 
							if(isset($migrada->status) && isset($migrada->description)){
								if(!$migrada->status && $migrada->description == "Verifica los datos enviados"){
									$migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
								}
							} else {
								$migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
							}
							if($migrada->status){
								if($migrada->description=='ok'){
									$this->licencia_model->updateEstadoBD($idbd);  
									//modifico las fechas licencia
									$plan=$this->crm_model->get_planes(array('id'=>$plan_id));
									$tiempo=$plan[0]->dias_vigencia;							
									$this->licencia_model->updateLicencianuevo($id_licencia[0]->id_licencia, $plan_id,$tiempo);    
									//email de bienvenida                                 
									$email->BienvenidoaVendty($idbd); 
								}
							}
							
						}else{
							$this->licencia_model->updateEstadoBD2($idbd);
						}    

						//email pago
						$email->emailConfirmarPago($id_licencia[0]->id_licencia);
						//generando la factura
						require_once('facturas_licencia.php');				
						$factura = new Facturas_licencia();
						//$facturag=$factura->generar_factura_de_licencia($id_licencia[0]->id_licencia,$pago);
						$facturag = $factura->generar_factura_electronica($id_licencia[0]->id_licencia,$pago);  
						if(!empty($facturag)){
							$email->emailFacturaPago($facturag);
							//cambiar las fechas de licencias de bodegas
							//Cambiar las fechas de bodegas si las hubiera
							$sqlbodegas="SELECT * FROM ".$bduser[0]['base_dato'].".almacen WHERE bodega=1";
							$bodegas=$this->db->query($sqlbodegas)->result_array();  

							//$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia[0]->id_licencia));
							$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia[0]->id_licencia));	
							
                            if(!empty($bodegas)){
                                //busco detalle del plan
                                $detalle_plan=$this->crm_model->get_detalle_plan("where id_plan=".$plan_id." and nombre_campo='bodegas'");                    
                                $i=0;
                                $cantidadPlanBodega=!empty($detalle_plan[0]->valor)?$detalle_plan[0]->valor:0;
                                //busco licencia asociada a ese almacen
                                foreach ($bodegas as $key => $value) {
                                
                                    if($i<$cantidadPlanBodega){                                                    
                                        //licencias asociadas
                                        $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config'=>$idbd,'id_almacen'=>$value['id']));	 
                                        
                                        if(empty($datos_licencia_bodegas)){//se crea la licencia asociada                                
                                            $planB=17;
                                            switch ($datos_licencia[0]->dias_vigencia) {
                                                case 30:
                                                    $planB=17;
                                                    break;
                                                case 90:
                                                    $planB=16;
                                                    break;
                                                case 365:
                                                    $planB=15;
                                                    break;
                                                default:
                                                    $planB=16;
                                                    break;
                                            }
                                        
                                            $datosli=array(
                                                'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                                ,'planes_id' => $planB
                                                ,'fecha_creacion' => date('Y-m-d H:i:s')
                                                ,'creado_por' =>$datos_licencia[0]->creado_por
                                                ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento
                                                ,'id_db_config' => $idbd
                                                ,'id_almacen' => $value['id']
                                                ,'estado_licencia' => 1                                
                                            );
                                            $this->crm_licencia_model->agregar_licencia($datosli);
                                        }else{
                                            //cambiar las fechas                         
                                            $datosli=array(  
                                                'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                                ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento      
                                                ,'fecha_modificacion' => date('Y-m-d H:i:s')                          
                                                ,'estado_licencia' => 1                                                                
                                            );
                                        
                                            $this->crm_licencias_empresa_model->update($datosli);
                                        }
                                    }                       
                                    $i++;
                                }
                            }
						}
					}

					if(!empty($facturag)){
						$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha creado el pago y generado factura exitosamente"));
						$this->session->set_flashdata('message_type', custom_lang('success','success'));
					}
					else{
						
						if(!empty($pago)){
							$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha creado exitosamente el pago"));
							$this->session->set_flashdata('message_type', custom_lang('success','success'));
						}else{
							$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "No se pudo crear el pago"));
							$this->session->set_flashdata('message_type', custom_lang('error','error'));
						}
						
					}
					
                	 redirect("administracion_vendty/pagos_factura/");
				}
			}

						
			$this->layout->template('administracion_vendty')->show('administracion_licencia/pagos/nuevo',array('data' => $data));
		}else{
            redirect("frontend/index");
        }
	}

	public function editar($id){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {  			
			$data['empresas'] = $this->crm_model->get_empresas(array('nombre_empresa !='=>""));             		    
			$data['formaspagos'] = $this->crm_model->get_formas_pago();			
			$data['datos_pago'] = $this->crm_pagos_licencias_model->ver_pagos(array('idpagos_licencias'=>$id));
			$data['planes'] =  $this->crm_model->get_planes_All();			
			$empresa=$this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$data['datos_pago'][0]['id_licencia']));			
			$data['idplan']= $empresa[0]->id_plan;
			if (isset($_POST['estado'])) {	
				
				if (!empty($this->input->post('estado'))) {
					//buscamos la licencia
					$id_pago=$id;
					$plan_id=$this->input->post('plan_id');
					$estado=$this->input->post('estado');
					$monto_pago=$this->input->post('valorpago');
					$transaction_id=$this->input->post('transaction_id');
					$ref_payco=$this->input->post('ref_payco');
					$descuento=$this->input->post('descuento');
					$retencion_pago=$this->input->post('retencion');
					$id_licencia=$data['datos_pago'][0]['id_licencia'];					
					$planActual=$this->licencia_model->getPlanActual($id_licencia);	
					$dataupdate = array(
						'estado_pago'=>$estado,
						'monto_pago'=>$monto_pago,
						'transaction_id'=>$transaction_id,
						'ref_payco'=>$ref_payco,
						'descuento_pago'=>$descuento,
						'retencion_pago'=>$retencion_pago
					);
					
					//se ubica el pago						
					$pago=$id;
					$migrar=0; 	
					$idbd="";
					
					$this->load->library('../controllers/job');						
					$email = new Job();

					if($this->input->post('estado')== 1){
						
						$bduser=$this->licencia_model->buscarBD($id_licencia);
						$idbd=$bduser[0]['id'];   
						$nombrebd=$bduser[0]['base_dato'];
						$email1=explode("vendty2_db_",$nombrebd);
						
						if(($migrar==0)&&($planActual==1)){    
							//PASAR A PRODUCCIÓN 			
							//$this->licencia_model->produccion($nombrebd);   
							$data = array(
								'origen' => 2,
								'destino' => 8,
								'dbname' => $email1[1]
							);
						   
							$migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api'));
							
							if(isset($migrada->status) && isset($migrada->description)){
								if(!$migrada->status && $migrada->description == "Verifica los datos enviados"){
									$migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
								}
							} else {
								$migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
							}

							if($migrada->status){
								if($migrada->description=='ok'){
									$this->licencia_model->updateEstadoBD($idbd);  
									//modifico las fechas licencia
									$plan=$this->crm_model->get_planes(array('id'=>$plan_id));
									$tiempo=$plan[0]->dias_vigencia;							
									$this->licencia_model->updateLicencianuevo($id_licencia, $plan_id,$tiempo); 
									//email de bienvenida                                 
									$email->BienvenidoaVendty($idbd); 
								}
							}

						}else{
							$this->licencia_model->updateEstadoBD2($idbd);
						}    

						$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias'=>$id),$dataupdate);						
						
						$datos_pago=$this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias'=>$id));
						
						//email pago
						$email->emailConfirmarPago($id_licencia);
						//generando la factura
						require_once('facturas_licencia.php');				
						$factura = new Facturas_licencia();
						//$facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
						$facturag = $factura->generar_factura_electronica($id_licencia,$pago); 
						if(!empty($facturag)){
							$email->emailFacturaPago($facturag);
							//cambiar las fechas de las licencias	
							if($planActual!=1){
								$data = array(
									'idpagos_licencias' => $datos_pago[0]->idpagos_licencias
									,'fecha_creacion' =>  $datos_pago[0]->fecha_creacion
									,'fecha_modificacion' =>  $datos_pago[0]->fecha_modificacion
									,'creado_por' => $datos_pago[0]->creado_por
									,'modificado_por' => $datos_pago[0]->modificado_por
									,'idformas_pago' => $datos_pago[0]->idformas_pago
									,'fecha_pago' => $datos_pago[0]->fecha_pago
									,'monto_pago' => $datos_pago[0]->monto_pago
									,'estado_pago' =>  $datos_pago[0]->estado_pago 
									,'fecha_conciliacion' =>  date('Y-m-d H:i:s')                            
									,'observacion_pago' =>  ($this->input->post('estado')==1)? '00-Aprobada' : 'Pendiente por Cancelar'
									,'info_adicional_pago' =>  "Pago Ingresado Manual"  
									,'id_licencia' => $datos_pago[0]->id_licencia
									,'descuento_pago' =>  $datos_pago[0]->descuento_pago
									,'retencion_pago' =>  $datos_pago[0]->retencion_pago
									,'transaction_id' =>  $datos_pago[0]->transaction_id
									,'ref_payco' =>  $datos_pago[0]->ref_payco
									,'id_factura_licencia' =>  $facturag
								);		

							//elimino el pago
							  
								$delete_pago=$this->crm_pagos_licencias_model->delete_pago(array('idpagos_licencias'=>$datos_pago[0]->idpagos_licencias));				
								if($delete_pago==1){
									//se crea el pago nuevamente						
									$this->licencia_model->insertPagoLicenciaManual($data);
								}
							}							
						}						
					}else{
						$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias'=>$id),$dataupdate);
					}

					if(!empty($facturag)){
						$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha editado el pago y generado factura exitosamente"));
						$this->session->set_flashdata('message_type', custom_lang('success','success'));
					}
					else{						
						if(!empty($pago)){
							$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha editado exitosamente el pago"));
							$this->session->set_flashdata('message_type', custom_lang('success','success'));
						}else{
							$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "No se pudo editar el pago"));
							$this->session->set_flashdata('message_type', custom_lang('error','error'));
						}						
					}					
                	 redirect("administracion_vendty/pagos_factura/");
				}
			}
						
			$this->layout->template('administracion_vendty')->show('administracion_licencia/pagos/editar',array('data' => $data));
		}else{
            redirect("frontend/index");
        }
	}

	public function eliminar($id){

		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {  			
			$delete_pago=$this->crm_pagos_licencias_model->delete_pago(array('idpagos_licencias'=>$id));	
			if($delete_pago){
				$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha eliminado el pago exitosamente"));
				$this->session->set_flashdata('message_type', custom_lang('success','success'));
			}else{
				$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "No se ha podido eliminar el pago, tiene una factura asociada"));
				$this->session->set_flashdata('message_type', custom_lang('success','success'));
			}
			redirect("administracion_vendty/pagos_factura/");
		}else{
            redirect("frontend/index");
        }
	}
	
	public function ver_pagos($id){
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {  			
				
			$where=array('id_licencia'=>$id); 
			$data['pagos']=$this->crm_model->ver_pagos($where,0);
			$data['facturas']=$this->crm_model->ver_pagos_facturas(array('id_licencia'=>$id));
	/********** */
					
				/*   $groupby='fecha_pago, id_licencia,estado_pago,idformas_pago';     
					$pagos = 				
					$data['facturas']=0;*/
			$data['empresa']=$this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$id));
			
			$this->layout->template('administracion_vendty')->show('administracion_licencia/pagos/ver_pagos_licencia',array('data' => $data));
		}
		else{
            redirect("frontend/index");
        }
	}

	public function ver_pago($id){
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) {  
			$where=array('idpagos_licencias'=>$id); 
			$data['pagos']=$this->crm_model->ver_pagos($where,0);
			$data['facturas']=$this->crm_model->ver_pagos_facturas(array('id_licencia'=>$id));	
			$data['empresa']=$this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$id));
			
			$this->layout->template('administracion_vendty')->show('administracion_licencia/pagos/ver_pagos_licencia',array('data' => $data));
		}else{
            redirect("frontend/index");
        }
	}


    public function _callback_rechazar_pago($primary_key){
    	$data=array('estado_pago'=>3);
    	$this->db->where(array('idpagos_licencias'=>$primary_key));
    	return $this->db->update('crm_pagos_licencias',$data);
    }

    public function _callback_column_monto($value,$row){
    	return '$'.number_format($value);
    }

    public function _callback_column_estado($value,$row){
    	$estado = '';
    	switch ($value) {
    			case 1:
    				$estado = 'Liquidado';
    				break;
    			case 2:
    				$estado = 'consolidado';
    				break;
    			case 3:
    				$estado = 'Rechazado';	
    				break;
    			case 4:
    				$estado = 'Pendiente';	
    			default:
    				$estado = '';
    				break;
    		}
    	return $estado;		
    }
}
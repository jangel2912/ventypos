<?php 

class Licencia_empresa extends CI_Controller
{
	var $dbConnection;

	function __construct()
	{
		parent::__construct();
		//$this->load->library('grocery_CRUD'); 
		$this->load->model('crm_model');
        $this->load->model("pais_model");
        $this->load->model("usuarios_model");        
        $this->load->model("crm_licencias_empresa_model");
		
                
        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }
	}

	public function get_ajax_data_licencias(){
        if ($this->ion_auth->in_group(5)) {		
            
            $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_licencias_empresa_model->get_ajax_data_licencias(false)));
        }else{
            redirect("frontend/index");
        }
    }

	public function index(){
		
		if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }
        
        if ($this->ion_auth->in_group(5)) {		
            /*$datoslicencias = $this->crm_licencias_empresa_model->get_all(false);           
            $data['datoslicencias']=$datoslicencias;
            $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/index',array('data' => $data));*/
            $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/index');
        }else{
            redirect("frontend/index");
        }
    }
    
    public function nuevo($id){
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }  

        if ($this->ion_auth->in_group(5)) { 
    
           if(!empty($id)){
                if ($this->form_validation->run('licencias_empresas') == true) {
                    
                    $empresa = $this->crm_model->get_empresas(array('idempresas_clientes'=>$this->input->post('idempresas_clientes')));               
                
                    $existe_licencia = $this->crm_licencias_empresa_model->validar_licencia($this->input->post('idempresas_clientes'),$this->input->post('id_almacen'));

                    if($existe_licencia == NULL){
                        $datos = array(                
                            'idempresas_clientes' =>  $this->input->post('idempresas_clientes')
                            ,'planes_id' => $this->input->post('id_plan')
                            ,'fecha_creacion' => date('Y-m-d H:i:s')
                            ,'fecha_modificacion' => date('Y-m-d H:i:s')
                            ,'creado_por' => $this->session->userdata('user_id')
                            ,'fecha_inicio_licencia' => $this->input->post('fecha_inicio_licencia')                    
                            ,'fecha_vencimiento' =>   $this->input->post('fecha_vencimiento') 
                            ,'estado_licencia' =>  $this->input->post('estado_licencia')                    
                            ,'id_db_config' =>  $empresa[0]->id_db_config                   
                            ,'id_almacen' =>  $this->input->post('id_almacen')   
                            ,'fecha_activacion' =>  date('Y-m-d')                                                      
                        );

                        $id_licencia = $this->crm_licencias_empresa_model->add($datos);
                    
                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La licencia se ha creado correctamente'));
                        redirect('administracion_vendty/licencia_empresa/');
                    }
                }else{                         
                    $data['empresas'] = $this->crm_model->get_empresas(array('idempresas_clientes'=>$id),1);                    
                    $data['planes'] = $this->crm_model->get_planes_All();
                    $data['id_empresa'] = $id;
                    $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/nuevo',array('data' => $data));
                }
           }else{
                redirect("administracion_vendty/licencia_empresa/");
           }            
        }else{
            redirect("frontend/index");
        }
    }
    
    public function editar($id){
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }  

        if ($this->ion_auth->in_group(5)) {            
            
            if ($this->form_validation->run('licencias_empresas') == true) {
                $datos = array(       
                    'idlicencias_empresa' =>  $this->input->post('idlicencias_empresa')
                    ,'planes_id' => $this->input->post('id_plan')
                    ,'fecha_inicio_licencia' => $this->input->post('fecha_inicio_licencia')                    
                    ,'fecha_vencimiento' =>  $this->input->post('fecha_vencimiento') 
                    ,'fecha_activacion' =>  $this->input->post('fecha_activacion') 
                    ,'estado_licencia' =>  $this->input->post('estado_licencia')  
                );
               
                $this->crm_licencias_empresa_model->update($datos);
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La licencia se ha modificada correctamente'));
                $this->session->set_flashdata('message_type', custom_lang('sima_category_created_message', 'success'));
                redirect('administracion_vendty/licencia_empresa/');

            }
            else{                         
                $data['datoslicencias'] = $this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$id));            		    
                $data['planes'] = $this->crm_model->get_planes_All();

                $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/editar',array('data' => $data));
            }
        }else{
            redirect("frontend/index");
        }
    }
    
    public function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
           // $datoslicencias = $this->crm_licencia_model->get_by_id(array('idempresas_clientes'=>$id));
           
          //  if(count($datoslicencias)==0){
                $this->crm_licencias_empresa_model->delete($id);
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La Licencia fue eliminada correctamente'));
                redirect('administracion_vendty/licencia_empresa/');
         /*   }else{
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La Empresa no pudo ser eliminada tiene licencias asociadas'));
                redirect('administracion_vendty/empresas/');
            }         */      
        }
        else{
            redirect("frontend/index");
        }
    }

    public function desactivar($id){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
            $licencia = $this->crm_licencias_empresa_model->get_all_id(array('id_licencia'=>$id));            		    
                           
            $datos = array(       
                'idlicencias_empresa' =>  $id               
                ,'estado_licencia' =>  ($licencia[0]->estado_licencia==1) ? 15 : 1
            );
          
            $this->crm_licencias_empresa_model->update($datos);
            $estado=($licencia[0]->estado_licencia==1) ? "Suspendido" : "Activado"; 
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La licencia se ha '.$estado.' correctamente'));
            redirect('administracion_vendty/licencia_empresa/');

        }
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
                    $sheet = $objXLS->getSheet(0); 
                    $highestRow = $sheet->getHighestRow(); 
                    $highestColumn = $sheet->getHighestColumn();
                    $adicionados=0;
                    $errores_importar="";
                    $noadicionados=0;
                    $array_datos;
                    $count=2;

                    //recorremos el archivo
                    for ($row = 2; $row <= $highestRow; $row++){
                        //obtemos los valores
                        $empresa=$sheet->getCell("A".$row)->getValue();
                        $plan=$sheet->getCell("B".$row)->getValue();
                        $fecha_inicio=$sheet->getCell("C".$row)->getValue();
                        $fecha_fin=$sheet->getCell("D".$row)->getValue();
                        $almacen=$sheet->getCell("E".$row)->getValue();
                        $estado=$sheet->getCell("F".$row)->getValue();
                        
                        if(!empty($empresa)){

                            $count++;
                            $datos_empresa=$this->crm_model->get_empresas(array('nombre_empresa'=> $empresa));
                            $planes=$this->crm_model->get_planes(array('nombre_plan' => $plan));
                            $almacenes=$this->almacenxnombre($datos_empresa[0]->id_db_config,$datos_empresa[0]->idempresas_clientes,$almacen);
                                               
                            if((count($datos_empresa)>0)&&(!empty($almacenes))){		
                                
                                $licencia=$this->crm_licencias_empresa_model->get_all_id(array('idempresas_clientes'=>$datos_empresa[0]->idempresas_clientes,'id_almacen'=>$almacenes));
                                
                                if(count($licencia)>0){
                                    $noadicionados++;
                                    $errores_importar.="<p><span class='glyphicon glyphicon-remove'></span> La licencia para la empresa ".$empresa." con el almacen ".$almacen." ya existe</p>";
                                }else{
                                    $array_datos = array(
                                        'idempresas_clientes' => $datos_empresa[0]->idempresas_clientes,
                                        'planes_id' => $planes[0]->id,                      
                                        'fecha_inicio_licencia' => date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fecha_inicio + 1)),
                                        'fecha_vencimiento' =>date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fecha_fin + 1)),
                                        'id_db_config' => $datos_empresa[0]->id_db_config,
                                        'id_almacen' => $almacenes,
                                        'fecha_creacion' => date('Y-m-d H:i:s'),
                                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                                        'creado_por' => $this->session->userdata('user_id'),
                                        'estado_licencia' => ($estado=='activo') ?  1 : 15
                                    );
            
                                    if(!empty($array_datos['idempresas_clientes'])){                    
                                        $this->crm_licencias_empresa_model->add($array_datos);                                
                                        $adicionados++;
                                    }
                                }                                
        
                            }else{
                                if(empty($almacenes)){
                                    $errores_importar.="<p><span class='glyphicon glyphicon-remove'></span> Empresa".$empresa." el almacen: ".$almacen." no existe</p>";
                                }else{
                                    $errores_importar.="<p><span class='glyphicon glyphicon-remove'></span> La Empresa ".$empresa." no existe </p>";
                                }
                                
                                $noadicionados++;
                            }
                        }  
                    } 

                    $objXLS->disconnectWorksheets();
                    unset($objXLS);
                    $data['count'] = $count - 2;
                    $data['adicionados'] = $adicionados;
                    $data['noadicionados'] = $noadicionados;
                    $data['errores_importar'] = $errores_importar;
                    unlink("uploads/$excel_name");
                    $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/import_complete', array('data' => $data));                   
                }
            }else{
                $data['data']['upload_error'] = $error_upload;          
                $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/import_excel', array('data' => $data));
            }
        }else{
            $data['data']['upload_error'] = "Seleccione un archivo";          
            $this->layout->template('administracion_vendty')->show('administracion_licencia/licencias/import_excel', array('data' => $data));
        }       
    }   


	public function almacenxnombre($id_config, $empresa,$almacen){
		
    	$this->armar_conexion_bd_cliente($id_config,$empresa);
    	$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);
		$datos_almacen = $this->almacenes_model->get_by_name($almacen);	
		
		return $datos_almacen;
	}	



	function __callback_activa_desactivar_almacen($post_array,$primary_key)
	{				
		$this->armar_conexion_bd_cliente($post_array['id_db_config'], $post_array['idempresas_clientes']);
    	$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);

		$data['id']=$post_array['id_almacen'];
		if($post_array['estado_licencia']==15){			
			$data['estado']=0;
		}else{
			$data['estado']=1;
		}
		
		$datos_almacen = $this->almacenes_model->update_almacen_activo($data);		
		return true;
	}

	public function generar_url_factura($primary_key,$row){
		return site_url('administracion_vendty/facturas_licencia/generar_factura_de_licencia').'/'.$primary_key;
	}

	public function definir_db_config_callback($post_array,$primary_key=''){
        
        $this->db->where(array('idempresas_clientes'=>$post_array['idempresas_clientes']));
        $query = $this->db->get('crm_empresas_clientes');
        $id_db_config = 0;
        foreach ($query->result() as $key => $value) {
            $id_db_config = $value->id_db_config;
        }
        $post_array['id_db_config'] = $id_db_config;
        return $post_array;
    }

    public function _callback_column_almacen($value, $row){
    	$this->armar_conexion_bd_cliente($row->id_db_config,$row->idempresas_clientes);
    	$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);
		$datos_almacen = $this->almacenes_model->get_by_id($value);		
		return $datos_almacen['nombre'];
	}	
	
    public function _callback_column_db_config($value,$row){
    	$this->db->where(array('id'=>$value));
    	$datos_bd = $this->db->get('db_config')->row();
    	return $datos_bd->base_dato;
    }

    private function armar_conexion_bd_cliente($id_db_config,$idempresas_clientes){
    	
		$this->db->where(array('id'=>$id_db_config));
		$datos_db_config = $this->db->get('db_config')->row();
		if(!$datos_db_config){
			//var_dump($idempresas_clientes);die();
			$this->db->where(array('idempresas_clientes'=>$idempresas_clientes));
        	$query = $this->db->get('crm_empresas_clientes');
        	//var_dump($query->result());die();
        	foreach ($query->result() as $key => $value) {
            	$id_db_config = $value->id_db_config;
        	}
        	$this->db->where(array('id'=>$id_db_config));
			$datos_db_config = $this->db->get('db_config')->row();
		}
		$usuario = $datos_db_config->usuario;
        $clave = $datos_db_config->clave;
        $servidor = $datos_db_config->servidor;
        $base_dato = $datos_db_config->base_dato;
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
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

}

?>
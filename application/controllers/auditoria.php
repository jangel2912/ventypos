<?php 
class Auditoria extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();
        $this->user = $this->session->userdata('user_id');

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("auditoria_model","auditoria");
        $this->auditoria->initialize($this->dbConnection);
        $this->load->model("almacenes_model","almacenes");
        $this->almacenes->initialize($this->dbConnection);
        $this->load->model('productos_model','productos');
        $this->productos->initialize($this->dbConnection);
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $this->load->model('inventario_model','inventario');
        $this->inventario->initialize($this->dbConnection);
        //actualizar la tabla con los campos anulaciones
        $this->auditoria->camposanulaciones();
    }


    public function index($estado=0){
    	$this->auditoria->check_existe_tablas();
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }        
        
        if ($estado == -1) {
            $action = 'auditoria/auditorias_anuladas';
        }else{
            $action = "auditoria/index";
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show($action,array("data" => $data));

    }

    public function get_ajax_datatable() {

    	 if($this->session->userdata('is_admin') == 't'){
           $id_almacen = 0;
        }else{
        	$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        	
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->auditoria->get_ajax_datatable($id_almacen,0)));
    }

    public function get_ajax_datatable_anuladas() {

    	 if($this->session->userdata('is_admin') == 't'){
           $id_almacen = 0;
        }else{
        	$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        	
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->auditoria->get_ajax_datatable($id_almacen,-1)));
    }

    public function nuevo(){
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $data=array();
        if($this->session->userdata('is_admin') == 't'){
        	$data['almacenes'] = $this->almacenes->get_almacenes_activos();	
        }else{
        	$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        	$datos_almacen = $this->almacenes->get_by_id($id_almacen);
        	$data['nombre_almacen'] = $datos_almacen['nombre'];
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->js(base_url('public/js/auditoria_stock.js?1.2'))->show('auditoria/nuevo',array('data'=>$data));
    }


    public function guardar_auditoria(){
    	$resultado =(object) array('status'=>false);
    	
    	$this->form_validation->set_rules('t_nombre_auditoria', 'Nombre auditoria', 'required');
    	$this->form_validation->set_rules('productos', 'Nombre auditoria', 'required');

    	if($this->session->userdata('is_admin') == 't'){
    		$this->form_validation->set_rules('s_almacen', 'Almacen', 'required');	
    	}

    	if ($this->form_validation->run() == true)
    	{	
            //print_r($resultado);die();
            
            if (!empty($_FILES['f_archivo_soporte']['name'])) {
                $soporte_name = $this->cargar_archivo($resultado);
            }
            else{
                $soporte_name="";
            }
                        
    		if($this->session->userdata('is_admin') =='t'){
    			$id_almacen = $this->input->post('s_almacen');	
    		}else{
    			$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
    		}

    		$data=array(
    			 'fecha_creacion'=> date("Y-m-d h:i:s"),
    			 'creado_por' => $this->ion_auth->get_user_id(),
    			 'nombre_auditoria' => $this->input->post('t_nombre_auditoria'),
    			 'descripcion_auditoria' => $this->input->post('ta_descripcion_auditoria'), 
    			 'estado_auditoria' => $this->input->post("ch_ajustar")? 'Cerrada':'borrador', 
    			 'id_almacen' => $id_almacen, 
    			 'archivo_fisico' => $soporte_name,
    			);

            $da=0;
            $id=0;
            $detalle="";
            $iguales="";
    		$id_auditoria = $this->auditoria->insertar_auditoria($data);
    		if($id_auditoria >0 ){
                 //insertamos los detalles
                
                $data_detalle = array();
                $data_ajuste = array(); // Arreglo para ajustar la auditoria real con la existente en el sistema
                $productos_auditados = json_decode( $this->input->post('productos'));
                //print_r($productos_auditados);   die();            
    			foreach ($productos_auditados as $key => $value) {
                    $tipo_movimiento = '';
                    $data_detalle = array();
                    $stock_producto = $this->productos->obtener_existencias($value->id,$id_almacen);
                        
                    if((is_numeric($value->cantidad_contada))&& (($value->cantidad_contada>=0)) && ($stock_producto[0]['codigo']==$value->codigo)){
                                            
    				    $data_detalle[]= array(
    						'fecha_creacion'=> date("Y-m-d h:i:s"),
    			 			'creado_por' => $this->ion_auth->get_user_id(),
    			 			'id_auditoria' => $id_auditoria,
    			 			'producto_id' => $value->id,
    			 			'cantidad_contada' => (is_numeric($value->cantidad_contada))? $value->cantidad_contada:'x',
    			 			'cantidad_sistema' => (isset($stock_producto[0]['unidades']) ? $stock_producto[0]['unidades'] : $value->stock),
    			 			'observacion_adicional' => $value->observacion_adicional
                        );
                              
                        $da=$this->auditoria->insertar_detalle_auditoria($data_detalle);
                    }
                    else{
                        if($stock_producto[0]['codigo']!=$value->codigo){
                            $detalle .="<br> El producto ".$value->nombre." con el código ".$value->codigo." no existe";
                        }                       
                    }
                    /* Realizamos un movimiento para ajustar el inventario*/ 
                    if(is_numeric($value->cantidad_contada) && ($value->cantidad_contada>=0) && $this->input->post("ch_ajustar") && (!empty($da))){
                        $product_data = $this->productos->get_total_inventario_producto($value->id,$id_almacen);
                        
                        if((!empty($product_data->codigo))&&($product_data->codigo==$value->codigo)){
                        
                            if($value->cantidad_contada > $product_data->stock_actual){
                                $cantidad = $value->cantidad_contada - $product_data->stock_actual;
                                $tipo_movimiento = 'entrada_auditoria';
                            }

                            if($value->cantidad_contada < $product_data->stock_actual){
                                $cantidad = $product_data->stock_actual - $value->cantidad_contada;
                                $tipo_movimiento = 'salida_auditoria';
                            }

                            if($value->cantidad_contada == $product_data->stock_actual){
                                $id="iguales";
                                $iguales .="<br> El producto ".$product_data->nombre." con el código ".$product_data->codigo." tiene la misma cantidad actualmente";
                            }
                        }else{
                            if(empty($product_data->codigo)){
                                $detalle .="<br> El producto ".$value->nombre." no tiene código asignado y no se ajustó";
                            }else{
                                if($product_data->codigo!=$value->codigo){
                                    $detalle .="<br> El producto ".$value->nombre." con el código ".$value->codigo." no existe";
                                }
                            }                            
                        }
                        
                        if($tipo_movimiento != ''){
                            $producto = array(
                                "cantidad" => $cantidad,
                                "precio_compra" => $product_data->precio_compra,
                                "codigo_barra" => $product_data->codigo,
                                "nombre" => $product_data->nombre,
                                "existencias" => $product_data->stock_actual,
                                "total_inventario" => ($product_data->precio_compra * $cantidad),
                                "producto_id" => $product_data->id
                            );

                            $data_movimiento = array(
                                "user_id" => $this->ion_auth->get_user_id(),
                                "fecha" => date("Y-m-d h:i:s"),
                                "producto" => $producto,
                                "almacen_id" => $id_almacen,
                                "tipo_movimiento" => $tipo_movimiento,
                                "total_inventario" => ($product_data->precio_compra * $cantidad),
                                "nota" => '',
                                "codigo_factura"=> $id_auditoria
                            );

                            $id = $this->inventario->add_by_auditoria($data_movimiento);
                            
                        }
                    }
                }
               
    			if((!empty($id)) || (!empty($da))){
    				
                    if($this->input->post("ch_ajustar") && (!empty($id))){  
                        $resultado->status = true;                  
                        $data_movimiento = array();
                        if((!empty($detalle)) || (!empty($iguales))){
                            $resultado->error_message = 'La auditoria de inventario se registro correctamente y se ajustó al sistema actual con algunos detalles:'.$detalle.$iguales;
                        }else{
                            $resultado->error_message = 'La auditoria de inventario se registro correctamente y se ajustó al sistema actual';
                        }
                        
                    }else{ 
                        if($this->input->post("ch_ajustar") && (empty($id))){                        
                            $resultado->status = false;
                            $resultado->error_message= 'Existe un problema en la base de datos, al ajustar el inventario, intente de nuevo más tarde';
                        }else{
                            if(!empty($da)){
                                $resultado->status = true;
                                $resultado->error_message = 'La auditoria de inventario se registro correctamente';
                            }
                            else{
                                $resultado->status = false;
                                $resultado->error_message= 'Existen un problema en la base de datos, al insertar los productos auditados, intente de nuevo más tarde';
                            }                             
                        }
                    }
    			}else{
                    if(empty($da)){
                            $resultado->status = false;
                            $resultado->error_message= 'Existen un problema en la base de datos, al insertar los productos auditados, intente de nuevo más tarde';
                        }
                    else{
                        if(empty($id)){
                            $resultado->status = false;
                            $resultado->error_message= 'Existen un problema en la base de datos, al insertar los movimientos de los productos auditados, intente de nuevo más tarde';
                        }
                    }
    			}
    		}else{
    			$resultado->error_message = 'Existe un problema en la base de datos, intente de nuevo más tarde';
    		}

    	}else{
    		$resultado->error_message = validation_errors();
    	}

    	echo json_encode($resultado);
    	
    }

    public function editar_view($id){
      
        $datos_auditoria = $this->auditoria->get_auditorias(array('auditoria_inventario.id'=>$id));
             
    	if($datos_auditoria[0]->estado_auditoria == 'borrador'){
    		$data_vista = $datos_auditoria[0];
    		$data_vista->productos = $this->auditoria->get_detalle_auditoria(array('id_auditoria'=>$id));
    		 if($this->session->userdata('is_admin') == 't'){
        		$data_vista->almacenes = $this->almacenes->get_almacenes_activos();	
        	}else{
        		
        		$datos_almacen = $this->almacenes->get_by_id($data_vista->id_almacen);
        		$data_vista->nombre_almacen = $datos_almacen['nombre'];
            }
         
    	    $data_empresa = $this->mi_empresa->get_data_empresa();           
            $data_vista->tipo_negocio = $data_empresa['data']['tipo_negocio']; 
            $data_vista=(array) $data_vista;
            //print_r($data_vista);die();
            $this->layout->template('member')->js(base_url('public/js/auditoria_stock.js?1.2'))->show('auditoria/editar',array('data'=>$data_vista));
    	}else{
    		$this->session->set_flashdata('message', custom_lang('sima_auditoria_editar_auditoria', 'No se puede editar una categoria en estado diferente a borrador'));
    		redirect('auditoria/index');
    	}	
    }

    private function cargar_archivo($resultado){
        $bd = $this->session->userdata('base_dato');
    	$carpeta = 'uploads/'.$bd.'/auditoria_inventario/'; 
       		
            if (!file_exists($carpeta)) {
            	mkdir($carpeta, 0777, true);
        	}	

    		$config['upload_path'] = $carpeta;
        	$config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|doc|docx|xls|xlsx|XLS|XLSX|xl';
        	$this->load->library('upload', $config);
			$soporte_name = $carpeta.'/';

			
			if (!empty($_FILES['f_archivo_soporte']['name'])) {
            	if (!$this->upload->do_upload('f_archivo_soporte')) {
                	$resultado->error_message = $this->upload->display_errors('<p>', '</p>');
                	echo json_encode($resultado);
                	die;
            	} else {
                	$upload_data = $this->upload->data();
                	$soporte_name.= $upload_data['file_name'];
            	}
        	}
        return $soporte_name;	
    }

    public function actualizar_auditoria($id_auditoria){
    	$resultado =(object) array('status'=>false);
    	$iguales="";
    	$detalle="";
    	$this->form_validation->set_rules('t_nombre_auditoria', 'Nombre auditoria', 'required');
    	$this->form_validation->set_rules('productos', 'Nombre auditoria', 'required');

    	if($this->session->userdata('is_admin') == 't'){
    		$this->form_validation->set_rules('s_almacen', 'Almacen', 'required');	
    	}

    	$datos_originales_auditoria = $this->auditoria->get_auditorias(array('auditoria_inventario.id'=>$id_auditoria));

    	if ($this->form_validation->run() == true)
    	{	

            if (!empty($_FILES['f_archivo_soporte']['name'])) {
                $soporte_name = $this->cargar_archivo($resultado);
            }else{
                $soporte_name = $datos_originales_auditoria[0]->archivo_fisico;
            }             		
    		
    		if($this->session->userdata('is_admin') =='t'){
    			$id_almacen = $this->input->post('s_almacen');	
    		}else{
    			$id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
    		}

    		$data_update = array(
    			 'fecha_modificacion' => date("Y-m-d h:i:s"),
    			 'modificado_por' 	  => $this->ion_auth->get_user_id(), 
    			 'nombre_auditoria'   => $this->input->post('t_nombre_auditoria'),
    			 'descripcion_auditoria' => $this->input->post('ta_descripcion_auditoria'), 
                 //'estado_auditoria'   => 'borrador', 
                 'estado_auditoria' => $this->input->post("ch_ajustar")? 'Cerrada':'borrador', 
    			 'id_almacen' 		  => $id_almacen, 
    			 'archivo_fisico' 	  => $soporte_name,
    			);

    		$this->auditoria->update_auditoria(array('id'=>$id_auditoria),$data_update);
    		$this->auditoria->delete_detalle_auditoria(array('id_auditoria'=>$id_auditoria));
    		
    		$data_detalle = array();
            $productos_auditados = json_decode( $this->input->post('productos'));
            
            /***************nuevo */
                    $data_detalle = array();                   
                    $productos_auditados = json_decode( $this->input->post('productos'));
                    foreach ($productos_auditados as $key => $value) {
                        if((is_numeric($value->cantidad_contada))&& (($value->cantidad_contada>=0))){
                            $tipo_movimiento = '';
                            $data_detalle = array();
                            $stock_producto = $this->productos->obtener_existencias($value->id,$id_almacen);	
                            $data_detalle[]= array(
                                'fecha_creacion'=> date("Y-m-d h:i:s"),
                                'creado_por' => $this->ion_auth->get_user_id(),
                                'id_auditoria' => $id_auditoria,
                                'producto_id' => $value->id,
                                'cantidad_contada' => (is_numeric($value->cantidad_contada))? $value->cantidad_contada:'x',
                                'cantidad_sistema' => (isset($stock_producto[0]['unidades']) ? $stock_producto[0]['unidades'] : $value->stock),
                                'observacion_adicional' => $value->observacion_adicional
                            );
                                
                            $da=$this->auditoria->insertar_detalle_auditoria($data_detalle);
                        }else{
                            $detalle .="<br> El producto ".$stock_producto[0]['nombre']." con el código ".$stock_producto[0]['codigo']." no tiene cantidad contada válida";
                        }
                        /* Realizamos un movimiento para ajustar el inventario*/ 
                        if(is_numeric($value->cantidad_contada) && $this->input->post("ch_ajustar") && (!empty($da))){
                            $product_data = $this->productos->get_total_inventario_producto($value->id,$id_almacen);
                            
                            if($value->cantidad_contada > $product_data->stock_actual){
                                $cantidad = $value->cantidad_contada - $product_data->stock_actual;
                                $tipo_movimiento = 'entrada_auditoria';
                            }

                            if($value->cantidad_contada < $product_data->stock_actual){
                                $cantidad = $product_data->stock_actual - $value->cantidad_contada;
                                $tipo_movimiento = 'salida_auditoria';
                            }

                            if($value->cantidad_contada == $product_data->stock_actual){
                                $id="iguales";
                                $iguales .="<br> El producto ".$product_data->nombre." con el código ".$product_data->codigo." tiene la misma cantidad actualmente";
                            }
                            
                            if($tipo_movimiento != ''){
                                $producto = array(
                                    "cantidad" => $cantidad,
                                    "precio_compra" => $product_data->precio_compra,
                                    "codigo_barra" => $product_data->codigo,
                                    "nombre" => $product_data->nombre,
                                    "existencias" => $product_data->stock_actual,
                                    "total_inventario" => ($product_data->precio_compra * $cantidad),
                                    "producto_id" => $product_data->id
                                );
    
                                $data_movimiento = array(
                                    "user_id" => $this->ion_auth->get_user_id(),
                                    "fecha" => date("Y-m-d h:i:s"),
                                    "producto" => $producto,
                                    "almacen_id" => $id_almacen,
                                    "tipo_movimiento" => $tipo_movimiento,
                                    "total_inventario" => ($product_data->precio_compra * $cantidad),
                                    "nota" => '',
                                    "codigo_factura"=> $id_auditoria
                                );
                                
                                $id = $this->inventario->add_by_auditoria($data_movimiento);
                                
                            }
                        }
                    }
            /**************fin */
                if((!empty($id)) || (!empty($da))){
    				
                    //if($this->input->post("ch_ajustar") && ($this->auditoria->ajustar_auditoria($data_detalle,$id_almacen)==1)){
                    if($this->input->post("ch_ajustar") && (!empty($id))){  
                        $resultado->status = true;                  
                        $data_movimiento = array();
                        if((!empty($iguales)) || (!empty($detalle))){
                            $resultado->error_message = 'La auditoria de inventario se actualizó correctamente y se ajustó al sistema actual con nota de: '.$iguales;
                        }else{
                            $resultado->error_message = 'La auditoria de inventario se actualizó correctamente y se ajustó al sistema actual';
                        }
                        
                    }else{ 
                        if($this->input->post("ch_ajustar") && (empty($id))){                        
                            $resultado->status = false;
                            $resultado->error_message= 'Existe un problema en la base de datos, al ajustar el inventario, intente de nuevo más tarde';
                        }else{
                            if(!empty($da)){
                                $resultado->status = true;
                                $resultado->error_message = 'La auditoria de inventario se actualizó correctamente';
                            }else{
                                $resultado->status = false;
                                $resultado->error_message= 'Existen un problema en la base de datos, al actualizar los productos auditados, intente de nuevo más tarde';
                            }                           
                        }
                    }
    			}else{
                    if(empty($da)){
                            $resultado->status = false;
                            $resultado->error_message= 'Existen un problema en la base de datos, al actualizar los productos auditados, intente de nuevo más tarde';
                        }
                    else{
                        if(empty($id)){
                            $resultado->status = false;
                            $resultado->error_message= 'Existen un problema en la base de datos, al actualizar los movimientos de los productos auditados, intente de nuevo más tarde';
                        }
                    }
    			}
        }else{
        	$resultado->error_message = validation_error();
        }
      echo json_encode($resultado);
    }	

    public function eliminar($id){
        
        $datos_auditoria = $this->auditoria->get_auditorias(array('auditoria_inventario.id'=>$id));
        
        //si esta en movimientos fue afectada o cerrada 1 para limite de registros y 1 para retornar los resultados 
        $id1 = $this->auditoria->afectar_inventario_si_no($id,1,1);
              
        $id1=$id1[0]->codigo_factura;
        $id_almacen=$datos_auditoria[0]->id_almacen;
        $id_auditoria=$datos_auditoria[0]->id;
        $errores="";

        /*
        if (($id1 > 0) && ($id1==$id)) {
            //anular movimientos
            $datos_detalle_auditoria = $this->auditoria->get_detalle_auditoria(array('id_auditoria'=>$id));
        
            if(!empty($datos_detalle_auditoria)){
           
                foreach ($datos_detalle_auditoria as $key => $value) {
                                  
                    $stock_producto = $this->productos->obtener_existencias($value->producto_id,$id_almacen);
                  
                    //quitar el ajuste del inventario 
                    if($stock_producto[0]['unidades']>=$value->cantidad_contada){
                        $cantidad=$value->cantidad_contada;
                    
                        $producto = array(
                            "cantidad" => $cantidad,
                            "precio_compra" => $stock_producto[0]['precio_compra'],
                            "codigo_barra" => $stock_producto[0]['codigo'],
                            "nombre" => $stock_producto[0]['nombre'],
                            "existencias" => $stock_producto[0]['unidades'],
                            "total_inventario" => ($stock_producto[0]['precio_compra'] * $cantidad),
                            "producto_id" => $value->producto_id
                        );

                        $data_movimiento = array(
                            "user_id" => $this->ion_auth->get_user_id(),
                            "fecha" => date("Y-m-d h:i:s"),
                            "producto" => $producto,
                            "almacen_id" => $id_almacen,
                            "tipo_movimiento" => 'devolucion_auditoria',
                            "total_inventario" => ($stock_producto[0]['precio_compra'] * $cantidad),
                            "nota" => '',
                            "codigo_factura"=> $id_auditoria
                        );

                        $this->inventario->add_by_auditoria($data_movimiento);
                    }
                    else{
                        $errores .="<br>El producto ".$stock_producto[0]['nombre']." con código ".$stock_producto[0]['codigo']." no tiene suficiente stock para devolver el inventario.<br>";                                        
                    }
                }
            }
        }*/
        if($datos_auditoria[0]->estado_auditoria=="borrador"){

            $data_update = array(
                'fecha_modificacion' => date("Y-m-d h:i:s"),
                'modificado_por' => $this->ion_auth->get_user_id(),
                'estado_auditoria'=>'Eliminada',
                'motivo'=> ($errores=="")? 'Anulación': 'Aulación Nota:'.$errores,
                'id_user_anulacion' => $this->ion_auth->get_user_id(),
                'fecha_anulacion' => date("Y-m-d h:i:s")
            );
            
            $this->auditoria->update_auditoria(array('id'=>$id),$data_update);
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente la auditoría"));
        }else{
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "No se puede anular una auditoría en otro estado que no sea borrador"));
        } 
        /*
        if(empty($errores)){
            
        }else{
            $this->session->set_flashdata('message1', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente la auditoría con algunos detalles:<br>".$errores));
        }           
           */ 
            redirect("auditoria/index"); 
          
    }

    public function cerrar($id){
        $resultado =(object) array('status'=>false);
    	$datos_auditoria = $this->auditoria->get_auditorias(array('auditoria_inventario.id'=>$id));
    	$datos_detalle_auditoria = $this->auditoria->get_detalle_auditoria(array('id_auditoria'=>$id));
        $iguales="";
    	if($datos_auditoria[0]->estado_auditoria == 'borrador'){
    		$data_update= array('fecha_modificacion' => date("Y-m-d h:i:s"),
                'modificado_por' => $this->ion_auth->get_user_id(),
                'estado_auditoria' => 'Cerrada'
            );
            $this->auditoria->update_auditoria(array('id'=>$id),$data_update);

            //ajustar inventario
            /* Realizamos un movimiento para ajustar el inventario*/ 
            $data_detalle = array();
            $id_movi = array();
            $data_ajuste = array(); // Arreglo para ajustar la auditoria real con la existente en el sistema            
            //print_r($productos_auditados);   die();            
            foreach ($datos_detalle_auditoria as $value) {             
                
                $tipo_movimiento = '';
                $stock_producto = $this->productos->obtener_existencias($value->id,$datos_auditoria[0]->id_almacen);	
                $data_detalle[]= array(
                        'fecha_creacion'=> date("Y-m-d h:i:s"),
                        'creado_por' => $this->ion_auth->get_user_id(),
                        'id_auditoria' => $id,
                        'producto_id' => $value->id,
                        'cantidad_contada' => (is_numeric($value->cantidad_contada))? $value->cantidad_contada:'x',
                        'cantidad_sistema' => (isset($stock_producto[0]['unidades']) ? $stock_producto[0]['unidades'] : $value->stock),
                        'observacion_adicional' => $value->observacion_adicional
                    );
                    
                    /* Realizamos un movimiento para ajustar el inventario*/ 
                    if(is_numeric($value->cantidad_contada)){
                        $product_data = $this->productos->get_total_inventario_producto($value->id,$datos_auditoria[0]->id_almacen);
                        
                        if($value->cantidad_contada > $product_data->stock_actual){
                            $cantidad = $value->cantidad_contada - $product_data->stock_actual;
                            $tipo_movimiento = 'entrada_auditoria';
                        }

                        if($value->cantidad_contada < $product_data->stock_actual){
                            $cantidad = $product_data->stock_actual - $value->cantidad_contada;
                            $tipo_movimiento = 'salida_auditoria';
                        }

                        if($value->cantidad_contada == $product_data->stock_actual){                            
                            $iguales .="<br> El producto ".$product_data->nombre." con el código ".$product_data->codigo." tiene la misma cantidad actualmente";
                        }
                        
                        if($tipo_movimiento != ''){
                            $producto = array(
                                "cantidad" => $cantidad,
                                "precio_compra" => $product_data->precio_compra,
                                "codigo_barra" => $product_data->codigo,
                                "nombre" => $product_data->nombre,
                                "existencias" => $product_data->stock_actual,
                                "total_inventario" => ($product_data->precio_compra * $cantidad),
                                "producto_id" => $product_data->id
                            );

                            $data_movimiento = array(
                                "user_id" => $this->ion_auth->get_user_id(),
                                "fecha" => date("Y-m-d h:i:s"),
                                "producto" => $producto,
                                "almacen_id" => $datos_auditoria[0]->id_almacen,
                                "tipo_movimiento" => $tipo_movimiento,
                                "total_inventario" => ($product_data->precio_compra * $cantidad),
                                "nota" => '',
                                "codigo_factura"=> $id
                            );

                            $id_movi[] = $this->inventario->add_by_auditoria($data_movimiento);

                        }
                    }
                }    
            $resultado->status = true;
            if(!empty($iguales)){
                $resultado->error_message= 'La auditoría se ha cerrado correctamente con algunos detalles:'.$iguales; 
            }
            else{
                $resultado->error_message= 'La auditoría se ha cerrado correctamente';
            }
    	}else{
            $resultado->status = false;
            $resultado->error_message= 'No se puede cerrar una auditoría en un estado diferente a borrador';
    		//$this->session->set_flashdata('message', custom_lang('sima_auditoria_deleted_auditoria', 'No se puede cerrar una auditoría en un estado diferente a borrador'));
        }        
      echo json_encode($resultado);   
 		//redirect("auditoria/index");
    }

    public function informe_auditoria_view(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        acceso_informe('Informe de auditorias inventario');
        $data=array();
        if($this->session->userdata('is_admin') == 't'){
            $data['almacenes'] = $this->almacenes->get_almacenes_activos(); 
        }else{
            $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $datos_almacen = $this->almacenes->get_by_id($id_almacen);
            $data['nombre_almacen'] = $datos_almacen['nombre'];
            $data['auditorias_realizadas'] = $this->auditoria->get_auditorias(array('id_almacen' => $id_almacen,'estado_auditoria !='=>'Eliminada'));
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('informes/auditoria_inventario',array('data'=>$data));   
    }

    public function consultar_auditorias_almacen(){
        $id_almacen = $this->input->post('almacen');
        $where = array('estado_auditoria !='=>'Eliminada');
        if(is_numeric($id_almacen)){
            $where['id_almacen'] = $id_almacen;
        }   
        $datos_auditorias = $this->auditoria->get_auditorias($where);
        $devolver = array();
        foreach ($datos_auditorias as $key => $value) {
            $devolver[]=array('id'=>$value->id,'nombre_auditoria'=>$value->nombre_auditoria);
        }
        echo json_encode(array('data'=>$devolver));
    }

    private function armar_where_informe(){
        $where = array();

        if($this->session->userdata('is_admin') == 't'){
            $id_almacen = $this->input->post('s_almacen');
        }else{
            $id_almacen =  $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }
        
        if(is_numeric($id_almacen)){
            $where['id_almacen'] = $id_almacen;
        }
        
        $nombre = $this->input->post('s_identificacion_auditoria');
        $fecha_inical = $this->input->post('t_fecha_inicial');
        $fecha_final = $this->input->post('t_fecha_final');

        if(empty($nombre)){
            if(!empty($fecha_inical)){
                $where['fecha_creacion >='] = $fecha_inical;    
            }
            if(!empty($fecha_final)){
                $where['fecha_creacion <='] = $fecha_final;    
            }
            
        }else{
            $where['auditoria_inventario.id'] = $nombre;
        }

        return $where;
    }

    public function generar_informe_auditoria(){
        $where = $this->armar_where_informe();
        
        if(empty($where)){
            echo json_encode(array('success'=>false,'error_message'=>'Debe seleccionar al menos un criterio de busqueda'));
            die();
        }
        $where['estado_auditoria !='] ='Eliminada';
       
        $auditorias = $this->auditoria->get_auditorias($where);
        $contenido = array();
        if(count($auditorias) ==1){
            $cabeceras = array('Código','Producto','Cantidad Contada','Cantidad en Sistema','Diferencia (Cantidad en Sistema - Cantidad Contada)','Observación');
            $productos_auditoria = $this->auditoria->get_detalle_auditoria(array('id_auditoria'=>$auditorias[0]->id));
            foreach ($productos_auditoria as $key => $value) {
                $diferencia = $value->cantidad_sistema - $value->cantidad_contada;
                $contenido[]=array($value->codigo,
                                   $value->nombre,
                                   $value->cantidad_contada,
                                   $value->cantidad_sistema,
                                   $diferencia,
                                   $value->observacion_adicional
                                   );
            }
        }else{
            $cabeceras = array('fecha auditoria','identificación','Realizada por','descripcion adicional','Estado','Almacen','Soporte');
            foreach ($auditorias as $key => $value) {
                $nombre = substr(strrchr($value->archivo_fisico, "/"), 1);
                $datos_usuario = $this->db->query("SELECT username  FROM users where id = '$value->creado_por' ")->row();
                $nombre_usuario = '';
                if($datos_usuario){
                    $nombre_usuario = $datos_usuario->username;
                } 
                $contenido[]=array(
                                    date("Y-m-d",strtotime($value->fecha_creacion)),
                                    $value->nombre_auditoria,
                                    $nombre_usuario,
                                    $value->descripcion_auditoria, 
                                    $value->estado_auditoria,
                                    $value->nombre,   
                                    '<a href="'.base_url().$value->archivo_fisico.'">'.$nombre.'</a>' 
                                );
            }
        }

        echo json_encode(array('cabecera'=>$cabeceras,'contenido'=>$contenido,'success'=>true));
    }

    public function generar_excel_nforme_auditoria(){
        
        $this->load->library('phpexcel');
        $where = $this->armar_where_informe();

        if(empty($where)){
           echo json_encode(array('success'=>false,'error_message'=>'Debe seleccionar al menos un criterio de busqueda'));
           die();
        }

        $where['estado_auditoria !='] ='Eliminada';        
        $auditorias = $this->auditoria->get_auditorias($where);

        $consolidado_auditorias=array();
          
        foreach ($auditorias as $key => $una_auditoria) {
            $detalle_auditoria = $this->auditoria->get_detalle_auditoria(array('id_auditoria'=>$una_auditoria->id));
            $datos_usuario = $this->db->query("SELECT username  FROM users where id = '$una_auditoria->creado_por' ")->row();
            $nombre_usuario = '';
            if($datos_usuario){
                $nombre_usuario = $datos_usuario->username;
            }             
            
            foreach ($detalle_auditoria as $key => $value) {
                     $consolidado_auditorias[]=(object)array(
                        'nombre_auditoria'      => $una_auditoria->nombre_auditoria,
                        'fecha_creacion'        => $una_auditoria->fecha_creacion,
                        'descripcion_auditoria' => $una_auditoria->descripcion_auditoria,
                        'codigo'                => $value->codigo,
                        'nombre'                => $value->nombre,
                        'cantidad_contada'      => $value->cantidad_contada,
                        'observacion_adicional' => $value->observacion_adicional,
                        'cantidad_sistema'      => $value->cantidad_sistema,
                        'nombre_almacen'        => $una_auditoria->nombre,
                        'autor_auditoria'       => $nombre_usuario,
                        );
            }
        }
        //print_r($consolidado_auditorias); die();
        $this->armar_estructura_excel($consolidado_auditorias);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe_auditorias.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        ob_clean();
        $objWriter->save('php://output');

        exit;

    }

    public function armar_estructura_excel($auditoria){
        
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1','Identificación:');
        $this->phpexcel->getActiveSheet()->setCellValue('B1','Fecha:');
        $this->phpexcel->getActiveSheet()->setCellValue('C1','Descripcion');
        $this->phpexcel->getActiveSheet()->setCellValue('D1','Realizada por');
        $this->phpexcel->getActiveSheet()->setCellValue('E1','codigo');
        $this->phpexcel->getActiveSheet()->setCellValue('F1','Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('G1','cantidad contada');
        $this->phpexcel->getActiveSheet()->setCellValue('H1','Observacion');
        $this->phpexcel->getActiveSheet()->setCellValue('I1','Almacen');
        $styleArray = array('font' => array('bold' => true));
        
        if($this->session->userdata('is_admin') == 't'){
           $this->phpexcel->getActiveSheet()->setCellValue('J1','cantidad sistema');
           $this->phpexcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);
           $this->phpexcel->getActiveSheet()->setCellValue('K1','Diferencia'); 
           $this->phpexcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleArray);    
        }

        $this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
        $this->phpexcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
        
        $fila_excel=2;
        
        foreach ($auditoria as $key => $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $fila_excel, $value->nombre_auditoria);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $fila_excel, date("Y-m-d",strtotime($value->fecha_creacion)));
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $fila_excel, $value->descripcion_auditoria);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $fila_excel, $value->autor_auditoria);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $fila_excel, $value->codigo);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $fila_excel, $value->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $fila_excel, $value->cantidad_contada);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $fila_excel, $value->observacion_adicional);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $fila_excel, $value->nombre_almacen);
            
            if($this->session->userdata('is_admin') =='t'){
                $this->phpexcel->getActiveSheet()->setCellValue('J' . $fila_excel, $value->cantidad_sistema);
                $diferencia = $value->cantidad_sistema - $value->cantidad_contada;
                $this->phpexcel->getActiveSheet()->setCellValue('K' . $fila_excel, $diferencia);
            }
            $fila_excel++;
        }
        
        //$this->phpexcel->getActiveSheet()->setTitle($auditoria->nombre_auditoria);

        $styleThinBlackBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
                ),
            ),
        );
        if($this->session->userdata('is_admin') =='t'){
            $this->phpexcel->getActiveSheet()->getStyle('A1:K' . --$fila_excel)->applyFromArray($styleThinBlackBorderOutline);
        }else{
            $this->phpexcel->getActiveSheet()->getStyle('A1:I' . --$fila_excel)->applyFromArray($styleThinBlackBorderOutline);
        }
        
    }


    public function generar_excel_para_auditoria_view(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $data=array();
        if($this->session->userdata('is_admin') == 't'){
            $data['almacenes'] = $this->almacenes->get_almacenes_activos(); 
        }else{
            $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $datos_almacen = $this->almacenes->get_by_id($id_almacen);
            $data['nombre_almacen'] = $datos_almacen['nombre'];
            $data['id'] = $datos_almacen['id'];
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('auditoria/importar_excel',array('data'=>$data));
    }
    
    public function generar_excel_para_auditoria(){
        $this->load->library('phpexcel');

        if($this->session->userdata('is_admin') == 't'){
            $id_almacen = $this->input->get('s_almacen');
        }else{
            $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }
        
        $datos_almacen = $this->almacenes->get_by_id($id_almacen);

        if(empty($id_almacen) or $id_almacen == 0){
            echo '<h4>Debe seleccionar el almacen al cual va a realizar auditoria <a href="#" onclick="javascript:window.history.back();">Regresar</a></h4>';
            die();
        }

        $productos_almacen = $this->productos->get_productos_almacen($id_almacen);
        
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1','Identificador interno');
        $this->phpexcel->getActiveSheet()->setCellValue('B1','codigo');
        $this->phpexcel->getActiveSheet()->setCellValue('C1','Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('D1','Cantidad en Sistema');
        $this->phpexcel->getActiveSheet()->setCellValue('E1','cantidad contada');
        $this->phpexcel->getActiveSheet()->setCellValue('F1','Observacion');
        $this->phpexcel->getActiveSheet()->setCellValue('G1','Almacen');
        
        $styleArray = array('font' => array('bold' => true));
        $this->phpexcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);     
        
        $fila_excel=2;

        foreach ($productos_almacen as $key => $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $fila_excel, $value->id);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $fila_excel, $value->codigo);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $fila_excel, $value->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $fila_excel, $value->unidades);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $fila_excel, $datos_almacen['nombre']);
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $fila_excel, $value->id);
            
            $fila_excel++;
        }

         $styleThinBlackBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
                ),
            ),
        );

        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setVisible(false); 
        $this->phpexcel->getActiveSheet()->getColumnDimension('X')->setVisible(false); 
        $this->phpexcel->getActiveSheet()->getStyle('A1:G' . --$fila_excel)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('B1:B'.--$fila_excel)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="inventario_auditoria.xls"');
        header('Cache-Control: max-age=0'); 
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter->save('php://output');

        exit;

    }

    public function cargar_auditoria_desde_excel(){
        $data=array();
        $resultado= (object)array('success'=>false);
        if($this->session->userdata('is_admin') == 't'){
            $data['almacenes'] = $this->almacenes->get_almacenes_activos(); 
        }else{
            $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $datos_almacen = $this->almacenes->get_by_id($id_almacen);
            $data['nombre_almacen'] = $datos_almacen['nombre'];
        }
        $soporte_name = $this->cargar_archivo($resultado);
        if(empty($soporte_name)){
            $success->error_message = 'no se ha podido cargar el archivo, por favor intente de nuevo';
            echo json_encode($success);
        }else{

            $this->load->library('phpexcel');
            $name = $_FILES['f_archivo_soporte']['name'];
            $tname = $_FILES['f_archivo_soporte']['tmp_name'];
            $obj_excel = PHPExcel_IOFactory::load($tname);
            $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);
            $productos_cargados = array();
            foreach ($sheetData as $key => $value) {
                if($value["A"] == $value["X"]){
                    $productos_cargados[$value["A"]]=array(
                                    'id'    => $value["A"],
                                    'label' => $value["C"],
                                    'value' => $value["C"],                                 
                                    'nombre' =>$value["C"],
                                    'codigo' =>$value["B"],
                                    'codigo_barra' => $value["B"],                                
                                    'stock' => 0,
                                    'cantidad_contada' => $value["E"],
                                    'observacion_adicional'=> (is_null($value["F"]) ? '': $value["F"]),
                        );
                }else{
                    $success->error_message = 'El archivo que esta cargando tiene inconsistencias con los productos, por favor descargue de nuevo la plantilla';
                    echo json_encode($resultado);die();
                }
            }
            $resultado->success=true;
            $resultado->datos_productos = $productos_cargados;
            $resultado->error_message = 'Por favor, revise la siguiente lista con los productos cargados y de click en el boton guardar auditoria';
            echo json_encode($resultado);
        }

    }

 }    
?>
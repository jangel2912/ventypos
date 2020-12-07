<?php

class UploadInvoice extends CI_Controller 
{
    var $dbConnection;
        
	function __construct() {
            parent::__construct();
            
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            
            $this->load->model("productos_model",'productos');
            $this->productos->initialize($this->dbConnection);
            
            $this->load->model("proveedores_model",'proveedores');
            $this->proveedores->initialize($this->dbConnection); 
            
            $this->load->model("almacenes_model",'almacenes');
            $this->almacenes->initialize($this->dbConnection);
            
            $this->load->model("miempresa_model",'miempresa');
            $this->miempresa->initialize($this->dbConnection);
           
            $this->load->model("inventario_model",'inventario');
            $this->inventario->initialize($this->dbConnection); 

            $this->load->model("stock_actual_model",'stock');
            $this->stock->initialize($this->dbConnection); 

          
            $this->load->library('pagination');
            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
            
            $idioma = $this->session->userdata('idioma');
            $this->lang->load('sima', $idioma);
        }
        
        public function get_ajax_data(){
            $this->output->set_content_type('application/json')->set_output(json_encode($this->inventario->get_ajax_data()));
        }
        
        public function index(){
            $this->layout->template('member')
                    ->css(array(base_url("/public/fancybox/jquery.fancybox.css")))
                    ->js(array(base_url("/public/fancybox/jquery.fancybox.js")))
                    ->show('inventario/index');
        }
        
        public function nuevo(){
            
            if (!$this->ion_auth->logged_in()){
    			redirect('auth', 'refresh');
    		}
		//$data['cod'] = $this->_codigo();
                if(isset($_POST['tipo_movimiento'])/*$this->form_validation->run('facturas') == true*/){
                    
                    $data = array(
                        'user_id' => $this->session->userdata('user_id')
                        ,'fecha' => $_POST['fecha']." ".date("H:i:s")
                        ,'productos' => $_POST['productos']
                        ,'almacen_id' => $_POST['almacen_id']
                        ,'tipo_movimiento' => $_POST['tipo_movimiento']
                        ,'total_inventario' => $_POST['total_inventario']
                    );
                    
                    if($_POST['tipo_movimiento'] == 'entrada_compra'){
                        $data['proveedor_id'] = $_POST['proveedor_id'];
                        $data['codigo_factura'] = $_POST['codigo_factura'];
                    }
                    elseif ($_POST['tipo_movimiento'] == 'traslado') {
                        $data['almacen_traslado_id'] = $_POST['almacen_traslado_id'];
                    }
                    
                    //agrega al inventario
                    $id = $this->inventario->add($data);
                    $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));
                }
                else{
                    
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');
                    
                     $this->layout->template('member')->show('inventario/nuevo', array('data' => $data));
                               // ->css(array(base_url("/public/fancybox/jquery.fancybox.css")))
                               // ->js(array(base_url("/public/fancybox/jquery.fancybox.js")))
                                
                }
        }
        
        public function imprimir($id){
           
           $data_empresa = $this->miempresa->get_data_empresa();
           
           $data = array(
               'movimiento' => $this->inventario->get_by_id($id)
              ,'detalle_movimiento' => $this->inventario->get_detalles_movimiento($id)
              ,'data_empresa' =>  $data_empresa
           );
           
           $this->layout->template('ajax')->show('inventario/_imprime', array('data' => $data));
        }


        //eliminar movimiento de inventario

        public function eliminar($id){

                  if (!$this->ion_auth->logged_in())
                  {
                    redirect('auth', 'refresh');
                  }

                  $movimiento_inv=$this->inventario->get_by_id($id);

                  
                  $id_inventario=$movimiento_inv['id'];
                  $id_almacen=$movimiento_inv['almacen_id'];

                  
                  $detalles=$this->inventario-> get_detalles_movimiento($id_inventario);


                  $this->inventario->eliminar_movimiento($id_inventario, $id_almacen);
           
                
                  $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));
                  

                  redirect("inventario");





        }
		
		
     public function import_excel(){

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}
		$this->layout->template('ventas');

		
		$data = array();
		
		$error_upload = "";
		$total=0;
		$total_correctos=0;
		$total_incorrectos=0;
		

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xlsx|xls';

            $image_name = "";

            $this->load->library('upload', $config);
	
            if(!empty($_FILES['archivo']['name'])){

    //no olivdar subir el archivo mime en config           

                if(!$this->upload->do_upload('archivo')){
			
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');

                        $error_upload =('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla inventario"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla inventario</p>');
						$data['data']['upload_error'] = $error_upload;
					     $this->layout->show('inventario/import_excel', array('data' => $data));  

                }else{

        $this->load->library('phpexcel');
    	$name   = $_FILES['archivo']['name'];
     	$tname  = $_FILES['archivo']['tmp_name'];
        $obj_excel = PHPExcel_IOFactory::load($tname);       
       	$sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
       	$arr_datos = array();
				    $data = array(
                        'user_id' => $this->session->userdata('user_id')
                        ,'fecha' => $_POST['fecha']." ".date("H:i:s")
                        ,'almacen_id' => $_POST['almacen_id']
                        ,'tipo_movimiento' => $_POST['tipo_movimiento']
                    );
                    
                    if($_POST['tipo_movimiento'] == 'entrada_compra'){
                        $data['proveedor_id'] = $_POST['proveedor_id'];
                        $data['codigo_factura'] = $_POST['codigo_factura'];
                    }
                    elseif ($_POST['tipo_movimiento'] == 'traslado') {
                        $data['almacen_traslado_id'] = $_POST['almacen_traslado_id'];
                    }	 			
						
					 $id = $this->inventario->add_csv_inventario_1($data);			
		
       	foreach ($sheetData as $index => $value) {            
        if ( $index != 1 ){
				 		
		if($value['A'] != ''  &&  $value['B'] != ''  &&  $value['C'] != ''){
				$array_datos = array();
		
            $value = array(
                "codigo"  	=> $value['A'],
                "cantidad"        => $value['B'],
                "precio_compra"        => $value['C'] 										
             );
								
				$id_producto = $this->inventario->add_csv_inventario_2($data, $value, $id);			   
			 			   
	     }
            $this->inventario->add_csv_inventario_3($id);	
/*                 $arr_datos = array(
                    'campo'  => $value['A'],
                    'campo1'  =>  $value['B'],
                    'campo2' =>  $value['C']                                       
                );
	foreach ($arr_datos as $llave => $valor) {
			echo $arr_datos[$llave] = $valor."<br>";
		}*/
	//	$this->db->insert('example_table',$arr_datos);	
	
	
            } 
       	}
       	$result['valid'] = true;
	$result['message'] = 'Productos importados correctamente';
		 $this->session->set_flashdata('message',  custom_lang('sima_bill_send_message', "Se ha guardado correctamente el inventario"));
		 
		 			    @unlink("uploads/$name");
          
           redirect("inventario/index");	
	
         }
         }else{	
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');
					$data['data']['upload_error'] = $error_upload;
		            $this->layout->show('inventario/import_excel', array('data' => $data));
                        
		
		 }
			  

        }	

		
     public function import_excel_nombre(){

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}
		$this->layout->template('ventas');

		
		$data = array();
		
		$error_upload = "";
		$total=0;
		$total_correctos=0;
		$total_incorrectos=0;
		

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xlsx|xls';

            $image_name = "";

            $this->load->library('upload', $config);
	
            if(!empty($_FILES['archivo']['name'])){

    //no olivdar subir el archivo mime en config           

                if(!$this->upload->do_upload('archivo')){
			
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');

                        $error_upload =('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla inventario"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla inventario</p>');
						$data['data']['upload_error'] = $error_upload;
					     $this->layout->show('inventario/import_excel_nombre', array('data' => $data));  

                }else{

        $this->load->library('phpexcel');
    	$name   = $_FILES['archivo']['name'];
     	$tname  = $_FILES['archivo']['tmp_name'];
        $obj_excel = PHPExcel_IOFactory::load($tname);       
       	$sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
       	$arr_datos = array();
				    $data = array(
                        'user_id' => $this->session->userdata('user_id')
                        ,'fecha' => $_POST['fecha']." ".date("H:i:s")
                        ,'almacen_id' => $_POST['almacen_id']
                        ,'tipo_movimiento' => $_POST['tipo_movimiento']
                    );
                    
                    if($_POST['tipo_movimiento'] == 'entrada_compra'){
                        $data['proveedor_id'] = $_POST['proveedor_id'];
                        $data['codigo_factura'] = $_POST['codigo_factura'];
                    }
                    elseif ($_POST['tipo_movimiento'] == 'traslado') {
                        $data['almacen_traslado_id'] = $_POST['almacen_traslado_id'];
                    }	 			
						
					 $id = $this->inventario->add_csv_inventario_1($data);			
		
       	foreach ($sheetData as $index => $value) {            
        if ( $index != 1 ){
				 		
		if($value['A'] != ''  &&  $value['B'] != ''){
				$array_datos = array();
		
            $value = array(
                "nombre"  	=> $value['A'],
                "cantidad"        => $value['B'] 										
             );
								
				$id_producto = $this->inventario->add_csv_inventario_nombre_2($data, $value, $id);			   
			 			   
	     }
            $this->inventario->add_csv_inventario_3($id);	
/*                 $arr_datos = array(
                    'campo'  => $value['A'],
                    'campo1'  =>  $value['B'],
                    'campo2' =>  $value['C']                                       
                );
	foreach ($arr_datos as $llave => $valor) {
			echo $arr_datos[$llave] = $valor."<br>";
		}*/
	//	$this->db->insert('example_table',$arr_datos);	
	
	
            } 
       	}
       	$result['valid'] = true;
	$result['message'] = 'Productos importados correctamente';
		 $this->session->set_flashdata('message',  custom_lang('sima_bill_send_message', "Se ha guardado correctamente el inventario"));
		 
		 			    @unlink("uploads/$image_name");
          
           redirect("inventario/index");	
	
         }
         }else{	
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');
						 $data['data']['upload_error'] = $error_upload;
		                $this->layout->show('inventario/import_excel_nombre', array('data' => $data));
                        
		
		 }
			  

        }	

		
     public function import_excel_nombre_codigo(){

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}
		$this->layout->template('ventas');

		
		$data = array();
		
		$error_upload = "";
		$total=0;
		$total_correctos=0;
		$total_incorrectos=0;
		

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xlsx|xls';

            $image_name = "";

            $this->load->library('upload', $config);
	
            if(!empty($_FILES['archivo']['name'])){

    //no olivdar subir el archivo mime en config           

                if(!$this->upload->do_upload('archivo')){
			
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');

                        $error_upload =('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla inventario"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla inventario</p>');
						$data['data']['upload_error'] = $error_upload;
					     $this->layout->show('inventario/import_excel_nombre', array('data' => $data));  

                }else{

        $this->load->library('phpexcel');
    	$name   = $_FILES['archivo']['name'];
     	$tname  = $_FILES['archivo']['tmp_name'];
        $obj_excel = PHPExcel_IOFactory::load($tname);       
       	$sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
       	$arr_datos = array();
				    $data = array(
                        'user_id' => $this->session->userdata('user_id')
                        ,'fecha' => $_POST['fecha']." ".date("H:i:s")
                        ,'almacen_id' => $_POST['almacen_id']
                        ,'tipo_movimiento' => $_POST['tipo_movimiento']
                    );
                    
                    if($_POST['tipo_movimiento'] == 'entrada_compra'){
                        $data['proveedor_id'] = $_POST['proveedor_id'];
                        $data['codigo_factura'] = $_POST['codigo_factura'];
                    }
                    elseif ($_POST['tipo_movimiento'] == 'traslado') {
                        $data['almacen_traslado_id'] = $_POST['almacen_traslado_id'];
                    }	 			
						
					 $id = $this->inventario->add_csv_inventario_1($data);			
		
       	foreach ($sheetData as $index => $value) {            
        if ( $index != 1 ){
				 		
		if($value['B'] != ''){
				$array_datos = array();
		
            $value = array(
                "codigo"  	=> $value['A'],
				"nombre"  	=> $value['B'],
                "cantidad"  => $value['C'] 										
             );
								
				$id_producto = $this->inventario->add_csv_inventario_nombre_codigo($data, $value, $id);			   
			 			   
	     }
            $this->inventario->add_csv_inventario_3($id);	
/*                 $arr_datos = array(
                    'campo'  => $value['A'],
                    'campo1'  =>  $value['B'],
                    'campo2' =>  $value['C']                                       
                );
	foreach ($arr_datos as $llave => $valor) {
			echo $arr_datos[$llave] = $valor."<br>";
		}*/
	//	$this->db->insert('example_table',$arr_datos);	
	
	
            } 
       	}
       	$result['valid'] = true;
	$result['message'] = 'Productos importados correctamente';
		 $this->session->set_flashdata('message',  custom_lang('sima_bill_send_message', "Se ha guardado correctamente el inventario"));
		 
		 			    @unlink("uploads/$image_name");
          
           redirect("inventario/index");	
	
         }
         }else{	
		
                    $data['almacenes'] = $this->almacenes->get_combo_data();
                    $data['proveedores'] = $this->proveedores->get_combo_data();
                    $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');
						 $data['data']['upload_error'] = $error_upload;
		                $this->layout->show('inventario/import_excel_nombre_codigo', array('data' => $data));
                        
		
		 }
			  

        }	



}

?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of productosf
 *
 * @author danbuchi
 */
class Productosf extends CI_Controller 
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
            
            $this->load->model("productosf_model",'productos');
            $this->productos->initialize($this->dbConnection);
            
            $this->load->model("impuestos_model",'impuestos');
            $this->impuestos->initialize($this->dbConnection);
            
            $this->load->model("opciones_model",'opciones');
            $this->opciones->initialize($this->dbConnection);
            
            $this->load->library('pagination');
            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
            
            $idioma = $this->session->userdata('idioma');
            $this->lang->load('sima', $idioma);
        }
        
	public function index($offset = 0)
	{	
                if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
               
                $this->layout->template('member')->show('productosf/index');
		
	}
	
	public function nuevo()
	{	
                if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		 if ($this->form_validation->run('productosf') == true)
                    {
                        $data = $this->productos->add();
                        /*$this->load->library('zend');
                        
                        $index = array();
                        $index['id'] = $data['id_producto'];
                        $index['type'] = "productos";
                        $index['contents'] = "Nombre del producto {$data['nombre']}, Codigo {$data['codigo']}, Precio de venta {$data['precio']}, Precio de compra {$data['precio_compra']}";
                        
                        $this->zend->index_data($index); */
                        $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto creado correctamente'));
                        redirect('productosf/index');
                    }
                    
                $data = array();
                $data['impuestos'] = $this->impuestos->get_combo_data();
                $this->layout->template('member')->show('productosf/nuevo', array('data' => $data));
	}
        
        public function product_check($str){
            $id = $this->productos->get_by_name($str);
            if(!empty($id)){
                $id_producto = $this->input->post('id');
                if(!empty($id_producto) && $id_producto == $id){
                    return true;
                }
                $this->form_validation->set_message('product_check', 'El %s existe');
                return false;
            }
            return true;
        }
        
	public function get_ajax_data(){
          
            $this->output->set_content_type('application/json')->set_output(json_encode($this->productos->get_ajax_data()));
        }
	public function editar($id){
		
             if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		if ($this->form_validation->run('productosf') == true)
                    {
                        $this->productos->update();	
                        $this->session->set_flashdata('message', custom_lang('sima_product_updated_message', 'Producto actualizado correctamente'));
                        redirect("productosf/index");
                    }
                    
                $data = array();   
		$data['data']  = $this->productos->get_by_id($id);
                $data['impuestos'] = $this->impuestos->get_combo_data();
                $this->layout->template('member')->show('productosf/editar', array('data' => $data));
	}
        
        public function detalles($id)
        {
            if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
            $data = $this->productos->get_by_id($id);
            $this->layout->template('member')->show('productosf/detalles', array('data' => $data));
        }
	
	public function eliminar($id)
	{	
             if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		$this->productos->delete($id);
		$this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));
		redirect("productosf");
	}
        
        public function filtro_prod(){
        
            $result = array();
            $result = $this->productos->get_term($this->input->get('term', TRUE), $this->input->get('cli', TRUE));
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
        
        public function filtro_prod_orden_compra(){
            
            $precioOrdenCompra = $this->opciones->getOpcion("orden_compra_precio");
            
            $result = $this->productos->get_term_orden_compra($this->input->get('term', TRUE),$precioOrdenCompra);
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
        
 
        public function filtro_prod_lista_precios(){
		   $result = array();
		   $id = $this->input->get('id', TRUE);
           $cli = $this->input->get('cli', TRUE);
		  
            $result = $this->productos->filtro_prod_lista_precios($id, $cli); 
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
				
        public function filtro_prod_orden(){
        
            $result = array();
            $result = $this->productos->get_term_orden($this->input->get('term', TRUE));
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
		        
        public function excel(){
             if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
            $this->load->library('phpexcel');
     
     
            $this->phpexcel->setActiveSheetIndex(0);
            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador del producto');
            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del producto');
            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Descripción');
            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio');
            $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre del impuesto');
            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Porciento');
            
            $query = $this->productos->excel();
            $row = 2;
            foreach ($query as $value) {
                $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $value->id_producto);
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $value->nombre);
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value->descripcion);
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $value->precio);
                $this->phpexcel->getActiveSheet()->setCellValue('E'.$row, $value->nombre_impuesto);
                $this->phpexcel->getActiveSheet()->setCellValue('F'.$row, $value->porciento);
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
            $this->phpexcel->getActiveSheet()->getStyle('A1:F'.--$row)->applyFromArray($styleThinBlackBorderOutline);
            $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				),
                                'bottom'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
            );
            // Rename worksheet
            $this->phpexcel->getActiveSheet()->setTitle('Productos');
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            //$this->phpexcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="productos.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        public function import_excel(){
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
            $config = array();
            if(!empty($_FILES)){
                
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'xls';
                
                $this->load->library('upload', $config);
                
                if(!empty($_FILES['archivo']['name'])){
                        if (!$this->upload->do_upload('archivo')){
                                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                        }
                        else
                        {
                            $upload_data = $this->upload->data();
                            $excel_name = $upload_data['file_name'];
                            
                            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/".$excel_name);
                            $reader->setReadDataOnly(TRUE);
                            $objXLS = $reader->load("uploads/".$excel_name);
                            
                            $campos[] = "No importar este campo";
                            for($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {
                                if($flag){
                                    $result = $alpha[$pointer].$alpha[$cursor];
                                    $cursor++;
                                    if($cursor >= (count($alpha) - 1)){
                                        $cursor = 0;
                                        $pointer++;
                                    }
                                }
                                else{
                                    $result = $alpha[$cursor];
                                    $cursor++;
                                    if($cursor >= (count($alpha) - 1)){
                                        $cursor = 0;
                                        $flag = true;
                                    }
                                }

                                if($objXLS->getSheet(0)->getCell($result.'1')->getValue() != ""){
                                    $campos[] = $objXLS->getSheet(0)->getCell($result.'1')->getValue();
                                }
                                else{
                                    break;
                                }
                            }
                            $data['campos'] = $campos;
                            $objXLS->disconnectWorksheets();
                            $this->session->set_flashdata("file_upload_productos", $excel_name);
                            unset($objXLS);
                            $this->layout->template('member')->show('productos/import_excel_fields', array('data' => $data));
                           
                        }
                    }
                
            }
            else if(isset($_POST["submit"])){
                $nombre_producto = $this->input->post("nombre_producto");
                $precio = $this->input->post("precio");
                $descripcion = $this->input->post("descripcion");
                $nombre_impuesto = $this->input->post("nombre_impuesto");     
                $porciento = $this->input->post("porciento");
                
                $excel_name = $this->session->flashdata("file_upload_productos");     
                $reader = PHPExcel_IOFactory::createReaderForFile("uploads/".$excel_name);
                $reader->setReadDataOnly(TRUE);
                $objXLS = $reader->load("uploads/".$excel_name);
                
                for($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {
                        if($flag){
                            $result = $alpha[$pointer].$alpha[$cursor];
                            $cursor++;
                            if($cursor >= (count($alpha) - 1)){
                                $cursor = 0;
                                $pointer++;
                            }
                        }
                        else{
                            $result = $alpha[$cursor];
                            $cursor++;
                            if($cursor >= (count($alpha) - 1)){
                                $cursor = 0;
                                $flag = true;
                            }
                        }

                        if($objXLS->getSheet(0)->getCell($result.'1')->getValue() != ""){
                            $campos[$result] = $objXLS->getSheet(0)->getCell($result.'1')->getValue();
                        }
                        else{
                            break;
                        }
                    }
               
                foreach ($campos as $key => $value) {
                    if($value == $nombre_producto){
                        $nombre_producto = $key;
                    }
                    else if($value == $porciento){
                        $porciento = $key;
                    }
                    else if($value == $precio){
                        $precio = $key;
                    }
                    else if($value == $nombre_impuesto){
                        $nombre_impuesto = $key;
                    }
                    else if($value == $descripcion){
                        $descripcion = $key;
                    }
                }
                
                
                $count = 2;
                $adicionados = 0;
                $noadicionados = 0;
                
                if($nombre_producto != 'No importar este campo' && $precio != 'No importar este campo' && $nombre_impuesto != 'No importar este campo' && $porciento != 'No importar este campo'){
                    while($objXLS->getSheet(0)->getCell($nombre_producto.$count)->getValue() != '' || $objXLS->getSheet(0)->getCell($porciento.$count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nombre_impuesto.$count)->getValue() != '' || $objXLS->getSheet(0)->getCell($precio.$count)->getValue() != ''){
                        $porcientoData = $objXLS->getSheet(0)->getCell($porciento.$count)->getValue();
                        $nombreImpuestoData = $objXLS->getSheet(0)->getCell($nombre_impuesto.$count)->getValue();
                        $nombreProductoData = $objXLS->getSheet(0)->getCell($nombre_producto.$count)->getValue();
                        $precioData = $objXLS->getSheet(0)->getCell($precio.$count)->getValue();
                        $descripcionData = "";
                         if($descripcion != 'No importar este campo')
                            $descripcionData = $objXLS->getSheet(0)->getCell($descripcion.$count)->getValue();
                         
                        if(!$this->productos->excel_exist($nombreProductoData, $precioData)){
                                $id_impuesto = $this->impuestos->excel_exist_get_id($nombreImpuestoData, $porcientoData);
                                $array_datos = array(
                                        "nombre"        => $nombreProductoData,
                                        "descripcion"  	=> $descripcionData,
                                        "precio"  	=> $precioData,
                                        "id_impuesto"  	=> $id_impuesto
                                );

                                $this->productos->excel_add($array_datos);
                                $adicionados++;
                            }
                            else{
                                $noadicionados++;
                            }
                        $count++;
                    }
                }
                else{
                    
                }
                
                $objXLS->disconnectWorksheets();
                unset($objXLS);
                $data['count'] = $count-2;
                $data['adicionados'] = $adicionados;
                $data['noadicionados'] = $noadicionados;
                unlink("uploads/$excel_name");
                
                $this->layout->template('member')->show('productos/import_complete', array('data' => $data));
                
            }
            else{
                $data['data']['upload_error'] = $error_upload;
                $this->layout->template('member')->show('productos/import_excel', array('data' => $data));
            }

        }
}      

?>

<?php

class Vendedores extends CI_Controller 

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

            

            $this->load->model("vendedores_model",'vendedores');

            $this->vendedores->initialize($this->dbConnection);

           $this->load->model("almacenes_model",'almacen'); 
		   
           $this->almacen->initialize($this->dbConnection);
           
           $this->load->model("miempresa_model", 'mi_empresa');
           $this->mi_empresa->initialize($this->dbConnection);

            $this->load->model("ventas_model", 'ventas');
           $this->ventas->initialize($this->dbConnection);

            $idioma = $this->session->userdata('idioma');

            $this->lang->load('sima', $idioma);

            $this->vendedores->actualizarTablaparaEstacion();

            $this->load->model('primeros_pasos_model');

            $this->load->model("new_count_model", 'newAcountModel');
            $this->newAcountModel->initialize($this->dbConnection);
        }

        

	public function index($offset = 0){	

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

        $action = "vendedores/index";       
        if($data["tipo_negocio"]=="restaurante"){ 
            $action = "vendedores/vendedores_estacion";
        }            
             

        $this->layout->template('member')->show($action,array('data' => $data));
    }
    
    public function username_check($str,$v=0)
    {           
        if($v!=0){
            $v=explode(",", $v);           
        }
        
        if(!empty($str)){  
              
            if($v==0){
                $where=array(
                    'codigo'=>$str
                );
            }else{
                $where=array(
                    'codigo'=>$str,
                    'id !='=> $v[1]
                );
            }
            $clavevenderdorestacion=$this->vendedores->existeCodigo($where);
            
            if($clavevenderdorestacion!=0){
                $this->form_validation->set_message('username_check', 'El %s ya está asignado a otro Vendedor');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }else{
            $this->form_validation->set_message('username_check', 'El %s no puede estar vacío');
            return FALSE;
        }   
            
    }

	public function nuevo($offset = 0){	

        if (!$this->ion_auth->logged_in()){
			redirect('auth', 'refresh');
        }
        
        $action = "vendedores/nuevo";        
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $form="vendedor";

        
            if($data["tipo_negocio"]=="restaurante"){                              
                $offset=-1;
                if ((isset($_POST['estacion_pedido']))&& ($_POST['estacion_pedido']==1)){
                    $form="vendedor_estacion";                                   
                }     
            }     
        
        if($form=="vendedor_estacion"){
            $form="";
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[254]|xss_clean');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|max_length[15]|xss_clean');
            $this->form_validation->set_rules('cedula', 'Cédula', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('almacen', 'Almacen', 'trim|required|max_length[254]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[254]|xss_clean');
            $this->form_validation->set_rules('codigo', 'Código', 'trim|required|min_length[4]|max_length[4]|callback_username_check[0]');
            
        }
        
		if($this->form_validation->run($form) == true){    
            

            $data = array(

                'email' => $this->input->post('email')

                ,'nombre' => $this->input->post('nombre')

                ,'telefono' => $this->input->post('telefono')

                ,'cedula' => $this->input->post('cedula')

                ,'comision' => $this->input->post('comision')

                ,'almacen' => $this->input->post('almacen')
                
                ,'estacion' => ($this->input->post('estacion_pedido')==1)? 1 : 0

                ,'codigo' => ($this->input->post('estacion_pedido')==1)? $this->input->post('codigo') : null
            );


            $this->vendedores->add($data);
            //guardar evento de primeros pasos vendedor
            $estadoBD = $this->newAcountModel->getUsuarioEstado();                    
            if($estadoBD["estado"]==2){
                $paso=13;
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
            
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Vendedor creado correctamente'));
            
            redirect('vendedores/index');
            
        }
    		 //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user='';
        $almacen='';
        $nombre='';	
        $user = $this->db->query("SELECT id FROM users where id = '".$user_id."' limit 1")->result();
        
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }	
				
        $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '".$id_user."' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
        }	

        $data['almacen_nombre'] = $nombre;
        $data['almacen_id'] = $almacen;		
        
	  //---------------------------------------------	               
        $data['almacen'] = $this->almacen->get_all('0',true);
        
        $this->layout->template('member')->show($action, array('data1' => $data,'data' => $data));

	}
    
    public function get_ajax_vendedores()
    {
        $result = $this->vendedores->get_term_vendedor($this->input->get('term', TRUE));

        $this->output->set_content_type('application/json')->set_output(json_encode($result));

    }


	public function get_ajax_data(){
        
        $this->output->set_content_type('application/json')->set_output(json_encode($this->vendedores->get_ajax_data(0)));

    }
    
    public function get_ajax_data_estacion(){

        $this->output->set_content_type('application/json')->set_output(json_encode($this->vendedores->get_ajax_data(1)));

    }

	public function editar($id,$offset=0){

            
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
        }
        
        $data = array();   
        $action = "vendedores/editar";
        $data["vendedor"]=1;
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $form="vendedor";

        if($data["tipo_negocio"]=="restaurante"){      
            if ((isset($_POST['estacion_pedido']))&& ($_POST['estacion_pedido']==1)){
                $form="vendedor_estacion";                                   
            }     
        }     
        
        if($form=="vendedor_estacion"){
            $form="";
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[254]|xss_clean');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|max_length[15]|xss_clean');
            $this->form_validation->set_rules('cedula', 'Cédula', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('almacen', 'Almacen', 'trim|required|max_length[254]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[254]|xss_clean');
            $this->form_validation->set_rules('codigo', 'Código', "trim|required|min_length[4]|max_length[4]|callback_username_check[1,$id]");
            
        }
    
        
        
		if ($this->form_validation->run($form) == true){

            $data = array(

                'id' => $this->input->post('id')

                ,'email' => $this->input->post('email')

                ,'nombre' => $this->input->post('nombre')

                ,'telefono' => $this->input->post('telefono')

                ,'cedula' => $this->input->post('cedula')
                
                ,'comision' => $this->input->post('comision')

                ,'almacen' => $this->input->post('almacen')
                
                ,'estacion' => ($this->input->post('estacion_pedido')==1)? 1 : 0

                ,'codigo' => ($this->input->post('codigo')!="")? $this->input->post('codigo') : null

            );

            $this->vendedores->update($data);

            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Vendedor actualizado correctamente'));

            redirect('vendedores/index');
            
        }                 
        $data['data']  = $this->vendedores->get_by_id($id);
        $data['almacen'] = $this->almacen->get_all('0',true);    		
        //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user='';
        $almacen='';
        $nombre='';	
        $user = $this->db->query("SELECT id FROM users where id = '".$user_id."' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }	
				
        $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '".$id_user."' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
        }	

        $data['almacen_nombre'] = $nombre;
        $data['almacen_id'] = $almacen;		                        
	  //---------------------------------------------	
        $this->layout->template('member')->show($action, array('data' => $data));

	}

        

        public function detalles($id)

        {

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

            $data = $this->productos->get_by_id($id);

            $this->layout->template('member')->show('productos/detalles', array('data' => $data));

        }

	

	public function eliminar($id)

	{	

        if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
        }
        
        //verificar si no tiene ventas asociadas
        $where = "(vendedor='$id' OR vendedor_2='$id')";      
        $ventas=$this->ventas->get_datos_una_venta_where($where);
       
        if(empty($ventas)){       
            $this->vendedores->delete($id);
            $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'Se ha eliminado correctamente'));
           
        }else{
            $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'No se ha podido eliminar el vendedor ya que tiene ventas asociadas'));
        }
         redirect("vendedores/index");
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
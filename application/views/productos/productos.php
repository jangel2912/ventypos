<?php

class Productos extends CI_Controller 

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

            

            $this->load->model("impuestos_model",'impuestos');

            $this->impuestos->initialize($this->dbConnection);

            

            $this->load->model("categorias_model",'categorias');

            $this->categorias->initialize($this->dbConnection);

            

            $this->load->model("almacenes_model",'almacenes');

            $this->almacenes->initialize($this->dbConnection);


            $this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);

            //Listas cliente ===========================================================

            $this->load->model("lista_precios_model",'lista_precios');

            $this->lista_precios->initialize($this->dbConnection);

            $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');

            $this->lista_detalle_precios->initialize($this->dbConnection);

            $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');

            $this->lista_detalle_precios->initialize($this->dbConnection);

            //Tipo de producto =========================================================

            $this->load->model("productos_tipo_model",'producto_tipo');

            $this->producto_tipo->initialize($this->dbConnection);

            //...........................................................................

            //Modelo unidades =========================================================
            $this->load->model("unidades_model",'unidades');
            $this->unidades->initialize($this->dbConnection);


            $this->load->library('pagination');

            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

            

            $idioma = $this->session->userdata('idioma');

            $this->lang->load('sima', $idioma);

        }

        

	public function index($offset = 0){	

        if (!$this->ion_auth->logged_in())
		redirect('auth', 'refresh');
               
        $this->layout->template('member')->show('productos/index');

	}


	public function nuevo(){	

        if (!$this->ion_auth->logged_in())
		redirect('auth', 'refresh');

        $error_upload = "";

		if ($this->form_validation->run('productos') == true) {

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size']	= '1024';

            $config['max_width']  = '900';

            $config['max_height']  = '500';

            $image_name = "";

            $this->load->library('upload', $config);

            if(!empty($_FILES['imagen']['name'])){

                

                if(!$this->upload->do_upload('imagen')){

                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                }else{

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];

                }

            }

            $active = isset($_POST['activo']) ? 1 : 0;

            if($_POST['is_ingrediente']!=1)
            $material = 0;
            else
            $material = 1;    

            $data = array(

                'imagen' => $image_name,

                "nombre"        => $this->input->post('nombre'),

                "codigo"        => $this->input->post('codigo'),

                "descripcion" => $this->input->post('descripcion'),

                "precio_venta"  	=> $this->input->post('precio'),

                "precio_compra" => $this->input->post('precio_compra'),

                "categoria_id"  	=> $this->input->post('categoria_id'),

                "impuesto"  	=> $this->input->post('id_impuesto'),

                'activo' => $active,

                'material' => $material

            );

            /*Guardar producto*/
            $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));

       
                switch ($_POST['tipo_producto']) {

                    //tipo producto
                    case 1:
                        $tProducto = true;
                    break;
                    //tipo ingrediente
                    case 2:

                        $ingredientes = $_POST['Ingrediente'];

                        $withIngredients = false; // bandera para productos con ingredientes
                        foreach ($ingredientes as $key => $value) {
                           if($key=='id'){
                            foreach ($value as $key2 => $id_ingrediente) {
                                if($id_ingrediente!='' && $id_ingrediente!=0){
                                    /*Ingrediente*/
                                    $ingrediente =  array(
                                        'id_ingrediente'=>$id_ingrediente,  
                                        'id_producto'=>$id_producto,
                                        'cantidad'=>$ingredientes['cantidad'][$key2]
                                    );
                                    /*Guardar ingrediente en producto_ingredientes*/
                                    $this->productos->addIngredient($ingrediente);
                                    $withIngredients = true;
                                }
                            }
                           }
                        }

                        /*Cambiar estado  (ingrediente = 1 -> tiene ingredientes) al producto*/
                        if($withIngredients)
                        $this->productos->withIngredients($id_producto);

                    break;
                    //tipo combo
                    case 3:

                        $isCombo = false; // bandera para combos
                        $productos_combo =  $_POST['productosCombo'];

                        foreach ($productos_combo as $key => $value) {
                            if($key=='id'){
                                foreach ($value as $key2 => $id_producto_combo) {
                                    if($id_producto_combo!='' && $id_producto_combo!=0){
                                        /*Ingrediente*/
                                        $producto_combo =  array(
                                            'id_combo'=>$id_producto,  
                                            'id_producto'=>$id_producto_combo,
                                            'cantidad'=>$productos_combo['cantidad'][$key2]
                                        );
                                        /*Guardar ingrediente en producto_ingredientes*/
                                        $this->productos->addProductCombo($producto_combo);
                                        $isCombo = true;
                                    }
                                } 
                            }
                        }

                        if($isCombo)
                        $this->productos->isCombo($id_producto);

                    break;
                    //tipo producto
                    default:
                        $tProducto = true;
                    break;

                }

          

         
                
            $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto creado correctamente'));

            redirect('productos/index');

        }

        $data = array();

        $data['data']['upload_error'] = $error_upload;

        $data['categorias'] = $this->categorias->get_combo_data();

        $data['impuestos'] = $this->impuestos->get_combo_data();

        $data['almacenes'] = $this->almacenes->get_combo_data();

        $data['tipo_productos'] = $this->producto_tipo->get_all();

        $data['unidades'] = $this->unidades->get_combo_data();

        $this->layout->template('member')->show('productos/nuevo', array('data' => $data));

	}


    public function nuevo_rapido(){    

        if (!$this->ion_auth->logged_in())
        redirect('auth', 'refresh');

        if ($this->form_validation->run('productos') == true) {

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size'] = '1024';

            $config['max_width']  = '900';

            $config['max_height']  = '500';

            $image_name = "";

            $this->load->library('upload', $config);

            if(!empty($_FILES['imagen']['name'])){

                

                if(!$this->upload->do_upload('imagen')){

                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                }else{

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];

                }

            }

            $active = isset($_POST['activo']) ? 1 : 0;
 

            $data = array(

                'imagen' => $image_name,

                "nombre"        => $this->input->post('nombre'),

                "codigo"        => $this->input->post('codigo'),

                "descripcion" => $this->input->post('descripcion'),

                "precio_venta"      => $this->input->post('precio'),

                "precio_compra" => $this->input->post('precio_compra'),

                "categoria_id"      => $this->input->post('categoria_id'),

                "impuesto"      => $this->input->post('id_impuesto'),

                'activo' => 1,

                'material' => 0

            );


            /*Guardar producto*/
            $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success'=>1)));
        }else{
             $this->output->set_content_type('application/json')->set_output(json_encode(array('success'=>0)));
        }

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

    public function productos_filter(){

        $result = array();

        $filter = $this->input->post('filter', TRUE);

        if(!empty($filter)){

            $this->productos->initialize($this->dbConnection);

            $result = $this->productos->get_term($filter, $this->session->userdata('user_id'));

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
       
    }

    public function productos_filter_group(){

        $result = array();

        $filter = $this->input->post('filter', TRUE);

        if(!empty($filter)){

            $type = $this->input->post('type');

            if($type == 'codificalo'){

                $productos =  $this->productos->get_by_codigo($filter, $this->session->userdata('user_id'));

            }else{

                $cliente = $this->input->post('cliente', TRUE);
                $this->productos->initialize($this->dbConnection);
                $productos = $this->productos->get_term($filter, $this->session->userdata('user_id'));

                if(!empty($cliente)){
                     //Cliente esta en grupo?
                    if($_POST['grupo']!=1){
                        //Grupo esta en una lista?/
                        $this->lista_precios->initialize($this->dbConnection);
                        $lista = $this->lista_precios->get_by_id($_POST['grupo']); //Lee si un grupo esta en una lista
                       
                        if(!empty($lista)){
            
                            foreach ($productos as $key => $value) {
                                 foreach ($value as $key2 => $value2) {
                                    if($key2=='id'){
                                        //Si el producto esta en una lista de detalle?/
                                        $this->lista_detalle_precios->initialize($this->dbConnection);
                                        $detalle= $this->lista_detalle_precios->get($lista['id'],$value2); //Lee una lista esta en un grupo
                                        /*Asigna nuevo precio*/
                                        if(!empty($detalle))
                                        $value->precio_venta = $detalle['precio'];
                                    }      
                                 }
                            }

                        } 
                    }
                } 

            }
               
            $this->output->set_content_type('application/json')->set_output(json_encode($productos));
           
        }

    }

    public function get_by_category($category_id){
        $productos =  $this->productos->get_by_category($category_id, $this->session->userdata('user_id'));
        $this->output->set_content_type('application/json')->set_output(json_encode($productos));
    }

    /*LIBRO DE PRECIOS ===============================================================*/
    public function libro_de_precios(){

        $data = array();
        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["almacenes"] = $this->almacenes->get_all(0);
        $data["lista_precios"] = $this->lista_precios->leer();
        $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
        
        $this->layout->template('member')->show('productos/libro_de_precios',$data);
    
    }

    public function ver_listas(){
        $data = array();
        $data["lista_precios"] = $this->lista_precios->leer();
        $this->layout->template('member')->show('productos/listas_de_precios',$data);  
    }

    //*Trae los productos filtrados por un termino*//
    public function productos_libro_precios_filter(){

        $result = array();

        $filter = $_GET['filter'];

        if(!empty($filter)){

            $this->productos->initialize($this->dbConnection);

            $result = $this->productos->get_term_two($filter, $this->session->userdata('user_id'));

            if(!empty($result)){
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>1,'data'=>$result) )
                );
            }else{
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>0) )
                );
            }
            

        }

    }
    /*....................................................................................*/

        

	public function editar($id){

	    $error_upload = "";

             if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

		if ($this->form_validation->run('productos') == true)

                    {

                            $config['upload_path'] = 'uploads/';

                            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

                            $config['max_size']	= '1024';

                            $config['max_width']  = '580';

                            $config['max_height']  = '300';

                            $image_name = "";

                            $this->load->library('upload', $config);

                            if(!empty($_FILES['imagen']['name'])){

                                if (!$this->upload->do_upload('imagen')){

                                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                                }  else {

                                    $upload_data = $this->upload->data();

                                    $image_name = $upload_data['file_name'];

                                }

                            }

                            $active = isset($_POST['activo']) ? 1 : 0;

                            $data = array(

                                    'id' => $this->input->post('id'),

                                     "nombre"        => $this->input->post('nombre'),

                                     "codigo"        => $this->input->post('codigo'),

                                     "descripcion" => $this->input->post('descripcion'),

                                     "precio_venta"  	=> $this->input->post('precio'),

                                     "precio_compra" => $this->input->post('precio_compra'),

                                     "categoria_id"  	=> $this->input->post('categoria_id'),
									 
                                     "unidad_id"  	=> $this->input->post('id_unidades'),									 

                                     "impuesto"  	=> $this->input->post('id_impuesto')

                                     ,'activo' => $active

                             );

                            

                            if(!empty($image_name))

                                {

                                    $data['imagen'] = $image_name;

                                }

                            

                            if($error_upload == ""){

                                $this->productos->update($data, $this->session->userdata('user_id'));

                                $id_producto = $data['id'];

                                //Ingredientes ================================================

                                $ingredientes = $_POST['Ingrediente'];

                                $this->productos->delete_ingredientes($id_producto);

                                $withIngredients = false; // bandera para productos con ingredientes
                                foreach ($ingredientes as $key => $value) {
                                   if($key=='id'){
                                    foreach ($value as $key2 => $id_ingrediente) {
                                        if($id_ingrediente!='' && $id_ingrediente!=0){
                                            /*Ingrediente*/
                                            $ingrediente =  array(
                                                'id_ingrediente'=>$id_ingrediente,  
                                                'id_producto'=>$id_producto,
                                                'cantidad'=>$ingredientes['cantidad'][$key2]
                                            );
                                            /*Guardar ingrediente en producto_ingredientes*/
                                            $this->productos->addIngredient($ingrediente);
                                            $withIngredients = true;
                                        }
                                    }
                                   }
                                }

                                /*Cambiar estado  (ingrediente = 1 -> tiene ingredientes) al producto*/
                                if($withIngredients)
                                $this->productos->withIngredients($id_producto);

                                //Productos ================================================

                                $isCombo = false; // bandera para combos

                                $this->productos->delete_productos_combo($id_producto);

                                $productos_combo =  $_POST['productosCombo'];


                                foreach ($productos_combo as $key => $value) {
                                    if($key=='id'){
                                        foreach ($value as $key2 => $id_producto_combo) {
                                            if($id_producto_combo!='' && $id_producto_combo!=0){
                                                /*Ingrediente*/
                                                $producto_combo =  array(
                                                    'id_combo'=>$id_producto,  
                                                    'id_producto'=>$id_producto_combo,
                                                    'cantidad'=>$productos_combo['cantidad'][$key2]
                                                );
                                                /*Guardar ingrediente en producto_ingredientes*/
                                                $this->productos->addProductCombo($producto_combo);
                                                $isCombo = true;
                                            }
                                        } 
                                    }
                                }

                                if($isCombo)
                                $this->productos->isCombo($id_producto);


                                $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto actualizado correctamente'));

                                redirect('productos/index');

                            }

                            

                    }

                    

                $data = array();   

		        $data['data']  = $this->productos->get_by_id($id);

                if($data['data']['ingredientes']==1){
                    $data['ingredientes']  = $this->productos->get_ingredientes($id);
                }else{
                    $data['ingredientes'] = array();
                }

                if($data['data']['material']==1){
                    $data['material']  = 1;
                }else{
                    $data['material']  = 0;
                }

                if($data['data']['combo']==1){
                    $data['productos_combo']  =  $this->productos->get_productos_combo($id);
                }else{
                    $data['productos_combo'] = array();
                }
           

                $data['data']['upload_error'] = $error_upload;

                $data['categorias'] = $this->categorias->get_combo_data();

                $data['almacenes'] = $this->almacenes->get_combo_data_stock_actual($id);

                $data['impuestos'] = $this->impuestos->get_combo_data();

                $data['tipo_productos'] = $this->producto_tipo->get_all();
				
				 $data['unidades'] = $this->unidades->get_combo_data_unidades();

                $this->layout->template('member')->show('productos/editar', array('data' => $data));

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

		$this->productos->delete($id);

		$this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));

		redirect("productos");

	}


    public function filtro_prod_existencia(){

            $type = $this->input->get('almacen');

            $filter = $this->input->get('term', TRUE);

            

            $result = $this->productos->get_term_existencias($filter, $type);

            $this->output->set_content_type('application/json')->set_output(json_encode($result));

    }


        

        public function excel(){

             if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

                

            ini_set("memory_limit","1048M");

            $this->load->library('phpexcel');

     

     

            $this->phpexcel->setActiveSheetIndex(0);

            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador del producto');

            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del producto');

            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Descripción');

            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio');

            $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre del impuesto');

            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Porciento');

            

            $query = $this->productos->excel();

           /* echo "<pre>";

                print_r($query);

            echo "</pre>";

            die; */

            $row = 2;

            foreach ($query as $value) {

                $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $value->id);

                $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $value->nombre);

                $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value->descripcion);

                $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $value->precio_venta);

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

            ob_clean();

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
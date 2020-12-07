<?php

class Categorias extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);
        
        $this->load->model("impresoras_restaurante_model", 'impresoras');
        $this->impresoras->initialize($this->dbConnection);
        

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("impresora_rest_categoria_almacen_model", 'impresoras_categoria_almacen');
        $this->impresoras_categoria_almacen->initialize($this->dbConnection);

        //     $this->load->model("atributos_model",'atributos');
        //   $this->atributos->initialize($this->dbConnection);            
        //$this->load->model("impuestos_model",'impuestos');
        //$this->impuestos->initialize($this->dbConnection);



        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');



        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    public function index($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->categorias->updateColumnCategoria();

        $data = array();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('categorias/index',array("data" => $data));
    }

    public function nuevo() {
        $error_upload = "";

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($_POST) {
            $base_dato = $this->session->userdata('base_dato');
            $carpeta = 'uploads/'.$base_dato.'/categorias_productos';
            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }   


            $config['upload_path'] = $carpeta;

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size'] = '2024';

            $config['max_width'] = '200000';

            $config['max_height'] = '2000000';



            $this->load->library('upload', $config);

            $image_name = "";

            if (!empty($_FILES['imagen']['name'])) {

                if (!$this->upload->do_upload('imagen')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];
                }
            }

            $active = isset($_POST['activo']) ? 1 : 0;
            $active = isset($_POST['tienda']) ? 1 : 0;

            
            if($this->input->post('categorias') != "Null")
            {
                $data = array(
                    'imagen' => $image_name,
                    'codigo' => $this->input->post('codigo'),
                    'nombre' => $this->input->post('nombre'),
                    'activo' => $active,
                    'padre' => $this->input->post('categorias'),
                    'tienda' => $this->input->post('tienda'),
                    'es_menu_principal_tienda' => $this->input->post('menu_categorias_tienda')
                );
            } else {
                $data = array(
                    'imagen' => $image_name,
                    'codigo' => $this->input->post('codigo'),
                    'nombre' => $this->input->post('nombre'),
                    'activo' => $active,
                    'es_menu_principal_tienda' => $this->input->post('menu_categorias_tienda')  
                );
            }
            if(isset($_POST['tienda']))
            {
                $data['tienda'] = $this->input->post('tienda');
            }            
            
            $cate = $this->categorias->add($data, isset($_POST['atributos'])? $_POST['atributos']: null);

            if((isset($_POST['id_impresora']))&& (!empty($_POST['id_impresora'])))
            {
                $impresora = $this->input->post('id_impresora');
                //$almacen   = $this->impresoras->impresora_get_almacen($impresora);  
                $almacen   = $this->almacenes->getAll();   

                $dataimpresora = array(
                    'id_impresora' => $impresora,
                    'id_categoria' => $cate,
                    'almacen' => $almacen             
                );
               
                $this->impresoras_categoria_almacen->add($dataimpresora); 
            }            
            
            $data['categorias'] = $this->categorias->getSelect();            
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Categoria creado correctamente'));

            redirect('categorias/index');
        }
        $this->impresoras->createtableimpresora_restaurante();
        $this->impresoras_categoria_almacen->createtable_impresoras_categoria_almacen();
        $data = array();

        $data['data']['upload_error'] = $error_upload;
        $data['categorias'] = $this->categorias->getSelect();
        $data['impresoras'] = $this->impresoras->get_impresoras();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        // $atributos = $this->atributos->get_combo_data();
        $atributos = array();

        $this->layout->template('member')->js(base_url() . "/public/js/plugins/multiselect/jquery.multi-select.min.js")
            ->show('categorias/nuevo', array('data' => $data, 'atributos' => $atributos));
    }

    public function category_check($str) {

        $id = $this->categorias->get_by_name($str);

        if (!empty($id)) {

            $id_producto = $this->input->post('id');

            if (!empty($id_producto) && $id_producto == $id) {

                return true;
            }

            $this->form_validation->set_message('product_check', 'El %s existe');

            return false;
        }

        return true;
    }

    public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->categorias->get_ajax_data()));
    }

    public function editar($id) {

        $error_upload = "";

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('categorias') == true) {
            $base_dato = $this->session->userdata('base_dato');
            $carpeta = 'uploads/'.$base_dato.'/categorias_productos';
            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }


            $config['upload_path'] = $carpeta;

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size'] = '2024';

            $config['max_width'] = '200000';

            $config['max_height'] = '2000000';



            $this->load->library('upload', $config);

            $image_name = "";

            if (!empty($_FILES['imagen']['name'])) {

                if (!$this->upload->do_upload('imagen')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];
                }
            }

            $active = isset($_POST['activo']) ? 1 : 0;


            if($this->input->post('categorias') != "Null")
            {
                $data = array(
                    'id'=>$id,
                    'imagen' => $image_name,
                    'codigo' => $this->input->post('codigo'),
                    'nombre' => $this->input->post('nombre'),
                    'activo' => $active,
                    'padre' => $this->input->post('categorias'),
                    'tienda' => $this->input->post('tienda'),
                    'es_menu_principal_tienda' => $this->input->post('menu_categorias_tienda'),
                );
            } else {
                $data = array(
                    'id'=>$id,
                    'imagen' => $image_name,
                    'codigo' => $this->input->post('codigo'),
                    'nombre' => $this->input->post('nombre'),
                    'activo' => $active,
                    'padre' => NULL,
                    'tienda' => $this->input->post('tienda'),
                    'es_menu_principal_tienda' => $this->input->post('menu_categorias_tienda')
                );
            }
            
            if (!empty($image_name)) {

                $data['imagen'] = $image_name;
            }else{
                unset($data['imagen']);
            }

            
            if(isset($_POST['tienda']))
            {
                $data['tienda'] = $this->input->post('tienda');
            }
           // var_dump($data);


            if ($error_upload == "") {

                $this->categorias->update($data);

                //$almacen   = $this->impresoras->impresora_get_almacen($this->input->post('id_impresora'));
                
                if((isset($_POST['id_impresora']))&& (!empty($_POST['id_impresora'])))
                {
                    $almacen   = $this->almacenes->getAll();
                    
                    $dataimpresora = array(
                        'id_impresora' => $this->input->post('id_impresora'),
                        'id_categoria' => $id,
                        'almacen' => $almacen               
                    );
                    $this->impresoras_categoria_almacen->delete($id);
                    $this->impresoras_categoria_almacen->update($dataimpresora);
                }
                

                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Categoria actualizado correctamente'));

                redirect('categorias/index');
            }
        }

        $data = array();
        $data['categorias'] = $this->categorias->getSelect();
        $data['impresoras'] = $this->impresoras->get_impresoras();
        $data['impresora_cate_almacen'] = $this->impresoras_categoria_almacen->impresora_cate_get_by_idimpresora_cate($id);
        $data['data'] = $this->categorias->get_by_id($id);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        //          $data['atributos'] = $this->atributos->get_combo_data();
        //      $data['atributos_selected'] = $this->categorias->get_combo_atributos_selected_data($id);

        $data['data']['upload_error'] = $error_upload;

        $this->layout->template('member')->js(base_url() . "/public/js/plugins/multiselect/jquery.multi-select.min.js")->show('categorias/editar', array('data' => $data));
    }


    public function changeStatus($status, $field, $id){
        $active = ((int) $status) ? false : true;
        $data = array(
            'id'=>$id,
            $field => $active
        );
        // var_dump($data);
        // die();
        $this->categorias->update($data);
        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Categoria actualizado correctamente'));
        redirect('categorias/index');
    }

    public function detalles($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data = $this->productos->get_by_id($id);

        $this->layout->template('member')->show('productos/detalles', array('data' => $data));
    }

    public function limit($offset) {
        $data = $this->categorias->get_limit($offset);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function eliminar($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $mensaje = $this->categorias->delete($id);
        if($mensaje=="Se ha eliminado correctamente"){
            $this->impresoras_categoria_almacen->delete($id);
        }
       

        $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', $mensaje));
        redirect("categorias/index");
    }

    public function excel() {

        if (!$this->ion_auth->logged_in()) {

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

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->id_producto);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->nombre);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->descripcion);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->precio);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->nombre_impuesto);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->porciento);

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

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0'
                        ),
                        'endcolor' => array(
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

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0



        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function import_excel() {

        if (!$this->ion_auth->logged_in()) {

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

        if (!empty($_FILES)) {



            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xls';



            $this->load->library('upload', $config);



            if (!empty($_FILES['archivo']['name'])) {

                if (!$this->upload->do_upload('archivo')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {

                    $upload_data = $this->upload->data();

                    $excel_name = $upload_data['file_name'];



                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

                    $reader->setReadDataOnly(TRUE);

                    $objXLS = $reader->load("uploads/" . $excel_name);



                    $campos[] = "No importar este campo";

                    for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                        if ($flag) {

                            $result = $alpha[$pointer] . $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $pointer++;
                            }
                        } else {

                            $result = $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $flag = true;
                            }
                        }



                        if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                            $campos[] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                        } else {

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
        } else if (isset($_POST["submit"])) {

            $nombre_producto = $this->input->post("nombre_producto");

            $precio = $this->input->post("precio");

            $descripcion = $this->input->post("descripcion");

            $nombre_impuesto = $this->input->post("nombre_impuesto");

            $porciento = $this->input->post("porciento");



            $excel_name = $this->session->flashdata("file_upload_productos");

            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

            $reader->setReadDataOnly(TRUE);

            $objXLS = $reader->load("uploads/" . $excel_name);



            for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                if ($flag) {

                    $result = $alpha[$pointer] . $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $pointer++;
                    }
                } else {

                    $result = $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $flag = true;
                    }
                }



                if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                    $campos[$result] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                } else {

                    break;
                }
            }



            foreach ($campos as $key => $value) {

                if ($value == $nombre_producto) {

                    $nombre_producto = $key;
                } else if ($value == $porciento) {

                    $porciento = $key;
                } else if ($value == $precio) {

                    $precio = $key;
                } else if ($value == $nombre_impuesto) {

                    $nombre_impuesto = $key;
                } else if ($value == $descripcion) {

                    $descripcion = $key;
                }
            }





            $count = 2;

            $adicionados = 0;

            $noadicionados = 0;



            if ($nombre_producto != 'No importar este campo' && $precio != 'No importar este campo' && $nombre_impuesto != 'No importar este campo' && $porciento != 'No importar este campo') {

                while ($objXLS->getSheet(0)->getCell($nombre_producto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($porciento . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($precio . $count)->getValue() != '') {

                    $porcientoData = $objXLS->getSheet(0)->getCell($porciento . $count)->getValue();

                    $nombreImpuestoData = $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue();

                    $nombreProductoData = $objXLS->getSheet(0)->getCell($nombre_producto . $count)->getValue();

                    $precioData = $objXLS->getSheet(0)->getCell($precio . $count)->getValue();

                    $descripcionData = "";

                    if ($descripcion != 'No importar este campo')
                        $descripcionData = $objXLS->getSheet(0)->getCell($descripcion . $count)->getValue();



                    if (!$this->productos->excel_exist($nombreProductoData, $precioData)) {

                        $id_impuesto = $this->impuestos->excel_exist_get_id($nombreImpuestoData, $porcientoData);

                        $array_datos = array(
                            "nombre" => $nombreProductoData,
                            "descripcion" => $descripcionData,
                            "precio" => $precioData,
                            "id_impuesto" => $id_impuesto
                        );



                        $this->productos->excel_add($array_datos);

                        $adicionados++;
                    } else {

                        $noadicionados++;
                    }

                    $count++;
                }
            } else {
                
            }



            $objXLS->disconnectWorksheets();

            unset($objXLS);

            $data['count'] = $count - 2;

            $data['adicionados'] = $adicionados;

            $data['noadicionados'] = $noadicionados;

            unlink("uploads/$excel_name");



            $this->layout->template('member')->show('productos/import_complete', array('data' => $data));
        } else {

            $data['data']['upload_error'] = $error_upload;

            $this->layout->template('member')->show('productos/import_excel', array('data' => $data));
        }
    }

    public function validateNombreyCodigo() {
        $result = 0;      
        $result = $this->categorias->validateNombreyCodigo($this->input->post('id'),$this->input->post('campo'));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

}

?>
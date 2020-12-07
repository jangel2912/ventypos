<?php

class Productos extends CI_Controller {

    const ATRIBUTOS = 3;

    var $dbConnection;
    var $user;

    function __construct() {

        parent::__construct();

        $this->load->helper('logs_helper');

        $this->user = $this->session->userdata('user_id');
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);

        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("proveedores_model", 'proveedores');
        $this->proveedores->initialize($this->dbConnection);

        $this->load->model("clientes_model", 'clientes');
        $this->clientes->initialize($this->dbConnection);

        $this->load->model("stock_actual_model", 'stock_actual');
        $this->stock_actual->initialize($this->dbConnection);

        $this->load->model("stock_diario_model", 'stock_diario');
        $this->stock_diario->initialize($this->dbConnection);

        //Listas cliente ===========================================================

        $this->load->model("lista_precios_model", 'lista_precios');
        $this->lista_precios->initialize($this->dbConnection);

        $this->load->model("lista_detalle_precios_model", 'lista_detalle_precios');
        $this->lista_detalle_precios->initialize($this->dbConnection);

        $this->load->model("lista_detalle_precios_model", 'lista_detalle_precios');
        $this->lista_detalle_precios->initialize($this->dbConnection);

        //Tipo de producto =========================================================

        $this->load->model("productos_tipo_model", 'producto_tipo');
        $this->producto_tipo->initialize($this->dbConnection);

        //...........................................................................
        //Modelo unidades =========================================================
        $this->load->model("unidades_model", 'unidades');
        $this->unidades->initialize($this->dbConnection);

        $this->load->model("opciones_model", "opciones");
        $this->opciones->initialize($this->dbConnection);

        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->model("inventario_model", 'inventario');
        $this->inventario->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("productos_seriales_model","productos_seriales");
        $this->productos_seriales->initialize($this->dbConnection);

        $this->load->library('phpexcel');

        $this->load->model('primeros_pasos_model');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);
        
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        $this->load->model("grupo_model", 'grupo');
        $this->grupo->initialize($this->dbConnection);

        //verificar si el cliente tiene precio por almacen para actualizarlos
        $precio_almacen = get_option('precio_almacen');            
        if($precio_almacen==1){
            $product = $this->productos->getList_NULL();
            $data = array();
            foreach($product as $rowProduct){
                $data = array(
                    'precio_compra' => floatval($rowProduct->precio_compra),
                    'precio_venta' => floatval($rowProduct->precio_venta),
                    'stock_minimo' => intval($rowProduct->stock_minimo),
                    'impuesto' => floatval($rowProduct->impuesto),
                    'fecha_vencimiento' => floatval($rowProduct->fecha_vencimiento),
                    'activo' => intval($rowProduct->activo),
                );
                $this->stock_actual->update_by_product($data,$rowProduct->id);            
            }
        }
    }

    public function index($offset = 0) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data['precio_almacen'] = $this->opciones->getOpcion('precio_almacen');
        $data['atributos'] = $this->almacenes->verificar_modulo_habilitado($this->user, self::ATRIBUTOS);
        $data['tipo_negocio'] =  $this->opciones->getOpcion("tipo_negocio");
        $permissions = $this->session->userdata('permisos'); 
        if($this->session->userdata('is_admin') != "t" && !in_array(2,$permissions)){
            redirect(site_url('frontend/index'));
        }else{
        $random = rand();
         $this->layout->template('member')->js(array(
            base_url("/public/js/imageFinder.js?$random"),
            base_url("/public/js/imageFinderNuevoProducto.js?$random"),
        ))->css(array(
            base_url("/public/css/imageFinder.css?$random")
        ))->show('productos/index', ['data' => $data]);
        }
    }

    /**
     * Rutina para poder llamar al php que consulte en base de datos
     * y devuelve la respuessta al javascript que lo solicito por el ajax
     * mandandolo por json
     */
    public function validateCodigo() {
        $result = 0;
        $result = $this->productos->validatecodigo($this->input->post('codigo'));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Rutina para poder llamar al php que consulte en base de datos
     * y devuelve la respuessta al javascript que lo solicito por el ajax
     * mandandolo por json
     */
    public function validateCodigoPuntosLeal() {
        $result = 0;
        $result = $this->productos->validatecodigoPuntosLeal($this->input->post('codigo_puntos_leal'));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function codigo_print_factura() {

        $data = array();

        if(isset($_POST)){
            $seccion=$this->input->post('seccion');
            $id=$this->input->post('orden_id');
            $columna=$this->input->post('columna');
        }
        
        switch ($seccion) {
            case 'ventas':
                $factura = $this->dbConnection->query("SELECT producto_id, unidades,impuesto FROM detalle_venta where venta_id = '$id' ")->result();
                break;
            case 'orden':
                $factura = $this->dbConnection->query("SELECT producto_id, unidades,impuesto FROM detalle_orden_compra where venta_id = '$id' ")->result();
                break;
        }

        foreach ($factura as $factura1) {

            $producto = $this->dbConnection->query("SELECT * FROM producto where id = '$factura1->producto_id' ")->result();

            foreach ($producto as $value) {

                $talla = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where referencia = '$value->codigo' and id_atributo = '4' ")->result();
                $talla_final = '';
                foreach ($talla as $talla1) {
                    $talla_final = $talla1->nombre_clasificacion;
                }

                $color = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where referencia = '$value->codigo' and id_atributo = '3' ")->result();
                $color_final = '';
                foreach ($color as $color1) {
                    $color_final = $color1->nombre_clasificacion;
                }

                $marca = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where referencia = '$value->codigo' and id_atributo = '1' ")->result();
                $marca_final = '';
                foreach ($marca as $marca1) {
                    $marca_final = $marca1->nombre_clasificacion;
                }
                for ($i = 1; $i <= $factura1->unidades; $i++) {
                    $data[] = array(
                        'nombre' => $value->nombre
                        , 'precio_venta' => $value->precio_venta + ($value->precio_venta*($factura1->impuesto/100))
                        , 'codigo' => $value->codigo
                        , 'talla' => $talla_final
                        , 'color_final' => $color_final
                        , 'marca_final' => $marca_final,
                    );
                }
            }
            //-----
        }

        if(!empty($columna) && $columna==1){
            $this->layout->template('ajax')->show('productos/_imprime', array('producto' => $data));
        }else{
            if(!empty($columna) && $columna==2){
                $this->layout->template('ajax')->show('productos/_imprimecodigodoble', array('producto' => $data));
            }
        }  
    }

    public function codigo_print_producto($id = 0) {

        $data = array();

        $producto = $this->dbConnection->query("SELECT * FROM producto limit 100 ")->result();

        foreach ($producto as $value) {

            $talla = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where codigo_interno = '$value->codigo_barra' and id_atributo = '4' ")->result();
            $talla_final = '';
            foreach ($talla as $talla1) {
                $talla_final = $talla1->nombre_clasificacion;
            }

            $color = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where codigo_interno = '$value->codigo_barra' and id_atributo = '3' ")->result();
            $color_final = '';
            foreach ($color as $color1) {
                $color_final = $color1->nombre_clasificacion;
            }

            $marca = $this->dbConnection->query("SELECT nombre_clasificacion FROM atributos_productos where codigo_interno = '$value->codigo_barra' and id_atributo = '1' ")->result();
            $marca_final = '';
            foreach ($marca as $marca1) {
                $marca_final = $marca1->nombre_clasificacion;
            }
            for ($i = 1; $i <= 10; $i++) {
                $data[] = array(
                    'nombre' => $value->nombre
                    , 'precio_venta' => $value->precio_venta
                    , 'codigo' => $value->codigo
                    , 'talla' => $talla_final
                    , 'color_final' => $color_final
                    , 'marca_final' => $marca_final,
                );
            }
        }

        $this->layout->template('ajax')->show('productos/_imprime', array('producto' => $data));
    }

    public function eliminar_unidades() {
        $id=$this->input->post('id');
        if(!empty($id)){
            //verificar que la unidad pueda eliminarse
            $puedo=$this->unidades->puedo_eliminar_unidad(array('u.id' => $id));
            
            if($puedo==0){
                $this->unidades->eliminar_unidad(array('id' => $id));
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1,'msm' => 'La unidad fue eliminada exitosamente')));
            }else{
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msm' => 'La unidad no pudo ser eliminada, tiene productos asociadas')));
            }
        }else{
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msm' => 'Error intente más tarde')));
        }
    }

    public function unidades() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
                
        if($_POST){
            $nombre=trim($this->input->post('nombre')); 
            if(!empty($nombre)){
                $re = '/[\;\`\'\*\~\´\¨]/';
                $acento='/á|é|í|ó|ú|Á|É|Í|Ó|Ú|à|è|ì|ò|ù|À|È|Ì|Ò|Ù|ä|ë|ï|ö|ü|Ä|Ë|Ï|Ö|Ü|â|ê|î|ô|û|Â|Ê|Î|Ô|Û|ý|Ý|ÿ/';
                preg_match($re, $nombre, $matches, PREG_OFFSET_CAPTURE, 0); 
                preg_match($acento, $nombre, $matches2, PREG_OFFSET_CAPTURE, 0); 

                if ((count($matches) > 0 ) || (count($matches2) > 0 )) {                    
                    $this->session->set_flashdata('alert_message', custom_lang('sima_product_created_message', 'error'));
                    $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'El nombre del producto es incorrecto. Por favor no use caracteres especiales'));
                }else{
                    //verificar que no exista
                    $existe=$this->unidades->validar_unidad(array('nombre' => $nombre));
                    if($existe!=0){                
                        $this->session->set_flashdata('alert_message', custom_lang('sima_product_created_message', 'error'));
                        $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'El nombre de la unidad ya existe'));
                    }else{
                        //insertar unidad
                        $data = array(
                            "nombre" => $nombre                            
                        );

                        $id=$this->unidades->insertar_unidad($data);

                        if(!empty($id)){                            
                            $this->session->set_flashdata('alert_message', custom_lang('sima_product_created_message', 'success'));
                            $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'La unidad se guardó exitosamente'));
                        }else{                            
                            $this->session->set_flashdata('alert_message', custom_lang('sima_product_created_message', 'error'));
                            $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'La unidad no pudo ser ingresada, por favor intente más tarde'));
                        }                        
                    }
                    
                }

            }else{                
                $this->session->set_flashdata('alert_message', custom_lang('sima_product_created_message', 'error'));
                $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'El nombre de la unidad debe ser obligatoria'));
            }         
            redirect('productos/unidades');
        }
        else{
            $data['unidades']=$this->unidades->get_combo_data_unidades();        
            $this->layout->template('member')->show('productos/unidades', array('data' => $data));
        }

    }

    public function upload_zip_photo() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');
        $this->layout->template('member');

        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        $carpeta_s3 = $base_dato.'/imagenes_productos/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        //validacion de adjuntos
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'zip';
        $config['max_size'] = '102400';
        /*$config['max_width'] = '1200';
        $config['max_height'] = '1200';*/

        $this->load->library('upload', $config);
        $this->load->library('zip');
        $this->load->helper('directory');

        $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp','JPG', 'JPEG', 'PNG', 'GIF', 'SVG', 'WEBP');
        if (!empty($_FILES['zip_file']['name'])) {
            if ( ! $this->upload->do_upload('zip_file')) {
                $this->session->set_flashdata('error', "Ha ocurrido un error al subir el archivo. Verifique que el archivo tenga la extension .zip y sea menor de 100 MB.");
                redirect('frontend/configuracion','refresh');

            } else {
                $data = array('upload_data' => $this->upload->data());
                $full_path = $data['upload_data']['full_path'];
                 
                /**** without library ****/
                $zip = new ZipArchive;
     
                if ($zip->open($full_path) === TRUE) 
                {
                    $zip->extractTo(FCPATH . $carpeta);
                    $zip->close();
                }

                $map = directory_map($carpeta);

                $directories_to_remove = array();
                foreach($map as $key => $resource) {
                    if(is_array($resource)) {
                        $directories_to_remove[] = $carpeta . "/" .$key;
                        foreach($resource as $image) {
                            if(!is_array($image)){
                                $image_path = $carpeta . "/" . $key . "/" . $image;
                                if(file_exists($image_path)) {
                                    $path_parts = pathinfo($image_path);
                                    if(isset($path_parts['extension']) && in_array($path_parts['extension'], $valid_extensions)) {
                                        copy($image_path, $carpeta . "/" . $image);
                                        $trim_clear_image_names = trim($path_parts['filename']) . "." . $path_parts['extension'];
                                        $clear_image_names = $this->_clean($trim_clear_image_names);
                                        $file =  file_get_contents($carpeta . "/" . $image , $carpeta . "/" . $clear_image_names); 
                                        rename($carpeta . "/" . $image , $carpeta . "/" . $clear_image_names);
                                        $object = $this->s3->putObject($file, 'vendty-img', $carpeta_s3.$clear_image_names, 'private', []);
                                    }
                                }       
                            }
                        }
                    } else {
                        $path_parts = pathinfo($carpeta . "/" . $resource);
                        if(isset($path_parts['extension']) && in_array($path_parts['extension'], $valid_extensions)) {
                            $trim_clear_image_names = trim($path_parts['filename']) . "." . $path_parts['extension'];
                            $clear_image_names = $this->_clean($trim_clear_image_names);
                            $file =  file_get_contents($carpeta . "/" . $resource , $carpeta . "/" . $clear_image_names); 
                            rename($carpeta . "/" . $resource , $carpeta . "/" . $clear_image_names);
                            $object = $this->s3->putObject($file, 'vendty-img', $carpeta_s3.$clear_image_names, 'private', []);
                        }
                    }
                }

                if(!empty($directories_to_remove)){
                    foreach($directories_to_remove as $directory) {
                        self::deleteDir($directory);
                    }
                }

                if(file_exists($full_path)) {
                    unlink($full_path);
                }
     
                $this->session->set_flashdata('message', "Archivo subido correctamente.");
                redirect('frontend/configuracion','refresh');
            }
        }
    }

    function _clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9-_\.]/', '', $string); // Removes special chars.
    }

    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function nuevo() {
        $this->productos->check_tabla_seriales();
        $this->productos->validate_tipo_producto_imei();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $error_upload = "";
        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        } 
        //validacion de adjuntos
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';
        $config['max_size'] = '300';
        $config['max_width'] = '1200';
        $config['max_height'] = '1200';
        $image_name = "";
        $image_name1 = "";
        $image_name2 = "";
        $image_name3 = "";
        $image_name4 = "";
        $image_name5 = "";
        $carpeta_s3 = $base_dato.'/imagenes_productos/';
        $this->load->library('upload', $config);
        if (!empty($_FILES['imagen']['name'])) {

            $image_name = $_FILES['imagen']['name'];
            


            if (!$this->upload->do_upload('imagen')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name, 'private', []);
        }
        if (!empty($_FILES['imagen1']['name'])) {
            $image_name = $_FILES['imagen1']['name'];
            

            if (!$this->upload->do_upload('imagen1')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name1 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen1']['tmp_name'], true);

            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name1, 'private', []);
        }
        if (!empty($_FILES['imagen2']['name'])) {
            $image_name = $_FILES['imagen2']['name'];

            if (!$this->upload->do_upload('imagen2')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name2 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen2']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name2, 'private', []);
        }
        if (!empty($_FILES['imagen3']['name'])) {
            $image_name = $_FILES['imagen3']['name'];


            if (!$this->upload->do_upload('imagen3')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name3 = $upload_data['file_name'];
            }
            $input = $this->s3->inputFile($_FILES['imagen3']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name3, 'private', []);
        }
        if (!empty($_FILES['imagen4']['name'])) {
            $image_name = $_FILES['imagen4']['name'];


            if (!$this->upload->do_upload('imagen4')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name4 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen4']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name4, 'private', []);
        }
        if (!empty($_FILES['imagen5']['name'])) {

            $image_name = $_FILES['imagen5']['name'];


            if (!$this->upload->do_upload('imagen5')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name5 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen5']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name5, 'private', []);
        }

        if(!empty($error_upload)){
            $error_upload.='<p class="text-error"> Tenga en cuenta: tamaño máximo de archivo '.$config['max_size'].' kb, alto y ancho de imagen: 250px.</p>';
        }

        if ($this->form_validation->run('productos') == true && empty($error_upload) ) {
            $active = isset($_POST['activo']) ? 1 : 0;
            if (isset($_POST['is_ingrediente'])) {
                $material = 1;
            } else {
                $material = 0;
            }

            $re = '/[\;\`\'\*\~\´\¨]/';
            preg_match($re, $this->input->post('nombre'), $matches, PREG_OFFSET_CAPTURE, 0);
            if (count($matches) > 0) {
                $this->session->set_flashdata('incorrecto', 'El nombre del producto es incorrecto. Por favor no use caracteres especiales.');
           
                redirect('productos/nuevo');
            }

            $data = array(
                "nombre" => $this->input->post('nombre'),
                "codigo" => $this->input->post('codigo'),
                "codigo_puntos_leal" => $this->input->post('codigo_puntos_leal'),
                "descripcion" => $this->input->post('descripcion'),
                "precio_venta" => $this->input->post("precio"),
                "precio_compra" => $this->input->post('precio_compra'),
                "categoria_id" => $this->input->post('categoria_id'),
                "impuesto" => $this->input->post('id_impuesto'),
                'activo' => $active,
                'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
                'stock_minimo' => $this->input->post('stock_minimo') ? $this->input->post('stock_minimo') : 0,
                'stock_maximo' => $this->input->post('stock_maximo') ? $this->input->post('stock_maximo') : 0,
                'ubicacion' => $this->input->post('ubicacion'),
                'ganancia' => $this->input->post('ganancia') ? $this->input->post('ganancia') : 0,
                "tienda" => $this->input->post('tienda') ? $this->input->post('tienda') : 0,
                "id_proveedor" => $this->input->post('id_proveedor'),
                "muestraexist" => $this->input->post('muestraexist'),
                "vendernegativo" => $this->input->post('vendernegativo'),
                'material' => $material,
            );

            if (!empty($image_name)) {
                $data['imagen'] = $image_name;
            } else if($this->input->post('imagenPrincipalHiddenInput') != "") {
                $data['imagen'] = $this->input->post('imagenPrincipalHiddenInput');
            }

            if (!empty($image_name1)) {
                $data['imagen1'] = $image_name1;
            } else if($this->input->post('imagen1HiddenInput') != "") {
                $data['imagen1'] = $this->input->post('imagen1HiddenInput');
            }

            if (!empty($image_name2)) {
                $data['imagen2'] = $image_name2;
            } else if($this->input->post('imagen2HiddenInput') != "") {
                $data['imagen2'] = $this->input->post('imagen2HiddenInput');
            }

            if (!empty($image_name3)) {
                $data['imagen3'] = $image_name3;
            } else if($this->input->post('imagen3HiddenInput') != "") {
                $data['imagen3'] = $this->input->post('imagen3HiddenInput');
            }

            if (!empty($image_name4)) {
                $data['imagen4'] = $image_name4;
            } else if($this->input->post('imagen4HiddenInput') != "") {
                $data['imagen4'] = $this->input->post('imagen4HiddenInput');
            }

            if (!empty($image_name5)) {
                $data['imagen5'] = $image_name5;
            } else if($this->input->post('imagen5HiddenInput') != "") {
                $data['imagen5'] = $this->input->post('imagen5HiddenInput');
            }

            /* Guardar producto */
            $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));

            post_curl('woocommerce/products', json_encode([
                'product' => $id_producto
            ]), $this->session->userdata('token_api'));

            //guardar evento de primeros pasos producto
            $estadoBD = $this->newAcountModel->getUsuarioEstado();                    
            if($estadoBD["estado"]==2){
                $paso=12;
                $marcada=$this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'),'db_config' => $this->session->userdata('db_config_id'),'id_paso'=>$paso));
                if($marcada==0) {
                    $datatarea = array(
                        'id_paso' => $paso,
                        'id_usuario' => $this->session->userdata('user_id'),
                        'db_config' => $this->session->userdata('db_config_id')
                    );
                    $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                }                               
            }
            
            if (isset($_POST['lista_precios']) && !empty($_POST['lista_precios'])) {
                foreach ($_POST['lista_precios'] as $lp) {
                    $porcentaje = $this->opciones->getNombre('listaPrecioPorcentaje_' . $lp)['valor_opcion'];
                    if (empty($porcentaje)) {
                        $porcentaje = 0;
                    }
                    $detalleLista = array(
                        'product_id' => $id_producto,
                        'impuesto' => $this->input->post('id_impuesto'),
                        'lista' => $lp,
                        'precio_nuevo' => ($this->input->post("precio") - (($this->input->post("precio") * $porcentaje) / 100))
                    );

                    $this->lista_detalle_precios->create($detalleLista);
                }
            }

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
                        if ($key == 'id') {
                            foreach ($value as $key2 => $id_ingrediente) {
                                if ($id_ingrediente != '' && $id_ingrediente != 0) {
                                    /* Ingrediente */
                                    $ingrediente = array(
                                        'id_ingrediente' => $id_ingrediente,
                                        'id_producto' => $id_producto,
                                        'cantidad' => $ingredientes['cantidad'][$key2],
                                    );
                                    /* Guardar ingrediente en producto_ingredientes */
                                    $this->productos->addIngredient($ingrediente);
                                    $withIngredients = true;
                                }
                            }
                        }
                    }

                    /* Cambiar estado  (ingrediente = 1 -> tiene ingredientes) al producto */
                    if ($withIngredients) {
                        $this->productos->withIngredients($id_producto);
                    }

                    break;
                //tipo combo
                case 3:
                    $isCombo = false; // bandera para combos
                    $productos_combo = $_POST['productosCombo'];

                    foreach ($productos_combo as $key => $value) {
                        if ($key == 'id') {
                            foreach ($value as $key2 => $id_producto_combo) {
                                if ($id_producto_combo != '' && $id_producto_combo != 0) {
                                    /* Ingrediente */
                                    $producto_combo = array(
                                        'id_combo' => $id_producto,
                                        'id_producto' => $id_producto_combo,
                                        'cantidad' => $productos_combo['cantidad'][$key2],
                                    );
                                    /* Guardar ingrediente en producto_ingredientes */
                                    $this->productos->addProductCombo($producto_combo);
                                    $isCombo = true;
                                }
                            }
                        }
                    }

                    if ($isCombo) {
                        $this->productos->isCombo($id_producto);
                    }

                    break;
                //tipo producto seriales
                 case 4:
                       $tProducto = true;    
                       $seriales_producto = $_POST['seriales_producto'];
                      
                       foreach ($seriales_producto as $key => $un_serial) {
                           if(!empty($un_serial)){
                                $data_serial=array(
                                    'fecha_creacion' => date("Y-m-d h:i:s"),
                                    'creado_por' => $this->ion_auth->get_user_id(),
                                    'id_producto' => $id_producto,
                                    'serial' => $un_serial,
                                );
                                $this->productos_seriales->agregar_serial_producto($data_serial); 
                           }
                       }
                default:
                    $tProducto = true;
                    break;
            }

            $this->session->set_flashdata('validar_almacen', custom_lang('sima_product_created_message', 'success'));
            $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto creado correctamente'));
           
            redirect('productos/index');
        }

        $data = array();

        $data['data']['upload_error'] = $error_upload;

        $data['categorias'] = $this->categorias->get_combo_data();

        $data['impuestos'] = $this->impuestos->get_combo_data();

        $data['almacenes'] = $this->almacenes->get_combo_data1();
        $data['almacenes_inactivo'] = $this->almacenes->get_almacenes_inactivos(false);

        $data['proveedores'] = $this->proveedores->obtenerProveedores();

        $data['tipo_productos'] = $this->producto_tipo->get_all();

        $data['unidades'] = $this->unidades->get_combo_data();

        $data["lista_precios"] = $this->lista_precios->getForPorcentaje();


        $db_config_id = $this->session->userdata('db_config_id');
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $id_user = '';
        $almacen = '';

        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->dbConnection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
        }

        $data['almacenes_id'] = $almacen;
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $random = rand();
        $this->layout->template('member')->js(array(
            base_url("/public/js/imageFinder.js?$random"),
            base_url("/public/js/imageFinderNuevoProducto.js?$random"),
        ))->css(array(
            base_url("/public/css/imageFinder.css?$random")
        ))->show('productos/nuevo', array('data' => $data));
        $data['categorias'] = $this->categorias->get_combo_data();
    }

    public function nuevo_rapido() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('productos') == true) {
            $base_dato = $this->session->userdata('base_dato');
            $carpeta = 'uploads1/'.$base_dato.'/imagenes_productos';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            } 
            $config['upload_path'] = $carpeta;

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size'] = '2024';

            $config['max_width'] = '200000';

            $config['max_height'] = '2000000';

            $image_name = "";

            $this->load->library('upload', $config);

            if (!empty($_FILES['imagen']['name'])) {

                if (!$this->upload->do_upload('imagen')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];
                }
            }

            $active = isset($_POST['activo']) ? 1 : 0;

            $data = array(
                'imagen' => $image_name,
                "nombre" => $this->input->post('nombre'),
                "codigo" => $this->input->post('codigo'),
                "descripcion" => $this->input->post('descripcion'),
                "precio_venta" => $this->input->post('precio'),
                "precio_compra" => $this->input->post('precio_compra'),
                "categoria_id" => $this->input->post('categoria_id'),
                "impuesto" => $this->input->post('id_impuesto'),
                'activo' => 1,
                'material' => 0,
            );

            /* Guardar producto */
            $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
        }
    }

    //===============================================
    // GIFTCARDS
    //===============================================   

    public function listaGiftCards() {

        // Mostramos la lista de giftcards

        $lista = $this->productos->listaGiftCards();
        $cantidad = $this->productos->cantidadGiftCards();

        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data['data'] = $lista;
        $data['cantidad'] = $cantidad;
       
        $this->layout->template('member');
        $this->layout->show('productos/listado_giftcards', array('data' => $data));
    }

    public function listaResultExcelGift($datos) {

        // Mostramos el resultado de la importacion
        
        $data['data'] = $datos;        
        
        $this->layout->template('member');
        $this->layout->show('productos/listado_excel_import_giftcard', array('data' => $data));
    }

    // Los GiftCards se ñaden como productos normales pero estan dentro de una categoria llamada GiftCard y su estado activa significa:
    //
    // Activo = 1 ->  Se puede vender esa giftcard
    // Activo = 2 ->  Ya fue pagada, pero no se ha utilizado
    // Activo = 0 ->  La giftcard ya fue comprada y utilizada

    public function nuevoGift($datos) {

        // ¡ IMPORTANTE ! No cambiar el nombre de la categoria "GiftCard".....

        $idCategoria = $this->categorias->crear_categoria("GiftCard", "giftCard.png");


        // creamos la tabla de pagos con gift si no existe
        // Esta tabla es para relacionar una venta con el codigo de la giftcard
        $this->productos->crearTablaPagosGiftCard();


        $userId = $this->session->all_userdata()["user_id"];
       // $almacenId = $this->almacenes->getIdAlmacenActualByUserId($userId);

        // añadimos solo 1 giftcard a todos los almacenes del cliente
        $todos_almacenes = $this->almacenes->get_almacenes_activos();
        $array_almacenes = array();
        foreach ($todos_almacenes as $key => $value) {
            $array_almacenes[$value->id]= 1;
        }

        $_POST['Stock'] = $array_almacenes;
        $resultado = array();

        foreach ($datos as $val) {

            $existeGift = $this->productos->existeGift(trim($val["codigo"]));


            if ($existeGift == false) {


                $data = array(
                    'imagen' => "giftCard.png",
                    "nombre" => "GiftCard " . $val["valor"],
                    "codigo" => trim($val["codigo"]),
                    "descripcion" => "giftcard",
                    "precio_venta" => $val["valor"],
                    "precio_compra" => 0,
                    "categoria_id" => $idCategoria,
                    "impuesto" => 1,
                    'activo' => 1,
                    'material' => 0,
                    'stock_minimo' => 0,
                    'stock_maximo' => 1
                );

                if (strlen($val["codigo"]) <= 15) {

                    $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));

                    $tmp = array(
                        "result" => 1,
                        "valor" => $val["valor"],
                        "codigo" => $val["codigo"]
                    );

                    $resultado[] = $tmp;
                } else {

                    $tmp = array(
                        "result" => 2,
                        "valor" => $val["valor"],
                        "codigo" => $val["codigo"]
                    );

                    $resultado[] = $tmp;
                }
            } else {

                $tmp = array(
                    "result" => 0,
                    "valor" => $val["valor"],
                    "codigo" => $val["codigo"]
                );

                $resultado[] = $tmp;
            }
        }

        $this->listaResultExcelGift($resultado);
    }

    public function pagarGiftCard() {
        $listaGiftCards = $this->input->post('cards');
        $this->productos->pagarGiftCard($listaGiftCards);
    }

    public function cancelarGiftCard() {
        // creamos la tabla de pagos con gift si no existe
        // Esta tabla es para relacionar una venta con el codigo de la giftcard
        $this->productos->crearTablaPagosGiftCard();
        $listaGiftCards = $this->input->post('cards');
        $this->productos->cancelarGiftCard($listaGiftCards);
    }

    //---------------------------------


    public function estadoGiftCard() {
        $codigo = $this->input->post('codigo');
        $estado = $this->productos->estadoGiftCard($codigo);
        $this->output->set_content_type('application/json')->set_output(json_encode($estado));
    }

    //---------------------------------
    // Cambiamos comas por puntos    
    // Elimina el signo de la modena,
    // Retorna un numero sin decimales si no es necesario
    private function toNum($str) {
        $str = str_replace(",", ".", $str);
        $number = preg_replace("/([^0-9\\.])/i", "", $str);
        return (float) $number + 0;
    }

    public function import_excel_gift() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $error_upload = "";


        $carpeta = 'uploads/archivos_productos/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        foreach (new DirectoryIterator("uploads/archivos_productos") as $fileInfo) {
            if (!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $image_name = "";
        $this->load->library('upload', $config);
        $res_data = array();

        // Si se adjunto un archivo excel
        if (!empty($_FILES['archivo']['name'])) { //no olivdar subir el archivo mime en config
            if (!$this->upload->do_upload('archivo')) {


                $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla producto"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla producto</p>');

                $data['data']['upload_error'] = $error_upload;
                $this->layout->show('productos/import_excel', array('data' => $data));
            } else {


                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel
                        ->getActiveSheet()
                        ->toArray(null, true, true, true);

                $list = Array();

                foreach ($sheetData as $index => $value) {
                    if ($index != 1 && $value['A'] != "" && $value['B'] != "") {

                        $array = array(
                            "codigo" => $value['A'],
                            "valor" => $this->toNum($value['B'])
                        );

                        $list[] = $array;
                    }
                }

                $this->nuevoGift($list);
            }
        } else {

            $data['impuestos'] = $this->impuestos->get_combo_data_impuesto();
            $data['categorias'] = $this->categorias->get_combo_data();
            $data['unidades'] = $this->unidades->get_combo_data();
            $data['data']['upload_error'] = $error_upload;
            $data_empresa = $this->miempresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
                    
            $this->layout->template('member');
            $this->layout->show('productos/import_excel_gift', array('data' => $data));
        }
    }

    public function getDetalles(){
        $id = $this->input->post('id');
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $id_orden = $this->input->post('id_orden');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $simbolo=(!empty($data_empresa['data']['simbolo'])) ? $data_empresa['data']['simbolo'] : '$';
        
        //Obtenemos los adicionales y verifiamos si ya esta en la orden
        $adicionales =  $this->productos->getAdicionales($id);     
        $data_adicional = array();
        foreach($adicionales as $adicional){
            $data_adicional[] = array(
                "id" => $adicional['id'],
                "id_producto" => $adicional['id_producto'],
                "id_adicional" => $adicional['id_adicional'],
                "cantidad" => $adicional['cantidad'],
                "precio" => $adicional['precio']*$adicional['cantidad'],
                "nombre" => $adicional['nombre'],
                "simbolo" => $simbolo,
                "in_orden" => $this->ordenes->getOrdenByMesa($id,$zona,$mesa,$adicional['id_adicional'],null,$id_orden)
            );
        }      
        $modificaciones = $this->productos->getModificaciones($id);

        
        $data_modificacion = array();
        foreach($modificaciones as $modificacion){
            $data_modificacion[] = array(
                "id" => $modificacion['id'],
                "id_producto" => $modificacion['id_producto'],
                "nombre" => $modificacion['nombre'],
                "in_orden" => $this->ordenes->getOrdenByMesa($id,$zona,$mesa,null,$modificacion['nombre'],$id_orden)
            );
        }
     
        $data = array();
        $data = [
            'adicionales' => $data_adicional,
            'modificaciones' => $data_modificacion,
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function addProductoOrden(){
        $id = $this->input->post('id');
        $zona = $this->input->post('zona');
        $mesa = $this->input->post('mesa');
        $cantidad = $this->input->post('cantidad');
        $comensales = $this->input->post('comensales');
        $tipo = (!empty($this->input->post('section'))) ? $this->input->post('section') : "";       
        $orden =(!empty($this->input->post('orden'))) ? $this->input->post('orden') : "";

        if(($tipo=="tablecantidad") && (!empty($orden))){
            $data = array(
                'id' => $orden,
                'order_producto' => $id,
                'tablecantidad' => $tipo,
                'zona' => $zona,
                'mesa_id' => $mesa,
                'estado' => 1,
                'cantidad' => $cantidad,
                'almacen'=> $this->almacenes->getIdAlmacenActualByUserId($this->session->userdata('user_id')),
            );
        }else{
            $data = array(
                'order_producto' => $id,
                'zona' => $zona,
                'mesa_id' => $mesa,
                'estado' => 1,
                'cantidad' => $cantidad,
                'almacen'=> $this->almacenes->getIdAlmacenActualByUserId($this->session->userdata('user_id')),
            );
        }
        
        $this->ordenes->addProductoOrden($data);
    }

    //===============================================
    // FIN GIFTCARDS
    //===============================================
    //===============================================
    // IMPORTACION PRODUCTOS V2
    //===============================================    



    public function importExcelNewGuardar() {
        
    }

    public function importExcelNewValidado($sheetData, $errorFix, $tipoAccion) {

        $result = $this->productos->importExcelNewImportar($sheetData, $errorFix, $tipoAccion);
        $this->output->set_content_type('application/json')->set_output(json_encode(Array("data" => $result)));
    }

    public function importExcelNewValidar($sheetData) {

        $result = $this->productos->importExcelNewValidar($sheetData);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function import_excel_new() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $error_upload = "";


        $carpeta = 'uploads/archivos_productos/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        foreach (new DirectoryIterator("uploads/archivos_productos") as $fileInfo) {
            if (!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $image_name = "";
        $this->load->library('upload', $config);
        $res_data = array();

        // Si se adjunto un archivo excel
        if (!empty($_FILES['archivo']['name'])) { //no olivdar subir el archivo mime en config
            if (!$this->upload->do_upload('archivo')) {


                $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla producto"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla producto</p>');

                $data['data']['upload_error'] = $error_upload;
                $this->layout->show('productos/import_excel', array('data' => $data));
            } else {



                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel
                        ->getActiveSheet()
                        ->toArray(null, true, true, true);

                if ($this->input->get("validado") == "ok") {

                    $tipoAccion = $this->input->get("accion");
                    $errorFix = $this->input->post("errorFix");
                    $this->importExcelNewValidado($sheetData, $errorFix, $tipoAccion);
                } else {
                    $this->importExcelNewValidar($sheetData);
                }
            }
        } else {

            $data['data'] = $this->productos->getImportExcelData();
            
            $data['data']['upload_error'] = $error_upload;        
            
            $this->layout->template('member');
            $this->layout->show('productos/import_excel_new', array('data' => $data));
        }
    }

    public function getImportExcelData() {
        pr($this->productos->getImportExcelData());
    }

    // Funcion para saber si existe un palabra esta en un array de palabras
    public function textInArray($str = "", $array = "") {

        $index = -1;
        foreach ($array as $val) {
            $index++;
            if (strpos(strtolower($val), strtolower($str)) !== false) {
                return $index;
            }
        }
        return -1;
    }

    // Funcion para convertir un indice numerico en su respectiva letra de excel
    public function i2t($index) {
        if ($index != -1)
            return PHPExcel_Cell::stringFromColumnIndex($index);
        else
            return -1;
    }

    // Funcion para convertir una letra de excel a su respectivo índice numerico
    public function t2i($letra) {
        return PHPExcel_Cell::columnIndexFromString($letra) - 1;
    }

    public function generarPlantillaNew() {
        $this->load->library('phpexcel');
        $valoresDinamicos = $this->input->get("dim");
       // $almacenes = $this->almacenes->get_all(0);
        $almacenes = $this->almacenes->getAll();

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        //===========================================================================
        // Creacion dinamica de los titulos en un ARRAY[]
        //===========================================================================
        // Master header en el que se guardaran todos los titulos
        $masterTitulos = Array();
        // Titulos estaticos, los que van por defecto
        $camposEstaticos = [
            "Categorías",
            "Código",
            "Nombre",
            "Precio Compra \n( sin iva )",
            "Precio Venta \n( sin iva )",
            "Impuesto",
            "Descripción"
        ];
        // Titulos dinamicos escogidos por el usuario
        $camposDinamicos = [];
        if ($valoresDinamicos != "") {
            $camposDinamicos = explode(",", $valoresDinamicos);
        }
        // Titulos de los almacenes
        $camposAlmacenes = Array();
        foreach ($almacenes as $val) {
            $camposAlmacenes[] = "Cantidad Almacén\n( " . $val->nombre . " )";
        }
        //-----------------------------------------
        // Añadimos titulos a masterHeader
        //-----------------------------------------
        $masterTitulos = array_merge($masterTitulos, $camposEstaticos);
        $masterTitulos = array_merge($masterTitulos, [""]);
        $masterTitulos = array_merge($masterTitulos, $camposAlmacenes);
        if (count($camposDinamicos) > 0) {
            $masterTitulos = array_merge($masterTitulos, [""]);
            $masterTitulos = array_merge($masterTitulos, $camposDinamicos);
        }
        //===========================================================================
        // Escribimos los titulos o encabezados segun el contenido de $masterTitulos
        //===========================================================================
        // Habilidamos salto de linea en los encabezados
        $ultimaColumna = count($masterTitulos) - 1;
        $excel->getActiveSheet()->getStyle('A1:' . $this->i2t($ultimaColumna) . '1')->getAlignment()->setWrapText(true);
        $fila = 1;
        foreach ($masterTitulos as $key => $val) {
            $columna = $key;
            // si tenemos un salto de linea lo aplicamos
            $text = explode('\n', $val);
            $finalText = isset($text[1]) ? trim($text[0]) . "\n" . trim($text[1]) : trim($text[0]);
            // Escribimos en el EXCEL !!
            $excel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $finalText);
        }
        //===========================================================================
        // Ajustamos dimensiones columnas
        //===========================================================================
        // Alto de la primer fila
        $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
        // Inicialmente ancho automaticos para todos y los separadores con ancho fijo
        foreach ($masterTitulos as $key => $val) {
            $columna = $key;
            if ($val == "")
                $excel->getActiveSheet()->getColumnDimensionByColumn($columna)->setAutoSize(false)->setWidth(5);
            else
                $excel->getActiveSheet()->getColumnDimensionByColumn($columna)->setAutoSize(true);
        }
        // Posteriormente cambiamos el ancho de los campos obligatorios o fijos
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false)->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false)->setWidth(22);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false)->setWidth(22);
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false)->setWidth(20);
        // Si unidad, ubicacion y proveedor sonn generados dinamicamente entonces les aumentamos el tamaño de la celda
        $idCampo = $this->textInArray("unidad", $masterTitulos);
        if ($idCampo != -1) {
            $excel->getActiveSheet()->getColumnDimension($this->i2t($idCampo))->setAutoSize(false)->setWidth(20);
        }
        $idCampo = $this->textInArray("proveedor", $masterTitulos);
        if ($idCampo != -1) {
            $excel->getActiveSheet()->getColumnDimension($this->i2t($idCampo))->setAutoSize(false)->setWidth(20);
        }
        $idCampo = $this->textInArray("ubicación", $masterTitulos);
        if ($idCampo != -1) {
            $excel->getActiveSheet()->getColumnDimension($this->i2t($idCampo))->setAutoSize(false)->setWidth(20);
        }
        //===========================================================================
        // Aplicamos Estilos a los títulos
        //===========================================================================
        // Alineamos a la derecha la columna F de Impuesto
        $excel->getActiveSheet()->getStyle('F')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
        foreach ($masterTitulos as $key => $val) {
            $columna = $this->i2t($key);
            if ($val != "") {
                $excel->getActiveSheet()->getStyle($columna . '1')->applyFromArray(
                        array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '76933c')
                                )
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'startcolor' => array('rgb' => 'c6efce')
                            ),
                            'font' => array(
                                'bold' => true,
                                'color' => array('rgb' => '32482b')
                            )
                        )
                );
            } else {
                $backgrounSeparadores = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'eeeeee')
                    )
                );
                $excel->getActiveSheet()->getStyle($columna . "1")->applyFromArray($backgrounSeparadores);
                $excel->getActiveSheet()->getStyle($columna)->applyFromArray($backgrounSeparadores);
            }
        }
        //===========================================================================
        // Aplicamos Formatos a las celdas
        //===========================================================================
        // Inicialmente asignamos formato texto a todos
        foreach ($masterTitulos as $key => $val) {
            $columna = $this->i2t($key);
            $excel->getActiveSheet()->getStyle($columna)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        }
        // Asignamos formato moneda
        $moneda = '  "$"* #,##0.00;  "$"* -#,##0.00';
        $date = 'dd/mm/yyyy';
        $excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode($moneda);
        $excel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode($moneda);
        // Si fecha vencimiento fue generada dinamicamente entonces le aplicamos el formato fecha
        $indFecha = $this->textInArray("fecha", $masterTitulos);
        if ($indFecha != -1) {
            $excel->getActiveSheet()->getStyle($this->i2t($indFecha))->getNumberFormat()->setFormatCode($date);
        }
        // Enfocamos la primer celda editable
        $excel->getActiveSheet()->setSelectedCells('A2');
        //===========================================================================
        //---------------------------------------------------------------------------
        //---------------------------------------------------------------------------
        //===========================================================================
        $excel->getActiveSheet()->setTitle('Importar Productos');
        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Importar Productos.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output');
    }

    public function creacionRapidaNewImportar() {

        $result = $this->productos->creacionRapidaNewImportar();

        $response = array(
            "result" => $result
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    //===============================================
    // FIN IMPORTACION PRODUCTOS V2
    //===============================================    



    public function product_check($str) {

        $id = $this->productos->get_by_name($str);

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

        $this->output->set_content_type('application/json')->set_output(json_encode($this->productos->get_ajax_data()));
    }

    public function productos_filter() {

        $result = array();

        $filter = $this->input->post('filter', TRUE);

        if (!empty($filter)) {

            $this->productos->initialize($this->dbConnection);

            $result = $this->productos->get_term($filter, $this->session->userdata('user_id'));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function productos_filter_group() {
        $this->lista_detalle_precios->initialize($this->dbConnection);
        $this->productos->initialize($this->dbConnection);
        $this->lista_precios->initialize($this->dbConnection);
        $this->lista_detalle_precios->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $simbolo=(!empty($data_empresa['data']['simbolo'])) ? $data_empresa['data']['simbolo'] : '$';
        
        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $hoy=date("Y-m-d");       
        $x = $this->lista_precios->activa_lista(array('grupo_cliente_id'=>$_POST['grupo'],'start <='=>$hoy),"(end >= '$hoy' OR end = '0000-00-00') and (almacen_id = $almacenActual or almacen_id = 0)");
        
        if(empty($x)){
           $_POST['grupo']=0;
        }
        
        $result = array();

        $filter = $this->input->post('filter', TRUE);
        $cliente = $this->input->post('cliente', TRUE);
        
        if (!empty($filter)) {
            $type = $this->input->post('type');
            
            if ($type == 'codificalo') {
                $productos = $this->productos->get_by_codigo($filter, $this->session->userdata('user_id'));
                if($productos){
                    $productos['imagen'] = $this->productos->devolver_ruta_imagen($productos['imagen']);
                }
                if (!empty($cliente)) {
                    $clienteD = $this->clientes->get_by_id($cliente);
                    $cartera = (isset($clienteD['cartera'])) ? $clienteD['cartera'] : 0;
                    //Cliente esta en grupo?
                    if ($_POST['grupo'] != 1 && $cartera == 0) {
                        //Grupo esta en una lista?/
                        $this->lista_precios->initialize($this->dbConnection);
                        $lista = $this->lista_precios->get_by_id($_POST['grupo']); //Lee si un grupo esta en una lista
                        
                        if (!empty($lista) && !empty($productos)) {
                            //Si el producto esta en una lista de detalle?/                            
                            $detalle = $this->lista_detalle_precios->get($lista['id'], $productos['id']); //Lee una lista esta en un grupo
                            /*Asigna nuevo precio*/
                            if (!empty($detalle)) {
                                $productos['precio_venta'] = $detalle['precio'];
                            }
                        }
                    }    
                }        
                
            }else if($type == 'codificalo_imei'){
                $productos = $this->productos->get_by_imei($filter,$this->input->post('imei'), $this->session->userdata('user_id'));
                if($productos){
                    $productos['imagen'] = $this->productos->devolver_ruta_imagen($productos['imagen']);
                }
                if (!empty($cliente)) {
                    $clienteD = $this->clientes->get_by_id($cliente);
                    $cartera = (isset($clienteD['cartera'])) ? $clienteD['cartera'] : 0;
                    //Cliente esta en grupo?
                    if ($_POST['grupo'] != 1 && $cartera == 0) {
                        //Grupo esta en una lista?/
                        $this->lista_precios->initialize($this->dbConnection);
                        $lista = $this->lista_precios->get_by_id($_POST['grupo']); //Lee si un grupo esta en una lista
                        
                        if (!empty($lista) && !empty($productos)) {
                            //Si el producto esta en una lista de detalle?/                            
                            $detalle = $this->lista_detalle_precios->get($lista['id'], $productos['id']); //Lee una lista esta en un grupo
                            /*Asigna nuevo precio*/
                            if (!empty($detalle)) {
                                $productos['precio_venta'] = $detalle['precio'];
                            }
                        }
                    }    
                } 
            }else {
                
                $productos = $this->productos->get_term($filter, $this->session->userdata('user_id'));
                
                foreach ($productos as $key => $un_producto) {
                    $un_producto->imagen = $this->productos->devolver_ruta_imagen($un_producto->imagen);  
                    $productos[$key]->simbolo = $simbolo;  
                    
                    //Buscamos si el producto tiene seriales
                    $seriales_producto = $this->productos_seriales->get_seriales_producto(array('id_producto'=>$un_producto->id));
                    if(count($seriales_producto)> 0){
                        $productos[$key]->imei = 1;
                    }else{
                        $productos[$key]->imei = 0;
                    }
                }

                if (isset($cliente) && !empty($cliente)) {
                    $clienteD = $this->clientes->get_by_id($cliente);
                    $cartera = (isset($clienteD['cartera'])) ? $clienteD['cartera'] : 0;
                    //Cliente esta en grupo?
                    //busco nombre del grupo
                    $nombregrupo= $this->clientes->get_by_where_group(array('id'=>$_POST['grupo']));
                    if($nombregrupo !=0 ){
                        $nombregrupocliente=strtolower($nombregrupo[0]['nombre']);
                    }

                    if ((($_POST['grupo'] != 1) || ($nombregrupocliente != 'sin grupo')) && ($cartera == 0)) {
                        //Grupo esta en una lista?/
                        
                        $lista = $this->lista_precios->get_by_id($_POST['grupo']); //Lee si un grupo esta en una lista

                        if (!empty($lista)) {
                           
                            foreach ($productos as $key => $value) {
                                $value->imagen = $this->productos->devolver_ruta_imagen($value->imagen);
                                foreach ($value as $key2 => $value2) {
                                    if ($key2 == 'id') {
                                        //Si el producto esta en una lista de detalle?/
                                       
                                        $detalle = $this->lista_detalle_precios->get($lista['id'], $value2); //Lee una lista esta en un grupo
                                        /*Asigna nuevo precio*/
                                        if (!empty($detalle)) {
                                            $value->precio_venta = $detalle['precio'];
                                        }
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

    public function get_by_category($category_id, $list = null,$cliente,$grupo) {
                      
        $this->lista_precios->initialize($this->dbConnection);
        $hoy=date("Y-m-d");
        $nombregrupo="";
        //buscamos el nombre del grupo
        $existe=$this->clientes->get_by_where_group(array('id'=>$grupo));
        if($existe != 0){
            $nombregrupo=strtolower($existe[0]['nombre']);
            if($nombregrupo != "sin grupo"){
                $x = $this->lista_precios->activa_lista(array('id'=>$list,'start <='=>$hoy),"(end >= '$hoy' OR end = '0000-00-00')");
            }else{
                $x="";
            }
        }
        
        if(empty($x)){
            $list=0;
            $grupo=0;
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $simbolo=(!empty($data_empresa['data']['simbolo'])) ? $data_empresa['data']['simbolo'] : '$';
        $productos = $this->productos->get_by_category($category_id, $this->session->userdata('user_id'), $list);
        
        foreach ($productos as $key => $value) {
            $productos[$key]['imagen'] = $this->productos->devolver_ruta_imagen($value['imagen']);
            $productos[$key]['simbolo'] = $simbolo;
        }

        if ($cliente != "-") {
            $clienteD = $this->clientes->get_by_id($cliente);
            $cartera = (isset($clienteD['cartera'])) ? $clienteD['cartera'] : 0;
            //Cliente esta en grupo?
            if ((($grupo != 1) || ($nombregrupo != 'sin grupo')) && ($cartera == 0)) {
                //Grupo esta en una lista?/
                //$this->lista_precios->initialize($this->dbConnection);
                $lista = $this->lista_precios->get_by_id($grupo); //Lee si un grupo esta en una lista
               
                if (!empty($lista)) {
                    foreach($productos as $p)
                    {
                        $detalle = $this->lista_detalle_precios->get($lista['id'], $p['id']); //Lee una lista esta en un grupo
                        /*Asigna nuevo precio*/
                        if (!empty($detalle)) {
                            $p['precio_venta'] = $detalle['precio'];
                        }
                    }
                }
            }    
        }  
        
        $this->output->set_content_type('application/json')->set_output(json_encode($productos));
    }

    /* LIBRO DE PRECIOS =============================================================== */

    public function libro_de_precios() {

        $data = array();
        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["almacenes"] = $this->almacenes->get_all(0);
        $data["lista_precios"] = $this->lista_precios->leer();
        $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));

        $this->layout->template('member')->show('productos/libro_de_precios', $data);
    }

    public function ver_listas() {
        $data = array();
        $data["lista_precios"] = $this->lista_precios->leer();
        $this->layout->template('member')->show('productos/listas_de_precios', $data);
    }

    public function getLibroData() {
        $libro = $this->input->get('libro');
        if ($libro !== '') {
            $this->output->set_content_type('application/json')->set_output(json_encode($this->lista_precios->getAjaxData($libro)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(['estado' => true]));
        }
    }

    //*Trae los productos filtrados por un termino*//
    public function productos_libro_precios_filter() {

        $result = array();

        $filter = $_GET['filter'];

        if (!empty($filter)) {

            $this->productos->initialize($this->dbConnection);

            $result = $this->productos->get_term_two($filter, $this->session->userdata('user_id'));

            if (!empty($result)) {
                $this->output->set_content_type('application/json')->set_output(
                        json_encode(array('done' => 1, 'data' => $result))
                );
            } else {
                $this->output->set_content_type('application/json')->set_output(
                        json_encode(array('done' => 0))
                );
            }
        }
    }

    /* .................................................................................... */

    public function impuesto_valor() {
        $result = array();
        $id = $this->input->get('id_impuesto', TRUE);

        $result = $this->impuestos->get_by_id($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    //MARK : update s3
    public function editar($id_producto) {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $productosEnLibroDePrecios = $this->lista_detalle_precios->getByProduct($id_producto);

        $this->productos->check_tabla_seriales();
        $this->productos->validate_tipo_producto_imei();
        $error_upload = "";

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        
        if($precio_almacen == 1){
            $validation = $this->form_validation->run('productos_almacen');
        }else{
            $validation = $this->form_validation->run('productos');
        }

        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';

        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        } 

        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';
        $config['max_size'] = '300';
        $config['max_width'] = '1200';
        $config['max_height'] = '1200';
        $image_name = "";
        $image_name1 = "";
        $image_name2 = "";
        $image_name3 = "";
        $image_name4 = "";
        $image_name5 = "";
        $this->load->library('upload', $config);
        $carpeta_s3 = $base_dato.'/imagenes_productos/'; 
        
        if (!empty($_FILES['imagen']['name'])) {
            
            $image_name = $_FILES['imagen']['name'];
            

            if (!$this->upload->do_upload('imagen')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }


            $input = $this->s3->inputFile($_FILES['imagen']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name, 'private', []);
        }

        if (!empty($_FILES['imagen1']['name'])) {
            $image_name = $_FILES['imagen1']['name'];


            if (!$this->upload->do_upload('imagen1')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name1 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen1']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name1, 'private', []);
        }

        if (!empty($_FILES['imagen2']['name'])) {
            $image_name = $_FILES['imagen2']['name'];

            if (!$this->upload->do_upload('imagen2')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name2 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen2']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name2, 'private', []);
        }

        if (!empty($_FILES['imagen3']['name'])) {
            $image_name = $_FILES['imagen3']['name'];

            if (!$this->upload->do_upload('imagen3')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name3 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen3']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name3, 'private', []);
        }

        if (!empty($_FILES['imagen4']['name'])) {

            $image_name = $_FILES['imagen4']['name'];


            if (!$this->upload->do_upload('imagen4')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name4 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen4']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name4, 'private', []);

        }

        if (!empty($_FILES['imagen5']['name'])) {
            $image_name = $_FILES['imagen5']['name'];


            if (!$this->upload->do_upload('imagen5')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name5 = $upload_data['file_name'];
            }

            $input = $this->s3->inputFile($_FILES['imagen5']['tmp_name'], true);
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta_s3.$image_name5, 'private', []);
        }

        if(!empty($error_upload)){
            $error_upload.='<p class="text-error"> Tenga en cuenta: tamaño máximo de archivo '.$config['max_size'].' kb, alto y ancho de imagen: 250px.</p>';
        }    

        if ($validation == true && empty($error_upload)) {
            $active = isset($_POST['activo']) ? 1 : 0;
            $ingrediente = isset($_POST['is_ingrediente']) ? 1 : 0;

            $re = '/[\;\`\'\*\~\´\¨]/';
            preg_match($re, $this->input->post('nombre'), $matches, PREG_OFFSET_CAPTURE, 0);
            if (count($matches) > 0) {                
                $this->session->set_flashdata('incorrecto', 'El nombre del producto es incorrecto. Por favor no use caracteres especiales.');
           
                redirect('productos/editar/' . $id_producto);
            }
           
            $data = array(
                'id' => $id_producto,
                "nombre" => $this->input->post('nombre'),
                "codigo" => $this->input->post('codigo'),
                "codigo_puntos_leal" => $this->input->post('codigo_puntos_leal'),
                "descripcion" => $this->input->post('descripcion'),
                "precio_venta" => $this->input->post('precio'),
                "precio_compra" => $this->input->post('precio_compra'),
                "categoria_id" => $this->input->post('categoria_id'),
                "unidad_id" => $this->input->post('id_unidades'),
                "impuesto" => $this->input->post('id_impuesto'),
                'activo' => $active,
                'material' => $ingrediente,
                'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
                'stock_minimo' => $this->input->post('stock_minimo') ? $this->input->post('stock_minimo') : 0,
                'stock_maximo' => $this->input->post('stock_maximo') ? $this->input->post('stock_maximo') : 0,
                'ubicacion' => $this->input->post('ubicacion'),
                'ganancia' => $this->input->post('ganancia') ? $this->input->post('ganancia') : 0,
                "tienda" => $this->input->post('tienda') ? $this->input->post('tienda') : 0,
                "id_proveedor" => $this->input->post('id_proveedor'),
                "muestraexist" => $this->input->post('muestraexist'),
                "vendernegativo" => $this->input->post('vendernegativo'),
            );

            switch ($this->input->post('tipo_producto_id')) {
                case 2:
                    //var_dump("Compuesto"); die;
                    $data['material'] = 0;
                    $data['ingredientes'] = 1;
                    $data['combo'] = 0;
                    break;
                case 3:
                    //var_dump("Combo"); die;
                    $data['material'] = 0;
                    $data['ingredientes'] = 0;
                    $data['combo'] = 1;
                    break;
                default:
                    //var_dump("Único o Serial"); die;
                    $data['material'] = $ingrediente;
                    $data['ingredientes'] = 0;
                    $data['combo'] = 0;
                    break;
            }

            if (!empty($image_name)) {
                $data['imagen'] = $image_name;
            } else if($this->input->post('imagenPrincipalHiddenInput') != "") {
                $data['imagen'] = $this->input->post('imagenPrincipalHiddenInput');
            }

            if (!empty($image_name1)) {
                $data['imagen1'] = $image_name1;
            } else if($this->input->post('imagen1HiddenInput') != "") {
                $data['imagen'] = $this->input->post('imagen1HiddenInput');
            }

            if (!empty($image_name2)) {
                $data['imagen2'] = $image_name2;
            } else if($this->input->post('imagen2HiddenInput') != "") {
                $data['imagen2'] = $this->input->post('imagen2HiddenInput');
            }

            if (!empty($image_name3)) {
                $data['imagen3'] = $image_name3;
            } else if($this->input->post('imagen3HiddenInput') != "") {
                $data['imagen3'] = $this->input->post('imagen3HiddenInput');
            }

            if (!empty($image_name4)) {
                $data['imagen4'] = $image_name4;
            } else if($this->input->post('imagen4HiddenInput') != "") {
                $data['imagen4'] = $this->input->post('imagen4HiddenInput');
            }

            if (!empty($image_name5)) {
                $data['imagen5'] = $image_name5;
            } else if($this->input->post('imagen5HiddenInput') != "") {
                $data['imagen5'] = $this->input->post('imagen5HiddenInput');
            }

            if ($error_upload == "") {
                $detalle_combo = $this->productos->getProductoCombo($id_producto);
                $error=$this->productos->update($data, $this->session->userdata('user_id'));

                if($detalle_combo){
                    foreach ($detalle_combo as $var){
                        $detalle_producto = $this->productos->getProducto($var['id_combo']);
                        $precio_viejo = $var['precio_compra'] * $var['cantidad']; 
                        $nuevo_precio  = $data['precio_compra'] * $var['cantidad'];
                        $nuevo_precio = $detalle_producto[0]['precio_compra'] - $precio_viejo + $nuevo_precio;
                        $this->productos->updatePrecioCombo($var['id_combo'], $nuevo_precio);
                    }
                }

                $id_producto = $data['id'];

                //lista de precios========================================================
                //No deberia Eliminarse (Jeisson Rodriguez)
                //$this->lista_detalle_precios->deleteProducto($id_producto);

                if (isset($_POST['lista_precios']) && !empty($_POST['lista_precios'])) {
                    foreach ($_POST['lista_precios'] as $lp) {
                        $porcentaje = $this->opciones->getNombre('listaPrecioPorcentaje_' . $lp)['valor_opcion'];
                        
                        if (empty($porcentaje)) {
                            $porcentaje = 0;
                        }

                        $detalleLista = array(
                            'product_id' => $id_producto,
                            'impuesto' => $this->input->post('id_impuesto'),
                            'lista' => $lp,
                            'precio_nuevo' => ($this->input->post("precio") - (($this->input->post("precio") * $porcentaje) / 100))
                        );

                        $this->lista_detalle_precios->create($detalleLista);
                    }
                }

                $this->auxEditarProductoCompuesto($id_producto);

                $this->auxEditarProductoCombo($id_producto);

                $this->auxEditarProductoSerial($id_producto);

                if(!empty($error)){
                    $mensa='Producto actualizado correctamente '. $error;
                }else{
                    $mensa='Producto actualizado correctamente';
                }
               // $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto actualizado correctamente' ));
                $this->session->set_flashdata('validar_almacen', custom_lang('sima_product_created_message', 'success')); 
                $this->session->set_flashdata('message', $mensa);
                //die("termine");
                redirect('productos/index');
            }
        }

        $data = array();

        $data['data'] = $this->productos->get_by_id($id_producto);
        //var_dump($data['data']);die();
        //$data['data']['precio_venta'] = $this->opciones_model->formatoMonedaMostrar($data['data']['precio_venta']);
        //$data['data']['precio_compra'] = $this->opciones_model->formatoMonedaMostrar($data['data']['precio_compra']);
        /*echo $data['data']['imagen']; die;*/
        $data['data']['imagen'] = $this->productos->devolver_ruta_imagen($data['data']['imagen']); 
        $data['data']['imagen1'] = $this->productos->devolver_ruta_imagen($data['data']['imagen1']);
        $data['data']['imagen2'] = $this->productos->devolver_ruta_imagen($data['data']['imagen2']);
        $data['data']['imagen3'] = $this->productos->devolver_ruta_imagen($data['data']['imagen3']);
        $data['data']['imagen4'] = $this->productos->devolver_ruta_imagen($data['data']['imagen4']);
        $data['data']['imagen5'] = $this->productos->devolver_ruta_imagen($data['data']['imagen5']);

        if ($data['data']['ingredientes'] == 1) {
            $data['ingredientes'] = $this->productos->get_ingredientes($id_producto);
        } else {
            $data['ingredientes'] = array();
        }

        if ($data['data']['material'] == 1) {
            $data['material'] = 1;
        } else {
            $data['material'] = 0;
        }

        if ($data['data']['combo'] == 1) {
            $data['productos_combo'] = $this->productos->get_productos_combo($id_producto);
        } else {
            $data['productos_combo'] = array();
        }
        //consultamos si tiene seriales
        $seriales_producto = $this->productos_seriales->get_seriales_producto(array('id_producto'=>$id_producto));
        if(count($seriales_producto)> 0){
            $data['seriales_producto'] = $seriales_producto;
        }
        $data['data']['upload_error'] = $error_upload;

        $data['categorias'] = $this->categorias->get_combo_data();

        $data['almacenes'] = $this->almacenes->get_combo_data_stock_actual($id_producto);
        
        $data['almacenes_inactivo'] = $this->almacenes->get_almacenes_inactivos(false);
        
        $data['proveedores'] = $this->proveedores->obtenerProveedores();

        $data['impuestos'] = $this->impuestos->get_combo_data();

        $data['tipo_productos'] = $this->producto_tipo->get_all();

        $data['unidades'] = $this->unidades->get_combo_data_unidades();

        $data["lista_precios"] = $this->lista_precios->getForPorcentaje();
        foreach ($data['lista_precios'] as $key => $lp) {
            $data["lista_precios"][$key]['checked'] = false;
            $seleccionado = $this->lista_detalle_precios->get($lp['id'], $id_producto);
            if (!empty($seleccionado)) {
                $data["lista_precios"][$key]['checked'] = true;
            }
        }

        $db_config_id = $this->session->userdata('db_config_id');
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $id_user = '';
        $almacen = '';

        $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->dbConnection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
        }
      
        $data['almacenes_id'] = $almacen;
        $data['precio_almacen'] = $this->opciones->getOpcion('precio_almacen');
        $data_empresa = $this->miempresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        //print_r($data); die();
        $random = rand();
        $this->layout->template('member')->js(array(
            base_url("/public/js/imageFinder.js?$random"),
            base_url("/public/js/imageFinderNuevoProducto.js?$random"),
        ))->css(array(
            base_url("/public/css/imageFinder.css?$random")
        ))->show('productos/editar', array('data' => $data, 'productosEnLibroDePrecios' => $productosEnLibroDePrecios));
    }

    public function auxEditarProductoCompuesto($id_producto) {
        $isCompuesto = false;
        $ingredientes = $_POST['Ingrediente'];
        $this->productos->delete_ingredientes($id_producto);
        
        foreach ($ingredientes as $key => $value) {
            if ($key == 'id') {
                foreach ($value as $key2 => $id_ingrediente) {
                    if ($id_ingrediente != '' && $id_ingrediente != 0) {
                        $ingrediente = array(
                            'id_ingrediente' => $id_ingrediente,
                            'id_producto' => $id_producto,
                            'cantidad' => $ingredientes['cantidad'][$key2],
                        );
                        $this->productos->addIngredient($ingrediente);
                        $isCompuesto = true;
                    }
                }
            }
        }
        if ($isCompuesto) {
            $this->productos->withIngredients($id_producto);
        } else {
            $this->productos->notWithIngredients($id_producto);
        }
    }

    public function auxEditarProductoCombo($id_producto) {
        $isCombo = false;
        $this->productos->delete_productos_combo($id_producto);
        $productos_combo = $_POST['productosCombo'];

        foreach ($productos_combo as $key => $combo) {
            if ($key != 'id') { continue; }
            foreach ($combo as $key2 => $id_producto_combo) {
                if ($id_producto_combo == '' && $id_producto_combo == 0) { continue; }
                $combo_producto= array(
                    'id_combo' => $id_producto,
                    'id_producto' => $id_producto_combo,
                    'cantidad' => $productos_combo['cantidad'][$key2],
                );
                $this->productos->addProductCombo($combo_producto);
                $isCombo = true;
            }
        }

        if ($isCombo) {
            $this->productos->isCombo($id_producto);
        } else {
            $this->productos->isNotCombo($id_producto);
        }
    }

    public function auxEditarProductoSerial($id_producto) {
        //Gustavo Nieves 08/01/2020
        $seriales_anteriores = $_POST['serial_anterior'];
        $seriales_producto = $_POST['seriales_producto'];
        $serialesSonDiferentes = $seriales_producto !== $seriales_anteriores;
        if ($serialesSonDiferentes) {
            foreach ($seriales_producto as $key => $serial) {
                $serial_anterior = $seriales_anteriores[$key];
                $serialFueEditado = $serial !== $serial_anterior;
                if ($serialFueEditado){
                    $serialEsNuevo = $serial_anterior === '';
                    if ($serialEsNuevo){
                        $this->productos_seriales->agregar_serial_producto(
                            [
                                'fecha_creacion' => date("Y-m-d h:i:s"),
                                'creado_por' => $this->ion_auth->get_user_id(),
                                'id_producto' => $id_producto,
                                'serial' => $serial,
                            ]
                        );
                    } else {
                        $this->productos_seriales->editar_serial_producto(
                            [
                                'fecha_creacion' => date("Y-m-d h:i:s"),
                                'creado_por' => $this->ion_auth->get_user_id(),
                                'id_producto' => $id_producto,
                                'serial' => $serial,
                            ],
                            $serial_anterior
                        );
                    }
                }
            }
            return;
        }
        $this->productos_seriales->delete_seriales(array('id_producto'=>$id_producto));
        foreach ($seriales_producto as $key => $serial) {
            if ($serial !== '') {
                $this->productos_seriales->agregar_serial_producto(
                    [
                        'fecha_creacion' => date("Y-m-d h:i:s"),
                        'creado_por' => $this->ion_auth->get_user_id(),
                        'id_producto' => $id_producto,
                        'serial' => $serial,
                    ]
                );
            }
        }
    }

    public function detalles($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data = $this->productos->get_by_id($id);

        $this->layout->template('member')->show('productos/detalles', array('data' => $data));
    }

    public function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->productos->delete($id);
        $this->session->set_flashdata('validar_almacen', custom_lang('sima_product_created_message', 'success'));
        $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));

        redirect("productos");
    }

    public function filtro_prod_existencia() {
        
        $type = $this->input->get('almacen');

        $filter = $this->input->get('term', TRUE);

        $result = $this->productos->get_term_existencias($filter, $type);

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function filtro_prod_existencia_auditoria(){
        
        $type = $this->input->post('type');
        $filter = $this->input->post('term', TRUE); 
        $almacen = $this->input->post('almacen');

        if(empty($almacen)){
            $almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }
        if($type =='buscalo'){
            $result = $this->productos->get_term_existencias($filter, $almacen);
        }else{
            $result = $this->productos->get_codigo_existencias($filter, $almacen);
        }
        

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function excel() {
      
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        //ini_set("memory_limit","1048M");

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Codigo del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Descripción');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio de compra');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Precio de venta');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Nombre del impuesto');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Categoria');

        $query = $this->productos->excel();

        $row = 2;

        foreach ($query as $value) {

            $this->phpexcel->getActiveSheet()->getCell('A' . $row)->setValueExplicit($value->codigo, PHPExcel_Cell_DataType::TYPE_STRING);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->nombre);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->descripcion);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->precio_compra);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->precio_venta);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->nombre_impuesto);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value->categoria);

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

        $this->phpexcel->getActiveSheet()->getStyle('A1:G' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0',
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF',
                        ),
                    ),
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

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    private function recursiva($arreglo, $obj_excel, $index) {
        $this->load->library('phpexcel');
        $fila = $index + 2;
        if ($index < count($arreglo)) {
            foreach ($arreglo[$index] as $k => $value) {
                if (is_array($value)) {
                    $obj_excel->setActiveSheetIndex(0);
                    $obj_excel->getActiveSheet()->setCellValue('A' . $fila, $value['categoria_id']);
                    $obj_excel->getActiveSheet()->setCellValue('B' . $fila, $value['codigo']);
                    $obj_excel->getActiveSheet()->setCellValue('C' . $fila, $value['nombre']);
                    $obj_excel->getActiveSheet()->setCellValue('D' . $fila, $value['precio_compra']);
                    $obj_excel->getActiveSheet()->setCellValue('E' . $fila, $value['precio_venta']);
                    $obj_excel->getActiveSheet()->setCellValue('F' . $fila, $value['descripcion']);
                    $obj_excel->getActiveSheet()->setCellValue('G' . $fila, $value['impuesto']);
                    $obj_excel->getActiveSheet()->setCellValue('H' . $fila, $value['unidades']);
                    $obj_excel->getActiveSheet()->setCellValue('I' . $fila, $value['cantidad']);
                    $obj_excel->getActiveSheet()->setCellValue('J' . $fila, $value['stockmin']);
                    $obj_excel->getActiveSheet()->setCellValue('K' . $fila, $value['almacen']);
                } else {
                    $obj_excel->getActiveSheet()->setCellValue('M' . $fila, utf8_encode($value));
                }
            }
            $this->recursiva($arreglo, $obj_excel, ++$index);
        } else {
            return $obj_excel;
        }
    }

    private function exportarFallos($Hoja_Productos) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Productos No Guardados.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($Hoja_Productos, 'Excel5');
        $objWriter->save("uploads/archivos_productos/Productos No Guardados.xls");
        $this->session->set_flashdata('archivo', custom_lang('sima_bill_send_message', 'Guardado'));
    }

    public function importar() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data = array();

        $this->layout->template('member')->show('productos/importar', array('data' => $data));
    }

    public function import_excel() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $error_upload = "";
        $this->layout->template('ventas');
        $carpeta = 'uploads/archivos_productos/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        foreach (new DirectoryIterator("uploads/archivos_productos") as $fileInfo) {
            if (!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $image_name = "";
        $this->load->library('upload', $config);
        $res_data = array();
        if (!empty($_FILES['archivo']['name'])) { //no olivdar subir el archivo mime en config
            if (!$this->upload->do_upload('archivo')) {
                $data['impuestos'] = $this->impuestos->get_combo_data_impuesto();
                $data['categorias'] = $this->categorias->get_combo_data();
                $data['unidades'] = $this->unidades->get_combo_data();
                $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos, llamado plantilla producto"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla producto</p>');
                $data['data']['upload_error'] = $error_upload;
                $this->layout->show('productos/import_excel', array('data' => $data));
            } else {
                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);

                //var_dump($sheetData);die;
                //array para guardar los movimientos de los productos del almacen.
                $almacenes = array();

                foreach ($sheetData as $index => $value) {
                    if ($index != 1 && $value['A'] != '' && $value['G'] != '' && $value['C'] != '') {
                        $array_datos = array();
                        $data = array(
                            "categoria_id" => $value['A'],
                            "codigo" => $value['B'],
                            "nombre" => $value['C'],
                            "precio_compra" => $value['D'],
                            "precio_venta" => $value['E'],
                            "descripcion" => $value['F'],
                            "impuesto" => $value['G'],
                            "unidades" => $value['H'],
                            "cantidad" => $value['I'],
                            "stockmin" => $value['J'],
                            "almacen" => $value['K'],
                        );

                        $res = $this->productos->add_csv($data, $this->session->userdata('user_id'));
                        list($res_oper, $msj, $res_data) = $this->productos->add_csv($data, $this->session->userdata('user_id'));

                        if ($res_oper === FALSE) {
                            $datos_fallo[] = array($data, $msj);
                        } else {

                            // si no existe el almacen en el array lo crea
                            if (!array_key_exists($res_data['almacen_id'], $almacenes)) {
                                $almacenes[$res_data['almacen_id']] = array();
                            }

                            // inserta el movimiento del producto para el almacen
                            array_push($almacenes[$res_data['almacen_id']], array(
                                'cantidad' => $value['I'],
                                'precio_compra' => $data['precio_compra'],
                                'codigo_barra' => $data['codigo'],
                                'nombre' => $data['nombre'],
                                'existencias' => $res_data['existencias'],
                                'total_inventario' => $data['cantidad'] * $data['precio_compra'],
                                'producto_id' => $res_data['producto_id'],
                            ));
                        }
                    }
                }

                foreach ($almacenes as $key => $almacen) {
                    $total_inventario = 0;

                    foreach ($almacen as $producto) {
                        $total_inventario += $producto['precio_compra'] * $producto['cantidad'];
                    }

                    $movimiento = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'fecha' => date("Y-m-d H:i:s"),
                        'productos' => $almacen,
                        'almacen_id' => $key,
                        'tipo_movimiento' => 'entrada_inicial',
                        'total_inventario' => $total_inventario,
                    );

                    $id = $this->inventario->add($movimiento);
                }

                if ($datos_fallo) {
                    $Hoja_Productos = $this->load->library('phpexcel');
                    $Hoja_Productos = new PHPExcel();
                    $Hoja_Productos->setActiveSheetIndex(0);
                    $Hoja_Productos->getActiveSheet()->setCellValue('A1', 'Categoria');
                    $Hoja_Productos->getActiveSheet()->setCellValue('B1', 'Codigo');
                    $Hoja_Productos->getActiveSheet()->setCellValue('C1', 'Nombre');
                    $Hoja_Productos->getActiveSheet()->setCellValue('D1', 'Precio Compra');
                    $Hoja_Productos->getActiveSheet()->setCellValue('E1', 'Precio Venta');
                    $Hoja_Productos->getActiveSheet()->setCellValue('F1', 'Descripción');
                    $Hoja_Productos->getActiveSheet()->setCellValue('G1', 'Impuesto');
                    $Hoja_Productos->getActiveSheet()->setCellValue('H1', 'Unidades');
                    $Hoja_Productos->getActiveSheet()->setCellValue('I1', 'Cantidad');
                    $Hoja_Productos->getActiveSheet()->setCellValue('J1', 'Stock Minimo');
                    $Hoja_Productos->getActiveSheet()->setCellValue('K1', 'Almacen');
                    $Hoja_Productos->getActiveSheet()->setCellValue('M1', 'Motivo del fallo');
                    $this->recursiva($datos_fallo, $Hoja_Productos, 0);
                    $Hoja_Productos->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
                            array(
                                'font' => array('bold' => true),
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                ),
                                'borders' => array(
                                    'top' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    ),
                                    'bottom' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    ),
                                ),
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                    'rotation' => 90,
                                    'startcolor' => array('argb' => 'FFA0A0A0'),
                                    'endcolor' => array('argb' => 'FFFFFFFF'),
                                ),
                            )
                    );
                    // Rename worksheet
                    $Hoja_Productos->getActiveSheet()->setTitle('Productos');
                    $this->exportarFallos($Hoja_Productos);
                    $enviarCorreo = FALSE;
                }

                chmod('../../' . $carpeta, 0777);
                unlink('../../' . $carpeta . $config['file_name'] . 'xlsx');
                $result['valid'] = true;
                if (isset($enviarCorreo)) {
                    $result['message'] = 'Productos importados con errores';
                    $result['validar_almacen'] = "danger";
                } else {
                    $result['message'] = 'Productos importados correctamente';
                    $result['validar_almacen'] = "success";
                }
                $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', $result['message']));
                $this->session->set_flashdata('validar_almacen', custom_lang('sima_product_created_message', $result['validar_almacen']));
                redirect("productos/index");
            }
        } else {
            $data['impuestos'] = $this->impuestos->get_combo_data_impuesto();
            $data['categorias'] = $this->categorias->get_combo_data();
            $data['unidades'] = $this->unidades->get_combo_data();
            $data['data']['upload_error'] = $error_upload;
            $this->layout->show('productos/import_excel', array('data' => $data));
        }
    }

    public function productos_actualizar() {

        $resultado = "SELECT * FROM producto ";
        foreach ($this->dbConnection->query($resultado)->result() as $value) {
            if (strlen($value->codigo) == 15) {
                echo $codigonuevo = $value->codigo . '0';
                echo "<br>";
                $query = "update producto set codigo = " . $codigonuevo . " where id = '" . $value->id . "' ";
                $this->dbConnection->query($query);
            }
        }
    }

    public function exportar_base_productos_codigo($fields) {
        $campos = [
            'id',
            'nombre',
        ];

        $campos_informe = array_filter(array_map('trim', explode('|', urldecode($fields))));
        $indices = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ];
        foreach ($campos_informe as $value) {
            array_push($campos, $value);
        }
        $productos = $this->productos->get_base($campos);
        // var_dump($productos);
        // die;
        foreach ($campos_informe as $value) {
            array_push($campos, $value);
        }
        // var_dump($campos_informe);
        // die;
        $hoja_productos = $this->load->library('phpexcel');
        $hoja_productos = new PHPExcel();
        $hoja_productos->setActiveSheetIndex(0);
        
        for ($i = 0; $i < count($campos); $i++) {
            $row = 1;
            $hoja_productos->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $campos[$i]);
        }
        $letra = 0;
        if ($productos) {
            $row = 2;
            for ($i = 0; $i < count($productos); $i++) {
                $col = 0;
                foreach ($productos[$i] as $campo) {
                    $letra = count($productos[$i]) - 1;
                    $hoja_productos->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $campo);
                    $col++;
                }

                $row++;
            }
        }

        $letra++;
        //$hoja_productos->getActiveSheet()->getColumnDimension('A')->setVisible(false);
        $hoja_productos->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $hoja_productos->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $hoja_productos->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $hoja_productos->getActiveSheet()->getStyle($indices[$letra] . '0:' . $indices[count($campos) - 1] . '' . (count($productos) + 1))->applyFromArray(
                array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array('rgb' => '9FF781'),
                        'endcolor' => array('rgb' => '9FF781'),
                    ),
                )
        );
        $hoja_productos->getActiveSheet()->setTitle('Productos');
        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Productos.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($hoja_productos, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
        // var_dump($objWriter);
        // die;
    }

    public function exportar_base_productos($fields) {

        $campos_informe = array_filter(array_map('trim', explode('|', urldecode($fields))));
        if (in_array('Codigo', $campos_informe))  {
            $campos = [
                'id',
                'nombre',
            ];
        } else {
            $campos = [
                'id',
                'nombre',
                'codigo',
            ];
        }

        
        $indices = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ];
        foreach ($campos_informe as $value) {
            array_push($campos, $value);
        }
        $productos = $this->productos->get_base($campos);
        // var_dump($productos);
        // die;
        foreach ($campos_informe as $value) {
            array_push($campos, $value);
        }
        // var_dump($campos_informe);
        // die;
        $hoja_productos = $this->load->library('phpexcel');
        $hoja_productos = new PHPExcel();
        $hoja_productos->setActiveSheetIndex(0);
        
        for ($i = 0; $i < count($campos); $i++) {
            $row = 1;
            $hoja_productos->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $campos[$i]);
        }

        /*echo "<pre>";
            print_r($productos);
        echo "<pre>";die;*/
        $letra = 0;
        if ($productos) {
            $row = 2;
            for ($i = 0; $i < count($productos); $i++) {
                $col = 0;
                foreach ($productos[$i] as $key => $campo) {
                    $letra = count($productos[$i]) - 1;
                    if($key == 'codigo') {
                        $hoja_productos->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $campo, PHPExcel_Cell_DataType::TYPE_STRING);
                    } else {
                        $hoja_productos->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $campo);
                    }
                    $col++;
                }
                $row++;
            }
        }

        $letra++;
        //$hoja_productos->getActiveSheet()->getColumnDimension('A')->setVisible(false);
        $hoja_productos->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $hoja_productos->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $hoja_productos->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $hoja_productos->getActiveSheet()->getStyle($indices[$letra] . '0:' . $indices[count($campos) - 1] . '' . (count($productos) + 1))->applyFromArray(
                array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array('rgb' => '9FF781'),
                        'endcolor' => array('rgb' => '9FF781'),
                    ),
                )
        );
        $hoja_productos->getActiveSheet()->setTitle('Productos');
        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Productos.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($hoja_productos, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
        // var_dump($objWriter);
        // die;
    }

    public function importar_base_productos() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');
        $error_upload = "";
        $this->layout->template('member');
        $carpeta = 'uploads/archivos_productos/';

        if (!file_exists($carpeta))
            mkdir($carpeta, 0777, true);

        foreach (new DirectoryIterator("uploads/archivos_productos") as $fileInfo) {
            if (!$fileInfo->isDot())
                unlink($fileInfo->getPathname());
        }
        
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $this->load->library('upload', $config);
        $res_data = array();
        $datos_fallo = false;
        
        if (!empty($_FILES['archivo']['name'])) {
            if (!$this->upload->do_upload('archivo')) {
                $data['impuestos'] = $this->impuestos->get_combo_data_impuesto();
                // $data['categorias'] = $this->categorias->get_combo_data();
                $data['unidades'] = $this->unidades->get_combo_data();
                $error_upload = 'No se pudo procesar el archivo por favor vuelva a cargar la plantilla o descarguela nuevamente.';
                
                $data['estado'] = 'error';
                $data['upload_error'] = $error_upload;
                $this->layout->show('configuracion/actualizacion_masiva_productos.php', array('data' => $data));
            } else {
                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                $columnas = ['A' => 'id', 'B' => 'nombre', 'C' => 'codigo'];

                $auxValidIndex = array(
                    'id', 
                    'nombre', 
                    'codigo', 
                    'Codigo', 
                    'Precio compra', 
                    'Precio venta',
                    'Stock minimo',
                    'Stock maximo',
                    'Impuesto',
                    'Descripcion',
                    'Activo',
                    'Fecha vencimiento',
                    'Venta negativo',
                    'Proveedor',
                    'Tienda',
                    'Categoria'
                );

                foreach ($sheetData[1] as $key => $value) {
                    if (!in_array($value, ['id', 'nombre', 'codigo']) && in_array($value, $auxValidIndex)) {
                        switch ($value) {
                            case 'Codigo':
                                $field = 'codigo';
                                break;
                            case 'Precio compra':
                                $field = 'precio_compra';
                                break;
                            case 'Precio venta':
                                $field = 'precio_venta';
                                break;
                            case 'Stock minimo':
                                $field = 'stock_minimo';
                                break;
                            case 'Stock maximo':
                                $field = 'stock_maximo';
                                break;
                            case 'Impuesto':
                                $field = 'impuesto';
                                break;
                            case 'Descripcion':
                                $field = 'descripcion';
                                break;
                            case 'Activo':
                                $field = 'activo';
                                break;
                            case 'Fecha vencimiento':
                                $field = 'fecha_vencimiento';
                                break;
                            case 'Venta negativo':
                                $field = 'vendernegativo';
                                break;
                            case 'Proveedor':
                                $field = 'id_proveedor';
                                break;
                            case 'Tienda':
                                $field = 'tienda';
                                break;
                            case 'Categoria':
                                $field = 'categoria_id';
                                break;
                        }
                        $columnas[$key] = $field;
                    }
                }

                foreach ($columnas as $key => $value) {
                    if ($this->__get_count_in_array($columnas, $value) > 1) {
                        //Try remove other
                        unset($columnas[$key]);
                    }
                }

                $toUpdate = [];
                //$keys = array_keys($columnas);
                $errors = [];
                for ($i = 2; $i <= count($sheetData); $i++) {
                    $row = [];
                    $valid = true;
                    $error_message = "";
                    foreach ($sheetData[$i] as $key => $value) {
                        if (in_array($key, array_keys($columnas))) {
                            $columna = $columnas[$key];
                            switch ($columna) {
                                case 'id':
                                    if (trim($value) === '' || !is_numeric(trim($value)) ) {
                                        $valid = false;
                                        $error_message = "Identificador inválido.";
                                        break;
                                    }
                                    break;
                                case 'codigo':
                                    $value = $value;
                                break;
                                case 'precio_compra':
                                    $value = $value;
                                    break;
                                case 'precio_venta':
                                    $value = $value;
                                    break;
                                case 'stock_minimo':
                                    $value = $value;
                                    break;
                                case 'stock_maximo':
                                    $value = $value;
                                    break;
                                case 'impuesto':
                                    $impuesto = $this->impuestos->get_id($value);
                                    if(empty($impuesto) && !empty($value)){
                                        $valid = false;
                                        $error_message = "Impuesto {$value} no existente.";
                                        break;
                                    }
                                    else if(empty($impuesto) && empty($value)) {
                                        $value = 1;
                                    } else {
                                        $value = $impuesto;
                                    }
                                    
                                    break;
                                case 'descripcion':
                                    $value = $value;
                                    break;
                                case 'activo':
                                    $value = (strtoupper($value) == 'SI' ? 1 : 0);
                                    break;
                                case 'fecha_vencimiento':
                                    $value = $value;
                                    break;
                                case 'vendernegativo':
                                    $value = (strtoupper($value) == 'SI' ? 1 : 0);
                                    break;
                                case 'id_proveedor':
                                    $proveedor = $this->proveedores->get_by_name($value);
                                    $value = $proveedor;
                                    break;
                                case 'tienda':
                                    $value = (strtoupper($value) == 'SI' ? 1 : 0);
                                    break;
                                case 'categoria_id':
                                    $categoria = $this->categorias->get_by_name($value);
                                    if(is_null($categoria)){
                                        $valid = false;
                                        if(empty($value)){
                                            $error_message = "Categoría no puede ser vacia.";
                                        }
                                        else{
                                            $error_message = "Categoría '{$value}' no existente.";
                                        }
                                        break;
                                    }
                                    $value = $categoria;
                                break;
                            }
                            $row[$columna] = $value;
                        }
                    }
                    
                    if ($valid) {
                        array_push($toUpdate, $row);
                    }
                    else {
                        array_push($errors, $error_message);
                    }
                }
                
                // var_dump($toUpdate); die;
                if(!empty($toUpdate)){
                    $this->productos->update_base($toUpdate);
                }
                $this->session->set_flashdata('estado', 'ok');
                $message = 'Plantilla procesada satisfactoriamente, se actualizaron ' . count($toUpdate) . ' productos.';
                if(count($errors) > 0){
                    if(count($errors) > 1){
                        $message .= " No se procesaron " . count($errors) . " registros.";
                    }
                    else {
                        $message .= " No se procesó " . count($errors) . " registro.";
                    }

                    foreach($errors as $error){
                        $message .= "$error";
                    }
                }

                $data['estado'] = 'ok';
                $data['upload_status'] = $message;
                //$this->layout->show('configuracion/actualizacion_masiva_productos.php', array('data' => $data));
                $this->session->set_flashdata('upload_status', $message);
                redirect('frontend/configuracion','refresh');
            }
        } else {
            $data['estado'] = 'error';
            $data['upload_error'] = $error_upload;
            //$this->layout->show('configuracion/actualizacion_masiva_productos.php', array('data' => $data));
            redirect('frontend/configuracion','refresh');
        }
    }

    public function exportar_base_productos_con_atributos() {
        $hoja_productos = $this->load->library('phpexcel');
        $hoja_productos = new PHPExcel();
        $hoja_productos->setActiveSheetIndex(0);
        $columnas = $this->__get_header_productos_con_atributos();

        for ($i = 0; $i < count($columnas); $i++) {
            $row = 1;
            $hoja_productos->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $columnas[$i]);
        }

        foreach ($hoja_productos->getWorksheetIterator() as $worksheet) {
            $hoja_productos->setActiveSheetIndex($hoja_productos->getIndex($worksheet));

            $sheet = $hoja_productos->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $hoja_productos->getActiveSheet()->setTitle('Productos');
        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Plantilla productos con atributos.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($hoja_productos, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function importar_productos_con_atributos() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $error_upload = "";
        $this->layout->template('member');
        $carpeta = 'uploads/archivos_productos/';

        if (!file_exists($carpeta))
            mkdir($carpeta, 0777, true);

        foreach (new DirectoryIterator("uploads/archivos_productos") as $fileInfo) {
            if (!$fileInfo->isDot())
                unlink($fileInfo->getPathname());
        }

        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx|xls';
        $prefijo = substr(md5(uniqid(rand())), 0, 8);
        $config['file_name'] = $prefijo . $this->session->userdata('user_id');
        $this->load->library('upload', $config);
        $res_data = array();
        $datos_fallo = false;
        $mensaje = '';
        $columnas = $this->__get_header_productos_con_atributos();
        $data['upload_status'] = '';

        if (!empty($_FILES['archivo']['name'])) {
            if (!$this->upload->do_upload('archivo')) {
                $error_upload = 'No se pudo procesar el archivo por favor vuelva a cargar la plantilla o descarguela nuevamente.';
                $data['estado'] = 'error';
                $data['upload_error'] = $error_upload;
                $this->layout->show('configuracion/productos_con_atributos.php', array('data' => $data));
            } else {
                $this->load->library('phpexcel');
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                $last_index = -1;

                if (count($sheetData[1]) != count($columnas)) {
                    $sheet = array_values($sheetData[1]);

                    $last_index = count($sheet) - 1;
                    if (count($sheetData[1]) == count($columnas) + 1 && $sheet[$last_index] == 'errores') {
                        
                    } else {
                        $datos_fallo = true;
                        $mensaje .= 'El archivo cargado no tiene la misma estructura que la plantilla.';
                    }
                }

                // Inicio procesamiento de archivo
                if (!$datos_fallo) {
                    $todos_almacenes = $this->almacenes->get_combo_data();
                    $todos_atributos = $this->atributos->getAtributos();
                    $index_almacenes = 11;
                    $index_atributos = $index_almacenes + count($todos_almacenes);

                    $principal_almacenes = [];
                    $principal_atributos = [];
                    $categorias = [];
                    $atributos = [];

                    $row = 1;
                    $errores = array();
                    foreach ($sheetData as $key => $value) {
                        $regist = array_values($value);
                        //obtener indices para recorrido de campos horizontales.                        
                        for ($i = $index_almacenes; $i < $index_almacenes + count($todos_almacenes); $i++) {
                            $parts = explode('/', $regist[$i]);
                            if (count($parts) > 1) {
                                $id_almacen = $this->almacenes->get_by_name(trim($parts[1]));
                                $principal_almacenes[$i] = [
                                    'id' => $id_almacen,
                                    'almacen' => trim($parts[1])
                                ];
                            } else {
                                $datos_fallo = true;
                                $mensaje .= 'El archivo cargado no tiene la misma estructura que la plantilla.';
                            }
                        }
                        //obtener indices para recorrido de campos horizontales.
                        for ($i = $index_atributos; $i < $index_atributos + count($todos_atributos); $i++) {
                            $id_atributo = $this->atributos->getAtributoByName($regist[$i]);
                            $principal_atributos[$i] = [
                                'id' => $id_atributo,
                                'nombre' => $regist[$i]
                            ];
                        }
                        break;
                    }//var_dump($regist);
                    //echo "__________________________________________________________________<br>";
                    $row = 0;
                    $array_insertar = [];
                    $columnas['errores'] = 'errores';
                    $array_errores = [];

                    array_push($array_errores, $columnas);
                    //var_dump($sheetData);
                    foreach ($sheetData as $key => $value) {
                        $regist = array_values($value);
                        //var_dump($regist[4]);echo("<br>");
                        if ($last_index > 0) {
                            unset($regist[$last_index]);
                        }
                        $array_producto_detalle = [];
                        $array_producto = [];
                        $array_cantidades_almacenes = [];
                        $valido = true;
                        $error = '';

                        $row ++;

                        if ($row == 1)
                            continue;

                        //validar categorias
                        //validar para evitar objetos duplicados.
                        $categoria = $this->categorias->get_by_name($regist[0]);
                        $existe_categoria_en_arreglo = false;

                        foreach ($categorias as $cat) {
                            if ($cat['id'] == $categoria) {
                                $existe_categoria_en_arreglo = true;
                                break;
                            }
                        }

                        if (!$existe_categoria_en_arreglo && $categoria != '')
                            array_push($categorias, ['id' => $categoria, 'descripcion' => $regist[0]]);

                        // crear estructura para insertar productos con atributos.
                        // codigo
                        $codigo = '';
                        $codigo_invalido = false;
                        $codigo_duplicado = null;
                        if (strtoupper(trim($regist[4])) == 'SI' OR trim($regist[4]) == '1')
                            $codigo = strtoupper(md5(microtime()));
                        else
                            $codigo = $regist[5];

                        if ($codigo != '')
                            $codigo_duplicado = $this->productos->get_by_codigo_barras($codigo);
                        else
                            $codigo_invalido = true;

                        // activo
                        $activo = 1;
                        if (strtoupper(trim($regist[9])) == 'SI' OR trim($regist[9]) == '1')
                            $activo = 1;
                        else
                            $activo = 0;

                        // tienda
                        $tienda = 0;
                        if (strtoupper(trim($regist[10])) == 'SI' OR trim($regist[10]) == '1' OR trim($regist[10]) == '')
                            $tienda = 1;
                        else
                            $tienda = 0;

                        $id_categoria = '';
                        $nombre_categoria = '';
                        foreach ($categorias as $cat) {
                            if (trim(strtolower($cat['descripcion'])) == trim(strtolower($regist[0]))) {
                                $id_categoria = $cat['id'];
                                $nombre_categoria = $cat['descripcion'];
                                break;
                            }
                        }

                        // obtener id impuesto
                        $id_impuesto = $this->impuestos->get_by_name($regist[8]);

                        // validar almacenes
                        $array_almacenes = [];
                        $noalmacen = 0;

                        for ($i = $index_almacenes; $i < $index_almacenes + count($todos_almacenes); $i++) {
                            $noalmacen ++;
                            $temp = [
                                'id' => $principal_almacenes[$i]['id'],
                                'nombre_almacen' => $principal_almacenes[$i]['almacen'],
                                'unidades' => trim($regist[$i]) == '' ? 0 : trim($regist[$i])
                            ];
                            $array_almacenes['almacen' . $noalmacen] = (object) $temp;
                        }

                        $array_almacenes['cantidad_almacenes'] = $noalmacen;

                        $array_atributos = [];
                        $nombre_con_atributos = $regist[1];
                        $atributo_invalido = false;
                        $atributo_error = '';
                        // validar y crear array atributos

                        if (strlen($id_categoria) > 0) {
                            for ($i = $index_atributos; $i < $index_atributos + count($todos_atributos); $i++) {
                                $existe_atributo = false;
                                if (strlen(trim($regist[$i])) > 0) {
                                    $atributo = $this->atributos->create_or_get($regist[$i], $principal_atributos[$i]['id']);
                                    $nombre_con_atributos .= '/' . $atributo['valor'];

                                    foreach ($atributos as $attr) {
                                        if ($attr['id'] == $atributo['id']) {
                                            $existe_atributo = true;
                                            break;
                                        }
                                    }

                                    if (!$existe_atributo)
                                        array_push($atributos, $atributo);

                                    array_push($array_atributos, [
                                        'id_atributo' => $principal_atributos[$i]['id'],
                                        'nombre_atributo' => $principal_atributos[$i]['nombre'],
                                        'id_clasificacion' => $atributo['id'],
                                        'nombre_clasificacion' => $atributo['valor']
                                    ]);

                                    if ($this->atributos->categoria_posee_clasificacion($principal_atributos[$i]['id'], $id_categoria) == 0) {
                                        $atributo_invalido = true;
                                        $atributo_error .= 'El atributo "' . $principal_atributos[$i]['nombre'] . '" no pertenece a la categoria del producto. ';
                                    }
                                }
                            }
                        }
                        //var_dump($regist);
                        // hacer validaciones antes de insertar
                        $array_producto_detalle = [
                            'codigo_interno' => intval($this->atributos->getIdPrductoAtributos()) + 1,
                            'referencia_producto' => $regist[3],
                            'nombre_producto' => $regist[1],
                            'codigo_barras' => $codigo,
                            'id_categoria' => $id_categoria,
                            'nombre_categoria' => $nombre_categoria,
                            'atributos' => $array_atributos
                        ];

                        $array_producto = [
                            'imagen' => '',
                            'imagen1' => '',
                            'imagen2' => '',
                            'imagen3' => '',
                            'imagen4' => '',
                            'imagen5' => '',
                            "nombre" => $nombre_con_atributos,
                            "codigo_barra" => $codigo,
                            "descripcion" => $regist[2],
                            "precio_compra" => $regist[6],
                            "precio_venta" => $regist[7],
                            "categoria_id" => $id_categoria,
                            "impuesto" => $id_impuesto,
                            'activo' => $activo,
                            "tienda" => $tienda
                        ];
                     
                        $array_cantidades_almacenes = [
                            'lista_almacenes' => (object) $array_almacenes
                        ];

                        // validaciones

                        if(empty($array_atributos)){
                            $error .= 'Los atributos no pueden ser blanco. ';
                            $valido = false;
                        }

                        if (strlen($id_categoria) == 0) {
                            $error .= 'La categoria seleccionada no existe. ';
                            $valido = false;
                        }

                        if (strlen($id_impuesto) == 0) {
                            $error .= 'El impuesto seleccionado no existe. ';
                            $valido = false;
                        }

                        if ($codigo_invalido) {
                            $error .= 'El codigo ingresado es invalido. ';
                            $valido = false;
                        }

                        if ($codigo_duplicado > 0) {
                            $error .= 'Ya existe un producto registrado con este codigo de barras. ';
                            $valido = false;
                        }

                        if ($atributo_invalido && strlen($id_categoria) > 0) {
                            $error .= $atributo_error;
                            $valido = false;
                        }
                        
                        if($regist[3] == "")
                        {
                            $error .= "Debe ingresar una referencia. ";
                            $valido = false;
                        }

                        $regist['errores'] = $error;
                        array_push($array_errores, $regist);

                        if ($valido == false)
                            $datos_fallo = true;

                        if ($valido) {
                            array_push($array_insertar, [
                                'producto' => $array_producto,
                                'cantidades' => $array_cantidades_almacenes,
                                'atributos' => $array_producto_detalle
                            ]);
                        }
                    }
                    //die("si");

                    if ($datos_fallo) {
                        $data['upload_error'] = 'Error!';
                        //echo 'errores';
                        $route_file = $this->__create_error_report_file($array_errores);
                        //$this->exportarFallos($archivo);
                        $data['estado'] = 'error';
                        $data['upload_status'] = 'La plantilla no se pudo procesar porque algunos campos presentan conflictos, por favor descargue el archivo <a class="link_session" href="'.base_url().$route_file.'">Aqui</a> con errores solucionelos e intente nuevamente.';
                    } else {
                        //echo 'insertar';
                        $data['estado'] = 'ok';
                        $data['upload_status'] = 'La plantilla se proceso satisfactoriamente, se han creado ' . count($array_insertar) . ' productos con atributos.';
                        foreach ($array_insertar as $insertar) {
                            $id_producto = $this->atributos->setAddProductoAttr($insertar['producto'], $insertar['cantidades'], $insertar['atributos']);
                        }
                    }
                } else {
                    $data['upload_error'] = 'Error!';
                    $data['estado'] = 'error';
                    $data['upload_status'] = 'La plantilla no se pudo procesar porque la cantidad de campos es invalida, por favor descargue la plantilla y haga una verificación manual.';
                }

                //$this->layout->show('configuracion/productos_con_atributos.php', array('data' => $data));
                $this->session->set_flashdata('estado', 'ok');
                $this->session->set_flashdata('upload_status', $data['upload_status']);
                redirect('frontend/configuracion','refresh');
            }
        } else {
            $data['estado'] = 'error';
            $data['upload_error'] = $error_upload;
            $this->session->set_flashdata('estado', 'error');
            $this->session->set_flashdata('upload_status', $data['upload_status']);
            //$this->layout->show('configuracion/productos_con_atributos.php', array('data' => $data));
        }
    }

    private function __create_error_report_file(array $data) {
        $carpeta = "uploads/".$this->session->userdata('base_dato')."/archivos_productos";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        @chmod("../../uploads/".$this->session->userdata('base_dato')."/archivos_productos/", 0777);
        @unlink("../../uploads/".$this->session->userdata('base_dato')."/archivos_productos/Productos con atributos no guardados.xlsx");

        $hoja_productos = $this->load->library('phpexcel');
        $hoja_productos = new PHPExcel();
        $hoja_productos->setActiveSheetIndex(0);

        $hoja_productos->getActiveSheet()->fromArray($data, null, 'A1');

        foreach ($hoja_productos->getWorksheetIterator() as $worksheet) {
            $hoja_productos->setActiveSheetIndex($hoja_productos->getIndex($worksheet));

            $sheet = $hoja_productos->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

       
        $hoja_productos->getActiveSheet()->setTitle('Productos');

        $objWriter = PHPExcel_IOFactory::createWriter($hoja_productos, 'Excel2007');
        $objWriter->save("uploads/".$this->session->userdata('base_dato')."/archivos_productos/Productos con atributos no guardados.xlsx");
        
        $route_file = $carpeta .'/Productos con atributos no guardados.xlsx';
        return $route_file;
    }

    private function __get_header_productos_con_atributos() {
        $almacenes = $this->almacenes->get_combo_data();
        $atributos = $this->atributos->getAtributos();

        $columnas = [
            'categoria',
            'nombre',
            'descripcion',
            'referencia',
            'codigo de barras_automatico(Si, No)',
            'codigo de barras',
            'precio compra (sin iva)',
            'precio venta (sin iva)',
            'impuesto',
            'activo (Si, No)',
            'tienda (Si, No)',
        ];

        foreach ($almacenes as $almacen) {
            array_push($columnas, 'cantidad / ' . $almacen);
        }

        foreach ($atributos as $atributo) {
            array_push($columnas, $atributo->nombre);
        }

        return $columnas;
    }

    private function __get_count_in_array($array, $item) {
        $total = 0;
        foreach ($array as $key => $value) {
            if ($value == $item) {
                $total += 1;
            }
        }

        return $total;
    }

    public function eliminarConfirmacion($id = 0) {
        if ($id != 0) {
            echo json_encode(array("resp" => $this->productos->eliminarConfirmacion($id)));
        }
    }

    public function store_price_update() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');
        $data['almacenes'] = array(0 => 'Todos');
        $data['almacenes'] = array_merge($data['almacenes'], $this->almacenes->get_combo_data());
        $data['proveedores'] = $this->proveedores->get_combo_data();
        $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');

        //$this->layout->template('ventas')->show('productos/import_excel_price_update', array('data' => $data));
        $this->layout->template('member')->show('productos/import_excel_price_update', array('data' => $data));
        
    }

    function actualizar_precios_almacen($update_confirm,$almacen_id,$row,$value){
        $cantidad=$value['B'];
        $estado="Pendiente";
        $stock_a=$this->stock_actual->get_by_prod_almac($almacen_id, $row->id);
        if($update_confirm==1){                                    
            $stock_a=$this->stock_actual->get_by_prod_almac($almacen_id, $row->id); 
            $cantidad=$stock_a['unidades']+$value['B'];
        }

        
        if(!empty($value['F'])){
            $impuesto = $this->impuestos->get_id($value['F']);        
            if(empty($impuesto)){
                $estado='<label class="label label-danger white">No existe Impuesto.</label>';
                $impuesto=$value['F'];
                $impuesto_name=$value['F'];
            }else{
                if(is_numeric($impuesto)) {
                    $impuesto_name = $this->impuestos->get_by_id($impuesto);
                    $impuesto_name=$impuesto_name['nombre_impuesto'];
                } else {
                    $impuesto_name = $this->impuestos->get_name_by_porcent($value['F']);
                    $impuesto_name=$impuesto_name['nombre_impuesto'];
                }
            }
        }else{
            if(!empty($stock_a['impuesto'])){
                $impuesto = $this->impuestos->get_id($stock_a['impuesto'],1);
                $impuesto_name = $this->impuestos->get_by_id($impuesto);
                $impuesto_name=$impuesto_name['nombre_impuesto'];
            } else{
                $estado='<label class="label label-danger white">El producto no tiene impuesto asociado.</label>';
                $impuesto=null;
                $impuesto_name=null;
            }          
        }

        $activo=(!empty($value['G']))? $value['G']: $stock_a['activo'];       

        $queryUpdate = array(
            "codigo" => $value['A'],
            "nombre" => $row->nombre,
            "unidades" => $cantidad,
            "stock_minimo" => (!empty($value['C']))? $value['C']: $stock_a['stock_minimo'],
            "precio_compra" => (!empty($value['D']))? $value['D']: $stock_a['precio_compra'],
            "precio_venta" => (!empty($value['E']))? $value['E']: $stock_a['precio_venta'],
            "impuesto" => $impuesto,
            "impuesto_name" => $impuesto_name,
            "activo" => (strtolower($activo) == 'si' || $activo == 1)?1:0,
            "estado" => $estado,
            "fecha_vencimiento" => (!empty($value['H']))? $value['H']: $stock_a['fecha_vencimiento'],
        );

        $dataUpdateAux = $queryUpdate;

        unset($queryUpdate['codigo']);
        unset($queryUpdate['nombre']);
        unset($queryUpdate['estado']);
        unset($queryUpdate['impuesto_name']);
        
        if(empty($queryUpdate['unidades'])){
            unset($queryUpdate['unidades']);
        }
        
        if (($update_confirm == 1) && ($estado=="Pendiente")) { 
            $resUpdate = $this->stock_actual->update_array($almacen_id, $row->id, $queryUpdate);
            if ((!empty($value['B'])) && ($value['B']>0)) {
                //actualizo el sctok_diario
                $data_stock_diario[] =  array(
                    'producto_id' =>$row->id
                    , 'almacen_id' => $almacen_id
                    , 'fecha' => date('Y-m-d')
                    , 'unidad' => $value['B']
                    , 'precio' => (!empty($value['E']))? $value['E']: $stock_a['precio_venta']
                    ,'usuario' => $this->session->userdata('user_id')
                    , 'razon' => 'E'
                );         
                $this->stock_diario->add($data_stock_diario);
   
                //ingreso el movimiento
                $precio_compra=(!empty($value['D']))? $value['D']: $stock_a['precio_compra'];
                $total_inventario=($precio_compra * $value['B']);
                $datamovimiento =
                    array(
                        'fecha' => date('Y-m-d H:i:s'), 
                        'almacen_id' => $almacen_id, 
                        'tipo_movimiento' =>'entrada_producto', 
                        'user_id' => $this->session->userdata('user_id'), 
                        'total_inventario' => $total_inventario
                    );

                $id_inventario=$this->inventario->add_csv_inventario_1($datamovimiento);
                
                $data_detalles[] =  array(

                    'id_inventario' => $id_inventario
                    , 'codigo_barra' => $value['A']
                    , 'cantidad' => $value['B']
                    , 'precio_compra' => (!empty($value['D']))? $value['D']: $stock_a['precio_compra']
                    ,'existencias' => $stock_a['unidades']
                    , 'nombre' => $row->nombre
                    ,'total_inventario' => $total_inventario                    
                    , 'producto_id' =>$row->id 
                ); 
                $this->inventario->add_csv_MovientosDetalles($data_detalles);
          
            }
            //ingresar el registro en movimiento_detalle
            if($resUpdate > 0) {
                $dataUpdateAux['estado'] = '<label class="label label-success white">Actualizado</label>';
            } else {
                $dataUpdateAux['estado'] = '<label class="label label-danger white">No afectado</label>';
            }
        }else{
            if ($update_confirm == 1) { 
                $dataUpdateAux['estado'] = '<label class="label label-danger white">No afectado</label>';
            }
        }
        return $dataUpdateAux;
    }

    public function import_excel_price_update() {
        // Dev: Leonardo Molina
        $error_upload = "";
        $total = 0;
        $total_correctos = 0;
        $total_incorrectos = 0;

        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'xlsx|xls';
        $image_name = "";
        $this->load->library('upload', $config);
        $dataUpdate = array();

        if (!empty($_FILES['archivo']['name'])) {
            if (!$this->upload->do_upload('archivo')) {
                $data = array(
                    'error' => 'Archivo no compatible. Asegurese que sea .XLS, .XLSX.',
                    'res' => 'error'
                );
            } else {
                $this->load->library('phpexcel');
                $name = $_FILES['archivo']['name'];
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                $arr_datos = array();
                $dataQuery = array(
                    'user_id' => $this->session->userdata('user_id')
                    , 'fecha' => $_POST['fecha'] . " " . date("H:i:s")
                    , 'almacen_id' => $_POST['almacen_id']
                );
                $almacen_id = null;
                $nombre_almacen = 'Todos';
                if ($_POST['almacen_id'] > 0) {
                    $almacen_id = $_POST['almacen_id'];
                    $almacenes = $this->almacenes->get_by_id($almacen_id);
                    $nombre_almacen = $almacenes['nombre'];
                }

                $update_confirm = $_POST['update_confirm'];
                $bandera=0;
                // var_dump($update_confirm);
                foreach ($sheetData as $index => $value) {
                    if ($index > 1) {
                        if ($value['A'] != '') {
                            $row = $this->productos->get_by_code($value['A']);
                            
                            if (isset($row->nombre)) { // Si el producto existe   
                                
                                if($update_confirm==1){
                                    //verifico si el almacen es todos o uno especifico
                                    if($almacen_id==0){                                        
                                        //busco los almacenes asociados
                                        $al=$this->almacenes->getAll();                                       
                                        foreach ($al as $alma) {                                                                                        
                                            $dataUpdateAux=$this->actualizar_precios_almacen($update_confirm,$alma->id,$row,$value);
                                            
                                        }                                        
                                    }else{
                                        $dataUpdateAux=$this->actualizar_precios_almacen($update_confirm,$almacen_id,$row,$value);
                                    }
                                    
                                }else{
                                    $dataUpdateAux=$this->actualizar_precios_almacen($update_confirm,$almacen_id,$row,$value);
                                }
                                                                                                   
                            }else{ // Si el producto no existe, se notifica al usuario
                                $dataUpdateAux = array(
                                    "codigo" => $value['A'],
                                    "nombre" => 'N/A',
                                    "unidades" => $value['B'],
                                    "stock_minimo" => $value['C'],
                                    "precio_compra" => $value['D'],
                                    "precio_venta" => $value['E'],
                                    "impuesto" => $this->impuestos->get_id($value['F']),
                                    "impuesto_name" => $value['F'],
                                    "activo" => $value['G'],
                                    "fecha_vencimiento" => $value['H'],
                                    "estado" => '<label class="label-danger white">No existe.</label>',
                                );
                            }
                            $dataUpdate[] = $dataUpdateAux;
                        }
                    }
                }
                
                if ($update_confirm == 1) {// Si se ha confirmado la carga de archvos
                    $html = " <div class='panel newPanel'>
                <div class='errorH' ><i class='icon wb-success' aria-hidden='true'></i>Se han actualizado los siguientes registros</div>
                <div class='descError'> A partir de este momento podrá acceder a las funciones de precios por almacén</div><br><hr>";
                } else {
                    $html = " <div class='panel newPanel'>
                <div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Confirme la actualización de datos para los siguientes almacenes: <label class='label label-success'>".$nombre_almacen."</label></div>
                <div class='descError'> Por favor asegurese de que los datos sean correctos y confirme la carga</div><br><hr>
                <button type=\"button\" onclick=\"$('#form_price_update').submit()\" class=\"btn btn-success\" style=\"margin: 0 8px 0 8px\"><li class=\"icon-ok\"></li> Cargar datos</button>";
                }
                $html .= "<table class='table aTable' cellpadding='0' cellspacing='0' width='100%' style='margin-top: 2%;'>";
                $html .= "<tr><th>Código</th>";
                $html .= "<th>Nombre</th>";
                $html .= "<th>Cantidad</th>";
                $html .= "<th>Stock Mínimo</th>";
                $html .= "<th>Precio Compra</th>";
                $html .= "<th>Precio Venta</th>";
                $html .= "<th>Impuesto</th>";
                $html .= "<th>Activo</th>";
                $html .= "<th>Vence</th>";
                $html .= "<th></th><tr>";

                foreach ($dataUpdate as $rowData) {
                    
                    if($rowData['activo']== 1)$activo = 'Si';else $activo = 'No';
                    
                    $html .= "<tr>";
                    $html .= "<td>" . $rowData['codigo'] . "</td>";
                    $html .= "<td>" . $rowData['nombre'] . "</td>";
                    $html .= "<td>" . $rowData['unidades'] . "</td>";
                    $html .= "<td>" . $rowData['stock_minimo'] . "</td>";
                    $html .= "<td>" . $rowData['precio_compra'] . "</td>";
                    $html .= "<td>" . $rowData['precio_venta'] . "</td>";
                    $html .= "<td>" . $rowData['impuesto_name'] . "</td>";
                    $html .= "<td>" . $activo . "</td>";
                    $html .= "<td>" . $rowData['fecha_vencimiento'] . "</td>";
                    $html .= "<td>" . $rowData['estado'] . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</table>";
                $html .= '<br><button class="btn btn-default"  type="button" onclick="javascript:location.href = \'store_price_update\'">Volver</button>';


                if ($update_confirm == 1) {
                    $data = array(
                        'html' => $html,
                        'res' => 'success',
                        'success' => 'Se ha actualizado con éxito los registro que han sido validados correctamente!',
                    );
                } else {
                    $data = array(
                        'html' => $html,
                        'res' => 'warning',
                        'success' => '<!--<button type="button" onclick="$(\'#form_price_update\').submit()" class="btn btn-success" style="margin: 0 8px 0 8px">Cargar datos!</button>-->',
                    );
                }
            }
        } else {
            $data = array(
                'error' => 'Asegurese de seleccionar un archivo.',
                'res' => 'error'
            );
        }
        // Respuesta en Json
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
    }
    
    public function import_compuesto() {
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');
        $data['almacenes'] = array(0 => 'Todos');
        $data['almacenes'] = array_merge($data['almacenes'], $this->almacenes->get_combo_data());
        $data['proveedores'] = $this->proveedores->get_combo_data();
        $data['tipo'] = $this->miempresa->get_nomen('tipo_movimiento');

        $this->layout->template('ventas')->show('productos/import_compuesto', array('data' => $data));
    }
    
    public function load_plantilla_compuesto() {

        $this->load->library('phpexcel');
        $compuestos = $this->productos->get_compuestos();
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        //===========================================================================
        // Creacion dinamica de los titulos en un ARRAY[]
        //===========================================================================
        // Master header en el que se guardaran todos los titulos
        $masterTitulos = Array();

        // Titulos estaticos, los que van por defecto
        $camposEstaticos = [
            "Compuesto",
            "Cod. Ingrediente",
            "Ingrediente",
            "Cantidad",
            "Unidad",
        ];
        //-----------------------------------------        
        // Añadimos titulos a masterHeader
        //-----------------------------------------

        $masterTitulos = array_merge($masterTitulos, $camposEstaticos);
        //===========================================================================
        // Escribimos los titulos o encabezados segun el contenido de $masterTitulos 
        //===========================================================================
        // Habilidamos salto de linea en los encabezados
        $ultimaColumna = count($masterTitulos) - 1;
        $excel->getActiveSheet()->getStyle('A1:' . $this->i2t($ultimaColumna) . '1')->getAlignment()->setWrapText(true);

        $fila = 1;
        foreach ($masterTitulos as $key => $val) {
            $columna = $key;
            // si tenemos un salto de linea lo aplicamos
            $text = explode('\n', $val);
            $finalText = isset($text[1]) ? trim($text[0]) . "\n" . trim($text[1]) : trim($text[0]);
            // Escribimos en el EXCEL !!
            $excel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $finalText);
        }
        
        foreach($compuestos as $rowCompuestos){
            $fila++;
            // Escribimos en el EXCEL los productos
            $excel->getActiveSheet()->setCellValueByColumnAndRow('A', $fila, $rowCompuestos->nombre);
        }

        //===========================================================================
        // Ajustamos dimensiones columnas
        //===========================================================================        
        // Alto de la primer fila
        $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);

        // Inicialmente ancho automaticos para todos y los separadores con ancho fijo
        foreach ($masterTitulos as $key => $val) {
            $columna = $key;
            if ($val == "")
                $excel->getActiveSheet()->getColumnDimensionByColumn($columna)->setAutoSize(false)->setWidth(5);
            else
                $excel->getActiveSheet()->getColumnDimensionByColumn($columna)->setAutoSize(true);
        }

        // Posteriormente cambiamos el ancho de los campos obligatorios o fijos

        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false)->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false)->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false)->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false)->setWidth(20);

        //===========================================================================
        // Aplicamos Estilos a los títulos
        //=========================================================================== 
        // Alineamos a la derecha la columna D de Impuesto
        $excel->getActiveSheet()->getStyle('D')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));

        foreach ($masterTitulos as $key => $val) {
            $columna = $this->i2t($key);
            if ($val != "") {
                $excel->getActiveSheet()->getStyle($columna . '1')->applyFromArray(
                        array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '76933c')
                                )
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'startcolor' => array('rgb' => 'c6efce')
                            ),
                            'font' => array(
                                'bold' => true,
                                'color' => array('rgb' => '32482b')
                            )
                        )
                );
            } else {
                $backgrounSeparadores = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'eeeeee')
                    )
                );
                $excel->getActiveSheet()->getStyle($columna . "1")->applyFromArray($backgrounSeparadores);
                $excel->getActiveSheet()->getStyle($columna)->applyFromArray($backgrounSeparadores);
            }
        }
        // Enfocamos la primer celda editable
        $excel->getActiveSheet()->setSelectedCells('A2');
        $excel->getActiveSheet()->setTitle('Plantilla Compuestos');

        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Plantilla Compuestos.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output');
    }

    public function import_compuesto_save() {
        // Dev: Leonardo Molina
        $error_upload = "";
        $total = 0;
        $total_correctos = 0;
        $total_incorrectos = 0;

        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'xlsx|xls';
        $image_name = "";
        $this->load->library('upload', $config);
        $dataUpdate = array();

        if (!empty($_FILES['archivo']['name'])) {
            if (!$this->upload->do_upload('archivo')) {
                $data = array(
                    'error' => 'Archivo no compatible. Asegurese que sea .XLS, .XLSX.',
                    'res' => 'error'
                );
            } else {
                $this->load->library('phpexcel');
                $name = $_FILES['archivo']['name'];
                $tname = $_FILES['archivo']['tmp_name'];
                $obj_excel = PHPExcel_IOFactory::load($tname);
                $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                
                $update_confirm = $_POST['update_confirm'];
                //  var_dump($update_confirm);
                
                foreach ($sheetData as $index => $value) {
                    if ($index > 1) {
                        if ($value['A'] != '' && $value['B'] != '') {
                            // Informacion del producto compuesto
                            $compuesto = $this->productos->get_by_name($value['A']);
                            // Informacion del ingrediente 
                            $row = $this->productos->get_by_code($value['B']);
                            
                            if (isset($compuesto->id) && isset($row->id)) { // Si el producto y el ingrediente existe
                                $queryUpdate = array(
                                    "id_producto" => $compuesto->id,
                                    "id_ingrediente" => $row->id,
                                    "cantidad" => $value['D']// Cantidad
                                );
                                $dataUpdateAux = array(
                                    "compuesto" => $compuesto->nombre,
                                    "cod_ingrediente" => $value['B'],
                                    "nom_ingrediente" => $row->nombre,
                                    "cantidad" => $value['D'],
                                    "unidad" => $value['E'],
                                    "estado" => 'Pendiente'
                                );
                                
                                if ($update_confirm == 1) {
                                    $resUpdate = $this->productos->set_producto_ingredientes($queryUpdate);
                                    if($resUpdate > 0) {
                                        $dataUpdateAux['estado'] = '<label class="label label-success white">Actualizado</label>';
                                    } else {
                                        $dataUpdateAux['estado'] = '<label class="label label-danger white">No afectado</label>';
                                    }
                                }
                            }else{ // Si el producto o ingrediente no existe, se notifica al usuario
                                $label = '';
                                if(!isset($compuesto->id)){
                                    $label .= '- Compuesto ';
                                }
                                if(!isset($row->id)){
                                    $label .= '- Ingrediente';
                                }
                                $dataUpdateAux = array(
                                    "compuesto" => $value['A'],
                                    "cod_ingrediente" => $value['B'],
                                    "nom_ingrediente" => $value['C'],
                                    "cantidad" => $value['D'],
                                    "unidad" => $value['E'],
                                    "estado" => '<label class="label-danger white">No existe '.$label.'</label>',
                                );
                            }
                            $dataUpdate[] = $dataUpdateAux;
                        }
                    }
                }
                
                if ($update_confirm == 1) {// Si se ha confirmado la carga de archvos
                    $html = " <div class='panel newPanel'>
                <div class='errorH' ><i class='icon wb-success' aria-hidden='true'></i>Se han actualizado los siguientes registros</div>
                <br><hr>";
                } else {
                    $html = " <div class='panel newPanel'>
                <div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Confirme la actualización de datos para los siguientes productos</label></div>
                <div class='descError'> Por favor asegurese de que los datos sean correctos y confirme la carga</div><br><hr>
                <button type=\"button\" onclick=\"$('#form_price_update').submit()\" class=\"btn\" style=\"margin: 0 8px 0 8px\"><li class=\"icon-ok\"></li> Cargar datos!</button>";
                }
                $html .= "<table class='table aTable' cellpadding='0' cellspacing='0' width='100%'>";
                $html .= "<tr><th>Compuesto</th>";
                $html .= "<th>Cod. Ingrediente</th>";
                $html .= "<th>Ingrediente</th>";
                $html .= "<th>Cantidad</th>";
                $html .= "<th>Unidad</th>";
                $html .= "<th></th><tr>";

                foreach ($dataUpdate as $rowData) {
                    $html .= "<tr>";
                    $html .= "<td>" . $rowData['compuesto'] . "</td>";
                    $html .= "<td>" . $rowData['cod_ingrediente'] . "</td>";
                    $html .= "<td>" . $rowData['nom_ingrediente'] . "</td>";
                    $html .= "<td>" . $rowData['cantidad'] . "</td>";
                    $html .= "<td>" . $rowData['unidad'] . "</td>";
                    $html .= "<td>" . $rowData['estado'] . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</table>";
                $html .= '<button class="btn btn-default"  type="button" onclick="javascript:location.href = \'import_compuesto\'">Volver</button>';


                if ($update_confirm == 1) {
                    $data = array(
                        'html' => $html,
                        'res' => 'success',
                        'success' => 'Se ha actualizado con éxito!',
                    );
                } else {
                    $data = array(
                        'html' => $html,
                        'res' => 'warning',
                        'success' => '<button type="button" onclick="$(\'#form_price_update\').submit()" class="btn btn-success" style="margin: 0 8px 0 8px">Cargar datos!</button>',
                    );
                }
            }
        } else {
            $data = array(
                'error' => 'Asegurese de seleccionar un archivo.',
                'res' => 'error'
            );
        }
        // Respuesta en Json
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
    }

    public function stockactualespeciales(){
        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $id=$this->input->post('id');
        $tipo=$this->input->post('tipo');
        $unidades=$this->input->post('unidades');
        
        if((!empty($id)) &&(!empty($tipo))){
            //busco los ingredientes del producto
            if($tipo==3){//combo

                $ingredientes=$this->productos->get_productos_combo($id);
                //print_r($ingredientes);  die();
                foreach ($ingredientes as $value) {
                    $cant_comprar=$unidades*$value['cantidad_producto'];
                    if(($value['vendernegativo']==0) && ($value['tipo_producto']==1)) {
                        //tengo stock disponible?
                        //busco almacen asociado
                        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
                        if(empty($almacenActual)){
                            $almacenActual = $this->dashboardModel->getAlmacenActual();
                        }
                        $existencia=$this->productos->obtener_existencias($value['id'], $almacenActual);
                        
                        if($existencia[0]['unidades']>=$cant_comprar){
                             
                        }else{
                            /*return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msj' =>"El producto ".$value['nombre'].", contenido en el Combo no tiene suficiente stock para venderse, comuniquese con el administrador y que le coloque <a href="">stock al producto</a> ó marcar la opción de <a href="">vender en negativo</a>")));*/
                            return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msj' =>"El producto ".$value['nombre'].", contenido en el Combo no tiene suficiente stock para venderse, comuniquese con el administrador para aumentar el <a target='_blank' href='https://ayuda.vendty.com/help/como-actualizo-mi-inventario'>stock al producto</a> ó marcar la opción de <a target='_blank' href='https://ayuda.vendty.com/help/activar-vender-en-negativo-para-todos-los-productos-masivamente'>vender en negativo</a>")));
                        }
                    }   
                }   
            }else{                
                if($tipo==2){//compuesto

                    $ingredientes=$this->productos->get_ingredientes($id);
                    //print_r($ingredientes); 
                    foreach ($ingredientes as $value) {
                        $cant_comprar=$unidades*$value['cantidad_ingrediente'];
                        if($value['vendernegativo']==0){
                            //tengo stock disponible?
                            //busco almacen asociado
                            $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
                            if(empty($almacenActual)){
                                $almacenActual = $this->dashboardModel->getAlmacenActual();
                            }
                            $existencia=$this->productos->obtener_existencias($value['id'], $almacenActual);

                            if($existencia[0]['unidades']>=$cant_comprar){

                            }else{
                                return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msj' =>"El producto ".$value['nombre'].", contenido en el Compuesto no tiene suficiente stock para venderse, comuniquese con el administrador para aumentar el <a target='_blank' href='https://ayuda.vendty.com/help/como-actualizo-mi-inventario'>stock al producto</a> ó marcar la opción de <a target='_blank' href='https://ayuda.vendty.com/help/activar-vender-en-negativo-para-todos-los-productos-masivamente'>vender en negativo</a>")));
                            }
                        }
                    }   
                }   
            }

        }else{
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0,'msj' =>"El producto no tiene suficiente stock para venderse")));
        }
             
    }

    public function editaLibroPreciosAjax(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $body = json_decode(file_get_contents('php://input'), true);

        foreach ($body['books'] as $key => $value) {
            $this->lista_detalle_precios->editListaPrecios($value['id_ldp'], $value['recalculation']);
        }
        
        echo json_encode('{"status": "updated", "message": "Libros actualizados correctamente."}');
    }
}

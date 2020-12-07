<?php
/**
 * Clase Productos para restaurantes
 * @author angeledugo@gmail.com
 */
class productoRestaurant extends CI_Controller {

    
    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user_id');
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
        $this->load->library('form_validation');
        // Productos
        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);  
        // Categorias
        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);

        // Unidades 
        $this->load->model("unidades_model", 'unidades');
        $this->unidades->initialize($this->dbConnection);

        // Impuestos
        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);

        //Empresa
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        // Proveedores
        $this->load->model("proveedores_model", 'proveedores');
        $this->proveedores->initialize($this->dbConnection);

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);
    }
    public function index() {
        //buscamos las categoria de los productos
        $categorias = $this->categorias->getAllCategoria();
        $data['categoria'] = $categorias;
        //print_r($categorias);
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];  
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('new_layout')->show('productos/productos_lists',array('data'=> $data));
    }

    public function getAjaxProductsLike() {
        $q = $this->input->get('q');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->productos->materialLike($q)));
    }

    function getAjaxProductsMaterialLike(){
        $q = $this->input->get('q');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->productos->productoLike($q)));
    }
    public function getAjaxProducts() { 
        $start = $this->input->post('pageIndex');
        $offset = $this->input->post('pageSize');
        $search = $this->input->post('searchBy');
        $filterBy = $this->input->post('filterBy');
        if($filterBy=="all"){
            $filterBy=null;
        }
        
        if(empty($search)){
            $search=null;
        }
       
        $this->output->set_content_type('application/json')->set_output(json_encode($this->productos->paginacion($start,$offset,$search,$filterBy)));
    }   
    function cloneProduct(){
        // Verifcamos si existe
        $id = $this->uri->segment(3);
        if(!empty($id)){
            $producto = $this->productos->get_by_id($id);
        }
        if(count($producto)){
            $almacenes = $this->almacen->getAll();
            $pn = $this->productos->cloneProducto($id,$almacenes);            
            if(!empty($pn)){                       
                $this->session->set_flashdata('message', 'Registro duplicado correctamente');
                redirect("ProductoRestaurant");
            }else{
                $this->session->set_flashdata('message', 'Registro no pudo ser duplicado');
                redirect("ProductoRestaurant");
            }
        }       
    }

    function deleteProduct(){       
        // Verifcamos si existe
        $id = $this->uri->segment(3);
        $producto = $this->productos->get_by_id($id);
        if(count($producto)){
            if($this->productos->deleteProducto($id)){
                $this->session->set_flashdata('message', 'Registro fue Elimando Correctamente');
                redirect("ProductoRestaurant");
            }
        }        
    }

    public function createProduct(){
        
        $data = [];
        $id = $this->uri->segment(3);
        $data['categorias'] = $this->categorias->get_combo_data();
        $data['impuestos'] = $this->impuestos->get_combo_data();
        $data['unidades'] = $this->unidades->get_combo_data();
        $data['proveedores'] = $this->proveedores->obtenerProveedores(); 
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data['precio_almacen'] = $this->opciones->getOpcion('precio_almacen');
        $data['almacenes_inactivo'] = $this->almacen->get_almacenes_inactivos(false);
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"]; 
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
              
        if(empty($id)){
            $data['almacenes'] = $this->almacen->get_all(null);
        }else {                       
            $data['almacenes'] = $this->almacen->get_combo_data_stock_actual($id);                           
        }        

        

        if($id != ''){
            $data['id'] = $id;
            //Obtenemos los datos del producto
            $producto = $this->productos->get_by_id($id);
            //print_r($producto);
            //die();            
            $data['producto'] = $producto;

            //Obtenemos sus modificaciones
            $modificaciones = $this->productos->getModificaciones($id);
            $data_modificacion = array();
            foreach($modificaciones as $key => $value){
                $nombre = $value['nombre'];
                //$nombre = $value->nombre;
                $data_modificacion[] = $nombre;
            }
            $listFinal = implode(',', $data_modificacion);
            $data['modificacion'] = $listFinal;

            //listado de adicionales
            $adicionales = $this->productos->getAdicionales($id);
            $data['adicionales'] = $adicionales;

            //Listamos los ingredientes
            $ingredien = $this->productos->get_ingredientes($id);
            $data['ingredientes'] = $ingredien;

        }
        //Obtenemos los productos que son ingredientes
        $productos = $this->productos->get_producto_material($id);
        $data['productos'] = $productos;

        $this->layout->template('new_layout')->show('productos/create_product', array('data' => $data));
    }
    function store(){        
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $error_upload = "";
        $base_dato = $this->session->userdata('base_dato');
        $carpeta = 'uploads/'.$base_dato.'/imagenes_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        } 

        //validacion de la imagen
        //validacion de adjuntos
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';
        $config['max_size'] = '300';
        $config['max_width'] = '1200';
        $config['max_height'] = '1200';
        $image_name = "";
        $this->load->library('upload', $config);

        //validamos que tipo de producto es 
        $frm_producto_stock = $this->input->post('form_stock');
        $frm_producto_stock1 = array();
        parse_str($frm_producto_stock, $frm_producto_stock1);
       
        if(isset($frm_producto_stock1['Stock'])){
            $_POST['Stock']=$frm_producto_stock1['Stock'];
        }
       
        $form_one = $this->input->post('form_basico');
        $form_one_values = array();
        parse_str($form_one, $form_one_values);

        $form_three = $this->input->post('adicionales');
        $form_three_values = array();
        parse_str($form_three, $form_three_values);        

        $form_four = $this->input->post('modificaciones');
        $form_four_values = array();
        $form_four_values = explode(',',$form_four);
        //$form_four_values = array();
        //parse_str($form_four, $form_four_values);

        extract($form_one_values);

        $nombre = $txt_nombre;
        $nuevo="";
        $id_producto="";
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 1){

            $_POST['Stock_minimo']=isset($frm_producto_stock1['Stock_minimo'])?$frm_producto_stock1['Stock_minimo']:null;
            $_POST['Precio_compra']=isset($frm_producto_stock1['Precio_compra'])?$frm_producto_stock1['Precio_compra']:null;
            $_POST['Precio_venta']=isset($frm_producto_stock1['Precio_venta'])?$frm_producto_stock1['Precio_venta']:null;
            $_POST['Impuesto']=isset($frm_producto_stock1['Impuesto'])?$frm_producto_stock1['Impuesto']:null;
            $_POST['Fecha_vencimiento']=isset($frm_producto_stock1['Fecha_vencimiento'])?$frm_producto_stock1['Fecha_vencimiento']:null;
            $_POST['Activo']=isset($frm_producto_stock1['Activo'])?$frm_producto_stock1['Activo']:null;

            $precio_compra=isset($frm_producto_stock1['Precio_compra'])?array_values($frm_producto_stock1['Precio_compra'])[0]:0;
            $precio_venta=isset($frm_producto_stock1['Precio_venta'])?array_values($frm_producto_stock1['Precio_venta'])[0]:0;
            $id_impuesto=isset($frm_producto_stock1['Impuesto'])?array_values($frm_producto_stock1['Impuesto'])[0]:0;

        }
      
        $active = isset($chk_activo) ? 1 : 0;
        
        $material = 0;
        //if(isset($active)){
            if(isset($chk_ingrediente) && $chk_ingrediente == 1)
                $material = 1;    

            if(!isset($chk_existencia))
                $chk_existencia = 0;     
            // Preparamos el Arreglo para guardar el producto    
                 
                $data = [
                    "nombre" => $txt_nombre,
                    "codigo" => $txt_codigo,
                    "descripcion" => $text_description,
                    "precio_venta" => $precio_venta,
                    "precio_compra" => $precio_compra,
                    "categoria_id" => $slt_categoria,
                    "impuesto" => $id_impuesto,
                    "stock_minimo" => 0,
                    "stock_maximo" => 0,
                    "fecha_vencimiento" => "0000-00-00",
                    "ubicacion" => "",
                    "ganancia" => 0,
                    "activo" => $active,
                    "id_proveedor" => $slt_proveedor,
                    "muestraexist" => $chk_existencia,
                    "material" => $material,
                    "unidad_id" => $unidad_medida,
                    "ingredientes" => $tipo_producto_id == 2 ? 1 : 0,
                    "combo" => $tipo_producto_id == 3 ? 1 : 0
                ];
                           
           
            //guardamos el producto
            if(empty($form)){
                //verificar que no exista el codigo
                $existecodigo=$this->productos->get_by_code($txt_codigo);              
                if(empty($existecodigo)){
                    $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));                    
                    //$id_producto = $this->productos->createProducto($data, $this->session->userdata('user_id'));
                    $nuevo="nuevo";
                    $mensaje="El producto se guardó exitosamente";
                    $estatus=true;
                    $this->session->set_flashdata('message', $mensaje);
                }else{
                    $mensaje="El código suministrado ya pertenece a un producto";
                    $nuevo="";
                    $estatus=false;
                }
               
            }else{                    
                $data['id'] = $form;              
                $existecodigo=$this->productos->existe_codigo_update(array('codigo'=>$txt_codigo,'id !='=>$form));  
                
                if(empty($existecodigo)){                
                    $error=$this->productos->update($data, $this->session->userdata('user_id'));
                    $id_producto=$form;
                    $mensaje="Registro Actualizado";
                    $nuevo="";
                    $estatus=true;
                }else{
                    $mensaje="El código suministrado ya pertenece a un producto";
                    $nuevo="";
                    $estatus=false;
                }
            }
        
           // switch($tipo_producto_id){
                
              //  case 2:
                    $form_two = $this->input->post('ingredientes');
                    $form_two_values = array();
                    parse_str($form_two, $form_two_values);
                    $contador = 0;
                    foreach($form_two_values['producto'] as $key => $campos){
                        if($contador > 0){
                            $ingrediente[] = array(
                                'id_ingrediente' => $campos['id'],
                                'id_producto' => isset($form) ? $form : $id_producto,
                                'cantidad' => $campos['cantidad'],
                            );
                            /* Guardar ingrediente en producto_ingredientes */
                            if((isset($form)&&($id_producto!=0)))
                                $this->productos->deleteIngredientById($form);

                        }
                        $contador++;
                    }
                    if(isset($ingrediente)&&(count($ingrediente))&&($tipo_producto_id!=1)&&($id_producto!=0))
                        $this->productos->addIngredient($ingrediente,1);          
            //    break;
           // }

            if(isset($form_three_values) && is_array($form_three_values)){
                if((isset($form)&&($id_producto!=0)))
                    $this->productos->deleteAdicionalById($form);                 
                
                $counter = 0;  
                $data_adicional = array();  
                foreach($form_three_values['producto'] as $adicionales){
                    if(is_array($adicionales) && $counter > 0){
                        $data_adicional[] = array(
                            'id_producto' => isset($form) ? $form : $id_producto,
                            'id_adicional' => $adicionales['id_producto'],
                            'cantidad' => $adicionales['cantidad'],
                            'precio' => $adicionales['precio']  
                        );
                    }
                    //
                    $counter++;
                }
                if(count($data_adicional)&&($tipo_producto_id!=1)&&($id_producto!=0))
                    $this->productos->addAdicional($data_adicional);
            }

            if(isset($form_four_values) && is_array($form_four_values)){

                if((isset($form)&&($id_producto!=0)))
                    $this->productos->deleteModificacionById($form);           
            
                foreach($form_four_values as $modificacion){
                    if(!empty($modificacion)){
                        $data = array(
                            'id_producto' => isset($form) ? $form : $id_producto,
                            'nombre' => $modificacion
                        );
                        
                        if((!empty($data))&&($tipo_producto_id!=1)&&($id_producto!=0)){
                            $this->productos->addModificacion($data); 
                        }                                          
                    }
                }
            }

        


        //$this->session->set_flashdata('message', 'Registro Actualizado');
        //if ($this->form_validation->run() == false) {
          //  $errors = validation_errors();
            $this->output->set_content_type('application/json')->set_output(json_encode(['nuevo'=>$nuevo,'mensaje' => $mensaje,"estatus" => $estatus]));
        //} else {
          
        //}

        
        
    }

    public function eliminar_Adicion_Ingredientes(){
        //buscamos la orden 
        $producto = $this->input->post('id');
        $tipo = $this->input->post('type');
        $adicional = $this->input->post('adicional');

        if((!empty($producto)) && (!empty($adicional)) && (!empty($tipo))){
            //eliminar la adición
            if($tipo==1){
                $this->productos->delete_Adicion(array('id_producto'=>$producto,'id_adicional'=>$adicional));                
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'mensaje' => 'La adición fue eliminada correctamente')));
            }else{
               if($tipo==2){
                    $this->productos->deleteIngrediente(array('id_producto'=>$producto,'id_ingrediente'=>$adicional));                
                    $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'mensaje' => 'El ingrediente fue eliminado correctamente')));
                } 
            }         
        }
            
    }
    
}


<?php

class Productos extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        //================================================
        
        // Helper for redirect inside codeigniter
        
        $this->load->helper('url');
        
        //================================================

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);


        $this->load->model("clientes_model", 'clientes');
        $this->clientes->initialize($this->dbConnection);

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


        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);

        $this->load->model("marcas_model", 'marcas');
        $this->marcas->initialize($this->dbConnection);

        $this->load->model("proveedores_model", 'proveedores');
        $this->proveedores->initialize($this->dbConnection);
        
        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);
        
        $this->load->model("tipos_materiales_model", 'tipos_materiales');
        $this->tipos_materiales->initialize($this->dbConnection);

        //...........................................................................
        //Modelo unidades =========================================================
        $this->load->model("unidades_model", 'unidades');
        $this->unidades->initialize($this->dbConnection);


        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');



        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    public function index($offset = 0) {

        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $this->layout->template('member')->show('productos/index');
    }   
    
    public function atributos() {
                
        $data = array();
        
        $data['almacenes'] = $this->almacenes->get_all(false);    
        
        $data['impuestos'] = $this->atributos->getImpuestos();                
        $data['categorias'] = $this->atributos->getCategorias(); // Solo categorias que tienen que tienen un atributo asignado
        $data['proveedores'] = $this->atributos->getProveedores();        
        
        $data['marcas'] = $this->atributos->getMarcas();                                       
        $data['tallas'] = $this->atributos->getTallas();
        $data['colores'] = $this->atributos->getColores();
        $data['lineas'] = $this->atributos->getLineas();
        $data['materiales'] = $this->atributos->getMateriales();

        $this->layout->template('member')->show('productos/nuevo_atributos', array('data' => $data));                 
        
    }
    
    public function testnuevo() {
        $id_producto = $this->productos->testnuevo();
        echo $id_producto;
    }
    
    
    public function atributo_nuevo($value = '') {

        //Capture json and delete xtrange chars ][
        
        $dataJson = $this->input->post("dataJson");        
        
        $dataJson = str_replace("[","",$dataJson);
        $dataJson = str_replace("]","",$dataJson);
        
        //echo $dataJson;
        
        //Convert json to object
        $dataObj = json_decode( $dataJson );
        
        $namesObjProducts = Array();
        
        $arrayFromObj = get_object_vars ( $dataObj );        
        foreach( $arrayFromObj as $key => $value ) {
            $namesObjProducts[] = $key;
        }
        

    // If post have a value
        if ( $dataObj->cantidad > 0) {

            
            //Configuration Upload and Image
            $image_name = "";
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
            $config['max_size'] = '2024';
            $config['max_width'] = '200000';
            $config['max_height'] = '2000000';

            $this->load->library('upload', $config);
            
            
            // No borrar!
            $productoN;

            
           
            
            for ($i = 0; $i < $dataObj->cantidad; $i++) {
                
                $prodcutoN = $namesObjProducts[$i];
                                                
                //Si hay una imagen
                if (!empty($_FILES['imagen']['name'])) {
                    if (!$this->upload->do_upload('imagen')) {
                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                    } else {
                        
                        $upload_data = $this->upload->data();
                        $image_name = $upload_data['file_name'];
                    }
                }else{
                    $image_name = 'img/productos/dragDrop.jpg';                    
                }
                
                echo $image_name."<br>";
                
                
                
                exit();
                
                $pos_talla = $i * 2;
                $pos_color = $pos_talla + 1;
                
                
                $idMarca =  $dataObj->$prodcutoN->marca_principal = "0" ? 0 : $dataObj->$prodcutoN->marca_principal;
                $idTalla =  $dataObj->$prodcutoN->talla = "0" ? 0 : $dataObj->$prodcutoN->talla;
                $idColor =  $dataObj->$prodcutoN->color = "0" ? 0 : $dataObj->$prodcutoN->color;
                $idProveedor =  $dataObj->$prodcutoN->proveedor_principal = "0" ? 0 : $dataObj->$prodcutoN->proveedor_principal;
                $idLinea =  $dataObj->$prodcutoN->linea = "0" ? 0 : $dataObj->$prodcutoN->linea;                
                $idMaterial =  $dataObj->$prodcutoN->material = "0" ? 0 : $dataObj->$prodcutoN->material;
                
                
                $attr_marca = $idMarca ? $this->productos->get_attr( $idMarca ) : "";
                $attr_talla = $idTalla ? $this->productos->get_attr( $idTalla ) : "";
                $attr_color = $idColor ? $this->productos->get_attr( $idColor ) : "";                                
                $attr_linea = $idLinea ? $this->productos->get_attr( $idLinea ) : "";                
                $attr_material = $idMaterial ? $this->productos->get_attr( $idMaterial ) : "";
                $attr_proveedor =  $idProveedor ? $this->productos->getClasificacionProveedor( $idProveedor ) : "";
                                                               
             
                $listaAlmacenes =  $dataObj->$prodcutoN->lista_almacenes;                               
                
                $nombre =  $dataObj->$prodcutoN->nombre == " " ? "" : $dataObj->$prodcutoN->nombre;                
                $nombreProveedor =  $dataObj->$prodcutoN->nombre_proveedor == " " ? "" : $dataObj->$prodcutoN->nombre_proveedor;
                $descripcion =  $dataObj->$prodcutoN->descripcion == " " ? "" : $dataObj->$prodcutoN->descripcion;                
                $pc =  $dataObj->$prodcutoN->precio_compra == " " ? 0 :  $dataObj->$prodcutoN->precio_compra;
                $pv = $dataObj->$prodcutoN->precio_venta == " " ? 0 : $dataObj->$prodcutoN->precio_venta;
                $codigo = $dataObj->$prodcutoN->codigo == " " ? 0 :  $dataObj->$prodcutoN->codigo;
                $categoria_atributo = $dataObj->$prodcutoN->categoria_atributos = "0" ? 0 : $dataObj->$prodcutoN->categoria_atributos;
                $impuesto = $dataObj->$prodcutoN->impuesto = "0" ? 0 : $dataObj->$prodcutoN->impuesto;                                
                $tienda = $dataObj->$prodcutoN->tienda;
                $active =  $dataObj->$prodcutoN->activo;
                $material = $attr_material;
                $name = $nombre . "/" . $attr_marca . "/" . $attr_talla . "/" . $attr_color;                
                
                
                
                //Datos para la nueva tabla atributos_prodcuto
                //Capturamos desde db el nombre de la categoria
                $nombreCatgoria = $this->productos->getAtributoCategoria( $categoria_atributo );
                
                $nombreProveedorClasificacion = $idProveedor ?  $this->productos->getClasificacionProveedor( $idProveedor ) : "";
                $nombreMarcaClasificacion = $idMarca ? $this->productos->getClasificacionMarca( $idMarca ) : "";
                                 
                
                //=========================================================================================
                //=========================================================================================
                //=========================================================================================
                
                //ARRAY DE ATRIBUTOS DISPONIBLES
                //Este array deberia ser dinamico
                $arrayAtributos = Array();

                // Atributo Marca
                if($idMarca){
                    $arrayAtributos[]= Array(
                        "id_atributo" => 1 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 1 ),
                        "id_clasificacion" => $idMarca,
                        'nombre_clasificacion' => $nombreMarcaClasificacion
                    );
                }

                // Atributo Proveedor
                if($idProveedor){
                    $arrayAtributos[]= Array(
                        "id_atributo" => 2 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 2 ),
                        "id_clasificacion" => $idProveedor,
                        'nombre_clasificacion' => $nombreProveedorClasificacion
                    );
                }                
                
                
                // Atributo Color
                if($idColor){
                    $arrayAtributos[]= Array(
                        "id_atributo" => 3 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 3 ),
                        "id_clasificacion" => $idColor,
                        'nombre_clasificacion' => $attr_color
                    );
                }
                

                // Atributo Talla
                if($idTalla){                
                    $arrayAtributos[]= Array(
                        "id_atributo" => 4 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 4 ),
                        "id_clasificacion" => $idTalla,
                        'nombre_clasificacion' => $attr_talla
                    );
                }

                // Atributo Linea
                if($idLinea){                
                    $arrayAtributos[]= Array(
                        "id_atributo" => 5 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 5 ),
                        "id_clasificacion" => $idLinea,
                        'nombre_clasificacion' => $attr_linea
                    );
                }

                // Atributo Material
                if($idMaterial){                
                    $arrayAtributos[]= Array(
                        "id_atributo" => 6 ,
                        'nombre_atributo' => $this->productos->getNombreAtributo( 6 ),
                        "id_clasificacion" => $idMaterial,
                        'nombre_clasificacion' => $attr_material
                    );
                }                
                
                // Si no se selecciono ningun atributo
                if(!count($arrayAtributos)){
                    
                    redirect('productos/atributos', 'refresh'); 
                    
                }
                
                
                //=========================================================================================
                //=========================================================================================
                //=========================================================================================


                
                //-----------------------------------
                // Añadimos los anteriores atributos
                // Array for table [atributos_productos]
                $dataAttributoProducto = array(
                    "codigo_interno" => intval( $this->productos->getIdPrductoAtributos() ) + 1,
                    "nombre_producto" => $nombre,
                    "codigo_barras" => $codigo,
                    "id_categoria" => $categoria_atributo, // Categoria pero de producto atributo
                    "nombre_categoria" => $nombreCatgoria,
                    "atributos" => $arrayAtributos
                );
                
                //=========================================================================================
                // Array for table [producto]
                $dataProducto = array(
                    'imagen' => $image_name,
                    "nombre" => $name,
                    "codigo_barra" => $codigo,
                    "descripcion" => $descripcion,
                    "precio_venta" => $pv,
                    "precio_compra" => $pc,                                       
                    "categoria_id" => $categoria_atributo,
                    "impuesto" => $impuesto,
                    'activo' => $active,
                    "tienda" => $tienda
                );
                
                //Arreglar, hay que poner las cantidades segun los almacenes
                // Array for table [almacen]
                $cantidadAlmacen = array(
                    'lista_almacenes' => $listaAlmacenes
                );

                
                $id_producto = $this->productos->add_producto_attr($dataProducto, $cantidadAlmacen, $dataAttributoProducto);
                              
                /*
                    echo "imagen: ".$dataProducto['imagen']."<br>";
                    echo "nombre: ".$dataProducto["nombre"]."<br>";
                    echo "codigo: ".$dataProducto["codigo_barra"]."<br>";
                    echo "descripcion: ".$dataProducto["descripcion"]."<br>";
                    echo "precio venta: ".$dataProducto["precio_venta"]."<br>";
                    echo "precio compra: ".$dataProducto["precio_compra"]."<br>";
                    echo "activo: ".$dataProducto['activo']."<br>";
                    echo "tienda: ".$dataProducto["tienda"]."<br>";                                                                            
                    echo "categoria_id: ".$dataProducto["categoria_id"]."<br>";
                    echo "impuesto: ".$dataProducto["impuesto"]."<br>";                    
                */                      
                
            }
        }
        
        // For testing purposes
        /*
        $vars = get_object_vars ( $dataObj->producto1 );        
        foreach( $vars as $key=>$value ) {
            echo $key." -> ".$value."<br>";
        }
         
         */
        
        redirect('productos/', 'refresh');        
        
    }    

    public function atributo_nuevo2($value = '') { 

    // If post have a value
        if (isset($_POST) && count($_POST) > 0) {
            /* print_r(count($_POST['marca']).">>>>");
              echo print_r($_POST, 1);
              exit; */
            
            //Configuration Upload and Image
            $image_name = "";
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';
            $config['max_size'] = '2024';
            $config['max_width'] = '200000';
            $config['max_height'] = '2000000';

            $this->load->library('upload', $config);
            
            
            for ($i = 0; $i < count($_POST['marca']); $i++) {

                if (!empty($_FILES['imagenes']['name'][$i])) {
                    if (!$this->upload->do_upload('imagenes')) {
                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                    } else {
                        $upload_data = $this->upload->data();
                        $image_name = $upload_data['file_name'];
                    }
                }
                
                $pos_talla = $i * 2;
                $pos_color = $pos_talla + 1;
                $attr_marca = $this->productos->get_marca($_POST['marca'][$i]);
                $attr_talla = $this->productos->get_attr($_POST['atributo'][$pos_talla]);
                $attr_color = $this->productos->get_attr($_POST['atributo'][$pos_color]);
                $name = $_POST['nombre'] . "/" . $attr_marca . "/" . $attr_talla . "/" . $attr_color;
                $active = isset($_POST['activo'][$i]) ? 1 : 0;
                $pc = isset($_POST['precio_compra'][$i]) ? $_POST['precio_compra'][$i] : 0;
                $codigo = isset($_POST['codigo'][$i]) ? $_POST['codigo'][$i] : 0;
                $cantidad = isset($_POST['cantidad'][$i]) ? $_POST['cantidad'][$i] : 0;
                $pv = isset($_POST['precio_venta'][$i]) ? $_POST['precio_venta'][$i] : 0;
                $almacen = isset($_POST['almacenes_nombre'][$i]) ? $_POST['almacenes_nombre'][$i] : "General";
                $tienda = isset($_POST['tienda'][$i]) ? 1 : 0;
                $material = 0;
                
                $data = array(
                    'imagen' => $image_name,
                    "nombre" => $name,
                    "codigo" => $codigo,
                    "descripcion" => $name,
                    "precio_venta" => $pv,
                    "precio_compra" => $pc,
                    "categoria_id" => 2,
                    "impuesto" => 1,
                    'activo' => $active,
                    "tienda" => $tienda
                );
                $data2 = array(
                    'almacen' => $almacen,
                    'cantidad' => $cantidad
                );

                //$id_producto = $this->productos->add_producto_attr($data, $data2);
                
                
                    echo "imagen: ".$data['imagen']."<br>";
                    echo "nombre: ".$data["nombre"]."<br>";
                    echo "codigo: ".$data["codigo"]."<br>";
                    echo "descripcion: ".$data["descripcion"]."<br>";
                    echo "precio venta: ".$data["precio_venta"]."<br>";
                    echo "precio compra: ".$data["precio_compra"]."<br>";
                    echo "categoria_id: ".$data["categoria_id"]."<br>";
                    echo "impuesto: ".$data["impuesto"]."<br>";
                    echo "activo: ".$data['activo']."<br>";
                    echo "tienda: ".$data["tienda"]."<br>";                

                    echo "almacen: ".$data2['almacen']."<br>";
                    echo "cantidad: ".$data2['cantidad']."<br><br><br>";
                
                
                
            }
        }
        
        //redirect('productos/atributos', 'refresh');        
        
    }        
    
    public function nuevo() {

        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        $error_upload = "";

        if ($this->form_validation->run('productos') == true) {

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

            $config['max_size'] = '2024';

            $config['max_width'] = '200000';

            $config['max_height'] = '2000000';

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

            if ($_POST['is_ingrediente'] != 1)
                $material = 0;
            else
                $material = 1;

            $data = array(
                'imagen' => $image_name,
                "nombre" => $this->input->post('nombre'),
                "codigo" => $this->input->post('codigo'),
                "descripcion" => $this->input->post('descripcion'),
                "precio_venta" => $this->input->post('precio'),
                "precio_compra" => $this->input->post('precio_compra'),
                "categoria_id" => $this->input->post('categoria_id'),
                "impuesto" => $this->input->post('id_impuesto'),
                'activo' => $active,
                'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
                'stock_minimo' => $this->input->post('stock_minimo'),
                'stock_maximo' => $this->input->post('stock_maximo'),
                'ubicacion' => $this->input->post('ubicacion'),
                'ganancia' => $this->input->post('ganancia'),
                "tienda" => $this->input->post('tienda'),
                "muestraexist" => $this->input->post('muestraexist')
            );

            /* Guardar producto */
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
                        if ($key == 'id') {
                            foreach ($value as $key2 => $id_ingrediente) {
                                if ($id_ingrediente != '' && $id_ingrediente != 0) {
                                    /* Ingrediente */
                                    $ingrediente = array(
                                        'id_ingrediente' => $id_ingrediente,
                                        'id_producto' => $id_producto,
                                        'cantidad' => $ingredientes['cantidad'][$key2]
                                    );
                                    /* Guardar ingrediente en producto_ingredientes */
                                    $this->productos->addIngredient($ingrediente);
                                    $withIngredients = true;
                                }
                            }
                        }
                    }

                    /* Cambiar estado  (ingrediente = 1 -> tiene ingredientes) al producto */
                    if ($withIngredients)
                        $this->productos->withIngredients($id_producto);

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
                                        'cantidad' => $productos_combo['cantidad'][$key2]
                                    );
                                    /* Guardar ingrediente en producto_ingredientes */
                                    $this->productos->addProductCombo($producto_combo);
                                    $isCombo = true;
                                }
                            }
                        }
                    }

                    if ($isCombo)
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
        $data['categorias'] = $this->categorias->get_combo_data();
    }

    public function nuevo_rapido() {

        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        if ($this->form_validation->run('productos') == true) {

            $config['upload_path'] = 'uploads/';

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
                'material' => 0
            );


            /* Guardar producto */
            $id_producto = $this->productos->add($data, $this->session->userdata('user_id'));
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
        }
    }

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

        $result = array();

        $filter = $this->input->post('filter', TRUE);

        if (!empty($filter)) {

            $type = $this->input->post('type');

            if ($type == 'codificalo') {

                $productos = $this->productos->get_by_codigo($filter, $this->session->userdata('user_id'));
            } else {

                $cliente = $this->input->post('cliente', TRUE);
                $this->productos->initialize($this->dbConnection);
                $productos = $this->productos->get_term($filter, $this->session->userdata('user_id'));

                if (!empty($cliente)) {
                    //Cliente esta en grupo?
                    if ($_POST['grupo'] != 1) {
                        //Grupo esta en una lista?/
                        $this->lista_precios->initialize($this->dbConnection);
                        $lista = $this->lista_precios->get_by_id($_POST['grupo']); //Lee si un grupo esta en una lista

                        if (!empty($lista)) {

                            foreach ($productos as $key => $value) {
                                foreach ($value as $key2 => $value2) {
                                    if ($key2 == 'id') {
                                        //Si el producto esta en una lista de detalle?/
                                        $this->lista_detalle_precios->initialize($this->dbConnection);
                                        $detalle = $this->lista_detalle_precios->get($lista['id'], $value2); //Lee una lista esta en un grupo
                                        /* Asigna nuevo precio */
                                        if (!empty($detalle))
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

    public function get_by_category($category_id) {
        $productos = $this->productos->get_by_category($category_id, $this->session->userdata('user_id'));
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

    public function editar($id) {

        $error_upload = "";

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('productos') == true) {

            $config['upload_path'] = 'uploads/';

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
            $ingrediente = isset($_POST['is_ingrediente']) ? 1 : 0;

            $data = array(
                'id' => $this->input->post('id'),
                "nombre" => $this->input->post('nombre'),
                "codigo" => $this->input->post('codigo'),
                "descripcion" => $this->input->post('descripcion'),
                "precio_venta" => $this->input->post('precio'),
                "precio_compra" => $this->input->post('precio_compra'),
                "categoria_id" => $this->input->post('categoria_id'),
                "unidad_id" => $this->input->post('id_unidades'),
                "impuesto" => $this->input->post('id_impuesto'),
                'activo' => $active,
                'material' => $ingrediente,
                'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
                'stock_minimo' => $this->input->post('stock_minimo'),
                'stock_maximo' => $this->input->post('stock_maximo'),
                'ubicacion' => $this->input->post('ubicacion'),
                'ganancia' => $this->input->post('ganancia'),
                "tienda" => $this->input->post('tienda'),
                "muestraexist" => $this->input->post('muestraexist')
            );


            if (!empty($image_name)) {

                $data['imagen'] = $image_name;
            }



            if ($error_upload == "") {

                $this->productos->update($data, $this->session->userdata('user_id'));

                $id_producto = $data['id'];

                //Ingredientes ================================================

                $ingredientes = $_POST['Ingrediente'];

                $this->productos->delete_ingredientes($id_producto);

                $withIngredients = false; // bandera para productos con ingredientes
                foreach ($ingredientes as $key => $value) {
                    if ($key == 'id') {
                        foreach ($value as $key2 => $id_ingrediente) {
                            if ($id_ingrediente != '' && $id_ingrediente != 0) {
                                /* Ingrediente */
                                $ingrediente = array(
                                    'id_ingrediente' => $id_ingrediente,
                                    'id_producto' => $id_producto,
                                    'cantidad' => $ingredientes['cantidad'][$key2]
                                );
                                /* Guardar ingrediente en producto_ingredientes */
                                $this->productos->addIngredient($ingrediente);
                                $withIngredients = true;
                            }
                        }
                    }
                }

                /* Cambiar estado  (ingrediente = 1 -> tiene ingredientes) al producto */
                if ($withIngredients)
                    $this->productos->withIngredients($id_producto);

                //Productos ================================================

                $isCombo = false; // bandera para combos

                $this->productos->delete_productos_combo($id_producto);

                $productos_combo = $_POST['productosCombo'];


                foreach ($productos_combo as $key => $value) {
                    if ($key == 'id') {
                        foreach ($value as $key2 => $id_producto_combo) {
                            if ($id_producto_combo != '' && $id_producto_combo != 0) {
                                /* Ingrediente */
                                $producto_combo = array(
                                    'id_combo' => $id_producto,
                                    'id_producto' => $id_producto_combo,
                                    'cantidad' => $productos_combo['cantidad'][$key2]
                                );
                                /* Guardar ingrediente en producto_ingredientes */
                                $this->productos->addProductCombo($producto_combo);
                                $isCombo = true;
                            }
                        }
                    }
                }

                if ($isCombo)
                    $this->productos->isCombo($id_producto);


                $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto actualizado correctamente'));

                redirect('productos/index');
            }
        }



        $data = array();

        $data['data'] = $this->productos->get_by_id($id);

        if ($data['data']['ingredientes'] == 1) {
            $data['ingredientes'] = $this->productos->get_ingredientes($id);
        } else {
            $data['ingredientes'] = array();
        }

        if ($data['data']['material'] == 1) {
            $data['material'] = 1;
        } else {
            $data['material'] = 0;
        }

        if ($data['data']['combo'] == 1) {
            $data['productos_combo'] = $this->productos->get_productos_combo($id);
        } else {
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

        $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));

        redirect("productos");
    }

    public function filtro_prod_existencia() {

        $type = $this->input->get('almacen');

        $filter = $this->input->get('term', TRUE);



        $result = $this->productos->get_term_existencias($filter, $type);

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



        $query = $this->productos->excel();

        /* echo "<pre>";

          print_r($query);

          echo "</pre>";

          die; */

        $row = 2;

        foreach ($query as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->codigo);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->nombre);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->descripcion);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->precio_compra);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->precio_venta);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->nombre_impuesto);

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
                            "almacen" => $value['K']
                        );
                        list($res_oper, $msj) = $this->productos->add_csv($data, $this->session->userdata('user_id'));
                        if ($res_oper === FALSE) {
                            $datos_fallo[] = array($data, $msj);
                        }
                    }
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
                    $styleThinBlackBorderOutline = array(
                        'borders' => array(
                            'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => 'FF000000'),
                            ),
                        ),
                    );
                    $Hoja_Productos->getActiveSheet()->getStyle('A1:K1' . --$row)->applyFromArray($styleThinBlackBorderOutline);
                    $Hoja_Productos->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
                            array(
                                'font' => array('bold' => true),
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
                                    'startcolor' => array('argb' => 'FFA0A0A0'),
                                    'endcolor' => array('argb' => 'FFFFFFFF')
                                )
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

}

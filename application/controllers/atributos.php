<?php

class Atributos extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        //=================================================================

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        //=================================================================

        $this->load->library('pagination');

        //=================================================================

        $this->load->model("almacenes_model", 'almacenesModel');
        $this->almacenesModel->initialize($this->dbConnection);

        $this->load->model("atributos_model", 'atributosModel');
        $this->atributosModel->initialize($this->dbConnection);

        //=================================================================



        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->library('Encryption');
    }

    //=====================================================================================
    //
    //          ADMINISTRACION DE PRODUCTOS CON ATRIBUTOS
    //  
    //=====================================================================================
    //----------------------
    // INDEX  - PANEL DE ADMINISTRACION DE ATRIBUTOS
    //----------------------
    public function index($offset = 0) {

        $data['atributos'] = $this->atributosModel->get_data();
        $data['atributosN'] = $this->atributosModel->getAtributos();
        $data['categorias'] = $this->atributosModel->getAllCategorias();

        $this->layout->template('member')->show('atributos/atributos', array('data' => $data));
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------
    //----------------------
    // TAB 1
    //----------------------    
    public function getAjaxCategoriasSeleccionadas($id) {
        $result = $this->atributosModel->ajaxSeleccionados($id);
        $string = "";
        foreach ($result as $key => $val) {
            $string = $string . "" . $val->atributo_id . ",";
        }
        $string = rtrim($string, ",");
        $this->output->set_output($string);
    }

    public function setAjaxRelacionarCategorias() {

        $data = array(
            'idCategoria' => $this->input->post('categoriaSeleccionada'),
            'atributos' => $this->input->post('atributos')
        );
        $this->atributosModel->atributosToCategoria($data);

        //redirect('atribut/nuevo');        
        $this->output->set_output("ok");
    }

    //----------------------
    // TAB 2
    //----------------------     
    public function setAjaxAtributosManage() {

        $data = array(
            'tipo' => $this->input->post('tipo'),
            'id' => $this->input->post('id'),
            'valor' => $this->input->post('valor')
        );

        if ($data["tipo"] == "add") {
            // Retorna  el ID del 
            $result = $this->atributosModel->ajaxAtributosAdd($data["valor"]);
        }

        if ($data["tipo"] == "del") {
            //Eliminamos
            $result = $this->atributosModel->ajaxAtributosDel($data);
        }

        if ($data["tipo"] == "set") {
            // Modificamos el valor 
            $result = $this->atributosModel->ajaxAtributosSet($data);
        }

        $this->output->set_output($result);
    }

    public function getAjaxAtributos() {

        $result = $this->atributosModel->ajaxAtributos();
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    //----------------------
    //   TAB 3
    //----------------------

    public function getAjaxClasificacion($id) {
        $result = $this->atributosModel->ajaxClasificacion($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function setAjaxClasificacionManage() {

        $data = array(
            'tipo' => $this->input->post('tipo'),
            'id' => $this->input->post('id'),
            'idAtr' => $this->input->post('idAtr'),
            'valor' => $this->input->post('valor')
        );

        if ($data["tipo"] == "add") {
            // Retorna  el ID del 
            $result = $this->atributosModel->ajaxClasificacionAdd($data["valor"], $data["idAtr"]);
        }

        if ($data["tipo"] == "del") {
            //Eliminamos
            $result = $this->atributosModel->ajaxClasificacionDel($data);
        }

        if ($data["tipo"] == "set") {
            // Modificamos el valor             
            $result = $this->atributosModel->ajaxClasificacionSet($data);
        }

        $this->output->set_output($result);
    }



    //=====================================================================================
    //
    //          EXCEL DE PRODUCTOS CON ATRIBUTOS
    //  
    //=====================================================================================
    //----------------------
    // VISTA EXCEL
    //----------------------
    public function excel() {
        $this->layout->template('member')->show('atributos/atributosExcel');
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------
    public function getAjaxAtributosExcel() {
        $result = $this->atributosModel->ajaxAtributosExcel();
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }    
    
    public function setAjaxAtributosExcel() {
        $jsonString = $this->input->post("data");
        $dataObj = json_decode($jsonString);
        $dataObj = $dataObj->listaUnidades;
        
        $this->atributosModel->setAjaxAtributosExcel( $dataObj );
        
    }    
    
    
    //=====================================================================================
    //
    //          INFROMES DE PRODUCTOS CON ATRIBUTOS
    //  
    //=====================================================================================
    //----------------------
    // VISTA INFORMES
    //----------------------
    public function informes() {

        $data = array();

        $data['marcas'] = $this->atributosModel->ajaxClasificacion(1);
        $data['proveedores'] = $this->atributosModel->ajaxClasificacion(2);
        $data['colores'] = $this->atributosModel->ajaxClasificacion(3);
        $data['tallas'] = $this->atributosModel->ajaxClasificacion(4);
        $data['lineas'] = $this->atributosModel->ajaxClasificacion(5);
        $data['materiales'] = $this->atributosModel->ajaxClasificacion(6);
        $data['tipos'] = $this->atributosModel->ajaxClasificacion(7);

        $data['categorias'] = $this->atributosModel->ajaxCategorias();
        $data['almacenes'] = $this->atributosModel->ajaxAlmacenes();

        $this->layout->template('member')->show('atributos/atributosInforme', array('data' => $data));
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    public function qPivote() {

        $idAtributos = $this->input->post("str");

        $arrayData = explode(",", $idAtributos);

        $data = array();
        $data['marca'] = $arrayData[0];
        $data['color'] = $arrayData[1];
        $data['talla'] = $arrayData[2];
        $data['proveedor'] = $arrayData[3];
        $data['material'] = $arrayData[4];
        $data['linea'] = $arrayData[5];
        $data['tipo'] = $arrayData[6];
        $data['almacen'] = $arrayData[7];
        $data['categoria'] = $arrayData[8];

        $result = $this->atributosModel->queryPivote($data);

        // Luego de obtener los resultados       

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    
    
    //=====================================================================================
    //
    //  CREACION DE PRODUCTOS CON ATRIBUTOS
    //  
    //=====================================================================================
    //----------------------
    // VISTA PANEL DE CREACION DE PRODUCTOS
    //----------------------
    public function productos() {

        $data = array();
        $atributos_detalle = [];

        $atributos = $this->atributosModel->getAtributos();
        foreach ($atributos as $atributo) 
        {
            $detalles = $this->atributosModel->getDetalleAtributos($atributo->id);
            $categorias = $this->atributosModel->getCategoriasAtributo($atributo->id);

            array_push($atributos_detalle, [
                'id' => $atributo->id,
                'nombre' => $atributo->nombre,
                'detalles' => $detalles,
                'categorias' => $categorias
            ]);
        }

        $data['almacenes'] = $this->almacenesModel->get_all(false);

        $data['impuestos'] = $this->atributosModel->getImpuestos();
        $data['categorias'] = $this->atributosModel->getCategorias(); // Solo categorias que tienen que tienen un atributo asignado
        $data['atributos'] = $atributos_detalle;
        $this->layout->template('member')->show('atributos/atributosProductos', array('data' => $data));
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    
    public function getAjaxProductoExiste(){
        
        $atributos = $this->input->post("atributos");
        $result = $this->atributosModel->getAjaxProductoExiste($atributos);
        $this->output->set_output($result);
        
    }
    
    public function setProductoNuevo(){


        //Capture json and delete xtrange chars ][        
        $dataJson = $this->input->post("dataJson");

        $dataJson = str_replace("[", "", $dataJson);
        $dataJson = str_replace("]", "", $dataJson);

        //echo $dataJson;
        //Convert json to object
        $dataObj = json_decode($dataJson);   

        $namesObjProducts = Array();
        $arrayFromObj = get_object_vars($dataObj);
        foreach ($arrayFromObj as $key => $value) {
            $namesObjProducts[] = $key;
        }


        // If post have a value
        if ($dataObj->cantidad > 0) {

            
            //=============================================
            //Configuration IMAGE and Upload
            //=============================================

            // Array asociativo de las imagenes, para almacenear el nombre resultante en caso
            // de que un imagen esté reptida
            $listaResultadoImagenes = Array();

            // La imagen por defecto
            $listaResultadoImagenes["dragDrop.jpg"] = "dragDrop.jpg";
            $image_name = "";
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
            $config['max_size'] = '2024';
            $config['max_width'] = '200000';
            $config['max_height'] = '2000000';

            $this->load->library('upload', $config);

            for ($img = 0; $img < 6; $img++) {
                if (!empty($_FILES['imagenes'.$img]['name'])) {
                    $cantidadImagenes = count($_FILES['imagenes'.$img]['name']);

                    for ($i = 0; $i < $cantidadImagenes; $i++) {
                        $_FILES['tmpImg']['name'] = $_FILES['imagenes'.$img]['name'][$i];
                        $_FILES['tmpImg']['type'] = $_FILES['imagenes'.$img]['type'][$i];
                        $_FILES['tmpImg']['tmp_name'] = $_FILES['imagenes'.$img]['tmp_name'][$i];
                        $_FILES['tmpImg']['error'] = $_FILES['imagenes'.$img]['error'][$i];
                        $_FILES['tmpImg']['size'] = $_FILES['imagenes'.$img]['size'][$i];
                        if (!$this->upload->do_upload( 'tmpImg' )) {
                            echo $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                        } else {
                            $upload_data = $this->upload->data();
                            $image_name = $upload_data['file_name'];
                            $listaResultadoImagenes[ $_FILES['tmpImg']['name'] ] = $image_name;
                        }

                    }
                }

            }


            
            //=================================================
            //      FIN IMAGE UPLOAD
            //=================================================
                    



            // No borrar!
            $productoN;

            for ($i = 0; $i < $dataObj->cantidad; $i++) {

                $prodcutoN = $namesObjProducts[$i];
                $pos_talla = $i * 2;
                $pos_color = $pos_talla + 1;
                $product_array_data = json_decode(json_encode($dataObj->$prodcutoN), true);

                $imagenPost[0] = $dataObj->$prodcutoN->imagen0;
                $imagenPost[1] = $dataObj->$prodcutoN->imagen1;
                $imagenPost[2] = $dataObj->$prodcutoN->imagen2;
                $imagenPost[3] = $dataObj->$prodcutoN->imagen3;
                $imagenPost[4] = $dataObj->$prodcutoN->imagen4;
                $imagenPost[5] = $dataObj->$prodcutoN->imagen5;
                
                /*$idMarca = $dataObj->$prodcutoN->marca_principal = "0" ? 0 : $dataObj->$prodcutoN->marca_principal;
                $idTalla = $dataObj->$prodcutoN->talla = "0" ? 0 : $dataObj->$prodcutoN->talla;
                $idColor = $dataObj->$prodcutoN->color = "0" ? 0 : $dataObj->$prodcutoN->color;
                $idProveedor = $dataObj->$prodcutoN->proveedor_principal = "0" ? 0 : $dataObj->$prodcutoN->proveedor_principal;
                $idLinea = $dataObj->$prodcutoN->linea = "0" ? 0 : $dataObj->$prodcutoN->linea;
                $idMaterial = $dataObj->$prodcutoN->material = "0" ? 0 : $dataObj->$prodcutoN->material;
                $idTipo = $dataObj->$prodcutoN->tipo = "0" ? 0 : $dataObj->$prodcutoN->tipo;*/

                /*$attr_marca = $idMarca ? $this->atributosModel->get_attr($idMarca) : "";
                $attr_talla = $idTalla ? $this->atributosModel->get_attr($idTalla) : "";
                $attr_color = $idColor ? $this->atributosModel->get_attr($idColor) : "";
                $attr_linea = $idLinea ? $this->atributosModel->get_attr($idLinea) : "";
                $attr_material = $idMaterial ? $this->atributosModel->get_attr($idMaterial) : "";
                $attr_proveedor = $idProveedor ? $this->atributosModel->getClasificacionProveedor($idProveedor) : "";
                $attr_tipo = $idTipo ? $this->atributosModel->get_attr($idTipo) : "";*/

                $listaAlmacenes = $dataObj->$prodcutoN->lista_almacenes;

                $referencia = $dataObj->$prodcutoN->referencia == " " ? "" : $dataObj->$prodcutoN->referencia;
                $nombre = $dataObj->$prodcutoN->nombre == " " ? "" : $dataObj->$prodcutoN->nombre;
                $nombreProveedor = $dataObj->$prodcutoN->nombre_proveedor == " " ? "" : $dataObj->$prodcutoN->nombre_proveedor;
                $descripcion = $dataObj->$prodcutoN->descripcion == " " ? "" : $dataObj->$prodcutoN->descripcion;
                $pc = $dataObj->$prodcutoN->precio_compra == " " ? 0 : $dataObj->$prodcutoN->precio_compra;
                $pv = $dataObj->$prodcutoN->precio_venta == " " ? 0 : $dataObj->$prodcutoN->precio_venta;
                // Este ajuste es para Hardcore silver, solo a este usuario se le agrega un espacio mas al codigo
                if($this->session->userdata('base_dato') == 'vendty2_db_4339_isalo2016' || 
                        $this->session->userdata('base_dato') == 'vendty2_db_5870_hardc2016' || 
                        $this->session->userdata('base_dato') == 'vendty2_db_2086_camap2016' || 
                        $this->session->userdata('base_dato') == 'vendty2_db_5086_hardc2016' || 
                        $this->session->userdata('base_dato') == 'vendty2_db_4954_Hardc2016' || 
                        $this->session->userdata('base_dato') == 'vendty2_db_563221c7afa8d'  || 
                        $this->session->userdata('base_dato') == 'vendty2_db_7448_Hardc2017'){
                    $codigo = substr($dataObj->$prodcutoN->codigo == " " ? strtoupper(md5(microtime())) : $dataObj->$prodcutoN->codigo,0,16); 
                }else{
                    $codigo = substr($dataObj->$prodcutoN->codigo == " " ? strtoupper(md5(microtime())) : $dataObj->$prodcutoN->codigo,0,15);
                }
                $categoria_atributo = $dataObj->$prodcutoN->categoria_atributos = "0" ? 0 : $dataObj->$prodcutoN->categoria_atributos;
                $impuesto = $dataObj->$prodcutoN->impuesto = "0" ? 0 : $dataObj->$prodcutoN->impuesto;
                $tienda = $dataObj->$prodcutoN->tienda;
                $active = $dataObj->$prodcutoN->activo;
                //$material = $attr_material;
                
                
                
                //$name = $nombre . "/" . $attr_marca . "/" . $attr_talla . "/" . $attr_color . "/" . $attr_material . "/" . $attr_proveedor . "/" . $attr_linea;
                $name = $dataObj->$prodcutoN->nombreString;
                //$atributosAsignados = $idMarca . "/" . $idTalla . "/" . $idColor . "/" . $idMaterial . "/" . $idProveedor . "/" . $idLinea. "/" . $idTipo;
                


                //Datos para la nueva tabla atributos_prodcuto
                //Capturamos desde db el nombre de la categoria
                $nombreCatgoria = $this->atributosModel->getAtributoCategoria($categoria_atributo);

                //$nombreProveedorClasificacion = $idProveedor ? $this->atributosModel->getClasificacionProveedor($idProveedor) : "";
                //$nombreMarcaClasificacion = $idMarca ? $this->atributosModel->getClasificacionMarca($idMarca) : "";


                //=========================================================================================
                //=========================================================================================
                //=========================================================================================
                //ARRAY DE ATRIBUTOS DISPONIBLES
                //Este array deberia ser dinamico
                $arrayAtributos = Array();

                /*
                // Atributo Marca
                if ($idMarca) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 1,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(1),
                        "id_clasificacion" => $idMarca,
                        'nombre_clasificacion' => $nombreMarcaClasificacion
                    );
                }

                // Atributo Proveedor
                if ($idProveedor) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 2,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(2),
                        "id_clasificacion" => $idProveedor,
                        'nombre_clasificacion' => $nombreProveedorClasificacion
                    );
                }


                // Atributo Color
                if ($idColor) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 3,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(3),
                        "id_clasificacion" => $idColor,
                        'nombre_clasificacion' => $attr_color
                    );
                }

                // Atributo Talla
                if ($idTalla) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 4,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(4),
                        "id_clasificacion" => $idTalla,
                        'nombre_clasificacion' => $attr_talla
                    );
                }

                // Atributo Linea
                if ($idLinea) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 5,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(5),
                        "id_clasificacion" => $idLinea,
                        'nombre_clasificacion' => $attr_linea
                    );
                }

                // Atributo Material
                if ($idMaterial) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 6,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(6),
                        "id_clasificacion" => $idMaterial,
                        'nombre_clasificacion' => $attr_material
                    );
                }

                // Atributo Material
                if ($idTipo) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 7,
                        'nombre_atributo' => $this->atributosModel->getNombreAtributo(7),
                        "id_clasificacion" => $idTipo,
                        'nombre_clasificacion' => $attr_tipo
                    );
                }
                */   

                //Implementación dinamica 

                $id_atributos = explode(',', $dataObj->$prodcutoN->attrid);
                foreach ($id_atributos as $id) 
                {
                    $clasificacion = $this->atributosModel->get_attr($product_array_data['atributo_'.$id]);
                    if($product_array_data['atributo_'.$id] != '0')
                    {
                        array_push($arrayAtributos, [
                            'id_atributo' => $id,
                            'nombre_atributo' => $this->atributosModel->getNombreAtributo($id),
                            'id_clasificacion' => $product_array_data['atributo_'.$id],
                            'nombre_clasificacion' => $clasificacion
                        ]);
                    }
                }

                // Si no se selecciono ningun atributo
                if (!count($arrayAtributos)) 
                {
                    redirect('productos/atributos', 'refresh');
                }

                //=========================================================================================
                //-----------------------------------
                // Añadimos los anteriores atributos
                // Array for table [atributos_productos]
                $dataAttributoProducto = array(
                    "codigo_interno" => intval($this->atributosModel->getIdPrductoAtributos()) + 1,
                    "referencia_producto" => $referencia,
                    "nombre_producto" => $nombre,
                    "codigo_barras" => $codigo,
                    "id_categoria" => $categoria_atributo, // Categoria pero de producto atributo
                    "nombre_categoria" => $nombreCatgoria,
                    "atributos" => $arrayAtributos
                );

                //=========================================================================================
                // Array for table [producto]
                $dataProducto = array(
                    'imagen'    => $listaResultadoImagenes[$imagenPost[0]],
                    'imagen1'   => $listaResultadoImagenes[$imagenPost[1]],
                    'imagen2'   => $listaResultadoImagenes[$imagenPost[2]],
                    'imagen3'   => $listaResultadoImagenes[$imagenPost[3]],
                    'imagen4'   => $listaResultadoImagenes[$imagenPost[4]],
                    'imagen5'   => $listaResultadoImagenes[$imagenPost[5]],
                    "nombre"    => $name,
                    //"atributos" => $atributosAsignados,
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
                
                $id_producto = $this->atributosModel->setAddProductoAttr($dataProducto, $cantidadAlmacen, $dataAttributoProducto);
            }
        }

        redirect('productos/', 'refresh');
        
    }

    //=====================================================================================
    //
    //  EDITAR  PRODUCTOS CON ATRIBUTOS
    //  
    //=====================================================================================
    //----------------------
    // VISTA PANEL DE EDICION DE PRODUCTOS CON ATRIBUTOS
    //----------------------

    public function editar($idProducto) {

        if (!$idProducto)
            redirect('productos/', 'refresh');

        $this->layout->template('member')->show('atributos/atributosProductosEditar', array('data' => $idProducto));
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    public function setEditarProductoIndividual($idProducto) {

        if (!$idProducto)
            redirect('productos/', 'refresh');




        // Capturamos el String Json
        $dataJson = $this->input->post("dataJson");

        //Quitamos los [], por que sólo recibimos un objeto
        $dataJson = str_replace("[", "", $dataJson);
        $dataJson = str_replace("]", "", $dataJson);

        //Convertimos el string json a un OBJETO PHP
        $dataObj = json_decode($dataJson);



        //Configuration Upload and Image
        $image_name = "";
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
        $config['max_size'] = '2024';
        $config['max_width'] = '200000';
        $config['max_height'] = '2000000';

        $this->load->library('upload', $config);



        // -----------------------------------
        //    IMAGEN
        //
        
        //Si hay una imagen
        if (!empty($_FILES['imagen']['name'])) {

            // Si se subio correctamente
            if (!$this->upload->do_upload('imagen')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {

                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }
        } else {

            $image_name = $dataObj->producto1->imagen;
        }

        $nombreImagen = $image_name;

        //
        //   >>>   FIN IMAGEN
        // -----------------------------------




        $this->atributosModel->setProductoAtributo($dataObj, $idProducto, $nombreImagen);

        redirect('atributos/editar/' . $idProducto, 'refresh');
    }

    public function getAjaxProductosEditar($idProducto) {
        $data = $this->atributosModel->getProductoAtributo($idProducto);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    //=====================================================================================
    //
    //      OTROS
    //  
    //=====================================================================================    

    public function posee_categorias($id = false, $campo = false) {
        echo json_encode($this->atributosModel->posee_categorias2($id, $campo));
    }

    public function get_ajax_data() {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->atributosModel->get_ajax_data()));
    }
}
?>

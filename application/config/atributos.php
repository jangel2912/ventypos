<?php

class Atributos extends CI_Controller {

    var $dbConnection;

    function __construct() {
        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        //=================================================================

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);



        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');



        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function index($offset = 0) {

        $data['atributos'] = $this->atributos->get_data();
        $data['atributosN'] = $this->atributos->getAtributos();
        $data['categorias'] = $this->atributos->getAllCategorias();

        $this->layout->template('member')->show('atributos/atributosEditar', array('data' => $data));
    }
    
    // Solo para redireccionar a informes/productos
    public function informes() {
        redirect('atributosInformes/productos/', 'refresh');               
    }
    //Vista del panel de productos
    public function productos() {

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

        $this->layout->template('member')->show('atributos/atributosProductos', array('data' => $data));
    }

    //Vista del panel de productos
    public function productos_editar($idProducto) {

        if (!$idProducto)
            redirect('productos/', 'refresh');

        $this->layout->template('member')->show('atributos/atributosProductosEditar', array('data' => $idProducto));
    }

    //Edicion de producto individual
    public function editar_producto_individual($idProducto) {

        if (!$idProducto)
            redirect('productos/', 'refresh');


        //Configuration Upload and Image
        $image_name = "";
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
        $config['max_size'] = '2024';
        $config['max_width'] = '200000';
        $config['max_height'] = '2000000';

        $this->load->library('upload', $config);



        //Si hay una imagen

        if (!empty($_FILES['imagen']['name'])) {
            if (!$this->upload->do_upload('imagen')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {

                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }
        } else {

            $image_name = 'img/productos/dragDrop.jpg';
        }

        
        $nombreImagen = $image_name;
        

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
        
        
        $this->atributos->setProductoAtributo($dataObj,$idProducto,$nombreImagen);
        
        redirect('atributos/productos_editar/' . $idProducto, 'refresh');
        
    }

    public function ajax_productos_editar($idProducto) {
        $data = array();
        $data = $this->atributos->getProductoAtributo($idProducto);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function producto_nuevo($value = '') {


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
                /*
                  if (!empty($_FILES['imagen']['name'][0])) {
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
                 */


                $image_name = 'img/productos/dragDrop.jpg';

                $pos_talla = $i * 2;
                $pos_color = $pos_talla + 1;


                $idMarca = $dataObj->$prodcutoN->marca_principal = "0" ? 0 : $dataObj->$prodcutoN->marca_principal;
                $idTalla = $dataObj->$prodcutoN->talla = "0" ? 0 : $dataObj->$prodcutoN->talla;
                $idColor = $dataObj->$prodcutoN->color = "0" ? 0 : $dataObj->$prodcutoN->color;
                $idProveedor = $dataObj->$prodcutoN->proveedor_principal = "0" ? 0 : $dataObj->$prodcutoN->proveedor_principal;
                $idLinea = $dataObj->$prodcutoN->linea = "0" ? 0 : $dataObj->$prodcutoN->linea;
                $idMaterial = $dataObj->$prodcutoN->material = "0" ? 0 : $dataObj->$prodcutoN->material;


                $attr_marca = $idMarca ? $this->atributos->get_attr($idMarca) : "";
                $attr_talla = $idTalla ? $this->atributos->get_attr($idTalla) : "";
                $attr_color = $idColor ? $this->atributos->get_attr($idColor) : "";
                $attr_linea = $idLinea ? $this->atributos->get_attr($idLinea) : "";
                $attr_material = $idMaterial ? $this->atributos->get_attr($idMaterial) : "";
                $attr_proveedor = $idProveedor ? $this->atributos->getClasificacionProveedor($idProveedor) : "";


                $listaAlmacenes = $dataObj->$prodcutoN->lista_almacenes;

                $nombre = $dataObj->$prodcutoN->nombre == " " ? "" : $dataObj->$prodcutoN->nombre;
                $nombreProveedor = $dataObj->$prodcutoN->nombre_proveedor == " " ? "" : $dataObj->$prodcutoN->nombre_proveedor;
                $descripcion = $dataObj->$prodcutoN->descripcion == " " ? "" : $dataObj->$prodcutoN->descripcion;
                $pc = $dataObj->$prodcutoN->precio_compra == " " ? 0 : $dataObj->$prodcutoN->precio_compra;
                $pv = $dataObj->$prodcutoN->precio_venta == " " ? 0 : $dataObj->$prodcutoN->precio_venta;
                $codigo = $dataObj->$prodcutoN->codigo == " " ? 0 : $dataObj->$prodcutoN->codigo;
                $categoria_atributo = $dataObj->$prodcutoN->categoria_atributos = "0" ? 0 : $dataObj->$prodcutoN->categoria_atributos;
                $impuesto = $dataObj->$prodcutoN->impuesto = "0" ? 0 : $dataObj->$prodcutoN->impuesto;
                $tienda = $dataObj->$prodcutoN->tienda;
                $active = $dataObj->$prodcutoN->activo;
                $material = $attr_material;
                $name = $nombre . "/" . $attr_marca . "/" . $attr_talla . "/" . $attr_color . "/" . $attr_material . "/" . $attr_proveedor . "/" . $attr_linea;
                $atributosAsignados = $idMarca . "/" . $idTalla . "/" . $idColor . "/" . $idMaterial . "/" . $idProveedor . "/" . $idLinea;




                //Datos para la nueva tabla atributos_prodcuto
                //Capturamos desde db el nombre de la categoria
                $nombreCatgoria = $this->atributos->getAtributoCategoria($categoria_atributo);

                $nombreProveedorClasificacion = $idProveedor ? $this->atributos->getClasificacionProveedor($idProveedor) : "";
                $nombreMarcaClasificacion = $idMarca ? $this->atributos->getClasificacionMarca($idMarca) : "";


                //=========================================================================================
                //=========================================================================================
                //=========================================================================================
                //ARRAY DE ATRIBUTOS DISPONIBLES
                //Este array deberia ser dinamico
                $arrayAtributos = Array();

                // Atributo Marca
                if ($idMarca) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 1,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(1),
                        "id_clasificacion" => $idMarca,
                        'nombre_clasificacion' => $nombreMarcaClasificacion
                    );
                }

                // Atributo Proveedor
                if ($idProveedor) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 2,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(2),
                        "id_clasificacion" => $idProveedor,
                        'nombre_clasificacion' => $nombreProveedorClasificacion
                    );
                }


                // Atributo Color
                if ($idColor) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 3,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(3),
                        "id_clasificacion" => $idColor,
                        'nombre_clasificacion' => $attr_color
                    );
                }


                // Atributo Talla
                if ($idTalla) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 4,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(4),
                        "id_clasificacion" => $idTalla,
                        'nombre_clasificacion' => $attr_talla
                    );
                }

                // Atributo Linea
                if ($idLinea) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 5,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(5),
                        "id_clasificacion" => $idLinea,
                        'nombre_clasificacion' => $attr_linea
                    );
                }

                // Atributo Material
                if ($idMaterial) {
                    $arrayAtributos[] = Array(
                        "id_atributo" => 6,
                        'nombre_atributo' => $this->atributos->getNombreAtributo(6),
                        "id_clasificacion" => $idMaterial,
                        'nombre_clasificacion' => $attr_material
                    );
                }

                // Si no se selecciono ningun atributo
                if (!count($arrayAtributos)) {

                    redirect('productos/atributos', 'refresh');
                }


                //=========================================================================================
                //=========================================================================================
                //=========================================================================================
                //-----------------------------------
                // Añadimos los anteriores atributos
                // Array for table [atributos_productos]
                $dataAttributoProducto = array(
                    "codigo_interno" => intval($this->atributos->getIdPrductoAtributos()) + 1,
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
                    "atributos" => $atributosAsignados,
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


                $id_producto = $this->atributos->add_producto_attr($dataProducto, $cantidadAlmacen, $dataAttributoProducto);

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

    public function posee_categorias($id = false, $campo = false) {
        if ($campo && $id) {
            // exit(json_encode($this->atributos->posee_categorias($id, $campo)));
        }

        echo json_encode($this->atributos->posee_categorias2($id, $campo));

        // echo 0;
    }

    public function get_ajax_data() {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->atributos->get_ajax_data()));
    }

    //  Deprecated
    /*
      public function nuevo() {

      $error_upload = "";

      if (!$this->ion_auth->logged_in()) {
      redirect('auth', 'refresh');
      }

      if ($_POST) {

      $data = array(
      'nombre' => $this->input->post('nombre')
      );

      $this->atributos->add($data);

      $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo creado correctamente'));
      redirect('atributos/index');
      }
      $this->layout->template('member')->show('atributos/nuevo');
      }



      public function editar($id) {
      $error_upload = "";
      if (!$this->ion_auth->logged_in()) {
      redirect('auth', 'refresh');
      }

      if ($_POST) {
      $data = array(
      'id' => $this->input->post('id')
      , 'nombre' => $this->input->post('nombre')
      );
      $this->atributos->update($data);

      $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo actualizado correctamente'));
      redirect('atributos/index');
      }

      $data = array();
      $data['data'] = $this->atributos->get_by_id($id);
      $this->layout->template('member')->show('atributos/editar', array('data' => $data));
      }

      public function eliminar($id) {
      if (!$this->ion_auth->logged_in()) {
      redirect('auth', 'refresh');
      }
      $this->atributos->delete($id);
      $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'Se ha eliminado correctamente'));
      redirect("atributos/index");
      }
     */
}

?>

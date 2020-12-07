<?php

class Tienda extends CI_Controller {

    var $dbConnection;

    function __construct() {



        parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("tienda_model", 'tienda');

        $this->load->model("usuarios_model", 'user');

        $this->load->model("redes_model", 'redes');
        $this->load->model("plantillas_model", 'plantillas');



        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("ventas_online_model", 'ventas_online');
        $this->ventas_online->initialize($this->dbConnection);
        //creacion de tablas si no existen
        $this->ventas_online->existeTabla($base_dato);
        $this->ventas_online->actualizarVenta();

        $this->load->model("envio_tienda_model","envio");
        $this->envio->initialize($this->dbConnection);
        $this->envio->existeTabla($base_dato);

        $this->load->model("almacenes_model", 'almacen');

        $this->almacen->initialize($this->dbConnection);


        $this->load->model("atributos_model", "atributos");
        $this->atributos->initialize($this->dbConnection);

        $this->load->model("opciones_model", "opciones");
        $this->opciones->initialize($this->dbConnection);

        $this->load->model('productos_model','productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("ventas_model", 'ventas');
        $this->ventas->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

    }

    public function index() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        //ajustes en categorias
        //$this->categorias->updateColumnCategoria();
        //$this->categorias->updateColumnMenuTienda();

        $ap = $this->atributos->atributosProducto($this->session->userdata('base_dato'));
        //die(var_dump($this->atributos->atributosProducto($this->session->userdata('base_dato'))));
        
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        
        $plantillas = $this->plantillas->get_by_tipo_negocio($data["tipo_negocio"]);
        /*if ($ap != false) {
            $plantillas = $this->plantillas->getAll(true);
        } else {
            $plantillas = $this->plantillas->getAll();
        }*/

        $user_id = $this->session->userdata('user_id');
        //var_dump($user_id);die();
        //Obtiene la informacion de la tienda del usuario

        $data = $this->tienda->get_by_id_user($user_id);



        $data['almacen'] = $this->almacen->get_combo_data();
        $data['envio'] = $this->envio->getAll();
        $data['url_uploads'] = base_url() .'uploads/'. $this->session->userdata('base_dato') .'/';
        $data['producto_envio'] = $this->productos->get_producto_envio();

        //$data['id_almacen'] = $this->almacen->get_combo_data_id();
        //obtener redes segun id del usuario

        $dataRed = $this->redes->getByUser($user_id);


        //configuracion URL shop
        $dir_tienda = $this->config->item('url_shop');

        //agregar columna de producto destacado en tienda en la tabla productos
        $this->productos->agregar_columna_destacado_tienda();
        //consulta de productos 
        $productos = $this->productos->getList();


        $this->layout->template('member')->show('tienda/index', array(
            'data' => $data,
            'dir_tienda' => $dir_tienda,
            'dataRed' => $dataRed,
            'plantillas' => $plantillas,
            'productos' => $productos
        ));
    }

    public function plantilla() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $user_id = $this->session->userdata('user_id');


        $exist = $this->tienda->get_by_id_user($user_id);

        $array_datos = array(
            "id_user" => $user_id,
            "layout" => $this->input->post('layout')
        );

        if (isset($exist['id_user'])) {//actualizacion                        
            $this->tienda->update_layout($array_datos);

            $this->session->set_flashdata('message', 'Tienda actualizada satisfactoriamente.');

            redirect('tienda/index');

            //  $this->enviar_email('upd');
        } else { // adicion                        
            $this->tienda->add($array_datos);

            $this->session->set_flashdata('message', 'Tienda creada satisfactoriamente.');

            redirect('tienda/index');
            // $this->enviar_email('add');
        }
    }

    public function formas_pago() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);
        $array_datos = array(
            "id_user" => $user_id,
            "merchantId" => $this->input->post('merchantId'),
            "accountId" => $this->input->post('accountId'),
            "ApiKey" => $this->input->post('ApiKey'),
            "apikeyEPayco" => $this->input->post('apikeyEPayco'),
            "idClienteEPayco" => $this->input->post('idClienteEPayco'),
            "publickeyEPayco" => $this->input->post('publickeyEPayco'),
            "cuentabancaria" => $this->input->post('cuentabancaria'),
            "nombrebanco" => $this->input->post('nombrebanco'),
            "nombretitular" => $this->input->post('nombretitular'),
            "tipocuenta" => $this->input->post('tipocuenta'),
            "correo" => $this->input->post('correo')
        );

        if (isset($exist['id_user'])) {
            $this->tienda->update_formas_pago($array_datos);
            $this->session->set_flashdata('message', 'Tienda actualizada satisfactoriamente.');
            redirect('tienda/index');
            //  $this->enviar_email('upd');
        } else {
            $this->tienda->add($array_datos);
            $this->session->set_flashdata('message', 'Tienda creada satisfactoriamente.');
            redirect('tienda/index');
        }
    }

    public function quienessomos() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);

        $error_upload = "";

        $image_name = "";



        //$config['upload_path'] = '../tienda/public/uploads/quienessomos/';
        $config['upload_path'] = 'uploads/'.$this->session->userdata('base_dato');
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

        $config['max_size'] = '2024';

        $config['max_width'] = '100800';

        $config['max_height'] = '100000';

        $this->load->library('upload', $config);

        if (!empty($_FILES['imagenQuienesSomos1']['name'])) {
            if (!$this->upload->do_upload('imagenQuienesSomos1')) {
                $error_upload = $this->upload->display_errors('<p>', '</p>');
                echo $error_upload;
                die;
            } else {
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    if (file_exists('../tiendaVirtual/public/uploads/quienessomos/' . $tienda["imagenQuienesSomos1"])) {
                        unlink('../tiendaVirtual/public/uploads/quienessomos/' . $tienda["imagenQuienesSomos1"]);
                    }
                }
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];


                $this->tienda->update_field('imagenQuienesSomos1', $image_name, $user_id);
            }
        }

        if (!empty($_FILES['imagenQuienesSomos2']['name'])) {
            if (!$this->upload->do_upload('imagenQuienesSomos2')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    if (file_exists('../tienda/public/uploads/quienessomos/' . $tienda["imagenQuienesSomos2"])) {
                        unlink('../tienda/public/uploads/quienessomos/' . $tienda["imagenQuienesSomos2"]);
                    }
                }
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];


                $this->tienda->update_field('imagenQuienesSomos2', $image_name, $user_id);
            }
        }
        if(isset($_POST['tituloQuienesSomos']))
            $this->tienda->update_field('tituloQuienesSomos', $_POST['tituloQuienesSomos'], $user_id);
        
        if(isset($_POST['descripcionQuienesSomos']))
            $this->tienda->update_field('descripcionQuienesSomos', $_POST['descripcionQuienesSomos'], $user_id);
        

        $this->session->set_flashdata('message', 'Quienes somos actualizado satisfactoriamente.');

        redirect('tienda/index');
    }


    public function diseno(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');

        $error_upload = "";
        $image_name = "";

      
        $config['upload_path'] = 'uploads/'.$this->session->userdata('base_dato');
        $config['max_size'] = '2024';
        $config['max_width'] = '100800';
        $config['max_height'] = '100000';
        
        if (!empty($_FILES['imagen_parallax']['name'])) {
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('imagen_parallax')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    unlink('uploads/logotienda/' . $tienda["imagen_parallax"]);
                }
            }
            if(isset($image_name)){
                $this->tienda->update_field('imagen_parallax', $image_name, $user_id);
            }
        }

      
      
        if (isset($_POST['menu_estatico']))
            $this->tienda->update_field('menu_estatico', $_POST['menu_estatico'], $user_id);

        if (isset($_POST['color_fondo_menu']))
        $this->tienda->update_field('color_fondo_menu', $_POST['color_fondo_menu'], $user_id);

        if (isset($_POST['color_letra_menu']))
        $this->tienda->update_field('color_letra_menu', $_POST['color_letra_menu'], $user_id);

        if (isset($_POST['color_fondo_pie_pagina']))
        $this->tienda->update_field('color_fondo_pie_pagina', $_POST['color_fondo_pie_pagina'], $user_id);

        if (isset($_POST['color_letra_pie_pagina']))
        $this->tienda->update_field('color_letra_pie_pagina', $_POST['color_letra_pie_pagina'], $user_id);

        if (isset($_POST['texto_parallax']))
            $this->tienda->update_field('texto_parallax', $_POST['texto_parallax'], $user_id);

        if (isset($_POST['texto_boton_parallax']))
            $this->tienda->update_field('texto_boton_parallax', $_POST['texto_boton_parallax'], $user_id);

        if (isset($_POST['link_parallax']))
        $this->tienda->update_field('link_parallax', $_POST['link_parallax'], $user_id);

        $this->session->set_flashdata('message', 'Ajustes de diseño actualizados satisfactoriamente.');
        redirect('tienda/index');
    }

	public function terminosCondiciones() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);

        if (isset($_POST['terminos_condiciones_titulo']))
            $this->tienda->update_field('terminos_condiciones_titulo', $_POST['terminos_condiciones_titulo'], $user_id);

        if (isset($_POST['terminos_condiciones']))
            $this->tienda->update_field('terminos_condiciones', $_POST['terminos_condiciones'], $user_id);


        $this->session->set_flashdata('message', 'Terminos y condiciones actualizado satisfactoriamente.');

        redirect('tienda/index');
    }
    
    public function propiedadIntelectual() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);

        if (isset($_POST['propiedad_intelectual_titulo']))
            $this->tienda->update_field('propiedad_intelectual_titulo', $_POST['propiedad_intelectual_titulo'], $user_id);

        if (isset($_POST['propiedad_intelectual']))
            $this->tienda->update_field('propiedad_intelectual', $_POST['propiedad_intelectual'], $user_id);


        $this->session->set_flashdata('message', 'Propiedad intelectual actualizado satisfactoriamente.');

        redirect('tienda/index');
    }
    
    public function cambiosDevoluciones() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);

        if (isset($_POST['cambios_devoluciones_titulo']))
            $this->tienda->update_field('cambios_devoluciones_titulo', $_POST['cambios_devoluciones_titulo'], $user_id);

        if (isset($_POST['cambios_devoluciones']))
            $this->tienda->update_field('cambios_devoluciones', $_POST['cambios_devoluciones'], $user_id);


        $this->session->set_flashdata('message', 'Cambios y devoluciones actualizado satisfactoriamente.');

        redirect('tienda/index');
    }
    
    public function tratamientoDatos() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $exist = $this->tienda->get_by_id_user($user_id);

        if (isset($_POST['tratamiento_datos_titulo']))
            $this->tienda->update_field('tratamiento_datos_titulo', $_POST['tratamiento_datos_titulo'], $user_id);

        if (isset($_POST['tratamiento_datos']))
            $this->tienda->update_field('tratamiento_datos', $_POST['tratamiento_datos'], $user_id);


        $this->session->set_flashdata('message', 'Tratamiento de datos actualizado satisfactoriamente.');

        redirect('tienda/index');
    }

    public function nuevo() {



        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        //echo $this->input->post('layout');

        if ($this->input->post('activo') == 'on')
            $activo = 1;
        else
            $activo = 0;
        if ($this->input->post('stock_almacen') == 'on')
            $stock_almacen = 1;
        else
            $stock_almacen = 0;



        $user_id = $this->session->userdata('user_id');



        if ($activo == 1) {// nuevo o acualizado
            $exist = $this->tienda->get_by_id_user($user_id);




            if ($this->input->post('shopname') != 'tienda' && $this->input->post('shopname') != 'admin' &&
                    $this->input->post('shopname') != 'gii' && $this->input->post('shopname') != 'invoice' &&
                    $this->input->post('shopname') != 'crearTienda') {





                if ($this->buscarTienda($this->input->post('shopname')) == FALSE) {
                 
                    $array_datos = array(
                        "id_user" => $user_id,
                        "id_almacen" => $this->input->post('almacen'),
                        "shopname" => str_replace(' ', '_', $this->input->post('shopname')),
                        "layout" => 'retail',
                        "correo" => $this->input->post('correo'),
                        "description" =>str_replace('\n', '\n', $this->input->post('description')),
                        "activo" => $activo,                  
                        "stock_almacen" => $stock_almacen,
                        "telefono" => $this->input->post('telefono')
                    );


                    if (isset($exist['id_user'])) {//actualizacion                        
                        $this->tienda->update($array_datos);

                        $this->session->set_flashdata('message', 'Tienda actualizada satisfactoriamente.');

                        //  $this->enviar_email('upd');
                    } else { // adicion                        
                        $this->tienda->add($array_datos);

                        $this->session->set_flashdata('message', 'Tienda creada satisfactoriamente.');

                        redirect('tienda/index');
                        // $this->enviar_email('add');
                    }
                } else {

                    $this->session->set_flashdata('message', 'Nombre de tienda reservado, elija otra nombre para su Tienda Virtual.');
                }
            } else {

                $this->session->set_flashdata('message', 'Nombre de tienda no permitido.');
            }
        } else { // actualizar solo activo a 0

            $this->tienda->update_field('activo', 0, $user_id);

            $this->session->set_flashdata('message', 'A partir de este momento su Tienda Virtual deja de estar disponible.');
        }



        // redirect('tienda/index');

        $this->session->set_flashdata('message', 'Tienda actualizada satisfactoriamente.');

        redirect('tienda/index');

        // $this->layout->template('member')->show('tienda/index', array('data' => $array_datos));
    }

    public function logo() {
        $user_id = $this->session->userdata('user_id');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $error_upload = "";
        $image_name = "";

      
        $config['upload_path'] = 'uploads/'.$this->session->userdata('base_dato');
        $config['max_size'] = '2024';
        $config['max_width'] = '100800';
        $config['max_height'] = '100000';
        
        if (!empty($_FILES['logo']['name'])) {
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('logo')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    unlink('uploads/logotienda/' . $tienda["logo"]);
                }
            }
            if(isset($image_name)){
                $this->tienda->update_field('logo', $image_name, $user_id);
            }
        }
        
        /**************** FAV ICON ********************/
        if (!empty($_FILES['favicon']['name'])) {
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('favicon')) {
                $error_upload .= $this->upload->display_errors('<p>', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    unlink('uploads/logotienda/' . $tienda["favicon"]);
                }
            }
            if(isset($image_name)){
                $this->tienda->update_field('favicon', $image_name, $user_id);
            }
        }
        /**************** Fondo de pagina ********************/
        if (!empty($_FILES['fondo']['name'])) {
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('fondo')) {
                $error_upload .= $this->upload->display_errors('<p>', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    unlink('uploads/logotienda/' . $tienda["fondo"]);
                }
            }
            if(isset($image_name)){
                $this->tienda->update_field('fondo', $image_name, $user_id);
            }
        }
        /**************** Logo inferior ********************/
        if (!empty($_FILES['logo_inferior']['name'])) {
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('logo_inferior')) {
                $error_upload .= $this->upload->display_errors('<p>', '</p>');
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $tienda = $this->tienda->get_by_id_user($user_id);
                if (!empty($tienda)) {
                    unlink('uploads/logotienda/' . $tienda["logo_inferior"]);
                }
            }
            if(isset($image_name)){
                $this->tienda->update_field('logo_inferior', $image_name, $user_id);
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        if($error_upload == ''){
            $this->session->set_flashdata('message', 'Logo de Tienda actualizado satisfactoriamente.');
        }else{
            $this->session->set_flashdata('message_error', 'Ha ocurrido un error! '.$error_upload);
        }
        

        redirect('tienda/index');
    }

    public function slider() {

        $user_id = $this->session->userdata('user_id');

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }


        $error_upload = "";

        $image_name = "";



        //$config['upload_path'] = '../tienda/public/uploads/slider_tienda/';
        $config['upload_path'] = 'uploads/'.$this->session->userdata('base_dato');
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';

        $config['max_size'] = '2024';

        $config['max_width'] = '100800';

        $config['max_height'] = '100000';



        $this->load->library('upload', $config);

        for($i=1;$i<=6;$i++)
        {
            if (isset($_POST['link_slider'.$i])){
             $this->tienda->update_field('link_slider'.$i, $_POST['link_slider'.$i], $user_id);
            }
            if (isset($_FILES['slider'.$i]) && !empty($_FILES['slider'.$i]['name'])) {
                
                if (!$this->upload->do_upload('slider'.$i)) {
                    $error_upload = $this->upload->display_errors('<p>', '</p>');
                } else {
                    $tienda = $this->tienda->get_by_id_user($user_id);
                    if (!empty($tienda)) {
                        if (file_exists('../tienda/public/uploads/slider_tienda/' . $tienda["slider".$i])) {
                            unlink('../tienda/public/uploads/slider_tienda/' . $tienda["slider".$i]);
                        }
                    }
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                    $this->tienda->update_field('slider'.$i, $image_name, $user_id);
                }
            }
        }
        
        $this->session->set_flashdata('message', 'Slider actualizado satisfactoriamente.');

        redirect('tienda/index');
    }


    public function marcas_destacadas() {

        $user_id = $this->session->userdata('user_id');

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        $error_upload = "";
        $image_name = "";

        //$config['upload_path'] = '../tienda/public/uploads/slider_tienda/';
        $config['upload_path'] = 'uploads/'.$this->session->userdata('base_dato');
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
        $config['max_size'] = '2024';
        $config['max_width'] = '100800';
        $config['max_height'] = '100000';

        $this->load->library('upload', $config);

        for($i=1;$i<=6;$i++)
        {
            if (isset($_POST['link_marca'.$i])){
             $this->tienda->update_field('link_marca'.$i, $_POST['link_marca'.$i], $user_id);
            }
            if (isset($_FILES['marca'.$i]) && !empty($_FILES['marca'.$i]['name'])) {
                
                if (!$this->upload->do_upload('marca'.$i)) {
                    $error_upload = $this->upload->display_errors('<p>', '</p>');
                } else {
                    $tienda = $this->tienda->get_by_id_user($user_id);
                    if (!empty($tienda)) {
                        if (file_exists('../tienda/public/uploads/marca_tienda/' . $tienda["marca".$i])) {
                            unlink('../tienda/public/uploads/marca_tienda/' . $tienda["marca".$i]);
                        }
                    }
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                    $this->tienda->update_field('marca'.$i, $image_name, $user_id);
                }
            }
        }
        
        $this->session->set_flashdata('message', 'Marcas destacadas actualizadas satisfactoriamente.');
        redirect('tienda/index');
    }


    public function seo() {



        $user_id = $this->session->userdata('user_id');


        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }





        $this->tienda->update_field('seo_description', $this->input->post('seo_description'), $user_id);

        $this->tienda->update_field('seo_keywords', $this->input->post('seo_keywords'), $user_id);



        $this->session->set_flashdata('message', 'Parametros de SEO actualizados satisfactoriamente.');
        redirect('tienda/index');
    }

    public function googlemap() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $user_id = $this->session->userdata('user_id');

        $this->tienda->update_field('google_map', $this->input->post('google_map'), $user_id);



        $this->session->set_flashdata('message', 'Direccion de Google Map actualizada satisfactoriamente.');

        redirect('tienda/index');
    }

    public function redes() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }



        $user_id = $this->session->userdata('user_id');

        $data = array(
            'id_user' => $user_id,
            'drible' => $this->input->post('drible'),
            'facebook' => $this->input->post('facebook'),
            'google' => $this->input->post('google'),
            'instagram' => $this->input->post('instagram'),
            'linkedin' => $this->input->post('linkedin'),
            'twitter' => $this->input->post('twitter'),
            'youtube' => $this->input->post('youtube'),
			'pinterest' => $this->input->post('pinterest'),
        );

        if ($this->redes->existeUser($user_id)) { // upd

            $this->redes->update($data);
        } else { // add

            $this->redes->add($data);
        }





        $this->session->set_flashdata('message', 'Redes Sociales actualizadas satisfactoriamente.');

        redirect('tienda/index');
    }

    public function cobro_envio(){
        
        $user_id = $this->session->userdata('user_id');
        $envio_gratis_desde = $this->input->post("envio_gratis_desde");
        $id_producto_envio = $this->input->post("id_producto_envio");
        $this->tienda->update_field('envio_gratis_desde', $envio_gratis_desde, $user_id);
        $this->tienda->update_field('id_producto_envio', $id_producto_envio, $user_id);
        $this->session->set_flashdata('message', 'Informacion de envios actualizada satisfactoriamente.');
        redirect('tienda/index');
    }

    public function envio()
    {
        $this->envio->existeTabla($this->session->userdata('base_dato'));
        $cobro_envios = $this->input->post('s_cobro_envios');
        $user_id = $this->session->userdata('user_id');
        $this->tienda->update_field('cobro_envios', $cobro_envios, $user_id);
        if(isset($_POST['id']))
        {
            foreach($_POST['id'] as $key =>$id)
            {
                if(empty($_POST['nombre'][$key])){
                    continue;
                }
                $envio = array(
                    'nombre'=>$_POST['nombre'][$key],
                    'valor'=>$_POST['valor'][$key],
                    'activo'=>$_POST['activo'][$key]
                );
                if($id == 0)
                {
                    $this->envio->insertar($envio);
                }else
                {
                    $envio['id'] = $id;
                    $this->envio->modificar($envio);
                }
            }
            $this->session->set_flashdata('message', 'Informacion de envios actualizada satisfactoriamente.');
        }
        redirect('tienda/index');
    }
    
    public function eliminarEnvio()
    {
        if(isset($_POST['id']) && $_POST['id'] !=0)
        {
            echo json_encode(array("resp"=>$this->envio->eliminar($_POST['id'])));
            
        }else
        {
            echo json_encode(array("resp"=>1));
        }
    }

    public function buscarTienda($tienda) {


        return $this->tienda->buscarIgual($tienda, $this->session->userdata('user_id'));
    }

    public function enviar_email($accion /* $id */) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }



        $user_id = $this->session->userdata('user_id');

        // $user = $this->user->getByID($user_id);  

        $tienda = $this->tienda->get_by_id_user($user_id);

        //$empresa = $this->miempresa->get_email_empresa();

        $this->email->clear();
        $this->email->from('info@vendty.com', 'Vendty');
        $this->email->to($this->miempresa->get_email_empresa());
        $this->email->subject("Tienda Virtual ");
        $msg = '';

        if ($accion == 'add') {//crear
            $msg = '<h2> Muchas felicidades!!! </h2></br>Usted ha creado su propia Tienda Virtual para promocionar y vender sus productos.'
                    . ' </br> '
                    . 'Para acceder a la misma entre <a href="' . $this->config->item('url_shop') . $tienda['shopname'] . '">Aqu&iacute;</a>';
        }

        if ($accion == 'upd') { //actualizar
            $msg = '<h2>Atenci&oacute;n!!! </h2></br>Usted ha actualizado par&aacute;metros de su Tienda Virtual. Podr&iacute;a percibir cambios en el nombre o en la plantilla de su Tienda.'
                    . ' </br>'
                    . 'Para acceder a la misma entre <a href="' . $this->config->item('url_shop') . $tienda['shopname'] . '">Aqu&iacute;</a>';
        }

        $this->email->message($msg);
        $this->email->send();
    }

    public function marcar_productos_destacados(){
        $productos_destacados = $this->input->post('s_productos_destacados');
        $data=array('destacado_tienda'=>1);
        foreach ($productos_destacados as $key => $value) {
            $this->productos->update_producto_destacado($data,array('id'=>$value));
        }
         $this->session->set_flashdata('message', 'Productos destacados actualizados correctamente.');
        redirect('tienda/index');
    }

    public function configuracion(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $user_id = $this->session->userdata('user_id');
      
        if (isset($_POST['dominio_configuracion']))
            $this->tienda->update_field('dominio', $_POST['dominio_configuracion'], $user_id);

        if (isset($_POST['ch_productos_destacados']))
        $this->tienda->update_field('productos_destacados', $_POST['ch_productos_destacados'], $user_id);

        if (isset($_POST['ch_productos_recientes']))
        $this->tienda->update_field('productos_recientes', $_POST['ch_productos_recientes'], $user_id);

        $this->session->set_flashdata('message', 'Modulo configuración actualizados satisfactoriamente.');
        redirect('tienda/index');
    }

    public function crossDomain(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->tienda->load_data_user($user_id);
        $hash = $this->encrypt(json_encode($data));
        echo $hash;
    }

    public function encrypt($string)
	{
		return openssl_encrypt($string,"AES-256-CBC","rejdard",0,"1234567812345678"); 
    }
    
}


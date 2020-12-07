<?php


class Tamanos_productos extends CI_Controller {


    var $dbConnection;
    var $user;

    function __construct() {

        parent::__construct();
        
        $usuario = $this->session->userdata('usuario');
        $this->user = $this->session->userdata('user_id');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model("tamanos_productos_model", 'tamanos');

        $this->tamanos->initialize($this->dbConnection);

        $this->load->model('categorias_model','categorias');

        $this->categorias->initialize($this->dbConnection);

    }

    public function index(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->tamanos->crear_tablas_tamanos();
        //$this->secciones_almacen->check_existe_tabla_secciones();
        $this->layout->template('member')->show('tamanos_productos/index');
    }

    public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->tamanos->get_ajax_data()));
    }
    public function agregar_view(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $categorias = $this->categorias->get_combo_data();
        $this->layout->template('member')->show('tamanos_productos/nuevo',array('categorias'=>$categorias));
    }

    public function insertar(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if ($this->form_validation->run('tamanos_productos') == true)
        {
            $data = array(
                    'fecha_creacion' => date("Y-m-d h:i:s"),
                    'creado_por'     => $this->ion_auth->get_user_id(),
                    'nombre_tamano'  => $this->input->post('t_nombre_tamano'),
                    'descripcion_tamano'=> $this->input->post('ta_descripcion_tamano')
                );

            $id_tamano = $this->tamanos->agregar_tamano($data);
            foreach ($this->input->post('s_categorias_prducto') as $key => $value) {
                $data_categorias = array('idtamanos_productos'=>$id_tamano,
                                         'categoria_id'=>$value);
                $this->tamanos->insertar_categorias_tamanos($data_categorias);
            }
            if($this->input->is_ajax_request()){
                echo json_encode(array('status'=>true,'errors'=>'El nuevo tamaño se creo correctamente')); 
            }else{
                $this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'El nuevo tamaño se creo correctamente'));
                redirect('tamanos_productos/index');
            }  

        }else{
            if($this->input->is_ajax_request()){
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['status'] = FALSE;
                $response['error_html']= validation_errors();
                echo json_encode($response);

            }else{
                $categorias = $this->categorias->get_combo_data();
                $this->layout->template('member')->show('tamanos_productos/nuevo',array('categorias'=>$categorias));                
            }
        }
    }

    public function editar_view($id){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $categorias = $this->categorias->get_combo_data();
        $tamano = $this->tamanos->get_un_tamano(array('idtamanos_productos'=>$id));
       
        $descripcion_tamano = $tamano[0]->descripcion_tamano;
        $nombre_tamano = $tamano[0]->nombre_tamano;
        $categorias_tamano = $this->tamanos->get_categoria_tamanos(array('tamanos_productos.idtamanos_productos'=>$id));
        $arreglo_categorias = array();
        foreach ($categorias_tamano as $key => $value) {
            $arreglo_categorias[]=$value->categoria_id;
        }
        $this->layout->template('member')->show('tamanos_productos/editar',array('categorias'=>$categorias,'datos_tamano'=>$tamano,'categorias_tamano'=>$arreglo_categorias,'nombre_tamano'=>$nombre_tamano,'descripcion_tamano'=>$descripcion_tamano,'id'=>$id)); 
    }

    public function update($id){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if ($this->form_validation->run('tamanos_productos') == true)
        {
            $data = array(
                    'fecha_modificacion' => date("Y-m-d h:i:s"),
                    'modificado_por'     => $this->ion_auth->get_user_id(),
                    'nombre_tamano'  => $this->input->post('t_nombre_tamano'),
                    'descripcion_tamano'=> $this->input->post('ta_descripcion_tamano')
                );

            $this->tamanos->actualizar_tamano(array('idtamanos_productos'=>$id),$data);
            $this->tamanos->eliminar_categorias_tamanos(array('idtamanos_productos'=>$id));
            foreach ($this->input->post('s_categorias_prducto') as $key => $value) {
                $data_categorias = array('idtamanos_productos'=>$id,
                                         'categoria_id'=>$value);
                $this->tamanos->insertar_categorias_tamanos($data_categorias);
            }
            if($this->input->is_ajax_request()){
                echo json_encode(array('status'=>true,'errors'=>'La informacion del tamaño se actualizo correctamente'));  
            }else{
                $this->session->set_flashdata('message', custom_lang('sima_seccion_created_message', 'La informacion del tamaño se actualizo correctamente'));
                redirect('secciones_almacen/index');
            }           
            
        }
        else

        {
            if($this->input->is_ajax_request()){
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors); // Some might be empty
                $response['status'] = FALSE;
                $response['error_html']= validation_errors();
                echo json_encode($response);

            }else{
                $this->layout->template('member')->show('secciones/editar',array('data'=>$data,'datos_seccion'=>$seccion));             
            }
            
        }

    }

    public function eliminar(){

    }

    public function get_tamanos_categoria(){
        $tamanos = $this->tamanos->get_categoria_tamanos(array('categoria_id'=>$this->input->post('categoria')));
        echo json_encode($tamanos);
    }
}
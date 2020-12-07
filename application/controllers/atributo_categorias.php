<?php

class Atributo_Categorias extends CI_Controller {

    var $dbConnection;

    function __construct() {
        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
    }

    public function index() {

        $this->layout->template('member')->show('atributos_categoria/index');
        redirect('atributo_categorias/nuevo',"refresh");
    }
    
    /* Reemplzado por atributos/index
    public function nuevo() {

        $data['atributos'] = $this->atributos->get_data();
        $data['atributosN'] = $this->atributos->getAtributos();
        $data['categorias'] = $this->atributos->getAllCategorias();

        $this->layout->template('member')->show('atributos_categoria/nuevo', array('data' => $data));
    }
     */

    public function editar() {

        $data['atributos'] = $this->atributos->get_data();
        $data['atributosN'] = $this->atributos->getAtributos();
        $data['categorias'] = $this->atributos->getAllCategorias();

        $this->layout->template('member')->show('atributos_categoria/editar_producto', array('data' => $data));
        
    }    

    public function relacionar() {

        
        $data = array(
            'idCategoria' => $this->input->post('categoriaSeleccionada'),            
            'atributos' => $this->input->post('atributos')
        );          
        

        $this->atributos->atributosToCategoria($data);        

        //redirect('atribut/nuevo');
        
        $this->output->set_output("ok");
        
        
    }

    
    public function ajaxSeleccionados($id) {

        $result = $this->atributos->ajaxSeleccionados($id);
        $string = "";
        
        foreach($result as $key => $val){
            $string = $string."".$val->atributo_id.",";
        }
        
        $string = rtrim($string,",");
        
        $this->output->set_output($string);        
        
    }    
    
    public function ajaxAtributos() {

        $result = $this->atributos->ajaxAtributos();
        $this->output->set_content_type('application/json')->set_output( json_encode( $result  ) );        

    }

    public function ajaxClasificacion($id) {

        $result = $this->atributos->ajaxClasificacion($id);        
        $this->output->set_content_type('application/json')->set_output( json_encode( $result  ) );       

    }        
    
    public function ajaxAtributosManage() {
                
        
        $data = array(
            
            'tipo' => $this->input->post('tipo'),            
            'id' => $this->input->post('id'),
            'valor' => $this->input->post('valor')                
                
        );  
        
        
        if( $data["tipo"] == "add" ){
            // Retorna  el ID del 
            $result = $this->atributos->ajaxAtributosAdd($data["valor"]);
        }

        if( $data["tipo"] == "del" ){
            //Eliminamos
            $result = $this->atributos->ajaxAtributosDel($data);
        }
        
        if( $data["tipo"] == "set" ){
            
            // Modificamos el valor 
            $result = $this->atributos->ajaxAtributosSet($data);
            
        }
        
        
        $this->output->set_output( $result );
        
    }  
    
    
    public function ajaxClasificacionManage() {
                        
        $data = array(
            
            'tipo' => $this->input->post('tipo'),            
            'id' => $this->input->post('id'),
            'idAtr' => $this->input->post('idAtr'),
            'valor' => $this->input->post('valor')                
                
        );
        
        if( $data["tipo"] == "add" ){
            // Retorna  el ID del 
            $result = $this->atributos->ajaxClasificacionAdd($data["valor"],$data["idAtr"]);
        }

        if( $data["tipo"] == "del" ){
            //Eliminamos
            $result = $this->atributos->ajaxClasificacionDel($data);
        }
        
        if( $data["tipo"] == "set" ){            
            // Modificamos el valor             
            $result = $this->atributos->ajaxClasificacionSet($data);
            
        }
        
        
        $this->output->set_output( $result );
        
    }      
    
 


    
    public function editar2($id = false) {
        if ($id) {
            if ($this->input->post('nuevo')) {
                $data = array(
                    'nombre' => $this->input->post('nombre'),
                    'atributos' => $this->input->post('atributos')
                );

                $this->atributos->editar_categoria($id, $data);

                $alert = array(
                    "tipo" => "success",
                    "texto" => 'La categoria se editó correctamente.'
                );

                $this->session->set_flashdata('message', $alert);
                redirect('atributo_categorias');
            }

            $response['data'] = array(
                'categoria' => $this->atributos->get_categorias($id),
                'atributos' => $this->atributos->get_data()
            );

            return $this->layout->template('member')->show('atributos_categoria/editar', $response);
        }

        redirect('atributo_categorias');
    }

    public function get_data($value = '') {
        return $this->atributos->get_categorias($id);
    }

    public function get_ajax_data($id = false) {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->atributos->get_categorias()));
    }

    public function get_ajax_atributos_categorias($id = false, $campo = false) {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->atributos->get_ajax_atributos_categorias($id, $campo)));
    }

    public function eliminar($id = false) {
        if ($id) {
            if ($this->atributos->eliminar_categoria($id)) {
                $alert = array(
                    "tipo" => "error",
                    "texto" => "Categoria eliminada correctamente"
                );
            } else {
                $alert = array(
                    "tipo" => "error",
                    "texto" => 'No se pudo eliminar la categoria, por favor comunicarse con <a href="mailto: soporte@vendty.com">soporte@vendty.com</a>'
                );
            }

            $this->session->set_flashdata('message', $alert);
        }

        redirect('atributo_categorias');
    }

}

?>

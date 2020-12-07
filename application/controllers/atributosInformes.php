<?php

class AtributosInformes extends CI_Controller {

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
        redirect('atributosInformes/productos', 'refresh');               
    }

    
    //============================================================
    //  INFORMES PRODUCTOS
    //============================================================    
    
    
    //Vista del panel de productos
    public function productos() {      
        
        $data = array();
                
        $data['marcas'] = $this->atributos->ajaxClasificacion(1);        
        $data['proveedores'] = $this->atributos->ajaxClasificacion(2);
        $data['colores'] = $this->atributos->ajaxClasificacion(3);
        $data['tallas'] = $this->atributos->ajaxClasificacion(4);
        $data['lineas'] = $this->atributos->ajaxClasificacion(5);
        $data['materiales'] = $this->atributos->ajaxClasificacion(6);   
        
        $data['categorias'] = $this->atributos->ajaxCategorias();   
        $data['almacenes'] = $this->atributos->ajaxAlmacenes(); 
                          
        $this->layout->template('member')->show('atributos/atributosInformeInventario',array( 'data' => $data) );        
        
    }
    
    
    public function qPivote() {
                
        $idAtributos = $this->input->post("str");                
                
        $arrayData =  explode(",", $idAtributos);
        
        $data = array();
        $data['marca'] = $arrayData[0];
        $data['color'] = $arrayData[1];
        $data['talla'] = $arrayData[2];
        $data['proveedor'] = $arrayData[3];
        $data['material'] = $arrayData[4];
        $data['linea'] = $arrayData[5];
        $data['almacen'] = $arrayData[6];
        $data['categoria'] = $arrayData[7];
        
        $result = $this->productos->queryPivote($data);
        
        // Luego de obtener los resultados       
        
        $this->output->set_content_type('application/json')->set_output( json_encode( $result ) );        
        
    }


}

?>

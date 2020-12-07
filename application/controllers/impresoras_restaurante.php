<?php

class Impresoras_Restaurante extends CI_Controller {

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

        $this->load->model("impresoras_restaurante_model", 'impresoras');
        $this->impresoras->initialize($this->dbConnection); 

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("impresora_rest_categoria_almacen_model", 'impresoras_categoria_almacen');
        $this->impresoras_categoria_almacen->initialize($this->dbConnection);
       
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
    }

    public function index() {
        
        $this->impresoras->createtableimpresora_restaurante();
        $this->impresoras_categoria_almacen->createtable_impresoras_categoria_almacen();
        $data = array();
        $data['impresoras'] = $this->impresoras->get_impresoras();  
        $data['almacenes'] = $this->almacenes->getAll();   
        $data['apikey'] = $this->impresoras->getApiKey();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

        if($this->session->userdata('is_admin') == "t"){
            $this->layout->template('member')->show('impresoras_restaurante/index',array('data' => $data));
       }else{
          redirect(site_url('frontend/index'));
       }
                   
    }

    public function nuevo() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($_POST) {            
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'codigo' => $this->input->post('codigo')
               // 'id_almacen' => $this->input->post('id_almacen')
            );
            $this->impresoras->add($data);
            $this->session->set_flashdata('message', custom_lang('sima_pinter_created_message', 'Impresora creada correctamente'));
            redirect('impresoras_restaurante/index');
        }

        $almacenes = $this->almacenes->getAll();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('impresoras_restaurante/nuevo', array('almacenes' => $almacenes,'data' => $data));         
    }

    public function editar($id) {               
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if ($_POST) {  
       // if ($this->form_validation->run('impresoras_restaurante') == true) {
            $data = array(
                'id' => $id,
                'nombre' => $this->input->post('nombre'),
                'codigo' => $this->input->post('codigo'),
                //'id_almacen' => $this->input->post('id_almacen')
            );
           
            $this->impresoras->update($data);
            $this->session->set_flashdata('message', custom_lang('sima_printer_updated', 'Impresora actualizada correctamente'));
            redirect('impresoras_restaurante/index');
       // }
        }
        $data = array();
        $data['impresora'] = $this->impresoras->impresora_get_by_id($id);
        $data['almacenes']=  $this->almacenes->getAll();
        
        $this->layout->template('member')->show('impresoras_restaurante/editar', array('data' => $data));
    }

    public function eliminar($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
       
        $mensaje = $this->impresoras->delete($id);
        $this->impresoras_categoria_almacen->delete_impresora($id);

        $this->session->set_flashdata('message', custom_lang('sima_pinter_delete_message', $mensaje));
        redirect("impresoras_restaurante/index");
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

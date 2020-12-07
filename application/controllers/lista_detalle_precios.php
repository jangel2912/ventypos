<?php

    class lista_detalle_precios extends CI_Controller 
{
        var $dbConnection;
        
	function __construct() {
            parent::__construct();
            
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            
            $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');
            $this->lista_detalle_precios->initialize($this->dbConnection);

            $this->load->model("Impuestos_model",'impuestos');
            $this->impuestos->initialize($this->dbConnection);
            
            $idioma = $this->session->userdata('idioma');
            $this->lang->load('sima', $idioma);
        }


    public function filtrar_por_lista(){

        $data=array();
        $data=$this->lista_detalle_precios->get_where();

        foreach ( $data as $key => $value2) {
            foreach ( $value2 as $key2 => $value3) {
                if($key2 =='id_impuesto'){
                    $impuesto=$this->impuestos->get_by_id($value3);
                    if(!empty($impuesto['porciento']))
                    $value2->impuesto = $impuesto['porciento'];
                    else
                    $value2->impuesto = 0; 
                }   
            }     
        }
        
        if(!empty($data)){
            $this->output->set_content_type('application/json')->set_output( json_encode( array('done'=>1,'data'=>$data) ) );
        }else{
            $this->output->set_content_type('application/json')->set_output( json_encode( array('done'=>0,'data'=>$data) ) );
        }
    }


    public function crear(){

        $data=array();
      
        $data = $this->lista_detalle_precios->crear();
      
        if($data==1){
            $this->output->set_content_type('application/json')->set_output( json_encode( array('done'=>1) ));
        }else{
            $this->output->set_content_type('application/json')->set_output( json_encode( array('done'=>0) ));
        }

    }

    public function editar_precio_especial(){
      $this->lista_detalle_precios->edit_price_special($_POST);
      $_POST['lista'] = $_POST['list_id'];
      $this->filtrar_por_lista();
    }


    public function get_precio_venta(){
      $sql = "SELECT * FROM producto where id = '".$_POST['id_product']."'";
					
       foreach ($this->dbConnection->query($sql)->result() as $value) { 
	        $precio = $value->precio_venta;
	   }
	   $this->output->set_content_type('application/json')->set_output( json_encode( array('precio_venta'=>$precio) ));
    }

    public function eliminar_item($id){
      $this->lista_detalle_precios->delete_item_list($id);
      $_POST['lista'] = $_POST['list_id'];
      $this->filtrar_por_lista();
    }

    public function eliminar_lista_precios($id){
      $this->lista_detalle_precios->eliminar_lista_precios($id);
    }
        
    public function leer(){
        echo "Estoy aca controlador";
        $data=array();
        $data=$this->lista_detalle_precios->leer();
        
        
    }

    }
?>
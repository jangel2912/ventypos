<?php

class Productos_restaurante extends CI_Controller {

	function __construct() {
        parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("tamanos_productos_model", 'tamanos');

        $this->tamanos->initialize($this->dbConnection);

        $this->load->model('categorias_model','categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model('productos_model','productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model('ingredientes_model','ingredientes');
        $this->ingredientes->initialize($this->dbConnection);
    }


    public function nuevo_view(){
    	 $categorias = $this->categorias->get_combo_data();
         $ing_base = $this->ingredientes->get_ingrediente(array('id_tipo_producto'=>4));
         foreach ($ing_base as $key => $value) {
             $value->imagen = $this->productos->devolver_ruta_imagen($value->imagen);
             $lista_ingredientes['base'][]=$value;
         }
         
    	 $ing_adicion =  $this->ingredientes->get_ingrediente(array('id_tipo_producto'=>5));
         foreach ($ing_adicion as $key => $value) {
             $value->imagen = $this->productos->devolver_ruta_imagen($value->imagen);
             $lista_ingredientes['adicion'][]=$value;
         }
         $salsas = $this->ingredientes->get_ingrediente(array('id_tipo_producto'=>6));
         foreach ($salsas as $key => $value) {
            $value->imagen = $this->productos->devolver_ruta_imagen($value->imagen);
            $lista_ingredientes['salsa'][] = $value;     
         }
    	 $insumos = $this->ingredientes->get_ingrediente(array('id_tipo_producto'=>7));
         foreach ($insumos as $key => $value) {
            $value->imagen = $this->productos->devolver_ruta_imagen($value->imagen);
            $lista_ingredientes['insumos'][] = $value;     
         }
    	 
    	 $this->layout->template('member')->show('ingredientes/nuevo_restaurante',array('categorias'=>$categorias,'lista_ingredientes'=>$lista_ingredientes));	
    }


    public function guardar(){

    }


    
}
?>
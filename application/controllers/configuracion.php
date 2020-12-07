<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends CI_Controller 
{
    var $dbConnection;

    public function __construct() {
    	parent::__construct();
    	$usuario = $this->session->userdata('usuario');
    	$clave = $this->session->userdata('clave');
    	$servidor = $this->session->userdata('servidor');
    	$base_dato = $this->session->userdata('base_dato');
		$this->load->model( 'crm_licencia_model' , 'licencia' );

		$this->load->model( 'crm_orden' , 'orden' );
		
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

		$dns = "mysql://$usuario:$clave@$servidor/$base_dato";
		$this->dbConnection = $this->load->database($dns, true);
		$this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
		
		
    }

    public function carga_de_datos()
    {
    	$this->layout->template('member')->show('configuracion/carga_de_datos.php');
    }

    public function carga($formulario)
    {
    	$view = '';
    	$data = [];
    	$data['data']['estado'] = '';
    	$data['data']['upload_error'] = '';
    	
    	switch ($formulario) {
            case 'actualizacion_productos':
                $view = 'configuracion/actualizacion_masiva_productos.php';
            break;
			case 'productos_con_atributos':
				$view = 'configuracion/productos_con_atributos.php';
			break;
    	}

    	$this->layout->template('member')->show($view, $data);
    }

	// actualizacion desarrollo pagos online
	public function mis_planes(){
		
		$this->layout->template('member')->show('configuracion/mis_planes');
	}

	public function generar_orden_compra(){
		//verificamos que el cliente pertenezca a la empresa
		 $this->output->set_content_type('application/json');
		 $id_empresa = $this->input->post('id_empresa');

		if(isset($id_empresa)){
			$orden = $this->orden->check_orden_activa($id_empresa);

			//procedemos a verificar el estado de las ordenes de compra relacionadas a la empresa
			if($orden == 0){
				$this->orden->nuevo($id_empresa);
				$data = [
					"mensaje" => "Orden Generada",
					"estatus" => true
				];
			}else{
				$this->orden->add_licencia($id_empresa);
			}
		echo json_encode($data);
		//verificamos que exista una orden de compra en proceso de no ser asi la creamos
		
		}
		//$orden = this->creditos->get_ajax_data_pagadas(0);
		//$this->output->set_content_type('application/json')->set_output(json_encode($this->creditos->get_ajax_data_pagadas(0)));


	}
	
}

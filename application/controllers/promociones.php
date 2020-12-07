<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promociones extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model("promociones_model", 'promociones');
        $this->promociones->initialize($this->db_connection);

		$this->load->model("almacenes_model", 'almacenes');
		$this->almacenes->initialize($this->db_connection);

		$this->load->model("miempresa_model", 'mi_empresa');
		$this->mi_empresa->initialize($this->db_connection);
	}

	public function index()
	{	
		$this->checkLogin();
		$data = array();
		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
		$this->layout->template('member')->show('promociones/index',array("data" => $data));
	}

	public function crear()
	{	
		$this->checkLogin();
		
		$data = [
			'almacenes' => $this->almacenes->get_combo_data(null,true),
			'promocion' => null
		];
		
		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data['data']['tipo_negocio'] = $data_empresa['data']['tipo_negocio']; 

		$this->layout->template('member')->show('promociones/formulario', $data);
	}

	public function store()
	{
		$this->checkLogin();

		$data = [	
			'almacenes' => $this->almacenes->get_combo_data(),
			'promocion' => null
		];
		
		if ($this->form_validation->run('promociones') == true)
		{
			$id = $this->promociones->store($this->input->post());
			$cantidad = 0;
			if(is_numeric($this->input->post('cantidad'))){
				$cantidad = $this->input->post('cantidad');
			}

			$producto_pos = 0;
			if(is_numeric($this->input->post('producto_pos'))){
				$producto_pos = $this->input->post('producto_pos');
			}
			$reglas = array(
				'id_promocion'=>$id,
				'cantidad' => $cantidad,
				'producto_pos'=> $producto_pos,
				'descuento'=> $this->input->post('productosIguales'),
				'tipo'=> 'mayor_costo'
			);
			$this->promociones->sync_reglasTipo($reglas);
			$this->session->set_flashdata('message', 'Promoción creada satisfactoriamente');
			redirect('/promociones/editar/'.$id, 'location', 301);
		} else {
			$this->layout->template('member')->show('promociones/formulario', $data);
		}
	}

	public function editar($id)
	{
		$this->checkLogin();

		$data = [
                    'almacenes' => $this->almacenes->get_combo_data(null,true),
                    'promocion' => $this->promociones->get($id),
                    'reglas' => $this->promociones->reglas($id)
		];
		
		$data_empresa = $this->mi_empresa->get_data_empresa();
		$data['data']['tipo_negocio'] = $data_empresa['data']['tipo_negocio']; 
		$this->layout->template('member')->show('promociones/formulario', $data);
	}

	public function update()
	{
		$this->checkLogin();

		$data = [
			'almacenes' => $this->almacenes->get_combo_data(),
			'promocion' => $this->promociones->get($this->input->post('id'))
		];
              
		
		if ($this->form_validation->run('promociones') == true)
		{
                    $id = $this->promociones->update($this->input->post());
                    if($this->input->post('tipo') == "progresivo")
                    {
						$cantidad = 0;
						if(is_numeric($this->input->post('cantidad'))){
							$cantidad = $this->input->post('cantidad');
						}
						$producto_pos = 0;
						if(is_numeric($this->input->post('producto_pos'))){
							$producto_pos = $this->input->post('producto_pos');
						}
                        $reglas = array(
                            'id_promocion' => $id,
                            'cantidad' => $cantidad,
                            'producto_pos' => $producto_pos,
                            'descuento' => $this->input->post('productosIguales'),
                            'tipo'=> 'mayor_costo'
						);

						
                        $this->promociones->sync_reglasTipo($reglas);
                    }
                    $this->session->set_flashdata('message', 'Promoción actualizada satisfactoriamente');
                    redirect('/promociones/editar/'.$id, 'location', 301);
		} else {
			$this->layout->template('member')->show('promociones/formulario', $data);
		}
	}

	public function eliminar($id)
	{
		$this->checkLogin();

		if($this->promociones->delete($id))
			redirect('/promociones/', 'location', 301);
	}

	public function productos($id)
	{
            $this->checkLogin();
            //print_r($this->promociones->get($id));die;
            $data = [
                'almacenes' => $this->almacenes->get_combo_data(),
                'promocion' => $this->promociones->get($id),
                'id' => $id,
                'productos' => $this->promociones->productos($id),
                'accion' => 1
			];
			$data_empresa = $this->mi_empresa->get_data_empresa();
			$data['data']['tipo_negocio'] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('promociones/productos', $data);
	}
        
        public function productosDescuento($id)
	{
            $this->checkLogin();
            $pP = $this->promociones->existePromocionesPD($this->session->userdata('base_dato'));
            //var_dump($pP);
            if(empty($pP->row_data))
            {
                $this->promociones->crearPromocionesPD($this->session->userdata('base_dato'));
            }
                //die;
            $promocion = $this->promociones->get($id);
            //var_dump($promocion);die;
            //($promocion->tipo != "descuentocombo") ? redirect('/promociones/editar/'.$id, 'location', 301) :"";
            
            $data = [
                'almacenes' => $this->almacenes->get_combo_data(),
                'promocion' => $promocion,
                'productos' => $this->promociones->productosDescuento($id),
                'accion' => 2
			];
			$data_empresa = $this->mi_empresa->get_data_empresa();
			$data['data']['tipo_negocio'] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('promociones/productos', $data);
	}

	public function sync_productos()
	{
		$this->checkLogin();
		$id = $this->input->post('id');
		$data = [
			'almacenes' => $this->almacenes->get_combo_data(),
			'promocion' => $this->promociones->get($this->input->post('id'))
		];
		
		$this->promociones->sync_productos($this->input->post());
		$this->session->set_flashdata('message', 'Productos de la promoción actualizados satisfactoriamente');
		redirect('/promociones/editar/'.$id, 'location', 301);
	}

	public function reglas($id)
	{
            $this->checkLogin();
            $data = [
                'almacenes' => $this->almacenes->get_combo_data(),
                'promocion' => $this->promociones->get($id),
                'reglas' => $this->promociones->reglasAll($id)
            ];

            $this->layout->template('member')->show('promociones/reglas', $data);
	}

        public function addReglas()
	{
            	$this->checkLogin();
		$id = $this->input->post('id');
                $data = array(
                    "id_promocion"=>$id,
                    "producto_pos"=> 0,
                    "cantidad"=>$this->input->post('cantidad'),
                    "descuento"=>$this->input->post('descuento'),
                    "tipo"=>"mayor_costo"
                );
                $this->promociones->addReglas($data);
		$this->session->set_flashdata('message', 'Reglas de la promoción actualizadas satisfactoriamente');
		redirect('/promociones/editar/'.$id, 'location', 301);
	}
        
	public function sync_reglas()
	{
		$this->checkLogin();
		$id = $this->input->post('id');
		$data = [
			'almacenes' => $this->almacenes->get_combo_data(),
			'promocion' => $this->promociones->get($this->input->post('id'))
		];
		
		$this->promociones->sync_reglasAll($this->input->post());
		$this->session->set_flashdata('message', 'Reglas de la promoción actualizadas satisfactoriamente');
		redirect('/promociones/editar/'.$id, 'location', 301);
	}

	public function getAjaxData()
	{
		$start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength'); 

		$this->output->set_content_type('application/json')->set_output(json_encode($this->promociones->getAjaxData($start,$limit)));
	}


	public function getAjaxDataProductos()
	{
		$start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength'); 
		$id_promocion = $this->input->get('id_promocion'); 
		$search = $this->input->get('sSearch'); 

		$this->output->set_content_type('application/json')->set_output(json_encode($this->promociones->productospromocion($search,$id_promocion,$start,$limit)));
	}


	public function obtener()
	{
		$id_promocion = $this->input->post('id_promocion');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->promociones->obtener($id_promocion)));
	}

	public function obtenerHabilitados()
	{
            $user_id = $this->session->userdata('user_id');
            $almacen = $this->almacenes->get_almacen_usuario($user_id);
            if(!empty($almacen))
            {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->promociones->obtenerHabilitados($almacen->almacen_id)));
            }else{
                $this->output->set_content_type('application/json')->set_output(json_encode(array()));
            }
            
	}

	public function obtenerDetallePromocion()
	{
            $id_promocion = $this->input->post('id_promocion');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->promociones->obtenerDetallePromocion($id_promocion)));
	}

	public function validarProducto()
	{
            $id_promocion = $this->input->post('id_promocion');
            $id_producto = $this->input->post('id_producto');
            $valido = false;

            if(count($this->promociones->validarProducto($id_promocion, $id_producto)) > 0)
                $valido = true;

            $this->output->set_content_type('application/json')->set_output(json_encode(['valido' => $valido]));
        }
        
        public function validarProductoD()
	{
		$id_promocion = $this->input->post('id_promocion');
		$id_producto = $this->input->post('id_producto');
		$valido = false;

		if(count($this->promociones->validarProductoD($id_promocion, $id_producto)) > 0)
			$valido = true;
		
		$this->output->set_content_type('application/json')->set_output(json_encode(['valido' => $valido]));
	}
}
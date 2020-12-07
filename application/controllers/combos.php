<?php

class Combos extends CI_Controller 

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

            

            $this->load->model("productos_model",'productos');

            $this->productos->initialize($this->dbConnection);

            

            $this->load->model("impuestos_model",'impuestos');

            $this->impuestos->initialize($this->dbConnection);

            //modelo categorias
            $this->load->model("categorias_model",'categorias');
            $this->categorias->initialize($this->dbConnection);

            //Modelo unidades
            $this->load->model("unidades_model",'unidades');
            $this->unidades->initialize($this->dbConnection);

            //modelo almacenes
            $this->load->model("almacenes_model",'almacenes');
            $this->almacenes->initialize($this->dbConnection);
            
            //modelo opciones
            $this->load->model("opciones_model",'opciones');
            $this->opciones->initialize($this->dbConnection);


            $this->load->model("clientes_model",'clientes');

            $this->clientes->initialize($this->dbConnection);

            $this->load->model("lista_precios_model",'lista_precios');

            $this->lista_precios->initialize($this->dbConnection);

            $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');

            $this->lista_detalle_precios->initialize($this->dbConnection);
            
            



            $this->load->library('pagination');

            $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

            

            $idioma = $this->session->userdata('idioma');

            $this->lang->load('sima', $idioma);

        }


	public function nuevo(){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

        $error_upload = "";

        /*INSERTAR PRODUCTO*/
		if ($this->form_validation->run('productos') == true){

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';

            $config['max_size']	= '1024';

            $config['max_width']  = '1080';

            $config['max_height']  = '800';

            $image_name = "";

            $this->load->library('upload', $config);

            if(!empty($_FILES['imagen']['name'])){

                

                if (!$this->upload->do_upload('imagen')){

                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                }  else {

                    $upload_data = $this->upload->data();

                    $image_name = $upload_data['file_name'];

                    

                }

            }

            $active = isset($_POST['activo']) ? 1 : 0;

            $data = array(

                'imagen' => $image_name,

                 "nombre"        => $this->input->post('nombre'),

                 "codigo"        => $this->input->post('codigo'),

                 "descripcion" => $this->input->post('descripcion'),

                 "precio_venta"  	=> $this->input->post('precio'),

                 "precio_compra" => $this->input->post('precio_compra'),

                 "categoria_id"  	=> $this->input->post('categoria_id'),

                 "unidad_id"     => $this->input->post('unidad_id'),

                 "impuesto"  	=> $this->input->post('id_impuesto'),

                 'activo' => $active,

                 'material' => 1

            );

            $this->ingredientes->add($data, $this->session->userdata('user_id'));

            $this->session->set_flashdata('message', custom_lang('sima_ingredient_created_message', 'Ingrediente creado correctamente'));

            redirect('ingredientes/index');

        }

        $data = array();

        $data['data']['upload_error'] = $error_upload;

        $data['categorias'] = $this->categorias->get_combo_data();

        $data['unidades'] = $this->unidades->get_combo_data();

        $data['impuestos'] = $this->impuestos->get_combo_data();

        $data['almacenes'] = $this->almacenes->get_combo_data();

        $this->layout->template('member')->show('ingredientes/nuevo', array('data' => $data));

	}

    public function product_check($str){

        $id = $this->ingredientes->get_by_name($str);

        if(!empty($id)){

            $id_producto = $this->input->post('id');

            if(!empty($id_producto) && $id_producto == $id){

                return true;

            }

            $this->form_validation->set_message('product_check', 'El %s existe');

            return false;

        }

        return true;

    }

        

	public function get_ajax_data(){

        $this->output->set_content_type('application/json')->set_output(json_encode($this->ingredientes->get_ajax_data()));

    }

    public function productos_filter(){

        $result = array();

        $filter = $this->input->post('filter', TRUE);

        if(!empty($filter)){

            $this->productos->initialize($this->dbConnection);

            $result = $this->productos->get_term($filter, $this->session->userdata('user_id'));

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
       
    }

   

    public function get_by_category($category_id){
        $productos =  $this->productos->get_by_category($category_id, $this->session->userdata('user_id'));
        $this->output->set_content_type('application/json')->set_output(json_encode($productos));
    }

    
    //*Trae los productos filtrados por un termino*//
    public function filtro(){
        
        $result = array();

        $filter = $_GET['filter'];
        $codigo = $_GET['codigo'];

        if(!empty($filter)){

            $result = $this->productos->get_term_combo($filter,$codigo, $this->session->userdata('user_id'));

            if(!empty($result)){
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>1,'data'=>$result) )
                );
            }else{
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>0) )
                );
            }
            

        }

    }
    /*....................................................................................*/

        

	public function editar($id){

	    $error_upload = "";

             if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

		if ($this->form_validation->run('productos') == true)

                    {

                            $config['upload_path'] = 'uploads/';

                            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg';

                            $config['max_size']	= '1024';

                            $config['max_width']  = '580';

                            $config['max_height']  = '300';

                            $image_name = "";

                            $this->load->library('upload', $config);

                            if(!empty($_FILES['imagen']['name'])){

                                if (!$this->upload->do_upload('imagen')){

                                        $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                                }  else {

                                    $upload_data = $this->upload->data();

                                    $image_name = $upload_data['file_name'];

                                }

                            }

                            $active = isset($_POST['activo']) ? 1 : 0;



                            $data = array(

                                    'id' => $this->input->post('id'),

                                     "nombre"        => $this->input->post('nombre'),

                                     "codigo"        => $this->input->post('codigo'),

                                     "descripcion" => $this->input->post('descripcion'),

                                     "precio_venta"  	=> $this->input->post('precio'),

                                     "precio_compra" => $this->input->post('precio_compra'),

                                     "categoria_id"  	=> $this->input->post('categoria_id'),

                                     "unidad_id"     => $this->input->post('unidad_id'),

                                     "impuesto"  	=> $this->input->post('id_impuesto')

                                     ,'activo' => $active

                             );

                            

                            if(!empty($image_name))

                                {

                                    $data['imagen'] = $image_name;

                                }

                            

                            if($error_upload == ""){

                                $this->ingredientes->update($data, $this->session->userdata('user_id'));

                                $this->session->set_flashdata('message', custom_lang('sima_product_created_message', 'Producto creado correctamente'));

                                redirect('ingredientes/index');

                            }

                            

                    }

                    

                $data = array();   

		        $data['data']  = $this->ingredientes->get_by_id($id);

                $data['data']['upload_error'] = $error_upload;

                $data['categorias'] = $this->categorias->get_combo_data();

                $data['unidades'] = $this->unidades->get_combo_data();

                $data['almacenes'] = $this->almacenes->get_combo_data_stock_actual($id);

                $data['impuestos'] = $this->impuestos->get_combo_data();

                $this->layout->template('member')->show('ingredientes/editar', array('data' => $data));

	}

        

        public function detalles($id)

        {

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

            $data = $this->productos->get_by_id($id);

            $this->layout->template('member')->show('productos/detalles', array('data' => $data));

        }

	

	public function eliminar($id){	

        if (!$this->ion_auth->logged_in()){

			redirect('auth', 'refresh');

		}

		$this->ingredientes->delete($id);

		$this->session->set_flashdata('message', custom_lang('sima_ingredient_deleted_message', 'Se ha eliminado correctamente'));

		redirect("ingredientes");

	}


    public function filtro_prod_existencia(){

            $type = $this->input->get('almacen');

            $filter = $this->input->get('term', TRUE);

            

            $result = $this->productos->get_term_existencias($filter, $type);

            $this->output->set_content_type('application/json')->set_output(json_encode($result));

    }


       

}
<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Edwin P�rez
// Framework: Codeigniter
// Clase: controller/restablecer.php


class Restablecer extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');


        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);
        $this->load->model("restablecer_model",'restablecerModel');
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $this->restablecerModel->initialize($this->dbConnection);
        $this->load->helper(array('reinicio_sistema','reinicio_sistema'));
        //$this->load->model('ion_auth_model','ion_auth');
    }
    
    
    public function index() {

        //Authentication
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $data = [
            'almacenes' => $this->almacenes->get_combo_data()
        ];
        $data["data"] = [
            'categorias' => $this->categorias->getAllCategoriesNotProduct()
        ];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        //Show template
        if($this->session->userdata('is_admin') == "t"){
        	
            $this->layout->template('member')->show('restablecer/index',$data);
       }else{
          redirect(site_url('frontend/index'));
       }
    }
    
    public function resetEmail()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $existe = $this->restablecerModel->existeTabla($this->session->userdata('base_dato'));
        if(isset($_POST))
        {
            if($this->ion_auth->validar($_POST['email'], $_POST['password']))
            {
                $data = array(
                    "id_usuario"=>$this->session->userdata("user_id"),
                    "json"=>json_encode($_POST['data']),
                    "fecha"=> date("Y-m-d H:i:s")
                );
                $id = $this->restablecerModel->add($data);
                $mensaje="";
                if(!empty($id))
                {
                    try {
                        
                        //If checkbox checked delete data
                        if(isset($_POST['data']['clientes']) && $_POST['data']['clientes'] == 1){                
                            $this->restablecerModel->deleteClientes();
                        }

                        //If checkbox checked delete data
                        if(isset($_POST['data']['proveedores']) && $_POST['data']['proveedores'] == 1){            
                            $this->restablecerModel->deleteProveedores();
                        }

                        //If checkbox checked delete data
                        if(isset($_POST['data']['productos']) && $_POST['data']['productos'] == 1){
                            $this->restablecerModel->deleteProductos();
                            $this->session->unset_userdata('caja');
                            $this->session->set_userdata('caja',"");
                            
                        }

                        //If checkbox checked delete data
                        if (isset($_POST['data']['ventas'])) {
                            $almacen = array();
                            foreach($_POST['data']['ventas'] as $a)
                            {
                                $sql_consecutivos_almacen = "UPDATE almacen SET consecutivo = 1 WHERE id=".$a;
                                $this->dbConnection->query($sql_consecutivos_almacen);
                                array_push($almacen,$a);
                            }
                            $sql = $this->restablecerModel->deleteVentas($almacen);
                            //var_dump($sql);
                            $this->session->unset_userdata('caja');
                            $this->session->set_userdata('caja',"");
                        }

                        //If inventarios checked delete inventario and ventas
                        if (isset($_POST['data']['inventario'])) {
                            $almacen = array();
                            foreach($_POST['data']['inventario'] as $a)
                            {
                                array_push($almacen,$a);
                            }
                            //var_dump($almacen);
                            $this->restablecerModel->deleteInventarios($almacen);
                        }

                        if (isset($_POST['data']['movimientos'])) {
                            $almacen = array();
                            foreach($_POST['data']['movimientos'] as $a)
                            {
                                array_push($almacen,$a);
                            }
                            $this->restablecerModel->deleteMovimientos($almacen);
                        } 
                        if (isset($_POST['data']['ordenes'])) {
                            $almacen = array();
                            foreach($_POST['data']['ordenes'] as $a)
                            {
                                array_push($almacen,$a);
                            }
                            $this->restablecerModel->deleteOrdenes($almacen);
                        }

                        //If checkbox checked delete data vendedores
                        if (isset($_POST['data']['vendedores'])) {   
                            $almacen = array();
                            foreach($_POST['data']['vendedores'] as $a)
                            {
                                array_push($almacen,$a);
                            }            
                            $mensaje=$this->restablecerModel->deletevendedores($almacen);
                            if(!empty($mensaje)){
                                $mensaje="Los siguientes Vendedores no pueden ser eliminados:<br> ".$mensaje;
                            }
                        }

                        if (isset($_POST['data']['categorias'])) {   
                            $categorias = array();
                            foreach($_POST['data']['categorias'] as $a)
                            {
                                array_push($categorias,$a);
                            }            
                            $this->restablecerModel->deletecategorias($categorias);
                            
                        }
                                                
                        echo json_encode(array("resp"=>1,'mensaje'=>$mensaje));                        

                    } catch (Exception $e) {            

                        echo json_encode(array("resp"=>0));

                    }
                }
            }else{
                echo json_encode(array("resp"=>2));
            }
        }
    }
    
    //Delete data
    public function reset() {
                
        try {
                                     
            //If checkbox checked delete data
            if($this->input->post('clientes')){                
                $this->restablecerModel->deleteClientes();
            }

            //If checkbox checked delete data
            if($this->input->post('proveedores')){            
                $this->restablecerModel->deleteProveedores();
            }
            
            //If checkbox checked delete data
            if($this->input->post('ventas')){
                $this->restablecerModel->deleteVentas();
            }
            
            //If inventarios checked delete inventario and ventas
            if($this->input->post('inventarios')){
                $this->restablecerModel->deleteInventarios();
            }            
            
            //If checkbox checked delete data
            if($this->input->post('productos')){
                $this->restablecerModel->deleteProductos();
            }                        
            
            $this->output->set_output("successful");
                    
        } catch (Exception $e) {            
            
            $this->output->set_output("Error: ".$e);
            
        }
        
    }
    
    public function test() {        
        $table = $this->restablecerModel->test();               
        foreach ($table as $row)
        {
            echo $row->id." ";
            echo $row->id_Usuario."<br>";
        }
    }
    

}

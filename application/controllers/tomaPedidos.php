<?php
/**
 * Clase Productos para restaurantes
 * @author angeledugo@gmail.com
 */
class tomaPedidos extends CI_Controller {

    public function __construct(){

        parent::__construct();
        
        $usuario = $this->session->userdata('usuario');
        $this->user = $this->session->userdata('user_id');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);
        
        $this->load->model("ventas_model", 'ventas');
        $this->ventas->initialize($this->dbConnection);
        

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("secciones_almacen_model", 'secciones_almacen');
        $this->secciones_almacen->initialize($this->dbConnection);

        $this->load->model("mesas_secciones_model", 'mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        if((!empty($data_empresa['data']['tipo_negocio']))&&($data_empresa['data']['tipo_negocio']=="restaurante")){         
            $this->mesas_secciones->add_campo_comensales($this->dbConnection);
            $this->ventas->add_campo_comensales($this->dbConnection);
        }

        $this->load->model("vendedores_model", 'vendedor');
        $this->vendedor->initialize($this->dbConnection);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);        

        $this->load->model('crm_model');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        $this->load->model("Caja_model",'caja');
        $this->caja->initialize($this->dbConnection);
    }

    function index(){

        if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
        }
        //print_r($this->session->userdata);
        //die();
        /****si es estacion */      
        if($this->session->userdata('es_estacion_pedido')==1){
            //print_r($this->session->userdata);
            if($this->session->userdata('vendedor_estacion_actual_id')>=0){
                redirect("tomaPedidos/mesero");                
            }else{
                redirect("tomaPedidos/estacion_pedidos");
            }
        }

        //Puedo facturar?
        //$almacenActual = $this->dashboardModel->getAlmacenActual();
        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
    
        if(($puedofacturar==1)){    
            echo'
            <script>
                    alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");           
                    window.location="frontend/index"; 
            </script>';
        }


        //$mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1));
        $id_almacen_usuario = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        $secciones = $this->secciones_almacen->get_secciones_almacen(array('id_almacen'=>$id_almacen_usuario,'a.activo'=>1,'a.id !=' => -1));
        $mesas_secciones = array();
        foreach ($secciones as $key => $value) {
           $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1));
           foreach ($mesas_seccion as $key_mesas => $una_mesa) {
                $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa->id);
                if(empty($pedidos)){
                    $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>null),array('id'=>$una_mesa->id,'id_seccion'=>$value->id,'vendedor_estacion'=>-1));
                }
                $una_mesa->pedidos_en_mesa = false;
                if($pedidos >=1){
                    $una_mesa->pedidos_en_mesa = true;
                }

               $ordersInCommand  =$this->ordenes->verifyOrdersInCommand($value->id,$una_mesa->id);
               $una_mesa->orders_in_command = false;
                if($ordersInCommand >=1){
                    $una_mesa->orders_in_command = true;
                }
 
               $fechaCreacion = $this->ordenes->getFechaOrdenMesa($value->id,$una_mesa->id);

               $una_mesa->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';
               
                $mesas_secciones[$value->id][] = $una_mesa;
               
                //$mesas_secciones[$value->id]['estado'] = $this->ordenes->verificaEstado($value->id,$una_mesa->id);
           }
        }

        
        $data['mesas'] = $mesas_secciones;
        $data['zonas'] = $secciones;
        $data['ordenes_actuales'] = $this->secciones_almacen->get_secciones_almacen(array('id_almacen'=>$id_almacen_usuario));

        //print_r($data);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['datos_empresa'] = $data_empresa;

        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];  
        //$this->layout->template('new_layout')->show('toma_pedidos/tomar_pedidos',array('data' => $data));
        $data["payment-methods"] = get_curl("payment-methods",$this->session->userdata('token_api'));
        $data["data-currency"] = get_curl("data-currency",$this->session->userdata('token_api'));
        $data["customers"] =  (array) get_curl("customers",$this->session->userdata('token_api'));
        $data["store-id"] = $this->mi_empresa->get_store();
        $data['quick-service'] = $this->opciones->getOpcion('quick_service');
        $data['quick-service-command'] = $this->opciones->getOpcion('quick_service_command');
        $data['checkout_enabled'] = 'no';

        if($this->session->userdata("es_estacion_pedido") == 0){
            //$caja = $this->verify_state_box();
            //print_r($caja["estado_caja"]); die(); 
            $this->load->view('layouts/new_layout',array('data' => $data));
            $this->load->view('orden_compra/ventas',array('data' => $data));
            $this->load->view('toma_pedidos/tomar_pedidos',array('data' => $data));
            $this->load->view('layouts/newFooter',array('data' => $data));
            // if($caja["estado_caja"] == "abierta"){
            //     $this->load->view('layouts/new_layout',array('data' => $data));
            //     $this->load->view('orden_compra/ventas',array('data' => $data));
            //     $this->load->view('toma_pedidos/tomar_pedidos',array('data' => $data));
            //     $this->load->view('layouts/newFooter',array('data' => $data));
            // }else{
            //     $this->session->set_userdata('page_backup','tomar-pedido');
            //     redirect(site_url('caja/apertura'));
            // }  
        }else{
            $this->load->view('layouts/new_layout',array('data' => $data));
            $this->load->view('orden_compra/ventas',array('data' => $data));
            $this->load->view('toma_pedidos/tomar_pedidos',array('data' => $data));
            $this->load->view('layouts/newFooter',array('data' => $data));
        }
             
    }

    
    // function verify_state_box(){
    //     $data_empresa = $this->mi_empresa->get_data_empresa();
    //     $data["estado_caja"] = "cerrada";
    //       //verifico si la caja esta abierta
    //     if ($this->session->userdata('caja') != ""){
    //         $data["estado_caja"] = "abierta";
    //     }else{
    //         //verifico si hay caja abierta y no la tengo en session 
    //         //verifico si hay una caja abierta para el usuario
    //         //verifico si hay cierre automatico
    //         if ($data_empresa['data']['valor_caja'] == 'si') {
    //             // Si el cierre de caja es automatico           
    //             if ($data_empresa['data']['cierre_automatico'] == '1') {
    //                 $hoy = date("Y-m-d"); 
    //                 $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
    //             }else{
    //                 $where=array('id_Usuario'=>$this->session->userdata('user_id'));
    //             }
            
    //             $orderby_cierre="fecha desc, hora_apertura desc";
    //             $limit_cierre="1";
    //             $cierre_caja=$this->caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);
                            
    //             if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){             
    //                 $this->session->set_userdata('caja', $cierre_caja->id);
    //                 $data["estado_caja"] = "abierta";
    //             }  
    //         }else{
    //             $data["estado_caja"] = "abierta";
    //         }
    //     }

    //     return $data;
    // }


    public function estacion_pedidos()
    {   
        if($this->session->userdata('es_estacion_pedido')==1){          
            
            if($this->session->userdata('vendedor_estacion_actual_id')>=0){
                $data=array(
                    'id'=> $this->session->userdata('vendedor_estacion_actual_id'),
                    'sesion_estacion'=>0           
                );
                $this->vendedor->update($data);              
            }
        }        
        $this->session->unset_userdata('vendedor_estacion_actual_id');
        $this->session->unset_userdata('vendedor_estacion_actual_nombre');
        $this->session->unset_userdata('vendedor_estacion_actual_almacen');
        $this->session->unset_userdata('vendedor_estacion_actual_codigo');
       
         //Puedo facturar?
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
    
        if(($puedofacturar==1)){            
            
            echo'
            <script>
                    alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");           
                    window.location="frontend/index"; 
            </script>';
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['datos_empresa']=$data_empresa;
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $data['estado'] = $cuentaEstado["estado"];   
        
        $this->layout->template('new_layout')->show('toma_pedidos/estacion_pedidos',array('data' => $data));
    }

    public function mesero(){ 
            //print_r($this->session->userdata); die();
        $id_almacen_usuario = $this->dashboardModel->getAlmacenActual();
                  
        if($this->session->userdata('es_estacion_pedido')==1){

            if(isset($_POST['codigo'])){
                $clave=$_POST['codigo'];                       
            }else{              
                //verifico que este en session
                if(($this->session->userdata('vendedor_estacion_actual_id')>=0 || isset($_POST['codigo']))){
                    $clave=(isset($_POST['codigo'])) ? $this->input->post('codigo'): $this->session->userdata('vendedor_estacion_actual_codigo');                
                }else{               
                    redirect("tomaPedidos/estacion_pedidos");
                }
            }

            if((!empty($clave))&&(is_numeric($clave))&&(strlen($clave)==4)){
                    
                $vendedor=$this->vendedor->verificarclave(array('codigo'=>$clave,'almacen'=>$id_almacen_usuario));  
                //print_r($clave); die();
                $data=array(
                    'id'=> $vendedor[0]->id     
                );
                //$this->vendedor->update($data);

                $data['vendedor'] = $vendedor;
                $this->session->set_userdata('vendedor_estacion_actual_id', $vendedor[0]->id);
                $this->session->set_userdata('vendedor_estacion_actual_nombre', $vendedor[0]->nombre);
                $this->session->set_userdata('vendedor_estacion_actual_almacen', $vendedor[0]->almacen);
                $this->session->set_userdata('vendedor_estacion_actual_codigo', $vendedor[0]->codigo);
                $estacion="(vendedor_estacion IS NULL or vendedor_estacion=".$vendedor[0]->id.")";
                $vendedor=$vendedor[0]->id;
                $secciones = $this->secciones_almacen->get_secciones_almacen(array('id_almacen'=>$id_almacen_usuario,'a.activo'=>1));
                $mesas_secciones = array();
               
                //quitar el id del vendedor de las mesas que no uso
                foreach ($secciones as $key => $value) {                    
                    $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1,'vendedor_estacion'=>$vendedor),0);
                    foreach ($mesas_seccion as $key_mesas1 => $una_mesa1) {    
                        $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa1->id); 
                        if(empty($pedidos)){
                            $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>null),array('id'=>$una_mesa1->id,'id_seccion'=>$value->id));
                        }
                    }
                }

                //muestro las mesas Disponibles y las que yo ocupe
                foreach ($secciones as $key => $value) {
                    $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1),$estacion);
                    foreach ($mesas_seccion as $key_mesas => $una_mesa) {       
                        
                            $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa->id);
                            $una_mesa->pedidos_en_mesa = false;
                            if($pedidos >=1){
                                $una_mesa->pedidos_en_mesa = true;
                            }

                        $fechaCreacion = $this->ordenes->getFechaOrdenMesa($value->id,$una_mesa->id);

                        $una_mesa->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';

                            $mesas_secciones[$value->id][] = $una_mesa;                           
                    }
                }

                $data['mesas'] = $mesas_secciones;
                $data['zonas'] = $secciones;
                
                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data['datos_empresa'] = $data_empresa;
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $data['estado'] = $cuentaEstado["estado"];   

                $this->layout->template('new_layout')->show('toma_pedidos/mesero',array('data' => $data));

            } else{
                $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'El código no puede ser blanco, tiene que tener 4 Dígitos'));
                redirect("tomaPedidos/estacion_pedidos"); 
            } 
        }else{

        }
              
    }


    public function mesero2(){        
        //print_r($this->session->userdata); 
        //die();
        if($this->session->userdata('vendedor_estacion_actual_id') != '' && $this->vendedor->validate_sesion() == NULL){
            $this->session->unset_userdata('vendedor_estacion_actual_id');
            $this->session->unset_userdata('vendedor_estacion_actual_nombre');
            $this->session->unset_userdata('vendedor_estacion_actual_almacen');
            $this->session->unset_userdata('vendedor_estacion_actual_codigo');
            redirect("tomaPedidos/estacion_pedidos");
        }

        $estacion="";
        if($this->session->userdata('es_estacion_pedido')==1){
         
            if(($this->session->userdata('vendedor_estacion_actual_id')>=0 || isset($_POST['codigo']))){
                $clave=(isset($_POST['codigo'])) ? $this->input->post('codigo'): $this->session->userdata('vendedor_estacion_actual_codigo');                
            }else{               
               redirect("tomaPedidos/estacion_pedidos");
            }
        }
        
     
        
        if((!empty($clave))&&(is_numeric($clave))&&(strlen($clave)==4)){
            $id_almacen_usuario = $this->dashboardModel->getAlmacenActual();
            
            if(isset($_POST['codigo'])){
                $vendedor=$this->vendedor->verificarclave(array('codigo'=>$clave,'almacen'=>$id_almacen_usuario,'sesion_estacion'=>0));    
            } else{
                $vendedor=$this->session->userdata('vendedor_estacion_actual_id');
                $estacion="(vendedor_estacion IS NULL or vendedor_estacion=$vendedor)";
            }          
            
           
            if(!empty($vendedor)){
                if(isset($_POST['codigo'])){
                    //guardo en bd que esta activa la sesion
                    $data=array(
                        'id'=> $vendedor[0]->id,
                        'sesion_estacion'=>1          
                    );
                    $this->vendedor->update($data);

                    $data['vendedor'] = $vendedor;
                    
                    $this->session->set_userdata('vendedor_estacion_actual_id', $vendedor[0]->id);
                    $this->session->set_userdata('vendedor_estacion_actual_nombre', $vendedor[0]->nombre);
                    $this->session->set_userdata('vendedor_estacion_actual_almacen', $vendedor[0]->almacen);
                    $this->session->set_userdata('vendedor_estacion_actual_codigo', $vendedor[0]->codigo);
                    $estacion="(vendedor_estacion IS NULL or vendedor_estacion=".$vendedor[0]->id.")";
                }
                
                $secciones = $this->secciones_almacen->get_secciones_almacen(array('id_almacen'=>$id_almacen_usuario,'a.activo'=>1));
                $mesas_secciones = array();

                if($this->session->userdata('vendedor_estacion_actual_id')>=0){
                    $vendedor=$this->session->userdata('vendedor_estacion_actual_id');
                }else
                {
                    $vendedor=-1;
                }

                //quitar el id del vendedor de las mesas que no uso
                foreach ($secciones as $key => $value) {                    
                    $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1,'vendedor_estacion'=>$vendedor),0);
                    foreach ($mesas_seccion as $key_mesas1 => $una_mesa1) {    
                        $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa1->id); 
                        if(empty($pedidos)){
                            $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>null),array('id'=>$una_mesa1->id,'id_seccion'=>$value->id));
                        }
                    }
                }

                //muestro las mesas Disponibles y las que yo ocupe
                foreach ($secciones as $key => $value) {
                    $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1),$estacion);
                    foreach ($mesas_seccion as $key_mesas => $una_mesa) {       
                        
                            $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa->id);
                            $una_mesa->pedidos_en_mesa = false;
                            if($pedidos >=1){
                                $una_mesa->pedidos_en_mesa = true;
                            }

                        $fechaCreacion = $this->ordenes->getFechaOrdenMesa($value->id,$una_mesa->id);

                        $una_mesa->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';

                            $mesas_secciones[$value->id][] = $una_mesa;                           
                    }
                }

                $data['mesas'] = $mesas_secciones;
                $data['zonas'] = $secciones;
                
                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data['datos_empresa'] = $data_empresa;
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $data['estado'] = $cuentaEstado["estado"];   

                $this->layout->template('new_layout')->show('toma_pedidos/mesero',array('data' => $data));

            }else{
                $vendedor=$this->vendedor->verificarclave(array('codigo'=>$clave,'almacen'=>$id_almacen_usuario,'sesion_estacion'=>1));
                if(!empty($vendedor)){
                    $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'Hemos detectado que exíste otra conexión activa en este momento y por su seguridad no podemos permitir el ingreso.<br>Le recordamos que solo puede mantener una sesión activa con su código'));
                }else{
                    $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'No se ha podido encontrar el código'));
                }                
                redirect("tomaPedidos/estacion_pedidos");
            }
        }else{
            $this->session->set_flashdata('message1', custom_lang('sima_category_deleted_message', 'El código no puede ser blanco, tiene que tener 4 Dígitos'));
            redirect("tomaPedidos/estacion_pedidos");
        }
    }

    public function salir_mesero(){

        if($this->session->userdata('es_estacion_pedido')==1){          
            if($this->session->userdata('vendedor_estacion_actual_id')>=0){
                $data=array(
                    'id'=> $this->session->userdata('vendedor_estacion_actual_id'),
                    'sesion_estacion'=>0           
                );                
                $this->vendedor->update($data);

                $secciones = $this->secciones_almacen->get_secciones_almacen(array('id_almacen'=>$this->session->userdata('vendedor_estacion_actual_almacen')));
                $mesas_secciones = array();

                //quitar el id del vendedor de las mesas que no uso
                foreach ($secciones as $key => $value) {                    
                    $mesas_seccion =$this->mesas_secciones->get_mesa_secciones(array('id_seccion'=>$value->id,'activo'=>1,'vendedor_estacion'=>$this->session->userdata('vendedor_estacion_actual_id')),0);
                    foreach ($mesas_seccion as $key_mesas1 => $una_mesa1) {    
                        $pedidos = $this->ordenes->verificaEstado($value->id,$una_mesa1->id); 
                        if(empty($pedidos)){
                            $this->mesas_secciones->actualizar_mesa(array('vendedor_estacion'=>null),array('id'=>$una_mesa1->id,'id_seccion'=>$value->id));
                        }
                    }
                }
            }
        }

       
        $this->session->unset_userdata('vendedor_estacion_actual_id');
        $this->session->unset_userdata('vendedor_estacion_actual_nombre');
        $this->session->unset_userdata('vendedor_estacion_actual_almacen');
        $this->session->unset_userdata('vendedor_estacion_actual_codigo');

        redirect("tomaPedidos/estacion_pedidos");
    }
}

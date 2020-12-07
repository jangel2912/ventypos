<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class PagoLicencia extends CI_Controller {

    var $dbConnection;
    var $dbConnectionp;
    var $dbConnectiond;
    function __construct() {

        parent::__construct();

        $this->load->model('backend/db_config/db_config_model', "dbconfig");
        
        $usuario = "vendtyMaster";
        $clave = "ro_ar_8027*_na";
        $servidor = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $base_dato = "vendty2";
        /* $usuario = "root";
        $clave = "";        
        $servidor = "localhost"; 
        $base_dato = "vendty2";*/

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("licencia_model", 'licencias');
        $this->licencias->initialize($this->dbConnection);

        $this->load->model("crm_facturas_model", 'facturas');
        $this->facturas->initialize($this->dbConnection);

        $this->load->model("crm_model", 'crm_model');
        
        $this->load->model("crm_empresas_clientes_model", 'crm_empresas_clientes');
       
        $this->load->model("crm_licencia_model", 'crm_licencia_model');

        $this->load->model("crm_licencias_empresa_model", 'crm_licencias_empresa');
               

    }

    public function response(){ 

        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $referencia_aux = explode('Licencia Vendty',$_POST['x_description']);
        $licencia = explode('-',$referencia_aux[1]);
        $tipo_documento_user=$_POST['x_customer_doctype'];
        $numero_documento_user=$_POST['x_customer_document'];
        $nombre_user=$_POST['x_customer_name'];
        $apellido_user=$_POST['x_customer_lastname'];
        $email_user=$_POST['x_customer_email'];
        $telefono_user=$_POST['x_customer_phone'];
        $direccion_user=$_POST['x_customer_address'];
        $transaction_id=$_POST['x_transaction_id'];
        $ref_payco=$_POST['x_ref_payco'];
        $info_adicional="response pagoLicencia";        
        $extra1 = $_POST['x_extra1'];
        $currency=$_POST['x_currency_code'];
        $total_pais=$_POST['x_amount_country'];
        $metodopago=$_POST['x_franchise'];
        $pago_por=$_POST['x_bank_name'];
        
        //licencia=1386_2 - 4024_2
         //extra1=1386-5000 _ 4024-5000
        if(count($licencia) != 1){
            $id_licencia = array();
            $valor = array();
            $valor_dolares = array();
            $sw = 1;
            $extra1 = explode('_',$_POST['x_extra1']);

            for($x=0; $x<count($licencia); $x++){                                
                $v = explode('_',$licencia[$x]);
                $ex = explode('-',$extra1[$x]);
                if($currency=="USD"){
                    array_push($valor, $ex[1]);
                }else{
                    array_push($valor, $v[2]);
                }
                array_push($id_licencia, $v[0]);                
                array_push($valor_dolares, $v[1]);
            }
        }else{
            $sw = 0;
            $valor = $_POST['x_amount'];
            $valor_dolares = $_POST['x_amount'];            
            
            if($currency=="USD"){
                $valor=$extra1;
            }
            //$valor=5000;
            $v = explode('_',$referencia_aux[1]);
            $id_licencia = (count($v) != 1) ? $v[0] : $referencia_aux[1];
        }
        
        $estado = ($estado == 1) ? 1 : 3;    
        $forma_pago=3;        

        if($estado == 1){
            
            //verifico si es un array o no la licencia             
            $id_licencia_array=is_array($id_licencia) ? $id_licencia[0] : $id_licencia; 

             //verificar si llego el pago
            $hoy=date('Y-m-d');
            $existe_pago=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia_array,'estado_pago'=>1));
            
            if($existe_pago==0){
                $pago=$this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw, $transaction_id, $ref_payco,$forma_pago, $valor_dolares,$total_pais,$metodopago,$pago_por,$currency);
                
                if($pago!=0){
                    $bduser=$this->licencias->buscarBD($id_licencia_array);
                    $idbd=$bduser[0]['id'];     
                    $this->licencias->updateEstadoBD2($idbd);

                    //**verifico si tiene informacion en crm_info_facturacion sino tomo los valores de epayco y los guardo */
                    $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia_array));	            
                    $empresa=$datos_licencia[0]->idempresas_clientes;	
                    $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente'=>$empresa));

                    if(empty($datos_empresas)){
                    //guardar la informacion del cliente que viene de epayco               
                        $this->crm_empresas_clientes->update_info_factura_cliente(
                            array(
                                'nombre_empresa' => $nombre_user." ".$apellido_user,
                                'tipo_identificacion' => $tipo_documento_user,
                                'numero_identificacion' => $numero_documento_user,
                                'direccion' => $direccion_user,
                                'telefono' => $telefono_user,                        
                                'correo' => $email_user,                                              
                                'contacto' => $nombre_user." ".$apellido_user                      
                            ),
                            array('id_db_config' => $idbd)
                        );                
                    }
                    //Cambiar las fechas de bodegas si las hubiera
                    $sqlbodegas="SELECT * FROM ".$bduser[0]['base_dato'].".almacen WHERE bodega=1";
                    $bodegas=$this->db->query($sqlbodegas)->result_array();     
                
                    if($sw != 0){                  
                        $pagos=explode(",", $pago);
                        $cantidadPlanBodega=0;
                        for ($x=0; $x<count($id_licencia); $x++){
                            $id = $id_licencia[$x];
                            $pago = $pagos[$x];

                            //cantidad de bodegas
                            $datos_licencia_b = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id));	            
                            $detalle_plan=$this->crm_model->get_detalle_plan("where id_plan=".$datos_licencia_b[0]->planes_id." and nombre_campo='bodegas'");                     
                            $cantidadPlanBodega += (!empty($detalle_plan[0]->valor))?$detalle_plan[0]->valor:0;
                            
                            require_once('job.php');
                            $email = new Job();
                            $email->emailConfirmarPago($id);   
                            //verificar nuevamente que no haya factura asociada                          
                            $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id,'estado_pago'=>1,'id_factura_licencia !='=>''));
                            if($existe_pago2==0){        
                                //generando la factura
                                require_once('administracion_vendty/facturas_licencia.php');
                                $factura = new Facturas_licencia();
                                /*if($idbd == '18318') {
                                    $facturag = $factura->generar_factura_electronica($id,$pago);
                                } else {
                                    $facturag = $factura->generar_factura_de_licencia($id,$pago);
                                }*/
                                $facturag = $factura->generar_factura_electronica($id,$pago);

                                if(!empty($facturag)){
                                    //$email->emailFacturaPago($facturag);                                
                                }   
                            }
                        }                   
                        //actualizar fechas bodegas
                        if(!empty($bodegas)){
                            $i=0;                        
                            //busco licencia asociada a ese almacen
                            foreach ($bodegas as $key => $value) {                        
                                if($i<$cantidadPlanBodega){                                                    
                                    //licencias asociadas
                                    $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config'=>$idbd,'id_almacen'=>$value['id']));	 
                                    
                                    if(empty($datos_licencia_bodegas)){//se crea la licencia asociada                                
                                        $planB=17;
                                        switch ($datos_licencia[0]->dias_vigencia) {
                                            case 30:
                                                $planB=17;
                                                break;
                                            case 90:
                                                $planB=16;
                                                break;
                                            case 365:
                                                $planB=15;
                                                break;
                                            default:
                                                $planB=16;
                                                break;
                                        }
                                    
                                        $datosli=array(
                                            'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                            ,'planes_id' => $planB
                                            ,'fecha_creacion' => date('Y-m-d H:i:s')
                                            ,'creado_por' =>$datos_licencia[0]->creado_por
                                            ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                            ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento
                                            ,'id_db_config' => $idbd
                                            ,'id_almacen' => $value['id']
                                            ,'estado_licencia' => 1                                
                                        );
                                        $this->crm_licencia_model->agregar_licencia($datosli);
                                    }else{
                                        //cambiar las fechas                         
                                        $datosli=array(  
                                            'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                            ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                            ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento      
                                            ,'fecha_modificacion' => date('Y-m-d H:i:s')                          
                                            ,'estado_licencia' => 1                                                                
                                        );                                
                                        $this->crm_licencias_empresa->update($datosli);
                                    }
                                }                       
                                $i++;
                            }
                        }

                    }else{

                        require_once('job.php');
                        $email = new Job();
                        $email->emailConfirmarPago($id_licencia);           
                        //generando la factura
                        require_once('administracion_vendty/facturas_licencia.php');
                        $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia_array,'estado_pago'=>1,'id_factura_licencia !='=>''));                              
                    
                        $bduser=$this->licencias->buscarBD($id_licencia);
                        $idbd=$bduser[0]['id'];

                        if($existe_pago2 == 0){  
                            $factura = new Facturas_licencia();                        

                            /*if($idbd == '18318') {
                                $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            } else {
                                $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            }*/
                            $facturag = $factura->generar_factura_electronica($id_licencia,$pago);

                            if(!empty($facturag)){
                                //$email->emailFacturaPago($facturag);
                                //cambiar las fechas de licencias de bodegas
                                if(!empty($bodegas)){
                                    //busco detalle del plan
                                    $detalle_plan=$this->crm_model->get_detalle_plan("where id_plan=".$datos_licencia[0]->planes_id." and nombre_campo='bodegas'");                    
                                    $i=0;
                                    $cantidadPlanBodega=!empty($detalle_plan[0]->valor)?$detalle_plan[0]->valor:0;
                                    //busco licencia asociada a ese almacen
                                    foreach ($bodegas as $key => $value) {
                                    
                                        if($i<$cantidadPlanBodega){                                                    
                                            //licencias asociadas
                                            $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config'=>$idbd,'id_almacen'=>$value['id']));	 
                                            
                                            if(empty($datos_licencia_bodegas)){//se crea la licencia asociada                                
                                                $planB=17;
                                                switch ($datos_licencia[0]->dias_vigencia) {
                                                    case 30:
                                                        $planB=17;
                                                        break;
                                                    case 90:
                                                        $planB=16;
                                                        break;
                                                    case 365:
                                                        $planB=15;
                                                        break;
                                                    default:
                                                        $planB=16;
                                                        break;
                                                }
                                            
                                                $datosli=array(
                                                    'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                                    ,'planes_id' => $planB
                                                    ,'fecha_creacion' => date('Y-m-d H:i:s')
                                                    ,'creado_por' =>$datos_licencia[0]->creado_por
                                                    ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                    ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento
                                                    ,'id_db_config' => $idbd
                                                    ,'id_almacen' => $value['id']
                                                    ,'estado_licencia' => 1                                
                                                );
                                                $this->crm_licencia_model->agregar_licencia($datosli);
                                            }else{
                                                //cambiar las fechas                         
                                                $datosli=array(  
                                                    'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                                    ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                    ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento      
                                                    ,'fecha_modificacion' => date('Y-m-d H:i:s')                          
                                                    ,'estado_licencia' => 1                                                                
                                                );
                                            
                                                $this->crm_licencias_empresa->update($datosli);
                                            }
                                        }                       
                                        $i++;
                                    }
                                }
                            }
                        }
                    } 
                }else{
                    if($id_licencia==11152){
                        print_r($_POST); die();
                    }                    
                } 
            }
        }              
        redirect("frontend/index");
    }

    public function responseDos(){
        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $valor = $_POST['x_amount'];
        $referencia_aux = explode('Licencia Vendty',$_POST['x_description']); 
        $referencia_aux = explode('-',$referencia_aux[1]);
        $id_licencia = $referencia_aux[0];
        $id_plan = $referencia_aux[1];                
        $tipo_documento_user=$_POST['x_customer_doctype'];
        $numero_documento_user=$_POST['x_customer_document'];
        $nombre_user=$_POST['x_customer_name'];
        $apellido_user=$_POST['x_customer_lastname'];
        $email_user=$_POST['x_customer_email'];
        $telefono_user=$_POST['x_customer_phone'];
        $direccion_user=$_POST['x_customer_address'];
        $transaction_id=$_POST['x_transaction_id'];
        $ref_payco=$_POST['x_ref_payco'];
        $info_adicional="response2 pagoLicencia";
        $extra1 = $_POST['x_extra1'];
        $currency=$_POST['x_currency_code'];
        $total_pais=$_POST['x_amount_country'];
        $metodopago=$_POST['x_franchise'];
        $pago_por=$_POST['x_bank_name'];
        $valor_dolares=$valor;
        $estado = ($estado == 1) ? 1 : 3;
        $forma_pago=3;        
        $idbd="";

        if($currency=="USD"){            
            $valor=$extra1;
        }
        

        if($estado == 1){           
            //verificar si llego el pago
            $hoy=date('Y-m-d');
            $existe_pago=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia,'estado_pago'=>1));
            
            if($existe_pago==0){
                
                //email              
                require_once('job.php');
                $email = new Job();

                $planActual=$this->licencias->getPlanActual($id_licencia);
                $pago=$this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, 0, $transaction_id, $ref_payco,$forma_pago, $valor_dolares,$total_pais,$metodopago,$pago_por,$currency);       
                
                //PASAR A PRODUCCIÓN      
                $migrar=is_array($id_licencia) ? 1 : 0; 

                if(($migrar==0)&&($planActual==1)){    

                    $bduser=$this->licencias->buscarBD($id_licencia); 
                    $nombrebd=$bduser[0]['base_dato'];           
                    $idbd=$bduser[0]['id'];
                    $email1=explode("vendty2_db_",$nombrebd);                                           
                    
                    //$this->licencias->produccion($nombrebd);   
                    $data = array(
                        'origen' => 2,
                        'destino' => 8,
                        'dbname' => $email1[1]
                    );
                   
                    $migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api'));
                    if(isset($migrada->status) && isset($migrada->description)){
                        if(!$migrada->status && $migrada->description == "Verifica los datos enviados"){
                            $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
                        }
                    } else {
                        $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
                    }
                    //if($migrada->status){
                        //if($migrada->description=='ok'){
                            $this->licencias->updateEstadoBD($idbd);   
                            //modifico las fechas licencia
                            $plan=$this->crm_model->get_planes(array('id'=>$id_plan));
                            $tiempo=$plan[0]->dias_vigencia;							
                            $this->licencias->updateLicencianuevo($id_licencia, $id_plan,$tiempo);    	
                            
                            //email de bienvenida                                 
                            $email->BienvenidoaVendty($idbd);   
                        //}
                    //}

                }            
                
                //**tomo los valores de epayco y los guardo */
                //if($migrada->status){
                    //if($migrada->description=='ok'){
                        $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia));	            
                        $empresa=$datos_licencia[0]->idempresas_clientes;	
                        $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente'=>$empresa));
                        //buscar tipo negocio
                        //$sqlinsertdb="SELECT valor_opcion FROM ".$nombrebd.".opciones WHERE nombre_opcion='tipo_negocio'";
                        //$sqlinsertdb=$this->db->query($sqlinsertdb)->result_array(); 
                        //$tipo_negocio=$sqlinsertdb[0]['valor_opcion'];
                        //$datos_bd_acti=array();
                        if(empty($datos_empresas)){
                            //guardar la informacion del cliente que viene de epayco               
                            $this->crm_empresas_clientes->update_info_factura_cliente(
                                array(
                                    'nombre_empresa' => $nombre_user." ".$apellido_user,
                                    'tipo_identificacion' => $tipo_documento_user,
                                    'numero_identificacion' => $numero_documento_user,
                                    'direccion' => $direccion_user,
                                    'telefono' => $telefono_user,                        
                                    'correo' => $email_user,                                              
                                    'contacto' => $nombre_user." ".$apellido_user                      
                                ),
                                array('id_db_config' => $idbd)
                            );     
                            
                        }
                        // insertar datos en bd_activa
                        // $this->crm_model->insert_db_activa_info($datos_bd_acti);

                        //email pago
                        $email->emailConfirmarPago($id_licencia);                                      
                        //generando la factura
                        $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia,'estado_pago'=>1,'id_factura_licencia !='=>''));                              
                        
                        if($existe_pago2==0){  
                            require_once('administracion_vendty/facturas_licencia.php');
                            $factura = new Facturas_licencia();
                            //$facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            /*if($idbd == '18318') {
                                $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            } else {
                                $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            } */
                            $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            if(!empty($facturag)){
                                $email->emailFacturaPago($facturag);
                            }
                        }
                        
                        $this->ion_auth->logout(); 
                        redirect("auth/login");  
                    //}
                //} 
            }                   
        }
        
        redirect("frontend/index");
    }

    public function responseCredit(){

        $usuario = 'vendtyMaster';
        $clave = 'ro_ar_8027*_na';
        $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
        /*$usuario = 'root';
        $clave = '';
        $servidor = 'localhost';*/
        $base_dato = 'vendty2';

        $conn = @mysql_connect($servidor, $usuario, $clave);
        mysql_select_db($base_dato, $conn);
        $response = json_encode($_POST);
        mysql_query("INSERT INTO `response` (`response`)VALUES ('$response');", $conn);
        $id_suscripcion = explode('-',$_POST['x_id_factura'])[0];
        $sql = "SELECT * FROM epayco_suscripcion WHERE suscripcion_id = '$id_suscripcion' LIMIT 1;";
        $result = mysql_query($sql, $conn);
        while ($row = mysql_fetch_array($result)) {
            $licenciaGuardada = $row["licencia"];
        }
        $plan = "SELECT id FROM crm_planes WHERE nombre_plan = '".$_POST['x_description']."' LIMIT 1";
        $result = mysql_query($plan, $conn);
        while ($row = mysql_fetch_array($result)) {
            $idplan = $row["id"];
        }
        mysql_close($conn);

        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $referencia_aux = explode('Licencia Vendty',$licenciaGuardada);
        $licencia = explode('-',$referencia_aux[1]);
        $tipo_documento_user=$_POST['x_customer_doctype'];
        $numero_documento_user=$_POST['x_customer_document'];
        $nombre_user=$_POST['x_customer_name'];
        $apellido_user=$_POST['x_customer_lastname'];
        $email_user=$_POST['x_customer_email'];
        $telefono_user=$_POST['x_customer_phone'];
        $direccion_user=$_POST['x_customer_address'];
        $transaction_id=$_POST['x_transaction_id'];
        $ref_payco=$_POST['x_ref_payco'];
        $info_adicional="response pagoLicencia";        
        $extra1 = $_POST['x_extra1'];
        $currency=$_POST['x_currency_code'];
        $total_pais=$_POST['x_amount_country'];
        $metodopago=$_POST['x_franchise'];
        $pago_por=$_POST['x_bank_name'];
        
        //licencia=1386_2 - 4024_2
         //extra1=1386-5000 _ 4024-5000
        if(count($licencia) != 1){
            $id_licencia = array();
            $valor = array();
            $valor_dolares = array();
            $sw = 1;
            $extra1 = explode('_',$_POST['x_extra1']);

            for($x=0; $x<count($licencia); $x++){                                
                $v = explode('_',$licencia[$x]);
                $ex = explode('-',$extra1[$x]);
                if($currency=="USD"){
                    array_push($valor, $ex[1]);
                }else{
                    array_push($valor, $v[2]);
                }
                array_push($id_licencia, $v[0]);                
                array_push($valor_dolares, $v[1]);
            }
        }else{
            $sw = 0;
            $valor = $_POST['x_amount'];
            $valor_dolares = $_POST['x_amount'];            
            
            if($currency=="USD"){
                $valor=$extra1;
            }
            //$valor=5000;
            $v = explode('_',$referencia_aux[1]);
            $id_licencia = (count($v) != 1) ? $v[0] : $referencia_aux[1];
        }
        
        $estado = ($estado == 1) ? 1 : 3;    
        $forma_pago=3;        

        if($estado == 1){
            
            //verifico si es un array o no la licencia             
            $id_licencia_array=is_array($id_licencia) ? $id_licencia[0] : $id_licencia; 

             //verificar si llego el pago
            $hoy=date('Y-m-d');
            $existe_pago=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia_array,'estado_pago'=>1));
            
            if($existe_pago==0){
                $pago=$this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw, $transaction_id, $ref_payco,$forma_pago, $valor_dolares,$total_pais,$metodopago,$pago_por,$currency);
                
                if($pago!=0){
                    $bduser=$this->licencias->buscarBD($id_licencia_array);
                    $idbd=$bduser[0]['id'];     
                    $this->licencias->updateEstadoBD2($idbd);

                    //**verifico si tiene informacion en crm_info_facturacion sino tomo los valores de epayco y los guardo */
                    $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia_array));	            
                    $empresa=$datos_licencia[0]->idempresas_clientes;	
                    $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente'=>$empresa));

                    if(empty($datos_empresas)){
                    //guardar la informacion del cliente que viene de epayco               
                        $this->crm_empresas_clientes->update_info_factura_cliente(
                            array(
                                'nombre_empresa' => $nombre_user." ".$apellido_user,
                                'tipo_identificacion' => $tipo_documento_user,
                                'numero_identificacion' => $numero_documento_user,
                                'direccion' => $direccion_user,
                                'telefono' => $telefono_user,                        
                                'correo' => $email_user,                                              
                                'contacto' => $nombre_user." ".$apellido_user                      
                            ),
                            array('id_db_config' => $idbd)
                        );                
                    }
                    //Cambiar las fechas de bodegas si las hubiera
                    $sqlbodegas="SELECT * FROM ".$bduser[0]['base_dato'].".almacen WHERE bodega=1";
                    $bodegas=$this->db->query($sqlbodegas)->result_array();     
                
                    if($sw != 0){                  
                        $pagos=explode(",", $pago);
                        $cantidadPlanBodega=0;
                        for ($x=0; $x<count($id_licencia); $x++){
                            $id = $id_licencia[$x];
                            $pago = $pagos[$x];

                            //cantidad de bodegas
                            $datos_licencia_b = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id));	            
                            $detalle_plan=$this->crm_model->get_detalle_plan("where id_plan=".$datos_licencia_b[0]->planes_id." and nombre_campo='bodegas'");                     
                            $cantidadPlanBodega += (!empty($detalle_plan[0]->valor))?$detalle_plan[0]->valor:0;
                            
                            require_once('job.php');
                            $email = new Job();
                            $email->emailConfirmarPago($id);   
                            //verificar nuevamente que no haya factura asociada                          
                            $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id,'estado_pago'=>1,'id_factura_licencia !='=>''));
                            if($existe_pago2==0){        
                                //generando la factura
                                require_once('administracion_vendty/facturas_licencia.php');
                                $factura = new Facturas_licencia();

                                /*if($idbd == '18318') {
                                    $facturag = $factura->generar_factura_electronica($id,$pago);
                                } else {
                                    $facturag=$factura->generar_factura_de_licencia($id,$pago);
                                }*/
                                $facturag = $factura->generar_factura_electronica($id,$pago);

                                if(!empty($facturag)){
                                    $email->emailFacturaPago($facturag);                                
                                }   
                            }
                        }                   
                        //actualizar fechas bodegas
                        if(!empty($bodegas)){
                            $i=0;                        
                            //busco licencia asociada a ese almacen
                            foreach ($bodegas as $key => $value) {                        
                                if($i<$cantidadPlanBodega){                                                    
                                    //licencias asociadas
                                    $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config'=>$idbd,'id_almacen'=>$value['id']));	 
                                    
                                    if(empty($datos_licencia_bodegas)){//se crea la licencia asociada                                
                                        $planB=17;
                                        switch ($datos_licencia[0]->dias_vigencia) {
                                            case 30:
                                                $planB=17;
                                                break;
                                            case 90:
                                                $planB=16;
                                                break;
                                            case 365:
                                                $planB=15;
                                                break;
                                            default:
                                                $planB=16;
                                                break;
                                        }
                                    
                                        $datosli=array(
                                            'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                            ,'planes_id' => $planB
                                            ,'fecha_creacion' => date('Y-m-d H:i:s')
                                            ,'creado_por' =>$datos_licencia[0]->creado_por
                                            ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                            ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento
                                            ,'id_db_config' => $idbd
                                            ,'id_almacen' => $value['id']
                                            ,'estado_licencia' => 1                                
                                        );
                                        $this->crm_licencia_model->agregar_licencia($datosli);
                                    }else{
                                        //cambiar las fechas                         
                                        $datosli=array(  
                                            'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                            ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                            ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento      
                                            ,'fecha_modificacion' => date('Y-m-d H:i:s')                          
                                            ,'estado_licencia' => 1                                                                
                                        );                                
                                        $this->crm_licencias_empresa->update($datosli);
                                    }
                                }                       
                                $i++;
                            }
                        }

                    }else{

                        require_once('job.php');
                        $email = new Job();
                        $email->emailConfirmarPago($id_licencia);           
                        //generando la factura
                        require_once('administracion_vendty/facturas_licencia.php');
                        $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia_array,'estado_pago'=>1,'id_factura_licencia !='=>''));                              
                    
                        $bduser=$this->licencias->buscarBD($id_licencia);
                        $idbd=$bduser[0]['id'];

                        if($existe_pago2==0){  
                            $factura = new Facturas_licencia();                        
                            //$facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            /*if($idbd == '18318') {
                                $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            } else {
                                $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            }*/
                            $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            if(!empty($facturag)){
                                $email->emailFacturaPago($facturag);
                                //cambiar las fechas de licencias de bodegas
                                if(!empty($bodegas)){
                                    //busco detalle del plan
                                    $detalle_plan=$this->crm_model->get_detalle_plan("where id_plan=".$datos_licencia[0]->planes_id." and nombre_campo='bodegas'");                    
                                    $i=0;
                                    $cantidadPlanBodega=!empty($detalle_plan[0]->valor)?$detalle_plan[0]->valor:0;
                                    //busco licencia asociada a ese almacen
                                    foreach ($bodegas as $key => $value) {
                                    
                                        if($i<$cantidadPlanBodega){                                                    
                                            //licencias asociadas
                                            $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config'=>$idbd,'id_almacen'=>$value['id']));	 
                                            
                                            if(empty($datos_licencia_bodegas)){//se crea la licencia asociada                                
                                                $planB=17;
                                                switch ($datos_licencia[0]->dias_vigencia) {
                                                    case 30:
                                                        $planB=17;
                                                        break;
                                                    case 90:
                                                        $planB=16;
                                                        break;
                                                    case 365:
                                                        $planB=15;
                                                        break;
                                                    default:
                                                        $planB=16;
                                                        break;
                                                }
                                            
                                                $datosli=array(
                                                    'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                                    ,'planes_id' => $planB
                                                    ,'fecha_creacion' => date('Y-m-d H:i:s')
                                                    ,'creado_por' =>$datos_licencia[0]->creado_por
                                                    ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                    ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento
                                                    ,'id_db_config' => $idbd
                                                    ,'id_almacen' => $value['id']
                                                    ,'estado_licencia' => 1                                
                                                );
                                                $this->crm_licencia_model->agregar_licencia($datosli);
                                            }else{
                                                //cambiar las fechas                         
                                                $datosli=array(  
                                                    'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                                    ,'fecha_inicio_licencia' =>$datos_licencia[0]->fecha_inicio_licencia
                                                    ,'fecha_vencimiento' =>$datos_licencia[0]->fecha_vencimiento      
                                                    ,'fecha_modificacion' => date('Y-m-d H:i:s')                          
                                                    ,'estado_licencia' => 1                                                                
                                                );
                                            
                                                $this->crm_licencias_empresa->update($datosli);
                                            }
                                        }                       
                                        $i++;
                                    }
                                }
                            }
                        }
                    } 
                }else{
                    if($id_licencia==11152){
                        print_r($_POST); die();
                    }                    
                } 
            }
        }                            
    }

    public function responseCreditDos(){

        $usuario = 'vendtyMaster';
        $clave = 'ro_ar_8027*_na';
        $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
        /*$usuario = 'root';
        $clave = '';
        $servidor = 'localhost';*/
        $base_dato = 'vendty2';

        $conn = @mysql_connect($servidor, $usuario, $clave);
        mysql_select_db($base_dato, $conn);
        $response = json_encode($_POST);
        mysql_query("INSERT INTO `response` (`response`)VALUES ('$response');", $conn);
        $id_suscripcion = explode('-',$_POST['x_id_factura'])[0];
        $sql = "SELECT * FROM epayco_suscripcion WHERE suscripcion_id = '$id_suscripcion' LIMIT 1;";
        $result = mysql_query($sql, $conn);
        while ($row = mysql_fetch_array($result)) {
            $licenciaGuardada = $row["licencia"];
        }
        $plan = "SELECT id FROM crm_planes WHERE nombre_plan = '".$_POST['x_description']."' LIMIT 1";
        $result = mysql_query($plan, $conn);
        while ($row = mysql_fetch_array($result)) {
            $idplan = $row["id"];
        }
        mysql_close($conn);

        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $valor = $_POST['x_amount'];
        $referencia_aux = explode('Licencia Vendty',$licenciaGuardada); 
        $referencia_aux = explode('-',$referencia_aux[1]);
        $id_licencia = $referencia_aux[0];
        $id_plan = $referencia_aux[1];                
        $tipo_documento_user=$_POST['x_customer_doctype'];
        $numero_documento_user=$_POST['x_customer_document'];
        $nombre_user=$_POST['x_customer_name'];
        $apellido_user=$_POST['x_customer_lastname'];
        $email_user=$_POST['x_customer_email'];
        $telefono_user=$_POST['x_customer_phone'];
        $direccion_user=$_POST['x_customer_address'];
        $transaction_id=$_POST['x_transaction_id'];
        $ref_payco=$_POST['x_ref_payco'];
        $info_adicional="response2 pagoLicencia";
        $extra1 = $_POST['x_extra1'];
        $currency=$_POST['x_currency_code'];
        $total_pais=$_POST['x_amount_country'];
        $metodopago=$_POST['x_franchise'];
        $pago_por=$_POST['x_bank_name'];
        $valor_dolares=$valor;
        $estado = ($estado == 1) ? 1 : 3;
        $forma_pago=3;        
        $idbd="";

        if($currency=="USD"){            
            $valor=$extra1;
        }
        

        if($estado == 1){           
            //verificar si llego el pago
            $hoy=date('Y-m-d');
            $existe_pago=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia,'estado_pago'=>1));
            
            if($existe_pago==0){
                
                //email              
                require_once('job.php');
                $email = new Job();

                $planActual=$this->licencias->getPlanActual($id_licencia);
                $pago=$this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, 0, $transaction_id, $ref_payco,$forma_pago, $valor_dolares,$total_pais,$metodopago,$pago_por,$currency);       
                
                //PASAR A PRODUCCIÓN      
                $migrar=is_array($id_licencia) ? 1 : 0; 

                if(($migrar==0)&&($planActual==1)){    

                    $bduser=$this->licencias->buscarBD($id_licencia); 
                    $nombrebd=$bduser[0]['base_dato'];           
                    $idbd=$bduser[0]['id'];
                    $email1=explode("vendty2_db_",$nombrebd);                                           
                    
                    //$this->licencias->produccion($nombrebd);   
                    $data = array(
                        'origen' => 2,
                        'destino' => 8,
                        'dbname' => $email1[1]
                    );
                   
                    $migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api'));
                    if(isset($migrada->status) && isset($migrada->description)){
                        if(!$migrada->status && $migrada->description == "Verifica los datos enviados"){
                            $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
                        }
                    } else {
                        $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
                    }
                    //if($migrada->status){
                        //if($migrada->description=='ok'){
                            $this->licencias->updateEstadoBD($idbd);   
                            //modifico las fechas licencia
                            $plan=$this->crm_model->get_planes(array('id'=>$id_plan));
                            $tiempo=$plan[0]->dias_vigencia;							
                            $this->licencias->updateLicencianuevo($id_licencia, $id_plan,$tiempo);    	
                            
                            //email de bienvenida                                 
                            $email->BienvenidoaVendty($idbd);   
                        //}
                    //}

                }            
                
                //**tomo los valores de epayco y los guardo */
                //if($migrada->status){
                    //if($migrada->description=='ok'){
                        $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa'=>$id_licencia));	            
                        $empresa=$datos_licencia[0]->idempresas_clientes;	
                        $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente'=>$empresa));
                        //buscar tipo negocio
                        //$sqlinsertdb="SELECT valor_opcion FROM ".$nombrebd.".opciones WHERE nombre_opcion='tipo_negocio'";
                        //$sqlinsertdb=$this->db->query($sqlinsertdb)->result_array(); 
                        //$tipo_negocio=$sqlinsertdb[0]['valor_opcion'];
                        //$datos_bd_acti=array();
                        if(empty($datos_empresas)){
                            //guardar la informacion del cliente que viene de epayco               
                            $this->crm_empresas_clientes->update_info_factura_cliente(
                                array(
                                    'nombre_empresa' => $nombre_user." ".$apellido_user,
                                    'tipo_identificacion' => $tipo_documento_user,
                                    'numero_identificacion' => $numero_documento_user,
                                    'direccion' => $direccion_user,
                                    'telefono' => $telefono_user,                        
                                    'correo' => $email_user,                                              
                                    'contacto' => $nombre_user." ".$apellido_user                      
                                ),
                                array('id_db_config' => $idbd)
                            );     
                            
                        }
                        // insertar datos en bd_activa
                        // $this->crm_model->insert_db_activa_info($datos_bd_acti);

                        //email pago
                        $email->emailConfirmarPago($id_licencia);                                      
                        //generando la factura
                        $existe_pago2=$this->crm_model->existe_pago(array('transaction_id'=>$transaction_id,'ref_payco'=>$ref_payco,'id_licencia'=>$id_licencia,'estado_pago'=>1,'id_factura_licencia !='=>''));                              
                        
                        if($existe_pago2==0){  
                            require_once('administracion_vendty/facturas_licencia.php');
                            $factura = new Facturas_licencia();

                            /*if($idbd == '18318') {
                                $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            } else {
                                $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                            }*/
                            $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                            if(!empty($facturag)){
                                $email->emailFacturaPago($facturag);
                            }
                        }
                        
                        
                    //}
                //} 
            }                   
        } 
    }
}

?>
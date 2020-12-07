<?php
/**
 * 
 */
class Primeros_pasos extends CI_controller {
    var $user_id;
    var $id_db_config;
   
    function __construct()
	{		
        parent::__construct();       
        $this->load->model('link_primeros_pasos');
	}

    public function index(){

        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }       
        
        if ($this->ion_auth->in_group(5)) {    
            $datosempresas = $this->crm_model->get_empresas(array('id_distribuidores_licencia'=>1));
            $usuarios = $this->crm_model->get_all_user();
            
            $data['datos_empresas']=$datosempresas;
            $data['usuarios']=$usuarios;
                    
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/index',array('data' => $data));
        }else if($this->ion_auth->in_group(3)){
            
            $datosempresas = $this->crm_model->get_empresas(array('id_distribuidores_licencia'=>1));
            $usuarios = $this->crm_model->get_all_user();
            $data['datos_empresas']=$datosempresas;
            $data['usuarios']=$usuarios;
            $data['clientes'] = $this->crm_model->get_all_clientes();
            $data["total_pagos"] = 0;
            $data["total_pagos_por_mes"] = $this->crm_model->get_all_pagos_by_ano();
            if(count($data["total_pagos_por_mes"]) > 0){
                $mes_actual = date('m');
                
                $key = array_search(intval($mes_actual), array_column($data["total_pagos_por_mes"], 'mes'));
                if($key == NULL){
                    $data["total_pagos_por_mes"][2] = array(
                        "mes" => intval($mes_actual),
                        "total" => 0, 
                    ); 
                }
                


                for($i=1; $i<=12; $i++){

                    if (array_key_exists($i, $data["total_pagos_por_mes"])) {
                        $data["total_pagos"] += $data["total_pagos_por_mes"][$i]["valor_plan"];

                        switch(intval($data["total_pagos_por_mes"][$i]["mes"])){
                            case 1:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Enero';
                            break;
                            case 2:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Febrero';
                            break;
                            case 3:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Marzo';
                            break;
                            case 4:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Abril';
                            break;
                            case 5:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Mayo';
                            break;
                            case 6:
                                $data["total_pagos_por_mes"][$i]["mes"]= 'Junio';
                            break;
                            case 7:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Julio';
                            break;
                            case 8:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Agosto';
                            break;
                            case 9:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Septiembre';
                            break;
                            case 10:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Octubre';
                            break;
                            case 11:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Noviembre';
                            break;
                            case 12:
                                $data["total_pagos_por_mes"][$i]["mes"] = 'Diciembre';
                            break;
                        }
                    }

                    /*if($data["total_pagos_por_mes"][$i] != "" && $data["total_pagos_por_mes"][$i] != 0){
                        $data["total_pagos"] += $data["total_pagos_por_mes"][$i]["total"];
                    }*/
                    
                }
            }


            $data["total_activos"] = 0;
            $data["total_pruebas"] = 0;
            $data["total_suspendidos"] = 0; 
            foreach($data["clientes"] as $cliente){
                if($cliente->plan == 1){
                    $data["total_pruebas"]++;
                }else if($cliente->fecha_vencimiento > date('Y m d')){
                    $data["total_activos"]++;
                }else{
                    $data["total_suspendidos"]++; 
                }
            }


            /* Traemos los totales de los usuarios nuevos y vencidos de este año */
            $data["vencidos_mensual"] =  $this->crm_model->get_all_vencidos('mensual');
            $data["vencidos_pagos_mensual"] = 0;
            foreach($data["vencidos_mensual"] as $vencido){
                $data["vencidos_pagos_mensual"] += $vencido->valor_plan;                      
            } 

            $data["vencidos_anual"] =  $this->crm_model->get_all_vencidos('anual');
            $data["vencidos_pagos_anual"] = 0;
            foreach($data["vencidos_anual"] as $vencido){
                $data["vencidos_pagos_anual"] += $vencido->valor_plan;                      
            } 

            $data["vencidos_trimestral"] =  $this->crm_model->get_all_vencidos('trimestral');
            $data["vencidos_pagos_trimestral"] = 0;
            foreach($data["vencidos_trimestral"] as $vencido){
                $data["vencidos_pagos_trimestral"] += $vencido->valor_plan;                      
            } 


            $data["nuevos_mensuales"] = $this->crm_model->get_all_nuevos('mensual');
            $data["nuevos_pagos_mensuales"] = 0;
            foreach($data["nuevos_mensuales"] as $nuevo){
                $data["nuevos_pagos_mensuales"] += $nuevo->total;                      
            } 

            $data["nuevos_anuales"] = $this->crm_model->get_all_nuevos('anual');
            $data["nuevos_pagos_anuales"] = 0;
            foreach($data["nuevos_anuales"] as $nuevo){
                $data["nuevos_pagos_anuales"] += $nuevo->total;                      
            } 

            $data["pagos_anuales"] = $this->crm_model->get_all_pagos_by_last_ano();
            $data["total_licencias_ultimo_ano"] = 0;
            $data["total_pagos_ultimo_ano"] = 0;
            foreach($data["pagos_anuales"] as $pagos_anual){
                $data["total_licencias_ultimo_ano"] += $pagos_anual->total_licencias;  
                $data["total_pagos_ultimo_ano"] += $pagos_anual->total_pagos;                      
            } 

            $data["pendientes_ultimo_ano"] = $this->crm_model->get_pagos_pendientes_by_ano();

            $data["por_renovar_mensual"] = $this->crm_model->planes_mensuales_por_renovar();
            $data["por_renovar_anual"] = $this->crm_model->planes_anuales_por_renovar();

            $data["planes_mensuales_pagados"] = $this->crm_model->planes_mensuales_pagados();
            $data["planes_anuales_pagados"] = $this->crm_model->planes_anuales_pagados();
            

            //print_r($data["vencidos_mensual"]);
            //die();
            $data["total_licencias_mensual"] =  $data["por_renovar_mensual"]["cantidad_licencias"] + $data["planes_mensuales_pagados"]["cantidad_licencias"] + count($data["vencidos_mensual"]); 
            $data["total_licencias_mensual_pagos"] =  $data["por_renovar_mensual"]["total_pagos"] + $data["planes_mensuales_pagados"]["total_pagos"] + $data["vencidos_pagos_mensual"]; 
            
            $data["total_licencias_anual"] =  $data["por_renovar_anual"]["cantidad_licencias"] + $data["planes_anuales_pagados"]["cantidad_licencias"] + count($data["vencidos_anual"]); 
            $data["total_licencias_anual_pagos"] =  $data["por_renovar_anual"]["total_pagos"] + $data["planes_anuales_pagados"]["total_pagos"] + $data["vencidos_pagos_anual"]; 
            $data["meses"] = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $this->layout->template('distribuidores_vendty')->show('distribuidores/index',array('data' => $data));
        }else{
            redirect("frontend/index");
        }
    }
    public function nuevo(){
       
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) { 
            $distribuidores = $this->crm_model->get_all_distribuidor();
            $email_bd = $this->crm_model->get_all_user();
            $paises=$this->pais_model->getAll();        
            $data['distribuidor']=$distribuidores;
            $data['email']=$email_bd;
            $data['pais']=$paises;
            
            $empresa=$this->input->post('nombre_empresa');
            if (!empty($empresa)) {              
                $dato = $this->crm_model->get_empresas(array('nombre_empresa'=>$empresa));               
                if(count($dato)==0){                
                    if ($this->form_validation->run('empresas_clientes') == true) {
                        $user=explode("-", $this->input->post('id_db_config'));
                        $data = array(
                            'nombre_empresa' =>  $this->input->post('nombre_empresa')
                            ,'direccion_empresa' => $this->input->post('direccion_empresa')
                            ,'telefono_contacto' => $this->input->post('telefono_contacto')
                            ,'idusuario_creacion' => $user[0]
                            ,'id_db_config' => $user[1]
                            ,'id_distribuidores_licencia' =>  $this->input->post('id_distribuidores_licencia') 
                            ,'id_user_distribuidor' =>  $this->input->post('id_user_distribuidor') 
                            ,'identificacion_empresa' =>  $this->input->post('identificacion_empresa') 
                            ,'tipo_identificacion' =>  $this->input->post('tipo_identificacion') 
                            ,'razon_social_empresa' =>  $this->input->post('razon_social_empresa') 
                            ,'ciudad_empresa' =>  $this->input->post('ciudad_empresa') 
                            ,'departamento_empresa' => $this->input->post('provincia')
                            ,'pais' => $this->input->post('pais')               

                        );
                    
                        $this->crm_empresas_clientes_model->add($data);
                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Empresa creada correctamente'));
                        redirect('administracion_vendty/empresas/');
                    }
                }
                else{
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'El nombre de la empresa ya existe'));
                    redirect('administracion_vendty/empresas/');
                } 
            }

            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/nuevo',array('data' => $data));
        }else{
            redirect("frontend/index");
        }
    }

    public function editar($id){
        
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) { 

            $empresa=$this->input->post('nombre_empresa');
            if (!empty($empresa)) {              
                $dato = $this->crm_model->get_empresas(array('nombre_empresa'=>$empresa,'idempresas_clientes !='=>$id));               
                if(count($dato)==0){  
                    if ($this->form_validation->run('empresas_clientes') == true) {
                        $user=explode("-", $this->input->post('id_db_config'));
                        $data = array(                
                            'idempresas_clientes' =>  $this->input->post('idempresas_clientes')
                            ,'nombre_empresa' =>  $this->input->post('nombre_empresa')
                            ,'direccion_empresa' => $this->input->post('direccion_empresa')
                            ,'telefono_contacto' => $this->input->post('telefono_contacto')
                            ,'idusuario_creacion' => $user[0]
                            ,'id_db_config' => $user[1]
                            ,'id_distribuidores_licencia' =>  $this->input->post('id_distribuidores_licencia') 
                            ,'id_user_distribuidor' =>  $this->input->post('id_user_distribuidor') 
                            ,'identificacion_empresa' =>  $this->input->post('identificacion_empresa') 
                            ,'tipo_identificacion' =>  $this->input->post('tipo_identificacion') 
                            ,'razon_social_empresa' =>  $this->input->post('razon_social_empresa') 
                            ,'ciudad_empresa' =>  $this->input->post('ciudad_empresa') 
                            ,'departamento_empresa' => $this->input->post('provincia')
                            ,'pais' => $this->input->post('pais')               

                        );
                        $this->crm_empresas_clientes_model->update($data);
                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Empresa Modificada correctamente'));
                        redirect('administracion_vendty/empresas/');
                    }
                }else{
                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'El nombre de la empresa ya existe'));
                    redirect('administracion_vendty/empresas/');
                } 
            }

            $data = array();
            $dataempresa = $this->crm_empresas_clientes_model->get_by_id($id);
            $data['dataempresa']=$dataempresa;
            $distribuidores = $this->crm_model->get_all_distribuidor();
            $email_bd = $this->crm_model->get_all_user();
            $paises=$this->pais_model->getAll();        
            $data['distribuidor']=$distribuidores;
            $data['email']=$email_bd;
            $data['pais']=$paises;

            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/editar',array('data' => $data));
        }
        else{
            redirect("frontend/index");
        }
    }

    public function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
            $datoslicencias = $this->crm_licencia_model->get_by_id(array('idempresas_clientes'=>$id));
           
            if(count($datoslicencias)==0){
                $this->crm_empresas_clientes_model->delete($id);
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La Empresa fue eliminada correctamente'));
                redirect('administracion_vendty/empresas/');
            }else{
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La Empresa no pudo ser eliminada tiene licencias asociadas'));
                redirect('administracion_vendty/empresas/');
            }               
        }
        else{
            redirect("frontend/index");
        }
    }

    public function get_link_inicio() {
        
    }
}

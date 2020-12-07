<?php
/**
 *
 */
class Empresas extends CI_controller {
    var $user_id;
    var $id_db_config;

    function __construct()
	{

        parent::__construct();
        //$this->load->library('grocery_CRUD');
        $this->load->model('crm_model');
        $this->load->model("pais_model");
        $this->load->model("usuarios_model");
        $this->load->model("crm_licencia_model");
        $this->load->model("crm_empresas_clientes_model");
        $this->load->model("crm_licencias_empresa_model");

        if(!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))){
            redirect("frontend/index");
        }

	}

    public function index(){

        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {    //die("5");
            //$datosempresas = $this->crm_model->get_empresas(array('id_distribuidores_licencia'=>1));
            //$datosempresas = $this->crm_model->get_empresas(0,100);
            //$usuarios = $this->crm_model->get_all_user();

            //$data['datos_empresas']=$datosempresas;
            //$data['usuarios']=$usuarios;

            //$this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/index',array('data' => $data));
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/index');
        }else if($this->ion_auth->in_group(3)){

            //distribuidor
            $user=$this->session->userdata('user_id');
            $distribuidor = $this->crm_model->get_distribuidor2(array('users_id'=>$user));
            $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];

            //buscar total licencias activas
            $data["licencias_mensuales"] = $this->crm_model->total_licencias('mensual');
            $data["licencias_anuales"] = $this->crm_model->total_licencias('anual');

            $data["total_pagos"] = 0;

           // $data["total_pagos_por_mes"] = $this->crm_model->get_all_pagos_by_ano();
            $data["total_pagos_por_mes"] = $this->crm_model->pagos_ultimos_18meses();

            if(count($data["total_pagos_por_mes"]) > 0){

                foreach ($data["total_pagos_por_mes"] as $key => $value) {

                    if (in_array($value['mes'], $data["total_pagos_por_mes"][$key])) {

                        $data["total_pagos"] += $data["total_pagos_por_mes"][$key]["total"];
                        $anio=explode("-", $key);

                        switch(($data["total_pagos_por_mes"][$key]["mes"])){
                            case 1:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Enero '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 2:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Febrero '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 3:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Marzo '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 4:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Abril '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 5:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Mayo '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 6:
                                $data["total_pagos_por_mes"][$key]["mes"]= 'Junio '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 7:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Julio '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 8:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Agosto '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 9:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Septiembre '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 10:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Octubre '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 11:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Noviembre '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                            case 12:
                                $data["total_pagos_por_mes"][$key]["mes"] = 'Diciembre '.$anio[0];
                                $data["total_pagos_por_mes"][$key]["mes1"] = $key;
                            break;
                        }

                    }

                }

            }

            if($iddistribuidor!=1){
                $this->layout->template('distribuidores_vendty')->show('distribuidores/index',array('data' => $data));
            }else{
                $this->layout->template('distribuidores_vendty')->show('distribuidores/index2',array('data' => $data));
            }

        }else{
            redirect("frontend/index");
        }
    }

    public function configuracion(){

        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
            //recibo el id de la bd

            if($_POST){
                $bd=$this->input->post('bd');
            }else{
                $bd=$this->uri->segment(4);
            }

            if(!empty($bd)){

                //busco conexion de la bd
                $cone=$this->crm_model->consulta_bd(array('id'=>$bd),'db_config');
                $usuario = $cone[0]['usuario'];
                $clave = $cone[0]['clave'];
                $servidor = $cone[0]['servidor'];
                $base_dato = $cone[0]['base_dato'];

                $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                $this->connection = $this->load->database($dns, true);
                $this->dbConnection = $this->load->database($dns, true);

                $this->load->model("miempresa_model", 'miempresa');
                $this->miempresa->initialize($this->connection);
                $this->load->model("opciones_model", 'opciones');
                $this->opciones->initialize($this->connection);
                $this->load->model("productos_model", 'productos');
                $this->productos->initialize($this->connection);
                $this->load->model("stock_actual_model", 'stock_actual');
                $this->stock_actual->initialize($this->connection);

                if($_POST){

                    $this->miempresa->update_data_empresa(array(
                        "offline" => $this->input->post('offline'),
                        'precio_almacen' => $this->input->post('precio_almacen'),
                        'resolucion_factura_estado' => $this->input->post('resolucion_factura_estado'),
                        'plantilla_orden_compra' => $this->input->post('plantilla_orden_compra'),
                        'plantilla_general' => $this->input->post('plantilla_general'),
                        'numero' => $this->input->post('numero'),
                        'redondear_precios' => $this->input->post('redondear_precios'),
                        'orden_compra_precio' => $this->input->post('orden_compra_precio'),
                        'simbolo' => $this->input->post('simbolo'),
                        'plan_separe' => $this->input->post('plan_separe_m'),
                        'auto_factura' => $this->input->post('auto_factura'),
                        'auto_pago' => $this->input->post('auto_pago'),
                        "enviar_factura" => $this->input->post('enviar_factura'),
                        "costo_promedio" => $this->input->post('costo_promedio'),
                        "cabecera_factura" => $this->input->post('cabecera_factura'),
                        "terminos_condiciones" => $this->input->post('terminos_condiciones'),
                        "prefijo_factura" => $this->input->post('prefijo_factura'),
                        "numero_factura" => $this->input->post('numero_factura'),
                        "resolucion_factura" => $this->input->post('resolucion_factura')
                    ));

                    //se actualice el sctock_actual con precios de almacen
                    if($this->input->post('precio_almacen') ==1){

                        $product = $this->productos->getList();
                        $data = array();
                        foreach($product as $rowProduct){
                            $data = array(
                                'precio_compra' => floatval($rowProduct->precio_compra),
                                'precio_venta' => floatval($rowProduct->precio_venta),
                                'stock_minimo' => intval($rowProduct->stock_minimo),
                                'impuesto' => floatval($rowProduct->impuesto),
                                'fecha_vencimiento' => floatval($rowProduct->fecha_vencimiento),
                                'activo' => intval($rowProduct->activo),
                            );
                            $this->stock_actual->update_by_product($data,$rowProduct->id);

                        }
                    }

                    //modulos de plan separe, atributos, puntos
                    //plansepare
                    $dataplansepare = array(
                        'db_config_id' => $bd,
                        'modulo_id' => 2,
                        'estado' => $this->input->post('plan_separe_m'),
                    );
                    $this->crm_model->activar_modulo(array('db_config_id'=>$bd,'modulo_id'=>2),$dataplansepare);
                    //atributos
                    $dataatributos = array(
                        'db_config_id' => $bd,
                        'modulo_id' => 3,
                        'estado' => $this->input->post('atributos_m'),
                    );
                    $this->crm_model->activar_modulo(array('db_config_id'=>$bd,'modulo_id'=>3),$dataatributos);

                    $datapuntos = array(
                        'db_config_id' => $bd,
                        'modulo_id' => 4,
                        'estado' => $this->input->post('puntos_m'),
                    );

                    $this->crm_model->activar_modulo(array('db_config_id'=>$bd,'modulo_id'=>4),$datapuntos);
                    $this->crm_model->update_cant_almacen(array('id'=>$bd),array('almacen'=>$this->input->post('cantidad_almacenes')));

                    $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Actualizada la información de la empresa'));
                    redirect("administracion_vendty/empresas/configuracion/$bd");
                }

                $data = $this->miempresa->get_data_empresa();
                $plan_separe = $this->crm_model->consulta_bd(array('db_config_id'=>$bd,'modulo_id' =>2),'modulos_clientes');
                $data['data']['plan_separe_m']=0;
                if(!empty($plan_separe)){
                    $data['data']['plan_separe_m']=$plan_separe[0]['estado'];
                }

                $atributos = $this->crm_model->consulta_bd(array('db_config_id'=>$bd,'modulo_id' =>3),'modulos_clientes');
                $data['data']['atributos_m'] =0;
                if(!empty($atributos)){
                    $data['data']['atributos_m']=$atributos[0]['estado'];
                }

                $puntos = $this->crm_model->consulta_bd(array('db_config_id'=>$bd,'modulo_id' =>4),'modulos_clientes');
                $data['data']['puntos_m'] =0;

                if(!empty($puntos)){
                    $data['data']['puntos_m']=$puntos[0]['estado'];
                }

                $aumentaralmacenes = $this->crm_model->consulta_bd(array('id'=>$bd),'db_config');
                $data['data']['cantidad_almacenes'] =0;

                if(!empty($aumentaralmacenes)){
                    $data['data']['cantidad_almacenes']=$aumentaralmacenes[0]['almacen'];
                }

                $data['data']['bd']=$bd;

                $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/configuracion',array('data' => $data));
            }else{
                redirect("administracion_licencia/empresas/index");
            }

        }else{
            redirect("frontend/index");
        }
    }

    function cambiar_clave_admin(){
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
            $id=$this->input->post('id');
            if(!empty($id)){
                //reiniciamos la clave del usuario
                $x=$this->crm_model->update_clave_user_admin(array('id'=>$id), array('password'=>'0ccf0cede9785bc7257102c3d9415532e590fff9'));
                if($x==1){
                    $data['success']=1;
                }else{
                    $data['success']=0;
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }else{

            }
        }else{
            redirect("frontend/index");
        }
    }
    function get_ajax_data_empresas(){
        if(!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->ion_auth->in_group(5)) {
            $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->get_ajax_data_empresas()));
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

    public function verlicencias($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }
        if ($this->ion_auth->in_group(5)) {
            $data['empresa']= $this->crm_model->get_empresas(array('idempresas_clientes'=>$id));
            $data['datoslicencias'] = $this->crm_licencias_empresa_model->get_all_id(array('idempresas_clientes'=>$id,'id_plan !='=>1));
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/verlicencias',array('data' => $data));
        }else{
            redirect("frontend/index");
        }

    }

    public function import_excel()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $this->load->library('phpexcel');
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $cursor = 0;
        $flag = false;
        $pointer = 0;
        $result = "";
        $data = array();
        $error_upload = "";
        $campos = array();
        if (!empty($_FILES))
        {
            $config = array();
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'xlsx|xls';
            $this->load->library('upload', $config);
            if (!empty($_FILES['archivo']['name']))
            {
                if (!$this->upload->do_upload('archivo'))
                {
                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $excel_name = $upload_data['file_name'];
                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);
                    $reader->setReadDataOnly(TRUE);
                    $objXLS = $reader->load("uploads/" . $excel_name);
                    $campos[] = "No importar este campo";
                    /************************************* */
                    $sheet = $objXLS->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $adicionados=0;
                    $errores_importar="";
                    $noadicionados=0;
                    $array_datos;
                    $count=2;

                     //recorremos el archivo
                     for ($row = 2; $row <= $highestRow; $row++){
                        //obtemos los valores
                        $empresa=$sheet->getCell("A".$row)->getValue();
                        $razon_social=$sheet->getCell("B".$row)->getValue();
                        $direccion=$sheet->getCell("C".$row)->getValue();
                        $telefono=$sheet->getCell("D".$row)->getValue();
                        $email=$sheet->getCell("E".$row)->getValue();
                        $tipo_identificacion=$sheet->getCell("F".$row)->getValue();
                        $documento=$sheet->getCell("G".$row)->getValue();
                        $ciudad=$sheet->getCell("H".$row)->getValue();
                        $departamento=$sheet->getCell("I".$row)->getValue();
                        $pais=$sheet->getCell("J".$row)->getValue();
                        $distribuidor=$sheet->getCell("K".$row)->getValue();
                        $usuario_distribuidor=$sheet->getCell("L".$row)->getValue();

                        if(!empty($empresa)){
                            $count++;
                            $usuario=$this->usuarios_model->get_id_config_email(trim($email));
                            $datos_empresa=$this->crm_model->get_empresas(array('nombre_empresa'=> $empresa));

                            if((isset($usuario->id)&&($usuario->id>0))&&(empty($datos_empresa))){

                                $array_datos = array(
                                    'nombre_empresa' => $empresa,
                                    'direccion_empresa' => $direccion,
                                    'telefono_contacto' => $telefono,
                                    'idusuario_creacion' => $usuario->id,
                                    'id_db_config' => $usuario->db_config_id,
                                    'id_distribuidores_licencia' => $distribuidor,
                                    'id_user_distribuidor' => $usuario_distribuidor,
                                    'identificacion_empresa' =>$documento,
                                    'tipo_identificacion' => $tipo_identificacion,
                                    'razon_social_empresa' => $razon_social,
                                    'ciudad_empresa' => $ciudad,
                                    'departamento_empresa' => $departamento,
                                    'pais' => $pais
                                );

                                if(!empty($array_datos['idusuario_creacion'])){
                                    $this->crm_empresas_clientes_model->add($array_datos);
                                    $adicionados++;
                                }
                            }else{
                                if(count($datos_empresa)>0){
                                    $errores_importar.="<p><span class='glyphicon glyphicon-remove'></span> La empresa ".$empresa." ya existe</p>";
                                }
                                else{
                                    $errores_importar.="<p><span class='glyphicon glyphicon-remove'></span> El email ".$email." asociado a la empresa ".$empresa." no existe</p>";
                                }
                                $noadicionados++;
                            }
                        }

                    }
                    $objXLS->disconnectWorksheets();
                    unset($objXLS);
                    $data['count'] = $count - 2;
                    $data['adicionados'] = $adicionados;
                    $data['noadicionados'] = $noadicionados;
                    $data['errores_importar'] = $errores_importar;
                    unlink("uploads/$excel_name");
                    $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_complete', array('data' => $data));

                }
            }else{
                $data['data']['upload_error'] = $error_upload;
                $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_excel', array('data' => $data));
            }
        }else{
            $data['data']['upload_error'] = "Seleccione un archivo";
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/import_excel', array('data' => $data));
        }
    }

    public function consultar_usuarios_distribuidores(){
		$id_distribuidor = $this->input->post('distribuidor');
		$this->db->where(array('id_distribuidores_licencia'=>$id_distribuidor));
		$this->db->select('users_id, email');
		$this->db->from('crm_usuarios_distribuidores');
		$this->db->join('users','crm_usuarios_distribuidores.users_id=users.id');
		$query = $this->db->get();
		$devolver = array();
		foreach ($query->result() as $key => $value) {
		 		$devolver[]=array('id'=>$value->users_id,'email'=>$value->email);
		}
		echo json_encode($devolver);
	}


    public function crear_contacto_empresa($post_array,$primary_key){
        $user = $this->db->where(array('id'=>$post_array['idusuario_creacion']))->get('users')->row();
        $data_contacto = array(
                'tipo_contacto'    => 'creacion_cuenta',
                'nombre_contacto'  => $post_array['razon_social_empresa'],
                'telefono_contacto'=> $post_array['telefono_contacto'],
                'email_contacto'   => $user->email,
                'idempresas_clientes'=>$primary_key
            );
        $this->db->insert('crm_contactos_empresa',$data_contacto);
        $data_licencia = array('idempresas_clientes'=>$primary_key,
                                'planes_id'=>1,
                                'fecha_vencimiento'=>date("Y-m-d",strtotime("+1 week")),
                                'fecha_creacion'   =>date("Y-m-d h:i:s"),
                                'id_db_config'     => $user->db_config_id,
                                'id_almacen'       => 1,
                                'estado_licencia'   =>1,
                                'fecha_inicio_licencia' => date("Y-m-d"),
                                'creado_por'        => $this->ion_auth->get_user_id()
                            );
        return $this->db->insert('crm_licencias_empresa',$data_licencia);

    }

    public function campo_correo_callback($value = '', $primary_key = null){
        return '<input type="text" maxlength="100" value="'.$value.'" name="idusuario_creacion" style="width:462px">';
    }

    public function crear_db_cliente_callback($post_array,$primary_key=''){
        $year = date('Y');
        $salt = substr(md5(uniqid(rand(), true)), 0, 10);
        $password = substr($post_array['idusuario_creacion'], 0, 4) . "$year";
        $password_send = $password;
        $conf_code = $salt . substr(sha1($salt . $password), 0, -10);

        $username = explode('@', $post_array['idusuario_creacion']);

        $data = array(
                'ip_address'     => $_SERVER['REMOTE_ADDR'],
                'username'       => $username[0],
                'password'       => $this->ion_auth->hash_password($password),
                'salt'           => $salt,
                'email'          => $post_array['idusuario_creacion'],
                'activation_code'=> $conf_code,
                'created_on'     => time(),
                'last_login'     => time(),
                'active'         => 0,
                'idioma'         => 'spanish',
                'is_admin'       => 't',
                'company'        => $post_array['nombre_empresa'],
                'phone'          => $post_array['telefono_contacto']
            );
        $this->db->insert('users',$data);
        $id_user = $this->db->insert_id();
        //creamos el contacto

        if($id_user){
            $this->ion_auth->activate($id_user, $conf_code);
            $this->enviar_email($post_array['idusuario_creacion'],$post_array['razon_social_empresa'],$username[0],$password_send);
            $nombre_bd = $id_user . '_' . $password;
            // Creamos y configuramos las DB del usuario
            $this->load->library('PHPRequests');
            $url ='http://localhost/vendty-demo/index.php/auth/create_db_distribuidores/'.$id_user.'/'.$nombre_bd;
            $response = Requests::get($url,array(),array('timeout'=>120.5));
            $user_actualizado = $this->db->where(array('email'=>$post_array['idusuario_creacion']))->get('users')->row();
            $post_array['id_db_config'] = $user_actualizado->db_config_id;
            $post_array['idusuario_creacion'] = $user_actualizado->id;
            return $post_array;
        }else{
            return false;
        }

    }

    public function enviar_email($email,$nombreUsuario,$username,$password_send){
         $html = $this->load->view("auth/registroEmail_vendty_demo",array(
                "email"=>$email,
                "nombre"=>$nombreUsuario,
                "username"=>$username,
                "password"=>$password_send
              ),true);

            $this->load->library('email');
            $this->email->initialize();
            $this->email->from('info@vendty.com', 'Vendty');
            $this->email->to($email);
            $this->email->bcc('roxanna@vendty.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $this->email->message($html);
            $this->email->send();

    }

    public function _callback_column_db_config($value,$row){
        $this->db->where(array('id'=>$value));
        $datos_bd = $this->db->get('db_config')->row();
        return $datos_bd->base_dato;
    }


    public function definir_db_config_callback($post_array,$primary_key=''){
        //$id_user = $post_array['idusuario_creacion'];
        $this->db->where(array('id'=>$post_array['idusuario_creacion']));
        $query = $this->db->get('users');
        $id_db_config = 0;
        foreach ($query->result() as $key => $value) {
            $id_db_config = $value->db_config_id;
        }
        $post_array['id_db_config'] = $id_db_config;
        return $post_array;
    }

    public function cargar_clientes($tipo_filtro,$fecha_inicio,$fecha_fin){
        $data = array();
        switch($tipo_filtro){
            case 'nuevos_mensuales' :
                $data["title"] = "Nuevos Mensuales";
                $data["url"] = "nuevos_mensuales/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "nuevos_mensuales";
                $data["clientes"] = $this->crm_model->get_all_nuevos2('mensual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'nuevos_anuales' :
                $data["title"] = "Nuevos Anuales";
                $data["url"] = "nuevos_anuales/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "nuevos_anuales";
                $data["clientes"] = $this->crm_model->get_all_nuevos2('anual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'ver_licencias_mensuales' :
                $data["title"] = "Licencias Activas Mensuales";
                $data["url"] = "ver_licencias_mensuales/".$fecha_inicio."/null";
                //$data["clientes"] = $this->crm_model->get_all_vencidos2('mensual',$fecha_inicio,null);
                $data["clientes"] = $this->crm_model->total_licencias('mensual');
                $this->layout->template('distribuidores_vendty')->show('distribuidores/ver_licencias',$data);
            break;

            case 'ver_licencias_anuales' :
                $data["title"] = "Licencias Activas Anuales";
                $data["url"] = "ver_licencias_anuales/".$fecha_inicio."/null";
                $data["clientes"] = $this->crm_model->total_licencias('anual');
                $this->layout->template('distribuidores_vendty')->show('distribuidores/ver_licencias',$data);
            break;

            case 'pagado_antes_mensuales' :
                $data["title"] = "Licencias Activas Pagadas Antes";
                $data["url"] = "pagado_antes_mensuales/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->activas_pagadas_antes('mensual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/ver_licencias',$data);
            break;

            case 'pagado_antes_anuales' :
                $data["title"] = "Licencias Activas Pagadas Antes";
                $data["url"] = "pagado_antes_anuales/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->activas_pagadas_antes('anual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/ver_licencias',$data);
            break;

            case 'vencidos_mensual' :
                $data["title"] = "Vencidos Mensuales";
                $data["url"] = "vencidos_mensual/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "vencidos_mensual";
                //$data["clientes"] = $this->crm_model->get_all_vencidos2('mensual',$fecha_inicio,null);
                $data["clientes"] = $this->crm_model->get_all_vencidos2('mensual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'vencidos_anual' :
                $data["title"] = "Trimestrales Vencidos";
                $data["url"] = "vencidos_trimestral/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "vencidos_trimestral";
                //$data["clientes"] = $this->crm_model->get_all_vencidos2('anual',$fecha_inicio,null);
                $data["clientes"] = $this->crm_model->get_all_vencidos2('anual',$fecha_inicio,$fecha_fin);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;
           /* case 'vencidos_trimestral' :
                $data["title"] = "Trimestrales Vencidos";
                $data["url"] = "vencidos_trimestral/".$fecha_inicio."/null";
                $data["identificador"] = "vencidos_trimestral";
                $data["clientes"] = $this->crm_model->get_all_vencidos2('trimestral',$fecha_inicio,null);
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;*/

            case 'por_renovar_mensual' :
                $data["title"] = "Mensuales por Renovar";
                $data["url"] = "por_renovar_mensual/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "por_renovar_mensual";
                $data["clientes"] = $this->crm_model->planes_por_renovar('mensual',$fecha_inicio,$fecha_fin)["clientes"];
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'por_renovar_anual' :
                $data["title"] = "Anuales por Renovar";
                $data["url"] = "por_renovar_anual/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "por_renovar_anual";
                $data["clientes"] = $this->crm_model->planes_por_renovar('anual',$fecha_inicio,$fecha_fin)["clientes"];
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'pagadas_mensual' :
                $data["title"] = "Mensuales Pagados";
                $data["url"] = "pagadas_mensual/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "pagadas_mensual";
                $data["clientes"] =  $this->crm_model->planes_pagados('mensual',$fecha_inicio,$fecha_fin)["clientes"];
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'pagadas_anual' :
                $data["title"] = "Anuales pagados";
                $data["url"] = "pagadas_anual/".$fecha_inicio."/".$fecha_fin;
                $data["identificador"] = "pagadas_anual";
                $data["clientes"] =  $this->crm_model->planes_pagados('anual',$fecha_inicio,$fecha_fin)["clientes"];
                $this->layout->template('distribuidores_vendty')->show('distribuidores/clientes',$data);
            break;

            case 'todos_los_pagados' :
                $aniomes=str_replace ("%20"," ",$fecha_inicio);
                $mesnum=$fecha_fin;
                $data["title"] = "Pagados en el mes de ".$aniomes;
                $data["url"] = "todos_los_pagados/$mesnum/$mesnum";
                $data["identificador"] = "todos_los_pagados";
                $data["clientes"] =  $this->crm_model->pagados_mes($mesnum)["clientes"];
                $this->layout->template('distribuidores_vendty')->show('distribuidores/todos_pagos',$data);
            break;
        }
    }

    public function descargar_excel($tipo_filtro,$fecha_inicio,$fecha_fin){
        $data = array();
        switch($tipo_filtro){
            case 'nuevos_mensuales' :
                $data["clientes"] = $this->crm_model->get_all_nuevos2('mensual',$fecha_inicio,$fecha_fin);
            break;

            case 'nuevos_anuales' :
                $data["title"] = "Nuevos Anuales";
                $data["url"] = "nuevos_anuales/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->get_all_nuevos2('anual',$fecha_inicio,$fecha_fin);
            break;

            case 'vencidos_mensual' :
                $data["title"] = "Mensuales Vencidos";
                $data["url"] = "vencidos_mensual/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->get_all_vencidos2('mensual',$fecha_inicio,$fecha_fin);
            break;

            case 'vencidos_trimestral' :
                $data["title"] = "Trimestrales Vencidos";
                $data["url"] = "vencidos_trimestral/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->get_all_vencidos2('trimestral',$fecha_inicio,$fecha_fin);
            break;

            case 'vencidos_anual' :
                $data["title"] = "Anuales Vencidos";
                $data["url"] = "vencidos_anual";
                $data["clientes"] = $this->crm_model->get_all_vencidos2('anual',$fecha_inicio,$fecha_fin);
            break;

            case 'pagado_antes_mensuales' :
                $data["title"] = "Licencias Activas  Mensuales Pagadas Antes";
                $data["url"] = "pagado_antes_mensuales/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->activas_pagadas_antes('mensual',$fecha_inicio,$fecha_fin);
            break;

            case 'pagado_antes_anuales' :
                $data["title"] = "Licencias Activas Anuales Pagadas Antes";
                $data["url"] = "pagado_antes_anuales/".$fecha_inicio."/".$fecha_fin;
                $data["clientes"] = $this->crm_model->activas_pagadas_antes('anual',$fecha_inicio,$fecha_fin);
            break;

            case 'ver_licencias_mensuales' :
                $data["title"] = "Licencias Activas Anuales";
                $data["url"] = "ver_licencias_mensuales";
                $data["clientes"] = $this->crm_model->total_licencias('mensual');
            break;

            case 'ver_licencias_anuales' :
                $data["title"] = "Licencias Activas Anuales";
                $data["url"] = "ver_licencias_anuales";
                $data["clientes"] = $this->crm_model->total_licencias('anual');
                $this->layout->template('distribuidores_vendty')->show('distribuidores/ver_licencias',$data);
            break;

            case 'por_renovar_mensual' :
                $data["title"] = "Mensuales por Renovar";
                $data["url"] = "por_renovar_mensual";
                $data["clientes"] = $this->crm_model->planes_por_renovar('mensual',$fecha_inicio,$fecha_fin)["clientes"];
            break;

            case 'por_renovar_anual' :
                $data["title"] = "Anuales por Renovar";
                $data["url"] = "por_renovar_anual";
                $data["clientes"] = $this->crm_model->planes_por_renovar('anual',$fecha_inicio,$fecha_fin)["clientes"];
            break;

            case 'pagadas_mensual' :
                $data["title"] = "Mensuales Pagados";
                $data["url"] = "pagadas_mensual";
                $data["clientes"] =  $this->crm_model->planes_pagados('mensual',$fecha_inicio,$fecha_fin)["clientes"];
            break;

            case 'pagadas_anual' :
                $data["title"] = "Anuales pagados";
                $data["url"] = "pagadas_anual";
                $data["clientes"] =  $this->crm_model->planes_pagados('anual',$fecha_inicio,$fecha_fin)["clientes"];
            break;
            /*
            case 'todos_los_pagados' :
                $mes=str_replace (" ","%20",$fecha_inicio);
                $mesnum=$fecha_fin;
                $data["title"] = "Pagados en el mes de ".$mes;
                $data["url"] = "todos_los_pagados";
                $data["clientes"] =  $this->crm_model->pagados_mes($mesnum)["clientes"];
            break;*/
            case 'todos_los_pagados' :
                $aniomes=str_replace ("%20"," ",$fecha_inicio);
                $mesnum=$fecha_fin;
                $data["title"] = "Pagados en el mes de ".$aniomes;
                $data["url"] = "todos_los_pagados";
                $data["identificador"] = "todos_los_pagados";
                $data["clientes"] =  $this->crm_model->pagados_mes($mesnum)["clientes"];
               // $this->layout->template('distribuidores_vendty')->show('distribuidores/todos_pagos',$data);
            break;
        }
        //excel
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);

        if ($tipo_filtro=="todos_los_pagados") {
            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Id Licencia');
            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha Activación');
            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha inicio licencia');
            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha vencimiento licencia');
            $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Distribuidor');
            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'vendedor');
            $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Plan');
            $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Valor plan');
            $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Fecha Pago');
            $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Forma de Pago');
            $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Monto Pago');
            $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Descuento Pago');
            $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Retención');
            $this->phpexcel->getActiveSheet()->setCellValue('N1', 'Total Recaudado');
            $this->phpexcel->getActiveSheet()->setCellValue('O1', 'Factura');
            $this->phpexcel->getActiveSheet()->setCellValue('P1', 'Nombre en Factura');
            $this->phpexcel->getActiveSheet()->setCellValue('Q1', 'Identificación');
        } else {
            if (($tipo_filtro=="ver_licencias_mensuales") || ($tipo_filtro=="ver_licencias_anuales") || ($tipo_filtro=="pagado_antes_mensuales")||($tipo_filtro=="pagado_antes_anuales")){
                $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Id Licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha Activación');
                $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha inicio licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha vencimiento licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Plan');
                $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor plan');
                $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Empresa');
                $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Correo Electrónico');
                $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Distribuidor');
                $this->phpexcel->getActiveSheet()->setCellValue('J1', 'vendedor');
                $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Días Vigencia');;
                $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Teléfono');
            } else {
                $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Id_licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha activación licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha inicio licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha vencimiento licencia');
                $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre Empresa');
                $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Teléfono');
                $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Email');
                $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Nombre Plan');
                $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Días Vigencia');
                $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Valor Plan');
                $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Distribuidor');
                $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Vendedor');

                if (($tipo_filtro=="nuevos_mensuales") ||($tipo_filtro=="nuevos_anuales")) {
                    $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Fecha_Pago');
                }
            }
        }
        ///consulta
        $query=$data["clientes"];
        $row = 2;
        $count = 0;
        foreach ($query as $cliente) {
            //$value = $cliente[0];
            if ($count >= 0) {
                if ($tipo_filtro=="todos_los_pagados") {
                    $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($cliente->idlicencias_empresa, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($cliente->fecha_activacion, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($cliente->fecha_inicio_licencia, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($cliente->fecha_vencimiento, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($cliente->nombre_distribuidor, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($cliente->vendedor, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($cliente->nombre_plan, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($cliente->valor_plan, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($cliente->fecha_pago, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, html_entity_decode($cliente->nombre_forma, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, html_entity_decode($cliente->monto_pago, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, html_entity_decode($cliente->descuento_pago, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, html_entity_decode($cliente->retencion_pago, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, html_entity_decode($cliente->total, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, html_entity_decode($cliente->numero_factura, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, html_entity_decode($cliente->nombre_empresa, ENT_QUOTES, 'UTF-8'));
                    $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, html_entity_decode($cliente->tipo_identificacion.' '.$cliente->numero_identificacion, ENT_QUOTES, 'UTF-8'));
                } else {
                    if (($tipo_filtro=="ver_licencias_mensuales")||($tipo_filtro=="ver_licencias_anuales") ||($tipo_filtro=="pagado_antes_mensuales")||($tipo_filtro=="pagado_antes_anuales")){
                       //echo $tipo_filtro; die();
                        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($cliente->idlicencias_empresa, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($cliente->fecha_activacion, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($cliente->fecha_inicio_licencia, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($cliente->fecha_vencimiento, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($cliente->nombre_plan, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($cliente->valor_plan, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($cliente->nombre_empresa, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($cliente->email, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($cliente->nombre_distribuidor, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, html_entity_decode($cliente->vendedor, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, html_entity_decode($cliente->dias_vigencia, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, html_entity_decode($cliente->phone, ENT_QUOTES, 'UTF-8'));
                    } else {
                        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($cliente->idlicencias_empresa, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($cliente->fecha_activacion, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($cliente->fecha_inicio_licencia, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($cliente->fecha_vencimiento, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($cliente->nombre_empresa, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($cliente->phone, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($cliente->email, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($cliente->nombre_plan, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($cliente->dias_vigencia, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, html_entity_decode($cliente->total, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, html_entity_decode($cliente->nombre_distribuidor, ENT_QUOTES, 'UTF-8'));
                        $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, html_entity_decode($cliente->vendedor, ENT_QUOTES, 'UTF-8'));

                        if (($tipo_filtro=="nuevos_mensuales") || ($tipo_filtro=="nuevos_anuales")) {
                            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, html_entity_decode($cliente->fecha_pago, ENT_QUOTES, 'UTF-8'));
                        }
                    }
                }
            }
            $count++;
            $row++;
        }

        $this->phpexcel->getActiveSheet()->setTitle($tipo_filtro);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$tipo_filtro.xls");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function info_fiscal(){
        $where="";
        $data['datos_empresas_info_fiscal']=$this->crm_model->info_fiscal($where);
        $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/informacion_fiscal',array('data' => $data));
    }

    public function correo_activo(){
        $correo = $this->input->post('correo');
        $data['user'] = $this->crm_model->get_user_activo(array('u.email'=>$correo));

        if(!empty($data['user'])){
            $db=$data['user'][0]['id'];
            $data['empresa'] = $this->crm_model->get_empresas(array('id_db_config' => $db));
            $data['success']=1;
        }else{
            $data['success']=0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function bd_existen(){
        $desde = $this->input->post('desde');
        $hasta = $this->input->post('hasta');
        $correo = $this->input->post('correo');
        $result = $this->crm_model->existenBD($desde,$hasta,$correo);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function migrar_bd(){
        if($_POST){
            $desde = $this->input->post('desde');
            $hasta = $this->input->post('hasta');
            $correo = $this->input->post('correo');
            $correo_id = $this->input->post('correo_id');
            $opcion = $this->input->post('opcion');
            $email=explode("vendty2_db_",$correo);

            // paso de data para el api
            $data = array(
                'origen' => $this->input->post('desde'),//2
                'destino' => $this->input->post('hasta'), //3
                'dbname' => $email[1]//21311_dann2019
            );

           $migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api'));

            if(isset($migrada->status) && isset($migrada->status) ){
                if(!$migrada->status && $migrada->description == "Verifica los datos enviados"){
                    $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
                }
            } else {
                $migrada=post_curl('migraciondb',$data,$this->session->userdata('token_api'));
            }

           if(isset($migrada->status) && $migrada->status){
                if(isset($migrada->status) && $migrada->description=='ok'){
                    $migrado=$this->crm_model->migrar_bd($desde,$hasta,$correo,$correo_id,$opcion);

                    if($migrado==0){
                        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
                    }else{
                        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
                    }
                }
           }else{
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
           }
        }else{
            $data['server'] = $this->crm_model->get_server();
            $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/migrar_bd',array('data' => $data));
        }
    }


    public function info_fiscal_cliente($fecha_inicio=null,$fecha_fin=null){
        $where="";

        if((empty($fecha_inicio)) &&(empty($fecha_inicio))){

            $fecha_inicio=date('Y-m-01');
            $fecha_inicio = strtotime($fecha_inicio);
            $fecha_inicio = date("Y-m-d", strtotime("-1 month", $fecha_inicio));

            $date = date_create($fecha_inicio);
            $fecha_fin=date_format($date, 'Y-m');
            $fecha_fin.="-".$this->crm_model->getMonthDays_mes($fecha_fin);
        }

        $where="fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
        $data['datos_empresas_info_fiscal']=$this->crm_model->info_fiscal_cliente($where);
        $data['fecha_inicio']=$fecha_inicio;
        $data['fecha_fin']=$fecha_fin;
        $this->layout->template('administracion_vendty')->show('administracion_licencia/empresas/informacion_fiscal_totales_cliente',array('data' => $data));
    }

    public function export_info_fiscal_excel($fecha_inicio=null,$fecha_fin=null){
         $where="";

        if((empty($fecha_inicio)) &&(empty($fecha_inicio))){

            $fecha_inicio=date('Y-m-01');
            $fecha_inicio = strtotime($fecha_inicio);
            $fecha_inicio = date("Y-m-d", strtotime("-1 month", $fecha_inicio));

            $date = date_create($fecha_inicio);
            $fecha_fin=date_format($date, 'Y-m');
            $fecha_fin.="-".$this->crm_model->getMonthDays_mes($fecha_fin);
        }

        $where="fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
        $data['datos_empresas_info_fiscal']=$this->crm_model->info_fiscal_cliente($where);
        $row = 2;
        $count = 0;
         //excel
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Id db');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Id Empresa');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Tipo Negocio');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre_empresa_config');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Tipo_documento_config');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Numero_documento_config');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Direccion_empresa_config');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Email_empresa_config');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Contacto_empresa_config');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Telefono_empresa_config');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Nombre_pais_config');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Nombre_empresa_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'tipo_identificacion_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('O1', 'numero_documento_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('P1', 'email_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('Q1', 'direccion_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('R1', 'contacto_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('S1', 'telefono_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('T1', 'pais_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('U1', 'ciudad_factura');
        $this->phpexcel->getActiveSheet()->setCellValue('V1', 'identificacion_tributaria_negocio');
        $this->phpexcel->getActiveSheet()->setCellValue('W1', 'tipo_negocio_config');
        $this->phpexcel->getActiveSheet()->setCellValue('X1', 'tipo_negocio_especializado');
        $this->phpexcel->getActiveSheet()->setCellValue('Y1', 'pais_negocio');
        $this->phpexcel->getActiveSheet()->setCellValue('Z1', 'ciudad_negocio');
        $this->phpexcel->getActiveSheet()->setCellValue('AA1', 'cantidad_almacenes');
        $this->phpexcel->getActiveSheet()->setCellValue('AB1', 'cantidad_bodegas');
        $this->phpexcel->getActiveSheet()->setCellValue('AC1', 'cantidad_cajas');
        $this->phpexcel->getActiveSheet()->setCellValue('AD1', 'cantidad_usuarios');
        $this->phpexcel->getActiveSheet()->setCellValue('AE1', 'cantidad_usuarios_activos');
        $this->phpexcel->getActiveSheet()->setCellValue('AF1', 'cantidad_usuarios_inactivos');
        $this->phpexcel->getActiveSheet()->setCellValue('AG1', 'cantidad_productos');
        $this->phpexcel->getActiveSheet()->setCellValue('AH1', 'cantidad_formas_pago');
        $this->phpexcel->getActiveSheet()->setCellValue('AI1', 'cantidad_formas_pago_activas');
        $this->phpexcel->getActiveSheet()->setCellValue('AJ1', 'cantidad_formas_pago_inactivas');
        $this->phpexcel->getActiveSheet()->setCellValue('AK1', 'tipo_moneda');
        $this->phpexcel->getActiveSheet()->setCellValue('AL1', 'simbolo_moneda');
        $this->phpexcel->getActiveSheet()->setCellValue('AM1', 'cantidad_facturas');
        $this->phpexcel->getActiveSheet()->setCellValue('AN1', 'cantidad_pagos_facturas');
        $this->phpexcel->getActiveSheet()->setCellValue('AO1', 'cantidad_pagos_en_facturas');
        $this->phpexcel->getActiveSheet()->setCellValue('AP1', 'cantidad_efectivo');
        $this->phpexcel->getActiveSheet()->setCellValue('AQ1', 'total_efectivo');
        $this->phpexcel->getActiveSheet()->setCellValue('AR1', 'cantidad_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('AS1', 'total_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('AT1', 'cantidad_puntos');
        $this->phpexcel->getActiveSheet()->setCellValue('AU1', 'total_puntos');
        $this->phpexcel->getActiveSheet()->setCellValue('AV1', 'cantidad_gift_card');
        $this->phpexcel->getActiveSheet()->setCellValue('AW1', 'total_gift_card');
        $this->phpexcel->getActiveSheet()->setCellValue('AX1', 'cantidad_nota_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('AY1', 'total_nota_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('AZ1', 'cantidad_bancolombia');
        $this->phpexcel->getActiveSheet()->setCellValue('BA1', 'total_bancolombia');
        $this->phpexcel->getActiveSheet()->setCellValue('BB1', 'cantidad_tarjeta_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('BC1', 'total_tarjeta_credito');
        $this->phpexcel->getActiveSheet()->setCellValue('BD1', 'cantidad_tarjeta_debito');
        $this->phpexcel->getActiveSheet()->setCellValue('BE1', 'total_tarjeta_debito');
        $this->phpexcel->getActiveSheet()->setCellValue('BF1', 'cantidad_tarjeta_credito_masterCard');
        $this->phpexcel->getActiveSheet()->setCellValue('BG1', 'total_tarjeta_credito_masterCard');
        $this->phpexcel->getActiveSheet()->setCellValue('BH1', 'cantidad_tarjeta_debito_visa');
        $this->phpexcel->getActiveSheet()->setCellValue('BI1', 'total_tarjeta_credito_visa');
        $this->phpexcel->getActiveSheet()->setCellValue('BJ1', 'cantidad_otros');
        $this->phpexcel->getActiveSheet()->setCellValue('BK1', 'total_otros');

        foreach ($data['datos_empresas_info_fiscal'] as $value) {
            $mes=explode("-",$value->fecha);

            switch($mes[1]){
                case '01':
                    $fecha = 'Enero-'.$mes[0];
                break;
                case '02':
                    $fecha = 'Febrero-'.$mes[0];
                break;
                case '03':
                    $fecha = 'Marzo-'.$mes[0];
                break;
                case '04':
                    $fecha = 'Abril-'.$mes[0];
                break;
                case '05':
                    $fecha = 'Mayo-'.$mes[0];
                break;
                case '06':
                    $fecha = 'Junio-'.$mes[0];
                break;
                case '07':
                    $fecha = 'Julio-'.$mes[0];
                break;
                case '08':
                    $fecha = 'Agosto-'.$mes[0];
                break;
                case '09':
                    $fecha = 'Septiembre-'.$mes[0];
                break;
                case '10':
                        $fecha = 'Octubre-'.$mes[0];
                break;
                case '11':
                        $fecha = 'Noviembre-'.$mes[0];
                break;
                case '12':
                    $fecha = 'Diciembre-'.$mes[0];
                break;
            }

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($fecha, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($value->id_db, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, html_entity_decode($value->id_empresa, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, html_entity_decode($value->tipo_negocio, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, html_entity_decode($value->nombre_empresa_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, html_entity_decode($value->tipo_documento_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, html_entity_decode($value->numero_documento_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, html_entity_decode($value->direccion_empresa_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, html_entity_decode($value->email_empresa_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, html_entity_decode($value->contacto_empresa_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, html_entity_decode($value->telefono_empresa_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, html_entity_decode($value->nombre_pais_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, html_entity_decode($value->nombre_empresa_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, html_entity_decode($value->tipo_identificacion_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, html_entity_decode($value->numero_documento_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, html_entity_decode($value->direccion_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, html_entity_decode($value->email_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, html_entity_decode($value->contato_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, html_entity_decode($value->telefono_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, html_entity_decode($value->pais_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, html_entity_decode($value->ciudad_factura, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, html_entity_decode($value->identificacion_tributaria_negocio, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, html_entity_decode($value->tipo_negocio_config, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, html_entity_decode($value->tipo_negocio_especializado, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, html_entity_decode($value->pais_negocio, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, html_entity_decode($value->ciudad_negocio, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, html_entity_decode($value->cantidad_almacenes, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, html_entity_decode($value->cantidad_bodegas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, html_entity_decode($value->cantidad_cajas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, html_entity_decode($value->cantidad_usuarios, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, html_entity_decode($value->cantidad_usuarios_activos, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, html_entity_decode($value->cantidad_usuarios_inactivos, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, html_entity_decode($value->cantidad_productos, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, html_entity_decode($value->cantidad_formas_pago, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, html_entity_decode($value->cantidad_formas_pago_activas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, html_entity_decode($value->cantidad_formas_pago_inactivas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, html_entity_decode($value->tipo_moneda, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, html_entity_decode($value->simbolo_moneda, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, html_entity_decode($value->cantidad_facturas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, html_entity_decode($value->cantidad_pagos_facturas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, html_entity_decode($value->cantidad_pagos_en_facturas, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, html_entity_decode($value->cantidad_efectivo, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, html_entity_decode($value->total_efectivo, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, html_entity_decode($value->cantidad_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, html_entity_decode($value->total_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, html_entity_decode($value->cantidad_puntos, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, html_entity_decode($value->total_puntos, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, html_entity_decode($value->cantidad_gift_card, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, html_entity_decode($value->total_gift_card, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, html_entity_decode($value->cantidad_nota_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, html_entity_decode($value->total_nota_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, html_entity_decode($value->cantidad_bancolombia, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, html_entity_decode($value->total_bancolombia, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, html_entity_decode($value->cantidad_tarjeta_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BC' . $row, html_entity_decode($value->total_tarjeta_credito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BD' . $row, html_entity_decode($value->cantidad_tarjeta_debito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BE' . $row, html_entity_decode($value->total_tarjeta_debito, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BF' . $row, html_entity_decode($value->cantidad_tarjeta_credito_masterCard, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BG' . $row, html_entity_decode($value->total_tarjeta_credito_masterCard, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BH' . $row, html_entity_decode($value->cantidad_tarjeta_debito_visa, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BI' . $row, html_entity_decode($value->total_tarjeta_credito_visa, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BJ' . $row, html_entity_decode($value->cantidad_otros, ENT_QUOTES, 'UTF-8'));
            $this->phpexcel->getActiveSheet()->setCellValue('BK' . $row, html_entity_decode($value->total_otros, ENT_QUOTES, 'UTF-8'));
            $count++;
            $row++;
        }

        $this->phpexcel->getActiveSheet()->setTitle('info_fiscal_totales_cliente');
        $nombre="info_fiscal_totales_cliente".$fecha_inicio."_".$fecha_fin.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='$nombre'");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }
    /*Se crean los métodos para poder consultar y descargar en excel informe de cuentas en prueba*/
    public function informe_prueba(){
        $this->layout->template('administracion_vendty')->show('administracion_licencia/informes/index');
    }

    public function get_ajax_informe_prueba(){

        $fechai=((isset($_GET['fecha_inicio']))?$_GET['fecha_inicio']:date("Y-m-01"));
        $fechaf=((isset($_GET['fecha_fin']))?$_GET['fecha_fin']:date("Y-m-d"));

        $this->output->set_content_type('application/json')->set_output(json_encode($this->crm_model->informe_prueba($fechai,$fechaf)));
    }

    public function get_ajax_ex_informe_prueba() {

        if((isset($_GET['fecha_inicio'])) && (!empty($_GET['fecha_inicio']))){
            $fechai=$_GET['fecha_inicio'];
        }else{
            $fechai= date("Y-m-01");
        }

        if((isset($_GET['fecha_fin'])) && (!empty($_GET['fecha_fin']))){
            $fechaf=$_GET['fecha_fin'];
        }else{
            $fechaf= date("Y-m-d");
        }

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Licencia');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Empresa');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Teléfono');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Username');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nombre');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Email');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Wizard');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Productos');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Facturas');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Fecha última Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('k1', 'Fecha Creación');
        $this->phpexcel->getActiveSheet()->setCellValue('l1', 'Fecha Inicio L');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Fecha Fin L');
        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'último Login');

        $query = $this->crm_model->informe_prueba($fechai,$fechaf);
        $row = 2;

        foreach ($query['aaData'] as $value) {
            echo "<br>va=".$value[0];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value[10]);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value[11]);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value[12]);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, $value[13]);

            $row++;
        }
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:O' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],

                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('info_pruebas' . date("Y-m-d"));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="info_pruebas"' . date("Y-m-d") . '".xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');              // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate');               // HTTP/1.1
        header('Pragma: public');                                      // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }
}

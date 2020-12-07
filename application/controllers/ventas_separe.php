<?php
//2015/12/18

class Ventas_separe extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("ventas_separe_model", 'separeModel');

        $this->separeModel->initialize($this->dbConnection);


        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);



        $this->load->model("vendedores_model", 'vendedores');

        $this->vendedores->initialize($this->dbConnection);


        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        /* $this->load->model("clientes_model",'clientes');

          $this->clientes->initialize($this->dbConnection); */


        $this->load->model("clientes_model", 'clientes');

        $this->clientes->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');

        $this->productos->initialize($this->dbConnection);


        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);


        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("facturas_model", 'facturas');

        $this->facturas->initialize($this->dbConnection);


        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

        $this->load->model("Caja_model",'caja');
        $this->caja->initialize($this->dbConnection);
        //agregar el id_venta a la tabla plan_separe_factura
        $this->separeModel->actualizarTabla_Plan_Separe_factura();
    }

    function nuevo() {


        if (!$this->ion_auth->logged_in())
            redirect('auth', 'refresh');

        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }



        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();


        $data['pais'] = $this->pais_provincia->get_pais();


        if ($data_empresa['data']['valor_caja'] == 'si') {

            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');

            if ($this->session->userdata('caja') == "") {
                $id_user = '';
                $almacen = '';
                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                foreach ($user as $dat) {
                    $id_user = $dat->id;
                }


                $hoy = date("Y-m-d");
                $query = $this->dbConnection->query('SELECT * FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ');
                $query->num_rows();

                if ($query->num_rows() == 1) {
                    $cierre_caja = 0;
                    $cierre = 'SELECT id FROM cierres_caja where fecha = "' . $hoy . '" and id_Usuario = "' . $id_user . '" and total_cierre = ""  ORDER BY fecha, hora_apertura desc limit 1 ';
                    $cierre_caja = $this->dbConnection->query($cierre)->row();

                    $this->session->set_userdata('caja', $cierre_caja->id);
                }
                if ($query->num_rows() == 0) {

                    header('Location: ../caja/apertura');
                }
            }
        }

        //Identifica si una venta fue por POS o por Servicios
        // POS
        if ($data_empresa['data']['tipo_factura'] != 'clasico') {

            $pago = $_POST['pago'];
            $tipo_factura = 'estandar';
            $fecha = date('Y-m-d H:i:s');
            $fecha_vencimiento = date('Y-m-d H:i:s');
        } else {//CLASICO

            $pago = array(
                'valor_entregado' => $_POST['total_venta'],
                'cambio' => 0,
                'forma_pago' => $_POST['forma_pago']
            );

            $tipo_factura = 'clasico';
            $fecha = $_POST['fecha'] . " " . date('H:i:s');
            $fecha_vencimiento = $_POST['fecha_v'];
        }



        $data = array(
            'fecha' => $fecha,
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] . " " . date('H:i:s'),
            'cliente' => $_POST['cliente'],
            'vendedor' => $_POST['vendedor'],
            'usuario' => $this->session->userdata('user_id'),
            'productos' => $_POST['productos'],
            'total_venta' => $_POST['total_venta'],
            'pago' => $pago,
            'tipo_factura' => $tipo_factura,
            'nota' => $_POST['nota'],
            'sobrecostos' => ((isset($_POST['propina'])) ? $_POST['propina'] : 0),
            'id_fact_espera' => (isset($_POST['id_fact_espera']) ? $_POST['id_fact_espera'] : ''),
            'sistema' => $data_empresa['data']['sistema'],
            'nota_plan_separe' => $_POST['nota_plan_separe']
        );




        //=======================================================================================
        //  PLAN SEPARE 
        //=======================================================================================

        /* Registrar venta */
        
        $total_pago=0;
        foreach($pago as $rowPago => $value){
            $total_pago += $value;
        }
        
        $id = $this->separeModel->add($data);
        // Validamos si lo separa con todo el valor de la compra
        if($total_pago == $_POST['total_venta']){
            $this->separeModel->setFacturar($id);
        }
        
        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));
    }

    //=====================================================================================
    //
    //          FACTURAS PLAN SEPARE
    //  
    //=====================================================================================
    //----------------------
    // VISTA INFORMES
    //----------------------
    public function facturas() {
        $this->separeModel->validateFields();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('separe/separeFacturas',array("data" => $data));
    }

    public function ex_ventas_separe($almacen = null){
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Usuario');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Identificación');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Fecha Vencimiento');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Tipo');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Estado');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Total Abonos');
        $query = $result = $this->separeModel->getAjaxFacturas();
        
        $row = 2;

        foreach ($query as $value) {
            $total_abonos = 0;
            $estado = '';
            if($value['estado'] == '2'){
                $estado = 'facturado';
            }
            if($value['estado'] == '0'){
                $estado = 'separado';
            }
            
            $pagos = $this->separeModel->get_pagos_all($value['id'],0);
            if(count($pagos) > 0){
                foreach($pagos as $pago):
                    $total_abonos += $pago->valor_entregado;
                endforeach;
            }
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['factura']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['usuario_id']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['nombre_comercial']);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value['fecha']);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value['total_venta']);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value['almacen_nombre']);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value['fecha_vencimiento']);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value['tipo_factura']);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $estado);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $total_abonos);
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

        $this->phpexcel->getActiveSheet()->getStyle('A1:K' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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
        $this->phpexcel->getActiveSheet()->setTitle('Informe de plan separe');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de plan separe.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        ob_clean();
        $objWriter->save('php://output');
        exit;
    }
     
    public function imprimir($id, $offset = 0){

        $this->load->model("miempresa_model",'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        
        //crear la opcion si no existe
        $this->mi_empresa->crearOpcion("publicidad_vendty",1);
        
        $get_by_id = $this->separeModel->get_by_id($id);
	   
	  
        $username='';
        $user = $this->db->query("SELECT username FROM users where id = '".$get_by_id["usuario_id"]."'")->result();
        foreach ($user as $dat) {
            $username = $dat->username;
        }			 

        $data['venta_credito'] = array(
            'venta' => $this->separeModel->get_by_id($id)
            ,'detalle_venta' => $this->separeModel->get_detalles_ventas($id)
            ,'detalle_pago' =>  $this->separeModel->get_detalles_pago($id)
        );

        $data['tipo'] = $this->pagos->get_tipos_pago();
        $data["total"] = $this->pagos->get_total($id);
        $data["data"] = $this->separeModel->get_all($id, $offset);
        $numero = $this->separeModel->get_by_id($id);
        $data['numero'] = $numero["factura"];
        $data["id_factura"] = $id;
        $data['publicidad_vendty'] = $this->mi_empresa->obtenerOpcion("publicidad_vendty");
        $data['data_empresa'] =  $data_empresa;
        
        $this->mi_empresa->crearOpcion("plantilla_general","media_carta");
        $imprimir = $this->opciones->getNombre("plantilla_general");
        $data["nota_plan_separe"] = '';
        $data_nota = json_decode($data['venta_credito']['venta']['nota']);
        if(isset($data_nota->nota_plan_separe)) {
            $data["nota_plan_separe"] = $data_nota->nota_plan_separe;
        }

        if($imprimir['valor_opcion'] == "media_carta")
        {
            $this->layout->template('ajax')->show('separe/_imprime', array('data' => $data)); 
        }else
        {
            $this->layout->template('ajax')->show('separe/_imprimeTirilla', array('data' => $data)); 
        }
        

  }


    public function eliminar() {
       
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band=$this->caja_abierta(); 
       
        if($band==1){
            $venta_id_ven = $_POST['venta_id_ven'];
            $dev = $_POST['dev'];
            $mensaje = $this->separeModel->delete($venta_id_ven, $dev);
            $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', $mensaje));
            redirect("ventas_separe/facturas");
        }else{
            if($band==0){
                $url=site_url("caja/apertura");
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', "Debe tener caja abierta para realizar este proceso, haga clic <a href='$url'>aqui</a> para aperturar caja"));
                redirect("ventas_separe/facturas");
            }
        }
        
    }
    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    public function getAjaxFacturas() {

        $result = $this->separeModel->getAjaxFacturas();
        
        for($i=0; $i<count($result); $i++){
            $result[$i]["total_abonos"] = 0;
            $pagos = $this->separeModel->get_pagos_all($result[$i]['id'],0);
            if(count($pagos) > 0){
                foreach($pagos as $pago):
                    $result[$i]["total_abonos"] += $pago->valor_entregado;
                endforeach; 
            }
        }
       
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    //=====================================================================================
    //
    //  DETALLE PLAN SEPARE
    //  
    //=====================================================================================
    //----------------------
    // VISTA PANEL DE EDICION DE PRODUCTOS CON ATRIBUTOS
    //----------------------

    public function detalle($id, $offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        
        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $data_empresa = $this->mi_empresa->get_data_empresa();        
        $estado = $this->separeModel->get_estado_factura($id);
        $data = array();

        $data['factura_separe'] = array(
            'venta' => $this->separeModel->get_separe_by_id($id),
            'detalle_venta' => $this->separeModel->get_separe_detalle_venta($id),
            'detalle_pago' => $this->separeModel->get_separe_detalles_pago($id),
            'data_empresa' => $data_empresa,
            'estado' => $estado,
            'id_factura' => $id
        );

        $data['tipo'] = $this->separeModel->get_pagos_tipos_pago();
        $data["total"] = $this->separeModel->get_pagos_total($id);
        $data["data"] = $this->separeModel->get_pagos_all($id, $offset);
        $data['forma_pago'] = $this->forma_pago->getAvaible();
        $numero = $this->separeModel->get_ventas_by_id($id);                
        $data['numero'] = ($numero["factura"]=="-") ? "" :$numero["factura"];
        $data["id_factura"] = $id;
        $data["estado_caja"] = "cerrada";

        //verifico si la caja esta abierta
        if ($this->session->userdata('caja') != ""){
            $data["estado_caja"] = "abierta";
        }
        else{
            //verifico si hay caja abierta y no la tengo en session 
            //verifico si hay una caja abierta para el usuario
            //verifico si hay cierre automatico
            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico           
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d"); 
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
                }else{
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'));
                }
            

                $orderby_cierre="fecha desc, hora_apertura desc";
                $limit_cierre="1";
                $cierre_caja=$this->caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);
                            
                if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){             
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $data["estado_caja"] = "abierta";
                } 
            }else{
                $data["estado_caja"] = "abierta";
            } 
       }
        $this->layout->template('member')->show('separe/separeFacturasDetalle', array('data' => $data));
    }

    function caja_abierta(){
        $band=0;
            $data_empresa = $this->mi_empresa->get_data_empresa();
           
            //verifico si la caja esta abierta
             //verifico si hay caja abierta y no la tengo en session 
            //verifico si hay una caja abierta para el usuario
            //verifico si hay cierre automatico
            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico           
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d"); 
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'),'fecha'=>$hoy);
                }else{
                    $where=array('id_Usuario'=>$this->session->userdata('user_id'));
                }
            
                $orderby_cierre="fecha desc, hora_apertura desc";
                $limit_cierre="1";
                $cierre_caja=$this->caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);            
                    
                if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){                             
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $band=1;
                } else{
                    $this->session->unset_userdata('caja');
                    $band=0;
                }
            }
            else{
                $band=1;
            }
        return $band;
    }

   /* function caja_abierta_check(){
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
        $ocpresult = $this->dbConnection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
              $valor_caja = $dat->valor_opcion;
        }
  
        if($valor_caja == 'no'){
          return true;
        }
  
        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
          return true;
        }else{
          return FALSE;
        }
      }*/
      
    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    
    public function nuevoPago($id) {


        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
       
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $band=$this->caja_abierta();                
        
        if(($this->input->post('cantidad')>0)&&($band==1)){
            $this->separeModel->addPago();
            $this->session->set_flashdata('message', custom_lang('sima_payment_created_message', 'Pago creado correctamente'));
        }else{
             if($band==0){                
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar un pago'));
            }else{
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'La cantidad debe ser mayor a 0'));   
            }  
        }

        redirect('ventas_separe/detalle/' . $id);
    }    
    
    
    public function setFacturar($idFactura) {

        $band=$this->caja_abierta(); 
        if($band==1){
            $this->separeModel->setFacturar($idFactura);
            echo '1';
        }else{
            if($band==0){
                echo '0';
            }             
        } 
    }
    
    
    public function setEditarProductoIndividual($idProducto) {

        if (!$idProducto)
            redirect('productos/', 'refresh');




        // Capturamos el String Json
        $dataJson = $this->input->post("dataJson");

        //Quitamos los [], por que sólo recibimos un objeto
        $dataJson = str_replace("[", "", $dataJson);
        $dataJson = str_replace("]", "", $dataJson);

        //Convertimos el string json a un OBJETO PHP
        $dataObj = json_decode($dataJson);



        //Configuration Upload and Image
        $image_name = "";
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
        $config['max_size'] = '2024';
        $config['max_width'] = '200000';
        $config['max_height'] = '2000000';

        $this->load->library('upload', $config);



        // -----------------------------------
        //    IMAGEN
        //
        
        //Si hay una imagen
        if (!empty($_FILES['imagen']['name'])) {

            // Si se subio correctamente
            if (!$this->upload->do_upload('imagen')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {

                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }
        } else {

            $image_name = $dataObj->producto1->imagen;
        }

        $nombreImagen = $image_name;

        //
        //   >>>   FIN IMAGEN
        // -----------------------------------


        $this->separeModel->setProductoAtributo($dataObj, $idProducto, $nombreImagen);

        redirect('atributos/editar/' . $idProducto, 'refresh');
    }

    public function getAjaxProductosEditar($idProducto) {
        $data = $this->separeModel->getProductoAtributo($idProducto);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

 
    public function plan_separe_anulado(){ 	
    $data  = array();	 
    $data_empresa = $this->mi_empresa->get_data_empresa();
    $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('separe/plan_separe_anulado', array('data' => $data));
    }

   public function get_ajax_data_plan_separe_anulado(){
        $this->output->set_content_type('application/json')->set_output(json_encode($this->separeModel->plan_separe_anulado()));
    }   
    
    public function eliminarProducto($id=false)
    {
        if($id != false)
        {
            $this->separeModel->eliminarProducto($id);
            echo json_encode(array("resp"=>1));
        }
    }

    public function eliminarPago($id,$factura)
    {
        
        if((!empty($id))&&(!empty($factura))){
           
            //verificar si existe el pago en la factura
            $existe=$this->separeModel->existepagoenventas(array('id_pago'=>$id,'id_venta'=>$factura));

            if(!empty($existe)){
                //elimino el registro
                $this->separeModel->deletepago(array('id_pago'=>$id,'id_venta'=>$factura));
                $this->session->set_flashdata('message', custom_lang('sima_payment_created_message', 'Pago Eliminado Correctamente'));                
                
            }else{
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Pago no pudo ser Eliminado'));  
            }
        }else{
            $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Pago no pudo ser Eliminado'));  
        }
        redirect('ventas_separe/detalle/' . $factura);
    }
}

?>
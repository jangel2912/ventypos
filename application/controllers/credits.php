<?php

class Credits extends CI_Controller 

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

        $this->load->model("credito_model",'creditos');
        $this->creditos->initialize($this->dbConnection);

        $this->load->model("credits_model",'credits');
        $this->credits->initialize($this->dbConnection);

        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);

        $this->load->model("impuestos_model",'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("ventas_model",'ventas');
        $this->ventas->initialize($this->dbConnection);


        $this->load->model("pagos_model",'pagos');
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("forma_pago_model",'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        
        $this->load->model("Caja_model",'box');
        $this->box->initialize($this->dbConnection);

        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

    }

    function index($offset = 0){
        session_start();
        if(!isset($_SESSION['api_auth'])){
            redirect('auth/logout', 'refresh');
        }
        $api_auth = (isset(json_decode($_SESSION['api_auth'])->token)) ? json_decode($_SESSION['api_auth'])->token : '';
        $this->session->set_userdata('page_backup', 'credito');
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        if ($this->session->userdata('token_api') == "")
        {
            if($api_auth){
                $this->session->set_userdata('token_api', $api_auth);
            }else{
                redirect('auth/logout', 'refresh');
            }
        }

        acceso_informe('Cuentas por cobrar');
        $data["total"] = $this->creditos->get_total_pendientes();     

        $data['data'] = $this->creditos->get_all_pendientes($offset);

        $data['monto_total'] = $this->creditos->get_sum_pendientes();

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

        $this->layout->template('member')
            ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))
            ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))
        ->show('credits/index', array('data' => $data));
    }


	function customer($customer_id){
        /*session_start();
        if(!isset($_SESSION['api_auth'])){
            redirect('auth/logout', 'refresh');
        }
        $api_auth = (isset(json_decode($_SESSION['api_auth'])->token)) ? json_decode($_SESSION['api_auth'])->token : '';
        if (!$this->ion_auth->logged_in()){
            redirect('auth', 'refresh');
        }

        if ($this->session->userdata('token_api') == "")
        {
            if($api_auth){
                $this->session->set_userdata('token_api',$api_auth);
            }else{
                redirect('auth/logout', 'refresh');
            }
        }*/

        $datacurrency = (object) array(
            'symbol' => $this->opciones->getDataMoneda()->simbolo,
            'decimals' => $this->opciones->getDataMoneda()->decimales,
            'thousands_sep' => $this->opciones->getDataMoneda()->tipo_separador_miles,
            'decimals_sep' => $this->opciones->getDataMoneda()->tipo_separador_decimales
        );
        
        $this->verifyStateBox();
        $data["total"] = $this->creditos->get_total_pendientes();     

        $data['data'] = $this->creditos->get_all_pendientes(15);

        $data['monto_total'] = $this->creditos->get_sum_pendientes();

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $data["customers"] =  (array) get_curl("customers",$this->session->userdata('token_api'));
        $data["customer_id"] =  $customer_id;
        $data["data-currency"] = get_curl("data-currency",$this->session->userdata('token_api'));
        $data["payment_methods"] = $this->forma_pago->getAvaible();
        
        $this->layout->template('member')
        ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))
        ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))
        ->show('credits/customer.php', array('data' => $data, 'datacurrency' => $datacurrency));
    }

    function getInvoicesByClient($customer_id){
        //$this->output->set_content_type('application/json')->set_output(json_encode($this->creditos->get_ajax_data()));
        echo json_encode($this->credits->getInvoicesByClient($customer_id));
    }

    function exportInvoicesByClient($customer_id){
        
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Total venta');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Retención');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Total pendiente');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Fecha factura');;

        $query = $this->credits->getInvoicesByClient($customer_id);
       
        $row = 2;
        foreach ($query as $value) {
            
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $value['invoice']);
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $value['client']);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value['totalSale']);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $value['retention']);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$row, $value['totalPending']);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$row, $value['date']);
            $row++;
        }

        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );

        $this->phpexcel->getActiveSheet()->getStyle('A1:F'.--$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            array(
                'font'    => array(
                    'bold'      => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0'
                    ),
                    'endcolor'   => array(
                        'argb' => 'FFFFFFFF'
                    )
                )
            )
        );

        $this->phpexcel->getActiveSheet()->setTitle('Facturas a credito');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="facturas_credito.xls"');
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

    function loadAccountStatus($customer_id){
        echo json_encode($this->credits->loadAccountStatus($customer_id));
    }

    function payBills(){
        $data = axios();

        if( ($data['totalPaid'] >= $data['totalPayment']) && $data['customer_id'] != 0):
            $response = (post_curl('credits/payment',json_encode($data),$this->session->userdata('token_api'))); 
            $data = array(
                'request' =>  $data,
                'response' => $response 
            );
            echo json_encode($data);
        endif;
    }

    function getCreditNotes($customer_id){
        echo json_encode($this->credits->getCreditNotes($customer_id));
    }


    function exportCreditNotesByClient($customer_id){
        
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Consecutivo');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Total');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Estado');

        $query = $this->credits->getCreditNotes($customer_id);
       
        $row = 2;
        foreach ($query as $value) {
            
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $value['consecutive']);
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $value['total']);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value['invoice']);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $value['date']);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$row, $value['state']);
            $row++;
        }

        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        );

        $this->phpexcel->getActiveSheet()->getStyle('A1:E'.--$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
            array(
                'font'    => array(
                    'bold'      => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0'
                    ),
                    'endcolor'   => array(
                        'argb' => 'FFFFFFFF'
                    )
                )
            )
        );

        $this->phpexcel->getActiveSheet()->setTitle('Notas credito');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="notas_credito.xls"');
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

    function verifyStateBox(){
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
            $cierre_caja=$this->box->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);            
            
            if((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))){                             
                $this->session->set_userdata('caja', $cierre_caja->id);
                $band=1;
            } else{
                $this->session->unset_userdata('caja');
                $band=0;
            }
        }else{
            $band=1;
        }
        return $band;
    }

    public function getCredits(){

        echo json_encode($this->credits->getCredits());

        // $this->output->set_content_type('application/json')->set_output(json_encode($this->credits->getCredits()));
    }

    public function getDetailInvoice($id,$offset = 0){

        //die($this->opciones->getNombre('valor_caja')['valor_opcion']);

        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();
        $data['venta_credito'] = array(
           'venta' => $this->ventas->get_by_id($id),
           'detalle_venta' => $this->ventas->get_detalles_ventas($id),
           'detalle_pago' =>  $this->ventas->get_detalles_pago($id),
           'data_empresa' =>  $data_empresa
       );

        $data['tipo'] = $this->pagos->get_tipos_pago();
        $data["total"] = $this->pagos->get_total($id);
        $data["data"] = $this->pagos->get_all($id, $offset);
        $data["forma_pago"] = $this->forma_pago->getAvaible();
        $numero = $this->ventas->get_by_id($id);
        $data['numero'] = $numero["factura"];
        $data["id_factura"] = $id;   
        $data["estado_caja"] = "cerrada";

        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user=$this->session->userdata('user_id');
        
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
        
        echo json_encode($data);
    }

    /* Abonar a una factura */
    public function payInvoice(){
        $data = axios();
        if( ($data['payTotal'] > 0) && $data['payInvoice'] != 0):
            $response = $this->credits->addPayInvoice($data);
            echo json_encode($response); 
        endif; 
    }
}

?>
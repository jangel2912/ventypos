<?php

/* * ******************************************* */
/** Módulo Producción                       ** */
/** Descripción: Gestión de producción      ** */
/** y traslado a producto final en almacen  ** */
/** y traslado a producto final en almacen  ** */
/** Dev: Leonardo Molina                    ** */

/** Date: Enero 2017                        ** */
/* * ******************************************* */

class Produccion extends CI_Controller {

    var $dbConnection;

    function __construct() {
        parent::__construct();
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("presupuestos_model", 'presupuestos');
        $this->presupuestos->initialize($this->dbConnection);

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $this->load->model("opciones_model", 'opciones');

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);
        $this->load->model("produccion_model", 'produccion');
        $this->produccion->initialize($this->dbConnection);
        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->connection = $this->dbConnection;

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $data["puedofacturar"]=0;
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
        if (($puedofacturar == 1) && ($this->session->userdata('db_config_id') != 2547)) {
           $data["puedofacturar"]=1;
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('produccion/index', array('data' => $data));
    }

    public function nuevo(){

        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
        if (($puedofacturar == 1) && ($this->session->userdata('db_config_id') != 2547)) {
            $url = site_url("frontend");
            echo '
        <script>
            alert("Lo sentimos su usuario esta asignado a una Bodega, por lo cual no puede facturar");           
            window.location="index"; 
        </script>';
        }

        $produccion_id = $this->input->post('produccion_id');
        $data = array();
        if ($produccion_id != false) {
            $data['produccion'] = $this->produccion->get_data($produccion_id);
        }
        
        $array_compuestos = array();
        $array_compuestos[0] = 'Seleccionar';
        foreach($this->productos->get_compuestos_by_produccion() as $rowComp){
            $array_compuestos[$rowComp->id] = $rowComp->nombre;
        }
        $array_final = array();
        $array_final[0] = 'Seleccionar';
        foreach($this->productos->get_producto_final() as $rowFinal){
            $array_final[$rowFinal['id']] = $rowFinal['nombre'];
        }

        $data['array_almacenes'] = $this->almacenes->get_combo_data();
        $data['array_final'] = $array_final;
        $data['array_compuestos'] = $array_compuestos;
        $data['produccion_id'] = $produccion_id;
        $data['produccion_detalle'] = $this->produccion->get_data_detail($produccion_id);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('produccion/nueva_produccion', $data);
    }

    public function get_ajax_data() {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->produccion->get_ajax_data( $_REQUEST )));
    }

    public function delete(){
        $id = $this->input->post('id');
        $response = $this->produccion->delete($id);

        echo json_encode($response);

    }

    public function getProduccion(){
        $produccion_id = $this->input->post('produccion_id');
        $data = array();
        if ($produccion_id != false) {
            $data['produccion'] = $this->produccion->get_data($produccion_id);
        }
        $data['produccion_detalle'] = $this->produccion->get_data_detail($produccion_id);
        $view = $this->load->view('produccion/table_produccion', $data, true);
        return $this->output
                        ->set_header("HTTP/1.0 200 OK")
                        ->set_content_type('application/json')
                        ->set_output(json_encode($view));
    }

    public function view_modal() {
        $produccion_id = $this->input->post('produccion_id');
        $data = array();
        if ($produccion_id != false) {
            $data['produccion'] = $this->produccion->get_data($produccion_id);
        }
        
        $array_compuestos = array();
        $array_compuestos[0] = 'Seleccionar';
        foreach($this->productos->get_compuestos() as $rowComp){
            $array_compuestos[$rowComp->id] = $rowComp->nombre;
        }
        $array_final = array();
        $array_final[0] = 'Seleccionar';
        foreach($this->productos->get_producto_final() as $rowFinal){
            $array_final[$rowFinal['id']] = $rowFinal['nombre'];
        }
        
        $data['array_almacenes'] = $this->almacenes->get_combo_data();
        $data['array_final'] = $array_final;
        $data['array_compuestos'] = $array_compuestos;
        $data['produccion_id'] = $produccion_id;
        $data['produccion_detalle'] = $this->produccion->get_data_detail($produccion_id);
        
        $view = $this->load->view('produccion/view_modal', $data, true);

        return $this->output
                        ->set_header("HTTP/1.0 200 OK")
                        ->set_content_type('application/json')
                        ->set_output(json_encode($view));
    }
    
    public function save_produccion(){
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('fecha', 'Fecha', 'required|xss_clean');
            $this->form_validation->set_rules('almacen_id', 'Almacen', 'required|xss_clean');
            
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                
                $data_array = array(
                    'consecutivo' => $this->produccion->get_consecutivo(),
                    'fecha'       => $this->input->post('fecha'),
                    'usuario_id'  => $this->session->userdata('user_id'),
                    'almacen_id'  => $this->input->post('almacen_id')
                );
                // Save produccion
                if($this->input->post('produccion_id')){
                    $this->connection->where('id', $this->input->post('produccion_id'));
                    $this->connection->update('produccion', $data_array);
                    $produccion_id = $this->input->post('produccion_id');
                    
                }else{
                    $data_array['estado'] = 1;// Estado Creacion 
                    $data_array['fecha_creacion'] = date('Y-m-d h:i:s');
                    $this->connection->insert('produccion', $data_array);
                    $produccion_id = $this->connection->insert_id();
                }
                
                $result = $this->connection->query("SELECT IF(estado = 0, 'Eliminado', 
                                                                IF(estado = 1, 'Creado', 
                                                                    IF(estado = 2, 'Confirmado', 'Trasladado')
                                                                ) 
                                                            ) AS estado FROM produccion WHERE id = '{$produccion_id}'");
                $estado = $result->row()->estado;
                
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success',
                    'produccion_id' => $produccion_id,
                    'estado' => $estado
                );
            }
            // Respuesta en Json
            //echo json_encode($data);
            // Respuesta en Json
            
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }
    
    public function add_product_produccion(){
        
        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('produccion_id', 'Producción', 'required|xss_clean');
            $this->form_validation->set_rules('producto_id', 'Producto a producción', 'required|xss_clean');
            $this->form_validation->set_rules('producto_final_id', 'Producto Final', 'required|xss_clean');
            $this->form_validation->set_rules('cantidad', 'Cantidad', 'required|numeric|xss_clean');
            
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                // Save product_produccion
                $data_array = array(
                    'produccion_id' => $this->input->post('produccion_id'),
                    'producto_id' => $this->input->post('producto_id'),
                    'producto_final_id' => $this->input->post('producto_final_id'),
                    'cantidad' => $this->input->post('cantidad')
                );
                if($this->input->post('produccion_detalle_id')){
                    $this->connection->where('id', $this->input->post('produccion_id'));
                    $this->connection->update('produccion_detalle', $data_array);
                    $id_detalle = $this->input->post('produccion_id');
                }else{
                    $this->connection->insert('produccion_detalle', $data_array);
                    $id_detalle = $this->db->insert_id();
                }
                
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success',
                    'id_detalle' => $id_detalle
                );
            }
            // Respuesta en Json
            //echo json_encode($data);
            // Respuesta en Json
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }
    
    
    public function confirm_produccion(){
        
        $connection = $this->connection;
        $data = array();
        
        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('produccion_id', 'Producción', 'required|xss_clean');
            $this->form_validation->set_rules("produccion_detalle_id[]", 'Detalle de Producción', 'required|xss_clean');
            $this->form_validation->set_rules("cantidad[]", 'Cantidad', 'required|xss_clean');
           
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                $data_array = array(
                    'produccion_id' => $this->input->post("produccion_id"),
                    'almacen_id' => $this->input->post("almacen_id"),
                    'produccion_detalle_id' => $this->input->post("produccion_detalle_id"),
                    'cantidad'  => $this->input->post("cantidad")
                );
                try {
                    $response = $this->produccion->confirm_produccion($connection, $data_array );
                    if ($response['cod_status']):
                        $connection->trans_rollback();
                        throw new Exception($response['msg_status'], $response['cod_status']);
                    endif;
                    $connection->trans_commit();
                    $data = array(
                        'success' => "Se ha confirmado la producciòn con éxito!",
                        'res' => 'success'
                    );
                } catch (Exception $ex) {
                    $data = array(
                        'error' =>  $exc->getMessage(),
                        'res' => 'error'
                    );
                }
            }           
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($data));
        }
    }
    public function update_stock(){
        
        $connection = $this->connection;
        
        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('almacen_id', 'Producción', 'required|xss_clean');
            $this->form_validation->set_rules('almacen_traslado_id', 'Producción', 'required|xss_clean');
            $this->form_validation->set_rules('produccion_id', 'Producción', 'required|xss_clean');
            $this->form_validation->set_rules("produccion_detalle_id[]", 'Detalle de Producción', 'required|xss_clean');
            $this->form_validation->set_rules("cantidad[]", 'Cantidad', 'required|xss_clean');
           
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                 $data_array = array(
                    'produccion_id' => $this->input->post("produccion_id"),
                    'almacen_id' => $this->input->post("almacen_id"),
                    'almacen_traslado_id' => $this->input->post("almacen_traslado_id"),
                    'produccion_detalle_id' => $this->input->post("produccion_detalle_id"),
                    'cantidad'  => $this->input->post("cantidad")
                );
                 
                try {
                    
                    $response = $this->produccion->update_stock($connection, $data_array );
                    if ($response['cod_status']):
                        $connection->trans_rollback();
                        throw new Exception($response['msg_status'], $response['cod_status']);
                    endif;
                    $connection->trans_commit();
                    $data = array(
                        'success' => "Se ha Trasladado la producciòn con éxito!",
                        'res' => 'success'
                    );
                    
                } catch (Exception $ex) {
                    $data = array(
                        'error' =>  $exc->getMessage(),
                        'res' => 'error'
                    );
                }
            }
        }
        $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($data));
        
    }
    
    public function informe(){
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        acceso_informe('Informe de Producción');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('produccion/informe',array('data'=>$data));
    }
    
    public function expexcel(){
        
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Consecutivo');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Usuario');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Estado');
       
        $query = $this->produccion->get_ajax_data( $_REQUEST );
        
        $row = 2;
        
        foreach ($query['aaData'] as $value):
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $row++;
        endforeach;
        
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:E' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
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

        $this->phpexcel->getActiveSheet()->setTitle('Producción');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="producción.xls"');
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
    
    /**
     * Realiza la exportacion a excel de los 
     */
    public function export_producion( )
    {
        $id = $_REQUEST['id'];
        $objProduccion = $this->produccion->findIdProd( $id );
        
        $this->load->library('phpexcel');
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        /**
         * Muestra el Excel
         */
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Consecutivo');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Nombre Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Producto en Produccion');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Producto Final');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Cantidad');
        
        $row = 2;
        //$value = $ventas;
       
        foreach ($objProduccion->producto as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $objProduccion->consecutivo);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $objProduccion->fecha);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $objProduccion->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->produccion->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->final->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->cantidad);
            
            $row=$row+1;
        }
              
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1' . $row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
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

        $this->phpexcel->getActiveSheet()->setTitle("Informe de Ventas por dia ");
        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Ventas por dia " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function imprimir_prod()
    {
        $id = $_REQUEST['id'];
        $objProduccion = $this->produccion->findIdProd( $id );
        //echo json_encode($objProduccion);die();
        require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', "LETTER");   
    
        
        $html.= '<div><h2 style="text-align:center"> ORDEN DE PRODUCCI&Oacute;N  </h2></div>';
        $html .= '<hr>';
    
        $html .= '
        <table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
            <tr>        
                <th align="left"><b>Consecutivo</b></th>                           
                <th align="left"><b>Fecha</b></th>
                <th align="left"><b>Almac&eacute;n</b></th>
                <th align="left"><b>Producto en Producci&oacute;n</b></th>
                <th align="left"><b>Producto Final</b></th>
                <th align="left"><b>Cantidad</b></th>
            </tr>
        ';
        //$html.='<body>';
        foreach ( $objProduccion->producto as $producto ) {
            $html.='<tr>';
            $html.='<td>'.$objProduccion->consecutivo.'</td>';
            $html.='<td>'.$objProduccion->fecha.'</td>';
            $html.='<td>'.$objProduccion->nombre.'</td>';
            $html.='<td>'.$producto->produccion->nombre.'</td>';
            $html.='<td>'.$producto->final->nombre.'</td>';
            $html.='<td>'.$producto->cantidad.'</td>';
            $html.='</tr>';
        }
        
        $html .= '</table>';  
        $html .= '<hr>';

        $total_descuento = 0; $total_impuesto = 0;
        
        $total_valor = 0; 
        
        ob_clean();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('cuadre de caja.pdf', 'I');
  
    }

    function removeProduct(){
        $id = $this->input->post('id');
        $response = $this->produccion->removeProduct($id);
        $data = array();

        if($response['message'] == 'success'){
            $data = array(
                'success' => "Se ha eliminado el producto con éxito!",
                'res' => 'success'
            );
        }else{
            $data = array(
                'error' => "Error al eliminar el producto!",
                'res' => 'error'
            );
        }

        $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($data));
    }
}
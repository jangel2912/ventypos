<?php

class Facturas extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("facturas_model", 'facturas');

        $this->facturas->initialize($this->dbConnection);

        $this->load->model("pais_provincia_model",'pais_provincia');
            

        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);



        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    
    
    /* ======================================================= */
    //  IMPORTAR EXCEL
    /* ======================================================= */

    public function importarExcel() {

        $data = array();

        $data['categorias'] = $this->facturas->getCategorias();
        $data['impuestos'] = $this->facturas->getImpuestos();
        $data['almacenes'] = $this->facturas->getAlmacenes();
        $data['productos'] = $this->facturas->getProductos();
        $data['formasPago'] = $this->facturas->getFormasPago();
        $data['vendedores'] = $this->facturas->getVendedores();
        $data['clientes'] = $this->facturas->getClientes();
        $data['prefijo'] = $this->facturas->getPrefijo();
        $data['ciudades'] = $this->pais_provincia->get_provincia_from_pais("Colombia");
        
        //$data['formasPago'] = $this->facturas->getFormasPago();
        
        $this->layout->template('member')->show('facturas/facturaExcel', array('data' => $data));
    }

    //----------------------
    // LOGICA Y CONEXION MODELO
    //----------------------

    public function setAjaxFacturaExcel() {
        $jsonString = $this->input->post("data");
        $dataObj = json_decode($jsonString);
        $this->facturas->setAjaxFacturasExcel($dataObj);
    }

    /* ======================================================= */
    //  IMPORTAR EXCEL
    /* ======================================================= */



    function index_pendientes($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data["total"] = $this->facturas->get_total_pendientes();

        $data['data'] = $this->facturas->get_all_pendientes($offset);

        $data['monto_total'] = $this->facturas->get_sum_pendientes();

        $this->layout->template('member')->show('facturas/index_pendientes', array('data' => $data));
    }

    function index_pagadas($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data["total"] = $this->facturas->get_total_pagadas();

        $data['data'] = $this->facturas->get_all_pagadas($offset);

        $data['monto_total'] = $this->facturas->get_sum_pagadas();

        $this->layout->template('member')->show('facturas/index_pagadas', array('data' => $data));
    }

    function nuevo() {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $data['cod'] = $this->_codigo();

        if ($this->form_validation->run('facturas') == true) {


            $data = $this->facturas->add();

            /* $index = array();

              $index['id'] = $data['id_factura'];

              $index['type'] = "facturas";

              $index['contents'] = "Numero {$data['numero']}, Monto total {$data['monto']}, Monto sin impuesto {$data['monto_siva']}";

              $this->load->library('zend');

              $this->zend->index_data($index); */



            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
            
        } else {

            $data['impuestos'] = $this->impuestos->get_combo_data_factura();

            $this->layout->template('member')->show('facturas/nuevo', array('data' => $data));
        }
    }

    function editar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('facturas_editar') == true) {

            $this->facturas->update();

            $this->session->set_flashdata('message', custom_lang('sima_bill_updated_message', "Se ha salvado correctamente"));

            redirect("facturas/index_pendientes");
        }



        $data['data'] = $this->facturas->get_by_id($id);

        $data['detail'] = $this->facturas->get_detail($id);



        $this->layout->template('member')->show('facturas/editar', array('data' => $data));
    }

    function detalles($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('facturas_editar') == true) {

            $this->facturas->update();

            $this->session->set_flashdata('message', custom_lang('sima_bill_updated_message', "Se ha salvado correctamente"));

            redirect("facturas/index_pendientes");
        }



        $data['data'] = $this->facturas->get_by_id($id);

        $data['detail'] = $this->facturas->get_detail($id);



        $this->layout->template('member')->show('facturas/editar', array('data' => $data));
    }

    function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->facturas->delete($id);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha eliminado correctamente"));

        redirect("facturas/index_pendientes");
    }

    function imprimir($id = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $empresa = $this->miempresa->get_data_empresa();

        $user_id = $this->session->userdata('user_id');

        $data_factura = $this->facturas->get_by_id($id);

        $detail = $this->facturas->get_detail($id);



        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        $total = 0;

        switch ($empresa["data"]["plantilla"]) {

            case "Moderno":

                $pdf->Image('public/img/Header.jpg', 0, 0, 0, 0, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

                $pdf->SetTextColor(104, 168, 212);

                $pdf->SetFont("", "B", 15);

                $pdf->SetY(40);

                $pdf->Cell(40, 10, $empresa["data"]['nombre']);

                $pdf->SetX(100);

                $right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li>{$empresa["data"]['direccion']}</li>

                                                <li>{$empresa["data"]['telefono']}</li>

                                                <li>{$empresa["data"]['email']}</li>

                                           </ul>";

                $pdf->SetFont("", "", 8);

                $pdf->writeHTMLCell(100, '', 100, 42, $right_column, 0, 0, 1, true, 'L', true);

                // 

                $last_right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li>{$empresa["data"]['resolucion']}</li>

                                                <li>{$empresa["data"]['web']}</li>

                                           </ul>";



                $pdf->writeHTMLCell(100, '', 160, 42, $last_right_column, 0, 0, 1, true, 'R', true);





                $facturar_a = "

                                   <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li><h1>{$data_factura['nombre_comercial']}</h1></li>

                                                <li>{$data_factura['direccion']}</li>

                                                <li>{$data_factura['telefono']}</li>    

                                                <li>{$data_factura['pais']}</li>

                                           </ul>";

                $pdf->writeHTMLCell(90, '', 5, 60, $facturar_a, 0, 0, false, false, 'L', true);



                $invoice_data = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li><b>Fecha de la factura</b> " . date("d/m/Y", strtotime($data_factura['fecha'])) . "</li>

                                                <li><b>Fecha de vencimiento</b> " . date("d/m/Y", strtotime($data_factura['fecha_v'])) . "</li>

                                                <li><b>No. de factura</b> " . $data_factura['numero'] . "</li>

                                           </ul>";



                $pdf->writeHTMLCell(90, '', 150, 70, $invoice_data, 0, 0, false, true, 'L', true);

                $html = "<div>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>";

                $pdf->writeHTMLCell(220, '', 15, 85, $html, 0, 0, 0, false, true, 'R', true);

                $pdf->Ln(13);

                $pdf->SetFont("", "B", 8);



                $html = <<<EOD

                               

                                  <table BORDER=0 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr>

                                        <th width="25%"><strong>Nombre</strong></th>

                                        <th width="45%"><strong>Descripción</strong></th>

                                        <th width="10%"><strong>Cantidad</strong></th>

                                        <th width="10%"><strong>Precio</strong></th>

                                        <th width="10%"><strong>Subtotal</strong></th>

                                    </tr>

EOD;



                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%">{$k['nombre']}</td>

                                        <td width="45%">{$k['descripcion_d']}</td>

                                        <td width="10%">{$k['cantidad']}</td>

                                        <td width="10%">{$k['precio']}</td>

                                        <td width="10%">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');



                $html = "<div>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>";

                $pdf->writeHTMLCell(220, '', 15, 210, $html, 0, 0, 0, false, true, 'R', true);

                $pdf->Ln(13);



                $pdf->Cell("130", "5", "Gracias por su compra", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();







                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();



                break;



            case "Clasico" :



                $pdf->SetFont("", "B", 10);

                $pdf->SetFillColor(255, 255, 255);



                $pdf->Write(10, $empresa["data"]['nombre']);

                $pdf->SetFont("", "", 8);

                $pdf->setCellHeightRatio(1.00);

                $pdf->writeHTMLCell(100, '', 10, 18, $empresa["data"]['cabecera_factura'], 0, 0, 1, true, 'L', true);

                $right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                            line-height: 2px;

                                        }

                                    </style> 

                                           <ul>

                                                <li><h1>Factura de venta</h1></li>

                                                <li><b>Fecha de la factura</b> " . date("d/m/Y", strtotime($data_factura['fecha'])) . "</li>

                                                <li><b>Fecha de vencimiento</b> " . date("d/m/Y", strtotime($data_factura['fecha_v'])) . "</li>

                                                <li><b>No. de factura</b> " . $data_factura['numero'] . "</li>

                                           </ul>";



                $pdf->writeHTMLCell(100, '', 150, 3, $right_column, 0, 0, 1, true, 'R', true);

                $pdf->Ln();



                $facturar_a = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-bottom: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr class="header">

                                            <th>Facturar a</th>

                                        </tr>

                                        <tr>

                                            <td>

                                                <strong>{$data_factura['nombre_comercial']}</strong><br/>

                                                &nbsp;&nbsp;{$data_factura['direccion']}<br/>

                                                &nbsp;&nbsp;{$data_factura['telefono']}<br/>

                                                &nbsp;&nbsp;{$data_factura['pais']}<br/>

                                            </td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 10, 40, $facturar_a, 0, 0, 1, true, '', true);



                $pdf->Ln(40);



                $html = <<<EOD

                               

                                  <table BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr style="background-color: '#CCCCCC;">

                                        <th width="25%">Nombre</th>

                                        <th width="45%">Descripción</th>

                                        <th width="10%">Cantidad</th>

                                        <th width="10%">Precio</th>

                                        <th width="10%">Subtotal</th>

                                    </tr>

EOD;

                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%" style="border-top: 1px solid #000000;">{$k['nombre']}</td>

                                        <td width="45%" style="border-top: 1px solid #000000;">{$k['descripcion_d']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['cantidad']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['precio']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');

                $pdf->Cell("130", "5", "Gracias por su compra", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], "T", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();

                break;

            default:

                $pdf->SetFont("", "", 8);

                if (!empty($empresa["data"]['logotipo'])) {

                    $pdf->Image('uploads/' . $empresa["data"]['logotipo'], 10, 10, 95, 20, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);
                }

                $pdf->SetFillColor(255, 255, 255);

                $top_right_column = '<b>' . $empresa["data"]['nombre'] . '</b><br/>' . $empresa["data"]['cabecera_factura'];



                $pdf->writeHTMLCell(100, '', '110', 10, $top_right_column, 0, 0, 1, true, 'R', true);



                $pdf->Image('public/img/factura_de_venta.JPG', 10, 35, '', '', 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

                $pdf->Ln(10);



                $facturar_a = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-bottom: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr class="header">

                                            <th>Facturar a</th>

                                        </tr>

                                        <tr>

                                            <td>

                                                <strong>{$data_factura['nombre_comercial']}</strong><br/>

                                                &nbsp;&nbsp;{$data_factura['direccion']}<br/>

                                                &nbsp;&nbsp;{$data_factura['telefono']}<br/>

                                                &nbsp;&nbsp;{$data_factura['pais']}<br/>

                                            </td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 10, 55, $facturar_a, 0, 0, 1, true, '', true);

                $fecha = date("d/m/Y", strtotime($data_factura['fecha']));

                $fecha_v = date("d/m/Y", strtotime($data_factura['fecha_v']));

                $data_facturar = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-right: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr>

                                            <td class="header">Número</td><td>{$data_factura['numero']}</td>

                                        </tr>

                                        <tr>

                                            <td class="header">Fecha de la factura</td><td>{$fecha}</td>

                                        </tr>

                                        <tr>

                                            <td class="header">Fecha de la vencimiento</td><td>{$fecha_v}</td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 129, 55, $data_facturar, 0, 0, 1, true, '', true);



                $pdf->Ln(40);



                $html = <<<EOD

                               

                                  <table BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr style="background-color: '#CCCCCC;">

                                        <th width="25%">Nombre</th>

                                        <th width="45%">Descripción</th>

                                        <th width="10%">Cantidad</th>

                                        <th width="10%">Precio</th>

                                        <th width="10%">Subtotal</th>

                                    </tr>

EOD;



                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%" style="border-top: 1px solid #000000;">{$k['nombre']}</td>

                                        <td width="45%" style="border-top: 1px solid #000000;">{$k['descripcion_d']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['cantidad']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['precio']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');

                $pdf->Cell("130", "5", "Gracias por su compra", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], "T", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();

                break;
        };

        ob_clean();

        $pdf->Output('Factura ' . $data_factura['numero'] . '.pdf', 'D');
    }

    function _codigo() {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $last_numero_factura = $this->miempresa->last_numero_factura();

        $numero_factura = $this->miempresa->get_numero_factura();



        $prefijo_factura = $this->miempresa->get_prefijo_factura();

        $cod = $this->facturas->get_max_cod();

        $new_cod = "";



        if ($cod == '') {



            if ($numero_factura != $last_numero_factura) {

                $this->miempresa->update_last_numero_factura($numero_factura);
            }

            $dig = ((int) $numero_factura);

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;



            return $prefijo_factura . $new_cod;
        } else {

            if ($numero_factura != $last_numero_factura) {

                $this->miempresa->update_last_numero_factura($numero_factura);

                $cod = $numero_factura;
            } else {

                $cod = (int) $cod + 1;
            }

            $dig = ((int) $cod );

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;



            return $prefijo_factura . $new_cod;
        }
    }

    public function enviar_email($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->load->library('email');

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $empresa = $this->miempresa->get_data_empresa();

        $user_id = $this->session->userdata('user_id');

        $data_factura = $this->facturas->get_by_id($id);

        $detail = $this->facturas->get_detail($id);
        $this->email->clear();
        $this->email->from($empresa["data"]["email"], $empresa["data"]["nombre"]);
        $this->email->to($data_factura["email"]);
        $this->email->subject("Factura " . $data_factura["numero"]);
        $this->email->message("Para ver su factura por favor verifique su adjunto.");

        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        $total = 0;

        switch ($empresa["data"]["plantilla"]) {

            case "Moderno":

                $pdf->Image('public/img/Header.jpg', 0, 0, 0, 0, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

                $pdf->SetTextColor(104, 168, 212);

                $pdf->SetFont("", "B", 15);

                $pdf->SetY(40);

                $pdf->Cell(40, 10, $empresa["data"]['nombre']);

                $pdf->SetX(100);

                $right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li>{$empresa["data"]['direccion']}</li>

                                                <li>{$empresa["data"]['telefono']}</li>

                                                <li>{$empresa["data"]['email']}</li>

                                           </ul>";

                $pdf->SetFont("", "", 8);

                $pdf->writeHTMLCell(100, '', 100, 42, $right_column, 0, 0, 1, true, 'L', true);

                // 

                $last_right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li>{$empresa["data"]['resolucion']}</li>

                                                <li>{$empresa["data"]['web']}</li>

                                           </ul>";



                $pdf->writeHTMLCell(100, '', 160, 42, $last_right_column, 0, 0, 1, true, 'R', true);





                $facturar_a = "

                                   <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li><h1>{$data_factura['nombre_comercial']}</h1></li>

                                                <li>{$data_factura['direccion']}</li>

                                                <li>{$data_factura['telefono']}</li>    

                                                <li>{$data_factura['pais']}</li>

                                           </ul>";

                $pdf->writeHTMLCell(90, '', 5, 60, $facturar_a, 0, 0, false, false, 'L', true);



                $invoice_data = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                        }

                                    </style> 

                                           <ul>

                                                <li><b>Fecha de la factura</b> " . date("d/m/Y", strtotime($data_factura['fecha'])) . "</li>

                                                <li><b>Fecha de vencimiento</b> " . date("d/m/Y", strtotime($data_factura['fecha_v'])) . "</li>

                                                <li><b>No. de factura</b> " . $data_factura['numero'] . "</li>

                                           </ul>";



                $pdf->writeHTMLCell(90, '', 150, 70, $invoice_data, 0, 0, false, true, 'L', true);

                $html = "<div>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>";

                $pdf->writeHTMLCell(220, '', 15, 85, $html, 0, 0, 0, false, true, 'R', true);

                $pdf->Ln(13);

                $pdf->SetFont("", "B", 8);



                $html = <<<EOD

                               

                                  <table BORDER=0 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr>

                                        <th width="25%"><strong>Nombre</strong></th>

                                        <th width="45%"><strong>Descripción</strong></th>

                                        <th width="10%"><strong>Cantidad</strong></th>

                                        <th width="10%"><strong>Precio</strong></th>

                                        <th width="10%"><strong>Subtotal</strong></th>

                                    </tr>

EOD;



                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%">{$k['nombre']}</td>

                                        <td width="45%">{$k['descripcion_d']}</td>

                                        <td width="10%">{$k['cantidad']}</td>

                                        <td width="10%">{$k['precio']}</td>

                                        <td width="10%">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');



                $html = "<div>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>";

                $pdf->writeHTMLCell(220, '', 15, 210, $html, 0, 0, 0, false, true, 'R', true);

                $pdf->Ln(13);



                $pdf->Cell("130", "5", "Gracias por su compra", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], 0, 0, "R", false, "", 0, false, "L");

                $pdf->Ln();







                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();



                break;



            case "Clasico" :



                $pdf->SetFont("", "B", 10);

                $pdf->SetFillColor(255, 255, 255);



                $pdf->Write(10, $empresa["data"]['nombre']);

                $pdf->SetFont("", "", 8);

                $pdf->setCellHeightRatio(1.00);

                $pdf->writeHTMLCell(100, '', 10, 18, $empresa["data"]['cabecera_factura'], 0, 0, 1, true, 'L', true);

                $right_column = "

                                    <style type='text/css'>

                                        ul {

                                            list-style-type:none;

                                            line-height: 2px;

                                        }

                                    </style> 

                                           <ul>

                                                <li><h1>Factura de venta</h1></li>

                                                <li><b>Fecha de la factura</b> " . date("d/m/Y", strtotime($data_factura['fecha'])) . "</li>

                                                <li><b>Fecha de vencimiento</b> " . date("d/m/Y", strtotime($data_factura['fecha_v'])) . "</li>

                                                <li><b>No. de factura</b> " . $data_factura['numero'] . "</li>

                                           </ul>";



                $pdf->writeHTMLCell(100, '', 150, 3, $right_column, 0, 0, 1, true, 'R', true);

                $pdf->Ln();



                $facturar_a = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-bottom: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr class="header">

                                            <th>Facturar a</th>

                                        </tr>

                                        <tr>

                                            <td>

                                                <strong>{$data_factura['nombre_comercial']}</strong><br/>

                                                &nbsp;&nbsp;{$data_factura['direccion']}<br/>

                                                &nbsp;&nbsp;{$data_factura['telefono']}<br/>

                                                &nbsp;&nbsp;{$data_factura['pais']}<br/>

                                            </td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 10, 40, $facturar_a, 0, 0, 1, true, '', true);



                $pdf->Ln(40);



                $html = <<<EOD

                               

                                  <table BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr style="background-color: '#CCCCCC;">

                                        <th width="25%">Nombre</th>

                                        <th width="45%">Descripción</th>

                                        <th width="10%">Cantidad</th>

                                        <th width="10%">Precio</th>

                                        <th width="10%">Subtotal</th>

                                    </tr>

EOD;

                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%" style="border-top: 1px solid #000000;">{$k['nombre']}</td>

                                        <td width="45%" style="border-top: 1px solid #000000;">{$k['descripcion_d']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['cantidad']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['precio']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');

                $pdf->Cell("130", "5", "Gracias por su compra", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], "T", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();

                break;

            default:

                $pdf->SetFont("", "", 8);

                if (!empty($empresa["data"]['logotipo'])) {

                    $pdf->Image('uploads/' . $empresa["data"]['logotipo'], 10, 10, 95, 20, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);
                }

                $pdf->SetFillColor(255, 255, 255);

                $top_right_column = '<b>' . $empresa["data"]['nombre'] . '</b><br/>' . $empresa["data"]['cabecera_factura'];



                $pdf->writeHTMLCell(100, '', '110', 10, $top_right_column, 0, 0, 1, true, 'R', true);



                $pdf->Image('public/img/factura_de_venta.JPG', 10, 35, '', '', 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

                $pdf->Ln(10);



                $facturar_a = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-bottom: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr class="header">

                                            <th>Facturar a</th>

                                        </tr>

                                        <tr>

                                            <td>

                                                <strong>{$data_factura['nombre_comercial']}</strong><br/>

                                                &nbsp;&nbsp;{$data_factura['direccion']}<br/>

                                                &nbsp;&nbsp;{$data_factura['telefono']}<br/>

                                                &nbsp;&nbsp;{$data_factura['pais']}<br/>

                                            </td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 10, 55, $facturar_a, 0, 0, 1, true, '', true);

                $fecha = date("d/m/Y", strtotime($data_factura['fecha']));

                $fecha_v = date("d/m/Y", strtotime($data_factura['fecha_v']));

                $data_facturar = <<<EOF

                                    <style type='text/css'>

                                        .header {

                                            background-color: #CCCCCC;

                                            border-right: 1px solid #000;

                                        }

                                        table {

                                            border: 1px solid #000;

                                        }

                                    </style> 

                                    <table>

                                        <tr>

                                            <td class="header">Número</td><td>{$data_factura['numero']}</td>

                                        </tr>

                                        <tr>

                                            <td class="header">Fecha de la factura</td><td>{$fecha}</td>

                                        </tr>

                                        <tr>

                                            <td class="header">Fecha de la vencimiento</td><td>{$fecha_v}</td>

                                        </tr>

                                    </table>

EOF;

                $pdf->writeHTMLCell(80, 15, 129, 55, $data_facturar, 0, 0, 1, true, '', true);



                $pdf->Ln(40);



                $html = <<<EOD

                               

                                  <table BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=COLS>

                                    <tr style="background-color: '#CCCCCC;">

                                        <th width="25%">Nombre</th>

                                        <th width="45%">Descripción</th>

                                        <th width="10%">Cantidad</th>

                                        <th width="10%">Precio</th>

                                        <th width="10%">Subtotal</th>

                                    </tr>

EOD;



                foreach ($detail as $k) {

                    $precio_t = $k['precio'] * $k['cantidad'];

                    $impuesto = $k['impuesto'] * $precio_t / 100;

                    $total = $impuesto + $precio_t;

                    $html .= <<<EOD

                                    <tr>

                                        <td width="25%" style="border-top: 1px solid #000000;">{$k['nombre']}</td>

                                        <td width="45%" style="border-top: 1px solid #000000;">{$k['descripcion_d']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['cantidad']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">{$k['precio']}</td>

                                        <td width="10%" style="border-top: 1px solid #000000;">$total</td>

                                    </tr>

EOD;
                }



                $count_details = count($detail);

                if ($count_details < 3) {

                    $count_details = 8;
                } else if ($count_details >= 5 && $count_details < 8) {

                    $count_details = 4;
                }

                $breaksLines = "";

                for ($i = 0; $i < $count_details; $i++) {

                    $breaksLines .= "<br/>";
                }





                $html .= <<<EOD

                                     <tr border="0" height='100px'>

                                        <td  colspan="5">$breaksLines</td>

                                    </tr>

EOD;



                $html .= <<<EOD

                                  </table>  

EOD;

                $pdf->writeHTML($html, true, false, false, false, '');

                $pdf->Cell("130", "5", "Gracias por su compra", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total", "T", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_siva'], "T", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", "", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto_iva'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->Cell("130", "5", " ", 0, 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "B", 8);

                $pdf->Cell("30", "5", "Total con impuesto", "B", 0, "L", false, "", 0, false, "L");

                $pdf->SetFont("", "", 8);

                $pdf->Cell("35", "5", "$ " . $data_factura['monto'], "B", 0, "R", false, "", 0, false, "L");

                $pdf->Ln();



                $pdf->SetFont("", "", 8);

                $pdf->Ln();

                $pdf->writeHTML("<h3>Términos y condiciones</h3>");

                $pdf->writeHTML($empresa["data"]['terminos_condiciones']);

                $pdf->Ln();

                break;
        };



        ob_clean();

        $pdf_name = 'Factura_' . $data_factura['numero'] . '.pdf';

        $pdf->Output("uploads/$pdf_name", 'F');



        $this->email->attach("uploads/$pdf_name");

        $this->email->send();

        unlink("uploads/$pdf_name");

        $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha enviado la factura correctamente"));

        redirect("facturas/index_pendientes");
    }

    public function _get_html_factura($id, $data, $detail, $datos_empresa) {



        $user_id = $this->session->userdata('user_id');

        $total = 0;

        $html = "";

        switch ($datos_empresa["data"]["plantilla"]) {

            case "A4":

                $html = <<<EOF

                    <h1>Esta es una plantilla de prueba</h1>

EOF;

                break;

            default :

                $html = '

			<style type="text/css">

				table.content{ border-bottom:1px solid #909090; border-left:1px solid #909090}

				table.content td{ border-top:1px solid #909090; border-right:1px solid #909090; padding:10px}

				.hdetail td{ color:#000000; padding:8px 4px 8px 4px }

				.data{ padding-top:10px;}

				.data td{ font-size:13px; padding:2px 0}

				h1{ font-size:38px; color:#DB9600}

				.foot1 { text-align:center;color:#FFFFFF; background:#22ACC8; font-size:13px; padding:5px;width:840px;left:-19px;bottom:-10px;position:absolute}

				.foot2 { text-align:center;color:#FFFFFF; background:#22ACC8; font-size:13px; padding:5px;width:840px;left:-19px;bottom:-30px;position:absolute}

			</style>

			<table width="500" align="center" cellpadding="0" cellspacing="0">

				<tr>

					

					<th width="200" align="left">

						<img src="uploads/' . $datos_empresa["data"]['logotipo'] . '">

					</th>

					<th width="160"></th>

					<th width="284" valign="top" class="data">

						<table  cellspacing="1" align="center">

							<tr><td width="100">Número :</td>   <td>' . $data['numero'] . '</td></tr>

							<tr><td>Fecha :</td>   <td>' . date("d/m/Y", strtotime($data['fecha'])) . '</td></tr>

							<tr><td>Cliente :</td>   <td>' . $data['nombre_comercial'] . '</td></tr>

							<tr><td>NIF/CIF :</td>   <td>' . $data['nif_cif'] . '</td></tr>

							<tr><td>Dirección :</td>   <td>' . $data['direccion'] . '</td></tr>

							<tr><td>C.P :</td>   <td>' . $data['cp'] . '</td></tr>

							<tr><td>Población :</td>   <td>' . $data['poblacion'] . '</td></tr>

							<tr><td>Pais :</td>   <td>' . $data['pais'] . '</td></tr>

                                                        <tr><td>Provincia :</td>   <td>' . $data['provincia'] . '</td></tr>    

						</table>

					</th>

				</tr>

			</table>

			<h1 align="center">Factura</h1>

			';



                $html .='<table align="center" cellspacing="0" class="content" width="700" style="margin-top:40px">

						<tr class="hdetail">

							<td width="100">Cantidad</td> 

							<td width="300">Descripción</td>

							<td width="150">Precio</td>

							<td width="100">Precio con IVA</td>

						</tr>';

                foreach ($detail as $k) {

                    $precio_t = ($k['precio'] * $k['cantidad']);

                    $total = $total + $precio_t;

                    $iva = ($total * $k['impuesto']);



                    $html .='<tr>

				<td>' . $k['cantidad'] . '</td> 	<td>' . $k['descripcion_d'] . '</td> 	<td>' . number_format($k['precio'], 2) . '</td> 	<td align="right">' . number_format($precio_t, 2) . '</td>

			</tr>';
                }



                $height = 560 - (count($detail) * 35);



                $html .='<tr>

						<td colspan="4" height="' . $height . '"></td>

					</tr>';



                $html .='<tr>

            	<td colspan="2"></td><td><b>Total</b></td><td align="right">' . number_format($total, 2) . '</td>

            </tr>';

                $html .='<tr>

            	<td colspan="2"></td><td><b>Total con IVA</b></td><td align="right">' . number_format(($total + $iva), 2) . '</td>

            </tr>';

                $html .='

        </table>

		<table width="500">

			<tr>

				<td width="20"></td>

				<td style="padding-top:5px">

					N° de Cuenta para abonos: ' . $datos_empresa["data"]['resolucion'] . '

				</td>

			</tr>

                        <tr>

				<td width="20"></td>

				<td style="padding-top:5px">

					Pagar con Paypal: <a href="' . site_url('paypal/paypal_pay/' . $user_id . '/' . $data['id_factura']) . '"><img src="public/img/paypal3-thumb.png" style="height: 30px; width: 40px;"/></a>

				</td>

			</tr>

		</table>

		<div class="foot1">

			' . $datos_empresa["data"]['nombre'] . ' | ' . $datos_empresa["data"]['direccion'] . '



		</div>

		<div class="foot2">

			Tel: ' . $datos_empresa["data"]['telefono'] . ' | Email: ' . $datos_empresa["data"]['email'] . '

		</div>

		';

                break;
        }

        return $html;
    }

    public function excel() {

        $this->load->library('phpexcel');





        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador de la factura');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Numero');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Monto');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Fecha');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Estado');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Descripción');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Cantidad');

        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Impuesto');



        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'NIF/CIF');

        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Nombre Comercial');

        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Email');

        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Fecha de pago');

        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'Cantidad');

        $this->phpexcel->getActiveSheet()->setCellValue('O1', 'Tipo de pago');

        $this->phpexcel->getActiveSheet()->setCellValue('P1', 'Notas');







        $query = $this->facturas->excel();

        $row = 2;

        foreach ($query as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->id_factura);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->numero);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->monto);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->fecha);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->estado);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->descripcion);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value->precio);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value->cantidad);

            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value->impuesto);

            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value->nif_cif);

            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value->nombre_comercial);

            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value->email);

            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value->fecha_pago);

            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, $value->cantidad);

            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, $value->tipo);

            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, $value->notas);

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

        $this->phpexcel->getActiveSheet()->getStyle('A1:P' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
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
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0'
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF'
                        )
                    )
                )
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('gastos');



        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="facturas.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');



        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0



        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function import_excel() {

        if (!$this->ion_auth->logged_in()) {

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



        if (!empty($_FILES)) {

            $config = array();

            $config['upload_path'] = 'uploads/';

            $config['allowed_types'] = 'xls';



            $this->load->library('upload', $config);



            if (!empty($_FILES['archivo']['name'])) {

                if (!$this->upload->do_upload('archivo')) {

                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {

                    $upload_data = $this->upload->data();

                    $excel_name = $upload_data['file_name'];



                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

                    $reader->setReadDataOnly(TRUE);

                    $objXLS = $reader->load("uploads/" . $excel_name);

                    $campos[] = "No importar este campo";

                    for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                        if ($flag) {

                            $result = $alpha[$pointer] . $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $pointer++;
                            }
                        } else {

                            $result = $alpha[$cursor];

                            $cursor++;

                            if ($cursor >= (count($alpha) - 1)) {

                                $cursor = 0;

                                $flag = true;
                            }
                        }



                        if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                            $campos[] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                        } else {

                            break;
                        }
                    }



                    $data['campos'] = $campos;

                    $objXLS->disconnectWorksheets();

                    $this->session->set_flashdata("file_upload_productos", $excel_name);

                    unset($objXLS);

                    $this->layout->template('member')->show('facturas/import_excel_fields', array('data' => $data));
                }
            }
        } else if (isset($_POST["submit"])) {

            $nombre_comercial = $this->input->post("nombre_comercial");

            $nif_cif = $this->input->post("nif_cif");

            $email = $this->input->post("email");





            $numero = $this->input->post("numero");

            $estado = $this->input->post("estado");

            $fecha = $this->input->post("fecha");

            $monto = $this->input->post("monto");





            $excel_name = $this->session->flashdata("file_upload_productos");

            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);

            $reader->setReadDataOnly(TRUE);

            $objXLS = $reader->load("uploads/" . $excel_name);



            for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                if ($flag) {

                    $result = $alpha[$pointer] . $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $pointer++;
                    }
                } else {

                    $result = $alpha[$cursor];

                    $cursor++;

                    if ($cursor >= (count($alpha) - 1)) {

                        $cursor = 0;

                        $flag = true;
                    }
                }



                if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "") {

                    $campos[$result] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                } else {

                    break;
                }
            }



            foreach ($campos as $key => $value) {

                if ($value == $nombre_comercial) {

                    $nombre_comercial = $key;
                } else if ($value == $nif_cif) {

                    $nif_cif = $key;
                } else if ($value == $email) {

                    $email = $key;
                } else if ($value == $numero) {

                    $numero = $key;
                } else if ($value == $fecha) {

                    $fecha = $key;
                } else if ($value == $estado) {

                    $estado = $key;
                } else if ($value == $monto) {

                    $monto = $key;
                }
            }



            $count = 2;

            $adicionados = 0;

            $noadicionados = 0;



            if ($nombre_comercial != 'No importar este campo' && $nif_cif != 'No importar este campo' || $email != 'No importar este campo' || $numero != 'No importar este campo' || $fecha != 'No importar este campo' || $monto != 'No importar este campo' || $estado != 'No importar este campo') {

                while ($objXLS->getSheet(0)->getCell($nombre_comercial . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nif_cif . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($email . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($numero . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($fecha . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($monto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($estado . $count)->getValue() != '') {



                    $nombre_comercial_data = $objXLS->getSheet(0)->getCell($nombre_comercial . $count)->getValue();

                    $nif_cif_data = $objXLS->getSheet(0)->getCell($nif_cif . $count)->getValue();

                    $email_data = $objXLS->getSheet(0)->getCell($email . $count)->getValue();



                    $this->load->model("clientes_model", 'clientes');

                    $this->clientes->initialize($this->dbConnection);

                    $id_cliente = $this->clientes->excel_exist_get_id($nombre_comercial_data, $email_data, $nif_cif_data);

                    $array_datos = array(
                        'id_cliente' => $id_cliente,
                        'numero' => $objXLS->getSheet(0)->getCell($numero . $count)->getValue(),
                        'monto' => $objXLS->getSheet(0)->getCell($monto . $count)->getValue(),
                        'estado' => $objXLS->getSheet(0)->getCell($estado . $count)->getValue(),
                        'fecha' => $objXLS->getSheet(0)->getCell($fecha . $count)->getValue()
                    );



                    if (!$this->facturas->excel_exist($array_datos['numero'])) {

                        $this->facturas->excel_add($array_datos);

                        $adicionados++;
                    } else {

                        $noadicionados++;
                    }

                    $count++;
                }
            } else {

                $this->session->set_flashdata('message', custom_lang('sima_import_failure', 'La importación falló'));

                redirect('facturas/import_excel');
            }

            $objXLS->disconnectWorksheets();

            unset($objXLS);

            $data['count'] = $count - 2;

            $data['adicionados'] = $adicionados;

            $data['noadicionados'] = $noadicionados;

            unlink("uploads/$excel_name");



            $this->layout->template('member')->show('facturas/import_complete', array('data' => $data));
        } else {

            $data['data']['upload_error'] = $error_upload;

            $this->layout->template('member')->show('facturas/import_excel', array('data' => $data));
        }
    }

    public function addnew() {

        $this->layout->template('member')->show('facturas/addnew');
    }

}

?>
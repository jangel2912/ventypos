<?php

class Proformas extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("proformas_model", 'proformas');

        $this->proformas->initialize($this->dbConnection);


        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);


        $this->load->model("almacenes_model", 'almacen');

        $this->almacen->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        
        $this->load->model("Cuentas_dinero_model", 'cuentas_dinero');

        $this->cuentas_dinero->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->model('primeros_pasos_model');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        $this->load->model("Caja_model",'caja');
        $this->caja->initialize($this->dbConnection);

        $this->load->model("bancos_model", 'bancos');
        $this->bancos->initialize($this->dbConnection);

        $this->bancos->check_tables();
    }

    function index($offset = 0) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proformas/index',array("data" => $data));
    }

    function anulados($offset = 0) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proformas/anulados',array("data" => $data));
    }

    public function get_ajax_data($filtro=null) {
        if (!empty($filtro)) {
            $this->output->set_content_type('application/json')->set_output(json_encode($this->proformas->get_ajax_data("anulados")));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode($this->proformas->get_ajax_data()));
        }
    }

    function caja_abierta() {
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
            } else {
                $where=array('id_Usuario'=>$this->session->userdata('user_id'));
            }
        
            $orderby_cierre="fecha desc, hora_apertura desc";
            $limit_cierre="1";
            $cierre_caja=$this->caja->get_id_caja_en_cierre_caja($where,$orderby_cierre,$limit_cierre);            
                
            if ((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))) {                             
                $this->session->set_userdata('caja', $cierre_caja->id);
                $band=1;
            } else {
                $this->session->unset_userdata('caja');
                $band=0;
            }
        } else {
            $band=1;
        }

        return $band;
    }

    function nuevo() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('proformas') == true) {
            $band=$this->caja_abierta();
            
            if ($band==1) {
                $data = $this->proformas->add();
                //guardar evento de primeros pasos vendedor
                $estadoBD = $this->newAcountModel->getUsuarioEstado();                    
                if ($estadoBD["estado"]==2) {
                    $paso=13;
                    $marcada=$this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'),'db_config' => $this->session->userdata('db_config_id'),'id_paso'=>$paso));
                    if ($marcada==0) {
                            $datatarea=array(
                            'id_paso' => $paso,
                            'id_usuario' => $this->session->userdata('user_id'),
                            'db_config' => $this->session->userdata('db_config_id')
                    );
                    $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                    }                               
                }
                $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));
                redirect("proformas/index");
            } else {
                if ($band==0) {
                    $url=site_url("caja/apertura");
                    $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', "Debe tener caja abierta para realizar un gasto, haga clic <a href='$url'>aqui</a> para aperturar caja"));
                    redirect("proformas/nuevo");
                }             
            }  
        }

        $real_array = array('0' => "");
        //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user = '';
        $almacen = '';
        $nombre = '';
        $user = $this->db->query("SELECT id FROM users where id = '" . $user_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
        }
        $data['almacen_nombre'] = $nombre;
        $data['almacen_id'] = $almacen;
        //---------------------------------------------
        $data['bancos'] = $this->bancos->get_bancos();
        $data['categorias_gastos'] = $this->proformas->get_categorias();
        $data['almacen'] = $this->almacen->get_all('0',true);
        $data['cuentas_dinero'] = $this->cuentas_dinero->get_all('0');
        $data['impuestos'] = $real_array + $this->impuestos->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proformas/nuevo', array('data' => $data));
    }

    function editar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->input->post('id_proveedor')) {

            $band=$this->caja_abierta();            
            if ($band==1) {
                $response = $this->proformas->update();                
                $data_movimiento = array('valor'=>$this->input->post('valor'));
                $where = array('id_mov_tip'=>$this->input->post('id_proforma'),'tabla_mov'=>'proformas');
                $this->proformas->actualizar_movimiento_cierre($data_movimiento,$where);
                $messages = array(
                    'message' => custom_lang('sima_bill_updated_message', "Se ha salvado correctamente"),
                    'message_movimientos' => $response
                );
                $this->session->set_flashdata($messages);
                redirect("proformas/index");
            } else {
                if ($band==0) {
                    $url=site_url("caja/apertura");
                    $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', "Debe tener caja abierta para realizar una modificación a un gasto, haga clic <a href='$url'>aqui</a> para aperturar caja"));
                    redirect("proformas/index");
                }             
            }  
        }

        $real_array = array('0' => "");

        $data['data'] = $this->proformas->get_by_id($id);

        //$data['detail']  = $this->proformas->get_detail($id);
        //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user = '';
        $almacen = '';
        $nombre = '';
        $user = $this->db->query("SELECT id FROM users where id = '" . $user_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
        }
        $data['almacen_nombre'] = $nombre;
        $data['almacen_id'] = $almacen;
        //---------------------------------------------	
        $data['bancos'] = $this->bancos->get_bancos();
        $data['categorias_gastos'] = $this->proformas->get_categorias();
        $data['almacen'] = $this->almacen->get_all('0',true);
        $data['impuestos'] = $real_array + $this->impuestos->get_combo_data();
        $data['cuentas_dinero'] = $this->cuentas_dinero->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        //print_r($data);die();
        $this->layout->template('member')->show('proformas/editar', array('data' => $data));
    }

    function eliminar() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        
        $id = $this->input->post('id');
        //$band=$this->caja_abierta();

       // if ($band==1) {
            $data["message"] = $this->proformas->delete($id);
            echo json_encode($data);
            //$this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha eliminado correctamente"));
            //redirect("proformas/index");
        /*    
        } else {
            $url=site_url("caja/apertura");
            $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', "Debe tener caja abierta para realizar este proceso, haga clic <a href='$url'>aqui</a> para aperturar caja"));
            redirect("proformas/index");
        }*/
    }

    /*function caja_abierta_check($str) {
      $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
      $ocpresult = $this->dbConnection->query($ocp)->result();
      foreach ($ocpresult as $dat) {
            $valor_caja = $dat->valor_opcion;
      }

      if ($valor_caja == 'no') {
        return true;
      }

      if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
        return true;
      } else {
        $this->form_validation->set_message('caja_abierta_check', 'Para registrar gastos debes tener una caja abierta, por favor realice la apertura de caja primero');
        return FALSE;
      }
    }*/

    /* 	function imprimir($id=0)

      {

      $total = 0;

      $data    = $this->proformas->get_by_id($id);



      $detail  = $this->proformas->get_detail($id);



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

      <table width="900" align="center" cellpadding="0" cellspacing="0">

      <tr>



      <th width="200" align="left">

      <img src="public/img/logo_pdf.png">

      </th>

      <th width="160"></th>

      <th width="284" valign="top" class="data">

      <table  cellspacing="1">

      <tr><td width="100">Número :</td>   <td>'.$data['numero'].'</td></tr>

      <tr><td>Fecha :</td>   <td>'.date("d/m/Y",strtotime($data['fecha'])).'</td></tr>

      <tr><td>Cliente :</td>   <td>'.$data['nombre_comercial'].'</td></tr>

      <tr><td>NIF/CIF :</td>   <td>'.$data['nif_cif'].'</td></tr>

      <tr><td>Dirección :</td>   <td>'.$data['direccion'].'</td></tr>

      <tr><td>C.P :</td>   <td>'.$data['cp'].'</td></tr>

      <tr><td>Población :</td>   <td>'.$data['poblacion'].'</td></tr>

      <tr><td>Provincia :</td>   <td>'.$data['nombre_provincia'].'</td></tr>

      </table>

      </th>

      </tr>

      </table>

      <h1 align="center">Proforma</h1>

      ';

      $html .='<table align="center" cellspacing="0" class="content" width="700" style="margin-top:40px">

      <tr class="hdetail">

      <td width="100">Cantidad</td>

      <td width="300">Descripción</td>

      <td width="150">Precio</td>

      <td width="100">Precio Total</td>

      </tr>';



      foreach($detail as $k) {

      $precio_t = ($k['precio'] * $k['cantidad']);

      $total    = $total + $precio_t;

      $iva      = ($total * 0.18);



      $html .='<tr>

      <td>'.$k['cantidad'].'</td> 	<td><div style="width:300px">'.$k['descripcion'].'</div></td> 	<td>'.number_format($k['precio'],2).'</td> 	<td align="right">'.number_format($precio_t,2).'</td>

      </tr>';

      }

      $height = 560 - (count($detail) * 35);



      $html .='<tr>

      <td colspan="4" height="'.$height.'"></td>

      </tr>';





      $html .='<tr>

      <td colspan="2"></td><td><b>Total</b></td><td align="right">'.number_format($total,2).'</td>

      </tr>';

      $html .='

      </table>

      <div class="foot1">

      Sima climatización calefacción S.L. | C.I.F.: B-72171622 | Teniente Miranda 101 A, Algeciras (Cádiz)



      </div>

      <div class="foot2">

      Tel: 697 267 077 | Email: info@simaclimatización.com

      </div>

      ';





      require_once('public/html2pdf/html2pdf.class.php');

      $html2pdf = new HTML2PDF('P','A4','fr');

      $html2pdf->WriteHTML($html);

      $html2pdf->Output('Proformas '.$data['numero'].'.pdf', 'D');

      } */

    function codigo($cod = '') {

        if ($cod == '') {

            return 'R000001';
        } else {

            $dig = ((int) $cod + 1);

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;



            return 'R' . $new_cod;
        }
    }

    public function excel() {

        $this->load->library('phpexcel');





        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador del gasto');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Notas');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Porciento');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Nombre del proveedor');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Email');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Nif/CIF');







        $query = $this->proformas->excel();

        $row = 2;

        foreach ($query as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->id_proforma);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->fecha);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->almacen);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->descripcion);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->cantidad);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->valor);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value->notas);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value->nombre_impuesto);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value->porciento);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value->nombre_comercial);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value->email);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value->nif_cif);
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

        $this->phpexcel->getActiveSheet()->getStyle('A1:I' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
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

        header('Content-Disposition: attachment;filename="gastos.xls"');

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

                    $this->layout->template('member')->show('proformas/import_excel_fields', array('data' => $data));
                }
            }
        } else if (isset($_POST["submit"])) {

            $nombre_comercial = $this->input->post("nombre_comercial");

            $nif_cif = $this->input->post("nif_cif");

            $email = $this->input->post("email");

            $nombre_impuesto = $this->input->post("nombre_impuesto");

            $porciento = $this->input->post("porciento");



            $amount = $this->input->post("amount");

            $sima_notes = $this->input->post("sima_notes");

            $fecha = $this->input->post("fecha");





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
                } else if ($value == $nombre_impuesto) {

                    $nombre_impuesto = $key;
                } else if ($value == $porciento) {

                    $porciento = $key;
                } else if ($value == $amount) {

                    $amount = $key;
                } else if ($value == $sima_notes) {

                    $sima_notes = $key;
                } else if ($value == $fecha) {

                    $fecha = $key;
                }
            }



            $count = 2;

            $adicionados = 0;

            $noadicionados = 0;



            if ($nombre_comercial != 'No importar este campo' && $nif_cif != 'No importar este campo' || $email != 'No importar este campo' || $nombre_impuesto != 'No importar este campo' || $porciento != 'No importar este campo' || $amount != 'No importar este campo' || $fecha != 'No importar este campo') {

                while ($objXLS->getSheet(0)->getCell($nombre_comercial . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nif_cif . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($email . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($porciento . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($amount . $count)->getValue() != '' || $objXLS->getSheet(0)->getCell($fecha . $count)->getValue() != '') {

                    $porcientoData = $objXLS->getSheet(0)->getCell($porciento . $count)->getValue();

                    $nombreImpuestoData = $objXLS->getSheet(0)->getCell($nombre_impuesto . $count)->getValue();

                    $nombre_comercial_data = $objXLS->getSheet(0)->getCell($nombre_comercial . $count)->getValue();

                    $nif_cif_data = $objXLS->getSheet(0)->getCell($nif_cif . $count)->getValue();

                    $email_data = $objXLS->getSheet(0)->getCell($email . $count)->getValue();



                    $id_impuesto = $this->impuestos->excel_exist_get_id($nombreImpuestoData, $porcientoData);

                    $this->load->model("proveedores_model", 'proveedores');

                    $this->proveedores->initialize($this->dbConnection);

                    $id_proveedor = $this->proveedores->excel_exist_get_id($nombre_comercial_data, $email_data, $nif_cif_data);

                    $array_datos = array(
                        'id_proveedor' => $id_proveedor,
                        'id_impuesto' => $id_impuesto,
                        'cantidad' => $objXLS->getSheet(0)->getCell($amount . $count)->getValue(),
                        'fecha' => $objXLS->getSheet(0)->getCell($fecha . $count)->getValue()
                    );



                    if ($sima_notes != 'No importar este campo')
                        $array_datos['notas'] = $objXLS->getSheet(0)->getCell($sima_notes . $count)->getValue();



                    if (!$this->proformas->excel_exist($array_datos['id_proveedor'], $array_datos['id_impuesto'], $array_datos['fecha'], $array_datos['cantidad'])) {

                        $this->proformas->excel_add($array_datos);

                        $adicionados++;
                    } else {

                        $noadicionados++;
                    }

                    $count++;
                }
            } else {

                $this->session->set_flashdata('message', custom_lang('sima_import_failure', 'La importación falló'));

                redirect('proformas/import_excel');
            }

            $objXLS->disconnectWorksheets();

            unset($objXLS);

            $data['count'] = $count - 2;

            $data['adicionados'] = $adicionados;

            $data['noadicionados'] = $noadicionados;

            unlink("uploads/$excel_name");



            $this->layout->template('member')->show('proformas/import_complete', array('data' => $data));
        } else {

            $data['data']['upload_error'] = $error_upload;

            $this->layout->template('member')->show('proformas/import_excel', array('data' => $data));
        }
    }

    public function imprimir($id = 0) {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $empresa = $this->miempresa->get_data_empresa();

        $data_gastos = $this->proformas->get_gastos_datos($id);
        //    $data['data']    = $this->proformas->get_gastos_datos($id);

        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';
        require_once APPPATH . 'libraries/numerosALetras.class.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        $total = 0;
        
        $dire = $empresa["data"]['direccion'];
        $telef = $empresa["data"]['telefono'];
        $email = $empresa["data"]['email'];

        $cafac = $empresa["data"]['cabecera_factura'];
        $nit = $empresa["data"]['nit'];
        $resol = $empresa["data"]['resolucion'];
        $tele = $empresa["data"]['telefono'];
        $dire = $empresa["data"]['direccion'];
        $web = $empresa["data"]['web'];
        $empre = $empresa["data"]["nombre"];
        $moneda = strtoupper($empresa["data"]['moneda'] !== '' ? $empresa["data"]['moneda'] : 'PESOS M/CTE');
        $simbolo = $empresa["data"]['simbolo'];

        $img = base_url("uploads/{$empresa['data']['logotipo']}");

        $descripcion = $data_gastos['descripcion'];
        $valor = $this->opciones_model->formatoMonedaMostrar($data_gastos['valor']);
        $notas = $data_gastos['notas'];
        $fecha = $data_gastos['fecha'];
        $id_impuesto = $data_gastos['id_impuesto'];
        $nombre_comercial = $data_gastos['nombre_comercial'];
        $nif_cif = $data_gastos['nif_cif'];
        $forma_pago = $data_gastos['forma_pago'];

        $nombre_almacen = $data_gastos['nombre'];
        
        $valor1 = str_replace(",", "", $valor);
        $valor2 = str_replace(".", "", $valor1);
       
        $V = new EnLetras();
        //$valor_final = strtoupper($V->ValorEnLetras($valor2));
        $valor_final = strtoupper($V->ValorEnLetras($data_gastos['valor']));
        $valor_final = str_replace("0", "", $valor_final);       
        if ($nit == '14.465.114-8') {
            $tam = 'width="88" height="88"';
        } else {
            $tam = 'width="130" height="53"';
        }

        $html = <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="27%"  align="center" style=" font-size: 11px"><br><br>
            $empre <br>
              NIT  $nit <br>
                $resol <br>
                $tele <br>
                $dire <br>
                $web 
                            </td>

                            <td width="19%"  align="left"><br><br>
							<img src="$img" alt="test alt attribute" $tam border="0" />
       
                            </td>

                            <td style="border-left: 1px solid #000000; font-size:11px" width="39%" align="left"><B><br><br>
                  COMPROBANTE DE EGRESO<br>
				&nbsp; NO. $id </B>

                           </td>
						</tr>
				</table>		   					   
EOF;

        $html .= <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="38%"  align="left" style=" font-size: 11px">
            $nombre_almacen
                            </td>						
                            <td width="17%"  align="left" style=" font-size: 11px">
            Fecha: $fecha 
                            </td>

                            <td width="30%" style="border-left: 1px solid #000000; " align="left">
				  Valor: $simbolo $valor
       
                            </td>
						</tr>
				</table>		   					   
EOF;

        $html .= <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="54%"  align="left" style=" font-size: 11px">
            Pagado a: $nombre_comercial
                            </td>

                            <td width="31%" align="left">
				  NIT/CC: $nif_cif
       
                            </td>
						</tr>
				</table>		   					   
EOF;


        $html .= <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="85%" height="52px"  align="left" style=" font-size: 11px">
           Por concepto de: $descripcion
                            </td>
						</tr>
				</table>		   					   
EOF;


        $html .= <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="85%" height="30px"  align="left" style=" font-size: 11px">
           La suma de: $valor_final $moneda
                            </td>
						</tr>
				</table>		   					   
EOF;

        if ($forma_pago != '') {
            $html .= <<<EOF
                     <table width="552px"  height="30px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td  align="left" style=" font-size: 11px"><br><br>
           $forma_pago
                            </td>					
						</tr>
				</table>		   					   
EOF;
        } else {
            $html .= <<<EOF
                     <table width="552px"  height="30px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td  align="left" style=" font-size: 11px"><br><br>
           Efectivo
                            </td>					
						</tr>
				</table>		   					   
EOF;
        }

        $html .= <<<EOF
                     <table width="650px"  height="30px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="40%" height="30px" align="left" style=" font-size: 11px"><br><br>
							 &nbsp;Firma y sello<br>
           <br>
		   ________________________________________<br>
		  NIT/C.C $nif_cif
                            </td>	
                            <td width="15%" style="border-left: 1px solid #000000; font-size: 8px " height="30px" align="left" >
							ELABORADO
                            </td>	
                            <td width="15%" style="border-left: 1px solid #000000; font-size: 8px" height="30px" align="left" >
							APROBADO
							</td>
                            <td width="15%" style="border-left: 1px solid #000000; font-size: 8px" height="30px" align="left">
							CONTABILIZADO
                            </td>																			
						</tr>
				</table>		   					   
EOF;



        $pdf->writeHTML($html, true, false, true, false, '');

        ob_clean(); // cleaning the buffer before Output()



        $pdf->Output('Comprobante de Egreso No ' . $id . '.pdf', 'I');
    }

    function cargar_subcategorias() {
        $id_categoria = $this->input->post('id_categoria');
        $subcategorias = $this->proformas->cargar_subcategorias($id_categoria);
        echo json_encode($subcategorias);
    }

}

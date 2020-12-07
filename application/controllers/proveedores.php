<?php
class Proveedores extends CI_Controller
{
    var $dbConnection;
    function __construct()
    {
        parent::__construct();
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model("proveedores_model", 'proveedores');
        $this->proveedores->initialize($this->dbConnection);
        $this->load->model("pais_provincia_model", 'pais_provincia');
        $this->load->library('pagination');
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);        
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function index($offset = 0)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proveedores/index',array("data" => $data));
    }

    public function get_ajax_data()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->proveedores->get_ajax_data()));
    }

    public function add_ajax_provider()
    {
        $nombre_comercial = $this->input->post('nombre_comercial');
        $email = $this->input->post('email');
        $razon_social = $this->input->post('razon_social');
        $nif_cif = $this->input->post('nif_cif');
        $id_proveedor = $this->proveedores->add_light($nombre_comercial, $razon_social, $nif_cif, $email);
        /*$index = array();
        $index['id'] = $id_proveedor;
        $index['type'] = "proveedores";
        $index['contents'] = "Nombre del proveedor $nombre_comercial, Correo $email, Razon social $razon_social";
        $this->load->library('zend');
        $this->zend->index_data($index);*/
        $result = array(
            'id_proveedor' => $id_proveedor
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function nif_check($str)
    {
        if ($this->proveedores->nif_check($str))
        {
            $this->form_validation->set_message('nif_check', 'EL %s existe');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function nuevo()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('proveedores') == true)
        {
            $data = $this->proveedores->add();
            /* $index = array();
            $index['id'] = $data['id_proveedor'];
            $index['type'] = "proveedores";
            $index['contents'] = "Nombre del proveedor {$data['nombre_comercial']}, Correo {$data['email']}, Razon social {$data['razon_social']}, Pais {$data['pais']}";
            $this->load->library("zend");
            $this->zend->index_data($index);*/
            $this->session->set_flashdata('message', custom_lang('sima_provider_created_message', 'Proveedor creado correctamente'));
            redirect('proveedores/index');
        }

        $data = array();
        $data['pais'] = $this->pais_provincia->get_pais();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proveedores/nuevo', array(
            'data' => $data
        ));
    }

    public function editar($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('proveedores') == true)
        {
            $this->proveedores->update();
            $this->session->set_flashdata('message', custom_lang('sima_provider_updated_message', 'Proveedor actualizado correctamente'));
            redirect("proveedores/index");
        }

        $data = array();
        $data['data'] = $this->proveedores->get_by_id($id);
        $data['pais'] = $this->pais_provincia->get_pais();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        $this->layout->template('member')->show('proveedores/editar', array(
            'data' => $data
        ));
    }

    public function detalles($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $data = $this->proveedores->get_by_id($id);
        $this->layout->template('member')->show('proveedores/detalles', array(
            'data' => $data
        ));
    }

    public function eliminar($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $this->proveedores->delete($id);
        $this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));
        redirect("proveedores/index");
    }

    public function get_ajax_proveedores()
    {
        $result = $this->proveedores->get_term($this->input->get('term', TRUE));
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function excel()
    {
        $reporte = $this->load->library('phpexcel');
        $reporte = new PHPExcel();
        $reporte->setActiveSheetIndex(0);
        $reporte->getActiveSheet()->setCellValue('A1', 'Identificador del proveedor');
        $reporte->getActiveSheet()->setCellValue('B1', 'Nombre comercial');
        $reporte->getActiveSheet()->setCellValue('C1', 'Pais');
        $reporte->getActiveSheet()->setCellValue('D1', 'Provincia');
        $reporte->getActiveSheet()->setCellValue('E1', 'Teléfono');
        $reporte->getActiveSheet()->setCellValue('F1', 'Razón Social');
        $reporte->getActiveSheet()->setCellValue('G1', 'NIF/CIF');
        $reporte->getActiveSheet()->setCellValue('H1', 'Contacto');
        $reporte->getActiveSheet()->setCellValue('I1', 'Página Web');
        $reporte->getActiveSheet()->setCellValue('J1', 'Email');
        $reporte->getActiveSheet()->setCellValue('K1', 'Población');
        $reporte->getActiveSheet()->setCellValue('L1', 'Direccion');
        $reporte->getActiveSheet()->setCellValue('M1', 'Código Postal');
        $reporte->getActiveSheet()->setCellValue('N1', 'Movil');
        $reporte->getActiveSheet()->setCellValue('O1', 'Fax');
        $reporte->getActiveSheet()->setCellValue('P1', 'Tipo de Empresa');
        $reporte->getActiveSheet()->setCellValue('Q1', 'Entidad Bancaria');
        $reporte->getActiveSheet()->setCellValue('R1', 'Numero de Cuenta');
        $reporte->getActiveSheet()->setCellValue('S1', 'Observaciones');
        $query = $this->proveedores->excel();
        $row = 2;
        foreach($query as $value)
        {
            $reporte->getActiveSheet()->setCellValue('A' . $row, $value->id_proveedor);
            $reporte->getActiveSheet()->setCellValue('B' . $row, $value->nombre_comercial);
            $reporte->getActiveSheet()->setCellValue('C' . $row, $value->pais);
            $reporte->getActiveSheet()->setCellValue('D' . $row, $value->provincia);
            $reporte->getActiveSheet()->setCellValue('E' . $row, $value->telefono);
            $reporte->getActiveSheet()->setCellValue('F' . $row, $value->razon_social);
            $reporte->getActiveSheet()->setCellValue('G' . $row, $value->nif_cif);
            $reporte->getActiveSheet()->setCellValue('H' . $row, $value->contacto);
            $reporte->getActiveSheet()->setCellValue('I' . $row, $value->pagina_web);
            $reporte->getActiveSheet()->setCellValue('J' . $row, $value->email);
            $reporte->getActiveSheet()->setCellValue('K' . $row, $value->poblacion);
            $reporte->getActiveSheet()->setCellValue('L' . $row, $value->direccion);
            $reporte->getActiveSheet()->setCellValue('M' . $row, $value->cp);
            $reporte->getActiveSheet()->setCellValue('N' . $row, $value->movil);
            $reporte->getActiveSheet()->setCellValue('O' . $row, $value->fax);
            $reporte->getActiveSheet()->setCellValue('P' . $row, $value->tipo_empresa);
            $reporte->getActiveSheet()->setCellValue('Q' . $row, $value->entidad_bancaria);
            $reporte->getActiveSheet()->setCellValue('R' . $row, $value->numero_cuenta);
            $reporte->getActiveSheet()->setCellValue('S' . $row, $value->observaciones);
            $row++;
        }

        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'argb' => 'FF000000'
                    ) ,
                ) ,
            ) ,
        );
        $reporte->getActiveSheet()->getStyle('A1:S' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $reporte->getActiveSheet()->getStyle('A1:S1')->applyFromArray(array(
            'font' => array(
                'bold' => true
            ) ,
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ) ,
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ) ,
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ) ,
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0'
                ) ,
                'endcolor' => array(
                    'argb' => 'FFFFFFFF'
                )
            )
        ));

        // Rename worksheet

        $reporte->getActiveSheet()->setTitle('Proveedores');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        // $this->phpexcel->setActiveSheetIndex(0);

        /*header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="proveedores.xlsx"');
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');*/
        
        /** Corrección de formato Brayan Camargo */
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="proveedores.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($reporte, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output');
        exit;
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
                    for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++)
                    {
                        if ($flag)
                        {
                            $result = $alpha[$pointer] . $alpha[$cursor];
                            $cursor++;
                            if ($cursor >= (count($alpha) - 1))
                            {
                                $cursor = 0;
                                $pointer++;
                            }
                        }
                        else
                        {
                            $result = $alpha[$cursor];
                            $cursor++;
                            if ($cursor >= (count($alpha) - 1))
                            {
                                $cursor = 0;
                                $flag = true;
                            }
                        }

                        if ($objXLS->getSheet(0)->getCell($result . '1')->getValue() != "")
                        {
                            $campos[] = $objXLS->getSheet(0)->getCell($result . '1')->getValue();
                        }
                        else
                        {
                            break;
                        }
                    }

                    $data['campos'] = $campos;
                    $objXLS->disconnectWorksheets();
                    $this->session->set_userdata("file_upload_productos", $excel_name);
                    unset($objXLS);

                    $excel_name = $this->session->userdata("file_upload_productos");
                    $this->session->unset_userdata('file_upload_productos');

                    $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);
                
                    $reader->setReadDataOnly(TRUE);
                    $objXLS = $reader->load("uploads/" . $excel_name);
                    
                    /* Transformamos el excel en csv y lo guardamos*/
                    $writer = PHPExcel_IOFactory::createWriter($objXLS, 'CSV');
                    $writer->setUseBOM(true);
                    $writer->save('uploads/data.csv');

                
                    $reader2 = new PHPExcel_Reader_CSV();
                    $reader2->setInputEncoding('utf-8');
                    $reader2->setDelimiter(';');
                    $reader2->setEnclosure('');
                    $reader2->setLineEnding("\r\n");
                    $reader2->setSheetIndex(0);
                    $objCsv = $reader2->load("uploads/data.csv");

                    $array_datos = array();
                    $adicionados = 0;
                    $noadicionados = 0;
                    $worksheet = $objCsv->getActiveSheet();
                    $rows = 1;
                    foreach ($worksheet->getRowIterator() AS $row) {
                        if($rows > 1){
                            $file = str_replace('"','',$objCsv->getSheet(0)->getCell('A'.$rows)->getValue());
                            $file_explode = explode(',',$file);
        
        
                            $array_datos = array(
                                'nombre_comercial' => $file_explode[1],
                                'nif_cif' => $file_explode[6],
                                'email' => $file_explode[9]
                            );
        
                            $array_datos['pais'] = $file_explode[2];
                            $array_datos['provincia'] = $file_explode[3];
                            $array_datos['telefono'] = $file_explode[4];
                            $array_datos['razon_social'] = $file_explode[5];
                            $array_datos['contacto'] = $file_explode[7];
                            $array_datos['pagina_web'] = $file_explode[8];
                            $array_datos['poblacion'] = $file_explode[10];
                            $array_datos['direccion'] = $file_explode[11];
                            $array_datos['cp'] = $file_explode[12];
                            $array_datos['movil'] = $file_explode[13];
                            $array_datos['fax'] = $file_explode[14];
                            $array_datos['tipo_empresa'] = $file_explode[15];
                            $array_datos['entidad_bancaria'] = $file_explode[16];
                            $array_datos['numero_cuenta'] = $file_explode[17];
                            $array_datos['observaciones'] = $file_explode[18];
        
                           if ( $array_datos['nombre_comercial'] != '' && $array_datos['nif_cif'] != ''){
                                $this->proveedores->excel_add($array_datos);
                                $adicionados++;
                            }else{
                                 $noadicionados++;
                            }
                        }
                        $rows++;
                    }
                    
                    $objCsv->disconnectWorksheets();
                    unset($objCsv);
                    $data['count'] = $rows - 2;
                    $data['adicionados'] = $adicionados;
                    $data['noadicionados'] = $noadicionados;
                    unlink("uploads/$excel_name");
                    unlink("uploads/data.csv");
                    $this->layout->template('member')->show('proveedores/import_complete', array(
                        'data' => $data
                    ));
                    /*$this->layout->template('member')->show('proveedores/import_excel_fields', array(
                        'data' => $data
                    ));*/
                }
            }
        }
        else if (isset($_POST["submit"]))
        {
            $nombre_comercial = $this->input->post("nombre_comercial");
            $pais = $this->input->post("pais");
            $provincia = $this->input->post("provincia");
            $telefono = $this->input->post("telefono");
            $razon_social = $this->input->post("razon_social");
            $nif_cif = $this->input->post("nif_cif");
            $contacto = $this->input->post("contacto");
            $pagina_web = $this->input->post("pagina_web");
            $email = $this->input->post("email");
            $poblacion = $this->input->post("poblacion");
            $direccion = $this->input->post("direccion");
            $codigo_postal = $this->input->post("codigo_postal");
            $movil = $this->input->post("movil");
            $fax = $this->input->post("fax");
            $tipo_empresa = $this->input->post("tipo_empresa");
            $entidad_bancaria = $this->input->post("entidad_bancaria");
            $numero_cuenta = $this->input->post("numero_cuenta");
            $observaciones = $this->input->post("observaciones");
            $excel_name = $this->session->userdata("file_upload_productos");
            $this->session->unset_userdata('file_upload_productos');

            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/" . $excel_name);
           
            $reader->setReadDataOnly(TRUE);
            $objXLS = $reader->load("uploads/" . $excel_name);
            
            /* Transformamos el excel en csv y lo guardamos*/
            $writer = PHPExcel_IOFactory::createWriter($objXLS, 'CSV');
            $writer->setUseBOM(true);
            $writer->save('uploads/data.csv');

        
            $reader2 = new PHPExcel_Reader_CSV();
            $reader2->setInputEncoding('utf-8');
            $reader2->setDelimiter(';');
            $reader2->setEnclosure('');
            $reader2->setLineEnding("\r\n");
            $reader2->setSheetIndex(0);
            $objCsv = $reader2->load("uploads/data.csv");

            
            /*
            for ($i = 0; $i <= (count($alpha) * count($alpha)); $i++)
            {
                

                if ($flag)
                {
                    $result = $alpha[$pointer] . $alpha[$cursor];
                    $cursor++;
                    if ($cursor >= (count($alpha) - 1))
                    {
                        $cursor = 0;
                        $pointer++;
                    }
                }
                else
                {
                    $result = $alpha[$cursor];
                    $cursor++;
                    if ($cursor >= (count($alpha) - 1))
                    {
                        $cursor = 0;
                        $flag = true;
                    }
                }

                if ($objCsv->getSheet(0)->getCell($result . '1')->getValue() != "")
                {
                    $campos[$result] = $objCsv->getSheet(0)->getCell($result . '1')->getValue();
                }
                else
                {
                    break;
                }
            }

     
            
            foreach($campos as $key => $value)
            {
                if ($value == $nombre_comercial)
                {
                    $nombre_comercial = $key;
                }
                else if ($value == $pais)
                {
                    $pais = $key;
                }
                else if ($value == $provincia)
                {
                    $provincia = $key;
                }
                else if ($value == $telefono)
                {
                    $telefono = $key;
                }
                else if ($value == $razon_social)
                {
                    $razon_social = $key;
                }
                else if ($value == $nif_cif)
                {
                    $nif_cif = $key;
                }
                else if ($value == $contacto)
                {
                    $contacto = $key;
                }
                else if ($value == $pagina_web)
                {
                    $pagina_web = $key;
                }
                else if ($value == $email)
                {
                    $email = $key;
                }
                else if ($value == $poblacion)
                {
                    $poblacion = $key;
                }
                else if ($value == $direccion)
                {
                    $direccion = $key;
                }
                else if ($value == $codigo_postal)
                {
                    $codigo_postal = $key;
                }
                else if ($value == $movil)
                {
                    $movil = $key;
                }
                else if ($value == $fax)
                {
                    $fax = $key;
                }
                else if ($value == $tipo_empresa)
                {
                    $tipo_empresa = $key;
                }
                else if ($value == $entidad_bancaria)
                {
                    $entidad_bancaria = $key;
                }
                else if ($value == $numero_cuenta)
                {
                    $numero_cuenta = $key;
                }
                else if ($value == $observaciones)
                {
                    $observaciones = $key;
                }
            }*/

            
           /* $in_nombre_comercial = NULL;
            $in_pais = NULL;
            $in_provincia = NULL;

            $array_campos =$objCsv->getSheet(0)->getCell('A1')->getValue(); 
            $campos_seleccionados = explode(',',$array_campos);
            $add_campos = array();
            foreach($campos_seleccionados as $value){
                $val = (string) str_replace('"','',$value);
                switch($val){
                    case "Nombre comercial" :
                        $in_nombre_comercial = 1;
                    break;
                    case "Pais" :
                        $in_pais = 2;
                    break;
                    case "Provincia" :
                        $in_provincia = 3;
                    break;

                    default: 
                        echo "no entre - /n";
                    break;
                   
                }
            }*/
        

            $array_datos = array();
            $adicionados = 0;
            $noadicionados = 0;
            $worksheet = $objCsv->getActiveSheet();
            $rows = 1;
            foreach ($worksheet->getRowIterator() AS $row) {
                if($rows == 3){
                    $file = str_replace('"','',$objCsv->getSheet(0)->getCell('A'.$rows)->getValue());
                    $file_explode = explode(',',$file);


                    $array_datos = array(
                        'nombre_comercial' => $file_explode[1],
                        'nif_cif' => $file_explode[6],
                        'email' => $file_explode[9]
                    );

                    $array_datos['pais'] = $file_explode[2];
                    $array_datos['provincia'] = $file_explode[3];
                    $array_datos['telefono'] = $file_explode[4];
                    $array_datos['razon_social'] = $file_explode[5];
                    $array_datos['contacto'] = $file_explode[7];
                    $array_datos['pagina_web'] = $file_explode[8];
                    $array_datos['poblacion'] = $file_explode[10];
                    $array_datos['direccion'] = $file_explode[11];
                    $array_datos['cp'] = $file_explode[12];
                    $array_datos['movil'] = $file_explode[13];
                    $array_datos['fax'] = $file_explode[14];
                    $array_datos['tipo_empresa'] = $file_explode[15];
                    $array_datos['entidad_bancaria'] = $file_explode[16];
                    $array_datos['numero_cuenta'] = $file_explode[17];
                    $array_datos['observaciones'] = $file_explode[18];

                   if ( $array_datos['nombre_comercial'] != '' && $array_datos['nif_cif'] != ''){
                        $this->proveedores->excel_add($array_datos);
                        $adicionados++;
                    }else{
                         $noadicionados++;
                    }
                }
                $rows++;
            }
            
            $objCsv->disconnectWorksheets();
            unset($objCsv);
            $data['count'] = $rows - 2;
            $data['adicionados'] = $adicionados;
            $data['noadicionados'] = $noadicionados;
            unlink("uploads/$excel_name");
            unlink("uploads/data.csv");
            $this->layout->template('member')->show('proveedores/import_complete', array(
                'data' => $data
            ));

            /*

            $count = 2;
            $adicionados = 0;
            $noadicionados = 0;
            if ($nombre_comercial != 'No importar este campo' && $nif_cif != 'No importar este campo' || $email != 'No importar este campo')
            {

                while ($objCsv->getSheet(0)->getCell($nombre_comercial . $count)->getValue() != '' || $objCsv->getSheet(0)->getCell($nif_cif . $count)->getValue() != '' || $objCsv->getSheet(0)->getCell($email . $count)->getValue() != '')
                {
                    $array_datos = array(
                        'nombre_comercial' => $objCsv->getSheet(0)->getCell($nombre_comercial . $count)->getValue() ,
                        'nif_cif' => $objCsv->getSheet(0)->getCell($nif_cif . $count)->getValue() ,
                        'email' => $objCsv->getSheet(0)->getCell($email . $count)->getValue()
                    );
                    if ($pais != 'No importar este campo') $array_datos['pais'] = $objCsv->getSheet(0)->getCell($pais . $count)->getValue();
                    if ($provincia != 'provincia') $array_datos['provincia'] = $objCsv->getSheet(0)->getCell($provincia . $count)->getValue();
                    if ($telefono != 'No importar este campo') $array_datos['telefono'] = $objCsv->getSheet(0)->getCell($telefono . $count)->getValue();
                    if ($razon_social != 'No importar este campo') $array_datos['razon_social'] = $objCsv->getSheet(0)->getCell($razon_social . $count)->getValue();
                    if ($contacto != 'No importar este campo') $array_datos['contacto'] = $objCsv->getSheet(0)->getCell($contacto . $count)->getValue();
                    if ($pagina_web != 'No importar este campo') $array_datos['pagina_web'] = $objCsv->getSheet(0)->getCell($pagina_web . $count)->getValue();
                    if ($poblacion != 'No importar este campo') $array_datos['poblacion'] = $objCsv->getSheet(0)->getCell($poblacion . $count)->getValue();
                    if ($direccion != 'No importar este campo') $array_datos['direccion'] = $objCsv->getSheet(0)->getCell($direccion . $count)->getValue();
                    if ($codigo_postal != 'No importar este campo') $array_datos['cp'] = $objXLS->getSheet(0)->getCell($codigo_postal . $count)->getValue();
                    if ($movil != 'No importar este campo') $array_datos['movil'] = $objCsv->getSheet(0)->getCell($movil . $count)->getValue();
                    if ($fax != 'No importar este campo') $array_datos['fax'] = $objCsv->getSheet(0)->getCell($fax . $count)->getValue();
                    if ($tipo_empresa != 'No importar este campo') $array_datos['tipo_empresa'] = $objCsv->getSheet(0)->getCell($tipo_empresa . $count)->getValue();
                    if ($entidad_bancaria != 'No importar este campo') $array_datos['entidad_bancaria'] = $objCsv->getSheet(0)->getCell($entidad_bancaria . $count)->getValue();
                    if ($numero_cuenta != 'No importar este campo') $array_datos['numero_cuenta'] = $objCsv->getSheet(0)->getCell($numero_cuenta . $count)->getValue();
                    if ($observaciones != 'No importar este campo') $array_datos['observaciones'] = $objCsv->getSheet(0)->getCell($observaciones . $count)->getValue();
                    if (!$this->proveedores->excel_exist($array_datos['nombre_comercial'], $array_datos['email'], $array_datos['nif_cif']))
                    {
                        $this->proveedores->excel_add($array_datos);
                        $adicionados++;
                    }
                    else
                    {
                        $noadicionados++;
                    }

                    $count++;
                }
            }
            else
            {
                $this->session->set_flashdata('message', custom_lang('sima_import_failure', 'La importación falló'));
                redirect('proveedores/import_excel');
            }

            $objXLS->disconnectWorksheets();
            unset($objXLS);
            $data['count'] = $count - 2;
            $data['adicionados'] = $adicionados;
            $data['noadicionados'] = $noadicionados;
            unlink("uploads/$excel_name");
            $this->layout->template('member')->show('proveedores/import_complete', array(
                'data' => $data
            ));
            */
        }
        else
        {
            $data['data']['upload_error'] = $error_upload;
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('proveedores/import_excel', array(
                'data' => $data
            ));
        }
    }
}
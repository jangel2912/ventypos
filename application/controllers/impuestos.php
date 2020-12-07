<?php

class Impuestos extends CI_Controller 

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

            

            $this->load->model("impuestos_model",'impuestos');

            $this->impuestos->initialize($this->dbConnection);

            
            $this->load->model("miempresa_model", 'mi_empresa');
            $this->mi_empresa->initialize($this->dbConnection);

            $this->load->library('pagination');

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

        $this->layout->template('member')->show('impuestos/index'/*,array('data' => $data)*/);

		

	}

        

        public function get_ajax_data(){

            $this->output->set_content_type('application/json')->set_output(json_encode($this->impuestos->get_ajax_data()));

        }


        public function get_impuesto(){
        
            $result = 0;
            $result = $this->impuestos->get_impuesto($this->input->post('imp'));
            $this->output->set_output($result);
        }
 	

	public function nuevo()

	{	

		if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

		 if ($this->form_validation->run('impuestos') == true)

                    {

                        $this->impuestos->add();

                        $this->session->set_flashdata('message', custom_lang('sima_tax_created_message', 'Impuesto creado correctamente'));

                        redirect('impuestos/index');

                    }

                    

                $data = array();

                $this->layout->template('member')->show('impuestos/nuevo', array('data' => $data));

	}

	

	public function editar($id){

		

		if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

		if ($this->form_validation->run('impuestos') == true)

                    {

                        $this->impuestos->update();	

                        $this->session->set_flashdata('message', custom_lang('sima_tax_updated_message', 'Impuesto actualizado correctamente'));

                        redirect("frontend/configuracion");

                    }

                    

                $data = array();   

        $data['data']  = $this->impuestos->get_by_id($id);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
        
        if($this->session->userdata('is_admin') == "t"){
             $this->layout->template('member')->show('impuestos/editar', array('data' => $data));
        }else{
           redirect(site_url('frontend/index'));
        }

		

	}

	

	public function eliminar($id)

	{	

		if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

		$this->impuestos->delete($id);

		$this->session->set_flashdata('message', custom_lang('sima_product_deleted_message', 'Se ha eliminado correctamente'));

		redirect("impuestos");

	}

        

        public function excel(){

            if (!$this->ion_auth->logged_in())

		{

			redirect('auth', 'refresh');

		}

            $this->load->library('phpexcel');

     

     

            $this->phpexcel->setActiveSheetIndex(0);

            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Identificador del impuesto');

            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del impuesto');

            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Porciento');
            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Predeterminado');

            

            $query = $this->impuestos->excel();

            $row = 2;

            foreach ($query as $value) {

                $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $value->id_impuesto);

                $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $value->nombre_impuesto);

                $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value->porciento);
                $predeterminado = $value->predeterminado == true ? 'SI' : 'NO';
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $predeterminado);

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

            $this->phpexcel->getActiveSheet()->getStyle('A1:D'.--$row)->applyFromArray($styleThinBlackBorderOutline);

            $this->phpexcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(

		array(

			'font'    => array(

				'bold'      => true

			),

			'alignment' => array(

				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,

			),

			'borders' => array(

				'top'     => array(

 					'style' => PHPExcel_Style_Border::BORDER_THIN

 				),

                                'bottom'     => array(

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

            // Rename worksheet

            $this->phpexcel->getActiveSheet()->setTitle('Impuestos');

            

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet

            //$this->phpexcel->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');

            header('Content-Disposition: attachment;filename="impuestos.xls"');

            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed

            header('Cache-Control: max-age=1');



            // If you're serving to IE over SSL, then the following may be needed

            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1

            header ('Pragma: public'); // HTTP/1.0



            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

            $objWriter->save('php://output');

            exit;

        }

        

        public function import_excel(){

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

            $config = array();

            if(!empty($_FILES)){

                

                $config['upload_path'] = 'uploads/';

                $config['allowed_types'] = 'xls';

                

                $this->load->library('upload', $config);

                

                if(!empty($_FILES['archivo']['name'])){

                        if (!$this->upload->do_upload('archivo')){

                            $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');

                        }

                        else

                        {

                            $upload_data = $this->upload->data();

                            $excel_name = $upload_data['file_name'];

                            

                            $reader = PHPExcel_IOFactory::createReaderForFile("uploads/".$excel_name);

                            $reader->setReadDataOnly(TRUE);

                            $objXLS = $reader->load("uploads/".$excel_name);

                            $campos[] = "No importar este campo";

                            for($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                                if($flag){

                                    $result = $alpha[$pointer].$alpha[$cursor];

                                    $cursor++;

                                    if($cursor >= (count($alpha) - 1)){

                                        $cursor = 0;

                                        $pointer++;

                                    }

                                }

                                else{

                                    $result = $alpha[$cursor];

                                    $cursor++;

                                    if($cursor >= (count($alpha) - 1)){

                                        $cursor = 0;

                                        $flag = true;

                                    }

                                }



                                if($objXLS->getSheet(0)->getCell($result.'1')->getValue() != ""){

                                    $campos[] = $objXLS->getSheet(0)->getCell($result.'1')->getValue();

                                }

                                else{

                                    break;

                                }

                            }

                            $data['campos'] = $campos;

                            $objXLS->disconnectWorksheets();

                            $this->session->set_flashdata("file_upload_impuesto", $excel_name);

                            unset($objXLS);

                            $this->layout->template('member')->show('impuestos/import_excel_fields', array('data' => $data));

                        }

                    }

                

            }

            else if(isset($_POST["submit"])){

                $nombre = $this->input->post("nombre");

                $porciento = $this->input->post("porciento");

                $excel_name = $this->session->flashdata("file_upload_impuesto");

                

                $reader = PHPExcel_IOFactory::createReaderForFile("uploads/".$excel_name);

                $reader->setReadDataOnly(TRUE);

                $objXLS = $reader->load("uploads/".$excel_name);

                

                for($i = 0; $i <= (count($alpha) * count($alpha)); $i++) {

                        if($flag){

                            $result = $alpha[$pointer].$alpha[$cursor];

                            $cursor++;

                            if($cursor >= (count($alpha) - 1)){

                                $cursor = 0;

                                $pointer++;

                            }

                        }

                        else{

                            $result = $alpha[$cursor];

                            $cursor++;

                            if($cursor >= (count($alpha) - 1)){

                                $cursor = 0;

                                $flag = true;

                            }

                        }



                        if($objXLS->getSheet(0)->getCell($result.'1')->getValue() != ""){

                            $campos[$result] = $objXLS->getSheet(0)->getCell($result.'1')->getValue();

                        }

                        else{

                            break;

                        }

                    }

                

               

                foreach ($campos as $key => $value) {

                    if($value == $nombre){

                        $nombre = $key;

                    }

                    else if($value == $porciento){

                        $porciento = $key;

                    }

                }

                

                

                $count = 2;

                $adicionados = 0;

                $noadicionados = 0;

                if($nombre != 'No importar este campo' && $porciento != 'No importar este campo'){

                    while($objXLS->getSheet(0)->getCell($nombre.$count)->getValue() != '' || $objXLS->getSheet(0)->getCell($porciento.$count)->getValue() != ''){

                        $porcientoData = $objXLS->getSheet(0)->getCell($porciento.$count)->getValue();

                        $nombreData = $objXLS->getSheet(0)->getCell($nombre.$count)->getValue();



                        if(!$this->impuestos->excel_exist($nombreData, $porcientoData)){

                        $array_datos = array(

                                "nombre_impuesto" => $nombreData,

                                "porciento"  	=> $porcientoData

                            );

                            $this->impuestos->excel_add($array_datos);

                            $adicionados++;

                        }

                        else{

                            $noadicionados++;

                        }

                        $count++;

                    }

                }else{

                    $this->session->set_flashdata('message', custom_lang('sima_import_failure', 'La importación falló'));

                     redirect('impuestos/import_excel');

                }

                $objXLS->disconnectWorksheets();

                unset($objXLS);

                $data['count'] = $count-2;

                $data['adicionados'] = $adicionados;

                $data['noadicionados'] = $noadicionados;

                unlink("uploads/$excel_name");

                

                $this->layout->template('member')->show('impuestos/import_complete', array('data' => $data));

                

            }

            else{

                $data['data']['upload_error'] = $error_upload;

                $this->layout->template('member')->show('impuestos/import_excel', array('data' => $data));

            }



        }

        

        

}

?>
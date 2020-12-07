<?php

    class lista_precios extends CI_Controller 
{
        var $dbConnection;
        const ATRIBUTOS = 3;
        
	function __construct() {
            parent::__construct();
            
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            
            $this->load->model("lista_precios_model",'lista_precios');
            $this->lista_precios->initialize($this->dbConnection);

            $this->load->model("lista_detalle_precios_model",'lista_detalle_precios');
            $this->lista_detalle_precios->initialize($this->dbConnection);

            $this->load->model("productos_model",'productos');
            $this->productos->initialize($this->dbConnection);

            $this->load->model("clientes_model",'clientes');
            $this->clientes->initialize($this->dbConnection);

            $this->load->model("almacenes_model",'almacenes');
            $this->almacenes->initialize($this->dbConnection);
            
            $this->load->model("opciones_model",'opciones');
            $this->opciones->initialize($this->dbConnection);
            
            $this->load->model("miempresa_model", 'mi_empresa');
            $this->mi_empresa->initialize($this->dbConnection);

            $idioma = $this->session->userdata('idioma');
            $this->lang->load('sima', $idioma);
        }
        
        public function index()
        {
            if (!$this->ion_auth->logged_in()) {
                redirect('auth', 'refresh');
            }
           
            $data['atributos'] = $this->almacenes->verificar_modulo_habilitado($this->session->userdata('user_id'), self::ATRIBUTOS);
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('lista_precios/index', ['data' =>$data]);
        }

        public function get_ajax_data()
        {
            $this->output->set_content_type('application/json')->set_output(json_encode($this->lista_precios->get_ajax_data_index()));
        }
        
        public function nuevo(){
            if (!$this->ion_auth->logged_in()) {
                redirect('auth', 'refresh');
            }
            
            $data = array();
            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["almacenes"] = $this->almacenes->get_all(0);
            $data["lista_precios"] = $this->lista_precios->leer();
            $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $data["precio_almacen"] = $this->opciones->getOpcion("precio_almacen");
            $this->layout->template('member')->show('lista_precios/nuevo', $data);
        }
        
        public function editar($id){
            if (!$this->ion_auth->logged_in()) {
                redirect('auth', 'refresh');
            }
            
            $data = array();
            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["almacenes"] = $this->almacenes->get_all(0);
            $data["lista_precios"] = $this->lista_precios->get($id)[0];
            $porcentaje = $this->opciones->getNombre("listaPrecioPorcentaje_".$id);
            if(!empty($porcentaje))
            {
                $data['detalle'] = array(array("id"=>" ","codigo"=>"todos","nombre"=>"los productos","precio_venta"=>"-","precio"=>"-",'id_impuesto'=>""));
                $data['porcentaje'] = $porcentaje['valor_opcion'];
            }else
            {
                $data['detalle'] = $this->lista_detalle_precios->getIdLista($id);
            }
            
            $data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));
            $data["precio_almacen"] = $this->opciones->getOpcion("precio_almacen");
            //var_dump($data['detalle']);
            //die();
            $this->layout->template('member')->show('lista_precios/editar', $data);
        }

        public function crear(){
            $isExist = $this->lista_precios->isExist($_POST['nombre']);
            if (!empty($isExist))
            {
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done' => 0))
                );
            } else {
                $lista = $this->lista_precios->crear();
                if(!empty($lista)){

                    $list_id=array();
                    $list_id = $this->lista_precios->getMaxId();
                    $detail = array();
					
                    if($_POST['todos'] == 'false'){
				        $detail_list = $_POST['detail_list'];
                        foreach ($detail_list as $key => $value) 
                        {
                            $detail['product_id'] = $value['id'];
                            $detail['impuesto'] = $value['id_impuesto'];
                            $detail['lista'] = $list_id[0]->id;
                            $detail['precio_nuevo'] = $value['nuevo_total_sin_iva'];
                            $this->lista_detalle_precios->create($detail);
                        }
                     }  else {
                        $data = array(
                            "nombre_opcion"=>"listaPrecioPorcentaje_".$list_id[0]->id,
                            "valor_opcion"=>$_POST['descuento'],
                        );
                        $this->opciones->setNew($data); 
                        $detail['lista'] = $list_id[0]->id;
                        $detail['descuento'] = $this->input->post('descuento');
                        $this->lista_detalle_precios->create_all($detail);
                    }
    				 
                    $this->output->set_content_type('application/json')->set_output(json_encode(array('done'=>$lista)));
                } else {

                   $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>0))
                   );
                }
            }
        }
        public function modificar(){
            $isExist = $this->lista_precios->isExistEdit($_POST['nombre'],$_POST['id']);
            if (!empty($isExist))
            {
                $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done' => 0))
                );
            } else {
                $lista = $this->lista_precios->modificar();
                if(!empty($lista)){

                    $detail = array();
                    $this->lista_detalle_precios->delete($lista);
                    $this->opciones->deleteName("listaPrecioPorcentaje_".$lista);
                    if($_POST['todos'] == 'false'){
                        $detail_list = $_POST['detail_list'];
                        foreach ($detail_list as $key => $value) {
                            if(isset($value['id'])){ 
                                $detail['product_id'] = $value['id'];
                                $detail['impuesto'] = $value['id_impuesto'];
                                $detail['lista'] = $lista;
                                $detail['precio_nuevo'] = ($value['nuevo_total_sin_iva'] - (($value['nuevo_total_sin_iva'] * $_POST['descuento']) /100));
                                $this->lista_detalle_precios->create($detail);
                            }else {
                                echo json_encode($detail_list);
                                die();
                            }
                        }
                    } else {
                        $data = array(
                            "nombre_opcion" => "listaPrecioPorcentaje_" . $lista,
                            "valor_opcion" => $_POST['descuento'],
                        );

                        $this->opciones->deleteName($data['nombre_opcion']);
                        $this->opciones->setNew($data); 
                        $detail['lista'] = $lista;

                        $detail['descuento'] = $_POST['descuento'];


                        $this->lista_detalle_precios->create_all($detail);
                    }
    				 
                    $this->output->set_content_type('application/json')->set_output(json_encode(array('done'=>$lista)));
                } else {

                   $this->output->set_content_type('application/json')->set_output(
                    json_encode(array('done'=>0))
                   );
                }
            }
        }

        public function importar()
        {
            if (!$this->ion_auth->logged_in())
                redirect('auth', 'refresh');
            
            //$this->layout->template('ventas');        
            $this->layout->template('member');        
            $error_upload = "";
            $carpeta = 'uploads/archivos_productos/';

            $data["grupo_clientes"] = $this->clientes->get_group_all(0);
            $data["almacenes"] = $this->almacenes->get_all(0);
            $data["lista_precios"] = $this->lista_precios->leer();
            $data['data']['upload_error'] = $error_upload;
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["data"]["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 

            $error_upload = "";
            $total = 0;
            $total_correctos = 0;
            $total_incorrectos = 0;

            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'xls|xlsx|csv';
            $prefijo = substr(md5(uniqid(rand())),0,8);
            $config['file_name'] = $prefijo.$this->session->userdata('user_id');
            $this->load->library('upload', $config);

            if ($this->form_validation->run('import_libro_precios') == true)
            { 
                if(!empty($_FILES['archivo']['name']))       
                {
                    if (!$this->upload->do_upload('archivo')) 
                    {
                        $error_upload = ('<script> alert("Volver a cargar el archivo que guardo despues de seguir los pasos"); </script> <p class="text-error">Volver a cargar el archivo que guardo llamado plantilla inventario</p>');
                        $data['data']['upload_error'] = $error_upload;
                        $this->layout->show('productos/import_libro_de_precios', $data);
                    } else {
                        $this->load->library('phpexcel');
                        $name = $_FILES['archivo']['name'];
                        $tname = $_FILES['archivo']['tmp_name'];
                        $obj_excel = PHPExcel_IOFactory::load($tname);
                        $dat_excel = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                        $datos_fallo = array();
                        $errores = false;

                        $lista = $this->lista_precios->crear();
                        $lista = array(
                            0 => array(
                                'id' => 0
                            )
                        );
                        if (!empty($lista))
                        {
                            $list_id = array();
                            $list_id = $this->lista_precios->getMaxId();   
                            $detail = array();

                            for ($i = 2; $i<count($dat_excel)+1; $i++)
                            {
                                $producto = $this->productos->get(null, trim($dat_excel[$i]['A']), trim($dat_excel[$i]['B']));
                                if (count($producto) > 0)
                                {
                                    $precio_nuevo = 0;
                                    $precio_anterior = is_numeric($dat_excel[$i]['C']) ? $dat_excel[$i]['C'] : $producto[0]->precio_venta;

                                    if (is_numeric($dat_excel[$i]['D']))
                                        $precio_nuevo = $precio_anterior - ($precio_anterior * $dat_excel[$i]['D'] / 100);
                                    else if (is_numeric($dat_excel[$i]['E']))
                                        $precio_nuevo = $dat_excel[$i]['E'];
                                    else
                                        $precio_nuevo = $precio_anterior * 1;

                                    $detail['product_id'] = $producto[0]->id;
                                    $detail['impuesto'] = $producto[0]->impuesto;
                                    $detail['lista'] = $list_id[0]->id;
                                    $detail['precio_nuevo'] = $precio_nuevo;
                                    $this->lista_detalle_precios->create($detail);
                                } else {
                                    $errores = true;
                                    array_push($datos_fallo, array(
                                            'registro' => $dat_excel[$i], 
                                            'mensaje' => 'No se encontró el producto.'
                                        )
                                    );
                                }
                            }
                            
                            if ($errores)
                            {
                                $hoja_errores = $this->load->library('phpexcel');
                                $hoja_errores = new PHPExcel();
                                $hoja_errores->setActiveSheetIndex(0);
                                $hoja_errores->getActiveSheet()->setCellValue('A1', 'Codigo');
                                $hoja_errores->getActiveSheet()->setCellValue('B1', 'Producto');
                                $hoja_errores->getActiveSheet()->setCellValue('C1', 'Precio');
                                $hoja_errores->getActiveSheet()->setCellValue('D1', 'Descuento %');
                                $hoja_errores->getActiveSheet()->setCellValue('E1', 'Nuevo precio');
                                $hoja_errores->getActiveSheet()->setCellValue('F1', 'Motivo del fallo');
                                $hoja_errores->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                                $hoja_errores->getActiveSheet()->getColumnDimension('B')->setWidth(70);
                                $hoja_errores->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                                $hoja_errores->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                                $hoja_errores->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                                $hoja_errores->getActiveSheet()->getColumnDimension('F')->setWidth(70);
                                $hoja_errores->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
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
                                                'argb' => 'FFDCDCDC'
                                            ),
                                            'endcolor' => array(
                                                'argb' => 'FFDCDCDC'
                                            )
                                        )
                                    )
                                );
                                
                                $index = 1;
                                for ($i = 0; $i<count($datos_fallo); $i++)
                                {
                                    $index ++;
                                    $registro = $datos_fallo[$i]['registro'];
                                    $mensaje = $datos_fallo[$i]['mensaje'];
                                    $hoja_errores->getActiveSheet()->setCellValue('A'.$index, $registro['A']);
                                    $hoja_errores->getActiveSheet()->setCellValue('B'.$index, $registro['B']);
                                    $hoja_errores->getActiveSheet()->setCellValue('C'.$index, $registro['C']);
                                    $hoja_errores->getActiveSheet()->setCellValue('D'.$index, $registro['D']);
                                    $hoja_errores->getActiveSheet()->setCellValue('E'.$index, $registro['E']);
                                    $hoja_errores->getActiveSheet()->setCellValue('F'.$index, $mensaje);
                                }

                                $hoja_errores->getActiveSheet()->setTitle('Libro de precios');
                                $this->exportarFallos($hoja_errores);
                                $enviarCorreo = FALSE;
                            }
                            
                            chmod('../../'.$carpeta, 0777);
                            unlink('../../'.$carpeta.$config['file_name'].'xlsx');
                            $result['valid'] = true;
                            if (isset($enviarCorreo))
                            {
                                $result['message'] = 'Libro de precios importado con errores';
                                $result['validar_almacen'] = "danger";
                            } else {
                                $result['message'] = 'Libro de precios importado correctamente';
                                $result['validar_almacen'] = "success";
                            }                                     
                            $this->session->set_flashdata('message',  custom_lang('sima_bill_send_message', $result['message']));
                            $this->session->set_flashdata('validar_almacen', custom_lang('sima_product_created_message', $result['validar_almacen']));
                            redirect("productos/libro_de_precios");
                        }
                    }
                } else {
                    $this->layout->show('productos/import_libro_de_precios', $data);
                }
            } else {
                $data["precio_almacen"] = $this->opciones->getOpcion('precio_almacen');
                $this->layout->show('productos/import_libro_de_precios', $data);
            }
        }

        private function exportarFallos($hoja_errores)
        {
            header ('Content-Type: application/vnd.ms-excel');
            header ('Content-Disposition: attachment;filename="Libro de precios no importados.xlsx"');
            header ('Cache-Control: max-age=0');
            header ('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');
            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($hoja_errores, 'Excel2007');
            $objWriter->save("uploads/archivos_productos/Libro de precios no importados.xlsx");
            $this->session->set_flashdata('archivo',  custom_lang('sima_bill_send_message', 'Guardado'));
        }

        public function exportar($id_almacen = NULL)
        {

            $this->load->library('phpexcel');
            $this->phpexcel->setActiveSheetIndex(0);
            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Codigo');
            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Producto');
            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Precio');
            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Descuento %');
            $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nuevo precio');

            $this->productos->initialize($this->dbConnection);
            $lista = $this->productos->getListProductos($id_almacen);

            $index = 1;
            for ($i = 0; $i<count($lista); $i++)
            {
                $index ++;
                $this->phpexcel->getActiveSheet()->setCellValue('A'.$index, ' '.$lista[$i]->codigo);
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$index, $lista[$i]->nombre);
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$index, $lista[$i]->precio_venta);
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$index, '');
                $this->phpexcel->getActiveSheet()->setCellValue('E'.$index, '');
            }

            $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
            $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

            $this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
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
                            'argb' => 'FFDCDCDC'
                        ),
                        'endcolor' => array(
                            'argb' => 'FFDCDCDC'
                        )
                    )
                )
            );
            
            // Rename worksheet
            $this->phpexcel->getActiveSheet()->setTitle('Libro de precios');
            header ('Content-Type: application/vnd.ms-excel');
            header ('Content-Disposition: attachment;filename="Libro de precios.xlsx"');
            header ('Cache-Control: max-age=0');
            header ('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');

            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
            ob_clean();
            
            $objWriter->save('php://output');
        }

    }
?>
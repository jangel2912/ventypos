<?php

class Presupuestos extends CI_Controller {

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



        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);


        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

         $this->load->model("opciones_model", 'opciones');

        $this->almacenes->initialize($this->dbConnection);

         $this->load->model('crm_model');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    function index($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        /* $data["total"] = $this->presupuestos->get_total();     

          $data['data'] = $this->presupuestos->get_all($offset);

          $data['monto_total'] = $this->presupuestos->get_sum(); */

          $data_empresa = $this->mi_empresa->get_data_empresa();
          $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
          $this->presupuestos->check_column_nota_cotizacion();
          $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
        $this->layout->template('member')->show('presupuestos/index' , array('data' => $data));
    }

    public function get_ajax_data() {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->presupuestos->get_ajax_data()));
    }

    function nuevo() {
        
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }
        
        $data["formato_moneda"]=$this->opciones->getDataMoneda();//die(var_dump( $data["formato_moneda"]));
        $data['cod'] = $this->_codigo();

        if ($this->form_validation->run('presupuestos') == true) {
            
            $data = $this->presupuestos->add();
            
            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));
            
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
        } else {
            $data['data']['upload_error'] = "";

            $data['almacenes'] = $this->almacenes->get_combo_data();

            $data['impuestos_productos'] = $this->impuestos->get_combo_data();

            $data['categorias'] = $this->categorias->get_combo_data();

            $data['impuestos'] = $this->impuestos->get_combo_data_factura();
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id'))); 
            $this->layout->template('member')->show('presupuestos/nuevo' , array('data' => $data));
        }
    }

    function eliminar_producto_coti($coti, $id) {

        $this->presupuestos->eliminar_producto_actualizar($coti, $id);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha quitado correctamente el producto"));

        redirect("presupuestos/editar/" . $coti);
    }

    function agregar_producto_coti($coti) {

        $this->presupuestos->agregar_producto_coti($coti);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha agregado correctamente el producto"));

        redirect("presupuestos/editar/" . $coti);
    }

    function editar($id = null) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }




        if ($this->input->post('id_cliente')) {

            $data = $this->presupuestos->actualizar_coti();

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true)));
        } else {

            $data['data'] = $this->presupuestos->get_by_id($id);

            $data['detail'] = $this->presupuestos->get_detail($id);

            $data['impuestos'] = $this->impuestos->get_combo_data();

            $data['impuestos_1'] = $this->dbConnection->query("SELECT * FROM impuesto")->result();


            $data["formato_moneda"]=$this->opciones->getDataMoneda();
            $data_empresa = $this->mi_empresa->get_data_empresa();
            $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio']; 
            $this->layout->template('member')->show('presupuestos/editar', array('data' => $data, 'id' => $id));
        }
    }

    function eliminar($id) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->presupuestos->delete($id);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha eliminado correctamente"));

        redirect("presupuestos");
    }

    function enviar_email($id = 0) {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $empresa = $this->miempresa->get_data_empresa();

        $data_factura = $this->presupuestos->get_by_id($id);

        $detail = $this->presupuestos->get_detail($id);
        //var_dump($this->presupuestos->get_detail($id));die;
        /*     var_dump($empresa); */
        $estaimg=0;
        if ($empresa['data']['plantilla_cotizacion'] == 'moderna_completa_ingles') {
            $data = array(
                'venta' => $this->presupuestos->get_by_id($id)
                , 'detalle_venta' => $this->presupuestos->get_detail($id)
                , 'detalle_pago' => $this->presupuestos->get_detail($id)
                , 'data_empresa' => $empresa
                , 'tipo_factura' => $empresa['data']['tipo_factura']
            );
            $this->layout->template('ajax')->show('presupuestos/_imprimemediacarta_ingles_completa', array('data' => $data));
        }

        if ($empresa['data']['plantilla_cotizacion'] != 'moderna_completa_ingles') {

            require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

            $pdf->setPrintHeader(false);

            $pdf->setPrintFooter(false);

            $pdf->AddPage('P', "LETTER");

            $total = 0;

            $dire = $empresa["data"]['direccion'];
            $telef = $empresa["data"]['telefono'];
            $email = $empresa["data"]['email'];

            $cafac = $empresa["data"]['cabecera_factura'];

            $nombre_empresa = $empresa["data"]['nombre'];
            $nit = $empresa["data"]['nit'];
            $resol = $empresa["data"]['resolucion'];
            $tele = $empresa["data"]['telefono'];
            $dire = $empresa["data"]['direccion'];
            $web = $empresa["data"]['web'];
            $img="";
            if(!empty($empresa['data']['logotipo'])){            
              $img = base_url("uploads/{$empresa['data']['logotipo']}");
                          
                if(getimagesize($img)){
                    $estaimg=1;       
                }else{
                    $estaimg=0;
                }
            }

            $fech = date("d/m/Y", strtotime($data_factura['fecha']));
            $numero = $data_factura['numero'];

            $monto_siva = number_format($data_factura['monto_siva']);
            $monto_iva = number_format($data_factura['monto_iva']);
            //$monto = number_format($data_factura['monto']);
            $monto = $this->opciones->formatoMonedaMostrar($data_factura['monto']);

            $nomcomercial_cli = $data_factura['nombre_comercial'];
            $direccion_cli = $data_factura['direccion'];
            $telefono_cli = $data_factura['telefono'];
            $pais_cli = $data_factura['pais'];


            $html = <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
	                        <tr>
                            <td width="43%"  align="center" style=" font-size: 11px">
                            </td>

                            <td width="13%"  align="center">
                             
                            </td>

                            <td width="30%" align="right">
                           </td>
						</tr>				 
                        <tr>
                            <td width="43%"  align="center" style=" font-size: 11px">
            $nombre_empresa <br>  
              NIT  $nit <br>
                $resol <br>
                $tele <br>
                $dire <br>
                $web 
                            </td>

                            <td width="13%"  align="center">
                             
                            </td>

                            <td width="30%" align="right">
EOF;
                        if((!empty($img)) && ($estaimg==1)){
				
                            $html .= <<<EOF
                              <img width="70px" src="$img" />                              
EOF;
                        }
                            $html .= <<<EOF
                              </td>
						</tr>
				</table>	

        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; ">
                      <tr>                         
                        <td>&nbsp;&nbsp;
                      <b>Fecha de la cotización</b>   $fech 
                        </td>
                         <td align="right">
                          <b>No. de cotización</b> $numero
                         </td>
                      </tr>
                     <tr>                         
                        <td style="border-top: 1px solid #000000;">		  
					   &nbsp;<b>Cliente: </b> $nomcomercial_cli
                         </td> 	
						 <td style="border-top: 1px solid #000000;">		 
						&nbsp;<b>Dirección: </b>  
						 $direccion_cli
						  </td> 
					 </tr>	
                     <tr>                         
                        <td style="border-top: 1px solid #000000;">		  
					   &nbsp;<b>Telefono: </b> $telefono_cli
                         </td> 	
						 <td style="border-top: 1px solid #000000;">		 
						&nbsp;<b>Pais: </b>  
						$pais_cli
						  </td> 
					 </tr>						 				  				  
                   </table>
EOF;


            $html .= <<<EOF
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; ">
                      <tr>                         
                          <th  style="font-size: 10px" align="left" width="51%"><strong>Nombre</strong></th>
                          <th  style="font-size: 10px" align="left" width="9%" ><strong>Cantidad</strong></th>
                          <th  style="font-size: 10px" align="right" width="10%"><strong>Precio</strong></th>
                          <th  style="font-size: 10px" align="right" width="15%"><strong>Subtotal</strong></th>
                          <th  style="font-size: 10px" align="right" width="15%"><strong>Total</strong></th>
                      </tr>
EOF;
            $counter = NULL;
            $hasta = NULL;
            $i = NULL;
            $descuento=0;
            $subtotal=0;
            foreach ($detail as $k) {
                $counter++;
                $precio_t = $k['precio'] * $k['cantidad'];

                $impuesto = $k['imp'] * $precio_t / 100;

                $total = $impuesto + $precio_t;
                $subtotal=+ $precio_t;

                $descuento += (($impuesto + $precio_t) * $k['descuento'] / 100);

                $precio_temp = $k['precio'];
                
                $nom = $k["nombre"]."-".$k['codigo'];
                $descripcion_d = $k["descripcion_d"];
                $cantidad = $k["cantidad"];
                $precio = number_format($precio_temp);
                $subtotal = number_format($subtotal);
                $total = number_format($total);

                if($descripcion_d != ""){
                    $descripcion_d = '('.$descripcion_d.')';
                }

                $html .= <<<EOF
                      <tr>                         
                       <td  style="font-size: 10px" align="left">$nom  <br><span style="font-size:8px;"> $descripcion_d</span></td>
                       <td  style="font-size: 10px" align="left"> $cantidad </td>
                       <td  style="font-size: 10px" align="right"> $precio </td>
                       <td  style="font-size: 10px" align="right"> $subtotal </td>
                       <td  style="font-size: 10px" align="right"> $total </td>
                      </tr>
EOF;
            }

            $hasta = 8 - $counter;
            for ($i = 1; $i <= $hasta; $i++) {
                $html .= <<<EOF
                      <tr>                         
                       <td  style="font-size: 10px" align="left">   </td>
                       <td  style="font-size: 10px" align="left">  </td>
                       <td  style="font-size: 10px" align="right">   </td>
                       <td  style="font-size: 10px" align="right">  </td>
                      </tr>
EOF;
            }
            $html .= <<<EOF
              </table>
EOF;
            

            $tot_desc = $this->opciones->formatoMonedaMostrar($data_factura['monto_siva'] + $descuento); 
            $descuento =  $this->opciones->formatoMonedaMostrar($descuento);
            $nota_cotizacion = $data_factura['nota'];

            $html .= <<<EOF
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; border-bottom: 1px solid #000000;">
                      <tr>                         
                          <td  style="border-right: solid 1px #000000; font-size: 10px; width: 319px" align="left">
                            <b>Nota general</b><br>
                                $nota_cotizacion
                            </td>
                          <td  style="border-right: solid 1px #000000; font-size: 10px; width: 120px; " align="left">
						  <b>&nbsp;Subtotal + Desc: </b><br>
						  <b>&nbsp;Descuento: </b><br>
						  <b>&nbsp;IVA: </b><br>
						  <b>&nbsp;Total: </b>
						  </td>
						  <td  style="font-size: 10px;  width: 120px;" align="right">
						  <b>$tot_desc</b><br>
						  <b>$descuento</b><br>
						  <b>$monto_iva</b><br>
						  <b>$monto</b>
						  </td>
                      </tr>
			 </table>		  
EOF;


            $pdf->writeHTML($html, true, false, true, false, '');

            ob_clean();

            $pdf_name = 'Cotización ' . $data_factura['numero'] . '.pdf';

            ob_clean();

            $pdf->Output("$pdf_name", 'F');
            $this->load->library('email');      
            $this->email->initialize();  
            $this->email->clear();
            
            if (!empty($empresa["data"]["email"])) {
                $this->email->from($empresa["data"]["email"], $empresa["data"]["nombre"]);
            } else {
                $this->email->from('no-responder@vendty.net', $empresa["data"]["nombre"]);  
            }     

            $this->email->to($data_factura['email']);
            $this->email->subject("Cotización " . $data_factura["numero"]);
            $this->email->attach("$pdf_name");
            $this->email->message("Para ver su cotización descargue el adjunto.");
            $this->email->send();

            unlink("$pdf_name");

            $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha enviado la factura correctamente"));
            redirect("presupuestos/index");
        }
    }

    function imprimir($id = 0) {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $empresa = $this->miempresa->get_data_empresa();

        $data_factura = $this->presupuestos->get_by_id($id);

        $detail = $this->presupuestos->get_detail($id);
        //var_dump($this->presupuestos->get_detail($id));die;
        /*     var_dump($empresa); */
        $estaimg=0;
        if ($empresa['data']['plantilla_cotizacion'] == 'moderna_completa_ingles') {
            $data = array(
                'venta' => $this->presupuestos->get_by_id($id)
                , 'detalle_venta' => $this->presupuestos->get_detail($id)
                , 'detalle_pago' => $this->presupuestos->get_detail($id)
                , 'data_empresa' => $empresa
                , 'tipo_factura' => $empresa['data']['tipo_factura']
            );
            $this->layout->template('ajax')->show('presupuestos/_imprimemediacarta_ingles_completa', array('data' => $data));
        }

        if ($empresa['data']['plantilla_cotizacion'] != 'moderna_completa_ingles') {

            require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

            $pdf->setPrintHeader(false);

            $pdf->setPrintFooter(false);

            $pdf->AddPage('P', "LETTER");

            $total = 0;

            $dire = $empresa["data"]['direccion'];
            $telef = $empresa["data"]['telefono'];
            $email = $empresa["data"]['email'];

            $cafac = $empresa["data"]['cabecera_factura'];

            $nombre_empresa = $empresa["data"]['nombre'];
            $nit = $empresa["data"]['nit'];
            $resol = $empresa["data"]['resolucion'];
            $tele = $empresa["data"]['telefono'];
            $dire = $empresa["data"]['direccion'];
            $web = $empresa["data"]['web'];
            $img="";
            if(!empty($empresa['data']['logotipo'])){            
              $img = base_url("uploads/{$empresa['data']['logotipo']}");
                          
                if(getimagesize($img)){
                    $estaimg=1;       
                }else{
                    $estaimg=0;
                }
            }

            $fech = date("d/m/Y", strtotime($data_factura['fecha']));
            $numero = $data_factura['numero'];

            $monto_siva = number_format($data_factura['monto_siva']);
            $monto_iva = number_format($data_factura['monto_iva']);
            //$monto = number_format($data_factura['monto']);
            $monto = $this->opciones->formatoMonedaMostrar($data_factura['monto']);

            $nomcomercial_cli = $data_factura['nombre_comercial'];
            $nifcifcomercial_cli = $data_factura['nif_cif'];
            $direccion_cli = $data_factura['direccion'];
            $telefono_cli = $data_factura['telefono'];
            $pais_cli = $data_factura['pais'];


            $html = <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
	                        <tr>
                            <td width="43%"  align="center" style=" font-size: 11px">
                            </td>

                            <td width="13%"  align="center">
                             
                            </td>

                            <td width="30%" align="right">
                           </td>
						</tr>				 
                        <tr>
                            <td width="43%"  align="center" style=" font-size: 11px">
            $nombre_empresa <br>  
              NIT  $nit <br>
                $resol <br>
                $tele <br>
                $dire <br>
                $web 
                            </td>

                            <td width="13%"  align="center">
                             
                            </td>

                            <td width="30%" align="right">
EOF;
                        if((!empty($img)) && ($estaimg==1)){
				
                            $html .= <<<EOF
                              <img width="70px" src="$img" />                              
EOF;
                        }
                            $html .= <<<EOF
                              </td>
						</tr>
				</table>	

        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; ">
                      <tr>                         
                        <td>&nbsp;&nbsp;
                      <b>Fecha de la cotización</b>   $fech 
                        </td>
                         <td align="right">
                          <b>No. de cotización</b> $numero
                         </td>
                      </tr>
                     <tr>                         
                        <td style="border-top: 1px solid #000000;">		  
					   &nbsp;<b>Cliente: </b> $nomcomercial_cli
                         </td>
                         <td style="border-top: 1px solid #000000;">		  
					   &nbsp;<b>NIT: </b> $nifcifcomercial_cli
                         </td>  
					 </tr>	
					 <tr>
					 <td colspan="2" style="border-top: 1px solid #000000;">		 
						&nbsp;<b>Dirección: </b>  
						 $direccion_cli
						  </td>
                    </tr>
                     <tr>                         
                        <td style="border-top: 1px solid #000000;">		  
					   &nbsp;<b>Telefono: </b> $telefono_cli
                         </td> 	
						 <td style="border-top: 1px solid #000000;">		 
						&nbsp;<b>Pais: </b>  
						$pais_cli
						  </td> 
					 </tr>						 				  				  
                   </table>
EOF;


            $html .= <<<EOF
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; ">
                      <tr>                         
                          <th  style="font-size: 10px" align="left" width="51%"><strong>Nombre</strong></th>
                          <th  style="font-size: 10px" align="left" width="9%" ><strong>Cantidad</strong></th>
                          <th  style="font-size: 10px" align="right" width="10%"><strong>Precio</strong></th>
                          <th  style="font-size: 10px" align="right" width="15%"><strong>Subtotal</strong></th>
                          <th  style="font-size: 10px" align="right" width="15%"><strong>Total</strong></th>
                      </tr>
EOF;
            $counter = NULL;
            $hasta = NULL;
            $i = NULL;
            $descuento=0;
            $subtotal=0;
            foreach ($detail as $k) {
                $counter++;
                $precio_t = $k['precio'] * $k['cantidad'];

                $impuesto = $k['imp'] * $precio_t / 100;

                $total = $impuesto + $precio_t;
                $subtotal=+ $precio_t;

                $descuento += (($impuesto + $precio_t) * $k['descuento'] / 100);

                $precio_temp = $k['precio'];
                
                $nom = $k["nombre"]."-".$k['codigo'];
                $descripcion_d = $k["descripcion_d"];
                $cantidad = $k["cantidad"];
                $precio = number_format($precio_temp);
                $subtotal = number_format($subtotal);
                $total = number_format($total);

                if($descripcion_d != ""){
                    $descripcion_d = '('.$descripcion_d.')';
                }

                $html .= <<<EOF
                      <tr>                         
                       <td  style="font-size: 10px" align="left">$nom  <br><span style="font-size:8px;"> $descripcion_d</span></td>
                       <td  style="font-size: 10px" align="left"> $cantidad </td>
                       <td  style="font-size: 10px" align="right"> $precio </td>
                       <td  style="font-size: 10px" align="right"> $subtotal </td>
                       <td  style="font-size: 10px" align="right"> $total </td>
                      </tr>
EOF;
            }

            $hasta = 8 - $counter;
            for ($i = 1; $i <= $hasta; $i++) {
                $html .= <<<EOF
                      <tr>                         
                       <td  style="font-size: 10px" align="left">   </td>
                       <td  style="font-size: 10px" align="left">  </td>
                       <td  style="font-size: 10px" align="right">   </td>
                       <td  style="font-size: 10px" align="right">  </td>
                      </tr>
EOF;
            }
            $html .= <<<EOF
              </table>
EOF;
            

            $tot_desc = $this->opciones->formatoMonedaMostrar($data_factura['monto_siva'] + $descuento); 
            $descuento =  $this->opciones->formatoMonedaMostrar($descuento);
            $nota_cotizacion = $data_factura['nota'];

            $html .= <<<EOF
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; border-bottom: 1px solid #000000;">
                      <tr>                         
                          <td  style="border-right: solid 1px #000000; font-size: 10px; width: 319px" align="left">
                            <b>Nota general</b><br>
                                $nota_cotizacion
                            </td>
                          <td  style="border-right: solid 1px #000000; font-size: 10px; width: 120px; " align="left">
						  <b>&nbsp;Subtotal + Desc: </b><br>
						  <b>&nbsp;Descuento: </b><br>
						  <b>&nbsp;IVA: </b><br>
						  <b>&nbsp;Total: </b>
						  </td>
						  <td  style="font-size: 10px;  width: 120px;" align="right">
						  <b>$tot_desc</b><br>
						  <b>$descuento</b><br>
						  <b>$monto_iva</b><br>
						  <b>$monto</b>
						  </td>
                      </tr>
			 </table>		  
EOF;


            $pdf->writeHTML($html, true, false, true, false, '');

            ob_clean();

            $pdf->Output('Cotización ' . $data_factura['numero'] . '.pdf', 'I');
        }
    }

    function toMoney($val, $symbol = '$', $r = 2) {


        $n = $val;
        $c = is_float($n) ? 1 : number_format($n, $r);
        $d = '.';
        $t = ',';
        $sign = ($n < 0) ? '-' : '';
        $i = $n = number_format(abs($n), $r);
        $j = (($j = $i) > 3) ? $j % 3 : 0;

        return $symbol . $sign . ($j ? substr($i, 0, $j) + $t : '') . preg_replace('/(\d{3})(?=\d)/', "$1" + $t, substr($i, $j));
    }

    function _codigo() {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $last_numero_presupuesto = $this->miempresa->last_numero_presupuesto();

        $numero_presupuesto = $this->miempresa->get_numero_presupuesto();



        $prefijo_presupuesto = $this->miempresa->get_prefijo_presupuesto();

        $cod = $this->presupuestos->get_max_cod();

        $new_cod = "";



        if ($cod == '') {



            if ($numero_presupuesto != $last_numero_presupuesto) {

                $this->miempresa->update_last_numero_presupuesto($numero_presupuesto);
            }

            $dig = ((int) $numero_presupuesto);

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;



            return $prefijo_presupuesto . $new_cod;
        } else {

            if ($numero_presupuesto != $last_numero_presupuesto) {

                $this->miempresa->update_last_numero_presupuesto($numero_presupuesto);

                $cod = $numero_presupuesto;
            } else {

                $cod = (int) $cod + 1;
            }

            $dig = ((int) $cod );

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;



            return $prefijo_presupuesto . $new_cod;
        }
    }

    function excel() {
        
    }

}

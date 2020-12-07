<?php
    class Bancos extends CI_Controller{

        function __construct(){
            parent::__construct();
            
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);

            $this->load->model("miempresa_model", 'mi_empresa');
            $this->mi_empresa->initialize($this->dbConnection);

            $this->load->model("bancos_model", 'bancos');
            $this->bancos->initialize($this->dbConnection);

            $this->load->model("almacenes_model", 'almacenes');
            $this->almacenes->initialize($this->dbConnection);

            $this->bancos->check_tables();
        }

        public function index(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            
            $this->layout->template('member')->show('bancos/index');
        }

        public function movimientos(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->layout->template('member')->show('bancos/movimientos/index');
        }

        public function conciliaciones(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["conciliaciones"] = $this->bancos->get_conciliaciones();
            $this->layout->template('member')->show('bancos/conciliaciones/index',$data);
        }

        public function nueva_conciliacion($id = NULL){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');

            $data = array();
            $data["saldo_inicial"] = 0;
            $data["banco"] = '';
            $data["conciliacion_pendiente"] = NULL;

            if($id != NULL){
                $data["banco"] = $this->bancos->get_banco($id);
                $data["movimientos"] = $this->bancos->get_movimientos_por_banco($id);
                $data["saldo_inicial"] = $data["banco"]->saldo_inicial;
                $data["conciliacion_pendiente"] = $this->bancos->get_conciliacion_pendiente($id);
                $data["movimientos_seleccionados"] = 0;
                $data["gastos_bancarios"] = 0;
                $data["impuestos_bancarios"] = 0;
                $data["entradas_bancarios"] = 0;
                $data["saldo_final"] = 0;
                $data["diferencia"] = 0;

                //print_r($data["conciliacion_pendiente"]);
                for($i=0; $i<count($data["movimientos"]);$i++){ 
                    $data["movimientos"][$i]->pendiente = 0;
                    if($data["movimientos"][$i]->estado != "" && $data["movimientos"][$i]->estado != NULL && $data["movimientos"][$i]->id_conciliacion != NULL){
                        if($data["movimientos"][$i]->tipo == 1) $data["saldo_inicial"] += $data["movimientos"][$i]->valor;
                        if($data["movimientos"][$i]->tipo == 2) $data["saldo_inicial"] -= $data["movimientos"][$i]->valor;  
                    } 

                    if(isset($data["conciliacion_pendiente"]["movimientos"]) && count($data["conciliacion_pendiente"]["movimientos"] > 0))
                    for($j=0;$j<count($data["conciliacion_pendiente"]["movimientos"]); $j++){ 
                        if($data["conciliacion_pendiente"]["movimientos"][$j]->id == $data["movimientos"][$i]->id){
                            $data["movimientos"][$i]->pendiente = 1;
                            $data["movimientos_seleccionados"]++; 
                              
                        } 
                    }
                }
            }else{
                $data['bancos'] = $this->bancos->get_bancos();
            }
            $this->layout->template('member')->show('bancos/conciliaciones/nuevo',$data);
        }
        
        public function nuevo(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["almacenes"] = $this->almacenes->get_all('0',true);           
            $this->layout->template('member')->show('bancos/nuevo',$data);
        }

        public function editar($id){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["almacenes"] = $this->almacenes->get_all('0',true);
            $data["banco"] = $this->bancos->get_banco($id);
            if($data["banco"] != NULL) $this->layout->template('member')->show('bancos/editar',$data);
            else redirect(site_url('bancos'));
        }

        public function editar_banco(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->bancos->editar_banco();
            $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Banco editado con exito'));
            redirect('bancos/index');
        }

        public function eliminar_banco(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["message"] = $this->bancos->eliminar_banco();
            echo json_encode($data);
        }

        public function get_ajax_data(){
            $start = $this->input->get('iDisplayStart');
            $limit = $this->input->get('iDisplayLength'); 
            $search = $this->input->get('sSearch');
            
            if($this->input->get('iSortCol_0')==0){
                if($order=$this->input->get('sEcho')=="1"){
                    $order="DESC";
                    $col="fecha_creacion ";
                }else{
                    $col= $this->input->get('iSortCol_0')+1;               
                    $order=$this->input->get('sSortDir_0');
                }
            }
            else{
                $col= $this->input->get('iSortCol_0')+1;
                $order=$this->input->get('sSortDir_0');
            }
            $orderby= " ORDER BY ".$col." ".$order;       

            $this->output->set_content_type('application/json')->set_output(json_encode($this->bancos->get_ajax_data($start,$limit,$search,$orderby)));
        }

        public function crear_banco(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->load->library('form_validation');

            if ($this->form_validation->run('bancos')) {
                $this->bancos->crear_banco();
                $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Banco creado correctamente'));
                redirect('bancos/index');
            }

            $this->layout->template('member')->show('bancos/nuevo');
        }


        /******************************************************/
        /********************** movimientos *******************/
        /******************************************************/
        public function get_ajax_data_movimientos(){
            $start = $this->input->get('iDisplayStart');
            $limit = $this->input->get('iDisplayLength'); 
            $search = $this->input->get('sSearch');
            
            if($this->input->get('iSortCol_0')==0){
                if($order=$this->input->get('sEcho')=="1"){
                    $order="DESC";
                    $col="fecha_creacion ";
                }else{
                    $col= $this->input->get('iSortCol_0')+1;               
                    $order=$this->input->get('sSortDir_0');
                }
            }
            else{
                $col= $this->input->get('iSortCol_0')+1;
                $order=$this->input->get('sSortDir_0');
            }
            $orderby= " ORDER BY ".$col." ".$order;       

            $this->output->set_content_type('application/json')->set_output(json_encode($this->bancos->get_ajax_data_movimientos($start,$limit,$search,$orderby)));
        } 

        public function nuevo_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data['bancos'] = $this->bancos->get_bancos();
            $data['tipo_movimientos'] = $this->bancos->get_tipo_movimientos();
            $this->layout->template('member')->show('bancos/movimientos/nuevo',$data);
        }

        public function nuevo_tipo_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->layout->template('member')->show('bancos/movimientos/nuevo_tipo_movimiento');
        }

        public function crear_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->load->library('form_validation');

            if ($this->form_validation->run('crear_movimiento')) {
                $this->bancos->crear_movimiento();
                $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Movimiento creado correctamente'));
                redirect('bancos/movimientos');
            }

            $data['bancos'] = $this->bancos->get_bancos();
            $data['tipo_movimientos'] = $this->bancos->get_tipo_movimientos();
            $this->layout->template('member')->show('bancos/movimientos/nuevo', $data);
        }

        public function crear_tipo_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $this->load->library('form_validation');

            if ($this->form_validation->run('crear_tipo_movimiento')) {
                $res=$this->bancos->crear_tipo_movimiento();
                if($res){
                    $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Tipo de movimiento creado correctamente'));
                }else{
                    $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Tipo de movimiento ya está creado'));
                }                    
                
            }else{
                $this->session->set_flashdata('error', custom_lang('sima_client_created_message', 'Error al crear tipo de movimiento'));
                
            }

            redirect('bancos/nuevo_movimiento');
            
        }

        public function editar_movimiento($id){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["movimiento"] = $this->bancos->get_movimiento($id);
            if($data["movimiento"] != NULL) {
                $data['bancos'] = $this->bancos->get_bancos();
                $data['tipo_movimientos'] = $this->bancos->get_tipo_movimientos();
                $this->layout->template('member')->show('bancos/movimientos/editar',$data);
            }else{
                redirect(site_url('bancos/movimientos'));
            }
        }


        public function actualizar_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');

            $this->load->library('form_validation');

            if ($this->form_validation->run('crear_movimiento')) {
                $this->bancos->editar_movimiento($this->input->post("id_movimiento"));
                $this->session->set_flashdata('message', custom_lang('sima_client_created_message', 'Movimiento editado correctamente'));
                redirect('bancos/movimientos');
            }else{
                $this->bancos->editar_movimiento($this->input->post("id_movimiento"));
                $this->session->set_flashdata('error', custom_lang('sima_client_created_message', 'Error al modificar movimiento'));
                redirect('bancos/movimientos');
            }
        }

        public function eliminar_movimiento(){
            if (!$this->ion_auth->logged_in()) redirect('auth', 'refresh');
            $data["message"] = $this->bancos->eliminar_movimiento();
            echo json_encode($data);
        }
        
        public function conciliar_movimientos(){
            if($this->bancos->conciliar_movimientos()){
                $response = array(
                    'response' => 'success'
                );
                echo json_encode($response);
            }
        }

        public function imprimir_movimiento($id){
            $this->load->model("miempresa_model", 'miempresa');
            $this->miempresa->initialize($this->dbConnection);

            $data["movimiento"] = $this->bancos->get_movimiento($id)[0];
            $empresa = $this->miempresa->get_data_empresa();

            require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';
            require_once APPPATH . 'libraries/numerosALetras.class.php';
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage('P', "LETTER");

            $dire = $empresa["data"]['direccion'];
            $telef = $empresa["data"]['telefono'];
            $email = $empresa["data"]['email'];
            $cafac = $empresa["data"]['cabecera_factura'];
            $nit = $empresa["data"]['nit'];
            $resol = $empresa["data"]['resolucion'];
            $tele = $empresa["data"]['telefono'];
            $dire = $empresa["data"]['direccion'];
            $web = $empresa["data"]['web'];
            $moneda = strtoupper($empresa["data"]['moneda'] !== '' ? $empresa["data"]['moneda'] : 'PESOS M/CTE');
            $simbolo = $empresa["data"]['simbolo'];
            $img = base_url("uploads/{$empresa['data']['logotipo']}");

            //Datos movimiento
            $fecha_creacion = $data["movimiento"]->fecha_creacion;
            $id_movimiento = $data["movimiento"]->id;
            $nota_impresion = $data["movimiento"]->nota_impresion;
            $observacion = $data["movimiento"]->observacion;

            $html = <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="27%"  align="center" style=" font-size: 11px"><br><br>
               
              Fecha creación  $fecha_creacion <br>
                            </td>

                            <td width="19%"  align="left"><br><br>
							<img src="$img" alt="test alt attribute" width="88" border="0" />
       
                            </td>

                            <td style="border-left: 1px solid #000000; font-size:11px" width="39%" align="left"><B><br><br>
                  COMPROBANTE DE MOVIMIENTO BANCARIO<br>
				&nbsp; NO. $id_movimiento </B>

                           </td>
						</tr>
				</table>		   					   
EOF;

        $html .= <<<EOF
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                        <tr>
                            <td width="38%"  align="left" style=" font-size: 11px">
                                almacen
                            </td>						
                            <td width="17%"  align="left" style=" font-size: 11px">
            Fecha: $fecha_inicial 
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
            //$this->layout->template('ajax')->show('bancos/movimientos/imprimir', array('data' => $data));
        }
        
        /******************************************************/
        /******************** conciliaciones ******************/
        /******************************************************/
        public function get_movimientos_conciliacion(){
            $id_conciliacion = $this->input->post('id_conciliacion');
            $data['response'] = $this->bancos->get_movimientos_conciliacion($id_conciliacion);
            echo json_encode($data);
        }

        public function get_data_banco_conciliacion(){
            $id_banco = $this->input->post("id_banco");
            $data = $this->bancos->get_data_banco_conciliacion($id_banco);
            echo json_encode($data);
        }

        public function guardar_conciliacion(){
            $data["response"] = $this->bancos->guardar_conciliacion();
            echo json_encode($data);
        }
        
        public function validateNombreyCodigo() { 
            $result = $this->bancos->validateNombreyCodigo($this->input->post('id'),$this->input->post('campo'));
            echo $result;
        }
    } 
?>
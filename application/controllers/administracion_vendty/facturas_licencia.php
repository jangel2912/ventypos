<?php 

class Facturas_licencia extends CI_Controller
{
	var $dbConnection;
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('crm_model');
		$this->load->model('crm_licencia_model');
		$this->load->model('crm_facturas_model');
		$this->load->model('crm_pagos_licencias_model');
		/*
        if (!$this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))) {
                    //var_dump('es del grupo de licencias');die();
              redirect("frontend/index");
        }*/
	}

	function index() {
	
		if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) { 

			$data['facturas']= $this->crm_facturas_model->get_facturas();			
			$this->layout->template('administracion_vendty')->show('administracion_licencia/facturas/index',array('data' => $data));
		} else {
            redirect("frontend/index");
        }
	}

	public function nuevo($aniomes=null) {
	
		if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
		}   

		if ($this->ion_auth->in_group(5)) { 

			
			if (empty($aniomes)) {
				$aniomes='2018-10';
			}
				$pagos=$this->crm_model->pagados_mes($aniomes)["clientes"];
				if (!empty($pagos)) {
					foreach ($pagos as $value) {
						
						if (empty($value->numero_factura)) {					
							//crear factura					
							$facturag=$this->generar_factura_de_licencia_rezagadas($value->idlicencias_empresa,$value->idpagos_licencias); 					
						}
					}
					$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha Verificado exitósamente las facturas de mes: ".$aniomes));
					$this->session->set_flashdata('message_type', custom_lang('success','success'));
				}				
				redirect("administracion_vendty/facturas_licencia");
			
		} else {
            redirect("frontend/index");
        }
	}

	public function generar_url_factura($primary_key,$row) {
		return site_url('administracion_vendty/facturas_licencia/imprimir_factura').'/'.$primary_key;
	}

	function armar_select_licencia($value='',$primary_key = null) {
		
	}

	public function imprimir_factura($id) {		
		$data['factura'] = $this->crm_facturas_model->get_facturas(array('crm_factura_licencia.id_factura_licencia' => $id));
		$data['detalle_factura'] = $this->crm_facturas_model->get_detalle_factura(array('crm_factura_licencia.id_factura_licencia' => $id));
		$data['pagos_factura'] = $this->crm_model->get_pagos_licencia(array('id_factura_licencia' => $id));
		$data['vendty'] = $this->crm_model->get_info_vendty();		
		$html = $this->load->view('administracion_licencia/imprimir_factura_licencia',$data,true);				
		echo $html; 		
	}

	public function pdf_factura($id,$tipo="navegador") {
		
		$factura = $this->crm_facturas_model->get_facturas(array('crm_factura_licencia.id_factura_licencia' => $id));
		$detalle_factura = $this->crm_facturas_model->get_detalle_factura(array('crm_factura_licencia.id_factura_licencia' => $id));
		$pagos_factura = $this->crm_model->get_pagos_licencia(array('id_factura_licencia' => $id));
		$vendty = $this->crm_model->get_info_vendty();
				
		require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
		
		$pdf->SetTitle($factura[0]->numero_factura);

		$pdf->setPrintHeader(false);

		$pdf->setPrintFooter(false);

		$pdf->AddPage('P', "LETTER");   
		/*****guardar pdf***** */

		/***totales*/
		$total_pagos = 0;
		$total_retencion= 0;
		$total_descuento= 0;
		foreach ($pagos_factura as $key => $value) {
			if ($value->estado_pago == 1) {
				$total_pagos+=$value->monto_pago;			
				$total_descuento+=$value->descuento_pago;			
				$total_retencion+=$value->retencion_pago;			
			}			
		}
		
		$html = '	
		<table border="0" cellspacing="1" cellpadding="3" style="font-size:9px; width:  100%;">
			<!--logo y # factura-->
			<tr>
				<th width="70%" class="text-left">
					<div style="width: 50%;">
						<img src="http://pos.vendty.com//public/v2/img/logo_white_bg_zoho.jpg" style="margin-top: 5%;">
					</div>
				</th>
				<th width="30%" >
					<div>
						<h1 style="text-align: right;" ><b>Factura de venta</b></h1>
						<h3 style="text-align: right;"><b>#'.$factura[0]->numero_factura.'</b></h3>
					</div>
				</th>	
			</tr>
			<!-- Detalle factura empresa vendty-->
			<tr>
				<th width="100%">
					<div style="width: 50%;">
						<p><b style="font-size:12px;">'.$vendty->nombre_empresa.'</b>
						<br>
						<b>'.$vendty->tipo_identificacion.': </b>'.$vendty->numero_identificacion.'  <b>Resolución: </b>'.$vendty->resolucion.'
						<br>
						<b>Fecha:</b>'.$vendty->fecha_resolucion.'<b> Rango:</b>'.$vendty->rangoinicio.' hasta '.$vendty->rangofinal.'
						<br>'.$vendty->direccion.'
						<br>'.$vendty->ciudad.'
						<br>'.$vendty->pais.'</p>
					</div>
				</th>			
			</tr>
			<!--detalle factura empresa y fecha -->
			<tr>
				<th width="70%">
					<p>				
						<b>Empresa:</b>'.$detalle_factura[0]->nombre_empresa.'
					<br>
						<b>'.$detalle_factura[0]->tipo_identificacion.': </b>'.$detalle_factura[0]->numero_identificacion.'
					</p>

				</th>
				<th width="30%" style="text-align: right;">
				
					<p>&nbsp;<br>
					<b>Fecha:</b>'; 					
						$date = date_create($factura[0]->fecha_factura);
						$html.= date_format($date, 'd/m/Y').' 
					</p>
					
				</th>	
			</tr> 
		</table>
			<!--Detalles de la factura -->

		<table border="0" cellspacing="1" cellpadding="3" style=" font-size:10px; width:100%; border-collapse: collapse;">
			<tr style="color:#fff; background-color: #505050;">
				<th width="10%">#</th>
				<th width="45%">Artículos y Descripción</th>
				<th width="10%">Cant.</th>
				<th width="10%">Precio</th>
				<th width="15%">Descuento</th>
				<th width="10%">Total</th>
			</tr>';
			foreach ($detalle_factura as $key => $value) {
				$items=$key+1; 
			$html.='<tr class="descripcion">
				<td>'.$items.'</td>
				<td>'.$value->nombre_licencia_orden.'</td>
				<td>'.$value->cantidad_licencia_orden.'</td>			
				<td>'.number_format($value->valor_unitario).'</td>
				<td>'.number_format($total_descuento).'</td>
				<td>'.number_format(($value->valor_unitario * $value->cantidad_licencia_orden - $total_descuento )).'</td>
			</tr>';	
			} 	
		$html.='
			<hr>		
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Subtotal</td>
				<td>'.number_format($factura[0]->total_factura - $factura[0]->total_impuesto_factura-$total_descuento).'</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b>Total</b></td>
				<td>'.number_format($factura[0]->total_factura-$total_descuento).'</td>
			</tr>';
			if ($total_retencion>0) {
				$html.='<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Pago realizado</td>
				<td>'.number_format($total_pagos).'</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Importe retenido</td>
				<td>'.number_format($total_retencion).'</td>
			</tr>';
			}
		$html.='	
		</table>
		<!-- Notas-->
		<div style=" font-size:10px;">
			<p>
				<b>
					<h4>Notas</h4>
					Gracias por su confianza
				</b>
			</p>

		<!-- Términos y condiciones-->	
		
			<h4><b>Términos y condiciones</b></h4>
			<p>'.$vendty->terminos.'</p>			
		

		<!-- Para Pagos-->	';
			if (!empty($vendty->pagos)) {
			$html.='					
			<h4><b>Para Pagos</b></h4>
			<p>'.$vendty->pagos.'</p>';
		}
		$html.='</div>';
		//echo $html;	
		ob_clean();	  
		$pdf->writeHTML($html, true, false, true, false, '');

		if ($tipo=='guardar') {
			$pdf->Output("factura_$id.pdf", 'F'); 
		} else {
			$factu=$factura[0]->numero_factura;
			$pdf->Output("factura_$factu.pdf", 'I'); 
		}	

	}

	public function pdf_factura_electronica($id,$tipo="navegador") {
		
		$factura = $this->crm_facturas_model->get_facturas(array('crm_factura_licencia.id_factura_licencia' => $id));
		$detalle_factura = $this->crm_facturas_model->get_detalle_factura(array('crm_factura_licencia.id_factura_licencia' => $id));
		$pagos_factura = $this->crm_model->get_pagos_licencia(array('id_factura_licencia' => $id));
		$vendty = $this->crm_model->get_info_vendty_factura_electronica();

		$response = get_curl('generateQrVendty/'.$id, $this->session->userdata('token_api'));
		print_r($response);

		$img_base64_encoded = 'data:image/png;base64,'.$response->qr;
		$imageContent = file_get_contents($img_base64_encoded);
		$path = 'imagetemp.png';
		file_put_contents ($path, $imageContent);
				
		require_once APPPATH.'libraries/tcpdf/tcpdf_import.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
		
		$pdf->SetTitle($factura[0]->numero_factura);

		$pdf->setPrintHeader(false);

		$pdf->setPrintFooter(false);

		$pdf->AddPage('P', "LETTER");   
		/*****guardar pdf***** */

		/***totales*/
		$total_pagos = 0;
		$total_retencion= 0;
		$total_descuento= 0;
		foreach ($pagos_factura as $key => $value) {
			if ($value->estado_pago == 1) {
				$total_pagos+=$value->monto_pago;			
				$total_descuento+=$value->descuento_pago;			
				$total_retencion+=$value->retencion_pago;			
			}			
		}
		
		$html = '	
		<table border="0" cellspacing="1" cellpadding="3" style="font-size:9px; width:  100%;">
			<!--logo y # factura-->
			<tr>
				<th width="70%" class="text-left">
					<div style="width: 50%;">
						<img src="http://pos.vendty.com//public/v2/img/logo_white_bg_zoho.jpg" style="margin-top: 5%;">
					</div>
				</th>
				<th width="30%" >
					<div>
						<h1 style="text-align: right;" ><b>Factura de venta</b></h1>
						<h3 style="text-align: right;"><b>#'.$factura[0]->numero_factura.'</b></h3>
					</div>
				</th>	
			</tr>
			<!-- Detalle factura empresa vendty-->
			<tr>
				<th width="100%">
					<div style="width: 50%;">
						<p><b style="font-size:12px;">'.$vendty->nombre_empresa.'</b>
						<br>
						<b>'.$vendty->tipo_identificacion.': </b>'.$vendty->numero_identificacion.'  <b>Resolución: </b>'.$vendty->resolucion.'
						<br>
						<b>Fecha:</b>'.$vendty->fecha_resolucion.'<b> Rango:</b>'.$vendty->rangoinicio.' hasta '.$vendty->rangofinal.'
						<br>'.$vendty->direccion.'
						<br>'.$vendty->ciudad.'
						<br>'.$vendty->pais.'</p>
					</div>
				</th>			
			</tr>
			<!--detalle factura empresa y fecha -->
			<tr>
				<th width="70%">
					<p>				
						<b>Empresa:</b>'.$detalle_factura[0]->nombre_empresa.'
					<br>
						<b>'.$detalle_factura[0]->tipo_identificacion.': </b>'.$detalle_factura[0]->numero_identificacion.'
					</p>

				</th>
				<th width="30%" style="text-align: right;">
				
					<p>&nbsp;<br>
					<b>Fecha:</b>'; 					
						$date = date_create($factura[0]->fecha_factura);
						$html.= date_format($date, 'd/m/Y').' 
					</p>
					
				</th>	
			</tr> 
		</table>
			<!--Detalles de la factura -->

		<table border="0" cellspacing="1" cellpadding="3" style=" font-size:10px; width:100%; border-collapse: collapse;">
			<tr style="color:#fff; background-color: #505050;">
				<th width="10%">#</th>
				<th width="45%">Artículos y Descripción</th>
				<th width="10%">Cant.</th>
				<th width="10%">Precio</th>
				<th width="15%">Descuento</th>
				<th width="10%">Total</th>
			</tr>';
			foreach ($detalle_factura as $key => $value) {
				$items=$key+1; 
			$html.='<tr class="descripcion">
				<td>'.$items.'</td>
				<td>'.$value->nombre_licencia_orden.'</td>
				<td>'.$value->cantidad_licencia_orden.'</td>			
				<td>'.number_format($value->valor_unitario).'</td>
				<td>'.number_format($total_descuento).'</td>
				<td>'.number_format(($value->valor_unitario * $value->cantidad_licencia_orden - $total_descuento )).'</td>
			</tr>';	
			} 	
		$html.='
			<hr>		
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Subtotal</td>
				<td>'.number_format($factura[0]->total_factura - $factura[0]->total_impuesto_factura-$total_descuento).'</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b>Total</b></td>
				<td>'.number_format($factura[0]->total_factura-$total_descuento).'</td>
			</tr>';
			if ($total_retencion>0) {
				$html.='<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Pago realizado</td>
				<td>'.number_format($total_pagos).'</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Importe retenido</td>
				<td>'.number_format($total_retencion).'</td>
			</tr>';
			}
		$html.='
			</table>
			<table border="0" cellspacing="1" cellpadding="3" style="font-size:9px; width:  100%;">
			<!--logo y # factura-->
			<tr>
				<th width="20%" class="text-left">
					<div >
						<img width="80px" height="80px" src="'.$path.'">
					</div>
				</th>
				<th width="80%" >
					<div style="">
						<p style="margin: 0;">
						<b style="">CÓDIGO ÚNICO DE FACTURA ELECTRÓNICA</b>
						<br>
						'.$response->cufe.'
						</p>
					</div>
				</th>	
			</tr>
		</table>
			';

		$html.='
			<!-- Notas-->
			<div style=" font-size:10px;">
				<p>
					<b>
						<h4>Notas</h4>
						Gracias por su confianza
					</b>
				</p>
			<!-- Términos y condiciones-->
			<h4><b>Términos y condiciones</b></h4>
			<p>'.$vendty->terminos.'</p>			
			<!-- Para Pagos-->	';

		if (!empty($vendty->pagos)) {
			$html.='					
			<h4><b>Para Pagos</b></h4>
			<p>'.$vendty->pagos.'</p>';
		}

		$html.='</div>';
		echo "--------------------------";
		echo $html;	
		ob_clean();	  
		$pdf->writeHTML($html, true, false, true, false, '');

		if ($tipo=='guardar') {
			$pdf->Output("factura_$id.pdf", 'F'); 
		} else {
			$factu=$factura[0]->numero_factura;
			$pdf->Output("factura_$factu.pdf", 'I'); 
		}
	}

	public function generar_factura_electronica($id_licencia, $id_pago) {
		$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia));		
		$almacen=$datos_licencia[0]->id_almacen;
		$id_db_config=$datos_licencia[0]->id_db_config;		
		$empresa=$datos_licencia[0]->idempresas_clientes;
		$this->armar_conexion_bd_cliente($id_db_config);
		//busco nombre del Almacen Asociado
		$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);
		$almacenes =$this->almacenes_model->get_almacenes(array('id' => $almacen));
		$nombreAlmacen=$almacenes[0]->nombre;
		$datos_pago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
		$datos_json = json_encode($datos_licencia[0]);		
		//busco datos empresa
		$datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

		if (empty($datos_empresas)) {
			$this->load->model("miempresa_model");
			$this->miempresa_model->initialize($this->dbConnection);
			$datos_empresas =$this->miempresa_model->get_data_empresa();			
			$nombre_empresa=$datos_empresas['data']['nombre'];
			$tipo_identificacion=$datos_empresas['data']['documento'];
			$numero_identificacion=$datos_empresas['data']['nit'];
		} else {
			$nombre_empresa=$datos_empresas->nombre_empresa;
			$tipo_identificacion=$datos_empresas->tipo_identificacion;
			$numero_identificacion=$datos_empresas->numero_identificacion;
		}

		$creado = 12094;
		$monto_pago = 0;
		$monto_pago = $datos_pago[0]->monto_pago;
		$data_factura = array(
			'creado_por' 	=> $creado,
			'fecha_creacion'=> date("Y-m-d h:i:s"),
			'numero_factura'=> $this->crm_facturas_model->get_prefijo_factura().$this->crm_facturas_model->get_numero_factura_electronica(),
			'fecha_factura' => date("Y-m-d"),
			'total_impuesto_factura'=> $datos_licencia[0]->iva_plan,
			'total_factura' => ($monto_pago+$datos_pago[0]->retencion_pago),
			'fecha_vencimiento_factura' => date("Y-m-d"),
			'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes,
			'valor_descuento_factura' => $datos_pago[0]->descuento_pago,
			'retencion_factura' => $datos_pago[0]->retencion_pago,
			'terminos_pago' => '',
			'estado_factura'=> 1,	
			'id_pago'=> $id_pago	
		);
		$id_factura = $this->crm_facturas_model->agregar_factura($data_factura);

		if ($id_factura) {
			$detalle_factura = array(
				'id_factura_licencia' => $id_factura, 
				'nombre_licencia_orden' => $datos_licencia[0]->nombre_plan.' ('.$nombreAlmacen.')',
				'cantidad_licencia_orden' => 1,
				'datos_licencia_json' => $datos_json,
				'valor_unitario' => ($monto_pago+$datos_pago[0]->retencion_pago),
				'nombre_empresa' => $nombre_empresa,
				'tipo_identificacion' =>$tipo_identificacion,
				'numero_identificacion' => $numero_identificacion
			);

			$this->crm_facturas_model->agregar_detalle_factura($detalle_factura);
			$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias' => $id_pago),array('id_factura_licencia' => $id_factura));			
			$dataPago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
			$dataFactura = array(
				"factura" => $data_factura,
				"detalles" => $detalle_factura,
				"empresa" => $datos_empresas,
				"pago" => $dataPago
			);
			$response = post_curl('generateVendty', json_encode($dataFactura));

			if ($response->codigoRespuesta == '01') {
				/*sleep(25);
				$response2 = post_curl('requestVendty', json_encode($response));
				if ($response2->RWS->codigoRespuesta == '01') {
					$documento = $response2->RWS->Documento;
					//var_dump($documento);
					$db_host_prod = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
					$db_username_prod = "vendtyMaster";
					$db_password_prod = "ro_ar_8027*_na";
					$db_prod = mysqli_connect($db_host_prod,$db_username_prod,$db_password_prod, 'vendty2');
					$db_prod->set_charset("utf8");
					$sqlFacturaVendty = "INSERT INTO crm_factura_electronica 
					(`id_factura`,`numeroDocumento`,`codigo`,`descripcion`,`id_transaccion`,`xml_firmado`,`representacion_grafica`,`cufe`) VALUES
					($id_factura,'$documento->numeroDocumento',$documento->codigo,'$documento->descripcion','$documento->idTransaccion','$documento->ubl','$documento->representacionGrafica','$documento->cufe')";

					$result = $db_prod->query($sqlFacturaVendty);
				}
				 Generar pdf */
				//$this->pdf_factura_electronica($id_factura,'guardar');
			}

			return $id_factura;
		}

		return 0;		
	}

	public function regenerar_factura_electronica($id_licencia, $id_pago) {
		if (!is_null($id_licencia) && !is_null($id_pago)) {
			$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia));		
			$almacen=$datos_licencia[0]->id_almacen;
			$id_db_config=$datos_licencia[0]->id_db_config;		
			$empresa=$datos_licencia[0]->idempresas_clientes;
			$this->armar_conexion_bd_cliente($id_db_config);
			//busco nombre del Almacen Asociado
			$this->load->model("almacenes_model");
			$this->almacenes_model->initialize($this->dbConnection);
			$almacenes =$this->almacenes_model->get_almacenes(array('id' => $almacen));
			$nombreAlmacen=$almacenes[0]->nombre;
			$datos_pago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
			$datos_json = json_encode($datos_licencia[0]);		
			//busco datos empresa
			$datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

			if (empty($datos_empresas)) {
				$this->load->model("miempresa_model");
				$this->miempresa_model->initialize($this->dbConnection);
				$datos_empresas =$this->miempresa_model->get_data_empresa();			
				$nombre_empresa=$datos_empresas['data']['nombre'];
				$tipo_identificacion=$datos_empresas['data']['documento'];
				$numero_identificacion=$datos_empresas['data']['nit'];
			} else {
				$nombre_empresa=$datos_empresas->nombre_empresa;
				$tipo_identificacion=$datos_empresas->tipo_identificacion;
				$numero_identificacion=$datos_empresas->numero_identificacion;
			}

			$creado = 12094;
			$monto_pago = 0;
			$monto_pago = $datos_pago[0]->monto_pago;
			$data_factura = array(
				'creado_por' 	=> $creado,
				'fecha_creacion'=> date("Y-m-d h:i:s"),
				'numero_factura'=> $this->crm_facturas_model->get_prefijo_factura().$this->crm_facturas_model->get_numero_factura_electronica(),
				'fecha_factura' => date("Y-m-d"),
				'total_impuesto_factura'=> $datos_licencia[0]->iva_plan,
				'total_factura' => ($monto_pago+$datos_pago[0]->retencion_pago),
				'fecha_vencimiento_factura' => date("Y-m-d"),
				'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes,
				'valor_descuento_factura' => $datos_pago[0]->descuento_pago,
				'retencion_factura' => $datos_pago[0]->retencion_pago,
				'terminos_pago' => '',
				'estado_factura'=> 1,	
				'id_pago'=> $id_pago	
			);
			$id_factura = $this->crm_facturas_model->agregar_factura($data_factura);

			if ($id_factura) {
				$detalle_factura = array(
					'id_factura_licencia' => $id_factura, 
					'nombre_licencia_orden' => $datos_licencia[0]->nombre_plan.' ('.$nombreAlmacen.')',
					'cantidad_licencia_orden' => 1,
					'datos_licencia_json' => $datos_json,
					'valor_unitario' => ($monto_pago+$datos_pago[0]->retencion_pago),
					'nombre_empresa' => $nombre_empresa,
					'tipo_identificacion' =>$tipo_identificacion,
					'numero_identificacion' => $numero_identificacion
				);

				$this->crm_facturas_model->agregar_detalle_factura($detalle_factura);
				$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias' => $id_pago),array('id_factura_licencia' => $id_factura));			
				$dataPago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
				$dataFactura = array(
					"factura" => $data_factura,
					"detalles" => $detalle_factura,
					"empresa" => $datos_empresas,
					"pago" => $dataPago
				);
				
				post_curl('generateVendty', json_encode($dataFactura));

				$this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La factura electronica de la licencia se ha creado correctamente.'));
				$this->session->set_flashdata('message_type', custom_lang('sima_category_created_message', 'success'));
				redirect('administracion_vendty/pagos_factura/ver_pagos/'.$id_licencia);
			}
		}
		
		$this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Error al intentar generar la factura electronica de la licencia.'));
		$this->session->set_flashdata('message_type', custom_lang('sima_category_created_message', 'error'));
		
		if (!is_null($id_licencia)) {
			redirect('administracion_vendty/pagos_factura/ver_pagos/'.$id_licencia);
		} else {
			redirect('administracion_vendty/licencia_empresa/');
		}
	}

	public function generar_factura_de_licencia($id_licencia,$id_pago) {
		$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia));		
		$almacen=$datos_licencia[0]->id_almacen;
		$id_db_config=$datos_licencia[0]->id_db_config;		
		$empresa=$datos_licencia[0]->idempresas_clientes;		
		$this->armar_conexion_bd_cliente($id_db_config);
		//busco nombre del Almacen Asociado
		$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);
		$almacenes =$this->almacenes_model->get_almacenes(array('id' => $almacen));
		$nombreAlmacen=$almacenes[0]->nombre;
		$datos_pago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
		$datos_json = json_encode($datos_licencia[0]);		
		//busco datos empresa
		$datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

		if (empty($datos_empresas)) {
			$this->load->model("miempresa_model");
			$this->miempresa_model->initialize($this->dbConnection);
			$datos_empresas =$this->miempresa_model->get_data_empresa();			
			$nombre_empresa=$datos_empresas['data']['nombre'];
			$tipo_identificacion=$datos_empresas['data']['documento'];
			$numero_identificacion=$datos_empresas['data']['nit'];
		} else {
			$nombre_empresa=$datos_empresas->nombre_empresa;
			$tipo_identificacion=$datos_empresas->tipo_identificacion;
			$numero_identificacion=$datos_empresas->numero_identificacion;
		}
		
		$creado = 12094;
		$monto_pago = 0;

		if ($datos_pago[0]->moneda_pago == 'USD') {
			$monto_pago=$datos_pago[0]->monto_pago_dolares;
		} else {
			$monto_pago=$datos_pago[0]->monto_pago;
		}

		$data_factura = array(
			'creado_por' 	=> $creado,
			'fecha_creacion'=> date("Y-m-d h:i:s"),
			'numero_factura'=> $this->crm_facturas_model->get_numero_factura(),
			'fecha_factura' => date("Y-m-d"),
			'total_impuesto_factura' => $datos_licencia[0]->iva_plan,
			'total_factura' =>($monto_pago+$datos_pago[0]->retencion_pago),
			'fecha_vencimiento_factura' => date("Y-m-d"),
			'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes,
			'valor_descuento_factura' => $datos_pago[0]->descuento_pago,
			'retencion_factura' => $datos_pago[0]->retencion_pago,
			'terminos_pago' => '',
			'estado_factura'=> 1,	
			'id_pago'=> $id_pago	
		);		
		$id_factura = $this->crm_facturas_model->agregar_factura($data_factura);
		
		if ($id_factura) {
			$detalle_factura = array(
				'id_factura_licencia' => $id_factura, 
				'nombre_licencia_orden' => $datos_licencia[0]->nombre_plan.' ('.$nombreAlmacen.')',
				'cantidad_licencia_orden' => 1,
				'datos_licencia_json' => $datos_json,
				'valor_unitario'	 =>($monto_pago+$datos_pago[0]->retencion_pago),
				'nombre_empresa'	 =>$nombre_empresa,
				'tipo_identificacion'	 =>$tipo_identificacion,
				'numero_identificacion'	 =>$numero_identificacion
			);
			
			$this->crm_facturas_model->agregar_detalle_factura($detalle_factura);
			$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias' => $id_pago),array('id_factura_licencia' => $id_factura));			
			$this->pdf_factura($id_factura,'guardar');

			return $id_factura;
		}

		return 0;		
	}		

	public function generar_factura_de_licencia_rezagadas($id_licencia, $id_pago) {
		$datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia));		
		$almacen=$datos_licencia[0]->id_almacen;
		$id_db_config=$datos_licencia[0]->id_db_config;		
		$empresa=$datos_licencia[0]->idempresas_clientes;		
		$this->armar_conexion_bd_cliente($id_db_config);
		//busco nombre del Almacen Asociado
		$this->load->model("almacenes_model");
		$this->almacenes_model->initialize($this->dbConnection);
		$almacenes =$this->almacenes_model->get_almacenes(array('id' => $almacen));
		$nombreAlmacen=$almacenes[0]->nombre;
		$datos_pago = $this->crm_pagos_licencias_model->get_by_id(array('idpagos_licencias' => $id_pago));
		$datos_json = json_encode($datos_licencia[0]);		
		//busco datos empresa
		$datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

		if (empty($datos_empresas)) {
			$this->load->model("miempresa_model");
			$this->miempresa_model->initialize($this->dbConnection);
			$datos_empresas =$this->miempresa_model->get_data_empresa();			
			$nombre_empresa=$datos_empresas['data']['nombre'];
			$tipo_identificacion=$datos_empresas['data']['documento'];
			$numero_identificacion=$datos_empresas['data']['nit'];
		} else {
			$nombre_empresa=$datos_empresas->nombre_empresa;
			$tipo_identificacion=$datos_empresas->tipo_identificacion;
			$numero_identificacion=$datos_empresas->numero_identificacion;
		}
		
		$creado = 12094;
		$data_factura = array(
			'creado_por' => $creado,
			'fecha_creacion' => date("Y-m-d h:i:s"),
			'numero_factura' => $this->crm_facturas_model->get_numero_factura(),
			'fecha_factura' => $datos_pago[0]->fecha_pago,
			'total_impuesto_factura' => $datos_licencia[0]->iva_plan,
			'total_factura' => ($datos_pago[0]->monto_pago+$datos_pago[0]->retencion_pago),
			'fecha_vencimiento_factura' => $datos_pago[0]->fecha_pago,
			'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes,
			'valor_descuento_factura' => $datos_pago[0]->descuento_pago,
			'retencion_factura' => $datos_pago[0]->retencion_pago,
			'terminos_pago' => '',
			'estado_factura' => 1,	
			'id_pago' => $id_pago	
		);
		$id_factura = $this->crm_facturas_model->agregar_factura($data_factura);
		
		if ($id_factura) {
			$detalle_factura = array(
				'id_factura_licencia' => $id_factura, 
				'nombre_licencia_orden' => $datos_licencia[0]->nombre_plan.' ('.$nombreAlmacen.')',
				'cantidad_licencia_orden' => 1,
				'datos_licencia_json' => $datos_json,
				'valor_unitario' => ($datos_pago[0]->monto_pago+$datos_pago[0]->retencion_pago),
				'nombre_empresa' => $nombre_empresa,
				'tipo_identificacion' => $tipo_identificacion,
				'numero_identificacion' => $numero_identificacion
			);
			
			$this->crm_facturas_model->agregar_detalle_factura($detalle_factura);
			$this->crm_pagos_licencias_model->update_pago_factura(array('idpagos_licencias' => $id_pago),array('id_factura_licencia' => $id_factura));			
			$this->pdf_factura($id_factura,'guardar');			

			return $id_factura;
		}

		return 0;		
	}

	private function armar_conexion_bd_cliente($id_db_config) {
		$this->db->where(array('id' => $id_db_config));
		$datos_db_config = $this->db->get('db_config')->row();
		$usuario = $datos_db_config->usuario;
        $clave = $datos_db_config->clave;
        $servidor = $datos_db_config->servidor;
        $base_dato = $datos_db_config->base_dato;
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
	}

	public function anular_factura($id) {
		$factura = $this->crm_facturas_model->anular_factura(array('id_factura_licencia' => $id),array('estado'=>1));

		if ($factura == 1) {
			$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha anulado la factura exitosamente"));
			$this->session->set_flashdata('message_type', custom_lang('success','success'));
		} else {
			$this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "No se pudo anular la factura"));
			$this->session->set_flashdata('message_type', custom_lang('error','error'));
		}					
		redirect("administracion_vendty/facturas_licencia");
	}
}
?>	
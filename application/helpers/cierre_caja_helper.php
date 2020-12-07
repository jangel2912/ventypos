<?php 
function generar_html_cierre_caja_tirilla($empresa,$datos_caja,$detalle_movimientos,$hidden_input =false,$es_tirilla = false,$devolver_datos = false, $xproducto = false){
	$ci = &get_instance(); 
    $ci->load->model('crm_imagenes_model');       
	$imagenes=$ci->crm_imagenes_model->imagenes();  	
	//print_r( $datos_caja); die();
	$obtener_movimientos_validos_productos = isset($detalle_movimientos['obtener_movimientos_validos_productos'])? $detalle_movimientos['obtener_movimientos_validos_productos'] : array();
	$obtener_movimientos_validos = $detalle_movimientos['obtener_movimientos_validos'];
	$obtener_impuestos_validos = $detalle_movimientos['obtener_impuestos_validos'];
    $obtener_movimientos_devoluciones = $detalle_movimientos['obtener_movimientos_devoluciones'];
    $obtener_movimientos_devoluciones_pendientes = $detalle_movimientos['obtener_movimientos_devoluciones_pendientes'];
    $obtener_movimientos_anulados = $detalle_movimientos['obtener_movimientos_anulados'];
    $obtener_movimientos_abonos = $detalle_movimientos['obtener_movimientos_abonos'];
    $formas_pago_validas = $detalle_movimientos['formas_pago_validas'];
    $cierres_salidas = $detalle_movimientos['cierres_salidas'];
    $cierre = $detalle_movimientos['cierre'];
    $rangoFacturas = $detalle_movimientos['rangoFacturas'];
	//$fecha =  $datos_caja['fecha']; 
	$fecha_inicio =  $datos_caja['fecha_inicio'];             
	$fecha_fin =  $datos_caja['fecha_fin'];                 
    $hora_apertura = $datos_caja['hora_apertura'];
    $hora_cierre = $datos_caja['hora_cierre'];
    $arqueo = $datos_caja['arqueo'];
    $username = $datos_caja['username'];
    $nombre_caja = $datos_caja['nombre_caja'];
    $almacen = $datos_caja['almacen'];
    $total_cierre =  $datos_caja['total_cierre'];
    $total_egresos = $datos_caja['total_egresos'];
    $total_ingresos =  $datos_caja['total_ingresos'];
    $id = (!empty($datos_caja['consecutivo'])) ? $datos_caja['consecutivo'] : $datos_caja['id'];	
	$resolucion ='';



	if(!$hidden_input){
		$html ='<style>.tamano_letra{font-size:9px;}</style>';
	}else{
		$html='';
	}

	if($empresa["data"]['nombre'] == 'ALMACEN LA TUERCA')
	{
		$resolucion = ' <tr><td align="center"><b>Res DIAN No 10000055307 2015/06/05<br> desde No 1 al 500000 factura POS Vendty.com</b></td> </tr>';
	}

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
		<tr>
			<td align="center"><b>'.$empresa["data"]['nombre'].'</b></td>
		</tr>
		<tr>
			<td align="center"><b> NIT:'.$empresa["data"]['nit'].'</b></td>
		</tr>
		'.$resolucion.'
		<tr>
			<td align="center"><b>Cierre de Caja No. '.$id.'</b></td>
		</tr>   
		<tr>
			<td align="center">Fecha Apertura: <b>'.$fecha_inicio.'</b> &nbsp;&nbsp;&nbsp; Fecha Cierre: <b>'.$fecha_fin.'</b> &nbsp;&nbsp;&nbsp; Hora de Apertura: <b>'.$hora_apertura.'</b> - Hora de Cierre: <b>'.$hora_cierre.'</b></td>
		</tr>   
		<tr>
			<td align="center">Usuario: <b>'.$username.'</b> &nbsp;&nbsp;&nbsp; Caja: <b>'.$nombre_caja.'</b> &nbsp;&nbsp;&nbsp; Almacen: <b>'.$almacen.'</b>  </td>
		</tr>   
		<tr>
			<td align="center">'.$rangoFacturas.'</td>
		</tr>
	</table>';
	if($es_tirilla){
		$colspan = 5;
	}else{
		$colspan = 8;
	}
        //movimientos validos
	if($xproducto){
		$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
			<tr>
				<th colspan="'.$colspan.'" style="border-bottom:1px solid #333"><h3>Productos</h3></th>
			</tr>
			<tr>        
				<th align="left" width="50px"><b>Código</b></th>                         
				<th align="left" width="50px"><b>Ref</b></th>'; 
	}else{
		
		$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
			<tr>
				<th colspan="'.$colspan.'" style="border-bottom:1px solid #333"><h3>Ventas</h3></th>
			</tr>
			<tr>        
				<th align="left" width="50px"><b>Número</b></th>                         
				<th align="left" width="50px"><b>Hora</b></th>'; 
	}
	  
				
	if(!$es_tirilla){
		$html.='<th align="left" width="100px;"><b>Usuario</b></th><th align="left"><b>Forma de pago</b></th><th><b>Base impuesto</b></th>';
	}

	if($xproducto){
		$html.='<th align="center"><b>Cant</b></th>
				<th align="center" colspan=2 ><b>Precio</b></th>			
			</tr>';
	}else{
		$html.='<th align="right"><b>Descuentos</b></th>
			<th align="right"><b>Impuestos</b></th>
			<th align="right"><b>Valor</b></th>
			</tr>';
	}
	$sub_total_movimientos_validos = 0;
	$totales_impuestos = array();
	$subtotal_notas_credito = 0;
	$subtotal_ventas_credito = 0;
	$subtotal_ventas_saldo_a_favor = 0;
	$subtotal_propinas = 0;

	//print_r($obtener_movimientos_anulados);
	//die();



	$facturas = array();
	$total_factura_producto = 0;
	$cantidad_factura_producto = 0;
	if($xproducto){		
		foreach ($obtener_movimientos_validos_productos as $movimientos){ //print_r($movimiento); die();
			foreach ($movimientos as $movimiento){
			$cantidad_factura_producto = $cantidad_factura_producto + $movimiento[8];
			$total_factura_producto = $total_factura_producto + $movimiento[10];

			$html .= '                         
			<tr>
				<td align="center">'.$movimiento[7].' </td>
				<td align="center">'.substr($movimiento[9], 0, 15).' </td>
				<td align="center">'.$movimiento[8].' </td>
				<td align="center" colspan=2 >'.number_format($movimiento[10]).' </td>
			<tr>';			
		}}
		
	}
	
	$facturacodigo="";
		foreach ($obtener_movimientos_validos as $movimiento){	
			
			$repeat = false;
			if( $movimiento["forma_pago"] == "Gift Card"){
								// No sumar al total
			}else{
				if ($movimiento['forma_pago'] == 'Nota credito') {
					$subtotal_notas_credito+=$movimiento['valor'];
				}
				if( $movimiento["forma_pago"] == "Credito"){
					$subtotal_ventas_credito+=$movimiento['valor'];			
				}
				if( $movimiento["forma_pago"] == "Saldo a Favor"){
					$subtotal_ventas_saldo_a_favor+=$movimiento['valor'];			
				}

				$sub_total_movimientos_validos += $movimiento["valor"];
				
			}
			/*if(isset($totales_impuestos[$movimiento['porcentaje_impuesto']])){ 				
				if($facturacodigo!=$movimiento["numero"]){  
					$acumulador_impuesto = $totales_impuestos[$movimiento['porcentaje_impuesto']]['total'] + $movimiento['impuesto'];
					$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']=$acumulador_impuesto;
				}
				
			}else{				
				$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>$movimiento['impuesto']);
			}			
			$facturacodigo=$movimiento["numero"];  */
			
			if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
				for($i=0;$i<count($facturas);$i++)
				{
					if($facturas[$i]['numero_factura'] == $movimiento['numero'])
					{
						$facturas[$i]['numero_factura'] =  $movimiento['numero'];
						$facturas[$i]['valor_total'] += $movimiento["valor"];
						$facturas[$i]['base_productos'] =  $movimiento["base_productos"];
						$facturas[$i]['impuesto'] =  $movimiento["impuesto"];
						$facturas[$i]['propina'] =  $movimiento["propina"];
						$repeat=true;
						break;
					}
				}

				
				if($repeat == false){
					$facturas[] = array(
						"numero_factura" =>  $movimiento['numero'],
						"valor_total" => $movimiento["valor"],
						"base_productos" => $movimiento["base_productos"],
						"impuesto" => $movimiento["impuesto"],
						"propina" => $movimiento["propina"]
					);
				}
				//$subtotal_propinas += $movimiento["valor"] - $movimiento["base_productos"] - $movimiento["impuesto"];
			}
			
			if(!$xproducto){
				$html .= '                         
				<tr>
					<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
					<td align="left">'.$movimiento["hora_movimiento"].' </td>';
				if(!$es_tirilla){
					$html.='<td align="left">'.$movimiento["username"].' </td>
							<td align="left">'.$movimiento["forma_pago"].' </td>
							<td align="left">'.$ci->opciones_model->formatoMonedaMostrar($movimiento["base_productos"]).' </td>';
				}	
				$html.='	
					<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
					<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
					<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
				</tr>
				';   
			} 
			           
		}
	

	//Calculamos las propinas de los movimientos validos
	foreach($facturas as $factura){
		//$subtotal_propinas += $factura["valor_total"] - $factura["base_productos"] - $factura["impuesto"];
		$subtotal_propinas += $factura["propina"];
	}



	foreach ($obtener_movimientos_anulados as $movimiento){
		
		if( $movimiento["forma_pago"] == "Gift Card"){
	                        // No sumar al total
		}else{
			if ($movimiento['forma_pago'] == 'Nota credito') {
				$subtotal_notas_credito+=$movimiento['valor'];
			}
			if( $movimiento["forma_pago"] == "Credito"){
	            //$subtotal_ventas_credito+=$movimiento['valor'];			
			}

			$sub_total_movimientos_validos += $movimiento["valor"];
		}
		
		/*if(isset($totales_impuestos[$movimiento['porcentaje_impuesto']])){
			//$acumulador_impuesto = $totales_impuestos[$movimiento['porcentaje_impuesto']]['total'] + $movimiento['impuesto'];
			//$acumulador_impuesto += 0;
			//$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']=$acumulador_impuesto;
			$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']+=0;
		}else{
			$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>0);
		}*/

		/*if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
			$subtotal_propinas += $movimiento["valor"] - $movimiento["base_productos"]-$movimiento["impuesto"];
		}*/
		
		if(!$xproducto){
			$html .= '                         
			<tr>
				<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
				<td align="left">'.$movimiento["hora_movimiento"].' </td>';
			if(!$es_tirilla){
				$html.='<td align="left">'.$movimiento["username"].' </td>
						<td align="left">'.$movimiento["forma_pago"].' </td>
						<td align="left">'.$ci->opciones_model->formatoMonedaMostrar($movimiento["base_productos"]).' </td>';
			}	
			//$valor_anulado = (($movimiento["base_productos"]+ $movimiento["impuesto"]) - ($movimiento["total_descuento"]));
			$valor_anulado = (($movimiento["base_productos"]+ $movimiento["impuesto"]));
			$html.='	
				<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
				<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
				<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
			</tr>
			';    
		}             
	}
	
	if($xproducto){
		$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
		<td colspan=2><b>Total</b></td>
		<td align="center" ><b>'.$cantidad_factura_producto.'</b></td>
		<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_factura_producto).'</td>
		</tr>
		<tr><td colspan="'.$colspan.'"><br></td></tr>';
	}
	else{
		$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
		<td colspan="'.($colspan - 1 ).'"><b>Total</b></td>
		<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_validos).'</td>
		</tr>
		<tr><td colspan="'.$colspan.'"><br></td></tr>';
	}
	$html .= '</table>';

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Devoluciones con Nota Crédito</h3></th>
	</tr>
	<tr>        
		<th align="left" ><b>No. <br>Devolución</b></th>
		<th align="left" ><b>Nota Crédito</b></th>
		<th align="left" ><b>Fecha</b></th>
		<th align="left" ><b>No. Factura Asociada</b></th>
		<th align="left" ><b>Usuario que devolvio</b></th>                            
		<th align="right"><b>Redimida</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_devoluciones_pendientes = 0;
	foreach ($obtener_movimientos_devoluciones_pendientes as $movimiento):
		$sub_total_devoluciones_pendientes += $movimiento["valor"];
		$html .= '                         
			<tr>
				<td align="left">'.$movimiento["devolucion"].' </td>
				<td align="left">'.$movimiento["consecutivo"].' </td>
				<td align="left">'.$movimiento["fecha"].' </td>
				<td align="left">'.$movimiento["facturaDevuelta"].' </td>
				<td align="left">'.$movimiento["usernameDevuelta"].' </td>
				<td align="left">'.$movimiento["redimida"].' </td>
				<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
				
			</tr>';              
	endforeach;

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="5"><b>Total</b></td>
	<td></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_devoluciones_pendientes).'</td>	
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';


	//devoluciones
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333" ><h3>Pagos con Nota Crédito</h3></th>
	</tr>
	<tr>        
		<th align="left" width="55px"><b>No. <br>Devolución</b></th>                         
		<th align="left" width="60px"><b>Fecha</b></th>
		<th align="left"><b>No. Factura con Devuelta</b></th>
		<th align="left" width="100px"><b>Usuario que devolvio</b></th>                            
		<th align="right"><b>No. Factura Redimida</b></th>
		<th align="right" width="100px"><b>Usuario que redimio</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_devoluciones = 0;
	foreach ($obtener_movimientos_devoluciones as $movimiento){
		$sub_total_devoluciones += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["devolucion"].' </td>
			<td align="left">'.$movimiento["fecha"].' </td>
			<td align="left">'.$movimiento["facturaDevuelta"].' </td>
			<td align="left">'.$movimiento["usernameDevuelta"].' </td>
			<td align="left">'.$movimiento["facturaRedimida"].' </td>
			<td align="left">'.$movimiento["usernameRedimida"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>';                 
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_devoluciones).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';

	//$sub_total_devoluciones += $sub_total_devoluciones_pendientes;

        //anuladas
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Anulados</h3></th>
	</tr>
	<tr>        
		<th align="left" width="50px"><b>Número</b></th>                         
		<th align="left" width="50px"><b>Hora</b></th>   
		<th align="left" width="140px;"><b>Usuario</b></th>                            
		<th align="left"><b>Forma de pago</b></th>
		<th align="right"><b>Descuentos</b></th>
		<th align="right"><b>Impuestos</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_movimientos_anulados = 0;
	$facturas_anuladas = array();
	

	foreach ($obtener_movimientos_anulados as $movimiento){
		$repeat = false;
		
		for($i=0;$i<count($facturas_anuladas);$i++)
		{
			if($facturas_anuladas[$i]['numero_factura'] == $movimiento['numero'])
			{
				$facturas_anuladas[$i]["valor_total"] += $movimiento['valor'];
				$repeat=true;
				break;
			}
		}

		if($repeat == false){
			$facturas_anuladas[] = array(
				"numero_factura" =>  $movimiento['numero'],
				"base_productos" => $movimiento['base_productos'],
				"impuesto" => $movimiento['impuesto'],
				"valor_total" => $movimiento['valor']
			);
		}

		//$sub_total_movimientos_anulados += ($movimiento["valor"]);
		//$sub_total_movimientos_anulados += (($movimiento["base_productos"]+ $movimiento["impuesto"]) - ($movimiento["total_descuento"]));
		//$sub_total_movimientos_anulados += (($movimiento["base_productos"]+ $movimiento["impuesto"]));
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<!--<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>-->
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';
	}
	
	//Calculamos el subtotal de las facturas anuladas
	foreach($facturas_anuladas as $factura){
		$sub_total_movimientos_anulados += (($factura["valor_total"]));
		//$subtotal_propinas += $factura["valor_total"] - $factura["base_productos"] - $factura["impuesto"];
	}

	
	//print_r($sub_total_movimientos_anulados);
	//die();

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_anulados).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';

	        //creditos y plan separe
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Abonos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="50px"><b>Número</b></th>                         
		<th align="left" width="50px"><b>Hora</b></th>   
		<th align="left" width="140px;"><b>Usuario</b></th>                            
		<th align="left"><b>Forma de pago</b></th>
		<th align="right"><b>Descuentos</b></th>
		<th align="right"><b>Impuestos</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	<tr><td align="left" colspan="7"><b>Créditos</b></td></tr>';
	$subtotal_creditos_abonos = 0;
	foreach ($obtener_movimientos_abonos['creditos'] as $movimiento){
		$subtotal_creditos_abonos += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>';
	}
	$html .= '<tr><td align="left" colspan="7"><b>Plan separe</b></td></tr>';
	foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento){
		$subtotal_creditos_abonos += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_creditos_abonos).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      FORMAS DE PAGO
            //===============================================

        //formas de pago
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Formas de pago</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b>Cantidad</b></th>                           
		<th align="left" width="290px;"><b>Forma de pago</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	';
	$subtotal_formas_pago_validas = 0;
	foreach ($formas_pago_validas as $forma_pago){
		$simbolo_operacion ='(+) ';   
		if( $forma_pago["forma_pago"] == "Gift Card"){                    
	            // no se suma al total si es giftCard
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Saldo a Favor"){
	            // no se suma al total si es con Saldo_a_Favor
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Nota credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else {
			$subtotal_formas_pago_validas += $forma_pago['total_ingresos'];    
		}


		$formpago2=str_replace("_"," ",$forma_pago["forma_pago"]);
		$formpago2=ucfirst($formpago2);          

		$html .= '<tr>
		<td align="left">'.($forma_pago["cantidad_ingresos"]).'</td>                                               
		<td align="left">'.$formpago2.'</td>
		<td align="right">'.$empresa['data']['simbolo'].' '.$simbolo_operacion.$ci->opciones_model->formatoMonedaMostrar($forma_pago['total_ingresos']).'</td>   
		</tr> '; 

	}
	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="2"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_formas_pago_validas).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      GASTOS 
            //===============================================

        //gastos
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Gastos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Gasto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';


	$total_gastos = 0;
	$total_gastos_pago_proveedores = 0;

	foreach ($cierres_salidas['pago_gastos_by_tipo'] as $val){
		$total_gastos += $val->total;

	                // si son gastos que no descuentan a caja añadimos parentesis
		$parentesisI = "";
		$parentesisF = "";
		if( $val->tipo_cuenta == "Tarjeta crédito" || $val->tipo_cuenta == "Banco"){
			$parentesisI = "( ";
			$parentesisF = " )";                    
		}                

		$html .= '<tr>
		<td align="left"></td>                                               
		<td align="left">Gastos por '.$val->tipo_cuenta.'</td>
		<td align="right">
			'.$parentesisI.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($val->total).$parentesisF.'
		</td>   
		</tr>';
	}

	foreach ($cierres_salidas['pago_proveedores'] as $value1){

		$total_gastos += $value1->total;
		$total_gastos_pago_proveedores += $value1->total;
		$html .= '<tr>
		<td align="left">
		</td>                                               
		<td align="left">Total de pagos a proveedores</td>
		<td align="right">'.
			$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value1->total).'
		</td>   
	</tr>';  
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td></td>
	<td><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      GASTOS CANCELADOS
            //===============================================

	$gastosCanceladoDentroCierre = $cierres_salidas['gastos_cancelados'][0]->dentro;
	$gastosCanceladoFueraCierre = $cierres_salidas['gastos_cancelados'][0]->fuera;


	if( $gastosCanceladoDentroCierre != 0 || $gastosCanceladoFueraCierre !=0 ){

	                //gastos canelados

		$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
		<tr>
			<th colspan="3" style="border-bottom:1px solid #333"><h3>Gastos Anulados</h3></th>
		</tr>
		<tr>        
			<th align="left" width="80px;"><b></b></th>                           
			<th align="left" width="290px;"><b>Gasto</b></th>
			<th align="right"><b>Valor</b></th>
		</tr>
		';


		$html .= '<tr>
		<td align="left">
		</td>                                               
		<td align="left">Gastos Anulados Dentro del Cierre de Caja</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($gastosCanceladoDentroCierre ).'
		</td>   
	</tr>';
	$html .= '<tr>
	<td align="left">
	</td>                                               
	<td align="left">Gastos Anulados fuera del Cierre de Caja</td>
	<td align="right">
		'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($gastosCanceladoFueraCierre ).'
	</td>   
	</tr>';


	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td></td>
	<td><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar( $gastosCanceladoFueraCierre + $gastosCanceladoDentroCierre).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';

	}


        //imuestos
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Impuestos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Porcentaje impuesto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	';   


	foreach ($obtener_impuestos_validos as $key => $un_impuesto) {
		$html.='<tr><td></td>';
		$html.='<td align="left">'.$un_impuesto['porciento'].'%</td>';
		$html.='<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($un_impuesto['impuesto']).'</td>';
		$html.='</tr>';
	}        
	$html.='</table>';        
            //===============================================
            //===============================================
            //
            //                  TOTAL
            //      
            //===============================================
            //===============================================

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Caja</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Concepto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

        // gastos validos y pago proveedores;
	$total_gastos_descuentan_caja = $cierres_salidas['gastos_descuentan_caja'] + $total_gastos_pago_proveedores;

	$total_apertura = 0;

	$total_apertura_sumar_final = 0;

	foreach ($cierre as $value) {

		if ( $value['forma_pago'] == "Saldo a Favor"){
                // no se suma a la apertura el saldo a favor
		}else{
			$total_apertura_sumar_final +=  $value['total_ingresos'];
		}
	  $total_apertura +=  $value['total_ingresos'];
	}       
	//calculos finales
	//$total_cierre = $total_apertura_sumar_final + $sub_total_movimientos_validos + $subtotal_creditos_abonos - $total_gastos_descuentan_caja -$subtotal_notas_credito - $subtotal_ventas_credito;
	$total_cierre = $total_apertura_sumar_final + $sub_total_movimientos_validos + $subtotal_creditos_abonos - $total_gastos_descuentan_caja -$subtotal_notas_credito - $subtotal_ventas_credito - $subtotal_ventas_saldo_a_favor - $sub_total_movimientos_anulados - $subtotal_propinas;

	$html_propina = "";
	if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
		$html_propina = '<tr>
			<td align="left">
			</td>                                               
			<td align="left">(-) Total propinas</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_propinas).'
			</td>   
		</tr>';
	} 

	$html .= '  <tr>
	<td align="left">
	</td>                                               
	<td align="left">(+) Total de apertura</td>
	<td align="right">
		'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_apertura).'
	</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(+) Total de ventas</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_validos).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(+) Total abonos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_creditos_abonos).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de ventas a Crédito</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_ventas_credito).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de ventas con Saldo a Favor</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_ventas_saldo_a_favor).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de pagos con Nota Crédito</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_notas_credito).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de Anulaciones</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_anulados).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total gastos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos_descuentan_caja).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">Total efectivo ingresado por el usuario (Arqueo)</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($arqueo).'
		</td>   
	</tr>
	'.$html_propina.'
	<tr>
		<td align="left">
		</td> 
		<td align="right" colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left"><b>Total cierre</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_cierre  ).'</b>
		</td>   
	</tr>'; 
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Arqueo de caja</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Concepto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';
	$html .= '
	<tr>
	<td align="left">
	
	</td>                                               
	<td align="left"><b>Valor ingresado por cajero</b></td>
	<td align="right">
		<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($arqueo).'</b>
	</td>   
	</tr>
	';
	$valorEfectivo = 0;
	foreach ($formas_pago_validas as $key => $f_p_v){
		if($f_p_v['forma_pago'] == 'Efectivo'){
			$valorEfectivo = $f_p_v['total_ingresos'];
		}
	}

	/*$html .= '
		<tr>
			<td align="left"></td>
			<td align="left">
				Detalle (Ventas en efectivo + Valor de apertura - Total gastos)
			</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja).'
			</td>   
		</tr>
	';*/
	$html .= '
		<tr>
			<td align="left"></td>                                               
			<td align="left">(+) Ventas en efectivo</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo).'
			</td>   
		</tr>
	';
	$html .= '
	<tr>
		<td align="left"></td>                                               
		<td align="left">(+) Valor de apertura</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_apertura).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total gastos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos_descuentan_caja).'
		</td>   
	</tr>
	<tr>
		<td align="left"></td> 
		<td align="right" colspan="2">
			<hr>
		</td>
	</tr>
	
	<!-- Re-calculo del total de operacion -->
	<tr>
		<td align="left"></td>
		<td align="left"><b>Total operación</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja).'</b>
		</td>   
	</tr>

	<!-- Diferencia entre el totalde la operación y el valor ingresado por cajero -->
	<tr>
		<td align="left"></td>
		<td align="left"><b>Diferencia entre el totalde la operación y el valor ingresado por cajero</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja - $arqueo).'</b>
		</td>   
	</tr>
	';
	$html .= '</table>';
	$html .= '</table>'; 
	if($hidden_input){
	    $permisos = $ci->session->userdata('permisos');
		$is_admin = $ci->session->userdata('is_admin');
		$cei=$imagenes['cierre_caja_verde']['original'];
		$regresar=$imagenes['regresar_verde']['original'];
	    $html.=form_open("caja/cerrarCaja", array("id" =>"validate"));
	    $html.='<div class="data-fluid">
	        <div class="row-form">
	                <div class="col-md-12"> <br />'; 
	    if(in_array('1009', $permisos) || $is_admin == 't'){
			/*
	        $html.='<button type="submit" class="btn btn-success">';
	        $html.='<i class="glyphicon glyphicon-lock" aria-hidden="true"></i>&nbsp;Cerrar Caja';
			$html.='</button>';  */
			
			$html.= '<div class="col-md-2 col-md-offset-1"><a data-tooltip="Cerrar Caja" onclick="$(\'#validate\').submit()">';                      
			$html.= "<img alt='cerrar caja' class='btnimagenes' src='".$cei."'";			                                                   
			$html.='</a></div>';                 
		} 
		$url='frontend/index';
		$descripciontool="Regresar al Inicio";

		if(in_array('11', $permisos)){
			$url='ventas/nuevo';
			$descripciontool='Regresar a Ventas';
		}

		if(in_array('10', $permisos)){
			$url='ventas/index';
			$descripciontool='Regresar a Histórico de Ventas';
		}

		if($is_admin == 't'){
			$url='ventas/index';
			$descripciontool='Regresar a Histórico de Ventas';
		}
		$html.='
				<div class="col-md-2">
					<a href="'.site_url("$url").'" data-tooltip="'.$descripciontool.'">
						<img alt="regresar" class="btnimagenes" src="'.$regresar.'">                                                     
					</a>
				</div>';
	    //$html.='<a href="'.site_url('ventas').'" class="btn default">Regresar a lista de cierres</a>';
	    $html.='</div>';
	    $html.='</div>';
		$html.='<input type="hidden" readonly="readonly" value="'.$total_gastos_descuentan_caja.'" placeholder="" name="egresos" />';
		$html.='<input type="hidden" readonly="readonly" value="'.($subtotal_creditos_abonos+ $sub_total_movimientos_validos).'" placeholder="" name="ingresos" />';
		$html.='<input type="hidden" readonly="readonly" value="'.$total_cierre.'" placeholder="" name="total" />';
		$html.='</div></form>';
	}

	if($devolver_datos){
 	  return array('total_cierre'=>$total_cierre,
 	  				'total_ingresos'=>$subtotal_creditos_abonos+ $sub_total_movimientos_validos,
 	  				'total_egresos' => $total_gastos_descuentan_caja,
 	  				'total_apertura' => $total_apertura,
 	  				'total_ventas' => $sub_total_movimientos_validos + $sub_total_movimientos_anulados,
 	  				'total_abonos' => $subtotal_creditos_abonos,
 	  				'total_ventas_credito' => $subtotal_ventas_credito,
 	  				'total_pagos_nota_credito'	=> $subtotal_notas_credito,
 	  				'total_anulaciones' => $sub_total_movimientos_anulados,
 	  				'total_gastos' => $total_gastos_descuentan_caja,
 	  				'total_pagos_proveedores' => $total_gastos_descuentan_caja );

	}
	return $html;
}


function generar_html_cierre_caja($empresa,$datos_caja,$detalle_movimientos,$hidden_input =false,$es_tirilla = false,$devolver_datos = false){
	$ci = &get_instance(); 
    $ci->load->model('crm_imagenes_model');       
	$imagenes=$ci->crm_imagenes_model->imagenes();  		
	$obtener_movimientos_validos = $detalle_movimientos['obtener_movimientos_validos'];
	$obtener_impuestos_validos = $detalle_movimientos['obtener_impuestos_validos'];
    $obtener_movimientos_devoluciones = $detalle_movimientos['obtener_movimientos_devoluciones'];
    $obtener_movimientos_devoluciones_pendientes = $detalle_movimientos['obtener_movimientos_devoluciones_pendientes'];
    $obtener_movimientos_anulados = $detalle_movimientos['obtener_movimientos_anulados'];
    $obtener_movimientos_abonos = $detalle_movimientos['obtener_movimientos_abonos'];
    $formas_pago_validas = $detalle_movimientos['formas_pago_validas'];
    $cierres_salidas = $detalle_movimientos['cierres_salidas'];
    $cierre = $detalle_movimientos['cierre'];
    $rangoFacturas = $detalle_movimientos['rangoFacturas'];
	$fecha_inicio =  $datos_caja['fecha_inicio'];             
	$fecha_fin =  $datos_caja['fecha_fin'];             
    $hora_apertura = $datos_caja['hora_apertura'];
    $hora_cierre = $datos_caja['hora_cierre'];
    $arqueo = $datos_caja['arqueo'];
    $username = $datos_caja['username'];
    $nombre_caja = $datos_caja['nombre_caja'];
    $almacen = $datos_caja['almacen'];
    $total_cierre =  $datos_caja['total_cierre'];
    $total_egresos = $datos_caja['total_egresos'];
    $total_ingresos =  $datos_caja['total_ingresos'];
	$id = (!empty($datos_caja['consecutivo'])) ? $datos_caja['consecutivo'] : $datos_caja['id'];		
	$resolucion ='';

	$total_ventas_efectivo = 0;

	if(!$hidden_input){
		$html ='<style>.tamano_letra{font-size:9px;}</style>';
	}else{
		$html='';
	}

	if($empresa["data"]['nombre'] == 'ALMACEN LA TUERCA')
	{
		$resolucion = ' <tr><td align="center"><b>Res DIAN No 10000055307 2015/06/05<br> desde No 1 al 500000 factura POS Vendty.com</b></td> </tr>';
	}

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
		<tr>
			<td align="left"><b> Fecha de Impresión: '.date("M d, Y G:i").'</b></td>
		</tr>
		<tr>
			<td align="center"><b>'.$empresa["data"]['nombre'].'</b></td>
		</tr>
		<tr>
			<td align="center"><b> NIT:'.$empresa["data"]['nit'].'</b></td>
		</tr>
		'.$resolucion.'
		<tr>
			<td align="center"><b>Comprobante Informe Diario No. '.$id.'</b></td>
		</tr>   
		<tr>
			<td align="center">Fecha Apertura: <b>'.$fecha_inicio.'</b> &nbsp;&nbsp;&nbsp; Fecha Cierre: <b>'.$fecha_fin.'</b> &nbsp;&nbsp;&nbsp; Hora de Apertura: <b>'.$hora_apertura.'</b> - Hora de Cierre: <b>'.$hora_cierre.'</b></td>
		</tr>   
		<tr>
			<td align="center">Usuario: <b>'.$username.'</b> &nbsp;&nbsp;&nbsp; Caja: <b>'.$nombre_caja.'</b> &nbsp;&nbsp;&nbsp; Almacen: <b>'.$almacen.'</b>  </td>
		</tr>   
		<tr>
			<td align="center">'.$rangoFacturas.'</td>
		</tr>
	</table>';
	if($es_tirilla){
		$colspan = 5;
	}else{
		$colspan = 8;
	}
        

	//movimientos validos
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
			<tr>
				<th colspan="'.$colspan.'" style="border-bottom:1px solid #333"><h3>Ventas</h3></th>
			</tr>
			<tr>        
				<th align="left" width="50px"><b>Número</b></th>                         
				<th align="left" width="50px"><b>Hora</b></th>';   
				
	if(!$es_tirilla){
		$html.='<th align="left" width="100px;"><b>Usuario</b></th><th align="left"><b>Forma de pago</b></th><th><b>Base impuesto</b></th>';
	}
	
	$html.='<th align="right"><b>Descuentos</b></th>
			<th align="right"><b>Impuestos</b></th>
			<th align="right"><b>Valor</b></th>
			</tr>';
	$sub_total_movimientos_validos = 0;
	$totales_impuestos = array();
	$subtotal_notas_credito = 0;
	$subtotal_ventas_credito = 0;
	$subtotal_ventas_saldo_a_favor = 0;
	$subtotal_propinas = 0;

	//print_r($obtener_movimientos_anulados);
	//die();



	$facturas = array();
	$facturacodigo="";
	$total_propina = 0;
	foreach ($obtener_movimientos_validos as $movimiento){
		$repeat = false;
		if( $movimiento["forma_pago"] == "Gift Card"){
	                        // No sumar al total
		}else{
			if ($movimiento['forma_pago'] == 'Nota credito') {
				$subtotal_notas_credito+=$movimiento['valor'];
			}
			if( $movimiento["forma_pago"] == "Credito"){
	            $subtotal_ventas_credito+=$movimiento['valor'];			
			}
			if( $movimiento["forma_pago"] == "Saldo a Favor"){
	            $subtotal_ventas_saldo_a_favor+=$movimiento['valor'];			
			}

			$sub_total_movimientos_validos += $movimiento["valor"];
			
		}
		/*if(isset($totales_impuestos[$movimiento['porcentaje_impuesto']])){
			if($facturacodigo!=$movimiento["numero"]){
				$acumulador_impuesto = $totales_impuestos[$movimiento['porcentaje_impuesto']]['total'] + $movimiento['impuesto'];
				$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']=$acumulador_impuesto;
			}
		}else{
			$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>$movimiento['impuesto']);
		}
		$facturacodigo=$movimiento["numero"];		*/
		if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
			for($i=0;$i<count($facturas);$i++)
			{
				if($facturas[$i]['numero_factura'] == $movimiento['numero'])
				{
					$facturas[$i]['numero_factura'] =  $movimiento['numero'];
					$facturas[$i]['valor_total'] += $movimiento["valor"];
					$facturas[$i]['base_productos'] =  $movimiento["base_productos"];
					$facturas[$i]['impuesto'] =  $movimiento["impuesto"];
					$facturas[$i]['propina'] =  $movimiento["propina"];
					$repeat=true;
					break;
				}
			}

			
			if($repeat == false){
				$facturas[] = array(
					"numero_factura" =>  $movimiento['numero'],
					"valor_total" => $movimiento["valor"],
					"base_productos" => $movimiento["base_productos"],
					"impuesto" => $movimiento["impuesto"],
					"propina" => $movimiento["propina"]
				);
			}
			//$subtotal_propinas += $movimiento["valor"] - $movimiento["base_productos"] - $movimiento["impuesto"];
		}
		

		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>';
		if(!$es_tirilla){
			$html.='<td align="left">'.$movimiento["username"].' </td>
					<td align="left">'.$movimiento["forma_pago"].' </td>
					<td align="left">'.$ci->opciones_model->formatoMonedaMostrar($movimiento["base_productos"]).' </td>';
		}
		if($movimiento["forma_pago"] == 'Efectivo'){
			$total_ventas_efectivo += $movimiento["valor"];
		}	
		$html.='	
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';
		
		$total_propina = $total_propina + $movimiento['propina'];
	}

	//Calculamos las propinas de los movimientos validos
	foreach($facturas as $factura){
		//$subtotal_propinas += $factura["valor_total"] - $factura["base_productos"] - $factura["impuesto"];
		$subtotal_propinas += $factura["propina"];
	}



	foreach ($obtener_movimientos_anulados as $movimiento){
		
		if( $movimiento["forma_pago"] == "Gift Card"){
	                        // No sumar al total
		}else{
			if ($movimiento['forma_pago'] == 'Nota credito') {
				$subtotal_notas_credito+=$movimiento['valor'];
			}
			if( $movimiento["forma_pago"] == "Credito"){
	            //$subtotal_ventas_credito+=$movimiento['valor'];			
			}

			$sub_total_movimientos_validos += $movimiento["valor"];
		}
		/*if(isset($totales_impuestos[$movimiento['porcentaje_impuesto']])){
			//$acumulador_impuesto = $totales_impuestos[$movimiento['porcentaje_impuesto']]['total'] + $movimiento['impuesto'];
			//$acumulador_impuesto += 0;
			//$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']=$acumulador_impuesto;
			$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']+=0;
		}else{
			//$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>$movimiento['impuesto']);
			$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>0);
		}*/

		/*if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
			$subtotal_propinas += $movimiento["valor"] - $movimiento["base_productos"]-$movimiento["impuesto"];
		}*/
		

		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>';
		if(!$es_tirilla){
			$html.='<td align="left">'.$movimiento["username"].' </td>
					<td align="left">'.$movimiento["forma_pago"].' </td>
					<td align="left">'.$ci->opciones_model->formatoMonedaMostrar($movimiento["base_productos"]).' </td>';
		}	
		//$valor_anulado = (($movimiento["base_productos"]+ $movimiento["impuesto"]) - ($movimiento["total_descuento"]));
		$valor_anulado = (($movimiento["base_productos"]+ $movimiento["impuesto"]));
		$html.='	
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';                 
	}

	


	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="'.($colspan - 1 ).'"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_validos).'</td>
	</tr>
	<tr><td colspan="'.$colspan.'"><br></td></tr>';
	$html .= '</table>';

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Devoluciones con Nota Crédito</h3></th>
	</tr>
	<tr>        
		<th align="left" ><b>No. <br>Devolución</b></th>
		<th align="left" ><b>Nota Crédito</b></th>
		<th align="left" ><b>Fecha</b></th>
		<th align="left" ><b>No. Factura Asociada</b></th>
		<th align="left" ><b>Usuario que devolvió</b></th>                            
		<th align="left" ><b>Redimida</b></th>                            
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_devoluciones_pendientes = 0;
	foreach ($obtener_movimientos_devoluciones_pendientes as $movimiento):
		$sub_total_devoluciones_pendientes += $movimiento["valor"];
		$html .= '                         
			<tr>
				<td align="left">'.$movimiento["devolucion"].' </td>
				<td align="left">'.$movimiento["consecutivo"].' </td>
				<td align="left">'.$movimiento["fecha"].' </td>
				<td align="left">'.$movimiento["facturaDevuelta"].' </td>
				<td align="left">'.$movimiento["usernameDevuelta"].' </td>
				<td align="left">'.$movimiento["redimida"].' </td>
				<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
			</tr>';              
	endforeach;

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_devoluciones_pendientes).'</td>
	</tr>
	<tr><td colspan="6"><br></td></tr>';
	$html .= '</table>';


	//devoluciones
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333" ><h3>Pagos con Nota Crédito</h3></th>
	</tr>
	<tr>        
		<th align="left" width="55px"><b>No. <br>Devolución</b></th>                         
		<th align="left" width="60px"><b>Fecha</b></th>
		<th align="left"><b>No. Factura con Devuelta</b></th>
		<th align="left" width="100px"><b>Usuario que devolvió</b></th>                            
		<th align="right"><b>No. Factura Redimida</b></th>
		<th align="right" width="100px"><b>Usuario que redimió</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_devoluciones = 0;
	foreach ($obtener_movimientos_devoluciones as $movimiento){
		$sub_total_devoluciones += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["devolucion"].' </td>
			<td align="left">'.$movimiento["fecha"].' </td>
			<td align="left">'.$movimiento["facturaDevuelta"].' </td>
			<td align="left">'.$movimiento["usernameDevuelta"].' </td>
			<td align="left">'.$movimiento["facturaRedimida"].' </td>
			<td align="left">'.$movimiento["usernameRedimida"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>';                 
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_devoluciones).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';

	//$sub_total_devoluciones += $sub_total_devoluciones_pendientes;

        //anuladas
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Anulados</h3></th>
	</tr>
	<tr>        
		<th align="left" width="50px"><b>Número</b></th>                         
		<th align="left" width="50px"><b>Hora</b></th>   
		<th align="left" width="140px;"><b>Usuario</b></th>                            
		<th align="left"><b>Forma de pago</b></th>
		<th align="right"><b>Descuentos</b></th>
		<th align="right"><b>Impuestos</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

	$sub_total_movimientos_anulados = 0;
	$facturas_anuladas = array();
	

	foreach ($obtener_movimientos_anulados as $movimiento){
		$repeat = false;
		
		for($i=0;$i<count($facturas_anuladas);$i++)
		{
			if($facturas_anuladas[$i]['numero_factura'] == $movimiento['numero'])
			{
				$facturas_anuladas[$i]["valor_total"] += $movimiento['valor'];
				$repeat=true;
				break;
			}
		}

		if($repeat == false){
			$facturas_anuladas[] = array(
				"numero_factura" =>  $movimiento['numero'],
				"base_productos" => $movimiento['base_productos'],
				"impuesto" => $movimiento['impuesto'],
				"valor_total" => $movimiento['valor']
			);
		}

		//$sub_total_movimientos_anulados += ($movimiento["valor"]);
		//$sub_total_movimientos_anulados += (($movimiento["base_productos"]+ $movimiento["impuesto"]) - ($movimiento["total_descuento"]));
		//$sub_total_movimientos_anulados += (($movimiento["base_productos"]+ $movimiento["impuesto"]));
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<!--<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>-->
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';
	}
	
	//Calculamos el subtotal de las facturas anuladas
	foreach($facturas_anuladas as $factura){
		$sub_total_movimientos_anulados += (($factura["valor_total"]));
		//$subtotal_propinas += $factura["valor_total"] - $factura["base_productos"] - $factura["impuesto"];
	}

	
	//print_r($sub_total_movimientos_anulados);
	//die();

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_anulados).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';

	        //creditos y plan separe
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="7" style="border-bottom:1px solid #333"><h3>Abonos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="50px"><b>Número</b></th>                         
		<th align="left" width="50px"><b>Hora</b></th>   
		<th align="left" width="140px;"><b>Usuario</b></th>                            
		<th align="left"><b>Forma de pago</b></th>
		<th align="right"><b>Descuentos</b></th>
		<th align="right"><b>Impuestos</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	<tr><td align="left" colspan="7"><b>Créditos</b></td></tr>';
	$subtotal_creditos_abonos = 0;
	foreach ($obtener_movimientos_abonos['creditos'] as $movimiento){
		$subtotal_creditos_abonos += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>';
	}
	$html .= '<tr><td align="left" colspan="7"><b>Plan separe</b></td></tr>';
	foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento){
		$subtotal_creditos_abonos += $movimiento["valor"];
		$html .= '                         
		<tr>
			<td align="left">'.$movimiento["numero"].' '.($movimiento['anulada'] ? '(a)' : '').' </td>
			<td align="left">'.$movimiento["hora_movimiento"].' </td>
			<td align="left">'.$movimiento["username"].' </td>
			<td align="left">'.$movimiento["forma_pago"].' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["total_descuento"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["impuesto"]).' </td>
			<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($movimiento["valor"]).' </td>
		</tr>
		';
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="6"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_creditos_abonos).'</td>
	</tr>
	<tr><td colspan="7"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      FORMAS DE PAGO
            //===============================================

        //formas de pago
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Formas de pago</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b>Cantidad</b></th>                           
		<th align="left" width="290px;"><b>Forma de pago</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	';

	$abonosCredito = array();
	$abonosSepare = array();
	$abonosTotal = array();
	
	$formaAnterior = "";
	foreach ($obtener_movimientos_abonos['creditos'] as $movimiento1){
		
		if($formaAnterior !== $movimiento1["forma_pago"]){

			$data = array(
				"forma" => $movimiento1["forma_pago"],
				"total" => 0
			);
		
			foreach ($obtener_movimientos_abonos['creditos'] as $movimiento2){

				if($movimiento1["forma_pago"] == $movimiento2["forma_pago"]) {
					$data['total'] = $data['total']	+ $movimiento2["valor"];
				}
			}

			array_push($abonosCredito, $data);
		}

		$formaAnterior = $movimiento1["forma_pago"];
	}

	$formaAnterior = "";
	foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento1){
		
		if($formaAnterior !== $movimiento1["forma_pago"]){

			$data = array(
				"forma" => $movimiento1["forma_pago"],
				"total" => 0
			);
		
			foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento2){

				if($movimiento1["forma_pago"] == $movimiento2["forma_pago"]) {
					$data['total'] = $data['total']	+ $movimiento2["valor"];
				}
			}

			array_push($abonosSepare, $data);
		}

		$formaAnterior = $movimiento1["forma_pago"];
	}

	$formaAnterior = "";
	foreach ($abonosCredito as $movimiento1) {
		if ($formaAnterior !== $movimiento1["forma"]) {
			$data = array(
				"forma" => $movimiento1["forma"],
				"total" => $movimiento1["total"]
			);
		
			foreach ($abonosSepare as $movimiento2) {
				if ($movimiento1["forma"] == $movimiento2["forma"]) {
					$data['total'] = $data['total']	+ $movimiento2["total"];
				}
			}

			array_push($abonosTotal, $data);
		}

		$formaAnterior = $movimiento1["forma"];
	}

	$subtotal_formas_pago_validas = 0;
	$encontrados = [];
	foreach ($formas_pago_validas as $forma_pago){
		
		$simbolo_operacion ='(+) ';   
		if( $forma_pago["forma_pago"] == "Gift Card"){                    
	            // no se suma al total si es giftCard
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Saldo a Favor"){
	            // no se suma al total si es con Saldo_a_Favor
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Nota credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else {
			$subtotal_formas_pago_validas += $forma_pago['total_ingresos'];    
		}

		$formpago2=str_replace("_"," ",$forma_pago["forma_pago"]);
		$formpago2=ucfirst($formpago2);
		
		$adicionAbono = 0;
		$cantidadAdicion = 0;

		foreach ($abonosTotal as $abono) {
			if ($forma_pago["forma_pago"] == $abono["forma"]) {
				$adicionAbono = $movimiento1["total"];
				$cantidadAdicion = 0;
				array_push($encontrados, $abono["forma"]);
			}
		}

		$html .= '<tr>
		<td align="left">'.($forma_pago["cantidad_ingresos"] + $cantidadAdicion).'</td>                                               
		<td align="left">'.$formpago2.'</td>
		<td align="right">'.$empresa['data']['simbolo'].' '.$simbolo_operacion.$ci->opciones_model->formatoMonedaMostrar($forma_pago['total_ingresos']).'</td>   
		</tr> ';
        
	}

	$html .= '<tr><td align="left">0</td><td align="left">Cheques</td><td align="right">'.$empresa['data']['simbolo'].' 0</td></tr>';
	$html .= '<tr><td align="left">0</td><td align="left">Bonos</td><td align="right">'.$empresa['data']['simbolo'].' 0</td></tr>';
	$html .= '<tr><td align="left">0</td><td align="left">Vales</td><td align="right">'.$empresa['data']['simbolo'].' 0</td></tr>';
	$html .= '<tr><td align="left">0</td><td align="left">Otros</td><td align="right">'.$empresa['data']['simbolo'].' 0</td></tr>';

	foreach($abonosTotal as $abono) {
		if (!in_array($abono["forma"], $encontrados)) {
			$html .= '<tr>
			<td align="left">1</td>                                               
			<td align="left">'.$abono["forma"].'</td>
			<td align="right">'.$empresa['data']['simbolo'].' (+)'.$ci->opciones_model->formatoMonedaMostrar($abono["total"]).'</td>   
			</tr> ';
		}
	}

	if(count($formas_pago_validas) <= 0) {
        $cantidadAbono = 0;
        $totalAbono = 0;
		$abonosCredito = array();
		$abonosSepare = array();
		$abonosTotal = array();
		
		$formaAnterior = "";
        foreach ($obtener_movimientos_abonos['creditos'] as $movimiento1){
			
			if($formaAnterior !== $movimiento1["forma_pago"]){

				$data = array(
					"forma" => $movimiento1["forma_pago"],
					"total" => 0
				);
			
				foreach ($obtener_movimientos_abonos['creditos'] as $movimiento2){

					if($movimiento1["forma_pago"] == $movimiento2["forma_pago"]) {
						$data['total'] = $data['total']	+ $movimiento2["valor"];
					}
				}
	
				array_push($abonosCredito, $data);
			}

			$formaAnterior = $movimiento1["forma_pago"];
		}

		//print_r($abonosCredito);

		$formaAnterior = "";
        foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento1){
			
			if($formaAnterior !== $movimiento1["forma_pago"]){

				$data = array(
					"forma" => $movimiento1["forma_pago"],
					"total" => 0
				);
			
				foreach ($obtener_movimientos_abonos['plan_separe'] as $movimiento2){

					if($movimiento1["forma_pago"] == $movimiento2["forma_pago"]) {
						$data['total'] = $data['total']	+ $movimiento2["valor"];
					}
				}
	
				array_push($abonosSepare, $data);
			}

			$formaAnterior = $movimiento1["forma_pago"];
		}

		//print_r($abonosSepare);

		$formaAnterior = "";
        foreach ($abonosCredito as $movimiento1){
			
			if($formaAnterior !== $movimiento1["forma"]){

				$data = array(
					"forma" => $movimiento1["forma"],
					"total" => $movimiento1["total"]
				);
			
				foreach ($abonosSepare as $movimiento2){

					if($movimiento1["forma"] == $movimiento2["forma"]) {
						$data['total'] = $data['total']	+ $movimiento2["total"];
					}
				}
	
				array_push($abonosTotal, $data);
			}

			$formaAnterior = $movimiento1["forma"];
		}

		//print_r($abonosTotal);
		
        foreach ($abonosTotal as $movimiento){
            $html .= '<tr>
            <td align="left">1</td>
            <td align="left">'.$movimiento["forma"].'</td>
            <td align="right">'.$empresa['data']['simbolo'].' (+)'.$ci->opciones_model->formatoMonedaMostrar($movimiento["total"]).'</td>
            </tr> ';
		}
	}
	
	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td colspan="2"><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_formas_pago_validas).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      GASTOS 
            //===============================================

        //gastos
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Gastos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Gasto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';


	$total_gastos = 0;
	$total_gastos_pago_proveedores = 0;

	foreach ($cierres_salidas['pago_gastos_by_tipo'] as $val){
		$total_gastos += $val->total;

	                // si son gastos que no descuentan a caja añadimos parentesis
		$parentesisI = "";
		$parentesisF = "";
		if( $val->tipo_cuenta == "Tarjeta crédito" || $val->tipo_cuenta == "Banco"){
			$parentesisI = "( ";
			$parentesisF = " )";                    
		}                

		$html .= '<tr>
		<td align="left"></td>                                               
		<td align="left">Gastos por '.$val->tipo_cuenta.'</td>
		<td align="right">
			'.$parentesisI.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($val->total).$parentesisF.'
		</td>   
		</tr>';
	}

	foreach ($cierres_salidas['pago_proveedores'] as $value1){
		$ordenes=isset($cierres_salidas['ordenes_proveedores'])?$cierres_salidas['ordenes_proveedores']:"";
		$total_gastos += $value1->total;
		$total_gastos_pago_proveedores += $value1->total;
		$html .= '<tr>
		<td align="left">
		</td>                                               
		<td align="left">Total de pagos a proveedores: órdenes # ('.$ordenes.')</td>
		<td align="right">'.
			$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value1->total).'
		</td>   
	</tr>';  
	}
	
	foreach ($cierres_salidas['pago_proveedores_bancos'] as $value1){
		$ordenes=isset($cierres_salidas['ordenes_proveedores'])?$cierres_salidas['ordenes_proveedores']:"";
		$total_gastos += $value1->total;
		$html .= '<tr>
		<td align="left">
		</td>                                               
		<td align="left">Total de pagos a proveedores por bancos</td>
		<td align="right">('.
			$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value1->total).'
		)</td>   
	</tr>';  
	}

	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td></td>
	<td><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';


            //===============================================
            //      GASTOS CANCELADOS
            //===============================================

	$gastosCanceladoDentroCierre = $cierres_salidas['gastos_cancelados'][0]->dentro;
	$gastosCanceladoFueraCierre = $cierres_salidas['gastos_cancelados'][0]->fuera;


	if( $gastosCanceladoDentroCierre != 0 || $gastosCanceladoFueraCierre !=0 ){

	                //gastos canelados

		$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
		<tr>
			<th colspan="3" style="border-bottom:1px solid #333"><h3>Gastos Anulados</h3></th>
		</tr>
		<tr>        
			<th align="left" width="80px;"><b></b></th>                           
			<th align="left" width="290px;"><b>Gasto</b></th>
			<th align="right"><b>Valor</b></th>
		</tr>
		';


		$html .= '<tr>
		<td align="left">
		</td>                                               
		<td align="left">Gastos Anulados Dentro del Cierre de Caja</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($gastosCanceladoDentroCierre ).'
		</td>   
	</tr>';
	$html .= '<tr>
	<td align="left">
	</td>                                               
	<td align="left">Gastos Anulados fuera del Cierre de Caja</td>
	<td align="right">
		'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($gastosCanceladoFueraCierre ).'
	</td>   
	</tr>';


	$html .= '<tr style="background-color: #ccc;" bgcolor="#ccc">
	<td></td>
	<td><b>Total</b></td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar( $gastosCanceladoFueraCierre + $gastosCanceladoDentroCierre).'</td>
	</tr>
	<tr><td colspan="3"><br></td></tr>';
	$html .= '</table>';

	}


        //imuestos
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Impuestos</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Porcentaje impuesto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>
	';   

	foreach ($obtener_impuestos_validos as $key => $un_impuesto) {
		$html.='<tr><td></td>';
		$html.='<td align="left">'.$un_impuesto['porciento'].'%</td>';
		$html.='<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($un_impuesto['impuesto']).'</td>';
		$html.='</tr>';
	}        
	$html.='</table>';        
            //===============================================
            //===============================================
            //
            //                  TOTAL
            //      
            //===============================================
            //===============================================

	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Caja</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Concepto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';

        // gastos validos y pago proveedores;
	$total_gastos_descuentan_caja = $cierres_salidas['gastos_descuentan_caja'] + $total_gastos_pago_proveedores;

	$total_apertura = 0;

	$total_apertura_sumar_final = 0;

	foreach ($cierre as $value) {

		if ( $value['forma_pago'] == "Saldo a Favor"){
                // no se suma a la apertura el saldo a favor
		}else{
			$total_apertura_sumar_final +=  $value['total_ingresos'];
		}
	  $total_apertura +=  $value['total_ingresos'];
	}       
	//calculos finales
	//$total_cierre = $total_apertura_sumar_final + $sub_total_movimientos_validos + $subtotal_creditos_abonos - $total_gastos_descuentan_caja -$subtotal_notas_credito - $subtotal_ventas_credito;
	$total_cierre = $total_apertura_sumar_final + $sub_total_movimientos_validos + $subtotal_creditos_abonos - $total_gastos_descuentan_caja -$subtotal_notas_credito - $subtotal_ventas_credito - $subtotal_ventas_saldo_a_favor - $sub_total_movimientos_anulados - $subtotal_propinas;
    // MARK: Total propina
	$html_propina = "";
	if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
		$html_propina = '<tr>
			<td align="left">
			</td>                                               
			<td align="left">(-) Total propinas</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_propinas).'
			</td>   
		</tr>';
	} 

	$html .= '  <tr>
	<td align="left">
	</td>                                               
	<td align="left">(+) Total de apertura</td>
	<td align="right">
		'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_apertura).'
	</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(+) Total de ventas</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_validos).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(+) Total abonos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_creditos_abonos).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de ventas a crédito</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_ventas_credito).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de ventas con Saldo a Favor</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_ventas_saldo_a_favor).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de pagos con Nota Crédito</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_notas_credito).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total de Anulaciones</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($sub_total_movimientos_anulados).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total gastos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos_descuentan_caja).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">Total efectivo ingresado por el usuario (Arqueo)</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($arqueo).'
		</td>   
	</tr>
	'.$html_propina.'
	<tr>
		<td align="left">
		</td> 
		<td align="right" colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left"><b>Total cierre</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_cierre).'</b>
		</td>   
	</tr>'; 
	$html .= '</table>';
	
	//Jeisson Rodriguez (16/07/2019)
	$html .= '<table border="0" cellspacing="1" cellpadding="3" class="tamano_letra" >
	<tr>
		<th colspan="3" style="border-bottom:1px solid #333"><h3>Arqueo de caja</h3></th>
	</tr>
	<tr>        
		<th align="left" width="80px;"><b></b></th>                           
		<th align="left" width="290px;"><b>Concepto</b></th>
		<th align="right"><b>Valor</b></th>
	</tr>';
	$html .= '
	<tr>
	<td align="left">
	</td>                                               
	<td align="left"><b>Valor ingresado por cajero</b></td>
	<td align="right">
		<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($arqueo).'</b>
	</td>   
	</tr>
	';
	$valorEfectivo = 0;
	foreach ($formas_pago_validas as $key => $f_p_v){
		if($f_p_v['forma_pago'] == 'Efectivo'){
			$valorEfectivo = $f_p_v['total_ingresos'];
		}
	}

	/*$html .= '
		<tr>
			<td align="left"></td>
			<td align="left">
				Detalle (Ventas en efectivo + Valor de apertura - Total gastos)
			</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja).'
			</td>   
		</tr>
	';*/
	$html .= '
		<tr>
			<td align="left"></td>                                               
			<td align="left">(+) Ventas en efectivo</td>
			<td align="right">
				'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo).'
			</td>   
		</tr>
	';
	$html .= '
	<tr>
		<td align="left"></td>                                               
		<td align="left">(+) Valor de apertura</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_apertura).'
		</td>   
	</tr>
	<tr>
		<td align="left">
		</td>                                               
		<td align="left">(-) Total gastos</td>
		<td align="right">
			'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_gastos_descuentan_caja).'
		</td>   
	</tr>
	<tr>
		<td align="left"></td> 
		<td align="right" colspan="2">
			<hr>
		</td>
	</tr>';
	// Ajuste para propina //
	if(isset($detalle_movimientos["tipo_negocio"]) && $detalle_movimientos["tipo_negocio"] == "restaurante"){
	$html .= '<tr>
	<td align="left">
	</td>
	<td>(-) Total propina</td>
	<td align="right">'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal_propinas).'</td>
	</tr>';
	}
	
	$html .='<!-- Re-calculo del total de operacion -->
	<tr>
		<td align="left"></td>
		<td align="left"><b>Total operación</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja - $subtotal_propinas).'</b>
		</td>   
	</tr>
	<!-- Diferencia entre el totalde la operación y el valor ingresado por cajero -->
	<tr>
		<td align="left"></td>
		<td align="left"><b>Diferencia entre el totalde la operación y el valor ingresado por cajero</b></td>
		<td align="right">
			<b>'.$empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valorEfectivo + $total_apertura - $total_gastos_descuentan_caja - $arqueo - $subtotal_propinas).'</b>
		</td>   
	</tr>
	';
	$html .= '</table>';
	
	if($hidden_input){
	    $permisos = $ci->session->userdata('permisos');
		$is_admin = $ci->session->userdata('is_admin');
		$cei=$imagenes['cierre_caja_verde']['original'];
		$regresar=$imagenes['regresar_verde']['original'];
	    $html.=form_open("caja/cerrarCaja", array("id" =>"validate"));
	    $html.='<div class="data-fluid">
	        <div class="row-form">
	                <div class="col-md-12"> <br />'; 
	    if(in_array('1009', $permisos) || $is_admin == 't'){
			/*
	        $html.='<button type="submit" class="btn btn-success">';
	        $html.='<i class="glyphicon glyphicon-lock" aria-hidden="true"></i>&nbsp;Cerrar Caja';
			$html.='</button>';  */
			
			$html.= '<div class="col-md-2 col-md-offset-4"><a data-tooltip="Cerrar Caja" onclick="$(\'#validate\').submit()">';                      
			$html.= "<img alt='cerrar caja' class='btnimagenes' src='".$cei."'";			                                                   
			$html.='</a></div>';                 
		} 
		$url='frontend/index';
		$descripciontool="Regresar al Inicio";

		if(in_array('11', $permisos)){
			$url='ventas/nuevo';
			$descripciontool='Regresar a Ventas';
		}

		if(in_array('10', $permisos)){
			$url='ventas/index';
			$descripciontool='Regresar a Histórico de Ventas';
		}

		if($is_admin == 't'){
			$url='ventas/index';
			$descripciontool='Regresar a Histórico de Ventas';
		}
		
		$html.='
				<div class="col-md-2">
					<a href="'.site_url("$url").'" data-tooltip="'.$descripciontool.'">
						<img alt="regresar" class="btnimagenes" src="'.$regresar.'">                                                     
					</a>
				</div>';
	    //$html.='<a href="'.site_url('ventas').'" class="btn default">Regresar a lista de cierres</a>';
	    $html.='</div>';
	    $html.='</div>';
		$html.='<input type="hidden" readonly="readonly" value="'.$total_gastos_descuentan_caja.'" placeholder="" name="egresos" />';
		$html.='<input type="hidden" readonly="readonly" value="'.($subtotal_creditos_abonos+ $sub_total_movimientos_validos).'" placeholder="" name="ingresos" />';
		$html.='<input type="hidden" readonly="readonly" value="'.$total_cierre.'" placeholder="" name="total" />';
		$html.='</div></form>';
	}

	if($devolver_datos){
 	  return array('total_cierre'=>$total_cierre,
 	  				'total_ingresos'=>$subtotal_creditos_abonos+ $sub_total_movimientos_validos,
 	  				'total_egresos' => $total_gastos_descuentan_caja,
 	  				'total_apertura' => $total_apertura,
 	  				'total_ventas' => $sub_total_movimientos_validos + $sub_total_movimientos_anulados,
 	  				'total_abonos' => $subtotal_creditos_abonos,
 	  				'total_ventas_credito' => $subtotal_ventas_credito,
 	  				'total_pagos_nota_credito'	=> $subtotal_notas_credito,
 	  				'total_anulaciones' => $sub_total_movimientos_anulados,
 	  				'total_gastos' => $total_gastos_descuentan_caja,
 	  				'total_pagos_proveedores' => $total_gastos_descuentan_caja );

	}
	return $html;
}

?>
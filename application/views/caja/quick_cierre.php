<style>
	b{
		font-weight: 600;
	}
</style>
<?php

    $total_cierre =  $datos_caja['total_cierre'];
    $sub_total_movimientos_validos = 0;
    $totales_impuestos = array();
	$subtotal_notas_credito = 0;
	$subtotal_ventas_credito = 0;

    foreach ($detalle_movimientos['obtener_movimientos_validos'] as $movimiento){

		if( $movimiento["forma_pago"] == "Gift Card"){
	                        // No sumar al total
		}else{
			if ($movimiento['forma_pago'] == 'Nota credito') {
				$subtotal_notas_credito+=$movimiento['valor'];
			}
			if( $movimiento["forma_pago"] == "Credito"){
	            $subtotal_ventas_credito+=$movimiento['valor'];			
			}

			$sub_total_movimientos_validos += $movimiento["valor"];
		}
		if(isset($totales_impuestos[$movimiento['porcentaje_impuesto']])){
			$acumulador_impuesto = $totales_impuestos[$movimiento['porcentaje_impuesto']]['total'] + $movimiento['impuesto'];

			$totales_impuestos[$movimiento['porcentaje_impuesto']]['total']=$acumulador_impuesto;
		}else{
			$totales_impuestos[$movimiento['porcentaje_impuesto']] = array('nombre'=>$movimiento['porcentaje_impuesto'],'total'=>$movimiento['impuesto']);
		}
    }
    $subtotal_creditos_abonos = 0;
	foreach ($detalle_movimientos['obtener_movimientos_abonos']['creditos'] as $movimiento){
        $subtotal_creditos_abonos += $movimiento["valor"];
    }

    $subtotal_formas_pago_validas = 0;
    foreach ($detalle_movimientos['formas_pago_validas'] as $forma_pago){
		$simbolo_operacion ='(+) ';   
		if( $forma_pago["forma_pago"] == "Gift Card"){                    
	            // no se suma al total si es giftCard
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else if( $forma_pago["forma_pago"] == "Nota credito"){
	            // no se suma al total si es con credito
			$simbolo_operacion ='(-) ';
		}else {
			$subtotal_formas_pago_validas += $forma_pago['total_ingresos'];    
		}


		$formpago2=str_replace("_"," ",$forma_pago["forma_pago"]);
		$formpago2=ucfirst($formpago2); 
    }

    $sub_total_devoluciones = 0;
	foreach ($detalle_movimientos['obtener_movimientos_devoluciones'] as $movimiento){
		$sub_total_devoluciones += $movimiento["valor"];
    }

     
    $total_gastos = 0;
    $total_gastos_pago_proveedores = 0;
    foreach ($detalle_movimientos['cierres_salidas']['pago_gastos_by_tipo'] as $val){
        $total_gastos += $val->total;
    }
    foreach ($detalle_movimientos['cierres_salidas']['pago_proveedores'] as $value1){
        $total_gastos += $value1->total;
		$total_gastos_pago_proveedores += $value1->total;
    }
    // gastos validos y pago proveedores;
	$total_gastos_descuentan_caja = $detalle_movimientos['cierres_salidas']['gastos_descuentan_caja'] + $total_gastos_pago_proveedores; 
    $total_apertura = 0;
    $total_apertura_sumar_final = 0;

	foreach ($detalle_movimientos['cierre'] as $value) {

		if ( $value['forma_pago'] == "Saldo a Favor"){
                // no se suma a la apertura el saldo a favor
		}else{
			$total_apertura_sumar_final +=  $value['total_ingresos'];
		}
	  $total_apertura +=  $value['total_ingresos'];
	}   
    //calculos finales
	$total_cierre = $total_apertura_sumar_final + $sub_total_movimientos_validos + $subtotal_creditos_abonos - $total_gastos_descuentan_caja -$subtotal_notas_credito - $subtotal_ventas_credito; 
?>
<center>
    <table border="0" cellspacing="1" cellpadding="3" class="tamano_letra">
            <tr>
                <td align="center"><b><?php echo $empresa["data"]['nombre'] ?></b></td>
            </tr>
            <tr>
                <td align="center"><b>Cierre de Caja No. <?php echo $datos_caja['id'] ?></b></td>
            </tr>
            <tr>
			<td align="center">Fecha Apertura: <b><?php echo $datos_caja['fecha_apertura']; ?></b> &nbsp;&nbsp;&nbsp; Fecha Cierre: <b><?php echo $datos_caja['fecha_cierre']; ?></b> </td>
		</tr>   
		<tr>
			<td align="center">Usuario: <b><?php echo $datos_caja['username']; ?></b> &nbsp;&nbsp;&nbsp; Caja: <b><?php echo $datos_caja['nombre_caja']; ?> </b> &nbsp;&nbsp;&nbsp; Almacen: <b><?php echo $datos_caja['almacen']; ?></b>  </td>
		</tr>   
		<tr>
			<td align="center"><?php echo $detalle_movimientos['rangoFacturas']; ?></td>
		</tr>  
    </table>
    <?php 
        $permisos = $this->session->userdata('permisos');
        $is_admin = $this->session->userdata('is_admin');
     
     ?>
	<form action="<?php echo site_url('caja/quickCerrarCaja');?>" method="post" accept-charset="utf-8" id="validate">
	    <div class="data-fluid">
	        <div class="row-form">
				<div class="col-md-12">
					<div class="col-md-4 col-md-offset-4">
						<input type="text" required value="" placeholder="Cantidad de Efectivo al momento de cerrar" name="arqueo" id="arqueo" />
					</div>
				</div>
				
				<div class="col-md-12"> <br />
					<?php
					if(in_array('1035', $permisos) || $is_admin == 't'){
						?>	
						<!--		
						<button type="submit" class="btn btn-success">
						<i class="glyphicon glyphicon-lock" aria-hidden="true"></i>&nbsp;Cerrar Caja
						</button>    -->   		
						<div class="col-md-2 col-md-offset-4">
							<a data-tooltip="Cerrar Caja" id="cerrarcaja">                       
								<img alt="cierre de caja" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['cierre_caja_verde']['original'] ?>">                                                     
							</a>    
						</div>                 
					<?php
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
					?>
					<!--<a href="<?php echo site_url('ventas'); ?>" class="btn default">Regresar a lista de cierres</a>-->
						<div class="col-md-2">
							<a href="<?php echo site_url("$url")?>" data-tooltip="<?= $descripciontool ?>">
								<img alt="regresar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['regresar_verde']['original'] ?>">                                                     
							</a>
						</div>
	    		</div>
			</div>
			<input type="hidden" readonly="readonly" value="<?php echo $total_gastos_descuentan_caja ?>" placeholder="" name="egresos" />
			<input type="hidden" readonly="readonly" value="<?php echo ($subtotal_creditos_abonos+ $sub_total_movimientos_validos) ?>" placeholder="" name="ingresos" />
			<input type="hidden" readonly="readonly" value="<?php echo $total_cierre ?>" placeholder="" name="total" />
		</div>
	</form>
</center>
<script>
	$("#cerrarcaja").click(function(){
		efe=$("#arqueo").val();
		
		if(efe>0){			
			$('#validate').submit();
		}else{
			swal({
				position: 'center',
				type: 'error',
				title: "Error",
				html: "Debe ingresar la cantidad de efectivo que tiene actualmente en caja",
				showConfirmButton: false,
				timer: 1500
			})
		}
		
	});
</script>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<style>
	.logo{				
		margin-top: 10%;
		width: 70%;
	}
	
</style>
</head>
<body>
<?php 
	$total_pagos = 0;
	$total_retencion= 0;
	$total_descuento= 0;
	foreach ($pagos_factura as $key => $value) {
		if($value->estado_pago == 1){
			$total_pagos+=$value->monto_pago;
			$total_descuento+=$value->descuento_pago;			
			$total_retencion+=$value->retencion_pago;		
		}			
	}
	$factura = $factura[0];
?>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<img src="http://pos.vendty.com//public/v2/img/logo_white_bg_zoho.jpg" class="logo">
		</div>
		<div class="col-md-4 col-md-offset-4 text-right">
			<div>
				<h1><b>Factura de venta</b></h1>
				<h3><b>#<?php echo $factura->numero_factura ?></b></h3>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6" style="line-height: 12px;">
			<h3><b><?= $vendty->nombre_empresa ?></b></h3>
			<p class="text-left"><b><?= $vendty->tipo_identificacion ?>: </b><?= $vendty->numero_identificacion ?>  <b>Resolución: </b><?= $vendty->resolucion ?></p>
			<p class="text-left"><b>Fecha:</b><?= $vendty->fecha_resolucion ?> <b>Rango:</b> <?= $vendty->rangoinicio ?> hasta <?= $vendty->rangofinal ?></p>
			<p class="text-left"><?= $vendty->direccion ?></p>
			<p class="text-left"><?= $vendty->ciudad ?></p>
			<p class="text-left"><?= $vendty->pais ?></p>
		</div>		
	</div>
	<div class="row">
		<br><br>
	</div>
	<div class="row">
		<div class="col-md-12">
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">	
			<p>
				<h4><b>Empresa:</b> <?= $detalle_factura[0]->nombre_empresa ?></h4>
				<h4><b><?= $detalle_factura[0]->tipo_identificacion ?>: </b><?= $detalle_factura[0]->numero_identificacion ?></h4>
			</p>		
		</div>
		<div class="col-md-3 col-md-offset-1 text-right">
			<p>
				<h4>&nbsp;</h4>
				<h4><b>Fecha:</b> 
				<?php 				
					$date = date_create($factura->fecha_factura);
					echo date_format($date, 'd/m/Y'); 
				?>
				</h4>
			</p>			
		</div>		
	</div>
	<div class="row">
		<table class="table" width="100%">
			<thead>
				<tr class="active">
					<th width="10%">#</th>
					<th width="55%">Artículos y Descripción</th>
					<th width="10%">Cant.</th>
					<th width="15%">Precio</th>
					<th width="10%">Total</th>
				</tr>
			</thead>
			<tbody class="text-justify">
				<?php foreach ($detalle_factura as $key => $value) { ?>
						<tr>
							<td><?php echo $key ?></td>
							<td><?php echo $value->nombre_licencia_orden ?></td>
							<td><?php echo $value->cantidad_licencia_orden ?></td>
							<td><?php echo number_format($value->valor_unitario) ?></td>
							<td><?php echo number_format(($value->valor_unitario * $value->cantidad_licencia_orden));  ?></td>
						</tr>	
				<?php } ?>							
			</tbody>
		</table>
		<hr>
		<table class="" width="100%">
			<thead>
				<tr class="active">
					<th width="75%"></th>					
					<th width="15%"></th>
					<th width="10%"></th>
				</tr>
			</thead>
			<tbody>			
				<tr>
					<td></td>
					<td><b>Subtotal</b></td>
					<td><?php echo number_format($factura->total_factura - $factura->total_impuesto_factura)  ?></td>
				</tr>
				<tr>
					<td></td>
					<td><b>Total</b></td>
					<td><?php echo number_format($factura->total_factura)  ?></td>
				</tr>
			<?php
			if($total_retencion>0){ ?>
				<tr>
					<td></td>					
					<td>Pago realizado</td>
					<td><?= number_format($total_pagos) ?></td>
				</tr>
				<tr>					
					<td></td>
					<td>Importe retenido</td>
					<td><?= number_format($total_retencion)?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
				
	</div>	
	<div class="row">
		<div class="col-md-12 text-justify">
			<p>
				<b>
					<h4>Notas</h4>
					Gracias por su confianza
				</b>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-justify">
			<h4><b>Términos y condiciones</b></h4>
			<p>
					<?= $vendty->terminos ?>
			</p>			
		</div>
	</div>
	<?php if(!empty($vendty->pagos)) {?>
		<div class="row">
			<div class="col-md-12 text-justify">
				<h4><b>Para Pagos</b></h4>
				<p>
					<?= $vendty->pagos ?>
				</p>			
			</div>
		</div>
	<?php } ?>
</div>	
</body>
</html>
<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/bootstrap/bootstrap.min.css"); ?>" media="screen"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_2.css?v=2"); ?>" media="screen"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/bootstrap/bootstrap.min.css"); ?>" media="print"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_2.css?v=2") ?>"  media="print"/>
	</head>
	<body>
		<div id="contenedor">
			<div class="print-area">
				<div class="ticket_wrapper">

					<div class="row-fluid">
						<div class="span12">
							<p class="center header" style=" line-height: 10pt;">
								<b>Orden de Compra: <?php echo 'Zona :</b> '.$data['nombrezona'].' <b>Mesa</b> : '.$data['nombremesa']  ?>
							</p>
							<hr style="margin: 3pt 0;" />
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<p class="info" style="text-align:center;">
								<b><?php echo 'Productos de la orden' ?> </b> <br>
							</p>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12 info">

							<table width="100%" class="table">
								<?php 
								foreach($data['orden'] as $value):
								?>
								<tr>
									<td colspan="2"><?php echo $value["producto"]['nombre'].' - cantidad : '.$value["cantidad"] ?></td>
								</tr>
								<?php if(isset($value['adicional'])) { 
									foreach($value['adicional'] as $adicional):
								?>
								<tr>
									<td>  </td><td><?php echo $adicional['producto']['nombre'].' - cantidad : '.$adicional["cantidad"]; ?></td>
								</tr>
								<?php endforeach; } ?>
								<?php if(isset($value['modificacion'])) { 
									foreach($value['modificacion'] as $modificacion):
								?>
								<tr>
									<td></td><td><?php echo $modificacion['nombre'] ?></td>
								</tr>
								<?php endforeach; } ?>
								<?php endforeach;  ?>
							</table>
						</div>
					</div>

					<!--div class="row-fluid">
						<div class="span8 offset4 info">
							<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
								<div class="span6" style="">
									Subtotal: 
								</div>
								<div class="span6 right" style="">
									<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($ci->opciones_model->redondear($total_items),2); ?>
								</div>
							</div>
							<?php 

								//$total = $total_items + $timp;

								foreach ($data["venta_impuestos"] as $p) {  
							 		if($p->imp != '') { 
								?>
									<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
										<div class="span6" style="">
											<?php echo $p->imp ?>
										</div>
										<div class="span6 right" style="">
											<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($p->impuestos, 2); ?>
										</div>
									</div>
								<?php  
								    } else {
								?>
									<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
										<div class="span6" style="">
											IVA
										</div>
										<div class="span6 right" style="">
											<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($p->impuestos, 2); ?>
										</div>
									</div>
								  <?php	 
									}
							    }
							?>
							<?php 
								if($sobrecosto > 0) {
			 						$propina_final = ($total_items_propina * $sobrecosto) / 100;
								}

								if($sobrecosto > 0 && $propina_final > 0) {
							?>
								<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
									<div class="span6" style="">
										Propina:
									</div>
									<div class="span6 right" style="">
										<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($propina_final, 2); ?>
									</div>
								</div>
							<?php 
								}
							?>
							<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
								<div class="span6" style="">
									Descuento:
								</div>
								<div class="span6 right" style="">
									<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($descuento, 2); ?>
								</div>
							</div>
							<?php
								foreach ($data["detalle_pago_multiples"] as $p)
								{
									$formpago = str_replace("_"," ",$p->forma_pago);
									if($p->forma_pago == 'efectivo')
									{ 
										$medio_efectivo = true;
										$cambio = $p->cambio;
									}
									?>
									<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
										<div class="span6" style="">
											<?php echo ucfirst($formpago) ?>
										</div>
										<div class="span6 right" style="">
											<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($p->valor_entregado, 2); ?>
										</div>
									</div>
									<?php
								}
							?>
							<?php
								if($medio_efectivo) 
								{
								?>
									<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
										<div class="span6" style="">
											Cambio
										</div>
										<div class="span6 right" style="">
											<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($cambio, 2); ?>
										</div>
									</div>
								<?php
								}
							?>
							<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
								<div class="span6" style="">
									<b>TOTAL:</b>
								</div>
								<div class="span6 right" style="">
									<b><?php echo $data['data_empresa']['data']['simbolo'].''.number_format($ci->opciones_model->redondear($total + $propina_final),2); ?></b>
								</div>
							</div>
						</div>
					</div-->

					<!--div class="row-fluid">
						<div class="span12">
							<p class="center" style="">
								<br />
								COPIA CLIENTE
							</p>
							<hr style="margin: 3pt 0;" />
						</div>
					</div>
					<div class="row-fluid info">
						<div class="span9" style="">
							Con esta compra ahorraste: 
						</div>
						<div class="span3 right" style="">
							<b><?php echo $data['data_empresa']['data']['simbolo'].''.number_format($data["puntos_cliente_factura"], 2); ?></b>
						</div>
						<br />
						<hr style="margin: 3pt 0;" />
					</div-->
					
					<!--div class="row-fluid">
						<div class="span12">
							<p class="info">
								<?php echo $data['data_empresa']["data"]['terminos_condiciones']; ?>
							</p>
						</div>
					</div-->
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	window.print();
</script>
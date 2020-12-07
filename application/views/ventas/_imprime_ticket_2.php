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
								<?php 
									echo $data['data_empresa']['data']['nombre'].'<br>';
									
									if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si')
							            echo $data['data_empresa']['data']['documento'].": ".$data['venta']['nit'].'<br>'; 
							        else if($data['data_empresa']['data']['sistema'] == 'Pos')
							            echo $data['data_empresa']['data']['documento'].": ".$data['data_empresa']['data']['nit'].'<br>'; 
							         
							        if($data['data_empresa']['data']['resolucion'] != '' && $data['data_empresa']['data']['resolucion'] != '0')
							        	echo $data['data_empresa']['data']['resolucion'].'<br>';

							        if($data['venta']['direccion'] != '')
							        	echo $data['venta']['direccion'].'<br>';

							        if($data['venta']['telefono'] != '')
							        	echo 'PBX: '.$data['venta']['telefono'].'<br>';
						        ?>
							</p>
							<hr style="margin: 3pt 0;" />
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<p class="info" style="">
								 <?php echo $data['data_empresa']['data']['titulo_venta'] .": ". $data['venta']['factura'] ?> <br />
								<?php echo $data['venta']['fecha'] ?> <br />
								Atendido por: <?php echo $data['venta']['vendedor'] == '' ? 'general' : $data['venta']['vendedor'] ?>
							</p>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12 info">
							<?php
							 	$total = 0;
							 	$descuento = 0;
					            $timp  = 0;
					            $subtotal = 0;
					            $total_items = 0;
								$total_items_propina = 0;
								$sobrecosto = 0;
								$medio_efectivo = false;
								$cambio = 0;
								$propina_final=0;

								foreach ($data["detalle_venta"] as $p) 
								{
									if ($p["nombre_producto"] == 'PROPINA')
									{
										$sobrecosto = $p['descripcion_producto'];
									}
									else
									{
										if ($data["tipo_factura"] == 'clasico')
										{
											/* SERVICIOS */
											$pv = $p['precio_venta'];
											$desc = $p['descuento'];
											$pvd = $pv - ($pv * ($desc / 100));
                                                                                        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                                                                                        $total_column = $pvd * $p['unidades'];
											$total_items += $total_column;
                                                                                        $valor_total = $pvd * $p['unidades'] + $imp;
                                                                                        $total+= $ci->opciones_model->redondear($valor_total);
                                                                                        $timp+= $imp;
                                                                                        $total_column = $ci->opciones_model->redondear($total_column);
                                                                                        $valor_total = $ci->opciones_model->redondear($valor_total);
                                                                                        $imp = $ci->opciones_model->redondear($imp);
                                                                                        $p['precio_venta'] = $ci->opciones_model->redondear($p['precio_venta']);
										} else {
											/* POS */
											$pv = $p['precio_venta'];
											$desc = $p['descuento'];
											$pvd = $pv - $desc;
                                                                                        //$pvd = round($pvd / 100) * 100;
											$imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                                                                                        $total_column = $pvd * $p['unidades'];
											$total_items += $total_column;
                                                                                        $valor_total = $pvd * $p['unidades'] + $imp;
											$total += $ci->opciones_model->redondear($valor_total);
											$timp += $imp;
                                                                                        $imp = $ci->opciones_model->redondear($imp);
                                                                                        $total_column = $ci->opciones_model->redondear($total_column);
                                                                                        $valor_total = $ci->opciones_model->redondear($valor_total);
                                                                                        $p['precio_venta'] = $ci->opciones_model->redondear($p['precio_venta']);
										}

										$descuento += $desc * $p['unidades'];

										if(trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO'){
					                        $pv_propina = $p['precio_venta'];
					                        $desc_propina = $p['descuento'];
					                        $pvd_propina = $pv_propina - $desc_propina;
					                        $total_column_propina = $pvd_propina * $p['unidades'];
					                        $total_items_propina += $total_column_propina;					   
										}
									?>
										<div class="producto" style=" line-height: 7pt; border-top:1px solid #000; padding-top: 2px;">
											<div class="row-fluid">
												<div class="span1 center" style="">
													<?php echo $p["unidades"] ?>
												</div>
												<div class="span5 pull-left" style="">
													<?php echo $p["nombre_producto"] ?>
												</div>
												<div class="span3 right" style="">
													<?php echo '@'.number_format($pv, 2); ?>
												</div>
												<div class="span3 right" style="">
													<?php echo $data['data_empresa']['data']['simbolo'].''.number_format($valor_total, 2); ?>
												</div>
											</div>
										</div>
									<?php
									}
								}
							?>
						</div>
					</div>

					<div class="row-fluid">
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
					</div>

					<div class="row-fluid">
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
					</div>
					
					<div class="row-fluid">
						<div class="span12">
							<p class="info">
								<?php echo $data['data_empresa']["data"]['terminos_condiciones']; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	window.print();
</script>
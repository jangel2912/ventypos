<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/bootstrap/bootstrap.min.css"); ?>" media="screen"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_2.css"); ?>" media="screen"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/bootstrap/bootstrap.min.css"); ?>" media="print"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_2.css") ?>"  media="print"/>
	</head>
	<body>
		<div id="contenedor">
			<div class="print-area">
				<div class="ticket_wrapper">

					<div class="row-fluid">
						<div class="span12">
							<p class="center header" style="font-size:8pt; line-height: 10pt;">
								<?php 
									echo $data['data_empresa']['data']['nombre'].'<br>';
									echo $data['data_empresa']['data']['documento'].": ".$data['data_empresa']['data']['nit'].'<br>'; 
						        ?>
							</p><br />
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<p class="info" style="font-size: 6pt; margin-top:0pt;">
								Número de documento: <?php echo $data['movimiento']['id'] ?> <br />
								Fecha: <?php echo $data['movimiento']['fecha'] ?> <br />
								Tipo movimiento: <?php 
									switch ($data['movimiento']['tipo_movimiento']) {
										case 'traslado':
											echo 'traslado de mercancias<br>'.
													'Almacen Origen: '.$data['movimiento']['almacen_origen'].'<br>'.
													'Almacen Traslado: '.$data['movimiento']['almacen_traslado'];
										break;
										case 'entrada_compra':
											echo 'traslado de compra<br>'.
													'Proveedor: '.$data['movimiento']['almacen_origen'].'<br>'.
													'No. Factura: '.$data['movimiento']['codigo_factura'];
										break;
										default:
											echo str_replace('_', ' ', $data['movimiento']['tipo_movimiento']);
										break;
									}
								?> <br />
                                                                Nota: <?php echo $data['movimiento']['nota'] ?> <br />
							</p>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12 info">
							<div class="producto" style="font-size: 6pt; line-height: 7pt; border-bottom:1px solid #000; padding-top: 2px;">
								<div class="row-fluid">
									<div class="span3 center" style="font-size: 6pt;">
										Código de barras
									</div>
									<div class="span4 pull-left" style="font-size: 6pt;">
										Nombre
									</div>
									<div class="span1 right" style="font-size: 6pt;">
										Cantidad
									</div>
									<div class="span2 right" style="font-size: 6pt;">
										Precio compra
									</div>
									<div class="span2 right" style="font-size: 6pt;">
										Subtotal
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php 
						$total = 0;
						$total_cantidad = 0;
						foreach ($data['detalle_movimiento'] as $row):
								$total_cantidad+=$row['cantidad'];
							?>
						<div class="row-fluid">
							<div class="span12 info">
								<div class="producto" style="font-size: 6pt; line-height: 7pt; padding-top: 2px;">
									<div class="row-fluid">
										<div class="span3 center" style="font-size: 6pt;">
											<?php echo $row['codigo_barra'];?>
										</div>
										<div class="span4 pull-left" style="font-size: 6pt;">
											<?php echo $row['nombre'];?>
										</div>
										<div class="span1 right" style="font-size: 6pt;">
											<?php echo $row['cantidad'];?>
										</div>
										<div class="span2 right" style="font-size: 6pt;">
											<?php echo $data['data_empresa']['data']['simbolo'].' '.$row['precio_compra'];?>
										</div>
										<div class="span2 right" style="font-size: 6pt;">
											<?php $total += $row['total_inventario']; echo $data['data_empresa']['data']['simbolo'].' '.number_format($row['total_inventario']);?>
										</div>
									</div>
								</div>
							</div>
						</div>
        			<?php endforeach;?>

					<div class="row-fluid">
						<div class="span12 info">
							<br />
							<div class="producto" style="font-size: 6pt; line-height: 7pt; padding-top: 2px">
								<div class="span8 right" style="font-size: 6pt">
									<b>Total productos:</b><?php echo $total_cantidad; ?>
								</div>
								<div class="span4 right" style="font-size: 6pt;">
									<b>TOTAL:</b> <b><?php echo $data['data_empresa']['data']['simbolo'].' '.$total ?></b>
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12 info">
						<br>
						<table width="100%">
        					<tr>
            					<th style="text-align: center;" width="50%">____________________________________<br>Firma de quien despacha</th>
            					<th style="text-align: center;" width="50%">____________________________________<br>Firma de quien recibe</th>
        					</tr>		
    					</table>
					    </div>
				    </div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	window.print();
</script>
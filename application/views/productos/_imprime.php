<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>-->
        <style type="text/css">
        	body, html
        	{
        		padding: 0;
        		margin: 0;
        		width: 226px;
        	}
        	.table
        	{
        		border-collapse: collapse;
        		display: block;
        	}
        </style>
    </head>
    <body style="background-color:#FFFFFF">
	<?php
	/*
	require 'BarcodeGeneratorPNG.php';
	$generator = new BarcodeGeneratorPNG();
	*/
	$columna=1;

	foreach($producto as $value){
	?>
	<table class="table" style="width: 226px; height:100px; margin-bottom:9px; margin-left:30px;" border="0">
		<tr>
			<td style="width: 100%;"  valign="middle"> 
				<table class="table" style="width: 100%;" style="border: 0px dotted #000;" cellpadding="0" cellspacing="0">
					<tr cellpadding="0" cellspacing="0">
						<td colspan="2" style="border: 0px dotted #000;"  style="font-size:12px ;"><?php echo $value['nombre'] ?></td>
					</tr>
					<tr>
						<td colspan="2"  style="font-size:14px ;">
							<table class="table" width="100%">
								<tr>
							<!--	<td width="50%" align="left" style="text-align:left;">   
										<table class="table" width="100%">
											<tr><td style="font-size:10px ;"><?php echo $value['marca_final'] ?>&nbsp;</td></tr>
											<tr><td style="font-size:10px ;"><?php echo strtoupper($value["color_final"]); ?></td></tr>
										</table>
									</td> -->
									<td width="50%" style="font-size: 10px; font-weight:bold; text-align:right;" align="right">$<?php echo number_format($value["precio_venta"]); ?></td>
								</tr> 
							</table>
						</td>
					</tr>
					<tr>
						<td  colspan="2"  style=" height: 0px; ">
							<table class="table">
								<tr>
									<td width="70%" >
										<img height="30px" width="100px"  src="<?= base_url("application/views/ventas/clsCodigoBarras.php?codetype=code128&size=100&text=".$value["codigo"]."") ?>">
									</td>
									<td width="30%" align="center" ><span style="background-color: black; color:#FFFFFF; font-size:20px " ><?php echo  $value["talla"].'&nbsp;&nbsp;&nbsp'; ?></span></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2"  style="font-size:10px ; ">
							<?php echo $value["codigo"]; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php
$columna++;
}
?>
</body>
</html>

<script type="text/javascript">

   window.print();

</script>


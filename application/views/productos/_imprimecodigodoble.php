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
		$cantidad = count($producto);
		$margen="";
		$table = 0;
		for($i = 0; $i < $cantidad; $i+=2){
			$table++;
			$img=base_url("application/views/ventas/clsCodigoBarras.php?codetype=code39&size=100&text=".$producto[$i]['codigo']."");
			$img2=isset($producto[$i+1]) ? base_url("application/views/ventas/clsCodigoBarras.php?codetype=code39&size=100&text=".$producto[$i+1]['codigo']."") :"";
			$margen = 'padding-top:15px;';
			if($table%2 == 0){ 
				//Par
				$margen = 'padding-top:20px;';
			}else{
				$margen = 'padding-top:15px; padding-bottom: 3px;';
			}
			$length1 = (isset($producto[$i]['codigo'])) ? strlen($producto[$i]['codigo']) : '';
			$length2 = (isset($producto[$i+1]['codigo'])) ? strlen($producto[$i+1]['codigo']) : '';
			$pr = '';
			if($length1 <= 12 ){
				$width1 = '210px';
				$pr = 'padding-right: 40px;';
			}else{
				$width1 = '250px';
			}
			if($length2 <= 12 ){
				$width2 = '210px';
			}else if($length2 > 12){
				$width2 = '250px';
			}
			?>
			<table style='width: 480px; margin-left:-6px; <?= $margen ?> padding-top:15px; min-width: 511px'>
				<tr>
					<td width='50%' style='<?php echo $pr;?>'>
						<?php echo isset($producto[$i]) ? "<p style='font-size:12px; text-align:left; margin: 0px; margin-left: 10px;'>".substr($producto[$i]['nombre'],0,25)."</p>" : "<p></p>" ;
						echo isset($producto[$i]) ? "<p style='font-size: 16px; font-weight:bold; margin: 0px; margin-left: 10px;'>$".number_format($producto[$i]['precio_venta'])."</p>" : "<p></p>" ;
						echo isset($producto[$i]) ? "<p style='margin: 0px; text-align:left;margin-left: 1px;'><img height='50px' width='$width1'  src='$img'/>" : "<p>";
						// echo isset($producto[$i]) ? "<span align='center' style='background-color: black; color:#FFFFFF; font-size:40px; position: absolute; margin-top: 5px;' >".$producto[$i]["talla"]."&nbsp;&nbsp;&nbsp</span></p>" : "</p>" ;
						echo isset($producto[$i]) ? "<p style='font-size: 14px; font-weight:bold; text-align:left; margin: 0px; margin-left: 10px;'>".$producto[$i]['codigo']."</p>" : "<p></p>" ;
						?>
					</td>
					<td  width='50%'>
					<?php echo isset($producto[$i+1]) ? "<p style='font-size:12px; text-align:left; margin: 0px; margin-left: 10px;'>".substr($producto[$i+1]['nombre'],0,25)."</p>" : "<p></p>" ;
							echo isset($producto[$i+1]) ? "<p style='font-size: 16px; font-weight:bold; margin: 0px; margin-left: 10px;'>$".number_format($producto[$i+1]['precio_venta'])."</p>" : "<p></p>" ;
							echo isset($producto[$i+1]) ? "<p style='margin: 0px; text-align:left;margin-left: 1px;'><img height='50px' width='$width2'  src='$img2'/>" : "<p>";
							// echo isset($producto[$i+1]) ? "<span align='center' style='background-color: black; color:#FFFFFF; font-size:40px; position: absolute; margin-top: 5px;' >".$producto[$i+1]["talla"]."&nbsp;&nbsp;&nbsp</span></p>" : "</p>" ;
							echo isset($producto[$i+1]) ? "<p style='font-size: 14px; font-weight:bold; text-align:left; margin: 0px; margin-left: 10px;'>".$producto[$i+1]['codigo']."</p>" : "<p></p>" ;
						?>
					</td>		
				</tr>
			</table>
		<?php
		}		
		?>	
	</body>
</html>

<script type="text/javascript">
	window.print();	
</script>


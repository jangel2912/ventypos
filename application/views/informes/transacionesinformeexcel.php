<?php
$filename = "Excel Informe de Transacciones ".date('Y-m-d').".xls";
   header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment;filename=".$filename); //tell browser what's the file name
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: max-age=0");

  $nombre = '';
  
?> 
        <?php  if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table  style="border: inset 1px #000000; font-size:10px" cellspacing="0" cellspacing="0">
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Almacén</th>
							<th style="border: inset 1px #000000; border-bottom: 0px solid red;">Cliente</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Identificación cliente</th>
							<th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Telefono cliente</th>
							<th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Celular cliente</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"># factura</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Detalle producto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Cantidad producto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Fecha factura</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Precio venta x producto</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Precio compra x producto</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Descuento</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Venta Real</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Impuesto</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Subtotal</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Ciudad</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" >Vendedor</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Usuario</th>	
						 </tr>			
				<?php 
				
							function fullUpper($string){ 
  return strtr(strtoupper($string), array( 
      "à" => "À", 
      "è" => "È", 
      "ì" => "Ì", 
      "ò" => "Ò", 
      "ù" => "Ù", 
          "á" => "Á", 
      "é" => "É", 
      "í" => "Í", 
      "ó" => "Ó", 
      "ú" => "Ú", 
          "â" => "Â", 
      "ê" => "Ê", 
      "î" => "Î", 
      "ô" => "Ô", 
      "û" => "Û", 
          "ç" => "Ç", 
    )); 
} 
              $total=0; $subtotal=0;
			foreach($data['total_ventas'] as $value){
			
$cadena = $value['numerofac'];
    $numero = "";
	$prefijo = "";
    
    for( $index = 0; $index < strlen($cadena); $index++ )
    {
        if( is_numeric($cadena[$index]) )
        {
            $numero .= $cadena[$index];
        }
		else{ $prefijo .= $cadena[$index];  }
    }  
      				 ?>	
                       <tr>						
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nombre_almacen']; ?></td>
							<td style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo utf8_decode($value['nombre_cliente']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit']; ?></td>		
							<td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['telefono']; ?></td>		
							<td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['telmovil']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['numerofac']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">
							<?php
							
							$nombre = str_replace(" ", "&nbsp;", $value['nombre_producto']);
							echo utf8_decode($nombre);							
							
							?>
							</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['unidades']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['precio_venta_producto']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['precio_compra']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['descuento']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo ($value['precio_venta_venta'] - $value['descuento']); ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['impuesto']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['subtotal']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo utf8_decode($value['ciudad']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['vendedor']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;" ><?php echo utf8_decode($value['usuario']); ?></td>									
                      </tr>
					<?php 
				   
				   } ?>				   	 
				  </table> 
		 <?php } ?>		


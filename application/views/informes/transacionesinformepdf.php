
        <?php  if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table  style="border: inset 1px #000000; font-size:10px" cellspacing="0" cellspacing="0">
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Almacén</th>
							<th style="border: inset 1px #000000; border-bottom: 0px solid red;">Cliente</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Identificación cliente</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"># factura</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Detalle producto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Cantidad producto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Fecha factura</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Precio por producto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Ciudad</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" >Vendedor</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Usuario</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Iva (valor)</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Total por factura</th>
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
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['numerofac']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo utf8_decode($value['nombre_producto']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['unidades']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['precio_venta']; ?></td>									
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo utf8_decode($value['ciudad']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['vendedor']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;" ><?php echo utf8_decode($value['usuario']); ?></td>	
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['impuesto']; ?></td>	
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['total_precio_venta']; ?></td>
									
                        </tr>
					<?php 
				   
				   } ?>				   	 
				  </table> 
		 <?php } ?>			
<script type="text/javascript">

    window.print();

</script>


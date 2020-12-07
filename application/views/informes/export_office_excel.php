<?php
    $filename = "Excel Ventas WorldOffice ".date('Y-m-d').".xls";
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment;filename=".$filename); //tell browser what's the file name
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: max-age=0");
?> 
        <?php  if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table  style="border: inset 1px #000000; border-bottom: 0px solid red;">
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Empresa</th>
							              <th style="border: inset 1px #000000; border-bottom: 0px solid red;">Tipo de docuemnto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">prefijo</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">DocumentoNúmero</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Fecha</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Terceros_Identificacion "Clientes"</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Vendedor</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Nota encabezado</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Verificado</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" >FormaDePago</th>	
               <!--              <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Clasificación</th>	 -->
   <!--                          <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado1</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado2</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado3</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado4</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado5</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado6</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado7</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado8</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado9</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado10</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado11</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado12</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado13</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado14</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Personalizado15</th> -->			
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">CódigoInventario</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Cantidad</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Unidad de Medida</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Monto Monetario Unitario</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Ipoconsumo</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Valor Ipoconsumo</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Nota Detalle</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Vencimiento</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Dcto</th>	
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Terceros_Identificacion "Clientes"</th>  
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FactorConversiónMovimientoABodega</th>    
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FactorConversiónMovimientoAInventario</th>
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">bodega</th>       
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">iva</th>  
                            <!-- <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">CostoPromedio</th>	 -->
                           <!--  <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Depreciacion</th>	 --><!-- 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Terceros_2_Identificacion</th> -->
         <!--                    <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FactorConversiónMovimientoABodega</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FactorConversiónMovimientoAInventario</th> -->
<!--                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Anulado</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">anulado</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">producto</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">bodega</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">iva</th>	
														 -->
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
	
	        function limpiarCaracteresEspeciales($string ){
             $string = htmlentities($string);
             $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
             return $string;
            }		  
			  
			foreach($data['total_ventas'] as $value){
			
    $cadena = $value['numerofac'];
    $numero = "";
	$prefijo = "";
	
	$formpago = str_replace("_"," ",$value['formapago']);
	 
    
    for( $index = 0; $index < strlen($cadena); $index++ )
    {
        if( is_numeric($cadena[$index]) )
        {
            $numero .= $cadena[$index];
        }
		else{ $prefijo .= $cadena[$index];  }
    }  
	
      
                   if($value['nombre_producto'] != 'PROPINA'){  	
 	
			?>	
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo strtoupper($value['empresa']); ?></th>
							<th style="border: inset 1px #000000; border-bottom: 0px solid red;">FV</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo ""/*$prefijo;*/; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $numero; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit']; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit_vendedor']; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FACTURA DE VENTA</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">0</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" ><?php echo limpiarCaracteresEspeciales($formpago); ?></th>	
  <!--                           <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>	 -->		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['codigo_producto']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['unidades']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Und.</th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['precio_venta']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['impuesto']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo round(($value['precio_venta'] - $value['descuento']) * $value['impuesto'] / 100 * $value['unidades']);?></th>	
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['descuento'] * $value['unidades']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit']; ?></th> 
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">1</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">1</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Principal</th>     
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">0</th>
                           
                           
									
                        </tr>
						
						
							 
				   <?php
				             
                  }
                  else{	
                     if($value['descripcion_producto'] > '1'){  	
				 ?>	
                        <tr>
                              <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo strtoupper($value['empresa']); ?></th>
                            <th style="border: inset 1px #000000; border-bottom: 0px solid red;">FV</th>        
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>      
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $numero; ?></th>       
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></th>      
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit']; ?></th>     
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit_vendedor']; ?></th>        
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FACTURA DE VENTA</th>     
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">0</th>        
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" ><?php echo $formpago; ?></th>  
  <!--                           <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>    
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>  -->        
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">PROPINA</th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['unidades']; ?></th>    
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Und.</th> 
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo round($value['total_precio_venta']); ?></th>   
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>    
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>  
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"></th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></th>  
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['descuento'] * $value['unidades']; ?></th>  
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nit']; ?></th> 
                             <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">1</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">1</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Principal</th>     
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">0</th>
									
                        </tr>
					<?php 
				   }      
					 
					 }
				   
				   } ?>				   	 
				  </table> 
		 <?php } ?>			

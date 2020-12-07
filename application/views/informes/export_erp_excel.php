<?php 
$filename = "VTAS_CONT_".date('Ymd').".csv";
header("Content-type: text/csv");
    header("Content-Disposition: attachment;filename=".$filename); //tell browser what's the file name
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: max-age=0");
	
?> 

        <?php  if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table  style="border: inset 1px #000000; border-bottom: 0px solid red;">
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">UNIDAD DE NEGOCIO</th>
							<th style="border: inset 1px #000000; border-bottom: 0px solid red;">ID CLIENTE</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FECHA FACT</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FACTURA</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FACTURA</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">CONCEPTO FACTURADO</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">VALOR CONCEPTO</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">ID CLIENTE</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">ID CLIENTE</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" >VENDEDOR</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;" >UN EXPLOTACI&Oacute;N</th>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">PRODUCTO</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">ORIGEN DE FACT</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">CIUDAD</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">TIPO DE FACTURA</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">FACTURA</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">PAIS</th>
							
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
			

			?>	
                        <tr>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">P9001</td>
							<td  style="border: inset 1px #000000; border-bottom: 0px solid red;">999999999</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['factura']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['factura']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo fullUpper($value['prod_equivalencia_1']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo round($value['precioventa']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">999999999</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">999999999</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">101</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['alm_equivalencia']; ?></td>
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['prod_equivalencia_2']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['prod_equivalencia_3']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo strtoupper($value['provincia']); ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">FT</td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['factura']; ?></td>		
                            <td  style="border: inset 1px #000000; border-bottom: 0px solid red;">COL</td>
                        </tr>	 
				   <?php } ?>				   	 
				  </table> 
		 <?php } ?>			

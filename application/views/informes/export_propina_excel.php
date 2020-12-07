<?php
$filename = "Excel Ventas con propina ".date('Y-m-d').".xls";
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
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Consecutivo</th>
							<th style="border: inset 1px #000000; border-bottom: 0px solid red;">Fecha</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Nombre del cliente</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Propina</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Impuesto</th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;">Total</th>	
														
                        </tr>			
				<?php 
              $total=0; $subtotal=0;
			foreach($data['total_ventas'] as $value){
			
			?>	
                        <tr>
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['numerofac']; ?></th>
							<th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['fechaventa']; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['nombre_comercia']; ?></th>		
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['propina_final']; ?></th>		
							<th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['impuesto']; ?></th>	
                            <th  style="border: inset 1px #000000; border-bottom: 0px solid red;"><?php echo $value['total_precio_venta']; ?></th>	
									
                        </tr>
						
						
							 
				   <?php
				         
				   } ?>				   	 
				  </table> 
		 <?php } ?>			

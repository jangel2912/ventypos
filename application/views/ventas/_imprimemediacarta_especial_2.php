<!DOCTYPE html>
<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style>

            body{

            /*  	background-image: url("<?php echo base_url("application/views/ventas/fondo2.jpg?con=5");?>");
			*/
				background-repeat:no-repeat;
            
			    font-family: sans-serif;

                font-size:9pt;
            }

            .header{

                 font-size:10pt;

            }

            #contenedor{

    margin-top: 20px;
    margin-bottom: 1px;
    margin-right: 0px;
    margin-left: 30px;

            }


            #print_area{

            border:0px;

          }

          .resolucion{
             font-size:8pt;
          }

            

        </style>

    </head>

    <body>

        <div id="contenedor" >

            <div id="print_area" >

                <div id="ticket_header" >
                  <table height="30px" width=860 style="border: 0px inset #000000;">
                     <tr> 
					   <td  width="30%">&nbsp;</td>				  
                     </tr>           
                  </table>	

                   <table height="30px" width=900 style="border: 0px inset #000000;">
                     <tr> 
					   <td width="40%" style="border: 0px inset #000000;">
			  
                   <table   style="border-left: 0px inset #000000; border-right: 0px inset #000000; border-top: 0px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="90%" style="border-right: 1px inset #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>        
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "".$data['venta']["nif_cif"]?></td>				  
                     </tr>         
                  </table>
				  
                     </td>
					  <td  width="22%" style="border: 0px inset #000000;">&nbsp;&nbsp;&nbsp;
						 <?php echo date("d",strtotime($data['venta']['fecha'])); ?>
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 <?php echo date("m",strtotime($data['venta']['fecha'])); ?>
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						     <?php echo date("Y",strtotime($data['venta']['fecha'])); ?></td>			
						  <td  width="10%" style="border: 0px inset #000000;">&nbsp;&nbsp;&nbsp;
						 <?php echo $data['venta']['factura'] ?></td>				 				  
                     </tr>           
                  </table>		

                   <table height="30px" width=900 style="border: 0px inset #000000;">
                     <tr> 
					   <td width="54%" style="border: 0px inset #000000;">
			  
                   <table   style="border-left: 0px inset #000000; border-right: 0px inset #000000; border-top: 0px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="80%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "".$data['venta']["cliente_telefono"]?></td>        
                      <td></td>				  
                     </tr>         
                  </table>
				  
                     </td>
					  <td  width="22%" style="border: 0px inset #000000;"></td>			
						  <td  width="10%" style="border: 0px inset #000000;"></td>				 				  
                     </tr>           
                  </table>	
				  				  
				  
	                  <table height="5px" width=860 style="border: 0px inset #000000;">
                     <tr> 
					   <td  width="30%">&nbsp;</td>				  
                     </tr>           
                  </table>				  
 					 <?php  
					 
					 $i=0;
					foreach ($data["detalle_venta"] as $p) { 
							
					    if($p['descuento'] > 0){  $i=1;  } 
						
					}					 
					 ?>	

                   <table  width=900 style="border: inset 0px #000000; border-bottom: inset 0px #000000; font-size:13px">
                  <!--  <tr>
                        <th width="60%" style="border-left: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>
						<th width="10%" style="border-left: inset 1px #000000; " align="left"><?php echo "Medida" ?></th>						
                        <th width="10%" style="border-left: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                        <th width="10%" style="border-left: inset 1px #000000; " align="left"><?php echo "Valor Unitario" ?></th>
                        <th width="10%" style="border-left: inset 1px #000000; " align="left"><?php echo "Valor Total" ?></th>
                    </tr> -->		
                    <?php

                        $total = 0;

                        $timp  = 0;

                        $subtotal = 0;

                        $total_items = 0;

                    $group_by_impuesto = array();
                      $counter=NULL;
					  $hasta=NULL;
                    foreach ($data["detalle_venta"] as $p) {
                    $counter++;
                        if($data["tipo_factura"]=='clasico'){
                             /* SERVICIOS */
                            $pv = $p['precio_venta'];

                            $desc = $p['descuento'];

                            $pvd = $pv - ($pv * ($desc/100));

                            $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

                            $total_column = $pvd * $p['unidades'];

                            $total_items += $total_column;

                            $valor_total = $pvd * $p['unidades'] + $imp ;

                            $total += $total + $valor_total;

                            $timp+=$imp;
                        }else{
                             /* POS */
                            $pv = $p['precio_venta'];
                            $desc = $p['descuento'];
                            $pvd = $pv - $desc;
                            $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                            $total_column = $pvd * $p['unidades'];
                            $total_items += $total_column;
                            $valor_total = $pvd * $p['unidades'] + $imp ;
                            $total += $total + $valor_total;
                            $timp+=$imp;
                        }
                       
                       /* $group_by_impuesto_length= count($group_by_impuesto);

                        if($group_by_impuesto_length==0){
                            array_push($group_by_impuesto, array('impuesto_nombre'=>$p['impuesto_nombre'],'impuesto_valor'=>$imp) );
                        }else{
                            $impuesto_exist = false;
                            for ($i=0; $i <  $group_by_impuesto_length; $i++) { 
                                if($p['impuesto_nombre']==$group_by_impuesto[$i]['impuesto_nombre']){
                                    $impuesto_exist = true;
                                    $group_by_impuesto[$i]['impuesto_valor']=$group_by_impuesto[$i]['impuesto_valor']+$imp;
                                }
                            }
                            if(!$impuesto_exist)
                            array_push($group_by_impuesto, array('impuesto_nombre'=>$p['impuesto_nombre'],'impuesto_valor'=>$imp)  );
                        }
*/

                        ?>				
                        <tr>
						
                            <td width="8%" style="border-left: inset 0px #000000;"  align="left"><?php echo $p["codigo_producto"] ?></td>				
                            <td width="5%"  style="border-left: inset 0px #000000;" align="left"><?php echo $p["unidades"] ?></td>
                            <td width="45%"  style="border-left: inset 0px #000000;" align="left"><?php echo $p["nombre_producto"] ?></td>
                            <td width="9%" align="center"  style="border-left: inset 0px #000000;" align="right">$ <?php echo number_format($p["precio_venta"]); ?></td>
							<td width="9%" align="center"  style="border-left: inset 0px #000000;"  align="right">$ <?php echo number_format($total_column); ?></td>   
					    </tr>						

                   <?php
                    }
                    ?>
	                    <?php
					
					$hasta=11-$counter;
                    for($i=1;$i<=$hasta;$i++){
                     ?>
                        <tr> 
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td> 
                            <td></td>   
                     </tr>    
                   <?php
                    }
                    ?>				
                  </table>
	                  <table height="10px" width=860 style="border: 0px inset #000000;">
                     <tr> 
					   <td  width="30%">&nbsp;</td>				  
                     </tr>           
                  </table>	
  <table  width=900>
                        <tr>
                            <td width="33%" align="center" style="border-left: inset 0px #000000;" align="left"></td>						
                            <td width="10%" align="center"  style="border-left: inset 0px #000000;" align="left"></td>
                            <td width="10%" align="center"  style="border-left: inset 0px #000000;" align="left"></td>
                            <td width="10%" align="center"  style="border-left: inset 0px #000000;" align="right"></td>
							<td width="10%" align="center"  style="border-left: inset 0px #000000;"  align="right">
							$ <?php echo number_format($total_items) ?>
							<P style=" padding-top: 1px;" >$ <?php echo number_format($total_items) ?></P>
							<P style=" padding-top: 1px;" >$ <?php  ?></P>
							<P style=" padding-top: 1px;" >$ <?php  ?></P>
							<P style=" padding-top: 1px;" >$ <?php echo number_format($total); ?></P>
							</td>   
					    </tr>
         </table>
		 
                    <table  width=860>
                   <tr>
                     <td >&nbsp;</td>
                   </tr>
                </table>           

            </div>

        </div>
    </body>

</html>

<script type="text/javascript">

    window.print();

</script>

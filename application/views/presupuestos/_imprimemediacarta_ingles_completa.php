<!DOCTYPE html>
<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style>

            body{

                font-family: sans-serif;

                background-color:#FFFFFF;

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
<br><br>
                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table width=818>
                        <tr>
                            <td width="33%"  align="center" style=" font-size: 11px">
                                  <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="250px" height="150px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>   							
  
                        
                            </td>

                            <td width="5%"  align="center">
                                                             
                            </td>

                            <td width="30%" align="right">   <?php echo $data['data_empresa']["data"]['cabecera_factura'];?>                   
                          </td>
                        <tr>  
                  </table>
  <br> <br>
                      <table  width=818>
                   <tr>
                     <td ><hr width="100%"></td>
                   </tr>
                </table>  
  <br> <br> 
   
                    <table  width=818>
                      <tr>                         
                        <td valign="top"  width="45%" style="border: 1px inset #000000; font-size: 14px" >
						<?php echo "<B>Contact Person: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?><br>
						<?php echo "<B>State:</B> ".$data['venta']["nif_cif"]?><br>
						<?php echo "<B>E-Mail:</B> " . $data['venta']['email']; ?><br>
						<?php echo "<B>Address:</B> ".$data['venta']["direccion"]?><br>
						<?php echo "<B>Phone: </B>".$data['venta']["telefono"]?><br>
						<?php echo "<B>Mobile:</B> ".$data['venta']["movil"]; ?>
                        </td>
                        <td  width="14%" >
                        </td>						
                        <td valign="top" width="20%"  >
						
						<table  style="border: 1px inset #000000; font-size: 14px">
						<tr><td>
						Quote <?php echo strtoupper($data['venta']['numero'] );?><br>
						 <?php echo "<b>Date:</b> " . $data['venta']['fecha'] ?><br>
						</td>
                      </tr>
                   </table>  
						  
                        </td>
                      </tr>
                   </table>   
   
      <br><br> <br>
  
 
 					 <?php  
					 
					 $i=0;
					foreach ($data["detalle_venta"] as $p) { 
							
					    if($p['descuento'] > 0){  $i=1;  } 
						
					}					 
					 ?>	
                  <?php  
					 if($i == 1){  				 
		         ?>	 
                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 1px #000000;" cellpadding="0" cellspacing="0">
                  <tr>
                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Description " ?></th>						
                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Quantity" ?></th>
                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Amount&nbsp;" ?></th>
                        <th  style="border: inset 1px #000000;font-size: 14px " align="center"><?php echo "Total&nbsp;" ?></th>
                    </tr>
		           <?php  
				   }	  
					 else{  				 
		         ?>	 
                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 1px #000000;" cellpadding="0" cellspacing="0">

                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Description" ?></th>						
                        <th  style="border: inset 1px #000000; font-size: 14px " align="center"><?php echo "Quantity&nbsp;" ?></th>
                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Amount&nbsp;" ?></th>
                        <th  style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Total&nbsp;" ?></th>
                    </tr>
		           <?php  
				   }			 
	            	?>	 					
					
							
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
                            $precio_t = $p['precio'] * $p['cantidad'];

                            $impuesto = $p['imp'] * $precio_t / 100;

                            $total    = $impuesto + $precio_t;

                            $precio_temp = $p['precio'];		
                            $nom = $p["nombre"]."-".$p['codigo'];
                            $descripcion_d = $p["descripcion_d"];
                            $cantidad = $p["cantidad"];
                            $precio = $precio_temp;
                            $total = $total;
                        ?>
                  <?php  
					 if($i == 1){  				 
		         ?>	 
                        <tr>					
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="left"><?php echo $nom ?></td>
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="right"><?php echo $cantidad ?>&nbsp;</td>
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="right"><?php echo number_format($precio, 2); ?>&nbsp;</td>
							<td style="border-right: inset 1px #000000; font-size: 14px"  align="right"> <?php echo number_format($total, 2); ?>&nbsp;</td>   
					    </tr>
                   <?php
                    } 
					else{  				 
		         ?>							
                        <tr>					
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="left"><?php echo $nom ?></td>
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="right"><?php echo $p["cantidad"] ?>&nbsp;</td>
                            <td  style="border-right: inset 1px #000000;  font-size: 14px" align="right"><?php echo number_format($precio, 2); ?>&nbsp;</td>
							<td style="border-right: inset 1px #000000; font-size: 14px"  align="right"><?php echo number_format($total, 2); ?>&nbsp;</td>   
					    </tr>						
				   <?php
                    }
                    ?>		
                   <?php
                    }
                    ?>
	                    <?php
					
					$hasta=23-$counter;
                    for($a=1;$a<=$hasta;$a++){
                     ?>
                        <tr> 
                            <td style=" border-right: inset 1px #000000; font-size: 14px" >&nbsp;</td>
                            <td style=" border-right: inset 1px #000000; font-size: 14px" ></td>
                            <td style=" border-right: inset 1px #000000; font-size: 14px" ></td>
				 <?php  
					 if($i == 1){  				 
		         ?>	 	
				 	 <td  style=" border-right: inset 1px #000000; font-size: 14px" ></td
				   ><?php
                    }
                    ?>								  
                     </tr>    
                   <?php
                    }
					
				$monto_siva = $data['venta']['monto_siva'];
				$monto_iva = $data['venta']['monto_iva'];
		        $monto = $data['venta']['monto'];
					
                    ?>				
                  </table><br>
				           
                   <table  width=818 style=" font-size: 11px" cellpadding="0" cellspacing="0">
                   <tr>
                     <td style="font-size: 9px;  width: 65%;"></td>
                     <td  valign="top" style="border-left: inset 0px #000000; width: 13%;  font-size: 14px" align="right" >
              &nbsp;&nbsp; <b><?php echo "Sub-Total" ?></b><br>
			  &nbsp;&nbsp; <b><?php echo "Tax " ?></b><br>
			<hr>
		 &nbsp;&nbsp; 	<b>Total </b>
			  </td>
			   <td  valign="top" align="right" style="font-size: 14px;">
			  <?php echo number_format($monto_siva, 2) ?><br>		  
			   <?php echo number_format($monto_iva, 2) ?><br>	  					  
			  <hr>
		  <?php echo number_format($monto, 2); ?>	  
			  
			  </td>
			  
			  </td>
                    </tr>
                </table>

                    <table  width=860>
                   <tr>
                     <td ><?php echo $data['data_empresa']["data"]['terminos_condiciones'];?></td>
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


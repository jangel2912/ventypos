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

    margin-top: 17px;
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
              <?php if($data['venta']['estado'] == '-1'){ ?>
                 <table  width=900>
                   <tr>
                     <td align="center" style="font-size:22px; font-weight:bold;">&nbsp;Venta Anulada
					 </td>
                   </tr>
                </table> 
               <?php } ?>
                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table style="border: inset 0px #000000; border-bottom: 0px solid red;" width=900>
                        <tr>
                            <td width="20%"  align="center" style=" font-size: 11px">
							              <?php /* if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="150px" height="50px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; */?>  
                        
                            </td>

                            <td width="33%"  align="center">
                                <?php //echo $data['data_empresa']["data"]['cabecera_factura'];?>                                
                            </td>

                            <td width="33%" align="center">
                          
                               <B><?php // echo strtoupper($data['data_empresa']['data']['nombre']); ?></B> <br>                                    
                                   
                                        <?php // echo "NIT:" . $data['data_empresa']['data']['nit']; ?><br/>
                                        <?php // echo $data['data_empresa']['data']['resolucion'];?>
										                    <?php // if(strlen($data['data_empresa']['data']['direccion']) > 2){ ?> <br/>
										                    <?php // echo "<B>Direcci&oacute;n:</B> ".$data['data_empresa']['data']["direccion"]?><br/> 
										<?php //} ?>									
										<?php // if(strlen($data['data_empresa']['data']['telefono']) > 2){ ?>
										<?php //echo "<B>Telefono:</B> " . $data['data_empresa']['data']['telefono']; ?>	
										<?php //} ?>
								     	 	
                          </td>
                        <tr>  

                  </table>
                    
                    <table style="border-left: 0px inset #000000; border-right: 0px inset #000000; border-top: 0px inset #000000; border-bottom: 0px solid red;"  width=818>
                     <!--  <tr>                         
                        
                       <!--  <td>&nbsp;&nbsp;
                         <?php  // echo strtoupper($data['venta']['nombre']);?> 
                        </td> -->
                        
                      <!--   <td>&nbsp;&nbsp;
                         <?php  //echo "<b>Fecha:</b> " . $data['venta']['fecha'] ?>
                        </td> -->

                        <!--  <td align="right">
                          <b><?php //echo strtoupper($data['data_empresa']['data']['titulo_venta']).' '. $data['venta']['factura'] ?></b>
                         </td> -->
                      <!-- </tr> -->
                   </table>
             
                   <table  width=900 style="margin-top: 0px; margin-left: 50px; border-left: 0px inset #000000; border-right: 0px inset #000000; border-top: 0px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <!-- <td width="48%"><?php echo "<B>Cliente: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>        
                      <td><?php echo "<B>C.C/NIT:</B> ".$data['venta']["nif_cif"]?></td>
                      <td><?php echo "<B>Ciudad:</B> " . $data['venta']['cliente_provincia']; ?></td>					  
                     </tr><?php /*echo "Email: ".$data['venta']["email"]*/ ?>
                     <tr>
                      <td><?php echo "<B>Direcci&oacute;n:</B> ".$data['venta']["cliente_direccion"]?></td>        
                      <td><?php echo "<B>Tel&eacute;fono: </B>".$data['venta']["cliente_telefono"]?></td>
					  <td><?php echo "<B>Celular:</B> ".$data['venta']["cliente_movil"]; ?></td>				  
                     </tr> -->
                    <tr>
                    <!--  <td width="48%"><B>Vendedor:</B> <?php echo $data['venta']["vendedor"]?></td> -->
					 
					   <div style="margin-top: 0px; margin-left: 100px; float: left;"><?php echo $data['venta']['nombre_comercial']; ?></div>	

             <div style="margin-top: 0px; margin-left: 150px; float: left;"><?php echo $data['venta']["nif_cif"]; ?></div> 

            

					   <div style="margin-top: 0px; margin-right: 220px; float: right;"> <?php  echo "" . $data['venta']['fecha'] ?>

					   </div>	  

              <div style="margin-top: 0px; margin-left: 100px; float: left;"><?php echo $data['venta']["cliente_telefono"]; ?></div> 
					 
                    
                    </tr>


                  
                  </table>

				<?php  if($data['venta']['nota'] != ''){   ?>
                   <table  width=900 style="padding-top: 12px; border-left: 0px inset #000000; border-right: 0px inset #000000; border-top: 0px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="48%"><?php echo $data['venta']['nota']; ?></td>        
                     </tr>	        
                    </tr>
                  </table>				  
				<?php  }   ?>  
 
 					 <?php  
					 
					 $i=0;
					foreach ($data["detalle_venta"] as $p) { 
							
					    if($p['descuento'] > 0){  $i=1;  } 
						
					}					 
					 ?>	
                  <?php  
					 if($i == 1){  				 
		         ?>	 
                   <table  width=900 style="border: inset 1px #000000; border-bottom: inset 0px #000000; padding-top: 5px; margin-top: 25px;">

                        <th  style="border: inset 1px #000000;" width="7%" align="left"><?php echo "Ref" ?></th>

                          <th  style="border: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>						
                      
                        <th  style="border: inset 1px #000000; " align="left" width="20%"><?php echo "Precio" ?></th>
                   
                        <th  style="border: inset 1px #000000; " align="left" width="20%"><?php echo "Total" ?></th>
                    </tr>
		           <?php  
				   }	  
					 else{  				 
		         ?>	 
                   <table  width=900 style="border: inset 0px #000000; border-bottom: inset 0px #000000; margin-left: 0px; padding-top: 25px;">

                        <th  style="border: inset 0px #000000; " width="6%" align="left"><?php echo " " ?></th>
                        <th  style="border: inset 0px #000000; " width="5%"  align="left"><?php echo " " ?></th>
                        <th  style="border: inset 0px #000000; " align="left"  width="70%"><?php echo " " ?></th>						
                        
                        <th  style="border: inset 0px #000000; " align="left"  width="10%"><?php echo " " ?></th>
                        <th  style="border: inset 0px #000000; " align="left"  width="10%"><?php echo " " ?></th>

                       
                      
                         
                          
                          
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
                        if($data["tipo_factura"]=='clasico'){
                             /* SERVICIOS */
                            $pv = $p['precio_venta'];
                            $desc = $p['descuento'];
                            $pvd = $pv - $desc;
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
                  <?php  
					 if($i == 1){  				 
		         ?>	 
                        <tr>
                            <td  style="font-size: 10px; float: left; margin-left: 10px; " align="left"><?php echo $p["codigo_producto"] ?></td>	
                            <td  style="font-size: 10px" align="left"><?php echo $p["unidades"] ?></td>					
                            <td  style="font-size: 10px" align="left"><?php echo $p["nombre_producto"] ?></td>
                            
                            <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p["precio_venta"]); ?></td>
                            <td style="font-size: 10px"  align="right"><?php echo $p['descuento']; ?></td> 
							<td style="font-size: 9px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?></td>   
					    </tr>
                   <?php
                    } 
					else{  				 
		         ?>							
                        <tr style="margin-top: 0px;">
                            <td  style="font-size: 10px; margin-left: 10px;" ><?php echo "&nbsp; &nbsp; &nbsp;".$p["codigo_producto"] ?></td>		
                            <td  style="font-size: 10px; margin-left: 10px;" align="left"><?php echo "&nbsp; &nbsp; &nbsp; &nbsp;".$p["unidades"] ?></td>				
                            <td  style="font-size: 10px; margin-left: 10px;" align="left"><?php echo "&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;".$p["nombre_producto"] ?></td>
                            
                            <td  style="font-size: 10px" align="right"> <?php echo  "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".$data['data_empresa']['data']['simbolo'].' '.number_format($p["precio_venta"]); ?></td>
							<td style="font-size: 10px"  align="right"> <?php echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".$data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?></td>   
					    </tr>						
				   <?php
                    }
                    ?>		
                   <?php
                    }
                    ?>
	                    <?php
					
					$hasta=7-$counter;
                    for($i=1;$i<=$hasta;$i++){
                     ?>
                        <tr> 
                            <td>&nbsp;</td>
                            
                     </tr>    
                   <?php
                    }
                    ?>				
                  </table>
   <?php  $total = $total_items + $timp; ?>          
                  <table  width=900 height=10  style="border: inset 0px #000000; height: 7px; font-size: 11px; padding-top: -10px; margin-top: 5px; line-height: 12px;">
                   <tr>
                     <!-- <td style="border-right: inset 1px #000000; width: 20%;" align="center"  valign="bottom">_______________________________<br><B>FIRMA DEL CLIENTE</B></td>	 -->
                     <td style="font-size: 9px;" width="70%">
					 
                       <?php // echo $data['data_empresa']["data"]['terminos_condiciones'];?>
				 		        </td>

                  
                     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                       <td></td>
                     <td></td>
                    
                        
                  
            
                     
                     <td style="border-left: inset 0px #000000; padding-top: -10px; " align="right" width="10%">
				 	 
                    &nbsp;&nbsp; <b><?php //echo "Valor items: " ?></b><br>
			               &nbsp;&nbsp; <b><?php //echo "IVA: " ?></b><br>					 
				

                 <?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>

			           <?php if($p->forma_pago!='efectivo'){  $formpago=str_replace("_"," ",$p->forma_pago);  ?> 
				  
			          &nbsp;&nbsp;  <b><?php echo ucfirst($formpago).": " ?></b> <br>
		           <?php } ?>
              <?php if($p->forma_pago=='efectivo'){  ?>	 
               &nbsp;&nbsp;  <b>  </b> <br>
		           &nbsp;&nbsp;  <b>  </b>  <br>
			         <?php } ?>	
			
			<?php } ?>
			
		 &nbsp;&nbsp; 	<b>  </b>
		 
		 
			  </td>
			  
			   <td   align="left" style="margin-left: 100px; float: left; line-height: 14px; padding-top: -20px;  " >
				  <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_items) ?><br>		  
			  <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($timp) ?><br>
			  <?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>


	<?php   $formpago=str_replace("_"," ",$p->forma_pago);  ?>			  		  
		 <?php if($p->forma_pago!='efectivo'){  ?>  		  
			  <?php echo $p->valor_entregado; ?><br>
	    	   <?php } ?>	
          <?php if($p->forma_pago=='efectivo'){  ?>	
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado) ?><br>
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->cambio) ?><br>
            <?php } ?>	  	
							  
			<?php } ?>	
			  
		$  <?php echo number_format($total); ?>	  
			  
			  </td>
			  
	
                    </tr>

                </table>
 
                    <table  width=900>
                   <tr>
                     <td align="center" style="font-size:24px; font-weight:bold;">&nbsp;
				<?php /*if($data['venta']['estado'] == '0'){ ?>
					 
					<?php /*if($this->uri->segment(4) == 'copia'){ ?>	
					  COPIA 
					<?php }else{ ?>	  	
					  ORIGINAL
					<?php } ?>
					
				<?php }*/ ?>	
					 </td>
                   </tr>
                </table>            

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>


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

                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table style="border: inset 1px #000000; border-bottom: 0px solid red;" width=818>
                        <tr>
                            <td width="33%"  align="center" style=" font-size: 11px">
                               <B><?php echo strtoupper($data['data_empresa']['data']['nombre']); ?></B> <br>                                    
                                   
                                        <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
                                            <?php echo $data['data_empresa']['data']['documento'].": " . $data['venta']['nit']; ?><br/>
                                            <?php echo $data['venta']['resolucion_factura'];?><br/>
                                        <?php } else { ?>
                                                <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>
                                                <?php
                                                    $prefijo = '1';
                                                    if(($data['data_empresa']['data']['nombre'] == 'TCC S.A.S') && ( $data['venta']['almacen_id'] == 84) ){
                                                      $prefijo = '64';
                                                    }
                                                    if(($data['data_empresa']['data']['nombre'] == 'TCC S.A.S') && ( $data['venta']['almacen_id'] == 122) ){
                                                      $prefijo = '41';
                                                    }
                                                 ?>
                                                <?php echo ($data['numero'] == 'no' && isset($data['venta']['resolucion_factura']))?
                                                'Resolución autorización No. '.strtoupper($data['venta']['resolucion_factura']).' de '.$data['venta']['fecha_vencimiento'].' rango del '.$data['venta']['prefijo'].$prefijo.' al '.$data['venta']['prefijo'].$data['venta']['numero_fin']: 
                                                    $data['data_empresa']['data']['resolucion']?><br/>
                                        <?php } ?>
                                        
                                        <?php echo $data['venta']['direccion'] ?><br/>
										<?php if($data['data_empresa']['data']['nombre'] == 'TCC S.A.'){ ?>
                                        <?php echo $data['data_empresa']['data']['telefono'] ?> <br/>
										<?php }else{ ?>
										<?php echo "TEL:" . $data['data_empresa']['data']['telefono'] ?> <br/>
										<?php } ?>										
										<B><?php echo "" . $data['data_empresa']['data']['web'] ?> </B><br/>
										<B><?php echo "" . $data['data_empresa']['data']['email'] ?> </B>
                        
                            </td>

                            <td width="33%"  align="center">
                                <?php echo $data['data_empresa']["data"]['cabecera_factura'];?>                                
                            </td>

                            <td width="20%" align="right">
                                  <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="150px" height="50px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>                            
                          </td>
                        <tr>  
                  </table>
                    
                    <table style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red;"  width=818>
                      <tr>                         
                        <td>&nbsp;&nbsp;
                         <?php echo strtoupper($data['venta']['nombre'] );?>
                        </td>
                        <td>
                         <?php echo "<b>Fecha:</b> " . $data['venta']['fecha'] ?> &nbsp;&nbsp;&nbsp;&nbsp; 					 
						<?php 
						if($data['venta']['tipo_factura'] == 'clasico'){
						  echo "<b>Fecha de Vencimiento:</b> " .$data['venta']['fecha_vencimiento_venta']; 
						}
						?>  				
                        </td>
                         <td align="right">
                          <b><?php echo strtoupper($data['data_empresa']['data']['titulo_venta']).' '. $data['venta']['factura'] ?></b>
                         </td>
                      </tr>
                   </table>
             
                   <table  width=818 style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="48%"><?php echo "<B>Cliente: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>        
                      <td><?php echo "<B>".$data['venta']["tipo_identificacion"].":</B> ".$data['venta']["nif_cif"]?></td>
                      <td><?php echo "<B>Vendedor:</B> " . $data['venta']['vendedor']; ?></td>					  
                     </tr><?php /*echo "Email: ".$data['venta']["email"]*/ ?>
                     <tr>
                      <td><?php echo "<B>Direcci&oacute;n:</B> ".$data['venta']["cliente_direccion"]?></td>        
                      <td><?php echo "<B>Tel&eacute;fono: </B>".$data['venta']["cliente_telefono"]?></td>
					  <td><?php echo "<B>Celular:</B> ".$data['venta']["cliente_movil"]; ?></td>				  
                     </tr>
                    <tr>
                     <td width="48%"></td>
					  <td></td>	
					   <td></td>	        
                    </tr>
                  
                  </table>
				<?php  if($data['venta']['nota'] != ''){   ?>
                   <table  width=818 style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
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
                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 0px #000000;">

                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Ref" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>						
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Precio" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Desc" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Total" ?></th>
                    </tr>
		           <?php  
				   }	  
					 else{  				 
		         ?>	 
                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 0px #000000;">

                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Ref" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>						
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Precio" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Total" ?></th>
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
                            <td  style="font-size: 10px" align="left"><?php echo $p["codigo_producto"] ?></td>						
                            <td  style="font-size: 10px" align="left"><?php echo $p["nombre_producto"] ?></td>
                            <td  style="font-size: 10px" align="left"><?php echo $p["unidades"] ?></td>
                            <td  style="font-size: 10px" align="right">$ <?php echo $data['data_empresa']['data']['simbolo'].' '.$p["precio_venta"]; ?></td>
                            <td style="font-size: 10px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.$p['descuento']; ?></td> 
							<td style="font-size: 9px"  align="right">$ <?php echo $data['data_empresa']['data']['simbolo'].' '.$total_column; ?></td>   
					    </tr>
                   <?php
                    } 
					else{  				 
		         ?>							
                        <tr>
                            <td  style="font-size: 10px" align="left"><?php echo $p["codigo_producto"] ?></td>						
                            <td  style="font-size: 10px" align="left"><?php echo $p["nombre_producto"] ?></td>
                            <td  style="font-size: 10px" align="left"><?php echo $p["unidades"] ?></td>
                            <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.$p["precio_venta"]; ?></td>
							<td style="font-size: 10px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.$total_column; ?></td>   
					    </tr>						
				   <?php
                    }
                    ?>		
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
   <?php  $total = $total_items + $timp; ?>          
                   <table  width=818 height=80  style="border: inset 1px #000000; font-size: 11px">
                   <tr>
                     <td style="border-right: inset 1px #000000; width: 20%;" align="center"  valign="bottom">_______________________________<br><B>FIRMA DEL CLIENTE</B></td>	
                     <td style="font-size: 9px;  width: 50%;"><?php echo $data['data_empresa']["data"]['terminos_condiciones'];?></td>
                     <td style="border-left: inset 1px #000000; width: 12%;" align="left" >
				 	 
                 &nbsp;&nbsp; <b><?php echo "Valor items: " ?></b><br>
			     &nbsp;&nbsp; <b><?php echo "IVA: " ?></b><br>					 
				<?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>

			     <?php if($p->forma_pago!='efectivo'){  $formpago=str_replace("_"," ",$p->forma_pago);  ?> 
				  
			    &nbsp;&nbsp;  <b><?php echo ucfirst($formpago).": " ?></b> <br>
		       <?php } ?>
              <?php if($p->forma_pago=='efectivo'){  ?>	 
                    &nbsp;&nbsp;  <b> Efectivo: </b> <br>
                    &nbsp;&nbsp;  <b>  Cambio: </b>  <br>               
               <?php 
                    $cambiot=$p->valor_entregado - $total;
                } 
                ?>	
			
			<?php } ?>
			
		 &nbsp;&nbsp; 	<b>Total a Pagar: </b>
			  </td>
			   <td   align="right" >
				  <?php echo $data['data_empresa']['data']['simbolo'].' '.$total_items; ?><br>		  
			    <?php echo $data['data_empresa']['data']['simbolo'].' '.$timp; ?><br>
			  <?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>


	<?php   $formpago=str_replace("_"," ",$p->forma_pago);  ?>			  		  
		 <?php if($p->forma_pago!='efectivo'){  ?>  		  
			 <?php echo $data['data_empresa']['data']['simbolo'].' '.$p->valor_entregado; ?><br>
	    	   <?php } ?>	
          <?php if($p->forma_pago=='efectivo'){  ?>	
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.$p->valor_entregado; ?><br>
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.$cambiot; ?><br>
            <?php } ?>	  	
							  
			<?php } ?>	
			  
		  <?php echo $data['data_empresa']['data']['simbolo'].' '.$total; ?>	  
			  
			  </td>
			  
			  </td>
                    </tr>
                </table>
 
              

            </div>
     <table  width=860>
                   <tr>
                     <td align="center" style="font-size:24px; font-weight:bold;">&nbsp;
        <?php if($data['venta']['estado'] == '0' && $data['venta']['tipo_factura'] == 'estandar'){ ?>
        
          <?php if($this->uri->segment(4) == 'copia'){ ?> 
                           <td style="margin-left: 350px; float: left;"><b><h2>COPIA</h2></b></td>
          <?php }else{ ?>     
          
          <?php } ?>
          
        <?php } ?>  
           </td>
                   </tr>
                </table>            
        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>


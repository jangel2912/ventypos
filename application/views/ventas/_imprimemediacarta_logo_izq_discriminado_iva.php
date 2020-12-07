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
              <?php if($data['venta']['estado'] == '-1'){ ?>
                 <table  width=860>
                   <tr>
                     <td align="center" style="font-size:24px; font-weight:bold;">&nbsp;Venta Anulada
					 </td>
                   </tr>
                </table> 
               <?php } ?>
                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table style="border: inset 1px #000000; border-bottom: 0px solid red;" width=818>
                        <tr>
                            <td width="20%"  align="center" style=" font-size: 11px">
							    <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="150px" height="50px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>  
                        
                            </td>

                            <td width="33%"  align="center">
                                <?php echo $data['data_empresa']["data"]['cabecera_factura'];?>                                
                            </td>

                            <td width="33%" align="center">
                          
                               <B><?php echo strtoupper($data['data_empresa']['data']['nombre']); ?></B> <br>                                    
                                   
                                        <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
                                            <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_almacen']['nit']; ?><br/>
                                            <?php echo $data['data_almacen']['resolucion_factura'];?><br/>
                                        <?php } else { ?>
                                            <?php if($data['data_empresa']['data']['sistema'] == 'Pos') { ?>
                                                    <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>

                                                    <?php echo $data['data_empresa']['data']['resolucion'];?><br/>
                                            <?php } ?>
                                        <?php } ?>
										
										<?php if(strlen($data['data_empresa']['data']['direccion']) > 2){ ?>
										<br/> <?php echo "<B>Direcci&oacute;n:</B> ".$data['data_empresa']['data']["direccion"]?><br/> 
										<?php } ?>
										
										<?php if(strlen($data['data_empresa']['data']['telefono']) > 2){ ?>
										<?php echo "<B>Telefono:</B> " . $data['data_empresa']['data']['telefono']; ?>	
										<?php } ?>
								     	 	
                          </td>
                        <tr>  
                  </table>
                    
                    <table style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red;"  width=818>
                      <tr>                         
                        <td>&nbsp;&nbsp;
                         <?php echo strtoupper($data['venta']['nombre']);?> 
                        </td>
                        <td>&nbsp;&nbsp;
                         <?php echo "<b>Fecha:</b> " . $data['venta']['fecha'] ?>
                        </td>
                         <td align="right">
                          <b><?php echo strtoupper($data['data_empresa']['data']['titulo_venta']).' '. $data['venta']['factura'] ?></b>
                         </td>
                      </tr>
                   </table>
             
                   <table  width=818 style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                      <td width="48%"><?php echo "<B>Cliente: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>        
                      <td><?php echo "<B>C.C/NIT:</B> ".$data['venta']["nif_cif"]?></td>
                      <td><?php echo "<B>Ciudad:</B> " . $data['venta']['cliente_provincia']; ?></td>					  
                     </tr><?php /*echo "Email: ".$data['venta']["email"]*/ ?>
                     <tr>
                      <td><?php echo "<B>Direcci&oacute;n:</B> ".$data['venta']["cliente_direccion"]?></td>        
                      <td><?php echo "<B>Tel&eacute;fono: </B>".$data['venta']["cliente_telefono"]?></td>
					  <td><?php echo "<B>Celular:</B> ".$data['venta']["cliente_movil"]; ?></td>				  
                     </tr>
                    <tr>
                     <td width="48%"><B>Vendedor:</B> <?php echo $data['venta']["vendedor"]?></td>
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
					    if($p['descuento'] > 0) $i=1; 
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
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Iva" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Desc" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Subtotal" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Total" ?></th>
              </tr>
		    <?php  
				    } else {  				 
		    ?>	 
             <table  width=818 style="border: inset 1px #000000; border-bottom: inset 0px #000000;">
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Ref" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>						
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Precio" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Iva" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Subtotal" ?></th>
                  <th  style="border: inset 1px #000000; " align="left"><?php echo "Total" ?></th>
              </tr>
		    <?php  
				   }
        $total = 0;
        $timp  = 0;
        $subtotal = 0;
        $total_items = 0;
        $exentos=0;

        $group_by_impuesto = array();
        $counter=NULL;
			  $hasta=NULL;
        //$codigoProducto = $p["codigo_producto"];
        $codigoProducto = "";
        foreach ($data["detalle_venta"] as $key => $p) {
            $counter++;
              if($data["tipo_factura"]=='clasico'){
                  /* SERVICIOS */
                  $pv = $p['precio_venta'];
                  $desc = $p['descuento'];
                  $pvd = $pv - ($pv * ($desc/100));
                  $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                  $impu = $pvd * $p['impuesto'] / 100;
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
                  $impu = $pvd * $p['impuesto'] / 100;
                  $total_column = $pvd * $p['unidades'];
                  
                  $valor_total = $pvd * $p['unidades'] + $imp ;
                  $total += $total + $valor_total;
                                   
                  if($p['impuesto']==0){                   
                    $exentos+=$valor_total;
                  }
                  else{
                      $timp+=$imp;
                      $total_items += $total_column;
                  }
                  
              }
              
              $imp = is_numeric($imp) ? $imp : 0;
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

               if($codigoProducto == $p["codigo_producto"])
                {
                    //echo "<br>Entre en el if<br>";
                    if(isset($data["detalle_venta"][$key-1]['descripcion_producto']) && $data["detalle_venta"][$key-1]['descripcion_producto'] == "-1")
                    {
                      $total_items-=$total_column;
                      $timp-=$imp;
                      $total-=$valor_total;
                      $p['precio_venta'] = 0;
                      $total_column = 0;
                      $imp = 0;

                    }
                } 

					     if($i == 1){ 
          ?>	 
                <tr>
                    <td  style="font-size: 10px" align="left"><?php echo $p["codigo_producto"] ?></td>						
                    <td  style="font-size: 10px" align="left"><?php echo $p["nombre_producto"] ?></td>
                    <td  style="font-size: 10px" align="left"><?php echo $p["unidades"] ?></td>
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p["precio_venta"]); ?></td>
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($impu); ?></td>
                    <td style="font-size: 10px"  align="right"><?php echo number_format($p['descuento']); ?></td> 
  							    <td style="font-size: 9px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?></td> 
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column + $imp); ?></td>
  					    </tr>
          <?php }	else { ?>							
                <tr>
                    <td  style="font-size: 10px" align="left"><?php echo $p["codigo_producto"] ?></td>						
                    <td  style="font-size: 10px" align="left"><?php echo $p["nombre_producto"] ?></td>
                    <td  style="font-size: 10px" align="left"><?php echo $p["unidades"] ?></td>
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p["precio_venta"]); ?></td>
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($impu); ?></td>
							      <td style="font-size: 10px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?></td>
                    <td  style="font-size: 10px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column + $imp); ?></td>
					      </tr>						
				   <?php
                }

             $codigoProducto = $p["codigo_producto"];
          }
          
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
   <?php  $total = $total_items + $timp + $exentos; ?>          
                   <table  width=818 height=80  style="border: inset 1px #000000; font-size: 11px">
                   <tr>
                     <td style="border-right: inset 1px #000000; width: 20%;" align="center"  valign="bottom">_______________________________<br><B>FIRMA DEL CLIENTE</B></td>	
                     <td style="font-size: 9px;  width: 50%;">
					 
                  <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
				 		 
					</td>
          <td style="border-left: inset 1px #000000; width: 12%;" align="left" >
				 	 
             &nbsp;&nbsp; <b><?php echo "Valor items: " ?></b><br>
             <?php /* foreach ($data["venta_impuestos"] as $p) { 
              echo '&nbsp;&nbsp;&nbsp;<b>'.$p->imp.'<b><br>';
              
             }*/
             echo "&nbsp;&nbsp;&nbsp;<b>Base IVA:</b><br>";
             echo "&nbsp;&nbsp;&nbsp;<b>IVA:</b><br>";
             echo "&nbsp;&nbsp;&nbsp;<b>Exentos:</b><br>";
             ?> 
            <?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>

               <?php if($p->forma_pago!='efectivo'){  $formpago=str_replace("_"," ",$p->forma_pago);  ?> 
              
              &nbsp;&nbsp;  <b><?php echo ucfirst($formpago).": " ?></b> <br>		       <?php } ?>
                  <?php if($p->forma_pago=='efectivo'){  ?>	 
                   &nbsp;&nbsp;  <b> Efectivo: </b> <br>
              &nbsp;&nbsp;  <b>  Cambio: </b>  <br>
             <?php } ?>	

            <?php } ?>
  			
  		        &nbsp;&nbsp; 	<b>Total a Pagar: </b>
		 
		 
			     </td>
			  
			   <td   align="right" >
				  <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total) ?><br>	
          <?php  /*foreach ($data["venta_impuestos"] as $p) {  
            echo $data['data_empresa']['data']['simbolo'].number_format($timp).'<br>';
          }*/ echo $data['data_empresa']['data']['simbolo'].number_format($total_items).'<br>';
          echo $data['data_empresa']['data']['simbolo'].number_format($timp).'<br>';
          echo $data['data_empresa']['data']['simbolo'].number_format($exentos).'<br>';
          ?>    
			  <?php  foreach ($data["detalle_pago_multiples"] as $p) { ?>


	<?php   $formpago=str_replace("_"," ",$p->forma_pago);  ?>			  		  
		 <?php if($p->forma_pago!='efectivo'){  ?>  		  
			  <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado); ?><br>
	    	   <?php } ?>	
          <?php if($p->forma_pago=='efectivo'){  ?>	
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado) ?><br>
		      <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->cambio) ?><br>
            <?php } ?>	  	
							  
			<?php } ?>	
			  
		    <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total); ?>	  
			  
			  </td>
			  
			  </td>
                    </tr>
                </table>
 
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

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>


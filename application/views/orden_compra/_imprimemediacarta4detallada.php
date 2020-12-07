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
                                   
                                        <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>
                                        <?php echo $data['data_empresa']['data']['resolucion'];?><br/>
                                        <?php echo $data['venta']['direccion'] ?><br/>
                                        <?php echo "TEL:" . $data['data_empresa']['data']['telefono'] ?> <br/>
										<B><?php echo "" . $data['data_empresa']['data']['web'] ?> </B><br/>
										<B><?php echo "" . $data['data_empresa']['data']['email'] ?> </B>                    
                            </td>

                            <td width="33%"  align="center">
                                                             
                            </td>

                            <td width="20%" align="right">
                                  <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="150px" height="48px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>                            
                          </td>
                        <tr>  
                  </table>
                    
                    <table style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red;"  width=818>
                      <tr>                         
                        <td>&nbsp;&nbsp;
                         <?php echo strtoupper($data['venta']['nombre'] );?>
                        </td>
                        <td>&nbsp;&nbsp;
                         <?php echo "<b>Fecha:</b> " . $data['venta']['fecha'] ?>
                        </td>
                         <td align="right">
                          <b>Orden de Compra: <?php echo $data['venta']['id_venta'] ?></b>
                         </td>
                      </tr>
                   </table>
             
                   <table  width=818 style="border-left: 1px inset #000000; border-right: 1px inset #000000; border-top: 1px inset #000000; border-bottom: 0px solid red; ">
                     <tr>
                        <td width="48%"><?php echo "<B>Proveedor: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?></td>        
                        <td><?php echo "<B>NIT:</B> ".$data['venta']["nif_cif"]?></td>
                        <td><?php echo "<B>Vendedor:</B> " . $data['venta']['vendedor']; ?></td>					  
                     </tr><?php /*echo "Email: ".$data['venta']["email"]*/ ?>
                     <tr>
                        <td><?php echo "<B>Direcci&oacute;n:</B> ".$data['venta']["proveedores_direccion"]?></td>        
                        <td><?php echo "<B>Tel&eacute;fono: </B>".$data['venta']["proveedores_telefono"]?></td>
                        <td></td>				  
                     </tr>
                     <?php if(!empty($data['venta']["motivo"])){ ?>
                     <tr>
                        <td><b>Orden de Compra:</b> Anulada</td>		
                        <td><?php echo "<B>Motivo:</B> ".$data['venta']["motivo"]?></td>        
                        <td><?php echo "<B>Fecha Anulación: </B>".$data['venta']["fecha_anulacion"]?></td>                        		  
                     </tr>
                     <?php } ?>
                    <tr>
                     <td width="48%"></td>
					  <td></td>	
					   <td></td>	        
                    </tr>
                  
                  </table>
             
                   <table  width=818 style="border: inset 1px #000000; border-bottom: inset 0px #000000;">

                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Ref" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Descripción" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Unidad" ?></th>												
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Cantidad" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Precio Compra" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Precio Venta" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Desc" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Total Compra" ?></th>
                        <th  style="border: inset 1px #000000; " align="left"><?php echo "Total Venta" ?></th>
                    </tr>
                    <?php

                        $total = 0;

                        $timp  = 0;

                        $subtotal = 0;

                        $total_items = 0;
                        $totalT = 0;

                        $timpT  = 0;

                        $subtotalT = 0;

                        $total_itemsT = 0;

                    $group_by_impuesto = array();
                      $counter=NULL;
					  $hasta=NULL;
                    foreach ($data["detalle_venta"] as $p) {
                    $counter++;
                             /* POS */
                            $pv = $p['precio_venta'];
                            $desc = $p['descuento'];
                            $pvd = $pv - ($desc*$pv/100);
                            $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                            $total_column = $pvd * $p['unidades'];
                            $total_items += $total_column;
                            $valor_total = $pvd * $p['unidades'] + $imp ;
                            $total += $total + $valor_total;
                            $timp+=$imp;
                            $pvT = $p['precio_venta_final'];
                            $pvdT = $pvT - ($desc*$pvT/100);
                            $impT = $pvdT * $p['impuesto'] / 100 * $p['unidades'];
                            $total_columnT = $pvdT * $p['unidades'];
                            $total_itemsT += $total_columnT;
                            $valor_totalT = $pvdT * $p['unidades'] + $impT;
                            $totalT += $totalT + $valor_totalT;
                            $timpT+=$impT;
                       
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
                            <td  style="font-size: 9px" align="left"><?php echo $p["codigo_producto"] ?></td>						
                            <td  style="font-size: 9px" align="left"><?php echo $p["nombre_producto"] ?></td>
                            <td  style="font-size: 9px" align="left"><?php echo $p["nombre_unidad"] ?></td>							
                            <td  style="font-size: 9px" align="left"><?php echo $p["unidades"] ?></td>
                            <td  style="font-size: 9px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.($p["precio_venta"]); ?></td>
                            <td  style="font-size: 9px" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.($p["precio_venta_final"]); ?></td>
                            <td style="font-size: 9px"  align="right"><?php echo $p['descuento']; ?></td> 
                            <td style="font-size: 9px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.($total_column); ?></td>
                            <td style="font-size: 9px"  align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.($total_columnT); ?></td>   						
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
                    <tfoot>
                        <tr>
                            <td colspan="5"><b>Nota :</b><?php echo $data['venta']['nota'] ?></td>
                        </tr>
                    </tfoot>
                  </table>
   <?php
   $total = $total_items + $timp;
   $totalT = $total_itemsT + $timpT;
   ?>          
                   <table  width=818 height=80  style="border: inset 1px #000000; font-size: 11px">
                   <tr>
                     <td style="border-right: inset 1px #000000; width: 60%;" align="center"  valign="bottom">_______________________________<br><B>FIRMA DEL PROVEEDOR</B></td>	
                        <td style="width: 17%;" align="left" >
                            &nbsp;&nbsp; <b><?php echo "Valor items compra: " ?></b><br>
                            &nbsp;&nbsp; <b><?php echo "T.Impuestos compra: " ?></b><br>
                            &nbsp;&nbsp; <font size="2"><b>Total a Pagar: </b></font><br>
                            <hr>
                            &nbsp;&nbsp; <b><?php echo "Valor items venta: " ?></b><br>
                            &nbsp;&nbsp; <b><?php echo "T.Impuestos venta: " ?></b><br>
                            &nbsp;&nbsp; <font size="2"><b>Total a Venta: </b></font>
                        </td>
                        <td   align="right" >
                            <?php echo $data['data_empresa']['data']['simbolo'].' '.round($total_items, 2, PHP_ROUND_HALF_UP) ?><br>

                            <?php echo $data['data_empresa']['data']['simbolo'].' '.round($timp, 2, PHP_ROUND_HALF_UP) ?><br>
                            <font size="3"><?php echo $data['data_empresa']['data']['simbolo'].' '.round($total, 2, PHP_ROUND_HALF_UP); ?></font>  
                            <hr>
                            <?php echo $data['data_empresa']['data']['simbolo'].' '.round($total_itemsT, 2, PHP_ROUND_HALF_UP) ?><br>
                            <?php echo $data['data_empresa']['data']['simbolo'].' '.round($timpT, 2, PHP_ROUND_HALF_UP) ?><br>
                            <font size="3"><?php echo $data['data_empresa']['data']['simbolo'].' '.round($totalT, 2, PHP_ROUND_HALF_UP); ?></font>  
                        </td>
			  
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


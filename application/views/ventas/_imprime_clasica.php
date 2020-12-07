<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            body {
                font-family: sans-serif;
                background-color:#FFFFFF;
                font-size:9pt;
            }
            .header {
                 font-size:10pt;
            }
            #contenedor {
                margin-top: 20px;
                margin-bottom: 1px;
                margin-right: 0px;
                margin-left: 30px;
            }
            #print_area {
                border:0px;
            }
            .resolucion{
               font-size:8pt;
            }
            table {

            }
        </style>
    </head>
    <body>
        <div id="contenedor" >
            <div id="print_area" >
                <div id="ticket_header" >
                <br>
                  <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->
                     <table width="828">

                        <tr>
                            <td width="33%"  align="center" style=" font-size: 11px">
                                <?php if(!empty($data['data_empresa']['data']['logotipo'])) : ?>
                                    <img width="350px" height="100px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />
                                <?php endif; ?>
                            </td>
                            <td width="5%"  align="center">       
                            </td>
                            <td width="30%" align="center">
                                <?php echo $data['data_empresa']["data"]['cabecera_factura'];?>                   
                            </td>
                            <td width="80%" align="center">
                                <B><?php echo strtoupper($data['data_empresa']['data']['nombre']); ?></B> 
                                <br>   
                                <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
                                    <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>
                                    <?php echo isset($data['data_empresa']['data']['resolucion_factura']) ? $data['data_empresa']['data']['resolucion_factura'] : '';?><br/>
                                    <?php echo isset($data['data_almacen']['resolucion_factura']) ? $data['data_almacen']['resolucion_factura'] : '';?><br/>
                                
                                <?php } else { ?>
                                    <?php if($data['data_empresa']['data']['sistema'] == 'Pos') { ?>
                                            <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>

                                            <?php echo $data['data_empresa']['data']['resolucion'];?><br/>
                                    <?php } ?>
                                <?php } ?>
                                <?php  if(strlen($data['data_empresa']['data']['direccion']) > 2){ ?> <br/>
                                <?php  echo "<B>Direcci&oacute;n:</B> ".$data['data_empresa']['data']["direccion"]?><br/> 
                                <?php } ?>                  
                                <?php  if(strlen($data['data_empresa']['data']['telefono']) > 2){ ?>
                                <?php echo "<B>Tel&eacute;fono:</B> " . $data['data_empresa']['data']['telefono']; ?>  
                                <?php } ?>
                          </td>
                        <tr>  
                  </table>
                  <br>
                  <table width="818">
                      <tr>
                          <td ><hr width="100%"></td>
                      </tr>
                  </table>  
                  <br>
                  <table width="818">
                      <tr>                         
                          <td valign="top"  width="45%" style="border: 1px inset #000000; font-size: 14px" >
                              <?php echo "<B>Cliente: </B>" . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?><br>
                              <?php echo "<B>Nit / CC:</B> ".$data['venta']["nif_cif"]?><br>
                              <?php echo "<B>Email:</B> " . $data['venta']['email']; ?><br>
                              <?php echo "<B>Direcci&oacute;n:</B> ".$data['venta']["cliente_direccion"]?><br>
                              <?php echo "<B>Tel&eacute;fono: </B>".$data['venta']["cliente_telefono"]?><br>
                              <?php echo "<B>M&oacute;vil:</B> ".$data['venta']["cliente_movil"]; ?>
                          </td>
                          <td  width="14%" >
                          </td>           
                          <td valign="top" width="25%"  >
                              <table  style="border: 1px inset #000000; font-size: 14px">
                                  <tr>
                                      <td>
                                          <?php echo $data['venta']['ciudad'];?><br>
                                          <?php echo strtoupper($data['venta']['nombre'] );?><br>
                                          <?php echo "<b>FECHA:</b> <br>" . $data['venta']['fecha'] ?><br>

                                          <?php 
                                              if (!empty($data['venta']['fecha_vencimiento_venta']))
                                                  echo "<b>FECHA DE VENCIMIENTO:</b> <br>" . $data['venta']['fecha_vencimiento_venta'] 
                                          ?><br>
                                          <b><?php echo strtoupper($data['data_empresa']['data']['titulo_venta']).' '. $data['venta']['factura'] ?></b>
                                      </td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                  </table>   
                  <br>
                  <?php
                    $i=0;
                    foreach ($data["detalle_venta"] as $p) { 
                        if($p['descuento'] > 0){  $i=1;  } 
                    }          
                  ?> 
                  <?php if($i == 1){ ?>  
                  <table  width="818" style="border: inset 1px #000000; border-bottom: inset 1px #000000;" cellpadding="0" cellspacing="0">
                      <tr>
                          <th style="border: inset 1px #000000; font-size: 14px" align="center"  width="100px;"><?php echo "C&oacute;digo" ?></th>
                          <th style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Descripci&oacute;n " ?></th>            
                          <th style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Cant." ?></th>
                          <th style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Precio;" ?></th>
                          <th style="border: inset 1px #000000; font-size: 14px" align="center" width="100px;"><?php echo "Descuento;" ?></th>
                          <th style="border: inset 1px #000000; font-size: 14px" align="center" width="100px;"><?php echo "Total&nbsp;" ?></th>
                      </tr>
                  <?php } else { ?>  
                   <table  width="818" style="border: inset 1px #000000; border-bottom: inset 1px #000000;" cellpadding="0" cellspacing="0">
                        <th style="border: inset 1px #000000; font-size: 14px" align="center"  width="100px;"><?php echo "C&oacute;digo" ?></th>
                        <th style="border: inset 1px #000000; font-size: 14px" align="center"><?php echo "Descripci&oacute;n" ?></th>           
                        <th style="border: inset 1px #000000; font-size: 14px " align="center"><?php echo "Cant." ?></th>
                        <th style="border: inset 1px #000000; font-size: 14px" align="center" width="100px;"><?php echo "Vr. Unitario" ?></th>
                        <th style="border: inset 1px #000000; font-size: 14px" align="center" width="100px;"><?php echo "Vr. Total&nbsp;" ?></th>
                    </tr>
                  <?php  
                        }
                        
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
                              $precioUnitario=($p['precio_venta']+ ($p['precio_venta']* ($p['impuesto'] / 100)));
                              $desc = $p['descuento'];
                              $pvd = $pv - $desc;
                              $imp = $pv * $p['impuesto'] / 100 * $p['unidades'];
                              $total_column1 = $pvd * $p['unidades'];
                              $total_column = (($precioUnitario - $desc) * $p['unidades']);
                              $total_items += $total_column1;
                              $valor_total = $pvd * $p['unidades'] + $imp ;
                              $total += $total + $valor_total;
                              $timp+=$imp;
                          } else {
                               /* POS */
                              $pv = $p['precio_venta'];
                              $precioUnitario=($p['precio_venta']+ ($p['precio_venta']* ($p['impuesto'] / 100)));
                              $desc = $p['descuento'];
                              $pvd = $pv - $desc;
                             /* $imp = $pv * $p['impuesto'] / 100 * $p['unidades'];
                              $total_column1 = $pvd * $p['unidades'];
                              $total_column = (($precioUnitario - $desc) * $p['unidades']);
                              $total_items += $total_column1;*/
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
                            if($i == 1) {          
                        ?>  
                            <tr>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="left"><?php echo $p["codigo_producto"] ?></td>           
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="left"><?php echo $p["nombre_producto"] ?></td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $p["unidades"] ?>&nbsp;</td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p['precio_venta']); ?>&nbsp;</td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p['descuento']); ?>&nbsp;</td> 
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?>&nbsp;</td>   
                            </tr>
                        <?php
                            } else {          
                        ?>             
                            <tr>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="left"><?php echo $p["codigo_producto"] ?></td>            
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="left"><?php echo $p["nombre_producto"] ?></td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $p["unidades"] ?>&nbsp;</td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p['precio_venta']); ?>&nbsp;</td>
                                <td style="border-right: inset 1px #000000; font-size: 14px; padding:2px;" align="right"><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_column); ?>&nbsp;</td>   
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
                            <td style=" border-right: inset 1px #000000; font-size: 14px" ></td> 
                            <td  style=" border-right: inset 1px #000000; font-size: 14px" ></td> 
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
                    ?>        
                  </table><br>
   <?php  $total = $total_items + $timp; ?>          
                  <table  width=818 style=" font-size: 11px" cellpadding="0" cellspacing="0">
                     <tr>
                          <td style="font-size: 9px;  width: 65%;"></td>
                          <td  valign="top" style="border-left: inset 0px #000000; width: 13%;   font-size: 14px" align="right" >
                              &nbsp;&nbsp;&nbsp;<b><?php echo "sub-total" ?></b><br>
                              &nbsp;&nbsp;&nbsp;<b><?php  echo "IVA " ?></b><br> 
                              <?php foreach ($data["detalle_pago_multiples"] as $p) { ?>
                                  <?php if($p->forma_pago != 'efectivo') { ?>
                                    &nbsp;&nbsp;&nbsp;<b><?= strtolower(str_replace("_", " " ,$p->forma_pago)); ?></b><br>
                                  <?php } else { ?>
                                    &nbsp;&nbsp;&nbsp;<b><?= strtolower(str_replace("_", " " ,$p->forma_pago)); ?></b><br>
                                    &nbsp;&nbsp;&nbsp;<b>cambio</b><br>
                                <?php } ?>
                              <?php } ?>
                              <hr>
                              &nbsp;&nbsp;&nbsp;<b>Total a Pagar</b>
                          </td>
                          <td valign="top" align="right" style="font-size: 14px; margin-right: 0px;">
                              <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($total_items) ?>  <br>      
                              <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($timp) ?> <br>
                              <?php foreach ($data["detalle_pago_multiples"] as $p) { ?>
                                  <?php 
                                          if($p->forma_pago != 'efectivo') {
                                              echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado); 
                                          } else {
                                              echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado).' <br>';
                                              echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->cambio);
                                          }
                                      ?> <br>
                              <?php } ?>
                              <hr>
                              <?php echo $data['data_empresa']['data']['simbolo'].' '.number_format(round($total)); ?>   
                          </td>
                      </tr>
                  </table>
                  <table  width=860>
                      <tr>
                        <td><?php echo $data['venta']['nota'] ?></td>
                      </tr>
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


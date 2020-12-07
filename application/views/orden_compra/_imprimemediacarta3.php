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

                margin: 0px 50px 0 50px;

                width:100%;

            }

            
            table{
               /* width: 350px!important;*/
                  width: 792px!important;
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
                     <table >


                        <tr>

                            <td width="15%">

                                <?php if(!empty($data['data_empresa']['data']['logotipo'])) :?>

                                    <img width="200px" src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}");?>" />

                                <?php endif;?>

                            </td>

                            <td width="33%">

                                <?php echo strtoupper($data['venta']['nombre'] );?><br/>

                                <?php echo $data['data_empresa']["data"]['cabecera_factura'];?>

                                    

                            </td>

                            <td width="47%">

                                <p class='resolucion'>
                                    <strong>
                                      <?php echo strtoupper($data['data_empresa']['data']['nombre']); ?>
                                    </strong>
                                    <br>
                                    <span class="header">
                                        <?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?><br/>

                                        <?php echo $data['data_empresa']['data']['resolucion'];?><br/>

                                        <?php echo $data['venta']['direccion'] ?><br/>

                                        <?php echo $data['venta']['telefono'] ?>
                                    </span>
                                  

                                </p>
                              

                            </td>

                        </tr>

                        <tr>

                            <td>

                                <strong>

                                    <?php echo strtoupper($data['data_empresa']['data']['titulo_venta']).' '. $data['venta']['factura'] ?> <br/>

                                    <?php echo "Fecha: " . $data['venta']['fecha'] ?>

                                </strong>

                            </td>

                            <td>

                              <BR>

                            </td>

                            <td>

                                <?php echo "Cliente " . strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?><br/>
                                <?php echo "C.C ".$data['venta']["nif_cif"]?><br/>

                                <?php /*echo "Email: ".$data['venta']["email"]*/ ?>
                                <?php echo "Direcci&oacute;n: ".$data['venta']["cliente_direccion"]?><br/>
                                <?php echo "Tel&eacute;fono: ".$data['venta']["cliente_telefono"]?>

                            </td>

                        </tr>

                    </table>

                <table style="border-bottom: 2px dotted #000; text-align:center;">

                    <tr style="border-bottom: 2px solid #000;">


                        <th width="10%"><?php echo "Ref" ?></th>

                        <th width="25%"><?php echo "Producto" ?></th>

                        <th width="10%"><?php echo "Descripción" ?></th>

                        <th width="10%"><?php echo "Cant" ?></th>

                        <th width="20%"><?php echo "Precio" ?></th>

                        <th width="15%"><?php echo "Desc" ?></th>

                        <th width="10%" style="text-align: right; "><?php echo "Total" ?></th>

                    </tr>

                    <tr>

                        <td colspan="7"><hr/></td>

                    </tr>

                    <?php

                        $total = 0;

                        $timp  = 0;

                        $subtotal = 0;

                        $total_items = 0;

                    $group_by_impuesto = array();
                    
                    foreach ($data["detalle_venta"] as $p) {

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

                            <td><?php echo $p["codigo_producto"] ?></td>
                            <td><?php echo $p["nombre_producto"] ?></td>

                            <td>
                                <?php 
                                    if(empty($p["descripcion_producto"] )) 
                                      echo $p["descripcion"];
                                    else
                                      echo $p["descripcion_producto"];   
                                ?>
                            </td>

                            <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                            <td style='text-align:right;'><?php echo number_format($p["precio_venta"]); ?></td>

                            <td style='text-align:center;'><?php echo $p['descuento']; ?></td>

                            <td style='text-align:right;'><?php echo number_format($total_column); ?></td>

                        </tr>

                        <?php

                    }

                    ?>

                        <tr>

                            <td colspan="7"><hr/></td>

                        </tr>

                        <tr style="border-top: 2px dotted #000;">

                            <?php  $total = $total_items + $timp; ?>

                        <td colspan="5" style='text-align:right;'><?php echo "Valor items" ?></td>

                        <td  style='text-align:right'><?php echo number_format($total_items) ?></td>

                    </tr>

                    <?php for ($i=0; $i < count($group_by_impuesto) ; $i++) { 
                       /* echo ' <tr>';
                            echo  '<td colspan="5" style="text-align:right;">'.$group_by_impuesto[$i]['impuesto_nombre'].'</td>';
                            echo  '<td  style="text-align:right;">'.number_format($group_by_impuesto[$i]['impuesto_valor']).'</td>';
                        echo ' </tr>';*/
                    } ?>

                    <tr>

                        <td colspan="5" style='text-align:right;'><?php echo "T.Impuestos" ?></td>

                        <td  style='text-align:right'><?php echo number_format($timp) ?></td>

                    </tr>

                    <tr>

                        <td colspan="5"><hr/></td>

                    </tr>    

                    <tr>

                        <td colspan="5" style='text-align:right;'><?php echo "Total venta" ?></td>

                        <td  style='text-align:right'><?php echo number_format($total); ?></td>

                    </tr>

                
                    <tr>

                        <td colspan="5"><br></td>

                    </tr>

                </table>

                <br/>

                 <div style="border-bottom: 2px solid #000; width: 90%; padding-bottom:8px;">
                    <?php echo "Vendedor:" . $data['venta']['vendedor'] ?></div>

                 <br/>

                 <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?></div>

            

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>


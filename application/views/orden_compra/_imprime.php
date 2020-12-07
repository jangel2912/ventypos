<div id="ticket_wrapper">

    <div id="ticket_header">

        <?php if(!empty($data['data_empresa']['data']['logotipo'])):?>

        <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="200" border="0" /></div>

        <?php endif;?>

        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>



        <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>

        <div id="heading"> <?php echo $data['data_empresa']["data"]['cabecera_factura'];?></div>


        <div id="company_almacen"><?php echo "Almacen:" . $data['venta']['nombre'] ?></div>

        <table id="ticket_company" align="center">

            <tr>

                <td style="width:65%;text-align: left;"><?php echo $data['venta']['direccion'] ?></td>

                <td style="width:35%;text-align: right;"><?php echo $data['venta']['telefono'] ?></td>				

            </tr>

        </table>			

        <table id="ticket_factura" align="center">

            <tr>

                <td style="width:45%;text-align: left;"><?php echo "Factura de venta:" . $data['venta']['factura'] ?></td>

                <td style="width:55%;text-align: right;"><?php echo "Fecha:" . $data['venta']['fecha'] ?></td>				

            </tr>

        </table>			

        <div id="customer"><?php echo "Cliente:" . ($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"] . ' <br> CC ' . $data['venta']["nif_cif"]) ?></div>

      <!--  <div id="customer">Direcci&oacute;n: <?php //echo $data['venta']['direccion'] ?></div>-->

        <div id="customer">Tel&eacute;fono:<?php echo $data['venta']['cliente_telefono'] ?></div>

        <div id="seller"><?php echo "Vendedor:" . $data['venta']['vendedor'] ?></div>

    </div>

 

    <table id="ticket_items">

        <tr>

            <th style="width:20%;text-align: left;"><?php echo "Ref" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;"><?php echo "Precio" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Desc" ?></th>

            <th style="width:20%;text-align:right;"><?php echo "Total" ?></th>

        </tr>

        <?php

            $total = 0;

            $timp  = 0;

            $subtotal = 0;

            $total_items = 0;

            /*$group_by_impuesto = array();*/

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

                   /*  $group_by_impuesto_length= count($group_by_impuesto);

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
                        }*/


            ?>

            <tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>

            <tr>

                <td><?php echo $p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;'><?php echo number_format($p["precio_venta"]); ?></td>

                <td style='text-align:center;'><?php echo $p['descuento']; ?></td>

                <td style='text-align:right;'><?php echo number_format($valor_total); ?></td>

            </tr>

            <?php

        }

        ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Valor items" ?></td>

            <?php  $total = $total_items + $timp; ?>

            <td  style='text-align:right'><?php echo number_format($total_items) ?></td>

        </tr>

        <?php /*for ($i=0; $i < count($group_by_impuesto) ; $i++) { 
            echo ' <tr>';
                echo  '<td colspan="4" style="text-align:right;">'.$group_by_impuesto[$i]['impuesto_nombre'].'</td>';
                echo  '<td  style="text-align:right;">'.number_format($group_by_impuesto[$i]['impuesto_valor']).'</td>';
            echo ' </tr>';} */
        ?>


        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Impuestos" ?></td>

            <td  style='text-align:right'><?php echo number_format($timp) ?></td>

        </tr>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Total venta" ?></td>

            <td  style='text-align:right'><?php echo number_format($total); ?></td>

        </tr>

<?php if($data['detalle_pago']['forma_pago']!='efectivo'){  ?>
                              
                    <tr>

                        <td colspan="4" style='text-align:right;' >Forma de pago</td>

                        <td  style='text-align:right'>

                            <p><?php echo $data['detalle_pago']['forma_pago'] ?></p></td>

                    </tr>
<?php } ?>
<?php if($data['detalle_pago']['forma_pago']=='efectivo'){  ?>

                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo "Efectivo" ?></td>

                        <td  style='text-align:right'><?php echo number_format($data['detalle_pago']['valor_entregado']) ?>
                        </td>

                    </tr>  

                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo "Cambio" ?></td>

                        <td  style='text-align:right'><?php echo number_format($data['detalle_pago']['cambio']) ?>
                        </td>

                    </tr>    

<?php } ?>

        <tr>

            <td colspan="5">&nbsp;</td>

        </tr>

        <?php 

          /* $efe   = 0;

           $otros = 0;

           foreach ($data["formas_pago"] as $f):

               if ($f["forma_pago"] == 1)

                   $efe += $f["valor_entregado"];

               else 

                   $otros += $f["valor_entregado"];

        ?>

        <tr>

            <td colspan="4"><?php echo $f["descripcion"]; ?></td>

            <td  style='text-align:right'><?php echo number_format($f['valor_entregado']); ?></td>

        </tr>

        <?php endforeach; ?>

        <?php if ($efe > 0) { 

           $cambio = ($total - $otros - $efe) * -1;

        ?>

           <tr>

                <td colspan="4"><?php echo "Cambio" ?></td>

                <td  style='text-align:right'><?php echo number_format($cambio); ?></td>

            </tr> 

        <?php } */?>

        

    </table>



    <div align="center"><?php echo  $data['data_empresa']['data']['resolucion'];//nl2br($data['resolucion']); ?></div>

         <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
                </div>


    <br/><br/>



</div>
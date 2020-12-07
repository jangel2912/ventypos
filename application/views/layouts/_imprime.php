<div id="ticket_wrapper">



    <div id="ticket_header">

        <!--<div align="center"><img src="logo_ticket.jpg" width="338" height="114" border="0" /></div>-->

        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>

        <div id="company_nit"><?php echo  $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['resolucion']; ?></div>



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

        <div id="customer"><?php echo "Cliente:" . ($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"] . ' - ' . $data['venta']["nif_cif"]) ?></div>

      <!--  <div id="customer">Direcci&oacute;n: <?php //echo $data['venta']['direccion'] ?></div>-->

        <div id="customer">Tel&eacute;fono:<?php echo $data['venta']['telefono'] ?></div>

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

        foreach ($data["detalle_venta"] as $p) {



            $pv = $p['precio_venta'];

            $desc = $p['descuento'];

            $pvd = $pv - $desc;

            $imp = $p['impuesto'];

            $valor_total = round(($pvd + $imp) * $p['unidades']);

            $total = $total + $valor_total;

            $timp+=$imp;

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

            <td  style='text-align:right'><?php echo number_format($total - $timp) ?></td>

        </tr>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Impuestos" ?></td>

            <td  style='text-align:right'><?php echo number_format($timp) ?></td>

        </tr>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Total venta" ?></td>

            <td  style='text-align:right'><?php echo number_format($total) ?></td>

        </tr>

        

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



    <br/><br/>



</div>
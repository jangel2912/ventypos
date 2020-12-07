<?php

$total = 0;

    $timp  = 0;

    $subtotal = 0;

    $total_items = 0;

    $html_tbody='';

    foreach ($data['venta_credito']["detalle_venta"] as $p) {

        $pv = $p['precio_venta'];

        $desc = $p['descuento'];

        $pvd = $pv - $desc;

        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

        $total_column = $pvd * $p['unidades'];

        $total_items += $total_column;

        $valor_total = $pvd * $p['unidades'] + $imp ;

      $total = $total + $valor_total;

        $timp+=$imp;

      if($p["nombre_producto"] != 'PROPINA'){
        $html_tbody = $html_tbody." 
		<tr>

           <td style='text-align:left;'>".$p["nombre_producto"] ."</td>
		   		
        </tr>		
		<tr>

           <td>".$p["codigo_producto"]."</td>

           <td style='text-align:center;'>".$p["unidades"] ."</td>

           <td style='text-align:right;'>".number_format($p["precio_venta"])."</td>

           <td style='text-align:center;'>". $p['descuento']."</td>

           <td style='text-align:right;'>".number_format($valor_total)."</td>
       
        </tr>";
       }
    }

    $pagos=0;

    foreach ($data['data'] as $row){
        $pagos = $pagos+ $row->valor_entregado;
    }


?>
<style media="print">
    body{
        font-size: 12pt;
    }
    table td 
    {
        font-size: 12pt;
    }
</style>
<div id="ticket_wrapper">
    
    <div id="ticket_header">


            <table  width="818" > 

                <tr>                    
                    <td style="width: 40%; font-size: 12pt;"><?php echo "<strong>Cliente: </strong>" . $data['venta_credito']['venta']['nombre_comercial'] ?></td> 
                    <td style="width: 40%; font-size: 12pt;"><?php echo "<strong>Cedula: </strong>" . $data['venta_credito']['venta']['nif_cif'] ?></td> 
                    <td style="width: 40%; font-size: 12pt;"><?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?></td> 
                    <td style="width: 30%; font-size: 12pt;"><?php echo "<strong>Plan separe</strong>" ?></td> 
                    <td style="width: 30%; font-size: 12pt;"><?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?></td>                                     
                </tr>			
                <tr>
                    <td  ><strong><?php echo "Total:" ?></strong> <?php echo number_format($total); ?></td>
                    <td  ><strong><?php echo "Total pagos:" ?></strong> <?php echo number_format($pagos); ?></td>
                    <td  ><strong><?php echo "Saldo:" ?></strong> <?php echo number_format($total-$pagos ); ?> &nbsp;&nbsp;
                        <?php if(($total-$pagos)=='0'){ 
                            echo "<strong>Factura paga</strong>";
                        } ?>
                    </td>
                    <td><strong><?php echo "Nota:" ?></strong>  <?= $data["nota_plan_separe"]; ?></td>  
                </tr>

            </table> 

           <div class="head blue" align="center">

                <div class="icon"><i class="ico-files"></i></div>

                <h3><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h3>

            </div>

                    <table cellpadding="4" cellspacing="0" width="818">

                        <thead>

                            <tr>
                                <th width="43%" align="left"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="30%" align="left"><?php echo custom_lang('sima_type', "Tipo");?></th>
                                <th width="25%" align="right"><?php echo custom_lang('sima_amount', "Cantidad");?></th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($data['data'] as $row):?>

                            <tr>
                                <td><?php echo $row->fecha; ?></td>
                                <td><?php $formpago=str_replace("_"," ",$row->forma_pago);   echo ucfirst($formpago);?></td>
                                <td align="right"><?php echo number_format($row->valor_entregado);?></td>
                            </tr>

                            <?php endforeach;?>                         

                        </tbody>

                    </table>


                    <table cellpadding="4" cellspacing="0" width="818">
                        <thead>
                            <tr>
                                <th  align="left"><br /></th><th  align="right"></th>
                                <th  align="right"></th><th  align="right"></th>
                                <th  align="right"></th>
                            </tr>
                            <tr>
                                <th  align="left">Ref</th>
                                <th  align="right">Cantidad</th>
                                <th  align="right">Precio de venta</th>
                                <th  align="right">Descuento</th>
                                <th  align="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $html_tbody;  ?>
                        </tbody>
                    </table>
</div>

<script type="text/javascript">

    window.print();

</script>



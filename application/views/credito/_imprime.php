<?php
$ci =&get_instance();
$ci->load->model('Opciones_model');

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


        $html_tbody = $html_tbody." <tr>

           <td>".$p["codigo_producto"]."</td>

           <td >".$p["nombre_producto"] ."</td>

           <td style='text-align:center;'>".$p["unidades"] ."</td>

           <td style='text-align:right;'>".$ci->opciones_model->formatoMonedaMostrar($p["precio_venta"])."</td>

           <td style='text-align:center;'>". $ci->opciones_model->formatoMonedaMostrar($p['descuento'])."</td>

           <td style='text-align:right;'>".$ci->opciones_model->formatoMonedaMostrar($valor_total)."</td>
       
        </tr>";

    }

    $pagos=0;

    foreach ($data['data'] as $row){
        $pagos = $pagos+ $row->cantidad + $row->importe_retencion;
    }


?>

<div id="ticket_wrapper">

    <div id="ticket_header">


            <table  width="818">

                <tr>                    
                    <td style="width: 40%;"><?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?></td> 
                    <td style="width: 30%;"><?php echo "<strong>Factura No: </strong>" . $data['venta_credito']['venta']['factura'] ?></td> 
                    <td style="width: 30%;"><?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?></td>                                     
                </tr>			
                <tr>
                    <td  ><strong><?php echo "Total:" ?></strong> <?php echo $ci->opciones_model->formatoMonedaMostrar($total); ?></td>
                    <td  ><strong><?php echo "Total pagos:" ?></strong> <?php echo $ci->opciones_model->formatoMonedaMostrar($pagos); ?></td>
                    <td  ><strong><?php echo "Saldo:" ?></strong> <?php echo $ci->opciones_model->formatoMonedaMostrar($total-$pagos ); ?> &nbsp;&nbsp;
						<?php if(($total-$pagos)=='0'){ 
					echo "<strong>Factura paga</strong>";
					 } ?>				</td>
					</td>
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
                                <th width="25%" align="right"><?php echo custom_lang('sima_amount', "Retención");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($data['data'] as $row):?>

                            <tr>

                                <td><?php echo $row->fecha_pago; ?></td>
								
                                <td><?php $formpago=str_replace("_"," ",$row->tipo);   echo ucfirst($formpago);?></td>

                                <td align="right"><?php echo $ci->opciones_model->formatoMonedaMostrar($row->cantidad);?></td>
                                <td align="right"><?php echo $ci->opciones_model->formatoMonedaMostrar($row->importe_retencion);?></td>

                            </tr>

                            <?php endforeach;?>                         

                        </tbody>

                    </table>



</div>

<script type="text/javascript">

    window.print();

</script>



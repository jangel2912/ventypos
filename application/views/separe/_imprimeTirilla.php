<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>
        <style>
            .ticket_items td{
                padding: 4px !important;
            }
        </style>
    </head>

    <body>
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
           <td style='text-align:left;' colspan='4'>".$p["nombre_producto"]."-".$p["codigo_producto"]."</td>	   		
        </tr>		
        <tr>
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
<div id="ticket_wrapper">

    <div id="ticket_header">
        <?php if(!empty($data['data_empresa']['data']['logotipo'])) { ?>
        <?php if($data['data_empresa']['data']['nit'] != '900590001-2' && $data['data_empresa']['data']['nit'] != '6466096-9'){ ?>
        <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="150" border="0" /></div><?php } 
            if($data['data_empresa']['data']['nit'] == '900590001-2' || $data['data_empresa']['data']['nit'] == '6466096-9'){?>
                <div align="center" style="margin-top: 2px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="65" border="0" /></div>
            <?php } ?>
        <?php } ?>
        <div class="empresa" id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
        <div align="center" style="font-size: 12pt; ">
            <?php echo "<strong>Plan separe</strong>" ?>
        </div>
        <div align="center">
            <?php echo "<strong>Cliente: </strong>" . $data['venta_credito']['venta']['nombre_comercial'] ?>
        </div>
        <div align="center">
            <?php echo "<strong>Cedula: </strong>" . $data['venta_credito']['venta']['nif_cif'] ?>
        </div>
        <div align="center">
            <?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?>
        </div>
        <div align="center" style="margin-top: 5px;">
            <?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?>
        </div>
        <div align="center" style="margin-top: 5px;">
            <strong><?php echo "Total:" ?></strong> <?php echo number_format($total); ?>
        </div>
        <div align="center" style="margin-top: 5px;">
            <strong><?php echo "Total pagos:" ?></strong> <?php echo number_format($pagos); ?>
        </div>
        <div align="center" style="margin-top: 5px;">
            <strong><?php echo "Saldo:" ?></strong> <?php echo number_format($total-$pagos ); ?>
        </div>
        <div align="center" style="margin-top: 5px;">
            <strong><?php echo "Nota:" ?></strong>  <?= $data["nota_plan_separe"]; ?>
        </div>
        
        <div class="head blue" align="center">

            <div class="icon"><i class="ico-files"></i></div>

            <h3><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h3>

        </div>
        <center>
            <table cellpadding="4" cellspacing="0">
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
        
            <table cellpadding="4" cellspacing="0">
                <thead>
                    <tr>
                        <th  align="left"><br /></th><th  align="right"></th>
                        <th  align="right"></th><th  align="right"></th>
                        <th  align="right"></th>
                    </tr>
                    <tr>
                        <th  align="right" width="10%">Cantidad</th>
                        <th  align="right" width="30%">Precio de venta</th>
                        <th  align="right" width="30%">Descuento</th>
                        <th  align="right" width="30%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_tbody;  ?>
                </tbody>
            </table>
        </center> 
        <br/><br/>
        <?php if($data['publicidad_vendty'] == 1)
        {
            ?>
            <div align="center">Software POS Cloud: Vendty.com</div>
            <?php
        }?>
        <br/>
    </div>
<style>
    body
    {
        //font-size: 8pt !important;
        padding: 0px !important;
        margin: 0px !important;
    }
    .pequeño
    {
        font-size: 8pt !important;
    }
    #ticket_items td
    {
        padding: 0.5px !important;
    }
</style>
<script type="text/javascript">

    window.print();

</script>



    </body>

</html>


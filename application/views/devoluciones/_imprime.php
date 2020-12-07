<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");

$redondear_precios=get_option("redondear_precios");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>
        <style>
            body{
                font-size: 11pt !important;
            }
        </style>
    </head>

    <body>

        <div id="contenedor">

            <div id="print_area">

<div id="ticket_wrapper">

    <div id="ticket_header">
        <?php
        if(!empty($data['data_empresa']['data']['logotipo'])) {// die("bn");
            if($data['data_empresa']['data']['nit'] != '900590001-2' && $data['data_empresa']['data']['nit'] != '6466096-9')
            { 
                ?>
                <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="150" border="0" /></div>
                <?php
            }
            if($data['data_empresa']['data']['nit'] == '900590001-2' || $data['data_empresa']['data']['nit'] == '6466096-9')
            {
                ?>
                <div align="center" style="margin-top: 2px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="65" border="0" /></div>
                <?php
            }
        }
        ?>
        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
        
        <table id="ticket_factura" align="center">
            <tr>
                <td colspan="2">Devolución No. <?php echo  $data['devolucion']->id ?></td>
            </tr>
            <tr>

                <td style="width:45%;text-align: left;"><?php echo "Factura Devolución: " . $data['devolucion']->factura ?></td>

                <td style="width:55%;text-align: right;"><?php echo "Fecha: " . $data['devolucion']->fecha ?></td>				

            </tr>

        </table>			
        
        <?php 
        if($data['cliente'])
        {
        ?>
            <div id="customer"><?php echo "Cliente:" . ($data['cliente']['nombre_comercial'] == "" ? "Mostrador" : $data['cliente']["nombre_comercial"] . ' <br> ' .$data['cliente']["tipo_identificacion"]. ': '. $data['cliente']["nif_cif"]) ?></div>
            <?php if( strlen(trim($data['cliente']['direccion'])) > 0) { ?>
                <div id="customer">Direcci&oacute;n: <?php echo $data['cliente']['direccion'] ?></div>
            <?php } ?>
            <?php if( strlen(trim($data['cliente']['telefono'])) > 0 || strlen(trim($data['cliente']['telefono'])) > 0 ) { ?>
                <div id="customer">Tel&eacute;fono:<?php echo ' '.$data['cliente']['telefono'].' '.$data['cliente']['movil'] ?></div>
            <?php } ?>
        <?php } ?>
    
        <table id="ticket_items">

            <tr>

                <th style="width:20%;text-align: left;"><?php echo "Ref" ?></th>

                <th style="width:20%;text-align:center;"><?php echo "Cant" ?></th>

                <th style="width:20%;text-align:right;" ><?php echo "Precio" ?></th>

                <th style="width:20%;text-align:right;" colspan="2"><?php echo "Total" ?></th>

            </tr>				 
				 
        <?php

        $total = 0;
        $detalle  = $data['detalle_devolucion']['detalle'];
        foreach ($detalle as $p)
        {
            $total += $p->total_inventario;
            ?>
            <tr><td colspan="5" style='text-align:left;'><?php echo $p->nombre ?></td></tr>
            <tr>
                <td>&nbsp;</td>
                <td style='text-align:center;'><?php echo $p->cantidad ?></td>
                <?php if($redondear_precios==0){ ?>
                    <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->formatoMonedaMostrar($p->precio_compra); ?></td>
                    <td colspan="2" style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->formatoMonedaMostrar($p->total_inventario); ?>
                <?php }else{ ?>
                    <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->redondear($p->precio_compra); ?></td>
                    <td colspan="2" style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->redondear($p->total_inventario); ?></td>
                <?php } ?>                
            </tr>			 	
            <?php
        }
        ?>	
            
        <tr>
            <td colspan="4" style='text-align:right;'><?php echo "Total devolución" ?></td>
            <?php if($redondear_precios==0){ ?>
                <td style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->formatoMonedaMostrar( $total ); ?></td>
            <?php }else{ ?>
                <td style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->redondear( $total ); ?></td>
            <?php } ?>
            
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <?php //var_dump($data['nota_credito']);
        if(count($data['nota_credito']) != 0)
        {
            ?>
            <tr>
                <td colspan="5" style='text-align:center'>Nota Crédito</td>
            </tr>
            <tr>
                <td colspan="2" style='text-align:left'>Codigo:</td>
                <td colspan="1" style='text-align:right'><?php echo $data['nota_credito']->consecutivo ?></td>
                <td colspan="2" style='text-align:left'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style='text-align:left'>Valor:</td>
                <td style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo']."<font color='#ffffff'>_</font>".$ci->opciones_model->formatoMonedaMostrar($data['nota_credito']->valor) ?></td>
                <td colspan="2" style='text-align:left'>&nbsp;</td>                
            </tr>
            <tr>
                <td colspan="2" style='text-align:left'>Estado:</td>
                <td style='text-align:right'><?php echo ($data['nota_credito']->estado == 1) ? "Sin Redimidir":"Redimido" ?></td>
                <td colspan="2" style='text-align:left'>&nbsp;</td>                
            </tr>
            <?php
        }
        ?>

    </table>

    <div align="center" style="padding-bottom:-10px;">
        Observaciones<br>
        ______________________________________________________<br>
        ______________________________________________________<br>
        ______________________________________________________<br>
        <br/>	
    </div>
    <br/><br/>
</div>
            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>
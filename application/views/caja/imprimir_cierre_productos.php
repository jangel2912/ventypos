<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>
        
        <style>
            * {
                font-size: 10px;
            }
        </style>

    </head>

    <body>

        <div id="contenedor">

            <div id="print_area">

<div id="ticket_wrapper">

    <div id="ticket_header">

        <?php if(!empty($data['data_empresa']['data']['logotipo'])):?>

        <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="200" border="0" /></div>

        <?php endif;?>

        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>



        <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>

        <div id="heading"> <?php echo $data['data_empresa']["data"]['cabecera_factura'];?></div>
    
        <table id="ticket_company" align="center">

            <tr>

               <td style="width:65%;text-align: center;"><?php echo $data['data_empresa']['data']['direccion']; ?> <?php // echo "Dir: ".$data['venta']['direccion'] ?></td>
               <!-- <td style="width:65%;text-align: left;"><?php // echo "Dir: ".$data['venta']['direccion'] ?></td> -->
               <!-- aterior <td style="width:35%;text-align: right;"><?php echo "Telf: ".$data['venta']['telefono'] ?></td>	 -->			

            </tr>

            <tr>
                     <td style="width:65%;text-align: center;"><?php echo "Telf: ".$data['data_empresa']['data']['telefono']; ?> <?php // echo "Dir: ".$data['venta']['direccion'] ?></td>
            </tr>

        </table>			
 
        <table id="ticket_items" align="center">
            <thead>
                <tr>
                    <th>
                        Código, Ref
                    </th>
                    <th>
                        Cant
                    </th>
                    <th>
                        Precio
                    </th>
                </tr>
            </thead>
            <tbody>
                    <?php $total_factura = 0?>
                    <?php $cantidad_factura = 0?>
                    <?php foreach ($data['cierres_productos'] as $cierre_producto_items) { ?>
                        <?php foreach ($cierre_producto_items as $cierre_producto_item) { ?>
                <tr>
                    <td align="left">
                        <b style="size: 2em"><?php echo $cierre_producto_item[6] ?></b>,
                        <?php echo substr($cierre_producto_item[8], 0, 15) ?>
                    </td>
                    <td><?php echo $cierre_producto_item[7] ?></td>
                    <td><?php echo number_format($cierre_producto_item[9]) ?></td>
                </tr>
                            <?php $cantidad_factura = $cantidad_factura + $cierre_producto_item[7] ?>
                            <?php $total_factura = $total_factura + $cierre_producto_item[9] ?>
                        <?php } ?>
                    <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    <th><?php echo $cantidad_factura ?></th>
                    <th><?php echo number_format($total_factura) ?></th>
                </tr>
            </tfoot>
        </table>

    <div align="center"><?php echo  $data['data_empresa']['data']['resolucion'];//nl2br($data['resolucion']); ?></div>

         <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
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
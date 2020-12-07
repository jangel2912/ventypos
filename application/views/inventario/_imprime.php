<!DOCTYPE html>
<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style>

            body{

                font-family: sans-serif;

                background-color:#FFFFFF;

                font-size:7pt;

            }

            .header{

                 font-size:10pt;

            }

            #contenedor{

    margin-top: 20px;
    margin-bottom: 1px;
    margin-right: 4px;
    margin-left: 20px;

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


<table id="ticket_company" align="center">
    <tr>
        <td style="width:35%;text-align: left;">
	<div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
   <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>
   <div id="company_nit">Dirección: <?php echo $data['data_empresa']['data']['direccion']?></div>
   <div id="company_nit">Teléfono: <?php echo $data['data_empresa']['data']['telefono']?></div>
   <div id="company_nit">Web: <?php echo $data['data_empresa']['data']['web']?></div>
   </td>
   
 <td style="width:65%;text-align: center;">
 <?php if($data['movimiento']['tipo_movimiento'] == 'traslado'): ?>
 <div id="company_almacen"><strong>Traslado de mercancias</strong></div>   
 <?php elseif ($data['movimiento']['tipo_movimiento'] == 'entrada_compra') :?>
 <div id="company_almacen"><strong>Traslado de compra</strong></div>
 <?php else:?>
 <div id="company_almacen"><strong>Movimiento de inventario</strong></div>
 <?php endif;?> 
 </td>	   
   	
 <td style="width:65%;text-align: right;">
  <?php if(!empty($data['data_empresa']['data']['logotipo'])):?> 
	<img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="130" height="80" border="0" />
	<?php endif;?>
	</div>
   </td>				
    </tr>	
</table>
<hr>

<?php if($data['movimiento']['tipo_movimiento'] == 'traslado'): ?>
<table id="ticket_company" align="center">
    <tr>
        <td style="width:75%;text-align: left;"><strong>Numero de documento:</strong> <?php echo $data['movimiento']['id'] ?></td>
        <td style="width:65%;text-align: left;"><strong>Fecha</strong> <?php echo $data['movimiento']['fecha'] ?></td>				
    </tr>
    <tr>
        <td style="width:75%;text-align: left;"><strong>Almacen Origen:</strong> <?php echo $data['movimiento']['almacen_origen'] ?></td>
        <td style="width:65%;text-align: left;"><strong>Almacen Traslado:</strong> <?php echo $data['movimiento']['almacen_traslado'] ?></td>				
    </tr>
</table>
<?php elseif ($data['movimiento']['tipo_movimiento'] == 'entrada_compra') :?>
<table id="ticket_company" align="center">
    <tr>
        <td style="width:75%;text-align: left;"><strong>Numero de documento:</strong> <?php echo $data['movimiento']['id'] ?></td>
        <td style="width:65%;text-align: left;"><strong>Fecha</strong> <?php echo $data['movimiento']['fecha'] ?></td>				
    </tr>
    <tr>
        <td style="width:75%;text-align: left;"><strong>Proveedor:</strong> <?php echo $data['movimiento']['nombre_comercial'] ?></td>
        <td style="width:65%;text-align: left;"><strong>No. Factura:</strong> <?php echo $data['movimiento']['codigo_factura'] ?></td>				
    </tr>
</table>
<?php else:?>
<table id="ticket_company" align="center">
    <tr>
        <td style="width:75%;text-align: left;"><strong>Numero de documento:</strong> <?php echo $data['movimiento']['id'] ?></td>
        <td style="width:65%;text-align: left;"><strong>Fecha</strong> <?php echo $data['movimiento']['fecha'] ?></td>				
    </tr>
    <tr>
        <td style="width:75%;text-align: left;"><strong>Tipo de movimiento:</strong> <?php echo $data['movimiento']['tipo_movimiento'] ?></td>
        <td style="width:65%;text-align: left;"></td>				
    </tr>
    <tr>
        <td style="width:75%;text-align: left;"><strong>Nota:</strong> <?php echo $data['movimiento']['nota'] ?></td>
        <td style="width:65%;text-align: left;"></td>				
    </tr>
</table>
<?php endif;?>
<hr>
    <table width="100%">
        <tr>
            <th style="text-align: left;">C&oacute;digo de Barra</th>
            <th style="text-align: left;">Nombre</th>
            <th style="text-align: left;">Cantidad</th>
            <th style="text-align: right;">Precio compra</th>
            <th style="text-align: right;">Subtotal</th>
        </tr>
        <?php 
            $total = 0;
            $total_cantidad = 0;
        ?>
            
        <?php foreach ($data['detalle_movimiento'] as $row):
            $total_cantidad+=$row['cantidad'];
        ?>
        <tr>
            <td style="text-align: left;"><?php echo $row['codigo_barra'];?></td>
            <td style="text-align: left;"><?php echo $row['nombre'];?></td>
            <td style="text-align: left;"><?php echo $row['cantidad'];?></td>
            <td style="text-align: right;"><?php echo $row['precio_compra'];?></td>
            <td style="text-align: right;"><?php $total += $row['total_inventario']; echo number_format($row['total_inventario']);?></td>
        </tr>
        <?php endforeach;?>
	    <tr>
            
        </tr>	
        <tr>
            <th style="text-align: left;"></th>
            <th style="text-align: left;">Total productos</th>
            <th style="text-align: left;"><?php echo $total_cantidad; ?></th>
            <th style="text-align: right;">Total</th>
            <th style="text-align: right;"><?php echo number_format($total);?></th>
        </tr>
	        <tr>
            <th colspan="4" style="text-align: right;"><br /><br /></th>
            <th style="text-align: right;"></th>
        </tr>		
    </table>

<br><br>
    <table width="100%">
        <tr>
            <th style="text-align: center;" width="50%">____________________________________<br>Firma de quien despacha</th>
            <th style="text-align: center;" width="50%">____________________________________<br>Firma de quien recibe</th>
        </tr>		
    </table>


    </body>

</html>

<script type="text/javascript">

    window.print();

</script>

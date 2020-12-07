
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>

    </head>

    <body>

    <?php 
        $ci =&get_instance();
        $ci->load->model('opciones_model');
    
    ?>

        <div id="contenedor">

            <div id="print_area">

<div id="ticket_wrapper">

    <div id="ticket_header">

        <?php if(!empty($data['data_empresa']['data']['logotipo'])):?>

        <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="200" border="0" /></div>

        <?php endif;?>
        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
        <table id="ticket_company" align="center">
            <tr>
                <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
                <td id="company_nit"><b><?php echo $data['data_empresa']['data']['documento'].":</b> " . $data['data_almacen']['nit']; ?></td>
                <?php } else { ?>
                    <?php if($data['data_empresa']['data']['sistema'] == 'Pos') { ?>
                        <td id="company_nit"><b><?php echo $data['data_empresa']['data']['documento'].":</b> " . $data['data_empresa']['data']['nit']; ?></td>
                    <?php } ?>
                <?php } ?>
            </tr>
            <tr>
               <td id="heading" style="width:65%;text-align: center;"><?php echo $data['data_empresa']["data"]['cabecera_factura'];?></td>
            </tr>
            <tr>
               <td style="width:65%;text-align: center;"><?php echo "<b>Almacen:</b>" . $data['venta']['nombre'] ?></td>
            </tr>
            <tr>
               <td style="width:65%;text-align: center;"><?php echo $data['data_empresa']['data']['direccion']; ?> </td>
            </tr>
            <tr>
                <td style="width:65%;text-align: center;"><?php echo "<b>Telf:</b> ".$data['data_empresa']['data']['telefono']; ?></td>
            </tr>
        </table>			

        <table id="ticket_factura" align="center">
            <tr>
                <td style="text-align: left;"><b><?php echo $data['data_empresa']['data']['titulo_venta'] .":</b> " . $data['venta']['factura'] ?></td>
            </tr>
            <tr>
                <td style="text-align: left;"><?php echo "<b>Fecha:</b>" . $data['venta']['fecha'] ?></td>				
            </tr>
            <tr>
                <td style="text-align: left;"><?php echo "<b>Cliente:</b>" . ($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"] . ' <br> <b>'.$data['venta']['tipo_identificacion'].':</b> '. $data['venta']["nif_cif"]) ?></td>				
            </tr>            
            <tr>
                <td style="text-align: left;"><b>Tel&eacute;fono:</b><?php echo $data['venta']['cliente_telefono'] ?></td>				
            </tr>
            <tr>
                <td style="text-align: left;"><?php echo "<b>Vendedor:</b>" . $data['venta']['vendedor'] ?></td>				
            </tr>
            <tr>
                <td style="text-align: left;">
                    <?php  if($data['venta']['nota'] != ''){   ?>
                        <div id="seller"><?php echo "<b>Nota:</b> ".$data['venta']['nota'] ?></div>
                    <?php  }   ?>  
                </td>				
            </tr>
        </table>			

        <div id="seller"></div>
		
    </div>
 					 <?php  
					 
					 $i=0;
					foreach ($data["detalle_venta"] as $p) { 
							
					    if($p['descuento'] > 0){  $i=1;  } 
						
					}					 
					 ?>	
                  <?php  
					 if($i == 1){  				 
		         ?>	 
    <table id="ticket_items">

        <tr>

            <th style="width:25%;text-align: left;"><?php echo "Ref" ?></th>

            <th style="width:10%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;"><?php echo "Precio" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Desc" ?></th>
					
            <th style="width:25%;text-align:right;"><?php echo "Total" ?></th>

        </tr>
		           <?php  
				   }	  
					 else{  				 
		         ?>	 
    <table id="ticket_items">

        <tr>

            <th style="width:35%;text-align: left;"><?php echo "Ref" ?></th>

            <th style="width:10%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;" ><?php echo "Precio" ?></th>
			
            <th style="width:35%;text-align:right;" colspan="2"><?php echo "Total" ?></th>

        </tr>				 
		           <?php  
				   }			 
	            	?>	 				 
				 
				 
        <?php

            $total = 0;

            $timp  = 0;

            $subtotal = 0;

            $total_items = 0;
			
			$sobrecosto = 0;

            /*$group_by_impuesto = array();*/

        foreach ($data["detalle_venta"] as $p) {
      
                   if($p["nombre_producto"] == 'PROPINA'){  	
                      $sobrecosto = $p['descripcion_producto'];
                  }
                  else{

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


            ?>

                  <?php  
					 if($i == 1){  				 
		         ?>	 
            <tr>

                <td><?php echo $p["nombre_producto"].' '.$p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($p["precio_venta"]); ?></td>

                <td style='text-align:center;'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($p['descuento']); ?></td>

                <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($valor_total); ?></td>

            </tr>
                   <?php
                    } 
					else{  				 
		         ?>	
            
            <tr>

                <td><?php echo $p["nombre_producto"].' '.$p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($p["precio_venta"]); ?></td>

                <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($valor_total); ?></td>

            </tr>			 	
				   <?php
                    }
                    ?>	

            <?php
          }
        }

        ?>
       

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Subtotal" ?></td>

            <?php  $total = $total_items + $timp; ?>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($total_items) ?></td>

        </tr>

        <?php /*for ($i=0; $i < count($group_by_impuesto) ; $i++) { 
            echo ' <tr>';
                echo  '<td colspan="4" style="text-align:right;">'.$group_by_impuesto[$i]['impuesto_nombre'].'</td>';
                echo  '<td  style="text-align:right;">'.number_format($group_by_impuesto[$i]['impuesto_valor']).'</td>';
            echo ' </tr>';} */
			
			$propina = $sobrecosto;
			$propina_final = ($total_items * $propina) /100;
        ?>


        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "IAC" ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($timp) ?></td>

        </tr>
            
        <?php
        foreach ($data["detalle_pago_multiples"] as $p) {
        $formpago=str_replace("_"," ",$p->forma_pago); 
        if($p->forma_pago=='efectivo'){  ?>  
            <tr>
                <td colspan="4" style='text-align:right;'><?php echo ucfirst($formpago) ?></td>
                <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?></td>
            </tr>  
        <?php } if($p->forma_pago!='efectivo'){  ?>  
            <tr>
                <td colspan="4" style='text-align:right;'><?php echo ucfirst($formpago) ?></td>
                <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?></td>
            </tr>   
        <?php } } ?>
        
        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Propina" ?></td>

            <td  style='text-align:right'><?php 

			echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($propina_final);
			 ?></td>

        </tr>
            
        <tr>
            <td colspan="4" style='text-align:right;'><?php echo "Total venta" ?></td>
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].''.$ci->opciones_model->formatoMonedaMostrar($total + $propina_final); ?></td>
        </tr>		
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
    </table>
        <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
            <div align="center"><?php echo  $data['data_almacen']['resolucion_factura'];//nl2br($data['resolucion']); ?></div>
        <?php } else { ?>
            <?php if($data['data_empresa']['data']['sistema'] == 'Pos') { ?>
                <div align="center"><?php echo  $data['data_empresa']['data']['resolucion'];//nl2br($data['resolucion']); ?></div>
            <?php } ?>
        <?php } ?>
        
         <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
                </div>
        <br>
        <?php if($data['publicidad_vendty'] == 1)
        {
            ?>
            <div align="center">Software POS Cloud: Vendty.com</div>
            <?php
        }?>

    <br/><br/>
</div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    window.print();
</script>
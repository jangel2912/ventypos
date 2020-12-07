<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");
function toEnglish($type){
    switch($type){
        case 'Efectivo':
            echo 'Cash';
        break;
        case 'efectivo':
            echo 'Cash';
        break;
        case 'Credito':
            echo 'Credit';
        break;
        case 'Maestro Debito': 
            echo 'Debit Master';
            break;
        case 'MasterCard debito': 
            echo 'MasterCard Debit ';
            break;
        case 'MasterCard Credito': 
            echo 'MasterCard Credit';
            break;
        case 'tarjeta credito': 
            echo 'Credit card';
            break;
        case 'tarjeta debito': 
            echo 'Credit debit';
            break;
        default:
            echo $type;
    }
}
?>
<div id="ticket_wrapper">

    <div id="ticket_header">

        <?php if(!empty($data['data_empresa']['data']['logotipo'])) { ?>
        <?php if($data['data_empresa']['data']['nit'] != '900590001-2' && $data['data_empresa']['data']['nit'] != '6466096-9'){ ?>
        <div align="center" style="margin-top: 5px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="150" border="0" /></div>        <?php } 
            if($data['data_empresa']['data']['nit'] == '900590001-2' || $data['data_empresa']['data']['nit'] == '6466096-9'){?>
            <div align="center" style="margin-top: 2px;"><img src="<?php echo base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="65" border="0" /></div>
       <?php } ?>
              <?php } ?>
        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
        
        <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
            <div id="company_resolucion"><?php echo $data['venta']['resolucion_factura']; ?></div>
            <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['venta']['nit']; ?></div>
            <div id="company_razon_social"><?php echo $data['data_almacen']['razon_social']; ?></div>
        <?php } else { ?>
            <!--<div id="company_resolucion"><?php echo $data['data_empresa']['data']['resolucion']; ?></div>-->
            <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>
            <div id="company_razon_social"><?php echo $data['data_almacen']['razon_social']; ?></div>
        <?php } ?>

        <div id="heading"> <?php echo $data['data_empresa']["data"]['cabecera_factura'];?></div>


        <div id="company_almacen"><?php echo ($lang == 'en') ? 'Brand: ' : 'Almacen: '; echo $data['venta']['nombre'] ?></div>

        <table id="ticket_company" align="center">

            <tr>

                <td style="width:65%;text-align: left;"><?php echo $data['venta']['direccion'] ?></td>

                <td style="width:35%;text-align: right;"><?php echo $data['venta']['telefono'] ?></td>              

            </tr>

        </table>            

        <table id="ticket_factura" align="center">

            <tr>

                <td style="width:45%;text-align: left;"><?php echo $data['data_empresa']['data']['titulo_venta']. ": " . $data['venta']['factura'] ?></td>

                <td style="width:55%;text-align: right;"><?php echo ($lang == 'en') ? 'Date: ' : 'Fecha: '; echo  $data['venta']['fecha'] ?></td>

            </tr>

        </table>            
<?php //var_dump($data['venta']); ?>
        <div id="customer"><?php echo ($lang == 'en') ? 'Client: ' : 'Cliente: '; echo  ($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"] . ' <br> ' .$data['venta']["tipo_identificacion"]. ': '. $data['venta']["nif_cif"]) ?></div>
        <?php if( strlen(trim($data['venta']['cliente_direccion'])) > 0) { ?>
            <div id="customer"><?php echo ($lang == 'en') ? 'Address: ' : 'Direcci&oacute;n: '; echo $data['venta']['cliente_direccion'] ?></div>
        <?php } ?>
        <?php if( strlen(trim($data['venta']['cliente_telefono'])) > 0 || strlen(trim($data['venta']['cliente_movil'])) > 0 ) { ?>
            <div id="customer"><?php echo ($lang == 'en') ? 'Phone: ' : 'Tel&eacute;fono: '; echo ' '.$data['venta']['cliente_telefono'].' '.$data['venta']['cliente_movil'] ?></div>
        <?php } ?>
        <?php if( strlen(trim($data['venta']['cliente_email'])) > 0) { ?>
            <div id="customer"><?php echo ($lang == 'en') ? 'Email: ' : 'Correo electr&oacute;nico: '; echo ' '.$data['venta']['cliente_email'] ?></div>
        <?php } ?>   
        
<?php  $username = $this->session->userdata('username');

    if($data['data_empresa']['data']['vendedor_impresion'] == '1'){ ?>
        <div id="seller"><?php echo ($lang == 'en') ? 'Seller: ' : 'Vendedor: '; echo $data['venta']['vendedor'] ?></div>
 <?php  }   ?>          
<?php if($data['data_empresa']['data']['vendedor_impresion'] == '2'){ ?>
        <div id="seller"><?php echo ($lang == 'en') ? 'Seller: ' : 'Vendedor: '; echo @$data['username']; ?></div>
 <?php  }   ?>          
<?php if($data['data_empresa']['data']['vendedor_impresion'] == '3'){ ?>
        <div id="seller"><?php echo ($lang == 'en') ? 'Seller: ' : 'Vendedor: '; echo $data['venta']['vendedor'] ?></div>
        <div id="seller"><?php echo ($lang == 'en') ? 'User: ' : 'Usuario: '; echo $username ?></div>
 <?php  }   ?>          
        
        <?php  if($data['venta']['nota'] != ''){   ?>
        <div id="seller"><?php echo $data['venta']['nota'] ?></div>
        <?php  }   ?> 

        <?php  if(isset($data['venta']['consecutivo_orden']) &&  $data['venta']['consecutivo_orden'] != ''){   ?>
        <div id="seller"><?php echo 'No. Orden: '. $data['venta']['consecutivo_orden'] ?></div>
        <?php  }   ?>  
    </div>
            
 
                     <?php  
                     //print_r($data['venta']);die();     
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

            <th style="width:20%;text-align: left;">Ref:</th>

            <th style="width:20%;text-align:center;"><?php echo ($lang == 'en') ? 'Qty: ' : 'Cant: '; ?></th>

            <th style="width:20%;text-align:right;"><?php echo ($lang == 'en') ? 'Price: ' : 'Precio: '; ?></th>

            <th style="width:20%;text-align:center;"><?php echo ($lang == 'en') ? 'Disc: ' : 'Desc: '; ?></th>
                        
            <th style="width:20%;text-align:right;">Total:</th>

        </tr>
                   <?php  
                   }      
                     else{                   
                 ?>  
    <table id="ticket_items">

        <tr>

            <th style="width:20%;text-align: left;">Ref:</th>

            <th style="width:20%;text-align:center;"><?php echo ($lang == 'en') ? 'Qty: ' : 'Cant: '; ?></th>

            <th style="width:20%;text-align:right;" ><?php echo ($lang == 'en') ? 'Price: ' : 'Precio: '; ?></th>
            
            <th style="width:20%;text-align:right;" colspan="2">Total:</th>

        </tr>                
                   <?php  
                   }             
                    ?>                   
                 
                 
        <?php

            $total = 0;

            $timp  = 0;

            $subtotal = 0;

            $total_items = 0;
            
            $total_items_propina = 0;

            $sobrecosto = 0;

            $propina_final = 0;

            /*$group_by_impuesto = array();*/
                   //print_r($data["detalle_venta"]);
        foreach ($data["detalle_venta"] as $p) {
      
                   if($p["nombre_producto"] == 'PROPINA'){      
                      $sobrecosto = $p['descripcion_producto'];
                    if($sobrecosto > 0){                         
                        $propina_final = $p['precio_venta'];
                    }
                     
                  }
                  else{
                    
                    if($data["tipo_factura"]=='clasico'){
                         /* SERVICIOS */
                        $pv = $p['precio_venta'];
                        $desc = $p['descuento'];
                        $pvd = $pv - $desc;
                        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                        $total_column = $pvd * $p['unidades'];
                        $total_items += ($total_column);
                        $valor_total = $pvd * $p['unidades'] + $imp ;
                        //$total += $ci->opciones_model->redondear($valor_total);
                        $total += $valor_total;
                        $timp+=$imp;
                        //$total_column = $ci->opciones_model->redondear($total_column);
                        //$valor_total = $ci->opciones_model->redondear($valor_total);
                        //$imp = $ci->opciones_model->redondear($imp);
                        $p['precio_venta'] =  $ci->opciones_model->redondear($p['precio_venta']);
                    }else{
                         /* POS */
                        $pv = $p['precio_venta'];
                        $desc = $p['descuento'];
                        $pvd = $pv - $desc;
                        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                        $total_column = $pvd * $p['unidades'];
                        $total_items += ($total_column);
                        $valor_total = $pvd * $p['unidades'] + $imp;                        
                        //$total += $ci->opciones_model->redondear($valor_total);
                        $total += $valor_total;
                        $timp+=$imp;
                        //$valor_total = $ci->opciones_model->redondear($valor_total);
                        //$total_column = $ci->opciones_model->redondear($total_column);
                        //$imp = $ci->opciones_model->redondear($imp);
                        $p['precio_venta'] = $ci->opciones_model->redondear($p['precio_venta']);
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
               if( trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO'){

                        $pv_propina = $p['precio_venta'];
                        $desc_propina = $p['descuento'];
                        $pvd_propina = $pv_propina - $desc_propina;
                        $total_column_propina = $pvd_propina * $p['unidades'];
                        $total_items_propina += $total_column_propina;                                                             
               }

            ?>

                  <?php  
                     if($i == 1){                
                 ?>  
            <tr><td colspan="5">
                <?php echo substr($p["nombre_producto"], 0, 28); ?>        
            </td></tr>

            <tr>

                <td><?php echo $p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;'><?php echo $ci->opciones_model->formatoMonedaMostrar($p["precio_venta"]); ?></td>

                <td style='text-align:center;'><?php echo $ci->opciones_model->formatoMonedaMostrar($p['descuento']); ?></td>

                <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valor_total); ?></td>

            </tr>
                   <?php
                    } 
                    else{                
                 ?> 
            <tr><td colspan="5">
                <?php echo substr($p["nombre_producto"], 0, 28);?>   
                </td></tr>
            <tr>

                <td><?php echo $p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p["precio_venta"]); ?></td>

                <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valor_total); ?></td>

            </tr>               
                   <?php
                    }
                    ?>  

    <?php

        }
      }
      
        ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? 'Value: ' : 'Valor sin impuesto: '; ?></td>

            <?php  //$total = $total_items + $timp;var_dump($total);var_dump($total_items);var_dump($timp); ?>

            <!--<td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($ci->opciones_model->redondear($total_items)) ?></td>-->
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_items); ?></td>
        </tr>

  <?php  foreach ($data["venta_impuestos"] as $p) {  
  if($p->imp != ''){ 
  ?>
        <tr>

            <td colspan="4" style='text-align:right;'><?php echo $p->imp ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->impuestos) ?></td>

        </tr>

  <?php  
     }else{
  ?>
        <tr>

        <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? 'Vat: ' : 'Iva : '; ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->impuestos) ?></td>

        </tr>

  <?php  
     }
   
   }
            if($sobrecosto > 0 && $propina_final > 0){
        ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Propina" ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($propina_final);  ?></td>

        </tr>
        <?php 
            }
        ?>

<?php  
//print_r($data["detalle_pago_multiples"]); die();
foreach ($data["detalle_pago_multiples"] as $p) { 

if($p->forma_pago!="Sin_asignar_pago"){
    
 $formpago=str_replace("_"," ",$p->forma_pago); 
  if($p->forma_pago=='efectivo'){  ?>  
                    <tr>

                    <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? 'Cash: ' : ucfirst($formpago); ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$p->valor_entregado; ?>
                        </td>

                    </tr>
                       
 <?php } ?> 

 <?php if($p->forma_pago!='efectivo'){  ?>  
                    <tr>

                    <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? toEnglish($formpago) : ucfirst($formpago); ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?>
                        </td>

                    </tr>   
 <?php } ?>   
 
<?php } }?>

<?php  foreach ($data["detalle_pago_multiples_cambio"] as $p) { ?>

                    <tr>

                    <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? 'Change: ' : 'Cambio'; ?></td>

                        <!--<td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar( $p->total_cambio,-1) ?> </td>-->
                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '. $ci->opciones_model->formatoMonedaMostrar($p->total_cambio); ?> </td>

                    </tr>    
    
<?php } ?>

        <tr>

        <td colspan="4" style='text-align:right;'><?php echo ($lang == 'en') ? 'Total sale: ' : 'Total Venta'; ?></td>

            <!--<td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format( $ci->opciones_model->redondear( $total + $propina_final) ); ?></td>-->
            <!-- <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format( $ci->opciones_model->redondear( $total + $propina_final) ); ?></td> -->
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total + $propina_final); ?></td>
        </tr>
        <tr>

            <td colspan="5">&nbsp;</td>
        </tr>
    </table>
    <div align="center"><?php echo  $data['data_empresa']['data']['resolucion'];//nl2br($data['resolucion']); ?></div>

         <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
                <br/>   
            <?php         
//puntos --------------------------------------------------------------------------------------------------------------------------
            if($data["puntos_cliente_factura"] > 0){
        ?>
        Puntos por esta factura: <?php  echo $ci->opciones_model->formatoMonedaMostrar($data["puntos_cliente_factura"]); ?><br/>
        Puntos Acumulados: <?php  echo $ci->opciones_model->formatoMonedaMostrar($data["puntos_cliente_acumulado"]); ?>
        <?php 
            }         
//puntos --------------------------------------------------------------------------------------------------------------------------
        ?>
                
                </div>


    <br/>
    <?php if($data['publicidad_vendty'] == 1)
        {
            ?>
            <div align="center">Software POS Cloud: Vendty.com</div>
            <?php
        }?>
    <br/>



</div>
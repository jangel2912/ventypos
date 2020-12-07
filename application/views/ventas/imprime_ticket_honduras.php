<?php 
    $ci = &get_instance();
    $ci->load->model("opciones_model");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="<?= base_url("/public/css/ticket.css"); ?>" media="screen"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url("/public/css/ticket_print.css") ?>"  media="print"/>
    </head>

    <body>
        <div id="contenedor">
            <div id="print_area">
                <!-- invoice -->
                    <div id="ticket_wrapper">

                        <div id="ticket_header">

                            <?php if(!empty($data['data_empresa']['data']['logotipo'])) { ?>
                            <?php if($data['data_empresa']['data']['nit'] != '900590001-2' && $data['data_empresa']['data']['nit'] != '6466096-9'){ ?>
                            <div align="center" style="margin-top: 5px;"><img src="<?= base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="150" border="0" /></div>        <?php } 
                                if($data['data_empresa']['data']['nit'] == '900590001-2' || $data['data_empresa']['data']['nit'] == '6466096-9'){?>
                                <div align="center" style="margin-top: 2px;"><img src="<?= base_url("uploads/{$data['data_empresa']['data']['logotipo']}"); ?>" width="65" border="0" /></div>
                        <?php } ?>
                                <?php } ?>
                            <div id="company_name"><?= $data['data_empresa']['data']['nombre']; ?></div>
                            
                            <?php if($data['data_empresa']['data']['resolucion_factura_estado'] == 'si') { ?>
                                <div id="company_resolucion"><?= $data['venta']['resolucion_factura']; ?></div>
                                <div id="company_nit"><?= $data['data_empresa']['data']['documento'].":" . $data['venta']['nit']; ?></div>
                            <?php } else { ?>
                                <!--<div id="company_resolucion"><?= $data['data_empresa']['data']['resolucion']; ?></div>-->
                                <div id="company_nit"><?= $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>
                            <?php } ?>

                            <div id="heading"> <?= $data['data_empresa']["data"]['cabecera_factura'];?></div>
                            <div id="company_almacen"><?= "Almacen:" . $data['venta']['nombre'] ?></div>
                            <!--Se modifico la vista para que la dirección y el teléfono del almacén se centraran y no lo mostrara en 2 columnas, sino en cada línea, por lo que se elimino la tabla y se colocó en div y se le bajo el tamaño a la letra de factura y día -->
                            <!--Se actualizó la tirilla de honduras para que utilice decimales y se le agregó los campos cantidad y precio unitario.-->
                            <div>
                                <?= $data['venta']['direccion'] ?>
                            </div>
                            <div>
                                Tel:<?= $data['venta']['telefono'] ?>
                            </div>
                            <?php
                                if((!empty($data['venta']['nombre_comercial'])) && ($data['venta']['nombre_comercial'] != 'general')){ 
                            ?>
                                <div id="customer"><?php echo "Cliente:".$data['venta']['nombre_comercial']." <br> ".$data['venta']['tipo_identificacion'].":".$data['venta']["nif_cif"] ?></div>
                            <?php
                                }
                            ?>
                    <?php $username = $this->session->userdata('username'); ?>  
                        </div>
                        <?php  
                        //print_r($data['venta']);die();     
                            $i=0;
                        foreach ($data["detalle_venta"] as $p) { 
                            if($p['descuento'] > 0){  $i=1;  } 
                        }                       
                        ?> 
                        <br>
                        <div class="container" style="width:90%; margin-left:5%;">
                            <table style="width:100%;">
                                <tr>
                                    <th style="width:50%;text-align:left;font-size:13px;">FACTURA # <?= $data['venta']['factura'] ?></th>
                                    <th style="width:50%;text-align:right;font-size:13px;">DÍA : <?= $data['venta']['fecha'] ?></th>    
                                </tr>   
                            </table>
                            <hr>
                        
                        <table  id="ticket_items">
                        <tr>
                            <td style="width:40%;text-align:left;font-size:12px;"><b>Descripción</b></td>
                            <td style="width:20%;text-align:left;font-size:12px;"><b>Cant.</b></td>
                            <td style="width:20%;text-align:left;font-size:12px;"><b>P. Unitario</b></td>
                            <td style="width:20%;text-align:right;font-size:12px;"><b>Total</b></td>
                        </tr>  
                              
                    <?php  
                        $total = 0;
                        $timp  = 0;
                        $subtotal = 0;
                        $total_items = 0;
                        $total_items_propina = 0;
                        $sobrecosto = 0;
                        $propina_final = 0;
                        $discount = 0;
                        foreach ($data["detalle_venta"] as $p) {
                            $discount += $p['descuento']; 

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
                                if( trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO'){

                                            $pv_propina = $p['precio_venta'];
                                            $desc_propina = $p['descuento'];
                                            $pvd_propina = $pv_propina - $desc_propina;
                                            $total_column_propina = $pvd_propina * $p['unidades'];
                                            $total_items_propina += $total_column_propina;                                                             
                                } ?>
                                
                                <tr>
                                    <td style="width:40%;text-align:left;"><?= substr($p["nombre_producto"], 0, 28); ?> </td>
                                    <td style="width:20%;text-align:left;"><?= substr($p["unidades"], 0, 28); ?> </td>
                                    <!--<td style="width:20%;text-align:left;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($ci->opciones_model->redondear($p['precio_venta'])); ?></td>-->
                                    <td style="width:20%;text-align:left;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p['precio_venta']); ?></td>
                                    <!--<td style="width:20%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($ci->opciones_model->redondear($valor_total)); ?></td>-->
                                    <td style="width:20%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valor_total); ?></td>
                                </tr>  
                        <?php
                            }
                        }
                        
                            ?>
                            </table>
                            <hr>
                            <table  id="ticket_items">
                                <tr>
                                    <td style="width:50%;text-align:left;">Subtotal</td>
                                    <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($ci->opciones_model->redondear($total_items)); ?></td>-->
                                    <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_items); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align:left;">Import. Exonerado</td>
                                    <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($ci->opciones_model->redondear($total_items)); ?></td>-->
                                    <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_items); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align:left;">Desc./Rebaja</td>
                                    <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format( $ci->opciones_model->redondear($discount)); ?></td>-->
                                    <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($discount); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align:left;">Import. Gravado</td>
                                    <td style="width:50%;text-align:right;">...</td>
                                </tr>
                                <?php  foreach ($data["venta_impuestos"] as $p):
                                            if($p->imp != ''): ?>
                                                    <tr>
                                                        <td style="width:50%;text-align:left;padding-left:5px;"><?= '-'.ucfirst(strtolower($p->imp)); ?></td>
                                                        <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($p->impuestos) ?></td>-->
                                                        <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->impuestos) ?></td>
                                                    </tr>
                                            <?php else: ?>
                                                    <tr>
                                                        <td style="width:50%;text-align:left;padding-left:5px;">-Iva</td>
                                                        <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($p->impuestos) ?></td>-->
                                                        <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->impuestos) ?></td>
                                                    </tr>
                                            <?php  endif;
                                        endforeach; 
                                if($sobrecosto > 0 && $propina_final > 0): ?>
                                    <tr>
                                        <td style="width:50%;text-align:left;">Propina</td>
                                        <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($propina_final);?></td>-->
                                        <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($propina_final);?></td>
                                    </tr>   
                                <?php endif; ?>

                                <tr>
                                    <td style="width:50%;text-align:left;">Formas de pago</td>
                                    <td style="width:50%;text-align:right;">...</td>
                                </tr>

                                <?php foreach ($data["detalle_pago_multiples"] as $p): 
                                    if($p->forma_pago!="Sin_asignar_pago"){
                                        
                                        $formpago=str_replace("_"," ",$p->forma_pago); 
                                        if($p->forma_pago=='efectivo'){  ?> 
                                            <tr>
                                                <td style="width:50%;text-align:left;padding-left:5px;">-<?= ucfirst($formpago) ?></td>
                                                <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado); ?></td>-->
                                                <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado); ?></td>
                                            </tr>     
                                        <?php } else{ ?>
                                            <tr>
                                                <td style="width:50%;text-align:left;padding-left:5px;">-<?= ucfirst($formpago) ?></td>
                                                <!--<td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.number_format($p->valor_entregado) ?></td>-->
                                                <td style="width:50%;text-align:right;"><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?></td>
                                            </tr>   
                                        <?php } 
                                    } 
                                endforeach; 
                                foreach ($data["detalle_pago_multiples_cambio"] as $p): ?>
                                    <tr>    
                                        <td style="width:50%;text-align:left;">Cambio</td>
                                        <!--<td  style='text-align:right'><?= $data['data_empresa']['data']['simbolo'].' '.number_format( $ci->opciones_model->redondear($p->total_cambio)); ?></td>-->
                                        <td  style='text-align:right'><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($p->total_cambio); ?></td>
                                    </tr>   
                                <?php endforeach; ?> 

                                <tr>
                                    <td style="width:50%;text-align:left;">Total</td>
                                    <!--<td  style='text-align:right'><?= $data['data_empresa']['data']['simbolo'].' '.number_format( $ci->opciones_model->redondear($total + $propina_final) ); ?></td>-->
                                    <td  style='text-align:right'><?= $data['data_empresa']['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total + $propina_final); ?></td>
                                </tr>  
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                        <div align="center" style="padding-bottom:-10px;">
                            <?= $data['data_empresa']["data"]['terminos_condiciones'];?>
                            <br/>         
                        </div>
                        
                        <br/>
                        <div align="center" style="padding-bottom:-10px;">
                            <?php
                            //puntos --------------------------------------------------------------------------------------------------------------------------
                                if($data["puntos_cliente_factura"] > 0){
                                    ?>
                                    Puntos por esta factura: <?php  echo $ci->opciones_model->formatoMonedaMostrar($data["puntos_cliente_factura"]); ?><br/>
                                    Puntos Acumulados: <?php  echo $ci->opciones_model->formatoMonedaMostrar($data["puntos_cliente_acumulado"]); ?>
                                    <?php 
                                } 
                            ?>
                        </div>
                        
                        <br>

                        <div align="center" style="padding-bottom:-10px;font-size: 11px;font-weight: initial;">
                                Son: <?= convertir($total + $propina_final); ?>       
                        </div>
                        <br/>
                    </div>
                <!-- end invoice -->               
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    window.print();
</script>


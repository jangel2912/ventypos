<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");

//variable global para identificar si la factura es de un cliente y el cliente tiene algun plan
//para que muestre decimales en ese caso
$cliente_tiene_plan =false;
if(is_numeric($data['venta']['grupo_clientes_id']) and ($data['venta']['grupo_clientes_id'] > 1) ){
    $cliente_tiene_plan = true; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print_promocionNueva.css") ?>"  media="print"/>

    </head>

    <body>
        <div id="contenedor">

            <div id="print_area">

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
        <?php } else { ?>
            <!--<div id="company_resolucion"><?php echo $data['data_empresa']['data']['resolucion']; ?></div>-->
            <div id="company_nit"><?php echo $data['data_empresa']['data']['documento'].":" . $data['data_empresa']['data']['nit']; ?></div>
        <?php } ?>

        <div id="heading"> <?php echo $data['data_empresa']["data"]['cabecera_factura'];?></div>


        <div id="company_almacen"><?php echo "Almacen:" . $data['venta']['nombre'] ?></div>

        <table id="ticket_company" align="center">

            <tr>

                <td style="width:65%;text-align: left;"><?php echo $data['venta']['direccion'] ?></td>

                <td style="width:35%;text-align: right;"><?php echo $data['venta']['telefono'] ?></td>              

            </tr>

        </table>            

        <table id="ticket_factura" align="center">

            <tr>

                <td style="width:45%;text-align: left;"><?php echo $data['data_empresa']['data']['titulo_venta'] .": " . $data['venta']['factura'] ?></td>

                <td style="width:55%;text-align: right;"><?php echo "Fecha:" . $data['venta']['fecha'] ?></td>              

            </tr>

        </table>            

        <div id="customer"><?php echo "Cliente:" . ($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"] . ' <br> ' .$data['venta']["tipo_identificacion"]. ': '. $data['venta']["nif_cif"]) ?></div>
        <?php if( strlen(trim($data['venta']['cliente_direccion'])) > 0) { ?>
            <div id="customer">Direcci&oacute;n: <?php echo $data['venta']['cliente_direccion'] ?></div>
        <?php } ?>
        <?php if( strlen(trim($data['venta']['cliente_telefono'])) > 0 || strlen(trim($data['venta']['cliente_movil'])) > 0 ) { ?>
            <div id="customer">Tel&eacute;fono:<?php echo ' '.$data['venta']['cliente_telefono'].' '.$data['venta']['cliente_movil'] ?></div>
        <?php } ?>
        <?php if( strlen(trim($data['venta']['cliente_email'])) > 0) { ?>
            <div id="customer">Email:<?php echo ' '.$data['venta']['cliente_email'] ?></div>
        <?php } ?> 
<?php  $username = $this->session->userdata('username');

    if($data['data_empresa']['data']['vendedor_impresion'] == '1'){ ?>
        <div id="seller"><?php echo "Vendedor: " . $data['venta']['vendedor'] ?></div>
 <?php  }   ?>          
<?php if($data['data_empresa']['data']['vendedor_impresion'] == '2'){ ?>
        <div id="seller"><?php echo "Vendedor: " . $data['username'] ?></div>
 <?php  }   ?>          
<?php if($data['data_empresa']['data']['vendedor_impresion'] == '3'){ ?>
        <div id="seller"><?php echo "Vendedor: " . $data['venta']['vendedor'] ?></div>
        <div id="seller"><?php echo "Usuario: " . $username ?></div>        
 <?php  }   ?>          
        
        <?php  if($data['venta']['nota'] != ''){   ?>
        <div id="seller"><?php echo $data['venta']['nota'] ?></div>
        <?php  }   ?>  
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

            <th style="width:25%;text-align: left;">Ref</th>

            <th style="width:5%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;"><?php echo "Precio" ?></th>

            <th style="width:25%;text-align:center;"><?php echo "Desc" ?></th>
                    
            <th style="width:25%;text-align:right;"><?php echo "Total" ?></th>

        </tr>
                   <?php  
                   }      
                     else{                   
                 ?>  
    <table id="ticket_items">

        <tr>

            <th style="width:25%;text-align: left;">Ref</th>

            <th style="width:5%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:40%;text-align:right;" ><?php echo "Precio" ?></th>
            
            <th style="width:40%;text-align:right;" colspan="2"><?php echo "Total" ?></th>

        </tr>                
                   <?php  
                   }             
                    ?>                   
                 
                 
        <?php

        $total = 0;

        $total_imp  = 0;

        $subtotal = 0;

        $total_items = 0;
        $total_items1 = 0;

        $total_items_propina = 0;

        $sobrecosto = 0;

        $base_impuesto = 0;

        $propina_final = 0;
        $descuentoPromociones = 0;
        $promocionDetalle = array();
        $exentosImpuesto = 0;
        $codigoProducto = "";
        $productos = array();
        $productosPromocion = array();
        foreach ($data["detalle_venta"] as $key=> $p)
        {
            if($p["nombre_producto"] == 'PROPINA'){     
                $sobrecosto = $p['descripcion_producto'];
            }
            else{
                //var_dump($timp);echo "<br>---";
               // echo "<br>precioventa:<br> ";
                $pv = floatval($p['precio_venta']);
                //var_dump($pv);
                //precio de venta con impuesto
                $valor_unitario_producto =  floatval( ($p["precio_venta"])+( ($p["precio_venta"]*$p['impuesto']) /100) );
                //echo '<br>precio unitario:<br> ';
                //var_dump($valor_unitario_producto);
                $porcentaje_descuento = $p['porcentaje_descuento'];
                
                if(is_null($porcentaje_descuento) or $porcentaje_descuento == 0){
                    $desc = floatval($p['descuento']) + floatval($p['descuento']*$p['impuesto'] / 100);
                    $porcentaje_descuento = round( ($desc * 100) / $valor_unitario_producto,4);
                }
                $valor_descuento = ($valor_unitario_producto * $porcentaje_descuento) / 100;
                //echo '<br>Valor descuento:<br>';
                //var_dump($valor_descuento);
                //echo "<br>descuento:";
               
                //var_dump($desc);
                //echo "<br> precio venta con descuento: ";
                $pvd = ($pv-$p['descuento']);
                $pvd = $pv - floatval($p['descuento']);
                
                //$base_impuesto+=$pvd;
                //echo "<br> total producto:<br>";
                $imp = ( $pvd * $p['impuesto'] / 100 ) * $p['unidades'];
              
                //$exentosImpuesto += ($p['impuesto'] == 0)? $pvd * $p['unidades']:0;
                $exentosImpuesto += ($p['impuesto'] == 0)? round($pvd) * $p['unidades']:0;
                //$total_column = round(($pvd * $p['unidades'])+ $imp);
                //$valor_total = round(($pvd * $p['unidades']) + $imp);                        
                //$valor_total = round(round($pvd + $pvd * $p['impuesto'] / 100) * $p['unidades']);
                //$valor_total = (round($pvd * $p['unidades']) + round(($pvd * $p['unidades']) * $p['impuesto'] / 100));
                
                if($i == 1){// Si es promocion
                    $valor_total = ( $valor_unitario_producto - $valor_descuento ) * $p['unidades'];
                }else{
                    $valor_total = ( $valor_unitario_producto - $valor_descuento ) * $p['unidades'];
                }
                //echo '<br>valor_total<br>';
                //var_dump($valor_total);
                //$valor_total = round((($p['precio_venta'] - $p['descuento']) * $p['unidades']) + ((($p['precio_venta'] - $p['descuento']) * $p['unidades']) * $p['impuesto'] / 100));
                //$total_column = round(round($pvd + $pvd * $p['impuesto'] / 100) * $p['unidades']);
                $total_items += $valor_total;
               
                //$subtotal += floatval($pvd * $p['unidades']);
                $subtotal+=$valor_total;
                if($p['descripcion_producto'] == "-1")
                {
                    $descuentoPromociones += $valor_total;
                    array_push($promocionDetalle,array(
                        "cantidad"=>$p['unidades'],
                        "valorUnitario"=>$valor_unitario_producto,
                        "valorTotal"=>$valor_total,
                        "nombre"=>$p["nombre_producto"],
                        "descuento"=>$valor_descuento,
                    ));
                    $subtotal -= floatval($pvd * $p['unidades']);
                }else if($p['impuesto'] == 0)
                {
                    $subtotal -= floatval($pvd * $p['unidades']);               
                }

                if($codigoProducto != $p["codigo_producto"])
                {
                    
                    if(isset($data["detalle_venta"][$key+1]['descripcion_producto']) && $data["detalle_venta"][$key+1]['descripcion_producto'] == "-1")
                    {
                        $unidadesP = $p["unidades"] + $data["detalle_venta"][$key+1]["unidades"];
                        $valor_total = round(round($pvd + $pvd * $p['impuesto'] / 100) * $unidadesP);
                        array_push($productosPromocion,array(
                            "nombre_producto"=>$p["nombre_producto"],
                            "unidades"=>$unidadesP,
                            "precioUnitario"=>$valor_unitario_producto,
                            "descuento"=>$valor_descuento,
                            "precioTotal"=>$valor_total
                        ));
                    }else
                    {
                        array_push($productos,array(
                            "nombre_producto"=>$p["nombre_producto"],
                            "unidades"=>$p["unidades"],
                            "precioUnitario"=>$valor_unitario_producto,
                            "descuento"=>$valor_descuento,
                            "precioTotal"=>$valor_total
                        ));
                    }
                }
                $total += $ci->opciones_model->redondear($valor_total + $imp);
                $total_imp+=$imp;
                //var_dump($timp);echo "<hr>---";
                if(trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO')
                    {
                        $pv_propina = $p['precio_venta'];
                        $desc_propina = $p['descuento'];
                        $pvd_propina = $pv_propina - $desc_propina;
                        $total_column_propina = $pvd_propina * $p['unidades'];
                        $total_items_propina += $total_column_propina;

                    }
                $codigoProducto = $p["codigo_producto"];
            }
        
        }

        $subtotal = round($subtotal);


        
        foreach($productos as $p)
        {

            if($p["unidades"] != 0)
            {
                if($cliente_tiene_plan){
                    $redondeo_decimales = 2;
                }else{
                    $redondeo_decimales = 0;
                }
                    if($i == 1){
                            $descuento = $p['descuento'];    
                            /*$array_descuento = explode('.',$p['descuento']);
                            if(isset($array_descuento[1]) && ( ($array_descuento[1] >=50 && $array_descuento[1] < 60) or ($array_descuento[1] == 5 ) ) ){
                                    $descuento = $p['descuento'] - 1;
                            }*/

                    ?>
                    <tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>
                    <tr>

                        <td>&nbsp;&nbsp;</td>

                        <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>
                
                        <td style='text-align:right;'><?php 
                            $redondeo_decimales = 2;
                            echo number_format($ci->opciones_model->redondear($p["precioUnitario"]),$redondeo_decimales); 
                            ?>
                                

                            </td>

                        <td style='text-align:center;'><?php echo number_format($descuento,$redondeo_decimales); ?></td>

                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($p["precioTotal"]),$redondeo_decimales); ?></td>
                    </tr>
                    <?php
                    } 
                    else{                
                    ?>  
                    </tr><tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>
                    <tr>

                        <td>&nbsp;&nbsp;</td>

                        <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($p["precioUnitario"]),$redondeo_decimales); ?></td>

                        <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($p["precioTotal"]),$redondeo_decimales); ?></td>
                    </tr>
                    <?php
                    }
                }   
        }
        foreach($productosPromocion as $p)
        {
            if($p["unidades"] != 0)
            {
                if($i == 1){                 
                ?>
                <tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>
                <tr>

                    <td>&nbsp;&nbsp;</td>

                    <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                    <td style='text-align:right;'><?php echo number_format($ci->opciones_model->redondear($p["precioUnitario"])); ?></td>

                    <td style='text-align:center;'><?php echo number_format($p['descuento']); ?></td>

                    <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format(($p["precioTotal"])); ?></td>
                </tr>
                <?php
                } 
                else{                
                ?>  
                </tr><tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>
                <tr>

                    <td>&nbsp;&nbsp;</td>

                    <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                    <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($p["precioUnitario"])); ?></td>

                    <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($p["precioTotal"])); ?></td>
                </tr>
                <?php
                }
            }   
        }
        ?>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td colspan="2" style='text-align:right;'>Subtotal</td><td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($total_items) ?></td>
        </tr>    
        <?php
        if(count($promocionDetalle) != 0)
        {
            foreach($promocionDetalle as $p)
            {
                if($i == 1){
                    ?>
                    <tr>

                        <td style='text-align:left;'>Descuento <?php echo $promocion ?></td>

                        <td style='text-align:center;'><?php echo $p['cantidad'] ?></td>

                        <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p['valorUnitario']) ?></td>

                        <td style='text-align:center;'><?php echo number_format($p['descuento']); ?></td>

                        <td style='text-align:right;' colspan="2">-<span style="color:#fff">_</span><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p['valorTotal']) ?></td>
                    </tr>
                    <?php
                }else
                {
                    ?>
                    <tr border="1px">
                        <td style='text-align:left;'>Descuento <?php echo $promocion ?></td>
                        <td style='text-align:center;'><?php echo $p['cantidad'] ?></td>
                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p['valorUnitario']) ?></td>
                        <td style='text-align:right;'>-<span style="color:#fff">_</span><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p['valorTotal']) ?></td>
                    </tr>
                    <?php
                }   
                $total_items = ($total_items) - $p['valorTotal'];
            }
        }
        ?>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td colspan="2" style='text-align:right;'>Valor a Pagar</td><td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($total_items) ?></td>
        </tr>
        <tr><td colspan="4">&nbsp;</td></tr>
            <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Valor base de Iva" ?></td>

            <?php 
            $total = $total_items;
            //no se debe colocar ya que se quema el impuesto lo cual puede generar problemas despues
            //$timp = $subtotal * 19 /100;
            $base_impuesto = round($total) - round($total_imp);
            if($subtotal + round($total_imp) + $exentosImpuesto != round($total))
            {
                
                if($sobrecosto > 0){
                    $propina_final = ($total_items_propina * $sobrecosto) / 100;
                }
                
                $subtotal = round($total) - round($total_imp) - $exentosImpuesto;

               // var_dump($subtotal);
            }
            ?>
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($base_impuesto) ?></td>

        </tr>
        
        <?php  foreach ($data["venta_impuestos"] as $p) {  
            if($p->imp != ''){ 
            ?>
                    <tr>
                        <td colspan="4" style='text-align:right;'><?php echo $p->imp ?></td>
                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->impuestos) ?></td>
                    </tr>

            <?php  
                }else{
            ?>
                <tr>
                    <td colspan="4" style='text-align:right;'>IVA</td>
                    <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].' '.number_format($p->impuestos) ?></td>
                </tr>

            <?php	 
                }
            }
        ?>

        <!--<tr>
            <?php 
            
            ?>
            <td colspan="4" style='text-align:right;'>Iva</td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format(($total_imp)) ?></td>

        </tr>-->
  <?php
  
  /*
  foreach ($data["venta_impuestos"] as $p) {  
  if($p->imp != ''){ 
  ?>
        <tr>

            <td colspan="4" style='text-align:right;'><?php echo $p->imp ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.(($p->impuestos)) ?></td>

        </tr>

  <?php  
     }else{
  ?>
        <tr>

            <td colspan="4" style='text-align:right;'>IVA</td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p->impuestos) ?></td>

        </tr>

  <?php  
     }
   
   }*/
    if($exentosImpuesto != 0)
    {
        ?>
        <tr>
            <td colspan="4" style='text-align:right;'>Bienes exentos</td>
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($exentosImpuesto) ?></td>
        </tr>
        <?php
    }
   ?>
    
         <?php 
            if($sobrecosto > 0){
             $propina_final = ($total_items_propina * $sobrecosto) / 100;
            }
        ?>
        
        <?php 
            if($sobrecosto > 0 && $propina_final > 0){
        ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Propina" ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($propina_final);  ?></td>

        </tr>
        <?php 
            }
        ?>

<?php  

foreach ($data["detalle_pago_multiples"] as $p) { ?>


 <?php
 $formpago=str_replace("_"," ",$p->forma_pago); 
  if($p->forma_pago=='efectivo'){  ?>  
                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo ucfirst($formpago) ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p->valor_entregado) ?>
                        </td>

                    </tr>
                       
 <?php } ?> 

 <?php if($p->forma_pago!='efectivo'){  ?>  
                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo ucfirst($formpago) ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($p->valor_entregado) ?>
                        </td>

                    </tr>   
 <?php } ?>   
 
<?php } ?>

<?php  foreach ($data["detalle_pago_multiples_cambio"] as $p) { ?>

                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo "Cambio" ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format( ( $p->total_cambio)) ?>
                        </td>

                    </tr>    
    
<?php } ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Total venta" ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].'<span style="color:#fff">_</span>'.number_format($ci->opciones_model->redondear($total + $propina_final)); ?></td>

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

         <div align="center" style="padding-bottom:-10px;">
                    <?php echo $data['data_empresa']["data"]['terminos_condiciones'];?>
                <br/>   
            <?php         
//puntos --------------------------------------------------------------------------------------------------------------------------
            if($data["puntos_cliente_factura"] > 0){
        ?>
        Puntos por esta factura: <?php  echo number_format($data["puntos_cliente_factura"]); ?><br/>
        Puntos Acumulados: <?php  echo number_format($data["puntos_cliente_acumulado"]); ?>
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
    

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>
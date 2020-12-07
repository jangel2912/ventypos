<?php 
$ci = &get_instance();
$ci->load->model("opciones_model");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print_promocionNueva.css") ?>"  media="print"/>
        <style>
            table#ticket_items tbody{
                /*font-size:12px;*/
            }
            #ticket_wrapper{
                font-size:12px;
            }

        </style>
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
                <td style="width:45%;text-align: left; font-size:14px;"><?php echo $data['data_empresa']['data']['titulo_venta'] .": " . $data['venta']['factura'] ?></td>
                <?php
                $fechaventa = new DateTime($data['venta']['fecha']); ?>
                <td style="width:55%;text-align: right;"><?php echo "Fecha:" .$fechaventa->format('Y-m-d'); ?></td>              
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
    <?php  //print_r($data["detalle_venta"]);  echo"<br><br>";
        if($i == 1){                
                 ?>  
    <table id="ticket_items">

        <tr>
            <th style="width:35%;text-align: left;">Ref</th>

            <th style="width:5%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;"><?php echo "Precio" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Desc" ?></th>
                    
            <th style="width:20%;text-align:right;"><?php echo "Total" ?></th>

        </tr>
        <?php  
        }      
            else{                   
        ?>  
            <table id="ticket_items">
                <tr>
                    <th style="width:45%;text-align: left;">Ref</th>
                    <th style="width:5%;text-align:center;"><?php echo "Cant" ?></th>
                    <th style="width:25%;text-align:right;" ><?php echo "Precio" ?></th>            
                    <th style="width:25%;text-align:right;" colspan="2"><?php echo "Total" ?></th>
                </tr>                
                   <?php  
                   }             
                    ?>    
        <?php

        $total = 0;
        $timp  = 0;
        $subtotal = 0;
        $subtotal_imp = 0;
        $total_items = 0;
        $total_items1 = 0;
        $total_items_propina = 0;
        $sobrecosto = 0;
        $propina_final = 0;
        $descuentoPromociones = 0;
        $promocionDetalle = array();
        $exentosImpuesto = 0;
        $codigoProducto = "";
        $productos = array();
        $productosPromocion = array();

        foreach ($data["detalle_venta"] as $key => $p) {

            if($p["nombre_producto"] == 'PROPINA'){     
                $sobrecosto = $p['descripcion_producto'];
            }
            else{
                $pv=$p['precio_venta'];
                $porcentaje_descuento=($p['porcentaje_descuento']);
                $desc = floatval($p['descuento']) +floatval($p['descuento']*$p['impuesto'] / 100);
                $pvd = floatval($pv-$p['descuento']);
                $imp = ($pvd* $p['unidades']) * $p['impuesto'] / 100 ;
                $exentosImpuesto += ($p['impuesto'] == 0)? $pvd * $p['unidades']:0;                                 
                $valor_total = (floatval($pvd + ($pvd * $p['impuesto'])  / 100) * $p['unidades']);
                $total_items += floatval($valor_total);
                $precioventadummy=ROUND(($p['precio_venta'])+((($p['precio_venta'])* $p['impuesto']/100)));
               // echo"<br>precio_venta=".$p['precio_venta'];
              //  echo"<br>descuento=".$p['descuento'];
                //ROUND(((d.precio_venta)- d.descuento),2),
                $desc1=ROUND(((($p['precio_venta'])+(($p['precio_venta'])* $p['impuesto']/100))*$p['porcentaje_descuento'])/100);                
                $dummy1=(($p['precio_venta'])-($desc1));
                $dummi=ROUND(($p['precio_venta'])+ (($p['precio_venta'])*($p['impuesto']/100)));
                $totaldummy=ROUND(($dummi- $desc1)*$p['unidades']);
                //ROUND(((SELECT dummy1) - (SELECT dummy2))*d.unidades)),
                $desdummy=ROUND((($precioventadummy *$p['unidades'])-$totaldummy)/$p['unidades']);
                $subtotal += floatval($pvd * $p['unidades']);
                $subtotal_imp += $imp;// Calculando el impuesto
                //echo"<br>des1=".$desc1;
                //Precio venta
                //ROUND((d.precio_venta)+ROUND((d.precio_venta* d.impuesto/100))),
                //dummy1
                //ROUND((d.precio_venta- d.descuento)),
                //descuento
               // ROUND((SELECT dummy1 *(porcentaje_descuento)/100)),         
                //total
                //ROUND((((SELECT dummy1)+ ROUND((SELECT dummy1*d.impuesto)/100))*d.unidades))),

                /*********NUEVO******** */
                $totalproductos=0;
                 //precio
                 if($p['impuesto'] != 0){
                    $precio_venta=ROUND(($p['precio_venta'])+ (($p['precio_venta'])*($p['impuesto']/100)));
                }else{
                    $precio_venta=ROUND($p['precio_venta']);
                }
                
                 //descuento_porcentaje
                $descuento_general=$data['venta']['porcentaje_descuento_general'];
                
                $porcentaje_descuentop = $p['porcentaje_descuento'];
                if($descuento_general != 0){  
                    $descuento_prod=ROUND((((($p['precio_venta'])+(($p['precio_venta'])*$p['impuesto']/100))*$porcentaje_descuentop)/100)+(((($precio_venta) -(((($p['precio_venta'])+(($p['precio_venta'])*$p['impuesto']/100))*$porcentaje_descuentop)/100))*$descuento_general)/100));
                }else{
                    $descuento_prod=ROUND(((($p['precio_venta'])+(($p['precio_venta'])* $p['impuesto']/100))*$porcentaje_descuentop)/100);
                }

                $totalp=ROUND(((($precio_venta) - ($descuento_prod))*$p['unidades']));
                 //productos sin promo totalizo el valor                           
                 if($p['descripcion_producto']!='-1'){                                    
                                        
                    $totalproductos+=$totalp;
                } 
                /*
                echo" <br>precio_venta=".$precio_venta;
                echo" <br>descuento_general=".$descuento_general;
                echo"<br>porcentaje_descuentop=".$porcentaje_descuentop;
                echo" <br>descuento_produ=".$descuento_prod;
                echo" <br>totalp=".$totalp;
                echo" <br>totalproductos=".$totalproductos;*/

            }
            if($p['descripcion_producto'] == "-1")
            {
               //$descuentoPromociones += $valor_total;
                $descuentoPromociones += $totalp;
                array_push($promocionDetalle,array(
                    "cantidad"=>$p['unidades'],
                    //"valorUnitario"=>$p["precio_venta"]+($p["precio_venta"]*$p['impuesto'] /100),
                    "valorUnitario"=>$precio_venta,
                    //"valorTotal"=>$valor_total,
                    "valorTotal"=>$valor_total,
                    "nombre"=>$p["nombre_producto"],
                    //"descuento"=>$desc 
                    "descuento"=>$descuento_prod 
                ));
                $subtotal -= floatval($pvd * $p['unidades']);
                $subtotal_imp -= $imp;// Calculando el impuesto
            }

            
            if($p['impuesto'] == 0)
            {
                $subtotal -= floatval($pvd * $p['unidades']);
                $subtotal_imp -= $imp;// Calculando el impuesto
            }          
            array_push($productos,array(
                "nombre_producto"=>$p["nombre_producto"],
                "unidades"=>$p["unidades"],
                //"precioUnitario"=>$p["precio_venta"]+($p["precio_venta"]*$p['impuesto'] /100),
                "precioUnitario"=>$precio_venta,
                //"descuento"=>$desc,
                "descuento"=>$descuento_prod,
                'porcentaje_descuento'=>$porcentaje_descuentop,
                //"precioTotal"=>$valor_total,
                "precioTotal"=>$totalp,
                "dummy"=>$dummy1,
                //"desdummy"=>$desdummy,
                "desdummy"=>$desc1, 
                //"totaldummy"=>$totaldummy,
                "totaldummy"=>$totalproductos,
                "precioventadummy"=>$precioventadummy
            ));

            $total += $ci->opciones_model->formatoMonedaMostrar($valor_total + $imp);
            $timp+=$imp;           
            if(trim(strtoupper($p["des_impuesto"])) == 'IAC' || trim(strtoupper($p["des_impuesto"])) == 'IMPOCONSUMO' || trim(strtoupper($p["des_impuesto"])) == 'IMPUESTO AL CONSUMO')
            {
                $pv_propina = $p['precio_venta'];
                $desc_propina = $p['descuento'];
                $pvd_propina = $pv_propina - $desc_propina;
                $total_column_propina = $pvd_propina * $p['unidades'];
                $total_items_propina += $total_column_propina;
            }
        }
     
        $totalitems=0;    
        //print_r($productos);
        foreach($productos as $p)
        {
            if($p["unidades"] != 0)
            {
                    if($i == 1){   
                      //  $pu=floatval($p["precioUnitario"]);
                      // $des=$pu*$p['porcentaje_descuento']/100;
                      //  $valortotal=floatval(($pu-$des)*$p["unidades"]);    
                       // $totalitems+=$valortotal;                       
                        //$des=$p['desdummy'];                         
                        $pu=$p['precioUnitario'];
                        //$des=$p['desdummy'];
                        $des=$p['descuento'];
                        
                        $valortotal= $p['totaldummy'];  
                        $totalitems+=$p['totaldummy'];            
                    ?>                   
                    <tr>
                        <td style='text-align:left;'><?php echo trim(substr($p["nombre_producto"],0,13)); ?></td>

                        <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                        <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($pu); ?></td>

                        <td style='text-align:center;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($des); ?></td>

                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($valortotal); ?></td>
                    </tr>
                    <?php
                    } 
                    else{ 
                        $totalitems+=$p['totaldummy'];                                           
                    ?>  
                    </tr>                   
                    <tr>                        
                        <td style='text-align:left;'><?php echo trim(substr($p["nombre_producto"],0,20)); ?></td>

                        <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["precioventadummy"]);?></td>

                        <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["totaldummy"]); ?></td>
                    </tr>
                    <?php
                    }
                }   
        }
           /*
        foreach($productosPromocion as $p)
        {
            if($p["unidades"] != 0)
            {
                if($i == 1){                 
                ?>              
                <tr>

                    <td style='text-align:left;'>bbb<?php echo trim(substr($p["nombre_producto"],0,12)); ?></td>

                    <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                    <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["precioUnitario"]); ?></td>

                    <td style='text-align:center;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['descuento']); ?></td>

                    <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["precioTotal"]); ?></td>
                </tr>
                <?php
                } 
                else{                
                ?>  
                </tr>               
                <tr>
                    <td style='text-align:left;'>cc<?php echo trim(substr($p["nombre_producto"],0,20)); ?></td>

                    <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                    <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["precioUnitario"]); ?></td>

                    <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p["precioTotal"]); ?></td>
                </tr>
                <?php
                }
            }   
        }*/
        if($i == 1){
            $total_items=$totalitems;
        }
        else{
            $total_items=$totalitems;
        }
        ?>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td colspan="2" style='text-align:right;'>Subtotal</td><td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($total_items) ?></td>
            
        </tr>    
        <?php
        if(count($promocionDetalle) != 0)
        {
            foreach($promocionDetalle as $p)
            {
                if($i == 1){
                    ?>
                    <tr>

                        <td style='text-align:left;'>Dto: <?php echo trim(substr($p["nombre"],0,10)); ?></td>
                        <td style='text-align:center;'><?php echo $p['cantidad'] ?></td>

                        <td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['valorUnitario']) ?></td>

                        <td style='text-align:center;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['descuento']); ?></td>

                        <td style='text-align:right;' colspan="2">-<span style="color:#fff">_</span><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['valorTotal']) ?></td>
                    </tr>
                    <?php
                }else
                {
                    ?>
                    <tr border="1px">
                        <td style='text-align:left;'>Dto: <?php echo trim(substr($p["nombre"],0,15)); ?></td>
                        <td style='text-align:center;'><?php echo $p['cantidad'] ?></td>
                        <td style='text-align:right;' colspan="2"><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['valorUnitario']) ?></td>
                        <td style='text-align:right;'>-<span style="color:#fff">_</span><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p['valorTotal']) ?></td>
                    </tr>
                    <?php
                }   
                $total_items = ($total_items) - $p['valorTotal'];
            }
        }
        ?>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td colspan="2" style='text-align:right;'>Valor a Pagar</td><td style='text-align:right;'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($total_items) ?></td>
        </tr>
        <tr><td colspan="4">&nbsp;</td></tr>
            <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Valor base de Iva" ?></td>

            <?php 
            $total = $total_items;
            //no se debe colocar ya que se quema el impuesto lo cual puede generar problemas despues
    
            $timp = $subtotal_imp;
 
            if($subtotal + $timp + $exentosImpuesto != $total)
            {
                if($sobrecosto > 0){
                    $propina_final = ($total_items_propina * $sobrecosto) / 100;
                }
                $subtotal = $total - $timp - $exentosImpuesto;
            }
            ?>
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($subtotal) ?></td>

        </tr>
        <tr>
            <?php 
            
            ?>
            <td colspan="4" style='text-align:right;'>Iva</td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar(($timp)) ?></td>

        </tr>
  <?php
    if($exentosImpuesto != 0)
    {
        ?>
        <tr>
            <td colspan="4" style='text-align:right;'>Bienes exentos</td>
            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($exentosImpuesto) ?></td>
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

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($propina_final);  ?></td>

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

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?>
                        </td>

                    </tr>
                       
 <?php } ?> 

 <?php if($p->forma_pago!='efectivo'){  ?>  
                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo ucfirst($formpago) ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($p->valor_entregado) ?>
                        </td>

                    </tr>   
 <?php } ?>   
 
<?php } ?>

<?php  foreach ($data["detalle_pago_multiples_cambio"] as $p) { ?>

                    <tr>

                        <td colspan="4" style='text-align:right;'><?php echo "Cambio" ?></td>

                        <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar( ( $p->total_cambio)) ?>
                        </td>

                    </tr>    
    
<?php } ?>

        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "Total venta" ?></td>

            <td  style='text-align:right'><?php echo $data['data_empresa']['data']['simbolo'].$ci->opciones_model->formatoMonedaMostrar($total + $propina_final); ?></td>

        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>              

    </table>



    <div align="center"><?php echo  $data['data_empresa']['data']['resolucion']; ?></div>

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
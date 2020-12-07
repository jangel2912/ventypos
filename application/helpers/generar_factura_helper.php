<?php 
function calcular_valores_detalle_factura($detalle_venta){

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
	foreach ($detalle_venta as $key=> $p)
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
			$valor_unitario_producto =  floatval($p["precio_venta"])+($p["precio_venta"]*$p['impuesto'] /100);
                //echo '<br>precio unitario:<br> ';
                //var_dump($valor_unitario_producto);
			$porcentaje_descuento = $p['porcentaje_descuento'];

			if(is_null($porcentaje_descuento) or $porcentaje_descuento == 0){
				$desc = floatval($p['descuento']) + floatval($p['descuento']*$p['impuesto'] / 100);
				$porcentaje_descuento = round( ($desc * 100) / $valor_unitario_producto,2);
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
    }

    ?>
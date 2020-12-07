<div id="ticket_wrapper">

    <div id="ticket_header">


        <div id="company_name"><?php echo $data['data_empresa']['data']['nombre']; ?></div>
		
        <div id="customer" style=" font-size:22px"><?php echo "<b>Comanda </b>" ?></div>
		
		<div id="customer" style=" font-size:14px"><?php if(!empty($data['venta'])) echo $data['venta']["factura"] ?></div>	<br />	
		
		<div id="customer"><?php echo "Cliente: "; if(!empty($data['venta'])) echo $data['venta']["nombre_cliente"] ?></div>	<br />
			
		<?php if((!empty($data['venta'])) && ($data['venta']["nota"] != '')){ ?>
		<div id="customer"><?php echo "Nota: ". $data['venta']["nota"] ?></div>
		<?php } ?>

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

            <th style="width:20%;text-align: left;"><?php echo "Ref" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Cant" ?></th>

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

            <th style="width:20%;text-align: left;"><?php echo "Ref" ?></th>

            <th style="width:20%;text-align:center;"><?php echo "Cant" ?></th>

            <th style="width:20%;text-align:right;" ><?php echo "Precio" ?></th>
			
            <th style="width:20%;text-align:right;" colspan="2"><?php echo "Total" ?></th>

        </tr>				 
		           <?php  
				   }			 
	            	?>	 				 
				 
				 
        <?php

            $total = 0;

            $timp  = 0;

            $subtotal = 0;

            $total_items = 0;

            /*$group_by_impuesto = array();*/

        foreach ($data["detalle_venta"] as $p) {

                         /* POS */
                        $pv = $p['precio_venta'];
                        $desc = $p['descuento'];
                        $pvd = $pv - $desc;
                        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                        $total_column = $pvd * $p['unidades'];
                        $total_items += $total_column;
                        $valor_total = $pvd * $p['unidades'] + $imp ;
                        $total += $valor_total;
                        $timp+=$imp;
						
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


            ?>

                  <?php  
					 if($i == 1){  				 
		         ?>	 
            <tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>

            <tr>

                <td><?php echo $p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;'><?php echo number_format($p["precio_venta"]); ?></td>

                <td style='text-align:center;'><?php echo $p['descuento']; ?></td>

                <td style='text-align:right;' colspan="2"><?php echo number_format($valor_total); ?></td>

            </tr>
                   <?php
                    } 
					else{  				 
		         ?>	
            <tr><td colspan="5"><?php echo $p["nombre_producto"] ?></td></tr>

            <tr>

                <td><?php echo $p["codigo_producto"]; ?></td>

                <td style='text-align:center;'><?php echo $p["unidades"]; ?></td>

                <td style='text-align:right;' colspan="2"><?php echo number_format($p["precio_venta"]); ?></td>

                <td style='text-align:right;'><?php echo number_format($valor_total); ?></td>

            </tr>			 	
				   <?php
                    }
                    ?>	

            <?php

        }

        ?>

        <tr>

            <td colspan="4" style='text-align:right;'></td>

            <td  style='text-align:right'></td>

        </tr>
        <tr>

            <td colspan="4" style='text-align:right;'><?php echo "TOTAL" ?></td>

            <td  style='text-align:right'><?php echo number_format($total); ?></td>

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



    <div align="center"></div>

         <div align="center" style="padding-bottom:-10px;">
                   NO VALIDO COMO FACTURA
                </div>


    <br/><br/>



</div>
<script type="text/javascript">

    window.print();

</script>

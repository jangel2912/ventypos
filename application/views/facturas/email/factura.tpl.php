<style type="text/css">
				table.content{ border-bottom:1px solid #909090; border-left:1px solid #909090}
				table.content td{ border-top:1px solid #909090; border-right:1px solid #909090; padding:10px}
				.hdetail td{ color:#000000; padding:8px 4px 8px 4px }
				.data{ padding-top:10px;}
				.data td{ font-size:13px; padding:2px 0}
				h1{ font-size:38px; color:#DB9600}
				.foot1 { text-align:center;color:#FFFFFF; background:#22ACC8; font-size:13px;}
			</style>
			<table width="700" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<th width="300" valign="top" class="data">
                                            <h1>Datos del cliente</h1>
                                            <table  cellspacing="1" align="center">
							<tr><td width="100">N&uacute;mero :</td>   <td><?php echo $data['numero'];?></td></tr>
							<tr><td>Fecha :</td>   <td><?php echo date("d/m/Y",strtotime($data['fecha']));?></td></tr>
							<tr><td>Cliente :</td>   <td><?php echo $data['nombre_comercial'];?></td></tr>
							<tr><td>NIF/CIF :</td>   <td><?php echo $data['nif_cif'];?></td></tr>
							<tr><td>Direcci&oacute;n :</td>   <td><?php echo $data['direccion'];?></td></tr>
							<tr><td>C.P :</td>   <td><?php echo $data['cp'];?></td></tr>
							<tr><td>Poblaci&oacute;n :</td>   <td><?php echo $data['poblacion'];?></td></tr>
							<tr><td>Pais :</td>   <td><?php echo $data['pais'];?></td></tr>
                                                        <tr><td>Provincia :</td>   <td><?php echo $data['provincia'];?></td></tr>    
						</table>
					</th>
					<th width="200"></th>
					<th width="200" ><?php echo $datos_empresa["data"]['cabecera_factura'];?></th>
				</tr>
			</table>
			
                        <table align="center" cellspacing="0" class="content" width="700" style="margin-top:40px">
                                                <tr>
                                                    <td colspan="4"><h1>Detalles de la Factura</h1></td>
                                                </tr>
						<tr class="hdetail">
							<td width="100">Cantidad</td> 
							<td width="300">Descripci&oacute;n</td>
							<td width="150">Precio</td>
							<td width="100">Subtotal</td>
						</tr>
                        <?php 
                            $total = 0;
                            foreach($detail as $k){
				$precio_t = ($k['precio'] * $k['cantidad']);
				$total    = $total + $precio_t;
				$iva      = ($total * $k['impuesto']);
                          ?>   
                            <tr>
				<td><?php echo $k['cantidad'];?></td> 	
                                <td><?php echo $k['descripcion'];?></td> 	
                                <td><?php echo number_format($k['precio'],2);?></td> 	
                                <td align="right"><?php number_format($precio_t,2);?></td>
                            </tr>    
                        <?php
                            }
                            $height = 560 - (count($detail) * 35);
                        ?>
			
			
			<tr>
                                <td colspan="4" height="<?php echo $height;?>"></td>
                        </tr>
						
			<tr>
                            <td colspan="2"></td><td><b>Total</b></td><td align="right"><?php echo number_format($total,2);?></td>
                        </tr>
			<tr>
                            <td colspan="2"></td><td><b>Total con IVA</b></td><td align="right"><?php echo number_format(($total+$iva),2);?></td>
                        </tr>
                    </table>
		<table width="700" align="center">
			<tr>
				<td width="20"></td>
				<td style="padding-top:5px">
                                    N&deg; de Cuenta para abonos: <?php echo $datos_empresa["data"]['resolucion'];?>
				</td>
			</tr>
                        <tr>
				<td width="20"></td>
				<td style="padding-top:5px">
					Pagar con Paypal: <a href="<?php echo site_url('paypal/paypal_pay/'.$user_id.'/'.$data['id_factura']);?>">Aqu&iacute;</a>
				</td>
			</tr>
		</table>
                        <table width="700" align="center">
			<tr>
				<td>
                                   <?php echo $datos_empresa["data"]['terminos_condiciones'];?>
				</td>
			</tr>
		</table>
                        <table width="700" align="center" class="foot1">
                        <tr>
                                <td width="20"></td>
				<td><?php echo $datos_empresa["data"]['nombre'].' | '.$datos_empresa["data"]['direccion'];?></td>
			</tr>
                        <tr>
				<td width="20"></td>
				<td >
					Tel: <?php echo $datos_empresa["data"]['telefono'].' | Email: '.$datos_empresa["data"]['email'];?>
				</td>
			</tr>
                </table>
<div class="page-header">    
    <div class="icon">
        <img alt="Facturas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_venta']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Facturas", "Facturas");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):
                    $message_type = $this->session->flashdata('message_type');
            ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message;?>
                </div>
			<?php endif; 				
			?>
			
			<!--<div class="col-md-6">
					<a href="<?php echo site_url("administracion_vendty/facturas_licencia/nuevo")?>" data-tooltip="Nueva Factura">                        
						<img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 					
					</a>    
			</div>-->
		</div>
	</div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('', "Listado de Facturas");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="facturas">
							<thead>
								<tr>
									<th width="10%"><?php echo custom_lang('sima_image', "#Factura");?></th>									
									<th width="10%"><?php echo custom_lang('sima_image', "Fecha Factura");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Total factura");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Descuento");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Total Impuesto");?></th>	
									<th width="10%"><?php echo custom_lang('sima_image', "Estado Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Estado Factura");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Empresa Asociada");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Pago Asociado");?></th>	
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>	
								</tr>
							</thead>
							<tbody>
								
								<?php 
									foreach($data['facturas'] as $key =>$value){
										$estado=($value->estado_factura==1) ? 'Cancelada' : 'Pendiente Pago';										
										$estadofactura=($value->estado==1) ? 'Anulada' : '';										
										echo'<tr>';
											echo'<td><a target="_blank" href="facturas_licencia/pdf_factura/'.$value->id_factura_licencia.'">'.$value->numero_factura.'</a></td>';
											echo'<td>'.$value->fecha_factura.'</td>';
											echo'<td>'.$value->total_factura.'</td>';
											echo'<td>'.$value->valor_descuento_factura.'</td>';
											echo'<td>'.$value->total_impuesto_factura.'</td>';
											echo'<td>'.$estado.'</td>';
											echo'<td>'.$estadofactura.'</td>';
											echo'<td>'.$value->nombre_empresa.'</td>';
											if(isset($data['pagos'])){		
												if(empty($data['pagos'])){ 
													$value->id_pago="";
												}
												else{										
													foreach ($data['pagos'] as $key2 => $value2) {
														if($value["id_pago"]==$value2->id_pago){
															$value->id_pago=$value2->id_pago;
														}
													}
												}
											}
											echo'<td><a target="_blank" href="pagos_factura/ver_pago/'.$value->id_pago.'">'.$value->id_pago.'</a></td>';
											?>
											<td>
												<?php if($value->estado==0){ ?>												
												<a href="<?php echo site_url('administracion_vendty/facturas_licencia/anular_factura/'.$value->id_factura_licencia);?>" onclick="if(confirm('Esta seguro que desea Anular la Factura')){return true;}else{return false;}" class="button red"  data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-remove"></span></div></a></td>
											<?php }
										echo'</tr>';
									}
								?>
								
							</tbody>
							<tfoot>
								<tr>
									<th width="10%"><?php echo custom_lang('sima_image', "#Factura");?></th>									
									<th width="10%"><?php echo custom_lang('sima_image', "Fecha Factura");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Total factura");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Descuento");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Total Impuesto");?></th>	
									<th width="10%"><?php echo custom_lang('sima_image', "Estado Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Estado Factura");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Empresa Asociada");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Pago Asociado");?></th>	
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>	
								</tr>
							</tfoot>
							
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
	var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
	
	$(document).ready(function(){
		$('#facturas').dataTable({
			"aaSorting": [[ 0, "desc" ]],
			"bProcessing": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
		});	

	});
</script>


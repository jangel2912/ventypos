<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>
	<?php 
	if($this->uri->segment(4)==-1){
		$parametro="";
		$nombrebtn="Pagos con Facturas";
		$con="sin";
	}else{
		$parametro="-1";
		$nombrebtn="Pagos sin Facturas";
		$con="con";
	}
	
	?>
    <h1><?php echo custom_lang("PagosLicencias", "Pagos de Licencias $con Facturas");?></h1>

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
            <a href="<?php echo site_url("administracion_vendty/pagos_factura/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('', "Nuevo Pago");?></a>
            <div class="pull-right">
				<a href="<?php echo site_url("administracion_vendty/pagos_factura/index/".$parametro)?>" class="btn default"><?php echo custom_lang('', $nombrebtn);?></a>
            </div>
			            			
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todas los Pagos");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="pagos">
							<thead>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "#Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Forma Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Fecha Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Monto");?></th>
									<th width="5%"><?php echo custom_lang('price_active', "Descuento");?></th>	
									<th width="10%"><?php echo custom_lang('sima_image', "Estado");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Informacion del Pago");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Factura Asociada");?></th>
									<th width="15%"><?php echo custom_lang('sima_codigo', "Empresa Asociada");?></th>												
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>
								
								<?php 
									foreach($data['pagos'] as $key =>$value){
										$estado=($value['estado_pago']==1) ? 'Aprobado' : $value['observacion_pago'];										
										echo'<tr>';
											echo'<td>'.$value['idpagos_licencias'].'</td>';
											echo'<td>'.$value['nombre_forma'].'</td>';
											echo'<td>'.$value['fecha_pago'].'</td>';
											echo'<td>'.$value['monto_pago'].'</td>';
											echo'<td>'.$value['descuento_pago'].'</td>';
											echo'<td>'.$estado.'</td>';
											echo'<td>'.$value['info_adicional_pago'].'</td>';
											if(isset($data['facturas'])){		
												if(empty($data['facturas'])){ 
													$value['numero_factura']="";
												}
												else{										
													foreach ($data['facturas'] as $key2 => $value2) {
														if($value["id_factura_licencia"]==$value2['id_factura']){
															$value['numero_factura']=$value2['numero_factura'];
														}
													}
												}
											}
											$url=site_url('administracion_vendty/facturas_licencia/pdf_factura/'.$value["id_factura_licencia"]);
											echo'<td><a target="_blank" href="'.$url.'">'.$value['numero_factura'].'</a></td>';
											echo'<td>'.$value['nombre_empresa'].'</td>';

								?>
									<td>		
									<?php if(empty($value["id_factura_licencia"])){ ?>							
									<a href="<?php echo site_url('administracion_vendty/pagos_factura/editar/'.$value['idpagos_licencias']);?>" class="button default"  data-tooltip="Editar"><div><span class="glyphicon glyphicon-pencil"></span></div></a>												
									<a href="<?php echo site_url('administracion_vendty/pagos_factura/eliminar/'.$value['idpagos_licencias']);?>" onclick="if(confirm('Esta seguro que desea eliminar la licencia')){return true;}else{return false;}" class="button red"  data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-trash"></span></div></a>
									<?php } ?>
								<?php	
											echo'</td>';										
										echo'</tr>';
									}
								?>
								
							</tbody>
							
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
	var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
	
	$(document).ready(function(){
		$('#pagos').dataTable({
                "aaSorting": [[ 0, "desc" ]],
				"bProcessing": true,
				"sPaginationType": "full_numbers",
				 "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
		});
	});

</script>


<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Empresas", "Empresas - Licencias");?></h1>
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
            <?php endif; ?>            
			
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todas las licencias");?></h2>
            </div>
                <div class="data-fluid">		
					<table class="table" cellpadding="0" cellspacing="0" width="100%" id="empresa">
							<thead>
								<tr>
									<th width="30%"><?php echo custom_lang('sima_image', "Nombre empresa");?></th>	
									<th width="40%"><?php echo custom_lang('sima_image', "Dirección");?></th>	
									<th width="30%"><?php echo custom_lang('sima_image', "Teléfono");?></th>	
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $data['empresa'][0]->nombre_empresa ?></td>
									<td><?= $data['empresa'][0]->direccion_empresa ?></td>
									<td><?= $data['empresa'][0]->telefono_contacto ?></td>
								</tr>
							</tbody>
							
						</table>			
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="licencias">
							<thead>
								<tr>
									<th width="15%"><?php echo custom_lang('sima_almacen', "Almacen");?></th>	
									<th width="15%"><?php echo custom_lang('sima_fechainicio', "Fecha inicio licencia");?></th>	
									<th width="15%"><?php echo custom_lang('sima_fechavencimiento', "Fecha vencimiento");?></th>
									<th width="20%"><?php echo custom_lang('sima_plan', "Nombre Plan");?></th>
									<th width="15%"><?php echo custom_lang('sima_valor', "Valor");?></th>
									<th width="10%"><?php echo custom_lang('price_estado', "estado_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_acciones', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>								
								<?php 
									foreach($data['datoslicencias'] as $key =>$value){
										echo'<tr>';
											echo'<td id="almacen-'.$value->idempresas_clientes.'-'.$value->id_almacen.'"> '.$value->id_almacen.' </td>';					
											echo'<td>'.$value->fecha_inicio_licencia.'</td>';	
											echo'<td>'.$value->fecha_vencimiento.'</td>';
											echo'<td>'.$value->nombre_plan.'</td>';
											echo'<td>'.$value->valor_plan.'</td>';
											echo'<td>'; echo ($value->estado_licencia==1)? 'Activa' : 'Suspendida </td>';
											echo'<td>';
											?>
												<a href="<?php echo site_url('administracion_vendty/licencia_empresa/editar/'.$value->id_licencia);?>" class="button default"  data-tooltip="Editar licencia"><div><span class="glyphicon glyphicon-pencil"></span></div></a>
												<a href="<?php echo site_url('administracion_vendty/pagos_factura/nuevo/'.$value->id_licencia);?>" class="button default" data-tooltip="Agregar Pagos" ><div><span class="glyphicon glyphicon-credit-card"></span></div></a>			
											<?php	
											echo'</td>';
										echo'</tr>';
									}
								?>								
							</tbody>
							<tfoot>
								<tr>
									<th width="15%"><?php echo custom_lang('sima_almacen', "Almacen");?></th>	
									<th width="15%"><?php echo custom_lang('sima_fechainicio', "Fecha inicio licencia");?></th>	
									<th width="15%"><?php echo custom_lang('sima_fechavencimiento', "Fecha vencimiento");?></th>
									<th width="20%"><?php echo custom_lang('sima_plan', "Nombre Plan");?></th>
									<th width="15%"><?php echo custom_lang('sima_valor', "Valor");?></th>
									<th width="10%"><?php echo custom_lang('price_estado', "estado_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_acciones', "Acciones");?></th>
								</tr>
							</tfoot>
					</table>
				</div>
			</div>			
		</div>			
	</div>
			
<script type="text/javascript">
	$(document).ready(function(){
		//$('#licencias').DataTable();	
		$('#licencias').DataTable({
			"aaSorting": [[ 2, "asc" ]]
		});	
		
		$("[id*=almacen-").each(function(){		 
			consultar_almacen_empresa($(this).attr('id'));     
		});

	});

var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';

function consultar_almacen_empresa(id){	
	var id	=id;	
	var ids = id.split("-");
			
	if(!isNaN(ids[1])){
		$.ajax({
			type: "post",
			dataType: "json",
			url: url_consulta_almacenes,
			data:{id_empresa:ids[1],activo:1},
			success: function(result){	
				$("#"+id).html('');					
				$.each(result,function(index,value){		
					if(value.id==ids[2]){			
						$("#"+id).html(value.nombre);
					}					
				});					
			}
		});
	}
}
</script>


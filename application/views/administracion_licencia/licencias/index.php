<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Licencias", "Licencias");?></h1>

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
            <!--<a href="<?php echo site_url("administracion_vendty/licencia_empresa/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('', "Nueva Licencia");?></a>-->
            <a href="<?php echo site_url("administracion_vendty/licencia_empresa/import_excel")?>" class="btn default"><small class="ico-circle-arrow-up icon-white"></small><?php echo custom_lang('', "Importar excel");?></a>                       
			<!--<div style="float: right">
				<a href="<?php echo site_url("administracion_vendty/licencia_empresa/import_excel")?>" class="btn default"><small class="ico-circle-arrow-up icon-white"></small><?php echo custom_lang('', "Importar excel");?></a>                       
            </div>-->
			
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todas las Licencias");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="licencias">
							<thead>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "N°");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Empresa");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Almacén");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Plan");?></th>
									<th width="5%"><?php echo custom_lang('sima_image', "Valor");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Fecha_inicio_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Fecha_fin_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Estado_licencia");?></th>							
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>
								<!--
								<?php 
									foreach($data['datoslicencias'] as $key =>$value){
										echo'<tr>';
											echo'<td>'.$value->id_licencia.'</td>';
											echo'<td>'.$value->nombre_empresa.'</td>';
											echo'<td id="almacen-'.$value->idempresas_clientes.'-'.$value->id_almacen.'">'.$value->id_almacen.'</td>';
											echo'<td>'.$value->nombre_plan.'</td>';
											echo'<td>'.$value->valor_plan.'</td>';
											echo'<td>'.$value->fecha_inicio_licencia.'</td>';
											echo'<td>'.$value->fecha_vencimiento.'</td>';
											echo'<td>'; echo ($value->estado_licencia==1)? 'Activa' : 'Suspendida'; echo'</td>';											
											echo'<td>';
											$estado =($value->estado_licencia==1)? 'suspender' : 'activar';
								?>
									<a href="<?php echo site_url('administracion_vendty/licencia_empresa/desactivar/'.$value->id_licencia);?>" onclick="if(confirm('Esta seguro que desea <?= $estado ?> la licencia')){return true;}else{return false;}" class="button default"  data-tooltip="Desactivar"><div><span title="Desactivar Licencia" class="glyphicon glyphicon-transfer"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/licencia_empresa/editar/'.$value->id_licencia);?>" class="button default"  data-tooltip="Editar"><div><span class="glyphicon glyphicon-pencil"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/licencia_empresa/eliminar/'.$value->id_licencia);?>" onclick="if(confirm('Esta seguro que desea eliminar la licencia')){return true;}else{return false;}" class="button red"  data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-trash"></span></div></a>
									<a href="<?php echo site_url('administracion_vendty/pagos_factura/ver_pagos/'.$value->id_licencia);?>" class="button default" data-tooltip="Ver pagos" ><div><span class="glyphicon glyphicon-credit-card"></span></div></a>			
									
								<?php	
											echo'</td>';										
										echo'</tr>';
									}
								?>
								-->
							</tbody>
							<tfoot>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "N°");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Empresa");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Almacén");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Plan");?></th>
									<th width="5%"><?php echo custom_lang('sima_image', "Valor");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Fecha_inicio_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Fecha_fin_licencia");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Estado_licencia");?></th>							
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
		/*$('#licencias').dataTable({
                "aaSorting": [[ 0, "desc" ]],
				"bProcessing": true,
				"sPaginationType": "full_numbers",
				 "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
		});*/

		$('#licencias').dataTable( {

			"aaSorting": [[ 0, "desc" ]],

			"bProcessing": true,

			"bServerSide": true,

			"sAjaxSource": "<?php echo site_url("administracion_vendty/licencia_empresa/get_ajax_data_licencias");?>",

			"sPaginationType": "full_numbers",

			"iDisplayLength": 5, 
						
			"aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Todos"]],

			"aoColumnDefs" : [

				{ "bSortable": false, "aTargets": [ 8 ], "bSearchable": false,

					"mRender": function ( data, type, row ) {
						console.log(data);
						
						var buttons = "<div class='btnacciones'>";  							
							buttons += '<a href="<?php echo site_url("administracion_vendty/licencia_empresa/desactivar/");?>/'+data+'" onclick="if(confirm("Esta seguro que desea la licencia")){return true;}else{return false;}" class="button default"  data-tooltip="Activar/Desactivar"><div><span title="Desactivar Licencia" class="glyphicon glyphicon-transfer"></span></div></a>';                                                                           
							buttons += '<a href="<?php echo site_url("administracion_vendty/licencia_empresa/editar/");?>/'+data+'" class="button default"  data-tooltip="Editar"><div><span class="glyphicon glyphicon-pencil"></span></div></a>';
							buttons += '<a href="<?php echo site_url("administracion_vendty/pagos_factura/ver_pagos/");?>/'+data+'" class="button default" data-tooltip="Ver pagos" ><div><span class="glyphicon glyphicon-credit-card"></span></div></a>';
							buttons += '<a href="<?php echo site_url("administracion_vendty/licencia_empresa/eliminar/");?>/'+data+'" onclick="if(confirm("Esta seguro que desea eliminar la licencia")){return true;}else{return false;}" class="button red"  data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-trash"></span></div></a>';
							buttons += "</div>";
						return buttons;
					}
				}				
			]
		});
				

		$("[name=licencias_length]").on('change',function(){

			$("[id*=almacen-").each(function(){		 
				consultar_almacen_empresa($(this).attr('id'));     
			});
		});
		


	});
	
function consultar_distribuidores_licencia(){
	$.ajax({
		 type: 'post',
		 url: url_distribuidores,
		 data: {distribuidor:$("#field-id_distribuidores_licencia").val()},
		 dataType: 'json',
		 success: function(result){
		 	$("#field-id_user_distribuidor").find('option').remove();
		 	$.each(result,function(index,value){
		 		$("#field-id_user_distribuidor").append($('<option>', { value : value.id }).text(value.email));
		 	});
		 	$("#field-id_user_distribuidor").trigger("chosen:updated");
		 }

	});
}	

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


<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Empresas", "Empresas");?></h1>
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
            <a href="<?php echo site_url("administracion_vendty/empresas/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('', "Nueva Empresa");?></a>
            
			<div style="float: right">
				<a href="<?php echo site_url("administracion_vendty/empresas/import_excel")?>" class="btn default"><small class="ico-circle-arrow-up icon-white"></small><?php echo custom_lang('', "Importar excel");?></a>                       
            </div>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todas las empresas");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="empresas">
							<thead>
								<tr>
									<th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>									
									<th width="15%"><?php echo custom_lang('sima_image', "Distribuidor");?></th>
									<th width="15%"><?php echo custom_lang('sima_codigo', "Nombre distribuidor");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Telefono");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "País");?></th>									
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>
								<!--
								<?php 
									foreach($data['datos_empresas'] as $key =>$value){
										echo'<tr>';
											echo'<td>'.$value->nombre_empresa.'</td>';											
											echo'<td>'.$value->tipo_identificacion.' '.$value->identificacion_empresa.'</td>';
											echo'<td>'.$value->direccion_empresa.'</td>';
											echo'<td>'.$value->telefono_contacto.'</td>';
											echo'<td>';
											foreach($data['usuarios'] as $key1 => $email){
												if($value->idusuario_creacion==$email->id){
													echo $email->email;
												}
											}
											echo'</td>';											
											echo'<td>'.$value->pais.'</td>';
											echo'<td>';
								?>
									<a href="<?php echo site_url('administracion_vendty/almacenes_cliente/nuevo/'.$value->id_db_config);?>" class="button default" data-tooltip="Agregar Almacén"><div ><span class="glyphicon glyphicon-plus"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/empresas/verlicencias/'.$value->idempresas_clientes);?>" class="button default" data-tooltip="Ver Licencias"><div ><span class="glyphicon glyphicon-search"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/empresas/editar/'.$value->idempresas_clientes);?>" class="button default" data-tooltip="Editar"><div><span class="glyphicon glyphicon-pencil"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/empresas/eliminar/'.$value->idempresas_clientes);?>" onclick="if(confirm('Esta seguro que desea eliminar el registro?')){return true;}else{return false;}" class="button red" data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-trash"></span></div></a>
									
								<?php	
											echo'</td>';										
										echo'</tr>';
									}
								?>
								-->
							</tbody>
							<tfoot>
								<tr>
									<th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>									
									<th width="15%"><?php echo custom_lang('sima_image', "Distribuidor");?></th>
									<th width="15%"><?php echo custom_lang('sima_codigo', "Nombre distribuidor");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Telefono");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "País");?></th>									
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</tfoot>
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	$(document).ready(function(){
		//$('#empresas').DataTable();	
	});

    $(document).ready(function(){

		$('#empresas').dataTable( {

			"aaSorting": [[ 0, "desc" ]],

			"bProcessing": true,

			"bServerSide": true,

			"sAjaxSource": "<?php echo site_url("administracion_vendty/empresas/get_ajax_data_empresas");?>",

			"sPaginationType": "full_numbers",

			"iDisplayLength": 5, 
						
			"aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Todos"]],
			

			"aoColumnDefs" : [

				{ "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,

					"mRender": function ( data, type, row ) {
						var buttons = "<div class='btnacciones'>";   
							buttons += '<a href="<?php echo site_url("administracion_vendty/licencia_empresa/nuevo/");?>/'+data+'" class="button default" data-tooltip="Nueva Licencia"><div ><span class="glyphicon glyphicon-plus"></span></div></a>';                              
							buttons += '<a href="<?php echo site_url("administracion_vendty/empresas/configuracion/");?>/'+row[7]+'" class="button default" data-tooltip="Configuración"><div ><span class="glyphicon glyphicon-cog"></span></div></a>';                                                                           
							buttons += '<a href="#" onclick="cambiar_clave_admin('+row[8]+')" class="button default" data-tooltip="Reiniciar Clave" ><div ><span class="glyphicon glyphicon-refresh"></span></div></a>';
							buttons += '<a href="<?php echo site_url("administracion_vendty/almacenes_cliente/nuevo/");?>/'+row[7]+'" class="button default" data-tooltip="Agregar Almacén"><div ><span class="glyphicon glyphicon-plus"></span></div></a>';                                                                           
							buttons += '<a href="<?php echo site_url("administracion_vendty/empresas/verlicencias/");?>/'+data+'" class="button default" data-tooltip="Ver Licencias"><div ><span class="glyphicon glyphicon-search"></span></div></a>';                                                                           
							buttons += '<a href="<?php echo site_url("administracion_vendty/empresas/editar/");?>/'+data+'" class="button default" data-tooltip="Editar"><div><span class="glyphicon glyphicon-pencil"></span></div></a>';                                                                           
							buttons += '<a href="<?php echo site_url("administracion_vendty/empresas/eliminar/");?>/'+data+'" onclick="if(confirm("Esta seguro que desea eliminar el registro?"	)){return true;}else{return false;}" class="button red" data-tooltip="Eliminar"><div><span class="glyphicon glyphicon-trash"></span></div></a>';                                                                           
							buttons += "</div>";
						return buttons;
					}
				}				
			]
		});
    });

function cambiar_clave_admin(item_id){
	    swal({
            title: '¿Está seguro?',
            text: "Se reiniciará la clave del 1 al 9",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4cae4c',
            cancelButtonColor: '#e53935',
            confirmButtonText: 'Si, reiniciar'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url("administracion_vendty/empresas/cambiar_clave_admin")?>",
                    data: { id: item_id },
                    type: "POST",
                    success: function(response) {
						console.log(response.success);

						if(response.success==1){
							swal({
								position: 'center',
								type: 'success',
								title: "success",
								html: "La clave fue reiniciada correctamente",
								showConfirmButton: false,
								timer: 1500
							});                                                   
							
						}else{
							swal({
								position: 'center',
								type: 'error',
								title: "error",
								html: "La clave no pudo ser reiniciada o ya tiene asignada la clave del 1 al 9",
								showConfirmButton: false,
								timer: 1500
							});  
						}
                        /* */                       	    		
                    }
                });
                
            }
        })
    
    }
</script>
	

<script type="text/javascript">
var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
$(document).ready(function(){
	$("#field-id_distribuidores_licencia").on('change',function(){ 
		consultar_distribuidores_licencia();
	});
	$("#field-idempresas_clientes").on('change',function(){
		consultar_almacen_empresa();
	});

	$(".facturar_licencia").on('click',function(evt){
		console.log($(this).attr('href'));
		evt.preventDefault();
		var valor= prompt('Valor unitario de la licencia');
		if(isNaN(valor) ){
			alert('debe digitar un valor numerico');
		}else{
			window.location.href=$(this).attr('href')+'/'+valor
		}
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

function consultar_almacen_empresa(){
		var empresa = $("#field-idempresas_clientes").val();
		if(!isNaN(empresa)){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:empresa},
				success: function(result){
					
					$("#field-id_almacen").find('option').remove();
					$("#field-id_almacen").append($("<option></option>").attr("value",'').text('seleccione'));
					$.each(result,function(index,value){
						console.log(value);
						$("#field-id_almacen").append($("<option></option>").attr("value",value.id).text(value.nombre));
					});
					$("#field-id_almacen").trigger("chosen:updated");
				}

			});
		}
	}
</script>


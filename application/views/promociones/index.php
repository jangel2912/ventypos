<div class="page-header">    
    <div class="icon">
        <img alt="Promociones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_promociones']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Promociones", "Promociones");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
			<div id="message">
			</div>
			<?php
			$message = $this->session->flashdata('message');
			if(!empty($message)):?>

				<div class="alert alert-success">
					<?php echo $message;?>
				</div>

			<?php endif; ?>
			<?php 
			$is_admin = $this->session->userdata('is_admin');
			$permisos = $this->session->userdata('permisos');
			// PENDIENTE AGREGAR PERMISO || in_array("28", $permisos)
			if($is_admin == 't'):?>
				<!--<a href="<?php echo site_url("promociones/crear")?>" class="btn btn-success"> <?php echo custom_lang('sima_new_quote', "Nueva Promoción");?></a>-->
				<a href="<?php echo site_url("promociones/crear")?>" data-tooltip="Nueva Promoción">
					<img alt="Promoción" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                      
				</a> 
			<?php endif;?>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="block">			
			<div class="head blue">				
				<h2><?php echo custom_lang('sima_all_quotes', "Listado de Promociones");?></h2>
			</div>
			<div class="data-fluid">
				<table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="main_table">
					<thead>
						<tr>
							<th width="30%"><?php echo custom_lang('sima_nombre', "Nombre");?></th>
							<th width="10%"><?php echo custom_lang('sima_dias', "Días");?></th>
							<th width="15%"><?php echo custom_lang('sima_fecha_inicio', "Fecha Inicio");?></th>
							<th width="15%"><?php echo custom_lang('sima_fecha_fin', "Fecha Fin");?></th>
							<th width="10%"><?php echo custom_lang('sima_activo', "Activo");?></th>
							<th width="10%"><?php echo custom_lang('sima_tipo', "Tipo");?></th>
							<th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th width="30%"><?php echo custom_lang('sima_nombre', "Nombre");?></th>
							<th width="10%"><?php echo custom_lang('sima_dias', "Días");?></th>
							<th width="15%"><?php echo custom_lang('sima_fecha_inicio', "Fecha Inicio");?></th>
							<th width="15%"><?php echo custom_lang('sima_fecha_fin', "Fecha Fin");?></th>
							<th width="10%"><?php echo custom_lang('sima_activo', "Activo");?></th>
							<th width="10%"><?php echo custom_lang('sima_tipo', "Tipo");?></th>
							<th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		$.fn.dataTable.ext.errMode = 'throw';

		$('#main_table').dataTable( {
			"aaSorting": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "<?php echo site_url('promociones/getAjaxData');?>",
			"sPaginationType": "full_numbers",
			"iDisplayLength": 5,
			"aLengthMenu": [5,10,25,50,100],
			"aoColumnDefs" : [
				{
					"bSortable": false,
					"aTargets": [ 6 ],
					"bSearchable": false,
					"mRender": function (data, type, row) {
						var buttons = "<div class='btnacciones'>";
						buttons += '<a data-role="editar" data-tooltip="Editar" href="<?php echo site_url("promociones/editar/");?>/'+data+'" class="button default btn-print acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
						buttons += '<a data-role="eliminar" data-tooltip="Eliminar" href="<?php echo site_url("promociones/eliminar/");?>/'+data+'" class="button red btn-print acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>'; 
						buttons += "</div>";
						return buttons;
					}
				}
			]
		});

		$('#main_table').delegate('a[data-role="eliminar"]', 'click', function(e)
		{
			var td = $(this).closest('tr').find('td').first();
			var href = $(this).prop('href');

			console.log(href);

			var r = confirm("¿Realmente desea eliminar la promoción \""+td.text()+"\"?");
			if (r == true) 
				location.href = href;
			
			e.preventDefault();
		});
	});
</script>

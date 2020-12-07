<div class="page-header">
    <div class="icon">
        <span class="ico-file"></span>
    </div>
    <h1><?php echo custom_lang("Carga_de_datos", "Actualización de productos");?></h1>
</div>
<div class="row-fluid">
	<div class="span12">
		<br>
	</div>
</div>
<form action="<?= site_url('productos/importar_base_productos') ?>" enctype="multipart/form-data" method="post">
	<div class="contenido">
		<div class="row-fluid">
			<div class="span12">
				<h4>
					Bienvenido siga los siguientes pasos para actualizar los productos desde un archivo Excel.
				</h4>
				<br>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<?php if($data['estado'] == 'error') { ?>
					<div class="alert alert-error">
						<?= $data['upload_error']; ?>
					</div>
				<?php } else if($data['estado'] == 'ok') { ?>
					<div class="alert alert-success">
						<?= $data['upload_status']; ?>
					</div>
				<?php } ?>
				<br>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				1. Seleccione los campos que desea actualizar y luego descargue la plantilla.
				<br><br>
				<select multiple name="campos" id="campos" class="multiple">
					<option value="Precio compra">Precio compra</option>
					<option value="Precio venta">Precio venta</option>
					<option value="Stock minimo">Stock mínimo</option>
					<option value="Stock maximo">Stock máximo</option>
					<option value="Impuesto">Impuesto</option>
					<option value="Descripcion">Descripción</option>
					<option value="Activo">Activo</option>
					<option value="Fecha vencimiento">Fecha vencimiento</option>
				</select>
			</div>
			<div class="span4">
				2. Descargue la plantilla
				<br><br><br>
				<a id="btn_exportar" data-url="<?= site_url('productos/exportar_base_productos') ?>" href="<?= site_url('productos/exportar_base_productos') ?>">
					<div class="icon">
						<span class="ico-download"></span>
    				</div>
    				Descargar archivo
    			</a>
			</div>
			<div class="span4">
				3. Una vez se finalice la edición del archivo seleccionelo y haga click en el botón enviar.
				<br><br>
				<div class="row-fluid">
	                <div class="span12">
	                    <input type="file" name="archivo"/>
					</div>
					
	            </div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<br>		
		<a href="<?= site_url('configuracion/carga_de_datos') ?>" class="btn btn-default">Regresar</a>
		<input type="submit" class="btn btn-success" value="Enviar">
	</div>
</form>
<script>
	$(function(e) {

		var agregarcampo = function()
		{
			var vars = '';
			var href = $('#btn_exportar').data('url');
			$('select.multiple option:selected').each(function(i, e){
				vars += $(e).val()+'|';
			});

			$('#btn_exportar').prop('href', href+'/'+vars);
		}

		$('select.multiple').mousedown(function(e){
		    e.preventDefault();
			var select = this;
		    var scroll = select.scrollTop;
		    e.target.selected = !e.target.selected;
		    setTimeout(function(){select.scrollTop = scroll;}, 0);
		    $(select).focus();
		    agregarcampo();
		}).mousemove(function(e){e.preventDefault()});
	});
</script>

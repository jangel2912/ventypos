<div class="page-header">
    <div class="icon">
        <span class="ico-file"></span>
    </div>
    <h1><?php echo custom_lang("Carga_de_datos", "Importar productos con atributos");?></h1>
</div>
<div class="row-fluid">
	<div class="span12">
		<br>
	</div>
</div>
<form action="<?= site_url('productos/importar_productos_con_atributos') ?>" enctype="multipart/form-data" method="post">
	<div class="contenido">
		<div class="row-fluid">
			<div class="span12">
				<h4>
					Bienvenido siga los siguientes pasos para importar productos con atributos desde un archivo Excel.
				</h4>
				<br>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<?php if($data['estado'] == 'error') { ?>
					<div class="alert alert-error">
						<?php
							echo $data['upload_error'].'<br>'.$data['upload_status']; 
						?>
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
				1. Descargue la plantilla
				<br><br>
				<a id="btn_exportar" data-url="<?= site_url('productos/exportar_base_productos_con_atributos') ?>" href="<?= site_url('productos/exportar_base_productos_con_atributos') ?>">
					<div class="icon">
						<span class="ico-download"></span>
    				</div>
    				Descargar archivo
    			</a>
			</div>
			<div class="span4">
				2. Una vez se finalice la edición del archivo seleccionelo y haga click en el botón enviar.
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

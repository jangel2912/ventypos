<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("configuracion_factura_electronica", "Configuracion factura electronica"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>


<?php echo form_open_multipart('factura_electronica/guardar_configuracion',array('id'=>'f_factura_electronica')); ?>
<div class="row-form">
		<div class="span5">
			<label for="fi_certificado_sello"><?php echo custom_lang('certificado_de_sellos','Certificado de sellos'); ?></label>
		</div>
		<div class="span7">
			<input type="file" name="fi_certificado_sello" id="fi_certificado_sello">
			<?php echo form_error('fi_certificado_sello'); ?>
		</div>		
	
</div>
<div class="row-form">	
		<div class="span5">
			<label for="fi_clave_privada_certificado"><?php echo custom_lang('clave_privada','Archivo de clave privada del certificado'); ?></label>
		</div>	
		<div class="span7">
			<input type="file" name="fi_clave_privada_certificado" id="fi_clave_privada_certificado">
			<?php echo form_error('fi_clave_privada_certificado'); ?>	
		</div>
</div>
<div class="row-form">	
		<div class="span3">
			<label for="t_clave_archivo_cifrado"><?php echo custom_lang('clave_archivo_certifico','Contraseña para clave privada') ?>:</label>
		</div>
		<div class="span4">
			<input type="password" name="t_clave_archivo_cifrado" id="t_clave_archivo_cifrado">
			<?php echo form_error('t_clave_archivo_cifrado'); ?>
		</div>
	
</div>
<div class="row-form">
	<div class="span8">
		<?php 
		if(isset($mensaje_archivos)){ ?>
				<div class="alert alert-error">
					<?php echo $mensaje_archivos ?>
				</div>
		<?php	} ?>
	</div>
</div>
<div class="row-form">
	<div class="span6">
	 <div class="btn-group">
        	<button class="btn btn-success" type="submit">Guardar</button>&nbsp;&nbsp;
        	<button class="btn btn-warning" type="button" onclick="javascript:location.href='../frontend/configuracion'">Cancelar</button>
     </div>
    </div>
</div>

<?php echo form_close(); ?>

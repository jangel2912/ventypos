<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1>
    	<?php $is_admin = $this->session->userdata('is_admin'); echo custom_lang("Inventario", "Inventario"); ?>
    	<small><?php echo $this->config->item('site_title');?></small>
    </h1>
</div>
<?php 

$is_admin = $this->session->userdata('is_admin');
$permisos = $this->session->userdata('permisos');
$entrada = in_array('1019', $permisos) || $is_admin == 't' ? 1 : 0;
$salida = in_array('1020', $permisos) || $is_admin == 't' ? 1 : 0;
$traslado = in_array('1021', $permisos) || $is_admin == 't' ? 1 : 0;

echo form_open_multipart("inventario/import_lista_codigos", array("id" =>"validate")); ?>
<div class="block title">
    <div class="head">
        <h4>Bienvenido siga los siguientes pasos para subir inventario desde una lista de códigos </h4> 
		<br />
        <h5>1. Haga click en la siguiente enlace para descargar la plantilla txt &nbsp;&nbsp;<a target="_blank" href="<?php echo base_url("/uploads1/Plantilla codigos.txt"); ?>" download>CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Inventario.</h5>
        <h5>2. Escoja la siguientes opciones:</h5>
        <div class="row-fluid">
            <div class="block">
                <div class="data-fluid">
                    <div class="span6">
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>
                                    <?php echo form_error('fecha'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('tipo_movimiento', "Tipo");?>:</div>
                            <div class="span9" id="movimiento" data-can="<?= $entrada.'-'.$salida.'-'.$traslado ?>">
                                    <?php echo form_dropdown('tipo_movimiento', $data['tipo']); ?>
                                    <?php echo form_error('tipo_movimiento'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('almacen', "Almacen");?>:</div>
                            <div class="span9">
                                    <?php
                                    if($is_admin == 's'){
                                     echo $data['almacen_nombre']; 
                                       ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen_id" id="almacen_id"/><?php
                                    } 
                                    else{
                                     echo form_dropdown('almacen_id', $data['almacenes']); 
                                    }
                                     ?>
                                    <?php echo form_error('almacen_id'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_date_v', "Proveedor");?>:</div>
                            <div class="span9">
                                    <?php echo form_dropdown('proveedor_id', $data['proveedores']) ?>
                                    <?php echo form_error('proveedor_id'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_date_v', "Codigo factura");?>:</div>
                            <div class="span9">
                                    <?php echo form_input('codigo_factura') ?>
                                    <?php echo form_error('codigo_factura'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_date_v', "Almacen de traslado");?>:</div>
                            <div class="span9">                                            
                                   <?php
                                    if($is_admin == 's'){
                                     echo $data['almacen_nombre']; 
                                       ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen_traslado_id" id="almacen_traslado_id"/><?php
                                    } 
                                    else{
                                      echo form_dropdown('almacen_traslado_id', $data['almacenes'], '', "disabled='disabled'"); 
                                    }
                                    ?>
                                    <?php echo form_error('almacen_traslado_id'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h5>3. Seleccione un archivo y haga click en enviar:</h5>
		    <div class="block">
		        <div class="data-fluid">
		        	<div class="span6">
        				<div class="block">
        					<?php
                			$message = $this->session->flashdata('message');
            				if(!empty($message)):?>
								<div class="alert alert-error">
	                				<?php echo $message;?>
	            				</div>
                			<?php endif; ?>

                            <?php echo validation_errors(); ?>

                            <div class="data-fluid">
                                <div class="row-form">
                                    <div class="span3">
                                    	<?php echo custom_lang('sima_file', "Archivo");?>:<br/>
                                    </div>
                                    <div class="span9">                            
                                        <div class="input-append file">
                                            <input type="file" name="archivo"/>
                                            <input type="text"/>
                                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>
                                        </div> 
                                        <?php echo $data['data']['upload_error']; ?>
                                    </div>
                                </div> 
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" onclick="javascript:this.form.submit(); this.disabled=true;" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
										<button class="btn btn-warning" type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
    					</div>
    				</div>
		        </div>
	        </div>
	    </div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var can = $('#movimiento').data('can');
    var select_movimiento = $('select[name="tipo_movimiento"]');
    var movimientos = can.split('-');

    console.log(movimientos, can);
    $.each(movimientos, function(i, e)
    {
        var selector = '';
        switch(i)
        {
            case 0:
                selector = 'entrada';
            break;
            case 1:
                selector = 'salida';
            break;
            case 2:
                selector = 'traslado';
            break;
        }

        if(e == 0)
            select_movimiento.find('option[value^="'+selector+'"]').remove();
            
    });

    select_movimiento.trigger('change');
</script>

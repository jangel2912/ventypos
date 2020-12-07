<script type="text/template" id="qq-template-gallery">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Arrastre archivos aca">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Cargar archivo</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Cargando archivos...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <div class="qq-thumbnail-wrapper">
                        <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                    </div>
                    <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                    <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                        <span class="qq-btn qq-retry-icon" aria-label="Reintentar"></span>
                        Reintentar
                    </button>

                    <div class="qq-file-info">
                        <div class="qq-file-name">
                            <span class="qq-upload-file-selector qq-upload-file"></span>
                            <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                        </div>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                            <span class="qq-btn qq-delete-icon" aria-label="Borrar"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                            <span class="qq-btn qq-pause-icon" aria-label="Pausar"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                            <span class="qq-btn qq-continue-icon" aria-label="Continuar"></span>
                        </button>
                    </div>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cerrar</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Si</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancelar</button>
                    <button type="button" class="qq-ok-button-selector">Aceptar</button>
                </div>
            </dialog>
        </div>
    </script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/select/select.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/bootstrap-toggle/bootstrap-toggle.css">
<link href="<?php echo base_url("public/css"); ?>/icheck/all.css" rel="stylesheet">
<link href="<?php echo base_url("public/css"); ?>/bloques_y_paneles.css" rel="stylesheet">
<link href="<?php echo base_url("public/css"); ?>/icheck/square/green.css" rel="stylesheet">
<link href="<?php echo base_url("public/css"); ?>/fineuploader/fine-uploader-gallery.css"  rel="stylesheet">
<script src="<?php echo base_url("public/js"); ?>/plugins/icheck/icheck.js"></script>
<script src="<?php echo base_url("public/js"); ?>/plugins/select/select2.min.js"></script>
<script src="<?php echo base_url("public/js"); ?>/productos_restaurante.js"></script>
<script src="<?php echo base_url("public/js"); ?>/plugins/bootstrap-toggle/bootstrap-toggle.js"></script>
<script src="<?php echo base_url("public/js"); ?>/plugins/fineuploader/jquery.fine-uploader.js"></script>


<div class="row">
 <div class="panel newPanel" style="overflow: hidden;!important">
 	<div class="col-md-12 tituloInstruccion">
 		<h3>Datos principales del nuevo producto</h3>
 	</div>
 	<div class="col-md-6">
		<div class="col-md-12">
			<label><?php echo custom_lang('sisma_nombre_producto','Nombre del producto final') ?>:</label>
		</div>
		
		<div class="col-md-12">
			<input type="text" name="t_nombre_producto" id="t_nombre_producto"	>
		</div>
		<div class="col-md-12">
			<label><?php echo custom_lang('sisma_componente_producto','Como se compone el producto') ?></label>
		</div>
		<div class="col-md-12">
			<select name="s_componentes_producto" id="s_componentes_producto">
				<option value="s_ing_"></option>
			</select>
		</div>
		<div class="col-md-12">
			<label>Categoria producto:</label>
		</div>
		
		<div class="col-md-12">
			<select name="s_categorias_prducto" id="s_categorias_prducto" data-placeholder="Seleccione" style="width: 100%" >
				<option value="">Seleccione</option>
				<?php foreach ($categorias as $key => $value) { ?>
					<option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>	
				<?php } ?>
			</select>
			<div id="div_consulta_ajax"></div>
		</div>

		<div class="col-md-12">
			<label><?php echo custom_lang('tamanos_disponibles_producto','Tamaños disponibles del producto') ?>:</label>
		</div>
		<div class="col-md-12" id="div_tamanos_categoria" >
			
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
			Imagen Principal:
			<div id="div_uploader">
				
			</div>
			
		</div>
	</div>		
 </div>
 	
		
</div>

<div class="row">
	<div class="panel newPanel" style="overflow: hidden;!important">
		<div class="col-md-12 tituloInstruccion">
			<center><h2>Como se compone el producto</h2></center>		
		</div>
	
		<div class="col-md-12">
			<div class="col-md-4" id="div_contenedor_ing_base">
				 <div class="contListas">
				 	<div class="well">
				 		<div>
				 			<div class="izq"><?php echo custom_lang('ingredientes_base','Ingredientes base') ?></div>
					 		<hr>
					 		<div class="listasCont intruct ing_base">
					 			<label><?php echo custom_lang('ingrediente','Ingrediente') ?></label>
					 			<select name="s_ingrediente_base" id="s_ing_base" data-posicion="0" class="s_ingrediente" style="width: 70%">
									<option value="">Seleccione</option>
									<?php foreach ($lista_ingredientes['base'] as $key => $value) { ?>
										<option data-img="<?php echo $value->imagen ?>" data-unidad="<?php echo $value->nombre_unidad ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
									<?php } ?>	
								</select>	
				 				<div class="div_input_tamanos" id="div_tamanos_ing_base" >
									<h5> Defina la cantidad del ingrediente utilizada en cada tamaño </h5>
								</div>
								<div class="div_opciones_ingrediente">
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
									  		<input type="checkbox" name="ch_intercambiable" id="ch_ing_base_cambiable" checked class="ch_opciones_ingredientes" > se puede cambiar ?
										</label>	
									</div>
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
											<input type="checkbox" name="ch_aumenta_porcion" id="ch_ing_base_aumento" class="ch_opciones_ingredientes" >se puede aumentar de porcion ?
										</label>	
									</div>						
								</div>
								<div id="div_mensajes_ing_base">
									
								</div>		
								<button id="btn_agregar_base" class="btn"><span class="ico-plus"></span><?php echo custom_lang('agregar_ingrediente','Agregar Ingrediente'); ?></button>
					 		</div>
				 		</div>
				 	</div>
				 </div>			
			</div>
			<div class="col-md-4" id="div_contenedor_ing_adicionales">
				<div class="contListas">
					<div class="well">
						<div>
							<div class="izq"><?php echo custom_lang('adicionales','Adicionales') ?></div>
							<hr>
							<div class="listasCont intruct">
								<label><?php echo custom_lang('ingrediente','Ingrediente') ?></label>			
								<select name="s_ingrediente_base[0]" data-posicion="0" class="s_ingrediente" style="width: 70%">
									<option value="">Seleccione</option>
									<?php foreach ($lista_ingredientes['adicion'] as $key => $value) { ?>
										<option data-img="<?php echo $value->imagen ?>" data-unidad="<?php echo $value->nombre_unidad ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
									<?php } ?>	
								</select>
								<div class="div_input_tamanos" id="div_tamanos_ing_base" >
									<h5> Defina la cantidad del ingrediente utilizada en cada tamaño </h5>
								</div>
								<div class="div_opciones_ingrediente">
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
									  		<input type="checkbox" name="ch_intercambiable" checked class="ch_opciones_ingredientes" > se puede cambiar ?
										</label>	
									</div>
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
											<input type="checkbox" name="ch_aumenta_porcion" class="ch_opciones_ingredientes" >se puede aumentar de porcion ?
										</label>	
									</div>						
								</div>	
								<button id="btn_agregar_adicional" class="btn"><span class="ico-plus"></span><?php echo custom_lang('agregar_adicional','Agregar adicional'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4" id="div_contenedor_ing_salsas">
				<div class="contListas">
					<div class="well">
						<div>
							<div class="izq"><?php echo custom_lang('salsas','Salsas') ?></div>
							<hr>
							<div class="listasCont intruct">
								<label><?php echo custom_lang('ingrediente','Ingrediente') ?></label>
								<select name="s_ingrediente_base[0]" data-posicion="0" class="s_ingrediente" style="width: 70%">
									<option value="">Seleecione</option>
									<?php foreach ($lista_ingredientes['salsa'] as $key => $value) { ?>
										<option data-img="<?php echo $value->imagen ?>" data-unidad="<?php echo $value->nombre_unidad ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
									<?php } ?>	
								</select>
								<div class="div_input_tamanos" id="div_tamanos_ing_base" >
									<h5> Defina la cantidad del ingrediente utilizada en cada tamaño </h5>
								</div>
								<div class="div_opciones_ingrediente">
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
									  		<input type="checkbox" name="ch_intercambiable" checked class="ch_opciones_ingredientes" > se puede cambiar ?
										</label>	
									</div>
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
											<input type="checkbox" name="ch_aumenta_porcion" class="ch_opciones_ingredientes" >se puede aumentar de porcion ?
										</label>	
									</div>						
								</div>
								<button id="btn_agregar_salsa" class="btn"><span class="ico-plus"></span><?php echo custom_lang('agregar_salsa','Agregar salsa'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="col-md-4" id="div_contenedor_ing_insumos">
				<div class="contListas">
					<div class="well">
						<div>
							<div class="izq"><?php echo custom_lang('insumos','Insumos') ?></div>
							<hr>
							<div class="listasCont intruct">
								<label><?php echo custom_lang('ingrediente','Ingrediente') ?></label>
								<select name="s_ingrediente_base[0]" data-posicion="0" class="s_ingrediente" style="width: 70%">
									<option value="">Seleecione</option>
									<?php foreach ($lista_ingredientes['insumos'] as $key => $value) { ?>
										<option data-img="<?php echo $value->imagen ?>" data-unidad="<?php echo $value->nombre_unidad ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
									<?php } ?>	
								</select>
								<div class="div_input_tamanos" id="div_tamanos_ing_base" >
									<h5> Defina la cantidad del ingrediente utilizada en cada tamaño </h5>
								</div>
								<div class="div_opciones_ingrediente">
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
									  		<input type="checkbox" name="ch_intercambiable" checked class="ch_opciones_ingredientes" > se puede cambiar ?
										</label>	
									</div>
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
											<input type="checkbox" name="ch_aumenta_porcion" class="ch_opciones_ingredientes" >se puede aumentar de porcion ?
										</label>	
									</div>						
								</div>
								<button id="btn_agregar_insumo" class="btn"><span class="ico-plus"></span><?php echo custom_lang('agregar_insumo','Agregar insumo'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4" id="div_contenedor_ing_otros">
				<div class="contListas">
					<div class="well">
						<div>
							<div class="izq"><?php echo custom_lang('otros','Otros') ?></div>
							<hr>
							<div class="listasCont intruct">
								<label><?php echo custom_lang('ingrediente','Ingrediente') ?></label>		
								<select name="s_ingrediente_base[0]" data-posicion="0" class="s_ingrediente" style="width: 70%">
									<option value="">Seleccione</option>
									<?php foreach ($lista_ingredientes['base'] as $key => $value) { ?>
										<option data-img="<?php echo $value->imagen ?>" data-unidad="<?php echo $value->nombre_unidad ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
									<?php } ?>	
								</select>		
								<div class="div_input_tamanos" id="div_tamanos_ing_base" >
									<h5> Defina la cantidad del ingrediente utilizada en cada tamaño </h5>
								</div>
								<div class="div_opciones_ingrediente">
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
									  		<input type="checkbox" name="ch_intercambiable" checked class="ch_opciones_ingredientes" > se puede cambiar ?
										</label>	
									</div>
									<div class="checkbox" style="margin-bottom: 2em">
										<label class="checkbox-inline">
											<input type="checkbox" name="ch_aumenta_porcion" class="ch_opciones_ingredientes" >se puede aumentar de porcion ?
										</label>	
									</div>						
								</div>
								<button id="btn_agregar_otros" class="btn"><span class="ico-plus"></span><?php echo custom_lang('agregar_otros','Agregar otros'); ?></button>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>	
</div>


<div class="row">
	<div class="panel newPanel" style="overflow: hidden;!important">
		<div class="col-md-12 tituloInstruccion">
			<h3>Precio de venta de producto:</h3>
		</div>
		<div class="col-md-12">
			<div id="div_precios_tamanos" style="padding: 1em"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="panel newPanel" style="overflow: hidden;!important">
		<div class="col-md-12 tituloInstruccion">
			<center><h3>Resumen del nuevo producto</h3></center>
		</div>
		<div class="col-md-12">
			<table id="tb_resumen_producto" class="table table-border table-condenced">
				<thead>
					<tr>
						<th><?php echo custom_lang('tipo_ingrediente','Tipo de ingrediente') ?></th>
						<th><?php echo custom_lang('nombre','nombre') ?></th>
						<th><?php echo custom_lang('cantidad','cantidad') ?></th>
						<th><?php echo custom_lang('se_cambiar','Se puede cambiar') ?></th>
						<th><?php echo custom_lang('se_aumenta','Se puede aumentar') ?></th>
						<th><?php echo custom_lang('acciones','Acciones') ?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>	
	</div>		
</div>
<div class="row">
     <div class="col-xs-12">
         
     </div>
     <div class="col-xs-12">
        <div id="div_mensajes"> 

        </div>
     </div>
 </div>  
<div class="row">
	<div class="col-md-12">
		<div class="btn-group">
        	<button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar"); ?></button>
        	<button class="btn btn-warning"  type="button" onclick="javascript:location.href = '../productos/index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
    	</div>
	</div>
</div>

<script type="text/javascript">
	var url_base_imagen = '<?php echo base_url() ?>';
	var url_consulta_tamanos_categoria = '<?php echo site_url('tamanos_productos/get_tamanos_categoria') ?>';
	const LABEL_CANTIDAD = '<?php echo custom_lang('cantidad','Cantidad') ?>';
	var ingredientes_seleccionados = [];
</script>

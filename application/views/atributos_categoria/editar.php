<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Categorias", "Atributos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_category', "Categorias");?></h2>                                          

    </div>

</div>

<?php echo form_open('atributo_categorias/editar/'.$data['categoria']['aaData'][0][1], array('method' => 'post')); ?>
	<div class="row-fluid">

	    <div class="span6">
	        <div class="block">
				Nombre: <input type="text" name="nombre" value="<?php echo $data['categoria']['aaData'][0][0] ?>">
			</div>
	        <div class="block">
				Atributos para ésta categoría:
	            <select name="atributos[]" multiple="multiple" id="atr" style="position: absolute; left: -9999px;">
	                <?php foreach ($data['atributos'] as $kAtributos => $vAtributos) { ?>
	                	<?php
	                		$selected = '';

	                		foreach ($data['atributos_categoria'] as $kCategoria => $vCategoria) {
	                			if($vCategoria->atributo_id == $vAtributos->id) $selected = "selected";
	                		}
	                	?>
	                	<option value="<?php echo $vAtributos->id ?>" <?php echo $selected ?>><?php echo $vAtributos->nombre ?></option>
	                <?php } ?>
	            </select>
			</div>
			
			<div class="data-fluid">
				<div class="row-form">
					<div class="span2"><button class="btn btn-success" name="nuevo" type="submit" value=true><?php echo custom_lang("sima_submit", "Guardar");?></button></div>
					&nbsp;
					<div class="span2"><button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button></div>
				</div>
			</div>
		</div>
	</div>
</form>
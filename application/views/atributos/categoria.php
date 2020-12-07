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

<div class="row-fluid">

    <div class="span6">
        
        <div class="block">
			Nombre: <input type="text" name="nombre">
		</div>
        <div class="block">
			Atributos:
            <select name="ms_example" multiple="multiple" id="msc" style="position: absolute; left: -9999px;">
                <?php foreach ($data['atributos'] as $kAtributos => $vAtributos) { ?>
                	<option value="<?php echo $vAtributos->id ?>"><?php echo $vAtributos->nombre ?></option>
                <?php } ?>
            </select>
		</div>
		
		<div class="data-fluid">
			<div class="row-form">
				<div class="span2"><button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button></div>
				&nbsp;
				<div class="span2"><button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button></div>
			</div>
		</div>
	</div>
</div>
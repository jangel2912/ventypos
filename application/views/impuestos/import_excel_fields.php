<div class="page-header">
    <div class="icon">
        <span class="ico-cogs"></span>
    </div>
    <h1><?php echo custom_lang("Configuracion", "Configuraci&oacute;n");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_select_relation', "Seleccione la relación de campos para importar");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("impuestos/import_excel", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nombre", $data["campos"]); ?>
                                            <?php echo form_error('nombre'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_tax_percent', "Porciento");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("porciento", $data["campos"]); ?>
                                        <?php echo form_error('porciento'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit" name="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
    </div>
</div>
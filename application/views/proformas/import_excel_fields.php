<div class="page-header">
    <div class="icon">
        <span class="ico-money-bag"></span>
    </div>
    <h1><?php echo custom_lang("Gastos", "Gastos");?><small><?php echo $this->config->item('site_title');?></small></h1>
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
                                <?php echo form_open("proformas/import_excel", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nombre_comercial", $data["campos"]); ?>
                                            <?php echo form_error('nombre_comercial'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nif_cif", $data["campos"]); ?>
                                        <?php echo form_error('nif_cif'); ?>
                                    </div>
                                </div>   
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("email", $data["campos"]); ?>
                                        <?php echo form_error('email'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_tax_name', "Nombre del impuesto");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nombre_impuesto", $data["campos"]); ?>
                                            <?php echo form_error('nombre_impuesto'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_tax_percent', "Porciento");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("porciento", $data["campos"]); ?>
                                        <?php echo form_error('porciento'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_amount', "Cantidad");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("amount", $data["campos"]); ?>
                                        <?php echo form_error('amount'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_notes', "Notas");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("sima_notes", $data["campos"]); ?>
                                        <?php echo form_error('sima_notes'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("fecha", $data["campos"]); ?>
                                        <?php echo form_error('fecha'); ?>
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
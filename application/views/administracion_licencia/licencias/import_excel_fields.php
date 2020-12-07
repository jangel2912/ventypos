<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Licencias", "Licencias");?></h1>

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
                <?php echo form_open("administracion_vendty/licencia_empresa/import_excel", array("id" =>"validate"));?>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre Empresa");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("nombre_empresa", $data["campos"]); ?>
                            <?php echo form_error('nombre_empresa'); ?>
                        </div>
                    </div>                                
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "Plan");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("plan", $data["campos"]); ?>
                            <?php echo form_error('plan'); ?>
                        </div>

                    </div>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "fecha_inicio_licencia");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("fecha_inicio_licencia", $data["campos"]); ?>
                            <?php echo form_error('fecha_inicio_licencia'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "fecha_vencimiento");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("fecha_vencimiento", $data["campos"]); ?>
                            <?php echo form_error('fecha_vencimiento'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "almacen");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("almacen", $data["campos"]); ?>
                            <?php echo form_error('almacen'); ?>
                        </div>

                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "estado_licencia");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("estado_licencia", $data["campos"]); ?>
                            <?php echo form_error('estado_licencia'); ?>
                        </div>
                    </div>     

                    <div class="toolbar bottom tar">
                        <button class="btn btn-default" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                        <button class="btn btn-success" type="submit" name="submit"><?php echo custom_lang("sima_submit", "Subir");?></button>  
                    </div>
                </form>
            </div>
        </div>
    </div>   
</div>
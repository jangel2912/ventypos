<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Empresas", "Empresas");?></h1>

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
                <?php echo form_open("administracion_vendty/empresas/import_excel", array("id" =>"validate"));?>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre Empresa");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("nombre_empresa", $data["campos"]); ?>
                            <?php echo form_error('nombre_empresa'); ?>
                        </div>
                    </div>                                
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("razon_social", $data["campos"]); ?>
                            <?php echo form_error('razon_social'); ?>
                        </div>

                    </div>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "Direcci&oacute;n");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("direccion", $data["campos"]); ?>
                            <?php echo form_error('direccion'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_contact', "Telefono");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("telefono", $data["campos"]); ?>
                            <?php echo form_error('telefono'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("email", $data["campos"]); ?>
                            <?php echo form_error('email'); ?>
                        </div>

                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "Tipo Identificación");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("tipo_identificacion", $data["campos"]); ?>
                            <?php echo form_error('tipo_identificacion'); ?>
                        </div>
                    </div>      

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('', "Documento");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("documento", $data["campos"]); ?>
                            <?php echo form_error('documento'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_country', "Pais");?>:</div>
                        <div class="span9">
                            <?php echo custom_form_dropdown("pais", $data["campos"]); ?>
                            <?php echo form_error('pais'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_estado', "Departamento");?>:</div>
                        <div class="span9">
                            <?php echo custom_form_dropdown("provincia", $data["campos"]); ?>
                            <?php echo form_error('provincia'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_people', "Ciudad");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("ciudad", $data["campos"]); ?>
                            <?php echo form_error('ciudad'); ?>
                        </div>
                    </div>                                

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('Distribuidor', "Distribuidor");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("distribuidor", $data["campos"]); ?>
                            <?php echo form_error('distribuidor'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('usuario_distribuidor', "Usuario_distribuidor");?>:</div>
                        <div class="span9"><?php echo custom_form_dropdown("usuario_distribuidor", $data["campos"]); ?>
                            <?php echo form_error('usuario_distribuidor'); ?>
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
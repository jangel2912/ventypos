<div class="page-header">
    <div class="icon">
        <span class="ico-group"></span>
    </div>
    <h1><?php echo custom_lang("Contactos", "Contactos");?><small><?php echo $this->config->item('site_title');?></small></h1>
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
                                <?php echo form_open("providers/import_excel", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nombre_comercial", $data["campos"]); ?>
                                            <?php echo form_error('nombre_comercial'); ?>
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
                                    <div class="span3"><?php echo custom_lang('sima_estado', "Provincia");?>:</div>
                                    <div class="span9">
                                            <?php echo custom_form_dropdown("provincia", $data["campos"]); ?>
                                            <?php echo form_error('provincia'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("razon_social", $data["campos"]); ?>
                                        <?php echo form_error('razon_social'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("nif_cif", $data["campos"]); ?>
                                        <?php echo form_error('nif_cif'); ?>
                                    </div>
                                </div>                    
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_contact', "Contacto");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("contacto", $data["campos"]); ?>
                                        <?php echo form_error('contacto'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_web', "Web");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("pagina_web", $data["campos"]); ?>
                                        <?php echo form_error('pagina_web'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("email", $data["campos"]); ?>
                                        <?php echo form_error('email'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_people', "Poblaci&oacute;n");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("poblacion", $data["campos"]); ?>
                                        <?php echo form_error('poblacion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_addres', "Direcci&oacute;n");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("direccion", $data["campos"]); ?>
                                        <?php echo form_error('direccion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_cp', "C&oacute;digo postal");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("codigo_postal", $data["campos"]); ?>
                                        <?php echo form_error('cp'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("telefono", $data["campos"]); ?>
                                        <?php echo form_error('telefono'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_movil', "Movil");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("movil", $data["campos"]); ?>
                                        <?php echo form_error('movil'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_fax', "Fax");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("fax", $data["campos"]); ?>
                                        <?php echo form_error('fax'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_company_type', "Tipo de empresa");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("tipo_empresa", $data["campos"]); ?>
                                        <?php echo form_error('tipo_empresa'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_bancaria_entity ', "Entidad bancaria");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("entidad_bancaria", $data["campos"]); ?>
                                        <?php echo form_error('entidad_bancaria'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_number_cuenta', "N&uacute;mero de cuenta");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("numero_cuenta", $data["campos"]); ?>
                                        <?php echo form_error('numero_cuenta'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_observaciones', "Observaciones");?>:</div>
                                    <div class="span9"><?php echo custom_form_dropdown("observaciones", $data["campos"]); ?>
                                        <?php echo form_error('observaciones'); ?>
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
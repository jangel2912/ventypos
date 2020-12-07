<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Servicios", "Servicios");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_services', "Editar servicio");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("servicios/editar/".$data['data']['id_servicio'], array("id" =>"validate"));?>
                                <input type="hidden" value="<?php echo set_value('id_servicio', $data['data']['id_servicio']); ?>" name="id" />
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre', $data['data']['nombre']); ?>" placeholder="Nombre del servicio" name="nombre" />
                                            <?php echo form_error('nombre'); ?>
                                    </div>
                                </div>
                                 <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo', $data['data']['codigo']); ?>" placeholder="" name="codigo" />
                                            <?php echo form_error('codigo'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_price', "Precio");?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('precio', $data['data']['precio']); ?>" name="precio" placeholder="Precio del servicio"/>
                                        <?php echo form_error('precio'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>
                                    <div class="span9">
                                            <?php echo form_dropdown('id_impuesto', $data['impuestos'], set_value('id_impuesto', $data['data']['id_impuesto']));?>
                                            <?php echo form_error('id_impuesto'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>
                                    <div class="span9"><textarea name="descripcion" placeholder="Descripci&oacute;n del servicio"><?php echo set_value('descripcion', $data['data']['descripcion']); ?></textarea>
                                        <?php echo form_error('descripcion'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
    </div>
    
</div>
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Atributos");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Editar atributo");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("atributos_valor/editar/".$data['data']['id_atributo_valor'], array("id" =>"validate"));?>
                                <input type="hidden" value="<?php echo set_value('id', $data['data']['id_atributo_valor']); ?>" name="id" />
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Atributo");?>:</div>
                                    <div class="span9">
                                           <?php echo form_dropdown('atributo_id', $data['atributos'], set_value($data['data']['atributo_id']));?>
                                            <?php echo form_error('atributo_id'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Valor");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('valor', $data['data']['valor']); ?>" placeholder="" name="valor" />
                                            <?php echo form_error('nombre'); ?>
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

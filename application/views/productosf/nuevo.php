<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_product', "Nuevo producto");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("productosf/nuevo", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                                            <?php echo form_error('nombre'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />
                                            <?php echo form_error('codigo'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('price_of_purchase', "Precio de compra");?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('precio_compra'); ?>" name="precio_compra" placeholder=""/>
                                        <?php echo form_error('precio_compra'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta");?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('precio'); ?>" name="precio" placeholder=""/>
                                        <?php echo form_error('precio'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>
                                    <div class="span9">
                                            <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto'));?>
                                            <?php echo form_error('id_impuesto'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>
                                    <div class="span9"><textarea name="descripcion" placeholder=""><?php echo set_value('descripcion'); ?></textarea>
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

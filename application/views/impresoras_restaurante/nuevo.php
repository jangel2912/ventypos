<div class="page-header">    
    <div class="icon">
        <img alt="Impresoras" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_impresora']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Impresoras", "Impresoras");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_impresora', "Nueva Impresora");?></h2> 
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
            <div class="data-fluid">
                <?php echo form_open("impresoras_restaurante/nuevo", array("id" =>"validate"));?>                               
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                            <?php echo form_error('nombre'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Código");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />
                            <?php echo form_error('codigo'); ?>
                    </div>
                </div>
                <!--
                <div class="row-form">                
                    <div class="span3"><?php echo custom_lang('sima_almacen', "Almacén");?>:</div>
                    <div class="span9">
                            <select name="id_almacen">
                                <?php
                                foreach ($almacenes as $key => $value) {
                                ?>
                                    <option value="<?=$value->id ?>"> <?=$value->nombre ?></option>
                                <?php
                                }
                                ?>                                
                            </select>                         
                            <?php echo form_error('id_almacen'); ?>
                    </div>
                </div>-->
                <div class="toolbar bottom tar">                    
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='<?php echo site_url('impresoras_restaurante/index/'); ?>'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                                            
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>   
</div>
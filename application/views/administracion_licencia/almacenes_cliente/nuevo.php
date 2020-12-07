<?php //print_r($data);?>
<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Almacenes", "Almacenes");?></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_category', "Nuevo almacen");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <div class="data-fluid">

            <?php echo form_open("administracion_vendty/almacenes_cliente/nuevo/".$data['db'], array("id" =>"validate"));?>
                <input type="hidden"  value="<?php echo $data['db']; ?>" name="bd" />              

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre Almacén");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_plan', "Plan");?>:</div>
                    <div class="span9">
                        <select name="id_plan">
                            <option value="" >Seleccione el Plan</option>
                            <?php
                                foreach($data['planes'] as $key => $value){ 
                                    if($value->id != 1){  ?>
                                    <option value="<?= $value->id ?>" ><?= $value->nombre_plan ?></option>
                                <?php
                                    }
                                }
                            ?>                                           
                        </select>
                        <?php echo form_error('id_plan'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_bodega', "Bodega");?>:</div>
                    <div class="span9">
                        <select name="bodega" >                            
                            <option value="0">No</option> 
                            <option value="1">Si</option>                                                                 
                        </select>
                    </div>
                </div>

                <div class="toolbar bottom tar">
                    <div>
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                    </div>
                </div>
            </div>
         </form>
        </div>
    </div>    

</div>

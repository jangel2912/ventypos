<?php extract($data);?>
<div class="block title">
    <div class="head">
        <h2>Desacticar el usuario <?php echo $user->username;?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                               <?php echo form_open("backend/usuarios/deactivate/".$user->id);?>
                                <?php echo form_hidden($csrf); ?>
                                <?php echo form_hidden(array('id'=>$user->id)); ?>
                                 <div class="row-form">
                                    <div class="span3">Desactivar:</div>
                                    <div class="span9">
                                        <input type="radio" name="confirm" value="yes" checked="checked" />Si
                                        <input type="radio" name="confirm" value="no" />No
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit">Submit</button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close();?>
    </div>
</div>
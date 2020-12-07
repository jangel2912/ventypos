
<?php extract($data)?>
<div class="block title">
    <div class="head">
        <h2>Editar grupo</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
        <div id="infoMessage"><?php echo $message;?></div>
                            <div class="data-fluid">
                               <?php echo form_open(current_url());?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                                    <div class="span9"> <?php echo form_input($group_name);?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">Descripci&oacute;n:</div>
                                    <div class="span9"> <?php echo form_input($group_description);?>
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
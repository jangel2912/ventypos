<?php extract($data);?>
<div class="block title">
    <div class="head">
        <h2>Editar usuario</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
        <?php 
                            
        if(!empty($message)):?>
        <div class="alert alert-success">
            <?php echo $message;?>
        </div>
        <?php endif; ?>
        <div class="data-fluid">
            <?php echo form_open(uri_string());?>
            <?php echo form_hidden('id', $user->id);?>
            <?php echo form_hidden($csrf); ?>
                <div class="row-form">
                    <div class="span3">Nombre:</div>
                    <div class="span9">
                            <?php echo form_input($first_name);?>
                            <?php echo form_error('first_name'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Apellido:</div>
                    <div class="span9">
                            <?php echo form_input($last_name);?>
                            <?php echo form_error('last_name'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Compa&ntilde;&iacute;a:</div>
                    <div class="span9">
                            <?php echo form_input($company);?>
                            <?php echo form_error('company'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Telef&oacute;no:</div>
                    <div class="span9">
                            <?php echo form_input($phone1);?>
                            <?php echo form_error('phone1'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Clave:</div>
                    <div class="span9">
                            <?php echo form_input($password);?>
                            <?php echo form_error('password'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Re-clave:</div>
                    <div class="span9">
                            <?php echo form_input($password_confirm);?>
                            <?php echo form_error('password_confirm'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3">Grupos:</div>
                    <div class="span9">
                        <?php foreach ($groups as $group):?>
                            <?php
                                    $gID=$group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach($currentGroups as $grp) {
                                            if ($gID == $grp->id) {
                                                    $checked= ' checked="checked"';
                                            break;
                                            }
                                    }
                            ?>
                            <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                            <?php echo $group['name'];?>
                            
                        <?php endforeach?>
                    </div>
                </div>
                <div class="toolbar bottom tar">
                    <div class="btn-group">
                        <button class="btn" type="submit">Submit</button>
                        <button class="btn btn-warning" type="reset">Cancelar</button>
                    </div>
                </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
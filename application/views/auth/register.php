<?php extract($data);?>
<div class="wrapper fixed">
    <div class="body">
        <div class="content">
                        <div class="page-header">
                        <div class="icon">
                            <span class="ico-cube"></span>
                        </div>
                        <h1><?php echo $this->config->item('site_title');?> <small>BIENVENIDO AL SITIO</small></h1>
                    </div>

<div class="block title">
    <div class="head">
        <h2>Nuevo usuario</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
        <?php
            $message = $this->session->flashdata('message');
            if(!empty($message)):?>
            <div class="alert alert-error">
                <?php echo $message;?>
            </div>
        <?php endif;?>
            <div class="data-fluid">
            <?php echo form_open("auth/register");?>
                <div class="row-form">
                    <div class="span3">Nombre:</div>
                    <div class="span9">
                            <?php echo form_input($first_name);?>
                            <?php echo form_error('first_name'); ?>
                    </div>
                </div>
                <!--<div class="row-form">
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
                </div> -->
                <div class="row-form">
                    <div class="span3">Correo:</div>
                    <div class="span9">
                            <?php echo form_input($email);?>
                            <?php echo form_error('email'); ?>
                    </div>
                </div>
              <!--  <div class="row-form">
                    <div class="span3">Telef&oacute;no:</div>
                    <div class="span9">
                            <?php echo form_input($phone1);?>
                            <?php echo form_error('phone1'); ?>
                    </div>
                </div> -->
                <div class="row-form">
                    <div class="span3">Clave:</div>
                    <div class="span5">
                            <?php echo form_input($password);?>
                            <?php echo form_error('password'); ?>
                    </div>
                    <div class="span4">
                            <?php echo form_input($password_confirm);?>
                            <?php echo form_error('password_confirm'); ?>
                    </div>
                </div>
              <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_country', "Pais");?>:</div>
                    <div class="span9">
                            <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), "id='pais'");?>
                            <?php echo form_error('pais'); ?>
                    </div>
            </div>
            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_language', "Idioma");?>:</div>
                <div class="span9"><?php echo form_dropdown("idioma", $data['idioma'], set_value('idioma')); ?>
                    <?php echo form_error('idioma'); ?>
                </div>
            </div>
            
                <div class="toolbar bottom tar">
                    <div class="btn-group">
                        <button class="btn" type="submit" value="submit" name="register">Submit</button>
                        <button class="btn btn-warning" type="reset">Cancelar</button>
                    </div>
                </div>
            <?php echo form_close();?>
        </div>
    </div>
</div> </div>
    </div>
    
</div>

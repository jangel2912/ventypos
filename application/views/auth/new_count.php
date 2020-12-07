<div class="login">        
        <?php
            if(!empty($message)):?>
            <div class="alert alert-error">
                <?php echo $message;?>
            </div>
        <?php endif;?>
        <div class="page-header">
            <img src="<?php echo base_url('public/img/logo_login.png');?>" alt="Logo"/>
        </div>        
        <?php echo form_open("auth/nueva_cuenta");?>
        <div class="row-fluid">
            <div class="row-form">
                <div class="span12">
				Creaci&oacute;n de nueva cuenta, ingrese email del cliente
                    <input type="text" name="email" >
                    
                </div>
            </div>
            
            
            <div class="row-form">
                <div class="span12">
                    <button class="btn" type="submit">Enviar <span class="icon-arrow-next icon-white"></span></button>
                </div>                
            </div>
    <?php echo form_close();?>
</div>
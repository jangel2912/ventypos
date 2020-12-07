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
        <?php echo form_open("auth/auto_cuenta");?>
        <div class="row-fluid">
            <div class="row-form">
                <div class="span12">
				Creaci&oacute;n de nueva cuenta, ingrese los datos del cliente
                    <input type="text" name="nombre"  placeholder="Nombre">
                    
                </div>
            </div>
            <div class="row-form">
                <div class="span12">			
                    <input type="text" name="telefono"  placeholder="Tel&eacute;fono">
                </div>
            </div>
            <div class="row-form">
                <div class="span12">			
                   <input type="text" name="email"  placeholder="Email">
                </div>
            </div>
            <div class="row-form">
                <div class="span12">
                    <button class="btn" type="submit">Enviar <span class="icon-arrow-next icon-white"></span></button>
                </div>                
            </div>
    <?php echo form_close();?>
</div>


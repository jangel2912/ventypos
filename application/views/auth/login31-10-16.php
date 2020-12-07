<?php 
    extract($data);
    $background = rand(1, 6);
?>
<div class="login login_<?php echo $background; ?>">
    <div class="form-login">
    <?php
        echo $this->session->flashdata('pass');
        $message = $this->session->flashdata('message');

        if (!empty($message)):  ?>
            <div class="alert alert-error">
                <?php echo $message; ?>
            </div>
        <?php endif;?>
        <?php
            $message2 = $this->session->flashdata('message2');
            
            if (!empty($message2)): ?>
            <div class="alert alert-success">
                <?php echo $message2; ?>
            </div>
        <?php endif;?>

        <div class="login-header">
            <img src="<?php echo base_url('public/img/logoVendty_v2_transparente.png'); ?>" alt="Logo"/>
        </div>

        <?php echo form_open("auth/login"); ?>
            <div class="row-fluid">
                <div class="row-form">
                    <div class="span12">
                        <?php echo form_input($identity); ?>
                        <?php echo form_error('identity'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span12">
                        <?php echo form_input($password); ?>
                        <?php echo form_error('password'); ?>
                    </div>
                </div>
               <!-- <div class="row-form">
                    <div class="span12">
                        <?php //echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>Recordar
                    </div>
                </div> -->
                <div class="row-form">
                    <div class="span12">
                        <button class="btn btn-large btn-block" type="submit">Entrar</button>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
        <a href="<?php echo site_url("auth/forgot_password"); ?>">Ha olvidado su clave</a>
    </div>
</div>
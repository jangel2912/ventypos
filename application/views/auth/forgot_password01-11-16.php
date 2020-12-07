<?php
    $background = rand(1, 6);
    if (isset($data)){  
        extract($data);
    }
?>
<div class="login login_<?php echo $background; ?>">
    <div class="form-login">
        <?php if (!empty($message)): ?>
            <div class="alert alert-error">
                <?php echo $message; ?>
            </div>
        <?php endif;?>
        <div class="login-header">
            <img src="<?php echo base_url('public/img/logoVendty_v2_transparente.png'); ?>" alt="Logo"/>
        </div>
        <?php echo form_open("auth/forgot_password"); ?>
        <div class="row-fluid">
            <div class="row-form">
                <div class="span12">
                    <?php echo form_input($email); ?>
                </div>
            </div>
            <div class="row-form">
                <div class="span12">
                    <button class="btn btn-large btn-block" type="submit">Recuperar</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php extract($data); ?>
<style>
    body{padding-top:0px;}
    /* General Trasition */
    .gradient .login-decorator .button-step{transition-duration: 0.8s;}
    .content-login{width:100%;min-height:100vh;}
    .content-login .login{padding: 5rem;padding-top: calc(50px + 2.5rem);-ms-flex-preferred-size: 10%;flex-basis: 10%;-webkit-box-flex: 1;-ms-flex-positive: 1;flex-grow: 1;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;text-align: center;background: #fff;}
    .content-login .login h2{margin-bottom: 3rem;margin-top: 3rem;text-transform: uppercase;font-weight: 200;font-size: 1.6em;color: #999;}
    .content-login .login label{text-align:left; display: block;color: #999;font-size: 0.9em;text-transform: uppercase;margin-bottom: 3px;font-weight: 500;}
    .content-login .login input{ border-color: #bbb; padding:15px;    border: solid 1px #bbb;width: 100%;margin-bottom: 10px;}
    .login .button-login{width: 100%;font-size: 16px;padding: 15px;position: relative;font-size: 14px;font-weight: 400;border: none;background-color:#26c664;border: 1px solid #26C664;color: #fff;padding: 15px;cursor: pointer;display: inline-block;margin: 0;text-align: center;text-shadow: none;cursor: pointer;border-radius: 2px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;letter-spacing: auto;white-space: nowrap;text-transform: none;text-decoration: none;-webkit-appearance: none;transition: all .2s ease-in-out;}
    .login .button-login:hover{text-decoration: none;background-color: #11AB4D;border-color: #11AB4D;color: #fff;box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), inset 0 1px 2px 0 transparent;}
    .login .button-login .text-login{font-size:1.8rem;}
    .login .links-login {margin-top: 10px;text-align: center;}
    .login .links-login a{font-size: 1.1em;color: #aaa;}
    .gradient{/*background: linear-gradient(to left,#39805A, #61CE70 50%); background-color:#ddffe0;*/background-color:#CDFFF6;color: #fff; min-height:100vh;}
    .gradient .login-decorator{/*padding-top: calc(140px + 2.5rem);*/padding-top: calc(110px + 2.5rem); padding-left: 40px;}
    .gradient .login-decorator h3{font-weight: 200;font-size: 1.4em;margin-bottom: 5rem;opacity: .7; /*color:#fff;*/ color:#1a1a1a;}
    .gradient .login-decorator h2{font-weight: 600;font-size: 1.8em;margin-bottom: 10px;}
    .gradient .login-decorator h2 a{/*color: #fff;*/ color:#1a1a1a; text-decoration: none;border-bottom: 1px solid rgba(255,255,255,.4);padding-bottom: 1px;line-height: 1.5em;}
    .gradient .login-decorator p{color:#000000; line-height: 1.4em;font-weight: 400;font-size: 1.6rem;margin-bottom:20px;}
    .gradient .login-decorator .button-step{border:solid 1px #fff; padding:18px; display:inline-block; text-align:center; font-weight: bold;border-radius: 7px;/*background-color: #fff;color: #39805A;*/ color:#FFFFFF; text-decoration:none; font-size:1.6rem;}
    .gradient .login-decorator .button-step:hover{opacity:0.8;}
    .image-gradient{position: absolute;bottom: 0;right: 0px;width: 59%;height: 130px;background-size: cover;transform: rotateY(200grad); background-image:url('<?= base_url().'/uploads/general/comercios_vendty.svg'?>'); }
    .icon-step{font-size:24px;}
    .input-group-addon {
        padding: 15px !important;
        border-radius: 0;
        border-bottom: solid 1px #bbb !important;
        border-top: solid 1px #bbb !important;
        border-right: solid 1px #bbb !important;
        border-radius:0;
    }
    #password{
        margin-bottom:0;
    }

    #note #note-message p{
        color: #761b18;
        background-color: #f9d6d5;
        border-color: #f7c6c5;
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    @media only screen and (max-width: 767px){
        .content-login .login{padding: 2rem;padding-top: calc(1.5rem);}
    }
</style>
<link rel="stylesheet" href="<?php echo base_url();?>public/css/toolkit.10.1.0.css">
<div class="content-login">
    <div class="hidden-xs col-sm-8 gradient" style="line-height: 50px; background-image: linear-gradient(36deg, rgb(0, 180, 121), rgb(205, 255, 246));">

    <div class="login-decorator" style="color: #FFFFFF; line-height: 50px;">
        <div class="aw-row aw-middle-xs">
            <div class="aw-col-xs-12 aw-col-xl-6 m--xs-medium-v">
            <h4 class="h3 text--bold" style="text-align: left; color: white"><?= $step_selected["title"];?></h4>
                <p style="font-size: 19px; text-align: left; color: white"><?= $step_selected["description"];?></p>
                <!-- <div class="text-center"> -->
                <div>
                    <? if (!$step_selected["button"]) {?>
                        <a style="padding:0; margin:0;" target="_blank" href="<?= $step_selected["link"];?>">
                        <img alt="Qries" src="<?= $step_selected["button_image"];?>" width="170">
                        </a>
                    <?} else {?>
                        <a style="font-size:1.2rem" class="btn btn--inverted-white" target="_blank" href="<?= $step_selected["link"];?>"><?= $step_selected["button"];?></a>
                    <? }?>
                </div>
            </div>
            <div class="aw-col-xs-12 aw-col-xl-6 text-center">
                <img alt="" src="<?= $step_selected["image"];?>" />
            </div>
        </div>
    </div>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-offset-0 col-sm-4">
<div class="row p--xs-small-h login-container">
    <div class="col-xs-12">
        <div class="text--xs-center m--xs-medium-v">
            <br><br><br>
            <a href="https://vendty.com">
                <img class="brand-img" src="<?php echo base_url('public/img/logo_login.png'); ?>" alt="..."
                width="200px">
            </a>
        </div>
        <div class="landing__form">
            <?php echo form_open("auth/login");
            echo $this->session->flashdata('pass');
            $message = $this->session->flashdata('message');
            $message2 = $this->session->flashdata('message2'); ?>
            <div class="m--sm-small-h text--xs-center">
            <h1 class="h3 text--bold">Iniciar Sesión</h1>
            <p>
                <a href="https://pos.vendty.com/index.php/auth/forgot_password" class="text--secondary">
                    ¿Olvidaste tu clave?</a> •
                <a href="http://vendty.com/registro" class="text--secondary">
                    Crear una cuenta</a>
                </p>
            </div>
            <div class="login-wrapper">
                <div class="aw-col-xs-12">
                    <div id="note-bar" class="width--full m--sm-small-t">
                        <div id="note">
                            <span id="note-message">
                            <?php  if (!empty($message)): ?>
                                <?= $message; ?>
                            <?php endif;
                            if (!empty($message2)): ?>
                                <?= $message2; ?>
                            <?php endif;?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="login-box display--xs-block">
                    <form class="aw-row m--xs-xsmall-v m--sm-small-a p--sm-xsmall-v middle-xs" id="login-form"
                        name="login-form" method="post" onsubmit="return false;">
                        <div class="form__group aw-col-xs-12">
                            <label for="identiy" class="form__label--floating">Tu correo electrónico</label>
                            <input type="text" name="identity" value="" id="identity" class='user' autofocus=''>
                        </div>
                        <div class="form__group--addons aw-col-xs-12">
                            <label for="password" class="form__label--floating">Contraseña</label>
                            <input type="password" name="password" value="" id="password" class="password">
                            <span class="form__input-addon" id="basic-addon2"><i class="far fa-eye-slash" style="line-height: 2.8;" id="showpassword"></i></span>
                        </div>
                        <br>
                        <div class="col-xs-7">
                            <div class="m--sm-small-h text--xs-left">
                                <p>
                                    <a href="<?= site_url("auth/forgot_password"); ?>" class="text--secondary text--semibold">¿Olvidaste tu clave?</a><br>
                                    <a href="http://vendty.com/registro" class="text--secondary text--semibold">Crear una cuenta</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-xs-5 text--xs-right">
                            <input class="display--xs-none" id="ff-submit-button" type="submit">
                            <button class="btn btn--primary button-login" style="background:#26c664" data-style="slide-right">Iniciar Sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<div class="aw-row p--xs-small-h text--xs-center">
<div class="aw-col-xs-12">
    <p class="m--sm-none-v">¿Necesitas ayuda? Contacta con nuestro equipo de soporte
    <a href="https://vendty.com/contactenos/"> aquí</a>
</div>
<br><br>
</div>
    </div>
</div>

<script>
    $('#showpassword').click(function () {
        seleccionado=$('#password').attr('type');

        if (seleccionado=='password') {
            $('#password').attr('type', 'text');
            $('#showpassword').removeClass('far fa-eye-slash').addClass('far fa-eye');
        } else {
            $('#password').attr('type', 'password');
            $('#showpassword').removeClass('far fa-eye').addClass('far fa-eye-slash');
        }
    });

    $('form').submit(function( event ) {
        $.post('https://apipos.vendty.com/api/v1/login', {
            email: $('#identity').val(),
            password: $('#password').val(),
            app: 'POS',
        }, function (data) {
            localStorage.setItem('api_auth', JSON.stringify(data));
        })
        .fail(function(data) {
            code = JSON.parse(data.responseText).code;
            if(code === 'SUBSCRIPTION-EXPIRED'){
                $.post('https://apipos.vendty.com/api/v1/loginLicenciaVencida', {
                    email: $('#identity').val(),
                    password: $('#password').val(),
                    app: 'POS',
                }, function (data) {
                    localStorage.setItem('api_auth', JSON.stringify(data));
                })
            }
        });
    });
</script>
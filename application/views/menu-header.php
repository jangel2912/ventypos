<link href='<?php echo base_url(); ?>public/animate.css' rel='stylesheet' type='text/css'>
<style>
    #beamerSelector {
        background-color: transparent;
        border: none;
        box-shadow: none;
    }

    /* .beamer_icon {
        top: 5px !important; 
    } */

    #site-navbar-collapse ul li {
        line-height: 3;
    }

    .drop-menu-header {
        display: none;
        background-color: #fff;
        position: absolute;
        list-style-type: none;
        border: solid 1px #eee;
        top: 50px;
        right: 0;
        border-radius: 0 0 10px 10px;
        overflow: hidden;
    }

    .drop-menu-header.open {
        display: block;
    }

    .drop-menu-header li,
    .drop-menu-header li a {
        color: #6a6c6f !important;
    }

    .drop-menu-header li a {
        height: 100%;
        position: absolute;
        left: 0;
        right: 0;
    }

    .drop-menu-header li {
        line-height: 4;
        text-align: left;
        padding: 0 0 0 10px;
        width: 270px;
        margin: 0;
    }

    .navbar-toolbar .path:before {
        display: table;
        content: " ";
        border-left: solid 1px rgba(0, 0, 0, 0.11);
        height: 100%;
        position: absolute;
    }

    .drop-menu-header li:hover {
        background-color: rgba(98, 203, 49, 0.24);
    }

    .drop-menu-header .logout,
    .drop-menu-header .logout:hover {
        background-color: #62cb31;
    }

    li[data-drop-menu="drop-menu-header"] {
        width: auto;
        padding-right: 15px;
        cursor: pointer;
    }

    .drop-menu-header .logout a {
        color: #fff !important;
        font-size: 1.6rem;
        font-weight: bold;
        line-height: 50px;
        width: 100%;
        height: 100%;
        display: block;
        margin: 0;
        position: relative;
        left: 0;
    }

    .drop-menu-header li.divider {
        height: 1px;
        border-top: solid 1px #eee;
    }

    .navbar-toolbar .iconimg {
        width: 10% !important;
    }

    #message-webinar {
        font-size: 1.5rem;
        font-weight: 500;
        line-height: 2;
        float: left;
        color: #fff;
        width: 80%;
        margin: 0 auto;
        padding-left: 25px;
        padding-top: 10px;
        position: relative;
    }

    .message-webinar_bd_black {
        background: #000;
    }

    #message-webinar a,
    #message-webinar i {
        color: #62cb31;
        font-weight: bold;
    }

    #message-webinar a {
        text-decoration: underline;
    }

    .close-message-webinar {
        color: #fff !important;
        text-decoration: none !important;
        position: absolute;
        right: 3px;
        top: 0px;
    }

    /*#message-webinar {
        animation-duration: 2s;
        animation-delay: 1s;
        animation-iteration-count: initial;
    }*/

    .toast-warning {
        display: none !important;
    }
</style>
<?php /*
    $session_api_auth = isset($_SESSION['api_auth']) ? $_SESSION['api_auth'] : null;
    $decode_session = null;
    if(!is_null($session_api_auth)) {
        $decode_session = json_decode($session_api_auth);
    }
?>

    <?php if(isset($decode_session->license) && $decode_session->license->plan->dias_vigencia == 30 && $decode_session->license->plan->id != 53 && $decode_session->license->plan->id != 68):?>
        <div id="message-webinar" class="message-webinar_bd_black">
            <p class="text-center">
                <i class="fa fa-tag" aria-hidden="true"></i>
                [BLACK FRIDAY] Ahórrate 6 Meses Pasándote al Plan Anual <a style="font-weight:initial" href="https://vendty.com/registro-black-friday/"
                                                                        target="_blank">Obtener Oferta</a>
            </p>
        </div>
    <?php else :*/?>
        <div id="message-webinar"></div>
    <?php //endif; ?>
   <!-- <a href="javascript:void(0)" class="close-message-webinar">x</a> -->

<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
    <li id="notification" style="cursor: pointer;width: 50px;" class="path">
        <img class="iconimg" style="width: 50% !important;width: auto;margin: 0;margin-right: 8px;"
            src="<?php echo $this->session->userdata('new_imagenes')['notificaciones']['original'] ?>">
    </li>
    <li data-drop-menu="drop-menu-header" class="path">
        <div class="avatar">
            <img alt="user" class="iconimg" style="width:60% !important;position: absolute;left:0;top:-1px;"
                src="http://pos.vendty.com/uploads/iconos/Gris/icono_gris-13.svg">
        </div>
        <?php echo $this->session->userdata('username'); ?> <span class="caret"></span>
        <ul class="drop-menu-header">
            <li>
                <a href="https://vendtycom.freshdesk.com/support/tickets/new" target="_blank">
                    <img title="Nuevo Ticket" alt="Nuevo Ticket" class="iconimg"
                        src="<?php echo $this->session->userdata('new_imagenes')['ticket_verde']['original'] ?>">
                    Nuevo Ticket
                </a>
            </li>
            <li role="separator" class="divider"></li>
            <li>
                <a href="https://ayuda.vendty.com/es/" target="_blank">
                    <img title="Ayuda" alt="ayuda" class="iconimg"
                        src="<?php echo $this->session->userdata('new_imagenes')['ayuda_verde']['original'] ?>">
                    Ayuda
                </a>
            </li>
            <li role="separator" class="divider"></li>
            <?php if (($this->session->userdata('es_estacion_pedido') != 1) && ($isAdmin == 't')): ?>
            <li>
                <a href="<?php echo base_url("index.php/frontend/configuracion") ?>">
                    <img title="Configuración" alt="Configuración" class="iconimg"
                        src="<?= base_url('uploads/iconos/Verde/iconos%20vendty_Configuracion.svg') ?>">
                    Configuración
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="javascript:goBorrarOffline();">
                    <img alt="Sincronizar" class="iconimg"
                        src="<?php echo $this->session->userdata('new_imagenes')['sincronizacion_verde']['original'] ?>">
                    Sincronizar
                </a>
            </li>
            <li role="separator" class="divider"></li>
            <li style="position: relative;z-index: 10;">
                <h6 style="text-align: center;margin: 0;padding: 3px 0;">Soporte:</h6>
                (+57) 318 531 5677
            </li>
            <li role="separator" class="divider"></li>
            <li class="logout"><a href="<?php echo site_url("auth/logout"); ?>" onclick="limpiarApi()">Salir</a></li>
            <script>
                function limpiarApi() {
                    localStorage.clear();
                }
            </script>
        </ul>
    </li>
</ul>

<script>
    $(".toast-warning").hide("slow");

    $('li').click(function () {
        if ($(this).data('drop-menu') === "drop-menu-header") {

            if (!$(".drop-menu-header").hasClass("open")) {
                $(".drop-menu-header").addClass("open");
            } else {
                $(".drop-menu-header").removeClass("open");
            }

        }
    });

    $(document).mouseup(function (e) {
        var menu = $(".drop-menu-header");
        if (!$('li[data-drop-menu="drop-menu-header"]').is(e.target) && !menu.is(e.target) && menu.has(e.target)
            .length == 0) {
            menu.removeClass("open");
        }
    });

    $('#notification').click(function () {
        $('#beamerSelector').trigger('click');
    });

    $(".close-message-webinar").click(function(){
        $("#message-webinar").hide();
    });
</script>

<?php include "application/views/chats.php"; ?>
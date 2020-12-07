<style>
    #content-alert-license {
        text-align: center;
        margin: auto;
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
    }

    #alert-license {
        display: inline-block;
        font-size: 1.8rem;
        text-align: center;
        margin: auto;
        padding: 5px 10px;
        border-radius: 5px 5px 0 0;
        position: relative;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ef7620;
    }

    .red-background {
        background-color: red !important;
    }

    #alert-license,
    #alert-license a {
        color: #fff;
    }

    .line-border {
        background-color: #62cb31 !important;
    }
</style>

<?php if ($estado == 1) { ?>
    <?php if ($data['dias_adicionales'] == 1) { ?>

    <?php } else { ?>

    <?php } ?>
<?php } elseif ($estado == 2) { ?>
    <style>
        .navbar-fixed-top {
            top: 0 !important;
        }

        .site-menubar {
            top: 63px !important;
        }

        iframe {
            margin-top: 35px;
        }
    </style>

    <?php if ($dias <= 15) { ?>
        <div id="content-alert-license">
            <div id="alert-license">
                Tu prueba gratis finalizará en <strong><?php echo $dias; ?></strong>
                días.&nbsp; <?php if ($resultPermisos["admin"] == 't') { ?>¿Quieres adquirir Vendty? <a
                        id="a_modal_expiracion"
                        href="<?php echo site_url("frontend/pagarPrueba"); ?>"><span
                            class="link_pagar"><b>COMPRALO AQUÍ</b></span></a> - <a id="modal-como-comprar">¿Cómo
                    comprar?</a> <?php } else {
                    echo "Comunícate con el administrador del Sistema.";
                } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<?php if (array_key_exists('datos_plan', $data) && $data['datos_plan']->tipo_plan === '2' && $data['datos_plan']->dias_vigencia === '30' && array_key_exists('datos_vencimiento', $data) && $data['datos_vencimiento']->fecha_inicio_licencia <= date('Y-m-d', mktime(0, 0, 0, 1, 1, 2020))) { ?>
    <div id="content-alert-license">
        <div id="alert-license" class="red-background">
            ¡PROMOCION! Solo por HOY, cambia tu plan Mensual a un Plan Anual Por Solo $720 Mil/Año y Ahorra $360 Mil.&nbsp;
            <a
                id="a_modal_expiracion"
                href="https://payco.link/322501"
                target="_blank"
            >
                <span class="link_pagar"><b>CÓMPRALO AQUÍ</b></span>
            </a>
        </div>
    </div>
<?php } ?>

<?php if ($estado == 1) { ?>
    <div class="toast-warning1" id="div_mensaje_renovacion"
         style="display: none">
        <?php
        if ($data['dias_adicionales'] != 1) { ?>
            <div id="content-alert-license">
                <div id="alert-license">
            <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Su suscripción <?php echo $data['nombre_plan']; ?> expirará en: <strong><?php echo $data['dias_licencia']; ?></strong> días. <?php if ($resultPermisos["admin"] == 't') { ?>Para renovar tu licencia
                    <a id=""
                       href="<?php echo site_url("frontend/pagarVencida"); ?>"><b>clic aquí</b></a> <?php } else {
                    echo "Comunícate con el administrador del Sistema.";
                } ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

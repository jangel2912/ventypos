<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.css?v2.2.0">
<div class="block title">
    <div class="head">
        <h2><?= isset($seguimiento['nombre']) ? $seguimiento['nombre'] : '' ?> 
            <small>Creación: <?= isset($seguimiento['fecha_creacion']) ? $seguimiento['fecha_creacion'] : '' ?></small>
            Oportunidad:
            <small>$ <?= isset($seguimiento['oportunidad']) ? number_format($seguimiento['oportunidad']) : '' ?></small>
        </h2>
        <span class="pull-right" style="text-align: right">
            <a href="<?= base_url() ?>index.php/crm/dashboard" type="submit" title="Volver a Dashboard" class="btn-sm btn-warning">
                <i class="glyphicon glyphicon-dashboard"></i> Dashboard
            </a>
            <a href="<?= base_url() ?>index.php/crm/contactos" type="submit" title="Volver a Contactos" class="btn-sm btn-info">
                <i class="ico-book-2"></i>   Contactos
            </a>
        </span>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="block">
            <div class="head blue">
                <div class="icon"><i class="ico-user"></i></div>
                <h2><?php echo custom_lang('sima_datos_principales', "Datos Principales"); ?></h2>
            </div>
            <?= form_open(base_url() . 'index.php/crm/save_customer', 'id="save_customer"') ?>
            <div class="data-fluid">
                <div class="span6">
                    <div class="row-fluid">
                        <span>Nombre</span>
                        <input type="text" name="nombre" id="nombre" value="<?= isset($seguimiento['nombre']) ? $seguimiento['nombre'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Identificación</span>
                        <input type="text" name="identificacion" id="identificacion" value="<?= isset($seguimiento['identificacion']) ? $seguimiento['identificacion'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Empresa</span>
                        <input type="text" name="empresa" id="empresa" value="<?= isset($seguimiento['empresa']) ? $seguimiento['empresa'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Email</span>
                        <input type="text" name="mail" id="mail" value="<?= isset($seguimiento['mail']) ? $seguimiento['mail'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Teléfono</span>
                        <input type="text" name="telefono" id="telefono" value="<?= isset($seguimiento['telefono']) ? $seguimiento['telefono'] : '' ?>" />
                    </div>

                </div>
                <div class="span6">
                    <div class="row-fluid">
                        <span>País</span>
                        <input type="text" name="pais" id="pais" value="<?= isset($seguimiento['pais']) ? $seguimiento['pais'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Ciudad</span>
                        <input type="text" name="ciudad" id="ciudad" value="<?= isset($seguimiento['ciudad']) ? $seguimiento['ciudad'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Dirección</span>
                        <input type="text" name="direccion" id="direccion" value="<?= isset($seguimiento['direccion']) ? $seguimiento['direccion'] : '' ?>" />
                    </div>
                    <div class="row-fluid">
                        <span>Tipo de Negocio</span>
                        <?= form_dropdown('tipo_negocio', $select_tipo_negocio, isset($seguimiento['tipo_negocio']) ? $seguimiento['tipo_negocio'] : '') ?>
                    </div>
                    <div class="row-fluid">
                        <span>Celular</span>
                        <input type="text" name="celular" id="celular" value="<?= isset($seguimiento['celular']) ? $seguimiento['celular'] : '' ?>" />
                    </div>
                </div>
            </div>
            <div class="data-fluid pull-right">
                <br>
                <input type="hidden" id="id_crm" name="id_crm" value="<?= isset($seguimiento['id']) ? $seguimiento['id'] : '' ?>"/>
                <button type="submit" class="btn-sm btn-success Pull-Right right">
                    Guardar <span class="glyphicon glyphicon-floppy-disk" style=""></span>
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>

    <div class="span6">
        <div class="block">
            <div class="head blue">
                <div class="data-fluid">
                    <div class="span10">
                        <div class="icon"><i class="ico-box"></i></div>
                        <h2><?php echo custom_lang('sima_datos_principales', "Oportunidades"); ?></h2>
                    </div>
                    <div class="span2" style="text-align: right">
                        <a class="btn-sm blue Pull-Right right" id="valor_datafono_vendtyb" onclick="oportunidad_modal()" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                            <span class="glyphicon glyphicon-plus" style=""></span>
                        </a>
                    </div>
                </div>

            </div>
            <div class="data-fluid">
                <table class="table aTable infoTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                            <th><?php echo custom_lang('sima_name', "Plan"); ?></th>
                            <th><?php echo custom_lang('sima_codigo', "Monto"); ?></th>
                            <th><?php echo custom_lang('price_of_purchase', "Estado"); ?></th>
                            <th></th>
                        </tr>
                    <tbody>
                        <?php if (isset($oportunidades)) foreach ($oportunidades as $rowOport) { ?>
                                <tr>
                                    <td><?= $rowOport['nombre'] ?></td>
                                    <td><?= $rowOport['plan_descripcion'] ?></td>
                                    <td>$<?= number_format($rowOport['monto']) ?></td>
                                    <td><?= form_dropdown('id_estado', $select_estados, isset($rowOport['id_estado']) ? $rowOport['id_estado'] : '', 'onchange="change_estado(' . $rowOport['id'] . ',this)" class="span8"') ?></td>
                                    <td>
                                        <a class="btn-sm" onclick="oportunidad_modal(<?= $rowOport['id'] ?>)" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                                            <div class="icon"><span class="ico-pencil"></span></div>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
            <div class="head blue">
                <div class="data-fluid">
                    <div class="span10">
                        <div class="icon"><i class="ico-box"></i></div>
                        <h2><?php echo custom_lang('sima_datos_principales', "Actividades"); ?></h2>
                    </div>
                    <div class="span2" style="text-align: right">
                        <a class="btn-sm btn-warning Pull-Right right" id="valor_datafono_vendtyb" onclick="actividad_modal()" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                            <span class="glyphicon glyphicon-plus" style=""></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <table class="table aTable infoTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo custom_lang('sima_image', "Fecha"); ?></th>
                            <th><?php echo custom_lang('sima_name', "Usuario"); ?></th>
                            <th><?php echo custom_lang('sima_codigo', "Actividad"); ?></th>
                            <th><?php echo custom_lang('price_of_purchase', "Nota"); ?></th>
                            <th></th>
                        </tr>
                    <tbody>
                        <?php if (isset($actividades)) foreach ($actividades as $rowAct) { ?>
                                <tr>
                                    <td><?= $rowAct['fecha'] ?></td>
                                    <td><?= $rowAct['usuario_nombre'] ?></td>
                                    <td><?= $rowAct['actividad_descripcion'] ?></td>
                                    <td><?= $rowAct['nota'] ?></td>
                                    <td>
                                        <a class="button-a btn-sm" onclick="actividad_modal(<?= $rowAct['id'] ?>)" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                                            <div class="icon"><span class="btn ico-pencil"></span></div>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="block">
            <div class="head blue">
                <div class="data-fluid">
                    <div class="span10">
                        <div class="icon"><i class="ico-box"></i></div>
                        <h2><?php echo custom_lang('sima_datos_principales', "Alertas"); ?></h2>
                    </div>
                    <div class="span2" style="text-align: right">
                        <a class="btn-sm blue Pull-Right right" id="valor_datafono_vendtyb" onclick="alerta_modal()" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                            <span class="glyphicon glyphicon-plus" style=""></span>
                        </a>
                    </div>
                </div>

            </div>
            <div class="data-fluid">
                <table class="table aTable infoTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo custom_lang('sima_image', "Fecha"); ?></th>
                            <th><?php echo custom_lang('sima_name', "Usuario"); ?></th>
                            <th><?php echo custom_lang('sima_codigo', "Actividad"); ?></th>
                            <th><?php echo custom_lang('price_of_purchase', "Desc."); ?></th>
                            <th></th>
                        </tr>
                    <tbody>
                        <?php if (isset($alertas)) foreach ($alertas as $rowAlert) { ?>
                                <tr>
                                    <td><?= date_format(date_create($rowAlert['fecha_programada']), 'Y-m-d').' '.$rowAlert['hora'] ?></td>
                                    <td><?= $rowAlert['usuario_nombre'] ?></td>
                                    <td><?= $rowAlert['actividad_descripcion'] ?></td>
                                    <td><?= $rowAlert['descripcion'] ?></td>
                                    <td>
                                        <a class="btn-sm" onclick="alerta_modal(<?= $rowAlert['id'] ?>)" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                                            <div class="icon"><span class="btn ico-pencil"></span></div>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade styleModalVendty" id="examplePositionBottom" aria-hidden="true" aria-labelledby="examplePositionBottom"
     role="dialog" tabindex="-1">
    <div id="info_modal" class="modal-dialog modal-bottom">

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.infoTable').dataTable({
            "order": [[0, "desc"]]
        });

        $("#save_customer").submit(function (e) {
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                success: function (json) {
                    if (json.res == 'error') {
                        toastrErrorMessage(json.error);
                    } else {
                        toastrSuccessMessage(json.success)
                        $('#id_crm').val(json.id_crm);
                    }
                }
            });
            e.preventDefault();
        });
    });

    function actividad_modal(actividad_id) {
        $.post('<?= base_url() ?>index.php/crm/actividad_modal/', {
            actividad_id: actividad_id,
            id_crm: $('#id_crm').val()
        },
                function (json) {
                    $('#info_modal').html(json);
                }, 'json');
    }
    function alerta_modal(alerta_id) {
        $.post('<?= base_url() ?>index.php/crm/alerta_modal/', {
            alerta_id: alerta_id,
            id_crm: $('#id_crm').val()
        },
                function (json) {
                    $('#info_modal').html(json);
                }, 'json');
    }
    function change_estado(id_oportunidad, id_estado) {
        $.post('<?= base_url() ?>index.php/crm_oportunidades/change_estado/', {
            id_oportunidad: id_oportunidad,
            id_estado: id_estado.value,
            id_crm: $('#id_crm').val()
        },
                function (json) {
                    if (json.res == 'error') {
                        toastrErrorMessage(json.error);
                    } else {
                        toastrSuccessMessage(json.success)
                    }
                }, 'json');
    }
    function oportunidad_modal(id_oportunidad) {
        $.post('<?= base_url() ?>index.php/crm_oportunidades/oportunidad_modal/', {
            id_oportunidad: id_oportunidad,
            id_crm: $('#id_crm').val()
        },
                function (json) {
                    $('#info_modal').html(json);
                }, 'json');
    }

    function toastrSuccessMessage(msg) {
        var title = "Bien Hecho!";
        var shortCutFunction = "success";
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }

    function toastrWarningMessage(msg) {
        var title = "Bien Hecho!";
        var shortCutFunction = "warning";
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "0",
            "extendedTimeOut": "0",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }

    function toastrErrorMessage(msg) {
        var title = "Alerta!";
        var shortCutFunction = "error";
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }

</script>
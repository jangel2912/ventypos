<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Oportunidades</h4>
    </div>
    <?= form_open(base_url() . 'index.php/crm_oportunidades/save_oportunidad', 'id="save_oportunidad"') ?>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="data-fluid">
                <div class="span6">
                    <div class="row-fluid">
                        <span>Nombre</span>
                        <input type="text" name="nombre" id="nombre" value="<?= isset($oportunidad['nombre']) ? $oportunidad['nombre'] : '' ?>" />
                    </div>

                </div>
                <div class="span6">
                    <div class="row-fluid">
                        <span>Fecha Cierre</span>
                        <input type="text" name="fecha_cierre" id="fecha_cierre" value="<?= isset($oportunidad['fecha_cierre']) ? $oportunidad['fecha_cierre'] : '' ?>" />
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="span12">
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Plan</span>
                            <?= form_dropdown('id_plan', $select_plan, isset($oportunidad['id_plan']) ? $oportunidad['id_plan'] : '') ?>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Monto</span>
                            <input type="text" name="monto" id="monto" value="<?= isset($oportunidad['monto']) ? $oportunidad['monto'] : '' ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="">
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Puntos de Venta</span>
                            <input type="text" name="punto_venta" id="punto_venta" value="<?= isset($oportunidad['punto_venta']) ? $oportunidad['punto_venta'] : '' ?>" />
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Estado</span>
                            <select class="form-control id_estado" name="id_estado" id="id_estado">
                                <?php foreach ($select_estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>" data-justificacion="<?= $estado['justificacion'] ?>"><?= $estado['descripcion'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="data-fluid">
                <div class="">
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Justificación</span>
                            <?= form_dropdown('id_justificacion', $select_justificacion, isset($oportunidad['id_justificacion']) ? $oportunidad['id_justificacion'] : '') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="span12">
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Asignado</span>
                            <?php if(isset($oportunidad['id_usuario']) && $oportunidad['id_usuario'] != $user_session_id && $user_session['rol_id'] != 1) $disabled = 'disabled="disabled"'; else $disabled = '';?>
                            <?= form_dropdown('id_usuario', $select_crm_usuarios, isset($oportunidad['id_usuario']) ? $oportunidad['id_usuario'] : $user_session_id,$disabled) ?>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <span>Probabilidad</span>
                            <?= form_dropdown('probabilidad', $select_probabilidad, isset($oportunidad['probabilidad']) ? $oportunidad['probabilidad'] : '') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="data-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <span>Descripción</span>
                        <textarea name="descripcion" id="descripcion"><?= isset($oportunidad['descripcion']) ? $oportunidad['descripcion'] : '' ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id_crm" id="id_crm" value="<?= $id_crm ?>" />
        <input type="hidden" name="id_oportunidad" id="id_oportunidad" value="<?= isset($oportunidad['id']) ? $oportunidad['id'] : '' ?>" />
        <button type="button" class="btn btn-warning" data-target="#examplePositionCenter" data-toggle="modal" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-gray">Guardar</button>
    </div>
    <?= form_close(); ?>
</div>
<script>
    $("#save_oportunidad").submit(function (e) {
        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            data: $(this).serialize(),
            success: function (data) {
                var json = JSON.parse(data);
                if (json.res == 'error') {
                    alert(json.error);
                    $(".btn-success").html("Guardar").removeAttr('disabled');
                } else {
//                    location.reload();
                    location.href = '<?= base_url() ?>index.php/crm/view/' + $('#id_crm').val()
                }
            }
        });
        e.preventDefault();
    });
    $(".id_estado").change(function(){
        alert($(this).attr('data-justificacion'));
        alert($(this).data('justificacion'));
    });
    $("#fecha_cierre").datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
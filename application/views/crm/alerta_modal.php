<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Alertas</h4>
    </div>
    <?= form_open(base_url() . 'index.php/crm/save_alerta', 'id="save_alerta"') ?>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="data-fluid">
                <div class="span6">
                    <div class="row-fluid">
                        <div class="span12">Fecha Programada</div>
                        <span class="span4">
                            <input type="text" name="fecha_programada" id="fecha_programada" value="<?= isset($alerta['fecha_programada']) ? $alerta['fecha_programada'] : date('Y-m-d') ?>" />
                        </span>
                        <span class="span4">
                            <?= form_dropdown('hora', $select_hora, isset($alerta['hora']) ? $alerta['hora'] : '') ?>
                        </span>
                    </div>
                    
                    <div class="row-fluid">
                        <span>Fecha Cierre</span>
                        <input type="text" name="fecha_cierre" id="fecha_cierre" value="<?= isset($alerta['fecha_cierre']) ? $alerta['fecha_cierre'] : '' ?>" />
                    </div>
                </div>
                <div class="span6">
                    <div class="row-fluid">
                        <span class="span12">Tipo Actividad</span><br/>
                        <?= form_dropdown('tipo_actividad', $select_tipo_actividad, isset($alerta['tipo_actividad']) ? $alerta['tipo_actividad'] : '') ?>
                    </div>
                </div>
                <div class="span6 pull-right">
                    <div class="row-fluid">
                        <span>Activo</span>
                        <?= form_dropdown('activo', array('1'=>'Si','0'=>'No'), isset($alerta['activo']) ? $alerta['activo'] : '') ?>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <span>Descripción</span>
                        <textarea name="descripcion" id="descripcion"><?= isset($alerta['descripcion']) ? $alerta['descripcion'] : '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id_crm" id="id_crm" value="<?= $id_crm ?>" />
        <input type="hidden" name="id_crm_alerta" id="id_crm_alerta" value="<?= isset($alerta['id']) ? $alerta['id'] : '' ?>" />
        <button type="button" class="btn btn-warning" data-target="#examplePositionCenter" data-toggle="modal" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-gray">Guardar</button>
    </div>
    <?= form_close(); ?>
</div>
<script>
    $("#save_alerta").submit(function (e) {
        $('.btn_guardar').attr("disabled", true);
        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            data: $(this).serialize(),
            success: function (data) {
                var json = JSON.parse(data);
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    $(".btn-success").html("Guardar").removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    location.reload();
                }
                $('.btn_guardar').attr("disabled", false);
            }
        });
        e.preventDefault();
    });
    $("#fecha_programada, #fecha_cierre").datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
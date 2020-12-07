<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Actividades</h4>
    </div>
    <?= form_open(base_url() . 'index.php/crm/save_actividad', 'id="save_actividad"') ?>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="data-fluid">
                <div class="span6">
                    <div class="row-fluid">
                        <span>Tipo Actividad</span>
                        <?= form_dropdown('tipo_actividad', $select_tipo_actividad, isset($actividad['tipo_actividad']) ? $actividad['tipo_actividad'] : '') ?>
                    </div>
                </div>
            </div>
            <div class="data-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <span>Nota</span>
                        <textarea name="nota" id="nota"><?= isset($actividad['nota']) ? $actividad['nota'] : '' ?></textarea>
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
        <input type="hidden" name="id_crm_actividades" id="id_crm_actividades" value="<?= isset($actividad['id']) ? $actividad['id'] : '' ?>" />
        <button type="button" class="btn btn-warning" data-target="#examplePositionCenter" data-toggle="modal" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-gray btn_guardar">Guardar</button>
    </div>
    <?= form_close(); ?>
</div>
<script>
    $("#save_actividad").submit(function (e) {
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
//                    location.href = '<?= base_url()?>index.php/crm/dashboard';
                }
                $('.btn_guardar').attr("disabled", false);
            }
        });
        e.preventDefault();
    });
    $("#fecha").datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
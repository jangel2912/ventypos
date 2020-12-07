<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_ingredient_list', "Seguimiento a clientes"); ?></h2> 
        <span class="pull-right">
        &nbsp;<a class="btn-sm btn-success" href="<?= base_url() ?>index.php/crm/dashboard">Dashboard <i class="ico icon-home"></i></a>
        &nbsp;<a class="btn-sm btn-warning" href="<?= base_url() ?>index.php/crm/view">Nuevo Cliente <i class="ico icon-plus"></i></a>
        &nbsp;<a class="btn-sm btn-info blue white" href="<?= base_url() ?>index.php/crm/contactos">Actualizar <i class="ico icon-refresh"></i> &nbsp;</a>
        </span>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_seguimiento', "Seguimiento a clientes nuevos"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="seguimientoTable">
                    <thead>
                        <tr>
                            <th width="20%"><?php echo custom_lang('sima_image', "Fecha"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_image', "Nombre"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_codigo', "Email"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_name', "Asignado"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_estado', "Estado"); ?></th>
                            <th width="10%"></th>
                        </tr>
                    <tbody>
                        <?php foreach ($seguimientos as $rowSeg) { ?>
                            <tr>
                                <td><?= $rowSeg['fecha_creacion'] ?></td>
                                <td><?= $rowSeg['nombre'] ?></td>
                                <td><?= $rowSeg['mail'] ?></td>
                                <td><?= $rowSeg['usuario_asignado'] ?></td>
                                <td><?= $rowSeg['estado_descripcion'] ?></td>
                                <td><a href="<?= base_url() ?>index.php/crm/view/<?= $rowSeg['id'] ?>" class="btn-sm btn-default"><i class="glyphicon-eye-open"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#seguimientoTable').dataTable({
        "order": [[0, "desc"]]
    });
</script>
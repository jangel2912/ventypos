<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_ingredient_list', "Seguimiento a clientes"); ?> </h2>
        <span class="pull-right">
            &nbsp;<a class="btn-sm btn-success" href="<?= base_url() ?>index.php/crm/contactos">Contactos <i class="ico icon-book"></i></a>
            &nbsp;<a class="btn-sm btn-warning" href="<?= base_url() ?>index.php/crm/view">Nuevo Cliente <i class="ico icon-plus"></i></a>
            &nbsp;<a class="btn-sm btn-info blue white" href="<?= base_url() ?>index.php/crm/dashboard">Actualizar <i class="ico icon-refresh"></i>&nbsp;</a>
        </span>
    </div>
</div>

<div class="row">
    <div class="row row-lg col-lg-9">
        <?php foreach ($dashboard as $rowDash) { ?>
            <div class="col-lg-4 col-sm-6">
                <!-- Example Top -->
                <div class="example-wrap">
                    <h4 class="example-title"><?= $rowDash['descripcion'] ?> <small>(<?= count($rowDash['dashboard']) ?>) [ $ <?= number_format($rowDash['monto']) ?>]</small></h4>
                    <div class="example">
                        <div class="example-well height-250"  style="overflow-y: scroll;">
                            <?php foreach ($rowDash['dashboard'] as $rowDashCliente) { ?>
                                <a href="<?= base_url() ?>index.php/crm/view/<?= $rowDashCliente['id_crm'] ?>" title="<?= $rowDashCliente['crm']['fecha_creacion'] ?> (<?= $rowDashCliente['info_estado']['day'] ?> Días)" style="padding-bottom: auto">
                                    <h6>
                                        <i class="icon wb-large-point <?= $rowDashCliente['info_estado']['style'] ?>-600 margin-right-5" aria-hidden="true"></i>
                                        <?= $rowDashCliente['crm']['nombre'] ?>
                                        <span class="label label-round <?= $rowDashCliente['info_estado']['style'] ?>"><?= $rowDashCliente['crm']['celular'] ?></span>     
                                        <small>$<?= number_format($rowDashCliente['monto']) ?></small> 

                                    </h6> 
                                </a>
                            <?php } ?>                         
                        </div>
                    </div>
                </div>
                <!-- End Example Top -->
            </div>
        <?php } ?>
    </div>
    <div class="row col-lg-3 example-col panel panelHeightFixed pull-right">
        <div class="col-lg-12">
            <h4>Alertas <span class="label label-round red white"><?= count($alertas) ?></span></h4>
        </div>
        <?php foreach ($alertas as $rowAlert) { ?>
            <div class="col-lg-12">
                <!-- Example Top -->
                <a href="<?= base_url() ?>index.php/crm/view/<?= $rowAlert['id_crm'] ?>" style="padding-bottom: auto">
                    <h6><small><?= date_format(date_create($rowAlert['fecha_programada']), 'Y-m-d') . ' ' . $rowAlert['hora'] ?></small> 
                        <span class="label label-round"><?= $rowAlert['actividad_descripcion'] ?></span>     
                        <?= $rowAlert['crm_nombre'] ?></h6>
                </a>
                <!-- End Example Top -->
            </div>
        <?php } ?>
    </div>
</div>

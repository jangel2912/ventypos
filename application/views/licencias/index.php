<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang('sima_outstanding', "Listado de Licencias");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre empresa</th>
                    <th>Tipo de Licencia</th>
                    <th>Valor renovación</th>
                    <th>Fecha vencimiento</th>
                    <th>Almacen</th>
                    <th>Estado licencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $licencia): ?>
                    <tr>
                        <td><?php echo $licencia['nombre_empresa']; ?></td>
                        <td><?php echo $licencia['nombre_plan']; ?></td>
                        <td><?php echo $licencia['valor_plan']; ?></td>
                        <td><?php echo $licencia['fecha_vencimiento']; ?></td>
                        <td><?php echo $licencia['almacen']; ?></td>
                        <td>
                            <?php isset($licencia['estado_licencia']) && $licencia['estado_licencia'] == 1 ? $estado = 'label-primary' : $estado = 'label-danger'; ?>
                            <span class="label <?php echo $estado; ?>"><?php echo $licencia['descripcion']; ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
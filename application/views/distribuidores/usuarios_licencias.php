
<style>
    .item{
        font-size:14px;
        font-weight: bold;
    }
</style>
<h4>Datos cliente</h4>
<hr>
<p>A continuación información general de cliente - licencias</p>

<div>
    <span class="item">Nombre:</span>
    <?php echo $info_cliente->first_name;?>
</div>

<div>
    <span class="item">Correo electrónico:</span>
    <?php echo $info_cliente->email;?>
</div>

<div>
    <span class="item">Teléfono:</span>
    <?php echo $info_cliente->phone;?>
</div>
<br>



<?php if($licencias_por_usuario != null){ ?>   
    <h4>Lista de licencias</h4>
    <hr>
    <table class="table table-striped">
    <tr>
        <td>Nombre del plan</td>
        <td>Fecha de inicio de licencia</td>
        <td>Fecha de vencimiento</td>
    </tr>
        <?php foreach($licencias_por_usuario as $licencia){ ?>
        <tr>
            <td><?php echo $licencia->nombre_plan;?></td>
            <td><?php echo $licencia->fecha_inicio_licencia;?></td>
            <td><?php echo $licencia->fecha_vencimiento;?></td>
        </tr>
        <?php }?>
    
    </table>
<?php }?>
<br>


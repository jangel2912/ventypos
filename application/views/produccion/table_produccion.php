<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<style>
    .column-table{
        padding-top:10px !important;
    }
</style>
<table class="table aTable" id="table-production" cellpadding="0" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Producto en Producción</th>
            <th>Producto Final</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
        
        if(!empty($produccion_detalle)):
            $i=0;
            foreach ( $produccion_detalle as $row):
    ?>
                <tr>
                    <td class="column-table" width="20%"><?= $row['producto']; ?></td>
                    <td class="column-table" width="20%"><?= $row['producto_final']; ?></td>
                    <td class="column-table" width="20%" style="padding: 0; margin: 0; text-align: center" >
                        <input type="text" id="cantidad[<?= $i ?>]" name="cantidad[<?= $i ?>]"  value="<?= $row['cantidad']; ?>" style="width: 60%;text-align: center;" size="3">
                        <input type="hidden" id="produccion_detalle_id[<?= $i ?>]" name="produccion_detalle_id[<?= $i ?>]" value="<?= $row['produccion_detalle_id']  ?>">
                    </td>
                    
                    <td class="column-table" width="20%">
                    
                        <a data-tooltip="Eliminar" onclick="removeProduct('<?= $row['produccion_detalle_id'] ?>','<?= $i ?>')" class="button red delete acciones" style="width: 30px;height: 30px;">
                            <i class="fas fa-trash-alt" style="font-size:16px;"></i>
                        </a>
                    </td>  
                </tr>
    <?php
                $i++;
            endforeach;
        endif;
    ?>
    </tbody>
</table>
<div class="pull-right">
    <a href="<?php echo site_url("produccion/index"); ?>" type="button" class="btn btn-default">Volver a listar</a>
    <button onclick="confirm_produccion( $(this) )" class="btn btn-success">Confirmar</button>
</div>
<div class="modal-header">
        <h4 class="modal-title" style="padding:15px;">Orden de Producción <?= !empty($produccion['estado']) ? ($produccion['estado'] == 'Confirmado' ? ' - Confirmada' : ($produccion['estado'] == 'Trasladado' ? ' - Trasladada' : '') ) : '' ?></h4>
</div>

    
    <div class="modal-body">
    <?php echo form_open('produccion/save_produccion', array('id'=>'f_save_produccion') ) ?>
        <input type="hidden" name="produccion_id"     id="produccion_id"     value="<?= $produccion_id ?>" />
        <input type="hidden" name="produccion_estado" id="produccion_estado" value="<?= !empty($produccion['estado']) ? $produccion['estado'] : ''; ?>" />
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="span3">
                    <span>Fecha</span>
                </div>
                <div class="span3">
                    <span>Almacén</span>
                </div>
                <?php if(!empty($produccion['estado']) && $produccion['estado']=='Confirmado'): ?>
                <div class="span3">
                    <span>Almacén Destino</span>
                </div>
                <?php endif; ?>
                <div class="span1">
                    <span>&nbsp;</span>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <input type="text" name="fecha" id="fecha" value="<?= isset($produccion['fecha']) ? $produccion['fecha'] : date('Y-m-d') ?>" />
                </div>
                <div class="span3">
                    <?= form_dropdown('almacen_id', $array_almacenes, !empty($produccion['almacen_id']) ? $produccion['almacen_id'] : NULL   ) ?>
                </div>
                <?php if(!empty($produccion['estado']) && $produccion['estado']=='Confirmado'): ?>
                    <div class="span3">
                        <?= form_dropdown('almacen_traslado_id', $array_almacenes, !empty($produccion['almacen_id']) ? $produccion['almacen_id'] : NULL   ) ?>
                    </div>
                <?php endif; ?>
                <!--
                <div class="span1"> 
                    <button type="submit" id='btn_save' class="btn-sm btn-success"><i class="glyphicon glyphicon-floppy-disk"></i></button>                    
                </div>-->
                <div class="span5">
                    <div id="div_mensaje_crear_produccion" class="alert alert-info" style="display: none"></div>
                </div>
            </div>
            
            <div class="row-fluid next_form" style="display: <?= !empty($produccion_id) ? 'block' : 'none' ?>">
                <div class="span3">
                    <span>En Producción</span>
                    <?= form_dropdown('producto_id', $array_compuestos,'','id="producto_id"') ?>
                </div>
                <div class="span3">
                    <span>Producto Terminado</span>
                    <?= form_dropdown('producto_final_id', $array_final, '', 'id="producto_final_id"') ?>
                </div>
                <div class="span3">
                    <span>Cantidad</span>
                    <input type="text" name="cantidad" id="cantidad" value="" />
                </div>
                <div class="span1">
                    <span>&nbsp;</span>
                    <a class="btn-sm" onclick="add_product_produccion()" title="Agregar"><li class="icon icon-plus"></li></a>
                </div>
            </div>
            <div class="row-fluid next_form">
               &nbsp;
            </div>
            <div class="row-fluid next_form" style="display: <?= !empty($produccion_id) ? 'block' : 'none' ?>">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Producto en Producción</th>
                            <th>Producto Final</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(!empty($produccion_detalle)):
                            $i=0;
                            foreach ( $produccion_detalle as $row):
                    ?>
                                <tr>
                                    <td><?= $row['producto']; ?></td>
                                    <td><?= $row['producto_final']; ?></td>
                                    <td style="padding: 0; margin: 0; text-align: center" >
                                        <input type="text" id="cantidad[<?= $i ?>]" name="cantidad[<?= $i ?>]"  value="<?= $row['cantidad']; ?>" style="text-align: right; width: 99%" size="3">
                                        <input type="hidden" id="produccion_detalle_id[<?= $i ?>]" name="produccion_detalle_id[<?= $i ?>]" value="<?= $row['produccion_detalle_id']  ?>">
                                    </td>
                                </tr>
                    <?php
                                $i++;
                            endforeach;
                        endif;
                    ?>
                    </tbody>
                </table>
                <br>
            </div> 
            <div class="row-fluid next_form">
               &nbsp;
            </div>
        </div>
        
    </div>
    
    <div class="modal-footer">                
        <button type="button" class="btn btn-default" data-target="#examplePositionCenter" data-toggle="modal" data-dismiss="modal">Cerrar</button>
        <button type="button" style="display: <?= !empty($produccion_id) ? 'table-cell' : 'none' ?>" class="btn btn-default btn-success white" id="btn-confirm" onclick="confirm_produccion( $(this) )"  data-toggle="modal" data-dismiss="modal">Confirmar</button>
        <button type="button" style="display: <?= !empty($produccion_id) ? 'table-cell' : 'none' ?>" class="btn btn-success" id="btn-update"  onclick="update_stock( $(this) )"  data-toggle="modal" data-dismiss="modal">Trasladar</button>
        
    </div>
    
    
<?php echo form_close(); ?>
</div>


<script type="text/javascript">

    function add_product_produccion() {
        
        if($("#produccion_estado").val() == 'Confirmado'){
            toastrErrorMessage('La Producción ya se encuentra Confirmada');
            return false;
        }
        
        if($("#produccion_estado").val() == 'Trasladado'){
            toastrErrorMessage('La Producción ya se encuentra Trasladada');
            return false;
        }
        
        
        $.post('<?= base_url() ?>index.php/produccion/add_product_produccion/', {
            produccion_id: $('#produccion_id').val(),
            producto_id: $('#producto_id').val(),
            producto_final_id: $('#producto_final_id').val(),
            cantidad: $('#cantidad').val()
        },
            function (json) {
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    $(".btn-success").html("Guardar").removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    view_modal( $('#produccion_id').val(), $('#produccion_estado').val());
                }
            }, 'json'
        );
    }

    function update_stock( btn ){

        btn.attr('disabled', 'disabled');
        
        $.ajax({
            url:  $("#f_save_produccion").attr("action").replace('save_produccion', 'update_stock'),
            type: $("#f_save_produccion").attr("method"),
            data: $("#f_save_produccion").serialize(),
            success: function (json) {
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    btn.removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    $("#presupuestosTable").dataTable().fnReloadAjax();
                    $("#examplePositionBottom").modal('hide');
                }
            }
        });
    }

    function confirm_produccion( btn ){
        if($("input[name^=produccion_detalle_id]").length == 0){
            toastrErrorMessage('No hay productos relacionados a la Producción para confirmarla');
            return false;
        }
        btn.attr('disabled', 'disabled');
        $.ajax({
            url:  $("#f_save_produccion").attr("action").replace('save_produccion', 'confirm_produccion'),
            type: $("#f_save_produccion").attr("method"),
            data: $("#f_save_produccion").serialize(),
            success: function (json) {
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    btn.removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    $("#presupuestosTable").dataTable().fnReloadAjax();
                    $("#examplePositionBottom").modal('hide');
                }
            }
        });
    }

$(document).ready(function(){
    //del modal
        $("#f_save_produccion").submit(function (e) {
            e.preventDefault();
            $(".btn-success").attr('disabled');
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                beforeSend: function(){
                    $("#div_mensaje_crear_produccion").html('<b>Por favor espere un momento...</b>');
                    $("#div_mensaje_crear_produccion").show();
                },
                success: function (json) {
                    $("#div_mensaje_crear_produccion").hide();  
                    if (json.res == 'error') {
                        toastrErrorMessage(json.error);
                        $(".btn-success").removeAttr('disabled');
                    } else {
                        toastrSuccessMessage(json.success);
                        $('#produccion_id').val(json.produccion_id);
                        $('#produccion_estado').val(json.estado);
                        $('.next_form').css('display','block');
                         $("#presupuestosTable").dataTable().fnReloadAjax();
                    }
                }
            });
        });
    
        $("#fecha").datepicker({
            dateFormat: 'yy-mm-dd'
        });

});
      
    
</script>
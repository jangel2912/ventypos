<h4>&nbsp; Procesando Solicitud de Venta Online #<span id="atend_id"><?php echo $data->id ?></span>  </h4>

<?php echo form_open_multipart("ventas_online/atender_solicitud", array("id" => "venta_online_atender")); ?>
       <div class="row-fluid">
            <div id="atend_fecha" class="span6" style="text-align: left; margin-left: 0px !important"><?php echo $data->fecha ?></div>
       </div>
       <?php if ($data->stock_almacen == 1){ ?>
       <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <label>Almacen origen productos</label>
                <select name="s_almacen_origen_confirmacion" id="s_almacen_origen_confirmacion">
                    <option value="">Seleccione almacen</option>
                    <?php foreach ($data->almacenes as $key => $value) { ?>
                        <option data-stock="<?php echo $value['stock_actual'] ?>" value="<?php echo $value['id'] ?>">Todo de: <?php echo $value['nombre'] ?></option> 
                    <?php } ?>
                    <option value="individuales">desde diferentes almacenes</option>
                </select>
            </div>
        </div>
        <?php }else{ ?>
            <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <label>Almacen productos</label>
                <?php echo $data->almacenes ?>
            </div>
            </div>
        <?php    } ?>
        <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <h6>Relaci&oacute;n de productos:</h6>
                <table class="table aTable" id="atend_productos">
                    <thead>
                       <tr>
                            <th class="tabp tabhead">Producto</th>
                            <th class="tabpr tabhead">Precio Unitario</th>
                            <th class="tabcm tabhead">Cant.</th>
                            <th class="tabcm tabhead">Almacen de tienda</th>
                            <th class="tabt tabhead">Total</th>
                            <th class="tabt tabhead">Existencias en varios almacenes</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php echo $data->productos; ?>
                    </tbody>  
                </table>
            </div>    
        </div>
        
        <div class="row-fluid">
        
            <div class="span12" >
                <div class="span3" style="text-align: left"> Subtotal:  </div>
                <div class="span3" id="atend_subtotal" style="text-align: left"><?php echo $data->subtotal ?>   </div>
            </div>
        </div>
        <div class="row-fluid">
           
            <div class="span12">
                <div class="span3" style="text-align: left"> Impuesto:  </div>
                <div class="span3" id="atend_impuesto" style="text-align: left"><?php echo $data->impuesto; ?>  </div>
            </div>
        </div>    
        <div class="row-fluid">
    
            <div class="span12" >
                <div  class="span3" style="text-align: left"> Total:  </div>
                <div id="atend_total" class="span3" style="text-align: left"><?php echo $data->total; ?>  </div>
            </div>
        </div>
        <?php if($data->puede == 1){ ?>    
        <div class="row-fluid">
            <div id="descuento" style="display: none">
                <div id="atend_descuento" class="span12"  style="text-align: left; color: #0066cc "> Descuento: $<?php echo $data->descuento; ?>  </div> 
                
                <div id="atend_descuento_imp" class="span12" style="text-align: left; color: #0066cc"> Descuento de Impuesto: $<?php echo $data->descuento_imp; ?> </div> 
                
                <div id="atend_total_final" class="span12" style="text-align: left; color: #0066cc"> Nuevo Total: $<?php echo $data->final ?>  </div>
            </div>
        </div>
        <?php } ?>    
        <div class="row-fluid">
            <div class="span6" style="margin-left: 0px !important"> &nbsp; </div>
            <span id="atend_msg"></span> 
        </div>

        <input type="hidden" id="conf_id" name="conf_id" value="<?php echo $data->id ?>"/>
        <div class="row-fluid">
        <?php if($data->puede !=2 or ($data->stock_almacen == 1) ){ 
            if(is_null($data->venta_id)){ ?>
                 <button type="submit" class="btn btn-default" id="btn_confirm">Confirmar</button>    
            <?php }else{ ?>
                <div class="alert alert-error">La venta online ya fue facturada, no puede volverse a facturar </div>
               
                <?php } ?>            
             <?php }else{ ?>
                <div class="alert alert-error">No tiene existencias del producto en el almacen (o almacenes) origen </div>
             <?php } ?>        
             <a href="<?php echo site_url('ventas_online/ventas') ?>"  class="btn btn-danger" >Cerrar</a>
        </div>
        <?php echo form_close() ?>

<script type="text/javascript">
    var almacenes_tienda =<?php echo json_encode($data->almacenes) ?>;

    $(document).ready(function () {

        $("#s_almacen_origen_confirmacion").on('change',function(){
            mostrar_indidual_productos($(this).val(),$(this).attr('data-stock'));
        });
    });

    function mostrar_indidual_productos(value,$stock){
      
        if(String(value) ==='individuales'){
            construir_select_almacen();
        }else{
            $(".select_existencias").html('');
        }
    }

    function construir_select_almacen(){
        $.each($("#atend_productos tbody tr"),function(index,value){
            var id_producto = $(value).attr('data-id');
            var select = $('<select name="s_almacen_independiente_'+id_producto+'" required>');
            select.append($("<option>").attr('value','').text('seleccione'));
            $.each(almacenes_tienda,function(key,value){
                 select.append($("<option>").attr('value',value.id).text(value.nombre));
            });
            $(value).find(".select_existencias").html(select);    
        });


    }

</script>
       

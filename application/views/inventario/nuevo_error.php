<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang("Inventario", "Inventario");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_bill', "Nuevo movimiento de inventario");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
        
                            <div class="data-fluid">
                                
                                <?php 
								$is_admin = $this->session->userdata('is_admin');
                                $permisos = $this->session->userdata('permisos');
                                $entrada = in_array('1019', $permisos) || $is_admin == 't' ? 1 : 0;
                                $salida = in_array('1020', $permisos) || $is_admin == 't' ? 1 : 0;
                                $traslado = in_array('1021', $permisos) || $is_admin == 't' ? 1 : 0;

								echo form_open("inventario/nuevo", array("id" =>"validate"));?>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>
                                                <?php echo form_error('fecha'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('tipo_movimiento', "Tipo");?>:</div>
                                        <div class="span9" id="movimiento" data-can="<?= $entrada.'-'.$salida.'-'.$traslado ?>">
                                            <?php echo form_dropdown('tipo_movimiento', $data['tipo']); ?>
                                            <?php echo form_error('tipo_movimiento'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('almacen', "Almacen");?>:</div>
                                        <div class="span9">
                                                <?php
												if($is_admin == 's'){
												 echo $data['almacen_nombre']; 
												   ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen_id" id="almacen_id"/><?php
												} 
												else{
												 echo form_dropdown('almacen_id', $data['almacenes']); 
												}
												 ?>
                                                <?php echo form_error('almacen_id'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('tipo_movimiento', "Nota");?>:</div>
                                        <div class="span9">
                                            <textarea name="nota" id="nota" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Proveedor");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('proveedor_id', $data['proveedores']) ?>
                                                <?php echo form_error('proveedor_id'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Codigo doc");?>:</div>
                                        <div class="span9">
                                                <?php echo form_input('codigo_factura', "", 'id="codigo_factura"') ?>
                                                <?php echo form_error('codigo_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Almacen de traslado");?>:</div>
                                        <div class="span9">   
                                            <select name='almacen_traslado_id' id='almacen_traslado_id'>
										       <?php
                                               //var_dump($data['almacenes']) ;
                                               //echo '<option value="">'.$data['almacenes'].'</option>';
                                               foreach($data['almacenes'] as $almacen) {
                                                    echo '<option value="'.$almacen['id'].'">'.$almacen['nombre'].'</option>';
                                                }
												/*if($is_admin == 's'){
												 echo $data['almacen_nombre']; 
												   ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen_traslado_id" id="almacen_traslado_id"/><?php
												} 
												else{*/
												  /*echo form_dropdown('almacen_traslado_id', $data['almacenes'], '', "disabled='disabled'", "id='almacenes_traslado'" ); */
												//}
											    ?>
                                            </select>
                                                <?php echo form_error('almacen_traslado_id'); ?>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="block">
                                    <div class="head dblue">
                                        <h2>Productos</h2>
                                    </div>
                                <div class="data"> 
                                    <div class="span5">
                                        <div class="row-form">
                                            <div class="span3"><?php echo custom_lang('sima_date_v', "Producto");?>:</div>
                                            <div class="span9">
                                                    <?php echo form_input(array('name' => 'producto', 'id' => 'product-service')) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span5">
                                        <div class="row-form">
                                            <div class="span3"><?php echo custom_lang('sima_date_v', "Cantidad");?>:</div>
                                            <div class="span9">
                                                    <?php echo form_input(array('name' => 'cantidad', 'id' => 'cantidad'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="row-form">
                                            <div class="span3">&nbsp;</div>
                                            <div class="span9">
                                                <input type="hidden" name="id" id="id" value=""/>
                                                <input type="hidden" name="unidades" id="unidades" value=""/>
                                                <input type="hidden" name="codigo" id="codigo" value=""/>
                                                <input type="hidden" name="nombre" id="nombre" value=""/>
												
                                                <input type="hidden" name="precio_compra" id="precio_compra" value=""/>
																					
                                                <button class="btn" type="button" id="adicionar_producto"><?php echo custom_lang("sima_submit", "Adicionar");?></button>
												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                    <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10px"></th>
                                            <th width="15%"><?php echo custom_lang('sima_product_name', "Codigo");?></th>
                                            <th width="20%"><?php echo custom_lang('sima_description', "Nombre");?></th>
                                            <th width="10%"><?php echo custom_lang('sima_amount', "Existensias");?></th>
                                            <th width="10%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>
                                            <th width="15%" style="text-align:right" width="10%"><?php 
											 if($is_admin == 't' || in_array('1019', $permisos)){ 
											   echo custom_lang('sima_price', "Precio");
											}
											?></th>
                                            <th style="text-align:right" width="15%">
											<?php if($is_admin == 't' || in_array('1019', $permisos)){ ?>		
											<?php echo custom_lang('sima_total_price', "Precio Total");
											}
											?></th>
                                            <th width="5%" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalle">
                                    </tbody>
                                    <tfoot>
									<?php if($is_admin == 't'){ ?>	
                                        <tr>
                                            <th colspan="5"></th>
                                            <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total");?></b></th>
                                            <th style="text-align:right"><b class="total">0.00</b></th>
                                            <th>&nbsp;</th>
                                        </tr>
									<?php } ?>	
                                    <tfoot>
                                </table>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="button" id="enviar_movimiento"><?php echo custom_lang("sima_submit", "Guardar");?></button>
 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
							<?php if($is_admin != 't'){ ?>	
                            <input type="hidden" name="total" class="total" />
							<?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
        $( "#fecha" ).datepicker({
             dateFormat: 'yy-mm-dd'
        });
    
                                   <?php
										if($is_admin == 's'){
								          ?> //$("select[name='tipo_movimiento']").find("option[value='traslado']").remove();  <?php
										} 
								   ?>		
	
	    
        $('#product-service').autocomplete({
            source: function( request, response ) {
                    $.ajax({
                            url: "<?php echo site_url("productos/filtro_prod_existencia"); ?>",
                            type:"GET",
                            dataType: "json",
                            data: {
							
                                   <?php
										if($is_admin == 's'){
								          ?> almacen: $("#almacen_id").val(), <?php
										} 
									else{
										 ?>	almacen: $("select[name='almacen_id']").val(),   <?php
										}
								   ?>							
                                    
                                    term: request.term
                            },
                            success: function(data) {

                                    response( $.map( data, function( item ) {
                                    
                                            return {
                                                    unidades: item.unidades,
                                                    id: item.id,
                                                    precio_compra: item.precio_compra,
                                                    value: item.nombre,
                                                    codigo: item.codigo
                                            }
                                    }));
                            }
                    });
            },
            minLength: 2,
            select: function( event, ui ) {
            
                    $("#precio_compra").val(ui.item.precio_compra);
                    $("#codigo").val(ui.item.codigo);
                    $("#nombre").val(ui.item.value);
                    $("#unidades").val(ui.item.unidades);
                    $("#id").val(ui.item.id);
                    
                    $("#cantidad").val(1);
                    $("#cantidad").focus();
                   // calculate();
            }
        }).on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (code == 9) {
               $('#product-service').autocomplete("search", $('#product-service').val());
                e.preventDefault();
            }
        }); 
        
        $("#adicionar_producto").click(function(e){
            e.preventDefault();
<?php if($is_admin == 't' || in_array('1019', $permisos)){ ?>			
            var tr = "<tr><td>&nbsp;<input name='producto_id[]' type='hidden' value='"+$("#id").val()+"' class='producto_id'/></td><td class='codigo'>"+$("#codigo").val()+"</td><td class='nombre'>"+$("#nombre").val()+"</td><td class='unidades'>"+$("#unidades").val()+"</td><td><input type='text' name='cantidad[]' class='cantidad' value='"+$("#cantidad").val()+"'/></td><td style='text-align: right;'><input type='text' name='precio_compra[]' class='precio_compra' value='"+$("#precio_compra").val()+"'/></td><td style='text-align: right;' class='ptotal'>"+$("#precio_compra").val() * $("#cantidad").val()+"</td><td><a  class='button red delete' title='Eliminar' href='#'><div class='icon'><span class='ico-remove'></span></div></td></tr>";
<?php } else if($is_admin != 't') { ?>			
            var tr = "<tr><td>&nbsp;<input name='producto_id[]' type='hidden' value='"+$("#id").val()+"' class='producto_id'/></td><td class='codigo'>"+$("#codigo").val()+"</td><td class='nombre'>"+$("#nombre").val()+"</td><td class='unidades'>"+$("#unidades").val()+"</td><td><input type='text' name='cantidad[]' class='cantidad' value='"+$("#cantidad").val()+"'/></td><td style='text-align: right;'><input type='hidden' name='precio_compra[]' class='precio_compra' value='"+$("#precio_compra").val()+"'/></td><td style='text-align: right;'  > <input type='hidden' name='ptotal' class='ptotal' /></td><td><a  class='button red delete' title='Eliminar' href='#'><div class='icon'><span class='ico-remove'></span></div></td></tr>";
<?php } ?>					
            $("#detalle").append(tr);
            $("#precio_compra").val("");
            $("#codigo").val("");
            $("#nombre").val("");
            $("#unidades").val("");
            $("#id").val("");

            $("#cantidad").val("");
            $("#product-service").val("");
            $("#product-service").focus();
            calculate();
        });
                
       
        function checkEmail(email) {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if (!filter.test(email)) {
                return false;
             }
             return true;
        }
        
        
        function calculate()
        {
                var suma=0;
                $(".cantidad").each(function(x){
                        cantidad = parseFloat($(".cantidad").eq(x).val());
                        precio_compra = parseFloat($(".precio_compra").eq(x).val());
                        suma_row = parseFloat($(".cantidad").eq(x).val()) * parseFloat($(".precio_compra").eq(x).val());
                        suma += suma_row;
                        $(".ptotal").eq(x).text(suma_row);
                });
                //(suma).toFixed(2)
                $(".total").html(suma);
                $("#total").val(suma);
        }
        
         function calculate_row(){
            impuesto = $("#precio").val() * $("#impuestos").val() / 100 * $("#cantidad").val();
            total = parseFloat($("#precio").val() * $("#cantidad").val()) - parseFloat($("#descuento").val()* $("#precio").val() / 100 * $("#cantidad").val()) + impuesto; 
            $('#total_row').text(total);
        }
        
        $(".cantidad, .precio_compra").live("keyup",(function(){
                //calculate_row();
                calculate();
        }));

        $(".delete").live("click",(function(e){
            e.preventDefault();
                $(this).parent().parent().remove();
                calculate();
        }));
       
        $("#enviar_movimiento").click(function () { 
                /*if(document.getElementById('codigo_factura').value == '') {
                    alert('El codigo de documento no debe estar vacio');
                    return false;
                }*/
                productos_list = new Array();
                
                $(".cantidad").each(function(x){
                    productos_list[x] = {
                        'cantidad': parseFloat($(".cantidad").eq(x).val()),
                        'precio_compra': parseFloat($(".precio_compra").eq(x).val()),
                        'codigo_barra': $(".codigo").eq(x).text(),
                        'nombre': $(".nombre").eq(x).text(),
                        'existencias': $(".unidades").eq(x).text(),
                        'total_inventario': $(".ptotal").eq(x).text(),
                        'producto_id' : $(".producto_id").eq(x).val()
                    }
                });    
                
                if(productos_list.length && productos_list.length > 0) {
                    document.getElementById("enviar_movimiento").disabled = true;
        
                    $.ajax({
                        url: "<?php echo site_url("inventario/nuevo");?>"
                        ,dataType: 'json'
                        ,type: 'POST'
                        ,data: {
                            fecha: $("#fecha").val()
                            ,tipo_movimiento: $("select[name='tipo_movimiento']").val()
                                   <?php
										if($is_admin == 's'){
								          ?>   ,almacen_id: $("#almacen_id").val()
										       ,almacen_traslado_id: $("#almacen_traslado_id").val()
										   <?php
										} 
									else{
										 ?>	  ,almacen_id: $("select[name='almacen_id']").val()
										      ,almacen_traslado_id: $("select[name='almacen_traslado_id']").val()
										   <?php
										}
								   ?>								
                          
                            ,proveedor_id: $("select[name='proveedor_id']").val()
                            ,codigo_factura: 'Rf. '+$("input[name='codigo_factura']").val()
                            ,nota: $('textarea[name="nota"]').val()
                            ,total_inventario: $('.total').text()
                            ,productos: productos_list
                        }
                        ,error: function(jqXHR, textStatus, errorThrown ){
                            alert(errorThrown);
                        }
                        ,success: function(data){
                            if(data.success == true){
                                location.href = "<?php echo site_url("inventario/index");?>";
                            }
                            else{
                                alert("Movimiento de inventario no creada");
                            }
                        }
                    });
                } else alert("Por favor agregue como minimo un producto de detalle.");
        });
        
        $("select[name='tipo_movimiento']").change(function(){

            if($(this).val() == 'traslado'){
                $("select[name='almacen_traslado_id']").removeAttr('disabled');
                $("select[name='proveedor_id']").attr('disabled', 'disabled');
                $("input[name='codigo_factura']").attr('disabled', 'disabled');
            }
            else if($(this).val() == 'entrada_compra'){
                $("select[name='proveedor_id']").removeAttr('disabled');
                $("input[name='codigo_factura']").removeAttr('disabled');
                $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
            }
            else if($(this).val() == 'entrada_remision'){
                $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
                $("input[name='codigo_factura']").attr('disabled', 'disabled');   
            }
            else{
                $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
                $("select[name='proveedor_id']").attr('disabled', 'disabled');
                $("input[name='codigo_factura']").attr('disabled', 'disabled');
            }
        });

        var can = $('#movimiento').data('can');
        var select_movimiento = $('select[name="tipo_movimiento"]');
        var movimientos = can.split('-');

        console.log(movimientos, can);
        $.each(movimientos, function(i, e)
        {
            var selector = '';
            switch(i)
            {
                case 0:
                    selector = 'entrada';
                break;
                case 1:
                    selector = 'salida';
                break;
                case 2:
                    selector = 'traslado';
                break;
            }

            if(e == 0)
                select_movimiento.find('option[value^="'+selector+'"]').remove();
            
        });

        select_movimiento.trigger('change');
        
    });
</script>
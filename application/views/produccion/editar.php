<script src="<?php echo base_url('index.php/OpcionesController/index'); ?>"></script>
<script>
    $(document).on('blur','.psi',function(){
       $(this).val(($(this).val()));
   });
</script>

<div class="page-header">

    <div class="icon">

        <span class="ico-money"></span>

    </div>

    <h1><?php echo custom_lang("Cotizaciones", "Cotizaci&oacute;n");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_edit_quote', "Editar cotizaci&oacute;n");?></h2>     

        <?php //echo "producto ".$data['producto']; ?>                                

    </div>

</div>

<div class="row-fluid">

    <div class="block">

                            <div class="data-fluid">

                                <div id="message">

                                </div>

                                <?php echo form_open("presupuesto/editar", array("id" =>"validate"));?>

                                    <input type="hidden" name="id_presupuesto" id="id_presupuesto" value="<?php echo set_value('id_presupuesto', $data["data"]['id_presupuesto']); ?>" />

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_invoice_number', "Numero de factura");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('numero', $data['data']['numero']); ?>" name="numero" readonly="readonly"/>

                                                <?php echo form_error('numero'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d", strtotime($data['data']['fecha'])); ?>" name="fecha" id="fecha" />

                                                <?php echo form_error('numero'); ?>

                                        </div>

                                    </div>

                                

                               

                                    <div class="row-form">

                                     
                                        <div class="span3"><?php echo custom_lang('sima_customer', "Cliente");?>:</div>

                                        <div class="span9">

                                                    <div class="input-append"> 

                                                    <input type="text"  value="<?php echo set_value('datos_cliente', $data['data']['nombre_comercial']); ?>" name="datos_cliente" id="datos_cliente"/>

                                                    <span class="add-on blue" id="add-new-client"><i class="icon-user icon-white"></i></span>

                                               </div>

                                                  <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $data['data']['id_cliente']; ?>" />

                                                <?php echo form_error('id_cliente'); ?>

                                                <?php echo form_error('datos_cliente'); ?>

                                        </div>


                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer_data', "Datos del cliente");?>:</div>

                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="Datos del cliente" readonly="readonly"><?php echo $data["data"]['nombre_comercial']." (".$data["data"]['razon_social'].") \n".$data["data"]['nif_cif'].", ".$data["data"]['direccion'].", ".$data["data"]['poblacion'].", ".$data["data"]['pais'].", ".$data["data"]['provincia'].", ".$data["data"]['cp'] ?></textarea>

                                        </div>

                                    </div>

                                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>

                                            <th width="10"></th>

                                            <th width="20%"><?php echo custom_lang('sima_product_name', "Nombre del producto");?></th>

                                            <th width="25%"><?php echo custom_lang('sima_description', "Descripción");?></th>

                                            <th width="10%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>

                                            <th width="10%" style="text-align:right" width="10%"><?php echo custom_lang('sima_price', "Precio");?></th>

                                            <th width="10%"><?php echo custom_lang('sima_discount', "Descuento(%)");?></th>

                                            <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>

                                            <th style="text-align:right" width="10%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>

                                            <th width="10%">                                                    
											<button style="border: 0;" type='button'  class='button blue add'>
                                                        <div class='icon'>
                                                            <span class='ico-plus'>
                                                            </span>
                                                        </div>
                                                    </button><br />
													</th>

                                            </tr>

                                        </thead>


                                            <?php

                                              $total = 0;
                                              $iva = 0;
                                               foreach($data['detail'] as $k) {
                                                $precio_t = $k['precio'] * $k['cantidad'];
                                                $impuesto = $k['imp'] * $precio_t / 100;
                                                $descuento = $k['descuento'] * $precio_t / 100;
                                               //  $total    = $this->opciones_model->formatoMonedaMostrar($impuesto + $precio_t - $descuento);

                                              ?>

                                      <tr></tr>
                                    <tr>

                                                <td width="10">
                                                    <input type="hidden" name="id_producto" class="product_id"  id="id_producto" value="<?php echo $k['fk_id_producto']; ?>"/>
                                                </td>

                                                <td>
                                                    <input type="text" name="product-service" id="product-service" value='<?php echo $k['nombre']."-".$k['codigo'] ?>'/>
                                                    <span id='product-service-error'></span>
                                                </td>

                                                <td id='descripcion_text'>
                                                    <textarea id="descripcion" class="description" name="descripcion" ><?php echo $k['descripcion_d']; ?></textarea>
                                                </td>

                                                <td>
                                                    <input type="text" name="cantidad" class="quantity" id="cantidad" value="<?php echo $k['cantidad']; ?>"/>
                                                    <span id='cantidad-error'></span>
                                                </td>

                                                <td><input type="text" name="precio" style="text-align:right" id="precio" class="psi" value="<?php echo $k['precio']; ?>"/>
                                                    <span id='precio-error'></span>
                                                </td>

                                                <td><input type="text" name="descuento" id="descuento" class="discount" value="<?php echo $k['descuento']; ?>"/>
                                                    <span id='descuento-error'></span>
                                                </td>

                                                <td><?php //echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto',$k['id_impuesto']), "id='impuestos' class='impuesto'");?>
												
	<?php 
	echo "<select  name='almacen' id='impuestos' class='impuesto'  >";     
    foreach($data['impuestos_1'] as $f){
        if($f->id_impuesto == $k['id_impuesto']){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->porciento . ">" . $f->nombre_impuesto . "</option>";
    }    
    echo "</select>";
    ?> 												
												</td>

                                                <td id="total_row" class="ptotal" style="text-align:center"><?php echo $total; ?></td>
                                                <td><br />
                                                  <a  class="button red delete" title="Eliminar" href="#"><div class="icon"><span class="ico-remove"></span></div></a>
                                                </td>

                                            </tr>

                                            <?php 

                                                }

                                            ?>

                                        <tbody id="detalle">
                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                    <th colspan="3"></th>

                                                <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA");?></b></th>

                                                <th style="text-align:right"><b class="total_siva"><?php echo $data['data']['monto_siva']; ?></b></th>

                                                <th colspan="4">&nbsp;</th>

                                            </tr>

                                            <tr>

                                                <th colspan="3"></th>

                                                <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA");?></b></th>

                                                <th style="text-align:right"><b class="iva"><?php echo $data['data']['monto_iva']; ?></b></th>

                                                <th colspan="4">&nbsp;</th>

                                            </tr>

                                            <tr>

                                                <th colspan="3"></th>

                                                <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA");?></b></th>

                                                <th style="text-align:right"><b class="total_civa"><?php echo $data['data']['monto']; ?></b></th>

                                                <th colspan="4">&nbsp;</th>

                                            </tr>

                                        <tfoot>

                                    </table>


                                      <input type="hidden" name="input_iva" id="input_iva" value='<?php echo $data['data']['monto_iva']; ?>' />

                                    <input type="hidden" name="input_total_civa" id="input_total_civa" value='<?php echo $data['data']['monto']; ?><' />

                                    <input type="hidden" name="input_total_siva" id="input_total_siva" value='<?php echo $data['data']['monto_siva']; ?>' />
                                <!--<div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn" type="submit">Submit</button>

                                        <button class="btn btn-warning" type="reset">Cancelar</button>

                                    </div>

                                </div> -->

                            </div>

                                <div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn" type="button" id="enviar_cotizacion"><?php echo custom_lang("sima_submit", "Guardar");?></button>

                        <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                    </div>

                                </div>

                            </form>

    </div>


<?php $no_cotizacion= $data['data']['id_presupuesto']; ?>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        calculate();
       $("#datos_cliente").autocomplete({

            source: "<?php echo site_url("clientes/get_ajax_clientes"); ?>",

            minLength: 2,

            select: function( event, ui ) {

                                $("#id_cliente").val(ui.item.id);

                $("#otros_datos").val(ui.item.descripcion);

            }

        });
       
        $( "#fecha, #fecha_v" ).datepicker({

             dateFormat: 'yy/mm/dd'

        });

        

        $('#product-service').autocomplete({

            source: function( request, response ) {

                    $.ajax({

                            url: "<?php echo site_url("productosf/filtro_prod"); ?>",

                            type:"GET",

                            dataType: "json",

                            data: {

                                    //type: $("input[name='tipo']:checked").val(),

                                    term: request.term

                            },

                            success: function(data) {



                                    response( $.map( data, function( item ) {

                                            return {

                                                    value: item.nombre+"-"+item.codigo,

                                                    id: item.id,

                                                    precio: item.precio_venta,

                                                    porciento: item.porciento,

                                                    descripcion: item.descripcion

                                            }

                                    }));

                            }

                    });

            },

            minLength: 2,

            select: function( event, ui ) {

                    $("#precio").val(ui.item.precio);

                    $("#product-service").val(ui.item.id);

                    $("#impuestos").val(ui.item.porciento);

                    $("#descripcion").val(ui.item.descripcion);

                    $("#id_producto").val(ui.item.id);

                    $("#cantidad").val(1);

                   //  calculate_row();

                     calculate();

            }

        });

        

        $(".add").click(function(e){

              e.preventDefault();

              var flag_errors = false;

            

              if($("#id_producto").val() == ""){

                $("#product-service-error").text("Producto no v&aacute;lido");

                flag_errors = true;

            }

            

            if($("#product-service").val() == ""){

                $("#product-service-error").text("Seleccione un producto");

                flag_errors = true;

            }

            else{

                $("#product-service-error").empty();

            }

            

            

            if($("#cantidad").val() == ""){

                $("#cantidad-error").text("Cantidad requerida");

                flag_errors = true;

            }

            else if(isNaN($("#cantidad").val())){

                $("#cantidad-error").text("Inserte un numero");

                flag_errors = true;

            }

            else{

                $("#cantidad-error").empty();

            }

            

            if($("#descuento").val() == ""){

                $("#descuento-error").text("Descuento requerido");

                flag_errors = true;

            } else if(isNaN($("#descuento").val())){

                $("#descuento-error").text("Inserte un numero");

                flag_errors = true;

            }

            else{

                $("#descuento-error").empty();

            }

            if($("#precio").val() == ""){

                $("#precio-error").text("Precio requerido");

                flag_errors = true;

            } else if(isNaN($("#precio").val())){

                $("#precio-error").text("Inserte un numero");

                flag_errors = true;

            }

            else{

                $("#precio-error").empty();

            }

            

            if(!flag_errors){

                var html = '<tr><td width="10"><input type="hidden" name="product_id[]" id="product_id" class="product_id"  value="'+$('#id_producto').val()+'"/></td><td><input type="text" name="product_name[]" class="product-service" value="'+$("#product-service").val()+'" id="product_name"/></td><td><textarea name="description[]" class="description">'+$("#descripcion").val()+'</textarea></td><td><input type="text" name="quantity[]" class="quantity" value="'+$("#cantidad").val()+'" id="cantidad"/></td><td><input style="text-align:right" value="'+$("#precio").val()+'" type="text" class="psi" name="psi[]"/></td><td><input type="text" name="discount[]" class="discount" id="discount" value="0"/></td><td><select name="impuesto[]" class="impuesto" id="'+$("#cantidad").val()+'_'+$("#product-service").val()+'"><?php foreach($data['impuestos'] as $key => $value){echo '<option value="'.$key.'">'.$value.'</option>';}?></select></td><td style="text-align:center" class="ptotal">'+ '' +'</td><td><a  class="button red delete" title="Eliminar" href="#"><div class="icon"><span class="ico-remove"></span></div></td></td></tr>';

                $("#detalle").append(html);

                $("#"+$("#cantidad").val()+"_"+$("#id_producto").val()).val($("#impuestos").val());

                

                

                $('#product-service').val("");

                $('#precio').val(0.0);

                $('#cantidad').val(0);

                $('#descuento').val(0);

                $("#impuestos").val("");

                $("#descripcion").val("");

                $("#total_row").empty(0); 

                
                calculate();

            }

            else{

                alert("Existen errores en el formulario");

            }

        });

        

        

        $( "#dialog-client-form" ).dialog({

            autoOpen: false,

            //height: 400,

            width: 620,

            modal: true,

            buttons: {

                "Aceptar": function() {

                                        var error = false;

                                        $(".error_email").text("");

                                        $(".error_cliente").text("");

                                        $(".error_nif_cif").text("");

                                        

                                        //error_cliente error_email 

                                        if($('#nombre_comercial').val() == ""){

                                            $(".error_cliente").text("Nombre requerido");

                                            error = true;

                                        }

                                        

                                        if($("#email").val() != "" && checkEmail($("#email").val()) == false){

                                            $(".error_email").text("Email inválido");

                                            error = true;

                                        }

                                        

                                        if($('#nif_cif').val() == ""){

                                            $(".error_nif_cif").text("Nif/Cif requerido");

                                            error = true;

                                        }

                                        

                                        if(!error){

                                           $.ajax({

                                                    url: '<?php echo site_url('clientes/add_ajax_client');?>',

                                                    data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val()},

                                                    dataType: 'json',

                                                    type: 'POST',

                                                    success: function(data){

                                                        $("#id_cliente").val(data.id_cliente);

                                                        $("#datos_cliente").val($('#nombre_comercial').val() + "(" + $('#razon_social').val()+ ")");

                                                        $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());

                                                        

                                                        $("#dialog-client-form").dialog( "close" );

                                                    }

                                                });

                                        }

                },

                "Cancelar": function() {

                    $( this ).dialog( "close" );

                }

            },

            close: function() {

                            $('#razon_social').val("");

                            $('#nif_cif').val("");

                            $('#email').val("");

                            $('#nombre_comercial').val("");

                            

                            $(".error_email").text("");

                            $(".error_cliente").text("");

                            $(".error_nif_cif").text("");

            }

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
            var suma=0, iva = 0;
            
            $(".psi").each(function(x){
                $(".psi").eq(x).val(limpiarCampo($(".psi").eq(x).val()));
                psi = ($(".psi").eq(x).val());
                //console.log(psi+"as"+);
                quantity = limpiarCampo($(".quantity").eq(x).val());
                discount = limpiarCampo($(".discount").eq(x).val());
                impuesto = limpiarCampo($(".impuesto").eq(x).val());
                cantidad = psi * quantity;
                suma_row = cantidad - (discount * cantidad / 100);
                suma += suma_row;
                impuesto_row =  cantidad * impuesto / 100;
                iva += impuesto_row;
                $(".ptotal").eq(x).text(limpiarCampo(suma_row +  impuesto_row));

            });

                

                $(".total_siva").html(mostrarNumero(suma));

                $(".iva").html(mostrarNumero(iva));

                $(".total_civa").html(mostrarNumero(suma + iva));

                $("#input_total_civa").val(limpiarCampo(suma + iva));

                $("#input_total_siva").val(limpiarCampo(suma));

                $("#input_iva").val(limpiarCampo(iva));

        }

        

         /* function calculate_row(){

            impuesto = $("#precio").val() * $("#impuestos").val() / 100 * $("#cantidad").val();

            total = parseInt($("#precio").val() * $("#cantidad").val()) - parseInt($("#descuento").val()* $("#precio").val() / 100 * $("#cantidad").val()) + impuesto; 

            $('#total_row').text(total);

        }

        

      $('#precio, #cantidad, #descuento').keyup(function(){

            calculate_row();

        });*/

       

       $('#impuestos').change(function(){

           //calculate_row();

           calculate();

       });

       

        $(".psi, .quantity, .discount").live("keyup",(function(){

                //calculate_row();

                calculate();

        }));

        

        $(".impuesto").live("change", function(){

              // calculate_row();

               calculate();

        });



        $(".delete").live("click",(function(e){

            e.preventDefault();

                $(this).parent().parent().remove();

     

               // calculate_row();

                calculate();

        }));

        

        $("#add-new-client").click(function(){

            $( "#dialog-client-form" ).dialog( "open" );

        });

        

        $("#enviar_cotizacion").click(function () { 

            //valida si hay cliente activo

            if($('#id_cliente').val() == ''){

                if(confirm("<?php echo custom_lang("sima_not_valid_client", "El cliente no es válido, desea adicionarlo?") ?>")){

                    if($("#datos_cliente").val() != ""){

                        $('#nombre_comercial').val($("#datos_cliente").val());

                    }

                    $( "#dialog-client-form" ).dialog( "open" );

                }

                return false; 

            }

            else{

                    

                    productos_list = new Array();

                    $(".psi").each(function(x){

                        productos_list[x] = {

                              'fk_id_producto': $('.product_id').eq(x).val() 

                            , 'precio': parseFloat($(".psi").eq(x).val())

                            , 'cantidad': parseFloat($(".quantity").eq(x).val())

                            , 'impuesto': parseInt($(".impuesto").eq(x).val())

                            , 'descuento': parseInt($(".discount").eq(x).val())

                            , 'descripcion': $(".description").eq(x).val()

                        }

                    });

                    

                    

                    $.ajax({

                        url: "<?php echo site_url("presupuestos/editar/".$no_cotizacion);?>"

                       /*,dataType: 'json'*/

                        ,type: 'POST'

                        ,data: {

                            id_cliente: $("#id_cliente").val()

                            ,id_presupuesto: $("#id_presupuesto").val()

                            ,numero: $("input[name='numero']").val()

                            ,fecha: $("#fecha").val()

                            //,fecha_v: $("#fecha_v").val()

                            ,input_total_civa: $("#input_total_civa").val()

                            ,monto_siva: $("#input_total_siva").val()

                            ,monto_iva: $("#input_iva").val()

                            ,productos: productos_list

                        }

                        ,error: function(jqXHR, textStatus, errorThrown ){

                                location.href = "<?php echo site_url("presupuestos/index");?>";

                            


                        }

                        ,success: function(data){


                                
                                  location.href = "<?php echo site_url("presupuestos/index");?>";

                            }

                            // else{

                            //     alert("Presupuesto no actualizado"+data);

                            // }

 //                       }

                    });

            }

        });

    });

</script>
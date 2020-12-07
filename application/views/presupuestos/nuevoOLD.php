
<script src="<?php echo base_url('index.php/OpcionesController/index'); ?>"></script>

<div class="page-header">

    <div class="icon">

        <span class="ico-money"></span>

    </div>

    <h1><?php echo custom_lang("Cotizaciones", "Cotizaci&oacute;n");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_quote', "Nueva cotizaci&oacute;n");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="block"><?php //echo validation_errors(); ?>

                            <div class="data-fluid">

                                <?php echo form_open("presupuestos/nuevo", array("id" =>"validate"));?>

                                

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_invoice_number', "Numero de factura");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('numero', $data['cod']); ?>" name="numero" readonly="readonly"/>

                                                <?php echo form_error('numero'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>

                                                <?php echo form_error('numero'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer', "Cliente");?>:</div>

                                        <div class="span9">

                                                    <div class="input-append"> 

                                                    <input type="text"  value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente" id="datos_cliente"/>

                                                    <span class="add-on blue" id="add-new-client"><i class="icon-user icon-white"></i></span>

                                               </div>

                                                <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo set_value('id_cliente'); ?>" />

                                                <?php echo form_error('id_cliente'); ?>

                                                <?php echo form_error('datos_cliente'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer_data', "Datos del cliente");?>:</div>

                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="" readonly="readonly"><?php echo set_value('otros_datos'); ?></textarea>

                                        </div>

                                    </div>

                                    <div class="toolbar bottom tar">

                                        <div class="btn-group">

                                           <!-- <a href="#" id="add-product-service" class="btn"><?php echo custom_lang('sima_add_product_service', "A&ntilde;adir producto o servicio");?></a>-->

                                        </div>

                                    </div>

                                
                                   <!-- Button to trigger modal -->
                                    <a href="#producto-modal" role="button" class="btn" data-toggle="modal">
                                         <small class="ico-plus icon-white"></small> 
                                        Nueva producto (rapido)  
                                    </a>           

                                    <div id="mensaje-contenedor"></div>
                                   

                                    <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">

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

                                            <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>

                                        </tr>

                                    </thead>

                                    <tbody id="detalle">

                                        <tr>

                                            <td width="10">

                                                <input type="hidden" name="id_producto" class="product_id"  id="id_producto" value="id_producto"/>

                                            </td>

                                            <td>

                                                <input type="hidden" name="product-service" id="product-service" class='product-service'/>
                                              
                                                <input type="text" name="nombre-producto" id="nombre-producto" class='nombre-producto'/>

                                                <span id='product-service-error'></span>

                                            </td>

                                            <td id='descripcion_text'><textarea id="descripcion" class="description" name="descripcion"></textarea></td>

                                            <td>

                                                <input type="text" name="cantidad" class="quantity" id="cantidad" value="0"/>

                                                <span id='cantidad-error'></span>

                                            </td>

                                            <td><input type="text" name="precio" style="text-align:right" id="precio" class="psi" value="0" class="dataMoneda"  />

                                                <span id='precio-error'></span>

                                            </td>

                                            <td><input type="text" name="descuento" id="descuento" class="discount" value="0"/>

                                                <span id='descuento-error'></span>

                                            </td>

                                            <td><?php echo form_dropdown('id_impuesto', $data['impuestos'], "", "id='impuestos' class='impuesto'");?></td>

                                            <td id="total_row" class="ptotal" style="text-align:right">&nbsp;</td>

                                            <td><button style="border: 0;" type='button'  class='button blue add'><div class='icon'><span class='ico-plus'></span></div></button></td>

                                     

                                        </tr>

                                    </tbody>

                                    <tfoot>

                                        <tr>

                                            <th colspan="2"></th>

                                            <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA");?></b></th>

                                            <th style="text-align:right"><b class="total_siva">0.00</b></th>

                                            <th colspan="5"></th>

                                       

                                        </tr>

                                        <tr>

                                            <th colspan="2"></th>

                                            <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA");?></b></th>

                                            <th style="text-align:right"><b class="iva">0.00</b></th>

                                            <th colspan="5"></th>

                                          

                                        </tr>

                                        <tr>

                                            <th colspan="2"></th>

                                            <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA");?></b></th>

                                            <th style="text-align:right"><b class="total_civa">0.00</b>

                                                <?php echo form_error('input_total_civa'); ?>

                                            </th>

                                            <th colspan="5"></th>

                                            

                                        </tr>

                                    <tfoot>

                                </table>

                            

                                <br/>

                                <div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn" type="button" id="enviar_cotizacion"><?php echo custom_lang("sima_submit", "Guardar");?></button>

                        <button class="btn btn-warning"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                    </div>

                                </div>

                            </div>

                            <input type="hidden" name="input_iva" id="input_iva" />

                            <input type="hidden" name="input_total_civa" id="input_total_civa" />

                            <input type="hidden" name="input_total_siva" id="input_total_siva" />

    </div>

</div>

<div id="dialog-client-form" title="<?php echo custom_lang('sima_new_client', "Adicionar Cliente");?>">

    <div class="span6">

        <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

        <form id="client-form">

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>

                    <div class="span3"><input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/>

                        <span class="error_cliente"></span>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>

                    <div class="span3"><input type="text" name="email" id="email" class="validate[custom[email]]"/>

                        <span class="error_email"></span>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>

                    <div class="span3"><input type="text" name="razon_social" id="razon_social" />

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>

                    <div class="span3"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]"/>

                        <span class="error_nif_cif"></span>

                    </div>

                </div>

        </form>

    </div>

</div>


<!-- Modal -->
<div id="producto-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Nuevo producto (rapido)</h3>
  </div>
  <div class="modal-body">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open_multipart("productos/nuevo_rapido", array("id" =>"ajaxform"));?>

                <div class="row-form">

                    <div class="span1"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?>:</div>

                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                    <div class="span1"><input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />

                            <?php echo form_error('codigo'); ?>

                    </div>

                    <div class="span3"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />

                            <?php echo form_error('nombre'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('price_of_purchase', "Precio de compra");?>:</div>

                    <div class="span2"><?php echo custom_lang('sale_price', "Precio de venta");?>:</div>

                    <div class="span2"><input type="text" value="<?php echo set_value('precio_compra'); ?>" name="precio_compra" placeholder=""/>

                        <?php echo form_error('precio_compra'); ?>

                    </div>
                   
                    <div class="span2"><input type="text" value="<?php echo set_value('precio'); ?>" name="precio" placeholder=""  class="dataMoneda" />

                        <?php echo form_error('precio'); ?>

                    </div>

                </div>

                <div class="row-form">

                     <div class="span3"><?php echo custom_lang('sima_category', "Categoria");?>:</div>

                    <div class="span4">

                        <select name='categoria_id'>
                            <?php 
                                foreach ($data['categorias'] as $key => $value) {
                                    echo "<option value='".$value->id."'>".($value->nombre)."</option>";
                                }
                             ?>
                        </select>
                        <?php echo form_error('categoria_id'); ?>

                    </div>

                    <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>

                    <div class="span4">

                            <?php echo form_dropdown('id_impuesto', $data['impuestos_productos'], $this->form_validation->set_value('id_impuesto'));?>

                            <?php echo form_error('id_impuesto'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>

                    <div class="span4"><textarea name="descripcion" placeholder=""><?php echo set_value('descripcion'); ?></textarea>

                        <?php echo form_error('descripcion'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen");?>:<br/>

                    </div>

                    <div class="span4">                            

                        <div class="input-append file">

                            <input type="file" name="imagen"/>

                            <input type="text" style="width: 315px!important;"/>

                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>



                        </div> 

                        <?php echo $data['data']['upload_error']; ?>

                    </div>

                </div>

                <div>

                    <table class="table">

                        <thead>

                            <tr>

                                <th width="50%">Almacen</th>

                                <th width="50%">Cantidad</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($data['almacenes'] as $key => $value) :?>

                                <tr>

                                   <td><?php echo $value;?></td><td><input name="Stock[<?php echo $key;?>]" min="0" type="number" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>

                               </tr>

                           <?php endforeach;?>

                        </tbody>

                        

                    </table>

                </div>

                <div class="toolbar bottom tar">

                    <div class="btn-group">

                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

                        <button class="btn btn-warning"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                    </div>

                </div>

                </form>
            </div>

        </div><!--cierra bloque-->
  </div>
</div>


<script type="text/javascript">

    //$("#precio").maskMoney({thousands:',', decimal:',', allowZero:true});

        //callback handler for form submit
    $("#ajaxform").submit(function(e)
    {

        $('#mensaje-producto').css('display','none');
        
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
      
        $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data, textStatus, jqXHR) 
            {
                $('#mensaje-producto').css('display','block');

                if(data.success){
                    $('#mensaje-contenedor').html(' <div class="alert alert-success" id="mensaje-producto">Producto creado con exito.</div>');
                }else{
                    $('#mensaje-contenedor').html('<div class="alert alert-error" id="mensaje-producto">No se creo el producto, vuelva a intentarlo.</div>');
                }

                $('#producto-modal').modal('hide')
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                //if fails      
            }
        });
        e.preventDefault(); //STOP default action
        e.unbind(); //unbind. to stop multiple form submit.
    });
     
    /*$("#ajaxform").submit(); //Submit  the FORM*/

    $(document).ready(function(){

        $("#datos_cliente").autocomplete({

			source: "<?php echo site_url("clientes/get_ajax_clientes"); ?>",

			minLength: 1,

			select: function( event, ui ) {

                $("#id_cliente").val(ui.item.id);

				$("#otros_datos").val(ui.item.descripcion);

			}

		});

        $( "#fecha, #fecha_v" ).datepicker({

             dateFormat: 'yy/mm/dd'

        });

        // funcion autocompletado en el campo con id #nombre-producto

        $('#nombre-producto').autocomplete({

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

                                                value: item.nombre+' ('+item.codigo+') ',

                                                id: item.id,

                                                codigo: item.codigo,

                                                precio: item.precio,

                                                porciento: item.porciento,

                                                descripcion: item.descripcion,

                                                precio_venta: item.precio_venta




                                        }

                                }));

                            }

                    });

            },

            minLength: 1,

            select: function( event, ui ) {

                $("#precio").val(ui.item.precio_venta);

                $("#product-service").val(ui.item.id);

                $("#impuestos").val(ui.item.porciento);

                $("#descripcion").val(ui.item.descripcion);

                $("#id_producto").val(ui.item.id);

                $("#codigo").val(ui.item.codigo);

                $("#cantidad").val(1);

                //  calculate_row();

               calculate();

            }

        }).on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (code == 9) {
               $('#nombre-producto').autocomplete("search", $('#nombre-producto').val());
                e.preventDefault();
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

                var html = '<tr>'+
                                '<td width="10"><input type="hidden" name="product_id[]" id="product_id" class="product_id"  value="'+$('#id_producto').val()+'"/></td>'+
                                '<td><input type="hidden" name="product_name[]" class="product-service" value="'+$("#product-service").val()+'" id="product_name"/>'+$("#nombre-producto").val()+'</td>'+
                                '<td><textarea name="description[]" class="description">'+$("#descripcion").val()+'</textarea></td>'+
                                '<td><input type="text" name="quantity[]" class="quantity" value="'+$("#cantidad").val()+'" id="cantidad"/></td>'+
                                '<td><input style="text-align:right" value="'+$("#precio").val()+'" type="text" class="psi" name="psi[]"/></td>'+
                                '<td><input type="text" name="discount[]" class="discount" id="discount" value="'+$("#descuento").val()+'"/></td>'+
                                '<td><input type="hidden" name="impuesto[]" class="impuesto" id="'+$("#cantidad").val()+'_'+$("#product-service").val()+'" value="'+$("#impuestos").val()+'"><input type="text"  value="'+$("#impuestos option:selected").html()+'" readonly="readonly"></td>'+
                                '<td style="text-align:right" class="ptotal">'+ '' +'</td>'+
                                '<td><a  class="button red delete" title="Eliminar" href="#"><div class="icon"><span class="ico-remove"></span></div></td></td>'+
                            '</tr>';



                $("#detalle").append(html);
                

                $("#"+$("#cantidad").val()+"_"+$("#product-service").val()).val($("#impuestos").val());


                $('#product-service').val("");
				
				$('#nombre-producto').val("");

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

        functioncalculate(){

            var suma=0, iva = 0;

            $(".psi").each(function(x){

                psi = ($(".psi").eq(x).maskMoney('unmasked'));

                quantity = ($(".quantity").eq(x).val());

                discount = ($(".discount").eq(x).val());

                impuesto = ($(".impuesto").eq(x).val());
               
                cantidad = psi * quantity;

                impuesto_row =  cantidad * (impuesto / 100);

                //TOTAL FILA
                suma_row = cantidad +  impuesto_row; //Precio + impuesto
                suma_row = suma_row - ( suma_row * discount / 100);//Precion con impuesto - (descuento con impuesto)

                //TOTALES
                suma += cantidad - ( cantidad * discount / 100) ; //Precio - descuento sin impuestos
                iva += impuesto_row - ( impuesto_row * discount / 100);

                $(".ptotal").eq(x).text(suma_row);

            });

        
            $(".total_siva").html((suma));

            $(".iva").html((iva));

            $(".total_civa").html((suma + iva));

            $("#input_total_civa").val((suma + iva));

            $("#input_total_siva").val((suma));

            $("#input_iva").val((iva)));

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

         // calculate();

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

                if($("#detalle tr").length == 0){

                        $("#detalle").html('<tr class="nothing"><td style="text-align:center" colspan="6">Sin detalle</td></tr>');

                }

               // calculate_row();

               calculate();

        }));

        

        $("#add-new-client").click(function(){

            $( "#dialog-client-form" ).dialog( "open" );

        });

        

        $("#enviar_cotizacion").click(function () { 

      

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
			
			
			  document.getElementById("enviar_cotizacion").disabled = true;

                    productos_list = new Array();

                    $(".psi").each(function(x){

                        productos_list[x] = {

                            'fk_id_producto': $('.product_id').eq(x).val() 

                            , 'precio': ($(".psi").eq(x).maskMoney('unmasked'))

                            , 'cantidad': ($(".quantity").eq(x).val())

                            , 'impuesto': ($(".impuesto").eq(x).val())

                            , 'descuento': ($(".discount").eq(x).val())

                            , 'descripcion': $(".description").eq(x).val()

                        }

                    });

                    

                    

                    $.ajax({

                        url: "<?php echo site_url("presupuestos/nuevo");?>"

                        ,dataType: 'json'

                        ,type: 'POST'

                        ,data: {

                            id_cliente: $("#id_cliente").val()

                            ,numero: $("input[name='numero']").val()

                            ,fecha: $("#fecha").val()

                            //,fecha_v: $("#fecha_v").val()

                            ,input_total_civa: $("#input_total_civa").val()

                            ,monto_siva: $("#input_total_siva").val()

                            ,monto_iva: $("#input_iva").val()

                            ,productos: productos_list

                        }

                        ,error: function(jqXHR, textStatus, errorThrown ){

                            alert(errorThrown);

                        }

                        ,success: function(data){

                            if(data.success == true){

                                location.href = "<?php echo site_url("presupuestos/index");?>";

                            }

                            else{

                                alert("Presupuesto no creado");

                            }

                        }

                    });

            }

        });

    });

</script>

<style type="text/css">
.ui-dialog{
    z-index: 9000!important;
}
#cargar_productos{margin-bottom: 10px;}
.alert-orden-compra{background: #e9e9e9; color: #666;}
.row-fluid [class*="span"]{margin-left:0px;}
.max{color: red;}
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_bill', "Nueva Orden de Compra");?></h2> 
    </div>
</div>

<div class="row-fluid">

    <div class="block">

        

            <div class="data-fluid">

                <?php  $is_admin = $this->session->userdata('is_admin');
				      echo form_open_multipart("facturas/nuevo", array("id" =>"validate"));?>

                

                    <div class="row-form">

                        <div class="span3"><?php echo custom_lang('sima_invoice_number', "Almacén");?>:</div>

                        <div class="span9"> <?php //echo form_dropdown('almacen', array_merge(array('0' => 'Seleccione un almacen'), $data['almacen']), '', "id='almacen'" ); ?>
							 <?php
							  if($is_admin == 's'){
								echo $data['almacen_nombre']; 
								  ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen" id="almacen"/><?php
								 } 
								 else{
									  echo form_dropdown('almacen', $data['almacen'], set_value('almacen', '0'), "id='almacen'" );
								  }
							  ?>						
						
                        </div>

                    </div>

                    <div class="row-form">

                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha del pedido");?>:</div>

                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>

                                <?php echo form_error('fecha'); ?>

                        </div>

                    </div>

                    <div class="row-form">

                        <div class="span3"><?php echo custom_lang('sima_date_v', "Fecha de pago");?>:</div>



                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_v" id="fecha_v"/>

                                <?php echo form_error('fecha_v'); ?>

                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer', "Proveedor");?>:</div>

                                        <div class="span9">

                                                    <div class="input-append"> 

                                                    <input type="text"  value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente" id="datos_cliente"/>

                                                    <span class="add-on green" id="add-new-client"><img alt="Cliente" src="<?php echo $this->session->userdata('new_imagenes')['cliente_blanco']['original'] ?>" /></span>

                                               </div>

                                                <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo set_value('id_cliente'); ?>" />

                                                <?php echo form_error('id_cliente'); ?>

                                                <?php echo form_error('datos_cliente'); ?>

                                        </div>

                                    </div>

                                      <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer', "Vendedor");?>:</div>

                                        <div class="span9">

                                                    <div class="input-append"> 

                                                    <input type="text"  value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor" id="datos_vendedor"/>

                                                    <span class="add-on green" id="add-new-vendedor"><img alt="Cliente" src="<?php echo $this->session->userdata('new_imagenes')['vendedor_blanco']['original'] ?>" /></span>

                                               </div>

                                                <input type="hidden" name="id_vendedor" id="id_vendedor" value="<?php echo set_value('id_vendedor'); ?>" />

                                                <?php echo form_error('id_vendedor'); ?>

                                                <?php echo form_error('datos_vendedor'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer_data', "Datos del Proveedor");?>:</div>

                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="Datos del Proveedor" readonly="readonly"><?php echo set_value('otros_datos'); ?></textarea>

                                        </div>

                                    </div>
               
                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_customer_data', "Nota");?>:</div>

                                        <div class="span9">
                                            <textarea name="nota" id="nota"><?php echo set_value('nota'); ?></textarea>
                                        </div>

                                    </div>

                                    <div class="row-form">
                                            <div class="span12">
                                                <h4>Importar productos por Excel</h4>
                                                <p>1. De click en el siguiente enlace para descargar la plantilla de excel <a href="<?= base_url().'uploads/PlantillaOrdenCompra.xlsx'?>"> CLICK AQUI </a>  llamada Plantilla Orden Compra.</p>
                                                <p>2. Seleccione el archivo con la informacion de los productos</p>
                                            </div>

                                            <div class="span6">                            
                                                <div class="input-append file">
                                                    <input type="file" id="file_productos" name="archivo" style="width: 320px;">
                                                    <input type="text" style="width: 333px;">
                                                    <button class="btn btn-success" type="button">Buscar</button>
                                                </div> 
                                            </div>
                                            <br><br>
                                            <div class="span12">
                                                <p>3. Click en el botón cargar productos <span class="max">(MÁXIMO 500 PRODUCTOS)</span></p>
                                                <button id="cargar_productos" class="btn btn-default">Cargar Productos</button>
                                            </div>
                                            
                                            <div class="span12 alert alert-warning alert-dismissible fade in alert-orden-compra" role="alert"> 
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span></button> 
                                                <h4>A tener en cuenta</h4> 
                                                <p>En el campo de unidades debe escribir alguna de estas opciones: unidad - gramo - kilogramo - libra - litro - mililitro - onza</p>
                                                <p>En el campo impuesto debe poner el valor del impuesto. Ejemplos: IVA 16% corresponde a 16 - IMPOCONSUMO corresponde a 8 - Sin Impuesto corresponde a 0 </p>
                                          </div>

                                    </div> <!-- End Row

                                    <div class="toolbar bottom tar">

                                        <div class="btn-group">

                                         <!--   <a href="#" id="add-product-service" class="btn"><?php echo custom_lang('sima_add_product_service', "A&ntilde;adir producto o servicio");?></a>-->

                                        </div>

                                    </div>

                                

                                    <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">

                                    <thead>

                                        <tr>

                                            <th width="1%"></th>

                                            <th width="20%"><?php echo custom_lang('sima_product_name', "Nombre del producto");?></th>
																						
                                            <th width="10%"><?php echo custom_lang('sima_asa', "Unidades");?></th>											

                                            <th width="5%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>

                                            <th width="10%" style="text-align:right" width="10%"><?php echo custom_lang('sima_price', "Precio Unitario");?></th>
											
											<th width="10%"><?php echo custom_lang('sima_discount', "Utilidad");?></th>

                                            <th width="10%"><?php echo custom_lang('sima_discount', "Descuento(%)");?></th>
											
                                            <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>

                                            <th width="10%" style="text-align:center" width="10%"><?php echo custom_lang('sima_price', "Precio Venta sin Impuesto");?></th>

                                            <th style="text-align:right" width="10%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>

                                            <th class="TAC" style="text-align:center" width="4%"><?php echo custom_lang('sima_option', "Acciones");?></th>

                                        </tr>

                                    </thead>

                                    <tbody id="detalle">

                                        <tr>
                                            <td width="1%">
                                                <input type="hidden" name="id_producto" class="product_id"  id="id_producto" />
                                                <input type="hidden" name="codigo" class="codigo"  id="codigo" />
                                            </td>

                                            <td width="20%">                                                
                                                <input type="hidden" name="product-service" id="product-service" class='product-service'/>
                                                <input type="text" name="nombre-producto" id="nombre-producto" class='nombre-producto'/>
                                                <span id='product-service-error'></span>
                                            </td>

                                            <td width="10%">
                                                <?php echo form_dropdown('unidades', $data['unidades'], "", "id='unidades' class='unidades'");?>
                                            </td>
                                            <td width="5%">
                                                <input type="text" name="cantidad" class="quantity" id="cantidad" value="0" />
                                                <span id='cantidad-error'></span>
                                            </td>

                                            <td width="10%">
                                                <input type="text" name="precio" style="text-align:right" id="precio" class="psi" value="0"/>
                                                <span id='precio-error'></span>
                                            </td>

                                            <td width="10%">
                                                <input type="text" name="utilidad" style="text-align:right" id="utilidad" class="utilidad" readonly="readonly" value="0"/>
                                                <input type="hidden" name="precioventareal" style="text-align:right" id="precioventareal" class="precioventareal" value="0"/>
                                                <span id='utilidad-error'></span>
                                            </td>

                                            <td width="10%">
                                                <input type="text" name="descuento" id="descuento" class="discount" value="0"/>
                                                <span id='descuento-error'></span>
                                            </td>
                                            
							                <td width="10%">
                                                <?php echo form_dropdown('id_impuesto', $data['impuestos'], "", "id='impuestos' class='impuesto'");?>
                                            </td>
                                            
                                            <td width="10%" data-tooltip="Si modifica el precio de venta, al afectar el inventario, el producto quedará con el valor colocado">
                                                <input type="text" name="precio_venta2" style="text-align:right" id="precio_venta2" class="psv" value="0"/>
                                                <span id='precio_venta2-error'></span>
                                            </td>
                                            
                                            <td width="10%" id="total_row" class="ptotal" style="text-align:right">&nbsp;</td>

                                            <td width="4%">                                                
                                                <a class='button green add' data-tooltip="Agregar">                        
                                                    <img alt="Orden de Compra" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_blanco']['original'] ?>"> 
                                                </a>    
                                            </td> 
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA");?></b></th>
                                            <th style="text-align:right"><b class="total_siva">0.00</b></th>
                                            <th colspan="7"></th> 
                                        </tr>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA");?></b></th>
                                            <th style="text-align:right"><b class="iva">0.00</b></th>
                                            <th colspan="7"></th>    
                                        </tr>

                                        <tr>
                                            <th colspan="2"></th>
                                            <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA");?></b></th>
                                            <th style="text-align:right"><b class="total_civa">0.00</b>
                                                <?php echo form_error('input_total_civa'); ?>
                                            </th>
                                            <th colspan="7"></th>
                                        </tr>
                                    <tfoot>
                                </table>
                                <br/>

                                <div class="toolbar bottom tar">      
                                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    <button class="btn btn-success" type="button"   onclick="this.disabled= true;"  id="enviar_factura"><?php echo custom_lang("sima_submit", "Guardar");?></button>                                    
                                </div>
                            </div>

                            <input type="hidden" name="input_iva" id="input_iva" />
                            <input type="hidden" name="input_total_civa" id="input_total_civa" />
                            <input type="hidden" name="input_total_siva" id="input_total_siva" />

    </div>

</div>

<div id="dialog-client-form" title="<?php echo custom_lang('sima_new_client', "Adicionar Proveedor");?>">

                                <div class="span6">

                                    <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                                    <form id="client-form">

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>

                                                <div class="span3">

                                                    <input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/>

                                                    <span class="error_cliente"></span>

                                                </div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>

                                                <div class="span3">
                                                    <input type="text" name="email" id="email" class="validate[custom[email]]"/>

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

<script type="text/javascript">

    $(document).ready(function(){

        $("#datos_cliente").autocomplete({

			source: "<?php echo site_url("proveedores/get_ajax_proveedores"); ?>",

			minLength: 1,

			select: function( event, ui ) {

                $("#id_cliente").val(ui.item.id);
				$("#otros_datos").val(ui.item.descripcion);

			}

		});

        $("#datos_vendedor").autocomplete({

            source: "<?php echo site_url("vendedores/get_ajax_vendedores"); ?>",

            minLength: 1,

            select: function( event, ui ) {

                console.log(ui);
//
                $("#id_vendedor").val(ui.item.id);
                $("#otros_datos").val(ui.item.descripcion);

            }

        });

        $( "#fecha, #fecha_v" ).datepicker({

             dateFormat: 'yy/mm/dd'

        });

        

        $('#nombre-producto').autocomplete({

            source: function( request, response ) {

                    $.ajax({

                            url: "<?php echo site_url("productosf/filtro_prod_orden_compra"); ?>",

                            type:"GET",

                            dataType: "json",

                            data: {

                                    //type: $("input[name='tipo']:checked").val(),

                                    term: request.term

                            },

                            success: function(data) {
                                //console.log(data);


                                response( $.map( data, function( item ) {

                                        return {

                                                value: item.nombre+' ('+item.codigo+') ',
												
                                                nombre: item.nombre,

                                                id: item.id,

                                                codigo: item.codigo,

                                                precio: item.precio,

                                                porciento: item.porciento,

                                                //impuesto: item.impuesto,
                                                impuesto: item.porciento,

                                                descripcion: item.descripcion,

                                                precio_venta: item.precio_compra,

                                                precio_venta2: item.precio_venta,
												
                                                precio_venta_real: item.precio_venta,

                                                unidades: item.unidad_id



                                        }

                                }));

                            }

                    });

            },

            minLength: 1,

            select: function( event, ui ) {

                $("#precio").val(ui.item.precio_venta);

                $("#product-service").val(ui.item.nombre);

               // $("#impuestos").val(ui.item.porciento);
                $("#impuestos").val(ui.item.impuesto);
                

                $("#descripcion").val(ui.item.descripcion);

                $("#id_producto").val(ui.item.id);

                $("#utilidad").val();

                $("#precioventareal").val(ui.item.precio_venta_real);

                $("#precio_venta2").val(ui.item.precio_venta2);

                $("#codigo").val(ui.item.codigo);

                $("#cantidad").val(1);

                $("#unidades").val(ui.item.unidades);
                //  calculate_row();

                calculate();

            }

        }).on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (code == 9) {
               $('#nombre-producto').autocomplete("search", $('#nombre-producto').val());
                e.preventDefault();
            }
        });;

        

        $(".add").click(function(e){

            e.preventDefault();

            var flag_errors = false;

            if($("#id_producto").val() == ""){
                $("#product-service-error").text("Producto no v&aacute;lido");
                flag_errors = true;
            }
            if($("#almacen").val() == "0"){
                $("#product-service-error").text("Seleccione un almacén");
                flag_errors = true;
            }

            if($("#product-service").val() == ""){
                $("#product-service-error").text("Seleccione un producto");
                flag_errors = true;
            }else{
                $("#product-service-error").empty();
            }  

            if($("#cantidad").val() == ""){
                $("#cantidad-error").text("Cantidad requerida");
                flag_errors = true;
            }else if(isNaN($("#cantidad").val())){
                $("#cantidad-error").text("Inserte un número");
                flag_errors = true;
            }else{
                if($("#cantidad").val()<=0){
                    $("#cantidad-error").text("Cantidad debe ser mayor a 0");
                    flag_errors = true;  
                }else{
                    $("#cantidad-error").empty();
                }
            }            

            if($("#descuento").val() == ""){
                $("#descuento-error").text("Descuento requerido");
                flag_errors = true;
            } else if(isNaN($("#descuento").val())){
                $("#descuento-error").text("Inserte un número");
                flag_errors = true;
            }
            else{
                if($("#descuento").val()<0){
                    $("#descuento-error").text("Descuento debe ser mayor o igual a 0");
                    flag_errors = true;  
                }else{
                    $("#descuento-error").empty();
                }
            }

            if($("#precio").val() == ""){
                $("#precio-error").text("Precio requerido");
                flag_errors = true;
            } else {
                if(isNaN($("#precio").val())){
                    $("#precio-error").text("Inserte un número");
                    flag_errors = true;
                }
                else{
                    if($("#precio").val()<0){
                        $("#precio-error").text("Precio debe ser mayor o igual a 0");
                        flag_errors = true;  
                    }else{
                        $("#precio-error").empty();
                    }
                }
            }

            if($("#precio_venta2").val() == ""){
                $("#precio_venta2-error").text("precio venta requerido");
                flag_errors = true;
            } else {
                if(isNaN($("#precio_venta2").val())){
                    $("#precio_venta2-error").text("Inserte un número");
                    flag_errors = true;
                }
                else{
                    if($("#precio_venta2").val()<0){
                        $("#precio_venta2-error").text("Precio venta debe ser mayor o igual a 0");
                        flag_errors = true;  
                    }else{
                        $("#precio_venta2-error").empty();
                    }
                }
            }

            if(!flag_errors){
                $tr = $(this).parent().parent().find('tr');
                uni=$('#unidades').val();  
                unid="";
                $("#unidades option").each(function(){                    
                    if(uni==$(this).attr('value')){
                        unid=$(this).text();
                    }
                });
                
                var html = '<tr id="last"><td width="10"><input type="hidden" name="product_id[]" id="product_id" class="product_id"  value="'+$('#id_producto').val()+'"/><input type="hidden" name="codigo" class="codigo" id="codigo" value="'+$('#codigo').val()+'"></td><td><input type="hidden" name="product_name[]" class="product-service" value="'+$("#product-service").val()+'" id="product_name"/>'+$("#product-service").val()+'</td><td><input type="text"  disabled name="unidades[]" id="unidades" class="unidades"  value="'+unid+'"/></td><td><input type="text" name="quantity[]" class="quantity" value="'+$("#cantidad").val()+'" id="cantidad"/></td><td><input style="text-align:right" value="'+$("#precio").val()+'" type="text" class="psi" name="psi[]"/></td><td><input style="text-align:right" value="'+$("#utilidad").val()+'" type="text" class="utilidad" name="utilidad"  readonly="readonly" /><input type="hidden" name="precioventareal"  value="'+$("#precioventareal").val()+'" id="precioventareal" class="precioventareal" value="0"/></td><td><input type="text" name="discount[]" class="discount" id="discount" value="'+$("#descuento").val()+'"/></td><td><input type="hidden" name="impuesto[]" class="impuesto" id="'+$("#cantidad").val()+'_'+$("#product-service").val()+'" value="'+$("#impuestos").val()+'"><input type="text"  value="'+$("#impuestos option:selected").html()+'" readonly="readonly"></td><td><input style="text-align:right" value="'+$("#precio_venta2").val()+'" type="text" class="psv" name="psv[]"/></td><td style="text-align:right" class="ptotal">'+ '' +'</td><td><a  class="button red delete acciones" title="Eliminar" href="#"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></td></td></tr>';

               
                $("#detalle").append(html);

                $('#product-service').val("");
				
				$('#nombre-producto').val("");

                $('#precio').val(0.0);
				
				$('#utilidad').val(0);
				
				$('#precioventareal').val(0);

                $('#cantidad').val(0);

                $('#descuento').val(0);

                $("#impuestos").val(0);

                $("#descripcion").val("");
                $('#precio_venta2').val(0.0);

                $("#total_row").empty(0); 


                calculate();

                //$select.selectedIndex=1;

            }else{                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Existen errores en el formulario, verifique los campos",
                    showConfirmButton: false,
                    timer: 1500
                })
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

                                                    url: '<?php echo site_url('proveedores/add_ajax_provider');?>',

                                                    data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val()},

                                                    dataType: 'json',

                                                    type: 'POST',

                                                    success: function(data){

                                                        $("#id_cliente").val(data.id_proveedor);

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

 
       $('.impuesto').change(function(){

           //calculate_row();

           calculate();

       });
        

        $("#add-product-service").click(function(){

            $( "#dialog-form" ).dialog( "open" );

        });

        /* Enviar excel por ajax para carga de productos*/
        $("#cargar_productos").click(function(e){
            e.preventDefault();
            $("#cargar_productos").prop('disabled',true);
            var archivo = $("#file_productos").val();
            var extensiones = archivo.substring(archivo.lastIndexOf("."));
            if(archivo == "") {  
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Debe seleccionar un archivo",
                    showConfirmButton: true                                   
                })
                $("#cargar_productos").prop('disabled',false);                
            }else{
                if(extensiones != ".xls" && extensiones != ".xlsx") {            
                    swal({
                        position: 'center',
                        type: 'error',
                        title: "Error",
                        html: "El archivo no es válido, debe ser tipo excel .xls o .xlsx",
                        showConfirmButton: true                                   
                    })
                    $("#cargar_productos").prop('disabled',false);
                }
                else{
                    var form = $("#validate")[0];
                    var url = "<?php echo site_url('orden_compra/cargar_productos');?>";
                    var formData = new FormData(form);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        contentType: false, 
                        processData: false, 
                        success: function (data) {
                            console.log(data);
                            
                            if(data == 0){                            
                                swal({
                                    position: 'center',
                                    type: 'error',
                                    title: "Error",
                                    html: "Error del servidor",
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $("#cargar_productos").prop('disabled',false);
                            }else if(data == 2){                            
                                swal({
                                    position: 'center',
                                    type: 'error',
                                    title: "Error",
                                    html: "Cantidad de datos excedidos",
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $("#cargar_productos").prop('disabled',false);
                            }else{                                  
                                if(data==4){
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: "Error",
                                        html: "El archivo cargado no tiene la misma estructura que la plantilla",
                                        showConfirmButton: true
                                    })
                                    $("#cargar_productos").prop('disabled',false);
                                }else{
                                    if(data==3){
                                        var urlarchivoerrores = "<?php echo base_url('uploads/'.$this->session->userdata('base_dato')."/archivos_productos/ordendecompranoguardado.xlsx"); ?>";
                                        swal({
                                            position: 'center',
                                            type: 'error',
                                            title: "Error",
                                            html: 'La plantilla no se pudo procesar porque algunos campos presentan errores, por favor descargue el archivo <a class="link_session" href="'+urlarchivoerrores+'">Aqui</a> solucionelos e intente nuevamente.',
                                            showConfirmButton: true
                                        })  
                                        $("#cargar_productos").prop('disabled',false);
                                    }else{
                                        if(data==5){
                                            swal({
                                                position: 'center',
                                                type: 'error',
                                                title: "Error",
                                                html: 'La plantilla debe contener por lo menos un registro.',
                                                showConfirmButton: true
                                            })  
                                            $("#cargar_productos").prop('disabled',false);
                                            
                                        }else{
                                            var productos = JSON.parse(data);  
                                            var html = "";
                                            var error="";
                                            var errores_columnas="";
                                            
                                            $.each(productos,function(index,element){
                                                
                                                        html += '<tr id="last"><td width="10">';
                                                        html += '<input type="hidden" name="product_id[]" id="product_id" class="product_id"  value="'+element.id_producto+'"/>';
                                                        html += '<input type="hidden" name="codigo" class="codigo" id="codigo" value="'+element.codigo_producto+'"></td>';
                                                        html += '<td><input type="hidden" name="product_name[]" class="product-service" value="'+element.nombre_producto+'" id="product_name"/>'+element.nombre_producto+'</td>'
                                                        html += '<td><input type="text"  disabled name="unidades[]" id="unidades" class="unidades"  value="'+element.unidades+'"/></td>'; 
                                                        html += '<td><input type="text" name="quantity[]" class="quantity" value="'+element.cantidad+'" id="cantidad"/></td>';
                                                        html += '<td><input style="text-align:right" value="'+element.precio_unitario+'" type="text" class="psi" name="psi[]"/></td>';  
                                                        html += '<td><input style="text-align:right" value="'+element.utilidad+'" type="text" class="utilidad" name="utilidad"  readonly="readonly" /><input type="hidden" name="precioventareal"  value="'+element.precio_venta_real+'" id="precioventareal" class="precioventareal" value="0"/></td>';  
                                                        html += '<td><input type="text" name="discount[]" class="discount" id="discount" value="'+element.descuento+'"/></td>';  
                                                        html += '<td><input type="hidden" name="impuesto[]" class="impuesto" id="'+element.id_impuesto+'" value="'+element.impuesto+'"><input type="text"  value="'+element.nombre_impuesto+'" readonly="readonly"></td>';  
                                                        html += '<td><input style="text-align:right" value="'+element.precio_venta_real+'" type="text" class="psv" name="psv[]"/></td>';  
                                                        html += '<td style="text-align:right" class="ptotal">'+ '' +'</td>';
                                                        html += '<td><a  class="button red delete" title="Eliminar" href="#"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></td>';
                                                        html += '</tr>';  
                                                
                                            })
                                            $("#detalle").append(html);
                                            
                                            $('#product-service').val("");
                                            $('#nombre-producto').val("");
                                            $('#precio').val(0.0);
                                            $('#utilidad').val(0);
                                            $('#precioventareal').val(0);
                                            $('#cantidad').val(0);
                                            $('#descuento').val(0);
                                            $("#impuestos").val(0);
                                            $('#precio_venta2').val(0.0);
                                            $("#descripcion").val("");
                                            $("#total_row").empty(0); 
                                            calculate();
                                        }
                                    }
                                }
                            }
                        },
                        error: function (r) {                       
                            swal({
                                position: 'center',
                                type: 'error',
                                title: "Error",
                                html: "Error del servidor",
                                showConfirmButton: true                                   
                            })
                            $("#cargar_productos").prop('disabled',false);
                        }
                    });
                }
            }
        })
      
        

        function calculate(){

            var suma=0, iva = 0;

            $(".psi").each(function(x){

                psi = parseFloat($(".psi").eq(x).val());

                quantity = parseFloat($(".quantity").eq(x).val());

                discount = parseFloat($(".discount").eq(x).val());

                impuesto = parseFloat($(".impuesto").eq(x).val());
				
				precioventareal = parseFloat($(".precioventareal").eq(x).val());
               
                cantidad = psi * quantity;

                impuesto_row =  cantidad * (impuesto / 100);

                //TOTAL FILA
                suma_row = cantidad +  impuesto_row; //Precio + impuesto
                suma_row = suma_row - ( suma_row * discount / 100);//Precion con impuesto - (descuento con impuesto)

                //TOTALES
                suma += cantidad - ( cantidad * discount / 100) ; //Precio - descuento sin impuestos
                iva += impuesto_row - ( impuesto_row * discount / 100);
				
				uti = precioventareal - psi;

                $(".ptotal").eq(x).text((suma_row).toFixed(2));
				
                $(".utilidad").eq(x).val((uti).toFixed(2));
            });

        
            $(".total_siva").html((suma).toFixed(2));

            $(".iva").html((iva).toFixed(2));

            $(".total_civa").html((suma + iva).toFixed(2));

            $("#input_total_civa").val((suma + iva).toFixed(2));

            $("#input_total_siva").val((suma).toFixed(2));

            $("#input_iva").val((iva).toFixed(2));

        }

        

        $(".psi, .quantity, .discount, .impuesto1").live("keyup",(function(){

                calculate();

        }));
        
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

        

    

        $("#enviar_factura").click(function () { 
            $("#enviar_factura").prop('disabled',true);
            productos_list = new Array();

            $(".psi").each(function(x){

                productos_list[x] = {

                      'product_id': $('.product_id').eq(x).val() 

                    , 'codigo': $(".codigo").eq(x).val()

                    , 'nombre_producto': $('.product-service').eq(x).val() 

                    , 'precio_venta': parseFloat($(".psi").eq(x).val())

                    , 'unidades': parseFloat($(".quantity").eq(x).val())

                    , 'impuesto': parseInt($(".impuesto").eq(x).val())

                    , 'descuento': parseFloat($(".discount").eq(x).val())

                    , 'id_unidades': $(".unidades").eq(x).val()

                    , 'precio_venta2': parseFloat($(".psv").eq(x).val())
					
                    , 'margen_utilidad':0

                }

            });

           var cliente = {};

                cliente = $('#id_cliente').val();

           
            if($('#id_vendedor').val() == '')
                id_vendedor = 0;
            else
                id_vendedor = $('#id_vendedor').val();
            
            
            if(productos_list.length >1){
                $.ajax({

                    url: "<?php echo site_url("orden_compra/nuevo");?>"
                   
                    ,type: 'POST'

                    ,data: {

                        tipo:'clasico',

                        vendedor:id_vendedor,

                        id_cliente: $("#id_cliente").val()

                        ,cliente : cliente

                        ,almacen: $("#almacen").val()

                        ,fecha: $("#fecha").val()

                        ,fecha_v: $("#fecha_v").val()

                        ,total_venta:  $("#input_total_civa").val()

                        ,input_total_civa: $("#input_total_civa").val()

                        ,monto_siva: $("#input_total_siva").val()

                        ,monto_iva: $("#input_iva").val()
                        ,nota : $("#nota").val()

                        ,productos: productos_list

                    }

                    ,error: function(jqXHR, textStatus, errorThrown ){
                        location.href = "<?php echo site_url("orden_compra/index");?>/";
                    }
                    ,success: function(data){
                        if(data.id != null){
                            location.href = "<?php echo site_url("orden_compra/index");?>/"+data.id;
                        }else{
                            swal({
                                position: 'center',
                                type: 'error',
                                title: "Error",
                                html: "Hubo un error, No se Guardó la Orden de Compra, intente nuevamente",
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $("#enviar_factura").prop('disabled',false);
                        }
                    }
                });
            }else{                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Debe agregar por lo menos un producto a la orden de compra",
                    showConfirmButton: false,
                    timer: 1500
                })
                $("#enviar_factura").prop('disabled',false);
            }
            
        });

    });


    

</script>
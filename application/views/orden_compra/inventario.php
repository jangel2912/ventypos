<?php //echo $this->session->userdata('base_dato');
if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015'):
    ?>
    <script>
        $(document).ready(function () {           
            $(document).on("mousedown", "#enviar_factura", function () {
            //$(document).on("mousedown", "#prueba", function () {
                $("#table-factura").find(".quantity").each(function (i, e) {
                    $.post(
                        "<?php echo base_url("index.php/RestFullController/agregarCantidadInventario") ?>", {
                        codigo: $(e).attr('data-codigo'),
                        cantidad: $(e).val()
                    });
                });                 
            });
        });

    </script>
  <!--<input type="button" id="prueba">-->
<?php endif; ?>
<style type="text/css">
    .ui-dialog{
        z-index: 9000!important;
    }
</style>
<!--<input type="button" id="prueba">-->

<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_bill', "Afectar Inventario"); ?></h2> 
    </div>
</div>

<div class="row-fluid">

    <div class="block">



        <div class="data-fluid">

<?php echo form_open("orden_compra/inventario", array("id" => "afectar")); ?>



            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_invoice_number', "Orden de compra No"); ?>:</div>

                <div class="span9"><input type="text"  value="<?php echo $id; ?>" name="id" readonly="readonly"/>

<?php echo form_error('numero'); ?>
                    <input type="hidden"  value="<?php echo $data['venta']['almacen_id']; ?>" name="almacen" readonly="readonly"/>
                    <input type="hidden"  value="<?php echo $data['venta']['cliente_id']; ?>" name="proveedor" readonly="readonly"/>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_invoice_number', "Almacén"); ?>:</div>

                <div class="span9"><?php echo $data['venta']['nombre']; ?>

<?php echo form_error('numero'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_date', "Fecha del pedido"); ?>:</div>

                <div class="span9"><?php echo $data['venta']['fecha']; ?>

<?php echo form_error('fecha'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_date_v', "Fecha de pago"); ?>:</div>



                <div class="span9"><?php echo ((!empty($data['venta']['fecha_vencimiento_orden'])) ? $data['venta']['fecha_vencimiento_orden'] : ""); ?>

<?php echo form_error('fecha_v'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_customer', "Proveedor"); ?>:</div>

                <div class="span9">


                    <?php echo strtoupper(($data['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta']["nombre_comercial"])); ?> 

                    <?php echo form_error('id_cliente'); ?>

                    <?php echo form_error('datos_cliente'); ?>

                </div>

            </div>
            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_customer', "Nota"); ?>:</div>

                <div class="span9">
                    <?php echo $data['venta']['nota'] ?>
                </div>

            </div>

            <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">

                <thead>

                    <tr>

                        <th width="10%">Codigo</th>

                        <th width="15%"><?php echo custom_lang('sima_product_name', "Nombre del producto"); ?></th>

                        <th width="5%"><?php echo custom_lang('sima_asa', "Unidades"); ?></th>											

                        <th width="5%"><?php echo custom_lang('sima_amount', "Cantidad"); ?></th>

                        <th width="10%" style="text-align:right"><?php echo custom_lang('sima_price', "Precio Unitario"); ?></th>

                        <th width="10%"><?php echo custom_lang('sima_discount', "Utilidad"); ?></th>

                        <th width="10%"><?php echo custom_lang('sima_discount', "Descuento(%)"); ?></th>

                        <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto"); ?></th>

                        <th width="10%" style="text-align:center" ><?php echo custom_lang('sima_price', "Precio Venta sin Impuesto actual"); ?></th>

                        <th width="10%" style="text-align:center" ><?php echo custom_lang('sima_price', "Precio Venta sin Impuesto Orden"); ?></th>

                        <th style="text-align:right" width="10%"><?php echo custom_lang('sima_total_price', "Precio Total"); ?></th>

                        <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Acciones"); ?></th>

                    </tr>

                </thead>
         
                <tbody id="detalle">

                    <?php
                    $total = 0;

                    $timp = 0;

                    $subtotal = 0;

                    $total_items = 0;

                    $group_by_impuesto = array();
                    $counter = NULL;
                    $hasta = NULL;
                    foreach ($data["detalle_venta"] as $p) { 
                        $counter++;
                        /* POS */
                        $pv = $p['precio_venta'];
                        $desc = $p['descuento'];
                        $pvd = $pv - ($desc * $pv / 100);
                        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                        $total_column = $pvd * $p['unidades'];
                        $total_items += $total_column;
                        $valor_total = $pvd * $p['unidades'] + $imp;
                        $total += $total + $valor_total;
                        $timp+=$imp;
                        ?>

                        <tr>

                            <td width="10">

                                <input type="hidden" name="id_producto[]" class="product_id"  id="id_producto" value="<?php echo $p["id"] ?>"/>
                                <input type="hidden" name="codigo[]" class="codigo"  id="codigo" value="<?php echo $p["codigo_producto"] ?>"/>
                                <?= $p["codigo_producto"] ?>
                            </td>

                            <td>
                                <?php echo $p["nombre_producto"] ?>
                                <input type="hidden" name="product-service[]" id="product-service" class='product-service' value="<?php echo $p["nombre_producto"] ?>"/>
                                <input type="hidden" name="producto_id[]" class="producto_id"  id="producto_id" value="<?php echo $p["producto_id"] ?>"/>

                                <span id='product-service-error'></span>

                            </td>

                            <td><?php echo $p['nombre_unidad']; ?></td>

                            <td>

                                <input type="text" name="cantidad[]" class="quantity" id="cantidad" value="<?php echo $p['unidades']; ?>" data-codigo="<?php echo $p['codigo_producto']; ?>" />

                                <span id='cantidad-error'></span>

                            </td>

                            <td><input type="text" name="precio[]" style="text-align:right" id="precio" class="psi" value="<?php echo $p['precio_venta']; ?>"/>

                                <span id='precio-error'></span>

                            </td>

                            <td><input type="text" name="utilidad" style="text-align:right" id="utilidad" class="utilidad" readonly="readonly" value="<?php echo $p['precio_venta_final'] - $p['precio_venta']; ?>"/>
                                <input type="hidden" name="precioventareal" style="text-align:right" id="precioventareal" class="precioventareal" value="<?php echo $p['precio_venta_final']; ?>"/>
                                <span id='utilidad-error'></span>

                            </td>

                            <td><input type="text" name="descuento[]" id="descuento" class="discount" value="<?php echo $p['descuento']; ?>"/>

                                <span id='descuento-error'></span>

                            </td>

                            <td><?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto', $p['impuesto']), "id='impuestos' class='impuesto'"); ?></td>

                            <td><input type="text" name="precio_venta_actual[]" style="text-align:right" readonly="readonly" id="precio_venta_actual" class="psv" value="<?php echo $p['precio_venta_final']; ?>"/>
                                <span id='precio_venta_actual-error'></span>
                            </td>

                            <td data-tooltip="Si modifica el precio de venta, al afectar el inventario, el producto quedará con el valor colocado"><input type="text" name="precio_venta2[]" style="text-align:right" id="precio_venta2" class="psv" value="<?php echo $p['precio_venta_p']; ?>"/>
                                <span id='precio_venta2-error'></span>
                            </td>

                            <td id="total_row" class="ptotal" style="text-align:right"><?php echo $valor_total; ?>&nbsp;</td>

                            <td align="right"><a href="<?php echo site_url("orden_compra/eliminar_producto/" . $id . "/" . $p['id']); ?>" onClick="if (confirm('Desea quitar este producto'))
                                        return true;
                                    else
                                        return false;" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a></td>



                        </tr>
                        <?php
                    }
                    ?>
                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="4"></th>

                        <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA"); ?></b></th>

                        <th style="text-align:right"><b class="total_siva"><?php echo $total_items; ?></b></th>

                        <th colspan="6"></th>



                    </tr>

                    <tr>

                        <th colspan="4"></th>

                        <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA"); ?></b></th>

                        <th style="text-align:right"><b class="iva"><?php echo $timp; ?></b></th>

                        <th colspan="6"></th>



                    </tr>

                    <tr>

                        <th colspan="4"></th>

                        <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA"); ?></b></th>
                        
                        <th style="text-align:right"><b class="total_civa"><?php echo $total_items + $timp; ?></b>

<?php echo form_error('input_total_civa'); ?>

                        </th>

                        <th colspan="6"></th>



                    </tr>

                <tfoot>

            </table>



            <br/>

            <div class="bottom tar">
                <div class="btn-group">
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                    <button class="btn btn-success" type="submit"  id="enviar_factura"><?php echo custom_lang("sima_submit", "Afectar Inventario"); ?></button>                   
                </div>

            </div>

        </div>

        <input type="hidden" name="input_iva[]" id="input_iva" />

        <input type="hidden" name="input_total_civa" id="input_total_civa" value="<?php echo  $total_items + $timp; ?>" />

        <input type="hidden" name="input_total_siva" id="input_total_siva"  value="<?php echo $total_items; ?>"/>

    </div>
</form>
</div>

<div id="dialog-client-form" title="<?php echo custom_lang('sima_new_client', "Adicionar Cliente"); ?>">


</div>



<script type="text/javascript">
    $(document).ready(function(){
        calculate();
    });
    $('#product-service').autocomplete({
        source: function (request, response) {

            $.ajax({
                url: "<?php echo site_url("productosf/filtro_prod"); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    //type: $("input[name='tipo']:checked").val(),

                    term: request.term

                },
                success: function (data) {



                    response($.map(data, function (item) {

                        return {
                            value: item.nombre,
                            id: item.id,
                            codigo: item.codigo,
                            precio: item.precio,
                            porciento: item.porciento,
                            descripcion: item.descripcion,
                            precio_venta: item.precio_compra




                        }

                    }));

                }

            });

        },
        minLength: 1,
        select: function (event, ui) {

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

    });


    function calculate() {

        var suma = 0, iva = 0;

        $(".psi").each(function (x) {

            psi = parseFloat($(".psi").eq(x).val());

            quantity = parseFloat($(".quantity").eq(x).val());

            discount = parseFloat($(".discount").eq(x).val());

            impuesto = parseFloat($(".impuesto").eq(x).val());

            precioventareal = parseFloat($(".precioventareal").eq(x).val());

            cantidad = psi * quantity;

            impuesto_row = cantidad * (impuesto / 100);

            //TOTAL FILA
            suma_row = cantidad + impuesto_row; //Precio + impuesto
            suma_row = suma_row - (suma_row * discount / 100);//Precion con impuesto - (descuento con impuesto)

            //TOTALES
            suma += cantidad - (cantidad * discount / 100); //Precio - descuento sin impuestos
            iva += impuesto_row - (impuesto_row * discount / 100);

            uti = precioventareal - psi;
            //alert(uti);
            $(".ptotal").eq(x).text(suma_row);

            $(".utilidad").eq(x).val(uti);
        });


        $(".total_siva").html((suma).toFixed(2));

        $(".iva").html((iva).toFixed(2));

        $(".total_civa").html((suma + iva).toFixed(2));

        $("#input_total_civa").val((suma + iva).toFixed(2));

        $("#input_total_siva").val((suma).toFixed(2));

        $("#input_iva").val((iva).toFixed(2));

    }


    $('#impuestos').change(function () {

        //calculate_row();

        calculate();

    });

    $("#enviar_factura").click(function () {
        document.getElementById('enviar_factura').disabled = true;
        document.getElementById("afectar").submit();
    });

    $(".psi, .quantity, .discount").live("keyup", (function () {

        //calculate_row();

        calculate();

    }));


</script>
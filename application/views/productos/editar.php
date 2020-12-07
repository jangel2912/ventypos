<script src="https://use.fontawesome.com/512cd430cc.js"></script>
<script src="<?= base_url('/assets/api_url.js') ?>"></script>
<script src="<?= base_url('/assets/toMoney.js') ?>"></script>
<!-- <?php $api_auth = (isset(json_decode($_SESSION['api_auth'])->token)) ? json_decode($_SESSION['api_auth'])->token : '';?> -->

<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<style>
    .state-icon {
        left: -5px;
    }
    .list-group-item-primary {
        color: rgb(255, 255, 255);
        background-color: rgb(66, 139, 202);
    }

    /* DEMO ONLY - REMOVES UNWANTED MARGIN */
    .well .list-group {
        width: 100%;
    }
</style>
<script>
    var precio_venta_inicial = Number("<?php echo $data['data']['precio_venta']; ?>");
    // let token_php = "<?php echo $api_auth; ?>";
    let token_php = "";
    $.ajax({
        type: "GET",
        url: api_url + '/data-currency',
        headers: {
            Authorization: `Bearer ${token_php}`
        },
        success: function (response) {
            datacurrency = response;
        }
    });
    function render () {

        $('.list-group.checked-list-box .list-group-item').each(function () {
            
            // Settings
            var $widget = $(this),
                $checkbox = $('<input type="checkbox" class="hidden" />'),
                color = ($widget.data('color') ? $widget.data('color') : "primary"),
                style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
                
            $widget.css('cursor', 'pointer')
            $widget.append($checkbox);

            // Event Handlers
            $widget.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function () {
                updateDisplay();
            });
            

            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $widget.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $widget.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$widget.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $widget.addClass(style + color + ' active');
                } else {
                    $widget.removeClass(style + color + ' active');
                }
            }

            // Initialization
            function init() {
                
                if ($widget.data('checked') == true) {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                }
                
                updateDisplay();

                // Inject the icon if applicable
                if ($widget.find('.state-icon').length == 0) {
                    $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
                }
            }
            init();
        });
        
        $('#get-checked-data').on('click', function(event) {
            event.preventDefault(); 
            var checkedItems = {}, counter = 0;
            $("#check-list-box li.active").each(function(idx, li) {
                checkedItems[counter] = $(li).text();
                counter++;
            });
            $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
        });
    }

    render();
    $(document).on('blur', '.dataMoneda1', function () {
        $(this).val(parseFloat($(this).val()));
    });
    var productosEnLibroDePrecios = JSON.parse('<?php echo json_encode($productosEnLibroDePrecios); ?>');
    var precio_compra = Number('<?php echo $data['data']['precio_compra']; ?>');
    $(function() {/*
        let libros_html = '';
        $.each(productosEnLibroDePrecios, function (indexInArray, valueOfElement) {
            console.log(valueOfElement.nombre_lp);
            let actual_value = Number($('#precio_venta').val());
            let actual_porcental = (100 - (valueOfElement.precio_ldp * 100) / Number(actual_value));
            let recalculation = (actual_value - (actual_value * actual_porcental) / 100);
            console.log(valueOfElement);
            libros_html = libros_html + '<li id_ldp="'+valueOfElement.id_ldp+'" id_lista_precios_ldp="' + valueOfElement.id_lista_precios_ldp + '" id_lp="'+valueOfElement.id_lp+'" id_producto_ldp="'+valueOfElement.id_producto_ldp+'" precio_ldp="'+valueOfElement.precio_ldp +'" nombre_lp="'+valueOfElement.nombre_lp+'" class="list-group-item"> <span style="font-weight: bold;">Libro:</span> ' + valueOfElement.nombre_lp + '  <span style="float: right;"><span style="font-weight: bold;">Recalculo:</span> ' + number_format(recalculation) +  '</span></li>';
            if(indexInArray == (productosEnLibroDePrecios.length -1)) {
                $('#list-books-prices').html(libros_html);
                render();
            }
        });*/

    });
</script>
<div class="page-header">    
    <div class="icon">
        <img alt="productos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_productos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Productos");?></h1>
</div>

<div class="block title">
    <div class="head">
        <?php if($this->session->flashdata('incorrecto')) { ?>
        <div class="alert alert-error"><?= $this->session->flashdata('incorrecto'); ?></div>
        <?php } ?>
        <h2><?php echo custom_lang('sima_new_product', "Editar Producto"); ?></h2>     
    </div>
</div>

<div class="row-fluid">
    <ul class="nav nav-tabs">
        <li class="active" id="video1" ><a href="#info" data-toggle="tab">Información</a>  </li>
        <li id="video"><a  href="#img" data-toggle="tab" >Imágenes</a></li>
    </ul>
    <?php echo form_open_multipart("productos/editar/" . $data['data']['id'], array("id" => "validate")); ?>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="info">
            <div class="span6">
                <div class="block">
                    <div class="data-fluid">
                        <input type="hidden" value="<?php echo set_value('id_producto', $data['data']['id']); ?>" name="id"  data-codigoS="<?= $data['data']['codigo'] ?>" />

                        <div class="row-form">
                            <div class="span3">Es ingrediente:</div>
                            <div class="span9"><input   <?php if ($data['material'] == 1) echo "checked='checked'"; ?> name="is_ingrediente" type="checkbox" value="1" style="opacity: 0;"></div>
                        </div>

                        <div class="row-form" style="display: none">
                            <div class="span3"><?php echo custom_lang('sima_category', "Tipo producto"); ?>:</div>
                            <div class="span9">
                                <select name='tipo_producto_id' onchange='setTipoProducto(this)'>
                                    <?php
                                        foreach ($data['tipo_productos'] as $key => $value) {
                                            if ($data['data']['combo'] == 1 && $value->id == 3)
                                                echo "<option value='" . $value->id . "' selected>" . ($value->nombre) . "</option>";
                                            else if ($data['data']['ingredientes'] == 1 && $value->id == 2)
                                                echo "<option value='" . $value->id . "' selected>" . ($value->nombre) . "</option>";
                                            else if (isset($data['data']['seriales_producto']) && $value->id == 4){
                                                echo "<option value='" . $value->id . "' selected>" . ($value->nombre) . "</option>";
                                            }else{
                                                echo "<option value='" . $value->id . "'>" . ($value->nombre) . "</option>";
                                            }
                                            
                                        }
                                    ?>
                                </select>
                                <?php echo form_error('tipo_producto_id'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_category', "Categoría"); ?>:</div>
                            <div class="span9">
                                <select name='categoria_id'>
                                    <?php
                                        foreach ($data['categorias'] as $key => $value) {
                                            if ($data['data']['categoria_id'] == $value->id) {
                                                echo "<option value='" . $value->id . "' selected>" . ($value->nombre) . "</option>";
                                            } else {
                                                echo "<option value='" . $value->id . "'>" . ($value->nombre) . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                                <?php echo form_error('categoria_id'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>
                            <div class="span9">
                                <input type="text" value="<?php echo set_value('nombre', $data['data']['nombre']); ?>" placeholder="" id="nombre" name="nombre" />
                                <?php echo form_error('nombre'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?>:</div>

                            <div class="span9"><input type="text" maxlength="16" value="<?php echo set_value('codigo', $data['data']['codigo']); ?>" placeholder="" name="codigo" id="codigo"/>
                                <div id= 'mensagge_warning' class="alert alert-error" style="display:none">El código suministrado ya pertenece a un producto</div>
                                <?php echo form_error('codigo'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_codigo_puntos_leal', "C&oacute;digo Puntos Leal"); ?>:</div>
                            <div class="span9">
                                <input type="text" value="<?php echo set_value('codigo_puntos_leal', $data['data']['codigo_puntos_leal']); ?>" placeholder="" name="codigo_puntos_leal" id="codigo_puntos_leal"/>
                                <div id= 'mensagge_warning2' class="alert alert-error" style="display:none">El código de Puntos Leal suministrado ya pertenece a un producto</div>
                                <?php echo form_error('codigo_puntos_leal'); ?>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sale_price', "Unidades"); ?>:</div>
                            <div class="span9">
                                <?php echo form_dropdown('id_unidades', $data['unidades'], $this->form_validation->set_value('id_unidades', $data['data']['unidad_id'])); ?>
                            </div>
                        </div>

                        <?php if ($data['precio_almacen'] != 1) { ?>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('price_of_purchase', "Precio de compra"); ?>:</div>
                                <div class="span9"><input class="" type="text" value="<?php echo set_value('precio_compra', $data['data']['precio_compra']); ?>" name="precio_compra" id="precio_compra" placeholder=""/>
                                    <?php echo form_error('precio_compra'); ?>
                                </div>
                            </div>

                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta"); ?>:</div>
                                <div class="span9"><input class="" type="text" value="<?php echo set_value('precio', $data['data']['precio_venta']); ?>" name="precio"  id="precio_venta"  placeholder="" readonly="readonly" />
                                    <?php echo form_error('precio'); ?>
                                </div>
                            </div>
                        <?php }?>                              

                        <?php if ($data['precio_almacen'] != 1) { ?>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto"); ?>:</div>
                                <div class="span9">
                                    <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto', $data['data']['impuesto']), "id='id_impuesto'"); ?>
                                    <?php echo form_error('id_impuesto'); ?>
                                </div>
                            </div>

                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta con impuesto"); ?>:</div>
                                <div class="span9"><input class="dataMoneda1" type="text" value="<?php echo set_value('precio'); ?>" name="precio_final" id="precio_venta_final" />
                                    <input  type="hidden" value="" name="impue" id="impue" readonly="readonly" />
                                </div>
                            </div>
                        <?php }?>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n"); ?>:</div>
                            <div class="span9">
                                <textarea name="descripcion" placeholder="">
                                    <?php echo set_value('descripcion', $data['data']['descripcion']); ?>
                                </textarea>
                                <?php echo form_error('descripcion'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_proveedor', "Proveedor"); ?>:</div>
                            <div class="span9">
                                <select name="id_proveedor" id="id_proveedor">
                                    <option value="0">Seleccionar proveedor</option>
                                    <?php
                                        foreach ($data['proveedores'] as $key => $proveedor) {
                                            if ($data['data']['id_proveedor'] == $proveedor['id_proveedor'])
                                                echo '<option value="' . $proveedor['id_proveedor'] . '" selected>' . $proveedor['nombre_comercial'] . '</option>';
                                            else
                                                echo '<option value="' . $proveedor['id_proveedor'] . '">' . $proveedor['nombre_comercial'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sale_active', "Activo"); ?>:</div>
                            <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo', $data['data']['activo']); ?>" <?php echo "1" == set_value('activo', $data['data']['activo']) ? "checked='checked'" : ""; ?> />
                                <?php echo form_error('activo'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sale_vendernegativo', "Vender en negativo"); ?>:</div>
                            <div class="span9">
                                <input id="vendernegativocheck" name="vendernegativo" type="checkbox" value="<?php echo set_value('vendernegativo', $data['data']['vendernegativo']); ?>" <?php echo "1" == set_value('vendernegativo', $data['data']['vendernegativo']) ? "checked='checked'" : ""; ?> />
                                <?php echo form_error('vendernegativo'); ?>
                            </div>
                        </div>

                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_tienda', "Tienda"); ?>:</div>
                            <div class="span9">
                                <input id="tiendacheck" name="tienda" type="checkbox" value="<?php echo set_value('tienda', $data['data']['tienda']); ?>" <?php echo "1" == set_value('tienda', $data['data']['tienda']) ? "checked='checked'" : ""; ?> />
                                <?php echo form_error('tienda'); ?>
                            </div>
                        </div>

                        <div id="muestraexist" class="row-form" <?php
                            if ($data['data']['tienda'] == 0) {
                                echo "style='display:none'";
                            }?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_category', "Tipo producto"); ?>:</div>
                    <div class="btn-group" data-toggle="buttons" role="group">
                        <?php foreach ($data['tipo_productos'] as $key => $value) { ?>
                            <label class="btn btn-success white">
                            <?php 
                                $checked = '';
                                
                                if ($data['data']['combo'] == 1  && $value->id == 3){
                                    $checked = 'checked = "checked"';
                                } else {
                                    if ($data['data']['ingredientes'] == 1  && $value->id == 2) {
                                        $checked = 'checked = "checked"';
                                    } else if(isset($data['seriales_producto']) && $value->id == 4) {
                                        $checked = 'checked = "checked"';
                                    } else {
                                        if ($value->id == 1){
                                            $checked = 'checked = "checked"';
                                        }
                                    }
                                }
                            ?>
                            <input type="radio" id="rb-tipo-producto-<?= $value->id ?>" name='tipo_producto_id' autocomplete="off" value="<?= $value->id ?>" <?= $checked?>>
                            <i class="icon wb-check text-active" aria-hidden="true"></i><?= $value->nombre ?></label>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="row-form"><button name="mostrar" type="button" class="btn btn-success" id="mostrar">Ver información adicional <i class="icon icon-arrow-down icon-white"></i></button></div>
                <div id="uno">
                    <div class="row-form">
                        <div class="span4"><?php echo custom_lang('sima_unit', "Ganancia"); ?>:</div>
                        <div class="span2"><input type="text" value="<?php echo $data['data']['ganancia']; ?>" name="ganancia" id="ganancia" placeholder=""/></div>
                        <div class="span2"> % </div>
                    </div>
                    <div class="row-form">
                        <div class="span4"><?php echo custom_lang('sima_unit', "Fecha de vencimiento"); ?>:</div>
                        <div class="span6"><input type="text" value="<?php echo $data['data']['fecha_vencimiento']; ?>" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="" class="datepicker"/>  </div>
                    </div>
                    <div class="row-form">
                        <div class="span4"><?php echo custom_lang('sima_unit', "Stock Mínimo"); ?>:</div>
                        <div class="span6"><input type="text" value="<?php echo $data['data']['stock_minimo']; ?>" name="stock_minimo" id="stock_minimo" placeholder=""/>  </div>
                    </div>
                    <div class="row-form">
                        <div class="span4"><?php echo custom_lang('sima_unit', "Stock Máximo"); ?>:</div>
                        <div class="span6"><input type="text" value="<?php echo $data['data']['stock_maximo']; ?>" name="stock_maximo" id="stock_maximo" placeholder=""/>  </div>
                    </div>
                    <div class="row-form">
                        <div class="span4"><?php echo custom_lang('sima_unit', "Ubicaci&oacute;n del producto"); ?>:</div>
                        <div class="span6"><input type="text" value="<?php echo $data['data']['ubicacion']; ?>" name="ubicacion" id="ubicacion" placeholder=""/>  </div>
                    </div>
                    <?php if (!empty($data['lista_precios'])) { ?>
                        <hr />
                        <div class="row-form">
                            <div class="span10"><?php echo custom_lang('sima_unit', "Listas de precios:"); ?></div>
                        </div> 
                        <div class="row-form">
                            <?php
                            //var_dump($data['lista_precios']);
                            foreach ($data['lista_precios'] as $key => $lp) {
                                ?>
                                <div class="span1"><input type="checkbox" name="lista_precios[]" value="<?php echo $lp['id'] ?>" <?php echo ($lp['checked'] == true) ? "checked" : "" ?>></div>
                                <div class="span4"><?php echo custom_lang('sima_unit', $lp['nombre']); ?></div>
                                <?php
                                if (($key + 1) % 2 == 0) {
                                    ?></div><div class="row-form"><?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!--Ingredientes ..................................................................... -->

                <p id='legenda-tipo-producto'></p>
                <hr />
                <table class="table aTable" id="table-ingredientes" cellpadding="0" cellspacing="0" width="100%">
                    <thead>

                        <tr>

                            <th width="5"></th>

                            <th width="15%">Código</th>
                            <th width="50%">Materiales</th>
                            <th width="35%">Cantidad</th>
                            <th ></th>

                        </tr>

                    </thead>

                    <tbody id="detalle">
                        <?php 
                        if(count($data['ingredientes'])>0){
                            $size = count($data['ingredientes']);
                        }else{
                            $size = 10;
                        }
                        for ($i = 0; $i < $size; $i++) {

                            if (!empty($data['ingredientes'][$i])) {
                                $ingrediente_id = $data['ingredientes'][$i]['id'];
                                $nombre = $data['ingredientes'][$i]['nombre'];
                                $codigo = $data['ingredientes'][$i]['codigo'];
                                $cantidad = $data['ingredientes'][$i]['cantidad_ingrediente'];
                            } else {
                                $ingrediente_id = '';
                                $nombre = '';
                                $codigo = '0000';
                                $cantidad = '';
                            }


                            echo' <tr>
                            <td width="10">
                                <input type="hidden" name="Ingrediente[id][' . $i . ']" class="id-ingredient" id="id-ingredient" value="' . $ingrediente_id . '">
                            </td>

                            <td>
                                <input type="text" name="Ingrediente[codigo][' . $i . ']" id="cod-ingredient" value="' . $codigo . '" disabled="" >
                            </td>

                            <td>
                                <div class="input-prepend input-append">
                                  <input type="text" placeholder="Nombre" name="Ingrediente[nombre][' . $i . ']" id="ingredient" style="width: 257px;"  autocomplete="off"  value="' . $nombre . '">
                                  <span class="add-on green"><i class="icon-search icon-white"></i></span>

                                </div>
                                <ul id="ingredients-list-' . ($i + 1) . '" class="autocomplete"> </ul>
                               <!--
                                <span id="product-error"></span> -->

                            </td>


                            <td>
                                <input class="ingreCantidad" idI="' . $i . '" type="text" name="Ingrediente[cantidad][' . $i . ']" style="text-align:right" id="quantity" value="' . $cantidad . '">

                                <span id="cant-error"></span>

                            </td>

                            <td>
                               <a href="javascript:void(0)" onclick="removeDetailIng(' . ($i) . ')" class="button red acciones">
                               <div class="icon">'; ?>
                               <img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div>
                            <?php echo' </div>
                            </td>

                        </tr>';
                        }
                        ?>



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <button type="button" class="btn btn-success white" id='add_ingrediente' onclick="agregar_campos_tablas_productos('table-ingredientes')">Agregar <i class="icon icon-plus icon-white"></i></button></td>
                        </tr>
                    </tfoot>


                </table>

                <table class="table aTable" id="table-combo" cellpadding="0" cellspacing="0" width="100%">
                    <thead>

                        <tr>

                            <th width="5"></th>

                            <th width="15%">Código</th>
                            <th width="50%">Productos</th>
                            <th width="35%">Cantidad</th>
                            <th></th>

                        </tr>

                    </thead>

                    <tbody id="detalle">

                        <?php
                        if(count($data['productos_combo'])> 0 ){
                            $size = count($data['productos_combo']);
                        }else{
                            $size = 10;
                        }
                        for ($i = 0; $i < $size; $i++) {

                            if (!empty($data['productos_combo'][$i])) {
                                $producto_id = $data['productos_combo'][$i]['id'];
                                $nombre = $data['productos_combo'][$i]['nombre'];
                                $codigo = $data['productos_combo'][$i]['codigo'];
                                $cantidad = $data['productos_combo'][$i]['cantidad_producto'];
                            } else {
                                $producto_id = '';
                                $nombre = '';
                                $codigo = '0000';
                                $cantidad = '';
                            }


                            echo' <tr>
                            <td width="10">
                                <input type="hidden" name="productosCombo[id][' . $i . ']" class="id-productos-combo" id="id-productos-combo" value="' . $producto_id . '">
                            </td>

                            <td>
                                <input type="text" name="productosCombo[codigo][' . $i . ']" id="cod-productos-combo" value="' . $codigo . '" disabled="" >
                            </td>

                            <td>
                                <div class="input-prepend input-append ventas">
                                  <input type="text" placeholder="Nombre" name="productosCombo[nombre][' . $i . ']" id="producto-combo" style="width: 207px;"  autocomplete="off"  value="' . $nombre . '">
                                  <span class="add-on green"><i class="icon-search icon-white"></i></span>
                                </div>
                                <ul id="productos-combo-list-' . ($i + 1) . '" class="autocomplete"> </ul>
                               <!--
                                <span id="product-error"></span> -->

                            </td>


                            <td>
                                <input class="combosCantidad" idC="' . $i . '" type="text" name="productosCombo[cantidad][' . $i . ']" style="text-align:right" id="quantity" value="' . $cantidad . '">

                                <span id="cant-error"></span>

                            </td>

                            <td>
                               <a href="javascript:void(0)" onclick="removeDetailCom(' . ($i) . ')" class="button red acciones">';?>
                               <div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></div>
                           <?php echo'</td>

                        </tr>';
                        }
                        ?>


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <button type="button" class="btn btn-success white" id='add_combo' onclick="agregar_campos_tablas_productos('table-combo')">Agregar <i class="icon icon-plus icon-white"></i></button></td>
                        </tr>
                    </tfoot>

                </table>

                <table  class="table aTable" id="table-seriales" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="90">Codigo serial</th>
                            <th width="10">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="detalle">
                    <?php if(isset($data['seriales_producto'])){
                            $max = count($data['seriales_producto']);
                        }else{
                            $max = 10;
                        }
                        for ($i = 0; $i < $max; $i++) {
                            $disabled_imei = false;
                            if(isset($data['seriales_producto'][$i])){
                                $value = $data['seriales_producto'][$i]->serial;
                                if($data['seriales_producto'][$i]->serial_vendido == 1 || $data['seriales_producto'][$i]->id_venta != ""){
                                    $disabled_imei = true;
                                }
                            }else{
                                $value = '';
                            }
                     ?>
                         <tr>
                             <td>
                                 <input type="hidden" name="serial_anterior[<?php echo $i ?>]" value ="<?php echo $value; ?>">
                                 <input type="text" id="input-serial" name="seriales_producto[<?php echo $i ?>]" placeholder ="Digite el serial" value ="<?php echo $value; ?>" <?php echo ($disabled_imei)? 'readonly' : '';?>>
                             </td>
                             <td>
                                 <a href="javascript:void(0)"  class="button red delete-seriales acciones">
                                 <div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></div>
                                 </a>
                             </td>
                         </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <button type="button" class="btn btn-success white" id='add_combo' onclick="agregar_campos_tablas_productos('table-seriales')">Agregar <i class="icon icon-plus icon-white"></i></button></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
        <div class="tab-pane fade" id="img">
            <div class="span12"><?php echo $data['data']['upload_error']; ?></div>
            <div class="span12" style="margin: 10px 0px 0px 0px;">
                <div class="row-form span4">
                    <div class="span4"><?php echo custom_lang('sima_image', "Imagen Principal"); ?>:
                    </div>
                    <div class="span8">
                        <div class="input-append file">
                            <input type="file" name="imagen" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-principal text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen'])): ?>
                            <img class='legacy-imagen-principal' src="<?php echo $data['data']['imagen']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row-form span4">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:
                    </div>
                    <div class="span9">
                        <div class="input-append file">
                            <input type="file" name="imagen1" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-1 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen1'])): ?>
                            <img class='legacy-imagen-1' src="<?php echo $data['data']['imagen1']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row-form span4">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                    </div>
                    <div class="span9">
                        <div class="input-append file">
                            <input type="file" name="imagen2" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-2 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen2'])): ?>
                            <img class='legacy-imagen-2' src="<?php echo $data['data']['imagen2']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="span12" style="margin: 10px 0px 0px 0px;">
                <div class="row-form span4">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                    </div>
                    <div class="span9">
                        <div class="input-append file">
                            <input type="file" name="imagen3" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-3 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen3'])): ?>
                            <img class='legacy-imagen-3' src="<?php echo $data['data']['imagen3']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row-form span4">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                    </div>
                    <div class="span9">
                        <div class="input-append file">
                            <input type="file" name="imagen4" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-4 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen4'])): ?>
                            <img class='legacy-imagen-4' src="<?php echo $data['data']['imagen4']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row-form span4">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                    </div>
                    <div class="span9">
                        <div class="input-append file">
                            <input type="file" name="imagen5" class="img-add"/>
                            <input type="text" class="img-add"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div>
                        <a class="selectFromGallery imagen-5 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        <?php if (!empty($data['data']['imagen5'])): ?>
                            <img class='legacy-imagen-5' src="<?php echo $data['data']['imagen5']; ?>" alt="logotipo" height="100px" width="100px"/>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                    <th>Cantidad actual</th>
                    <?php if ($data['precio_almacen'] == 1) { ?>
                        <th>Stock Mínimo</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Impuesto</th>
                        <th>Fecha Vencimiento</th>
                        <th>Activo</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $is_admin = $this->session->userdata('is_admin');
                foreach ($data['almacenes'] as $value) :
                    $desactivado="";
                    if(!empty($data['almacenes_inactivo'])){

                        if (array_key_exists($value->almacen_id, $data['almacenes_inactivo'])) {
                            $desactivado='readonly';
                        }
                    }
                    ?>
                    <?php if ($is_admin == 't') { ?>
                        <tr>

                            <td><?php echo $value->nombre; ?></td>

                            <td><input <?=$desactivado?> name="Stock[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?php echo isset($_POST['Stock'][$value->almacen_id]) ? $_POST['Stock'][$value->almacen_id] : 0; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-stock_actual="<?= $value->unidades ?>" /></td>

                            <td><?php echo $value->unidades; ?></td>
                            <?php if ($data['precio_almacen'] == 1) { ?>
                                <td><input <?=$desactivado?> name="Stock_minimo[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?= $value->stock_minimo; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-stock_minimo="<?= $value->stock_minimo ?>" /></td>
                                <td><input <?=$desactivado?> name="Precio_compra[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?= $value->precio_compra; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-precio_compra="<?= $value->precio_compra ?>" /></td>
                                <td><input <?=$desactivado?> name="Precio_venta[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?= $value->precio_venta; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-precio_venta="<?= $value->precio_venta ?>" /></td>
                                <td><?php echo form_dropdown('Impuesto['.$value->almacen_id.']', $data['impuestos'], $value->impuesto, 'data-almacen_id="'.$value->almacen_id.'" data-impuesto="'.$value->impuesto.'"'); ?></td>
                                <td><input <?=$desactivado?> class="datepicker" name="Fecha_vencimiento[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?= $value->fecha_vencimiento; ?>" data-almacen_id="<?= $value->almacen_id ?>" data-fecha_vencimiento="<?= $value->fecha_vencimiento ?>" /></td>
                                <td><?php echo form_dropdown('Activo['.$value->almacen_id.']', array('1'=>'Si','0'=>'No'), $value->activo, 'data-almacen_id="'.$value->almacen_id.'" data-activo="'.$value->activo.'"'); ?></td>
                            <?php } ?>

                        </tr>
                    <?php } ?>
                    <?php if ($is_admin != 't') { ?>
                        <?php if ($data['almacenes_id'] == $value->almacen_id) { ?>
                            <tr>

                                <td><?php echo $value->nombre; ?></td>

                                <td><input <?=$desactivado?> name="Stock[<?php echo $value->almacen_id; ?>]" min="0" type="text" value="<?php echo isset($_POST['Stock'][$value->almacen_id]) ? $_POST['Stock'][$value->almacen_id] : 0; ?>"/></td>

                                <td><?php echo $value->unidades; ?></td>

                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <div class="toolbar bottom tar">
        <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
        <button id="prueba" class="btn btn-success"  type="button"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
    </div>
</form>
</div>

<!-- Modal Abonar factura -->
<div class="modal fade" id="modal_book_prices" tabindex="-1" role="dialog" aria-labelledby="modal_book_prices_Label" aria-hidden="true">
    <div id="modalInternet" class="modal-dialog modal-center">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7);padding: 5px;">Lista de libro de precios</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="msm-info" style="max-height: 300px; background-color: #F6F6F6;">
                        <div style="border: 1px solid #e3e3e3; border-radius: 4px; border-radius: 4px;">
                        <div style="text-align: center;">
                            <i class="fa fa-info-circle fa-5x" aria-hidden="true"></i>
                            <p style="margin: 5px; font;font-weight: bold;">El producto que intentas editar está en algunos libros de precios.</p>
                        </div>
                        <ol>
                            <li>
                                <p style="margin: 5px;">Marca los libros que quieras recalcular.</p>
                            </li>
                            <li>
                                <p style="margin: 5px;">El recalculo se hace en base a el valor actual y el valor registrado en el libro.</p>
                            </li>
                            <li>
                                <p style="margin: 5px;">Este recalculo se actualizará automáticamente en los libros de precios seleccionados después de presionar la opción “Recalcular”.</p>
                            </li>
                        </ol>
                        </div>
                    </div>
                    <div class="well" style="max-height: 250px;overflow: auto; margin-top: 10px;">
                        <ul class="list-group checked-list-box" id="list-books-prices">
                            <!-- Ejemplo de uso -->
                            <!-- <li class="list-group-item">Cras justo odio</li>
                            <li class="list-group-item" data-checked="true">Dapibus ac facilisis in</li> -->
                        </ul>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="">
                <button id="modal_book_prices_cancelar" type="button" class="btn btn-default" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Cancelar </button>
                <button id="modal_book_prices_recalculate" type="button" class="btn btn-success" style="padding: 5px 20px 5px 20px;">
                    <span class="recalculate-state">Recalcular</span>
                    <span style="display: none" class="spinner-loading">
                        <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!--video-->
<div class="social" style="display:none">
    <ul>
        <!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
        <li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>
    </ul>
</div>
    <!-- vimeo-->
<div id="myModalvideovimeo" class="modal fade">
    <div style="padding:56.25% 0 0 0;position:relative;">
        <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924231?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
</div>

<div class="myImageFinderModal"></div>
    <!-- youtuve-->
    <!--
<div id="myModalvideo" class="modal fade">
        <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>  -->


<style type="text/css">
    #table-ingredientes,#table-combo,#table-seriales{
        display: none;
    }
    
    .img-add{
        width: 90% !important;
    }
    #table-ingredientes #ingredient, #table-combo #producto-combo{
        width: 230px!important;
    }

    ul.autocomplete{
        display: none;
        z-index: 3000;
        list-style: none;
        margin-left: 10px;
        position: absolute;
        width: 300px;
        background: white;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        border-bottom: 1px solid #E9E9E9;
    }

    ul.autocomplete li div{
        padding-left: 10px;
        padding-top: 7px;
        padding-right: 10px;
        padding-bottom: 7px;
        border-bottom: 1px solid #E9E9E9;
    }

    ul.autocomplete li div:hover{
        background: #F9F9F9;
    }

    ul.autocomplete li  div span#precio-venta-autocomplete{
        float: right;
    }
</style>

<script type="text/javascript">
    var name = $("#nombre").val();
    var regex = /[\;\`\'\*\~\´\¨]/;
    var replace = '';

    while(regex.exec(name) !== null){
        replace = name.replace(regex,'');
        $("#nombre").attr('value',replace);
        name =  $("#nombre").val();
    }

    $('#nombre').keyup(function(event) {
        var str = $(this).val();
        if (regex.exec(str) !== null) {
            $(this).val(str.substr(0, (str.length - 1)));
        }
    });

    $("#video").click(function () {
        $(".social").css('display','block');
    });

    $("#video1").click(function () {
        $(".social").css('display','none');
    });

    <?php
        $lista_ingredientes = array();

        if(count($data['ingredientes']) > 0){
            $max = count($data['ingredientes']);
        } else {
            $max = 10;
        }

        for ($i = 0; $i < $max; $i++) {
            $cantidad = '0';
            $compra = '0';
            $impuesto = '0';

            if (!empty($data['ingredientes'][$i])) {
                $cantidad = $data['ingredientes'][$i]['cantidad_ingrediente'];
                $impuesto = $data['ingredientes'][$i]['impuesto'];
                $compra = $data['ingredientes'][$i]['precio_compra'];
            }

            $lista_ingredientes[]=array('cantidad'=>$cantidad,'compra'=>$compra,'impuesto'=>$impuesto);
        }
    ?>

    var listaIngredientes = <?php echo json_encode($lista_ingredientes) ?>;

    <?php
        $lista_combos = array();
        if(count($data['productos_combo']) > 0){
            $max = count($data['productos_combo']);
        }else{
            $max = 10;
        }

        for ($i = 0; $i < $max; $i++) {
            $cantidad = '0';
            $compra = '0';
            $impuesto = '0';

            if (!empty($data['productos_combo'][$i])) {
                $cantidad = $data['productos_combo'][$i]['cantidad_producto'];
                $impuesto = $data['productos_combo'][$i]['impuesto'];
                $compra = $data['productos_combo'][$i]['precio_compra'];
            }

            $lista_combos[]=array('compra'=>$compra,'impuesto'=>$impuesto,'cantidad'=>$cantidad);
        }
    ?>
    var listaCombos = <?php echo json_encode($lista_combos) ?>;

    $(".ingreCantidad").keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
        }
    });

    $(".combosCantidad").keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
        }
    });

    $(".ingreCantidad").live("keyup",function () {
        var index = $(this).attr("idI");
        var codigo = $('input[name="Ingrediente[codigo][' + (index) + ']"').val();

        if (codigo == "0000") {
            listaIngredientes[(index)]["cantidad"] = 0;
        } else {
            listaIngredientes[(index)]["cantidad"] = parseFloat($(this).val());
        }
        calcularPrecioCompra("ing");
    });

    $(".combosCantidad").keyup(function () {
        var index = $(this).attr("idC");
        var codigo = $('input[name="productosCombo[codigo][' + (index) + ']"').val();

        if (codigo == "0000") {
            listaCombos[(index)]["cantidad"] = 0;
        } else {
            listaCombos[(index)]["cantidad"] = parseFloat($(this).val());
        }

        calcularPrecioCompra("comb");
    });

    /*Busca y filtra productos por nombre*/
    $("input[placeholder=Nombre]").keyup(function () {
        var element_name = $(this).attr("name");
        var ingrediente_nombre = element_name.split('Ingrediente[nombre]');
        var filter = '';
        var codigo = '';
        var index = 0;

        if (ingrediente_nombre.length > 1) {

            index = ((element_name.split('Ingrediente[nombre]')[1]).replace("[", "")).replace("]", "");
            index = parseInt(index) + 1;
            filter = $(this).val();
            url = "../../ingredientes/filtro?filter=" + filter;
            list = '#ingredients-list';
            action = 'setIngredent';

        } else {
            //var producto_nombre = element_name.split('productosCombo[nombre]');

            index = ((element_name.split('productosCombo[nombre]')[1]).replace("[", "")).replace("]", "");
            index = parseInt(index) + 1;
            filter = $(this).val();
            codigo = $("#codigo").val();
            url = "../../combos/filtro?filter=" + filter+ '&codigo=' + codigo;
            list = '#productos-combo-list';
            action = 'setProductosCombo';
        }

        $.ajax({
            type: "GET",
            url: url
        }).done(function (response) {
            if (response.done == 1) {
                ingredients = response.data;
                $(list + '-' + index).html("");
                $(list + '-' + index).css("display", "block");

                for (var i = 0; i < response.data.length; i++) {
                    $(list + '-' + index).append(
                        '<li id="' + response.data[i].id + '">' +
                        '<div onclick="' + action + '(' + (i) + ',' + (index) + ')">' +
                        '<h5>' + response.data[i].nombre + '</h5>' +
                        '<span id="precio-venta-autocomplete">Venta: ' + response.data[i].precio_venta + '</span>' +
                        '<span id="precio-compra-autocomplete">Compra: ' + response.data[i].precio_compra + '</span>' +
                        '</div>' +
                        '</li>'
                    );
                }
            }
        });
    });

    $("#mostrar").click(function (e) {
        if (document.getElementById('uno').style.display == 'none') {
            document.getElementById('uno').style.display = 'block';
        } else {
            document.getElementById('uno').style.display = 'none';
        }
    });

    function validateSendForm(){
        document.getElementById("prueba").disabled = true;
        let validate = true;
        if ($("#codigo").val() != '<?php echo set_value('codigo', $data['data']['codigo']); ?>') {
            $.ajax({
                async: false,
                url: "<?php echo site_url("productos/validateCodigo"); ?>",
                type: "POST",
                dataType: "json",
                data: {codigo: $("#codigo").val()},
                success: function (data) {
                    if (data != 0) {
                        $('#mensagge_warning').css('display','block');
                        $('#codigo').focus();
                        document.getElementById("prueba").disabled = false;
                        validate = false;
                    } else {
                        document.getElementById('mensagge_warning').style.display = 'none';
                    }
                }
            });
        }

        if ($("#codigo_puntos_leal").val() != '<?php echo set_value('codigo_puntos_leal', $data['data']['codigo_puntos_leal']); ?>') {
            $.ajax({
                async: false,
                url: "<?php echo site_url("productos/validateCodigoPuntosLeal"); ?>",
                type: "POST",
                dataType: "json",
                data: {codigo_puntos_leal: $("#codigo_puntos_leal").val()},
                success: function (data) {
                    if (data != 0) {
                        $('#mensagge_warning2').css('display','block');
                        $('#codigo_puntos_leal').focus();
                        document.getElementById("prueba").disabled = false;
                        validate = false;
                    } else {
                        document.getElementById('mensagge_warning2').style.display = 'none';
                    }
                }
            });
        }

        if (validate == true) {
            $("#validate").submit();
        }
    }

    $("#prueba").click(function () {
        let precio_venta_actua = Number($('#precio_venta').val());
        if(precio_venta_actua !== precio_venta_inicial){ //cambio
            let libros_html = '';
            if(productosEnLibroDePrecios.length == 0){
                validateSendForm();
            }
            $.each(productosEnLibroDePrecios, function (indexInArray, valueOfElement) {
                //porcentaje que actualmente tiene el libro de precios
                // 100% - (("valor en el libro de precios"*100 ) / "el precio de venta del producto sin modificar")
                let actual_porcental = (100 - (valueOfElement.precio_ldp * 100) / Number(precio_venta_inicial));

                //el recalculo es
                //("El valor actual" - ("el valor actual" * "El porcentaje de descuento que tiene")) / 100
                let recalculation = (precio_venta_actua - (precio_venta_actua * actual_porcental) / 100);

                libros_html = libros_html + '<li id_ldp="'+valueOfElement.id_ldp+'" id_lista_precios_ldp="' + valueOfElement.id_lista_precios_ldp + '" id_lp="'+valueOfElement.id_lp+'" id_producto_ldp="'+valueOfElement.id_producto_ldp+'" precio_ldp="'+valueOfElement.precio_ldp +'" nombre_lp="'+valueOfElement.nombre_lp+'" recalculation="'+recalculation+'" class="list-group-item"> <span style="font-weight: bold;" >Libro:</span> ' + valueOfElement.nombre_lp + '  <span style="float: right;"><span style="font-weight: bold;">Recalculo:</span> ' + number_format(recalculation) +  '</span></li>';
                if(indexInArray == (productosEnLibroDePrecios.length -1)) {
                    $('#list-books-prices').html(libros_html);
                    render();
                    $('.checker').show();

                    $('#modal_book_prices').modal();

                }
            });
            return;
        }else{
            validateSendForm();
        }
    });

    $('#modal_book_prices_recalculate').click(function(val){
        let lista_libros_seleccionados = $('.list-group-item-primary');
        $('.recalculate-state').hide();
        $('.spinner-loading').show();
        var data = {
            'books': []
        };

        if($('.list-group-item-primary').length == 0){
            //No selecciono ninguno
            validateSendForm();
        }

        $(lista_libros_seleccionados).each(function (index, element) {
            let obj = {
                'id_ldp': $(this).attr('id_ldp'),
                'id_lista_precios_ldp': $(this).attr('id_lista_precios_ldp'),
                'id_lp': $(this).attr('id_lp'),
                'id_producto_ldp': $(this).attr('id_producto_ldp'),
                'precio_ldp': $(this).attr('precio_ldp'),
                'nombre_lp': $(this).attr('nombre_lp'),
                'recalculation': $(this).attr('recalculation')
            }
            data.books.push(obj);

            if(lista_libros_seleccionados.length === 0){
                validateSendForm();
            }

            if((lista_libros_seleccionados.length - 1) == index){
                console.log(data);
                $.ajax({
                    type: "POST",
                    contentType: 'application/json',
                    dataType: 'json',
                    url: "<?php echo site_url("productos/editaLibroPreciosAjax") ?>",
                    data: JSON.stringify(data),
                    success: function(response) {
                        var data = JSON.parse(response);
                        if(data.status == "updated"){
                            $('.recalculate-state').show();
                            $('.spinner-loading').hide();
                            $('#modal_book_prices').modal('hide');
                        }

                        //seguir con el cliclo comun
                        validateSendForm();
                    }
                });
            }
        });
    });

    window.onload = function () {
        document.getElementById('uno').style.display = 'none';
    }

    function calcularPrecioCompra(tipo) {
        var total = 0;

        if( tipo == "unico"){
            total = $("#precio_compra").val();
        }else{
            var arreglo_calcular;

            if( tipo === "ing"){
                arreglo_calcular=listaIngredientes;
                id_tabla='table-ingredientes';
            }
            if( tipo === "comb"){
                arreglo_calcular = listaCombos;
                id_tabla = 'table-combo';
            }

            for (var i = 0,max = $("table#"+id_tabla+" #detalle tr").length; i < max; i++) {
                if(Number(arreglo_calcular[i].cantidad)>0){
                    console.log('dentro del ciclo, cantidad > 0 posicion:'+i);
                    console.log('valor a calcular de cantidad '+arreglo_calcular[i].cantidad);
                    total = total + Number((arreglo_calcular[i].compra * arreglo_calcular[i].cantidad));
                }
            }
        }
        $("#precio_compra").val( total );
    }

    function formatDollar(num) {
        num = parseFloat(num);
        var p = num.toFixed(2).split(".");
        return p[0].split("").reverse().reduce(function (acc, num, i, orig) {
            return  num + (i && !(i % 3) ? "," : "") + acc;
        }, "") /*+ "." + p[1]*/;
    }

    $(document).ready(function () {
        $.each($('input[name="tipo_producto_id"]'),function(index,value){
            if($(value).is(":checked")){
               set_tabla_tipo_producto($(value).val());
            }

        });
    });

     // Calcula el precio de la venta y la ganancia descontando el impuesto //
    
     $("#precio_compra, #ganancia").keyup(function (e) {
            if (parseFloat($("#ganancia").val()) >= 0) {
                var gan = parseFloat($("#ganancia").val());
                var precio_venta = parseFloat((gan * parseFloat($("#precio_compra").val())) / 100);
                var diferencia = 

                $("#precio_venta").val(
                    parseFloat((precio_venta + parseFloat($("#precio_compra").val())))
                );

                $("#precio_venta_final").attr('disabled', false);
                $("#precio_venta_final").val(
                    parseFloat(parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val()))
                );
                
            } else {
                $("#precio_venta_final").attr('disabled', false);
            }
        });

    // $("#precio_compra, #ganancia").keyup(function (e) {
    //     if ($("#ganancia").val() > 0) {
    //         if(__decimales__==0){
    //             var gan = parseInt($("#ganancia").val());
    //             var precio_venta = (gan * parseInt($("#precio_compra").val())) / 100;

    //             $("#precio_venta").val(Math.round((
    //                     Math.round(parseInt(precio_venta) + parseInt($("#precio_compra").val()))
    //                     )));

    //             $("#precio_venta_final").val(formatDollar(
    //             //  Math.round(parseInt($("#precio_venta").val()) * parseInt($("#impue").val()) / 100 + parseInt($("#precio_venta").val())))
    //                 (parseInt($("#precio_venta").val()) * parseInt($("#impue").val()) / 100 + parseInt($("#precio_venta").val())))
    //             );
    //         }else{
    //             var gan = parseFloat($("#ganancia").val());
    //             var precio_venta = (gan * parseFloat($("#precio_compra").val())) / 100;

    //             $("#precio_venta").val(redondear((
    //                     redondear(parseFloat(precio_venta) + parseFloat($("#precio_compra").val()))
    //                     )));

    //             $("#precio_venta_final").val(formatDollar(
    //                 (parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val())))
    //             );
    //         }
    //     }
    // });

    $('#id_impuesto').change(function () {
        $.ajax({
            url: "<?php echo site_url("productos/impuesto_valor"); ?>",
            type: "GET",
            dataType: "json",
            data: {id_impuesto: parseFloat($("#id_impuesto").val())},
            success: function (data) {
                $("#impue").val(parseFloat(data.porciento));
                if (parseFloat($("#ganancia").val()) >= 0) {
                    var gan = parseFloat($("#ganancia").val());
                    var precio_venta = parseFloat((gan * parseFloat($("#precio_compra").val())) / 100);

                    $("#precio_venta").val(
                        parseFloat((precio_venta + parseFloat($("#precio_compra").val())))
                    );

                    $("#precio_venta_final").val(
                        parseFloat(parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val()))
                    );
                    
                } else {
                    if (parseFloat($("#precio_venta_final").val()) > 0) {
                        $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_final").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
                        if (parseFloat(data.porciento) == 0){
                            $("#precio_venta").val(parseFloat($("#precio_venta_final").val()).toFixed(2));
                        }
                    } else {
                        $("#precio_venta").val(0);
                    }
                }
                
            }
        });
    });

    // $('#id_impuesto').change(function () {
    //     $.ajax({
    //         url: "<?php echo site_url("productos/impuesto_valor"); ?>",
    //         type: "GET",
    //         dataType: "json",
    //         data: {id_impuesto: parseFloat($("#id_impuesto").val())},
    //         success: function (data) {
    //             $("#impue").val(parseFloat(data.porciento));
    //             if (parseFloat($("#precio_venta_final").val()) > 0) {
    //                 $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_final").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
    //                 if (parseFloat(data.porciento) == 0){
    //                     $("#precio_venta").val(parseFloat($("#precio_venta_final").val()).toFixed(2));
    //                 }
    //             } else {
    //                 $("#precio_venta").val(0);
    //             }
    //         }
    //     });
    // });

    //importante!
    $("#precio_venta_final").keyup(function (e) {
        if (parseFloat($("#precio_venta_final").val()) >= 0) {
            $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_final").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
            $("#temp") = $("#precio_venta").val(parseFloat($("#precio_venta_final").val()).toFixed(2));
            $("#precio_venta_final") = $("#precio_venta_final") + $("#temp");
        }
    });


    // $("#precio_venta_final").keyup(function (e) {
    //     if (parseFloat($("#precio_venta_final").val()) > 0) {
    //         $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_final").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
    //     }
    // });

    $().ready(function () {
        $("#tiendacheck").click(function () {
            if ($('#tiendacheck').val() == 1) {
                $('#tiendacheck').val(0);
                $('#muestraexist').hide();
            } else {
                $('#muestraexist').show();
                $('#uniform-muestraexistcheck').show();
                $('#tiendacheck').val(1);
            }
        });

        $("#muestraexistcheck").click(function () {
            if ($('#muestraexistcheck').val() == 1) {
                $('#muestraexistcheck').val(0);
            } else {
                $('#muestraexistcheck').val(1);
            }
        });

        $("#vendernegativocheck").click(function () {
            if ($('#vendernegativocheck').val() == 1) {
                $('#vendernegativocheck').val(0);
            } else {
                $('#vendernegativocheck').val(1);
            }
        });

        let data_impuesto = <?php echo $data['data']['impuesto'] ? $data['data']['impuesto'] : 1; ?>

        $.ajax({
            url: "<?php echo site_url("productos/impuesto_valor"); ?>",
            type: "GET",
            dataType: "json",
            data: {id_impuesto: data_impuesto},
            success: function (data) {
                $("#impue").val(data.porciento);
                if(__decimales__==0){
                    $("#precio_venta_final").val(
                        Math.round(parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val()))
                    );
                }
                else{
                    if(__decimales__>0){
                        $("#precio_venta_final").val(
                            redondear(parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val()))
                        );

                    }
                }
            }
        });
    });

    $('input[name="tipo_producto_id"').change(function () {
        event.preventDefault();
        if ($(this).is(":checked")) {
            set_tabla_tipo_producto($(this).val());
        }
    });

    function set_tabla_tipo_producto(value){
        var table = '';
        var legenda = '';
        $('#table-ingredientes').fadeOut();
        $('#table-combo').fadeOut();
        $('#table-seriales').fadeOut();

        switch (value) {
            case '1':
                legenda = '';
                break;

            case '2':
                table = '#table-ingredientes';
                legenda = 'Ingrese los ingredientes del producto';
                break;

            case '3':
                table = '#table-combo';
                legenda = 'Ingrese los productos del combo';
                break;
            case '4':
                table = '#table-seriales';
                legenda = 'Ingrese los seriales del producto, que seleccionara al vender';
                break;
        }

        $(table).fadeIn();
        $('#legenda-tipo-producto').html(legenda);
    }

    $(".delete-seriales").live('click', function(event) {
        $(this).parent().parent().remove();
    });

    function setProductosCombo(i, index) {
        // ------------------------------------------------------------
        // agregando datos al array para sumar el total de compra
        // ------------------------------------------------------------

        var compra = ingredients[i].precio_compra;
        var impuesto = ingredients[i].impuesto;
        var cantidad = $('input[name="productosCombo[cantidad][' + (index - 1) + ']"').val().trim();

        listaCombos[(index - 1)]["compra"] = parseFloat(compra);
        listaCombos[(index - 1)]["impuesto"] = parseFloat(impuesto);

        if (cantidad == "" || cantidad == "0000") {
            listaCombos[(index - 1)]["cantidad"] = 0;
        } else {
            listaCombos[(index - 1)]["cantidad"] = parseFloat(cantidad);
        }
        calcularPrecioCompra("comb");
        // ------------------------------------------------------------
        $('input[name="productosCombo[id][' + (index - 1) + ']"').val(ingredients[i].id);
        $('input[name="productosCombo[nombre][' + (index - 1) + ']"').val(ingredients[i].nombre);
        $('input[name="productosCombo[codigo][' + (index - 1) + ']"').val(ingredients[i].codigo);
        $('#productos-combo-list-' + (index)).html("");
        $('#productos-combo-list-' + (index)).css("display", "none");
    }

    function setIngredent(i, index) {

        // ------------------------------------------------------------
        // agregando datos al array para sumar el total de compra
        // ------------------------------------------------------------

        var compra = ingredients[i].precio_compra;
        var impuesto = ingredients[i].impuesto;
        console.log('Index '+index);
        //var cantidad = $('input[name="Ingrediente[cantidad][' + (index - 1) + ']"').val().trim();
        var cantidad = $('input[name="Ingrediente[cantidad][' + (index - 1) + ']"').val();
        console.log('cantidad digitada '+cantidad);
        listaIngredientes[(index - 1)]["compra"] = parseFloat(compra);
        listaIngredientes[(index - 1)]["impuesto"] = parseFloat(impuesto);

        if (cantidad == "" || cantidad == "0000") {
            listaIngredientes[(index - 1)]["cantidad"] = 0;
        } else {
            listaIngredientes[(index - 1)]["cantidad"] = parseFloat(cantidad);
        }
        //calcularPrecioCompra("ing");
        // ------------------------------------------------------------

        $('input[name="Ingrediente[id][' + (index - 1) + ']"').val(ingredients[i].id);
        $('input[name="Ingrediente[nombre][' + (index - 1) + ']"').val(ingredients[i].nombre);
        $('input[name="Ingrediente[codigo][' + (index - 1) + ']"').val(ingredients[i].codigo);
        $('#ingredients-list-' + (index)).html("");
        $('#ingredients-list-' + (index)).css("display", "none");

    }

    function removeDetail(index) {

        listaIngredientes[(index)]["compra"] = 0;
        listaIngredientes[(index)]["impuesto"] = 0;
        listaIngredientes[(index)]["cantidad"] = 0;

        calcularPrecioCompra("ing");

        $('input[name="Ingrediente[id][' + (index) + ']"').val("");
        $('input[name="Ingrediente[nombre][' + (index) + ']"').val("");
        $('input[name="Ingrediente[codigo][' + (index) + ']"').val("0000");
        $('input[name="Ingrediente[cantidad][' + (index) + ']"').val("0000");
        $('#ingredients-list-' + (index + 1)).html("");
        $('#ingredients-list-' + (index + 1)).css("display", "none");
    }

    function removeDetailCom(index) {
        listaCombos[(index)]["compra"] = 0;
        listaCombos[(index)]["impuesto"] = 0;
        listaCombos[(index)]["cantidad"] = 0;

        calcularPrecioCompra("comb");

        $('input[name="productosCombo[id][' + (index) + ']"').val("");
        $('input[name="productosCombo[nombre][' + (index) + ']"').val("");
        $('input[name="productosCombo[codigo][' + (index) + ']"').val("0000");
        $('input[name="productosCombo[cantidad][' + (index) + ']"').val("0000");
        $('#productos-combo-list-' + (index + 1)).html("");
        $('#productos-combo-list-' + (index + 1)).css("display", "none");
    }

    function removeDetailIng(index) {
        listaIngredientes[(index)]["compra"] = 0;
        listaIngredientes[(index)]["impuesto"] = 0;
        listaIngredientes[(index)]["cantidad"] = 0;

        calcularPrecioCompra("ing");

        $('input[name="Ingrediente[id][' + (index) + ']"').val("");
        $('input[name="Ingrediente[nombre][' + (index) + ']"').val("");
        $('input[name="Ingrediente[codigo][' + (index) + ']"').val("0000");
        $('input[name="Ingrediente[cantidad][' + (index) + ']"').val("0000");
        $('#ingredients-list-' + (index + 1)).html("");
        $('#ingredients-list-' + (index + 1)).css("display", "none");
    }

    function agregar_campos_tablas_productos(id_tabla) {
        var rowO = $("table#"+id_tabla+" #detalle tr").last();
        var rowN = rowO.clone(true);

        rowN.find('input').val('');
        rowN.find('input#cod-productos-combo').val('0000');

        if(id_tabla ==='table-ingredientes'){
                var idi_nuevo = Number($(rowN).find(".ingreCantidad").attr("idi"))+1;
                listaIngredientes[idi_nuevo] = {compra:0, impuesto:0, cantidad:0};
                $(rowN).find(".ingreCantidad").attr("idi",idi_nuevo);
                rowN.find('a.button').attr('onclick','removeDetailIng('+idi_nuevo+')');

        }else if(id_tabla === 'table-combo'){
                var idc_nuevo = Number($(rowN).find(".combosCantidad").attr("idc"))+1;
                listaCombos[idc_nuevo] = {compra:0, impuesto:0, cantidad:0};
                $(rowN).find(".combosCantidad").attr("idc",idc_nuevo);
                rowN.find('a.button').attr('onclick','removeDetailCom('+idc_nuevo+')');
        }else if(id_tabla === 'table-seriales'){

            var serial = ($(rowN).find("#input-serial"));
            $(rowN).find("#input-serial").removeAttr("readonly");
            $(rowN).find("#input-serial").removeAttr("value");
            console.log($(rowN).find("#input-serial")[0].attributes.value);
        }

        $("table#"+id_tabla+" #detalle").append(rowN);

        $("table#"+id_tabla+" #detalle tr").each(function(i, e) {
            $(this).find("ul").each(function(){
                var index = parseInt($(this).attr("id").replace(/[^\d]/g, '').replace(/^\s+|\s+$/g,""));
                $(this).attr("id", $(this).attr("id").replace(index, Number(i)+1));
            });

            $(this).find(":input").each(function(j, o){
                var index = parseInt($(this).attr("name").replace(/[^\d]/g, '').replace(/^\s+|\s+$/g,""));
                if(typeof $(this).attr("name") !== "undefined" ){
                    $(this).attr("name", $(this).attr("name").replace(index, i));
                    if($(this).hasClass('combosCantidad')){
                        $(this).attr("idc", $(this).attr("idc").replace(index, i));
                        $(this).parent().append("<input type='hidden' name='precioIngrediente["+i+"]'>");
                    }
                }
            });
        });
    }
</script>

<?php if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015'): ?>
    <script>
        $("#prueba").mousedown(function () {
            $.post("<?php echo base_url("index.php/RestFullController/Rest") ?>", {
                producto_id: $("input[name*='id']").val(),
                actual: $("input[name*='Stock[3]']").attr('data-stock_actual'),
                agregado: $("input[name*='Stock[3]']").val(),
                codigoS: $("input[name*='id']").attr('data-codigoS'),
                almacen_id: $("input[name*='Stock[3]']").attr('data-almacen_id')
            });
        });
    </script>
<?php endif; ?>

<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<script>
    $(document).on('blur', '.dataMoneda', function () {
        $(this).val(limpiarCampo($(this).val()));
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

        <h2><?php echo custom_lang('sima_new_product', "Nuevo Producto"); ?></h2>                                          

    </div>

</div>
<div id='mensagge_warning' class="alert alert-error" style="display:none">El código suministrado ya pertenece a un producto</div>
<div id='mensagge_warning2' class="alert alert-error" style="display:none">El código de Puntos Leal suministrado ya pertenece a un producto</div>
<div class="row-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" id="video1" class="active"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Información</a></li>
        <li role="presentation"><a id="video" href="#img" aria-controls="profile" role="tab" data-toggle="tab">Imagenes</a></li>
    </ul>
    <!-- Tab panes -->
    <?php echo form_open_multipart("productos/nuevo", array("id" => "validate")); ?>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="info">
                <div class="col-md-6">
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>
                                <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" id="nombre" />
                                    <?php echo form_error('nombre'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?>:</div>
                                <div class="span9"><input type="text" id="codigo"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />
                                    <?php echo form_error('codigo'); ?>
                                </div>
                            </div>
                            <?php if (get_option('puntos_leal') === 'si') { ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo_puntos_leal', "C&oacute;digo Puntos Leal"); ?>:</div>
                                    <div class="span9"><input type="text" id="codigo_puntos_leal"  value="<?php echo set_value('codigo_puntos_leal'); ?>" placeholder="" name="codigo_puntos_leal" />
                                        <?php echo form_error('codigo_puntos_leal'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('price_of_purchase', "Precio de compra"); ?>:</div>
                                <div class="span9"><input class="dataMoneda1" type="text" value="0" name="precio_compra"  id="precio_compra"  placeholder=""/>
                                    <?php echo form_error('precio_compra'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta"); ?>:</div>
                                <div class="span9"><input class="dataMoneda" type="text" value="0" name="precio" id="precio_venta" placeholder="" readonly="readonly"/>
                                    <?php echo form_error('precio'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto"); ?>:</div>
                                <div class="span9">
                                    <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto'), "id='id_impuesto'"); ?>
                                    <?php echo form_error('id_impuesto'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta con impuesto"); ?>:</div>
                                <div class="span9"><input class="dataMoneda" type="text" value="0" name="precio_final" id="precio_venta_final" />
                                    <input type="hidden" value="" name="impue" id="impue" readonly="readonly" />
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n"); ?>:</div>
                                <div class="span9"><textarea name="descripcion" placeholder=""><?php echo set_value('descripcion'); ?></textarea>
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
                                            echo '<option value="' . $proveedor['id_proveedor'] . '">' . $proveedor['nombre_comercial'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sale_active', "Activo"); ?>:</div>
                                <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo'); ?>" <?php echo "1" == set_value('activo', 1) ? "checked='checked'" : ""; ?> />
                                <?php echo form_error('activo'); ?>
                                </div>
                            </div>
                            <div class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_tienda', "Tienda"); ?>:</div>
                                <div class="span9"><input id="tiendacheck" name="tienda" type="checkbox" value="1" <?php echo "1" == set_value('tienda', 1) ? "checked='checked'" : ""; ?> />
                                <?php echo form_error('tienda'); ?>
                                </div>
                            </div>
                            <div id="vendernegativo" class="row-form">
                                <div class="span3"><?php echo custom_lang('sima_vendernegativo', "Permite vender en negativo"); ?>:</div>
                                <div class="span9"><input id="vendernegativocheck" name="vendernegativo" type="checkbox" checked value="1"/>
                                <?php echo form_error('vendernegativo'); ?>
                                </div>
                            </div>

                            <div class="row-form">
                                <div class="span3">Es ingrediente:</div>
                                <div class="span9"><input name="is_ingrediente" type="checkbox" value="1" style="opacity: 0;"></div>
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
                                        <?php $is_admin = $this->session->userdata('is_admin');
                                        foreach ($data['almacenes'] as $key => $value) :                                                
                                                $desactivado="";   
                                                if(!empty($data['almacenes_inactivo'])){                                           
                                                    if (array_key_exists($key, $data['almacenes_inactivo'])) {
                                                        $desactivado='readonly';                                                                                                  
                                                    }
                                                }
                                            ?>
                                            <?php if ($is_admin == 't') {  ?>                                                 
                                                <tr> 
                                                    <td><?php echo $value; ?></td><td><input <?=$desactivado?> name="Stock[<?php echo $key; ?>]" min="0" type="text" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>
                                                </tr>            
                                        <?php } ?>       
                                        <?php if ($is_admin != 't') { ?>   
                                                <?php if ($data['almacenes_id'] == $key) { ?>
                                                    <tr> 
                                                        <td><?php echo $value; ?></td><td><input <?=$desactivado?> name="Stock[<?php echo $key; ?>]" min="0" type="text" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr> 
                                                        <td></td><td><input <?=$desactivado?> name="Stock[<?php echo $key; ?>]" min="0" type="hidden" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>
                                                    </tr>
                                                <?php } ?>   
                                        <?php } ?>  
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                </div>
                <div class="col-md-6">
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_category', "Tipo producto"); ?>:</div>
                            <div class="span3">
                                <?php
                                foreach ($data['tipo_productos'] as $key => $value) {
                                    if($value->id < 5){
                                        if ($value->id == 1)
                                        echo "  <input checked='checked' type='radio' id='rb-tipo-producto-" . $value->id . "' name='tipo_producto_id' value=" . $value->id . ">" . ($value->nombre) . "<br>";
                                        else
                                            echo "  <input type='radio' id='rb-tipo-producto-" . $value->id . "' name='tipo_producto_id' value=" . $value->id . ">" . ($value->nombre) . "<br>";    
                                    }
                                }
                                ?>
                                <input type='hidden' name='tipo_producto' value='1'>
                                <!--  <select name='tipo_producto_id' onchange='setTipoProducto(this)'>
                                            <?php
                                            /*  foreach ($data['tipo_productos'] as $key => $value) {
                                                echo "<option value='".$value->id."'>".($value->nombre)."</option>";
                                                } */
                                            ?>
                                </select> -->
                                <?php echo form_error('tipo_producto_id'); ?>
                            </div>
                        <div class="span2"><?php echo custom_lang('sima_category', "Categoría"); ?>:</div>
                        <div class="span4">
                            <select name='categoria_id'>
                                <?php
                                foreach ($data['categorias'] as $key => $value) {
                                    echo "<option value='" . $value->id . "'>" . ($value->nombre) . "</option>";
                                }
                                ?>
                            </select>
                            <?php echo form_error('categoria_id'); ?>
                        </div>
                        <div class="span2"><?php echo custom_lang('sima_unit', "Unidad"); ?>:</div>
                        <div class="span4">
                            <select name='unidad_id'>
                                <?php
                                foreach ($data['unidades'] as $key => $value) {
                                    echo "<option value='" . $value->id . "'>" . ($value->nombre) . "</option>";
                                }
                                ?>
                            </select>
                             <?php echo form_error('categoria_id'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="row-form"><button name="mostrar" type="button" class="btn btn-success" id="mostrar">Ver información adicional <i class="icon icon-arrow-down icon-white"></i></button></div>
                    <div id="uno">
                        <div class="row-form">
                            <div class="span4"><?php echo custom_lang('sima_unit', "Ganancia"); ?>:</div>
                            <div class="span2"><input type="text" value="<?php echo set_value('ganancia'); ?>" name="ganancia" id="ganancia" placeholder=""/></div>
                            <div class="span2"> % </div>
                        </div>  
                        <div class="row-form">
                            <div class="span4"><?php echo custom_lang('sima_unit', "Fecha de vencimiento"); ?>:</div>
                            <div class="span6"><input type="text" value="<?php echo set_value('fecha_vencimiento'); ?>" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="" class="datepicker" />  </div>
                        </div>
                        <div class="row-form">
                            <div class="span4"><?php echo custom_lang('sima_unit', "Stock Mínimo"); ?>:</div>
                            <div class="span6"><input type="text" value="<?php echo set_value('stock_minimo'); ?>" name="stock_minimo" id="stock_minimo" placeholder=""/>  </div>
                        </div>
                        <div class="row-form">
                            <div class="span4"><?php echo custom_lang('sima_unit', "Stock Máximo"); ?>:</div>
                            <div class="span6"><input type="text" value="<?php echo set_value('stock_maximo'); ?>" name="stock_maximo" id="stock_maximo"  placeholder=""/>  </div>
                        </div>
                        <div class="row-form">
                            <div class="span4"><?php echo custom_lang('sima_unit', "Ubicaci&oacute;n del producto"); ?>:</div>
                            <div class="span6"><input type="text" value="<?php echo set_value('ubicacion'); ?>" name="ubicacion" id="ubicacion" placeholder=""/>  </div>
                        </div>
                        <?php
                        if(!empty($data['lista_precios']))
                        {
                            ?>
                            <hr />
                            <div class="row-form">
                                <div class="span10"><?php echo custom_lang('sima_unit', "Listas de precios:"); ?></div>
                            </div> 
                            <div class="row-form">
                            <?php
                            //var_dump($data['lista_precios']);
                            foreach($data['lista_precios'] as $key => $lp) 
                            {
                                ?>
                                    <div class="span1"><input type="checkbox" name="lista_precios[]" value="<?php echo $lp['id'] ?>"></div>
                                    <div class="span4"><?php echo custom_lang('sima_unit', $lp['nombre']); ?></div>
                                    <?php
                                    if(($key +1) % 2 == 0)
                                    {
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

                                <th width="15%">Codigo</th>
                                <th width="50%">Materiales</th>
                                <th width="35%">Cantidad</th>

                            </tr>

                        </thead>

                        <tbody id="detalle">

                            <?php
                            for ($i = 0; $i < 10; $i++) {
                                echo ' 
                            <tr>
                                <td width="10">
                                    <input type="hidden" name="Ingrediente[id][' . $i . ']" class="id-ingredient" id="id-ingredient" >
                                </td>
                                <td>
                                    <input type="text" name="Ingrediente[codigo][' . $i . ']" id="cod-ingredient" value="0000" disabled="" >
                                </td>
                                <td>
                                    <div class="input-prepend input-append">
                                        <input class="ingredientes" type="text" placeholder="Nombre" name="Ingrediente[nombre][' . $i . ']" id="ingredient" style="width: 207px;"  autocomplete="off">     
                                        <span class="add-on green"><i class="icon-search icon-white"></i></span>
                                    </div>
                                    <ul id="ingredients-list-' . ($i + 1) . '" class="autocomplete"> </ul>
                                </td>
                                <td>
                                    <input idI="'. $i .'" type="text" class="cantidadIngrediente ingreCantidad" name="Ingrediente[cantidad][' . $i . ']" style="text-align:right" id="quantity" >
                                    <input type="hidden" name="precioIngrediente[' . $i . ']">
                                    <span id="cant-error"></span>
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

                                <th width="15%">Codigo</th>
                                <th width="50%">Productos</th>
                                <th width="35%">Cantidad</th>

                            </tr>

                        </thead>

                        <tbody id="detalle">

                            <?php
                            for ($i = 0; $i < 10; $i++) {
                                echo ' 
                            <tr>
                                <td width="10">
                                    <input type="hidden" name="productosCombo[id][' . $i . ']" class="id-productos-combo" id="id-productos-combo" >
                                </td>

                                <td>
                                    <input type="text" name="productosCombo[codigo][' . $i . ']" id="cod-productos-combo" value="0000" disabled="" >
                                </td>

                                <td>
                                    <div class="input-prepend input-append">
                                        <input type="text" placeholder="Nombre" name="productosCombo[nombre][' . $i . ']" id="producto-combo" style="width: 207px;"  autocomplete="off">     
                                        <span class="add-on green"><i class="icon-search icon-white"></i></span>
                                    </div>
                                    <ul id="productos-combo-list-' . ($i + 1) . '" class="autocomplete"> </ul>
                                    <!--  
                                    <span id="product-error"></span> -->

                                </td>


                                <td>
                                    <input class="combosCantidad" idC="'. $i .'" type="text" name="productosCombo[cantidad][' . $i . ']" style="text-align:right" id="quantity" >

                                    <span id="cant-error"></span>

                                </td>             

                            </tr>';
                            } ?>

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
                        <?php for ($i = 0; $i < 10; $i++) { ?>
                                <tr>
                                    <td>
                                        <input type="text" name="seriales_producto[<?php echo $i ?>]" placeholder ="Digite el serial">
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
            <div role="tabpanel" class="tab-pane" id="img">
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
                        </div>
                    </div>

                    <div class="row-form span4">
                        <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:
                        </div>
                        <div class="span9">
                            <div class="input-append file">
                                <input type="file" name="imagen1" class="img-add"/>
                                <input type="text"  class="img-add"/>
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                            <a class="selectFromGallery imagen-1 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        </div>
                    </div>
                    <div class="row-form span4">
                        <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                        </div>
                        <div class="span9">
                            <div class="input-append file">
                                <input type="file" class="img-add" name="imagen2"/>
                                <input type="text" class="img-add"/>
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                            <a class="selectFromGallery imagen-2 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        </div>
                    </div>
                </div>
                <div class="span12" style="margin: 10px 0px 0px 0px;">
                    <div class="row-form span4">
                        <div class="span4"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                        </div>
                        <div class="span8">
                            <div class="input-append file">
                                <input type="file" class="img-add" name="imagen3"/>
                                <input type="text" class="img-add"/>
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                            <a class="selectFromGallery imagen-3 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        </div>
                    </div>
                    <div class="row-form span4">
                        <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                        </div>
                        <div class="span9">
                            <div class="input-append file">
                                <input type="file" class="img-add" name="imagen4"/>
                                <input type="text" class="img-add"/>
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                            <a class="selectFromGallery imagen-4 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        </div>
                    </div>
                    <div class="row-form span4">
                        <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                        </div>
                        <div class="span9">
                            <div class="input-append file">
                                <input type="file" class="img-add" name="imagen5"/>
                                <input type="text" class="img-add"/>
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                            <a class="selectFromGallery imagen-5 text-primary" href="javascript:void(false);">Seleccionar desde la galería</a> 
                        </div>
                    </div>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="pull-right">
            <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
            <button class="btn btn-success"  id="prueba"    type="button"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
            
        </div>
            
     
        
    </form>

</div>

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
      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->
    <div class="myImageFinderModal"></div>

<style type="text/css">

    .img-add{
        width: 90% !important;
    }

    #table-ingredientes,#table-combo,#table-seriales{
        display: none;
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
        cursor:pointer;
        cursor: hand;
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

    var regex = /[\;\`\'\*\~\´\¨]/;
    $('#nombre').keyup(function(event) {
        var str = $(this).val();
        if (regex.exec(str) !== null) {
            $(this).val(str.substr(0, (str.length - 1)));
        }
    });

    
    var listaIngredientes = {
        0:{compra:0,impuesto:0,cantidad:0},
        1:{compra:0,impuesto:0,cantidad:0},
        2:{compra:0,impuesto:0,cantidad:0},
        3:{compra:0,impuesto:0,cantidad:0},
        4:{compra:0,impuesto:0,cantidad:0},
        5:{compra:0,impuesto:0,cantidad:0},
        6:{compra:0,impuesto:0,cantidad:0},
        7:{compra:0,impuesto:0,cantidad:0},
        8:{compra:0,impuesto:0,cantidad:0},
        9:{compra:0,impuesto:0,cantidad:0},        
        /*10:{compra:0,impuesto:0,cantidad:0},        
        11:{compra:0,impuesto:0,cantidad:0},       
        12:{compra:0,impuesto:0,cantidad:0},      
        13:{compra:0,impuesto:0,cantidad:0},        
        14:{compra:0,impuesto:0,cantidad:0},        
        15:{compra:0,impuesto:0,cantidad:0},        
        16:{compra:0,impuesto:0,cantidad:0},        
        17:{compra:0,impuesto:0,cantidad:0},        
        18:{compra:0,impuesto:0,cantidad:0},        
        19:{compra:0,impuesto:0,cantidad:0},        
        20:{compra:0,impuesto:0,cantidad:0},        
        21:{compra:0,impuesto:0,cantidad:0},        
        22:{compra:0,impuesto:0,cantidad:0},        
        23:{compra:0,impuesto:0,cantidad:0},        
        24:{compra:0,impuesto:0,cantidad:0},        
        25:{compra:0,impuesto:0,cantidad:0},        
        26:{compra:0,impuesto:0,cantidad:0},        
        27:{compra:0,impuesto:0,cantidad:0},        
        28:{compra:0,impuesto:0,cantidad:0},        
        29:{compra:0,impuesto:0,cantidad:0},        
        30:{compra:0,impuesto:0,cantidad:0},*/   
    };
    
    //console.log( listaIngredientes );
    
    var listaCombos = {
        0:{compra:0,impuesto:0,cantidad:0},
        1:{compra:0,impuesto:0,cantidad:0},
        2:{compra:0,impuesto:0,cantidad:0},
        3:{compra:0,impuesto:0,cantidad:0},
        4:{compra:0,impuesto:0,cantidad:0},
        5:{compra:0,impuesto:0,cantidad:0},
        6:{compra:0,impuesto:0,cantidad:0},
        7:{compra:0,impuesto:0,cantidad:0},
        8:{compra:0,impuesto:0,cantidad:0},
        9:{compra:0,impuesto:0,cantidad:0}        
    };
    
    $("#video").click(function () {   
        $(".social").css('display','block');
    });
    $("#video1").click(function () {   
        $(".social").css('display','none');
    });

    $(".ingreCantidad").live("keyup",function () {
        var index = $(this).attr( "idI" );
        var codigo = $('input[name="Ingrediente[codigo][' + (index) + ']"').val();
        
        if( codigo == "0000"){
            listaIngredientes[(index)]["cantidad"] = 0;
        }else{
            listaIngredientes[(index)]["cantidad"] = parseFloat( $(this).val() );
        }                
        
        calcularPrecioCompra("ing"); 
    });
    
    $(".combosCantidad").keyup(function () {        
        
        var index = $(this).attr( "idC" );
        var codigo = $('input[name="productosCombo[codigo][' + (index) + ']"').val();
        
        if( codigo == "0000"){
            listaCombos[(index)]["cantidad"] = 0;
        }else{
            listaCombos[(index)]["cantidad"] = parseFloat( $(this).val() );
        }  
        
        calcularPrecioCompra("comb");

    });

    /*modificar cantidad en ingredientes*/
   /* $(document).on('keyup','input.cantidadIngrediente',function(){
        /*var $this = $(this),
            precio = limpiarCampo($this.parent().find('input[type=hidden]').val()),
            cantidad = limpiarCampo($this.val()),
            precioVenta = limpiarCampo($('#precio_venta').val()),
            precioCompra = limpiarCampo($('#precio_compra').val());
        console.log(fijarNumero(precioCompra)+"---"+fijarNumero(precioVenta));
        precioCompra += limpiarCampo(precio * cantidad);
        $('#precio_venta').val(precioCompra);*/
     /*   var $this = $(this),
            precio = parseFloat($this.parent().find('input[type=hidden]').val()),
            cantidad = parseFloat($this.val()),
            precioVenta = parseFloat($('#precio_venta').val()),
            precioCompra = parseFloat($('#precio_compra').val());
        console.log(fijarNumero(precioCompra)+"---"+fijarNumero(precioVenta));
        precioCompra += parseFloat(precio * cantidad);
        $('#precio_venta').val(precioCompra);
    }); */
    
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
            
            url = "<?php echo site_url("ingredientes/filtro?filter="); ?>" + filter;
            list = '#ingredients-list';
            action = 'setIngredent';

        } else {
            var producto_nombre = element_name.split('productosCombo[nombre]');

            index = ((element_name.split('productosCombo[nombre]')[1]).replace("[", "")).replace("]", "");
            index = parseInt(index) + 1;
            filter = $(this).val();
            codigo = $("#codigo").val();
            url = "../combos/filtro?filter=" + filter + '&codigo=' + codigo;
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

    window.onload = function () {
        document.getElementById('uno').style.display = 'none';
    }


    function calcularPrecioCompra( tipo ){
        
        var total = 0;
        if( tipo == "unico"){
            total = 0;
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

        console.log('calculando precio');
            console.log(arreglo_calcular);
            console.log(id_tabla)
            for (var i = 0,max = $("table#"+id_tabla+" #detalle tr").length; i < max; i++) {
                    if(Number(arreglo_calcular[i].cantidad)>0){
                        console.log('dentro del ciclo, cantidad > 0 posicion:'+i);
                        console.log('Cantidad a operar '+arreglo_calcular[i].cantidad);
                        console.log('compra '+arreglo_calcular[i].compra);
                        total = total + Number((arreglo_calcular[i].compra * arreglo_calcular[i].cantidad));    
                    }
            }       

        }
        
        $("#precio_compra").val( total );

    }


    function formatDollar(num) {
        num = parseFloat(num);
        (num % 1 == 0) ? p = num.toFixed(0) : p = num.toFixed(3);
        return p;
    }


    function setTipoProducto(element) {

        var table = '';
        var legenda = '';
        $('#table-ingredientes').fadeOut();
        $('#table-combo').fadeOut();

        tipo_producto = element.selectedIndex;

        switch (tipo_producto) {
            case 0:
                legenda = '';
                calcularPrecioCompra( "unico" );
                break;

            case 1:
                table = '#table-ingredientes';
                legenda = 'Ingrese los ingredientes del producto';
                calcularPrecioCompra( "ing" );
                break;

            case 2:
                table = '#table-combo';
                legenda = 'Ingrese los productos del combo';
                calcularPrecioCompra( "comb" );
                break;
        }

        $(table).fadeIn();
        $('#legenda-tipo-producto').html(legenda);


    }

    /*Rutina para poder verificar por ajax que no este registrado el código del producto previamente
     *Recibe en success "1", si el código ya existe, muestra mensaje de error
     *Recibe en success "0", si el código no existe, elimina el mensaje de error enc caso que ya exista
     *Envia el formulario 
     */
    $().ready(function () {
        $("#prueba").click(function () {
            document.getElementById("prueba").disabled = true;
            $.ajax({
                url: "<?php echo site_url("productos/validateCodigo"); ?>",
                type: "POST",
                dataType: "json",
                data: {codigo: $("#codigo").val()},
                success: function (data) {
                    if (data != 0) {
                        document.getElementById('mensagge_warning').style.display = 'inline-block';
                        document.getElementById("codigo").focus();
                        document.getElementById("prueba").disabled = false;
                    } else {
                        document.getElementById('mensagge_warning').style.display = 'none';
                        $.ajax({
                            url: "<?php echo site_url("productos/validateCodigoPuntosLeal"); ?>",
                            type: "POST",
                            dataType: "json",
                            data: {codigo_puntos_leal: $("#codigo_puntos_leal").val()},
                            success: function (data) {
                                if (data != 0) {
                                    document.getElementById('mensagge_warning2').style.display = 'inline-block';
                                    document.getElementById("codigo_puntos_leal").focus();
                                    document.getElementById("prueba").disabled = false;
                                } else {
                                    document.getElementById('mensagge_warning2').style.display = 'none';
                                    $("#validate").submit();  //Para enviar el formulario
                                }
                            }
                        });
                    }
                }
            });
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

                $("#precio_venta_final").attr('disabled', true);
                $("#precio_venta_final").val(
                    parseFloat(parseFloat($("#precio_venta").val()) * parseFloat($("#impue").val()) / 100 + parseFloat($("#precio_venta").val()))
                );
                
            } else {
                $("#precio_venta_final").attr('disabled', false);
            }
        });
    
    $(document).ready(function () {
        $("#tiendacheck").click(function () {
            if ($('#tiendacheck').val() == 1) {
                $('#tiendacheck').val(0);
            } else {
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

        $.ajax({
            url: "<?php echo site_url("productos/impuesto_valor"); ?>",
            type: "GET",
            dataType: "json",
            data: {id_impuesto: parseFloat($("#id_impuesto").val())},
            success: function (data) {
                $("#impue").val(data.porciento);
            }
        });
    });

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

    //importante!
    $("#precio_venta_final").keyup(function (e) {
        if (parseFloat($("#precio_venta_final").val()) >= 0) {
            $("#precio_venta").val(parseFloat(parseFloat($("#precio_venta_final").val()) / ((parseFloat($("#impue").val()) / 100) + 1)).toFixed(2));
            $("#temp") = $("#precio_venta").val(parseFloat($("#precio_venta_final").val()).toFixed(2));
            $("#precio_venta_final") = $("#precio_venta_final") + $("#temp");
        }
    });

    $('input[name="tipo_producto_id"').change(function () {
        event.preventDefault();
        if ($(this).is(":checked")) {

            var table = '';
            var legenda = '';
            $('#table-ingredientes').fadeOut();
            $('#table-combo').fadeOut();
            $('#table-seriales').fadeOut();
            //console.log($(this).val());
            switch ($(this).val()) {
                case '1':
                    legenda = '';
                    $('input[name="tipo_producto"').val(1);
                    break;

                case '2':
                    table = '#table-ingredientes';
                    legenda = 'Ingrese los ingredientes del producto';
                    $('input[name="tipo_producto"').val(2);
                    break;

                case '3':
                    table = '#table-combo';
                    legenda = 'Ingrese los productos del combo';
                    $('input[name="tipo_producto"').val(3);
                    break;

                case '4':
                   table = '#table-seriales';
                   legenda = 'Ingrese los seriales del producto, que seleccionara al vender';
                   $('input[name="tipo_producto"]').val(4);
                   break;    
            }

            $(table).fadeIn();
            $('#legenda-tipo-producto').html(legenda);

        }

    });

     $(".delete-seriales").live('click', function(event) {
        $(this).parent().parent().remove();
    });


    function setProductosCombo(i, index) {
        
        // ------------------------------------------------------------
        // agregando datos al array para sumar el total de compra
        // ------------------------------------------------------------
        console.log('setProductosCombo');
        //console.log(ingredients[i]);
        var compra = ingredients[i].precio_compra;
        var impuesto = ingredients[i].impuesto;
        var cantidad = $('input[name="productosCombo[cantidad][' + (index - 1) + ']"').val().trim();
        console.log(index);        
        listaCombos[(index - 1)]["compra"] = parseFloat(compra);
        listaCombos[(index - 1)]["impuesto"] = parseFloat(impuesto);
                
        if( cantidad == "" || cantidad == "0000"){
            listaCombos[(index - 1)]["cantidad"] = 0;
        }else{
            listaCombos[(index - 1)]["cantidad"] = parseFloat(cantidad);
        }
        console.log(compra);
        console.log(impuesto);
        console.log(cantidad);
        calcularPrecioCompra("comb"); 
        // ------------------------------------------------------------


        $('input[name="productosCombo[id][' + (index - 1) + ']"').val(ingredients[i].id);
        $('input[name="productosCombo[nombre][' + (index - 1) + ']"').val(ingredients[i].nombre);
        $('input[name="productosCombo[codigo][' + (index - 1) + ']"').val(ingredients[i].codigo);
        $('#productos-combo-list-' + (index)).html("");
        $('#productos-combo-list-' + (index)).css("display", "none");

    }

    function setIngredent(i, index) {

        console.log('dentro de setIngredent');
        // ------------------------------------------------------------
        // agregando datos al array para sumar el total de compra
        // ------------------------------------------------------------
        
        var compra = ingredients[i].precio_compra;
        var impuesto = ingredients[i].impuesto;
        var cantidad = $('input[name="Ingrediente[cantidad][' + (index - 1) + ']"').val().trim();
                
        listaIngredientes[(index - 1)]["compra"] = parseFloat(compra);
        listaIngredientes[(index - 1)]["impuesto"] = parseFloat(impuesto);
                
        if( cantidad == "" || cantidad == "0000"){
            listaIngredientes[(index - 1)]["cantidad"] = 0;
        }else{
            listaIngredientes[(index - 1)]["cantidad"] = parseFloat(cantidad);
        }            
        // calcularPrecioCompra("ing");                        
        // ------------------------------------------------------------
        
        $('input[name="Ingrediente[id][' + (index - 1) + ']"').val(ingredients[i].id);
        $('input[name="Ingrediente[nombre][' + (index - 1) + ']"').val(ingredients[i].nombre);
        $('input[name="Ingrediente[codigo][' + (index - 1) + ']"').val(ingredients[i].codigo);
        $('input[name="precioIngrediente[' + (index - 1) + ']"').val(fijarNumero(ingredients[i].precio_venta));
        $('#ingredients-list-' + (index)).html("");
        $('#ingredients-list-' + (index)).css("display", "none");
    }


   /* $(document).on("click", "#add_ingrediente", function(){
       
        var rowO = $("table#table-ingredientes #detalle tr").last()
        var rowN = rowO.clone(true);
        var idi_nuevo = Number($(rowN).find(".ingreCantidad").attr("idi"))+1;
        $(rowN).find(".ingreCantidad").attr("idi",idi_nuevo);
        rowN.find('input').val('');
        rowN.find('input#cod-productos-combo').val('0000');
        $("table#table-ingredientes #detalle").append(rowN);
         listaIngredientes[idi_nuevo] = {compra:0, impuesto:0, cantidad:0};
         
        $("table#table-ingredientes #detalle tr").each(function(i, e){            
           
            
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
       
    }); */

    function agregar_campos_tablas_productos(id_tabla){

        var rowO = $("table#"+id_tabla+" #detalle tr").last();
        var rowN = rowO.clone(true);
        
        
        rowN.find('input').val('');
        rowN.find('input#cod-productos-combo').val('0000');
        $("table#"+id_tabla+" #detalle").append(rowN);

        if(id_tabla ==='table-ingredientes'){
                var idi_nuevo = Number($(rowN).find(".ingreCantidad").attr("idi"))+1; 
                listaIngredientes[idi_nuevo] = {compra:0, impuesto:0, cantidad:0};   
                $(rowN).find(".ingreCantidad").attr("idi",idi_nuevo);
        }else if(id_tabla === 'table-combo'){
                var idc_nuevo = Number($(rowN).find(".combosCantidad").attr("idc"))+1;
                listaCombos[idc_nuevo] = {compra:0, impuesto:0, cantidad:0};
                $(rowN).find(".combosCantidad").attr("idc",idc_nuevo);
        }
         
        $("table#"+id_tabla+" #detalle tr").each(function(i, e){            
           
            
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
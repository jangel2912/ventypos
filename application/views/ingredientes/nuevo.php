<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<script>
    $(document).on('blur', '.dataMoneda', function () {
        $(this).val(limpiarCampo($(this).val()));
    });
</script>
<div class="page-header">    
    <div class="icon">
        <img alt="Ingredientes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ingredientes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ingredientes", "Ingredientes");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_ingredient', "Nuevo Ingrediente"); ?></h2>                                          
    </div>
</div>
<div id= 'mensagge_warning' class="alert alert-error" style="display:none">El código suministrado ya pertenece a un producto</div>
<div class="row-fluid">
<?php echo form_open_multipart("ingredientes/nuevo", array("id" => "validate")); ?>
    <div class="span6">
        <div class="block">
            <div class="data-fluid">
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_category', "Categoría"); ?>:</div>
                    <div class="span9">
                        <select name='categoria_id'>
                            <?php
                            foreach ($data['categorias'] as $key => $value) {
                                echo "<option value='" . $value->id . "'>" . ($value->nombre) . "</option>";
                            }
                            ?>
                        </select>
                        <?php echo form_error('categoria_id'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_unit', "Unidad"); ?>:</div>
                    <div class="span9">
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
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_tipo_producto', "Tipo de  ingrediente"); ?>:</div>
                    <div class="span9">
                        <select name='tipo_ingrediente'>
                            <option value=""></option>
                            <option value="4">Base de producto</option>
                            <option value="5">Adicion de producto</option>
                            <option value="6">Salsa de producto</option>
                            <option value="7">Insumo de producto</option>
                        </select>
                        <?php echo form_error('tipo_ingrediente'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" id="codigo" name="codigo" />
                        <?php echo form_error('codigo'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('price_of_purchase', "Precio de compra"); ?>:</div>
                    <div class="span9">
                        <input type="text"  value="<?php echo set_value('precio_compra'); ?>" name="precio_compra" placeholder=""/>
                        <input type="hidden" value="0" name="precio" placeholder=""/>
                        <?php echo form_error('precio_compra'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto"); ?>:</div>
                    <div class="span9">
                        <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto')); ?>
                        <?php echo form_error('id_impuesto'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n"); ?>:</div>
                    <div class="span9"><textarea name="descripcion" placeholder=""><?php echo set_value('descripcion'); ?></textarea>
                        <?php echo form_error('descripcion'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sale_active', "Activo"); ?>:</div>
                    <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo'); ?>" <?php echo "1" == set_value('activo', 1) ? "checked='checked'" : ""; ?> />
                        <?php echo form_error('activo'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>
                    </div>
                    <div class="span9">                            
                        <div class="input-append file">
                            <input type="file" name="imagen"/>
                            <input type="text"/>
                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                        </div> 
                        <?php echo $data['data']['upload_error']; ?>
                    </div>
                </div>
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="50%">Almacén</th>
                                <th width="50%">Cantidad</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data['almacenes'] as $key => $value) : ?>
                                <tr>
                                    <td><?php echo $value; ?></td><td><input name="Stock[<?php echo $key; ?>]" min="0" type="number" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="toolbar bottom tar">
                    <div class="pull-right">     
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>                                   
                        <button class="btn btn-success" id="prueba" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>                        
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="span6">
        <div class="row-form white btn-success ">Información Adicional (Opcional)</div>
        <div id="uno">

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_unit', "Fecha de vencimiento"); ?>:</div>
                <div class="span6"><input type="text" value="<?php echo set_value('fecha_vencimiento'); ?>" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="" class="datepicker" />  </div>
            </div>
            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_unit', "Stock Mínimo"); ?>:</div>
                <div class="span6"><input type="text" value="<?php echo set_value('stock_minimo'); ?>" name="stock_minimo" id="stock_minimo" placeholder=""/>  </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_unit', "Ubicaci&oacute;n del producto"); ?>:</div>
                <div class="span6"><input type="text" value="<?php echo set_value('ubicacion'); ?>" name="ubicacion" id="ubicacion" placeholder=""/>  </div>
            </div>
        </div>
    </div>
    
    <?= form_close()?>
</div>

<script>
    $().ready(function () {

        $("#prueba").click(function (e) {            
            e.preventDefault();
            $("#prueba").prop('disabled',true);
            $.ajax({
                url: "<?php echo site_url("productos/validateCodigo"); ?>",
                type: "POST",
                dataType: "json",
                data: {codigo: $("#codigo").val()},
                success: function (data) {

                    if (data != 0) {
                        document.getElementById('mensagge_warning').style.display = 'inline-block';
                        document.getElementById("codigo").focus();
                        $("#prueba").prop('disabled',false);
                    } else {
                        document.getElementById('mensagge_warning').style.display = 'none';
                        $("#validate").submit();  //Para enviar el formulario
                        
                    }

                }
            });

        });
    });
$("#mostrar").click(function (e) {
        if (document.getElementById('uno').style.display == 'none') {
            document.getElementById('uno').style.display = 'block';
        } else {
            document.getElementById('uno').style.display = 'none';
        }
    });
</script>


<div class="page-header">    
    <div class="icon">
        <img alt="categorías" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_categorias']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("categorias", "Categorías");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_category', "Nueva Categoría"); ?></h2>  
    </div>
</div>
<div id= 'mensagge_warning' class="alert alert-error" style="display:none">El Nombre suministrado ya pertenece a una categoría</div>
<div id= 'mensagge_warning1' class="alert alert-error" style="display:none">El Código suministrado ya pertenece a una categoría</div>
<div class="row-fluid">

    <div class="span6">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open_multipart("categorias/nuevo", array("id" => "validate")); ?>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>

                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" id="nombre" name="nombre" />

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

                    <div class="span3"><?php echo custom_lang('sima_impresora', "Impresora"); ?>:</div>

                    <div class="span9">
                        <select name="id_impresora" >
                        <option value="0">Seleccione una impresora</option>
                        <?php 
                            foreach ($data['impresoras'] as $key => $value) {
                        ?>
                        <option value="<?= $value['id']; ?>"><?= $value['nombre']; ?></option>
                        <?php
                                
                            }
                        ?>

                        </select>
                       
                        <!--<input type="text"  value="<?php echo set_value('impresora'); ?>" placeholder="" name="impresora" />-->

                        <?php echo form_error('impresora'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sale_active', "Activo"); ?>:</div>

                    <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo'); ?>" <?php echo "1" == set_value('activo') ? "checked='checked'" : ""; ?> />

                        <?php echo form_error('activo'); ?>

                    </div>

                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sale_active', "Tienda"); ?>:</div>
                    <div class="span9"><input name="tienda" type="checkbox" value="1" <?php echo "1" == set_value('tienda') ? "checked='checked'" : ""; ?> />
                        <?php echo form_error('tienda'); ?>
                    </div>
                </div>  

                <div class="row-form" style='display:none'>

                    <div class="span3"><?php echo custom_lang('sale_active', "Atributos"); ?>:</div>

                    <div class="span9">

                        <?php echo form_multiselect('atributos[]', $atributos, set_value('atributos'), "id='ms'"); ?>

                        <?php echo form_error('atributos'); ?>

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

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sale_active', "Categoría Padre"); ?>:</div>

                    <div class="span9">

                        <?php echo form_dropdown('categorias', $data['categorias'], set_value('categorias'), "id='categorias'"); ?>

                        <?php echo form_error('categorias'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sale_active', "En el menú principal en tienda virtual?"); ?>:</div>

                    <div class="span9">

                        <?php echo form_dropdown('menu_categorias_tienda',array( 0=>'no',1=>'si'), set_value('es_menu_principal_tienda'), "id='menu_principal_tienda'"); ?>

                        <?php echo form_error('menu_categorias_tienda'); ?>

                    </div>

                </div>

                <div class="toolbar bottom tar">

                    <div class="btn-right">
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                        <button class="btn btn-success" id="prueba" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>

                    </div>

                </div>

            </div>

            </form>

        </div>

    </div>

</div>

<script>
    $(document).ready(function () {

        $("#prueba").click(function (e) {            
            e.preventDefault();
            $("#prueba").prop('disabled',true);
            $.ajax({
                url: "<?php echo site_url("categorias/validateNombreyCodigo"); ?>",
                type: "POST",
                dataType: "json",
                data: {campo:'nombre', id: $("#nombre").val()},
                success: function (data) {
                    if (data != 0) {
                        document.getElementById('mensagge_warning').style.display = 'inline-block';                        
                        $("#nombre").focus();
                        $("#prueba").prop('disabled',false);
                    } else {
                        $.ajax({
                            url: "<?php echo site_url("categorias/validateNombreyCodigo"); ?>",
                            type: "POST",
                            dataType: "json",
                            data: {campo:'codigo', id: $("#codigo").val()},
                            success: function (data) {

                                if (data != 0) {
                                    document.getElementById('mensagge_warning').style.display = 'none';
                                    document.getElementById('mensagge_warning1').style.display = 'inline-block';                        
                                    $("#codigo").focus();
                                    $("#prueba").prop('disabled',false);
                                } else {
                                    document.getElementById('mensagge_warning1').style.display = 'none';
                                    $("#validate").submit();  //Para enviar el formulario                                    
                                }
                            }
                        });                        
                    }
                }
            });

        });
    });

</script>

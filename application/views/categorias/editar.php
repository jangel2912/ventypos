<?PHP 
     $disabled = "";
     if($data['data']['nombre'] == "GiftCard"){
         $disabled = 'disabled';
     }
?>
<div class="page-header">    
    <div class="icon">
        <img alt="categorías" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_categorias']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("categorias", "Categorías");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_categoria', "Editar Categoría"); ?></h2> 
    </div>
</div>
<div id= 'mensagge_warning' class="alert alert-error" style="display:none">El Nombre suministrado ya pertenece a una categoría</div>
<div id= 'mensagge_warning1' class="alert alert-error" style="display:none">El Código suministrado ya pertenece a una categoría</div>
<div class="row-fluid">

    <div class="span6">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open_multipart("categorias/editar/" . $data['data']['id'], array("id" => "validate")); ?>

                <input type="hidden" value="<?php echo set_value('id_producto', $data['data']['id']); ?>" name="id" />

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre"); ?>:</div>

                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre', $data['data']['nombre']); ?>" <?php echo ($data['data']['nombre'] == "GiftCard")? 'readonly' : '';?> placeholder="" id="nombre" name="nombre" />

                        <?php echo form_error('nombre'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?>:</div>

                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo', $data['data']['codigo']); ?>" <?php echo ($data['data']['nombre'] == "GiftCard")? 'readonly' : '';?> placeholder="" id="codigo" name="codigo" disabled="disabled"/>

                        <?php echo form_error('codigo'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_impresora', "Impresora"); ?>:</div>

                    <div class="span9">
                        <select name="id_impresora" <?php echo ($data['data']['nombre'] == "GiftCard")? 'disabled' : '';?>>
                        
                        <?php 
                        if($data['impresora_cate_almacen']['id_impresora']==0){
                            $selected="selected";
                        }
                        else{
                            $selected="";
                        }
                        echo'<option value="0" '; if(!empty($selected)) echo $selected.'>Seleccione una impresora</option>';
                            
                            foreach ($data['impresoras'] as $key => $value) {                               
                                if($data['impresora_cate_almacen']['id_impresora']==$value['id']){
                                    $selected="selected";
                                }else{
                                    $selected="";
                                }
                        ?>
                        <option  value="<?= $value['id']; ?>" <?php if(!empty($selected)) echo $selected ; ?> ><?= $value['nombre']; ?></option>
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

                    <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo', $data['data']['activo']); ?>" <?php echo "1" == set_value('activo', $data['data']['activo']) ? "checked='checked'" : ""; ?> />

                        <?php echo form_error('activo'); ?>

                    </div>

                </div>

                <?php
                if(isset($data['data']['tienda']))
                {
                    ?>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sale_active', "Tienda"); ?>:</div>

                        <div class="span9"><input name="tienda" type="checkbox" value="1" <?php echo ($data['data']['nombre'] == "GiftCard")? 'readonly' : '';?> <?php echo "1" == set_value('tienda', $data['data']['tienda']) ? "checked='checked'" : ""; ?> />

                            <?php echo form_error('tienda'); ?>

                        </div>

                    </div>  
                    <?php 
                }?>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen"); ?>:<br/>

                    </div>

                    <div class="span9">                            

                        <div class="input-append file">

                            <input type="file" name="imagen" <?php echo ($data['data']['nombre'] == "GiftCard")? 'disabled' : '';?>/>

                            <input type="text"/>

                            <button class="btn btn-success" type="button" <?php echo ($data['data']['nombre'] == "GiftCard")? 'disabled' : '';?>><?php echo custom_lang('sima_search', "Buscar"); ?></button>



                        </div> 

                        <?php echo $data['data']['upload_error']; ?>

                        <?php if (!empty($data['data']['imagen'])): ?> 

                            <img src="<?php echo base_url("uploads/" . $data['data']['imagen']); ?>" alt="logotipo" height="100px" width="100px"/>

<?php endif; ?>

                    </div>

                </div> 

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sale_categoria_padre', "Categoria Padre"); ?>:</div>

                    <div class="span9">

                        <?php echo form_dropdown('categorias', $data['categorias'], isset($data['data']['padre'])?$data['data']['padre']:set_value('categorias'), "id='categorias' $disabled"); ?>

                        <?php echo form_error('categorias'); ?>

                    </div>

                </div>
                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sale_active', "En el menu principal en tienda virtual ?"); ?>:</div>

                    <div class="span9">
                        
                        <?php echo form_dropdown('menu_categorias_tienda',array( 0=>'no',1=>'si'), $data['data']['es_menu_principal_tienda'], "id='menu_principal_tienda' $disabled"); ?>

                        <?php echo form_error('menu_categorias_tienda'); ?>

                    </div>

                </div>

                <div class="toolbar bottom tar">
                    <div class="btn-right">
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                        <button class="btn btn-success" type="submit" id="btn_form_edit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>                        
                    </div>
                </div>

            </div>

            </form>

        </div>

    </div>



</div>

<script>

    $("#btn_form_edit").click(function(e){
        e.preventDefault();
        $("#btn_form_edit").prop('disabled',true);
        $("#categorias").removeAttr('disabled');

        if (($("#nombre").val() == '<?php echo set_value('nombre', $data['data']['nombre']); ?>')&&($("#codigo").val() == '<?php echo set_value('codigo', $data['data']['codigo']); ?>')) {
            document.getElementById('mensagge_warning').style.display = 'none';
            document.getElementById('mensagge_warning1').style.display = 'none';
            $("#validate").submit();  //Para enviar el formulario
                    
        } else {
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
        }

        //$("#validate").submit();
    })

</script>
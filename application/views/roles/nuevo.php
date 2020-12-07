<?php  
    if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] != "restaurante"){
        unset($permisos["1036"]);
        unset($permisos["1037"]);
        unset($permisos["1038"]);
    }
?>
<div class="page-header">    
    <div class="icon">
        <img alt="Roles" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_roles']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Roles", "Roles");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_rol', "Nuevo Rol");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span11">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open("roles/nuevo", array("id" =>"validate"));?>

                                <div class="row-form">

                                    <div class="span4"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                                    <div class="span10"><input type="text"  value="<?php echo set_value('nombre_rol'); ?>" placeholder="" name="nombre_rol" />

                                            <?php echo form_error('nombre_rol'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span4"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>

                                    <div class="span10"><textarea name="descripcion"></textarea>

                                            <?php echo form_error('descripcion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span4"><?php echo custom_lang('sima_permisions', "Permisos");?></div>

                                    <div class="span10">

                      <?php echo form_multiselect('permisos[]', $permisos, isset($_POST['permisos']) ? $_POST['permisos'] : array(), "id='ms'"); ?>

                                    </div>

                                </div>

                                <div class="toolbar bottom tar">

                                    <div>  
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>

<script type="text/javascript">

    $(document).ready(function(){

        $("#ms").multiSelect();

    });

    

</script>
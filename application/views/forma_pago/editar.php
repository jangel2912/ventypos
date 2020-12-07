<div class="page-header">    
    <div class="icon">
        <img alt="Formas de Pago" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_formasdepagos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Formas de Pago");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_forma', "Editar Forma de Pago");?></h2>
    </div>
</div>

<div class="row-fluid">
    <div class="block">
        <div class="data-fluid">
            <?php echo form_open("forma_pago/editar/".$forma->id, array("id" =>"validate"));?>

                <input type="hidden" name="id_fa" id="id_factura" value="<?php ?>" />
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_nombre', "Nombre");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('numero', $forma->nombre); ?>" name="nombre" <?php echo ($forma->eliminar == 0) ? "readonly":"" ?>/>
                        <?php echo form_error('nombre'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_date', "Mostrar función Datáfono");?>:</div>
                    <div class="span9">
                        <input type="checkbox" value="<?php echo "Datafono"; ?>" name="tipo" id="tipo" <?php echo ($forma->tipo == "Datafono") ? "checked":"" ?> <?php //echo ($forma->eliminar == 0) ? "disabled":"" ?>/>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_activo', "Activo");?>:</div>
                    <div class="span9">
                        <input type="checkbox"  value="1" name="activo" id="activo" <?php echo ($forma->activo == 1) ? "checked":"" ?>/>
                    </div>
                </div>
                <div class="toolbar bottom tar">
                    <button class="btn btn-default devolverRuta" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                    
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).on('click','.devolverRuta',function()
{
    location.href = "<?php echo site_url("forma_pago/index");?>";
});
</script>
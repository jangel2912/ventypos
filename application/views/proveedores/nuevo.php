<style type="text/css">

.requerido{
    color: red;
}

</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Proveedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_proveedores']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Proveedores", "Proveedores");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_provider', "Nuevo proveedor");?></h2>    
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("proveedores/nuevo", array("id" =>"validate"));?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?><span class='requerido'> * </span>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_comercial'); ?>" placeholder="" name="nombre_comercial" />

                                            <?php echo form_error('nombre_comercial'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_country', "PaÍs");?>:</div>

                                    <div class="span9">

                                            <?php echo custom_form_dropdown('pais', $data['pais'], $this->form_validation->set_value('pais'), "id='pais'");?>

                                            <?php echo form_error('pais'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_estado', "Provincia");?>:</div>

                                    <div class="span9">

                                            <?php echo form_dropdown('provincia', array(), $this->form_validation->set_value('provincia'), "id='provincia'");?>

                                            <?php echo form_error('provincia'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social");?><span class='requerido'> * </span>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('razon_social'); ?>" name="razon_social" placeholder=""/>

                                        <?php echo form_error('razon_social'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_nif', "NIF/CIF");?><span class='requerido'> * </span>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('nif_cif'); ?>" name="nif_cif" placeholder=""/>

                                        <?php echo form_error('nif_cif'); ?>

                                    </div>

                                </div>                    

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_contact', "Contacto");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('contacto'); ?>" name="contacto" placeholder=""/>

                                        <?php echo form_error('contacto'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_web', "Web");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('pagina_web'); ?>" name="pagina_web" placeholder=""/>

                                        <?php echo form_error('pagina_web'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_email', "Correo Electrónico");?> :</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('email'); ?>" name="email" placeholder=""/>

                                        <?php //echo form_error('email'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_people', "Poblaci&oacute;n");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('poblacion'); ?>" name="poblacion" placeholder=""/>

                                        <?php echo form_error('poblacion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_addres', "Direcci&oacute;n");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('direccion'); ?>" name="direccion" placeholder=""/>

                                        <?php echo form_error('direccion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_cp', "C&oacute;digo Postal");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('cp'); ?>" name="cp" placeholder=""/>

                                        <?php echo form_error('cp'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('telefono'); ?>" name="telefono" placeholder=""/>

                                        <?php echo form_error('telefono'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_movil', "Movil");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('movil'); ?>" name="movil" placeholder=""/>

                                        <?php echo form_error('movil'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_fax', "Fax");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('fax'); ?>" name="fax" placeholder=""/>

                                        <?php echo form_error('fax'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_company_type', "Tipo de Empresa");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('tipo_empresa'); ?>" name="tipo_empresa" placeholder=""/>

                                        <?php echo form_error('tipo_empresa'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_bancaria_entity ', "Entidad Bancaria");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('entidad_bancaria'); ?>" name="entidad_bancaria" placeholder=""/>

                                        <?php echo form_error('entidad_bancaria'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_number_cuenta', "N&uacute;mero de Cuenta");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('numero_cuenta'); ?>" name="numero_cuenta" placeholder=""/>

                                        <?php echo form_error('numero_cuenta'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_observaciones', "Observaciones");?>:</div>

                                    <div class="span9"><textarea name="observaciones" placeholder=""><?php echo set_value('observaciones'); ?></textarea>

                                        <?php echo form_error('observaciones'); ?>

                                    </div>

                                </div>

                                <div class="toolbar bottom tar">
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

       $("#pais").change(function(){

           load_provincias_from_pais($(this).val());

       }); 

       

       var pais = $("#pais").val();

       if(pais != ""){

           load_provincias_from_pais(pais);

       }

      

    });

    function load_provincias_from_pais(pais){

        $.ajax({

            url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",

            data: {"pais" : pais},

            dataType: "json",

            success: function(data) {

                $("#provincia").html('');

                $.each(data, function(index, element){

                    provincia = "<?php echo set_value('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }

</script>
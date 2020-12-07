<div class="page-header">    
    <div class="icon">
        <img alt="Almacenes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_almacen']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Almacenes");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_category', "Nuevo Almacén");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open("almacenes/nuevo", array("id" =>"validate"));?>

                                <?php if ( $data['data']['miempresa_data']['data']['resolucion_factura_estado'] == 'si' ) { ?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "Resolución #");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('resolucion_factura'); ?>" placeholder="" name="resolucion_factura" />

                                            <?php echo form_error('resolucion_factura'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "NIT");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nit'); ?>" placeholder="" name="nit" />

                                            <?php echo form_error('nit'); ?>

                                    </div>

                                </div>

                                <?php } ?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "Razon Social");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('razon_social'); ?>" placeholder="" name="razon_social" />

                                            <?php echo form_error('razon_social'); ?>

                                    </div>

                                </div>
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />

                                            <?php echo form_error('nombre'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Dirección");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('direccion'); ?>" placeholder="" name="direccion" />

                                            <?php echo form_error('direccion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Prefijo");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('prefijo'); ?>" placeholder="" name="prefijo" />

                                            <?php echo form_error('prefijo'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('consecutivo'); ?>" placeholder="" name="consecutivo" />

                                            <?php echo form_error('consecutivo'); ?>

                                    </div>

                                </div>
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Número de Final");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('numero_fin'); ?>" placeholder="" name="numero_fin" />

                                            <?php echo form_error('numero_fin'); ?>

                                    </div>

                                </div>
                                
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Fecha Vencimiento");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('fecha_vencimiento'); ?>" placeholder="" name="fecha_vencimiento" class="datepicker" />

                                            <?php echo form_error('fecha_vencimiento'); ?>

                                    </div>

                                </div>
                                
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Avisarme cuando llegue al número:");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('numero_alerta'); ?>" placeholder="" name="numero_alerta" />

                                            <?php echo form_error('numero_alerta'); ?>

                                    </div>

                                </div>
                                
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Avisarme cuando falten");?>:</div>

                                    <div class="span9">
                                        <select name="fecha_alerta" value="">
                                            <option value="7" >7 Dias</option>
                                            <option value="15">15 Dias</option>
                                            <option value="30">30 Dias</option>
                                        </select>

                                        <?php echo form_error('fecha_alerta'); ?>

                                    </div>

                                </div>

                                  <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Teléfono");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('telefono'); ?>" placeholder="" name="telefono" />

                                            <?php echo form_error('telefono'); ?>

                                    </div>

                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Meta Diaria");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('meta_diaria'); ?>" placeholder="" name="meta_diaria" />
                                        <?php echo form_error('meta_diaria'); ?>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Activar Consecutivo Cierre Caja");?>:</div>
                                    <div class="span9"><input type="checkbox"  value="<?php echo set_value('activar_consecutivo_cierre_caja'); ?>" name="activar_consecutivo_cierre_caja" id="activar_consecutivo_cierre_caja" />
                                        <?php echo form_error('activar_consecutivo_cierre_caja'); ?>
                                    </div>
                                </div>

                                <div class="row-form consecutivo_cierre_caja hidden">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo Cierre Caja");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('consecutivo_cierre_caja'); ?>" name="consecutivo_cierre_caja" />
                                        <?php echo form_error('consecutivo_cierre_caja'); ?>
                                    </div>
                                </div>
                                
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Pais");?>:</div>
                                    <div class="span9">
                                        <select name="pais_almacen" id="pais">
                                            <?php foreach($data["paises"] as $pais): ?>
                                                <option value="<?= $pais;?>"><?= $pais;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Ciudad");?>:</div>

                                    <div class="span9"><?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?>

                                            <?php echo form_error('meta_diaria'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sale_active', "Activo");?>:</div>

                                    <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo'); ?>" <?php echo "1" == set_value('activo') ? "checked='checked'" : ""; ?> />

                                        <?php echo form_error('activo'); ?>

                                    </div>

                                </div>
                                <?php
                                if($data["tipo_negocio"]=="restaurante"){
                                ?>
                               
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('consecutivo_orden', "Consecutivo Orden");?>:</div>

                                    <div class="span9"><input type="text"  value="" placeholder="Ingrese Consecutivo de orden de Restaurante" name="consecutivo_orden_restaurante" name="consecutivo_orden_restaurante" />

                                            <?php echo form_error('consecutivo_orden_restaurante'); ?>

                                    </div>

                                </div>
                                 <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('reiniciar_consecutivo_orden', "Reiniciar Consecutivo Orden Restaurante");?>:</div>

                                    <div class="span9"><input type="text"  value="" placeholder="Reiniciar Consecutivo Orden de Restaurante cuando llegue al número" name="reiniciar_consecutivo_orden_restaurante" />
                                            <?php echo form_error('reiniciar_consecutivo_orden_restaurante'); ?>
                                    </div>

                                </div>
                                <?php
                                    }
                                ?>

                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                        <button class="btn btn-success" id="guardar" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>


<script type="text/javascript">

    $(document).ready(function(){

        $('#activar_consecutivo_cierre_caja').change(function() {            
            if($(this).prop('checked')){            
                $(".consecutivo_cierre_caja").removeClass('hidden');
            }else{
                $(".consecutivo_cierre_caja").addClass('hidden');
            }
        });

        $("#validate").submit(function(){
            $('#guardar').prop('disabled',true);
        });

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
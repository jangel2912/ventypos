<div class="page-header">    
    <div class="icon">
        <img alt="Cliente" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_cliente']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cliente", "Cliente");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_client', "Nuevo Cliente");?></h2>                                          
    </div>
</div>

<div class="row-fluid">
<?php echo form_open("clientes/nuevo", array("id" =>"validate"));?>
    <div class="span7">

        <div class="block">

        <div class="data-fluid">

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre Comercial");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo set_value('nombre_comercial'); ?>" placeholder="" name="nombre_comercial" />

                 <?php echo form_error('nombre_comercial'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_country', "País");?>:</div>

                <div class="span9">

                        <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), "id='pais'   style='width: 100%'");?>

                        <?php echo form_error('pais'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_estado', "Provincia");?>:</div>

                <div class="span9">

                        <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'  style='width: 100%'");?>

                        <?php echo form_error('provincia'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_people', "Ciudad");?>:</div>

                <div class="span9"><input type="text" value="<?php echo set_value('poblacion'); ?>" name="poblacion" placeholder=""/>

                    <?php echo form_error('poblacion'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social");?>:</div>

                <div class="span9"><input type="text" value="<?php echo set_value('razon_social'); ?>" name="razon_social" placeholder=""/>

                    <?php echo form_error('razon_social'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_dsnif', "Tipo de Identificaci&oacute;n");?>:</div>

                <div class="span9">

                    <?php echo form_dropdown('tipo_identificacion', $data['tipo_identificacion'], "", "id='forma_pago'"); ?>
                    <?php echo form_error('tipo_identificacion'); ?>

                </div>

            </div>   

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_nif', "N° de identificaci&oacute;n");?>:</div>

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

                <div class="span3"><?php echo custom_lang('sima_email', "Correo Electrónico");?>:</div>

                <div class="span9"><input type="text" value="<?php echo set_value('email'); ?>" name="email" placeholder=""/>

                    <?php echo form_error('email'); ?>

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

                <div class="span3"><?php echo custom_lang('sima_movil', "Celular");?>:</div>

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
            <?php if(count($data['grupo']) != 0)
            {
                ?>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_grupo', "Grupo");?>:</div>
                    <div class="span9">
                        <select name="grupo">
                            <option>Seleccione un grupo</option>
                            <?php 
                            foreach($data['grupo'] as $g)
                            {
                                ?>
                                <option value="<?php echo $g->id ?>"><?php echo $g->nombre ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php echo form_error('grupo'); ?>
                    </div>
                </div>   
                <?php
            }?>
            

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_observaciones', "Observaciones");?>:</div>

                <div class="span9"><textarea name="observaciones" placeholder="Observaciones"><?php echo set_value('observaciones'); ?></textarea>

                    <?php echo form_error('observaciones'); ?>

                </div>

            </div>

            <div class="toolbar bottom tar">
                <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
            </div>

        </div>

    </div>

    </div>
    
    <div class="span5">
        <div class="block">
            <div class="data-fluid">
                <div class="row-form">
                    <div class="span5"><?php echo custom_lang('sima_name_comercial', "Fecha Nacimiento");?>:</div>
                    <div class="span7"><input type="text" value="<?php echo set_value('fecha_nacimiento'); ?>" placeholder="" name="fecha_nacimiento" id="fecha_nacimiento"/>
                     <?php echo form_error('fecha_nacimiento'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span5"><?php echo custom_lang('sima_gender', "Género");?>:</div>
                    <div class="span7">
                            <?php echo custom_form_dropdown('genero', array(''=>'Seleccione','M'=>'Masculino','F'=>'Femenino'), set_value('genero'), "id='genero'  class='select' style='width: 100%'");?>
                            <?php echo form_error('genero'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= form_close();?>    

</div>

<script type="text/javascript">

    $(document).ready(function(){
        
        $( "#fecha_nacimiento" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: "-100:+0",
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
                            url: "<?php echo site_url("frontend/load_provincias_from_pais"); ?>",

                            type:"GET",

                            dataType: "json",

                            data: {  "pais" : pais },

                            success: function(data) {

                                console.log(data);

                                $("#provincia").html('');

                                $.each(data, function(index, element){
                                    provincia = "<?php echo set_value('provincia');?>"

                                    sel = provincia == element[0] ? "selected='selected'" : '';

                                if (element[0] != undefined) {
                                    $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");
                                } 
                               
                                });
							 }

        });

    }

</script>
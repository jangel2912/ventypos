<div class="page-header">    
    <div class="icon">
        <img alt="Cliente" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_cliente']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cliente", "Cliente");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_client', "Editar Cliente"); ?></h2>  
    </div>
</div>

<div class="row-fluid">
<?php echo form_open("clientes/editar/" . $data['data']['id_cliente'], array("id" => "validate")); ?>
    <div class="span7">

        <div class="block">

            <div class="data-fluid">

                <input type="hidden" value="<?php echo set_value('id_cliente', $data['data']['id_cliente']); ?>" name="id" />

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre comercial"); ?>:</div>

                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_comercial', $data['data']['nombre_comercial']); ?>" placeholder="nombre comercial" name="nombre_comercial" />

                        <?php echo form_error('nombre_comercial'); ?>

                    </div>

                </div>
                <!--
                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_country', "Pais"); ?>:</div>

                    <div class="span9">
                        <?php
                            if($data['data']['pais']==0){
                                $data['pais'][0]="Seleccione";
                                echo custom_form_dropdown('pais', $data['pais'], $this->form_validation->set_value('pais', 0), "id='pais'  class='select' style='width: 100%'");
                            }else{ 
                                echo custom_form_dropdown('pais', $data['pais'], $this->form_validation->set_value('pais', $data['data']['pais']), "id='pais'  class='select' style='width: 100%'"); 
                            } ?>
                        <?php echo form_error('pais'); ?>

                    </div>

                </div>-->
                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_country', "País");?>:</div>

                    <div class="span9">

                            <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), "id='pais'   style='width: 100%'");?>

                            <?php echo form_error('pais'); ?>

                    </div>

                </div>
                
                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_estado', "Provincia"); ?>:</div>

                    <div class="span9">

                        <select name="provincia" id="provincia" style="width: 100%">        
                            <?php if($data['data']['provincia']==0){ ?>
                                <option value="0">Seleccione</option>
                            <?php }else{ ?>
                                <option value="<?= $this->form_validation->set_value('provincia', $data['data']['provincia']) ?>"><?= $this->form_validation->set_value('provincia', $data['data']['provincia']) ?></option>
                            <?php } ?>
                            
                        </select>
                        <?php echo form_error('provincia'); ?>
                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_people', "Ciudad"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('poblacion', $data['data']['poblacion']); ?>" name="poblacion" placeholder="Poblaci&oacute;n"/>

                        <?php echo form_error('poblacion'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n social"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('razon_social', $data['data']['razon_social']); ?>" name="razon_social" placeholder="Razon social"/>

                        <?php echo form_error('razon_social'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_dsnif', "Tipo de identificaci&oacute;n"); ?>:</div>

                    <div class="span9">

                        <?php echo form_dropdown('tipo_identificacion', $data['tipo_identificacion'], $this->form_validation->set_value('tipo_identificacion', $data['data']['tipo_identificacion']), "", "id='forma_pago' class='form-control'"); ?>			  


                    </div>

                </div>   


                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_nif', "No de identificaci&oacute;n"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('nif_cif', $data['data']['nif_cif']); ?>" name="nif_cif" placeholder="NIF/CIF"/>

                        <?php echo form_error('nif_cif'); ?>

                    </div>

                </div>                    

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_contact', "Contacto"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('contacto', $data['data']['contacto']); ?>" name="contacto" placeholder="Contacto"/>

                        <?php echo form_error('contacto'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_web', "Web"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('pagina_web', $data['data']['pagina_web']); ?>" name="pagina_web" placeholder="Pagina web"/>

                        <?php echo form_error('pagina_web'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_email', "Correo electronico"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('email', $data['data']['email']); ?>" name="email" placeholder="Email"/>

                        <?php echo form_error('email'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_addres', "Direcci&oacute;n"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('direccion', $data['data']['direccion']); ?>" name="direccion" placeholder="Direcci&oacute;n"/>

                        <?php echo form_error('direccion'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_cp', "C&oacute;digo postal"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('cp', $data['data']['cp']); ?>" name="cp" placeholder="C&oacute;digo postal"/>

                        <?php echo form_error('cp'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('telefono', $data['data']['telefono']); ?>" name="telefono" placeholder="Tel&eacute;fono"/>

                        <?php echo form_error('telefono'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_movil', "Movil"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('movil', $data['data']['movil']); ?>" name="movil" placeholder="Movil"/>

                        <?php echo form_error('movil'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_fax', "Fax"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('fax', $data['data']['fax']); ?>" name="fax" placeholder="Fax"/>

                        <?php echo form_error('fax'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_company_type', "Tipo de empresa"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('tipo_empresa', $data['data']['tipo_empresa']); ?>" name="tipo_empresa" placeholder="Tipo de empresa"/>

                        <?php echo form_error('tipo_empresa'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_bancaria_entity ', "Entidad bancaria"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('entidad_bancaria', $data['data']['entidad_bancaria']); ?>" name="entidad_bancaria" placeholder="Entidad bancaria"/>

                        <?php echo form_error('entidad_bancaria'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_number_cuenta', "N&uacute;mero de cuenta"); ?>:</div>

                    <div class="span9"><input type="text" value="<?php echo set_value('numero_cuenta', $data['data']['numero_cuenta']); ?>" name="numero_cuenta" placeholder="N&uacute;mero de cuenta"/>

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
                                    <option value="<?php echo $g->id ?>" <?php echo ($data['data']['grupo_clientes_id'] == $g->id) ?"selected":""; ?>>
                                        <?php echo $g->nombre ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>   
                    <?php
                }?>

                <div class="row-form">

                    <div class="span3"><?php echo custom_lang('sima_observaciones', "Observaciones"); ?>:</div>

                    <div class="span9"><textarea name="observaciones" placeholder="Observaciones"><?php echo set_value('observaciones', $data['data']['observaciones']); ?></textarea>

                        <?php echo form_error('observaciones'); ?>

                    </div>

                </div>

                <div class="toolbar bottom tar">
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>                    
                </div>
            </div>

            

        </div>

    </div>
    <div class="span5">
        <div class="block">
            <div class="data-fluid">
                <div class="row-form">
                    <div class="span5"><?php echo custom_lang('sima_name_comercial', "Fecha Nacimiento");?>:</div>
                    <div class="span7"><input type="text" value="<?php echo $data['data']['fecha_nacimiento']; ?>" placeholder="" name="fecha_nacimiento" id="fecha_nacimiento"/>
                     <?php echo form_error('fecha_nacimiento'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span5"><?php echo custom_lang('sima_gender', "Género");?>:</div>
                    <div class="span7">
                            <?php echo custom_form_dropdown('genero', array(''=>'Seleccione','M'=>'Masculino','F'=>'Femenino'), $data['data']['genero'], "id='genero'  class='select' style='width: 100%'");?>
                            <?php echo form_error('genero'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= form_close();?>


</div>

<script type="text/javascript">

    $(document).ready(function () {
        var pais="<?php echo $data['data']['pais']; ?>";
        
        if(pais != ""){
            $("#pais").val(pais);
            pais="<?php echo $data['data']['pais'];?>";            
            load_provincias_from_pais(pais);
        }

        $( "#fecha_nacimiento" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: "-100:+0",
          })

        $("#pais").change(function(){
            load_provincias_from_pais($(this).val());
        });         

    });

/*
    function load_provincias_from_pais(pais) {

//$("#provincia").select2().select2('val',"<?php echo set_value('provincia', $data["data"]["provincia"]); ?>");

        $.ajax({
            url: "<?php echo site_url("clientes/load_provincias_from_pais"); ?>",

            type: "GET",

            dataType: "json",

            data: {"pais": pais},

            success: function (data) {

                if (pais != 'Colombia') {
                    $("#provincia").html('');
                }

                for (var i in data) {

                    provincia = "<?php echo set_value('provincia', $data["data"]["provincia"]); ?>"

                    sel = provincia == data[i].pro_nombre ? "selected='selected'" : '';

                    $("#provincia").append("<option value='" + data[i].pro_nombre + "' >" + data[i].pro_nombre + "</option>");

                }

                $("#provincia").select2().select2('val', '<?php echo set_value('provincia', $data["data"]["provincia"]); ?>');

            }

        });

    }*/

    
   function load_provincias_from_pais(pais){
        
	    $.ajax({
            url: "<?php echo site_url("frontend/load_provincias_from_pais"); ?>",

            type:"GET",

            dataType: "json",

            data: {  "pais" : pais },

            success: function(data) {                
                
                $("#provincia").html('');

                $.each(data, function(index, element){
                    provincia = "<?php echo $data['data']['provincia'] ?>"

                    sel = provincia == element[0] ? "selected='selected'" : '';

                if (element[0] != undefined) {
                    $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");
                } 
                
                });
            }

        });

    }


</script>
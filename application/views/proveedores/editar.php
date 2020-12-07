<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Contactos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_edit_provider', "Editar proveedor");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open("proveedores/editar/".$data['data']['id_proveedor'], array("id" =>"validate"));?>

                                <input type="hidden" value="<?php echo set_value('id_proveedor', $data['data']['id_proveedor']); ?>" name="id" />

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_comercial', $data['data']['nombre_comercial']); ?>" placeholder="nombre comercial" name="nombre_comercial" />

                                            <?php echo form_error('nombre_comercial'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_country', "Pais");?>:</div>

                                    <div class="span9">

                                            <?php echo custom_form_dropdown('pais', $data['pais'], $this->form_validation->set_value('pais', $data['data']['pais']), "id='pais'");?>

                                            <?php echo form_error('pais'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_estado', "Provincia");?>:</div>

                                    <div class="span9">

                                            <?php echo form_dropdown('provincia', array(), $this->form_validation->set_value('provincia', $data['data']['provincia']), "id='provincia'");?>

                                            <?php echo form_error('provincia'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('razon_social', $data['data']['razon_social']); ?>" name="razon_social" placeholder="Razon social"/>

                                        <?php echo form_error('razon_social'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('nif_cif', $data['data']['nif_cif']); ?>" name="nif_cif" placeholder="NIF/CIF"/>

                                        <?php echo form_error('nif_cif'); ?>

                                    </div>

                                </div>                    

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_contact', "Contacto");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('contacto', $data['data']['contacto']); ?>" name="contacto" placeholder="Contacto"/>

                                        <?php echo form_error('contacto'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_web', "Web");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('pagina_web', $data['data']['pagina_web']); ?>" name="pagina_web" placeholder="Pagina web"/>

                                        <?php echo form_error('pagina_web'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('email', $data['data']['email']); ?>" name="email" placeholder="Email"/>

                                        <?php echo form_error('email'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_people', "Poblaci&oacute;n");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('poblacion', $data['data']['poblacion']); ?>" name="poblacion" placeholder="Poblaci&oacute;n"/>

                                        <?php echo form_error('poblacion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_addres', "Direcci&oacute;n");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('direccion', $data['data']['direccion']); ?>" name="direccion" placeholder="Direcci&oacute;n"/>

                                        <?php echo form_error('direccion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_cp', "C&oacute;digo postal");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('cp', $data['data']['cp']); ?>" name="cp" placeholder="C&oacute;digo postal"/>

                                        <?php echo form_error('cp'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('telefono', $data['data']['telefono']); ?>" name="telefono" placeholder="Tel&eacute;fono"/>

                                        <?php echo form_error('telefono'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_movil', "Movil");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('movil', $data['data']['movil']); ?>" name="movil" placeholder="Movil"/>

                                        <?php echo form_error('movil'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_fax', "Fax");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('fax', $data['data']['fax']); ?>" name="fax" placeholder="Fax"/>

                                        <?php echo form_error('fax'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_company_type', "Tipo de empresa");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('tipo_empresa', $data['data']['tipo_empresa']); ?>" name="tipo_empresa" placeholder="Tipo de empresa"/>

                                        <?php echo form_error('tipo_empresa'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_bancaria_entity ', "Entidad bancaria");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('entidad_bancaria', $data['data']['entidad_bancaria']); ?>" name="entidad_bancaria" placeholder="Entidad bancaria"/>

                                        <?php echo form_error('entidad_bancaria'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_number_cuenta', "N&uacute;mero de cuenta");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('numero_cuenta', $data['data']['numero_cuenta']); ?>" name="numero_cuenta" placeholder="N&uacute;mero de cuenta"/>

                                        <?php echo form_error('numero_cuenta'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_observaciones', "Observaciones");?>:</div>

                                    <div class="span9"><textarea name="observaciones" placeholder="Observaciones"><?php echo set_value('observaciones', $data['data']['observaciones']); ?></textarea>

                                        <?php echo form_error('observaciones'); ?>

                                    </div>

                                </div>

                                <div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

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

                    provincia = "<?php echo set_value('provincia', $data["data"]["provincia"]);?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }

</script>
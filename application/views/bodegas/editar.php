<div class="page-header">    
    <div class="icon">
        <img alt="Bodegas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_bodegas']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Bodegas", "Bodegas");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_edit_product', "Editar Bodega");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open_multipart("bodegas/editar/".$data['data']['id'], array("id" =>"validate"));?>

                                <input type="hidden" value="<?php echo set_value('id', $data['data']['id']); ?>" name="id" />
                                    

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre', $data['data']['nombre']); ?>" placeholder="" name="nombre" />

                                            <?php echo form_error('nombre'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Dirección");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo set_value('direccion', $data['data']['direccion']); ?>" placeholder="" name="direccion" />

                                            <?php echo form_error('direccion'); ?>

                                    </div>

                                </div>    
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Pais");?>:</div>
                                    <div class="span9">
                                        <select name="pais_almacen" id="pais">
                                            <?php foreach($data["paises"] as $pais): ?>
                                                <option value="<?= $pais;?>" <?php echo (isset($data["data"]["pais"]) && ($data["data"]["pais"] == $pais))? 'selected' : '';?>><?= $pais;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Ciudad");?>:</div>
                                    <div class="span9">
							            <?php echo form_dropdown('provincia', array(), $this->form_validation->set_value('provincia', $data['data']['ciudad']), "id='provincia'");?>
                                        <?php echo form_error('provincia'); ?>
                                    </div>
                                </div>

                                <?php 
                               
                                $band=false;
                                foreach ($data['licenciavencidas'] as $key => $value) {
                                   if($value['id_almacen']==$data['data']['id']){
                                    $band=true;
                                   }
                                }
                             
                                if(!$band){
                                ?>
                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sale_active', "Activo");?>:</div>

                                    <div class="span9"><input name="activo" type="checkbox" value="<?php echo set_value('activo',$data['data']['activo']); ?>" <?php echo "1" == set_value('activo',$data['data']['activo']) ? "checked='checked'" : ""; ?> />

                                        <?php echo form_error('activo'); ?>

                                    </div>
                                </div>
                                <?php
                                    }
                                ?>

                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
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

                   provincia = "<?php echo set_value('provincia', $data["data"]["ciudad"]);?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }

</script>
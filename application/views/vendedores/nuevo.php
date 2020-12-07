<?php 
    $titulo="Vendedores";
    if((isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante")){
        $titulo="Vendedores/Meseros";
    } 
    ?>
<div class="page-header">    
    <div class="icon">
        <img alt="Vendedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_vendedor']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Vendedores", $titulo);?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_provider', "Nuevo ".$titulo);?></h2> 
    </div>
</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open("vendedores/nuevo/", array("id" =>"validate", 'autocomplete'=>'off'));?>

                    <div class="row-form">

                        <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>

                        <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                                <?php echo form_error('nombre'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_country', "Cédula");?>:</div>
                        <div class="span9">
                                <input type="text"  value="<?php echo set_value('cedula'); ?>" placeholder="" name="cedula" />
                                <?php echo form_error('cedula'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_estado', "Email");?>:</div>
                        <div class="span9">
                            <input type="text"  value="<?php echo set_value('email'); ?>" placeholder="" name="email"  autocomplete="off"/>
                            <?php echo form_error('email'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?>:</div>
                        <div class="span9"><input type="text" value="<?php echo set_value('telefono'); ?>" name="telefono" placeholder=""/>
                            <?php echo form_error('telefono'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_country', "Comisión (%)");?>:</div>
                        <div class="span9">
                                <input type="text"  value="" placeholder="" name="comision" />
                                <?php echo form_error('comision'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_country', "Almacén");?>:</div>
                        <div class="span9">
                            <?php   $is_admin = $this->session->userdata('is_admin');
                                if($is_admin == 's'){
                                echo $data1['almacen_nombre']; 
                                    ?><input type="hidden"  value="<?php echo $data1['almacen_id']; ?>" name="almacen" id="almacen"/><?php
                                    } 
                                    else{  
                                    echo "<select  name='almacen' >";    
                                    echo "<option value=''>Seleccione un almacén</option>";    
                                    foreach($data1['almacen'] as $f){
                                        if($f->id == $this->input->post('almacen')){
                                            $selected = " selected=selected ";
                                        } else {
                                            $selected = "";
                                        }        
                                        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                    }    
                                    echo "</select>";
                                    echo form_error('almacen');
                                    }
                                ?>
                        </div>
                    </div>

                    <?php if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante"){ ?>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_country', "¿Pertenece a estación de Pedidos?");?>:</div>
                        <div class="span9">                    
                            <label class="radio-inline estacion_pedido">
                                <?php echo form_radio('estacion_pedido', '1', '1'); ?> <?php echo custom_lang('estacion_pedido', "Si"); ?>                                
                            </label>
                            <label class="radio-inline estacion_pedido">
                                <?php echo form_radio('estacion_pedido', '0', ''); ?> <?php echo custom_lang('estacion_pedido', "No"); ?>
                                <?php echo form_error('estacion_pedido'); ?>
                            </label>
                        </div>
                    </div>
                     <div class="row-form" id="codigo">
                        <div class="span3"><?php echo custom_lang('codigo_estacion', "Código estación");?>:</div>
                        <div class="span9">
                            <input type="password"  value="<?php echo set_value('codigo'); ?>" autocomplete="off" placeholder="Código estación" id="codigo" name="codigo"/>
                            <?php echo form_error('codigo'); ?>
                        </div>
                    </div>
                    <?php }?>
                        
                    <div class="bottom tar">
                        <div class="btn-group">
                            <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                            <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                    
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>     
</div>

<script>
    $(document).ready(function(){
        
        $('.estacion_pedido input[type=radio]').change(function(){
            id=$(this).val();
            if(id==1){
                $('#clave').val('');
                $('#codigo').show();
            }
            else{
                $('#clave').val('');
                $('#codigo').hide();
            }      
        });
    });
    
</script>
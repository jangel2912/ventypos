<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Cuentas Dinero");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_provider', "Editar Cuenta Dinero");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <div class="data-fluid">

            <?php echo form_open("cuentas_dinero/editar/".$data['data']['id'], array("id" =>"validate"));?>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo $data['data']['nombre'];?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Tipo de Cuenta");?>:</div>

                <div class="span9">
<select name="tipo_cuenta" id="tipo_cuenta">
<option <?php if($data['data']['tipo_cuenta'] == 'Banco'){ echo "selected=selected"; } ?> value="Banco">Banco</option>
<option <?php if($data['data']['tipo_cuenta'] == 'Tarjeta crédito'){ echo "selected=selected"; } ?>  value="Tarjeta crédito">Tarjeta crédito</option>
<option <?php if($data['data']['tipo_cuenta'] == 'Caja menor'){ echo "selected=selected"; } ?> value="Caja menor">Caja menor</option>
<option <?php if($data['data']['tipo_cuenta'] == 'Caja registradora'){ echo "selected=selected"; } ?>  value="Caja registradora">Caja registradora</option>
</select>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Numero");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo $data['data']['nombre'];?>" placeholder="" name="numero" />

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Banco");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo $data['data']['nombre'];?>" placeholder="" name="banco" />

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Tipo de Cuenta bancaria");?>:</div>

                <div class="span9">

<select name="tipo_bancaria" id="tipo_bancaria">
<option <?php if($data['data']['tipo_bancaria'] == 'Ahorro'){ echo "selected=selected"; } ?> value="Ahorro">Ahorro</option>
<option <?php if($data['data']['tipo_bancaria'] == 'Corriente'){ echo "selected=selected"; } ?>  value="Corriente">Corriente</option>
</select>		
                </div>

            </div>
			
            <div class="row-form" >

                <div class="span3"><?php echo custom_lang('sima_country', "Almacen");?>:</div>

                <div class="span9">

<?php echo "<select  name='almacen' >";      
    foreach($data1['almacen'] as $f){
        if($f->id == $data['data']['id_almacen']){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";  ?>

                </div>

            </div>


            <div class="toolbar bottom tar">

                <div class="btn-group">

                    <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                </div>

            </div>

        </div>

        </form>

    </div>

    </div>   

    

</div>
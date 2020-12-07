<div class="page-header">

    <div class="icon">

        <span class="ico-group"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Cuentas Dinero");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_provider', "Nuevo Cuentas Dinero");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <div class="data-fluid">

            <?php echo form_open("cuentas_dinero/nuevo", array("id" =>"validate"));?>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Tipo de Cuenta");?>:</div>

                <div class="span9">
<?php echo "<select  name='tipo_cuenta' >";   
        echo "<option $selected value='Banco'>Banco</option>";
        echo "<option $selected value='Tarjeta crédito'>Tarjeta crédito</option>";
        echo "<option $selected value='Caja menor'>Caja menor</option>";
        echo "<option $selected value='Caja registradora'>Caja registradora</option>";
		
    echo "</select>";  ?>


                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Numero");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo set_value('numero'); ?>" placeholder="" name="numero" />

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Banco");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo set_value('banco'); ?>" placeholder="" name="banco" />

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Tipo de Cuenta bancaria");?>:</div>

                <div class="span9">
				
<?php echo "<select  name='tipo_bancaria' >";   
        echo "<option $selected value='Ahorro'>Ahorro</option>";
        echo "<option $selected value='Corriente'>Corriente</option>";
	    echo "</select>";  ?>
                </div>

            </div>
			
            <div class="row-form" >

                <div class="span3"><?php echo custom_lang('sima_country', "Almacen");?>:</div>

                <div class="span9">

<?php echo "<select  name='almacen' >";      
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
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
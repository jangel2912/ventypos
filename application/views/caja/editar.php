<div class="page-header">

    <div class="icon">

        <span class="ico-cabinet"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Cajas");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_provider', "Editar Caja");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open("caja/editar/".$data['data']['id'], array("id" =>"validate"));?>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>

                <div class="span9"><input type="text"  value="<?php echo $data['data']['nombre'];?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span3"><?php echo custom_lang('sima_country', "Almacen");?>:</div>

                <div class="span9">

<?php 
						 
	echo "<select  name='almacen' >";      
    foreach($data1['almacen'] as $f){
        if($f->id == $data['data']['id_Almacen']){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";
						 
						 ?>

                </div>

            </div>

            <div class="toolbar bottom tar">
                <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>                
            </div>

        </div>

        </form>

    </div>

    </div>   

    

</div>
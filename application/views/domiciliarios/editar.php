<style>
.ocultar{
    display:none;
}
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Vendedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_vendedor']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Domiciliarios", "Domiciliarios");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('Domiciliarios', "Editar Domiciliarios");?></h2> 
    </div>
</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open("domiciliarios/editar/".$data['domiciliario'][0]['id'], array("id" =>"validate",'autocomplete'=>'off'));?>

                <input type="hidden" value="<?php echo $data['domiciliario'][0]['id'];?>" name="id"/>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Tipo de Domiciliario");?>:</div>
                    <div class="span9">
                        <select name="tipo" id="tipo">
                            <option value=''>Seleccione el tipo de Domiciliario</option>
                            <?php $select="";
                                foreach ($data["tipo_domiciliario"] as $key => $value) { 
                                    if($data['domiciliario'][0]['tipo']==$value['id'])  {
                                        $select="selected";
                                    } else{
                                        $select="";
                                    } 
                                ?>
                                <option <?=$select?> value="<?= $value['id']?>"><?= $value['descripcion']?></option>
                            <?php } ?>
                        </select>
                            <?php echo form_error('tipo'); ?>
                    </div>
                </div>
                
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo $data['domiciliario'][0]['descripcion'] ?>" placeholder="" name="nombre" />
                            <?php echo form_error('nombre'); ?>
                    </div>
                </div>
                <div class="row-form ocultar">
                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Telefono");?>:</div>
                    <div class="span9"><input type="number"  value="<?php echo $data['domiciliario'][0]['telefono'] ?>" placeholder="" name="telefono" />
                            <?php echo form_error('telefono'); ?>
                    </div>
                </div>
                <div class="row-form ocultar">
                    <div class="span3"><?php echo custom_lang('sima_name_comercial', "Dirección");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo $data['domiciliario'][0]['direccion'] ?>" placeholder="" name="direccion" />
                            <?php echo form_error('direccion'); ?>
                    </div>
                </div>
			
                <div class="bottom tar">
                    <div class="btn-group">
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='<?php echo site_url('domiciliarios/') ?>'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
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
        tipo='<?php echo $data['domiciliario'][0]['tipo'] ?>'
        
        if(tipo==2){
            $(".ocultar").css('display','block');                
        }else{
            $(".ocultar").css('display','none');
        }

        $('#tipo').change(function(){
            id=$(this).val();            
            if(id=="2"){
                $(".ocultar").css('display','block');
            }
            else{
                $(".ocultar").css('display','none');
            }      
        });
    });
    
</script>
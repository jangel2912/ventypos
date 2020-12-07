<style>
.ocultar, .ocultarn{
    display:none;
}
.imglogo{
    width:100%;
    height: 100px;
    background-color: #fff;
    border-radius: 10px;
    border: 1px solid #ccd5db;  
    display: none;
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
        <h2><?php echo custom_lang('sima_new_provider', "Nuevo Domiciliario");?></h2> 
    </div>
</div>

<div class="row-fluid">
    <?php echo form_open("domiciliarios/nuevo/", array("id" =>"validate", 'autocomplete'=>'off'));?>
        <div class="col-md-6">                
            <div class="row-form">
                <div class="col-md-4"><?php echo custom_lang('sima_name_comercial', "Tipo de Domiciliario");?>:</div>
                <div class="col-md-6">
                    <select name="tipo" id="tipo" style="margin-left: 5px;">
                        <option value=''>Seleccione el tipo de Domiciliario</option>
                        <?php foreach ($data["tipo_domiciliario"] as $key => $value) { ?>
                            <option value="<?= $value['id']?>"><?= $value['descripcion']?></option>
                        <?php } ?>
                    </select>
                        <?php echo form_error('tipo'); ?>
                </div>                        
            </div>
            <div class="row-form ocultarn">
                <div class="col-md-4"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>
                <div class="col-md-6">
                    <input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="Nombre" name="nombre" />
                    <?php echo form_error('nombre'); ?>
                </div>                       
            </div>
            <div class="row-form ocultar">
                <div class="col-md-4"><?php echo custom_lang('sima_name_comercial', "Telefono");?>:</div>
                <div class="col-md-6"><input type="text"  value="<?php echo set_value('telefono'); ?>" placeholder="Teléfono" name="telefono" />
                        <?php echo form_error('telefono'); ?>
                </div>
            </div>
            <div class="row-form ocultar">
                <div class="col-md-4"><?php echo custom_lang('sima_name_comercial', "Dirección");?>:</div>
                <div class="col-md-6"><input type="text"  value="<?php echo set_value('direccion'); ?>" placeholder="Dirección" name="direccion" />
                        <?php echo form_error('direccion'); ?>
                </div>
            </div>
            <div class="row-form ocultar">
                <div class="col-md-4"><?php echo custom_lang('sima_name_comercial', "activo");?>:</div>
                <div class="col-md-6"><input type="text"  value="<?php echo set_value('activo'); ?>" placeholder="Activo" name="activo" />
                        <?php echo form_error('telefono'); ?>
                </div>
            </div>               
                         
        </div>         
        <div class="col-md-6" style="display=none">
            <div class="row-form ocultarn">            
                <div class="col-md-6">
                    <div id="logo" name="logo" class="imglogo"></div>
                </div>                       
            </div>            
        </div> 
        <div class="col-md-12">
            <div class="bottom tar">
                <div class="btn-group">
                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='<?php echo site_url('domiciliarios/') ?>'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                    <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                    
                </div>
            </div>  
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        
        $('#tipo').change(function(){
            id=$(this).val();   
            if(id==""){
                $(".ocultarn").css('display','none');  
            }else{
                $(".ocultarn").css('display','block');  
            }  
                 
            if(id=="2"){
                $(".ocultar").css('display','block');
                
            }
            else{
                $(".ocultar").css('display','none');               
               
            }      
        });
    });
    
</script>
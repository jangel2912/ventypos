<div class="page-header">    
    <div class="icon">
        <img alt="Mesas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_mesas']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Mesas", "Mesas/Secciones");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_category', "Nueva Sección"); ?></h2>                                          
    </div>
</div>

<div class="row-fluid">
    <?php echo form_open_multipart("secciones_almacen/nuevo", array("id" => "f_validate")); ?>
    <div class="row-form">
     <div class="form-group">
       <div class="col-xs-12">
           <label for="codigo" class="col-sm-2 control-label">
               <?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?>:                          
           </label>
           <div class="col-sm-4">
               <input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />
               <?php echo form_error('codigo','<p class="text-error">','</p>'); ?>
           </div>
           <label class="col-sm-2 control-label">
             <?php echo custom_lang('sima_nombre_seccion', "Nombre Sección"); ?> *:
         </label>
         <div class="col-sm-4">
             <input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
             <?php echo form_error('nombre','<p class="text-error">','</p>'); ?>
         </div>
        </div>
      </div>
     </div>   
 <div class="row-form">
    <div class="form-group">
        <div class="col-xs-12">
            <label class="col-sm-2 control-label"><?php echo custom_lang('sale_almacen', "Almacén"); ?> *:</label>
            <div class="col-sm-4">
                <select name="almacen">
                    <option value ="">Seleccione</option>    
                    <?php foreach ($data['almacenes'] as $key => $value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->nombre; ?></option> 
                    <?php } ?>
                </select>
                <?php echo form_error('almacen','<p class="text-error">','</p>'); ?>
            </div>
            <label class="col-sm-2 control-label">
                <?php echo custom_lang('sale_almacen', "Descripción"); ?>:
            </label>
            <div class="col-sm-4">
                <textarea name="descripcion"></textarea>
                <?php echo form_error('descripcion','<p class="text-error">','</p>'); ?>
            </div>
        </div>
    </div>
 </div> 
 <div class="row-fluid">
     <div class="col-xs-12">
         
     </div>
     <div class="col-xs-12">
        <div id="div_mensajes"> 

        </div>
     </div>
 </div>      
<div class="toolbar bottom tar">
    <div>
        <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../secciones_almacen/index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
    </div>
</div>
<?php echo form_close() ?>      
</div>

<script type="text/javascript">
  /*  $(document).ready(function(){
        $("#f_validate").submit(function(e){
            e.preventDefault();
            enviar_formulario();

        });
    });

  function enviar_formulario(){
    console.log('enviar_formulario');
    $.ajax({
        url: $("#f_validate").attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $("#f_validate").serialize(),
        beforeSend: function(){
                $("#div_mensajes").hide('fast');
        },
        success: function(result){
            $("#div_mensajes").html('');
            $("#div_mensajes").removeClass();
            if(result.status){
                $("#div_mensajes").addClass('alert alert-success');
                $("#div_mensajes").html(result.errors);
            }else{

                $.each(result.errors, function(key, val) {
                    $('[name="'+ key +'"]').after(val);
                });

                $("#div_mensajes").addClass('alert alert-danger');
                $("#div_mensajes").html('<b>Por favor revise los errores en el formulario</b>');

            }
            $("#div_mensajes").show('slow');
        }
    });
  }  */
</script>



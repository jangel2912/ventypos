<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/bootstrap-chosen.css">
<script src="<?php echo base_url("public/js"); ?>/chosen.jquery.js"></script>

<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("tamanos_productos", "Tamaños productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_editar_tamano', "Editar tamaño de producto"); ?></h2>                                          
    </div>
</div>

<div class="row-fluid">
    <?php echo form_open_multipart("tamanos_productos/update/".$id, array("id" => "f_editar_tamano_producto")); ?>
	<div class="row-form">
     <div class="form-group">
       <div class="col-xs-12">
           <label for="t_nombre_tamano" class="col-sm-2 control-label">
               <?php echo custom_lang('sima_nombre', "Nombre"); ?>:                          
           </label>
           <div class="col-sm-4">
               <input type="text"  value="<?php echo $nombre_tamano; ?>" placeholder="" name="t_nombre_tamano" />
               <?php echo form_error('nombre_tamano','<p class="text-error">','</p>'); ?>
           </div>
           <label class="col-sm-2 control-label">
             <?php echo custom_lang('sisma_categoria_productos', "Categorias de producto:"); ?> *:
         </label>
         <div class="col-sm-4">
             <select name="s_categorias_prducto[]" id="s_categorias_prducto" multiple="multiple">
             	<?php foreach ($categorias as $key => $una_categoria) { 
                  $selected = '';
                  if(in_array($una_categoria->id,$categorias_tamano)){
                    $selected = 'selected';
                  }
                ?>
             		<option <?php echo $selected ?> value="<?php echo $una_categoria->id ?>"><?php echo $una_categoria->nombre ?></option>	
             	<?php } ?>
             </select>
             <?php echo form_error('s_categorias_prducto','<p class="text-error">','</p>'); ?>
         </div>
        </div>
      </div>
     </div>  
     <div class="row-form">
     <div class="form-group">
       <div class="col-xs-12">
           <label for="ta_descripcion_tamano" class="col-sm-2 control-label">
               <?php echo custom_lang('sima_descripcion', "Descripcion"); ?>:                          
           </label>
           <div class="col-sm-4">
           <textarea name="ta_descripcion_tamano"><?php echo $descripcion_tamano; ?></textarea>
             <?php echo form_error('ta_descripcion_tamano','<p class="text-error">','</p>'); ?>
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
    <div class="btn-group">
        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar"); ?></button>
        <button class="btn btn-warning"  type="button" onclick="javascript:location.href = '../tamanos_productos/index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
    </div>
</div>
<?php echo form_close() ?> 
</div>    
<script>
    $(document).ready(function(){ 
    	$('#s_categorias_prducto').chosen(); 
    	$("#f_editar_tamano_producto").submit(function(e){
            e.preventDefault();
            enviar_formulario();
        });
    });
        
    function enviar_formulario(){
    	console.log('enviar_formulario');
	    $.ajax({
	        url: $("#f_editar_tamano_producto").attr('action'),
	        type: 'POST',
	        dataType: 'json',
	        data: $("#f_editar_tamano_producto").serialize(),
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

	                $("#div_mensajes").addClass('alert alert-error');
	                $("#div_mensajes").html('<b>Por favor revise los siguiente errores:</b>'+result.error_html);

	            }
	            $("#div_mensajes").show('slow');
	        }
	    });
    }
</script>
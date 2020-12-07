

<?php extract($data); ?>
<div class="page-header">    
    <div class="icon">
        <img alt="Usuarios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_usuarios']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Usuarios", "Usuarios");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('Nuevo Usuario', "Nuevo Usuario"); ?></h2>                                          
    </div>
</div>


<div class="row-fluid">
    <?php echo form_open("usuarios/nuevo", array("id" => "validate")); ?>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="first_name" class="col-sm-2 control-label"><?php echo custom_lang('sima_name', "Nombre"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($first_name); ?>
                    <?php echo form_error('first_name'); ?>
                </div>
                 <label for="last_name" class="col-sm-2 control-label"><?php echo custom_lang('sima_last_name', "Apellido"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($last_name); ?>
                    <?php echo form_error('last_name'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="company" class="col-sm-2 control-label"><?php echo custom_lang('sima_company', "Compa&ntilde;&iacute;a"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($company); ?>
                    <?php echo form_error('company'); ?>
                </div>
                 <label for="email" class="col-sm-2 control-label"><?php echo custom_lang('sima_email', "Correo electr&oacute;nico"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($email); ?>
                    <?php echo form_error('email'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="phone1" class="col-sm-2 control-label"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($phone1); ?>
                    <?php echo form_error('phone1'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="password" class="col-sm-2 control-label"><?php echo custom_lang('sima_password', "Clave"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($password); ?>
                    <?php echo form_error('password'); ?>
                </div>
                <label for="password_confirm" class="col-sm-2 control-label"><?php echo custom_lang('sima_password_confirm', "Confirmar Clave"); ?>:</label>
                <div class="col-sm-4">
                    <?php echo form_input($password_confirm); ?>
                    <?php echo form_error('password_confirm'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="almacen" class="col-sm-2 control-label"><?php echo custom_lang('sima_almacen', "Almacén"); ?>:</label>
                <div class="col-sm-4">
                    <?php                   
                        $attr_almacen = array();
                         $usu=0;
                        $attr_almacen[''] = 'Seleccion un Almacén';
                        foreach ($almacen as $row):
                            if(isset($data["definecrear"][$row->id]['usuarios']) && ($data["definecrear"][$row->id]['usuarios']==1)){
                                $usu++;
                                $attr_almacen[ $row->id ] = $row->nombre;
                            }                            
                        endforeach;
                        if($usu>0){
                             $attr_almacen[-1] = 'Ver la informacion de todos los almacenes';
                        }                       
                        echo  form_dropdown('almacen', $attr_almacen, '', "id='almacen' class='form-control required' "); 
                    ?>
                    <?php echo form_error('almacen'); ?>
                </div>
                <?php if ($data['valor_caja'] == 'si') { ?>
                    <label for="caja" class="col-sm-2 control-label"><?php echo custom_lang('sima_caja', "Caja"); ?>:</label>
                    <div class="col-sm-4">
                        <?php echo form_dropdown('caja', array(''=>'Seleccione una Caja'), '', "id='caja' class='form-control required' ");  ?>
                        <?php echo form_error('caja'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">
                <label for="rol_id" class="col-sm-2 control-label"><?php echo custom_lang('sima_rol', "Rol"); ?>:</label>
                <div class="col-sm-4">
                   <select name="rol_id" id="rol_id" class="form-control required">
                        <option value="">Seleccione un Rol</option>
                    <?php foreach ($roles as $key => $un_rol) { ?>
                          <option value="<?php echo $key ?>"><?php echo $un_rol ?></option>      
                    <?php } ?>   
                   </select>
                    <?php echo form_error('rol_id'); ?>
                </div>
                <div class="col-sm-6">
                    <label class="radio-inline">
                        <?php echo form_radio('is_admin', 't', ''); ?> <?php echo custom_lang('sima_is_asmin', "Administrador"); ?>
                        <?php echo form_error('is_admin'); ?>
                    </label>
                    <label class="radio-inline">
                        <?php echo form_radio('is_admin', 's', ''); ?> <?php echo custom_lang('sima_is_asmin', "Ver solo la informaci&oacute;n del almac&eacute;n"); ?>
                        <?php echo form_error('is_admin'); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
     <?php if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante"){ ?>
    <div class="row-form">
        <div class="form-group">
            <div class="col-xs-12 ">                
                <div class="col-sm-6">
                    <label for="estacion_pedido" class="control-label"><?php echo custom_lang('estacion_pedido', "¿Es estación de Pedidos?"); ?>:</label>
                    <label class="radio-inline">
                        <?php echo form_radio('estacion_pedido', '1', ''); ?> <?php echo custom_lang('estacion_pedido', "Si"); ?>
                        <?php echo form_error('estacion_pedido'); ?>
                    </label>
                    <label class="radio-inline">
                        <?php echo form_radio('estacion_pedido', '0', ''); ?> <?php echo custom_lang('estacion_pedido', "No"); ?>
                        <?php echo form_error('estacion_pedido'); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    <div class="row-form">
         <div class="col-sm-5 ">
            <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
            <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>            
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


<script>
    usu='<?= $usu ?>';
     if(usu==0){
        alert("Lo sentimos, Tu licencia no te permite crear más usuarios.");                
        location.href ='<?php echo site_url("usuarios") ?>';            
    }   
    $(document).ready(function(){
        $(this).on("change blur", "#email", function(){
            if($(this).val().trim()!=''){
                validaEmail( $(this) );
            }else{
                return false;
            }
        });
        
        $("#almacen").change(function () {
            $("#almacen option:selected").each(function () {
                almacen = $('#almacen').val();
                bodega=0;                
                //verifico si es bodega              
                $.ajax({
                    url: "<?php echo site_url("almacenes/get_Bodega")?>",
                    data: {"almacen" : almacen},
                    type:'POST',
                    success: function(data) {                            
                        bodega=data.success;    
                        $.post("<?php echo site_url("usuarios/almacen_caja"); ?>", {
                            almacen: almacen,                    
                        }, function (data) {
                            $("#caja").html(data);                   
                            if(bodega==1){
                                $("#caja").val(0);
                                $("#caja").prop('disabled',true);   
                                
                            }else{
                                $("#caja").prop('disabled',false);    
                            }
                        });                                                  
                    }
                });               
            });
        });
        
        $("#validate").submit(function () {
            var result = validaFields( $(this) );
            
            return result;
        });
    });
    
    var validaEmail = function( field ){
        $.ajax({
            url: $("#validate").attr('action').replace('nuevo', 'validaEmail'),
            type:'POST',
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            async: false,
            data: {
                email: field.val()
            },
            success: function( mdata ) {
                if(Number(mdata.cod_status) == 200 ){
                    swal('Validación de Usuario', 'El Usuario con Email '+field.val()+', ya se encuentra registrado por favor ingrese uno diferente!', 'warning');
                    field.val('');
                }else{
                    return false;
                }
            }
        });
    };
    
    var validaFields = function(frm){
        var count = 0;
        frm.find(":input:not(:button):not(:radio)").each(function(){
            if($(this).hasClass('required') && $(this).val().trim()==''){
                if( !$(this).parent().hasClass('has-error') ){
                    $(this).parent().addClass('has-error');
                    $("<p class='text-error' validate='"+$(this).attr('name')+"'>El campo "+ $(this).attr('name') +" es requerido.</p>").insertAfter( $(this) ); 
                    
                    $(document).off("blur change", "#" + $(this).attr('name'), function(){
                        validaField( $(this) );
                    });
                    
                    $(document).on("blur change", "#" + $(this).attr('name'), function(){
                        validaField( $(this) );
                    });
                }
                count++;
            }
        });
        
        return count>0 ? false : true;
    };
    
    var validaField = function( field ){
        if( field.val().length == 0){
            if( !field.parent().hasClass('has-error') ){
                field.parent().addClass('has-error');
                $("<p class='text-error' validate='"+field.attr('name')+"'>El campo "+ field.attr('name') +" es requerido.</p>").insertAfter( field ); 
            }
        }else{
            field.parent().removeClass('has-error');
            field.next().remove();
        }
    };
</script>
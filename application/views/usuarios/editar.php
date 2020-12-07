<?php extract($data); ?>

<div class="page-header">    
    <div class="icon">
        <img alt="Usuarios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_usuarios']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Usuarios", "Usuarios");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('Editar Usuario', "Editar Usuario"); ?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <div class="data-fluid">

                <?php echo form_open("usuarios/editar/{$user->id}"); ?>

                <?php echo form_hidden('id', $user->id); ?>

                <div class="row-form">
                  <div class="form-group">  
                    <div class="col-xs-12">
                    <label for="first_name" class="col-sm-2 control-label" >
                        <?php echo custom_lang('sima_name', "Nombre"); ?>:    
                    </label>
                    <div class="col-sm-4">
                        <?php echo form_input($first_name); ?>
                        <?php echo form_error('first_name'); ?>
                    </div>
                    <label for="last_name" class="col-sm-2 control-label">
                            <?php echo custom_lang('sima_last_name', "Apellido"); ?>:       
                       </label>                   
                    <div class="col-sm-4">
                        <?php echo form_input($last_name); ?>
                        <?php echo form_error('last_name'); ?>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="row-form">
                 <div class="form-group">
                   <div class="col-xs-12">
                    <label for="company" class="col-sm-2 control-label">
                        <?php echo custom_lang('sima_company', "Compa&ntilde;&iacute;a"); ?>:    
                    </label>
                    <div class="col-sm-4">
                        <?php echo form_input($company); ?>
                        <?php echo form_error('company'); ?>
                    </div>
                    <label class="col-sm-2 control-label" for="email">
                       <?php echo custom_lang('sima_email', "Correo electr&oacute;nico"); ?>:
                    </label>

                    <div class="col-sm-4">
                        <?php echo form_input($email); ?>
                        <?php echo form_error('email'); ?>
                    </div>
                   </div>
                 </div>
                </div>

                <div class="row-form">
                  <div class="form-group">
                    <div class="col-xs-12">                       
                        <label for="phone1" class="col-sm-2 control-label"><?php echo custom_lang('sima_phone', "Tel&eacute;fono"); ?>:</label>
                        <div class="col-sm-4">
                            <?php echo form_input($phone1); ?>
                            <?php echo form_error('phone1'); ?>
                        </div>
                        <label for="password" class="col-sm-2"><?php echo custom_lang('sima_password', "Clave"); ?>:</label>
                        <div class="col-sm-4">
                            <?php echo form_input($password); ?>
                            <?php echo form_error('password'); ?>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="row-form">
                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password_confirm" class="col-sm-2 control-label"><?php echo custom_lang('sima_password_confirm', "Confirmar clave"); ?>:</label>
                    <div class="col-sm-4">
                        <?php echo form_input($password_confirm); ?>
                        <?php echo form_error('password_confirm'); ?>
                    </div>
                    <label for="almacen" class="col-sm-2 control-label"><?php echo custom_lang('sima_almacen', "Almacén"); ?>:</label>

                
                    <div class="col-sm-4">                            
                        <?php
                        //$selected1 = "";
                      /*  echo "<select  name='almacen' id='almacen'>";
                        foreach ($data['almacen'] as $f) {
                            if ($f->id == $almacen_usuario->almacen_id) {
                                $selected = " selected=selected ";
                            } else {
                                $selected = "";
                            }
                            echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                        }
                        if ($user->is_admin == 'a' || $user->is_admin == 't' ) {
                            $selected1 = " selected=selected ";
                        }
                        echo "<option $selected1 value='-1'>Ver la informacion de todos los almacenes</option>";
                        echo "</select>";*/
                                    
                            $attr_almacen = array();
                            $attr_almacen[-1] = 'Ver la información de todos los almacenes';
                            $usu=0;                            
                            $selected="";
                            foreach ($data['almacen'] as $row):
                               
                                if ($row->id == $almacen_usuario->almacen_id) {                                    
                                    $attr_almacen[$row->id] = $row->nombre;
                                    $selected = $row->id;
                                } 

                                // if(isset($data["definecrear"][$row->id]['usuarios']) && ($data["definecrear"][$row->id]['usuarios']==1)){
                                    $usu++;
                                    $attr_almacen[ $row->id ] = $row->nombre;
                                // }
                            endforeach;
                             
                            if ($user->is_admin == 'a' || $user->is_admin == 't' ) {
                                $selected = -1;                               
                            }                                         
                            echo  form_dropdown('almacen', $attr_almacen, $selected, "id='almacen' class='form-control required' "); 
                        ?>
                       
                    </div>
                    </div>
                </div>
                </div>

                <div class="row-form">
                <div class="form-group">
                    <div class="col-xs-12">
                        <?php if ($data['valor_caja'] == 'si') { ?>
                        <label for="almacen" class="col-sm-2 control-label"><?php echo custom_lang('sima_almacen', "Caja"); ?>:</label>
                        <div class="col-sm-4">
                            <select name="caja" id="caja"><?php
                             $selected = "";
                                foreach ($data['caja'] as $f) {
                                    if ($f->id == $almacen_usuario->id_Caja) {
                                        $selected = " selected=selected ";
                                        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                    } else {
                                        echo "<option value=" . $f->id . ">" . $f->nombre . "</option>";
                                    }
                                }
                                ?> 
                            </select>
                            <?php 
                                if(empty($selected)){?>
                                    <script>
                                        $("#caja").html("<option value=''>Seleccione una Caja</option>");
                                        $("#caja").prop("disabled",true);
                                    </script>
                            <?php }                                
                            ?>
                        </div>
                        <?php } ?> 
                        <label for="rol_id" class="col-sm-2 control-label"><?php echo custom_lang('sima_rol', "Rol"); ?>:</label>
                          <div class="col-sm-4">
                            <?php echo form_dropdown('rol_id', $roles, set_value('rol_id', $user->rol_id)); ?>
                            <?php echo form_error('rol_id'); ?>
                         </div>
                    </div>
                        
                </div>    
                </div>
               <?php 
                    $style = '';
                    if ($user->id == $this->ion_auth->get_user_id()){  
                            $style = 'display: none;';
                         }
                ?>
                <div class="row-form" style="<?php echo $style ?>">
                 <div class="form-group">
                     <div class="col-xs-12 col-sm-12">
                        <div class="col-sm-6">     
                            <div class="row">
                                <label class="col-sm-4"><?php echo custom_lang('sima_password', "Tipo de Usuario"); ?>:</label>
                                <div class="col-sm-8">
                                    <label for="is_admin_admin" class="control-label">
                                        <?php echo form_radio('is_admin', 't', ($user->is_admin == 't'), 'id="is_admin_admin"'); ?>
                                        Administrador 
                                        <br/> <span class="help-block">Puede configurar sistema</span>
                                    </label>

                                    <label for="is_admin_all" class="control-label">
                                        <?php echo form_radio('is_admin', 's', ($user->is_admin == 's'), 'id="is_admin_all"');?>
                                        Ver solo la informaci&oacute;n del almac&eacute;n 
                                        <br/><span class="help-block">Informes e inventario</span>
                                    </label>

                                    <label for="is_admin_onlyitstore" class="control-label">
                                        <?php echo form_radio('is_admin', 'a', ($user->is_admin == 'a'), 'id="is_admin_onlyitstore"');?>
                                        Ver información de todos los almacenes
                                        <br/><span class="help-block">Informes, inventario, cierres de caja</span>
                                    </label>
                                    <?php echo form_error('is_admin'); ?>    
                                </div>
                            </div>   
                        </div> 
                        <label class="col-sm-2"><?php echo custom_lang('sima_password', "Desactivar Usuario"); ?>:</label>
                        <div class="col-sm-4">
                            <input id="desactivar_usuario" name="desactivar_usuario" type="checkbox" />
                            <?php echo form_error('desactivar_usuario'); ?>
                        </div>
                    </div>
                 </div>
                </div>
                <?php /* if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante"){ ?>
                <div class="row-form" style="<?php echo $style ?>">
                    <div class="form-group">
                        <div class="col-xs-12 ">                
                            <div class="col-sm-6">
                                <label for="estacion_pedido" class="control-label"><?php echo custom_lang('estacion_pedido', "¿Es estación de Pedidos?"); ?>:</label>
                                <label class="radio-inline">
                                    <?php echo form_radio('estacion_pedido', '1',($user->es_estacion_pedido == 1)); ?> <?php echo custom_lang('estacion_pedido', "Si"); ?>
                                    <?php echo form_error('estacion_pedido'); ?>
                                </label>
                                <label class="radio-inline">
                                    <?php echo form_radio('estacion_pedido', '0',($user->es_estacion_pedido == 0)); ?> <?php echo custom_lang('estacion_pedido', "No"); ?>
                                    <?php echo form_error('estacion_pedido'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } */ ?>
                <div class="toolbar bottom tar">
                    <div>
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>                        
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>

        </div>

    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {

        //Jeisson Rodriguez
        $('#v2Cont > div > div > div > div.row-fluid > div > div > div > form > div:nth-child(8) > div > div > div > label:nth-child(2)').click(function (){
            Swal.fire({
            type: 'info',
            title: '¿Estas seguro de activar esta funcionalidad?',
            html: '<p style="font-size: 15px;">Esta funcionalidad estará asociada para los meseros, ellos solo podrán ver el modulo de toma pedidos y para ingresar a este debe crear cada mesero desde el modulo de contacto-Mesoneros y en este modulo le darán un código de 4 dígitos para ingresar al sistema.<p>',
            showCloseButton: true
            });
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
                    //dataType: "json",
                    success: function(data) {                            
                        bodega=data.success;    
                        $.post("<?php echo site_url("usuarios/almacen_caja"); ?>", {
                            almacen: almacen,
                            caja: <?php echo isset($almacen_usuario->id_Caja) ?  $almacen_usuario->id_Caja:  '0'; ?>
                        }, function (data) {
                            $("#caja").html(data);
                            if(bodega==1){
                                $("#caja").prop('disabled',true);
                            }else{
                                $("#caja").prop('disabled',false);
                            }
                        });                                                  
                    }
                });
               
            });
        })

        $("#validate").submit(function () {

            if ($('#almacen').val() == '') {
                alert("debe escojer un almacen");
                return false;
            } else {

                return true
            }
        });

    });

</script>
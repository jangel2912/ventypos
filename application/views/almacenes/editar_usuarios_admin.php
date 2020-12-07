<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Usuarios", "Usuarios");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_user', "Editar Usuario");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open_multipart("almacenes/editar_usuarios_admin/".$data['data']['id'], array("id" =>"validate"));?>
        <div class="span6">
            <div class="block">
                <div class="data-fluid">
                    <input type="hidden" value="<?php echo set_value('id', $data['data']['id']); ?>" name="id" />
                    <div class="row-form">
                        <div class="span5">Base de datos:</div>
                        <div class="span7"><?php echo set_value('id', $data['data']['id'].' / '.$data['data']['db_config']); ?></div>
                    </div>
                    <div class="row-form">
                        <div class="span5"><?php echo custom_lang('sima_name', "Numero de Ventas");?>:</div>
                        <div class="span7"><input type="text"  disabled = "disabled"  value="<?php echo set_value('total_ventas', $data['data']['total_ventas']); ?>" placeholder="" name="total_ventas" />
                            <?php echo form_error('total_ventas'); ?>
                        </div>
                    </div>
                    <div class="row-form">
                        <div class="span5"><?php echo custom_lang('sima_name', "Numero de Productos");?>:</div>
                        <div class="span7"><input type="text" disabled = "disabled"  value="<?php echo set_value('nombre', $data['data']['total_productos']); ?>" placeholder="" name="total_productos" />
                                <?php echo form_error('total_productos'); ?>
                        </div>
                    </div>
                    <div class="row-form">
                        <div class="span5"><?php echo custom_lang('sima_name', "Numero de Almacenes");?>:</div>
                        <div class="span7"><input type="text"  value="<?php echo set_value('almacenes', $data['data']['almacenes']); ?>" placeholder="" name="almacenes" />
                                <?php echo form_error('almacenes'); ?>
                        </div>
                    </div>
                    <div class="row-form">
                        <div class="span5"><?php echo custom_lang('sima_name', "Tienda Online");?>:</div>
                        <div class="span7">
                            <select name="tienda"  data-value="<?php echo set_value('estado', $data['data']['tienda']);?>">
                                <option value="1">Habilitado</option>
                                <option value="0">Deshabilitado</option>                                                                      
                            </select>
                            <?php echo form_error('tienda'); ?>
                        </div>
                    </div>
                    <div class="row-form">
                        <div class="span5"><?php echo custom_lang('sima_name', "Estado");?>:</div>
                        <div class="span7">
                            <select name="estado" data-value="<?php echo set_value('estado', $data['data']['activo']);?>">
                                <option value="1">Habilitado</option>
                                <option value="0">Deshabilitado</option>                                                                                             
                            </select>
                            <?php echo form_error('estado'); ?>
                        </div>
                    </div>
                    <div class="row-form">  
                        <div class="span5"><?php echo custom_lang('fecha_pruebas', "Ampliar prueba (dias)"); ?>: </div>
                        <div class="span7">
                            <select name="dias_restantes" id="dias_restantes" data-value="0">
                                <?php 
                                    if($data['data']['estado_cliente'] == '2')
                                    {
                                        for($d=0; $d <= $data['data']['dias_restantes']; $d++)
                                        {
                                            if($d == 15)
                                                break;
                                            
                                            echo '<option value="'.$d.'">'.$d.'</option>';
                                        }
                                    } else {
                                        echo '<option value="0">0</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="block">
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('estado_cuenta', "Estado");?>: </div>
                    <div class="span7">
                        <select name="estado_cliente" data-value="<?php echo set_value('estado_cliente', $data['data']['estado_cliente']); ?>">
                            <option value="1">Producción</option>
                            <option value="2">Pruebas</option> 
                            <option value="3">Suspendido</option>                                                                                             
                        </select>
                    </div>
                </div>
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('alertas_inventario', "Alertas de inventario");?>: </div>
                    <div class="span7">
                        <select name="alertas_inventario" data-value="<?php echo set_value('alertas_inventario', $data['data']['alertas_inventario']); ?>">
                            <option value="0">Deshabilitado</option>
                            <option value="1">Habilitado</option>
                        </select>
                    </div>
                </div>
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('plan_separe', "Plan separe");?>: </div>
                    <div class="span7">
                        <select name="plan_separe" data-value="<?php echo set_value('plan_separe', $data['data']['plan_separe']); ?>">
                            <option value="0">Deshabilitado</option>
                            <option value="1">Habilitado</option>
                        </select>
                    </div>
                </div>
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('atributos', "Atributos");?>: </div>
                    <div class="span7">
                        <select name="atributos" data-value="<?php echo set_value('atributos', $data['data']['atributos']); ?>">
                            <option value="0">Deshabilitado</option>
                            <option value="1">Habilitado</option>
                        </select>
                    </div>
                </div>
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('puntos', "Puntos");?>: </div>
                    <div class="span7">
                        <select name="puntos" data-value="<?php echo set_value('puntos', $data['data']['puntos']); ?>">
                            <option value="0">Deshabilitado</option>
                            <option value="1">Habilitado</option>
                        </select>
                    </div>
                </div>
                <div class="row-form">  
                    <div class="span5"><?php echo custom_lang('alertas_inventario', "Offline");?>: </div>
                    <div class="span7">
                        <select name="offline" data-value="<?php echo set_value('offline', $data['data']['offline']); ?>">
                            <option value="0">Deshabilitado</option>
                            <option value="1">Habilitado</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="span12">
            <div class="toolbar bottom tar">
                <div class="btn-group">
                    <div class="span6">
                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                    </div>
                    <div class="span6">
                        <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../administrador_vendty'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>   
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('select').each(function(i, e){
            if ($(this).attr('data-value')){
                if ($.trim($(this).data('value')) !== '')
                {
                    var dato = $(this).data('value');
                    $(this).val(dato);
                }
            }
        });
    });
</script>

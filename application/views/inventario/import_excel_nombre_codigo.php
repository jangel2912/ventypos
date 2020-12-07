<?php  $is_admin = $this->session->userdata('is_admin'); ?>
<div class="page-header">    
    <div class="icon">
        <img alt="movimientos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_movimientos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("movimientos", "Movimientos");?></h1>
</div>
<?php echo form_open_multipart("inventario/import_excel_nombre_codigo", array("id" =>"validate"));?>
<div class="block title">

    <div class="head">

        <h4>Bienvenido siga los siguientes pasos para subir inventario desde Excel </h4> 
		<br />
        <h5>1. De click en la siguiente enlace para descargar la plantilla de excel <a href="<?php echo base_url("/uploads1/Plantilla Inventario codigo.xlsx"); ?>">CLICK AQUI</a> llamada Plantilla Inventario.</h5>
        <h5>2. Escoja la siguientes opciones:</h5>
<div class="row-fluid">
    <div class="block">
        
                            <div class="data-fluid">
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>
                                                <?php echo form_error('fecha'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <?php 
                                            $is_admin = $this->session->userdata('is_admin');
                                            $permisos = $this->session->userdata('permisos');
                                            $entrada = in_array('1019', $permisos) || $is_admin == 't' ? 1 : 0;
                                            $salida = in_array('1020', $permisos) || $is_admin == 't' ? 1 : 0;
                                            $traslado = in_array('1021', $permisos) || $is_admin == 't' ? 1 : 0;
                                        ?>
                                        <div class="span3"><?php echo custom_lang('tipo_movimiento', "Tipo");?>:</div>
                                        <div class="span9" id="movimiento" data-can="<?= $entrada.'-'.$salida.'-'.$traslado ?>">
                                                
                                                <?php echo form_dropdown('tipo_movimiento', $data['tipo']); ?>
                                                <?php echo form_error('tipo_movimiento'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('almacen', "Almacén");?>:</div>
                                        <div class="span9">
                                                <?php
												if($is_admin == 's'){
												 echo $data['almacen_nombre']; 
												   ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen_id" id="almacen_id"/><?php
												} 
												else{
												 echo form_dropdown('almacen_id', $data['almacenes']); 
												}
												 ?>
                                                <?php echo form_error('almacen_id'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3">
                                            <?php echo custom_lang('','Nota') ?>
                                        </div>
                                        <div class="span9">
                                            <textarea name="nota" id="nota" cols="30" rows="10" style="resize:none;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Proveedor");?>:</div>
                                        <div class="span9">
                                                <?php echo form_dropdown('proveedor_id', $data['proveedores']) ?>
                                                <?php echo form_error('proveedor_id'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Código factura");?>:</div>
                                        <div class="span9">
                                                <?php echo form_input('codigo_factura') ?>
                                                <?php echo form_error('codigo_factura'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date_v', "Almacén de traslado");?>:</div>
                                        <div class="span9">
                                            <?php
                                                echo form_dropdown('almacen_traslado_id', $data['almacenes'], $data['almacen_id'], "disabled='disabled'");
                                            ?>
                                            <?php echo form_error('almacen_traslado_id'); ?>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                </div>
                                </div>

			
        <h5>3. Abra el archivo Plantilla Inventario que descargo a su computador y comience a ingresar el código que tiene el producto tal como se ingresó en el sistema y la cantidad</a>.</h5>          
       <img src="<?php echo base_url("/public/img/");?>/csv_inventario_codigo_1.jpg?act=4" width="800px" />   
        <h5>4. Click en el boton buscar selecione la plantilla de excel que se encuentra en su computador con el inventario que que ingreso en el excel.</h5> 	
        <h5>5. Por último click en Guardar.</h5> 		   	
    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-error">

                    <?php echo $message;?>

                </div>

                <?php endif; ?>

                            <?php echo validation_errors(); ?>

                            <div class="data-fluid">


                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/>

                                    </div>

                                    <div class="span9">                            

                                        <div class="input-append file">

                                            <input type="file" name="archivo"/>

                                            <input type="text"/>

                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

                                        </div> 

                                        <?php echo $data['data']['upload_error']; ?>
                                    </div>

                                </div> 

                                <div class="toolbar bottom tar">
                                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    <button class="btn btn-success"  onclick="javascript:this.form.submit();this.disabled= true;"  type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                </div>

                            </div>

                            </form>

    </div>

    </div>
</div>

<script type="text/javascript">
                                  
    var can = $('#movimiento').data('can');
    var select_movimiento = $('select[name="tipo_movimiento"]');
    var movimientos = can.split('-');

   // console.log(movimientos, can);
    $.each(movimientos, function(i, e)
    {
        var selector = '';
        switch(i)
        {
            case 0:
                selector = 'entrada';
            break;
            case 1:
                selector = 'salida';
            break;
            case 2:
                selector = 'traslado';               
            break;
        }

        if(e == 0)
            select_movimiento.find('option[value^="'+selector+'"]').remove();
            
    });

    select_movimiento.trigger('change');

        //almacenes traslado
        var url_consulta_almacenes = '<?php echo site_url('almacenes/consultar_almacen') ?>';
    function almacenes(){
        almacen=$("select[name='almacen_id']").attr('value');           
        $.ajax({
            type: "post",
            dataType: "json",
            url: url_consulta_almacenes,
            data:{id:almacen},
            success: function(result){                                            
            $("select[name='almacen_traslado_id']").find('option').remove();
                $.each(result,function(index,value){                        
                    $("select[name='almacen_traslado_id']").append($('<option>', { value : value.id }).text(value.nombre));
                });
                
            }

        });
    }

    $("select[name='almacen_id']").change(function(){            
        almacenes();
    });
        
    $("select[name='tipo_movimiento']").change(function(){

        if($(this).val() == 'traslado'){
            $("select[name='almacen_traslado_id']").removeAttr('disabled');
            $("select[name='proveedor_id']").attr('disabled', 'disabled');
            $("input[name='codigo_factura']").attr('disabled', 'disabled');   
            almacenes();
        }
        else if($(this).val() == 'entrada_compra'){
            $("select[name='proveedor_id']").removeAttr('disabled');
            $("input[name='codigo_factura']").removeAttr('disabled');
            $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
        }
        else if($(this).val() == 'entrada_remision'){
            $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
            $("input[name='codigo_factura']").attr('disabled', 'disabled');   
        }
        else{
            $("select[name='almacen_traslado_id']").attr('disabled', 'disabled');
            $("select[name='proveedor_id']").attr('disabled', 'disabled');
            $("input[name='codigo_factura']").attr('disabled', 'disabled');
        }
    });


</script>								   	
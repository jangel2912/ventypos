<div class="page-header">    
    <div class="icon">
        <img alt="Promociones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_promociones']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Promociones", "Promociones");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_list_quotes', isset($promocion) ? "Editar Promoción" : "Crear Promoción");?></h2>                                          
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div id="message">
                <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                    <div class="alert alert-success">
                <?php echo $message;?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="data-fluid">
                <form id="form_promociones" action="<?php echo $promocion ? site_url('promociones/update/') : site_url('promociones/store/') ?>" method="post">
                    <div class="row-fluid">
                        <?php //var_dump($almacenes); ?>
                    </div>
                    <div class="row-fluid">
                        <div class="span4 control-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" value="<?= set_value('nombre', $promocion ? $promocion->nombre : ''); ?>">
                            <?php echo form_error('nombre'); ?>
                        </div>
                        <div class="span2 control-group">
                            <label for="fecha_inicial">Fecha Inicial</label>
                            <input type="text" name="fecha_inicial" data-role="fecha_inicio" value="<?= set_value('fecha_inicial', $promocion ? $promocion->fecha_inicial : ''); ?>">
                            <?php echo form_error('fecha_inicial'); ?>
                        </div>
                        <div class="span2 control-group">
                            <label for="fecha_final">Fecha Final</label>
                            <input type="text" name="fecha_final" data-role="fecha_fin"  value="<?= set_value('fecha_final', $promocion ? $promocion->fecha_final : ''); ?>">
                            <?php echo form_error('fecha_final'); ?>
                        </div>
                        <div class="span2 control-group">
                            <label for="hora_inicial">Hora Inicial</label>
                            <input type="text" name="hora_inicial" data-role="timepicker" value="<?= set_value('hora_inicio', $promocion ? $promocion->hora_inicio : ''); ?>">
                            <?php echo form_error('hora_inicial'); ?>
                        </div>
                        <div class="span2 control-group">
                            <label for="hora_final">Hora Final</label>
                            <input type="text" name="hora_final" data-role="timepicker" value="<?= set_value('hora_fin', $promocion ? $promocion->hora_fin : ''); ?>">
                            <?php echo form_error('hora_final'); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span8">
                            <p><br></p>
                            <label class="checkbox inline no-indent">
                                <input type="checkbox" name="dias[]" value="1" <?= in_array('1', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Lunes
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="dias[]" value="2" <?= in_array('2', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Martes
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="dias[]" value="3" <?= in_array('3', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Miércoles
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="dias[]" value="4" <?= in_array('4', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Jueves
                            </label>
                            <label class="checkbox inline no-indent	">
                                <input type="checkbox" name="dias[]" value="5" <?= in_array('5', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Viernes
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="dias[]" value="6" <?= in_array('6', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Sábado
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="dias[]" value="7" <?= in_array('7', explode(',', $promocion ? $promocion->dias : '')) ? 'checked' : ''; ?>>Domingo
                            </label>
                            <div><?php echo form_error('dias'); ?></div>
                        </div>
                        <div class="span2">
                            <br>
                            <label class="checkbox no-indent">
                                <input type="checkbox" name="activo" id="activo" value="1" <?=  $promocion && $promocion->activo == 1 ? 'checked' : ''; ?>>Activo
                            </label>
                        </div>
                        <div class="span2">
                            <label for="">Tipo</label>
                            <select name="tipo" id="tipo" data-value="<?= set_value('tipo', $promocion ? $promocion->tipo : '') ?>">
                                <option value="progresivo">Obsequio</option>
                                <option value="cantidad">Cantidad</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <label for="almacenes_disponibles">Almacenes</label>
                            <select name="almacenes_disponibles" id="almacenes_disponibles" data-role="transferible" data-target="almacenes_seleccionados" multiple>
                            <?php 
                            foreach ($almacenes as $key => $value) {
                                $seleccionado = false;
                                if($promocion)
                                {
                                        foreach ($promocion->almacenes as $almacen) 
                                        {
                                                if($key == $almacen->id_almacen) $seleccionado = true;
                                        }
                                } else {
                                        $seleccionado = false;
                                } 

                                if(!$seleccionado) echo '<option value="'.$key.'">'.$value.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                        <div class="span6">
                            <label for="almacenes_seleccionados">Almacenes</label>
                            <select name="almacenes_seleccionados" id="almacenes_seleccionados" data-role="transferible" data-target="almacenes_disponibles" multiple>
                                <?php 
                                    foreach ($almacenes as $key => $value) {
                                        $seleccionado = false;
                                        if($promocion)
                                        {
                                            foreach ($promocion->almacenes as $almacen) 
                                            {
                                                if($key == $almacen->id_almacen) $seleccionado = true;
                                            }	
                                        } else {
                                            $seleccionado = false;
                                        } 

                                        if($seleccionado) echo '<option value="'.$key.'">'.$value.'</option>';
                                    }
                                ?>
                            </select>
                            <?php echo form_error('almacenes'); ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <br>
                    </div>    
                    <div id="reglas"  <?php echo ($promocion && ($promocion->tipo !="progresivo")) ? "style='display:none'":""?> class="row-fluid">
                        <div class="span3">
                            Cantidad de Compra
                        </div>
                        <div class="span3">
                            Cantidad de Obsequio
                        </div>
                        <div class="span3">
                            Productos obsequios iguales a los de la compra
                        </div>
                        <div class="span3">
                            <input id="productosIguales" type="checkbox" name="productosIguales" value="1" <?php echo (isset($reglas['descuento']) && $reglas['descuento'] == 1) ? "checked='true'":""?>>
                        </div>
                    </div>
                    <div id="reglas" <?php echo ($promocion && ($promocion->tipo !="progresivo")) ? "style='display:none'":""?>>
                        <div class="row-fluid regla">
                            <div class="span3">
                                <input type="number" name="cantidad" min="0" value="<?php echo (isset($reglas['cantidad'])) ? $reglas['cantidad']:""  ?>">
                            </div>
                            <div class="span3">
                                <input type="number" name="producto_pos" min="0" value="<?php echo (isset($reglas['producto_pos'])) ? $reglas['producto_pos']: "" ?>">
                            </div>
                        </div>
                    </div>
                    <?php if($promocion){?>
                    <div class="row-fluid">
                        <div class="span12">
                            <br>
                            <a href="<?php echo site_url('promociones/productos/'.$promocion->id) ?>"><div class="icon iconbotones"><img alt="editar" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div>Editar productos compra</a>
                            <?php 
                            $disyplay = "";
                            $disyplay2 = "style='display:none'";
                            if($promocion->tipo == "progresivo" && $reglas['descuento'] == "1")
                            {
                                $disyplay = "style='display:none'";
                            }else if($promocion->tipo != "progresivo")
                            {
                                $disyplay = "style='display:none'";
                                $disyplay2 = "";
                            }
                            ?>
                            <a id="linkProducto" <?php echo  $disyplay ?> href="<?php echo site_url('promociones/productosDescuento/'.$promocion->id) ?>"><div class="icon iconbotones"><img alt="editar" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div>Editar productos descuento</a>
                            <a id="linkProducto2" <?php echo  $disyplay2 ?> href="<?= site_url('promociones/reglas/'.$promocion->id) ?>"><div class="icon iconbotones"><img alt="editar" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div>Editar reglas</a>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row-fluid">
                        <br>
                        <input type="hidden" name="id" value="<?= set_value('id',  $promocion ? $promocion->id : '0') ?>">
                        <input type="hidden" name="almacenes" value="">                        
                        <a href="<?php echo site_url('promociones')?>" class="btn btn-default">Cancelar</a>
                        <input type="submit" id="promo" class="btn btn-success" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="social">
		<ul>
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924674?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
  
<script>
$(function(){
    // Configuración de calendarios
    $('input[data-role="fecha_inicio"]').datepicker({
    dateFormat: 'yy-mm-dd',
    onSelect: function(selected) {
        var fecha_i = new Date(selected);
        fecha_i.setDate(fecha_i.getDate() + 1);
        $('input[data-role="fecha_fin"]').datepicker("option","minDate", fecha_i);
    }
});

$('input[data-role="fecha_fin"]').datepicker({
    dateFormat: 'yy-mm-dd',
    onSelect: function(selected) {
        $('input[data-role="fecha_inicio"]').datepicker("option","maxDate", selected);
    }
});

$(document).on('change','#productosIguales',function(){
   if($(this).prop('checked'))
   {
       $('#linkProducto').hide();
   }else
   {
       $('#linkProducto').show();
   }
});

var fecha_i = new Date($('input[data-role="fecha_inicio"]').val());
fecha_i.setDate(fecha_i.getDate() + 1);
$('input[data-role="fecha_fin"]').datepicker("option","minDate", fecha_i);

// Configuración de horas
$('input[data-role="timepicker"]').timepicker({
    timeFormat: 'G:i:s',
    step: 60
});

$('input[name="hora_inicial"]').timepicker('option', 'change', function(time)
{
    if($('input[data-role="fecha_inicio"]').val() == $('input[data-role="fecha_fin"]').val())
    {
        $('input[name="hora_final"]').timepicker('option', 'minTime', time);
    } else {
        $('input[name="hora_final"]').timepicker('option', 'minTime', new Date(0, 0, 0, 0, 0, 0));
    }
});

		// Configuración de selectores de almacenes
function transferir (target, option)
{
    $('#'+target).append(option);
    console.log(target);
}

$('select[data-role="transferible"]').delegate('option', 'click', function(e){
    var target = $(this).closest('select').data('target');
    transferir(target, $(this).clone());
    $(this).remove();
});

// Configuración formulario
$('#form_promociones').on('submit', function(e){
    $("#promo").prop('disabled',true);
    var almacenes = '';
    $('#almacenes_seleccionados option').each(function(i, e){
        almacenes += $(this).prop('value')+',';
    });

    $('input[name="almacenes"]').val(almacenes.slice(0, -1));
})
});
$(document).on('change','select#tipo',function(){
    var $this = $(this);
    if($this.val() != "")
    {
        $('#linkProducto2').show();
        $('#linkProducto').hide();
        $('div#reglas').hide();
    }else
    {
        $('#linkProducto2').hide();
        $('#linkProducto').show();
        $('div#reglas').show();
    }
});
</script>
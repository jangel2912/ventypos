<div class="page-header">    
    <div class="icon">
        <img alt="Libros de Precios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_libro_precio']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Libros de Precios", "Libros de Precios");?></h1>
</div>
<?php 
    $is_admin = $this->session->userdata('is_admin');
?>    
<?php echo form_open_multipart(site_url('lista_precios/importar'), array("id" =>"validate")); ?>
<div class="block title">
    <div class="head">
        <h4>Bienvenido siga los siguientes pasos para subir un libro de precios </h4> 
		<br />
        <div class="row">
            <div class="col-md-12">
                <h5>1. Seleccione un almacen</h5>
            </div>
            <div class="col-md-3">
                <select id="seleccionar-almacen" name="almacen" data-value="<?= set_value('almacen') ?>">
                    <option value="">Seleccionar..</option>
                    <?php 
                        foreach ($almacenes as $key => $value) {
                            echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
                        }
                    ?>
                </select>
                <?php echo form_error('almacen'); ?>
            </div>
        </div>
        <h5>2. Haga click en la siguiente enlace para descargar la plantilla. &nbsp;&nbsp;<a href="exportar" id="exportar">CLICK AQUI</a></h5>
        <h5>3. Escoja la siguientes opciones:</h5>
        <div class="row-fluid">
            <div class="block">
                <div id="form">
                    <!--Seleccionar fecha -->
                    <div class="row-form">
                        <div class="span2">Inicio      
                           <input type="text"  value="<?php echo set_value('inicio'); ?>" name="inicio" id="inicio"/>
                           <?php echo form_error('inicio'); ?>
                        </div>
                        <div class="span2">Fin    
                           <input type="text"  value="<?php echo set_value('termina'); ?>" name="termina" id="termina"/>
                           <?php echo form_error('termina'); ?>
                        </div> 
                        <div class="span4">Nombre de la lista:
                            <input type="text" value="<?php echo set_value('nombre') ?>" placeholder="" id="nombre" name="nombre">
                            <?php echo form_error('nombre'); ?>
                        </div>   
                    </div>
                    <!-- .......................................................... -->
                    <div class="row-form">
                    </div>
                    <div class="row-form">
                        <div class="span2">Grupo:</div>
                        <div class="span10">
                            <select id="seleccionar-grupo" name="grupo" data-value="<?= set_value('grupo') ?>">
                                <option value="">Seleccionar..</option>
                                <?php 
                                    foreach ($grupo_clientes as $key => $value) {
                                        echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
                                    }
                                ?>
                            </select>
                            <?php echo form_error('grupo'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <h5>3. Seleccione un archivo y haga click en Guardar:</h5>
		    <div class="block">
		        <div class="data-fluid">
		        	<div class="span6">
        				<div class="block">
        					<?php
                			$message = $this->session->flashdata('message');
            				if(!empty($message)):?>
								<div class="alert alert-error">
	                				<?php echo $message;?>
	            				</div>
                			<?php endif; ?>

                            <div class="data-fluid">
                                <div class="row-form">
                                    <div class="span3">
                                    	<?php echo custom_lang('sima_file', "Archivo");?>:<br/>
                                    </div>
                                    <div class="span9">                            
                                        <div class="input-append file">
                                            <input type="file" name="archivo"/>
                                            <input type="text"/>
                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>
                                        </div> 
                                        <?php echo $data['upload_error']; ?>
                                        <?php echo form_error('archivo'); ?>
                                    </div>
                                </div> 
                                <div class="toolbar bottom tar">
                                    <div class="">
                                        <button class="btn btn-default" type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>    
                                        <button class="btn btn-success" onclick="javascript:this.form.submit(); this.disabled=true;" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>										
                                    </div>
                                </div>
                            </div>
    					</div>
    				</div>
		        </div>
	        </div>
	    </div>
	</div>
</div>

<!--video-->
    <div class="social">
		<ul>
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">     
        <div style="padding:48.81% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266933816?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 
        
    </div>
    
<script type="text/javascript">
    $(document).ready(function()
    {
        var precio_almacen = <?= $precio_almacen;?>
        //
        $( "#inicio" ).datepicker({
            dateFormat: 'yy/mm/dd',
            onSelect: function(selected) {
                var fecha_i = new Date(selected);
                fecha_i.setDate(fecha_i.getDate() + 1);
                $("#termina").datepicker("option","minDate", fecha_i);
            }
        });
        //
        $( "#termina" ).datepicker({
            dateFormat: 'yy/mm/dd',
            onSelect: function(selected) {
                $("#inicio").datepicker("option","maxDate", selected);
            }
        });
        //
        var fecha_i = new Date($( "#inicio" ).val());
        fecha_i.setDate(fecha_i.getDate() + 1);
        $("#termina").datepicker("option","minDate", fecha_i);

        $("#seleccionar-almacen").change(function(){
            if(precio_almacen == 1){
                $("#exportar").attr("href","<?= site_url('lista_precios/exportar');?>"+"/"+$(this).val());
            }
        })

        $("#exportar").click(function(){
            if(precio_almacen == 1){
                if($("#seleccionar-almacen").val() == ''){
                    alert("Por favor seleccione un almacen");
                    return false;
                }else{
                    location.href="<?= site_url('lista_precios/exportar');?>"+"/"+$(this).val();
                }
            }
        })
    });
</script>
<?php echo form_close(); ?>

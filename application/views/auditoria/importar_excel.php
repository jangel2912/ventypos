<div class="page-header">    
    <div class="icon">
        <img alt="Auditoria" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_auditoria']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Auditoria", "Auditoría Inventario");?></h1>
</div>

<div class="block title">
	<div class="head">
		<h2><?php echo custom_lang('sima_importar_auditoria', "Importar Auditoría desde Excel"); ?></h2>
	</div>
</div>

<div class="row-form">
	<div class="col-xs-12">
		<h5>1. Seleccione las siguientes opciones para generar el excel de auditoría</h5>
	</div>
</div>
<?php echo form_open_multipart('auditoria/guardar_auditoria',array('id'=>'f_auditoria_excel')); ?>
<div class="row-form">
	<div class="col-xs-12">
		<div class="form-group">
			<label for="fecha_auditoria" class="col-xs-3 control-label">
				<?php echo custom_lang('sima_fecha_auditoria', "Fecha Auditoría"); ?>:                          
			</label>
			<div class="col-xs-9">
				<?php echo date("Y-m-d",now()) ?>
			</div>
		</div>
	</div>
</div>
<div class="row-form">
	<div class="form-group">
		<div class="col-xs-12">
			<label for="t_nombre_auditoria" class="col-xs-3 control-label">
				<?php echo custom_lang('sisma_nombre_auditoria','Nombre Auditoría') ?>
			</label>
			<div class="col-xs-9">
				<input type="text" name="t_nombre_auditoria" id="t_nombre_auditoria" required="required">
			</div>
		</div>
	</div>
</div>
<div class="row-form">
	<div class="form-group">
		<div class="col-xs-12">
			<label for="ta_descripcion_auditoria" class="col-xs-3 control-label">
				<?php echo custom_lang('sisma_descripcion','Descripción') ?>
			</label>
			<div class="col-xs-9">
				<textarea name="ta_descripcion_auditoria" id="ta_descripcion_auditoria"></textarea>
			</div>
		</div>
	</div>
</div>

<div class="row-form">
	<div class="col-xs-12">
		<div class="form-group">
			<label for="s_almacen" class="col-xs-3 control-label">
				<?php echo custom_lang('sisma_almacen','Almacén'); ?>
			</label>

			<div class="col-xs-9">
				<?php if($this->session->userdata('is_admin') == 't' && isset($data['almacenes'])){ ?>		
				<select name="s_almacen" id="s_almacen" required="required">
					<option value="">Seleccione</option>
					<?php foreach ($data['almacenes'] as $key => $value) { ?>
					<option value="<?php echo $value->id ?>"><?php echo $value->nombre; ?></option>	
					<?php }   	?>
				</select>
				<?php }else{  ?>
					<select name="s_almacen" id="s_almacen" required="required">
						<option value="">Seleccione</option>
						<option value="<?php echo $data['id'] ?>"><?php echo $data['nombre_almacen'] ?></option>
					</select>

				<?php } ?>
			</div>
		</div>	
	</div>
</div>

<div class="row-form">
	<div class="col-xs-12">
		<div class="form-group">
			<label for="s_almacen" class="col-xs-3 control-label">
				<?php echo custom_lang('sisma_almacen','Ajustar valor del inventario'); ?>
			</label>	
			<div class="col-xs-9">
				<input type="checkbox" name="ch_ajustar">	
			</div>
		</div>
	</div>		
</div>
<div class="row-form">
	<div class="col-xs-12">
		<h5>2. De click en el siguiente enlace para descargar la plantilla de excel <a href="#" id="a_generar_excel">CLICK AQUÍ</a> de los productos del almacén</h5>	
	</div>
</div>

<?php echo form_close(); ?>
<div class="row-form">
	<div class="col-xs-12" id="div_importar_excel">
		<h5>3. Modifique el archivo de excel agregando la cantidad contada en la columna "cantidad contada" ( columna D), puede escribir una observación en la columna "observación" (columna E). Luego cargue el archivo en el siguiente campo y de click en importar para cargar el archivo modificado.</h5>
		<div class="alert alert-danger">
			<p>
				<strong>Consideraciones antes de subir el archivo</strong>
				<ul>
					<li>No elimine filas de productos.(La cantidad de productos debe ser la misma que en la plantilla descargada).</li>
					<li>No suba el archivo con filtros aplicados.</li>
					<li>Si algunos productos no han sido auditados y no quiere cambiar su cantidad, la columna cantidad contada deberá tener la misma información de la columna cantidad del sistema. No debe dejar en cero a no ser que esta sea la cantidad real.</li>
				</ul>
			</p>
		</div>
		<?php echo form_open_multipart('auditoria/cargar_auditoria_desde_excel',array('id'=>'f_archivo_excel')) ?>	
			<div class="span3">
				<div class="input-append file">

					<input type="file" name="f_archivo_soporte" required/>

					<input type="text"/>

					<button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

				</div> 
			</div> 
			<div class="span3">
				<!--<input type="file" name="f_archivo_soporte" required>-->
				<button class="btn btn-default"  type="button" onclick="javascript:location.href = '../auditoria/index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
				<button class="btn btn-success" id="btn_importar">Importar</button>				
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<div class="row-form">
	<div class="col-xs-12">
		<div id="div_progress" class="progress">
		  <div class="progress-bar progress-bar-success"  role="progressbar" style="width: 0%"></div>			
		</div>
	</div>
	<div class="col-xs-12">
		<div id="div_mensajes">
			
		</div>
	</div>
</div>
<div class="row-form">
	<div class="col-xs-12 text-center ">
		<div id="div_cargando" class="cargando" style="display:none">
		  <div class="">Espere...<br><img src="<?php echo base_url(); ?>public/img/loaders/loading_icon.gif" alt="Cargando" height="42" width="42"></div>			
		</div>
	</div>
	<div class="col-xs-12">
		<div id="div_mensajes">
			
		</div>
	</div>
</div>
<div class="row-form">
	<div class="col-xs-12" style="display: none;" id="div_enviar_formulario">
		<button id="btn_formulario_excel" class="btn btn-success">Guardar Auditoría</button>
		<button class="btn btn-default" id="cancelar" type="button" onclick="javascript:location.href = '../auditoria/index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
	</div>
</div>
<div class="row-form">
	<div class="col-xs-12">
		<table id="tb_productos_auditoria" class="table aTable">
			<thead>
				<tr>
					<th>Código</th>
					<th>Producto</th>
					<th>Cantidad contada</th>
					<th>Observación</th>	
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
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
			<iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266765364?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>    
    </div>
     
<script type="text/javascript">
	var data_table;
	var url_excel = '<?php echo site_url('auditoria/generar_excel_para_auditoria')  ?>';
	var url_procesar_excel = '<?php echo site_url('auditoria/cargar_auditoria_desde_excel') ?>';
	var productos_auditados = [];
	$(document).ready(function(){
		$("#f_archivo_excel").submit(function(evt){
			evt.preventDefault();
		});
	});

	$("#a_generar_excel").on('click',function(){

		if( isNaN( $("#s_almacen").val())){
			swal('Generar Excel','Debe seleccionar el almacén al cual va realizar auditoria','warning');
		}else{
			var form = $('<form type="post"></form>');
			form.attr('action',url_excel);

			form.append('<input type="text" name="s_almacen" value="'+$("#s_almacen").val()+'">');
			$('body').append(form);
			form.submit();	
		}
	});  

	$("#btn_importar").on('click',function(){
		form_data = new FormData(document.getElementById("f_archivo_excel"));
		$.ajax({
			url:url_procesar_excel,
        	type: 'POST',
        	dataType: 'json',
        	data:  form_data,
        	processData: false,
        	contentType: false,
        	beforeSend: function(){
                $("#div_mensajes").hide('fast');
				$("#div_cargando").css('display','block');
                $("#div_progress .progress-bar").css('width','80%');				
                $("#btn_enviar").attr('disabled','disabled');
       		},
       		 success: function(result){
	            $("#div_mensajes").html('');
	            $("#div_mensajes").removeClass();
				$("#div_cargando").css('display','none');
	            $("#div_progress .progress-bar").css('width','100%');
	            if(result.success){
	                $("#div_mensajes").addClass('alert alert-success');
	                $("#div_mensajes").html(result.error_message);
	                armar_tabla_productos(result.datos_productos);
	                $("#div_importar_excel").hide();
	                $("#div_enviar_formulario").show();
	                $("#div_progress .progress-bar").css('width','0%');
	            }else{

	                $("#div_mensajes").addClass('alert alert-error');
	                $("#div_mensajes").html(result.error_message);
	                

	            }
	            $("#div_mensajes").show('slow');
	        }
		});
	});

	$("#btn_formulario_excel").on('click',function(){
		enviar_datos();
	});

	function enviar_datos(){
		form_data = new FormData(document.getElementById("f_auditoria_excel"));
		form_data.append('productos',JSON.stringify(productos_auditados));

		$.ajax({
	        url: $("#f_auditoria_excel").attr('action'),
	        type: 'POST',
	        dataType: 'json',
	        data:  form_data,
	        processData: false,
	        contentType: false,
	        beforeSend: function(){
	                $("#div_mensajes").hide('fast');
					$("#div_cargando").css('display','block');
	                $("#div_progress .progress-bar").css('width','80%');	                
	                $("#btn_formulario_excel").attr('disabled','disabled');
	                $("#cancelar").prop("disabled", true );
	        },
	        success: function(result){
	            $("#div_mensajes").html('');
	            $("#div_mensajes").removeClass();
				$("#div_cargando").css('display','none');
	            $("#div_progress .progress-bar").css('width','100%');
				$("#cancelar").prop("disabled", false );
				
	            if(result.status){
	                $("#div_mensajes").addClass('alert alert-success');
	                $("#div_mensajes").html(result.error_message);
	            }else{
					$("#btn_formulario_excel").prop('disabled',false);
	                $("#div_mensajes").addClass('alert alert-error ');
	                $("#div_mensajes").html(result.error_message);

	            }
	            $("#div_mensajes").show('slow');
	        }
	    });
	}

	function armar_tabla_productos(datos){
		var contenido_tabla = '';
		productos_auditados = datos;

		$.each(datos,function(key,value){		
			if(parseFloat(value.cantidad_contada)>=0){
				contenido_tabla+='<tr>';
				contenido_tabla+='<td>'+value.codigo+'</td>';
				contenido_tabla+='<td>'+value.nombre+'</td>';
				contenido_tabla+='<td>'+value.cantidad_contada+'</td>';
				contenido_tabla+='<td>'+value.observacion_adicional+'</td>';
				contenido_tabla+='</tr>';
			}			
		});

		$("#tb_productos_auditoria tbody").html(contenido_tabla);

		if ( ! $.fn.DataTable.isDataTable( '#tb_productos_auditoria' ) ) {
          data_table = $('#tb_productos_auditoria').dataTable();
      	}else{
          data_table.fnDraw();
      	} 
	}
</script>

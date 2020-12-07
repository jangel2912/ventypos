<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_informe_auditoria', "Auditoría Inventario"); ?></h2>
    </div>
</div>

<div class="row-fluid">
   	<?php
	   echo form_open('auditoria/generar_excel_nforme_auditoria',array('id'=>'f_consultar_auditoria'));
	?>
	<div class="col-md-3 col-xs-3">
   		<div class="form-group">
   			<label for="t_fecha_inicial">Fecha Inicial</label>
   			<input type="text" class="t_fecha" name="t_fecha_inicial" id="t_fecha_inicial">
   		</div>
   	</div>
	<div class="col-md-3 col-xs-3">
   		<div class="form-group">
   			<label for="t_fecha_final">Fecha Final</label>
   			<input type="text" class="t_fecha" name="t_fecha_final" id="t_fecha_final">
   		</div>
   	</div>
	<div class="col-md-6 col-xs-6">
		<div class="form-group">
			<br>
			<!--<input type="button" class="btn btn-success" id="btn_consultar" value="Consultar">&nbsp;
			<input type="submit" class="btn btn-success" value="Generar excel">-->
			<span>
				<a data-tooltip="Consultar" id="btn_consultar">                        
					<img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
				</a> 
			</span>
			<span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a data-tooltip="Descargar Excel" onclick="verificar()">                        
					<img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
				</a>
			</span>
		</div>
		
	</div>
	
	<div class="col-md-12">
		<?php
		if($this->session->userdata('is_admin') == 't' && isset($data['almacenes'])){ ?>
		<div class="col-md-3 col-xs-3">
			<div class="form-group">
				<label for="s_almacen"><?php echo custom_lang('sisma_almacen','Almacén'); ?></label>
				<select name="s_almacen" id="s_almacen">
					<option value="todos">Todos los Almacenes</option>
					<?php foreach ($data['almacenes'] as $key => $value) { ?>
							<option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
					<?php } ?>
				</select>
			</div>		
		</div>
	<?php }?>
		<div class="col-md-3 col-xs-3">
			<div class="form-group">
				<label for="s_auditoria">Idenfiticación Auditoría</label>
				<select name="s_identificacion_auditoria" id="s_identificacion_auditoria">
					<option value="">Seleccione</option>
					<?php if(isset($data['auditorias_realizadas'])){ 
						foreach ($data['auditorias_realizadas'] as $key => $value) { ?>
								<option value="<?php echo $value->id ?>"><?php echo $value->nombre_auditoria ?></option>
					<?php	}	
					} ?>
				</select>
			</div>
		</div>   	
	</div>   	
	
   	<?php echo form_close(); ?>
</div>
<br><br>
<div class="row-fluid">
	<div class="col-xs-12">
		<table id="tb_informe_auditoria" class="table aTable"></table>
	</div>
</div>

<script type="text/javascript">
 var data_table;
	$(document).ready(function(){

		$("#s_almacen").change(function(){
			consultar_auditorias_almacen($(this).val());
		});

		$(".t_fecha").datepicker({dateFormat:"yy-mm-dd"});
		
		$("#btn_consultar").on('click',function(){
			 consultar_datos();
		});
	});

	function consultar_auditorias_almacen(id_almacen){
		$.ajax({
			type: "post",
			url: '<?php echo site_url('auditoria/consultar_auditorias_almacen') ?>',
			data:{almacen:id_almacen},
			dataType: "json",
			beforeSend: function(){
				$("#s_almacen").after('<span class="span_consultando">consultando....</span>');	
			},
			success: function(result){
				$("#s_almacen").nextAll('.span_consultando').remove();
				$("#s_identificacion_auditoria").find('option').remove();
				$("#s_identificacion_auditoria").append('<option value ="">Seleccione</option>');
				$.each(result.data,function(index,value){
					$("#s_identificacion_auditoria").append('<option value="'+value.id+'">'+value.nombre_auditoria+'</option>');
				});	
			}
		});
	}
	function verificar(){		
		var t_fecha_inicial = $("#t_fecha_inicial").val();
		var t_fecha_final = $("#t_fecha_final").val();
		var s_auditoria = $("#s_identificacion_auditoria").val();
		if(((t_fecha_inicial !="") &&(t_fecha_final !="")) ||(s_auditoria != "")){
			$('#f_consultar_auditoria').submit();
		}else{
			swal('Busqueda auditoria', 'Debe seleccionar al menos un criterio de busqueda', 'warning');
		}
		
	}
	function consultar_datos(){
		var t_fecha_inicial = $("#t_fecha_inicial").val();
		var t_fecha_final = $("#t_fecha_final").val();
		var s_auditoria = $("#s_identificacion_auditoria").val();
		if(((t_fecha_inicial !="") &&(t_fecha_final !="")) ||(s_auditoria != "")){
			$.ajax({
				type:"post",
				url: '<?php echo site_url('auditoria/generar_informe_auditoria') ?>',
				data: $("#f_consultar_auditoria").serialize(),
				dataType: "json",
				success: function(result){

					if(result.success){
						armar_tabla_informe(result);
					}else{
						swal('Busqueda auditoria', result.error_message, 'warning');
					}
				}
			});
		}else{
			swal('Busqueda auditoria', 'Debe seleccionar al menos un criterio de busqueda', 'warning');
		}
	}

	function armar_tabla_informe(result){
		var thead ='<thead><tr>';
		var tfoot ='<thead><tr>';
		$.each(result.cabecera,function(index,value){
			thead+='<th>'+value+'</th>';
			tfoot+='<th>'+value+'</th>';
		});
		thead+='</tr></thead>';
		var tbody = '<tbody>';
		$.each(result.contenido,function(index,value){
			tbody+='<tr>';
			 $.each(value,function(index2,contenido){
			 	tbody+='<td>'+contenido+'</td>';
			 });
			tbody+='</tr>';
		});
		tbody+='</tbody>';
		$("#tb_informe_auditoria").html(thead+tbody+tfoot);
		if ( ! $.fn.DataTable.isDataTable( '#tb_informe_auditoria' ) ) {
          data_table = $('#tb_informe_auditoria').dataTable();
      	}else{
          data_table.fnDraw();
      	} 
	}

	mixpanel.track("informe_auditoria");  
</script>
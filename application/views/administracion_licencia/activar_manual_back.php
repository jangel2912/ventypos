<div class="row">
	<div class="hpanel">
	  <div class="panel-body">
<script src="<?php echo base_url('public/js/plugins/cleave/cleave.min.js') ?>"></script>
<?php 
$atributos= array('id'=>'f_activar_licencia','class'=>'');
echo form_open('administracion_vendty/activaciones_licencia/guardar',$atributos) ?>
<div class="row">
	<div class="col-md-12">
		<center><h2><?php echo custom_lang('Datos_para_licencia','Datos para crear licencia') ?></h2></center>
	</div>
</div>
<div class="row">
 <div class="col-md-4">
 	<div class="form-group">
		<label class="col-md-6"><?php echo custom_lang('empresa','Empresa') ?></label>
		
		<select name="s_empresa" id="s_empresa" class="form-control chosen-select">
			<option value="">Seleccione</option>
			<?php foreach ($data['empresas'] as $key => $value) { ?>
				<option value="<?php echo $value->idempresas_clientes ?>"><?php echo $value->nombre_empresa ?></option>
			<?php } ?>
		</select>
		<div id="div_consulta_ajax"></div>
		
	</div>	
 </div>
 <div class="col-md-4">
 	<div class="form-group">
		<label class="col-md-6"><?php echo custom_lang('plan','Plan') ?></label>
		<select name="s_plan" id="s_plan" class="form-control chosen-select" >
			<option value="">Seleccione</option>
			<?php foreach ($data['planes'] as $key => $value) { ?>
					<option data-vigencia="<?php echo $value->dias_vigencia ?>" data-valor_plan="<?php echo $value->valor_plan ?>" data-iva="<?php echo $value->iva_plan ?>" data-total_plan="<?php echo $value->valor_final ?>" value="<?php echo $value->id ?>"><?php echo $value->nombre_plan ?></option>
			<?php } ?>
		</select>
	</div>	
 </div>
 <div class="col-md-4">
   		<div class="form-group">
			<label class="col-md-6"><?php echo custom_lang('almacen','Almacen') ?></label>
			<select name="s_almacen[]" id="s_almacen" class="form-control chosen-select" multiple="multiple">
				
			</select>
		</div>			
   </div>
</div>
<div class="row">
<div class="col-md-4">
 	<div class="form-group">
		<label class="col-md-6"><?php echo custom_lang('fecha_inicio','Fecha inicio licencia') ?></label>
		<input type="text" name="t_fecha_inicio" id="t_fecha_inicio" class="form-control datepicker-input">
	</div>
 </div>
 <div class="col-md-4">
 	<div class="form-group">
 		<label class="col-md-12"><?php echo custom_lang('observacion_adicional_licencia','Observacion adicional licencia') ?></label>
 	 	<textarea name="ta_observacion_adicional_licencia" class="form-control"></textarea>	
 	</div> 
 </div>
</div>
<div class="row">
   <div class="col-md-12">
   		<center><h2><?php echo custom_lang('datos_pago','Datos del pago') ?></h2></center>
   </div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-6"><?php echo custom_lang('forma_pago','Forma de pago') ?></label>
			<select name="s_forma_pago" id="s_forma_pago" class="form-control chosen-select">
				<option value="">Seleccione</option>
				<?php foreach ($data['formas_pago'] as $key => $value) { ?>
					<option value="<?php echo $value->idformas_pago ?>"><?php echo $value->nombre_forma ?></option>
				<?php } ?>
			</select>	
		</div>	
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-6"><?php echo custom_lang('fecha_pago','Fecha Pago') ?></label>
			<input type="text" name="t_fecha_pago" class="form-control datepicker-input">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-6"><?php echo custom_lang('soporte_pago','Soporte de pago') ?></label>
		</div>
		
		
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-12"><?php echo custom_lang('valor_antes_impuesto','Valor pagado antes de impuesto') ?></label>
			<input type="text" name="t_valor_antes_impuesto" class="form-control input-number">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-12"><?php echo custom_lang('valor_impuesto','Valor impuesto') ?></label>
			<input type="text" name="t_valor_impuesto" class="form-control input-number">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-12"><?php echo custom_lang('valor_total','Valor total') ?></label>
			<input type="text" name="t_valor_total" class="form-control input-number">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-12"><?php echo custom_lang('fecha_consolidado_pago','Fecha en que se confirmo el pago') ?></label>
			<input type="text" name="t_fecha_conciliacion" class="form-control datepicker-input">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="col-md-12"><?php echo custom_lang('observacion_adicional_pago','Observacion adicional del pago') ?></label>
			<textarea name="ta_observacion_adicional_pago" class="form-control"></textarea>
		</div>
		
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<center><h2><?php echo custom_lang('datos_factura','Informacion para factura') ?></h2></center>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label><?php echo custom_lang('fecha_factura','Fecha factura') ?></label>
			<input type="text" name="t_fecha_factura" class="form-control datepicker-input">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label><?php echo custom_lang('fecha_vencimiento_factura','Fecha vencimiento factura') ?></label>
			<input type="text" name="t_fecha_vencimiento_factura" class="form-control datepicker-input">
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label><?php echo custom_lang('descripcion_adicional_factura','Informacion adicional en factura') ?></label>
			<textarea name="ta_observacion_adicional_factura" class="form-control"></textarea>	
		</div>
		
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div id="div_mensajes"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<button type="submit" class="btn btn-primary"><?php echo custom_lang('submit','Enviar') ?></button>
		<button type="button" class="btn btn-warning"><?php echo custom_lang('cancelar','Cancelar') ?></button>
	</div>
</div>
<?php 
echo form_close();
?>
</div>		
	</div>
</div>
<script type="text/javascript">
	//hay js de creacion importados de la librery de grocery_crud
	var js_date_format = 'yy-mm-dd';
	var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>'; 
	var cleaveNumeral = new Cleave('.input-number', {
    	numeral: true,
    	numeralThousandsGroupStyle: 'thousand'
	});
	$(document).ready(function(){
		$("#s_empresa").on('change',function(){
			consultar_almacen_empresa();
		});

		$("#f_activar_licencia").on('submit',function(e){
			e.preventDefault();
			enviar_formulario();
		});

		$('.input-number').toArray().forEach(function(field){
			new Cleave(field, {
			  numericOnly: true,
			  numeral: true,
    		  numeralThousandsGroupStyle: 'thousand'
			});
		});		
	});

	function consultar_almacen_empresa(){
		var empresa = $("#s_empresa").val();
		if(!isNaN(empresa)){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:empresa},
				success: function(result){
					console.log(result);
					$("#s_almacen").find('option').remove();
					$("#s_almacen").append($("<option></option>").attr("value",'-1').text('Crear almacen adicional'));
					$.each(result,function(index,value){
						console.log(value);
						$("#s_almacen").append($("<option></option>").attr("value",value.id).text(value.nombre));
					});
					$("#s_almacen").trigger("chosen:updated");
				}

			});
		}
	}

	function enviar_formulario(){
		$.ajax({
			type:"post",
			url: $("#f_activar_licencia").attr('action'),
			data: $("#f_activar_licencia").serialize(),
			dataType: "json",
			beforeSend: function(){
				$("div_mensajes").hide('fast');
			},
			success: function(result){
				//console.log(result);
				$("#div_mensajes").removeClass();
				$("#div_mensajes").html('');
				if(result.success){
					$("#div_mensajes").addClass('alert alert-success');
					$("#div_mensajes").html(result.error);		
				}else{
					$("#div_mensajes").addClass('alert alert-danger');
					$("#div_mensajes").html(result.error);
					$("#div_mensajes").show('slow');
				}
			}
		});
	}

</script>
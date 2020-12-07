<div class="row">
	<div class="hpanel">
	  <div class="panel-body">
	  		<div style='height:20px;'></div>  
				<div class="row">
					<div class="col-md-12">
						<?php echo $gc->output; ?>
					</div>
				</div>	
	  	</div>		
	</div>
</div>
	

<script type="text/javascript">
var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
$(document).ready(function(){
	$("#field-id_distribuidores_licencia").on('change',function(){ 
		consultar_distribuidores_licencia();
	});
	$("#field-idempresas_clientes").on('change',function(){
		consultar_almacen_empresa();
	});

	$(".facturar_licencia").on('click',function(evt){
		console.log($(this).attr('href'));
		evt.preventDefault();
		var valor= prompt('Valor unitario de la licencia');
		if(isNaN(valor) ){
			alert('debe digitar un valor numerico');
		}else{
			window.location.href=$(this).attr('href')+'/'+valor
		}
	});
});

function consultar_distribuidores_licencia(){
	$.ajax({
		 type: 'post',
		 url: url_distribuidores,
		 data: {distribuidor:$("#field-id_distribuidores_licencia").val()},
		 dataType: 'json',
		 success: function(result){
		 	$("#field-id_user_distribuidor").find('option').remove();
		 	$.each(result,function(index,value){
		 		$("#field-id_user_distribuidor").append($('<option>', { value : value.id }).text(value.email));
		 	});
		 	$("#field-id_user_distribuidor").trigger("chosen:updated");
		 }

	});
}	

function consultar_almacen_empresa(){
		var empresa = $("#field-idempresas_clientes").val();
		if(!isNaN(empresa)){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:empresa},
				success: function(result){
					
					$("#field-id_almacen").find('option').remove();
					$("#field-id_almacen").append($("<option></option>").attr("value",'').text('seleccione'));
					$.each(result,function(index,value){
						console.log(value);
						$("#field-id_almacen").append($("<option></option>").attr("value",value.id).text(value.nombre));
					});
					$("#field-id_almacen").trigger("chosen:updated");
				}

			});
		}
	}
</script>
</body>
</html>

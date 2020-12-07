<div class="page-header">    
    <div class="icon">
        <img alt="Promociones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_promociones']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Promociones", "Promociones");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_list_quotes', "Editar las Reglas de la Promoción");?></h2>                                          
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
			<form id="form_promociones_reglas" action="<?= site_url('promociones/sync_reglas') ?>" method="post">
				<div class="row-fluid">
					<div class="span1 center">
						Orden
					</div>
					<div class="span3">
						Cantidad
					</div>
					<div class="span3">
						Descuento (%)
					</div>
					<div class="span1">
						&nbsp;
					</div>
				</div>
				<div id="reglas">
					<?php 
						$indice = 0;
						foreach ($reglas as $regla) 
						{
						?>
							<div class="row-fluid regla">
								<div class="span1 center">
									<input type="hidden" name="pos_<?=$indice?>" value="<?=$indice?>">
									<input type="hidden" name="tipo_<?=$indice?>" value="<?=$regla['tipo']?>">
									<p class="indice"><?=$indice+1?></p>
								</div>
								<div class="span3">
									<input type="number" name="cantidad_<?=$indice?>" min="0" value="<?= $regla['cantidad']?>" style="width:100%;">
								</div>
								<div class="span3">
									<input type="number" step="0.001" name="descuento_<?=$indice?>" min="0" value="<?= $regla['descuento']?>" style="width:100%;">
								</div>
								<div class="span1">
									<a href="#" data-role="eliminar">
										<span class="ico-remove"></span>
									</a>
								</div>
							</div>
						<?php
						$indice ++;
						} 
					?>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<br>
						<a href="#" id="agregar">Agregar regla</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<br>
						<input type="hidden" name="reglas" value="<?= count($reglas) ?>">
						<input type="hidden" name="id" value="<?= $promocion->id ?>">
						<a href="<?= site_url('promociones/editar/'.$promocion->id)?>" class="btn btn-default">Cancelar</a>
						<input type="submit" id="promoreglas" class="btn btn-success" value="Guardar">						
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		var $reglas = $('input[name="reglas"]');
		
		$('#agregar').on('click', function(e)
		{
			var id_regla = ($reglas.val() * 1) + 1;
			var id_campo = id_regla - 1;
			$reglas.val(id_regla);

			var html = '<div class="row-fluid regla">'+
							'<div class="span1 center">'+
								'<input data-role="pos" type="hidden" name="pos_'+id_campo+'" value="'+id_campo+'">'+
								'<input data-role="tipo" type="hidden" name="tipo_'+id_campo+'" value="mayor_costo">'+
								'<p class="indice">'+id_regla+'</p>'+
							'</div>'+
							'<div class="span3">'+
								'<input data-role="cantidad" type="number" name="cantidad_'+id_campo+'" min="0" value="0" style="width:100%;">'+
							'</div>'+
							'<div class="span3">'+
								'<input data-role="descuento" type="number" step="0.001" name="descuento_'+id_campo+'" min="0" value="0" style="width:100%;">'+
							'</div>'+
							'<div class="span1">'+
								'<a href="#" data-role="eliminar">'+
									'<span class="ico-remove"></span>'+
								'</a>'+
							'</div>'+
						'</div>';

			$('#reglas').append(html);
			e.preventDefault();
		});

		$('#form_promociones_reglas').on('submit', function(e){			
			$("#promoreglas").prop('disabled',true);
			indexar();
		})

		var indexar = function()
		{
			var reglas = $('#reglas').find('.regla');

			if(reglas.length)
			{
				$.each(reglas, function(i, e){
					$(e).find('.indice').text(i+1);
					$(e).find('input[data-role="tipo"]').prop('name', 'tipo_'+i);
					$(e).find('input[data-role="pos"]').prop('name', 'pos_'+i);
					$(e).find('input[data-role="cantidad"]').prop('name', 'cantidad_'+i);
					$(e).find('input[data-role="descuento"]').prop('name', 'descuento_'+i);
				});

				$reglas.val(reglas.length);
			}
		}
	
		$('#reglas').delegate('a[data-role="eliminar"]', 'click', function(e){
			$(this).closest('.regla').remove();
			indexar();
		});
	});
</script>
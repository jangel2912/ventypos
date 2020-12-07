<div class="page-header">    
    <div class="icon">
        <img alt="Promociones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_promociones']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Promociones", "Promociones");?></h1>
</div>

<?php

$titulo = "Editar los Productos de la Promoción";

if($promocion->tipo == "descuentocombo")

{

    $titulo = ($accion == 1) ? "Editar los productos de compra en la promoción":"Editar los productos de descuento en la promoción";

}

?>

<input type="hidden" name="accion" value="<?php echo $accion ?>">

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_list_quotes', $titulo);?></h2> 

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

			<div class="text-center select_all_items hide">
				Ha seleccionado <?php echo count($productos); ?> productos.
			</div>

			<form id="form_productos" action="<?= site_url('promociones/sync_productos') ?>" method="post">

				<input type="hidden" name="accion" value="<?php echo $accion?>">

                                <div class="row-fluid">

					<div class="span12">

						<table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productos">
	                        <thead>
	                            <tr>
	                                <th width="5%"><?php echo custom_lang('sima_image', "Imagen");?></th>
	                                <th width="15%"><?php echo custom_lang('sima_name', "Nombre");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
	                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de Compra");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_price', "Precio de Venta");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
	                                <th class="center" align="center" width="5%"><input type="checkbox" name="select_all" value="1" id="todos"></th>
	                            </tr>
	                        </thead>
	                        <tbody>
								<?php foreach ($productos as $producto) {
										$imagen = base_url().'uploads/default.png';
										if($producto['imagen'] != ""){
											$imagen = base_url().'uploads/'.$this->session->userdata('base_dato').'/imagenes_productos/'.$producto['imagen'];
										}
									?>
									<tr data-id="<?= $producto['id'] ?>" class="<?= $producto['activo_promocion'] == 1 ? 'active' : '' ?>">
										<td>
											<img class='img-polaroid' height='30px' width='30px' src='<?= $imagen;  ?>'/>
										</td>
										<td>
											<?= $producto['nombre'] ?>
										</td>
										<td>
											<?= $producto['codigo'] ?>
										</td>
										<td>
											<?= number_format($producto['precio_compra']) ?>
										</td>
										<td>
											<?= number_format($producto['precio_venta']) ?>
										</td>
										<td>
											<?= $producto['nombre_impuesto'] ?>
										</td>
										<td class="center">
											<input type="checkbox" name="producto[]" value="<?= $producto['id'] ?>" <?= $producto['activo_promocion'] == 1 ? 'checked' : '' ?>>
										</td>
									</tr>
									<?php
								}?>
	                        </tbody>
	                        <tfoot>
	                            <tr>
	                                <th width="5%"><?php echo custom_lang('sima_image', "Imagen");?></th>
	                                <th width="15%"><?php echo custom_lang('sima_name', "Nombre");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
	                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de Compra");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_price', "Precio de Venta");?></th>
	                                <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
	                                <th class="center" align="center" width="5%"><input type="checkbox" name="select_all" value="1" id="todos"></th>
	                            </tr>
	                        </tfoot>
	                    </table>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<br>
						<input type="hidden" name="productos" value="">
						<input type="hidden" name="id" value="<?= $promocion->id ?>">
						<a href="<?= site_url('promociones/editar/'.$promocion->id)?>" class="btn btn-default">Cancelar</a>
						<input type="submit" id="promoproducto" class="btn btn-success" value="Guardar">	
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>

	$(function(){

		var oTable = $('#productos').DataTable({

			select: true,

			paging: true,

			dom: '<"controles_tabla"Bfr><"table"t>ip',

	       	columnDefs: [

	        	{

	        		orderable: true

	        	},

	        	{

	        		orderable: true

	        	},

	        	{

	        		orderable: true

	        	},

	       		{

	            	orderable: false,

	            	className: 'select-checkbox',

	            	style: 'multi',

	            	targets:   6

	        	},

	        ],

	        select: {

	            style:    'multi',

	            selector: 'td:last-child'

	        }

	   	});


	   	$('#productos #todos').on('click', function(e){
			
	   		var check = $(this).is(':checked');
			var rows = oTable.rows().every(function(rowIdx, tableLoop, rowLoop){
			var tr = $(this.node());
			var data = this.data();

			var checkbox = tr.find('input[type="checkbox"]');

			var check_span = tr.find('div.checker span');

			checkbox.prop('checked', check);

				if(check)
				{	
					$(".select_all_items").addClass("show");
					tr.addClass('active');
					check_span.addClass('checked');
				} else {
					
					$(".select_all_items").removeClass("show");
					tr.removeClass('active');
					check_span.removeClass('checked');

				}

			});

		});

		$('#form_productos').on('submit', function(e){
			$("#promoproducto").prop('disabled',true);
			var productos = '';
			var rows = oTable.rows().every(function(rowIdx, tableLoop, rowLoop){

				var tr = $(this.node());
				var data = this.data();
				var checkbox = tr.find('input[type="checkbox"]');

				if(checkbox.is(':checked'))
				{
					productos += checkbox.val()+',';
				}
			});
			$('input[name="productos"]').val(productos);

		});

	});

</script>
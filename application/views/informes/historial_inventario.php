<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('historial_de_inventario', "Historial de inventario");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
        	<?php
				$is_admin = $this->session->userdata('is_admin');
		 		$username = $this->session->userdata('username');	
	            $message = $this->session->flashdata('message');
	            if(!empty($message)):
	        ?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Historial de inventario");?></h2>
            </div>
            <div class="row">
            	<br>
            </div>
            <form action="<?php echo site_url("informes/historial_inventario_data");?>" method="POST">
                <div class="row-fluid">
                	<div class="span3">
                		<label>Fecha desde: </label>
                		<input type="text" name="fecha_desde" value="<?php echo $this->input->post('fecha_desde') ?  $this->input->post('fecha_desde') : date('Y-m-d') ;?>" class="datepicker"/>
                	</div>
					<div class="span3">
                		<label>Fecha hasta: </label>
                		<input type="text" name="fecha_hasta" value="<?php echo $this->input->post('fecha_hasta') ?  $this->input->post('fecha_hasta') : date('Y-m-d') ;?>" class="datepicker"/>
                	</div>
                	<div class="span3">
                		<label>Almacen: </label>
                		<?php 
							echo "<select  name='almacen' >";    
							echo "<option value=''>Todos los Almacenes</option>";    
						    foreach($data1['almacen'] as $f)
						    {
						        if($f->id == $this->input->post('almacen')){
						            $selected = " selected=selected ";
						        } else {
						            $selected = "";
						        }        
						        echo "<option $selected value=".$f->id.">" . $f->nombre . "</option>";
						    }    
						    echo "</select>";
						?>
                	</div>
                	<div class="span3">
                		<br>
                		<input type="submit" value="Consultar" class="btn btn-success"/>
                		<a id="excel" data-href="<?php echo site_url("informes/historial_inventario_excel")?>" href="#" class="btn btn-success" ><small class="ico-circle-arrow-down icon-white"></small> Excel</a>
                	</div>
                </div>
        	</form>
        	<?php if($data): ?>
        		<div class="row-fluid">
        			<div class="span12">
        				<br>
        					Historial del dia: <strong><?= $this->input->post('fecha_desde') ?></strong>
        				<br>	
        			</div>
        		</div>
	        	<div class="row-fluid">
	        		<div class="span12 table-responsive">
		        		<table class="table table-striped" width="100%">
		        			<thead>
		        				<th>Almacen</th>
								<th>Fecha</th>
		        				<th>Categoría</th>
		        				<th>Código</th>
		        				<th>Producto</th>
		        				<th style="text-align:right;">Cantidad</th>
								<th style="text-align:right;">Precio compra</th>
		        				<th style="text-align:right;">Precio</th>
		        			</thead>
		        			<tbody>
		        				<?php foreach ($data as &$registro) { ?>
			        				<tr>
			        					<td><?= $registro->Almacen ?></td>
										<td><?= $registro->Fecha ?></td>
			        					<td><?= $registro->Categoria ?></td>
			        					<td><?= $registro->Codigo ?></td>
			        					<td><?= $registro->Nombre ?></td>
			        					<td style="text-align:right;"><?= $registro->Unidades ?></td>
										<td style="text-align:right;">$<?= number_format($registro->Precio_compra, 2) ?></td>
			        					<td style="text-align:right;">$<?= number_format($registro->Precio, 2) ?></td>			        				
									</tr>
		        				<?php } ?>
		        			</tbody>
		        		</table>
	        		</div>
	        	</div>
        	<?php endif; ?>
        </div>
    </div>
</div>
<script>
	$(function(e){

		var sethrefexcel = function()
		{
			var url = $('#excel').data('href');
			var query = url+'/'+$('input[name="fecha_desde"]').val()+'/'+$('input[name="fecha_hasta"]').val()+'/'+$('select[name="almacen"]').val();
			$('#excel').prop('href', query);
		}

		$('input[name="fecha"], select[name="almacen"]').on('change', function(e)
		{
			sethrefexcel();
		});

		sethrefexcel();
	});
</script>
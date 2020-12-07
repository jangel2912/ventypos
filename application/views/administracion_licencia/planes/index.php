<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Planes", "Planes");?></h1>

</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):
                    $message_type = $this->session->flashdata('message_type');
            ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <a href="<?php echo site_url("administracion_vendty/planes/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('', "Nuevo Plan");?></a>
            			
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todos los Planes");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="planes">
							<thead>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "Id");?></th>
									<th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Dias Vigencia");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Valor del plan");?></th>
									<th width="5%"><?php echo custom_lang('sima_image', "Iva Plan");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Valor Final");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Promocion");?></th>					
									<th width="10%"><?php echo custom_lang('sima_codigo', "Tipo Plan");?></th>					
									<th width="10%"><?php echo custom_lang('sima_codigo', "Valor en $");?></th>					
									<th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>
								
								<?php 
									foreach($data['planes'] as $key =>$value){
										$tipo=$value->tipo_plan;
										if($value->tipo_plan==1){
											$tipo='Básico';
										}
										else{
											if($value->tipo_plan==2){
												$tipo='Pyme';
											}else{
												if($value->tipo_plan==3){
													$tipo='Empresarial';
												}
											}
										}
										
										echo'<tr>';
											echo'<td>'.$value->id.'</td>';
											echo'<td>'.$value->nombre_plan.'</td>';
											echo'<td>'.$value->dias_vigencia.'</td>';
											echo'<td>'.$value->valor_plan.'</td>';
											echo'<td>'.$value->iva_plan.'</td>';
											echo'<td>'.$value->valor_final.'</td>';
											echo'<td>'.$value->promocion.'</td>';
											echo'<td>'.$tipo.'</td>';
											echo'<td>'.$value->valor_plan_dolares.'</td>';
											echo'<td>';
								?>
									<a href="<?php echo site_url('administracion_vendty/planes/editar/'.$value->id);?>" class="button default"><div class="icon"><span class="ico-pencil"></span></div></a>			
									
								<?php	
											echo'</td>';										
										echo'</tr>';
									}
								?>
								
							</tbody>
							<tfoot>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "Id");?></th>
									<th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Dias Vigencia");?></th>
									<th width="10%"><?php echo custom_lang('sima_image', "Valor del plan");?></th>
									<th width="5%"><?php echo custom_lang('sima_image', "Iva Plan");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Valor Final");?></th>
									<th width="10%"><?php echo custom_lang('sima_codigo', "Promocion");?></th>					
									<th width="10%"><?php echo custom_lang('sima_codigo', "Tipo Plan");?></th>					
									<th width="10%"><?php echo custom_lang('sima_codigo', "Valor en $");?></th>					
									<th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</tfoot>
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	$(document).ready(function(){
		$('#planes').DataTable();	
	});	
	
</script>


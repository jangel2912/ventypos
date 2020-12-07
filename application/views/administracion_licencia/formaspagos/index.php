<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("", "Formas de Pago");?></h1>

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
            <a href="<?php echo site_url("administracion_vendty/formas_pago/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('', "Nueva Forma de pago");?></a>
            			
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Todas las formas de pagos");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table" cellpadding="0" cellspacing="0" width="100%" id="pagos">
							<thead>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "id");?></th>
									<th width="25%"><?php echo custom_lang('sima_image', "Nombre");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "descripcion");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Numero de Cuenta");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Cuenta");?></th>
									<th width="5%"><?php echo custom_lang('price_active', "Activo");?></th>					
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</thead>
							<tbody>
								
								<?php 
									foreach($data['formaspago'] as $key =>$value){
										echo'<tr>';
											echo'<td>'.$value->idformas_pago.'</td>';
											echo'<td>'.$value->nombre_forma.'</td>';
											echo'<td>'.$value->descripcion.'</td>';
											echo'<td>'.$value->numero_cuenta.'</td>';
											echo'<td>'.$value->nombre_cuenta.'</td>';
											echo'<td>'.$value->activo_forma.'</td>';
											echo'<td>';
								?>
									<a href="<?php echo site_url('administracion_vendty/formas_pagos/editar/'.$value->idformas_pago);?>" class="button default"><div class="icon"><span class="ico-pencil"></span></div></a>			
									<a href="<?php echo site_url('administracion_vendty/formas_pagos/eliminar/'.$value->idformas_pago);?>" onclick="if(confirm(('Esta seguro que desea eliminar el registro?);')){return true;}else{return false;}" class="button red"><div class="icon"><span class="icon ico-remove"></span></div></a>
									
								<?php	
											echo'</td>';										
										echo'</tr>';
									}
								?>
								
							</tbody>
							<tfoot>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "id");?></th>
									<th width="25%"><?php echo custom_lang('sima_image', "Nombre");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Descripcion");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Numero de Cuenta");?></th>
									<th width="15%"><?php echo custom_lang('sima_image', "Nombre Cuenta");?></th>
									<th width="5%"><?php echo custom_lang('price_active', "Activo");?></th>					
									<th class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
								</tr>
							</tfoot>
						</table>
						</div>
				</div>			
			</div>			
		</div>
			
<script type="text/javascript">
	$(document).ready(function(){
		$('#pagos').DataTable();	
	});	
	
</script>


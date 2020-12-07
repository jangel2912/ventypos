<div class="page-header">    
    <div class="icon">
        <img alt="info_fiscal" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("info_fiscal", "Información Fiscal");?></h1>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">              
            <div class="col-md-2">              
                <a href="<?php echo site_url("administracion_vendty/facturas_licencia/")?>" data-tooltip="Volver atrás">                       
                    <img alt="Volver atrás" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                </a>            
            </div>
        </div>
        <div class="col-md-6 btnizquierda"> 
            <div class="col-md-2 col-md-offset-10">
                <a href="<?php echo site_url("administracion_vendty/empresas/export_info_fiscal_excel/")?>" data-tooltip="Exportar a Excel">                       
                    <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                     
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="table-responsive">
  	<table class="table" id="empresas_info" width=100%>			
		<thead>
			<tr>
				<th width="5%"><?php echo custom_lang('sima_image', "id_bd");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_empresa");?></th>
				<th width="10%"><?php echo custom_lang('sima_codigo', "Nombre Empresa");?></th>
				<th width="10%"><?php echo custom_lang('price_active', "Negocio");?></th>
				<th width="10%"><?php echo custom_lang('price_active', "Identificacion Factura");?></th>															
				<th width="10%"><?php echo custom_lang('price_active', "Nombre Plan");?></th>	
				<th width="10%"><?php echo custom_lang('sima_action', "Tipo Plan");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Valor Final");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Estado Licencia");?></th>																	
				<th width="10%"><?php echo custom_lang('sima_action', "Pais Factura");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Ciudad Factura");?></th>
			</tr>
		</thead>
		<tbody>
			
			<?php 
				foreach ($data['datos_empresas_info_fiscal'] as $value) {
					echo'<tr>';
						echo'<td>'.$value->id_db.'</td>';											
						echo'<td>'.$value->id_empresa.'</td>';											
						echo'<td>'.$value->nombre_empresa_config.'</td>';											
						echo'<td>'.$value->tipo_negocio.'</td>';											
						echo'<td>'.$value->tipo_documento_config.' '.$value->numero_documento_config.'</td>';
						echo'<td>'.$value->nombre_plan.'</td>';
						echo'<td>'.$value->des_tipo_plan_S.'</td>';
						echo'<td>'.$value->valor_final.'</td>';
						echo'<td>';
							$estado=($value->estado_licencia==1)? 'Activa' : 'Desactivada';
							echo$estado;
						echo'</td>';											
						echo'<td>'.$value->nombre_pais_config.'</td>';
						echo'<td>'.$value->ciudad_factura.'</td>';																				
					echo'</tr>';
				}
			?>
			
		</tbody>
		<tfoot>
			<tr>
				<th width="5%"><?php echo custom_lang('sima_image', "id_bd");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_empresa");?></th>
				<th width="10%"><?php echo custom_lang('sima_codigo', "Nombre Empresa");?></th>
				<th width="10%"><?php echo custom_lang('price_active', "Negocio");?></th>
				<th width="10%"><?php echo custom_lang('price_active', "Identificacion Factura");?></th>															
				<th width="10%"><?php echo custom_lang('price_active', "Nombre Plan");?></th>	
				<th width="10%"><?php echo custom_lang('sima_action', "Tipo Plan");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Valor Final");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Estado Licencia");?></th>																	
				<th width="10%"><?php echo custom_lang('sima_action', "Pais Factura");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Ciudad Factura");?></th>
			</tr>
		</tfoot>
	</table>
</div>
					
		
<script type="text/javascript">
	$(document).ready(function(){
		$('#empresas_info').DataTable();	
	});

</script>

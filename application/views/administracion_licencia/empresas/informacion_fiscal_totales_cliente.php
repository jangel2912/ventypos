<style>
    #ui-datepicker-div{
        background-color: #fff;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="info_fiscal" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("info_fiscal", "Información Fiscal Totales Cliente");?></h1>
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
                <a href="<?php echo site_url("administracion_vendty/empresas/export_info_fiscal_excel/")."/".$data['fecha_inicio']."/".$data['fecha_fin'] ?>" data-tooltip="Exportar a Excel">                       
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
				<th width="5%"><?php echo custom_lang('sima_image', "Fecha");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_bd");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_empresa");?></th>
				<th width="10%"><?php echo custom_lang('sima_codigo', "Nombre Empresa");?></th>
				<th width="5%"><?php echo custom_lang('price_active', "Negocio");?></th>
				<th width="5%"><?php echo custom_lang('price_active', "Negocio Especializado");?></th>														
				<th width="10%"><?php echo custom_lang('price_active', "Identificación Tributaria");?></th>															
				<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>	
				<th width="5%"><?php echo custom_lang('sima_action', "Cantidad Facturas");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Pagos Facturas");?></th>
				<th width="5%"><?php echo custom_lang('sima_action', "Tipo Moneda");?></th>																	
				<th width="5%"><?php echo custom_lang('sima_action', "Simbolo Moneda");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Pais Negocio");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Ciudad Negocio");?></th>
			</tr>
		</thead>
		<tbody>
			
			<?php 
				
				foreach ($data['datos_empresas_info_fiscal'] as $value) {
                    
                    $mes=explode("-",$value->fecha);

						switch($mes[1]){
                            case '01':
                                $fecha = 'Enero-'.$mes[0];                                                              
                            break;
							case '02':
								$fecha = 'Febrero-'.$mes[0];                                
                            break;
							case '03':
								$fecha = 'Marzo-'.$mes[0];                                 
                            break;
                            case '04':
                                $fecha = 'Abril-'.$mes[0]; 
                            break;
							case '05':
							 	$fecha = 'Mayo-'.$mes[0]; 
                            break;
                            case '06':
                                $fecha = 'Junio-'.$mes[0]; 
                            break;
                            case '07':
                                $fecha = 'Julio-'.$mes[0]; 
                            break;
                            case '08':
                                $fecha = 'Agosto-'.$mes[0];
                            break;
                            case '09':
                                $fecha = 'Septiembre-'.$mes[0];
                            break;
                            case '10':
                                 $fecha = 'Octubre-'.$mes[0];
                            break;
                            case '11':
                                 $fecha = 'Noviembre-'.$mes[0];
                            break;
                            case '12':
                                $fecha = 'Diciembre-'.$mes[0];
                            break;
						}
						
					echo'<tr>';
						echo'<td>'.$fecha.'</td>';											
						echo'<td>'.$value->id_db.'</td>';											
						echo'<td>'.$value->id_empresa.'</td>';											
						echo'<td>'.$value->nombre_empresa_config.'</td>';											
						echo'<td>'.$value->tipo_negocio.'</td>';											
						echo'<td>'.$value->tipo_negocio_especializado.'</td>';											
						echo'<td>'.$value->tipo_documento_config.' '.$value->numero_documento_config.'</td>';
						echo'<td>'.$value->email_factura.'</td>';
						echo'<td>'.$value->cantidad_facturas.'</td>';
						echo'<td>'.$value->cantidad_pagos_facturas.'</td>';
						echo'<td>'.$value->tipo_moneda.'</td>';
						echo'<td>'.$value->simbolo_moneda.'</td>';																
						echo'<td>'.$value->pais_negocio.'</td>';
						echo'<td>'.$value->ciudad_negocio.'</td>';																				
					echo'</tr>';
				}
			?>
			
		</tbody>
		<tfoot>
			<tr>
				<th width="5%"><?php echo custom_lang('sima_image', "Fecha");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_bd");?></th>									
				<th width="5%"><?php echo custom_lang('sima_image', "id_empresa");?></th>
				<th width="10%"><?php echo custom_lang('sima_codigo', "Nombre Empresa");?></th>
				<th width="5%"><?php echo custom_lang('price_active', "Negocio");?></th>
				<th width="5%"><?php echo custom_lang('price_active', "Negocio Especializado");?></th>														
				<th width="10%"><?php echo custom_lang('price_active', "Identificación Tributaria");?></th>															
				<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>	
				<th width="5%"><?php echo custom_lang('sima_action', "Cantidad Facturas");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Pagos Facturas");?></th>
				<th width="5%"><?php echo custom_lang('sima_action', "Tipo Moneda");?></th>																	
				<th width="5%"><?php echo custom_lang('sima_action', "Simbolo Moneda");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Pais Negocio");?></th>
				<th width="10%"><?php echo custom_lang('sima_action', "Ciudad Negocio");?></th>
			</tr>
		</tfoot>
	</table>
</div>
					
		
<script type="text/javascript">
	$(document).ready(function(){

		$('#empresas_info').DataTable();	
        $("<label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label>").insertAfter('#empresas_info_length');
        $("<label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label>").insertAfter('#empresas_info_length');
        $("<label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label>").insertAfter('#empresas_info_length');

        $( "#fecha_inicio" ).datepicker({

                defaultDate: "+0w",
                changeMonth: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                onClose: function( selectedDate ) {
					$( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
                }
        });

        $( "#fecha_fin" ).datepicker({
                defaultDate: "+0w",
                changeMonth: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                onClose: function( selectedDate ) {
                        $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
                }
        });

        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            
            if((fecha_inicio !="")&&(fecha_fin !="")){                
                url="<?php echo site_url("administracion_vendty/empresas/info_fiscal_cliente");?>/"+ fecha_inicio+"/"+fecha_fin;
                location.href =url;              
            }
            else{
                swal({
                    position: 'top-center',
                    type: 'error',
                    title: 'Seleccione un rango de fechas a consultar',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        });
        
        fechai='<?php echo $data["fecha_inicio"] ?>';
        fechaf='<?php echo $data["fecha_fin"] ?>';
        $( "#fecha_inicio" ).val(fechai);
        $( "#fecha_fin" ).val(fechaf);

	});

</script>


<style>
	#ui-datepicker-div,.ui-datepicker{
		background-color: #e9e9e9;
	}
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="info_pruebas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("info_prueba", "Informe Cuentas Pruebas");?></h1>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">              
            <div class="col-md-2">              
                <a href="<?php echo site_url("administracion_vendty/empresas/informe_prueba/")?>" data-tooltip="Volver atrás">                       
                    <img alt="Volver atrás" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                </a>            
            </div>
        </div>
        <div class="col-md-6 btnizquierda"> 
            <div class="col-md-2 col-md-offset-10">
					<a data-tooltip="Descargar Excel" id="ex">                        
                        <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a> 
            </div>
        </div>
        <hr>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">            
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('', "Informe Cuentas en Prueba");?></h2>
            </div>
                <div class="data-fluid">					
						<table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="cuentaspruebas">
							<thead>
								<tr>
								<th width="5%"><?php echo custom_lang('sima_image', "Id_licencia");?></th>	
									<th width="5%"><?php echo custom_lang('sima_image', "Empresa");?></th>									
									<th width="5%"><?php echo custom_lang('sima_image', "Teléfono");?></th>
									<th width="5%"><?php echo custom_lang('sima_codigo', "Username");?></th>
									<th width="5%"><?php echo custom_lang('price_active', "Nombre");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>	
									<th width="5%"><?php echo custom_lang('price_active', "Wizard");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "# Productos");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "# Facturas");?></th>	
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Factura");?></th>	
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Creación");?></th>					
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Inicio L");?></th>						
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Fin L");?></th>						
									<th width="10%"><?php echo custom_lang('price_active', "Último Login");?></th>						
									<!--<th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>-->
								</tr>
							</thead>
							<tbody>	</tbody>
							<tfoot>
								<tr>
									<th width="5%"><?php echo custom_lang('sima_image', "Id_licencia");?></th>	
									<th width="5%"><?php echo custom_lang('sima_image', "Empresa");?></th>									
									<th width="5%"><?php echo custom_lang('sima_image', "Teléfono");?></th>
									<th width="5%"><?php echo custom_lang('sima_codigo', "Username");?></th>
									<th width="5%"><?php echo custom_lang('price_active', "Nombre");?></th>
									<th width="10%"><?php echo custom_lang('price_active', "Email");?></th>	
									<th width="5%"><?php echo custom_lang('price_active', "Wizard");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "# Productos");?></th>															
									<th width="5%"><?php echo custom_lang('price_active', "# Facturas");?></th>	
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Factura");?></th>	
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Creación");?></th>					
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Inicio L");?></th>						
									<th width="10%"><?php echo custom_lang('price_active', "Fecha Fin L");?></th>						
									<th width="10%"><?php echo custom_lang('price_active', "Último Login");?></th>						
									<!--<th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>-->
								</tr>
							</tfoot>
						</table>
					
				</div>			
		</div>			
	</div>
</div>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>	
<script type="text/javascript">
	$(document).ready(function(){
		//$('#empresas').DataTable();	
	});

    $(document).ready(function(){

		oTable=$('#cuentaspruebas').dataTable( {

			"aaSorting": [[ 0, "desc" ]],

			"bProcessing": true,

			"bServerSide": true,

			"sAjaxSource": "<?php echo site_url("administracion_vendty/empresas/get_ajax_informe_prueba");?>",

			"sPaginationType": "full_numbers",

			"iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

			"aoColumnDefs" : [

				{ "bSortable": false, "aTargets": [ 6 ] },
				{ "bSortable": false, "aTargets": [ 7 ] },
				{ "bSortable": false, "aTargets": [ 8 ] },
				{ "bSortable": false, "aTargets": [ 9 ] }
			]

		});		

		$("<div id='cuentaspruebas_length1' data-tooltip='Periódo máximo a consultar 3 meses' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#cuentaspruebas_length');
        $("<div id='cuentaspruebas_length2' data-tooltip='Periódo máximo a consultar 3 meses' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#cuentaspruebas_length1');

		if ( $("#cuentaspruebas_length3").length > 0 ) {
            $("<div id='cuentaspruebas_length4' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#cuentaspruebas_length3');
        }else{
            $("<div id='cuentaspruebas_length3' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#cuentaspruebas_length2');
        }

		$("#filtrar").click(function(){
			alert("filtro");
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();

           	var dias_diferencia = moment(fecha_fin).diff(moment(fecha_inicio), 'days');
			if(dias_diferencia > 90){
				//alert("No es posible consultar los movimientos en un rango mayor a 3 meses");
					$("#mensaje").html("No es posible consultar los movimientos en un rango mayor a 3 meses");
					$("#mensaje").removeClass('hidden');
			}else{
					$("#mensaje").html("");
					$("#mensaje").addClass('hidden');
					oTable.fnReloadAjax( "<?php echo site_url("administracion_vendty/empresas/get_ajax_informe_prueba");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin);
			}

      	});
        
        $( "#fecha_inicio" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,           
            currentText: "Today:",
            maxDate: "0",           
            yearRange: "-2:+0",
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
               
        });
       

        $( "#fecha_fin" ).datepicker({            
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,           
            currentText: "Today:",
            maxDate: "0",           
            yearRange: "-2:+0",
            onClose: function( selectedDate ) {
                $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        
        $("#ex").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();          
            var dir = "<?php echo site_url("administracion_vendty/empresas/get_ajax_ex_informe_prueba");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin;
            $(this).attr('href', dir);    
            
        });
    });

</script>
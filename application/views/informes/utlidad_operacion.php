<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");

?>
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">            
                <?php
                $is_admin = $this->session->userdata('is_admin');
		        $username = $this->session->userdata('username');				
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            
            <!--<a id="ex" href="<?php echo site_url("informes/detalle_utilidad");?>" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_detalle', "Detalle de informe");?></a>
            <span> (Tenga en cuenta que el total de las ventas consultadas en este informe no discrimina los descuentos) </span> -->
            <div class="col-md-6">   
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a data-tooltip="Descargar Excel" id="ex">                        
                        <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a> 
                </div>
            </div>            
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
                <div id="mensaje" class="alert alert-error hidden"></div>  
                <div class="head blue">
                    <h2><?php echo custom_lang('ventasxclientes', "Utilidad de Operaci&oacute;n");?></h2>
                </div>
                <form action="<?php echo site_url("informes/utilidad_periodo_data");?>" method="POST" id="validate">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial <input id="fecha_inicio" type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker" readonly required/>  </td>
                        <td width="15%">Fecha Final <input id="fecha_fin" type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker" readonly required/> </td>
						
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacén   
                            <?php 
                            echo "<select  name='almacen' id='almacen'>";    
                            echo "<option value='0'>Todos los Almacenes</option>";    
                            foreach($data1['almacen'] as $f){
                                if($f->id == $this->input->post('almacen')){
                                    $selected = " selected=selected ";
                                } else {
                                    $selected = "";
                                }        
                                echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                            }    
                            echo "</select>";
                            ?> 
	                    </td>						
				     <?php /* if( $filtro_ciudad == 'si'){ //administrador ?>
						<td width="30%">Ciudad : <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?></td>
						<?php } */ ?>
					<?php } ?>							
                        <!--<td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>-->
                        <td width="30%">
                            <a data-tooltip="Consultar" onclick="verificar()">                        
                                <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                            </a> 
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>
 
                    <div class="head blue">
                        <h2>Estados de Resultados</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td></td>
                            <td><b>Total de Ventas sin impuestos</b></td>
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_venta']); ?></b></p></td>
							<!--<td></td>-->
                        </tr>
                        <tr>
                            <td>Menos: </td>
                            <td>Costo de Ventas</td>
                            <td><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_costos']); ?></td>
							<!--<td></td>-->
                        </tr>
                        <tr>
                            <td>Menos: </td>
                            <td>Total Descuentos</td>
                            <td><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_descuento']); ?></td>
							<!--<td></td>-->
                        </tr>						
	                    <tr>
                            <th><b>Igual: </b></th>
                            <th><b>Utilidad Bruta</b></th>
                            <th><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_venta'] - $data['total_costos'] - $data['total_descuento']); ?></b></th>
							<!--<td></td>-->
                        </tr>
	                    <tr>
                            <td>Menos: </td>
                            <td>Gastos de Operaci&oacute;n</b></td>
                            <td><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_gastos']); ?></td>
							<!--<td></td>-->
                        </tr>
		                    <tr>
                            <th>Igual: </th>
                            <th><b>Utilidad de Operaci&oacute;n</b></th>
                            <th><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['total_venta'] - $data['total_costos'] - $data['total_gastos'] - $data['total_descuento']); ?></th>
							<!--<td></td>-->
                        </tr>																							
                   </table>
		 <?php } ?>			
        </div>
    </div>
    <small>* Utilidad =  Total de venta sin impuestos - Costo de Ventas - Descuentos</small>
</div>
<script type="text/javascript">

    function verificar(){
        var fechainicial = $("#fecha_inicio").val();
        var fechafinal = $("#fecha_fin").val();
        
        if((fechainicial != "") &&(fechafinal != "")){
            
            if((fechainicial)<=(fechafinal)) {                    
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#validate').submit();
            }else{                    
                $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                $("#mensaje").removeClass('hidden');
            }         
            
        }else{                
            $("#mensaje").html("Debe seleccionar los filtros a consultar");
            $("#mensaje").removeClass('hidden');
        }
    }

    $(document).ready(function(){

       $("#pais").change(function(){

           load_provincias_from_pais($(this).val());

       }); 

       

       var pais = $("#pais").val();

       if(pais != ""){

           load_provincias_from_pais(pais);

       }

      

    });

    function load_provincias_from_pais(pais){

        $.ajax({

            url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",

            data: {"pais" : 'Colombia'},

            dataType: "json",

            success: function(data) {

                $("#provincia").html('');
				
				  $("#provincia").append("<option value=''>Todas las ciudades</option>"); 

                $.each(data, function(index, element){

                    provincia = "<?php echo $this->input->post('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }
    
    $("#ex").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
                fecha_fin = $("#fecha_fin").val();
                almacen = $("#almacen").val();
                if((fecha_inicio != "") &&(fecha_fin != "")){
                    if((fecha_inicio)<=(fecha_fin)) {                    
                        $("#mensaje").html("");
                        $("#mensaje").addClass('hidden');
                        dir = "<?php echo site_url("informes/detalle_utilidad");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen;
                        $(this).attr('href', dir);
                    }else{                    
                        $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                        $("#mensaje").removeClass('hidden');
                    }                      
                }
                else{
                    $("#mensaje").html("Debe seleccionar los filtros a consultar");
                    $("#mensaje").removeClass('hidden');
                }            
        });
       
        //mixpanel.track("Informe_Utilidad_Operacion_Periodo");  
</script> 
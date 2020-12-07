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

<!--<a href="#" id="btn_excel" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
<a data-tooltip="Exportar Excel" id="btn_excel">                        
    <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
</a> 
<div class="row-fluid">
    <div class="span12">
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
            <div id="mensaje" class="alert alert-error hidden"></div>    
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "H&aacute;bitos de Consumo por Hora");?></h2>
            </div>

             <form class="form-inline" action="<?php echo site_url("informes/habitos_consumo_hora_data");?>" method="POST"  id="f_consumo_hora" >
                <div class="form-group">
                    <label for="exampleInputName2">Fecha Inicial</label>
                    <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker form-control" readonly required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Fecha Final</label>
                    <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="form-control datepicker" readonly required/>
                </div>
                <?php  if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>
                <div class="form-group">
                    <label for="exampleInputEmail3">Almacén</label>
                    <?php 
                        echo "<select id='almacen' name='almacen' class='form-control' >";    
                        echo "<option value='0'>Todos los Almacenes</option>";    
                        foreach($data1['almacen'] as $f){
                            if($f->id == $this->input->post('almacen')){
                                $selected = " selected=selected ";
                            } else {
                                $selected = "";
                            }        
                            echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                        }    
                        echo "</select>";?>
                </div>
                <?php }	 ?>  
                                
                <a data-tooltip="Consultar" onclick="verificar()">                        
                    <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                </a> 
            </form>

<!--
            <form action="<?php echo site_url("informes/habitos_consumo_hora_data");?>" method="POST" id="f_consumo_hora">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
						
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacen :   
                        <?php 
                        echo "<select id='almacen' name='almacen' >";    
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
					<?php } ?>							
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>
            -->
            </div>
        </div>
											
    <div>
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table text-justify" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="35%"><b>Fecha y Hora</b></td>
                            <td width="25%"></td>
                            <td width="20%"></td>
                            <td width="20%"><b>Total de ventas</b></td>                          
                        </tr>			
				<?php 
function fechaespanol($fecha){ //yyyy-mm-dd
$diafecespanol=date("d", strtotime($fecha));
$diaespanol=date("N", strtotime($fecha));
$mesespanol=date("m", strtotime($fecha));
$anoespanol=date("Y", strtotime($fecha));
//Asignamos el nombre en espa�ol

// dia
    if($diaespanol == "1"){ $diaespan="Lunes"; }
    if($diaespanol == "2"){ $diaespan="Martes"; }
    if($diaespanol == "3"){ $diaespan="Miercoles"; }
    if($diaespanol == "4"){ $diaespan="Jueves"; }
    if($diaespanol == "5"){ $diaespan="Viernes"; }
    if($diaespanol == "6"){ $diaespan="Sabado"; }
    if($diaespanol == "7"){ $diaespan="Domingo"; }
        
//mes
    if($mesespanol == "1"){ $mesespan="Enero"; }
    if($mesespanol == "2"){ $mesespan="Febrero"; }
    if($mesespanol == "3"){ $mesespan="Marzo"; }
    if($mesespanol == "4"){ $mesespan="Abril"; }
    if($mesespanol == "5"){ $mesespan="Mayo"; }
    if($mesespanol == "6"){ $mesespan="Junio"; }
    if($mesespanol == "7"){ $mesespan="Julio"; }
    if($mesespanol == "8"){ $mesespan="Agosto"; }
    if($mesespanol == "9"){ $mesespan="Septiembre"; }
    if($mesespanol == "10"){ $mesespan="Octubre"; }
    if($mesespanol == "11"){ $mesespan="Noviembre"; }
    if($mesespanol == "12"){ $mesespan="Diciembre"; } 

//ano
    $anoespanol=$anoespanol;
    
//Fecha
$fecha=$diaespan." ".$diafecespanol." de ".$mesespan." del ".$anoespanol;

return $fecha;
}              
                $total=0;
				foreach($data['total_ventas_1'] as $value){ ?>	
                        <tr>
                            <td width="35%"><b><?php echo fechaespanol($value->fecha_dia);?> - <?php echo $value->hora;?></b></td>
                            <td width="25%"><b>Producto</b></td>
                            <td width="20%"><b>Cantidad Vendida</b></td>
                            <td width="20%"><b><?php echo $ci->opciones_model->formatoMonedaMostrar($value->total_venta);?></b></td>
							<!--<td><b>&nbsp; </td>-->
                        </tr>	
						
                           <?php  
						     

			 	 // foreach($data['total_ventas_2'] as $det){
				 
						  foreach($data['total_ventas_3'] as $prod){
			                
						  	   if($prod['hora']==$value->hora && $prod['fecha_dia']==$value->fecha_dia ){
							      $total += $prod['total_detalleventa'];
						                ?>  
                                        <tr>
                                            <td  width="35%"><b><?php echo $prod['codigo_producto'] ?></b> </td>           
                                            <td  width="25%"><?php  echo $prod['nombre'];?></td>
											<td  width="20%"><?php  echo $prod['unidades'];?></td> 
                                            <td  width="20%"><p><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($prod['total_detalleventa']);?></p></td>							               	
                                        </tr>                                                                                                                                                                                                                             <?php  }  ?> 
						  
						   
				                       	<?php 	} ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php  // }  ?>                                                                                                                                                                                   
				   <?php } ?>	
				 <?php   foreach($data['total_ventas_4'] as $value){ ?>	
                        <tr>
                            <td width="35%"><b>Total </b></td>
							<td width="25%"></td>
                            <td width="20%"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value->total_ventas);?></b></td>
							<td width="20%"><b>&nbsp; </td>
                        </tr>	
						<?php } ?>				   	 
				  </table> 
			
         <?php } ?>     
    </div>
    </div>
    <script type="text/javascript">

     function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#datefinal").val();
        var almacen = $("#almacen").val();
        
        if((fechainicial != "") &&(fechafinal != "") && (almacen!="")){
            
            if((fechainicial)<=(fechafinal)) {                    
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#f_consumo_hora').submit();
            }else{                    
                $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                $("#mensaje").removeClass('hidden');
            }         
            
        }else{                
            $("#mensaje").html("Debe seleccionar los filtros a consultar");
            $("#mensaje").removeClass('hidden');
        }
    }

    
    var url_excel ='<?php echo site_url("informes/habitos_consumo_hora_excel") ?>';
        $(document).ready(function(){
            $("#btn_excel").on('click',function(){
                var valido = true;    
                $.each($("#f_consumo_hora input"),function(key,value){
                    if($(this).val() == '' ){
                        valido = false;
                    }
                });
                if(valido){
                     $("#mensaje").html("");
                    $("#mensaje").addClass('hidden');
                    $("#f_consumo_hora").attr('action',url_excel);
                    $("#f_consumo_hora").submit();
                }else{
                    //alert('debe seleccionar un rango de fechas, antes de generar el excel');
                     $("#mensaje").html("Debe seleccionar el rango de fechas, antes de generar el Excel");
                     $("#mensaje").removeClass('hidden');
                }
            });
        });

        mixpanel.track("Informe_habitos_consumo_horas");  
    </script>
<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");
?>
<style>
    .ui-datepicker{
        background-color: white;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>

<!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
<!--<a data-tooltip="Exportar Excel" id="ex">                        
    <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
</a> -->
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
                <h2><?php echo custom_lang('ventasxclientes', "H&aacute;bitos de Consumo por Mes2");?></h2>
            </div>
            <br>
            <form class="form-inline" action="<?php echo site_url("informes/habitos_consumo_mes_data2");?>" method="POST"  id="f_consumo_dia" >
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
                <form id="f_consumo_dia" action="<?php echo site_url("informes/habitos_consumo_dia_data");?>" method="POST">
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
                </form>-->
        </div>
    </div>
</div>
<?php if(isset($fechafinal) && !empty($fechainicial)){?>
<div class="col-md-12">
    <div class="col-md-6">
        <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->    
    </div>
    <div class="col-md-6 btnizquierda">
        <div class="col-md-2 col-md-offset-10">
            <a data-tooltip="Exportar Excel" id="ex">                        
                <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a> 
        </div>
    </div>
</div>
<div class="row-fluid">                             
    <div class="span12">
        <div class="block">        
	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">                     
                        <tr>
                            <td colspan="2"><b>Fecha </b></td>
                            <td colspan="2" align="right"><b>Total de ventas</td>		
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
                            <th><b><?php echo fechaespanol($value->fecha_dia);?> - <?php echo $value->fecha;?></b></th>
							<th><b>Cantidad Vendida</b></th>
                            <th>&nbsp;</th>
                            <th>Devoluciones</th>
                            <th><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value->total_venta - $data["devoluciones"]);?></b></p></th>
							<!--<th><b>&nbsp; </th>-->
                        </tr>	
						
                           <?php  
						     

			 	 // foreach($data['total_ventas_2'] as $det){
				 
						  foreach($data['total_ventas_3'] as $prod){
			                
						  	   if($prod['fecha']==$value->fecha){
							  $total +=  $prod['total_detalleventa'];
						                ?>  
						 	                <tr>
                                            <td><?php echo $prod['codigo_producto'] ?></td>                         
                                            <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php  echo $prod['nombre'];?></td>
											<td><?php  echo $prod['unidades'];   ?> </td> 
											<td><?php  echo $prod['total_devoluciones'];   ?> </td> 
                                            <td><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($prod['total_detalleventa']);?></p></td>
							               	<!--<td><b>&nbsp; </td>-->
                        	               </tr>                                                                                                                                                                                                                             <?php  }  ?> 
						  
						   
				                       	<?php 	} ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php  // }  ?>                                                                                                                                                                                   
				   <?php } ?>
                        <tr>
                            <td><b>Total </b></td>
							<td></td>
                            <td colspan="2"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total);?></b></p></td>
							<!--<td><b>&nbsp; </td>-->
                        </tr>					   	 
				  </table> 
		 		
        </div>
    </div>
</div>
<?php } ?>	
    <script type="text/javascript">

    function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#datefinal").val();
        var almacen = $("#almacen").val();
        
        if((fechainicial != "") &&(fechafinal != "") && (almacen!="")){
            
            if((fechainicial)<=(fechafinal)) {                    
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#f_consumo_dia').submit();
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
            $("#ex").click(function(event){
                event.preventDefault();    
                var $fecha_inicio = $("input[name='dateinicial']"),$fecha_final = $("input[name='datefinal']"),$almacen = $("input[name='almacen']");
                
                if($fecha_inicio.val() == '' && $fecha_final.val() == ''){
                    //alert("Debe seleccionar el rango de fechas, antes de generar el Excel");
                     $("#mensaje").html("Debe seleccionar el rango de fechas, antes de generar el Excel");
                     $("#mensaje").removeClass('hidden');
                }else{
                    var dir = "<?php echo site_url("informes/habitos_consumo_dia_excel");?>";
                    $("#mensaje").html("");
                    $("#mensaje").addClass('hidden');
                    $("#f_consumo_dia").attr('action',dir);
                    $("#f_consumo_dia").submit();
                }
                    
                

            /*  var filtro = 0;

                if($("#almacenes").val() != '-1'){

                    filtro = $("#almacenes").val();

                }

                var dir = "<?php echo site_url("informes/exexistensiasinventario");?>?almacen="+filtro;

                $(this).attr('href', dir); */    

            });
        })
                
        mixpanel.track("Informe_habitos_consumo_dia");  
    </script>
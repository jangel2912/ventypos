<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
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
                 <div id="mensaje" class="alert alert-error hidden">
                    
                </div> 
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Informe de Devoluciones");?></h2>
            </div>

            <form class="form-inline" action="<?php echo site_url("informes/devolucionesinforme_data");?>" method="POST" target="_blank" id="validate" >
                <div class="form-group">
                    <label for="exampleInputName2">Fecha Inicial Devolución</label>
                    <input type="text" name="dateinicial" id="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="form-control datepicker" readonly required/>                     
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Fecha Final Devolución</label>
                    <input type="text" name="datefinal" id="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="form-control  datepicker " readonly required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Almacén</label>
                    <?php 						 
                        echo "<select id='almacen'  name='almacen' class='form-control' required >"; 
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
                </div>
                <a data-tooltip="Descargar Excel" onclick="verificar()">                        
                    <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                </a> 
            </form>

            <!--
            <form action="<?php echo site_url("informes/transacionesinforme_data");?>" method="POST" target="_blank" >
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="15%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>						
						<td width="30%">Almacen : 
						
						 <?php 						 
                            echo "<select  name='almacen' >"; 
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
						<?php if( $filtro_ciudad == 'si'){ //administrador ?>
						<td hidden width="30%">Ciudad : <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?></td>
						<?php } ?>						 
					<?php } ?>							
                        <td width="30%"><br/> <input type="submit" value="Descargar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>-->
            </div>
        </div>
											
    <div class="span12 block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

            <table class="table" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td style="border-bottom: inset 1px #000000;"><b>Fecha</b></td>
                <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Subtotal</b></p></td>							
                <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Total de ventas</b></p></td>

                    <td  style="border-bottom: inset 1px #000000;"><p align="right"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></p></td>
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
$fecha=$mesespan." del ".$anoespanol;
return $fecha;
}                
    $total=0; $subtotal=0; 
    foreach($data['total_ventas'] as $value){?>	
            <tr>
                <td><b><?php echo fechaespanol($value['fecha_dia']);?> </b></td>
                <td><p align="right"><b><?php echo " $ ".number_format($value['total_precio_venta']);?> </b></td>						
                <td><p align="right"><b><?php echo " $ ".number_format($value['total_precio_venta'] + $value['total_impuesto']);?></b></p></td>
                <td><b>&nbsp; </td>
            </tr>																							
        <?php   
                    $total += $value['total_precio_venta'] + $value['total_impuesto'];  
                    $subtotal += $value['total_precio_venta'];  
        ?>
        <?php } ?>
            <tr>
                <td style="border-top: inset 1px #000000;"><b>Total </b></td>
                <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo " $ ".number_format($subtotal);?></b></p></td>
                <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo " $ ".number_format($total);?></b></p></td>
                <td style="border-top: inset 1px #000000;"><b>&nbsp; </td>
            </tr> 
        </table> 
		 <?php } ?>			
    </div>
    </div>
<script type="text/javascript">

        function verificar(){
			var fechainicial = $("#dateinicial").val();
			var fechafinal = $("#datefinal").val();
			var almacen = $("#almacen").val();
            
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
				
				  $("#provincia").append("<option value=''>Todas las Ciudades</option>"); 

                $.each(data, function(index, element){

                    provincia = "<?php echo $this->input->post('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }


    
   // mixpanel.track("Informe_de_transacciones");   
</script> 
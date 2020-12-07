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
                <div class="head blue">
                    <h2><?php echo custom_lang('ventasxclientes', "Total de Ventas por Mes");?></h2>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">                       
                    </div>
                    <div class="col-md-6 btnizquierda">
                        <div class="col-md-2 col-md-offset-10">
                            <a data-tooltip="Exportar Excel" id="btn_ex_ventas_mes">                        
                                <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                            </a> 
                        </div>
                    </div>
                </div>
                <form action="<?php echo site_url("informes/ex_ventas_mes");?>" method="POST" id="ex_ventas_mes">
                    <table>
                        <tr>
                            <td width="30%"><br/>
                            <input type="hidden" value="<?php echo $this->input->post('dateinicial');?>" name="dateinicial" id="ex_dateinicial">
                            <input type="hidden" value="<?php echo $this->input->post('datefinal');?>" name="datefinal" id="ex_datefinal">
                            <input type="hidden" value="<?php echo $this->input->post('almacen') ?>" name="almacen" id="ex_almacen">
                            <input type="hidden" value="" name="ex_provincia" id="ex_provincia">
                            <!--<input type="submit" value="Exportar a Excel" class="btn btn-success" id="btn_ex_ventas_mes"/>-->
                            
                            </td>
                        </tr>                
                    </table>
                </form>
                <!--
                <form action="<?php echo site_url("informes/total_ventas_mes_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" name="dateinicial" id="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="15%">Fecha Final : <input type="text" name="datefinal" id="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>						
						<td width="30%">Almacen : 
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
						<?php if( $filtro_ciudad == 'si'){ //administrador ?>
						<td hidden width="30%">Ciudad : <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?></td>
						<?php } ?>	 
						 </td>
					<?php } ?>	
                        <td width="30%">Filtrar por:
                            <?php 
                            $filtro = [
                                "horas" => "Horas",
                                "dias" => "Días",
                                "mes" => "Mes"                                
                            ];
                           
                            echo "<select  name='filtro' id='filtro'>"; 
                            //foreach($filtro as $fil ){
                            foreach ($filtro as $fil => $fvalue) {                                   
                                if($fil == $this->input->post('filtro')){
                                    $selected = " selected=selected ";
                                } else {
                                    $selected = "";
                                }        
                                echo "<option $selected value=" . $fil . ">" . $fvalue . "</option>";
                            }    
                            echo "</select>";						 
						 ?>  
                        </td>
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>-->
        </div>
    </div>
											
    <div class="span12 block">
        <?php  if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border-bottom: inset 1px #000000;"><b>Fecha</b></td>
                            <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Subtotal</b></p></td>							
                            <?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Total de las propinas</b></p></td>
                            <?php }?>
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
                $fecha=$diafecespanol." de ".$mesespan." del ".$anoespanol;
                return $fecha;
                }               
                $total=0; $subtotal=0; $total_saldo_a_favor=0;
				foreach($data['total_ventas'] as $value){?>	
                        <tr>
                            <!--<td><b><?php echo fechaespanol($value['fecha_dia']);?> </b></td>-->
                            <td><b><?php echo $value['mes'];?> </b></td>
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['subtotal_precio_venta']);?> </b></td>
                            <?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['propina_venta']);?> </b></td>                            
                            <?php }?>
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['total_precio_venta']);?></b></p></td>
							<td><b>&nbsp; </td>
                        </tr>																							
                    <?php   
					         $total += $value['total_precio_venta'];  
				             $subtotal += $value['subtotal_precio_venta']; 
                             $total_saldo_a_favor = $value['saldo_a_favor'];
                             
				   ?>
				   <?php } ?>

                   <?php if(count($data["ventas_por_mes"]) > 0){ 
                            foreach($data["ventas_por_mes"] as $mes){ ?>
                            <tr>
                                <td style="border-top: inset 1px #000000;"><b>Total Ventas <?php echo $mes["mes"] ?></b></td>
                                <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($mes["venta_sin_impuesto"]);?></b></p></td>
<?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td style="border-top: inset 1px #000000;"><p align="right"></p></td>
                            <?php }?>
                                <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($mes["total_venta"]);?></b></p></td>
                                <td style="border-top: inset 1px #000000;"><b>&nbsp; </td>
                            </tr> 
                      <?php } } ?>
                        <tr>
                            <td style="border-top: inset 1px #000000;"><b>Total Devoluciones </b></td>
                            <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data["subtotaldevoluciones"]);?></b></p></td>
                            <?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td style="border-top: inset 1px #000000;"><p align="right"></p></td>
                            <?php }?>
                            <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data["devoluciones"]);?></b></p></td>
                            <td style="border-top: inset 1px #000000;"><b>&nbsp; </td>
                        </tr>
                        <tr>
                            <td style="border-top: inset 1px #000000;"><b>Total saldo a favor</b></td>
                            <td style="border-top: inset 1px #000000;"><p align="right"></p></td>
                            <?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td style="border-top: inset 1px #000000;"><p align="right"></p></td>
                            <?php }?>
                            <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_saldo_a_favor);?></b></p></td>
                            <td style="border-top: inset 1px #000000;"><b>&nbsp; </td>
                        </tr>	
                        <tr>
                            <td style="border-top: inset 1px #000000;"><b>Total </b></td>
							<td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal-$data["devoluciones"]);?></b></p></td>
                            <?php if($data_empresa['data']['tipo_negocio'] == "restaurante"){?>
                            <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data["propina"]);?></b></p></td>                            
                            <?php }?>
                            <td style="border-top: inset 1px #000000;"><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total-$data["devoluciones"]);?></b></p></td>
							<td style="border-top: inset 1px #000000;"><b>&nbsp; </td>
                        </tr> 
				  </table> 
		 <?php } ?>			
    </div>
    </div>
    <div class="pull-right">
        <!--<a class="btn btn-default" type="button" href="<?php echo site_url("informes/total_ventas");?>"><?php echo custom_lang('sima_search', "Volver"); ?></a>-->
        <a data-tooltip="Volver" href="<?php echo site_url("informes/total_ventas");?>">                        
            <img alt="Volver" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>"> 
        </a> 
    </div>   
<script type="text/javascript">

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
    
    $("#btn_ex_ventas_mes").click(function(e){
        e.preventDefault();
        $("#ex_dateinicial").val('<?php echo $this->input->post('dateinicial');?>');
        $("#ex_datefinal").val('<?php echo $this->input->post('datefinal');?>');
        $("#ex_almacen").val('<?php echo $this->input->post('almacen');?>');
        $("#ex_provincia").val($("#provincia").val());
        $("#ex_ventas_mes").submit();
    })
    
    mixpanel.track("totalventasxmes"); 
</script> 
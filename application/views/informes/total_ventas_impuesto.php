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
            <div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Total de Ventas por Impuestos");?></h2>
            </div>
                <form action="<?php echo site_url("informes/total_ventas_impuesto_data");?>" method="POST" id="validate">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker" readonly required/>  </td>
                        <td width="15%">Fecha Final : <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker" readonly required/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>						
						<td width="30%">Almacen : 
						 <?php 
                            echo "<select  name='almacen' >";    
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
						<td width="30%">Ciudad : <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'");?></td>
						<?php } ?>	 
						 </td>
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
<?php if(isset($fechafinal) && !empty($fechainicial)){?>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
                <table class="table text-right" width="100%" cellspacing="0" cellpadding="0">
                        <tr>	
							<th style="text-align: left;" width="15%" ><b>Nombre</b></th>							
                            <th style="text-align: right;" width="25%"><b>Impuesto Valor</b></th>	
							<th style="text-align: right;" width="25%" ><b>Subtotal</b></th>							
                            <th style="text-align: right;" width="25%"><b>Total</b></th>	                            
                        </tr>

                        <?php
                        $total_descuento = 0; $total_impuesto = 0;  $total_valor = 0;  $totalisimo=0;
                        $total_subtotal = 0;
                        $total_total = 0;
                        $i=0;

                        foreach ($data['total_ventas'] as $value){
                            
                            $total_subtotal += $value["subtotal"];              
                            $total_total+= $value["total"];              
                            
                        ?>

                        <tr>
                        <td  style="text-align: left;" width="15%"> <?php echo  $value["nombre_impuesto"]; $total_impuesto += $value["impuesto"];  ?> </td>
                        <td  style="text-align: right;" width="25%"> <?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value["impuesto"]);  ?></td>
                        <td  style="text-align: right;" width="25%"> <?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value["subtotal"]);  ?></td>
                        <td  style="text-align: right;" width="25%"> <?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value["total"]);  ?></td>              
                        </tr>         
                
                <?php  } ?>	
                        <tr>	
                            <th style="text-align: left;" width="15%" ><b>Devoluciones</b></th>							
                            <th style="text-align: right;" width="25%"><b>(-)<?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data['impuesto_devolucion']); ?></b></th>	
                            <th style="text-align: right;" width="25%"><b>(-)<?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data["devoluciones"]) ?></b></th>	
                            <th style="text-align: right;" width="25%"><b>(-)<?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($data["devoluciones"] + $data['impuesto_devolucion']) ?></b></th>	                                                        
                        </tr>
                            <tr>	
                            <th style="text-align: left;" width="15%" ><b>Total</b></th>							
                            <th style="text-align: right;" width="25%"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_impuesto - $data['impuesto_devolucion']) ?></b></th>	
                            <th style="text-align: right;" width="25%"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_subtotal - $data["devoluciones"]) ?></b></th>	
                            <th style="text-align: right;" width="25%"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total_total - $data["devoluciones"]) ?></b></th>	                                                        
                        </tr>
                </table>
		    <?php } ?>			
        </div>
    </div>
</div>
<script type="text/javascript">

    function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#datefinal").val();
        
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
    
    //mixpanel.track("Informe_total_ventas_impuesto");  
</script> 
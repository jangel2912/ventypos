<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");
?>
<style>
    .ui-datepicker{
        background-color: #fff;
    }
</style>
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
                <h2><?php echo custom_lang('ventasxclientes', "Resumen de Formas de Pago");?></h2>
            </div>
                <p style="margin-top:10px;"> (Tenga en cuenta que el total de las ventas consultadas en este informe no discrimina los descuentos) </p>                                        

            <form class="form-inline" action="<?php echo site_url("informes/total_formas_pago_data");?>" method="POST"  id="validate" >
                <div class="span12">
                    <div class="form-group">
                        <label for="exampleInputName2">Fecha Inicial</label>
                        <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker form-control" readonly required/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail2">Fecha Final</label>
                        <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="form-control datepicker" readonly required/>
                    </div>
                </div>
                <div class="span12">
                    <?php  if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>
                        <div class="form-group">
                            <label for="exampleInputEmail3">Almacén</label>
                            <?php 
                                echo "<select  name='almacen' class='form-control' >";    
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
                        <div class="form-group">
                            <label for="exampleInputEmail3">Forma de Pago</label>
                            <select name="forma_pago" id="forma_pago" class="form-control">
                                <option value="0">Todas las Formas de Pago</option>
                            <?php 
                                foreach($data1['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>	
                        </div>
                    <?php }	 ?>                 
                    
                    <a data-tooltip="Consultar" onclick="verificar()">                        
                        <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                    </a> 
                 </div>
            </form>
            <!--
            <form action="<?php echo site_url("informes/total_formas_pago_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="15%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador    ?>
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
						<td width="30%">Forma de pago :
                            <select name="forma_pago" id="forma_pago">
                                <option value="0">Todas las formas de pago</option>
                            <?php 
                                foreach($data1['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>						 
						 </td>
					<?php } ?>		
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>-->
        </div>
        </div>

        <?php if(isset($fechafinal) && !empty($fechainicial)){?>
        <div class="col-md-12">
            <div class="col-md-6">                
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a data-tooltip="Exportar Excel" onclick="$('#validate2').submit()">                        
                        <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a>
                </div>
            </div>
        </div>
        <div class="span11">
            <table class="table" width="50px" cellspacing="0" cellpadding="0">
                <tr>
                    <th>	
                        <form action="<?php echo site_url("informes/total_formas_pago_excel");?>" method="POST" id="validate2" >			
                                <input type="hidden" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" />
                                <input type="hidden" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" />
                                <input type="hidden" name="almacen" value="<?php echo $this->input->post('almacen');?>" />
                                <input type="hidden" name="provincia" value="<?php echo $this->input->post('provincia');?>" />
                                <input type="hidden" name="forma_pago" value="<?php echo $this->input->post('forma_pago');?>" />					
                                <!--<input type="submit" value="Exportar a excel" class="btn btn-success"/>-->
                                <!--<a data-tooltip="Exportar Excel" onclick="$('#validate2').submit()">                        
                                    <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                                </a> -->
                        </form>
                    </th>
                </tr>
            </table>
            <table class="table" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="border-bottom: inset 1px #000000;"><p align="left"><b>#</b></p></td>	
                    <td style="border-bottom: inset 1px #000000;"><p align="left"><b>Forma de pago</b></p></td>
                    <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Total</b></p></td>

                    <td  style="border-bottom: inset 1px #000000;"><p align="right"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></p></td>
                </tr>
                    <?php            
                    $venta=0; $utilidad=0; $formpago='';
                    foreach($data['total_ventas_forma_pago_result'] as $value){
                    
                        $formpago=str_replace("_"," ",$value->forma_pago); 
                        $formpago=strtolower($formpago);
                        
                        ?>	
                <tr>
                    <td><p align="left"><b><?php echo $value->cantidad;?> </b></td>	
                    <td><p align="left"><b><?php echo $formpago;?> </b></td>		
                    <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value->total_venta);?> </b></td>					
                    <td><p align="right"><b></b></p></td>
                </tr>																							
                    <?php
                          
                        if($value->forma_pago !== 'nota_credito'){
                            $venta += $value->total_venta;
                        }

                        //$venta += $value->total_venta;
                    } ?>	
                <tr>
                    <td><p align="left"><b> </b></td>	
                    <td><p align="left"><b>Total</b></td>		
                    <td><p align="right"><b>$ <?php echo $ci->opciones_model->formatoMonedaMostrar($venta);?> </b></td>					
                    <td><p align="right"><b></b></p></td>
                </tr>						
            </table><br />

            <table class="table" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="border-bottom: inset 1px #000000;"><p align="left"><b>Fecha</b></p></td>	
                    <td style="border-bottom: inset 1px #000000;"><p align="left"><b>Cliente</b></p></td>
                    <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Factura</b></p></td>					
                    <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Forma de pago</b></p></td>			
                    <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Valor</b></p></td>
                    <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Almacen</b></p></td>                  
                </tr>			
				<?php            $venta=0; $utilidad=0; $formpago='';
				foreach($data['total_ventas'] as $value){
				
				$formpago=str_replace("_"," ",$value['forma_pago']); 
				$formpago=strtolower($formpago);
				
				?>	
                        <tr>
	                        <td><p align="left"><b><?php echo $value['fecha_factura'];?> </b></td>	
	                        <td><p align="left"><b><?php echo $value['nom_cli'];?> </b></td>		
	                        <td><p align="right"><b><?php echo $value['factura'];?> </b></td>						
                            <td><p align="right"><b><?php echo ucfirst($formpago);?></b></p></td>				
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['valor_recibido']);?></b></p></td>
							<td><b><?php echo $value['almacen'] ?></b></td>
                        </tr>																							
                    <?php   
					         $venta += $value['valor_recibido'];  
				   ?>
				   <?php } ?>
                        <tr>
	                        <td><p align="right"><b> </b></td>
	                        <td><p align="right"><b> </b></td>	
	                        <td><p align="right"><b> </b></td>						
                            <td><p align="right"><b>Total</b></p></td>				
                            <td><p align="right"><b><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($venta);?></b></p></td>
							<td><b>&nbsp; </td>
                        </tr>
            </table> 				  
		 <?php } ?>				
    </div>

    <div class="col-md-1 col-md-offset-10 pull-right">            
        <!--<a class="btn btn-default" type="button" href="<?php echo site_url("informes/total_ventas");?>"><?php echo custom_lang('sima_search', "Volver"); ?></a>-->
        <a data-tooltip="Volver" href="<?php echo site_url("informes");?>">                        
            <img alt="Volver" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>"> 
        </a> 
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
    /*
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
    */
    //mixpanel.track("Informe_de_ventas_por_formas_de_pago");      
</script> 
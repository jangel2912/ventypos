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
                <h2><?php echo custom_lang('ventasxclientes', "Total de Ventas por Vendedor");?></h2>
            </div>
                <form action="<?php echo site_url("informes/total_ventas_data_vendedor");?>" method="POST" id="validate">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker" readonly required/>  </td>
                        <td width="30%">Fecha Final : <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker" readonly required/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacen :  	<?php 
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
                            ?>    </td>
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
											
    <div class="span12 block"  style="margin-left:0px;">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Almacen");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Subtotal");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Total");?></th>
                               <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Porciento");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Valor Total de Comision");?></th>
                            </tr>	
				<?php 
              $total=0;
				foreach($data['total_vendedor'] as $value){?>	
                            <tr>
                                <td><?php echo $value['nombre_vendedor'];?></td>
                                <td><?php echo $value['nombre_almacen'];?></td>
                                <td><?php echo $value['impuesto'];?></td>
                                <td><?php echo $value['subtotal'];?></td>
                                <td><?php echo $value['total_venta'];?></td>
                                <td><?php echo $value['comision'];?></td>
                                <td><?php echo $value['total_comision'];?></td>
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
    
    //mixpanel.track("Informe_total_ventas_vendedor");  
</script>
<style type="text/css">
.ui-dialog{
    z-index: 9000!important;
}
</style>

<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Orden de Compra", "Cerrar Caja");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

 <?php echo form_open("caja/cerrar_caja", array("id" =>"validate"));?>
 
<div class="row-fluid">
    <div class="span6">
<?php foreach ($data['caja_result_1'] as $value){ ?>
	         <div class="data-fluid">
                    <div class="row-form ">
                        <div class="span3">Almacen:</div>
                        <div class="span9"> <?php //echo form_dropdown('almacen', $data1['almacen'], set_value('almacen', $this->input->post('almacen')));						   
    foreach($data1['almacen'] as $f){
        if($f->id == $value->id_Almacen){
           echo $f->nombre;
        }       
     }     ?>
                     </div>
              </div>

	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">Caja:</div>
                        <div class="span9"> <?php //echo form_dropdown('almacen', $data1['almacen'], set_value('almacen', $this->input->post('almacen')));						   
    foreach($data1['caja'] as $f){
        if($f->id == $value->id_Caja){
           echo $f->nombre;
        }       
     }     ?>
                     </div>
              </div>			  
	         <div class="data-fluid">
                    <div class="row-form"  style="border-bottom:1px #000000 solid">
                        <div class="span3 " class="head blue" style="border-right:1px #000000 solid">Ingresos</div>
                        <div class="span9">Cantidad
						</div>
              </div>	  	
<?php }  ?>			  		  
<?php  $total=0; $egresos=0; $ingresos=0; $forma_pago_gastos=0; $total_gastos=0;
    foreach ($data['caja_result_2'] as $value1){
	 
			$formpago1=str_replace("_"," ",$value1->forma_pago);
			$formpago1=ucfirst($formpago1);		


?>	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span3" style="border-right:1px #000000 solid"><?php echo $formpago1; ?>:</div>
                        <div class="span9">$  <?php echo number_format($value1->total); ?>
						</div>
              </div>
<?php

if(ucfirst($value1->forma_pago) == 'Efectivo'){   $ingresos += $value1->total;
						$total += $value1->total;   }

   } 
 ?>
			  <br />
	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span3" style="border-right:1px #000000 solid">Egresos</div>
                        <div class="span9">Cantidad
						</div>
              </div>	 
<?php  $totalegresos=0; 
 	foreach ($data['caja_result_4'] as $value1){ 

if(ucfirst($value1->forma_pago) == 'Efectivo'){     $totalegresos += $value1->total;  $totalegresos = $value1->total;   }
      

    }
 ?>			    <br />
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3"  style="border-right:1px #000000 solid">Total de cierre:</div>
                        <div class="span9"> $  <?php echo number_format($total - $totalegresos); ?>	</div>
              </div>
			  
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3"></div>
                        <div class="span9"> <br /> <input type="submit" value="Cerrar" class="btn btn-primary"/>
						</div>
              </div>			  			  		  
            </div>
        </div>
		
	<input type="hidden" readonly="readonly" value="<?php echo $totalegresos; ?>" placeholder="" name="egresos" />
	<input type="hidden" readonly="readonly" value="<?php echo $ingresos; ?>" placeholder="" name="ingresos" />
		<input type="hidden" readonly="readonly" value="<?php echo $total - $totalegresos; ?>" placeholder="" name="total" />	
</form>	
		
</div>

<script type="text/javascript">

    $(document).ready(function(){


        
        $("#foma_pago1, #foma_pago2, #foma_pago3, #foma_pago4, #foma_pago5, #foma_pago6, #foma_pago7, #foma_pago8, #foma_pago9, #foma_pago10, #foma_pago11, #foma_pago12").keyup(function(e){
																																			
            $("#total_formapago").val(Math.round((
														   
     parseInt($("#foma_pago1").val()) + parseInt($("#foma_pago2").val()) + parseInt($("#foma_pago3").val()) + parseInt($("#foma_pago4").val())  
	 + parseInt($("#foma_pago5").val()) 
	  					  
				  )));
			            
        });
        

    });

</script>
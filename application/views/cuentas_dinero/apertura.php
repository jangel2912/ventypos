<style type="text/css">
.ui-dialog{
    z-index: 9000!important;
}
</style>

<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Orden de Compra", "Apertura de Caja");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

 <?php echo form_open("caja/apertura", array("id" =>"validate"));?>
<div class="row-fluid">
    <div class="span6">
           <div class="block">
             <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">Fecha:</div>
                        <div class="span9"><input type="text" readonly="readonly" value="<?php echo date('Y-m-d'); ?>" placeholder="" name="fecha" />
                     </div>
              </div>

	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">Almacen:</div>
                        <div class="span9"> <?php //echo form_dropdown('almacen', $data1['almacen'], set_value('almacen', $this->input->post('almacen')));
						 
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
                     </div>
              </div>			  
 <?php $i=0; foreach($data1['forma_pago'] as $f){ $i++; ?>
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">
				<?php echo $f->mostrar_opcion; ?>:<input type="hidden"   placeholder="" name="foma_pago[]" value="<?php echo $f->mostrar_opcion; ?>"  /></div>
                        <div class="span9">  <input type="text"  value="0" placeholder="" name="valor[]"  id="foma_pago<?php echo $i; ?>" />
						</div>
              </div>
 <?php }  ?>
			  
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3">Total:</div>
                        <div class="span9">  <input type="text" readonly="readonly"  value="0" placeholder="" name="total_formapago"  id="total_formapago" />
						</div>
              </div>
			  
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3"></div>
                        <div class="span9"> <br /> <input type="submit" value="Enviar" class="btn btn-primary"/>
						</div>
              </div>			  			  		  
            </div>
        </div>
		
		
</div>
</form>

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
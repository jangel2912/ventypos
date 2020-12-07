<?php 

$ci =&get_instance();
$ci->load->model("opciones_model");
$moneda =$ci->opciones_model->getDataMoneda();
?>

<style type="text/css">
    
    .ui-dialog{
        z-index: 9000!important;
    }
    .site-footer{
        display: none;
    }
    
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Caja" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_caja']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cierres de Cajas", "Cierres de Cajas");?></h1>
</div>

 <?php $permisos = $this->session->userdata('permisos');

                    $is_admin = $this->session->userdata('is_admin');
 
 
  echo form_open("caja/cerrar_caja", array("id" =>"validate"));?>
 
<div class="row-fluid">
    <div class="span6">
<?php foreach ($data['caja_result_1'] as $value){ ?>
	         <div class="data-fluid">
                    <div class="row-form ">
                        <div class="span4">Almacen:</div>
                        <div class="span7"> <?php //echo form_dropdown('almacen', $data1['almacen'], set_value('almacen', $this->input->post('almacen')));						   
    foreach($data1['almacen'] as $f){
        if($f->id == $value->id_Almacen){
           echo $f->nombre;
        }       
     }     ?>
                     </div>
              </div>

	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span4">Caja:</div>
                        <div class="span7"> <?php //echo form_dropdown('almacen', $data1['almacen'], set_value('almacen', $this->input->post('almacen')));						   
    foreach($data1['caja'] as $f){
        if($f->id == $value->id_Caja){
           echo $f->nombre;
        }       
     }     ?>
                     </div>
              </div>			  
	         <div class="data-fluid">
                    <div class="row-form"  style="border-bottom:1px #000000 solid">
                        <div class="span4" class="head blue" style="border-right:1px #000000 solid">Ingresos</div>
                        <div class="span7">Cantidad
						</div>
              </div>	  	
<?php }  ?>			  		  
<?php  $total=0; $egresos=0; $ingresos=0; $forma_pago_gastos=0; $total_gastos=0; $totalcreditos=0; $notas_credito_pendiente=0; $existe=0;
    foreach ($data['caja_result_2'] as $value1){
        $formpago1=str_replace("_"," ",$value1->forma_pago);
        $formpago1=ucfirst($formpago1);
        if($formpago1=='Nota credito'){
            $existe=1;
        }
    }
    foreach ($data['caja_result_2'] as $value1){
	
			$formpago1=str_replace("_"," ",$value1->forma_pago);
            $formpago1=ucfirst($formpago1);
            
            if(($formpago1=='Nota credito') &&($existe==1)){
                foreach ($data2 as $value2) {   
                    if($value2['redimida']=='No'){
                        $notas_credito_pendiente += $value2['valor'];
                    } 
                } ?>
                <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                    <div class="span4" style="border-right:1px #000000 solid">Notas Créditos Pendientes:</div>
                    <div class="span7"><?php echo $moneda->simbolo." ".$ci->opciones_model->formatoMonedaMostrar($notas_credito_pendiente); ?></div>
              </div>
                <?php $formpago1="Nota Crédito Redimidas"; 
            }else{ 
                if($existe==0){
                    $existe=1;
                    foreach ($data2 as $value2) {   
                        if($value2['redimida']=='No'){
                            $notas_credito_pendiente += $value2['valor'];
                        } 
                    } ?>
                    <div class="data-fluid">
                        <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span4" style="border-right:1px #000000 solid">Notas Créditos Pendientes:</div>
                        <div class="span7"><?php echo $moneda->simbolo." ".$ci->opciones_model->formatoMonedaMostrar($notas_credito_pendiente); ?></div>
                    </div>
                <?php }
            } 

?>	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                    <div class="span4" style="border-right:1px #000000 solid"><?php echo $formpago1; ?>:</div>
                    <div class="span7"><?php echo $moneda->simbolo." ".$ci->opciones_model->formatoMonedaMostrar($value1->total); ?></div>
              </div>
<?php

    if( ucfirst( $value1->forma_pago == 'Saldo_a_Favor')){   
        // No sumer al cierre de caja
    }else if( ucfirst( $value1->forma_pago == 'Gift_Card')){
        // No sumer al cierre de caja
    }else if(ucfirst($value1->forma_pago) == 'Credito'){
        // No sumer al cierre de caja
    }else{
            $ingresos += $value1->total;
            $total += $value1->total;         
        
    }
} 
   /*if($value1->tipo_movimiento != 'anulada' && $value1->forma_pago != 'Saldo_a_favor'){   $ingresos += $value1->total;
            $total += $value1->total;   }
S
   } */
   //devoluciones 
    $devoluciones = 0;
    foreach ($data['caja_result_5'] as $value1){
        $devoluciones = $value1->total;
    ?>       
    <div class="data-fluid">
        <div class="row-form" style="border-bottom:1px #000000 solid">
        <div class="span4" style="border-right:1px #000000 solid">Devoluciones:</div>
        <div class="span7"><?php echo $moneda->simbolo." ".$ci->opciones_model->formatoMonedaMostrar($value1->total); ?></div>
    </div>
    <?php
    }
    foreach ($data['pago_recibidos'] as $value1){

?>	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span4" style="border-right:1px #000000 solid">Total de pagos a créditos:</div>
                        <div class="span7"><?php echo number_format($value1->total); ?>
						</div>
              </div>
<?php
   } 
?>
			  <br />
	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span4" style="border-right:1px #000000 solid">Egresos</div>
                        <div class="span7">Cantidad
						</div>
              </div>	 
<?php  $totalegresos=0; 
 	foreach ($data['caja_result_4'] as $value1){ 

            //if(ucfirst($value1->forma_pago) != 'Credito'){
                
                $totalegresos =  $value1->total;
            //}
      

    }
 ?>	
 <?php  
    foreach ($data['pago_gastos'] as $value1){

?>	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span4" style="border-right:1px #000000 solid"> Total gastos:</div>
                        <div class="span7"><?php echo number_format($value1->total); ?>
						</div>
              </div>
<?php
   } 
?> 
 <?php  
    foreach ($data['pago_proveedores'] as $value1){

?>	         <div class="data-fluid">
                    <div class="row-form" style="border-bottom:1px #000000 solid">
                        <div class="span4" style="border-right:1px #000000 solid">Total de pagos a proveedores:</div>
                        <div class="span7"><?php echo number_format($value1->total); ?>
						</div>
              </div>
<?php
   } 
?> 
 			    <br />
	         <div class="data-fluid">
                    <div class="row-form">
                        <div class="span4"  style="border-right:1px #000000 solid">Total de cierre:</div>
                        <div class="span7"><?php echo number_format($total - $totalegresos - $devoluciones - $totalcreditos +$notas_credito_pendiente); ?>	</div>
              </div>
			  
	         <div class="data-fluid">
                    <div class="row-form">
                    <div class="col-md-12"> <br /> 
                      <?php if(in_array('1009', $permisos) || $is_admin == 't'):?>
                      <!--
                        <button type="submit" class="btn btn-success">
                            <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>&nbsp;Cerrar Cajas
                        </button> -->
                        <div class="col-md-2 col-md-offset-2">
							<a data-tooltip="Cerrar Caja" onclick="$('#validate').submit()">                       
								<img alt="cierre de caja" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['cierre_caja_verde']['original'] ?>">                                                     
							</a>    
						</div>                                                
                      
                      <div class="col-md-2">
							<a href="<?php echo site_url("caja/listado_cierres")?>" data-tooltip="Regresar a Lista de Cierres">                       
								<img alt="regresar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['regresar_verde']['original'] ?>">                                                     
							</a>
						</div>
                        <?php endif;?>
                      <!--<a href="<?php echo site_url('caja/listado_cierres') ?>" class="btn default">Regresar a lista de cierres</a>-->
                    </div>
                        
						</div>
              </div>			  			  		  
            </div>
        </div>
        
  	<input type="hidden" readonly="readonly" value="<?php echo $totalegresos; ?>" placeholder="" name="egresos" />
  	<input type="hidden" readonly="readonly" value="<?php echo $ingresos; ?>" placeholder="" name="ingresos" />
    <input type="hidden" readonly="readonly" value="<?php echo $total - $totalegresos - $totalcreditos; ?>" placeholder="" name="total" />	
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
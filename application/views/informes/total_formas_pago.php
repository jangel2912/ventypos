<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('cuadrecaja', "Total de utilidad");?></h2>                                          
    </div>
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
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Resumen de formas de pago");?></h2>
            </div>
                <form action="<?php echo site_url("informes/total_formas_pago_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="15%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="15%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador    ?>
						<td width="30%">Almacen :  <?php 
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
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
											
    <div class="span12 block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
							<td style="border-bottom: inset 1px #000000;"><p align="right"><b>Fecha</b></p></td>							
                            <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Forma de pago</b></p></td>						
                            <td style="border-bottom: inset 1px #000000;"><p align="right"><b>Valor</b></p></td>

							 <td  style="border-bottom: inset 1px #000000;"><p align="right"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></p></td>
                        </tr>			
				<?php            $venta=0; $utilidad=0;
				foreach($data['total_ventas'] as $value){
				
				$formpago=str_replace("_"," ",$value['forma_pago']); 
				
				?>	
                        <tr>
	                        <td><p align="right"><b><?php echo $value['mes_facturado'];?> </b></td>						
                            <td><p align="right"><b><?php echo ucfirst($formpago);?></b></p></td>				
                            <td><p align="right"><b><?php echo " $ ".number_format($value['valor_recibido']);?></b></p></td>
							<td><b>&nbsp; </td>
                        </tr>																							
                    <?php   
					         $venta += $value['valor_recibido'];  
				   ?>
				   <?php } ?>
                        <tr>
	                        <td><p align="right"><b> </b></td>						
                            <td><p align="right"><b>Total</b></p></td>				
                            <td><p align="right"><b><?php echo " $ ".number_format($venta);?></b></p></td>
							<td><b>&nbsp; </td>
                        </tr>
				  </table> 				  
		 <?php } ?>				
    </div>
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
    
</script> 
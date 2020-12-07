<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('cuadrecaja', "H&aacute;bitos de consumo por mes");?></h2>                                          
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
                <h2><?php echo custom_lang('ventasxclientes', "H&aacute;bitos de consumo por mes");?></h2>
            </div>
                <form action="<?php echo site_url("informes/habitos_consumo_mes_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
						
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
    ?>   </td>
					<?php } ?>							
                        <td width="30%"><br/> <input type="submit" value="Enviar" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
					<?php 
function fechaespanol($fecha){ //yyyy-mm-dd
$diafecespanol=date("d", strtotime($fecha));
$diaespanol=date("N", strtotime($fecha));
$mesespanol=date("m", strtotime($fecha));
$anoespanol=date("Y", strtotime($fecha));
//Asignamos el nombre en español

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
}                  $total=0;
		 ?>											
    <div class="span12 block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><b>Fecha desde: <?php echo fechaespanol($this->input->post('dateinicial'));?></b></td>
							 <td></td>
                            <td><b>Fecha hasta: <?php echo fechaespanol($this->input->post('datefinal'));?></b></td>
							 <td><p align="right"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></p></td>
                        </tr>			

                        <tr>
                            <th><b>Producto</b></th>
							 <th><b>Cantidad Vendida</b></th>
                            <th><p align="right"><b>Total de ventas</b></p></th>
							<th><b>&nbsp; </th>
                        </tr>	
						
                           <?php  
						     
						  foreach($data['total_ventas_3'] as $prod){
			                
							
							  $total +=  $prod['total_detalleventa'];
						                ?>  
						 	                <tr>                         
                          	                 <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php  echo $prod['nombre'];?></td>
											<td><?php  echo $prod['unidades'];   ?> </td> 
                            	               <td><p align="right">$ <?php echo number_format($prod['total_detalleventa']);?></p></td>
							               	<td><b>&nbsp; </td>
                        	               </tr>                                                                                                                                                                                                                             <?php  }  ?> 
			                                                                                                                                                                                 
				
                        <tr>
                            <td><b>Total </b></td>
							<td></td>
                            <td><p align="right"><b><?php echo " $ ".number_format($total);?></b></p></td>
							<td><b>&nbsp; </td>
                        </tr>					   	 
				  </table> 		   <?php } ?>
    </div>
    </div>
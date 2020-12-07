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
                <h2><?php echo custom_lang('Inventario Menos rotacion', "Inventario con Menos Rotaci&oacute;n ");?></h2>
            </div>
            <br>
            <form class="form-inline" action="<?php echo site_url("informes/menos_rotacion_data");?>" method="POST"  id="validate" >
                <div class="form-group">
                    <label for="exampleInputName2">Fecha Inicial</label>
                    <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker form-control" readonly required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Fecha Final</label>
                    <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="form-control datepicker" readonly required/>
                </div>
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
                <?php }	 ?>  
                                
                <a data-tooltip="Consultar" onclick="verificar()">                        
                    <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                </a> 
            </form>

           <!--
            <form action="<?php echo site_url("informes/menos_rotacion_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
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
	                    </td>
					<?php } ?>					   						
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>
            -->
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>
		<?php foreach($data['totales'] as $value){?>	
	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><b>Total: <?php echo $this->opciones_model->formatoMonedaMostrar($value->total_valor * $value->total_unidades);?></b></td>
                        </tr>	
					</table>
		<?php } ?>				  	 
                    <div class="head blue">
                        <h2>Productos</h2>
                    </div>
	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><b>Nombre</b></td>
                            <td><b>Código</b></td>
							<td><b>Cantidad</b></td>
							<td><b>Valor de Compra</b></td>
							<td><b>Total</b></td>
                        </tr>			
				<?php foreach($data['productos'] as $value){?>	
                        <tr>
                            <td><b><?php echo $value->nombre;?></b></td>
                            <td><b><?php echo $value->codigo;?></b></td>
							<td><b><?php echo $this->opciones_model->formatoMonedaMostrar($value->unidades)."<br>";?></b></td>
							<td><b><?php echo $this->opciones_model->formatoMonedaMostrar($value->precio_compra);?></b></td>
							<td><b><?php echo $this->opciones_model->formatoMonedaMostrar($value->unidades * $value->precio_compra);?></b></td>
                        </tr>																							
                   
				   <?php } ?>	 
				  </table> 
		 <?php } ?>			
        </div>
    </div>
</div>
<script type="text/javascript">
    //mixpanel.track("Informe_inventario_menos_rotacion");      
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

</script>
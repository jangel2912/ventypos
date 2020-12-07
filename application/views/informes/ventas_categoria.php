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
                <h2><?php echo custom_lang('Ventas por Categoría', "Ventas por Categoría");?></h2>
            </div>
            <br><br>
                <form class="form-inline" action="<?php echo site_url("informes/ventas_categoria_data");?>" method="POST" id="validate" >
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
                        echo "<select id='almacen' name='almacen' class='form-control' >";    
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

                <a data-tooltip="Descargar Excel" id="ex">                        
                    <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                </a> 
            </form>


            <!--
            <form action="<?php echo site_url("informes/ventas_categoria_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php  if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>						
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
						 
						 ?>   </td>
					<?php } ?>							
                        <td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>
                    </tr>
                </table>
            </form>-->
            </div>
        </div>
											
    <div class="span12 block" style="margin-left: 0px;">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Categoria</th>
							<th>Subtotal</th>
                            <th>Total de ventas</th>
                            <th>Productos(Nombre - Cantidad)</th>
                        </tr>	
                        <?php $total = 0; 
                        $subtotal=0;
						foreach($data['ventas_categorias'] as $ventas_categoria){?>
                            <tr>
                                <td><?=$ventas_categoria['categoria']?></td>	
								<!--<td><?=$data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($ventas_categoria['subtotal']);?></td>-->
								<td><?=$data_empresa['data']['simbolo'].' '.($ventas_categoria['subtotal']);?></td>					
                                <!--<td><?=$data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($ventas_categoria['total']);?></td>-->
                                <td><?=$data_empresa['data']['simbolo'].' '.($ventas_categoria['total']);?></td>
                                <td>
                                    <ul class="list-group" style="margin:0px;">
                                    <?php foreach($ventas_categoria["descripcion_productos"] as $productos): ?>
                                        <li class="list-group-item">
                                            <span class="badge"><?php echo $productos->unidades;?></span>
                                            <?php echo $productos->nombre_producto;?>
                                        </li>
                                    <?php endforeach;?>
                                    </ul>
                                <!--<div class="panel-group" id="accordion<?php echo $ventas_categoria['categoria'];?>" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="heading<?php echo $ventas_categoria['categoria'];?>">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion<?php echo $ventas_categoria['categoria'];?>" href="#<?php echo $ventas_categoria['categoria'];?>" aria-expanded="true" aria-controls="<?php echo $ventas_categoria['categoria'];?>">
                                                    Ver productos
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?php echo $ventas_categoria['categoria'];?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $ventas_categoria['categoria'];?>">
                                                <div class="panel-body">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                </td>
                            </tr>    
                            <?php   
                             $subtotal+=$ventas_categoria['subtotal'];
					         $total += $ventas_categoria['total'];
				   			?>
                        <?php }?>
                        <tr>
                            <td><b>Total:</b></td>
							<!--<td><p><b><?=$data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($subtotal);?></b></td>-->
							<td><p><b><?=$data_empresa['data']['simbolo'].' '.($subtotal);?></b></td>
                            <!--<td><p><b><?=$data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($total);?></b></p></td>-->
                            <td><p><b><?=$data_empresa['data']['simbolo'].' '.($total);?></b></p></td>
							<td></td>
                        </tr>					   	 
				  </table> 
		 <?php } ?>			
    </div>
     <div class="pull-right">            
        <!--<a class="btn btn-default" type="button" href="<?php echo site_url("informes/total_ventas");?>"><?php echo custom_lang('sima_search', "Volver"); ?></a>-->
        <a data-tooltip="Volver" href="<?php echo site_url("informes/");?>">                        
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

 $("#ex").click(function(e){
    e.preventDefault();
    let fechainicial = $("#dateinicial").val();
    let fechafinal = $("#datefinal").val();
    let almacen = $("#almacen").val();

    var url = "<?php echo site_url('informes/ex_ventas_categoria_data');?>/"+almacen+"/"+fechainicial+"/"+fechafinal;
    location.href = url;
})
   // mixpanel.track("Informe_de_ventas_categorias"); 
    
</script>
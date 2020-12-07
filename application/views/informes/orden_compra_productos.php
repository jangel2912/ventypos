<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
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
                <h2><?php echo custom_lang('ventasxclientes', "Productos Comprados");?></h2>
            </div>
                <form action="<?php echo site_url("informes/orden_compra_productos_data");?>" method="POST" id="validate">
                <table>
                    <tr>
					<td width="30%">Producto: <input type="text" name="product-service"  id="product-service"  value="<?php echo $this->input->post('producto');?>" /> </td>		
                        <td width="20%">Fecha Inicial : <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="20%">Fecha Final : <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
						
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacen :  	<?php 
                            echo "<select  name='almacen' id='almacen' >";    
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
                        <td width="30%"> </td>
                    </tr>
                    <tr>
					<td width="30%">Proveedor: <input type="text" name="datos_proveedores"  id="datos_proveedores"  value="<?php echo $this->input->post('datos_proveedores');?>"/> </td>		
                        <td width="20%" style="text-align: center;">
                            <a data-tooltip="Consultar" onclick="verificar()">                        
                                <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                            </a>
                            <a data-tooltip="Descargar Excel" onclick="exportar()">                        
                                <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                            </a>
                        </td>
                        <td width="20%"><input type="hidden" name="proveedor"  id="id_proveedores"    / > <!-- <input type="submit" value="Consultar" class="btn btn-success"/>--></td>
						
					<?php if( $is_admin == 't' ){ //administrador ?>	
						<td width="30%">  </td>
					<?php } ?>							
                        <td width="30%"><br/></td>
                    </tr>					
                </table>
				<input type="hidden" name="producto"  id="descripcion"/>
            </form>
            </div>
        </div>
    </div>
					<?php 
function fechaespanol($fecha){ //yyyy-mm-dd
$diafecespanol=date("d", strtotime($fecha));
$diaespanol=date("N", strtotime($fecha));
$mesespanol=date("m", strtotime($fecha));
$anoespanol=date("Y", strtotime($fecha));
//Asignamos el nombre en espa�ol

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
<div class="row-fluid">
    <div class="span12">
        <div class="block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th><b>Fecha</b></th>
							<th><b>Proveedor</b></th>						
                            <th><b>Producto</b></th>
							 <th><b>Cantidad Comprada</b></th>
                            <th><p align="right"><b>Precio de Compra</b></p></th>
							<!--<th><b>&nbsp; </th>-->
                        </tr>	
						
                           <?php  
						     
						  foreach($data['total_ventas_3'] as $prod){
			                
                            ?>  
                            <tr>  
                                <td> <?php  echo fechaespanol($prod['fecha']);?></td>   
                                <td> <?php  echo $prod['nomprove'];?></td>                       
                                <td> <?php  echo $prod['nombre_producto'];?></td>
                                <td><?php  echo $prod['unidades'];   ?> </td> 
                                <td><p align="right"><?php echo $data_empresa['data']['simbolo'].' '.($prod['precio_compra']);?></p></td>
                                <!--<td><b>&nbsp; </td>-->
                            </tr>                                                                                                                                                                                                                     <?php  }  ?> 
										   				   	 
				  </table> 		  
			 <?php } ?>

        </div>
    </div>
</div>

<script type="text/javascript">	

    function verificar(){
        let action = "<?= site_url('informes/orden_compra_productos_data');?>";
        $("#validate").attr('action',action);
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

    


        $("#datos_proveedores").autocomplete({

			source: "<?php echo site_url("proveedores/get_ajax_proveedores"); ?>",

			minLength: 1,

			select: function( event, ui ) {

                $("#id_proveedores").val(ui.item.id);
				$("#otros_datos").val(ui.item.descripcion);

			}

		});


        $('#product-service').autocomplete({

            source: function( request, response ) {

                    $.ajax({

                            url: "<?php echo site_url("productosf/filtro_prod"); ?>",

                            type:"GET",

                            dataType: "json",

                            data: {  term: request.term, cli: 1  },

                            success: function(data) {



                                response( $.map( data, function( item ) {
				                            var  nom1 = item.nombre.replace("(", " ");
											 var nom2 = nom1.replace(")", " "); 
											 
				                            var  descripcion1 = item.descripcion.replace("(", " ");
											 var descripcion2 = descripcion1.replace(")", " "); 											 
                                        return {

											  
                                                value: item.nombre,

                                                id: item.id,

                                                codigo: item.codigo,

                                                precio: item.precio,

                                                porciento: item.porciento,

                                                descripcion: item.descripcion2,

                                                precio_venta: item.precio_venta




                                        }

                                }));

                            }

                    });

            },

            minLength: 1,

            select: function( event, ui ) {


                $("#product-service").val(ui.item.id);

                $("#descripcion").val(ui.item.value);

                $("#id_producto").val(ui.item.id);

                $("#codigo").val(ui.item.codigo);
				
				$("#id").val(ui.item.id);

            }
           
        });
   
    mixpanel.track("Informe_orden_compra_productos");  


   function exportar(){
        let action = "<?= site_url('informes/ex_orden_compra_productos_data');?>";
        $("#validate").attr('action',action);
        let fechainicial = $("#dateinicial").val();
        let fechafinal = $("#datefinal").val();
        
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
</script>	
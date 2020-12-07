<style type="text/css">
.head{
	 cursor:pointer; cursor: hand
}
</style>
<script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>
<script>
    $(document).on('blur','.dataMoneda',function(){
        $(this).val(limpiarCampo($(this).val()));
        if($(this).val() == "NaN") { $(this).val("");}
    });
</script>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_price_book', "Producto - Libro de precios");?></h2>                                          
    </div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="block">
			<a href="<?php echo site_url('lista_precios/nuevo') ?>" style="float: left;margin-right: 10px;padding-bottom: 7px;padding-top: 7px;" class="btn btn-success"><p style="margin-bottom: 0px;"><?php echo custom_lang('sima_new_price_list', "Nueva lista");?></p></a>
			<a href="<?php echo site_url('lista_precios/index') ?>" style="float: left;margin-right: 10px;padding-bottom: 7px;padding-top: 7px;" class="btn btn-success"><p style="margin-bottom: 0px;"><?php echo custom_lang('sima_new_price_list', "listas de precios");?></p></a>		   
			<a href="<?php echo site_url('lista_precios/importar') ?>" style="float: left;margin-right: 10px;padding-bottom: 7px;padding-top: 7px;" class="btn btn-success"><p style="margin-bottom: 0px;"><?php echo custom_lang('sima_new_price_list', "Importar libro de precios");?></p></a>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<?php
            $message = $this->session->flashdata('message');
            $validate = $this->session->flashdata('validar_almacen'); 
            if(!empty($message)):?>
                <div class="<?php echo 'alert alert-'.$validate ?>">
                    <?php 
	                    echo $message;
	                    $arch = $this->session->flashdata('archivo');
	                    if(!empty($arch))
	                    {
                    ?>
                    	<a href="../../uploads/archivos_productos/Libro de precios no importados.xlsx" download="Libro de precios no importado"> Descargar Archivo </a>
                    <?php  
                 		} 
                 	?>
                </div>
        <?php endif; ?>
	</div>
 	<div class="span12">
		<div class="block">
			<div id="form">
				<!--Seleccionar fecha -->
			    <div class="row-form">
		            <div class="span2">Inicio      
                	   <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha-inicio" id="fecha-inicio"/>
                	   <?php echo form_error('fecha'); ?>
                	</div>
                	<div class="span2">Fin    
                	   <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha-fin" id="fecha-fin"/>
                       <?php echo form_error('fecha'); ?>
                	</div> 
		            <div class="span2">Nombre de la lista:</div>
		            <div class="span4"><input type="text" value="" placeholder="" id="nombre" name="nombre"></div>	 
                </div>
                <!-- .......................................................... -->
		      	<div class="row-form">
		        </div>
				<div class="row-form">
					<div class="span2">Grupo:</div>
                    <div class="span10">
                        <select id="seleccionar-grupo">
                            <option>Seleccionar..</option>
                            <?php 
                                foreach ($grupo_clientes as $key => $value) {
                                    echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row-form">
					<div class="span2">Almacen:</div>
                    <div class="span10">
                        <select id="seleccionar-almacen">
                            <option>Seleccionar..</option>
                            <?php 
                                foreach ($almacenes as $key => $value) {
                                    echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row-form">
					<div class="span2"></div>
                    <div class="span10">
						<span class="span4">Selecionar todos los productos: <input type="checkbox" name="todos" onclick="todos()" id="todos" value="todos"></span>
						<span id="desc" style='display:none;'>
						   <span class="span1"><input type="text" value="0" placeholder="" id="descuento" name="descuento"></span><span class="span1"> %</span>
					    </span>
					</div>
            	</div>
			</div>
	    </div>
	</div> 
 </div>

 <div class="row-fluid">
 	<div class="span7">
 		 <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">
            <thead>

                <tr>

                    <th width="11%"><?php echo custom_lang('nnnnnnn', "Codigo");?></th>
                    <th width="35%"><?php echo custom_lang('nnnnnnn', "Producto");?></th>
                    <th width="20%"><?php echo custom_lang('nnnnnnn', "Precio");?></th>
                    <th width="13%"><?php echo custom_lang('nnnnnnn', "Desc %");?></th>
                    <th width="20%"><?php echo custom_lang('sima_description', "Nuevo precio");?></th>

                    <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>

                </tr>

            </thead>

            <tbody id="detalle">

                <tr>

                    <td> 
                        <input type="hidden" name="id_producto" class="product_id"  id="id_producto" value="id_producto"/>
                        <input type="hidden" name="impuesto" class="impuesto"  id="impuesto" value=""/>
                        <input type="text" name="cod-product" id="cod-product" value="0000" disabled/>

                    </td>


                    <td>
                    	<div class="input-prepend input-append">
						  <input type="text" placeholder="Nombre" name="product" id="product"/>     
						  <span class="add-on"><i class="icon-search icon-white"></i></span>
						</div>
						<ul id="products-list"> </ul>
                       <!--  
                        <span id='product-error'></span> -->

                    </td>


                    <td>
                    	<input type="text" name="price" style="text-align:right" id="price" class="psi" value="0" disabled/>

                        <span id='precio-error'></span>

                    </td>


                    <td>
                    	<input type="text" name="descuentocampo" style="text-align:right" id="descuentocampo" class="descuentocampo" value="0" />

                        <span id='precio-error'></span>

                    </td>
					
                    <td>
                    	<input class="dataMoneda" type="text" name="new-price" style="text-align:right" id="new-price" class="psi" value="0" disabled/>

                        <span id='new-price-error'></span>

                    </td>

                    <td><button style="border: 0;" type='button'  class='button btn-success add' onclick="addDetail()"><div class='icon'><span class='ico-plus'></span></div></button></td>

             

                </tr>

            </tbody>

            <tfoot>

                <tr>

                    <th colspan="2"></th>

                    <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA");?></b></th>

                    <th style="text-align:right"><b id="total-sin-iva" class="total_siva">0.00</b></th>

                    <th colspan="5"></th>

               

                </tr>

                <tr>

                    <th colspan="2"></th>

                    <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA");?></b></th>

                    <th style="text-align:right"><b id="total-iva" class="iva">0.00</b></th>

                    <th colspan="5"></th>

                  

                </tr>

                <tr>

                    <th colspan="2"></th>

                    <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA");?></b></th>

                    <th style="text-align:right"><b id="total-con-iva" class="total_civa">0.00</b>

                        <?php echo form_error('input_total_civa'); ?>

                    </th>

                    <th colspan="5"></th>

                    

                </tr>

            <tfoot>

        </table>
 	</div>
 	<div class="span5">
 		<div id="detail-list">
 			<table class="table aTable" id="table-preview" cellpadding="0" cellspacing="0" width="100%">
 				<thead>
 
                    <th width="15%"><?php echo custom_lang('nnnnnnn', "Codigo");?></th>
                    <th width="35%"><?php echo custom_lang('nnnnnnn', "Producto");?></th>
                    <th width="20%"><?php echo custom_lang('nnnnnnn', "Precio venta");?></th>
                    <th width="20%"><?php echo custom_lang('sima_description', "Nuevo precio");?></th>

                    <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>
 				</thead>
 				<tbody id="tbody-preview">

 				</tbody>
 			</table>
 		</div>	
 	</div>	
 </div>
 
         <div class="block">
		    <div class="span12">
	            <div id="alert-nueva-lista" class="alert alert-danger" style="display:none">    
	            </div>
	        </div>
	    </div>
 <div class="row-fluid">
  		<div class="toolbar bottom tar">
            <div class="pull-right">
				<button class="btn btn-default"   onclick="javascript:location.href='productos/index'" >Cancelar</button>
                <button class="btn btn-success" id="envio" onclick="nueva_lista()">Guardar</button>
            </div>
        </div>
</div>

<style type="text/css">

ul#products-list{
	list-style: none;
	margin-left: 10px;
	position: absolute;
	width: 300px;
	background: white;
	-webkit-border-radius: 10px;
    -moz-border-radius: 10px;
     border-radius: 10px;
     border-bottom: 1px solid #E9E9E9;
}

ul#products-list li div{
	padding-left: 10px;
	padding-top: 7px;
	padding-right: 10px;
	padding-bottom: 7px;
	border-bottom: 1px solid #E9E9E9;
}

ul#products-list li div:hover{
	background: #F9F9F9;
}

ul#products-list li  div span#precio-venta-autocomplete{
	float: right;
}
</style>


 <script type="text/javascript">
        $(document).ready(function(){
	    $( "#fecha-inicio" ).datepicker({dateFormat: 'yy/mm/dd'});
	    $( "#fecha-fin" ).datepicker({dateFormat: 'yy/mm/dd'});
	});

	var lista = {};
	var products = {}; 
	var detail = {}; 
	var detail_list = [];

	/*Busca y filtra productos por nombre*/
	$( "#product" ).keyup(function() {
	    console.log(detail);
	    var filter = $(this).val();
	       
	    $.ajax({
            type: "GET",
            url: "productos_libro_precios_filter?filter="+filter
            }).done(function(response) {
                //console.log("!!!1");
                //console.log(response);
                products = response;
                //console.log(response);
                $('#products-list').html("");

                for (var i = 0; i < response.data.length; i++) {
                	$('#products-list').append('<li id="'+response.data[i].id+'">'+
                            '<div onclick="setProduct('+(i)+')">'+
                               '<h5>'+response.data[i].nombre+'</h5>'+
                               '<span id="precio-venta-autocomplete">Venta: '+response.data[i].precio_venta+'</span>'+
                               '<span id="precio-compra-autocomplete">Compra: '+response.data[i].precio_compra+'</span>'+
                            '</div>'+
                        '</li>');
                    //console.log(response.data[i].impuesto);
                };           
            });
	});

	/*Selecciona producto y crea detalle temporal*/
	function setProduct(index){
                detail = products.data[index];
                //console.log(products.data[index].impuesto);
                //console.log(detail);
		$('#products-list').html(""); 
		$("#cod-product").val(detail.codigo);
		$("#product").val(detail.nombre);
		$("#price").val(detail.precio_venta);
		$("#new-price").prop('disabled', false);
                $("#impuesto").val(products.data[index].impuesto);
		detail.impuesto = products.data[index].producto_id;
	}

	/*Calcula precio nuevo*/
	$('#new-price, #descuentocampo').keyup(function() {
            if(parseFloat($('#descuentocampo').val()) > 0){
		desc = limpiarCampo(parseFloat($('#price').val() - ( parseFloat($('#price').val()) * parseFloat($('#descuentocampo').val()) ) / 100));
		$('#new-price').val(desc);
                console.log(desc);
            }
	
	    detail.nuevo_total_sin_iva =  limpiarCampo($('#new-price').val());
	    detail.nuevo_total_iva = limpiarCampo((detail.nuevo_total_sin_iva * $("#impuesto").val() ) / 100 );
	    detail.nuevo_total_con_iva = limpiarCampo( detail.nuevo_total_sin_iva + ((detail.nuevo_total_sin_iva * $("#impuesto").val()) / 100 ));	
            //console.log(detail.nuevo_total_sin_iva+"--"+ detail.nuevo_total_iva+"--"+detail.nuevo_total_con_iva);
	    $('#total-sin-iva').html(formatDollar(detail.nuevo_total_sin_iva));
	    $('#total-iva').html(formatDollar(detail.nuevo_total_iva));
	    $('#total-con-iva').html(formatDollar(detail.nuevo_total_con_iva));

	});

    
    function addDetail(){

		if($("#new-price").val()>0 && $('#todos').prop('checked') == false ){
			$('#alert-nueva-lista').css('display','none');
			detail_list.push(detail);
			reset();
			preview();
		}else{
			showError('El nuevo precio debe ser mayor a cero');
		}
		
	}

	function removeDetail(index){
		detail_list.splice(index, 1);
		preview();
	}


	function preview(){

		$('#tbody-preview').html("");

		for (var i = 0; i < detail_list.length; i++) {
			$('#tbody-preview').append('<tr id="'+detail_list[i].id+'">'+             								
                                '<td>'+detail_list[i].codigo+'</td>'+
                                '<td>'+detail_list[i].nombre+'</td>'+
                                '<td>'+formatDollar(detail_list[i].precio_venta)+'</td>'+
                                '<td>'+formatDollar(detail_list[i].nuevo_total_sin_iva)+'</td>'+
                                '<td><a href="javascript:void(0)" onclick="removeDetail('+(i)+')" class="button red"><div class="icon"><span class="ico-remove"></span></div></a></td>'+
                        '</tr>');
		};
			           
	}

	function todos(){
	
     if($('#todos').prop('checked') == true){
		
 	document.getElementById('desc').style.display = 'block';
 
    for (var i = 0; i < 1; i++) {
			$('#tbody-preview').append('<tr id="">'+             								
	                					'<td> todos</td>'+
	                					'<td> los productos</td>'+
	                					'<td> - </td>'+
	                					'<td> - </td>'+
	                					'<td> - </td>'+
	                				'</tr>');
	     }
									
		}
		else {       $('#tbody-preview').html(""); $('#descuento').val("0");  document.getElementById('desc').style.display = 'none';    }
			           
	}
	
	function reset(){

		detail = {}; 
		$('#products-list').html(""); 
		$("#cod-product").val('0000');
		$("#product").val('');
		$("#price").val('0.00');
		$("#price").prop('disabled', true);
		$("#new-price").val('0.00');
		$("#descuentocampo").val('0');
		$("#new-price").prop('disabled', true);

	}

	/*Crea una nueva lista*/
	function nueva_lista(){

		lista.inicio = $( "#fecha-inicio" ).val();
		lista.termina = $( "#fecha-fin" ).val();
		lista.todos = $('#todos').prop('checked');

		if(lista.inicio != lista.termina){

			lista.nombre = document.getElementById('nombre').value;

			if(lista.nombre!=''){

				lista.grupo = getSelectedValue(document.getElementById('seleccionar-grupo'));

				if(lista.grupo!=''){

					lista.almacen = getSelectedValue(document.getElementById('seleccionar-almacen'));

					if(lista.almacen!=''){

						if(detail_list.length > 0 || $('#todos').prop('checked') == true){
                            
							if($('#todos').prop('checked') != true){
								lista.detail_list = detail_list;
                            }
							
							document.getElementById("envio").disabled = true;
							
							$.ajax({

							    url: "<?php echo site_url("lista_precios/crear")?>",
                                dataType: "json",
								type: "POST",
								data: {
                                   nombre: document.getElementById('nombre').value,
                                   inicio: $( "#fecha-inicio" ).val(),
                                   termina : $( "#fecha-fin" ).val(),
                                   todos: $('#todos').prop('checked'),
                                   descuento: $( "#descuento" ).val(),
                                   grupo: getSelectedValue(document.getElementById('seleccionar-grupo')),
                                   almacen: getSelectedValue(document.getElementById('seleccionar-almacen')),
                                   detail_list: detail_list
                              	},
							    success: function(data) {
							    	if(data.done >= 1){
									/*
							    		$('#nombre').val('');
							    		document.getElementById('seleccionar-grupo').selectedIndex = 0;
							    		document.getElementById('seleccionar-grupo').selectedIndex = 0;
							    		reset();
							    		$('#tbody-preview').html('');
							    		$('#alert-nueva-lista').removeClass( "alert-danger" ).addClass( "alert-success" );
							    		$('#alert-nueva-lista').html('<span>Lista de precios creada con exito<span>');
							    		$('#alert-nueva-lista').css('display','block');
										
									*/	
                                   alert("la lista de precios "+lista.nombre+" fue creada correctamente");

							    		 location.href = "<?php echo site_url("productos/ver_listas");?>";
							    			
							    	}else{
							    		showError('Una lista ya existe con este nombre ');
							    	}

							    }

							});

						}else{
							showError('Debe agregar almenos un detalle de lista  ');
						}
						
					}else{
						showError('Por favor seleccione un almacén');
					}	
				}else{
					showError('Por favor seleccione un grupo');
				}	        
			}else{
					showError('Por favor escriba el nombre de la lista');
			}
		}else{
			showError('La fecha de inicio y fin no pueden ser iguales');
		}
  
	}

	function showError(error){
		$('#alert-nueva-lista').removeClass( "alert-success" ).addClass( "alert-danger" );
		$('#alert-nueva-lista').html(error);
		$('#alert-nueva-lista').css('display','block');
	}

	function getSelectedValue(element){
		if(element.selectedIndex!=0){
			var x = element.selectedIndex;
	        var y = element.options;
	        return y[x].value;
		}else{
			return '';
		}	
	}

 </script>
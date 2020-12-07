<style type="text/css">
.head{
	 cursor:pointer; cursor: hand
}
</style>


<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_price_book', "Libro de precios");?></h2>                                          
    </div>
</div>



<div class="row-fluid">
	<div class="span4">
		<div class="block">
			<a href="libro_de_precios" style="float: left;margin-right: 10px;padding-bottom: 7px;padding-top: 7px;" class="btn btn-green"><p style="margin-bottom: 0px;"><?php echo custom_lang('sima_new_price_list', "Nueva lista");?></p></a>
			<a href="ver_listas" style="float: left;margin-right: 10px;padding-bottom: 7px;padding-top: 7px;" class="btn btn-green"><p style="margin-bottom: 0px;"><?php echo custom_lang('sima_new_price_list', "listas de precios");?></p></a>		   
		</div>
	</div>
</div>

 <div class="row-fluid">

 	<div class="span12">
		<div class="block">
	 		

			<div id="form">

				<!--Seleccionar fecha -->

			    <div class="row-form">
		            <div class="span1"> 
						<label>Inicio</label>
		            </div>
		            <div class="span5">    
                	   <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha-inicio" id="fecha-inicio"/>
                	   <?php echo form_error('fecha'); ?>
                	</div>
                	<div class="span1"> 
						<label>Fin</label>
		            </div>
                	<div class="span5">   
                	   <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha-fin" id="fecha-fin"/>
                       <?php echo form_error('fecha'); ?>
                	</div>  
                </div>
                <!-- .......................................................... -->

		      	<div class="row-form">
		            <div class="span2">Nombre de la lista:</div>
		            <div class="span10"><input type="text" value="" placeholder="" id="nombre" name="nombre"></div>
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


		       
			</div>
	    </div>

		
	</div> 
	
 </div>

 <div class="row-fluid">
 	<div class="span7">
 		 <table class="table aTable" id="table-factura" cellpadding="0" cellspacing="0" width="100%">
            <thead>

                <tr>

                    <th width="5"></th>

                    <th width="15%"><?php echo custom_lang('nnnnnnn', "Codigo");?></th>
                    <th width="35%"><?php echo custom_lang('nnnnnnn', "Producto");?></th>
                    <th width="20%"><?php echo custom_lang('nnnnnnn', "Precio");?></th>
                    <th width="20%"><?php echo custom_lang('sima_description', "Nuevo precio");?></th>

                    <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>

                </tr>

            </thead>

            <tbody id="detalle">

                <tr>

                    <td width="10">

                        <input type="hidden" name="id_producto" class="product_id"  id="id_producto" value="id_producto"/>

                    </td>

                    <td>

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
                    	<input type="text" name="price" style="text-align:right" id="price" class="psi" value="0.00" disabled/>

                        <span id='precio-error'></span>

                    </td>

                    <td>
                    	<input type="text" name="new-price" style="text-align:right" id="new-price" class="psi" value="0.00" disabled/>

                        <span id='new-price-error'></span>

                    </td>

                    <td><button style="border: 0;" type='button'  class='button blue add' onclick="addDetail()"><div class='icon'><span class='ico-plus'></span></div></button></td>

             

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
                    <th width="20%"><?php echo custom_lang('nnnnnnn', "Precio");?></th>
                    <th width="20%"><?php echo custom_lang('sima_description', "Nuevo precio");?></th>

                    <th class="TAC" style="text-align:center"><?php echo custom_lang('sima_option', "Opcion");?></th>
 				</thead>
 				<tbody id="tbody-preview">

 				</tbody>
 			</table>
 		</div>	
 	</div>	
 </div>
 <div class="row-fluid">
  		<div class="toolbar bottom tar">

            <div class="btn-group">

                <button class="btn" onclick="nueva_lista()">Enviar</button>

                <button class="btn btn-warning" >Cancelar</button>

            </div>

        </div>

        <div class="block">
		    <div class="span12">
	            <div id="alert-nueva-lista" class="alert alert-danger" style="display:none">    
	                <?php  
	                    if(!empty($message)){
	                        echo $message;
	                    }
	                ?>
	            </div>
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
	    
	    var filter = $(this).val();
	       
	    $.ajax({
            type: "GET",
            url: "productos_libro_precios_filter?filter="+filter
            }).done(function( response ) {
                products = response;
                $('#products-list').html("");

                for (var i = 0; i < response.data.length; i++) {
                	$('#products-list').append('<li id="'+response.data[i].id+'">'+
                								'<div onclick="setProduct('+(i)+')">'+
                								   '<h5>'+response.data[i].nombre+'</h5>'+
                								   '<span id="precio-venta-autocomplete">Venta: '+response.data[i].precio_venta+'</span>'+
                								   '<span id="precio-compra-autocomplete">Compra: '+response.data[i].precio_compra+'</span>'+
                								'</div>'+  
                						   '</li>');
                };
                	           
            });
       
	});

	/*Selecciona producto y crea detalle temporal*/
	function setProduct(index){

		detail = products.data[index];
		$('#products-list').html(""); 
		$("#cod-product").val(detail.codigo);
		$("#product").val(detail.nombre);
		$("#price").val(detail.precio_venta);
		$("#new-price").prop('disabled', false);
		detail.impuesto = products.data[index].producto_id;

	}

	/*Calcula precio nuevo*/
	$('#new-price').keyup(function() {

	    detail.nuevo_total_sin_iva =  parseInt($(this).val());
	    detail.nuevo_total_iva = ((detail.nuevo_total_sin_iva * 16) / 100 );
	    detail.nuevo_total_con_iva = ( detail.nuevo_total_sin_iva + ((detail.nuevo_total_sin_iva * 16) / 100 ));

	    $('#total-sin-iva').html(detail.nuevo_total_sin_iva);
	    $('#total-iva').html(detail.nuevo_total_iva);
	    $('#total-con-iva').html(detail.nuevo_total_con_iva);

	});

	function addDetail(){

		if($("#new-price").val()>0){
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
	                					'<td>'+detail_list[i].precio_venta+'</td>'+
	                					'<td>'+detail_list[i].nuevo_total_sin_iva+'</td>'+
	                					'<td><a href="javascript:void(0)" onclick="removeDetail('+(i)+')" class="button red"><div class="icon"><span class="ico-remove"></span></div></a></td>'+
	                				'</tr>');
		};
			           
	}

	
	function reset(){

		detail = {}; 
		$('#products-list').html(""); 
		$("#cod-product").val('0000');
		$("#product").val('');
		$("#price").val('0.00');
		$("#price").prop('disabled', true);
		$("#new-price").val('0.00');
		$("#new-price").prop('disabled', true);

	}

	/*Crea una nueva lista*/
	function nueva_lista(){

		lista.inicio = $( "#fecha-inicio" ).val();
		lista.termina = $( "#fecha-fin" ).val();

		if(lista.inicio != lista.termina){

			lista.nombre = document.getElementById('nombre').value;

			if(lista.nombre!=''){

				lista.grupo = getSelectedValue(document.getElementById('seleccionar-grupo'));

				if(lista.grupo!=''){

					lista.almacen = getSelectedValue(document.getElementById('seleccionar-almacen'));

					if(lista.almacen!=''){

						if(detail_list.length > 0 ){

							lista.detail_list = detail_list;

							$.ajax({

							    url: "<?php echo site_url("lista_precios/crear")?>",
								data: lista,
								type: "POST",

							    success: function(response) {

							    	if(response.done == true){
							    		$('#nombre').val('');
							    		document.getElementById('seleccionar-grupo').selectedIndex = 0;
							    		document.getElementById('seleccionar-grupo').selectedIndex = 0;
							    		reset();
							    		$('#tbody-preview').html('');
							    		$('#alert-nueva-lista').removeClass( "alert-danger" ).addClass( "alert-success" );
							    		$('#alert-nueva-lista').html('<span>Lista de precios creada con exito<span>');
							    		$('#alert-nueva-lista').css('display','block');
							    		
							    			
							    	}else{
							    		showError('Una lista ya existe con este nombre ');
							    	}

							    }

							});

						}else{
							showError('Debe agregar almenos un detalle de lista');
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
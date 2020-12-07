<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<style type="text/css">
.head{
	 cursor:pointer; cursor: hand
}
</style>
            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

		
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
 	<div class="block">
		<div class="span12">Para ver detalles seleccione una lista </div>
	</div>
    <br>
    <!--Seleccionar lista -->
	<div class="block">
		<div class="span1">Lista: </div>
		<div class="span6">
			<select id="lista-detalle-precios">
    				<?php 
    					foreach ($lista_precios as $p_key => $p_value) {
	                        echo "<option value='".$p_value->id."'>Lista de precios: ".$p_value->nombre." - Pertenece al grupo: ".$p_value->nom_group."</option>"; 
	                    }
    				 ?>  
			</select>
		</div>
		<div class="span5">
			<button id="eliminar" class="btn btn-danger pull-right"><span class="ico-remove"></span> Borrar lista</button>
		</div>
	</div>
	<br>
	<div class="block">
		<div class="span12">
			<div id='alert-nueva-detalle-lista' class='alert'></div>
			<table class="table aTable dataTable">
				<tbody id='tb-lista-detalle-eliminar'>

				</tbody>
			</table>
			
			<table id="main_table" class="table aTable dataTable">
				<thead>
					<tr>
						<th width="10%">Codigo</th>
						<th width="50%">Producto</th>
						<th width="15%" align="right">V.Especial</th>
						<th width="15%" align="right">V.Especial + iva</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
				<tfoot>
					<tr>
						<th>Codigo</th>
						<th>Producto</th>
						<th>V.Especial</th>
						<th>V.Especial + iva</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
 </div>
 
<!-- Modal -->
<div id="nuevo-precio-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Nuevo precio especial</h3>
  </div>
			<table class="table aTable dataTable">
				<tr>
					<th>  
   Precio venta: <input name='precio_venta' id="precio_venta">
                   </th>
				 </tr>
				<tr> 
					<th>  
   Descuento: <input name='descuento' id="descuento" value="0"> %
                   </th>
			  </tr>
				<tr>
					<th>  
   Nuevo precio especial: <input name='new-price-special' id="new-price-special">
                   </th>
			  </tr>
          </table>
  
  <div class="modal-footer">

    <button class="btn btn-primary" onclick="changePrice()">Guardar</button>  
  
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
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

td.action{
	width: 30px;
}

#main_table tbody tr td:nth-child(3),
#main_table tbody tr td:nth-child(4)
{
	text-align: right;
}

#alert-nueva-detalle-lista{
	display: none;
}
</style>


<script type="text/javascript">

var lista = {};
var MAINTABLE;
var products = {}; 
var detail = {}; 
var detail_list = [];

var new_price_product = {
	list_id:0,
	id_product:0,
	new_price:0
}

function showError(error){
	$('#alert-nueva-lista').removeClass( "alert-success" ).addClass( "alert-danger" );
	$('#alert-nueva-lista').html(error);
	$('#alert-nueva-lista').css('display','block');
}

/*Calcula precio nuevo*/
$('#descuento').keyup(function() {
	if(parseInt($('#descuento').val()) > 0){
	  desc = (parseInt($('#precio_venta').val() - ( parseInt($('#precio_venta').val()) * parseInt($('#descuento').val()) ) / 100));
	  $('#new-price-special').val(desc);
	}

});

function changePrice(){
	var new_price = $('[name="new-price-special"]').val();
	new_price_product.new_price = new_price;
	$('[name="new-price-special"]').val('');
	$('#nuevo-precio-modal').modal('hide');
	$('#descuento').val('0');
	
	$.ajax({
    	url: "<?php echo site_url("lista_detalle_precios/editar_precio_especial")?>",
        data: {
    		list_id:new_price_product.list_id,
    		product_id:new_price_product.id_product,
    		new_price:new_price_product.new_price
    	},
        type: "POST",
        success: function(response)
        {
            if(response.done)
            {
				MAINTABLE.fnDraw();
            }
        }   
    });
}

function showChangePrice(id_product, producto_id_real){
	new_price_product.id_product = id_product;

	$.ajax({
        url: "<?php echo site_url("lista_detalle_precios/get_precio_venta")?>",
        data: {  id_product: producto_id_real  },
        type: "POST",
        success: function(response) { 
        	$('#precio_venta').val(response.precio_venta); 
        }
    });	
	
	$('#nuevo-precio-modal').modal('show');
}

function deleteListcomplete(item_id){
	var r = confirm("Esta seguro que desea eliminar esta lista de precios?");
	if (r == true) {
		$.ajax({
            url: "<?php echo site_url("lista_detalle_precios/eliminar_lista_precios")?>/"+item_id,
            data: { list_id: item_id },
            type: "POST",
            success: function(response) {	
                alert("la lista de precios fue eliminada correctamente");
				location.href = "<?php echo site_url("productos/ver_listas");?>";		    		
			}
    	});
    } 
}					

function deleteItemList(item_id){
	$.ajax({
        url: "<?php echo site_url("lista_detalle_precios/eliminar_item")?>/"+item_id,
        data: {list_id:new_price_product.list_id},
        type: "POST",
        success: function(response) {
        	MAINTABLE.fnDraw();
        }
    });
}

$(function(){

	var main_table = $('#main_table').dataTable({
		"aaSorting": [[ 0, "desc" ]],
		"bProcessing": false,
		"bServerSide": true,
		"sAjaxSource": "<?php echo site_url('productos/getLibroData');?>",
		"fnServerParams": function ( aoData ) {
			var id = $('#lista-detalle-precios').val();
	    	aoData.push( { "name": "libro", "value": id } );
	    },
		"sPaginationType": "full_numbers",
		"iDisplayLength": 10,
		"aLengthMenu": [5,10,25,50,100],
		"aoColumnDefs" : [
			{
				"bSortable": false,
				"aTargets": [ 4 ],
				"bSearchable": false,
				"mRender": function (data, type, row) {
					var ids = data.split(',');
					var buttons = "";

					buttons += '<a href="javascript:void(0)" onclick="showChangePrice('+ids[0]+', '+ids[1]+')" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>'+
							   '<a href="javascript:void(0)" onclick="deleteItemList('+ids[0]+')" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';
					
					return buttons;
				} 
			}
		]
	});

	$('#lista-detalle-precios').on('change', function(e){
		new_price_product.list_id = $(this).val();
		main_table.fnDraw();
	});

	$('#eliminar').on('click', function(e){
		deleteListcomplete($('#lista-detalle-precios').val());
	});

	new_price_product.list_id = $('#lista-detalle-precios').val();
	MAINTABLE = main_table;
});
 </script>
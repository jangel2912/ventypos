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
 	<div class="block">
		<div class="span12">Para ver detalles seleccione una lista </div>
	</div>
    <br>
    <!--Seleccionar lista -->
	<div class="block">
		<div class="span1">Lista: </div>
		<div class="span6">
			<select id="lista-detalle-precios" onclick="getListaDetallePrecios(this)">
				 <option>Seleccionar..</option>
    				<?php 
    					foreach ($lista_precios as $p_key => $p_value) {
	                        echo "<option value='".$p_value->id."'>".$p_value->nombre."</option>"; 
	                    }
    				 ?>  
			</select>
		</div>
	</div>
	<br>
	<div class="block">
		<div class="span12">
			<table class="table aTable dataTable">
				<thead>
					<th>Codigo</th>
					<th>Producto</th>
					<th>V.Especial</th>
					<th>V.Especial + iva</th>
				</thead>
				<tbody id='tb-lista-detalle'>
					<tr><td></td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td></td></tr>
				</tbody>
			</table>
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

 	var lista = {};



	var products = {}; 
	var detail = {}; 
	var detail_list = [];



	
	function showError(error){
		$('#alert-nueva-lista').removeClass( "alert-success" ).addClass( "alert-danger" );
		$('#alert-nueva-lista').html(error);
		$('#alert-nueva-lista').css('display','block');
	}

	/*Traer detalles de lista segun lista*/
	function getListaDetallePrecios(element){

		if(element.selectedIndex!=0){
			var x = element.selectedIndex;
	        var y = element.options;
	        //Asignar grupo a objeto asignar
	        lista_precios = y[x].value;

			$.ajax({

	            url: "<?php echo site_url("lista_detalle_precios/filtrar_por_lista")?>",

	            data: {lista:lista_precios},

	            type: "POST",

	            success: function(response) {

	            	if(response.done){

	            		$('#alert-nueva-detalle-lista').css('display','none');
	            		var tbody = document.getElementById('tb-lista-detalle');

	            		tbody.innerHTML='';
	            		for (var i = 0; i < response.data.length; i++) {
	            			
	            				tbody.innerHTML = tbody.innerHTML +
	            				'<tr>'+
	            				'<td>'+response.data[i].id+'</td>'+
	            				'<td>'+response.data[i].nombre+'</td>'+
	            				'<td>'+response.data[i].precio+'</td>'+
								'<td>'+(parseInt(response.data[i].precio)+((response.data[i].precio * response.data[i].impuesto) / 100 ))+'</td>'+
	            				'<tr>';

	            		};
	            		//0: {id:1, nombre:zapatillas, impuesto:16, id_impuesto:1, id_lista_precios:7, precio:100}
	            	}else{
	            		var tbody = document.getElementById('tb-lista-detalle').innerHTML='<tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr>';
	            		$('#alert-nueva-detalle-lista').html("No hay items en lista de precios");
	            		$('#alert-nueva-detalle-lista').css('display','block');
	            	}
	            	
	            }

	        });
		}
		
	}

	

 </script>
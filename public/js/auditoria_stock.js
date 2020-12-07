var tipo_busqueda = 'buscalo';







$(document).ready(function(){

        $("#f_auditoria").submit(function(e){

            e.preventDefault();

            enviar_formulario();



        });



        $("#btn_enviar").on('click',function(){

            $("#f_auditoria").submit();

        });

    });





$("#tipo-busqueda li").click(function() {



    $('#search-container').css("display", "block");

    $('#categorias').css("display", "none");

    $('#vitrina').css("display", "none");

    $('#cod-container').css("display", "none");

    $('#buscalo-controles').css("display", "none");

    $('#search').val('');

    $('#vitrina').html('');

    $('#facturasTable tbody').html('');

    busquedas = $(this).attr('id');



    switch ($(this).attr('id')) {

        case 'buscalo':

            tipo_busqueda = 'buscalo';

            $('#search').attr("placeholder", "Digite producto a buscar...");

            $('#search').focus();

            $('#codificalo').removeClass("active");

            $('#navegador').removeClass("active");

            $('#buscalo-controles').css("display", "block");

            $(this).addClass("active");

            break;



        case 'codificalo':

            tipo_busqueda = 'codificalo';

            $('#search').attr("placeholder", "Codigo de barra");

            $('#search').focus();

            $('#buscalo').removeClass("active");

            $('#navegador').removeClass("active");

            $(this).addClass("active");

            break;



        case 'navegador':

            tipo_busqueda = 'navegador';

            filtrarCategoria(document.getElementById('categorias'), 0);

            $('#categorias').css("display", "block");

            $('#vitrina').css("display", "block");

            $('#search-container').css("display", "none");

            $('#buscalo').removeClass("active");

            $('#codificalo').removeClass("active");

            $(this).addClass("active");

            break;

    }



});





$( "#t_search" ).autocomplete({

	  minLength: 1,

      source: function(request,response){

				$.ajax({

					url: url_busqueda,

					type: "post",

					data:{

						term:$("#t_search").val(),

						almacen:$("#s_almacen").val(),

						type: tipo_busqueda							

					},

					success:function(data){

						

						if(String(data) !== 'null'){



							if(data.constructor === Array){								

								response($.map(data,function(item){

									return {

										id: item.id,

										label: item.nombre,

										value: item.nombre,									

										nombre: item.nombre,

										codigo: item.codigo,

										codigo_barra: item.codigo_barra,										

										stock : item.unidades,

									}

							 	 }));	

							}else{															

								response([{

										id: data.id,

										label: data.nombre,

										value: data.nombre,									

										nombre: data.nombre,

										codigo: data.codigo,

										codigo_barra: data.codigo_barra,										

										stock : data.unidades,

								}]);

							}

							

						}else{

							alert('No se encuentra ningun producto, con el criterio buscado');

						}

					}

				});

			},

      select: function( event, ui ) {



 		armar_productos_seleccionados(ui.item);	

 		$("#t_search").val('');

 		$("#t_search").val("");

 		this.value = "";

 		$("#t_search").focus();

 		

 		$("#t_search").autocomplete( "search", "" );

        return false;

      },

      response: function (event,ui){

      	 if (ui.content.length == 1)

        {

        	ui.item = ui.content[0];

        	$(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);

        	$(this).autocomplete('close');	

      	}

      },

      	

    });  



function armar_productos_seleccionados(producto){

	//console.log(productos_seleccionados);

	var key = producto.id;
	
	if(productos_seleccionados[key] !== undefined){

		//console.log('existe');

		sumar = 1;

		cantidad_actual = $("#t_cantidad_contada_"+key).val();

		productos_seleccionados[key].cantidad_contada= Number(cantidad_actual) + Number(sumar);

		$("#tr_fila_"+key).hide();		

		$("#tr_fila_"+key).show('slow');

		$("#t_cantidad_contada_"+key).val(productos_seleccionados[key].cantidad_contada);

		$("#tr_fila_"+key+" td:nth-child(6)").html(calcular_diferencia_existencias(productos_seleccionados[key].cantidad_contada,producto.stock));

	}else{

		//console.log('nuevo');

		productos_seleccionados[key] = producto;

		productos_seleccionados[key].cantidad_contada = 1;



		var fila_html = '<tr id="tr_fila_'+key+'">';

		fila_html+="<td>"+producto.codigo +"</td>";

		fila_html+="<td>"+producto.nombre+"</td>";

		fila_html +='<td><input class="cantidad_contada" type="text" id="t_cantidad_contada_'+key+'" name="t_cantidad_contada_'+key+'" value="'+productos_seleccionados[key].cantidad_contada+'"></td>';

		fila_html+='<td><textarea id="ta_observacion_'+key+'"></textarea></td>';

		if(es_administrador){ 

			fila_html+="<td class='stock'>"+producto.stock+"</td>";

			fila_html +="<td class='diferencias'>"+calcular_diferencia_existencias(productos_seleccionados[key].cantidad_contada,producto.stock)+"</td>";

		 } 	

		fila_html += '<td><div class="icon" onclick="eliminar_fila_producto(this,' + key +')"><span style="font-size: large; color:#5ca745" class="ico-trash"></span></div></td>';

		fila_html+='</tr>';

		$("#tb_productos_auditoria tbody").append(fila_html);

		

	}



}



function eliminar_fila_producto(fila,key){

	productos_seleccionados[key] = undefined;

	$(fila).parent().parent().remove();

}



function calcular_diferencia_existencias(contado,stock){

    var diferencia = 0;

    if(Number(stock) < 0){

         diferencia = Number(stock) + Number(contado) ;

    }else{

        diferencia = Number(stock) - Number(contado) ;     

    }



	if(Number(contado) == Number(stock)){

		return '<span class="label label-success">'+diferencia+"</span>";

	}else{

		return '<span class="label label-warning">'+diferencia+'</span>';

	}

}



function enviar_formulario(){

	

	var productos_enviar = [];

	$.each(productos_seleccionados,function(key,value){

		if(value !== undefined){

			value.observacion_adicional= $("#ta_observacion_"+key).val(); 

            value.cantidad_contada = $("#t_cantidad_contada_"+key).val();

			productos_enviar.push(value);

		}

	});

	form_data = new FormData(document.getElementById("f_auditoria"));

	form_data.append('productos',JSON.stringify(productos_enviar));



	$.ajax({

        url: $("#f_auditoria").attr('action'),

        type: 'POST',

        dataType: 'json',

        data:  form_data,

        processData: false,

        contentType: false,

        beforeSend: function(){

                $("#div_mensajes").hide('fast');
				$("#div_cargando").css('display', 'block');
				$("#cancelar").prop("disabled", true);
                $("#div_progress .progress-bar").css('width','80%');
				$("#btn_enviar").attr('disabled','disabled');
				$("#cancelar").prop("disabled", true);

        },

        success: function(result){

            $("#div_mensajes").html('');
			$("#div_mensajes").removeClass();			
			$("#cancelar").prop("disabled", false);
			$("#div_cargando").css('display', 'none');
            $("#div_progress .progress-bar").css('width','100%');

            if(result.status){
                $("#div_mensajes").addClass('alert alert-success');
                $("#div_mensajes").html(result.error_message);
            }else{    
                $("#div_mensajes").addClass('alert alert-error');
                $("#div_mensajes").html(result.error_message);
                $("#btn_enviar").attr('disabled',false);
            }
            $("#div_mensajes").show('slow');
        }

    });

}


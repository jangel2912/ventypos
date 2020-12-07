tipo_busqueda = 'buscalo'; /*Tipo de busqueda default = 'buscalo' */



/*--------------------------------------------------

| Controlador tipo busqueda                         |

---------------------------------------------------*/



$("#tipo-busqueda li").click(function(){

    

    $('#search-container').css("display", "block");

    $('#categorias').css("display", "none");

    $('#vitrina').css("display", "none");

    $('#cod-container').css("display", "none");

    $('#buscalo-controles').css("display", "none");

    $('#search').val('');

    $('#vitrina').html('');

    $('#facturasTable tbody').html('');

    

    switch($(this).attr('id')){

        case 'buscalo':

            tipo_busqueda = 'buscalo';

            $('#search').attr("placeholder", "Digite producto a buscar...");

            $('#codificalo').removeClass( "active" );

            $('#navegador').removeClass( "active" );

            $('#buscalo-controles').css("display", "block");

            $(this).addClass( "active" );

        break;



        case 'codificalo':

            tipo_busqueda = 'codificalo';

            $('#search').attr("placeholder", "Codigo de barra");

            $('#buscalo').removeClass( "active" );

            $('#navegador').removeClass( "active" );

            $(this).addClass( "active" );

        break;



        case 'navegador':

            tipo_busqueda = 'navegador';

            filtrarCategoria(document.getElementById('categorias'));

            $('#categorias').css("display", "block");

            $('#vitrina').css("display", "block");

            $('#search-container').css("display", "none");

            $('#buscalo').removeClass( "active" );

            $('#codificalo').removeClass( "active" );

            $(this).addClass( "active" );

        break;

    }



});



var venta = {};



$(".cantidad").live('click', function(){

    cantidadField = $(this);

    propoverContent = "<div class='span9'><input type='text' class='spinner' name='cantidad_input' value='"+cantidadField.text()+"'/></div><button type='button' id='btn-accept-cantidad' class='btn btn-primary text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>";

    cantidadField.popover({

        placement: 'bottom'

        , title: 'Cantidad'

        , html: true

        , content: propoverContent

        , trigger: 'manual'

    }).popover('show');

    //cantidadField;

    

    $(".spinner").spinner({min: 0});

    $("#btn-accept-cantidad").click(function(){

        cantidadField.html($('.spinner').val());

        cantidadField.popover('destroy');

        calculate();

    });

    

    $("input[name='cantidad_input']").keyup(function(e){

        if(e.keyCode == 13) {

            cantidadField.html($('.spinner').val());

            cantidadField.popover('destroy');

            calculate();

        }

    });

});



function calculate(){



        iva = 0;subtotal = 0;total = 0;valporcen1=0;

        $(".title-detalle").each(function(index, item){



            precio = $(".precio-prod-real").eq(index).val();

                       

            impuesto = $(".impuesto-final").eq(index).val();

            cantidad = $(".cantidad").eq(index).text();

			porcentaje = $(".precio-porcentaje").eq(index).text();

           

           if(porcentaje > 1){

		      valporcen1 =  (precio  - ((porcentaje*precio) / 100));

			    subtotal += valporcen1 * cantidad;

				vimpuesto = valporcen1 * impuesto / 100 * cantidad;

				total += subtotal + vimpuesto;

		   }else{

			   subtotal += precio * cantidad;

			   vimpuesto = precio * impuesto / 100 * cantidad;

			   total += (precio * cantidad) + vimpuesto;

		   }

  

            iva += vimpuesto;

            $('.precio-calc').eq(index).html(formatDollar(precio * cantidad));

            $('.precio-calc-real').eq(index).html(precio * cantidad);

        });



        $('#total').val(total);

        $("#total-show").html(formatDollar(total));

        

        $("#iva-total").html(formatDollar(iva));

        $("#subtotal").html(formatDollar(subtotal));

    }

    

     function formatDollar(num) {

        num = parseInt(num);

        var p = num.toFixed(2).split(".");

        return p[0].split("").reverse().reduce(function(acc, num, i, orig) {

            return  num + (i && !(i % 3) ? "," : "") + acc;

        }, "") /*+ "." + p[1]*/;

    }



$(".precio-prod").live('click', function(){

    precioField = $(this);

    //alert($(".precio-prod").index($(this)));

    precioFieldReal = $('.precio-prod-real').eq($(".precio-prod").index($(this)));

    precioContent = "<form id='Calc'><table width='100%'><tr><td><input type='text' value='' name='Input' class='Input'/><input type='hidden' value='' name='Input1' class='Input1'/></td></tr>";

    precioContent += "<table  width='100%' ><tr></table><hr>";

    precioContent += "<td>";	

	precioContent += "&nbsp;<input type='button' class='btn one'    value='   1  ' name='one'/>";		

	precioContent += "&nbsp;&nbsp;<input type='button' class='btn two'    value='  2  ' name='two'/>";

    precioContent += "&nbsp;&nbsp;<input type='button' class='btn three'  value='  3  ' name='three'/>";	

	precioContent += "&nbsp;&nbsp;<input class='btn sum' type='button'    value='    +    ' name='sum'/>";

    precioContent += "</td>";	

    precioContent += "</tr>";	

	

    precioContent += "<tr>";

    precioContent += "<td>";	

	precioContent += "&nbsp;<input type='button' class='btn four'  value='   4  ' name='four'/>";		

	precioContent += "&nbsp;&nbsp;<input type='button' class='btn five'  value='  5  ' name='five'/>";

    precioContent += "&nbsp;&nbsp;<input type='button' class='btn six'   value='  6  ' name='six'/>";	

	precioContent += "&nbsp;&nbsp;<input class='btn rest' type='button'  value='    -     ' name='rest'/>";

    precioContent += "</td>";	

    precioContent += "</tr>";		

	

	

    precioContent += "<tr>";

    precioContent += "<td>";	

	precioContent += "&nbsp;<input type='button' class='btn seven' value='   7  ' name='seven'/>";	

	precioContent += "&nbsp;&nbsp;<input type='button' class='btn eith'  value='  8  '  name='eith'/>";

    precioContent += "&nbsp;&nbsp;<input class='btn nine' type='button'  value='  9  ' name='nine'/>";		

	precioContent += "&nbsp;&nbsp;<input class='btn porcen' type='button'  value='   %    ' name='divi'/>";

    precioContent += "</td>";	

    precioContent += "</tr>";		



	

    precioContent += "<tr>";

    precioContent += "<td>";

    precioContent += "&nbsp;<input type='button' class='btn clear' value='  C   ' name='clear'/>";	

	precioContent += "&nbsp;&nbsp;<input class='btn cero' type='button'  value='  0  ' name='cero'/>";

	precioContent += "&nbsp;&nbsp;<input class='btn doIt' type='button'  value='  =  ' name='doIt'/>";		

	precioContent += "&nbsp;&nbsp;<button type='button' id='btn-accept-precio' class='btn btn-primary'>&nbsp;Enter</span></button>";

    precioContent += "</td>";	

    precioContent += "</tr>";	

	

    precioContent += "</table></form>";

    precioField.popover({

        placement: 'bottom'

        , title: 'Precio'

        , html: true

        , content: precioContent

        , trigger: 'manual'

    }).popover('show');

    

    $("#btn-accept-precio").click(function(){



		if($('.Input').val() != ''){

		       var  valor = $('.Input').val();

		       var val1 = valor.replace("%", "");

			   var val2 = val1.replace(" ", "");	

			   var precio = precioField.text().replace(",", "");

			   

	          var primer = $('.Input').val();

		      var res1 = primer.replace(/[1234567890]/gi, "");		  

			  

			  if(res1 == ' % ' || $('.Input1').val() == 'porcentaje'){

				var resultado_porcen1 =  (parseInt(precio) * val2 / 100);

				var resultado_porcen2 =  (precio - resultado_porcen1);

				$('.Input').val(resultado_porcen2);

				$('.Input1').val("");

			  }

		      else{

                 $('.Input').val(eval($('.Input').val()));

			  }

		}else if($('.Input').val() == ''){

			var precio = precioField.text().replace(",", "");

			  $('.Input').val(precio);

		}



		if(isNaN( $('.Input').val() )){

			$('.Input').val('0');

		}

       /*

		var precio_compro = precioField.text().replace(",", "");

		if(parseInt($('.Input').val()) > parseInt(precio_compro)){

			

			  $('.Input').val('0');

		}

		*/





      precioField.html(formatDollar($('.Input').val()));

        precioFieldReal.val($('.Input').val());

        precioField.popover('destroy');

        calculate();

    });

    

    $('#Calc').submit(function(){

        precioField.html(formatDollar($('.Input').val()));

        precioFieldReal.val($('.Input').val());

        precioField.popover('destroy');

        calculate();

        return false;

    });



});



$(".delete").live("click",(function(){

        $(this).parent().parent().remove();

        if($("#facturasTable tbody tr").length == 0){

            $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");

        }

        calculate();

}));



$(".one, .two, .three, .sum, .four, .five, .six, .seven, .eith, .nine, .cero, .rest, .mult, .porcen, .doIt, .clear").live('click', function(){

    data = $(this).attr('class');

    switch (data.split(' ')[1]){

        case 'one':

                $('.Input').val($('.Input').val() + 1);

            break;

        case 'two':

                $('.Input').val($('.Input').val() + 2);

            break;

        case 'three':

                $('.Input').val($('.Input').val() + 3);

            break;

        case 'four':

                $('.Input').val($('.Input').val() + 4);

            break;

        case 'five':

                $('.Input').val($('.Input').val() + 5);

            break;

        case 'six':

                $('.Input').val($('.Input').val() + 6);

            break;

        case 'seven':

                $('.Input').val($('.Input').val() + 7);

            break;

        case 'eith':

                $('.Input').val($('.Input').val() + 8);

            break;

        case 'nine':

                $('.Input').val($('.Input').val() + 9);

            break;

        case 'cero':

                $('.Input').val($('.Input').val() + 0);

            break;

        case 'sum':

                 $('.Input').val($('.Input').val() + " + ");

            break;

        case 'rest':

                 $('.Input').val($('.Input').val() + " - ");

            break;

        case 'porcen':

                 $('.Input').val($('.Input').val() + " % ");

				 $('.Input1').val($('.Input1').val() + "porcentaje");

            break;

        case 'doIt':

		       var  valor = $('.Input').val();

		       var val1 = valor.replace("%", "");

			   var val2 = val1.replace(" ", "");	

			   var precio = precioField.text().replace(",", "");

			   

	          var primer = $('.Input').val();

		      var res1 = primer.replace(/[1234567890]/gi, "");		  

			  

			  if(res1 == ' % ' || $('.Input1').val() == 'porcentaje'){

				var resultado_porcen1 =  (parseInt(precio) * val2 / 100);

				var resultado_porcen2 =  (precio - resultado_porcen1);

				$('.Input').val(resultado_porcen2);

				$('.Input1').val("");

			  }

		      else{

                 $('.Input').val(eval($('.Input').val()));

			  }

            break;

        case 'clear':

               $('.Input').val('');

			   $('.Input1').val('');

            break;

    }

    $('.Input').focus();

});





$(".precio-porcentaje").live('click', function(){

    precioField1 = $(this);

    //alert($(".precio-prod").index($(this)));

    precioFieldReal1 = $('.precio-prod-precio-porcentaje').eq($(".precio-porcentaje").index($(this)));

    precioContent = "<form id='Calc'><table  width='100%'><tr colspan='4'><td><input type='text' value=' '  style='width: 200px; height: 31px;' name='Input' class='Input'/></td></tr>";

    precioContent += "<tr>";

    precioContent += "<td>";

    precioContent += "&nbsp;<input type='button' class='btn clear' value='           C           ' name='clear'/>";		

	precioContent += "&nbsp;&nbsp;<button type='button' id='btn-accept-precio' class='btn btn-primary'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></button>";

    precioContent += "</td>";	

    precioContent += "</tr>";	

	

    precioContent += "</table></form>";

    precioField1.popover({

        placement: 'bottom'

        , title: 'Ingrese el numero de porcentaje de descuento y dar click en enter'

        , html: true

        , content: precioContent

        , trigger: 'manual'

    }).popover('show');

    

    $("#btn-accept-precio").click(function(){

        precioField1.html(formatDollar($('.Input').val()));

        precioFieldReal1.val($('.Input').val());

        precioField1.popover('destroy');

        calculate();

    });

    

    $('#Calc').submit(function(){

        precioField1.html(formatDollar($('.Input').val()));

        precioFieldReal1.val($('.Input').val());

        precioField1.popover('destroy');

        calculate();

        return false;

    });



});



$(".delete").live("click",(function(){

        $(this).parent().parent().remove();

        if($("#facturasTable tbody tr").length == 0){

            $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");

        }

        calculate();

}));



$(".uno, .dos, .tres, .sumas, .cuatro, .cinco, .seis, .siete, .ocho, .nueve, .cero, .rest, .mult, .divi, .doIt, .clear").live('click', function(){

    data = $(this).attr('class');

    switch (data.split(' ')[1]){



        case 'clear':

                 $('.Input').val(' ');

            break;

    }

    $('.Input').focus();

});







var objData = null;

var limit = 8;

var offset = 0;

var pages = 0;



$(document).ready(function(){

          

        $("#buscar-cliente").keyup(function(e){

            if($( "#buscar-cliente" ).val()!='')

            $('#contenedor-lista-clientes').fadeIn('fast');

            else

            $('#contenedor-lista-clientes').fadeOut('fast');

        });

        

        $(".first, .previous, .next, .last").live('click', function(){

            if(!$(this).hasClass('paginate_button_disabled')){

                classValue = $(this).attr('class');

                switch (classValue.split(' ')[0]){

                    case 'next':

                            pages++;

                            offset += limit;

                            break;

                    case 'last':

                            offset = (getCountPages() - 1) * limit;

                            pages = getCountPages();

                            break;

                    case 'first':

                            offset = 0;

                            pages = 0;

                            break;

                    case 'previous':

                            offset -= limit;

                            pages--;

                            break;

                }

                 paintRows();

            }

        });





        $("#search").keyup(function(e){

            

            //alert(e.keyCode);

     

            if(tipo_busqueda == 'buscalo'){

                //alert(cliente_grupo);

                $('#cod-container').css('display','none');



                if(cliente_selecionado){

                   var cliente = cliente_selecionado; 

                   var grupo = cliente_grupo;

                }else{

                   var cliente = '';

                   var grupo = 0;

                }





                $.ajax({

                     url: $url

                    ,dataType: 'json'

                    ,type: 'post'

                    ,data: {filter: $(this).val(),type: 'buscalo',cliente:cliente, grupo: grupo}

                    ,success: function(data){

                        objData = data;

                        limit = 8;

                        offset = 0;

                        pages = 0;

                        paintRows();

                    }

                });

            }

            else if(tipo_busqueda == 'codificalo' && e.keyCode == 13){

                codificaloAddValue();

            }

            

        });



        

        





        function paintRows(){

            formatTable();

            paintInfo();

            paintPagin();

             for(i = offset; i < getCount(); i++){

                 if($("#facturasTable tbody tr").eq(0).hasClass("nothing")){

                     $('#facturasTable tbody').html(formatRows(objData[i]));

                 }

                 else{

                     $('#facturasTable tbody').append(formatRows(objData[i]));

                 }

            }

        }

        /*Count elements on table*/

        function getCount(){

            count = limit + offset;

            dataLength = objData.length;

            if(count > dataLength){

                count = dataLength;

            }

            return count;

        }

        

        function getCountPages(){

            count = Math.ceil(objData.length / limit);

            return count;

        }

        

        function formatRows(row){

            var imageName = "default.png";

            if(row.imagen != ""){

                imageName = row.imagen;

            }



            if(imageName=='')

            imageName = 'product-dummy.png';

          var precioimpuesto =   (parseInt(row.precio_venta) * parseInt(row.impuesto) / 100 +parseInt(row.precio_venta) );

            image = $urlImages+ "/" + imageName; 

            html = "<tr><td width='20%'><img src='"+image+"' class='grid-image'/></td>";

            html += "<td><p><span class='nombre_producto'>"+row.nombre+"</span>&nbsp;<input type='hidden' value='"+row.precio_venta+"' class='precio-real'/><span class='precio'>"+formatDollar(row.precio_venta)+"</span></p>";

            html += "<p><span class='stock'>Stock: "+row.stock_minimo+"</span>&nbsp;<input type='hidden' value='"+row.precio_compra+"' class='precio-compra-real'/><span class='precio-minimo'>C&oacute;digo de barra: "+row.codigo+"</span>  -  <span class='precio-minimo'>Precio + IVA: "+formatDollar(precioimpuesto) +"</span></p><input type='hidden' class='id_producto' value='"+row.id+"'/><input type='hidden' class='codigo' value='"+row.codigo+"'><input type='hidden' class='impuesto' value='"+row.impuesto+"'></td></tr>";

            return html;

        }

        

        function formatTable(){

            $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");

        }

        

        function paintInfo(){

            $(".dataTables_info").html("Mostrando desde "+ offset + " hasta " + getCount() + " de " + objData.length + " elementos");

        }

        /*Paginador*/

        function paintPagin(){

           $(".dataTables_paginate").html(

               getFirstPage() + getPrevPage() + getNextPage() + getLastPage()

            );

        }

        

        function getFirstPage(){ 

            if(offset > 0 ){

                return '<a class="first paginate_button">Primero</a>';

            }

            else {

                return '<a class="first paginate_button paginate_button_disabled">Primero</a>';

            }

        }

        

        function getPrevPage(){

             if(pages > 0 ){

                return '<a class="previous paginate_button" tabindex="0">Anterior</a>';

            }

            else {

                return '<a class="previous paginate_button paginate_button_disabled" tabindex="0">Anterior</a>';

            }

        }

        

        function getNextPage(){

            if((getCountPages() - 1) > pages){

                return '<a class="next paginate_button" tabindex="0">Pr&oacute;ximo</a>';

            }

            else {

                return '<a class="next paginate_button paginate_button_disabled" tabindex="0">Pr&oacute;ximo</a>';

            }

        }

        

        function getLastPage(){

            if(getCountPages() - 1 > pages ){

                return '<a class="last paginate_button" tabindex="0">&Uacute;timo</a>';

            }

            else {

                return '<a class="last paginate_button paginate_button_disabled" tabindex="0">&Uacute;timo</a>';

            }

        }

        

        $("#facturasTable tr").live('click', function(){

                if(!$("#facturasTable tr").eq(0).hasClass("nothing")){

                    

                    var id_producto = $('.id_producto').eq($(this).index()).val();

                    var matching = $('.product_id[value="'+id_producto+'"]').index();

                    if(matching == -1){

                        rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='"+$('.precio-compra-real').eq($(this).index()).val()+"'/><input type='hidden' value='"+$('.id_producto').eq($(this).index()).val()+"' class='product_id'/><input type='hidden' class='codigo-final' value='"+$('.codigo').eq($(this).index()).val()+"'><input type='hidden' class='impuesto-final' value='"+$('.impuesto').eq($(this).index()).val()+"'><span class='title-detalle text-info'><input type='hidden' value='"+$('.impuesto').eq($(this).index()).text()+"' class='detalles-impuesto'>"+$('.nombre_producto').eq($(this).index()).text()+"</span></td>";

                        rowHtml += "<td><span class='label label-success cantidad'>"+1+"</span></td>";

                        rowHtml += "<td><span class='label label-success precio-prod'>"+$('.precio').eq($(this).index()).text()+"</span><input type='hidden' class='precio-prod-real' value='"+$('.precio-real').eq($(this).index()).val()+"'/><input type='hidden' class='precio-prod-real-no-cambio' value='"+$('.precio-real').eq($(this).index()).val()+"'/></td>";

                        rowHtml += "<td><span class='precio-calc'>"+$('.precio').eq($(this).index()).text()+"</span><input type='hidden' value='precio-calc-real' value='"+$('.precio-real').eq($(this).index()).val()+"'/></td>";

                        rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='ico-remove'></span></div></a></td></td>";

                        rowHtml += "</tr>";



                        if($("#productos-detail tr").eq(0).hasClass("nothing")){

                                    $("#productos-detail").html(rowHtml);

                        }else{

                                $("#productos-detail").append(rowHtml);

                        }

                    }

                    else{

                        parent = $('.product_id[value="'+id_producto+'"]').parent().parent().index();

                        cantidad = parseInt($('.cantidad').eq(parent).text()) + 1;

                        $('.cantidad').eq(parent).text(cantidad);

                        //alert("Ya estaaaa");

                    }

                    

                    calculate();

                }

        });

        

        $('.vendedorHideButton').click(function () {

            $('#vendedorBlock').slideToggle( "slow" );

        });

        

        $('.clienteHideButton').click(function(){

            $('#clienteBlock').slideToggle( "slow" );

        });

        

         $('#clienteBlock, #vendedorBlock').slideUp();

        

        /*----------------------------------------------------

        | PAGAR VENTA                                        |

        ------------------------------------------------------*/

        $("#pagar").click(function(){

  

            $("#valor_pagar").val(formatDollar($("#total").val()));

			$("#valor_entregado").val(parseInt($("#total").val()));

            $("#valor_pagar_hidden").val($("#total").val());

			$("#sima_cambio").val(parseInt('0'));

            $( "#dialog-forma-pago-form" ).dialog( "open" ); 

           

        });

		

        $("#nota").click(function(){

  

            $( "#dialog-nota-form" ).dialog( "open" ); 

           

        });

				



        /*----------------------------------------------------

        | VENTA PENDIENTE                                     |

         ------------------------------------------------------*/



        $("#pendiente").click(function(){

  

            alert('venta pendiente');

          /*  $("#valor_pagar").val(formatDollar($("#total").val()));

            $("#valor_pagar_hidden").val($("#total").val());*/

            /*$( "#dialog-forma-pago-form" ).dialog( "open" ); */

           

        });

        

        $("#valor_entregado").keyup(function(e){

            $("#sima_cambio_hidden").val($(this).val() - $("#valor_pagar_hidden").val());

            $("#sima_cambio").val(formatDollar($(this).val() - $("#valor_pagar_hidden").val()));

            //$("#sima_cambio_hidden").val($("#valor_pagar_hidden").val() - $(this).val());

            

        });

        

        $("#dialog-forma-pago-form").dialog({

			autoOpen: false,

			//height: 400,

			width: 620,

			modal: true,

			buttons: {

				"Aceptar": function() {











                                        productos_list = new Array();

                                        $(".title-detalle").each(function(x){

                                            var descuento = 0;

																				

                                         // 

                                         descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

										 

											  if(parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())){

												  descuento = 0;

                                              }

                                         

                                            productos_list[x] = {

                                                'codigo': $('.codigo-final').eq(x).val() 

                                                , 'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val()

                                                , 'unidades': parseInt($(".cantidad").eq(x).text())

                                                , 'impuesto': $(".impuesto-final").eq(x).val()

                                                , 'nombre_producto': $(".title-detalle").eq(x).text()

                                                , 'product_id': $(".product_id").eq(x).val()

                                                , 'descuento': descuento

                                                , 'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

                                            }

                                            

                                            pago = {

                                                valor_entregado: $("#valor_entregado").val()

                                               ,cambio : $("#sima_cambio_hidden").val()

                                               ,forma_pago: $("#forma_pago").val()

                                            };

                                            

                                            

                                        });  

                                        $.ajax({

                                          url: $sendventas

                                          ,dataType: 'json'

                                          ,type: 'POST'

                                          ,data: {

                                               cliente: $("#id_cliente").val(),

                                               productos: productos_list

                                              ,vendedor : $("#vendedor").val()

                                              ,total_venta: $("#total").val()

                                              ,pago: pago

											  ,nota: $("#notas").val() 

                                          }

                                          ,error: function(jqXHR, textStatus, errorThrown ){

                                              alert(errorThrown);

                                          }

                                          ,success: function(data){

                                              if(data.success == true){





                                                  if(!confirm("Desea imprimir la factura de venta?")){

                                                      location.href = $reloadThis;

                                                  }

                                                  else {

                                                      //location.href = ;

                                                      $.fancybox.open({

                                                              'width' : '85%',

                                                              'height' : '85%',

                                                              'autoScale' : false,

                                                              'transitionIn' : 'none',

                                                              'transitionOut' : 'none',

                                                              href : $urlPrint +"/"+data.id,

                                                              type : 'iframe',

                                                              afterClose: function(){

                                                                  location.href = $reloadThis;

                                                              }

                                                              //padding : 5

                                                      });

                                                  }



                                                  $("#dialog-forma-pago-form").dialog( "close" ); 



                                              }

                                              else{

                                                  alert("Ha ocurrido un error venta no creada");

                                              }

                                          }

                                      });  

                                        

				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() {

                            $('#valor_pagar').val("");

                            $('#valor_entregado').val("");

                            $('#forma_pago').val("");

                            $('#sima_cambio').val("");

                            $("#sima_cambio_hidden").val("");

                            $("#valor_pagar_hidden").val("");

			}

		});

        

        

});



/*--------------------------------------------------

| Clientes                                         |

---------------------------------------------------*/

 var cliente_selecionado = {}

 var cliente_grupo = {}

        $("#datos_cliente").autocomplete({



			source: "../clientes/get_ajax_clientes",



			minLength: 1,



			select: function( event, ui ) {

			cliente_selecionado = ui.item.id;

			cliente_grupo = ui.item.grupo_clientes_id;

                $("#id_cliente").val(ui.item.id);

				$("#otros_datos").val(ui.item.descripcion);

				



			}



		});

/*

 var cliente_selecionado = {}



 var lista_clientes = {



    prototype:{},

    init:function(){

        var li = ""; 

        for (var i = 0; i < this.prototype.length; i++) {

            li = li + "<li value='"+(i)+"' onclick='lista_clientes.select(this)'>"+this.prototype[i].nombre_comercial+"</li>";

        };

        $('#lista-clientes').html(li);

    },

    select:function(element){



       $("#buscar-cliente").val('');

       cliente_selecionado = this.prototype[element.value];

       $('#nombre_comercial').html(cliente_selecionado.nombre_comercial);

       $('#razon_social').html(cliente_selecionado.razon_social);

       $('#nif_cif').html(cliente_selecionado.razonif_cifn_social);

       $('#contacto').html(cliente_selecionado.contacto);

       $('#email').html(cliente_selecionado.email);

       $('#contenedor-lista-clientes').fadeOut('fast');

       //$('#cliente-titulo').html(cliente_selecionado.nombre_comercial);

       //$('#grupo_clientes_id').html(cliente.grupo_clientes_id);



      // console.log(cliente_selecionado.id);

    }

 }

*/



 /*Buscar cliente*/

$("#datos_cliente").keyup(function(e){



    var filter = $(this).val();

    $('#lista-clientes').html('');

    $('#facturasTable tbody').html('');

    $('#cliente-titulo').html('');

    $('#search').val('');

    $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');

    $('#total-show').html('0.00');

    $('#subtotal').html('0.00');

    $('#iva-total').html('0.00');

 /*      

    if(filter!=''){

        $.ajax({

            type: "GET",

            url: "../clientes/get_clients_filter?filter="+filter

        }).done(function( response ) {

            console.log(response); 

            lista_clientes.prototype=response;

            lista_clientes.init();

        ;

        }); 

    }

*/

});



//Metodo REST API -> Estado inactivo

function getClient(element){

    

    if(element.selectedIndex!=0){



        $('#nombre_comercial').html(client[element.selectedIndex - 1].nombre_comercial);

        $('#razon_social').html(client[element.selectedIndex - 1].razon_social);

        $('#nif_cif').html(client[element.selectedIndex - 1].nif_cif);

        $('#contacto').html(client[element.selectedIndex - 1].contacto);

        $('#email').html(client[element.selectedIndex - 1].email);

        $('#grupo_clientes_id').html(client[element.selectedIndex - 1].grupo_clientes_id);



        $.ajax({



            url: $url,



            data:  {filter: '', cliente: client[element.selectedIndex - 1].id_cliente, grupo: client[element.selectedIndex - 1].grupo_clientes_id },



            type: "POST",



            success: function(response) {



             

                for (var i = 0; i < response.length; i++) {

                    $('#nombre-producto-'+response[i].id).text(response[i].nombre);

                    $('#stock_minimo-producto-'+response[i].id).text(response[i].stock_minimo);

                    $('#precio_venta-producto-'+response[i].id).text(response[i].precio_venta);

                    $('#precio-real-'+response[i].id).val(response[i].precio_venta);

                    

                };

               

            }



        });

    }





}





/*--------------------------------------------------

| CODIGO DE BARRAS                                 |

---------------------------------------------------*/

var sProduct = null;

//aqui revisar-------------------------------------------------------

$('#cod-container').click(function(){

   renderFactura();

});



function codificaloAddValue(){

    $.ajax({

            url: $url

           ,dataType: 'json'

           ,type: 'post'

           ,data: {filter: $("#search").val(), type: 'codificalo'}

           ,success: function(data){



               if(data != null){

				   sProduct = data;

				   

                   var precioimpuesto =   (parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto) / 100 +parseInt(sProduct.precio_venta) );

                    

                    /*Item*/

                    $('#cod-precio').html(sProduct.precio_venta+'$');

                    /*Descripcion*/

                    $('#cod-nombre').html(sProduct.nombre);

                    $('#cod').html(sProduct.codigo);

                    $('#cod-stock').html(sProduct.stock_minimo);

                    $('#cod-compra').html(sProduct.precio_compra);

                    $('#cod-img').attr("src", $urlImages+'/'+sProduct.imagen);

                    $('#cod-precio-impuesto').html(formatDollar(precioimpuesto));

                    $('#cod-container').fadeIn('fast');

					

					renderFactura();



               }

               else {

                   $('#cod-container').fadeOut('fast');

                   alert("Codigo de barras no encontrado");

               }

           }

    });

}



/*--------------------------------------------------

| VITRINA                                          |

---------------------------------------------------*/



var productos_categoria = null;



function filtrarCategoria(element){

   

    $.ajax({



        url: $urlVitrina+'/'+element.id,



        type: "GET",



        success: function(response) {



            productos_categoria = response;



            $('#vitrina').html('');



            for (var i = 0; i < response.length; i++) {



                console.log(response[i]);



                if(response[i].imagen=='')

                    response[i].imagen= 'product-dummy.png';



                $('#vitrina').append(

                   '<div class="vitrina-item" onclick="categoria_producto('+i+')">'+    

                        '<img src="'+$urlImages+'/'+response[i].imagen+'">'+

                        '<div id="pie-item">'+

                            '<div><h5 id="item-nombre">'+response[i].nombre+'</h5></div>'+

                            '<div><h5 id="item-precio">$'+response[i].precio_venta+'</h5></div>'+

                        '</div>'+

                    '</div>'



                );

            }

           

        }



    });

}



var offset = 0;



function siguiente_categorias(){



    offset+= 6;

    $.ajax({

      type: "GET",

      url: $urlCategorias+'/'+offset

    }).done(function( response ) {

        

        if(response.length!=0){

            html =  '<li id="0" onclick="filtrarCategoria(this)"><img src="'+$urlImages+'/todos.jpg"><br>Todos</li>';

            html += '<li id="2" onclick="filtrarCategoria(this)"><img src="http://localhost/Vendty/invoicex/uploads/general.jpg"><br>General</li>';

            for (var i = 0; i < (response.length); i++) {

               if(response[i].id!=2)

               html+= '<li id="'+response[i].id+'" onclick="filtrarCategoria(this)" ><img src="'+$urlImages+'/'+response[i].imagen+'"><br>'+response[i].nombre+'</li>';

            };

             $('#nav-categoria').html(html);

            

        }else{

             offset= -6;

             siguiente_categorias();

        }

       

    });



}



function categoria_producto(index){



    sProduct = productos_categoria[index];

    renderFactura();

   

}





/*--------------------------------------------------

| RENDERIZAR FACTURA                               |

---------------------------------------------------*/



function renderFactura(){



    var id_producto = sProduct.id;

    var matching = $('.product_id[value="'+id_producto+'"]').index();



    sProduct.stock_minimo-=1;

    $('#cod-stock').html(sProduct.stock_minimo);



    if(matching == -1){

        rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='"+sProduct.precio_venta+"'/><input type='hidden' value='"+id_producto+"' class='product_id'/><input type='hidden' class='codigo-final' value='"+sProduct.codigo+"'><input type='hidden' class='impuesto-final' value='"+sProduct.impuesto+"'><span class='title-detalle text-info'><input type='hidden' value='"+sProduct.impuesto+"' class='detalles-impuesto'>"+sProduct.nombre+"</span></td>";

        rowHtml += "<td><span class='label label-success cantidad'>"+1+"</span></td>";

        rowHtml += "<td><span class='label label-success precio-prod'>"+formatDollar(sProduct.precio_venta)+"</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='"+sProduct.precio_venta+"'/><input type='hidden' class='precio-prod-real-no-cambio' value='"+sProduct.precio_venta+"'/></td>";

        rowHtml += "<td><span class='precio-calc'>"+sProduct.precio_venta+"</span><input type='hidden' value='precio-calc-real' value='"+sProduct.precio_venta+"'/></td>";

        rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='ico-remove'></span></div></a></td></td>";

        rowHtml += "</tr>";



        if($("#productos-detail tr").eq(0).hasClass("nothing")){

                    $("#productos-detail").html(rowHtml);

        }else{

                $("#productos-detail").append(rowHtml);

        }

    }

    else{

        parent = $('.product_id[value="'+id_producto+'"]').parent().parent().index();

        cantidad = parseInt($('.cantidad').eq(parent).text()) + 1;

        $('.cantidad').eq(parent).text(cantidad);

    }



    calculate();

}


//2015-12-30





tipo_busqueda = $navegador; /*Tipo de busqueda default = 'buscalo' */

/*--------------------------------------------------

| Controlador tipo busqueda                         |

---------------------------------------------------*/



switch (tipo_busqueda) {

    case 'buscalo':

        tipo_busqueda = 'buscalo';

        $('#search').attr("placeholder", "Digite producto a buscar...");

        $('#search').focus();

        $('#codificalo').removeClass("active");

        $('#navegador').removeClass("active");

        $('#buscalo-controles').css("display", "block");

        $('#search2').hide();

        $('#search3').hide();

        $('#search4').hide();

        $('#search').show();

        

        $(this).addClass("active");

        break;



    case 'codificalo':

        tipo_busqueda = 'codificalo';

        $('#search').attr("placeholder", "Codigo de barra");

        $('#search').focus();

        $('#buscalo').removeClass("active");

        $('#navegador').removeClass("active");

        $('#buscalo-controles').css("display", "none");

        $(this).addClass("active");

        break;



    case 'navegador':

        tipo_busqueda = 'navegador';

        filtrarCategoria(document.getElementById('categorias'), 0);

        $('#categorias').css("display", "block");

        $('#vitrina').css("display", "block");

        $('#search-container').css("display", "none");

        $('#buscalo-controles').css("display", "none");

        $('#buscalo').removeClass("active");

        $('#codificalo').removeClass("active");

        $(this).addClass("active");

        break;

}



var descuentoPorcentajePromocion = 0;



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

            $('#search2').hide();

            $('#search3').hide();

            $('#search').show();

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



var venta = {};

var url_promos = $('#promociones').data('fetch');

var promocionTipo = false;



function obtenerPromocion(id_promocion)

{

    if(this.memo === undefined)

        this.memo = [];



    if(this.memo['promocion_'+id_promocion] !== undefined)

        return this.memo['promocion_'+id_promocion];



    var promocion;

    $.ajax({

        method: 'post',

        url: url_promos+'/obtener',

        data: {

            id_promocion: id_promocion,

        },

        async: false,

        dataType : 'json' 

    }).done(function(data){

        promocion = data;

        if(promocion['tipo'] == "cantidad")

        {

            promocionTipo = 1;

        }else if(promocion['tipo'] == "")

        {

            promocionTipo = 0;

        }

    });





    this.memo['promocion_'+id_promocion] = promocion;

    return promocion;

}



function obtenerDetallePromocion(id_promocion)

{

    if(this.memo === undefined)

        this.memo = [];



    if(this.memo['promocion_detalle_'+id_promocion] !== undefined)

        return this.memo['promocion_detalle_'+id_promocion];



    var detalle;

    $.ajax({

        method: 'post',

        url: url_promos+'/obtenerDetallePromocion',

        data: {

            id_promocion: id_promocion,

        },

        async: false,

        dataType : 'json' 

    }).done(function(data){

        detalle = data;

    });



    this.memo['promocion_detalle_'+id_promocion] = detalle;

    //console.log(detalle);

    return detalle;

}



function validarProductoPromocion(id_promocion, id_producto)

{

    if(this.memo === undefined)

        this.memo = [];



    if(this.memo['promocion_producto_'+id_promocion+'_'+id_producto] !== undefined)

        return this.memo['promocion_producto_'+id_promocion+'_'+id_producto];



    $.ajax({

        method: 'post',

        url: url_promos+'/validarProducto',

        data: {

            id_promocion: id_promocion,

            id_producto: id_producto

        },

        async: false,

        dataType : 'json' 

    }).done(function(data){

        valido = data.valido

    });



    return valido;

}



function validarProductoPromocionD(id_promocion, id_producto)

{

    if(this.memo === undefined)

        this.memo = [];



    if(this.memo['promocion_producto_'+id_promocion+'_'+id_producto] !== undefined)

        return this.memo['promocion_producto_'+id_promocion+'_'+id_producto];



    $.ajax({

        method: 'post',

        url: url_promos+'/validarProductoD',

        data: {

            id_promocion: id_promocion,

            id_producto: id_producto

        },

        async: false,

        dataType : 'json' 

    }).done(function(data){

        valido = data.valido

    });



    return valido;

}



function procesarDescuento(producto, descuento)

{

    var table = $('.product_id[value="'+producto.product_id+'"]').closest('tr');

    var precio = table.find('.precio-prod-real-no-cambio').val();



    var total_descuento = (parseFloat(precio) * descuento / 100);

    var total = (precio - total_descuento);

    table.find('.precio-prod-real').val(total);

    table.find('.precio-prod-real').attr('data-promocion', 1);

    table.find('.precio-prod').text(total);

}



function removerDescuento(producto)

{

    var table = $('.product_id[value="'+producto.product_id+'"]').closest('tr');

    var attr = table.find('.precio-prod-real').attr('data-promocion');

    if(typeof attr !== typeof undefined && attr !== false)

    {

        var precio = table.find('.precio-prod-real-no-cambio').val();

        precio = parseFloat(precio);

        table.find('.precio-prod-real').val(precio);

        table.find('.precio-prod-real').removeAttr('data-promocion');

        table.find('.precio-prod').text(precio);

    }

}



function facturasTable()

{

    // Recorrer productos de la venta

    productos_list = new Array();

    productos_promo = new Array();



    if($('#promociones').is(':visible') && $('#promocion').val() > 0)

    {

        var id_promocion = $('#promocion').val();



        $('.title-detalle').each(function(x) {

            var descuento = 0;

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

            

            if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val()))

                descuento = 0;



            productos_list[x] = {

                'codigo': $('.codigo-final').eq(x).val(),

                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                'unidades': parseFloat($(".cantidad").eq(x).text()),

                'impuesto': $(".impuesto-final").eq(x).val(),

                'nombre_producto': $(".title-detalle").eq(x).text(),

                'product_id': $(".product_id").eq(x).val(),

                'descuento': descuento,

                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

            }

        });



        /*$.each(productos_list, function(i, e)

        {

            if(validarProductoPromocion(id_promocion, e.product_id))

            {

                productos_promo.push(e);

            }

        });*/



        detalle = obtenerDetallePromocion(id_promocion);

        promocion = obtenerPromocion(id_promocion);



        /*productos_promo.sort(

            function(a, b){

                if (a.precio_venta < b.precio_venta)

                    return 1;

                else if (a.precio_venta > b.precio_venta)

                    return -1;

                else 

                    return 0;

            }

        );*/



        //validar y aplicar promoción

        switch(promocion.tipo)

        {

            case 'progresivo':

                

            break;

            case 'individual':



            break;

            case 'cantidad':

                var descuento_general = 0;

                var unidades = 0;

                $.each(productos_promo, function(i, e){

                    unidades += e.unidades;

                });



                var cantidad_para_descuento = 0;

                $.each(detalle, function(i, e)

                {

                    cantidad_para_descuento = e.cantidad;



                    //console.log(unidades, cantidad_para_descuento, unidades >= cantidad_para_descuento);

                    if(unidades >= cantidad_para_descuento)

                    {

                        descuento_general = e.descuento;

                    }

                    //console.log(descuento_general+"descuento");

                });



                $.each(productos_promo, function(i, e){

                    procesarDescuento(e, descuento_general);

                });

            break;

        }

    } else {

        $('.title-detalle').each(function(x) {

            var descuento = 0;

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

            

            if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val()))

                descuento = 0;



            productos_list[x] = {

                'codigo': $('.codigo-final').eq(x).val(),

                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                'unidades': parseFloat($(".cantidad").eq(x).text()),

                'impuesto': $(".impuesto-final").eq(x).val(),

                'nombre_producto': $(".title-detalle").eq(x).text(),

                'product_id': $(".product_id").eq(x).val(),

                'descuento': descuento,

                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

            }

        });



        $.each(productos_list, function(i, e){

            removerDescuento(e);

        });

    }

}







function pasarPromocion()

{

    // Recorrer productos de la venta

    productos_list = new Array();

    productos_promo = new Array();

    

    if($('#promociones').is(':visible') && $('#promocion').val() > 0)

    {

        var id_promocion = $('#promocion').val();



        $('.title-detalle').each(function(x) {

            var descuento = 0;

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

            

            if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val()))

                descuento = 0;



            productos_list[x] = {

                'codigo': $('.codigo-final').eq(x).val(),

                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                'unidades': parseFloat($(".cantidad").eq(x).text()),

                'impuesto': $(".impuesto-final").eq(x).val(),

                'nombre_producto': $(".title-detalle").eq(x).text(),

                'product_id': $(".product_id").eq(x).val(),

                'descuento': descuento,

                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

            }

        });



        $.each(productos_list, function(i, e)

        {   

            if(validarProductoPromocion(id_promocion, e.product_id))

            {

                productos_promo.push(e);

            }

        });

        

        detalle = obtenerDetallePromocion(id_promocion);console.log(detalle);

        console.log("pasarPromocion"+promocion);

    

        switch(promocion.tipo)

        {

            case '':

                if(detalle[0]['descuento'] == 1)

                {

                    var cantidadPDescuento = parseInt(detalle[0]['cantidad']),//cantidad para obsequio

                        cantidadDDescuento = parseInt(detalle[0]['producto_pos']);//cantidad de obsequio

                    $('#productos-detail').find('tr').each(function(i,e){

                        if(validarProductoPromocion(id_promocion,$(e).find('.product_id').val()))

                        {

                            $("input.precio-prod-real").eq(i).attr("data-promocion",'1');

                            cantidad = $(e).find('span.cantidad').html();

                            cantidadPagar = 0;

                            function recorrerCantidad(cantidadFaltante)

                            {

                                var cantidadContador = cantidadFaltante;

                                for(i=1;i<=cantidadFaltante;i++)

                                {   

                                    if(i<=cantidadPDescuento)

                                    {

                                       cantidadPagar++; 

                                       cantidadContador--;

                                    }else if(i<=cantidadPDescuento+cantidadDDescuento)

                                    {

                                        cantidadContador--;

                                    }else if(i >= cantidadPDescuento+cantidadDDescuento)

                                    {

                                        recorrerCantidad(cantidadContador);

                                        break;

                                    }

                                }

                            }

                            recorrerCantidad(cantidad);



                            numeroDescuento = parseInt(cantidad / (cantidadPDescuento))*cantidadDDescuento;

                            impuesto = $(e).find('input.impuesto-final').val();

                            precio = $(e).find('input.precio-prod-real-no-cambio').val();

                            precioTotal = (cantidadPagar) * precio;

                            ivaCantidad = (precio * impuesto /100)*(cantidadPagar);

                            $(e).find('input.promocionPrecio').val(precioTotal);

                            $(e).find('.promocionIva').val(ivaCantidad);

                        }

                    });

                }else if(detalle[0]['descuento'] == 0)

                { 

                    var cantidadPDescuento = parseInt(detalle[0]['cantidad']),//cantidad para obsequio

                        cantidadDDescuento = parseInt(detalle[0]['producto_pos']);//cantidad de obsequio

                    $('#productos-detail').find('tr').each(function(i,e){

                       /*if(validarProductoPromocion(id_promocion,$(e).find('.product_id').val()) && validarProductoPromocionD(id_promocion,$(e).find('.product_id').val()))

                        {

                            $("input.precio-prod-real").eq(i).attr("data-promocion",'3');

                            //producto que es de compra y de obsequio

                        }else*/ 

                        if(validarProductoPromocion(id_promocion,$(e).find('.product_id').val()))

                        {

                            $("input.precio-prod-real").eq(i).attr("data-promocion",'1');

                            //producto que es solo de compra

                        }else if(validarProductoPromocionD(id_promocion,$(e).find('.product_id').val()))

                        {

                            $("input.precio-prod-real").eq(i).attr("data-promocion",'2');

                            //producto que es solo de obsequio

                        }

                    });

                    cantidadPagar = 0;



                    $('#productos-detail').find('input[data-promocion=1]').parents('tr').each(function(i,e)

                    {

                        cantidad = $(e).find('span.cantidad').html();

                        cantidadPagar = parseInt(cantidadPagar) + parseInt(cantidad);

                        impuesto = $(e).find('input.impuesto-final').val();

                        precio = $(e).find('input.precio-prod-real-no-cambio').val();

                        precioTotal = (cantidad) * precio;

                        ivaCantidad = (precio * impuesto /100)*(cantidad);

                        $(e).find('input.promocionPrecio').val(precioTotal);

                        $(e).find('.promocionIva').val(ivaCantidad);

                    });

                    $('#productos-detail').find('input[data-promocion=2]').parents('tr').each(function(i,e)

                    {

                        cantidad = $(e).find('span.cantidad').html();



                        if(cantidadPagar >= cantidadPDescuento)

                        {

                            cantidadDescuentos = parseInt(cantidadPagar / cantidadPDescuento)* cantidadDDescuento;

                            if(cantidadDescuentos >= cantidad)

                            {

                                impuesto = $(e).find('input.impuesto-final').val();

                                precio = $(e).find('input.precio-prod-real-no-cambio').val();

                                precioTotal = 0;

                                ivaCantidad = 0;

                                $(e).find('input.promocionPrecio').val(precioTotal);

                                $(e).find('.promocionIva').val(ivaCantidad);

                                $('#productos-detail').find('input[data-promocion=2]').eq(i).attr('data-cantidad',cantidadPagar);

                                cantidadPagar = cantidadPagar-cantidad;

                            }else

                            {

                                impuesto = $(e).find('input.impuesto-final').val();

                                precio = $(e).find('input.precio-prod-real-no-cambio').val();

                                precioTotal = (cantidad-cantidadDescuentos) * precio;

                                ivaCantidad = (precio * impuesto /100)*(cantidad-cantidadDescuentos);

                                $(e).find('input.promocionPrecio').val(precioTotal);

                                $(e).find('.promocionIva').val(ivaCantidad);

                                $('#productos-detail').find('input[data-promocion=2]').eq(i).attr('data-cantidad',cantidadPagar);

                                cantidadPagar = 0;

                            }

                        }else

                        {

                            impuesto = $(e).find('input.impuesto-final').val();

                            precio = $(e).find('input.precio-prod-real-no-cambio').val();

                            precioTotal = (cantidad) * precio;

                            ivaCantidad = (precio * impuesto /100)*(cantidad);

                            $(e).find('input.promocionPrecio').val(precioTotal);

                            $(e).find('.promocionIva').val(ivaCantidad);

                        }



                    });

                    $('#productos-detail').find('input[data-promocion=3]').parents('tr').each(function(i,e)

                    {



                    });

                }

            break;

            case 'individual':



            break;

            //---//////promocion cantidad--

            case 'cantidad':

                var descuento_general = 0;

                var unidades = 0;

                

                $.each(productos_promo, function(i, e){

                    unidades += e.unidades;

                });



                var cantidad_para_descuento = 0;

                $.each(detalle, function(i, e)

                {

                    cantidad_para_descuento = (e.cantidad * 1);



                    if(unidades >= cantidad_para_descuento)

                    {

                        descuento_general = e.descuento;

                    }

                    

                });

                descuentoPorcentajePromocion = descuento_general;

                var precioTotal = 0,

                    ivaCantidad = 0,

                    idAnterior = 0;                    

                $.each(productos_promo, function(i, e){

                    if(idAnterior != e.product_id)

                    {

                        precioTotal = 0;

                        ivaCantidad = 0;

                    }

                    var table = $('.product_id[value="'+e.product_id+'"]').closest('tr');

                    var precio = (table.find('.precio-prod-real-no-cambio').val());

                    var cantidad = limpiarCampo(table.find('.cantidad').text());

                    var total_descuento = (((precio / 100) * (descuento_general )) *cantidad);

                    var total = (((precio *cantidad) - total_descuento)),

                        impuesto = table.find('.impuesto-final').val();

                        

                    console.log(total_descuento+"TD-P"+precio+"--DG"+descuento_general+"--C"+cantidad+"--T"+total);

                    

                    precioTotal = (((total) + (precioTotal)));

                    console.log("PT"+precioTotal+"--");

                    ivaCantidad = (((precioTotal /100) * impuesto));

                    table.find('.precio-prod-real').attr('data-promocion', 1);

                    table.find('input.promocionPrecio').val((precioTotal));

                    table.find('.promocionIva').val((ivaCantidad));

                    //procesarDescuento(e, descuento_general);

                    idAnterior = e.product_id;

                });

                

            break;

        }

        

        promocion = obtenerPromocion(id_promocion);



        productos_promo.sort(

            function(a, b){

                if (a.precio_venta < b.precio_venta)

                    return 1;

                else if (a.precio_venta > b.precio_venta)

                    return -1;

                else 

                    return 0;

            }

        );

    } else {

        $('.title-detalle').each(function(x) {

            var descuento = 0;

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

            

            if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val()))

                descuento = 0;



            productos_list[x] = {

                'codigo': $('.codigo-final').eq(x).val(),

                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                'unidades': parseFloat($(".cantidad").eq(x).text()),

                'impuesto': $(".impuesto-final").eq(x).val(),

                'nombre_producto': $(".title-detalle").eq(x).text(),

                'product_id': $(".product_id").eq(x).val(),

                'descuento': descuento,

                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

            }

        });



        $.each(productos_list, function(i, e){

            removerDescuento(e);

        });

    }

}



$(".cantidad").live('click', function() {

    cantidadField = $(this);

    propoverContent = "<div class='span9'><input type='text' class='spinner' name='cantidad_input' value='" + cantidadField.text() + "'/></div><button type='button' id='btn-accept-cantidad' class='btn btn-primary text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>";

    cantidadField.popover({

        placement: 'bottom',

        title: 'Cantidad',

        html: true,

        content: propoverContent,

        trigger: 'manual'

    }).popover('show');

    //cantidadField;



    $(".spinner").spinner({

        min: 0

    });

    $("#btn-accept-cantidad").click(function() {

        cantidadField.html($('.spinner').val());

        cantidadField.popover('destroy');

        pasarPromocion();

        calculate(); 

        actualizarEspera();

    });



    $("input[name='cantidad_input']").keyup(function(e) {

        if (e.keyCode == 13) {

            cantidadField.html($('.spinner').val());

            cantidadField.popover('destroy');

            pasarPromocion();

            calculate();  actualizarEspera();

        }

    });

});







function calculadora_descuento(valor_precio_venta) {



    $(".precio-prod").live('click', function() {

        precioField = $(this);

        //alert(valor);

        precioFieldReal = $('.precio-prod-real').eq($(".precio-prod").index($(this)));

        precioProd = precioFieldReal.val();

        console.log(precioProd);

        impuesto = $('.impuesto-final').eq($(".precio-prod").index($(this))).val();

        precioContent = "<form id='Calc'>" +

            "<div class='row'>" +

            "<div class='span12'>" +

            "<input type='text' value='' name='Input' class='form-control Input'/><input type='hidden' value='' name='Input1' class='Input1'/>" +

            "</div>" +

            "</div>" +

            "<div class='row'>" +

            "<div class='span3'>" +

            "<input type='button' class='btn one' value='1' name='one'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn two' value='2' name='two'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn three' value='3' name='three'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input class='btn sum' type='button' value='+' name='sum' style='width:51px'/>" +

            "</div>" +

            "</div>" +

            "<div class='row'>" +

            "<div class='span3'>" +

            "<input type='button' class='btn four' value='4' name='four'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn five' value='5' name='five'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn six' value='6' name='six'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input class='btn rest' type='button' value='-' name='rest' style='width:51px'/>" +

            "</div>" +

            "</div>" +

            "<div class='row'>" +

            "<div class='span3'>" +

            "<input type='button' class='btn seven' value='7' name='seven'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn eith' value='8' name='eith'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn nine' value='9' name='nine'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input class='btn porcen' type='button'  value='%' name='divi' style='width:51px'/>" +

            "</div>" +

            "</div>" +

            "<div class='row'>" +

            "<div class='span3'>" +

            "<input class='btn cero' type='button'  value='0' name='cero'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input type='button' class='btn clear' value='C' name='clear'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<input class='btn doIt' type='button'  value='=' name='doIt'/>" +

            "</div>" +

            "<div class='span3'>" +

            "<button type='button' id='btn-accept-precio' style='width:51px' class='btn btn-primary'>Enter</button>" +

            "</div>" +

            "</div>" +

            "</form>";

        precioField.popover({

            placement: 'bottom',

            title: 'Precio',

            html: true,

            content: precioContent,

            trigger: 'manual'

        }).popover('show').addClass('my-super-popover');



        $("#btn-accept-precio").click(function() {



            if ($('.Input').val() != '') {

                var valor = $('.Input').val();

                var val1 = valor.replace("%", "");

                var val2 = val1.replace(" ", "");

                var precio = valor_precio_venta;



                var primer = $('.Input').val();

                var res1 = primer.replace(/[1234567890]/gi, "");



                if (res1 == ' % ' || $('.Input1').val() == 'porcentaje') {

                    var resultado_porcen1 = (parseFloat(precio) * val2 / 100);

                    var resultado_porcen2 = (precio - resultado_porcen1);

                    $('.Input').val(resultado_porcen2);

                    $('.Input1').val("");

                } else {

                    $('.Input').val(eval($('.Input').val()));

                }

                if(impuesto != 0)

                {

                    //console.log(precioProd+"----"+(($('.Input').val() * impuesto) / 100));

                    //precioProd = $('.Input').val();

                    if(impuesto.length == 1)

                    {

                        precioProd = Math.round($('.Input').val() / parseFloat("1.0"+impuesto));

                        //console.log($('.Input').val() / parseFloat("1.0"+impuesto));

                    }else if(impuesto.length == 2)

                    {

                        precioProd = Math.round($('.Input').val() / parseFloat("1."+impuesto));

                        //console.log($('.Input').val() / parseFloat("1."+impuesto));

                    }

                    //console.log($('.Input').val() - (($('.Input').val() * impuesto) / 100));

                    

                    console.log("precioP"+precioProd);

                    //console.log($('.Input').val()+"..."+impuesto+"..."+$('.Input').val() * impuesto+"....");

                }else

                {

                    precioProd = limpiarCampo($('.Input').val());

                }

            } else if ($('.Input').val() == '') {

                var precio = limpiarCampo(precioField.text());

                $('.Input').val(precio);

            }

            if (isNaN($('.Input').val())) {

                $('.Input').val('0');

            }

            /*

             var precio_compro = precioField.text().replace(",", "");

             if(parseInt($('.Input').val()) > parseInt(precio_compro)){

                 

                   $('.Input').val('0');

             }

             */



            precioField.html(formatDollar($('.Input').val()));

            precioFieldReal.val(precioProd);

            precioField.popover('destroy');

            calculate();  actualizarEspera();

            

        });



        $('#Calc').submit(function() {

            precioField.html(formatDollar($('.Input').val()));

            precioFieldReal.val($('.Input').val());

            precioField.popover('destroy');

            calculate();  actualizarEspera();

            return false;

        });



    });



}



$(".one, .two, .three, .sum, .four, .five, .six, .seven, .eith, .nine, .cero, .rest, .mult, .porcen, .doIt, .clear").live('click', function() {

    data = $(this).attr('class');

    switch (data.split(' ')[1]) {

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

            var valor = $('.Input').val();

            var val1 = valor.replace("%", "");

            var val2 = val1.replace(" ", "");

            var precio = precioField.text().replace(",", "");



            var primer = $('.Input').val();

            var res1 = primer.replace(/[1234567890]/gi, "");



            if (res1 == ' % ' || $('.Input1').val() == 'porcentaje') {

                var resultado_porcen1 = (parseInt(precio) * val2 / 100);

                var resultado_porcen2 = (precio - resultado_porcen1);

                $('.Input').val(resultado_porcen2);

                $('.Input1').val("");

            } else {

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





$(".precio-porcentaje").live('click', function() {

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

        placement: 'bottom',

        title: 'Ingrese el numero de porcentaje de descuento y dar click en enter',

        html: true,

        content: precioContent,

        trigger: 'manual'

    }).popover('show');



    $("#btn-accept-precio").click(function() {

        precioField1.html(formatDollar($('.Input').val()));

        precioFieldReal1.val($('.Input').val());

        precioField1.popover('destroy');

        calculate();  actualizarEspera();

    });



    $('#Calc').submit(function() {

        precioField1.html(formatDollar($('.Input').val()));

        precioFieldReal1.val($('.Input').val());

        precioField1.popover('destroy');

        calculate();  actualizarEspera();

        return false;

    });



});



$(".uno, .dos, .tres, .sumas, .cuatro, .cinco, .seis, .siete, .ocho, .nueve, .cero, .rest, .mult, .divi, .doIt, .clear").live('click', function() {

    data = $(this).attr('class');

    switch (data.split(' ')[1]) {



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

var tieneCredito = false;



$(document).ready(function(){



    //Inicio cotizacion a factura

    // iva = 0;

    // subtotal = 0;

    // total = 0;

    // valporcen1 = 0;

    // total_porcen = 0;

    //

    // if(presupuesto !== ''){

    //     $(presupuesto).each(function(index, item) {

    //         console.log(item);

    //         nombre = item.nombre;

    //         id_producto = item.fk_id_producto;

    //         precio   = item.precio_venta;

    //         precio = parseFloat(precio);

    //         impuesto = item.porciento;

    //         cantidad = item.cantidad;

    //         porcentaje = $(".precio-porcentaje").eq(index).text();

    //         codigo_barra = item.codigo_barra;

    //         precio_compra = item.precio_compra;

    //         nombre_impuesto = item.nombre_impuesto;

    //         nombre_impuesto = nombre_impuesto.toUpperCase();

    //         if (porcentaje > 1) {

    //             valporcen1 = (precio - ((porcentaje * precio) / 100));

    //             subtotal += valporcen1 * cantidad;

    //             vimpuesto = valporcen1 * impuesto / 100 * cantidad;

    //             total += subtotal + vimpuesto;

    //         } else {

    //             subtotal += precio * cantidad;

    //             vimpuesto = precio * impuesto / 100 * cantidad;

    //             total += (precio * cantidad) + vimpuesto;

    //         }

    //

    //         if (nombre_impuesto.trim() == 'IAC' || nombre_impuesto.trim() == 'IMPOCONSUMO' || nombre_impuesto.trim() == 'IMPUESTO AL CONSUMO') {

    //             total_porcen += precio * cantidad;

    //         }

    //

    //         iva += vimpuesto;

    //         $('.precio-calc').eq(index).html(formatDollar(precio * cantidad));

    //         $('.precio-calc-real').eq(index).html(precio * cantidad);

    //

    //         $('#total').val(total);

    //         $("#total-show").html(formatDollar(total));

    //

    //         $("#iva-total").html(formatDollar(iva));

    //         $("#subtotal_input").val(subtotal);

    //

    //         $("#subtotal_propina_input").val(total_porcen);

    //

    //         $("#subtotal").html(formatDollar(subtotal));

    //

    //

    //         rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + precio + "'/>";

    //         rowHtml += "<input type='hidden' value='" + id_producto + "' class='product_id'/>";

    //         rowHtml += "<input type='hidden' class='codigo-final' value='" + codigo_barra + "'>";

    //         rowHtml += "<input type='hidden' class='impuesto-final' value='" + impuesto + "'><span class='title-detalle text-info'>";

    //         rowHtml += "<input type='hidden' value='" + nombre_impuesto + "' class='detalles-impuesto'>" + nombre + "</span></td>";

    //         rowHtml += "<td><span class='label label-success cantidad'>" + 1 + "</span><input type='hidden' class='nombre_impuesto' value='" + nombre_impuesto + "'></td>";

    //

    //         if ($sinprecio == 'si') {

    //

    //             rowHtml += "<td><input type='hidden' class='precio-prod-real' value='" + precio +"'/>";

    //             rowHtml += "<input type='hidden' class='precio-prod-real-no-cambio' value='" + precio + "'/></td>";

    //

    //         } else {

    //

    //             rowHtml += "<td><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + precio + ");'>" + precio + "</span>";

    //             rowHtml += "<input type='hidden' class='precio-prod-real' value='" + precio + "'/>";

    //             rowHtml += "<input type='hidden' class='precio-prod-real-no-cambio' value='" + precio + "'/></td>";

    //

    //         }

    //

    //         rowHtml += "<td><span class='precio-calc'>" + precio + "</span><input type='hidden' value='precio-calc-real' value='" + precio + "'/></td>";

    //         rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='wb-trash'></span></div></a></td></td>";

    //         rowHtml += "</tr>";

    //

    //         if ($("#productos-detail tr").eq(0).hasClass("nothing")) {

    //             $("#productos-detail").html(rowHtml);

    //         } else {

    //             $("#productos-detail").append(rowHtml);

    //         }

    //

    //     });

    // }

    //Fin cotizacion a factura







    //============================================================================

    // Forma de pago GiftCard

    //============================================================================

    

    // para validar pago final

    window.formaGiftObj ={

        "0":null,

        "1":null,

        "2":null,

        "3":null,

        "4":null,

        "5":null

    }

    

    function mostrarDiscriminacionImpuesto(e)

    {

        var opcion = $(e).find('option:selected');

        if($(opcion).attr('data-tipo') == "Datafono")

        {

            calcularImpuestoDicriminado($(e));

            $(e).parents("div.row-form").parent().find('div.datafono').show();

        }else

        {

            $(e).parents("div.row-form").parent().find('div.datafono').hide();

        }

    }



    function calcularImpuestoDicriminado(e)

    {

        var row = e.parents("div.row-form").parent(),

            valorEntregado = $(row).find('input[name=valor_entregado]').val(),

            impuesto = limpiarCampo($(row).find('#impuestoDatafono').val()),

            iva = valorEntregado * impuesto / 100;

            subtotal = valorEntregado - iva;

            

        $(row).find('input.subtotal').val(subtotal);

        $(row).find('input.impuesto').val(iva);

    }

    $('input[name=impuestoDatafono]').on('keyup',function(){

        calcularImpuestoDicriminado($(this));

    });



    $('#forma_pago').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("",1) : setDomGift("",0);

        mostrarDiscriminacionImpuesto($(this));

    });



    $('#forma_pago1').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("1",1) : setDomGift("1",0);

        mostrarDiscriminacionImpuesto($(this));

    });

    

    $('#forma_pago2').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("2",1) : setDomGift("2",0);

        mostrarDiscriminacionImpuesto($(this));

    });

    

    $('#forma_pago3').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("3",1) : setDomGift("3",0);

        mostrarDiscriminacionImpuesto($(this));

    });

    

    $('#forma_pago4').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("4",1) : setDomGift("4",0);

        mostrarDiscriminacionImpuesto($(this));

    });

    

    $('#forma_pago5').on('change', function() {

        $(this).val() == "Gift_Card" ? setDomGift("5",1) : setDomGift("5",0);

        mostrarDiscriminacionImpuesto($(this));

    });

    

    

    $(".codigoGift").keyup(function( event ) {

        if ( event.which == 13 ) {

            var index = $(this).attr("index") ;

            var codigo = $(this).val() ;

            getGiftEstado(codigo,index);

            event.preventDefault();

        }

    });

    

    $(".btnBuscarGift2").click(function(  ) {

        var index = $(this).attr("index") ;

        var codigo = $("#valor_entregado_gift" + index ).val() ;

        getGiftEstado( codigo, index );

    });    

    

    

    // para validar pago final

    function setGiftObj(index,valor){

        if( index == "" ) i = 0;

        else i = index;

        

        formaGiftObj[i] = valor;

        

    }    

    

      

    window.eliminarGiftcard = function( index ){ 

        

        setDomGift(index,0);    

        $("#forma_pago"+index).val("efectivo").trigger("change");

    }

    

    

    function setDomGift(index,opc){

        setGiftObj(index,null);

        

        // si opc = 1 se muestra la opcion giftcard, de lo contrario s eoculta

        if( opc == 1 ){

            $("#valor_entregado"+index).hide();

            $("#valor_entregado"+index).val( 0 );

            $("#valor_entregado_gift"+index).attr('style','display: block !important');            

            $("#valor_entregado_giftb"+index).attr('style','display: block !important');            

        }else{

            

            $("#valor_entregado"+index).show();

            //$("#valor_entregado"+index).val( 0 );

            $("#valor_entregado"+index).prop('disabled', false);

            $("#valor_entregado"+index).css("cursor", "default");         

            

            $("#valor_entregado_gift"+index).prop('disabled', false);

            $("#valor_entregado_gift"+index).attr('style','display: none !important');

            $("#valor_entregado_giftb"+index).attr('style','display: none !important');

            $("#valor_entregado_gift"+index).val('');

            

        }

        

        validarMediosDePago(0);

        

    }  

        

    function getGiftEstado( codigo, index ){

        

        $.ajax({

            

            url: $estadoGiftCard,

            dataType: 'json',

            type: 'POST',

            data: {"codigo":codigo},

            error: function(jqXHR, textStatus, errorThrown ){

                alert(errorThrown);

            },

            success: function(data){

                setEstadoGiftCard( data, index );

            }

            

        }); 

        

    }

    

    

    function setEstadoGiftCard( datos, index ){

        

        var estado = datos.estado;

        var nombre = datos.nombre;

        var valor = datos.valor;

        

        //valor_entregado_gift -> codigo

        //valor_entregado -> valor

        //forma_pago -> select

        

        $("#valor_entregado"+index).val( 0 );

        $("#valor_entregado"+index).prop('disabled', false);

        $("#valor_entregado"+index).css("cursor", "default");

        $("#valor_entregado"+index).hide();

        $("#valor_entregado_gift"+index).prop('disabled', false);

        $("#valor_entregado_gift"+index).css("cursor", "default");            

        $("#valor_entregado_giftb"+index).attr('style','display: block !important');

        

        setGiftObj( index, null );

            

        if( estado == "empty" ){

            alert("La giftCard no existe");  

        }

        if( estado == "cancelado" ){

            alert("La "+nombre+" ya ha sido canjeada");

        }

        if( estado == "activo" ){

            alert("La "+nombre+" no ha sido pagada");

        }

        

        if( estado == "pagado" ){

            

            $("#valor_entregado"+index).val( valor );

            $("#valor_entregado"+index).prop('disabled', true);

            $("#valor_entregado"+index).css("cursor", "not-allowed");

            $("#valor_entregado"+index).show();

            $("#valor_entregado_gift"+index).prop('disabled', true);

            $("#valor_entregado_gift"+index).css("cursor", "not-allowed");

            $("#valor_entregado_giftb"+index).attr('style','display: none !important');

            setGiftObj(index,"pagada");



        }            

        

        validarMediosDePago(0);

        

    }

    

    

    //============================================================================

    // FIN Forma de pago GiftCard

    //============================================================================

    





    $("#valor_entregado, #valor_entregado1, #valor_entregado2, #valor_entregado3, #valor_entregado4, #valor_entregado5").keyup(function(e){

        validarMediosDePago(e);

        calcularImpuestoDicriminado($(this));

    });



    //obtener promociones

    $.post (

        url_promos+'/obtenerHabilitados',

        {},

        function (data)

        {

            if (data.length > 0)

            {

                $('#promocion').append('<option value="0">Seleccionar</option>');

                $.each(data, function(i, e){

                    $('#promocion').append('<option value="'+e.id_promocion+'">'+e.nombre+'</option>');

                });



                //mostrar selector de promociones solo cuando existan

                $('#promociones').show();

            }

        },

        'json'

    );



    $("#buscar-cliente").keyup(function(e) {

        if ($("#buscar-cliente").val() != '')

            $('#contenedor-lista-clientes').fadeIn('fast');

        else

            $('#contenedor-lista-clientes').fadeOut('fast');

    });



    $(".first, .previous, .next, .last").live('click', function() {

        if (!$(this).hasClass('paginate_button_disabled')) {

            classValue = $(this).attr('class');

            switch (classValue.split(' ')[0]) {

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



    $("#search").on('keydown', function(e) {

        if (tipo_busqueda == 'buscalo') {

            //alert(cliente_grupo);

            var code = e.keyCode || e.which;

            $('#cod-container').css('display', 'none');



            if (cliente_selecionado) {

                var cliente = cliente_selecionado;

                var grupo = cliente_grupo;

            } else {

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

     });



    function paintRows(){

        formatTable();

        paintInfo();

        paintPagin();

        for(i = offset; i < getCount(); i++){

             if($("#facturasTable tbody tr").eq(0).hasClass("nothing")){

                 $('#facturasTable tbody').html("");

                 var strRow = formatRows(objData[i]).replace("<img", '<img onerror="ImgError(this)" ');                 

                 var $row = $( strRow ).appendTo('#facturasTable tbody');

                 

                 //Agregamos input hidden con el valor del giftcard

                 $($row[0]).find(".codigo").after('<input type="hidden" class="giftcard" value="'+objData[i]["gc"]+'">');

             }

             else{

                var strRow = formatRows(objData[i]).replace("<img", '<img onerror="ImgError(this)" ');                 

                var $row = $( strRow ).appendTo('#facturasTable tbody');

                 //Agregamos input hidden con el valor del giftcard

                 $($row[0]).find(".codigo").after('<input type="hidden" class="giftcard" value="'+objData[i]["gc"]+'">');

             }

        }

    }



    /*Count elements on table*/

    function getCount() {

        count = limit + offset;

        dataLength = objData.length;

        if (count > dataLength) {

            count = dataLength;

        }

        return count;

    }



    function getCountPages() {

        count = Math.ceil(objData.length / limit);

        return count;

    }



        

  

  

    /*function formatTable(){

        $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");



        if (row.impuesto != '0') {

            html += "<p><span class='stock'>Stock: " + row.stock_minimo + "</span>&nbsp;<input type='hidden' value='" + row.precio_compra + "' class='precio-compra-real'/><span class='precio-minimo'>C&oacute;digo de barra: " + row.codigo + "</span>  -  <span class='precio-minimo'>Precio + IVA: " + formatDollar(precioimpuesto) + "</span> &nbsp;<span class='ubicacion_producto'>Ubicaci&oacute;n: " + row.ubic + "</span> </p><input type='hidden' class='id_producto' value='" + row.id + "'/><input type='hidden' class='codigo' value='" + row.codigo + "'><input type='hidden' class='impuesto' value='" + row.impuesto + "'></p></td>";

        }

        html += "</tr>";



        return html;

    }*/



    function formatTable() {

        $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");

    }



    function paintInfo() {

        $(".dataTables_info").html("Mostrando desde " + offset + " hasta " + getCount() + " de " + objData.length + " elementos");

    }

    /*Paginador*/

    function paintPagin() {

        $(".dataTables_paginate").html(

            getFirstPage() + getPrevPage() + getNextPage() + getLastPage()

        );

    }



    function getFirstPage() {

        if (offset > 0) {

            return '<a class="first paginate_button">Primero</a>';

        } else {

            return '<a class="first paginate_button paginate_button_disabled">Primero</a>';

        }

    }



    function getPrevPage() {

        if (pages > 0) {

            return '<a class="previous paginate_button" tabindex="0">Anterior</a>';

        } else {

            return '<a class="previous paginate_button paginate_button_disabled" tabindex="0">Anterior</a>';

        }

    }



    function getNextPage() {

        if ((getCountPages() - 1) > pages) {

            return '<a class="next paginate_button" tabindex="0">Pr&oacute;ximo</a>';

        } else {

            return '<a class="next paginate_button paginate_button_disabled" tabindex="0">Pr&oacute;ximo</a>';

        }

    }



    function getLastPage() {

        if (getCountPages() - 1 > pages) {

            return '<a class="last paginate_button" tabindex="0">&Uacute;timo</a>';

        } else {

            return '<a class="last paginate_button paginate_button_disabled" tabindex="0">&Uacute;timo</a>';

        }

    }

  



    function grabar(){

        

        $("#grabar").prop( "disabled", true );        

        var max = 0;

        

        var imprimir = function(data){

            

            $.fancybox.open({

                'width' : '85%',

                'height' : '85%',

                'autoScale' : false,

                'transitionIn' : 'none',

                'transitionOut' : 'none',

                href : $urlPrint +"/"+data.id,

                type : 'iframe',

                afterClose: function(){

                   // location.href =  $reloadThis+"?var="+busquedas;

                    window.location = $reloadThis+"?var="+tipo_busqueda;

                }

                //padding : 5

            });

        }

        

        var imprimirFactura = function(data){

            

            if(facturaAutomatica == "estandar" ){

                

                if(!confirm("Desea imprimir la factura de venta?"))  window.location = $reloadThis+"?var="+tipo_busqueda;

                else imprimir(data);  

                

            }else if(facturaAutomatica == "auto" ){

                

                imprimir(data); 

                

            }else if(facturaAutomatica == "no" ){

                 window.location = $reloadThis+"?var="+tipo_busqueda;

            }



            $("#dialog-forma-pago-form").dialog( "close" );

            

        }

            

        $(this).attr('disabled', true);

        productos_list = new Array();

        

        // para almacenar los giftcard que se venderan

        var giftCardsObjs = [];

        

        var promocion = '',

            promocionId = '';

        if($('#promocion').is(':visible')){

            if($('#promocion').val() != '0')

            {

                //promocion = $('#promocion option:selected').text();

                promocionId =$('#promocion').val();

                promocion = promocionId;

                var detalle = obtenerDetallePromocion(promocionId);

                promocionDescuento = detalle[0]['descuento'];

            }

        }

        

        $(".title-detalle").each(function(x){

            var descuento = 0;

            

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();                        

            if(parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())){

                descuento = 0;

            }

            

            // Si es giftcard loa añadimos a la lista de fitcards que van a ser pagadas

            if ( $("#productos-detail .giftcard").eq(x).val() == "1"){

                giftCardsObjs.push( $(".product_id").eq(x).val() );

            }

            precio = redondear(Math.round($(".precio-prod-real-no-cambio").eq(x).val()));

            if(promocionTipo == 1)

            {

                impusto = parseFloat($(".impuesto-final").eq(x).val());

                precioMostrar = Math.round(precio - Math.round(precio * descuentoPorcentajePromocion / 100));

                //console.log(precio+"--"+precioMostrar+"--"+descuentoPorcentajePromocion+"--"+(precio * descuentoPorcentajePromocion / 100));

                precioConIva = limpiarCampo(parseFloat(precio + parseFloat(precio * impuesto / 100)));

                precioPromocion = $(".promocionPrecio").eq(x).val();

                console.log('precio_promocion'+precioPromocion);

                if(precioPromocion > 0)

                precioDescuento = Math.round(Math.round(precio*$(".cantidad").eq(x).text()) - precioPromocion)/Math.round($(".cantidad").eq(x).text());

                else precioDescuento = 0;

                

                arregloProductos = {

                    'codigo': $('.codigo-final').eq(x).val(),

                    'precio_venta':(precio),

                    'unidades': parseFloat($(".cantidad").eq(x).text()),

                    'impuesto': $(".impuesto-final").eq(x).val(),

                    'nombre_producto': $(".title-detalle").eq(x).text(),

                    'product_id': $(".product_id").eq(x).val(),

                    'descuento': precioDescuento,

                    'margen_utilidad': 0

                }

                console.log("qqq1");

                productos_list.push(arregloProductos);

            }else

            if(typeof(promocionDescuento) != "undefined")

            {   

                if(detalle[0]['descuento'] == 1 && promocionTipo != 1)

                {

                    if(validarProductoPromocion(promocionId,$('.product_id').eq(x).val()) )

                    {

                        console.log("sisi1");

                            cantidad = $('span.cantidad').eq(x).html();

                            cantidadPagar = 0;

                            var cantidadPDescuento = parseInt(detalle[0]['cantidad']),//cantidad para obsequio

                                cantidadDDescuento = parseInt(detalle[0]['producto_pos']);//cantidad de obsequio

                            function recorrerCantidad(cantidadFaltante)

                            {

                                var cantidadContador = cantidadFaltante;

                                for(i=1;i<=cantidadFaltante;i++)

                                {   

                                    if(i<=cantidadPDescuento)

                                    {

                                       cantidadPagar++; 

                                       cantidadContador--;

                                    }else if(i<=cantidadPDescuento+cantidadDDescuento)

                                    {

                                        cantidadContador--;

                                    }else if(i >= cantidadPDescuento+cantidadDDescuento)

                                    {

                                        recorrerCantidad(cantidadContador);

                                        break;

                                    }

                                }

                            }

                            recorrerCantidad(cantidad);

                            if(cantidad == cantidadPagar)

                            {

                                arregloProductos = {

                                    'codigo': $('.codigo-final').eq(x).val(),

                                    'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                    'unidades': parseFloat($(".cantidad").eq(x).text()),

                                    'impuesto': $(".impuesto-final").eq(x).val(),

                                    'nombre_producto': $(".title-detalle").eq(x).text(),

                                    'product_id': $(".product_id").eq(x).val(),

                                    'descuento': descuento,

                                    'margen_utilidad': 0

                                }

                                console.log("qqq2");

                                productos_list.push(arregloProductos);

                            }else if(cantidad > cantidadPagar)

                            {

                                arregloProductos = {

                                    'codigo': $('.codigo-final').eq(x).val(),

                                    'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                    'unidades': cantidadPagar,

                                    'impuesto': $(".impuesto-final").eq(x).val(),

                                    'nombre_producto': $(".title-detalle").eq(x).text(),

                                    'product_id': $(".product_id").eq(x).val(),

                                    'descuento': descuento,

                                    'margen_utilidad': -1

                                }

                                console.log("qqq3");

                                productos_list.push(arregloProductos);

                                arregloProductos = {

                                    'codigo': $('.codigo-final').eq(x).val(),

                                    'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                    'unidades': parseInt(cantidad-cantidadPagar),

                                    'impuesto': $(".impuesto-final").eq(x).val(),

                                    'nombre_producto': $(".title-detalle").eq(x).text(),

                                    'product_id': $(".product_id").eq(x).val(),

                                    'descuento': descuento,

                                    'margen_utilidad': 0

                                }

                                productos_list.push(arregloProductos);

                            }

                    }

                }else if( detalle[0]['descuento'] == 0)

                {

                    var cantidadPDescuento = parseInt(detalle[0]['cantidad']),//cantidad para obsequio

                        cantidadDDescuento = parseInt(detalle[0]['producto_pos']);//cantidad de obsequio

                        cantidadPagar = 0;





                    if($('input.precio-prod-real').eq(x).attr('data-promocion') == 1)

                    {

                        

                        arregloProductos = {

                                'codigo': $('.codigo-final').eq(x).val(),

                                'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                'unidades': parseFloat($(".cantidad").eq(x).text()),

                                'impuesto': $(".impuesto-final").eq(x).val(),

                                'nombre_producto': $(".title-detalle").eq(x).text(),

                                'product_id': $(".product_id").eq(x).val(),

                                'descuento': descuento,

                                'margen_utilidad': 0

                            }

                            console.log("qqq4");

                        productos_list.push(arregloProductos);

                    }else if($('input.precio-prod-real').eq(x).attr('data-promocion') == 2)

                    {

                        if($('input.precio-prod-real').eq(x).attr('data-cantidad') >= parseFloat($(".cantidad").eq(x).text()))

                        {

                            arregloProductos = {

                                'codigo': $('.codigo-final').eq(x).val(),

                                'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                'unidades': 0,

                                'impuesto': $(".impuesto-final").eq(x).val(),

                                'nombre_producto': $(".title-detalle").eq(x).text(),

                                'product_id': $(".product_id").eq(x).val(),

                                'descuento': descuento,

                                'margen_utilidad': 0

                            }

                            console.log("qqq5");

                            productos_list.push(arregloProductos);

                            arregloProductos = {

                                'codigo': $('.codigo-final').eq(x).val(),

                                'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                'unidades': parseFloat($(".cantidad").eq(x).text()),

                                'impuesto': $(".impuesto-final").eq(x).val(),

                                'nombre_producto': $(".title-detalle").eq(x).text(),

                                'product_id': $(".product_id").eq(x).val(),

                                'descuento': descuento,

                                'margen_utilidad': -1

                            }

                            productos_list.push(arregloProductos);

                        }else

                        {

                            datacantidad = $('input.precio-prod-real').eq(x).attr('data-cantidad');

                            arregloProductos = {

                                'codigo': $('.codigo-final').eq(x).val(),

                                'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                'unidades': parseFloat($(".cantidad").eq(x).text()) - datacantidad,

                                'impuesto': $(".impuesto-final").eq(x).val(),

                                'nombre_producto': $(".title-detalle").eq(x).text(),

                                'product_id': $(".product_id").eq(x).val(),

                                'descuento': descuento,

                                'margen_utilidad': 0

                            }

                            console.log("qqq6");

                            productos_list.push(arregloProductos);

                            arregloProductos = {

                                'codigo': $('.codigo-final').eq(x).val(),

                                'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                                'unidades': datacantidad,

                                'impuesto': $(".impuesto-final").eq(x).val(),

                                'nombre_producto': $(".title-detalle").eq(x).text(),

                                'product_id': $(".product_id").eq(x).val(),

                                'descuento': descuento,

                                'margen_utilidad': -1

                            }

                            productos_list.push(arregloProductos);

                        }

                    }else

                    {

                        arregloProductos = {

                            'codigo': $('.codigo-final').eq(x).val(),

                            'precio_venta':(precio),

                            'unidades': parseFloat($(".cantidad").eq(x).text()),

                            'impuesto': $(".impuesto-final").eq(x).val(),

                            'nombre_producto': $(".title-detalle").eq(x).text(),

                            'product_id': $(".product_id").eq(x).val(),

                            'descuento': descuento,

                            'margen_utilidad': 0

                        }

                        console.log("qqq7");

                        productos_list.push(arregloProductos);

                    }

                }

            }   

            else

            {

                arregloProductos = {

                    'codigo': $('.codigo-final').eq(x).val(),

                    'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                    'unidades': parseFloat($(".cantidad").eq(x).text()),

                    'impuesto': $(".impuesto-final").eq(x).val(),

                    'nombre_producto': $(".title-detalle").eq(x).text(),

                    'product_id': $(".product_id").eq(x).val(),

                    'descuento': descuento,

                    'margen_utilidad': 0

                }

                console.log("qqq8");

                productos_list.push(arregloProductos);

            }

        }); 

        

        //console.log(productos_list);

        pago = {

            valor_entregado: $("#valor_entregado").val(),

            cambio : $("#sima_cambio_hidden").val(),

            forma_pago: $("#forma_pago").val(),

            cod_gift: ""

        };



        pago_1 = {

            valor_entregado: $("#valor_entregado1").val(),

            cambio : 0,

            forma_pago: $("#forma_pago1").val(),

            cod_gift: ""

        };

        

        pago_2 = {

            valor_entregado: $("#valor_entregado2").val(),

            cambio : 0,

            forma_pago: $("#forma_pago2").val(),

            cod_gift: ""

        };                                           

        

        pago_3 = {

            valor_entregado: $("#valor_entregado3").val(),

            cambio : 0,

            forma_pago: $("#forma_pago3").val(),

            cod_gift: ""

        };                                           

        

        pago_4 = {

            valor_entregado: $("#valor_entregado4").val(),

            cambio : 0,

            forma_pago: $("#forma_pago4").val(),

            cod_gift: ""

        };                                           

        

        pago_5 = {

            valor_entregado: $("#valor_entregado5").val(),

            cambio : 0,

            forma_pago: $("#forma_pago5").val(),

            cod_gift: ""

        };



        var pagos = validarMediosDePago();



        if (!pagos.resultado){

            var errores = '';

            for(var i=0; i<pagos.errores.length; i++)

            {

                errores += pagos.errores[i]+"\n";

            }

            alert("No se pudo efectuar la compra debido a:\n\n"+errores);

            $(this).attr('disabled', false);

            e.preventDefault();

            return false;

        }



        

     







        //-----------------------------------------------

        //  GiftCard

        //-----------------------------------------------

        //

        //Si el metodo de pago es GiftCard

        cantidadFormasGift=0;       

        //añadimos los codigos de los giftcard que seran canjeados

        giftCardsFormasObjs = [];

        

        

        

        // guardamos los codigos de las giftcards seleccionadas como formas de pago

        for (var k = 0; k < 6; k++) {

            if (formaGiftObj[k] == "pagada" ){

                

                var ind;

                if( k == 0) ind = "";

                else ind = k;

                

                giftCardsFormasObjs.push( $("#valor_entregado_gift"+ind).val() );

                

                

                // agregamos el codigo de la giftcard a los metodos de pago respectivamente

                if( k == 0 ) pago.cod_gift = $("#valor_entregado_gift"+ind).val();

                if( k == 1 ) pago_1.cod_gift = $("#valor_entregado_gift"+ind).val();

                if( k == 2 ) pago_2.cod_gift = $("#valor_entregado_gift"+ind).val();

                if( k == 3 ) pago_3.cod_gift = $("#valor_entregado_gift"+ind).val();

                if( k == 4 ) pago_4.cod_gift = $("#valor_entregado_gift"+ind).val();

                if( k == 5 ) pago_5.cod_gift = $("#valor_entregado_gift"+ind).val();

                

            }

        }

        

        //  Validar que no hayan GiftCards Repetidas

        if ( giftCardsFormasObjs.length > 1){

            for (var j = 0; j < giftCardsFormasObjs.length; j++) {

                for (var k = 0; k < giftCardsFormasObjs.length; k++) {

                    if( j != k){

                        if( giftCardsFormasObjs[j] == giftCardsFormasObjs[k] ){

                            alert( "No pueden haber GiftCards repetidas" );

                            return 0;

                        }

                    }

                }

            }

        }

        

        

        

        

        //Si hay metodos giftcard validados agregamos el metodo de pago giftcard

        if( giftCardsFormasObjs.length > 0){

            

            $.ajax({

                url: $canjearGiftCard,

                dataType: 'text',

                type: 'POST',

                data: {"cards":giftCardsFormasObjs},

                error: function(jqXHR, textStatus, errorThrown ){

                    alert(errorThrown);

                },

                success: function(data){

                    //console.log(data);

                }

            }); 

            

        }

        

        //Si hay productos giftcard para vender

        if( giftCardsObjs.length > 0){

            

            $.ajax({

                url: $pagarGiftCard,

                dataType: 'text',

                type: 'POST',

                data: {"cards":giftCardsObjs},

                error: function(jqXHR, textStatus, errorThrown ){

                    alert(errorThrown);

                },

                success: function(data){

                    console.log(data);

                }

            }); 

            

        }

        

        //-----------------------------------------------

        // FIN GiftCard

        //-----------------------------------------------



        var ventaData = {

            cliente: $("#id_cliente").val(),

            productos: productos_list,

            vendedor : $("#vendedor").val(),

            vendedor_2 : $("#vendedor_2").val(),

            total_venta: $("#total").val(),

            pago: pago,

            pago_1: pago_1,

            pago_2: pago_2,

            pago_3: pago_3,

            pago_4: pago_4,

            pago_5: pago_5,

            fecha_v: $('#fecha_vencimiento_venta').val(),

            nota: $("#notas").val() ,

            descuento_general: $("#descuento_general").val()  ,

            subtotal_propina_input: $("#subtotal_propina_input").val() ,

            subtotal_input: $("#subtotal_input").val()  ,

            sobrecostos: $("#sobrecostos").val(),

            propina: $('#sobrecostos_input').val(),

            promocion: promocion,

            id_fact_espera: $("#id_fact_espera").val() 

        }   

        

        

        //Si tenemos la db local creada entonces guardamos                            

        $.ajax({

            url: $sendventas,

            dataType: 'json',

            type: 'POST',

            data: ventaData,

            error: function(jqXHR, textStatus, errorThrown ){

                alert(errorThrown);

            },

            success: function(data){

                if(data.success == true){

                    if(offline == "backup")

                    {   

                        // Eliminamos objeto giftcard para que no de error al guardar en local

                        delete ventaData["pago"]["cod_gift"];

                        delete ventaData["pago_1"]["cod_gift"];

                        delete ventaData["pago_2"]["cod_gift"];

                        delete ventaData["pago_3"]["cod_gift"];

                        delete ventaData["pago_4"]["cod_gift"];

                        delete ventaData["pago_5"]["cod_gift"];                        

                        

                        appOffline.guardarVenta(ventaData, function(){ 

                            appOffline.truncateVentas(

                                function(){

                                    imprimirFactura(data);

                                },

                                function(){

                                    imprimirFactura(data);

                                }

                            ); 

                        },

                        //FALLBACK

                        function(){ imprimirFactura(data); }

                        );

                    } else {

                        imprimirFactura(data);

                    }      

                } else {

                    alert("Ha ocurrido un error venta no creada");

                }                     

            }

        });  

     

    }

     

    $("#grabar").click(function(e){    

        grabar();

    });  

    



    $("#cancelar").click(function(){

        $("#dialog-forma-pago-form").dialog( "close" );   

    });     



    $("#grabar_plan").click(function() {

        $(this).attr('disabled', true);



        //============================================

        //

        //      VENTA PLAN SEPARE

        //

        //============================================





        $(this).attr('disabled', true);



        productos_list = new Array();



        $(".title-detalle").each(function(x) {

            var descuento = 0;



            // 

            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();



            if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())) {

                descuento = 0;

            }



            productos_list[x] = {

                'codigo': $('.codigo-final').eq(x).val(),

                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),

                'unidades': parseFloat($(".cantidad").eq(x).text()),

                'impuesto': $(".impuesto-final").eq(x).val(),

                'nombre_producto': $(".title-detalle").eq(x).text(),

                'product_id': $(".product_id").eq(x).val(),

                'descuento': descuento,

                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())

            }



            pago = {

                valor_entregado: $("#valor_entregado_plan").val(),

                cambio: 0,

                forma_pago: $("#forma_pago_plan").val()

            };



        });





        $sendventas = controladorSepare;

        



        $.ajax({

            url: $sendventas,

            dataType: 'text',

            type: 'POST',

            data: {

                cliente: $("#id_cliente").val(),

                productos: productos_list,

                vendedor: $("#vendedor").val(),

                total_venta: $("#total").val(),

                pago: pago,

                nota: $("#notas").val(),

                fecha_vencimiento: $("#fecha_vencimiento").val(),

                descuento_general: $("#descuento_general").val(),

                subtotal_propina_input: $("#subtotal_propina_input").val(),

                subtotal_input: $("#subtotal_input").val(),

                sobrecostos: $("#sobrecostos").val(),

                propina: $('#sobrecostos_input').val(),

                id_fact_espera: $("#id_fact_espera").val()



            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert(errorThrown);

            },

            success: function(data) {



                $("#dialog-forma-pago-form").dialog("close");

                alert("Plan Separe Registrado")

                window.location = $reloadThis + "?var=" + tipo_busqueda;



                /*

                if (!confirm("Desea imprimir la factura de venta?")) {

                    

                    //location.href = $reloadThis;

                    

                } else {

                    //location.href = ;

                    $.fancybox.open({

                        'width': '85%',

                        'height': '85%',

                        'autoScale': false,

                        'transitionIn': 'none',

                        'transitionOut': 'none',

                        href: $urlPrint + "/" + data.id,

                        type: 'iframe',

                        afterClose: function () {

                            // location.href =  $reloadThis+"?var="+busquedas;

                            //

                        }

                        //padding : 5

                    });

                }

                */









            }

        });

    });



    

    $('#promocion').on('change', function(e){

        if (!$("#facturasTable tr").eq(0).hasClass("nothing")) 

        {

            pasarPromocion();

            calculate();  actualizarEspera();

        }

    });





    $("#facturasTable tr").live('click', function() {

        if (!$("#facturasTable tr").eq(0).hasClass("nothing")) {

            //  alert($('.codigo').eq($(this).index()).val());

            var id_producto = $('.id_producto').eq($(this).index()).val();

            var isGiftCard = $('#facturasTable .giftcard').eq($(this).index()).val();

            var matching = $('.product_id[value="' + id_producto + '"]').index();

            var id_promocion = $('#promocion').val();

            //var promocion = obtenerPromocion

            var totalProducto = parseFloat($('.precio-real').eq($(this).index()).val()) + (parseFloat($('.precio-real').eq($(this).index()).val()) * parseFloat($('.impuesto').eq($(this).index()).val()) / 100);

            

            // matching = -1 -> aun no esta listado en la factura

            // matching =  1 -> = ya esta listado

            if (matching == -1) {



                if ($sobrecosto == 'si' && $nit != '320001127839') {

                    var nom;

                    $.ajax({

                        async: false, //mostrar variables fuera de el function 

                        url: $impuestosnom,

                        type: "POST",

                        dataType: "text",

                        data: {

                            imp: $('.impuesto').eq($(this).index()).val()

                        },

                        success: function(data) {

                            nom = data

                        }

                    });

                }



                rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + $('.precio-compra-real').eq($(this).index()).val() + "'/><input type='hidden' value='" + $('.id_producto').eq($(this).index()).val() + "' class='product_id'/><input type='hidden' class='codigo-final' value='" + $('.codigo').eq($(this).index()).val() + "'><input type='hidden' class='impuesto-final' value='" + $('.impuesto').eq($(this).index()).val() + "'><span class='title-detalle text-info'><input type='hidden' value='" + $('.impuesto').eq($(this).index()).text() + "' class='detalles-impuesto'>" + $('.nombre_producto').eq($(this).index()).text() + "</span></td>";

                rowHtml += "<td><span class='label label-success cantidad'>" + 1 + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'>";

                rowHtml += "<input type='hidden' class='promocionPrecio' value='0'><input type='hidden' class='promocionIva' value='0'></td>";

                if(validarProductoPromocion(id_promocion,$('.id_producto').eq($(this).index()).val()) && promocionTipo == 1)

                {

                    if ($sinprecio == 'si') {



                    rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-real' data-promocion='1' value='" + $('.precio-real').eq($(this).index()).val() + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + $('.precio-real').eq($(this).index()).val() + "'/></td>";



                    } else {



                        rowHtml += "<td class='contCalc'><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + $('.precio').eq($(this).index()).text() + "</span><input type='hidden' data-promocion='1' class='precio-prod-real' value='" + $('.precio-real').eq($(this).index()).val() + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + $('.precio-real').eq($(this).index()).val() + "'/></td>";

                    }

                }else

                {

                    if ($sinprecio == 'si') {



                        rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-real' value='" + $('.precio-real').eq($(this).index()).val() + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + $('.precio-real').eq($(this).index()).val() + "'/></td>";



                    } else {



                        rowHtml += "<td class='contCalc'><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + $('.precio').eq($(this).index()).text() + "</span><input type='hidden' class='precio-prod-real' value='" + $('.precio-real').eq($(this).index()).val() + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + $('.precio-real').eq($(this).index()).val() + "'/></td>";

                    }

                }

                

                rowHtml += "<td><span class='precio-calc'>" + $('.precio').eq($(this).index()).text() + "</span><input type='hidden' value='precio-calc-real' value='" + $('.precio-real').eq($(this).index()).val() + "'/></td>";

                rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='wb-trash'></span></div></a></td></td>";

                rowHtml += "</tr>";

                

                var $objDom = null;

                                        

                if ($("#productos-detail tr").eq(0).hasClass("nothing")) {

                    $("#productos-detail").html("");

                    $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");

                } else {

                    $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");

                }

                

                // Si es giftcard ocultamos los botones de cambiar precio y cantidad de el listado de productos       

                if( isGiftCard == "1" ){

                    $( $objDom[0] ).find(".cantidad").hide();

                    $( $objDom[0] ).find(".precio-prod").hide();                   

                }

                

                // agregamos el si es giftcard a la lista de productos seleccionados

                $( $objDom[0] ).find(".product_id").after('<input type="hidden" class="giftcard" value="'+ isGiftCard +'">');





            } else {

                

                // Si es giftcard no se le permitira añadir mas productos

                if( isGiftCard == "0" ){

                    parent = $('.product_id[value="' + id_producto + '"]').parent().parent().index();

                    cantidad = parseInt($('.cantidad').eq(parent).text()) + 1;

                    $('.cantidad').eq(parent).text(cantidad);

                }

                

            }



            pasarPromocion();

            calculate();

            actualizarEspera();

            

        }

    });



    $('.vendedorHideButton').click(function() {

        $('#vendedorBlock').slideToggle("slow");

    });



    $('.clienteHideButton').click(function() {

        $('#clienteBlock').slideToggle("slow");

    });



    $('#clienteBlock, #vendedorBlock').slideUp();



    /*----------------------------------------------------

    | PAGAR VENTA                                        |

    ------------------------------------------------------*/



    $("#btnGrandePagar").bind('click', function() {

        

        // Reseteamos primer metodo de pago a efectivo

        setDomGift("",0);

        

        $("#valor_entregado").removeAttr("disabled");

        $("#valor_entregado1").removeAttr("disabled");

        $("#valor_entregado2").removeAttr("disabled");

        $("#valor_entregado3").removeAttr("disabled");

        $("#valor_entregado4").removeAttr("disabled");

        $("#valor_entregado5").removeAttr("disabled");

        

        // Si el pago es estandar , se muestra el dialog

        if( pagoAutomatico == "estandar" ){

            pagar();            

        }else if( pagoAutomatico == "auto" ){

            

            $(this).unbind('click');

            

            $("#btnGrandePagar").css("cursor","not-allowed");

            $("#pagarTitulo").html("Pagando...");

            $("#btnGrandePagar").unbind( "click" );            

            

            pagar();            

            $("#dialog-forma-pago-form").parent().css("visibility","hidden");

            grabar();

            

        }



    });







    $("#planSepare").click(function() {



        $("#dialog-plan-separe-form").dialog("open");



        $('#descuento_general').val('0');



        var propina = $("#sobrecostos_input").val() || 10,

            valorTotal = $('#subtotal_input').val(),

            total = Math.ceil(parseFloat((valorTotal * propina) / 100));



        $('#propina_output').html(propina + '% - ' + total);

        $('#propina_input').val(propina);



        var propina_pro = $("#sobrecostos_input").val() || 10,

            valorTotal_pro = $('#subtotal_propina_input').val(),

            total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);



        $('#propina_output_pro_plan').html(propina_pro + '% - ' + formatDollar(total_pro));

        $('#propina_input_pro_plan').val(propina_pro);



        $('#valor_pagar_propina_plan').html(formatDollar(parseFloat($("#total").val()) + parseFloat(total_pro)));



        var valor_total_entregado = parseFloat($("#total").val()) + parseFloat(total_pro);



        $("#valor_pagar_plan").val(formatDollar(parseFloat($("#total").val())));

        $("#valor_entregado_plan").val(Math.round(valor_total_entregado));

        $("#valor_pagar_hidden_plan").val(Math.round(valor_total_entregado));

        $("#sima_cambio_plan").val(parseInt('0'));



    });



    $("#nota").click(function() {



        $("#dialog-nota-form").dialog("open");



    });



    $("#sobrecosto").click(function() {



        $("#dialog-sobrecosto-form").dialog("open");



    });







    /*----------------------------------------------------

    | VENTA PENDIENTE                                     |

     ------------------------------------------------------*/





    $("#valor_entregado, #valor_entregado1, #valor_entregado2, #valor_entregado3, #valor_entregado4, #valor_entregado5").keyup(function(e) {

        cambioVentaPendiente();

    });



    $("#dialog-forma-pago-form").dialog({

        autoOpen: false,

        //height: 400,



        height: $height,



        width: $width,



        modal: true,



        close: function() {

            $('#valor_pagar').val("");

            $('#valor_entregado').val("");

            $('#forma_pago').val("");

            $('#sima_cambio').val("");

            $("#sima_cambio_hidden").val("");

            $("#valor_pagar_hidden").val("");

        }



    });



    $("#cancelar").click(function() {

        $("#dialog-forma-pago-form").dialog("close");



    });

});









/*--------------------------------------------------

| Clientes                                         |

---------------------------------------------------*/

var cliente_selecionado = {}

var cliente_grupo = {}





var sourceAutociomplete;



if ( clientesCartera == "1")

    sourceAutociomplete = $urlclienteCartera;

else

    sourceAutociomplete = $urlcliente;





$("#datos_cliente").autocomplete({

    

    source: sourceAutociomplete,



    minLength: 1,



    select: function(event, ui) {

        cliente_selecionado = ui.item.id;

        cliente_grupo = ui.item.grupo_clientes_id;

        lista = ui.item.lista;

        $("#id_cliente").val(ui.item.id);

        $("#id_cliente_plan").val(ui.item.id);

        

        $("#otros_datos").val(ui.item.descripcion);

                

        

        // Si no esta activa la opcion de cartera en clientes les descontamos normal

        if ( clientesCartera == "0"){

            

            if (lista >= '1') {

                

                var filter = $(this).val();

                $('#lista-clientes').html('');

                $('#facturasTable tbody').html('');

                $('#cliente-titulo').html('');

                $('#search').val('');

                $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');

                $('#total-show').html('0.00');

                $('#subtotal').html('0.00');

                $('#iva-total').html('0.00');

                $('#id_lista').val(lista);

                filtrarCategoria(document.getElementById('categorias'), lista);                



            } else {

                $('#id_lista').val('0');

                filtrarCategoria(document.getElementById('categorias'), 0);

            } 

            

        }else{

            

            // CAPTURAMOS SI EL CLIENTE TIENE CARTERA O NO

            var cartera = ui.item.cartera;

            

            if( cartera == "0"){

                

                if (lista >= '1') {



                    var filter = $(this).val();

                    $('#lista-clientes').html('');

                    $('#facturasTable tbody').html('');

                    $('#cliente-titulo').html('');

                    $('#search').val('');

                    $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');

                    $('#total-show').html('0.00');

                    $('#subtotal').html('0.00');

                    $('#iva-total').html('0.00');

                    $('#id_lista').val(lista);

                    filtrarCategoria(document.getElementById('categorias'), lista);



                } else {

                    $('#id_lista').val('0');

                    filtrarCategoria(document.getElementById('categorias'), 0);

                }   

                

                $("#clienteCartera").html("");

                

            }else if( cartera == "1"){

                $("#clienteCartera").html("El cliente "+ui.item.value+" está en mora");

            }

        }

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



/*Buscar cliente

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



});

*/

//Metodo REST API -> Estado inactivo

function getClient(element) {



    if (element.selectedIndex != 0) {



        $('#nombre_comercial').html(client[element.selectedIndex - 1].nombre_comercial);

        $('#razon_social').html(client[element.selectedIndex - 1].razon_social);

        $('#nif_cif').html(client[element.selectedIndex - 1].nif_cif);

        $('#contacto').html(client[element.selectedIndex - 1].contacto);

        $('#email').html(client[element.selectedIndex - 1].email);

        $('#grupo_clientes_id').html(client[element.selectedIndex - 1].grupo_clientes_id);



        $.ajax({



            url: $url,



            data: {

                filter: '',

                cliente: client[element.selectedIndex - 1].id_cliente,

                grupo: client[element.selectedIndex - 1].grupo_clientes_id

            },



            type: "POST",



            success: function(response) {





                for (var i = 0; i < response.length; i++) {

                    $('#nombre-producto-' + response[i].id).text(response[i].nombre);

                    $('#stock_minimo-producto-' + response[i].id).text(response[i].stock_minimo);

                    $('#precio_venta-producto-' + response[i].id).text(response[i].precio_venta);

                    $('#precio-real-' + response[i].id).val(response[i].precio_venta);



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

$('#cod-container').click(function() {

    renderFactura();

    actualizarEspera();

    calculate();

});

var codigos = [];

function codificaloAddValue(){

    

    

    codigos.push();

    $("#search").val("");

    //console.log(codigos);

    function estadoGift(codigo){    

   

        $.ajax({

           url: $estadoGiftCard,

           dataType: 'json',

           type: 'post',

           data: {"codigo": codigo},

           success: function(data){

               codigos.pop();

               if( data.estado != "empty"){

                   

                    if( data.estado == "pagado"){ alert("La "+data.nombre+" ha sido vendida") }

                    if( data.estado == "cancelado"){ alert("La "+data.nombre+" ha sido canjeada") }

                   

               }else{

                   

                    $('#cod-container').fadeOut('fast');                   

                    alert("Codigo de barras no encontrado");

               }

               

           }

       });

       

    }

    

    

}







/*--------------------------------------------------

| VITRINA                                          |

---------------------------------------------------*/



var productos_categoria = null;



function filtrarCategoria(element, cliente) {

    

    $.ajax({



        url: $urlVitrina + '/' + element.id + '/' + $('#id_lista').val(),



        type: "GET",



        success: function(response) {



            productos_categoria = response;



            $('#vitrina').html('');



            for (var i = 0; i < response.length; i++) {



                var sProduct = response[i];

                var precioimpuesto =   (parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto) / 100 +parseInt(sProduct.precio_venta) );

                    

                if(sProduct.impuesto != '0')

                    total = precioimpuesto;

                else

                    total = sProduct.precio_venta;



                if (response[i].imagen == '')

                    response[i].imagen = 'product-dummy.png';



                $('#vitrina').append(

                   '<div class="vitrina-item newPanel2" onclick="categoria_producto('+i+')">'+    

                        '<img onerror="ImgError(this)"  src="'+$urlImages+'/'+response[i].imagen+'" >'+

                        '<div id="pie-item">'+

                            '<div><h5 id="item-nombre">'+response[i].nombre+'</h5></div><br>'+

                            '<div align="center" style="background: #5327AF;" ><h5 >$ '+Math.round(total)+'</h5></div>'+

                        '</div>'+

                    '</div>'

                );

            }



        }

    });

}



var offset = 0;



function siguiente_categorias() {



    offset += 3;

    $.ajax({

        type: "GET",

        url: $urlCategorias + '/' + offset

    }).done(function(response) {



        if (response.length != 0) {

            html = '<li id="0" onclick="filtrarCategoria(this)"><img onerror="ImgError(this)"  src="' + $urlImages + '/todos.jpg"><br>Todos</li>';

            html += '<li id="2" onclick="filtrarCategoria(this)"><img onerror="ImgError(this)"  src="http://www.vendty.com/invoice/uploads/general.jpg"><br>General</li>';

            for (var i = 0; i < (response.length); i++) {

                if (response[i].id != 2)

                    html += '<li id="' + response[i].id + '" onclick="filtrarCategoria(this)" ><img onerror="ImgError(this)"  src="' + $urlImages + '/' + response[i].imagen + '"><br>' + response[i].nombre + '</li>';

            };

            $('#nav-categoria').html(html);



        } else {

            offset = -3;

            siguiente_categorias();

        }



    });



}



function categoria_producto(index) {



    sProduct = productos_categoria[index];

    renderFactura();

    pasarPromocion();

    calculate();

    actualizarEspera();



}


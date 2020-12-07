function GuardarProductos(id,zona,mesa,section,orden, comensales){
    var cantidad = 1;
    orden = !(orden === "") ? orden : "";
    $("#tbl_orden tbody .tr_product").each(function(){

        var bol = $(this).find("td")[0]; /// la primera td
        var bol2 = $(bol).find("span"); /// span que contiene clase hidden // si esta en comanda o no
        var bol3 = $(bol2).hasClass("hidden"); // checkear estado
        ///debugger;

        if (section === "tablecantidad") {

            id_orden = parseInt($(this).find(".number-spinner").attr("data-id"), 10);
            if (id_orden + "" === orden + "") {
                id = $(this).data("id");
                var val = parseInt($(this).find("input").val(), 10);
                $(this).find("input").val(val);
                cantidad = val;
            }

        } else {

            if (!bol3){
                if ($(this).data("id") === id) {
                    var val2 = parseInt($(this).find("input").val(), 10);
                    if (section === "grid") {
                        cantidad = val2 + 1;
                    } else {
                        cantidad = val2;
                    }
                    $(this).find("input").val(cantidad);
                }
            }

        }
    });

    $.ajax({
        type: "POST",
        async: false,
        url: VendtyApp.handleBaseURL()+"/productos/addProductoOrden",
        data: {id:id,zona:zona,mesa:mesa,cantidad:cantidad,section:section,orden:orden,comensales:comensales}
        //contentType: 'multipart/form-data',
    })
        .done(function(){
            //renderDetalle(data)
            VendtyOrden.renderOrden(zona,mesa);
            return true;
        });
}

$(".composite-products-options ul li").click(function (event) {
    var content = $(this).data("content");

    $(".composite-products-title .title").text($(this).children("span").text());

    $(".composite-products-options ul li").removeClass("active");
    $(this).addClass("active");

    $(".composite-contents").hide();
    $(content).show();

    if (content == "#composite-content-modify") {
        $(".composite-products-title div").show();
    } else {
        $(".composite-products-title div").hide();
    }
});

var idOrder = 0;
var ingredientes = null;
var lastOrder;
var currentProduct = 0;
var loadAdditions = function(id) {

    // console.log(id);

    id = (id != 0) ? id : idOrder;

    console.log(id);

    $.getJSON(VendtyApp.handleBaseURL() + '/orden_compra/load_additions', {
        id: id
    }, function(json) {

        console.log(json);

        $(".composite-products-content.additions").html("");
        $(".composite-products-content.actives").html("");
        $(".composite-modify-content.modify").html("");

        $("#composite-products .composite-products-ingredients").html("Ingredientes: ");

        if (json.data.additions !== null) {
            $.each(json.data.additions, function (index, val) {
                $(".composite-products-content.actives").append("<span class='option active' data-id='" + val.addition.id + "' style='display: block;'>" + val.addition.nombre + "</span>");
            });
        }

        if (json.data.modifications !== null) {
            $.each(json.data.modifications, function (index, val) {
                $(".composite-modify-content.actives").append("<span class='option active' data-id='" + val.id + "' style='display: block;'>" + val.nombre + "</span>");
            });
        }

        $.each(json.data.product.additions, function(index, val) {
            $(".composite-products-content.additions").append("<span class='option' data-id='" + val.id + "'>" + val.name + "</span>");
        });

        $.each(json.data.product.ingredients, function(index, val) {
            $("#composite-products .composite-products-ingredients").append("<span class='ingredient' data-id='" + val.id + "'>" + val.nombre + "</span>");
        });

        $.each(json.data.product.modifications, function(index, val) {
            $(".composite-modify-content.modify").append("<span class='option' data-id='" + val.id + "'>" + val.nombre + "</span>");
        });
        
        console.log('adiciones'+json.data.product.additions.length+'modificaciones'+json.data.product.modifications.length);
        if(json.data.product.additions.length > 0  || json.data.product.modifications.length) {
            $("#composite-products").show();
        }
    });

    
}

$("#composite-products .composite-products-title div input").keyup(function (event) {

    var input = $(this);
    var modification = input.val();

    if (event.keyCode == 13) {
        $.post(VendtyApp.handleBaseURL() + '/orden_compra/create_new_modification', {
            product: currentProduct,
            modification: modification
        }, function(data, textStatus, xhr) {
            console.log(data);
            console.log(textStatus);
            console.log(xhr);

            if (textStatus == "success" && xhr.status == 200) {
                var json = $.parseJSON(data);
                $(".composite-modify-content.modify").append("<span class='option' data-id='" + json.id + "'>" + json.nombre + "</span>");
                input.val("");
            }
        });
    }
});

var VendtyOrden = function(){
    return {
        init:function(){
            VendtyOrden.tomarOrden();
            VendtyOrden.carouselCategorias();
            VendtyOrden.listarProductos();
            VendtyOrden.resultado();
            VendtyOrden.sidebarRight();
            VendtyOrden.renderOrden();
            //VendtyOrden.deleteAdicion()
            //VendtyOrden.renderModificacion();
            //VendtyOrden.renderAdicional();

            var top = $('html').offset().top;


            $('[data-toggle="tooltip"]').tooltip()

            var getUrl = window.location;

            var href = getUrl.pathname.split('/');
            if(href.length > 6){
                var actual = getUrl.pathname.split('/')[5];
            }else{
                var actual = getUrl.pathname.split('/')[3];
            }


            if(actual == 'mi_orden'){
                if($('.header-right').length)
                    $('header').remove();

                $('.body-content').css('padding','0px');

            }


            $('#product_category').affix({
                offset: {
                    top: function(){

                        return 50;
                    },
                    bottom: function () {
                        return (this.bottom = $('.footer').outerHeight(true))
                    }
                }
            });

            $(document).ajaxSend(function(event, request, settings) {
                $('#loading-indicator').show();
            });

            $(document).ajaxComplete(function(event, request, settings) {
                $('#loading-indicator').hide();
            });

            $(document).on('click','#btn_cancelar',function(){
                var zona = $('#txt_seccion').val();
                var mesa = $('#txt_mesa').val();
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/eliminarOrden',
                        //data:{id:$id},
                        type:'POST',
                        dataType:'json',
                        data:{zona:zona,mesa:mesa,estado:1}
                    }
                ).done(function(data){
                    VendtyOrden.renderOrden();

                });
            });

            $(document).on('click', '#nueva_modificacion', function () {
                $('#erroresm').empty();
                $('#formnuevam').toggle();
            });

            $(document).on('submit', '#form_nuevamodificacion', function (e) {
                e.preventDefault();
                pro = $("#id_producto_modificacion").val();
                orden = $("#id_orden_modificacion").val();
                modi = $.trim($("#nuevamodificacion").val());
                if ((modi.length > 0)&&(pro>0)){
                    $('#erroresm').empty();
                    $.ajax(
                        {
                            url: VendtyApp.handleBaseURL() + '/orden_compra/guardarModificacion',
                            type: 'POST',
                            dataType: 'json',
                            data: { id: pro, modi: modi }
                        }
                    ).done(function (data) {
                        if(data.status==1){
                            boton = '<button onclick="VendtyOrden.renderModificacion(this,&quot;add&quot;);" class="btn  btn-orden input-xs" data-id="' + orden + '" data-producto="' + modi + '"><b>' + modi + '</b></button>';
                            VendtyOrden.renderModificacion(boton, 'add');
                            sms = "<div class='text-success'>" + data.mensaje + "</div>";
                            $("#nuevamodificacion").val('');
                            $("#nuevamodificacion").focus();
                            $('#formnuevam').removeClass('form-group has-error');
                            $('#erroresm').empty();
                            $('#erroresm').append(sms);
                        }
                        else{
                            sms = "<div class='text-danger'>"+data.mensaje+"</div>";
                            $('#formnuevam').addClass('form-group has-error');
                            $('#erroresm').empty();
                            $('#erroresm').append(sms);
                            //$("#nuevamodificacion").val('');
                            $("#nuevamodificacion").focus();
                        }
                    });
                }else{
                    $("#nuevamodificacion").val('');
                    $("#nuevamodificacion").focus();
                    sms = "<div class='text-danger'>El campo no puede estar vacío</div>";
                    $('#formnuevam').addClass('form-group has-error');
                    $('#erroresm').empty();
                    $('#erroresm').append(sms);
                }
            });

            $(document).on('click','#btn_confirmarNew',function(){
                var zona = $('#txt_seccion').val();
                var mesa = $('#txt_mesa').val();
                var notacomanda = $('#message-nota').val();
                var comensales = $('#txt_comensales').val();
                var token = JSON.parse(localStorage.getItem('api_auth'))
                token = token ? token.token : "";

                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/confirmarOrden',
                        //data:{id:$id},
                        type:'POST',
                        dataType:'json',
                        data: {
                            zona: zona,
                            mesa: mesa,
                            notacomanda: notacomanda,
                            comensales: comensales,
                            token: token
                        }
                    }
                ).done(function(data){
                    $('#message-nota').val('');
                    $('#txt_comensales').val('');
                    $('#orden_consecutivo').empty();
                    $('#orden_consecutivo').html(data.orden_consecutivo);
                    VendtyOrden.renderOrden();
                    
                    /*$.post(VendtyApp.handleBaseURL()+'/orden_compra/get_order_products',{
                        zona:zona,
                        mesa:mesa
                    },function(data){
                       // console.log(data);
                       let valor_total_comanda = data.total;
                        $("#valor_recibido").val(valor_total_comanda);
                        $("#valor_recibido").attr('data-total',valor_total_comanda);
                        $(".total-payment").html(symbol + valor_total_comanda);
                        help_pay_amounts();
                        //alert(Math.round(valor_total_comanda));
                    });*/
                    
                    
                    /*Swal({
                        title: 'Desea generar la factura?',
                        text: "Presione pagar para seleccionar el método de pago",
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#5cb85c',
                        cancelButtonColor: '#d9534f',
                        confirmButtonText: 'Si, pagar!',
                        cancelButtonText: 'Cancelar'
                      }).then((result) => {
                        if (result.value) {
                            if(zona == -1 && quick_service == 'si'){
                                //url="<?php echo site_url('get_order_product_restaurant')?>";
                                get_order_product_restaurant(zona,mesa);
                                open_modal_payment();
                            }else{
                                    $.ajax({
                                    url:VendtyApp.handleBaseURL()+'/orden_compra/getOrden',
                                    type:'POST',
                                    dataType:'json',
                                    data:{zona:zona,mesa:mesa},
                                    success: function(data){
                                        // console.log(data);
                                        // console.log(data.fecha_orden);
                                        tengo=0;
                                        $.each(data.orden,function(key,value){
                                            if((value.estado == 2)||(value.estado == 3)){
                                                tengo=1;
                                            }                    
                                        });
                    
                                        if(tengo==0){
                                            swal(
                                                'Alerta',
                                                'No Posee pedidos en comanda.',
                                                'warning'
                                            ) 
                                            close_modal_payment();
                                        }else{
                                            get_order_product_restaurant(zona,mesa);
                                            open_modal_payment();
                                            $("#valor_recibido").select();
                                        }
                                    }
                                });
                            }
                            
                        }
                      })*/
                    
                });
            });


        },
        deleteAdicional:function(adicional,id){
            $.ajax({
                type: 'POST',
                async: false,
                url: VendtyApp.handleBaseURL()+'/orden_compra/eliminarAdicion',
                data: {id:id,val:adicional,type:2},
                //contentType: 'multipart/form-data',
            })
                .done(function(data){
                    VendtyOrden.renderOrden();
                });
        },
        deleteModificacion:function(modificacion,id){
            // console.log(modificacion);
            $.ajax({
                type: 'POST',
                async: false,
                url: VendtyApp.handleBaseURL()+'/orden_compra/eliminarAdicion',
                data: {id:id,val:modificacion,type:1},
                //contentType: 'multipart/form-data',
            })
                .done(function(data){
                    VendtyOrden.renderOrden();
                });
        },

        renderModificacion:function(domItem,tipo){
            var button = $(domItem);
            var id = button.data('id');
            var producto = button.data('producto');
            $(domItem).parent().detach();

            if(tipo == 'add'){
                var html = '<div><button onclick="VendtyOrden.renderModificacion(this,&quot;del&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+producto+'"><b>'+producto+'</b></button></div>';
                $("#producto-modificado").append( html );
                guardarModificacion(id,producto);
            }else if(tipo == 'del'){
                var html = '<div><button onclick="VendtyOrden.renderModificacion(this,&quot;add&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+producto+'"><b>'+producto+'</b></button></div>';
                $("#producto-sinmodificar").append( html );
                VendtyOrden.deleteModificacion(producto,id);
            }

            function guardarModificacion(id,producto){
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/addModificacion',
                        //data:{id:$id},
                        type:'POST',
                        dataType:'json',
                        data:{id:id,producto:producto}
                    }
                ).done(function(data){
                    VendtyOrden.renderOrden();

                });
            }


        },

        renderAdicional:function(domItem,tipo){
            var button = $(domItem);
            var id = button.data('id');
            var producto = button.data('producto');
            var nombre = button.data('nombre');
            $(domItem).parent().detach();
            if(tipo == 'add'){
                var html = '<div><button onclick="VendtyOrden.renderAdicional(this,&quot;del&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+producto+'" data-nombre="'+nombre+'"><b>'+nombre+'</b></button></div>';
                $("#producto-adicionado").append( html );
                guardarAdicional(id,producto);
            }else if(tipo == 'del'){
                var html = '<div><button onclick="VendtyOrden.renderAdicional(this,&quot;add&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+producto+'" data-nombre="'+nombre+'"><b>'+nombre+'</b></button></div>';
                $("#producto-sinadicionar").append( html );
                VendtyOrden.deleteAdicional(producto,id);
            }
            function guardarAdicional(id,producto){
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/addAdicional',
                        //data:{id:$id},
                        type:'POST',
                        dataType:'json',
                        data:{id:id,producto:producto}
                    }
                ).done(function(data){
                    VendtyOrden.renderOrden();

                });
            }
        },
        renderOrden:function(zona,mesa){
            var zona = $('#txt_seccion').val();
            var mesa = $('#txt_mesa').val();
            if((zona==-1) &&(mesa==-1)){
                $("#tbl_orden").css('width','98%');
                $("#tbl_orden").css('margin-left','auto');
            }
            if ((zona != undefined) && (mesa != undefined)){
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/getOrden',
                        //data:{id:$id},
                        type:'POST',
                        dataType:'json',
                        data:{zona:zona,mesa:mesa},
                        beforeSend:function(){
                            $('#tbl_orde tbody').html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');
                        }
                    }
                ).done(function(data){
                    $('#orden_result').html('<b><h3>'+data.simbolo+''+data.valor_orden+'</h3></b>')
                    $('#fecha_orden').html(data.fecha_orden);
                    //$('#valor_recibido').val(data.valor_orden);
                    //$('.total-payment').html(symbol+formatNumber(data.valor_orden));
                    var table = $('#tbl_orden tbody');
                    table.empty();
                    totalfactura = 0;
                    $.each(data.orden,function(key,value){

                        var imp = '';
                        hidden = "";
                        disabled = "";

                        ///busco precio real del producto, adiciones y modificaciones
                        adiciones = "";
                        modificaciones = "";
                        totaladiciones = 0;
                        // totalvalorp = parseFloat(value.precio_ventasin);
                        nombrepa=value.nombre;

                        //modificaciones
                        if (value.modificacion != null) {
                            modificaciones+="<br>Modificaciones(";
                            $.each(value.modificacion, function (key, val) {
                                modificaciones+= val + ",";
                                subrow += '<tr><td>' + val + '</td><td></td><td>' +
                                    '<button onclick="VendtyOrden.deleteModificacion(&quot;' + val + '&quot;,' + value.id + ')" data-id="' + value.id + '" data-producto="' + value.id_producto + '" data-value="' + val + '" data-type="1" type="button" class="' + hidden + ' btn btn-danger btn-xs"><span class="fa fa-trash" aria-hidden="true"></span></button></td></tr>';
                            });
                            modificaciones+= ") ";
                        }

                        //adiciones
                        if (value.adicionales != null) {
                            adiciones += " Adiciones (";
                            $.each(value.adicionales, function (key, val) {

                                adiciones += val.nombre + ",";
                                totaladiciones += parseFloat(val.precio_venta);
                                subrow += '<tr><td>' + val.nombre + '</td><td>' + val.precio_venta + '</td><td>' +
                                    '<button  type="button" onclick="VendtyOrden.deleteAdicional(' + val.id_adicional + ',' + value.id + ')" data-id="' + value.id + '" data-producto="' + value.id_producto + '" data-value="' + val.id_adicional + '" data-type="2" class="' + hidden + ' btn btn-danger btn-xs"><span class="fa fa-trash" aria-hidden="true"></span></button></td></tr>';
                            });
                            adiciones += ")";
                        }
                        if (totaladiciones > 0) {
                            // totalvalorp += parseFloat(totaladiciones);
                            nombrepa += '<span style="font-weight: normal !important;">' + modificaciones + '</span>' + '<br/><span style="font-weight: normal !important;">' + adiciones + '</span>';
                        }
                        //totalfactura += (totalvalorp * parseFloat(value.cantidad));
                        if ((value.estado == 2) || (value.estado == 3) || (value.estado == 4)) { $("#btn_cancelar").attr("disabled",true); disabled="disabled"; hidden="hidden"; imp = '<i class="fa fa-print"></i>';}
                        var row =   $('<tr class="tr_product" data-id="'+value.id_producto+'">'+
                            '<td><span class="'+hidden+' fa-stack fa-danger fa-lg" style="padding-left: 10px" data-id="'+value.id+'" data-producto="'+value.id_producto+'">'+
                            '<i class="fa fa-close fa-lg" style="color: #505050; cursor: pointer"></i>'+
                            '</span></td>'+
                            '<td width="40%"><p><span style="font-weight: bold"> ' + nombrepa+ ' </span> <span> '+imp+' </span> </p>'+
                            '<span class="tr_price" id="precio_' + value.id + '" data-value="' + value.precio_ventaptotal + '" style="color: #2a4157">'+value.simbolo+' '+ value.precio_ventaptotal +'</span>'+
                            '</td>'+
                            '<td width="30%">'+
                            '<div class="input-group number-spinner" data-id="' + value.id+'">'+
                            '      <span class="'+hidden+' input-group-btn data-dwn">'+
                            '          <button class="btn btn-default btn-danger number" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>'+
                            '      </span>'+
                            '      <input type="text" disabled class="form-control cantidad text-center" value="'+value.cantidad+'" min="1" max="40">'+
                            '     <span class="'+hidden+' input-group-btn data-up">'+
                            '        <button class="btn btn-default number" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>'+
                            '    </span>'+
                            '</div>'+
                            '</td>'+
                            '<td>'+
                            //'<a id="sidebar-toggle" data-id="'+value.id+'" data-producto="'+value.id_producto+'" class="'+hidden+' btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-pencil"></i></a>'+
                            '<a data-id="'+value.id+'" data-producto="'+value.id_producto+'" class="'+hidden+' btn btn-success btn-xs open-composite-products" data-toggle="tooltip" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-pencil"></i></a>'+
                            '<a class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-target="#collapse_'+value.id+'" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-chevron-down"></i></a></td>'+
                            '</tr>');
                        var subrow = '<tr id="collapse_'+value.id+'" data-producto="collapse_'+value.id+'" class="collapse"><td colspan="4">' +
                            '<div class="containerTableModap"><div class="tableModap"><table class="table table-striped" style="background-color: white">';

                        if(value.modificacion != null){
                            $.each(value.modificacion,function(key,val){

                                subrow += '<tr><td>' + val + '</td><td></td><td>'+
                                    '<a style="float: right; cursor: pointer" onclick="VendtyOrden.deleteModificacion(&quot;'+val+'&quot;,'+value.id+')" data-id="'+value.id+'" data-producto="'+value.id_producto+'" data-value="'+val+'" data-type="1" class="'+hidden+'"><span style="color: red" class="fa fa-trash" aria-hidden="true"></span></a></td></tr>';
                            });
                        }

                        if(value.adicionales != null){
                            $.each(value.adicionales,function(key,val){
                                subrow += '<tr><td>'+val.nombre+'</td><td style="font-weight: bold">$ '+val.precio_venta+'</td><td>'+
                                    '<a style="float: right;" type="button" onclick="VendtyOrden.deleteAdicional('+val.id_adicional+','+value.id+')" data-id="'+value.id+'" data-producto="'+value.id_producto+'" data-value="'+val.id_adicional+'" data-type="2" class="'+hidden+'"><span style="color: red" class="fa fa-trash" aria-hidden="true"></span></a></td></tr>';
                            });

                        }
                        subrow += "</td></table></div></div></tr>";

                        idOrder = value.id;
                        // console.log("-------------------" + idOrder + "-------------------");
                        // loadAdditions(idOrder);

                        $('#tbl_orden').removeClass('hidden');
                        table.append(row);
                        table.append(subrow);
                    });

                });
            }


            $(document).off('click', '.number');
            $(document).one('click', '.number', function (e) {

                e.preventDefault();
                id = "";
                btn = $(this);
                btn.prop("disabled", true);
                input = btn.closest('.number-spinner').find('input');
                id_orden = btn.closest('.number-spinner').attr('data-id');
                btn.closest('.number-spinner').find('button').prop("disabled", false);

                if (btn.attr('data-dir') == 'up') {
                    if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                        input.val(parseInt(input.val()) + 1);
                    } else {
                        btn.prop("disabled", true);
                    }

                } else {
                    if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                        input.val(parseInt(input.val()) - 1);
                    } else {
                        btn.prop("disabled", true);
                    }
                }
                //guardar
                VendtyOrden.resultado();

                GuardarProductos(id, $('#txt_seccion').val(), $('#txt_mesa').val(), "tablecantidad", id_orden, $('#txt_comensales').val());
            });

            $(document).on('click','#btn_del_adicion',function(){
                var id = $(this).data('id');
                var id_producto = $(this).data('producto');
                var val = $(this).data('value');
                var type = $(this).data('type');
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: VendtyApp.handleBaseURL()+'/orden_compra/eliminarAdicion',
                    data: {id:id,id_producto:id_producto,val:val,type:type},
                    //contentType: 'multipart/form-data',
                })
                    .done(function(data){
                        VendtyOrden.renderOrden();
                    });
            });
        },

        tomarOrden:function(){
            var action;
            var bandera = 0;
            $('#slt_producto').select2({

                ajax: {
                    url:VendtyApp.handleBaseURL()+'/ProductoRestaurant/getAjaxProductsLike',
                    dataType:'json',
                    delay:250,
                    quietMillis:50,
                    data:function(params){
                        return {
                            q:params.term,
                            page:params.page
                        }
                    },
                    processResults:function(data,params){
                        params.page = params.page || 1;
                        return {
                            results:data.items,
                            pagination:{
                                more:(params.page * 30) < data.count
                            }
                        }
                    },
                    /*processResults:function(data){
                        return {
                            results : $.map(data.items,function(items){
                                return {
                                    name:items.name,
                                    id:items.id
                                }
                            })
                        };
                    },*/
                    cache:true
                },
                dropdowCssClass:'bigdrop',
                escapeMarkup:function(markup){return markup;},
                minimunInputLenght:1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });
            function formatRepoSelection (repo) {





                //producto_text = repo.name;
                //var selected = "<input type='hidden' name='ingrediente[material]["+counter+"]' value='"+repo.id+"'>";
                //return selected;
                return repo.name;
            }

            function formatRepo(repo){

                bandera = bandera + 1;

                if(bandera > 0)
                    $('#tbl_orden').removeClass('hidden');

                var $option = $('<span></span>')
                var $preview = $('<a target="_blank"> Agregar a la orden</a>');

                $preview.prop("href",repo.id);

                $preview.on('mouseup', function (evt) {

                    evt.stopPropagation();
                });

                $preview.on('click', function (evt) {
                    evt.preventDefault();
                    var table = $('#tbl_orden tbody');
                    var row =   $('<tr class="tr_product" data-id="'+repo.id+'">'+
                        '<td width="30%"><p>'+repo.name+'</p><span class="tr_price" data-value="'+repo.precio_venta+'">$'+repo.precio_venta+'</span></td>'+
                        '<td width="40%">'+
                        '<div class="input-group number-spinner">'+
                        '      <span class="input-group-btn data-dwn">'+
                        '          <button class="btn btn-default btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>'+
                        '      </span>'+
                        '      <input type="text" class="form-control text-center" value="1" min="1" max="40">'+
                        '     <span class="input-group-btn data-up">'+
                        '        <button class="btn btn-default btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>'+
                        '    </span>'+
                        '</div>'+
                        '<td><a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Adicionales" title="Adicionales"><i class="fa fa-eye"></i></a>'+
                        '<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-pencil"></i></a></td>'+
                        '</td>'+
                        '</tr>');

                    ordenarProducto(repo.id);

                    $spinner = row.find('.number-spinner button');
                    $spinner.mousedown(function () {
                        btn = $(this);
                        input = btn.closest('.number-spinner').find('input');
                        btn.closest('.number-spinner').find('button').prop("disabled", false);

                        if (btn.attr('data-dir') == 'up') {
                            action = setInterval(function(){
                                if ( input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max')) ) {
                                    input.val(parseInt(input.val())+1);
                                }else{
                                    btn.prop("disabled", true);
                                    clearInterval(action);
                                }
                            }, 50);
                        } else {
                            action = setInterval(function(){
                                if ( input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min')) ) {
                                    input.val(parseInt(input.val())-1);
                                }else{
                                    btn.prop("disabled", true);
                                    clearInterval(action);
                                }
                            }, 50);
                        }
                    }).mouseup(function(){
                        resultado();
                        clearInterval(action);
                    });
                    table.append(row);
                    resultado();
                });



                $option.text(repo.name);
                $option.append($preview);

                return $option;


            }

            function ordenarProducto(id){
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: VendtyApp.handleBaseURL()+'/orden_compra/storeProductOrden',
                    data: id,
                    //contentType: 'multipart/form-data',
                })
                    .done(function(data){

                    });
            }

            function resultado(){
                // //console.log("llego");z
                var $linea_orden = $(document).find('#tbl_orden tbody tr');
                var contador,monto_acumulado = 0;
                $('#tbl_orden tbody tr').each(function(index){
                    var monto,operacion,cantidad = 0;
                    $(this).children("td").each(function(index2){
                        switch(index2){
                            case 0:
                                monto = $(this).find('span').data('value');
                                break;
                            case 1:
                                cantidad = $(this).find('input').val();
                                break;
                        }
                        operacion = monto * cantidad;
                    });

                    monto_acumulado = monto_acumulado + operacion;

                    /*contador = contador + 1;
                    // console.log(index); */
                });

                $('#orden_result').html('<h3>$ '+monto_acumulado+'</h3>');

                // //console.log(monto_acumulado);
            }

        },
        carouselCategorias:function(){
            $(document).on('click','.fa-angle-right',function(e){
                e.preventDefault();
                $linkNext = $(this).parent();
                $page = $(this).parent().data('href');
                // //console.log(VendtyApp.handleBaseURL());
                //$category = $('#idOption').data('category');
                // //console.log($page);
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/getAjaxCategorias',
                        data:{offset:$page},
                        type:'GET',
                        dataType:'json',
                        success: function(data){
                            var $next_page = null;
                            var $final_page = data.final_page;
                            // console.log($page);
                            $html = '';
                            $html += '<li>'+
                                '   <a href="" style="cursor: pointer" class="prev-category" data-href="'+$page+'"><i class="fa fa-angle-left"></i></a>'+
                                '</li>';
                            $.each(data.categorias,function(index,value){
                                //$html += '<li><a class="category-option" data-id="'+value.id+'">'+value.nombre+'</a></li>' ;
                                $html += '<li><a href=""><img width="50px" height="50px" src="/uploads/'+value.imagen+'" alt=""><span>'+value.nombre+'</span></a></li>';
                            });
                            $next_page = data.next_page;
                            if($page >= $final_page){
                                $html += '<li><a disabled></a></li>';
                            }else{
                                $html += "<li><a href='#' style='cursor: pointer'  data-href='"+$next_page+"'><i class='fa fa-angle-right'></i></a></li>";
                            }
                            $('.nav-list').html($html);
                            /*if($linkNext.hasClass('next-category')){
                                $('.calendar-content-category').html(data);
                            }
                            if($linkNext.hasClass('next-recipes')){
                                $('.recipes-content').html(data);
                                drag();
                                drop();
                            }*/
                            //active = $(selector).find('a[data-id='+$id+']');
                            //$(active).parent().addClass('active');  
                        },
                        error:function(error){
                            // console.log('status: '+error.status+', message: '+error.statusText);
                        },
                        statusCode: {
                            422: function() {
                                alert( "page not found" );
                            }
                        }
                    }
                );

            });

            $(document).on('click','.fa-angle-left',function(e){
                e.preventDefault();
                $linkNext = $(this).parent();
                $page = $(this).parent().data('href');
                $last_page = $page - 1;

                // console.log($last_page);
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/orden_compra/getAjaxCategorias',
                        data:{offset:$last_page},
                        type:'GET',
                        dataType:'json',
                        success: function(data){
                            var $next_page = null;
                            $html = '';
                            if($last_page <= 1) {
                                $html += '<li><a disabled></a></li>';
                            } else {
                                $html += '<li>'+
                                    '   <a href="" style="cursor: pointer" class="prev-category" data-href="'+$last_page+'"><i class="fa fa-angle-left"></i></a>'+
                                    '</li>';
                            }
                            $.each(data.categorias,function(index,value){
                                $html += '<li><a href=""><img width="50px" height="50px" src="/uploads/'+value.imagen+'" alt=""><span>'+value.nombre+'</span></a></li>';

                            });
                            $next_page = data.next_page;
                            $html += "<li><a href='#' style='cursor: pointer' class='next-recipes' data-href='"+$next_page+"'><i class='fa fa-angle-right'></i></a></li>";

                            $('.nav-list').html($html);
                            /*if($linkNext.hasClass('next-category')){
                                $('.calendar-content-category').html(data);
                            }
                            if($linkNext.hasClass('next-recipes')){
                                $('.recipes-content').html(data);
                                drag();
                                drop();
                            }*/
                            //active = $(selector).find('a[data-id='+$id+']');
                            //$(active).parent().addClass('active');  
                        },
                        error:function(error){
                            // console.log('status: '+error.status+', message: '+error.statusText);
                        },
                        statusCode: {
                            422: function() {
                                alert( "page not found" );
                            }
                        }
                    }
                );
            });



        },
        listarProductos: function(){

            listarProductos($id = 0,function(values){
                renderProductos(values)
            });
            $(document).on('click','.category-option',function(e) {

                $id = $(this).data('id');
                //$(selector).removeClass('active');

                listarProductos($id,function(values){
                    renderProductos(values)
                });
            });

            $(document).on('keyup', '#buscarproducto', function (e) {
                productob = $(this).val();
                if (productob!=""){
                    $.ajax(
                        {
                            url: VendtyApp.handleBaseURL() + '/productos/productos_filter_group',
                            //data:{id:$id},
                            type: 'POST',
                            dataType: 'json',
                            data: { filter: productob, type: 'buscalo', cliente: 0, grupo: 0 },
                            success: function (values) {
                                renderProductos(values);
                            }
                        });
                }else{
                    listarProductos($id = 0, function (values) {
                        renderProductos(values)
                    });
                }
            });
            function renderProductos(values){
                var $html = '';
                $.each(values,function(index,value){

                    value.precio_venta = (value.precio_venta != null) ? value.precio_venta : 0;
                    impuesto = (value.impuesto != null) ? value.impuesto : 0;
                    precioimpuesto = parseFloat(value.precio_venta);

                    if (__decimales__ == 0) {
                        precioimpuesto = (parseFloat(value.precio_venta) + (parseFloat(value.precio_venta) * (parseFloat(value.impuesto) / 100)));
                    } else {
                        //precioimpuesto = (parseFloat(value.precio_venta) * (parseFloat(value.impuesto) / 100) + parseFloat(value.precio_venta));
                        precioimpuesto = (parseFloat(value.precio_venta) * parseFloat(value.impuesto) / 100 + parseFloat(value.precio_venta));
                    }
                    valorTotal =mostrarNumero(precioimpuesto);

                    if(value.imagen == ''){
                        imagen = window.location.href;
                    }

                    $html += '<div class="col-md-3 col-sm-3 col-xs-6"><div class="panel panel-default rounded shadow">'+
                        '<div class="panel-body no-padding titulo-producto" data-impuesto="' + value.impuesto + '" data-precio="' + value.precio_venta + '" data-name="' + value.nombre + '" data-id="' + value.id + '" data-vendernegativo="' + value.vendernegativo + '" data-stock="'+ value.uni + '"  style="background-image:url(&quot;' + (value.imagen != " " ? value.imagen : imagen) +'&quot;) !important; background-size:cover !important; background-position:center !important;">'+
                        '<a>'+
                        //'<img data-no-retina="" style="height:100px;text-align:center;" width="100%" src="'+(value.imagen != " " ? value.imagen : "/uploads/img/product-dummy.png")+'" class="img-responsive full-width">'+
                        '</a><div class="tooltip-precio">' + value.simbolo+' '+valorTotal+'</div></div>'+
                        '<div class="panel-footer" style="height:40px; padding: 0px;">'+
                        '<h5 style="text-align:center;white-space: normal !important;"><a style="color:#777;font-size:10px;" class="titulo-producto" data-precio="' + value.precio_venta + '" data-name="' + value.nombre + '" data-id="' + value.id + '" data-vendernegativo="' + value.vendernegativo + '" data-stock="'+ value.uni + '">' + value.nombre.toUpperCase() +'</a></h5>'+
                        '</div></div></div>';
                });

                $('.thumb-productos').html($html);
            }

            function listarProductos($id = 0,callback){
                var id = $id;
                $.ajax(
                    {
                        url:VendtyApp.handleBaseURL()+'/productos/get_by_category/'+$id+'/0/-/0',
                        //data:{id:$id},
                        type:'GET',
                        dataType:'json',
                        success: callback
                    }
                );
            }

            /*$(document).on('click','.accordion-toggle',function(){
                // console.log("asdad");
                var subtable = $('<tr id="collapse_701" class="collapse"><td colspan="4">'+
                '<div>'+
                '<table id="sub-table" class="table">'+
                '    <tbody>'+
                '        <tr class="default"><td></td>'+
                '        <td colspan="3"><p>Sin Cebolla</p></td>'+
                '        <tr class="default"><td></td>'+
                '        <td colspan="2"><p>Tocineta</p></td><td>$34.57</td>'+
                '        </tr>'+
                '    </tbody>'+
                '    </table>'+
                '</div>'+
                '</td></tr>');
                $('#tbl_orden').find("tr[data-id='701']").after(subtable);
            });*/

            $(".composite-products-close").click(function (event) {
                $("#composite-products").hide();
                $(".composite-products-content.additions").html("");
                $(".composite-products-content.actives").html("");

                $(".composite-modify-content.modify").html("");
                $(".composite-modify-content.actives").html("");
            });

            $('#tbl_orden tbody').on('click', '.tr_product td .open-composite-products', function (event) {
                idOrder = $(this).data('id');
                currentProduct = $(this).data('producto');
                loadAdditions(idOrder);
            });

            //Guardamos el producto seleccionado
            $(document).on('click','.titulo-producto',function(){
                //debugger;
                var id = $(this).data('id');
                var zona = $('#txt_seccion').val();
                var mesa = $('#txt_mesa').val();
                var stock = $(this).data('stock');
                var vendernegativo = $(this).data('vendernegativo');

                if(stock <= 0 && vendernegativo == 0){
                    swal(
                        'Alerta',
                        'El producto no tiene stock y la opción vender negativo no esta activa.',
                        'error'
                    );
                    return;
                }
                
                //setTimeout(function () {
                    $.getJSON(VendtyApp.handleBaseURL() + '/orden_compra/get_last_order', {
                        product: id,
                        zone: zona,
                        table: mesa
                    }, function(json, textStatus) {
                        console.log(json);
                        console.log(textStatus);
                        if (json.data.product.ingredientes == 1 || json.data.product.combo == 1) {
                            lastOrder = json;
                            currentProduct = lastOrder.data.product.id;
                            loadAdditions(json.data.id);
                        }
                    });
                //}, 1000);

                GuardarProductos(id,zona,mesa,"grid",function(values){}, $('#txt_comensales').val());
                var id = $(this).data('id');
                var name = $(this).data('name');
                var precio_venta = $(this).data('precio');
                var impuesto = $(this).data('impuesto');
                precio_venta = precio_venta * parseFloat("1."+impuesto);
                var table = $('#tbl_orden tbody');
                var row =   $('<tr class="tr_product" data-id="'+id+'">'+
                    '<td><span class="fa-stack fa-danger fa-lg">'+
                    '<i class="fa fa-circle fa-stack-2x"></i>'+
                    '<i class="fa fa-close fa-stack-1x fa-inverse"></i>'+
                    '</span></td>'+
                    '<td width="40%"><p>'+name+'</p><span class="tr_price" data-value="'+precio_venta+'">$'+precio_venta+'</span></td>'+
                    '<td width="30%">'+
                    '<div class="input-group number-spinner">'+
                    '      <span class="input-group-btn data-dwn">'+
                    '          <button class="btn btn-default btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>'+
                    '      </span>'+
                    '      <input type="text" class="form-control text-center" value="1" min="1" max="40">'+
                    '     <span class="input-group-btn data-up">'+
                    '        <button class="btn btn-default btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>'+
                    '    </span>'+
                    '</div>'+
                    '</td>'+
                    '<td>'+
                    '<a id="sidebar-toggle" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-pencil"></i></a>'+
                    '<a class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-target="#collapse_'+id+'" data-placement="top" title="Modificaciones" data-original-title="Modificaciones"><i class="fa fa-chevron-down"></i></a></td>'+
                    '</tr><tr id="collapse_'+id+'" data-producto="collapse_'+id+'" class="collapse default">'+
                    '<td colspan="4"><div  class="col-md-12" data-producto="collapse_'+id+'"></div></td>'+
                    '</tr>');
                $('#tbl_orden').removeClass('hidden');

                //table.append(row);
                //var tds=$("#tbl_orden tr:first td").length;
                // //console.log(tds);


                $spinner = row.find('.number-spinner button');

                $spinner.mousedown(function () {
                    btn = $(this);
                    input = btn.closest('.number-spinner').find('input');
                    btn.closest('.number-spinner').find('button').prop("disabled", false);

                    if (btn.attr('data-dir') == 'up') {

                        action = setInterval(function(){
                            if ( input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max')) ) {
                                input.val(parseInt(input.val())+1);
                            }else{
                                btn.prop("disabled", true);
                                clearInterval(action);
                            }
                        }, 50);
                    } else {
                        action = setInterval(function(){
                            if ( input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min')) ) {
                                input.val(parseInt(input.val())-1);
                            }else{
                                btn.prop("disabled", true);
                                clearInterval(action);
                            }
                        }, 50);
                    }

                }).mouseup(function(){
                    VendtyOrden.resultado();
                    clearInterval(action);
                });

                VendtyOrden.resultado();
            });
            //$('.accordion-toggle').trigger('click');

            //Eliminar producto de la orden 
            $(document).on('click','#tbl_orden tbody .fa-danger',function(){
                var id = $(this).data('id');
                var id_producto = $(this).data('producto');
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: VendtyApp.handleBaseURL()+'/orden_compra/eliminarProducto',
                    data: {id:id,id_producto:id_producto},
                    //contentType: 'multipart/form-data',
                })
                    .done(function(data){
                        VendtyOrden.renderOrden();
                    });

            });
        },
        resultado:function(){
           // // console.log("llego");
            var $linea_orden = $(document).find('#tbl_orden tbody tr');
            var contador,monto_acumulado = 0;
            $('#tbl_orden tbody .tr_product').each(function(index){
                var monto,operacion,cantidad = 0;
                monto = $(this).find('.tr_price')[0].dataset.value.replace(',', '');
                cantidad = $(this).find('input').val();
                operacion = monto * cantidad;
                /*$(this).children("td").each(function(index2){
                    switch(index2){
                        case 0:
                            // console.log("asdad");
                        break;
                        case 1:
                            monto = $(this).find('span').data("value");
                        break;
                        case 2:
                            cantidad = $(this).find('input').val();
                        break;
                    }
                    operacion = monto * cantidad;
                });*/

                monto_acumulado = (monto_acumulado + operacion)*1.0;
                /*contador = contador + 1;
                // console.log(index); */
            });

            $('#orden_result').html('<h3>$ '+monto_acumulado+'</h3>');


        },
        sidebarRight:function(zonaP,mesaP){
            $(document).on('click','#sidebar-toggle',function(){

                $('#erroresm').empty();
                $('#formnuevam').empty();
                $('#formnuevam').hide();
                $('.sidebar-right').toggleClass('sidebar-open-effect');
                /*if($('.page-sidebar-minimize.page-sidebar-right-show').length){
                    //$('body').toggleClass('page-sidebar-minimize page-sidebar-right-show');
                }
                else if($('.page-sidebar-minimize').length){
                    $('body').toggleClass('page-sidebar-right-show');
                }
                /*}else{
                    $('body').toggleClass('page-sidebar-minimize page-sidebar-right-show');
                }*/
                var id = $(this).data('id');
                var producto = $(this).data('producto');
                getDetalles(producto,id);

            });

            function getDetalles(producto,id){
                //Obtenemos el detalle de un producto segun la seccion y la mesa
                var getUrl = window.location;

                var href = getUrl.pathname.split('/');
                if(href.length > 6){
                    var zonaP = getUrl.pathname.split('/')[6];
                    var mesaP = getUrl.pathname.split('/')[7];
                }else{
                    var zonaP = getUrl.pathname.split('/')[4];
                    var mesaP = getUrl.pathname.split('/')[5];
                }


                $.ajax({
                    type: 'POST',
                    async: false,
                    url: VendtyApp.handleBaseURL()+'/productos/getDetalles',
                    data: { id: producto, zona: zonaP, mesa: mesaP, id_orden: id},
                    //contentType: 'multipart/form-data',
                })
                    .done(function(data){
                        renderDetalle(data,id,producto)
                    });
            }

            function renderDetalle(data,id,producto){
                $('#producto-modificado').empty();
                $('#producto-sinmodificar').empty();
                $('#formnuevam').empty();
                $('#producto-adicionado').empty();
                $('#producto-sinadicionar').empty();
                var modificacion = $('#producto-sinmodificar');
                var adicional = $('#producto-sinadicionar');
                var action = '';
                var html_modificacion = '';

                $.each(data.modificaciones,function(k,value){
                    if(value.in_orden)
                    {
                        html_modificacion = '<div><button onclick="VendtyOrden.renderModificacion(this,&quot;del&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+value.nombre+'"><b>'+value.nombre+'</b></button></div>';
                        $('#producto-modificado').append(html_modificacion);
                    }else{
                        html_modificacion = '<div><button onclick="VendtyOrden.renderModificacion(this,&quot;add&quot;);" class="btn  btn-orden input-xs" data-id="'+id+'" data-producto="'+value.nombre+'"><b>'+value.nombre+'</b></button></div>';
                        $('#producto-sinmodificar').append(html_modificacion);
                    }
                });

                formu = '<form class="form-inline" id="form_nuevamodificacion"><input type="hidden" id="id_producto_modificacion" name="id_producto_modificacion" value="' + producto + '"/><input type="hidden" id="id_orden_modificacion" name="id_orden_modificacion" value="' + id + '"/><input type = "text" class="form-control control-label" id ="nuevamodificacion" required placeholder = "nueva modificación"><button type="submit" class="btn btn-success">Agregar</button></form >';
                $('#formnuevam').empty();
                $('#formnuevam').append(formu);


                var html_adicional = '';
                $.each(data.adicionales,function(k,value){
                    if(value.in_orden){
                        html_adicional = '<div><button onclick="VendtyOrden.renderAdicional(this,&quot;del&quot;);" class="btn  btn-orden input-xs" data-id="' + id + '" data-producto="' + value.id_adicional + '" data-nombre="' + value.nombre + ' - ' + value.precio + '"><b>' + value.nombre + ' - ' + value.precio + '' + value.simbolo+'</b></button></div>';
                        $('#producto-adicionado').append(html_adicional);
                    }else{
                        html_adicional = '<div><button onclick="VendtyOrden.renderAdicional(this,&quot;add&quot;);" class="btn  btn-orden input-xs" data-id="' + id + '" data-producto="' + value.id_adicional + '" data-nombre="' + value.nombre + ' - ' + value.precio + '"><b>' + value.nombre + ' - ' + value.precio + value.simbolo+'</b></button></div>';
                        $('#producto-sinadicionar').append(html_adicional);
                    }
                });


                //modificacion.html(html_modificacion);
                //adicional.html(html_adicional);
            }

            $(document).on('click','#sidebar-close',function(){
                /*if($('.page-sidebar-minimize.page-sidebar-right-show').length){
                    $('body').toggleClass('page-sidebar-right-show');
                }*/

                if($('.sidebar-right.sidebar-right-effect.sidebar-open-effect')){
                    $('.sidebar-right').toggleClass('sidebar-open-effect');
                }
            });

            /*$(document).on('click','.btn-orden',function(){ 
                // console.log($(this));
                $('#producto-modificado').append($(this));
                $(this).remove();               
                guardarModificacion($(this));
            });

            
            $(document).on('click','#sidebar-adicional table tr',function(){ 
                $(this).css('background-color','#ccc');               
                guardarAdicional($(this));
            });*/

            $('.composite-products-content').on('click', '.option', function (event) {

                var _this = $(this);
                var id = $(this).data('id');
                var url = null;

                if ($(this).parent().hasClass('additions')) {
                    url = '/orden_compra/add_addition';
                } else if ($(this).parent().hasClass('actives')) {
                    url = '/orden_compra/remove_addition';
                }

                $.post(VendtyApp.handleBaseURL() + url, {
                    order: idOrder,
                    addition: id
                }, function(data, textStatus, xhr) {
                    // console.log(xhr);
                    if (textStatus == "success") {
                        var json = $.parseJSON(data);
                        // console.log(json);
                        if (url == '/orden_compra/add_addition') {
                            $('.composite-products-content.actives').append('<span class="option active" data-id="' + json.addition.id + '" style="display: block;">' + json.addition.nombre + '</span>');
                        } else if (url == '/orden_compra/remove_addition') {
                            _this.remove();
                        }
                        VendtyOrden.renderOrden($('#txt_seccion').val(), $('#txt_mesa').val());
                    }
                });
            });

            $(".composite-modify-content").on('click', '.option', function (event) {

                var _this = $(this);
                var id = $(this).data("id");
                var url = null;

                if ($(this).parent().hasClass("modify")) {
                    url = "/orden_compra/add_modify";
                } else if ($(this).parent().hasClass("actives")) {
                    url = "/orden_compra/remove_modify";
                }

                $.post(VendtyApp.handleBaseURL() + url, {
                    order: idOrder,
                    modification: id
                }, function(data, textStatus, xhr) {
                    // console.log(xhr);
                    if (textStatus == "success") {
                        var json = $.parseJSON(data);
                        // console.log(json);
                        if (url == "/orden_compra/add_modify") {
                            $('.composite-modify-content.actives').append("<span class='option active' data-id='" + json.modification.id + "' style='display: block;'>" + json.modification.nombre + "</span>");
                        } else if (url == "/orden_compra/remove_modify") {
                            _this.remove();
                        }
                        VendtyOrden.renderOrden($('#txt_seccion').val(), $('#txt_mesa').val());
                    }
                });
            });

        }
    }
}();
// Call ordenes and init
VendtyOrden.init();
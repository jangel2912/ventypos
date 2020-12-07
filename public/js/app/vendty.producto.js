var VendtyProducto = function(){
    return {
        
                // =========================================================================
                // CONSTRUCTOR APP
                // =========================================================================
                init: function () {
                    VendtyProducto.tableIng();
                    VendtyProducto.tableIngredientes();
                    VendtyProducto.tableAdicionales();
                    VendtyProducto.tableModificaciones();
                    VendtyProducto.storeProductos();
                    VendtyProducto.jqueryValidation();
                    //
                    //VendtyProducto.handleIngSelect();
                  
                    $('.seleted').select2({    
                        //templateResult: formatRepo,
                        templateSelection: formatRepoSelection
                    });
                   
                    function formatRepo(repo){
                        if (repo.loading) return "Buscando...";
                        
                        
                        markup = '<option value="'+repo.id+'">'+repo.name+'</option>';
                        return markup; 
                    }

                    function formatRepoSelection(repo){
                        
                        //var $obj = $(repo.element).parent().data('id');
                        var $obj = $(repo.element).parent().parent().parent().data('id');
                        //var table = $('#tab_ing');
                        $input = $(document).find('input#ingrediente_'+$obj);
                        $input.val( repo.id );
                        return repo.text;
                    }     
                    var compuesto = $(document).find('input#tipo_producto_compuesto');
                    compuesto.on('click',function(){
                        if(compuesto.is(':checked')) {
                            $('#tab6-4[data-toggle="tab"]').prop("disabled",true);
                            //$("#tab6-4").hide();
                            //$('#tab6-4').tab('disable', 1); // disable first tab
                            //$('#tab6-4').tab('hide')
                        }
                    })
                    var frm_vaidate = false
                    
                  
                    /*compuesto.is(':checked',function(){
                        console.log("es compuesto");
                    })*/
                   // console.log(compuesto);
                    $('.fileinput').fileinput();

                    $('.fileinput').on('change.bs.fileinput', function(evt, files){
                        var self = $(this),
                        input = self.find(':file');
                        //var file = evt.target.files[0];
                        console.log(input);
                        //$('#imagen1').val(files.name); 
                    })
                },
                tableIng:function(){
                    
                    
                    $("#add_ing").on("click", function() {
                        // Dynamic Rows Code
                        // Get max row id and set new id
                        var newid = 0;
                        $.each($("#tab_ing tr"), function() {
                            if (parseInt($(this).data("id")) > newid) {
                                newid = parseInt($(this).data("id"));
                            }
                        });
                        newid++;
                        
                        var tr = $("<tr></tr>", {
                            id: "addr"+newid,
                            "data-id": newid
                        });
                        
                        // loop through each td and create new elements with name of newid
                        $.each($("#tab_ing tbody tr:nth(0) td"), function() {
                            var cur_td = $(this);
                            
                            var children = cur_td.children();
                            //if(children.prop('tagName') == "INPUT" || children.prop('tagName') == "SELECT" || children.prop('tagName') == "BUTTONS"){  
                            
                                // add new td and element if it has a nane
                                if ($(this).data("name") != undefined) {
                                    var td = $("<td></td>", {
                                        "data-name": $(cur_td).data("name"),
                                    });
                                    
                                    var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                                    console.log(c);
                                    //c.attr("name", $(cur_td).data("name") + newid);
                                    c.attr("name",'producto['+newid+']['+$(cur_td).data("name")+']');
                                    c.attr("id",'ingrediente_'+newid);
                                    c.appendTo($(td));
                                    td.appendTo($(tr));
                                    //if(children.prop('tagName') == "SELECT")
                                        
                                } else {
                                    var td = $("<td></td>", {
                                        'text': $('#tab_ing tr').length
                                    }).appendTo($(tr));
                                }
                            //}
                        });
                        
                        // add delete button and td
                        /*
                        $("<td></td>").append(
                            $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                                .click(function() {
                                    $(this).closest("tr").remove();
                                })
                        ).appendTo($(tr));
                        */
                        
                        // add the new row
                        $(tr).appendTo($('#tab_ing'));
                        $('.seleted').select2({    
                            //templateResult: formatRepo,
                            templateSelection: formatRepoSelection
                        });

                        function formatRepo(repo){
                            if (repo.loading) return "Buscando...";
                            
                            
                            markup = '<option value="'+repo.id+'">'+repo.name+'</option>';
                            return markup; 
                        }

                        function formatRepoSelection(repo){
                            
                            //var $obj = $(repo.element).parent().data('id');
                            var $obj = $(repo.element).parent().parent().parent().data('id');
                                                        //var table = $('#tab_ing');
                            $input = $(document).find('input#ingrediente_'+$obj);
                            $input.val( repo.id );
                            $input.attr('idI',repo.id);                            
                            return repo.text;
                        }
                        
                        $(tr).find("td button.row-remove").on("click", function(e) {                            
                            e.preventDefault(); 
                            $(this).closest("tr").remove();
                            swal({
                                position: 'center',
                                type: 'success',
                                title: "success",
                                html: 'El ingrediente fue eliminado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        });

                        $(".row-remove").on("click", function() {
                            console.log("asd");
                        });    


                        
                    });
                   
                // Sortable Code
                    var fixHelperModified = function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();

                        $helper.children().each(function(index) {
                            $(this).width($originals.eq(index).width())
                        });

                        return $helper;
                    };

                    /*$(".table-sortable tbody").sortable({
                        helper: fixHelperModified      
                    }).disableSelection();*/

                    //$(".table-sortable thead").disableSelection();



                    //$("#add_ing").trigger("click");
                    
                },
                tableIngredientes: function () {
                    var counter = 0;
                    var tableDom = $('#tbl_ingredientes').DataTable({
                            autoWidth        : false,
                            lengthMenu: [
                                [5, 10, 25, -1], [5, 10, 25, "All"]
                            ],
                            /*data:[{"materiales":"prueba","cantidad":"1",'id_material':1}],
                            "columnDefs": [ 
                                {
                                "render": function ( data, type, row,meta ) {
                                    counter = meta.row + 1;    
                                    
                                    return '<input type="hidden" id="codigo_'+counter+'" name="ingrediente['+counter+'][codigo]" class="form-control" disabled /><select id="material_'+counter+'" data-counter="'+counter+'" name="ingrediente['+counter+'][material]"   class="form-control"></select>';    
                                },
                                "targets": [0]
                                },
                                {
                                    "render": function ( data, type, row ) {
                                        return '<input id="ingrediente[cantidad]['+counter+']" name="ingrediente['+counter+'][cantidad]" class="form-control"/>';
                                    },
                                    "targets": 1
                                },
                            ],*/
                            preDrawCallback: function () {
                                // Initialize the responsive datatables helper once.
                                /*if (!responsiveHelperDom) {
                                    responsiveHelperDom = new ResponsiveDatatablesHelper(tableDom, breakpointDefinition);
                                }*/
                                
                                console.log(counter);
                                //VendtyProducto.handleIngSelect(null,counter);
                            },
                            //lengthChange: false,
                            dom: "<'row'<'col-sm-6 text-center'B><'col-sm-3'>>"+
                            "<'row'<'col-sm-12'tr>>",
                            buttons: [
                                [
                                    {
                                        text: 'Nuevo ingrediente',
                                        action: function ( e, dt, node, config ) {
                                            //console.log("llego");
                                            //var counter = 1;
                                            addRow();
                                     

                                            //dt.ajax.reload();
                                        }
                                    },
                                ],
                                
                            ],
                            rowCallback    : function (nRow) {
                                //responsiveHelperDom.createExpandIcon(nRow);
                                
                            },
                            drawCallback   : function (oSettings) {
                                var api = this.api();
                                console.log(counter);    
                                VendtyProducto.handleIngSelect(api.table().container(),counter);
                            }
                        });
                        ;
                        function addRow(){
                            
                            counter = counter + 1;
                            tableDom.row.add(
                                [
                                [
                                    '<input type="hidden" id="codigo_'+counter+'" name="ingrediente['+counter+'][codigo]" class="form-control" disabled />'+
                                    '<select id="material_'+counter+'" data-counter="'+counter+'" name="ingrediente['+counter+'][material]"  class="form-control"></select>'
                                ],
                                [
                                    '<input id="ingrediente[cantidad]['+counter+']" name="ingrediente['+counter+'][cantidad]" class="form-control"/>'
                                ]]  

                            ).draw(  );
                            counter = counter + 1;
                        }
                        
                        /*tableDom.buttons(1,null).container()
                        .appendTo( tableDom.table().container() );*/
                
                        return {
                            table : tableDom
                        }
                },
                handleIngSelect:function(counter){
                    //$('select',container).select2();
                    
                    $('.seleted').select2({
                        //minimumResultsForSearch: 1,
                        //placeholder: "Seleccion",
                        allowClear: true,
                        ajax: {
                            url:VendtyApp.handleBaseURL()+'/ProductoRestaurant/getAjaxProductsMaterialLike',
                            dataType:'json',
                            delay:50,
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
                            cache:true
                        },
                        dropdowCssClass:'bigdrop',
                        escapeMarkup:function(markup){return markup;},
                        minimunInputLenght:1,
                        templateResult: formatRepo,
                        templateSelection: formatRepoSelection
                    });
                    
                    function formatRepoSelection (repo) {
                        console.log("selection");
                        var $obj = $(repo.element).parent().data('counter');
                        $input = $(document).find('input#codigo_'+$obj);
                        $input.val( repo.codigo );
                        var selected = "<input type='hidden' name='ingrediente["+$obj+"][id]' value='"+repo.id+"'>";
                        if(repo.name == 'undefined' && obj == 'undefinded')
                            repo.name = "Seleccione";
                        return selected + repo.name;
                    }
                    
                    function formatRepo(repo){
                        console.log("asdad");      
                          /*markup += "<div class='select2-result-repository__statistics'>" +
                          "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.name + " Forks</div>" +
                          "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                          "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                        "</div>" +
                        "</div></div>";*/
                        if (repo.loading) return "Buscando...";
                        
                        
                        markup = '<option value="'+repo.id+'">'+repo.name+'</option>';
                        return markup;  
                
                    }
                },
                tableAdicionales:function(){
                    var producto_text,producto,select_element,producto_id;

                    $('.select2').select2({
                        
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
                    }).on("select2:select", function (e) {
                        select_element = $(e.currentTarget);
                        producto = select_element.val();
                        
                    });
                    function formatRepoSelection (repo) {
                        producto_text = repo.name;
                        producto_id = repo.id;
                        //var selected = "<input type='hidden' name='ingrediente[material]["+counter+"]' value='"+repo.id+"'>";
                        //return selected;
                        return  repo.name;
                    }
                    
                    function formatRepo(repo){
                          
                          /*markup += "<div class='select2-result-repository__statistics'>" +
                          "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.name + " Forks</div>" +
                          "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                          "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                        "</div>" +
                        "</div></div>";*/
                    
                        markup = '<option value="'+repo.id+'">'+repo.codigo+' - '+repo.name+'</option>';
                        return markup;  
                
                    }

                    $(document).find("#add_row").on("click", function() {
                        var newid = 0;
                        $.each($("#tab_logic tr"), function() {
                            if (parseInt($(this).data("id")) > newid) {
                                newid = parseInt($(this).data("id"));
                            }
                        });
                        newid++;
                        
                        var tr = $("<tr></tr>", {
                            id: "addr"+newid,
                            "data-id": newid
                        });
                        
                        // loop through each td and create new elements with name of newid
                        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
                            var cur_td = $(this);
                            
                            var children = cur_td.children();
                            
                            // add new td and element if it has a nane
                            if ($(this).data("name") != undefined) {
                                var td = $("<td></td>", {
                                    "data-name": $(cur_td).data("name")
                                });
                                
                                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                                c.attr("name",'producto['+newid+']['+$(cur_td).data("name")+']');
                                c.attr("id", $(cur_td).data("name") + newid);
                                c.appendTo($(td));
                                td.appendTo($(tr));
                            } else {
                                var td = $("<td></td>", {
                                    'text': $('#tab_ing tr').length
                                }).appendTo($(tr));
                            }
                        });
                        $(tr).appendTo($('#tab_logic'));
                        
                        $(tr).find("td button.row-remove").on("click", function() {
                            
                            $(this).closest("tr").remove();
                            swal({
                                position: 'center',
                                type: 'success',
                                title: "success",
                                html: 'La adición fue eliminada correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        });

                        //console.log(producto_text);
                        $("#producto"+newid).val(producto_text);
                        $(':hidden#id_producto'+newid).val(producto_id);
                    });
                    // Sortable Code
                    var fixHelperModified = function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                    
                        $helper.children().each(function(index) {
                            $(this).width($originals.eq(index).width())
                        });
                        
                        return $helper;
                    };

                    //$("#add_row").trigger("click");
                },
                tableModificaciones:function(){
                    
                    $(".tags").tagsinput('item');
                    //$('.tags').tagsinput('add', { "value": 1 , "text": "Amsterdam"  },{ "value": 1 , "text": "Amsterdam"});


                    
                    $('.bootstrap-tagsinput > input').css('width', '');
                    $("#add_modificacion").on("click", function() {
                       

                        
                        var newid = 0;
                        $.each($("#tab_modi tr"), function() {
                            if (parseInt($(this).data("id")) > newid) {
                                newid = parseInt($(this).data("id"));
                            }
                        });
                        newid++;
                        
                        var tr = $("<tr></tr>", {
                            id: "addr"+newid,
                            "data-id": newid
                        });
                        
                       
                        $.each($("#tab_modi tbody tr:nth(0) td"), function() {
                            var cur_td = $(this);
                            
                            var children = cur_td.children();
                            
                            // add new td and element if it has a nane
                            if ($(this).data("name") != undefined) {
                                var td = $("<td></td>", {
                                    "data-name": $(cur_td).data("name")
                                });
                                
                                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                                c.attr("name", $(cur_td).data("name") + newid);
                                c.appendTo($(td));
                                td.appendTo($(tr));
                            } else {
                                var td = $("<td></td>", {
                                    'text': $('#tab_ing tr').length
                                }).appendTo($(tr));
                            }
                        });
                        $(tr).appendTo($('#tab_modi'));
                        
                        $(tr).find("td button.row-remove").on("click", function() {
                             $(this).closest("tr").remove();
                        });
                    });
                    // Sortable Code
                    var fixHelperModified = function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                    
                        $helper.children().each(function(index) {
                            $(this).width($originals.eq(index).width())
                        });
                        
                        return $helper;
                    };
                  
                    /*$(".table-sortable tbody").sortable({
                        helper: fixHelperModified      
                    }).disableSelection();*/
                
                    //$(".table-sortable thead").disableSelection();                
                },
                storeProductos:function(){
                    var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
                    name = $("#txt_nombre"),
                    codigo = $( "#txt_codigo"),
                    proveedor = $( "#slt_proveedor"),
                    precio_compra = $( "#precio_compra" ),
                    precio_venta = $( "#precio_venta" ),
                    slt_categoria = $( "#slt_categoria" ),
                    text_description = $( "#text_description" ),
                    //precio_venta_impuesto = $( "#precio_venta_impuesto" ),
                    unidad_medida = $( "#unidad_medida" ),
                    precio_almacen = $( "#precio_almacen" ),
                    allFields = $([]).add(name).add(codigo).add(precio_compra).add(precio_almacen),
                    tips = $( ".validateTips" );

                    function updateTips( t ) {
                        tips
                          .text( t )
                          .addClass( "ui-state-highlight" );
                        setTimeout(function() {
                          tips.removeClass( "ui-state-highlight", 1500 );
                          $(".alert.alert-warning").removeClass("hidden");
                        }, 500 );
                    }
                   
                    function checkLength( o, n, min, max ) {
                        
                        if ( o.val().length > max || o.val().length < min ) {
                          /*o.parent().addClass( "has-error" );
                          updateTips( "Longitud del campo " + n + " debe estar entre " +
                            min + " y " + max + "." );*/
                           /* mensaje = "Longitud del campo " + n + " debe estar entre " + min + " y " + max + "." ;
                            mensaje = '<div class="alert alert-danger">' + mensaje + '</div>';
                            $('#mensaje').html(mensaje);*/
                          return false;
                        } else {
                          return true;
                        }
                    }

                    
                    
                    var button = $(document).find('#btn_store');
                    button.on('click',function(){
                        var valid = true;   
                        mensaje="Campos obligatorios:";        
                        
                        allFields.removeClass( "has-error" );
                     
                        if (name.val() == "") {
                            mensaje += "<br>Debe ingresar un nombre";
                            valid = false;
                        }
                       
                        if (codigo.val() == "") {
                            mensaje += "<br>Debe ingresar código";
                            valid = false;
                        }
                       
                        if (proveedor.val() == "") {
                            mensaje += "<br>Debe seleccionar un proveedor";
                            valid = false;
                        }

                        if (slt_categoria.val()==""){
                            mensaje += "<br>Debe seleccionar un categoria";                           
                            valid = false;
                        }
                        if (unidad_medida.val() == "") {
                            mensaje += "<br>Debe seleccionar unidad de medida";
                            valid = false;
                        }
                        
                        if (precio_almacen.val()==0){
                            if ((precio_compra.val()=="") && (!isNaN(precio_compra.val()))){
                                mensaje += "<br>Debe ingresar precio de compra";
                                valid = false;
                            }
                            if ((precio_venta.val() == "") && (!isNaN(precio_venta.val()))) {
                                mensaje += "<br>Debe ingresar precio de venta";                                
                                valid = false;
                            }
                            /*if ((precio_venta_impuesto.val() == "") && (!isNaN(precio_venta_impuesto.val()))) {
                                mensaje += "<br>Debe ingresar precio de venta con impuesto";
                                valid = false;
                            } */                           
                        }

                        if ((mensaje != "Campos obligatorios:") || (!valid)){
                            //mensaje1 = '<div class="alert alert-danger">' + mensaje + '</div>';
                            //$('#mensaje').html(mensaje1);
                            swal({
                                position: 'center',
                                type: 'error',
                                title: "error",
                                html: mensaje,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                                           
                       /* valid = valid && checkLength( precio_compra, "precio de compra", 1, 10 );
                        valid = valid && checkLength( precio_venta, "precio_venta", 1, 10 );
                        valid = valid && checkLength( slt_categoria, "Categoria", 1, 10 );
                        valid = valid && checkLength( precio_venta_impuesto, "precio_venta_impuesto", 1, 10 );
                        valid = valid && checkLength( unidad_medida, "unidad", 1, 10 );*/
                        //x=$('#frm_producto_basico').val();
                        if ( valid ) {
                            var post_data = {};
                            post_data.form_basico =  $('#frm_producto_basico').serialize();
                            post_data.form_stock = $('#frm_producto_stock').serialize();
                            post_data.ingredientes = $('#frm_ingredientes').serialize();
                            post_data.adicionales = $('#frm_adicionales').serialize();
                            //post_data.imagen = $('#imagen1').prop('files')[0];
                            post_data.modificaciones = $('#txt_modificacion').val();
                            console.log($('#frm_ingredientes').serialize());
                        //var sData = $('input', VendtyProducto.tableIngredientes.tableDom.fnGetNodes()).serialize();
                            
                            $.ajax({
                                type: 'POST',
                                async: false,
                                url: VendtyApp.handleBaseURL()+'/ProductoRestaurant/store',
                                data: post_data,
                                dataType:'json'
                                //contentType: 'multipart/form-data',
                            },'json')
                            .done(function(data){                              
                                if(!data.estatus){
                                    //var mensaje = '<div class="alert alert-danger">' + data.mensaje + '</div>';
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: "error",
                                        html: data.mensaje,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    
                                }else{
                                    var mensaje = '<div class="alert alert-success">' + data.mensaje +'</div>';
                                    swal({
                                        position: 'center',
                                        type: 'success',
                                        title: "success",
                                        html: data.mensaje,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    if (data.nuevo=="nuevo"){
                                        window.location = VendtyApp.handleBaseURL() + '/ProductoRestaurant';
                                    }
                                }
                                //$('#mensaje').html(mensaje);                                
                                $("html, body, #wrapper").animate({ scrollTop: 0 }, 600);
                                return false;
                            });
                        }
                        $("html, body, #wrapper").animate({ scrollTop: 0 }, 600); 

                    });
                },
                jqueryValidation:function(){
                    if($('#frm_producto_basico').length){
                        
                        
                        //console.log("llego");
                    }
                }

                //templateResult:formatRepo,
                //templateSelection:formatRepoSelection

    }
}();
 
// Call main app init
VendtyProducto.init();

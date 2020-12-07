<style>

    .titCategoria{
        color: #68AF27;
    }

    .redSpan{
        color: #FFAA31
    }

    .label{
        margin: 2px 2px;
        padding-left: 6px;
        padding-right: 10px;
    }

    .separador{
        height: 4px;
    }
    .tituloPr{
        font-size: 20px;
        color:#005683;        
        text-align: center;
        font-size: bold;
    }
    .row-fluid{
        padding: 0px;
        margin: 0px;
    }

    hr{       
        background-color: #ddd;
        height: 1px;
        margin:2px;
        margin-bottom: 10px;
    }



    #btnAlmacenes{        
        margin: 20px auto;        
    }

    #btnGuardar{
        margin: 20px auto;
    }    

    td,th{
        padding: 0px;
    }
    .titlePanel{
        color: #777;
        font-size: 14px;
        text-align: center;
    }
    #cargando {
        background-color: #fff;
        position:absolute;
        opacity: 0.2;
        filter: alpha(opacity=20); /* For IE8 and earlier */
    }
    .well{
        display: flex;
        padding: 10px;        
    }
    .borderPanel{
    }

    .infoImage{
        line-height: 12px;
        color:#999;
    }
    .paneles .row-fluid{
        margin-bottom: 5px;
    }

</style>

<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Producto con atributos", "Producto con atributos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>


<!-- Bootrstrap modal form -->
<div id="fModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Cantidad en almacenes</h3>
    </div>
    <div style="padding: 5% 10%;">
        <table width="100%">
            <thead>
                <tr>
                    <th>Almacen</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody id="list_almacenes">


            </tbody>
        </table>
    </div>                   
    <div class="modal-footer">
        <button type="button" onclick="guardar_almacen_modal(this)" class="btn btn-success">Guardar</button> 
        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancelar</button>            
    </div>
</div>

<?php echo form_open_multipart("atributos/setEditarProductoIndividual/" . $data, array("id" => "validate")); ?>


<div class="row-fluid">

    <div class="span2">       

    </div>                                                         

    <div class="span4" style="padding-right:5px; margin-bottom: 50px;">

        <div class="paneles">    

            <div class="row-fluid">

                <div class="row-fluid well"> 

                    <div class="span12">

                        <div class="row-fluid">
                            <div class="span12 user-profile-title">
                                <div class="tituloPr" style="color: #68AF27;"></div>
                            </div>                                                         
                        </div>                            
                        <div class="row-fluid">
                            <hr>
                        </div>
                        <div class="row-fluid">

                            <div class="span6">

                                <div class="row-fluid">
                                    <div class="span12">

                                        <div class="imageDrag" style="margin-bottom:20px;">
                                            <center>
                                                <img id="previewImg" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                            </center>
                                        </div>                        

                                    </div>                                                         
                                </div>                

                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="input-append file">
                                            <input id="masterFile" type="file" name="imagen" style="display: none" onchange="reloadPreview(this)" />
                                            <input placeholder="Cargar imagen"  type="text"/>
                                            <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>                               
                                        </div>
                                    </div>    
                                </div>        


                            </div>


                            <div class="span6">

                                <div class="row-fluid">                    
                                    <label class="titlePanel titCategoria" style=" color:#005683;"></label>
                                </div>

                                <div class="row-fluid">
                                    <small>Código de barras: <span class="redSpan"></span></small>
                                </div>

                                <div class="row-fluid">
                                    <span>Descripcion:</span>
                                </div>

                                <div class="row-fluid">
                                    <span class="txtDescripcion"></span>
                                </div>                

                            </div>
                        </div>    

                    </div>

                </div>

                <div class="row-fluid separador"></div>

                <div class="row-fluid well">
                    <div id="tagsCont" class="span12" style="padding-top:4px;">                                              

                    </div>                                                         
                </div>  

            </div>

        </div>

    </div>           

    <div class="span4" style="padding-left:5px;">

        <div class="paneles">    

            <div class="row-fluid">

                <div class="row-fluid well">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span6">
                                <label class="titlePanel">Impuestos</label>
                            </div>                                                         
                            <div class="span3">
                                <label class="titlePanel">P.Compra </label>
                            </div>                                                         
                            <div class="span3">
                                <label class="titlePanel">P.Venta</label>
                            </div>                                                                         
                        </div>
                        <div class="row-fluid">
                            <div class="span6">

                                <select id="marca_principal" name="impuestos" class="select select2" style="width: 100%;">
                                    <option value="0">Seleccione un impuesto...</option>
                                    <optgroup label="-------">
                                    </optgroup>
                                </select>                            

                            </div>                                                         
                            <div class="span3">
                                <input  type="text" name="compra" value="">
                            </div>                                                         
                            <div class="span3">
                                <input  type="text" name="venta" value="">
                            </div>                                                                         
                        </div>
                    </div>                    
                </div>          

                <div class="row-fluid separador"></div>

                <div class="row-fluid well" style="margin-bottom:40px; padding-top: 20px;">

                    <div class="span4">

                        <div class="row-fluid">                       
                            <div class="span12">
                                <center>
                                    <label> <input id="check_activo" type="checkbox" class="active-select" checked> Activo </label>
                                </center>
                            </div>          
                        </div>

                        <div class="row-fluid">                       
                            <div class="span12">
                                <center>
                                    <label> <input id="check_tienda" type="checkbox" class="active-select" checked> Tienda </label>

                                </center>
                            </div>          
                        </div>                

                    </div>                                                         

                    <div class="span4">
                        <center>
                            <a id="btnAlmacenes" href="#fModal" class="btn almacenes" role="button" data-toggle="modal">Almacenes</a>                
                        </center>
                    </div>                                                                     

                    <div class="span4">
                        <center>
                            <div align="center" id="btnGuardar" class="btn btn-success"> Guardar </div>            
                        </center>
                    </div>                                                         

                </div>                          

            </div>

        </div>

    </div>           


    <div class="span2">

    </div>               

</div>    




<!-- Values send to send in format json-->
<input id="dataJson" type="hidden" name="dataJson" value=''>

</form>






<script src="<?php echo base_url('public/js/form2js.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/previewImg.js'); ?>" type="text/javascript"></script>

<script>


    var baseUrl = "<?php echo site_url(); ?>";
    var id = "<?php echo $data ?>";
    var obj = {};

    var jsonData;
    var listaProductos = {};
    var listaAlmacenes = {};
    var indiceProductos = 0;
    var cantidadProductos = 0;



//===========================================================================
// On CLICK 
//===========================================================================

    $("#btnGuardar").click(function () {
        $("#validate").submit();
    });
    

//=========================================================================
//  OCULTAR MODAL BOOTSTRAP DE LA LISTA DE ALMACENES
//=========================================================================

    guardar_almacen_modal = function (elem) {
        $('#fModal').modal('hide');
        $(".tip").tooltip({html: true, placement: 'top', trigger: 'hover'});
    }



    

//=========================================================================
//  PARA CONVERTIR LOS ALMACENES DISPONIBLES A UN OBJ
//=========================================================================

    guardarAlmacenObj = function () {

        var almacenesChilds = $("#list_almacenes tr");
        var cantAlmacenes = $(almacenesChilds).length;

        var listaAlmacenes = {};

        for (var i = 0; i < cantAlmacenes; i++) {

            var idAlm = $(almacenesChilds[i]).find("#almacenId").attr("data-almacen");
            var nombreAlm = $(almacenesChilds[i]).find("#almacenNombre").text();
            var cantUnidades = $(almacenesChilds[i]).find("#almacenId").val();
            var producto = {id: idAlm, nomber_almacen: nombreAlm, unidades: cantUnidades}
            listaAlmacenes["almacen" + (i + 1)] = Object.assign({}, producto);
        }

        listaAlmacenes["cantidad_almacenes"] = cantAlmacenes;

        return listaAlmacenes;

    }


//=========================================================================
//  PARA CONVERTIR EL FORMULARIO A UN OBJ
//=========================================================================

    agregar_datos = function () {

        //Transform inputs null to some data for create object values
        $("form input[type=text]").each(function () {
            if ($(this).val() == "")
                $(this).val(" ");
        });

        // Convert form to obj javascript
        var formToObjActual = form2js($('#validate')[0]);

        // Restore original value of texbox
        $("form input[type=text]").each(function () {
            if ($(this).val() == " ")
                $(this).val("");
        });


        // Deleting from obj hidden  parameter                    
        delete listaProductos["dataJson"];
        // Save formObj in a master OBJ listaProductos
        var objActual = listaProductos["producto" + 1] = formToObjActual;



        // Save almacenes in the object listaAlmacenes                                
        // Add list Almacenes to list Products                                
        //listaProductos["lista_almacenes"]=listaAlmacenes;                                
        var listaAlmacen = guardarAlmacenObj();
        objActual["lista_almacenes"] = listaAlmacen;

        objActual["impuesto"] = getSel($("#marca_principal"));


        //=========================================================================

        // Clone style table for display list
        var tableData = $('#defaulTable').clone();
        // Clone style table for display list
        var jqueryObj = $(tableData).appendTo('#contentDataList');

        var idProducto = "idProducto" + 1;

        // Multiple configuration for new table in list
        $(jqueryObj).addClass(idProducto);
        $(jqueryObj).attr("id", "");
        $(jqueryObj).find("button").attr("onclick", "eliminarProdcuto('" + 1 + "')");
        $(jqueryObj).show();


        //=========================================================================
        //Adding custome attributes to OBJ
        //=========================================================================

        //Captura el id de la categoria atributo
        objActual["categoria_atributos"] = $("#categoria_atributos").val();
        //Captura el nombre del proveedor y añadimos al objeto                        
        objActual["nombre_proveedor"] = getSelText($("#proveedor_principal"));
        //Checks list activo y tienda
        objActual["activo"] = getCheck($("#check_activo")) ? "1" : "0";
        objActual["tienda"] = getCheck($("#check_tienda")) ? "1" : "0";


        

        // Si el usuario no selecciono una imagen, agregamos en objJson la imagen del preview   
        if (!objActual.hasOwnProperty("imagen")) {
            var arrySrcPreview = $("#previewImg").attr("src").split("/");
            var imagen = arrySrcPreview[ arrySrcPreview.length - 1 ];
            objActual["imagen"] = imagen;
        }


    }

//=========================================================================
//  PARA MOSTRAR LOS DATOS DEL FORMULARIO
//=========================================================================
    function updateData(json) {

        obj = jQuery.parseJSON(json);

        $(".tituloPr").html(obj["atributos"][0]["nombre_producto"]);
        $(".titCategoria").html(obj["atributos"][0]["nombre_categoria"]);
        $(".redSpan").html(obj["atributos"][0]["codigo_barras"]);
        $(".txtDescripcion").html(obj["producto"]["descripcion"]);


        $(obj["atributos"]).each(function () {
            var tag = '<span class="label label-default">' + this["nombre_atributo"] + ' = ' + this["nombre_clasificacion"] + '</span>';
            $("#tagsCont").append(tag);
        })

        $(obj["impuestos"]).each(function () {
            var tag = '<option value="' + this["id_impuesto"] + '">' + this["nombre_impuesto"] + ' ' + this["porciento"] + '%  </option>';
            $("#marca_principal optgroup").append(tag);
        })

        setSel($("#marca_principal"), obj["producto"]["impuesto"]);

        toSel2($("#marca_principal"));

        $("input[name=compra]").val(obj["producto"]["precio_compra"])
        $("input[name=venta]").val(obj["producto"]["precio_venta"])

        if (obj["producto"]["activo"] == "1")
            setCheck($("#check_activo"), true)
        else
            setCheck($("#check_activo"), false)


        var pathImg = "<?php echo base_url('uploads'); ?>/" + obj["producto"]["imagen"];
        $("#previewImg").attr("src", pathImg);


        if (obj["producto"]["tienda"] == "1")
            setCheck($("#check_tienda"), true)
        else
            setCheck($("#check_tienda"), false)


        $(obj["almacenes"]).each(function () {

            var almacen = "";
            almacen = almacen + '<tr><td id="almacenNombre">' + this["nombre"] + '</td>';
            almacen = almacen + '<td align="right"><input id="almacenId" class="almacenesCant" type="text" name="cantidad" data-almacen="' + this["almacen_id"] + '" value=" "></td></tr>';
            var node = $(almacen).appendTo("#list_almacenes");
            $("input", node).val(this["unidades"]);

        })

    }
    
    

//=========================================================================
//  IMAGE INPUT FILE PREVIEW
//=========================================================================


    var reloadPreview = function(inputFile){
    
        var file = inputFile.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $('#previewing').attr('src', 'noimage.png');
            return false;
        } else {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL( inputFile.files[0] );
        }
    }

    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#previewImg').attr('src', e.target.result);
        $('#previewImg').attr('width', '250px');
        $('#previewImg').attr('height', '230px');
    };


//===========================================================================
//===========================================================================
//
//     API FORMS
//
//===========================================================================
//===========================================================================

    //-------------------------------------------------------------------------
    // SELECTS
    //-------------------------------------------------------------------------

    // All select with class select2
    toSel2 = function () {
        $("select.select2").select2("destroy");
        $("select.select2").select2();
    }

    getSel = function (obj) {
        return $(obj).val();
    }
    getSelText = function (obj) {
        var str = "#" + $(obj).attr("id") + " option:selected";
        return $(str).text();
    }
    setSel = function (obj, value) {
        $(obj).val(value).trigger("change");
    }



    //-------------------------------------------------------------------------
    //  CHECKS
    //-------------------------------------------------------------------------

    setCheck = function (obj, option) {
        if (option) {
            $(obj).prop('checked', true);
            $(obj).parent().addClass("checked");
        } else {
            $(obj).prop('checked', false);
            $(obj).parent().removeClass("checked");
        }
    }

    getCheck = function (obj) {
        var id = "#" + $(obj).attr("id") + "[type=checkbox]";        
        if ($(id).prop('checked'))  return true;
        else return false;      
    }
    

//===========================================================================
//===========================================================================
//
//      SUBMIT
//
//===========================================================================
//===========================================================================

    $("#validate").submit(function () {

        agregar_datos();
        //return false;

        //Creacion de json string
        $("#dataJson").attr("value", "");

        var jsonDataString = JSON.stringify(listaProductos);
        jsonDataString = jsonDataString.replace('[', '');
        jsonDataString = jsonDataString.replace(']', '');

        //AÑADIMOS EL STRING JSON AL HIDDEN INPUT !!!!!
        $("#dataJson").attr("value", jsonDataString);
        
        
        //return false;                                

    });



//===========================================================================
//===========================================================================
//
//      AJAX
//
//===========================================================================
//===========================================================================


    //===========================================================================
    // Enviamos el formulario
    // Nos retornará un String JSON
    // Callback -> actualizar Datos
    //===========================================================================

    function getProductoData() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('/atributos/getAjaxProductosEditar'); ?>/" + id,
            cache: false,
            dataType: 'text',
            success: function (response) {
                updateData(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }



//===========================================================================
//===========================================================================
//
//      INIT
//
//===========================================================================
//===========================================================================
    $(document).ready(function (e) {

        getProductoData();

    });

</script>


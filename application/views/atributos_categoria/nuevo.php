<style>

    .success{
        float: right;
        margin-right: 20px;
        background-color: #67b168;
        color: #fff;
        width: 120px;
        text-align: center;
        border-radius: 3px;
        display:none;
    }

    .well{
        display: flex;
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
    }
    .ico-angle-right{
        margin-right: 10px;
    }
    .item{
        padding: 5px;
    }
    span.noMargen{
        margin: 0px;
        padding: 0px;
    }
    .tab-pane{
        padding-top: 20px;
    }
    .btnEliminarAtr{
        margin-right: 10px;
        float:right;
        width:20px;
        height:20px;
        opacity: 0.9;
        cursor: pointer;
    }
    .btnEditarAtr{
        margin-right: 10px;
        float:right;
        width:20px;
        height:20px;
        opacity: 0.9;      
        cursor: pointer;
    }

    .btnEliminarClass{
        margin-right: 10px;
        float:right;
        width:20px;
        height:20px;
        opacity: 0.9;
        cursor: pointer;
    }
    .btnEditarClass{
        margin-right: 10px;
        float:right;
        width:20px;
        height:20px;
        opacity: 0.9;      
        cursor: pointer;
    }    
    .iconoAtributo{
        margin-right: 20px;
    }

    #contClasificaciones, #contAtributos{
        margin-bottom: 30px;
    }
    
    #cargando{
        display: none;
    }


</style>


<!-- Bootrstrap modal form -->
<div id="fModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Nombre atributo</h3>
    </div>
    <div style="padding: 5% 10%;">
        <table width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="list_almacenes">

                <tr>
                    <td id="almacenNombre">Nombre atributo:</td>
                    <td align="right">
                        <input id="nuevoNombre" class="almacenesCant" type="text" name="nuevoNombre" value="">
                        <input id="tmpAtrId" class="almacenesCant" type="hidden" name="tmpAtrId" value="0">
                    </td>
                </tr>

            </tbody>
        </table>
    </div>                   
    <div class="modal-footer">
        <button type="button" onclick="editarAtr()" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Guardar</button> 
        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancelar</button>            
    </div>
</div>

<!-- Bootrstrap modal form -->
<div id="fModalClass" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Atributo Valor</h3>
    </div>
    <div style="padding: 5% 10%;">
        <table width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="list_almacenes">

                <tr>
                    <td id="almacenNombre">Nombre:</td>
                    <td align="right">
                        <input id="nuevoNombreClass" class="almacenesCant" type="text" name="nuevoNombre" value="">
                        <input id="tmpClassId" class="almacenesCant" type="hidden" name="tmpClassId" value="0">
                    </td>
                </tr>

            </tbody>
        </table>
    </div>                   
    <div class="modal-footer">
        <button type="button" onclick="editarClass()" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Guardar</button> 
        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancelar</button>            
    </div>
</div>






<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Categorias", "Atributos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>

</div>




<form id="form" method="POST" action="<?php echo site_url('atributo_categorias/relacionar'); ?> " accept-charset="utf-8">    


    <div class="data-fluid tabbable"> 

        <ul class="nav nav-tabs">

            <li><a id="tabA" href="#relacion" data-toggle="tab">Categoría <-> Atributos</a></li>
            <li class="active"><a href="#atributos" data-toggle="tab">Atributos</a></li>
            <li><a id="tabB" href="#clasificaciones" data-toggle="tab">Atributos <-> Valores</a></li>

        </ul>

        <div class="tab-content">


            <!------------------ TAB 1  ---------------------------->

            <div class="tab-pane" id="relacion">


                <div class="row-fluid">

                    <div class="span3"></div>

                    <div class="span6">   

                        <div class="row-fluid">

                            <div class="block well">

                                <div style="margin-right:15px; margin-top: 3px;">CATEGORÍAS:</div>

                                <input id="hidden" type="hidden" value="none" name="categoriaSeleccionada"/>

                                <select id="categoria_atributos" name="nombre" class="select select2" style="width: 100%;">
                                    <option value="0">Seleccione una categoria...</option>
                                    <optgroup label="-------">                                                                  
                                        <?php foreach ($data['categorias'] as $value) { ?>
                                            <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select> 

                            </div>


                            <div class="block">
                                <div style="margin-bottom:10px;">Atributos para ésta categoría:<span class="success"> ¡Relación Exitosa! </span><img id="cargando" src="<?php echo base_url('public/img/loaders'); ?>/1d_2.gif" style="float:right"></div>
                                <select name="atributos[]" multiple="multiple" id="msc" style="position: absolute; left: -9999px;">

                                    <?php foreach ($data['atributos'] as $kAtributos => $vAtributos) { ?>
                                        <option value="<?php echo $vAtributos->id ?>"><?php echo $vAtributos->nombre ?></option>
                                    <?php } ?>                                                                   

                                </select>                            

                            </div>


                            <div class="block">
                                <div class="row-form">
                                    <div class="span2"><button class="btn btn-success" name="nuevo" type="submit" value=true><?php echo custom_lang("sima_submit", "Guardar"); ?></button></div>
                                    &nbsp;                                     
                                </div>
                            </div>                        

                        </div>

                    </div>

                    <div class="span3">
                    </div>

                </div>



            </div>



            <!------------------ TAB 2  ---------------------------->

            <div class="tab-pane active" id="atributos">

                <div class="row-fluid">                   

                    <div class="span3">
                    </div>
                    <div class="span6 noMargen ">
                        <div class="row-fluid">
                            <div class="row-fluid">

                                <div class="block well">
                                    <div class="span3">
                                        <label>Nombre Atributo:</label>
                                    </div>                                     
                                    <div class="span6">
                                        <input type="text" id="nombreAtributo" name="nombre">        
                                    </div>                        
                                    <div class="span3">
                                        <button id="addAtrBtn" type="button" class="btn btn-success"><i class="ico-plus icon-white"></i></button>                                    
                                    </div>
                                </div>
                            </div>                                

                        </div>


                        <div id="contAtributos" class="row-fluid faq">

                            <div id="itemAtr0" class="item" style="padding:2px 2px 2px 20px; display: none;">                                    
                                <div class="" idAtr="">
                                    <span aria-hidden="true" class="ico-caret-right iconoAtributo"></span>
                                    <span id="textAtr"></span>

                                    <div class="btnEliminarAtr" title="Eliminar">
                                        <span aria-hidden="true" class="ico-remove"></span>
                                    </div>

                                    <div class="btnEditarAtr" title="Editar">
                                        <a id="cambiarNombreAtributo" href="#fModal" role="button" data-toggle="modal">
                                            <span aria-hidden="true" class="ico-edit-2"></span>
                                        </a>

                                    </div>                                    


                                </div>
                            </div>                                                                   
                        </div>                            


                    </div>

                    <div class="span3">

                    </div>

                </div>

            </div>



            <!------------------ TAB 3  ---------------------------->

            <div class="tab-pane" id="clasificaciones">


                <div class="row-fluid">                   

                    <div class="span3">
                    </div>

                    <div class="span6 noMargen ">

                        <div class="block well">

                            <div style="margin-right:15px; margin-top: 3px;">Atributos:</div>

                            <input id="hidden" type="hidden" value="none" name="clasificacionSeleccionada"/>

                            <select id="select_clasificacion" name="nombre" class="select select2" style="width: 100%;">
                                <option value="0">Seleccione un atributo...</option>
                                <optgroup label="-------">                                                                  
                                    <?php foreach ($data['atributos'] as $kAtributos => $vAtributos) { ?>
                                        <option value="<?php echo $vAtributos->id ?>"><?php echo $vAtributos->nombre ?></option>
                                    <?php } ?>
                                </optgroup>
                            </select> 

                        </div>                        

                        <div class="row-fluid">
                            <div class="row-fluid">

                                <div class="block well">
                                    <div class="span3">
                                        Nombre Valor:
                                    </div>                                     
                                    <div class="span6">
                                        <input type="text" id="nombreClasificacion" name="nombre">        
                                    </div>                        
                                    <div class="span3">
                                        <button id="addClasBtn" type="button" class="btn btn-success"><i class="ico-plus icon-white"></i></button>                                    
                                    </div>
                                </div>
                            </div>                                

                        </div>



                        <div id="contClasificaciones" class="row-fluid faq">

                            <div id="itemClass0" class="item" style="padding:2px 2px 2px 20px; display: none;">                                    
                                <div class="" idClass="">
                                    <span aria-hidden="true" class="ico-caret-right iconoAtributo"></span>
                                    <span id="textClass"></span>

                                    <div class="btnEliminarClass" title="Eliminar">
                                        <span aria-hidden="true" class="ico-remove"></span>
                                    </div>

                                    <div class="btnEditarClass" title="Editar">
                                        <a id="cambiarNombreAtributo" href="#fModalClass" role="button" data-toggle="modal">
                                            <span aria-hidden="true" class="ico-edit-2"></span>
                                        </a>
                                    </div>                                    

                                </div>
                            </div>                                                                   
                        </div>                            


                    </div>

                    <div class="span3">

                    </div>

                </div>

            </div>
        </div>

</form>


<script type="text/javascript">

    var globalAtrId = 0;
    var globalClassId = 0;


    //-----------------------------------
    //  SELECTS API
    //-----------------------------------

    // All select with class select2
    toSel2 = function () {
        $("select.select2").select2("destroy");
        $("select.select2").select2();
    }


    // items = [{txt: "asd", val: 10}, {txt: "zasds", val: 11}];
    addSel = function (obj, array) {

        for (var i = 0; i < array.length; i++) {

            if ($(obj).find("optgroup")) {
                $(obj).find("optgroup").append($('<option>', {
                    value: array[i]["val"],
                    text: array[i]["txt"]
                }));
            } else {
                $(obj).append($('<option>', {
                    value: array[i]["val"],
                    text: array[i]["txt"]
                }));
            }
        }

        // If is select 2
        if ($(obj).hasClass("select2")) {
            // Refresh Select2
            $(obj).select2("destroy");
            $(obj).select2();
        }
    }

    // para eliminar una opcion de un select, (objeto, value )
    removeSel = function (obj, value) {
        var opt = "option[value='" + value + "']";
        $(obj).find(opt).remove();

        var opt = "option[value='" + value + "']";
        $(obj).find(opt).remove();

        // If is select 2
        if ($(obj).hasClass("select2")) {
            // Refresh Select2
            $(obj).select2("destroy");
            $(obj).select2();
        }
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

    enabledSel = function (obj, value) {
        if (value) {
            setSel(obj, 0);
            $(obj).select2("enable");
        } else {
            setSel(obj, 0);
            $(obj).select2("disable");
        }
    }




    /*
     $("#selectAtrValores").change(function () {
     if (getSel($(this)) > 0) {
     ajaxSelectedVal(getSel($(this)));
     }
     });
     */


    //====================================================================================
    //  HELPERS
    //====================================================================================    

    function ajaxGetAtributos() {

        var respuesta;

        $.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url('atributo_categorias/ajaxAtributos'); ?>",
            cache: false,
            data: {null: "null"},
            dataType: 'text',
            success: function (response) {
                respuesta = response;
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

        return respuesta;
    }


    //-----------------------------------
    //  OnClick
    //-----------------------------------    

    // Recargar atributos cada vez que damos click en el primer tab
    $("#tabA").click(function () {

        setSel($("#categoria_atributos"), 0)

        var atributos = ajaxGetAtributos();

        obj = jQuery.parseJSON(atributos);

        $("#msc").html("");

        $(obj).each(function () {
            $("#msc").append('<option value="' + this["id"] + '">' + this["nombre"] + '</option>');
        });

        $("#msc").multiSelect('refresh');


    });

    // Recargar atributos cada vez que damos click en el tercer tab
    $("#tabB").click(function () {

        setSel($("#select_clasificacion"), 0)

        var atributos = ajaxGetAtributos();
        atributos = atributos.replace(/nombre/g, "txt");
        atributos = atributos.replace(/id/g, "val");

        obj = jQuery.parseJSON(atributos);


        $("#select_clasificacion").find("optgroup").html("");

        addSel($("#select_clasificacion"), obj);
        toSel2($("#select_clasificacion"));

    });




    //====================================================================================
    //Tab 1
    //====================================================================================

    //-----------------------------------
    //  OnChange
    //-----------------------------------

    $("#categoria_atributos").change(function () {
        if (getSel($(this)) > 0) {
            ajaxSelectedVal();
        } else {
            $('#msc').multiSelect('deselect_all');
        }
    });

    //-----------------------------------
    //  CRUDS
    //-----------------------------------

    function ajaxSelectedVal() {

        var id = getSel($("#categoria_atributos"));


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('atributo_categorias/ajaxSeleccionados'); ?>/" + id,
            cache: false,
            data: {nada: "no"},
            dataType: 'text',
            success: function (response) {

                var values = response;

                $('#msc').multiSelect('deselect_all');

                if (!response == "") {

                    $.each(values.split(","), function (i, e) {

                        $('#msc').multiSelect('select', e);

                    });

                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });
    }


    function guardarCategoria(form) {
    
        $("#cargando").show();
        
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            cache: false,
            data: form.serialize(),
            dataType: 'text',
            success: function (response) {
                updateTab1(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });
    }

    function updateTab1(response) {

        $('#msc').multiSelect('refresh');
        
        $("#cargando").hide();

        $(".success").fadeIn("fast", function () {
            setTimeout(function () {
                $(".success").fadeOut("slow");
            }, 3000)
        });

    }

    //====================================================================================
    //====================================================================================



    //====================================================================================
    //Tab 2
    //====================================================================================

    //-----------------------------------
    //  OnClick
    //-----------------------------------    

    $("#addAtrBtn").click(function () {

        if (!$("#nombreAtributo").val() == "") {

            var node = $("#itemAtr0").clone();
            var jqueryObj = $(node).appendTo($("#contAtributos"));

            $(jqueryObj).addClass("itemAtrView");
            $(jqueryObj).hide();
            $(jqueryObj, jqueryObj).attr('id', '');
            
            $("#textAtr", jqueryObj).html($("#nombreAtributo").val());
            $(".btnEliminarAtr", jqueryObj).css("opacity", "1");
            $(".btnEditarAtr", jqueryObj).css("opacity", "1");  
            $(".btnEditarAtr", jqueryObj).attr('onClick', 'javascript:void(0)');
            $(".btnEliminarAtr", jqueryObj).attr('onClick', 'javascript:void(0)');
                $("#cambiarNombreAtributo", jqueryObj).attr("href", "javascript: void(0)");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("toggle");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("data-toggle");
                
            $(jqueryObj).slideDown("slow", function () {               
               ajaxAttrManage("add", 0, $("#nombreAtributo").val());
            });
            
        } else {
            alert("Digite el nombre del atributo");
        }

    }
    );
    function eliminarAtr(id) {

        var r = confirm("¿ Desea eliminar el atributo ?");
        if (r == true) {
            $("#itemAtr" + id).slideUp("slow", function () {
                ajaxAttrManage("del", id, "");    
            });            
        }
    }

    function editarAtr() {

        var nombre = $("#nuevoNombre").val();
        var id = $("#tmpAtrId").val();
        $("#nuevoNombre").val("");

        ajaxAttrManage("set", id, nombre);

    }

    function setAtrId(id) {

        globalAtrId = id;
        $("#tmpAtrId").val(id);

    }

    function ajaxAttr() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('atributo_categorias/ajaxAtributos'); ?>",
            cache: false,
            data: {nada: "no"},
            dataType: 'text',
            success: function (response) {
                updateTab2(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }

    function ajaxAttrManage(tipoData, idData, valorData) {


        var obj = {
            tipo: tipoData,
            id: idData,
            valor: valorData
        };

        $.ajax({
            type: "POST",
            url: " <?php echo site_url('atributo_categorias/ajaxAtributosManage'); ?> ",
            cache: false,
            data: jQuery.param(obj),
            dataType: 'text',
            success: function (response) {
                ajaxAttr();
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }

        });

    }

    function updateTab2(response) {

        var obj = jQuery.parseJSON(response);
        var nElementos = obj.length

        var contAtr = $("#contAtributos");
        var nodeOriginal = $("#contAtributos #itemAtr0").clone();
        $("#contAtributos").empty();
        var nodeOriginal = $(nodeOriginal).appendTo($("#contAtributos"));


        $(obj).each(function () {

            var id = $(this)[0]["id"];
            var nombre = $(this)[0]["nombre"];
            var node = $("#contAtributos #itemAtr0").clone();

            var jqueryObj = $(node).appendTo($("#contAtributos"));

            $(jqueryObj).show();
            $(jqueryObj).attr("id", "itemAtr" + id);
            $(jqueryObj).addClass("itemAtrView");
            $("#textAtr", jqueryObj).html(nombre);

            if (id < 7) {

                $(".btnEliminarAtr", jqueryObj).attr('onClick', '');
                $(".btnEditarAtr", jqueryObj).attr('onClick', '');
                $(".btnEliminarAtr", jqueryObj).css("opacity", "0.5");
                $(".btnEditarAtr", jqueryObj).css("opacity", "0.5");
                $(".btnEliminarAtr", jqueryObj).css("cursor", "default");
                $(".btnEditarAtr", jqueryObj).css("cursor", "default");

                $("#cambiarNombreAtributo", jqueryObj).attr("href", "javascript: void(0)");
                $("#cambiarNombreAtributo", jqueryObj).css("cursor", "default");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("toggle");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("data-toggle");


            } else {

                $(".btnEditarAtr", jqueryObj).attr('onClick', 'setAtrId("' + id + '")');
                $(".btnEliminarAtr", jqueryObj).attr('onClick', 'eliminarAtr("' + id + '")');
                $(".btnEliminarAtr", jqueryObj).css("opacity", "1");
                $(".btnEditarAtr", jqueryObj).css("opacity", "1");
            }

            
        });


    }
    //====================================================================================
    //====================================================================================    


    //====================================================================================
    //Tab 3
    //====================================================================================

    //-----------------------------------
    //  OnChange
    //-----------------------------------

    $("#select_clasificacion").change(function () {

        if (getSel($(this)) > 0) {
            ajaxClass(getSel($(this)));
        } else {

            var nodeOriginal = $("#contClasificaciones #itemClass0").clone();
            $("#contClasificaciones").empty();
            var nodeOriginal = $(nodeOriginal).appendTo($("#contClasificaciones"));

        }

    });

    //-----------------------------------
    //  OnClick
    //-----------------------------------    
    $("#addClasBtn").click(function () {

        if (!$("#nombreClasificacion").val() == "") {

            // Si no hay un atributo seleccionado en el select
            if (getSel($("#select_clasificacion")) > 0) {
                //(tipoData, idData, valorData)
                

            var node = $("#itemClass0").clone();
            var jqueryObj = $(node).appendTo($("#contClasificaciones"));

            $(jqueryObj).addClass("itemClassView");
            $(jqueryObj).hide();
            $(jqueryObj, jqueryObj).attr('id', '');
            
            $("#textClass", jqueryObj).html( $("#nombreClasificacion").val() );
            $(".btnEliminarClass", jqueryObj).css("opacity", "1");
            $(".btnEditarClass", jqueryObj).css("opacity", "1");  
            $(".btnEditarClass", jqueryObj).attr('onClick', 'javascript:void(0)');
            $(".btnEliminarClass", jqueryObj).attr('onClick', 'javascript:void(0)');
                $("#cambiarNombreAtributo", jqueryObj).attr("href", "javascript: void(0)");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("toggle");
                $("#cambiarNombreAtributo", jqueryObj).removeAttr("data-toggle");
                
            $(jqueryObj).slideDown("slow", function () {               
               ajaxClassManage("add", 0, $("#nombreClasificacion").val(), getSel($("#select_clasificacion")));
            });


            } else {
                alert("Seleccione un atributo");
            }

        } else {
            alert("Digite el valor del atributo");
        }


    });

    function eliminarClass(id) {

        var r = confirm("¿ Desea eliminar el valor?");
        if (r == true) {
            $("#itemClass" + id).slideUp("slow", function () {
                ajaxClassManage("del", id, "", 0);
            });
        }

    }

    //-----------------------------------
    //  CRUDS
    //-----------------------------------    

    function editarClass() {

        var nombre = $("#nuevoNombreClass").val();
        var id = $("#tmpClassId").val();
        $("#nuevoNombreClass").val("");

        ajaxClassManage("set", id, nombre, 0);

    }

    function setClassId(id) {

        globalClassId = id;
        $("#tmpClassId").val(id);

    }

    function ajaxClass(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('atributo_categorias/ajaxClasificacion'); ?>/" + id,
            cache: false,
            data: {nada: "no"},
            dataType: 'text',
            success: function (response) {
                updateTab3(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }

    function ajaxClassManage(tipoData, idData, valorData, idAtrData) {

        var obj = {
            tipo: tipoData,
            id: idData,
            idAtr: idAtrData,
            valor: valorData
        };


        $.ajax({
            type: "POST",
            url: " <?php echo site_url('atributo_categorias/ajaxClasificacionManage'); ?> ",
            cache: false,
            data: jQuery.param(obj),
            dataType: 'text',
            success: function (response) {
                ajaxClass(getSel($("#select_clasificacion")));
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }

        });

    }

    function updateTab3(response) {

        var obj = jQuery.parseJSON(response);

        var nElementos = obj.length;

        var contAtr = $("#contClasificaciones");
        var nodeOriginal = $("#contClasificaciones #itemClass0").clone();
        $("#contClasificaciones").empty();
        var nodeOriginal = $(nodeOriginal).appendTo($("#contClasificaciones"));


        $(obj).each(function () {

            var id = $(this)[0]["id"];
            var nombre = $(this)[0]["valor"];
            var node = $("#contClasificaciones #itemClass0").clone();

            var jqueryObj = $(node).appendTo($("#contClasificaciones"));

            $(jqueryObj).show();
            $(jqueryObj).attr("id", "itemClass" + id);
            $(jqueryObj).addClass("itemClassView");
            $("#textClass", jqueryObj).html(nombre);

            $(".btnEliminarClass", jqueryObj).attr('onClick', 'eliminarClass("' + id + '")');
            $(".btnEliminarClass", jqueryObj).css("opacity", "1");
            $(".btnEditarClass", jqueryObj).css("opacity", "1");

            $(".btnEditarClass", jqueryObj).attr('onClick', 'setClassId("' + id + '")');


        });


    }
    //====================================================================================
    //====================================================================================



    //==========================================

    $(function () {

        $("#form").submit(function () {
            if (getSel($("#categoria_atributos")) == "0")
            {
                alert("Selecicone una categoria")
                return false
            }

            $("#hidden").val(getSel($("#categoria_atributos")));

            guardarCategoria($(this));
            return false;
        });


    });


    $(document).ready(function () {

        $("select.select2").select2("destroy");
        $("select.select2").select2();

        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        //Mostramos lista de atributos
        ajaxAttr();



    });


</script>

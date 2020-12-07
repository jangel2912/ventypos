<style>
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
    .well{
        background-color:#F9F9F9
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
    <h1><?php echo custom_lang("Productos", "Productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
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
                <?php foreach ($data['almacenes'] as $key => $value) { ?>
                    <tr>
                        <td id="almacenNombre"><?php echo $value->nombre ?></td>
                        <td align="right"><input id="almacenId" class="almacenesCant" type="text" name="cantidad" data-almacen="<?php echo $value->id ?>" value="0"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>                   
    <div class="modal-footer">
        <button type="button" onclick="guardar_almacen(this)" class="btn btn-success">Guardar</button> 
        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancelar</button>            
    </div>
</div>


<?php echo form_open_multipart("atributos/setProductoNuevo", array("id" => "validate")); ?>

<div class="well paneles">    

    <div class="row-fluid">

        <div class="row-fluid">             
            <div class="span4">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">OPCIONES</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">
                    <div class="span4">
                        <label>Categoria</label>
                    </div>                                     
                    <div class="span8">

                        <select id="categoria_atributos" name="categoria_atributos" class="select select2" style="width: 100%;">
                            <option value="0">Seleccione una categoria...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['categorias'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                                <?php } ?>

                            </optgroup>
                        </select> 

                        <span id="spanCategoriaCont" style="display:none">
                            <a href="javascript: void(0)" onclick="eliminarCategoria(this)">
                                <span id="spanCategoriaTxt"></span> &nbsp; <b class="text-error">&lt;Remover&gt;</b>
                            </a>
                        </span>

                    </div>                        
                </div>

                <div class="row-fluid">
                    <div class="span4">
                        <label>Impuesto</label>
                    </div>                                     
                    <div class="span8">
                        <select id="select_impuesto" name="impuesto" class="select select2" style="width: 100%;">
                            <option data-porcentaje=" " value="0">Seleccione un impuesto...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['impuestos'] as $value) { ?>
                                    <option data-porcentaje="<?php echo $value->porciento ?> %" value="<?php echo $value->id_impuesto ?>"><?php echo $value->nombre_impuesto . " ( " . $value->porciento . "% )" ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                                                       
                    </div>                        
                </div>                    

                <div class="row-fluid">
                    <div class="span4">
                        <label>Nombre:</label>
                    </div>                                     
                    <div class="span8">
                        <input type="text" id="nombre" name="nombre">        
                    </div>                        
                </div>

                <div class="row-fluid">
                    <div class="span4">
                        <label>Descripción:</label>
                    </div>                                     
                    <div class="span8">
                        <input type="text" id="descripcion" name="descripcion">
                    </div>                        
                </div>                    

            </div>


            <div class="span4 borderPanel">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">ATRIBUTOS</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">                       

                    <div class="span4">
                        <label> <input id="check_marca" type="checkbox" class="active-select" checked> Marca: </label>
                    </div>                        
                    <div class="span8">
                        <select id="marca_principal" name="marca_principal" class="select select2" style="width: 100%;">
                            <option value="0">Seleccione una marca...</option>
                            <optgroup label="-------">
                                <?php foreach ($data['marcas'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                            
                    </div>

                </div>

                <div class="row-fluid">
                    <div class="span4">
                        <label> <input id="check_proveedor" type="checkbox" class="active-select" checked>  Proveedor: </label>
                    </div>                        
                    <div class="span8">
                        <select id="proveedor_principal" name="proveedor_principal" class="select select2" style="width: 100%;">
                            <option value="0">Seleccione un proveedor...</option>
                            <optgroup label="-------">
                                <?php foreach ($data['proveedores'] as $value) { ?>
                                    <option value="<?php echo $value->id_proveedor ?>"><?php echo $value->nombre_comercial ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                            
                    </div>
                </div>                    

                <div class="row-fluid">                       
                    <div class="span4">
                        <label> <input id="check_linea" type="checkbox" class="active-select" checked>  Lineas: </label>
                    </div>                        
                    <div class="span8">
                        <select id="linea" name="linea" class="select select2" style="width: 100%;">
                            <option value="0">Seleccione una linea...</option>
                            <optgroup label="-------">
                                <?php foreach ($data['lineas'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                            
                    </div>
                </div>

                <div class="row-fluid">                       
                    <div class="span4">
                        <label> <input id="check_material" type="checkbox" class="active-select" checked>  Materiales: </label>
                    </div>                        
                    <div class="span8">
                        <select id="tipo_material" name="material"  class="select select2" style="width: 100%;">
                            <option value="0">Seleccione un material...</option>
                            <optgroup label="-------">
                                <?php foreach ($data['materiales'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                            
                    </div>
                </div>                    

                <div class="row-fluid">                       
                    <div class="span4">
                        <label> <input id="check_tipo" type="checkbox" class="active-select" checked>  Tipos: </label>
                    </div>                        
                    <div class="span8">
                        <select id="tipo" name="tipo"  class="select select2" style="width: 100%;">
                            <option value="0">Seleccione un tipo...</option>
                            <optgroup label="-------">
                                <?php foreach ($data['tipos'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>                            
                    </div>
                </div>    
                

            </div>  


            <div class="span4">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">IMAGEN</label>
                    </div>                                                         
                </div>                

                <div class="row-fluid">

                    <div class="span6">
                        <div class="imageDrag">
                            <img id="previewImg" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                        </div>                        
                    </div>                            

                    <div class="span6">

                        <div class="input-append file">
                            <input id="masterFile" type="file" name="imagen" style="display: none" onchange="reloadPreview(this)" />
                            <input placeholder="Cargar imagen"  type="text"/>
                            <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>                               
                        </div>
                        <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                    </div>    

                </div>                    

            </div>

        </div>


    </div>


</div>



<div class="row-fluid">
    <div class="span12">
        <div class="well">
            <input type="hidden" id="fila_seleccionada" value="0">
            <table class="dataSelection" width="100%" id="detalle_producto">
                <thead>
                    <tr id="nameValuesTable">
                        <th class="group-marca">Talla</th>
                        <th class="group-proveedor">Color</th>
                        <th>Código</th>
                        <th>P. Compra</th>
                        <th>P. Venta</th>
                        <th>Activo</th>
                        <th>Tienda</th>
                        <th>Almacenes</th>
                        <th>Acción</th>

                    </tr>
                </thead>
                <tbody>
                    <tr id="valuesTable">
                        <td class="group-marca">
                            <select id="talla" name="talla" class="select select2" style="width: 100%;">
                                <option value="0">Tallas...</option>
                                <optgroup label="-------">                                                                
                                    <?php foreach ($data['tallas'] as $value) { ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                    <?php } ?>
                                </optgroup>
                            </select>
                        </td>

                        <td class="group-proveedor">
                            <select id="color" name="color" class="select select2" style="width: 100%;">
                                <option value="0">Colores...</option>
                                <optgroup label="-------">                                           
                                    <?php foreach ($data['colores'] as $value) { ?>                     
                                        <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                    <?php } ?>
                                </optgroup>
                            </select>
                        </td>

                        <td><input size="3" type="text" name="codigo"></td>
                        <td><input size="3" type="text" name="precio_compra"></td>
                        <td><input size="3" type="text" name="precio_venta"></td>
                        <td align="center"><input id="check_activo" type="checkbox" name="activo" checked="checked" value="1"/></td>
                        <td align="center"><input id="check_tienda" type="checkbox" name="tienda" value="0"/></td>
                        <td align="center"><a id="btnAlmacenes" href="#fModal" class="btn almacenes" role="button" data-toggle="modal">Seleccionar</a></td>
                        <td class="btnAgregar" align="center">                            
                            <button type="button" onclick="agregar_datos(this)" class="btn btn-success"><i class="ico-plus icon-white"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Values send to send in format json-->
<input id="dataJson" type="hidden" name="dataJson" value=''>

</form>

<div class="row-fluid">
    <div class="block">
        <div class="head blue">
            <div class="icon"><span class="ico-pen-2"></span></div>
            <h2>Lista Productos</h2>
            <ul class="buttons">
                <li><a href="#" onclick="source('table_default');
                        return false;"><div class="icon"><span class="ico-info"></span></div></a></li>
            </ul>                              
        </div>                
        <div id="contentDataList" class="data-fluid">

            <table id="defaulTable" class="table" width="100%" style="display:none">
                <thead>
                    <tr id="nameValuesTable">
                        <th id="tImagen" align="center">Imagen</th>
                        <th id="tNombre" align="center">Nombre</th>
                        <th id="tImpuesto" align="center">Impuesto</th>						
                        <th id="tTalla" align="center">Talla</th>
                        <th id="tColor" align="center">Color</th>
                        <!-- <th id="tMarca" align="center">Marca</th> -->
                        <!-- <th id="tProveedor" align="center">Proveedor</th> -->
                        <th id="tMaterial" align="center">Material</th>
                        <!-- <th id="tLinea" align="center">Línea</th> -->
                        <th id="tCodigo" align="center">Código</th>
                        <th id="tCompra" align="center">P.Compra</th>
                        <th id="tVenta" align="center">P.Venta</th>			
                        <!-- <th id="tActivo" align="center">Activo</th> -->
                        <th id="tTienda" align="center">Tienda</th>
                        <th id="tAlmacenes" align="center">Almacenes</th>
                        <th id="tAccion" align="center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="valuesTable">

                        <td id="tImagen" align="center"><img id="previewImg" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="40"></td>
                        <td id="tNombre" align="center"></td>
                        <td id="tImpuesto" align="center"></td>						
                        <td id="tTalla" align="center"></td>
                        <td id="tColor" align="center"></td>
                       <!-- <td id="tMarca"> align="center"</td> -->
                       <!-- <td id="tProveedor" align="center"></td> -->
                        <td id="tMaterial" align="center"></td>
                       <!-- <td id="tLinea" align="center"></td> -->			
                        <td id="tCodigo" align="center"></td>
                        <td id="tCompra" align="center"></td>
                        <td id="tVenta" align="center"></td>			
                       <!-- <td id="tActivo" align="center"></td> -->
                        <td id="tTienda" align="center"></td>
                        <td id="tAlmacenes" align="center">                            
                            <select style="max-width:100px;">
                                <option value="0">Unidades...</option>
                                <optgroup label="-------">                                                                                                      
                                </optgroup>
                            </select>                                                         
                        </td>		
                        <td id="tAccion" align="center">                            
                            <button type="button" onclick="agregar_datos(this)" class="btn red"><i class="ico-cancel icon-white"></i></button>                            
                        </td>			
                    </tr>
                </tbody>
            </table>

        </div>                
    </div>
</div>

<!-- Separador -->
<div class="row-fluid">
    <div class="span12">
        <hr>
        <br>
    </div>
</div>

<!-- Boton Guardar -->
<div class="row-fluid">
    <div class="span12">
        <div id="btnGuardar" class="btn btn-success"> Guardar </div>
        <br><br><br><br><br>
    </div>
</div>




<script src="<?php echo base_url('public/js/form2js.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/previewImg.js'); ?>" type="text/javascript"></script>


<script>



    var baseUrl = "<?php echo site_url(); ?>";
    var jsonData;
    var listaProductos = {};
    var listaAlmacenes = {};
    var indiceProductos = 0;
    var cantidadProductos = 0;

    var imagenActual = "dragDrop.jpg";




    $(function () {


        //===========================================================================
        // On change list an checbox

        //================
        // Checboxes
        //================

        $('#marca_principal').on('change', function (e) {
            var valor = e.val;
            $("#marca").select2("val", valor);
        });

        $('#proveedor_principal').on('change', function (e) {
            var valor = e.val;
            $("#proveedor").select2("val", valor);
        });
        

        $('#categoria_atributos').on('change', function () {
            if (this.value > 0) {

                $("#s2id_categoria_atributos").hide(); //Ocultamos el selct2 categoria                                
                $("#spanCategoriaCont").show(); // Mostramos el span de categoria

                // Mostramos el span de categoria
                var textCategoria = getSelText($('#categoria_atributos'));
                $("#spanCategoriaTxt").text(textCategoria);

                enabledSel($('#color'), true);
                enabledSel($('#talla'), true);

            } else {
                resetValues();
            }

        });


        //================
        // Checboxes
        //================

        //When check inventarios change, active and deactive checkbox ventas
        $("#check_marca").change(function () {

            if (this.checked)
                enabledSel($('#marca_principal'), true);
            else
                enabledSel($('#marca_principal'), false);
        });

        $("#check_proveedor").change(function () {

            if (this.checked)
                enabledSel($('#proveedor_principal'), true);
            else
                enabledSel($('#proveedor_principal'), false);
        });

        $("#check_linea").change(function () {

            if (this.checked)
                enabledSel($('#linea'), true);
            else
                enabledSel($('#linea'), false);
        });

        $("#check_material").change(function () {

            if (this.checked)
                enabledSel($('#tipo_material'), true);
            else
                enabledSel($('#tipo_material'), false);

        });
        $("#check_tipo").change(function () {

            if (this.checked)
                enabledSel($('#tipo'), true);
            else
                enabledSel($('#tipo'), false);

        });

        //===========================================================================
        // RESETS
        //===========================================================================

        eliminarCategoria = function () {
            resetValues();
        }

        // Reset inputs texts
        resetInput = function () {
            $('input').val("")
        }

        // Reset to defauul checks
        resetChecks = function () {

            setCheck($("#check_activo"), true);
            setCheck($("#check_tienda"), false);

            setCheck($("#check_marca"), true);
            setCheck($("#check_proveedor"), true);
            setCheck($("#check_linea"), true);
            setCheck($("#check_material"), true);
            setCheck($("#check_tipo"), true);

        }

        // Reset to index 0 all selects
        resetSel = function () {
            $('form select').prop('selectedIndex', 0);
            toSel2();
        }


        // MASTER RESET
        resetValues = function () {

            //Categoria
            setSel($("categoria_atributos"), 0); // Select categoria a posicion 0
            $("#s2id_categoria_atributos").show(); // Ocultamos el selct2 categoria                                
            $("#spanCategoriaCont").hide(); // Mostramos el span de categoria                                


            //Reset Selects
            resetSel();
            //Reset input boxs
            resetInput();
            //Reset Checks
            resetChecks();


            enabledSel($('#color'), false);
            enabledSel($('#talla'), false);

            //cantidad de almacenes
            $('input#almacenId,input#almacenId').val(0)

            $("#previewImg").attr("src", "<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg");


        }


        //-----------------------------------------------
        //Selects Functions
        //-----------------------------------------------

        // All select with class select2
        toSel2 = function () {
            $("select.select2").select2("destroy");
            $("select.select2").select2();
        }



        //-----------------------------------
        //  SELECTS
        //-----------------------------------

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


        //-----------------------------------
        //  CHECKS
        //-----------------------------------

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

            if ($(id).prop('checked')) {
                return true;
            } else {
                return false;
            }

        }


        //-----------------------------------------------
        guardar_almacen = function (elem) {

            $('#fModal').modal('hide');
            $(".tip").tooltip({html: true, placement: 'top', trigger: 'hover'});
        }


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


        eliminarProdcuto = function (elemento) {
            cantidadProductos = cantidadProductos - 1;
            delete listaProductos["producto" + elemento];
            $(".idProducto" + elemento).remove();
        }


        convertFormToLabel = function (copy, indiceObj, stringAtributos) {

            var filaTitulo = $(copy).find("> tHead > tr");
            var fila = $(copy).find("> tbody > tr");

            var obj = listaProductos["producto" + indiceObj];



            // ASIGNAR IMAGEN A PRODCUTO

            var tmpImagen = imagenActual.split("\\");
            tmpImagen = tmpImagen[ tmpImagen.length - 1 ];
            obj["imagen"] = tmpImagen;
            delete obj["imagenes"]; // Eliminamos el array de imagenes por que genera error en la conversion a obj

            // ASIGNAR IMAGEN A PRODCUTO


            // Set mini image from image master
            $("#previewImg", fila).attr("src", $(".imageDrag #previewImg").attr("src"));
            // Set name
            $("#tNombre", fila).html(obj["nombre"]);

            // Set impuesto                                                                                
            if (obj["impuesto"] == "0") {
                $("#tImpuesto", fila).remove();
                $("#tImpuesto", filaTitulo).remove();
            } else {
                var selected = $("#select_impuesto").find('option:selected');
                var extra = selected.data('porcentaje');
                $("#tImpuesto", fila).html(extra);
            }

            // Set Talla
            if (obj["talla"] == "0") {
                $("#tTalla", fila).remove();
                $("#tTalla", filaTitulo).remove();
            } else {
                $("#tTalla", fila).html(getSelText($("#talla")));
            }

            // Set Color
            if (obj["color"] == "0") {
                $("#tColor", fila).remove();
                $("#tColor", filaTitulo).remove();
            } else {
                $("#tColor", fila).html(getSelText($("#color")));
            }




            // Set Materiales
            if (obj["material"] == "0") {
                $("#tMaterial", fila).remove();
                $("#tMaterial", filaTitulo).remove();
            } else {
                $("#tMaterial", fila).html(getSelText($("#tipo_material")));
            }

            // Set Tipo
            if (obj["tipo"] == "0") {
                $("#tTipo", fila).remove();
                $("#tTipo", filaTitulo).remove();
            } else {
                $("#tTipo", fila).html(getSelText($("#tipo")));
            }



            //  Set codigo                                        
            $("#tCodigo", fila).html(obj["codigo"]);
            //  Set compra                                      
            $("#tCompra", fila).html(obj["precio_compra"]);
            //  Set venta                                        
            $("#tVenta", fila).html(obj["precio_venta"]);


            //  Set Tienda
            obj["tienda"] == "1" ? $("#tTienda", fila).html("Si") : $("#tTienda", fila).html("No");

            // SetAlmacenes
            var almacenes = obj["lista_almacenes"];
            var almCant = almacenes["cantidad_almacenes"];
            var items = [];
            for (var i = 1; i <= almCant; i++) {

                var n = almacenes["almacen" + i]["unidades"];
                var name = almacenes["almacen" + i]["nomber_almacen"];

                items.push({
                    val: i + "",
                    txt: "(" + n + ") unidades en " + name
                });

            }

            // Añadimos los valores de los almacenes al select
            addSel($("#tAlmacenes select", fila), items);

            //================================================
            // NOMBRE DEL PRODUCTO Y PARAMETRO PÁRA VALIDAR QUE UN OBJETO YA EXISTE
            //================================================
            obj["nombreString"] = stringAtributos;

        }



        validacion = function () {


            if (getSel($("#select_impuesto")) == 0) {
                alert("Seleccione un impuesto");
                return false
            }
            if ($("input[name=nombre]").val() == "") {
                alert("Digite el nombre del producto");
                return false
            }

            if (getCheck($("#check_marca")) && getSel($("#marca_principal")) == 0) {
                alert("Seleccione una marca");
                return false
            }
            if (getCheck($("#check_proveedor")) && getSel($("#proveedor_principal")) == 0) {
                alert("Seleccione un proveedor");
                return false
            }
            if (getCheck($("#check_linea")) && getSel($("#linea")) == 0) {
                alert("Seleccione una linea");
                return false
            }
            if (getCheck($("#check_material")) && getSel($("#tipo_material")) == 0) {
                alert("Seleccione un material");
                return false
            }
            
            if (getCheck($("#check_tipo")) && getSel($("#tipo")) == 0) {
                alert("Seleccione un tipo");
                return false
            }            

            if (getSel($("#talla")) == 0) {
                alert("Seleccione una talla");
                return false
            }
            if (getSel($("#color")) == 0) {
                alert("Seleccione un color");
                return false
            }

            if ($("input[name=codigo]").val() == "") {
                alert("Digite el código de barras");
                return false
            }
            if ($("input[name=precio_compra]").val() == "") {
                alert("Digite el precio de compra");
                return false
            }
            if ($("input[name=precio_venta]").val() == "") {
                alert("Digite el precio de venta");
                return false
            }


            var almacenes = 0;

            $(".almacenesCant").each(function () {
                if ($(this).val() == "0") {

                } else {
                    almacenes = 1;
                }
            })

            return true;

        }

        agregar_datos = function () {


            // If there any "Atributo categoria" selected
            if (parseInt($('#categoria_atributos').val()) > 0) {

                // VALIDACIÓN FORMULARIO
                var validado = validacion();
                if( !validado ) return true;


                //=====================================================
                //     VALIDACIÓN, EXISTE YA EL PRODUCTO?
                //=====================================================

                // Generamos string de validacion   
                var stringAtributos = "";

                // TRIM espacios y eliminar multiples espacios
                var nombre = $("#nombre").val();
                nombre = nombre.replace(/\s\s+/g, ' ');
                nombre = $.trim(nombre);

                stringAtributos += nombre + "/";
                stringAtributos += $("#marca_principal").val() != "0" ? $("#marca_principal option:selected").text() + "/" : "/";
                stringAtributos += $("#talla").val() != "0" ? $("#talla option:selected").text() + "/" : "/";
                stringAtributos += $("#color").val() != "0" ? $("#color option:selected").text() + "/" : "/";
                stringAtributos += $("#tipo_material").val() != "0" ? $("#tipo_material option:selected").text() + "/" : "/";
                //stringAtributos += $("#proveedor_principal").val() != "0" ? $("#proveedor_principal option:selected").text() + "/" : "/";
                stringAtributos += $("#linea").val() != "0" ? $("#linea option:selected").text() + "/" : "/";
                stringAtributos += $("#tipo").val() != "0" ? $("#tipo option:selected").text() + "" : "";


                var validarExistencia = false;

                //---------------------------------------
                // VALIDAR EN OBJETOS JAVASCRIPT
                $.each(listaProductos, function (key, val) {
                    console.log(val["nombreString"]);
                    if (stringAtributos == val["nombreString"]) {
                        validarExistencia = true;
                        return false;
                    }
                });


                //----------------------------------------
                // VALIDAR EN BASE DE DATOS
                if (getAjaxProductoExiste(stringAtributos) == "1") {
                    validarExistencia = true;
                }

                if (validarExistencia) {
                    alert("El producto ya existe!\nSeleccione otro nombre u otros atributos");
                    return false;
                }


                //=====================================================
                //=====================================================                                            


                // Index and count TMP products
                indiceProductos = indiceProductos + 1;
                cantidadProductos = cantidadProductos + 1;


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
                var objActual = listaProductos["producto" + indiceProductos] = formToObjActual;





                // Save almacenes in the object listaAlmacenes                                
                // Add list Almacenes to list Products                                
                //listaProductos["lista_almacenes"]=listaAlmacenes;                                
                var listaAlmacen = guardarAlmacenObj();
                objActual["lista_almacenes"] = listaAlmacen;

                //=========================================================================

                // Clone style table for display list
                var tableData = $('#defaulTable').clone();
                // Clone style table for display list
                var jqueryObj = $(tableData).appendTo('#contentDataList');

                var idProducto = "idProducto" + indiceProductos;

                // Multiple configuration for new table in list
                $(jqueryObj).addClass(idProducto);
                $(jqueryObj).attr("id", "");
                $(jqueryObj).find("button").attr("onclick", "eliminarProdcuto('" + indiceProductos + "')");
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



                // GUARDAMOS IMAGEN
                if ($("#masterFile").val() != "") {

                    imagenActual = $("#masterFile").val();

                    $("#masterFile").addClass("masterFile2").attr("id", "").attr("name", "imagenes[]").prependTo($(".input-append").parent());
                    $(".input-append").prepend('<input id="masterFile" type="file" name="imagen" style="display: none" onchange="reloadPreview(this)" />');

                }

                // Debug imagenes
                $(".masterFile2").each(function () {
                    console.log($(this).prop('files')[0]['name'])
                })



                //Convert form to label
                convertFormToLabel(jqueryObj, indiceProductos, stringAtributos);


            } else {
                alert("Por favor seleccione una categoria de atributo");
            }

        }



        $("#btnGuardar").click(function () {

            $("#validate").submit();

        });

        $("#validate").submit(function () {

            // Si ya tenemos objetos almacenados enviamos formulario
            if (cantidadProductos > 0) {

                //Creacion de json string
                $("#dataJson").attr("value", "");

                listaProductos["cantidad"] = cantidadProductos;

                var jsonDataString = JSON.stringify(listaProductos);
                jsonDataString = jsonDataString.replace('[', '');
                jsonDataString = jsonDataString.replace(']', '');

                $("#dataJson").attr("value", jsonDataString);

                //enviar_correo($(this));
                //return false;                                
            } else {
                alert("Debe ingresar mínimo un producto");
                return false;
            }

        });

    });



//=========================================================================
//  IMAGE INPUT FILE PREVIEW
//=========================================================================


    var reloadPreview = function (inputFile) {

        var file = inputFile.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $('#previewing').attr('src', 'noimage.png');
            return false;
        } else {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(inputFile.files[0]);
        }
    }

    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#previewImg').attr('src', e.target.result);
        $('#previewImg').attr('width', '250px');
        $('#previewImg').attr('height', '230px');
    }
    ;




//===========================================================================
//===========================================================================
//
//      AJAX
//
//===========================================================================
//===========================================================================


    //===========================================================================
    // Existe ya el producto?
    //===========================================================================

    function getAjaxProductoExiste(stringAtributos) {

        var respuesta = "-1";

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('/atributos/getAjaxProductoExiste'); ?>",
            cache: false,
            async: false,
            data: {atributos: stringAtributos},
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


//===========================================================================
//===========================================================================
//
//      INIT
//
//===========================================================================
//===========================================================================

    $(document).ready(function (e) {

        resetValues();

    });

</script>


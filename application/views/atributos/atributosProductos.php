<style>
    td,th{
        padding: 0px;
    }
    
    .producto
    {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
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
<script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>
<script>
    $(document).on('blur','.dataMoneda',function(){
        $(this).val(limpiarCampo($(this).val()));
    });
    $(document).on('click','')
</script>

<div class="page-header">    
    <div class="icon">
        <img alt="productos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_productos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Productos", "Productos");?></h1>
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
                    <th>Almacén</th>
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
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button> 
        <button type="button" onclick="guardar_almacen(this)" class="btn btn-success">Guardar</button>                    
    </div>
</div>


<?php echo form_open_multipart("atributos/setProductoNuevo", array("id" => "validate")); ?>

<div class="well paneles">    

    <div class="row-fluid">

        <div class="row-fluid">
            <ul class="nav nav-tabs">
                <li class="active "><a href="#info" data-toggle="tab">Información</a>  </li>
                <li><a  href="#img" data-toggle="tab" >Imágenes</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="info">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span9">
                                <label class="titlePanel">OPCIONES</label>
                            </div>
                            <div class="span3">
                                <label class="titlePanel">ATRIBUTOS</label>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span9">
                            <div class="row-fluid">
                                <div class="span4">
                                    <label>Categoría</label>
                                    <select id="categoria_atributos" name="categoria_atributos" class="select select2" style="width: 100%;">
                                        <option value="0">Seleccione una categoría...</option>
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
                                <div class="span4">
                                    <label>Impuesto</label>
                                    <select id="select_impuesto" name="impuesto" class="select select2" style="width: 100%;">
                                        <option data-porcentaje=" " value="0">Seleccione un impuesto...</option>
                                        <optgroup label="-------">
                                            <?php foreach ($data['impuestos'] as $value) { ?>
                                                <option data-porcentaje="<?php echo $value->porciento ?> %" value="<?php echo $value->id_impuesto ?>"><?php echo $value->nombre_impuesto . " ( " . $value->porciento . "% )" ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="span4">
                                    <label>Nombre:</label>
                                    <input type="text" id="nombre" name="nombre">
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <label>Descripción:</label>
                                    <input type="text" id="descripcion" name="descripcion">
                                </div>
                                <div class="span4">
                                    <label>Referencia:</label>
                                    <input type="text" size="3" name="referencia" value="">
                                </div>
                            </div>
                            <input type="hidden" id="fila_seleccionada" value="0">
                            <div class="row-fluid">
                                <div class="span4">
                                    <label for="">Código de barras automático</label><br>
                                    <input id="check_codigo_automatico" maxlength="16" type="checkbox" name="check_codigo_automatico" value="0"/>
                                </div>
                                <div class="span4">
                                    <label for="">Activo</label><br>
                                    <input id="check_activo" type="checkbox" name="activo" checked="checked" value="1"/>
                                </div>
                                <div class="span4">
                                    <label for="">Tienda</label><br>
                                    <input id="check_tienda" type="checkbox" name="tienda" value="0"/>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <label for="">Código de barras</label>
                                    <input size="3" maxlength="16" type="text" name="codigo">
                                </div>
                                <div class="span3">
                                    <label for="">P. Compra</label>
                                    <input size="3" type="text" name="precio_compra" class="dataMoneda">
                                </div>
                                <div class="span3">
                                    <label for="">P. Venta</label>
                                    <input size="3" type="text" name="precio_venta" class="dataMoneda">
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span2">
                                    <label for=""></label><br>
                                    <a id="btnAlmacenes" href="#fModal" class="almacenes" role="button" data-toggle="modal">Almacenes</a>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <br>
                                <!--<button id="agregar" type="button" onclick="agregar_datos(this)" class="btn btn-success"><i class="ico-plus icon-white"></i> Agregar</button>-->
                                <a id="agregar" href="#" data-tooltip="Agregar Producto" onclick="agregar_datos(this)">
                                    <img alt="Agregar Producto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">  
                                </a>
                            </div>
                        </div>
                        <div id="atributos" class="span3 atributos">
                            <?php 
                                $list_atributos = '';
                                foreach ($data['atributos'] as $atributo) {
                                $list_atributos .= $atributo['id'].',';
                            ?>
                                <div class="row-fluid" data-categorias="<?= $atributo['categorias'] ?>" style="display: none;">
                                    <div class="span12">
                                        <label for="">
                                            <?= $atributo['nombre'] ?>
                                        </label>
                                        <select id="atributo_<?= $atributo['id'] ?>" name="atributo_<?= $atributo['id'] ?>" data-id="<?= $atributo['id'] ?>" data-atributo="<?= $atributo['nombre'] ?>" class="select select2" style="width: 100%;">
                                            <option value="0">Seleccione un valor...</option>
                                            <optgroup label="-------">
                                                <?php foreach ($atributo['detalles'] as $detalle) : ?>
                                                    <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['valor'] ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <input type="hidden" name="attrid" data-value=<?php echo substr($list_atributos, 0, strlen($list_atributos)-1) ?> />
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="img">
                    <div class="span12">
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen Principal</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg0" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append0 file">
                                        <input id="masterFile0" type="file" name="imagen0" style="display: none" onchange="reloadPreview(this,0)" />
                                        <input placeholder="Cargar imagen"  type="text"/>
                                        <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                    </div>
                                    <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg1" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append1 file">
                                        <input id="masterFile1" type="file" name="imagen1" style="display: none" onchange="reloadPreview(this,1)" />
                                        <input placeholder="Cargar imagen"  type="text"/>
                                        <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                    </div>
                                    <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg2" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append2 file">
                                        <input id="masterFile2" type="file" name="imagen2" style="display: none" onchange="reloadPreview(this,2)" />
                                        <input placeholder="Cargar imagen"  type="text"/>
                                        <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                    </div>
                                    <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span12" style="margin: 10px 0px 0px 0px;">
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg3" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append3 file">
                                        <input id="masterFile3" type="file" name="imagen3" style="display: none" onchange="reloadPreview(this,3)" />
                                        <input placeholder="Cargar imagen"  type="text"/>
                                        <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                    </div>
                                    <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg4" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append4 file">
                                        <input id="masterFile4" type="file" name="imagen4" style="display: none" onchange="reloadPreview(this,4)" />
                                        <input placeholder="Cargar imagen"  type="text"/>
                                        <button type="button" class="btn"><i class="icon-folder-open icon-white"></i></button>
                                    </div>
                                    <div class="infoImage">Arrastre una imagen al cuadro punteado o presione el botón</div>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span12">
                                    <label class="titlePanel">Imagen</label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="imageDrag">
                                        <img id="previewImg5" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" width="137">
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="input-append5 file">
                                        <input id="masterFile5" type="file" name="imagen5" style="display: none" onchange="reloadPreview(this,5)" />
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
            <!-- /.panel-body -->
        </div>
    </div>
</div>


<!-- Values send to send in format json-->
<input id="dataJson" type="hidden" name="dataJson" value=''>

</form>

<div class="row-fluid">
    <div class="block">
        <div class="head blue">            
            <h2>Lista Productos</h2>
            <ul class="buttons">
                <li><a href="#" onclick="source('table_default'); return false;"></a></li>
            </ul>                              
        </div>                
        <div id="contentDataList" class="data-fluid">
            <div class="producto row-fluid" id="clone" style="display:none;">
                <div class="span12">
                    <div class="row-fluid">
                        <div class="span12">
                            <h5>
                                <img data-rel="imagen" src="<?php echo base_url('public/img/productos'); ?>/dragDrop.jpg" alt="" style="height:10px !important; display:none;">
                                <span data-rel="nombre">Nombre</span>
                                <a href="#" class="eliminar pull-right red acciones" style="width: 5%;"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>
                                
                            </h5>
                            <p>
                                <small data-rel="descripcion">
                                    descripcion
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <label for="">Referencia</label> <br>
                            <span data-rel="referencia">1223456</span>
                        </div>
                        <div class="span3">
                            <label for="">Codigo de barras</label> <br>
                            <span data-rel="codigo">1222343456</span>
                        </div>
                        <div class="span3">
                            <label for="">Activo</label> <br>
                            <span data-rel="activo">Si</span>
                        </div>
                        <div class="span3">
                            <label for="">Tienda</label> <br>
                            <span data-rel="tienda">Si</span>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <label for="">Precio de compra</label> <br>
                            <span data-rel="compra">1223456</span>
                        </div>
                        <div class="span3">
                            <label for="">Precio de venta</label> <br>
                            <span data-rel="venta">1222343456</span>
                        </div>
                        <div class="span3">
                            <label for="">Almacenes</label> <br>
                            <select name="almacenes" id="">
                                <option value="0">Unidades...</option> 
                                <optgroup label="-------">                                                                                                       
                                </optgroup> 
                            </select>
                        </div>
                    </div>
                </div>
            </div>
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

    var imagenActual = [];

    for(i=0;i<6;i++){
        imagenActual[i] = "dragDrop.jpg";
    }



    $(function () {


        //===========================================================================
        // On change list an checbox

        //================
        // Checboxes
        //================
        var show_attr_id = function(id)
        {
            $('select[name^="atributo_"]').each(function(index, el) {
                $(el).val(0).trigger("change");
                var div = $(el).closest('div.row-fluid');
                var categorias = div.data('categorias');
                var arr_cat = categorias.length > 0 ? categorias.split(',') : '';
                var array = [];
                
                if ($.isArray(arr_cat))
                {
                    $.each(arr_cat, function(i, e){
                        array.push(parseInt(e));
                    });
                } else {
                    array.push(categorias);
                }

                if(array.length == 0 || id == 0)
                {
                    div.fadeOut('fast');
                } else {
                    if ($.inArray(id, array) >= 0)
                        div.fadeIn('fast');
                    else
                        div.fadeOut('fast');
                }
            });
        }

        $('#categoria_atributos').on('change', function () {
            show_attr_id(parseInt(this.value));
            if (this.value > 0) {
                $("#s2id_categoria_atributos").hide(); //Ocultamos el selct2 categoria                                
                $("#spanCategoriaCont").show(); // Mostramos el span de categoria

                // Mostramos el span de categoria
                var textCategoria = getSelText($('#categoria_atributos'));
                $("#spanCategoriaTxt").text(textCategoria);
            } else {
                resetValues();
            }

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

        // MASTER RESET
        resetValues = function () {
            resetInput();
            //cantidad de almacenes
            setSel($("categoria_atributos"), 0);
            $("#s2id_categoria_atributos").show(); // Ocultamos el selct2 categoria                                
            $("#spanCategoriaCont").hide(); // Mostramos el span de categori

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

            var obj = listaProductos["producto" + indiceObj];

            copy.find('*[data-rel="nombre"]').html(obj.nombre+'<br>'+stringAtributos);
            copy.find('*[data-rel="descripcion"]').text('Descripción: '+obj.descripcion);
            copy.find('*[data-rel="referencia"]').text(obj.referencia);
            copy.find('*[data-rel="codigo"]').text(obj.codigo_automatico == '1' ? 'automatico' : obj.codigo);
            copy.find('*[data-rel="activo"]').text(obj.activo ? 'Si' : 'No');
            copy.find('*[data-rel="tienda"]').text(obj.tienda ? 'Si' : 'No');
            copy.find('*[data-rel="compra"]').text(obj.precio_compra);
            copy.find('*[data-rel="venta"]').text(obj.precio_venta);

            // ASIGNAR IMAGEN A PRODCUTO
            for(i=0;i<6;i++){
                var tmpImagen = imagenActual[i].split("\\");
                tmpImagen = tmpImagen[ tmpImagen.length - 1 ];
                obj["imagen"+i] = tmpImagen;
            }

            delete obj["imagenes"]; // Eliminamos el array de imagenes por que genera error en la conversion a obj


            // ASIGNAR IMAGEN A PRODCUTO


            // Set mini image from image master
            // $("#previewImg", fila).attr("src", $(".imageDrag #previewImg0").attr("src"));

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
            addSel(copy.find('select[name="almacenes"]'), items);

            //================================================
            // NOMBRE DEL PRODUCTO Y PARAMETRO PÁRA VALIDAR QUE UN OBJETO YA EXISTE
            //================================================
            obj["nombreString"] = stringAtributos;

        }

        $('input[name="check_codigo_automatico"]').on('click', function(e){
            if($(this).is(':checked'))
            {
                $("input[name=codigo]").prop('readonly', true);
            } else {
                $("input[name=codigo]").prop('readonly', false);
            }
        });



        validacion = function () {

            if ($("input[name=nombre]").val() == "") {
                alert("Digite el nombre del producto");
                return false
            }
            if ($("input[name=codigo]").val() == "" && !$('input[name="check_codigo_automatico"]').is(':checked')) {
                alert("Digite el código de barras");
                return false
            }
            if ($("input[name=referencia]").val() == "") {
                alert("Digite el número de referencia");
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

        function creacionDeElementos()
        {
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
                    $('#atributos select').each(function(i, e)
                    {
                        if($(e).val() != '0')
                            stringAtributos += $(e).find('option:selected').text()+'/';
                    });

                    var validarExistencia = false;

                    // VALIDAR EN OBJETOS JAVASCRIPT
                    $.each(listaProductos, function (key, val) {
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
                    var tableData = $('#clone').clone();
                    // Clone style table for display list
                    var jqueryObj = $(tableData).appendTo('#contentDataList');

                    var idProducto = "idProducto" + indiceProductos;

                    // Multiple configuration for new table in list
                    $(jqueryObj).addClass(idProducto);
                    $(jqueryObj).attr("id", "");
                    $(jqueryObj).find("a").attr("onclick", "eliminarProdcuto('" + indiceProductos + "')");
                    $(jqueryObj).show();


                    //=========================================================================
                    //Adding custome attributes to OBJ
                    //=========================================================================

                    //Captura el id de la categoria atributo
                    objActual["categoria_atributos"] = $("#categoria_atributos").val();
                    objActual["attrid"] = $('input[name="attrid"]').data('value');
                    //Captura el nombre del proveedor y añadimos al objeto                        
                    objActual["nombre_proveedor"] = getSelText($("#proveedor_principal"));
                    //Checks list activo y tienda
                    objActual["activo"] = getCheck($("#check_activo")) ? "1" : "0";
                    objActual["tienda"] = getCheck($("#check_tienda")) ? "1" : "0";
                    objActual["codigo_automatico"] = getCheck($("#check_codigo_automatico")) ? "1" : "0";



                    // GUARDAMOS IMAGEN

                        for(i=0;i<6;i++){
                            if ($("#masterFile"+i).val() != "") {
                                imagenActual[i] = $("#masterFile"+i).val();
                                $("#masterFile"+i).addClass("masterFile2").attr("id", "").attr("name", "imagenes"+i+"[]").prependTo($(".input-append"+i).parent());
                                $(".input-append"+i).prepend('<input id="masterFile'+i+'" type="file" name="imagen'+i+'" style="display: none" onchange="reloadPreview(this,'+i+')" />');
                            }
                        }

                        


                    //Convert form to label
                    convertFormToLabel(jqueryObj, indiceProductos, stringAtributos);
        }

        agregar_datos = function () {
           

            // If there any "Atributo categoria" selected
            if (parseInt($('#categoria_atributos').val()) > 0)
                {
                //Validar que por lo menos este un atributo seleccionado
                var __atributos__ = false;
                $('#atributos select').each(function(i, e)
                {
                    if($(e).val() != '0')
                    {
                        __atributos__ = true;

                    }
                });
                if(__atributos__)
                {
                    
                    // VALIDACIÓN FORMULARIO

                    var validado = validacion();
                    if( !validado ) return true;
                    
                    if($("input[name=codigo]").val() != "" && !$('input[name="check_codigo_automatico"]').is(':checked'))
                    {
                        codigo = $("input[name=codigo]").val();
                        $.post
                        (   
                            "<?php echo site_url("productos/validateCodigo") ?>",
                            {
                                "codigo":codigo
                            },function(data)
                            {
                                if(data != 0)
                                {
                                    alert("El codigo de barras ya existe, ingrese otro que no se haya usado");
                                    return true;
                                }else
                                {
                                    creacionDeElementos();
                                    return true;
                                }
                            },'json'
                        );
                    }else
                    {
                        creacionDeElementos();
                    }
                } else {
                    alert("Por favor seleccione un atributo para el producto");
                }
                
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


    var reloadPreview = function (inputFile,id) {
        var file = inputFile.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $('#previewImg'+id).attr('src', 'noimage.png');
            return false;
        } else {
            var reader = new FileReader();
            switch (id) {
                case 0:
                    reader.onload = imageIsLoaded1;
                    break;
                case 1:
                    reader.onload = imageIsLoaded2;
                    break;
                case 2:
                    reader.onload = imageIsLoaded3;
                    break;
                case 3:
                    reader.onload = imageIsLoaded4;
                    break;
                case 4:
                    reader.onload = imageIsLoaded5;
                    break;
                case 5:
                    reader.onload = imageIsLoaded6;
                    break;
            }

            reader.readAsDataURL(inputFile.files[0]);
        }
    }

    function imageIsLoaded1(e) {
        $("#file").css("color", "green");
        $('#previewImg0').attr('src', e.target.result);
        $('#previewImg0').attr('width', '250px');
        $('#previewImg0').attr('height', '230px');
    }
    function imageIsLoaded2(e) {
        $("#file").css("color", "green");
        $('#previewImg1').attr('src', e.target.result);
        $('#previewImg1').attr('width', '250px');
        $('#previewImg1').attr('height', '230px');
    }
    function imageIsLoaded3(e) {
        $("#file").css("color", "green");
        $('#previewImg2').attr('src', e.target.result);
        $('#previewImg2').attr('width', '250px');
        $('#previewImg2').attr('height', '230px');
    }
    function imageIsLoaded4(e) {
        $("#file").css("color", "green");
        $('#previewImg3').attr('src', e.target.result);
        $('#previewImg3').attr('width', '250px');
        $('#previewImg3').attr('height', '230px');
    }
    function imageIsLoaded5(e) {
        $("#file").css("color", "green");
        $('#previewImg4').attr('src', e.target.result);
        $('#previewImg4').attr('width', '250px');
        $('#previewImg4').attr('height', '230px');
    }
    function imageIsLoaded6(e) {
        $("#file").css("color", "green");
        $('#previewImg5').attr('src', e.target.result);
        $('#previewImg5').attr('width', '250px');
        $('#previewImg5').attr('height', '230px');
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


<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/dataTables.bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/buttons.dataTables.min.css'); ?>">


<style>


    hr{       
        background-color: #ddd;
        height: 1px;
        margin:2px;
        margin-bottom: 10px;
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

    .paging_full_numbers{
        margin-bottom: 40px;
    }
    .contenedorTabla{
        padding-bottom: 80px;
    }
    
    .paginate_button{
        margin: 10px 0px 60px 0px;
    }
    
</style>



<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes Productos", "Informes Productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>



<?php echo form_open_multipart("atributosInformes/qPivote", array("id" => "validate")); ?>


    <div class="row-fluid">

    <div class="span2 well">

        <div class="row-fluid">

            <div class="span12">                


                <div class="row-fluid">


                    <div class="row-fluid">

                        <div class="row-fluid">
                            <div class="span12">
                                <label class="titlePanel">ALMACENES</label>
                            </div>  
                        </div>
                        <div class="row-fluid">
                            <div class="span12">                        

                                <select id="almacenes_select" name="almacenes" class="select select2" style="width: 100%;">
                                    <option value="0">Todas los almacenes...</option>
                                    <optgroup label="-------">                                                                  
                                        <?php foreach ($data['almacenes'] as $value) { ?>
                                            <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                                        <?php } ?>

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="row-fluid">

                        <div class="row-fluid">
                            <div class="span12">
                                <label class="titlePanel">CATEGORIAS</label>
                            </div>  
                        </div>
                        <div class="row-fluid">
                            <div class="span12">                        

                                <select id="categorias" name="categorias" class="select select2" style="width: 100%;">
                                    <option value="0">Todas las categorias...</option>
                                    <optgroup label="-------">                                                                  
                                        <?php foreach ($data['categorias'] as $value) { ?>
                                            <option value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>
                                        <?php } ?>

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>                        



            </div>  

        </div>



    </div>    

    <div class="span10 well">


        <div class="row-fluid">  

            <div class="span2">

                <div class="row-fluid">

                    <div class="span12">

                        <label class="titlePanel">MARCA</label>

                    </div>  

                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="marcas" name="marcas" class="select select2" style="width: 100%;">
                            <option value="0">Todas las marca...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['marcas'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>

                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>

            </div>


            <div class="span2">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">TALLAS</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="tallas" name="tallas" class="select select2" style="width: 100%;">
                            <option value="0">Todas las tallas...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['tallas'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>

                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>



            </div>  


            <div class="span2">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">COLORES</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="colores" name="colores" class="select select2" style="width: 100%;">
                            <option value="0"> Todos los colores...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['colores'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>



            </div>  



            <div class="span2">

                <div class="row-fluid">

                    <div class="span12">

                        <label class="titlePanel">MATERIALES</label>

                    </div>  

                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="materiales" name="materiales" class="select select2" style="width: 100%;">
                            <option value="0">Todos los materiales...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['materiales'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>

                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>

            </div>


            <div class="span2">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">PROVEEDORES</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="proveedores" name="proveedores" class="select select2" style="width: 100%;">
                            <option value="0">Todas las tallas...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['proveedores'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>

                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>



            </div>  


            <div class="span2">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">LÍNEAS</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="lineas" name="lineas" class="select select2" style="width: 100%;">
                            <option value="0">Todas las linea...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['lineas'] as $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->valor ?></option>
                                <?php } ?>
                            </optgroup>
                        </select> 
                    </div>                                                         
                </div>

            </div>  

        </div>        


    </div>



</div>

    <!-- Values send to send in format json-->
    <input id="dataJson" type="hidden" name="dataJson" value=''>

</form>

<div id="contentDataList" class="data-fluid contenedorTabla">

    <table id="testTable" class="table" width="100%"/> 
    
</div>






<script src="<?php echo base_url('public/js/form2js.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/previewImg.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('public/export/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/jszip.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/pdfmake.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.html5.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.print.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/dataTables.buttons.min.js'); ?>" type="text/javascript"></script>


<script>



    var baseUrl = "<?php echo site_url(); ?>";
    var jsonData;
    var listaProductos = {};
    var listaAlmacenes = {};
    var indiceProductos = 0;
    var cantidadProductos = 0;
    var obj = {};



    //===========================================================================
    // On change SELECTS

    $('#colores').on('change', function (e) {
        generateString();
    });
    $('#tallas').on('change', function (e) {
        generateString();
    });
    $('#marcas').on('change', function (e) {
        generateString();
    });
    $('#proveedores').on('change', function (e) {
        generateString();
    });
    $('#lineas').on('change', function (e) {
        generateString();
    });
    $('#materiales').on('change', function (e) {
        generateString();
    });
    $('#categorias').on('change', function (e) {
        generateString();
    });
    $('#almacenes_select').on('change', function (e) {
        generateString();
    });


    function generateString() {

        var data = getSel($("#marcas")) + "," + getSel($("#colores")) + "," + getSel($("#tallas")) + "," + getSel($("#proveedores")) + "," + getSel($("#materiales")) + "," + getSel($("#lineas")) + "," + getSel($("#almacenes_select")) + "," + getSel($("#categorias"));
        getPivot(data);
        

    }


    function getPivot(str) {
        

        var path = $("#validate").attr('action');

        $.ajax({
            type: "POST",
            url: path,
            cache: false,
            data: {str: str},
            dataType: 'text',
            success: function (response) {
                updateReport(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }


    function updateReport(json) {
                       
        obj = jQuery.parseJSON(json);
               
        setDataTable(obj);

    }


    agregar_datos = function (obj) {


        // If there any "Atributo categoria" selected
        if (parseInt($('#categoria_atributos').val()) > 0) {

            var validado = validacion();

            if (!validado)
                return true;

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


            //Convert form to label
            convertFormToLabel(jqueryObj, indiceProductos);

        }


    }


    $(function () {


    
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

    });



    $(document).ready(function (e) {



        // Function to preview image after validation
        $(function () {
                       
            generateString();
            
        });

        function imageIsLoaded(e) {
            $("#file").css("color", "green");
            $('#previewImg').attr('src', e.target.result);
            $('#previewImg').attr('width', '250px');
            $('#previewImg').attr('height', '230px');
        }
        ;

        $('#masterFile').change(function () {
            var clone = $(this).clone();
            clone.attr('id', 'field2');
            $('#field2_area').html(clone);
        });

    });

   

    function setDataTable( dataSetObj ) { 
        


        var table = $('#testTable').DataTable({
            "sPaginationType": "full_numbers",
            data: dataSetObj,
            columns: [
                {data: "nombre_almacen"},
                {data: "unidades"},
                {data: "nombre_categoria"},
                {data: "nombre_producto"},
                {data: "codigo"},
                {data: "precio_compra"},
                {data: "precio_venta"},
                {data: "nombre_talla"},
                {data: "nombre_marca"},
                {data: "nombre_color"}
            ],
            columnDefs: [
                {"title": "Almacén", "targets": 0},
                {"title": "Unidades", "targets": 1},
                {"title": "Categoría", "targets": 2},
                {"title": "Producto", "targets": 3},               
                {"title": "C. Barra", "targets": 4},
                {"title": "P.Compra", "targets": 5},
                {"title": "P.Venta", "targets": 6},
                {"title": "Talla", "targets": 7},
                {"title": "Marca", "targets": 8},
                {"title": "Color", "targets": 9}
            ],
            "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],
            "pageLength": 10,
            "language": {
                "url": "<?php echo base_url('public/export'); ?>/Spanish.json"
            },
            "bDestroy": 'Blfrtip',
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        }
        );


    }

    

</script>


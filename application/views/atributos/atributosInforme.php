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
    .img-polaroid{
        height:30px;
        width:30px;
    }

</style>



<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes Productos", "Informes Productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>



<?php echo form_open_multipart("atributos/qPivote", array("id" => "validate")); ?>


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
        <div class="row-fluid"> 
            <div class="span2">

                <div class="row-fluid">
                    <div class="span12">
                        <label class="titlePanel">TIPOS</label>
                    </div>                                                         
                </div>

                <div class="row-fluid">

                    <div class="span12">
                        <select id="tipos" name="tipos" class="select select2" style="width: 100%;">
                            <option value="0">Todos los tipos...</option>
                            <optgroup label="-------">                                                                  
                                <?php foreach ($data['tipos'] as $value) { ?>
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

    <table id="testTable" class="table" width="100%"> </table>

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



//===========================================================================
// On CHANGE
//===========================================================================

    //------------------------------
    // SELECTS
    //------------------------------
    
    $('#colores').change(function (e) {
        generateString();
    });
    $('#tallas').change(function (e) {
        generateString();
    });
    $('#marcas').change(function (e) {
        generateString();
    });
    $('#proveedores').change(function (e) {
        generateString();
    });
    $('#lineas').change(function (e) {
        generateString();
    });
    $('#tipos').change(function (e) {
        generateString();
    });
    $('#materiales').change(function (e) {
        generateString();
    });
    $('#categorias').change(function (e) {
        generateString();
    });
    $('#almacenes_select').change(function (e) {
        generateString();
    });


    
//===========================================================================
// Generamos el string de atributos para consulta, compuesta por los ID de el valor del atributo
//  resutn = " marca, colores, tallas, provvedores, materiales, lineas, almacenes, categorias "
//===========================================================================

    function generateString() {
        var stringAtributos = $("#marcas").val() + "," + $("#colores").val() + "," + $("#tallas").val() + "," + $("#proveedores").val() + "," + $("#materiales").val() + "," + $("#lineas").val() + "," + $("#tipos").val() + "," + $("#almacenes_select").val() + "," + $("#categorias").val();               
        getPivot(stringAtributos);// AJAX
    }



//===========================================================================
//  DATATABLE
//  
//  Solo recibe OBJETOS llavascript dentro de un array =>   [ { id : "1" } , { id : "2" } ]
//===========================================================================
    var table;
    function setDataTable(obj) {
        
        var bold = function (data){
            return "<strong>"+data+"</strong>";
        }
        
        var imagen = function (data){
            return '<img class="img-polaroid" src="<?php echo base_url("/uploads"); ?>/'+data+'">';
        }
        

        table = $('#testTable').DataTable({
            
            data: obj,            
            columns: [
                {data: "imagen", orderable: false , render: imagen }, 
                {data: "referencia", "title": "Referencia"}, 
                {data: "nombre_producto", title : "Producto", render: bold },
                {data: "nombre_almacen", title : "Almacén" },
                {data: "unidades", title : "Unidaes" },
                {data: "nombre_categoria", title : "Categoría" },
                {data: "codigo", title : "C.Barras" },
                {data: "precio_compra", title : "P.Compra" },
                {data: "precio_venta", title : "P.Venta" },                
                {data: "vr_inventario", title : "Vr.Inventario" },                
                {data: "nombre_marca", title : "Marca" },
                {data: "nombre_color", title : "Color" },
                {data: "nombre_talla", title : "Talla" },
            ],            
            //order: [[ 5, "asc" ]], // Orden inicial [ indiceColumna, asc o desc ]
            pageLength: 10,
            sPaginationType: "full_numbers",
            aLengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],            
            bDestroy: 'Blfrtip',
            dom: 'Blfrtip',
            buttons: [
                { extend: 'copy', text: 'Portapapeles'},
                { extend: 'csv', text: 'CSV'},
                { extend: 'excel', text: 'Excel'},
                { extend: 'pdf', text: 'PDF'},                
                { extend: 'print', text: 'Imprimir'}
            ],
            language: {
                url: "<?php echo base_url('public/export'); ?>/Spanish.json"
            }
        }
        );

    }


//===========================================================================
//===========================================================================
//
//      AJAX
//
//===========================================================================
//===========================================================================


    //===========================================================================
    // Enviamos un string con los atributos
    // Nos retornará un String JSON
    // Callback -> actualizar datos DataTable
    //===========================================================================
    function getPivot(str) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('atributos/qPivote'); ?>",
            cache: false,
            data: {str: str},
            dataType: 'text',
            success: function (response) {
                  
                var obj = $.parseJSON(response);//Convertimos stringJson a un objeto javascript
                setDataTable(obj);
                
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
    
        generateString();
        
    });

</script>


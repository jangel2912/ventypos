<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/dataTables.bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/buttons.dataTables.min.css'); ?>">


<style>

    #select{
        height: 1px;
        margin: 0px;
        padding: 0px;
    }
    .page-header{    
        margin-bottom: 0px;               
    }
    #drop{
        margin-bottom: 20px;               
    }
    
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

    #drop{
	border:2px dashed #bbb;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	padding:10px;
	text-align:center;
	font:20pt bold,"Vollkorn";color:#bbb
}
#b64data{
	width:100%;
}
    
</style>


<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Atributos Excel", "Atributos Excel"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>

<select id="select" name="format" style="visibility:hidden">
	<option value="json" selected> JSON</option>
</select>


<div class="data-fluid">
<div id="drop">Arrastre un archivo de Excel</div>
<input type="file" name="xlfile" id="xlf" />
<input type="checkbox" name="useworker" style="display:none">
<input type="checkbox" name="xferable" style="display:none">
<input type="checkbox" name="userabs" style="display:none">
</div>
<br>


<?php echo form_open_multipart("atributos/setGuardarExcel", array("id" => "validate")); ?>

    <input id="dataJson" type="hidden" name="dataJson" value=''>



    <div id="contentDataList" class="data-fluid contenedorTabla">

        <table id="testTable" class="table" width="100%"/> 

    </div>

</form>




<script src="<?php echo base_url('public/export/js/form2js.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/previewImg.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('public/export/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/jszip.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/pdfmake.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.html5.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.print.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/dataTables.buttons.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('public/export/js/jszip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/jszip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/xlsx.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/dist/ods.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/importExcelJS.js'); ?>" type="text/javascript"></script>


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
        var stringAtributos = $("#marcas").val() + "," + $("#colores").val() + "," + $("#tallas").val() + "," + $("#proveedores").val() + "," + $("#materiales").val() + "," + $("#lineas").val() + "," + $("#almacenes_select").val() + "," + $("#categorias").val();               
        getPivot(stringAtributos);// AJAX
    }

//===========================================================================
//      EXCEL TO OBJECT !!!
//===========================================================================

    //var excelObj;
    function excelObj(obj){
       var excelObj = jQuery.parseJSON( obj );
       excelObj = excelObj["Sheet1"]
       
       $( excelObj ).each(function(){
           var idAlmacen = this["Id Almacen"];
           var idProducto = this["Id"];
           var cantidad = this["Unidades"];
           
           if( cantidad != ""){
               //console.log(idAlmacen+" "+idProducto+" "+cantidad);
               var selec = ".unidades[idproducto="+idProducto+"][idalmacen="+idAlmacen+"]";
               $(selec).val(cantidad);
           }
           
       })
       
       //$.each(obj, function(key, val) { 
       //}); 
        
    }



//===========================================================================
//      GUARDAR EXCEL
//===========================================================================

    function guardarExcel(){
        
        var arr = [];
        
        $(".unidades").each(function(){
            
            var idPr = $(this).attr("idproducto");
            var idAlmacen = $(this).attr("idalmacen");
            var stockA = $(this).attr("stock");
            var cantidad = $(this).val();
            
            if(cantidad != "0" ){                
                arr.push( { idProducto : idPr, idAlmacen : idAlmacen, stock:stockA, unidades : cantidad } );
            }
            
        });
        
        var lista = {listaUnidades:arr};
        
        setAjaxStock( lista );
        
    }


//===========================================================================
//  DATATABLE
//  
//  Solo recibe OBJETOS llavascript dentro de un array =>   [ { id : "1" } , { id : "2" } ]
//===========================================================================
    var table;
    function setDataTable(obj){
        
        
        var bold = function (data){
            return "<strong>"+data+"</strong>";
        }
        
        var inputText = function (data,type,row){
            return '<input idproducto="'+row["id"]+'" stock="'+row["unidades"]+'" idalmacen="'+row["almacen_id"]+'" class="unidades" type="text" name="unidades" value="0" size="3">';
        }                
        
        var guardarExcelBtn = function (){
            guardarExcel();
        }        
        

        table = $('#testTable').DataTable({
            
            data: obj,            
            columns: [
                {data: "id", render:function(){return ""}, visible: false},
                {data: "id", title : "Id", visible: false },
                {data: "nombre_producto", title : "Producto", render: bold  },
                {data: "almacen_id", title : "Id Almacen", visible: false },
                {data: "nombre_almacen", title : "Almacén", render: bold  },
                {data: "codigo", title : "C.Barras" },
                {data: "nombre_marca", title : "Marca" },
                {data: "nombre_color", title : "Color" },
                {data: "nombre_talla", title : "Talla" },
                {data: "nombre_materiales", title : "Material" },
                {data: "nombre_proveedor", title : "Proveedor" },
                {data: "nombre_lineas", title : "Línea" },                
                {data: "unidades", title : "S.Actual", render: bold  },
                {data: "unidades", title : "Unidades", render: inputText  },
            ],            
            //order: [[ 5, "asc" ]], // Orden inicial [ indiceColumna, asc o desc ]
            pageLength: -1,
            sPaginationType: "full_numbers",
            aLengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],            
            bDestroy: 'Bfrtip',
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel', text: 'Exportar Excel'},
                { text: ' GUARDAR ', action: guardarExcel},
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
            url: "<?php echo site_url('atributos/getAjaxAtributosExcel'); ?>",
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



    function setAjaxStock(data) {
        
        var jsonString = JSON.stringify(data)
        
        $.ajax({
            
            type: "POST",
            url: "<?php echo site_url('atributos/setAjaxAtributosExcel'); ?>",
            cache: false,
            data: { data : jsonString },
            dataType: 'text',
            success: function (response) {                  
                  console.log(response);
                  generateString();
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


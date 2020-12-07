
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
        padding: 6px;
        text-align: center;
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

    .btnEnviar{
        margin: 20px 0px 10px 20px;        
    }
    .blue{
        color:#eee;
    }

</style>


<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Importar Facturas", "Importar Facturas"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>





<div class="block title">
    <div class="head">
        <h4>Bienvenido sigua los siguiente pasos para cargar los productos desde Excel </h4> 
        <br>
        <h5>1. De click en la siguiente enlace para descargar la plantilla de excel &nbsp;&nbsp;<a href="<?php echo base_url('uploads1/Plantilla Facturas.xlsx?random=')."".date('YmdHis'); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Facturas.</h5>
        <h5> Complete la plantilla teniendo en cuenta la información brindada al final de esta página: </h5>

    </div>
</div>



<div class="block">   

    <h5>2. A continuación selecione la plantilla de excel ya diligenciada.</h5> 	
    <h5>3. Por último presione el botón enviar.</h5> 		   	

</div>


<!--  IMPORT EXCEL  -->

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

</form>


<button class="btn btnEnviar" type="button"> Enviar </button>



<!--  IMPORT EXCEL  -->


<div class="row-fluid" style="margin-bottom:50px;">  
    <div class="span3"></div>
    <div class="span6">
        <div id="contentDataList" class="data-fluid contenedorTabla">
            <table id="testTable" class="table" width="100%"></table> 
        </div>        
    </div>
    <div class="span3"></div>
</div>





<div class="row-fluid" style="margin-bottom:50px;">  



    <div class="span3">

        <div class="row-fluid">

            <div class="span12">

                <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td class="head blue"><h5>&nbsp;&nbsp;Almacenes</h5></td>
                        </tr>                                
                        <?php foreach ($data['almacenes'] as $value) { ?>
                            <tr>
                                <td class="odd">&nbsp;&nbsp; <?php echo $value->nombre ?> &nbsp;&nbsp;</td>
                            </tr>
                        <?php } ?>                                        
                    </tbody>
                </table>

            </div>                                                         
        </div>

    </div>    

    <div class="span3">

        <div class="row-fluid">

            <div class="span12">

                <table border="1" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td class="head blue"><h5>&nbsp;&nbsp;Clientes</h5></td>
                        </tr>                                
                        <?php foreach ($data['clientes'] as $value) { ?>
                            <tr>
                                <td class="odd">&nbsp;&nbsp; <?php echo $value->nombre_comercial ?> &nbsp;&nbsp;</td>
                            </tr>
                        <?php } ?>                                        
                    </tbody>
                </table>

            </div>                                                         
        </div>

    </div>    

    <div class="span3">

        <div class="row-fluid">

            <div class="span12">

                <table border="1" cellpadding="0" cellspacing="0" width="100%">

                    <tbody>
                        <tr>
                            <td class="head blue"><h5>&nbsp;&nbsp;Vendedores</h5></td>
                        </tr>
                        <?php foreach ($data['vendedores'] as $value) { ?>
                            <tr>
                                <td class="odd">&nbsp;&nbsp; <?php echo $value->nombre ?> &nbsp;&nbsp;</td>
                            </tr>
                        <?php } ?>                                        
                    </tbody>

                </table>

            </div>                                                         
        </div>


    </div>

    <div class="span3">

        <div class="row-fluid">

            <div class="span12">

                <table border="1" cellpadding="0" cellspacing="0" width="100%">

                    <tbody>
                        <tr>
                            <td class="head blue"><h5>&nbsp;&nbsp;Formas Pago</h5></td>
                        </tr>
                        <?php foreach ($data['formasPago'] as $value) { ?>
                            <tr>
                                <td class="odd">&nbsp;&nbsp; <?php echo $value->mostrar_opcion ?> &nbsp;&nbsp;</td>
                            </tr>
                        <?php } ?>                                        
                    </tbody>

                </table>

            </div>                                                         
        </div>

    </div>
    
    <!--
    <div class="span2">

        <div class="row-fluid">

            <div class="span12">

                <table border="1" cellpadding="0" cellspacing="0" width="100%">

                    <tbody>
                        <tr>
                            <td class="head blue"><h5>&nbsp;&nbsp;Ciudades</h5></td>
                        </tr>
                        <?php foreach ($data['ciudades'] as $value) { ?>
                            <tr>
                                <td class="odd">&nbsp;&nbsp; <?php echo $value[0]; ?> &nbsp;&nbsp;</td>
                            </tr>
                        <?php } ?>                                        
                    </tbody>

                </table>

            </div>                                                         
        </div>

    </div>    
    -->


</div> 



<script src="<?php echo base_url('public/export/js/jszip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/jszip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/xlsx.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/dist/ods.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/importExcelJS.js'); ?>" type="text/javascript"></script>


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
// On CLICK
//===========================================================================



    $('.btnEnviar').click(function (e) {

        setAjaxFactura(excelObj);

    });



//===========================================================================
//      EXCEL TO OBJECT !!!
//===========================================================================

    var excelObj;

    function excelObj(obj) {

        excelObj = jQuery.parseJSON(obj);
        excelObj = excelObj["Facturas"];
        //setAjaxFactura( excelObj ); 

    }



//===========================================================================
//  DATATABLE
//  
//  Solo recibe OBJETOS llavascript dentro de un array =>   [ { id : "1" } , { id : "2" } ]
//===========================================================================
    var table;
    function setDataTable(obj) {

        var bold = function (data) {
            return "<strong>" + data + "</strong>";
        }


        var estadoFac = function (data, type, row) {
            if (row["idFac"] != "0")
                return  '<span class="label label-info"> &nbsp;' + data + '&nbsp; </span>';
            else
                return  ' ';
        }

        var estadoPro = function (data, type, row) {
                
            if ( row["almEst"] != "0"){
                if ( row["proEsta"] != "0")
                    return  '<span class="label label-success">&nbsp; ' + data + ' &nbsp;</span>';
                else
                    return  '<span class="label label-important"> &nbsp; ' + data + ' no existe &nbsp;</span>';
            }else{
                if (row["proEsta"] != "0")
                    return  '<span class="label label-important"> &nbsp; ' + data + ' &nbsp;</span>';
                else
                    return  '<span class="label label-important"> &nbsp; ' + data + ' no existe &nbsp;</span>';
            }

            
            
        }

        var estadoAlm = function (data, type, row) {
            if (row["almEst"] != "0")
                return  '<span class="label label-success"> &nbsp;' + data + '&nbsp; </span>';
            else
                return  '<span class="label label-important">&nbsp; ' + data + ' no existe &nbsp;</span>';
        }

        table = $('#testTable').DataTable({
            data: obj,
            columns: [
                {data: "idFac", title: "Factura", render: estadoFac},
                {data: "alm", title: "Almacén", render: estadoAlm},
                {data: "pro", title: "Código Producto", render: estadoPro}
            ],
            //order: [[ 5, "asc" ]], // Orden inicial [ indiceColumna, asc o desc ]
            pageLength: 100,
            sPaginationType: "full_numbers",
            aLengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],
            bDestroy: 'lfrtip',
            dom: 'lfrtip',
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



    function setAjaxFactura(dataObjectExcel) {

        var jsonString = JSON.stringify(dataObjectExcel);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('facturas/setAjaxFacturaExcel'); ?>",
            cache: false,
            data: {data: jsonString},
            dataType: 'text',
            success: function (response) {

                console.log(response);
                alert("Factura almacenada");
                
                var obj = $.parseJSON(response);//Convertimos stringJson a un objeto javascript
                setDataTable(obj);

                if (response.indexOf("ok") >= 0) {
                    alert("Factura almacenada correctamente");
                    //window.location = "<?php echo site_url('ventas'); ?>";
                    //console.log(response);

                } else {
                    //console.log(response);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
                console.log(xhr);
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



    });

</script>


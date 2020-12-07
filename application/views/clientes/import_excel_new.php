<script>
   /* $(".navbar-brand-logo1").hide();
    $("body").attr("class","site-menubar-fold site-menubar-keep");    
    $(".navbar-brand-logo2").css("visibility","visible");*/
</script>
<style>
    .containre{
        margin-top: 50px;
    }
    .panel {
        padding: 0px;
    }
    .block{
        margin-bottom: 0px !important;
    }
    hr{
        margin: 0px;
        border-color: #dcdcdc;
    }
    .titulo{        
        padding: 12px;
        padding-left: 25px;
        padding-bottom: 6px;
    }
    .titulo h4{        
        color: #313131 !important;
    }
    .titulo2 h5{
        text-align: center;
        padding: 0px;
        margin: 0px;
        margin-bottom: 10px; 
        font-size: 16px;
        margin-bottom: 18px !important;
    }
    ol{
        font-weight: 500;
    }    
    ol li{
        margin-bottom: 15px !important;        
    }
    li span{
        font-weight: 100;
    }
    .fondo2{
        background-color: #fafafa;
    }
    code{
        margin: 0px 3px;
        white-space:nowrap;
        font-weight: 500;
    }

    .body .content #validate .btn {
        background: #eaeaea !important;
        border-color: transparent !important;
        padding: 3px 6px;
        margin-bottom: 4px;
        border-radius: 0px 4px 4px 0px !important;
        color: #656565;
        border: 1px solid #bbbbbb !important;
    }
    .input-append input{
        border-radius: 4px 0px 0px 4px !important;
        border-right: 1px solid #bbbbbb !important;
        background-color: #f2f6f9;
    }
    
    #btnEnviar{
        float: none;
        padding: 0px;
        text-align: center !important;
        margin: 0px;
    }
    
    #btnEnviar .btn{
        padding: 3px 25px;
        font-size: 14px;
        /* font-weight: 100; */
        margin-right: 10px !important;
        width: 100%;
        background-color: #5cb85c !important;
    }
    .tituloInstruccion{
        background-color: #f5f5f5;
        border-top: #fff solid 1px;
        padding: 8px;
        padding-left: 20px
    }
    .tituloInstruccion > strong{
        margin-right: 20px;
    }
        
    .tituloContenido, .contenidoInput{
        background-color: #fff;
    }
    .importante{
        text-transform: uppercase;
        color: #de5a5a;
        margin: 0px 5px;
    }
    
    .containre {
        margin-top: 10px;
    }

    .descargar{
        text-align: center;
        padding: 30px;
        font-size: 20px;
    }
    .descargar a{
        text-decoration: none !important;
        font-weight: 400;
     }
     
    .icon.wb-download{
        color: #5cb85c;
        margin-right: 10px;
    }
    
    .contenidoInput{
        padding: 20px 20px !important;
        padding-top: 25px !important;
        padding-bottom: 10px !important;
        text-align: center;
        font-size: 16px;
        font-weight: 400;
    }
    .contenidoInput div{
        padding: 0px !important;
    }
    
    .contListas{
        padding: 10px;
    }
    
    .well{
        padding: 0px !important;
        background-color: #f7f7f7 !important;
    }
    
    .well > div{
        display: block;
        width: 100%;
    }
    
    .fondo2 {
        /*background-color: #ffffff*/;
    }
    
    
    .tituloContenido .col-md-6,
    .tituloContenido .col-md-4{
        padding: 0px !important;
    }
    
    .col-md-3{
        padding: 0px !important;
    }
    
    .tituloContenido .col-md-4 .well{
        margin-bottom: 0px;
    }       
    
    .tituloContenido .row {
        margin: 0px !important;
        padding: 10px;
        padding-bottom: 0px !important;
    }
    
    .center{
        text-align: center;
        padding: 10px;
        font-weight: 400;
        text-transform: uppercase;
    }
    
    .izq{
        text-align: left;
        padding: 10px;
        font-weight: 400;
        text-transform: none;
    }
    
    .listasCont{
        padding: 15px;
        background-color: #fff;
    }
    
    .listasCont div{
        margin-bottom: 10px;
    }
    
    .listasCont div.checker span, .listasCont div.radio span {
        background-image: none !important;
    }
    .listasCont span.switchery, .listasCont .checker {
        width: 40px;
        margin-right: 10px;
    }
    
    .switchery > small {
        width: 18px;
        height: 18px;
    }
    
    code{
        background-color: rgba(232,241,248,.4);
        margin-right: 6px;
    }
    .info{
        line-height: 16px;
        font-size: 13px;
    }
    .separador{
        margin-bottom: 20px;
        margin-top: 10px;
        border-color: #ececec !important;
    }
    
    .t{
        display : table;
        border-collapse : collapse;
    }

    .c{
        display : table-cell;
        line-height: 16px;
        font-size: 13px;
    }
    .tablaL .c:first-child{
        width: 120px;
        padding-right: 10px;
    }
    
    .tablaR .c:first-child{
        width: 210px;
        padding-right: 10px;
    }
    
    .t .c:first-child{
        text-align: right;        
    }    
    
    .checker{
        float: left;
        text-align: left;
    }
    
    .tablaL code{
        width: 110px;
        height: 20px;
        display: inline-block;
        line-height: 14px;
        text-align: center;
    }
    .tablaR code{
        width: 140px;
        height: 20px;
        display: inline-block;
        line-height: 14px;
        text-align: center;
    }
    
    .grp2{
        padding-top: 0px !important;
    }
    
    .info{
        margin-bottom: 0px !important;
    }
    .intruct{
        height: 105px !important;
    }
    
    .descargarLink{
        font-size: 20px;
        text-align: center;
        padding-top: 28px;
        font-weight: 400;
    }
    .contInfoInst{
        text-align: center;
        padding-top: 18px;
    }
    
    .textValidando{
        margin-top: 100px;
        text-align: center;
        padding: 50px;
        font-size: 22px;
        font-weight: 400;
        color: #3a3a3a;
    }
    .errorH{
        padding: 10px;
        font-size: 18px;
        font-weight: 400;
        color: white;
        background-color: #f1c505;
    }
    .errorH i{
        margin-right: 15px;
        margin-left: 10px;
        font-size: 20px;        
    }
    
    .descError{
        padding: 10px;
        color: #222;
    }
    .rowError{
        padding: 10px 20px;
        border-top: #ffffff 1px solid;
        border-bottom: #dedede 1px solid;
        background-color: #f9f9f9;
        color: black;
        height: auto;
        line-height: 30px;
        overflow: hidden;
    }
    
    select.listas{
        width: 50%;
        float: right;
    }
    
    .bRed{
        background-color: #C70C0C !important;
    }
    
    .I50{
        width: 50%;
        float: left;
    }
    .D50{
        width: 50%;
        float: right;
    }
    
    .rowError code{
        background-color: rgba(183, 0, 0, .8);
        margin-right: 10px;
        color: white;
        border: none !important;
    }
    
    .contBotonesReport{
        text-align: right;
        padding: 10px 10px 5px 20px;
        color: #222;
    }
    .contBotonesReport h4{
        color: #111 !important;
    }
    
    .contBotonesReport a
    .contBotonesReport button{
        font-size: 14px;
        padding: 2px 20px;
    }
    #tableReport {
        border-collapse: collapse;
        font-size: 11px;
        color: #2f2f2f;
    }
    #tableReport thead{
        font-weight: 400;
        font-size: 12px;
    }
    #tableReport thead td{        
        background-color: #fdfdfd;
        border-bottom: #d8d8d8 1px solid;
        border-left: #e0e0e0 1px solid;
        text-align: center;
    }
    #tableReport tbody td{
    }
    #tableReport thead tr{
        height: 40px;
    }
    #tableReport tbody tr{
        background-color: #f4f7f9;
        border-bottom: #e2e2e2 1px solid;        
    }
    .noImp td{
        background-color: #efcbcb !important;
        border-bottom: #dc9c9c 1px solid !important;        
        border-top: #dc9c9c 1px solid !important;        
    }
    
    #tableReport code{
        display: inline-block;
        width: 100%;
        height: 20px;
        line-height: 13px;
        text-align: center;
        color: white;
        font-weight: 100;
        font-size: 11px;
        border: none;
    }
    
    #tableReport .imp code{
        background-color: #5cb85c;
    }
    
    #tableReport .noImp code{
        background-color: #d43030;
    }
    #tableReport td{
        padding: 2px;    
    }
    
    #tableReport td:first-child{
        padding: 0px 0px 0px 10px;    
    }
    #tableReport tbody td:first-child{
        padding: 0px 10px 0px 4px;    
    }
    
    #tableReport thead td:first-child{
        width: 50px;        
        border-left: none;
        padding-left: 0px;
    }
    
    .erCell{
        background-color: #c75050 !important;
        color: white !important;
        text-align: center !important;
    }
    
    .msj2{
        float: left;
        color: #212121;
        font-size: 14px;
        margin-left: 20px;
        line-height: 20px;
        padding-top: 8px;
        padding-left: 15px;
        height: 35px;
        border-left: #cecece 1px solid;
    }    
    
    #btnReporte button,
    #btnRevalidar button{
        background: #5cb85c !important;
    }
    
    .codeGreen{
        display: inline-block;
        height: 20px;
        width: 50px;
        line-height: 16px;
        background-color: #4db51c !important;
        border: none !important;
        text-align: center;
        color: #ffffff;  
    }
    
    #importarBox li{
        border-bottom: #efefef 1px solid;
    }
    
    .changeColor{
        border-radius: 3px 3px 0px 0px;
    }
    
    #sino{
        margin: 20px;
        background-color: #f1f6fb;
    }
    #sino thead{
        background-color: #e9f0f7;    
    }    
    
    #sino th{
        font-weight: 500;
        padding: 5px 20px;
        border-bottom: #d2d2d2 1px solid;
        color: #4a4a4a;
    }
    
    #sino td{
        padding: 0;
        text-align: center;
        border-bottom: #eaeaea 1px solid;        
    }
    
    #sino .cellErrRed{
        background-color: #dc5c5c;
        color: white;
        border: #eaeaea 1px solid;
    }
    
    .strR{
        font-weight: 500;
        color: #d64a23;
        font-size: 17px;        
    }
    
    .sinoId{
        font-weight: 500;
        color: #4a4a4a;
    }
    .newItemBtn{
        float: right;
        margin-right: 20px;   
        height: 30px;
        padding-top: 3px;
        cursor: pointer; 
    }
    
    .newItemBtn i{
        color: #5cb85c;
        font-size: 22px;
    }
    .modal-content{
        border-radius: 5px !important;
    }
    .modal-header{
        margin: 0px;
        padding: 10px 0px;
        border-radius: 5px 5px 0px 0px !important;
        background-color: rgba(0,0,0,0.015);
        border-bottom: rgba(0,0,0,0.1) solid 1px;

    }

    .modal-body{
        padding-top: 10px;
        padding-bottom: 1px;
    }

    .modal-footer{
        padding: 0px;
        margin: 0px;
        background-color: #eee;
        margin-top: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
        border-top: rgba(0,0,0,0.1) solid 1px;
        border-radius: 0px 0px 5px 5px !important;                

    }
    .black{
        color: #000;
    }
    .wrapper{
        margin-left: 5% !important;
    }
</style>


<div class="page-header">    
    <div class="icon">
        <img alt="Clientes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_cliente']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Clientes", "Clientes");?></h1>
</div>

<div id="importarBox" class="row containre" style="">
    <div class="col-md-12">
        <div class="panel newPanel">
            <div class="titulo fondo2">
               <h3>Importar Clientes</h3>             
            </div>
            
            <div class="block" ><hr></div>
            <div class="block" style=" padding: 0px;">
                <div class="intrucciones">
                    <div class="tituloInstruccion">                        
                        <span> Lea las intrucciones para evitar errores de importación.</span>
                    </div><hr>
                    <div class="tituloContenido">
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="contListas">
                                    <div class="well">
                                        <div>
                                            <div class="izq">1. Información Obligatoria.</div>
                                            <hr>
                                            <div class="listasCont intruct">
                                                <div class="contInfoInst" > Lea detalladamente la información de cada campo de excel <strong><a href="javascript:scrollTo('#camposObligatorios',45);">aquí</a></strong>.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="contListas">
                                    <div class="well">
                                        <div>
                                            <div class="izq">2. Descargar Excel</div>
                                            <hr>
                                            <div class="listasCont intruct">
                                                <div class="info" >
                                                    <div class="descargarLink">
                                                        <i class="icon wb-download" aria-hidden="true"></i><a id="" href="<?php echo base_url("/uploads/Plantilla_Clientes.xls") ?>">Descargar Excel</a>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="contListas">
                                    <div class="well">
                                        <div>
                                            <div class="izq">3. Importar Excel</div>
                                            <hr>
                                            <div class="listasCont intruct">
                                                <div class="info" >
                                                    <?php echo form_open_multipart("clientes/import_excel_new", array("id" => "validate")); ?>
                                                    <div class="input-append file">
                                                            <input type="file" name="archivo" placeholder="Archivo" style=" display:none; "/>
                                                            <input type="text" placeholder="Archivo"/>
                                                            <input type="hidden" name="errorFix" id="errorFix"/>
                                                            <button class="btn btn-success" type="button">Buscar</button>
                                                    </div> 
                                                    </form>
                                                    <?php echo $data['data']['upload_error']; ?>
                                                    <div id="btnEnviar" class="toolbar bottom tar">
                                                        <button class="btn btn-success"  onclick="javascript:enviarFormulario();"  type="button"> Validar Importación </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="camposObligatorios" class="col-md-6">  
                                <div class="contListas grp2">
                                    <div class="well">
                                        <div>
                                            <div class="center changeColor">Campos Obligatorios</div>
                                            <hr>
                                            <div class="listasCont tablaL">
                                                <div class="info" >Campos incluidos por defecto en el Excel, regístrelos según las indicaciones correspondientes para evitar errores de importación</div>
                                                <hr class="separador">
                                                <div class="t" >
                                                    <div class="c"><code># Identificación </code></div>
                                                    <div class="c">Número de identificación o Nit del cliente,<br>no debe repetirse en el excel ni debe estar registrado.</div>
                                                </div>
                                                <div class="t" >
                                                    <div class="c"><code>Nombre Comercial</code></div>
                                                    <div class="c">Campo requerido, no debe repetirse en el excel ni debe estar registrado.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="camposGrupos" class="col-md-6">                            
                                <div class="contListas grp2 contListasSwitch">
                                    <div class="well">
                                        <div>
                                            <div class="center changeColor">Grupos</div>
                                            <hr>
                                            <div class="listasCont tablaR">
                                                <div class="info" >Si quiere agregar un cliente a un grupo recuerde que los siguientes son los que estan registrados.</div>
                                                <hr class="separador">
                                                <ul style="list-style-type: none; margin: 0px">
                                                    <?php 
                                                        foreach( $data["data"]["grupos"] as $row){ 
                                                            echo "<li>".$row->nombre."</li>";
                                                        } 
                                                    ?>                                                
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                            </div>  
                        </div>
                        
                    </div>
                                     
                    
                </div>

                            
            </div> 
            
                                
            <?php $message = $this->session->flashdata('message');
            if (!empty($message)){ ?>
                <div class="alert alert-error"><?php echo $message; ?></div>
            <?php } ?>                                
                                
            <div class="block" ><hr></div>
            
            <div class="block fondo2">
                
    
                
            </div>
            
        </div>
        
    </div>
</div>

<div id="validandoBox" class="row" style=" display: none; ">
    
    <div class="col-md-12">
        
        <div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="panel newPanel textValidando">
                        <img src="<?php echo base_url(); ?>/public/img/loaders/2d_4.gif">
                        <span id="mensajeImportacion" style="margin-left: 10px;">Validando Importación...</span>
                    </div>
                </div>
                <div class="col-md-3"></div>            
            </div>
            
        </div>
        
    </div>
    
</div>

<div id="okBox" class="row" style=" display: none; ">
    
    <div class="col-md-12">
        
        <div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div id="ok" class="panel newPanel textValidando" style="color:#ffffff; background-color: #5cb85c; cursor: pointer;">
                        <i class="icon wb-check-circle" aria-hidden="true" style=" margin-right: 15px;    font-size: 40px; margin-bottom: 20px;"></i>
                        <br>
                        <span id="mensajeImportacion" style="margin-left: 10px; text-align: center">¡ Clientes Importados Correctamente !</span>
                    </div>
                </div>
                <div class="col-md-2"></div>            
            </div>
            
        </div>
        
    </div>
    
</div>

<div id="resultadosBox" class="row" style="display:none">
    <div class="col-md-12">
        
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 contenedorResultados">
                    
            </div>
            <div class="col-md-2"></div>            
        </div>   

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="panel newPanel ">
                    <div id="btnRecargar" class="toolbar bottom tar" style="float:right; display: none;">                            
                        <button class="btn" style="font-size: 16px; padding: 8px 20px;" type="button" onclick="window.location.reload()">Volver a cargar archivo</button>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>            
        </div>
        
        
    </div>
</div>

<script>
    
    var downloadExcel = '<?php echo site_url("/productos/generarPlantillaNew/")."?dim=" ?>';
    
    var arrayOpc = [];
    var opcObj = {};
    
    var resultErrores = [];
    
    var actualDomElementCrear = null;
    
    //USAGE: $("#form").serializefiles();
    (function($) {
    $.fn.serializefiles = function() {
        var obj = $(this);
        /* ADD FILE TO PARAM AJAX */
        var formData = new FormData();
        $.each($(obj).find("input[type='file']"), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });
        var params = $(obj).serializeArray();
        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });
        return formData;
    };
    })(jQuery);



    function nuevoDialog ( tipo, nombre, domEle ){
        
        actualDomElementCrear = domEle;
        
        $("#textoInfoImpuesto").hide();
        $("#nuevoImpuestoPorcentaje").hide();               
    
        if( tipo == "grupo"){                        
            $(".modal-title").html("Crear Grupo");
            $(".modal-body #mensaje").html("¿Desea crear el grupo <strong class='black'>"+ nombre +"</strong>?");
        }
        /*if( tipo == "categoria"){                        
            $(".modal-title").html("Crear Categoría");
            $(".modal-body #mensaje").html("¿Desea crear la categoría <strong class='black'>"+ nombre +"</strong>?");
        }
        if( tipo == "impuesto"){                        
            $(".modal-title").html("Crear Impuesto");
            $(".modal-body #mensaje").html("¿Desea crear el impuesto <strong class='black'>"+ nombre +"</strong>?");
            $("#textoInfoImpuesto").show();
            $("#nuevoImpuestoPorcentaje").show();
            $("#nuevoImpuestoPorcentaje").val("");            
        }
        if( tipo == "unidad"){                        
            $(".modal-title").html("Crear Unidad");
            $(".modal-body #mensaje").html("¿Desea crear la unidad <strong class='black'>"+ nombre +"</strong>?");
        }
        if( tipo == "proveedor"){                        
            $(".modal-title").html("Crear Proveedor");
            $(".modal-body #mensaje").html("¿Desea crear el proveedor <strong class='black'>"+ nombre +"</strong>?");
        }*/
        
        $("#nuevoValor").val( nombre );      
        $("#nuevoTipo").val( tipo );
        
        $("#modalCont").modal("show");

    }

    function nuevo(){
                
        var datos ={
            "tipo": $("#nuevoTipo").val(),
            "nombre": $("#nuevoValor").val(),
        };
        
        jQuery.ajax({
            url: '<?php echo site_url(); ?>/clientes/creacionRapidaNewImportar/',
            dataType: 'json',
            data: datos,
            type: 'POST',
            success: function(response){
                
                if( response.result == true ){
                    $("#btnError").hide();
                    $("#btnReporte").hide();
                    $("#btnRevalidar").show();    

                    var filaCreada = $(actualDomElementCrear).parent()[0];
                    $(filaCreada).fadeOut();                    
                }else{
                    alert("Ha ocurrido un error en la creación");
                }    
                
            }
        });  
        
        
        $("#modalCont").modal("hide");
        
    }

    function scrollTo(target, offset){
        $('html, body').animate({
            scrollTop: $(target).offset().top-offset
        }, 800,function(){
            //cambiamos color encabezado
            $(target).find(".changeColor").animate({
                'color': "#ffffff",
                'background-color':"#5ca745"
             }, 300,function(){
                // devolvemos el color original
                $(target).find(".changeColor").animate({
                    'color': "#76838f",
                    'background-color':"#f7f7f7"
                 }, 2000); 
             });
        });
    }
    

    function enviarFormulario(){
        
        if( $("input[name=archivo]")[0].files.length != 0 ){
        
            $("#importarBox").hide();
            $("#resultadosBox").hide();
            
            $("#validandoBox").fadeIn();


            // Enviar el excel por Submit()
            //$("#validate").submit(); return false;
            $("#btnEnviar .btn").attr("onclick","javascript:void(0)");

            //Enviar el excel via ajax
            console.log("bn");
            jQuery.ajax({
                url: '<?php echo site_url(); ?>/clientes/importar_excel_nuevo/',
                data: $("#validate").serializefiles(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                dataType: "json",
                success: function(data){
                    console.log(data);
                    //setTimeout( function(){
                        if(data.resp == 1)
                        {
                            alert("La importación se realizo correctamente, se han ingresado "+data.cuantos+" clientes nuevos");
                            location.href ="<?php echo site_url('clientes');?>";
                        }else
                        {
                            alert("Se han generado errores en la importacion, corrija el excel y vuelva a intertarlo");
                            mostrarErrores((data.errores));
                        }
                    //},1500);                
                }
            });
        }
        else{
            alert("Seleccione un archivo");
        }
    }
    
    function mostrarErrores(data)
    {
        //var arr = (data).toArray();
        $("#validandoBox").hide(); 
        $("#resultadosBox").fadeIn(); 
        $(".contenedorResultados").html("");
        var html = "";
        $.each(data.camposFaltantes,function(i,e){
            console.log(i,e);
            html += '<div class="panel">\n\
                        <div class="row">\n\
                            <div class="col-md-2"></div>\n\
                            <div class="col-md-8">'+e+'</div>\n\
                        </div>\n\
                    </div>';
        });
        $.each(data.errores,function(i,e){
            console.log(i,e);
            html += '<div class="panel">\n\
                        <div class="row">\n\
                            <div class="col-md-2"></div>\n\
                            <div class="col-md-8">'+e+'</div>\n\
                        </div>\n\
                    </div>';
        });
        $("#btnRecargar").show();
        $(".contenedorResultados").append(html);
        
    }    
    
    function mostrarResultadosValidacion(data){
                               
        resultErrores = data.errores;
        
        $("#validandoBox").hide(); 
        $("#resultadosBox").fadeIn(); 
        
        $(".contenedorResultados").html("");
        
        // Si no hay errores entonces pasamos a generar el reporte
        if( data.errores.length == 0 ) {
            importarProductos("reporte"); console.log("bn1");
            return 0;
        }
        
        var errores = []; 
        
        var n = data.errores.length;
        for (var i = 0; i < n; i++) {
            if( data.errores[ i ] == "faltantes" && data.objErrores[ data.errores[ i ] ].length > 1 ){
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error Campos Faltantes</div>";
                panelError += "<div class='descError'> ¡Los siguientes campos no pueden estar vacios! Por favor, <strong>corrija el archivo Excel.</strong></div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 1; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<span class='newItemBtn' onclick=\"javascript:location.reload()\"><b>Volver a cargar archivo</b></span>";
                    panelError += "</div>";
                }
                panelError += "</div>";
            }else if(data.errores[ i ] == "faltantes" && data.objErrores[ data.errores[ i ] ].length <= 1)
            {
                errores++;
            }
            
            if( data.errores[ i ] == "nombre" && data.objErrores[ data.errores[ i ] ].length > 1 ){
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error de Nombre Comercial</div>";
                panelError += "<div class='descError'> ¡Los siguientes Nombres Comerciales ya han exiten en el sistema! Por favor los clientes deben tener nombres comerciales distintos, <strong>corrija el archivo Excel.</strong></div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 1; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<span class='newItemBtn' onclick=\"javascript:location.reload()\"><b>Volver a cargar archivo</b></span>";
                    panelError += "</div>";
                }
                panelError += "</div>";
            }else if(data.errores[ i ] == "nombre" && data.objErrores[ data.errores[ i ] ].length <= 1)
            {
                errores++;
            }
            
            if( data.errores[ i ] == "nombreRep"  && data.objErrores[ data.errores[ i ] ].length > 1){
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error de Nombre Comercial</div>";
                panelError += "<div class='descError'> ¡Los siguientes Nombres Comerciales estan repetidos en el Excel! Por favor los clientes deben tener nombres comerciales distintos, <strong>corrija el archivo Excel.</strong></div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 1; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<span class='newItemBtn' onclick=\"javascript:location.reload()\"><b>Volver a cargar archivo</b></span>";
                    panelError += "</div>";
                }
                panelError += "</div>";
            }else if(data.errores[ i ] == "nombreRep" && data.objErrores[ data.errores[ i ] ].length <= 1)
            {
                errores++;
            }
            
            
            if( data.errores[ i ] == "identificacion"  && data.objErrores[ data.errores[ i ] ].length > 1){
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error de identificacion</div>";
                panelError += "<div class='descError'> ¡Los siguientes numeros de identificacion ya existen en el sistema! Por favor los clientes deben tener numeros de identificacion distintos, <strong>corrija el archivo Excel.</strong></div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 1; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<span class='newItemBtn' onclick=\"javascript:location.reload()\"><b>Volver a cargar archivo</b></span>";
                    panelError += "</div>";
                }
                
                panelError += "</div>";
            }else if(data.errores[ i ] == "identificacion" && data.objErrores[ data.errores[ i ] ].length <= 1)
            {
                errores++;
            }
            
            if( data.errores[ i ] == "identificacionRep"  && data.objErrores[ data.errores[ i ] ].length > 1){
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error de Identificacion</div>";
                panelError += "<div class='descError'> ¡Los siguientes numeros de identificacion estan repetidos en el excel! Por favor los clientes deben tener numeros de identificacion distintos, <strong>corrija el archivo Excel.</strong></div><hr>";
            
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 1; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<span class='newItemBtn' onclick=\"javascript:location.reload()\"><b>Volver a cargar archivo</b></span>";
                    panelError += "</div>";
                }
                panelError += "</div>";
            }else if(data.errores[ i ] == "identificacionRep" && data.objErrores[ data.errores[ i ] ].length <= 1)
            {
                errores++;
            }
            
            if( data.errores[ i ] == "grupo"){
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH' ><i class='icon wb-warning' aria-hidden='true'></i>Error de Grupo</div>";
                panelError += "<div class='descError'> ¡Los siguientes grupos no existen! Por favor relacionelos clientes a un grupo correcto o <strong>creelas previamente en el sistema</strong></div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 0; j < nErr; j++) {                    
                    // Nombres de los grupos que no existen
                    var element = objErr[j];                                      
                    panelError += "<div class='rowError'>";
                    panelError +=     "<span>"+ element +"</span>";                    
                    panelError +=     "<select class='gruposListas listas'><option value='' disabled selected>Seleccionar Grupo .... </option>";
                    
                    var realData = data.realData[data.errores[i]];
                    
                    for (var k = 0; k < realData.length; k++) {;
                        panelError += "<option value='"+ element +"'>"+realData[k].v+"</option>";
                    }
                    
                    panelError +=     "</select>";                 
                    panelError +=     "<span class='newItemBtn' onclick=\"nuevoDialog('grupo','"+ element +"',this)\"><i class='icon wb-plus-circle' aria-hidden='true'></i></span>";
                    panelError += "</div>";
                }
            
                panelError += "</div>";

            }
            
            
            
            if( data.errores[ i ] == "codigosExcel"){
            
                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH bRed'><i class='icon wb-warning' aria-hidden='true'></i>¡ Fatal Error ! Códigos duplicados en Excel </div>";
                panelError += "<div class='descError'> ¡Los siguientes códigos están duplicados en la lista de excel ! Por favor resuelva el conflicto y vuelva a subir el archivo  de Excel</div><hr>";
                
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 0; j < nErr; j++) {                    
                    // Codigos y nombres de los productos repetidos en el excel
                    var element = objErr[j];
                    if(element.c == "-" ){
                        panelError += "<div class='rowError' style='height:6px;background-color: white;'></div>";
                    }else{
                        panelError += "<div class='rowError' > Fila: <code class='codeGreen'>"+element.i + "</code> Código: <code>"+element.c + "</code> <strong>" + element.ex + " </strong></div>";
                    }
                    
                }
            
                panelError += "</div>";

            }
            
            if( data.errores[ i ] == "codigosDB"){

                $("#btnError").show();
                $("#btnReporte").hide();
                
                var panelError = "";
                panelError += "<div class='panel newPanel'>";
                panelError += "<div class='errorH bRed'><i class='icon wb-warning' aria-hidden='true'></i>¡ Fatal Error ! Códigos ya existen en el sistema</div>";
                panelError += "<div class='descError'> ¡Los siguientes códigos ya existen en el sistema! Por favor resuelva el conflicto y vuelva a subir el archivo de Excel</div><hr>";
                panelError += "<div class='descError' style='text-align: center;height: 40px;font-weight: 400;'> <div class='I50'>EXCEL</div> <div class='D50'>SISTEMA</div> </div><hr>";
                    
                objErr = data.objErrores[ data.errores[ i ] ];
                var nErr = objErr.length;
                for (var j = 0; j < nErr; j++) {                    
                    // Codigos y nombres de los productos repetidos en la DB
                    var element = objErr[j];
                    panelError += "<div class='rowError'>";
                    panelError += "<div class='I50'> Fila: <code class='codeGreen'>"+element.i + "</code> Código: <code>"+element.c + "</code> <strong>" + element.ex + "</strong></div>";
                    panelError += "<div class='D50'> Código: <code>"+element.c + "</code><strong>" + element.db + "</strong></div>";                    
                    panelError += "</div>";
                    
                } 
            
                panelError += "</div>";

            }
            if(errores ==  data.errores.length)
            {
                importarProductos("reporte"); console.log("bn2");
                return 0;
            }
            $(".contenedorResultados").append( panelError );

        }                    
        
        
    }
    
    
    function getOpcSession(){
        if ( localStorage.getItem("opciones") != null ){
            
            var objOpc;
            
            try {
                objOpc = JSON.parse( localStorage.opciones );
                objOpc.forEach(function(val){ 
                    if( val.val ) $( '#'+val.name ).trigger('click');                    
                });                
            } catch (e) {
                console.log("error conversion json opciones")
            }
            
   
        }
    }    
    
    function getOpc(){
        
        arrayOpc = [];
        
        var strOpciones = "";
        
        $(".contListasSwitch input").each( function(){
            
            var elementId = $(this).attr("id"); 
            var nombreCampo = $(this).attr("name"); 
            var val = $(this)[0].checked;
            
            arrayOpc.push( { "name" : elementId, "val" :  val } );    
            
            // generamos sl string de parametros GET
            if( $(this)[0].checked ){                
                strOpciones += nombreCampo+","
            }

        });
        
        $("#generarExcel").attr('href', downloadExcel+strOpciones );

        localStorage.setItem("opciones", JSON.stringify( arrayOpc ) );
        
    }

    
    function importarProductos( tipo){
        /*jQuery.ajax({
            url: "<?php echo site_url(); ?>/clientes/importar_excel_nuevo?validado=ok",
            data: $("#validate").serializefiles(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                setTimeout( function(){
                        $("#validandoBox").hide();
                        $("#reporteBox").hide();
                        $("#okBox").fadeIn();  
                        
                        setTimeout(function(){
                            //window.location.replace('<?php echo site_url(); ?>/clientes');    
                        },800);                    
                },1500);                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText+" "+thrownError);                
                console.log(xhr.responseText+" "+thrownError);
            }
        }); */ //---------------bn
        if( tipo == "guardar" )
            $("#reporteBox").hide();
        
        // VALIDACION SELECTS
        
        var masterResult = {};
        
        var unselectsGrupo = 0;
        
        // Si hay errores en categorias
        if( resultErrores.indexOf("grupo")!=-1){            
            
            var listTmp = [];
            $(".grupoListas").each(function(){            
                if( $(this).prop('selectedIndex')  == 0 ) unselectsGrupo = 1;                 
                else{
                    var tmp = {}
                    tmp.k = $(this).find('option:selected').val();
                    tmp.v = $(this).find('option:selected').text();
                    listTmp.push(tmp);
                }                
            });            
            masterResult.grupo = listTmp;
        }

        
        // Validamos si alguna de las listas no ha sido seleccionada
        if( unselectsGrupo == 1) {  alert("Relacione todas los grupos por favor"); return false; }
        
        
        $("#errorFix").val( JSON.stringify( masterResult ) );

        if( tipo == "guardar"){
            $("#mensajeImportacion").html("Importando Clientes...");    
        }
        if( tipo == "reporte"){
            $("#mensajeImportacion").html("Generando Informe...");
        }
        
        $("#resultadosBox").hide();
        $("#validandoBox").fadeIn();        
        
        
        
        var url;
        
        if( tipo == "guardar") url= '<?php echo site_url(); ?>/clientes/importar_excel_nuevo?validado=ok&accion=guardar';
        if( tipo == "reporte") url= '<?php echo site_url(); ?>/clientes/importar_excel_nuevo?validado=ok&accion=reporte';
        
        jQuery.ajax({
            url: url,
            data: $("#validate").serializefiles(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                setTimeout( function(){
                    
                    if( tipo == "reporte"){
                        $("#validandoBox").hide();                        
                        mostrarTablaReporte(data);
                    }
                    
                    if( tipo == "guardar"){                        
                        $("#validandoBox").hide();
                        $("#reporteBox").hide();
                        $("#okBox").fadeIn();  
                        
                        setTimeout(function(){
                            window.location.replace('<?php echo site_url(); ?>/clientes');    
                        },800)
                    }
                    
                },1500);                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText+" "+thrownError);                
                console.log(xhr.responseText+" "+thrownError);
            }
        }); 
    }
    
    function mostrarTablaReporte(data){
        
        var lista = data.data;   
        
        var validos = 0;
        var invalidos = 0;
        
        //---------------------------------------------
        // Añadimos encabezado
        //---------------------------------------------
        var html = "";
        html +="<tr>";
        for (var j = 1; j < lista[0].length; j++) {
            var campo = lista[0][j];
            html +="<td> "+campo+" </td>";
        }        
        html +="</tr>";
        $("#tableReport thead").html( html );



        //---------------------------------------------
        // Añadimos información
        //---------------------------------------------
        
        var html = "";
        
        for (var i = 1; i < lista.length; i++) {
            
            var estado = lista[i][0];
            
            if( estado == 1 ){
                html +="<tr class='imp'>";
                validos ++;
            }
            else if( estado == 0 ){
                html +="<tr class='noImp'>";
                invalidos ++;
            }
                        
            for (var j = 1; j < lista[i].length; j++) {
                
                var campo = lista[i][j];
                                
                if( j == 1 )
                    html +="<td><code> "+campo+" </code></td>";
                else{
                    if( estado == 0 && campo == "?"){
                        html +="<td class='erCell' style='background-color: #c75050 !important;'><strong>"+campo+"</strong></td>";
                    }else{
                        html +="<td> "+campo+" </td>";
                    }
                }
            }
            
            html +="</tr>";
            

        }
        
        $("#tableReport tbody").html( html );
        $("#totalValidos").html( validos );
        $("#totalInvalidos").html( invalidos );

        
        $("#reporteBox").fadeIn();
        
    }

    $(document).ready(function(){
         
         //$("#importarBox").hide();         
         //$("#reporteBox").show();
         
        getOpcSession();
        
        defaults = {
            color             : '#69d65a'
          , secondaryColor    : '#f4f8f9'
          , jackColor         : '#fff'
          , jackSecondaryColor: null
          , className         : 'switchery'
          , disabled          : false
          , disabledOpacity   : 0.5
          , speed             : '0.1s'
          , size              : 'default'
        }
        
        
        // Convertimos a Switchery
        $(".contListasSwitch input").each( function(){
            
            // Obtenemos el id de cada input
            var elementId = $(this).attr("id"); 
            
            // convertimos el check a switchery
            new Switchery( $('#'+elementId)[0], defaults );
            
            // almacenamos el elemento dom en el array opcObj
            opcObj[ elementId ] = $(this);
            
            
        });
        
        
        // Si los checkbox cambian
        $( ".js-switch" ).change(function() {
            getOpc();
        });
        
        // Cargamos los estados de las opciones guardadas por los usuarios en session        
        
        getOpc();
        
        
        //BOTONES 
        
        //Boton importar                      
        $("#btnReporte").click( function(){
            importarProductos("reporte");
        });
        
        //Boton ok final
        $("#ok").click( function(){
            window.location.replace("<?php echo site_url(); ?>/clientes/");
        });
        
        
        // Boton de revalidar cuando se crea un item nuevo
        $("#btnRevalidar").click( function(){
            $("#btnReporte").show();
            enviarFormulario();            
            $(this).hide();
        });
        



        //------------------
        //  Botnones Modal
        //-------------------
        
        $("#btnAceptarModal").click( function(){
            nuevo();
        });
        
        $("#btnCancelarModal").click( function(){
            $("#modalCont").modal("hide");
            
            $("#nuevoTipo").val("");
            $("#nuevoValor").val("");            
            $("#nuevoImpuestoPorcentaje").val("");
        });
        
        

    });  
    
</script>



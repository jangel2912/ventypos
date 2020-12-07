
<script>
    

    $(".navbar-brand-logo1").hide();
    $("body").attr("class","site-menubar-fold site-menubar-keep");    
    $(".navbar-brand-logo2").css("visibility","visible");

    

</script>

<style>
    .navbar-brand-logo{
        display:none !important;
    }    
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
        padding: 12px 0px;
        /*padding-left: 25px;*/
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
        background-color: #62cb31 !important;
    }
    .tituloInstruccion{
        background-color: #f5f9fb;
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
        color: #62cb31;
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
        background-color: #62cb31;
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
        background: #62cb31 !important;
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
        color: #62cb31;
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
    table{
        width: 100%;
    }
    
    td,th{
        border-bottom: #e8e8e8 1px solid;
        padding: 5px 0px;
        text-align: center !important;
    }
    thead tr{
        background-color: #ffffff;
        color: #4a4a4a;
        font-weight: 600;
        height: 50px;
    }
    
    .tituloInstruccion{
        padding: 10px 20px;
        font-size: 16px;
        color: #1f1f1f;
        background-color: #f7f7f7;
        cursor: pointer;
    }
    
    .lista2 .tituloInstruccion{
        cursor: default;
    }
    
    .insIzq .tituloInstruccion:hover {
        background-color: #f1f1f1;
    }
    .insIzq.validado .tituloInstruccion:hover {
        background-color: #f7f7f7;
    }
    
    .finalizadoText{
        float: right;
        display:none;
    }
    
    .insIzq.validado .finalizadoText{
        display:block;
    }
    
    .listaOpciones a{
        margin: 0px 20px;
        font-weight: 400;
        font-size: 16px;
    }
    
    .detalle{
        display: none;
    }
    
    #contBotones{
        display: none;
    }
    
    .validado{
        opacity:0.3;
    }
    
    .insIzq.nuevo{
        padding-left: 10px;
        background-color: #62cb31;
    }
    
    .insIzq.nuevo .tituloInstruccion{
        padding-left: 10px;
    }
    
@media all and (min-width: 992px) {
    .leftPanel {
        padding-right: 0px !important;
    }
}
</style>
    


<div id="importarBox" class="row containre" style="">
    
    
    
    <div class="col-md-5 leftPanel">
        
        <div class="panel newPanel">

            <div class="titulo fondo2" style=" background-color: #fff">
               <h4 style=" text-align: center;" > Listado Comanda </h4>             
            </div>
            <!--
            <div class="block" ><hr></div>
            <div class="block listaOpciones" style=" padding: 10px; "><a href="javascript:expandir();">Expandir</a><a href="javascript:contraer();">Contraer</a></div>
            -->
            <div class="block" ><hr></div>
            <div class="block" style=" padding: 0px;">
                
                <div id="listadoComandas" class="lista1"  >
                    
                    <div class="intrucciones">
                        <div class="tituloInstruccion" style="text-align: center; ">
                            <span> No hay comanda </span>
                        </div><hr>
                    </div>                                        
                    
                </div>
                
                <div id="lastBorder" style=" background-color: #ffffff; height: 10px;">
                    
                </div>
                
            </div> 

        </div>

    </div>
    
    <div class="col-md-7">
        
        <div class="panel newPanel">

            <div class="titulo fondo2" style=" background-color: #fff; text-align: center">
               <h4>Detalle</h4>             
            </div>
            <div class="block" ><hr></div>            
            <div class="block" style=" padding: 0px;">
                
                <div id="listadoComandas" class="lista2"  >
                    
                    <div class="intrucciones">
                        <div class="" style="text-align: center; background-color: #fdfdfd; font-size: 16px; padding: 40px;">
                            <span> No hay detalles </span>
                        </div><hr>
                    </div>                                        
                    
                </div>
                
                <div style=" background-color: #fff; height: 55px; text-align: right; padding: 10px 20px 0px 0px">
                    
                    <div id="contBotones" >
                        <a class="" style="background-color: #fff !important;color: #C70C0C ; margin-right: 15px; font-weight: 400;" href="javascript:cancelarSeleccion()">Cancelar</a>
                        <button class="btn" type="button" style=" background-color: #62cb31 !important; " onclick='validarSeleccion()'>  Confirmar </button>                
                    </div>
                    
                </div>         
                
            </div> 

        </div>

    </div>
    

</div>
    
    


</div>



<!-- Modal Creación nuevo ITEM-->

<div class="modal fade in" id="modalCont" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;">
    <div id="modal" class="modal-dialog modal-center">
        <div class="modal-content">
            <div class="modal-header">                            
                <h3 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.9) !important; font-size: 24px; padding: 5px;"></h3>
            </div>

            <div class="modal-body" style="color: #000;">                            
                
                <div id="mensaje"></div>
                
                <input id="nuevoValor" type="hidden" value="">
                <input id="nuevoTipo" type="hidden" value="">
                
                <div id="textoInfoImpuesto" style="margin: 20px 0px 10px 0px;">Digite el porcentaje del impuesto sin el signo <strong>"%"</strong>. Ej: 16</div>
                <input id="nuevoImpuestoPorcentaje" type="text" value="" placeholder="Impuesto Porcentaje" style="display:none">
                
            </div>

            <div class="modal-footer" style="">                                            
                <button id="btnAceptarModal" type="button" class="btn btn-primary" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px; background-color: #62cb31 !important;"> Aceptar </button>
                <button id="btnCancelarModal" type="button" class="btn btn-danger" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px; background-color: #C70C0C !important;"> Cancelar </button>
            </div>

        </div>
    </div>
</div> 




<script>
    



    //var downloadExcel = '<?php echo site_url("/productos/generarPlantillaNew/")."?dim=" ?>';
    
    var ultimaNotificacion = null;
    var objComandas = null;
    var comandaSeleccionada = -1; // se guarda el indice del array "objComandas"
    var comandaSeleccionadaId = -1; // se guarda el indice del array "objComandas"
    
    var id = '<?php echo $data["id"] ?>';

    function actualizarLista(lista){
        
        $("#lastBorder").css("background-color","#fff");
        
        var lista = objComandas;
        if(lista.length != 0){
            
            $("#listadoComandas.lista1").html("");
            
            // si dentro de la nueva lista existe la comanda seleccionada
            var existeSeleccion = 0;
            
            for( i = 0; i < lista.length; i++ ){
                
                
                var obj = lista[i] ;
                var detalles = lista[i].detalle ;
                
                var hora = obj.fecha.split(" ")[1];
                
                var estadoHtml = "";
                
                if( lista[i].estado == "3" ) estadoHtml = "validado"; 
                if( lista[i].estado == "0" || lista[i].estado == "1" ) estadoHtml = "nuevo";
                
                
                if( obj.id == comandaSeleccionadaId ){
                    existeSeleccion = 1; // si existe en la nueva lista, la comanda seleccionada
                    seleccionarComanda(i); // Actualizamos seleccion en caso de cambios
                }
                
                // HEADER

                var html = "<div id='row"+ i +"' class='intrucciones insIzq " + estadoHtml + "'><div class='tituloInstruccion' onclick='seleccionarComanda("+ i +")' ><strong><span> " + obj.factura + " </span></strong><span> " + hora + " </span><strong class='finalizadoText'><span> FINALIZADO </span></strong></div><hr></div>";
                /*
                html += "<div class='detalle'><table><thead><row><th>Código</th><th>Nombre</th><th>Unidades</th><th>Precio V.</th><th>Descuento</th><th>Total</th></row></thead>";
                
                // BODY
                html += "<tbody>";
                for( j = 0; j < detalles.length; j++ ){
                    
                    var producto = detalles[j];
                    
                    html += "<tr>";
                    html += "<td>" + producto.codigo + "</td>";
                    html += "<td>" + producto.nombre + "</td>";
                    html += "<td>" + producto.unidades + "</td>";
                    html += "<td>" + producto.precio + "</td>";
                    html += "<td>" + producto.descuento + "</td>";
                    html += "<td>" + producto.total + "</td>";
                     
                    html += "</tr>";
                }
                html += "</tbody></table></div>";
                */
                   
                $("#listadoComandas.lista1").append( html );                
                
            }
            
            // Si ya no existe la comanda borramos la seleccion
            if( existeSeleccion == 0 ){
                cancelarSeleccion();
            }
            
            setEstado( "recibido", 0 );
            
        }else{
            var html = '<div class="intrucciones"><div class="tituloInstruccion" style="text-align: center; "><span> No hay comanda </span></div><hr></div>';
            $("#listadoComandas.lista1").html(html);
            cancelarSeleccion();
        }
        
    }
    

    function seleccionarComanda( idObj ){        
        
        $("#listadoComandas.lista2").html("");

        var obj = objComandas[idObj];    
        var nota = objComandas[idObj].nota;    
        var detalles = objComandas[idObj].detalle ;
        
        comandaSeleccionada = idObj;
        comandaSeleccionadaId = obj.id;
        comandaSeleccionadaEstado = obj.estado;

        var hora = obj.fecha.split(" ")[1];

        // HEADER
        var html = "<div class='intrucciones'><div class='tituloInstruccion' ><strong><span> " + obj.factura + " : </span></strong><span> " + nota + " </span></div><hr></div>";
        html += "<div ><table><thead><row><th>Código</th><th>Nombre</th><th>Unidades</th><th>Descripción</th></row></thead>";

        // BODY
        html += "<tbody>";
        for( j = 0; j < detalles.length; j++ ){

            var producto = detalles[j];

            html += "<tr>";
            html += "<td>" + producto.codigo + "</td>";
            html += "<td>" + producto.nombre + "</td>";
            html += "<td>" + producto.unidades + "</td>";
            html += "<td>" + producto.descripcion + "</td>";

            html += "</tr>";
        }
        html += "</tbody></table></div>";

        $("#listadoComandas.lista2").append( html );
        $("#contBotones").show();
        
        if(comandaSeleccionadaEstado=="3"){ $("#contBotones .btn").hide(); }
        else{ $("#contBotones .btn").show(); }
        
        setEstado( "visto", comandaSeleccionadaId );
        $("#row"+idObj).removeClass("nuevo");

        
        
    }
        
    function expandir(){
        $("#lastBorder").css("background-color","#f7f7f7");

        $(".detalle").show();
    }
    
    function contraer(){
        $("#lastBorder").css("background-color","#fff");
        $(".detalle").hide();
    }
    
    function cancelarSeleccion(){
        
        comandaSeleccionada = -1;
        comandaSeleccionadaId = -1;
        
        $("#contBotones").hide();
        $("#listadoComandas.lista2").html("<div class='intrucciones'><div style='text-align: center; background-color: #fdfdfd; font-size: 16px; padding: 40px;'><span> No hay detalles </span></div><hr></div>");

    }
    
    function validarSeleccion(){        
                
        setEstado( "validado", comandaSeleccionadaId );  
        cancelarSeleccion();
        getComandas();
        
    }    
    
    //==============================
    //       AJAX    
    //==============================
    
    function getComandas(){
        
        $.ajax({
            url: '<?php echo site_url(); ?>/comanda/getComandas/'+id,            
            dataType: 'json',            
            success: function (data) {
                objComandas = data;
                actualizarLista();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
      
    }

    function consultarNotificacion(){
        
        $.ajax({
            url: '<?php echo site_url(); ?>/comanda/getNotificacion/'+id,            
            async: false,
            dataType: 'json',            
            success: function (data) {
                
                if( ultimaNotificacion != data[0].notificacion){
                    ultimaNotificacion = data[0].notificacion;
                    getComandas();  
                    console.log("cambio");
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
      
    }


    function setEstado( tipo, id ){
        
        $.ajax({
            url: '<?php echo site_url(); ?>/comanda/setEstado/'+tipo+'/'+id,   
            async: false,
            dataType: 'json',            
            success: function (data) {
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
      
    }
    
    //==============================
    //       Iniciar Aplicaicon
    //==============================

    function run(){
        setInterval(function(){
            consultarNotificacion();
        },3000)
    }


    $(document).ready(function(){
        
        consultarNotificacion();
        
        // Iniciamos el setinterval
        run();

    });  
    
</script>



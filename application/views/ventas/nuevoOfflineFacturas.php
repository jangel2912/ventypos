

<style>

    #v2Cont.panel {margin-bottom: 0px !important; border: none !important; box-shadow: none !important;}
    #v2Cont.panel,.body,.wrapper
    {
        margin: 0px;
        padding: 0px;
        background-color: transparent;
    }

    .panel-title{
        padding: 5px;
    }

    table a{ color: #66B12F; font-size: 13px; }
    table a:hover{ text-decoration: underline; color: #5B7D3A}    


    /*  SOBREESCRIBIR  */

    #add-new-client{
        padding: 3px 6px 3px 6px;
        margin-top: 4px;
        float: right;
    }

    #categorias{
        background-color: #fff;
    }    

    .newPanel{
        background-color: #fff;
    }

    .newContNavegacion{
        padding: 5px 0px 5px 0px;
        margin-bottom: 5px !important;
    }

    #nav-categoria li{
        background-color: #f6f6f6 !important;
        color: #555;
    }

    #next,#next-triangulo{ background-color: #eee !important; }    
    #next-triangulo{ border-color: transparent transparent transparent #ccc; }    

    #next:hover,#next:hover #next-triangulo{ background-color: #ddd !important; }
    #next:hover #next-triangulo{ border-color: transparent transparent transparent #bbb; }

    #nav-categoria li:hover{
        background-color: #eee !important;
    }    

    .newTexto{
        color: #555 !important;
        height: 25px !important;
    }

    .block .head.green *{
        color: #555 !important;
        font-size: 14px;
    }    

    .newContPrecio .head.green {
        background-color: #fff !important;
    }

    .newContPrecio .head.green.well {
        background-color: #f2f2f2 !important;
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }    

    .newContPrecio .head.green span{
        color: #555 !important;
    }    


    #buscalo,#codificalo,#navegador{
        background-color: transparent !important;
        height: auto !important;
        border-left: transparent 0px solid !important;        
    }    

    #tipo-busqueda li.active {
        background-color: transparent !important;
    }
    #tipo-busqueda li.active h3{
        color: #66B12F !important;
    }    

    #codificalo{
        border-left: rgba(0,0,0,0.1) 1px solid !important;
        border-right: rgba(0,0,0,0.1) 1px solid !important;
    }

    #tipo-busqueda{
        height: auto !important;
        margin: 0px !important;
    }
    #tipo-busqueda li {
        height: 30px !important;
        padding-top: 6px;
    }
    #tipo-busqueda li h3 {
        margin: 0px !important;
        padding: 0px !important;
        font-size: 16px !important;
        transition: color 0.1s linear !important;
    }
    #tipo-busqueda li h3:hover {
        color: #66B12F !important;
    }


    #tipo-busqueda img{
        display: none;
    }

    .text-info {
        color: #68AF27;
    }    

    .vitrina-item div{
        background-color: #eee !important;
    }
    .vitrina-item div#pie-item{
        background-color: #eee !important;
    }    
    .vitrina-item div#pie-item:first-child{
        text-align: center !important;
    }        
    .vitrina-item div#pie-item div:last-child {
        clear: both !important;
    }        

    .head.green.well tr td:first-child span{
        font-size: 40px !important;
        font-weight: bold !important;
    }
    .site-navbar {
        display: none !important;
    }


    .btn {
        background: #5E8C47 !important;
        margin-bottom: 0px !important;
        color: #fff !important;
    }

    #pagar{
        color:#fff;
        background-color: #006699 !important;
        float: none !important;

    }
    #cancelarVenta{
        color:#fff;
        background-color: #c5272d !important;
        float: none !important

    }

    .clearBoth{
        clear: both;
    }

    #botonesVenta .btn{        
        margin: 0px 5px 5px 0px;
        padding: 4px 10px !important;
        font-size: 14px;        
    }
    #botonesVenta{
    }    

    #botonesVenta #nota{               
        float: right;
    }

    .newPanel{
        margin-bottom: 10px !important;
    }
    thead{
        background-color:#ECF2F5;
    }
    .imprimir{
        font-size: 16px;
    }

</style>



<div class="panel newPanel" style="margin-top:20px;">
    <h2 style="margin-top:10px; text-align: center">Facturas</h2>
    <hr>
    <div id="contentDataList" class="data-fluid contenedorTabla">
    </div>
</div>

    </div>    
</div>


<script src="<?php echo base_url('public/export/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>









<script>



    
    var table;
    var base;
    
    
    function imprimir(id){
        
        window.idVenta = id;
        
        $.fancybox.open({
            'width': '85%',
            'height': '85%',
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            href: base+"offline/imprimir/"+plantilla+".html",
            type: 'iframe',
            afterClose: function () {
               
            }
        });  
        
    }
    


    //===========================================================================
    //  DATATABLE
    //  
    //  Solo recibe OBJETOS javascript dentro de un array =>   [ { id : "1" } , { id : "2" } ]
    //===========================================================================
    
    function setDataTable( ultimaVenta, obj) {
         
        
        var btnImprimir = function (data){
            return '<a href="javascript:imprimir('+data+')"><i class="site-menu-icon wb-print imprimir" aria-hidden="true"></i></a>';
        }                
        var precio = function (data){
            data=Math.round(data);
            return "<strong>$ "+data.toLocaleString()+"</strong>";
        }

        table = $('#testTable').DataTable({
            
            data: obj,            
            columns: [
                //{data: "factura", title : "Factura", render: bold },
                {data: "id", visible: false, title : "ID"},                
                {data: "factura", title : "Factura"},
                {data: "fecha", title : "Fecha"},
                {data: "cliente", title : "Cliente"},
                {data: "vendedor", title : "Vendedor"},                
                {data: "total", title : "Total", render: precio},
                {data: "id", title : "Imprimir", render: btnImprimir }
            ],            
            order: [[ 0, "desc" ]],
            pageLength: 25,
            sPaginationType: "full_numbers",
            aLengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],            
            bDestroy: 'Blfrtip',
            dom: 'Blfrtip',
            buttons: [],
            language: {
                url: "<?php echo base_url('public/export'); ?>/Spanish.json"
            },
            fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {                                
                    
                    if ( aData.id > ultimaVenta)                    {
                        $('td', nRow).css('background-color', '#EFF5F1');
                    }
                }
                
            }
        );

    }




//===========================================================================
//===========================================================================
//
//      INIT
//
//===========================================================================
//===========================================================================

    $(document).ready(function (e) {



        $("body").attrchange({
            trackValues: true, 
            callback: function (event) {                     
                if( event.newValue == "dashboard site-menubar-unfold" || event.newValue == "dashboard site-menubar-changing site-menubar-unfold")
                    $("body").attr("class","dashboard site-menubar-fold");
            }        
        });



   
        $("#contentDataList").append('<table id="testTable" class="table" width="100%"/> ');
        
        //var obj = [ { id : "1" ,nombre:"edwin"} , { id : "2" ,nombre:"maricio"} ];
        //
        
        appOffline.conectarDB( function(){
            
            appOffline.queryVentasHistorico( function(){
                
                var ultimaVentaProd = appOffline.getIdVentaProd()
                var obj = appOffline.getObjVentasHis()
                base = appOffline.getBase();
                plantilla = appOffline.getPlantilla();
                
                setDataTable( ultimaVentaProd, obj );
            });            
            
        });
        
    });

</script>


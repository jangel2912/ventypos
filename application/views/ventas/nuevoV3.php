<script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>
<script type="text/javascript">
    $(document).on('blur','.dataMoneda',function(){
        $(this).val(limpiarCampo($(this).val()));
    });
</script>

<script type="text/javascript"> var client = <?php echo json_encode($data['clientes']) ?></script>

<?php //echo $this->session->userdata('base_dato');
if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015'): ?>
<script>
    $(document).ready(function () {
        $(document).on("mousedown", "#grabar", function () {
            $("#productos-detail").find(".codigo-final").each(function (i, e) {
                $.post(
                    "<?php echo base_url("index.php/RestFullController/updateProductInventory") ?>", {
                        codigo: $(e).val(),
                        cantidad: $(e).parents("tr").eq(0).find("span.cantidad").text()
                    });
             });
        });
    });
</script>
<?php endif; ?>

<?php
if ($cotizacion != '') {
    ?>
    <script>
        $(document).ready(function () {
            
            $("#iva-total").html(mostrarNumero(<?php echo $cotizacion[0]->monto_iva; ?>));
            $("#subtotal").html(mostrarNumero(<?php echo $cotizacion[0]->monto; ?>));
            $("#total-show").html(mostrarNumero(<?php echo ($cotizacion[0]->monto); ?>));
            $("#id_cliente").val(<?php echo $cotizacion[0]->id_cliente ?>); 
            $("#datos_cliente").val("<?php echo $cotizacion[0]->nombre_comercial ?>");
            var datos =<?php echo json_encode($cotizacion); ?>;
            //console.log(datos);
            var html = "";
            for (var i = 0; i < datos.length; i++) {
                var object = datos[i]; 
                //console.log(object.precio);
                html += "<tr>";
                html += "<td>";
                html += "<input type='hidden' class='precio-compra-real-selected' value='" + object.precio_compra + "'>";
                html += "<input type='hidden' value='" + object.fk_id_producto + "' class='product_id'>";
                html += "<input type='hidden' class='codigo-final' value='" + object.codigo + "' data-cantidad='true'>";
                html += "<input type='hidden' class='impuesto-final' value='" + object.impuesto + "'>";
                html += "<span class='title-detalle text-info'><input type='hidden' value='' class='detalles-impuesto'>" + object.nombre + "</span>";
                html += "</td>";
                html += "<td>";
                html += "<span class='label label-success cantidad'>" + object.cantidad + "</span>";
                html += "<input type='hidden' class='nombre_impuesto' value='" + object.impuesto + "'>";
                html += "<span class='label label-success precio-prod conCalc' onclick='calculadora_descuento(" + Math.round(parseFloat(object.precio)+parseFloat(parseFloat(object.precio) * parseFloat(object.impuesto) /100)) + ");'>" + object.precio + "</span>";
                html += "<input type='hidden' class='precio-prod-real' value='" + object.precio + "'>";
                html += "<input type='hidden' class='precio-prod-real-no-cambio' value='" + object.precio + "'>";
                html += "</td>";
                html += "<td>";
                html += '<span class="precio-calc">' + object.precio + '</span>';
                html += '<input type="hidden" value="precio-calc-real">';
                html += "</td>";
                html += '<td></td>';
                html += "<td>";
                html += '<a class="button red delete" href="#"><div class="icon"><span class="wb-trash"></span></div></a>';
                html += "</td>";
                html += "</tr>";
            }
            $("#productos-detail").html(html);
            
           calculate();
        });</script>
<?php } ?>





<script type="text/javascript">
    $(document).ready(function () {
        /* $("#valor_entregado").blur(function () {
         $("#sima_cambio").val(parseInt($("#valor_entregado").val())-parseInt($("#valor_pagar").val()));
         $("#sima_cambio_hidden").val(parseInt($("#valor_entregado").val())-parseInt($("#valor_pagar").val()));
         });*/
    });
    function mostrar() {
        if (document.getElementById('contenido_a_mostrar1').style.display == 'none') {
            document.getElementById('contenido_a_mostrar1').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar2').style.display == 'none') {
            document.getElementById('contenido_a_mostrar2').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar3').style.display == 'none') {
            document.getElementById('contenido_a_mostrar3').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar4').style.display == 'none') {
            document.getElementById('contenido_a_mostrar4').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar5').style.display == 'none') {
            document.getElementById('contenido_a_mostrar5').style.display = 'block';
        }
    }

    window.onload = function () {
        document.getElementById('contenido_a_mostrar1').style.display = 'none';
        document.getElementById('contenido_a_mostrar2').style.display = 'none';
        document.getElementById('contenido_a_mostrar3').style.display = 'none';
        document.getElementById('contenido_a_mostrar4').style.display = 'none';
        document.getElementById('contenido_a_mostrar5').style.display = 'none';
    }
</script>
<style type="text/css">
    body {
        overflow-x: hidden;
    }

    .ui-dialog{
        z-index: 10000!important;
    }

    #total-show{
        font-weight: bold;
        background: none!important;
        font-size: 20px;
    }

    #contenedor-lista-clientes{
        display: none;
        position: absolute;
        width: 247px;
    }
    #buscar-cliente{
        width: 217px!important;
    }

    #contenedor-lista-clientes ul#lista-clientes{

        height: 168px;
        list-style: none;
        margin-left: 0px;      
        overflow-x: hidden;
        overflow-y: scroll;
        background: white;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
        -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
        -ms-transition: border linear 0.2s, box-shadow linear 0.2s;
        -o-transition: border linear 0.2s, box-shadow linear 0.2s;
        transition: border linear 0.2s, box-shadow linear 0.2s;
        border: 1px solid #DDD;        

    }

    #contenedor-lista-clientes ul#lista-clientes li{
        border-bottom: 1px #CCC solid;
        padding: 4px 10px;
        color: #555;
        font-size: 11px;

    }

    #contenedor-lista-clientes ul#lista-clientes li:hover{
        background: #68AF27;
        color: white;
        cursor:pointer; cursor: hand
    }


    #cod-container{
        display: none;
        width: 40%;
        padding: 10px 7px;
        border:1px solid #EBEBEB;
        overflow: hidden;
        color: #757575;
        margin-left: 25%;
    }

    #cod-container:hover{
        background: #F9F9F9;
        cursor:pointer; cursor: hand;
    }

    #cod-item,#cod-item-descripcion{
        overflow: hidden;
    }

    #cod-item{
        text-align: center;
    }

    #cod-item-descripcion{
        text-align: center;
        margin-left: 10px;
    }

    #cod-item-descripcion strong{
        margin-right: 5px;
        font-weight: 700;
    }

    #cod-container img{
        width: 266px;
        height: 232px;
        border:2px dotted #EBEBEB;/*//68af27*/
    }

    #cod-container #cod-nombre{
        color: #68AF27;
        margin-top: 0px;
        margin-bottom: 0px;
    }

    #cod-container #cod-precio{
        margin-top: 3px;
        color: rgb(41, 41, 173);
        text-align: center;
        display: block;
        background: #5327AF;
        color: white;/*
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;*/
        padding-top: 5px;
        padding-bottom: 5px;
        width: 95%;
    }

    #vitrina{
        display: none;
        padding-bottom: 30px;
    }

    #contenedor-vitrina #cbx-category{
        margin-top: -10px;
        margin-bottom:10px; 
        display: none;
    }

    .vitrina-item{
        border:1px solid #EBEBEB;
        float: left;
        margin-left: 7px;
        margin-top: 8px;
        cursor:pointer; cursor: hand;
        width: 134px;
        height: 175px;
    }

    .vitrina-item:hover{
        -webkit-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        -moz-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
    }

    .vitrina-item div#pie-item #item-nombre{
        font-size: 10px !important;
        color: #ec6400 !important;
    }
    
    .vitrina-item div#pie-item{
        margin-top: 0px;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        color: white;
        background: rgba(104,175,39,0.9); 
        overflow: hidden;
    }

    #item-nombre,#item-precio{
        float: left;
    }

    #item-nombre{
        width: 53%;
        font-size: 12px;
        padding-left: 7px;
        text-align: left;
        /*  background: blue;*/
    }
    #item-precio{
        width: 39%;
        display: block;
        background: #5327AF;
        color: white;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        margin-right: 3px;
        font-size: 13px;
        /*   background: red;*/
    }

    #vitrina{
        overflow: hidden;
    }

    .vitrina-item img{
        width: 170px;
        height: 130px;
    }

    .vitrina-item h5{
        margin: 0;
    }

    #tipo-busqueda{
        list-style: none;
        margin-left: 0px;
        overflow: hidden;
    }

    #tipo-busqueda li{
        height: 65px;
        background: #68AF27;
        width: 33.1%;
        float: left;
        text-align: center;
        color: white;
        border-left: white 1px solid;
        cursor:pointer; cursor: hand;
    }

    #tipo-busqueda li.active{
        background: #316800!important;
    }

    #tipo-busqueda li h3{
        font-family: "Segoe UI", arial, sans-serif;
        font-weight: 400;
    }

    #categorias{
        padding-top: 15px;
        padding-bottom: 15px;
        font-size: 14px;
        background: #4C8D13;
        overflow: hidden;
        display: none;
    }

    #nav-categoria{

        list-style: none;
        margin: 0;
        overflow: hidden;
        float: left;
        margin-left: 10px;
        width: 91%;

    }

    #nav-categoria li{
        float: left;
        background: #67AF27;
        margin-right: 10px;
        height: 90px;
        color: white;
        cursor:pointer; cursor: hand;
        text-align: center;
        width: 12%;
    }

    #nav-categoria li:hover{
        background: #83C44A;
    }

    #nav-categoria li img{
        width: 100%;
        height: 70px;
    }

    .btn-control{
        width: 4%;
        height: 100px;
        float: left;
        background: #83C44A;
    }

    #previous{
        margin-left: 10px;
    }
    #next{
        margin-right: 10px;
        float: right;
        cursor:pointer; cursor: hand;
    }
    #next:hover{
        background: #316800;
    }

    #next-triangulo{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 13px 0 13px 13px;
        border-color: transparent transparent transparent #4C8D13;
        margin-top: 35px;
        margin-left: 25%;
    }


</style>


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
    #cod-barras-sep{
        border-color: #dedede !important;
    }
    #cod-container #cod-img{
        margin-bottom: 10px;
    }
    
    #cod-precio{
        background-color: transparent !important;
        color: rgb(98, 203, 49) !important;
        font-weight: 500;
        font-size: 28px !important;
        padding: 0px !important;
        margin: 0px !important;
        width: 100% !important;
    }
    #cod-item-descripcion{
        margin: 0px !important;
    }
    
    #nav-categoria li img {
        width: 70px;
        height: 70px;
    }
    #nav-categoria li {
        width: 70px;
    }
    
    .nombre_producto{
        font-weight: 500 !important;
        color: #000;
    }
    
    .newPanel2{
        background-color: #fff;
        box-shadow: 0 2px 6px -3px rgba(0,0,0,0.1) !important;
        padding: 5px 5px 0px 5px;
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0,0.1) !important;
        overflow: hidden;
    }
    
    #tipo-busqueda h3{
        color: #131212 !important;
    }
    
    #botones .popover-content{
        padding: 10px;
        margin-bottom: 10px;
        height: 40px;
    }
    #botones .popover-content .btn-warning{
        background-color: #c74646 !important;
        margin-left: 4px;
    }
    #botones .popover-content .btn{
        padding: 3px 6px;
    }
    
    .funcLista{
        text-decoration: none !important;
        color: #53a52a;
    }
    .funcLista:hover span.textFunc{
        text-decoration: underline;
    }
    .textFunc{
        margin-left: 4px;
        font-weight: 400;
        text-decoration: none !important;
    }
    
    #faqSearch{
        padding-bottom: 1px;
    }
    
    #tablaListaProductos td a {        
        font-size: 10px;
        margin: 0px;
        padding-bottom: 2px !important;
    }

    
    
    #tablaListaProductos td a {
        color: #C22439;
        background-color: #f4f4f4 !important;
        padding: 3px 5px 0px 5px;
        border-radius: 4px;
        
        -webkit-transition: color 200ms linear, background-color 200ms linear;
        -moz-transition: color 200ms linear, background-color 200ms linear;
        -o-transition: color 200ms linear, background-color 200ms linear;
        -ms-transition: color 200ms linear, background-color 200ms linear;
        transition: color 200ms linear, background-color 200ms linear;
                
    }
    
    #tablaListaProductos td a:hover {
        background-color: #C22439 !important;
        color: #fff  !important;
    }

    
    
    .label-success, .badge-success, .green {
        background: #797979 !important;
    }
    
    .label-success:hover{
        background: #68AF27 !important;
        -webkit-transition: background-color 200ms linear;
        -moz-transition: background-color 200ms linear;
        -o-transition: background-color 200ms linear;
        -ms-transition: background-color 200ms linear;
        transition: background-color 200ms linear;
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
    .newContPrecio{
        padding: 0px;
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
        background-color: #fff !important;
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
        /*display: none !important;*/
    }
    .navbar .btn, .navbar .btn-group {
        margin-top: 0px;
    }


    .btn {
        border-color: #5E8C47;
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
        color: #c5272d !important;
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

    .textShadow{
        text-shadow: 0px 0px 1px #000000;
        color: #ffffff !important;
        text-shadow: 0px 0px 1px #ffffff;
    }
    #pagarTitulo{
        font-weight: 600 !important;
        font-size: 30px !important;
        font-family: Roboto,sans-serif !important;
        color: #ffffff !important;
        text-shadow: 0px 0px 1px #ffffff;
        font-style: italic;
        text-align: left;
        padding-left: 20px;
        line-height: 30px;
    }
    
    #pagarTable,#pagarTableInfo{
        border-collapse: separate;
    }
    #pagarTableInfo{
        background-color: #f2f2f2;
        color: #000;
        text-align: center;
    }

    #bordeBevelLeft{
	border-top: none;
        border-bottom: none;
	border-left: none;
	border-right: 1px solid #DBDBDB !important;
    }
    #bordeBevelRight{
	border-top: none;
        border-bottom: none;
	border-left: 1px solid #FFFFFF !important;
	border-right: none;
    }
    #bordeBevelTop{
	border-top: 1px solid #FFFFFF !important;	
        
    }
    #bordeBevelBottom{
	border-bottom: 1px solid #DBDBDB !important;
    }    
    #total-show {
        padding: 0px;
        font-weight: 400;
        background: none!important;
        font-size: 40px;
        color: #ffffff !important;
        text-shadow: 0px 0px 1px #ffffff;
        padding-top: 0px;
        line-height: 40px;
    }

    #total_show_peso{
        font-size: 28px !important;
        color: #ffffff !important;
        text-shadow: 0px 0px 1px #ffffff;
        line-height: 10px;
    }
    
    #btnGrandePagar,#btnGrandePagar2{
        cursor: pointer;
        padding: 0px;
        margin: 10px;
        border: none;

        text-align: center !important;
        background-color: #6dca42 !important;
        box-shadow: 0 0px 4px 0 rgba(0, 0, 0, 0.1), 0 3px 8px 0 rgba(0, 0, 0, 0.20);
        border-radius: 5px;
        
        -webkit-transition: background-color 100ms linear;
        -moz-transition: background-color 100ms linear;
        -o-transition: background-color 100ms linear;
        -ms-transition: background-color 100ms linear;
        transition: background-color 100ms linear;
        
    } 
    
    
    #pagarInfo,#iva-total,#subtotal,#sigPeso{
        font-weight: 100 !important;
        font-size: 14px !important;
    }
    #pagarInfo{
        font-size: 12px !important;
    }
    #sigPeso{
        margin-left: 8px;
        margin-right: 2px;
    }
    #sigPeso,#iva-total,#subtotal{
        font-weight: 500 !important;
    }
    
    #listaProductos{
        background-color: #f9f9f9;
    }
    
    tr.nothing td{
        text-align: center;
        padding-top: 100px !important;
        height:100px !important;
        color: #aaa !important;
    }

    #tablaListaProductos {   
        border-collapse: separate !important;
        overflow-x: hidden !important;
    }  
    
    #tablaListaProductos td{
        height: 30px;
        border-bottom: 1px solid #e2e2e2 !important;
        border-top: 1px solid #fff !important;
        background-color: #f4f4f4;
        padding-left: 12px;
    }

    
    
    #tablaListaProductos .text-info {
        color: #232323;
        font-size: 11px;
        font-weight: 400;
    }
    #tablaListaProductos .precio-calc {
        color: #232323;
    }    
    
    #tablaListaProductos tr.nothing td{
        border-bottom: none !important;
        background-color: transparent;
        border: none;
    }

    #tablaListaProductos tr td:first-child {
        padding-left: 20px;
    }
    
    #tablaListaProductos tr td:last-child {
        padding-top: 3px;
        padding-left: 0px;
    }
    #tablaListaProductos tr td:last-child {
        width: 30px;
    }
    #tablaListaProductos .title-detalle {
        word-break: break-all !important;
    }
    
   
    .input-group-addon{
        padding: 5px 12px 5px 12px;
    }
    
    .input-group-addon.btnClientes{
        color: #66b12f;
        cursor: pointer;
    }
    
    .input-group input{
        background-color: #f9fcfd !important;
        height: 30px;
        border-radius: 0px 3px 3px 0px;
        border: 1px solid #eaeaea;
        z-index: -1;
    }
    .input-group .wb-user{
        font-size:14px !important;
    }
    
    #formLeft{
        border-right: 1px solid #eaeaea;
    }
    
    #botones div.btn{
        border: none;
        background: #545454 !important;
        width: inherit !important;
        height: inherit !important;
        padding: 4px 10px !important;
        font-size: 12px;
        
        -webkit-transition: background-color 200ms linear;
        -moz-transition: background-color 200ms linear;
        -o-transition: background-color 200ms linear;
        -ms-transition: background-color 200ms linear;
        transition: background-color 200ms linear;
        
    }
    #botones div.btn:active,
    #botones div.btn:visited,
    #botones div.btn:focus{
        background: #545454 !important;        
    } 
    
      
    #botones div.btn:hover{
        background: #5E8C47 !important;        
    }    

    
    #botones table{
        display:none;
    }
    .btn.funcLista{
        width: 80%;
        text-decoration: none;
        background: #6dca42 !important;
        margin-top: 5px;
        padding: 2px;
    }
    
    #listadoProdcutos{
        color: #555;
        text-align: center;
        padding: 6px;
        font-weight: 400;
        font-size: 18px;
    }
    .codigoGift{
        width: 205px !important;
        margin-bottom: 5px !important;
        display:none !important;
        float: left;
    }
    .btnBuscarGift2{
        display:none !important;
        font-weight: 400 !important;
        font-size: 12px;
        background-color: #5bb133 !important;
        padding: 5px 5px;
        margin-left: 2px;
        margin-bottom: 2px !important;        
        float: left;
    }
    
    .contCalc #Calc .btn:first-child {
        width: 35px;
    }
    
    .contCalc .popover-content{
        width: 170px;
        margin: 0px;
        padding: 0px;
        padding-left: 25px;
        padding-right: 10px;
    }
    
    .contCalc #Calc .row{
        margin-bottom: 10px;
    }
    
    .contCalc #Calc .span12 input{
        width: 177px;
        margin-top: 12px;
        border-radius: 3px;
    }
    
    .contCalc .popover{
        width: 200px;
    }
    .contCalc .btn{
        border: none;
        background: #4c4c4c !important;
        border-radius: 30px;
    }
    .contCalc .btn:hover {
        background: #6E9E55 !important;
    }

    .popover .ui-spinner span.ui-icon{
        margin-top: -5px;
    }
    .popover .ui-spinner{
        margin-bottom: 20px;
    }
    
    #btnBackupOffline{
        background-color: #fff !important;
        color: #000 !important;
    }
    
    .toast {
        opacity: 1 !important;
    }

    .toast-warning {
        background-color: #C70C0C;
    }
    
    #toast-container>.toast-warning {
        background-position-y: 22px;
    }

    #search,  #search:focus{
        border-color: #d2d2d2 !important;
        box-shadow: 0px 1px 6px rgba(0,0,0,0.08) !important;
        border-radius: 4px 0px 0px 4px;
    }
    
    #DataTables_Table_2_paginate .paginate_button{
        background-color: #ffffff;
        text-shadow: 0 1px 0 #fff;
        box-shadow: 0px 1px 6px rgba(0,0,0,0.1) !important;
        color: #333 !important;
    } 
    
    #faqSearch{
        background-color: #6dca42 !important;
        padding: 5px 12px 1px 12px;
        border-radius: 0px 5px 5px 0px !important;
        box-shadow: 0px 1px 6px rgba(0,0,0,.2) !important;
    }
    
    #clienteCartera{
        color: #de240d;
        font-weight: 400;
    }
    
    #facturasTable tbody tr:hover {
        background-color: #fbfbfb !important;
    }
    
    @media (max-width: 1024px){
       
        #pagarTitulo, #total_show_peso{
            font-size: 20px !important;
        }
        #total-show {
            font-size: 24px !important;
        }  
    }
    
</style>

<div class="content" >
    <input type="hidden" id="subtotal_input">
    <input type="hidden" id="subtotal_propina_input">
    <div class="row-fluid no-space">

        <!--Derecha-->

        <div class="col-md-5 pull-right" style="margin-top: 7px; padding: 0px 10px">

            <div class="block panel newPanel newContPrecio">                    

                <div id="listadoProdcutos">Listado Productos</div>

                <hr style="margin: 2px 0px 0px 0px; border-color: #e4e4e4; margin-top: 0px">

                <div class="data-fluid">

                    <div id="listaProductos" style="height:200px; overflow-x: hidden; width:100%">

                        <table id="tablaListaProductos" cellpadding="0" cellspacing="0" width="100%" class="dtable lcnp">
                            <thead >
                                <tr style=" display: none">
                                    <td width="50%"></td>
                                    <td width="10%"></td>
                                    <td width="10%"></td>
                                    <td width="19%"></td>
                                    <td width="1%"></td>
                                    <td width="10%"></td>
                                </tr>
                            </thead>
                            <tbody height="50px" id="productos-detail">



                                <tr class="nothing">

                                    <td>No existen elementos</td>

                                </tr>                     

                            </tbody>

                            <?php
                            $permisos = $this->session->userdata('permisos');

                            $is_admin = $this->session->userdata('is_admin');
                            /* foreach($data['espera_detalles'] as $f){ ?>  
                              <tr><td><input type='hidden' class='precio-compra-real-selected' value='qr'/><input type='hidden' value='qr' class='product_id'/><input type='hidden' class='codigo-final' value='qr'><input type='hidden' class='impuesto-final' value='qr'><span class='title-detalle text-info'><input type='hidden' value='qr' class='detalles-impuesto'>qr</span></td>
                              <td><span class='label label-success cantidad'>qr</span></td>
                              <td><span class='label label-success precio-prod'>qr</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='qr'/><input type='hidden' class='precio-prod-real-no-cambio' value='qr'/></td>
                              <td><span class='precio-calc'>qr</span><input type='hidden' value='precio-calc-real' value='qr'/></td>
                              <td><td><a class='button red delete' href='#'><div class='icon'><span class='wb-trash'></span></div></a></td></td>
                              </tr>
                              <?php } */
                            ?>  

                        </table>

                    </div>
                    <hr style="margin: 0px; border-color: #e4e4e4;">
                    <div id="bordeBevelTop">
                        <table id="pagarTableInfo" width="100%" >
                            <tr style="height:30px;">
                                <td width="50%" id="bordeBevelLeft"> <span id="pagarInfo">IVA: </span><span id="sigPeso">$</span><span id="iva-total">0.00</span> </td>
                                <td width="50%" id="bordeBevelRight"> <span id="pagarInfo">Subtotal: </span><span id="sigPeso">$</span><span id="subtotal" >0.00</span> </td>
                            </tr>
                        </table>
                    </div>
                    <hr style="margin: 0px; border-color: #e4e4e4; margin-top: 0px">
                    <div id="btnGrandePagar" class="head green well" style="color: #fff; font-size: 55px;  padding-top:8px !important; padding-bottom:8px !important; margin-bottom: 8px;">
                        <input type="hidden" value="0" id="total"/>
                        <table id="pagarTable" width="100%" >
                            <tr>
                                <td width="30%" id="">
                                    <div id="pagarTitulo">Pagar</div>
                                </td>
                                <td width="70%" id="">
                                    <div id="">
                                        <span id="total_show_peso" class="textShadow"> $ </span>
                                        <span class="label label-info textShadow" id="total-show"> 0.00</span>
                                    </div>
                                </td>

                            </tr>                     
                        </table>
                    </div>
                    <hr style="margin: 0px; border-color: #e4e4e4;">
                    
                    <!-- <table height="7px"><tbody><tr><td></td></tr></tbody></table>   -->


                    <div class="row-fluid no-space">
                        
                        <div class="col-md-8" id="formLeft" style="padding:10px 15px 18px 15px;">
                            <div id="clienteCartera"></div>
                            <!-- Clientes -->
                            <div class="input-group">
                                <span title="Nuevo Cliente" class="input-group-addon btnClientes" id="add-new-client">
                                    <small class="ico-plus icon-white" style="margin-top:0px; position: absolute; left: 8px; top:8px"></small>
                                    <span class="icon wb-user" aria-hidden="true" style="margin-left:5px; margin-top: 3px;"></span>
                                </span>
                                <input type="text" class="" placeholder="Cliente" value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente" id="datos_cliente">
                            </div>
                            
                            <!-- ?? -->
                            <div id="clienteBlock">
                                <div class="row-form">
                                    <div class="span6">
                                        <div class="input-prepend input-append">
                                      <!--      <input id='buscar-cliente' placeholder="Seleccionar Cliente" style="width: 260px;height: 25px;">  -->

                                            <div id="contenedor-lista-clientes"><ul id="lista-clientes"></ul></div>

                                        </div>
                                    </div> 
                                </div>

                            </div>
                            
                            <!-- Vendedor 1 -->
                            <div class="input-group">
                                <span title="Vendedor" class="input-group-addon">
                                    <span class="icon wb-user" aria-hidden="true" style="margin:3px 4px 0px 2px;"></span>
                                </span>
                                <input type="text" class="" placeholder="Vendedor" value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor" id="datos_vendedor" >
                                <input type="hidden" name="vendedor" id="vendedor"/>
                            </div>

                            <!-- Vendedor 2 -->
                            <div class="input-group" style=" <?= $data['multiples_vendedores'] == '0' ? 'display:none;' : ''; ?> ">
                                <span title="Vendedor" class="input-group-addon">
                                    <span class="icon wb-user" aria-hidden="true" style="margin:3px 4px 0px 2px;"></span>
                                </span>
                                <input type="text" class="" placeholder="Vendedor 2" value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor_2" id="datos_vendedor_2" >
                                <input type="hidden" name="vendedor_2" id="vendedor_2"/>
                            </div>

                            
                            <table id="promociones" data-fetch="<?= site_url('promociones') ?>" width="100%" style="padding-top:5px; border-bottom:5px; display:none; background: #fff !important;" >
                                <tr>
                                    <td  class="head green" width="25%" style="margin-top:0px;">
                                        <div class="icon"><span class="ico-pig"></span></div>
                                        <span style="color: #fff; font-size: 17px;">
                                            <b class="newTexto">Promoción:</b>
                                        </span>
                                    </td>
                                    <td style="padding-right:6px;">
                                        <select name="promocion" id="promocion" style="width: 200px; height: 31px; margin-top: 4px;"></select>
                                    </td>
                                </tr>
                            </table> 
                            
                            <!-- ?? -->   
                            <div class="row-form" id="vendedorBlock">
                                <div class="span3">Nombre:</div>
                                <div class="span9">                                    
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        
                        <div class="col-md-4" style=" margin: 0px; padding: 0px 0px 5px 0px;">                            
                            
                            
                            <ul style=" margin: 10px 0px 0px 0px; list-style-type: none; text-align: center">

                                <?php if( $data['plan_separe'] ) { ?>
                                    <li>
                                        <a href="javascript:void(0)" id="planSepare" class=" btn funcLista">
                                            <span class="textFunc">Plan Separe</span>
                                        </a>
                                    </li>
                                <?php } ?>	

                                <?php if ($data['sobrecosto'] == 'si') { ?>
                                    <li>
                                         <a href="javascript:void(0)" id="sobrecosto" class=" btn funcLista">
                                            <span class="textFunc">Sobrecosto</span>
                                        </a>					
                                    </li>
                                <?php } ?>			
                                
                                <?php if ($data['comanda'] == 'si') { ?>
                                    <li>
                                        <a href="javascript:void(0)" id="comanda" class=" btn funcLista">                                            
                                            <span class="textFunc">Comanda</span>
                                        </a>					
                                    </li>
                                <?php } ?>
                                

                            </ul>
                            
                        </div>
                        
                    </div>
                    
                </div>

                
                
                <hr style="margin: 0px; border-color: #e4e4e4;">
                
                <div style="padding: 12px 20px 12px 20px;">
                    
                    <a id="" href="javascript:void(0)" class="" style="float: left !important; color : #5eaf38; margin-right: 8px; ">
                        <span style=" font-weight: 400; font-size: 14px;" > <small class="icon wb-time"></small> </span>
                    </a>
                    
                    <a id="pendiente" href="javascript:javascript:void(0)" class="" style="float: left !important; color : #5eaf38; margin-right: 20px;">
                        <span style=" font-weight: 400; font-size: 14px;"> Venta en Espera</span>
                    </a>

                    <a id="" href="javascript:void(0)" class="" style="float: left !important; color : #5eaf38; margin-right: 0px; ">
                        <span style=" font-weight: 400; font-size: 14px;" > <strong><small class="ico-plus icon-white"></small></strong> </span>
                    </a>
                    
                    <a id="actualizar_pendiente" href="javascript:javascript:void(0)" class="" style="float: left !important; color : #5eaf38; margin-right: 30px;">
                        <span style=" font-weight: 400; font-size: 14px;">Nueva Venta</span>
                    </a>
                    
                    
                    
                    <a href="javascript:void(0)" id="nota" class="funcLista">
                        <small class="ico-pencil"></small>
                        <span class="textFunc" style="margin-left: 0px">Nota</span>
                    </a>
                    
                    
                    <a id="cancelarVenta" href="javascript:limpiarVentas();" class="" style="float: right !important; font-weight: 400 !important; ">
                        <small class="icon wb-trash" style=""></small>
                        <span style="  font-size: 14px;" >Limpiar</span>
                    </a>                    
                            
                </div>
               
              

            </div>

            <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px;"/>
            <input type="hidden" name="id_lista" id="id_lista" value="0" style="width: 200px;height: 25px;"/>
            <input type="hidden" name="id_fact_espera_nombre" id="id_fact_espera_nombre" style="width: 260px;height: 25px;"/>	


            <div >
                <div id="botones">
                </div>

            </div>          

        </div>

 <div class="col-md-7" style="padding: 0px 10px">

            <div class="block">

                <!--<div class="head" style="text-align: center;">
                    

                    <div class="row-form panel newPanel newContNavegacion" style="padding-left: 0px;padding-right: 0px; padding-top:4px;">
                        <form>
                            <ul id='tipo-busqueda'>
                                <li id ='buscalo' <?php
                                if ($_REQUEST['var'] == "buscalo") {
                                    echo "class='active'";
                                }
                                ?> >
                                    <h3>
                                        <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/buscador.png" width="45px" height="15px" />&nbsp;&nbsp; BUSCADOR </h3></li>
                                <li id ='codificalo' <?php
                                if ($_REQUEST['var'] == "codificalo") {
                                    echo "class='active'";
                                }
                                ?> > <h3>
                                        <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
                                        <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/codigo_barra.png" width="40px" height="15px" />
                                        &nbsp;&nbsp; LECTOR </h3> </li>
                                <li id ='navegador'  <?php
                                if ($_REQUEST['var'] == "navegador") {
                                    echo "class='active'";
                                }
                                ?> > <h3>
                                        <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
                                        <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/navegador.png" width="40px" height="15px" />
                                        &nbsp;NAVEGADOR</h3>  </li>
                            </ul>
                        </form>

                    </div>

                </div>


                <div id='search-container' class="input-append">

                    <input type="text" name="text" class="span12" placeholder="Digite producto a buscar..." id="search" autofocus="autofocus" style="width: 540px;"/>

                    <button class="btn btn-success" id="faqSearch" type="button"><span class="icon-search icon-white"></span></button>

                </div>     
                -->
                <form id="buscador" onsubmit="return enviarCodigo()">
                    <div class="row-form panel newPanel newContNavegacion" style="padding-left: 0px;padding-right: 0px; padding-top:4px;">
                        <ul id='tipo-busqueda'>
                            <li id ='buscalo' <?php echo ($_REQUEST['var'] == "buscalo") ? "class='active'":"" ?> >
                                <h3>
                                    <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/buscador.png" width="45px" height="15px" />
                                    &nbsp;&nbsp; BUSCADOR
                                </h3>
                            </li>
                            <li id ='codificalo' <?php echo ($_REQUEST['var'] == "codificalo") ? "class='active'":"" ?> >
                                <h3>
                                    <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/codigo_barra.png" width="40px" height="15px" />
                                    &nbsp;&nbsp; LECTOR 
                                </h3>
                            </li>
                            <li id ='navegador'  <?php echo ($_REQUEST['var'] == "navegador") ? "class='active'":"" ?> >
                                <h3>
                                    <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)"  src="<?php echo base_url("/public/img/"); ?>/navegador.png" width="40px" height="15px" />
                                    &nbsp;NAVEGADOR
                                </h3>
                            </li>
                        </ul>
                    </div>
                    <div id='search-container' class="input-append">

                        <input type="text" name="text" class="span12" placeholder="Digite producto a buscar..." id="search" autofocus="autofocus" style="width: 540px;"/>
                        <input type="text" name="text2" class="span12" placeholder="Digite producto a buscar..." id="search2" autofocus="autofocus" style="width: 540px; display:none"/>
                        <input type="text" name="text3" class="span12" placeholder="Digite producto a buscar..." id="search3" autofocus="autofocus" style="width: 540px; display:none"/>
                        <input type="text" name="text4" class="span12" placeholder="Digite producto a buscar..." id="search4" autofocus="autofocus" style="width: 540px; display:none"/>
                        <button class="btn btn-success" id="faqSearch" type="submit"><span class="icon-search icon-white"></span></button>
                    </div>
                </form>
                <script>
                
                var campo = 1;codigos = [];
                function codigosBusqueda(codigo){$.ajax({url: $url,dataType: 'json',type: 'post',data: {filter: codigo, type: 'codificalo'},success: function(data){if(data != null){sProduct = data;var precioimpuesto =   (parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto) / 100 + parseInt(sProduct.precio_venta) );/*Descripcion*/$('#cod-nombre').html(sProduct.nombre);$('#cod').html(sProduct.codigo);$('#cod-stock').html(sProduct.stock_minimo);$('#cod-compra').html(sProduct.precio_compra);if(sProduct.ubic != ''){$('#ubic').html("<strong>Ubicaci&oacute;n:</strong> "+sProduct.ubic+"<br>");}else{$('#ubic').html("");}$('#cod-img').attr("src", $urlImages+'/'+sProduct.imagen);$('#cod-precio-impuesto').html("<strong>Precio de venta:</strong> "+formatDollar(Math.round(sProduct.precio_venta))+"<br>");$('#cod-precio').html('$ '+Math.round(precioimpuesto));$('#cod-container').fadeIn('fast');renderFactura();pasarPromocion();calculate();actualizarEspera();$('#search').val('');}else {estadoGift(codigo);}}});}
                function enviarCodigo(){if(campo == 1){codigos.push(document.getElementById('search').value);document.getElementById('search').value = "";document.getElementById('search').style = "display:none";document.getElementById('search2').style = "display:block";document.getElementById('search2').focus();campo = 2;console.log(document.getElementById('search').value);}else if(campo == 2){codigos.push(document.getElementById('search2').value);document.getElementById('search2').value = "";document.getElementById('search2').style = "display:none";document.getElementById('search3').style = "display:block";document.getElementById('search3').focus();campo = 3;console.log(document.getElementById('search2').value);}else if(campo == 3){codigos.push(document.getElementById('search3').value);document.getElementById('search3').value = "";document.getElementById('search3').style = "display:none";document.getElementById('search').style = "display:block";document.getElementById('search').focus();campo = 1;console.log(document.getElementById('search2').value);}return false;}    
                function colocar(codigos){$.each(codigos,function(i,e){codigosBusqueda(e);});}
                setInterval(function(){nuevos = codigos;codigos = [];colocar(nuevos);},2500);
                
                /*$(document).on("submit",'form#buscador',function(event){
                    event.preventDefault();
                    console.log($('input#search').val());
                    codigos.push($('input#search').val());
                    $('input#search').val("");
                    $('input#search').focus();
                    
                    /*if(codigos.length >= 4)
                    {
                        nuevos = codigos;
                        codigos = [];
                        colocar(nuevos);
                    }*-/
                });
                
                function borrar(num) { 
                    provi = codigos.slice(num+1); 
                    codigos = codigos.slice(0,num); 
                    codigos = codigos.concat(provi); 
                }*/
                
                
                
                </script>
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="">

                    <tbody>



                    </tbody>

                </table>

                <div id='buscalo-controles'>
                    <div class="dataTables_info">Mostrando desde 0 hasta 0 de 0 elementos</div>

                    <div id="DataTables_Table_2_paginate" class="dataTables_paginate paging_full_numbers">

                        <a class="first paginate_button paginate_button_disabled" tabindex="0">Primero</a>

                        <a class="previous paginate_button paginate_button_disabled" tabindex="0">Anterior</a>

                        <!--  <a class="paginate_active paginate_button_disabled" tabindex="0">1</a> -->

                        <a class="next paginate_button paginate_button_disabled" tabindex="0">Siguiente</a>

                        <a class="last paginate_button paginate_button_disabled" tabindex="0">Ultimo</a>

                    </div>
                </div>


                <div id="contenedor-vitrina" >
                    <div class="input-append">

                        <div id="categorias" class="panel newPanel" >
                            <!--  <div id='previous' class='btn-control'></div> -->
                            <ul id="nav-categoria">
                                <li id="0" onclick="filtrarCategoria(this)"><img onerror="ImgError(this)"  src="<?php echo base_url("/uploads/") . '/todos.jpg'; ?>"><br>Todos</li>
                                <?php
                                $i = 0;

                                foreach ($data['categorias'] as $key => $value) {
                                    if ($i == 0)
                                        echo '<li id="' . $value->id . '" onclick="filtrarCategoria(this)" ><img onerror="ImgError(this)"  src="' . base_url("/uploads/") . '/general.jpg"><br>' . $value->nombre . '</li>';
                                    else
                                        echo '<li id="' . $value->id . '" onclick="filtrarCategoria(this)" ><img onerror="ImgError(this)"  src="' . base_url("/uploads/") . '/' . $value->imagen . '"><br>' . $value->nombre . '</li>';
                                    $i++;
                                }
                                ?>
                            </ul>
                            <div id='next' class='btn-control' onclick='siguiente_categorias()'>
                                <div id='next-triangulo'></div>
                            </div>
                        </div>


                        <div id="vitrina" class="">
                        </div>

                    </div>
                </div>

                <div id='cod-container' class="panel newPanel">
                    <div id='cod-item'>
                        <img onerror="ImgError(this)"  id='cod-img' src="<?php echo base_url("/uploads/"); ?>/product-dummy.png">  
                        <h5 id='cod-nombre'></h5>
                        <strong>Cod: </strong><span id='cod'></span><br>
                        <strong>Stock: </strong><span id='cod-stock'></span><br>
                        <span id='ubic'></span>
                        <h5 ><span id='cod-precio-impuesto'></h5></span>
                    </div>
                    <hr id="cod-barras-sep">
                    <div id="cod-item-descripcion">
                        <h4 id='cod-precio'></h4>
                    </div>
                </div>


            </div>

            <!-- HTML REST API -> Estado inactivo-->
            <div class="block">


                <table width="100%" id="facturasTable">

                    <tbody>
                        <?php
                        /* foreach ($data['productos'] as $key => $value) {
                          echo "<tr>
                          <td width='20%'><img onerror="ImgError(this)"   src='imagen.png' class='grid-image'></td>
                          <td>
                          <p>
                          <span class='nombre_producto'id='nombre-producto-".$value->id."'>".$value->nombre."</span>&nbsp;
                          <input id='precio-real-".$value->id."' type='hidden' value='".$value->precio_venta."' class='precio-real'>
                          <span class='precio'id='precio_venta-producto-".$value->id."'>".$value->precio_venta."</span>
                          </p>
                          <p>
                          <span class='stock' id='stock_minimo-producto-".$value->id."'>Stock: ".$value->stock_minimo."</span>&nbsp;
                          <input type='hidden' value='".$value->precio_compra."' class='precio-compra-real'>
                          <span class='precio-minimo'>Precio mínimo: ".$value->precio_compra."</span>
                          </p>
                          <input type='hidden' class='id_producto' value=".$value->id.">
                          <input type='hidden' class='codigo' value=".$value->codigo.">
                          <input type='hidden' class='impuesto' value=".$value->impuesto.">
                          </td>

                          </tr>";
                          }
                         */
                        ?>




                </table>
            </div>
            <!-- HTML REST API -> Estado inactivo /-->





        </div>        
        
    </div>
</div>


</div>



</div>




<div id="dialog-nota-form"  title="<?php echo custom_lang('sima_pay_information', "Nota (opcional)"); ?>">

    <form id="client-form">

        <div class="row-form" class="data-fluid">

            <div class="span2"><?php echo custom_lang('sima_pay_value', "Nota"); ?>:</div>

            <div class="span4">
                <textarea rows="9" id="notas" name="notas" cols="60"></textarea> 

            </div>

            <div class="span2"><?php echo custom_lang('sima_pay_value', "Nota Comanda"); ?>:</div>

            <div class="span4">
                <textarea rows="9" id="nota_comanda" name="notas" cols="60"></textarea> 

            </div>

        </div>

    </form>

</div>


<div id="dialog-sobrecosto-form"  title="<?php echo custom_lang('sima_pay_information', "Sobrecosto (opcional)"); ?>">

    <form id="client-form">

        <div class="row-form" class="data-fluid">

            <div class="span2"><?php echo custom_lang('sima_pay_value', "Sobrecosto"); ?>:</div>

            <div class="span4">
                <input type="number" name="sobrecostos_input" id="sobrecostos_input" value="10" />

            </div>

        </div>

    </form>

</div>


<div id="dialog-plan-separe-form"  title="<?php echo custom_lang('sima_pay_information', "Plan separe"); ?>">

    <form id="plan-separe-form">

        <div class="row-form" class="data-fluid">

            <div class="span2"><?php echo custom_lang('sima_pay_value', "Valor a pagar"); ?>:</div>

            <div class="span3">

                <input type='hidden' name='valor_pagar_hidden_plan' id='valor_pagar_hidden_plan'/>
                <input type="hidden" name="descuento_general_plan" id="descuento_general_plan" value="0"/>
                <input type="text" disabled='disabled' name="valor_pagar_plan" id="valor_pagar_plan"/>

            </div>

        </div>

        <div class="row-form">

            <div class="span2"><?php echo custom_lang('sima_forma_pago', "Forma de pago"); ?>:</div>

            <div class="span3">
                
                <select name="forma_pago" id="forma_pago_plan">
                <?php
                    foreach($data['forma_pago'] as $f)
                    {
                        ?>
                        <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                        <?php
                    }
                ?>
                </select>
            </div>

        </div>

        <div class="row-form">

            <div class="span2"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>

            <div class="span3">
                <input type="number" name="valor_entregado_plan" id="valor_entregado_plan"   />
                <input type="hidden" name="id_cliente_plan" id="id_cliente_plan" style="width: 260px;height: 25px;"/>

                <input type="hidden" name="valor_entregado" id="valor_entregado1_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado2_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado3_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado4_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado5_plan" value="0" />

            </div>

        </div>

        <div class="row-form">

            <div class="span2"><?php echo custom_lang('sima_forma_pago', "Fecha de vencimiento"); ?>:</div>

            <div class="span3">

                <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha_vencimiento"/>

            </div>

        </div>



        <br />
        <div align="center"> 
            <input type="button" value="Aceptar"  id="grabar_plan" class="btn btn-primary"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
            <input type="button" value="Cancelar"  id="cancelar" class="btn btn-warning"/> 
        </div>
    </form>

</div>

<div id="dialog-forma-pago-form" title="<?php echo custom_lang('sima_pay_information', "Informacion de forma de pago"); ?>">

    <div class="span6">

        <form id="client-form">

            <div class="row-form">

                <div class="span2"><?php echo custom_lang('sima_pay_value', "Valor a pagar"); ?>:</div>

                <div class="input-append span2">

                    <input type='hidden' name='valor_pagar_hidden' id='valor_pagar_hidden'/>
                    <input type="hidden" name="descuento_general" id="descuento_general" value="0"/>
                    <input type="text" disabled='disabled' name="valor_pagar" id="valor_pagar"/>
                    <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px;"/>
                    <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>

                    <?php } else { ?> 
                        <button type="button" class="btn btn-primary" title="Descuento al valor total de venta" id="descuento_general_pro"  onClick="descuento_general_propover('mostrar');"  >%</button>
                    <?php } ?> 
                </div>

            </div>

            <?php if ($data['multiples_formas_pago'] != 'si') { ?>
                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_forma_pago', "Forma de pago"); ?>:</div>

                    <div class="span3">

                        <select name="forma_pago" id="forma_pago_plan">
                        <?php
                            foreach($data['forma_pago'] as $f)
                            {
                                ?>
                                <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                <?php
                            }
                        ?>
                        </select>

                    </div>
                    <div class="row-form datafono" style="display:none">
                        <div class="span2">
                            <label>Subtotal</label>
                            <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                        </div>
                        <div class="span2">
                            <label>Iva</label>
                            <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                        </div>
                        <div class="span1">
                            <label>Impuesto</label>
                            <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                        </div>
                    </div>
                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>

                    <div class="span3">
                        <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado"/>
                        <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>

                        <input type="hidden" name="valor_entregado" id="valor_entregado1" value="0" />
                        <input type="hidden" name="valor_entregado" id="valor_entregado2" value="0" />
                        <input type="hidden" name="valor_entregado" id="valor_entregado3" value="0" />
                        <input type="hidden" name="valor_entregado" id="valor_entregado4" value="0" />
                        <input type="hidden" name="valor_entregado" id="valor_entregado5" value="0" />

                    </div>

                </div>
            <?php } ?>


            <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                <div id="contenido_a_mostrar">
                    <div class="row-form">

                        <div class="span2">
                        <select name="forma_pago" id="forma_pago">
                        <?php
                            foreach($data['forma_pago'] as $f)
                            {
                                ?>
                                <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                <?php
                            }
                        ?>
                        </select>
                        </div>

                        <div class="span3">
                            
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift" index="" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb" href="javascript:void(0);" class="btn btnBuscarGift2" index=""><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input type="number" class="dataMoneda1"  name="valor_entregado" id="valor_entregado"/>
                            <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>  

                        </div>

                    </div>
                    <div class="row-form datafono" style="display:none">
                        <div class="span2">
                            <label>Subtotal</label>
                            <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                        </div>
                        <div class="span2">
                            <label>Iva</label>
                            <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                        </div>
                        <div class="span1">
                            <label>Impuesto</label>
                            <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                        </div>
                    </div>
                </div>
                <div id="contenido_a_mostrar1">
                    <div class="row-form">
                        <div class="span2">
                            <select name="forma_pago" id="forma_pago1">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div class="span3">
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift1" index="1" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb1" href="javascript:void(0);" class="btn btnBuscarGift2" index="1"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input class="dataMoneda1" type="number" name="valor_entregado" id="valor_entregado1" value="0"/>&nbsp;  
                            <a style='cursor: pointer;' data-id="1" title="">X</a>

                        </div>
                        <div class="row-form datafono" style="display:none">
                            <div class="span2">
                                <label>Subtotal</label>
                                <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span2">
                                <label>Iva</label>
                                <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span1">
                                <label>Impuesto</label>
                                <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contenido_a_mostrar2">
                    <div class="row-form">

                        <div class="span2">
                            <select name="forma_pago" id="forma_pago2">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div class="span3">
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift2" index="2" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb2" href="javascript:void(0);" class="btn btnBuscarGift2" index="2"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input class="dataMoneda1" type="number" name="valor_entregado" id="valor_entregado2" value="0"/> &nbsp;  
                            <a style='cursor: pointer;' data-id="2" title="">X</a> 

                        </div>
                        <div class="row-form datafono" style="display:none">
                            <div class="span2">
                                <label>Subtotal</label>
                                <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span2">
                                <label>Iva</label>
                                <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span1">
                                <label>Impuesto</label>
                                <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contenido_a_mostrar3">
                    <div class="row-form">

                        <div class="span2">
                            <select name="forma_pago" id="forma_pago3">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div class="span3">
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift3" index="3" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb3" href="javascript:void(0);" class="btn btnBuscarGift2" index="3"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input class="dataMoneda1" type="number" name="valor_entregado" id="valor_entregado3" value="0"/> &nbsp;  
                            <a style='cursor: pointer;' data-id="3" title="">X</a>

                        </div>
                        <div class="row-form datafono" style="display:none">
                            <div class="span2">
                                <label>Subtotal</label>
                                <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span2">
                                <label>Iva</label>
                                <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span1">
                                <label>Impuesto</label>
                                <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contenido_a_mostrar4">
                    <div class="row-form">

                        <div class="span2">
                            <select name="forma_pago" id="forma_pago4">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div class="span3">
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift4" index="4" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb4" href="javascript:void(0);" class="btn btnBuscarGift2" index="4"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input class="dataMoneda1" type="number" name="valor_entregado" id="valor_entregado4" value="0" />  &nbsp;  
                            <a style='cursor: pointer;' data-id="4" title="">X</a>

                        </div>
                        <div class="row-form datafono" style="display:none">
                            <div class="span2">
                                <label>Subtotal</label>
                                <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span2">
                                <label>Iva</label>
                                <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span1">
                                <label>Impuesto</label>
                                <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contenido_a_mostrar5">
                    <div class="row-form">

                        <div class="span2">
                            <select name="forma_pago" id="forma_pago5">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div class="span3">
                            <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift5" index="5" value="" placeholder=" Código GiftCard"/>
                            <a id="valor_entregado_giftb5" href="javascript:void(0);" class="btn btnBuscarGift2" index="5"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                            <input class="dataMoneda1" type="number" name="valor_entregado" id="valor_entregado5"  value="0"/> &nbsp;  
                            <a style='cursor: pointer;' data-id="5" title="">X</a>

                        </div>
                        <div class="row-form datafono" style="display:none">
                            <div class="span2">
                                <label>Subtotal</label>
                                <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span2">
                                <label>Iva</label>
                                <input class="impuesto" type="text" disabled="true" value="0" style="text-align:right">
                            </div>
                            <div class="span1">
                                <label>Impuesto</label>
                                <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="16" style="text-align:right">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row-form"> <div class="span2"><p><a style='cursor: pointer;' onClick="mostrar();" title="">Agregar Forma de Pago</a></p></div>
                    <div class="span3"> </div></div>

            <?php } ?>	
            <div id="row-fecha-vencimiento" class="row-form" style="display:none">

                <div class="span2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>

                <div class="span3">

                    <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" />

                </div>

            </div>
            
            <div class="row-form">

                <div class="span2"><?php echo custom_lang('sima_cambio', "Cambio"); ?>:</div>

                <div class="span3">

                    <input type='hidden' name='sima_cambio_hidden' id='sima_cambio_hidden'/>

                    <input type="text" disabled='disabled' name="sima_cambio" id="sima_cambio" />

                </div>

            </div>

            <?php if ($data['sobrecosto'] == 'si') { ?>

                <?php if ($data['nit'] == '320001127839') { ?>
                    <div class="row-form">
                        <div class="span2">
                            Propina <input type="hidden" id="propina_input">
                        </div>
                        <div id="propina_output" class="span3">

                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row-form">
                        <div class="span2">
                            Propina <input type="hidden" id="propina_input_pro">
                        </div>
                        <div id="propina_output_pro" class="span3">

                        </div>
                    </div>

                    <div class="row-form">
                        <div class="span2">
                            Total a pagar con propina 
                        </div>
                        <div id="valor_pagar_propina" class="span3">

                        </div>
                    </div>				

                <?php } ?>

            <?php } ?>
            <br />
            <div align="center"> 
                <input type="button" value="Aceptar"  id="grabar" class="btn btn-primary"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                <input type="button" value="Cancelar"  id="cancelar" class="btn btn-warning"/> 
            </div>
        </form>

    </div>

</div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-client-form" aria-labelledby="ui-id-1" style="display: none; position: relative;"  title="Adicionar Cliente">
    <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
        <span id="ui-id-1" class="ui-dialog-title">Adicionar Cliente</span><button class="ui-dialog-titlebar-close"></button>
    </div>
    <div id="dialog-client-form" class="ui-dialog-content ui-widget-content">
        <form id="client-form">
            <div class="row-fluid">
                <div class="span12">
                    Todos campos son requeridos.
                </div>
            </div>
            <div class="row-fluid errores" style="display:none">
                <div class="span12">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span8">Nombre completo <input type="text" name="nombre_comercial_cliente" id="nombre_comercial_cliente" class="validate[required]"> </div>
                <div class="span4">Tipo de identificaci&oacute;n <?php echo form_dropdown('tipo_identificacion', $data['tipo_identificacion'], "", "id='tipo_identificacion'"); ?></div>                                         
            </div>
            <div class="row-fluid">
                <div class="span4">No de identificaci&oacute;n<input type="text" name="nif_cif" id="nif_cif" class="validate[required]"></div>                                         
                <div class="span8">Correo electronico<input type="text" name="email" id="email" class="validate[custom[email]]"></div>
            </div>
            <div class="row-fluid">
                <div class="span4">Telefono <input type="text" name="telefono" id="telefono"></div>
                <div class="span4">Celular <input type="text" name="celular" id="celular"></div>
                <div class="span4">Dirección <input type="text" name="direccion" id="direccion"></div> 
            </div>
            <div class="row-fluid">
                <div class="span4">Pais <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), " id='pais' style='width: 100%; '"); ?></div>                                    
                <div class="span4">Ciudad   <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'"); ?></div>
            </div>

            <?php
            if ($data['puntos']) {
                if ($data['si_no_plan_punto'] != '0') {
                    ?>
                    <div class="row-fluid">
                        <hr>
                    </div>
                    <div class="row-fluid">
                        <div class="span5" id="asignar_plan"  style='display:none;'>Asignar plan de puntos
                            <table width="100%">
                                <tr>
                                    <td><input type="checkbox" id="plan_puntos"  onclick="plan()" ></td>
                                    <td> 
                                        <table width="100%" id="escoger_plan" style="display:none;">
                                            <tr>
                                                <td>Plan de puntos</td>
                                                <td>
                                                    <?php
                                                    echo "<select  name='pl' id='pl' style='width: 100%' >";
                                                    foreach ($data['plan_puntos'] as $f) {
                                                        echo "<option value=" . $f->id_puntos . ">" . $f->nombre . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Codigo de Tarjeta</td>
                                                <td> <input type="text" name="cod_targeta" id="cod_targeta"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                    <?php
                }
            }
            ?>
        </form>   
    </div>                             
</div>
</div><div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset" style="background:#FFFFFF"></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>


<script src="<?php echo base_url(); ?>public/v2/appVentasOffline.js"></script>

<script type="text/javascript">

                                
                                var facturaAutomatica = "<?php echo $data['auto_factura']  ?>";
                                var pagoAutomatico = "<?php echo $data['auto_pago']  ?>";                                
                                var sobrecostoTodos = "<?php echo $data['sobrecosto_todos']  ?>";
                                
                                
                                
                                var clientesCartera = "<?php echo $data['clientes_cartera']  ?>";
                                
                                var cantidadProductosProduccion = "<?php echo $data['cantidadProductos']  ?>";
                                
                                
                                var offline = "<?php echo getOffline(); ?>";
                                if (offline == "backup") {
                                                                        
                                    var appOffline;
                                    appOffline = new classVentaOffline();
                                    appOffline.conectarDB(function () {
                                        
                                        //OFFLINE
                                        // Obtenemos la cantidad de productos offline
                                        appOffline.getTotalProductos(
                                            function(){
                                                equalProducts( appOffline.totalProductos() );
                                            }
                                        );
                            
                                    });

                                    
                                }

                                var planSepareObj = {saveTitulo: "Titulo", estado: false};
                                var controladorSepare = "<?php echo site_url("ventas_separe/nuevo"); ?>";
<?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                                    $sinprecio = 'si';
<?php } else {
    ?>
                                    $sinprecio = 'no';
<?php } ?>

                                $url = "<?php echo site_url("productos/productos_filter"); ?>";
                                $url = "<?php echo site_url("productos/productos_filter_group"); ?>";
                                $urlVitrina = "<?php echo site_url("productos/get_by_category"); ?>";
                                $urlImages = "<?php echo base_url("/uploads/"); ?>";
                                $urlCategorias = "<?php echo site_url("categorias/limit"); ?>";
                                $sendventas = "<?php echo site_url("/ventas/nuevo"); ?>";                                
                                $sendventas_espera = "<?php echo site_url("/ventas/espera"); ?>";
                                $sendventas_espera_actualizar = "<?php echo site_url("/ventas/espera_actualizar"); ?>";
                                $comanda = "<?php echo site_url("/ventas/comanda"); ?>";
                                $comanda_imprimir = "<?php echo site_url("/ventas/comanda_imprimir"); ?>";
                                $reload = "<?php echo site_url("ventas/index"); ?>";
                                $reloadThis = "<?php echo site_url("ventas/nuevo2"); ?>";
                                $urlPrint = "<?php echo site_url("ventas/imprimir"); ?>";
                                $urlcliente = "<?php echo site_url("clientes/get_ajax_clientes"); ?>";
                                $urlclienteCartera = "<?php echo site_url("clientes/get_ajax_clientes_cartera"); ?>";
                                $navegador = '<?php echo $_REQUEST['var']; ?>';
                                $impuestosnom = "<?php echo site_url("impuestos/get_impuesto"); ?>";
                                $sobrecosto = "<?php echo $data['sobrecosto']; ?>";
                                $nit = "<?php echo $data['nit']; ?>";
                                
                                $pagarGiftCard = "<?php echo site_url("/productos/pagarGiftCard"); ?>";
                                $estadoGiftCard = "<?php echo site_url("/productos/estadoGiftCard"); ?>";
                                $canjearGiftCard = "<?php echo site_url("/productos/cancelarGiftCard"); ?>";
                                
<?php if ($data['multiples_formas_pago'] == 'si') { ?>
                                    $height = 570;
                                    $width = 700;
<?php } ?>
<?php if ($data['multiples_formas_pago'] != 'si') { ?>
                                    $height = 500;
                                    $width = 700;
<?php } ?>

                                function isNumberKey(evt)
                                {
                                    var charCode = (evt.which) ? evt.which : event.keyCode
                                    if (charCode > 31 && (charCode < 48 || charCode > 57))
                                        return false;
                                    return true;
                                }

                                function descuento_general_propover(valor){  

                                    if($("#descuento_general").val() == 0){
                                        propoverContent = "<div class='input-append span2'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-primary' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
                                    }else
                                    {
                                        propoverContent = "<div class='input-append span2'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='"+$("#descuento_general").val()+"' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-primary' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
                                    }

                                    $('#descuento_general_pro').popover({
                                             placement: 'bottom'
                                            , title: 'manual'
                                             , html: true
                                             , content: propoverContent
                                             , trigger: 'manual'
                                                         , placement: "bottom"
                                       }).popover('show');

                                    $("#btn-accept-descuento").click(function(e){
                                        if($('.descuento').val() != ''){
                                            var propina_pro = $("#sobrecostos_input").val() || 10,
                                                valorTotal_pro = $('#subtotal_propina_input').val(),
                                                total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);

                                            var valor_total_propina_descuento = parseFloat(total_pro) - (  ( parseFloat(total_pro) * parseFloat($('.descuento').val()) ) / 100 );																	  

                                            var valor_total_descuento =   (parseFloat($("#total").val()) * parseFloat($('.descuento').val())) / 100;


                                            $('#valor_pagar_propina').html(formatDollar(
                                                (parseFloat($("#total").val()) +  parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento)
                                            ));								 

                                            $('#propina_output_pro').html(propina_pro+'% - '+formatDollar( 
                                                parseFloat(total_pro) - (  ( parseFloat(total_pro) * parseFloat($('.descuento').val()) ) / 100 )
                                                                                                                                                      ));


                                            $("#valor_pagar").val(formatDollar(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                                            $("#valor_entregado").val(Math.round((parseFloat($("#total").val()) +  parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento)));
                                            $("#valor_pagar_hidden").val(Math.round((parseFloat($("#total").val()) - parseFloat(valor_total_descuento)) + parseFloat(valor_total_propina_descuento)));
                                            $("#descuento_general").val($('.descuento').val());
                                        }
                                        validarMediosDePago(e);   
                                        if(isNaN( $('.descuento').val() )){
                                                $('.descuento').val('0');
                                        }


                                        $('#descuento_general_pro').popover('destroy');
                                    });

                                }

                                function resetFacturaEspera() {
                                
                                    $("#id_fact_espera").val('');
                                    $("#id_fact_espera_nombre").val('');
                                    $("#datos_cliente").val('');
                                    $("#id_cliente").val('');
                                    $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                                    $('#total-show').html('0.00');
                                    $('#subtotal').html('0.00');
                                    $('#iva-total').html('0.00');                                        
                                    
                                }
                                
                                // Funcion que guarda en la DB todas las acciones del usuario en tiempo real si esta visualizando una factura en espera
                                function actualizarEspera() {

                                    //Si una factura en espera esta activa, actualizamos en DB

                                    if( $("#id_fact_espera").val() != "" ){

                                        productos_list = new Array();
                                        $(".title-detalle").each(function (x) {
                                            var descuento = 0;
                                            // 
                                            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
                                            if (parseFloat($(".precio-prod-real-no-cambio").eq(x).val()) < parseFloat($(".precio-prod-real").eq(x).val())) {
                                                descuento = 0;
                                            }

                                            productos_list[x] = {
                                                'codigo': $('.codigo-final').eq(x).val()
                                                , 'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val()
                                                , 'unidades': parseFloat($(".cantidad").eq(x).text())
                                                , 'impuesto': $(".impuesto-final").eq(x).val()
                                                , 'nombre_producto': $(".title-detalle").eq(x).text()
                                                , 'product_id': $(".product_id").eq(x).val()
                                                , 'descuento': descuento
                                                , 'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text())
                                            }

                                        });
                                        $.ajax({
                                            url: $sendventas_espera_actualizar
                                            , dataType: 'json'
                                            , type: 'POST'
                                            , data: {
                                                id: $("#id_fact_espera").val(),
                                                productos: productos_list
                                            },
                                            success: function (data) {

                                            }
                                        });
                                        
                                    }else{
                                        
                                    }

                                }
                                    
                                    
                                    
                                    
                                $().ready(function () {

                                    $("#datos_vendedor").autocomplete({
                                        source: "<?php echo site_url("vendedores/get_ajax_vendedores"); ?>",
                                        minLength: 1,
                                        select: function (event, ui) {

                                            
//
                                            $("#vendedor").val(ui.item.id);
                                        }

                                    });
                                    $("#datos_vendedor_2").autocomplete({
                                        source: "<?php echo site_url("vendedores/get_ajax_vendedores"); ?>",
                                        minLength: 1,
                                        select: function (event, ui) {
                                            
                                            $("#vendedor_2").val(ui.item.id);
                                        }

                                    });
                                    $("#dialog-nota-form").dialog({
                                        autoOpen: false,
                                        height: 400,
                                        width: 500,
                                        modal: true,
                                        buttons: {
                                            "Aceptar": function () {

                                                $(this).dialog("close");
                                            }

                                        }
                                    });
                                    $("#dialog-sobrecosto-form").dialog({
                                        autoOpen: false,
                                        height: 200,
                                        width: 500,
                                        modal: true,
                                        buttons: {
                                            "Aceptar": function () {

                                                $('#propina_output').html($('#sobrecostos_input').val());
                                                $(this).dialog("close");
                                            }

                                        }
                                    });
                                    $("#dialog-plan-separe-form").dialog({
                                        autoOpen: false,
                                        height: 400,
                                        width: 570,
                                        modal: true,
                                    });
                                    $("#dialog-client-form").dialog({
                                        autoOpen: false,
                                        // height: 550,

                                        width: 620,
                                        modal: true,
                                        buttons: {
                                            "Aceptar": function () { //alert('aca');

                                                if ($("#client-form").length > 0)

                                                {

                                                    $("#client-form").validationEngine('attach', {promptPosition: "topLeft"});
                                                    if ($("#client-form").validationEngine('validate')) {

                                                        var clienteData = {
                                                            nombre_comercial: $('#nombre_comercial_cliente').val()
                                                            , tipo_identificacion: $('#tipo_identificacion').val()
                                                            , nif_cif: $('#nif_cif').val()
                                                            , email: $('#email').val()
                                                            , telefono: $('#telefono').val()
                                                            , direccion: $('#direccion').val()
                                                            , pais: $('#pais').val()
                                                            , provincia: $('#provincia').val()
                                                            , celular: $('#celular').val()
                                                            , plan_puntos: $('#plan_puntos').prop('checked')
                                                            , pl: $('#pl').val()
                                                            , cod_targeta: $('#cod_targeta').val()
                                                        }



                                                        if (offline == "backup") {

                                                            appOffline.guardarCliente(clienteData, function () {
                                                                appOffline.truncateClientes();
                                                            });
                                                        }

                                                        $.ajax({
                                                            url: '<?php echo site_url('clientes/add_fast_ajax_client'); ?>',
                                                            data: clienteData,
                                                            dataType: 'json',
                                                            type: 'POST',
                                                            success: function (data) {

                                                                if (data.success)
                                                                {
                                                                    $("#id_cliente").val(data.id_cliente);
                                                                    $("#id_cliente_plan").val(data.id_cliente);
                                                                    $("#datos_cliente").val($('#nombre_comercial_cliente').val() + " (" + $('#nif_cif').val() + ")");
                                                                    $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());
                                                                    $("#dialog-client-form").dialog("close");
                                                                } else if (!data.success) {
                                                                    $('#dialog-client-form .errores').show();
                                                                    $('#dialog-client-form .errores').find('>.span12').html(data.msg);
                                                                }

                                                            }

                                                        });
                                                    }

                                                }

                                            },
                                            "Cancelar": function () {

                                                $(this).dialog("close");
                                            }

                                        },
                                        close: function () {

                                            $('#nombre_comercial_cliente').val("");
                                            $('#nif_cif').val("");
                                            $('#email').val("");
                                            $('#nombre_comercial').val("");
                                            $('#telefono').val("");
                                            $('#direccion').val("");
                                            $('#cod_targeta').val("");
                                            $('#dialog-client-form .errores').hide();
                                            $('#dialog-client-form .errores').find('>.span12').html('');
                                        }

                                    });
                                    $("#add-new-client").click(function () {

                                        $("#dialog-client-form").dialog("open");
                                    });
                                    //pendientE
                                    $("#comanda").click(function () {//alert('aca');


                                        productos_list = new Array();
                                        $(".title-detalle").each(function (x) {
                                            var descuento = 0;
                                            // 
                                            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
                                            if (limpiarCampo($(".precio-prod-real-no-cambio").eq(x).val()) < limpiarCampo($(".precio-prod-real").eq(x).val())) {
                                                descuento = 0;
                                            }

                                            productos_list[x] = {
                                                'codigo': $('.codigo-final').eq(x).val()
                                                , 'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val()
                                                , 'unidades': parseFloat($(".cantidad").eq(x).text())
                                                , 'impuesto': $(".impuesto-final").eq(x).val()
                                                , 'nombre_producto': $(".title-detalle").eq(x).text()
                                                , 'product_id': $(".product_id").eq(x).val()
                                                , 'descuento': descuento
                                                , 'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text())
                                            }

                                            pago = {
                                                valor_entregado: $("#valor_entregado").val()
                                                , cambio: $("#sima_cambio_hidden").val()
                                                , forma_pago: $("#forma_pago").val()
                                            };
                                        });
                                        $.ajax({
                                            url: $comanda
                                            , dataType: 'json'
                                            , type: 'POST'
                                            , data: {//aqui 
                                                cliente: $("#id_cliente").val()
                                                , productos: productos_list
                                                , vendedor: $("#vendedor").val()
                                                , vendedor_2: $("#vendedor_2").val()
                                                , total_venta: $("#total").val()
                                                , pago: pago
                                                , nota: $("#nota_comanda").val()
                                                , sobrecostos: $("#sobrecostos_input").val()
                                                , id_fact_espera_nombre: $("#id_fact_espera_nombre").val()
                                            }
                                            , error: function (jqXHR, textStatus, errorThrown) {
                                                alert(errorThrown);
                                            }
                                            , success: function (data) { //alert("aca");
                                                if (data.success == true) {
                                                    $.fancybox.open({
                                                        'width': '85%',
                                                        'height': '85%',
                                                        'autoScale': false,
                                                        'transitionIn': 'none',
                                                        'transitionOut': 'none',
                                                        href: $comanda_imprimir + "/" + data.id,
                                                        type: 'iframe',
                                                        afterClose: function () {
                                                            $.ajax({
                                                                url: "<?php echo site_url("ventas/eliminar_comanda_temporal"); ?>",
                                                                type: "GET",
                                                                dataType: "json",
                                                                data: {id: data.id}
                                                            });
                                                        }
                                                        //padding : 5
                                                    });
                                                }
                                            }
                                        });
                                    });
                                    //pendientE
                                    $("#pendiente").click(function () {
                                        
                                        
                                        // Si venimos de otra factura en espera, primero reseteamos
                                        if( $("#id_fact_espera").val() != "" ){
                                                resetFacturaEspera();
                                        }
                                        

                                        productos_list = new Array();
                                        $(".title-detalle").each(function (x) {
                                            var descuento = 0;
                                            // 
                                            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
                                            if (parseFloat($(".precio-prod-real-no-cambio").eq(x).val()) < parseFloat($(".precio-prod-real").eq(x).val())) {
                                                descuento = 0;
                                            }

                                            productos_list[x] = {
                                                'codigo': $('.codigo-final').eq(x).val()
                                                , 'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val()
                                                , 'unidades': parseFloat($(".cantidad").eq(x).text())
                                                , 'impuesto': $(".impuesto-final").eq(x).val()
                                                , 'nombre_producto': $(".title-detalle").eq(x).text()
                                                , 'product_id': $(".product_id").eq(x).val()
                                                , 'linea': $(".nombre_impuesto").eq(x).val()
                                                , 'descuento': descuento
                                                , 'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text())
                                            }

                                            pago = {
                                                valor_entregado: 0
                                                , cambio: 0
                                                , forma_pago: ''
                                            };
                                        });
                                        $.ajax({
                                            url: $sendventas_espera
                                            , dataType: 'json'
                                            , type: 'POST'
                                            , data: {
                                                cliente: $("#id_cliente").val(),
                                                productos: productos_list
                                                , vendedor: $("#vendedor").val()
                                                , total_venta: $("#total").val()
                                                , pago: pago
                                                , nota: $("#notas").val()
                                                , sobrecostos: $("#sobrecostos_input").val()
                                            }
                                            , success: function (data) {
                                                if (data.success == true) {
                                                    // alert(data.id);

                                                    $('#botones').html('');
                                                    $.ajax({
                                                        url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                        type: "GET",
                                                        dataType: "json",
                                                        data: {id: 1},
                                                        success: function (data) {

                                                            if (data != null) {

                                                                for (var i in data)
                                                                {
                                                                    if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {
                                                                        rowHtml = "<div style='width: 79px; padding: 1px 1px; height:40px; background-color: #005683; vertical-align: text-bottom;' class='btn car" + data[i].id + "' id='" + data[i].id + "' onclick='espera_cargar(" + data[i].id + "); cambiar_color(" + data[i].id + ");' ><table height='8px'><tbody><tr><td></td></tr></tbody></table>" + (data[i].factura) + "</div> ";
                                                                        $("#botones").append(rowHtml);
                                                                    }
                                                                }
                                                            } else {
                                                                $("#datos_cliente").val('');
                                                            }

                                                        }
                                                    });
                                                    $.ajax({
                                                        url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                        type: "GET",
                                                        dataType: "json",
                                                        data: {id: 1},
                                                        success: function (data) {
                                                            for (var i in data) {


                                                                if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {
                                                                    $(".car" + data[i].id + "").live('touchstart dblclick', function () {
                                                                        var valor = $(this).attr('id');
                                                                        $.ajax({
                                                                            url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                                            type: "GET",
                                                                            dataType: "json",
                                                                            data: {id: 1},
                                                                            success: function (data) {
                                                                                for (var i in data) {
                                                                                    if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?> && data[i].id != valor) {
                                                                                        $('#' + data[i].id + '').popover('destroy');
                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                        cantidadField = $(this);
                                                                        propoverContent = "<div class='span7'><input type='text' class='spinner' name='cantidad_input' value='" + $('#' + valor + '').text() + "'/></div><button type='button' id='btn-cancel-cantidad" + valor + "' class='btn btn-warning text-right' style='float:right;'><span class='icon-remove icon-white'></span></button><button type='button' id='btn-accept-cantidad" + valor + "'  class='btn btn-primary text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>";
                                                                        $('#' + valor + '').popover({
                                                                            placement: 'bottom'
                                                                            , html: true
                                                                            , content: propoverContent
                                                                            , trigger: 'manual'
                                                                            , placement: "left"
                                                                        }).popover('show');
// modificar factura 
                                                                        $("#btn-accept-cantidad" + valor + "").click(function () {
                                                                            //cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+$('.spinner').val());

                                                                            var nom = $('.spinner').val();
                                                                            $.ajax({
                                                                                url: "<?php echo site_url("ventas/factura_espera_nombre"); ?>",
                                                                                type: "GET",
                                                                                dataType: "json",
                                                                                data: {
                                                                                    nom: $('.spinner').val(),
                                                                                    id: valor},
                                                                                success: function (data) {
                                                                                    //alert(data);
                                                                                    if (data == '1') {
                                                                                        cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>' + cantidadField.text());
                                                                                    }
                                                                                    if (data == '0') {
                                                                                        cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>' + nom);
                                                                                    }

                                                                                }
                                                                            });
                                                                            $('#' + valor + '').popover('destroy');
                                                                            $('#id_fact_espera_nombre').val(nom);
                                                                        });
                                                                        $("#btn-cancel-cantidad" + valor + "").click(function () {

                                                                            $.ajax({
                                                                                url: "<?php echo site_url("ventas/factura_espera_eliminar"); ?>",
                                                                                type: "GET",
                                                                                dataType: "json",
                                                                                data: {id: valor},
                                                                                success: function (data) {

                                                                                }
                                                                            });
                                                                            $('#' + valor + '').popover('destroy');
                                                                            $("#id_fact_espera").val('');
                                                                            $("#id_fact_espera_nombre").val('');
                                                                            $("#datos_cliente").val('');
                                                                            $("#id_cliente").val('');
                                                                            $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                                                                            $('#total-show').html('0.00');
                                                                            $('#subtotal').html('0.00');
                                                                            $('#iva-total').html('0.00');
                                                                            $('#botones').html('');
                                                                            $.ajax({
                                                                                url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                                                type: "GET",
                                                                                dataType: "json",
                                                                                data: {id: 1},
                                                                                success: function (data) {

                                                                                    for (var i in data)
                                                                                    {

                                                                                        if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {
                                                                                            rowHtml = "<div style='width: 79px; padding: 1px 1px; height:45px;  vertical-align: text-bottom;' class='btn " + data[i].id + "' id='" + data[i].id + "' onclick='espera_cargar(" + data[i].id + "); cambiar_color(" + data[i].id + ");' ><table height='8px'><tbody><tr><td></td></tr></tbody></table>" + (data[i].factura) + "</div> ";
                                                                                            $("#botones").append(rowHtml);
                                                                                        }
                                                                                    }

                                                                                }
                                                                            });
                                                                        });
                                                                    });
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                        /*										
                                         
                                         */

                                        $("#id_fact_espera").val('');
                                        $("#id_fact_espera_nombre").val('');
                                        $("#datos_cliente").val('');
                                        $("#id_cliente").val('');
                                        $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                                        $('#total-show').html('0.00');
                                        $('#subtotal').html('0.00');
                                        $('#iva-total').html('0.00');
                                    });
                                    
                                                                      
                                    $("#actualizar_pendiente").click(function () {
                                        actualizarEspera();
                                        resetFacturaEspera();
                                        limpiarVentas();
                                        resetBotonesEsperaColor();
                                    });
                                    
                                        
                                    $('#botones').html('');
                                    $.ajax({
                                        url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                        type: "GET",
                                        dataType: "json",
                                        data: {id: 1},
                                        success: function (data) {

                                            if (data != null) {

                                                for (var i in data)
                                                {

                                                    if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {
                                                        rowHtml = "<div style='width: 79px; padding: 1px 1px; height:45px;  vertical-align: text-bottom;' class='btn " + data[i].id + "' id='" + data[i].id + "' onclick='espera_cargar(" + data[i].id + "); cambiar_color(" + data[i].id + ");' ><table height='8px'><tbody><tr><td></td></tr></tbody></table>" + (data[i].factura) + "</div> ";
                                                        $("#botones").append(rowHtml);
                                                    }
                                                }
                                            } else {
                                                $("#datos_cliente").val('');
                                            }

                                        }
                                    });
                                    
                                });
                                /*--------------------------------------------------
                                 | RENDERIZAR FACTURA                               |
                                 ---------------------------------------------------*/

                                $.ajax({
                                    url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                    type: "GET",
                                    dataType: "json",
                                    data: {id: 1},
                                    success: function (data) {
                                        for (var i in data) {

                                            if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {

                                                $("." + data[i].id + "").live('dblclick', function () {
                                                    var valor = $(this).attr('id');
                                                    $.ajax({
                                                        url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                        type: "GET",
                                                        dataType: "json",
                                                        data: {id: 1},
                                                        success: function (data) {
                                                            for (var i in data) {
                                                                if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?> && data[i].id != valor) {
                                                                    $('#' + data[i].id + '').popover('destroy');
                                                                }
                                                            }
                                                        }
                                                    });
                                                    cantidadField = $(this);
                                                    // text editar++++++++++++++++++++++
                                                    propoverContent = "<div class='span7'><input type='text' class='spinner' name='cantidad_input' value='" + $('#' + valor + '').text() + "'/></div><button type='button' id='btn-cancel-cantidad" + valor + "' class='btn btn-warning text-right' style='float:right;'><span class='icon-remove icon-white'></span></button><button type='button' id='btn-accept-cantidad" + valor + "'  class='btn btn-primary text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>";
                                                    // propoverContent += "<table><tr><td></td></tr></table>";  

                                                    $('#' + valor + '').popover({
                                                        placement: 'bottom'
                                                        , html: true
                                                        , content: propoverContent
                                                        , trigger: 'manual'
                                                        , placement: "left"
                                                    }).popover('show');
                                                    // ++++++++++++++++++++++++++++++++++   


                                                    $("#btn-accept-cantidad" + valor + "").click(function () {
                                                        //cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+$('.spinner').val());
                                                        var nom = $('.spinner').val();
                                                        $.ajax({
                                                            url: "<?php echo site_url("ventas/factura_espera_nombre"); ?>",
                                                            type: "GET",
                                                            dataType: "json",
                                                            data: {nom: $('.spinner').val(), id: valor},
                                                            success: function (data) {
                                                                //alert(data);
                                                                if (data == '1') {
                                                                    cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>' + cantidadField.text());
                                                                }
                                                                if (data == '0') {
                                                                    cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>' + nom);
                                                                }

                                                            }
                                                        });
                                                        $('#' + valor + '').popover('destroy');
                                                        $('#id_fact_espera_nombre').val(nom);
                                                    });
                                                    //eliminar factura espera

                                                    $("#btn-cancel-cantidad" + valor + "").click(function () {

                                                        $.ajax({
                                                            url: "<?php echo site_url("ventas/factura_espera_eliminar"); ?>",
                                                            type: "GET",
                                                            dataType: "json",
                                                            data: {id: valor},
                                                            success: function (data) {

                                                            }
                                                        });
                                                        $('#' + valor + '').popover('destroy');
                                                        $("#id_fact_espera").val('');
                                                        $("#id_fact_espera_nombre").val('');
                                                        $("#datos_cliente").val('');
                                                        $("#id_cliente").val('');
                                                        $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                                                        $('#total-show').html('0.00');
                                                        $('#subtotal').html('0.00');
                                                        $('#iva-total').html('0.00');
                                                        $('#botones').html('');
                                                        $.ajax({
                                                            url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                                            type: "GET",
                                                            dataType: "json",
                                                            data: {id: 1},
                                                            success: function (data) {

                                                                for (var i in data)
                                                                {

                                                                    if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?>) {
                                                                        rowHtml = "<div style='width: 79px; padding: 1px 1px; height:45px;  vertical-align: text-bottom;' class='btn " + data[i].id + "' id='" + data[i].id + "' onclick='espera_cargar(" + data[i].id + "); cambiar_color(" + data[i].id + ");' ><table height='8px'><tbody><tr><td></td></tr></tbody></table>" + (data[i].factura) + "</div> ";
                                                                        $("#botones").append(rowHtml);
                                                                    }
                                                                }

                                                            }
                                                        });
                                                    });
                                                });
                                            }
                                        }
                                    }
                                });
                                function cambiar_color(id) {


                                    $.ajax({
                                        url: "<?php echo site_url("ventas/factura_espera"); ?>",
                                        type: "GET",
                                        dataType: "json",
                                        data: {id: 1},
                                        success: function (data) {
                                            for (var i in data) {
                                                if (data[i].id !== '-1' && data[i].usuario_id == <?php echo $this->session->userdata('user_id'); ?> &&
                                                        data[i].id != id) {   // background: #88BF6C !important; 
                                                    // document.getElementById(''+data[i].id+'').style.backgroundColor = '#DD00DD';
                                                    $('#'+data[i].id+'').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom;');
                                                }
                                            }

                                        }
                                    });
                                    // document.getElementById(''+id+'').style.backgroundColor = '#DD00DD';
                                    $('#'+id+'').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom; background-color:#5E8C47 !important');
                                }
                                
                                function resetBotonesEsperaColor(){                                
                                    $('#botones div').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom;');
                                }
                                
                                function espera_cargar(id) {
                                
                                                                        
                                    /*
                                     // if($('#id_fact_espera').val() == id){      
                                     
                                     //     }
                                     */
                                    $.ajax({
                                        url: "<?php echo site_url("ventas/detalles_espera"); ?>",
                                        type: "GET",
                                        dataType: "json",
                                        data: {id: id},
                                        success: function (data) {

                                            $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                                            $('#total-show').html('0.00');
                                            $('#subtotal').html('0.00');
                                            $('#iva-total').html('0.00');
                                            if (data != null) {
                                                sProduct = data;
                                                
                                                for (var i in data)
                                                {
                                                    // $("#datos_cliente").val(data[i].nombre_producto);	
                                                    //alert(data[i].nombre_producto);


                                                    if ($sobrecosto == 'si' && $nit != '320001127839') {
                                                        var nom;
                                                        $.ajax({
                                                            async: false, //mostrar variables fuera de el function 
                                                            url: $impuestosnom,
                                                            type: "POST",
                                                            dataType: "text",
                                                            data: {imp: data[i].impuesto },
                                                            success: function (data) {
                                                                nom = data
                                                            }
                                                        });
                                                    }                                                    

                                                    rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + data[i].precio_venta + "'/><input type='hidden' value='" + data[i].id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' value='" + data[i].codigo_producto + "' data-cantidad='true'><input type='hidden' class='impuesto-final' value='" + data[i].impuesto + "'><span class='title-detalle text-info'><input type='hidden' value='" + data[i].impuesto + "' class='detalles-impuesto'>" + data[i].nombre_producto + "</span></td>";
                                                    rowHtml += "<td><span class='label label-success cantidad'>" + data[i].unidades + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'></td>";
<?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                                                        rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
<?php } else { ?>
                                                        rowHtml += "<td class='contCalc'><span class='label label-success precio-prod' onClick='calculadora_descuento(" + data[i].precio_venta + ");'>" + mostrarNumero(data[i].precio_venta) + "</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
<?php } ?>

                                                    rowHtml += "<td><span class='precio-calc'>" + data[i].precio_venta + "</span><input type='hidden' value='precio-calc-real' value='" + data[i].precio_venta + "'/></td>";
                                                    rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='wb-trash'></span></div></a></td></td>";
                                                    rowHtml += "</tr>";
                                                    if ($("#productos-detail tr").eq(0).hasClass("nothing")) {
                                                       $("#productos-detail").html("");
                                                       $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                                                    } else {
                                                        $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                                                    }

                                                    
                                                    $('#datos_cliente').val(data[i].cli_nom);
                                                    $('#id_cliente').val(data[i].id_clientes);
                                                    $('#id_fact_espera').val(data[i].venta_id)
                                                    $('#id_fact_espera_nombre').val(data[i].factura);
                                                }
                                                
                                                calculate();
                                                

                                            } else {
                                                $("#datos_cliente").val('');
                                                $('#id_fact_espera').val(id);
                                                calculate();
                                            }

                                        }
                                    });
                                }

                                function renderFactura() {
                                    
                                    var id_producto = sProduct.id;
                                    var isGiftCard = sProduct.gc;
                                    var matching = $('.product_id[value="' + id_producto + '"]').index();
                                    var id_promocion = $('#promocion').val();
                                    
                                    if( isGiftCard=="0"){
                                        sProduct.stock_minimo -= 1;
                                    }
                                    
                                    $('#cod-stock').html(sProduct.stock_minimo);
                                    
                                    // matching = -1 -> aun no esta listado en la factura
                                    // matching =  1 -> = ya esta listado
                                    if (matching == -1) {
                                        
                                        if ($sobrecosto == 'si' && $nit != '320001127839') {
                                            var nom;
                                            $.ajax({
                                                async: false, //mostrar variables fuera de el function 
                                                url: $impuestosnom,
                                                type: "POST",
                                                dataType: "text",
                                                data: {imp: sProduct.impuesto},
                                                success: function (data) {
                                                    nom = data;
                                                }
                                            });
                                        }
                                        sProduct.precio_venta = parseFloat(sProduct.precio_venta);//console.log(sProduct.precio_venta);
                                        var totalProducto = parseFloat(sProduct.precio_venta) + (parseFloat(sProduct.precio_venta) * parseFloat(sProduct.impuesto) / 100);
                                        
                                        sProduct.precio_venta = parseFloat(sProduct.precio_venta);//console.log(sProduct.precio_venta);
                                        sProduct.precio_venta = parseFloat(sProduct.precio_venta);
                                        rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + sProduct.precio_venta + "'/><input type='hidden' value='" + id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' data-cantidad='true' value='" + sProduct.codigo + "'><input type='hidden' class='impuesto-final' value='" + sProduct.impuesto + "'><span class='title-detalle text-info'><input type='hidden' value='" + sProduct.impuesto + "' class='detalles-impuesto'>" + sProduct.nombre + "</span></td>";
                                        rowHtml += "<td><span class='label label-success cantidad'>" + 1 + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'>";
                                        rowHtml += "<input type='hidden' class='promocionPrecio' value='0'><input type='hidden' class='promocionIva' value='0'></td>";
                                        if(validarProductoPromocion(id_promocion,$('.id_producto').eq($(this).index()).val()) && promocionTipo == 1)
                                        {
                                            <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                                            rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-real' data-promocion='1' value='" +sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/></td>";

                                            <?php } else { ?>
                                                rowHtml += "<td class='contCalc'><input type='hidden' data-promocion='1' class='precio-prod-real' value='" + sProduct.precio_venta  + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta  + "'/><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + (sProduct.precio_venta) + "</span></td>";
                                            <?php } ?>
                                        }else
                                        {
                                            <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                                            rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/></td>";
                                            
                                            <?php } else { ?>
                                            rowHtml += "<td class='contCalc'><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta  + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta  + "'/><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + mostrarNumero(sProduct.precio_venta) + "</span></td>";
                                            <?php } ?>
                                        }
                                        rowHtml += "<td><span class='precio-calc'>" +limpiarCampo(sProduct.precio_venta)+ "</span><input type='hidden' class='precio-calc-real' value='" + sProduct.precio_venta + "'/></td>";
                                        rowHtml += "<td><a class='button red delete' href='#'><div class='icon'><span class='wb-trash'></span></div></a></td>";
                                        rowHtml += "</tr>";
                                        var $objDom = null;
                                        
                                        if($("#productos-detail tr").eq(0).hasClass("nothing")){
                                            $("#productos-detail").html("");                    
                                            $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                                        }else{                
                                            $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                                        }
                                        
                                        // Si es giftcard ocultamos los botones de cambiar precio y cantidad de el listado de productos       
                                        if( isGiftCard == "1" ){
                                            $( $objDom[0] ).find(".cantidad").hide();
                                            $( $objDom[0] ).find(".precio-prod").hide();
                                        }
                                        
                                        // agregamos el si es giftcard a la lista de productos seleccionados
                                        $( $objDom[0] ).find(".product_id").after('<input type="hidden" class="giftcard" value="'+isGiftCard+'">');
                                        
                
                                    } else {
                                        
                                        // Si es giftcard no se le permitira añadir mas productos
                                        if( isGiftCard == "0" ){
                                            parent = $('.product_id[value="' + id_producto + '"]').parent().parent().index();
                                            cantidad = parseFloat($('.cantidad').eq(parent).text()) + 1;
                                            $('.cantidad').eq(parent).text(cantidad);
                                        }
                                    }

                                    //calculate();
                                }
                                


                                $(".delete").live("click", (function () {
                                    
                                    $(this).parent().parent().remove();
                                    if ($("#tablaListaProductos tbody tr").length == 0) {
                                        $("#tablaListaProductos tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");
                                    }
                                    pasarPromocion();
                                    calculate();  
                                    actualizarEspera();
                                }));
                                
                                
                                // Limpiamos la factura sin refrescar
                                function limpiarVentas(){
                                    $("#tablaListaProductos tbody tr")
                                    .fadeOut( "slow", function() {
                                        $("#tablaListaProductos tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");
                                        pasarPromocion();
                                        calculate();
                                        actualizarEspera();
                                    });
                                }
                                    
                                
                                $(document).ready(function () {

                                    $(".checker").css("display", "block");
                                    $("#pais").change(function () {

                                        load_provincias_from_pais($(this).val());
                                    });
                                    $("#add-new-client").click(function () {
                                        $.ajax({
                                            url: "<?php echo site_url("puntos/get_por_compras_puntos") ?>/" + $("#total").val(),
                                            success: function (response) {
                                                if (response == 'si') {
                                                    document.getElementById('asignar_plan').style.display = 'block';
                                                } else {
                                                    document.getElementById('asignar_plan').style.display = 'none';
                                                }

                                            }
                                        });
                                    });
                                    
                                    $('div[id^="contenido_a_mostrar"] a').on('click', function (e) {
                                        var id = $(this).data('id');
                                        $('#contenido_a_mostrar' + id).fadeOut();
                                        $('#valor_entregado' + id).removeAttr("disabled");
                                        $('#valor_entregado' + id).val(0);
                                        
                                        //Eliminamos la giftcard si existe en las formas de pago
                                        eliminarGiftcard(id);
                                        
                                        validarMediosDePago(e);
                                    });
                                    
                                    
//puntos -------------------------------------------------------------------
$("#forma_pago, #forma_pago1, #forma_pago2, #forma_pago3, #forma_pago4, #forma_pago5").on('change', function (e)
{
    var forma_pago_id = $(this).attr("id");
    var forma_pago = $(this).val();
    var cliente = $("#id_cliente").val()
    if (forma_pago == 'Puntos') {
        $.ajax({
            url: "<?php echo site_url("puntos/get_datos_punto_redimir") ?>/" + cliente,
            success: function (response) {
                
                $('input[id^="valor_entregado"]').removeAttr('max');
                $('input[id^="valor_entregado"]').removeAttr('min');
                if (forma_pago_id == 'forma_pago') {
                    //$("#valor_entregado").prop('disabled', true);
                    $("#valor_entregado").val(response);
                    $("#valor_entregado").prop('max', response);
                    $("#valor_entregado").prop('min', 0);
                }
                if (forma_pago_id == 'forma_pago1') {
                    //$("#valor_entregado1").prop('disabled', true);
                    $("#valor_entregado1").val(response);
                    $("#valor_entregado1").prop('max', response);
                    $("#valor_entregado1").prop('min', 0);
                }
                if (forma_pago_id == 'forma_pago2') {
                    //$("#valor_entregado2").prop('disabled', true);
                    $("#valor_entregado2").val(response);
                    $("#valor_entregado2").prop('max', response);
                    $("#valor_entregado2").prop('min', 0);
                }
                if (forma_pago_id == 'forma_pago3') {
                    //$("#valor_entregado3").prop('disabled', true);
                    $("#valor_entregado3").val(response);
                    $("#valor_entregado3").prop('max', response);
                    $("#valor_entregado3").prop('min', 0);
                }
                if (forma_pago_id == 'forma_pago4') {
                    //$("#valor_entregado4").prop('disabled', true); 
                    $("#valor_entregado4").val(response);
                    $("#valor_entregado4").prop('max', response);
                    $("#valor_entregado4").prop('min', 0);
                }
                if (forma_pago_id == 'forma_pago5') {
                    $("#valor_entregado5").val(response);
                    $("#valor_entregado5").prop('max', response);
                    $("#valor_entregado5").prop('min', 0);
                }
                validarMediosDePago(e);
            }
        });
    } else {
        //if(forma_pago_id == 'forma_pago'){ $("#valor_entregado").val($("#total").val());  }
        if (forma_pago_id == 'forma_pago1') {
            //$("#valor_entregado1").val(0);
            $("#valor_entregado1").removeAttr("disabled");
        }
        if (forma_pago_id == 'forma_pago2') {
            //$("#valor_entregado2").val(0);
            $("#valor_entregado2").removeAttr("disabled");
        }
        if (forma_pago_id == 'forma_pago3') {
            //$("#valor_entregado3").val(0);
            $("#valor_entregado3").removeAttr("disabled");
        }
        if (forma_pago_id == 'forma_pago4') {
            //$("#valor_entregado4").val(0);
            $("#valor_entregado4").removeAttr("disabled");
        }
        if (forma_pago_id == 'forma_pago5') {
            //$("#valor_entregado5").val(0);
            $("#valor_entregado5").removeAttr("disabled");
        }
    }

    var credito = false;
    $('select[id^="forma_pago"]').each(function (i, e) {
        if ($(e).val() == 'Credito')
            credito = true;
    });
    if (credito)
        $('#row-fecha-vencimiento').fadeIn();
    else
        $('#row-fecha-vencimiento').fadeOut();
    validarMediosDePago(e);
    //$("#sima_cambio_hidden").val($("#valor_pagar_hidden").val() - $(this).val());

});
//puntos -------------------------------------------------------------------

                                    var pais = $("#pais").val();
                                    if (pais != "")
                                    {
                                        load_provincias_from_pais(pais);
                                    }

                                });
                                $("#fecha_vencimiento").datepicker({
                                    dateFormat: 'yy/mm/dd'
                                });
                                $("#fecha_vencimiento_venta").datepicker({
                                    dateFormat: 'yy/mm/dd'
                                });
                                function load_provincias_from_pais(pais) {

                                    $.ajax({
                                        url: "<?php echo site_url("clientes/load_provincias_from_pais"); ?>",
                                        type: "GET",
                                        dataType: "json",
                                        data: {"pais": pais},
                                        success: function (data) {

                                            if (pais != 'Colombia') {
                                                $("#provincia").html('');
                                            }

                                            for (var i in data) {

                                                provincia = "Bogota, D.C."

                                                sel = provincia == data[i].pro_nombre ? "selected='selectted'" : '';
                                                $("#provincia").append("<option value='" + data[i].pro_nombre + "' " + sel + ">" + data[i].pro_nombre + "</option>");
                                            }



                                        }

                                    });
                                }

                                function plan() {

                                    if ($('#plan_puntos').prop('checked') == true) {

                                        document.getElementById('escoger_plan').style.display = 'block';
                                    } else {
                                        document.getElementById('escoger_plan').style.display = 'none';
                                    }

                                }

</script>

<script type="text/javascript">

  
    function errorOffline(error){        
      //  toastr.warning( error );
       $("#btnOffline i").css("color","red");
        
    }
    
    function equalProducts( cantidadProductosLocal ){
        
        if ( cantidadProductosLocal != cantidadProductosProduccion){
           // toastr.warning(' ¡ Ha modificado los productos, haga una copia local antes de vender ! <br><br><button type="button" id="btnBackupOffline" class="btn" style="margin: 0 8px 0 8px" onclick="backupOffline();">Hacer Backup</button>');
            $("#btnOffline i").css("color","red");
        }
    }


    function noLocalData(){
        //toastr.warning(' <strong>¡¡ ERROR !!</strong> <br>Base de datos no sincronizada, realice una copia <strong>inmediata</strong>.<br><br><button type="button" id="btnBackupOffline" class="btn" style="margin: 0 8px 0 8px" onclick="backupOffline();">Hacer Backup</button>');
         $("#btnOffline i").css("color","red");
    }

    function backupOffline(){
        window.location = "<?php echo site_url(); ?>/frontend/borrarOffline/";
    }


    
    $(document).ready(function () {
        
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "0",
            "extendedTimeOut": "0",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };



          
        
    });
</script>

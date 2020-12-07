<?php valide_option('nueva_impresion_rapida','no');  ?>

<script type="text/javascript"> 
    var negocio= '<?php echo $data['tipo_negocio']?>'; 
    var new_fast_print = <?= (get_option('nueva_impresion_rapida') == "si")? 'true' : 'false';  ?>; 
</script>
<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>

<script type="text/javascript">
    $(document).on('blur', '.dataMoneda', function () {
        $(this).val(limpiarCampo($(this).val()));
    });
</script>

<script type="text/javascript"> var client = <?php echo json_encode($data['clientes']) ?></script>

<?php
if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015'):
    ?>
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
    
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $admin = $resultPermisos["admin"];
    if (in_array("1010", $permisos) && $admin !== 't') { 
        $permiso_precio="no";
    }else {
        $permiso_precio="si";
    }
    ?>
    <script>
        $(document).ready(function () {

            $("#iva-total").html(mostrarNumero(<?php echo $cotizacion[0]->monto_iva; ?>));
            $("#subtotal").html(mostrarNumero(<?php echo $cotizacion[0]->monto; ?>));
            $("#total-show").html(mostrarNumero(<?php echo ($cotizacion[0]->monto); ?>));
            $("#id_cliente").val(<?php echo $cotizacion[0]->id_cliente ?>);
            $("#vendedor").val(<?php echo $cotizacion[0]->vendedor ?>);
            $("#datos_vendedor").val("<?php echo $cotizacion[0]->nombre_vendedor ?>");
            $("#datos_cliente").val("<?php echo $cotizacion[0]->nombre_comercial ?>");
            if($("#datos_vendedor").val()!=""){
                $("#datos_vendedor").prop('disabled', true);
            }
            var datos =<?php echo json_encode($cotizacion); ?>;
            var permiso_precio = '<?php echo $permiso_precio; ?>';            
            var html = "";
            $("#cantidad-total").html(''+(Number(datos.length) -1 ));
            for (var i = 0; i < datos.length; i++) {
                var object = datos[i];
                var precio=object.precio;
                if(typeof  object.descuento != "undefined"){
                    if(object.descuento != ""){
                        var descuento=object.descuento;
                        var resultado_porcen1 = parseFloat(parseFloat(object.precio) * descuento / 100);
                        var resultado_porcen2 = (object.precio - resultado_porcen1);                    
                        precio=resultado_porcen2;
                    }
                }
                html += "<tr>";                
                html += '<td width="10%">';
                <?php if(isset($data["eliminar_producto_comanda"])):
                        if($data["eliminar_producto_comanda"] == "si"):?>                           
                            html += '<a class="button red delete" href="#"><div class="icon"><span class="wb-close"></span></div></a>';                           
                        <?php endif; ?>
                <?php endif; ?>
                html += '</td>';
                html += "<td width='40%' style='text-align: left !important;'>";
                html += "<input type='hidden' class='precio-compra-real-selected' value='" + object.precio_compra + "'>";
                html += "<input type='hidden' value='" + object.fk_id_producto + "' class='product_id'>";
                html += "<input type='hidden' class='codigo-final' value='" + object.codigo+ "' data-cantidad='true'>";
                html += "<input type='hidden' class='impuesto-final' value='" + object.impuesto + "'>";
                html += "<span class='title-detalle text-info'><input type='hidden' value='' class='detalles-impuesto'>" + object.nombre + "</span>";
                html += "</td>";
                html += "<td width='10%'>";
                html += "<span class='label label-success cantidad'>" + object.cantidad + "</span>";
                html += "</td>";
                html += "<td width='20%' class='contCalc'>";               
                html += "<input type='hidden' class='nombre_impuesto' value='" + object.impuesto + "'>";
                if(permiso_precio=='si'){
                    html += "<span class='label label-success precio-prod' onclick='calculadora_descuento(" + Math.round(parseFloat(object.precio) + parseFloat(parseFloat(object.precio) * parseFloat(object.impuesto) / 100)) + ");'>" + object.precio + "</span>";
                }else{
                    html += "<span class='precio-prod'>" + object.precio + "</span>"; 
                }
                html += "<input type='hidden' class='precio-prod-real' value='" + precio + "'>";
                html += "<input type='hidden' class='precio-prod-descuento' value='" + object.precio + "'>";
                html += "<input type='hidden' class='precio-prod-real-no-cambio' value='" + object.precio + "'>";
                html += "</td>";
                html += "<td width='20%'>";
                html += '<span class="precio-calc">' + object.precio + '</span>';
                html += '<input type="hidden" value="precio-calc-real">';
                html += "</td>";
                html += "</tr>";
            }
            $("#productos-detail").html(html);

            calculate();
        });</script>
<?php } ?>






<script type="text/javascript">
    $(document).ready(function () {
        $(".success-impresion-rapida").fadeTo(2000, 500).slideUp(500, function(){
            $("#success-alert").slideUp(500);
        });
        /* $("#valor_entregado").blur(function () {
         $("#sima_cambio").val(parseInt($("#valor_entregado").val())-parseInt($("#valor_pagar").val()));
         $("#sima_cambio_hidden").val(parseInt($("#valor_entregado").val())-parseInt($("#valor_pagar").val()));
         });*/

        programar_consulta_ventas_espera();
    });

    function eliminar_efectivo(x){    
        
        bandera=0;
        id=0;
        cadena="efectivo";

        if(x>0){            
            $("#forma_pago"+x).prop("selectedIndex",0);                  
        }
        //me paseo y activo todo
        for (let index = 0; index < 6; index++) {           
            var id = index == 0 ? "" : index;         
            $("#forma_pago"+id).find('option[value="efectivo"]').show().prop("disabled",false);
        }  

        //me paseo por todos los select de pagos y verifico si hay alguno con efectivo para bloquearlos en los otros y viceversa
        for (let index = 0; index < 6; index++) {          
            var id = index == 0 ? "" : index;
            var opcion_seleccionada = $("#forma_pago"+id+" option:selected").val();            
            if (String(cadena.toLowerCase()) == String(opcion_seleccionada.toLowerCase())){
                bandera=1;
                id = index;
                break;
            }            
        }

        if(bandera==1){
            for (let index = 0; index < 6; index++) {               
                if(id!=index){
                    var id2 = index == 0 ? "" : index;
                    $("#forma_pago"+id2).find('option[value="efectivo"]').hide().prop("disabled",true);                                 
                }                                    
            }            
        }else{
            for (let index = 0; index < 6; index++) {              
                var id = index == 0 ? "" : index;

                $("#forma_pago"+id).find('option[value="efectivo"]').show().prop("disabled",false);         
            }     
        }           
    }

    function mostrar() {
        
        if (document.getElementById('contenido_a_mostrar1').style.display == 'none') {
            document.getElementById('contenido_a_mostrar1').style.display = 'block';
            $("#forma_pago1").prop("selectedIndex",0);
        } else if (document.getElementById('contenido_a_mostrar2').style.display == 'none') {
            $("#forma_pago2").prop("selectedIndex",0);
            document.getElementById('contenido_a_mostrar2').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar3').style.display == 'none') {
            $("#forma_pago3").prop("selectedIndex",0);
            document.getElementById('contenido_a_mostrar3').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar4').style.display == 'none') {
            $("#forma_pago4").prop("selectedIndex",0);
            document.getElementById('contenido_a_mostrar4').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar5').style.display == 'none') {
            $("#forma_pago5").prop("selectedIndex",0);
            document.getElementById('contenido_a_mostrar5').style.display = 'block';
        }
        eliminar_efectivo();
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
    .multiple-items1, .responsive{
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s ease;
        -webkit-transition: opacity 1s ease;
    }
    .multiple-items1.slick-initialized, .responsive.slick-initialized {
        visibility: visible;
        opacity: 1;    
    }
    .slick-initialized{
        display:block !important;
    }
    body {
        overflow-x: hidden;
    }

    .ui-dialog{
        z-index: 1000!important;
    }

    #total-show, .subtotales{
        font-weight: bold;
        background: none!important;
        font-size: 12px;
    }

    #contenedor-lista-clientes,#contenedor-lista-clientes-domicilios{
        display: none;
        position: absolute;
        width: 247px;
    }
    #contenedor-lista-clientes-domicilios{
        display: none;
        position: absolute;
        width: 95%;
        z-index: 2;
        left: 1%;
    }
    #buscar-cliente{
        width: 217px!important;
    }

    #contenedor-lista-clientes ul#lista-clientes,#contenedor-lista-clientes-domicilios ul#lista-clientes-domicilios{

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

    #contenedor-lista-clientes ul#lista-clientes li, #contenedor-lista-clientes-domicilios ul#lista-clientes-domicilios li{
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

    #contenedor-lista-clientes-domicilios ul#lista-clientes-domicilios li:hover{
        background: #68AF27;        
        background: #e4eaec;
        color: #505050
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
       /* display: none;*/
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
        width: 150px;
        height: 160px;
    }

    .vitrina-item:hover{
        -webkit-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        -moz-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
    }

    .vitrina-item div#pie-item #item-nombre{
        font-size: 10px !important;
        /*color: #ec6400 !important;*/
        color: #505050 !important;
        text-align: center !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
    }


    .vitrina-item div#pie-item{
        margin-top: 0px;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        color: white;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 80px;
    }

    #item-nombre,#item-precio{
    }

    #item-nombre{
        width: 100%;
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
      /*  overflow: hidden;*/
    }

    .vitrina-item img{
        width: 140px;
        height: 90px;
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

    table a{ color: #5ca745; font-size: 13px; }
    table a:hover{ text-decoration: underline; color: #5B7D3A}


    /*  SOBREESCRIBIR  */
    #comandaSidebar.modal.fade.in,#mesasSidebar.modal.fade.in {
        top: 0px !important;
    }
    #comandaSidebar.modal.fade,#mesasSidebar.modal.fade {
        top: 0px !important;
    }

    #comandaSidebar.modal,#mesasSidebar.modal {
        border: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        left: initial !important;
        z-index: 1700;
        width: 600px !important;
        margin: 0px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
        outline: 0;
        -webkit-overflow-scrolling: touch;
        margin: 0px !important;
        background-color: transparent !important;
        box-shadow: none;
    }
    #comandaSidebarModal,#mesasSidebarModal{
        width: 100%;
    }
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
        /*padding: 5px 5px 0px 5px;*/
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0,0.1) !important;
        overflow: hidden;
       // width:100% !important;
    }

    #tipo-busqueda h3{
        /*color: #131212 !important;*/
        color: #989a9d !important;
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
        /*margin-left: 4px;*/
        font-weight: 400;
        text-decoration: none !important;
    }

    #faqSearch{
        padding-bottom: 1px;
    }

    
    #tablaListaProductos td a {
        /*color: white !important;
        background-color: #C22439 !important;*/
        color: #505050 !important;
        /*background-color: transparent !important;*/
        padding: 5px;
        /*border-radius: 100px;*/
        border:none !important;
         font-size: 10px;
        margin: 0px;
        padding-bottom: 2px !important;
        -webkit-transition: color 200ms linear, background-color 200ms linear;
        -moz-transition: color 200ms linear, background-color 200ms linear;
        -o-transition: color 200ms linear, background-color 200ms linear;
        -ms-transition: color 200ms linear, background-color 200ms linear;
        transition: color 200ms linear, background-color 200ms linear;

    }

    #tablaListaProductos td a:hover {
        color:red !important;
        background-color: none !important;       
    }



    .label-success, .badge-success, .green {
        /*background: rgba(17,40,75,0.93) !important;*/
        background: #505050 !important;
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
        color: #5ca745 !important;
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
        color: #5ca745 !important;
    }


    #tipo-busqueda img{
       /* display: none;*/
       margin-top: -5px;
    }

    /*
    #tipo-busqueda img:hover{
       /* display: none;*/
        margin-top: 0px;
    }*/

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
        /*border-color: #5E8C47;
        background: #5E8C47 !important;*/
        border-color: #5ca745;
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
        font-size: 35px !important;
        font-family: Roboto,sans-serif !important;
        color: #ffffff !important;
        text-shadow: 0px 0px 1px #ffffff;
        text-align: left;
        padding-left: 20px;
        line-height: 30px;
    }

    #pagarTable,#pagarTableInfo{
        font-size: 11px !important;
    }
    #pagarTableInfo{
        background-color: #505050;
        color: white !important;
        font-weight: bold;
        border: none !important;
        text-align: center;
        width: 100% !important;
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
    #total-show, .subtotales {
        padding: 0px;
        font-weight: 400;
        background: none!important;
        /*font-size: 30px;
        color: #ffffff !important;*/
        font-size: 15px;
        color: #999 !important;
        text-shadow: 0px 0px 1px #ffffff;
        padding-top: 0px;
        line-height: 20px;
        font-weight: 600;
    }

    #total_show_peso, .total_show_peso{
        /*font-size: 35px !important;
        color: #ffffff !important;   */     
        font-size: 12px !important;
        color: #999 !important;               
        text-shadow: 0px 0px 1px #ffffff;
        line-height: 10px;
        /*font-weight: 600;*/
    }

    #btnGrandePagar,#btnGrandePagar2{
        cursor: pointer;
        padding: 5px;
        font-family: Roboto,sans-serif;
        /*margin: 10px;*/
        border: none;
        height: 40px;
        width:100% !important;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size:25px;
        color:#FFFFFF;
        border-radius: 3px; 
        text-align: center !important;
        /*background-color: #6dca42 !important;*/
        background-color: #5ca745 !important;
        /*box-shadow: 0 0px 4px 0 rgba(0, 0, 0, 0.1), 0 3px 8px 0 rgba(0, 0, 0, 0.20);*/

        -webkit-transition: background-color 100ms linear;
        -moz-transition: background-color 100ms linear;
        -o-transition: background-color 100ms linear;
        -ms-transition: background-color 100ms linear;
        transition: background-color 100ms linear;

    }

    .user-puntos-leal-selected {
        background-color: #5ca745 !important;
        color: #fff;
    }

    #searchPuntosLeal {
        cursor: pointer;
        height: 34px;
        width:100% !important;
        display: flex;
        justify-content: center;
        align-items: center;
        color:#FFFFFF;
        border-radius: 3px; 
        text-align: center !important;
        background-color: #5ca745 !important;
    }


    #pagarInfo,#iva-total,#subtotal,#sigPeso{
        font-weight: 600 !important;
        font-size: 12px !important;
    }
    #pagarInfo{
        font-size: 10px !important;
    }
    #sigPeso{
        margin-left: 8px;
        margin-right: 2px;
    }
    #sigPeso,#iva-total,#subtotal{
       /* font-weight: 500 !important;*/
        /*font-weight: 600 !important;*/
        font-size: 12px !important;
       
    }

    #listaProductos{
        background-color: #f9f9f9;
    }

    tr.nothing td{
        text-align: center;
        height:auto !important;
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
        background-color: #ffffff;
        padding-left: 12px;
        text-align: center;
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
        color: #5ca745;
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
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background: #5ca745 !important;
        width: 85px !important;
        height: 30px !important;
        padding: 4px 10px !important;
        font-size: 12px;
        font-weight: bold !important;

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


    #botones div.btn:hover, #div_contenedor_mesas_en_espera div.btn:hover{
    /*    background: #5E8C47 !important;*/
        background: #505050 !important;
    }


    #botones table{
        display:none;
    }
    .btn.funcLista{
        width: 80%;
        text-decoration: none;
        /*background: #6dca42 !important;*/
        background: #5ca745 !important;
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

    .codigoNotaCredito{
        width: 205px !important;
        margin-bottom: 5px !important;
        display:none !important;
        float: left;
    }
    .btnBuscarNotaCredito2{
        display:none !important;
        font-weight: 400 !important;
        font-size: 12px;
        background-color: #5bb133 !important;
        padding: 5px 5px;
        margin-left: 2px;
        margin-bottom: 2px !important;
        float: left;
    }
    .btnDatafono_vendty{
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
        margin-bottom: 5px;
    }

    .contCalc #Calc .span12 input{
        width: 177px;
        margin-top: 12px;
        border-radius: 3px;
    }

    .contCalc .popover{
        width: auto !important;
        padding: 10px !important;
        padding-left: 0px !important;
        padding-right: 14px !important;
    }
    .contCalc .btn{
        border: none;
        background: #505050 !important;
        width: 100% !important;
        border-radius: 30px;
    }
    .contCalc .btn:hover {
        /*background: #6E9E55 !important;*/
        background: #5ca745 !important;
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
        /*background-color: #6dca42 !important;*/
        background-color: #5ca745 !important;
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

    .headerComanda{
        font-weight: 400;
        font-size: 16px;
        border-bottom: #e4e4e4 1px solid;
        border-right: #e4e4e4 1px solid;
        border-left: #e4e4e4 1px solid;
        padding: 10px 15px;
        color: #272727;
        text-align: center;
        background-color: #fff;
    }
    .minH100{
        min-height: 100%;
        max-height: 100%;
        overflow-y: auto;
    }
    .minH90{
        min-height: 90%;
        max-height: 90%;
        overflow-y: auto;
    }

    #comandaL{
        width: 40%;
        float: left;
        border-right: #e8e8e8 1px solid;
        background-color: #f9f9f9;
    }
    #comandaR{
        padding-left: 10px;
        width: 60%;
        float: right;
        background-color: #fff;
        border-right: #e4e4e4 1px solid;
    }

    #comandaRCont{
        height: 100% !important;
        min-height: 100%;
        background-color: #f9f9f9;
        border-left: #e6e6e6 1px solid;
        border-right: #e2e2e2 1px solid;
        position: absolute;
        width: 350px;
    }

    #contComandaSides{
        top: 46px;
        position: absolute;
        bottom: 50px;
        left: 0;
        right: 0;

    }

    #comandaFooter{
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 50px;
        background-color: #fff;
        border-top: #e4e4e4 1px solid;
        border-right: #e4e4e4 1px solid;
        border-left: #e4e4e4 1px solid;
    }

    .comandaUsu{
        position: relative;
        color: #000000;
        font-weight: 400;
        padding: 5px 10px;
        padding-right: 20px;
        border-bottom: #e4e4e4 1px solid;
        border-top: #ffffff 1px solid;
        background-color: #f3f3f3;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .comandaUsu:hover{
        background-color: #efefef;
    }

    .comandaOk{
        width: 12px;
        height: 32px;
        position: absolute;
        right: 0;
        top: 0;
        background-color: #ffa02f;
        display: none;

    }
    .comUserActive .comandaOk{
        background-color: #ffb153;
    }

    .comandaVenH{
        color: #252525;
        font-weight: 400;
        padding: 5px 10px;
        border-bottom: #e4e4e4 1px solid;
        border-top: #ffffff 1px solid;
        background-color: #f3f3f3;
        text-align: center;
    }

    .btnCom{
        padding: 5px;
        padding-bottom: 2px;
    }

    .btnCom div.btn {
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
    .btnCom div.btn:hover{
        background: #5E8C47 !important;
    }

    #contComBtnAsignados,
    #contComBtnNoAsignados{
        padding: 5px;
    }
    #contComBtnAsignados{
        border-bottom: #e4e4e4 1px solid;
    }

    .btnComandaFact{
        padding: 1px 1px;
        height:45px;
        vertical-align: text-bottom;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .comUserActive,
    .comUserActive:hover{
        color: #ffffff;
        background-color: #62cb31;
    }

    .cantidadComandaAsig{
        padding: 3px 6px 2px 6px;
        background-color: #757575;
        color: #ffffff;
        line-height: 10px;
        margin-right: 10px;
        border-radius: 10px;
        font-size: 12px;
    }

    .comUserActive .cantidadComandaAsig{
        background-color: aliceblue;
        color: #545454;
    }

    #comanda.comandaAlert{
        /*background: #fd9107 !important;*/
       /* background: #505050 !important;*/
    }


    #promocion,#domiciliario{
        background-color: #fff !important;
        height: 30px;
        /*border-radius: 0px 3px 3px 0px;
        border: 1px solid #eaeaea;*/
        font-size: 13px;
        border: 1px solid #eaeaea;
        border-radius: 40px;
    }

    #tabledivision td {
        vertical-align: center;
    }

    #tabledivision input:invalid {
        border: 1px solid red;
    }

    #tabledivision input:valid {
        border: 1px solid green;
    }

    #tabledivision .input-group input{
        height: 23px !important;
        margin-top: 1px;
    }

    .btn-default {
        color: #333 !important;
        background-color: #fff !important;
        border-color: #ccc !important;
    }

    .ui-dialog .ui-dialog-buttonpane button {
        border: 1px solid #5ca745 !important;
        background: #5ca745 !important;
    }


    @media (max-width: 1024px){

        #pagarTitulo, #total_show_peso, .total_show_peso{
            font-size: 10px !important;
        }
        #total-show, .subtotales {
            font-size: 10px !important;
        }
    }

    .sinpadding [class*="col-"] {
        padding: 0;
    }
    .styleModalVendty.modal.fade {
        top: 0px !important;
    }

    .styleModalVendty.modal {
        border: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        //left: 0;
        //left: initial !important;
        z-index: 1700;
        width: 100% !important;
        margin: 0px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
        outline: 0;
        -webkit-overflow-scrolling: touch;
        margin: 0px !important;
        background-color: transparent !important;
        box-shadow: none;
    }
    .styleModalVendtyModal{
        width: 100%;
    }




    /* Inicio estilos de Clase para estabilizar slider modal*/

    .styleModalVendty.modal.fade.in {
        top: 0px !important;
    }
    .styleModalVendty.modal.fade {
        top: 0px !important;
    }

    .styleModalVendty.modal {
        border: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        //left: 0;
        //left: initial !important;
        z-index: 1700;
        width: 100% !important;
        margin: 0px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
        outline: 0;
        -webkit-overflow-scrolling: touch;
        margin: 0px !important;
        background-color: transparent !important;
        box-shadow: none;
    }
    .styleModalVendtyModal{
        width: 100%;
    }
    .iconfinal{
        /*color: #5eaf38 !important;*/
        color: #5ca745 !important;
        /*font-family: Helvetica Neue !important;*/
    }
    .colicon{
        padding-right: 5px !important;
        padding-left: 5px !important;
    }
     /* Fin estilos de Clase para estabilizar slider modal*/

     /*imei */
     .item-imei{font-weight:bold;}
     .producto_imei{
        border: solid 1px lightgray;
        border-radius: 5px 5px;
        margin-bottom: 5px;
        padding: 10px;
        box-sizing: border-box;
        display:none;
     }

     .btn-close-imei{
        background-color: #5ca745 !important;
        color: #fff;
        padding: 6px;
        box-sizing: border-box;
        border: none;
        border: solid 1px;
        border-radius: 5px 5px;
        float: right;
        margin-bottom: 10px;
        text-align: center;
        clear:both;
     }

     .imei-title{
         font-size:10px;
         margin-left:2px;
     }


     #plan-separe-form label{color: #333;font-weight: bold;font-size: 14px;margin-bottom: 8px;}
     #plan-separe-form #plan-separe-form input,#plan-separe-form select,#plan-separe-form textarea{font-size:14px;}
     #plan-separe-form textarea{ border: solid 1px lightgray;}
     .switch input { 
        display:none;
    }
    .switch {
        display:inline-block;
        width:50px;
        height:20px;
        margin:8px;
        transform:translateY(50%);
        position:relative;
        float: right;
        margin-top: -5px;
        margin-right: 0;
    }

    .slider {
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        right:0;
        border-radius:30px;
        box-shadow:0 0 0 2px #777, 0 0 4px #777;
        cursor:pointer;
        border:4px solid transparent;
        overflow:hidden;
        transition:.4s;
    }
    .slider:before {
        position:absolute;
        content:"";
        width:100%;
        height:100%;
        background:#777;
        border-radius:30px;
        transform:translateX(-29px);
        transition:.4s;
    }

    input#facturacion-electronica-check:checked + .slider:before {
        transform:translateX(29px);
        background:limeGreen;
    }
    input#facturacion-electronica-check:checked + .slider {
        box-shadow:0 0 0 2px limeGreen,0 0 2px limeGreen;
        background: white !important;
    }

</style>
<?php
    $impuestopredeterminado=$data['impuesto']->porciento;
?>


 <?php
    if(!empty($this->session->flashdata('venta_impresion_rapida')) && $this->session->flashdata('venta_impresion_rapida') != ""){ ?>
        <div class="alert alert-success text-center success-impresion-rapida">
            <?php echo $this->session->flashdata('venta_impresion_rapida'); ?>
        </div>  
<?php } ?>

<div id="backPopUp" class="popap" style="display: none;">
    <div style="display: flex; width: 100%; height: 100%; align-items: center; justify-items: center; justify-content: center">
        <?php
        $permisos = $this->session->userdata('permisos');

        $is_admin = $this->session->userdata('is_admin');
        ?>

        <div id="modalEfectuarPago" class="animatePanel">

            <form id="client-form">

                <div class="container">

                    <div class="row" style="padding: 10px !important;">
                        <div class="col-md-12" style="padding: 10px 10px; background-color: rgb(59,59,59); color: white; font-weight: bold; text-align: center; border-radius: 10px">
                            Información de forma de pago
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            Subtotal
                        </div>
                        <div class="col-md-8">
                            <input type='hidden' name='valor_pagar_hidden' id='valor_pagar_hidden' style="text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 25px; font-weight: bold"/>
                            <input type="hidden" name="descuento_general" id="descuento_general" value="0" style="text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 25px; font-weight: bold"/>
                            <input type="text" disabled='disabled' name="valor_pagar" id="valor_pagar" style="text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 25px; font-weight: bold"/>
                            <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px; text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 25px; font-weight: bold"/>
                        </div>
                    </div>

                    <?php if ($data['sobrecosto'] == 'si') { ?>

                        <?php if ($data['nit'] == '320001127839') { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Propina</label>
                                    <input type="hidden" id="propina_input">
                                    <div id="propina_output"></div>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>

                            <hr style="margin-top: 5px"/>

                            <div class="row">
                                <div class="col-md-4">
                                    Propina
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" id="propina_input_pro">
                                    <div id="propina_output_pro" style="text-align: right; font-size: 14px"></div>
                                </div>
                            </div>

                            <hr style="margin-top: 5px"/>

                            <div class="row">
                                <div class="col-md-4">
                                    Total a pagar con propina
                                </div>
                                <div class="col-md-8">
                                    <div id="valor_pagar_propina" style="font-weight: bold; color: #00CC00; text-align: right; font-size: 35px"></div>
                                </div>
                            </div>

                        <?php } ?>

                    <?php } ?>

                    <hr style="margin-top: 5px"/>

                    <div class="row">
                        <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>                           
                        <?php }else{ ?>
                        <div class="col-md-6">                                                  
                            <button type="button" class="btn btn-success" title="Descuento al valor total de venta" id="descuento_general_pro"  onClick="descuento_general_propover('mostrar');" style="width: 100%; padding: 5px !important;">Descuento</button>                            
                        </div>
                        <?php } ?>
                        <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success" onClick="mostrar();" style="width: 100%; padding: 5px !important;">Agregar Forma de Pago</button>
                        </div>
                        <?php } ?>
                    </div>

                    <hr style="margin-top: 5px"/>

                    <?php if ($data['multiples_formas_pago'] != 'si') { ?>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="col-md-3"><?php echo custom_lang('sima_reason', " Forma de pago"); ?>:</div>
                                <div class="col-md-3">
                                    <select name="forma_pago" id="forma_pago">
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>
                                <div class="col-md-3">
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado"/>
                                    <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>
                                    <input type="hidden" name="valor_entregado" id="valor_entregado1" value="0" />
                                    <input type="hidden" name="valor_entregado" id="valor_entregado2" value="0" />
                                    <input type="hidden" name="valor_entregado" id="valor_entregado3" value="0" />
                                    <input type="hidden" name="valor_entregado" id="valor_entregado4" value="0" />
                                    <input type="hidden" name="valor_entregado" id="valor_entregado5" value="0" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row datafono" style="display:none">
                                    <div class="col-md-3">
                                        <label>Subtotal</label>
                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Iva</label>
                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Impuesto</label>
                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Nro. Transacci&oacute;n</label>
                                        <input type="text" name="transaccion" id="transaccion" value="" style="text-align:right">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php } ?>

                    <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                        <div id="contenido_a_mostrar">
                            <div class="row">

                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago">
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6">

                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift" index="" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb" href="javascript:void(0);" class="btn btnBuscarGift2" index=""><span class="icon glyphicon glyphicon-search"></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito" index="" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index=""><span class="icon glyphicon glyphicon-search"></span></a>
                                    <input type="text" class="dataMoneda1" name="valor_entregado" id="valor_entregado"/>
                                    <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>
                                    <input class="codigoNotaCredito" type="text" name="valor_datafono_vendty" id="valor_datafono_vendty" index="" value="" placeholder=" Valor Datafono Vendty"/>
                                    <a class="btn btnDatafono_vendty" id="valor_datafono_vendtyb" onclick="set_iframe_url()" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal">
                                        <span class="icon glyphicon glyphicon-th" style=""></span>
                                    </a>
                                </div>

                            </div>

                            <div class="row datafono" style="display:none">
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion" value="" style="text-align:right">
                                </div>
                            </div>
                        </div>

                        <div id="contenido_a_mostrar1">
                            <hr style="margin-top: 5px"/>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago1">
                                        <option value="" data-tipo="">Seleccione</option>
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-5" >
                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift1" index="1" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb1" href="javascript:void(0);" class="btn btnBuscarGift2" index="1"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito1" index="1" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="1"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado1" value="0"/>
                                </div>
                                <div class="col-md-1">
                                    <a style='cursor: pointer;' data-id="1" class="eliminar_forma_pago" title=""><i class="glyphicon glyphicon-trash" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i> </a>                                    
                                </div>
                            </div>
                            <div class="row datafono" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion1" value="" style="text-align:right">
                                </div>
                            </div>
                        </div>


                        <div id="contenido_a_mostrar2">
                            <hr style="margin-top: 5px"/>
                            <div class="row">

                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago2">
                                        <option value="" data-tipo="">Seleccione</option>
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift2" index="2" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb2" href="javascript:void(0);" class="btn btnBuscarGift2" index="2"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito2" index="2" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="2"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado2" value="0"/>
                                </div>

                                <div class="col-md-1" style="display: flex; align-items: center; justify-items: center">
                                    <a style='cursor: pointer;' data-id="2" class="eliminar_forma_pago" title=""><i class="glyphicon glyphicon-trash" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i> </a>
                                </div>

                            </div>

                            <div class="row datafono" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion2" value="" style="text-align:right">
                                </div>
                            </div>
                        </div>


                        <div id="contenido_a_mostrar3">
                            <hr style="margin-top: 5px"/>
                            <div class="row">

                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago3">
                                        <option value="" data-tipo="">Seleccione</option>
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift3" index="3" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb3" href="javascript:void(0);" class="btn btnBuscarGift2" index="3"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito3" index="3" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="3"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado3" value="0"/>
                                </div>

                                <div class="col-md-1" style="display: flex; align-items: center; justify-items: center">
                                    <a style='cursor: pointer;' data-id="3" class="eliminar_forma_pago" title=""><i class="glyphicon glyphicon-trash" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i> </a>
                                </div>

                            </div>

                            <div class="row datafono" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion3" value="" style="text-align:right">
                                </div>
                            </div>
                        </div>


                        <div id="contenido_a_mostrar4">
                            <hr style="margin-top: 5px"/>
                            <div class="row">

                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago4">
                                        <option value="" data-tipo="">Seleccione</option>
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift4" index="4" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb4" href="javascript:void(0);" class="btn btnBuscarGift2" index="4"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito4" index="4" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob4" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="4"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado4" value="0" />
                                </div>

                                <div class="col-md-1" style="display: flex; align-items: center; justify-items: center">
                                    <a style='cursor: pointer;' data-id="4" class="eliminar_forma_pago" title=""><i class="glyphicon glyphicon-trash" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i> </a>
                                </div>

                            </div>

                            <div class="row datafono" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion4" value="" style="text-align:right">
                                </div>
                            </div>
                        </div>


                        <div id="contenido_a_mostrar5">
                            <hr style="margin-top: 5px"/>
                            <div class="row">

                                <div class="col-md-6">
                                    <select name="forma_pago" id="forma_pago5">
                                        <option value="" data-tipo="">Seleccione</option>
                                        <?php
                                        foreach ($data['forma_pago'] as $f) {
                                            ?>
                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift5" index="5" value="" placeholder=" C&oacute;digo GiftCard"/>
                                    <a id="valor_entregado_giftb5" href="javascript:void(0);" class="btn btnBuscarGift2" index="5"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito5" index="5" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                    <a id="valor_entregado_nota_creditob5" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="5"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                    <input class="dataMoneda1" type="text" name="valor_entregado" id="valor_entregado5"  value="0"/>
                                </div>

                                <div class="col-md-1" style="display: flex; align-items: center; justify-items: center">
                                    <a style='cursor: pointer;' data-id="5" class="eliminar_forma_pago" title=""><i class="glyphicon glyphicon-trash" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i> </a>
                                </div>

                            </div>

                            <div class="row datafono" style="display:none">
                                <hr style="margin-top: 5px"/>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Iva</label>
                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Impuesto</label>
                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                </div>
                                <div class="col-md-3">
                                    <label>Nro. Transacci&oacute;n</label>
                                    <input type="text" name="transaccion" id="transaccion5" value="" style="text-align:right">
                                </div>
                            </div>

                        </div>


                    <?php } ?>

                    <div id="row-fecha-vencimiento" class="row-form" style="display:none">

                        <div class="col-md-6 text-right"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>

                        <div class="col-md-6">
                          
                           <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" readonly/>
                          
                        </div>

                    </div>

                    <hr style="margin-top: 5px"/>

                    <div class="row">

                        <div class="col-md-4" id="labelCambioFalta">
                            <?php echo custom_lang('sima_cambio', "Cambio"); ?>
                        </div>
                        <div class="col-md-8">
                            <input type='hidden' name='sima_cambio_hidden' id='sima_cambio_hidden' style="text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 20px; font-weight: bold"/>
                            <input type="text" disabled='disabled' name="sima_cambio" id="sima_cambio" style="text-align: right; background-color: transparent !important; border: none !important; padding: 0px !important; font-size: 20px; font-weight: bold"/>
                        </div>

                    
                    </div>

                    <?php if($data["facturacion_electronica"]) { ?>
                    <div class="row">

                        <div class="col-md-8" id="facturacion-electronica">
                            <?php echo custom_lang('sima_facturacion_electronica', "Facturación Electrónica"); ?>
                        </div>
                        <div class="col-md-4 float-right">
                            <label class="switch">
                                <input type="text" name="facturacion-electronica" id="facturacion-electronica-check">
                                <span class="slider"></span>
                            </label>
                        </div>


                    </div>

                    <?php } ?>

                    <hr style="margin-top: 5px"/>

                    <div class="row" style="margin-bottom: 20px !important;">
                        <div class="col-md-4">
                            <input type="button" value="Cancelar"  id="cancelar" class="btn btn-default" style="padding: 20px !important;"/>
                        </div>
                        <input type="hidden" name="venta_sin_pago" id="venta_sin_pago" value="0"/>
                        <?php if(($data['tipo_negocio']=="restaurante") &&($data['permitir_formas_pago_pendiente']=="si")){ ?>
                        <div class="col-md-4" align="center">
                            <input type="button" value="Formas Pago Pendiente"  id="grabar_sin_pago" class="btn btn-success" style="padding: 20px !important;"/>
                        </div>
                        <div class="col-md-4" align="right">
                            <input type="button" value="Aceptar"  id="grabar" class="btn btn-success" style="padding: 20px !important;"/>
                        </div>
                        <?php }else{ ?>
                            <div class="col-md-4 pull-right" style="text-align: right;">
                                <input type="button" value="Aceptar"  id="grabar" class="btn btn-success" style="padding: 20px !important;"/>
                            </div>
                        <?php } ?>
                        
                    </div>
                  
                </div>
            </form>

        </div>
    </div>

</div>

<div style="position: inherit; z-index: 10; width: 100%; height: 100vh; padding: 0px; margin: 0px !important; overflow-x: hidden !important; overflow-y: auto !important; background-color: #FFF;">

    <input type="hidden" id="subtotal_input">
    <input type="hidden" id="subtotal_propina_input">
    <input type="hidden" id="aleatorio" value="<?php echo $data['aleatorio']?>">

    <div class="col-md-8 col-sm-12 col-xs-12 topMargin" style="height: 100% !important;">

        <div class="fill-height-or-more" style="width: 100%; height: 100% !important;">

            <div class="row" style="padding-bottom: 1px !important; flex: 1">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="row" style="position: inherit; height: 100% !important; display: flex; justify-content: center; align-items: center;">

                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4" style="bottom: 0px; height: 100% !important; margin-left: 10px; padding-top: 8px;">

                            <div class="row" style="display: flex; align-items: center; justify-content: center">

                                <div class="col-lg-6 col-md-6 col-sm-5 col-xs-5 btnTypeSearch" data-toggle="tooltip" data-placement="bottom" title="Buscar Código de Barra" style="display: flex; align-items: center; justify-content: center">
                                    <div onclick="setTypeSearch(1)" style="min-width: 50px; min-height: 40px !important; display: flex; align-items: center; justify-content: center; cursor: pointer !important;" id="forBarCode">
                                        <i class="glyphicon glyphicon-barcode"></i>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-5 col-xs-5 btnTypeSearch" data-toggle="tooltip" data-placement="bottom" title="Buscar por Nombre" style="display: flex; align-items: center; justify-content: center">
                                    <div onclick="setTypeSearch(2)" style="cursor: pointer; min-width: 50px !important; min-height: 40px !important; display: flex; align-items: center; justify-content: center;" id="forNameProduct" class="activeB">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-8">
                            <div class="input-group" style="padding-left: 0px !important; margin-left: -15px !important;">
                                <input type="text" name="text" class="span12 form-control" style="height: 40px !important;" placeholder="Digite producto a buscar..." id="search" autofocus="autofocus" onkeypress="isAlphaNumeric()"/>

                                <span class="input-group-addon" id="basic-addon1">
                                <div>
                                    <i class="glyphicon glyphicon-search"></i>
                                </div>
                            </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!--CATEGORIAS-->
            <div id="slickCategories" class="row" style="flex: 1.5; ">
                <div class="col-md-12">
                    <div class="multiple-items1">
                            <div>
                                <a class="category-option" data-id="0" id="0" onclick="filtrarCategoria(this); selectCategory(0)">
                                    <img style="width: 50px; height: 50px;" src="https://vendty-img.s3-us-west-2.amazonaws.com/default.png" alt="0">
                                    <p>Todos</p>
                                </a>
                            </div>
                        <?php
                        foreach($data['categorias'] as $categoria):

                            if(!empty($categoria->imagen)){
                                if(file_exists('uploads/'.$categoria->imagen)):
                                    $nombre=base_url().'uploads/'.$categoria->imagen;
                                else:
                                    $nombre=base_url().'uploads/'.$this->session->userdata('base_dato').'/categorias_productos/'.$categoria->imagen;
                                endif;
                            }else{
                                $nombre="https://vendty-img.s3-us-west-2.amazonaws.com/default.png";
                            }
                            ?>
                            <div>
                                <a class="category-option" data-id="<?php echo $categoria->id; ?>" id="<?php echo $categoria->id; ?>" onclick="filtrarCategoria(this); selectCategory(<?php echo $categoria->id; ?>)">
                                    <img style="width: 50px; height: 50px;" src="<?= $nombre;?>" alt="<?= $categoria->id; ?>">
                                    <p><?php echo $categoria->nombre; ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!--List Productos-->
            <div id="vitrinaProductos" class="row bodyMobile" style="flex: 10;">
                <div class="col-md-12">
                    <div class="row">
                        <div id="vitrina" style="background-color: transparent; display: flex; align-items: center; justify-content: center; overflow-y: scroll !important; margin-top: 3%; max-height: 70vh !important;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="flex: 1; padding: 0px; margin-top: 0px !important; padding-top: 0px !important; display: none" id="searchPaginator">
                <div class="col-md-12">
                    <div id='buscalo-controles'>
                        <div class="dataTables_info"></div>
                        <div id="DataTables_Table_2_paginate" class="dataTables_paginate paging_full_numbers">
                        </div>
                    </div>
                </div>
            </div>

            <!--LISTADO DE RESULTADOS del Input  Buscador-->
            <div class="row bodyMobile" style="flex: 10; display: none" id="productsSearchInput">
                <div class="col-md-12">
                    <table width="100%" id="facturasTable">
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="row" style="flex: 0.6;z-index: 1;">
                <div class="col-md-12" style="position: inherit;overflow: hidden;overflow-y: scroll;">

                    <div id="botones"></div>
                    <div id="div_contenedor_mesas_en_espera"> </div>  
                </div>

                <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px;"/>
                <input type="hidden" name="id_lista" id="id_lista" value="0" style="width: 200px;height: 25px;"/>
                <input type="hidden" name="id_fact_espera_nombre" id="id_fact_espera_nombre" style="width: 260px;height: 25px;"/>

            </div>
        </div>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-12 tableroproductos">
        <div class="col-md-12 col-sm-12 col-xs-12 subtableroproductos">
            <div class="fill-height-or-more" style="width: 100%; height: 100% !important">
                <div class="row specificMobile-10 formclientes">
                    <div class="col-md-6 col-sm-6 col-xs-12" id="formLeft" style="border: none !important;">                                                       
                        <div class="input-group" style="padding-left: 0px !important;">
                            <input type="text" class="span12 form-control" placeholder="Cliente" value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente" id="datos_cliente"/>
                            <span class="input-group-addon" id="add-new-client">
                                <div><span class="icon ico-plus vender"></span></div>
                            </span>
                        </div>
                        <div class="row" id="promociones" data-fetch="<?= site_url('promociones') ?>" style="display:none">                                
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div style="padding-left: 0px !important; padding-top: 8px" >
                                    <select name="promocion" id="promocion"></select>                                        
                                </div>
                            </div>
                        </div>                        
                        <div id="clienteCartera"></div>
                        <!-- Clientes -->
                        <!-- ?? -->
                        <div id="clienteBlock">
                            <div class="row-form">
                                <div class="span6">
                                    <div class="input-prepend input-append">
                                        <div id="contenedor-lista-clientes"><ul id="lista-clientes"></ul></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        
                        <!-- ?? -->
                        <div class="row-form" id="vendedorBlock">
                            <div class="hidden span3">Nombre:</div>
                            <div class="span9">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <!-- Vendedor 1 -->                        
                        <div class="input-group" style="padding-left: 0px !important">
                            <input type="text" class="span12 form-control" placeholder="Vendedor" value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor" id="datos_vendedor"/>
                            <span class="input-group-addon" >                                                
                                <span class="icon"><img alt="vendedor" src="<?php echo $this->session->userdata('new_imagenes')['vendedor_verde']['original'] ?>" /></span>                                              
                            </span>
                            <input type="hidden" name="vendedor" id="vendedor"/>
                        </div>
                        <!-- Vendedor 2 -->
                        <div class="row" style="<?= $data['multiples_vendedores'] == '0' ? 'display:none;' : ''; ?> ">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group" style="padding-left: 0px !important;">
                                    <input type="text" class="span12 form-control" placeholder="Vendedor 2" value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor_2" id="datos_vendedor_2"/>
                                    <span class="input-group-addon">
                                        <span class="icon"><img alt="vendedor" src="<?php echo $this->session->userdata('new_imagenes')['vendedor_verde']['original'] ?>" /></span>
                                    </span>
                                    <input type="hidden" name="vendedor_2" id="vendedor_2"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
                
                <div class="row specificMobile-4" style="flex: 0.20;">
                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding: 0px !important;">
                        <table id="tablaListaProductos" cellpadding="0" cellspacing="0" width="100%">
                            <thead style="font-weight: bold; color: black">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="40%" style="text-align: left !important;">
                                        Productos ( <span id="cantidad-total">0</span> )
                                    </td>
                                    <td width="10%">
                                        Cant.
                                    </td>
                                    <td width="20%">
                                        Total
                                    </td>
                                    <td width="20%">
                                        Subtotal
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="row specificMobile-4 tabla_productos">                            
                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding: 0px !important;">
                        <table id="tablaListaProductos" cellpadding="0" cellspacing="0" width="100%">
                            <!--<thead style="font-weight: bold; color: black">
                                <tr>
                                    <td width="10%">

                                    </td>
                                    <td width="40%" style="text-align: left !important;">
                                        
                                    </td>
                                    <td width="10%">
                                        
                                    </td>
                                    <td width="20%">
                                        
                                    </td>
                                    <td width="20%">
                                        
                                    </td>
                                </tr>
                            </thead>-->
                            <tbody id="productos-detail">
                                <tr class="nothing" style="text-align: center">
                                    <td colspan="5">No existen productos</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                
                <div class="row specificMobile-3 panel_subbtn" >
                    <?php 
                        $zona = $this->uri->segment(3);
                        $mesa=$this->uri->segment(4);
                    ?>
                    <div class="col-md-12">
                        <div class="row">                             
                            <div class="responsive">   
                                <?php if (($data['tipo_negocio'] == "moda" || $data['tipo_negocio'] == "retail" || $data['tipo_negocio'] == "")|| ($data['plan_separe'])) { ?>
                                    <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                        <a id="planSepare" class="iconfinal" title="Plan Separe" style="text-decoration: none">       
                                            <div class="icon">
                                                <img title="Plan Separe" alt="Plan Separe" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['vender_plan_separe']['original'] ?>">
                                            </div>
                                            <div class="letra_btn_subpanel">Separar</div>
                                        </a>                                   
                                    </div>
                                <?php } ?>
                                
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">
                                    <a id="nota" class="iconfinal funcLista" title="Nota" style="text-decoration: none">                                                                              
                                        <div class="icon">
                                            <img title="Nota" alt="Nota" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['nota_verde']['original'] ?>">  
                                        </div>                                                                                    
                                        <div class="letra_btn_subpanel">Nota</div>
                                    </a>
                                </div>

                                <?php if ((empty($zona))&&(empty($mesa))) { $activo=1; ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">
                                    <a id="domicilio" class="iconfinal funcLista" title="Domicilio" style="text-decoration: none">  
                                        <div class="icon">
                                            <img title="Domicilio" alt="Domicilio" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['domicilio_verde']['original'] ?>">
                                        </div>                                         
                                        <div class="letra_btn_subpanel">Domicilio</div>
                                    </a>
                                </div>
                                <?php } ?>

                                <?php if ($data['sobrecosto'] == 'si') { ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                    <a id="sobrecosto" class="iconfinal" title="Propina" style="text-decoration: none">                                        
                                        <div class="icon">
                                            <img title="Propina" alt="Propina" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['venta_propina']['original'] ?>">                                            
                                        </div>
                                        <div class="letra_btn_subpanel">Propina</div>
                                    </a>                                   
                                </div>
                                 <?php } ?>

                                <?php if ((empty($zona))&&(empty($mesa)) && ($data['factura_con_mesas'] == 'si')) { $activo=1; ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                    <a onclick="cargar_modal_mesas()" class="iconfinal" title="Mesas" style="text-decoration: none"> 
                                        <div class="icon">
                                            <img title="Mesas" alt="Mesas" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['mesas_blanca']['original'] ?>">                                            
                                        </div>
                                        <div class="letra_btn_subpanel">Mesas</div>
                                    </a>                                   
                                </div>
                                <?php } ?>   

                                <?php if (($data['tipo_negocio'] == "restaurante") && (!empty($zona))&&(!empty($mesa))) { ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel" >                                    
                                    <a id="modal-division-cuenta1"  class="iconfinal" title="División Cuenta" style="text-decoration: none">  
                                        <div class="icon">
                                            <img title="División Cuenta" alt="División Cuenta" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['division_cuenta']['original'] ?>">                   
                                        </div>
                                        <div class="letra_btn_subpanel">División</div>
                                    </a>                                   
                                </div>
                                <?php } ?> 
                                                                                  
                                <?php if ($data['imprimir_comanda']) { ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                    <a id="imprimirOrden" href="javascript:imprimirOrden();" class="iconfinal" title="Imprimir orden" style="text-decoration: none">               
                                        <div class="icon">
                                            <img title="Imprimir orden" alt="Imprimir orden" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['vender_orden_comanda']['original'] ?>">
                                        </div>
                                        <div class="letra_btn_subpanel">Orden</div>
                                    </a>                                   
                                </div>
                                <?php } ?>

                                <!--
                                <?php // if ($data['comanda'] == 'si') { ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                    <a id="comanda" class="iconfinal" title="Comanda" style="text-decoration: none">               
                                        <div class="icon">
                                            <img title="Comanda" alt="Comanda" class="iconimgventas" src="<?php // echo $this->session->userdata('new_imagenes')['vender_comanda']['original'] ?>">
                                        </div>
                                        <div class="letra_btn_subpanel">Comanda</div>
                                    </a>                                   
                                </div>
                                <?php // } ?>
                                -->
                                <?php if (get_option('puntos_leal') == "si") { ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 btn_subpanel">                                    
                                    <a id="puntos_leal" class="iconfinal" title="Puntos Leal" style="text-decoration: none">                                        
                                        <div class="icon" style="height: 26px; width: 50px;">
                                            <img title="Puntos Leal" alt="Puntos Leal" class="iconimgventas" src="<?php echo $this->session->userdata('new_imagenes')['puntos_leal']['original'] ?>">                                            
                                        </div>
                                        <div class="letra_btn_subpanel">Puntos Leal</div>
                                    </a>                                   
                                </div>
                                 <?php } ?>                           
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row specificMobile-2" style="flex: 0.60; background-color: #fff;">
                    <div> 
                        <table class="table subtotales" width="100%">
                            <thead>
                                <tr>
                                    <td width="50%" class="tdtitulos"><b class="titulo_subtotales">SUBTOTAL</b></td>
                                    <td width="50%" class="tdsubtotales"><b class="titulo_subtotales"><span class="total_show_peso" ><?php $simbolo=(!empty($data['simbolo']))? $data['simbolo'] : '$ '; echo $simbolo.' '; ?></span><span class="label label-info subtotales" id="subtotal" > 0.00</span></b></td>
                                </tr>
                                <tr>
                                    <td width="50%" class="tdtitulos"><b class="titulo_subtotales">IVA</b></td>
                                    <td width="50%" class="tdsubtotales"><b class="titulo_subtotales"><span class="total_show_peso" ><?php $simbolo=(!empty($data['simbolo']))? $data['simbolo'] : '$ '; echo $simbolo.' '; ?></span><span class="label label-info subtotales" id="iva-total"> 0.00</span></b></td>
                                </tr>
                                <!--<tr>
                                    <td width="50%" class="tdtitulos"><b class="titulo_subtotales">DESCUENTO</b></td>
                                    <td width="50%" class="tdsubtotales"><b class="titulo_subtotales"><span class="total_show_peso" ><?php $simbolo=(!empty($data['simbolo']))? $data['simbolo'] : '$ '; echo $simbolo.' '; ?></span><span class="label label-info subtotales" id="iva-total"> 0.00</span></b></td>
                                </tr>-->
                                <tr>
                                    <td width="50%" class="tdtitulos"><b class="titulo_subtotales" style="font-size: 12px !important;">TOTAL</b></td>
                                    <td width="50%" class="tdsubtotales"><b class="titulo_subtotales"><span class="total_show_peso" style="font-size: 20px !important; font-weight:600 !important; color:#505050 !important"><?php $simbolo=(!empty($data['simbolo']))? $data['simbolo'] : '$ '; echo $simbolo.' '; ?></span><span class="label label-info subtotales" id="total-show" style="font-size: 20px !important; font-weight:600; color:#505050 !important"> 0.00</span></b></td>
                                </tr>
                            </thead>
                        </table>
                    </div>                    
                </div>
              
                <div class="row specificMobile-2" style="flex: 0.35; background-color: #fff;">
                    <div class="col-md-12">
                        <div class="col-md-3 col-xs-3">                        
                            <?php if ((empty($zona))&&(empty($mesa))) { $activo=1; ?>                                                                                                                                                               
                                <button id="cancelarVenta" class="btn_subpanel_pagar icon letra_btn_subpanel" style="border: 0 !important; background-color: #e62626; color: #fff !important;" type="button">Cancelar</button>                                                                   
                            <?php } ?>
                        </div> 
                        <div class="col-md-3 col-xs-3">
                            <?php if ((empty($zona))&&(empty($mesa))) { ?>                                
                                <button id="pendiente" class="btn_subpanel_pagar icon letra_btn_subpanel" style="border: 0 !important;" type="button">Espera</button>                                                                    
                            <?php } ?>
                        </div> 
                        <div class="col-md-6 col-xs-6">
                            <div id="btnGrandePagar">PAGAR<input type="hidden" value="0" id="total"/></div> 
                        </div> 
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!--  modal imei -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-imei" style="width: 51%; bottom:initial; top:10%; margin: 0 auto;z-index:50;">
    <div class="container" style="margin-top:8px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" style="color:#5ca745;">PRODUCTO TIPO SERIAL/IMEI</h4>
        <hr>

        <div class="form-group">
            <label for="codigo_barra_imei" style="margin-top:10px;">A CONTINUACIÓN INGRESE SU SERIAL/IMEI (CODIGO DE BARRA) </label>
            <input style="heigth:30px;" type="text" class="form-control" id="codigo_barra_imei" autofocus="autofocus" placeholder="Ingresa aqui tu código de barras">
        </div>

        <div class="resultado_imei">
            <p>Sin resultados aun</p>
            <div class="col-md-6 col-md-offset-3 text-center producto_imei">
                <img class="imagen-producto-imei" src="" alt="" style="margin-bottom:15px;">
                <div><span class="nombre-producto-imei"></span></div>
                <div><span class="codigo-producto-imei"></span></div>
                <div><span class="codigo-imei"></span></div>
                <div><span class="stock-imei"></span></div>
                <div><span class="precio-venta-imei" style="font-size: 22px;color: #5ca745;margin-bottom: 10px; "></span></div>
            </div>
        </div>

        <button class="btn-close-imei" data-dismiss="modal" aria-label="Close">CERRAR VENTANA</button>
    </div>
</div>
<!-- fin modal imei -->

<div id="dialog-domicilio-form"  title="Domicilio">

    <form id="client-form" style="overflow: hidden !important;">

        <div class="row">
        <!--
            <div class="col-md-12">
                <div id="domiciliarios" data-fetch="<?= site_url('domiciliarios') ?>">        
                    <div style="padding-left: 0px !important; padding-top: 8px" >
                        <select name="domiciliario" id="domiciliario" required>
                            <option value="">Seleccione Domiciliario</option>
                                <?php foreach ($data['domiciliarios'] as $key => $value) {
                                   echo'<option value="'.$value['id'].'">'.$value['descripcion'].'</option>';
                                }?>
                            
                        </select>                                        
                    </div>                    
                </div>                 
            </div>
          
            <div class="col-md-12">
                <div class="input-group" style="padding-left: 0px !important;">                    
                    <input type="hidden" value="" name="id_cliente_domicilio" id="id_cliente_domicilio"/>
                    <input type="hidden" value="no" name="presione_domicilio" id="presione_domicilio"/>
                    <input list="cliente_domicilio" autocomplete=off type="text" data-id="0" class="span10 form-control" required placeholder="Cliente" value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente_domicilio" id="datos_cliente_domicilio" />
                    <span class="input-group-addon" id="add-new-client">
                        <div><span class="icon ico-plus vender"></span></div>
                    </span>
                </div>            
            </div>-->

            <div class="col-md-12">
                <div id="domiciliarios">        
                    <?php foreach ($data['domiciliarios'] as $key => $value) { 
                        if(!empty($value['logo'])){
                            $logodomi= base_url('/uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$value['logo']);
                        }else{
                            $logodomi="https://vendty-img.s3-us-west-2.amazonaws.com/default.png";
                        }
                        
                        ?>
                        <div class="col-md-2 domiciliarios-option text-center" data-id="<?= $value['id']; ?>" id="<?= $value['id']; ?>">
                            <div class="cuadro_domiciliarios">
                                <img class="imgdomi" alt="<?= $value['descripcion'] ?>" style="max-height: 68px;" src="<?= $logodomi; ?>" />
                            </div>
                            <span><?php echo $value['descripcion']; ?></span>
                        </div>
                    <?php }?>          
                </div>                 
            </div>
            
            <div class="col-md-12" style="margin-top:3% border-top: 1px solid #f5f5f5;">
                <div class="col-md-4">
                   <label>Cliente</label>
                </div>  
                <div class="col-md-4">
                    <label>Teléfono</label>
                </div> 
                <div class="col-md-4">
                   <label>Dirección</label>
                </div>                           
            </div>

            <div class="col-md-12" >
                <div class="col-md-4">
                    <div class="input-group" style="padding-left: 0px !important; margin-top: 0px !important; height: 34px; !important;">                    
                        <input type="hidden" value="" name="domiciliario" id="domiciliario"/>
                        <input type="hidden" value="" name="id_cliente_domicilio" id="id_cliente_domicilio"/>
                        <input type="hidden" value="no" name="presione_domicilio" id="presione_domicilio"/>
                        <input list="cliente_domicilio" style="height: 34px; !important;" autocomplete=off type="text" data-id="0" class="form-control" required placeholder="Cliente" value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente_domicilio" id="datos_cliente_domicilio" />
                        <span class="input-group-addon" id="add-new-client" data-id="0">
                            <div><span id="icocambiar" class="icon ico-plus vender"></span></div>
                        </span>
                    </div>  
                </div>  
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Teléfono" value="" required name="telefono_domicilio" id="telefono_domicilio"/> 
                    <label hidden id="telefono_domicilio_label" ></label>
                </div> 
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="direccion" value="" required name="direccion_domicilio" id="direccion_domicilio"/>                
                    <label hidden id="direccion_domicilio_label" ></label>
                </div>                           
            </div>

            <div class="col-md-12">
                <div id="cliente_domicilio">
                    <div class="row-form">
                        <div id="contenedor-lista-clientes-domicilios">
                            <ul id="lista-clientes-domicilios">                               
                            </ul>
                        </div>
                    </div>
                </div> 
            </div> 
                                            
           <!--
            <div class="col-md-12" style="margin-top:2%">                                
                <input type="text" class="span12 form-control" placeholder="Teléfono" value="" required name="telefono_domicilio" id="telefono_domicilio"/>                
            </div>     

            <div class="col-md-12" style="margin-top:2%"><?php echo custom_lang('sima_pay_value', "Direccion"); ?>:</div>
            <div class="col-md-12">
                <textarea rows="9" id="direccion_domicilio" required name="direccion_domicilio" cols="60"></textarea>
            </div>-->

        </div>

    </form>

</div>

<div id="dialog-puntos-leal-form"  title="Puntos Leal">
    <form id="client-form" style="overflow: hidden !important;">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12">
                    <label>Documento o Celular</label>                          
                </div>
                <div class="col-xs-8">
                    <input type="text" class="form-control" placeholder="Documento o Celular" id="id_puntos_leal"/> 
                </div>
                <div class="col-xs-4">
                    <div id="searchPuntosLeal">Buscar</div>
                </div>
            </div>
            <div id="selectedPuntosLeal" class="col-xs-12" style="margin-top:2%; border-top: 1px #CCC solid; height: 50px"></div>
            <div id="listPuntosLeal" class="col-xs-12" style="border-top: 1px #CCC solid;"></div>
        </div>
    </form>

</div>

<div id="dialog-nota-form"  title="<?php echo custom_lang('sima_pay_information', "Nota (opcional)"); ?>">

    <form id="client-form" style="overflow: hidden !important;">

        <div class="row" style="overflow: hidden !important;">

            <div class="col-md-12" style="font-size: 16px; font-weight: bold"><?php echo custom_lang('sima_pay_value', "Nota"); ?>:</div>

            <div class="col-md-12">
                <textarea rows="9" id="notas" name="notas" cols="60" style="border: none !important;"></textarea>

            </div>

            <div class="col-md-12" style="font-size: 16px; font-weight: bold"><?php echo custom_lang('sima_pay_value', "Nota Comanda"); ?>:</div>

            <div class="col-md-12">
                <textarea rows="9" id="nota_comanda" name="notas" cols="60" style="border: none !important;"></textarea>

            </div>

        </div>

    </form>

</div>

<div id="dialog-sobrecosto-form"  style="overflow: hidden;" title="<?php echo custom_lang('sima_pay_information', "Propina (opcional)"); ?>">

    <form id="client-form">
        <div class="row-form" class="data-fluid">
            <input type="radio" name="tipo_propina" value="porcentaje" checked class="tipo_propina">Por porcentaje<br>
            <input type="radio" name="tipo_propina" value="valor"  class="tipo_propina">Por valor<br>
        </div>
        <div class="row-form" class="data-fluid">
            <div class="text-center"><?php echo custom_lang('sima_pay_value', "Propina"); ?>: <input type="number" style="width:100px; margin-top: 10px;" name="sobrecostos_input" id="sobrecostos_input" /></div>
            <input type="hidden" style="width:100px; margin-top: 10px;" name="sobrecostos_input_valor" id="sobrecostos_input_valor" value="" />
        </div>
    </form>

</div>

<div id="dialog-plan-separe-form"  title="<?php echo custom_lang('sima_pay_information', "Plan Separe"); ?>">
  
    <form id="plan-separe-form">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="valor_pagar_plan"><?php echo custom_lang('sima_pay_value', "Valor a pagar"); ?>:</label>
                    <input type='hidden' name='valor_pagar_hidden_plan' id='valor_pagar_hidden_plan'/>
                    <input type="hidden" name="descuento_general_plan" id="descuento_general_plan" value="0"/>
                    <input type="text" class="form-control" disabled='disabled' name="valor_pagar_plan" id="valor_pagar_plan"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="forma_pago_plan"><?php echo custom_lang('sima_forma_pago', "Forma de pago"); ?>:</label>
                    <select class="form-control" name="forma_pago" id="forma_pago_plan">
                        <?php foreach ($data['forma_pago'] as $f) { ?>
                            <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="valor_entregado_plan"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</label>
                    <input type="text" class="form-control" name="valor_entregado_plan" id="valor_entregado_plan" />
                    <input type="hidden" name="id_cliente_plan" id="id_cliente_plan" style="width: 260px;height: 25px;"/>

                    <input type="hidden" name="valor_entregado" id="valor_entregado1_plan" value="0" />
                    <input type="hidden" name="valor_entregado" id="valor_entregado2_plan" value="0" />
                    <input type="hidden" name="valor_entregado" id="valor_entregado3_plan" value="0" />
                    <input type="hidden" name="valor_entregado" id="valor_entregado4_plan" value="0" />
                    <input type="hidden" name="valor_entregado" id="valor_entregado5_plan" value="0" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha_vencimiento"><?php echo custom_lang('sima_forma_pago', "Fecha de vencimiento"); ?>:</label>
                    <input type="text" class="form-control"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha_vencimiento"/>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="nota_plan_separe"><?php echo custom_lang('sima_nota', "Nota"); ?>:</label>
                    <textarea class="form-control"  name="" id="nota_plan_separe" placeholder="Escribe la nota para tu plan separe aquí."></textarea>
                </div>
            </div>


        </div>


        <!--<div class="row" class="data-fluid">
            <div class="col-md-4"><?php echo custom_lang('sima_pay_value', "Valor a pagar"); ?>:</div>
            <div class="col-md-8">
                <input type='hidden' name='valor_pagar_hidden_plan' id='valor_pagar_hidden_plan'/>
                <input type="hidden" name="descuento_general_plan" id="descuento_general_plan" value="0"/>
                <input type="text" disabled='disabled' name="valor_pagar_plan" id="valor_pagar_plan"/>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4"><?php echo custom_lang('sima_forma_pago', "Forma de pago"); ?>:</div>

            <div class="col-md-8">

                <select name="forma_pago" id="forma_pago_plan">
                    <?php
                    foreach ($data['forma_pago'] as $f) {
                        ?>
                        <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="row">

            <div class="col-md-4"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>

            <div class="col-md-8">
                <input type="number" name="valor_entregado_plan" id="valor_entregado_plan"   />
                <input type="hidden" name="id_cliente_plan" id="id_cliente_plan" style="width: 260px;height: 25px;"/>

                <input type="hidden" name="valor_entregado" id="valor_entregado1_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado2_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado3_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado4_plan" value="0" />
                <input type="hidden" name="valor_entregado" id="valor_entregado5_plan" value="0" />

            </div>

        </div>

        <div class="row">

            <div class="col-md-4"><?php echo custom_lang('sima_forma_pago', "Fecha de vencimiento"); ?>:</div>

            <div class="col-md-8">

                <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha_vencimiento"/>

            </div>

        </div>

        <div class="row">
            <div class="col-md-4"><?php echo custom_lang('sima_nota', "Nota"); ?>:</div>
            <div class="col-md-8">
                 <textarea name="" id="nota_plan_separe"></textarea>
            </div>
        </div>-->

        <br />
        <div align="center">
            <input type="button" value="Aceptar"  id="grabar_plan" class="btn btn-success"/>
            <input type="button" value="Cancelar"  id="cancelar" class="btn btn-default"/>
        </div>
    </form>

</div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-client-form" aria-labelledby="ui-id-1" style="display: none; position: relative;"> 
    <div id="dialog-client-form" class="ui-dialog-content ui-widget-content" title="<?php echo custom_lang('sima_pay_information', "Adicionar Cliente"); ?>">
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
                <div class="span8">Correo electr&oacute;nico<input type="text" name="email" id="email" class="validate[custom[email]]"></div>
            </div>
            <div class="row-fluid">
                <div class="span4">Tel&eacute;fono <input type="text" name="telefono" id="telefono"></div>
                <div class="span4">Celular <input type="text" name="celular" id="celular"></div>
                <div class="span4">Direcci&oacute;n <input type="text" name="direccion" id="direccion"></div>
            </div>
            <div class="row-fluid">
                <div class="span4">Pa&iacute;s <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), " id='pais' style='width: 100%; '"); ?></div>
                <div class="span4">Ciudad   <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'"); ?></div>
                <div class="span4">Grupo <select name="grupo" id="grupo">
                            <option>Seleccione un grupo</option>
                            <?php
                            foreach($data['grupos'] as $g)
                            {
                                ?>
                                <option value="<?php echo $g->id ?>"><?php echo $g->nombre ?></option>
                                <?php
                            }
                            ?>
                        </select></div>
            </div>

            <?php
            
            if ($data['puntos']) {
                if ($data['si_no_plan_punto'] != '0') {
                    //print_r($data); die();
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
                                                <td>Código de Tarjeta</td>
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
<!--modal editar cliente rapido-->
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-client-form2" aria-labelledby="ui-id-1" style="display: none; position: relative;"> 
    <div id="dialog-client-form2" class="ui-dialog-content ui-widget-content" title="<?php echo custom_lang('sima_pay_information', "Editar Cliente"); ?>">
        <form id="client-form">            
            <div class="row-fluid errores_cliente_domi">
                Todos los campos son obligatorios                
            </div>
            <div class="row-fluid">
                <div class="col-md-4">Nombre<input style="margin-top: 5px;" type="text" name="nombre_comercial_cliente_edit" id="nombre_comercial_cliente_edit" required> </div>
                 <div class="col-md-4"><label>Tel&eacute;fono </label><input type="text" name="telefono_edit" id="telefono_edit" required ></div>
                 <div class="col-md-4"><label>Direcci&oacute;n </label><input type="text" name="direccion_edit" id="direccion_edit" required ></div>
            </div>           
                  
        </form>
    </div>

  
  
</div>
<!--modal editar cliente rapido-->


<!--Enviar Factura correo-->

+<div class="modal fade in styleModalVendty" id="enviarFacturaModal" aria-hidden="true" aria-labelledby="examplePositionTop" role="dialog" tabindex="-1" style="diysplay:bock; padding-right: 17px;">
    <div class="modal-dialog modal-top">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close grabarVenta" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="exampleModalTitle">¿Desea enviar la factura al correo electronico del cliente?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>Correo a donde se va enviar</label>
                        <input type="email" id="emailCliente" class="form-control round" name="emailCliente" placeholder="Ingrese el correo a donde se quiere enviar" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default grabarVenta" id="EnviarFacturaBotonNo" data-dismiss="modal" onclick="grabar()">No</button>
                <button type="button" class="btn btn-success" id="EnviarFacturaBoton">Si</button>
            </div>
        </div>
    </div>
</div>
<!--enviar correo Factura-->

<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset" style="background:#FFFFFF"></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>

<!-- Si comanda y sistemas de notificacion comanda esta activo -->
<?php if ($data['comanda_push'] == "1" && $data['comanda'] == "si") { ?>
    <!-- Modal Comanda -->
    <div class="modal fade" id="comandaSidebar" aria-hidden="true" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1">
        <div id="comandaSidebarModal" class="modal-dialog modal-sidebar modal-sm">
            <div id="comandaSidebarContenido" style=" height: 100%; overflow-y: auto;background-color: #f3f3f3;">
                <div class="headerComanda" onclick="cerrarComanda()" style=" cursor: pointer"> Notificaciones Comanda </div>

                <div id="contComandaSides">

                    <div id="comandaL" class="minH100">
                        <div class="comandaUsu">
                            Mesero 1
                        </div>
                        <div class="comandaUsu">
                            Mesero 2
                        </div>
                        <div class="comandaUsu">
                            Mesero 3
                        </div>

                    </div>
                    <div id="comandaR" class="minH100">
                        <div id="comandaRCont" class="minH100">

                            <div class="comandaVenH">
                                Asignado
                            </div>
                            <div id="contComBtnAsignados">
                                <div class="btnCom"> <div style=" padding: 1px 1px; height:45px;  vertical-align: text-bottom;" class="btn 2" onclick="">Venta # 1</div> </div>
                                <div class="btnCom"> <div style=" padding: 1px 1px; height:45px;  vertical-align: text-bottom;" class="btn 2" onclick="">Venta # 1</div> </div>
                            </div>
                            <div class="comandaVenH">
                                Sin asignar
                            </div>
                            <div id="contComBtnNoAsignados">
                                <div class="btnCom"> <div style=" padding: 1px 1px; height:45px;  vertical-align: text-bottom;" class="btn 2" onclick="">Venta # 1</div> </div>
                                <div class="btnCom"> <div style=" padding: 1px 1px; height:45px;  vertical-align: text-bottom;" class="btn 2" onclick="">Venta # 1</div> </div>
                                <div class="btnCom"> <div style=" padding: 1px 1px; height:45px;  vertical-align: text-bottom;" class="btn 2" onclick="">Venta # 1</div> </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="comandaFooter">

                    <a href="javascript:cerrarImprimirComanda();" class="" style=" margin-left: 20px; margin-top: 14px;  font-weight: 400; margin-top: 15px; float: left; "> <i class="icon wb-print" aria-hidden="true"></i> Imprimir </a>

                    <a href="javascript:enviarComanda()" class=" btn funcLista" style=" width: 150px; margin: 11px 20px 0px 0px; float: right; "> <span class="textFunc"> Aplicar </span></a>
                    <a href="javascript:cerrarComanda()" class="" style=" margin-right: 30px; margin-top: 14px; color: #a90000; font-weight: 400; float: right; "> Cerrar </a>
                </div>

            </div>
        </div>
    </div>
    <!-- End Modal Comanda -->
    <!-- Sistema de notificaciones para comanda -->

<?php } ?>

<?php if($data['factura_con_mesas'] == 'si') { ?>
     <div class="modal fade" id="mesasSidebar" aria-hidden="true" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1">
        <div id="mesasSidebarModal" class="modal-dialog modal-sidebar modal-sm">
            <div id="mesasSidebarContenido" style=" height: 100%; overflow-y: auto;background-color: #f3f3f3;">
                <div class="headerComanda" onclick="cerrar_mesas()" style=" cursor: pointer"> Mesas </div>
                <div id="contmesasSides">
                    <div id="div_mesas_seccion">

                    </div>

                </div>

                <div id="comandaFooter">
                    <a href="javascript:cerrar_mesas()" class="" style=" margin-right: 30px; margin-top: 14px; color: #a90000; font-weight: 400; float: right; "> Cerrar </a>
                </div>
            </div>      
        </div>      
    </div> 
    
<?php } ?>


<!-- Inicio Modal Bottom-->
<div class="modal fade styleModalVendty" id="examplePositionBottom" aria-hidden="true" aria-labelledby="examplePositionBottom"
     role="dialog" tabindex="-1">
    <div class="modal-dialog modal-bottom">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Datafono Vendty</h4>
            </div>
            <div class="modal-body">
                <iframe id="datafono_venty_iframe" class="embed-responsive-item" src="" style="width: 100%; min-height: 300px;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-target="#examplePositionCenter" data-toggle="modal" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal Bottom-->





<!-- Modal -->
<!--<div class="modal fade styleModalVendty" id="examplePositionCenter" aria-hidden="true" aria-labelledby="examplePositionCenter"
     role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sidebar modal-lg">
        <div class="modal-content" style="height: 100%; background-color: #f9f9f9;">
            <div class="modal-header" style="background-color: #f9f9f9">
                <span class="span4"></span>
            </div>
            <div class="modal-body minH90" style="margin-top:-15px;">
                <div class="row-fluid" style="margin-top:-15px;">
                    <div class="span2">
                        <div class="row-fluid text-left span12">
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="1000" class="label label-success btn-lg btn_denomination" style=" width: 100%; padding: 10px; background-color: #00b22d !important;">$1.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="2000" class="label label-success btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$2.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="5000" class="label label-success btn-round btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$5.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="10000" class="label label-success btn-round btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$10.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="20000" class="label label-success btn-round btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$20.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="50000" class="label label-success btn-round btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$50.000</button>&nbsp;
                            </div>
                            <div class="col-sm-12 col-xs-3">
                                <button type="button" data-value="100000" class="label label-success btn-round btn-lg btn_denomination" style=" width: 100%; padding: 10px; margin-top:-7px; background-color: #00b22d !important;">$100.000</button>&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="span6 text-center">
                        <div class=" span12 content">
                            <div class="row-fluid">
                                <div class="span8 text-right">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <button type="button" data-value="1" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">1</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="2" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">2</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="3" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">3</button>
                                        </div>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <button type="button" data-value="4" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">4</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="5" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">5</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="6" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">6</button>
                                        </div>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row ">
                                        <div class="col-xs-4">
                                            <button type="button" data-value="7" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">7</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="8" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999;background-color: #fff !important;">8</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="9" class="label label-success btn-floating btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">9</button>
                                        </div>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <button type="button" data-value="." class="label label-success btn-floating btn-block btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">.</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" data-value="0" class="label label-success btn-floating btn-block btn-lg btn_number" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;">0</button>
                                        </div>
                                        <div class="col-xs-4">
                                            <button type="button" class="label label-success btn-floating btn-lg btn_delete" style=" width: 90px !important; color: #000; height: 90px !important; box-shadow: 0 0px 0px #fff !important; border: 1px solid #999; background-color: #fff !important;"><i class="glyphicon glyphicon-arrow-left"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span5 text-center" style="margin-left: -60px;">
                        <div class="span12 text-left">

                            <div class="col-md-10"><h1 class="modal-title">Total <span class="pull-right" id="valor_pagar_label">0</span></h1></div><div class="col-xs-2"><button type="button" class="label label-success label-lg" style="margin-top:10px;background-color: #6dca42 !important;" id="descuento_general_pro"  onClick="descuento_general_propover('mostrar');">%</button></div>

                            <div class="span12" style=" border: 1px solid #999; background-color: #fff; padding: 10px; height: 320px; border-radius: 4px; margin-left: -0px; overflow:auto;">

                                <!--   Inicio antigua forma de pago   -+->

                                <form id="client-form">
                                    <div style="display:none;">
                                        <!--<div class="span2"><?php echo custom_lang('sima_pay_value', "Valor a pagar"); ?>:</div>-+->
                                        <div class="input-append span2">
                                            <input type='hidden' name='valor_pagar_hidden' id='valor_pagar_hidden'/>
                                            <input type="hidden" name="descuento_general" id="descuento_general" value="0"/>
                                            <input type="hidden" disabled='disabled' name="valor_pagar" id="valor_pagar"/>
                                            <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px;"/>
                                            <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>

                                            <?php } else { ?>
                                                <!--<button type="button" class="btn btn-primary" title="Descuento al valor total de venta" id="descuento_general_pro"  onClick="descuento_general_propover('mostrar');"  >%</button>-+->
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if ($data['multiples_formas_pago'] != 'si') { ?>
                                        <div class="row-form span12">

                                            <div class="span5"><?php echo custom_lang('sima_forma_pago', "Forma de pago"); ?>:</div>

                                            <div class="span7">

                                                <select name="forma_pago" id="forma_pago_plan">
                                                    <?php
                                                    foreach ($data['forma_pago'] as $f) {
                                                        ?>
                                                        <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
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
                                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                </div>
                                                <div class="span1">
                                                    <label>Impuesto</label>
                                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
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
                                    <div class="row-fluid" id="contenido_a_mostrar">
                                            <div class="row-form">

                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">

                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift" index="" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb" href="javascript:void(0);" class="btn btnBuscarGift2" index=""><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito" index="" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index=""><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input type="number" class="dataMoneda1 span10"  name="valor_entregado" id="valor_entregado"/>
                                                    <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>

                                                    <input class="codigoNotaCredito" type="text" name="valor_datafono_vendty" id="valor_datafono_vendty" index="" value="" placeholder=" Valor Datafono Vendty"/>
                                <a class="btn btnDatafono_vendty" id="valor_datafono_vendtyb"
                                   onclick="set_iframe_url()"
                                   data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal"><span class="icon glyphicon glyphicon-th" style=""></span></a>

                                                </div>

                                            </div>
                                            <div class="row-form datafono" style="display:none">
                                                <div class="span2">
                                                    <label>Subtotal</label>
                                                    <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                </div>
                                                <div class="span2">
                                                    <label>Iva</label>
                                                    <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                </div>
                                                <div class="span1">
                                                    <label>Impuesto</label>
                                                    <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" id="contenido_a_mostrar1">
                                            <div class="row-form">
                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago1">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">
                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift1" index="1" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb1" href="javascript:void(0);" class="btn btnBuscarGift2" index="1"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito1" index="1" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="1"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="dataMoneda1 span10" type="number" name="valor_entregado" id="valor_entregado1" value="0"/>&nbsp;
                                                    <a style='cursor: pointer;' data-id="1" title=""><i class="ico ico-remove"></i></a>

                                                </div>
                                                <div class="row-form datafono" style="display:none">
                                                    <div class="span2">
                                                        <label>Subtotal</label>
                                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span2">
                                                        <label>Iva</label>
                                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span1">
                                                        <label>Impuesto</label>
                                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="row-fluid" id="contenido_a_mostrar2">
                                            <div class="row-form">

                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago2">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">
                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift2" index="2" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb2" href="javascript:void(0);" class="btn btnBuscarGift2" index="2"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito2" index="2" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="2"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="dataMoneda1 span10" type="number" name="valor_entregado" id="valor_entregado2" value="0"/> &nbsp;
                                                    <a style='cursor: pointer;' data-id="2" title=""><i class="ico ico-remove"></i></a>

                                                </div>
                                                <div class="row-form datafono" style="display:none">
                                                    <div class="span2">
                                                        <label>Subtotal</label>
                                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span2">
                                                        <label>Iva</label>
                                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span1">
                                                        <label>Impuesto</label>
                                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" id="contenido_a_mostrar3">
                                            <div class="row-form">

                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago3">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">
                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift3" index="3" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb3" href="javascript:void(0);" class="btn btnBuscarGift2" index="3"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito3" index="3" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="3"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="dataMoneda1 span10" type="number" name="valor_entregado" id="valor_entregado3" value="0"/> &nbsp;
                                                    <a style='cursor: pointer;' data-id="3" title=""><i class="ico ico-remove"></i></a>

                                                </div>
                                                <div class="row-form datafono" style="display:none">
                                                    <div class="span2">
                                                        <label>Subtotal</label>
                                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span2">
                                                        <label>Iva</label>
                                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span1">
                                                        <label>Impuesto</label>
                                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" id="contenido_a_mostrar4">
                                            <div class="row-form">

                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago4">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">
                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift4" index="4" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb4" href="javascript:void(0);" class="btn btnBuscarGift2" index="4"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito4" index="4" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob4" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="4"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="dataMoneda1 span10" type="number" name="valor_entregado" id="valor_entregado4" value="0" />  &nbsp;
                                                    <a style='cursor: pointer;' data-id="4" title=""><i class="ico ico-remove"></i></a>

                                                </div>
                                                <div class="row-form datafono" style="display:none">
                                                    <div class="span2">
                                                        <label>Subtotal</label>
                                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span2">
                                                        <label>Iva</label>
                                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span1">
                                                        <label>Impuesto</label>
                                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" id="contenido_a_mostrar5">
                                            <div class="row-form">

                                                <div class="span4">
                                                    <select name="forma_pago" id="forma_pago5">
                                                        <?php
                                                        foreach ($data['forma_pago'] as $f) {
                                                            ?>
                                                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="span8">
                                                    <input class="codigoGift" type="text" name="valor_entregado_gift" id="valor_entregado_gift5" index="5" value="" placeholder=" C&oacute;digo GiftCard"/>
                                                    <a id="valor_entregado_giftb5" href="javascript:void(0);" class="btn btnBuscarGift2" index="5"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito5" index="5" value="" placeholder=" C&oacute;digo Nota Credito"/>
                                                    <a id="valor_entregado_nota_creditob5" href="javascript:void(0);" class="btn btnBuscarNotaCredito2" index="5"><span class="icon glyphicon glyphicon-search" style=""></span></a>
                                                    <input class="dataMoneda1 span10" type="number" name="valor_entregado" id="valor_entregado5"  value="0"/> &nbsp;
                                                    <a style='cursor: pointer;' data-id="5" title=""><i class="ico ico-remove"></i></a>

                                                </div>
                                                <div class="row-form datafono" style="display:none">
                                                    <div class="span2">
                                                        <label>Subtotal</label>
                                                        <input class="subtotal" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span2">
                                                        <label>Iva</label>
                                                        <input class="impuesto2" type="text" disabled="true" value="0" style="text-align:right">
                                                    </div>
                                                    <div class="span1">
                                                        <label>Impuesto</label>
                                                        <input type="text" name="impuestoDatafono" id="impuestoDatafono" value="<?php echo $impuestopredeterminado; ?>" style="text-align:right">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="row-form"> <div class="span4"><p><a style='cursor: pointer;' onClick="mostrar();" title=""><i class="icon-plus"></i> Agregar</a></p></div>
                                            <div class="span3"> </div></div>

                                    <?php } ?>
                                    <div id="row-fecha-vencimiento" class="row-form" style="display:none">

                                        <div class="span2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>

                                        <div class="span3">

                                            <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" readonly/>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <!--<div class="span2"><?php echo custom_lang('sima_cambio', "Cambio"); ?>:</div>-+->

                                        <div class="span3">

                                            <input type='hidden' name='sima_cambio_hidden' id='sima_cambio_hidden'/>

                                            <input type="hidden" disabled='disabled' name="sima_cambio" id="sima_cambio" />

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
                                </form>

                                <!--   Fin antigua forma de pago   -+->

                            </div>
                            <div class="row-fluid">&nbsp;</div>
                            <h3 style="margin-top: 250px;color: #a90000; font-size: 36px;">Cambio <span class="pull-right" id="sima_cambio_label" style="color: #a90000;">0</span></h3>
                        </div>

                        <div class="row-fluid">&nbsp;</div>
                        <div class="row-fluid">&nbsp;</div>
                        <div class="row-fluid">&nbsp;</div>
                        <div class="row-fluid">&nbsp;</div>
                        <div class="row-fluid">&nbsp;</div>
                    </div>

                    <div class="span12 text-center">   <div class="row-fluid span2">
                            <div class="row col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135%;padding: 8px;border-radius: 8px; margin-left:-6px" data-value="efectivo" data-tipo="">Efectivo</button>
                            </div>
                        </div>&nbsp;
                        <div class="row-fluid span2">
                            <div class="row-fluid col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135px;padding: 8px;border-radius: 8px;margin-left: -26px;" data-value="tarjeta_credito" data-tipo="Datafono">Tarjeta de crédito</button>
                            </div>
                        </div>&nbsp;
                        <div class="row-fluid span2">
                            <div class="row-fluid col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135px;padding: 8px;border-radius: 8px;margin-left: -23px;" data-value="tarjeta_debito" data-tipo="Datafono">Tarjeta debito</button>
                            </div>
                        </div>&nbsp;
                        <div class="row-fluid span2">
                            <div class="row-fluid col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135%;padding: 8px;border-radius: 8px;margin-left: -19px;" data-value="Credito" data-tipo="">Crédito</button>
                            </div>
                        </div>&nbsp;
                        <div class="row-fluid span2">
                            <div class="row-fluid col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135%;padding: 8px;border-radius: 8px;margin-left: -23px;" data-value="Saldo_a_Favor" data-tipo="">Saldo a Favor</button>
                            </div>
                        </div>&nbsp;
                        <div class="row-fluid span2">
                            <div class="row-fluid col-md-12">
                                <button type="button" class="btn btn-block btn-round" style="background-color: #6dca42 !important;color: #fff;width: 135%;padding: 8px;border-radius: 8px;margin: -22px 0px 0px -30px;" data-value="Visa_debito" data-tipo="">Visa débito</button>
                            </div>
                        </div>&nbsp;
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="position: absolute; width: 100%; bottom: 0;">
                <div class="span4">
                    <div class="span4" align="center" style=" border: 1px solid #999; padding: 12px; margin-top: 5px; border-radius: 4px; width:90%;" data-dismiss="modal">
                        <a href="#"><span class="textFunc" style="color: #999; font-size:20px;">Cancelar</span></a></div>
                </div>
                <div class="span4" align="center">
                    <a href="#" class=" btn funcLista btn-lg" id="grabar" style=" width: 90% !important; padding: 16px !important;"> <span class="textFunc"> Aceptar </span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Modal -->
<!--
-->

<!-- Modal Division de cuentas -->

<div class="modal fade styleModalVendty" style="top: 10% !important;" id="modal-division-cuenta" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Divisi&oacute;n de cuenta</h4>
      </div>
      <div class="modal-body">
           <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Selecione los productos</h4>
                        <div class="table-responsive-md">
                            <table id="tabledivision" class="table table-bordered table-hover table-sm ">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 40%;">Producto</th>
                                        <th scope="col" style="width: 20%;">Cantidad</th>
                                        <th scope="col" style="width: 20%;">Precio</th>
                                        <th scope="col" style="width: 20%;">Facturar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3"><b class="pull-right" id="totaldivision">Total: $0</b></th>
                                        <th colspan="1"> <button type="button" class="btn btn-success btn_dividir_cuenta pull-right">Facturar</button></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal  -->

<script src="<?php echo base_url(); ?>public/js/ventasComanda.js?v=1.0"></script>
<script src="<?php echo base_url(); ?>public/v2/appVentasOffline.js"></script>
<?php if($data['imprimir_comanda']=='si'){ $restaurant="1_".$data['zona']."_".$data['mesa']; }else{ $restaurant=0; }  ?>
<script type="text/javascript">

    $(document).ready(function() {
        try {
            $('#facturacion-electronica-check').get(0).type = 'checkbox';
            $('#facturacion-electronica-check').change(function() {
                if($(this).is(':checked')) {
                    if($('#datos_cliente').val() == '') {
                        $(this).prop('checked', false);
                        $('#backPopUp').css("display", "none");
                        $('#dialog-forma-pago-form').dialog('close');
                        swal({
                            position: 'center',
                            type: 'error',
                            title: "Error",
                            html: "Debe seleccionar un cliente si desea usar la facturación electrónica.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                        $('datos_cliente').focus();
                    }
                }
            })
        } catch(e){}
    });  
    
    var comandarestaurante= "<?php  echo $restaurant; ?>";
    var facturaAutomatica = "<?php echo $data['auto_factura'] ?>";
    var enviar_factura = "<?php echo $data['enviar_factura'] ?>";
    var pagoAutomatico = "<?php echo $data['auto_pago'] ?>";
    var sobrecostoTodos = "<?php echo $data['sobrecosto_todos'] ?>";
    var factura_mesas = '<?php echo $data['factura_con_mesas'] ?>';

    var pedidos_mesas = [];
    var facturas_en_espera = [];
    var finalto=0;
    var descuentogeneral=0;
    var cantidadsindescuentogeneral=0;
    var clientesCartera = "<?php echo $data['clientes_cartera'] ?>";
    var planSepareObj = {saveTitulo: "Titulo", estado: false};
    var controladorSepare = "<?php echo site_url("ventas_separe/nuevo"); ?>";
    var cantidadProductosProduccion = "<?php echo $data['cantidadProductos'] ?>";
    $url = "<?php echo site_url("productos/productos_filter"); ?>";
    $url = "<?php echo site_url("productos/productos_filter_group"); ?>";
    $urlVitrina = "<?php echo site_url("productos/get_by_category"); ?>";
    $urlImages = "<?php echo "https://vendty-img.s3-us-west-2.amazonaws.com/"; ?>";
    $urlImagesCategoria = "<?php echo base_url("/uploads/").'/'.$this->session->userdata("base_dato").'/categorias_productos'; ?>";
    $urlCategorias = "<?php echo site_url("categorias/limit"); ?>";
    $sendventas = "<?php echo site_url("/ventas/nuevo"); ?>";
    $comprobar_venta = "<?php echo site_url("/ventas/comprobar_venta"); ?>";
    $caso="<?php echo $this->session->userdata("db_config_id");?>";
    $sendventas_espera = "<?php echo site_url("/ventas/espera"); ?>";
    $sendventas_espera_actualizar = "<?php echo site_url("/ventas/espera_actualizar"); ?>";
    $comanda = "<?php echo site_url("/ventas/comanda"); ?>";
    $comanda_imprimir = "<?php echo site_url("/ventas/comanda_imprimir"); ?>";
    $reload = "<?php echo site_url("ventas/index"); ?>";
    $reloadThis = "<?php echo site_url("ventas/nuevo"); ?>";
    $urlPrint = "<?php echo site_url("ventas/imprimir"); ?>";
    $urlcliente = "<?php echo site_url("clientes/get_ajax_clientes"); ?>";
    $urlclienteCartera = "<?php echo site_url("clientes/get_ajax_clientes_cartera"); ?>";
    $navegador = '<?php echo $_REQUEST['var']; ?>';
    $impuestosnom = "<?php echo site_url("impuestos/get_impuesto"); ?>";
    $sobrecosto = "<?php echo $data['sobrecosto']; ?>";
    $nit = "<?php echo $data['nit']; ?>";
    $url_consultar_ventas_espera = "<?php echo site_url("ventas/factura_espera"); ?>";
    $url_webpay_iframe = "<?php echo site_url("webpay/closeIframe")?>"
    $url_notas_factura_espera = "<?php echo site_url("ventas/getFacturaEsperaNota"); ?>"
    $url_detalles_factura_espera = "<?php echo site_url("ventas/detalles_espera"); ?>";
    $url_actualizar_notas_factura_espera = "<?php echo site_url("ventas/setFacturaEsperaNota"); ?>";

    $aleatorio = "<?php echo $data['aleatorio']?>";
    $pagarGiftCard = "<?php echo site_url("/productos/pagarGiftCard"); ?>";
    $estadoGiftCard = "<?php echo site_url("/productos/estadoGiftCard"); ?>";
    $canjearGiftCard = "<?php echo site_url("/productos/cancelarGiftCard"); ?>";
    $estadoNotaCredito = "<?php echo site_url("/notacredito/estadoNotaCredito"); ?>";
    $canjearNotaCredito = "<?php echo site_url("/notacredito/cancelarNotaCredito"); ?>";
    $elimanrComandaTemporal = "<?php echo site_url("ventas/eliminar_comanda_temporal"); ?>";
    $buscarEmail = "<?php echo site_url("clientes/getClienteId"); ?>";   
    $buscarstockespeciales = "<?php echo site_url("productos/stockactualespeciales"); ?>"; 
    $enviaCorreoPrimeraVenta = "<?php echo site_url("ventas/enviar_email_primera_venta"); ?>";
    $buscador=2;
    $siteUrl = "<?php echo site_url(); ?>";
    $id_user_mix= "<?php echo $this->session->userdata('user_id') ?>";
    $email_mix= "<?php echo $this->session->userdata('email') ?>";
    $empresa_mix= "<?php echo (!empty($data['datos_empresa'][0]->nombre_empresa))? $data['datos_empresa'][0]->nombre_empresa : 'No existe nombre' ?>";
    <?php if ($data['multiples_formas_pago'] == 'si') { ?>
            $height = 570;
            $width = 700;
    <?php } ?>
    <?php if ($data['multiples_formas_pago'] != 'si') { ?>
            $height = 500;
            $width = 700;
    <?php } ?>

    <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
            $sinprecio = 'si';
    <?php } else {?>
            $sinprecio = 'no';
    <?php } ?>

     <?php if($this->session->userdata('is_admin') == 't' ){ ?>
        const es_usuario_administrador = true;
    <?php }else{ ?>
        const es_usuario_administrador = false;
    <?php }?>;

    var id_usuario_js;
    id_usuario_js = <?php echo $this->session->userdata('user_id'); ?> ;

    //=================================================
    // COMANDA
    //=================================================
    // Si la notificacion de comanda esta activa iniciamos la aplicacion
    <?php if ($data['comanda_push'] == "1" && $data['comanda'] == "si") { ?>

        $(document).ready(function () {
            // Al cerrar guardamos y enviamos notificaciones
            $("#comandaSidebar").on("hidden.bs.modal", function () {
                enviarComanda();
            });
            
            consultarNotificacion();
            runComanda();
        });

    <?php } ?>

    var offline = "<?php echo getOffline(); ?>";
    if (offline == "backup") {
        var appOffline;
        appOffline = new classVentaOffline();
        appOffline.conectarDB(function () {
            //OFFLINE
            // Obtenemos la cantidad de productos offline
            appOffline.getTotalProductos(
                    function () {
                        equalProducts(appOffline.totalProductos());
                    }
            );
        });
    }

    $(document).ready(function () {

        //valor por defecto del porcentaje 0
        /*if(!localStorage.porcentage_default)
        {
            localStorage.porcentage_default = 0;
        }*/
        //set a la caja de texto el valor encontrado en localstorage
        if(localStorage.porcentage_default){
            $('#sobrecostos_input').val(Number(localStorage.porcentage_default));
        }else{
            $('#sobrecostos_input').val(Number(10));
        }
        
        vari = '<?php if (isset($_GET['var'])){ echo $_GET['var']; }else{ echo 2; }  ?>';        
        if(vari !=""){             
            if(vari==1){                
                setTypeSearch(1);
            }
        }
        
        $("#datos_vendedor").autocomplete({
            source: "<?php echo site_url("vendedores/get_ajax_vendedores"); ?>",
            minLength: 1,
            select: function (event, ui) {
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

        $("#dialog-domicilio-form").dialog({
            autoOpen: false,
            height: 350,
            width: 700,
            modal: true,
            buttons: {
                "Guardar": function () {                   
                    domiciliario=$("#domiciliario").val();
                    datos_cliente_domicilio=$("#datos_cliente_domicilio").val().trim();
                    telefono_domicilio=$("#telefono_domicilio").val().trim();
                    direccion_domicilio=$("#direccion_domicilio").val().trim();
                    if((domiciliario!="") && (datos_cliente_domicilio !="") && (telefono_domicilio !="" ) && (direccion_domicilio !="")){
                        $("#presione_domicilio").val("si");
                        $("#sobrecostos_input").val(0);
                        $(this).dialog("close");
                    }else{
                        $("#dialog-domicilio-form").dialog("close");
                        swal({
                            position: 'center',
                            type: 'error',
                            title: "Error",
                            html: "Todos los Campos son requeridos",
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function(){ $("#dialog-domicilio-form").dialog("open");  }, 1600);                        
                    }
                    
                },
               "Cancelar": function () {                    
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog-puntos-leal-form").dialog({
            autoOpen: false,
            height: 350,
            width: 700,
            modal: true,
            closeOnEscape: false,
            open: function() {
                $(".ui-dialog-titlebar-close").hide();
            },
            buttons: {
                "Seleccionar": function () {
                    if (userPuntosLeal) {       
                        userPuntosLealSelected = userPuntosLeal;
                        $("#selectedPuntosLeal").empty().append(`
                        <div style="border: 1px #CCC solid; height: 40px; align-items: center; display: flex; margin-top:5px;">
                            <div class="col-xs-11">${userPuntosLeal.cedula} - ${userPuntosLeal.fullname}</div>
                            <div class="col-xs-1">
                                <div class="icon" style="color: red; cusrsor: pointer;" onclick="unselectUserPuntosLeal()">
                                    <span class="wb-close"></span>
                                </div>
                            </div>
                        </div>`);
                    }
                    usersPuntosLeal = [];
                    userPuntosLeal = null;
                    $("#listPuntosLeal").empty();
                    $("#id_puntos_leal").val("");             
                    $(this).dialog("close");             
                },
               "Cancelar": function () {
                    usersPuntosLeal = [];
                    userPuntosLeal = null;
                    $("#listPuntosLeal").empty();
                    $("#id_puntos_leal").val("");             
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog-nota-form").dialog({
            autoOpen: false,
            height: 400,
            width: 500,
            modal: true,
            dialogClass: 'ap-dialog',
            buttons: {
                "Aceptar": function () {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog-sobrecosto-form").dialog({
            autoOpen: false,
            /*height: 200,
            width: 500,*/
            modal: true,
            buttons: {
                "Aceptar": function () {

                    //set de varible encontrada en el localstorage para el porcentaje de propina
                    //localStorage.porcentage_default = $('#sobrecostos_input').val();
                    $('#propina_output').html($('#sobrecostos_input').val());
                    $(this).dialog("close");
                }
            }
        });
    /*
        $(".tipo_propina").click(function(e){
            val=$('#sobrecostos_input').val();
            alert("aqui"+val);
            $('#divsobrecostos_input').toogle();
        });*/

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
                "Aceptar": function () {
                    if ($("#client-form").length > 0)
                    {

                        $("#client-form").validationEngine('attach', {promptPosition: "topLeft"});
                        if ($("#client-form").validationEngine('validate')) {

                            var clienteData = {
                                nombre_comercial: $('#nombre_comercial_cliente').val(),
                                tipo_identificacion: $('#tipo_identificacion').val(),
                                nif_cif: $('#nif_cif').val(),
                                email: $('#email').val(),
                                telefono: $('#telefono').val(),
                                direccion: $('#direccion').val(),
                                pais: $('#pais').val(),
                                provincia: $('#provincia').val(),
                                celular: $('#celular').val(),
                                plan_puntos: $('#plan_puntos').prop('checked'),
                                pl: $('#pl').val(),
                                grupo: $('#grupo').val(),
                                cod_targeta: $('#cod_targeta').val()
                            };

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
                                        $("#id_cliente_domicilio").val(data.id_cliente);
                                        $("#id_cliente_plan").val(data.id_cliente);
                                        $("#datos_cliente").val($('#nombre_comercial_cliente').val() + " (" + $('#nif_cif').val() + ")");
                                        $("#datos_cliente_domicilio").val($('#nombre_comercial_cliente').val() + " (" + $('#nif_cif').val() + ")");
                                        $('#direccion_domicilio').val($('#direccion').val());
                                        $('#direccion_domicilio_label').html($('#direccion').val());
                                        telefono1=$('#telefono').val().trim();
                                        celular1=$('#celular').val().trim();
                                        if((telefono1 != "")&&(celular1 != "")){
                                            $("#telefono_domicilio").val(telefono1+" / "+celular1);
                                            $('#telefono_domicilio_label').html(telefono1+" / "+celular1); 
                                        }else{
                                            if((telefono1 != "")){
                                                $("#telefono_domicilio").val(telefono1);
                                                $('#telefono_domicilio_label').html(telefono1);
                                            }
                                            if((celular1 != "")){
                                                $("#telefono_domicilio").val(celular1);
                                                $('#telefono_domicilio_label').html(celular1);
                                            }
                                        }   
                                        $('#direccion_domicilio').hide();                                    
                                        $('#telefono_domicilio').hide(); 
                                        $('#direccion_domicilio_label').show(); 
                                        $('#telefono_domicilio_label').show(); 
                                                                           
                                        
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

        $("#dialog-client-form2").dialog({
            autoOpen: false,
            // height: 550,
            width: 620,
            modal: true,
            buttons: {
                "Aceptar": function () {
                    var nombre=$("#nombre_comercial_cliente_edit").val().trim();
                    var direccion=$("#direccion_edit").val().trim();
                    var telefono=$("#telefono_edit").val().trim();

                    if ((nombre != "" ) && (direccion != "" ) && (telefono != "" ))
                    {                                                   
                            var clienteData = {
                                id:$("#id_cliente_domicilio").val(),
                                nombre_comercial: nombre,                               
                                telefono: telefono,
                                direccion: direccion,
                            };

                            $.ajax({
                                url: '<?php echo site_url('clientes/update_fast_ajax_client'); ?>',
                                data: clienteData,
                                dataType: 'json',
                                type: 'POST',
                                success: function (data) {
                                    if (data.success)
                                    {     
                                        $("#datos_cliente_domicilio").val(nombre);                                   
                                        $("#telefono_domicilio").val(telefono);                                   
                                        $("#telefono_domicilio_label").html(telefono);                                   
                                        $("#direccion_domicilio").val(direccion);                                   
                                        $("#direccion_domicilio_label").html(direccion); 
                                        $("#dialog-client-form2").dialog("close");
                                    } 
                                }
                            });
                    }else{
                        $(".errores_cliente_domi").html("");
                        $(".errores_cliente_domi").html("<span style='color: red'>Todos los campos son requeridos</span>");
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

        $(document).on('click','#add-new-client', function () {
            data=$("#add-new-client").data("id");
            id=$("#id_cliente_domicilio").val();            
            if(data==1){
                //buscar el cliente para editarlo
                $.ajax({
                    url: '<?php echo site_url('clientes/getClienteId'); ?>',
                    data: {id:id},
                    dataType: 'json',
                    type: 'POST',
                    success: function (data) {
                        telefono
                        movil=data.cliente.movil;
                        telefono=data.cliente.telefono;
                        if((movil != "") && (telefono != "" )){
                            cel=telefono+" / "+movil;
                        }else{
                            if(movil != ""){
                                cel=movil;
                            }
                            if(telefono != ""){
                                cel=telefono;
                            }
                        }

                        $("#direccion_edit").val(data.cliente.direccion);
                        $("#telefono_edit").val(cel);
                        $("#nombre_comercial_cliente_edit").val(data.cliente.nombre_comercial);                        
                    }
                });
                $("#dialog-client-form2").dialog("open");
            }else{
                $("#dialog-client-form").dialog("open");
            }
            
        });
        /*$("#add-new-client").click( function () {
            $("#dialog-client-form").dialog("open");
        });*/
        //pendientE
        $("#comanda").click(function () {//alert('aca');
                    
        /*mixpanel*/
       
                var id='<?php echo $this->session->userdata('user_id') ?>';       
                var email='<?php echo $this->session->userdata('email') ?>';
                var nombre_empresa='<?php echo $this->session->userdata('nombre_empresa') ?>';
                mixpanel.identify(id);   
        
        <?php 
            if($data['estado']==2){?>
                
                mixpanel.track("Comanda en Ventas Prueba", { 
                    "$email": email,   
                    "$empresa": nombre_empresa,    
                });  


        <?php
            }else{ ?>
               
                
                mixpanel.track("Comanda en Ventas", { 
                    "$email": email,   
                    "$empresa": nombre_empresa,    
                });    

        <?php
            }?>

            <?php if ($data['comanda'] == "si") { ?>
                <?php if ($data['comanda_push'] == "1") { ?>
                    $("#comanda").removeClass("comandaAlert");
                    clearComanda();
                    $('#comandaSidebar').modal('show');
                    setTimeout(function () {
                        getComandaData("");
                    }, 450);
                <?php } else { ?>
                    imprimirComanda();
                <?php } ?>
            <?php } ?>
        });
        //pendientE
        $("#pendiente").click(function (e) {
            $("#pendiente").prop('disabled',true);
            poner_venta_en_espera();            
        });

        $("#cancelarVenta").click(function () {               
            limpiarVentas();
        });

        $("#actualizar_pendiente").click(function () {
            actualizarEspera();
            resetFacturaEspera();
            limpiarVentas();
            resetBotonesEsperaColor();
            limpiar_mesas();
        });

    });



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

        $("#cancelar").click(function () { 
            
            if($('#forma_pago').val()=='nota_credito'){
                $('#forma_pago').val(0);               
                $("#valor_entregado_nota_credito").attr('style','display: none !important');
                $("#valor_entregado_nota_creditob").attr('style','display: none !important');
                $("#valor_entregado").attr('style','display: block !important'); 
            }
            for(i=1;i<6;i++){            
                if($('#forma_pago'+i).val()=='nota_credito'){                    
                    $('#forma_pago'+i).val(0); 
                    $("#valor_entregado_nota_credito"+i).attr('style','display: none !important');
                    $("#valor_entregado_nota_creditob"+i).attr('style','display: none !important');
                    $("#valor_entregado"+i).attr('style','display: block !important');  
                }       
                $('#contenido_a_mostrar' + i).fadeOut();
                $('#valor_entregado' + i).removeAttr("disabled");
                $('#valor_entregado' + i).val(0);       
                $('.subtotal').val(0);
                $('.impuesto2').val(0);
                $('#impuestoDatafono').val(0);
                $('#transaccion'+i).val(0);
            }            
        });

        $('div[id^="contenido_a_mostrar"] a').on('click', function (e) {
            
            var id = $(this).data('id');

            $('#contenido_a_mostrar' + id).fadeOut();
            $('#valor_entregado' + id).removeAttr("disabled");
            $('#valor_entregado' + id).val(0);

            //Eliminamos la giftcard si existe en las formas de pago
            eliminarGiftcard(id);
            validarMediosDePago(e);
            eliminar_efectivo(id);
        });


            //puntos -------------------------------------------------------------------
        $("#forma_pago, #forma_pago1, #forma_pago2, #forma_pago3, #forma_pago4, #forma_pago5").on('change', function (e)
        {
            ///debugger;
            eliminar_efectivo();
            var forma_pago_id = $(this).attr("id");
            var forma_pago = $(this).val();
            var cliente = $("#id_cliente").val();

            if(forma_pago == "Credito" && cliente == ''){
                alert("Aun no ha seleccionado un cliente para esta forma de pago. Verifique y vuelva a intentarlo"); 
                $(this).val("Seleccione");   
                return false;
            }

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

        $("#fecha_vencimiento").datepicker({
            dateFormat: 'yy/mm/dd'
        });
        $("#fecha_vencimiento_venta").datepicker({
            dateFormat: 'yy/mm/dd'
        });

    });

    function setTypeSearch(element){

        switch(element) {
            case 1:
                $('#search').attr("placeholder", "Búsqueda por código de barras...");
                $('#search').val("");
                $('#forNameProduct').removeClass();
                $('#forBarCode').addClass('activeB');
                $('#search').focus();
                $buscador=1;
                break;
            case 2:
                $('#search').attr("placeholder", "Búsqueda por nombre de producto...");
                $('#search').val("");
                $('#forBarCode').removeClass();
                $('#forNameProduct').addClass('activeB');
                $buscador=2;
                break;
        }
    }

    function set_iframe_url() {
        var url = 'http://localhost:60024/webpay/vendty.htm?amount=' + $('#valor_datafono_vendty').val() +'&var=<?php echo $data['db']?>&a=<?php echo $data['aleatorio']?>';
        $('#dialog-forma-pago-form').dialog( "close" );
        $('#datafono_venty_iframe').attr('src', url);

        closeIframe();
    }

    function closeIframe()
    {
        var respuestaDataphone = setInterval(function(){
            $.post
            (
                url_webpay_iframe,
                {
                    "aleatorio":$aleatorio,
                },function(data)
                {
                    if(data.close)
                    {
                        //ventana de dataphono
                        $('#examplePositionBottom').modal("toggle");
                        //venta de pago
                        $('#dialog-forma-pago-form').dialog( "open" );
                        //nueva ventana de pago
                        //$('#examplePositionCenter').modal("toggle");
                        clearInterval(respuestaDataphone);
                    }
                },'json'
            );
        },"5000");
    }

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function descuento_general_propover(valor) {

        if(descuentogeneral!=0){
            $("#descuento_general").val(descuentogeneral);
        }
        if ($("#descuento_general").val() == 0) {
            propoverContent = "<div class='input-append'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-success' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
        } else
        {
            propoverContent = "<div class='input-append'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='" + $("#descuento_general").val() + "' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-success' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
        }

        $('#descuento_general_pro').popover({
            placement: 'bottom',
            title: 'manual',
            html: true,
            content: propoverContent,
            trigger: 'manual'
        }).popover('show');

        $(document).on("click","#btn-accept-descuento", function (e) {

            totaldes1=0;
            finalto=0;
            total2=0;


            if ($('.descuento').val() != '') {
                //cantidadsindescuentogeneral=1;
                var propina_pro = $("#sobrecostos_input").val() || 10,
                valorTotal_pro = $('#subtotal_propina_input').val(),
                total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);
                var valor_total_propina_descuento = parseFloat(total_pro) - ((parseFloat(total_pro) * parseFloat($('.descuento').val())) / 100);
             //por cada uno
                $(".title-detalle").each(function(index, item) {

                        promo=$("input.precio-prod-real").eq(index).attr("data-promocion");
                        cantidad = $(".cantidad").eq(index).text();
                        impuesto = $(".impuesto-final").eq(index).val();
                        precio = parseFloat($(".precio-prod-real").eq(index).val());

                        if(promo==2){                            
                            cantidad = $("input.precio-prod-real").eq(index).attr("data-cantidad");                            
                        }                                           
                        precioProd =parseFloat((precio)+ (((precio)* ((impuesto) /100))));
                        total2 += (precioProd * cantidad);
                        if(__decimales__==0){
                            total3 = redondear(precioProd * cantidad);
                            totaldes = redondear(parseFloat(((precioProd) * (parseFloat($('.descuento').val()) / 100))));
                        }else{
                            total3 = parseFloat(precioProd * cantidad);
                            totaldes = parseFloat(parseFloat(((precioProd) * (parseFloat($('.descuento').val()) / 100))));
                        }

                        totaldes=(totaldes*parseFloat(cantidad));
                        totaldes1+=totaldes;
                        if(__decimales__==0){
                            finalto +=redondear(total3-totaldes);
                        }else{
                            finalto +=(total3-totaldes);
                        }

                        var valor = $('.descuento').val();
                        var val1 = valor.replace("%", "");
                        var val2 = val1.replace(" ", "");
                        var precio = Math.round(precioProd);
                        var primer = $('.descuento').val();
                        var res1 = primer.replace(/[1234567890]/gi, "");

                        if(__decimales__==0){
                            var resultado_porcen1 =  Math.round(parseFloat(precio) * val2 / 100);
                        }else{
                            var precio =parseFloat(precioProd);
                            var resultado_porcen1 =  (parseFloat(precio) * val2 / 100);
                        }

                        var resultado_porcen2 = (precio - resultado_porcen1);
                        w=resultado_porcen2;

                            if(impuesto != 0)
                            {
                                if(impuesto.length == 1)
                                {
                                   precioProd = w / parseFloat("1.0"+impuesto);

                                }else if(impuesto.length == 2)
                                {
                                    precioProd = w / parseFloat("1."+impuesto);
                                }

                            }else
                            {
                                precioProd = w;
                            }

                            if (__decimales__ > 0) {
                                precioProd = redondear(precioProd);
                            }

                            $(".precio-prod-descuento").eq(index).val(precioProd);
                            calculate();
                });

                var valor_total_descuento =  totaldes1;

                $('#valor_pagar_propina').html(formatDollar(
                    (parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento)
                ));

                $('#propina_output_pro').html(propina_pro + '% - ' + formatDollar(
                    parseFloat(total_pro) - ((parseFloat(total_pro) * parseFloat($('.descuento').val())) / 100)
                ));

                if(__decimales__==0){
                    $("#valor_pagar").val(redondear(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                    $("#valor_entregado").val(redondear(parseFloat(parseFloat(parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento))));
                    $("#valor_datafono_venty").val(formatDollar(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                    $("#valor_pagar_hidden").val(redondear((parseFloat($("#total").val()) - parseFloat(valor_total_descuento)) + parseFloat(valor_total_propina_descuento)));
                }else{
                   // total.;
                    $("#valor_pagar").val((parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))).toFixed(__decimales__));
                    $("#valor_entregado").val((parseFloat(parseFloat(parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento))).toFixed(__decimales__));
                    $("#valor_datafono_venty").val(formatDollar(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                    $("#valor_pagar_hidden").val(((parseFloat($("#total").val()) - parseFloat(valor_total_descuento)) + parseFloat(valor_total_propina_descuento)).toFixed(__decimales__));
                }


                finalto= $("#valor_pagar").val();
                $("#total").val($("#valor_pagar").val());
                $("#descuento_general").val($('.descuento').val());
                descuentogeneral=$('.descuento').val();
                if($('.descuento').val()==100){
                    $('#grabar_sin_pago').prop('disabled',true);
                }else{
                    $('#grabar_sin_pago').prop('disabled',false);
                }
            }
            validarMediosDePago(e);
            if (isNaN($('.descuento').val())) {
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
        $('#productos-detail').html('<tr class="nothing"><td colspan="5">No existen elementos</td></tr>');
        $('#total-show').html('0.00');
        $('#subtotal').html('0.00');
        $('#iva-total').html('0.00');
        $("#cantidad-total").html('0');
    }

    // Funcion que guarda en la DB todas las acciones del usuario en tiempo real si esta visualizando una factura en espera
    function actualizarEspera() {

        //Si una factura en espera esta activa, actualizamos en DB
        if ($("#id_fact_espera").val() != "") {

            productos_list = new Array();
            $(".title-detalle").each(function (x) {
                var descuento = 0;
                //
                descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
                if (parseFloat($(".precio-prod-real-no-cambio").eq(x).val()) < parseFloat($(".precio-prod-real").eq(x).val())) {
                    descuento = 0;
                }

                productos_list[x] = {
                    'codigo': $('.codigo-final').eq(x).val(),
                    'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),
                    'unidades': parseFloat($(".cantidad").eq(x).text()),
                    'impuesto': $(".impuesto-final").eq(x).val(),
                    'nombre_producto': $(".title-detalle").eq(x).text(),
                    'product_id': $(".product_id").eq(x).val(),
                    'descuento': descuento,
                    'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text())
                };

            });
            actualizar_productos_venta_espera(productos_list);
        }
    }

    function actualizar_productos_venta_espera(lista_productos){

        $.ajax({
                url: $sendventas_espera_actualizar,
                dataType: 'json',
                type: 'POST',
                data: {
                    id: $("#id_fact_espera").val(),
                    productos: productos_list
                },
                success: function (data) {
                    repintar_ventas_espera();
                }
            });
    }


    function imprimirComanda() {
        productos_list = new Array();
        pago = {};
        $(".title-detalle").each(function (x) {
            var descuento = 0;
            //
            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
            if (limpiarCampo($(".precio-prod-real-no-cambio").eq(x).val()) < limpiarCampo($(".precio-prod-real").eq(x).val())) {
                descuento = 0;
            }
            productos_list[x] = {
                'codigo': $('.codigo-final').eq(x).val(),
                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),
                'unidades': parseFloat($(".cantidad").eq(x).text()),
                'impuesto': $(".impuesto-final").eq(x).val(),
                'nombre_producto': $(".title-detalle").eq(x).text(),
                'product_id': $(".product_id").eq(x).val(),
                'descuento': descuento,
                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text())
            };
            pago = {
                valor_entregado: $("#valor_entregado").val(),
                cambio: $("#sima_cambio_hidden").val(),
                forma_pago: $("#forma_pago").val(),
            };
        });

        var dataFinal = {
            cliente: $("#id_cliente").val(),
            productos: productos_list,
            vendedor: $("#vendedor").val(),
            vendedor_2: $("#vendedor_2").val(),
            total_venta: $("#total").val(),
            pago: pago,
            nota: $("#nota_comanda").val(),
            sobrecostos: $("#sobrecostos_input").val(),
            id_fact_espera_nombre: $("#id_fact_espera_nombre").val()
        };

        if(productos_list.length > 0){
            $.ajax({
                url: $comanda,
                dataType: 'json',
                type: 'POST',
                data: dataFinal,
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                success: function (data) { //alert("aca");
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
                                    url: $elimanrComandaTemporal,
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
        }else{
            alert( 'No hay productos listados para la comanda, no se puede imprimir, por favor seleccione los productos antes de imprimir la comanda');
        }

    }



    function cambiar_color(id) {

        $.ajax({
            url: $url_consultar_ventas_espera,
            type: "GET",
            dataType: "json",
            data: {id: 1},
            success: function (data) {
                for (var i in data) {
                    if (data[i].id !== '-1' && ( data[i].usuario_id == id_usuario_js || es_usuario_administrador) &&
                            data[i].id != id) {   // background: #88BF6C !important;
                        // document.getElementById(''+data[i].id+'').style.backgroundColor = '#DD00DD';
                        ///$('#' + data[i].id + '').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom;');
                    }
                }

            }
        });
        // document.getElementById(''+id+'').style.backgroundColor = '#DD00DD';
        ///$('#' + id + '').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom; background-color:#5E8C47 !important');
    }

    function resetBotonesEsperaColor() {
        //$('#botones div').attr('style', 'width: 79px; padding: 1px 1px; height: 45px; vertical-align: text-bottom;');
    }

    function guardarNotaEspera() {

        if ($("#id_fact_espera").val() != "") {

            var id = $("#id_fact_espera").val();

            $.ajax({
                url: $url_actualizar_notas_factura_espera,
                type: "GET",
                dataType: "text",
                data: {
                    id: id,
                    nota: $("#nota_comanda").val()
                },
                success: function (data) {
                }
            });
        }
    }

    function consultar_notas_factura_espera(id){
        $.ajax({
            url: $url_notas_factura_espera,
            type: "GET",
            dataType: "json",
            data: {id: id},
            success: function (data) {
                $("#nota_comanda").val(data.nota);
            }
        });
    }

    function espera_cargar(id) {

        $.ajax({
            url: $url_detalles_factura_espera,
            type: "GET",
            dataType: "json",
            data: {id: id},
            success: function (data) {
                $("#listadoProdcutos").html('Listado Productos ');
                $('#productos-detail').html('<tr class="nothing"><td colspan="5">No existen elementos</td></tr>');
                $('#total-show').html('0.00');
                $('#subtotal').html('0.00');
                $('#iva-total').html('0.00');
                if (data != null && data.length > 0 ) {
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
                                data: {imp: data[i].impuesto},
                                success: function (data) {
                                    nom = data;
                                }
                            });
                        }

                        rowHtml = "<tr id='"+data[i].id_producto+"'>";
                        rowHtml += "<input type='hidden' class='precio-compra-real-selected' value='" + data[i].precio_venta + "'/><input type='hidden' class='vendernegativo_selected' value='" + data[i].vendernegativo + "'/><input type='hidden' class='tipo_producto_selected' value='" + data[i].tipo_producto + "'/><input type='hidden' class='stock_selected' value='" + data[i].stock + "'/><input type='hidden' value='" + data[i].id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' value='" + data[i].codigo_producto + "' data-cantidad='true'><input type='hidden' class='impuesto-final' value='" + data[i].impuesto + "'>";
                        rowHtml += "<td width='10%'><a class='button red delete' href='#'><div class='icon'><span class='wb-close'></span></div></a></td>";
                        rowHtml += "<td width='40%' style='text-align: left'><span class='title-detalle text-info'><input type='hidden' value='" + data[i].impuesto + "' class='detalles-impuesto'>" + data[i].nombre_producto + "</span></td>";
                        rowHtml += "<td width='10%'><span data-id='"+data[i].id_producto+"' data-stock='"+data[i].stock+"' data-vendernegativo='"+data[i].vendernegativo+"' data-tipo_producto='"+data[i].tipo_producto+"' data-imei='' class='label label-success cantidad'>" + data[i].unidades + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'></td>";
                        <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                            rowHtml += "<td width='20%' class='contCalc'><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
                        <?php } else { ?>
                            rowHtml += "<td width='20%' class='contCalc'><span class='label label-success precio-prod' onClick='calculadora_descuento(" + data[i].precio_venta + ");'>" + mostrarNumero(data[i].precio_venta) + "</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
                        <?php } ?>
                        rowHtml += "<td width='20%'><span class='precio-calc'>" + data[i].precio_venta + "</span><input type='hidden' value='precio-calc-real' value='" + data[i].precio_venta + "'/></td>";
                        rowHtml += "</tr>";
                        if ($("#productos-detail tr").eq(0).hasClass("nothing")) {
                            $("#productos-detail").html("");
                            $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                        } else {
                            $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
                        }

                        if(Number(data[i].id_mesa) > 0){
                            $("#listadoProdcutos").html('Listado Productos - MESA: '+data[i].nombre_mesa);
                        }else{
                            $("#listadoProdcutos").html('Listado Productos ');
                        }

                        $('#datos_cliente').val(data[i].cli_nom);
                        $('#id_cliente').val(data[i].id_clientes);
                        $('#id_fact_espera').val(data[i].venta_id);
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

        consultar_notas_factura_espera(id);
    }

    function renderFactura() {
        var id_producto = sProduct.id;
        var isGiftCard = sProduct.gc;
        var matching = $('.product_id[value="' + id_producto + '"]').index();
        var id_promocion = $('#promocion').val();

        //Validamos si tiene imei y ya esta listado
        var imei_repetido =-1;
        var tipo_producto_imei = 0;
        var indices = Object.keys(sProduct);
        var titulo_imei = '';
        if(indices.indexOf('serial') != '-1'){
            tipo_producto_imei = 1;
            imei_repetido = ($('.productoImei[value="' + sProduct.serial + '"]').index());
            titulo_imei = sProduct.serial;
        }

        $('#cod-stock').html(sProduct.stock_minimo);

        //imei_repetido = -1 -> aun no esta el imei en el listado
        //imei_repetido != -1 ->  el imei esta en el listado

        // matching = -1 -> aun no esta listado en la factura
        // matching =  1 -> = ya esta listado
        //alert("matching"+matching+ " - "+"imei repetido"+imei_repetido+ " - "+"tipo producto imei"+tipo_producto_imei);
        if (matching == -1 || (matching != -1 && imei_repetido == -1 && tipo_producto_imei == 1)) {
            var nom;
            if ($sobrecosto == 'si' && $nit != '320001127839') {

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
            sProduct.precio_venta = parseFloat(sProduct.precio_venta);
            var totalProducto = parseFloat(sProduct.precio_venta) + (parseFloat(sProduct.precio_venta) * parseFloat(sProduct.impuesto) / 100);

            sProduct.precio_venta = parseFloat(sProduct.precio_venta);
            sProduct.precio_venta = parseFloat(sProduct.precio_venta);
            rowHtml = "<tr id='"+id_producto+"'>";
            rowHtml += "<input type='hidden' class='vendernegativo_selected' value='"+sProduct.vendernegativo+"'><input type='hidden' class='tipo_producto_selected' value='"+sProduct.tipo_producto+"'><input type='hidden' class='stock_selected' value='"+sProduct.stock_minimo+"'><input type='hidden' class='precio-compra-real-selected' value='" + sProduct.precio_venta + "'/><input type='hidden' value='" + id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' data-cantidad='true' value='" + sProduct.codigo+ "'><input type='hidden' class='impuesto-final' value='" + sProduct.impuesto + "'>";

            rowHtml += "<td width='10%'><a class='button red delete' href='#'><div class='icon'><span class='wb-close'></span></div></a></td>";
            rowHtml += "<td width='40%' style='text-align: left !important;'><span class='title-detalle text-info'><input type='hidden' value='" + sProduct.impuesto + "' class='detalles-impuesto'>" + sProduct.nombre + "</span><span class='imei-title'>"+titulo_imei+"</span></td>";

            rowHtml += "<td width='10%'><span data-id='"+id_producto+"' data-stock='"+sProduct.stock_minimo+"' data-vendernegativo='"+sProduct.vendernegativo+"' data-tipo_producto='"+sProduct.tipo_producto+"' data-imei='"+titulo_imei+"' class='label label-success cantidad'>" + 1 + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'>";
            rowHtml += "<input type='hidden' class='promocionPrecio' value='0'><input type='hidden' class='promocionIva' value='0'>";
            rowHtml += "<input type='hidden' class='productoImei' value='"+sProduct.serial+"'></td>";

            if (validarProductoPromocion(id_promocion, $('.id_producto').eq($(this).index()).val()) && promocionTipo == 1)
            {
                <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                    rowHtml += "<td width='20%' class='contCalc'><input type='hidden' class='precio-prod-real' data-promocion='1' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' data-promocion='1' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/></td>";
                <?php } else { ?>
                    rowHtml += "<td width='20%' class='contCalc'><input type='hidden' data-promocion='1' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' data-promocion='1' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + (sProduct.precio_venta) + "</span></td>";
                <?php } ?>
            } else
            {
                <?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                    rowHtml += "<td width='20%' class='contCalc'><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/><span class='precio-prod'>" + mostrarNumero(sProduct.precio_venta) + "</span></td>";

                <?php } else { ?>
                    rowHtml += "<td width='20%' class='contCalc'><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + totalProducto + ");'>" + mostrarNumero(sProduct.precio_venta) + "</span></td>";
                <?php } ?>
            }
            rowHtml += "<td width='20%'><span class='precio-calc'>" + limpiarCampo(sProduct.precio_venta) + "</span><input type='hidden' class='precio-calc-real' value='" + sProduct.precio_venta + "'/></td>";

            rowHtml += "</tr>";
            var $objDom = null;

            if ($("#productos-detail tr").eq(0).hasClass("nothing")) {
                $("#productos-detail").html("");
                $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
            } else {
                $objDom = $(rowHtml).hide().prependTo("#productos-detail").fadeIn("slow");
            }

            // Si es giftcard ocultamos los botones de cambiar precio y cantidad de el listado de productos
            if (isGiftCard == "1") {
                $($objDom[0]).find(".cantidad").hide();
                $($objDom[0]).find(".precio-prod").hide();
            }

            // agregamos el si es giftcard a la lista de productos seleccionados
            $($objDom[0]).find(".product_id").after('<input type="hidden" class="giftcard" value="' + isGiftCard + '">');


        } else {


            if($("#"+id_producto).length){
                var cant = parseFloat($("#"+id_producto).find('.cantidad').text());
                /*if(sProduct.vendernegativo == 0 && (cant+1) > sProduct.uni ){
                    swal(
                        'Alerta',
                        'No posees stock para la venta de este producto7',
                        'warning'
                    )
                }else{*/
                    // Si es giftcard no se le permitira añadir mas productos
                    //alert(imei_repetido);

                    if(imei_repetido != -1 && tipo_producto_imei == 1){
                        alert('Este imei ya se encuentra asociado en la venta.');
                    }

                    if (isGiftCard == "0" && (imei_repetido == -1)) {
                        parent = $('.product_id[value="' + id_producto + '"]').parent().index();
                        cantidad = parseFloat($('.cantidad').eq(parent).text()) + 1;
                        $('.cantidad').eq(parent).text(cantidad);
                    }
                //}
            }
        }

        //calculate();
    }


    // Limpiamos la factura sin refrescar
    function limpiarVentas() {
        // Borramos la nota de la comanda
        $("#nota_comanda").val("");
        $("#tablaListaProductos tbody tr")
                .fadeOut("slow", function () {
                    $("#tablaListaProductos tbody").html("<tr class='nothing'><td colspan='5'>No existen elementos</td><tr>");
                    pasarPromocion();
                    calculate();
                    actualizarEspera();
                });
        //limpiar_mesas();
    }

    function load_provincias_from_pais(pais) {

        $.ajax({
            url: "<?php echo site_url("frontend/load_provincias_from_pais"); ?>",
            type: "GET",
            dataType: "json",
            data: {"pais": pais},
            success: function (data) {
                $("#provincia").html('');

                $.each(data, function(index, element){
                    provincia = "<?php echo set_value('provincia');?>"

                    sel = provincia == element[0] ? "selected='selected'" : '';

                $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                });
                /*if (pais != 'Colombia') {
                    $("#provincia").html('');
                }

                for (var i in data) {
                    provincia = "Bogota, D.C.";
                    sel = provincia == data[i].pro_nombre ? "selected='selectted'" : '';
                    $("#provincia").append("<option value='" + data[i].pro_nombre + "' " + sel + ">" + data[i].pro_nombre + "</option>");
                }*/
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


    function errorOffline(error) {
        //toastr.warning(error);
        $("#btnOffline i").css("color","red");
    }

    function equalProducts(cantidadProductosLocal) {

        if (cantidadProductosLocal != cantidadProductosProduccion) {
            //toastr.warning(' ¡ Ha modificado los productos, haga una copia local antes de vender ! <br><br><button type="button" id="btnBackupOffline" class="btn" style="margin: 0 8px 0 8px" onclick="backupOffline();">Hacer Backup</button>');
             $("#btnOffline i").css("color","red");
        }
    }


    function noLocalData() {
        //toastr.warning(' <strong>¡¡ ERROR !!</strong> <br>Base de datos no sincronizada, realice una copia <strong>inmediata</strong>.<br><br><button type="button" id="btnBackupOffline" class="btn" style="margin: 0 8px 0 8px" onclick="backupOffline();">Hacer Backup</button>');
         $("#btnOffline i").css("color","red");
    }

    function backupOffline() {
        window.location = "<?php echo site_url(); ?>/frontend/borrarOffline/";
    }


    function poner_venta_en_espera(id_mesa_seleccionada ){
        id_mesa_seleccionada = (typeof id_mesa_seleccionada !== 'undefined') ?  id_mesa_seleccionada : 0;
        var productos_list_venta_espera = [];
        var pago_seleccionado = {
                valor_entregado: 0,
                cambio: 0,
                forma_pago: '',
            };
        // Si venimos de otra factura en espera, primero reseteamos
        if ($("#id_fact_espera").val() != "") {
            resetFacturaEspera();
        }

        $(".title-detalle").each(function (x) {
            var descuento = 0;
            descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
            if (parseFloat($(".precio-prod-real-no-cambio").eq(x).val()) < parseFloat($(".precio-prod-real").eq(x).val())) {
                descuento = 0;
            }

            productos_list_venta_espera[x] = {
                'codigo': $('.codigo-final').eq(x).val(),
                'precio_venta': (descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),
                'unidades': parseFloat($(".cantidad").eq(x).text()),
                'impuesto': $(".impuesto-final").eq(x).val(),
                'nombre_producto': $(".title-detalle").eq(x).text(),
                'product_id': $(".product_id").eq(x).val(),
                'linea': $(".nombre_impuesto").eq(x).val(),
                'descuento': descuento,
                'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseFloat($(".cantidad").eq(x).text()),
            };

        });

        if(productos_list_venta_espera.length > 0 || Number(id_mesa_seleccionada) > 0){
            
            $.ajax({
                url: $sendventas_espera,
                dataType: 'json',
                type: 'POST',
                data: {
                    cliente: $("#id_cliente").val(),
                    productos: productos_list_venta_espera,
                    vendedor: $("#vendedor").val(),
                    total_venta: $("#total").val(),
                    pago: pago_seleccionado,
                    nota: $("#nota_comanda").val(),//, nota: $("#notas").val()
                    sobrecostos: $("#sobrecostos_input").val(),
                    id_mesa_seleccionada:id_mesa_seleccionada
                },
                success: function (data) {
                    if (data.success == true) {
                        $("#pendiente").prop('disabled',false);
                        var idComandaResult = data.id;
                        var creada_comanda = data.creada_comanda;
                        repintar_ventas_espera(idComandaResult,creada_comanda);
                        if(Number(id_mesa_seleccionada) >0 ){
                            $("#id_fact_espera").val(data.id);
                            $("#id_fact_espera_nombre").val(data.nombre);
                        }else{
                            $("#id_fact_espera").val('');
                            $("#id_fact_espera_nombre").val('');
                        }

                        $("#datos_cliente").val('');
                        $("#id_cliente").val('');
                        $('#productos-detail').html('<tr class="nothing"><td colspan="5">No existen elementos</td></tr>');
                        $('#total-show').html('0.00');
                        $('#subtotal').html('0.00');
                        $('#iva-total').html('0.00');
                        $("#cantidad-total").html('0');
                        $("#pendiente").prop('disabled',false);
                    }
                    
                }
            });

        }else{
            //alert('No hay productos seleccionados para enviar a venta en espera');
            swal({
                position: 'center',
                type: 'error',
                title: 'No hay productos seleccionados para enviar a venta en espera',
                showConfirmButton: false,
                timer: 1500
            })
            $("#pendiente").prop('disabled',false);
        }

    }




    function repintar_ventas_espera(idComandaResult,creada_comanda){
        idComandaResult = (typeof idComandaResult !== 'undefined') ?  idComandaResult : 0;
        creada_comanda = (typeof creada_comanda !== 'undefined') ?  creada_comanda : false;
        $.ajax({
            url: $url_consultar_ventas_espera,
            type: "GET",
            dataType: "json",
            data: {id: 1},
            success: function (data) {
                if (data != null) {
                    facturas_en_espera['nuevo']= [];
                    facturas_en_espera['comparar'] = [];
                    pedidos_mesas['nuevo'] = [];
                    pedidos_mesas['comparar'] = [];
                    rowHtml = '';
                    for (var i in data)
                    {
                        if (data[i].id !== '-1' && (data[i].usuario_id == id_usuario_js || es_usuario_administrador)) {
                            if (data[i].id == idComandaResult) {
                                var nombreComandNew = data[i].factura;
                                agregarComanda(idComandaResult, nombreComandNew);
                            }
                            var key = data[i].id;
                            if(Number(data[i].id_mesa)){
                                    pedidos_mesas['comparar'][key] = data[i];
                                    if(pedidos_mesas[key] === undefined){
                                        pedidos_mesas['nuevo'][key] = data[i];
                                    }else{
                                        $("#btn_mesa_en_espera_"+data[i].id).text(data[i].nombre_seccion+'-'+data[i].factura);
                                    }

                            }else{
                                    facturas_en_espera['comparar'][key]=data[i];
                                    if(facturas_en_espera[key] === undefined){
                                        facturas_en_espera['nuevo'][key] = data[i];
                                    }else{
                                        $("#btn_venta_espera_"+data[i].id).text(data[i].factura);
                                    }

                            }

                        }
                    }
                    pintar_botones_ventas_espera();
                    pintar_html_secciones_espera();
                    eliminar_texto_secciones_mesas_vacias();
                } else {
                   // $("#datos_cliente").val('');
                }
            }
        });
    }

    function pintar_botones_ventas_espera(){
        rowHtml = '';
        chequear_estado_ventas_espera();
        zona = '<?php echo $this->uri->segment(3); ?>';
        mesa ='<?php echo $this->uri->segment(4); ?>';

        if(zona=="" && mesa==""){
            $.each(facturas_en_espera['nuevo'],function(key,value){
                if(value !== undefined){
                    rowHtml+= "<div data-id_venta_espera='"+value.id+"' class='btn div_factura_espera' id='btn_venta_espera_" +value.id + "' onclick='espera_cargar(" + value.id + "); cambiar_color(" + value.id + ");' >" + (value.factura).split('-')[0] + "</div> ";
                    facturas_en_espera[value.id] = value;
                }

            });
            $("#botones").append(rowHtml);
        }

    }

    function chequear_estado_ventas_espera(){
        $.each(facturas_en_espera,function(key,value){
            if(String(key)!=='nuevo' && value !== undefined){
                if(facturas_en_espera['comparar'][value.id] == undefined){
                    $('#btn_venta_espera_' + value.id + '').popover('hide');
                    facturas_en_espera[value.id] = undefined;
                    $("#btn_venta_espera_"+value.id).remove();
                }
            }
        });
    }

    function chequear_estado_mesas_espera(){
        $.each(pedidos_mesas,function(key,value){
            if(String(key)!=='nuevo' && value !== undefined){
                if(pedidos_mesas['comparar'][value.id] == undefined){
                    $("#btn_mesa_en_espera_"+value.id).popover('hide');
                    pedidos_mesas[value.id] = undefined;
                    $("#btn_mesa_en_espera_"+value.id).remove();
                    eliminar_texto_secciones_mesas_vacias();
                }
            }
        });
    }

    function pintar_html_secciones_espera(){
        var secciones_almacen = [];
        chequear_estado_mesas_espera();
        $.each(pedidos_mesas['nuevo'],function(key,value){
            if(value !== undefined){
                if(secciones_almacen[value.id_seccion] !== undefined ){
                    secciones_almacen[value.id_seccion]['mesas'][value.id] = value;
                }else{
                    secciones_almacen[value.id_seccion]=[] ;
                    secciones_almacen[value.id_seccion]['nombre_seccion']=value.nombre_seccion;
                    secciones_almacen[value.id_seccion]['mesas']=[];
                    secciones_almacen[value.id_seccion]['mesas'][value.id]=value;

                }
            }
        });
        var html_secciones_en_espera = '';

        $.each(secciones_almacen,function(key,value){
            if(value !== undefined){

                html_btn_mesa_espera = armar_botones_html_mesas_espera(value.mesas);
                if($("#div_contenedor_mesas_seccion_"+key).length){
                    $("#div_contenedor_mesas_seccion_"+key).append(html_btn_mesa_espera);

                }else{
                    html_secciones_en_espera+='<div class="div_contanedor_mesas_seccion" id="div_contenedor_mesas_seccion_'+key+'">';
                    /*html_secciones_en_espera+='<div class="row-fluid"><center><h5>'+value.nombre_seccion+'</h5></center></div>';*/
                    html_secciones_en_espera+=html_btn_mesa_espera;
                    html_secciones_en_espera+='</div>';

                }
            }
        });
        $("#div_contenedor_mesas_en_espera").append(html_secciones_en_espera);
    }

    function armar_botones_html_mesas_espera(mesas_recorrer){
        html_btn_mesa_espera ='';
         $.each(mesas_recorrer,function(key_mesas,mesas){
            if(mesas !== undefined){
                var nombre_seccion= mesas.nombre_seccion;
                var nombre_mesa_array = mesas.factura.split('-');
                var color_fondo = '#005683 ';
                var color_border = '#005683 ';

                if(mesas.estado_comanda){
                    color_fondo = '#fd9107 '
                    color_border = '#fd9107 '
                }
                html_btn_mesa_espera+= '<div data-id_venta_espera="'+mesas.id+'" style="padding: 1px 5px; height:50px;width:auto; border-color: '+color_border+' !important; background-color:'+color_fondo+' !important; vertical-align: text-bottom;margin:5px;min-width:50px;"  class="btn btn_mesas_en_espera" onClick="espera_cargar('+mesas.id+')" id="btn_mesa_en_espera_'+mesas.id+'"><center>'+nombre_seccion+'-'+mesas.factura+'</center></div>';
                pedidos_mesas[mesas.id]=mesas;
            }
        });
        return html_btn_mesa_espera;
    }

    function eliminar_texto_secciones_mesas_vacias(){
        $.each($(".div_contanedor_mesas_seccion"),function(key,value){
            if($(this).has("div.btn_mesas_en_espera").length ==0 ){
                $(this).remove();
            }
        })
    }

    function limpiar_mesas(){
        id_mesa_seleccionada = 0;
        nombre_mesa = '';
        $("#listadoProdcutos").html('Listado Productos');
    }


    function ocultar_popover_facturas_espera(capa_evento){
        $.each($("."+capa_evento),function(index,capa){
            if ($(capa).next('div.popover:visible').length){
                var valor = $(capa).attr('id');
                $("#"+valor).popover('hide');
            }
        });
    }

    function programar_consulta_ventas_espera(){
        repintar_ventas_espera()
    }

    function mostrar_popover_facturas_en_espera(capa_evento,origen){

        var id_capa = $(capa_evento).attr('id');
        var id_factura_espera = $(capa_evento).attr('data-id_venta_espera');

        if(String(origen) ==='factura_espera'){
            ocultar_popover_facturas_espera('div_factura_espera');
            propoverContent = "<div style='padding:10px'>" +
                            "<input type='text' class='spinner' name='cantidad_input' value='" + $('#' + id_capa + '').text() + "'/>" +
                            "</div>" +
                "<button data-id='"+id_factura_espera+"' type='button' data-id_factura='"+id_factura_espera+"'  class='btn btn_cancel_cantidad btn-default text-right' style='float:right;'><span class='wb-close'></span></button>" +
                "<button type='button' data-id_factura='"+id_factura_espera+"'   class='btn btn-success btn_accept_cantidad text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>";
        }else{
            ocultar_popover_facturas_espera('btn_mesas_en_espera');
            propoverContent = "<button data-id='"+id_factura_espera+"' type='button' data-id_factura='"+id_factura_espera+"' style='float:right;' class='btn btn_cancel_cantidad btn-default' ><span class='icon-remove'></span></button><button type='button' data-id_factura='"+id_factura_espera+"'   class='btn btn_imprimir_comanda text-right' style='float:right; background-color: #fff' onclick='imprimirComanda()'><span class='icon wb-print' style='color: #505050;'></span></button>";
        }


        $('#' + id_capa + '').popover({
            placement: 'top',
            html: true,
            content: propoverContent,
            trigger: 'manual',
        }).popover('show');
    }

     $(".delete").live("click", function () {

        $(this).parent().parent().remove();
        if ($("#tablaListaProductos tbody tr").length == 0) {
            $("#tablaListaProductos tbody").html("<tr class='nothing'><td colspan='5'>No existen elementos</td><tr>");
        }
        pasarPromocion();
        calculate();
        actualizarEspera();
    });

    $(".div_factura_espera").live("touchstart dblclick",function(){
        mostrar_popover_facturas_en_espera(this,'factura_espera');
     });

    $(".btn_mesas_en_espera").live("touchstart dblclick",function(){
        mostrar_popover_facturas_en_espera(this,'mesas_espera');
    });

    $(".btn_accept_cantidad").live('click',function(){
        var valor = $(this).attr('data-id_factura');
        var nom = $('.spinner').val();
        cambiarNombreComanda(valor, nom);
        cantidadField = $("#btn_venta_espera_"+valor);
        $.ajax({
            url: "<?php echo site_url("ventas/factura_espera_nombre"); ?>",
            type: "GET",
            dataType: "json",
            data: {
                nom: $('.spinner').val(),
                id: valor
            },
            success: function (data) {
                //alert(data);
                if (data == '1') {
                    cantidadField.html( cantidadField.text());

                }
                if (data == '0') {
                    cantidadField.html( nom);
                }
                $("#btn_venta_espera_"+valor).popover('hide');
            }
        });
        $('#id_fact_espera_nombre').val(nom);
    });

    $(".btn_cancel_cantidad").live('click',function(){
         // Eliminamos obj del listado de comandas
        var valor = $(this).attr('data-id_factura');

        eliminarComanda(valor);
        $.ajax({
            url: "<?php echo site_url("ventas/factura_espera_eliminar"); ?>",
            type: "GET",
            async: false,
            dataType: "json",
            data: {id: valor},
            success: function (data) {
                $('#btn_venta_espera_' + valor + '').popover('hide');
                $("#btn_mesa_en_espera_"+valor).popover('hide');
                facturas_en_espera[valor] = undefined;
                pedidos_mesas[valor] = undefined;
                $("#btn_venta_espera_"+valor).remove();
                $("#btn_mesa_en_espera_"+valor).remove();
                capa_seccion_mesas = $("#btn_mesas_en_espera_"+valor).parent();
                eliminar_texto_secciones_mesas_vacias();
                repintar_ventas_espera();
            }
        });


        $("#id_fact_espera").val('');
        $("#id_fact_espera_nombre").val('');
        $("#datos_cliente").val('');
        $("#id_cliente").val('');
         $("#listadoProdcutos").html('Listado Productos');
        $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
        $('#total-show').html('0.00');
        $('#subtotal').html('0.00');
        $('#iva-total').html('0.00');
        $("#cantidad-total").html('0');

    });


/***division cuenta */
    /* total factura dividida*/
    //totaldivision
    var totaldivisionaf=0;
    $(".checkdivision").live("change", function (e) {

        id_orden=$(this).val();
        //cant=parseFloat($('#cantidad_division_'+id_orden).attr('data-cant'));
        cantp=parseFloat($('#cantidad_division_'+id_orden).attr('value'));
        totaldivision=0;
         //obtengo precio producto seleccionado para division
        preciopd=parseFloat($("#precio_"+id_orden).attr('data-precio'));

        //obtengo total factura division
        if (this.checked) {
            totaldivision=(parseFloat(totaldivisionaf)+(parseFloat(preciopd)*cantp));
        }
        else{
             totaldivision=(parseFloat(totaldivisionaf)-(parseFloat(preciopd)*cantp));
        }

        if(__decimales__==0){
            totaldivisionaf=Math.round(totaldivision);
        }else{
            totaldivisionaf=totaldivision;
        }

        $("#totaldivision").html("Total: $"+totaldivisionaf);

    });


    $(".number").live("click", function (e) {
        btn=$(this);
        id=$(this).attr('data-dir');
        input = btn.closest('.input-group').find('input');

        if (btn.attr('data-dir') == 'up') {
            if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.val(parseInt(input.val()) + 1);
                btn.prop("disabled", false);
            } else {
                btn.prop("disabled", true);
            }

        } else {
            if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.val(parseInt(input.val()) - 1);
                btn.prop("disabled", false);
            } else {
                btn.prop("disabled", true);
            }
        }

        preciopd=0;
        totaldivisionaf=0;

        $("#tabledivision tbody").find("input:checkbox:checked").each(function() {
            totaldivision=0;
            id_orden=$(this).val();
            cant=parseFloat($('#cantidad_division_'+id_orden).attr('data-cant'));
            cantp=parseFloat($('#cantidad_division_'+id_orden).attr('value'));
            preciopd=parseFloat($("#precio_"+id_orden).attr('data-precio'));

            if (this.checked) {
                if(__decimales__==0){
                    totaldivision=Math.round(preciopd*cantp);
                }
                else{
                    totaldivision=((preciopd)*cantp);
                }
                totaldivisionaf+=totaldivision;
            }

            $("#totaldivision").html("Total: $"+totaldivisionaf);

        });

    });

    $("#modal-division-cuenta1").click(function(e){

        zona = '<?php echo $this->uri->segment(3);?>';
        mesa = '<?php echo $this->uri->segment(4);?>';
        url= "<?php echo site_url(); ?>/orden_compra/getOrden";
        var body="";
        $('#tabledivision tbody').html(body);
        $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:{zona:zona,mesa:mesa},
            success: function(data){
                $.each(data.orden,function(key,value){
                    if((value.estado == 2)||(value.estado == 3)){
                       // totalvalor=value.precio_ventaptotal;
                        adiciones="";
                       // totaladiciones=0;
                        body+="<tr><th scope='row'>"+value.nombre;

                        if(value.modificacion != null){
                            body+="<br>Modificaciones("+value.modificacion+")";
                        }
                        cantadi=value.adicionales.length;
                        if(cantadi>0){
                            if(value.adicionales != null){
                                body+=" Adiciones(";
                                $.each(value.adicionales,function(key,val){
                                    adiciones+=val.nombre+",";
                                   // totaladiciones+=parseFloat(val.precio_venta);
                                });
                                adiciones=adiciones.slice(0,-1);
                                body+=adiciones+")";
                            }
                        }

                       body+="</th><td><div class='input-group'><span class='input-group-btn'><button class='btn btn-default btn-success number' data-dir='dwn' type='button'>-</button></span><input type='number'  id='cantidad_division_"+value.id+"' class='form-control cantidad_division text-center' disabled required='required' data-cant='"+value.cantidad+"' value='"+value.cantidad+"' min='1' max='"+value.cantidad+"'><span class='input-group-btn'><button class='btn btn-default btn-success number' data-dir='up' type='button'>+</button></span></div></td>";
                       body+="<td data-precio='"+value.precio_ventaputotalsin+"' id='precio_"+value.id+"'>"+value.precio_ventaputotal+"</td><td><input type='checkbox' name='dividirproducto' class='checkdivision' value='"+value.id+"'></td></tr>";

                    }
                });
                $('#tabledivision tbody').append(body);
                $("#totaldivision").html("Total: $0");
                $('#modal-division-cuenta').modal('show');
            }
        });
    })

    $("#modal-division-cuenta").on('hidden.bs.modal', function () {
        totaldivisionaf=0;
    });
    /**dividir  */

    $(".btn_dividir_cuenta").click(function(e){
        zona = '<?php echo $this->uri->segment(3);?>';
        mesa = '<?php echo $this->uri->segment(4);?>';
        urldiv= "<?php echo site_url(); ?>/ventas/dividir_cuenta";
        band=false;
        hay=true;
        $("#tabledivision tbody").find("input:checkbox:checked").each(function() {
            id_orden=$(this).val();
            cant=parseFloat($('#cantidad_division_'+id_orden).attr('data-cant'));
            cantp=parseFloat($('#cantidad_division_'+id_orden).attr('value'));

            if(isNaN(cantp)){

                band=false;
                hay=false;
                return;
            }else {
                if(cantp>cant) {
                    band=false;
                    hay=false;
                    return;
                }else{
                    if(cantp<=0){
                        band=false;
                        hay=false;
                        return;
                    }else{
                        if(hay){
                            band=true;
                        }

                    }
                }
            }

        });

        if(band){
           $("#tabledivision tbody").find("input:checkbox").each(function() {
               id_orden=$(this).val();
                if (!this.checked) {
                    $.get(
                        urldiv,
                        { id: id_orden, id_mesa:mesa, id_zona:zona }
                    );
                }
                else{
                    cant=parseFloat($('#cantidad_division_'+id_orden).attr('data-cant'));
                    cantp=parseFloat($('#cantidad_division_'+id_orden).attr('value'));
                    if((cantp<cant)&&(cantp>0) &&(cantp !='')){
                        $.get(
                            urldiv,
                            { id: id_orden, id_mesa:mesa, id_zona:zona, cantp:cantp}
                        );
                    }
                }

            });
            setTimeout(function(){ location.reload(true); }, 1000);
        }
        else{
            alert("Seleccione los productos a facturar \n ó Valide las Cantidades de los productos a Facturar");
        }
    });

    $('.responsive').slick({
        dots: false,
        infinite: true,
        speed: 300,
        prevArrow: '<div class="slick-prev" style="margin-top: -20px !important;"><img style="width: 15px; height: 15px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
        nextArrow: '<div class="slick-next" style="margin-top: -20px !important;"><img style="width: 15px; height: 15px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });


    $('.multiple-items1').slick({
        dots: false,
        infinite: true,
        speed: 300,
        prevArrow: '<div class="slick-prev"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
        nextArrow: '<div class="slick-next"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    function selectCategory(idCategory) {
        $('.category-option').removeClass().addClass( 'category-option' );
        $('#' + idCategory).addClass( 'activeCategory' );
    }

     $(".domiciliarios-option").click(function() { 
        
        id=$(this).data('id');        
        btn=$(this);
        $("#domiciliario").val(id);
        //busco los demas img y cambio de tamaño
        $(".domiciliarios-option").each(function(index) {
            
            btn2=$(this);
            img2 = btn2.find('.cuadro_domiciliarios'); 
            img2.css('border','1px solid #ddd8d8');
        });

        img = btn.find('.cuadro_domiciliarios'); 
        img.css('border','1px solid #5ca745');
     
    });

    var usersPuntosLeal = [];
    var userPuntosLeal = null;
    var userPuntosLealSelected = null;

    $("#searchPuntosLeal").click(function() {
        let id = $("#id_puntos_leal").val();

        if (id !== "") {
            $("#listPuntosLeal").empty();
            $.ajax({
                url: '<?php echo site_url('clientes/search_puntos_leal'); ?>',
                data: {
                    id: $("#id_puntos_leal").val()
                },
                dataType: 'json',
                type: 'POST',
                success: function (data) {
                    usersPuntosLeal = data;

                    $.each(data, function(index, element){
                        $("#listPuntosLeal").append(`<div onclick="selectUserPuntosLeal(${element.cedula}, ${index})" class="user-puntos-leal ${element.cedula}" style="cursor: pointer; border-bottom: 1px #CCC solid; height: 42px; align-items: center; display: flex;"><div class="col-xs-12">${element.cedula} - ${element.fullname}</div></div>`);
                    });
                }
            });
        }
    });

    function selectUserPuntosLeal(id, index) {
        userPuntosLeal = usersPuntosLeal[index];
        $(`.user-puntos-leal`).removeClass('user-puntos-leal-selected');
        $(`.${id}`).addClass('user-puntos-leal-selected');
    };

    function unselectUserPuntosLeal() {
        userPuntosLealSelected = null;
        $("#selectedPuntosLeal").empty();
    };
</script>
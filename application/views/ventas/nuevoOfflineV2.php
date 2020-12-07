
<script type="text/javascript"> var client = <?php echo json_encode($data['clientes']) ?></script>
<script type="text/javascript"> var __decimales__ = <?php echo json_encode($data['__decimales__']) ?></script>
<script type="text/javascript"> var __separadorDecimal__ = <?php echo json_encode($data['__separadorDecimal__']) ?></script>
<script type="text/javascript"> var __separadorMiles__ = <?php echo json_encode($data['__separadorMiles__']) ?></script>
<script type="text/javascript"> var __redondear__ = <?php echo json_encode($data['__redondear__']) ?></script>
<script type="text/javascript"> var descuentogeneral=0; var cantidadsindescuentogeneral=0;</script>



<script type="text/javascript">
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
    function ocultar(id) {
        document.getElementById(id).style.display = 'none';

        if (id == 'contenido_a_mostrar1') {
            $("#valor_entregado1").removeAttr("disabled");
            document.getElementById('valor_entregado1').value = '0';
        }

        if (id == 'contenido_a_mostrar2') {
            $("#valor_entregado2").removeAttr("disabled");
            document.getElementById('valor_entregado2').value = '0';
        }

        if (id == 'contenido_a_mostrar3') {
            $("#valor_entregado3").removeAttr("disabled");
            document.getElementById('valor_entregado3').value = '0';
        }

        if (id == 'contenido_a_mostrar4') {
            $("#valor_entregado4").removeAttr("disabled");
            document.getElementById('valor_entregado4').value = '0';
        }

        if (id == 'contenido_a_mostrar5') {
            $("#valor_entregado5").removeAttr("disabled");
            document.getElementById('valor_entregado5').value = '0';
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
        background-color: #ffffff;
    }

    #cod-container:hover{        
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
        background: #f7f7f7;
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
        margin-top: 30px;
        cursor:pointer; cursor: hand;
        width: 134px;
        height: 155px;
    }

    .vitrina-item:hover{
        -webkit-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        -moz-box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
        box-shadow: 1px 0px 5px 0px rgba(117,117,117,0.5);
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
        width: 70px;
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
    
    #dialog-internet, #dialog-sincronizando{
        display: none;
    }
    
    #nav-categoria li{
        width: 70px;    
    }

</style>

<?php if($data["num"] == 2){ ?>
    <div>
        <h3>Bienvenido al sistema Offline</h3>
    </div>
<?php }else{ ?>
    
<div class="content" >
    <input type="hidden" id="subtotal_input">
    <input type="hidden" id="subtotal_propina_input">
    <div class="row-fluid">

        <!--Derecha-->

        <div class="span7">

            <div class="block">

                <div class="head" style="text-align: center;">
                    <!-- 
                                        <div class="icon"><span class="ico-arrow-right"></span></div><br/><h2>Nueva venta</h2> -->

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
                                    <img src="<?php echo base_url("/public/img/"); ?>/buscador.png" width="45px" height="15px" />&nbsp;&nbsp; BUSCADOR </h3><!--  <img src="<?php //echo base_url("/public/img/");  ?>/codigobarra-icon.png">  --></li>
                                <li id ='codificalo' <?php
                                if ($_REQUEST['var'] == "codificalo") {
                                    echo "class='active'";
                                }
                                ?> > <h3>
                                        <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
                                        <img src="<?php echo base_url("/public/img/"); ?>/codigo_barra.png" width="40px" height="15px" />
                                        &nbsp;&nbsp; LECTOR </h3> </li>
                                <li id ='navegador'  <?php
                                if ($_REQUEST['var'] == "navegador") {
                                    echo "class='active'";
                                }
                                ?> > <h3>
                                        <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
                                        <img src="<?php echo base_url("/public/img/"); ?>/navegador.png" width="40px" height="15px" />
                                        &nbsp;NAVEGADOR</h3>  </li>
                            </ul>
                        </form>

                    </div>

                </div>


                <div id='search-container' class="input-append">

                    <input type="text" name="text" class="span12" placeholder="Digite producto a buscar..." id="search" autofocus="autofocus" style="width: 540px;"/>

                    <button class="btn btn-success" id="faqSearch" type="button"><span class="icon-search icon-white"></span></button>

                </div>     

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
                                <li id="0" onclick="filtrarCategoria(this)"><img src="<?php echo base_url("/uploads/") . '/vendtyShop.jpg'; ?>"><br>Todos</li>
                                <?php
                                $i = 0;

                                foreach ($data['categorias'] as $key => $value) {
                                    if ($i == 0)
                                        echo '<li id="' . $value->id . '" onclick="filtrarCategoria(this)" ><img src="' . base_url("/uploads/") . '/vendtyShop.jpg"><br>' . $value->nombre . '</li>';
                                    else
                                        echo '<li id="' . $value->id . '" onclick="filtrarCategoria(this)" ><img src="' . base_url("/uploads/") . '/vendtyShop.jpg"><br>' . $value->nombre . '</li>';
                                    $i++;
                                }
                                ?>
                            </ul>
                            <div id='next' class='btn-control' onclick='siguiente_categorias()'>
                                <div id='next-triangulo'></div>
                            </div>
                        </div>


                        <div id="vitrina" class="panel newPanel">
                        </div>

                    </div>
                </div>

                <div id='cod-container'>
                    <div id='cod-item'>
                        <img id='cod-img' src="<?php echo base_url("/uploads/"); ?>/product-dummy.png">  
                        <h5 id='cod-nombre'></h5>
                        <strong>Cod: </strong><span id='cod'></span><br>
                        <strong>Stock: </strong><span id='cod-stock'></span><br>
                        <span id='ubic'></span>
                        <h5 ><span id='cod-precio-impuesto'></h5></span><br>

                    </div>
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
                          <td width='20%'><img  src='imagen.png' class='grid-image'></td>
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

        <div class="span5" style="margin-top: 10px;">

            <div class="block panel newPanel newContPrecio">                    

                <div class="head green well" style="color: #fff; font-size: 55px;  padding-top:20px; padding-bottom:20px; margin-bottom: 8px;">


                    <input type="hidden" value="0" id="total"/>
                    <table width="100%" >
                        <tr>
                            <td align="left"> <span > $  </span></td>
                            <td align="center"><span  style="font-size: 55px;" class="label label-info" id="total-show"> 0.00</span></td>                   </tr>                     
                    </table>
                </div>

                <div class="data-fluid">

                    <div style="height:200px;overflow:auto; width:100%">

                        <table cellpadding="0" cellspacing="0" width="100%" class="table  dtable lcnp">

                            <tbody height="50px" id="productos-detail">



                                <tr class="nothing">

                                    <td>No existen elementos</td>

                                </tr>                     

                            </tbody>

                            <?php
                            $permisos = $this->session->userdata('permisos');

                            $is_admin = $this->session->userdata('is_admin');
                            // foreach($data['espera_detalles'] as $f){ ?>  
                             <!-- <tr><td><input type='hidden' class='precio-compra-real-selected' value='qr'/><input type='hidden' value='qr' class='product_id'/><input type='hidden' class='codigo-final' value='qr'><input type='hidden' class='impuesto-final' value='qr'><span class='title-detalle text-info'><input type='hidden' value='qr' class='detalles-impuesto'>qr</span></td>
                              <td><span class='label label-success cantidad'>qr</span></td>
                              <td><span class='label label-success precio-prod'>qr</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='qr'/><input type='hidden' class='precio-prod-real-no-cambio' value='qr'/></td>
                              <td><span class='precio-calc'>qr</span><input type='hidden' value='precio-calc-real' value='qr'/></td>
                              <td><td><a class='button red delete' href='#'><div class='icon'><span class='ico-remove'></span></div></a></td></td>
                              </tr> -->
                              <?php //}  ?>  

                        </table>

                    </div>

                    <table cellpadding="0" cellspacing="0" width="100%" class="table" style=" margin-top: 8px;">

                        <tr>

                            <td>IVA:</td>

                            <td style="background-color: #E9E9E9; text-align: right;"><span id="iva-total">0.00</span></td>

                            <td style="text-align: right;">Subtotal:</td>

                            <td style="background-color: #E9E9E9; text-align: right; font-weight: bold;">$ <span id="subtotal">0.00</span></td>

                        </tr>

                    </table>

                    <!-- <table height="7px"><tbody><tr><td></td></tr></tbody></table>   -->


                    <table width="100%" style="padding-top:5px; border-bottom:5px; background: #fff !important;" >
                        <tr>
                            <td class="head green" width="25%"> <div class="icon"><span class="ico-user"></span></div>
                                <span style="color: #fff; font-size: 17px;">
                                    <b class="newTexto">Cliente:</b>
                                </span>
                            </td>
                            <td style="padding-right:6px;">

                                <input class="newTexto" type="text"  value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente" id="datos_cliente" style="width: 200px; height: 31px;margin-top: 4px;"/>

                                <a href="javascript:void(0)" class="btn" id="add-new-client" style="">
                                    <small class="ico-plus icon-white" style=" margin-top: 3px;"></small> 
                                    Nuevo cliente
                                </a>

                            </td>

                        </tr>
                    </table> 



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


                    <!-- <table height="7px"><tbody><tr><td></td></tr></tbody></table>-->


                    <table  width="100%" style="padding-top:5px; border-bottom:5px; background: #fff !important;" >
                        <tr>
                            <td  class="head green" width="25%" style="margin-top:0px;">
                                <div class="icon"><span class="ico-user"></span></div>
                                <span style="color: #fff; font-size: 17px;">
                                    <b class="newTexto">Vendedor:</b>
                                </span>
                            </td>
                            <td style="padding-right:6px;">
                                <input class="newTexto" type="text" value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor" id="datos_vendedor" style="width: 200px; height: 31px;margin-top: 4px;"/>
                                <input type="hidden" name="vendedor" id="vendedor" style="width: 200px;height: 25px;"/>
                            </td>
                        </tr>
                    </table> 

<!-- <table height="14px"><tbody><tr><td></td></tr></tbody></table> -->

                    <div class="row-form" id="vendedorBlock">

                        <div class="span3">Nombre:</div>

                        <div class="span9">                                    



                        </div>

                    </div>

                </div>

                <div style=" text-align: center; margin-top: 10px;">

                    <button class="btn btn-inverse btn-large" id="pagar" style=" padding: 5px 50px 5px 50px; ">
                        <span class="ico-money"></span>
                        Pagar 
                    </button>

                    <a id="cancelarVenta" href="<?php echo site_url("ventas/nuevo"); ?>" class="btn btn-danger btn-large" style="float: right !important;">
                        <span class="ico-remove"></span>
                        Cancelar                    
                    </a>                 

                </div>
                <!--
                
                <button class="btn btn-inverse btn-large" id="pagar">
                    <span class="ico-money"></span>
                    Pagar 
                </button>

                <button class="btn btn-primary btn-large" id="nota">
                    <span class="ico-pencil"></span>
                    Nota
                </button>

                <?php if ($data['sobrecosto'] == 'si') { ?>
                        <button class="btn btn-primary btn-large" id="sobrecosto">
                            <span class="ico-pencil"></span>
                            Sobrecosto
                        </button>					
                <?php } ?>			


                <?php if ($data['comanda'] == 'si') { ?>
                        <button class="btn btn-primary btn-large" id="comanda">
                            comanda
                        </button>					
                <?php } ?>						
                
                -->

                <!--  <button class="btn btn-inverse btn-large" id="pendiente">
                         <span class="ico-money"></span>
                         Pendiente
                 </button> -->

                <!--
                <a id="cancelarVenta" href="<?php echo site_url("ventas/nuevo"); ?>" class="btn btn-danger btn-large" >
                    <span class="ico-remove"></span>
                    Cancelar                    
                </a>
                
                -->

            </div>

            <input type="hidden" name="id_fact_espera" id="id_fact_espera" style="width: 260px;height: 25px;"/>

            <input type="hidden" name="id_fact_espera_nombre" id="id_fact_espera_nombre" style="width: 260px;height: 25px;"/>	



            <table height="28px"><tbody><tr><td></td></tr></tbody></table>    


            <div id="botonesVenta" class="clearBoth panel newPanel" style=""> 

                <?php if ($data['nit'] == '320001127839dcf' || $this->session->userdata('base_dato') == 'vendty2_db_1542_venta2015' || $this->session->userdata('base_dato') == 'vendty2_db_5550ee88dc62b') { ?>

                <button class="btn btn-primary btn-large" id="planSepare" style=" visibility: hidden">
                        <span class="ico-pencil"></span>
                        Plan Separe
                    </button> 

                <?php } ?>	

                <?php if ($data['sobrecosto'] == 'si') { ?>
                    <button class="btn btn-primary btn-large" id="sobrecosto">
                        <span class="ico-pencil"></span>
                        Sobrecosto
                    </button>					
                <?php } ?>			


                <?php if ($data['comanda'] == 'si') { ?>
                    <button class="btn btn-primary btn-large" id="comanda" style=" visibility: hidden">
                        comanda
                    </button>					
                <?php } ?>						               


                <button class="btn btn-primary btn-large" id="nota">
                    <span class="ico-pencil"></span>
                    Nota
                </button>


            </div>

            <div id="botonesEspera"  class="clearBoth panel newPanel"> 
                <table>
                    <tr>
                        <td style=" width:43%">&nbsp;</td><td style="width:410px;">  
                            <button style="border: 0; vertical-align: middle; height:28px; padding: 4px 8px; visibility:hidden" type="button"   id="pendiente"  onclick="generar_boton()"   class="btn btn-primary btn-large" title="Esperar"><span class="ico-plus" style=" margin: 4px 8px 0px 0px;"></span>Venta en Espera</button>
                        </td>
                    </tr>
                </table>
            </div>

            <div >
                <div id="botones">
                </div>

            </div>          

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

                <?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago_plan'"); ?>

            </div>

        </div>

        <div class="row-form">

            <div class="span2"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>

            <div class="span3"><input type="number" name="valor_entregado_plan" id="valor_entregado_plan"   onkeypress="return isNumberKey(event)"/>
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

<div id="dialog-forma-pago-form" title="<?php echo custom_lang('sima_pay_information', "Información de forma de pago"); ?>">

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

                        <?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago'"); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_reason', "Valor entregado"); ?>:</div>

                    <div class="span3"><input type="number" name="valor_entregado" id="valor_entregado"   onkeypress="return isNumberKey(event)"/>
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

                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado"   onkeypress="return isNumberKey(event)"/>
                            <input type="hidden" name="id_cliente" id="id_cliente" style="width: 260px;height: 25px;"/>  

                        </div>

                    </div>
                </div>
                <div id="contenido_a_mostrar1">
                    <div class="row-form">
                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago1'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado1" value="0"   onkeypress="return isNumberKey(event)"/>&nbsp;  
                            <a style='cursor: pointer;' onClick="ocultar('contenido_a_mostrar1');" title="">X</a>

                        </div>

                    </div>
                </div>
                <div id="contenido_a_mostrar2">
                    <div class="row-form">

                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago2'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado2" value="0"   onkeypress="return isNumberKey(event)"/> &nbsp;  
                            <a style='cursor: pointer;' onClick="ocultar('contenido_a_mostrar2');" title="">X</a> 

                        </div>

                    </div>
                </div>
                <div id="contenido_a_mostrar3">
                    <div class="row-form">

                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago3'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado3" value="0"    onkeypress="return isNumberKey(event)"/> &nbsp;  
                            <a style='cursor: pointer;' onClick="ocultar('contenido_a_mostrar3');" title="">X</a>

                        </div>

                    </div>
                </div>
                <div id="contenido_a_mostrar4">
                    <div class="row-form">

                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago4'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado4" value="0"    onkeypress="return isNumberKey(event)"/>  &nbsp;  
                            <a style='cursor: pointer;' onClick="ocultar('contenido_a_mostrar4');" title="">X</a>

                        </div>

                    </div>
                </div>
                <div id="contenido_a_mostrar5">
                    <div class="row-form">

                        <div class="span2"><?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago5'"); ?></div>

                        <div class="span3">

                            <input type="number" name="valor_entregado" id="valor_entregado5"  value="0"   onkeypress="return isNumberKey(event)"/> &nbsp;  
                            <a style='cursor: pointer;' onClick="ocultar('contenido_a_mostrar5');" title="">X</a>

                        </div>

                    </div>
                </div>

                <div class="row-form"> <div class="span2"><p><a style='cursor: pointer;' onClick="mostrar();" title="">Agregar Forma de Pago</a></p></div>
                    <div class="span3"> </div></div>

            <?php } ?>	
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
        <span id="ui-id-1" class="ui-dialog-title">Adicionar Cliente</span><button class="ui-dialog-titlebar-close"></button></div>
    <div id="dialog-client-form" class="ui-dialog-content ui-widget-content">



        <p class="validateTips">Todos campos son requeridos.</p>

        <form id="client-form">

            <div class="span3">Nombre completo <input type="text" name="nombre_comercial_cliente" id="nombre_comercial_cliente" class="validate[required]"> </div>

            <div class="span2">Tipo de identificaci&oacute;n <?php echo form_dropdown('tipo_identificacion', $data['tipo_identificacion'], "", "id='tipo_identificacion'"); ?></div>                                         

            <div class="span2">No de identificaci&oacute;n<input type="text" name="nif_cif" id="nif_cif" class="validate[required]"></div>                                         

            <div class="span3">Correo electronico<input type="text" name="email" id="email" class="validate[custom[email]]"></div>   

            <div class="span2"> Telefono <input type="text" name="telefono" id="telefono"></div>



            <div class="span3">Dirección <input type="text" name="direccion" id="direccion"></div>

            <div class="span2">Pais <?php echo custom_form_dropdown('pais', $data['pais'], set_value('pais'), " id='pais' style='width: 100%; '"); ?></div>

            <div class="span3">Ciudad   <?php echo form_dropdown('provincia', array(), set_value('provincia'), "id='provincia'"); ?></div>

            <div class="span3">Celular <input type="text" name="celular" id="celular"></div>

            <?php if ($data['si_no_plan_punto'] != '0') { ?>
                <div class="span5" id="asignar_plan"  style='display:none;'>Asignar plan de puntos
                    <table width="100%">
                        <tr>
                            <td><input type="checkbox" id="plan_puntos"  onclick="plan()" ></td>
                            <td> 

                                <table width="100%" id="escoger_plan" style="display:none;">
                                    <tr>
                                        <td>Plan de puntos</td>
                                        <td><?php
                echo "<select  name='pl' id='pl' style='width: 100%' >";
                foreach ($data['plan_puntos'] as $f) {
                    echo "<option value=" . $f->id_puntos . ">" . $f->nombre . "</option>";
                }
                echo "</select>";
                ?>  </td>
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
<?php } ?>  

    </div>

</form>
</div>


</div><div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset" style="background:#FFFFFF"></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>



<script type="text/javascript">


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

    $sendventas = "<?php echo site_url("/ventas/nuevo/"); ?>";

    $sendventas_espera = "<?php echo site_url("/ventas/espera"); ?>";

    $comanda = "<?php echo site_url("/ventas/comanda"); ?>";

    $comanda_imprimir = "<?php echo site_url("/ventas/comanda_imprimir"); ?>";

    $reload = "<?php echo site_url("ventas/index"); ?>";

    $reloadThis =  "<?php echo site_url("/ventasOffline/nuevo"); ?>/";

    $urlPrint = "<?php echo site_url("ventas/imprimir"); ?>";

    $urlcliente = "<?php echo site_url("clientes/get_ajax_clientes"); ?>";

    $navegador = '<?php echo $_REQUEST['var']; ?>';

    $impuestosnom = "<?php echo site_url("impuestos/get_impuesto"); ?>";

    $sobrecosto = "<?php echo $data['sobrecosto']; ?>";

    $nit = "<?php echo $data['nit']; ?>";

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

    function descuento_general_propover(valor) {

        if(descuentogeneral!=0){
            $("#descuento_general").val(descuentogeneral);
        }
        if ($("#descuento_general").val() == 0) {
            propoverContent = "<div class='input-append'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-primary' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
        } else {
            propoverContent = "<div class='input-append'><input type='text' class='descuento' placeholder='Valor en porcentaje' value='" + $("#descuento_general").val() + "' style='width:130px;' class='spinner' name='cantidad_input' /><button type='button' id='btn-accept-descuento'  class='btn btn-primary' style='float:right;'><span class='icon-ok icon-white'></span></button></div>";
        }

        $('#descuento_general_pro').popover({
            placement: 'bottom'
            , title: 'manual'
            , html: true
            , content: propoverContent
            , trigger: 'manual'
            , placement: "bottom"
        }).popover('show');

        //$("#btn-accept-descuento").click(function () {
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
                
                $(".title-detalle").each(function(index, item) {                    
                                          
                   
                        cantidad = $(".cantidad").eq(index).text();                           
                        impuesto = $(".impuesto-final").eq(index).val();                        
                        precio = parseFloat($(".precio-prod-real").eq(index).val());                         
                        
                        precioProd =parseFloat((precio)+ (((precio)* ((impuesto) /100)))); 
                     
                        total2 += (precioProd * cantidad);  
                        total3 = redondear(precioProd * cantidad);     
                        totaldes = redondear(parseFloat(((precioProd) * (parseFloat($('.descuento').val()) / 100))));                                        
                        totaldes=(totaldes*parseFloat(cantidad));
                        totaldes1+=totaldes;                        
                        finalto +=redondear(total3-totaldes);   
                        var valor = $('.descuento').val();                                             
                        var val1 = valor.replace("%", "");
                        var val2 = val1.replace(" ", "");
                        var precio = Math.round(precioProd);
                        var primer = $('.descuento').val();              
                        var res1 = primer.replace(/[1234567890]/gi, "");                                                                    
                        var resultado_porcen1 =  Math.round(parseFloat(precio) * val2 / 100);                            
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
                                                        
                        $(".precio-prod-descuento").eq(index).val(precioProd);                                     
                        calculate();                           
                });

                //var valor_total_descuento = (parseFloat($("#total").val()) * parseFloat($('.descuento').val())) / 100;

                var valor_total_descuento =  totaldes1;

                $('#valor_pagar_propina').html(formatDollar(
                    (parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento)
                ));

                $('#propina_output_pro').html(propina_pro + '% - ' + formatDollar(
                    parseFloat(total_pro) - ((parseFloat(total_pro) * parseFloat($('.descuento').val())) / 100)
                ));

                //$("#valor_pagar").val(formatDollar(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                //$("#valor_entregado").val(Math.round((parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento)));
                //$("#valor_pagar_hidden").val(Math.round((parseFloat($("#total").val()) - parseFloat(valor_total_descuento)) + parseFloat(valor_total_propina_descuento)));
                //$("#descuento_general").val($('.descuento').val());

                $("#valor_pagar").val(redondear(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                $("#valor_entregado").val(redondear(parseFloat(parseFloat(parseFloat($("#total").val()) + parseFloat(valor_total_propina_descuento)) - parseFloat(valor_total_descuento))));
                $("#valor_datafono_venty").val(formatDollar(parseFloat(parseFloat($("#total").val()) - parseFloat(valor_total_descuento))));
                $("#valor_pagar_hidden").val(redondear((parseFloat($("#total").val()) - parseFloat(valor_total_descuento)) + parseFloat(valor_total_propina_descuento)));
                finalto= $("#valor_pagar").val();
                $("#total").val($("#valor_pagar").val());   

                $("#descuento_general").val($('.descuento').val());
                descuentogeneral=$('.descuento').val();
            }

            if (isNaN($('.descuento').val())) {
                $('.descuento').val('0');
            }


            $('#descuento_general_pro').popover('destroy');
            
        });

    }

    $().ready(function () {

        $("#datos_vendedor").autocomplete({
            source:   function (request, response) { 
                var query = request.term;

                appOffline.queryVendedores( query, function(){
                    var vendedores = appOffline.getObjVendedores();                                       
                    response( vendedores );
                });            

            },
            minLength: 1,
            select: function (event, ui) {

                console.log(ui);
//
                $("#vendedor").val(ui.item.id);

            }

        });

        $("#dialog-nota-form").dialog({
            autoOpen: false,
            height: 300,
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
                "Aceptar": function () {

                    if ($("#client-form").length > 0)

                    {

                        $("#client-form").validationEngine('attach', {promptPosition: "topLeft"});

                        if ($("#client-form").validationEngine('validate')) {

                            var dataCliente = {
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
                                    cod_targeta: $('#cod_targeta').val()
                                }
                
                
                            appOffline.guardarCliente( dataCliente, function () {

                                var data = {"id_cliente" : appOffline.getIdCliente() };
                                
                                console.log(data.id_cliente);
                                console.log(appOffline.getIdCliente());
                                
                                $("#id_cliente").val(data.id_cliente);
                                $("#id_cliente_plan").val(data.id_cliente);
                                $("#datos_cliente").val($('#nombre_comercial_cliente').val() + " (" + $('#nif_cif').val() + ")");
                                $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());
                                $("#dialog-client-form").dialog("close");

                            });

                            /*
                            $.ajax({
                                url: '<?php echo site_url('clientes/add_ajax_client'); ?>',
                                data: dataCliente,
                                dataType: 'json',
                                type: 'POST',
                                success: function (data) {
                                    
                                    $("#id_cliente").val(data.id_cliente);
                                    $("#id_cliente_plan").val(data.id_cliente);
                                    $("#datos_cliente").val($('#nombre_comercial_cliente').val() + " (" + $('#nif_cif').val() + ")");
                                    $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());
                                    $("#dialog-client-form").dialog("close");
                                }

                            });
                            */
                            
                            // FIN AJAX



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


            }

        });



        $("#add-new-client").click(function () {

            $("#dialog-client-form").dialog("open");

        });

        //pendientE
        $("#comanda").click(function () {

            productos_list = new Array();
            $(".title-detalle").each(function (x) {
                var descuento = 0;
            
                // 
                descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

                if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())) {
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
                    , 'margen_utilidad': ($(".precio-prod-real").eq(x).val() - $(".precio-compra-real-selected").eq(x).val()) * parseInt($(".cantidad").eq(x).text())
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
                , data: {
                    cliente: $("#id_cliente").val()
                    , productos: productos_list
                    , vendedor: $("#vendedor").val()
                    , total_venta: $("#total").val()
                    , pago: pago
                    , nota: $("#notas").val()
                    , sobrecostos: $("#sobrecostos_input").val()
                    , id_fact_espera_nombre: $("#id_fact_espera_nombre").val()
                }
                , error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
                , success: function (data) {
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

            


            if ($("#id_fact_espera").val() == '') {



                productos_list = new Array();
                $(".title-detalle").each(function (x) {
                    var descuento = 0;

                    // 
                    descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

                    if (parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())) {
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

//eliminar factura espera

                                                $("#btn-cancel-cantidad" + valor + "").click(function () {
                                                    //cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+$('.spinner').val());
                                                    //alert("hola");
                                                    var nom = $('.spinner').val();
                                                    $.ajax({
                                                        url: "<?php echo site_url("ventas/factura_espera_eliminar"); ?>",
                                                        type: "GET",
                                                        dataType: "json",
                                                        data: {
                                                            nom: $('.spinner').val() //, 
                                                                    /* id: valor*/},
                                                        success: function (data) {

                                                            // alert(valor);

                                                            document.getElementById('' + valor + '').remove();
                                                            /*   if(data == '1'){
                                                             cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+cantidadField.text());
                                                             }
                                                             if(data == '0'){
                                                             cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+nom);
                                                             }   
                                                             */
                                                        }
                                                    });

                                                    $('#' + valor + '').popover('destroy');
                                                    $('#id_fact_espera_nombre').val(nom);
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
                $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                $('#total-show').html('0.00');
                $('#subtotal').html('0.00');
                $('#iva-total').html('0.00');


            } else {


                //window.location = $reloadThis+"?var="+tipo_busqueda;
                $("#id_fact_espera").val('');
                $("#id_fact_espera_nombre").val('');
                $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
                $('#total-show').html('0.00');
                $('#subtotal').html('0.00');
                $('#iva-total').html('0.00');

            } // if principal
            //  window.location = $reloadThis+"?var="+tipo_busqueda;

        });



        $(document).ready(function () {


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
                                rowHtml = "<div style='width: 79px; padding: 1px 1px; height:45px; background-color: #005683; vertical-align: text-bottom;' class='btn " + data[i].id + "' id='" + data[i].id + "' onclick='espera_cargar(" + data[i].id + "); cambiar_color(" + data[i].id + ");' ><table height='8px'><tbody><tr><td></td></tr></tbody></table>" + (data[i].factura) + "</div> ";
                                $("#botones").append(rowHtml);
                            }
                        }
                    } else {
                        $("#datos_cliente").val('');
                    }

                }
            });
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
                            //cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+$('.spinner').val());
                            // alert("holaaa");
                            var nom = $('.spinner').val();
                            $.ajax({
                                url: "<?php echo site_url("ventas/factura_espera_eliminar"); ?>",
                                type: "GET",
                                dataType: "json",
                                data: {
                                    nom: $('.spinner').val() //, 
                                            /* id: valor*/},
                                success: function (data) {


                                    if (data.success == 'true') {


                                        document.getElementById('' + valor + '').remove();


                                    }

                                    // if(data == '0'){
                                    //         cantidadField.html('<table height="8px"><tbody><tr><td></td></tr></tbody></table>'+nom);
                                    // }   

                                }
                            });

                            $('#' + valor + '').popover('destroy');
                            $('#id_fact_espera_nombre').val(nom);
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
                            data[i].id != id) {
                        document.getElementById('' + data[i].id + '').style.backgroundColor = '#005683';
                    }
                }

            }
        });

        document.getElementById('' + id + '').style.backgroundColor = 'black';

    }

    function espera_cargar(id) {

        $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
        $('#total-show').html('0.00');
        $('#subtotal').html('0.00');
        $('#iva-total').html('0.00');

        $.ajax({
            url: "<?php echo site_url("ventas/detalles_espera"); ?>",
            type: "GET",
            dataType: "json",
            data: {id: id},
            success: function (data) {

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
                                dataType: "json",
                                data: {imp: sProduct.impuesto},
                                success: function (data) {
                                    nom = data
                                }
                            });
                        }

                        rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + data[i].precio_venta + "'/><input type='hidden' value='" + data[i].id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' value='" + data[i].codigo_producto + "'><input type='hidden' class='impuesto-final' value='" + data[i].impuesto + "'><span class='title-detalle text-info'><input type='hidden' value='" + data[i].impuesto + "' class='detalles-impuesto'>" + data[i].nombre_producto + "</span></td>";
                        rowHtml += "<td><span class='label label-success cantidad'>" + data[i].unidades + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'></td>";


<?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                            rowHtml += "<td><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
<?php } else { ?>
                            rowHtml += "<td><span class='label label-success precio-prod' onClick='calculadora_descuento(" + data[i].precio_venta + ");'>" + formatDollar(data[i].precio_venta) + "</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + data[i].precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + data[i].precio_venta + "'/></td>";
<?php } ?>

                        rowHtml += "<td><span class='precio-calc'>" + data[i].precio_venta + "</span><input type='hidden' value='precio-calc-real' value='" + data[i].precio_venta + "'/></td>";
                        rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='ico-remove'></span></div></a></td></td>";
                        rowHtml += "</tr>";

                        if ($("#productos-detail tr").eq(0).hasClass("nothing")) {
                            $("#productos-detail").html(rowHtml);
                        } else {
                            $("#productos-detail").append(rowHtml);
                        }

                        calculate();


                        $('#datos_cliente').val(data[i].cli_nom);
                        $('#id_cliente').val(data[i].id_clientes);
                        $('#id_fact_espera').val(data[i].venta_id)
                        $('#id_fact_espera_nombre').val(data[i].factura);

                    }


                } else {
                    $("#datos_cliente").val('');
                }

            }
        });
    }

    function renderFactura() {

        var id_producto = sProduct.id;
        var matching = $('.product_id[value="' + id_producto + '"]').index();

        sProduct.stock_minimo -= 1;
        $('#cod-stock').html(sProduct.stock_minimo);

        if (matching == -1) {

            if ($sobrecosto == 'si' && $nit != '320001127839') {
                var nom;
                $.ajax({
                    async: false, //mostrar variables fuera de el function 
                    url: $impuestosnom,
                    type: "POST",
                    dataType: "json",
                    data: {imp: sProduct.impuesto},
                    success: function (data) {
                        nom = data
                    }
                });
            }

            rowHtml = "<tr><td><input type='hidden' class='precio-compra-real-selected' value='" + sProduct.precio_venta + "'/><input type='hidden' value='" + id_producto + "' class='product_id'/><input type='hidden' class='codigo-final' value='" + sProduct.codigo + "'><input type='hidden' class='impuesto-final' value='" + sProduct.impuesto + "'><span class='title-detalle text-info'><input type='hidden' value='" + sProduct.impuesto + "' class='detalles-impuesto'>" + sProduct.nombre + "</span></td>";
            rowHtml += "<td><span class='label label-success cantidad'>" + 1 + "</span><input type='hidden' class='nombre_impuesto' value='" + nom + "'></td>";

<?php if (in_array("1010", $permisos) && $is_admin !== 't') { ?>
                rowHtml += "<td><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/></td>";
    <?php
} else {
    ?>
                rowHtml += "<td><span class='label label-success precio-prod'  onClick='calculadora_descuento(" + sProduct.precio_venta + ");'>" + formatDollar(sProduct.precio_venta) + "</span><input type='hidden' class='precio-prod-precio-porcentaje' /><input type='hidden' class='precio-prod-real' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-descuento' value='" + sProduct.precio_venta + "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" + sProduct.precio_venta + "'/></td>";
<?php } ?>

            rowHtml += "<td><span class='precio-calc'>" + sProduct.precio_venta + "</span><input type='hidden' value='precio-calc-real' value='" + sProduct.precio_venta + "'/></td>";
            rowHtml += "<td><td><a class='button red delete' href='#'><div class='icon'><span class='ico-remove'></span></div></a></td></td>";
            rowHtml += "</tr>";

            if ($("#productos-detail tr").eq(0).hasClass("nothing")) {
                $("#productos-detail").html(rowHtml);
            } else {
                $("#productos-detail").append(rowHtml);
            }

        } else {
            parent = $('.product_id[value="' + id_producto + '"]').parent().parent().index();
            cantidad = parseInt($('.cantidad').eq(parent).text()) + 1;
            $('.cantidad').eq(parent).text(cantidad);
        }

        calculate();
    }

    $(".delete").live("click", (function () {
        $(this).parent().parent().remove();
        if ($("#facturasTable tbody tr").length == 0) {
            $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");
        }
        calculate();
    }));

    $(document).ready(function () {


        $("body").attrchange({
                trackValues: true, 
                callback: function (event) {                     
                    if( event.newValue == "dashboard site-menubar-unfold" || event.newValue == "dashboard site-menubar-changing site-menubar-unfold")
                        $("body").attr("class","dashboard site-menubar-fold");
                }        
        });

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

        $("#pagar").click(function () {
            $("#valor_entregado").removeAttr("disabled");
            $("#valor_entregado1").removeAttr("disabled");
            $("#valor_entregado2").removeAttr("disabled");
            $("#valor_entregado3").removeAttr("disabled");
            $("#valor_entregado4").removeAttr("disabled");
            $("#valor_entregado5").removeAttr("disabled");
        });
        //puntos -------------------------------------------------------------------
        $("#forma_pago, #forma_pago1, #forma_pago2, #forma_pago3, #forma_pago4, #forma_pago5").change(function () {
            var forma_pago_id = $(this).attr("id");
            var forma_pago = $(this).val();
            var cliente = $("#id_cliente").val()
            if (forma_pago == 'Puntos') {
                $.ajax({
                    url: "<?php echo site_url("puntos/get_datos_punto_redimir") ?>/" + cliente,
                    success: function (response) {
                        if (forma_pago_id == 'forma_pago') {
                            $("#valor_entregado").val(response);
                            $("#valor_entregado").attr('disabled', 'disabled');
                            $("#valor_entregado").prop('disabled', true);
                        }
                        if (forma_pago_id == 'forma_pago1') {
                            $("#valor_entregado1").val(response);
                            $("#valor_entregado1").attr('disabled', 'disabled');
                            $("#valor_entregado1").prop('disabled', true);
                        }
                        if (forma_pago_id == 'forma_pago2') {
                            $("#valor_entregado2").val(response);
                            $("#valor_entregado2").attr('disabled', 'disabled');
                            $("#valor_entregado2").prop('disabled', true);
                        }
                        if (forma_pago_id == 'forma_pago3') {
                            $("#valor_entregado3").val(response);
                            $("#valor_entregado3").attr('disabled', 'disabled');
                            $("#valor_entregado3").prop('disabled', true);
                        }
                        if (forma_pago_id == 'forma_pago4') {
                            $("#valor_entregado4").val(response);
                            $("#valor_entregado4").attr('disabled', 'disabled');
                            $("#valor_entregado4").prop('disabled', true);
                        }
                        if (forma_pago_id == 'forma_pago5') {
                            $("#valor_entregado5").val(response);
                            $("#valor_entregado5").attr('disabled', 'disabled');
                            $("#valor_entregado5").prop('disabled', true);
                        }

                        $("#sima_cambio_hidden").val(Math.round((
                                (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val())
                                        + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

                                )));

                        $("#sima_cambio").val(formatDollar(Math.round((
                                (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val())
                                        + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

                                ))));

                    }
                });

            } else {
                //if(forma_pago_id == 'forma_pago'){ $("#valor_entregado").val($("#total").val());  }
                if (forma_pago_id == 'forma_pago1') {
                    $("#valor_entregado1").val(0);
                    $("#valor_entregado1").removeAttr("disabled");
                }
                if (forma_pago_id == 'forma_pago2') {
                    $("#valor_entregado2").val(0);
                    $("#valor_entregado2").removeAttr("disabled");
                }
                if (forma_pago_id == 'forma_pago3') {
                    $("#valor_entregado3").val(0);
                    $("#valor_entregado3").removeAttr("disabled");
                }
                if (forma_pago_id == 'forma_pago4') {
                    $("#valor_entregado4").val(0);
                    $("#valor_entregado4").removeAttr("disabled");
                }
                if (forma_pago_id == 'forma_pago5') {
                    $("#valor_entregado5").val(0);
                    $("#valor_entregado5").removeAttr("disabled");
                }

            }

            $("#sima_cambio_hidden").val(Math.round((
                    (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val())
                            + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

                    )));

            $("#sima_cambio").val(formatDollar(Math.round((
                    (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val())
                            + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

                    ))));
            //$("#sima_cambio_hidden").val($("#valor_pagar_hidden").val() - $(this).val());

        });
//puntos -------------------------------------------------------------------


        var pais = $("#pais").val();

        if (pais != "") {

            load_provincias_from_pais(pais);

        }



    });

    $("#fecha_vencimiento").datepicker({
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
<?php } ?>


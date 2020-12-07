<script type="text/javascript"> var client = <?php echo json_encode($data['clientes']) ?></script>

<style type="text/css">

.ui-dialog{
    z-index: 9000!important;
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

<div class="content" >

    <div class="row-fluid">
        <!--Derecha-->
        <div class="span7">

            <div class="block">

                <div class="head" style="text-align: center;">
<!-- 
                    <div class="icon"><span class="ico-arrow-right"></span></div><br/><h2>Nueva venta</h2> -->

                    <div class="row-form" style="padding-left: 0px;padding-right: 0px;">
                        <form>
                            <ul id='tipo-busqueda'>
                                <li id ='buscalo' class='active'> <h3>Buscador </h3><!--  <img src="<?php //echo base_url("/public/img/");?>/codigobarra-icon.png">  --></li>
                                <li id ='codificalo'> <h3>C&oacute;digo </h3> </li>
                                <li id ='navegador'> <h3>Navegador</h3>  </li>
                            </ul>
                        </form>

                    </div>

                </div>

                    <div id='search-container' class="input-append">

                        <input type="text" name="text" class="span12" placeholder="Digite producto a buscar..." id="search"/>

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

                <div id="contenedor-vitrina">
                    <div class="input-append">

                        <div id="categorias" >
                           <!--  <div id='previous' class='btn-control'></div> -->
                            <ul id="nav-categoria">
                                <li id="0" onclick="filtrarCategoria(this)"><img src="<?php echo base_url("/uploads/").'/todos.jpg'; ?>"><br>Todos</li>
                                <?php 
                                    $i=0;
                                    foreach ($data['categorias'] as $key => $value) {
                                        if($i==0)
                                        echo '<li id="'.$value->id.'" onclick="filtrarCategoria(this)" ><img src="'.base_url("/uploads/").'/general.jpg"><br>'.$value->nombre.'</li>';
                                        else
                                        echo '<li id="'.$value->id.'" onclick="filtrarCategoria(this)" ><img src="'.base_url("/uploads/").'/'.$value->imagen.'"><br>'.$value->nombre.'</li>';
                                        $i++;
                                    }
                                ?>
                            </ul>
                            <div id='next' class='btn-control' onclick='siguiente_categorias()'>
                                <div id='next-triangulo'></div>
                            </div>
                        </div>
                      
                          
                        <div id="vitrina">

                        </div>
                    </div>
                </div>

                <div id='cod-container'>
                    <div id='cod-item'>
                        <img id='cod-img' src="<?php echo base_url("/uploads/");?>/product-dummy.png">  
                        <h5 id='cod-nombre'></h5>
                        <strong>Cod: </strong><span id='cod'></span><br>
                        <strong>Stock: </strong><span id='cod-stock'></span><br>
                        <strong>Compra: </strong><span id='cod-compra'></span><br>
                        
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
                            /*foreach ($data['productos'] as $key => $value) {
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

            <div class="block">                    

                <div class="head green" style="color: #fff; font-size: 20px;">

                    <div class="icon">

                        <span class="ico-info"></span>

                    </div>

                    <input type="hidden" value="0" id="total"/>

                    TOTAL VENTA= <span style="font-weight: bold; "><span class="label label-info" id="total-show">0.00</span></span>

                </div>

                <div class="data-fluid">

                    <div style="height:200px;overflow:auto; width:100%">

                        <table cellpadding="0" cellspacing="0" width="100%" class="table  dtable lcnp">

                            <tbody height="50px" id="productos-detail">

                                <tr class="nothing">

                                    <td>No existen elementos</td>

                                </tr>                     

                            </tbody>

                        </table>

                    </div>

                    <table cellpadding="0" cellspacing="0" width="100%" class="table">

                        <tr>

                            <td >Subtotal:</td>

                            <td style="background-color: #E9E9E9; text-align: right; font-weight: bold;">$<span id="subtotal">0.00</span></td>

                        </tr>

                        <tr>

                            <td>IVA:</td>

                            <td style="text-align: right;"><span id="iva-total">0.00</span></td>

                        </tr> 

                    </table>

                    <br/>

                    <div class="head green">

                        <div class="icon"><span class="ico-user"></span></div>

                        <h2>Cliente</h2>

                        <ul class="buttons">                                    

                            <li><a href="#" class="clienteHideButton"><div class="icon"><span class="ico-sort"></span></div></a></li>

                        </ul>  

                    </div>

                    <div id="clienteBlock">
                        <div class="row-form">
                            <div class="span6">
                                <div class="input-prepend input-append">
                                    <label>Buscar: </label>
                                    <input id='buscar-cliente' placeholder="Nombre" style="width: 260px;height: 25px;">
                                    <span class="add-on" style="background: #68AF27;"><i class="icon-search icon-white"></i></span>
                                    <div id="contenedor-lista-clientes"><ul id="lista-clientes"></ul></div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-form" >      
                            <div class="span6">
                                <p>
                                    <strong>Cliente: </strong><span id="nombre_comercial">No seleccionado</span><br>
                                   <!--  <strong>Razon social: </strong> <span id="razon_social"></span><br>
                                    <strong>Nif cif: </strong> <span id="nif_cif"></span><br> -->
                                    <!-- <strong>Contacto: </strong> <span id="contacto"></span><br>
                                    <strong>Grupo: </strong> <span id="grupo_clientes_id"></span><br> -->
                                </p>
                            </div>
                        </div>
                        <div class="row-form" >
                            <div class="span8"></div>
                            <div class="span4">
                                  <a href="javascript:void(0)" class="btn" id="add-new-client"><small class="ico-plus icon-white"></small>Nuevo cliente(Rápido) </a>
                            </div>
                        </div>



                    </div>
                    
                    
                    <br/>

                        <div class="head green">

                            <div class="icon"><span class="ico-user"></span></div>

                                <h2>Vendedor</h2>

                            <ul class="buttons">                                    

                                <li><a href="#" class="vendedorHideButton"><div class="icon"><span class="ico-sort"></span></div></a></li>

                            </ul>  

                        </div>

                    <div class="row-form" id="vendedorBlock">

                            <div class="span3">Nombre:</div>

                            <div class="span9">                                    

                                <?php echo form_dropdown('vendedor', array_merge(array("-1" => "Seleccione vendedor"), $data['vendedores']), array(), "id='vendedor'") ?>

                            </div>

                        </div>

                </div>

                <div class="toolbar">

                    <button class="btn btn-inverse btn-large" id="pagar">

                            <span class="ico-money"></span>

                            Pagar

                    </button>

                   <!--  <button class="btn btn-inverse btn-large" id="pendiente">

                            <span class="ico-money"></span>

                            Pendiente

                    </button> -->

                    <a href="<?php echo site_url("ventas/nuevo");?>" class="btn btn-danger btn-large" >

                            <span class="ico-remove"></span>

                            Cancelar

                    </a>

                </div>

                </div>  

            </div>

        </div>



    </div>

<div id="dialog-forma-pago-form" title="<?php echo custom_lang('sima_pay_information', "Informacion de forma de pago");?>">

    <div class="span6">

        <form id="client-form">

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_pay_value', "Valor a pagar");?>:</div>

                    <div class="span3">

                        <input type='hidden' name='valor_pagar_hidden' id='valor_pagar_hidden'/>

                        <input type="text" disabled='disabled' name="valor_pagar" id="valor_pagar"/></div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_forma_pago', "Forma de pago");?>:</div>

                    <div class="span3">

                        <?php echo form_dropdown('forma_pago', $data['forma_pago'], "", "id='forma_pago'"); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_reason', "Valor entregado");?>:</div>

                    <div class="span3"><input type="number" name="valor_entregado" id="valor_entregado" />

                    </div>

                </div>

                <div class="row-form">

                    <div class="span2"><?php echo custom_lang('sima_cambio', "Cambio");?>:</div>

                    <div class="span3">

                        <input type='hidden' name='sima_cambio_hidden' id='sima_cambio_hidden'/>

                        <input type="text" disabled='disabled' name="sima_cambio" id="sima_cambio" />

                    </div>

                </div>

        </form>

    </div>

</div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-client-form" aria-labelledby="ui-id-1" style="display: none; position: absolute;"  title="Adicionar Cliente"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix"><span id="ui-id-1" class="ui-dialog-title">Adicionar Cliente</span><button class="ui-dialog-titlebar-close"></button></div><div id="dialog-client-form" class="ui-dialog-content ui-widget-content">

                                <div class="span6">

                                    <p class="validateTips">Todos campos son requeridos.</p>

                                    <form id="client-form">

                                            <div class="row-form">

                                                <div class="span2">Nombre comercial:</div>

                                                <div class="span3"><input type="text" name="nombre_comercial_cliente" id="nombre_comercial_cliente" class="validate[required]"></div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2">Correo electronico:</div>

                                                <div class="span3"><input type="text" name="email" id="email" class="validate[custom[email]]">

                                                    

                                                </div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2">Razón social:</div>

                                                <div class="span3"><input type="text" name="razon_social" id="razon_social">

                                                </div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2">NIF/CIF:</div>

                                                <div class="span3"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]">

                                                </div>

                                            </div>

                                    </form>

                                </div>

                            </div><div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset"><button type="button">Aceptar</button><button type="button">Cancelar</button></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>


<script type="text/javascript">

    
    $url = "<?php echo site_url("productos/productos_filter");?>";
    
    $url = "<?php echo site_url("productos/productos_filter_group");?>";

    $urlVitrina = "<?php echo site_url("productos/get_by_category");?>";

    $urlImages = "<?php echo base_url("/uploads/");?>";

    $urlCategorias = "<?php echo site_url("categorias/limit");?>";

    $sendventas = "<?php echo site_url("/ventas/nuevo");?>";

    $reload = "<?php echo site_url("ventas/index");?>";

    $reloadThis = "<?php echo site_url("ventas/nuevo");?>";

    $urlPrint = "<?php echo site_url("ventas/imprimir");?>";


    $().ready(function(){


         $( "#dialog-client-form" ).dialog({

            autoOpen: false,

            //height: 400,

            width: 620,

            modal: true,

            buttons: {

                "Aceptar": function() {

                    if($("#client-form").length > 0)

                    {

                        $("#client-form").validationEngine('attach',{promptPosition : "topLeft"});

                        if($("#client-form").validationEngine('validate')){

                            $.ajax({

                                url: '<?php echo site_url('clientes/add_ajax_client');?>',

                                data: {nombre_comercial: $('#nombre_comercial_cliente').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val()},

                                dataType: 'json',

                                type: 'POST',

                                success: function(data){

                                    $("#dialog-client-form").dialog( "close" );

                                }

                            });
                        }

                    }

                },

                "Cancelar": function() {

                    $( this ).dialog( "close" );

                }

            },

            close: function() {

                            $('#razon_social').val("");

                            $('#nif_cif').val("");

                            $('#email').val("");

                            $('#nombre_comercial').val("");

            }

        });

                

        $("#add-new-client").click(function(){

            $( "#dialog-client-form" ).dialog( "open" );

        });

    });
    
var cliente_selecionado = {}
var cliente_grupo = {}
$("#datos_cliente").autocomplete({

    source: "../clientes/get_ajax_clientes",

    minLength: 1,

    select: function(event, ui) {
        cliente_selecionado = ui.item.id;
        cliente_grupo = ui.item.grupo_clientes_id;
        lista = ui.item.lista;
        $("#id_cliente").val(ui.item.id);
        $("#id_cliente_plan").val(ui.item.id);

        $("#otros_datos").val(ui.item.descripcion);

        if (lista >= '1') {
            var filter = $(this).val();
            $('#lista-clientes').html('');
            $('#facturasTable tbody').html('');
            $('#cliente-titulo').html('');
            $('#search').val('');
            $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');
            $('#total-show').html('0.00');
            $('#subtotal').html('0.00');
            $('#iva-total').html('0.00');
            $('#id_lista').val(lista);
            filtrarCategoria(document.getElementById('categorias'), lista);

        } else {
            $('#id_lista').val('0');
        }

    }

});
        

</script>
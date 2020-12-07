<style>
    #v2Cont.panel {
        margin-bottom: 0px !important;
        border: none !important;
        box-shadow: none !important;
    }
    #v2Cont.panel,.body,.wrapper{
        margin: 0px;
        padding: 0px;
        background-color: transparent;
    }
    .panel-title{
        padding: 5px;
    }

    table a{
        color: #66B12F;
        font-size: 13px;
    }
    table a:hover{
        text-decoration: underline;
        color: #5B7D3A;
    }

    .page-header{display: flex;align-items: center;}
    .content-informes{background-color: #FFF; padding: 30px; box-sizing:border-box;}
    .content-informes ul{margin-top:9px; color:gray;margin-left: 11px;}
    .content-informes ul li{list-style:none; padding-top: 10px; padding-bottom: 10px; border-bottom:solid 1px lightgray;font-size:13px;}
    .content-informes ul li a{color:gray;}
    .content-informes ul li a:hover{color: #6dca42; transition: 0.8s;}
    .content-informes .file-informes{margin-top: 30px;}
    .content-icon{float: left;width: 35px;height: 35px; border: solid 1px lightgray;border-radius: 50% 50% 50% 50%; padding-left: 4px; margin-right:4px;}
    .icon-informe{color: #aeaeae !important; padding:0px; float: left;font-size: 23px;}
</style>
<!--
<div class="row">                                             
    <div class="col-xs-12 col-md-12">
        <div class="example-col panel" style=" padding: 5px 15px; margin-bottom:0px;">
            <div class="page-header">
                
                <div class="content-icon"><i class="wb-graph-up icon-informe" aria-hidden="true"></i></div>
                <h1><?php echo custom_lang("Informes", "Informes"); ?></h1>
            </div>
        </div>
    </div>
</div>-->
<div class="page-header" style="margin-left: 2%; margin-top: 1%;">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<?php
    $permisos = $this->session->userdata('permisos');
    $is_admin = $this->session->userdata('is_admin');
    $options = getOptions();
?>
<div class="content-informes">
    <div class="row">
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_ventas']['original'] ?>"> 
                VENTAS
                <ul>
                    <?php if (in_array('1013', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="transacionesinforme" data-container="body" data-content="Nos descarga la facturacion hecha en un rango de fechas" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/transacionesinforme') ?>" >Exportar facturas (Transacciones)</a>
                   </li>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="transacionesinforme" data-container="body" data-content="Nos descarga las devoluciones hechas en un rango de fechas" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/devolucionesinforme') ?>" >Exportar devoluciones</a>
                   </li>
                    <?php } ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxdomiciliario" data-container="body" data-content="Ventas la facturación por domiciliarios" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventas_domicilios') ?>" >Ventas por domicilios</a>
                    </li>
                    <?php if (in_array('74', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="Calcula la utilidad de cada venta" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasxclientes') ?>" >Facturas con info del cliente</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('75', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="comprasCliente" data-container="body" data-content="Muestra la cantidad de producto compradas por cada cliente" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/comprasCliente') ?>" >Productos vendidos por cliente</a>
                     </li>
                    <?php } ?>
                     <?php if ((in_array('77', $permisos)) || (in_array('78', $permisos)) || (in_array('79', $permisos)) || ($is_admin == 't') || (in_array('79', $permisos))) {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas" data-container="body" data-content="Filtra el total de venta por horas, por días o por mes en un rango de fechas" rel="popover" data-placement="right" data-original- data-trigger="hover"  href="<?php echo site_url('informes/total_ventas') ?>" >Ventas</a>
                    </li>
                    <?php } ?>
                    <!--<?php if (in_array('77', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas_hora" data-container="body" data-content="Filtra el total de venta de cada hora" rel="popover" data-placement="right" data-original- data-trigger="hover"  href="<?php echo site_url('informes/total_ventas_hora') ?>" >Ventas por Hora </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('79', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas_mes" data-container="body" data-content="Muestra el total de venta segun un rango de período" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_mes') ?>" >Ventas por Día y Mes</a>
                    </li>
                    <?php } ?>-->
                    <?php if (in_array('80', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventas_categoria" data-container="body" data-content="Calcula la venta total por categorias en un rango de fecha" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventas_categoria') ?>" >Ventas por Categoria</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('80', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_formas_pago" data-container="body" data-content="Te muestra los pagos dependiendo la forma de pago por rango de fecha descontando las notas credito" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_formas_pago') ?>" >Ventas por formas de pago</a>
                    </li>
                    <?php } ?>
                    <?php if ((in_array('80', $permisos) || $is_admin == 't') && $data['atributos']) {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas_atributos" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_atributos') ?>" >Informe de Total de Ventas por Atributos</a>
                   </li> 
                    <?php } ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="clientes_puntos_acumulados" data-container="body" data-content="Muestra el total de puntos acumulados por cliente y el valor que tiene esos puntos" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/clientes_puntos_acumulados') ?>" >Puntos Acumulados por Cliente</a>
                   </li> 

                   <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventas_por_vendedor" data-container="body" data-content="Muestra el detalle de ventas por vendedor y sus respectivos promedios (UPT,VPT,VPU)" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/detalle_ventas_por_vendedor') ?>" >Detalle de ventas por vendedor</a>
                   </li>
                   <?php if(isset($options["tipo_negocio"]) && $options["tipo_negocio"] == 'restaurante'): ?>
                        <li>
                            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            <a id="ventas_por_tomapedido" data-container="body" data-content="Muestra el detalle de ventas realizadas por toma pedido" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventas_por_tomapedido') ?>" >Detalle de ventas por toma pedido</a>
                        </li>
                   <?php endif; ?>
                 
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_productos']['original'] ?>"> 
                PRODUCTOS Y EXISTENCIAS
                <ul>
                    <?php if (in_array('87', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="existensias_inventario" data-container="body" data-content="Nos muestra la existencia que tenemos de nuestro producto en tiempo real y por almacen con precio de compra y venta" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/existensias_inventario') ?>" >Existencias del inventario</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1012', $permisos) || $is_admin == 't') {?>
                    <!--<li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="consolidado_inventario" data-container="body" data-content="Este informa el total de los productos que se se tiene en todo el sistema por producto sumando todos los almacenes" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/consolidado_inventario') ?>" >Consolidado de existencias</a>
                    </li>-->
                    <?php } ?>
                    <!--<li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe_producto_por_almacen" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url("informes/informe_producto_por_almacen"); ?>">Existencias por almacén</a>
                    </li>-->
                    <?php if ($is_admin == 't') { ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a  id="stock_minimo_maximo" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url("informes/stock_minimo_maximo"); ?>">Inventario con Stock Mínimo</a>   
                    </li>
                    <?php } ?>
                    <?php if (in_array('84', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="valor_inventario" data-container="body" data-content="Esto nos muestra el total del valor del inventario segun el costo del producto tambien el total de venta y el margen de utilidad" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/valor_inventario') ?>" >Valor del Inventario</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="habitos_consumo_dia" data-container="body" data-content="Muestra la cantidad de productos vendidos filtrados por dia de venta y totaliza el valor del producto" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/habitos_consumo_dia') ?>" >Hábitos de Consumo por día</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="habitos_consumo_mes" data-container="body" data-content="Muestra la cantidad de productos vendidos filtrados por mes de venta y totaliza el valor del producto" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/habitos_consumo_mes') ?>" >Hábitos de Consumo por Mes</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('86', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="menos_rotacion" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/menos_rotacion') ?>" >Inventario con Menos Rotaci&oacute;n </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1018', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe_lista_de_precios" data-container="body" data-content="Este informe nos descarga en un Ecxel el listado de precios junto al descuento asociado en la lista" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/informe_lista_de_precios') ?>" >Exportar Libro de Precios</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="plan_separe_productos" data-container="body" data-content="Consulta los productos que se encuentran en los planes separe" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/plan_separe_productos') ?>" >Productos Separados</a>
                    </li>
                    <?php }?>
                    <?php  if(count($franquicias) > 0){ ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_inventario_atributos_franquicia" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_inventario_atributos_franquicia') ?>" >Existencias de inventario por atributos</a>
                    </li>
                   <?php } ?>


                   <?php if ((in_array('80', $permisos) || $is_admin == 't') && $data['atributos']) {?>
                        <li>
                            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            <a id="total_inventario_atributos" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_inventario_atributos') ?>" >Total de Inventario por Atributos</a>
                        </li>                          
                    <?php }?>

                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_inventario_imeis" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_inventario_imeis') ?>" >Listado de Seriales/Imei</a>
                    </li> 
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_gastos']['original'] ?>"> 
                GASTOS Y COMPRAS
                <ul>
                    <?php if (in_array('90', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_gastos') ?>" > Exportar gastos </a>
                    </li>
                    <?php } ?>
                    <?php if ( in_array("71", $permisos ) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="pagosrordencompra" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('orden_compra/pagosrordencompra') ?>" >Exportar Ordenes de Compra</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="orden_compra_productos" data-container="body" data-content="Nos muestra los productos comprados los cuales podemos mostrar por producto o por proveedor" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/orden_compra_productos') ?>" >Búsqueda Compras de un producto</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1015', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_saldo_proveedor') ?>" > Búsqueda Saldo de Proveedor </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row file-informes">
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-eur" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_utilidad']['original'] ?>"> 
                UTILIDAD
                <ul>
                    <?php if (in_array('73', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="utilidad_periodo" data-container="body" data-content="Calcula la utilidad detallallada con respecto a un período ingresado" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/utilidad_periodo') ?>" >Utilidad Detallada de Operaci&oacute;n</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('74', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="Calcula la utilidad de cada venta" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasxclientes') ?>" >Facturas con su Utilidad</a>
                    </li>
                    <?php } ?>
                    <?php if ($is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_utilidad" data-container="body" data-content="Calcula la utilidad del total de venta por un rango de período" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_utilidad') ?>" >Total de Utilidad</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_inventario']['original'] ?>"> 
                MOVIMIENTOS DE INVENTARIOS
                <ul>
                    <?php if (in_array('88', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe_movimientos" data-container="body" data-content="Muestra los movimientos del inventario y las ventas de los productos / movimientos" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/informe_movimientos') ?>" >Exportar movimientos de producto</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('89', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="stock_diario" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/stock_diario') ?>" >Exportar movimientos de material</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1034',$permisos) || $is_admin == 't') { ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe_auditoria_view" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('auditoria/informe_auditoria_view') ?>">Auditorias inventario </a>
                    </li>
                    <?php } ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('produccion/informe') ?>" >Ordenes de Producción</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_credito']['original'] ?>"> 
                CREDITOS Y PAGOS
                <ul>
                    <?php if (in_array('91', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/pagosrecibidos') ?>" > Exportar Pagos recibidos </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1014', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_saldo_clientes') ?>" > Búsqueda Saldo de Cliente </a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('92', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('credito/index') ?>" > Cuentas por cobrar </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row file-informes">
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_caja']['original'] ?>"> 
                CAJA
                <ul>
                    <?php if (in_array('89', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/cuadre_caja') ?>" > Cuadre de caja </a>
                    </li>
                    <?php } ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('caja/cierre_caja_periodo') ?>" > Cierres de Cajas por Fecha </a>
                    </li>
                </ul>
            </div>
        </div>
                        
        
        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-gbp" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_comisiones']['original'] ?>"> 
                COMISIONES
                <ul>
                    <?php if(in_array('95', $permisos) || $is_admin == "t"){ ?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasVendedor" data-container="body" data-content="MUestra la cantidad de producto que un vendedor a vendido asociandolo a la factura" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasVendedor') ?>" >Productos vendidos por vendedor</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('74', $permisos) || $is_admin == 't') {?>
                    <!--<li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_comisiones') ?>">Informe de comisiones por vendedor</a>
                    </li>-->
                    <?php } ?>
                    <?php if (in_array('94', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas_vendedor" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_vendedor') ?>" >Comisiones por vendedor</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('1011', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_vendedor_utilidad') ?>" >Comisión y utilidad por Factura</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_impuesto']['original'] ?>"> 
                IMPUESTOS
                <ul>
                    <?php if (in_array('76', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="informe_impuestos" data-container="body" data-content="Descrimina la venta i el impuesto a pagar por cada factura" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_impuestos') ?>" >Facturas con Impuestos</a>
                    </li>
                    <?php } ?>
                    <?php if (in_array('79', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="total_ventas_impuesto" data-container="body" data-content="Informa el total de impuesto por el total de venta" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_impuesto') ?>" >Resumen Impuestos</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row file-informes">
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_devoluciones']['original'] ?>"> 
                DEVOLUCIONES
                <ul>
                    <?php if (in_array('1023', $permisos) || $is_admin == 't') {?>    
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="notas" data-container="body" data-content="Nos informa de las notas credito y el estado en el que se encuentra la nota credito" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/notas') ?>" >Estado Notas Crédito y Debito</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <!--<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>-->
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_propina']['original'] ?>"> 
                PROPINAS Y COMANDAS
                <ul>
                    <?php if (in_array('82', $permisos) || $is_admin == 't') {?>
                    <li>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="export_propina" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/export_propina') ?>" >  Exportar Facturas con Propina </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        
        <!-- item -->
        <div class="col-md-4">
            <div class="col-md-10">
                <img alt="ventas" class="iconimginformes" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes_archivo_erp']['original'] ?>"> 
                BANCOS
                <ul>
                    <li>
                        <span class="glyph  icon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="bancos" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/bancos') ?>" >Mis bancos</a>
                    </li>

                     <li>
                        <span class="glyph  icon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="movimientos_bancarios" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/movimientos_bancarios') ?>" >Movimientos bancarios</a>
                    </li>

                     <li>
                        <span class="glyph  icon glyphicon-menu-right" aria-hidden="true"></span>
                        <a id="conciliaciones" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/conciliaciones') ?>" >Mis conciliaciones</a>
                    </li>
                </ul>
            </div>
        </div>

        
    </div>
</div>

<script>
    $('#total_ventas,#utilidad_periodo,#ventasxclientes,#total_utilidad,#ventas_group_clientes,#comprasCliente,#ventasVendedor,#ventas_por_vendedor,#ventas_por_tomapedido,#informe_impuestos,#total_ventas_hora,#total_ventas_dia,#total_ventas_mes,#total_ventas_impuesto,#ventas_categoria,#transacionesinforme,#total_ventas_atributos,#clientes_puntos_acumulados,#total_formas_pago,#notas,#valor_inventario,#habitos_consumo_hora,#habitos_consumo_dia,#habitos_consumo_mes,#orden_compra_productos,#menos_rotacion,#existensias_inventario,#transacciones,#informe_movimientos,#stock_diario,#consolidado_inventario,#historial_inventario,#informe_lista_de_precios,#plan_separe_productos,#total_inventario_atributos,#pagosrordencompra,#informe_auditoria_view,#stock_minimo_maximo,#informe_producto_por_almacen,#ventasDomiciliarios').popover();
</script>
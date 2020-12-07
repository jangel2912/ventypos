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
    
</style>
<div class="row">                                             
    <div class="col-xs-12 col-md-12">
        <div class="example-col panel" style=" padding: 5px 15px;">
            <div class="page-header">
                <div class="icon">
                    <span class="ico-box"></span>
                </div>
                <h1><?php echo custom_lang("Informes", "Informes"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
            </div>
        </div>
    </div>
</div>
<?php
$message = $this->session->flashdata('message');
if (!empty($message)):
?>
    <div class="alert alert-success">
        <?php echo $message; ?>
    </div>
<?php
endif;
$permisos = $this->session->userdata('permisos');
$is_admin = $this->session->userdata('is_admin');
?>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center"><?php echo custom_lang('margin_utility', "VENTAS Y UTILIDAD"); ?></h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <tbody>
                            <?php if (in_array('', $permisos) || $is_admin == 't') {?>
                                <!--<tr>
                                    <td width="100%">
                                        <a id="grafica_ventas_almacen" data-container="body" data-content="Muestra unas graficas de venta por almacen" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/grafica_ventas_almacen') ?>" >Grafica de Ventas por Almacén</a>
                                    </td>
                                </tr>-->
                            <?php }?>
                            <?php if (in_array('73', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="utilidad_periodo" data-container="body" data-content="Calcula la utilidad con respecto a un periodo ingresado" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/utilidad_periodo') ?>" >Utilidad de Operaci&oacute;n del Periodo</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('74', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="ventasxclientes" data-container="body" data-content="Calcula la utilidad de cada venta" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasxclientes') ?>" >Ventas por Utilidad</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if ($is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_utilidad" data-container="body" data-content="Calcula  la utilidad del total de venta por un rango de periodo" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_utilidad') ?>" >Total de Utilidad</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('75', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="ventas_group_clientes" data-container="body" data-content="Muestra las ventas por cada cliente y cada factura" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasgroupclientes') ?>" >Ventas por clientes</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%">
                                        <a id="comprasCliente" data-container="body" data-content="Muestra la cantidad de producto compradas por cada cliente" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/comprasCliente') ?>" >Ventas detallas por clientes</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if(in_array('95', $permisos) || $is_admin == "t")
                            {
                                ?>
                                <tr>
                                    <td width="100%">
                                        <a id="ventasVendedor" data-container="body" data-content="MUestra la cantidad de producto que un vendedor a vendido asociandolo a la factura" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventasVendedor') ?>" >Ventas detallas por vendedor</a>
                                    </td>
                                </tr>   
                                <?php
                            }?>
                            <?php if (in_array('76', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="informe_impuestos" data-container="body" data-content="Descrimina la venta i el impuesto a pagar por cada factura" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_impuestos') ?>" >Informe de Impuestos</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('77', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_ventas_hora" data-container="body" data-content="Filtra el total de venta de cada hora" rel="popover" data-placement="right" data-original- data-trigger="hover"  href="<?php echo site_url('informes/total_ventas_hora') ?>" > Total de Ventas por Hora </a></td> </tr>
                            <?php }?>
                            <?php if (in_array('78', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_ventas_dia" data-container="body" data-content="Filtra el total de venta de cada dia" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_dia') ?>" >Total de Ventas por Dia</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('79', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_ventas_mes" data-container="body" data-content="Muestra el total de venta segun un rango de periodo" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_mes') ?>" >Total de Ventas por Mes</a>
                                    </td> 
                                </tr>
                            <?php }?>
                            <?php if (in_array('79', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_ventas_impuesto" data-container="body" data-content="Informa el total de impuesto por el total de venta" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_impuesto') ?>" >Total de Ventas por Impuestos</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('80', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="ventas_categoria" data-container="body" data-content="Calcula la venta total por categorias en un rango de fecha" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/ventas_categoria') ?>" >Ventas por Categoria</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('1013', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="transacionesinforme" data-container="body" data-content="Nos descarga la facturacion hecha en un rango de fechas" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/transacionesinforme') ?>" >Informe de Transacciones</a>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php if ((in_array('80', $permisos) || $is_admin == 't') && $data['atributos']) {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_ventas_atributos" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_atributos') ?>" >Informe de Total de Ventas por Atributos</a>
                                    </td>
                                </tr>                            
                            <?php }?>
                            <?php if ((in_array('1016', $permisos) || $is_admin == 't') && $data['puntos']) {?>
                                <tr>
                                    <td width="100%">
                                        <a id="clientes_puntos_acumulados" data-container="body" data-content="Muestra el total de puntos acumulados por cliente y el valor que tiene esos puntos" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/clientes_puntos_acumulados') ?>" >Puntos Acumulados por Cliente</a>
                                    </td>
                                </tr>
                             <?php }?>
                            <?php if (in_array('80', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="total_formas_pago" data-container="body" data-content="Te muestra los pagos dependiendo la forma de pago por rango de fecha descontando las notas credito" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_formas_pago') ?>" >Resumen por medios de pago</a>
                                    </td>
                                </tr>
                             <?php }?>
                            <?php if (in_array('1023', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%">
                                        <a id="notas" data-container="body" data-content="Nos informa de las notas credito y el estado en el que se encuentra la nota credito" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/notas') ?>" >Informe Notas Credito y Debito</a>
                                    </td>
                                </tr>
                             <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center"><?php echo custom_lang('margin_utility', "INVENTARIO Y PRODUCTOS"); ?></h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <tbody>
                            <?php if (in_array('84', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="valor_inventario" data-container="body" data-content="Esto nos muestra el total del valor del inventario segun el costo del producto tambien el total de venta y el margen de utilidad" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/valor_inventario') ?>" >Valor de Inventario</a></td>
                                </tr>
                            <?php }?>
                            <?php /*if (in_array('85', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="habitos_consumo_hora" data-container="body" data-content="Muestra la cantidad de productos vendidos filtrados por hora de venta y totaliza el valor del producto" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/habitos_consumo_hora') ?>" >Hábitos de Consumo por Hora</a></td>
                                </tr>
                            <?php }*/?>
                            <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="habitos_consumo_dia" data-container="body" data-content="Muestra la cantidad de productos vendidos filtrados por dia de venta y totaliza el valor del producto" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/habitos_consumo_dia') ?>" >Hábitos de Consumo por día</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="habitos_consumo_mes" data-container="body" data-content="Muestra la cantidad de productos vendidos filtrados por mes de venta y totaliza el valor del producto" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/habitos_consumo_mes') ?>" >Hábitos de Consumo por Mes</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('85', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="orden_compra_productos" data-container="body" data-content="Nos muestra los productos comprados los cuales podemos mostrar por producto o por proveedor" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/orden_compra_productos') ?>" >Informe de los productos comprados</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('86', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="menos_rotacion" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/menos_rotacion') ?>" >Inventario con Menos Rotaci&oacute;n </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('87', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="existensias_inventario" data-container="body" data-content="Nos muestra la existencia que tenemos de nuestro producto en tiempo real y por almacen con precio de compra y venta" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/existensias_inventario') ?>" >Existencias de inventario</a></td>
                                </tr>
                            <?php }?>
                            <?php if ($is_admin == 't') {?>
                                <!-- <tr>
                                    <td width="100%"><a href="<?php echo site_url('informes/stock_minimo_maximo') ?>" > Stock Actual</a></td>
                                </tr> -->
                            <?php }?>
                            <?php if (in_array('88', $permisos) || $is_admin == 't') {?>
                                <!--<tr>
                                    <td width="100%"><a id="transacciones" data-container="body" data-content="Muestra el movimiento que se tiene de cada producto ingresos de inventario por compra y las salidas por venta" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/transacciones') ?>" >Informe de transacciones de inventario</a></td>
                                </tr>-->
                            <?php }?>
                            <?php if (in_array('88', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="informe_movimientos" data-container="body" data-content="Muestra los movimientos del inventario y las ventas de los productos / movimientos" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/informe_movimientos') ?>" >Informe de movimientos de producto</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('89', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="stock_diario" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/stock_diario') ?>" >Informe movimiento material</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('1012', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="consolidado_inventario" data-container="body" data-content="Este informa el total de los productos que se se tiene en todo el sistema por producto sumando todos los almacenes" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/consolidado_inventario') ?>" >Informe de consolidado</a></td>
                                </tr>
                            <?php }?>
                        </tbody>
                        <?php if (in_array('1012', $permisos) || $is_admin == 't') {?>
                            <!--
                            <tr>
                                <td width="100%"><a href="<?php echo site_url('informes/productos') ?>" >Informe de existencias de productos por atributos</a></td>
                            </tr>
                            -->
                        <?php }?>
                        <!--<?php if (in_array('1017', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="historial_inventario" data-container="body" data-content="Este informe nos muestra el historial del inventario" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/historial_inventario') ?>" >Historial de inventario</a></td>
                            </tr>
                        <?php }?>-->
                        <?php if (in_array('1018', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="informe_lista_de_precios" data-container="body" data-content="Este informe nos descarga en un Ecxel el listado de precios junto al descuento asociado en la lista" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/informe_lista_de_precios') ?>" >Informe lista de precios</a></td>
                            </tr>
                        <?php }?>
                        <?php if (in_array('', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="plan_separe_productos" data-container="body" data-content="Consulta los productos que se encuentran en los planes separe" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/plan_separe_productos') ?>" >Informe de plan separe</a></td>
                            </tr>
                        <?php }?>
                        <?php if ((in_array('80', $permisos) || $is_admin == 't') && $data['atributos']) {?>
                            <tr>
                                <td width="100%">
                                    <a id="total_inventario_atributos" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/total_inventario_atributos') ?>" >Informe de Total de Inventario por Atributos</a>
                                </td>
                            </tr>                            
                        <?php }?>
                        <?php if ( in_array("71", $permisos ) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%">
                                    <a id="pagosrordencompra" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('orden_compra/pagosrordencompra') ?>" >Informe orden de compra</a>
                                </td>
                            </tr>                            
                        <?php }?>
                            
                            <tr>
                                <td width="100%">
                                    <a id="informe" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('produccion/informe') ?>" >Informe de Producción</a>
                                </td>
                            </tr> 
                            <?php if (in_array('1034',$permisos) || $is_admin == 't') { ?>
                              <tr>
                                  <td width="100%">
                                      <a id="informe_auditoria_view" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('auditoria/informe_auditoria_view') ?>">Informe de auditorias inventario </a>
                                  </td>
                              </tr>    
                            <?php } ?>
                            <?php if ($is_admin == 't') { ?>
                             <!-- <tr>
                                  <td width="100%">
                                      <a href="<?php echo site_url('informes/informe_devolucion_view') ?>">Informe devoluciones </a>
                                  </td>
                              </tr>-->
                                
                                <tr>
                                    <td>
                                        <a  id="stock_minimo_maximo" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url("informes/stock_minimo_maximo"); ?>">
                                            Stock Minimo y Maximo
                                        </a>
                                    </td>
                                </tr>     
                            <?php } ?>

                             <tr>
                                    <td>
                                        <a id="informe_producto_por_almacen" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url("informes/informe_producto_por_almacen"); ?>">
                                            
                                        Consulta de producto por almacen
                                        </a>
                                    </td>
                                </tr> 
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center">
                            <?php echo custom_lang('margin_utility', "CAJA"); ?>
                        </h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <tbody>
                            <?php if (in_array('89', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/cuadre_caja') ?>" > Cuadre de caja </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('90', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_gastos') ?>" > Informe de gastos </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('91', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/pagosrecibidos') ?>" > Pagos recibidos </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('1014', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_saldo_clientes') ?>" > Saldo total por clientes </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('1015', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_saldo_proveedor') ?>" > Saldo total por proveedores </a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('92', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('credito/index') ?>" > Cuentas por cobrar </a></td>
                                </tr>
                            <?php }?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="right" data-original- data-trigger="hover" href="<?php echo site_url('caja/cierre_caja_periodo') ?>" > Cierre de Cajas por Fecha </a></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-4">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center">
                            <?php echo custom_lang('margin_utility', "COMISIONES"); ?>
                        </h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <tbody>
                            <?php if (in_array('93', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_comisiones') ?>">Informe de comisiones por vendedor</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('94', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/total_ventas_vendedor') ?>" >Total de comisiones por vendedor</a></td>
                                </tr>
                            <?php }?>
                            <?php if (in_array('1011', $permisos) || $is_admin == 't') {?>
                                <tr>
                                    <td width="100%"><a id="ventasxclientes" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover" href="<?php echo site_url('informes/informe_vendedor_utilidad') ?>" >Total de comisiones de vendedor por utilidad</a></td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="example-col panel">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center">
                            <?php echo custom_lang('margin_utility', "EXPORTACIONES"); ?>
                        </h3>
                        <hr>
                    </div>
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">
                        <?php if (in_array('81', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="export_erp" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/export_erp') ?>" >Exportaci&oacute;n PeopleSoft</a></td>
                            </tr>
                        <?php }?>
                        <!--<?php if (in_array('82', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="export_office" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/export_office') ?>" > Exportaci&oacute;n WorldOffice </a></td>
                            </tr>
                        <?php }?>
                        <?php if ((in_array('82', $permisos) || $is_admin == 't' ) && ($siigo)) {?>
                            <tr>
                                <td width="100%"><a id="export_siigo" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/export_siigo') ?>" > Exportaci&oacute;n Siigo </a></td>
                            </tr>
                        <?php }?>-->
                        <?php if (in_array('82', $permisos) || $is_admin == 't') {?>
                            <tr>
                                <td width="100%"><a id="export_propina" data-container="body" data-content="" rel="popover" data-placement="left" data-original- data-trigger="hover"  href="<?php echo site_url('informes/export_propina') ?>" >  Exportaci&oacute;n Ventas con Propina </a></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <?php 
        if(count($franquicias) > 0)
        {
    ?>
    <div class="col-xs-12 col-sm-4">
        <div class="example-col panel">
            <div class="panel-heading">
                <h3 class="panel-title" style="text-align: center">
                    <?php echo custom_lang('franquicias', "FRANQUICIAS"); ?>
                </h3>
                <hr>
                <table class="table aTable" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <a href="<?php echo site_url('informes/total_inventario_franquicia') ?>" >
                                <?php echo custom_lang('informe_total_inventario_franquicia', 'Existencias de inventario'); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('informes/total_inventario_atributos_franquicia') ?>" >
                                <?php echo custom_lang('informe_total_inventario_atributos_franquicia', 'Existencias de inventario por atributos'); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('informes/total_ventas_franquicia')  ?>" >
                                <?php echo custom_lang('informe_total_ventas_franquicia', 'Total ventas'); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('informes/total_ventas_atributos_franquicia')  ?>" >
                                <?php echo custom_lang('informe_total_ventas_atributos_franquicia', 'Total ventas por atributos'); ?>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
    $('#utilidad_periodo,#ventasxclientes,#total_utilidad,#ventas_group_clientes,#comprasCliente,#ventasVendedor,#informe_impuestos,#total_ventas_hora,#total_ventas_dia,#total_ventas_mes,#total_ventas_impuesto,#ventas_categoria,#transacionesinforme,#total_ventas_atributos,#clientes_puntos_acumulados,#total_formas_pago,#notas,#valor_inventario,#habitos_consumo_hora,#habitos_consumo_dia,#habitos_consumo_mes,#orden_compra_productos,#menos_rotacion,#existensias_inventario,#transacciones,#informe_movimientos,#stock_diario,#consolidado_inventario,#historial_inventario,#informe_lista_de_precios,#plan_separe_productos,#total_inventario_atributos,#pagosrordencompra,#informe_auditoria_view,#stock_minimo_maximo,#informe_producto_por_almacen').popover();
</script>
<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Informes", "Informes"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php
            $message = $this->session->flashdata('message');

            if (!empty($message)):
                ?>

                <div class="alert alert-success">

                    <?php echo $message; ?>

                </div>

            <?php endif; ?>

            <div class="data-fluid">             
                <?php
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');

                    ?>

                    <div class="head blue">

                        <div class="icon"><i class="ico-box"></i></div>

                        <h2><?php echo custom_lang('margin_utility', "VENTAS Y UTILIDAD"); ?></h2>

                    </div>

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">

                        <tbody>
                <?php if(in_array('', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Grafica de Ventas por Almac&eacute;n</td>

                                <td width="15%"><a href="<?php echo site_url('informes/grafica_ventas_almacen') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('73', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Utilidad de Operaci&oacute;n del Periodo</td>

                                <td width="15%"><a href="<?php echo site_url('informes/utilidad_periodo') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('74', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Ventas por Utilidad</td>

                                <td width="15%"><a href="<?php echo site_url('informes/ventasxclientes') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if( $is_admin == 't'){ ?>                         
                            <tr>

                                <td width="85%">Total de Utilidad</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_utilidad') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('75', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Ventas por clientes</td>

                                <td width="15%"><a href="<?php echo site_url('informes/ventasgroupclientes') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('76', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de Impuestos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_impuestos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('77', $permisos) || $is_admin == 't'){ ?>                             
                            <tr>

                                <td width="85%">Total de Ventas por Hora</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_hora') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('78', $permisos) || $is_admin == 't'){ ?>                             
                            <tr>

                                <td width="85%">Total de Ventas por Dia</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_dia') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                     <?php } ?> 
                <?php if(in_array('79', $permisos) || $is_admin == 't'){ ?>                         
                            <tr>

                                <td width="85%">Total de Ventas por Mes</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_mes') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('79', $permisos) || $is_admin == 't'){ ?>                         
                            <tr>

                                <td width="85%">Total de Ventas por Impuestos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_impuesto') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('80', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Ventas por Categoria</td>

                                <td width="15%"><a href="<?php echo site_url('informes/ventas_categoria') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>           
                 <?php } ?> 
                <?php if(in_array('1013', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de Transacciones</td>

                                <td width="15%"><a href="<?php echo site_url('informes/transacionesinforme') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>           
                 <?php } ?>  
                <?php if(in_array('1016', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Puntos Acumulados por Cliente</td>

                                <td width="15%"><a href="<?php echo site_url('informes/clientes_puntos_acumulados') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>           
                 <?php } ?> 
                <?php if(in_array('80', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Resumen por medios de pago</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_formas_pago') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>           
                 <?php } ?> 
                <?php if(in_array('80', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de Total de Ventas por Atributos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_atributos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>           
                 <?php } ?>                                                                                                                                           
                        </tbody>

                    </table>

                    <div class="head yellow">

                        <div class="icon"><i class="ico-box"></i></div>

                        <h2><?php echo custom_lang('margin_utility', "INVENTARIO Y PRODUCTOS"); ?></h2>

                    </div>

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">

                        <tbody>
                <?php if(in_array('84', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Valor de Inventario</td>

                                <td width="15%"><a href="<?php echo site_url('informes/valor_inventario') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('85', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Habitos de Consumo por Hora</td>

                                <td width="15%"><a href="<?php echo site_url('informes/habitos_consumo_hora') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('85', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Habitos de Consumo por dia</td>

                                <td width="15%"><a href="<?php echo site_url('informes/habitos_consumo_dia') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('85', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Habitos de Consumo por Mes</td>

                                <td width="15%"><a href="<?php echo site_url('informes/habitos_consumo_mes') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php }  ?>    
                <?php if(in_array('85', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de los productos comprados</td>

                                <td width="15%"><a href="<?php echo site_url('informes/orden_compra_productos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php }  ?>                     
                <?php if(in_array('86', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Inventario con Menos Rotaci&oacute;n </td>

                                <td width="15%"><a href="<?php echo site_url('informes/menos_rotacion') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr> 
                 <?php } ?> 
                <?php if(in_array('87', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Existencias de inventario</td>

                                <td width="15%"><a href="<?php echo site_url('informes/existensias_inventario') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if($is_admin == 't'){ ?>  
                            <tr>

                                <td width="85%">Stock actual</td>

                                <td width="15%"><a href="<?php echo site_url('informes/stock_minimo_maximo') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('12232434', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de transacciones de inventario</td>

                                <td width="15%"><a href="<?php echo site_url('informes/transacciones') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('88', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de movimientos de inventario</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_movimientos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                 <?php if(in_array('89', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe movimiento material</td>

                                <td width="15%"><a href="<?php echo site_url('informes/stock_diario') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                 <?php if(in_array('1012', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de consolidado</td>

                                <td width="15%"><a href="<?php echo site_url('informes/consolidado_inventario') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                        </tbody>
                 <?php if(in_array('1012', $permisos) || $is_admin == 't'){ ?> 
                        <!--
                            <tr>

                                <td width="85%">Informe de existencias de productos por atributos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/productos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                        -->
                 <?php } ?> 
                 <?php if(in_array('1017', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Historial de inventario</td>

                                <td width="15%"><a href="<?php echo site_url('informes/historial_inventario') ?>" >Ver </a></td>

                            </tr>
                 <?php } ?> 
                 <?php if(in_array('1018', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe lista de precios</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_lista_de_precios') ?>" >Ver </a></td>

                            </tr>
                 <?php } ?> 
                 <?php if($is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de plan separe</td>

                                <td width="15%"><a href="<?php echo site_url('informes/plan_separe_productos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                        </tbody>

                    </table>

                    <div class="head green">

                        <div class="icon"><i class="ico-box"></i></div>

                        <h2><?php echo custom_lang('margin_utility', "CAJA"); ?></h2>

                    </div>

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">

                        <tbody>
                <?php if(in_array('89', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Cuadre de caja</td>

                                <td width="15%"><a href="<?php echo site_url('informes/cuadre_caja') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('90', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de gastos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_gastos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('1014', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Pagos recibidos</td>

                                <td width="15%"><a href="<?php echo site_url('informes/pagosrecibidos') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('1015', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Saldo total por clientes</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_saldo_clientes') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('91', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Saldo total por proveedores</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_saldo_proveedor') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('92', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Cuentas por cobrar</td>

                                <td width="15%"><a href="<?php echo site_url('credito/index') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                        </tbody>
                    </table>

                    <div class="head red">

                        <div class="icon"><i class="ico-box"></i></div>

                        <h2><?php echo custom_lang('margin_utility', "COMISIONES"); ?></h2>

                    </div>

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">

                        <tbody>
                <?php if(in_array('93', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Informe de comisiones por vendedor</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_comisiones') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('94', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Total de comisiones por vendedor</td>

                                <td width="15%"><a href="<?php echo site_url('informes/total_ventas_vendedor') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
             <?php } ?>     
                 <?php if(in_array('1011', $permisos) || $is_admin == 't'){ ?>    
                            <tr>

                                <td width="85%">Total de comisiones de vendedor por utilidad</td>

                                <td width="15%"><a href="<?php echo site_url('informes/informe_vendedor_utilidad') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
             <?php } ?>                     


                        </tbody>
                    </table>

                    <div class="head purple">

                        <div class="icon"><i class="ico-box"></i></div>

                        <h2><?php echo custom_lang('margin_utility', "EXPORTACIONES"); ?></h2>

                    </div>
              
              <table class="table aTable" cellpadding="0" cellspacing="0" width="50%" id="informesTable">                
                <?php if(in_array('81', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Exportaci&oacute;n PeopleSoft</td>

                                <td width="15%"><a href="<?php echo site_url('informes/export_erp') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('82', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Exportaci&oacute;n WorldOffice</td>

                                <td width="15%"><a href="<?php echo site_url('informes/export_office') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?> 
                <?php if(in_array('82', $permisos) || $is_admin == 't'){ ?> 
                            <tr>

                                <td width="85%">Exportaci&oacute;n Ventas con Propina</td>

                                <td width="15%"><a href="<?php echo site_url('informes/export_propina') ?>" class="btn btn-primary"><i class="ico-arrow-right"></i> Ver</a></td>

                            </tr>
                 <?php } ?>
                </table>                                                                                                                                 
                        </tbo

            ></div>

        </div>



    </div>

</div>
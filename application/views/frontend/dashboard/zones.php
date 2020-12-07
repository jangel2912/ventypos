<?php

$resultPermisos = getPermisos();
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];
?>
<br>
<?php if (isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante" && (in_array("1038", $permisos) || $isAdmin == 't')): ?>
    <div role="tabpanel" class="tab-pane active"
         id="restaurante_home">


        <!--TABS-->
        <!--
                <div class="col-lg-offset-3 col-lg-9 col-md-offset-3 col-md-9 col-sm-12">

                    <div class="containerTabsR">
                        <div class="row">
                            <?php
        foreach ($data['zonas'] as $key => $value) {
            $active = "";
            if ($value->id == $data['zonas'][0]->id)
                $active = "activeTabMesero";
            echo '<div role="zonas" class="individualTab col-xs-12 col-sm-12 col-md-2 col-lg-2 col-2 ' . $active . '" data-id="' . $value->id . '" id="tabMesero' . $value->id . '" id="">
                                <a onclick="selectTab(' . $value->id . ')" aria-controls="' . $value->id . '" role="tab" data-toggle="tab" href="#' . $value->id . '">' . strtoupper($value->nombre_seccion) . '</a>
                                </div>';
        }
        ?>
                        </div>
                    </div>
                </div>
                -->
        <!--tabs-->
        <!--
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 col-lg-offset-5 col-md-offset-5 col-sm-offset-5 tab_panel" >
                    <div class="row centrarbtn">
                        <?php
        foreach ($data['zonas'] as $key => $value) {
            $active = "";
            if ($value->id == $data['zonas'][0]->id)
                $active = "activeTabMesero";
            echo '<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2"><a class="tz" onclick="selectTab(' . $value->id . ')" aria-controls="home" role="tab" data-toggle="tab" href="#' . $value->id . '">
                                <div role="zonas" class="btnmesas ' . $active . '" data-id="' . $value->id . '" id="tabMesero' . $value->id . '" id="">' . strtoupper($value->nombre_seccion) . '</div></a></div>';
        }
        ?>
                    </div>
                </div> -->

        <!-- ROW INFO-->
        <div class="row">
            <!--INFO USER-->
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <!--
                    <div class="containerMeseroInfo">
                        <div class="row">
                            <div class="col-12">
                                Hola <?php echo $this->session->userdata('username') ?>
                            </div>
                        </div>
                        <div class="row logoEmpresa">
                            <div class="col-12">
                                <?php if (!empty($data["datos_empresa_ap"]['data']['logotipo'])) { ?>
                                    <img src="<?php echo base_url('uploads') . '/' . $data["datos_empresa_ap"]['data']['logotipo']; ?>" alt=""/>
                                <?php } else { ?>
                                    <div style="font-weight: bold">Mi Empresa</div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <img class="logoVendty" src="<?php echo $this->session->userdata('new_imagenes')['logo_vendty_color']['original'] ?>" alt="logo">
                            </div>
                        </div>
                    </div>
                    -->
                <!--ordenes a pagar -->
                <div class="containerBoxBottom">
                    <h5>Órdenes Pendientes</h5>
                    <div class="content_ordenes_pendientes"
                         style="overflow-x: hidden !important; overflow-y: auto !important;">
                        <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                            <thead style="font-weight:bold;">
                            <tr>
                                <th>Fecha de Creación</th>
                                <th>Zona</th>
                                <th>Mesa</th>
                                <!--<th>Productos</th>-->
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody id="ordenes_pendientes" class="ordenes_pendientes"></tbody>
                        </table>
                    </div>
                </div>
                <!--ordenes por formas de pago-->
                <?php if ($data['permitir_formas_pago_pendiente'] == 'si') { ?>
                    <div class="containerBoxBottom">
                        <h5>Facturas Pendientes por Forma de Pago</h5>
                        <div class="content_ordenes_pendientes"
                             style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                <tr>
                                    <th>Factura</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody id="facturas_pendientes_pago" class="ventas_pendientes"></tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!--INFO MESAS-->
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 tab_panel">
                <div class="row centrarbtn">
                    <?php
                    foreach ($data['zonas'] as $key => $value) {
                        $active = "";
                        if ($value->id == $data['zonas'][0]->id)
                            $active = "activeTabMesero";
                        echo '<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2"><a class="tz" onclick="selectTab(' . $value->id . ')" aria-controls="home" role="tab" data-toggle="tab" href="#' . $value->id . '">
                                <div role="zonas" class="btnmesas ' . $active . '" data-id="' . $value->id . '" id="tabMesero' . $value->id . '" id="">' . strtoupper($value->nombre_seccion) . '</div></a></div>';
                    }
                    ?>
                </div>

                <div class="tab-content" style="height: 50vh; overflow-y: auto;">
                    <!-- Zonas -->
                    <?php
                    // print_r($data["mesas_secciones"]);
                    ?>
                    <?php $i = 0;
                    foreach ($data["zonas"] as $zona):
                        $active = "";
                        $classmesa = "content-mesa";
                        ?>

                        <div role="tabpanel" class="tab-pane <?php echo ($i == 0) ? 'active' : ''; ?>"
                             id="<?= $zona->id; ?>">
                            <?php $j = 0;
                            foreach ($data["mesas_secciones"] as $mesa): ?>
                                <?php if ($zona->id == $mesa->id_seccion):
                                    $comensales = ($mesa->comensales > 0) ? $mesa->comensales : '';
                                    $estado = ($mesa->pedidos > 0) ? 'verde' : 'gris';
                                    $classmesa = ($mesa->pedidos > 0) ? "content-mesa-active" : "content-mesa";
                                    $fecha_creacion = '';
                                    if (!empty($mesa->fecha_creacion)) {
                                        $fecha_creacion = new DateTime($mesa->fecha_creacion);
                                        $fecha_creacion = $fecha_creacion->format('H:i');
                                    }
                                    if ($j == 0) {
                                        $ref_mesa = site_url('orden_compra/mi_orden/') . '/-1/' . strtotime("now"); ?>
                                        <div class="col-md-2 col-sm-6 col-xs-6 text-center panel_mesa">
                                            <div class="<?= $classmesa ?>">
                                                <a href="<?= $ref_mesa ?>">
                                                    <!--<img class="mesa" src="<?= base_url() . 'uploads/mesa-' . $estado . '.svg'; ?>" alt="">-->
                                                    <img class="mesa"
                                                         src="<?= base_url() . 'uploads/tables/mesa' . get_option('table_selected') . '_gris.svg'; ?>"
                                                         alt="barra">
                                                    <span class="nombre_mesa">Barra</span>
                                                </a>
                                                <!--<a onclick='location.href="<?php echo $ref_mesa; ?>"'>
                                                            <div class="panelMesas <?php echo ($mesa->pedidos > 0) ? 'panel-danger-new' : 'panel-success-new'; ?> rounded shadow">
                                                                <div class="text-center ">
                                                                    <img src="<?php echo $this->session->userdata('new_imagenes')['rest_barra']['original'] ?>" alt="barra">
                                                                </div>
                                                            </div>
                                                            <b style="color: #000; text-transform: capitalize !important;"> Barra </b>
                                                        </a>-->
                                            </div>
                                        </div>
                                        <?php $j++;
                                    } ?>


                                    <?php if ($mesa->id != '-1'):
                                    $ref_mesa = site_url('orden_compra/mi_orden/') . '/' . $zona->id . '/' . $mesa->id; ?>
                                    <div class="col-md-2 col-sm-6 col-xs-6 text-center panel_mesa">
                                        <div class="<?= $classmesa ?>">
                                            <a href="<?= $ref_mesa ?>">
                                                <!--<img class="mesa" src="<?= base_url() . 'uploads/mesa-' . $estado . '.svg'; ?>" alt="">-->
                                                <img class="mesa"
                                                     src="<?= base_url() . 'uploads/tables/mesa' . get_option('table_selected') . '_' . $estado . '.svg'; ?>"
                                                     alt="mesa">
                                                <span class="nombre_mesa"><?= $mesa->nombre_mesa; ?></span>
                                                <div class="fecha_creacion_comanda">
                                                    <?= $fecha_creacion ?>
                                                </div>
                                            </a>
                                            <?php if ($mesa->comensales > 0) { ?>
                                                <div class="comensales"><?= $comensales ?></div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </div>
                        <?php $i++; endforeach; ?>
                </div>

            </div>
        </div>

        <div class="row">

            <!-- Pedidos a pagar -->
            <div class="row">
                <!--
                    <div class="col-md-6 containerBoxBottom">
                        <h5>Órdenes Pendientes</h5>
                        <div class="col-md-12 content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Fecha de Creación</th>
                                        <th>Zona</th>
                                        <th>Mesa</th>
                                        <th>Productos</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="ordenes_pendientes" class="ordenes_pendientes"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 containerBoxBottom">
                        <h5>Facturas Pendientes por Forma de Pago</h5>
                        <div class="col-md-12 content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Factura</th>
                                        <th>Fecha de Creación</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="facturas_pendientes_pago" class="ventas_pendientes"></tbody>
                            </table>
                        </div>
                    </div> -->
            </div>
        </div>
    </div>
    </div>
<?php endif; ?>

<?php
    $impuestopredeterminado=$data['impuesto']->porciento;
?>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-forma-pago">
 <div class="modal-dialog modal-lg" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close close-modal-forma-pago" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
       <h4 class="modal-title">Formas de Pagos Pendientes a la Factura N° <span id="factura_forma"></span></h4>
     </div>
     <div class="modal-body">
        <div class="container">
    <div class="col-md-12">
        <form class="form-horizontal" id="form_pago" method="POST">
            <input type="hidden" class="form-control" id="factura" name="factura" value="0">
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor a Pagar:</label>
                <div class="col-md-10">
                <input type="number" disabled index="" class="form-control" id="valor_a_pagar" name="valor_a_pagar" placeholder="valor_a_pagar" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                <div class="col-md-10">
                    <select name="forma_pago" id="forma_pago" class="form-control forma_pago" data-id="">
                        <?php
                        foreach ($data['forma_pago'] as $f) {
                            if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                            ?>
                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group" id="pago_datafono" style="display:none">
                <div class="col-md-2"> </div>
                <div class="col-md-10">
                    <div class="col-md-3">
                    Subtotal <input id="subtotal" class="subtotal" type="text" disabled="true" value="0">
                    </div>
                    <div class="col-md-3">
                    IVA <input class="impuesto" id="impuesto" type="text" disabled="true" value="0" >
                    </div>
                    <div class="col-md-3">
                    Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono" id="impuestoDatafono" data-id="" value="<?php echo $impuestopredeterminado; ?>">
                    </div>
                    <div class="col-md-3">
                    N° Transacción <input type="text" name="transaccion" id="transaccion" value="">
                    </div>
                </div>
            </div>
            <div id="fecha_vencimiento_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                <div class="col-md-10">
                    <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" />
                </div>
            </div>
            <div id="nota_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                <div class="col-md-9">
                   <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                </div>
                <div class="col-md-1">
                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                </div>

            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                <div class="col-md-10">
                    <input type="number" class="form-control valor_entregado" id="valor_entregado" name="valor_entregado" data-id="" placeholder="valor Entregado" value="">
                </div>
            </div>

            <div id="contenido_a_mostrar1" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago1" id="forma_pago1" class="form-control forma_pago" data-id="1">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer" class="eliminar_forma_pago">
                            <i class="glyphicon glyphicon-trash" data-id="1" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado1" name="valor_entregado1" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono1" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal1" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto1" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono1" data-id="1" id="impuestoDatafono1" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion1" id="transaccion1" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta1" id="fecha_vencimiento_venta1" />
                    </div>
                </div>
                <div id="nota_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito1" id="valor_entregado_nota_credito1"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div id="contenido_a_mostrar2" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago2" id="forma_pago2" class="form-control forma_pago" data-id="2">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer">
                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="2" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado2" name="valor_entregado2" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono2" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal2" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto2" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono2" id="impuestoDatafono2"  data-id="2" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion2" id="transaccion2" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta2" id="fecha_vencimiento_venta2" />
                    </div>
                </div>
                <div id="nota_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito2" id="valor_entregado_nota_credito2"  index="2" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div id="contenido_a_mostrar3" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago3" id="forma_pago3" class="form-control forma_pago" data-id="3">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="3" style="cursor: pointer" class="eliminar_forma_pago">
                            <i class="glyphicon glyphicon-trash" data-id="3" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado3" name="valor_entregado3" data-id="3" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono3" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal3" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto3" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono3" id="impuestoDatafono3"  data-id="3" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion3" id="transaccion3" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta3" id="fecha_vencimiento_venta3" />
                    </div>
                </div>
                <div id="nota_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito3" id="valor_entregado_nota_credito3"  index="3" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Cambio:</label>
                <div class="col-md-10">
                    <input type="number" disabled class="form-control" id="cambio" name="cambio" placeholder="cambio" value="0">
                    <p style="color: red; display: none;" class="validate_pay">Cuando la forma de pago no es “Efectivo” el "cambio" debería ser cero (0).</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="button" class="btn btn-default close-modal-forma-pago">Cancelar</button>
                    <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                        <button type="button" class="btn btn-success" onClick="mostrar();" >Agregar Forma de Pago</button>
                    <?php } ?>
                    <button type="button" id="pagar_pendiente" class="btn btn-success">Pagar</button>
                </div>
            </div>
        </form>
    </div>
</div>
     </div>
     <div class="modal-footer">
      <!-- <button type="button" id="save-changues" class="btn btn-primary">Save changes</button>-->
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .apexcharts-canvas svg {
        max-height: 340px !important;
    }
</style>

<?php if($this->session->userdata("soy_nuevo")==1) { ?>
    <iframe src="https://vendty.com/gracias.html" width="800" height="1000" scrolling="no" class="hidden"></iframe>
<?php
    $this->session->set_userdata('soy_nuevo', 0);
    } ?>

<script>

    function cargar_modal_pagos(id){
        //busco los datos de la factura
       //$("#modal-forma-pago").modal('show');
        $.ajax({
            url: "<?php echo site_url("ventas/formaspago/"); ?>",
            dataType: 'html',
            type: 'POST',
            data: {"id":id},
            success: function (data) {
                data=JSON.parse(data);
                console.log(data);
                console.log(data[0]);
                console.log(data[0].id);
                if(data[0].id!=0){
                    idfactura=data[0].id;
                    factura=data[0].factura;
                    valor_entregado=parseFloat(data[0].valor_entregado);
                    $("#factura").val(idfactura);
                    $("#factura_forma").html("");
                    $("#factura_forma").html(factura);
                    $("#valor_a_pagar").val(valor_entregado);
                    $("#valor_entregado").val(valor_entregado);
                    $("#modal-forma-pago").modal('show');

                }else{
                    alert("error");
                }
               /* $("#modal-forma-pago .modal-body").html("");
                $("#modal-forma-pago .modal-body").append(data);
                */
            }
        });
   }

   $(".close-modal-forma-pago").click(function(){
       $("#modal-forma-pago").modal('hide');
   })
    $(document).ready(function() {

        //$(".page").css("background-color", "#f1f3f6");
        activo='<?php echo $active; ?>';
        if(activo=='inicio'){
            $("#graficos_home").removeClass("active");
            $(".page").css("background-color", "White");

        }
        /* Cargamos ventas pendientes por zona por defecto*/
        //cargar_ventas_pendientes_por_zona(<?php //echo $zona_defecto;?>);
        cargar_ventas_pendientes();

        $("#fecha_vencimiento_venta").datepicker({
            dateFormat: 'yy/mm/dd'
        });

       // $('#fecha_vencimiento_venta').datetimepicker();


    /*formas de pagos*/
        $('.forma_pago').on('change', function() {
            forma=$(this).val();
            id=$(this).attr('data-id');
            //bloquear_opciones_forma(id);
            $( "#forma_pago"+id+" option:selected" ).each(function() {
                tipo=$(this).data('tipo');
            });

            $("#valor_entregado").prop("disabled", false);

            if(tipo=='Datafono'){
                $("#pago_datafono"+id).css('display','block');
                discriminado(id);
            }else{
                $("#pago_datafono"+id).css('display','none');
            }

            if(forma=='Credito'){
                $("#fecha_vencimiento_credito"+id).css('display','block');
            }else{
                $("#fecha_vencimiento_credito"+id).css('display','none');
            }

            if(forma=='nota_credito'){
                $("#nota_credito"+id).css('display','block');
            }else{
                $("#nota_credito"+id).css('display','none');
            }

        });

        $(".btnBuscarNotaCredito2").click(function(  ) {
            var index = "" ;
            var codigo = $("#valor_entregado_nota_credito").val() ;

            $.ajax({
                url: "<?php echo site_url("notacredito/estadoNotaCredito"); ?>",
                dataType: 'json',
                type: 'POST',
                data: {"codigo":codigo},
                error: function(jqXHR, textStatus, errorThrown ){
                    //alert(errorThrown);
                    swal({
                        position: 'center',
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                success: function(data){

                    setEstadoNotaCredito( data, index );
                }
            });
        });

        $("#pagar_pendiente").click(function(e){
            $("#pagar_pendiente").prop("disabled", true);
            valor_entregado1=0;
            valor_entregado2=0;
            valor_entregado3=0;
            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            cambio=parseFloat($("#cambio").val());
            url='<?php echo site_url("frontend/dash") ?>';
            if(isNaN(valor_entregado)){
                $("#valor_entregado").val(0);
                $("#cambio").val(0);
            }

            //me paseo por todos los pagos
            if (document.getElementById('contenido_a_mostrar1').style.display == 'block') {
                valor_entregado1=parseFloat($("#valor_entregado1").val());
            }
            if (document.getElementById('contenido_a_mostrar2').style.display == 'block') {
                valor_entregado2=parseFloat($("#valor_entregado2").val());
            }
            if (document.getElementById('contenido_a_mostrar3').style.display == 'block') {
                valor_entregado3=parseFloat($("#valor_entregado3").val());
            }

            valor_entregadototal=valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3;


            if(!isNaN(valor_entregadototal)){
                if((((valor_a_pagar!="") &&(valor_entregadototal!="")) && (valor_a_pagar<=valor_entregadototal))){

                        $("#cambio").prop("disabled", false);
                        $("#valor_entregado_nota_credito").prop("disabled", false);
                        $("#valor_a_pagar").prop("disabled", false);
                        $("#valor_entregado").prop("disabled", false);
                        //ajax
                        $.ajax({
                            url: "<?php echo site_url("ventas/registrarformaspago"); ?>",
                            type: "POST",
                            dataType: "json",
                            data: $("#form_pago").serialize(),
                            success: function (data) {
                                console.log(data);
                                if(data.success==1){
                                    swal({
                                        position: 'center',
                                        type: 'success',
                                        title: 'La Forma de pago se registro Exitosamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    setTimeout(function(){

                                        cargar_ventas_pendientes();
                                        $("#modal-forma-pago").modal('hide');
                                        $("#pagar_pendiente").prop("disabled", false);
                                    }, 1600);
                                }else{
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: data.mgs,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    $("#pagar_pendiente").prop("disabled", false);
                                }
                            }
                        });
                    }
                    else{
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'El valor entregado debe ser igual al valor a pagar',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $("#pagar_pendiente").prop("disabled", false);
                    }

            }else{
                swal({
                    position: 'center',
                    type: 'error',
                    title: 'El valor entregado debe ser mayor o igual al valor de la factura',
                    showConfirmButton: false,
                    timer: 1500
                })
                $("#pagar_pendiente").prop("disabled", false);
            }
        });

        $(".valor_entregado").keyup(function(e){

            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            valor_entregado1=parseFloat($("#valor_entregado1").val());
            valor_entregado2=parseFloat($("#valor_entregado2").val());
            valor_entregado3=parseFloat($("#valor_entregado3").val());

            if(isNaN(valor_entregado)){
                valor_entregado=0;
            }
            if(isNaN(valor_entregado1)){
                valor_entregado1=0;
            }
            if(isNaN(valor_entregado2)){
                valor_entregado2=0;
            }
            if(isNaN(valor_entregado3)){
                valor_entregado3=0;
            }

            cambio=parseFloat(valor_a_pagar-(valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3));
            cambio=cambio*(-1);
            $("#cambio").val(cambio);

            if($('#forma_pago').val() != '0' && $('#forma_pago').val() != 'efectivo'){
                console.log("cambio2", cambio);
                if(cambio >= 1){
                    console.log("no puede ser mayor valor_entregado:" + valor_entregado + ", cambio:" +cambio);
                    $('#pagar_pendiente').prop('disabled', true);
                    $('.validate_pay').show();
                }else{
                    $('#pagar_pendiente').prop('disabled', false);
                    $('.validate_pay').hide();
                }
            }
            if($('#forma_pago').val() == 'efectivo'){
                $('#pagar_pendiente').prop('disabled', false);
                $('.validate_pay').hide();
            }
            $('.forma_pago').each(function (val){
                    if($(this).val() != 'efectivo' && $(this).val() != '0'){
                        console.log($(this).val());                        
                    }
                });
        });

        $('#forma_pago').change(function (val){
            if($(this).val() == 'efectivo'){
                $('#pagar_pendiente').prop('disabled', false);
                $('.validate_pay').hide();
            }else{
                valor_a_pagar=parseFloat($("#valor_a_pagar").val());
                valor_entregado=parseFloat($("#valor_entregado").val());
                valor_entregado1=parseFloat($("#valor_entregado1").val());
                valor_entregado2=parseFloat($("#valor_entregado2").val());
                valor_entregado3=parseFloat($("#valor_entregado3").val());

                if(isNaN(valor_entregado)){
                    valor_entregado=0;
                }
                if(isNaN(valor_entregado1)){
                    valor_entregado1=0;
                }
                if(isNaN(valor_entregado2)){
                    valor_entregado2=0;
                }
                if(isNaN(valor_entregado3)){
                    valor_entregado3=0;
                }

                cambio=parseFloat(valor_a_pagar-(valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3));
                cambio=cambio*(-1);
                $("#cambio").val(cambio);

                if($('#forma_pago').val() != '0' && $('#forma_pago').val() != 'efectivo'){
                    console.log("cambio2", cambio);
                    if(cambio >= 1){
                        console.log("no puede ser mayor valor_entregado:" + valor_entregado + ", cambio:" +cambio);
                        $('#pagar_pendiente').prop('disabled', true);
                        $('.validate_pay').show();
                    }else{
                        $('#pagar_pendiente').prop('disabled', false);
                        $('.validate_pay').hide();
                    }
                }
                if($('#forma_pago').val() == 'efectivo'){
                    $('#pagar_pendiente').prop('disabled', false);
                    $('.validate_pay').hide();
                }
            }
        });

        $(".impuestoDatafono").keyup(function(){
            id=$(this).attr('data-id');
            //alert(id);
            discriminado(id);
        });

        $(".eliminar_forma_pago").click(function(e){

            id=$(this).attr('data-id');
            $("#contenido_a_mostrar"+id).css('display','none');
            //eliminar datos del eliminado
            $("#valor_entregado"+id).val(0);
            $( "#forma_pago"+id+" option:selected" ).each(function() {
                tipo=$(this).data('tipo');
            });
            if(tipo=='Datafono'){
                    $("#impuestoDatafono"+id).val(0);
                    $("#transaccion"+id).val("");
                    $("#impuesto"+id).val(0);
                    $("#subtotal"+id).val(0);
                $("#pago_datafono"+id).css('display','none');
            }

            if(forma=='Credito'){
                $("#fecha_vencimiento_venta"+id).val("");
                $("#fecha_vencimiento_credito"+id).css('display','none');
            }
            $("#forma_pago"+id).val(0);
        });

    });

    function setEstadoNotaCredito( datos, index ){

        var estado = datos.estado;

        var nombre = datos.nombre;

        var valor = datos.valor;

        $("#valor_entregado").val( 0 );

        $("#valor_entregado").prop('disabled', false);

        $("#valor_entregado").css("cursor", "default");

        $("#valor_entregado").hide();

        $("#valor_entregado_gift").attr('disabled');

        $("#valor_entregado_nota_credito").css("cursor", "default");

        $("#valor_entregado_nota_credito").attr('style','display: block !important');

       // setNotaCreditoObj( index, null );
        if( estado == "empty" ){
            //alert("La nota credito no existe");
            $("#valor_entregado_nota_credito").val('');
            swal({
                position: 'center',
                type: 'error',
                title: 'La nota credito no existe',
                showConfirmButton: false,
                timer: 1500
            })
        }

        if( estado == "cancelado" ){
            //alert("La "+nombre+" ya ha sido canjeada");
            $("#valor_entregado_nota_credito").val('');
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" ya ha sido canjeada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "activo" ){
            //alert("La "+nombre+" no ha sido pagada");
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" no ha sido pagada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "pagado" ){

            $("#valor_entregado").val( valor );

            $("#valor_entregado").prop('disabled', true);

            $("#valor_entregado").css("cursor", "not-allowed");

            $("#valor_entregado").show();

            $("#valor_entregado_nota_credito").prop('disabled', true);

            $("#valor_entregado_nota_credito").css("cursor", "not-allowed");

            $("#valor_entregado_nota_creditob").attr('style','display: none !important');
           // setNotaCreditoObj(index,"pagada");
            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            cambio=parseFloat(valor_a_pagar-(valor_entregado));
            cambio=cambio*(-1);
            $("#cambio").val(cambio);

        }
        //validarMediosDePago(0);
    }

    function bloquear_opciones_forma(id){
        forma0=$("#forma_pago").val();

        for(i=id;i<=id;i++){

            $( "#forma_pago"+i+" option" ).each(function() {
                nombre=$(this).val();
                if(nombre==forma0){
                    $(this).attr('disabled', true);

                }else{
                    $(this).attr('disabled', false);
                }
            });
        }
    }

    function discriminado(id){
            //alert(id);
        valorEntregado=parseFloat($("#valor_entregado"+id).val());
        impuesto=parseFloat($("#impuestoDatafono"+id).val());
        subtotal=valorEntregado;
        x=String(impuesto).length;

        if(x == 2)
        {
            subtotal = valorEntregado / parseFloat("1."+impuesto);
        }else if(x == 1)
        {
            subtotal = valorEntregado / parseFloat("1.0"+impuesto);
        }

        iva = valorEntregado - subtotal;
        $("#subtotal"+id).val(parseInt(subtotal));
        $("#impuesto"+id).val(parseInt(iva));
    }

    function mostrar() {

        if (document.getElementById('contenido_a_mostrar1').style.display == 'none') {
            document.getElementById('contenido_a_mostrar1').style.display = 'block';
            //bloquear las opciones anteriores
           // bloquear_opciones_forma(1);
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

        function cargar_ventas_pendientes(){
        $("#ordenes_pendientes").html("");
        $("#facturas_pendientes_pago").html("");
        //facturas pendientes por pagos
        $.get("<?php echo site_url().'/ventas/getAllFacturasPendientesxPago';?>",function(data){
          //  console.log(data);
            if(data.length > 0){
                $.each(data,function(index,el){
                //var href_pagar = "<?php echo site_url('ventas/formaspago/')?>/"+el.id_venta;
                var href_pagar = el.id_venta;
                var row = "<tr>";
                    row += "<td>"+el.factura+"</td>";
                    row += "<td>"+el.fecha+"</td>";
                    row += "<td><div class='centrando'><a onclick='cargar_modal_pagos("+href_pagar+")' id='modal-pago' class='btn btn-success'>Asignar Forma de Pago</a></div></td>";
                    //row += "<td><div class='centrando'><a href='"+href_pagar+"' class='btn btn-success'>Asignar Forma de Pago</a></div>";
                    row += "</tr>";
                $("#facturas_pendientes_pago").append(row);
                })
            }else{
                $("#facturas_pendientes_pago").append('No se encontro ninguna venta pendiente Por forma de pago');
            }
        })
        //ordenes pendientes
        $.get("<?php echo site_url().'/ventas/getAllOrdenes';?>",function(data){
            //var ordenes = JSON.stringify(data);
            if(data.length > 0){
                $.each(data,function(index,el){
                //var href_pagar = "<?php echo site_url('ventas/nuevo/')?>/"+el.zona+'/'+el.mesa_id;
                var href_pagar = "<?php echo site_url('ventas/nuevo/')?>/"+el.zona+'/'+el.mesa_id;
                var href_editar = "<?php echo site_url('orden_compra/mi_orden/')?>/"+el.zona+'/'+el.mesa_id;
                var row = "<tr>";
                    row += "<td>"+el.created_at+"</td>";
                    row += "<td>"+el.zona_mesa+"</td>";
                    row += "<td>"+el.nombre_mesa+"</td>";
                   // row += "<td>"+el.cantidad+"</td>";
                    row += "<td>"+el.monto+"</td>";
                    //row += "<td><a href='"+href_pagar+"'><img src='<?php echo $this->session->userdata("new_imagenes")["btn_pagar_mesa"]["original"] ?>' alt='logo'></a>";
                    row += "<td><div class='centrando'><a href='"+href_pagar+"' class='btn btn-success'>Pagar</a>";
                    //row += "<a href='"+href_editar+"'><img src='<?php echo $this->session->userdata("new_imagenes")["btn_editar_mesa"]["original"] ?>' alt='logo'></a></td>";
                    row += "<a href='"+href_editar+"' class='btn btn-success'>Editar</a></div></td>";
                    row += "</tr>";
                $("#ordenes_pendientes").append(row);
                })
            }else{
                $("#ordenes_pendientes").append('No se encontro ninguna venta pendiente');
            }

        })
    }

</script>
<!--mixpanel-->
<script type="text/javascript">
    var id='<?php echo $this->session->userdata('user_id') ?>';
    var email='<?php echo $this->session->userdata('email') ?>';
    var nombre_empresa='<?php echo $nombre_empresa ?>';

    mixpanel.identify(id);

    mixpanel.track_links('#configura_tu_tirilla', 'Configura Tirilla',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#crea_tus_productos', 'Crear Productos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#abre_tu_caja', 'Abrir Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#realiza_tu_primera_venta', 'Realizar Primera Venta',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#registra_tus_gastos', 'Registrar Gastos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#revisa_tu_cierre_de_caja', 'Cierre de Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_carga_tu_logo', 'Link Cargar Logo',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_aperturar_una_caja', 'Link Abrir Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_producto', 'Link Crear Productos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_vendedor', 'Link Crear Vendedor',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_mi_primera_factura', 'Link Realizar Primera Venta',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_gasto', 'Link Registrar Gastos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_una_orden_de_compra', 'Link Registrar Orden de Compra',{
        "$email": email,
        "$empresa": nombre_empresa,
    });

    function selectTab(id) {
        $('[role="zonas"]').removeClass().addClass( "btnmesas col-xs-12 col-sm-12 col-md-2 col-lg-2 col-2" );
        $("#" + 'tabMesero' + id).addClass( "activeTabMesero" );
    }

    $(".tabs-restaurante>li").click(function(e){
        data=$(this).attr("data-id");
        //console.log($(this));
        //esconderpasos
       // alert(data);
        if(data=="restaurante_home"){
            $(".esconderpasos").css('display','none');
        }else{
            $(".esconderpasos").css('display','block');
        }
    })
    $("#admin_shop").click(function(e){
        e.preventDefault();
        var url_shop = "http://admintienda.vendty.com/admin/crosslogin";
        //var url_shop = "http://192.168.0.15:4200/";

        var url_cross = "<?php echo site_url('tienda/crossDomain');?>";
        var a = document.createElement('a');

        $.get(url_cross,function(data){
            localStorage.setItem("data",data);
            a.target="_blank";
            a.href=url_shop;
            a.click();
        })
    })


    $("#accept_terms_conditions").click(function(){
        let url_terms_conditions = "<?= site_url('frontend/accept_terms_conditions');?>";
        $.get(url_terms_conditions,function(data){
            location.reload();
        });
    })



    var type_business = '';
    var subcategory_business = '';
    var step = 1;

    /* Steps -  type businnes sleceted*/
    $(".content-type-business .content-type").each(function(index,element){
        $(this).click(function(){
            clear_business();
            $(".content-type-business .content-type").each(function(index,element){
                $(this).removeClass("type-business-active");
            });
            $(this).addClass("type-business-active");
            type_business = $(this).data('type');
            switch(type_business){
                case 'restaurant':
                    $(".restaurant-buttons").addClass("active-subcategorie");
                break;

                case 'retail':
                    $(".retail-buttons").addClass("active-subcategorie");
                break;

                case 'fashion':
                    $(".fashion-buttons").addClass("active-subcategorie");
                break;
            }

        })
    })




    $(".subcategory").each(function(index,element){
        $(this).click(function(){
            clear_subcategory();
            subcategory_business = $(this).data('name');
            $(this).addClass('selected-subcategorie');
        })
    })

    function clear_subcategory(){
        $(".subcategory").each(function(index,element){
            $(this).removeClass('selected-subcategorie');
        })
    }
    function clear_business(){
        type_business = '';
        subcategory_business = '';

        $('.button-subcategorie').each(function(){
            $(this).removeClass('active-subcategorie');
        })
    }



    /* End step invoice */

    $("#next-step").click(function(){

        $("#prev-step").css('visibility','visible');
        step++;
        axios.post('<?= site_url("frontend/save_step");?>', {
            step:step,
            type_business:type_business
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
        switch(step){
            case 2:
                mixpanel.track("Paso 1 - Tipo de negocio", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                if(type_business == 'restaurant'){
                    $(".content-propine").removeClass('hidden');
                }else{
                    $(".content-propine").addClass('hidden');
                }
                load_step();
            break;

            case 3:
                mixpanel.track("Paso 2 - Configuración  de factura", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                load_step();
                $('.multiple-templates').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    vertical: false,
                    prevArrow: '<div class="slick-prev"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                    nextArrow: '<div class="slick-next"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
                });
            break;

            case 4:
                mixpanel.track("Paso 3 - Configuración  de tienda", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                load_step();
                $("#next-step").css('visibility','hidden');
            break;
        }
    })

    $("#prev-step").click(function(){
        $("#next-step").css('visibility','visible');
        if(step > 1){step--;}

        switch(step){
            case 1:
                $("#prev-step").css('visibility','hidden');
                load_step();
            break;

            case 2:
                if(type_business == 'restaurant'){
                    $(".content-propine").removeAttr('disabled');
                }
                load_step();
            break;

            case 3:
                load_step();
                $('.multiple-templates').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    vertical: false,
                    prevArrow: '<div class="slick-prev"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                    nextArrow: '<div class="slick-next"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
                });
            break;
        }
    })

    function load_step(){
        $("#next-step").attr('step',step);
        $(".steps .step").each(function(index,element){
            if(index != 0 && step > index){
                $(this).addClass('active');
                $(this).find('.line-status').addClass('active');
            }
        })

        $(".step-content").each(function(index,element){
            $(this).removeClass('active-step');
            let step_id = $(this).data('id');
            if(step_id == step){
                $(this).addClass('active-step');
            }
        })
    }

    $(".close-wizard").click(function(){
        $(".warning-wizard").hide("slow");
    })

    $(document).ready(function(){
        var step2 = new Vue({
            el: '#invoice',
            data: {
                    title: 'Boutique Fashion',
                    footer: 'Software POS Cloud: Vendty.com',
                    image: '<?= base_url("uploads/default.png")?>',
                    address: 'Calle 127 #33-22',
                    phone: '(1) 235-6666',
                    propine: false,
                },
            methods: {
                previewFiles(e) {
                    const file = e.target.files[0];
                    this.image = URL.createObjectURL(file);
                }
            }
        })

        var step3 = new Vue({
            el: '#shop',
            data: {
                    shop_name: '',
                    local_domain: 'http://tienda.vendty.com/',
                    domain: '',
                    country: 'Colombia',
                    currency: 'COP',
                    stores_avaible: <?= $data["stores_avaibles"]; ?>,
                    template: '',
                    error: '',
                },
            methods: {
                localDomain() {
                    let avaible = true;
                    let store = this.shop_name;
                    $.each(this.stores_avaible,function(index,element){
                        let str = ""+$(this)[0].shopname;
                        let str2 = ""+store;
                        if(str == str2){
                            avaible = false;
                        }
                    })

                    if(avaible){
                        this.error = '';
                        this.local_domain = 'http://tienda.vendty.com/'+this.shop_name;
                    }else{
                        this.error = '(*) Nombre no disponible';
                    }
                },
                activeTemplate(template){
                    this.template = template;
                    $(".template").each(function(index,element){
                        $(this).click(function(){
                            $('.template').each(function(){
                                $(this).removeClass('active-template');
                            })
                            $(this).addClass("active-template");
                        })
                    })
                }
            }
        })

        var step4 = new Vue({
            el: '#finalize',
            data: {
                    url: "<?= site_url().'/frontend/load_settings'?>",
                    urlEmailSend: "<?= site_url().'/frontend/load_settings'?>",
                    user: "<?= $this->session->userdata('email') ?>"
                },
            methods: {
                sale_now: function () {
                    mixpanel.track("Paso 4 - Vender", {
                        "$email": email,
                        "$empresa": nombre_empresa,
                        "$tipo_negocio": type_business,
                    });
                    let store = '';
                    swal({
                        title: 'Estamos configurando tu negocio!',
                        text: 'No cierres la ventana, Esto puede tardar un momento.',
                        imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                        imageWidth: 200,
                        imageHeight: 200,
                        imageAlt: 'Cargando',
                        animation: false,
                        showConfirmButton: false
                    })

                    if(step3.error == ''){
                        store = step3.shop_name;
                    }
                    axios.post(this.url, {
                        type_business: type_business,
                        subcategory_business: subcategory_business,
                        title:step2.title,
                        footer:step2.footer,
                        image:step2.image,
                        address:step2.address,
                        phone:step2.phone,
                        shop_name:store,
                        local_domain:step3.local_domain,
                        domain:step3.domain,
                        country:step3.country,
                        currency:step3.currency,
                        template:step3.template,
                        propine:step2.propine,
                        email:this.user
                    })
                    .then(function (response) {
                        swal.close();
                        console.log(response);
                        if(type_business == "restaurant"){
                            location.href="<?= site_url("orden_compra/mi_orden/-1/".strtotime("now")); ?>";
                        }else{
                            location.href="<?= site_url('ventas/nuevo'); ?>";
                        }
                    })
                    .catch(function (error) {
                        swal.close();
                        console.log(error);
                        swal(
                            'Error inesperado!',
                            'Ocurrio un error al intentar cargar la información.',
                            'error'
                        )
                    });
                }
            }
        })
    })
</script>

<div class="page-header">
    <div class="icon">
        <img alt="ventas_creditos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_credito']['original'] ?>">
    </div>
    <h1 class="sub-title"><?php echo custom_lang("Ventas a Credito", "Ventas a Crédito"); ?></h1>
</div>

<style>
    .to-right{
        text-align: right;
        margin: 0px -15px 0px 0px;
    }
    .input-date {
        width: 100px !important;
        margin-left: auto !important;
    }

    #facturasTable_filter,
    #facturasTable_2_filter {
        float: left !important;
    }

    #last_date_div_2,
    #second_date_div_2,
    #first_date_div_2,
    #last_date_div,
    #second_date_div,
    #first_date_div {
        //float: right !important;
    }
</style>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <!-- <?php
                    $message = $this->session->flashdata('message');
                    if (!empty($message)) : ?>
                                            <div class="alert alert-success">
                                                <?php echo $message; ?>
                                            </div>
            <?php endif; ?> -->
            <?php
            $message1 = $this->session->flashdata('message1');
            if (!empty($message1)) : ?>
                <div class="alert alert-error">
                    <?php echo $message1; ?>
                </div>
            <?php endif; ?>
            <?php
            $is_admin = $this->session->userdata('is_admin');
            $permisos = $this->session->userdata('permisos');
            if (in_array("21", $permisos) || $is_admin == 't') : ?>
                <!--<a href="<?php echo site_url("credito/index_pagadas") ?>" class="btn btn-success"></small>Facturas Pagadas</a>-->
                <div class="col-md-6">
                </div>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a href="<?php echo site_url("credito/index_pagadas") ?>" data-tooltip="Listado de Ventas a Crédito Pagadas">
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['credito_pagado_verde']['original'] ?>">
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row-fluid" id="content_credits">
    <div class="span12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item nav-item active">
                <a class="nav-link active" id="bill-tab" data-toggle="tab" href="#bill" role="tab" aria-controls="bill" aria-selected="true">Ventas a Crédito por Facturas </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Ventas a Crédito por Cliente</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                <div class="block container">
                    <!--<div class="head blue">
                        <h2><?php echo custom_lang('sima_outstanding_all', "Ventas a Crédito Pendientes por Cliente"); ?></h2>
                    </div>-->

                    <div class="data-fluid">

                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">
                            <thead>
                                <tr>
                                    <th width="15%"><?php echo custom_lang('sima_number', "Cliente"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Correo electrónico"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Identificación"); ?></th>
                                    <th width="7%"><?php echo custom_lang('sima_number', "N. Facturas"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_customer', "Total ventas"); ?></th>
                                    <th width="5%"><?php echo custom_lang('sima_total_price', "Retenciones"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total abonos'); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total pendiente'); ?></th>
                                    <th width="13%"><?php echo custom_lang('sima_number', "Fecha ultima factura"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th width="15%"><?php echo custom_lang('sima_number', "Cliente"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Correo electrónico"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Identificación"); ?></th>
                                    <th width="7%"><?php echo custom_lang('sima_number', "N. Facturas"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_customer', "Total ventas"); ?></th>
                                    <th width="5%"><?php echo custom_lang('sima_total_price', "Retenciones"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total abonos'); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total pendiente'); ?></th>
                                    <th width="13%"><?php echo custom_lang('sima_number', "Fecha ultima factura"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade active in" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                <div class="block container">
                    <div class="data-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable_2">

                            <thead>
                                <tr>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Factura"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_customer', "Cliente"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_total_price', "Total venta"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_total_price', "Retenciones"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total pendiente'); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_date', "Fecha factura"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_date', "Fecha vencimiento"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th width="10%"><?php echo custom_lang('sima_number', "Factura"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_customer', "Cliente"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_total_price', "Total venta"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_total_price', "Retenciones"); ?></th>
                                    <th width="10%"><?php echo custom_lang('sima_saldo', 'Total pendiente'); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_date', "Fecha vencimiento"); ?></th>
                                    <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<div class="social">
    <ul>
        <li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>
    </ul>
</div>
<!-- vimeo-->
<div id="myModalvideovimeo" class="modal fade">
    <div style="padding:56.25% 0 0 0;position:relative;">
        <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266925040?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
</div>


<!-- Modal Abonar factura -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="modalInternet" class="modal-dialog modal-center">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7);padding: 5px;">Abonar factura</h4>
            </div>

            <div class="modal-body">
                <div class="container">

                    <div class="">
                        <p class="negrilla">Informaci&oacute;n general de Factura</p>
                        <table class='table'>
                            <tr>
                                <td style='width: 15%;'><strong>Pagos:</strong></td>
                                <td style='width: 18%;'><span id="response_pagos"></span></td>
                                <td style='width: 15%;'><strong>Retenciones:</strong></td>
                                <td style='width: 20%;'><span id="response_retenciones"></span></td>
                                <td style='width: 12%;'><strong>Total:</strong></td>
                                <td style='width: 18%;'><span id="response_total"></span><br /></td>
                            </tr>
                            <tr>
                                <td style='width: 15%;'></td>
                                <td style='width: 18%;'></td>
                                <td style='width: 15%;'></td>
                                <td style='width: 20%;'></td>
                                <td style='width: 12%;'><strong>Saldo:</strong></td>
                                <td style='width: 18%;'><span id="response_saldo"></span><br />
                                </td>
                            </tr>

                        </table>

                        <table class='table'>
                            <tr>
                                <td style="width: 30%;"><span id="response_t_fecha"></span></td>
                                <td style="width: 40%;"><strong>Factura No: </strong> <span id="response_t_factura"></span></td>
                                <td style="width: 30%;"><strong>Almacen: </strong> <span id="response_t_nombre"></span>
                                <td>
                            </tr>
                        </table>

                        <table class='table'>
                            <tr>
                                <td style="width: 30%;">
                                    <strong>Cliente:</strong>
                                    <span id="response_nombre_cliente"></span>
                                <td style="width: 40%;"><strong>CC: </strong> <span id="response_cc"></span> </td>
                                <td style="width: 30%;"><strong>Tel&eacute;fono: </strong> <span id="response_cellphone"></span></td>
                                </td>

                            </tr>
                        </table>



                        <div class="pt-1">
                            <div class="">
                                <a class="" style="text-decoration: underline;" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                                    Detalle factura <span id="response_numero"></span>
                                </a>
                            </div>
                            <br>

                            <!-- Items -->
                            <div class="row">
                                <div class="col">
                                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                                        <div class="card card-body">
                                            <div class="container">

                                                <div class="data-fluid">
                                                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="10%">Ref</th>
                                                                <th width="20%">Producto</th>
                                                                <th width="15%">Cant</th>
                                                                <th width="15%">Precio</th>
                                                                <th width="10%">Desc</th>
                                                                <th width="30%">Total</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="items_total_venta">

                                                        </tbody>
                                                    </table>

                                                    <div class="pagination pagination-centered">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Abonar factura -->

                    <div class="">
                        <p class="negrilla">Abonar factura</p>

                        <form id="form_add_abono">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payDate" id="label_payDate">Fecha de pago <span class="color-red">*</span>
                                            <input type="text" class="form-control input-date" id="payDate" date-format="yy-mm-dd" required disabled>
                                        </label>
                                        <span class="text-muted color-red font-size-9 valid_payDate">Fecha de pago requerida</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="payDate">Forma de pago <span class="color-red">*</span></label>
                                    <select class="form-control" id="payMethod" required value=""></select>
                                    <span class="text-muted color-red font-size-9 valid_payMethod">Forma de pago requerida</span>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payValue">Valor a pagar <span class="color-red">*</span></label>
                                        <input type="text" class="form-control" id="payValue" placeholder="Valor a pagar" required maxlength="10">
                                        <span class="text-muted color-red font-size-9 valid_payValue">Valor a pagar requerido</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payRetention">Retención</label>
                                        <input type="text" class="form-control" id="payRetention" v-model="payRetention" required maxlength="10">
                                    </div>
                                </div>

                                <div class="col-md-12" class="to-right" style="text-align: right; margin: 0px -15px 0px 0px;">
                                    <button data-tooltip="Agregar" id="btnGuardarAbono" type="button" class="btn btn-success for-show"><i class="fa fa-plus" aria-hidden="true"></i> Abonar</button>
                                    <button id="btnGuardarAbono" type="button" class="btn btn-success for-hide" disabled>...</button>
                                </div>
                            </div>
                        </form>

                        <p class="color-red" id="text-error-suma"></p>
                    </div>


                    <div class="">
                        <p class="negrilla">Lista de Abonos</p>

                        <table class='table'>
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Retención</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_listado_abonos">

                            </tbody>

                        </table>
                    </div>


                </div>
            </div>

            <div class="modal-footer" style="">
                <button id="btnNoOffline" type="button" class="btn btn-default" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Cerrar</button>
                <!--<button id="btnGuardarAbono" type="button" class="btn btn-success" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Aceptar </button>-->
            </div>

        </div>
    </div>
</div>
<!-- END MODAL -->


<!-- Modal -->
<div class="modal fade" id="validateBox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="modalInternet" class="modal-dialog modal-center">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7);padding: 5px;">Apertura de caja</h4>
            </div>

            <div class="modal-body">
                <div class="container">
                    <!-- Abonar factura -->
                    <form id="form_add_abono">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="payDate">Valor de la caja<span class="color-red">*</span></label>
                                <input type="text" class="form-control" id="valueBoxOpen" placeholder="Valor de la caja" required maxlength="10">
                                <span class="text-muted color-red font-size-9  value_box_require">Valor de la caja requerido</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer" style="">
                <button id="btn-close-box" type="button" class="btn btn-default" data-dismiss="modal" style="padding: 5px 20px 5px 20px;"> Cancelar </button>
                <button id="btn-open-box" type="button" class="btn btn-success" style="padding: 5px 20px 5px 20px;"> Abrir </button>
            </div>
        </div>
    </div>
</div>


<script src="https://use.fontawesome.com/512cd430cc.js"></script>
<script src="<?= base_url('/assets/api_url.js') ?>"></script>
<script src="<?= base_url('/assets/toMoney.js') ?>"></script>

<!-- <?php  $api_auth = (isset(json_decode($_SESSION['api_auth'])->token)) ? json_decode($_SESSION['api_auth'])->token : '';?> -->
<script type="text/javascript">
    $(function() {
        // let token_php = "<?php echo $api_auth; ?>";
        // let data_modal;
        datacurrency = JSON.parse('<?php echo json_encode($datacurrency); ?>');
        var dateObj = new Date("<?php echo date("Y-m-d H:i:s", (strtotime ("-5 Hours"))); ?>");
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        var day = dateObj.getUTCDate();
        var year = dateObj.getUTCFullYear();
        let month_2;
        if (month.toString().length == 1) {
            month_2 = "0" + month.toString();
        } else {
            month_2 = month.toString();
        }
        var yesterday = dateObj.getDate() - 1;
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0,0,0,0);
        //---------------------------------------
        const lastMonth = new Date();
        lastMonth.setMonth((lastMonth.getMonth() - 1));
        var lastMonthDay = lastMonth.getDate();
        var lastMonthMonth = lastMonth.getMonth() + 1;
        var lastMonthYear = lastMonth.getFullYear();
        
        if (lastMonthDay < 10) {
            lastMonthDay = '0' + lastMonthDay;
        } 
        if (lastMonthMonth < 10) {
            lastMonthMonth = '0' + lastMonthMonth;
        } 
        //---------------------------------------
  
        
        /*if (!datacurrency) {
            $.ajax({
                type: "GET",
                url: api_url + '/data-currency',
                headers: { Authorization: `Bearer ${token_php}` },
                success: function(response) {
                    datacurrency = response;
                    if(response.thousands_sep == ' '){
                        if(response.decimals_sep != ' '){
                            if(response.decimals_sep == ',') {
                                datacurrency.thousands_sep = '.';
                            }
                            if(response.decimals_sep == '.') {
                                datacurrency.thousands_sep = ',';
                            }
                        }else{
                            response.thousands_sep = ',';
                        }
                    }
                    if(response.decimals_sep == ' '){
                        if(response.thousands_sep != ' '){
                            if(response.thousands_sep == ',') {
                                datacurrency.decimals_sep = '.';
                            }
                            if(response.thousands_sep == '.') {
                                datacurrency.decimals_sep = ',';
                            }
                        }else{
                            response.decimals_sep = ',';
                        }
                    }
                }
            });
        }*/

                    $('#facturasTable').dataTable({
                        "preDrawCallback": function(settings) {
                            // if (!datacurrency) {
                            //     $.ajax({
                            //         type: "GET",
                            //         url: api_url + '/data-currency',
                            //         headers: {
                            //             Authorization: `Bearer ${token_php}`
                            //         },
                            //         success: function(response) {
                            //             datacurrency = response;
                            //         }
                            //     });
                            // }
                        },
                        "aaSorting": [
                            [5, "desc"]
                        ],
                        "bProcessing": true,
                        "sAjaxSource": "<?php echo site_url("credits/getCredits"); ?>",
                        "fnServerData": function(sSource, aoData, fnCallback) {
                            aoData.push({
                                "name": "fecha_inicial",
                                "value": $('#first_date_filter_2').val()
                            }, {
                                "name": "fecha_final",
                                "value": $('#second_date_filter_2').val()
                            });
                            $.getJSON(sSource, aoData, function(json) {
                                fnCallback(json)
                            });

                        },
                        "sPaginationType": "full_numbers",
                        "iDisplayLength": 5,
                        "aLengthMenu": [5, 10, 25, 50, 100],
                        "aoColumnDefs": [{
                            "bSortable": false,
                            "aTargets": [9],
                            "bSearchable": false,
                            "mRender": function(data, type, row) {
                                var buttons = "<div class='btnacciones' style='text-align:center !important'>";
                                <?php if (in_array('1001', $permisos) || $is_admin == 't') : ?>
                                    buttons += '<a data-tooltip="Ver detalle" href="<?php echo site_url("credits/customer/"); ?>/' + data + '" class="button default acciones"><div class="icon"><img alt="Ver detalle" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';
                                <?php endif; ?>

                                buttons += "</div>";
                                return buttons;

                            }

                        }],
                        "oLanguage": {
                            "sSearch": "Clientes: ",
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sUrl": "",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        "initComplete": function(settings, json) {
                            render_by_customers();
                        }
                    });



                    $('#facturasTable_2').dataTable({
                        //"serverSide": true,
                        "preDrawCallback": function(settings) {
                            // if (!datacurrency) {
                            //     $.ajax({
                            //         type: "GET",
                            //         url: api_url + '/data-currency',
                            //         headers: {
                            //             Authorization: `Bearer ${token_php}`
                            //         },
                            //         success: function(response) {
                            //             datacurrency = response;
                            //         }
                            //     });
                            // }
                        },
                        "firstAjax": false,
                        "deferLoading": 0, // here
                        "aaSorting": [
                            [5, "desc"]
                        ],
                        "bProcessing": true,
                        "sAjaxSource": "<?php echo site_url("credito/get_ajax_data_pendientes"); ?>",
                        "fnServerData": function(sSource, aoData, fnCallback) {
                            let value_inicial = null;
                            let value_final = null;
                            if (typeof $('#first_date_filter').val() === "undefined") {
                                value_inicial = lastMonthYear + '-' + lastMonthMonth + '-' + lastMonthDay;
                            } else {
                                value_inicial = $('#first_date_filter').val()
                            }
                            if (typeof $('#second_date_filter').val() === "undefined") {
                                var tomorrowDay = tomorrow.getDate();
                                var tomorrowMonth = tomorrow.getMonth() + 1;
                                var tomorrowYear = today.getFullYear();
                                if (tomorrowDay < 10) {
                                    tomorrowDay = '0' + tomorrowDay;
                                } 
                                if (tomorrowMonth < 10) {
                                    tomorrowMonth = '0' + tomorrowMonth;
                                } 
                                value_final = tomorrowYear + '-' + tomorrowMonth + '-' + tomorrowDay;
                            } else {
                                value_final = $('#second_date_filter').val()
                            }
                            
                            aoData.push({
                                "name": "fecha_inicial",
                                "value": value_inicial
                            }, {
                                "name": "fecha_final",
                                "value": value_final
                            });
                            $.getJSON(sSource, aoData, function(json) {
                                fnCallback(json)
                            });

                        },
                        "sServerMethod": "POST",
                        "sPaginationType": "full_numbers",

                        "iDisplayLength": 5,
                        "aLengthMenu": [5, 10, 25, 50, 100],

                        "aoColumnDefs": [{
                                "bSortable": false,
                                "aTargets": [7],
                                "bSearchable": false,
                                "mRender": function(data, type, row) {
                                    var buttons = "<div class='btnacciones'>";
                                    <?php if (in_array('1001', $permisos) || $is_admin == 't') : ?>
                                        //buttons += '<a some-data="' + data + '"  data-tooltip="Ver detalle" data-toggle="modal" id="#view_detail" data-target="#exampleModal" class="button default acciones view_detail"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';
                                        buttons += '<a some-data="' + data + '"  id="#view_detail_' + data + '" data-target="#exampleModal" class="button default acciones view_detail" data-tooltip="Abonar factura"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';
                                    <?php endif; ?>
                                    <?php if (in_array('1001', $permisos) || $is_admin == 't') : ?>
                                        buttons += '<a href="<?php echo site_url("credito/imprimir/"); ?>/' + data + '/copia" class="button default btn-print acciones" target="_blank" data-tooltip="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                                    <?php endif; ?>
                                    <?php if (in_array('13', $permisos) || $is_admin == 't') : ?>
                                        buttons += '<a href="#" id="' + data + '" class="button red anular acciones" data-tooltip="Anular"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';
                                    <?php endif; ?>
                                    buttons += "</div>";
                                    return buttons;
                                }
                            }
                        ],
                        "oLanguage": {
                            "sSearch": "Facturas: ",
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sUrl": "",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        "drawCallback": function(oSettings) {
                            renderTable();
                        },
                        "initComplete": function(settings, json) {
                            render_by_invoices();
                        }
                    });


        function renderTable() {
            $('.view_detail').off();
            setTimeout(function() {
                let api_auth = JSON.parse(localStorage.getItem('api_auth'));
                $('.view_detail').click(function() {
                    let me = this;
                    id_modal = $(me).attr('some-data');
                    $.ajax({
                        type: "GET",
                        url: "<?php echo site_url("credito/verify_state_box") ?>",
                        success: function(data) {
                            if (data.estado_caja) {
                                let id = $(me).attr('some-data');
                                $.ajax({
                                    url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id,
                                    type: 'GET',
                                    success: function(data) {
                                        renderModal(data, id)
                                    }

                                });
                            } else {
                                $('#validateBox').modal('show');
                            }
                        }
                    });

                });

            }, 10)
        }
        let id_modal;
        var anularDialog = anularDialog || (function($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
                '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
                '<div class="modal-dialog modal-m">' +
                '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;">' +
                '<h4 class="modal-title"><?php echo custom_lang('sima_motivo_form', "Motivo de la Anulación"); ?></h4>' +
                '</div>' +
                '<div class="modal-body">' +
                '<form id="motivo-form" action="<?php echo site_url('ventas/anular'); ?>" method="POST" >' +
                '<input type="hidden" value="" name="venta_id" id="venta_id"/>' +
                '<div class="row-form">' +
                '<div class="span2"><?php echo custom_lang('sima_motivo', "Motivo"); ?>:</div>' +
                '<div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>' +
                '</div>' +
                '<div align="center"> ' +
                '<input type="button" value="Cancelar" data-dismiss="modal" id="cancelar" class="btn btn-default"/> ' +
                '<input type="submit" value="Continuar"  class="btn btn-success"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ' +
                '</div><br>' +
                '</form>' +
                '</div>' +
                '</div></div></div>');
            return {
                show: function(id) {
                    //$dialog.find("#venta_id_ven").val(id);
                    $dialog.find("#venta_id").val(id);

                    $.ajax({
                        async: false, //mostrar variables fuera de el function 
                        url: "<?php echo site_url("clientes/get_ajax_clientes_correo"); ?>",
                        type: "post",
                        dataType: "json",
                        data: {
                            idventa: id
                        },
                        success: function(data2) {
                            $dialog.find("#correo_cliente").html(data2);
                        }
                    });


                    $dialog.modal();
                },
                hide: function() {
                    $dialog.hide();
                }
            }
        })(jQuery);

        $('body').on('click', '.anular', function(e) {
            e.preventDefault();
            anularDialog.show($(this).attr('id'));

        });


        $("#dialog-motivo-form").dialog({
            autoOpen: false,
            //height: 400,
            width: 620,
            modal: true,
            buttons: {
                "Aceptar": function() {
                    if ($("#motivo-form").length > 0) {
                        $("#motivo-form").validationEngine('attach', {
                            promptPosition: "topLeft"
                        });
                        if ($("#client-form").validationEngine('validate')) {
                            $("#motivo-form").submit();
                        }
                    }
                },
                "Cancelar": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $('#razon_social').val("");
                $('#nif_cif').val("");
                $('#email').val("");
                $('#nombre_comercial').val("");
            }
        });

        function validateNull(str) {
            if (str == "null" || str == null || !str) {
                return '';
            }
            return str;
        }


        $('#exampleModal').on('show.bs.modal', function(e) {
            $('#exampleModal').children().removeClass('modal-center');
        });

        $('#exampleModal').on('hidden.bs.modal', function(e) {
            $('#payDate').val("");
            $('#payMethod').val("");
            $('#payValue').val("");
            $('#payRetention').val("");
        });
        $('.for-hide').hide();

        $('#btnGuardarAbono').click(function() {
            let id_factura = $(this).attr('attr-id-value');
            let payDate = $('#payDate').val();
            let payMethod = $('#payMethod').val();
            let payValue = $('#payValue').val();
            let payRetention = $('#payRetention').val();
            if(valid_range()){
                if (payDate || payMethod || payValue || payRetention) {
                let arrayValid = [];
                if (!payMethod) {
                    $('.valid_payMethod').show();
                } else {
                    $('.valid_payMethod').hide();
                    arrayValid.push(1);
                }
                if (!payValue) {
                    $('.valid_payValue').show();
                } else {
                    $('.valid_payValue').hide();
                    arrayValid.push(1);
                }
                let cantidad = $('#payValue').val();
                let importe_retencion = $('#payRetention').val();
                if(datacurrency.decimals_sep == ',' && datacurrency.decimals != '0'){
                    if(cantidad.includes(","))
                    {
                        if(cantidad.includes("."))
                        {
                            cantidad = cantidad.split('.').join('');
                        }
                        
                        cantidad = cantidad.split(',').join('.');
                    }
                    if(importe_retencion.includes(","))
                    {
                        if(importe_retencion.includes("."))
                        {
                            importe_retencion = importe_retencion.split('.').join('');
                        }
                        importe_retencion = importe_retencion.split(',').join('.');
                    }
                }
                if(datacurrency.decimals_sep == '.' && datacurrency.decimals != '0'){
                    if(cantidad.includes(","))
                    {
                        cantidad = cantidad.split(',').join('');
                    }
                    if(importe_retencion.includes(","))
                    {
                        importe_retencion = importe_retencion.split(',').join('');
                    }
                }
                
                if (arrayValid.length == 2) {
                    if(datacurrency.decimals == '0'){
                        if(cantidad.includes(","))
                        {
                            cantidad = cantidad.split(",").join("");
                        }
                        if(cantidad.includes("."))
                        {
                            cantidad = cantidad.split(".").join("");
                        }

                        if(importe_retencion.includes(","))
                        {
                            importe_retencion = importe_retencion.split(",").join("");
                        }
                        if(importe_retencion.includes("."))
                        {
                            importe_retencion = importe_retencion.split(".").join("");
                        }
                    }
                    let data = {
                        fecha_pago: $('#payDate').val(),
                        tipo: $('#payMethod').val(),
                        cantidad: cantidad,
                        importe_retencion: importe_retencion,
                        id_factura: id_factura
                    };
                    $('.valid_payDate').hide();
                    $('.valid_payMethod').hide();
                    $('.valid_payValue').hide();
                    console.log(data);
                    $('.for-hide').show();
                    $('.for-show').hide();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url("pagos/nuevo_ajax") ?>/" + id_factura,
                        data: data,
                        success: function(response) {
                            //reload DataTable
                            $('#facturasTable_2').dataTable().api().ajax.reload(function() {
                                $('.view_detail').off();
                                $('.view_detail').click(function() {
                                    let id = $(this).attr('some-data');
                                    console.log("some id", id);
                                    $.ajax({
                                        url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id,
                                        type: 'GET',
                                        success: function(data) {
                                            renderModal(modal, id)
                                        }
                                    });
                                });
                            });
                            //hide and disable buttons
                            $('.for-hide').hide();
                            $('.for-show').show();

                            //clear data
                            $('#payMethod').val("Seleccione alguna opción");
                            $('#payValue').val("");
                            $('#payRetention').val("");

                            //parse response to jsson
                            let data = JSON.parse(response)

                            //reload Modal
                            $.ajax({
                                url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id_factura,
                                type: 'GET',
                                success: function(data) {
                                    renderModal(data, id_factura)
                                }
                            });
                        }
                    });
                }
            }
            };
            
        });



        function reloadAfterDeelte(id_factura) {
            $.ajax({
                url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id_factura,
                type: 'GET',
                success: function(data) {
                    renderModal(data, id_factura)
                }
            });
            $('#facturasTable_2').dataTable().api().ajax.reload(function() {
                $('.view_detail').off();
                $('.view_detail').click(function() {
                    let id = $(this).attr('some-data');
                    console.log("some id", id);
                    $.ajax({
                        url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id,
                        type: 'GET',
                        success: function(data) {
                            renderModal(modal, id)
                        }
                    });
                });
            });

        }

        $('#payValue, #payRetention, #valueBoxOpen').keyup(function() {
            let value = $(this).val();
            let value2 = value.replace(/[^0-9]/g, '');
            $(this).val(toMoney(value2));
        });
        $('.value_box_require').hide()

        $('#btn-open-box').click(function() {
            let api_auth = JSON.parse(localStorage.getItem('api_auth'));
            let id = id_modal;
            let value_box_require = $('#valueBoxOpen').val();
            if (value_box_require) {
                $('.value_box_require').hide()
                let value_open_box =  $('#valueBoxOpen').val();
                if(datacurrency.decimals_sep == ',' && datacurrency.decimals != '0'){
                    if(value_open_box.includes(","))
                    {
                        if(value_open_box.includes("."))
                        {
                            value_open_box = value_open_box.split('.').join('');
                        }
                        
                        value_open_box = value_open_box.split(',').join('.');
                    }
                }
                if(datacurrency.decimals_sep == '.' && datacurrency.decimals != '0'){
                    if(value_open_box.includes("."))
                    {
                        if(value_open_box.includes(","))
                        {
                            value_open_box = value_open_box.split(',').join('');
                        }
                    }
                }
                $.ajax({
                    async: false,
                    url: "<?php echo site_url("caja/apertura/credito") ?>",
                    type: "post",
                    dataType: "json",
                    data: {
                        fecha: year.toString() + "-" + month_2 + "-" + day.toString(),
                        almacen: "<?php echo $this->dashboardModel->getAlmacenActual() ?>",
                        foma_pago: ['efectivo'],
                        valor: [
                            value_open_box
                        ],
                        total_formapago: value_open_box,
                        back: '',
                        url: "http://localhost:8080/pos/index.php/frontend/index"
                    },
                    success: function(data) {
                        $('#validateBox').modal('hide');
                        $.ajax({
                            url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id,
                            type: 'GET',
                            success: function(data) {
                                renderModal(modal, id)
                            }

                        });

                    }
                });
            } else {
                $('.value_box_require').show()
            }
        });
        $('.valid_payDate').hide();
        $('.valid_payMethod').hide();
        $('.valid_payValue').hide();

        $('#second_date_filter_2').val(year.toString() + "-" + month_2 + "-" + day.toString());
        //init datapicker
        $('#payDate').datepicker();
        $('#payDate').datepicker( "option", "minDate", new Date() );

        //change format
        $('#payDate').datepicker("option", "dateFormat", "yy-mm-dd");
        $('#payDate').datepicker("setDate", year.toString() + "-" + month_2 + "-" + day.toString());

        //function to show datepicker
        $('#label_payDate').click(function() {
            // $('#payDate').datepicker("show");
            // setTimeout(function() {
            //     $('.ui-datepicker').css('z-index', 9999);
            // }, 100)
        });

        function render_by_customers() {
            $($('#facturasTable_length')).insertBefore('#facturasTable_info');

            $('<div id="first_date_div_2" class="dataTables_length"><label id="label_input_first_date_2">Fecha inicial:<input type="text" disabled class="input-date" name="first_date_filter" id="first_date_filter_2" placeholder="Fecha"></label></div>').insertAfter('#facturasTable_filter');
            $('<div id="second_date_div_2" class="dataTables_length"><label id="label_input_second_date_2">Fecha final:<input type="text"  disabled class="input-date" name="second_date_filter" id="second_date_filter_2" placeholder="Fecha"></label></div>').insertAfter('#first_date_div_2');
            $("<div id='last_date_div_2' class='dataTables_length'><label><button class='btn btn-success' name='filtrar_2' id='filtrar_2' value='Filtrar'><small class='fa fa-search'></small> Buscar</button></label></div>").insertAfter('#second_date_div_2');

            $('#first_date_filter_2').datepicker();
            $('#second_date_filter_2').datepicker();

            $('#first_date_filter_2').datepicker("option", "dateFormat", "yy-mm-dd");
            $('#first_date_filter_2').datepicker("setDate", lastMonthYear.toString() + "-" + lastMonthMonth.toString() + "-" + lastMonthDay.toString());
            $('#second_date_filter_2').datepicker("option", "dateFormat", "yy-mm-dd");
            $('#second_date_filter_2').datepicker("setDate", (tomorrow));

            $('#label_input_first_date_2').click(function() {
                $('#first_date_filter_2').datepicker("show");
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 9999);
                }, 100)
            });

            $('#label_input_second_date_2').click(function() {
                $('#second_date_filter_2').datepicker("show");
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 9999);
                }, 100)
            });

            $('#filtrar_2').click(function() {
                $('#facturasTable').dataTable().api().ajax.reload();
            });
        }

        function render_by_invoices() {
            $($('#facturasTable_2_length')).insertBefore('#facturasTable_2_info');

            $('<div id="first_date_div" class="dataTables_length"><label id="label_input_first_date">Fecha inicial:<input type="text" disabled name="first_date_filter"  class="input-date" id="first_date_filter" placeholder="Fecha"></label></div>').insertAfter('#facturasTable_2_filter');
            $('<div id="second_date_div" class="dataTables_length"><label id="label_input_second_date">Fecha final:<input type="text" disabled name="second_date_filter" class="input-date" id="second_date_filter" placeholder="Fecha"></label></div>').insertAfter('#first_date_div');
            $("<div id='last_date_div' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='fa fa-search'></small> Buscar</button></label></div>").insertAfter('#second_date_div');

            $('#first_date_filter').datepicker();
            $('#second_date_filter').datepicker();


            $('#first_date_filter').datepicker("option", "dateFormat", "yy-mm-dd");
            $('#first_date_filter').datepicker("setDate", lastMonthYear.toString() + "-" + lastMonthMonth.toString() + "-" + lastMonthDay.toString());
            $('#second_date_filter').datepicker("option", "dateFormat", "yy-mm-dd");
            $('#second_date_filter').datepicker("setDate", (tomorrow));

            $('#label_input_first_date').click(function() {
                $('#first_date_filter').datepicker("show");
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 9999);
                }, 100)
            });

            $('#label_input_second_date').click(function() {
                $('#second_date_filter').datepicker("show");
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 9999);
                }, 100)
            });

            $('#filtrar').click(function() {
                let first_date = $('#first_date_filter').val();
                let last_date = $('#second_date_filter').val();

                console.log((first_date) ? first_date : new Date());
                console.log((last_date) ? last_date : new Date());
                let data = {
                    fecha_inicial: $('#first_date_filter').val(),
                    fecha_final: $('#second_date_filter').val()
                }
                $('#facturasTable_2').dataTable().api().ajax.reload(function() {
                    $('.view_detail').off();
                    $('.view_detail').click(function() {
                        let id = $(this).attr('some-data');
                        console.log("some id", id);
                        $.ajax({
                            url: "<?php echo site_url("pagos/ver_pago_ajax") ?>/" + id,
                            type: 'GET',
                            success: function(data) {
                                renderModal(modal, id)
                            }
                        });
                    });
                });
            });
        }

        function renderModal(data, id){

            $('#btnGuardarAbono').attr('attr-id-value', id);
            console.log(JSON.parse(data));
            //cerrar collapse
            $('#multiCollapseExample1').collapse('hide')
            let response = JSON.parse(data);
            let pagos = 0;
            let retenciones = 0;
            let total_venta = response.venta_credito.venta.total_venta;
            let response_total = 0;
            let response_saldo = 0;

            //items
            let total = 0;
            let total_items = 0;
            let timp = 0;
            let html_tbody = "";
            let html_option = "<option selected disabled>Seleccione alguna opción</option>";

            let tbody_listado_abonos = '';

            let response_t_fecha = response.venta_credito.venta.fecha;
            let response_t_factura = response.venta_credito.venta.factura;
            let response_t_nombre = response.venta_credito.venta.nombre;
            let response_nombre_cliente = (response.venta_credito.venta.nombre_comercial) ? response.venta_credito.venta.nombre : "mostrador";
            let response_cc = (response.venta_credito.venta.nif_cif == 0 || response.venta_credito.venta.nif_cif == "") ? "Indefinido" : response.venta_credito.venta.nif_cif;
            let response_cellphone = (response.venta_credito.venta.cliente_telefono) ? response.venta_credito.venta.cliente_telefono : 'Indefinido';

            let propina_find = response.venta_credito.detalle_venta.find( detalle_venta => detalle_venta.nombre_producto === 'PROPINA' );
            let propina_value = 0;
            if(propina_find){
                propina_value = Number(propina_find.precio_venta);
            }
            response.data.forEach((element, index) => {
                pagos = pagos + Number(element.cantidad);
                retenciones = retenciones + Number(element.importe_retencion);
                console.log(response.data.length, index++)
                
                if (response.data.length == index++) {
                    response_saldo = Number(response.venta_credito.venta.total_venta) - (pagos + retenciones) + propina_value;
   
                    $('#response_saldo').text(number_format(response_saldo));
                    response_total = pagos + retenciones;
                    $('#response_total').text(number_format(response_total));
                }
            });

            let total_suma_2 = 0;
            response.venta_credito.detalle_venta.forEach((element, index) => {
                let pv = Number(element.precio_venta);
                let desc = Number(element.descuento);
                let pvd = pv - desc;
                let imp = Number(pvd) * Number(element.impuesto) / 100 * Number(element.unidades);
                let total_column = Number(pvd) * Number(element.unidades);
                total_items += total_column;
                let valor_total = Number(pvd) * Number(element.unidades) + Number(imp);
                total = total + valor_total;
                timp += imp;

                html_tbody = html_tbody + "<tr> <td>" + validateNull(element.codigo_producto) + "</td> <td >" + element.nombre_producto + "</td> <td style='text-align:center;'>" + element.unidades + "</td> <td style='text-align:right;'>" + number_format(Number(element.precio_venta)) + "</td> <td style='text-align:center;'>" + number_format(validateNull(Number(element.descuento))) + "</td> <td style='text-align:right;'>" + number_format(valor_total) + "</td> </tr>";
                console.log(response.venta_credito.detalle_venta.length, index++)
                total_suma_2 = total_suma_2 + Number(valor_total);

                if (response.venta_credito.detalle_venta.length == index++) {
                    let row_suma_total = '<tr><td style="text-align: right;" colspan="6">Total: ' + number_format(total_suma_2) + '</td></tr>';
                    $('#items_total_venta').html(html_tbody + row_suma_total);
                }
            });


            response.forma_pago.forEach((element, index) => {
                html_option = html_option + "<option value=" + element.codigo + ">" + element.nombre + "</option>";
                console.log(response.forma_pago.length, index++);
                if (response.forma_pago.length == index++) {
                    $('#payMethod').html(html_option);
                }
            });


            let total_suma_1 = 0;
            response.data.forEach((element, index) => {
                //let btnEliminar = `<a href="/pos/index.php/pagos/eliminar/`+element.id_pago+`?factura=`+id+`" idPago="`+element.id_pago+`" class="button red acciones bt-eliminar-pago" onclick="if (confirm('Esta seguro que desea eliminar el registro?')) { return true; } else { return false; }"><div class="icon"><img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Verde/icono_verde-22.svg" alt="Imprimir"></div></a>`;
                let btnEliminar = `<a factura="` + id + `" idPago="` + element.id_pago + `" class="button red acciones bt-eliminar-pago" data-tooltip="Eliminar"><div class="icon"><img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Verde/icono_verde-22.svg" alt="Imprimir"></div></a>`;
                tbody_listado_abonos = tbody_listado_abonos + "<tr id='bono-" + element.id_pago + "'><td>" + element.fecha_pago + "</td><td>" + number_format(Number(element.cantidad)) + "</td><td>" + element.tipo + "</td><td>" + number_format(element.importe_retencion) + "</td><td> <a>" + btnEliminar + "</a> </td></tr>";
                total_suma_1 = total_suma_1 + Number(element.cantidad);
                console.log(response.data.length, index++);
                if (response.data.length == index++) {
                    let row_total = '<tr><td style="text-align: right;"> Total cantidad: </td><td>' + number_format(total_suma_1) + '</td><td colspan="3"></td></tr>';
                    $('#tbody_listado_abonos').html(tbody_listado_abonos + row_total);
                    console.log(total_suma_1);
                }
            });


            if (response.data.length == 0) {
                response_saldo = Number(response.venta_credito.venta.total_venta) + propina_value;
                $('#response_saldo').text(number_format(response_saldo));
                $('#tbody_listado_abonos').html('<tr><td class="text-center" colspan="5">No hay un listado de abonos</td></tr>');
            }


            $('#response_numero').text(response.numero);
            $('#response_pagos').text(number_format(pagos));
            $('#response_retenciones').text(number_format(retenciones));
            $('#response_t_fecha').text(response_t_fecha);
            $('#response_t_factura').text(response_t_factura);
            $('#response_t_nombre').text(response_t_nombre);
            $('#response_nombre_cliente').text(response_nombre_cliente);
            $('#response_cc').text(response_cc);
            $('#response_cellphone').text(response_cellphone);

            //results
            $('#response_total').text(number_format(response_total));

            //items_total_venta - items
            if (!$('#exampleModal').is(":visible")) {
                $('#exampleModal').modal('show');

            }
            $('#payDate').datepicker("setDate", year.toString() + "-" + month_2 + "-" + day.toString());

            $('.bt-eliminar-pago').attr("data-tooltip", "Eliminar");
            $('.bt-eliminar-pago').click(function() {
                let factura = $(this).attr("factura");
                let id_pago = $(this).attr("idPago");
                console.log(factura, id_pago);
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Desea eliminar el abono?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4cae4c',
                    cancelButtonColor: '#eee',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: '<span style="color: black;font-size: 12px;">Cancelar</span>'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "<?php echo site_url("pagos/eliminar_ajax") ?>/" + id_pago + '?factura=' + factura,
                            success: function(response) {
                                reloadAfterDeelte(factura);
                                Swal.fire(
                                    'Eliminado',
                                    'Se ha eliminado correctamente.',
                                    'success'
                                )

                            }
                        });
                    }
                })
            });
        }
        function valid_range() {
            let max_range = Number($('#response_saldo').text().replace(/[^0-9]/g, ''));
            let payValue = Number($('#payValue').val().replace(/[^0-9]/g, ''));
            let payRetention = Number($('#payRetention').val().replace(/[^0-9]/g, ''));
            
            if(payRetention + payValue > max_range){
                $('#text-error-suma').text('La suma de los campos "Valor a pagar" ('+number_format(Number($('#payValue').val().replace(/[^0-9]/g, '')))+') con "Retención" ('+toMoney(Number($('#payRetention').val().replace(/[^0-9]/g, '')))+') no debe ser mayor al saldo pendiente: '+ toMoney(Number($('#response_saldo').text().replace(/[^0-9]/g, ''))));
                return false;
            }
            else{
                $('#text-error-suma').text("");
                return true;
            }
        }
    });
</script>

<style>
    .color-red {
        color: red;
    }

    .font-size-9 {
        font-size: 9px;
    }

    .pt-2 {
        padding-top: 2em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .negrilla {
        font-weight: bold;
    }
</style>
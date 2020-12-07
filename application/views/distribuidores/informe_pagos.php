<style>
    .bootstrap-select.btn-group .dropdown-menu li a span.text {
        display: inline-block;
        color: #333;
        font-size: 12px;
    }
</style>
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informe de Pagos");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
        $is_admin = $this->session->userdata('is_admin');
         $username = $this->session->userdata('username');
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Listado de pagos por licencia");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="suscripcionesTable">
                        <thead>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Fecha pago");?></th>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Monto pago");?></th>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Observacion");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Nombre licencia");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Nombre del cliente");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Fecha inicio licencia");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Fecha de vencimiento");?></th>
                                <th width="12%"><?php echo custom_lang('sima_sales', "Estado del pago");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Fecha pago");?></th>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Monto pago");?></th>
                                <th width="15%"><?php echo custom_lang('sima_sales', "Observacion");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Nombre licencia");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Nombre del cliente");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Fecha inicio licencia");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Fecha de vencimiento");?></th>
                                <th width="12%"><?php echo custom_lang('sima_sales', "Estado del pago");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        oTable = $('#suscripcionesTable').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?php echo site_url('administracion_vendty/distribuidores/get_ajax_data_pagos');?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 12, "aLengthMenu": [5,10,25,50,100]
        });
        
        $("<div id='suscripcionesTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#suscripcionesTable_length');
        $("<div id='suscripcionesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#suscripcionesTable_length1');
                  
        $("<div id='suscripcionesTable_length3' class='dataTables_length'><label><select id='tipo_plan' class='selectpicker' data-live-search='true'><?php echo $tipos_licencia;?></select></label></div>").insertAfter('#suscripcionesTable_length2');
        $("<div id='suscripcionesTable_length4' class='dataTables_length'><label><select id='cliente' class='selectpicker' data-live-search='true'><?php echo $clientes;?></select></label></div>").insertAfter('#suscripcionesTable_length3');
       
        $("<div id='suscripcionesTable_length5' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#suscripcionesTable_length4');
        
        $( "#fecha_inicio" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
                }
        });

        $( "#fecha_fin" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
                }
        });
        
        $("#ex").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var almacen = $("#almacen").val();
            var dir = "<?php echo site_url("informes/exinforme_movimiento");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen;
            $(this).attr('href', dir);    
            
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var tipo_plan = $("#tipo_plan").val();
            var cliente = $("#cliente").val();
            oTable.fnReloadAjax( "<?php echo site_url("administracion_vendty/distribuidores/get_ajax_data_pagos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&tipo_plan="+tipo_plan+"&cliente="+cliente );
        });

    });
</script>
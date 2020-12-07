<style type="text/css">
#informesTable_filter{
    display: none;
}
</style>

<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('ventasxclientes', "Informe de comisiones");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Informe de comisiones");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Almacen");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Porcientos");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Valor Comision");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th><?php echo custom_lang('sima_recount_invoices', "Almacen");?></th>
                                <th><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Total");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Porcientos");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor Comision");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        var oTable;

        oTable = $('#informesTable').dataTable( {
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_informe_comision");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });

        $("<div id='informesTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');
        $("<div id='informesTable_length3' class='dataTables_length'><label><select id='vendedor'><?php echo $options;?></select></label></div>").insertAfter('#informesTable_length2');
        $("<div id='informesTable_length4' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length3');
        
        $('#filtrar').click(function(e){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var vendedor = $("#vendedor").val();
            var url = "<?php echo site_url('informes/get_ajax_data_informe_comision');?>";
            oTable.fnReloadAjax( url+"?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&vendedor="+vendedor );
        });

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
            var vendedor = $("#vendedor").val();
            var dir = "<?php echo site_url("informes/ex_informe_comision");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&vendedor="+vendedor;
            $(this).attr('href', dir);    
            
        });

    });
</script>
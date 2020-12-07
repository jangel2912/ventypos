
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('ventasxclientes', "Informe de transacciones de inventario");?></h2>                                          
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
            <!--a href="#" id="ex" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php //echo custom_lang('sima_export', "Exportar a Excel");?></a-->
            <a href="<?= site_url("informes/excel_transacciones") ?>" class="btn btn-success">Exportar a excel</a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Informe de transacciones de inventario");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_name', "Fecha");?></th>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Código documento");?></th>
                                <th><?php echo custom_lang('sima_sales', "Almacen");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Codigo del producto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Nombre producto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Descripcion");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Cantidad");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Razón");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Usuario");?></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
       oTable = $('#informesTable').dataTable( {
                "aaSorting": [[ 3, "desc" ]],

                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("informes/json_transacciones");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
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

    });
</script>
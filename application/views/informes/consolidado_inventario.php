<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Informes", "Informes");?></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('ventasxclientes', "Consolidado");?></h2>                                          

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

                <h2><?php echo custom_lang('ventasxclientes', "Consolidado del inventario");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">

                        <thead>

                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Categoria");?></th>

                                <th width="20%"><?php echo custom_lang('sima_name', "Producto");?></th>

                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Codigo");?></th>

                                <th width="10%"><?php echo custom_lang('sima_sales', "Total de precio venta");?></th>

                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Total de unidades");?></th>

                            </tr>

                        </thead>

                        <tbody> 

                                                     

                        </tbody>

                         <tfoot>

                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Categoria");?></th>

                                <th width="20%"><?php echo custom_lang('sima_name', "Producto");?></th>

                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Codigo");?></th>

                                <th width="10%"><?php echo custom_lang('sima_sales', "Total de precio venta");?></th>

                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Total de unidades");?></th>

                            </tr>

                        </tfoot>

                    </table>

             

                </div>

            </div>

            

        </div>

    </div>

<script type="text/javascript">

    $(document).ready(function(){

       oTable = $('#informesTable').dataTable( {

                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_consolidado_inventario");?>",

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

        

        $("#ex").click(function(){

            var fecha_inicio = $("#fecha_inicio").val();

            var fecha_fin = $("#fecha_fin").val();

            var dir = "<?php echo site_url("informes/consolidado_inventario_ex");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin;

            $(this).attr('href', dir);    

            

        });

        

        $("#filtrar").click(function(){

            var fecha_inicio = $("#fecha_inicio").val();

            var fecha_fin = $("#fecha_fin").val();

            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_consolidado_inventario");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin );

        });



    });

</script>
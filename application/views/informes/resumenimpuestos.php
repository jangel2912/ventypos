<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('resumenimpuestos', "Resumen impuestos");?></h2>                                          
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
            <a href="<?php echo site_url("informes/exresumenimpuestos");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('resumenimpuestos', "Resumen impuestos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="40%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax_percent', "Porciento");?></th>
                                <th width="20%"><?php echo custom_lang('valorsinimpuesto', "Valor sin impuesto");?></th>
                                <th width="20%"><?php echo custom_lang('valordelimpuesto', "Valor del impuesto");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                 <th><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_tax_percent', "Porciento");?></th>
                                <th><?php echo custom_lang('valorsinimpuesto', "Valor sin impuesto");?></th>
                                <th><?php echo custom_lang('valordelimpuesto', "Valor del impuesto");?></th>
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
                /*"bServerSide": true,*/
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_resumenimpuestos");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
        
        $( "#fecha_inicio" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                onClose: function( selectedDate ) {
                        $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
                }
        });
        $( "#fecha_fin" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                onClose: function( selectedDate ) {
                        $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
                }
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_resumenimpuestos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin );
        });

    });
</script>
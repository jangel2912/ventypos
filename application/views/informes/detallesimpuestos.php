<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('detallesimpuestos', "Detalles de impuestos");?></h2>                                          
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
            <a href="#" id='ex' class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('detallesimpuestos', "Detalles de impuestos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_state', "Estado");?></th>
                               <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="10%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="20%"><?php echo custom_lang('sima_client', "Cliente");?></th>
                                <th width="10%"><?php echo custom_lang('valueoftheinvoice', "Valor de la factura");?></th>
                                <th width="10%"><?php echo custom_lang('valueofthetax', "Valor del impuesto");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                <th ><?php echo custom_lang('sima_state', "Estado");?></th>
                                <th ><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th ><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th ><?php echo custom_lang('sima_clientt', "Cliente");?></th>
                                <th ><?php echo custom_lang('valueoftheinvoice', "Valor de la factura");?></th>
                                <th><?php echo custom_lang('valueofthetax', "Valor del impuesto");?></th>
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
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_detallesimpuestos");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
        $("<div id='informesTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');
        $("<div id='informesTable_length3' class='dataTables_length'><label><button class='btn' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length2');
    
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
            var dir = "<?php echo site_url("informes/exdetallesimpuestos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin;
            $(this).attr('href', dir);    
            
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_detallesimpuestos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin );
        });

    });
</script>
<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.css?v2.2.0">
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
            $is_admin = $this->session->userdata('is_admin');
            $permisos = $this->session->userdata('permisos');
            ?>
            <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
            <div class="col-md-6">                               
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a data-tooltip="Descargar Excel" id="ex">                        
                        <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_quotes', "Órdenes de Producción"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="produccionTable">
                    <thead>
                        <tr>
                            <th width="10%"><?php echo custom_lang('sima_number', "Consecutivo"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_customer', "Usuario"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_total_price', "Almacén"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_date', "Estado"); ?></th>
                            <th class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><?php echo custom_lang('sima_number', "Consecutivo"); ?></th>
                            <th><?php echo custom_lang('sima_customer', "Usuario"); ?></th>
                            <th><?php echo custom_lang('sima_total_price', "Almacén"); ?></th>
                            <th><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                            <th><?php echo custom_lang('sima_date', "Estado"); ?></th>
                            <th class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        
        $('#produccionTable').dataTable({
            "aaSorting": [[0, "desc"]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("produccion/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10, "aLengthMenu": [5, 10, 25, 50, 100],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [5], "bSearchable": false,
                    "mRender": function (data, type, row) {
                        var buttons = "";
                       // buttons += '<a title="Factura" href="#" onclick="view_modal( ' + data + ', \''+ row[4] +'\' )" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal" class="button green btn-print" target="" title="Imprimir" targe ><div class="icon"><span class="glyphicon glyphicon-list-alt"></span></div></a>';
                        return buttons;
                    }
                }
            ]
        });
        
        
        $("<div id='produccionTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#produccionTable_length');
        $("<div id='produccionTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#produccionTable_length1');
        $("<div id='produccionTable_length3' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#produccionTable_length2');
        
        
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
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin    = $("#fecha_fin").val();
            $('#produccionTable').dataTable().fnReloadAjax( "<?= site_url("produccion/get_ajax_data"); ?>?fecha_inicio="+ fecha_inicio +"&fecha_fin="+ fecha_fin );
        });
        
        
        $("#ex").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var dir = "<?php echo site_url("produccion/expexcel");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin;
            $(this).attr('href', dir);    
        });

    });

    mixpanel.track("Informe_Produccion");  
</script>
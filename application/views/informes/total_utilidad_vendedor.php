<style type="text/css">
#informesTable_filter{
    display: none;
}
</style>
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
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
            <div class="col-md-6">            
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
                    <a id="ex" data-tooltip="Exportar Excel">                            
                        <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                           
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
                <h2><?php echo custom_lang('ventasxclientes', "Comisiones de Vendedor por Utilidad");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Total");?></th>
                                
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Costo");?></th>

                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Utilidad");?></th>

                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Porciento");?></th>
                               

                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Valor Comisión");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Total");?></th>

                                <th><?php echo custom_lang('sima_sales_taxes', "Costo");?></th>
                                
                                <th><?php echo custom_lang('sima_sales_taxes', "Utilidad");?></th>

                                <th><?php echo custom_lang('sima_sales_taxes', "Porciento");?></th>
                    
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor Comisión");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>            
        </div>
    </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
       oTable = $('#informesTable').dataTable( {

                "aaSorting": [[2, "desc" ]],
	   
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_informe_utilidad");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
        $("<div id='informesTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');
        $("<div id='informesTable_length3' class='dataTables_length'><label><select id='vendedor'><?php echo $options;?></select></label></div>").insertAfter('#informesTable_length2');
    
        $("<div id='informesTable_length4' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length3');
    
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
            var dir = "<?php echo site_url("informes/ex_informe_utilidad");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&vendedor="+vendedor;
            $(this).attr('href', dir);    
            
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var vendedor = $("#vendedor").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_informe_utilidad");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&vendedor="+vendedor );
        });

    });
    
    //mixpanel.track("informe_vendedor_utilidad");  
</script>
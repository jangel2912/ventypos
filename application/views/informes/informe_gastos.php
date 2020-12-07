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
                <div class="col-md-6">                   
                </div>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a data-tooltip="Exportar Excel" id="ex">                        
                            <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                        </a>
                    </div>
                </div>
            <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->            
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Informe de Gastos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="5%"><?php echo custom_lang('sima_name', "Consecutivo");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_recount_invoices', "Proveedor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Descripci&oacute;n");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Valor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Cantidad");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Valor con Impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Almacen");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Cuenta Dinero");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_name', "Consecutivo");?></th>
                                <th><?php echo custom_lang('sima_name', "Fecha");?></th>
                                <th><?php echo custom_lang('sima_recount_invoices', "Proveedor");?></th>
                                <th><?php echo custom_lang('sima_sales', "Descripci&oacute;n");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Impuesto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Cantidad");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor con Impuesto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Almacen");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Cuenta Dinero");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
    <?php 
    $select ="<select  name='almacen' id='almacen_filtro' >";    
    $select.="<option value='0'>Todos los Almacenes</option>";    
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        $select.="<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    $select.="</select>"; 

    ?>
<script type="text/javascript">
    $(document).ready(function(){
       oTable = $('#informesTable').dataTable( {
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_informe_gastos");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aaSorting": [0, "desc" ],
        });
        $("<div id='informesTable_length1' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');

        var select="<?php echo $select; ?>";

        $("<div id='informesTable_length3' class='dataTables_length'><label>"+select+"</div>").insertAfter('#informesTable_length2');
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
            var almacen = $("#almacen_filtro").val();
            var dir = "<?php echo site_url("informes/exinformegastos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen;
            $(this).attr('href', dir);    
            
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var almacen = $("#almacen_filtro").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_informe_gastos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen );
        });

    });
    mixpanel.track("informe_gastos");      
</script>
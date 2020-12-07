<style>
    .dataTables_length{max-width:170px;}
    .ui-datepicker{z-index:99 !important; background-color: #fff;}
    .ui-datepicker-next, .ui-datepicker-prev{display:none;}
    #fecha_inicio,#fecha_fin{max-width:130px;}
    .fields{background-color: #e9e9e9;padding: 10px;box-sizing: border-box;padding-top: 11px;}
    .fields label{font-weight:bold; margin-left:2rem;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-10 text-right">
            <a data-tooltip="Descargar Excel" id="ex">                        
                <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a> 
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">         
            <div class="head blue">
                <h2><?php echo custom_lang('detalle_ventas_por_vendedor', "Informe de Ventas por vendedor");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Almacen");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "No Factura");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Vendedor");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Cedula Vendedor");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Descuento");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Impuesto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Neto sin impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Total venta");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                         <tfoot>
                            <tr>
                            <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Almacen");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "No Factura");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Vendedor");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Cedula Vendedor");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Descuento");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Impuesto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Neto sin impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Total venta");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>

        <div class="col-md-12 text-right fields">
            <table class="table table-bordered">
                <tr>
                    <td class="text-center">Venta neta</td>
                    <td class="text-center">Impuestos</td>
                    <td class="text-center">Descuentos</td>
                    <td class="text-center">Devoluciones</td>
                    <td class="text-center">Unidades</td>
                    <td class="text-center">Unidades Devueltas</td>
                    <td class="text-center">Transacciones</td>
                    <td class="text-center" data-tooltip="UPT (Número de unidades / Número de facturas)">Unidades vendidas promedio en cada transacción</td>
                    <td class="text-center" data-tooltip="VPT (Total Neto / Número de facturas)">Valor promedio facturado por transacción</td>
                    <td class="text-center" data-tooltip="VPU (Total Neto / Número de unidades)">Valor promedio por unidad</td>
                </tr>
                <tr>
                    <td class="text-center">
                        <span  id="total_venta_neta"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_impuestos"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_descuentos"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_devoluciones"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_unidades"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_unidades_devueltas"></span>
                    </td>
                    <td class="text-center">
                        <span  id="total_transacciones"></span>
                    </td>
                    <td class="text-center">
                        <span  id="upt"></span>
                    </td>
                    <td class="text-center">
                        <span  id="vpt"></span>
                    </td>
                    <td class="text-center">
                        <span  id="vpu"></span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        oTable = $('#informesTable').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?php echo site_url('informes/get_ajax_data_detalle_ventas_por_vendedor');?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aaSorting": [[ 0, "desc" ]]
        });
        
        $("<div id='informesTable_length1' data-tooltip='Selecciona fecha de inicio' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' data-tooltip='Selecciona fecha final' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');           
        $("<div id='informesTable_length3' class='dataTables_length'><label><select id='almacen'><?php echo $options;?></select></label></div>").insertAfter('#informesTable_length2');
        $("<div id='informesTable_length4' class='dataTables_length'><label><select id='vendedor'><?php echo $options_vendedor;?></select></label></div>").insertAfter('#informesTable_length3');
        $("<div id='informesTable_length5' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length4');
        
        $( "#fecha_inicio" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,           
            currentText: "Today:",
            maxDate: "0",           
            yearRange: "-2:+0",
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
        });
       
        $( "#fecha_fin" ).datepicker({            
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,           
            currentText: "Today:",
            maxDate: "0",           
            yearRange: "-2:+0",
            onClose: function( selectedDate ) {
                $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        
        $("#ex").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var almacen = $("#almacen").val();
            var vendedor = $("#vendedor").val();
            var url = '<?php echo site_url("informes/ex_detalle_ventas_por_vendedor");?>'+'?fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin+'&almacen='+almacen+'&vendedor='+vendedor;
            $(this).attr('href', url);    
            
        });
        
        $("#filtrar").click(function(){
         filtrar_ventas();   
        });
     
        function filtrar_ventas(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();

            var dias_diferencia = moment(fecha_fin).diff(moment(fecha_inicio), 'days');
            if(dias_diferencia > 30){
                    alert("No es posible consultar las ventas en un rango mayor a 1 mes");
            }else{
                var almacen = $("#almacen").val();
                var vendedor = $("#vendedor").val();
                var url = '<?php echo site_url("informes/get_ajax_data_detalle_ventas_por_vendedor");?>'+'?fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin+'&almacen='+almacen+'&vendedor='+vendedor;
                
                $.get(url,function(data){
                    var totales = JSON.parse(data);
                    $("#total_venta_neta").html(""+totales.total_venta_neta);
                    $("#total_impuestos").html(""+totales.total_impuestos);
                    $("#total_descuentos").html(""+totales.total_descuentos);
                    $("#total_devoluciones").html(""+totales.total_devoluciones);
                    $("#total_unidades").html(""+totales.total_unidades);
                    $("#total_unidades_devueltas").html(""+totales.total_unidades_devueltas);
                    $("#total_transacciones").html(""+totales.total_transacciones);
                    $("#upt").html(""+totales.UPT);
                    $("#vpt").html(""+totales.VPT);
                    $("#vpu").html(""+totales.VPU);

                    var dates = oTable.fnReloadAjax(url);
                })
           }
        }

        filtrar_ventas(); 

    });
</script>
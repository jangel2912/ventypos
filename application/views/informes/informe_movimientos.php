<style>
    table.ui-datepicker-calendar{
        background-color:#e9e9e9;
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
                $is_admin = $this->session->userdata('is_admin');
                $username = $this->session->userdata('username');
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
            <div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Informe de Movimientos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Usuario");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Documento/Factura");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Consecutivo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Nota");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Almacén Destino");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Id producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Producto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Código Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Descripción Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Costo Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Unidad Producto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Cantidad");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Tipo Movimiento");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                         <tfoot>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Usuario");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Documento/Factura");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Consecutivo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Nota");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Almacén Destino");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Id producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Producto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Código Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Descripción Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Costo Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Unidad Producto");?></th>
                                <th width="5%"><?php echo custom_lang('sima_sales_taxes', "Cantidad");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Tipo Movimiento");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        oTable = $('#informesTable').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?php echo site_url('informes/get_ajax_data_informe_movimientos');?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aaSorting": [[ 0, "desc" ]]
        });
        
        $("<div id='informesTable_length1' data-tooltip='Periódo máximo a consultar 3 meses' class='dataTables_length'><label><input type='text' placeholder='Fecha inicial' id='fecha_inicio'/></label></div>").insertAfter('#informesTable_length');
        $("<div id='informesTable_length2' data-tooltip='Periódo máximo a consultar 3 meses' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');
        <?php if( $is_admin == 't' ){ //administrador ?>                
            $("<div id='informesTable_length3' class='dataTables_length'><label><select id='almacen'><?php echo $options;?></select></label></div>").insertAfter('#informesTable_length2');
        <?php } ?>  
        
        if ( $("#informesTable_length3").length > 0 ) {
            $("<div id='informesTable_length4' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length3');
        }else{
            $("<div id='informesTable_length3' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length2');
        }
        
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
            var dir = "<?php echo site_url("informes/exinforme_movimiento");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen;
            $(this).attr('href', dir);    
            
        });
        
        $("#filtrar").click(function(){
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_fin").val();
            var almacen = $("#almacen").val();

           var dias_diferencia = moment(fecha_fin).diff(moment(fecha_inicio), 'days');
           if(dias_diferencia > 90){
               //alert("No es posible consultar los movimientos en un rango mayor a 3 meses");
                $("#mensaje").html("No es posible consultar los movimientos en un rango mayor a 3 meses");
                $("#mensaje").removeClass('hidden');
           }else{
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_informe_movimientos");?>?fecha_inicio="+ fecha_inicio+"&fecha_fin="+fecha_fin+"&almacen="+almacen );
           }

      });

    });

//mixpanel.track("informe_movimientos");  
</script>
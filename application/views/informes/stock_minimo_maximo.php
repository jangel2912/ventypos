<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
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
            <div class="col-md-6">   
            </div>
            <div class="col-md-6 btnizquierda">   
                <div class="col-md-2 col-md-offset-10"> 
                    <a data-tooltip="Exportar Excel" id="ex">                        
                        <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a> 
                </div>                  
                <!--<a href="#" id="ex" target="_blank" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->                
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('Stock Actual', "Stock Actual");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name', "Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Código");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Precio");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Proveedor");?></th>                                
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Stock Mínimo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Valor de Inventario");?></th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                         <tfoot>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name', "Producto");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales', "Código");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Precio");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Proveedor");?></th>                                
                                <th width="10%"><?php echo custom_lang('sima_sales_taxes', "Stock Mínimo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Valor de Inventario");?></th>
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
                "bServerSide" : true,
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_stock_minimo_maximo");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100]

        });
   <?php   
 
 	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');     
   
   if( $is_admin == 't' || $is_admin == 'a'){   ?>
        var combo_text = "<?php $combo = "<select id='almacenes'><option value='0'>Todos los almacenes</option>"; foreach ($data['almacenes'] as $key => $value){ $combo .= "<option value='".$key."'>".$value."</option>";} $combo .= "</select>"; echo $combo; ?>";
     <?php   }     ?>
        var combo_text1 = "<?php $combo1 = "<select id='stock'><option value='minimo'>Stock M&iacute;nimo</option><option value='maximo'>Stock Maximo</option></select>"; echo $combo1; ?>";
		
        $("<div id='informesTable_length1' class='dataTables_length'><label>"+combo_text+"</label></div>").insertAfter('#informesTable_length');
		
		// $("<div id='informesTable_length2' class='dataTables_length'><label>"+combo_text1+"</label></div>").insertAfter('#informesTable_length');

        //$("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');

        $("<div id='informesTable_length3' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length1');

    

        /*$( "#fecha_inicio" ).datepicker({

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

        });*/

        

        $("#ex").click(function(){

            var filtro = 0;

            if($("#almacenes").val() != '-1'){

                filtro = $("#almacenes").val();

            }

            var dir = "<?php echo site_url("informes/exstockminimomaximo");?>?almacen="+filtro;

            $(this).attr('href', dir);    

            

        });

        

        $("#filtrar").click(function(){

            var filtro = $("#almacenes").val();
			 var stock = $("#stock").val();

            if(filtro != -1){

                oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_stock_minimo_maximo");?>?almacen="+filtro+"&stock="+stock);

            }

        });



    });

    mixpanel.track("Inventario_con_Stock_Mínimo");  
</script>
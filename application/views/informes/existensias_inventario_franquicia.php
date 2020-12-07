<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('ventasxclientes', "Existencias de inventario");?></h2>                                          

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

            <!--<a href="#" id="ex" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php //echo custom_lang('sima_export', "Exportar a Excel");?></a>-->

            <div class="head blue">

                <div class="icon"><i class="ico-box"></i></div>

                <h2><?php echo custom_lang('ventasxclientes', "Existencias de inventario");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">

                        <thead>

                            <tr>

                                <th ><?php echo custom_lang('sima_recount_invoices', "Almacen");?></th>

                                <th ><?php echo custom_lang('sima_name', "Categoria");?></th>

                                <th ><?php echo custom_lang('sima_name', "Producto");?></th>

                                <th ><?php echo custom_lang('sima_sales', "Codigo");?></th>

                                <th ><?php echo custom_lang('sima_sales', "Unidad");?></th>

                                <th ><?php echo custom_lang('sima_sales_taxes', "Precio");?></th>

                                <th ><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>

                                <th ><?php echo custom_lang('sima_sales_taxes', "Valor de inventario");?></th>

                                <th ><?php echo custom_lang('sima_sales_location', "Ubicación");?></th>
                                
                                <th ><?php echo custom_lang('sima_sales_fecha_vencimiento', "Fecha Vencimiento");?></th>

                            </tr>

                        </thead>


                    </table>

             

                </div>

            </div>

            

        </div>

    </div>

<script type="text/javascript">

    $(document).ready(function(){

       oTable = $('#informesTable').dataTable( {

                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_existensias_inventario_franquicia") . '/' . $id_franquicia;?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]

        });
   
   <?php 
   	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');   
		 
   if($is_admin == 't' || $is_admin == 'a'){  ?>
        var combo_text = "<?php $combo = "<select id='almacenes'><option value='-1'>Seleccione un almacen</option>"; foreach ($data['almacenes'] as $key => $value){ $combo .= "<option value='".$key."'>".$value."</option>";} $combo .= "</select>"; echo $combo; ?>";

        $("<div id='informesTable_length1' class='dataTables_length'><label>"+combo_text+"</label></div>").insertAfter('#informesTable_length');

        //$("<div id='informesTable_length2' class='dataTables_length'><label><input type='text' placeholder='Fecha final' id='fecha_fin'/></label></div>").insertAfter('#informesTable_length1');

        $("<div id='informesTable_length3' class='dataTables_length'><label><button class='btn' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length1');
    <?php } ?>
    

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

            var dir = "<?php echo site_url("informes/exexistensiasinventario");?>?almacen="+filtro;

            $(this).attr('href', dir);    

            

        });

        

        $("#filtrar").click(function(){

            var filtro = $("#almacenes").val();

            if(filtro != -1){

                oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_existensias_inventario_franquicia"). '/' . $id_franquicia;?>?almacen="+ filtro);

            }

        });



    });

</script>
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
                <h2><?php echo custom_lang('ventasxclientes', "Notas Crédito y Débito");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Código");?></th>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Devolución");?></th>
                                <th ><?php echo custom_lang('sima_name', "Usuario");?></th>
                                <th ><?php echo custom_lang('sima_name', "Tipo");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Valor");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Cliente");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Estado");?></th>
                                <th ><?php echo custom_lang('sima_sales_location', "Factura Asociada");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Código");?></th>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Devolución");?></th>
                                <th ><?php echo custom_lang('sima_name', "Usuario");?></th>
                                <th ><?php echo custom_lang('sima_name', "Tipo");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Valor");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Fecha");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Factura");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Cliente");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Estado");?></th>
                                <th ><?php echo custom_lang('sima_sales_location', "Factura Asociada");?></th>
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

                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_notas");?>",

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

        $("<div id='informesTable_length3' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length1');
    <?php } ?>
    
        $("#ex").click(function(){

            var filtro = 0;

            if($("#almacenes").val() != '-1'){

                filtro = $("#almacenes").val();

            }

            var dir = "<?php echo site_url("informes/exnotas");?>?almacen="+filtro;

            $(this).attr('href', dir);    

            

        });

        

        $("#filtrar").click(function(){

            var filtro = $("#almacenes").val();

            if(filtro != -1){

                oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_notas");?>?almacen="+filtro);

            }

        });



    });

mixpanel.track("Informe_notas_creditos");  
</script>
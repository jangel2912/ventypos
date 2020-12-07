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
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div id="mensaje" class="alert alert-error hidden"></div>  
            <!--<a href="#" id="ex" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
            <!--<a data-tooltip="Exportar Excel" id="ex">                        
                <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a>-->
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-6">       
    </div>
    <div class="col-md-6 btnizquierda">
        <div class="col-md-2 col-md-offset-10">
            <a data-tooltip="Exportar Excel" id="ex">                        
                <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('Existencias de Inventario por Serial', "Existencias de Inventario por Serial");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th ><?php echo custom_lang('sima_name', "Categoría");?></th>
                                <th ><?php echo custom_lang('sima_name', "Producto");?></th>
                                <th ><?php echo custom_lang('sima_name', "Serial/Imei");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Código");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Unidad");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Precio Compra");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Precio Venta");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Vendido");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th ><?php echo custom_lang('sima_recount_invoices', "Almacén");?></th>
                                <th ><?php echo custom_lang('sima_name', "Categoría");?></th>
                                <th ><?php echo custom_lang('sima_name', "Producto");?></th>
                                <th ><?php echo custom_lang('sima_name', "Serial/Imei");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Código");?></th>
                                <th ><?php echo custom_lang('sima_sales', "Unidad");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Precio Compra");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Precio Venta");?></th>
                                <th ><?php echo custom_lang('sima_sales_taxes', "Vendido");?></th>
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
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_existencias_inventario_imei");?>",
                "sPaginationType": "simple_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "bInfo" : false,
        });

    $.fn.dataTable.ext.errMode = 'throw';
   
   <?php 
   	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');   
    ?>
        
        $("#ex").click(function(){
            var filtro = 0;
                //filtro = $("#almacenes").val();
            var dir = "<?php echo site_url("informes/exexistensiasimei");?>?almacen="+filtro;
            $(this).attr('href', dir);    
        });

        $("#filtrar").click(function(){
            var filtro = $("#almacenes").val();
                oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_existensias_inventario");?>?almacen="+ filtro);
        });
    });

</script>
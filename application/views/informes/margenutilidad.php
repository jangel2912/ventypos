<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('margin_utility', "Margen de utilidad");?></h2>                                          
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
            <a href="<?php echo site_url("informes/exmargenutilidad");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('margin_utility', "margen de utilidad");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                               <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>
                                <th width="10%"><?php echo custom_lang('sale_price', "Precio de venta");?></th>
                                <th width="10%"><?php echo custom_lang('margin_utility', "margen de utilidad");?></th>
                                <th width="15%"><?php echo custom_lang('sima_percent', "Porciento");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                               <th ><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>
                                <th><?php echo custom_lang('sale_price', "Precio de venta");?></th>
                                <th><?php echo custom_lang('margin_utility', "margen de utilidad");?></th>
                                <th><?php echo custom_lang('sima_percent', "Porciento");?></th>
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
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_margenutilidad");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
        
        
        $("#filtrar").click(function(){
          //  oTable.sAjaxSource = "PRUEBA AAAAA";
            oTable.fnClearTable();
        });

    });
</script>
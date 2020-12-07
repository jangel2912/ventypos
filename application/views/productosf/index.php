<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Productos");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_product_list', "Listado de productos");?></h2>                                          
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
            <?php $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');  
                if(in_array("51", $permisos) || $is_admin == 't'):?>
                 <a href="<?php echo site_url("productosf/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_product', "Nuevo producto");?></a>
            <?php endif;?>
            <a href="<?php echo site_url("productosf/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <a href="<?php echo site_url("productosf/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_all_product', "Todos los productos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                        <thead>
                            <tr>
                                
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?></th>
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>
                                <th width="10%"><?php echo custom_lang('sima_price', "Precio de venta");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                    
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th><?php echo custom_lang('sima_description', "Descripci&oacute;n");?></th>
                                 <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>
                                <th><?php echo custom_lang('sima_price', "Precio de venta");?></th>
                                <th><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#productosTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("productosf/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                            var buttons = "";
                            <?php if(in_array('52', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php echo site_url("productosf/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                                 <?php endif;?> 
                                 <?php if(in_array('53', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php echo site_url("productosf/eliminar/");?>/'+data+'" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';
                                 <?php endif;?>
                            return buttons;
                        } 
                    }
                ]
        });
    });
</script>
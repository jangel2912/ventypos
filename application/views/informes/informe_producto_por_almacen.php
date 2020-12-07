<div class="page-header">
<div class="icon">
    <span class="ico-box"></span>
</div>
<h1><?php echo custom_lang("Informes", "Informes");?></h1>
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
       
        <div class="head blue">
            <div class="icon"><i class="ico-box"></i></div>
            <h2><?php echo custom_lang('productoxalmacen', "Informe de productos por almacen");?></h2>
        </div>

        
        <form class="form-inline form_datatable" action="" method="post">
            <div class="form-group">
                <label for="producto">Producto</label>
                <input type="text" class="form-control" id="producto" placeholder="Nombre o Codigo">
                <button class="btn btn-success" id="search_products">Consultar</button>
            </div>
            
        </form>
       

        <div class="data-fluid">
            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                <thead>
                    <tr>
                        <th><?php echo custom_lang('sima_sales', "Codigo producto");?></th>
                        <th><?php echo custom_lang('sima_sales', "Producto");?></th>
                        <th><?php echo custom_lang('sima_sales', "Almacen");?></th>
                        <th><?php echo custom_lang('sima_sales', "Unidades");?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        </div>
        
    </div>
</div>
<script type="text/javascript">
    $("#search_products").click(function(e){
        e.preventDefault();
        var producto = $("#producto").val();

        $('#informesTable').dataTable({ 
        "searching": false,
        "bProcessing": true,
        "ordering": false,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
        "ajax": {
            'type': 'POST',
            'url': '<?php echo site_url("informes/get_ajax_data_producto_por_almacen");?>',
            'data': {
            producto: producto,
                },
            }
        }).fnDestroy();
    });
</script>
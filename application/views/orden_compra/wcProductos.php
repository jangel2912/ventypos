<script>
$(document).ready(function(){
    var productos = [];
    $.post
    (
        "<?php echo site_url('orden_compra/pruebaProductos') ?>",
        {"post":"post"},
        function(data)
        {
            console.log(data);
            $.each(data,function(i,e)
            {
                /*$.post
                (
                    "<?php echo site_url('RestFullController/consultarWC') ?>",
                    {'codigo':$(e)[0]['codigo']},
                    function(data)
                    {console.log("Nombre:"+$(e)[0]['nombre']+"||Codigo:"+$(e)[0]['codigo']);
                        if(data.lenght < 1)
                        {
                            console.log("Nombre:"+$(e)[0]['nombre']+"||Codigo:"+$(e)[0]['codigo']);
                        }
                    }
                );*/
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('RestFullController/consultarWC') ?>",
                    dataType: "html",
                    data: {'codigo':$(e)[0]['codigo']},
                    success: function (response) {
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        var tr = "<tr><td>"+$(e)[0]['nombre']+"</td><td>"+$(e)[0]['codigo']+"</td></tr>";
                        $("table tbody").append(tr);
                        //array.push(array("nombre"=>$(e)[0]['nombre'],"codigo"=>$(e)[0]['codigo']));
                      console.log("Nombre:"+$(e)[0]['nombre']+"||Codigo:"+$(e)[0]['codigo']);
                    }
                });
                //console.log($(e)[0]['codigo']);
            });
        },'json'
    );
});
</script>
<style type="text/css">
    .ui-dialog{
        z-index: 9000!important;
    }
</style>
<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Orden de Compra", "Productos Con Errores"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_bill', "Productos Con Errores"); ?></h2>                                          

    </div>

</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="clientesTable">
                    <thead>
                        <tr>
                            <th><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                            <th><?php echo custom_lang('sima_reason', "Codigo");?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                            <th><?php echo custom_lang('sima_reason', "Codigo");?></th>
                        </tr>
                    </tfoot>            

                </table>
            </div>
        </div>        
    </div>

</div>
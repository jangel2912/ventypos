<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>
<div id="form_view">
    <?= form_open_multipart("productos/import_compuesto_save", 'id="form_price_update"'); ?>
    <div class="head">
        <h4>Bienvenido! Siga los siguientes pasos para cargar los PRODUCTOS COMPUESTOS desde Excel. </h4> 
        <br />
        <h5>1. Click en el siguiente enlace para descargar la plantilla de Excel &nbsp;&nbsp;<a href="<?php echo site_url("/productos/load_plantilla_compuesto/")?>">CLICK AQUI</a></h5>
        <h5>2. Complete las siguientes opciones:</h5>
        <h5>3. Abra el archivo (Plantilla Compuestos) que descargo a su computador y Asigne los productos a los compuestos.</a>.</h5> 	                                        
        <img src="<?php echo base_url("/public/img/"); ?>/example_compuesto.png" width="800px" />
        <h5>4. Click en el boton buscar, selecione la plantilla de excel que se encuentra en su computador con la información que completó.</h5> 	
        <h5>5. Por ultimo click en cargar.</h5> 		   	

    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="block">
                <div class="data-fluid">
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('sima_file', "Archivo"); ?>:<br/>
                        </div>
                        <div class="span9">                            
                            <div class="input-append file">
                                <input type="file" name="archivo" id="archivo"/>
                                <input type="text"/>
                                <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                        </div>
                    </div> 
                    <div class="toolbar bottom tar">
                        <div class="">
                            <input type="hidden" name="update_confirm" id="update_confirm" value=""/>
                            <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Cargar"); ?></button>
                            <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>
<div id="form_result">

</div>

<script>
    $(document).ready(function () {
        $("#form_price_update").submit(function (e) {
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
//                dataType: "json",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.res == 'error') {
                        toastrErrorMessage(data.error);
                    } else {
                        if (data.res == 'success'){
                            toastrSuccessMessage(data.success)
                        }else{
                            toastrWarningMessage(data.success)
                        }

                        if (data.html) {
                            $('#form_view').css("display", "none");
                            ;
                            $('#update_confirm').val('1');
                            $('#form_result').html(data.html);
                        }
                    }
                }
            });
            e.preventDefault();
        });




    });



    function toastrSuccessMessage(msg) {
        var title = "Bien Hecho!";
        var shortCutFunction = "success";
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }
    
    function toastrWarningMessage(msg) {
        var title = "Bien Hecho!";
        var shortCutFunction = "warning";
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "0",
            "extendedTimeOut": "0",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }

    function toastrErrorMessage(msg) {
        var title = "Alerta!";
        var shortCutFunction = "error";
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#toastrOptions").text("Command: toastr[" + shortCutFunction + "](\"" + msg + (title ? "\", \"" + title : '') + "\")\n\ntoastr.options = " + JSON.stringify(toastr.options, null, 2));
        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    }



</script>
<style>
    #mensaje {        
        font-size: 20px;
    }
    #mensaje .error {        
        color: red;
    }
    #mensaje .success {        
        color:green;
    }   
    .label-danger{
        background-color: #d9534f !important;
    }
</style>
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Actualizar Productos Por Almacén"); ?></h1>
</div>
<div id="form_view">
    <?= form_open_multipart("productos/import_excel_price_update", 'id="form_price_update"'); ?>
    <div class="head">
        <h4>Bienvenido! Siga los siguientes pasos para actualizar precios por almacén desde Excel </h4> 
        <br />
        <h5>1. Click en el siguiente enlace para descargar la plantilla de Excel &nbsp;&nbsp;<a href="<?php echo base_url("/uploads1/Precios por almacen.xlsx"); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Inventario.</h5>
        <h5>2. Complete las siguientes opciones:</h5>
        <div class="row-fluid">
            <div class="block">
                <div class="data-fluid">
                    <div class="span6">
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_date', "Fecha"); ?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>
                                <?php echo form_error('fecha'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('almacen', "Almacen"); ?>:</div>
                            <div class="span9">
                                <?php echo form_dropdown('almacen_id', $data['almacenes']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h5>3. Abra el archivo Plantilla Inventario que descargo a su computador y comience a ingresar el código y precio que tiene el producto.</a>.</h5> 	                                        
            <img src="<?php echo base_url("/public/img/"); ?>/csv_inventario_1.png?act=2" width="800px" />
            <h5>4. Click en el boton buscar, selecione la plantilla de excel que se encuentra en su computador con la información que completó.</h5> 	
            <h5>5. Por ultimo click en cargar.</h5> 		   	
        </div>
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
                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                            </div>
                        </div>
                    </div> 
                    <div class="toolbar bottom tar">                        
                        <input type="hidden" name="update_confirm" id="update_confirm" value=""/>
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href = 'index'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                        <button class="btn btn-success" id="cargar" type="submit"><?php echo custom_lang("sima_submit", "Cargar"); ?></button>                                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>
<div id="mensaje" class="text-center">
    
</div>
<div id="form_result">
</div>

<script>
    $(document).ready(function () {
        $("#form_price_update").submit(function (e) {
            $("#cargar").prop('disabled',true);
            $("#mensaje").html('');
            e.preventDefault();
           
            swal({
                title: 'Espere un momento!',
                text: 'Se está verificando el archivo seleccionado.',
                imageUrl: '<?php echo base_url()."public/img/loaders/loading_icon.gif";?>',
                imageWidth: 200,
                imageHeight: 200,
                imageAlt: 'Cargando',
                animation: false,
                showConfirmButton: false
            });
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
//                dataType: "json",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data) {
                    //console.log(data);
                    if (data.res == 'error') {                        
                        $("#mensaje").html('<div class="error">'+data.error+'</div>');
                        $("#mensaje").removeClass('hidden');
                        $("#cargar").prop('disabled',false);
                        //toastrErrorMessage(data.error);
                        
                    } else {
                        if (data.res == 'success'){
                            $("#mensaje").html('<div class="success">'+data.success+'</div>');
                            $("#mensaje").removeClass('hidden');
                            //toastrSuccessMessage(data.success)
                        }else{
                            $("#mensaje").html('<div class="success">'+data.success+'</div>');
                            $("#mensaje").removeClass('hidden');
                            
                            //toastrWarningMessage(data.success)
                        }                        
                        if (data.html) {
                            $('#form_view').css("display", "none");                            
                            $('#update_confirm').val('1');
                            $('#form_result').html(data.html);
                        }
                    }
                    swal.close();
                }
            });           
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
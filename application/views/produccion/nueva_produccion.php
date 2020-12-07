<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.css?v2.2.0">
<div class="page-header">    
    <div class="icon">
        <img alt="producción" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_produccion']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Producción", "Producción");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_product', "Nuevo producción"); ?></h2>     
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open_multipart("produccion/save_produccion", array("id" => "f_save_produccion","class" => "form-inline")); ?>
            <div class="form-group">
                <label for="txt_fecha">Fecha</label>
                <input type="fecha" name="fecha" class="form-control" id="txt_fecha" placeholder="Fecha">
            </div>
            <div class="form-group">
                <label for="slt_almacen">Almacén</label>
                <?= form_dropdown('almacen_id', $array_almacenes, !empty($produccion['almacen_id']),'class="form-control"' ) ?>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <div class="clearfix"></div>
            <br>
            <div class="row next_form" style="display: none">
                <div class="col-md-3">
                    <div class="form-group">
                        <span>En Producción</span>
                        <?= form_dropdown('producto_id', $array_compuestos,'','id="producto_id"') ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <span>Producto Terminado</span>
                        <?= form_dropdown('producto_final_id', $array_final, '', 'id="producto_final_id"') ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <span>Cantidad</span>
                        <input type="text" name="cantidad" id="cantidad" value="" />
                    </div>
                </div>
                <div class="col-md-3" style="padding:2%;">
                    <a class="btn-sm green" onclick="add_product_produccion()" title="Agregar"><li class='icon' style="font-size: 0px;cursor: pointer;"><span class='ico-plus icon-white' style="color: #fff;"></span></li></a>
                    <!--<a class="button add green" onclick="add_product_produccion()" data-tooltip="Agregar" ><div class='icon'><img alt="agregar" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['mas_blanco']['original'] ?>" ></div></a>-->
                </div>
                <input type="hidden" name="produccion_id"     id="produccion_id"     value="<?= $produccion_id ?>" />
                <input type="hidden" name="produccion_estado" id="produccion_estado" value="<?= !empty($produccion['estado']) ? $produccion['estado'] : ''; ?>" />    
            </div>
            <div class="row-fluid" id="info_table">
    
            </div>
    </form>
</div>




<script type="text/javascript">
    function add_product_produccion() {
        
        if($("#produccion_estado").val() == 'Confirmado'){
            toastrErrorMessage('La Producciòn ya se encuentra Confirmada');
            return false;
        }
        
        if($("#produccion_estado").val() == 'Trasladado'){
            toastrErrorMessage('La Producciòn ya se encuentra Trasladada');
            return false;
        }
        
        
        $.post('<?= base_url() ?>index.php/produccion/add_product_produccion/', {
            produccion_id: $('#f_save_produccion #produccion_id').val(),
            producto_id: $('#producto_id').val(),
            producto_final_id: $('#producto_final_id').val(),
            cantidad: $('#cantidad').val()
        },
            function (json) {
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    $(".btn-success").html("Guardar").removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    getProduccion( $('#produccion_id').val(), $('#produccion_estado').val());
                }
            }, 'json'
        );
    }
    function confirm_produccion( btn ){
        if($("input[name^=produccion_detalle_id]").length == 0){
            toastrErrorMessage('No hay productos relacionados a la Producción para confirmarla');
            return false;
        }
        btn.attr('disabled', 'disabled');
        $.ajax({
            url:  $("#f_save_produccion").attr("action").replace('save_produccion', 'confirm_produccion'),
            type: $("#f_save_produccion").attr("method"),
            data: $("#f_save_produccion").serialize(),
            success: function (json) {
                if (json.res == 'error') {
                    toastrErrorMessage(json.error);
                    btn.removeAttr('disabled');
                } else {
                    toastrSuccessMessage(json.success);
                    location.href="<?php echo base_url() ?>index.php/produccion";
                    //$("#presupuestosTable").dataTable().fnReloadAjax();
                    //$("#examplePositionBottom").modal('hide');
                }
            }
        });
    }
    function getProduccion(produccion_id, estado){
        $.ajax({
            type: "post",
            url: '<?= base_url() ?>index.php/produccion/getProduccion/',
            data: {produccion_id: produccion_id}, 
            beforeSend: function(){
                $("#info_table").html('<b>Consultando por favor espere..</b>');
            },
            success: function(data){
               $('#info_table').html(data);    
            }

        }).done(function(){
            setTimeout(function(){
               // console.log( $("#save") );
                if(estado != 'Creado'){
                    if(estado == 'Confirmado'){
                        $("#btn-confirm").hide();
                        $("#btn-update").show().removeAttr('disabled');;
                    }else{
                        $("#btn-confirm").hide();
                        $("#btn-update").attr('disabled', 'disabled');
                    }
                   // $("#save").attr('disabled', 'disabled');
                }else{
                    $("#btn-confirm").removeAttr('disabled');
                    $("#btn-update").hide();
                }
            }, 100);
        });
    }

    function removeProduct(id,row){
        console.log(id);
        let url = "<?= site_url('produccion/removeProduct')?>";
        $.post(url,{
            id:id
        },function(json){
            if (json.res == 'error') {
                toastrErrorMessage(json.error);
            } else {
                toastrSuccessMessage(json.success);
                //$('#table-production').dataTable().fnDeleteRow(row);
                getProduccion( $('#produccion_id').val(), $('#produccion_estado').val());
            }
            
        })
        

    }   

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

    $(document).ready(function(){
        
        


        $("#txt_fecha").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        // Generamos la solicitud de orden de produccion
        $("#f_save_produccion").submit(function (e) {
            e.preventDefault();
            $(".btn-success").attr('disabled');
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                beforeSend: function(){
                    $("#div_mensaje_crear_produccion").html('<b>Por favor espere un momento...</b>');
                    $("#div_mensaje_crear_produccion").show();
                },
                success: function (json) {
                    $("#div_mensaje_crear_produccion").hide();  
                    if (json.res == 'error') {
                        toastrErrorMessage(json.error);
                        $(".btn-success").removeAttr('disabled');
                    } else {
                        toastrSuccessMessage(json.success);
                        $('#produccion_id').val(json.produccion_id);
                        $('#produccion_estado').val(json.estado);
                        $('.next_form').css('display','block');
                         $("#presupuestosTable").dataTable().fnReloadAjax();
                    }
                }
            });
        });

    });



</script>

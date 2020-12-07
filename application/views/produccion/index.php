<link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.css?v2.2.0">
<div class="page-header">    
    <div class="icon">
        <img alt="producción" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_produccion']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Producción", "Producción");?></h1>
</div>
<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">        
            <!--<a class="btn btn-success" href="<?php echo site_url("produccion/nuevo"); ?>">              
                Nueva Orden de Producción
            </a>-->
             <div class="col-md-6">
                <?php 
                    if($data['puedofacturar']==0){                        
                ?>
                <a href="<?php echo site_url("produccion/nuevo")?>" data-tooltip="Nueva Producción">                        
                    <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                     
                </a>      
                    <?php } ?>              
            </div>
            <?php
            $is_admin = $this->session->userdata('is_admin');
            $permisos = $this->session->userdata('permisos');
            ?>
             </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">                
                <h2><?php echo custom_lang('sima_all_quotes', "Listado de Órdenes de Producción"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="presupuestosTable">
                    <thead>
                        <tr>
                            <th width="10%"><?php echo custom_lang('sima_number', "Consecutivo"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_customer', "Usuario"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_total_price', "Almacén"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_date', "Estado"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="10%"><?php echo custom_lang('sima_number', "Consecutivo"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_customer', "Usuario"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_total_price', "Almacén"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_date', "Estado"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade styleModalVendty modal-top" id="examplePositionBottom" aria-hidden="true" aria-labelledby="examplePositionBottom"
     role="dialog" tabindex="-1">
    <div id="info_modal" class="modal-dialog modal-bottom">
    </div>
</div>


<!--video-->
    <div class="social">
		<ul>
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">        
        <div style="padding:48.81% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266948756?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>   
    </div>
 
<script type="text/javascript">
     
    $(document).ready(function () {
        $('#presupuestosTable').dataTable({
            "aaSorting": [[0, "desc"]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("produccion/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [5], "bSearchable": false,
                    "mRender": function (data, type, row) {
                        console.log(row[6]);
                        var buttons = "<div class='btnacciones'>";
                        if(row[6]==0){
                            buttons += '<a data-tooltip="Factura" href="#" onclick="view_modal( ' + data + ', \''+ row[4] +'\' )" data-target="#examplePositionBottom" data-toggle="modal" data-dismiss="modal" class="button default acciones" target="" title="Facturar" targe ><div class="icon"><img alt="Facturar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['facturar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>" ></div></a>';
                        }
                        buttons += '<a data-tooltip="Exportar" href="produccion/export_producion?id='+ row[5] + '" class="button default acciones" target="" title="exportar acciones" targe ><div class="icon"><img alt="exportar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['exportar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['exportar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['exportar']['original'] ?>" ></div></a>';

                        buttons += '<a data-tooltip="Imprimir" target="_blank" href="produccion/imprimir_prod?id='+ row[5] + '" class="button default acciones" target="" title="Imprimir" targe ><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                        if(row[4] == 'Creado' || row[4] == 'Confirmado'){
                            buttons += '<a data-tooltip="Eliminar" target="_blank" onclick="delete_order_production('+ row[5] + ')" class="button default acciones" target="" title="Eliminar" targe ><div class="icon"><img alt="eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';
                        }
                       
                        buttons += "</div>";
                        return buttons;
                    }
                }
            ]
        });

        

    });

    function delete_order_production(id){
        
        swal({
            title: 'Estás seguro?',
            text: "Se eliminara la orden de producción por completo!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4cae4c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        url: "<?php echo site_url('produccion/delete')?>",
                        data: { id: id },
                        type: "POST",
                        success: function(data) {	
                            var response = JSON.parse(data);
                            switch(response.message){
                                case 'success':
                                    swal(
                                    'Redirigiendo!',
                                    'La orden de producción fue eliminada con exito',
                                    'success'
                                    );
                                    setTimeout(function(){
                                        location.href = "<?php echo site_url('produccion/');?>";
                                    }, 2000); 
                                 break;
                                 case 'error':
                                    swal(
                                    'Error inesperado!',
                                    'vuelve a intentarlo en un momento',
                                    'error'
                                    );
                                 break;
                            }    		
                        }
                    });
                }
        })
    }

    function view_modal(produccion_id, estado) {

        $.ajax({
            type: "post",
            url: '<?= base_url() ?>index.php/produccion/view_modal/',
            data: {produccion_id: produccion_id}, 
            beforeSend: function(){
                $("#info_modal").html('<b>Consultando por favor espere..</b>');
            },
            success: function(data){
               $('#info_modal').html(data);    
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
        /*$.post('<?= base_url() ?>index.php/produccion/view_modal/', {
            produccion_id: produccion_id
        },
        function (json) {
            $('#info_modal').html(json);
        }, 'json').done(function() {
            setTimeout(function(){
                console.log( $("#save") );
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
        });*/
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

</script>
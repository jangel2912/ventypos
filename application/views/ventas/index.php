<style>
    .alert-danger{
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="ventas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_venta']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Ventas");?></h1>
</div>

    <div class="row-fluid">    
        <div class="col-md-12">
            <div class="block">
                <?php

                    $message = $this->session->flashdata('message');
                    $error = $this->session->flashdata('error');

                    if(!empty($message)):?>

                    <div class="alert alert-success">

                        <?php echo $message;?>

                    </div>

                <?php endif;

                if(!empty($error)):?>

                    <div class="alert alert-danger">

                        <?php echo $error;?>

                    </div>

                    <?php endif; ?>
                <?php

                    $message1 = $this->session->flashdata('message1');

                    if(!empty($message1)):?>

                    <div class="alert alert-error">

                        <?php echo $message1;?>

                    </div>

                <?php endif; ?>
                <div class="col-md-6">
                <?php 
                    $permisos = $this->session->userdata('permisos');

                    $is_admin = $this->session->userdata('is_admin');

                    $base_dato = $this->session->userdata('base_dato');


                        if(in_array("11", $permisos) || $is_admin == 't'):?>
                   
                        <a href="<?php echo site_url("ventas/nuevo")?>" data-tooltip="Nueva Venta">                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 
                            <!--<?php echo custom_lang('sima_new_bill', "Nueva venta");?>-->
                        </a>                    
                    
                <?php endif;?>
                </div>
                <div class="col-md-6 btnizquierda">

                    <?php if(in_array("66", $permisos) || $is_admin == 't'): ?>
                        <!--<a href="<?php echo site_url("ventas/index/-1")?>" class="btn default"><?php echo custom_lang('sima_new_bill', "Ventas anuladas");?></a>-->
                    <div class="col-md-2 col-md-offset-8">
                        <a href="<?php echo site_url("ventas/index/-1")?>" data-tooltip="Ventas Anuladas">                            
                            <img alt="ventas anuladas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ventas_anuladas']['original'] ?>">                                                           
                        </a>
                    </div>
                    <?php endif;?>

                    <?php if(($data['plan_separe']) || $is_admin == 't'): ?>
                        <!--<a href="<?php echo site_url("devoluciones/index"); ?>" class="btn default">Devoluciones</a>-->
                    <div class="col-md-2">
                        <a href="<?php echo site_url("devoluciones/index")?>" data-tooltip="Devoluciones">                       
                            <img alt="devoluciones" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['devolver_verde']['original'] ?>">                                                     
                        </a>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">
                <!--
                <div class="icon">
                    <img title="ventas" alt="ventas" class="iconimg" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-06.svg">        
                </div>-->
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de ventas");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">
                        <thead>
                            <tr>
                                <th width="7%"><?php echo custom_lang('sima_number', "Factura");?></th>

                                <th width="6%"><?php echo custom_lang('sima_number', "Electrónica");?></th>

                                <th width="7%"><?php echo custom_lang('sima_customer', "Identificación");?></th>

                                <th width="15%"><?php echo custom_lang('sima_total_price', "Cliente");?></th>

                                <th width="15%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Almacén");?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Usuario");?></th>

                                <th width="20%" ><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th width="7%"><?php echo custom_lang('sima_number', "Factura");?></th>

                                <th width="6%"><?php echo custom_lang('sima_number', "Electrónica");?></th>

                                <th width="7%"><?php echo custom_lang('sima_customer', "Identificación");?></th>

                                <th width="15%"><?php echo custom_lang('sima_total_price', "Cliente");?></th>

                                <th width="15%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Almacén");?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Usuario");?></th>                            

                                <th width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
                    </div>
            </div>

    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-anular-form" aria-labelledby="ui-id-1" style="display: none; position: relative;"  title="Anular venta">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <button class="ui-dialog-titlebar-close"></button>
        </div>
        <div id="dialog-anular-form" class="ui-dialog-content ui-widget-content">
            <form id="motivo-form" action="<?php echo site_url('ventas/anular');?>" method="POST" >

                <input type="hidden" value="" name="venta_id" id="venta_id"/>

                <div class="row-form">
                    <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>
                    <div class="span3"><textarea name="motivo" id="motivo" required></textarea></div>                
                </div>
                <div class="row-form">
                    <div class="span2"></div>
                    <div class="span3" id="errores" class=""> </div>                
                </div>           
            </form>
        </div>     
    </div>

    <div class="social">
		<ul>
			<li>
                <a href="#myModalvideovimeo" data-toggle="modal">                    
                </a>
            </li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924204?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
   
    

<script type="text/javascript">
    var url_timbrado = '<?php echo site_url('factura_electronica/generar_timbrado') ?>';
    var url_descargar_xml = '<?php echo site_url('factura_electronica/descargar_xml') ?>';

    function openConnection() {
        // uses global 'conn' object
        if (conn.readyState === undefined || conn.readyState > 1) {
            conn = new WebSocket("ws://127.0.1.1:12500");
            conn.onopen = function() {
            conn.send("Connection Established Confirmation");
            };
            conn.onmessage = function(event) {
            //document.getElementById("content").innerHTML = event.data;
            };
            conn.onerror = function(event) {
            console.log("Web Socket Error");
            };

            conn.onclose = function(event) {
            console.log("Web Socket Closed");
            };
        }
    } 

    $(document).ready(function(){

        (conn = {}), (window.WebSocket = window.WebSocket || window.MozWebSocket);
        openConnection();
        //dialogo para envio por correo 
        var envioDialog = envioDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
            '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header text-center">'+
                '<?php echo custom_lang('sima_motivo_form', "Envío de correo electrónico");?></div>' +
                '<div class="modal-body">' +
                    '<form id="envio-form" action="<?php echo site_url('ventas/enviar_email/');?>" method="POST" >'+
                    '<input type="hidden" name="venta_id_ven" id="venta_id_ven"/>'+
                    '<div class="row-form">'+
                        '<div class="span5">Se le enviara el correo a: <span id="correo_cliente"></span>  </div>'+
                        '<div class="span5"><?php echo custom_lang('sima_motivo', "Escriba el mensaje que desea que vaya en el correo");?>:'+
                        '<textarea name="cuerpo_correo" id="cuerpo_correo" ></textarea></div>'+
                    '</div>'+
                    '<div align="center"> '+
                        '<input type="button" value="Cancelar"  id="cancelar" class="btn btn-default" data-dismiss="modal"/> '+
                        '<input type="submit" value="Enviar" id="enviocorreo"  class="btn btn-success"/>'+                        
                    '</div><br>'+
                '</form>'+
                '</div>' +
            '</div></div></div>');
            return {
                show:function(id){
                    $dialog.find("#venta_id_ven").val(id);

                    $.ajax({
                            async: false, //mostrar variables fuera de el function 
                            url: "<?php echo site_url("clientes/get_ajax_clientes_correo"); ?>",
                            type: "post",
                            dataType: "json",
                            data: {  idventa: id},
                            success: function(data2) {
                                $dialog.find("#correo_cliente").html(data2);  
                            }
                    });                                                             
                

                     $dialog.modal();
                },
                hide:function(){
                     $dialog.hide();
                }
            }
        })(jQuery);

        $('#ventasTable').dataTable( {

                "aaSorting": [[ 4, "desc" ]],

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("ventas/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 8 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {

                           var buttons = "<div class='btnacciones'>";

                            <?php if(in_array('57', $permisos) || $is_admin == 't'):?>
                                buttons += '<a "class=btnacciones" href="<?php echo site_url("ventas/imprimir/");?>/'+data+'/copia" id="'+data+'" data-id="'+data+'" class="button default btn-print acciones fast-print acciones"  data-tooltip="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>"></div></a>';
                                    
                            <?php endif;?>
                            <?php if(in_array('57', $permisos) || $is_admin == 't'):?>
                                buttons += '<a "class=btnacciones" href="<?php echo site_url("ventas/edit/");?>/'+data+'" id="edit_'+data+'" data-id="'+data+'" class="button default acciones"  data-tooltip="Editar Fecha"><div class="icon"><img alt="Editar fecha" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                    
                            <?php endif;?>
                            if(row[1] == 'Si') {
                            <?php if(in_array('57', $permisos) || $is_admin == 't'):?>
                                buttons += '<a "class=btnacciones" href="<?php echo site_url("ventas/nota/");?>/'+data+'" id="edit_'+data+'" data-id="'+data+'" class="button default acciones"  data-tooltip="Nota de credito electronica"><div class="icon"><img alt="Nota credito" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>"></div></a>';
                                    
                            <?php endif;?>
                            }

                            <?php if(in_array('12', $permisos) || $is_admin == 't'):?>
                                if(row[6]=='clasico')
                                buttons += '<a data-tooltip="Editar" href="<?php echo site_url("ventas/actualizar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if(in_array('59', $permisos) || $is_admin == 't'):?>
                                buttons += '<a data-tooltip="Enviar por correo" id="'+data+'" class="button default envio acciones"><div class="icon"><img alt="enviar correo" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if(in_array('13', $permisos) || $is_admin == 't'):?>
                                buttons += '<a  href="<?php echo site_url("ventas/guia_despacho/");?>/'+data+'"  class="button default btn-print acciones" data-tooltip="Guía de Despacho"><div class="icon"><img alt="Despacho" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['guiadespacho']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['guiadespacho']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['guiadespacho']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if($codigo_de_barras): ?>
                                //buttons += '<a href="<?php echo site_url("productos/codigo_print_factura") ?>/'+data+'/ventas" class="button default btn-print acciones" data-tooltip="Imprimir Codigo de barras"><div class="icon"><img alt="Código barra" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if(in_array('1025', $permisos) || $is_admin == 't'):?>
                                buttons += '<a href="#" id="'+data+'" class="button default devolver acciones" data-tooltip="Devolver"><div class="icon"><img alt="devolver" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['devolver']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['devolver']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['devolver']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if(in_array('13', $permisos) || $is_admin == 't'):?>
                                buttons += '<a href="#" id="'+data+'" class="button red anular acciones" data-tooltip="Anular"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if($this->session->userdata('pais_idioma') == 103 && $is_admin == 't'):?>
                                buttons += '<a href="#" id="'+data+'" class="button default timbrado acciones" data-tooltip="timbrar" onclick="timbrado_factura('+data+')"><div class="icon"><img alt="timbrar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['notificaciones']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['notificaciones']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['notificaciones']['original'] ?>"></div></a>';
                            <?php endif;?>

                            <?php if($this->session->userdata('pais_idioma') == 103 && $is_admin == 't'):?>
                                buttons += '<a href="#" id="timbrado_descargar_'+data+'" style="display:in-block" class="button red timbrado-descargar acciones" data-tooltip="Descargar XML" onclick="descargar_xml('+data+')"><div class="icon"><img alt="Descargar XML" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['descargar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['descargar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['descargar']['original'] ?>"></div></a>';
                            <?php endif;?>
                                                                     
                            buttons += "</div>";
                            return buttons;

                        }

                    }

                ]

        });

        $('body').on('click','.fast-print',function(e){
            e.preventDefault();
            $this = $(this);            
            id=$(this).attr('id');
            
            if(id!=""){
                $.ajax({
                    url: "<?php echo site_url("ventas/impresion_rapida")?>",
                    data: {id: id},
                    type: "post",
                    dataType:"json",
                    success: function(result){
                        console.log(result);
                        conn.send(JSON.stringify(result));
                    }
                });
            }
        });      

        $('.btn-print').fancybox({
            'width' : '85%',
            'height' : '85%',
            'autoScale' : false,
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'type' : 'iframe'
        });

        /****nuevo */
        $( "#dialog-anular-form" ).dialog({
            autoOpen: false,
            width: 400,
            draggable: false,
            modal: true,
            buttons: [ {
                id: "dialogSave",
                text: "Aceptar",
                click: function() { 
                    motivo=$("#motivo").val().trim();   
                            
                    if(motivo!="")
                    {        
                        $("#errores").html("");
                        $("#dialogSave").button("option", "disabled", true);
                        $("#motivo-form").submit();
                    }
                    else{       
                        $("#errores").html("");
                        $("#errores").css("color", "red");                
                        $("#errores").html("<b>El campo motivo no puede estar vacío</b>");
                    }
                }
            },
            {
                id: "dialogCancel",
                text: "Cancelar",
                click: function() { 
                    $("#dialogSave").button("option", "disabled", false);
                    $("#errores").html("");
                    $(this).dialog("close"); 
                }
            }],               
            close: function() {
                $("#dialogSave").button("option", "disabled", false);  
                $("#errores").html("");
                $('#motivo').val("");
                $('#venta_id').val("");
            }
        });
        
        $(document).on("submit", "#envio-form", function(e){            
            $("#enviocorreo").prop('disabled',true);
            var id='<?php echo $this->session->userdata('user_id') ?>';       
            var email='<?php echo $this->session->userdata('email') ?>';
            var nombre_empresa="<?php echo (!empty($data['datos_empresa'][0]->nombre_empresa))? $data['datos_empresa'][0]->nombre_empresa : 'No existe nombre' ?>";
            
            mixpanel.identify(id);

            mixpanel.track("Enviar factura por correo en historico de ventas", {
                "$email": email,
                "$empresa": nombre_empresa,
            });     
        });

        $('body').on('click','.anular',function(e){
            e.preventDefault();
            $this = $(this);            
            id=$(this).attr('id');
            
            if(id!=""){
                $("#venta_id").val(id);
                $.post
                (
                    "<?php echo site_url("devoluciones/facturaSindevolucion") ?>/"+id,
                    {},function(data)
                    {
                        if(data.resp == 1)
                        {                            
                            //waitingDialog.show($this.attr('id'));
                            $("#dialog-anular-form").dialog("open");
                        }else
                        {
                            alert(data.mensaje);
                        }
                    },'json'
                );
            }
        });
            
        $('body').on('click','.devolver',function(e){
            e.preventDefault();
            location.href = "<?php echo site_url("devoluciones/productos"); ?>/"+$(this).attr('id');
        });

 
        $('body').on('click','.envio',function(e){

            e.preventDefault();                                                   
            envioDialog.show($(this).attr('id'));
            //$( "#dialog-envio-form" ).dialog( "open" );

        });
           

        $( "#dialog-motivo-form" ).dialog({

            autoOpen: false,
            //height: 400,
            width: 620,
            modal: true,
            buttons: {

                "Aceptar": function() {

                                        if($("#motivo-form").length > 0)

                                        {

                                            $("#motivo-form").validationEngine('attach',{promptPosition : "topLeft"});

                                            if($("#client-form").validationEngine('validate')){

                                                $("#motivo-form").submit();

                                            }

                                        }

                },

                "Cancelar": function() {

                    $( this ).dialog( "close" );

                }

            },

            close: function() {

                            $('#razon_social').val("");
                            $('#nif_cif').val("");
                            $('#email').val("");
                            $('#nombre_comercial').val("");

            }

        });



        $( "#dialog-envio-form" ).dialog({

            autoOpen: false,
            //height: 400,
            width: 620,
            modal: true,
            buttons: {
                "Enviar Correo": function() {
                     $("#envio-form").submit();
                },
                "Cancelar": function() {
                    $( this ).dialog( "close" );
                }
            },

            close: function() {
                            $('#razon_social').val("");
                            $('#nif_cif').val("");
                            $('#email').val("");
                            $('#nombre_comercial').val("");
            }

        });

    });

    function timbrado_factura(id_factura){
        var confirmacion = confirm('Esta seguro de timbrar la factura ');
        if(confirmacion){
            enviar_peticion_timbrado(id_factura);
        }
    }

    function enviar_peticion_timbrado(id_factura){
        $.ajax({
            url: url_timbrado,
            type: "post",
            data: { factura:id_factura },
            dataType:"json",
            success: function(result){
                    respuesta = result.respuesta;
                   setTimeout(function(result){ alert(respuesta);$("#timbrado_descargar_"+id_factura).show(); },3000); 
            }
        });
    }

    function descargar_xml(id_factura){
        $.ajax({
            type: "post",
            url: url_descargar_xml,
            data: { factura:id_factura },
            dataType:"json",
            success: function(result){
                console.log(result);
                window.location.href=result.url_archivo;
            }

        })
    }
</script>
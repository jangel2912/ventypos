<div class="page-header">    
    <div class="icon">
        <img alt="Cliente" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_cliente']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cliente", "Cliente");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
                <style>
                    .alert-danger {
                        color: #a94442 !important;
                        background-color: #f2dede !important;;
                        border-color: #ebccd1 !important;
                    }
                </style>
            <?php
            $message = $this->session->flashdata('message');      
            if(!empty($message)){                
                if (($message == 'Se ha eliminado correctamente')||($message == 'Cliente creado correctamente') ||($message == 'Cliente actualizado correctamente')) {
                    echo'<div class="alert alert-success">'.$message.' </div>';
                }
                else{                
                    echo'<div class="alert alert-danger">'.$message.' asociadas</div>';
                }  
            }  ?>  
            <div class="col-md-6">
                <?php
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                
                if (in_array("34", $permisos) || in_array("39", $permisos) || $is_admin == 't'): ?>
                    <!--
                    <a href="<?php echo site_url("clientes/nuevo") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_client', "Nuevo cliente"); ?> </a>
                    <a href="#" class="btn btn-success" id="add-new-client"><?php echo custom_lang('sima_new_client_fast', "Nuevo cliente(Rápido)"); ?> </a>-->
                <div class="col-md-2">
                    <a href="<?php echo site_url("clientes/nuevo")?>" data-tooltip="Nuevo Cliente">                        
                        <img alt="nuevo cliente" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
                    </a>                    
                </div>
                <div class="col-md-2">
                    <a href="#" data-tooltip="Nuevo Cliente (Rápido)" id="add-new-client">                        
                        <img alt="nuevo cliente" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['cliente_rapido_verde']['original'] ?>">                         
                    </a>                    
                </div>     
                <?php endif; ?>
            </div>
            <div class="col-md-6 btnizquierda">                
                <div class="col-md-2 col-md-offset-6">
                    <a href="<?php echo site_url("clientes/grupos")?>" data-tooltip="Grupo de Clientes">                            
                        <img alt="Grupo de Clientes" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['grupo_cliente_verde']['original'] ?>">                                                           
                    </a>
                </div>               
                <div class="col-md-2">
                    <a href="<?php echo site_url("clientes/excel")?>" data-tooltip="Exportar a Excel">                       
                        <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                     
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo site_url("clientes/importar_excel_nuevo")?>" data-tooltip="Importar a Excel">                       
                        <img alt="Importar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>">                                                     
                    </a>
                </div>
            </div>
            <!--
            <div style="float: right">
                <a href="<?php echo site_url("clientes/grupos") ?>" class="btn default"><?php echo custom_lang('sima_client_group', "Grupo de clientes"); ?></a>

                <a href="<?php echo site_url("clientes/excel"); ?>" class="btn default"><?php echo custom_lang('sima_export', "Exportar a Excel"); ?></a>

                <a href="<?php echo site_url("clientes/importar_excel_nuevo"); ?>" class="btn default"><?php echo custom_lang('sima_import', "Importar excel"); ?></a>
            </div>-->
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">        
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_client', "Listado de Clientes"); ?></h2>
            </div>

            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="clientesTable">
                    <thead>
                        <tr>
                            <th width="15%"><?php echo custom_lang('sima_name_comercial', "Nombre"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_reason_social', "NIT/CC"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_telefono', "Teléfono"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_celular', "Celular"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_email', "Correo Electrónico"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_grupo', "Grupo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_tienda', "Tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th width="15%"><?php echo custom_lang('sima_name_comercial', "Nombre"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_reason_social', "NIT/CC"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_telefono', "Teléfono"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_celular', "Celular"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_email', "Correo Electrónico"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_grupo', "Grupo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_tienda', "Tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript">

    var oTable;

    $(document).ready(function () {
        var clienteDialog = clienteDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
                '<div id="dialog-client-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:5%; overflow-y:visible;">' +
                '<div class="modal-dialog modal-m">' +
                '<div class="modal-content">' +
                    '<div class="modal-header" style="padding:15px"><h4><?php echo custom_lang('sima_motivo_form', "Adicionar Cliente");?></h4></div>' +
                    '<div class="modal-body">' +
                    ' <form id="client-form">'+
                        '<div class="row-form">'+
                        '    <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre Comercial"); ?>:</div>'+
                        '    <div class="span3"><input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/></div>'+
                        '</div>'+
                        '<div class="row-form">'+
                        '    <div class="span2"><?php echo custom_lang('sima_email', "Correo Electrónico"); ?>:</div>'+
                        '   <div class="span3"><input type="text" name="email" id="email" class="validate[custom[email]]"/>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="row-form">'+
                        '    <div class="span2"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social"); ?>:</div>'+
                        '    <div class="span3"><input type="text" name="razon_social" id="razon_social" />'+
                        '    </div>'+
                        '</div>'+
                        '<div class="row-form">'+
                        '    <div class="span2"><?php echo custom_lang('sima_name_comercial', "Tipo de Identificaci&oacute;n"); ?>:</div>'+
                        '    <div class="span3">'+
                            '<select name="tipo_identificacion">'+
                                <?php foreach($data['tipo_identificacion'] as $ident){ ?>
                                    '<option value="<?php echo $ident; ?>"><?php echo $ident; ?></option>'+
                                <?php } ?>
                            '</select></div>'+                        
                        '</div>'+
                        '<div class="row-form">'+
                        '    <div class="span2"><?php echo custom_lang('sima_nif', "NIF/CIF"); ?>:</div>'+
                        '    <div class="span3"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]"/>'+
                        '    </div>'+
                        '</div>'+
                        '<div class="row-form error">'+
                        '</div>'+
                    '</form>'+
                    '</div>' +
                    '<div class="modal-footer">'+
                    '<div align="center"> '+
                            '<input type="button" value="Cancelar" data-dismiss="modal"  id="cancelar" class="btn btn-default"/> '+
                            '<input type="button" value="Continuar" id="guardar_cliente"  class="btn btn-success"/>'+
                        '</div><br></div>'+
                '</div></div></div>');
            return {
                show:function(id){
                    $dialog.find("#venta_id").val(id);                                              
                
                    $dialog.find("#guardar_cliente").on('click',function(){
                        if ($dialog.find("#client-form").validationEngine('validate')) {
                            $.ajax({
                                url: '<?php echo site_url('clientes/add_ajax_client'); ?>',
                                data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val(), tipo_identificacion: $('#tipo_identificacion').val()},
                                dataType: 'json',
                                type: 'POST',
                                success: function (data) {
                                    if (data.success) {
                                        //oTable.fnClearTable(); 
                                        $dialog.hide();                                       
                                        location.reload();
                                    } else {

                                        $('.error').html(data.msg);
                                        //alert(data.msg);
                                        //console.log(data);
                                    }
                                }

                            });
                        }
                    });
                    $dialog.modal();
                },
                hide:function(){
                        $dialog.hide();
                }
            }
        })(jQuery);

        $.fn.dataTable.ext.errMode = 'throw';

        oTable = $('#clientesTable').dataTable({
            
            "aaSorting": [[ 0, "desc" ]],

            "bProcessing": true,

            "bServerSide" : true,

            "sAjaxSource": "<?php echo site_url("clientes/get_ajax_data"); ?>",

            "sPaginationType": "full_numbers",

            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],

            "bInfo" : false,

            "aoColumnDefs": [

                {"bSortable": false, "aTargets": [7], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var buttons = "<div class='btnacciones'>";

                            <?php if (in_array('33', $permisos) || in_array('38', $permisos) || $is_admin == 't'): ?>

                            buttons += '<a href="<?php echo site_url("clientes/editar/"); ?>/' + data + '" class="button default acciones" data-tooltip="Editar Cliente"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                            <?php endif; ?>


                            <?php if (in_array('33', $permisos) || in_array('38', $permisos) || $is_admin == 't'): ?>

                            buttons += '<a href="mailto:' + row[4] + '" class="button default acciones" data-tooltip="Enviar Correo"><div class="icon"><img alt="enviar correo" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>"></div></a>';

                            <?php endif; ?>

                            <?php if (in_array('35', $permisos) || in_array('40', $permisos) || $is_admin == 't'): ?>
                            if (data != "-1")
                                buttons += '<a href="<?php echo site_url("clientes/eliminar/"); ?>/' + data + '" data-tooltip="Eliminar Cliente" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';

                            <?php endif; ?>

                        buttons += "</div>";
                        return buttons;

                    }

                }

            ]

        });



        $("#dialog-client-form").dialog({

            autoOpen: false,

            //height: 400,

            width: 620,

            modal: true,

            buttons: {

                "Aceptar": function () {



                    if ($("#client-form").length > 0)

                    {

                        $("#client-form").validationEngine('attach', {promptPosition: "topLeft"});

                        if ($("#client-form").validationEngine('validate')) {

                            $.ajax({

                                url: '<?php echo site_url('clientes/add_ajax_client'); ?>',

                                data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val(), tipo_identificacion: $('#tipo_identificacion').val()},

                                dataType: 'json',

                                type: 'POST',

                                success: function (data) {
                                    if (data.success) {
                                        oTable.fnClearTable();
                                        $("#dialog-client-form").dialog("close");
                                    } else {

                                        $('#nif_cif').parent().append(data.msg);
                                        //alert(data.msg);
                                        //console.log(data);
                                    }


                                }

                            });



                        }

                    }

                },

                "Cancelar": function () {

                    $(this).dialog("close");

                }

            },

            close: function () {

                $('#razon_social').val("");

                $('#nif_cif').val("");

                $('#email').val("");

                $('#nombre_comercial').val("");

            }

        });



        $("#add-new-client").click(function () {

            //$("#dialog-client-form").dialog("open");
            clienteDialog.show();

        });

    });

</script>
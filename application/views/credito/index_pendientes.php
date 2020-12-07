<div class="page-header">    
    <div class="icon">
        <img alt="ventas_creditos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_credito']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas a Credito", "Ventas a Crédito");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <?php
                $message1 = $this->session->flashdata('message1');
                if(!empty($message1)):?>
                <div class="alert alert-error">
                    <?php echo $message1;?>
                </div>
            <?php endif; ?>
            <?php
                $is_admin = $this->session->userdata('is_admin');
                $permisos = $this->session->userdata('permisos');
                if(in_array("21", $permisos) || $is_admin == 't'):?>
                   <!--<a href="<?php echo site_url("credito/index_pagadas")?>" class="btn btn-success"></small>Facturas Pagadas</a>-->
                   <div class="col-md-6">
                   </div>
                   <div class="col-md-6 btnizquierda">
                        <div class="col-md-3 col-md-offset-9 text-right">
                            <a href="<?php echo site_url("credito/index_pagadas")?>" data-tooltip="Listado de Ventas a Crédito Pagadas">                        
                                <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['credito_pagado_verde']['original'] ?>">                             
                            </a>   
                            <a data-tooltip="Exportar Excel" href="<?= site_url("credito/excel_data_informe") ?>">                        
                                <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                            </a>                  
                        </div>
                    </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">        
            <div class="head blue">              
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Ventas a Crédito Pendientes");?></h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="20%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="15%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Retenciones");?></th>
                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Saldo');?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="15%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="20%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="15%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Retenciones");?></th>
                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Saldo');?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="15%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="social">
		<ul>			
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">     
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266925040?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 
    </div>   

<script type="text/javascript">

    $(document).ready(function(){
        //Actualizacion Modal anular
        var anularDialog = anularDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
            '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;">'+
                '<h4 class="modal-title"><?php echo custom_lang('sima_motivo_form', "Motivo de la Anulación");?></h4>'+
                '</div>' +
                '<div class="modal-body">' +
                    '<form id="motivo-form" action="<?php echo site_url('ventas/anular');?>" method="POST" >'+
                        '<input type="hidden" value="" name="venta_id" id="venta_id"/>'+
                        '<div class="row-form">'+
                            '<div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>'+
                            '<div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>'+
                        '</div>'+
                        '<div align="center"> '+
                            '<input type="button" value="Cancelar" data-dismiss="modal" id="cancelar" class="btn btn-default"/> '+
                            '<input type="submit" value="Continuar"  class="btn btn-success"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '+
                        '</div><br>'+
                    '</form>'+                    
                '</div>' +
            '</div></div></div>');
            return {
                show:function(id){
                    //$dialog.find("#venta_id_ven").val(id);
                    $dialog.find("#venta_id").val(id);

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



            $('body').on('click','.anular',function(e){

                e.preventDefault();

                //$("#venta_id").val($(this).attr('id'));
                anularDialog.show($(this).attr('id'));
                //$( "#dialog-motivo-form" ).dialog( "open" );

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


        $('#facturasTable').dataTable( {

                "aaSorting": [[ 5, "desc" ]],
				
                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("credito/get_ajax_data_pendientes");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [             

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {

                            var buttons = "<div class='btnacciones'>";

                            <?php if(in_array('1001', $permisos) || $is_admin == 't'):?>

                                buttons += '<a data-tooltip="Ver detalle" href="<?php echo site_url("pagos/ver_pago/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';

                            <?php endif;?>


                            <?php if(in_array('1001', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("credito/imprimir/");?>/'+data+'/copia" class="button default btn-print acciones" data-tooltip="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';

                            <?php endif;?>


                             <?php if(in_array('13', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="#" id="'+data+'" class="button red anular acciones" data-tooltip="Anular"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';

                             <?php endif;?>
                             
                            buttons += "</div>";
                            return buttons;

                        }

                    }

                ]

        });

            $('.btn-print').fancybox({

                   'width' : '85%',

                   'height' : '85%',

                   'autoScale' : false,

                   'transitionIn' : 'none',

                   'transitionOut' : 'none',

                   'type' : 'iframe'

                 }

               );

    });
   
   mixpanel.track("Informe_cuentas_por_cobrar");  

</script>
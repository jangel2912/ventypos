<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
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
            
                <div class="col-md-6">
                    <?php $permisos = $this->session->userdata('permisos');

                    $is_admin = $this->session->userdata('is_admin');

                    if(in_array("11", $permisos) || $is_admin == 't'):?>

                    <!--<a href="<?php echo site_url("orden_compra/nuevo")?>" class="btn btn-success"> <?php echo custom_lang('sima_new_bill', "Nueva orden de compra");?></a>-->
                   
                        <a href="<?php echo site_url("orden_compra/nuevo")?>" data-tooltip="Nueva Orden de Compra">                        
                            <img alt="Orden de Compra" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 
                        </a> 
                    <?php endif;?> 
                </div>  
                <div class="col-md-6 btnizquierda">
                    <?php if (in_array("70", $permisos) || $is_admin == 't'): ?>
                        <div class="col-md-2 col-md-offset-10">
                            <a href="<?php echo site_url("orden_compra/index/")?>" data-tooltip="Listado de órdenes de Compra">                            
                                <img alt="Orden de Compra" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ordenes_compra_verde']['original'] ?>">                                                           
                            </a>
                        </div>
                    <?php endif;?> 
                </div>
            <!--
             <div style="float: right">
                <?php if (in_array("70", $permisos) || $is_admin == 't'): ?>
                    <a href="<?php echo site_url("orden_compra/index") ?>" class="btn default">
                        <?php echo custom_lang('sima_new_bill', "Listado de Órdenes de Compra"); ?>
                    </a>
                <?php endif; ?>    
            </div>         -->           
        </div>
    </div>
</div>
<div class="row-fluid">        
    <div class="span12">
        <div class="block">
            <div class="head blue">

                <div class="icon"><i class="ico-files"></i></div>

                <h2><?php echo custom_lang('sima_outstanding_all', "Órdenes de Compras Anuladas");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">

                        <thead>

                            <tr> 

                                <th ><?php echo custom_lang('sima_number', "N°");?></th>

                                <th ><?php echo custom_lang('sima_customer', "Cédula");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th ><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th ><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>
                                <th><?php echo custom_lang('sima_action', "usuario");?></th>
                                <th><?php echo custom_lang('sima_action', "motivo");?></th>
                                <th><?php echo custom_lang('sima_action', "fecha_anulacion");?></th>

                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                        <tfoot>

                            <tr> 

                                <th><?php echo custom_lang('sima_number', "N°");?></th>

                                <th><?php echo custom_lang('sima_customer', "Cédula");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>
                                <th><?php echo custom_lang('sima_action', "usuario");?></th>
                                <th><?php echo custom_lang('sima_action', "motivo");?></th>
                                <th><?php echo custom_lang('sima_action', "fecha_anulacion");?></th>

                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            

        </div>

    </div>

    <div id="dialog-motivo-form" title="<?php echo custom_lang('sima_motivo_form', "Motivo de la Anulacion");?>">

            <div class="span6">

                <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                <form id="motivo-form" action="<?php echo site_url('orden_compra/anular');?>" method="POST" >

                    <input type="hidden" value="" name="venta_id" id="venta_id"/>

                    <div class="row-form">

                        <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>

                        <div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>

                    </div>

                </form>

            </div>

    </div>

<script type="text/javascript">

    $(document).ready(function(){

        //Modal Anular ordenes de compra
        var anularDialog = anularDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
                '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
                '<div class="modal-dialog modal-m">' +
                '<div class="modal-content">' +
                    '<div class="modal-header" style="padding:15px"><h4><?php echo custom_lang('sima_motivo_form', "Motivo de la Anulacion");?></h4></div>' +
                    '<div class="modal-body">' +
                        '<form id="motivo-form" action="<?php echo site_url('orden_compra/anular');?>" method="POST" >'+
                            '<input type="hidden" value="" name="venta_id" id="venta_id"/>'+
                            '<div class="row-form">'+
                            '    <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>'+
                            '    <div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>'+
                            '</div>'+
                            '<div align="center"> '+                                
                                '<input type="button" value="Cancelar" data-dismiss="modal"  id="cancelar" class="btn btn-default"/> '+
                                '<input type="submit" value="Continuar"  class="btn btn-success"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '+
                            '</div><br>'+
                        '</form>'+                
                        
                    '</div>' +
                '</div></div></div>');
            return {
                show:function(id){
                    $dialog.find("#venta_id").val(id);                                              
                

                        $dialog.modal();
                },
                hide:function(){
                        $dialog.hide();
                }
            }
        })(jQuery);


        $('#ventasTable').dataTable( {

                "aaSorting": [[ 8, "desc" ]],

                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("orden_compra/get_ajax_data_anuladas");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 9 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {
                            
                           var buttons = "";

                            <?php if(in_array('1002', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("orden_compra/imprimir/");?>/'+data+'" class="button default btn-print" data-tooltip="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>';

                            <?php endif;?>
                            
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

               

            $('body').on('click','.anular',function(e){

                e.preventDefault();

                //$("#venta_id").val($(this).attr('id'));

                //$( "#dialog-motivo-form" ).dialog( "open" );
                anularDialog.show($(this).attr('id'));


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

    });

</script>
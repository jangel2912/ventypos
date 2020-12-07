<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Orden de Compra", "Orden de Compra");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_outstanding', "Todas las Ordenes de Compras");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

            <?php $permisos = $this->session->userdata('permisos');

                    $is_admin = $this->session->userdata('is_admin');

                    if(in_array("11", $permisos) || $is_admin == 't'):?>

                    <a href="<?php echo site_url("orden_compra/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_bill', "Nueva orden de compra");?></a>

            <?php endif;?>

            <?php /*if(in_array("68", $permisos) || $is_admin == 't'): ?>

                    <a href="<?php echo site_url("ventas/index/-1")?>" class="btn"><small class="ico-sale icon-white"></small> <?php echo custom_lang('sima_new_bill', "Ventas anuladas");?></a>

            <?php endif;*/?>

            

            <div class="head blue">

                <div class="icon"><i class="ico-files"></i></div>

                <h2><?php echo custom_lang('sima_outstanding_all', "Todas las Ordenes de Compras");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">

                        <thead>

                            <tr> 

                                <th width="10%"><?php echo custom_lang('sima_number', "NO");?></th>

                                <th width="10%"><?php echo custom_lang('sima_customer', "Cedula");?></th>

                                <th width="20%"><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th width="15%"><?php echo custom_lang('sima_action', "Almacen");?></th>

                                <th width="5%"><?php echo custom_lang('sima_action', "Tipo");?></th>

                              <!--  <th  class="TAC"><?php echo custom_lang('sima_action', "Usuario");?></th> -->

                                <th  width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                        <tfoot>

                            <tr> 

                                <th><?php echo custom_lang('sima_number', "NO");?></th>

                                <th><?php echo custom_lang('sima_customer', "Cedula");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>

                                <th ><?php echo custom_lang('sima_action', "Tipo");?></th>

                              <!--  <th><?php echo custom_lang('sima_action', "Usuario");?></th> -->

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

        $('#ventasTable').dataTable( {

                "aaSorting": [[ 7, "asc" ]],

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("orden_compra/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                          
                           var buttons = "";

                            <?php if(in_array('1002', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("orden_compra/imprimir/");?>/'+data+'" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>';

                            <?php endif;?>

                            <?php if(in_array('1002', $permisos) || $is_admin == 't'):?>

                              /*    console.log(row[6]);*/
                                if(row[6]=='Orden de Compra') 
                                buttons += '<a title="Ver pagos" href="<?php echo site_url("orden_compra/pagos_servicio/");?>/'+data+'" class="button yellow"><div class="icon"><span class="ico-dollar"></span></div></a>';

                            <?php endif;?>
							
	                            <?php if(in_array('1003', $permisos) || $is_admin == 't'):?>

                              /*    console.log(row[6]);*/
                                if(row[6]=='Orden de Compra') 
                                buttons += '<a title="Afectar Inventario" href="<?php echo site_url("orden_compra/inventario/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-circle-arrow-right"></span></div></a>';

                            <?php endif;?>						

                             <?php if(in_array('1003', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="#" id="'+data+'" class="button red anular" title="Anular"><div class="icon"><span class="ico-remove"></span></div></a>';

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

                $("#venta_id").val($(this).attr('id'));

                $( "#dialog-motivo-form" ).dialog( "open" );

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
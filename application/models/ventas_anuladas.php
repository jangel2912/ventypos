<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Ventas", "Ventas Anuladas");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_outstanding', "Listado de ventas anuladas");?></h2>                                          

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

                    <a href="<?php echo site_url("ventas/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_bill', "Nueva venta");?></a>

                <?php endif;?>

            <?php if(in_array("10", $permisos) || $is_admin == 't'): ?>

                    <a href="<?php echo site_url("ventas/index")?>" class="btn"><small class="ico-sale icon-white"></small> <?php echo custom_lang('sima_new_bill', "Historico de ventas");?></a>

            <?php endif;?>

            

            <div class="head blue">

                <div class="icon"><i class="ico-files"></i></div>

                <h2><?php echo custom_lang('sima_outstanding_all', "Todas las ventas anuladas");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">

                        <thead>

                            <tr> 

                                <th width="5%"><?php echo custom_lang('sima_number', "Factura");?></th>

                                <th width="20%"><?php echo custom_lang('sima_customer', "Motivo de anulaci&oacute;n");?></th>

                                <th width="14%"><?php echo custom_lang('sima_total_price', "Cliente");?></th>

                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th width="15%"><?php echo custom_lang('sima_action', "Almacen");?></th>
								
                                <th  width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                        <tfoot>

                            <tr> 

                                <th><?php echo custom_lang('sima_number', "Factura");?></th>

                                <th><?php echo custom_lang('sima_customer', "Motivo de anulaci&oacute;n");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Cliente");?></th>

                                <th><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>

                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            

        </div>

    </div>

   <!-- <div id="dialog-motivo-form" title="<?php echo custom_lang('sima_motivo_form', "Motivo de la Anulacion");?>">

            <div class="span6">

                <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                <form id="motivo-form" action="<?php echo site_url('ventas/anular');?>" method="POST" >

                    <input type="hidden" value="" name="venta_id" id="venta_id"/>

                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>

                            <div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>

                        </div>

                        

                </form>

            </div>

        </div> -->

<script type="text/javascript">

    $(document).ready(function(){


       oTable = $('#facturasTable').dataTable( {

                "bProcessing": true,

                /*"bServerSide": true,*/

                "sAjaxSource": "<?php echo site_url("ventas/get_ajax_data_anuladas");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                            var buttons = "";

                               <?php if(in_array('57', $permisos) || $is_admin == 't'):?>

                                        buttons += '<a href="<?php echo site_url("ventas/imprimir/");?>/'+data+'" target="_blank" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>';

                                <?php endif;?>

                            return buttons; 

  
                        } 

                    }

                ]

        });

    });

</script>
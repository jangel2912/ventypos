<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang("Facturas", "Factura");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_list_bill_paid', "Listado de facturas pagadas");?></h2>                                          
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
                                if(in_array("22", $permisos)):?>
                   <a href="<?php echo site_url("facturas/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_bill', "Nueva factura");?></a>
            <?php endif;?>
            <a href="<?php echo site_url("facturas/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <a href="<?php echo site_url("facturas/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-files"></i></div>
                <h2><?php echo custom_lang('sima_all_bill_paid', "Todos las facturas pagadas");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="20%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                	<tr>
                	<tr><td colspan="1"></td><td><b><?php echo custom_lang('sima_total', "Total");?></b></td><td class="total_pe" style="font-size:13px"><b><?php echo $data['monto_total'];?></b></td><td colspan="2"></td></tr>
                        
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#facturasTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("facturas/get_ajax_data_pagadas");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                                var buttons = "";
                            <?php if(in_array('54', $permisos) || $is_admin == 't'):?>
                                buttons += '<a title="Imprimir" href="<?php echo site_url("facturas/imprimir/");?>/'+data+'" class="button blue"><div class="icon"><span class="ico-print"></span></div></a>';
                            <?php endif;?>
                            <?php if(in_array('55', $permisos) || $is_admin == 't'):?>
                                buttons += '<a title="Ver Pago" href="<?php echo site_url("pagos/ver_pago/");?>/'+data+'" class="button blue"><div class="icon"><span class="ico-money-bag"></span></div></a>';
                            <?php endif;?>    
                            <?php if(in_array('56', $permisos) || $is_admin == 't'):?>
                                buttons += '<a title="Enviar correo" href="<?php echo site_url("facturas/enviar_email/");?>/'+data+'" class="button blue"><div class="icon"><span class="ico-circle-arrow-right"></span></div></a>';
                            <?php endif;?>
                            <?php if(in_array('22', $permisos) || $is_admin == 't'):?>
                                buttons += '<a title="Editar" href="<?php echo site_url("facturas/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                             <?php endif;?> 
                             <?php if(in_array('20', $permisos) || $is_admin == 't'):?>
                                buttons += '<a title="Eliminar" href="<?php echo site_url("facturas/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';
                             <?php endif;?>
                            return buttons; 
                               /*var buttons = '<div class="btn-group"><button class="btn btn-medium dropdown-toggle" data-toggle="dropdown"><span class="ico-cog-2 icon-white">&nbsp;</span><span class="caret"></span></button><ul class="dropdown-menu">';
                                 buttons += '<li><a href="<?php echo site_url("facturas/editar/");?>/'+data+'"><span class="icon-pencil"></span>&nbsp;<?php echo custom_lang("sima_edit", "Editar") ?></a></li>';
                                 buttons += '<li><a href="<?php echo site_url("facturas/imprimir/");?>/'+data+'"><span class="ico-print"></span>&nbsp;<?php echo custom_lang("sima_print", "Imprimir") ?></a></li>';
                                 buttons += '<li><a href="<?php echo site_url("pagos/ver_pago/");?>/'+data+'"><span class="ico-money-bag"></span>&nbsp;<?php echo custom_lang('sima_see_payments', "Ver pagos");?></a></li>';
                                 buttons += '<li><a href="<?php echo site_url("facturas/enviar_email/");?>/'+data+'"><span class="ico-envelope"></span>&nbsp;<?php echo custom_lang('sima_send_mail', "Enviar por correo");?></a></li>';
                                 buttons += '<li class="divider"></li>';
                                 buttons += '<li><a href="<?php echo site_url("facturas/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}"><span class="icon-remove"></span>&nbsp;<?php echo custom_lang("sima_delete", "Eliminar") ?></a></li>';
                                 buttons += '</ul></div>';
                                return buttons;*/
                        } 
                    }
                ]
        });
    });
</script>
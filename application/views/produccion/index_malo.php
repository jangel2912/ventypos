<div class="page-header">

    <div class="icon">

        <span class="ico-money"></span>

    </div>

    <h1><?php echo custom_lang("Cotizaciones", "Cotizaci&oacute;n");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_list_quotes', "Listado de cotizaciones");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

                                <div id="message">

                                </div>
            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

            <?php 

                $is_admin = $this->session->userdata('is_admin');

                $permisos = $this->session->userdata('permisos');

                                if(in_array("28", $permisos) || $is_admin == 't'):?>

                    <a href="<?php echo site_url("presupuestos/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_quote', "Nueva cotizaci&oacute;n");?></a>

            <?php endif;?>

            

            <div class="head blue">

                <div class="icon"><i class="ico-money"></i></div>

                <h2><?php echo custom_lang('sima_all_quotes', "Todas las cotizaciones");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="presupuestosTable">

                        <thead>

                            <tr>

                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>

                                <th width="30%"><?php echo custom_lang('sima_customer', "Cliente");?></th>

                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio total");?></th>

                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>

                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                        <tfoot>

                            <tr>

                                <th><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>

                                <th><?php echo custom_lang('sima_customer', "Cliente");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Precio total");?></th>

                                <th><?php echo custom_lang('sima_date', "Fecha");?></th>

                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            

        </div>

    </div>

<script type="text/javascript">

    $(document).ready(function(){




        $('#presupuestosTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("presupuestos/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                            var buttons = "";

                            <?php if(in_array('29', $permisos) || $is_admin == 't'):?>

                                buttons += '<a title="Imprimir" href="<?php echo site_url("presupuestos/imprimir/");?>/'+data+'" class="button blue btn-print" target="_blank" title="Imprimir" targe ><div class="icon"><span class="ico-print"></span></div></a>'; 


                            <?php endif;?>  

                            <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a  title="Editar" href="<?php echo site_url("presupuestos/editar/");?>/'+data+'" class="button yellow"><div class="icon"><span class="ico-pencil"></span></div></a>';

                             <?php endif;?> 

                            <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a title="Enviar por correo" href="<?php echo site_url("presupuestos/enviar_email/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-circle-arrow-right"></span></div></a>';

                            <?php endif;?>
							
                             <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("presupuestos/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';

                             <?php endif;?>

                            <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                            buttons += '<a title="Cambiar a Factura" href="<?php echo site_url("presupuestos/convertir_factura/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea convertir la cotización No. P00000 a factura?");?>\')){return true;}else{return false;}" class="button blue"><div class="icon"><span class="ico-list-alt"></span></div></a>';
//                            buttons+=' <title="Cambiar a Factura" title="Cambiar a Factura" class="button blue"> <div class="icon" data-id=data ><span class="ico-list-alt"></span></div></a>';
                        <?php endif;?>

                            return buttons;



                            /* var buttons = '<div class="btn-group"><button class="btn btn-medium dropdown-toggle" data-toggle="dropdown"><span class="ico-cog-2 icon-white">&nbsp;</span><span class="caret"></span></button><ul class="dropdown-menu">';

                                 buttons += '<li><a href="<?php echo site_url("presupuestos/editar/");?>/'+data+'"><span class="icon-pencil"></span>&nbsp;<?php echo custom_lang("sima_edit", "Editar") ?></a></li>';

                                 buttons += '<li><a href="<?php echo site_url("presupuestos/imprimir/");?>/'+data+'"><span class="ico-print"></span>&nbsp;<?php echo custom_lang("sima_print", "Imprimir") ?></a></li>';

                                 buttons += '<li><a href="<?php echo site_url("presupuestos/enviar_email/");?>/'+data+'"><span class="ico-envelope"></span>&nbsp;<?php echo custom_lang('sima_send_mail', "Enviar por correo");?></a></li>';

                                 buttons += '<li class="divider"></li>';

                                 buttons += '<li><a href="<?php echo site_url("presupuestos/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}"><span class="icon-remove"></span>&nbsp;<?php echo custom_lang("sima_delete", "Eliminar") ?></a></li>';

                                 buttons += '</ul></div>';

                            return buttons;*/

                        } 

                    }

                ]

        });


    });

</script>
<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("mis_pagos", "Mis pagos");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('pagos_realizados', "Pagos realizados");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):
                    $message_type = $this->session->flashdata('message_type');
            ?>

                <div class="alert alert-<?php echo $message_type; ?>">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

            <a href="#" class="btn" id="btn_nueva_licencia"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_pago', "Nuevo Pago");?></a>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="tb_mis_licencias">
                        <thead>
                            <tr>

                                <th width="30%"><?php echo custom_lang('sima_pan', "Fecha pago");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_inicio', "Monto pagado");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_fin', "Estado pago");?></th>
                                <th width="10%"><?php echo custom_lang('observaciones_pago', "Observaciones pago");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>
                        <?php foreach ($pagos as $key => $value) { 
                            $estado = '';
                            switch ($value->estado_pago) {
                                case 1:
                                    $estado = 'pendiente confirmacion';
                                    break;
                                case 2:
                                    $estado = 'Aceptado';
                                    break;
                                case 3:
                                    $estado = 'Rechazado';
                                    break;    
                                default:
                                    
                                    break;
                            }
                            ?>
                               <tr>
                                   <td><?php echo $value->fecha_pago  ?></td>
                                   <td><?php echo $value->monto_pago  ?></td>
                                   <td><?php echo $estado  ?></td>
                                   <td><?php echo $value->observacion_pago ?></td>
                                   <td></td>
                               </tr> 
                        <?php } ?>
                                                    

                        </tbody>

                        <tfoot>

                            <tr>

                                <th width="30%"><?php echo custom_lang('sima_pan', "Fecha pago");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_inicio', "Monto pagado");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_fin', "Estado pago");?></th>
                                <th width="10%"><?php echo custom_lang('dias_renovacion', "Observaciones ");?></th>
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

/*        $('#tb_mis_licencias').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("administracion_vendty/administracion_clientes/get_ajax_data_licencias");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                      { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {      

                            var buttons = '<a href="<?php echo site_url("almacenes/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';

                                buttons += '<a href="<?php echo site_url("almacenes/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';

        

                            return buttons;

                        } 

                    }

                ]

        });*/

        $("#tb_mis_licencias").dataTable();

    });

</script>
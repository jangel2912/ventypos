<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("facturas_pendientes_pago", "Facturas pendientes de pago");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('listado_facturas_pendientes_pago', "Listado de facturas pendientes de pago");?></h2>                                        
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

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="tb_facturas_pendientes">

                        <thead>

                            <tr>

                                <th width="30%"><?php echo custom_lang('numero_factura', "#");?></th>

                                <th width="10%"><?php echo custom_lang('fecha_factura', "Fecha");?></th>

                                <th width="10%"><?php echo custom_lang('total_factura', "Total factura");?></th>

                                <th width="10%"><?php echo custom_lang('fecha_vencimiento', "Fecha de vencimiento");?></th>

                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>
                        <?php foreach ($facturas as $key => $value) { ?>
                               <tr>
                                   <td><?php echo $value->numero_factura  ?></td>
                                   <td><?php echo $value->fecha_factura  ?></td>
                                   <td><?php echo $value->total_factura  ?></td>
                                   <td><?php echo $value->fecha_vencimiento_factura  ?></td>                                   
                                   <td></td>
                               </tr> 
                        <?php } ?>
                                                    

                        </tbody>

                        <tfoot>

                            <tr>

                               <th width="30%"><?php echo custom_lang('numero_factura', "#");?></th>

                                <th width="10%"><?php echo custom_lang('fecha_factura', "Fecha");?></th>

                                <th width="10%"><?php echo custom_lang('total_factura', "Total factura");?></th>

                                <th width="10%"><?php echo custom_lang('fecha_vencimiento', "Fecha de vencimiento");?></th>

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

        $("#tb_facturas_pendientes").dataTable();

    });

</script>
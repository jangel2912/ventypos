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
                if (!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <?php 
                    $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                ?>
                <?php                   
                    if(in_array("11", $permisos) || $is_admin == 't'):?>
                    <div class="col-md-6">
                        <a href="<?php echo site_url("ventas/nuevo")?>" data-tooltip="Nueva Venta">                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a>                    
                    </div>
                <?php endif;?>

                <div class="col-md-6 btnderecha">
                    <?php if(in_array("10", $permisos) || $is_admin == 't'): ?>                      
                    <div class="col-md-2 col-md-offset-8">
                        <a href="<?php echo site_url("ventas/index")?>" data-tooltip="Histórico de Ventas">                            
                            <img alt="Histórico de Ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['venta_verde']['original'] ?>">                                                           
                        </a>
                    </div>
                    <?php endif;?>
                    <div class="col-md-2">
                        <a data-tooltip="Exportar a Excel" href="<?= site_url("ventas/excel_data_anuladas") ?>" >
                            <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">   
                        </a>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

<!--
<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php

            $message = $this->session->flashdata('message');

            if (!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message; ?>

                </div>

            <?php endif; ?>

            <?php $permisos = $this->session->userdata('permisos');

            $is_admin = $this->session->userdata('is_admin');

            if (in_array("11", $permisos) || $is_admin == 't'):?>

                <a href="<?php echo site_url("ventas/nuevo") ?>" class="btn btn-success">
                    <small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_bill', "Nueva venta"); ?>
                </a>

            <?php endif; ?>

            <div style="float: right">

                <?php if (in_array("10", $permisos) || $is_admin == 't'): ?>

                    <a href="<?php echo site_url("ventas/index") ?>" class="btn default">
                        <?php echo custom_lang('sima_new_bill', "Historico de ventas"); ?>
                    </a>

                <?php endif; ?>

                <a href="<?= site_url("ventas/excel_data_anuladas") ?>" class="btn default">
                    Exportar a excel</a>

            </div>
        </div>
    </div>
</div>-->
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">               
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Ventas Anuladas"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">
                    <thead>
                        <tr>

                            <th width="15%"><?php echo custom_lang('sima_number', "Fecha"); ?></th>

                            <th width="10%"><?php echo custom_lang('sima_number', "Usuario"); ?></th>

                            <th width="5%"><?php echo custom_lang('sima_number', "Factura"); ?></th>

                            <th width="20%"><?php echo custom_lang('sima_customer', "Motivo de anulaci&oacute;n"); ?></th>

                            <th width="14%"><?php echo custom_lang('sima_total_price', "Cliente"); ?></th>

                            <th width="15%"><?php echo custom_lang('sima_saldo', 'Fecha anulación'); ?></th>

                            <th width="10%"><?php echo custom_lang('sima_action', "Valor"); ?></th>

                            <th width="15%"><?php echo custom_lang('sima_action', "Almacén"); ?></th>

                            <th width="5%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th width="15%"><?php echo custom_lang('sima_number', "Fecha"); ?></th>

                            <th width="10%"><?php echo custom_lang('sima_number', "Usuario"); ?></th>

                            <th><?php echo custom_lang('sima_number', "Factura"); ?></th>

                            <th><?php echo custom_lang('sima_customer', "Motivo de anulaci&oacute;n"); ?></th>

                            <th><?php echo custom_lang('sima_total_price', "Cliente"); ?></th>

                            <th><?php echo custom_lang('sima_saldo', 'Fecha anulación'); ?></th>

                            <th><?php echo custom_lang('sima_action', "Valor"); ?></th>

                            <th><?php echo custom_lang('sima_action', "Almacén"); ?></th>

                            <th><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="social">
		<ul>			
			<li>
                <a href="#myModalvideovimeo" data-toggle="modal"> </a>
            </li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266923528?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 
    </div>      

<script type="text/javascript">

    $(document).ready(function () {
        oTable = $('#facturasTable').dataTable({
            //"ordering": false, /* Se comentó ya que no deja filtrar los resultados */ 
            "aaSorting": [[ 5, "desc" ]],
            "bProcessing": true,
            "bServerSide": true, /*Se colocó para que pueda descargar progresivamente*/
            "sAjaxSource": "<?php echo site_url("ventas/get_ajax_data_anuladas");?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],
            "aoColumnDefs": [
                {
                    "bSortable": false, "aTargets": [8], "bSearchable": false,
                    "mRender": function (data, type, row) {
                        var buttons = "";
                        <?php if(in_array('57', $permisos) || $is_admin == 't'):?>
                        buttons += '<a data-tooltip="Imprimir" href="<?php echo site_url("ventas/imprimir/");?>/' + data + '" target="_blank" class="button default btn-print acciones" title="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                        <?php endif;?>
                        return buttons;
                    }
                }
            ]
        });
    });

</script>
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
                $is_admin = $this->session->userdata('is_admin');
                $permisos = $this->session->userdata('permisos');
                if(in_array("21", $permisos) || $is_admin == 't'):?>
                   <!--<a href="<?php echo site_url("credito/index")?>" class="btn btn-success">Facturas de Credito Pendientes</a> -->
                
                <div class="col-md-6">
                </div>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a href="<?php echo site_url("credito/index")?>" data-tooltip="Listado de Ventas a Crédito Pendientes">                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['credito_pendiente_verde']['original'] ?>">                             
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
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Ventas a Crédito Pagadas");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="facturasTable">
                        <thead>
                            <tr> 
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="20%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_saldo', 'Saldo');?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr> 
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="20%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_saldo', 'Saldo');?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>  
            </div>         
        </div>
    </div>

<script type="text/javascript">

    $(document).ready(function(){

        $('#facturasTable').dataTable( {

                "aaSorting": [[ 4, "desc" ]],
				
                "bProcessing": true,
				
                "sAjaxSource": "<?php echo site_url("credito/get_ajax_data_pagadas");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [              

                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                            var buttons = "<div class='btnacciones'>";


                            <?php if(in_array('1001', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("credito/imprimir/");?>/'+data+'/copia" class="button default btn-print acciones" data-tooltip="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';

                            <?php endif;?>
							 

                            <?php if(in_array('1001', $permisos) || $is_admin == 't'):?>

                                buttons += '<a data-tooltip="Ver detalle" href="<?php echo site_url("pagos/ver_pago/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';

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

</script>
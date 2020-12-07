<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
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
            </div> 
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a data-tooltip="Exportar Excel" href="<?= site_url("orden_compra/excel_data_informe") ?>">                        
                        <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                    </a>
                </div>
            </div> 
            <!--<a href="<?= site_url("orden_compra/excel_data_informe") ?>" class="btn btn-success">Exportar a excel</a> -->
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Órdenes de Compras");?></h2>
            </div>
                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr> 
                                <th><?php echo custom_lang('sima_number', "N° Orden de compra");?></th>
                                <th ><?php echo custom_lang('sima_customer', "Fecha de pago");?></th>
                                <th ><?php echo custom_lang('sima_total_price', "Nit");?></th>
                                <th ><?php echo custom_lang('sima_saldo', 'Proveedor');?></th>
                                <th ><?php echo custom_lang('sima_saldo', 'Almacen');?></th>
                                <th ><?php echo custom_lang('sima_action', "Valor del Pago");?></th>
                                <th ><?php echo custom_lang('sima_action', "Valor del Impuesto");?></th>
                                <th><?php echo custom_lang('sima_action', "Valor de la Orden");?></th>                                
                                <th><?php echo custom_lang('sima_action', "Total a Pagar");?></th>  
                                <th><?php echo custom_lang('sima_action', "Fecha del Pedido");?></th> 
                                <th><?php echo custom_lang('sima_action', "Nota");?></th>                               
                            </tr>
                        </thead>
                        <tbody> </tbody>
                        <tfoot>
                            <tr> 
                                <th><?php echo custom_lang('sima_number', "N° Orden de compra");?></th>
                                <th ><?php echo custom_lang('sima_customer', "Fecha de pago");?></th>
                                <th ><?php echo custom_lang('sima_total_price', "Nit");?></th>
                                <th ><?php echo custom_lang('sima_saldo', 'Proveedor');?></th>
                                <th ><?php echo custom_lang('sima_saldo', 'Almacen');?></th>
                                <th ><?php echo custom_lang('sima_action', "Valor del Pago");?></th>
                                <th ><?php echo custom_lang('sima_action', "Valor del Impuesto");?></th>
                                <th><?php echo custom_lang('sima_action', "Valor de la Orden");?></th>                                
                                <th><?php echo custom_lang('sima_action', "Total a Pagar");?></th>  
                                <th><?php echo custom_lang('sima_action', "Fecha del Pedido");?></th> 
                                <th><?php echo custom_lang('sima_action', "Nota");?></th>                               
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

       oTable = $('#informesTable').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("orden_compra/get_ajax_data_informe");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
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
    mixpanel.track("informe_orden_compra"); 
</script>
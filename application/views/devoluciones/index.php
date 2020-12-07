<div class="page-header">    
    <div class="icon">
        <img alt="Devoluciones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_devoluciones']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Devoluciones", "Devoluciones");?></h1>
</div>


<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');                
                $message = $this->session->flashdata('message');

                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
            </div>
            <?php if (in_array("10", $permisos) || $is_admin == 't'): ?>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a href="<?php echo site_url("ventas/index")?>" data-tooltip="Histórico de Ventas">                            
                            <img alt="Histórico de Ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['venta_verde']['original'] ?>">                                                           
                        </a>
                    </div>          
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">            

            <div class="head blue">
                <h2><?php echo custom_lang('todas_devoluciones', "Listado de Devoluciones");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="devolucionesTable">
                        <thead>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_number', "Código");?></th>
                                <th width="15%"><?php echo custom_lang('sima_customer', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Factura");?></th>
                                <th width="15%"><?php echo custom_lang('sima_action', "Valor");?></th>
                                <th width="20%"><?php echo custom_lang('sima_action', "Cliente");?></th>
                                <th width="20%" ><?php echo custom_lang('sima_action', "Usuario");?></th>
                                <th  width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_number', "Código");?></th>
                                <th width="15%"><?php echo custom_lang('sima_customer', "Fecha");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Factura");?></th>
                                <th width="15%"><?php echo custom_lang('sima_action', "Valor");?></th>
                                <th width="20%"><?php echo custom_lang('sima_action', "Cliente");?></th>
                                <th width="20%" ><?php echo custom_lang('sima_action', "Usuario");?></th>
                                <th  width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    
        $(document).ready(function(){
		$('#devolucionesTable').dataTable( {
			"aaSorting": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "<?php echo site_url('devoluciones/get_ajax_data');?>",
			"sPaginationType": "full_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [5,10,25,50,100],
			"aoColumnDefs" : [
                            {
                                "bSortable": false,
                                "aTargets": [ 6 ],
                                "bSearchable": false,
                                "mRender": function (data, type, row) {
                                        var buttons = "<div class='btnacciones'>";
                                        buttons += '<a data-tooltip="Imprimir" target="_blank" href="<?php echo site_url("devoluciones/imprimir") ?>/'+data+'/ventas" class="button default btn-print acciones" title="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                                        buttons += "</div>";
                                        return buttons;
                                }
                            }
			]
                })
            });

            $('.btn-print').fancybox({
                'width' : '85%',
                'height' : '85%',
                'autoScale' : false,
                'transitionIn' : 'none',
                'transitionOut' : 'none',
                'type' : 'iframe'
            });

</script>
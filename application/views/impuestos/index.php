<div class="page-header">    
    <div class="icon">
        <img alt="Impuestos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_impuestos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Impuestos", "Impuestos");?></h1>
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

            <!--<a href="<?php echo site_url("impuestos/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_tax_new', "Nuevo impuesto");?></a>
            <a href="<?php echo site_url("impuestos/excel");?>" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <a href="<?php echo site_url("impuestos/import_excel");?>" class="btn btn-success"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>-->

            <div class="col-md-1">
                <a href="<?php echo site_url("impuestos/nuevo")?>" data-tooltip="Nuevo Impuesto">                        
                    <img alt="Impuesto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 
                </a>                    
            </div>
            <div class="col-md-1">
                <a href="<?php echo site_url("impuestos/excel")?>" data-tooltip="Exportar Excel">                        
                    <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                </a>                    
            </div>
            <div class="col-md-1">
                <a href="<?php echo site_url("impuestos/import_excel")?>" data-tooltip="Importar Excel">                        
                    <img alt="Importar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>"> 
                </a>                    
            </div>
            </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_tax', "Listado de Impuestos");?></h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="impuestosTable">

                        <thead>
                            <tr>
                                <th width="50%"><?php echo custom_lang('sima_name', "Nombre");?></th>                                
                                <th width="20%"><?php echo custom_lang('', "Predeterminado");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax_percent', "Porciento");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>  </tbody>
                        <tfoot>
                            <tr>
                                <th width="50%"><?php echo custom_lang('sima_name', "Nombre");?></th>                                
                                <th width="20%"><?php echo custom_lang('', "Predeterminado");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax_percent', "Porciento");?></th>
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

        $('#impuestosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("impuestos/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [
                    {
                        "bSortable": false, "aTargets": [ 1 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                            predeterminado = data == false ? 'NO' : 'SI';
                            return predeterminado;
                        }  
                    },
                    { 
                        "bSortable": false, "aTargets": [ 3 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                            var buttons = "";                              
                            var buttons = '<a href="<?php echo site_url("impuestos/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            return buttons;
                        }
                    }

                ]

        });

    });

</script>
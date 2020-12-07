<div class="page-header">    
    <div class="icon">
        <img alt="Bodegas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_bodegas']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Bodegas", "Bodegas");?></h1>
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

            <!--<a href="<?php echo site_url("bodegas/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_category', "Nueva bodega");?></a>-->
            <a href="<?php echo site_url("bodegas/nuevo")?>" data-tooltip="Nueva Bodega">                        
                <img alt="bodegas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                
            </a>  

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_category', "Listado de bodegas");?></h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">

                        <thead>
                            <tr>
                                <th width="30%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                                <th width="30%"><?php echo custom_lang('sima_codigo', "Direccion");?></th>
                                <th width="15%"><?php echo custom_lang('price_active', "Activo");?></th>
                                <th width="15%"><?php echo custom_lang('price_active', "Ciudad");?></th>                                
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>   </tbody>
                        <tfoot>
                            <tr>
                                <th width="30%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                                <th width="30%"><?php echo custom_lang('sima_codigo', "Direccion");?></th>
                                <th width="15%"><?php echo custom_lang('price_active', "Activo");?></th>
                                <th width="15%"><?php echo custom_lang('price_active', "Ciudad");?></th>                                
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

        $('#productosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("bodegas/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                        { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                            
                            var text = "Si";

                            if(data != "1"){

                                text = "No";
                            }    
                            return text;
                        } 

                    }

                    ,{ "bSortable": false, "aTargets": [ 4 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {                            
                                var buttons = "<div class='btnacciones'>";
                                buttons += '<a href="<?php echo site_url("bodegas/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                                buttons += '<a href="<?php echo site_url("bodegas/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                                buttons += "</div>"; 
                            return buttons;

                        } 

                    }

                ]

        });

    });

</script>
<?php 
    $titulo="Vendedores";
    if((isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante")){
        $titulo="Vendedores/Meseros";
    } 
    ?>
<div class="page-header">    
    <div class="icon">
        <img alt="Vendedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_vendedor']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Vendedores", $titulo);?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                $message1 = $this->session->flashdata('message1');
                if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>          
                <?php                 
            endif;
                if(!empty($message1)):?>
                    <div class="alert alert-error">
                        <?php echo $message1;?>
                    </div>          
                <?php endif; ?>
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                if(in_array("1004", $permisos) || $is_admin == 't'):?>
                    <!--<a href="<?php echo site_url("vendedores/nuevo/")?>" class="btn btn-success"> <?php echo custom_lang('sima_new_sales_man', "Nuevo vendedor");?></a>-->
                    <a href="<?php echo site_url("vendedores/nuevo")?>" data-tooltip="Nuevo <?= $titulo?>">                        
                        <img alt="Nuevo Vendedor" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
                    </a> 
                 <?php endif;?>

         <!--   <a href="#" class="btn" id="add-new-provider"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_provider_fast', "Nuevo proveedor(Rápido)");?></a>

             <a href="<?php echo site_url("proveedores/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>

            <a href="<?php echo site_url("proveedores/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>-->
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">        
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_provider', "Listado de ".$titulo);?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "ID");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="20%"><?php echo custom_lang('sima_nif', "Cédula");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Email");?></th>
                                <th width="10%"><?php echo custom_lang('sima_contact', "Teléfono");?></th>
                                <th width="10%"><?php echo custom_lang('sima_contact', "Estación Pedido");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody> 
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "ID");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="20%"><?php echo custom_lang('sima_nif', "Cédula");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Email");?></th>
                                <th width="10%"><?php echo custom_lang('sima_contact', "Teléfono");?></th>
                                <th width="10%"><?php echo custom_lang('sima_contact', "Estación Pedido");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

        </div>

    </div>

   <script type="text/javascript">

    var oTable;

    $(document).ready(function(){

       oTable = $('#proveedoresTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "aaSorting": [[ 0, "DESC" ]],
				
                "sAjaxSource": "<?php echo site_url("vendedores/get_ajax_data_estacion");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {
                            
                                var buttons =  "<div class='btnacciones'>";

                                 <?php if(in_array('1005', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Editar" href="<?php echo site_url("vendedores/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                                 <?php endif;?>

                                 <?php if(in_array('1006', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Eliminar" href="<?php echo site_url("vendedores/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';

                                 <?php endif;?>
                                buttons += "</div>";
                            return buttons;

                        }

                    },
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }

                ]

        });

    });

</script>
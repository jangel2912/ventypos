<div class="page-header">    
    <div class="icon">
        <img alt="Mesas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_mesas']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Mesas", "Mesas/Secciones");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
            $message = $this->session->flashdata('message');

            if (!empty($message)):
                ?>

                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php
            $permisos = $this->session->userdata('permisos');

            $is_admin = $this->session->userdata('is_admin');

            if (in_array("15", $permisos) || $is_admin == 't'):
                ?>
                <a href="<?php echo site_url("secciones_almacen/nuevo") ?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_category', "Nueva sección"); ?></a>
                <a href="<?php echo site_url("mesas_secciones/nuevo") ?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_category', "Nueva mesa"); ?></a>
                <div class="pull-right">
                    
                    <a href="<?php echo site_url("secciones_almacen/index") ?>" class="btn btn-default"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_admin_secciones', "Administrar secciones"); ?></a>
                </div>
            <?php endif; ?>

           <!-- <a href="<?php echo site_url("categorias/excel"); ?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel"); ?></a>
            <a href="<?php echo site_url("categorias/import_excel"); ?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel"); ?></a>-->

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_category', "Listado de las Mesas"); ?></h2>
            </div>

            <div class="data-fluid">
            
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="mesas_table">
                    <thead>
                        <tr>
                            <th width="20%"><?php echo custom_lang('almacen', "Almacén"); ?></th>
                            <th width="20%"><?php echo custom_lang('price_active', "Sección"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "C&oacute;digo mesa"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_name', "Mesa"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="20%"><?php echo custom_lang('almacen', "Almacén"); ?></th>
                            <th width="20%"><?php echo custom_lang('price_active', "Sección"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "C&oacute;digo mesa"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_name', "Mesa"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

 <div class="social">
		<ul>
			<!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
			<li><a href="#myModalvideovimeo" data-toggle="modal" id="modal-click-vimeo" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo https://player.vimeo.com/video/266923686?loop=1&color=ffffff&title=0&byline=0&portrait=0-->    
    <div id="myModalvideovimeo" class="modal fade">  
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266923811?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->

<script type="text/javascript">

    $(document).ready(function () {

        $('#mesas_table').dataTable({

            "bProcessing": true,

            "bServerSide": true,

            "sAjaxSource": "<?php echo site_url("mesas_secciones/get_ajax_data"); ?>",

            "sPaginationType": "full_numbers",

            "iDisplayLength": 10, "aLengthMenu": [5, 10, 25, 50, 100],

            "aoColumnDefs": [

                {"bSortable": false, "aTargets": [4], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var text = "Si";

                        if (data != "1") {
                            text = "No";
                        }

                        return text;
                    }

                }

                , {"bSortable": false, "aTargets": [5], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var buttons ="<div class='btnacciones'>";

                    <?php if (in_array('16', $permisos) || $is_admin == 't'): ?>
                           
                                buttons += '<a data-tooltip="Editar" href="<?php echo site_url("mesas_secciones/editar/"); ?>/' + data + '" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                
                            
                    <?php endif; ?>

                    <?php if (in_array('17', $permisos) || $is_admin == 't'): ?>

                                buttons += '<a data-tooltip="Eliminar" href="<?php echo site_url("mesas_secciones/eliminar/"); ?>/' + data + '" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            
                    <?php endif; ?>
                    buttons += "</div>";
                    return buttons;

                    }
                }
            ]
        });
    });

</script>
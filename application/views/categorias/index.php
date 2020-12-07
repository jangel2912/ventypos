<div class="page-header">    
    <div class="icon">
        <img alt="categorías" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_categorias']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("categorias", "Categorías");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
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
            <!--<a href="<?php echo site_url("categorias/nuevo") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_category', "Nueva categoria"); ?></a>-->
            <div class="col-md-6">
                <a href="<?php echo site_url("categorias/nuevo")?>" data-tooltip="Nueva Categoría">                        
                    <img alt="Nueva Categoría" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                    
                </a>                    
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row-fluid">        
    <div class="span12">
        <div class="block">

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_category', "Listado de Categorías"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                    <thead>
                        <tr>
                            <th width="10%"><?php echo custom_lang('sima_image', "Imagen"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Padre"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_en_tienda', "En tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_categoria_menu_tienda', "Menu principal de tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                    <tfoot>
                        <tr>
                            <th width="10%"><?php echo custom_lang('sima_image', "Imagen"); ?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Padre"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_en_tienda', "En tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('price_active', "Activo"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_categoria_menu_tienda', "Menu principal de tienda"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!--video-->
    <div class="social">
		<ul>		
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">           
        <div style="padding:48.81% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266935013?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
   
<script type="text/javascript">

    $(document).ready(function () {

        $('#productosTable').dataTable({

            "bProcessing": true,

            "bServerSide": true,

            "sAjaxSource": "<?php echo site_url("categorias/get_ajax_data"); ?>",

            "sPaginationType": "full_numbers",

            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],

            "aoColumnDefs": [

                {"bSortable": false, "aTargets": [0], "bSearchable": false,

                    "mRender": function (data, type, row) {

                     
                        image_name = 'default.png';

                        if (data != "") {

                            image_name = data;

                        }

                        return "<img class='img-polaroid' height='30px' width='30px' src='" + image_name + "'/>";

                    }

                },

                {"bSortable": false, "aTargets": [4,5,6], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var text = "Si";

                        if (data != "1") {

                            text = "No";

                        }

                        return text;

                    }

                }

                , {"bSortable": false, "aTargets": [7], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var buttons = "<div class='btnacciones'>";

<?php if (in_array('16', $permisos) || $is_admin == 't'): ?>

                            if ( (row[2] != "GiftCard" && data != 1) && row[2] != "General") {
                                buttons += '<a data-tooltip="Editar" href="<?php echo site_url("categorias/editar/"); ?>/' + data + '" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" ></div></a>';
                            }
                            //Boton de Activar-desactivar
                            else{
                                var status = (row[5] == '1') ? '1':'0';
                                var tooltip = (row[5] == '1') ? 'Desactivar':'Activar';
                                buttons += '<a data-tooltip="'+tooltip+'" href="<?php echo site_url("categorias/changeStatus"); ?>/'+status+'/activo/' + data + '" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['activar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['activar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['activar']['original'] ?>" ></div></a>';
                                if(row[2] == "General"){
                                    var status_g = (row[4] == '1') ? '1':'0';
                                    var tooltip_g = (row[4] == '1') ? 'Desactivar tienda':'Activar tienda';
                                    buttons += '<a data-tooltip="'+tooltip_g+'" href="<?php echo site_url("categorias/changeStatus"); ?>/'+status_g+'/tienda/' + data + '" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['tiendaStatus']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['tiendaStatus']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['tiendaStatus']['original'] ?>" ></div></a>';
                                }
                            }
<?php endif; ?>

<?php if (in_array('17', $permisos) || $is_admin == 't'): ?>

                            if ( (row[2] != "GiftCard" && data != 1) && row[2] != "General") {
                                buttons += '<a data-tooltip="Eliminar" href="<?php echo site_url("categorias/eliminar/"); ?>/' + data + '" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';
                            }

<?php endif; ?>
                        buttons += "</div>";
                        return buttons;

                    }

                }

            ]

        });

    });

</script>
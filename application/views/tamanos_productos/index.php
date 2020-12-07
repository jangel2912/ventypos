<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("tamanos_productos", "Tamaños productos"); ?><small><?php echo $this->config->item('site_title'); ?></small></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_tamano_productos_list', "Listado de tamaños para productos"); ?></h2>                                
    </div>

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
            if (in_array("1030", $permisos) || $is_admin == 't'):       ?>
                <a href="<?php echo site_url("tamanos_productos/agregar_view") ?>" class="btn"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_tamano', "Nuevo tamaño"); ?></a>
            <?php endif; ?>
            <div class="data-fluid">
            	<table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="tb_tamanos_productos">
            		<thead>
                        <tr> 
	                        <th><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
	                        <th><?php echo custom_lang('sima_name', "Nombre"); ?></th>
	                        <th><?php echo custom_lang('sima_descripcion', "Descripcion"); ?></th>
							<th><?php echo custom_lang('categoria', "Categoria"); ?></th>
							<th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    	
                    </tbody>
                    <tfoot>
                    		<th><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
	                        <th><?php echo custom_lang('sima_name', "Nombre"); ?></th>
	                        <th><?php echo custom_lang('sima_descripcion', "Descripcion"); ?></th>
							<th><?php echo custom_lang('categoria', "Categoria"); ?></th>
							<th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                    </tfoot>    
            	</table>
            </div>
        </div>
    </div>
</div>        
<script type="text/javascript">
	  $(document).ready(function () {

        $('#tb_tamanos_productos').dataTable({

            "bProcessing": true,

            "bServerSide": true,

            "sAjaxSource": "<?php echo site_url("tamanos_productos/get_ajax_data"); ?>",

            "sPaginationType": "full_numbers",

            "iDisplayLength": 10, "aLengthMenu": [5, 10, 25, 50, 100],

            "aoColumnDefs": [

                {"bSortable": false, "aTargets": [4], "bSearchable": false,

                    "mRender": function (data, type, row) {

                        var buttons = "";

                        <?php if (in_array('1031', $permisos) || $is_admin == 't'): ?>
                           
                                buttons += '<a href="<?php echo site_url("tamanos_productos/editar_view/"); ?>/' + data + '" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                            
                    	<?php endif; ?>

                    	<?php if (in_array('1032', $permisos) || $is_admin == 't'): ?>

                                buttons += '<a href="<?php echo site_url("tamanos_productos/eliminar/"); ?>/' + data + '" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';
                            
                    	<?php endif; ?>

                    return buttons;

                    }
                }
            ]
        });
    });
</script>
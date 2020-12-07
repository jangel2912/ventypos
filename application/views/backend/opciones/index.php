<div class="block title">
    <div class="head">
        <h2>Listado de nomencladores</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <a href="<?php echo site_url("backend/opciones/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> Nueva opcion</a>
            <div class="head blue">
                <div class="icon"><i class="ico-layout-9"></i></div>
                <h2>Todas las opciones</h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="sub_menuTable">
                        <thead>
                            <tr>
                                <th width="30%">Nombre de la opci&oacute;n</th>
                                <th width="30%">Valor a mostrar</th>
                                <th width="30%">Valor a real</th>
                                <th  class="TAC">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Nombre de la opci&oacute;n</th>
                                <th>Valor a mostrar</th>
                                <th>Valor a real</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#sub_menuTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("backend/opciones/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 3 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                            var buttons = '<a href="<?php echo site_url("backend/opciones/editar/");?>/'+data+'"class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>'; 
                                buttons += '<a href="<?php echo site_url("backend/opciones/eliminar/");?>/'+data+'"  onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red">';
                                buttons +=  '<div class="icon"><span class="ico-remove"></span></div></a>';    
                            return buttons;
                        } 
                    }
                ]
        });
    });
</script>
<div class="block title">
    <div class="head">
        <h2>Listado de usuarios</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php 
                //$message = $data['message']['msg'];
                            if(!empty($message)):?>
                            <div class="alert alert-success">
                                <?php echo $message;?>
                            </div>
                            <?php endif; ?>
             <a href="<?php echo site_url("/backend/usuarios/create_user")?>" class="btn"><small class="ico-plus icon-white"></small> Crear usuario</a>
            <div class="head blue">
                <div class="icon"><i class="ico-layout-9"></i></div>
                <h2>Todos los usuarios</h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="usersTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Grupos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Grupos</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
            </table>
            </div>
        </div>
    </div>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#usersTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("backend/usuarios/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [5], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                            var buttons = '<a href="<?php echo site_url("backend/usuarios/edit_user/");?>/'+data+'"class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>'; 
                                buttons += '<a href="<?php echo site_url("backend/usuarios/delete");?>/'+data+'"  onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red">';
                                buttons +=  '<div class="icon"><span class="ico-remove"></span></div></a>';    
                            return buttons;
                        } 
                    },
                    {
                        "bSortable": false, "aTargets": [4], "bSearchable": false,
                        "mRender" : function(data, type, row ){
                            var groups = "";
                            $.each(data, function(index, item){
                               groups += "<a href='<?php echo site_url("backend/usuarios/edit_group/");?>/"+item.id+"' class='label label-success' style='margin-bottom: 2px;'>"+item.name+"</a><br/>";
                            });
                            return groups;
                        }
                    },
                    {
                        "bSortable": false, "aTargets": [3], "bSearchable": false,
                        "mRender" : function(data, type, row ){
                           
                            var active = "";
                            if(data == "1"){
                                active = "<a href='<?php echo site_url("backend/usuarios/deactivate/")?>/"+row[5]+"' class='label label-important'>Desactivar</a>";
                            }
                            else {
                                active = "<a href='<?php echo site_url("auth/activate/")?>/"+row[5]+"' class='label label-info'>Activar</a>";
                            }
                            return active;
                        }
                    }
                ]
        });
    });
</script>
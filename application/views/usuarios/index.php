<div class="page-header">    
    <div class="icon">
        <img alt="Usuarios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_usuarios']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Usuarios", "Usuarios");?></h1>
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
            <!--<a href="<?php echo site_url("usuarios/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small> <?php echo custom_lang('Nuevo Usuario', "Nuevo usuario");?></a>-->
             <a href="<?php echo site_url("usuarios/nuevo")?>" data-tooltip="Nueva Venta">                        
                <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                 
            </a> 

        <div class="head blue">
            <h2>Listado de usuarios</h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="usuariosTable">

                        <thead>
                            <tr>  
                                <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="20%"><?php echo custom_lang('sima_email', "Correo electr&oacute;nico");?></th>
                                <th width="10%"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?></th>
                                <th width="20%"><?php echo custom_lang('sima_rol', "Rol");?></th>
                                <th width="10%"><?php echo custom_lang('sima_rol', "Desactivado");?></th>
                                <th width="10%"><?php echo custom_lang('sima_admin', "Administrador");?></th>
                                <th width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>  </tbody>
                        <tfoot>
                            <tr>

                                <th><?php echo custom_lang('sima_name', "Nombre");?></th>

                                <th><?php echo custom_lang('sima_email', "Correo electr&oacute;nico");?></th>

                                <th><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?></th>

                                <th><?php echo custom_lang('sima_rol', "Rol");?></th>

                                <th><?php echo custom_lang('sima_admin', "Desactivado");?></th>

                                <th><?php echo custom_lang('sima_admin', "Administrador");?></th>

                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </tfoot>

                    </table> 

                </div>

            </div>

            

        </div>

    </div>



    

    

    

        

        

        

    





<script type="text/javascript">

var id_usuario = <?php echo $this->ion_auth->get_user_id(); ?>;

    $(document).ready(function(){

        $('#usuariosTable').dataTable( {

                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("usuarios/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {                       
                            result = 'No'; 
                            if(data == 't'){
                                result = "Si";
                            }
                            return result;
                        } 
                    },
                    { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {                        
                            result = 'No'; 
                            if(data == '0'){
                                result = "Si";
                            }
                            return result;
                        } 
                    }
                    ,{ "bSortable": false, "aTargets": [ 6 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {                            
                            var buttons ="<div class='btnacciones'>";
                                buttons += '<a href="<?php echo site_url("usuarios/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                if(data !=id_usuario){
                                    buttons += '<a href="<?php echo site_url("usuarios/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';   
                                }
                             buttons += "</div>";
                            return buttons;
                        } 
                    }
                ]                
        });

    });

</script>
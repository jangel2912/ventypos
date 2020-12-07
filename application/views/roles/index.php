<div class="page-header">    
    <div class="icon">
        <img alt="Roles" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_roles']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Roles", "Roles");?></h1>
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

            <!--<a href="<?php echo site_url("roles/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_category', "Nuevo rol");?></a>-->
            <a href="<?php echo site_url("roles/nuevo")?>" data-tooltip="Nuevo Rol">                        
                <img alt="roles" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                 
            </a>   
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_category', "Listado de Roles");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="rolesTable">

                        <thead>
                            <tr>  
                                <th width="45%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="45%"><?php echo custom_lang('sima_description', "Descripción");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                           <tr>  
                                <th width="45%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="45%"><?php echo custom_lang('sima_description', "Descripción");?></th>
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

        $('#rolesTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("roles/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                        { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                            var buttons = "<div class='btnacciones'>";
                            buttons += '<a href="<?php echo site_url("roles/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                                buttons += '<a href="<?php echo site_url("roles/eliminar/");?>/'+data+'" class="eliminarRol button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            buttons += "</div>";
                            return buttons;

                        } 

                    }

                ]

        });

    });
    
    $(document).on('click','a.eliminarRol',function(event){
        event.preventDefault();
        if(confirm("¿Esta seguro que desea eliminar el registro?"))
        {
            var $this = $(this);
            $.post
            (
                $this.attr('href'),
                function(data)
                {
                    if(data.resp == "1")
                    {
                        $this.parents("tr").remove();
                        alert("Se ha eliminado correctamente el rol");
                    }else
                    {
                        alert("No se puede eliminar el rol puesto que hay usuarios asociados al rol");
                    }
                },'json'
            );
        }
    });

</script>
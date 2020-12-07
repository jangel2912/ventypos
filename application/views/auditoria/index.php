<div class="page-header">    
    <div class="icon">
        <img alt="Auditoria" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_auditoria']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Auditoria", "Auditoría Inventario");?></h1>
</div>

<div class="row-fluid">
	<div class="col-md-12">
        <div class="block">
            <?php
            $message = $this->session->flashdata('message');
            $message1 = $this->session->flashdata('message1');

            if (!empty($message)):
                ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; 

            if (!empty($message1)):
                ?>
                <div class="alert alert-error">
                    <?php echo $message1; ?>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
                <?php
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                
                if (in_array("1027", $permisos) || $is_admin == 't'):
                ?>
                <div class="col-md-2">
                    <a href="<?php echo site_url("auditoria/nuevo")?>" data-tooltip="Nueva Auditoría">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
                    </a>                    
                </div>
                <div class="col-md-2">
                    <a href="<?php echo site_url("auditoria/generar_excel_para_auditoria_view")?>" data-tooltip="Nueva Auditoría desde Excel">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['auditoria_excel']['original'] ?>">                         
                    </a>                    
                </div>
                <!--<a href="<?php echo site_url("auditoria/nuevo") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_auditoria', "Nueva Auditoría"); ?></a>
                <a href="<?php echo site_url("auditoria/generar_excel_para_auditoria_view") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_auditoria_excel', "Nueva auditoría desde Excel"); ?></a>-->
                <!--<div style="float: right">                
                    <a href="<?php echo site_url("auditoria/index/-1")?>" class="btn default"><?php echo custom_lang('sima_new_bill', "Auditorías Anuladas");?></a>
                </div>-->
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("auditoria/index/-1")?>" data-tooltip="Auditorías Anuladas">                            
                        <img alt="Auditorías anuladas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['auditorias_anuladas']['original'] ?>">                                                           
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">               
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Auditorías");?></h2>
            </div>

            <div class="data-fluid">  
                <div class="col-xs-12 text-center ">
                    <div id="div_cargando" class="cargando" style="display:none">
                        <div class="">Espere...<br><img src="<?php echo base_url(); ?>public/img/loaders/loading_icon.gif" alt="Cargando" height="42" width="42"></div>			
                    </div>                   
                    <div id="div_mensajes">                        
                    </div>                    
                </div>
                                
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="auditoriaTable">
                    <thead>
                        <tr>                           
                            <th width="5%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
                            <th width="15%"><?php echo custom_lang('price_active', "Fecha Auditoría"); ?></th>
                            <th width="20%"><?php echo custom_lang('price_active', "Almacén"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Descripción"); ?></th>
                            <th width="10%"><?php echo custom_lang('estado_auditoria', "Estado"); ?></th>
                            <th width="10%"><?php echo custom_lang('soporte_digital', "Soporte Digital"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>                           
                            <th width="5%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>
                            <th width="15%"><?php echo custom_lang('price_active', "Fecha Auditoría"); ?></th>
                            <th width="20%"><?php echo custom_lang('price_active', "Almacén"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Descripción"); ?></th>
                            <th width="10%"><?php echo custom_lang('estado_auditoria', "Estado"); ?></th>
                            <th width="10%"><?php echo custom_lang('soporte_digital', "Soporte Digital"); ?></th>
                            <th width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>       
</div>
<script type="text/javascript">
	$(document).ready(function(){

        $('#auditoriaTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "aaSorting": [[ 0, "desc" ]],

                "sAjaxSource": "<?php echo site_url("auditoria/get_ajax_datatable");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [
                        { "aTargets":[6],
                          "mRender": function(data, type, row){
                              nombre_archivo = data.substring(data.lastIndexOf("/")+1);
                               var url_archivo = '<a href="<?php echo base_url() ?>'+data+'">'+nombre_archivo+'</a>'; 
                               return url_archivo
                          }  

                        },
                        { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false, 

                            "mRender": function ( data, type, row ) {
                               var buttons ="<div class='btnacciones'>";
                              if(row[5] == 'borrador'){   
                                buttons += '<a data-tooltip="Editar" href="<?php echo site_url("auditoria/editar_view/");?>/'+row[0]+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                buttons += '<a data-tooltip="Afectar Inventario/Cerrar" href="<?php echo site_url("auditoria/cerrar/");?>/'+row[0]+'" id="'+row[0]+'" class="button default cerrar acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['afectar_cerrar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['afectar_cerrar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['afectar_cerrar']['original'] ?>"></div></a>';
                                   
                                <?php if($this->session->userdata('is_admin') == 't'){ ?>
                                    buttons += '<a data-tooltip="Anular" href="<?php echo site_url("auditoria/eliminar/");?>/'+row[0]+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea anular la auditoría?");?>\')){return true;}else{return false;}" class="button red anular acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';                         

                                <?php } ?> 
                               }                        
                            buttons += "</div>";
                            return buttons;

                        } 

                    }

                ]

        });
        
        $(".cerrar").live("click", function(e){            
            e.preventDefault();
            var r =confirm("Esta seguro de cerrar la auditoría? Esto implica que ajustará el inventario");
            if (r == true) {
                url=$(this).attr('href');
                id=$(this).attr('id');
                //alert("sigo el camino "+id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    //data: { id: id },
                    beforeSend: function(){                            
                        $("#div_cargando").css('display', 'block'); 
                    },
                    success: function(result){ 
                        //console.log(result.status); 
                        if(result.status){                      
                            $("#div_cargando").css('display', 'none');
                            $("#div_mensajes").addClass('alert alert-success');
                            $("#div_mensajes").html(result.error_message);
                            setTimeout("location.reload();", 5000);
                        }else{
                            $("#div_mensajes").addClass('alert alert-error');
                            $("#div_mensajes").html(result.error_message);
                        }
                    }
                });
            } 
        });
    /*
         $(".anular").live("click", function(e){            
            e.preventDefault();
            var r =confirm("Esta seguro de cerrar la auditoría? Esto implica que ajustará el inventario");
            if (r == true) {
                url=$(this).attr('href');
                id=$(this).attr('id');
                //alert("sigo el camino "+id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: { id: id },
                    beforeSend: function(){                            
                        $("#div_cargando").css('display', 'block'); 
                    },
                    success: function(result){ 
                        console.log(result.status); 
                        if(result.status){                      
                            $("#div_cargando").css('display', 'none');
                            $("#div_mensajes").addClass('alert alert-success');
	                        $("#div_mensajes").html(result.error_message);
                        }else{
                            $("#div_mensajes").addClass('alert alert-error');
                            $("#div_mensajes").html(result.error_message);
                        }
                    }
                });
            } 
        });
        */
    });
	
</script>
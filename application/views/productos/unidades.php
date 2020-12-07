<div class="page-header">    
    <div class="icon">
        <img alt="productos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_productos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Unidades", "Unidades de Productos");?></h1>
</div>
   
    <div class="row-fluid">
        <div class="col-md-12">
            <?php
                $message = $this->session->flashdata('message');
                $validate = $this->session->flashdata('alert_message');
                if(!empty($message)):?>
                    <div class="<?php echo 'alert alert-'.$validate ?>">
                        <?php echo $message;?>                        
                    </div>
            <?php endif; ?>
            <div class="block">
                <?php 
                    $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                ?>
                <div class="col-md-6">
                    <?php if(in_array("3", $permisos) || $is_admin == 't'): ?>                            
                        <div class="col-md-2">
                            <a id="add-new-unidad" data-tooltip="Nueva Unidad">                        
                                <img alt="Nueva Unidad" class="btnimagenes" src="<?= base_url('uploads/iconos/Verde/unidad_de_medida.svg') ?>">                    
                            </a>                                          
                        </div>                  
                    <?php endif; ?> <!--fin nueva unidad-->
                </div>                                    
            </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">                
                <h2><?php echo custom_lang('sima_all_product', "Listado de Unidades");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table" cellpadding="0" cellspacing="0" width="100%" id="unidadesTable">
                        <thead>
                            <tr>                                
                                <th width="90%"><?php echo custom_lang('sima_name', "Nombre");?></th>                                
                                <?php if(in_array('4', $permisos) || in_array('5', $permisos) || $is_admin == 't'):?>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($data['unidades'] as $key => $value) { 
                            ?>                                
                                <tr>
                                    <td><?= $value ?></td>                                
                                    <td>
                                        <a data-tooltip="Eliminar" onclick="eliminarUnidad(<?= $key ?>)" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>
                                    </td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                        <tfoot>
                             <tr>                                
                                <th width="90%"><?php echo custom_lang('sima_name', "Nombre");?></th>                                
                                <?php if(in_array('4', $permisos) || in_array('5', $permisos) || $is_admin == 't'):?>
                                    <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                <?php endif;?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div> 
        </div>
    </div>
    
    <div class="social">
		<ul>		
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">
       <div style="padding:48.81% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266933291?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>           
    </div>
     

    
    
        

<script type="text/javascript">

    $(document).ready(function(){
   
        $('#unidadesTable').dataTable({});

         var unidadDialog = unidadDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            
            var $dialog = $(
            '<div id="cliente-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;"><h4><?php echo custom_lang('sima_motivo_form', "Agregar Unidad de Medida");?></h4></div>' +
                '<div class="modal-body">' +
                    '<form id="form_unidades"  action="<?php echo site_url("productos/unidades");?>" method="POST" >'+
                    '    <div class="row-form">'+
                    '        <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>'+
                    '        <div class="span3"> <input type="text" name="nombre" id="nombre" required /> </div>'+                    
                    '    </div>'+						
                    '    </div>'+						
                    '    <div class="modal-footer">'+
                    '    <div class="pull-right"> '+
                    '       <input type="button" value="Cancelar" data-dismiss="modal"  id="cancelar" class="btn btn-default"/> '+
                    '       <input id="unidad_producto" type="button" value="Guardar"  class="btn btn-success"/>'+
                    '    </div>'+
                    '</form>'+
                '</div>' +
            '</div></div></div>');
            return {
                show:function(){      
                    $dialog.find("#unidad_producto").click(function(e){                       
                        e.preventDefault();    
                        //verificar si no esta metida la unidad  
                        $("#form_unidades").submit();

                    });                    
                    $dialog.modal();
                },
                hide:function(){
                        $dialog.hide();
                }
            }
        })(jQuery);

        $("#add-new-unidad").click(function(){              
            unidadDialog.show(null);
        });
        
    });

    function eliminarUnidad(id){
        
        swal({
            title: '¿Está seguro?',
            text: "¿Desea eliminar esta unidad de medida?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5ca745',
            cancelButtonColor: '#e53935',
            confirmButtonText: 'Si, Eliminar'
            }).then((result) => {
            if (result.value) {               
                //verificar que se pueda eliminar sino tiene productos asciados y eliminarlos si no hay inconvenientes
                $.ajax({
                    url: "<?php echo site_url("productos/eliminar_unidades")?>",
                    data: { id: id },
                    type: "POST",
                    success: function(response) {

                        if(response.success==0){
                        
                            swal({
                                position: 'center',
                                type: 'error',
                                title: 'Error',
                                html: response.msm,
                                showConfirmButton: false,
                                timer: 1500
                            });                    
                            
                        }else{
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Redirigiendo',
                                html: response.msm,
                                showConfirmButton: false,
                                timer: 1500
                            });                                                   
                                                    
                            setTimeout(function(){
                                location.href = "<?php echo site_url('productos/unidades');?>";
                            }, 1600);    
                        }                	    		
                    }
                });
                
            }
        })
       
        /*if(confirm("Esta seguro que desea eliminar este producto?"))
        {
            $.post
            (
                "<?php echo site_url("productos/eliminarConfirmacion")?>/"+id,
                {},function(data)
                {
                    if(data.resp == 1)
                    {
                        window.location = '<?php echo site_url("productos/eliminar/");?>/'+id;
                        
                    }else if(data.resp == 2)
                    {
                        alert("El producto ya fue vendido por lo que no puede ser eliminado");
                    }else if(data.resp == 3)
                    {
                        alert("El producto es un ingrediente de otro producto o parte de un combo");
                    }
                },'json'
            );
        }*/
        
    }
        
</script>
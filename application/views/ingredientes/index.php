<div class="page-header">    
    <div class="icon">
        <img alt="Ingredientes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ingredientes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ingredientes", "Ingredientes");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <?php $permisos = $this->session->userdata('permisos');
                  $is_admin = $this->session->userdata('is_admin');  
                    if(in_array("3", $permisos) || $is_admin == 't'):?>
                     <!--<a href="<?php echo site_url("ingredientes/nuevo")?>" class="btn btn-success"><?php echo custom_lang('sima_new_ingredient', "Nuevo ingrediente");?></a>-->
                     <div class="col-md-6">
                        <a href="<?php echo site_url("ingredientes/nuevo")?>" data-tooltip="Nuevo Ingrediente">                        
                            <img alt="Ingrediente" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                            
                        </a>                    
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_ingredient', "Todos los ingredientes");?></h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">

                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio");?></th>
                                <th width="10%"><?php echo custom_lang('sima_unit', "Unidades");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        <tbody> </tbody>
                        <tfoot>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio");?></th>
                                <th width="10%"><?php echo custom_lang('sima_unit', "Unidades");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
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
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266934419?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
     
<script type="text/javascript">

    $(document).ready(function(){

        $('#productosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("ingredientes/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 0 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {

                            var url = '<?php echo base_url("uploads");?>';

                            image_name = 'default.png';

                            if(data != ""){

                                image_name = data;

                            }

                            return "<img class='img-polaroid' height='30px' width='30px' src='"+url+"/"+image_name+"'/>";

                        }

                    },

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {
                            console.log(data);
                            var buttons = '';

                                <?php if(in_array('12', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Editar" href="<?php echo site_url("ingredientes/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" ></div></a>';

                                 <?php endif;?>

                                 <?php if(in_array('13', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Eliminar" href="javascript: void(0)" onclick="eliminarProducto('+data+')" class="button red acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';

                                 <?php endif;?>





                            return buttons;

                        }

                    }
                ]
        });
    });

    function eliminarProducto(id){
        //DB.Producto().eliminar(id);
        if(confirm("Esta seguro que desea eliminar este ingrediente?"))
        {
            $.post
            (
                "<?php echo site_url("ingredientes/eliminarConfirmacion")?>/"+id,
                {},function(data)
                {
                    if(data.resp == 1)
                    {
                        window.location = '<?php echo site_url("ingredientes/eliminar/");?>/'+id;
                        //console.log("asasbn");
                    }else if(data.resp == 2)
                    {
                        alert("El producto es un ingrediente de un producto y no puede ser eliminado");
                    }
                },'json'
            );
        }
    }

</script>
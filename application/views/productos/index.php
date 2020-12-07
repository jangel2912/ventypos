<div class="page-header">    
    <div class="icon">
        <img alt="productos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_productos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Productos", "Productos");?></h1>
</div>
   
<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                $validate = $this->session->flashdata('validar_almacen');
                if(!empty($message)):?>
                    <div class="<?php echo 'alert alert-'.$validate ?>">
                        <?php echo $message;?>
                        <?php
                        $arch = $this->session->flashdata('archivo');
                        if(!empty($arch)){?>
                        <a href="../../uploads/archivos_productos/Productos No Guardados.xls" download="Productos No Guardados"> Descargar Archivo </a>
                           <?php  } ?>
                    </div>
            <?php endif; ?>
                
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
            ?>
                    <div class="col-md-6">
                    <?php if(in_array("3", $permisos) || $is_admin == 't'): ?>                            
                        <!--<a href="<?php echo site_url("productos/nuevo")?>" class="btn btn-success"><?php echo custom_lang('sima_new_product', "Nuevo producto");?></a>-->
                        <div class="col-md-2">
                            <a href="<?php echo site_url("productos/nuevo")?>" data-tooltip="Nuevo Producto">                        
                                <img alt="nuevo producto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                            
                            </a>                    
                        </div>
                        <?php if($data['tipo_negocio'] == "moda"): ?>
                        <div class="col-md-2">
                            <a href="<?php echo site_url("atributos/productos")?>" data-tooltip="Nuevo Producto con Atributos" >
                                <img alt="nuevo producto con Atributos" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['producto_atributos_verde']['original'] ?>">  
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2">
                            <a href="<?php echo site_url("productos/unidades")?>" data-tooltip="Nueva Unidad de Producto">                        
                                <img alt="nueva unidad" class="btnimagenes" src="<?= base_url('uploads/iconos/Verde/unidad_de_medida.svg') ?>">                            
                            </a>                    
                        </div>
                    <?php endif; ?> <!--fin nuevo producto-->
                    </div>
                    <div class="col-md-6 btnizquierda">
                        <?php 
                            $coloffset="col-md-offset-6";                            
                            if($data['precio_almacen'] == 1){
                                $coloffset="col-md-offset-4";
                            }                        
                        ?>
                        <div class='col-md-2 <?= $coloffset ?>'>
                            <a href="<?php echo site_url("productos/import_excel_new");?>" data-tooltip="Importar Productos">
                                <img alt="Importar Productos" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>">  
                            </a>
                        </div>
                        <?php 

                        if($data['precio_almacen'] == 1){?>
                            <div class="col-md-2">
                                <a href="<?php echo site_url("productos/store_price_update")?>" data-tooltip="Actualizar Precios por almacén" >
                                    <img alt="Actualizar Precios por almacén" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['actualizar_preciosxalmacen']['original'] ?>">  
                                </a>
                            </div>
                            <?php }?>                       

                        <?php if( in_array("68", $permisos ) || $is_admin == 't'){ ?>
                            <div class="col-md-2">
                                <a href="<?php echo site_url("ingredientes"); ?>" data-tooltip="Materiales">
                                    <?php if(($data['tipo_negocio'] !='restaurante')||($data['tipo_negocio'])!='restaurante '){ ?>
                                    <img alt="Materiales" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ingredientes_verde']['original'] ?>">  
                                    <?php }else{ ?>
                                    <img alt="Materiales" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['materiales_verde']['original'] ?>">  
                                    <?php } ?>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="col-md-2">
                            <a href="<?php echo site_url("productos/excel");?>" data-tooltip="Exportar Productos">
                                <img alt="Exportar Productos" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">  
                            </a>
                        </div>
                    </div>

                </div>
            </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">                
                <h2><?php echo custom_lang('sima_all_product', "Listado de Productos");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                <th width="15%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>                                
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>                        
                                <th width="10%"><?php echo custom_lang('sima_price', "Precio de venta");?></th>
                                <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sisma_categoria', "Categoría");?></th>
                                <th width="10%"><?php echo custom_lang('sisma_cantidad', "Cantidad");?></th>
                                <?php if(in_array('4', $permisos) || in_array('5', $permisos) || $is_admin == 't'):?>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                             <tr>
                                <th width="10%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                <th width="15%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>                                
                                <th width="10%"><?php echo custom_lang('price_of_purchase', "Precio de compra");?></th>                        
                                <th width="10%"><?php echo custom_lang('sima_price', "Precio de venta");?></th>
                                <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th width="10%"><?php echo custom_lang('sisma_categoria', "Categoría");?></th>
                                <th width="10%"><?php echo custom_lang('sisma_cantidad', "Cantidad");?></th>
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
    <div class="myImageFinderModal"></div>

    
    
        

<script type="text/javascript">

    $(document).ready(function(){
   
        $('#productosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("productos/get_ajax_data");?>",

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

                            return "<img class='img-polaroid' height='30px' width='30px' src='"+image_name+"'/>";

                        }

                    },

                    {  <?php if(in_array('4', $permisos) || $is_admin == 't'){   $con='8'; } else {    $con='3';  }   ?>
					
					"bSortable": false, "aTargets": [  <?php echo $con; ?> ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                           
                            var buttons = "<div class='btnacciones'>";

                            buttons += '<a data-tooltip="Subir Imagen Principal" href="javascript:void(false);" data-product-id="'+ data +'"  class="button default acciones upload-first-image"><div class="icon"><img data-cambiar="/uploads/iconos/Blanco/icono_blanco-15.svg" data-original="/uploads/iconos/Gris/icono_gris-15.svg" alt="Editar" class="iconacciones" src="/uploads/iconos/Gris/icono_gris-15.svg" ></div></a>';

                                <?php if(in_array('4', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Editar" href="<?php echo site_url("productos/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="Editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" ></div></a>';

                                 <?php endif;?>

                                 <?php if(in_array('5', $permisos) || $is_admin == 't'):?>

                                    buttons += '<a data-tooltip="Eliminar" href="javascript: void(0)" onclick="eliminarProducto('+data+')" class="button red acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';

                                 <?php endif;?>
                                buttons += "</div>";
                            return buttons;

                        }

                    }

                ]

        });

    });
    
    //var DB = new _DB();

    function eliminarProducto(id){
       
        if(confirm("Esta seguro que desea eliminar este producto?"))
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
        }
        
    }
    
    
</script>
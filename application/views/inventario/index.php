<div class="page-header">    
    <div class="icon">
        <img alt="movimientos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_movimientos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("movimientos", "Movimientos");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
			$is_admin = $this->session->userdata('is_admin');
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <?php    
                $message_error = $this->session->flashdata('message_error');
                if(!empty($message_error)):?>
                <div class="alert alert-error">
                    <?php echo $message_error;?>
                </div>
            <?php endif; ?>
            <?php $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');

                if( in_array("1007", $permisos) ||
                    in_array("1019", $permisos) ||
                    in_array("1020", $permisos) ||
                    in_array("1021", $permisos) ||
                    $is_admin == 't'):?>                    
                    <!--<a href="<?php echo site_url("inventario/nuevo")?>" class="btn btn-success"><?php echo custom_lang('sima_new_bill', "Nuevo movimiento");?></a>-->
                    <div class="col-md-6">
                        <a href="<?php echo site_url("inventario/nuevo")?>" data-tooltip="Nuevo Movimiento">                        
                            <img alt="Nuevo Movimiento" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a>                    
                    </div>
                    
                    <!--<div style="float:right">
                        <a href="<?php echo site_url("inventario/import_excel_nombre_codigo")?>" class="btn default"> <?php echo custom_lang('sima_new_bill', "Importar Excel al Inventario código del producto y cantidad");?></a>
                        <?php  if($this->session->userdata('base_dato') == 'vendty2_db_562a64c85a0a2' || $this->session->userdata('base_dato') == 'vendty2_db_5500a0c4159d4'): ?>
    		                <a href="<?php echo site_url("inventario/consolidado_inventario_almacen")?>" class="btn default"> <?php echo custom_lang('sima_new_bill', "Consolidado de inventario");?></a>
                        <?php endif; ?>
                    </div>-->
            <?php endif;?>
            <div class="col-md-6 btnizquierda">                    
                    <?php  
                    $col="col-md-offset-10";
                    if($this->session->userdata('base_dato') == 'vendty2_db_562a64c85a0a2' || $this->session->userdata('base_dato') == 'vendty2_db_5500a0c4159d4'){
                        $col="col-md-offset-8";
                    } ?>
                    <div class="col-md-2 <?=$col?>">
                        <a href="<?php echo site_url("inventario/import_excel_nombre_codigo")?>" data-tooltip="Importar por código del producto y cantidad">                            
                            <img alt="Importar por código del producto y cantidad" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>">                                                           
                        </a>
                    </div>      
                    <?php  if($this->session->userdata('base_dato') == 'vendty2_db_562a64c85a0a2' || $this->session->userdata('base_dato') == 'vendty2_db_5500a0c4159d4'): ?>
                        <div class="col-md-2">    
                            <a href="<?php echo site_url("inventario/consolidado_inventario_almacen")?>" data-tooltip="Consolidado de Inventario"> 
                                <img alt="Consolidado de Inventario" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['consolidado_movimiento_verde']['original'] ?>">                             
                            </a>
                    <?php endif; ?>              
                </div>
            </div>
        </div>
    </div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">

            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Todos los Movimientos de Inventario");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">
                        <thead>
                            <tr> 
                                <th width="10%"><?php echo custom_lang('sima_number', "Consecutivo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_number', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_customer', "Tipo");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Código");?></th>
                                <th width="10%"><?php echo custom_lang('sima_nota', "Nota");?></th>
                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>
								<?php if($is_admin == 't'){ ?>	
                                    <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>
                                <?php } ?>	
                                <th  width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            <tr> 
                                <th width="10%"><?php echo custom_lang('sima_number', "Consecutivo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_number', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_customer', "Tipo");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "Código");?></th>
                                <th width="15%"><?php echo custom_lang('sima_nota', "Nota");?></th>
                                <th width="10%"><?php echo custom_lang('sima_saldo', 'Fecha');?></th>
								<?php if($is_admin == 't'){ ?>	
                                    <th width="10%"><?php echo custom_lang('sima_action', "Valor");?></th>
                                <?php } ?>	
                                <th  width="15%"><?php echo custom_lang('sima_action', "Acciones");?></th>                                
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
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266925179?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>  
    </div>
    
<script type="text/javascript">
    $(document).ready(function(){
        $('#ventasTable').dataTable( {
                "aaSorting": [[ 5, "desc" ]],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("inventario/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    {
					<?php if($is_admin == 't'){ ?>
					"bSortable": false, "aTargets": [ 7 ], "bSearchable": false,
					<?php } ?>
					<?php if($is_admin != 't'){ ?>
					"bSortable": false, "aTargets": [ 6 ], "bSearchable": false,
					<?php } ?>
                        "mRender": function ( data, type, row ) {
                                var buttons = "<div class='btnacciones'>";
                               <?php if(in_array('57', $permisos) || $is_admin == 't'):?>
                                        buttons += '<a href="<?php echo site_url("inventario/imprimir/");?>/'+data+'" class="button default btn-print acciones" data-tooltip="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                                        buttons += '<a href="<?php echo site_url("inventario/imprimir_tirilla/");?>/'+data+'" class="button default btn-print acciones" data-tooltip="Imprimir Tirilla"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['original'] ?>" ></div></a>';
                                <?php endif;?>
                                <?php  if(in_array('12', $permisos) || $is_admin == 't'){?>
                                    //buttons += '<a href="<?php echo site_url("ventas/editar/");?>/'+data+'" class="button default"><div class="icon"><span class="ico-pencil"></span></div></a>';
                                 <?php  } ?>
                                 <?php if(in_array('13', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php echo site_url("inventario/eliminar/");?>/'+data+'" id="'+data+'" data-tooltip="Eliminar" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar este movimiento de inventario?");?>\')){return true;}else{return false;}" class="button red anular acciones" title="Anular"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" ></div></a>';
                                 <?php endif;?>
                                buttons += "</div>";
                                return buttons;
                        }
                    }
                ]
        });
        
        $('.btn-print').fancybox({
            'width' : '85%',
            'height' : '85%',
            'autoScale' : false,
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'type' : 'iframe'
          }
        );
    });
</script>
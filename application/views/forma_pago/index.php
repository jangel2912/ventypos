<div class="page-header">    
    <div class="icon">
        <img alt="Formas de Pago" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_formasdepagos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Formas de Pago");?></h1>
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

            <?php
                $is_admin = $this->session->userdata('is_admin');
                if($is_admin == 't'):?>
                <!--<a href="<?php echo site_url("forma_pago/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_sales_man', "Nueva forma de pago");?></a>-->
                <a href="<?php echo site_url("forma_pago/nuevo")?>" data-tooltip="Nueva Forma de Pago">                        
                    <img alt="forma_pago" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                     
                </a>   
            <?php endif;?>

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_provider', "Listado de Formas de Pago");?></h2>
            </div>

            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="formapagoTabla">
                    <thead>
                        <tr>
                            <th width="30"><?php echo custom_lang('sima_nombre', "Nombre");?></th>
                            <th width="30"><?php echo custom_lang('sima_tipo','Tipo')?></th>
                            <th width="30"><?php echo custom_lang('sima_activo', "Activo");?></th>
                            <th width="10"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th width="30"><?php echo custom_lang('sima_nombre', "Nombre");?></th>
                            <th width="30"><?php echo custom_lang('sima_tipo','Tipo')?></th>
                            <th width="30"><?php echo custom_lang('sima_activo', "Activo");?></th>
                            <th width="10"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="social">
		<ul>
			<!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
			<li><a href="#myModalvideovimeo" data-toggle="modal" id="modal-click-vimeo" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo https://player.vimeo.com/video/266923686?loop=1&color=ffffff&title=0&byline=0&portrait=0-->    
    <div id="myModalvideovimeo" class="modal fade">  
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/267663868?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->
<div class="social">
		<ul>
			<!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
			<li><a href="#myModalvideovimeo" data-toggle="modal" id="modal-click-vimeo" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo https://player.vimeo.com/video/266923686?loop=1&color=ffffff&title=0&byline=0&portrait=0-->    
    <div id="myModalvideovimeo" class="modal fade">  
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/267663868?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->


<script>
$(document).ready(function(){

    $('#formapagoTabla').dataTable( {
        
        //"aaSorting": [[ 3, "desc" ]],
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo site_url("forma_pago/get_ajax_data");?>",
        "sPaginationType": "full_numbers",
        "iDisplayLength": 50, "aLengthMenu": [5,10,25,50,100],
        "aoColumnDefs" : [
            { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false, 
                "mRender": function ( data, type, row ) {
                    var input = "",
                        activo = (data == 1 ) ? "Si":"No",
                        checked = (data == 1 ) ? "checked":"";
                        
                    <?php if($is_admin == 't'):?>
                       input = '<input type="checkbox" value="1" class="inputActivo" '+checked+'>';
                    <?php endif;?>

                    var input = input + "&nbsp;<span class='activo'>"+activo+"</span>";
                    return input;
                }
            },    
            { "bSortable": false, "aTargets": [ 3 ], "bSearchable": false, 
                "mRender": function ( data, type, row ) {
                    var buttons = "<div class='btnacciones'>";
                    <?php if($is_admin == 't'):?>
                       buttons += '<a style="display: none;" data-id="'+data+'" href="<?php echo site_url("forma_pago/editar/");?>/'+data+'" class="button default acciones" title="Editar"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                       if(row[0] != 'Gift Card' && row[0] != 'GiftCard' && row[0] != 'Efectivo' && row[0] != 'Crédito' && row[3] != 2 && row[0] != 'Nota Credito' && row[0] != 'Puntos' && row[0] != 'Tarjeta de crédito' && row[0] != 'Tarjeta debito' && row[0] != 'Tarjeta débito')
                       buttons += '<a href="<?php echo site_url("forma_pago/eliminar/");?>/'+data+'" class="button red anular acciones" title="Eliminar" onclick="confirm(\'Esta seguro de eliminar esta forma de pago?\')"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                     
                    <?php endif;?>
                    buttons += "</div>";
                    return buttons;
                }
            }
        ]
    });
});
$(document).on('change','input.inputActivo',function(){
    var $this = $(this),
        activo = ($this.prop("checked") == true) ? 1:0,
        id = $this.parents("tr").find('a').attr("data-id");       
    $.post
    (
        "<?php echo site_url("forma_pago/activo") ?>",
        {
            "activo":activo,
            "id":id
        },function(data)
        {
            if(data.resp == 1)
            {
                $this.parent().find("span.activo").html(data.texto);
            }
        },'json'
    );          
});
</script>
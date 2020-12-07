<div class="page-header">    
    <div class="icon">
        <img alt="Impresoras" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_impresora']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Impresoras", "Impresoras");?></h1>
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
                    if(in_array("15", $permisos) || $is_admin == 't'):?>
                     <!--<a href="<?php echo site_url("impresoras_restaurante/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('New Printer', "Nueva Impresora");?></a>-->
                <?php endif;?>           
                <div class="col-md-6">  
                    <?php if(in_array("15", $permisos) || $is_admin == 't'):?>    
                    <a href="<?php echo site_url("impresoras_restaurante/nuevo")?>" data-tooltip="Nueva Impresora">                        
                        <img alt="Impresora" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                        
                    </a>       
                    <?php endif;?>             
                </div>
                <div class="col-md-6 btnizquierda">
                    
                </div>
            </div>
        </div>
    </div>    

<div class="block title">
    <div class="head">  
        <?php if( $data['apikey'] != NULL):?>     
        <div class="row">                                  
         <div class="col-md-6">Código para impresora de comanda - <input class="input_apikley" value="<?= $data['apikey'];?>" disabled> 
         <a id="pop-apikey" href="#" data-container="body" data-content="Copia y pega este código para configurar la impresora en la aplicación de comanda" rel="popover" data-placement="right" data-original-="" data-trigger="hover" data-original-title="" title="">
            <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
        </a>
          </div>
        <?php endif;?>
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
                <?php $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                    if(in_array("15", $permisos) || $is_admin == 't'):?>
                     <!--<a href="<?php echo site_url("impresoras_restaurante/nuevo")?>" class="btn btn-success"><small class="ico-plus icon-white"></small><?php echo custom_lang('New Printer', "Nueva Impresora");?></a>-->
                <?php endif;?>           

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_category', "Listado de Impresoras");?></h2>
            </div>

                <div class="data-fluid table-responsive">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                        <thead>
                            <tr>
                                <th width="45%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                                <th width="45%"><?php echo custom_lang('sima_image', "Código");?></th>
                                <th  width="10%" class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>                            
                           
                            <?php 
                                foreach ($data['impresoras'] as $value) {
                            ?>
                                <tr>
                                    <td><?= $value['nombre'] ?></td>
                                    <td><?= $value['codigo'] ?></td>                                   
                                    <td>
                                    <?php if(in_array('16', $permisos) || $is_admin == 't'):?>

                                   <a href="<?php echo site_url('impresoras_restaurante/editar/'.$value['id']);?>" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>

                                 <?php endif;?> 

                                 <?php if(in_array('17', $permisos) || $is_admin == 't'):?>

                                    <a href="<?php echo site_url('impresoras_restaurante/eliminar/'.$value['id']);?>" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>

                                 <?php endif;?>

                                    </td>
                                </tr>
                            <?php       
                                }
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th><?php echo custom_lang('sima_image', "Nombre");?></th>
                            <th><?php echo custom_lang('sima_image', "Código");?></th>                           
                            <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
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
            <iframe  id="cartoonVideovimeo" src="https://player.vimeo.com/video/266767870?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
         
<script type="text/javascript">

    $(document).ready(function(){
        $("#pop-apikey").popover();

    });

</script>
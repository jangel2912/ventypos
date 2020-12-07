<div class="page-header">    
    <div class="icon">
        <img alt="Auditoria" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_auditoria']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Auditoria", "Auditoría Inventario");?></h1>
</div>


<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_new_auditoria', "Nueva Auditoría"); ?></h2>
    </div>
</div>

<div class="row-fluid">
    <?php echo form_open_multipart("auditoria/guardar_auditoria", array("id" => "f_auditoria")); ?>
    <div class="row-form">
    	<div class="col-xs-12">
	    	<div class="form-group">
	    		<label for="fecha_auditoria" class="col-xs-2 control-label">
	               <?php echo custom_lang('sima_fecha_auditoria', "Fecha auditoría"); ?>:                          
	           </label>
	           <div class="col-xs-10">
	           		<?php echo date("Y-m-d",now()) ?>
	           </div>
	    	</div>
    	</div>
    </div>
    <div class="row-form">
    	<div class="form-group">
	    	<div class="col-xs-12">
	    		<label for="t_nombre_auditoria" class="col-xs-2 control-label">
	    			<?php echo custom_lang('sisma_nombre_auditoria','Nombre auditoría') ?>:
	    		</label>
	    		<div class="col-xs-10">
	    			<input type="text" name="t_nombre_auditoria" id="t_nombre_auditoria" required="required">
	    		</div>
	    	</div>
    	</div>
    </div>   		
	<div class="row-form">
	   <div class="col-xs-12">
		 	<div class="form-group">
				<label for="s_almacen" class="col-xs-2 control-label">
					<?php echo custom_lang('sisma_almacen','Almacén'); ?>:
				</label>
				<div class="col-xs-10">
				   	<?php if($this->session->userdata('is_admin') == 't' && isset($data['almacenes'])){ ?>		
					   <select name="s_almacen" id="s_almacen" required="required">
					   	<option value="">Seleccione</option>
					   	<?php foreach ($data['almacenes'] as $key => $value) { ?>
					   		<option value="<?php echo $value->id ?>"><?php echo $value->nombre; ?></option>	
					   	<?php }   	?>
					   </select>
					<?php }else{ echo $data['nombre_almacen']; } ?>
				</div>
			</div>	
		</div>
    </div>    
    <div class="row-form">
	   <div class="col-xs-12">
		 	<div class="form-group">
				<label for="ta_descripcion_auditoria" class="col-xs-2 control-label">
					<?php echo custom_lang('ajustar_Auditoria','Ajustar auditoría'); ?>:
				</label>
				<div class="col-xs-10">
				   	<input type="checkbox" name="ch_ajustar">
				</div>
			</div>	
		</div>
    </div>
    
    <div class="row-form">
    	<div class="col-xs-12">
    	  <div class="form-group">
    		<label for="f_archivo_soporte" class="col-xs-2 control-label">
    		<?php echo custom_lang('sisma_archivo_soporte','Soporte de arqueo') ?>:		
    		</label>
    		<div class="col-xs-10 input-append file">               
                <input type="file" id="f_archivo_soporte" name="f_archivo_soporte" style="width: 320px;">	
                <input type="text" style="width: 333px;">
                <button class="btn btn-success" type="button">Buscar</button>
    		</div>
    	 </div>
    	</div>
    </div>
    
<?php echo form_close() ?>     
<div class="row-form">
    <div class="block">       
        <div class="head blue">
            <h2>Productos</h2>
        </div>
    </div>
</div>
    <div class="row-form">
        <div class="col-xs-12">
         <div class="block">
            <div class="head" style="text-align: center;">
                <div class="row-form panel newPanel newContNavegacion" style="padding-left: 0px;padding-right: 0px; padding-top:4px;">
                        <ul id="tipo-busqueda">
                            <li id="buscalo" class="active">
                                <h3>
                                    <!--<i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)" src="<?php echo base_url("/public/img/"); ?>/buscador.png" width="45px" height="15px">&nbsp;&nbsp;-->
                                    <img width="25" alt="Buscador" class="barraventa" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['buscador']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['buscador']['original'] ?>" src="<?php echo $this->session->userdata('new_imagenes')['buscador_verde']['original'] ?>" >
                                    Buscador 
                                </h3>
                            </li>
                            <li id="codificalo"> 
                                <h3>
                                    <!--<i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
                                    <img onerror="ImgError(this)" src="<?php echo base_url("/public/img/"); ?>/codigo_barra.png" width="40px" height="15px">
                                    &nbsp;&nbsp;-->
                                    <img width="25" alt="lector" class="barraventa" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['codigobarraventas']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['codigobarraventas']['original'] ?>" src="<?php echo $this->session->userdata('new_imagenes')['codigobarraventas']['original'] ?>" >
                                    Lector 
                                </h3> 
                            </li>                            
                        </ul>
                </div>
            </div>
            <div id="search-container" class="input-append">
                <input type="text" name="t_search" class="span12" placeholder="Digite producto a buscar..." id="t_search" autofocus="autofocus" style="width: 664px;">
                <button class="btn btn-success" id="faqSearch" type="button"><span class="icon-search icon-white"></span></button>
            </div>
          </div> 
        </div>
    </div>
    <div class="row-form">
        <div class="col-xs-12">
        <div class="form-group">
            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="tb_productos_auditoria">
                <thead>
                    <tr>
                        <th><?php echo custom_lang('sigma_codigo', "Código"); ?></th>
                        <th><?php echo custom_lang('Producto', "Producto"); ?></th>
                        <th><?php echo custom_lang('cantidad_contada', "Cantidad Contada"); ?></th>
                        <th><?php echo custom_lang('observacion_adicional', "Observación Adicional"); ?></th>
                        <?php if($this->session->userdata('is_admin') == 't'){ ?>
                            <th><?php echo custom_lang('cantidad_en_sistema', "Cantidad en Sistema"); ?></th>
                            <th style="text-align:center;"><?php echo custom_lang('diferencia', "Diferencia"); ?><b><br>Cantidad en sistema - cantidad contada</b></th>
                        <?php } ?>
                        <th><?php echo custom_lang('acciones','Acciones') ?></th>
                    </tr>   
                </thead>
                <tbody>
                    
                </tbody>
            </table>
         </div>
        </div>
    </div>
    <div class="row-form">
        <div class="col-xs-12">
            <div id="div_progress" class="progress">
              <div class="progress-bar progress-bar-success"  role="progressbar" style="width: 0%"></div>
                
            </div>
        </div>
        <div class="col-xs-12">
            <div id="div_mensajes">
                
            </div>
        </div>
    </div>
    <div class="pull-right">        
        <button class="btn btn-default"  type="button" onclick="javascript:location.href = '../auditoria/index'"><?php echo custom_lang('sima_cancel', "Volver"); ?></button>        
        <button class="btn btn-success" id="btn_enviar" ><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
            
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
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266950510?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 
    </div>   
 

<script type="text/javascript">
var url_busqueda ="<?php echo site_url("productos/filtro_prod_existencia_auditoria"); ?>";
var productos_seleccionados = [];
<?php if($this->session->userdata('is_admin') == 't'){ ?>
       const es_administrador = true;
<?php }else{ ?>
        const es_administrador = false;
<?php } ?>


$(".cantidad_contada").live("keyup", function(){   
    
    id=$(this).attr('id').split("_");
    contado=$(this).attr('value');
    
    if(contado>=0){
        if(id[3]!=""){
            $(this).parents("tr").parents().find("#tr_fila_"+id[3]).each(function(index,a) {
                row=$(this).find(".diferencias");            
                stock=$(this).find(".stock").html();
                x=calcular_diferencia_existencias(contado,stock);               
                row.html(x);
            });
        }
    }else{        
        alert("La cantidad contada debe ser número positivo");
        id2=$(this).attr('id');  
        $("#"+id2).val(1); 
        $(this).parents("tr").parents().find("#tr_fila_"+id[3]).each(function(index,a) {
            row=$(this).find(".diferencias");            
            stock=$(this).find(".stock").html();
            x=calcular_diferencia_existencias(1,stock);               
            row.html(x);
        });     
    }    
});
    
</script>

<style type="text/css">
	.block .head.green *{
        color: #555 !important;
        font-size: 14px;
    }    

    .newContPrecio .head.green {
        background-color: #fff !important;
    }

    .newContPrecio .head.green.well {
        background-color: #f2f2f2 !important;
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }    

    .newContPrecio .head.green span{
        color: #555 !important;
    }

    .newPanel{
        background-color: #fff;
        margin-bottom: 10px !important;
    }

    .newContNavegacion{
        padding: 5px 0px 5px 0px;
        margin-bottom: 5px !important;
    }

    #buscalo,#codificalo,#navegador{
        background-color: transparent !important;
        height: auto !important;
        border-left: transparent 0px solid !important;        
    }


    #codificalo{
        border-left: rgba(0,0,0,0.1) 1px solid !important;
        border-right: rgba(0,0,0,0.1) 1px solid !important;
    }

    #faqSearch{
        padding-bottom: 1px;
    }

    #tipo-busqueda{
        list-style: none;
        margin-left: 0px;
        overflow: hidden;
        height: auto !important;
        margin: 0px !important;
    }

    #tipo-busqueda h3{
       /* color: #131212 !important;*/
        color: #989a9d !important;
    } 
    /*
    #tipo-busqueda img{
        display: none;
    } */

    #tipo-busqueda li{
        background: #68AF27;
        width: 33.1%;
        float: left;
        text-align: center;
        color: white;
        border-left: white 1px solid;
        cursor:pointer; cursor: hand;
        height: 30px !important;
        padding-top: 6px;
    }

    #tipo-busqueda li.active{
        background: #316800!important;
        background-color: transparent !important;
    }


    #tipo-busqueda li.active h3{
        color: #66B12F !important;
    }

    #tipo-busqueda li h3{
        font-family: "Segoe UI", arial, sans-serif;
        font-weight: 400;
         margin: 0px !important;
        padding: 0px !important;
        font-size: 16px !important;
        transition: color 0.1s linear !important;
    }
    
    #tipo-busqueda li h3:hover {
        color: #66B12F !important;
    }

    #t_search,  #t_search:focus{
        border-color: #d2d2d2 !important;
        box-shadow: 0px 1px 6px rgba(0,0,0,0.08) !important;
        border-radius: 4px 0px 0px 4px;
    }

</style>
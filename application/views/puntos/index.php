<style>
    .item-form-point{font-size: 14px;color: #6a6c6f;font-weight:bold;}
    .ml-0{margin-left:0px !important;}
    .mr-0{margin-right:0px !important;}
    #ui-datepicker-div{z-index:99; background-color:#fff;}
    .help-header{border: solid 1px lightgrey;padding: 7px;box-sizing: border-box;border-left-width: 6px;border-left-color: green;}
    .pl-0{padding-left:0px !important;} 
    .pr-0{padding-right:0px !important;}
    .danger{color: #721c24;font-size: 12px;font-family: sans-serif;font-style: italic;}
    .help-form{color: gray;font-size: 11.5px;font-style: italic;}
    input[type='text']:focus, input[type='password']:focus, textarea:focus, select:focus{border: solid 1px green !important;}
    .btn-cancel:hover{background-color:#fff !important;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Puntos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_puntos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Puntos", "Puntos");?></h1>
</div>

<?php if($data["count_plan"] == 0): ?>
<div class="row">
    <div class="col-md-12">
        <p class="help-header">Aun no tienes ningun plan de puntos creado,crealo <a href="<?= site_url('puntos/crear');?>">Aquí</a> y empieza aumentar tus ganancias.</p>
    </div>
</div>
<?php endif; ?>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <div class="alert" style="color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
                Los puntos pueden acumularse para cualquier forma de pago a excepción de Saldo a favor y Nota crédito.
            </div>      
            <?php
                $message = $this->session->flashdata('message');
				if($message == 'Este cliente no se puede eliminar porque tiene facturas registradas'){
				 echo "<script> alert('Este cliente no se puede eliminar porque tiene facturas registradas'); </script>";
                 }
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                
                if(in_array("34", $permisos) || in_array("39", $permisos) || $is_admin == 't'):?>
                 <!--<a id="add-new-plan" class="btn btn-success"><?php echo custom_lang('sima_new_client', "Nuevo Plan de Puntos");?> </a> -->
                <a id="add-new-plan" data-tooltip="Nuevo Plan de Puntos">                        
                    <img alt="Nuevo Plan de Puntos" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                    
                </a>
            <?php endif;?>
            </div>
            <div class="col-md-6 btnizquierda">
            <!--
                 <div style="float: right;">
                     <a id="open-punto" class="btn default"><?php echo custom_lang('sima_new_client', "Valor del punto");?> </a>
                     <a  href="<?php echo site_url("puntos/clientes_plan_puntos/");?>" class="btn default"><?php echo custom_lang('sima_new_client', "Clientes Asignados al Plan de puntos");?> </a>
                     <a  id="open-porcompras"  class="btn default"></small><?php echo custom_lang('sima_new_client', "Compras");?> </a>
                 </div>-->
                <!--<div class="col-md-2 col-md-offset-6">
                    <a id="open-punto" data-tooltip="Valor del punto">                            
                        <img alt="Valor del punto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['valor_punto_verde']['original'] ?>">                                                           
                    </a>
                </div>-->
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("puntos/clientes_plan_puntos/");?>" data-tooltip="Clientes Asignados al Plan de Puntos">                            
                        <img alt="clientes plan punto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['cliente_plan_punto']['original'] ?>">                                                           
                    </a>
                </div>
                <!--<div class="col-md-2">
                    <a id="open-porcompras" data-tooltip="Compras">                            
                        <img alt="Compras" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['compra_puntos_verde']['original'] ?>">                                                           
                    </a>
                </div>-->
            </div>
        </diV>
    </diV>
</diV>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_client', "Planes de puntos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="clientesTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "N° puntos");?></th>
                                <th width="15%"><?php echo custom_lang('sima_contact', "Valor");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "IVA");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Tiempo de caducidad (Meses) ");?></th>
                                <th width="10%"><?php echo custom_lang('sima_nif', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                        <tfoot>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "N° puntos");?></th>
                                <th width="15%"><?php echo custom_lang('sima_contact', "Valor");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "IVA");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Tiempo de caducidad (Meses)");?></th>
                                <th width="10%"><?php echo custom_lang('sima_nif', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div id="dialog-edit-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header" style="padding:15px;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="myModalLabel" class="title-modal-points text-center">Crear plan de puntos </h4>
  </div>

  <div class="modal-body">
    <form id="edit-form"  action="<?php echo site_url('puntos/nuevo');?>" method="POST" >
        <input type="hidden"  name="id_puntos" id="id_puntos"  />
        <div class="col-md-12">
            <div class="form-group">
                <span class="danger">(*) Todos los campos son obligatorios</span>
            </div>
            <div class="form-group col-md-6 pl-0">
                <label for="nombre">
                    <span class="item-form-point">Nombre del plan</span>
                </label>
                <input type="text" class="form-control ml-0" name="nombre" id="nombre" placeholder="Ingrese nombre del plan" class="validate[required]">
                <span id="helpBlock" class="help-block help-form">Nombre único del plan</span>
            </div>
            <div class="form-group col-md-6 pl-0">
                <label for="valor">
                    <span class="item-form-point">Valor a comprar</span>
                </label>
                <!--<span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Este es el valor de compra para obsequiar puntos. " data-trigger="hover"></span>-->
                <input type="text" class="form-control ml-0" name="valor" id="valor" placeholder="Ej (10.000)" class="validate[required]">
                <span id="helpBlock" class="help-block help-form">Valor de compra para obsequiar puntos</span>
            </div>
            <div class="form-group">
                <label for="no_puntos">
                    <span class="item-form-point">Puntos obsequiados por compra</span>
                </label>
                <!--<span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Este es el total de puntos obsequiados por cada compra. (Ejemplo: 1 punto por cada $10.000 en compras) " data-trigger="hover"></span>-->
                <input type="text" class="form-control ml-0" name="no_puntos" id="no_puntos" placeholder="Ej (100)" class="validate[required]">
                <span id="helpBlock" class="help-block help-form">Este es el total de puntos obsequiados por cada compra. (Ej: 1 punto por cada $10.000 en compras) </span>
            </div>
            <div class="form-group col-md-6 pl-0">
                <label for="impuesto">
                    <span class="item-form-point">Impuesto</span>
                </label>
                <select name="impuesto" id="impuesto" class="form-control ml-0">
                    <option value="SI">si</option>
                    <option value="NO">no</option>
                </select>
                <span id="helpBlock" class="help-block help-form">Aplicar puntos con o sin impuesto</span>
            </div>
            <div class="form-group col-md-6 pr-0">
                <label for="tiempo_caducidad">
                    <span class="item-form-point">Tiempo de caducidad</span>
                </label>
                <!--<span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Este es el tiempo limite para redimir los puntos." data-trigger="hover"></span>-->
                <select name="tiempo_caducidad" id="tiempo_caducidad" class="form-control ml-0">
                    <?php for($i=1;$i<=12;$i++){ ?>
                        <option value="<?= $i; ?>"> <?= $i; ?> <?= ($i > 1)? 'meses' : 'mes' ?> </option>
                    <?php } ?>
                </select>
                <span id="helpBlock" class="help-block help-form">Este es el tiempo limite para redimir los puntos. </span>           
            </div>
            <div class="form-group">
                <label for="punto_redimir">
                    <span class="item-form-point">Valor por punto al momento de redimir</span>
                </label>
                <!--<span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Este es valor equivalente a un punto acumulado al momento de redimir. Ejemplo(Si un cliente tiene 8 puntos y el valor por punto es 500, podrá redimir $4.000 en compras) " data-trigger="hover"></span>-->
                <input type="text" class="form-control ml-0" name="punto_redimir" id="punto_redimir" placeholder="Ej (150)" class="validate[required]">
                <span id="helpBlock" class="help-block help-form">Este es valor equivalente a un punto acumulado al momento de redimir. </span>           
            </div>

            <div class="form-group">
                <label for="compras">
                    <span class="item-form-point">Compra minima para acumular puntos (Nuevos clientes)</span>
                </label>
                <input type="text" class="form-control ml-0" name="compras" id="compras" placeholder="Ej (15000)" class="validate[required]">
            </div>

            <div class="form-group pull-right">
                <button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                <button class="btn btn-success mr-0" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
            </div>
        </div>
   
      <!--<div class="span10">
          <input type="hidden"  name="id_puntos" id="id_puntos"  />
          <div class="row-form">
              <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre");?>:</div>
              <div class="span3"><input type="text" name="nombre" id="nombre" class="validate[required]"/></div>
          </div>
          <div class="row-form">
              <div class="span2"><?php echo custom_lang('sima_name_comercial', "N° puntos");?>:</div>
              <div class="span3"><input type="text" name="no_puntos" id="no_puntos" class="validate[required]"/></div>
          </div>
          <div class="row-form">
              <div class="span2"><?php echo custom_lang('sima_name_comercial', "Valor");?>:</div>
              <div class="span3"><input type="text" name="valor" id="valor" class="validate[required]"/></div>
          </div>
         

          <div class="row-form">
            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Impuesto");?>:</div>
            <div class="span3">
                <select name="impuesto" id="impuesto">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
          </div>    
          <div class="row-form">
              <div class="span2"><?php echo custom_lang('sima_name_compras', "Compras");?>:</div>
              <div class="span3"><input type="text" name="compras" id="compras" class="validate[required]"/></div>
          </div>
          <div class="row-form">
              <div class="span2"><?php echo custom_lang('sima_name_compras', "Valor del punto a redimir");?>:</div>
              <div class="span3"><input type="text" name="punto_redimir" id="punto_redimir" class="validate[required]"/></div>
          </div>
        </div>-->  
          
        <!--<div class="row-form">
            <div class="button-actions pull-right">
                <button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
            </div>
        </div>-->
        </div>
    </form>
</div>

<div id="dialog-punto-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header" style="padding:15px;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="myModalLabel"><?php echo custom_lang('sima_new_client', "Valor del punto");?></h4>
  </div>
  <div class="modal-body">
    <div class="span6">
       <form id="actualizar-form"  action="<?php echo site_url('puntos/actualizar_punto');?>" method="POST" >
          <div class="row-form">
            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Valor");?>:</div>
            <div class="span3"><input type="text" name="punto_val" id="punto_val" class="validate[required]"/></div>
          </div>
          <div class="row-form">
            <div class="button-actions pull-right">
                <button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button> 
            </div>    
          </div>
      </form>
    </div>
  </div>
</div>

<div id="dialog-porcompras-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header" style="padding:15px;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="myModalLabel"><?php echo custom_lang('sima_new_client', "Compras");?></h4>
  </div>
  <div class="modal-body">
    <div class="span6">
       <form id="actualizar-porcompras-form"  action="<?php echo site_url('puntos/actualizar_porcompras');?>" method="POST" >
              <div class="row-form">
                  <div class="span2"><?php echo custom_lang('sima_name_comercial', "Por compras de ");?>:</div>
                  <div class="span3"><input type="text" name="porcompras" id="porcompras" class="validate[required]"/></div>
              </div>
              <div class="row-form">
                  <div class="button-actions pull-right">
                     <button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                     <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button> 
                  </div>
              </div>
      </form>
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
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266773772?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
     
<script type="text/javascript">
    var oTable;
    $(document).ready(function(){
      oTable =  $('#clientesTable').dataTable( {
                "language": {
                    "url": url_spanish_datatable
                },
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("puntos/get_ajax_plan_puntos");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false,
                        "mRender": function ( data, type, row ) {
                      var buttons = "<div class='btnacciones'>";
                            <?php if(in_array('', $permisos) || $is_admin == 't'):?>
                                buttons += '<a  id="'+data+'" href="<?php echo site_url("puntos/actualizar/");?>/'+data+'" class="button default acciones" data-tooltip="Editar plan de puntos" ><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                             <?php endif;?>
                             <?php if(in_array('', $permisos) || $is_admin == 't'):?>
                                buttons += '<a href="<?php echo site_url("puntos/eliminar/");?>/'+data+'" data-tooltip="Eliminar Plan de puntos" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                             <?php endif;?>
                            buttons += "</div>";
                            return buttons;
                        }
                    }
                ]
        });


    $(document).on("submit", "#edit-form", function(e){
        nombre=$("#nombre").val().trim();
        no_puntos=$("#no_puntos").val().trim();
        valor=$("#valor").val().trim();
        punto_redimir=$("#punto_redimir").val().trim();
        compras=$("#compras").val().trim();

        if((nombre !="") &&(no_puntos !="") && (valor !="") && (punto_redimir !="") && (compras !="") ){

            var id='<?php echo $this->session->userdata('user_id') ?>';       
            var email='<?php echo $this->session->userdata('email') ?>';
            var nombre_empresa="<?php echo (!empty($data['datos_empresa'][0]->nombre_empresa))? $data['datos_empresa'][0]->nombre_empresa : 'No existe nombre' ?>";
             mixpanel.identify(id);

            mixpanel.track("Sistema de Puntos", {
                "$email": email,
                "$empresa": nombre_empresa,
            });
        }else{
            e.preventDefault();
            swal(
                'Error!',
                'Todos los datos son requeridos. verifique e intente nuevamente',
                'error'
            );
        }
    });	  

	  $('body').on('click','.editar',function(e){
        e.preventDefault();
        id = $(this).attr('id');
		$.ajax({
               url: "<?php echo site_url("puntos/get_datos_punto_plan")?>",
               data: {  id: id  },
                type: "POST",
                 success: function(response) {
                     let plan = response.plan[0];
                     let options = response.options;
                     let action = '<?= site_url("puntos/editar"); ?>';
                    $("#edit-form").attr('action',action);
                    $(".title-modal-points").html("Editar plan de puntos");
				    $("#nombre").val(plan.nombre);
				    $("#no_puntos").val(plan.puntos);
				    $("#valor").val(plan.valor);
                    $("#impuesto").val(plan.iva);
                    $("#tiempo_caducidad").val(plan.tiempo_caducidad);
                    $("#id_puntos").val(plan.id_puntos);
                    
                    $.each(options,function(i,e){
                         if(e.nombre_opcion == 'punto_valor'){
                             $("#punto_redimir").val(e.valor_opcion);
                         } 
                         if(e.nombre_opcion == 'por_compras_puntos_acumulados'){
                            $("#compras").val(e.valor_opcion);
                        } 
                     })

                    $("#dialog-edit-form").modal('show');
			    }

	    });
     });
     
        $("#add-new-plan").click(function(){
   
            let url_validate_plan = "<?= site_url('puntos/get_count_plan') ?>"; 
            $.get(url_validate_plan,function(data){
                let response = JSON.parse(data);
                console.log(response.total);
                if(response.total < 1){
                    location.href = "<?= site_url('puntos/crear') ?>";
                    /*$(".title-modal-points").html("Crear plan de puntos");
                    let action = '<?= site_url("puntos/nuevo"); ?>';
                    $("#edit-form").attr('action',action);
                    $("#dialog-edit-form").modal('show');*/
                }else{
                    swal(
                        'Error!',
                        'Ha superado el limite de plan de puntos permitidos',
                        'error'
                    );
                }
            })
        });


        $("#open-porcompras").click(function(){
		   $.ajax({
              url: "<?php echo site_url("puntos/get_datos_porcompras_valor")?>",
              success: function(response) {
                    $("#porcompras").val(response.valor_opcion);
                    $( "#dialog-porcompras-form" ).modal( "show" );
                }
	        });
        });

        $("#open-punto").click(function(){
		   $.ajax({
               url: "<?php echo site_url("puntos/get_datos_punto_valor")?>",
                 success: function(response) {
				    $("#punto_val").val(response.valor_opcion);
                    $( "#dialog-punto-form" ).modal( "show" );
			    }
	        });
        });
    });
</script>
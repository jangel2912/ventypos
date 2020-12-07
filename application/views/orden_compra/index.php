<style>
    .ui-dialog .ui-dialog-titlebar{
        background: #5cb85c !important;
    }
    .ui-dialog .ui-dialog-buttonpane button {
        border: 1px solid #5cb85c !important;
        background: #5cb85c !important;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
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
            <?php
                $message1 = $this->session->flashdata('message1');
                if(!empty($message1)):?>
                <div class="alert alert-error">
                    <?php echo $message1;?>
                </div>
            <?php endif; ?>

            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                if(in_array("11", $permisos) || $is_admin == 't'):?>
                    <!--<a href="<?php echo site_url("orden_compra/nuevo")?>" class="btn btn-success"> <?php echo custom_lang('sima_new_bill', "Nueva orden de compra");?></a>-->
                <div class="col-md-6">
                    <a href="<?php echo site_url("orden_compra/nuevo")?>" data-tooltip="Nueva Orden de Compra">                        
                        <img alt="Orden de Compra" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 
                    </a>                    
                </div>
                <?php endif;?>

            <!--<div style="float: right">
                <?php if(in_array("1028", $permisos) || $is_admin == 't'): ?>
                        <a href="<?php echo site_url("orden_compra/index/-1")?>" class="btn default"><?php echo custom_lang('sima_new_bill', "Órdenes Anuladas");?></a>
                <?php endif;?>                
            </div>-->
            <div class="col-md-6 btnizquierda">
                <?php if(in_array("1028", $permisos) || $is_admin == 't'): ?>
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("orden_compra/index/-1")?>" data-tooltip="Orden de Compra Anuladas">                            
                        <img alt="Orden de Compra Anuladas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ordenes_compra_anuladas']['original'] ?>">                                                           
                    </a>
                </div>
                <?php endif;?> 
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">        
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Listados de  Órdenes de Compras");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">

                        <thead>

                            <tr> 

                                <th ><?php echo custom_lang('sima_number', "N°");?></th>

                                <th ><?php echo custom_lang('sima_customer', "Cédula");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th ><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th ><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>

                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            

                        </tbody>

                        <tfoot>

                            <tr> 

                                <th><?php echo custom_lang('sima_number', "N°");?></th>

                                <th><?php echo custom_lang('sima_customer', "Cédula");?></th>

                                <th><?php echo custom_lang('sima_total_price', "Proveedor");?></th>

                                <th><?php echo custom_lang('sima_saldo', 'Fecha');?></th>

                                <th><?php echo custom_lang('sima_action', "Valor");?></th>

                                <th><?php echo custom_lang('sima_action', "Almacen");?></th>

                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            

        </div>

    </div>
<!--
    <div id="dialog-motivo-form" title="<?php echo custom_lang('sima_motivo_form', "Motivo de la Anulacion");?>">

            <div class="span6">

                <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                <form id="motivo-form" action="<?php echo site_url('orden_compra/anular');?>" method="POST" >

                    <input type="hidden" value="" name="venta_id" id="venta_id"/>

                    <div class="row-form">

                        <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>

                        <div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>

                    </div>

                </form>

            </div>

    </div>-->
        
        <!-- dsfdfg-->
    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-anular-form" aria-labelledby="ui-id-1" style="display: none; position: relative;"  title="Adicionar Cliente">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <!--<span id="ui-id-1" class="ui-dialog-title">Adicionar Cliente</span>-->
            <button class="ui-dialog-titlebar-close"></button>
        </div>
        <div id="dialog-anular-form" class="ui-dialog-content ui-widget-content">
            <form id="motivo-form" action="<?php echo site_url('orden_compra/anular');?>" method="POST" >

                <input type="hidden" value="" name="venta_id" id="venta_id"/>

                <div class="row-form">
                    <div class="span2"><?php echo custom_lang('sima_motivo', "Motivo");?>:</div>
                    <div class="span3"><textarea name="motivo" id="motivo" required></textarea></div>                
                </div>
                <div class="row-form">
                    <div class="span2"></div>
                    <div class="span3" id="errores" class="hidden"> </div>                
                </div>           
            </form>
        </div>     
    </div>

    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="dialog-anular-form" aria-labelledby="ui-id-1" style="display: none; position: relative;"  title="Adicionar Cliente">
        <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
            <!--<span id="ui-id-1" class="ui-dialog-title">Adicionar Cliente</span>-->
            <button class="ui-dialog-titlebar-close"></button>
        </div>
        <div id="dialog-codigobarra-form" class="ui-dialog-content ui-widget-content">
            <form id="columna-form" action="<?php echo site_url('productos/codigo_print_factura');?>" method="POST" >

                <input type="hidden" value="" name="orden_id" id="orden_id"/>
                <input type="hidden" value="orden" name="seccion" id="seccion"/>

                <div class="row-form">
                    <div class="span2"><?php echo custom_lang('columna', "Cantidad en Columna a Imprimir");?>:</div>
                    <div class="span3"><input type="number" name="columna" id="columna" min='1' max='2' value='1' required /></div>                
                </div>
                <div class="row-form">
                    <div class="span2"></div>
                    <div class="span3" id="errores" class="hidden"> </div>                
                </div>           
            </form>
        </div>     
    </div>
    <div class="social">
		<ul>
			<!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">   
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe  id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924427?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>    
         
    </div>


      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->


<script type="text/javascript">

    $(document).ready(function(){

      
        $('#ventasTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],

                "bProcessing": true,

                //Jeisson Rodriguez Deve
                //Add bServerSide for processing data
                
                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("orden_compra/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "bSort": false,

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {
                            //console.log(row);
                           var buttons = "<div class='btnacciones'>";
                            //console.log(row);
                            <?php if(in_array('1002', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("orden_compra/imprimir/");?>/'+data+'" class="button default btn-print acciones" data-tooltip="Imprimir"><div class="icon "><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                                //buttons += '<a href="<?php echo site_url("productos/codigo_print_factura") ?>/'+data+'/orden" class="button default btn-print acciones" data-tooltip="Imprimir códigos de barra"><div class="icon"><img alt="Código barra" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>"></div></a>';
                                buttons += '<a href="#"  id="'+data+'" class="button default acciones codigobarra" data-tooltip="Imprimir códigos de barra"><div class="icon"><img alt="Código barra" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['codigobarra']['original'] ?>"></div></a>';
                            <?php endif;?>
                            <?php if(in_array('1002', $permisos) || $is_admin == 't'):?>
                                //console.log("ver="+row[9]);
                                if(row[9] != 0){
                                    buttons += '<a class="button default successfull" data-tooltip="Orden de Compra Pagada" href="<?php echo site_url("orden_compra/pagos_servicio/");?>/'+data+'"> <div class="icon"><img alt="ordencomprapaga" src="<?php echo $this->session->userdata('new_imagenes')['ordencomprapaga']['cambio'] ?>" ></div></a>';
                                }else{
                                    buttons += '<a data-tooltip="Ver Pagos" href="<?php echo site_url("orden_compra/pagos_servicio/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="verpagos" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';
                                }                                
                            <?php endif;?>
	                            <?php if(in_array('1003', $permisos) || $is_admin == 't'):?>  
                                //console.log("afectada="+row[8]);                            
                              if(row[8] != 0){
                                    buttons += '<a class="button default successfull" data-tooltip="Orden de compra afectada"> <div class="icon"><img alt="ordenafectada" src="<?php echo $this->session->userdata('new_imagenes')['ordenafectada']['cambio'] ?>" ></div></a>';
                                }else{
                                    buttons += '<a data-tooltip="Afectar Inventario" href="<?php echo site_url("orden_compra/cargar_detalle_orden/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="ordenanofectada" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['ordenanofectada']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['ordenanofectada']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['ordenanofectada']['original'] ?>" ></div></a>';

                                }
                                
                            <?php endif;?>

                             <?php if(in_array('1028', $permisos) || $is_admin == 't'):?>                                                              
                                if(row[8] == 0){
                                    buttons += '<a href="<?php echo site_url('orden_compra/lv_anular')."/'+data+'" ;?> " id="'+data+'" class="button default acciones" data-tooltip="Devolver"><div class="icon"><img alt="devolver" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['devolver']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['devolver']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['devolver']['original'] ?>"></div></a>';
                                    buttons += '<a href="#" id="'+data+'" class="button red anular  acciones" data-tooltip="Anular" data-orden="'+data+'"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                                }
                            <?php endif;?>
                            
                            buttons += "</div>";
                            return buttons;

                        } 

                    }

                ],
                "oLanguage": {
                    "sSearch": "Clientes: ",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }

        });
        

        $(document).on('click','.codigobarra', function () {
            id=$(this).attr('id');
            if(id!=""){
                $("#orden_id").val(id);
            } 
            $("#dialog-codigobarra-form").dialog("open");           
        });

        $(document).on('click','.anular', function () {
            id=$(this).attr('id');
            if(id!=""){
                $("#venta_id").val(id);
            }            
            $("#dialog-anular-form").dialog("open");
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
                          

            $( "#dialog-anular-form" ).dialog({

                autoOpen: false,
                width: 400,
                draggable: false,
                modal: true,
                buttons: [ {
                    id: "dialogSave",
                    text: "Aceptar",
                    click: function() { 
                        motivo=$("#motivo").val().trim();                    
                        if(motivo!="")
                        {   
                            $("#errores").html("");
                            $("#dialogSave").button("option", "disabled", true);                            
                            $("#motivo-form").submit();
                        }
                        else{           
                            $("#errores").css("color", "red");                
                            $("#errores").html("<b>El campo motivo no puede estar vacío</b>");
                        }
                    }
                },
                {
                    id: "dialogCancel",
                    text: "Cancelar",
                    click: function() { 
                        $("#dialogSave").button("option", "disabled", false);                        
                        $("#errores").html("");
                        $(this).dialog("close"); 
                    }
                }],
                
                close: function() {
                    $('#motivo').val("");
                    $('#venta_id').val("");
                }
        });
        
        $( "#dialog-codigobarra-form" ).dialog({

                autoOpen: false,
                width: 400,
                draggable: false,
                modal: true,
                buttons: [ {
                    id: "dialogSave",
                    text: "Aceptar",
                    click: function() { 
                        motivo=$("#columna").val().trim();                                       
                        if(motivo!="")
                        {   
                            $("#errores").html("");
                            $("#dialogSave").button("option", "disabled", true);                            
                            $("#columna-form").submit();                            
                        }
                        else{           
                            $("#errores").css("color", "red");                
                            $("#errores").html("<b>El campo cantidad de columnas no puede estar vacío</b>");
                        }
                    }
                },
                {
                    id: "dialogCancel",
                    text: "Cancelar",
                    click: function() { 
                        $("#dialogSave").button("option", "disabled", false);                        
                        $("#errores").html("");
                        $(this).dialog("close"); 
                    }
                }],
               
                close: function() {
                    $('#columna').val("");
                    $('#orden_id').val("");
                }
		});

    });

</script>
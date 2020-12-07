<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/dataTables.bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/export/css/buttons.dataTables.min.css'); ?>">-->
<style>


    hr{       
        background-color: #ddd;
        height: 1px;
        margin:2px;
        margin-bottom: 10px;
    }    
    td,th{
        padding: 0px;
    }
    .titlePanel{
        color: #777;
        font-size: 14px;
        text-align: center;
    }
    #cargando {
        background-color: #fff;
        position:absolute;
        opacity: 0.2;
        filter: alpha(opacity=20); /* For IE8 and earlier */
    }
    .well{
        display: flex;
        padding: 10px;
    }
    .borderPanel{
    }
    .well{
        background-color:#F9F9F9
    }
    .infoImage{
        line-height: 12px;
        color:#999;
    }
    .paneles .row-fluid{
        margin-bottom: 5px;
    }

    .paging_full_numbers{
        margin-bottom: 40px;
    }
    .contenedorTabla{
        padding-bottom: 80px;
    }

    .paginate_button{
        margin: 10px 0px 60px 0px;
    }
    .img-polaroid{
        height:30px;
        width:30px;
    }
    
    .btnacciones{
        width:10rem;
    }

</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Plan separe" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_plan_separe']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Plan Separe", "Plan Separe");?></h1>
</div>

<!--<a href="<?php echo site_url("ventas_separe/plan_separe_anulado")?>" class="btn btn-success"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_bill', "Plan separe anulados");?></a>-->

<!--
<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php

            $message = $this->session->flashdata('message');
            $message1 = $this->session->flashdata('message1');

            if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; 
            
            if(!empty($message1)):?>
                <div class="alert alert-error">
                    <?php echo $message1;?>
                </div>
            <?php endif; ?>

            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado Plan Separe");?></h2>
            </div>
        </div>

    </div>
</div>-->

    <div class="row-fluid">    
        <div class="col-md-12">
            <div class="block">
               <?php
                $message = $this->session->flashdata('message');
                $message1 = $this->session->flashdata('message1');

                if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>
                <?php endif; 
                
                if(!empty($message1)):?>
                    <div class="alert alert-error">
                        <?php echo $message1;?>
                    </div>
                <?php endif; ?>

                <?php 
                    $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                    if(in_array("11", $permisos) || $is_admin == 't'):?>
                    <div class="col-md-6">
                        <a href="<?php echo site_url("ventas/nuevo")?>" data-tooltip="Nueva Venta">                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a>                    
                    </div>
                <?php endif;?>

                <div class="col-md-6 btnderecha">                    
                    <div class="col-md-2 col-md-offset-8">
                        <a data-tooltip="Exportar Excel" href="<?php echo site_url('ventas_separe/ex_ventas_separe');?>">                            
                            <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                           
                        </a> 
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo site_url("ventas_separe/plan_separe_anulado")?>" data-tooltip="Plan Separe Anuladas">                            
                            <img alt="Plan Separe Anuladas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ps_anuladas_verde']['original'] ?>">                                                           
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
                    <h2><?php echo custom_lang('sima_outstanding_all', "Listado Plan Separe");?></h2>
                </div>
                <div class="data-fluid">
                    <div id="contentDataList" class="contenedorTabla">
                        <table id="testTable" class="table" width="100%">
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
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924473?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>      




<script src="<?php echo base_url('public/export/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/jszip.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/pdfmake.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/vfs_fonts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.html5.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/buttons.print.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/export/js/dataTables.buttons.min.js'); ?>" type="text/javascript"></script>


<script>
      var anularDialog = anularDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
            '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
            '<div class="modal-header"><?php echo custom_lang('sima_motivo_form', "Motivo de la Anulación");?></div>' +
            '<div class="modal-body">' +
                '<p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>'+
                '<form id="envio-form" action="<?php echo site_url('ventas_separe/eliminar/');?>" method="POST" >'+
                    '<input type="hidden" name="venta_id_ven" id="venta_id_ven"/>'+
                    '<div class="row-form">'+
                        '<div class="span5">Escoja si desea devolver los abonos realizados:   </div>'+
                        '<div class="span5">'+
						'<select name="dev">'+
						  '<option value="si">Devolver</option>'+
						  '<option value="no">No Devolver</option>'+
						'</select>'+
					    '</div>'+
                    '</div>'+
                    '<div  align="center" class="row-form">'+
                        '<input type="button" value="Cancelar"  id="cancelar" class="btn btn-default" data-dismiss="modal"/> '+
                        '<button type="submit" class="btn btn-success" id="anular_plan" >Anular plan</button>'+
                    '</div>'+
                '</form>'+
            '</div>' +
            '</div></div></div>');

            return {
            /**
                * Opens our dialog
                * @param message Custom message
                * @param options Custom options:
                * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
                * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
                */
            show: function (id, options) {
                // Assigning defaults
                if (typeof options === 'undefined') {
                    options = {};
                }
                /*if (typeof message === 'undefined') {
                    message = 'Loading';
                }*/
                var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                });
                //$dialog.find('h3').text(message);
                $dialog.find('#venta_id_ven').val(id);
               
                $dialog.modal();
            },
            /**
                * Closes dialog
                */
            hide: function () {
                $dialog.modal('hide');
            }
            };

            })(jQuery); 


//===========================================================================
//  DATATABLE
//  
//  Solo recibe OBJETOS llavascript dentro de un array =>   [ { id : "1" } , { id : "2" } ]
//===========================================================================
    var table;
    function setDataTable(obj) {
        
        var bold = function (data){
            return "<strong>"+data+"</strong>";
        }
        
        var imagen = function (data){
            return '<img class="img-polaroid" src="<?php echo base_url("/uploads"); ?>/'+data+'">';
        }
		
        
        var detalle = function(data){
            var buttons = "<div class='btnacciones'>";
            buttons += '<a data-tooltip="Imprimir" href="<?php echo site_url("ventas_separe/imprimir/");?>/'+data+'/copia" target="_blank" class="button default btn-print acciones" title="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a><a title="Ver detalle" href="<?php echo site_url("ventas_separe/detalle"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a>';
            <?php if(in_array(13,$permisos) || $is_admin == 't' ){ ?>
                buttons+= '<a data-tooltip="Anular" title="Anular" id="'+data+'"  class="button red envio acciones"><div class="icon"><img alt="Anular" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
            <?php } ?>
            buttons += "</div>";
           return  buttons; 
            //'<a href="<?php echo site_url("ventas_separe/imprimir/");?>/'+data+'/copia" target="_blank" class="button default btn-print acciones" title="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a><a title="Ver detalle" href="<?php echo site_url("ventas_separe/detalle"); ?>/'+data+'" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['verpagos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['verpagos']['original'] ?>" ></div></a><a title="Anular"  id="'+data+'"  class="button red envio acciones"><div class="icon"><img alt="Anular" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
        }
        var estado = function(data){
            if(data==2)
                return  '<span class="label label-success"> Facturado </span>';
            if(data==0)
                return  '<span class="label label-warning"> Separado </span>';
        }        
        

        table = $('#testTable').DataTable({
            
            data: obj,            
            columns: [
                { data: "id", title : "Consecutivo"},
                { data: "factura", title : "Factura", render: bold },
                { data: "usuario_id", title : "Usuario"},
                { data: "nombre_comercial", title : "Cliente"},
                { data: "nif_cif", title : "Identificación"},
                { data: "fecha", title : "Fecha"},
                { data: "total_venta", title : "Valor"},
                { data: "almacen_nombre", title : "Almacén"},
                { data: "fecha_vencimiento", title : "Fecha de Vencimiento"},
                { data: "tipo_factura", title : "Tipo"},
                { data: "total_abonos", title : "Total abonos"},
                { data: "estado", title : "Estado",render: estado},
                { data: "id", title : "Acciones",render: detalle}
            ],            

            order: [[ 1, "desc" ]], // Orden inicial [ indiceColumna, asc o desc ]
            pageLength: 5,
            sPaginationType: "full_numbers",
            aLengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "Todos"]],            
            bDestroy: 'Blfrtip',
            dom: 'Blfrtip',
            buttons: [
              //   { extend: 'copy', text: 'Portapapeles'},
              //  { extend: 'csv', text: 'CSV'},
              //   { extend: 'excel', text: 'Excel'},
             //    { extend: 'pdf', text: 'PDF'},                
               //  { extend: 'print', text: 'Imprimir'}
            ],
            language: {
                url: "<?php echo base_url('public/export'); ?>/Spanish.json"
            }
        }
        );

    }


//===========================================================================
//===========================================================================
//
//      AJAX
//
//===========================================================================
//===========================================================================


    //===========================================================================
    // Enviamos un string con los atributos
    // Nos retornará un String JSON
    // Callback -> actualizar datos DataTable
    //===========================================================================
    function getAjaxFacturas() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ventas_separe/getAjaxFacturas'); ?>",            
            dataType: 'text',
            success: function (response) {
                    
                console.log(response);
                var obj = $.parseJSON(response);//Convertimos stringJson a un objeto javascript
                setDataTable(obj);
                
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }

//===========================================================================
//===========================================================================
//
//      INIT
//
//===========================================================================
//===========================================================================
    $(document).ready(function (e) {
    
        getAjaxFacturas();
  
              $('body').on('click','.envio',function(e){

                e.preventDefault();

                //$("#venta_id_ven").val($(this).attr('id'));
                anularDialog.show($(this).attr('id'));
                //$( "#dialog-envio-form" ).dialog( "open" );

            });
  

	    $( "#dialog-envio-form" ).dialog({

			autoOpen: false,

			//height: 400,

			width: 620,

			modal: true,

			buttons: {

				"Anular plan": function() {

                     $("#envio-form").submit();
                
				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() {                               
                
			}

		});

 $('.site-footer').hide();

        $(document).on("submit", "#envio-form", function(e){            
            $("#anular_plan").prop('disabled',true);           
        });
		    
    });



</script>
</div></div>

    <div id="dialog-envio-form" title="<?php echo custom_lang('sima_motivo_form', "Anular plan separe");?>">

            <div class="span6">


                <form id="envio-form" action="<?php echo site_url('ventas_separe/eliminar/');?>" method="POST" >

                    <input type="hidden" name="venta_id_ven" id="venta_id_ven"/>

                    <div class="row-form">
                        <div class="span5">Escoja si desea devolver los abonos realizados:   </div>
						

                        <div class="span5">
						<select name="dev">
						  <option value="si">Devolver</option>
						  <option value="no">No Devolver</option>
						</select>
					    </div>

                    </div>

                </form>

            </div>

    </div>
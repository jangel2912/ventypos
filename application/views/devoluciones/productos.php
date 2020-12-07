
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<div class="page-header">    
    <div class="icon">
        <img title="ventas" alt="ventas" class="iconimg" src="<?php echo base_url('/uploads/iconos/Gris/'); ?>/icono_gris-21.svg">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Devoluciones", "Devoluciones");?></h1>
</div>
<!--
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_product_list', "Listado de productos");?></h2>     
    </div>
</div>-->

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
            ?>
            <form id="devolucion" action="<?php echo site_url("devoluciones/devolver/".$data['factura_id']); ?>" method="POST">
                <?php if($data["estado_caja"] == "cerrada"){ ?>
                    <div style="color:#d32f2f; font-size:12px;">Para realizar una devolución debe tener una caja abierta, haga clic <a target="_blank" href="<?php echo site_url('caja/apertura'); ?>">aquí</a> para aperturar caja </div>
                <?php }else{ ?>
                <div style="padding: 5px 0">
                    <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                </div>
                <?php }?>
                <input type="hidden" name="tipoDevolucion" id="tipoDevolucion" value="0">
                <div class="head blue">                   
                    <h2><?php echo custom_lang('sima_all_product', "Listado de Productos");?></h2>
                </div>

                <div class="data-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">

                            <thead>
                                <tr>
                                    <th width="10%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                    <th width="15%"><?php echo custom_lang('sima_codigo', "Imei/Serial");?></th>                                    
                                    <th width="15%"><?php echo custom_lang('price_of_purchase', "Precio de venta");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_price', "Descuento");?></th>
                                    <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                    <th width="5%"><?php echo custom_lang('sima_unidad_compra', "Unidades de compra");?></th>
                                    <th width="5%"><?php echo custom_lang('sima_unidad_devolucion', "Unidades a devolver");?></th>
                                    <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                        <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                    <?php endif;?>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                            <tfoot>
                               <tr>
                                    <th width="10%"><?php echo custom_lang('sima_image', "Imagen");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                    <th width="15%"><?php echo custom_lang('sima_codigo', "Imei/Serial");?></th>                                    
                                    <th width="15%"><?php echo custom_lang('price_of_purchase', "Precio de venta");?></th>
                                    <th width="10%"><?php echo custom_lang('sima_price', "Descuento");?></th>
                                    <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                    <th width="5%"><?php echo custom_lang('sima_unidad_compra', "Unidades de compra");?></th>
                                    <th width="5%"><?php echo custom_lang('sima_unidad_devolucion', "Unidades a devolver");?></th>
                                    <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                        <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                    <?php endif;?>
                                </tr>
                            </tfoot>

                        </table>

                </div>
                    </form>

            </div>

            

        </div>

    </div>
    
<div id="dialog-confirm" title="Empty the recycle bin?">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>¿Desea generar nota credito?<br>En el caso de que no solo se generara la devolución</p>
</div>


<div class="social">
		<ul>			
			<li>
                <a href="#myModalvideovimeo" data-toggle="modal">
                    <img alt="video" src="<?php echo base_url('/uploads/iconos/Blanco/'); ?>/icono_blanco-35.svg">
                </a>
            </li>
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="modal fade">
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266923920?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
     



<script type="text/javascript">
    //ocultar el dialogo al cargar la pagina
    $( function() {
        $( "#dialog-confirm" ).dialog({
            autoOpen: false
        });
    });
    
    $(document).on('submit','form#devolucion',function(event){
        $("button#guardar").attr("disabled","disable");
        event.preventDefault();
        var $this = $(this),
            desactivados = 0;
        
        /*if(confirm("¿Desea generar nota credito?"))
        {
            $('input#tipoDevolucion').val(1);
        }*/
        
        

        $('#productosTable tbody tr').each(function(i,e){
            if(!$(e).find('input[type=checkbox]').prop('checked'))
            {
                $(e).find('input[name*=unidades]').attr("disabled","disabled");
                $(e).find('input[name*=precio]').attr("disabled","disabled");
                desactivados++;
            }
        });
        
        if($('input[type=checkbox]').length == desactivados)
        {
            alert("Debe seleccionar al menos un producto a devolver");
            $("#productosTable input").each(function(i,e){
               $(e).removeAttr("disabled");
            });
            $("button#guardar").removeAttr("disabled");
            return false;
        }

        var cuantosProductos = $('#productosTable tbody').find("tr").length;
        if(cuantosProductos == 0)
        {
            $("button#guardar").attr("disabled","disable");

            return false;
        }
        /*$( "#dialog-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            autoOpen: true,
            buttons: {
                "Si": function() {
                    $('input#tipoDevolucion').val(1);
                    $( this ).dialog( "close" );
                    $.post
                    (
                        $this.attr("action"),
                        $this.serialize(),
                        function(data)
                        {
                            if(data.resp == "1")
                            {
                                alert(data.mensaje);
                                location.href = "<?php echo site_url("devoluciones/index") ?>";
                            }
                        },'json'
                    );
                },
                "No": function() {
                    $( this ).dialog( "close" );
                    $.post
                    (
                        $this.attr("action"),
                        $this.serialize(),
                        function(data)
                        {
                            if(data.resp == "1")
                            {
                                alert(data.mensaje);
                                location.href = "<?php echo site_url("devoluciones/index") ?>";
                            }
                        },'json'
                    );
                }
            }
        });*/
        $('input#tipoDevolucion').val(1);
        $.post
        (
            $this.attr("action"),
            $this.serialize(),
            function(data)
            {
                
                if(data.resp == "1")
                {
                    alert(data.mensaje);
                    location.href = "<?php echo site_url("devoluciones/index") ?>";
                }else{
                    if(data.resp == "0"){
                        alert(data.mensaje);  
                        location.href = "<?php echo site_url("ventas/index") ?>";
                    }
                }
            },'json'
        );
    });


    $(document).ready(function(){
        var cont = 0;
        $('#productosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("devoluciones/obtener_productos/".$data['factura_id']);?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 0 ], "bSearchable": true, 

                        "mRender": function ( data, type, row ) {
                            var url = '<?php echo base_url("uploads");?>';
                            image_name = 'default.png'; 
                            if(data != ""){
                                image_name = data;
                            }   

                            return "<img class='img-polaroid' height='30px' width='30px' src='"+url+"/"+image_name+"'/>";
                        } 

                    },

                    {  "bSortable": false, "aTargets": [ 8 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                            
                            var buttons = '';
                                cont++;
                                <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                    if(row[3] == 'No aplica'){
                                        row[3] = 0;
                                    }
                                    var id = row[12]+'--'+row[9]+'--'+row[3];
                                    buttons += '<input type="number" name="unidades['+id+']" value="'+data+'"  minlength=1 max="'+data+'" maxlength="'+data+'" style="width: 50px">';
                                    buttons += '<input type="hidden" name="precio['+id+']" value="'+row[4]+'">';
                                    buttons += '<input type="hidden" name="imei['+id+']" value="'+row[3]+'">';
                                               
                                 <?php endif;?>

                            return buttons;

                        } 

                    },

                    {  
                    
                        "bSortable": false, "aTargets": [  <?php echo 9; ?> ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                            var disabled="", status_text = '';
                            var id = row[12]+'--'+row[9]+'--'+row[3];
                            var buttons = '';
                                
                                <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>

                                    buttons += '<input type="checkbox" name="productos['+id+']" '+disabled+'/> '+status_text;

                                 <?php endif;?>

                                  
        

                            return buttons;

                        } 

                    }
 
                ]

        });
       

    });
    
    _DB = function () {
        // CODE
    }
    _DB.prototype.Producto = function() {
        var DB = this;
        
        return {
            eliminar: function(id) {
                $.post( "<?php echo site_url();?>/ventas/facturas", function( data ) {
                    data = JSON.parse(data);

                    for (var iData in data) {
                        if ( data[iData].producto_id == id ) {
                            alert("Este producto está asociado con una venta y por lo tanto no se puede eliminar.")
                            return false;
                        };
                    }

                    if(confirm('<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar este producto?");?>')){
                        return window.location = '<?php echo site_url("productos/eliminar/");?>/'+id;
                    }else{
                        return false;
                    }
                });
            }
        };
    }

    var DB = new _DB();

    eliminarProducto = function (id) {
        DB.Producto().eliminar(id);
    }
    
    
</script>
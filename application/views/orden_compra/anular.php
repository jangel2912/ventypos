<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_product_list', "Anular Orden de Compra");?></h2>                                          

    </div>

</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
            ?>

             <form id="anulacion" action="<?php echo site_url("orden_compra/devolver_productos_by_orden/".$data['orden']); ?>" method="POST">
                        <div style="padding: 5px 0">
                            <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                        </div>
                        <input type="hidden" name="tipoDevolucion" id="tipoDevolucion" value="0">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_product', "Todos los productos");?></h2>
            </div>

                <div class="data-fluid">
                            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                                <thead>
                                    <tr>
                                        <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>                                        
                                        <th width="15%"><?php echo custom_lang('price_of_purchase', "Precio de venta");?></th>                            
                                        <th width="10%"><?php echo custom_lang('sima_price', "Descuento");?></th>
                                        <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_compra', "Unidades de compra");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_devolucion', "Unidades a devolver");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_stock', "Unidades en stock actual");?></th>
                                    <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                        <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                                    <?php endif;?>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                                <tfoot>
                                    <tr>
                                        <th width="10%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>                                        
                                        <th width="15%"><?php echo custom_lang('price_of_purchase', "Precio de venta");?></th>                            
                                        <th width="10%"><?php echo custom_lang('sima_price', "Descuento");?></th>
                                        <th width="15%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_compra', "Unidades de compra");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_devolucion', "Unidades a devolver");?></th>
                                        <th width="10%"><?php echo custom_lang('sima_unidad_stock', "Unidades en stock actual");?></th>
                                    <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                        <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
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
<script type="text/javascript">
    //ocultar el dialogo al cargar la pagina
    $( function() {
        $( "#dialog-confirm" ).dialog({
            autoOpen: false
        });
    });
    
    $(document).on('submit','form#anulacion',function(event){
        $("button#guardar").attr("disabled","disable");
        event.preventDefault();
        var $this = $(this),
            desactivados = 0;

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
     
        $('input#tipoDevolucion').val(1);
        $.post
        (
            $this.attr("action"),
            $this.serialize(),
            function(data)
            {
                if(data.resp == "1")
                {
                    alert(data.message);
                    location.href = "<?php echo site_url("orden_compra/index") ?>";
                }
            },'json'
        );
    });


    $(document).ready(function(){

        $('#productosTable').dataTable( {

                "bProcessing": true,

                "bServerSide": false,

                "sAjaxSource": "<?php echo site_url("orden_compra/obtener_productos/".$data['orden']);?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [
                    {  "bSortable": false, "aTargets": [6], "bSearchable": true, 

                        "mRender": function ( data, type, row ) {                           
                            var buttons = '';
                            
                                <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                    if(parseFloat(row[7])>parseFloat(row[5])){
                                        
                                        buttons += '<input type="number" name="unidades['+row[6]+']" value="'+row[5]+'" min=1 minlength=1 max="'+row[5]+'" maxlength="'+row[5]+'" style="width: 50px">';
                                        buttons += '<input type="hidden" name="precio['+row[6]+']" value="'+row[5]+'">';
                                    }
                                    else{                                       
                                        min=(row[7]>0) ? 1:0;
                                        desabilitar=(row[7]>0) ? '':'disabled';

                                        buttons += '<input type="number" '+desabilitar+' name="unidades['+row[6]+']" value="'+row[7]+'" min="'+min+'" minlength="'+row[7]+'" max="'+row[7]+'" maxlength="'+row[7]+'" style="width: 50px">';
                                        buttons += '<input type="hidden" name="precio['+row[6]+']" value="'+row[5]+'">';        
                                    }
                                 <?php endif;?>

                            return buttons;

                        } 

                    },

                    {  
                    
                        "bSortable": false, "aTargets": [8], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {                                                       
                            disabled=(row[7]>0) ? '':'disabled';
                            status_text = '';
                            var buttons = '';
                                <?php if(in_array('1024', $permisos) || $is_admin == 't'):?>
                                    buttons += '<input type="checkbox" name="productos['+row[6]+']" '+disabled+'/> '+status_text;
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
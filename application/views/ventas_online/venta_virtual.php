<div class="page-header">    
    <div class="icon">
        <img alt="ventas_online" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_venta_online']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas Online", "Ventas Online");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
            $message = $this->session->flashdata('message');
            if (!empty($message)):
                ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; 
            $permisos = $this->session->userdata('permisos');
            $is_admin = $this->session->userdata('is_admin');
            ?>
            <?php if (in_array("68", $permisos) || $is_admin == 't'): ?>
               <!-- <a href="<?php echo site_url("ventas_online/ventas_anuladas") ?>" class="btn btn-success "><small class="ico-sale icon-white"></small> <?php echo custom_lang('sima_new_bill', "Solicitudes de Ventas Anuladas"); ?></a>-->
                <div class="col-md-1 col-md-offset-10 btnderecha">
                    <a href="<?php echo site_url("ventas_online/ventas_anuladas")?>" data-tooltip="Ventas Online Anuladas">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ventas_online_anuladas']['original'] ?>">                         
                    </a>                    
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">            
            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Venta Online"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventas_onlineTable">
                    <thead>
                        <tr> 
                            <th width="7%"><?php echo custom_lang('ventaonline_number', "N°."); ?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_name', "Nombre"); ?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_customer_dni', "Cédula"); ?></th>
                            <th width="15%"><?php echo custom_lang('ventaonline_email', "Email"); ?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_fecha', 'Fecha'); ?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_valor', "Valor"); ?></th>
                            <th width="13%"><?php echo custom_lang('ventaonline_estado', "Estado"); ?></th>
                            <th ><?php echo custom_lang('ventaonline_estado', "Pago"); ?></th>
                            <th  width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr> 
                            <th width="7%"><?php echo custom_lang('ventaonline_number', "N°."); ?></th>
                            <th width="18%"><?php echo custom_lang('ventaonline_name', "Nombre"); ?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_customer_dni', "Cédula"); ?></th>
                            <th width="15%"><?php echo custom_lang('ventaonline_email', "Email"); ?></th>
                            <th width="10%"><?php echo custom_lang('ventaonline_fecha', 'Fecha'); ?></th>
                            <th width="12%"><?php echo custom_lang('ventaonline_valor', "Valor"); ?></th>
                            <th width="13%"><?php echo custom_lang('ventaonline_estado', "Estado"); ?></th>
                            <th ><?php echo custom_lang('ventaonline_estado', "Pago"); ?></th>
                            <th  width="15%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style >

    .tabhead{

        background-color: #DDD;

        //border: 1px solid #393b3b !important;

        text-align: center;

    }

    .tabp{

        width: 220px;

        border: 1px solid #DDD;

    }

    .tabpr{

        width: 150px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .tabc{

        width: 100px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .tabcm{

        width: 50px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .tabt{

        width: 150px;

        text-align: center;

        border: 1px solid #DDD;

    }

    .oscuro{

        font-weight: bold;

    }

    .oscurorojo{

        font-weight: bold;

        color: red;



    }

    .modal .modal-content{
        width: 700px !important;
        overflow: auto !important;
    }


</style>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title" id="myModalLabel">&nbsp; Detalles de la Solicitud de Venta Online #<span id="det_id"></span>  </h4>

    </div>

    <div class="modal-body">

        <div class="row-fluid">
            <div id="det_fecha_estado" class="span8" style="text-align: right; position: absolute; margin-left: 0px !important"></div>

            <br>

            <div class="span12" style="margin-left: 0px !important">

                <h6>Datos del Cliente:</h6>

                <div id="det_nombre" class="span4"></div>

                <div id="det_cedula" class="span4"></div>

                <div id="det_email" class="span4"></div>

            </div>
        </div>
        <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <div id="det_telefono" class="span4"></div>

                <div id="det_cpostal" class="span4"></div>

                <div id="det_movil" class="span4"></div>
            </div>
        </div>
        <div class="row-fluid">
              <div id="det_direccion" class="span4"></div>

                <div id="det_fax" class="span4"></div>

                
        </div>
        <div class="row-fluid">
            <div id="det_notas" class="span12"></div>
        </div>
        <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <h6>Relaci&oacute;n de productos:</h6>
                <table id="det_productos" style="width: 100%;">
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12" style="margin-left: 0px !important">
                <div class="row-fluid">
                    <br>
                    <div id="det_total" class="span3 pull-right" style="text-align: right"> Total:  </div>
                    <div id="det_impuesto" class="span3 pull-right" style="text-align: right"> Impuesto:  </div>
                    <div id="det_subtotal" class="span3 pull-right" style="text-align: right"> Subtotal:  </div>
                </div>
            </div>
        </div>



    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>



    </div>

    <!-- /.modal-dialog -->

</div>





<div class="modal" id="facturarModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">&nbsp; Facturar Venta Online #<span id="atend_id"></span>  </h4>
            </div>
            <div class="modal-body">
                <div id="atend_fecha" class="span6" style="text-align: right; position: absolute; margin-left: 0px !important"></div>
                <div class="span6" style="margin-left: 0px !important">
                    <h6>Datos del cliente:</h6>
                    <table id="atend_cliente"></table>
                </div>
                <div class="span6" style="margin-left: 0px !important"> &nbsp; </div>
                <div class="span6" style="margin-left: 0px !important">
                    <div class="span2"> </div>
                    <div id="atend_nombre" class="span3" style="text-align: left"> Nombre:  </div>
                    <div class="span2"> </div>
                    <div id="atend_correo" class="span3" style="text-align: left"> Correo:  </div>
                    <div class="span2"> </div>
                    <div id="atend_telefono" class="span3" style="text-align: left"> Telefono:  </div>
                    <div class="span2"> </div>
                    <div id="atend_movil" class="span3" style="text-align: left"> Celular:  </div>
                    <div class="span2"> </div>
                    <div id="atend_direccion" class="span3" style="text-align: left"> Dirección:  </div>
                    <div class="span2"> </div>
                    <div id="atend_ciudad" class="span3" style="text-align: left"> Ciudad:  </div>
                </div>
                <div class="span6" style="margin-left: 0px !important">
                    <h6>Productos:</h6>
                    <table id="atend_productos"></table>
                </div>
                <div class="span6" style="margin-left: 0px !important"> &nbsp; </div>
                <div class="span6" style="margin-left: 0px !important">
                    <div class="span2"> </div>
                    <div id="atend_subtotal" class="span3" style="text-align: left"> Subtotal:  </div>
                    <div class="span2"> </div>
                    <div id="atend_impuesto" class="span3" style="text-align: left"> Impuesto:  </div>
                    <div class="span2"> </div>
                    <div id="atend_total" class="span3" style="text-align: left"> Total:  </div>
                    <div id="descuento" style="display: none">
                        <div class="span2"> </div>
                        <div id="atend_descuento" class="span3" style="text-align: left; color: #0066cc "> Descuento:  </div> 
                        <div class="span2"> </div>
                        <div id="atend_descuento_imp" class="span3" style="text-align: left; color: #0066cc"> Descuento de Impuesto:  </div> 
                        <div class="span2"> </div>
                        <div id="atend_total_final" class="span3" style="text-align: left; color: #0066cc"> Nuevo Total:  </div>
                    </div>
                </div>
                <div class="span2">
                    Forma de pago : <select id="forma_pago"></select>
                </div>
                <div class="span6">
                    <br>
                    <hr>
                    <center>
                        <span id="atend_msg"></span>
                        <br>
                        <input type="hidden" id="conf_id" name="conf_id" value=""/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="facturarVenta">Confirmar</button>
                        
                    </center>
                    <br>
                </div>
            </div>
    </div>
    <!-- /.modal-dialog -->
</div>



<script type="text/javascript">

var almacenes_tienda =[];

    $(document).ready(function () {

         
        $(document).on('click','#facturarVenta',function(event)
        {
            $.post
            (
                "<?php echo site_url("ventas_online/facturar")?>",
                {
                    'id':$('#facturarModal').find('#conf_id').val(),
                    'pago':$('#facturarModal').find('#forma_pago option:selected').val(),
                },
                function(data)
                {
                    if(data.json == 1)
                    {
                        alert("Se ha facturado correctamente la venta online. \n Factura:"+data.factura);
                        $('#facturarModal').modal('hide');
                        
                    }
                },'json'
            )
        });


        $('body').on('click', '#btn_confirm', function (e) {

            $('#conf_id').val();

            $('#venta_online_atender').submit();

        });



        $('#ventas_onlineTable').dataTable({
            "aaSorting": [[0, "desc"]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("ventas_online/get_ajax_data"); ?>",
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10, "aLengthMenu": [5, 10, 25, 50, 100],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [8], "bSearchable": false,
                    "mRender": function (data, type, row) {
                        var buttons = "<div class='btnacciones'>";
                        <?php if (in_array('57', $permisos) || $is_admin == 't'){ ?>
                            buttons += '<a style="cursor:pointer;"  id="d_' + row[0] + '" data-toggle="modal" data-target="#myModal" data-tooltip="Detalles" class="button default detall acciones" title="Ver Detalles"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                        <?php } ?>
                            if(row[6] == 'Atendida') {
                            <?php if (in_array('11', $permisos) || $is_admin == 't'){ ?>
                                        buttons += '<a href="" id="a_' + row[0] + '" data-toggle="modal" data-target="#facturarModal" data-tooltip="Facturar" class="button default facturar acciones" title="Facturar"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['facturar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>"></div></a>';
                            <?php } ?>
                            }else if(row[6] =='Pendiente pago' || row[6]=='Aprobada' || row[6] == 'Facturada'){
                                buttons += '<a href="<?php echo site_url('ventas/imprimir/') ?>/'+row[10]+'/copia" style="cursor:pointer;"  id="a_' + row[0] + '" data-tooltip="Imprimir" class="button default btn-print confirmar acciones" title="Ver factura" target="_blank"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                            }

                            buttons += '<a href="" id="' + row[0] + '" class="button red anular acciones" data-tooltip="Eliminar" title="Anular"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                        buttons += "</div>";
                        return buttons;
                    }
                }
            ]
        });
        
                
        $('body').on('click', '.detall', function (e) {

            e.preventDefault();

            var ids = $(this).attr('id');

            var id = ids.split('_');

            var url = "<?php echo site_url('ventas_online/get_detalle_solicitud/') ?>";

            $.getJSON(
                    url + '/' + id[1],
                    function (data) {

                        console.log(data);

                        $('#det_nombre').html("Nombre: " + data.nombre);

                        $('#det_cedula').html("C&eacute;dula: " + data.cedula);

                        $('#det_email').html("Email: " + data.email);

                        $('#det_telefono').html("Tel&eacute;fono: " + data.telefono);

                        $('#det_movil').html("Movil: " + data.movil);

                        $('#det_fax').html("Fax: " + data.fax);

                        $('#det_cpostal').html("C&oacute;digo Postal: " + data.cpostal);

                        
                        $('#det_direccion').html("Direcci&oacute;n: " + data.direccion);
                        
                        
                        //  var notas = JSON.parse(data.notas);

                         notas_html='<h3>Datos para env&iacute;o</h3>';
                         notas_html+='<b>Contacto:</b> ' + data.nombre_envio + '<br>';
                         notas_html+='<b>Correo</b> ' + data.correo_envio + '<br>';
                         notas_html+='<b>Tel&eacute;fono:</b> ' + data.telefono_envio + '<br>';
                         notas_html+='<b>Ciudad:</b> ' + data.poblacion + '<br>';
                         notas_html+='<b>Observaci&oacute;n:</b> ' + data.notas + '<br>';
                         //notas_html+='<b>Cobro env&iacute;o a:</b> '+notas.nombre+' valor: $'+notas.valor+'<br>';
                         notas_html+='<b>Direccion envio:</b> ' + data.direccion_envio + '<br>';

                        $('#det_notas').html(notas_html);

                        $('#det_id').html(data['id']);

                        $('#det_fecha_estado').html(data['fecha_estado'] + '<br />' + data['estado']);

                        $('#det_productos').html(data['productos']);



                        $('#det_subtotal').html("Subtotal: $ " + data['subtotal']);

                        $('#det_impuesto').html("Impuesto: $ " + data['impuesto']);

                        $('#det_total').html("Total: $ " + data['total']);

                    });

        });

      /*  $('body').on('click', '.confirmar', function (e) {

            e.preventDefault();
            var ids = $(this).attr('id');
            var id = ids.split('_');
            var url = "<?php echo site_url('ventas_online/get_detalle_confirmacion/') ?>";
            $.getJSON(
                url + '/' + id[1],
                function (data) {
                    $('#conf_id').val(data['id']);
                    $('#atend_id').html(data['id']);
                    $('#atend_fecha').html(data['fecha']);
                    $('#atend_productos').html(data['productos']);
                    $('#atend_subtotal').html("$ " + data['subtotal']);
                    $('#atend_impuesto').html("$ " + data['impuesto']);
                    $('#atend_total').html("$ " + data['total']);

                    if (data['puede'] == 1) { //hay descuento
                        $('#descuento').show();
                        $('#atend_descuento').html("$ " + data['descuento']);
                        $('#atend_descuento_imp').html("$ " + data['descuento_imp']);
                        $('#atend_total_final').html("$ " + data['final']);
                    }

                    if (data['puede'] == 2) { // no se puede vender
                        $('#btn_confirm').hide();
                    }
                    $("#s_almacen_origen_confirmacion").html('');
                    if (data['almacenes'].constructor === Array || data['almacenes'].constructor === Object ){
                        $("#s_almacen_origen_confirmacion").append('<option value="">Seleccione almacen</option>');
                        almacenes_tienda = data['almacenes'];
                        console.log(data['almacenes']);
                        $.each(data['almacenes'],function(index,value){
                            $("#s_almacen_origen_confirmacion").append('<option value="'+value.id+'">Todo de: '+value.nombre+'</option>');
                        });
                        $("#s_almacen_origen_confirmacion").append('<option value="individuales">De diferentes almacenes</option>')
                    }else{
                        $("#s_almacen_origen_confirmacion").append('<option value="">Todo de: '+data['almacenes']+'</option>')
                    }

                    if (data['msg'] == 1)
                        $('#atend_msg').html('¿Desea confirmar la venta de estos productos?');

                    else if (data['msg'] == 2)
                        $('#atend_msg').html('No se podra efectuar la venta de la solicitud completa debido a la no disponibilidad de algunos productos. �Aun asi desea confirmar la venta de los restantes productos disponibles?');

                    else if (data['msg'] == 3)
                        $('#atend_msg').html('No se podra efectuar la venta de ningun producto de la solicitud debido a la no disponibilidad de los productos.');

            });
        }); */

        $('body').on('click', '.facturar', function (e) {

            console.log(e);

            e.preventDefault();
            var ids = $(this).attr('id');
            var id = ids.split('_');
            var url = "<?php echo site_url('ventas_online/facturarConfirmacion/') ?>";
            $.getJSON(
                url + '/' + id[1],
                function (data) {

                    if(data.estado == 0)
                    {
                        $('#facturarModal').find('#conf_id').val(data.id);
                        $('#facturarModal').find('#atend_id').html(data.id);
                        $('#facturarModal').find('#atend_fecha').html(data.fecha);
                        $('#facturarModal').find('#atend_productos').html(data.productos);
                        $('#facturarModal').find('#atend_subtotal').html("Subtotal: $ " + data.subtotal);
                        $('#facturarModal').find('#atend_impuesto').html("Impuesto: $ " + data.impuesto);
                        $('#facturarModal').find('#atend_total').html("Total: $ " + data.total);
                        $('#facturarModal').find('#forma_pago').find('option').remove();
                        $('#facturarModal').find('#forma_pago').append(data.formasPago);
                        $('#facturarModal').find('#atend_nombre').html("Nomre: " + data.nombre);
                        $('#facturarModal').find('#atend_correo').html("Correo: " + data.email);
                        $('#facturarModal').find('#atend_telefono').html("Telefono: " + data.telefono);
                        $('#facturarModal').find('#atend_movil').html("Celular: " + data.movil);
                        $('#facturarModal').find('#atend_direccion').html("Dirección: " + data.direccion);
                        $('#facturarModal').find('#atend_ciudad').html("Ciudad: " + data.poblacion);
                        
                        /* hay descuento
                            $('#facturarModal').find('#descuento').show();
                            $('#facturarModal').find('#atend_descuento').html("Descuento: $ " + data['descuento']);
                            $('#facturarModal').find('#atend_descuento_imp').html("Descuento de Impuesto: $ " + data['descuento_imp']);
                            $('#facturarModal').find('#atend_total_final').html("Nuevo Total: $ " + data['final']);
                        */

                        $('#facturarModal').find('#atend_msg').html('¿Esta seguro de facturar esta venta online?');
                    }else
                    {
                        $('#facturarModal').modal("hide");
                        alert("La venta online ya fue facturada, no puede volverse a facturar");
                    }
                    
            });
        });



        $('body').on('click', '.anular', function (e) {



            e.preventDefault();



            var url = "<?php echo site_url('ventas_online/eliminar_solicitud_ventas/') ?>";



            if (confirm('¿Esta seguro de querer anular esta Solicitud de Venta Online?')) {



                window.location = url + '/' + $(this).attr('id');

                /*$.getJSON(
                 
                 url +'/'+ $(this).attr('id') ,
                 
                 function(data){  
                 
                 alert(data);
                 
                 }
                 
                 );*/

            }

        });



    });

    function cartel() {
        alert('Funcionalidad en desarrollo');
    }

    function mostrar_indidual_productos(value){
      
        if(String(value) ==='individuales'){
            construir_select_almacen();
        }else{
            $(".select_existencias").html('');
        }
    }

    function construir_select_almacen(){
        var select = $('<select name="s_almacen_independiente">');
        select.append($("<option>").attr('value','').text('seleccione'));
        $.each(almacenes_tienda,function(key,value){
             select.append($("<option>").attr('value',value.id).text(value.nombre));
        });
        $(".select_existencias").html(select);
        console.log(select);
        console.log($(".select_existencias"));

    }

</script>
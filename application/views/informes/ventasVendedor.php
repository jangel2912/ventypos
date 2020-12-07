<style>
    #reporte tr td 
    {
        white-space: nowrap;
    }
    .ui-datepicker {
        background-color: #fff !important;
        z-index: 2 !important;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $is_admin = $this->session->userdata('is_admin');
                $username = $this->session->userdata('username');	
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>
            <?php 
                endif; 
            ?>    
             <div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('', "Ventas Detalladas por Vendedor");?></h2>
            </div>
        </div>
     </div>
</div>
<div class="row-fluid">
    <div class="span12 well">
        <form id="formulario" action="<?php echo site_url("informes/ex_ventasVendedor");?>" method="POST">
            <div class="row-fluid">
                <div class="span3 form-group">
                    Fecha Inicial:
                    <input type="text" id="dateinicial" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"  readonly required/>
                </div>
                <div class="span3 form-group">
                    Fecha Final:
                    <input type="text" id="datefinal" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker" readonly required/>
                </div>
                <?php if( $is_admin == 't' || $is_admin == 'a') { ?>
                    <div class="span3 form-group">
                        Almacén:  
                        <?php 
                        echo "<select  name='almacen' >";    
                            echo "<option value='0'>Todos los Almacenes</option>";    
                                foreach($data1['almacen'] as $f)
                                {
                                    if($f->id == $this->input->post('almacen')){
                                        $selected = " selected=selected ";
                                    } else {
                                        $selected = "";
                                    }        
                                    echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                }
                        echo "</select>";    
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="row-fluid">
                <div class="span3 form-group">
                    Vendedor:  
                    <input type="text"  value="<?php echo set_value('datos_vendedor'); ?>" name="datos_vendedor" id="datos_vendedor"/>
                    <input type="hidden" name="vendedor" id="vendedor" style="width: 200px;height: 25px;"/>
                </div>
                <div class="span3 form-group">
                    Producto:  
                    <input type="text"  value="<?php echo set_value('nombre-producto'); ?>" name="nombre-producto" id="nombre-producto"/>
                    <input type="hidden" id="id_producto" name="id_producto">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                <!--
                    <input id="consultar" type="button" value="Consultar" class="btn btn-success"/> &nbsp; 
                    <input type="submit" value="Exportar a excel" class="btn btn-success"/>-->                    
                    <div class="span1">
                        <a data-tooltip="Consultar" id="consultar">                        
                            <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                        </a> 
                    </div>
                    <div class="span1">
                        <a data-tooltip="Descargar Excel" onclick="verificar()">                        
                            <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                        </a> 
                    </div>
                </div>
            </div>
      </form>
	</div>		
</div>
<div class="row-fluid">
    <div class="span12"> 
        <div class="overflow" style="width:100%; heigt:auto; overflow-x:auto;">
            <table id="reporte" width="100%" class="table table-striped">
              
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    
    function verificar(){
        var fechainicial = $("#dateinicial").val();
        var fechafinal = $("#dateinicial").val();
        
        if((fechainicial != "") &&(fechafinal != "")){
            
            if((fechainicial)<=(fechafinal)) {                    
                $("#mensaje").html("");
                $("#mensaje").addClass('hidden');
                $('#formulario').submit();
            }else{                    
                $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                $("#mensaje").removeClass('hidden');
            }         
            
        }else{                
            $("#mensaje").html("Debe seleccionar las Fechas a consultar");
            $("#mensaje").removeClass('hidden');
        }
    }

    $("#datos_vendedor").autocomplete({
        source: "<?php echo site_url("vendedores/get_ajax_vendedores"); ?>",
        minLength: 1,
        select: function( event, ui ) {
            console.log(ui);
            $("#vendedor").val(ui.item.id);
        }
    });
    
    $('#nombre-producto').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo site_url("productosf/filtro_prod"); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    term: request.term

                },
                success: function (data) {
                    response($.map(data, function (item) {
                    return {
                            value: item.nombre + ' (' + item.codigo + ') ',
                            id: item.id,
                            codigo: item.codigo,
                            
                            porciento: item.porciento,
                            descripcion: item.descripcion,
                        }
                    }));
                }
            });

            },
            minLength: 1,
            select: function (event, ui) {

                $("#precio").val(ui.item.precio_venta);

                $("#product-service").val(ui.item.id);

                $("#impuestos").val(ui.item.porciento);
                $("span#idImpuestos").text(ui.item.porciento);
                

                $("#descripcion").val(ui.item.descripcion);

                $("#id_producto").val(ui.item.id);

                $("#codigo").val(ui.item.codigo);

                $("#cantidad").val(1);

            }

        }).on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (code == 9) {
                $('#nombre-producto').autocomplete("search", $('#nombre-producto').val());
                e.preventDefault();
            }
    });
        
        
    $(document).ready(function() {

        $('#consultar').on('click', function(e){

            var fechainicial = $("#dateinicial").val();
            var fechafinal = $("#datefinal").val();
            var datos_vendedor = $("#datos_vendedor").val();
            var producto = $("#nombre-producto").val();
            
            if((fechainicial != "") &&(fechafinal != "")){
                
                if((fechainicial)<=(fechafinal)) 
                {                    
                    $("#mensaje").html("");
                    $("#mensaje").addClass('hidden');
                    $.post(
                        "<?php echo site_url('informes/get_ajax_data_ventasVendedor'); ?>",
                        $('#formulario').serialize(),
                        function(data){
                            var ventas = data.total_ventas;
                            var html = '<tr>';
                                $.each(ventas['columnas'], function(index, el) {
                                    html += '<th>'+el+'</th>';
                                });
                            html += '</tr>';

                            $.each(ventas, function(index, el){
                                html += '<tr>';
                                    $.each(ventas[index], function(index, el){
                                        html += '<td>'+(el == null ? '' : el)+'</td>';
                                    });
                                html += '</tr>';
                            });

                            $('#reporte').html(html);
                        },
                        'json'
                    );
                }else{
                    $("#mensaje").html("La Fecha Final debe ser mayor a la Fecha Inicial");
                    $("#mensaje").removeClass('hidden');
                }

            }else{
                    $("#mensaje").html("Debe seleccionar las Fechas a consultar");
                    $("#mensaje").removeClass('hidden');                
            }
        });
    });
    
    mixpanel.track("Informe_ventas_Vendedor");  
</script> 
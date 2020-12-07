  
        <?php
        $permisos = $this->session->userdata('permisos');

        $is_admin = $this->session->userdata('is_admin');
        $impuestopredeterminado=$data['impuesto']->porciento;                
        ?>
        <style>
            .titulototal{
                font-size: 16px;
                font-weight: 700;
            }
        </style>
<div class="page-header">    
    <div class="icon">
        <img alt="Bodegas" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_bodegas']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Facturas Pendientes", "Pagos Pendientes a Facturas");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Editar Pago Factura ".$data['dataventapago'][0]['factura'] );?></h2>   
    </div>
</div>
<div class="container">
    <div class="col-md-12">
        <form class="form-horizontal" id="form_pago" method="POST">
            <input type="hidden" class="form-control" id="factura" name="factura" value="<?= $data['dataventapago'][0]['id'] ?>">
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor a Pagar:</label>
                <div class="col-md-10">
                <input type="number" disabled index="" class="form-control" id="valor_a_pagar" name="valor_a_pagar" placeholder="valor_a_pagar" value="<?= $data['dataventapago'][0]['valor_entregado'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                <div class="col-md-10">
                    <select name="forma_pago" id="forma_pago" class="form-control forma_pago" data-id="">
                        <?php
                        foreach ($data['forma_pago'] as $f) {
                            if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                            ?>
                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group" id="pago_datafono" style="display:none">   
                <div class="col-md-2"> </div>            
                <div class="col-md-10">
                    <div class="col-md-3">
                    Subtotal <input id="subtotal" class="subtotal" type="text" disabled="true" value="0">
                    </div>                                          
                    <div class="col-md-3">
                    IVA <input class="impuesto" id="impuesto" type="text" disabled="true" value="0" >
                    </div>                                          
                    <div class="col-md-3">
                    Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono" id="impuestoDatafono" data-id="" value="<?php echo $impuestopredeterminado; ?>">
                    </div>                                          
                    <div class="col-md-3">
                    N° Transacción <input type="text" name="transaccion" id="transaccion" value="">
                    </div>                                          
                </div>                                          
            </div>
            <div id="fecha_vencimiento_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                <div class="col-md-10">                    
                    <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" />                    
                </div>
            </div>
            <div id="nota_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                <div class="col-md-9">                    
                   <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/> 
                </div>
                <div class="col-md-1">
                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>                 
                </div> 

            </div>            
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                <div class="col-md-10">
                    <input type="number" class="form-control valor_entregado" id="valor_entregado" name="valor_entregado" data-id="" placeholder="valor Entregado" value="<?= $data['dataventapago'][0]['valor_entregado'] ?>">
                </div>
            </div>
           
            <div id="contenido_a_mostrar1" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago1" id="forma_pago1" class="form-control forma_pago" data-id="1">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer">
                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="1" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado1" name="valor_entregado1" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono1" style="display:none">   
                    <div class="col-md-2"> </div>            
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal1" class="subtotal" type="text" disabled="true" value="0">
                        </div>                                          
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto1" type="text" disabled="true" value="0" >
                        </div>                                          
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono1" data-id="1" id="impuestoDatafono1" value="<?php echo $impuestopredeterminado; ?>">
                        </div>                                          
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion1" id="transaccion1" value="">
                        </div>                                          
                    </div>                                          
                </div>            
                <div id="fecha_vencimiento_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">                    
                        <input type="text" name="fecha_vencimiento_venta1" id="fecha_vencimiento_venta1" />                    
                    </div>
                </div>
                <div id="nota_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">                    
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito1" id="valor_entregado_nota_credito1"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/> 
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>                 
                    </div> 
                </div>
            </div>

            <div id="contenido_a_mostrar2" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago2" id="forma_pago2" class="form-control forma_pago" data-id="2">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer">
                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="2" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado2" name="valor_entregado2" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono2" style="display:none">   
                    <div class="col-md-2"> </div>            
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal2" class="subtotal" type="text" disabled="true" value="0">
                        </div>                                          
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto2" type="text" disabled="true" value="0" >
                        </div>                                          
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono2" id="impuestoDatafono2"  data-id="2" value="<?php echo $impuestopredeterminado; ?>">
                        </div>                                          
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion2" id="transaccion2" value="">
                        </div>                                          
                    </div>                                          
                </div>            
                <div id="fecha_vencimiento_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">                    
                        <input type="text" name="fecha_vencimiento_venta2" id="fecha_vencimiento_venta2" />                    
                    </div>
                </div>
                <div id="nota_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">                    
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito2" id="valor_entregado_nota_credito2"  index="2" value="" placeholder=" C&oacute;digo Nota Crédito"/> 
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>                 
                    </div> 
                </div>
            </div>

            <div id="contenido_a_mostrar3" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago3" id="forma_pago3" class="form-control forma_pago" data-id="3">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer">
                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="3" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado3" name="valor_entregado3" data-id="3" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono3" style="display:none">   
                    <div class="col-md-2"> </div>            
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal3" class="subtotal" type="text" disabled="true" value="0">
                        </div>                                          
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto3" type="text" disabled="true" value="0" >
                        </div>                                          
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono3" id="impuestoDatafono3"  data-id="3" value="<?php echo $impuestopredeterminado; ?>">
                        </div>                                          
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion3" id="transaccion3" value="">
                        </div>                                          
                    </div>                                          
                </div>            
                <div id="fecha_vencimiento_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">                    
                        <input type="text" name="fecha_vencimiento_venta3" id="fecha_vencimiento_venta3" />                    
                    </div>
                </div>
                <div id="nota_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">                    
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito3" id="valor_entregado_nota_credito3"  index="3" value="" placeholder=" C&oacute;digo Nota Crédito"/> 
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>                 
                    </div> 
                </div>
            </div>
           
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Cambio:</label>
                <div class="col-md-10">
                    <input type="number" disabled class="form-control" id="cambio" name="cambio" placeholder="cambio" value="0">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="button" class="btn btn-default">Cancelar</button>                    
                    <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                        <button type="button" class="btn btn-success" onClick="mostrar();" >Agregar Forma de Pago</button>
                    <?php } ?>
                    <button type="button" id="pagar_pendiente" class="btn btn-success">Pagar</button>
                </div>
            </div>
        </form>            
    </div>
</div>

<script>
    

    $(".btnBuscarNotaCredito2").click(function(  ) {
        
        //var index = $(this).attr("index") ;
        var index = "" ;
        var codigo = $("#valor_entregado_nota_credito").val() ;
               
        $.ajax({
            url: "<?php echo site_url("notacredito/estadoNotaCredito"); ?>",
            dataType: 'json',
            type: 'POST',
            data: {"codigo":codigo},
            error: function(jqXHR, textStatus, errorThrown ){
                //alert(errorThrown);
                swal({
                    position: 'center',
                    type: 'error',
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500
                })  
            },
            success: function(data){

                setEstadoNotaCredito( data, index );
            }
        }); 
    });

    function setEstadoNotaCredito( datos, index ){       

        var estado = datos.estado;

        var nombre = datos.nombre;

        var valor = datos.valor;        

        $("#valor_entregado").val( 0 );

        $("#valor_entregado").prop('disabled', false);

        $("#valor_entregado").css("cursor", "default");

        $("#valor_entregado").hide();

        $("#valor_entregado_gift").attr('disabled');

        $("#valor_entregado_nota_credito").css("cursor", "default");            

        $("#valor_entregado_nota_credito").attr('style','display: block !important');

       // setNotaCreditoObj( index, null );
        if( estado == "empty" ){
            //alert("La nota credito no existe");  
            $("#valor_entregado_nota_credito").val('');
            swal({
                position: 'center',
                type: 'error',
                title: 'La nota credito no existe',
                showConfirmButton: false,
                timer: 1500
            })  
        }

        if( estado == "cancelado" ){
            //alert("La "+nombre+" ya ha sido canjeada");
            $("#valor_entregado_nota_credito").val('');   
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" ya ha sido canjeada",
                showConfirmButton: false,
                timer: 1500
            })  
        }
        if( estado == "activo" ){
            //alert("La "+nombre+" no ha sido pagada");
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" no ha sido pagada",
                showConfirmButton: false,
                timer: 1500
            })  
        }
        if( estado == "pagado" ){            

            $("#valor_entregado").val( valor );

            $("#valor_entregado").prop('disabled', true);

            $("#valor_entregado").css("cursor", "not-allowed");

            $("#valor_entregado").show();

            $("#valor_entregado_nota_credito").prop('disabled', true);

            $("#valor_entregado_nota_credito").css("cursor", "not-allowed");

            $("#valor_entregado_nota_creditob").attr('style','display: none !important');
           // setNotaCreditoObj(index,"pagada");
            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());            
            cambio=parseFloat(valor_a_pagar-(valor_entregado));
            cambio=cambio*(-1);
            $("#cambio").val(cambio);

        }           
        //validarMediosDePago(0);
    }

    $("#fecha_vencimiento_venta").datepicker({
        dateFormat: 'yy/mm/dd'
    });

    $('.forma_pago').on('change', function() { 
                
        forma=$(this).val();
        id=$(this).attr('data-id');
        //bloquear_opciones_forma(id);
        $( "#forma_pago"+id+" option:selected" ).each(function() {           
            tipo=$(this).data('tipo');
        });      

        $("#valor_entregado").prop("disabled", false); 
       
        if(tipo=='Datafono'){
            $("#pago_datafono"+id).css('display','block');
            discriminado(id);
        }else{
            $("#pago_datafono"+id).css('display','none');
        }

        if(forma=='Credito'){
            $("#fecha_vencimiento_credito"+id).css('display','block');
        }else{
            $("#fecha_vencimiento_credito"+id).css('display','none');
        }

        if(forma=='nota_credito'){
            $("#nota_credito"+id).css('display','block');
        }else{
            $("#nota_credito"+id).css('display','none');
        }
        
    });

    $("#pagar_pendiente").click(function(e){      
        $("#pagar_pendiente").prop("disabled", true);  
        valor_entregado1=0;
        valor_entregado2=0;
        valor_entregado3=0;
        valor_a_pagar=parseFloat($("#valor_a_pagar").val());
        valor_entregado=parseFloat($("#valor_entregado").val());
        cambio=parseFloat($("#cambio").val());
        url='<?php echo site_url("frontend/index") ?>';
        
        if(isNaN(valor_entregado)){
            $("#valor_entregado").val(0);
            $("#cambio").val(0);            
        }

        //me paseo por todos los pagos
        if (document.getElementById('contenido_a_mostrar1').style.display == 'block') {
            valor_entregado1=parseFloat($("#valor_entregado1").val());
        }
        if (document.getElementById('contenido_a_mostrar2').style.display == 'block') {
            valor_entregado2=parseFloat($("#valor_entregado2").val());
        }
        if (document.getElementById('contenido_a_mostrar3').style.display == 'block') {
            valor_entregado3=parseFloat($("#valor_entregado3").val());
        }
        
        valor_entregadototal=valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3;


        if(!isNaN(valor_entregadototal)){
            if((((valor_a_pagar!="") &&(valor_entregadototal!="")) && (valor_a_pagar<=valor_entregadototal))){
                    
                    $("#cambio").prop("disabled", false);
                    $("#valor_entregado_nota_credito").prop("disabled", false);
                    $("#valor_a_pagar").prop("disabled", false);
                    $("#valor_entregado").prop("disabled", false);                    
                    //ajax
                    $.ajax({
                        url: "<?php echo site_url("ventas/registrarformaspago"); ?>",
                        type: "POST",
                        dataType: "json",
                        data: $("#form_pago").serialize(),
                        success: function (data) {
                            console.log(data);
                            if(data.success==1){
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'La Forma de pago se registro Exitosamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                })  
                                setTimeout(function(){ 
                                
                                    location.href =url;
                                }, 1600);  
                            }else{
                                swal({
                                    position: 'center',
                                    type: 'error',
                                    title: data.mgs,
                                    showConfirmButton: false,
                                    timer: 1500
                                }) 
                                 $("#pagar_pendiente").prop("disabled", false);  
                            }                         
                        }
                    });                
                }
                else{
                    swal({
                        position: 'center',
                        type: 'error',
                        title: 'El valor entregado debe ser igual al valor a pagar',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                     $("#pagar_pendiente").prop("disabled", false);  
                }
            
        }else{
            swal({
                position: 'center',
                type: 'error',
                title: 'El valor entregado debe ser mayor o igual al valor de la factura',
                showConfirmButton: false,
                timer: 1500
            })
            $("#pagar_pendiente").prop("disabled", false); 
        }
    });

    $(".valor_entregado").keyup(function(e){         
        valor_a_pagar=parseFloat($("#valor_a_pagar").val());
        valor_entregado=parseFloat($("#valor_entregado").val());        
        valor_entregado1=parseFloat($("#valor_entregado1").val());    
        
        if(isNaN(valor_entregado)){              
            valor_entregado=0;          
        }
        if(isNaN(valor_entregado1)){           
            valor_entregado1=0;          
        }
        
        
        cambio=parseFloat(valor_a_pagar-(valor_entregado+valor_entregado1));
        cambio=cambio*(-1);
        $("#cambio").val(cambio);
    });   

    $(".impuestoDatafono").keyup(function(){         
        id=$(this).attr('data-id'); 
        //alert(id);
        discriminado(id);
    }); 
    
    function discriminado(id){
            //alert(id);
        valorEntregado=parseFloat($("#valor_entregado"+id).val());        
        impuesto=parseFloat($("#impuestoDatafono"+id).val());        
        subtotal=valorEntregado;                
        x=String(impuesto).length;
        
        if(x == 2)
        {
            subtotal = valorEntregado / parseFloat("1."+impuesto);
        }else if(x == 1)
        {
            subtotal = valorEntregado / parseFloat("1.0"+impuesto);
        }
        
        iva = valorEntregado - subtotal;      
        $("#subtotal"+id).val(parseInt(subtotal));        
        $("#impuesto"+id).val(parseInt(iva));           
    }
    
    $(".eliminar_forma_pago").click(function(e){     
        
        id=$(this).attr('data-id');  
        //eliminar datos del eliminado           
        $("#valor_entregado"+id).val(0);
        $( "#forma_pago"+id+" option:selected" ).each(function() {           
            tipo=$(this).data('tipo');
        });    
        if(tipo=='Datafono'){             
             $("#impuestoDatafono"+id).val(0);
             $("#transaccion"+id).val("");
             $("#impuesto"+id).val(0);
             $("#subtotal"+id).val(0);
            $("#pago_datafono"+id).css('display','none');
        }

        if(forma=='Credito'){
            $("#fecha_vencimiento_venta"+id).val("");           
            $("#fecha_vencimiento_credito"+id).css('display','none');
        }
        $( "#forma_pago"+id).val(0);  
        $("#contenido_a_mostrar"+id).css('display','none');

    });  

    function bloquear_opciones_forma(id){
        forma0=$("#forma_pago").val();
        
        for(i=id;i<=id;i++){

            $( "#forma_pago"+i+" option" ).each(function() {           
                nombre=$(this).val();
                if(nombre==forma0){
                    $(this).attr('disabled', true);
                    
                }else{
                    $(this).attr('disabled', false);
                }                
            });  
        }

        
        
    }
        
    function mostrar() {       
        
        if (document.getElementById('contenido_a_mostrar1').style.display == 'none') {
            document.getElementById('contenido_a_mostrar1').style.display = 'block';
            //bloquear las opciones anteriores
            bloquear_opciones_forma(1);
        } else if (document.getElementById('contenido_a_mostrar2').style.display == 'none') {
            document.getElementById('contenido_a_mostrar2').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar3').style.display == 'none') {
            document.getElementById('contenido_a_mostrar3').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar4').style.display == 'none') {
            document.getElementById('contenido_a_mostrar4').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar5').style.display == 'none') {
            document.getElementById('contenido_a_mostrar5').style.display = 'block';
        }
    }
</script>


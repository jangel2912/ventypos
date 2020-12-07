<style>
    .ml-0{margin-left:0px;}
    .mr-0{margin-right:0px;}
    .mt-2{margin-top:5px;}
    .mt-3{margin-top:10px;}
    .pl-0{padding-left:0px;}
    .content-results{margin-top:5rem;}
    .label-results{font-weight:bold;}
    .error-bordered{border: solid 1px !important; color: red !important;}
    #ui-datepicker-div{z-index:99; background-color:#fff;}
    .content-help ul{margin-top:15px;}
    .content-help ul li{list-style-type:circle; margin-top:5px;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Conciliar cuenta", "Conciliar cuenta");?></h1>
</div>


    <div class="row">
        <div class="col-md-8">
       
        </div>
    </div>


<div class="row">
    <div class="col-md-8">
        <div class="col-md-12 ">
            <?php if(isset($bancos)){ ?>
            <div class="col-md-3 pl-0">
                <div class="form-group">
                    <label for="banco_conciliacion">Seleccione banco:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Fecha de corte que aparece en el extracto bancario." data-trigger="hover"></span>
                    <select  id="banco_conciliacion">
                            <option value=""> Seleccione banco</option>
                        <?php foreach($bancos as $value): ?>
                            <option value="<?= $value->id; ?>"> <?= $value->nombre_cuenta;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php } ?>
            <div class="col-md-3 pl-0">
                <div class="form-group">
                    <label for="fecha_corte">Fecha de corte:</label>
                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Fecha de corte que aparece en el extracto bancario." data-trigger="hover"></span>
                    <input type="text" class="ml-0 datepicker" id="fecha_corte" placeholder="Seleccione fecha" <?= (isset($conciliacion_pendiente["conciliacion"]->fecha_corte))? 'value="'.$conciliacion_pendiente["conciliacion"]->fecha_corte.'"' : '';?>>
                </div>
            </div>  
        </div>
            
        <div class="col-md-12">
            <div class="form-group">
                <label for="gastos_bancarios">(-) Gastos bancarios:</label>
                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Sumatoria de todos los gastos por concepto de uso de servicios del banco (cuota manejo, cobro por retiros ...)" data-trigger="hover"></span>
                <input type="text" class="form-control ml-0" id="gastos_bancarios" placeholder="Gastos bancarios" <?= (isset($conciliacion_pendiente["conciliacion"]->gastos_bancarios))? 'value="'.$conciliacion_pendiente["conciliacion"]->gastos_bancarios.'"' : '';?>>
            </div>
        </div> 
        <div class="col-md-12">
            <div class="form-group">
                <label for="impuestos_bancarios">(-) Impuestos bancarios:</label>
                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Sumatoria de todos los cobros que realiza el banco por concepto de impuestos. " data-trigger="hover"></span>
                <input type="text" class="form-control ml-0" id="impuestos_bancarios" placeholder="Impuestos bancarios" <?= (isset($conciliacion_pendiente["conciliacion"]->impuestos_bancarios))? 'value="'.$conciliacion_pendiente["conciliacion"]->impuestos_bancarios.'"' : '';?>>
            </div>
        </div> 
        <div class="col-md-12">
            <div class="form-group">
                <label for="entradas_bancarias">(+) Entradas bancarias:</label>
                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Sumatoria de todos los ingresos en tu cuenta bancaria. (abonos, consignaciones)" data-trigger="hover"></span>
                <input type="text" class="form-control ml-0" id="entradas_bancarias" placeholder="Entradas bancarias" <?= (isset($conciliacion_pendiente["conciliacion"]->entradas_bancarias))? 'value="'.$conciliacion_pendiente["conciliacion"]->entradas_bancarias.'"' : '';?>>
            </div>
        </div> 
        <div class="col-md-12">
            <div class="form-group">
                <label for="saldo_final">(-) Saldo final:</label>
                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="Saldo que genera tu extracto bancario." data-trigger="hover"></span>
                <input type="text" class="form-control ml-0" id="saldo_final" placeholder="Saldo final" <?= (isset($conciliacion_pendiente["conciliacion"]->saldo_final))? 'value="'.$conciliacion_pendiente["conciliacion"]->saldo_final.'"' : '';?>>
            </div>
        </div> 
    </div>

    <div class="col-md-4 content-help">
        <h4>Observaciones</h4>
        <hr>
        <ul>
            <li> Puedes guardar tu conciliación en el momento que desees y volver a conciliar en otro momento.</li>
            <li> Si ya has guardado tu conciliación y quieres volver a guardar el sistema actualiza la información automaticamente.</li>
            <li> El saldo inicial lo calcula el sistema automaticamente.</li>
            <li> Para poder conciliar la diferencia debe ser igual a 0.</li>
        </ul>
    </div>
</div>

<hr>
<div class="row mt-3">
    <div class="col-md-8">
        <div class="col-md-12">
            <h4>Listado de movimientos</h4>
            <hr>
            <table class="table" id="movimientos">
                <thead>
                    <tr>
                        <td>Fecha creación</td>
                        <td>Nombre movimiento</td>
                        <td>Tipo movimiento</td>
                        <td>Valor</td>
                        <td>Estado</td>
                        <td>Acción</td>
                    </tr>
                </thead>
                <tbody>
                    <?php //print_r($movimientos);
                     if(isset($movimientos)){ ?>
                        <?php foreach($movimientos as $movimiento): ?>
                        <tr class="row-movimiento">
                            <td><?= $movimiento->fecha_creacion;?></td>
                            <td><?= $movimiento->nombre;?></td>
                            <td><?= ($movimiento->tipo == 1)? 'Entrada' : 'Salida';?></td>
                            <td><?= $movimiento->valor;?></td>
                            <td><?= ($movimiento->estado == '')? 'Sin conciliar' : 'Conciliada';?></td>
                            <td><input type="checkbox" <?= ($movimiento->estado == '')? '' : 'disabled';?> id="<?= ($movimiento->estado == '')? 'conciliar' : '';?>"  
                                data-id='<?= $movimiento->id; ?>' 
                                data-tipo='<?= $movimiento->tipo; ?>'  
                                data-valor='<?= $movimiento->valor; ?>'
                                <?= ($movimiento->pendiente)? 'checked' : '';?>>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-4 content-results">
        <div class="col-md-12">
            <p><span class="label-results">Banco: </span> <span class="banco"><?= (isset($banco->nombre_cuenta)) ? ucfirst($banco->nombre_cuenta) : '';?></span></p>
            <p><span class="label-results">Saldo inicial: </span> <span class="saldo_inicial">$<?= $saldo_inicial;?></span></p>
            <p><span class="label-results">Movimientos a conciliar:</span>  <span class="movimientos_seleccionados"><?= (isset($movimientos_seleccionados)) ? $movimientos_seleccionados : 0;  ?></span></p>
            <p><span class="label-results">Diferencia: </span> <span class="diferencia">$0</span></p>
        </div>
        <div class="col-md-12">
            <a href="<?php echo site_url('bancos/conciliaciones');?>" class="btn btn-default pull-left">Cancelar</a>
            <button class="btn btn-success pull-left mr-1" id="guardar-conciliacion">Guardar</button>
            <button class="btn btn-success pull-left mr-0" id="conciliar-transacciones">Conciliar</button>
        </div>
    </div>
</div>


<script>
    var saldo_inicial = <?= $saldo_inicial; ?>;
    var banco = '<?= (isset($banco->id)) ? $banco->id : '' ?>',  gastos_bancarios = 0, impuestos_bancarios = 0, entradas_bancarias = 0, 
    saldo_final = 0, cantidad_movimientos = 0, total_movimientos = 0, diferencia = 0, total = 0, url_conciliacion = '<?= site_url('bancos/conciliar_movimientos');?>';
    var movimientos = [];
    var url_data_banco = "<?= site_url('bancos/get_data_banco_conciliacion'); ?>";
    var url_guardar_conciliacion = "<?= site_url('bancos/guardar_conciliacion'); ?>";
    var conciliacion_pendiente = <?php echo ($conciliacion_pendiente != NULL)? 1:0;?>

    $(document).ready(function(){
        cargar_conciliacion_pendiente();
        var oTable = $("#movimientos").dataTable();

        $("#banco_conciliacion").change(function(){
            
            swal({
                    title: 'Un momento!',
                    text: 'Se esta actualizando la información con el banco seleccionado.',
                    imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                    imageWidth: 200,
                    imageHeight: 200,
                    imageAlt: 'Cargando',
                    animation: false,
                    showConfirmButton: false
                })
            var id_banco = $(this).val();
            banco = id_banco;
            $.post(url_data_banco+'/'+id_banco,{
                id_banco : id_banco
            },function(data){
                var data_movimientos = [];
                setTimeout(function(){
                    $(".banco").html(""); 
                    swal.close();
                    var response = JSON.parse(data);
                    if(response != null){
                        var banco = response.banco;
                        var movimientos = response.movimientos;
                        var movimiento = [];
                        $(".banco").html(banco.nombre_cuenta);  
                        $.each(movimientos,function(index,element){
                            var check = "<input type='checkbox' ";
                                check +=  (element.estado == 'conciliado')? 'disabled' : 'id="conciliar" data-id="'+element.id+'" data-tipo="'+element.tipo+'" data-valor="'+element.valor+'"';
                                check += ">";
                            movimiento = [
                                    element.fecha_creacion,
                                    element.nombre,
                                    (element.tipo == 1)? 'Entrada' : 'Salida',
                                    element.valor,
                                    (element.estado == 'conciliado')? 'Conciliado' : 'No conciliado',
                                    check
                            ];

                            data_movimientos.push(movimiento);
                        })
                    }

                     oTable.dataTable({
                        destroy: true,
                        searching: false,
                        data: data_movimientos
                    }) 

                    calc();
                   
                }, 2000);  
                
                
            })
        })

        $("#gastos_bancarios").keyup(function(){
            $(this).removeClass('error-bordered');
            gastos_bancarios = parseFloat($(this).val());
            update_diference();
        });

        $("#impuestos_bancarios").keyup(function(){
            $(this).removeClass('error-bordered');
            impuestos_bancarios = parseFloat($(this).val());
            update_diference();
        });

        $("#entradas_bancarias").keyup(function(){
            $(this).removeClass('error-bordered');
            entradas_bancarias = parseFloat($(this).val());
            update_diference();
        });

         $("#saldo_final").keyup(function(){
            $(this).removeClass('error-bordered');
            saldo_final = parseFloat($(this).val());
            update_diference();
        });

         $("#fecha_corte").click(function(){
            $(this).removeClass('error-bordered');
        });
        
        

        $("#conciliar-transacciones").click(function(){
            $("#conciliar-transacciones").prop('disabled',true);
            var flag_conciliacion = true;

            if($("#fecha_corte").val() == ''){
                $("#fecha_corte").addClass('error-bordered');               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de fecha",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#gastos_bancarios").val() == ''){
                $("#gastos_bancarios").addClass('error-bordered');                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de gastos bancarios",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#impuestos_bancarios").val() == ''){
                $("#impuestos_bancarios").addClass('error-bordered');               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de impuestos bancarios",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#entradas_bancarias").val() == ''){
                $("#entradas_bancarias").addClass('error-bordered');
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de entradas bancarias",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#saldo_final").val() == ''){
                $("#saldo_final").addClass('error-bordered');
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de saldo final",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if(cantidad_movimientos <= 0){                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Seleccione al menos una transacción para conciliar",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if(total != 0){                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "La diferencia no puede dar diferente a 0, verifique",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            } 

            if(flag_conciliacion){
                fecha_corte = $("#fecha_corte").val();
                
                swal({
                    title: 'Un momento!',
                    text: 'Se estan conciliando las transacciones bancarias.',
                    imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                    imageWidth: 200,
                    imageHeight: 200,
                    imageAlt: 'Cargando',
                    animation: false,
                    showConfirmButton: false
                })
                $.post(url_conciliacion,{
                    banco:banco,
                    fecha_corte: fecha_corte,
                    gastos_bancarios : gastos_bancarios,
                    impuestos_bancarios : impuestos_bancarios,
                    entradas_bancarias : entradas_bancarias,
                    saldo_final : saldo_final,
                    movimientos: JSON.stringify(movimientos) 
                },function(response){
                    swal.close();
                    var data = JSON.parse(response);
                    if(data.response == 'success'){                   
                        swal({
                            title: "Redirigiendo!",
                            text:  "Transacciones conciliadas correctamente",
                            type: "success",
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            location.href = "<?php echo site_url('bancos');?>";
                        }, 2000);    
                    }else{
                        swal(
                        'Error inesperado!',
                        'Ocurrio un error al intentar conciliar los movimientos.',
                        'error'
                        )
                    }
                })
            }else{
                $("#conciliar-transacciones").prop('disabled',false);
            }

        })

        $("#guardar-conciliacion").click(function(){
            $("#guardar-conciliacion").prop('disabled',true);
            var flag_conciliacion = true;

            if($("#fecha_corte").val() == ''){
                $("#fecha_corte").addClass('error-bordered');
                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de fecha",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#gastos_bancarios").val() == ''){
                $("#gastos_bancarios").addClass('error-bordered');
               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de gastos bancarios",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#impuestos_bancarios").val() == ''){
                $("#impuestos_bancarios").addClass('error-bordered');
               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de impuestos bancarios",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#entradas_bancarias").val() == ''){
                $("#entradas_bancarias").addClass('error-bordered');
                
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de entradas bancarias",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if($("#saldo_final").val() == ''){
                $("#saldo_final").addClass('error-bordered');
               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Por favor complete el campo de saldo final",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }else if(cantidad_movimientos <= 0){               
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: "Seleccione al menos una transacción para guardar",
                    showConfirmButton: false,
                    timer: 1500
                })
                flag_conciliacion = false;
            }

            if(flag_conciliacion){
                fecha_corte = $("#fecha_corte").val();
                swal({
                    title: 'Un momento!',
                    text: 'Se esta guardando la conciliacion.',
                    imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                    imageWidth: 200,
                    imageHeight: 200,
                    imageAlt: 'Cargando',
                    animation: false,
                    showConfirmButton: false
                })
                $.post(url_guardar_conciliacion,{
                    banco:banco,
                    fecha_corte: fecha_corte,
                    gastos_bancarios : gastos_bancarios,
                    impuestos_bancarios : impuestos_bancarios,
                    entradas_bancarias : entradas_bancarias,
                    saldo_final : saldo_final,
                    movimientos: JSON.stringify(movimientos) 
                },function(response){
                    console.log(response);
                    swal.close();
                    var data = JSON.parse(response);
                    switch(data.response){
                        case 'insert_success' :
                            swal({
                                title: "Redirigiendo!",
                                text:  "Conciliacion guardada correctamente",
                                type: "success",
                                showConfirmButton: false,
                                timer:1500
                            })
                            setTimeout(function(){
                                location.href = "<?php echo site_url('bancos');?>";
                            }, 2000);  
                        break;
                    
                        case 'update_success' :
                            swal({
                                title: "Redirigiendo!",
                                text:  "Conciliacion actualizada",
                                type: "success",
                                showConfirmButton: false
                            })
                            setTimeout(function(){
                                location.href = "<?php echo site_url('bancos');?>";
                            }, 2000);  
                        break;

                         case 'error' :
                            swal(
                                'Error inesperado!',
                                'Ocurrio un error al intentar guardar la conciliacion.',
                                'error'
                            )
                            $("#guardar-conciliacion").prop('disabled',false);
                        break;
                    }
                })
            }
            else{
                $("#guardar-conciliacion").prop('disabled',false);
            }
        })
    })


    function calc(){
        $("#movimientos #conciliar").each(function(index,element){
            
            $(this).click(function(){
                
                if($(this).is(':checked')){
                    movimientos.push($(this).data('id'));
                    cantidad_movimientos++;
                    switch($(this).data('tipo')){
                        case 1 :
                            total_movimientos += $(this).data('valor');
                        break;

                        case 2:
                            total_movimientos -= $(this).data('valor');
                        break;
                    }
                }else{
                    movimientos.pop($(this).data('id'));
                    cantidad_movimientos--;
                    switch($(this).data('tipo')){
                        case 1 :
                            total_movimientos -= $(this).data('valor');
                        break;

                        case 2 :
                            total_movimientos += $(this).data('valor');
                        break;
                    }
                }
                $(".movimientos_seleccionados").html(cantidad_movimientos);
                update_diference();
            })
            
        })
    } 

    function update_diference(){
        console.log('total_movimientos'+total_movimientos+'\n'
        +'gastos_bancarios'+gastos_bancarios+'\n'
        +'impuestos_bancarios'+impuestos_bancarios+'\n'
        +'entradas_bancarias'+entradas_bancarias+'\n'
        +'saldo_final'+saldo_final+'\n');

        total =  total_movimientos +  (saldo_inicial - gastos_bancarios - impuestos_bancarios + entradas_bancarias - saldo_final);
        $(".diferencia").html(total);
    }

    calc();

    function cargar_conciliacion_pendiente(){
        if(conciliacion_pendiente == 1){
            swal({
                title: 'Conciliación pendiente encontrada!',
                text: 'Un momento, se está actualizando la información.',
                imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                imageWidth: 200,
                imageHeight: 200,
                imageAlt: 'Cargando',
                animation: false,
                showConfirmButton: false
            })

            setTimeout(function(){
                swal.close();
                gastos_bancarios = parseFloat($("#gastos_bancarios").val());
                impuestos_bancarios = parseFloat($("#impuestos_bancarios").val());
                entradas_bancarias = parseFloat($("#entradas_bancarias").val());
                saldo_final = parseFloat($("#saldo_final").val());

                $("#movimientos #conciliar").each(function(index,element){
                        if($(this).is(':checked')){
                            movimientos.push($(this).data('id'));
                            cantidad_movimientos++;
                            switch($(this).data('tipo')){
                                case 1 :
                                    total_movimientos += $(this).data('valor');
                                break;

                                case 2:
                                    total_movimientos -= $(this).data('valor');
                                break;
                            }
                        }
                        $(".movimientos_seleccionados").html(cantidad_movimientos);
                        update_diference();
                })               
            }, 2000);
        }
    }
  
</script>
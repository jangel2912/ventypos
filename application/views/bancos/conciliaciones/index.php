<style>
    .titulo-movimientos{font-weight:bold;}
    #modal-movimientos .modal-title{padding-top:15px;margin-bottom: 10px;}
    .close-modal{ position: absolute;right: 10px;top: 10px;}
    
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Conciliaciones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Conciliaciones", "Conciliaciones");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <?php 
            $message = $this->session->flashdata('message'); 
            if(!empty($message)){ ?>
                <div class="alert alert-success text-center"><?= $message; ?></div>
            <?php }?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <a href="<?php echo site_url("bancos/nueva_conciliacion")?>" data-tooltip="Nueva conciliacion">                        
                <img alt="nuevo banco" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
            </a>                     
        </div>
    </div>
</div>

<div class="block">        
    <div class="head blue">
        <h2><?php echo custom_lang('sima_all_client', "Listado de conciliaciones"); ?></h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="conciliaciones" class="table aTable" style="width:100%">
                <thead>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="10%">Transacción</th>
                        <th width="10%">Gastos bancarios</th>
                        <th width="10%">Impuestos bancarios</th>
                        <th width="10%">Entradas bancarias</th>
                        <th width="10%">Saldo final</th>
                        <th width="10%">Fecha corte</th>
                        <th width="10%">Banco</th>
                        <th width="10%">Movimientos</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(count($conciliaciones) > 0){
                         foreach($conciliaciones as $conciliacion): ?>
                    <tr>
                        <td><?= $conciliacion->fecha_creacion;?></td>
                        <td><?= $conciliacion->transaccion;?></td>
                        <td><?= $conciliacion->gastos_bancarios;?></td>
                        <td><?= $conciliacion->impuestos_bancarios;?></td>
                        <td><?= $conciliacion->entradas_bancarias;?></td>
                        <td><?= $conciliacion->saldo_final;?></td>
                        <td><?= $conciliacion->fecha_corte;?></td>
                        <td><?= $conciliacion->nombre_cuenta;?></td>
                        <td><a id="movimientos" data-movimiento="<?= $conciliacion->id;?>">Ver movimientos</a></td>
                    </tr>
                    <?php  endforeach; } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="10%">Transacción</th>
                        <th width="10%">Gastos bancarios</th>
                        <th width="10%">Impuestos bancarios</th>
                        <th width="10%">Entradas bancarias</th>
                        <th width="10%">Saldo final</th>
                        <th width="10%">Fecha corte</th>
                        <th width="10%">Banco</th>
                        <th width="10%">Movimientos</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-movimientos">
  <div class="" role="document">
    <div class="">
      <div class="">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="close-modal" aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Movimientos asociados</h4>
        <hr>
      </div>
      <div class="modal-body">
      <ul class="list-group lista-movimientos">
     </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>

    var url_movimientos = "<?= site_url('bancos/get_movimientos_conciliacion');?>";
    var movimientos = '';
    var tipo = '';
    $(document).ready(function() {
        $('#conciliaciones').dataTable({
            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],
        });
    });

    $("#conciliaciones #movimientos").each(function(){
            $(this).click(function(){
                $("#modal-movimientos").modal('hide');
                var id_conciliacion = $(this).data('movimiento');
                $.post(url_movimientos,{
                    id_conciliacion : id_conciliacion
                },function(response){
                    var data = JSON.parse(response);
                    movimientos = '<li class="list-group-item titulo-movimientos"> <div class="row">';
                    movimientos += '<div class ="col-md-3">Referencia</div>';
                    movimientos += '<div class ="col-md-3"> Nombre</div>';
                    movimientos += '<div class ="col-md-3">Tipo</div>';
                    movimientos += '<div class ="col-md-3">Valor</div>';
                    movimientos += '</div></li>';
                    if(data.response == 'null'){    
                        movimientos += '<li class="list-group-item">Ningun movimiento encontrado</li>';
                    }else{
                        $.each(data.response,function(index,element){
                            if(element.tipo == 1) tipo = 'Entrada';
                            else tipo = 'Salida';
                            movimientos += '<li class="list-group-item"> <div class="row">';
                            movimientos += '<div class ="col-md-3">'+element.referencia+'</div>';
                            movimientos += '<div class ="col-md-3">'+element.nombre_movimiento+'</div>';
                            movimientos += '<div class ="col-md-3">'+tipo+'</div>';
                            movimientos += '<div class ="col-md-3">$'+parseFloat(element.valor)+'</div>';
                            movimientos += '</div></li>';
                        })
                       
                        $(".lista-movimientos").html(movimientos);
                    }
                })
                $("#modal-movimientos").modal('show');
            })
        })
 
</script>
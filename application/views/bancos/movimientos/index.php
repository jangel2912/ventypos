<style>
    .alert-danger{    background-color: #f2dede;
    border-color: #ebcccc;
    color: #a94442;}
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Movimientos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Movimientos", "Movimientos");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <?php 
            $message = $this->session->flashdata('message'); 
            if(!empty($message)){ ?>
                <div class="alert alert-success text-center"><?= $message; ?></div>
            <?php }?>

        <?php 
            $error = $this->session->flashdata('error'); 
            if(!empty($error)){ ?>
                <div class="alert alert-danger text-center"><?= $error; ?></div>
            <?php }?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2">
            <!--<a href="<?php echo site_url("bancos/nuevo_tipo_movimiento")?>" data-tooltip="Agregar tipo de movimiento">                        
                <img alt="nuevo tipo de movimiento" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
            </a>--> 
            <a href="<?php echo site_url("bancos/nuevo_movimiento")?>" data-tooltip="Nuevo movimiento bancario">                        
                <img alt="nuevo movimiento bancario" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
            </a>                    
        </div>
    </div>
</div>

<div class="block">        
    <div class="head blue">
        <h2><?php echo custom_lang('sima_all_client', "Listado de Movimientos"); ?></h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="movimientos" class="table aTable" style="width:100%">
                <thead>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="5%">Referencia</th>
                        <th width="10%">Nombre movimiento</th>
                        <th width="10%">Tipo movimiento</th>
                        <th width="10%">Valor</th>
                        <th width="10%">Observacion</th>
                        <th width="10%">Nombre banco</th>
                        <th width="10%">Estado</th>
                        <th width="10%">Usuario</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="5%">Referencia</th>
                        <th width="10%">Nombre movimiento</th>
                        <th width="10%">Tipo movimiento</th>
                        <th width="10%">Valor</th>
                        <th width="10%">Observacion</th>
                        <th width="10%">Nombre banco</th>
                        <th width="10%">Estado</th>
                        <th width="10%">Usuario</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url('public/fancybox/jquery.fancybox.css');?>">
<script src="<?php echo base_url('public/fancybox/jquery.fancybox.js');?>"></script>

<script>
    $(document).ready(function() {
        $('#movimientos').DataTable( {
            "bProcessing": true,
            "ajax": '<?php echo site_url("bancos/get_ajax_data_movimientos"); ?>',
            "sPaginationType": "simple_numbers",
            "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],
            "bInfo" : false,
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [9], "bSearchable": false,
                    "mRender": function (data, type, row) {
                        
                        var buttons = "<div class='btnacciones'>";
                            if(row[7] == "Sin conciliar"){
                                buttons += '<a href="<?php echo site_url("bancos/editar_movimiento/"); ?>/' + data + '" class="button default acciones" data-tooltip="Editar Movimiento"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            }else{
                                buttons += '<a data-tooltip="Movimiento conciliado (Imposible editar)" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                            }
                            //buttons += '<a href="<?php echo site_url("bancos/imprimir_movimiento/"); ?>/' + data + '" class="button default acciones btn-print" data-tooltip="Imprimir Movimiento"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>"></div></a>';
                            if(row[7] == "Sin conciliar"){
                                buttons += '<a data-tooltip="Eliminar movimiento" onclick="eliminar_movimiento(('+data+'))" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            }else{
                                buttons += '<a data-tooltip="Movimiento conciliado (Imposible eliminar)" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                            }
                            buttons += "</div>";
                        return buttons;
                    }
                }
            ]
        });
    });

    function eliminar_movimiento(id){
        swal({
            title: 'Estás seguro?',
            text: "Se eliminara el movimiento por completo!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4cae4c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        url: "<?php echo site_url("bancos/eliminar_movimiento")?>",
                        data: { id: id },
                        type: "POST",
                        success: function(data) {	
                            var response = JSON.parse(data);
                            switch(response.message){
                                case 'error':
                                    swal(
                                    'Error inesperado!',
                                    'vuelve a intentarlo en un momento',
                                    'error'
                                    );
                                 break;

                                 case 'success':
                                    swal(
                                    'Redirigiendo!',
                                    'Movimiento eliminado con exito',
                                    'success'
                                    );
                                    setTimeout(function(){
                                        location.href = "<?php echo site_url('bancos/movimientos');?>";
                                    }, 2000); 
                                 break;
                                
                                 case 'movimiento_conciliado':
                                    swal(
                                    'Error!',
                                    'El movimiento se encuentra conciliado',
                                    'error'
                                    );
                                 break;

                                 case 'movimiento_pendiente':
                                    swal(
                                    'Error!',
                                    'El movimiento se encuentra pendiente por conciliación',
                                    'error'
                                    );
                                 break;
                                 
                            }    		
                        }
                    });
                }
        })
    }

    $('.btn-print').fancybox({
    'width' : '85%',
    'height' : '85%',
    'autoScale' : false,
    'transitionIn' : 'none',
    'transitionOut' : 'none',
    'type' : 'iframe'
    });

</script>
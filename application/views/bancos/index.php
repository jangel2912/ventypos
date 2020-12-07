<div class="page-header">    
    <div class="icon">
        <img alt="Bancos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
    
     <h1 class="sub-title"><?php echo custom_lang("Bancos", "Bancos");?></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <?php 
            $message = $this->session->flashdata('message'); 
            if(!empty($message)){ ?>
                <div class="alert alert-success text-cente"><?= $message; ?></div>
            <?php }?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <a href="<?php echo site_url("bancos/nuevo")?>" data-tooltip="Nuevo banco">                        
                <img alt="nuevo banco" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
            </a>                     
        </div>
    </div>
</div>

<div class="block">        
    <div class="head blue">
        <h2><?php echo custom_lang('sima_all_client', "Listado de Bancos"); ?></h2>
    </div>

    <div class="row">
        <div class="col-md-12">
        <table id="bancos" class="table aTable" style="width:100%">
                <thead>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="10%">Nombre cuenta</th>
                        <th width="10%">Número cuenta</th>
                        <th width="10%">Descripción</th>
                        <th width="15%">Saldo inicial</th>
                        <th width="15%">Saldo actual</th>
                        <th width="15%">Fecha actualización</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th width="10%">Fecha creación</th>
                        <th width="10%">Nombre cuenta</th>
                        <th width="10%">Número cuenta</th>
                        <th width="10%">Descripción</th>
                        <th width="15%">Saldo inicial</th>
                        <th width="15%">Saldo actual</th>
                        <th width="15%">Fecha actualización</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        renderTableBank();

        function renderTableBank()
        {
            $('#bancos').dataTable({
                "oLanguage": {
                    "sSearch": "Clientes: ",
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                "bProcessing": true,
                "bServerSide" : true,
                "sAjaxSource": "<?php echo site_url("bancos/get_ajax_data"); ?>",
                "sPaginationType": "simple_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5, 10, 25, 50, 100],
                "bInfo" : false,
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [7], "bSearchable": false,
                        "mRender": function (data, type, row) {
                            console.log(data + ' - '+ type+' - '+row);
                            var buttons = "<div class='btnacciones'>";
                                buttons += '<a href="<?php echo site_url("bancos/editar/"); ?>/' + data + '" class="button default acciones" data-tooltip="Editar Banco"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                buttons += '<a href="<?php echo site_url("bancos/nueva_conciliacion/"); ?>/' + data + '" class="button default acciones" data-tooltip="Conciliar Banco"><div class="icon"><img alt="conciliar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['conciliar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['conciliar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['conciliar']['original'] ?>"></div></a>';
                                buttons += '<a  data-tooltip="Eliminar banco" onclick="eliminar_banco('+data+')" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                                buttons += "</div>";
                            return buttons;
                        }
                    },
                    {
                        "mRender": function ( data, type, row ) {
                            return  data;
                        },
                        "targets": [ 4, 5 ] 
                    }
                ]   
            });
        }
    });

    function eliminar_banco(id){
        swal({
            title: 'Estás seguro?',
            text: "Se eliminara el banco por completo!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4cae4c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        url: "<?php echo site_url("bancos/eliminar_banco")?>/"+id,
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
                                    'Banco eliminado con exito',
                                    'success'
                                    );
                                    setTimeout(function(){
                                        location.href = "<?php echo site_url('bancos');?>";
                                    }, 2000); 
                                 break;

                                 case 'movimientos_asociados':
                                    swal(
                                    'Error!',
                                    'No es posible eliminar el banco ya que cuenta con movimientos asociados',
                                    'error'
                                    );
                                 break;
                            }    		
                        }
                    });
                }
        })
    }
</script>
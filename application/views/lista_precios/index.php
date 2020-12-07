<div class="page-header">    
    <div class="icon">
        <img alt="Libros de Precios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_libro_precio']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Libros de Precios", "Libros de Precios");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <!--<a href="<?php echo site_url("lista_precios/nuevo")?>" class="btn btn-success"><?php echo custom_lang('sima_new_price_list', "Nueva lista");?></a>
            <div style="float: right;">
                <a href="<?php echo site_url('lista_precios/importar') ?>"  class="btn default"><?php echo custom_lang('sima_new_price_list', "Importar libro de precios");?></a>
            </div>-->
            <div class="col-md-6">
                <a href="<?php echo site_url("lista_precios/nuevo")?>" data-tooltip="Nuevo Libro de Precios">                        
                    <img alt="Libro de Precios" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                     
                </a>                    
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("lista_precios/importar")?>" data-tooltip="Importar Libro de Precios">                            
                        <img alt="Importar Libro de Precios" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>">                                                           
                    </a>
                </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_product', "Listado de Libros de Precios");?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="listaTable">
                    <thead>
                        <tr>
                            <th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                            <th width="20%"><?php echo custom_lang('sima_name', "Grupo");?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "Almacén");?></th>
                            <th width="15%"><?php echo custom_lang('price_of_purchase', "Fecha Inicio");?></th>
                            <th width="15%"><?php echo custom_lang('sima_price', "Fecha Fin");?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                         <tr>
                            <th width="20%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                            <th width="20%"><?php echo custom_lang('sima_name', "Grupo");?></th>
                            <th width="20%"><?php echo custom_lang('sima_codigo', "Almacén");?></th>
                            <th width="15%"><?php echo custom_lang('price_of_purchase', "Fecha Inicio");?></th>
                            <th width="15%"><?php echo custom_lang('sima_price', "Fecha Fin");?></th>
                            <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

        $('#listaTable').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("lista_precios/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [
                    {
                        "bSortable": false, "aTargets": [5], "bSearchable": false,

                        "mRender": function ( data, type, row ) {
                            var buttons = "<div class='btnacciones'>";

                                    buttons += '<a data-tooltip="Editar" href="<?php echo site_url("lista_precios/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';
                                    buttons += '<a data-tooltip="Eliminar" href="javascript: void(0)" onclick="eliminarLista('+data+')" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';
                                    buttons += "</div>";
                            return buttons;

                        }

                    }

                ]

        });

    });
    
    function eliminarLista(item_id){
        swal({
            title: '¿Está seguro?',
            text: "Se eliminará por completo este libro de precios",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4cae4c',
            cancelButtonColor: '#e53935',
            confirmButtonText: 'Si, Eliminar'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url("lista_detalle_precios/eliminar_lista_precios")?>/"+item_id,
                    data: { list_id: item_id },
                    type: "POST",
                    success: function(response) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: "Redirigiendo",
                            html: "El libro de precios fue eliminado correctamente",
                            showConfirmButton: false,
                            timer: 1500
                        });                                                   
                                                
                        setTimeout(function(){
                            location.href = "<?php echo site_url('lista_precios');?>";
                        }, 1600);                         	    		
                    }
                });
                
            }
        })
    
}
</script>
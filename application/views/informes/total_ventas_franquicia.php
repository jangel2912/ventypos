<style>
    #reporte tr td 
    {
        white-space: nowrap;
    }
</style>
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
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Total de ventas franquicias");?></h2>
            </div>
        </div>
     </div>
</div>
<div class="row-fluid">
    <div class="span12 well">
        <form id="formulario" action="<?php echo site_url("informes/total_ventas_data_franquicia") ?>" method="POST">
            <div class="row-fluid">
                <div class="span2 form-group">
                    Fecha Inicial :
                    <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>
                </div>
                <div class="span2 form-group">
                    Fecha Final :
                    <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>
                </div>
                <?php if( $is_admin == 't' || $is_admin == 'a') { ?>
                    <div class="span3 form-group">
                        Franquicias :  
                        <?php 
                        echo '<select id="id_franquicia" name="id_franquicia" >';    
                            echo '<option value="0">Todas las franquicias</option>';    
                                foreach($data1['franquicias'] as $f)
                                {   
                                    echo '<option value='.$f['id'].'>'.$f['nombre_empresa'].'</option>';
                                }
                        echo '</select>';    
                        ?>
                    </div>
                <?php } ?>
                <?php if( $is_admin == 't' || $is_admin == 'a') { ?>
                    <div class="span3 form-group">
                        Almacen :  
                        <?php 
                            echo '<select name="almacen">'.
                                      '<option value="0">Todos los alamacenes</option>'.
                                  '</select>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <input type="hidden" name="id_categoria" value="0">
                    <input id="consultar" type="button" value="Consultar" class="btn btn-primary"/> &nbsp; 
                    <input type="submit" value="Exportar a excel" class="btn btn-primary"/>
                </div>
            </div>
      </form>
  </div>
</div>
<div class="row-fluid">
    <div class="span12"> 
        <div class="overflow" id="reporte" style="width:100%; heigt:auto; overflow-x:auto;">

        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {

        $("#id_franquicia").change(function() {
            $("select[name='almacen']").html('');
            $.ajax({
                async: false, //mostrar variables fuera de el function 
                url: "<?php echo site_url('informes/almacenes_franquicias') ?>",
                type: "post",
                dataType: "json",
                data: {  
                    id_franquicia: $("#id_franquicia").val() 
                },
                success: function(data1) {  
                    var html = '<option value="0">Todos los almacenes</option>';
                    for(var e in data1) {
                       html += '<option value="'+data1[e].id+'">'+data1[e].nombre+'</option>';
                    }
                    $("select[name='almacen']").html(html);
                }
            });
        }); 

        $('#consultar').on('click', function(e){
            $.post(
                "<?= site_url('informes/total_ventas_ajax_data_franquicia') ?>",
                $('#formulario').serialize(),
                function(data){
                    console.log(data.length);
                    var ventas = data.total_ventas;
                    if(data.length > 0)
                    {
                        var html = '';
                        $.each(data, function(key, value){
                            html += '<table class="table table-striped">';
                                $.each(value, function(key2, value2){
                                    html += '<tr><th colspan="'+value2['columnas'].length+'">'+key2+'</th></tr>';

                                    //console.log(key2, value2);
                                    html += '<tr>';
                                        $.each(value2['columnas'], function(index, el) {
                                            html += '<th>'+el+'</th>';
                                        });
                                    html += '</tr>';

                                    $.each(value2, function(index, el){
                                        html += '<tr>';
                                            $.each(value2[index], function(index, el){
                                                html += '<td>'+(el == null ? '' : el)+'</td>';
                                            });
                                        html += '</tr>';
                                    });
                                });
                            html += '</table>';
                            console.log(html);
                        });
                    }

                    $('#reporte').html(html);

                    /*
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

                    */
                },
                'json'
            );
        });

    });
    
</script> 
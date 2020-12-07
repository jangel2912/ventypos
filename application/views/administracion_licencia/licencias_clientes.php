<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("mis_licencias", "Mis licencias");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('listado_licencias_adquiridas', "Listado de licencias adquiridas por la empresa");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):
                    $message_type = $this->session->flashdata('message_type');
            ?>

                <div class="alert alert-<?php echo $message_type; ?>">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

            <a href="#moda_nueva_licencia" class="btn" id="btn_nueva_licencia" data-toggle="modal"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_licencia', "Nueva licencia");?></a>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="tb_mis_licencias">

                        <thead>

                            <tr>

                                <th width="30%"><?php echo custom_lang('sima_pan', "Plan");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_inicio', "Fecha inicio");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_fin', "Fecha fin");?></th>
                                <th width="10%"><?php echo custom_lang('dias_renovacion', "dias para renovacion");?></th>
                                <th width="30%"><?php echo custom_lang('almacen', "Almacen");?></th>
                                <th width="30%"><?php echo custom_lang('estado', "Estado");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>
                        <?php foreach ($licencias as $key => $value) { ?>
                               <tr>
                                   <td><?php echo $value->nombre_plan  ?></td>
                                   <td><?php echo $value->fecha_inicio_licencia  ?></td>
                                   <td><?php echo $value->fecha_vencimiento_licencia  ?></td>
                                   <td><?php echo $value->dias_vencimiento  ?></td>
                                   <td><?php echo $value->nombre_almacen  ?></td>
                                   <td><?php echo $value->estado_licencia  ?></td>
                                   <td></td>
                               </tr> 
                        <?php } ?>
                                                    

                        </tbody>

                        <tfoot>

                            <tr>

                                <th width="10%"><?php echo custom_lang('sima_pan', "Plan");?></th>
                                <th width="20%"><?php echo custom_lang('fecha_inicio', "Fecha inicio");?></th>
                                <th width="10%"><?php echo custom_lang('fecha_fin', "Fecha fin");?></th>
                                <th width="10%"><?php echo custom_lang('dias_renovacion', "dias para renovacion");?></th>
                                <th width="10%"><?php echo custom_lang('almacen', "Almacen");?></th>
                                <th width="30%"><?php echo custom_lang('estado', "Estado");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            

        </div>

    </div>
<?php $this->load->view('administracion_licencia/modal_nueva_licencia_cliente'); ?>
<script type="text/javascript">

    $(document).ready(function(){

/*        $('#tb_mis_licencias').dataTable( {

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("administracion_vendty/administracion_clientes/get_ajax_data_licencias");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                      { "bSortable": false, "aTargets": [ 7 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {      

                            var buttons = '<a href="<?php echo site_url("almacenes/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';

                                buttons += '<a href="<?php echo site_url("almacenes/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';

        

                            return buttons;

                        } 

                    }

                ]

        });*/

        $("#tb_mis_licencias").dataTable();

    });

</script>
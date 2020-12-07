<div class="page-header">    
    <div class="icon">
        <img alt="cotizaciones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_presupuesto']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("cotizaciones", "Cotizaciones");?></h1>
</div>
<div class="row-fluid">    
        <div class="col-md-12">
            <div class="block">
                <div id="message">
                </div>      
                <?php
                    $message = $this->session->flashdata('message');
                    $messageerror = $this->session->flashdata('messageerror');
                    if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>
                <?php endif; 
                if(!empty($messageerror)){ ?>
                    <div class="alert alert-error">
                        <?php echo $messageerror;?>
                    </div>
                <?php 
                }
                ?>                
                <?php 
                    $is_admin = $this->session->userdata('is_admin');
                    $permisos = $this->session->userdata('permisos');
                        if(in_array("28", $permisos) || $is_admin == 't'):?>
                        <div class="col-md-6">
                            <a href="<?php echo site_url("presupuestos/nuevo")?>" data-tooltip="Nueva Cotización">                        
                                <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                                
                            </a>                    
                        </div>
                <?php endif;?>              
            </div>
        </div>
    </div>    
<div class="row-fluid">
    <div class="span12">
        <div class="block">    
            <div class="head blue">                
                <h2><?php echo custom_lang('sima_all_quotes', "Listado de Cotizaciones");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="presupuestosTable">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="30%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                             <tr>
                                <th width="10%"><?php echo custom_lang('sima_number', "N&uacute;mero");?></th>
                                <th width="30%"><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="20%"><?php echo custom_lang('sima_total_price', "Precio total");?></th>
                                <th width="20%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="20%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>     
        </div>
    </div>

<script type="text/javascript">

    $(document).ready(function(){

        $('#presupuestosTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("presupuestos/get_ajax_data");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 4 ], "bSearchable": false,

                        "mRender": function ( data, type, row ) {

                            var buttons = "<div class='btnacciones'>";

                            <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a  data-tooltip="Editar" href="<?php echo site_url("presupuestos/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="Editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                             <?php endif;?>

                              buttons += '<a data-tooltip="Facturar" href="<?php echo site_url("ventas/nuevo/?id_cot=");?>'+data+'" class="button default btn-print acciones" target="" title="Facturar" targe ><div class="icon"><img alt="Facturar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['facturar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>" ></div></a>';


                            <?php if(in_array('29', $permisos) || $is_admin == 't'):?>

                                buttons += '<a data-tooltip="Imprimir" href="<?php echo site_url("presupuestos/imprimir/");?>/'+data+'" class="button default btn-print acciones" target="_blank" title="Imprimir" targe ><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';


                            <?php endif;?>                            

                            <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a data-tooltip="Enviar por correo" id="enviar_email" href="<?php echo site_url("presupuestos/enviar_email/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="Enviar por correo" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['envioxcorreo']['original'] ?>" ></div></a>';

                            <?php endif;?>

                             <?php if(in_array('30', $permisos) || $is_admin == 't'):?>

                                buttons += '<a data-tooltip="Eliminar" href="<?php echo site_url("presupuestos/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';

                             <?php endif;?>
                             
                            buttons += "</div>";
                            return buttons;
                        } 

                    }

                ]

        });

    });

    $(document).on("click", "#enviar_email", function(e){
        var id='<?php echo $this->session->userdata('user_id') ?>';       
        var email='<?php echo $this->session->userdata('email') ?>';
        var nombre_empresa="<?php echo (!empty($data['datos_empresa'][0]->nombre_empresa))? $data['datos_empresa'][0]->nombre_empresa : 'No existe nombre' ?>";
        
        mixpanel.identify(id);

        mixpanel.track("Cotizaciones por correo", {
            "$email": email,
            "$empresa": nombre_empresa,
        });        
        
    });

</script>
<style>
    .alert-error{color: #721c24 !important;
    background-color: #f8d7da !important;
    border-color: #f5c6cb !important;}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Caja" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_caja']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cierres de Cajas", "Cierres de Cajas");?></h1>
</div>

<div class="row-fluid">

<?php if($this->session->userdata('caja') == ""): ?>
    <div class="span12">
        <div class="block" >
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="color:#fff;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                <strong>Notificación:</strong> No posee caja aperturada
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="span12">

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');
                $error = $this->session->flashdata('error');

                if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>
                <?php endif; 

                if(!empty($error)):?>
                    <div class="alert alert-error">
                        <?php echo $error;?>
                    </div>
                <?php endif; ?>
            

            <?php $permisos = $this->session->userdata('permisos');

                $is_admin = $this->session->userdata('is_admin');

                if(in_array("46", $permisos) || $is_admin == 't'):?>  
            <?php endif;?>

            <div class="btnderecha">
                <?php if($this->session->userdata('caja') != ""): ?>                
                    <!--<a href="<?php echo site_url("caja/cerrarCaja")?>" class="btn btn-success"><small class="ico-lock icon-white"></small> <?php echo custom_lang('sima_new_bill', "Cerrar Caja");?></a>-->
                    <a href="<?php echo site_url("caja/cerrarCaja")?>" data-tooltip="Cerrar Caja">                       
                        <img alt="cerrarCaja" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['cierre_caja_verde']['original'] ?>">                                                     
                    </a>
                <?php endif;?>
            </div>
            <br><br><br>  
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_provider', "Listado de Cierres de Cajas");?></h2>
            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">

                        <thead>

                            <tr>

                                <th width="5%" ><?php echo custom_lang('sima_name_comercial', "Id");?></th>                                
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Fecha Apertura");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Fecha Cierre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Hora Apertura");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Hora Cierre");?></th>								
								<th width="5%"><?php echo custom_lang('sima_name_comercial', "Usuario");?></th> 								
								<th width="5%"><?php echo custom_lang('sima_name_comercial', "Caja");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Total Cierre");?></th>	
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Arqueo Cierre");?></th>								
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Consecutivo");?></th>								
                                <th width="15%" ><?php echo custom_lang('sima_action', "Acciones");?></th>								
                                <th width="5%"><?php echo custom_lang('sima_action', "-");?></th>	
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th width="5%" ><?php echo custom_lang('sima_name_comercial', "Id");?></th>                                
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Fecha Apertura");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Fecha Cierre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Hora Apertura");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Hora Cierre");?></th>								
								<th width="5%"><?php echo custom_lang('sima_name_comercial', "Usuario");?></th> 								
								<th width="5%"><?php echo custom_lang('sima_name_comercial', "Caja");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Almacén");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Total Cierre");?></th>									
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Arqueo Cierre");?></th>									
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Consecutivo");?></th>									
                                <th width="15%" ><?php echo custom_lang('sima_action', "Acciones");?></th>								
                                <th width="5%"><?php echo custom_lang('sima_action', "-");?></th>	
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <script type="text/javascript">

    var oTable;

    $(document).ready(function(){

       oTable = $('#proveedoresTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],
                "bProcessing": true,//seagregópra que sea progresiva la carga
                "bServerSide": true,//seagregópra que sea progresiva la carga
                "sAjaxSource": "<?php echo site_url("caja/get_ajax_data_listado_cierre");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 11 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {
                                     
                                var buttons = "<div class='btnacciones'>";
                                    buttons += '<a href="<?php echo site_url("caja/imprimir_cierre_productos/");?>/'+data+'/'+row[0]+'/'+row[1]+'/'+row[3]+'" class="button default btn-print acciones" data-tooltip="Cierre de caja x Productos" tag="Cierre de Caja x Productos" target="_blank"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir_productos']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir_productos']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir_productos']['original'] ?>" ></div></a>';
                                
                                 <?php if($is_admin == 't' || in_array("1023", $permisos)):?>
                                    
                                    buttons += '<a href="<?php echo site_url("caja/imprimir_cierre_caja_nuevo/");?>/'+data+'" target="_blank" data-tooltip="Imprimir cierre caja" class="button default acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';
                                    buttons += '<a href="<?php echo site_url("caja/imprimir_cierre_caja_nuevo_tirilla/");?>/'+data+'" class="button default btn-print acciones" data-tooltip="Imprimir Tirilla"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir_tirilla']['original'] ?>" ></div></a>';
                                    
                                 <?php endif;?> 
								 buttons += "</div>";
                            return buttons;

                        } 

                    } , { "bSortable": false, "aTargets": [ 12 ], "bSearchable": false, 

                        "mRender": function ( data1, type, row ) {
                            var buttons = "";
                            var va = data1.substring(0, data1.length-1);
                            var ultimo = data1.substr(0,1);
                            //console.log(data1+"-----"+data1.substr(0,1));
                            var elem = data1.split(',');
                            valor = elem[0];
                            id = elem[1];
                            if(ultimo == 0){
                                buttons += '<a href="<?php echo site_url("caja/re_apertura/");?>/'+id+'" data-tooltip="Cerrar caja"  class="button red acciones"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['cierre_caja']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['cierre_caja']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['cierre_caja']['original'] ?>" ></div></a>';
                            }

                            return buttons;
                        } 
                    }
                ]
        });
        
        $('.btn-print').fancybox({
            'width' : '85%',
            'height' : '85%',
            'autoScale' : false,
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'type' : 'iframe'
        });
    });

</script>
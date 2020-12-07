<div class="page-header">    
    <div class="icon">
        <img alt="Auditoria" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_auditoria']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Auditoria", "Auditoría Inventario");?></h1>
</div>

<div class="row-fluid">
	<div class="col-md-12">
        <div class="block">
            <?php
            $message = $this->session->flashdata('message');

            if (!empty($message)):
                ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
                <?php
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');

                if (in_array("1027", $permisos) || $is_admin == 't'): ?>
                <div class="col-md-2">
                    <a href="<?php echo site_url("auditoria/nuevo")?>" data-tooltip="Nueva Auditoría">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
                    </a>                    
                </div>
                <div class="col-md-2">
                    <a href="<?php echo site_url("auditoria/generar_excel_para_auditoria_view")?>" data-tooltip="Nueva Auditoría desde Excel">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['auditoria_excel']['original'] ?>">                         
                    </a>                    
                </div>
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo site_url("auditoria/index/")?>" data-tooltip="Listado de Auditorías">                            
                        <img alt="Listado de Auditorías" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['auditoria_verde']['original'] ?>">                                                           
                    </a>
                </div>
            </div>
                    <!--<a href="<?php echo site_url("auditoria/nuevo") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_auditoria', "Nueva Auditoría"); ?></a>
                    <a href="<?php echo site_url("auditoria/generar_excel_para_auditoria_view") ?>" class="btn btn-success"><?php echo custom_lang('sima_new_auditoria_excel', "Nueva auditoría desde Excel"); ?></a>
                    <div style="float: right">                
                        <a href="<?php echo site_url("auditoria/index/")?>" class="btn default"><?php echo custom_lang('sima_new_bill', "Histórico Auditorías");?></a>
                    </div>-->
                <?php endif; ?>            
        </div>
    </div>
</div>    
    <div class="row-fluid">
        <div class="span12">
          <div class="block">
            <div class="head blue">               
                <h2><?php echo custom_lang('sima_outstanding_all', "Listado de Auditorías Anuladas");?></h2>
            </div>

            <div class="data-fluid">            
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="auditoriaTable">
                    <thead>
                        <tr>                           
                            <th width="5%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>                            
                            <th width="10%"><?php echo custom_lang('price_active', "Fecha Auditoría"); ?></th>
                            <th width="15%"><?php echo custom_lang('price_active', "Almacén"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Descripción"); ?></th>
                            <th width="5%"><?php echo custom_lang('estado_auditoria', "Estado"); ?></th>
                            <th width="10%"><?php echo custom_lang('estado_auditoria', "Soporte físico"); ?></th>
                            <th width="10%"><?php echo custom_lang('fecha_anulacion', "Fecha Anulación"); ?></th>
                            <th width="10%"><?php echo custom_lang('fecha_anulacion', "Motivo Anulación"); ?></th>
                            <th width="15%"><?php echo custom_lang('usuario_anulacion', "Usuario Anulación"); ?></th>                            
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>                           
                            <th width="5%"><?php echo custom_lang('sima_codigo', "C&oacute;digo"); ?></th>                            
                            <th width="10%"><?php echo custom_lang('price_active', "Fecha Auditoría"); ?></th>
                            <th width="15%"><?php echo custom_lang('price_active', "Almacén"); ?></th>
                            <th width="10%"><?php echo custom_lang('sima_name', "Nombre"); ?></th>                        
                            <th width="10%"><?php echo custom_lang('price_active', "Descripción"); ?></th>
                            <th width="5%"><?php echo custom_lang('estado_auditoria', "Estado"); ?></th>
                            <th width="10%"><?php echo custom_lang('estado_auditoria', "Soporte físico"); ?></th>
                            <th width="10%"><?php echo custom_lang('fecha_anulacion', "Fecha Anulación"); ?></th>
                            <th width="10%"><?php echo custom_lang('fecha_anulacion', "Motivo Anulación"); ?></th>
                            <th width="15%"><?php echo custom_lang('usuario_anulacion', "Usuario Anulación"); ?></th>                            
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>       
</div>
<script type="text/javascript">
	$(document).ready(function(){

        $('#auditoriaTable').dataTable( {

            "bProcessing": true,

            "bServerSide": true,

            "aaSorting": [[ 7, "desc" ]],

            "sAjaxSource": "<?php echo site_url("auditoria/get_ajax_datatable_anuladas");?>",

            "sPaginationType": "full_numbers",

            "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],
            "aoColumnDefs" : [
                { "aTargets":[6],
                    "mRender": function(data, type, row){
                        nombre_archivo = data.substring(data.lastIndexOf("/")+1);
                        var url_archivo = '<a href="<?php echo base_url() ?>'+data+'">'+nombre_archivo+'</a>'; 
                        return url_archivo
                    } 
                }                        
            ]   
        });

    });
	
</script>
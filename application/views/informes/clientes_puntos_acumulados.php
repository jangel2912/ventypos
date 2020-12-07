<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
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
            <?php endif; ?>
            <!--<a href="<?php echo site_url('informes/expuntos_acumulados');?>" target="_blank" class="btn btn-success"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>-->
            
            <div class="col-md-12">
                <div class="col-md-6">                    
                </div>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a data-tooltip="Exportar Excel" href="<?php echo site_url('informes/expuntos_acumulados');?>">                        
                            <img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
                        </a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">       
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Puntos Acumulados por Cliente");?></h2>
            </div>
            
                <div class="data-fluid">
                
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th><?php echo custom_lang('sima_sales', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_sales', "N° identificaci&oacute;n");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Total Puntos");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_sales', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_sales', "N° identificaci&oacute;n");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Total Puntos");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Valor");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
       oTable = $('#informesTable').dataTable( {
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_clientes_puntos_acumulados");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
      
		<?php  
 	    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');     
   
   if( $is_admin == 't' || $is_admin == 'a'){   ?>
        var combo_text = "<?php $combo = "<select id='almacenes'><option value=''>Todos los almacenes</option>"; foreach ($data['almacenes'] as $key => $value){ $combo .= "<option value='".$key."'>".$value."</option>";} $combo .= "</select>"; echo $combo; ?>";
     <?php   }     ?>
             
        $("#filtrar").click(function(){
            var almacen = $("#almacenes").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_plan_separe_productos");?>?almacen="+almacen );
        });

    });
</script>
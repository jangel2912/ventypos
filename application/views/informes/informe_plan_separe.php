<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="col-md-12">
    <div class="col-md-6 btnderecha">
        <div class="col-md-2 col-md-offset-10">
            <a id="ex" data-tooltip="Exportar Excel">                            
                <img alt="Exportar Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                           
            </a>
        </div>
    </div>
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
           
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Productos del Plan Separe");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="informesTable">
                        <thead>
                            <tr>
                                <th><?php echo custom_lang('sima_sales', "Almacén");?></th>
                                <th><?php echo custom_lang('sima_sales', "Cliente");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Cédula");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Nombre del Producto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Precio");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Precio + IVA");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_sales', "Almacén");?></th>
                                <th><?php echo custom_lang('sima_sales', "Cliente");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Cédula");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Nombre del Producto");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Unidades");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Precio");?></th>
                                <th><?php echo custom_lang('sima_sales_taxes', "Precio + IVA");?></th>
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
                "sAjaxSource": "<?php echo site_url("informes/get_ajax_data_plan_separe_productos");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]
        });
     
    <?php  
    $is_admin = $this->session->userdata('is_admin');
    $username = $this->session->userdata('username');     
   
    if( $is_admin == 't' || $is_admin == 'a'){   ?>
        var combo_text = "<?php $combo = "<select id='almacenes'><option value=''>Todos los almacenes</option>"; foreach ($data['almacenes'] as $key => $value){ $combo .= "<option value='".$key."'>".$value."</option>";} $combo .= "</select>"; echo $combo; ?>";
     <?php   }     ?>
        $("<div id='informesTable_length1' class='dataTables_length'><label>"+combo_text+"</label></div>").insertAfter('#informesTable_length');
		

        $("<div id='informesTable_length2' class='dataTables_length'><label><button class='btn btn-success' name='filtrar' id='filtrar' value='Filtrar'><small class='ico-filter icon-white'></small>Filtrar</button></label></div>").insertAfter('#informesTable_length1');
    

        
        $("#filtrar").click(function(){
            var almacen = $("#almacenes").val();
            oTable.fnReloadAjax( "<?php echo site_url("informes/get_ajax_data_plan_separe_productos");?>?almacen="+almacen );
        });


         $("#ex").click(function(e){
            e.preventDefault();
            var almacen = $("#almacenes").val();
            var url = "<?php echo site_url('informes/ex_plan_separe');?>/"+almacen;
            location.href = url;
        })
    });
    mixpanel.track("informe_plan_separe");  
    

   
</script>
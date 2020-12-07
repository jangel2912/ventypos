<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");
?>
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
            $message = $this->session->flashdata('message');
            if(!empty($message)):?>
            <div class="alert alert-success">
                <?php echo $message;?>
            </div>
            <?php endif; ?>    
            <div class="head blue">
                <h2><?php echo custom_lang('Valor de Inventario', "Valor de Inventario");?></h2>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">											
    <div class="span12">	  	 
        <div class="block">
            <div class="head blue">
                <h2>Productos</h2>
            </div>                            
            <?php
            $utilidad = 0;
            foreach($data['almacenes'] as $value){?>	
                <table class="table" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><b><?php echo $value['almacen_nombre'];?></b></td>
                    </tr>
                    
                    <tr>
                        <!--<td>Valor del Inventario : <?php echo $ci->opciones_model->formatoMonedaMostrar($value['valor_inventario']);?></td>-->
                        <td>Valor del Inventario : <?php echo ($value['valor_inventario']);?></td>
                    </tr>	
                    <tr>
                        <!--<td>Valor a Vender : <?php echo $ci->opciones_model->formatoMonedaMostrar($value['valor_venta']);?> </td>-->
                        <td>Valor a Vender : <?php echo ($value['valor_venta']);?> </td>
                    </tr>	
                    <tr>
                        <!--<td>Total de Unidades : <?php echo number_format($value['total_unidades']);?> </td>-->
                        <td>Total de Unidades : <?php echo ($value['total_unidades']);?> </td>
                    </tr>													
                        <tr>
                        <!--<td>Valor Utilidad : <?php echo $ci->opciones_model->formatoMonedaMostrar($utilidad = $value['valor_venta'] - $value['valor_inventario']);?></td>-->
                        <td>Valor Utilidad : <?php echo ($utilidad = $value['valor_venta'] - $value['valor_inventario']);?></td>
                    </tr>	
                    <tr>
                        <td>Procentaje de Utilidad :
                        
                        <?php 
                        $porcen = 0;
                        if($utilidad != 0){
                            $porcen = ($utilidad / $value['valor_venta']) * 100; 
                            }
                        echo ($porcen)." % ";?>
                            </td>
                    </tr>																																						
                </table><br /> 	
            <?php } ?>	 
						
        </div>
    </div>
</div>
<script type="text/javascript">   
    //mixpanel.track("Informe_Valor_Inventario");  
</script>
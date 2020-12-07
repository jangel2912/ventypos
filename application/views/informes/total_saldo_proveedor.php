<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");
?>
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?></h1>
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
                <h2><?php echo custom_lang('ventasxclientes', "Saldo Total por Proveedor");?></h2>
            </div>
                <form action="<?php echo site_url("informes/total_saldo_proveedor_data");?>" method="POST">
                <table>
                    <tr>
					<td width="50%">Proveedor: <input type="text" name="datos_proveedor"  id="datos_proveedor"  value="<?php echo $this->input->post('datos_proveedor');?>"/><input type="hidden" name="proveedor"  id="id_proveedor"  value="<?php echo $this->input->post('proveedor');?>" / > </td>	
                        <td width="20%"><br /> <input type="submit" value="Consultar" class="btn btn-success"/></td>	

                    </tr>					
                </table>
				<input type="hidden" name="producto"  id="descripcion"/>
            </form>
            </div>
        </div>
    </div>
	<?php $total=0;  ?>		
<div class="row-fluid">
    <div class="span12">
        <div class="block">
        <?php if(isset($proveedor)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th><b>Proveedor</b></th>
							<th><b>Saldo</b></th>	
							<th><b>&nbsp; </th>
                        </tr>	
						
                           <?php  
						     
						  foreach($data['total_ventas'] as $value){
			                
							 ?>  
                            <tr>  
                                <td width="30%"> <?php  echo $value['nombre_proveedor'];?></td>                       
                                <td><?php echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($value['total_venta'] - $value['saldo_proveedor']);?></td>
                                <td><b>&nbsp; </td>
                            </tr>                                                                                                                                                                                                                     <?php  }  ?> 
										   				   	 
				  </table> 		  
			 <?php } ?>
    </div>
    </div>
    </div>

<script type="text/javascript">	


        $("#datos_proveedor").autocomplete({

			source: "<?php echo site_url("proveedores/get_ajax_proveedores"); ?>",

			minLength: 1,

			select: function( event, ui ) {

                $("#id_proveedor").val(ui.item.id);
				$("#otros_datos").val(ui.item.descripcion);

			}

		});
//mixpanel.track("Informe_Saldo_de_Proveedor");  
</script>	
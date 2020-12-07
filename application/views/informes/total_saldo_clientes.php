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
				    $is_admin = $this->session->userdata('is_admin');
		 $username = $this->session->userdata('username');	
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Saldo Total por Clientes");?></h2>
            </div>
                <form action="<?php echo site_url("informes/total_saldo_clientes_data");?>" method="POST">
                <table>
                    <tr>
					<td width="50%">Cliente: <input type="text" name="datos_clientes"  id="datos_clientes"  value="<?php echo $this->input->post('datos_clientes');?>"/><input type="hidden" name="cliente"  id="id_clientes"  value="<?php echo $this->input->post('cliente');?>" / > </td>	
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
        <?php if(isset($cliente)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th><b>Cliente</b></th>
							<th><b>Saldo</b></th>	
							<th><b>&nbsp; </th>
                        </tr>	
						
            <?php  foreach($data['total_ventas'] as $value)
            {
                $valor = $value['total_venta'] - $value['saldo_cliente'];
                if($valor <= 49)
                {
                    $valor = 0;
                }
                ?>  
            <tr>  
                <td width="30%"> <?php  echo $value['nombre_cliente'];?></td>                       
                <td><?php  echo $data_empresa['data']['simbolo'].' '.$ci->opciones_model->formatoMonedaMostrar($valor);?></td>
                <td><b>&nbsp; </td>
            </tr>                                                                                                                                                                                                                     <?php  }  ?> 
										   				   	 
        </table> 		  
			 <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">	


        $("#datos_clientes").autocomplete({

			source: "<?php echo site_url("clientes/get_ajax_clientes"); ?>",

			minLength: 1,

			select: function( event, ui ) {

                $("#id_clientes").val(ui.item.id);
				$("#otros_datos").val(ui.item.descripcion);

			}

		});
    mixpanel.track("Informe_total_saldo_clientes");  
</script>	
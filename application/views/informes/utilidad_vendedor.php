<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
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
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('Utiliti', "Comisi&oacute;n de Vendedor por Utilidad");?></h2>
            </div>
                <form action="<?php echo site_url("informes/utilidad_vendedor_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha Inicial : <input type="text" name="dateinicial" value="<?php echo $this->input->post('dateinicial');?>" class="datepicker"/>  </td>
                        <td width="30%">Fecha Final : <input type="text" name="datefinal" value="<?php echo $this->input->post('datefinal');?>" class="datepicker"/>   </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>	
						<td width="30%">Almacen :  	<?php 
	echo "<select  name='almacen' >";    
    echo "<option value='0'>Todos los Almacenes</option>";    
    foreach($data1['almacen'] as $f){
        if($f->id == $this->input->post('almacen')){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";
    ?>    </td>
							<?php } ?>	
                        <td width="30%"><br/> <input type="submit" value="Enviar" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
											
    <div class="span12 block">
        <?php if(isset($fechafinal) && !empty($fechainicial)){?>

	                 <table class="table" width="100%" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="20%"><?php echo custom_lang('sima_name', "Nombre Vendedor");?></th>
                                <th width="20%"><?php echo custom_lang('sima_recount_invoices', "Almacen");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Utilidad");?></th>
                                <th width="4%"><?php echo custom_lang('sima_sales_taxes', "Porciento");?></th>
                                <th width="20%"><?php echo custom_lang('sima_sales_taxes', "Valor Total de Comision");?></th>
                            </tr>	
				<?php 
              $total=0;
				foreach($data['total_vendedor'] as $value){?>	
                            <tr>
                                <td><?php echo $value['nombre_vendedor'];?></td>
                                <td><?php echo $value['nombre_almacen'];?></td>
                                <td>$ <?php echo $value['total_venta'];?></td>
                                <td><?php echo $value['comision'];?></td>
                                <td>$ <?php echo $value['total_comision'];?></td>
                            </tr>								
				   <?php } ?> 
				  </table> 
		 <?php } ?>			
    </div>
    </div>
<script type="text/javascript">
    
</script>
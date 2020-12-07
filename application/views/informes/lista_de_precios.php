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
	            if(!empty($message)):
	        ?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
			<div id="mensaje" class="alert alert-error hidden"></div>  
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Listas de Precios");?></h2>
            </div>
            <div class="row">
            	<br>
            </div>
			<form class="form-inline" action="<?php echo site_url("informes/informe_lista_precios_excel");?>" method="POST"  id="lista" >                
               
                <div class="form-group">
                    <label for="exampleInputEmail3">Lista de Precios</label>
                    <?php 
						echo "<select  name='lista_precios' class='form-control' >";    
						echo "<option value=''>Todas las listas</option>";    
						foreach($data1['lista_precios'] as $l)
						{
							if($l->id == $this->input->post('lista_precios')){
								$selected = " selected=selected ";
							} else {
								$selected = "";
							}        
							echo "<option $selected value=".$l->id.">" . $l->nombre . "</option>";
						}    
						echo "</select>";
					?>
                </div>
                                
                <a data-tooltip="Exportar Excel" onclick="$('#lista').submit()">                        
					<img alt="Exportar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
				</a> 
            </form>

			<!--
            <form id="lista" action="<?php echo site_url("informes/informe_lista_precios_excel");?>" method="POST">
                <div class="row-fluid">
                	<div class="span3">
                		<label>Lista de Precios </label>
                		<?php 
							echo "<select  name='lista_precios' >";    
							echo "<option value=''>Todas las listas</option>";    
						    foreach($data1['lista_precios'] as $l)
						    {
						        if($l->id == $this->input->post('lista_precios')){
						            $selected = " selected=selected ";
						        } else {
						            $selected = "";
						        }        
						        echo "<option $selected value=".$l->id.">" . $l->nombre . "</option>";
						    }    
						    echo "</select>";
						?>
                	</div>
                	<div class="span2">
                		<br>
                		<input type="submit" value="Exportar" class="btn btn-success"/>						
                	</div>
                	<div class="span3">
                		<input type="hidden" name="almacen" value="">
                	</div>
                </div>
        	</form>-->
        </div>
    </div>
</div>
<script>
	$(function(e){

		var sethrefexcel = function()
		{
			var url = $('#excel').data('href');
			var query = url+'/'+$('input[name="fecha"]').val()+'/'+$('select[name="almacen"]').val();
			$('#excel').prop('href', query);
		}

		$('input[name="fecha"], select[name="almacen"]').on('change', function(e)
		{
			sethrefexcel();
		});

		sethrefexcel();
	});

	//mixpanel.track("informe_lista_de_precios");
</script>
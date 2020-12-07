<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("clientes", "Clientes");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h4>Bienvenido sigua los siguiente pasos para cargar los clientes desde Excel </h4> 
		<br />
        <h5>1. De click en la siguiente enlace para descargar la plantilla de excel &nbsp;&nbsp;<a href="<?php echo base_url("/uploads1/Plantilla Clientes.xlsx"); ?>">CLICK AQUI</a>&nbsp;&nbsp; llamada Plantilla Clientes.</h5>
		<table>
		<tr>
		<td>
		
		<table  border="1" cellpadding="0" cellspacing="0" width="400px" >
		<tr>
		<td class="head blue"><h5>&nbsp;&nbsp;Grupos</h5></td>
		<?php foreach($data["grupo_clientes"] as $value){  ?>
		<tr><td class="odd">&nbsp;&nbsp;<?php echo  $value->nombre; ?></td></tr>
		<?php  }  ?>	
		</table>	
        
		</td>
		</tr>
		</table>				
        <h5>2. Abra el archivo Plantilla Clientes que descargo a su computador y comience a ingresar los clientes en el Excel que descargo como se muestra en la imagen de abajo, recuerde que loss grupos de los clientes tienen que ser igual a los que muestran en las tabla que los  <a href="../clientes/grupos" >click aqui si nesecita ingresar un nuevo grupo o editar los grupos a los que van a pertenecer los clientes</a>.</h5> 	                                        
       <img src="<?php echo base_url("/public/img/");?>/csv_clientes_1.png" width="800px" />
        <h5>3. Guardar el archivo como CSV (delimitado por comas) como lo vemos en la siguiente imagen. </h5> 	   
       <img src="<?php echo base_url("/public/img/");?>/csv_clientes_2.png" width="980px" />
	  <h5> 4. Siempre dar click en si en la pantalla que nos muestra, como lo vemos en la siguiente imagen.</h5> 
       <img src="<?php echo base_url("/public/img/");?>/csv_clientes_3.png" width="980px" />	   	
        <h5>4. Click en el boton buscar selecione la plantilla de excel que se encuentra en su computador con los clientes que ingreso en el excel.</h5> 	
        <h5>5. Por ultimo click en enviar.</h5> 		   	
    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

        <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-error">

                    <?php echo $message;?>

                </div>

                <?php endif; ?>

                            <?php echo validation_errors(); ?>

                            <div class="data-fluid">

                                <?php echo form_open_multipart("clientes/import_excel", array("id" =>"validate"));?>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_file', "Archivo");?>:<br/>

                                    </div>

                                    <div class="span9">                            

                                        <div class="input-append file">

                                            <input type="file" name="archivo"/>

                                            <input type="text"/>

                                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>

                                        </div> 

                                         <?php echo $data['data']['upload_error']; ?>

                                    </div>

                                </div> 

                                <div class="toolbar bottom tar">

                                    <div class="btn-group">

                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                                    </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>